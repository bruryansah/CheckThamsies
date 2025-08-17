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
        $guru = DB::table('guru')->join('kelas', 'guru.id_kelas', '=', 'kelas.id_kelas')->join('mapel', 'guru.id_mapel', '=', 'mapel.id_mapel')->select('guru.id_guru', 'guru.nama', 'guru.email', 'kelas.nama_kelas as kelas', 'mapel.nama_mapel as mapel')->get();
        return Inertia::render('guru', ['guru' => $guru]);
    }

    public function tambah()
    {
        $users = \App\Models\User::all(['id', 'name', 'email']); // ambil user yang ada
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']); // ambil user yang ada
        $mapel = \App\Models\Mapel::all(['id_mapel', 'nama_mapel']); // ambil user yang ada
        return inertia('tambahguru', [
            'users' => $users,
            'kelas' => $kelas,
            'mapel' => $mapel
        ]);
    }

    public function store(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // id harus ada di tabel users
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_mapel' => 'required|exists:mapel,id_mapel',
        ]);

        // Simpan ke tabel guru
        DB::table('guru')->insert($validated);

        // Redirect balik ke halaman data guru
        return redirect()->route('guru')->with('success', 'Data guru berhasil ditambahkan!');
    }
    // Admin Section End
}
