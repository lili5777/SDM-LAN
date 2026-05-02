@extends('admin.partials.layout')

@section('title', 'Dokumen Saya - JABLAYMEN')
@section('page-title', 'Dokumen Saya')

@section('styles')
<style>
    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s;
        height: 100%;
    }
    .stats-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1;
    }
    .stats-label {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 5px;
    }
    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stats-icon.primary { background: #e8f5e9; color: #166534; }
    .stats-icon.info { background: #e3f2fd; color: #0ea5e9; }
    
    .kategori-group {
        margin-bottom: 2rem;
    }
    .kategori-header {
        background: linear-gradient(135deg, #166534 0%, #15803d 100%);
        border-radius: 12px;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        color: white;
    }
    .kategori-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }
    .dokumen-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.2s;
    }
    .dokumen-card:hover {
        border-color: #bbf7d2;
        box-shadow: 0 4px 12px rgba(22,101,52,0.1);
    }
    .file-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }
    .file-icon.pdf { background: #fee2e2; color: #dc2626; }
    .file-icon.image { background: #dbeafe; color: #2563eb; }
    .file-icon.doc { background: #d1fae5; color: #065f46; }
    .file-icon.other { background: #fef3c7; color: #d97706; }
    
    .btn-action {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        margin: 0 2px;
    }
    
    .empty-folder {
        text-align: center;
        padding: 2rem;
        background: #f8fafc;
        border-radius: 12px;
        color: #94a3b8;
    }
</style>
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ number_format($dokumens->sum(function($group) { return $group->count(); })) }}</div>
                    <div class="stats-label">Total Dokumen Saya</div>
                </div>
                <div class="stats-icon primary">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ number_format($dokumens->count()) }}</div>
                    <div class="stats-label">Kategori Folder</div>
                </div>
                <div class="stats-icon info">
                    <i class="fas fa-folder-open"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="fas fa-folder-open me-2 text-success"></i> Arsip Dokumen Saya
            </h4>
            <a href="{{ route('dokumen.create') }}" class="btn btn-success">
                <i class="fas fa-upload me-1"></i> Ajukan Dokumen Baru
            </a>
        </div>

        <!-- Dokumen by Kategori -->
        @forelse($dokumens as $kategori => $dokumenList)
        <div class="kategori-group">
            <div class="kategori-header">
                <h5>
                    <i class="fas fa-folder-open me-2"></i> {{ $kategori }}
                    <span class="badge bg-light text-success ms-2">{{ $dokumenList->count() }}</span>
                </h5>
            </div>
            
            <div class="row">
                @foreach($dokumenList as $dokumen)
                @php
                    $extension = pathinfo($dokumen->file_name, PATHINFO_EXTENSION);
                    $fileClass = 'other';
                    if (in_array(strtolower($extension), ['pdf'])) $fileClass = 'pdf';
                    elseif (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) $fileClass = 'image';
                    elseif (in_array(strtolower($extension), ['doc', 'docx'])) $fileClass = 'doc';
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="dokumen-card">
                        <div class="d-flex gap-3">
                            <div class="file-icon {{ $fileClass }}">
                                <i class="fas fa-{{ $fileClass == 'pdf' ? 'file-pdf' : ($fileClass == 'image' ? 'file-image' : ($fileClass == 'doc' ? 'file-word' : 'file-alt')) }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ Str::limit($dokumen->judul, 40) }}</h6>
                                @if($dokumen->nomor_dokumen)
                                <small class="text-muted d-block">No: {{ $dokumen->nomor_dokumen }}</small>
                                @endif
                                <small class="text-muted d-block">
                                    <i class="fas fa-calendar-alt me-1"></i> {{ $dokumen->tanggal_dokumen ? date('d/m/Y', strtotime($dokumen->tanggal_dokumen)) : '-' }}
                                </small>
                                <small class="text-muted d-block">
                                    <i class="fas fa-folder me-1"></i> {{ $dokumen->folder->nama ?? '-' }}
                                </small>
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $dokumen->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary btn-action">
                                        <i class="fas fa-eye me-1"></i> Lihat
                                    </a>
                                    <a href="{{ asset('storage/' . $dokumen->file_path) }}" download class="btn btn-sm btn-outline-success btn-action">
                                        <i class="fas fa-download me-1"></i> Unduh
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-action ajukan-hapus" 
                                            data-id="{{ $dokumen->id }}" data-judul="{{ $dokumen->judul }}">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-5x text-muted mb-3 opacity-25"></i>
            <h5 class="text-muted">Belum ada dokumen</h5>
            <p class="text-muted">Anda belum memiliki dokumen yang sudah disetujui</p>
            <a href="{{ route('dokumen.create') }}" class="btn btn-success mt-2">
                <i class="fas fa-upload me-1"></i> Ajukan Dokumen Baru
            </a>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Ajukan Hapus -->
<div class="modal fade" id="ajukanHapusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">
                    <i class="fas fa-trash-alt text-danger me-2"></i> Ajukan Penghapusan Dokumen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="ajukanHapusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-3">Anda akan mengajukan penghapusan dokumen:</p>
                    <h6 class="text-danger mb-3" id="hapusJudul"></h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Alasan Penghapusan <span class="text-danger">*</span></label>
                        <textarea name="alasan" class="form-control" rows="4" required 
                                  placeholder="Jelaskan alasan Anda ingin menghapus dokumen ini..."></textarea>
                        <small class="text-muted">Alasan akan dikirimkan ke Admin untuk proses persetujuan</small>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Pengajuan penghapusan akan diproses oleh Admin. Dokumen akan tetap tersedia sampai disetujui.
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-paper-plane me-2"></i> Ajukan Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Ajukan hapus modal
    const ajukanModal = new bootstrap.Modal(document.getElementById('ajukanHapusModal'));
    const ajukanForm = document.getElementById('ajukanHapusForm');
    const hapusJudul = document.getElementById('hapusJudul');
    
    document.querySelectorAll('.ajukan-hapus').forEach(btn => {
        btn.addEventListener('click', function() {
            const dokumenId = this.dataset.id;
            const judul = this.dataset.judul;
            
            hapusJudul.textContent = judul;
            ajukanForm.action = `{{ url('dokumen-saya') }}/${dokumenId}/ajukan-hapus`;
            ajukanModal.show();
        });
    });
</script>
@endsection