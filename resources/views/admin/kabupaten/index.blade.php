@extends('layouts.admin')

@section('page-title', 'Manage Kabupaten')
@section('breadcrumb', 'Manage Kabupaten')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Daftar Kabupaten</h3>
        <a href="{{ route('admin.kabupaten.create') }}" class="btn btn-primary">Tambah Kabupaten</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama Kabupaten</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kabupatens as $kabupaten)
            <tr>
                <td>{{ $kabupaten->nama }}</td>
                <td>
                    <a href="{{ route('admin.kabupaten.edit', $kabupaten) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.kabupaten.destroy', $kabupaten) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus kabupaten ini?');">
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
