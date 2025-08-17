<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\siswa;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'user_id'   => 6,
                'nama'      => 'Tubagus',
                'email'     => 'agus@gmail.com',
                'id_kelas'  => 1,
                'id_jurusan'  => 1,
            ],
            [
                'user_id'   => 7,
                'nama'      => 'Ryan',
                'email'     => 'ryan@gmail.com',
                'id_kelas'  => 2,
                'id_jurusan'  => 1,
            ],
            [
                'user_id'   => 8,
                'nama'      => 'Kenzo',
                'email'     => 'kenzo@gmail.com',
                'id_kelas'  => 3,
                'id_jurusan'  => 1,
            ],
        ];

        foreach ($data as $siswa) {
            Siswa::create($siswa);
        }
    }
}
