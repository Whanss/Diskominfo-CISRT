<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $items = Layanan::orderBy('name')->paginate(15);
        return view('admin.layanan.index', compact('items'));
    }

    public function create()
    {
        return view('admin.layanan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:master_layanan,name',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        Layanan::create($data);
        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil dibuat');
    }

    public function edit(Layanan $master_layanan)
    {
        return view('admin.layanan.edit', ['item' => $master_layanan]);
    }

    public function update(Request $request, Layanan $master_layanan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:master_layanan,name,' . $master_layanan->id,
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $master_layanan->update($data);
        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil diupdate');
    }

    public function destroy(Layanan $master_layanan)
    {
        $master_layanan->delete();
        return redirect()->route('admin.layanan.index')->with('success', 'Layanan dihapus');
    }
}