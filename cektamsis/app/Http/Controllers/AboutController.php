<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutController extends Controller
{
    public function index()
    {
        // Hitung data dari database
        $totalSiswa = User::where('role', 'user')->count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalKelas = Kelas::count();
        $totalJurusan = Jurusan::count();

        return Inertia::render('Tentang', [
            'totalSiswa' => $totalSiswa,
            'totalGuru' => $totalGuru,
            'totalKelas' => $totalKelas,
            'totalJurusan' => $totalJurusan,
        ]);
    }
}