<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Kelas;

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

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        $insertData = [];

        // Ambil semua kelas dari tabel kelas
        $kelasList = Kelas::all();

        foreach ($kelasList as $kelas) {
            foreach ($hariList as $hari) {
                $mulai = Carbon::createFromTime(6, 45, 0);

                while ($mulai->lt(Carbon::createFromTime(15, 10, 0))) {
                    // Durasi pelajaran acak (40 - 90 menit)
                    $durasi = rand(40, 90);
                    $selesai = $mulai->copy()->addMinutes($durasi);

                    // Batas maksimal 15:10
                    if ($selesai->gt(Carbon::createFromTime(15, 10, 0))) {
                        $selesai = Carbon::createFromTime(15, 10, 0);
                    }

                    // Ambil mapel acak
                    $id_mapel = array_rand($mapel);

                    $insertData[] = [
                        'id_guru'     => \App\Models\Guru::inRandomOrder()->first()->id_guru,
                        'id_mapel'    => $id_mapel,
                        'id_kelas'    => $kelas->id_kelas,
                        'hari'        => $hari,
                        'jam_mulai'   => $mulai->format('H:i:s'),
                        'jam_selesai' => $selesai->format('H:i:s'),
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ];

                    $mulai = $selesai;
                }
            }
        }

        DB::table('jadwal')->insert($insertData);
    }
}
