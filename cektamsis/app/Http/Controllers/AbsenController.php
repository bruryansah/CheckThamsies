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

        // Default values
        $kehadiransekolah = 0;
        $totalAbsensi = 0;
        $persentaseKehadiran = 0;
        $totalSakit = 0;
        $totalIzin = 0;
        $totalAlfa = 0;
        $recentAttendance = [];
        $jadwalData = [];
        $absensiSekolahData = [];
        $absensiPelajaranData = []; // 🔹 Data real untuk statistik

        if ($siswa) {
            // Statistik absensi sekolah
            $totalAbsensi = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->count();
            $kehadiransekolah = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
                ->whereIn('status', ['hadir', 'terlambat'])
                ->count();
            $totalSakit = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->where('status', 'sakit')->count();
            $totalIzin = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->where('status', 'izin')->count();
            $totalAlfa = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->where('status', 'alfa')->count();

            $persentaseKehadiran = $totalAbsensi > 0 ? round(($kehadiransekolah / $totalAbsensi) * 100, 1) : 0;

            // 🔹 Ambil semua data absensi sekolah (untuk popup)
            $absensiSekolahData = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
                ->select('tanggal', 'jam_masuk', 'jam_keluar', 'status', 'keterangan', 'verifikasi')
                ->orderBy('tanggal', 'desc')
                ->get();

            // 🔹 Ambil SEMUA data absensi pelajaran untuk statistik
            $absensiPelajaranData = AbsensiPelajaran::with(['jadwal.mapel'])
                ->where('id_siswa', $siswa->id_siswa)
                ->orderBy('waktu_scan', 'desc')
                ->get()
                ->map(function ($attendance) {
                    return [
                        'mata_pelajaran' => $attendance->jadwal->mapel->nama_mapel ?? 'Unknown',
                        'status' => strtolower($attendance->status ?? 'N/A'),
                        'tanggal' => $attendance->waktu_scan ? $attendance->waktu_scan->format('Y-m-d') : 'N/A',
                        'waktu' => $attendance->waktu_scan ? $attendance->waktu_scan->format('H:i') : 'N/A',
                        'keterangan' => $attendance->keterangan ?? '-',
                    ];
                })
                ->toArray();

            Log::info('Data absensi pelajaran untuk statistik:', [
                'id_siswa' => $siswa->id_siswa,
                'count' => count($absensiPelajaranData),
            ]);

            // 🔹 Recent attendance pelajaran (tetap 5 terakhir untuk card)
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
                        'verifikasi' => $attendance->verifikasi,
                        'color' => match ($attendance->status) {
                            'hadir' => 'green',
                            'terlambat' => 'orange',
                            'izin', 'sakit' => 'purple',
                            'alfa' => 'red',
                            default => 'gray',
                        },
                    ];
                })
                ->toArray();

            // 🔹 Jadwal hari ini
            $today = Carbon::today('Asia/Jakarta');
            $hari = strtolower($today->locale('id')->dayName);

            $jadwalData = Jadwal::with(['mapel', 'kelas', 'guru'])
                ->where('id_kelas', $siswa->id_kelas)
                ->whereRaw('LOWER(hari) = ?', [$hari])
                ->get()
                ->map(function ($item) use ($today) {
                    $idenc = Crypt::encryptString("{$item->id_kelas}|{$item->id_guru}|{$item->id_qr}|" . now()->addMinutes(5)->format('Y-m-d H:i:s'));

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
                })
                ->toArray();

            // 🔹 Log alfa otomatis untuk absensi sekolah
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
                        'verifikasi' => '-',
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

        // 🔹 Kirim semua data ke Inertia
        return Inertia::render('User/Dashboard', [
            'auth' => [
                'user' => $user,
                'siswa' => $siswa
                    ? [
                        'id_siswa' => $siswa->id_siswa,
                        'nama' => $siswa->nama,
                        'id_kelas' => $siswa->id_kelas,
                        'nama_kelas' => $siswa->kelas->nama_kelas ?? 'Unknown',
                    ]
                    : null,
            ],
            'kehadiransekolah' => $kehadiransekolah,
            'persentaseKehadiran' => $persentaseKehadiran,
            'totalAbsensi' => $totalAbsensi,
            'totalSakit' => $totalSakit,
            'totalIzin' => $totalIzin,
            'totalAlfa' => $totalAlfa,
            'recentAttendance' => $recentAttendance,
            'jadwalData' => $jadwalData,
            'absensiSekolah' => $absensiSekolahData,
            'absensiPelajaranData' => $absensiPelajaranData, // 👈 Data real untuk statistik
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
            Log::warning('Siswa sudah absen hari ini:', ['id_siswa' => $siswa->id_siswa]);
            return back()->with('error', 'Anda sudah absen masuk hari ini');
        }

        // Validasi input
        try {
            $validated = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'status' => 'required|in:hadir,terlambat,izin,sakit',
                'description' => 'nullable|string|max:1000',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi gagal:', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors());
        }

        Log::info('Data validasi berhasil:', $validated);

        $now = Carbon::now('Asia/Jakarta');
        $timeInMinutes = $now->hour * 60 + $now->minute;
        $startHadir = 6 * 60 + 40; // 06:40
        $endHadir = 7 * 60 + 10; // 07:10
        $endTerlambat = 13 * 60 + 40; // 13:40

        // Tentukan status berdasarkan waktu jika status awal adalah 'hadir'
        $status = $validated['status'];
        
        Log::info('Status awal dari request:', ['status' => $status, 'description' => $validated['description'] ?? 'kosong']);
        
        // Jangan ubah status jika sudah ada description (berarti dari modal terlambat)
        // Atau jika status sudah ditentukan dari frontend (izin, sakit, terlambat)
        if ($status === 'hadir' && empty($validated['description'])) {
            if ($timeInMinutes > $endTerlambat) {
                Log::warning('Waktu melewati batas:', ['timeInMinutes' => $timeInMinutes]);
                return back()->with('error', 'Waktu sudah melewati 13:40, status alfa otomatis diterapkan.');
            } elseif ($timeInMinutes > $endHadir && $timeInMinutes <= $endTerlambat) {
                // Jangan ubah ke terlambat di sini, biarkan frontend yang handle
                Log::info('Waktu terlambat tapi description kosong, frontend harus handle:', ['timeInMinutes' => $timeInMinutes]);
            }
        }

        // Validasi keterangan untuk status tertentu
        if (in_array($status, ['izin', 'sakit']) && empty($validated['description'])) {
            Log::warning('Keterangan kosong untuk status:', ['status' => $status]);
            return back()->withErrors(['description' => 'Keterangan diperlukan untuk status ' . ucfirst($status)]);
        }
        
        // Validasi khusus untuk terlambat (hanya jika ada description yang dikirim atau status memang terlambat dari awal)
        if ($status === 'terlambat') {
            if (empty($validated['description'])) {
                Log::warning('Keterangan kosong untuk terlambat');
                return back()->withErrors(['description' => 'Keterangan diperlukan untuk status Terlambat']);
            }
        }

        // Set verifikasi
        $verifikasi = '-';
        if (in_array($status, ['izin', 'sakit'])) {
            $verifikasi = 'cek';
        } elseif ($status === 'terlambat') {
            $verifikasi = 'cek'; // Terlambat juga perlu verifikasi
        }

        Log::info('Verifikasi diset:', ['verifikasi' => $verifikasi]);

        try {
            $absensi = AbsensiSekolah::create([
                'id_siswa' => $siswa->id_siswa,
                'tanggal' => Carbon::today(),
                'jam_masuk' => $now->format('H:i:s'),
                'latitude_in' => $validated['latitude'],
                'longitude_in' => $validated['longitude'],
                'status' => $status,
                'keterangan' => $validated['description'] ?? '',
                'verifikasi' => $verifikasi,
            ]);

            Log::info('Check-in berhasil:', [
                'id_absensi' => $absensi->id_absensi ?? null,
                'id_siswa' => $siswa->id_siswa,
                'status' => $status,
                'keterangan' => $validated['description'] ?? '',
                'waktu' => $now->toDateTimeString(),
            ]);

            return back()->with('success', 'Absen masuk berhasil sebagai ' . ucfirst($status));
            
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan absensi:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['message' => 'Terjadi kesalahan saat menyimpan absensi: ' . $e->getMessage()]);
        }
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
        $cutoffTime = Carbon::today('Asia/Jakarta')->setHour(13)->setMinute(39);
        $isEarlyCheckout = $now->lessThan($cutoffTime);

        $rules = [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ];

        if ($isEarlyCheckout) {
            $rules['keterangan'] = 'required|string|max:1000';
        }

        $verifikasi = $absensi->verifikasi;

        if ($isEarlyCheckout) {
            $verifikasi = 'cek';
        } else {
            $verifikasi = '-';
        }

        $validated = $request->validate($rules);

        $absensi->update([
            'jam_keluar' => $now->format('H:i:s'),
            'latitude_out' => $validated['latitude'],
            'longitude_out' => $validated['longitude'],
            'keterangan' => $isEarlyCheckout ? $validated['keterangan'] : $absensi->keterangan,
            'status' => $isEarlyCheckout ? 'pulang cepat' : $absensi->status,
            'verifikasi' => $verifikasi,
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