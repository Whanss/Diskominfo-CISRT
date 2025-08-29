@extends('layouts.admin')

@section('page-title', 'Manage Kecamatan')
@section('breadcrumb', 'Manage Kecamatan')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Daftar Kecamatan</h3>
        <a href="{{ route('admin.kecamatan.create') }}" class="btn btn-primary">Tambah Kecamatan</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama Kecamatan</th>
                <th>Kabupaten</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kecamatans as $kecamatan)
            <tr>
                <td>{{ $kecamatan->nama }}</td>
                <td>{{ $kecamatan->kabupaten->nama }}</td>
                <td>
                    <a href="{{ route('admin.kecamatan.edit', $kecamatan) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.kecamatan.destroy', $kecamatan) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus kecamatan ini?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
