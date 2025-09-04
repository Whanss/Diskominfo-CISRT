<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsCategoryController extends Controller
{
    public function index()
    {
        $categories = NewsCategory::orderBy('name')->paginate(5);
        return view('admin.news_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.news_categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:news_categories,name',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        NewsCategory::create($data);
        return redirect()->route('admin.news-categories.index')->with('success', 'Kategori dibuat');
    }

    public function edit(NewsCategory $news_category)
    {
        return view('admin.news_categories.edit', ['category' => $news_category]);
    }

    public function update(Request $request, NewsCategory $news_category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:news_categories,name,' . $news_category->id,
            'is_active' => 'sometimes|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $news_category->update($data);
        return redirect()->route('admin.news-categories.index')->with('success', 'Kategori diupdate');
    }

    public function destroy(NewsCategory $news_category)
    {
        $news_category->delete();
        return redirect()->route('admin.news-categories.index')->with('success', 'Kategori dihapus');
    }
}
