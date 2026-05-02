@extends('admin.partials.layout')

@section('title', 'Detail Pengajuan - JABLAYMEN')
@section('page-title', 'Detail Pengajuan')

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
    }
    .info-label {
        width: 150px;
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
        padding: 1rem;
        text-align: center;
    }
    .file-icon {
        font-size: 3rem;
        color: #166534;
        margin-bottom: 0.5rem;
    }
    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1rem;
    }
    .badge-status-lg {
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .badge-status-lg.menunggu { background: #fef3c7; color: #d97706; }
    .badge-status-lg.disetujui { background: #d1fae5; color: #065f46; }
    .badge-status-lg.ditolak { background: #fee2e2; color: #dc2626; }
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
                            {{ $pengajuan->jenis == 'upload' ? 'Pengajuan Upload' : 'Pengajuan Hapus' }}
                        </h5>
                        <p class="mb-0 opacity-75 small">{{ $pengajuan->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="badge-status-lg {{ $pengajuan->status }}">
                            <i class="fas fa-{{ $pengajuan->status == 'menunggu' ? 'clock' : ($pengajuan->status == 'disetujui' ? 'check' : 'times') }} me-1"></i>
                            {{ ucfirst($pengajuan->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informasi Pengajuan -->
            <div class="detail-section">
                <h6 class="mb-3">
                    <i class="fas fa-info-circle me-2 text-success"></i> Informasi Pengajuan
                </h6>
                
                <div class="info-row">
                    <div class="info-label">Judul Dokumen</div>
                    <div class="info-value fw-semibold">{{ $pengajuan->judul }}</div>
                </div>
                
                @if($pengajuan->nomor_dokumen)
                <div class="info-row">
                    <div class="info-label">Nomor Dokumen</div>
                    <div class="info-value">{{ $pengajuan->nomor_dokumen }}</div>
                </div>
                @endif
                
                @if($pengajuan->tanggal_dokumen)
                <div class="info-row">
                    <div class="info-label">Tanggal Dokumen</div>
                    <div class="info-value">{{ date('d/m/Y', strtotime($pengajuan->tanggal_dokumen)) }}</div>
                </div>
                @endif
                
                <div class="info-row">
                    <div class="info-label">Folder Tujuan</div>
                    <div class="info-value">
                        {{ $pengajuan->folder->kategoriFolder->nama ?? '' }} → {{ $pengajuan->folder->nama ?? '-' }}
                    </div>
                </div>
                
                @if($pengajuan->keterangan)
                <div class="info-row">
                    <div class="info-label">Keterangan</div>
                    <div class="info-value">{{ $pengajuan->keterangan }}</div>
                </div>
                @endif
                
                @if($pengajuan->alasan_pengajuan)
                <div class="info-row">
                    <div class="info-label">Alasan Pengajuan</div>
                    <div class="info-value">{{ $pengajuan->alasan_pengajuan }}</div>
                </div>
                @endif
            </div>

            <!-- Informasi Pegawai -->
            <div class="detail-section">
                <h6 class="mb-3">
                    <i class="fas fa-user me-2 text-success"></i> Informasi Pegawai
                </h6>
                
                <div class="info-row">
                    <div class="info-label">Nama Pegawai</div>
                    <div class="info-value">{{ $pengajuan->pegawai->nama ?? '-' }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">NIP</div>
                    <div class="info-value">{{ $pengajuan->pegawai->nip ?? '-' }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Unit Kerja</div>
                    <div class="info-value">{{ $pengajuan->pegawai->unitKerja->nama ?? '-' }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $pengajuan->pegawai->user->email ?? '-' }}</div>
                </div>
            </div>

            <!-- File Preview (untuk upload) -->
            @if($pengajuan->jenis == 'upload' && $pengajuan->file_path)
            <div class="detail-section">
                <h6 class="mb-3">
                    <i class="fas fa-file me-2 text-success"></i> File Dokumen
                </h6>
                
                <div class="preview-box">
                    @php
                        $extension = pathinfo($pengajuan->file_name, PATHINFO_EXTENSION);
                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                        $isPdf = strtolower($extension) == 'pdf';
                    @endphp
                    
                    @if($isImage)
                        <img src="{{ asset('storage/' . $pengajuan->file_path) }}" alt="Preview" class="img-fluid rounded" style="max-height: 300px;">
                    @elseif($isPdf)
                        <div class="file-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <p class="mb-2">{{ $pengajuan->file_name }}</p>
                        <p class="text-muted small">{{ number_format($pengajuan->file_size / 1024, 2) }} KB</p>
                        <a href="{{ asset('storage/' . $pengajuan->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> Lihat PDF
                        </a>
                    @else
                        <div class="file-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <p class="mb-2">{{ $pengajuan->file_name }}</p>
                        <p class="text-muted small">{{ number_format($pengajuan->file_size / 1024, 2) }} KB</p>
                        <a href="{{ asset('storage/' . $pengajuan->file_path) }}" download class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download me-1"></i> Download File
                        </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Action Buttons (hanya untuk status menunggu) -->
            @if($pengajuan->status == 'menunggu')
            <div class="detail-section border-bottom-0">
                <div class="action-buttons">
                    <form action="{{ route('pengajuan.approve', $pengajuan->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg px-4" onclick="return confirm('Setujui pengajuan ini?')">
                            <i class="fas fa-check me-2"></i> Setujui
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger btn-lg px-4" id="showRejectForm">
                        <i class="fas fa-times me-2"></i> Tolak
                    </button>
                </div>
                
                <!-- Form Tolak -->
                <div id="rejectFormContainer" style="display: none; margin-top: 1.5rem;">
                    <form action="{{ route('pengajuan.reject', $pengajuan->id) }}" method="POST">
                        @csrf
                        <div class="border rounded-3 p-3 bg-light">
                            <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="alasan" class="form-control" rows="4" required 
                                      placeholder="Berikan alasan penolakan yang jelas..."></textarea>
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="hideRejectForm">
                                    <i class="fas fa-times me-1"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-check me-1"></i> Konfirmasi Tolak
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Informasi Review (jika sudah diproses) -->
            @if($pengajuan->reviewed_by)
            <div class="detail-section bg-light">
                <h6 class="mb-3">
                    <i class="fas fa-clipboard-list me-2 text-success"></i> Informasi Review
                </h6>
                
                <div class="info-row">
                    <div class="info-label">Direview oleh</div>
                    <div class="info-value">{{ $pengajuan->reviewer->name ?? '-' }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Waktu Review</div>
                    <div class="info-value">{{ $pengajuan->reviewed_at ? date('d/m/Y H:i:s', strtotime($pengajuan->reviewed_at)) : '-' }}</div>
                </div>
                
                @if($pengajuan->catatan_admin)
                <div class="info-row">
                    <div class="info-label">Catatan</div>
                    <div class="info-value">{{ $pengajuan->catatan_admin }}</div>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Tombol Kembali -->
        <div class="text-center mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>
</div>

<script>
    // Toggle reject form
    const showRejectBtn = document.getElementById('showRejectForm');
    const rejectFormContainer = document.getElementById('rejectFormContainer');
    const hideRejectBtn = document.getElementById('hideRejectForm');
    
    if (showRejectBtn) {
        showRejectBtn.addEventListener('click', function() {
            rejectFormContainer.style.display = 'block';
            showRejectBtn.style.display = 'none';
        });
    }
    
    if (hideRejectBtn) {
        hideRejectBtn.addEventListener('click', function() {
            rejectFormContainer.style.display = 'none';
            if (showRejectBtn) showRejectBtn.style.display = 'inline-block';
        });
    }
</script>
@endsection