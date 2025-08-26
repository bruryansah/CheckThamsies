<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\QrSession;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Kelas;
use Carbon\Carbon;

class QrSessionController extends Controller
{
    public function generate(Request $request)
    {
        // Ambil jadwal berdasarkan id_jadwal
        $jadwal = Jadwal::with(['guru', 'mapel'])->findOrFail($request->id_jadwal);

        // buat kode unik QR
        $kodeQr = Str::uuid()->toString();

        $now = Carbon::now();
        $expired = $now->copy()->addMinutes(10);

        // simpan ke qr_session
        $qr = QrSession::create([
            'id_jadwal'     => $jadwal->id_jadwal,
            'id_guru'       => $jadwal->id_guru,
            'kode_qr'       => $kodeQr,
            'nama_mapel'    => $jadwal->mapel->nama_mapel,
            'nama_kelas'    => $jadwal->kelas->nama_kelas ?? '-',
            'waktu_mulai'   => $now,
            'waktu_selesai' => $expired,
            'status'        => 'aktif',
        ]);

        return response()->json($qr);
    }

    public function validateQr(Request $request)
    {
        $qr = QrSession::where('kode_qr', $request->kode_qr)->first();

        if (!$qr) {
            return response()->json(['message' => 'QR tidak ditemukan'], 404);
        }

        if (Carbon::now()->greaterThan($qr->waktu_selesai)) {
            $qr->update(['status' => 'expired']);
            return response()->json(['message' => 'QR expired'], 400);
        }

        return response()->json([
            'message' => 'QR valid',
            'data' => $qr
        ]);
    }
}
