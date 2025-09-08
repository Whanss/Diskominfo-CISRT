@extends('layouts.admin')

@section('title', 'Tambah Kategori Layanan')

@section('content')
    <div class="container-fluid px-4" style="padding: 24px; max-width: 800px;">
        <div class="card"
            style="border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);">
            <div class="card-header" style="background:white; border-bottom:1px solid #e5e7eb;">
                <h5 style="margin:0;">Tambah Kategori Layanan</h5>
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <div><strong>Periksa kembali input Anda:</strong></div>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.layanan-categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Layanan <span class="text-danger">*</span></label>
                        <select name="layanan_id" class="form-select" required>
                            <option value="">Pilih Layanan</option>
                            @foreach ($layananList as $l)
                                <option value="{{ $l->id }}"
                                    {{ old('layanan_id', request('layanan_id')) == $l->id ? 'selected' : '' }}>
                                    {{ $l->name }}</option>
                            @endforeach
                        </select>
                        @error('layanan_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.layanan-categories.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
