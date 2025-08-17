<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_kelas' => 'X RPL', 'id_jurusan' => 1],
            ['nama_kelas' => 'XI RPL', 'id_jurusan' => 1],
            ['nama_kelas' => 'XII RPL', 'id_jurusan' => 1],

        ];

        foreach ($data as $kelas) {
            Kelas::create($kelas);
        }
    }
}
