@extends('admin.partials.layout')

@section('title', 'Tambah Folder - JABLAYMEN')
@section('page-title', 'Tambah Folder Baru')

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
    .ext-checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 0.5rem;
    }
    .ext-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f8fafc;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .ext-checkbox:hover {
        background: #e2e8f0;
    }
    .ext-checkbox input:checked + span {
        color: #166534;
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="form-card">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-folder-plus me-2 text-success"></i> Form Tambah Folder
                </h5>
                <a href="{{ route('folder.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <form action="{{ route('folder.store') }}" method="POST">
                @csrf

                <!-- Kategori -->
                <div class="mb-4">
                    <label class="form-label">Kategori Folder <span class="text-danger">*</span></label>
                    <select name="kategori_folder_id" class="form-select @error('kategori_folder_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_folder_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('kategori_folder_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Nama Folder -->
                <div class="mb-4">
                    <label class="form-label">Nama Folder <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                           value="{{ old('nama') }}" placeholder="Contoh: Kepegawaian, Sertifikat" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                              rows="3" placeholder="Deskripsi singkat tentang folder ini">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <!-- Ekstensi yang diizinkan -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Ekstensi File yang Diizinkan</label>
                        <div class="ext-checkbox-group">
                            <label class="ext-checkbox">
                                <input type="checkbox" name="ekstensi_allowed[]" value="pdf" {{ in_array('pdf', old('ekstensi_allowed', [])) ? 'checked' : '' }}>
                                <span>PDF</span>
                            </label>
                            <label class="ext-checkbox">
                                <input type="checkbox" name="ekstensi_allowed[]" value="jpg" {{ in_array('jpg', old('ekstensi_allowed', [])) ? 'checked' : '' }}>
                                <span>JPG</span>
                            </label>
                            <label class="ext-checkbox">
                                <input type="checkbox" name="ekstensi_allowed[]" value="png" {{ in_array('png', old('ekstensi_allowed', [])) ? 'checked' : '' }}>
                                <span>PNG</span>
                            </label>
                            <label class="ext-checkbox">
                                <input type="checkbox" name="ekstensi_allowed[]" value="doc" {{ in_array('doc', old('ekstensi_allowed', [])) ? 'checked' : '' }}>
                                <span>DOC</span>
                            </label>
                            <label class="ext-checkbox">
                                <input type="checkbox" name="ekstensi_allowed[]" value="docx" {{ in_array('docx', old('ekstensi_allowed', [])) ? 'checked' : '' }}>
                                <span>DOCX</span>
                            </label>
                            <label class="ext-checkbox">
                                <input type="checkbox" name="ekstensi_allowed[]" value="xls" {{ in_array('xls', old('ekstensi_allowed', [])) ? 'checked' : '' }}>
                                <span>XLS</span>
                            </label>
                            <label class="ext-checkbox">
                                <input type="checkbox" name="ekstensi_allowed[]" value="xlsx" {{ in_array('xlsx', old('ekstensi_allowed', [])) ? 'checked' : '' }}>
                                <span>XLSX</span>
                            </label>
                        </div>
                        <small class="text-muted">Kosongkan jika semua ekstensi diizinkan</small>
                        @error('ekstensi_allowed') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <!-- Max Size -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Max Ukuran File (MB)</label>
                        <input type="number" name="max_size_mb" class="form-control @error('max_size_mb') is-invalid @enderror" 
                               value="{{ old('max_size_mb', 10) }}" min="1" max="100">
                        @error('max_size_mb') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Urutan -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Urutan Tampil</label>
                        <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror" 
                               value="{{ old('urutan', 0) }}">
                        <small class="text-muted">Semakin kecil angka, semakin atas tampilannya</small>
                        @error('urutan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch mt-2">
                            <input type="checkbox" name="is_active" class="form-check-input" value="1" 
                                   style="width: 40px; height: 20px;" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label ms-2">Aktif</label>
                        </div>
                        <small class="text-muted">Folder nonaktif tidak akan terlihat oleh pegawai</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-3">
                    <a href="{{ route('folder.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-2"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i> Simpan Folder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection