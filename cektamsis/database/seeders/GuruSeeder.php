<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Ketua Kompetensi Keahlian
            [
                'user_id'   => 1,
                'nip'       => 801112001,
                'nama'      => 'Ki Fatahullah NR',
                'email'     => 'fatahullah@gmail.com',
                'id_mapel'  => 1, // TITL
            ],
            [
                'user_id'   => 2,
                'nip'       => 801112002,
                'nama'      => 'Ki Gatot Sukoco',
                'email'     => 'gatot@gmail.com',
                'id_mapel'  => 2, // TKR
            ],
            [
                'user_id'   => 3,
                'nip'       => 801112003,
                'nama'      => 'Ki Tri Kurnianto',
                'email'     => 'tri.kurnianto@gmail.com',
                'id_mapel'  => 3, // TP
            ],
            [
                'user_id'   => 4,
                'nip'       => 801112004,
                'nama'      => 'Nyi Rimay Kwartiyanti',
                'email'     => 'rimay.kwartiyanti@gmail.com',
                'id_mapel'  => 4, // TKJ
            ],
            [
                'user_id'   => 5,
                'nip'       => 801112005,
                'nama'      => 'Ki A. Ircham Ali',
                'email'     => 'ircham.ali@gmail.com',
                'id_mapel'  => 5, // RPL
            ],
            [
                'user_id'   => 6,
                'nip'       => 801112006,
                'nama'      => 'Ki Supriono',
                'email'     => 'supriono@gmail.com',
                'id_mapel'  => 6, // TBSM
            ],
        ];

        foreach ($data as $guru) {
            Guru::updateOrCreate(
                ['nip' => $guru['nip']],
                $guru
            );
        }

    }
}
