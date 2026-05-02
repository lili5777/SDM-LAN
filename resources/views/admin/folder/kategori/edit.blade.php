@extends('admin.partials.layout')

@section('title', 'Edit Kategori Folder - JABLAYMEN')
@section('page-title', 'Edit Kategori Folder')

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
                    <i class="fas fa-edit me-2 text-warning"></i> Edit Kategori: {{ $kategori->nama }}
                </h5>
                <a href="{{ route('folder.kategori.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <!-- Info Box -->
            @if($kategori->folders->count() > 0)
            <div class="info-box">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-info-circle fa-2x text-success"></i>
                    <div>
                        <small class="text-muted">Kategori ini memiliki <strong>{{ $kategori->folders->count() }}</strong> folder</small>
                        <div class="small text-muted">Folder yang sudah ada tidak akan terpengaruh oleh perubahan nama kategori</div>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('folder.kategori.update', $kategori->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Kategori -->
                <div class="mb-4">
                    <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                           value="{{ old('nama', $kategori->nama) }}" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                              rows="3">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <!-- Urutan -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Urutan Tampil</label>
                        <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror" 
                               value="{{ old('urutan', $kategori->urutan) }}">
                        <small class="text-muted">Semakin kecil angka, semakin atas tampilannya</small>
                        @error('urutan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch mt-2">
                            <input type="checkbox" name="is_active" class="form-check-input" value="1" 
                                   style="width: 40px; height: 20px;" {{ old('is_active', $kategori->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label ms-2">Aktif</label>
                        </div>
                        <small class="text-muted">Kategori nonaktif tidak akan terlihat</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-3">
                    <a href="{{ route('folder.kategori.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-2"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i> Update Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection