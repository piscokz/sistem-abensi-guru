<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailJamMapel extends Model
{
    use HasFactory, Multitenantable;

    protected $fillable = [
        'sekolah_id',
        'jadwal_detail_id',
        'jam_mapel_id',
        'jadwal_id',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function jadwalDetail()
    {
        return $this->belongsTo(JadwalDetail::class);
    }

    public function jamMapel()
    {
        return $this->belongsTo(JamMapel::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}
