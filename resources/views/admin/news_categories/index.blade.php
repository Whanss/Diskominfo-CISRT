@extends('layouts.admin')

@section('title', 'Kategori Berita')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Kategori Berita</h1>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-tags me-1"></i> Daftar Kategori</span>
                <a href="{{ route('admin.news-categories.create') }}" class="btn btn-primary btn-sm"><i
                        class="fas fa-plus"></i> Tambah</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Aktif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <span
                                        class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.news-categories.edit', $category) }}"
                                        class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.news-categories.destroy', $category) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
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
                <div class="pagination-wrapper mt-3">
                    {{ $categories->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Compact, clean pagination for admin */
            .pagination-wrapper {
                display: flex;
                justify-content: center;
                margin-top: 1rem;
            }

            .pagination {
                margin: 0;
                gap: 0.25rem;
                flex-wrap: wrap;
            }

            .pagination .page-item {
                margin: 0;
            }

            .pagination .page-link {
                border-radius: 8px;
                padding: 6px 10px;
                min-width: 38px;
                text-align: center;
                font-weight: 600;
                font-size: 0.875rem;
                /* smaller text */
                line-height: 1.2;
                color: #1a365d;
                border: 1px solid #e2e8f0;
                background: #fff;
                position: relative;
            }

            /* If icons exist, keep them small */
            .pagination .page-link i,
            .pagination .page-link svg {
                font-size: 0.9rem;
                width: 0.9rem;
                height: 0.9rem;
                vertical-align: middle;
            }

            .pagination .page-link:hover {
                color: #fff;
                background-color: #1a365d;
                border-color: #1a365d;
            }

            .pagination .page-item.active .page-link {
                color: #fff;
                background: linear-gradient(135deg, #1a365d 0%, #2c5282 100%);
                border-color: #1a365d;
            }

            .pagination .page-item.disabled .page-link {
                color: #94a3b8;
                background-color: #f8fafc;
                border-color: #e2e8f0;
            }

            /* Keep Previous/Next compact (Bootstrap uses text by default) */
            .pagination .page-link[aria-label*="Previous"],
            .pagination .page-link[aria-label*="Next"] {
                min-width: 44px;
                padding: 6px 10px;
                font-size: 0.875rem;
                font-weight: 600;
            }

            @media (max-width: 576px) {
                .pagination .page-link {
                    padding: 6px 8px;
                    min-width: 34px;
                }
            }
        </style>
    @endpush
@endsection
