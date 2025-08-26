<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SekolahUserSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks sementara untuk menghindari error
        Schema::disableForeignKeyConstraints();

        // Kosongkan tabel sekolahs dan users sebelum seeding
        Sekolah::truncate();
        User::truncate();

        // Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();

        // Buat satu data sekolah terlebih dahulu
        $sekolah = Sekolah::create([
            'nama_sekolah' => 'SMK Lentera Bangsa',
            'alamat_sekolah' => '',
            'telepon_sekolah' => '',
            'email_sekolah' => 'info@lentera-bangsa.sch.id',
        ]);

        // Peran-peran yang akan dibuat untuk pengguna
        $roles = ['kurikulum', 'guru_piket', 'guru_mapel', 'siswa', 'super_admin'];

        foreach ($roles as $role) {
            // Gunakan metode 'create' langsung, bukan factory,
            // untuk memastikan 'sekolah_id' disematkan.
            User::create([
                'sekolah_id' => $sekolah->id,
                'name' => ucwords(str_replace('_', ' ', $role)),
                'email' => $role . '@gmail.com',
                'password' => Hash::make($role . '1234'),
                'role' => $role,
            ]);
        }
    }
}
