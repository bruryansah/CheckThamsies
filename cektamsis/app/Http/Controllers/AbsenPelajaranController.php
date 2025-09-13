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

        if (now()->gt($expiredAt)) {
            return back()->with('flash', [
                'success' => false,
                'message' => 'QR Code expired'
            ]);
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
            return redirect()->back()->with('flash', [
                'success' => false,
                'message' => 'Kamu sudah absen di jadwal ini!'
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

    public function dashboard()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (!$siswa) {
            return Inertia::render('Dashboard', [
                'kehadiransekolah' => 0,
                'persentaseKehadiran' => 0,
                'totalSakit' => 0,
                'totalIzin' => 0,
                'totalAbsensi' => 0,
                'recentAttendance' => [],
                'auth' => ['user' => Auth::user()],
            ]);
        }

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

        return Inertia::render('Dashboard', [
            'kehadiransekolah' => AbsensiPelajaran::where('id_siswa', $siswa->id_siswa)->where('status', 'hadir')->count(),
            'persentaseKehadiran' => 0, // TODO: hitung persentase sesuai logika
            'totalSakit' => AbsensiPelajaran::where('id_siswa', $siswa->id_siswa)->where('status', 'sakit')->count(),
            'totalIzin' => AbsensiPelajaran::where('id_siswa', $siswa->id_siswa)->where('status', 'izin')->count(),
            'totalAbsensi' => AbsensiPelajaran::where('id_siswa', $siswa->id_siswa)->count(),
            'recentAttendance' => $recentAttendance,
            'auth' => ['user' => Auth::user()],
        ]);
    }
}
