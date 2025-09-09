@extends('layouts.app')

@section('content')
<div class="container py-5">
    <a href="{{ route('guest.events.index') }}" class="text-decoration-none">&larr; Kembali ke Agenda</a>

    <div class="row mt-3">
        <div class="col-lg-8">
            <h1 class="fw-bold">{{ $event->title }}</h1>
            <div class="text-muted mb-3">
                <i class="bi bi-calendar-event me-1"></i>
                {{ optional($event->start_at)->format('d M Y H:i') }}
                @if($event->end_at)
                    - {{ optional($event->end_at)->format('d M Y H:i') }}
                @endif
                <span class="ms-3"><i class="bi bi-geo-alt me-1"></i>{{ $event->location }}</span>
            </div>
            <p class="lead">{{ $event->summary }}</p>
            @if($event->description)
                <div class="mt-4">{!! nl2br(e($event->description)) !!}</div>
            @endif
        </div>
    </div>
</div>
@endsection