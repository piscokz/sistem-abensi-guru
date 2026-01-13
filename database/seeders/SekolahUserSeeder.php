<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sekolah;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Shift;
use App\Models\JamMapel;
use App\Models\JadwalDetail;
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
        JadwalDetail::truncate();
        Shift::truncate();
        JamMapel::truncate();
        Jadwal::truncate();

        Schema::enableForeignKeyConstraints();

        // 1. Sekolah
        $sekolah = Sekolah::create([
            'nama_sekolah'    => 'SMK Lentera Bangsa',
            'alamat_sekolah'  => '',
            'telepon_sekolah' => '',
            'email_sekolah'   => 'info@lentera-bangsa.sch.id',
        ]);

        // 2. Role dasar
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

        // 3. Kelas
        $kelasList = ['X RPL 1', 'XI RPL 1'];
        $kelasData = [];
        foreach ($kelasList as $namaKelas) {
            $user = User::create([
                'sekolah_id' => $sekolah->id,
                'name'       => $namaKelas,
                'email'      => str_replace(' ', '_', strtolower($namaKelas)) . '@gmail.com',
                'password'   => Hash::make('kelas1234'),
                'role'       => 'kelas_siswa',
            ]);

            $kelasData[] = Kelas::create([
                'sekolah_id' => $sekolah->id,
                'user_id'    => $user->id,
                'nama_kelas' => $namaKelas,
            ]);
        }

        // 4. Mapel
        $mapels = ['Matematika', 'Bahasa Indonesia', 'Produktif RPL'];
        $mapelData = collect($mapels)->map(fn($m) => Mapel::create([
            'sekolah_id' => $sekolah->id,
            'nama_mapel' => $m,
            'status'     => 'mapel',
        ]));

        // 5. Guru
        $guruData = $mapelData->map(function ($mapel, $i) use ($sekolah) {
            $user = User::create([
                'sekolah_id' => $sekolah->id,
                'name'       => 'Guru ' . $mapel->nama_mapel,
                'email'      => 'guru_' . strtolower(str_replace(' ', '_', $mapel->nama_mapel)) . '@gmail.com',
                'password'   => Hash::make('guru1234'),
                'role'       => 'guru_mapel',
            ]);
            return Guru::create([
                'sekolah_id' => $sekolah->id,
                'user_id'    => $user->id,
                'nip'        => '198' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'nama_guru'  => 'Guru ' . $mapel->nama_mapel,
            ]);
        });

        // 6. Shift
        $shiftPagi = Shift::create([
            'sekolah_id' => $sekolah->id,
            'nama'       => 'Pagi',
        ]);

        // 7. Jam mapel pagi
        $jamMulai = ['07:00', '08:00', '09:00', '10:00', '11:00'];
        $jamSelesai = ['08:00', '09:00', '10:00', '11:00', '23:00'];
        $jamPagiData = [];
        foreach ($jamMulai as $i => $mulai) {
            $jamPagiData[] = JamMapel::create([
                'sekolah_id' => $sekolah->id,
                'shift_id'   => $shiftPagi->id,
                'nomor_jam'  => $i + 1,
                'jam_mulai'  => $mulai,
                'jam_selesai' => $jamSelesai[$i],
            ]);
        }

        // 8. Jadwal per kelas
        $jadwalData = [];
        foreach ($kelasData as $kelas) {
            $jadwalData[] = Jadwal::create([
                'sekolah_id' => $sekolah->id,
                'kelas_id'   => $kelas->id,
                'shift_id'   => $shiftPagi->id,
                'nama_jadwal' => 'Pagi ' . $kelas->nama_kelas,
            ]);
        }

        // 9. Jadwal detail:
        //    - jam 1–2: Matematika (dua jam berurutan, guru sama)
        //    - jam 3: Bahasa Indonesia
        //    - jam 4: Produktif RPL
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        foreach ($jadwalData as $jadwal) {
            foreach ($hariList as $hari) {
                // jam 1–2 Matematika
                foreach ([0, 1] as $j) {
                    JadwalDetail::create([
                        'jadwal_id'    => $jadwal->id,
                        'jam_mapel_id' => $jamPagiData[$j]->id,
                        'mapel_id'     => $mapelData[0]->id,
                        'guru_id'      => $guruData[0]->id,
                        'hari'         => $hari,
                    ]);
                }

                // jam 3 Bahasa Indonesia
                JadwalDetail::create([
                    'jadwal_id'    => $jadwal->id,
                    'jam_mapel_id' => $jamPagiData[2]->id,
                    'mapel_id'     => $mapelData[1]->id,
                    'guru_id'      => $guruData[1]->id,
                    'hari'         => $hari,
                ]);

                // jam 4 Produktif RPL
                JadwalDetail::create([
                    'jadwal_id'    => $jadwal->id,
                    'jam_mapel_id' => $jamPagiData[3]->id,
                    'mapel_id'     => $mapelData[0]->id,
                    'guru_id'      => $guruData[0]->id,
                    'hari'         => $hari,
                ]);

                // jam 5 Bahasa Indo
                JadwalDetail::create([
                    'jadwal_id'    => $jadwal->id,
                    'jam_mapel_id' => $jamPagiData[4]->id,
                    'mapel_id'     => $mapelData[1]->id,
                    'guru_id'      => $guruData[1]->id,
                    'hari'         => $hari,
                ]);
            }
        }
    }
}
