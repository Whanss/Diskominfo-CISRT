@extends('layouts.app')

@section('content')

    <style>
        .auth-page {
            min-height: calc(100vh - 120px);
            background: linear-gradient(135deg, #1b212a 0%, #2d3748 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        .auth-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(26, 54, 93, 0.15);
            overflow: hidden;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .auth-header .logo {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: #e6eefc;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            color: #1b212a;
        }

        .auth-title {
            font-weight: 700;
            margin: 0;
        }

        .auth-subtitle {
            color: #64748b;
            margin: 6px 0 0;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
        }

        .input-group-text {
            background: #f8fafc;
        }

        .btn-primary {
            background-color: #1b212a;
            border-color: #1b212a;
        }

        .btn-primary:hover {
            background-color: #2c3a4c;
            border-color: #2c3a4c;
        }
    </style>

    <section class="auth-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="card auth-card">
                        <div class="card-body p-4 p-lg-5">

                            <div class="auth-header">
                                <div class="logo">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <h3 class="auth-title">Masuk Admin</h3>
                                <p class="auth-subtitle">Login untuk mengakses dashboard admin</p>
                            </div>

                            @if (session('status'))
                                <div class="alert alert-success py-2" role="alert">{{ session('status') }}</div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('admin.login.submit') }}" method="POST" novalidate>
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email') }}" required autofocus placeholder="you@example.com">
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required
                                            placeholder="••••••••">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword"
                                            aria-label="Show password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Login Admin
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Toggle show/hide password
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('togglePassword');
            const input = document.getElementById('password');
            if (toggle && input) {
                toggle.addEventListener('click', function() {
                    const isPassword = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPassword ? 'text' : 'password');
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('bi-eye');
                        icon.classList.toggle('bi-eye-slash');
                    }
                });
            }
        });
    </script>

@endsection
