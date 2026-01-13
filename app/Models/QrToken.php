<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class QrToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'jadwal_detail_id',
        'kelas_id',
        'expires_at',
        'used',
        'created_by'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    public function jadwalDetail()
    {
        return $this->belongsTo(JadwalDetail::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function isValid(): bool
    {
        $now = Carbon::now('Asia/Jakarta');
        if ($this->used) return false;
        if ($this->expires_at && $this->expires_at->lessThanOrEqualTo($now)) return false;
        return true;
    }
}
