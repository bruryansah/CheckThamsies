<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'user_id'   => 4,
                'nip'       => 800736128,
                'nama'      => 'Ali Achamad',
                'email'     => 'kiali@gmail.com',
                'id_mapel'  => 1,
            ],
            [
                'user_id'   => 5,
                'nip'       => 800172632,
                'nama'      => 'Juli Mohammad',
                'email'     => 'kijuli@gmail.com',
                'id_mapel'  => 2,
            ],
            [
                'user_id'   => 6,
                'nip'       => 800286456,
                'nama'      => 'Dwi Yoga',
                'email'     => 'kijalu@gmail.com',
                'id_mapel'  => 3,
            ],
        ];

        foreach ($data as $guru) {
            Guru::create($guru);
        }
    }
}
