<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory, Multitenantable;

    protected $fillable = ['sekolah_id', 'nama_mapel', 'status'];

    public function gurus()
    {
        return $this->belongsToMany(Guru::class);
    }
}