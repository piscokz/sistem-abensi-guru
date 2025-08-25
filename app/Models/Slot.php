<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory, Multitenantable;

    protected $fillable = ['sekolah_id', 'nomor_slot', 'jam_mulai', 'jam_selesai'];
}