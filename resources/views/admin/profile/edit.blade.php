@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
    <div class="container px-4 py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4" style="border-radius:12px; box-shadow: var(--shadow-md);">
            <div class="card-body">
                <h5 class="mb-3">Informasi Profil</h5>
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3 text-center">
                            <!-- Simplified avatar container with direct preview -->
                            <div class="avatar-container" style="width:160px;height:160px;border-radius:16px;overflow:hidden;margin:0 auto;background:#f3f4f6;display:flex;align-items:center;justify-content:center;border:2px solid #e5e7eb;position:relative;">
                                <img id="avatarPreview" 
                                     src="{{ $admin->avatar_path ? Storage::url($admin->avatar_path) : '' }}" 
                                     alt="Avatar" 
                                     style="display: {{ $admin->avatar_path ? 'block' : 'none' }}; width:100%;height:100%;object-fit:cover;" />
                                @unless ($admin->avatar_path)
                                    <div id="avatarInitial" style="font-size:48px;font-weight:700;color:#475569;">
                                        {{ strtoupper(substr($admin->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endunless
                                <!-- Added overlay for better UX -->
                                <div class="avatar-overlay" style="position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);display:none;align-items:center;justify-content:center;color:white;font-size:14px;cursor:pointer;">
                                    <i class="fas fa-camera"></i><br>
                                    <small>Ubah Foto</small>
                                </div>
                            </div>
                            
                            <!-- Simplified file input with immediate preview -->
                            <input type="file" id="avatarInput" name="avatar" class="form-control mt-3" accept="image/jpeg,image/jpg,image/png,image/webp" style="display:none;">
                            <button type="button" id="changeAvatarBtn" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="fas fa-camera me-1"></i>Pilih Foto
                            </button>
                            <button type="button" id="cropAvatarBtn" class="btn btn-primary btn-sm mt-2" style="display:none;">
                                <i class="fas fa-crop me-1"></i>Potong Foto
                            </button>
                            <button type="button" id="removeAvatarBtn" class="btn btn-outline-danger btn-sm mt-2" style="display:{{ $admin->avatar_path ? 'inline-block' : 'none' }};">
                                <i class="fas fa-trash me-1"></i>Hapus
                            </button>
                            
                            <input type="hidden" id="avatarCropped" name="avatar_cropped" />
                            <input type="hidden" id="removeAvatar" name="remove_avatar" value="0" />
                            <small class="text-muted d-block mt-2">Maks 2 MB. Format: JPG, PNG, WebP.</small>

                            <!-- Simplified crop modal -->
                            <div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Potong Foto Profil</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <div id="cropContainer" style="width:100%;height:300px;background:#f8f9fa;border-radius:8px;overflow:hidden;position:relative;margin-bottom:15px;">
                                                <img id="cropImage" style="width:100%;height:100%;object-fit:contain;">
                                            </div>
                                            <div class="crop-controls">
                                                <button type="button" id="zoomIn" class="btn btn-sm btn-outline-secondary me-2">
                                                    <i class="fas fa-search-plus"></i>
                                                </button>
                                                <button type="button" id="zoomOut" class="btn btn-sm btn-outline-secondary me-2">
                                                    <i class="fas fa-search-minus"></i>
                                                </button>
                                                <button type="button" id="resetCrop" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="button" id="applyCrop" class="btn btn-primary">Terapkan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="mb-3">
                                <label class="form-label" for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $admin->name ?? '') }}" required maxlength="255">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror"
                                       value="{{ old('username', $admin->username ?? '') }}" placeholder="Opsional" maxlength="50">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $admin->email ?? '') }}" required maxlength="255">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="text-end">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-save me-1"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card" style="border-radius:12px; box-shadow: var(--shadow-md);">
            <div class="card-body">
                <h5 class="mb-3">Ganti Kata Sandi</h5>
                <form action="{{ route('admin.profile.password') }}" method="POST" id="passwordForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="current_password">Kata sandi saat ini <span class="text-danger">*</span></label>
                            <input type="password" id="current_password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="password">Kata sandi baru <span class="text-danger">*</span></label>
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror" required minlength="8">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="password_confirmation">Konfirmasi kata sandi baru <span class="text-danger">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-control" required minlength="8">
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button class="btn btn-secondary" type="submit">
                            <i class="fas fa-key me-1"></i>
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css" rel="stylesheet">
        <style>
            /* Simplified styles for better UX */
            .avatar-container {
                transition: all 0.3s ease;
                cursor: pointer;
            }
            .avatar-container:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }
            .avatar-container:hover .avatar-overlay {
                display: flex !important;
            }
            .cropper-container {
                max-width: 100% !important;
                max-height: 280px !important;
            }
            .cropper-canvas {
                max-width: 100% !important;
                max-height: 280px !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const avatarInput = document.getElementById('avatarInput');
                const avatarPreview = document.getElementById('avatarPreview');
                const avatarInitial = document.getElementById('avatarInitial');
                const avatarContainer = document.querySelector('.avatar-container');
                const changeBtn = document.getElementById('changeAvatarBtn');
                const cropBtn = document.getElementById('cropAvatarBtn');
                const removeBtn = document.getElementById('removeAvatarBtn');
                const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
                const cropImage = document.getElementById('cropImage');
                const applyCropBtn = document.getElementById('applyCrop');
                const avatarCropped = document.getElementById('avatarCropped');
                const removeAvatar = document.getElementById('removeAvatar');
                
                let cropper = null;
                let currentFile = null;

                // Simple file validation
                function validateFile(file) {
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    const maxSize = 2 * 1024 * 1024; // 2MB
                    
                    if (!validTypes.includes(file.type)) {
                        alert('Format file harus JPG, PNG, atau WebP');
                        return false;
                    }
                    
                    if (file.size > maxSize) {
                        alert('Ukuran file maksimal 2MB');
                        return false;
                    }
                    
                    return true;
                }

                // Show immediate preview
                function showPreview(file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatarPreview.src = e.target.result;
                        avatarPreview.style.display = 'block';
                        if (avatarInitial) avatarInitial.style.display = 'none';
                        cropBtn.style.display = 'inline-block';
                        removeBtn.style.display = 'inline-block';
                        
                        // Prepare crop image
                        cropImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }

                // Event listeners
                avatarContainer.addEventListener('click', () => changeBtn.click());
                changeBtn.addEventListener('click', () => avatarInput.click());

                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    
                    if (!validateFile(file)) {
                        avatarInput.value = '';
                        return;
                    }
                    
                    currentFile = file;
                    showPreview(file);
                    avatarCropped.value = ''; // Reset cropped data
                    removeAvatar.value = '0';
                });

                cropBtn.addEventListener('click', function() {
                    if (!currentFile) return;
                    cropModal.show();
                });

                removeBtn.addEventListener('click', function() {
                    avatarPreview.style.display = 'none';
                    if (avatarInitial) avatarInitial.style.display = 'flex';
                    cropBtn.style.display = 'none';
                    removeBtn.style.display = 'none';
                    avatarInput.value = '';
                    avatarCropped.value = '';
                    removeAvatar.value = '1';
                    currentFile = null;
                });

                // Cropper modal events
                document.getElementById('cropModal').addEventListener('shown.bs.modal', function() {
                    if (cropper) cropper.destroy();
                    
                    cropper = new Cropper(cropImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 0.8,
                        responsive: true,
                        background: false
                    });
                });

                document.getElementById('cropModal').addEventListener('hidden.bs.modal', function() {
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                });

                // Crop controls
                document.getElementById('zoomIn').addEventListener('click', () => cropper?.zoom(0.1));
                document.getElementById('zoomOut').addEventListener('click', () => cropper?.zoom(-0.1));
                document.getElementById('resetCrop').addEventListener('click', () => cropper?.reset());

                applyCropBtn.addEventListener('click', function() {
                    if (!cropper) return;
                    
                    const canvas = cropper.getCroppedCanvas({
                        width: 300,
                        height: 300,
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high'
                    });
                    
                    canvas.toBlob(function(blob) {
                        const reader = new FileReader();
                        reader.onload = function() {
                            avatarCropped.value = reader.result;
                            avatarPreview.src = reader.result;
                            avatarInput.value = ''; // Clear file input since we're using cropped data
                            cropModal.hide();
                        };
                        reader.readAsDataURL(blob);
                    }, 'image/jpeg', 0.9);
                });

                // Form validation
                document.getElementById('profileForm').addEventListener('submit', function(e) {
                    const name = document.getElementById('name').value.trim();
                    const email = document.getElementById('email').value.trim();
                    
                    if (!name || !email) {
                        e.preventDefault();
                        alert('Nama dan email harus diisi');
                        return;
                    }
                });

                // Password form validation
                document.getElementById('passwordForm').addEventListener('submit', function(e) {
                    const password = document.getElementById('password').value;
                    const confirmation = document.getElementById('password_confirmation').value;
                    
                    if (password !== confirmation) {
                        e.preventDefault();
                        alert('Konfirmasi kata sandi tidak cocok');
                        return;
                    }
                });
            });
        </script>
    @endpush
@endsection
