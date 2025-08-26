<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        $jurusan = [
            ['nama_jurusan' => 'Rekayasa Perangkat Lunak'],
            ['nama_jurusan' => 'Teknik Komputer dan Jaringan'],
            ['nama_jurusan' => 'Teknik Pemesinan'],
            ['nama_jurusan' => 'Teknik Kendaraan Ringan'],
            ['nama_jurusan' => 'Teknik dan Bisnis Sepeda Motor'],
            ['nama_jurusan' => 'Teknik Instalasi Tenaga Listrik'],
        ];

        foreach ($jurusan as $j) {
            Jurusan::create($j);
        }
    }
}
