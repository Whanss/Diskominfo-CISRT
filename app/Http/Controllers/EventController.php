<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // Admin: list
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    // Admin: create form
    public function create()
    {
        return view('admin.events.create');
    }

    // Admin: store
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string|max:500',
            'description' => 'nullable|string',
            'start_at' => 'required|date',
            'end_mode' => 'nullable|in:date,open',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'location' => 'required|string|max:255',
            'is_published' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // If open-ended, remove end_at
        if (($validated['end_mode'] ?? null) === 'open') {
            $validated['end_at'] = null;
        }
        unset($validated['end_mode']);

        $validated['is_published'] = $request->boolean('is_published');

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image'] = $path; // relative path from storage/app/public
        }

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan!');
    }

    // Admin: show
    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    // Admin: edit form
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    // Admin: update
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string|max:500',
            'description' => 'nullable|string',
            'start_at' => 'required|date',
            'end_mode' => 'nullable|in:date,open',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'location' => 'required|string|max:255',
            'is_published' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if (($validated['end_mode'] ?? null) === 'open') {
            $validated['end_at'] = null;
        }
        unset($validated['end_mode']);

        $validated['is_published'] = $request->boolean('is_published');

        // Handle image upload (replace old if new uploaded)
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image'] = $path;
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui!');
    }

    // Admin: delete
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus!');
    }

    // Guest: index (3 per page) + search by q (title, location, summary)
    public function guestIndex(Request $request)
    {
        $query = Event::published();

        if ($request->filled('q')) {
            $term = '%' . $request->get('q') . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('location', 'like', $term)
                    ->orWhere('summary', 'like', $term);
            });
        }

        $events = $query->orderBy('start_at')
            ->paginate(3, ['*'], 'events_page')
            ->withQueryString();

        return view('guest.events.index', compact('events'));
    }

    // Guest: show
    public function guestShow(string $slug)
    {
        $event = Event::published()->where('slug', $slug)->firstOrFail();
        return view('guest.events.show', compact('event'));
    }
}
