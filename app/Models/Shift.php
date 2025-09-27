<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory, Multitenantable;

    protected $fillable = ['nama', 'sekolah_id'];

    public function jamMapels()
    {
        return $this->hasMany(JamMapel::class);
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}
