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
    ];

    // Relasi: satu jadwal dimiliki oleh satu sekolah
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    // Relasi: satu jadwal dimiliki oleh satu kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi: satu jadwal punya banyak detail
    public function details()
    {
        return $this->hasMany(JadwalDetail::class);
    }
}
