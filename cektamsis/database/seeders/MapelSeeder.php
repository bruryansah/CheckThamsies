<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mapel;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mapel = [
            ['nama_mapel' => 'Bahasa Indonesia'],
            ['nama_mapel' => 'Bahasa Inggris'],
            ['nama_mapel' => 'Mulok'],
            ['nama_mapel' => 'Mulok Jepang'],
            ['nama_mapel' => 'Matematika'],
            ['nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan'],
            ['nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan'],
            ['nama_mapel' => 'Pelajaran Kejuruan'],
            ['nama_mapel' => 'Ketamansiswaan'],
            ['nama_mapel' => 'Produk Kreatif'],
            ['nama_mapel' => 'Pendidikan Agama Islam'],
            ['nama_mapel' => 'Kewirausahaan'],
            ['nama_mapel' => 'Sejarah Indonesia'],
            ['nama_mapel' => 'Samsung Tech Institute'],
        ];

        foreach ($mapel as $m) {
            Mapel::create($m);
        }
    }
}
