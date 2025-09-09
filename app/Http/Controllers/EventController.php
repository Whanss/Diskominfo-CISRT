<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // Admin: list
    public function index()
    {
        $events = Event::latest('start_at')->paginate(10);
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
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'location' => 'required|string|max:255',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

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
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'location' => 'required|string|max:255',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui!');
    }

    // Admin: delete
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus!');
    }

    // Guest: index (3 per page)
    public function guestIndex(Request $request)
    {
        $events = Event::published()->orderBy('start_at')->paginate(3, ['*'], 'events_page')->withQueryString();
        return view('guest.events.index', compact('events'));
    }

    // Guest: show
    public function guestShow(string $slug)
    {
        $event = Event::published()->where('slug', $slug)->firstOrFail();
        return view('guest.events.show', compact('event'));
    }
}
