<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    use HasFactory;

    /**
     * Nama tabel
     */
    protected $table = 'absensi_sekolah';

    /**
     * Primary key
     */
    protected $primaryKey = 'id_absensi';

    /**
     * Tipe primary key
     */
    protected $keyType = 'int';

    /**
     * Auto increment
     */
    public $incrementing = true;

    /**
     * Mass assignable
     */
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

    /**
     * Aktifkan timestamps hanya kalau tabel punya created_at & updated_at
     */
    public $timestamps = false; // 🔄 ubah ke true kalau tabel punya kolom ini

    /**
     * Casting untuk kolom tanggal & jam
     */
    protected $casts = [
        'tanggal'    => 'date',
        'jam_masuk'  => 'datetime:H:i:s',
        'jam_keluar' => 'datetime:H:i:s',
    ];

    /**
     * Relasi: satu absen milik satu siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }
}