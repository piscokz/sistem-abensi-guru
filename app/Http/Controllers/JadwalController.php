<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kela)
    {
    
    $jadwalAktif = $kela->jadwals()->where('is_active', true)->first();

    // Ambil semua detail jadwal (jika jadwal aktif ditemukan)
    $detailJadwal = [];
    if($jadwalAktif) {
        $detailJadwal = $jadwalAktif->jadwalDetails()
                            ->with(['slot', 'mapel', 'guru']) // Eager loading
                            ->orderBy('slot_id')
                            ->get()
                            ->groupBy('hari'); // Kelompokkan berdasarkan hari
    }

    // Kita akan membuat view ini nanti saat fokus ke manajemen jadwal
    return view('jadwal.show', compact('kela', 'jadwalAktif', 'detailJadwal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
