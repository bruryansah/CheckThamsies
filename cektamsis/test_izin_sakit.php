<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

echo "=== TEST IZIN/SAKIT INTEGRATION ===\n\n";

$todayDate = Carbon::today('Asia/Jakarta')->toDateString();
echo "Tanggal hari ini: $todayDate\n\n";

// 1. Cek data absensi sekolah hari ini
echo "1. Data Absensi Sekolah (Izin/Sakit) hari ini:\n";
$absensiSekolah = DB::table('absensi_sekolah')
    ->whereDate('tanggal', $todayDate)
    ->whereIn('status', ['izin', 'sakit'])
    ->get();

if ($absensiSekolah->count() > 0) {
    foreach ($absensiSekolah as $abs) {
        echo "   - ID Siswa: {$abs->id_siswa}, Status: {$abs->status}, Keterangan: {$abs->keterangan}\n";
    }
} else {
    echo "   Tidak ada data izin/sakit hari ini\n";
}

echo "\n2. Test Query Join:\n";
$result = DB::table('siswa')
    ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
    ->leftJoin('absensi_sekolah as ask', function ($join) use ($todayDate) {
        $join->on('siswa.id_siswa', '=', 'ask.id_siswa')
             ->where('ask.tanggal', '=', $todayDate);
    })
    ->select(
        'siswa.id_siswa',
        'siswa.nama as nama_siswa',
        'kelas.nama_kelas',
        'ask.status as sekolah_status',
        'ask.keterangan as sekolah_keterangan'
    )
    ->whereNotNull('ask.status')
    ->get();

if ($result->count() > 0) {
    foreach ($result as $row) {
        echo "   - {$row->nama_siswa} ({$row->nama_kelas}): {$row->sekolah_status} - {$row->sekolah_keterangan}\n";
    }
} else {
    echo "   Tidak ada hasil dari query join\n";
}

echo "\n=== TEST SELESAI ===\n";
