@extends('layouts.admin')

@section('page-title', 'Log Activity Tiket')
@section('breadcrumb', 'Log Activity')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-header min-height-150 border-radius-xl mt-4"
                     style="background-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <span class="mask bg-gradient-primary opacity-6"></span>
                    <div class="container py-3">
                        <div class="row">
                            <div class="col-lg-8">
                                <h3 class="text-white mb-0">Log Activity Tiket</h3>
                                <p class="text-white opacity-8 mb-0">Monitor semua aktivitas yang terjadi pada sistem tiket</p>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <button class="btn btn-white btn-sm" onclick="window.location.reload()">
                                    <i class="fas fa-sync-alt me-2"></i>Refresh
                                </button>
                                <button class="btn btn-white btn-sm ms-2" onclick="exportData()">
                                    <i class="fas fa-download me-2"></i>Export
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Aktivitas</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ $activities->total() }}
                                        <span class="text-success text-sm font-weight-bolder">+55%</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                    <i class="ni ni-chart-bar-32 text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Disetujui</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ $activities->where('action', 'accepted')->count() }}
                                        <span class="text-success text-sm font-weight-bolder">+3%</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Ditolak</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ $activities->where('action', 'rejected')->count() }}
                                        <span class="text-danger text-sm font-weight-bolder">-2%</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                    <i class="ni ni-fat-remove text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Selesai</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ $activities->where('action', 'completed')->count() }}
                                        <span class="text-info text-sm font-weight-bolder">+5%</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="ni ni-satisfied text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label class="form-label text-xs">Filter Status</label>
                                    <select class="form-control form-control-sm" id="filterStatus">
                                        <option value="">Semua Status</option>
                                        <option value="accepted">Disetujui</option>
                                        <option value="rejected">Ditolak</option>
                                        <option value="completed">Selesai</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label class="form-label text-xs">Dari Tanggal</label>
                                    <input type="date" class="form-control form-control-sm" id="dateFrom">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label class="form-label text-xs">Sampai Tanggal</label>
                                    <input type="date" class="form-control form-control-sm" id="dateTo">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label class="form-label text-xs">Cari</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" placeholder="Cari kode tiket...">
                                        <button class="btn btn-primary btn-sm mb-0" type="button">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                <p class="text-sm text-muted mb-2">Menampilkan {{ $activities->count() }} dari {{ $activities->total() }} aktivitas</p>
                            </div>
                            <div class="col-6 text-end">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary active">
                                        <i class="fas fa-list"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-th"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0 table-hover">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">
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
                                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
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
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-group">
                                                        <a href="{{ route('admin.tickets.show', $activity->ticket) }}"
                                                           class="avatar avatar-sm rounded-circle bg-gradient-primary"
                                                           data-bs-toggle="tooltip"
                                                           data-bs-placement="bottom"
                                                           title="Lihat Detail Tiket">
                                                            <span class="text-white text-xs">
                                                                {{ substr($activity->ticket->code_tracking, 0, 2) }}
                                                            </span>
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
                                                @if($activity->action == 'accepted')
                                                    <span class="badge badge-sm bg-gradient-success shadow-sm">
                                                        <i class="fas fa-check-circle me-1"></i>Disetujui
                                                    </span>
                                                @elseif($activity->action == 'rejected')
                                                    <span class="badge badge-sm bg-gradient-danger shadow-sm">
                                                        <i class="fas fa-times-circle me-1"></i>Ditolak
                                                    </span>
                                                @elseif($activity->action == 'completed')
                                                    <span class="badge badge-sm bg-gradient-info shadow-sm">
                                                        <i class="fas fa-flag-checkered me-1"></i>Selesai
                                                    </span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-secondary shadow-sm">
                                                        <i class="fas fa-sync me-1"></i>{{ ucfirst($activity->action) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-wrap" style="max-width: 300px;">
                                                    <p class="text-sm font-weight-normal mb-0">
                                                        {{ Str::limit($activity->description, 100) }}
                                                    </p>
                                                    @if(strlen($activity->description) > 100)
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
                                                    @if($activity->action === 'accepted')
                                                        <div class="d-flex align-items-center">
                                                            <div class="timeline-icon bg-success-light rounded-circle p-2 me-2">
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
                                                            <div class="timeline-icon bg-danger-light rounded-circle p-2 me-2">
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
                                                            <div class="timeline-icon bg-info-light rounded-circle p-2 me-2">
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
                                                    <button class="btn btn-link text-secondary mb-0"
                                                            type="button"
                                                            id="dropdownMenuButton{{ $activity->id }}"
                                                            data-bs-toggle="dropdown"
                                                            aria-expanded="false">
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
                                                            <a class="dropdown-item" href="#" onclick="printActivity({{ $activity->id }})">
                                                                <i class="fas fa-print me-2"></i>Cetak
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" onclick="deleteActivity({{ $activity->id }})">
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
                                                    <p class="text-sm text-secondary">Belum ada aktivitas yang tercatat dalam sistem</p>
                                                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-primary mt-3">
                                                        <i class="fas fa-ticket-alt me-2"></i>Lihat Tiket
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="row align-items-center">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info">
                                    Menampilkan {{ $activities->firstItem() ?? 0 }} sampai {{ $activities->lastItem() ?? 0 }}
                                    dari {{ $activities->total() }} aktivitas
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="float-end">
                                    {{ $activities->links('pagination::bootstrap-4') }}
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
            transition: all 0.3s ease;
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
            background-color: rgba(40, 167, 69, 0.1);
        }

        .bg-danger-light {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .bg-info-light {
            background-color: rgba(23, 162, 184, 0.1);
        }

        .empty-state {
            padding: 40px;
        }

        .card {
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
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
            border-radius: 20px;
            padding: 30px;
        }

        .icon-shape {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .table-hover tbody tr {
            cursor: pointer;
        }

        .dropdown-menu {
            border: none;
            border-radius: 10px;
        }

        .dropdown-item {
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            padding-left: 25px;
        }
    </style>

    <script>
        function exportData() {
            // Implementasi export data
            alert('Fitur export akan segera tersedia');
        }

        function printActivity(id) {
            // Implementasi print activity
            window.print();
        }

        function deleteActivity(id) {
            // Implementasi delete activity dengan konfirmasi
            if(confirm('Apakah Anda yakin ingin menghapus aktivitas ini?')) {
                // Proses delete
                console.log('Delete activity:', id);
            }
        }

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
@endsection
