@extends('layouts.app')

@section('content')
    <style>
        :root {
            --csirt-primary: #1a365d;
            --csirt-secondary: #2d3748;
            --csirt-dark: #1a202c;
        }

        .event-header {
            background: linear-gradient(135deg, var(--csirt-primary) 0%, var(--csirt-secondary) 100%);
            color: white;
            padding: 120px 0 80px;
            position: relative;
        }

        .event-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .event-header .container {
            position: relative;
            z-index: 1;
        }

        .btn-primary {
            background-color: var(--csirt-primary);
            border-color: var(--csirt-primary);
        }

        .btn-primary:hover {
            background-color: #2c5282;
            border-color: #2c5282;
        }

        .event-content {
            font-size: 1.05rem;
            line-height: 1.75;
            color: #374151;
        }

        .event-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 10px 24px rgba(26, 54, 93, .08);
        }
    </style>

    <!-- Header -->
    <section class="event-header">
        <div class="container">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white-75"
                            href="{{ route('guest.guest_dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-white-75" href="{{ route('guest.events.index') }}">Agenda</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($event->title, 50) }}</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3 text-white">{{ $event->title }}</h1>
                    <div class="d-flex align-items-center text-white-50 mb-3">
                        <i class="bi bi-calendar-event me-2"></i>
                        <span class="me-3">{{ optional($event->start_at)->format('d F Y H:i') }}@if ($event->end_at)
                                - {{ optional($event->end_at)->format('d F Y H:i') }}
                            @endif
                        </span>
                        @if ($event->location)
                            <i class="bi bi-geo-alt me-2"></i>
                            <span>{{ $event->location }}</span>
                        @endif
                    </div>
                    @if ($event->summary)
                        <p class="lead text-white-75">{{ $event->summary }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <article class="event-content">
                        @if ($event->description)
                            <div>{!! nl2br(e($event->description)) !!}</div>
                        @else
                            <p class="text-muted">Tidak ada deskripsi tambahan untuk agenda ini.</p>
                        @endif
                    </article>
                </div>

                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 100px;">
                        <div class="card event-card mb-4">
                            <div class="card-body">
                                <a href="{{ route('guest.events.index') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Agenda
                                </a>
                            </div>
                        </div>

                        <div class="card event-card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Detail Agenda</h5>
                                <div class="mb-2"><i class="bi bi-calendar-event me-2"></i><strong>Mulai:</strong>
                                    {{ optional($event->start_at)->format('d M Y H:i') }}</div>
                                @if ($event->end_at)
                                    <div class="mb-2"><i class="bi bi-calendar2-check me-2"></i><strong>Selesai:</strong>
                                        {{ optional($event->end_at)->format('d M Y H:i') }}</div>
                                @endif
                                @if ($event->location)
                                    <div class="mb-2"><i class="bi bi-geo-alt me-2"></i><strong>Lokasi:</strong>
                                        {{ $event->location }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
