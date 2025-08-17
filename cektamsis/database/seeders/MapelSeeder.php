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
        $mapel = ['nama' => 'Web Programming'];
        $mapel = ['nama' => 'Pemogragaman Berbasis Object'];
        $mapel = ['nama' => 'Database Management System'];

        foreach ($mapel as $m) {
            mapel::create($m);
        }
    }
}
