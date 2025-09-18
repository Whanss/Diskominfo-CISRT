<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>CSIRT - LOMBOK TENGAH</title>
    <meta name="description" content="">
    <meta name="keywords" content="">


    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
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

    <!-- Custom CSS -->
    <style>
        body {
            padding-top: 70px;
            /* Adjust this value to match the height of your header */
            background-color: var(--bg);


        }

        .container-fluid {
            padding: 0 10px;
            /* Adjust padding as needed */
        }

        .navmenu {
            display: flex;
            align-items: center;
        }

        .navmenu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }



        .navmenu a {
            text-decoration: none;
            color: #374151;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .navmenu a:hover {
            color: var(--primary);
        }

        .btn-getstarted {
            margin-left: 15px;
            background-color: var(--primary);
            color: #fff;
            border-radius: 8px;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }

        .btn-getstarted:hover {
            background-color: var(--primary-dark);
        }

        .main {
            padding-top: 20px;
            /* Add some space between the header and the main content */
        }

        /* Prevent scroll when sidebar open */
        body.no-scroll {
            overflow: hidden;
        }

        /* Mobile Sidebar */
        .mobile-sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
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
            background: #ffffff;
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.15);
            transform: translateX(-100%);
            transition: transform 0.25s ease;
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
            color: var(--primary, #1a365d);
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

            /* hide desktop nav on mobile */
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

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="/" class="logo d-flex align-items-center me-auto">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="{{ asset('template/Dashboard/assets/img/logo.png') }}" alt="">
                <h1 class="sitename"></h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="{{ route('guest.guest_dashboard') }}/" class="active">Home<br></a></li>
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
                    <li><a href="{{ route('guest.guest_dashboard') }}#about" class="active">Tentang Kami<br></a></li>
                    <li><a href="{{ route('guest.guest_dashboard') }}#contact">Kontak</a></li>
                </ul>
            </nav>

            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>

            <div class="mobile-sidebar-overlay"></div>
            <!-- Mobile Sidebar -->
            <div class="mobile-sidebar">
                <i class="sidebar-close bi bi-x"></i>
                <div class="sidebar-logo">
                    <img src="{{ asset('template/Dashboard/assets/img/logo.png') }}" alt="CSIRT Logo">
                </div>
                <ul class="mobile-nav">
                    <li><a href="{{ route('guest.guest_dashboard') }}/">Home</a></li>

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

                    <li><a href="{{ route('guest.guest_dashboard') }}#about">Tentang Kami</a></li>
                    <li><a href="{{ route('guest.guest_dashboard') }}#contact">Kontak</a></li>
                </ul>
            </div>

        </div>
    </header>

    <main class="main">
        @yield('content')
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
        // Mobile sidebar interactions
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
                // Pastikan toggle bawaan template tidak mengubah ikon
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

            // Close sidebar when clicking any non-toggle link, but allow navigation
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

            // Mobile dropdown toggle inside sidebar (do not close sidebar)
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

</body>

</html>
