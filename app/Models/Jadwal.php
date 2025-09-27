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
        'shift_id',
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
    
    public function shift() {
        return $this->belongsTo(Shift::class);
    }

    public function detailJamMapels() {
        return $this->hasMany(DetailJamMapel::class);
    }

    public function details() {
        return $this->hasMany(JadwalDetail::class);
    }
}
