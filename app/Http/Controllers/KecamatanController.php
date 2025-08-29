<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kabupaten;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::with('kabupaten')->orderBy('nama')->get();
        return view('admin.kecamatan.index', compact('kecamatans'));
    }

    public function create()
    {
        $kabupatens = Kabupaten::orderBy('nama')->get();
        return view('admin.kecamatan.create', compact('kabupatens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kecamatan,nama',
            'kabupaten_id' => 'required|exists:kabupaten,id',
        ]);

        Kecamatan::create([
            'nama' => $request->nama,
            'kabupaten_id' => $request->kabupaten_id,
        ]);

        return redirect()->route('admin.kecamatan.index')->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    public function edit(Kecamatan $kecamatan)
    {
        $kabupatens = Kabupaten::orderBy('nama')->get();
        return view('admin.kecamatan.edit', compact('kecamatan', 'kabupatens'));
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kecamatan,nama,' . $kecamatan->id,
            'kabupaten_id' => 'required|exists:kabupaten,id',
        ]);

        $kecamatan->update([
            'nama' => $request->nama,
            'kabupaten_id' => $request->kabupaten_id,
        ]);

        return redirect()->route('admin.kecamatan.index')->with('success', 'Kecamatan berhasil diperbarui.');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        $kecamatan->delete();
        return redirect()->route('admin.kecamatan.index')->with('success', 'Kecamatan berhasil dihapus.');
    }
}
