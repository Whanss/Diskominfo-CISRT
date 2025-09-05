@extends('layouts.admin')

@section('breadcrumb', 'Process Tickets')
@section('page-title', 'Process Approved Tickets')

@section('content')
    <style>
        .process-dashboard {
            min-height: 100vh;
            background: #f8fafc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .process-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        .process-header {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .process-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }

        .stat-label {
            font-size: 14px;
            color: #64748b;
            margin-top: 4px;
        }

        /* Tab Navigation */
        .tab-navigation {
            background: white;
            border-radius: 12px 12px 0 0;
            padding: 0;
            margin-bottom: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            border-bottom: 1px solid #e2e8f0;
        }

        .tab-btn {
            padding: 16px 24px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            color: #64748b;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
            background: #f8fafc;
        }

        .tab-btn:hover {
            color: #2563eb;
            background: #f8fafc;
        }

        /* Tab Content */
        .tab-content {
            background: white;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            min-height: 500px;
        }

        .tab-pane {
            display: none;
            padding: 24px;
        }

        .tab-pane.active {
            display: block;
        }

        /* Ticket List Styles */
        .ticket-list {
            background: transparent;
            border-radius: 0;
            overflow: hidden;
            box-shadow: none;
        }

        .ticket-item {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .ticket-info h4 {
            margin: 0 0 8px 0;
            color: #1e293b;
        }

        .ticket-meta {
            font-size: 14px;
            color: #64748b;
        }

        .timer-container {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .timer {
            font-family: 'SF Mono', Monaco, monospace;
            font-size: 18px;
            font-weight: bold;
            color: #059669;
            background: #f0fdf4;
            padding: 8px 12px;
            border-radius: 6px;
        }

        .timer.stopped {
            color: #dc2626;
            background: #fef2f2;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-start {
            background: #10b981;
            color: white;
        }

        .btn-pause {
            background: #f59e0b;
            color: white;
        }

        .btn-complete {
            background: #3b82f6;
            color: white;
        }

        .btn-view {
            background: #6b7280;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .work-session-info {
            background: #f8fafc;
            padding: 12px;
            border-radius: 6px;
            font-size: 14px;
            color: #475569;
            margin-top: 8px;
        }

        /* Calendar Styles */
        .calendar-container {
            display: grid;
            /* Widen sidebar to avoid cramped cards */
            grid-template-columns: 2fr 310px;
            gap: 24px;
            min-height: 600px;
        }

        .calendar-main {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
        }

        .calendar-sidebar {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            background: #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            min-height: 400px;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .calendar-nav {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .calendar-nav button {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        .calendar-nav button:hover {
            background: #2563eb;
        }

        .calendar-month {
            font-size: 20px;
            font-weight: bold;
            color: #1e293b;
            margin: 0 16px;
        }

        .calendar-day-header {
            background: #374151;
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .calendar-day {
            background: white;
            min-height: 80px;
            padding: 8px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            display: flex;
            flex-direction: column;
            border: 1px solid #e5e7eb;
        }

        .calendar-day:hover {
            background: #f1f5f9;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .calendar-day.other-month {
            background: #f8fafc;
            color: #9ca3af;
        }

        .calendar-day.today {
            background: #dbeafe;
            border: 2px solid #3b82f6;
            font-weight: bold;
        }

        .calendar-day.has-work {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
        }

        .calendar-day.selected {
            background: #dbeafe !important;
            border: 2px solid #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .day-number {
            font-weight: 600;
            margin-bottom: 4px;
            font-size: 16px;
            color: #1f2937;
        }

        .calendar-day.other-month .day-number {
            color: #9ca3af;
        }

        .calendar-day.today .day-number {
            color: #1e40af;
        }

        .day-work-info {
            font-size: 10px;
            color: #059669;
            background: #dcfce7;
            padding: 2px 4px;
            border-radius: 3px;
            margin-bottom: 2px;
            text-align: center;
            font-weight: 500;
        }

        .sidebar-section {
            margin-bottom: 24px;
        }

        .sidebar-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 12px;
        }

        .day-detail {
            background: white;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .day-detail-header {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .work-session-item {
            background: #f8fafc;
            padding: 12px 12px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 13px;
            border-left: 4px solid #10b981;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 6px 12px;
        }

        .work-session-item .session-time {
            grid-column: 1 / 2;
        }

        .work-session-item .badge-status {
            grid-column: 2 / 3;
            justify-self: end;
            white-space: nowrap;
        }

        .work-session-item .session-ticket {
            grid-column: 1 / -1;
            word-break: break-word;
        }

        .work-session-item .session-duration {
            grid-column: 1 / -1;
            font-size: 12px;
            color: #9ca3af;
        }

        .work-session-item .session-subtime {
            grid-column: 1 / -1;
            font-size: 12px;
            color: #0000006f;
        }

        .session-time {
            color: #059669;
            font-weight: 600;
            font-size: 14px;
        }

        .session-ticket {
            color: #64748b;
            margin-top: 2px;
        }

        @media (max-width: 768px) {
            .calendar-container {
                grid-template-columns: 1fr;
                height: auto;
            }

            .calendar-sidebar {
                order: -1;
            }

            .calendar-grid {
                min-height: 300px;
            }

            .calendar-day {
                min-height: 60px;
                padding: 4px;
            }
        }

        /* Search bar */
        .search-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .search-input {
            width: 280px;
            max-width: 100%;
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
        }

        /* Minimal work indicator dot in calendar */
        .work-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            /* green */
            border-radius: 50%;
            position: absolute;
            bottom: 6px;
            right: 6px;
        }

        /* Loading indicator */
        .calendar-loading {
            display: none;
            background: #eef2ff;
            color: #4338ca;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .calendar-loading.active {
            display: block;
        }
    </style>

    <div class="process-dashboard">
        <div class="process-container">
            <div class="process-header">
                <h1>Process Approved Tickets</h1>
                <p>Manage and track tickets that have been approved for processing</p>
            </div>

            <div class="process-stats">
                <div class="stat-card">
                    <div class="stat-number">{{ $acceptedTickets->count() }}</div>
                    <div class="stat-label">Approved Tickets</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $acceptedTickets->where('is_processing', true)->count() }}</div>
                    <div class="stat-label">Currently Processing</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $completedToday->count() }}</div>
                    <div class="stat-label">Completed Today</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ gmdate('H:i:s', $totalWorkTimeToday) }}</div>
                    <div class="stat-label">Total Work Time Today</div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="tab-navigation">
                <button class="tab-btn active" onclick="switchTab('tickets')">
                    <i class="fas fa-list"></i> Daftar Tiket
                </button>
                <button class="tab-btn" onclick="switchTab('calendar')">
                    <i class="fas fa-calendar-alt"></i> Kalender Kerja
                </button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Tickets Tab -->
                <div id="tickets-tab" class="tab-pane active">
                    <!-- Search and Filter Bar -->
                    <div class="search-bar">
                        <form method="GET" action="{{ route('admin.tickets.process') }}"
                            style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-search" style="color: #64748b;"></i>
                                <input type="text" name="search" class="search-input" placeholder="Cari tiket..."
                                    value="{{ request('search') }}">
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <label for="layanan_filter"
                                    style="font-size: 14px; color: #64748b; margin: 0;">Layanan:</label>
                                <select name="layanan_id" id="layanan_filter" class="search-input" style="width: 200px;">
                                    <option value="">Semua Layanan</option>
                                    @foreach ($layananList as $layanan)
                                        <option value="{{ $layanan->id }}"
                                            {{ request('layanan_id') == $layanan->id ? 'selected' : '' }}>
                                            {{ $layanan->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            @if (request('search') || request('layanan_id'))
                                <a href="{{ route('admin.tickets.process') }}" class="btn btn-secondary"
                                    style="padding: 8px 16px;">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            @endif
                        </form>
                        <div style="font-size: 14px; color: #64748b;">
                            @if ($acceptedTickets->total() > 0)
                                Menampilkan {{ $acceptedTickets->firstItem() }} - {{ $acceptedTickets->lastItem() }} dari
                                {{ $acceptedTickets->total() }} tiket
                            @else
                                Tidak ada tiket ditemukan
                            @endif
                        </div>
                    </div>

                    <div class="ticket-list">
                        @if ($acceptedTickets->isEmpty())
                            <div class="empty-state">
                                <i class="fas fa-inbox" style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px;"></i>
                                <h3>Tidak ada tiket yang disetujui untuk diproses</h3>
                                <p>Saat ini tidak ada tiket yang disetujui menunggu untuk diproses.</p>
                            </div>
                        @else
                            @foreach ($acceptedTickets as $ticket)
                                <div class="ticket-item" data-ticket-id="{{ $ticket->id }}">
                                    <div class="ticket-info">
                                        <h4>{{ $ticket->judul ?? 'Tanpa Judul' }}</h4>
                                        <div class="ticket-meta">
                                            <span><strong>ID:</strong> {{ $ticket->code_tracking }}</span> |
                                            <span><strong>Pelapor:</strong> {{ $ticket->nama_pelapor }}</span> |
                                            @if ($ticket->layanan)
                                                <span><strong>Layanan:</strong> {{ $ticket->layanan->name }}</span> |
                                            @endif
                                            <span><strong>Diterima:</strong>
                                                {{ $ticket->accepted_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="work-session-info">
                                            <div><strong>Rentang Waktu:</strong>
                                                {{ optional($ticket->accepted_at)->locale('id')->translatedFormat('d M Y H:i') }}
                                                —
                                                @if (!empty($ticket->resolved_at))
                                                    {{ optional($ticket->resolved_at)->locale('id')->translatedFormat('d M Y H:i') }}
                                                @else
                                                    <em>Sedang diproses</em>
                                                @endif
                                            </div>
                                            <div><strong>Durasi:</strong> {{ $ticket->formatted_processing_time }}</div>
                                        </div>
                                    </div>

                                    <div class="timer-container">
                                        <div class="timer @if ($ticket->is_processing) {{ 'active' }}@else{{ 'stopped' }} @endif"
                                            id="timer-{{ $ticket->id }}"
                                            data-accepted-at="{{ $ticket->accepted_at->toISOString() }}">
                                            {{ $ticket->formatted_processing_time }}
                                        </div>

                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-complete" data-bs-toggle="modal"
                                                data-bs-target="#completeModal"
                                                data-action="{{ route('admin.tickets.complete', $ticket) }}"
                                                data-title="{{ $ticket->judul ?? 'Tanpa Judul' }}"
                                                data-code="{{ $ticket->code_tracking }}">
                                                <i class="fas fa-check"></i> Selesai
                                            </button>

                                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-view">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Pagination -->
                    @if ($acceptedTickets->hasPages())
                        <div
                            style="margin-top: 24px; padding: 20px; border-top: 1px solid #e2e8f0; background: #fafafa; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                            <div style="color: #64748b; font-size: 14px;">
                                Menampilkan {{ $acceptedTickets->firstItem() }} sampai {{ $acceptedTickets->lastItem() }}
                                dari {{ $acceptedTickets->total() }} tiket
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                {{-- Previous Page Link --}}
                                @if ($acceptedTickets->onFirstPage())
                                    <span class="btn" style="background: #e5e7eb; color: #9ca3af; cursor: not-allowed;">
                                        <i class="fas fa-chevron-left"></i> Sebelumnya
                                    </span>
                                @else
                                    <a href="{{ $acceptedTickets->appends(request()->query())->previousPageUrl() }}"
                                        class="btn" style="background: #3b82f6; color: white;">
                                        <i class="fas fa-chevron-left"></i> Sebelumnya
                                    </a>
                                @endif

                                {{-- Page Numbers --}}
                                <div style="display: flex; align-items: center; gap: 4px;">
                                    @foreach ($acceptedTickets->appends(request()->query())->getUrlRange(1, $acceptedTickets->lastPage()) as $page => $url)
                                        @if ($page == $acceptedTickets->currentPage())
                                            <span class="btn"
                                                style="background: #3b82f6; color: white; font-weight: 600;">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="btn"
                                                style="background: #e5e7eb; color: #374151;">{{ $page }}</a>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- Next Page Link --}}
                                @if ($acceptedTickets->hasMorePages())
                                    <a href="{{ $acceptedTickets->appends(request()->query())->nextPageUrl() }}"
                                        class="btn" style="background: #3b82f6; color: white;">
                                        Selanjutnya <i class="fas fa-chevron-right"></i>
                                    </a>
                                @else
                                    <span class="btn"
                                        style="background: #e5e7eb; color: #9ca3af; cursor: not-allowed;">
                                        Selanjutnya <i class="fas fa-chevron-right"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Calendar Tab -->
                <div id="calendar-tab" class="tab-pane">
                    <div class="calendar-container">
                        <div class="calendar-main">
                            <div class="calendar-header">
                                <div class="calendar-nav">
                                    <button onclick="changeMonth(-1)">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <span class="calendar-month" id="current-month"></span>
                                    <button onclick="changeMonth(1)">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                                <div style="display:flex; gap:12px; align-items:center;">
                                    <div id="calendar-loading" class="calendar-loading"><i
                                            class="fas fa-spinner fa-spin"></i> Memuat data kalender...</div>
                                    <div id="calendar-range"
                                        style="font-size: 12px; color: #64748b; white-space: nowrap;">
                                        Rentang: -
                                    </div>
                                    <button onclick="goToToday()" class="btn"
                                        style="background: #10b981; color: white;">
                                        <i class="fas fa-calendar-day"></i> Hari Ini
                                    </button>
                                </div>
                            </div>
                            <div class="calendar-grid" id="calendar-grid">
                                <!-- Calendar will be generated by JavaScript -->
                            </div>
                        </div>
                        <div class="calendar-sidebar">
                            <div class="sidebar-section">
                                <div class="sidebar-title">Hari Terpilih</div>
                                <div id="selected-day-info">
                                    <p style="color: #64748b; font-style: italic;">Klik pada tanggal untuk melihat detail
                                        kerja</p>
                                </div>
                            </div>
                            <div class="sidebar-section">
                                <div class="sidebar-title">Ringkasan Bulan Ini</div>
                                <div class="day-detail">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span>Total Hari Kerja:</span>
                                        <span id="month-work-days">-</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span>Total Waktu:</span>
                                        <span id="month-total-hours">-</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span>Rata-rata/Hari:</span>
                                        <span id="month-avg-hours">-</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span>Tiket Selesai:</span>
                                        <span id="month-tickets">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Ticket Modal -->
    <div class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="completeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="completeModalLabel">Selesaikan Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="completeForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div style="margin-bottom: 10px; font-size: 14px; color: #64748b;">
                            <div><strong>Ticket:</strong> <span id="complete-ticket-title">-</span></div>
                            <div><strong>Kode:</strong> <span id="complete-ticket-code">-</span></div>
                        </div>
                        <div class="form-group">
                            <label for="resolution_notes"><strong>Catatan Penanganan</strong></label>
                            <textarea class="form-control" id="resolution_notes" name="resolution_notes" rows="4"
                                placeholder="Jelaskan tindakan dan hasil penanganan" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-complete"><i class="fas fa-check"></i> Tandai
                            Selesai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentDate = new Date();

        // Hook up modal with data attributes
        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('[data-bs-target="#completeModal"]');
            if (!trigger) return;
            const action = trigger.getAttribute('data-action');
            const title = trigger.getAttribute('data-title');
            const code = trigger.getAttribute('data-code');

            const form = document.getElementById('completeForm');
            form.setAttribute('action', action);
            document.getElementById('complete-ticket-title').textContent = title || '-';
            document.getElementById('complete-ticket-code').textContent = code || '-';

            // reset textarea on open
            const textarea = document.getElementById('resolution_notes');
            textarea.value = '';
            textarea.focus({
                preventScroll: true
            });
        });

        let workData = {}; // Will store real work session data from API

        // Helper: format seconds to "Xj Ym Zs" or "Xm Ys" or "Xs"
        function formatDuration(totalSeconds) {
            const s = Math.max(0, Math.round(Number(totalSeconds || 0)));
            const hours = Math.floor(s / 3600);
            const minutes = Math.floor((s % 3600) / 60);
            const seconds = s % 60;
            if (hours > 0) return `${hours}j ${minutes}m ${seconds}s`;
            if (minutes > 0) return `${minutes}m ${seconds}s`;
            return `${seconds}s`;
        }


        document.addEventListener('DOMContentLoaded', function() {
            // Ticket search and counter
            function filterTickets() {
                const q = (document.getElementById('ticket-search')?.value || '').toLowerCase().trim();
                const items = document.querySelectorAll('.ticket-item');
                let visible = 0;
                items.forEach(item => {
                    const text = item.innerText.toLowerCase();
                    const match = !q || text.includes(q);
                    item.style.display = match ? '' : 'none';
                    if (match) visible++;
                });
                const counter = document.getElementById('ticket-counter');
                if (counter) {
                    counter.textContent = q ? `${visible} tiket cocok` : '';
                }
            }

            // Real-time timer update for processing tickets
            function updateTimers() {
                const timerElements = document.querySelectorAll('.timer.active');

                timerElements.forEach(timer => {
                    const ticketId = timer.id.replace('timer-', '');
                    const acceptedAt = timer.getAttribute('data-accepted-at');

                    if (acceptedAt) {
                        const acceptedTime = new Date(acceptedAt).getTime();
                        const currentTime = new Date().getTime();
                        const elapsedSeconds = Math.floor((currentTime - acceptedTime) / 1000);

                        // Format time as HH:MM:SS
                        const hours = Math.floor(elapsedSeconds / 3600);
                        const minutes = Math.floor((elapsedSeconds % 3600) / 60);
                        const seconds = elapsedSeconds % 60;

                        timer.textContent =
                            hours.toString().padStart(2, '0') + ':' +
                            minutes.toString().padStart(2, '0') + ':' +
                            seconds.toString().padStart(2, '0');
                    }
                });
            }

            // Update timers immediately and then every second
            updateTimers();
            setInterval(updateTimers, 1000);

            // Initialize calendar
            generateCalendar();
            loadWorkData();
            updateCalendarRangeLabel();
        });

        function switchTab(tabName) {
            // Remove active class from all tabs and buttons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

            // Add active class to selected tab and button
            const btn = document.querySelector(`button[onclick="switchTab('${tabName}')"]`);
            if (btn) btn.classList.add('active');
            const pane = document.getElementById(`${tabName}-tab`);
            if (pane) pane.classList.add('active');

            // If switching to calendar, refresh the calendar
            if (tabName === 'calendar') {
                generateCalendar();
                loadWorkData();
            }
        }

        function generateCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            // Update month display - Indonesian month names
            const monthNames = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            document.getElementById('current-month').textContent = `${monthNames[month]} ${year}`;

            // Get first day of month and number of days
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDayOfWeek = firstDay.getDay();

            // Generate calendar grid
            const calendarGrid = document.getElementById('calendar-grid');
            calendarGrid.innerHTML = '';

            // Add day headers - Indonesian day names
            const dayHeaders = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            dayHeaders.forEach(day => {
                const dayHeader = document.createElement('div');
                dayHeader.className = 'calendar-day-header';
                dayHeader.textContent = day;
                calendarGrid.appendChild(dayHeader);
            });

            // Add empty cells for days before month starts
            for (let i = 0; i < startingDayOfWeek; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day other-month';
                calendarGrid.appendChild(emptyDay);
            }

            // Add days of the month
            const today = new Date();
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';

                const dayNumber = document.createElement('div');
                dayNumber.className = 'day-number';
                dayNumber.textContent = day;
                dayElement.appendChild(dayNumber);

                // Check if this is today
                if (year === today.getFullYear() && month === today.getMonth() && day === today.getDate()) {
                    dayElement.classList.add('today');
                }

                // Add click event
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                dayElement.onclick = () => selectDay(dateStr);
                dayElement.dataset.date = dateStr;

                calendarGrid.appendChild(dayElement);
            }
        }

        function changeMonth(direction) {
            currentDate.setMonth(currentDate.getMonth() + direction);
            generateCalendar();
            loadWorkData();
            updateCalendarRangeLabel();
        }

        function goToToday() {
            currentDate = new Date();
            generateCalendar();
            loadWorkData();
            updateCalendarRangeLabel();
        }

        function loadWorkData() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const startDate = new Date(year, month, 1);
            const endDate = new Date(year, month + 1, 0);

            const startStr = startDate.toISOString().split('T')[0];
            const endStr = endDate.toISOString().split('T')[0];

            console.log('Loading work data for:', startStr, 'to', endStr);

            // tampilkan loading
            const loadingEl = document.getElementById('calendar-loading');
            if (loadingEl) loadingEl.classList.add('active');

            // Fetch real data from API
            fetch(`{{ route('admin.tickets.calendar.data') }}?start=${startStr}&end=${endStr}`)
                .then(response => response.json())
                .then(events => {
                    workData = {};

                    events.forEach(event => {
                        const date = event.start;
                        const props = event.extendedProps;

                        workData[date] = {
                            totalDuration: props.duration,
                            formattedDuration: props.formatted_duration,
                            ticketsWorked: props.ticket_count,
                            sessionsCount: props.sessions_count,
                            // breakdowns
                            completedDuration: props.completed_duration || 0,
                            formattedCompletedDuration: props.formatted_completed_duration || '-',
                            inProgressDuration: props.in_progress_duration || 0,
                            formattedInProgressDuration: props.formatted_in_progress_duration || '-',
                            completedTickets: props.completed_ticket_count || 0,
                            inProgressTickets: props.in_progress_ticket_count || 0,
                        };

                        // Add minimal indicator and tooltip
                        const dayElement = document.querySelector(`[data-date="${date}"]`);
                        if (dayElement) {
                            dayElement.classList.add('has-work');
                            dayElement.title =
                                `Total: ${props.formatted_duration}\nSelesai: ${props.formatted_completed_duration} • Proses: ${props.formatted_in_progress_duration}\nTiket: ${props.ticket_count} (Selesai: ${props.completed_ticket_count}, Proses: ${props.in_progress_ticket_count})`;
                            const dot = document.createElement('div');
                            dot.className = 'work-dot';
                            dayElement.appendChild(dot);
                        }
                    });

                    updateMonthlySummary();
                })
                .catch(error => {
                    console.error('Error loading work data:', error);
                })
                .finally(() => {
                    if (loadingEl) loadingEl.classList.remove('active');
                });
        }

        function updateCalendarRangeLabel() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const startDate = new Date(year, month, 1);
            const endDate = new Date(year, month + 1, 0);

            const opts = {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            };
            const startText = startDate.toLocaleDateString('id-ID', opts).replace('.', '');
            const endText = endDate.toLocaleDateString('id-ID', opts).replace('.', '');

            const rangeEl = document.getElementById('calendar-range');
            if (rangeEl) {
                rangeEl.textContent = `Rentang: ${startText} — ${endText}`;
            }
        }

        function selectDay(dateStr) {
            // Remove previous selection
            document.querySelectorAll('.calendar-day').forEach(day => {
                day.classList.remove('selected');
            });

            // Add selection to clicked day
            const selectedDay = document.querySelector(`[data-date="${dateStr}"]`);
            if (selectedDay) {
                selectedDay.classList.add('selected');
            }

            // Fetch day details from API
            fetch(`{{ url('admin/tickets/calendar/day') }}/${dateStr}`)
                .then(response => response.json())
                .then(data => {
                    const dayInfo = document.getElementById('selected-day-info');

                    if (data.sessions && data.sessions.length > 0) {
                        // Pagination sederhana untuk sesi kerja
                        const pageSize = 5;
                        const total = data.sessions.length;
                        let page = 1;

                        function renderSessions() {
                            const start = (page - 1) * pageSize;
                            const end = Math.min(start + pageSize, total);
                            const pageSessions = data.sessions.slice(start, end);
                            const completedSessions = [];
                            const inProgressSessions = [];

                            pageSessions.forEach(session => {
                                const isCompleted = session.status === 'completed' || session.status ===
                                    'selesai/completed';
                                if (isCompleted) {
                                    completedSessions.push(session);
                                } else {
                                    inProgressSessions.push(session);
                                }
                            });

                            function renderItem(session) {
                                const isCompleted = (session.status === 'completed' || session.status ===
                                    'selesai/completed');
                                const badge = isCompleted ?
                                    '<span class="badge-status" style="background:#10b981;color:#fff;font-size:11px;padding:2px 6px;border-radius:4px;">Selesai</span>' :
                                    '<span class="badge-status" style="background:#f59e0b;color:#fff;font-size:11px;padding:2px 6px;border-radius:4px;">Proses</span>';

                                // Pisahkan tanggal dan jam hanya untuk sesi selesai: baris pertama tanggal, baris kedua jam kecil
                                let timeMain = session.time_range;
                                let timeSub = '';
                                if (isCompleted && typeof session.time_range === 'string') {
                                    // Robust: normalisasi pemisah (hyphen, en dash, em dash)
                                    const normalized = session.time_range.replace(/\s*[—–-]\s*/, ' — ');
                                    // Pola: dd MMM yyyy HH:mm(:ss)? — dd MMM yyyy HH:mm(:ss)?
                                    const m = normalized.match(
                                        /^(\d{1,2}\s+\p{L}+\s+\d{4})\s+(\d{2}:\d{2}(?::\d{2})?)\s+—\s+(\d{1,2}\s+\p{L}+\s+\d{4})\s+(\d{2}:\d{2}(?::\d{2})?)$/u
                                        );
                                    if (m) {
                                        timeMain = `${m[1]} — ${m[3]}`;
                                        timeSub = `${m[2]} — ${m[4]}`;
                                    } else {
                                        // Fallback generic split
                                        const parts = session.time_range.split(/\s*[—–-]\s*/);
                                        if (parts.length === 2) {
                                            const leftTokens = parts[0].trim().split(/\s+/);
                                            const rightTokens = parts[1].trim().split(/\s+/);
                                            const leftDate = leftTokens.slice(0, 3).join(' ');
                                            const rightDate = rightTokens.slice(0, 3).join(' ');
                                            timeMain = `${leftDate} — ${rightDate}`;
                                            const leftTime = leftTokens.slice(3).join(' ');
                                            const rightTime = rightTokens.slice(3).join(' ');
                                            if (leftTime || rightTime) {
                                                timeSub = `${leftTime} — ${rightTime}`.trim();
                                            }
                                        }
                                    }
                                }

                                return `
                            <div class="work-session-item">
                                <div class="session-time">${timeMain}</div>
                                ${badge}
                                ${timeSub ? `<div class="session-subtime">${timeSub}</div>` : ''}
                                <div class="session-ticket">${session.ticket_code} - ${session.ticket_title}</div>
                                <div class="session-duration">Durasi: ${session.duration}</div>
                            </div>`;
                            }

                            const inProgressHtml = inProgressSessions.map(renderItem).join('') ||
                                '<div style="font-size:12px;color:#9ca3af;">Tidak ada sesi yang sedang diproses pada halaman ini.</div>';
                            const completedHtml = completedSessions.map(renderItem).join('') ||
                                '<div style="font-size:12px;color:#9ca3af;">Tidak ada sesi selesai pada halaman ini.</div>';

                            const sessionsHtml = `
                                <div class="sidebar-section">
                                    <div class="sidebar-title">Sedang Diproses (${(data.stats && (data.stats.in_progress_sessions ?? inProgressSessions.length)) || 0})</div>
                                    ${inProgressHtml}
                                </div>
                                <div class="sidebar-section">
                                    <div class="sidebar-title">Selesai (${(data.stats && data.stats.completed_sessions) || completedSessions.length || 0})</div>
                                    ${completedHtml}
                                </div>`;

                            const pager = `
                            <div style="display:flex; justify-content: space-between; align-items:center; margin-top: 8px;">
                                <button class="btn" style="background:#e5e7eb;" ${page===1?'disabled':''} onclick="window._dayPagerPrev && window._dayPagerPrev()"><i class="fas fa-chevron-left"></i></button>
                                <div style="font-size: 12px; color:#64748b;">Menampilkan ${start+1}-${end} dari ${total} sesi</div>
                                <button class="btn" style="background:#e5e7eb;" ${end>=total?'disabled':''} onclick="window._dayPagerNext && window._dayPagerNext()"><i class="fas fa-chevron-right"></i></button>
                            </div>`;

                            dayInfo.innerHTML = `
                                <div class="day-detail">
                                    <div class="day-detail-header">${data.date}</div>
                                    <div style="margin-bottom: 12px;">
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                            <span>Total Waktu:</span>
                                            <span class="session-time">${data.stats.formatted_duration}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                            <span>Selesai:</span>
                                            <span>${data.stats.formatted_completed_duration || '-'}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                            <span>Sedang Proses:</span>
                                            <span>${data.stats.formatted_in_progress_duration || '-'}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                            <span>Total Tiket Dikerjakan:</span>
                                            <span>${data.stats.unique_tickets}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between;">
                                            <span>Sesi Kerja:</span>
                                            <span>Selesai: ${data.stats.completed_sessions} • Proses: ${data.stats.in_progress_sessions || 0}</span>
                                        </div>
                                    </div>
                                    <div style="font-size: 13px; color: #64748b; margin-bottom: 8px; display:flex; justify-content: space-between; align-items:center;">
                                        <span>Detail Sesi Kerja</span>
                                    </div>
                                    <div id="sessions-container">${sessionsHtml}</div>
                                    ${total>pageSize ? pager : ''}
                                </div>`;
                        }

                        // Expose handler untuk tombol pager
                        window._dayPagerPrev = () => {
                            if (page > 1) {
                                page--;
                                renderSessions();
                            }
                        };
                        window._dayPagerNext = () => {
                            if ((page * pageSize) < total) {
                                page++;
                                renderSessions();
                            }
                        };

                        renderSessions();
                    } else {
                        dayInfo.innerHTML = `
                    <div class="day-detail">
                        <div class="day-detail-header">${data.date}</div>
                        <p style="color: #64748b; font-style: italic; margin: 12px 0;">Tidak ada sesi kerja yang tercatat untuk hari ini.</p>
                    </div>
                `;
                    }
                })
                .catch(error => {
                    console.error('Error loading day details:', error);
                });
        }

        function updateMonthlySummary() {
            const workDays = Object.keys(workData).length;
            const totalSeconds = Math.round(Object.values(workData).reduce((sum, day) => sum + Number(day.totalDuration ||
                0), 0));
            const totalTickets = Object.values(workData).reduce((sum, day) => sum + Number(day.ticketsWorked || 0), 0);

            const totalTimeText = formatDuration(totalSeconds);
            const avgSeconds = workDays > 0 ? Math.round(totalSeconds / workDays) : 0;
            const avgTimeText = formatDuration(avgSeconds);

            document.getElementById('month-work-days').textContent = workDays;
            document.getElementById('month-total-hours').textContent = totalTimeText;
            document.getElementById('month-avg-hours').textContent = avgTimeText;
            document.getElementById('month-tickets').textContent = totalTickets;
        }
    </script>
@endsection
