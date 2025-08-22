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
            ['nama_kelas' => 'X RPL 1', 'tingkat_kelas' => 1, 'total_siswa' => 20, 'id_jurusan' => 1, 'id_wali_kelas' => 1],
            ['nama_kelas' => 'XI RPL 1', 'tingkat_kelas' => 2, 'total_siswa' => 23, 'id_jurusan' => 1, 'id_wali_kelas' => 2],
            ['nama_kelas' => 'XII RPL 1', 'tingkat_kelas' => 3, 'total_siswa' => 25, 'id_jurusan' => 1, 'id_wali_kelas' => 3],

            // Jurusan 2: RPL 2
            ['nama_kelas' => 'X RPL 2', 'tingkat_kelas' => 1, 'total_siswa' => 21, 'id_jurusan' => 2, 'id_wali_kelas' => 4],
            ['nama_kelas' => 'XI RPL 2', 'tingkat_kelas' => 2, 'total_siswa' => 22, 'id_jurusan' => 2, 'id_wali_kelas' => 5],
            ['nama_kelas' => 'XII RPL 2', 'tingkat_kelas' => 3, 'total_siswa' => 24, 'id_jurusan' => 2, 'id_wali_kelas' => 6],

            // Jurusan 3: TKJ 1
            ['nama_kelas' => 'X TKJ 1', 'tingkat_kelas' => 1, 'total_siswa' => 28, 'id_jurusan' => 3, 'id_wali_kelas' => 7],
            ['nama_kelas' => 'XI TKJ 1', 'tingkat_kelas' => 2, 'total_siswa' => 27, 'id_jurusan' => 3, 'id_wali_kelas' => 8],
            ['nama_kelas' => 'XII TKJ 1', 'tingkat_kelas' => 3, 'total_siswa' => 30, 'id_jurusan' => 3, 'id_wali_kelas' => 9],

            // Jurusan 4: TKJ 2
            ['nama_kelas' => 'X TKJ 2', 'tingkat_kelas' => 1, 'total_siswa' => 26, 'id_jurusan' => 4, 'id_wali_kelas' => 10],
            ['nama_kelas' => 'XI TKJ 2', 'tingkat_kelas' => 2, 'total_siswa' => 25, 'id_jurusan' => 4, 'id_wali_kelas' => 11],
            ['nama_kelas' => 'XII TKJ 2', 'tingkat_kelas' => 3, 'total_siswa' => 29, 'id_jurusan' => 4, 'id_wali_kelas' => 12],

            // Jurusan 5: TP 1
            ['nama_kelas' => 'X TP 1', 'tingkat_kelas' => 1, 'total_siswa' => 24, 'id_jurusan' => 5, 'id_wali_kelas' => 13],
            ['nama_kelas' => 'XI TP 1', 'tingkat_kelas' => 2, 'total_siswa' => 26, 'id_jurusan' => 5, 'id_wali_kelas' => 14],
            ['nama_kelas' => 'XII TP 1', 'tingkat_kelas' => 3, 'total_siswa' => 27, 'id_jurusan' => 5, 'id_wali_kelas' => 15],

            // Jurusan 6: TP 2
            ['nama_kelas' => 'X TP 2', 'tingkat_kelas' => 1, 'total_siswa' => 23, 'id_jurusan' => 6, 'id_wali_kelas' => 16],
            ['nama_kelas' => 'XI TP 2', 'tingkat_kelas' => 2, 'total_siswa' => 22, 'id_jurusan' => 6, 'id_wali_kelas' => 17],
            ['nama_kelas' => 'XII TP 2', 'tingkat_kelas' => 3, 'total_siswa' => 24, 'id_jurusan' => 6, 'id_wali_kelas' => 18],

            // Jurusan 7: TKR 1
            ['nama_kelas' => 'X TKR 1', 'tingkat_kelas' => 1, 'total_siswa' => 30, 'id_jurusan' => 7, 'id_wali_kelas' => 19],
            ['nama_kelas' => 'XI TKR 1', 'tingkat_kelas' => 2, 'total_siswa' => 28, 'id_jurusan' => 7, 'id_wali_kelas' => 20],
            ['nama_kelas' => 'XII TKR 1', 'tingkat_kelas' => 3, 'total_siswa' => 27, 'id_jurusan' => 7, 'id_wali_kelas' => 21],

            // Jurusan 8: TKR 2
            ['nama_kelas' => 'X TKR 2', 'tingkat_kelas' => 1, 'total_siswa' => 29, 'id_jurusan' => 8, 'id_wali_kelas' => 22],
            ['nama_kelas' => 'XI TKR 2', 'tingkat_kelas' => 2, 'total_siswa' => 28, 'id_jurusan' => 8, 'id_wali_kelas' => 23],
            ['nama_kelas' => 'XII TKR 2', 'tingkat_kelas' => 3, 'total_siswa' => 30, 'id_jurusan' => 8, 'id_wali_kelas' => 24],

            // Jurusan 9: TBSM 1
            ['nama_kelas' => 'X TBSM 1', 'tingkat_kelas' => 1, 'total_siswa' => 32, 'id_jurusan' => 9, 'id_wali_kelas' => 25],
            ['nama_kelas' => 'XI TBSM 1', 'tingkat_kelas' => 2, 'total_siswa' => 31, 'id_jurusan' => 9, 'id_wali_kelas' => 26],
            ['nama_kelas' => 'XII TBSM 1', 'tingkat_kelas' => 3, 'total_siswa' => 30, 'id_jurusan' => 9, 'id_wali_kelas' => 27],

            // Jurusan 10: TBSM 2
            ['nama_kelas' => 'X TBSM 2', 'tingkat_kelas' => 1, 'total_siswa' => 31, 'id_jurusan' => 10, 'id_wali_kelas' => 28],
            ['nama_kelas' => 'XI TBSM 2', 'tingkat_kelas' => 2, 'total_siswa' => 30, 'id_jurusan' => 10, 'id_wali_kelas' => 29],
            ['nama_kelas' => 'XII TBSM 2', 'tingkat_kelas' => 3, 'total_siswa' => 33, 'id_jurusan' => 10, 'id_wali_kelas' => 30],

            // Jurusan 11: TITL
            ['nama_kelas' => 'X TITL', 'tingkat_kelas' => 1, 'total_siswa' => 28, 'id_jurusan' => 11, 'id_wali_kelas' => 31],
            ['nama_kelas' => 'XI TITL', 'tingkat_kelas' => 2, 'total_siswa' => 27, 'id_jurusan' => 11, 'id_wali_kelas' => 32],
            ['nama_kelas' => 'XII TITL', 'tingkat_kelas' => 3, 'total_siswa' => 29, 'id_jurusan' => 11, 'id_wali_kelas' => 33],
        ];

        foreach ($data as $kelas) {
            $kelas['id_wali_kelas'] = Guru::inRandomOrder()->first()->id_guru; // ambil guru random
            Kelas::create($kelas);
        }
    }
}
