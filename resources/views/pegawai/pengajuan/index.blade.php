@extends('admin.partials.layout')

@section('title', 'Status Pengajuan - JABLAYMEN')
@section('page-title', 'Status Pengajuan')

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
    
    .filter-bar {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .pengajuan-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .pengajuan-item {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .pengajuan-item:hover {
        background: #f8fafc;
    }
    
    .badge-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .badge-status.menunggu { background: #fef3c7; color: #d97706; }
    .badge-status.disetujui { background: #d1fae5; color: #065f46; }
    .badge-status.ditolak { background: #fee2e2; color: #dc2626; }
    
    .badge-jenis {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .badge-jenis.upload { background: #dbeafe; color: #2563eb; }
    .badge-jenis.hapus { background: #fee2e2; color: #dc2626; }
    
    .review-note {
        background: #f1f5f9;
        border-radius: 10px;
        padding: 0.75rem;
        margin-top: 0.75rem;
        font-size: 0.85rem;
    }
</style>
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-4 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ number_format($pengajuans->total()) }}</div>
                    <div class="stats-label">Total Pengajuan</div>
                </div>
                <div class="stats-icon primary">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ number_format($pengajuans->where('status', 'menunggu')->count()) }}</div>
                    <div class="stats-label">Menunggu Persetujuan</div>
                </div>
                <div class="stats-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ number_format($pengajuans->where('status', 'disetujui')->count()) }}</div>
                    <div class="stats-label">Disetujui</div>
                </div>
                <div class="stats-icon success">
                    <i class="fas fa-check-circle"></i>
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
                <i class="fas fa-history me-2 text-success"></i> Riwayat Pengajuan Saya
            </h4>
            <a href="{{ route('dokumen.create') }}" class="btn btn-success">
                <i class="fas fa-upload me-1"></i> Pengajuan Baru
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <select id="filterJenis" class="form-select form-select-sm" style="width: 130px;">
                <option value="">Semua Jenis</option>
                <option value="upload">Upload</option>
                <option value="hapus">Hapus</option>
            </select>
            <select id="filterStatus" class="form-select form-select-sm" style="width: 150px;">
                <option value="">Semua Status</option>
                <option value="menunggu">Menunggu</option>
                <option value="disetujui">Disetujui</option>
                <option value="ditolak">Ditolak</option>
            </select>
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari judul..." style="width: 250px;">
            <button class="btn btn-sm btn-outline-secondary" id="resetFilter">
                <i class="fas fa-undo-alt me-1"></i> Reset
            </button>
        </div>

        <!-- Pengajuan List -->
        <div class="pengajuan-card" id="pengajuanList">
            @forelse($pengajuans as $pengajuan)
            <div class="pengajuan-item" data-jenis="{{ $pengajuan->jenis }}" data-status="{{ $pengajuan->status }}" data-judul="{{ strtolower($pengajuan->judul) }}">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="d-flex gap-3">
                            <div>
                                <span class="badge-jenis {{ $pengajuan->jenis }}">
                                    <i class="fas fa-{{ $pengajuan->jenis == 'upload' ? 'upload' : 'trash' }} me-1"></i>
                                    {{ ucfirst($pengajuan->jenis) }}
                                </span>
                                <span class="badge-status {{ $pengajuan->status }} ms-1">
                                    <i class="fas fa-{{ $pengajuan->status == 'menunggu' ? 'clock' : ($pengajuan->status == 'disetujui' ? 'check' : 'times') }} me-1"></i>
                                    {{ ucfirst($pengajuan->status) }}
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ Str::limit($pengajuan->judul, 40) }}</h6>
                                <div class="small text-muted">
                                    <i class="fas fa-folder me-1"></i> {{ $pengajuan->folder->kategoriFolder->nama ?? '' }} → {{ $pengajuan->folder->nama ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">Tanggal Pengajuan</div>
                        <div class="fw-semibold">{{ $pengajuan->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="col-md-3">
                        @if($pengajuan->status != 'menunggu')
                        <div class="small text-muted">Tanggal Diproses</div>
                        <div>{{ $pengajuan->reviewed_at ? date('d/m/Y H:i', strtotime($pengajuan->reviewed_at)) : '-' }}</div>
                        @endif
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="{{ route('pengajuan.status.show', $pengajuan->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> Detail
                        </a>
                        @if($pengajuan->status == 'menunggu')
                        <button class="btn btn-sm btn-outline-danger batalkan-pengajuan mt-1 mt-md-0"
                                data-id="{{ $pengajuan->id }}" data-judul="{{ $pengajuan->judul }}">
                            <i class="fas fa-times me-1"></i> Batal
                        </button>
                        @endif
                    </div>
                </div>
                
                @if($pengajuan->catatan_admin && $pengajuan->status == 'ditolak')
                <div class="review-note">
                    <i class="fas fa-comment-dots me-2 text-danger"></i>
                    <strong>Alasan Ditolak:</strong> {{ $pengajuan->catatan_admin }}
                </div>
                @endif
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3 opacity-25"></i>
                <h5 class="text-muted">Belum ada pengajuan</h5>
                <p class="text-muted">Anda belum pernah mengajukan dokumen</p>
                <a href="{{ route('dokumen.create') }}" class="btn btn-success mt-2">
                    <i class="fas fa-upload me-1"></i> Ajukan Dokumen Sekarang
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $pengajuans->links() }}
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
                <p class="text-muted small">Pengajuan yang dibatalkan tidak dapat dikembalikan. Anda dapat mengajukan ulang dokumen yang sama.</p>
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
    // Filter and Search
    const filterJenis = document.getElementById('filterJenis');
    const filterStatus = document.getElementById('filterStatus');
    const searchInput = document.getElementById('searchInput');
    const resetBtn = document.getElementById('resetFilter');
    const pengajuanItems = document.querySelectorAll('.pengajuan-item');
    
    function filterPengajuan() {
        const jenis = filterJenis.value;
        const status = filterStatus.value;
        const search = searchInput.value.toLowerCase();
        
        pengajuanItems.forEach(item => {
            const itemJenis = item.dataset.jenis;
            const itemStatus = item.dataset.status;
            const itemJudul = item.dataset.judul || '';
            
            const matchJenis = !jenis || itemJenis === jenis;
            const matchStatus = !status || itemStatus === status;
            const matchSearch = !search || itemJudul.includes(search);
            
            item.style.display = (matchJenis && matchStatus && matchSearch) ? '' : 'none';
        });
    }
    
    filterJenis.addEventListener('change', filterPengajuan);
    filterStatus.addEventListener('change', filterPengajuan);
    searchInput.addEventListener('keyup', filterPengajuan);
    
    resetBtn.addEventListener('click', function() {
        filterJenis.value = '';
        filterStatus.value = '';
        searchInput.value = '';
        filterPengajuan();
    });
    
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