<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterLayanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $items = MasterLayanan::withCount(['categories' => function($q){ $q->where('is_active', true); }])
            ->latest()
            ->paginate(15);
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
        MasterLayanan::create($data);
        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil dibuat');
    }

    public function edit(MasterLayanan $layanan)
    {
        return view('admin.layanan.edit', ['item' => $layanan]);
    }

    public function update(Request $request, MasterLayanan $layanan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:master_layanan,name,' . $layanan->id,
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $layanan->update($data);
        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil diupdate');
    }

    public function destroy(MasterLayanan $layanan)
    {
        $layanan->delete();
        return redirect()->route('admin.layanan.index')->with('success', 'Layanan dihapus');
    }
}
