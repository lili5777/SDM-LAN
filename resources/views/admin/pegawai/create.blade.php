@extends('admin.partials.layout')

@section('title', 'Tambah Pegawai - JABLAYMEN')
@section('page-title', 'Tambah Pegawai Baru')

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
    .info-box {
        background: #f0fdf6;
        border: 1px solid #bbf7d2;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="form-card">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2 text-success"></i> Form Tambah Pegawai
                </h5>
                <a href="{{ route('pegawai.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="info-box">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-info-circle fa-2x text-success"></i>
                    <div>
                        <small>Setelah menambahkan pegawai, Anda dapat membuat akun login untuk pegawai tersebut</small>
                    </div>
                </div>
            </div>

            <form action="{{ route('pegawai.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- NIP -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" 
                               value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai" required>
                        @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Nama -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                               value="{{ old('nama') }}" placeholder="Nama lengkap pegawai" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Tempat Lahir -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                               value="{{ old('tempat_lahir') }}" required>
                        @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                               value="{{ old('tanggal_lahir') }}" required>
                        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Jenis Kelamin -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Unit Kerja -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                        <select name="unit_kerja_id" class="form-select @error('unit_kerja_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach($unitKerjas as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama }}
                            </option>
                            @endforeach
                        </select>
                        @error('unit_kerja_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Golongan -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Golongan</label>
                        <input type="text" name="golongan" class="form-control" 
                               value="{{ old('golongan') }}" placeholder="Contoh: III/a, IV/b">
                    </div>

                    <!-- Jabatan -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" 
                               value="{{ old('jabatan') }}" placeholder="Jabatan pegawai">
                    </div>
                </div>

                <div class="row">
                    <!-- No HP -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="no_hp" class="form-control" 
                               value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890">
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="pensiun" {{ old('status') == 'pensiun' ? 'selected' : '' }}>Pensiun</option>
                            <option value="mutasi" {{ old('status') == 'mutasi' ? 'selected' : '' }}>Mutasi</option>
                            <option value="berhenti" {{ old('status') == 'berhenti' ? 'selected' : '' }}>Berhenti</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-3">
                    <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-2"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i> Simpan Pegawai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection