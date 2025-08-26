<?php

namespace App\Traits;

use App\Scopes\SekolahScope;
use Illuminate\Support\Facades\Auth;

trait Multitenantable
{
    /**
     * The "booted" method of the trait.
     *
     * @return void
     */
    protected static function bootMultitenantable()
    {
        // Hanya tambahkan Global Scope jika ada pengguna yang terautentikasi
        // dan tidak berada di konsol.
        if (Auth::check() && ! app()->runningInConsole()) {
            static::addGlobalScope(new SekolahScope());
        }

        // Isi sekolah_id secara otomatis saat membuat data baru.
        // Bagian ini tidak memerlukan pemeriksaan konsol.
        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->sekolah_id) {
                $model->sekolah_id = Auth::user()->sekolah_id;
            }
        });
    }
}
