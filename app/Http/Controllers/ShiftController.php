<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::orderBy('nama')->get();
        return view('shift.index', compact('shifts'));
    }

    public function create()
    {
        return view('shift.create', ['shift' => new Shift()]);
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:100']);
        
    
        Shift::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('guru-piket.shift.index')->with('success', 'Shift berhasil ditambahkan.');
    }

    public function edit(Shift $shift)
    {
        return view('shift.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate(['nama' => 'required|string|max:100']);

        $shift->update(['nama' => $request->nama]);

        return redirect()->route('guru-piket.shift.index')->with('success', 'Shift berhasil diperbarui.');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();

        return redirect()->route('guru-piket.shift.index')->with('success', 'Shift berhasil dihapus.');
    }
}
