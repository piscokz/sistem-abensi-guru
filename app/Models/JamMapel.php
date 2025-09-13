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

    public function JamMulai($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function JamSelesai($value)
    {
        return Carbon::parse($value)->format('H:i');
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}