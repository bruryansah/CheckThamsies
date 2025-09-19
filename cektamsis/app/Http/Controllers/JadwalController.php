<?php

namespace App\Http\Controllers;

use App\Models\Guru;

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
            return [
                'id_jadwal'      => $item->id_jadwal,
                'mata_pelajaran' => $item->mapel->nama_mapel ?? '-',
                'nama_kelas'     => $item->kelas->nama_kelas ?? '-',
                'kelas_id'       => $item->kelas->id_kelas ?? null,
                'tanggal'        => $item->hari,
                'jam_mulai'      => $item->jam_mulai,
                'jam_selesai'    => $item->jam_selesai,
                'guru_id'        => $item->id_guru,
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
                    'total_siswa' => $kelas->siswa->count(), // Real count
                    'siswa_count' => $kelas->siswa->count(), // Fallback name
                ];
            });

        $absensi = DB::table('siswa')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->leftJoin('absensi_pelajaran', function ($join) {
                $join->on('siswa.id_siswa', '=', 'absensi_pelajaran.id_siswa');
            })
            ->leftJoin('jadwal', 'absensi_pelajaran.id_jadwal', '=', 'jadwal.id_jadwal')
            ->leftJoin('mapel', 'jadwal.id_mapel', '=', 'mapel.id_mapel')
            ->select(
                'siswa.id_siswa',
                'siswa.nama as nama_siswa',
                'kelas.nama_kelas',
                'mapel.nama_mapel',
                'absensi_pelajaran.waktu_scan',
                'absensi_pelajaran.status'
            )
            ->get()
            ->map(function ($row) {
                return [
                    'name'   => $row->nama_siswa,
                    'class'  => $row->nama_kelas,
                    'subject' => $row->nama_mapel ?? '-',
                    'date'   => $row->waktu_scan ? date('Y-m-d', strtotime($row->waktu_scan)) : date('Y-m-d'),
                    'time'   => $row->waktu_scan ? date('H:i', strtotime($row->waktu_scan)) : 'Belum Absen',
                    'status' => $row->status ?? 'Belum Absen',
                ];
            });

        return Inertia::render('Guru/Dashboard', [
            'totalsakit' => AbsensiSekolah::where('status', 'sakit')->count(),
            'totalizin' => AbsensiSekolah::where('status', 'izin')->count(),
            'totalalfa' => AbsensiSekolah::where('status', 'alfa')->count(),
            'lantai' => Jadwal::where('lantai')->count(),
            'ruang' => Jadwal::where('ruang')->count(),
            'guru' => [
                'id'   => $guru->id_guru,
                'nama' => $guru->nama,
                'nip'  => $guru->nip,
                'mapel' => $guru->mapel->nama_mapel ?? null,
                'kelas' => $guru->kelas->nama_kelas ?? null,
            ],
            'jadwalData' => $jadwalFormatted,
            'kelasData'   => $kelasList,
            'absensiData' => $absensi,

        ]);
    }
}
