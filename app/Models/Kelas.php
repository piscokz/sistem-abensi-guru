<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory, Multitenantable;

    protected $fillable = ['sekolah_id', 'nama_kelas', 'user_id'];
    
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function qrTokens()
    {
        return $this->hasMany(QrToken::class);
        
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}