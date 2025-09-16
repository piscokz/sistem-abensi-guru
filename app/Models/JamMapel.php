<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JamMapel extends Model
{
    use HasFactory, Multitenantable;

    protected $fillable = ['sekolah_id', 'nomor_jam', 'jam_mulai', 'jam_selesai', 'keterangan'];

    public function getJamMulaiAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('H:i') : null;
    }

    public function getJamSelesaiAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('H:i') : null;
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
    public function jadwalDetails()
    {
        return $this->hasMany(JadwalDetail::class);
    }
}