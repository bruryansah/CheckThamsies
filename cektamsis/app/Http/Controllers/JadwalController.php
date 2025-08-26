<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Guru;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // ambil guru berdasarkan user_id
    $guru = Guru::where('user_id', $user->id)
        ->with(['mapel', 'kelas'])
        ->first();

    // kalau tidak ada guru
    if (!$guru) {
        return Inertia::render('Guru/Dashboard', [
            'guru'       => null,
            'jadwalData' => [],
        ]);
    }

    // ambil jadwal guru
    $jadwal = Jadwal::with(['guru.mapel', 'guru.kelas'])
        ->where('id_guru', $guru->id_guru)
        ->get();

    // format jadwal
    $jadwalFormatted = $jadwal->map(function ($item) {
        return [
            'id'             => $item->id_jadwal,
            'mata_pelajaran' => $item->guru->mapel->nama_mapel ?? '-',
            'nama_kelas'     => $item->guru->kelas->nama_kelas ?? '-',
            'kelas_id'       => $item->guru->kelas->id_kelas ?? null,
            'tanggal'        => $item->hari,
            'jam_mulai'      => $item->jam_mulai,
            'jam_selesai'    => $item->jam_selesai,
            'ruangan'        => $item->ruangan ?? '-',
            'guru_id'        => $item->id_guru,
        ];
    });



     $kelasList = \App\Models\Kelas::select('id_kelas as id', 'nama_kelas')->get();

    return Inertia::render('Guru/Dashboard', [
        'guru' => [
            'id'   => $guru->id_guru,
            'nama' => $guru->nama,
            'nip'  => $guru->nip,
            'mapel' => $guru->mapel->nama_mapel ?? null,
            'kelas' => $guru->kelas->nama_kelas ?? null,
        ],
        'jadwalData' => $jadwalFormatted,
        'kelasData'   => $kelasList
    ]);
}
}
