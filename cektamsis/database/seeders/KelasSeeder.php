<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_kelas' => 'X RPL', 'tingkat_kelas' => '1', 'total_siswa' => '20', 'id_jurusan' => 1, 'id_wali_kelas' => 1],
            ['nama_kelas' => 'XI RPL', 'tingkat_kelas' => '2', 'total_siswa' => '23', 'id_jurusan' => 1, 'id_wali_kelas' => 2],
            ['nama_kelas' => 'XII RPL', 'tingkat_kelas' => '3', 'total_siswa' => '25', 'id_jurusan' => 1, 'id_wali_kelas' => 3],

        ];

        foreach ($data as $kelas) {
            Kelas::create($kelas);
        }
    }
}
