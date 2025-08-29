<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ asset('template/dashboard admin/css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @stack('styles')

    <style>
        /* Modern Tailwind-inspired Admin Layout */
        :root {
            --primary-50: #eff6ff;
            --primary-100: #dbeafe;
            --primary-500: #3b82f6;
            --primary-600: #2563eb;
            --primary-700: #1d4ed8;
            --primary-800: #1e40af;
            --primary-900: #1e3a8a;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--gray-50);
            color: var(--gray-900);
            line-height: 1.5;
            margin: 0;
            overflow-x: hidden;
        }

        /* Top Navigation */
        .modern-topnav {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(20px);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            height: 64px;
            transition: all 0.3s ease;
        }

        .topnav-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 1.5rem;
            max-width: none;
        }

        .topnav-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.125rem;
            color: var(--gray-900);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .topnav-brand:hover {
            color: var(--primary-600);
            text-decoration: none;
        }

        .brand-logo {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .sidebar-toggle {
            background: none;
            border: 1px solid var(--gray-200);
            color: var(--gray-600);
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }

        .sidebar-toggle:hover {
            background: var(--gray-100);
            border-color: var(--gray-300);
            color: var(--gray-800);
        }

        .topnav-search {
            flex: 1;
            max-width: 400px;
            margin: 0 2rem;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid var(--gray-300);
            border-radius: 12px;
            background: var(--gray-50);
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: 2px solid var(--primary-500);
            outline-offset: -2px;
            border-color: var(--primary-500);
            background: white;
            box-shadow: 0 0 0 3px rgb(59 130 246 / 0.1);
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 0.875rem;
        }

        .topnav-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-dropdown {
            position: relative;
        }

        .user-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            background: white;
            color: var(--gray-700);
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .user-button:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
            color: var(--gray-900);
            text-decoration: none;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Sidebar */
        .modern-sidebar {
            position: fixed;
            top: 64px;
            left: 0;
            width: 280px;
            height: calc(100vh - 64px);
            background: white;
            border-right: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            overflow-y: auto;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1020;
        }

        .sidebar-collapsed .modern-sidebar {
            width: 72px;
        }

        .sidebar-content {
            padding: 1.5rem 0;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-heading {
            padding: 0 1.5rem;
            margin-bottom: 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-500);
            transition: all 0.3s ease;
        }

        .sidebar-collapsed .nav-heading {
            opacity: 0;
            transform: translateX(-10px);
        }

        .nav-items {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: var(--gray-700);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            position: relative;
            border-radius: 0;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 32px;
            background: var(--primary-600);
            border-radius: 0 4px 4px 0;
            transition: width 0.2s ease;
        }

        .nav-link:hover {
            background: var(--gray-50);
            color: var(--gray-900);
            text-decoration: none;
        }

        .nav-link:hover::before {
            width: 3px;
        }

        .nav-link.active {
            background: var(--primary-50);
            color: var(--primary-700);
        }

        .nav-link.active::before {
            width: 3px;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .nav-text {
            transition: all 0.3s ease;
        }

        .sidebar-collapsed .nav-text {
            opacity: 0;
            transform: translateX(-10px);
        }

        .nav-arrow {
            margin-left: auto;
            font-size: 0.75rem;
            transition: transform 0.2s ease;
        }

        .sidebar-collapsed .nav-arrow {
            opacity: 0;
        }

        /* Collapsible sections */
        .nav-link[data-bs-toggle="collapse"] .nav-arrow {
            transform: rotate(0deg);
        }

        .nav-link[data-bs-toggle="collapse"]:not(.collapsed) .nav-arrow {
            transform: rotate(90deg);
        }

        .nav-collapse {
            background: var(--gray-25);
        }

        .nav-collapse .nav-link {
            padding-left: 3.5rem;
            font-size: 0.8125rem;
            color: var(--gray-600);
        }

        .nav-collapse .nav-link:hover {
            color: var(--gray-800);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            margin-top: 64px;
            min-height: calc(100vh - 64px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--gray-50);
        }

        .sidebar-collapsed .main-content {
            margin-left: 72px;
        }

        .content-wrapper {
            padding: 0;
            min-height: calc(100vh - 64px - 80px);
            /* Subtract topnav and footer height */
        }

        /* Footer */
        .modern-footer {
            background: white;
            border-top: 1px solid var(--gray-200);
            padding: 1.5rem 0;
            margin-top: auto;
        }

        .footer-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        .footer-links {
            display: flex;
            gap: 1rem;
        }

        .footer-links a {
            color: var(--gray-600);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: var(--gray-900);
            text-decoration: none;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            min-width: 200px;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            color: var(--gray-700);
            font-size: 0.875rem;
            transition: all 0.2s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dropdown-item:hover {
            background: var(--gray-50);
            color: var(--gray-900);
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-top: 1px solid var(--gray-200);
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .modern-sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar-open .modern-sidebar {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .topnav-search {
                display: none;
            }

            .topnav-container {
                padding: 0 1rem;
            }

            .sidebar-overlay {
                position: fixed;
                top: 64px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1010;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            .sidebar-open .sidebar-overlay {
                opacity: 1;
                visibility: visible;
            }
        }

        /* Sidebar User Info */
        .sidebar-user {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--gray-200);
            margin-top: auto;
        }

        .sidebar-user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .sidebar-collapsed .sidebar-user-info {
            justify-content: center;
        }

        .sidebar-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            flex-shrink: 0;
        }

        .sidebar-user-details {
            transition: all 0.3s ease;
        }

        .sidebar-collapsed .sidebar-user-details {
            opacity: 0;
            transform: translateX(-10px);
        }

        .sidebar-user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
            line-height: 1.2;
        }

        .sidebar-user-role {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin: 0;
            line-height: 1.2;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .nav-link {
            animation: slideIn 0.3s ease forwards;
        }

        .nav-link:nth-child(1) {
            animation-delay: 0.05s;
        }

        .nav-link:nth-child(2) {
            animation-delay: 0.1s;
        }

        .nav-link:nth-child(3) {
            animation-delay: 0.15s;
        }

        .nav-link:nth-child(4) {
            animation-delay: 0.2s;
        }

        .nav-link:nth-child(5) {
            animation-delay: 0.25s;
        }

        /* Scrollbar Styling */
        .modern-sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .modern-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .modern-sidebar::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 2px;
        }

        .modern-sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        /* Content padding adjustment */
        main {
            padding: 0;
        }

        /* Loading states */
        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid var(--gray-300);
            border-top: 2px solid var(--primary-500);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="modern-admin">
    <!-- Top Navigation -->
    <nav class="modern-topnav">
        <div class="topnav-container">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="topnav-brand" href="{{ route('admin.dashboard') }}">
                    <div class="brand-logo">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span>CSIRT Admin</span>
                </a>
            </div>

            <div class="topnav-actions">
                <div class="user-dropdown dropdown">
                    <a class="user-button dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span style="font-size: 0.875rem; font-weight: 500;">{{ Auth::user()->name ?? 'Admin' }}</span>
                        <i class="fas fa-chevron-down" style="font-size: 0.75rem;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <a class="dropdown-item" href="{{ route('admin.tickets.activity') }}">
                                <i class="fas fa-history"></i>
                                <span>Activity Log</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <nav class="modern-sidebar" id="sidebar">
        <div class="sidebar-content">
            <!-- Core Section -->
            <div class="nav-section">
                <div class="nav-heading">Core</div>
                <ul class="nav-items">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <div class="nav-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Management Section -->
            <div class="nav-section">
                <div class="nav-heading">Management</div>
                <ul class="nav-items">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.tickets*') ? 'active' : '' }}"
                            href="{{ route('admin.tickets.index') }}">
                            <div class="nav-icon">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <span class="nav-text">All Tickets</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.tickets.activity') ? 'active' : '' }}"
                            href="{{ route('admin.tickets.activity') }}">
                            <div class="nav-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <span class="nav-text">Activity Logs</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.tickets.process') ? 'active' : '' }}"
                            href="{{ route('admin.tickets.process') }}">
                            <div class="nav-icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <span class="nav-text">Process Tickets</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.tickets.export') ? 'active' : '' }}"
                            href="{{ route('admin.tickets.export') }}">
                            <div class="nav-icon">
                                <i class="fas fa-download"></i>
                            </div>
                            <span class="nav-text">Export Tickets</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.kabupaten*') ? 'active' : '' }}"
                            href="{{ route('admin.kabupaten.index') }}">
                            <div class="nav-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <span class="nav-text">Kabupaten</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.kecamatan*') ? 'active' : '' }}"
                            href="{{ route('admin.kecamatan.index') }}">
                            <div class="nav-icon">
                                <i class="fas fa-map-pin"></i>
                            </div>
                            <span class="nav-text">Kecamatan</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.layanan*') ? 'active' : '' }}"
                            href="{{ route('admin.layanan.index') }}">
                            <div class="nav-icon">
                                <i class="fas fa-concierge-bell"></i>
                            </div>
                            <span class="nav-text">Layanan</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Ticket Quick Actions -->
            <div class="nav-section">
                <div class="nav-heading">Ticket Actions</div>
                <ul class="nav-items">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.tickets.index', ['status' => 'open']) }}">
                            <div class="nav-icon">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <span class="nav-text">Open Tickets</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.tickets.index', ['status' => 'closed']) }}">
                            <div class="nav-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <span class="nav-text">Closed Tickets</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Sidebar User Info -->
            <div class="sidebar-user">
                <div class="sidebar-user-info">
                    <div class="sidebar-user-avatar">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="sidebar-user-details">
                        <p class="sidebar-user-name">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="sidebar-user-role">Administrator</p>
                    </div>
                </div>
            </div>
    </nav>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="content-wrapper">
            <main>
                @yield('content')
            </main>
        </div>

        <!-- Footer -->
        <footer class="modern-footer">
            <div class="footer-content">
                <div>
                    <span>© {{ date('Y') }} {{ e(config('app.name', 'CSIRT Admin')) }}. All rights
                        reserved.</span>
                </div>
                <div class="footer-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms & Conditions</a>
                    <a href="#">Support</a>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('template/dashboard admin/js/scripts.js') }}"></script>

    <!-- Only load Chart.js on pages that need it -->
    @if (request()->routeIs('admin.dashboard'))
        <!-- Use only the modern Chart.js version -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @else
        <!-- Load old Chart.js for other pages that might need the demo charts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <!-- Only load demo files if the canvas elements exist -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Only load chart demos if the canvas elements exist
                if (document.getElementById('myAreaChart')) {
                    const script1 = document.createElement('script');
                    script1.src = "{{ asset('template/dashboard admin/assets/demo/chart-area-demo.js') }}";
                    document.head.appendChild(script1);
                }

                if (document.getElementById('myBarChart')) {
                    const script2 = document.createElement('script');
                    script2.src = "{{ asset('template/dashboard admin/assets/demo/chart-bar-demo.js') }}";
                    document.head.appendChild(script2);
                }
            });
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('template/dashboard admin/js/datatables-simple-demo.js') }}"></script>

    <script>
        // Modern Sidebar Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const body = document.body;

            // Toggle sidebar collapsed state
            function toggleSidebar() {
                body.classList.toggle('sidebar-collapsed');

                // Save state to localStorage
                const isCollapsed = body.classList.contains('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', isCollapsed);
            }

            // Toggle mobile sidebar
            function toggleMobileSidebar() {
                body.classList.toggle('sidebar-open');
            }

            // Handle sidebar toggle click
            sidebarToggle.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    toggleMobileSidebar();
                } else {
                    toggleSidebar();
                }
            });

            // Handle overlay click (mobile)
            sidebarOverlay.addEventListener('click', function() {
                body.classList.remove('sidebar-open');
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    body.classList.remove('sidebar-open');
                }
            });

            // Restore sidebar state from localStorage
            const savedState = localStorage.getItem('sidebar-collapsed');
            if (savedState === 'true') {
                body.classList.add('sidebar-collapsed');
            }

            // Handle dropdown arrow rotation for collapsible items
            const collapsibleLinks = document.querySelectorAll('[data-bs-toggle="collapse"]');
            collapsibleLinks.forEach(link => {
                const target = document.querySelector(link.getAttribute('data-bs-target'));

                if (target) {
                    target.addEventListener('show.bs.collapse', function() {
                        link.classList.remove('collapsed');
                    });

                    target.addEventListener('hide.bs.collapse', function() {
                        link.classList.add('collapsed');
                    });
                }
            });

            // Enhanced search functionality
            const searchInput = document.querySelector('.search-input');
            const searchResults = document.createElement('div');
            searchResults.className = 'search-results';
            searchResults.style.cssText = `
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                border: 1px solid var(--gray-200);
                border-top: none;
                border-radius: 0 0 12px 12px;
                box-shadow: var(--shadow-lg);
                max-height: 300px;
                overflow-y: auto;
                z-index: 1000;
                display: none;
            `;

            if (searchInput) {
                searchInput.parentElement.style.position = 'relative';
                searchInput.parentElement.appendChild(searchResults);

                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();

                    if (query.length > 0) {
                        searchTimeout = setTimeout(() => performSearch(query), 300);
                    } else {
                        searchResults.style.display = 'none';
                    }
                });

                searchInput.addEventListener('focus', function() {
                    if (this.value.trim().length > 0) {
                        searchResults.style.display = 'block';
                    }
                });

                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                        searchResults.style.display = 'none';
                    }
                });
            }

            function performSearch(query) {
                // Mock search results - replace with actual search logic
                const mockResults = [{
                        title: 'Dashboard',
                        url: '/admin/dashboard',
                        icon: 'fas fa-tachometer-alt'
                    },
                    {
                        title: 'Tickets',
                        url: '/admin/tickets',
                        icon: 'fas fa-ticket-alt'
                    },
                    {
                        title: 'Settings',
                        url: '/admin/settings',
                        icon: 'fas fa-cog'
                    }
                ].filter(item => item.title.toLowerCase().includes(query.toLowerCase()));

                searchResults.innerHTML = '';

                if (mockResults.length > 0) {
                    mockResults.forEach(result => {
                        const resultItem = document.createElement('a');
                        resultItem.href = result.url;
                        resultItem.className = 'search-result-item';
                        resultItem.style.cssText = `
                            display: flex;
                            align-items: center;
                            gap: 0.75rem;
                            padding: 0.75rem 1rem;
                            color: var(--gray-700);
                            text-decoration: none;
                            transition: background 0.2s ease;
                        `;
                        resultItem.innerHTML = `
                            <i class="${result.icon}" style="width: 16px; color: var(--gray-500);"></i>
                            <span style="font-size: 0.875rem;">${result.title}</span>
                        `;

                        resultItem.addEventListener('mouseenter', function() {
                            this.style.background = 'var(--gray-50)';
                        });

                        resultItem.addEventListener('mouseleave', function() {
                            this.style.background = 'transparent';
                        });

                        searchResults.appendChild(resultItem);
                    });

                    searchResults.style.display = 'block';
                } else {
                    const noResults = document.createElement('div');
                    noResults.style.cssText = `
                        padding: 1rem;
                        text-align: center;
                        color: var(--gray-500);
                        font-size: 0.875rem;
                    `;
                    noResults.textContent = 'No results found';
                    searchResults.appendChild(noResults);
                    searchResults.style.display = 'block';
                }
            }

            // Add smooth scroll behavior for in-page navigation
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add loading states for navigation links
            document.querySelectorAll('.nav-link').forEach(link => {
                if (!link.hasAttribute('data-bs-toggle')) {
                    link.addEventListener('click', function() {
                        if (this.href && !this.href.includes('#')) {
                            const icon = this.querySelector('.nav-icon i');
                            if (icon) {
                                const originalClass = icon.className;
                                icon.className = 'fas fa-spinner fa-spin';

                                // Reset after navigation (in case of back button)
                                setTimeout(() => {
                                    icon.className = originalClass;
                                }, 2000);
                            }
                        }
                    });
                }
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + K for search focus
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    if (searchInput) {
                        searchInput.focus();
                    }
                }

                // Ctrl/Cmd + B for sidebar toggle
                if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                    e.preventDefault();
                    sidebarToggle.click();
                }

                // Escape to close mobile sidebar
                if (e.key === 'Escape' && body.classList.contains('sidebar-open')) {
                    body.classList.remove('sidebar-open');
                }
            });

            // Add tooltips for collapsed sidebar
            function updateTooltips() {
                const isCollapsed = body.classList.contains('sidebar-collapsed');
                const navLinks = document.querySelectorAll('.nav-link');

                navLinks.forEach(link => {
                    if (isCollapsed) {
                        const text = link.querySelector('.nav-text');
                        if (text) {
                            link.setAttribute('title', text.textContent);
                            link.setAttribute('data-bs-placement', 'right');
                        }
                    } else {
                        link.removeAttribute('title');
                        link.removeAttribute('data-bs-placement');
                    }
                });

                // Initialize Bootstrap tooltips
                if (isCollapsed) {
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
                    tooltipTriggerList.map(function(tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                }
            }

            // Update tooltips when sidebar state changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        updateTooltips();
                    }
                });
            });

            observer.observe(body, {
                attributes: true,
                attributeFilter: ['class']
            });

            // Initial tooltip setup
            updateTooltips();
        });

        // Add utility functions for notifications and loading states
        window.adminUtils = {
            showNotification: function(type, message, duration = 5000) {
                const notification = document.createElement('div');
                notification.className = `alert alert-${type} alert-dismissible fade show`;
                notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 9999;
                    min-width: 300px;
                    border-radius: 8px;
                    box-shadow: var(--shadow-lg);
                `;

                const icon = type === 'success' ? 'fa-check-circle' :
                    type === 'danger' ? 'fa-exclamation-triangle' :
                    type === 'warning' ? 'fa-exclamation-circle' :
                    'fa-info-circle';

                notification.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas ${icon}"></i>
                        <span>${message}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, duration);
            },

            setLoading: function(element, loading = true) {
                if (loading) {
                    element.disabled = true;
                    const originalText = element.innerHTML;
                    element.setAttribute('data-original-content', originalText);
                    element.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
                } else {
                    element.disabled = false;
                    element.innerHTML = element.getAttribute('data-original-content') || element.innerHTML;
                    element.removeAttribute('data-original-content');
                }
            }
        };
    </script>

    @stack('scripts')
</body>

</html>
