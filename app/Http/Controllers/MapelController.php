<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index()
    {
        $daftarMapel = Mapel::orderBy('nama_mapel')->get();
        return view('mapel.index', compact('daftarMapel'));
    }

    public function create()
    {
        return view('mapel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            // 'status' => 'required|in:mapel,kosong',
        ]);

        Mapel::create($request->all());

        return redirect()->route('guru-piket.mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Mapel $mapel)
    {
        return view('mapel.edit', compact('mapel'));
    }

    public function update(Request $request, Mapel $mapel)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            // 'status' => 'required|in:mapel,kosong',
        ]);

        $mapel->update($request->all());

        return redirect()->route('guru-piket.mapel.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Mapel $mapel)
    {
        $mapel->delete();

        return redirect()->route('guru-piket.mapel.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
