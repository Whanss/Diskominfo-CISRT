@extends('admin.layouts.app')

@section('title', 'Detail Berita')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Berita</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('guest.news.show', $news->slug) }}" class="btn btn-info" target="_blank">
                                <i class="fas fa-eye"></i> Lihat di Website
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h2>{{ $news->title }}</h2>

                                <div class="mb-3">
                                    <span class="badge bg-{{ $news->category_badge_class }} me-2">
                                        {{ $news->category_label }}
                                    </span>
                                    @if ($news->is_published)
                                        <span class="badge bg-success">Dipublikasi</span>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </div>

                                @if ($news->excerpt)
                                    <div class="alert alert-info">
                                        <strong>Ringkasan:</strong> {{ $news->excerpt }}
                                    </div>
                                @endif

                                @if ($news->image)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}"
                                            class="img-fluid rounded">
                                    </div>
                                @endif

                                <div class="content">
                                    {!! nl2br(e($news->content)) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Informasi Berita</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Slug:</strong></td>
                                                <td>{{ $news->slug }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kategori:</strong></td>
                                                <td>{{ $news->category_label }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>
                                                    @if ($news->is_published)
                                                        <span class="badge bg-success">Dipublikasi</span>
                                                    @else
                                                        <span class="badge bg-secondary">Draft</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Dibuat:</strong></td>
                                                <td>{{ $news->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Diupdate:</strong></td>
                                                <td>{{ $news->updated_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            @if ($news->image)
                                                <tr>
                                                    <td><strong>Gambar:</strong></td>
                                                    <td>
                                                        <a href="{{ asset('storage/' . $news->image) }}" target="_blank">
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
                                            <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-warning">
                                                <i class="fas fa-edit"></i> Edit Berita
                                            </a>
                                            <a href="{{ route('guest.news.show', $news->slug) }}" class="btn btn-info"
                                                target="_blank">
                                                <i class="fas fa-eye"></i> Lihat di Website
                                            </a>
                                            <form action="{{ route('admin.news.destroy', $news) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger w-100">
                                                    <i class="fas fa-trash"></i> Hapus Berita
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
