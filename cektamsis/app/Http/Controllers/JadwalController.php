<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

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

        $guru = Guru::where('user_id', $user->id)
            ->with(['mapel', 'kelas'])
            ->first();
        if (!$guru) {
            return Inertia::render('Guru/Dashboard', [
                'guru'       => null,
                'jadwalData' => [],
            ]);
        }

        $jadwal = Jadwal::with(['mapel', 'guru.kelas'])
            ->where('id_guru', $guru->id_guru)
            ->get();


        $jadwal = Jadwal::with(['mapel', 'kelas'])
            ->where('id_guru', $guru->id_guru)
            ->get();

        $jadwalFormatted = $jadwal->map(function ($item) {
            return [
                'id_jadwal'             => $item->id_jadwal,
                'mata_pelajaran' => $item->mapel->nama_mapel ?? '-',
                'nama_kelas'     => $item->kelas->nama_kelas ?? '-',
                'kelas_id'       => $item->guru->kelas->id_kelas ?? null,
                'tanggal'        => $item->hari,
                'jam_mulai'      => $item->jam_mulai,
                'jam_selesai'    => $item->jam_selesai,
                'guru_id'        => $item->id_guru,
            ];
        });



        $kelasList = \App\Models\Kelas::select('id_kelas as id', 'nama_kelas')->get();

        $absensi = DB::table('siswa')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->leftJoin('absensi_pelajaran', function ($join) {
                $join->on('siswa.id_siswa', '=', 'absensi_pelajaran.id_siswa');
            })
            ->leftJoin('jadwal', 'absensi_pelajaran.id_jadwal', '=', 'jadwal.id_jadwal')
            ->leftJoin('mapel', 'jadwal.id_mapel', '=', 'mapel.id_mapel')
            ->select(
                'siswa.id_siswa',
                'siswa.nama as nama_siswa',
                'kelas.nama_kelas',
                'mapel.nama_mapel',
                'absensi_pelajaran.waktu_scan',
                'absensi_pelajaran.status'
            )
            ->get()
            ->map(function ($row) {
                return [
                    'name'   => $row->nama_siswa,
                    'class'  => $row->nama_kelas,
                    'subject' => $row->nama_mapel ?? '-',
                    'date'   => $row->waktu_scan ? date('Y-m-d', strtotime($row->waktu_scan)) : date('Y-m-d'),
                    'time'   => $row->waktu_scan ? date('H:i', strtotime($row->waktu_scan)) : 'Belum Absen',
                    'status' => $row->status ?? 'Belum Absen',
                ];
            });

        return Inertia::render('Guru/Dashboard', [
            'guru' => [
                'id'   => $guru->id_guru,
                'nama' => $guru->nama,
                'nip'  => $guru->nip,
                'mapel' => $guru->mapel->nama_mapel ?? null,
                'kelas' => $guru->kelas->nama_kelas ?? null,
            ],
            'jadwalData' => $jadwalFormatted,
            'kelasData'   => $kelasList,
            'absensiData' => $absensi,

        ]);
    }
}
