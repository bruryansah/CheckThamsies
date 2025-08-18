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
                'nama'      => 'Ali Achamad',
                'email'     => 'kiali@gmail.com',
                'id_kelas'  => 1,
                'id_mapel'  => 1,
            ],
            [
                'user_id'   => 5,
                'nama'      => 'Juli Mohammad',
                'email'     => 'kijuli@gmail.com',
                'id_kelas'  => 2,
                'id_mapel'  => 2,
            ],
            [
                'user_id'   => 6,
                'nama'      => 'Dwi Yoga',
                'email'     => 'kijalu@gmail.com',
                'id_kelas'  => 3,
                'id_mapel'  => 3,
            ],
        ];

        foreach ($data as $guru) {
            Guru::create($guru);
        }
    }
}
