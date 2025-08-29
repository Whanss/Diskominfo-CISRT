@extends('layouts.admin')

@section('page-title', 'Edit Kabupaten')
@section('breadcrumb', 'Edit Kabupaten')

@section('content')
<div class="container-fluid py-4">
    <h3>Edit Kabupaten</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kabupaten.update', $kabupaten) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Kabupaten</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $kabupaten->nama) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.kabupaten.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
