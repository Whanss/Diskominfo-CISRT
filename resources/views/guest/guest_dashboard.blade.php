<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>CSIRT - LOMBOK TENGAH</title>
    <meta name="description" content="">
    <meta name="keywords" content="">




    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('template/Dashboard/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/Dashboard/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('template/Dashboard/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('template/Dashboard/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/Dashboard/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('template/Dashboard/assets/css/main.css') }}" rel="stylesheet">

    <style>
        :root {
            --font-sans: 'Poppins', 'Nunito', 'Roboto', system-ui, -apple-system, Segoe UI, Arial, sans-serif;
        }

        body,
        .navmenu a,
        .mobile-nav a {
            font-family: var(--font-sans);
            letter-spacing: .2px;
        }
    </style>

    <!-- Custom Network Animation CSS (Enhanced, white background) -->
    <style>
        /* Base & Theme */
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
            background-color: #ffffff;
            /* Tetap putih */
        }

        /* Network Animation Background */
        .network-bg {
            position: fixed;
            inset: 0;
            z-index: -1;
            background: #ffffff;
            /* Putih bersih */
            overflow: hidden;
        }

        /* Grid halus + glow lembut agar tidak mengganggu konten */
        .network-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(26,54,93,0.04)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>') repeat,
                radial-gradient(circle at 20% 20%, rgba(49, 130, 206, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(56, 161, 105, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 40% 60%, rgba(139, 92, 246, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .network-node {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--csirt-primary);
            border-radius: 50%;
            opacity: 0.6;
            animation: pulse 2.2s infinite ease-in-out;
            box-shadow: 0 0 0 1px rgba(26, 54, 93, 0.1);
        }

        .network-line {
            position: absolute;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(26, 54, 93, 0.45), transparent);
            opacity: 0.25;
            animation: flow 4s infinite linear;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.6;
            }

            50% {
                transform: scale(1.6);
                opacity: 1;
            }
        }

        @keyframes flow {
            0% {
                transform: translateX(-120%);
            }

            100% {
                transform: translateX(120vw);
            }
        }

        /* Konten di atas background animasi */
        .main {
            position: relative;
            z-index: 1;
        }

        /* Layout polish */
        .section {
            scroll-margin-top: 80px;
        }

        /* Hero CTA lebih tegas */
        .btn-get-started {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: linear-gradient(135deg, var(--csirt-primary), #2c5282);
            color: #fff;
            border: none;
            padding: 12px 18px;
            border-radius: 10px;
            font-weight: 700;
            letter-spacing: .3px;
            box-shadow: 0 10px 24px rgba(26, 54, 93, 0.25);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .btn-get-started:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(26, 54, 93, 0.32);
        }

        /* Service item: kartu interaktif */
        .service-item {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 6px 20px rgba(26, 54, 93, 0.08);
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
            position: relative;
            overflow: hidden;
        }

        .service-item::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(26, 54, 93, 0.06), rgba(49, 130, 206, 0.04));
            opacity: 0;
            transition: opacity .25s ease;
        }

        .service-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(26, 54, 93, 0.18);
            border-color: rgba(26, 54, 93, 0.35);
        }

        .service-item:hover::after {
            opacity: 1;
        }

        .service-item h3 {
            font-weight: 700;
            color: var(--csirt-dark);
            margin-top: 12px;
        }

        .service-item p {
            color: #64748b;
        }

        /* Stats: ringkas dan kuat */
        .stats .stats-item {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 18px 16px;
            box-shadow: 0 6px 18px rgba(26, 54, 93, 0.08);
        }

        .stats .stats-item i {
            font-size: 28px;
            color: var(--csirt-primary);
        }

        .stats .stats-item span {
            color: var(--csirt-primary);
            font-weight: 800;
        }

        /* Feature box */
        .feature-box {
            gap: 10px;
            padding: 14px 12px;
            border: 1px dashed #d1d5db;
            border-radius: 12px;
            transition: background-color .2s ease, transform .2s ease, border-color .2s ease;
            background: #fff;
        }

        .feature-box i {
            color: var(--csirt-primary);
        }

        .feature-box:hover {
            background: #f8fafc;
            transform: translateY(-2px);
            border-color: rgba(26, 54, 93, 0.4);
        }

        /* Recent posts / intel siber */
        .recent-posts .post-item {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(26, 54, 93, 0.08);
            border: 1px solid #e2e8f0;
            margin-bottom: 1.25rem;
            transition: all 0.25s ease;
            overflow: hidden;
        }

        .recent-posts .post-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(26, 54, 93, 0.15);
            border-color: rgba(26, 54, 93, 0.35);
        }

        .recent-posts .post-img {
            width: 120px;
            height: 120px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .recent-posts .post-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .recent-posts .post-content {
            padding: 1.25rem;
            flex: 1;
        }

        .recent-posts .post-meta {
            margin-bottom: .6rem;
        }

        .recent-posts .post-meta .badge {
            font-size: .7rem;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-right: .5rem;
        }

        .recent-posts .post-date {
            color: #64748b;
            font-size: .85rem;
            font-weight: 600;
        }

        .recent-posts h3 {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: .45rem;
            line-height: 1.35;
        }

        .recent-posts h3 a {
            color: var(--csirt-dark);
            text-decoration: none;
            transition: color .18s ease;
        }

        .recent-posts h3 a:hover {
            color: var(--csirt-primary);
        }

        .recent-posts p {
            color: #64748b;
            font-size: .92rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        /* Widget */
        .widget {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 6px 18px rgba(26, 54, 93, 0.08);
            border: 1px solid #e2e8f0;
        }

        .widget h4 {
            color: var(--csirt-dark);
            font-weight: 700;
            margin-bottom: .9rem;
            font-size: 1.08rem;
        }

        .threat-level-widget .threat-indicator {
            text-align: center;
            padding: 1rem;
            border-radius: 10px;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #fff;
            margin-bottom: .5rem;
        }

        .threat-level-widget .level-text {
            display: block;
            font-weight: 800;
            font-size: 1.12rem;
            letter-spacing: .6px;
        }

        .threat-level-widget .level-description {
            display: block;
            font-size: .85rem;
            opacity: .9;
            margin-top: .25rem;
        }

        /* Color overrides */
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

        /* Aksesibilitas: hormati preferensi reduced motion */
        @media (prefers-reduced-motion: reduce) {

            .network-node,
            .network-line {
                animation: none !important;
            }

            .service-item,
            .feature-box,
            .recent-posts .post-item,
            .btn-get-started {
                transition: none !important;
            }
        }

        /* Prevent scroll when sidebar open */
        body.no-scroll {
            overflow: hidden;
        }

        /* Mobile Sidebar */
        .mobile-sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            opacity: 0;
            visibility: hidden;
            transition: opacity .25s ease, visibility .25s ease;
            z-index: 9998;
        }

        .mobile-sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 300px;
            max-width: 85vw;
            background: #fff;
            box-shadow: 2px 0 12px rgba(0, 0, 0, .15);
            transform: translateX(-100%);
            transition: transform .25s ease;
            z-index: 9999;
            padding: 18px 16px;
            overflow-y: auto;
        }

        .mobile-sidebar.open {
            transform: translateX(0);
        }

        .sidebar-close {
            font-size: 28px;
            cursor: pointer;
            display: block;
            margin-left: auto;
        }

        .sidebar-logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .sidebar-logo img {
            max-height: 48px;
        }

        .mobile-nav {
            list-style: none;
            margin: 12px 0 0 0;
            padding: 0;
        }

        .mobile-nav li {
            border-bottom: 1px solid #eee;
        }

        .mobile-nav a {
            display: block;
            padding: 12px 6px;
            color: #374151;
            text-decoration: none;
            font-weight: 600;
        }

        .mobile-nav a.active,
        .mobile-nav a:hover {
            color: var(--csirt-primary, #1a365d);
        }

        .mobile-dropdown .mobile-dropdown-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mobile-dropdown .chev {
            transition: transform .2s ease;
        }

        .mobile-dropdown .mobile-dropdown-menu {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows .25s ease;
            padding-left: 10px;
        }

        .mobile-dropdown .mobile-dropdown-menu>* {
            overflow: hidden;
        }

        .mobile-dropdown.open .mobile-dropdown-menu {
            grid-template-rows: 1fr;
        }

        .mobile-dropdown.open .chev {
            transform: rotate(180deg);
        }

        @media (max-width: 1199px) {
            #navmenu {
                display: none !important;
            }
        }

        @media (min-width: 1200px) {

            .mobile-sidebar,
            .mobile-sidebar-overlay {
                display: none;
            }
        }
    </style>

    <!-- =======================================================
  * Template Name: FlexStart
  * Template URL: https://bootstrapmade.com/flexstart-bootstrap-startup-template/
  * Updated: Nov 01 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">
    <!-- Network Animation Background -->
    <div class="network-bg" id="networkBg"></div>

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="{{ route('guest.guest_dashboard') }}" class="logo d-flex align-items-center me-auto">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="{{ asset('template/Dashboard/assets/img/logo.png') }}" alt="">
                <h1 class="sitename"></h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Home<br></a></li>
                    <li class="dropdown"><a href="#"><span>Layanan</span> <i
                                class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            <li class="dropdown"><a href="{{ Route('guest.create_tiket') }}"><span>KIRIM ADUAN
                                        SIBER</span> <i class="bi bi-chevron-right toggle-dropright"></i></a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="#"><span>Informasi</span> <i
                                class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            <li><a href="{{ route('guest.news.index') }}">Berita</a></li>
                            <li class="dropdown"><a href="#"><span>Event</span> <i
                                        class="bi bi-chevron-right toggle-dropright"></i></a>
                                <ul>
                                    <li><a href="{{ route('guest.events.index') }}">Agenda</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="#about" class="active">Tentang Kami<br></a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
            </nav><!-- End Nav Menu -->
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>

            <div class="mobile-sidebar-overlay"></div>
            <!-- Mobile Sidebar -->
            <div class="mobile-sidebar">
                <i class="sidebar-close bi bi-x"></i>
                <div class="sidebar-logo">
                    <img src="{{ asset('template/Dashboard/assets/img/logo.png') }}" alt="CSIRT Logo">
                </div>
                <ul class="mobile-nav">
                    <li><a href="#hero">Home</a></li>

                    <li class="mobile-dropdown">
                        <a href="#" class="mobile-dropdown-toggle">Layanan <i
                                class="bi bi-chevron-down chev"></i></a>
                        <div class="mobile-dropdown-menu">
                            <div>
                                <a href="{{ Route('guest.create_tiket') }}">KIRIM ADUAN SIBER</a>
                            </div>
                        </div>
                    </li>

                    <li class="mobile-dropdown">
                        <a href="#" class="mobile-dropdown-toggle">Informasi <i
                                class="bi bi-chevron-down chev"></i></a>
                        <div class="mobile-dropdown-menu">
                            <div>
                                <a href="{{ route('guest.news.index') }}">Berita</a>
                                <a href="{{ route('guest.events.index') }}">Agenda</a>
                            </div>
                        </div>
                    </li>

                    <li><a href="#about">Tentang Kami</a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
            </div>
        </div>
    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section">

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
                        <h1 data-aos="fade-up"> Siaga 24/7 untuk Keamanan Siber Anda</h1>
                        <p data-aos="fade-up" data-aos-delay="100">Kami adalah tim respons insiden keamanan siber yang
                            berdedikasi, siap bertindak cepat untuk mengatasi serangan siber dan meminimalkan kerugian
                            Anda.</p>

                        <div class="d-flex flex-column flex-md-row" data-aos="fade-up" data-aos-delay="200">
                            <a href="{{ Route('guest.create_tiket') }}" class="btn-get-started">KIRIM ADUAN CYBER<i
                                    class="bi bi-cursor"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2 hero-img position-relative" data-aos="zoom-out"
                        style="position: relative;">
                        <img src="{{ asset('template/Dashboard/assets/img/hero-img.png') }}"
                            class="img-fluid animated" alt="" style="position: relative; z-index: 2;">
                    </div>
                </div>
            </div>

        </section><!-- /Hero Section -->

        <!-- Security Intelligence Section -->
        <section id="security-intelligence" class="section bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Section Title -->
                        <div class="section-title" data-aos="fade-up">
                            <p>Halaman berita</p>
                            <br>
                            <h2>BERITA TERKINI</h2>
                        </div>

                        <!-- Recent Posts -->
                        <div class="recent-posts" data-aos="fade-up" data-aos-delay="100">

                            @forelse($latestNews as $news)
                                <article class="post-item d-flex">
                                    <div class="post-img">
                                        @if ($news->image)
                                            <img src="{{ asset('storage/' . $news->image) }}"
                                                alt="{{ $news->title }}">
                                        @else
                                            <img src="{{ asset('template/Dashboard/assets/img/blog/blog-1.jpg') }}"
                                                alt="{{ $news->title }}">
                                        @endif
                                    </div>
                                    <div class="post-content">
                                        <div class="post-meta">
                                            <span class="post-date">{{ $news->created_at->format('d M Y') }}</span>
                                        </div>
                                        <h3><a
                                                href="{{ route('guest.news.show', $news->slug) }}">{{ $news->title }}</a>
                                        </h3>
                                        <p>{{ $news->excerpt }}</p>
                                        <a href="{{ route('guest.news.show', $news->slug) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </article>
                            @empty
                                <div class="text-center py-5">
                                    <i class="bi bi-shield-exclamation" style="font-size: 3rem; color: #6c757d;"></i>
                                    <h4 class="mt-3 text-muted">Belum Ada Informasi Keamanan</h4>
                                    <p class="text-muted">Peringatan keamanan siber akan dipublikasikan di sini ketika
                                        tersedia.</p>
                                </div>
                            @endforelse

                            @if ($latestNews->count() > 0)
                                <div class="text-center mt-4">
                                    <a href="{{ route('guest.news.index') }}" class="btn btn-primary">
                                        Lihat Semua Berita <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Quick Actions -->
                        <div class="widget quick-actions-widget" data-aos="fade-up" data-aos-delay="300">
                            <h4>Aksi Cepat</h4>
                            <div class="d-grid gap-2">
                                <a href="{{ route('guest.create_tiket') }}" class="btn btn-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Laporkan Insiden Keamanan
                                </a>
                                <a href="{{ route('guest.news.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-newspaper-me-2"></i>Lihat Semua Berita
                                </a>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="widget contact-widget" data-aos="fade-up" data-aos-delay="400">
                            <h4>Kontak Darurat</h4>
                            <div class="contact-info">
                                <p><strong>Respon Insiden 24/7</strong></p>
                                <p><i class="bi bi-telephone me-2"></i>+62 370 XXX-XXXX</p>
                                <p><i class="bi bi-envelope me-2"></i>incident@csirt-loteng.go.id</p>
                                <p class="small text-muted">Untuk insiden keamanan kritis yang memerlukan respon segera
                                </p>
                            </div>
                        </div>

                        <!-- Stats Section -->

                        <section id="stats" class="stats section">
                            <h3 class="mt-4 mb-3 fw-bold text-primary text-center" data-aos="fade-up"
                                data-aos-delay="500">SATISTIK TIKET</h3>

                            <div data-aos="fade-up" data-aos-delay="100">

                                <div class="row g-3 gy-4">

                                    <div class="col-6 col-md-6">
                                        <div class="card stats-card shadow-sm border-0 h-100 mb-3 mb-md-0">
                                            <div
                                                class="card-body d-flex flex-column align-items-center gap-2 text-center">
                                                <div class="icon-wrap bg-primary-subtle text-primary mb-2"
                                                    style="width:48px;height:48px;border-radius:12px;display:grid;place-items:center;font-size:22px;">
                                                    <i class="bi bi-ticket-detailed"></i>
                                                </div>
                                                <div>
                                                    <div class="h3 mb-1 fw-bold text-primary">
                                                        <span class="purecounter" data-purecounter-start="0"
                                                            data-purecounter-end="{{ $countSent }}"
                                                            data-purecounter-duration="1">{{ $countSent }}</span>
                                                    </div>
                                                    <small class="text-muted">Total tiket</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6 col-md-6">
                                        <div class="card stats-card shadow-sm border-0 h-100 mb-3 mb-md-0">
                                            <div
                                                class="card-body d-flex flex-column align-items-center gap-2 text-center">
                                                <div class="icon-wrap bg-success-subtle text-success mb-2"
                                                    style="width:48px;height:48px;border-radius:12px;display:grid;place-items:center;font-size:22px;">
                                                    <i class="bi bi-patch-check"></i>
                                                </div>
                                                <div>
                                                    <div class="h3 mb-1 fw-bold text-success">
                                                        <span class="purecounter" data-purecounter-start="0"
                                                            data-purecounter-end="{{ $countWorkedOn }}"
                                                            data-purecounter-duration="1">{{ $countWorkedOn }}</span>
                                                    </div>
                                                    <small class="text-muted">Tiket Resolved</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="services section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Services</h2>
                <p>Cek Layanan Kami<br></p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-item item-orange position-relative">
                            <i class="bi bi-lightning-charge"style="font-size: 56px;"></i>
                            <h3>Penanganan Insiden Cepat</h3>
                            <p>Respon cepat untuk meminimalkan dampak serangan siber dan memulihkan sistem Anda.</p>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-item item-teal position-relative">
                            <i class="bi bi-shield-check" style="font-size: 56px;"></i>
                            <h3> Monitoring Ancaman 24/7</h3>
                            <p>Memantau sistem Anda secara real-time untuk mendeteksi potensi ancaman siber sebelum
                                menjadi masalah serius</p>

                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="service-item item-red position-relative">
                            <i class="bi bi-book" style="font-size: 56px;"></i>
                            <h3>Edukasi & Pelatihan Keamanan</h3>
                            <p>Memberikan pelatihan dan panduan keamanan bagi pengguna agar lebih siap menghadapi
                                ancaman digital.</p>
                        </div>
                    </div><!-- End Service Item -->


                </div>

            </div>

        </section><!-- /Services Section -->


        <!-- About Section -->

        <section id="about" class="about section">
            <div class="container section-title" data-aos="fade-up">
                <h2>About Us</h2>
                <p>Tentang Kami<br></p>
            </div>
            <div class="container" data-aos="fade-up">
                <div class="row gx-0">

                    <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up"
                        data-aos-delay="200">
                        <div class="content">
                            <h3>Who We Are</h3>
                            <h2>Tim Keamanan Siber Andal untuk Melindungi Data Anda.</h2>
                            <p>
                                Kami adalah tim respon insiden keamanan siber yang siap membantu instansi dan masyarakat
                                dalam mencegah, mendeteksi, dan menangani ancaman siber. Dengan pengalaman dan teknologi
                                terbaru, kami memastikan keamanan informasi Anda tetap terjaga.
                            </p>
                            <div class="text-center text-lg-start">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
                        <img src="{{ asset('template/Dashboard/assets/img/about.jpeg') }}" class="img-fluid"
                            alt="">
                    </div>

                </div>
            </div>

        </section><!-- /About Section -->



        </div>

        </div>

        </section><!-- /Stats Section -->

        <!-- Features Section -->
        <section id="features" class="features section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Keunggulan Kami</h2>
                <p>Fitur-fitur Unggulan CSIRT Lombok Tengah<br></p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-5">

                    <div class="col-xl-6" data-aos="zoom-out" data-aos-delay="100">
                        <img src="{{ asset('template/Dashboard/assets/img/features.png') }}" class="img-fluid"
                            alt="">
                    </div>

                    <div class="col-xl-6 d-flex">
                        <div class="row align-self-center gy-4">

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                                <div class="feature-box d-flex align-items-start gap-3">
                                    <i class="bi bi-shield-check"></i>
                                    <div>
                                        <h3>Deteksi Ancaman Real-time</h3>
                                        <p class="mb-0 text-muted">Pemantauan dan pemberitahuan dini untuk
                                            mengidentifikasi ancaman saat terjadi.</p>
                                    </div>
                                </div>
                            </div><!-- End Feature Item -->

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                                <div class="feature-box d-flex align-items-start gap-3">
                                    <i class="bi bi-lightning-charge"></i>
                                    <div>
                                        <h3>Respons Cepat 24/7</h3>
                                        <p class="mb-0 text-muted">Tim siap sedia sepanjang waktu untuk merespons
                                            insiden keamanan.</p>
                                    </div>
                                </div>
                            </div><!-- End Feature Item -->

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                                <div class="feature-box d-flex align-items-start gap-3">
                                    <i class="bi bi-people"></i>
                                    <div>
                                        <h3>Tim Ahli Berpengalaman</h3>
                                        <p class="mb-0 text-muted">Tenaga profesional dengan sertifikasi dan pengalaman
                                            menangani berbagai insiden.</p>
                                    </div>
                                </div>
                            </div><!-- End Feature Item -->

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                                <div class="feature-box d-flex align-items-start gap-3">
                                    <i class="bi bi-graph-up"></i>
                                    <div>
                                        <h3>Analisis Forensik Digital</h3>
                                        <p class="mb-0 text-muted">Investigasi mendalam untuk mengungkap jejak serangan
                                            dan penyebab insiden.</p>
                                    </div>
                                </div>
                            </div><!-- End Feature Item -->

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="600">
                                <div class="feature-box d-flex align-items-start gap-3">
                                    <i class="bi bi-book"></i>
                                    <div>
                                        <h3>Edukasi Keamanan Siber</h3>
                                        <p class="mb-0 text-muted">Pelatihan dan materi edukasi untuk meningkatkan
                                            kesadaran keamanan.</p>
                                    </div>
                                </div>
                            </div><!-- End Feature Item -->

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="700">
                                <div class="feature-box d-flex align-items-start gap-3">
                                    <i class="bi bi-gear"></i>
                                    <div>
                                        <h3>Solusi Terintegrasi</h3>
                                        <p class="mb-0 text-muted">Pendekatan end-to-end dari pencegahan, deteksi,
                                            respons, hingga pemulihan.</p>
                                    </div>
                                </div>
                            </div><!-- End Feature Item -->

                        </div>
                    </div>

                </div>

            </div>

        </section><!-- /Features Section -->

        <!-- Alt Features Section -->
        <section id="alt-features" class="alt-features section">

            <div class="container">

                <div class="row gy-5">

                    <div class="col-xl-7 d-flex order-2 order-xl-1" data-aos="fade-up" data-aos-delay="200">

                        <div class="row align-self-center gy-5">

                            <div class="col-md-6 icon-box d-flex align-items-start gap-3">
                                <i class="bi bi-envelope-exclamation"></i>
                                <div>
                                    <h4>Phishing</h4>
                                    <p>Penipuan digital untuk mencuri informasi sensitif seperti password dan data kartu
                                        kredit melalui email atau situs palsu</p>
                                </div>
                            </div><!-- End Feature Item -->

                            <div class="col-md-6 icon-box d-flex align-items-start gap-3">
                                <i class="bi bi-shield-exclamation"></i>
                                <div>
                                    <h4>Malware & Ransomware</h4>
                                    <p>Perangkat lunak berbahaya yang dapat merusak sistem dan mengenkripsi data untuk
                                        meminta tebusan</p>
                                </div>
                            </div><!-- End Feature Item -->

                            <div class="col-md-6 icon-box d-flex align-items-start gap-3">
                                <i class="bi bi-wifi-off"></i>
                                <div>
                                    <h4>Serangan DDoS</h4>
                                    <p>Serangan yang membanjiri server dengan traffic palsu untuk melumpuhkan layanan
                                        online
                                    </p>
                                </div>
                            </div><!-- End Feature Item -->

                            <div class="col-md-6 icon-box d-flex align-items-start gap-3">
                                <i class="bi bi-person-exclamation"></i>
                                <div>
                                    <h4>Social Engineering</h4>
                                    <p>Manipulasi psikologis untuk mendapatkan informasi rahasia atau akses tidak sah ke
                                        sistem</p>
                                </div>
                            </div><!-- End Feature Item -->

                            <div class="col-md-6 icon-box d-flex align-items-start gap-3">
                                <i class="bi bi-file-earmark-lock"></i>
                                <div>
                                    <h4>Kerentanan Sistem</h4>
                                    <p>Kelemahan konfigurasi atau celah keamanan yang memungkinkan penyerang mendapatkan
                                        akses tidak sah</p>
                                </div>
                            </div><!-- End Feature Item -->

                            <div class="col-md-6 icon-box d-flex align-items-start gap-3">
                                <i class="bi bi-shield-lock"></i>
                                <div>
                                    <h4>Kebocoran Data</h4>
                                    <p>Insiden dimana data sensitif diakses, dicuri, atau diungkapkan tanpa izin kepada
                                        pihak yang tidak berwenang</p>
                                </div>
                            </div><!-- End Feature Item -->

                        </div>

                    </div>

                    <div class="col-xl-5 d-flex align-items-center order-1 order-xl-2" data-aos="fade-up"
                        data-aos-delay="100">
                        <img src="{{ asset('template/Dashboard/assets/img/alt-features.png') }}" class="img-fluid"
                            alt="">
                    </div>

                </div>

            </div>

        </section><!-- /Alt Features Section -->

        <!-- Faq Section -->
        <section id="faq" class="faq section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>F.A.Q</h2>
                <p>Pertanyaan yang Sering Diajukan</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row">

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">

                        <div class="faq-container">

                            <div class="faq-item faq-active">
                                <h3>Apa itu CSIRT dan apa fungsinya?</h3>
                                <div class="faq-content">
                                    <p>CSIRT (Computer Security Incident Response Team) adalah tim yang bertugas
                                        menangani insiden keamanan siber. Kami berfungsi untuk mencegah, mendeteksi,
                                        menganalisis, dan merespons ancaman keamanan siber yang dapat merugikan
                                        organisasi atau masyarakat.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Bagaimana cara melaporkan insiden keamanan siber?</h3>
                                <div class="faq-content">
                                    <p>Anda dapat melaporkan insiden keamanan siber melalui formulir aduan di website
                                        kami atau menghubungi hotline 24/7. Sertakan informasi detail tentang insiden
                                        yang terjadi, waktu kejadian, dan dampak yang dialami untuk membantu kami
                                        memberikan respons yang tepat.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Berapa lama waktu respons CSIRT terhadap laporan insiden?</h3>
                                <div class="faq-content">
                                    <p>Waktu respons kami bervariasi tergantung tingkat keparahan insiden. Untuk insiden
                                        kritis, kami merespons dalam 1-2 jam. Insiden dengan tingkat menengah ditangani
                                        dalam 4-8 jam, sedangkan insiden ringan akan ditangani dalam 24 jam.
                                    </p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                        </div>

                    </div><!-- End Faq Column-->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">

                        <div class="faq-container">

                            <div class="faq-item">
                                <h3>Apakah layanan CSIRT gratis untuk masyarakat?</h3>
                                <div class="faq-content">
                                    <p>Ya, layanan dasar CSIRT seperti konsultasi keamanan siber, pelaporan insiden, dan
                                        edukasi keamanan siber tersedia gratis untuk masyarakat. Namun untuk layanan
                                        khusus seperti audit keamanan mendalam atau forensik digital mungkin dikenakan
                                        biaya.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Apa yang harus dilakukan jika terkena serangan ransomware?</h3>
                                <div class="faq-content">
                                    <p>Jangan panik dan jangan membayar tebusan. Segera putuskan koneksi internet,
                                        laporkan ke CSIRT, backup data yang masih bisa diselamatkan, dan jangan
                                        menghapus file yang terenkripsi karena mungkin masih bisa dipulihkan.
                                    </p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Bagaimana cara melindungi diri dari serangan phishing?</h3>
                                <div class="faq-content">
                                    <p>Selalu verifikasi pengirim email, jangan klik link mencurigakan, periksa URL
                                        dengan teliti, gunakan two-factor authentication, dan selalu update software
                                        keamanan. Jika ragu, hubungi CSIRT untuk verifikasi.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                        </div>

                    </div><!-- End Faq Column-->

                </div>

            </div>

        </section><!-- /Faq Section -->



        <!-- Clients Section -->
        <section id="clients" class="clients section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Clients</h2>
                <p>We work with best clients<br></p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="swiper init-swiper">
                    <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 2,
                  "spaceBetween": 40
                },
                "480": {
                  "slidesPerView": 3,
                  "spaceBetween": 60
                },
                "640": {
                  "slidesPerView": 4,
                  "spaceBetween": 80
                },
                "992": {
                  "slidesPerView": 6,
                  "spaceBetween": 120
                }
              }
            }
          </script>
                    <div class="swiper-wrapper align-items-center">
                        <div class="swiper-slide"><img
                                src="{{ asset('template/Dashboard/assets/img/clients/client-1.png') }}"
                                class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img
                                src="{{ asset('template/Dashboard/assets/img/clients/client-2.png') }}"
                                class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img
                                src="{{ asset('template/Dashboard/assets/img/clients/client-3.png') }}"
                                class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img
                                src="{{ asset('template/Dashboard/assets/img/clients/client-4.png') }}"
                                class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img
                                src="{{ asset('template/Dashboard/assets/img/clients/client-5.png') }}"
                                class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img
                                src="{{ asset('template/Dashboard/assets/img/clients/client-6.png') }}"
                                class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img
                                src="{{ asset('template/Dashboard/assets/img/clients/client-7.png') }}"
                                class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img
                                src="{{ asset('template/Dashboard/assets/img/clients/client-8.png') }}"
                                class="img-fluid" alt=""></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>

        </section><!-- /Clients Section -->



        <!-- Contact Section -->
        <section id="contact" class="contact section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Contact</h2>
                <p>Contact Us</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-6">

                        <div class="row gy-4">
                            <div class="col-md-6">
                                <div class="info-item" data-aos="fade" data-aos-delay="200">
                                    <i class="bi bi-geo-alt"></i>
                                    <h3>Alamat</h3>
                                    <p>Kantor Pemerintah Daerah</p>
                                    <p>Lombok Tengah, NTB</p>
                                </div>
                            </div><!-- End Info Item -->

                            <div class="col-md-6">
                                <div class="info-item" data-aos="fade" data-aos-delay="300">
                                    <i class="bi bi-telephone"></i>
                                    <h3>Hubungi Kami</h3>
                                    <p>+62 370 123456</p>
                                    <p>Hotline 24/7: 0800-CSIRT</p>
                                </div>
                            </div><!-- End Info Item -->

                            <div class="col-md-6">
                                <div class="info-item" data-aos="fade" data-aos-delay="400">
                                    <i class="bi bi-envelope"></i>
                                    <h3>Email</h3>
                                    <p>csirt@lomboktengahkab.go.id</p>
                                    <p>incident@lomboktengahkab.go.id</p>
                                </div>
                            </div><!-- End Info Item -->

                            <div class="col-md-6">
                                <div class="info-item" data-aos="fade" data-aos-delay="500">
                                    <i class="bi bi-clock"></i>
                                    <h3>Jam Operasional</h3>
                                    <p>Senin - Jumat: 08:00 - 16:00</p>
                                    <p>Emergency Response: 24/7</p>
                                </div>
                            </div><!-- End Info Item -->

                        </div>

                    </div>

                    <div class="col-lg-6">
                        <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up"
                            data-aos-delay="200">
                            <div class="row gy-4">

                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Nama Lengkap" required="">
                                </div>

                                <div class="col-md-6 ">
                                    <input type="email" class="form-control" name="email"
                                        placeholder="Email Anda" required="">
                                </div>

                                <div class="col-12">
                                    <input type="text" class="form-control" name="subject"
                                        placeholder="Subjek Pesan" required="">
                                </div>

                                <div class="col-12">
                                    <textarea class="form-control" name="message" rows="6" placeholder="Pesan atau Laporan Insiden Keamanan Siber"
                                        required=""></textarea>
                                </div>

                                <div class="col-12 text-center">
                                    <div class="loading">Mengirim...</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Pesan Anda telah terkirim. Terima kasih!</div>

                                    <button type="submit">Kirim Pesan</button>
                                </div>

                            </div>
                        </form>
                    </div><!-- End Contact Form -->

                </div>

            </div>

        </section><!-- /Contact Section -->

    </main>



    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('template/Dashboard/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/Dashboard/assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('template/Dashboard/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('template/Dashboard/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('template/Dashboard/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('template/Dashboard/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('template/Dashboard/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('template/Dashboard/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('template/Dashboard/assets/js/main.js') }}"></script>

    <script>
        // Mobile sidebar interactions (guest dashboard page)
        (function() {
            const body = document.body;
            const toggleBtn = document.querySelector('.mobile-nav-toggle');
            const sidebar = document.querySelector('.mobile-sidebar');
            const overlay = document.querySelector('.mobile-sidebar-overlay');
            const closeBtn = document.querySelector('.sidebar-close');

            function syncHamburgerIcon() {
                if (!toggleBtn) return;
                toggleBtn.style.display = '';
                toggleBtn.classList.remove('bi-x', 'bi-x-lg');
                if (!toggleBtn.classList.contains('bi-list')) toggleBtn.classList.add('bi-list');
            }

            function openSidebar(e) {
                if (e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                }
                document.body.classList.remove('mobile-nav-active');
                sidebar.classList.add('open');
                overlay.classList.add('active');
                body.classList.add('no-scroll');
                syncHamburgerIcon();
            }

            function closeSidebar(e) {
                if (e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                }
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
                body.classList.remove('no-scroll');
                document.body.classList.remove('mobile-nav-active');
                syncHamburgerIcon();
            }

            toggleBtn && toggleBtn.addEventListener('click', openSidebar);
            overlay && overlay.addEventListener('click', closeSidebar);
            closeBtn && closeBtn.addEventListener('click', closeSidebar);

            // Close on link click, but allow navigation; keep sidebar for dropdown toggle
            function closeSidebarFromLink() {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
                body.classList.remove('no-scroll');
                document.body.classList.remove('mobile-nav-active');
                syncHamburgerIcon();
            }
            document.querySelectorAll('.mobile-sidebar a:not(.mobile-dropdown-toggle)').forEach(function(a) {
                a.addEventListener('click', closeSidebarFromLink);
            });

            document.querySelectorAll('.mobile-dropdown-toggle').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    const parent = el.closest('.mobile-dropdown');
                    if (parent) parent.classList.toggle('open');
                });
            });

            window.addEventListener('resize', syncHamburgerIcon);
        })();
    </script>

    <!-- Network Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const networkBg = document.getElementById('networkBg');

            // Create network nodes
            function createNetworkNodes() {
                const nodeCount = 15;

                for (let i = 0; i < nodeCount; i++) {
                    const node = document.createElement('div');
                    node.className = 'network-node';
                    node.style.left = Math.random() * 100 + '%';
                    node.style.top = Math.random() * 100 + '%';
                    node.style.animationDelay = Math.random() * 2 + 's';
                    networkBg.appendChild(node);
                }
            }

            // Create flowing lines
            function createNetworkLines() {
                const lineCount = 8;

                setInterval(() => {
                    if (document.querySelectorAll('.network-line').length < lineCount) {
                        const line = document.createElement('div');
                        line.className = 'network-line';
                        line.style.top = Math.random() * 100 + '%';
                        line.style.width = Math.random() * 300 + 100 + 'px';
                        line.style.animationDuration = (Math.random() * 2 + 3) + 's';
                        networkBg.appendChild(line);

                        // Remove line after animation
                        setTimeout(() => {
                            if (line.parentNode) {
                                line.parentNode.removeChild(line);
                            }
                        }, 5000);
                    }
                }, 1000);
            }

            // Initialize network animation
            createNetworkNodes();
            createNetworkLines();

            // Add mouse interaction
            document.addEventListener('mousemove', function(e) {
                const nodes = document.querySelectorAll('.network-node');
                const mouseX = e.clientX / window.innerWidth;
                const mouseY = e.clientY / window.innerHeight;

                nodes.forEach((node, index) => {
                    const nodeX = parseFloat(node.style.left) / 100;
                    const nodeY = parseFloat(node.style.top) / 100;
                    const distance = Math.sqrt(Math.pow(mouseX - nodeX, 2) + Math.pow(mouseY -
                        nodeY, 2));

                    if (distance < 0.2) {
                        node.style.transform = `scale(2)`;
                        node.style.opacity = '1';
                    } else {
                        node.style.transform = `scale(1)`;
                        node.style.opacity = '0.6';
                    }
                });
            });
        });
    </script>

</body>

</html>
