@extends('layouts.admin')

@section('page-title', 'Log Activity Tiket')
@section('breadcrumb', 'Log Activity')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-header min-height-100 border-radius-xl mt-4 bg-white" style="border: 1px solid #e5e7eb;">
                    <div class="container py-3">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h3 class="mb-1" style="color:#111827">Log Aktivitas Tiket</h3>
                                <p class="text-sm mb-0" style="color:#6b7280">Pantau aktivitas yang terjadi pada sistem
                                    tiket</p>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-secondary" onclick="window.location.reload()">
                                        <i class="fas fa-rotate me-1"></i>Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-3">
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-xs text-muted mb-1">Total Aktivitas</p>
                            <h5 class="mb-0">{{ $counts['total'] ?? $activities->total() }}</h5>
                        </div>
                        <i class="fas fa-layer-group text-secondary"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-xs text-muted mb-1">Disetujui</p>
                            <h5 class="mb-0">
                                {{ $counts['accepted'] ?? $activities->where('action', 'accepted')->count() }}</h5>
                        </div>
                        <i class="fas fa-check text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-xs text-muted mb-1">Ditolak</p>
                            <h5 class="mb-0">
                                {{ $counts['rejected'] ?? $activities->where('action', 'rejected')->count() }}</h5>
                        </div>
                        <i class="fas fa-times text-danger"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-xs text-muted mb-1">Selesai</p>
                            <h5 class="mb-0">
                                {{ $counts['completed'] ?? $activities->where('action', 'completed')->count() }}</h5>
                        </div>
                        <i class="fas fa-flag-checkered text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-3">
                        <form id="filterForm" method="GET" action="{{ route('admin.tickets.activity') }}">
                            <div class="row align-items-end g-2">
                                <div class="col-md-3">
                                    <label class="form-label text-xs">Filter Status</label>
                                    <select class="form-control form-control-sm" id="filterStatus" name="action"
                                        value="{{ request('action') }}">
                                        <option value="">Semua Status</option>
                                        <option value="accepted" {{ request('action') == 'accepted' ? 'selected' : '' }}>
                                            Disetujui</option>
                                        <option value="rejected" {{ request('action') == 'rejected' ? 'selected' : '' }}>
                                            Ditolak</option>
                                        <option value="completed" {{ request('action') == 'completed' ? 'selected' : '' }}>
                                            Selesai</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-xs">Dari Tanggal</label>
                                    <input type="date" class="form-control form-control-sm" id="dateFrom"
                                        name="start_date" value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-xs">Sampai Tanggal</label>
                                    <input type="date" class="form-control form-control-sm" id="dateTo"
                                        name="end_date" value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-xs">Cari Kode Tiket</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="searchCode" name="code_tracking"
                                            value="{{ request('code_tracking') }}" placeholder="Cari kode tiket...">
                                        <button class="btn btn-primary btn-sm mb-0" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-light pb-0">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="mb-0">Riwayat Aktivitas</h5>
                                <p class="text-sm text-muted mb-2">Menampilkan {{ $activities->count() }} dari
                                    {{ $activities->total() }} aktivitas</p>
                            </div>
                            <div class="col-6 text-end">
                                <div class="btn-group" role="group">
                                    <button type="button" id="btnList" class="btn btn-sm btn-outline-primary active">
                                        <i class="fas fa-list"></i>
                                    </button>
                                    <button type="button" id="btnGrid" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-th"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0" id="viewList">
                            <table class="table align-items-center mb-0 table-hover">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">
                                            <i class="far fa-clock me-2"></i>Waktu
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            <i class="fas fa-ticket-alt me-2"></i>Kode Tiket
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            <i class="fas fa-bolt me-2"></i>Aksi
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            <i class="fas fa-info-circle me-2"></i>Deskripsi
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            <i class="fas fa-history me-2"></i>Timeline
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Opsi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($activities as $activity)
                                        <tr class="hover-row">
                                            <td class="ps-3">
                                                <div class="d-flex flex-column">
                                                    <h6 class="mb-0 text-sm font-weight-bold">
                                                        {{ $activity->created_at->format('d M Y') }}
                                                    </h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        <i class="far fa-clock me-1"></i>
                                                        {{ $activity->created_at->format('H:i:s') }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="text-dark">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-group">
                                                        <a href="{{ route('admin.tickets.show', $activity->ticket) }}">
                                                            {{-- Removed the first two letters of code_tracking as per user request --}}
                                                        </a>
                                                    </div>
                                                    <div class="ms-3">
                                                        <a href="{{ route('admin.tickets.show', $activity->ticket) }}"
                                                            class="text-dark font-weight-bold text-sm text-decoration-none hover-underline">
                                                            {{ $activity->ticket->code_tracking }}
                                                        </a>
                                                        <p class="text-xs text-secondary mb-0">
                                                            ID: #{{ $activity->ticket->id }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($activity->action == 'accepted')
                                                    <span class="badge badge-sm bg-light text-success border shadow-sm">
                                                        <i class="fas fa-check-circle me-1"></i>Disetujui
                                                    </span>
                                                @elseif($activity->action == 'rejected')
                                                    <span class="badge badge-sm bg-light text-danger border shadow-sm">
                                                        <i class="fas fa-times-circle me-1"></i>Ditolak
                                                    </span>
                                                @elseif($activity->action == 'completed')
                                                    <span class="badge badge-sm bg-light text-info border shadow-sm">
                                                        <i class="fas fa-flag-checkered me-1"></i>Selesai
                                                    </span>
                                                @else
                                                    <span class="badge badge-sm bg-light text-secondary border shadow-sm">
                                                        <i class="fas fa-sync me-1"></i>{{ ucfirst($activity->action) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-wrap" style="max-width: 300px;">
                                                    <p class="text-sm font-weight-normal mb-0">
                                                        {{ Str::limit($activity->description, 100) }}
                                                    </p>
                                                    @if (strlen($activity->description) > 100)
                                                        <a href="#" class="text-xs text-primary"
                                                            data-bs-toggle="tooltip"
                                                            title="{{ $activity->description }}">
                                                            Lihat selengkapnya
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="timeline-info">
                                                    @if ($activity->action === 'accepted')
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="timeline-icon bg-success-light rounded-circle p-2 me-2">
                                                                <i class="fas fa-check text-success text-xs"></i>
                                                            </div>
                                                            <div>
                                                                <p class="text-xs mb-0 font-weight-bold">Disetujui</p>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ optional($activity->ticket->accepted_at)->format('d/m/Y H:i') ?? '-' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @elseif($activity->action === 'rejected')
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="timeline-icon bg-danger-light rounded-circle p-2 me-2">
                                                                <i class="fas fa-times text-danger text-xs"></i>
                                                            </div>
                                                            <div>
                                                                <p class="text-xs mb-0 font-weight-bold">Ditolak</p>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ optional($activity->ticket->resolved_at)->format('d/m/Y H:i') ?? '-' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @elseif($activity->action === 'completed')
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="timeline-icon bg-info-light rounded-circle p-2 me-2">
                                                                <i class="fas fa-flag text-info text-xs"></i>
                                                            </div>
                                                            <div>
                                                                <p class="text-xs mb-0 font-weight-bold">Selesai</p>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ optional($activity->ticket->resolved_at)->format('d/m/Y H:i') ?? '-' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-xs text-secondary">-</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-link text-secondary mb-0" type="button"
                                                        id="dropdownMenuButton{{ $activity->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu shadow-lg"
                                                        aria-labelledby="dropdownMenuButton{{ $activity->id }}">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.tickets.show', $activity->ticket) }}">
                                                                <i class="fas fa-eye me-2"></i>Lihat Detail
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#"
                                                                onclick="deleteActivity({{ $activity->id }});" data-id="{{ $activity->id }}">
                                                                <i class="fas fa-trash me-2"></i>Hapus
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">Tidak Ada Aktivitas</h5>
                                                    <p class="text-sm text-secondary">Belum ada aktivitas yang tercatat
                                                        dalam sistem</p>
                                                    <a href="{{ route('admin.tickets.index') }}"
                                                        class="btn btn-sm btn-primary mt-3">
                                                        <i class="fas fa-ticket-alt me-2"></i>Lihat Tiket
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div id="viewGrid" class="p-3">
                            <div class="row g-3">
                                @forelse($activities as $activity)
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="activity-card">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span class="text-xs text-secondary">
                                                    <i
                                                        class="far fa-clock me-1"></i>{{ $activity->created_at->format('d M Y H:i') }}
                                                </span>
                                                @php $color = $activity->action==='accepted'?'success':($activity->action==='rejected'?'danger':($activity->action==='completed'?'info':'secondary')); @endphp
                                                <span
                                                    class="badge bg-gradient-{{ $color }} activity-badge text-white">{{ ucfirst($activity->action) }}</span>
                                            </div>
                                            <a href="{{ route('admin.tickets.show', $activity->ticket) }}"
                                                class="d-block fw-bold text-decoration-none mb-1">
                                                {{ $activity->ticket->code_tracking }}
                                            </a>
                                            <div class="text-sm text-secondary mb-2">ID: #{{ $activity->ticket->id }}
                                            </div>
                                            <div class="text-sm">{{ Str::limit($activity->description, 120) }}</div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="empty-state text-center">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Tidak Ada Aktivitas</h5>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="row align-items-center">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info">
                                    Menampilkan {{ $activities->firstItem() ?? 0 }} sampai
                                    {{ $activities->lastItem() ?? 0 }}
                                    dari {{ $activities->total() }} aktivitas
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="float-end">
                                    {{ $activities->onEachSide(1)->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-row:hover {
            background-color: rgba(0, 0, 0, 0.02);
            transition: all .3s ease;
        }

        .hover-underline:hover {
            text-decoration: underline !important;
        }

        .timeline-icon {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-success-light {
            background-color: rgba(40, 167, 69, .1);
        }

        .bg-danger-light {
            background-color: rgba(220, 53, 69, .1);
        }

        .bg-info-light {
            background-color: rgba(23, 162, 184, .1);
        }

        .empty-state {
            padding: 40px;
        }

        .card {
            border-radius: 12px;
        }

        .badge {
            padding: 5px 10px;
            font-weight: 600;
        }

        .avatar {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .page-header {
            border-radius: 12px;
            padding: 16px 20px;
        }

        .dropdown-menu {
            border: none;
            border-radius: 10px;
        }

        .dropdown-item {
            padding: 10px 20px;
            transition: all .2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            padding-left: 25px;
        }

        /* Grid view */
        #viewGrid {
            display: none;
        }

        .activity-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px;
            background: #fff;
            height: 100%;
        }

        .activity-badge {
            font-size: .75rem;
        }
    </style>

    <script>
        // Submit on change for filters
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filterForm');
            ['filterStatus', 'dateFrom', 'dateTo'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('change', () => form.submit());
            });

            // Toggle view
            const btnList = document.getElementById('btnList');
            const btnGrid = document.getElementById('btnGrid');
            const viewList = document.getElementById('viewList');
            const viewGrid = document.getElementById('viewGrid');
            if (btnList && btnGrid && viewList && viewGrid) {
                btnList.addEventListener('click', function() {
                    btnList.classList.add('active');
                    btnGrid.classList.remove('active');
                    viewList.style.display = 'block';
                    viewGrid.style.display = 'none';
                });
                btnGrid.addEventListener('click', function() {
                    btnGrid.classList.add('active');
                    btnList.classList.remove('active');
                    viewList.style.display = 'none';
                    viewGrid.style.display = 'block';
                });
            }

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });

        function printActivity(id) {
            window.print();
        }

        function deleteActivity(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus aktivitas ini?')) return;
            fetch(`{{ url('admin/tickets/activity') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(() => window.location.reload());
        }
    </script>
@endsection
