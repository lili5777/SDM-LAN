@extends('admin.partials.layout')

@section('title', 'Tambah Unit Kerja - JABLAYMEN')
@section('page-title', 'Tambah Unit Kerja')

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
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="form-card">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle me-2 text-success"></i> Form Tambah Unit Kerja
                </h5>
                <a href="{{ route('unit-kerja.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <form action="{{ route('unit-kerja.store') }}" method="POST">
                @csrf

                <!-- Kode -->
                <div class="mb-4">
                    <label class="form-label">Kode Unit <span class="text-danger">*</span></label>
                    <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" 
                           value="{{ old('kode') }}" placeholder="Contoh: PUSJAR, SKMP, UMUM" required>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <small class="text-muted">Kode harus unik dan tidak mengandung spasi</small>
                </div>

                <!-- Nama -->
                <div class="mb-4">
                    <label class="form-label">Nama Unit Kerja <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                           value="{{ old('nama') }}" placeholder="Nama lengkap unit kerja" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                              rows="4" placeholder="Deskripsi singkat tentang unit kerja ini">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-3">
                    <a href="{{ route('unit-kerja.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-2"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i> Simpan Unit Kerja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection