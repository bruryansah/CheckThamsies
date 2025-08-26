<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Guru;


class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Jurusan 1: RPL 1
            ['nama_kelas' => 'X RPL', 'tingkat_kelas' => 1, 'total_siswa' => 20, 'id_jurusan' => 1, 'id_wali_kelas' => 1],
            ['nama_kelas' => 'XI RPL', 'tingkat_kelas' => 2, 'total_siswa' => 23, 'id_jurusan' => 1, 'id_wali_kelas' => 2],
            ['nama_kelas' => 'XII RPL', 'tingkat_kelas' => 3, 'total_siswa' => 25, 'id_jurusan' => 1, 'id_wali_kelas' => 3],

            // Jurusan 3: TKJ 1
            ['nama_kelas' => 'X TKJ', 'tingkat_kelas' => 1, 'total_siswa' => 28, 'id_jurusan' => 2, 'id_wali_kelas' => 7],
            ['nama_kelas' => 'XI TKJ', 'tingkat_kelas' => 2, 'total_siswa' => 27, 'id_jurusan' => 2, 'id_wali_kelas' => 8],
            ['nama_kelas' => 'XII TKJ', 'tingkat_kelas' => 3, 'total_siswa' => 30, 'id_jurusan' => 2, 'id_wali_kelas' => 9],

            // Jurusan 5: TP 1
            ['nama_kelas' => 'X TP', 'tingkat_kelas' => 1, 'total_siswa' => 24, 'id_jurusan' => 3, 'id_wali_kelas' => 13],
            ['nama_kelas' => 'XI TP', 'tingkat_kelas' => 2, 'total_siswa' => 26, 'id_jurusan' => 3, 'id_wali_kelas' => 14],
            ['nama_kelas' => 'XII TP', 'tingkat_kelas' => 3, 'total_siswa' => 27, 'id_jurusan' => 3, 'id_wali_kelas' => 15],

            // Jurusan 7: TKR 1
            ['nama_kelas' => 'X TKR', 'tingkat_kelas' => 1, 'total_siswa' => 30, 'id_jurusan' => 4, 'id_wali_kelas' => 19],
            ['nama_kelas' => 'XI TKR', 'tingkat_kelas' => 2, 'total_siswa' => 28, 'id_jurusan' => 4, 'id_wali_kelas' => 20],
            ['nama_kelas' => 'XII TKR', 'tingkat_kelas' => 3, 'total_siswa' => 27, 'id_jurusan' => 4, 'id_wali_kelas' => 21],

            // Jurusan 9: TBSM 1
            ['nama_kelas' => 'X TBSM', 'tingkat_kelas' => 1, 'total_siswa' => 32, 'id_jurusan' => 5, 'id_wali_kelas' => 25],
            ['nama_kelas' => 'XI TBSM', 'tingkat_kelas' => 2, 'total_siswa' => 31, 'id_jurusan' => 5, 'id_wali_kelas' => 26],
            ['nama_kelas' => 'XII TBSM', 'tingkat_kelas' => 3, 'total_siswa' => 30, 'id_jurusan' => 5, 'id_wali_kelas' => 27],

            // Jurusan 11: TITL
            ['nama_kelas' => 'X TITL', 'tingkat_kelas' => 1, 'total_siswa' => 28, 'id_jurusan' => 6, 'id_wali_kelas' => 31],
            ['nama_kelas' => 'XI TITL', 'tingkat_kelas' => 2, 'total_siswa' => 27, 'id_jurusan' => 6, 'id_wali_kelas' => 32],
            ['nama_kelas' => 'XII TITL', 'tingkat_kelas' => 3, 'total_siswa' => 29, 'id_jurusan' => 6, 'id_wali_kelas' => 33],
        ];

        foreach ($data as $kelas) {
            $kelas['id_wali_kelas'] = Guru::inRandomOrder()->first()->id_guru; // ambil guru random
            Kelas::create($kelas);
        }
    }
}
