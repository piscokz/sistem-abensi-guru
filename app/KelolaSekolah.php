<?php

namespace App\Traits;

use App\Scopes\SekolahScope;
use Illuminate\Support\Facades\Auth;

trait Multitenantable
{
    protected static function bootMultitenantable()
    {
        // Terapkan scope saat mengambil data
        static::addGlobalScope(new SekolahScope());

        // Isi sekolah_id secara otomatis saat membuat data baru
        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->sekolah_id) {
                $model->sekolah_id = Auth::user()->sekolah_id;
            }
        });
    }
}