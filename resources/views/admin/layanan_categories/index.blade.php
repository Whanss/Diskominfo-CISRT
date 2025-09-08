@extends('layouts.admin')

@section('title', 'Kategori Layanan')

@section('content')
    <div class="container-fluid px-4" style="padding: 24px;">
        <div class="card"
            style="border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);">
            <div class="card-header d-flex justify-content-between align-items-center"
                style="background:white; border-bottom:1px solid #e5e7eb;">
                <h5 style="margin:0;">Kategori Layanan</h5>
                <a href="{{ route('admin.layanan-categories.create') }}" class="btn btn-primary">Tambah</a>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="GET" class="row g-2 mb-3">
                    <div class="col-auto">
                        <select name="layanan_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Layanan</option>
                            @foreach ($layananList as $l)
                                <option value="{{ $l->id }}" {{ request('layanan_id') == $l->id ? 'selected' : '' }}>
                                    {{ $l->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (request('layanan_id'))
                        <div class="col-auto">
                            <a href="{{ route('admin.layanan-categories.index') }}"
                                class="btn btn-sm btn-outline-secondary">Reset</a>
                        </div>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Layanan</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $i => $cat)
                                <tr>
                                    <td>{{ $categories->firstItem() + $i }}</td>
                                    <td>{{ $cat->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.layanan.index') }}?q={{ urlencode($cat->layanan->name ?? '') }}"
                                            class="text-decoration-none">
                                            {{ $cat->layanan->name ?? '-' }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge {{ $cat->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $cat->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>{{ $cat->created_at?->format('Y-m-d') }}</td>
                                    <td class="d-flex gap-2">
                                        <a href="{{ route('admin.layanan-categories.edit', $cat) }}"
                                            class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.layanan-categories.destroy', $cat) }}" method="POST"
                                            onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $categories->links() }}
            </div>
        </div>
    </div>
@endsection
