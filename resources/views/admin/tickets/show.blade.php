@extends('layouts.admin')

@section('breadcrumb', 'Ticket Details')
@section('page-title', 'Ticket Details - ' . $ticket->code_tracking)

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Ticket Details</h4>
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Ticket Information</h5>
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
                                        class="badge badge-sm
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
                            <p class="mt-2">{{ $ticket->description }}</p>
                        </div>

                        @if ($ticket->attachment_path)
                            <div class="mb-3">
                                <strong>Attachment:</strong>
                                <br>
                                <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-file"></i> View Attachment
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
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
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fas fa-times"></i> Reject Ticket
                                </button>
                            </form>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> This ticket has already been processed.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Activity Log</h5>
                    </div>
                    <div class="card-body">
                        @if ($ticket->activityLogs->count() > 0)
                            <ul class="list-unstyled">
                                @foreach ($ticket->activityLogs as $log)
                                    <li class="mb-2">
                                        <small>
                                            <strong>{{ ucfirst($log->action) }}</strong> -
                                            {{ $log->description }}<br>
                                            <span class="text-muted">{{ $log->created_at->format('d M Y H:i') }}</span>
                                        </small>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No activity recorded yet.</p>
                        @endif
                    </div>
                </div>
            </div>
