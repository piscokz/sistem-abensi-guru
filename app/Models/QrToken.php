<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrToken extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'jadwal_detail_id', 'generated_by_siswa_id', 'status', 'expires_at'];

    public function jadwalDetail()
    {
        return $this->belongsTo(JadwalDetail::class);
    }

    public function siswaGenerator()
    {
        return $this->belongsTo(Siswa::class, 'generated_by_siswa_id');
    }
}