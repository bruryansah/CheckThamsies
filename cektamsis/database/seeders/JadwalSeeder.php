<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Kelas;
use App\Models\Guru;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Daftar mata pelajaran (ID harus sesuai dengan MapelSeeder)
        $mapel = [
            1 => 'Bahasa Indonesia',
            2 => 'Bahasa Inggris',
            3 => 'Mulok',
            4 => 'Mulok Jepang',
            5 => 'Matematika',
            6 => 'Pendidikan Pancasila dan Kewarganegaraan',
            7 => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
            8 => 'Pelajaran Kejuruan',
            9 => 'Ketamansiswaan',
            10 => 'Produk Kreatif',
            11 => 'Pendidikan Agama Islam',
            12 => 'Kewirausahaan',
            13 => 'Sejarah Indonesia',
            14 => 'Samsung Tech Institute',
        ];

        // Hari-hari sekolah
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        // Definisi ruang per lantai (total 25 ruang)
        $ruangan = [
            1 => [1, 2, 3, 4],                 // lantai 1
            2 => [5, 6, 7, 8, 9, 10, 11],      // lantai 2
            3 => [12, 13, 14, 15, 16, 17, 18], // lantai 3
            4 => [19, 20, 21, 22, 23, 24, 25], // lantai 4
        ];

        // Ambil semua kelas dari database
        $kelasList = Kelas::all();

        $insertData = [];
        $maxTotal = 100; // total jadwal yang ingin di-generate
        $totalCount = 0;

        // Tracking jadwal ruangan agar tidak bentrok
        $jadwalRuangan = [];

        while ($totalCount < $maxTotal) {
            $hari = $hariList[array_rand($hariList)]; // hari acak
            $kelas = $kelasList->random(); // kelas acak

            $mulai = Carbon::createFromTime(6, 45, 0)
                ->addMinutes(rand(0, 450)); // waktu mulai acak antara 06:45 dan 14:15
            $durasi = rand(45, 60);
            $selesai = $mulai->copy()->addMinutes($durasi);

            // pastikan tidak lewat jam 15:10
            if ($selesai->gt(Carbon::createFromTime(15, 10, 0))) {
                continue;
            }

            // cari ruang kosong
            $tries = 0;
            $found = false;

            while ($tries < 100) {
                $lantaiRandom = rand(1, 4);
                $ruangCandidates = $ruangan[$lantaiRandom];
                $ruangRandom = $ruangCandidates[array_rand($ruangCandidates)];

                // cek bentrok ruangan di jam yang sama
                $bentrok = false;
                if (isset($jadwalRuangan[$hari][$ruangRandom])) {
                    foreach ($jadwalRuangan[$hari][$ruangRandom] as $slot) {
                        if ($mulai->lt($slot['selesai']) && $selesai->gt($slot['mulai'])) {
                            $bentrok = true;
                            break;
                        }
                    }
                }

                if (!$bentrok) {
                    $jadwalRuangan[$hari][$ruangRandom][] = [
                        'mulai' => $mulai->copy(),
                        'selesai' => $selesai->copy(),
                    ];
                    $lantai = $lantaiRandom;
                    $ruang = $ruangRandom;
                    $found = true;
                    break;
                }

                $tries++;
            }

            if (!$found) {
                continue; // kalau gak dapat ruang kosong, coba generate ulang
            }

            // pilih mapel dan guru acak
            $id_mapel = array_rand($mapel);
            $id_guru = Guru::inRandomOrder()->first()->id_guru;

            // simpan data
            $insertData[] = [
                'id_guru'     => $id_guru,
                'id_mapel'    => $id_mapel,
                'id_kelas'    => $kelas->id_kelas,
                'hari'        => $hari,
                'jam_mulai'   => $mulai->format('H:i:s'),
                'jam_selesai' => $selesai->format('H:i:s'),
                'lantai'      => $lantai,
                'ruang'       => $ruang,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];

            $totalCount++;
        }

        // Masukkan ke database
        if (!empty($insertData)) {
            DB::table('jadwal')->insert($insertData);
        }

        echo "✅ Seeder Jadwal selesai. Total jadwal dibuat: {$totalCount}\n";
    }
}
