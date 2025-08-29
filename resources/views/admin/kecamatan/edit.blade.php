@extends('layouts.admin')

@section('page-title', 'Edit Kecamatan')
@section('breadcrumb', 'Edit Kecamatan')

@section('content')
<div class="container-fluid py-4">
    <h3>Edit Kecamatan</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kecamatan.update', $kecamatan) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Kecamatan</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $kecamatan->nama) }}" required>
        </div>
        <div class="mb-3">
            <label for="kabupaten_id" class="form-label">Kabupaten</label>
            <select class="form-control" id="kabupaten_id" name="kabupaten_id" required>
                <option value="">Pilih Kabupaten</option>
                @foreach($kabupatens as $kabupaten)
                    <option value="{{ $kabupaten->id }}" {{ old('kabupaten_id', $kecamatan->kabupaten_id) == $kabupaten->id ? 'selected' : '' }}>{{ $kabupaten->nama }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.kecamatan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
