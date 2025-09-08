<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LayananCategory;
use App\Models\MasterLayanan;
use Illuminate\Http\Request;

class LayananCategoryController extends Controller
{
    public function index()
    {
        $query = LayananCategory::with('layanan')->orderBy('created_at', 'desc');
        if (request('layanan_id')) {
            $query->where('layanan_id', request('layanan_id'));
        }
        $categories = $query->paginate(15)->withQueryString();
        $layananList = MasterLayanan::where('is_active', true)->orderBy('name')->get(['id', 'name']);
        return view('admin.layanan_categories.index', compact('categories', 'layananList'));
    }

    public function create()
    {
        $layananList = MasterLayanan::where('is_active', true)->orderBy('name')->get(['id', 'name']);
        return view('admin.layanan_categories.create', compact('layananList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'layanan_id' => 'required|exists:master_layanan,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|in:1,on,true,0,off,false',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        LayananCategory::create($validated);
        return redirect()->route('admin.layanan-categories.index')->with('success', 'Kategori layanan berhasil dibuat');
    }

    public function edit(LayananCategory $layanan_category)
    {
        $layananList = MasterLayanan::where('is_active', true)->orderBy('name')->get(['id', 'name']);
        return view('admin.layanan_categories.edit', ['category' => $layanan_category, 'layananList' => $layananList]);
    }

    public function update(Request $request, LayananCategory $layanan_category)
    {
        $validated = $request->validate([
            'layanan_id' => 'required|exists:master_layanan,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|in:1,on,true,0,off,false',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $layanan_category->update($validated);
        return redirect()->route('admin.layanan-categories.index')->with('success', 'Kategori layanan berhasil diperbarui');
    }

    public function destroy(LayananCategory $layanan_category)
    {
        $layanan_category->delete();
        return redirect()->route('admin.layanan-categories.index')->with('success', 'Kategori layanan dihapus');
    }
}
