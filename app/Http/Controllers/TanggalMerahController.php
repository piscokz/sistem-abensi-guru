<?php

namespace App\Http\Controllers;

use App\Models\TanggalMerah;
use Illuminate\Http\Request;

class TanggalMerahController extends Controller
{
    public function index()
    {
        $liburs = TanggalMerah::orderBy('tanggal', 'desc')->get();
        return view('libur.index', compact('liburs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|unique:tanggal_merahs,tanggal',
            'keterangan' => 'nullable|string|max:255'
        ]);

        TanggalMerah::create($request->only('tanggal', 'keterangan'));

        return back()->with('success', 'Tanggal libur berhasil ditambahkan.');
    }

    public function destroy(TanggalMerah $libur)
    {
        $libur->delete();
        return back()->with('success', 'Tanggal libur berhasil dihapus.');
    }
}
