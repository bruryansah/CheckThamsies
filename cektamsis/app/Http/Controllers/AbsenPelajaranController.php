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
        $expiredAt = $decode[3] !== 'null' ? Carbon::parse($decode[3]) : null;

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

        // 5. Tentukan status absensi berdasarkan expired
        $status = 'hadir';
        $keterangan = null;

        if ($expiredAt && now()->gt($expiredAt)) {
            // QR pertama expired → absensi masuk sebagai TELAT
            $status = 'telat';
            $keterangan = 'Terlambat';
        }

        // 6. Simpan absensi
        $isLate = now()->gt(Carbon::parse($expiredAt));

        AbsensiPelajaran::create([
            'id_qr'      => $id_qr,
            'id_siswa'   => $siswa->id_siswa,
            'id_jadwal'  => $id_jadwal,
            'id_guru'    => $id_guru,
            'status'     => $isLate ? 'telat' : 'hadir',
            'waktu_scan' => now('Asia/Jakarta'),
            'keterangan' => $isLate ? 'Terlambat' : 'Aman',
        ]);

        return redirect()->back()->with('flash', [
            'success' => true,
            'message' => $status === 'hadir'
                ? 'Absensi berhasil (Hadir)!'
                : 'Absensi berhasil, tetapi status kamu TERLAMBAT.'
        ]);
    }
}
