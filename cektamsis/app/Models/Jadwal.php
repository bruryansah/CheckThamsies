<?php

namespace App\Models;

use App\Models\AbsensiPelajaran;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Jadwal extends Model
{
    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'id_guru',
        'id_mapel',
        'id_kelas',
        'hari',
        'lantai',
        'ruang',
        'jam_mulai',
        'jam_selesai',
        'status',
        'last_finalized_at',
    ];

    protected $casts = [
        'last_finalized_at' => 'datetime',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel', 'id_mapel');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function absensiPelajaran()
    {
        return $this->hasMany(AbsensiPelajaran::class, 'id_jadwal');
    }

    /**
     * Tentukan apakah jadwal aktif hari ini
     */
    public function isActiveToday()
    {
        $now = Carbon::now('Asia/Jakarta');
        $todayHari = strtolower($now->locale('id')->dayName);
        $hariJadwal = strtolower($this->hari);

        // Mapping hari
        $mapHari = [
            'minggu' => 'minggu',
            'senin' => 'senin',
            'selasa' => 'selasa',
            'rabu' => 'rabu',
            'kamis' => 'kamis',
            'jumat' => 'jumat',
            'sabtu' => 'sabtu',
        ];

        $todayHariMapped = $mapHari[$todayHari] ?? $todayHari;

        // Jika hari tidak sesuai, jadwal tidak aktif
        if ($todayHariMapped !== $hariJadwal) {
            return false;
        }

        // Jika sudah finalized hari ini, jadwal tutup
        if ($this->last_finalized_at) {
            $lastFinalizedDate = $this->last_finalized_at->toDateString();
            $todayDate = $now->toDateString();
            if ($lastFinalizedDate === $todayDate) {
                return false;
            }
        }

        return true;
    }

    /**
     * Tentukan status jadwal (aktif/tutup)
     */
    public function getStatusAttribute()
    {
        return $this->isActiveToday() ? 'aktif' : 'tutup';
    }
}
