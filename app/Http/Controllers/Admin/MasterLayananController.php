<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterLayanan;
use Illuminate\Http\Request;

class MasterLayananController extends Controller
{
    public function index()
    {
        $items = MasterLayanan::orderBy('name')->paginate(15);
        return view('admin.master_layanan.index', compact('items'));
    }

    public function create()
    {
        return view('admin.master_layanan.create');
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
        return redirect()->route('admin.master-layanan.index')->with('success', 'Layanan berhasil dibuat');
    }

    public function edit(MasterLayanan $master_layanan)
    {
        return view('admin.master_layanan.edit', ['item' => $master_layanan]);
    }

    public function update(Request $request, MasterLayanan $master_layanan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:master_layanan,name,' . $master_layanan->id,
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $master_layanan->update($data);
        return redirect()->route('admin.master-layanan.index')->with('success', 'Layanan berhasil diupdate');
    }

    public function destroy(MasterLayanan $master_layanan)
    {
        $master_layanan->delete();
        return redirect()->route('admin.master-layanan.index')->with('success', 'Layanan dihapus');
    }
}