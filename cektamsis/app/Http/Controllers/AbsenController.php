<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
use Illuminate\Support\Facades\DB;
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
        $siswa = \DB::table('siswa')->where('user_id', $user->id)->first();

        // Default values jika siswa tidak ditemukan
        $kehadiransekolah = 0;
        $totalAbsensi = 0;
        $persentaseKehadiran = 0;
        $totalSakit = 0;
        $totalIzin = 0;
        $recentAttendance = [];

        if ($siswa) {
            $totalAbsensi = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->count();
            $kehadiransekolah = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->where('status', 'hadir')->count();
            $totalSakit = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->where('status', 'sakit')->count();
            $totalIzin = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->where('status', 'izin')->count();
            $persentaseKehadiran = $totalAbsensi > 0 ? round(($kehadiransekolah / $totalAbsensi) * 100, 1) : 0;

            // Fetch recent lesson attendance
            $recentAttendance = AbsensiPelajaran::with('jadwal.mapel')
                ->where('id_siswa', $siswa->id_siswa)
                ->orderBy('waktu_scan', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($attendance) {
                    return [
                        'name' => $attendance->jadwal->mapel->nama_mapel ?? 'Unknown',
                        'time' => $attendance->waktu_scan->format('d-M-Y H:i'),
                        'status' => $attendance->status,
                        'color' => $attendance->status === 'hadir' ? 'green' : ($attendance->status === 'izin' ? 'purple' : 'orange'),
                    ];
                });
        }

        return Inertia::render('User/Dashboard', [
            'auth' => ['user' => $user],
            'kehadiransekolah' => $kehadiransekolah,
            'persentaseKehadiran' => $persentaseKehadiran,
            'totalAbsensi' => $totalAbsensi,
            'totalSakit' => $totalSakit,
            'totalIzin' => $totalIzin,
            'recentAttendance' => $recentAttendance,
        ]);
    }

public function checkIn(Request $request)
{
    $siswa = Siswa::where('user_id', Auth::id())->first();

    if (!$siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
    }

    // Cek apakah sudah ada absensi hari ini
    $absensi = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
        ->whereDate('tanggal', Carbon::today())
        ->first();

    if ($absensi) {
            return back()->with('error', 'Anda sudah absen masuk hari ini');
    }

    // Validasi input
    $validated = $request->validate([
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'status' => 'required|in:hadir,izin,sakit',
        'description' => 'nullable|string|max:1000', // Make description optional but store it
    ]);

    AbsensiSekolah::create([
        'id_siswa' => $siswa->id_siswa,
        'tanggal' => Carbon::today(),
        'jam_masuk' => Carbon::now()->format('H:i:s'),
        'latitude_in' => $validated['latitude'],
        'longitude_in' => $validated['longitude'],
        'status' => $validated['status'],
        'keterangan' => $validated['description'] ?? '', // Use description if provided, empty string otherwise
    ]);

        return back()->with('success', 'Absen masuk berhasil');
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

    // Tambahkan validasi untuk mencegah checkout jika status adalah 'izin' atau 'sakit'
    if (in_array($absensi->status, ['izin', 'sakit'])) {
        return back()->with('error', 'Anda tidak perlu absen pulang karena status Anda adalah ' . $absensi->status);
    }

    // Check if current time is before 15:10 (WIB)
    $now = Carbon::now('Asia/Jakarta');
    $cutoffTime = Carbon::today('Asia/Jakarta')->setHour(15)->setMinute(10);
    $isEarlyCheckout = $now->lessThan($cutoffTime);

    // Validasi input
    $rules = [
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
    ];

    // Jika checkout sebelum 15:10, tambahkan validasi untuk keterangan
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