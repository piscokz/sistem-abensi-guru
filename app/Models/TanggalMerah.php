<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TanggalMerah extends Model
{
    protected $fillable = ['tanggal', 'keterangan'];
    public $timestamps = true;
}
