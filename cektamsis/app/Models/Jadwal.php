<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }
}
