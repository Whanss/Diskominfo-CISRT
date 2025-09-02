<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Berita Terkini - CSIRT LOMBOK TENGAH</title>
    <meta name="description" content="Berita terkini seputar keamanan siber dari CSIRT Lombok Tengah">
    <meta name="keywords" content="berita, keamanan siber, CSIRT, Lombok Tengah">

    <!-- Favicons -->
    <link href="{{ asset('template/Dashboard/assets/img/favicon.png') }}" rel="icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('template/Dashboard/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/Dashboard/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('template/Dashboard/assets/vendor/aos/aos.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('template/Dashboard/assets/css/main.css') }}" rel="stylesheet">

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

        .pagination .page-link {
            color: var(--csirt-primary);
            border-color: #e2e8f0;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--csirt-primary);
            border-color: var(--csirt-primary);
        }
    </style>
</head>

<body>
    <br>
    <br>
    <br>
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">
            <a href="{{ route('guest.guest_dashboard') }}" class="logo d-flex align-items-center me-auto">
                <img src="{{ asset('template/Dashboard/assets/img/logo.png') }}" alt="">
                <h1 class="sitename"></h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="{{ route('guest.guest_dashboard') }}">Home</a></li>
                    <li><a href="{{ route('guest.news.index') }}" class="active">Berita</a></li>
                    <li><a href="{{ route('guest.create_tiket') }}">Kirim Aduan</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main">
        <!-- Search Section -->
        <section class="search-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-4">
                            <h1 class="fw-bold mb-3 text-white">Security Intelligence Center</h1>
                            <p class="lead mb-0 text-white">Threat Intelligence, Security Advisories & Incident Response
                                Updates</p>
                            <p class="text-white-50">CSIRT Lombok Tengah - Protecting Digital Infrastructure</p>
                        </div>

                        <form method="GET" action="{{ route('guest.news.index') }}">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-lg" name="search"
                                        value="{{ request('search') }}" placeholder="Cari berita...">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select form-select-lg" name="category">
                                        <option value="">All Categories</option>
                                        <option value="alert" {{ request('category') == 'alert' ? 'selected' : '' }}>
                                            Security Alerts</option>
                                        <option value="tips" {{ request('category') == 'tips' ? 'selected' : '' }}>
                                            Security Guidelines</option>
                                        <option value="news" {{ request('category') == 'news' ? 'selected' : '' }}>
                                            Threat Intelligence</option>
                                        <option value="update" {{ request('category') == 'update' ? 'selected' : '' }}>
                                            System Updates</option>
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

        <!-- News Section -->
        <section class="py-5">
            <div class="container">
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
                                    <strong>Kategori: {{ ucfirst(request('category')) }}</strong>
                                @endif
                                <a href="{{ route('guest.news.index') }}"
                                    class="btn btn-sm btn-outline-primary ms-2">Reset Filter</a>
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
                                        <img src="{{ asset('storage/' . $item->image) }}"
                                            class="card-img-top news-image" alt="{{ $item->title }}">
                                    @else
                                        <img src="{{ asset('template/Dashboard/assets/img/portfolio/app-1.jpg') }}"
                                            class="card-img-top news-image" alt="{{ $item->title }}">
                                    @endif

                                    <span
                                        class="badge category-badge bg-{{ $item->category == 'alert' ? 'danger' : ($item->category == 'tips' ? 'info' : ($item->category == 'update' ? 'warning' : 'primary')) }}">
                                        {{ $item->category_label }}
                                    </span>
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
                                <h3 class="mt-3 text-muted fw-semibold">No Security Intelligence Available</h3>
                                <p class="text-muted">
                                    @if (request('search') || request('category'))
                                        No security advisories match your current search criteria.
                                    @else
                                        Security intelligence updates will be published here as they become available.
                                    @endif
                                </p>
                                @if (request('search') || request('category'))
                                    <a href="{{ route('guest.news.index') }}" class="btn btn-primary">
                                        <i class="bi bi-arrow-left me-2"></i>View All Intelligence
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($news->hasPages())
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                {{ $news->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer id="footer" class="footer">
        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">CSIRT Lombok Tengah</strong> <span>All Rights
                    Reserved</span></p>
        </div>
    </footer>

    <!-- Vendor JS Files -->
    <script src="{{ asset('template/Dashboard/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/Dashboard/assets/vendor/aos/aos.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('template/Dashboard/assets/js/main.js') }}"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 600,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
    </script>
</body>

</html>
