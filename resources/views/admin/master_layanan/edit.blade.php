@extends('layouts.admin')

@section('title','Edit Master Layanan')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Layanan</h1>
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.master-layanan.update', $item) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
                    @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description) }}</textarea>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ $item->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.master-layanan.index') }}" class="btn btn-secondary">Batal</a>
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection