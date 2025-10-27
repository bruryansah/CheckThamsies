<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\AbsensiPelajaran;
use App\Models\AbsensiSekolah;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoFinalizeSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:auto-finalize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically finalize attendance for schedules that have passed their end time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now('Asia/Jakarta');
        $todayHari = strtolower($now->locale('id')->dayName);
        $todayDate = $now->toDateString();

        // Mapping hari
        $mapHari = [
            'minggu' => 'minggu',
            'senin' => 'senin',
            'selasa' => 'selasa',
            'rabu' => 'rabu',
            'kamis' => 'kamis',
            'jumat' => 'jumat',
            'sabtu' => 'sabtu',
        ];

        $todayHariMapped = $mapHari[$todayHari] ?? $todayHari;

        // Ambil semua jadwal untuk hari ini yang belum di-finalize
        $schedules = Jadwal::where('hari', $todayHariMapped)
            ->where(function ($query) use ($todayDate) {
                $query->whereNull('last_finalized_at')
                    ->orWhereDate('last_finalized_at', '<', $todayDate);
            })
            ->get();

        $this->info("Checking " . $schedules->count() . " schedules for auto-finalize...");

        foreach ($schedules as $jadwal) {
            $jamSelesai = Carbon::parse($jadwal->jam_selesai, 'Asia/Jakarta');
            
            // Jika waktu sekarang sudah melewati jam selesai
            if ($now->greaterThan($jamSelesai)) {
                $this->autoFinalizeSchedule($jadwal, $now);
            }
        }

        $this->info('Auto-finalize completed!');
    }

    /**
     * Auto-finalize a schedule
     */
    private function autoFinalizeSchedule($jadwal, $now)
    {
        Log::info("Auto-finalizing schedule id: {$jadwal->id_jadwal}");

        $kelasId = $jadwal->id_kelas;
        $siswaList = Siswa::where('id_kelas', $kelasId)->get(['id_siswa']);
        $createdAlpha = 0;
        $createdExcused = 0;

        foreach ($siswaList as $s) {
            // Cek apakah sudah ada absensi pelajaran untuk hari ini
            $exists = AbsensiPelajaran::where('id_jadwal', $jadwal->id_jadwal)
                ->where('id_siswa', $s->id_siswa)
                ->whereDate('waktu_scan', $now->toDateString())
                ->exists();

            if ($exists) {
                continue;
            }

            // Cek absensi sekolah: jika izin/sakit pada hari ini
            $school = AbsensiSekolah::where('id_siswa', $s->id_siswa)
                ->whereDate('tanggal', $now->toDateString())
                ->first();

            if ($school && in_array(strtolower($school->status), ['izin', 'sakit'], true)) {
                try {
                    AbsensiPelajaran::create([
                        'id_jadwal'  => $jadwal->id_jadwal,
                        'id_siswa'   => $s->id_siswa,
                        'waktu_scan' => $now,
                        'status'     => strtolower($school->status),
                        'keterangan' => $school->keterangan ?? ucfirst($school->status),
                    ]);
                    $createdExcused++;
                } catch (\Exception $e) {
                    Log::error('Gagal menyimpan izin/sakit auto-finalize untuk siswa id ' . $s->id_siswa . ': ' . $e->getMessage());
                }
                continue;
            }

            // Selain itu, tandai ALFA otomatis
            try {
                AbsensiPelajaran::create([
                    'id_jadwal'  => $jadwal->id_jadwal,
                    'id_siswa'   => $s->id_siswa,
                    'waktu_scan' => $now,
                    'status'     => 'alfa',
                    'keterangan' => 'Otomatis alfa',
                ]);
                $createdAlpha++;
            } catch (\Exception $e) {
                Log::error('Gagal menyimpan auto-finalize absensi untuk siswa id ' . $s->id_siswa . ': ' . $e->getMessage());
            }
        }

        // Update last_finalized_at
        $jadwal->update([
            'last_finalized_at' => $now,
        ]);

        Log::info("Auto-finalize completed for schedule id: {$jadwal->id_jadwal}, Alfa: $createdAlpha, Izin/Sakit: $createdExcused");
        $this->info("✓ Schedule {$jadwal->id_jadwal} auto-finalized. Alfa: $createdAlpha, Izin/Sakit: $createdExcused");
    }
}
