<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\jurusan;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        $jurusan = [
            ['nama_jurusan' => 'Rekayasa Perangkat Lunak'],
        ];
        foreach ($jurusan as $j) {
            jurusan::create($j);
        }
    }
}
