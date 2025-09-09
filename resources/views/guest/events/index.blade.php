@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 fw-bold">Agenda</h1>

    <div class="row gy-4">
        @forelse($events as $event)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-2">{{ $event->title }}</h5>
                        <div class="text-muted small mb-2">
                            <i class="bi bi-calendar-event me-1"></i>
                            {{ optional($event->start_at)->format('d M Y H:i') }}
                            @if($event->end_at)
                                - {{ optional($event->end_at)->format('d M Y H:i') }}
                            @endif
                        </div>
                        <div class="text-muted small mb-2">
                            <i class="bi bi-geo-alt me-1"></i> {{ $event->location }}
                        </div>
                        <p class="text-secondary flex-grow-1">{{ Str::limit($event->summary, 120) }}</p>
                        <a href="{{ route('guest.events.show', $event->slug) }}" class="btn btn-primary mt-auto">Detail</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">Belum ada agenda.</div>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $events->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection