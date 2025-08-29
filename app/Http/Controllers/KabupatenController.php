<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use Illuminate\Http\Request;

class KabupatenController extends Controller
{
    public function index()
    {
        $kabupatens = Kabupaten::orderBy('nama')->get();
        return view('admin.kabupaten.index', compact('kabupatens'));
    }

    public function create()
    {
        return view('admin.kabupaten.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kabupaten,nama',
        ]);

        Kabupaten::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.kabupaten.index')->with('success', 'Kabupaten berhasil ditambahkan.');
    }

    public function edit(Kabupaten $kabupaten)
    {
        return view('admin.kabupaten.edit', compact('kabupaten'));
    }

    public function update(Request $request, Kabupaten $kabupaten)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kabupaten,nama,' . $kabupaten->id,
        ]);

        $kabupaten->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.kabupaten.index')->with('success', 'Kabupaten berhasil diperbarui.');
    }

    public function destroy(Kabupaten $kabupaten)
    {
        $kabupaten->delete();
        return redirect()->route('admin.kabupaten.index')->with('success', 'Kabupaten berhasil dihapus.');
    }
}
