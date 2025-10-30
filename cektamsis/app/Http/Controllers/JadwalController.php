<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Jadwal;
use App\Models\AbsensiSekolah;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class JadwalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $guru = Guru::where('user_id', $user->id)
            ->with(['mapel', 'kelas'])
            ->first();
        if (!$guru) {
            return Inertia::render('Guru/Dashboard', [
                'guru'       => null,
                'jadwalData' => [],
            ]);
        }

        $jadwal = Jadwal::with(['mapel', 'kelas'])
            ->where('id_guru', $guru->id_guru)
            ->get();

        // Ambil nama kelas yang guru ajar
        $teacherClasses = $jadwal->pluck('kelas.nama_kelas')->unique();

        $jadwalFormatted = $jadwal->map(function ($item) {
            // Tentukan status jadwal berdasarkan hari dan finalize
            $isActive = $item->isActiveToday();
            $status = $isActive ? 'aktif' : 'tutup';

            return [
                'id_jadwal'      => $item->id_jadwal,
                'mata_pelajaran' => $item->mapel->nama_mapel ?? '-',
                'nama_kelas'     => $item->kelas->nama_kelas ?? '-',
                'kelas_id'       => $item->kelas->id_kelas ?? null,
                'hari'           => $item->hari,
                'lantai'         => $item->lantai,
                'ruang'          => $item->ruang,
                'jam_mulai'      => $item->jam_mulai,
                'jam_selesai'    => $item->jam_selesai,
                'guru_id'        => $item->id_guru,
                'status'         => $status,
                'status_aktif'   => $isActive,
                'last_finalized_at' => $item->last_finalized_at,
                'idenc' => Crypt::encryptString(
                    $item->id_jadwal . '|' . $item->id_guru . '|' . $item->id_qr . '|' . now()->addMinutes(5)->format('Y-m-d H:i:s')
                )
            ];
        });

        // Ambil HANYA kelas yang guru ajar dengan siswa count
        $kelasList = \App\Models\Kelas::with('siswa')
            ->whereIn('nama_kelas', $teacherClasses)
            ->select('id_kelas', 'nama_kelas', 'total_siswa')
            ->get()
            ->map(function ($kelas) {
                return [
                    'id' => $kelas->id_kelas,
                    'nama_kelas' => $kelas->nama_kelas,
                    'total_siswa' => $kelas->siswa->count(),
                    'siswa_count' => $kelas->siswa->count(),
                ];
            });

        $todayDate = \Carbon\Carbon::today('Asia/Jakarta')->toDateString();

        // QUERY 1: Untuk Stats & Recent (hari ini + semua siswa termasuk belum absen)
        $absensiHariIni = DB::table('siswa')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->whereIn('kelas.nama_kelas', $teacherClasses)
            ->leftJoin('absensi_pelajaran', function ($join) use ($todayDate) {
                $join->on('siswa.id_siswa', '=', 'absensi_pelajaran.id_siswa')
                     ->whereDate('absensi_pelajaran.waktu_scan', '=', $todayDate);
            })
            ->leftJoin('jadwal', 'absensi_pelajaran.id_jadwal', '=', 'jadwal.id_jadwal')
            ->leftJoin('mapel', 'jadwal.id_mapel', '=', 'mapel.id_mapel')
            ->leftJoin('absensi_sekolah as ask', function ($join) use ($todayDate) {
                $join->on('siswa.id_siswa', '=', 'ask.id_siswa')
                     ->where('ask.tanggal', '=', $todayDate);
            })
            ->select(
                'siswa.id_siswa',
                'siswa.nama as nama_siswa',
                'kelas.nama_kelas',
                'mapel.nama_mapel',
                'jadwal.hari',
                'jadwal.lantai',
                'jadwal.ruang',
                'absensi_pelajaran.waktu_scan',
                'absensi_pelajaran.status',
                'absensi_pelajaran.keterangan',
                'ask.status as sekolah_status',
                'ask.keterangan as sekolah_keterangan'
            )
            ->get()
            ->map(function ($row) use ($todayDate) {
                // Cek apakah ada absensi pelajaran hari ini
                $hasAbsensiToday = !empty($row->waktu_scan) && 
                                   date('Y-m-d', strtotime($row->waktu_scan)) === $todayDate;

                $statusPel = null;
                $ketPel = null;
                $waktu = 'Belum Absen';

                if ($hasAbsensiToday) {
                    // Ada absensi pelajaran hari ini
                    $statusPel = $row->status;
                    $ketPel = $row->keterangan;
                    $waktu = date('H:i', strtotime($row->waktu_scan));
                } else {
                    // Tidak ada absensi pelajaran, cek absensi sekolah
                    $sekolahStatus = strtolower((string)($row->sekolah_status ?? ''));
                    if (in_array($sekolahStatus, ['izin', 'sakit'], true)) {
                        // Gunakan status dari absensi sekolah
                        $statusPel = ucfirst($sekolahStatus); // Izin atau Sakit
                        $ketPel = $row->sekolah_keterangan ?? ucfirst($sekolahStatus);
                        $waktu = '-'; // Tidak ada waktu karena izin/sakit
                    } else {
                        // Belum absen sama sekali
                        $statusPel = 'Belum Absen';
                        $ketPel = null;
                    }
                }

                return [
                    'name'        => $row->nama_siswa,
                    'class'       => $row->nama_kelas,
                    'subject'     => $row->nama_mapel ?? '-',
                    'hari'        => $row->hari ?? 'senin',
                    'lantai'      => $row->lantai ?? '-',
                    'ruang'       => $row->ruang ?? '-',
                    'date'        => $todayDate,
                    'time'        => $waktu,
                    'status'      => $statusPel,
                    'keterangan'  => $ketPel,
                ];
            });

        // QUERY 2: Untuk Modal History (semua waktu, hanya yang sudah absen)
        $absensiHistory = DB::table('absensi_pelajaran')
            ->join('jadwal', 'absensi_pelajaran.id_jadwal', '=', 'jadwal.id_jadwal')
            ->join('siswa', 'absensi_pelajaran.id_siswa', '=', 'siswa.id_siswa')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->join('mapel', 'jadwal.id_mapel', '=', 'mapel.id_mapel')
            ->whereIn('kelas.nama_kelas', $teacherClasses)
            ->leftJoin('absensi_sekolah as ask', function ($join) {
                $join->on('siswa.id_siswa', '=', 'ask.id_siswa')
                     ->on('ask.tanggal', '=', DB::raw('DATE(absensi_pelajaran.waktu_scan)'));
            })
            ->select(
                'siswa.id_siswa',
                'siswa.nama as nama_siswa',
                'kelas.nama_kelas',
                'mapel.nama_mapel',
                'jadwal.hari',
                'jadwal.lantai',
                'jadwal.ruang',
                'absensi_pelajaran.waktu_scan',
                'absensi_pelajaran.status',
                'absensi_pelajaran.keterangan',
                'ask.status as sekolah_status',
                'ask.keterangan as sekolah_keterangan'
            )
            ->orderBy('absensi_pelajaran.waktu_scan', 'desc')
            ->get()
            ->map(function ($row) {
                $statusPel = $row->status ?? null;
                $ketPel = $row->keterangan ?? null;

                if (!$statusPel || strtolower($statusPel) === 'belum absen') {
                    $sekolahStatus = strtolower((string)($row->sekolah_status ?? ''));
                    if (in_array($sekolahStatus, ['izin', 'sakit'], true)) {
                        $statusPel = $row->sekolah_status;
                        $ketPel = $row->sekolah_keterangan ?? ucfirst($row->sekolah_status);
                    }
                }

                return [
                    'name'        => $row->nama_siswa,
                    'class'       => $row->nama_kelas,
                    'subject'     => $row->nama_mapel ?? '-',
                    'hari'        => $row->hari ?? 'senin',
                    'lantai'      => $row->lantai ?? '-',
                    'ruang'       => $row->ruang ?? '-',
                    'date'        => $row->waktu_scan ? date('Y-m-d', strtotime($row->waktu_scan)) : date('Y-m-d'),
                    'time'        => $row->waktu_scan ? date('H:i', strtotime($row->waktu_scan)) : 'Belum Absen',
                    'status'      => $statusPel ?? 'Belum Absen',
                    'keterangan'  => $ketPel,
                ];
            });

        $today = Carbon::today();

        // Hitung jumlah status per hari ini saja (untuk statistik dashboard)
        $totalsakit = AbsensiSekolah::whereDate('tanggal', $today)
            ->where('status', 'sakit')
            ->count();

        $totalizin = AbsensiSekolah::whereDate('tanggal', $today)
            ->where('status', 'izin')
            ->count();

        $totalalfa = AbsensiSekolah::whereDate('tanggal', $today)
            ->where('status', 'alfa')
            ->count();

        // Get count of unique lantai dan ruang dari jadwal
        $lantaiCount = $jadwal->pluck('lantai')->unique()->count();
        $ruangCount = $jadwal->pluck('ruang')->unique()->count();

        return Inertia::render('Guru/Dashboard', [
            'totalsakit' => $totalsakit,
            'totalizin'  => $totalizin,
            'totalalfa'  => $totalalfa,
            'lantai'     => $lantaiCount,
            'ruang'      => $ruangCount,
            'guru' => [
                'id'    => $guru->id_guru,
                'nama'  => $guru->nama,
                'nip'   => $guru->nip,
                'mapel' => $guru->mapel->nama_mapel ?? null,
                'kelas' => $guru->kelas->nama_kelas ?? null,
            ],
            'jadwalData'  => $jadwalFormatted,
            'kelasData'   => $kelasList,
            'absensiData' => $absensiHariIni, // Untuk Stats & Recent (hari ini)
            'absensiHistory' => $absensiHistory, // Untuk Modal (semua waktu)
        ]);
    }
}