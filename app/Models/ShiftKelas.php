<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftKelas extends Model
{
    use HasFactory, Multitenantable;

    protected $fillable = [
        'sekolah_id',
        'shift_id',
        'jadwal_id',
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

}
