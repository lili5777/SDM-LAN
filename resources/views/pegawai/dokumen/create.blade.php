@extends('admin.partials.layout')

@section('title', 'Ajukan Dokumen - JABLAYMEN')
@section('page-title', 'Ajukan Dokumen Baru')

@section('styles')
<style>
*, *::before, *::after { box-sizing: border-box; }

/* ── Breadcrumb ──────────────────────────────────────── */
.drive-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 0.8rem; margin-bottom: 1.25rem; flex-wrap: wrap;
}
.drive-breadcrumb a { color: #64748b; text-decoration: none; transition: color .15s; }
.drive-breadcrumb a:hover { color: #166534; }
.drive-breadcrumb .sep { color: #cbd5e1; }
.drive-breadcrumb .current { color: #0f172a; font-weight: 600; }

/* ── Card ────────────────────────────────────────────── */
.upload-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    overflow: hidden;
}
.upload-card-header {
    background: #166534;
    padding: 1.25rem 1.5rem;
    color: #fff;
    position: relative; overflow: hidden;
}
.upload-card-header::after {
    content: '';
    position: absolute; right: -30px; top: -30px;
    width: 130px; height: 130px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
}

/* ── Info banner ─────────────────────────────────────── */
.info-banner {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    padding: .75rem 1rem;
    font-size: .8rem;
    color: #166534;
    display: flex; align-items: flex-start; gap: 10px;
    margin-bottom: 1.5rem;
}

/* ── Section divider ─────────────────────────────────── */
.section-divider {
    font-size: .68rem; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    color: #94a3b8; margin-bottom: .875rem;
    display: flex; align-items: center; gap: .5rem;
}
.section-divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }

/* ── Form controls ───────────────────────────────────── */
.form-label {
    font-size: .82rem; font-weight: 600;
    color: #475569; margin-bottom: .4rem;
}
.form-control, .form-select {
    border-radius: 10px; border-color: #e2e8f0;
    font-size: .875rem; padding: .55rem .85rem;
    transition: border-color .2s, box-shadow .2s;
}
.form-control:focus, .form-select:focus {
    border-color: #166634;
    box-shadow: 0 0 0 3px rgba(22,101,52,.08);
}

/* ── Folder radio cards ──────────────────────────────── */
.folder-grid-pick {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 10px; margin-bottom: .5rem;
}
.folder-pick-card {
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: .875rem 1rem;
    cursor: pointer;
    transition: all .18s;
    position: relative;
    display: flex; align-items: flex-start; gap: 10px;
}
.folder-pick-card:hover { border-color: #86efac; background: #f8fffb; }
.folder-pick-card.selected { border-color: #166634; background: #f0fdf4; }
.folder-pick-card input[type=radio] {
    position: absolute; opacity: 0; pointer-events: none;
}
.folder-pick-icon {
    width: 36px; height: 36px; border-radius: 9px;
    background: #fef9c3;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.folder-pick-icon i { color: #ca8a04; font-size: 1rem; }
.folder-pick-name {
    font-size: .82rem; font-weight: 600; color: #0f172a; line-height: 1.3;
}
.folder-pick-desc { font-size: .72rem; color: #64748b; margin-top: 2px; }
.folder-pick-meta {
    display: flex; gap: 8px; flex-wrap: wrap; margin-top: 5px;
}
.folder-pick-tag {
    font-size: .67rem; padding: 2px 7px; border-radius: 20px;
    background: #f1f5f9; color: #475569;
    display: inline-flex; align-items: center; gap: 3px;
}
.folder-pick-check {
    width: 18px; height: 18px; border-radius: 50%;
    border: 1.5px solid #d1d5db;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-left: auto; margin-top: 2px;
    transition: all .15s;
}
.folder-pick-card.selected .folder-pick-check {
    background: #166634; border-color: #166634; color: #fff;
}

/* ── Drop zone ───────────────────────────────────────── */
.drop-zone {
    border: 2px dashed #d1d5db;
    border-radius: 14px;
    padding: 2.25rem 1.5rem;
    text-align: center; cursor: pointer;
    transition: all .2s; background: #fafafa;
    position: relative;
}
.drop-zone:hover, .drop-zone.dragover {
    border-color: #166634; background: #f0fdf4;
}
.drop-zone input[type=file] {
    position: absolute; inset: 0;
    opacity: 0; cursor: pointer;
    width: 100%; height: 100%;
}
.drop-zone .dz-icon {
    font-size: 2.25rem; color: #cbd5e1;
    margin-bottom: .75rem; transition: color .2s;
}
.drop-zone:hover .dz-icon,
.drop-zone.dragover .dz-icon { color: #166634; }

/* ── File preview box ────────────────────────────────── */
.file-preview-box {
    display: none;
    background: #f0fdf4;
    border: 1px solid #86efac;
    border-radius: 12px;
    padding: .875rem 1rem;
    align-items: center; gap: .875rem;
    margin-top: .75rem;
}
.file-preview-box.show { display: flex; }
.fpi { width: 40px; height: 40px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0; }
.fpi.pdf   { background:#fee2e2;color:#dc2626; }
.fpi.image { background:#dbeafe;color:#2563eb; }
.fpi.doc   { background:#d1fae5;color:#065f46; }
.fpi.other { background:#fef3c7;color:#d97706; }

/* ── Step indicators ─────────────────────────────────── */
.steps {
    display: flex; align-items: center; gap: 0;
    margin-bottom: 1.75rem; overflow-x: auto;
    padding-bottom: 4px;
}
.step-item {
    display: flex; align-items: center; gap: 8px;
    flex-shrink: 0;
}
.step-num {
    width: 26px; height: 26px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; font-weight: 700;
    border: 1.5px solid #e2e8f0;
    color: #94a3b8; background: #fff;
    transition: all .2s;
}
.step-num.done { background: #d1fae5; border-color: #166634; color: #166634; }
.step-num.active { background: #166634; border-color: #166634; color: #fff; }
.step-lbl { font-size: .75rem; color: #64748b; font-weight: 500; }
.step-lbl.active { color: #166634; font-weight: 700; }
.step-sep { width: 32px; height: 1px; background: #e2e8f0; margin: 0 4px; flex-shrink: 0; }

@media (max-width:576px) {
    .folder-grid-pick { grid-template-columns: 1fr; }
}
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
<div class="drive-breadcrumb">
    <a href="{{ route('dokumen.saya') }}">
        <i class="fas fa-home me-1"></i> Dokumen Saya
    </a>
    <span class="sep">/</span>
    <span class="current">Ajukan Dokumen Baru</span>
</div>

<div class="row justify-content-center">
<div class="col-xl-9 col-lg-11">

    {{-- Info banner --}}
    <div class="info-banner">
        <i class="fas fa-info-circle" style="flex-shrink:0;margin-top:1px"></i>
        <div>
            Dokumen yang Anda ajukan akan <strong>diproses Admin</strong> sebelum masuk ke arsip.
            Pantau status pengajuan di menu <strong>Status Pengajuan</strong>.
        </div>
    </div>

    {{-- Step indicator --}}
    <div class="steps">
        <div class="step-item">
            <div class="step-num active" id="step1-num">1</div>
            <span class="step-lbl active" id="step1-lbl">Pilih Folder</span>
        </div>
        <div class="step-sep"></div>
        <div class="step-item">
            <div class="step-num" id="step2-num">2</div>
            <span class="step-lbl" id="step2-lbl">Info Dokumen</span>
        </div>
        <div class="step-sep"></div>
        <div class="step-item">
            <div class="step-num" id="step3-num">3</div>
            <span class="step-lbl" id="step3-lbl">Upload File</span>
        </div>
    </div>

    <div class="upload-card">
        <div class="upload-card-header d-flex align-items-center gap-2">
            <i class="fas fa-upload fa-lg" style="position:relative;z-index:1"></i>
            <div style="position:relative;z-index:1">
                <div style="font-weight:700;font-size:.95rem">Form Pengajuan Dokumen</div>
                <div style="font-size:.78rem;opacity:.65">Upload akan diproses Admin</div>
            </div>
        </div>

        <div class="p-4">

            @if($errors->any())
            <div class="alert alert-danger rounded-3 mb-4" style="font-size:.82rem">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('dokumen.store') }}" method="POST"
                enctype="multipart/form-data" id="uploadForm">
                @csrf

                {{-- ══ STEP 1: PILIH FOLDER ══════════════════════════ --}}
                <div class="section-divider">
                    <i class="fas fa-folder me-1"></i> Langkah 1 — Pilih Folder Tujuan
                </div>

                @foreach($folders as $kategori => $folderList)
                <div style="margin-bottom:1rem">
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;
                        letter-spacing:.06em;color:#94a3b8;margin-bottom:.5rem">
                        {{ $kategori ?? 'Tanpa Kategori' }}
                    </div>
                    <div class="folder-grid-pick">
                        @foreach($folderList as $folder)
                        <div class="folder-pick-card {{ old('folder_id') == $folder->id ? 'selected' : '' }}"
                            onclick="selectFolder(this, '{{ $folder->id }}')">
                            <input type="radio" name="folder_id"
                                value="{{ $folder->id }}"
                                id="folder_{{ $folder->id }}"
                                data-max-size="{{ $folder->max_size_mb ?? 10 }}"
                                data-extensions="{{ json_encode($folder->ekstensi_allowed ?? ['pdf','jpg','png']) }}"
                                {{ old('folder_id') == $folder->id ? 'checked' : '' }}>
                            <div class="folder-pick-icon">
                                <i class="fas fa-folder"></i>
                            </div>
                            <div style="flex:1;min-width:0">
                                <div class="folder-pick-name">{{ $folder->nama }}</div>
                                @if($folder->deskripsi)
                                <div class="folder-pick-desc">{{ Str::limit($folder->deskripsi, 60) }}</div>
                                @endif
                                <div class="folder-pick-meta">
                                    <span class="folder-pick-tag">
                                        <i class="fas fa-weight-hanging" style="font-size:.6rem"></i>
                                        Max {{ $folder->max_size_mb ?? 10 }} MB
                                    </span>
                                    @if(!empty($folder->ekstensi_allowed))
                                    <span class="folder-pick-tag">
                                        <i class="fas fa-file" style="font-size:.6rem"></i>
                                        {{ implode(', ', $folder->ekstensi_allowed) }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="folder-pick-check" id="chk_{{ $folder->id }}">
                                @if(old('folder_id') == $folder->id)
                                    <i class="fas fa-check" style="font-size:.6rem"></i>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                @error('folder_id')
                <div class="text-danger small mb-3">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
                @enderror

                {{-- ══ STEP 2: INFO DOKUMEN ═══════════════════════════ --}}
                <div class="section-divider mt-4">
                    <i class="fas fa-file-alt me-1"></i> Langkah 2 — Informasi Dokumen
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label class="form-label">
                            Judul Dokumen <span class="text-danger">*</span>
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
                        <input type="text" name="nomor_dokumen" class="form-control"
                            placeholder="Opsional"
                            value="{{ old('nomor_dokumen') }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Tanggal Dokumen</label>
                        <input type="date" name="tanggal_dokumen" class="form-control"
                            value="{{ old('tanggal_dokumen', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-7">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control"
                            placeholder="Catatan tambahan (opsional)"
                            value="{{ old('keterangan') }}">
                    </div>
                </div>

                {{-- ══ STEP 3: UPLOAD FILE ════════════════════════════ --}}
                <div class="section-divider mt-4">
                    <i class="fas fa-paperclip me-1"></i> Langkah 3 — File Dokumen
                </div>

                <div class="mb-4">
                    <label class="form-label">
                        Pilih File <span class="text-danger">*</span>
                    </label>
                    <div class="drop-zone" id="dropZone">
                        <input type="file" name="file" id="fileInput"
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <div class="dz-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                        <div style="font-weight:600;color:#64748b;margin-bottom:4px">
                            Klik atau seret file ke sini
                        </div>
                        <div style="font-size:.78rem;color:#94a3b8" id="dzHint">
                            Format & ukuran menyesuaikan folder yang dipilih
                        </div>
                    </div>

                    @error('file')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-times-circle me-1"></i>{{ $message }}
                    </div>
                    @enderror

                    <div id="fileError" class="text-danger small mt-1" style="display:none"></div>

                    <div class="file-preview-box" id="filePreview">
                        <div class="fpi" id="fpiIcon"><i class="fas fa-file-alt"></i></div>
                        <div style="flex:1;min-width:0">
                            <div style="font-weight:600;font-size:.83rem" id="fpiName">-</div>
                            <div style="font-size:.75rem;color:#64748b" id="fpiSize">-</div>
                        </div>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger rounded-pill"
                            id="clearFileBtn">
                            <i class="fas fa-times me-1"></i> Hapus
                        </button>
                    </div>
                </div>

                {{-- ══ TOMBOL AKSI ════════════════════════════════════ --}}
                <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                    <a href="{{ route('dokumen.saya') }}"
                        class="btn btn-outline-secondary px-4 rounded-pill">
                        <i class="fas fa-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success px-4 rounded-pill"
                        id="submitBtn">
                        <i class="fas fa-paper-plane me-2"></i> Ajukan Dokumen
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Step tracker ────────────────────────────────── */
    function updateSteps() {
        const folderOk = !!document.querySelector('.folder-pick-card.selected');
        const judulOk  = !!document.getElementById('judulInput')?.value.trim();
        const fileOk   = document.getElementById('filePreview').classList.contains('show');

        setStep(1, folderOk ? 'done' : 'active');
        setStep(2, folderOk && judulOk ? 'done' : (folderOk ? 'active' : ''));
        setStep(3, fileOk  ? 'done' : (folderOk ? 'active' : ''));
    }
    function setStep(n, state) {
        const num = document.getElementById('step' + n + '-num');
        const lbl = document.getElementById('step' + n + '-lbl');
        num.className = 'step-num' + (state === 'active' ? ' active' : state === 'done' ? ' done' : '');
        lbl.className = 'step-lbl' + (state === 'active' || state === 'done' ? ' active' : '');
        num.innerHTML = state === 'done'
            ? '<i class="fas fa-check" style="font-size:.6rem"></i>' : n;
    }

    /* ── Folder selection ────────────────────────────── */
    window.selectFolder = function(card, id) {
        document.querySelectorAll('.folder-pick-card').forEach(c => {
            c.classList.remove('selected');
            const chk = c.querySelector('[id^=chk_]');
            if (chk) chk.innerHTML = '';
        });
        card.classList.add('selected');
        const radio = card.querySelector('input[type=radio]');
        if (radio) radio.checked = true;
        const chk = document.getElementById('chk_' + id);
        if (chk) chk.innerHTML = '<i class="fas fa-check" style="font-size:.6rem"></i>';

        updateFolderHint(radio);
        clearFile();
        updateSteps();
    };

    function updateFolderHint(radio) {
        if (!radio) return;
        let ext = ['pdf','jpg','png'];
        try { ext = JSON.parse(radio.dataset.extensions); } catch(e){}
        const size = radio.dataset.maxSize || 10;
        document.getElementById('dzHint').textContent =
            'Format: ' + ext.map(e => e.toUpperCase()).join(', ') + ' — Maks ' + size + ' MB';
        document.getElementById('fileInput').accept = ext.map(e => '.' + e).join(',');
    }

    /* ── Drop zone ───────────────────────────────────── */
    const dropZone  = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');
    const fileError = document.getElementById('fileError');

    function typeInfo(ext) {
        if (ext === 'pdf')                            return { cls: 'pdf',   icon: 'fa-file-pdf' };
        if (['jpg','jpeg','png','gif'].includes(ext)) return { cls: 'image', icon: 'fa-file-image' };
        if (['doc','docx'].includes(ext))             return { cls: 'doc',   icon: 'fa-file-word' };
        return { cls: 'other', icon: 'fa-file-alt' };
    }

    function formatBytes(b) {
        if (b < 1024)    return b + ' B';
        if (b < 1048576) return (b/1024).toFixed(1) + ' KB';
        return (b/1048576).toFixed(2) + ' MB';
    }

    function showFile(file) {
        const ext = file.name.split('.').pop().toLowerCase();
        const t = typeInfo(ext);
        document.getElementById('fpiIcon').className = 'fpi ' + t.cls;
        document.getElementById('fpiIcon').innerHTML = '<i class="fas ' + t.icon + '"></i>';
        document.getElementById('fpiName').textContent = file.name;
        document.getElementById('fpiSize').textContent = formatBytes(file.size);
        filePreview.classList.add('show');
        updateSteps();
    }

    function handleFile(file) {
        const radio = document.querySelector('.folder-pick-card.selected input[type=radio]');
        fileError.style.display = 'none';
        if (!radio) {
            fileError.textContent = 'Pilih folder tujuan terlebih dahulu';
            fileError.style.display = 'block'; return;
        }
        let ext = ['pdf','jpg','png'];
        try { ext = JSON.parse(radio.dataset.extensions); } catch(e){}
        const fileExt = file.name.split('.').pop().toLowerCase();
        if (!ext.includes(fileExt)) {
            fileError.textContent = 'Format tidak diizinkan. Format yang diterima: ' + ext.join(', ');
            fileError.style.display = 'block'; return;
        }
        const maxMB = parseFloat(radio.dataset.maxSize) || 10;
        if (file.size / 1048576 > maxMB) {
            fileError.textContent = 'Ukuran file melebihi batas ' + maxMB + ' MB';
            fileError.style.display = 'block'; return;
        }
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
        showFile(file);
    }

    function clearFile() {
        fileInput.value = '';
        filePreview.classList.remove('show');
        fileError.style.display = 'none';
        updateSteps();
    }

    fileInput.addEventListener('change', e => {
        if (e.target.files.length) handleFile(e.target.files[0]);
    });
    document.getElementById('clearFileBtn').addEventListener('click', clearFile);
    dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('dragover'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault(); dropZone.classList.remove('dragover');
        if (e.dataTransfer.files.length) handleFile(e.dataTransfer.files[0]);
    });

    /* ── Submit ──────────────────────────────────────── */
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        const folderOk = !!document.querySelector('.folder-pick-card.selected');
        if (!folderOk) { e.preventDefault(); alert('Pilih folder tujuan terlebih dahulu'); return; }
        if (!fileInput.files.length) { e.preventDefault(); alert('Pilih file terlebih dahulu'); return; }
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
    });

    updateSteps();
});
</script>
@endsection