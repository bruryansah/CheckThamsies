<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    public $timestamps = false;

    protected $fillable = [
        'nama_kelas',
        'tingkat_kelas',
        'total_siswa',
        'id_jurusan',
        'id_wali_kelas',
    ];

    // Relasi ke jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    // Relasi ke wali kelas (guru)
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'id_wali_kelas', 'id_guru');
    }

    public function siswa()
    {
        return $this->hasMany(\App\Models\Siswa::class, 'id_kelas', 'id_kelas');
    }
}
