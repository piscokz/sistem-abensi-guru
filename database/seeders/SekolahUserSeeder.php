<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sekolah;
use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SekolahUserSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks sementara untuk menghindari error
        Schema::disableForeignKeyConstraints();

        // Kosongkan tabel sebelum seeding
        Sekolah::truncate();
        User::truncate();
        Kelas::truncate();

        // Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();

        // Buat satu data sekolah
        $sekolah = Sekolah::create([
            'nama_sekolah'   => 'SMK Lentera Bangsa',
            'alamat_sekolah' => '',
            'telepon_sekolah' => '',
            'email_sekolah'  => 'info@lentera-bangsa.sch.id',
        ]);

        // Peran-peran dasar (selain kelas)
        $roles = ['kurikulum', 'guru_piket', 'guru_mapel', 'super_admin'];

        foreach ($roles as $role) {
            User::create([
                'sekolah_id' => $sekolah->id,
                'name'       => ucwords(str_replace('_', ' ', $role)),
                'email'      => $role . '@gmail.com',
                'password'   => Hash::make($role . '1234'),
                'role'       => $role,
            ]);
        }

        // Contoh generate beberapa kelas dengan akun login masing-masing
        $daftarKelas = ['X RPL 1', 'X RPL 2', 'XI TKJ 1', 'XII MM 1'];

        foreach ($daftarKelas as $namaKelas) {
            $user = User::create([
                'sekolah_id' => $sekolah->id,
                'name'       => $namaKelas,
                'email'      => str_replace(' ', '_', strtolower($namaKelas)) . '@kelas.com',
                'password'   => Hash::make('kelas1234'),
                'role'       => 'kelas',
            ]);

            Kelas::create([
                'sekolah_id'  => $sekolah->id,
                'user_id'     => $user->id,
                'nama_kelas'  => $namaKelas,
            ]);
        }
    }
}
