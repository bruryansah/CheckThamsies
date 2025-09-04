<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiPelajaran extends Model
{
    protected $table = 'absensi_pelajaran';
    protected $primaryKey = 'id_absensi_pelajaran';
    public $timestamps = true; // ✅ penting!

    protected $fillable = [
        'id_jadwal',
        'id_siswa',
        'waktu_scan',
        'status',
        'keterangan',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
}
