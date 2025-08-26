<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
use App\Models\AbsensiSekolah;

class AbsenController extends Controller
{
    public function checkIn(Request $request)
    {
        $user = Auth::user();

        // Cek role siswa
        if ($user->role !== UserRole::USER->value) {
            return redirect()->back()->with('error', 'Hanya siswa yang bisa melakukan absen.');
        }

        $siswa = $user->siswa;

        if (!$siswa) {
            return redirect()->back()->with('error', 'User ini belum terdaftar sebagai siswa!');
        }

        $today = now()->toDateString();

$absen = AbsensiSekolah::firstOrNew([
    'id_siswa' => $siswa->id_siswa,
    'tanggal' => now()->toDateString(),
]);

        if ($absen->jam_masuk) {
            return redirect()->back()->with('error', 'Sudah absen masuk hari ini!');
        }
        $validated = $request->validate([
            'status' => 'nullable|in:Hadir,Izin,Sakit',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

       $absen->fill([
    'jam_masuk' => now()->toTimeString(),
    'latitude_in' => $request->latitude ?? null,
    'longitude_in' => $request->longitude ?? null,
    'status' => strtolower($validated['status'] ?? 'hadir'),
])->save();
        return redirect()->back()->with('success', 'Absen masuk berhasil!');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== UserRole::USER->value) {
            return redirect()->back()->with('error', 'Hanya siswa yang bisa melakukan absen.');
        }

        $siswa = $user->siswa;

        if (!$siswa) {
            return redirect()->back()->with('error', 'User ini belum terdaftar sebagai siswa!');
        }

        $today = now()->toDateString();

        $absen = AbsensiSekolah::where('id_siswa', $siswa->id_siswa)
            ->where('tanggal', $today)
            ->first();

        if (!$absen || !$absen->jam_masuk) {
            return redirect()->back()->with('error', 'Belum ada data absen masuk hari ini.');
        }

        if ($absen->jam_keluar) {
            return redirect()->back()->with('error', 'Sudah absen pulang hari ini!');
        }

        $absen->update([
            'jam_keluar' => now()->toTimeString(),
            'latitude_out' => $request->latitude ?? null,
            'longitude_out' => $request->longitude ?? null,
        ]);

        return redirect()->back()->with('success', 'Absen pulang berhasil!');
    }
}
