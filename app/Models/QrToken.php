<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrToken extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'jadwal_detail_id', 'kelas_id', 'status', 'expires_at'];

    public function jadwalDetail()
    {
        return $this->belongsTo(JadwalDetail::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function absensi() {
        return $this->hasOne(Absensi::class);
    }
}