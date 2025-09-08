@extends('layouts.admin')

@section('title', 'Layanan')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Layanan</h1>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-list-alt me-1"></i> Daftar Layanan</span>
                <a href="{{ route('admin.layanan.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
                    Tambah</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>

                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Aktif</th>
                            <th>Kategori Aktif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ Str::limit($item->description, 120) }}</td>
                                <td>
                                    <span
                                        class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </td>
                                <td>
                                    <a class="badge bg-primary text-decoration-none"
                                        href="{{ route('admin.layanan-categories.index', ['layanan_id' => $item->id]) }}"
                                        title="Lihat kategori">
                                        {{ $item->categories_count ?? 0 }} kategori
                                    </a>
                                </td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('admin.layanan.edit', $item) }}" class="btn btn-sm btn-warning"
                                        title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.layanan.destroy', $item) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Hapus layanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="Hapus"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $items->links() }}
            </div>
        </div>
    </div>
@endsection
