@extends('admin.partials.layout')

@section('title', 'Edit Pegawai - JABLAYMEN')
@section('page-title', 'Edit Data Pegawai')

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
                    <i class="fas fa-edit me-2 text-warning"></i> Edit Pegawai: {{ $pegawai->nama }}
                </h5>
                <a href="{{ route('pegawai.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            @if($pegawai->user_id)
            <div class="info-box">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                    <div>
                        <small>Pegawai ini sudah memiliki akun login dengan email: <strong>{{ $pegawai->user->email ?? '-' }}</strong></small>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- NIP -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" 
                               value="{{ old('nip', $pegawai->nip) }}" required>
                        @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Nama -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                               value="{{ old('nama', $pegawai->nama) }}" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Tempat Lahir -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                               value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}" required>
                        @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                               value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir->format('Y-m-d')) }}" required>
                        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Jenis Kelamin -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                            <option value="L" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Unit Kerja -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                        <select name="unit_kerja_id" class="form-select @error('unit_kerja_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach($unitKerjas as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_kerja_id', $pegawai->unit_kerja_id) == $unit->id ? 'selected' : '' }}>
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
                               value="{{ old('golongan', $pegawai->golongan) }}" placeholder="Contoh: III/a, IV/b">
                    </div>

                    <!-- Jabatan -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" 
                               value="{{ old('jabatan', $pegawai->jabatan) }}" placeholder="Jabatan pegawai">
                    </div>
                </div>

                <div class="row">
                    <!-- No HP -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="no_hp" class="form-control" 
                               value="{{ old('no_hp', $pegawai->no_hp) }}" placeholder="Contoh: 081234567890">
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="aktif" {{ old('status', $pegawai->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="pensiun" {{ old('status', $pegawai->status) == 'pensiun' ? 'selected' : '' }}>Pensiun</option>
                            <option value="mutasi" {{ old('status', $pegawai->status) == 'mutasi' ? 'selected' : '' }}>Mutasi</option>
                            <option value="berhenti" {{ old('status', $pegawai->status) == 'berhenti' ? 'selected' : '' }}>Berhenti</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-3">
                    <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-2"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i> Update Pegawai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection