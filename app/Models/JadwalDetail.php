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
        'guru_id',
        'hari',
    ];

    // Relasi ke jadwal induk
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    // Relasi ke mapel
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    // Relasi ke guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // Relasi ke slot
    // public function slot()
    // {
    //     return $this->belongsTo(Slot::class);
    // }

    public function shift() {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
}
