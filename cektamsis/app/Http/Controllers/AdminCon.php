<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminCon extends Controller
{
    // Admin Section Start
    public function index()
    {
        $guru = DB::table('guru')
        ->join('kelas', 'guru.id_kelas', '=', 'kelas.id_kelas')
        ->join('mapel', 'guru.id_mapel', '=', 'mapel.id_mapel')
        ->select('guru.id_guru',
        'guru.nama',
        'guru.email',
        'kelas.nama_kelas as kelas',
        'mapel.nama_mapel as mapel')
        ->get();
        return Inertia::render('guru', ['guru' => $guru]);
    }

    public function tambah()
    {
        return Inertia::render('tambahguru');
    }

    // Admin Section End
}
