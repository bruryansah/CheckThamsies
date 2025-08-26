<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiSekolah extends Model
{
    use HasFactory;

    protected $table = 'absensi_sekolah'; // nama tabel di DB
    protected $primaryKey = 'id_absensi'; // primary key sesuai tabel
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_siswa',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'latitude_in',
        'longitude_in',
        'latitude_out',
        'longitude_out',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }
}
