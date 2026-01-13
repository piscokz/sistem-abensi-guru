<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\JadwalDetail;
use App\Models\JamMapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasSiswaController extends Controller
{
    public function index()
    {
        return 'halaman siswa';
    }

    public function jadwal()
    {
        $userId = Auth::user()->id;
        $kelas = Kelas::where('user_id', $userId)->first();
        $jadwals = $kelas->jadwals()->with('shift')->get();
        return view('level.kelas_siswa.jadwal.index', compact('kelas', 'jadwals'));
    }

    public function detail(Jadwal $jadwal)
    {
        $namaKelas = $jadwal->kelas->nama_kelas;
        $jadwalDetail = JadwalDetail::where('jadwal_id', $jadwal->id)
            ->with(['mapel', 'guru', 'jamMapel'])
            ->get()
            ->groupBy('hari');


        $order = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        // sort collection sesuai urutan manual
        $jadwalDetail = $jadwalDetail->sortBy(function ($value, $key) use ($order) {
            return array_search($key, $order);
        });

        $jamMapel = JamMapel::where('shift_id', $jadwal->shift->id)->orderBy('nomor_jam')->get();

        return view('level.kelas_siswa.jadwal.detail', compact('jadwal', 'namaKelas', 'jadwalDetail', 'jamMapel'));
    }
}
