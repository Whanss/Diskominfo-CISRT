@extends('layouts.app')

@section('content')
    <style>
        :root {
            --csirt-primary: #1a365d;
            --csirt-secondary: #2d3748;
            --csirt-accent: #e53e3e;
            --csirt-warning: #d69e2e;
            --csirt-success: #38a169;
            --csirt-info: #3182ce;
            --csirt-dark: #1a202c;
            --csirt-light: #f7fafc;
        }

        body {
            background-color: #f8fafc;
        }

        /* Header/Search section (selaras dengan news) */
        .search-section {
            background: linear-gradient(135deg, var(--csirt-primary) 0%, var(--csirt-secondary) 100%);
            color: white;
            padding: 80px 0;
            position: relative;
        }

        .search-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.25;
        }

        .search-section .container {
            position: relative;
            z-index: 1;
        }

        .search-section form .row.g-3 {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .search-section .form-control,
        .search-section .form-select {
            background: rgba(255, 255, 255, 0.95);
            border-color: rgba(255, 255, 255, 0.65);
            color: #1f2937;
            height: 54px;
            border-radius: 10px;
        }

        .search-section .btn {
            height: 54px;
            border-radius: 10px;
            font-weight: 700;
            box-shadow: 0 8px 22px rgba(26, 54, 93, 0.25);
        }

        @media (max-width: 576px) {
            .search-section {
                padding: 56px 0;
            }

            .search-section .form-control {
                height: 48px;
            }

            .search-section .btn {
                height: 48px;
            }

            .search-section form .row.g-3 {
                padding: 12px;
                border-radius: 12px;
            }
        }

        /* Kartu agenda (selaras dengan news-card) */
        .event-card {
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            overflow: hidden;
        }

        .event-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 54, 93, 0.15);
            border-color: var(--csirt-primary);
        }

        .event-meta {
            font-size: 0.9rem;
            color: #64748b;
            font-weight: 500;
        }

        .event-card .card-title {
            color: var(--csirt-dark);
            font-weight: 600;
            line-height: 1.4;
        }

        /* Pagination (selaras dengan news) */
        .pagination-wrapper {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-top: 3rem;
            border: 1px solid #e2e8f0;
        }

        .pagination {
            justify-content: center;
            gap: .375rem;
            flex-wrap: wrap;
        }

        .pagination .page-link {
            color: var(--csirt-primary);
            background-color: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            font-weight: 600;
            min-width: 48px;
            min-height: 48px;
        }

        .pagination .page-link:hover {
            color: #fff;
            background-color: var(--csirt-primary);
            border-color: var(--csirt-primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(26, 54, 93, 0.3);
        }

        .pagination .page-item.active .page-link {
            color: #fff;
            background: linear-gradient(135deg, var(--csirt-primary) 0%, #2c5282 100%);
            border-color: var(--csirt-primary);
        }

        .btn-primary {
            background-color: var(--csirt-primary);
            border-color: var(--csirt-primary);
        }

        .btn-primary:hover {
            background-color: #2c5282;
            border-color: #2c5282;
        }
    </style>

    <!-- Header / Search (opsional pencarian judul agenda) -->
    <section class="search-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="fw-bold mb-2">Agenda</h1>
                    <p class="mb-0 text-white-75">Kegiatan dan event terbaru yang akan berlangsung.</p>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <form action="" method="GET">
                        <div class="row g-3">
                            <div class="col-8">
                                <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                                    placeholder="Cari agenda (judul, lokasi)...">
                            </div>
                            <div class="col-4 d-grid">
                                <button class="btn btn-light text-dark fw-bold" type="submit">
                                    <i class="bi bi-search me-1"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row gy-4">
            @forelse($events as $event)
                <div class="col-md-6 col-lg-4">
                    <div class="event-card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2">{{ $event->title }}</h5>
                            <div class="event-meta mb-2">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ optional($event->start_at)->format('d M Y H:i') }}
                                @if ($event->end_at)
                                    - {{ optional($event->end_at)->format('d M Y H:i') }}
                                @endif
                            </div>
                            @if ($event->location)
                                <div class="event-meta mb-2">
                                    <i class="bi bi-geo-alt me-1"></i> {{ $event->location }}
                                </div>
                            @endif
                            <p class="text-secondary flex-grow-1 mb-3">{{ Str::limit($event->summary, 120) }}</p>
                            <div class="mt-auto">
                                <a href="{{ route('guest.events.show', $event->slug) }}" class="btn btn-primary w-100">
                                    Detail Agenda
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x" style="font-size: 3rem; color: #cbd5e0;"></i>
                        <h4 class="mt-3 text-muted">Belum ada agenda</h4>
                        <p class="text-muted">Tunggu informasi agenda terbaru kami.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if (method_exists($events, 'links'))
            <div class="pagination-wrapper">
                <div class="d-flex justify-content-center">
                    {{ $events->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection
