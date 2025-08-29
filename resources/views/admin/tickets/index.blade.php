@extends('layouts.admin')

@section('breadcrumb', 'Manage Tickets')
@section('page-title', 'Manage Tickets')

@section('content')
    <style>
        /* Modern Tailwind-inspired CSIRT Dashboard */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .modern-dashboard {
            min-height: 100vh;
            background: #f8fafc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1e293b;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Header Section */
        .header-section {
            margin-bottom: 32px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .header-title {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.025em;
            margin-bottom: 4px;
        }

        .header-subtitle {
            font-size: 16px;
            color: #64748b;
            font-weight: 400;
        }

        .clock-container {
            text-align: right;
        }

        .clock-time {
            font-family: 'SF Mono', Monaco, 'Cascadia Code', monospace;
            font-size: 20px;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.2;
        }

        .clock-date {
            font-size: 14px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .filter-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .filter-title {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .filter-label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }

        .filter-input,
        .filter-select {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: white;
        }

        .filter-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-filter {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        /* Quick Filter Tabs */
        .quick-filters {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .quick-filter-tab {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            border: 2px solid transparent;
            transition: all 0.2s ease;
            background: white;
            color: #64748b;
        }

        .quick-filter-tab:hover {
            text-decoration: none;
            transform: translateY(-1px);
        }

        .quick-filter-tab.active {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .quick-filter-tab.all.active {
            background: #6366f1;
            border-color: #6366f1;
        }

        .quick-filter-tab.pending.active {
            background: #f59e0b;
            border-color: #f59e0b;
        }

        .quick-filter-tab.approved.active {
            background: #10b981;
            border-color: #10b981;
        }

        .quick-filter-tab.rejected.active {
            background: #ef4444;
            border-color: #ef4444;
        }

        .quick-filter-tab.completed.active {
            background: #8b5cf6;
            border-color: #8b5cf6;
        }

        .filter-count {
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-color: #cbd5e1;
        }

        .stat-header {
            display: flex;
            items-center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .stat-icon.total {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }

        .stat-icon.pending {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .stat-icon.approved {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .stat-icon.rejected {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .stat-icon.completed {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
        }

        /* Alert */
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success .icon {
            color: #16a34a;
            font-size: 18px;
        }

        .alert-success .text {
            color: #15803d;
            font-weight: 500;
        }

        /* Tickets Section */
        .tickets-section {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .section-header {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
            background: #fafafa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title .icon {
            color: #3b82f6;
        }

        .results-info {
            color: #64748b;
            font-size: 14px;
        }

        .tickets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(480px, 1fr));
            gap: 1px;
            background: #e2e8f0;
        }

        .ticket-card {
            background: white;
            padding: 24px;
            transition: all 0.2s ease;
            position: relative;
            border-left: 4px solid transparent;
        }

        .ticket-card:hover {
            background: #f8fafc;
            border-left-color: #3b82f6;
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .ticket-id {
            background: #3b82f6;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-family: 'SF Mono', Monaco, monospace;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .ticket-time {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #64748b;
            font-size: 12px;
        }

        .ticket-title {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 16px;
            line-height: 1.4;
        }

        .ticket-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .meta-item {
            background: #f8fafc;
            padding: 12px;
            border-radius: 6px;
            border-left: 2px solid #e2e8f0;
        }

        .meta-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .meta-value {
            font-size: 14px;
            color: #0f172a;
            font-weight: 500;
        }

        .ticket-description {
            background: #f1f5f9;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            border-left: 3px solid #cbd5e1;
        }

        .description-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .description-text {
            color: #475569;
            font-size: 14px;
            line-height: 1.5;
        }

        .ticket-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.approved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-badge.completed {
            background: #ede9fe;
            color: #6b21a8;
        }

        .attachment-info {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #64748b;
            font-size: 12px;
        }

        .ticket-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 16px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            text-decoration: none;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .no-actions {
            color: #64748b;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .empty-state {
            text-align: center;
            padding: 64px 24px;
            color: #64748b;
        }

        .empty-state .icon {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 16px;
        }

        .empty-state h3 {
            font-size: 18px;
            color: #374151;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .empty-state p {
            font-size: 14px;
            color: #64748b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 16px;
            }

            .header-content {
                flex-direction: column;
                gap: 16px;
            }

            .clock-container {
                text-align: left;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .quick-filters {
                flex-direction: column;
            }

            .tickets-grid {
                grid-template-columns: 1fr;
            }

            .ticket-meta {
                grid-template-columns: 1fr;
            }

            .ticket-footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .ticket-actions {
                width: 100%;
            }

            .btn {
                flex: 1;
                justify-content: center;
            }
        }

        /* Animation */
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="modern-dashboard">
        <div class="dashboard-container">
            <!-- Header -->
            <div class="header-section fade-in">
                <div class="header-content">
                    <div>
                        <h1 class="header-title">CSIRT Management Dashboard</h1>
                        <p class="header-subtitle">Monitor and manage cybersecurity incident reports</p>
                    </div>
                    <div class="clock-container">
                        <div class="clock-time" id="clockTime"></div>
                        <div class="clock-date" id="clockDate"></div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert-success fade-in">
                    <i class="fas fa-check-circle icon"></i>
                    <span class="text">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Statistics -->
            <div class="stats-grid fade-in">
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-number">{{ $stats['total'] }}</div>
                            <div class="stat-label">Total Tickets</div>
                        </div>
                        <div class="stat-icon total">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-number">{{ $stats['pending'] }}</div>
                            <div class="stat-label">Pending Review</div>
                        </div>
                        <div class="stat-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-number">{{ $stats['approved'] }}</div>
                            <div class="stat-label">Approved</div>
                        </div>
                        <div class="stat-icon approved">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-number">{{ $stats['rejected'] }}</div>
                            <div class="stat-label">Rejected</div>
                        </div>
                        <div class="stat-icon rejected">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-number">{{ $stats['completed'] }}</div>
                            <div class="stat-label">Completed</div>
                        </div>
                        <div class="stat-icon completed">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Filter Tabs -->
            <div class="quick-filters fade-in">
                <a href="{{ route('admin.tickets.index') }}"
                    class="quick-filter-tab all {{ !request('status') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    All Tickets
                    <span class="filter-count">{{ $stats['total'] }}</span>
                </a>
                <a href="{{ route('admin.tickets.index', ['status' => 'pending']) }}"
                    class="quick-filter-tab pending {{ request('status') == 'pending' ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    Pending
                    <span class="filter-count">{{ $stats['pending'] }}</span>
                </a>
                <a href="{{ route('admin.tickets.index', ['status' => 'diterima/approved']) }}"
                    class="quick-filter-tab approved {{ request('status') == 'diterima/approved' ? 'active' : '' }}">
                    <i class="fas fa-check-circle"></i>
                    Approved
                    <span class="filter-count">{{ $stats['approved'] }}</span>
                </a>
                <a href="{{ route('admin.tickets.index', ['status' => 'ditolak/rejected']) }}"
                    class="quick-filter-tab rejected {{ request('status') == 'ditolak/rejected' ? 'active' : '' }}">
                    <i class="fas fa-times-circle"></i>
                    Rejected
                    <span class="filter-count">{{ $stats['rejected'] }}</span>
                </a>
                <a href="{{ route('admin.tickets.index', ['status' => 'selesai/completed']) }}"
                    class="quick-filter-tab completed {{ request('status') == 'selesai/completed' ? 'active' : '' }}">
                    <i class="fas fa-check-double"></i>
                    Completed
                    <span class="filter-count">{{ $stats['completed'] }}</span>
                </a>
            </div>

            <!-- Advanced Filters -->
            <div class="filter-section fade-in">
                <div class="filter-header">
                    <i class="fas fa-filter"></i>
                    <span class="filter-title">Advanced Filters</span>
                </div>

                <form method="GET" action="{{ route('admin.tickets.index') }}">
                    <div class="filter-grid">
                        <div class="filter-group">
                            <label class="filter-label">Search</label>
                            <input type="text" name="search" class="filter-input"
                                placeholder="Code tracking, reporter name, or title..." value="{{ request('search') }}">
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Status</label>
                            <select name="status" class="filter-select">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status
                                </option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="diterima/approved"
                                    {{ request('status') == 'diterima/approved' ? 'selected' : '' }}>Approved</option>
                                <option value="ditolak/rejected"
                                    {{ request('status') == 'ditolak/rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="selesai/completed"
                                    {{ request('status') == 'selesai/completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Kabupaten</label>
                            <select name="kabupaten_id" class="filter-select">
                                <option value="">All Kabupaten</option>
                                @foreach ($kabupatenList as $kabupaten)
                                    <option value="{{ $kabupaten->id }}"
                                        {{ request('kabupaten_id') == $kabupaten->id ? 'selected' : '' }}>
                                        {{ $kabupaten->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Kecamatan</label>
                            <select name="kecamatan_id" class="filter-select">
                                <option value="">All Kecamatan</option>
                                @foreach ($kecamatanList as $kecamatan)
                                    <option value="{{ $kecamatan->id }}"
                                        {{ request('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                                        {{ $kecamatan->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Start Date</label>
                            <input type="date" name="start_date" class="filter-input"
                                value="{{ request('start_date') }}">
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">End Date</label>
                            <input type="date" name="end_date" class="filter-input"
                                value="{{ request('end_date') }}">
                        </div>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-filter btn-primary">
                            <i class="fas fa-search"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.tickets.index') }}" class="btn-filter btn-secondary">
                            <i class="fas fa-times"></i>
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tickets -->
            <div class="tickets-section fade-in">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-shield-alt icon"></i>
                        Security Incident Tickets
                    </h2>
                    <div class="results-info">
                        Showing {{ $tickets->count() }} of {{ $stats['total'] }} tickets
                    </div>
                </div>

                @if ($tickets->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-inbox icon"></i>
                        <h3>No tickets found</h3>
                        <p>No tickets match your current filter criteria. Try adjusting your filters or check back later.
                        </p>
                    </div>
                @else
                    <div class="tickets-grid">
                        @foreach ($tickets as $ticket)
                            <div class="ticket-card">
                                <div class="ticket-header">
                                    <div class="ticket-id">{{ $ticket->code_tracking }}</div>
                                    <div class="ticket-time">
                                        <i class="fas fa-clock"></i>
                                        {{ $ticket->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                <h3 class="ticket-title">{{ $ticket->judul ?? 'No Title Provided' }}</h3>

                                <div class="ticket-meta">
                                    <div class="meta-item">
                                        <div class="meta-label">Reporter Name</div>
                                        <div class="meta-value">{{ $ticket->nama_pelapor ?? 'Anonymous' }}</div>
                                    </div>
                                    <div class="meta-item">
                                        <div class="meta-label">Contact Email</div>
                                        <div class="meta-value">{{ Str::limit($ticket->email ?? 'No Email', 25) }}</div>
                                    </div>
                                    @if ($ticket->kabupaten)
                                        <div class="meta-item">
                                            <div class="meta-label">Kabupaten</div>
                                            <div class="meta-value">{{ $ticket->kabupaten->nama }}</div>
                                        </div>
                                    @endif
                                    @if ($ticket->kecamatan)
                                        <div class="meta-item">
                                            <div class="meta-label">Kecamatan</div>
                                            <div class="meta-value">{{ $ticket->kecamatan->nama }}</div>
                                        </div>
                                    @endif
                                </div>

                                @if ($ticket->description)
                                    <div class="ticket-description">
                                        <div class="description-label">Description</div>
                                        <div class="description-text">{{ Str::limit($ticket->description, 120) }}</div>
                                    </div>
                                @endif

                                <div class="ticket-footer">
                                    <div>
                                        @if ($ticket->status == 'pending')
                                            <span class="status-badge pending">
                                                <i class="fas fa-hourglass-half"></i>
                                                Pending Review
                                            </span>
                                        @elseif($ticket->status == 'diterima/approved')
                                            <span class="status-badge approved">
                                                <i class="fas fa-check"></i>
                                                Approved
                                            </span>
                                        @elseif($ticket->status == 'ditolak/rejected')
                                            <span class="status-badge rejected">
                                                <i class="fas fa-times"></i>
                                                Rejected
                                            </span>
                                        @elseif($ticket->status == 'selesai/completed')
                                            <span class="status-badge completed">
                                                <i class="fas fa-check-double"></i>
                                                Completed
                                            </span>
                                        @endif
                                    </div>
                                    @if ($ticket->attachment_path)
                                        <div class="attachment-info">
                                            <i class="fas fa-paperclip"></i>
                                            Has Attachment
                                        </div>
                                    @endif
                                </div>

                                <div class="ticket-actions">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-primary">
                                        <i class="fas fa-eye"></i>
                                        View Details
                                    </a>

                                    @if ($ticket->status == 'pending')
                                        <form action="{{ route('admin.tickets.accept', $ticket) }}" method="POST"
                                            style="display: inline-block;" class="ticket-form">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check"></i>
                                                Accept
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.tickets.reject', $ticket) }}" method="POST"
                                            style="display: inline-block;" class="ticket-form">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-times"></i>
                                                Reject
                                            </button>
                                        </form>
                                    @else
                                        <div class="no-actions">
                                            <i class="fas fa-info-circle"></i>
                                            No actions available
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Real-time clock
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const dateString = now.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            document.getElementById('clockTime').textContent = timeString;
            document.getElementById('clockDate').textContent = dateString;
        }

        updateClock();
        setInterval(updateClock, 1000);

        // Form submission handlers
        document.addEventListener('DOMContentLoaded', function() {
            const actionForms = document.querySelectorAll('.ticket-form');

            actionForms.forEach((form) => {
                const button = form.querySelector('button[type="submit"]');
                if (button) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Konfirmasi untuk action
                        let confirmMessage = '';
                        if (button.classList.contains('btn-danger')) {
                            confirmMessage = 'Are you sure you want to reject this ticket?';
                        } else if (button.classList.contains('btn-success')) {
                            confirmMessage = 'Are you sure you want to accept this ticket?';
                        }

                        if (confirmMessage && !confirm(confirmMessage)) {
                            return;
                        }

                        // Show loading state
                        const originalContent = button.innerHTML;
                        button.innerHTML = '<span class="spinner"></span> Processing...';
                        button.disabled = true;

                        // Submit form after small delay
                        setTimeout(() => {
                            form.submit();
                        }, 100);
                    });
                }
            });

            // Add staggered fade-in animation
            const cards = document.querySelectorAll('.ticket-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.05}s`;
                card.classList.add('fade-in');
            });
        });
    </script>

@endsection
