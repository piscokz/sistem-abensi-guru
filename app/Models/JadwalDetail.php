<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDetail extends Model
{
    use HasFactory;

    protected $fillable = ['jadwal_id', 'slot_id', 'mapel_id', 'guru_id', 'hari'];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}