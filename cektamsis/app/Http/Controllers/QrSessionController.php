<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\QrSession;
use App\Models\Jadwal;
use Carbon\Carbon;

class QrSessionController extends Controller
{
    /**
     * Generate QR baru
     */
    public function generate(Request $request)
    {
        $jadwal = Jadwal::with(['guru', 'mapel', 'kelas'])
            ->findOrFail($request->id_jadwal);

        $now = Carbon::now();

        // Cek apakah sudah ada QR aktif untuk jadwal ini
        $existingQr = QrSession::where('id_jadwal', $jadwal->id_jadwal)
            ->latest()
            ->first();

        $status = 'aktif';
        $expired = $now->copy()->addMinutes(10);

        // Jika ada QR sebelumnya dan sudah expired → QR baru dianggap "telat"
        if ($existingQr && $now->greaterThan($existingQr->waktu_selesai)) {
            $status = 'telat';
            $expired = null; // QR telat tidak ada expired
        }

        // Buat kode unik QR
        $kodeQr = Str::uuid()->toString();

        // Simpan QR baru
        $qr = QrSession::create([
            'id_jadwal'     => $jadwal->id_jadwal,
            'id_guru'       => $jadwal->id_guru,
            'kode_qr'       => $kodeQr,
            'nama_mapel'    => $jadwal->mapel->nama_mapel ?? '-',
            'nama_kelas'    => $jadwal->kelas->nama_kelas ?? '-',
            'waktu_mulai'   => $now,
            'waktu_selesai' => $expired,
            'status'        => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => "QR berhasil digenerate ({$status})",
            'data' => $qr,
        ]);
    }

    /**
     * Validasi QR ketika discan siswa
     */
    public function validateQr(Request $request)
    {
        $qr = QrSession::where('kode_qr', $request->kode_qr)->first();

        if (!$qr) {
            return response()->json([
                'success' => false,
                'message' => '❌ QR tidak ditemukan'
            ], 404);
        }

        // Jika status aktif tapi sudah lewat waktu → expired
        if ($qr->status === 'aktif' && Carbon::now()->greaterThan($qr->waktu_selesai)) {
            $qr->update(['status' => 'expired']);

            return response()->json([
                'success' => false,
                'message' => '❌ QR expired',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => '✅ QR valid',
            'data' => $qr
        ]);
    }
}
