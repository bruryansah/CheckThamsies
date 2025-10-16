<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;
use App\Models\User;

class Siswa extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'siswa';

    // Primary key custom (bukan id default)
    protected $primaryKey = 'id_siswa';

    // Auto increment primary key
    public $incrementing = true;

    // Tipe data primary key
    protected $keyType = 'int';

    // Kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'id_kelas',
        'id_jurusan',
    ];

    // Relasi ke tabel kelas
public function kelas()
{
    return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
}


    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}