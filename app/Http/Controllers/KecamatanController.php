<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;

use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::orderBy('nama')->paginate(15);
        return view('admin.kecamatan.index', compact('kecamatans'));
    }

    public function create()
    {
        return view('admin.kecamatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kecamatan,nama',
        ]);

        Kecamatan::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.kecamatan.index')->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    public function edit(Kecamatan $kecamatan)
    {
        return view('admin.kecamatan.edit', compact('kecamatan'));
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kecamatan,nama,' . $kecamatan->id,
        ]);

        $kecamatan->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.kecamatan.index')->with('success', 'Kecamatan berhasil diperbarui.');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        $kecamatan->delete();
        return redirect()->route('admin.kecamatan.index')->with('success', 'Kecamatan berhasil dihapus.');
    }
}
