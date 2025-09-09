<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\AbsensiSekolah;
use Carbon\Carbon;

class AbsenController extends Controller
{
    // CONTROLLER LENGKAP - SIMPLE & CLEAN
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

        if ($siswa) {
            $totalAbsensi = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->count();
            $kehadiransekolah = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->where('status', 'hadir')->count();
            $totalSakit = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->where('status', 'sakit')->count();
            $totalIzin = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->where('status', 'izin')->count();
            $persentaseKehadiran = $totalAbsensi > 0 ? round(($kehadiransekolah / $totalAbsensi) * 100, 1) : 0;
        }

        return inertia('User/Dashboard', [
            'auth' => ['user' => $user],
            'kehadiransekolah' => $kehadiransekolah,
            'persentaseKehadiran' => $persentaseKehadiran,
            'totalAbsensi' => $totalAbsensi,
            'totalSakit' => $totalSakit,    // Added real data for total sick days
            'totalIzin' => $totalIzin,      // Added real data for total leave days
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

        AbsensiSekolah::create([
            'id_siswa' => $siswa->id_siswa,
            'tanggal' => Carbon::today(),
            'jam_masuk' => Carbon::now()->format('H:i:s'),
            'latitude_in' => $request->latitude,
            'longitude_in' => $request->longitude,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Absen masuk berhasil');
    }

    public function checkOut(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();

        $absensi = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->whereDate('tanggal', Carbon::today())->first();

        if (!$absensi) {
            return back()->with('error', 'Anda belum absen masuk hari ini');
        }

        if ($absensi->jam_keluar) {
            return back()->with('error', 'Anda sudah absen pulang hari ini');
        }

        $absensi->update([
            'jam_keluar' => Carbon::now()->format('H:i:s'),
            'latitude_out' => $request->latitude,
            'longitude_out' => $request->longitude,
        ]);

        return back()->with('success', 'Absen pulang berhasil');
    }

    public function status()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();

        $absensi = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)->whereDate('tanggal', Carbon::today())->first();

        if (!$absensi) {
            return response()->json(['status' => 'belum_masuk']);
        }

        if ($absensi && !$absensi->jam_keluar) {
            return response()->json(['status' => 'sudah_masuk']);
        }

        return response()->json(['status' => 'sudah_pulang']);
    }
}