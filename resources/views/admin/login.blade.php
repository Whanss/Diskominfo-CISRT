@extends('layouts.app')

@section('content')
<div class="bg-light rounded-4 py-5 px-4 px-md-5 container mt-5">
    <div class="text-center mb-5">
        <div class="feature bg-primary bg-gradient-primary-to-secondary text-white rounded-3 mb-3">
            <i class="bi bi-envelope"></i>
        </div>
        <h1 class="fw-bolder">Admin Login</h1>
        <p class="lead fw-normal text-muted mb-0">Please sign in to your account</p>
    </div>
    <div class="row gx-5 justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <form action="{{ route('admin.login.submit') }}" method="POST" id="adminLoginForm">
                @csrf
                <div class="form-floating mb-3">
                    <input class="form-control" id="email" type="email" name="email" placeholder="name@example.com" required autofocus />
                    <label for="email">Email address</label>
                    @error('email')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" id="password" type="password" name="password" placeholder="Password" required />
                    <label for="password">Password</label>
                    @error('password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary btn-lg" id="submitButton" type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
