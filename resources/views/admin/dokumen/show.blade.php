@extends('admin.partials.layout')

@section('title', 'Detail Dokumen - JABLAYMEN')
@section('page-title', 'Detail Dokumen')

@section('styles')
<style>
    .detail-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .detail-header {
        background: linear-gradient(135deg, #166534 0%, #15803d 100%);
        padding: 1.25rem 1.5rem;
        color: white;
    }
    .detail-section {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .info-row {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px dashed #e2e8f0;
    }
    .info-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .info-label {
        width: 160px;
        font-weight: 500;
        color: #64748b;
        flex-shrink: 0;
    }
    .info-value {
        flex: 1;
        color: #0f172a;
    }
    .preview-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
    }
    .file-icon-large {
        font-size: 4rem;
        margin-bottom: 1rem;
    }
    .file-icon-large.pdf { color: #dc2626; }
    .file-icon-large.image { color: #2563eb; }
    .file-icon-large.doc { color: #065f46; }
    .file-icon-large.other { color: #d97706; }
    
    .badge-status-lg {
        padding: 0.5rem 1.2rem;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .badge-status-lg.aktif { background: #d1fae5; color: #065f46; }
    .badge-status-lg.diarsipkan { background: #fee2e2; color: #dc2626; }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Detail Card -->
        <div class="detail-card">
            <div class="detail-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">
                            <i class="fas fa-file-alt me-2"></i> 
                            {{ $dokumen->judul }}
                        </h5>
                        <p class="mb-0 opacity-75 small">Diupload: {{ $dokumen->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="badge-status-lg {{ $dokumen->status }}">
                            <i class="fas fa-{{ $dokumen->status == 'aktif' ? 'check-circle' : 'archive' }} me-1"></i>
                            {{ ucfirst($dokumen->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informasi Dokumen -->
            <div class="detail-section">
                <h6 class="mb-3">
                    <i class="fas fa-info-circle me-2 text-success"></i> Informasi Dokumen
                </h6>
                
                <div class="info-row">
                    <div class="info-label">Judul Dokumen</div>
                    <div class="info-value fw-semibold">{{ $dokumen->judul }}</div>
                </div>
                
                @if($dokumen->nomor_dokumen)
                <div class="info-row">
                    <div class="info-label">Nomor Dokumen</div>
                    <div class="info-value">{{ $dokumen->nomor_dokumen }}</div>
                </div>
                @endif
                
                @if($dokumen->tanggal_dokumen)
                <div class="info-row">
                    <div class="info-label">Tanggal Dokumen</div>
                    <div class="info-value">{{ date('d/m/Y', strtotime($dokumen->tanggal_dokumen)) }}</div>
                </div>
                @endif
                
                <div class="info-row">
                    <div class="info-label">Lokasi Folder</div>
                    <div class="info-value">
                        {{ $dokumen->folder->kategoriFolder->nama ?? '' }} 
                        <i class="fas fa-arrow-right mx-1 text-muted"></i> 
                        {{ $dokumen->folder->nama ?? '-' }}
                    </div>
                </div>
                
                @if($dokumen->keterangan)
                <div class="info-row">
                    <div class="info-label">Keterangan</div>
                    <div class="info-value">{{ $dokumen->keterangan }}</div>
                </div>
                @endif
                
                <div class="info-row">
                    <div class="info-label">Nama File</div>
                    <div class="info-value">{{ $dokumen->file_name }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Ukuran File</div>
                    <div class="info-value">{{ $dokumen->formatted_file_size }}</div>
                </div>
            </div>

            <!-- Informasi Pegawai -->
            <div class="detail-section">
                <h6 class="mb-3">
                    <i class="fas fa-user me-2 text-success"></i> Informasi Pegawai
                </h6>
                
                <div class="info-row">
                    <div class="info-label">Nama Pegawai</div>
                    <div class="info-value">{{ $dokumen->pegawai->nama ?? '-' }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">NIP</div>
                    <div class="info-value">{{ $dokumen->pegawai->nip ?? '-' }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Unit Kerja</div>
                    <div class="info-value">{{ $dokumen->pegawai->unitKerja->nama ?? '-' }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Golongan / Jabatan</div>
                    <div class="info-value">{{ $dokumen->pegawai->golongan ?? '-' }} / {{ $dokumen->pegawai->jabatan ?? '-' }}</div>
                </div>
            </div>

            <!-- Informasi Upload & Approval -->
            <div class="detail-section">
                <h6 class="mb-3">
                    <i class="fas fa-clipboard-list me-2 text-success"></i> Informasi Upload & Approval
                </h6>
                
                <div class="info-row">
                    <div class="info-label">Diupload oleh</div>
                    <div class="info-value">{{ $dokumen->uploader->name ?? '-' }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Waktu Upload</div>
                    <div class="info-value">{{ $dokumen->created_at->format('d/m/Y H:i:s') }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Disetujui oleh</div>
                    <div class="info-value">{{ $dokumen->approver->name ?? '-' }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Waktu Approval</div>
                    <div class="info-value">{{ $dokumen->approved_at ? date('d/m/Y H:i:s', strtotime($dokumen->approved_at)) : '-' }}</div>
                </div>
            </div>

            <!-- File Preview -->
            <div class="detail-section border-bottom-0">
                <h6 class="mb-3">
                    <i class="fas fa-eye me-2 text-success"></i> Preview File
                </h6>
                
                @php
                    $extension = pathinfo($dokumen->file_name, PATHINFO_EXTENSION);
                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                    $isPdf = strtolower($extension) == 'pdf';
                    $isDoc = in_array(strtolower($extension), ['doc', 'docx']);
                    
                    $fileClass = 'other';
                    if ($isPdf) $fileClass = 'pdf';
                    elseif ($isImage) $fileClass = 'image';
                    elseif ($isDoc) $fileClass = 'doc';
                @endphp
                
                <div class="preview-box">
                    @if($isImage)
                        <img src="{{ asset('storage/' . $dokumen->file_path) }}" alt="{{ $dokumen->judul }}" 
                             class="img-fluid rounded" style="max-height: 400px;">
                        <div class="mt-3">
                            <a href="{{ asset('storage/' . $dokumen->file_path) }}" download class="btn btn-success">
                                <i class="fas fa-download me-2"></i> Download Gambar
                            </a>
                        </div>
                    @elseif($isPdf)
                        <div class="file-icon-large pdf">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <p class="mb-2">{{ $dokumen->file_name }}</p>
                        <p class="text-muted small">{{ $dokumen->formatted_file_size }}</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ asset('storage/' . $dokumen->file_path) }}" target="_blank" class="btn btn-primary">
                                <i class="fas fa-eye me-2"></i> Lihat PDF
                            </a>
                            <a href="{{ asset('storage/' . $dokumen->file_path) }}" download class="btn btn-success">
                                <i class="fas fa-download me-2"></i> Download
                            </a>
                        </div>
                        <!-- PDF Embed untuk preview langsung -->
                        <div class="mt-3">
                            <embed src="{{ asset('storage/' . $dokumen->file_path) }}" type="application/pdf" 
                                   width="100%" height="400px" style="border-radius: 8px;">
                        </div>
                    @elseif($isDoc)
                        <div class="file-icon-large doc">
                            <i class="fas fa-file-word"></i>
                        </div>
                        <p class="mb-2">{{ $dokumen->file_name }}</p>
                        <p class="text-muted small">{{ $dokumen->formatted_file_size }}</p>
                        <a href="{{ asset('storage/' . $dokumen->file_path) }}" download class="btn btn-success">
                            <i class="fas fa-download me-2"></i> Download File
                        </a>
                    @else
                        <div class="file-icon-large other">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <p class="mb-2">{{ $dokumen->file_name }}</p>
                        <p class="text-muted small">{{ $dokumen->formatted_file_size }}</p>
                        <a href="{{ asset('storage/' . $dokumen->file_path) }}" download class="btn btn-success">
                            <i class="fas fa-download me-2"></i> Download File
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="text-center mt-4">
            <a href="{{ route('admin.dokumen.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Dokumen
            </a>
        </div>
    </div>
</div>
@endsection