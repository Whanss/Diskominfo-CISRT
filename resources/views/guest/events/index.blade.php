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

        /* Kartu dan gambar sama seperti news */
        .news-card {
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            overflow: hidden;
        }

        .news-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 54, 93, 0.15);
            border-color: var(--csirt-primary);
        }

        .news-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .event-meta {
            font-size: 0.9rem;
            color: #64748b;
            font-weight: 500;
        }

        .news-card .card-title {
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
                    <h1 class="fw-bold mb-2 text-white">Agenda</h1>
                    <p class="mb-0 text-white-75">Kegiatan dan event terbaru yang akan berlangsung.</p>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <form action="{{ route('guest.events.index') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-12 col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                                    <input type="text" name="q" value="{{ request('q') }}"
                                        class="form-control border-start-0"
                                        placeholder="Cari agenda (judul, lokasi, ringkasan)...">
                                    @if (request('q'))
                                        <a href="{{ route('guest.events.index') }}"
                                            class="btn btn-outline-secondary">Reset</a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-md-3 d-grid">
                                <button class="btn btn-light text-dark fw-bold" type="submit">
                                    Cari
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
                <div class="col-lg-4 col-md-6">
                    <div class="card news-card border-0 shadow-sm">
                        <div class="position-relative">
                            @if ($event->image)
                                <img src="{{ asset($event->image) }}" class="card-img-top news-image"
                                    alt="{{ $event->title }}">
                            @else
                                <img src="{{ asset('template/Dashboard/assets/img/portfolio/app-1.jpg') }}"
                                    class="card-img-top news-image" alt="{{ $event->title }}">
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $event->title }}</h5>
                            <p class="card-text text-muted flex-grow-1">{{ Str::limit($event->summary, 140) }}</p>
                            <div class="news-meta mb-1">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ optional($event->start_at)->format('d M Y') }}
                            </div>
                            <div class="news-meta mb-3">
                                <i class="bi bi-clock me-1"></i>
                                Mulai: {{ optional($event->start_at)->format('H:i') }}
                                @if ($event->end_at)
                                    - Selesai: {{ optional($event->end_at)->format('H:i') }}
                                @else
                                    - Sampai selesai
                                @endif
                            </div>
                            <a href="{{ route('guest.events.show', $event->slug) }}" class="btn btn-primary">
                                Detail Agenda <i class="bi bi-arrow-right ms-1"></i>
                            </a>
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
                <div class="text-center mb-3">
                    <span class="pagination-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Menampilkan {{ $events->firstItem() ?? 0 }} hingga {{ $events->lastItem() ?? 0 }} dari
                        {{ $events->total() }} hasil
                    </span>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $events->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection
