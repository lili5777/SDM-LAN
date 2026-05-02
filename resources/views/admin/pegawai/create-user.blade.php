@extends('admin.partials.layout')

@section('title', 'Buat Akun Pegawai - JABLAYMEN')
@section('page-title', 'Buat Akun untuk Pegawai')

@section('styles')
<style>
    .form-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 1.5rem;
    }
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .info-card {
        background: #f0fdf6;
        border: 1px solid #bbf7d2;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    .password-strength {
        height: 4px;
        background: #e2e8f0;
        border-radius: 4px;
        margin-top: 8px;
        overflow: hidden;
    }
    .password-strength-bar {
        width: 0%;
        height: 100%;
        transition: all 0.3s;
    }
    .password-strength-bar.weak { width: 33%; background: #dc2626; }
    .password-strength-bar.medium { width: 66%; background: #f59e0b; }
    .password-strength-bar.strong { width: 100%; background: #10b981; }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="form-card">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2 text-success"></i> Buat Akun untuk {{ $pegawai->nama }}
                </h5>
                <a href="{{ route('pegawai.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <!-- Info Pegawai -->
            <div class="info-card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="small text-muted">NIP</div>
                        <div class="fw-semibold">{{ $pegawai->nip }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Unit Kerja</div>
                        <div class="fw-semibold">{{ $pegawai->unitKerja->nama ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <form action="{{ route('pegawai.store-user', $pegawai->id) }}" method="POST">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-envelope text-success"></i>
                        </span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" placeholder="email@domain.com" required>
                    </div>
                    @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    <small class="text-muted">Email akan digunakan untuk login ke sistem</small>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-lock text-success"></i>
                        </span>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Minimal 8 karakter" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrength"></div>
                    </div>
                    <small class="text-muted">Password minimal 8 karakter dengan kombinasi huruf dan angka</small>
                </div>

                <!-- Konfirmasi Password -->
                <div class="mb-4">
                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-lock text-success"></i>
                        </span>
                        <input type="password" name="password_confirmation" class="form-control" 
                               placeholder="Ulangi password" required>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Setelah akun dibuat, pegawai dapat login menggunakan email dan password di atas.
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-3">
                    <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-2"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i> Buat Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
    
    // Password strength checker
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.getElementById('passwordStrength');
        
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;
        
        strengthBar.className = 'password-strength-bar';
        if (strength >= 3) {
            strengthBar.classList.add('strong');
        } else if (strength >= 2) {
            strengthBar.classList.add('medium');
        } else if (strength >= 1) {
            strengthBar.classList.add('weak');
        }
    });
</script>
@endsection