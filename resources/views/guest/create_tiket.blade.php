@extends('layouts.app')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        :root {
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --primary-light: #dbeafe;
            --success: #10b981;
            --success-light: #d1fae5;
            --danger: #ef4444;
            --danger-light: #fee2e2;
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
            --white: #ffffff;
            --radius: 12px;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--gray-900);
            background-color: var(--gray-50);
        }

        .page-container {
            padding: 2rem 1rem;
            background: var(--gray-50);
            min-height: calc(100vh - 120px);
        }

        .main-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .header-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: var(--primary-light);
            border-radius: var(--radius);
            margin-bottom: 1rem;
        }

        .header-icon svg {
            width: 24px;
            height: 24px;
            color: var(--primary);
        }

        .main-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.025em;
        }

        .main-subtitle {
            font-size: 1rem;
            color: var(--gray-600);
            margin: 0;
        }

        .tabs-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .tabs-header {
            display: flex;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }

        .tab-button {
            flex: 1;
            padding: 1rem 1.5rem;
            background: none;
            border: none;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-600);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
        }

        .tab-button.active {
            color: var(--primary);
            background: var(--white);
            border-bottom: 2px solid var(--primary);
        }

        .tab-button:hover:not(.active) {
            color: var(--gray-700);
            background: var(--gray-100);
        }

        .tab-button svg {
            width: 16px;
            height: 16px;
        }

        .tab-content {
            padding: 2rem;
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .section-header svg {
            width: 20px;
            height: 20px;
            color: var(--primary);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
        }

        .section-description {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin: 0 0 1.5rem 0;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            animation: slideDown 0.3s ease-out;
        }

        .alert-success {
            background: var(--success-light);
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-danger {
            background: var(--danger-light);
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .alert ul {
            margin: 0;
            padding-left: 1rem;
        }

        .alert li {
            margin-bottom: 0.25rem;
        }

        .alert li:last-child {
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .form-label svg {
            width: 16px;
            height: 16px;
            color: var(--gray-500);
        }

        .required {
            color: var(--danger);
            font-weight: 700;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            font-family: inherit;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            background: var(--white);
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control:hover:not(:focus) {
            border-color: var(--gray-300);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
            font-family: inherit;
            line-height: 1.5;
        }

        select.form-control {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3ccircle cx='10' cy='10' r='8' stroke='%236b7280' stroke-width='1.5' fill='none'/%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.25rem;
            padding-right: 2.5rem;
            appearance: none;
        }

        select.form-control:disabled {
            background-color: var(--gray-50);
            color: var(--gray-400);
            cursor: not-allowed;
            opacity: 0.6;
        }

        .character-count {
            font-size: 0.75rem;
            color: var(--gray-400);
            text-align: right;
            margin-top: 0.25rem;
        }

        .character-count.warning {
            color: #f59e0b;
        }

        .character-count.danger {
            color: var(--danger);
        }

        .btn {
            width: 100%;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            color: var(--white);
            background: var(--gray-800);
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn:hover:not(:disabled) {
            background: var(--gray-900);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn svg {
            width: 20px;
            height: 20px;
        }

        .btn-search {
            width: auto;
            padding: 0.875rem 1.5rem;
            background: var(--gray-800);
        }

        .btn-search:hover:not(:disabled) {
            background: var(--gray-900);
        }

        .search-container {
            display: flex;
            gap: 0.5rem;
            align-items: flex-end;
        }

        .search-input {
            flex: 1;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }

        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .spinner {
            width: 24px;
            height: 24px;
            border: 3px solid var(--gray-200);
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--gray-500);
        }

        .empty-state svg {
            width: 48px;
            height: 48px;
            margin: 0 auto 1rem;
            opacity: 0.5;
        }

        .empty-state p {
            margin: 0;
            font-size: 1rem;
        }

        .empty-state .subtitle {
            font-size: 0.875rem;
            margin-top: 0.25rem;
            opacity: 0.8;
        }

        .searchable-dropdown {
            position: relative;
            width: 100%;
        }

        .dropdown-input {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            font-family: inherit;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            background: var(--white);
            transition: var(--transition);
            cursor: pointer;
        }

        .dropdown-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .dropdown-input:hover:not(:focus) {
            border-color: var(--gray-300);
        }

        .dropdown-input.blurred {
            background-color: var(--gray-100);
            color: var(--gray-400);
            opacity: 0.7;
            pointer-events: none;
            cursor: not-allowed;
        }

        .dropdown-placeholder {
            flex: 1;
        }

        .dropdown-arrow {
            width: 16px;
            height: 16px;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-top: none;
            border-radius: 0 0 var(--radius) var(--radius);
            box-shadow: var(--shadow);
            z-index: 10;
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-search {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid var(--gray-200);
            position: relative;
        }

        .dropdown-search input {
            width: 100%;
            padding: 0.5rem;
            padding-right: 2.5rem;
            font-size: 1rem;
            font-family: inherit;
            border: none;
            background: none;
        }

        .dropdown-search .search-icon {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: var(--gray-500);
            pointer-events: none;
        }

        .dropdown-options {
            max-height: 200px;
            overflow-y: auto;
        }

        .dropdown-option {
            padding: 0.875rem 1rem;
            font-size: 1rem;
            font-family: inherit;
            color: var(--gray-900);
            cursor: pointer;
            transition: var(--transition);
        }

        .dropdown-option:hover:not(.selected) {
            background: var(--gray-100);
        }

        .dropdown-option.selected {
            background: var(--primary-light);
            color: var(--primary);
        }

        .dropdown-option.disabled {
            color: var(--gray-400);
            cursor: not-allowed;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 640px) {
            .page-container {
                padding: 1rem;
            }

            .tab-content {
                padding: 1.5rem;
            }

            .main-title {
                font-size: 1.75rem;
            }

            .form-control {
                padding: 0.75rem;
            }

            .search-container {
                flex-direction: column;
                gap: 1rem;
            }

            .btn-search {
                width: 100%;
            }
        }

        /* Focus visible for accessibility */
        .form-control:focus-visible,
        .btn:focus-visible,
        .tab-button:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }
    </style>

    <div class="page-container">
        <div class="main-container">
            <div class="header-section">
                <div class="header-icon">
                    <img src="{{ asset('images/lomboktengah.png') }}" alt="Lombok Tengah"
                        style="width:100px;height:100px;object-fit:contain;border-radius:4px;" />
                </div>
                <h1 class="main-title">Sistem Pengaduan Masyarakat</h1>
                <p class="main-subtitle">Laporkan masalah atau lacak status tiket Anda dengan mudah</p>
            </div>

            <div class="tabs-container">
                <div class="tabs-header">
                    <button class="tab-button active" onclick="switchTab('create')" id="create-tab">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Buat Tiket
                    </button>
                    <button class="tab-button" onclick="switchTab('track')" id="track-tab">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Lacak Tiket
                    </button>
                </div>
                <div class="tab-content active" id="create-content">
                    <div class="section-header">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            <h6>Buat Tiket Baru</h6>
                        </svg>
                    </div>
                    <p class="section-description">Isi formulir di bawah untuk membuat tiket support baru</p>

                    @if (session('success'))
                        <div class="alert alert-success">
                            <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('tickets.store') }}" method="POST" id="ticketForm"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="judul" class="form-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Judul Tiket <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="judul" name="judul"
                                value="{{ old('judul') }}" required maxlength="100"
                                placeholder="Contoh: Masalah login aplikasi">
                            <div class="character-count" id="judul-count">0/100</div>
                        </div>

                        <div class="form-group">
                            <label for="nama_pelapor" class="form-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Nama Lengkap <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="nama_pelapor" name="nama_pelapor"
                                value="{{ old('nama_pelapor') }}" required maxlength="50"
                                placeholder="Masukkan nama lengkap Anda">
                            <div class="character-count" id="nama_pelapor-count">0/50</div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Email <span class="required">*</span>
                            </label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email') }}" required placeholder="contoh@email.com">
                        </div>
                        <!-- Layanan (jenis insiden) - berasal dari master layanan admin -->
                        <div class="form-group">
                            <label for="layanan_id" class="form-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                Jenis Layanan <span class="required">*</span>
                            </label>
                            <select class="form-control" id="layanan_id" name="layanan_id" required>
                                <option value="">Pilih layanan</option>
                                @foreach ($layanan as $l)
                                    <option value="{{ $l->id }}"
                                        {{ old('layanan_id') == $l->id ? 'selected' : '' }}>{{ $l->name }}</option>
                                @endforeach
                            </select>
                            <div id="kategori-inline" style="margin-top:.5rem; color:#6b7280; display:none;">
                                Kategori: <span id="kategori-inline-name"></span>
                            </div>
                        </div>

                        <!-- Kategori Layanan (dinamis berdasarkan layanan) -->
                        <div class="form-group" id="kategori-wrapper">
                            <label for="layanan_category_id" class="form-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M7 12h10M10 18h4" />
                                </svg>
                                Kategori Layanan <span class="required" id="kategori-required"
                                    style="display:none;">*</span>
                            </label>
                            <select class="form-control" id="layanan_category_id" name="layanan_category_id" disabled style="background-color: #f9fafb; color: #6b7280; opacity: 0.6;">
                                <option value="">Pilih jenis layanan terlebih dahulu</option>
                            </select>
                            @error('layanan_category_id')
                                <div class="alert alert-danger" style="margin-top:.5rem;">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="nomor_pelapor" class="form-label">
                                <svg width="16" height="16" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" class="inline-block mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                                Nomor Aktif <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp"
                                value="{{ old('no_hp') }}" required maxlength="15" pattern="[0-9]*"
                                inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                placeholder="Masukkan nomor telepon atau WhatsApp Anda">
                            <div class="character-count" id="no_hp-count">0/15</div>
                        </div>



                        <div class="form-group">
                            <label for="kecamatan" class="form-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                Kecamatan <span class="required">*</span>
                            </label>
                            <div style="position: relative;">
                                <!-- Replaced simple select with searchable dropdown -->
                                <div class="searchable-dropdown" id="kecamatan-dropdown">
                                    <input type="hidden" id="kecamatan" name="kecamatan_id" required>
                                    <div class="dropdown-input" id="kecamatan-input" tabindex="0">
                                        <span class="dropdown-placeholder">Pilih Kecamatan</span>
                                        <svg class="dropdown-arrow" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                    <div class="dropdown-menu" id="kecamatan-menu">
                                        <div class="dropdown-search">
                                            <input type="text" id="kecamatan-search" placeholder="Cari kecamatan..."
                                                autocomplete="off">
                                            <svg class="search-icon" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="dropdown-options" id="kecamatan-options">
                                            @foreach($kecamatans as $kec)
                                                <div class="dropdown-option {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}" data-value="{{ $kec->id }}">{{ $kec->nama }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="loading-overlay" id="kecamatan-loading">
                                    <div class="spinner"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Attachment -->
                        <div class="form-group">
                            <label for="attachment" class="form-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7v10a5 5 0 11-10 0V7a3 3 0 016 0v9a1 1 0 11-2 0V7" />
                                </svg>
                                Lampiran (opsional)
                            </label>
                            <input type="file" class="form-control" id="attachment" name="attachment"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.txt">
                            <small class="text-muted">Maks 5MB. Format: pdf, doc, docx, xls, xlsx, png, jpg, jpeg,
                                txt</small>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Deskripsi Masalah <span class="required">*</span>
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="4" required maxlength="500"
                                placeholder="Jelaskan masalah yang Anda alami secara detail...">{{ old('description') }}</textarea>
                            <div class="character-count" id="description-count">0/500</div>
                        </div>
                        <button type="submit" class="btn" id="submitBtn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span id="submitText">Buat Tiket</span>
                        </button>
                    </form>

                    <script>
                        // Tampilkan input custom jika pilih "Lainnya"
                        (function() {
                            const typeSelect = document.getElementById('layanan_type');
                            const customWrap = document.getElementById('layanan_custom_wrapper');

                            function toggleCustom() {
                                customWrap.style.display = typeSelect.value === 'other' ? 'block' : 'none';
                            }
                            if (typeSelect) {
                                typeSelect.addEventListener('change', toggleCustom);
                                toggleCustom();
                            }
                        })();
                    </script>
                </div>
                <div class="tab-content" id="track-content">
                    <div class="section-header">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            <h6>Lacak Tiket</h6>
                        </svg>
                    </div>

                    <div class="empty-state">
                        <p>Masukkan kode tracking untuk mencari tiket</p>
                        <br>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <form action="{{ route('guest.lacak_tiket') }}" method="GET" id="trackForm">
                        <div class="form-group">
                            <label for="search-code" class="form-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                </svg>
                                Kode Tracking
                            </label>
                            <div class="search-container">
                                <div class="search-input">
                                    <input type="text" class="form-control" id="search-code" name="code_tracking"
                                        value="{{ request('code_tracking') }}" placeholder="Contoh: TK123456789012"
                                        required>
                                </div>
                                <button type="submit" class="btn btn-search" id="trackSubmitBtn">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div id="trackError" class="alert alert-danger"
                        style="display:none; align-items: center; gap: 8px; padding: 10px;">
                        <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="width: 20px; height: 20px; flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            Maaf kode yang anda masukan tidak ada,
                            <button onclick="switchTab('create')"
                                style="color: #ef4444; text-decoration: underline; background: none; border: none; cursor: pointer; font-weight: 500;">
                                Buat tiket?
                            </button>
                        </div>
                    </div>

                    <script>
                        document.getElementById('trackForm').addEventListener('submit', function(e) {
                            e.preventDefault();
                            const code = document.getElementById('search-code').value.trim();
                            if (!code) return;

                            fetch(`{{ route('guest.lacak_tiket') }}?code_tracking=${encodeURIComponent(code)}`, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.exists) {
                                        window.location.href = `{{ url('guest/lihat_tiket') }}/${code}`;
                                    } else {
                                        document.getElementById('trackError').style.display = 'flex';
                                    }
                                })
                                .catch(() => {
                                    document.getElementById('trackError').style.display = 'flex';
                                });
                        });
                    </script>

                    @if (session('error'))
                        <div class="alert alert-danger"
                            style="align-items: center; gap: 8px; padding: 10px; display: flex;">
                            <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="width: 20px; height: 20px; flex-shrink: 0;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                Maaf kode yang anda masukan tidak ada,
                                <button onclick="switchTab('create')"
                                    style="color: #ef4444; text-decoration: underline; background: none; border: none; cursor: pointer; font-weight: 500;">
                                    Buat tiket?
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kabupatenSelect = document.getElementById('kabupaten');
            const kecamatanDropdown = document.getElementById('kecamatan-dropdown');
            const kecamatanInput = document.getElementById('kecamatan-input');
            const kecamatanMenu = document.getElementById('kecamatan-menu');
            const kecamatanSearch = document.getElementById('kecamatan-search');
            const kecamatanOptions = document.getElementById('kecamatan-options');
            const kecamatanHidden = document.getElementById('kecamatan');
            const kecamatanLoading = document.getElementById('kecamatan-loading');

            let allKecamatanData = [];
            let isDropdownOpen = false;

            // Initially blur the kecamatan dropdown
            kecamatanInput.classList.add('blurred');

            // Character counters
            const setupCharacterCounter = (fieldId, maxLength) => {
                const field = document.getElementById(fieldId);
                const counter = document.getElementById(`${fieldId}-count`);

                if (field && counter) {
                    const updateCounter = () => {
                        const length = field.value.length;
                        counter.textContent = `${length}/${maxLength}`;

                        counter.className = 'character-count';
                        if (length > maxLength * 0.8) {
                            counter.classList.add('warning');
                        }
                        if (length >= maxLength) {
                            counter.classList.add('danger');
                        }
                    };

                    field.addEventListener('input', updateCounter);
                    updateCounter(); // Initial count
                }
            };

            setupCharacterCounter('judul', 100);
            setupCharacterCounter('nama_pelapor', 50);
            setupCharacterCounter('description', 500);
            setupCharacterCounter('no_hp', 15);


            // Toggle dropdown
            function toggleDropdown() {
                if (allKecamatanData.length === 0) return;

                isDropdownOpen = !isDropdownOpen;
                kecamatanMenu.classList.toggle('show', isDropdownOpen);

                if (isDropdownOpen) {
                    kecamatanSearch.focus();
                    kecamatanSearch.value = '';
                    filterKecamatan('');
                }
            }

            // Close dropdown
            function closeDropdown() {
                isDropdownOpen = false;
                kecamatanMenu.classList.remove('show');
            }

            // Filter kecamatan based on search
            function filterKecamatan(searchTerm) {
                const filtered = allKecamatanData.filter(kecamatan =>
                    kecamatan.nama.toLowerCase().includes(searchTerm.toLowerCase())
                );

                renderKecamatanOptions(filtered);
            }

            // Render kecamatan options
            function renderKecamatanOptions(kecamatanData) {
                if (kecamatanData.length === 0) {
                    kecamatanOptions.innerHTML =
                        '<div class="dropdown-option disabled">Tidak ada kecamatan ditemukan</div>';
                    return;
                }

                const optionsHTML = kecamatanData.map(kecamatan => {
                    const selected = "{{ old('kecamatan_id') }}" == kecamatan.id ? 'selected' : '';
                    return `<div class="dropdown-option ${selected}" data-value="${kecamatan.id}">${kecamatan.nama}</div>`;
                }).join('');

                kecamatanOptions.innerHTML = optionsHTML;
            }

            // Select kecamatan option
            function selectKecamatan(value, text) {
                kecamatanHidden.value = value;
                kecamatanInput.querySelector('.dropdown-placeholder').textContent = text;
                kecamatanInput.classList.add('has-value');
                closeDropdown();

                // Update selected state
                kecamatanOptions.querySelectorAll('.dropdown-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                kecamatanOptions.querySelector(`[data-value="${value}"]`)?.classList.add('selected');
            }

            // Event listeners
            kecamatanInput.addEventListener('click', toggleDropdown);
            kecamatanInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleDropdown();
                }
            });

            kecamatanSearch.addEventListener('input', function() {
                filterKecamatan(this.value);
            });

            kecamatanSearch.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDropdown();
                }
            });

            kecamatanOptions.addEventListener('click', function(e) {
                const option = e.target.closest('.dropdown-option');
                if (option && !option.classList.contains('disabled')) {
                    const value = option.dataset.value;
                    const text = option.textContent;
                    selectKecamatan(value, text);
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!kecamatanDropdown.contains(e.target)) {
                    closeDropdown();
                }
            });

            // Initialize kecamatan options from server-rendered list
            allKecamatanData = Array.from(document.querySelectorAll('#kecamatan-options .dropdown-option'))
                .map(opt => ({ id: opt.dataset.value, nama: opt.textContent.trim() }));
            kecamatanInput.classList.remove('blurred');

            // Restore old value if exists
            (function() {
                const oldValue = "{{ old('kecamatan_id') }}";
                if (oldValue) {
                    const selected = allKecamatanData.find(k => k.id == oldValue);
                    if (selected) {
                        selectKecamatan(selected.id, selected.nama);
                    }
                }
            })();
        });

        // Tab switching functionality
        function switchTab(tabName) {
            // Remove active class from all tabs and contents
            document.querySelectorAll('.tab-button').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            // Add active class to selected tab and content
            document.getElementById(`${tabName}-tab`).classList.add('active');
            document.getElementById(`${tabName}-content`).classList.add('active');
        }
        // Dynamic Layanan Category dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const layananSelect = document.getElementById('layanan_id');
            const kategoriWrapper = document.getElementById('kategori-wrapper');
            const kategoriSelect = document.getElementById('layanan_category_id');
            const kategoriRequiredMark = document.getElementById('kategori-required');
            const kategoriInline = document.getElementById('kategori-inline');
            const kategoriInlineName = document.getElementById('kategori-inline-name');

            function resetKategori() {
                kategoriSelect.innerHTML = '<option value="">Pilih jenis layanan terlebih dahulu</option>';
                kategoriSelect.value = '';
                kategoriSelect.disabled = true;
                kategoriSelect.style.backgroundColor = '#f9fafb';
                kategoriSelect.style.color = '#6b7280';
                kategoriSelect.style.opacity = '0.6'; // Visual indication of disabled state
                kategoriRequiredMark.style.display = 'none';
                kategoriInline.style.display = 'none';
                kategoriInlineName.textContent = '';
            }

            function loadCategories(layananId) {
                resetKategori();
                if (!layananId) return;

                // Show loading state
                kategoriSelect.innerHTML = '<option value="">Memuat kategori...</option>';
                kategoriSelect.disabled = true;

                fetch(`{{ route('api.layanan.categories', ['layanan' => '___ID___']) }}`.replace('___ID___',
                        layananId))
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return res.json();
                    })
                    .then(list => {
                        if (!Array.isArray(list) || list.length === 0) {
                            kategoriSelect.innerHTML =
                                '<option value="">Tidak ada kategori untuk layanan ini</option>';
                            kategoriSelect.disabled = true;
                            kategoriRequiredMark.style.display = 'none';
                            kategoriInline.style.display = 'none';
                            kategoriInlineName.textContent = '';
                            return;
                        }

                        // Enable the select and populate options
                        kategoriSelect.disabled = false;
                        kategoriSelect.style.backgroundColor = '#ffffff'; // Set background to white when enabled
                        kategoriSelect.style.color = '#000000'; // Set text color to black when enabled
                        kategoriSelect.style.opacity = '1'; // Make it fully visible when enabled
                        kategoriSelect.innerHTML = '<option value="">Pilih kategori</option>';
                        kategoriRequiredMark.style.display = 'inline';

                        for (const item of list) {
                            const opt = document.createElement('option');
                            opt.value = item.id;
                            opt.textContent = item.name;
                            kategoriSelect.appendChild(opt);
                        }

                        // Restore old value if exists
                        const oldVal = "{{ old('layanan_category_id') }}";
                        if (oldVal) {
                            kategoriSelect.value = oldVal;
                            const selectedOption = Array.from(kategoriSelect.options).find(o => o.value == oldVal);
                            if (selectedOption) {
                                kategoriInline.style.display = 'block';
                                kategoriInlineName.textContent = selectedOption.textContent;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error loading categories:', error);
                        kategoriSelect.innerHTML = '<option value="">Error memuat kategori</option>';
                        kategoriSelect.disabled = true;
                        kategoriSelect.style.backgroundColor = '#f9fafb';
                        kategoriSelect.style.color = '#6b7280';
                        kategoriSelect.style.opacity = '0.6';
                        kategoriRequiredMark.style.display = 'none';
                        kategoriInline.style.display = 'none';
                        kategoriInlineName.textContent = '';
                    });
            }

            layananSelect.addEventListener('change', function() {
                loadCategories(this.value);
            });

            kategoriSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                if (this.value) {
                    kategoriInline.style.display = '';
                    kategoriInlineName.textContent = selected.textContent;
                } else {
                    kategoriInline.style.display = 'none';
                    kategoriInlineName.textContent = '';
                }
            });

            // init on page load if layanan already selected
            if (layananSelect.value) {
                loadCategories(layananSelect.value);
            }
        });
    </script>

@endsection
