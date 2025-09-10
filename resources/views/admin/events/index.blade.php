@extends('layouts.admin')

@section('title', 'Kelola Event/Agenda')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Kelola Event/Agenda</h3>
                        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Event
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="10%">Gambar</th>
                                        <th width="25%">Judul</th>
                                        <th width="20%">Waktu</th>
                                        <th width="20%">Lokasi</th>
                                        <th width="10%">Status</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($events as $index => $item)
                                        <tr>
                                            <td>{{ $events->firstItem() + $index }}</td>
                                            <td>
                                                <div class="ratio ratio-1x1" style="width: 64px;">
                                                    @if ($item->image)
                                                        <img src="{{ asset($item->image) }}" alt="{{ $item->title }}"
                                                            class="rounded object-fit-cover w-100 h-100">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center rounded border"
                                                            style="font-size: 0.9rem;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $item->title }}</strong>
                                                <div class="text-muted small">{{ Str::limit($item->summary, 80) }}</div>
                                            </td>
                                            <td>
                                                {{ optional($item->start_at)->format('d/m/Y H:i') }}
                                                @if ($item->end_at)
                                                    - {{ optional($item->end_at)->format('d/m/Y H:i') }}
                                                @endif
                                            </td>
                                            <td>{{ $item->location }}</td>
                                            <td>
                                                @if ($item->is_published)
                                                    <span class="badge bg-success">Dipublikasi</span>
                                                @else
                                                    <span class="badge bg-secondary">Draft</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.events.show', $item) }}"
                                                        class="btn btn-sm btn-info" title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.events.edit', $item) }}"
                                                        class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.events.destroy', $item) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada event</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $events->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
