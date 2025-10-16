<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\AbsensiSekolah;
use App\Models\AbsensiPelajaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AbsenController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->with('kelas')->first();

        // Default values jika siswa tidak ditemukan
        $kehadiransekolah = 0;
        $totalAbsensi = 0;
        $persentaseKehadiran = 0;
        $totalSakit = 0;
        $totalIzin = 0;
        $totalAlfa = 0;
        $recentAttendance = [];
        $jadwalData = [];

        if ($siswa) {
            // Statistik absensi sekolah
            $totalAbsensi = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->count();
            $kehadiransekolah = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
                ->whereIn('status', ['hadir', 'terlambat'])
                ->count();
            $totalSakit = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
                ->where('status', 'sakit')
                ->count();
            $totalIzin = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
                ->where('status', 'izin')
                ->count();
            $totalAlfa = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
                ->where('status', 'alfa')
                ->count();
            $persentaseKehadiran = $totalAbsensi > 0 ? round(($kehadiransekolah / $totalAbsensi) * 100, 1) : 0;

            // Recent attendance pelajaran
            $recentAttendance = AbsensiPelajaran::with(['jadwal.mapel'])
                ->where('id_siswa', $siswa->id_siswa)
                ->orderBy('waktu_scan', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($attendance) {
                    return [
                        'name' => $attendance->jadwal->mapel->nama_mapel ?? 'Unknown',
                        'time' => $attendance->waktu_scan ? $attendance->waktu_scan->format('d-M-Y H:i') : 'N/A',
                        'status' => ucfirst($attendance->status ?? 'N/A'),
                        'color' => match ($attendance->status) {
                            'hadir' => 'green',
                            'terlambat' => 'orange',
                            'izin', 'sakit' => 'purple',
                            'alfa' => 'red',
                            default => 'gray'
                        },
                    ];
                })->toArray();

            // Ambil jadwal untuk kelas siswa dan hari ini
            $today = Carbon::today('Asia/Jakarta');
            $hari = strtolower($today->locale('id')->dayName); // misal: "selasa"
            $jadwalData = Jadwal::with(['mapel', 'kelas','guru'])
                ->where('id_kelas', $siswa->id_kelas)
                ->whereRaw('LOWER(hari) = ?', [$hari])
                ->get()
                ->map(function ($item) use ($today) {
                    // Buat idenc dengan format id_kelas|id_guru|id_qr|expiredAt
                    $idenc = Crypt::encryptString(
                        "{$item->id_kelas}|{$item->id_guru}|{$item->id_qr}|" . now()->addMinutes(5)->format('Y-m-d H:i:s')
                    );

                    // Cek apakah jadwal sudah ditutup
                    $sudahDitutup = AbsensiPelajaran::where('id_jadwal', $item->id_jadwal)
                        ->whereDate('waktu_scan', $today)
                        ->where('keterangan', 'like', 'Otomatis alfa%')
                        ->exists();

                    return [
                        'id' => $item->id_jadwal,
                        'idenc' => $idenc,
                        'mata_pelajaran' => $item->mapel->nama_mapel ?? 'Unknown',
                        'nama_kelas' => $item->kelas->nama_kelas ?? '-',
                        'nama_guru' => $item->guru->nama ?? '-',
                        'hari' => $item->hari,
                        'jam_mulai' => $item->jam_mulai,
                        'jam_selesai' => $item->jam_selesai,
                        'lantai' => $item->lantai,
                        'ruang' => $item->ruang,
                        'status_aktif' => !$sudahDitutup,
                    ];
                })->toArray();

            // Log untuk debugging
            Log::info('Jadwal Data untuk Siswa Dashboard:', [
                'siswa_id' => $siswa->id_siswa,
                'id_kelas' => $siswa->id_kelas,
                'hari' => $hari,
                'jadwal_count' => count($jadwalData),
                'jadwal_sample' => $jadwalData ? array_slice($jadwalData, 0, 2) : [],
            ]);

            // Logika alfa otomatis untuk absensi sekolah
            $absensiHariIni = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
                ->whereDate('tanggal', $today)
                ->first();

            if (!$absensiHariIni) {
                $now = Carbon::now('Asia/Jakarta');
                $timeInMinutes = $now->hour * 60 + $now->minute;
                $endTerlambat = 13 * 60 + 40; // 13:40

                if ($timeInMinutes > $endTerlambat) {
                    AbsensiSekolah::create([
                        'id_siswa' => $siswa->id_siswa,
                        'tanggal' => $today,
                        'jam_masuk' => $now->format('H:i:s'),
                        'latitude_in' => 0,
                        'longitude_in' => 0,
                        'status' => 'alfa',
                        'keterangan' => 'Absen otomatis alfa setelah 13:40',
                    ]);

                    Log::info('Absen alfa otomatis diterapkan:', [
                        'id_siswa' => $siswa->id_siswa,
                        'waktu' => $now->toDateTimeString(),
                    ]);
                }
            }
        } else {
            Log::error('Siswa tidak ditemukan untuk user_id:', ['user_id' => $user->id]);
        }

        return Inertia::render('User/Dashboard', [
            'auth' => [
                'user' => $user,
                'siswa' => $siswa ? [
                    'id_siswa' => $siswa->id_siswa,
                    'nama' => $siswa->nama,
                    'id_kelas' => $siswa->id_kelas,
                    'nama_kelas' => $siswa->kelas->nama_kelas ?? 'Unknown',
                ] : null,
            ],
            'kehadiransekolah' => $kehadiransekolah,
            'persentaseKehadiran' => $persentaseKehadiran,
            'totalAbsensi' => $totalAbsensi,
            'totalSakit' => $totalSakit,
            'totalIzin' => $totalIzin,
            'totalAlfa' => $totalAlfa,
            'recentAttendance' => $recentAttendance,
            'jadwalData' => $jadwalData,
        ]);
    }

    public function checkIn(Request $request)
    {
        $userId = Auth::id();
        Log::info('User ID saat check-in:', ['user_id' => $userId]);

        if (!Auth::check()) {
            Log::error('Pengguna tidak terautentikasi');
            return back()->withErrors(['message' => '❌ Sesi tidak valid, silakan login kembali!']);
        }

        $siswa = Siswa::where('user_id', $userId)->first();

        if (!$siswa) {
            Log::error('Siswa tidak ditemukan untuk user_id:', ['user_id' => $userId]);
            return back()->withErrors(['message' => "❌ Data siswa tidak ditemukan untuk user_id: $userId. Silakan hubungi admin!"]);
        }

        $absensiHariIni = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        if ($absensiHariIni) {
            return back()->with('error', 'Anda sudah absen masuk hari ini');
        }

        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required|in:hadir,terlambat,izin,sakit',
            'description' => 'nullable|string|max:1000',
        ]);

        $now = Carbon::now('Asia/Jakarta');
        $timeInMinutes = $now->hour * 60 + $now->minute;
        $startHadir = 6 * 60 + 40; // 06:40
        $endHadir = 7 * 60 + 10;   // 07:10
        $endTerlambat = 13 * 60 + 40; // 13:40

        // Tentukan status berdasarkan waktu jika status awal adalah 'hadir'
        $status = $validated['status'];
        if ($status === 'hadir') {
            if ($timeInMinutes > $endTerlambat) {
                return back()->with('error', 'Waktu sudah melewati 13:40, status alfa otomatis diterapkan.');
            } elseif ($timeInMinutes > $endHadir && $timeInMinutes <= $endTerlambat) {
                $status = 'terlambat';
            }
        }

        // Validasi keterangan untuk status tertentu
        if (in_array($status, ['izin', 'sakit']) && empty($validated['description'])) {
            return back()->withErrors(['description' => 'Keterangan diperlukan untuk status Izin atau Sakit']);
        }

        AbsensiSekolah::create([
            'id_siswa' => $siswa->id_siswa,
            'tanggal' => Carbon::today(),
            'jam_masuk' => $now->format('H:i:s'),
            'latitude_in' => $validated['latitude'],
            'longitude_in' => $validated['longitude'],
            'status' => $status,
            'keterangan' => $validated['description'] ?? '',
        ]);

        Log::info('Check-in berhasil:', [
            'id_siswa' => $siswa->id_siswa,
            'status' => $status,
            'keterangan' => $validated['description'] ?? '',
            'waktu' => $now->toDateTimeString(),
        ]);

        return back()->with('success', 'Absen masuk berhasil sebagai ' . ucfirst($status));
    }

    public function checkOut(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();

        if (!$siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $absensi = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        if (!$absensi) {
            return back()->with('error', 'Anda belum absen masuk hari ini');
        }

        if ($absensi->jam_keluar) {
            return back()->with('error', 'Anda sudah absen pulang hari ini');
        }

        if ($absensi->status === 'alfa') {
            return back()->with('error', 'Anda tidak dapat absen pulang karena status Anda adalah Alfa.');
        }

        if (in_array($absensi->status, ['izin', 'sakit'])) {
            return back()->with('error', 'Anda tidak perlu absen pulang karena status Anda adalah ' . ucfirst($absensi->status));
        }

        $now = Carbon::now('Asia/Jakarta');
        $cutoffTime = Carbon::today('Asia/Jakarta')->setHour(13)->setMinute(30);
        $isEarlyCheckout = $now->lessThan($cutoffTime);

        $rules = [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ];

        if ($isEarlyCheckout) {
            $rules['keterangan'] = 'required|string|max:1000';
        }

        $validated = $request->validate($rules);

        $absensi->update([
            'jam_keluar' => $now->format('H:i:s'),
            'latitude_out' => $validated['latitude'],
            'longitude_out' => $validated['longitude'],
            'keterangan' => $isEarlyCheckout ? $validated['keterangan'] : $absensi->keterangan,
        ]);

        Log::info('Check-out berhasil:', [
            'id_siswa' => $siswa->id_siswa,
            'waktu' => $now->toDateTimeString(),
        ]);

        return back()->with('success', 'Absen pulang berhasil');
    }

    public function status()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan']);
        }

        $absensi = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        if (!$absensi) {
            return response()->json(['status' => 'belum_masuk']);
        }

        if (!$absensi->jam_keluar) {
            return response()->json(['status' => 'sudah_masuk']);
        }

        return response()->json(['status' => 'sudah_pulang']);
    }

    public function latestStatus()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan']);
        }

        $absensi = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        return response()->json([
            'status' => $absensi ? $absensi->status : null,
        ]);
    }

    
}