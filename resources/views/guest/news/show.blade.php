@extends('layouts.app')

@section('content')
    <style>
        :root {
            --csirt-primary: #1b212a;
            --csirt-secondary: #2d3748;
            --csirt-accent: #e53e3e;
            --csirt-warning: #d69e2e;
            --csirt-success: #38a169;
            --csirt-info: #3182ce;
            --csirt-dark: #1a202c;
            --csirt-light: #f7fafc;
        }

        .news-header {
            background: linear-gradient(135deg, var(--csirt-primary) 0%, var(--csirt-secondary) 100%);
            color: white;
            padding: 120px 0 80px;
            position: relative;
        }

        .news-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .news-header .container {
            position: relative;
            z-index: 1;
        }

        .news-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #374151;
        }

        .news-content p {
            margin-bottom: 1.5rem;
        }

        .news-image {
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(26, 54, 93, 0.15);
            border: 1px solid #e2e8f0;
            max-width: 700px;
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .related-news-card {
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .related-news-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 54, 93, 0.15);
            border-color: var(--csirt-primary);
        }

        .related-news-image {
            height: 120px;
            object-fit: cover;
            width: 100%;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.6);
        }

        .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .breadcrumb-item a:hover {
            color: white;
        }

        .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.9);
        }

        .share-buttons .btn {
            margin-right: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--csirt-primary);
            border-color: var(--csirt-primary);
        }

        .btn-primary:hover {
            background-color: #2c5282;
            border-color: #2c5282;
        }

        .btn-outline-primary {
            color: var(--csirt-primary);
            border-color: var(--csirt-primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--csirt-primary);
            border-color: var(--csirt-primary);
        }
    </style>

    <!-- News Header -->
    <section class="news-header">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guest.guest_dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guest.news.index') }}">Berita</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($news->title, 50) }}</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-lg-8">
                    <span class="badge bg-{{ $news->category_badge_class }} mb-3">{{ $news->category_label }}</span>
                    <h1 class="display-5 fw-bold mb-3 text-white">{{ $news->title }}</h1>
                    <div class="d-flex align-items-center text-white-50 mb-4">
                        <i class="bi bi-calendar3 me-2"></i>
                        <span class="me-4">{{ $news->created_at->format('d F Y') }}</span>
                        <i class="bi bi-clock me-2"></i>
                        <span>{{ $news->created_at->format('H:i') }} WITA</span>
                    </div>
                    @if ($news->excerpt)
                        <p class="lead text-white-75">{{ $news->excerpt }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- News Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <article class="news-content">
                        @if ($news->image)
                            <div class="mb-5">
                                <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}"
                                    class="img-fluid news-image w-100">
                            </div>
                        @endif

                        <div class="content">{!! nl2br(e($news->content)) !!}</div>

                        <!-- Share Buttons -->
                        <div class="mt-5 pt-4 border-top">
                            <h5 class="mb-3">Bagikan Berita Ini:</h5>
                            <div class="share-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                    target="_blank" class="btn btn-primary">
                                    <i class="bi bi-facebook me-1"></i> Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($news->title) }}"
                                    target="_blank" class="btn btn-info">
                                    <i class="bi bi-twitter me-1"></i> Twitter
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($news->title . ' - ' . request()->fullUrl()) }}"
                                    target="_blank" class="btn btn-success">
                                    <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                </a>
                                <button class="btn btn-secondary" onclick="copyToClipboard(event)">
                                    <i class="bi bi-link-45deg me-1"></i> Salin Tautan
                                </button>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 100px;">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <a href="{{ route('guest.news.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-left me-1"></i> kembali ke Berita
                                </a>
                            </div>
                        </div>

                        @if ($relatedNews->count() > 0)
                            <div class="card">
                                <div class="card-body">
                                    @foreach ($relatedNews as $related)
                                        <div class="card related-news-card border-0 shadow-sm mb-3">
                                            @if ($related->image)
                                                <img src="{{ asset('storage/' . $related->image) }}"
                                                    class="card-img-top related-news-image" alt="{{ $related->title }}">
                                            @else
                                                <img src="{{ asset('template/Dashboard/assets/img/portfolio/app-1.jpg') }}"
                                                    class="card-img-top related-news-image" alt="{{ $related->title }}">
                                            @endif

                                            <div class="card-body p-3">
                                                <span
                                                    class="badge bg-{{ $related->category_badge_class }} mb-2">{{ $related->category_label }}</span>
                                                <h6 class="card-title">
                                                    <a href="{{ route('guest.news.show', $related->slug) }}"
                                                        class="text-decoration-none text-dark">
                                                        {{ Str::limit($related->title, 60) }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ $related->created_at->format('d M Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function copyToClipboard(e) {
            e.preventDefault();
            navigator.clipboard.writeText(window.location.href).then(function() {
                const btn = e.target.closest('button');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check me-1"></i> Copied!';
                btn.classList.remove('btn-secondary');
                btn.classList.add('btn-success');
                setTimeout(function() {
                    btn.innerHTML = originalText;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-secondary');
                }, 2000);
            });
        }
    </script>
@endsection
