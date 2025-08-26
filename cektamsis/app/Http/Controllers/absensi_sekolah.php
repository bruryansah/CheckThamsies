<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use Illuminate\Support\Facades\Auth;

class AbsenController extends Controller
{
    // ✅ Check In
    public function checkIn(Request $request)
    {
        $id_siswa = Auth::user()->siswa?->id;

        if (!$id_siswa) {
            return back()->with('error', 'User ini belum terdaftar sebagai siswa!');
        }

        // Validasi data
        $validated = $request->validate([
            'status' => 'nullable|in:hadir,izin,sakit',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Simpan absen masuk
        Absen::create([
            'id_siswa'    => $id_siswa,
            'tanggal'     => now()->toDateString(),
            'jam_masuk'   => now()->toTimeString(),
            'latitude_in' => $request->latitude ?? null,
            'longitude_in'=> $request->longitude ?? null,
            'status'      => $request->status ?? 'hadir',
        ]);

        return back()->with('success', 'Absen masuk berhasil!');

        

        
    }

    // ✅ Check Out
        public function checkOut(Request $request)
        {
            $id_siswa = Auth::user()->siswa?->id;

            if (!$id_siswa) {
                return back()->with('error', 'User ini belum terdaftar sebagai siswa!');
            }

            // Cari absen hari ini
            $absen = Absen::where('id_siswa', $id_siswa)
                ->where('tanggal', now()->toDateString())
                ->first();

            if (!$absen) {
                return back()->with('error', 'Belum ada data absen masuk untuk hari ini.');
            }

            // Update absen keluar
            $absen->update([
                'jam_keluar'   => now()->toTimeString(),
                'latitude_out' => $request->latitude ?? null,
                'longitude_out'=> $request->longitude ?? null,
            ]);

            return back()->with('success', 'Absen pulang berhasil!');
        }
}