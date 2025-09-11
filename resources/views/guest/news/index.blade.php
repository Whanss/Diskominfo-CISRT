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
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
        }

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

        .category-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 2;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .search-section {
            background: linear-gradient(135deg, var(--csirt-primary) 0%, var(--csirt-secondary) 100%);
            color: white;
            padding: 80px 0;
            position: relative;
        }

        .search-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .search-section .container {
            position: relative;
            z-index: 1;
        }

        /* Enhanced search form styling */
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

        .search-section .form-control::placeholder {
            color: #6b7280;
        }

        .search-section .form-control:focus,
        .search-section .form-select:focus {
            border-color: var(--csirt-primary);
            box-shadow: 0 0 0 3px rgba(26, 54, 93, 0.18);
            outline: none;
        }

        /* Custom select arrow */
        .search-section .form-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23263b5e' viewBox='0 0 16 16'%3E%3Cpath d='M3.204 5.204a.5.5 0 0 1 .707 0L8 9.293l4.089-4.089a.5.5 0 1 1 .707.707l-4.442 4.442a.75.75 0 0 1-1.06 0L3.204 5.911a.5.5 0 0 1 0-.707z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 36px;
        }

        /* Search button */
        .search-section .btn {
            height: 54px;
            border-radius: 10px;
            font-weight: 700;
            box-shadow: 0 8px 22px rgba(26, 54, 93, 0.25);
        }

        .search-section .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 26px rgba(26, 54, 93, 0.28);
        }

        /* Responsive tweaks */
        @media (max-width: 576px) {
            .search-section {
                padding: 56px 0;
            }

            .search-section .form-control,
            .search-section .form-select {
                height: 48px;
                font-size: 0.95rem;
            }

            .search-section .btn {
                height: 48px;
            }

            .search-section form .row.g-3 {
                padding: 12px;
                border-radius: 12px;
            }
        }

        .news-meta {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
        }

        .news-card .card-title {
            color: var(--csirt-dark);
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 0.75rem;
        }

        .news-card .card-text {
            color: #64748b;
            line-height: 1.6;
        }

        .btn-primary {
            background-color: var(--csirt-primary);
            border-color: var(--csirt-primary);
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #2c5282;
            border-color: #2c5282;
            transform: translateY(-1px);
        }

        .alert-info {
            background-color: #ebf8ff;
            border-color: #bee3f8;
            color: var(--csirt-info);
            border-radius: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 6px;
            border: 1px solid #d1d5db;
            padding: 10px 12px;
            transition: border-color 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--csirt-primary);
            box-shadow: 0 0 0 3px rgba(26, 54, 93, 0.1);
        }

        .bg-danger {
            background-color: var(--csirt-accent) !important;
        }

        .bg-warning {
            background-color: var(--csirt-warning) !important;
        }

        .bg-success {
            background-color: var(--csirt-success) !important;
        }

        .bg-info {
            background-color: var(--csirt-info) !important;
        }

        .bg-primary {
            background-color: var(--csirt-primary) !important;
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e0;
            margin-bottom: 1rem;
        }

        /* ENHANCED PAGINATION STYLES */
        .pagination-wrapper {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-top: 3rem;
            border: 1px solid #e2e8f0;
        }

        .pagination-info {
            text-align: center;
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            padding: 10px 20px;
            background-color: #f8fafc;
            border-radius: 8px;
            display: inline-block;
        }

        .pagination {
            margin: 0;
            justify-content: center;
            gap: 0.375rem;
            flex-wrap: wrap;
        }

        .pagination .page-item {
            margin: 0;
        }

        .pagination .page-link {
            color: var(--csirt-primary);
            background-color: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 48px;
            min-height: 48px;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
        }

        .pagination .page-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .pagination .page-link:hover::before {
            left: 100%;
        }

        .pagination .page-link:hover {
            color: white;
            background-color: var(--csirt-primary);
            border-color: var(--csirt-primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(26, 54, 93, 0.3);
        }

        .pagination .page-item.active .page-link {
            color: white;
            background: linear-gradient(135deg, var(--csirt-primary) 0%, #2c5282 100%);
            border-color: var(--csirt-primary);
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(26, 54, 93, 0.4);
            transform: translateY(-1px);
        }

        .pagination .page-item.active .page-link:hover {
            background: linear-gradient(135deg, #2c5282 0%, var(--csirt-primary) 100%);
            border-color: #2c5282;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 54, 93, 0.5);
        }

        .pagination .page-item.disabled .page-link {
            color: #cbd5e0;
            background-color: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
            transform: none;
            opacity: 0.6;
        }

        .pagination .page-item.disabled .page-link:hover {
            color: #cbd5e0;
            background-color: #f9fafb;
            border-color: #e5e7eb;
            transform: none;
            box-shadow: none;
        }

        .pagination .page-link[aria-label*="Previous"],
        .pagination .page-link[aria-label*="Next"] {
            font-size: 1rem;
            font-weight: 600;
            padding: 10px 12px;
            min-width: 44px;
        }

        .pagination .page-link[aria-label*="Previous"]::after,
        .pagination .page-link[aria-label*="Next"]::after {
            content: none;
        }

        .pagination .page-link[aria-label*="Previous"] span,
        .pagination .page-link[aria-label*="Next"] span {
            display: inline;
        }

        .pagination .page-item .page-link[aria-disabled="true"] {
            background-color: transparent;
            border-color: transparent;
            color: #9ca3af;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .pagination .page-item .page-link[aria-disabled="true"]:hover {
            background-color: transparent;
            border-color: transparent;
            color: #9ca3af;
            transform: none;
            box-shadow: none;
        }

        @media (max-width: 768px) {
            .pagination-wrapper {
                padding: 20px 15px;
                margin-top: 2rem;
            }

            .pagination {
                gap: 0.25rem;
            }

            .pagination .page-link {
                padding: 10px 12px;
                min-width: 42px;
                min-height: 42px;
                font-size: 0.9rem;
            }

            .pagination .page-link[aria-label*="Previous"],
            .pagination .page-link[aria-label*="Next"] {
                min-width: 46px;
                padding: 10px 12px;
            }

            .pagination-info {
                font-size: 0.85rem;
                padding: 8px 16px;
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 576px) {
            .pagination {
                gap: 0.125rem;
            }

            .pagination .page-link {
                padding: 8px 10px;
                min-width: 38px;
                min-height: 38px;
                font-size: 0.85rem;
            }

            .pagination .page-item:not(.active):not(:first-child):not(:last-child):not(.disabled) {
                display: none;
            }

            .pagination .page-item.active,
            .pagination .page-item:first-child,
            .pagination .page-item:last-child {
                display: block;
            }
        }

        .pagination-loading {
            text-align: center;
            padding: 20px;
            color: #64748b;
        }

        .pagination-loading::after {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #e2e8f0;
            border-top: 2px solid var(--csirt-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .pagination .page-link:focus {
            outline: 3px solid rgba(26, 54, 93, 0.3);
            outline-offset: 2px;
            box-shadow: 0 0 0 3px rgba(26, 54, 93, 0.1);
        }
    </style>

    <!-- Search Section -->
    <section class="search-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-4">
                        <h1 class="fw-bold mb-3 text-white">Pusat Informasi Keamanan</h1>
                        <p class="lead mb-0 text-white">Intelijen Ancaman, Pemberitahuan Keamanan & Pembaruan Tanggap
                            Insiden</p>
                        <p class="text-white-50">CSIRT Lombok Tengah - Melindungi Infrastruktur Digital</p>
                    </div>

                    <form method="GET" action="{{ route('guest.news.index') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control form-control-lg" name="search"
                                    value="{{ request('search') }}" placeholder="Cari berita...">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select form-select-lg" name="category">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ (string) request('category') === (string) $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-light btn-lg w-100">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Events/Agenda Section (Top of News) -->
    <section class="py-5" style="padding-bottom: 0 !important;">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="fw-bold mb-0">Event Terbaru</h2>
                <a href="{{ route('guest.events.index') }}" class="btn btn-outline-primary btn-sm">Lihat event
                    selengkapnya</a>
            </div>

            @if (isset($eventsTop) && $eventsTop->count())
                <div class="row gy-4">
                    @foreach ($eventsTop as $event)
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
                                    @if (!empty($event->location))
                                        <div class="news-meta mb-1">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            {{ $event->location }}
                                        </div>
                                    @endif
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
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x" style="font-size: 3rem; color: #cbd5e0;"></i>
                    <h5 class="mt-3 text-muted">Belum ada event terbaru</h5>
                    <p class="text-muted">Pantau halaman agenda untuk informasi selanjutnya.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- News Section -->
    <section class="py-5" style="padding-bottom: 0 !important;">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="fw-bold mb-0">Berita</h2>

            </div>
            @if (request('search') || request('category'))
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Menampilkan hasil untuk:
                            @if (request('search'))
                                <strong>"{{ request('search') }}"</strong>
                            @endif
                            @if (request('category'))
                                @php $selectedCat = $categories->firstWhere('id', request('category')); @endphp
                                <strong>Kategori: {{ $selectedCat->name ?? 'Tidak diketahui' }}</strong>
                            @endif
                            <a href="{{ route('guest.news.index') }}" class="btn btn-sm btn-outline-primary ms-2">Reset
                                Filter</a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row gy-4">
                @forelse($news as $item)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="card news-card border-0 shadow-sm">
                            <div class="position-relative">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top news-image"
                                        alt="{{ $item->title }}">
                                @else
                                    <img src="{{ asset('template/Dashboard/assets/img/portfolio/app-1.jpg') }}"
                                        class="card-img-top news-image" alt="{{ $item->title }}">
                                @endif
                                <span
                                    class="badge category-badge bg-{{ $item->category_badge_class }}">{{ $item->category_label }}</span>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $item->title }}</h5>
                                <p class="card-text text-muted flex-grow-1">{{ $item->excerpt }}</p>
                                <div class="news-meta mb-3">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $item->created_at->format('d M Y') }}
                                </div>
                                <a href="{{ route('guest.news.show', $item->slug) }}" class="btn btn-primary">
                                    Lihat Berita <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-shield-exclamation"></i>
                            <h3 class="mt-3 text-muted fw-semibold">Tidak Ada Informasi Keamanan Tersedia</h3>
                            <p class="text-muted">
                                @if (request('search') || request('category'))
                                    Tidak ada pemberitahuan keamanan yang sesuai dengan kriteria pencarian Anda.
                                @else
                                    Pembaruan informasi keamanan akan diterbitkan di sini saat tersedia.
                                @endif
                            </p>
                            @if (request('search') || request('category'))
                                <style>
                                    .btn-smaller {
                                        padding: 6px 12px !important;
                                        font-size: 0.85rem !important;
                                        border-radius: 6px !important;
                                    }
                                </style>
                                <a href="{{ route('guest.news.index') }}" class="btn btn-primary btn-smaller">
                                    <i class="bi bi-arrow-left me-2"></i>Lihat Semua Informasi
                                </a>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($news->hasPages())
                <div class="row">
                    <div class="col-12">
                        <div class="pagination-wrapper">
                            <div class="text-center mb-3">
                                <span class="pagination-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Menampilkan {{ $news->firstItem() ?? 0 }} hingga {{ $news->lastItem() ?? 0 }} dari
                                    {{ $news->total() }} hasil
                                </span>
                            </div>
                            <div class="d-flex justify-content-center pagination-simple">
                                {{ $news->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
                            </div>
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    Page {{ $news->currentPage() }} of {{ $news->lastPage() }}
                                    @if ($news->hasMorePages())
                                        | <a href="{{ $news->appends(request()->query())->nextPageUrl() }}"
                                            class="text-primary text-decoration-none">Halaman Berikutnya →</a>
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
