@extends('admin.partials.layout')

@section('title', 'Upload Dokumen Pegawai - JABLAYMEN')
@section('page-title', 'Upload Dokumen Pegawai')

@section('styles')
<style>
    /* Breadcrumb */
    .drive-breadcrumb {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }
    .drive-breadcrumb a { color: #64748b; text-decoration: none; transition: color 0.15s; }
    .drive-breadcrumb a:hover { color: #166534; }
    .drive-breadcrumb .sep { color: #cbd5e1; }
    .drive-breadcrumb .current { color: #0f172a; font-weight: 600; }

    /* Card wrapper */
    .upload-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .upload-card-header {
        background: linear-gradient(135deg, #166534, #15803d);
        padding: 1.25rem 1.5rem;
        color: white;
    }

    /* Section divider */
    .section-divider {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    /* Form controls */
    .form-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.4rem;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border-color: #e2e8f0;
        font-size: 0.9rem;
        padding: 0.55rem 0.85rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #166534;
        box-shadow: 0 0 0 3px rgba(22,101,52,0.08);
    }
    .required-mark { color: #dc2626; }

    /* Pegawai preview */
    .pegawai-preview {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 1rem;
        min-height: 70px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s;
    }
    .pegawai-preview.has-data {
        background: #f0fdf4;
        border-color: #86efac;
    }
    .pegawai-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #166534;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
    }

    /* Folder info */
    .folder-info {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 0.875rem 1rem;
        min-height: 52px;
        transition: all 0.3s;
        font-size: 0.85rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
    }
    .folder-info.has-data {
        background: #eff6ff;
        border-color: #93c5fd;
        color: #1e40af;
    }

    /* Drop zone */
    .drop-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        padding: 2.5rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: #fafafa;
        position: relative;
    }
    .drop-zone:hover, .drop-zone.dragover {
        border-color: #166534;
        background: #f0fdf4;
    }
    .drop-zone.border-danger { border-color: #dc2626 !important; }
    .drop-zone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }
    .drop-zone .dz-icon {
        font-size: 2.5rem;
        color: #94a3b8;
        margin-bottom: 0.75rem;
        transition: color 0.3s;
    }
    .drop-zone:hover .dz-icon,
    .drop-zone.dragover .dz-icon { color: #166534; }

    /* File preview box */
    .file-preview-box {
        display: none;
        background: #f0fdf4;
        border: 1px solid #86efac;
        border-radius: 12px;
        padding: 1rem;
        align-items: center;
        gap: 0.75rem;
        margin-top: 0.75rem;
    }
    .file-preview-box.show { display: flex; }
    .file-preview-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .file-preview-icon.pdf   { background: #fee2e2; color: #dc2626; }
    .file-preview-icon.image { background: #dbeafe; color: #2563eb; }
    .file-preview-icon.doc   { background: #d1fae5; color: #065f46; }
    .file-preview-icon.other { background: #fef3c7; color: #d97706; }

    /* Info badge */
    .badge-admin-upload {
        background: #fef3c7;
        color: #92400e;
        border-radius: 10px;
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    /* Back breadcrumb context */
    .context-bar {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        color: #166534;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }
</style>
@endsection

@section('content')

{{-- Breadcrumb dinamis --}}
<div class="drive-breadcrumb">
    <a href="{{ route('admin.dokumen.index') }}">
        <i class="fas fa-home me-1"></i> Semua Dokumen
    </a>
    @if(!empty($selectedPegawaiId))
        @php $peg = $pegawais->firstWhere('id', $selectedPegawaiId); @endphp
        @if($peg)
        <span class="sep">/</span>
        <a href="{{ route('admin.dokumen.pegawai', $peg->id) }}">{{ $peg->nama }}</a>
        @endif
    @endif
    <span class="sep">/</span>
    <span class="current">Upload Dokumen</span>
</div>

<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-11">

        {{-- Konteks jika datang dari folder tertentu --}}
        @if(!empty($selectedPegawaiId) && !empty($selectedFolderId))
        @php
            $pegCtx = $pegawais->firstWhere('id', $selectedPegawaiId);
            $folCtx = null;
            foreach($folders as $items) {
                foreach($items as $f) { if($f->id == $selectedFolderId) { $folCtx = $f; break 2; } }
            }
        @endphp
        @if($pegCtx && $folCtx)
        <div class="context-bar">
            <i class="fas fa-info-circle"></i>
            Upload ke folder <strong>{{ $folCtx->nama }}</strong> milik
            <strong>{{ $pegCtx->nama }}</strong>
        </div>
        @endif
        @endif

        {{-- Info badge --}}
        <div class="badge-admin-upload">
            <i class="fas fa-shield-alt mt-1" style="flex-shrink:0"></i>
            <div>
                <strong>Upload oleh Admin</strong> — Dokumen yang diunggah melalui form ini akan langsung masuk ke arsip pegawai. Status langsung <strong>Aktif</strong>.
            </div>
        </div>

        <div class="upload-card">
            <div class="upload-card-header d-flex align-items-center gap-2">
                <i class="fas fa-cloud-upload-alt fa-lg"></i>
                <div>
                    <div class="fw-semibold">Upload Dokumen Pegawai</div>
                    <div class="small opacity-75">Unggah berkas langsung ke arsip pegawai</div>
                </div>
            </div>

            <div class="p-4">

                @if($errors->any())
                <div class="alert alert-danger rounded-3 mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.dokumen.store') }}" method="POST"
                    enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    {{-- ══ 1. PILIH PEGAWAI ══════════════════════════════════ --}}
                    <div class="section-divider">
                        <i class="fas fa-user me-1"></i> Data Pegawai
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">
                                Pilih Pegawai <span class="required-mark">*</span>
                            </label>
                            <select name="pegawai_id" id="pegawaiSelect"
                                class="form-select @error('pegawai_id') is-invalid @enderror"
                                required>
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach($pegawais as $pegawai)
                                <option
                                    value="{{ $pegawai->id }}"
                                    data-nama="{{ $pegawai->nama }}"
                                    data-nip="{{ $pegawai->nip }}"
                                    data-unit="{{ $pegawai->unitKerja->nama ?? '-' }}"
                                    data-jabatan="{{ $pegawai->jabatan ?? '-' }}"
                                    {{ old('pegawai_id', $selectedPegawaiId ?? '') == $pegawai->id ? 'selected' : '' }}>
                                    {{ $pegawai->nama }} — {{ $pegawai->nip }}
                                </option>
                                @endforeach
                            </select>
                            @error('pegawai_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Info Pegawai</label>
                            <div class="pegawai-preview" id="pegawaiPreview">
                                <i class="fas fa-user-circle fa-2x text-muted"></i>
                                <span class="text-muted small">Pilih pegawai terlebih dahulu</span>
                            </div>
                        </div>
                    </div>

                    {{-- ══ 2. PILIH FOLDER ═══════════════════════════════════ --}}
                    <div class="section-divider">
                        <i class="fas fa-folder me-1"></i> Lokasi Folder
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">
                                Pilih Folder <span class="required-mark">*</span>
                            </label>
                            <select name="folder_id" id="folderSelect"
                                class="form-select @error('folder_id') is-invalid @enderror"
                                required>
                                <option value="">-- Pilih Folder --</option>
                                @foreach($folders as $kategoriNama => $items)
                                    <optgroup label="{{ $kategoriNama ?? 'Tanpa Kategori' }}">
                                        @foreach($items as $folder)
                                        <option
                                            value="{{ $folder->id }}"
                                            data-ext="{{ $folder->ekstensi_json }}"
                                            data-size="{{ $folder->max_size_mb ?? 10 }}"
                                            {{ old('folder_id', $selectedFolderId ?? '') == $folder->id ? 'selected' : '' }}>
                                            {{ $folder->nama }}
                                        </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('folder_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ketentuan Folder</label>
                            <div class="folder-info" id="folderInfo">
                                <i class="fas fa-info-circle me-2"></i>
                                Pilih folder untuk melihat ketentuan
                            </div>
                        </div>
                    </div>

                    {{-- ══ 3. INFORMASI DOKUMEN ══════════════════════════════ --}}
                    <div class="section-divider">
                        <i class="fas fa-file-alt me-1"></i> Informasi Dokumen
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label">
                                Judul Dokumen <span class="required-mark">*</span>
                            </label>
                            <input type="text" name="judul"
                                class="form-control @error('judul') is-invalid @enderror"
                                placeholder="Contoh: SK Pengangkatan 2024"
                                value="{{ old('judul') }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nomor Dokumen</label>
                            <input type="text" name="nomor_dokumen"
                                class="form-control @error('nomor_dokumen') is-invalid @enderror"
                                placeholder="Opsional"
                                value="{{ old('nomor_dokumen') }}">
                            @error('nomor_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Tanggal Dokumen <span class="required-mark">*</span>
                            </label>
                            <input type="date" name="tanggal_dokumen"
                                class="form-control @error('tanggal_dokumen') is-invalid @enderror"
                                value="{{ old('tanggal_dokumen') }}" required>
                            @error('tanggal_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="keterangan"
                                class="form-control @error('keterangan') is-invalid @enderror"
                                placeholder="Catatan tambahan (opsional)"
                                value="{{ old('keterangan') }}">
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- ══ 4. UPLOAD FILE ════════════════════════════════════ --}}
                    <div class="section-divider">
                        <i class="fas fa-paperclip me-1"></i> File Dokumen
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            Pilih File <span class="required-mark">*</span>
                        </label>

                        <div class="drop-zone @error('file') border-danger @enderror"
                            id="dropZone">
                            <input type="file" name="file" id="fileInput"
                                accept=".pdf,.jpg,.jpeg,.png">
                            <div class="dz-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="fw-semibold text-muted mb-1">
                                Klik atau seret file ke sini
                            </div>
                            <div class="small text-muted" id="dropZoneHint">
                                Format &amp; ukuran sesuai ketentuan folder
                            </div>
                        </div>

                        @error('file')
                            <div class="text-danger small mt-1">
                                <i class="fas fa-times-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror

                        <div class="file-preview-box" id="filePreview">
                            <div class="file-preview-icon" id="filePreviewIcon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold small" id="filePreviewName">-</div>
                                <div class="text-muted small" id="filePreviewSize">-</div>
                            </div>
                            <button type="button"
                                class="btn btn-sm btn-outline-danger rounded-pill"
                                id="clearFile">
                                <i class="fas fa-times me-1"></i> Hapus
                            </button>
                        </div>
                    </div>

                    {{-- ══ TOMBOL AKSI ═══════════════════════════════════════ --}}
                    <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                        {{-- Kembali ke folder asal jika ada konteks --}}
                        @if(!empty($selectedPegawaiId) && !empty($selectedFolderId))
                            <a href="{{ route('admin.dokumen.folder', ['pegawai' => $selectedPegawaiId, 'folder' => $selectedFolderId]) }}"
                                class="btn btn-outline-secondary px-4 rounded-pill">
                                <i class="fas fa-arrow-left me-1"></i> Batal
                            </a>
                        @elseif(!empty($selectedPegawaiId))
                            <a href="{{ route('admin.dokumen.pegawai', $selectedPegawaiId) }}"
                                class="btn btn-outline-secondary px-4 rounded-pill">
                                <i class="fas fa-arrow-left me-1"></i> Batal
                            </a>
                        @else
                            <a href="{{ route('admin.dokumen.index') }}"
                                class="btn btn-outline-secondary px-4 rounded-pill">
                                <i class="fas fa-arrow-left me-1"></i> Batal
                            </a>
                        @endif

                        <button type="submit" class="btn btn-success px-4 rounded-pill"
                            id="submitBtn">
                            <i class="fas fa-cloud-upload-alt me-2"></i> Upload &amp; Simpan
                        </button>
                    </div>

                </form>
            </div>{{-- end card body --}}
        </div>{{-- end upload-card --}}
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Pegawai preview ────────────────────────────────────────
    const pegawaiSelect  = document.getElementById('pegawaiSelect');
    const pegawaiPreview = document.getElementById('pegawaiPreview');

    function updatePegawaiPreview() {
        const opt = pegawaiSelect.options[pegawaiSelect.selectedIndex];
        if (!opt || !opt.value) {
            pegawaiPreview.innerHTML =
                '<i class="fas fa-user-circle fa-2x text-muted"></i>'
                + '<span class="text-muted small">Pilih pegawai terlebih dahulu</span>';
            pegawaiPreview.classList.remove('has-data');
            return;
        }
        const initial = (opt.dataset.nama || '?').charAt(0).toUpperCase();
        pegawaiPreview.classList.add('has-data');
        pegawaiPreview.innerHTML = `
            <div class="pegawai-avatar">${initial}</div>
            <div>
                <div class="fw-semibold small">${opt.dataset.nama}</div>
                <div class="text-muted" style="font-size:0.78rem">NIP: ${opt.dataset.nip}</div>
                <div class="text-muted" style="font-size:0.78rem">
                    ${opt.dataset.jabatan} &middot; ${opt.dataset.unit}
                </div>
            </div>`;
    }

    pegawaiSelect.addEventListener('change', updatePegawaiPreview);
    updatePegawaiPreview(); // render langsung jika sudah pre-selected

    // ── Folder info ────────────────────────────────────────────
    const folderSelect = document.getElementById('folderSelect');
    const folderInfo   = document.getElementById('folderInfo');
    const dropZoneHint = document.getElementById('dropZoneHint');
    const fileInput    = document.getElementById('fileInput');

    function updateFolderInfo() {
        const opt = folderSelect.options[folderSelect.selectedIndex];
        if (!opt || !opt.value) {
            folderInfo.classList.remove('has-data');
            folderInfo.innerHTML =
                '<i class="fas fa-info-circle me-2"></i> Pilih folder untuk melihat ketentuan';
            dropZoneHint.textContent = 'Format & ukuran sesuai ketentuan folder';
            fileInput.accept = '.pdf,.jpg,.jpeg,.png';
            return;
        }

        let ext = ['pdf', 'jpg', 'png'];
        try { ext = JSON.parse(opt.dataset.ext); } catch (e) {}

        const size   = opt.dataset.size || 10;
        const extStr = ext.map(e => e.toUpperCase()).join(', ');

        folderInfo.classList.add('has-data');
        folderInfo.innerHTML =
            `<i class="fas fa-folder-open me-2"></i>`
            + `<strong>Format:</strong> ${extStr} &nbsp;&middot;&nbsp; `
            + `<strong>Maks:</strong> ${size} MB`;
        dropZoneHint.textContent = `Format: ${extStr} — Maks ${size} MB`;
        fileInput.accept = ext.map(e => '.' + e).join(',');
    }

    folderSelect.addEventListener('change', updateFolderInfo);
    updateFolderInfo(); // render langsung jika sudah pre-selected

    // ── Drop zone & file preview ───────────────────────────────
    const dropZone        = document.getElementById('dropZone');
    const filePreview     = document.getElementById('filePreview');
    const filePreviewIcon = document.getElementById('filePreviewIcon');
    const filePreviewName = document.getElementById('filePreviewName');
    const filePreviewSize = document.getElementById('filePreviewSize');
    const clearFileBtn    = document.getElementById('clearFile');

    function formatBytes(bytes) {
        if (bytes < 1024)    return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(2) + ' MB';
    }

    function showFilePreview(file) {
        const ext = file.name.split('.').pop().toLowerCase();
        let iconClass = 'other', iconName = 'file-alt';

        if (ext === 'pdf') {
            iconClass = 'pdf';  iconName = 'file-pdf';
        } else if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
            iconClass = 'image'; iconName = 'file-image';
        } else if (['doc', 'docx'].includes(ext)) {
            iconClass = 'doc';  iconName = 'file-word';
        }

        filePreviewIcon.className = `file-preview-icon ${iconClass}`;
        filePreviewIcon.innerHTML = `<i class="fas fa-${iconName}"></i>`;
        filePreviewName.textContent = file.name;
        filePreviewSize.textContent = formatBytes(file.size);
        filePreview.classList.add('show');
    }

    fileInput.addEventListener('change', function () {
        if (this.files.length) showFilePreview(this.files[0]);
        else filePreview.classList.remove('show');
    });

    clearFileBtn.addEventListener('click', function () {
        fileInput.value = '';
        filePreview.classList.remove('show');
    });

    dropZone.addEventListener('dragover',  e => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
    dropZone.addEventListener('drop', function (e) {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        if (e.dataTransfer.files.length) {
            const dt = new DataTransfer();
            dt.items.add(e.dataTransfer.files[0]);
            fileInput.files = dt.files;
            showFilePreview(e.dataTransfer.files[0]);
        }
    });

    // ── Submit loading state ───────────────────────────────────
    document.getElementById('uploadForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span> Mengupload...';
    });

    // ── Auto-submit filter select ──────────────────────────────
    document.querySelectorAll('.filter-card select').forEach(select => {
        select.addEventListener('change', function () {
            this.closest('form').submit();
        });
    });
});
</script>
@endsection