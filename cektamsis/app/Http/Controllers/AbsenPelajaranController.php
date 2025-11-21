<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\AbsensiPelajaran;
use App\Models\AbsensiSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AbsenPelajaranController extends Controller
{
    public function checkIn(Request $request)
    {
        Log::info('🔹 Absensi attempt', ['request' => $request->all(), 'user_id' => Auth::id()]);

        // 1. Cek siswa
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (!$siswa) {
            Log::warning('Siswa tidak ditemukan untuk user_id: ' . Auth::id());
            return redirect()->back()->with('flash', [
                'success' => false,
                'message' => 'Siswa tidak ditemukan'
            ]);
        }

        // 2. Ambil ID jadwal dari request (langsung tanpa decrypt)
        $id_jadwal = (int) $request->id_jadwal;
        Log::info("ID Jadwal diterima: $id_jadwal");

        // 3. Validasi jadwal
        $jadwal = Jadwal::find($id_jadwal);
        if (!$jadwal) {
            Log::warning("Jadwal tidak ditemukan dengan id_jadwal: $id_jadwal");
            return redirect()->back()->with('flash', [
                'success' => false,
                'message' => 'Jadwal tidak ditemukan'
            ]);
        }

        // 4. Cek apakah sudah ditutup (finalize) untuk hari ini
        $today = Carbon::today('Asia/Jakarta');
        $sudahFinal = AbsensiPelajaran::where('id_jadwal', $id_jadwal)
            ->whereDate('waktu_scan', $today)
            ->where('keterangan', 'like', 'Otomatis alfa%')
            ->exists();

        if ($sudahFinal) {
            Log::info("Absen sudah ditutup untuk jadwal id: $id_jadwal pada tanggal $today");
            return back()->withErrors([
                'message' => 'Absen untuk jadwal ini sudah ditutup hari ini.',
            ]);
        }

        // 5. ⭐ PERBAIKAN: Cek apakah sudah absen untuk jadwal ini HARI INI
        $sudahAbsen = AbsensiPelajaran::where('id_siswa', $siswa->id_siswa)
            ->where('id_jadwal', $id_jadwal)
            ->whereDate('waktu_scan', $today) // Tambahkan pengecekan tanggal hari ini
            ->exists();

        if ($sudahAbsen) {
            Log::info("Siswa id {$siswa->id_siswa} sudah absen di jadwal id $id_jadwal pada tanggal $today");
            return back()->withErrors([
                'message' => 'Kamu sudah absen di jadwal ini hari ini!',
            ]);
        }

        // 6. Tentukan status dan keterangan
        $inputStatus = strtolower($request->input('status', 'hadir'));
        $description = $request->input('description');

        Log::info('Menyimpan absensi dengan data:', [
            'id_jadwal' => $id_jadwal,
            'id_siswa' => $siswa->id_siswa,
            'waktu_scan' => now('Asia/Jakarta')->toDateTimeString(),
            'status' => $inputStatus,
            'keterangan' => $description ?: ucfirst($inputStatus),
        ]);

        try {
            // Izin / Sakit
            if (in_array($inputStatus, ['izin', 'sakit'], true)) {
                AbsensiPelajaran::create([
                    'id_jadwal'  => $id_jadwal,
                    'id_siswa'   => $siswa->id_siswa,
                    'waktu_scan' => now('Asia/Jakarta'),
                    'status'     => $inputStatus,
                    'keterangan' => $description ?: ucfirst($inputStatus),
                ]);
            } else {
                // Absensi Hadir
                AbsensiPelajaran::create([
                    'id_jadwal'  => $id_jadwal,
                    'id_siswa'   => $siswa->id_siswa,
                    'waktu_scan' => now('Asia/Jakarta'),
                    'status'     => 'hadir',
                    'keterangan' => '-',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan absensi: ' . $e->getMessage());
            return back()->withErrors([
                'message' => 'Gagal menyimpan absensi. Silakan coba lagi.'
            ]);
        }

        return back()->with('flash', [
            'success' => true,
            'message' => 'Absensi berhasil (' . ucfirst($inputStatus) . ')!',
        ]);
    }

    public function finalize(Request $request)
    {
        try {
            // Ambil id_jadwal langsung (tidak perlu decrypt)
            $id_jadwal = (int) $request->id_jadwal;
            Log::info("Finalize absensi untuk id_jadwal: $id_jadwal");
        } catch (\Throwable $e) {
            Log::error("ID jadwal tidak valid: " . $e->getMessage());
            return back()->withErrors(['message' => 'ID jadwal tidak valid']);
        }

        $jadwal = Jadwal::with('kelas')->find($id_jadwal);
        if (!$jadwal) {
            Log::warning("Jadwal tidak ditemukan pada finalize dengan id: $id_jadwal");
            return back()->withErrors(['message' => 'Jadwal tidak ditemukan']);
        }

        // Validasi hari dan waktu
        $now = Carbon::now('Asia/Jakarta');
        $todayHari = strtolower($now->locale('id')->dayName);
        $hariJadwal = strtolower($jadwal->hari);

        $mapHari = [
            'minggu' => 'minggu',
            'senin' => 'senin',
            'selasa' => 'selasa',
            'rabu' => 'rabu',
            'kamis' => 'kamis',
            'jumat' => 'jumat',
            'sabtu' => 'sabtu',
        ];

        $mulai = Carbon::parse($jadwal->jam_mulai, 'Asia/Jakarta');
        $isPassed45Min = $now->greaterThan($mulai->copy()->addMinutes(45));

        if (($mapHari[$todayHari] ?? $todayHari) !== $hariJadwal) {
            Log::warning("Hari tidak sesuai untuk finalize: hari sekarang $todayHari, jadwal $hariJadwal");
            return back()->withErrors(['message' => 'Hari ini tidak sesuai dengan hari jadwal']);
        }

        if (!$isPassed45Min) {
            Log::warning("Belum melewati 45 menit dari jam mulai untuk jadwal id: $id_jadwal");
            return back()->withErrors(['message' => 'Belum melewati 45 menit dari jam mulai']);
        }

        // Finalize absensi
        $kelasId = $jadwal->id_kelas;
        $siswaList = \App\Models\Siswa::where('id_kelas', $kelasId)->get(['id_siswa']);
        $createdAlpha = 0;
        $createdExcused = 0;

        foreach ($siswaList as $s) {
            // Cek apakah sudah ada absensi pelajaran untuk hari ini
            $exists = AbsensiPelajaran::where('id_jadwal', $id_jadwal)
                ->where('id_siswa', $s->id_siswa)
                ->whereDate('waktu_scan', $now->toDateString())
                ->exists();

            if ($exists) {
                continue;
            }

            // Cek absensi sekolah: jika izin/sakit pada hari ini, buat record izin/sakit di absensi pelajaran
            $school = AbsensiSekolah::where('id_siswa', $s->id_siswa)
                ->whereDate('tanggal', $now->toDateString())
                ->first();

            if ($school && in_array(strtolower($school->status), ['izin', 'sakit'], true)) {
                try {
                    AbsensiPelajaran::create([
                        'id_jadwal'  => $id_jadwal,
                        'id_siswa'   => $s->id_siswa,
                        'waktu_scan' => $now,
                        'status'     => strtolower($school->status),
                        'keterangan' => $school->keterangan ?? ucfirst($school->status),
                    ]);
                    $createdExcused++;
                } catch (\Exception $e) {
                    Log::error('Gagal menyimpan izin/sakit finalize untuk siswa id ' . $s->id_siswa . ': ' . $e->getMessage());
                }
                continue;
            }

            // Selain itu, tandai ALFA otomatis
            try {
                AbsensiPelajaran::create([
                    'id_jadwal'  => $id_jadwal,
                    'id_siswa'   => $s->id_siswa,
                    'waktu_scan' => $now,
                    'status'     => 'alfa',
                    'keterangan' => 'Otomatis alfa',
                ]);
                $createdAlpha++;
            } catch (\Exception $e) {
                Log::error('Gagal menyimpan finalize absensi untuk siswa id ' . $s->id_siswa . ': ' . $e->getMessage());
            }
        }

        Log::info("Finalize selesai, Alfa: $createdAlpha, Izin/Sakit: $createdExcused");

        // Update last_finalized_at untuk menandai jadwal sudah ditutup hari ini
        $jadwal->update([
            'last_finalized_at' => $now,
        ]);

        return back()->with('flash', [
            'success' => true,
            'message' => "Finalize berhasil. Alfa: {$createdAlpha}, Izin/Sakit: {$createdExcused}.",
        ]);
    }

    public function resetFinalized(Request $request)
    {
        try {
            // Ambil id_jadwal langsung (tidak perlu decrypt)
            $id_jadwal = (int) $request->id_jadwal;
            Log::info("Reset finalized untuk id_jadwal: $id_jadwal");
        } catch (\Throwable $e) {
            Log::error("ID jadwal tidak valid: " . $e->getMessage());
            return back()->withErrors(['message' => 'ID jadwal tidak valid']);
        }

        $jadwal = Jadwal::find($id_jadwal);
        if (!$jadwal) {
            Log::warning("Jadwal tidak ditemukan pada reset finalized dengan id: $id_jadwal");
            return back()->withErrors(['message' => 'Jadwal tidak ditemukan']);
        }

        // Hapus semua absensi dengan status alfa otomatis untuk jadwal ini hari ini
        $today = Carbon::today('Asia/Jakarta');
        $deleted = AbsensiPelajaran::where('id_jadwal', $id_jadwal)
            ->whereDate('waktu_scan', $today)
            ->where('keterangan', 'like', 'Otomatis alfa%')
            ->delete();

        Log::info("Reset finalized selesai, total record dihapus: $deleted");

        return back()->with('flash', [
            'success' => true,
            'message' => "Reset finalized berhasil. Dihapus: {$deleted} record alfa otomatis.",
        ]);
    }
}