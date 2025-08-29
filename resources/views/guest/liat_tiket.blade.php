@extends('layouts.app')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #1a1a1a;
            line-height: 1.6;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Header Card */
        .ticket-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 20px;
            position: relative;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.2);
        }

        .ticket-id {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .ticket-title {
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .ticket-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
        }

        .status-badge {
            position: absolute;
            top: 30px;
            right: 30px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .status-pending {
            background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);
            color: white;
        }

        .status-accepted {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .status-rejected {
            background: linear-gradient(135deg, #fc466b 0%, #3f5efb 100%);
            color: white;
        }

        .status-completed {
            background: linear-gradient(135deg, #4caf50 0%, #66bb6a 100%);
            color: white;
        }

        /* Main Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
        }

        .main-content {
            background: #ffffff;
            border: 1px solid #e8eaf6;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.08);
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Section Styling */
        .section {
            padding: 25px;
            border-bottom: 1px solid #f3e5f5;
        }

        .section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #4a148c;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title svg {
            width: 20px;
            height: 20px;
            color: #667eea;
        }

        /* FIXED: Reporter Info Layout */
        .reporter-info {
            display: grid;
            grid-template-columns: 60px 1fr;
            gap: 20px;
            background: linear-gradient(135deg, #f8f9ff 0%, #f3e5f5 100%);
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e8eaf6;
            align-items: start;
        }

        .reporter-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            flex-shrink: 0;
        }

        .reporter-details {
            min-width: 0; /* Prevents overflow */
        }

        .reporter-details h3 {
            font-size: 16px;
            font-weight: 600;
            color: #4a148c;
            margin-bottom: 12px;
        }

        .reporter-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 8px;
            font-size: 14px;
            color: #6a1b9a;
        }

        .reporter-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 0;
        }

        .reporter-meta-item svg {
            width: 14px;
            height: 14px;
            color: #667eea;
            flex-shrink: 0;
        }

        .reporter-meta-item span {
            word-break: break-word;
        }

        /* Description */
        .description-content {
            background: linear-gradient(135deg, #f8f9ff 0%, #f3e5f5 100%);
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e8eaf6;
            color: #333333;
            line-height: 1.7;
        }

        /* Sidebar Cards */
        .sidebar-card {
            background: #ffffff;
            border: 1px solid #e8eaf6;
            border-radius: 12px;
            padding: 20px;
            height: 100%;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.08);
        }

        .sidebar-title {
            font-size: 16px;
            font-weight: 600;
            color: #4a148c;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-title svg {
            width: 16px;
            height: 16px;
            color: #667eea;
        }

        /* Info Items */
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f3e5f5;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 14px;
            color: #6a1b9a;
            font-weight: 500;
        }

        .info-value {
            font-size: 14px;
            color: #4a148c;
            font-weight: 600;
            text-align: right;
        }

        .tracking-code {
            font-family: 'Monaco', monospace;
            background: linear-gradient(135deg, #e8eaf6 0%, #f3e5f5 100%);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            border: 1px solid #d1c4e9;
        }

        /* FIXED: Processing Time Display */
        .processing-time {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            border: 1px solid #a5d6a7;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #2e7d32;
        }

        .processing-time svg {
            width: 14px;
            height: 14px;
            color: #4caf50;
        }

        /* FIXED: Timeline Styling */
        .timeline {
            position: relative;
        }

        .timeline-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px 0;
            position: relative;
            min-height: 70px;
        }

        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 15px;
            top: 35px;
            width: 2px;
            height: calc(100% - 15px);
            background: #e8eaf6;
            z-index: 0;
        }

        /* FIXED: Timeline Dots - Base Styling */
        .timeline-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            flex-shrink: 0;
            z-index: 1;
            background: #ffffff;
            border: 2px solid #e0e0e0;
            color: #9e9e9e;
            position: relative;
        }

        /* FIXED: Completed Timeline Dot */
        .timeline-dot.completed {
            background: linear-gradient(135deg, #4caf50 0%, #66bb6a 100%);
            border-color: #4caf50;
            color: white;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }

        /* FIXED: Active Timeline Dot */
        .timeline-dot.active {
            background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%);
            border-color: #ff9800;
            color: white;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
            animation: pulse 2s infinite;
        }

        /* FIXED: Rejected Timeline Dot */
        .timeline-dot.rejected {
            background: linear-gradient(135deg, #f44336 0%, #e57373 100%);
            border-color: #f44336;
            color: white;
            box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
            }
            50% {
                box-shadow: 0 4px 12px rgba(255, 152, 0, 0.6);
            }
            100% {
                box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
            }
        }

        /* FIXED: Timeline Content */
        .timeline-content {
            flex: 1;
            padding-top: 2px;
        }

        .timeline-title {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .timeline-time {
            font-size: 12px;
            color: #666666;
            line-height: 1.4;
        }

        /* FIXED: Timeline Item States */
        .timeline-item.completed .timeline-title {
            color: #2e7d32;
        }

        .timeline-item.active .timeline-title {
            color: #ef6c00;
        }

        .timeline-item.rejected .timeline-title {
            color: #c62828;
        }

        .timeline-item.pending .timeline-title {
            color: #757575;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            padding: 12px 16px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn svg {
            width: 16px;
            height: 16px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #ffffff;
            color: #667eea;
            border-color: #e8eaf6;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #f3e5f5 100%);
            border-color: #d1c4e9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .content-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .ticket-header {
                padding: 20px;
                text-align: center;
            }

            .status-badge {
                position: static;
                margin-top: 15px;
                display: inline-block;
            }

            .section {
                padding: 20px;
            }

            .reporter-info {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 15px;
            }

            .reporter-meta {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .reporter-meta-item {
                justify-content: center;
            }
        }

        /* Print Styles */
        @media print {
            body {
                padding: 0;
            }

            .sidebar {
                display: none;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .ticket-header,
            .main-content {
                box-shadow: none;
                border: 1px solid #cccccc;
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 40px;
            background: #ffffff;
            border: 1px solid #e8eaf6;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.08);
        }

        .empty-state svg {
            width: 60px;
            height: 60px;
            color: #d1c4e9;
            margin-bottom: 20px;
        }

        .empty-state h2 {
            font-size: 20px;
            font-weight: 600;
            color: #4a148c;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #6a1b9a;
            margin-bottom: 20px;
        }
    </style>

    <br><br><br>

    @if (!$ticket)
        <div class="container">
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <h2>Tiket Tidak Ditemukan</h2>
                <p>Maaf, tiket yang Anda cari tidak dapat ditemukan dalam sistem.</p>
                <a href="/guest/lacak_tiket" class="btn btn-primary">Cari Tiket Lain</a>
            </div>
        </div>
    @else
        <div class="container">
            <!-- Header -->
            <div class="ticket-header">
                <div class="ticket-id">Tiket #{{ $ticket->code_tracking }}</div>
                <h1 class="ticket-title">{{ $ticket->judul }}</h1>
                <p class="ticket-subtitle">Laporan Keamanan Siber</p>

                @php
                    $statusClass = match ($ticket->status) {
                        'pending' => 'status-pending',
                        'diterima/approved' => 'status-accepted',
                        'selesai/completed' => 'status-completed',
                        'ditolak/rejected' => 'status-rejected',
                        default => 'status-pending',
                    };
                @endphp
                <div class="status-badge {{ $statusClass }}">
                    @switch($ticket->status)
                        @case('pending')
                            Menunggu Review
                            @break
                        @case('diterima/approved')
                            Sedang diproses
                            @break
                        @case('selesai/completed')
                            Selesai
                            @break
                        @case('ditolak/rejected')
                            Ditolak
                            @break
                        @default
                            {{ ucfirst($ticket->status) }}
                    @endswitch
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Main Content -->
                <div class="main-content">
                    <!-- Reporter Info -->
                    <div class="section">
                        <h2 class="section-title">
                            <svg fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                            Informasi Pelapor
                        </h2>
                        <div class="reporter-info">
                            <div class="reporter-avatar">
                                {{ strtoupper(substr($ticket->nama_pelapor, 0, 1)) }}
                            </div>
                            <div class="reporter-details">
                                <h3>{{ $ticket->nama_pelapor }}</h3>
                                <div class="reporter-meta">
                                    <div class="reporter-meta-item">
                                        <svg fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                        </svg>
                                        <span>{{ $ticket->email }}</span>
                                    </div>
                                    @if ($ticket->no_hp)
                                        <div class="reporter-meta-item">
                                            <svg fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                                            </svg>
                                            <span>{{ $ticket->no_hp }}</span>
                                        </div>
                                    @endif
                                    @if ($ticket->kabupaten)
                                        <div class="reporter-meta-item">
                                            <svg fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                                            </svg>
                                            <span>{{ $ticket->kabupaten->nama }}</span>
                                        </div>
                                    @endif
                                    @if ($ticket->kecamatan)
                                        <div class="reporter-meta-item">
                                            <svg fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                                            </svg>
                                            <span>{{ $ticket->kecamatan->nama }}</span>
                                        </div>
                                    @endif
                                    <div class="reporter-meta-item">
                                        <svg fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z" />
                                            <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                                        </svg>
                                        <span>
                                            @if ($ticket->created_at)
                                                @php
                                                    $localTime = \Carbon\Carbon::parse($ticket->created_at)->setTimezone('Asia/Makassar');
                                                @endphp
                                                {{ $localTime->format('d M Y, H:i') }} WITA
                                            @else
                                                Waktu tidak tersedia
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="section">
                        <h2 class="section-title">
                            <svg fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 6h-2v9H6v2c0 .55.45 1 1 1h11l4 4V7c0-.55-.45-1-1-1zm-4 6V3c0-.55-.45-1-1-1H3c-.55 0-1 .45-1 1v14l4-4h11c.55 0 1-.45 1-1z" />
                            </svg>
                            Deskripsi Masalah
                        </h2>
                        <div class="description-content">
                            {{ $ticket->description }}
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Ticket Details -->
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">
                            <svg fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h8c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-5 2h6v2H9V4zm0 4h6v2H9V8zm0 4h6v2H9v-2z" />
                            </svg>
                            Detail Tiket
                        </h3>
                        <div class="info-item">
                            <span class="info-label">Kode Tracking</span>
                            <span class="info-value tracking-code">{{ $ticket->code_tracking }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                <span class="status-badge {{ $statusClass }}" style="position: static; padding: 4px 8px; font-size: 10px;">
                                    @switch($ticket->status)
                                        @case('pending')
                                            Menunggu
                                            @break
                                        @case('diterima/approved')
                                            Sedang diproses, Tunggu yaa
                                            @break
                                        @case('selesai/completed')
                                            Selesai
                                            @break
                                        @case('ditolak/rejected')
                                            Ditolak
                                            @break
                                        @default
                                            {{ ucfirst($ticket->status) }}
                                    @endswitch
                                </span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tanggal Dibuat</span>
                            <span class="info-value">
                                @if ($ticket->created_at)
                                    @php
                                        $localTime = \Carbon\Carbon::parse($ticket->created_at)->setTimezone('Asia/Makassar');
                                    @endphp
                                    {{ $localTime->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Waktu</span>
                            <span class="info-value">
                                @if ($ticket->created_at)
                                    @php
                                        $localTime = \Carbon\Carbon::parse($ticket->created_at)->setTimezone('Asia/Makassar');
                                    @endphp
                                    {{ $localTime->format('H:i') }} WITA
                                @else
                                    -
                                @endif
                            </span>
                        </div>

                        @if ($ticket->status === 'selesai/completed' && $ticket->resolved_at && $ticket->accepted_at)
                            @php
                                $processingTime = \Carbon\Carbon::parse($ticket->accepted_at)->diffInMinutes(\Carbon\Carbon::parse($ticket->resolved_at));
                                $hours = floor($processingTime / 60);
                                $minutes = $processingTime % 60;
                            @endphp
                            <div class="info-item">
                                <span class="info-label">Waktu Pengerjaan</span>
                                <span class="info-value">
                                    <div class="processing-time">
                                        <svg fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                                            <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                                        </svg>
                                        @if ($hours > 0)
                                            {{ $hours }}j {{ $minutes }}m
                                        @else
                                            {{ $minutes }}m
                                        @endif
                                    </div>
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Progress Timeline -->
                    <div class="sidebar-card timeline-card">
                        <h3 class="sidebar-title">
                            <svg fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z" />
                            </svg>
                            Status Progress
                        </h3>
                        <div class="timeline">
                            <!-- Step 1: Tiket Dibuat (Always completed) -->
                            <div class="timeline-item completed">
                                <div class="timeline-dot completed">✓</div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Tiket Sudah Dibuat</div>
                                    <div class="timeline-time">
                                        @if ($ticket->created_at)
                                            @php
                                                $localTime = \Carbon\Carbon::parse($ticket->created_at)->setTimezone('Asia/Makassar');
                                            @endphp
                                            {{ $localTime->format('d M Y, H:i') }} WITA
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Sedang Diproses -->
                            @if ($ticket->status === 'pending')
                                <div class="timeline-item active">
                                    <div class="timeline-dot active">⏳</div>
                                    <div class="timeline-content">
                                        <div class="timeline-title">Sedang Diproses</div>
                                        <div class="timeline-time">Belum diproses</div>
                                    </div>
                                </div>
                            @elseif (in_array($ticket->status, ['diterima/approved', 'selesai/completed']))
                                <div class="timeline-item completed">
                                    <div class="timeline-dot completed">✓</div>
                                    <div class="timeline-content">
                                        <div class="timeline-title">Sedang Diproses</div>
                                        <div class="timeline-time">Tiket Sedang diproses oleh tim</div>
                                    </div>
                                </div>
                            @elseif ($ticket->status === 'ditolak/rejected')
                                <div class="timeline-item rejected">
                                    <div class="timeline-dot rejected">✗</div>
                                    <div class="timeline-content">
                                        <div class="timeline-title">Ditolak</div>
                                        <div class="timeline-time">Tiket tidak memenuhi syarat</div>
                                    </div>
                                </div>
                            @else
                                <div class="timeline-item pending">
                                    <div class="timeline-dot">2</div>
                                    <div class="timeline-content">
                                        <div class="timeline-title">Sedang Diproses</div>
                                        <div class="timeline-time">Belum diproses</div>
                                    </div>
                                </div>
                            @endif

                            <!-- Step 3: Keputusan Final (Only if not rejected at step 2) -->
                            @if ($ticket->status !== 'ditolak/rejected')
                                @if ($ticket->status === 'selesai/completed')
                                    <div class="timeline-item completed">
                                        <div class="timeline-dot completed">✓</div>
                                        <div class="timeline-content">
                                            <div class="timeline-title">Menunggu Keputusan</div>
                                            <div class="timeline-time">
                                                @if ($ticket->resolved_at)
                                                    @php
                                                        $resolvedTime = \Carbon\Carbon::parse($ticket->resolved_at)->setTimezone('Asia/Makassar');
                                                    @endphp
                                                    Selesai pada {{ $resolvedTime->format('d M Y, H:i') }} WITA
                                                @else
                                                    Telah diselesaikan
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($ticket->status === 'diterima/approved')
                                    <div class="timeline-item active">
                                        <div class="timeline-dot active">⏳</div>
                                        <div class="timeline-content">
                                            <div class="timeline-title">Menunggu Keputusan</div>
                                            <div class="timeline-time">Menunggu penyelesaian akhir</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="timeline-item pending">
                                        <div class="timeline-dot">3</div>
                                        <div class="timeline-content">
                                            <div class="timeline-title">Menunggu Keputusan</div>
                                            <div class="timeline-time">Menunggu review dan keputusan admin</div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">
                            <svg fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                            </svg>
                            Tindakan
                        </h3>
                        <div class="action-buttons">
                            <button class="btn btn-primary" onclick="window.print()">
                                <svg fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z" />
                                </svg>
                                Cetak Tiket
                            </button>
                            <a href="/guest/create_tiket" class="btn btn-secondary">
                                <svg fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                                </svg>
                                Buat Tiket Baru
                            </a>
                            <a href="/guest/lacak_tiket" class="btn btn-secondary">
                                <svg fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                                </svg>
                                Lacak Tiket Lain
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
