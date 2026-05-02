@extends('admin.partials.layout')

@section('title', 'Ajukan Dokumen - JABLAYMEN')
@section('page-title', 'Ajukan Dokumen Baru')

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
    .folder-select-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .folder-select-card:hover {
        border-color: #bbf7d2;
        background: #f8fafc;
    }
    .folder-select-card.selected {
        border-color: #166534;
        background: #f0fdf6;
    }
    .folder-select-card input {
        margin-right: 10px;
    }
    .file-preview {
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #f8fafc;
    }
    .file-preview:hover {
        border-color: #166534;
        background: #f0fdf6;
    }
    .file-preview.has-file {
        border-color: #166534;
        border-style: solid;
    }
    .file-info {
        margin-top: 1rem;
        padding: 0.75rem;
        background: #e8f5e9;
        border-radius: 8px;
        display: none;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="form-card">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-upload me-2 text-success"></i> Form Pengajuan Dokumen Baru
                </h5>
                <a href="{{ route('dokumen.saya') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="info-box">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-info-circle fa-2x text-success"></i>
                    <div>
                        <small>Pengajuan dokumen akan diproses oleh Admin. Status pengajuan dapat dilihat di menu <strong>Status Pengajuan</strong>.</small>
                    </div>
                </div>
            </div>

            <form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                <!-- Pilih Folder -->
                <div class="mb-4">
                    <label class="form-label">Pilih Folder Tujuan <span class="text-danger">*</span></label>
                    @foreach($folders as $kategori => $folderList)
                    <div class="mb-3">
                        <div class="fw-semibold mb-2 text-success">{{ $kategori }}</div>
                        @foreach($folderList as $folder)
                        <div class="folder-select-card" data-folder-id="{{ $folder->id }}">
                            <div class="form-check">
                                <input type="radio" name="folder_id" value="{{ $folder->id }}" 
                                       class="form-check-input folder-radio" 
                                       id="folder_{{ $folder->id }}"
                                       data-max-size="{{ $folder->max_size_mb }}"
                                       data-extensions="{{ json_encode($folder->ekstensi_allowed) }}"
                                       {{ old('folder_id') == $folder->id ? 'checked' : '' }}>
                                <label class="form-check-label" for="folder_{{ $folder->id }}">
                                    <div class="fw-semibold">{{ $folder->nama }}</div>
                                    @if($folder->deskripsi)
                                    <div class="small text-muted">{{ $folder->deskripsi }}</div>
                                    @endif
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-weight-hanging me-1"></i> Max: {{ $folder->max_size_mb }} MB
                                        @if($folder->ekstensi_allowed)
                                        <i class="fas fa-file me-1 ms-2"></i> Format: {{ implode(', ', $folder->ekstensi_allowed) }}
                                        @endif
                                    </div>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                    @error('folder_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Informasi Dokumen -->
                <div class="row">
                    <div class="col-md-8 mb-4">
                        <label class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" 
                               value="{{ old('judul') }}" placeholder="Masukkan judul dokumen" required>
                        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label">Nomor Dokumen</label>
                        <input type="text" name="nomor_dokumen" class="form-control" 
                               value="{{ old('nomor_dokumen') }}" placeholder="Contoh: 001/SK/2024">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Tanggal Dokumen</label>
                        <input type="date" name="tanggal_dokumen" class="form-control" 
                               value="{{ old('tanggal_dokumen', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" 
                               value="{{ old('keterangan') }}" placeholder="Keterangan tambahan (opsional)">
                    </div>
                </div>

                <!-- Upload File -->
                <div class="mb-4">
                    <label class="form-label">File Dokumen <span class="text-danger">*</span></label>
                    <div class="file-preview" id="filePreview">
                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                        <p class="mb-1">Klik atau drag & drop file di sini</p>
                        <small class="text-muted">Maksimal ukuran file menyesuaikan folder yang dipilih</small>
                        <input type="file" name="file" id="fileInput" style="display: none;" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                    </div>
                    <div class="file-info" id="fileInfo">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-alt me-2"></i>
                                <span id="fileName"></span>
                                <small class="text-muted ms-2" id="fileSize"></small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="removeFile">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    @error('file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    <div id="fileError" class="text-danger small mt-1" style="display: none;"></div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-3">
                    <a href="{{ route('dokumen.saya') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-2"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success px-4" id="submitBtn">
                        <i class="fas fa-paper-plane me-2"></i> Ajukan Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Folder selection handler
    const folderCards = document.querySelectorAll('.folder-select-card');
    const folderRadios = document.querySelectorAll('.folder-radio');
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeFileBtn = document.getElementById('removeFile');
    const fileError = document.getElementById('fileError');
    let selectedFile = null;
    
    // Folder selection styling
    folderRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            folderCards.forEach(card => {
                card.classList.remove('selected');
            });
            this.closest('.folder-select-card').classList.add('selected');
            
            // Clear file if folder changes
            if (selectedFile) {
                clearFile();
            }
            fileError.style.display = 'none';
        });
    });
    
    // File preview click
    filePreview.addEventListener('click', () => {
        fileInput.click();
    });
    
    // Drag & drop
    filePreview.addEventListener('dragover', (e) => {
        e.preventDefault();
        filePreview.style.borderColor = '#166534';
    });
    
    filePreview.addEventListener('dragleave', (e) => {
        e.preventDefault();
        filePreview.style.borderColor = '#e2e8f0';
    });
    
    filePreview.addEventListener('drop', (e) => {
        e.preventDefault();
        filePreview.style.borderColor = '#e2e8f0';
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });
    
    // File input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFile(e.target.files[0]);
        }
    });
    
    // Handle file validation
    function handleFile(file) {
        const selectedRadio = document.querySelector('.folder-radio:checked');
        if (!selectedRadio) {
            fileError.textContent = 'Pilih folder tujuan terlebih dahulu';
            fileError.style.display = 'block';
            return;
        }
        
        const maxSize = parseInt(selectedRadio.dataset.maxSize);
        const allowedExtensions = JSON.parse(selectedRadio.dataset.extensions);
        const fileExt = file.name.split('.').pop().toLowerCase();
        
        // Validate extension
        if (allowedExtensions && allowedExtensions.length > 0 && !allowedExtensions.includes(fileExt)) {
            fileError.textContent = `Format file tidak diizinkan. Format yang diizinkan: ${allowedExtensions.join(', ')}`;
            fileError.style.display = 'block';
            return;
        }
        
        // Validate size
        const fileSizeMB = file.size / (1024 * 1024);
        if (fileSizeMB > maxSize) {
            fileError.textContent = `Ukuran file melebihi batas maksimal ${maxSize} MB`;
            fileError.style.display = 'block';
            return;
        }
        
        fileError.style.display = 'none';
        selectedFile = file;
        
        // Update preview
        fileName.textContent = file.name;
        fileSize.textContent = `${fileSizeMB.toFixed(2)} MB`;
        filePreview.classList.add('has-file');
        filePreview.style.display = 'none';
        fileInfo.style.display = 'block';
    }
    
    // Remove file
    function clearFile() {
        selectedFile = null;
        fileInput.value = '';
        filePreview.classList.remove('has-file');
        filePreview.style.display = 'block';
        fileInfo.style.display = 'none';
        fileError.style.display = 'none';
    }
    
    removeFileBtn.addEventListener('click', clearFile);
    
    // Form submit validation
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        const selectedRadio = document.querySelector('.folder-radio:checked');
        if (!selectedRadio) {
            e.preventDefault();
            alert('Silakan pilih folder tujuan');
            return;
        }
        
        if (!selectedFile && fileInput.files.length === 0) {
            e.preventDefault();
            alert('Silakan pilih file yang akan diupload');
            return;
        }
        
        // Create FileList-like object for form submission
        if (selectedFile && fileInput.files.length === 0) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(selectedFile);
            fileInput.files = dataTransfer.files;
        }
    });
</script>
@endsection