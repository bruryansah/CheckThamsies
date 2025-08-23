<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar jurusan (sesuai seeder Jurusan)
        $jurusan = [
            1 => 'RPL 1',
            2 => 'RPL 2',
            3 => 'TKJ 1',
            4 => 'TKJ 2',
            5 => 'TP 1',
            6 => 'TP 2',
            7 => 'TKR 1',
            8 => 'TKR 2',
            9 => 'TBSM 1',
            10 => 'TBSM 2',
            11 => 'TITL',
        ];

        // Misalkan ada 3 tingkatan kelas (X, XI, XII)
        $kelas = [
            1 => 'X',
            2 => 'XI',
            3 => 'XII',
        ];

        $userId = 1; // Lanjut dari user terakhir

        foreach ($jurusan as $jurusanId => $namaJurusan) {
            foreach ($kelas as $kelasId => $namaKelas) {
                for ($i = 1; $i <= 20; $i++) {
                    $namaSiswa = "Siswa {$namaKelas} {$namaJurusan} {$i}";
                    $emailSiswa = strtolower(str_replace(' ', '', $namaSiswa)) . "@gmail.com";

                    // Buat user login untuk siswa
                    $user = User::create([
                        'name' => $namaSiswa,
                        'email' => $emailSiswa,
                        'password' => Hash::make('password123'),
                        'role' => 'user', // Sesuaikan jika pakai enum UserRole
                    ]);

                    // Masukkan ke tabel siswa
                    Siswa::create([
                        'user_id' => $user->id,
                        'nama' => $namaSiswa,
                        'email' => $emailSiswa,
                        'id_kelas' => $kelasId,
                        'id_jurusan' => $jurusanId,
                    ]);

                    $userId++;
                }
            }
        }
    }
}
