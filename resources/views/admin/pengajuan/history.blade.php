@extends('admin.partials.layout')

@section('title', 'Riwayat Pengajuan - JABLAYMEN')
@section('page-title', 'Riwayat Pengajuan')

@section('styles')
<style>
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }
    .history-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .history-item {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .history-item:hover {
        background: #f8fafc;
    }
    .badge-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
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
    <div class="col-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="fas fa-history me-2 text-success"></i> Riwayat Pengajuan
            </h4>
            <a href="{{ route('pengajuan.pending') }}" class="btn btn-outline-success">
                <i class="fas fa-inbox me-1"></i> Pengajuan Pending
            </a>
        </div>

        <!-- Filter Card -->
        <div class="filter-card">
            <form method="GET" action="{{ route('pengajuan.history') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Jenis Pengajuan</label>
                    <select name="jenis" class="form-select">
                        <option value="">Semua</option>
                        <option value="upload" {{ request('jenis') == 'upload' ? 'selected' : '' }}>Upload</option>
                        <option value="hapus" {{ request('jenis') == 'hapus' ? 'selected' : '' }}>Hapus</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Judul atau nama pegawai..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- History List -->
        <div class="history-card">
            @forelse($pengajuans as $pengajuan)
            <div class="history-item">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <span class="badge-jenis {{ $pengajuan->jenis }}">
                                    <i class="fas fa-{{ $pengajuan->jenis == 'upload' ? 'upload' : 'trash' }} me-1"></i>
                                    {{ ucfirst($pengajuan->jenis) }}
                                </span>
                                <span class="badge-status {{ $pengajuan->status }} ms-1">
                                    <i class="fas fa-{{ $pengajuan->status == 'disetujui' ? 'check' : 'times' }} me-1"></i>
                                    {{ ucfirst($pengajuan->status) }}
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ Str::limit($pengajuan->judul, 40) }}</h6>
                                <div class="small text-muted">{{ $pengajuan->folder->nama ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="small text-muted">Pegawai</div>
                        <div class="fw-semibold">{{ $pengajuan->pegawai->nama ?? '-' }}</div>
                    </div>
                    <div class="col-md-2">
                        <div class="small text-muted">Diajukan</div>
                        <div>{{ $pengajuan->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="col-md-2">
                        <div class="small text-muted">Diproses</div>
                        <div>{{ $pengajuan->reviewed_at ? date('d/m/Y H:i', strtotime($pengajuan->reviewed_at)) : '-' }}</div>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="{{ route('pengajuan.show', $pengajuan->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> Detail
                        </a>
                    </div>
                </div>
                
                @if($pengajuan->catatan_admin)
                <div class="review-note">
                    <i class="fas fa-comment-dots me-2 text-muted"></i>
                    <strong>Catatan Admin:</strong> {{ $pengajuan->catatan_admin }}
                </div>
                @endif
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-history fa-4x text-muted mb-3 opacity-25"></i>
                <h5 class="text-muted">Tidak ada riwayat pengajuan</h5>
                <p class="text-muted">Belum ada pengajuan yang diproses</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $pengajuans->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection