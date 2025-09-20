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
            // Jurusan 1: RPL
            ['nama_kelas' => 'X RPL 1', 'tingkat_kelas' => 1, 'total_siswa' => 20, 'id_jurusan' => 1],
            ['nama_kelas' => 'X RPL 2', 'tingkat_kelas' => 1, 'total_siswa' => 22, 'id_jurusan' => 1],
            ['nama_kelas' => 'XI RPL 1', 'tingkat_kelas' => 2, 'total_siswa' => 23, 'id_jurusan' => 1],
            ['nama_kelas' => 'XI RPL 2', 'tingkat_kelas' => 2, 'total_siswa' => 24, 'id_jurusan' => 1],
            ['nama_kelas' => 'XII RPL 1', 'tingkat_kelas' => 3, 'total_siswa' => 25, 'id_jurusan' => 1],
            ['nama_kelas' => 'XII RPL 2', 'tingkat_kelas' => 3, 'total_siswa' => 26, 'id_jurusan' => 1],

            // Jurusan 2: TKJ
            ['nama_kelas' => 'X TKJ 1', 'tingkat_kelas' => 1, 'total_siswa' => 28, 'id_jurusan' => 2],
            ['nama_kelas' => 'X TKJ 2', 'tingkat_kelas' => 1, 'total_siswa' => 29, 'id_jurusan' => 2],
            ['nama_kelas' => 'XI TKJ 1', 'tingkat_kelas' => 2, 'total_siswa' => 27, 'id_jurusan' => 2],
            ['nama_kelas' => 'XI TKJ 2', 'tingkat_kelas' => 2, 'total_siswa' => 28, 'id_jurusan' => 2],
            ['nama_kelas' => 'XII TKJ 1', 'tingkat_kelas' => 3, 'total_siswa' => 30, 'id_jurusan' => 2],
            ['nama_kelas' => 'XII TKJ 2', 'tingkat_kelas' => 3, 'total_siswa' => 31, 'id_jurusan' => 2],

            // Jurusan 3: TP
            ['nama_kelas' => 'X TP 1', 'tingkat_kelas' => 1, 'total_siswa' => 24, 'id_jurusan' => 3],
            ['nama_kelas' => 'X TP 2', 'tingkat_kelas' => 1, 'total_siswa' => 25, 'id_jurusan' => 3],
            ['nama_kelas' => 'XI TP 1', 'tingkat_kelas' => 2, 'total_siswa' => 26, 'id_jurusan' => 3],
            ['nama_kelas' => 'XI TP 2', 'tingkat_kelas' => 2, 'total_siswa' => 27, 'id_jurusan' => 3],
            ['nama_kelas' => 'XII TP 1', 'tingkat_kelas' => 3, 'total_siswa' => 27, 'id_jurusan' => 3],
            ['nama_kelas' => 'XII TP 2', 'tingkat_kelas' => 3, 'total_siswa' => 28, 'id_jurusan' => 3],

            // Jurusan 4: TKR
            ['nama_kelas' => 'X TKR 1', 'tingkat_kelas' => 1, 'total_siswa' => 30, 'id_jurusan' => 4],
            ['nama_kelas' => 'X TKR 2', 'tingkat_kelas' => 1, 'total_siswa' => 29, 'id_jurusan' => 4],
            ['nama_kelas' => 'XI TKR 1', 'tingkat_kelas' => 2, 'total_siswa' => 28, 'id_jurusan' => 4],
            ['nama_kelas' => 'XI TKR 2', 'tingkat_kelas' => 2, 'total_siswa' => 27, 'id_jurusan' => 4],
            ['nama_kelas' => 'XII TKR 1', 'tingkat_kelas' => 3, 'total_siswa' => 27, 'id_jurusan' => 4],
            ['nama_kelas' => 'XII TKR 2', 'tingkat_kelas' => 3, 'total_siswa' => 26, 'id_jurusan' => 4],

            // Jurusan 5: TBSM
            ['nama_kelas' => 'X TBSM 1', 'tingkat_kelas' => 1, 'total_siswa' => 32, 'id_jurusan' => 5],
            ['nama_kelas' => 'X TBSM 2', 'tingkat_kelas' => 1, 'total_siswa' => 31, 'id_jurusan' => 5],
            ['nama_kelas' => 'XI TBSM 1', 'tingkat_kelas' => 2, 'total_siswa' => 31, 'id_jurusan' => 5],
            ['nama_kelas' => 'XI TBSM 2', 'tingkat_kelas' => 2, 'total_siswa' => 30, 'id_jurusan' => 5],
            ['nama_kelas' => 'XII TBSM 1', 'tingkat_kelas' => 3, 'total_siswa' => 30, 'id_jurusan' => 5],
            ['nama_kelas' => 'XII TBSM 2', 'tingkat_kelas' => 3, 'total_siswa' => 29, 'id_jurusan' => 5],

            // Jurusan 6: TITL (tetap 1 kelas)
            ['nama_kelas' => 'X TITL', 'tingkat_kelas' => 1, 'total_siswa' => 28, 'id_jurusan' => 6],
            ['nama_kelas' => 'XI TITL', 'tingkat_kelas' => 2, 'total_siswa' => 27, 'id_jurusan' => 6],
            ['nama_kelas' => 'XII TITL', 'tingkat_kelas' => 3, 'total_siswa' => 29, 'id_jurusan' => 6],
        ];

        foreach ($data as $kelas) {
            $kelas['id_wali_kelas'] = Guru::inRandomOrder()->first()->id_guru; // guru random
            Kelas::create($kelas);
        }
    }
}
