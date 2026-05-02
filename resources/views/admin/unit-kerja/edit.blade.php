@extends('admin.partials.layout')

@section('title', 'Edit Unit Kerja - JABLAYMEN')
@section('page-title', 'Edit Unit Kerja')

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
    <div class="col-md-6 mx-auto">
        <div class="form-card">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2 text-warning"></i> Edit Unit Kerja: {{ $unitKerja->nama }}
                </h5>
                <a href="{{ route('unit-kerja.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            @if($unitKerja->pegawais_count > 0)
            <div class="info-box">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-info-circle fa-2x text-success"></i>
                    <div>
                        <small>Unit ini memiliki <strong>{{ $unitKerja->pegawais_count }}</strong> pegawai. Perubahan nama unit akan otomatis tercermin pada data pegawai.</small>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('unit-kerja.update', $unitKerja->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Kode -->
                <div class="mb-4">
                    <label class="form-label">Kode Unit <span class="text-danger">*</span></label>
                    <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" 
                           value="{{ old('kode', $unitKerja->kode) }}" required>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Nama -->
                <div class="mb-4">
                    <label class="form-label">Nama Unit Kerja <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                           value="{{ old('nama', $unitKerja->nama) }}" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                              rows="4">{{ old('deskripsi', $unitKerja->deskripsi) }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-3">
                    <a href="{{ route('unit-kerja.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-2"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i> Update Unit Kerja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection