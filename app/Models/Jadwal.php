<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory, Multitenantable;

    protected $fillable = [
        'sekolah_id',
        'kelas_id',
        'tahun_ajaran',
        'is_active',
        'nama_jadwal',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // 🔑 relasi ke shift melalui pivot jadwal_shifts
    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'jadwal_shifts')
            ->withTimestamps();
    }

    public function details()
    {
        return $this->hasMany(JadwalDetail::class);
    }
}
