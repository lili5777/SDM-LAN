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
        padding-bottom: 0.5rem;
        border-bottom: 1px dashed #e2e8f0;
    }
    .info-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .info-label {
        width: 140px;
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
    .badge-status-lg {
        padding: 0.5rem 1.2rem;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .badge-status-lg.menunggu { background: #fef3c7; color: #d97706; }
    .badge-status-lg.disetujui { background: #d1fae5; color: #065f46; }
    .badge-status-lg.ditolak { background: #fee2e2; color: #dc2626; }
    
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    .timeline-dot {
        position: absolute;
        left: -1.5rem;
        top: 0;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: white;
        border: 2px solid #166534;
    }
    .timeline-dot.completed {
        background: #166534;
    }
    .timeline-dot.rejected {
        border-color: #dc2626;
        background: #dc2626;
    }
    .timeline-content {
        background: #f8fafc;
        border-radius: 10px;
        padding: 0.75rem 1rem;
    }
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
                        <p class="mb-0 opacity-75 small">Diajukan: {{ $pengajuan->created_at->format('d/m/Y H:i') }}</p>
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
                        <img src="{{ asset('storage/' . $pengajuan->file_path) }}" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                        <div class="mt-2">
                            <small class="text-muted">{{ $pengajuan->file_name }}</small>
                            <br>
                            <a href="{{ asset('storage/' . $pengajuan->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="fas fa-eye me-1"></i> Lihat Full
                            </a>
                        </div>
                    @elseif($isPdf)
                        <i class="fas fa-file-pdf fa-4x text-danger mb-2"></i>
                        <p class="mb-1">{{ $pengajuan->file_name }}</p>
                        <p class="text-muted small">{{ number_format($pengajuan->file_size / 1024, 2) }} KB</p>
                        <a href="{{ asset('storage/' . $pengajuan->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> Lihat PDF
                        </a>
                    @else
                        <i class="fas fa-file-alt fa-4x text-muted mb-2"></i>
                        <p class="mb-1">{{ $pengajuan->file_name }}</p>
                        <p class="text-muted small">{{ number_format($pengajuan->file_size / 1024, 2) }} KB</p>
                        <a href="{{ asset('storage/' . $pengajuan->file_path) }}" download class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download me-1"></i> Download File
                        </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Timeline Status -->
            <div class="detail-section">
                <h6 class="mb-3">
                    <i class="fas fa-chart-line me-2 text-success"></i> Timeline Pengajuan
                </h6>
                
                <div class="timeline">
                    <!-- Step 1: Pengajuan Dibuat -->
                    <div class="timeline-item">
                        <div class="timeline-dot completed"></div>
                        <div class="timeline-content">
                            <div class="fw-semibold">Pengajuan Dibuat</div>
                            <div class="small text-muted">{{ $pengajuan->created_at->format('d/m/Y H:i:s') }}</div>
                            <div class="small">Pengajuan telah dikirim ke Admin untuk diproses</div>
                        </div>
                    </div>
                    
                    <!-- Step 2: Diproses Admin -->
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $pengajuan->status != 'menunggu' ? 'completed' : '' }} {{ $pengajuan->status == 'ditolak' ? 'rejected' : '' }}"></div>
                        <div class="timeline-content">
                            <div class="fw-semibold">
                                @if($pengajuan->status == 'menunggu')
                                    Menunggu Diproses Admin
                                @elseif($pengajuan->status == 'disetujui')
                                    Disetujui oleh Admin
                                @else
                                    Ditolak oleh Admin
                                @endif
                            </div>
                            @if($pengajuan->reviewed_at)
                            <div class="small text-muted">{{ date('d/m/Y H:i:s', strtotime($pengajuan->reviewed_at)) }}</div>
                            @endif
                            @if($pengajuan->reviewer)
                            <div class="small">Oleh: {{ $pengajuan->reviewer->name }}</div>
                            @endif
                            @if($pengajuan->catatan_admin)
                            <div class="small text-danger mt-1">
                                <i class="fas fa-comment me-1"></i> Catatan: {{ $pengajuan->catatan_admin }}
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Step 3: Selesai (untuk upload yang disetujui) -->
                    @if($pengajuan->jenis == 'upload' && $pengajuan->status == 'disetujui')
                    <div class="timeline-item">
                        <div class="timeline-dot completed"></div>
                        <div class="timeline-content">
                            <div class="fw-semibold">Dokumen Tersimpan</div>
                            <div class="small">Dokumen telah tersimpan di arsip Anda</div>
                            <a href="{{ route('dokumen.saya') }}" class="btn btn-sm btn-link text-success p-0 mt-1">
                                <i class="fas fa-folder-open me-1"></i> Lihat Dokumen Saya
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Step 3: Selesai (untuk hapus yang disetujui) -->
                    @if($pengajuan->jenis == 'hapus' && $pengajuan->status == 'disetujui')
                    <div class="timeline-item">
                        <div class="timeline-dot completed"></div>
                        <div class="timeline-content">
                            <div class="fw-semibold">Dokumen Dihapus</div>
                            <div class="small">Dokumen telah dihapus dari arsip Anda</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="text-center mt-4">
            <a href="{{ route('pengajuan.status') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Status Pengajuan
            </a>
            @if($pengajuan->status == 'menunggu')
            <button class="btn btn-outline-danger ms-2 batalkan-pengajuan" 
                    data-id="{{ $pengajuan->id }}" data-judul="{{ $pengajuan->judul }}">
                <i class="fas fa-times me-2"></i> Batalkan Pengajuan
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Modal Batalkan Pengajuan -->
<div class="modal fade" id="batalkanModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i> Batalkan Pengajuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-3">Anda akan membatalkan pengajuan:</p>
                <h6 class="text-warning mb-3" id="batalkanJudul"></h6>
                <p class="text-muted small">Pengajuan yang dibatalkan tidak dapat dikembalikan.</p>
                <form id="batalkanForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning px-4">
                        <i class="fas fa-check me-2"></i> Ya, Batalkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Batalkan pengajuan modal
    const batalkanModal = new bootstrap.Modal(document.getElementById('batalkanModal'));
    const batalkanForm = document.getElementById('batalkanForm');
    const batalkanJudul = document.getElementById('batalkanJudul');
    
    document.querySelectorAll('.batalkan-pengajuan').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const judul = this.dataset.judul;
            
            batalkanJudul.textContent = judul;
            batalkanForm.action = `{{ url('pengajuan-saya') }}/${id}/batalkan`;
            batalkanModal.show();
        });
    });
</script>
@endsection