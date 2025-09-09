<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Models\AbsensiPelajaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AbsenPelajaranController extends Controller
{
    public function checkIn(Request $request)
    {
        Log::info('🔹 Absensi attempt', ['request' => $request->all(), 'user_id' => Auth::id()]);

        // 1. Cek siswa
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (!$siswa) {
            Log::warning('❌ Siswa tidak ditemukan', ['user_id' => Auth::id()]);
            return redirect()->back()->with('flash', [
                'success' => false,
                'message' => 'Siswa tidak ditemukan'
            ]);
        }
        Log::info('✅ Siswa ditemukan', ['id_siswa' => $siswa->id_siswa]);

        // 2. Ambil ID jadwal dari request
        $id_jadwal = (int) $request->id_jadwal;
        Log::info('📌 ID Jadwal diterima', ['id_jadwal' => $id_jadwal]);

        // 3. Validasi jadwal
        $jadwal = Jadwal::find($id_jadwal);
        if (!$jadwal) {
            Log::warning('❌ Jadwal tidak ditemukan', ['id_jadwal' => $id_jadwal]);
            return redirect()->back()->with('flash', [
                'success' => false,
                'message' => 'Jadwal tidak ditemukan'
            ]);
        }
        Log::info('✅ Jadwal ditemukan', [
            'mapel' => $jadwal->mapel->nama_mapel ?? '-', 
            'kelas' => $jadwal->kelas->nama_kelas ?? '-'
        ]);

        // 4. Cek apakah sudah absen
        $sudahAbsen = AbsensiPelajaran::where('id_siswa', $siswa->id_siswa)
            ->where('id_jadwal', $id_jadwal)
            ->exists();

        if ($sudahAbsen) {
            Log::warning('⚠️ Sudah absen sebelumnya', [
                'id_siswa' => $siswa->id_siswa, 
                'id_jadwal' => $id_jadwal
            ]);
            return redirect()->back()->with('flash', [
                'success' => false,
                'message' => 'Kamu sudah absen di jadwal ini!'
            ]);
        }

        // 5. Simpan absensi
        $absen = AbsensiPelajaran::create([
            'id_siswa'   => $siswa->id_siswa,
            'id_jadwal'  => $id_jadwal,
            'status'     => 'hadir',
            'waktu_scan' => Carbon::now('Asia/Jakarta'),
        ]);

        Log::info('✅ Absensi berhasil dicatat', ['id_absensi_pelajaran' => $absen->id_absensi_pelajaran]);

        return redirect()->back()->with('flash', [
            'success' => true,
            'message' => 'Absensi berhasil!'
        ]);
    }
}
