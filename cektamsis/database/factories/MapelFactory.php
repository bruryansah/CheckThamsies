<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $table = 'mapel';
    protected $primaryKey = 'id_mapel';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'jenis_mapel', // produktif, normatif, adaptif
    ];

    // Relasi ke Guru (1 mapel bisa punya banyak guru)
    public function guru()
    {
        return $this->hasMany(Guru::class, 'id_mapel', 'id_mapel');
    }
}
