<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function show($slug)
    {
        $news = News::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Get related news (same category, exclude current)
        $relatedNews = News::where('category', $news->category)
            ->where('id', '!=', $news->id)
            ->where('is_published', true)
            ->latest()
            ->take(3)
            ->get();

        return view('guest.news.show', compact('news', 'relatedNews'));
    }

    public function index(Request $request)
    {
        $query = News::published()->latest();

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $news = $query->paginate(9);

        return view('guest.news.index', compact('news'));
    }
}
