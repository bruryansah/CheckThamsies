<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jadwal')->insert([
            [
                'id_guru'    => 7,
                'id_mapel'   => 14,
                'id_kelas'   => 1,
                'hari'       => '2025-08-01',
                'jam_mulai'  => '08:00:00',
                'jam_selesai' => '12:00:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_guru'    => 6,
                'id_mapel'   => 9,
                'id_kelas'   => 7,
                'hari'       => '2025-08-02',
                'jam_mulai'  => '09:00:00',
                'jam_selesai' => '11:00:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_guru'    => 5,
                'id_mapel'   => 5,
                'id_kelas'   => 2,
                'hari'       => '2025-08-03',
                'jam_mulai'  => '09:00:00',
                'jam_selesai' => '11:00:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
