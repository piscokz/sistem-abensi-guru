<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sekolah;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Shift;
use App\Models\JamMapel;
use App\Models\Jadwal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SekolahUserSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        Sekolah::truncate();
        User::truncate();
        Kelas::truncate();
        Guru::truncate();
        Mapel::truncate();
        Shift::truncate();
        JamMapel::truncate();
        Jadwal::truncate();

        Schema::enableForeignKeyConstraints();

        // 1. Buat sekolah
        $sekolah = Sekolah::create([
            'nama_sekolah'    => 'SMK Lentera Bangsa',
            'alamat_sekolah'  => '',
            'telepon_sekolah' => '',
            'email_sekolah'   => 'info@lentera-bangsa.sch.id',
        ]);

        // 2. Buat user role dasar
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

        // 3. Buat beberapa kelas dengan akun login masing-masing
        $daftarKelas = ['X RPL 1', 'X RPL 2', 'XI TKJ 1', 'XII MM 1'];
        $kelasData = [];
        foreach ($daftarKelas as $namaKelas) {
            $user = User::create([
                'sekolah_id' => $sekolah->id,
                'name'       => $namaKelas,
                'email'      => str_replace(' ', '_', strtolower($namaKelas)) . '@kelas.com',
                'password'   => Hash::make('kelas1234'),
                'role'       => 'kelas',
            ]);

            $kelasData[] = Kelas::create([
                'sekolah_id' => $sekolah->id,
                'user_id'    => $user->id,
                'nama_kelas' => $namaKelas,
            ]);
        }

        // 4. Buat mapel
        $mapels = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Produktif RPL',
            'Produktif TKJ',
            'Produktif MM'
        ];
        $mapelData = [];
        foreach ($mapels as $nama) {
            $mapelData[] = Mapel::create([
                'sekolah_id' => $sekolah->id,
                'nama_mapel' => $nama,
                'status'     => 'mapel',
            ]);
        }

        // 5. Buat guru (1 guru_mapel untuk tiap mapel)
        $guruData = [];
        foreach ($mapelData as $index => $mapel) {
            $user = User::create([
                'sekolah_id' => $sekolah->id,
                'name'       => 'Guru ' . $mapel->nama_mapel,
                'email'      => 'guru_' . strtolower(str_replace(' ', '_', $mapel->nama_mapel)) . '@gmail.com',
                'password'   => Hash::make('guru1234'),
                'role'       => 'guru_mapel',
            ]);

            $guruData[] = Guru::create([
                'sekolah_id' => $sekolah->id,
                'user_id'    => $user->id,
                'nip'        => '198' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'nama_guru'  => 'Guru ' . $mapel->nama_mapel,
            ]);
        }

        // 6. Buat shift (pagi & siang)
        $shiftPagi = Shift::create([
            'sekolah_id' => $sekolah->id,
            'nama'       => 'Pagi',
        ]);
        $shiftSiang = Shift::create([
            'sekolah_id' => $sekolah->id,
            'nama'       => 'Siang',
        ]);

        // 7. Buat jam_mapel untuk shift pagi (07:00 - 12:00)
        $jamMulai = ['07:00', '08:00', '09:00', '10:00', '11:00'];
        $jamSelesai = ['08:00', '09:00', '10:00', '11:00', '12:00'];
        foreach ($jamMulai as $i => $mulai) {
            JamMapel::create([
                'sekolah_id' => $sekolah->id,
                'shift_id'   => $shiftPagi->id,
                'nomor_jam'  => $i + 1,
                'jam_mulai'  => $mulai,
                'jam_selesai' => $jamSelesai[$i],
                'keterangan' => 'Jam ke-' . ($i + 1),
            ]);
        }

        // 8. Buat jam_mapel untuk shift siang (13:00 - 17:00)
        $jamMulai = ['13:00', '14:00', '15:00', '16:00'];
        $jamSelesai = ['14:00', '15:00', '16:00', '17:00'];
        foreach ($jamMulai as $i => $mulai) {
            JamMapel::create([
                'sekolah_id' => $sekolah->id,
                'shift_id'   => $shiftSiang->id,
                'nomor_jam'  => $i + 1,
                'jam_mulai'  => $mulai,
                'jam_selesai' => $jamSelesai[$i],
                'keterangan' => 'Jam ke-' . ($i + 1),
            ]);
        }

        // 9. Buat jadwal contoh untuk kelas pertama dengan shift pagi
        $jadwal = Jadwal::create([
            'sekolah_id'   => $sekolah->id,
            'kelas_id'     => $kelasData[0]->id,
            'tahun_ajaran' => now()->year,
            'is_active'    => true,
            'nama_jadwal'  => $shiftPagi->nama,
        ]);
        $jadwal->shifts()->attach($shiftPagi->id);
    }
}
