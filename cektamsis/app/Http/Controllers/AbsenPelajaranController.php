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

        // 1. Validate request
        $request->validate([
            'id_jadwal' => 'required',
            'status' => 'required|in:hadir,izin,sakit',
            'description' => 'required_if:status,izin,sakit|nullable|string|max:255',
        ]);

        // 2. Check student
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (!$siswa) {
            return back()->withErrors(['message' => 'Siswa tidak ditemukan']);
        }

        // 3. Decrypt and extract QR code data
        try {
            $decode = explode('|', Crypt::decryptString($request->id_jadwal));
            $id_jadwal = (int) $decode[0];
            $id_guru = (int) $decode[1];
            $id_qr = (int) $decode[2];
            $expiredAt = $decode[3] !== 'null' ? Carbon::parse($decode[3]) : null;
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Format QR Code tidak valid']);
        }

        // 4. Validate schedule
        $jadwal = Jadwal::find($id_jadwal);
        if (!$jadwal) {
            return back()->withErrors(['message' => 'Jadwal tidak ditemukan']);
        }

        // 5. Check if already attended
        $sudahAbsen = AbsensiPelajaran::where('id_siswa', $siswa->id_siswa)
            ->where('id_jadwal', $id_jadwal)
            ->exists();

        if ($sudahAbsen) {
            return back()->withErrors(['message' => 'Kamu sudah absen di jadwal ini!']);
        }

        // 6. Determine status and description
        $status = $request->status;
        $keterangan = $request->description ?? 'Aman';

        if ($status === 'hadir' && $expiredAt && now()->gt($expiredAt)) {
            $status = 'telat';
            $keterangan = $request->description ?? 'Terlambat';
        }

        // 7. Save attendance
        AbsensiPelajaran::create([
            'id_qr' => $id_qr,
            'id_siswa' => $siswa->id_siswa,
            'id_jadwal' => $id_jadwal,
            'id_guru' => $id_guru,
            'status' => $status,
            'waktu_scan' => now('Asia/Jakarta'),
            'keterangan' => $keterangan,
        ]);

        // 8. Return response
        $message = $status === 'hadir' ? 'Absensi berhasil (Hadir)!' :
                   ($status === 'telat' ? 'Absensi berhasil, tetapi status kamu TERLAMBAT.' :
                   "Absensi berhasil ($status)!");

        return redirect()->back()->with('flash', [
            'success' => true,
            'message' => $message,
        ]);
    }
}
