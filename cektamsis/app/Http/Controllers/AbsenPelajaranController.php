<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\AbsensiPelajaran;

class AbsenPelajaranController extends Controller
{
    public function checkIn(Request $request)
    {
        Log::info('🔹 Absensi attempt', ['request' => $request->all(), 'user_id' => Auth::id()]);

        // 1. Cek siswa
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (!$siswa) {
            Log::warning('❌ Siswa tidak ditemukan', ['user_id' => Auth::id()]);
            return back()->withErrors(['msg' => 'Siswa tidak ditemukan']);
        }
        Log::info('✅ Siswa ditemukan', ['id_siswa' => $siswa->id_siswa]);

        // 2. Ambil ID jadwal dari request
        $id_jadwal = (int) $request->id_jadwal;
        Log::info('📌 ID Jadwal diterima', ['id_jadwal' => $id_jadwal]);

        // 3. Validasi jadwal
        $jadwal = Jadwal::find($id_jadwal);
        if (!$jadwal) {
            Log::warning('❌ Jadwal tidak ditemukan', ['id_jadwal' => $id_jadwal]);
            return back()->withErrors(['msg' => 'Jadwal tidak ditemukan']);
        }
        Log::info('✅ Jadwal ditemukan', ['mapel' => $jadwal->mapel->nama_mapel ?? '-', 'kelas' => $jadwal->kelas->nama_kelas ?? '-']);

        // 4. Cek apakah sudah absen
        $sudahAbsen = AbsensiPelajaran::where('id_siswa', $siswa->id_siswa)
            ->where('id_jadwal', $id_jadwal)
            ->exists();

        if ($sudahAbsen) {
            Log::warning('⚠️ Sudah absen sebelumnya', ['id_siswa' => $siswa->id_siswa, 'id_jadwal' => $id_jadwal]);
            return back()->withErrors(['msg' => 'Kamu sudah absen di jadwal ini!']);
        }

        // 5. Simpan absensi
        $absen = AbsensiPelajaran::create([
            'id_siswa'  => $siswa->id_siswa,
            'id_jadwal' => $id_jadwal,
            'status'    => 'hadir',
            'waktu_scan' => now(),
        ]);

        Log::info('✅ Absensi berhasil dicatat', ['id_absensi_pelajaran' => $absen->id_absensi_pelajaran]);

        return back()->with('success', 'Absensi berhasil!');
    }
}
