<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_id',
        'mapel_id',
        'jam_mapel_id',
        'guru_id',
        'hari',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function jamMapel()
    {
        return $this->belongsTo(JamMapel::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function detailJamMapels()
    {
        return $this->hasMany(DetailJamMapel::class);
    }
}
