@extends('admin.partials.layout')

@section('title', 'Profil Saya - JABLAYMEN')
@section('page-title', 'Profil Saya')

@section('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #166534 0%, #15803d 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }
    .profile-avatar {
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        border: 3px solid white;
    }
    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }
    .profile-card .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
        color: #166534;
    }
    .info-row {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px dashed #e2e8f0;
    }
    .info-label {
        width: 140px;
        font-weight: 500;
        color: #64748b;
    }
    .info-value {
        flex: 1;
        color: #0f172a;
    }
    .btn-upload-photo {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        transition: all 0.3s;
    }
    .btn-upload-photo:hover {
        background: #166534;
        color: white;
        border-color: #166534;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Profile Photo -->
        <div class="profile-card text-center">
            <div class="profile-avatar mx-auto mb-3">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <h4>{{ $user->name }}</h4>
            <p class="text-muted small">{{ $user->role->name ?? '-' }}</p>
            <p class="text-muted small">
                <i class="fas fa-envelope me-1"></i> {{ $user->email }}
            </p>
            
            <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                @csrf
                <label class="btn-upload-photo btn w-100">
                    <i class="fas fa-camera me-2"></i> Ganti Foto
                    <input type="file" name="foto" style="display: none;" onchange="this.form.submit()">
                </label>
            </form>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Profile Information -->
        <div class="profile-card">
            <h5 class="card-title">
                <i class="fas fa-user me-2 text-success"></i> Informasi Profil
            </h5>
            
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="no_telp" class="form-control" 
                           value="{{ old('no_telp', $user->no_telp) }}">
                </div>
                
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i> Update Profil
                </button>
            </form>
        </div>
        
        <!-- Change Password -->
        <div class="profile-card">
            <h5 class="card-title">
                <i class="fas fa-lock me-2 text-success"></i> Ubah Password
            </h5>
            
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Password Saat Ini</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control" required>
                    <small class="text-muted">Minimal 8 karakter</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-key me-2"></i> Ubah Password
                </button>
            </form>
        </div>
        
        @if($pegawai)
        <!-- Employee Information -->
        <div class="profile-card">
            <h5 class="card-title">
                <i class="fas fa-id-card me-2 text-success"></i> Data Kepegawaian
            </h5>
            
            <div class="info-row">
                <div class="info-label">NIP</div>
                <div class="info-value">{{ $pegawai->nip ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tempat, Tanggal Lahir</div>
                <div class="info-value">{{ $pegawai->tempat_lahir ?? '-' }}, {{ $pegawai->tanggal_lahir ? date('d/m/Y', strtotime($pegawai->tanggal_lahir)) : '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Jenis Kelamin</div>
                <div class="info-value">{{ $pegawai->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Golongan</div>
                <div class="info-value">{{ $pegawai->golongan ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Jabatan</div>
                <div class="info-value">{{ $pegawai->jabatan ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Unit Kerja</div>
                <div class="info-value">{{ $pegawai->unitKerja->nama ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <span class="badge bg-success">{{ ucfirst($pegawai->status) }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection