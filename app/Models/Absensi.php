<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = ['guru_id', 'jadwal_detail_id', 'waktu_absen', 'qr_token_id'];

    public function guru()
    {   
        return $this->belongsTo(Guru::class);
    }

    public function jadwalDetail()
    {
        return $this->belongsTo(JadwalDetail::class);
    }

    public function qrToken()
    {
        return $this->belongsTo(QrToken::class);
    }
}