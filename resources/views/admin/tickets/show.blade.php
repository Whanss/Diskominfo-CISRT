@extends('layouts.admin')

@section('breadcrumb', 'Ticket Details')
@section('page-title', 'Ticket Details - ' . $ticket->code_tracking)

@push('styles')
    <style>
        /* Reset default styles yang mungkin conflict */
        .main-content {
            margin-left: 270px !important;
            margin-top: 64px !important;
            padding: 0 !important;
            min-height: calc(100vh - 64px);
        }

        body.sidebar-collapsed .main-content {
            margin-left: 90px !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0 !important;
                margin-top: 60px !important;
            }
        }

        .card {
            border: 1px solid #e3e6f0;
            border-radius: 12px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            overflow: hidden;
            background: #fff;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.25rem;
        }

        .ticket-title {
            font-weight: 700;
            color: #5a5c69;
            margin-bottom: 0;
        }

        .btn-block {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        .bg-gradient-warning {
            background: linear-gradient(45deg, #f6c23e, #f4b619) !important;
        }

        .bg-gradient-success {
            background: linear-gradient(45deg, #1cc88a, #17a673) !important;
        }

        .bg-gradient-danger {
            background: linear-gradient(45deg, #e74a3b, #c0392b) !important;
        }

        .bg-gradient-secondary {
            background: linear-gradient(45deg, #858796, #60616f) !important;
        }

        .text-primary {
            color: #4e73df !important;
        }

        .alert {
            border-radius: 8px;
            border: none;
            padding: 1rem 1.25rem;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .list-unstyled li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e3e6f0;
        }

        .list-unstyled li:last-child {
            border-bottom: none;
        }

        .container-fluid {
            max-width: 100%;
            padding: 20px;
        }

        .page-header {
            background: transparent;
            padding: 0 0 1rem 0;
            margin-bottom: 1.5rem;
        }

        /* Button improvements */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Responsive table for ticket info */
        @media (max-width: 576px) {
            .col-md-6 {
                margin-bottom: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Ticket Details</h4>
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="ticket-title">Ticket Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Code Tracking:</strong> <span
                                            class="text-primary">{{ $ticket->code_tracking }}</span></p>
                                    <p><strong>Title:</strong> {{ $ticket->judul }}</p>
                                    <p><strong>Reporter Name:</strong> {{ $ticket->nama_pelapor }}</p>
                                    <p><strong>Email:</strong> {{ $ticket->email }}</p>
                                    <p><strong>Phone:</strong> {{ $ticket->no_hp ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Status:</strong>
                                        <span
                                            class="badge
                                            @if ($ticket->status == 'pending') bg-gradient-warning
                                            @elseif($ticket->status == 'diterima/approved') bg-gradient-success
                                            @elseif($ticket->status == 'ditolak/rejected') bg-gradient-danger
                                            @else bg-gradient-secondary @endif">
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </p>
                                    <p><strong>Created:</strong> {{ $ticket->created_at->format('d M Y H:i') }}</p>
                                    <p><strong>Last Updated:</strong> {{ $ticket->updated_at->format('d M Y H:i') }}</p>
                                    <p><strong>Kabupaten:</strong> {{ $ticket->kabupaten->nama ?? 'N/A' }}</p>
                                    <p><strong>Kecamatan:</strong> {{ $ticket->kecamatan->nama ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <strong>Description:</strong>
                                <div class="mt-2 p-3" style="background-color: #f8f9fc; border-radius: 6px; border-left: 4px solid #4e73df;">
                                    {{ $ticket->description }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>Jenis Layanan/Insiden:</strong>
                                <div class="mt-2 p-3" style="background-color: #f8f9fc; border-radius: 6px; border-left: 4px solid #4e73df;">
                                    @if($ticket->layanan_type === 'other' && $ticket->layanan_custom)
                                        {{ $ticket->layanan_custom }}
                                    @else
                                        {{ ucfirst(str_replace('_',' ', $ticket->layanan_type ?? '-')) }}
                                    @endif
                                </div>
                            </div>

                            @if ($ticket->attachment_path)
                                <div class="mb-3">
                                    <strong>Attachment dari Guest:</strong>
                                    <br>
                                    <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank"
                                        class="btn btn-outline-primary btn-sm mt-2">
                                        <i class="fas fa-file"></i> Lihat/Download Lampiran
                                    </a>
                                </div>
                            @endif

                            @if ($ticket->status === 'ditolak/rejected' && $ticket->rejection_reason)
                                <div class="mb-3">
                                    <strong>Alasan Penolakan:</strong>
                                    <div class="mt-2 p-3" style="background-color: #fff5f5; border-radius: 6px; border-left: 4px solid #e74a3b;">
                                        {{ $ticket->rejection_reason }}
                                    </div>
                                </div>
                            @endif

                            @if ($ticket->status === 'selesai/completed' && $ticket->resolution_notes)
                                <div class="mb-3">
                                    <strong>Catatan Penyelesaian:</strong>
                                    <div class="mt-2 p-3" style="background-color: #ecfff4; border-radius: 6px; border-left: 4px solid #1cc88a;">
                                        {{ $ticket->resolution_notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="ticket-title">Actions</h5>
                        </div>
                        <div class="card-body">
                            @if ($ticket->status == 'pending')
                                <form action="{{ route('admin.tickets.accept', $ticket) }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-check"></i> Accept Ticket
                                    </button>
                                </form>
                                <form action="{{ route('admin.tickets.reject', $ticket) }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-2">
                                        <label for="rejection_reason"><strong>Alasan Penolakan</strong></label>
                                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" placeholder="Tuliskan alasan penolakan..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-block">
                                        <i class="fas fa-times"></i> Reject Ticket
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Tiket ini telah diproses.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="ticket-title">Activity Log</h5>
                        </div>
                        <div class="card-body">
                            @if ($ticket->activityLogs->count() > 0)
                                <ul class="list-unstyled">
                                    @foreach ($ticket->activityLogs as $log)
                                        <li>
                                            <div>
                                                <strong class="text-primary">{{ ucfirst($log->action) }}</strong>
                                                <span class="text-muted float-right">
                                                    {{ $log->created_at->format('d M Y H:i') }}
                                                </span>
                                            </div>
                                            <small class="text-muted">{{ $log->description }}</small>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted mb-0">No activity recorded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
