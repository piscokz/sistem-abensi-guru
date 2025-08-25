<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory, Multitenantable;

    protected $fillable = [
        'user_id',
        'sekolah_id',
        'kelas_id',
        'nama_siswa',
        'nipd',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    
}
