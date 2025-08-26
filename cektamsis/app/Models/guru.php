<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'guru';

    // Primary key custom (bukan id default)
    protected $primaryKey = 'id_guru';

    // Jika primary key bukan auto increment integer (misal UUID), bisa set false.
    // Karena disini integer auto increment, biarkan default true.
    public $incrementing = true;

    // Jika primary key bukan tipe int, set ke string. Defaultnya int.
    protected $keyType = 'int';

    // Kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'user_id',
        'nip',
        'nama',
        'email',
        'id_mapel',
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
    }
}
