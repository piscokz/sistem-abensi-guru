<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sekolah extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_sekolah',
        'email_sekolah',
        'telepon_sekolah',
        'alamat_sekolah',
    ];

    public function gurus()
    {
        return $this->hasMany(Guru::class);
    }
    
    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
}
