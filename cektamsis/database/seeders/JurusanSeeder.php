<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        $jurusan = [
            ['nama_jurusan' => 'Rekayasa Perangkat Lunak 1'],
            ['nama_jurusan' => 'Rekayasa Perangkat Lunak 2'],
            ['nama_jurusan' => 'Teknik Komputer dan Jaringan 1'],
            ['nama_jurusan' => 'Teknik Komputer dan Jaringan 2'],
            ['nama_jurusan' => 'Teknik Pemesinan 1'],
            ['nama_jurusan' => 'Teknik Pemesinan 2'],
            ['nama_jurusan' => 'Teknik Kendaraan Ringan 1'],
            ['nama_jurusan' => 'Teknik Kendaraan Ringan 2'],
            ['nama_jurusan' => 'Teknik dan Bisnis Sepeda Motor 1'],
            ['nama_jurusan' => 'Teknik dan Bisnis Sepeda Motor 2'],
            ['nama_jurusan' => 'Teknik Instalasi Tenaga Listrik'],
        ];

        foreach ($jurusan as $j) {
            Jurusan::create($j);
        }
    }
}
