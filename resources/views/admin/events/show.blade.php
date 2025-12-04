@extends('layouts.admin')

@section('title', 'Detail Event')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Event</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h2>{{ $event->title }}</h2>

                                <div class="mb-3">
                                    @if ($event->is_published)
                                        <span class="badge bg-success">Dipublikasi</span>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </div>

                                @if ($event->summary)
                                    <div class="alert alert-info">
                                        <strong>Ringkasan:</strong> {{ $event->summary }}
                                    </div>
                                @endif

                                @if ($event->image)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}"
                                            class="img-fluid rounded">
                                    </div>
                                @endif

                                <div class="content">
                                    {!! nl2br(e($event->description)) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Informasi Event</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Judul:</strong></td>
                                                <td>{{ $event->title }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Lokasi:</strong></td>
                                                <td>{{ $event->location }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Waktu Mulai:</strong></td>
                                                <td>{{ optional($event->start_at)->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            @if($event->end_at)
                                            <tr>
                                                <td><strong>Waktu Selesai:</strong></td>
                                                <td>{{ optional($event->end_at)->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>
                                                    @if ($event->is_published)
                                                        <span class="badge bg-success">Dipublikasi</span>
                                                    @else
                                                        <span class="badge bg-secondary">Draft</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Dibuat:</strong></td>
                                                <td>{{ $event->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Diupdate:</strong></td>
                                                <td>{{ $event->updated_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            @if ($event->image)
                                                <tr>
                                                    <td><strong>Gambar:</strong></td>
                                                    <td>
                                                        <a href="{{ asset('storage/' . $event->image) }}" target="_blank">
                                                            Lihat Gambar
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5>Aksi</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning">
                                                <i class="fas fa-edit"></i> Edit Event
                                            </a>
                                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger w-100">
                                                    <i class="fas fa-trash"></i> Hapus Event
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
