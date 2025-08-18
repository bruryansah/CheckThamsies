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
        ->select('guru.id_guru', 'guru.nama', 'guru.email',
        'kelas.nama_kelas as kelas',
        'mapel.nama_mapel as mapel')->get();
        return Inertia::render('guru', ['guru' => $guru]);
    }

    public function tambah()
    {
        $users = \App\Models\User::where('role', 'guru')->select('id', 'name', 'email')->get();
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']); // ambil user yang ada
        $mapel = \App\Models\Mapel::all(['id_mapel', 'nama_mapel']); // ambil user yang ada
        return inertia('tambahguru', [
            'users' => $users,
            'kelas' => $kelas,
            'mapel' => $mapel,
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

    public function edit($id)
    {
        $guru = DB::table('guru')->where('id_guru', $id)->first(); // gunakan first()
        $users = \App\Models\User::all(['id', 'name', 'email']);
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']);
        $mapel = \App\Models\Mapel::all(['id_mapel', 'nama_mapel']);

        return inertia('editguru', [
            'guru' => $guru,
            'users' => $users,
            'kelas' => $kelas,
            'mapel' => $mapel,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_mapel' => 'required|exists:mapel,id_mapel',
        ]);

        // Update data guru
        $updated = DB::table('guru')
            ->where('id_guru', $id)
            ->update([
                'user_id' => $request->user_id,
                'nama' => $request->nama,
                'email' => $request->email,
                'id_kelas' => $request->id_kelas,
                'id_mapel' => $request->id_mapel,
            ]);

        if ($updated) {
            return redirect()->route('guru')->with('success', 'Data guru berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data!');
        }
    }
    public function destroy($id)
    {
        // mengambil data pegawai berdasarkan id yang dipilih
        DB::table('guru')->where('id_guru', operator: $id)->delete();
        // passing data pegawai yang didapat ke view edit.blade.php
        return redirect('/guru');
    }
    // Admin Section End

    // Siswa X Section Start
public function siswax()
{
    $siswax = DB::table('siswa')
        ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
        ->join('jurusan', 'siswa.id_jurusan', '=', 'jurusan.id_jurusan')
        ->select(
            'siswa.id_siswa',
            'siswa.nama',
            'siswa.email',
            'kelas.nama_kelas as kelas',
            'jurusan.nama_jurusan as jurusan'
        )
        ->where('kelas.nama_kelas', '=', 'X RPL') // Filter hanya kelas X RPL
        ->get();

    return Inertia::render('xrpl', ['siswa' => $siswax]);
}
}
