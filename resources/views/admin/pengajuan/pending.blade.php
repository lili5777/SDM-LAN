@extends('admin.partials.layout')

@section('title', 'Pengajuan Menunggu - JABLAYMEN')
@section('page-title', 'Pengajuan Menunggu Persetujuan')

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
    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(22,101,52,0.1);
    }
    .stats-number {
        font-size: 2rem;
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
    .stats-icon.upload { background: #dbeafe; color: #2563eb; }
    .stats-icon.hapus { background: #fee2e2; color: #dc2626; }
    
    .pengajuan-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .pengajuan-item {
        padding: 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .pengajuan-item:hover {
        background: #f8fafc;
    }
    .badge-jenis {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .badge-jenis.upload { background: #dbeafe; color: #2563eb; }
    .badge-jenis.hapus { background: #fee2e2; color: #dc2626; }
    
    .filter-bar {
        background: white;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .filter-bar .form-select {
        width: auto;
        min-width: 150px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ $pengajuans->total() }}</div>
                    <div class="stats-label">Total Pengajuan</div>
                </div>
                <div class="stats-icon upload">
                    <i class="fas fa-inbox"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ $pengajuans->where('jenis', 'upload')->count() }}</div>
                    <div class="stats-label">Pengajuan Upload</div>
                </div>
                <div class="stats-icon upload">
                    <i class="fas fa-upload"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ $pengajuans->where('jenis', 'hapus')->count() }}</div>
                    <div class="stats-label">Pengajuan Hapus</div>
                </div>
                <div class="stats-icon hapus">
                    <i class="fas fa-trash"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ $pengajuans->where('created_at', '>=', now()->subDays(7))->count() }}</div>
                    <div class="stats-label">7 Hari Terakhir</div>
                </div>
                <div class="stats-icon upload">
                    <i class="fas fa-calendar-week"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="d-flex gap-2 flex-wrap w-100">
                <select id="filterJenis" class="form-select form-select-sm">
                    <option value="">Semua Jenis</option>
                    <option value="upload">Upload</option>
                    <option value="hapus">Hapus</option>
                </select>
                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari judul atau nama pegawai..." style="width: 250px;">
                <button class="btn btn-sm btn-success" id="refreshBtn">
                    <i class="fas fa-sync-alt me-1"></i> Refresh
                </button>
                <div class="ms-auto">
                    <a href="{{ route('pengajuan.history') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-history me-1"></i> Lihat Riwayat
                    </a>
                </div>
            </div>
        </div>

        <!-- Pengajuan List -->
        <div class="pengajuan-card" id="pengajuanList">
            @forelse($pengajuans as $pengajuan)
            <div class="pengajuan-item" data-jenis="{{ $pengajuan->jenis }}" data-title="{{ strtolower($pengajuan->judul) }}" data-pegawai="{{ strtolower($pengajuan->pegawai->nama ?? '') }}">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="stats-icon {{ $pengajuan->jenis }}" style="width: 45px; height: 45px;">
                                    <i class="fas fa-{{ $pengajuan->jenis == 'upload' ? 'upload' : 'trash' }}"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ Str::limit($pengajuan->judul, 50) }}</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge-jenis {{ $pengajuan->jenis }}">
                                        <i class="fas fa-{{ $pengajuan->jenis == 'upload' ? 'upload' : 'trash' }} me-1"></i>
                                        {{ ucfirst($pengajuan->jenis) }}
                                    </span>
                                    <span class="badge" style="background: #fef3c7; color: #d97706;">
                                        <i class="fas fa-clock me-1"></i> Menunggu
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted mb-1">
                            <i class="fas fa-user me-1"></i> Pegawai
                        </div>
                        <div class="fw-semibold">{{ $pengajuan->pegawai->nama ?? '-' }}</div>
                        <div class="small text-muted">{{ $pengajuan->pegawai->nip ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted mb-1">
                            <i class="fas fa-folder me-1"></i> Folder Tujuan
                        </div>
                        <div class="fw-semibold">{{ $pengajuan->folder->nama ?? '-' }}</div>
                        <div class="small text-muted">{{ $pengajuan->folder->kategoriFolder->nama ?? '-' }}</div>
                    </div>
                    <div class="col-md-2">
                        <div class="small text-muted mb-1">
                            <i class="fas fa-calendar me-1"></i> Diajukan
                        </div>
                        <div>{{ $pengajuan->created_at->format('d/m/Y H:i') }}</div>
                        <div class="small text-muted">{{ $pengajuan->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('pengajuan.show', $pengajuan->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                            <form action="{{ route('pengajuan.approve', $pengajuan->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui pengajuan ini?')">
                                    <i class="fas fa-check me-1"></i> Setujui
                                </button>
                            </form>
                            <button type="button" class="btn btn-sm btn-danger reject-btn" 
                                    data-id="{{ $pengajuan->id }}" data-judul="{{ $pengajuan->judul }}">
                                <i class="fas fa-times me-1"></i> Tolak
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3 opacity-25"></i>
                <h5 class="text-muted">Tidak ada pengajuan pending</h5>
                <p class="text-muted">Semua pengajuan sudah diproses</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $pengajuans->links() }}
        </div>
    </div>
</div>

<!-- Modal Tolak Pengajuan -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle text-danger me-2"></i> Tolak Pengajuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-3">Anda akan menolak pengajuan:</p>
                    <h6 class="text-danger mb-3" id="rejectJudul"></h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan" class="form-control" rows="4" required 
                                  placeholder="Berikan alasan penolakan yang jelas..."></textarea>
                        <small class="text-muted">Alasan akan dikirimkan ke pegawai sebagai notifikasi</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-check me-2"></i> Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Filter and Search
    const filterJenis = document.getElementById('filterJenis');
    const searchInput = document.getElementById('searchInput');
    const pengajuanItems = document.querySelectorAll('.pengajuan-item');
    
    function filterPengajuan() {
        const jenis = filterJenis.value;
        const search = searchInput.value.toLowerCase();
        
        pengajuanItems.forEach(item => {
            const itemJenis = item.dataset.jenis;
            const itemTitle = item.dataset.title || '';
            const itemPegawai = item.dataset.pegawai || '';
            
            const matchJenis = !jenis || itemJenis === jenis;
            const matchSearch = !search || itemTitle.includes(search) || itemPegawai.includes(search);
            
            item.style.display = (matchJenis && matchSearch) ? '' : 'none';
        });
    }
    
    filterJenis.addEventListener('change', filterPengajuan);
    searchInput.addEventListener('keyup', filterPengajuan);
    
    // Reject Modal
    const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
    const rejectForm = document.getElementById('rejectForm');
    const rejectJudul = document.getElementById('rejectJudul');
    
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const judul = this.dataset.judul;
            
            rejectJudul.textContent = judul;
            rejectForm.action = `{{ url('pengajuan') }}/${id}/reject`;
            rejectModal.show();
        });
    });
    
    // Refresh button
    document.getElementById('refreshBtn').addEventListener('click', function() {
        location.reload();
    });
</script>
@endsection