<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\AbsensiPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;

class AbsenPelajaranController extends Controller
{
    public function checkIn(Request $request)
    {
        Log::info('🔹 Absensi attempt', ['request' => $request->all(), 'user_id' => Auth::id()]);

        // 1. Cek siswa
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (!$siswa) {
            return redirect()->back()->with('flash', [
                'success' => false,
                'message' => 'Siswa tidak ditemukan'
            ]);
        }

        // 2. Ambil ID jadwal dari request (decrypt)
        $decode = explode('|', Crypt::decryptString($request->id_jadwal));

        $id_jadwal = (int) $decode[0];
        $id_guru   = (int) $decode[1];
        $id_qr     = (int) $decode[2];
        $expiredAt = $decode[3];

        $expiredAt = Carbon::parse($decode[3]);

                if (now()->gt(Carbon::parse($expiredAt))) {
            return response()->json([
                'errors' => [
                    'message' => 'QR Code expired'
                ]
            ], 422);
        }

        // 3. Validasi jadwal
        $jadwal = Jadwal::find($id_jadwal);
        if (!$jadwal) {
            return redirect()->back()->with('flash', [
                'success' => false,
                'message' => 'Jadwal tidak ditemukan'
            ]);
        }

        // 4. Cek apakah sudah absen
        $sudahAbsen = AbsensiPelajaran::where('id_siswa', $siswa->id_siswa)
            ->where('id_jadwal', $id_jadwal)
            ->exists();

                if ($sudahAbsen) {
        return back()->withErrors([
            'message' => 'Kamu sudah absen di jadwal ini!',
        ]);
    }

    if (now()->gt(Carbon::parse($expiredAt))) {
        return back()->withErrors([
            'message' => 'QR Code expired',
        ]);
    }


        // 5. Simpan absensi
        AbsensiPelajaran::create([
            'id_qr'      => $id_qr,
            'id_siswa'   => $siswa->id_siswa,
            'id_jadwal'  => $id_jadwal,
            'id_guru'    => $id_guru,
            'status'     => 'hadir',
            'waktu_scan' => now('Asia/Jakarta'),
            'keterangan' => null,
        ]);

        return redirect()->back()->with('flash', [
            'success' => true,
            'message' => 'Absensi berhasil!'
        ]);
    }
}
