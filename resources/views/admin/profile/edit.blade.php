@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
<div class="container px-4 py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-4" style="border-radius:12px; box-shadow: var(--shadow-md);">
        <div class="card-body">
            <h5 class="mb-3">Informasi Profil</h5>
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3 text-center">
                        <div style="width:120px;height:120px;border-radius:16px;overflow:hidden;margin:0 auto;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                            @if($admin->avatar_path)
                                <img src="{{ Storage::url($admin->avatar_path) }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;" />
                            @else
                                <div style="font-size:48px;font-weight:700;color:#475569;">{{ strtoupper(substr($admin->name,0,1)) }}</div>
                            @endif
                        </div>
                        <input type="file" name="avatar" class="form-control mt-3" accept="image/*">
                        <small class="text-muted d-block">Maks 2MB</small>
                    </div>
                    <div class="col-md-9">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $admin->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username', $admin->username) }}" placeholder="opsional">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" required>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card" style="border-radius:12px; box-shadow: var(--shadow-md);">
        <div class="card-body">
            <h5 class="mb-3">Ganti Kata Sandi</h5>
            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Kata sandi saat ini</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kata sandi baru</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Konfirmasi kata sandi baru</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button class="btn btn-secondary" type="submit">Perbarui Kata Sandi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection