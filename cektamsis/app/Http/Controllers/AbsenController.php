<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\AbsensiSekolah;
use App\Models\AbsensiPelajaran;
use Carbon\Carbon;
use Inertia\Inertia;

class AbsenController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        // Default values jika siswa tidak ditemukan
        $kehadiransekolah = 0;
        $totalAbsensi = 0;
        $persentaseKehadiran = 0;
        $totalSakit = 0;
        $totalIzin = 0;
        $totalAlfa = 0;
        $recentAttendance = [];

        if ($siswa) {
            $totalAbsensi = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->count();
            $kehadiransekolah = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
                ->whereIn('status', ['hadir', 'terlambat']) // Hanya hitung hadir dan terlambat sebagai kehadiran
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

            // Fetch recent lesson attendance with fallback
            $recentAttendance = AbsensiPelajaran::with('jadwal.mapel')
                ->where('id_siswa', $siswa->id_siswa)
                ->orderBy('waktu_scan', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($attendance) {
                    return [
                        'name' => $attendance->jadwal->mapel->nama_mapel ?? 'Unknown',
                        'time' => $attendance->waktu_scan ? $attendance->waktu_scan->format('d-M-Y H:i') : 'N/A',
                        'status' => $attendance->status ?? 'N/A',
                        'color' => $attendance->status === 'hadir' ? 'green' : ($attendance->status === 'terlambat' ? 'orange' : ($attendance->status === 'izin' ? 'purple' : ($attendance->status === 'alfa' ? 'red' : 'gray'))),
                    ];
                })->toArray();
        }

        return Inertia::render('User/Dashboard', [
            'auth' => ['user' => $user],
            'kehadiransekolah' => $kehadiransekolah,
            'persentaseKehadiran' => $persentaseKehadiran,
            'totalAbsensi' => $totalAbsensi,
            'totalSakit' => $totalSakit,
            'totalIzin' => $totalIzin,
            'totalAlfa' => $totalAlfa, // Tambahkan total alfa untuk ditampilkan di dashboard jika diperlukan
            'recentAttendance' => $recentAttendance,
        ]);
    }

    public function checkIn(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();

        if (!$siswa) {
            return back()->withErrors(['message' => '❌ Data siswa tidak ditemukan, silakan hubungi admin!']);
        }

        $absensi = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        if ($absensi) {
            return back()->with('error', 'Anda sudah absen masuk hari ini');
        }

        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required|in:hadir,izin,sakit',
            'description' => 'nullable|string|max:1000',
        ]);

        $now = Carbon::now('Asia/Jakarta');
        $timeInMinutes = $now->hour * 60 + $now->minute;
        $startHadir = 6 * 60 + 40; // 06:40
        $endHadir = 7 * 60 + 10;   // 07:10
        $endTerlambat = 13 * 60 + 40; // 13:40

        // Tentukan status berdasarkan waktu
        if ($timeInMinutes > $endTerlambat) {
            $status = 'alfa'; // Alfa dihitung sebagai ketidakhadiran
        } elseif ($timeInMinutes > $endHadir && $timeInMinutes <= $endTerlambat) {
            $status = 'terlambat'; // Terlambat dihitung sebagai kehadiran
        } elseif ($timeInMinutes >= $startHadir && $timeInMinutes <= $endHadir && $validated['status'] === 'hadir') {
            $status = 'hadir';
        } else {
            $status = $validated['status']; // Izin atau sakit
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

        if (in_array($absensi->status, ['izin', 'sakit', 'alfa'])) {
            return back()->with('error', 'Anda tidak perlu absen pulang karena status Anda adalah ' . $absensi->status);
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

        return back()->with('success', 'Absen pulang berhasil');
    }

    public function status()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        $absensi = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        if (!$absensi) {
            return response()->json(['status' => 'belum_masuk']);
        }

        if ($absensi && !$absensi->jam_keluar) {
            return response()->json(['status' => 'sudah_masuk']);
        }

        return response()->json(['status' => 'sudah_pulang']);
    }

    public function latestStatus()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        $absensi = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        if ($absensi) {
            return response()->json(['status' => $absensi->status]);
        }

        return response()->json(['status' => null]);
    }
}