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

        // Daftar mapel (ID harus sesuai MapelSeeder yang kamu punya)
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

        // Hanya seminggu: Senin - Jumat
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        // Definisi ruang per lantai (total 25 ruang)
        $ruangan = [
            1 => [1, 2, 3, 4],                 // lantai 1 (4 ruang)
            2 => [5, 6, 7, 8, 9, 10, 11],      // lantai 2 (7 ruang)
            3 => [12, 13, 14, 15, 16, 17, 18], // lantai 3 (7 ruang)
            4 => [19, 20, 21, 22, 23, 24, 25], // lantai 4 (7 ruang)
        ];

        $insertData = [];

        // Ambil semua kelas dari tabel kelas
        $kelasList = Kelas::all();

        // Ambil semua guru
        $guruList = Guru::all();

        if ($guruList->isEmpty()) {
            return; // stop kalau tidak ada guru
        }

        // Mapping guru -> 2 mapel random
        $guruMapel = [];
        foreach ($guruList as $guru) {
            $guruMapel[$guru->id_guru] = array_rand($mapel, 2);
            // array_rand bisa return int kalau jumlahnya 1, jadi pastikan jadi array
            if (!is_array($guruMapel[$guru->id_guru])) {
                $guruMapel[$guru->id_guru] = [$guruMapel[$guru->id_guru]];
            }
        }

        // Batasi total jadwal mingguan
        $maxTotal = 50;
        $totalCount = 0;

        foreach ($hariList as $hari) {
            $jadwalRuangan = []; // tracking ruang per hari

            foreach ($kelasList as $kelas) {
                $mulai = Carbon::createFromTime(6, 45, 0);

                while ($mulai->lt(Carbon::createFromTime(15, 10, 0))) {
                    if ($totalCount >= $maxTotal) {
                        break 3; // keluar semua loop
                    }

                    // Durasi pelajaran acak (45 - 60 menit)
                    $durasi = rand(45, 60);
                    $selesai = $mulai->copy()->addMinutes($durasi);

                    if ($selesai->gt(Carbon::createFromTime(15, 10, 0))) {
                        break;
                    }

                    // Cari ruang kosong
                    $found = false;
                    $tries = 0;
                    while ($tries < 50) {
                        $lantaiRandom = rand(1, 4);
                        $ruangCandidates = $ruangan[$lantaiRandom];
                        $ruangRandom = $ruangCandidates[array_rand($ruangCandidates)];

                        $bentrok = false;
                        if (isset($jadwalRuangan[$ruangRandom])) {
                            foreach ($jadwalRuangan[$ruangRandom] as $slot) {
                                if ($mulai->lt($slot['selesai']) && $selesai->gt($slot['mulai'])) {
                                    $bentrok = true;
                                    break;
                                }
                            }
                        }

                        if (!$bentrok) {
                            $jadwalRuangan[$ruangRandom][] = [
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
                        break;
                    }

                    // Pilih guru random
                    $guru = $guruList->random();

                    // Pilih mapel dari 2 mapel milik guru ini
                    $mapelPilihan = $guruMapel[$guru->id_guru];
                    $id_mapel = $mapelPilihan[array_rand($mapelPilihan)];

                    $insertData[] = [
                        'id_guru'     => $guru->id_guru,
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
                    $mulai = $selesai;
                }
            }
        }

        if (!empty($insertData)) {
            DB::table('jadwal')->insert($insertData);
        }
    }
}
