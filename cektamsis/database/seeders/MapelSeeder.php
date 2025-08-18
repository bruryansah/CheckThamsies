<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\mapel;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mapel =[ ['nama_mapel' => 'Web Programming'],
        ['nama_mapel' => 'Pemogragaman Berbasis Object'],
        ['nama_mapel' => 'Database Management System'],
        ['nama_mapel' => 'Android Programming'],
        ['nama_mapel' => 'Ilmu Pengetahuan Alam)'],
        ['nama_mapel' => 'Matematika'],
        ['nama_mapel' => 'Bahasa Inggris'],
        ['nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan'],
        ['nama_mapel' => 'Bahasa Indonesia'],
        ['nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan'],
    ];
    
        foreach ($mapel as $m) {
            mapel::create($m);
        }
    }
}
