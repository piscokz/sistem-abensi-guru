<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;

class Guru extends Model
{
    use HasFactory, Multitenantable;

    protected $fillable = ['user_id', 'sekolah_id', 'nip', 'nama_guru', 'nomor_telepon'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function mapels()
    {
        return $this->belongsToMany(Mapel::class);
    }
}