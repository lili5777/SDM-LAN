@extends('admin.partials.layout')

@section('title', 'Semua Dokumen - JABLAYMEN')
@section('page-title', 'Semua Dokumen')

@section('styles')
<style>
    .drive-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .drive-search {
        position: relative;
        flex: 1;
        max-width: 360px;
    }
    .drive-search input {
        width: 100%;
        padding: 9px 14px 9px 38px;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        font-size: 0.875rem;
        background: #f8fafc;
        color: #0f172a;
        outline: none;
        transition: all 0.2s;
    }
    .drive-search input:focus {
        border-color: #166534;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(22,101,52,0.08);
    }
    .drive-search i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
    }

    /* Stats bar */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        margin-bottom: 1.75rem;
    }
    .stat-chip {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.875rem 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .stat-chip-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .stat-chip-icon.green  { background: #d1fae5; color: #166534; }
    .stat-chip-icon.blue   { background: #dbeafe; color: #1d4ed8; }
    .stat-chip-icon.amber  { background: #fef3c7; color: #92400e; }
    .stat-chip-icon.red    { background: #fee2e2; color: #991b1b; }
    .stat-chip-val  { font-size: 1.35rem; font-weight: 700; color: #0f172a; line-height: 1; }
    .stat-chip-lbl  { font-size: 0.75rem; color: #64748b; margin-top: 2px; }

    /* Section label */
    .section-lbl {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94a3b8;
        margin: 0 0 0.75rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-lbl::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f1f5f9;
    }

    /* Pegawai grid */
    .pegawai-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 12px;
    }
    .pegawai-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem 1.1rem;
        cursor: pointer;
        text-decoration: none;
        display: block;
        transition: all 0.18s;
    }
    .pegawai-card:hover {
        border-color: #166534;
        box-shadow: 0 4px 16px rgba(22,101,52,0.1);
        transform: translateY(-2px);
    }
    .pegawai-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
        color: #166534;
        margin-bottom: 10px;
    }
    .pegawai-nama {
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .pegawai-nip  { font-size: 0.75rem; color: #64748b; }
    .pegawai-unit { font-size: 0.72rem; color: #94a3b8; margin-top: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .dok-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 10px;
        background: #f0fdf4;
        color: #166534;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 20px;
        border: 1px solid #bbf7d0;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #94a3b8;
    }
    .empty-state i { font-size: 3rem; opacity: 0.25; display: block; margin-bottom: 1rem; }

    /* Search filter result */
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 20px;
    }
    .filter-tag a { color: #dc2626; text-decoration: none; font-weight: 700; margin-left: 2px; }
</style>
@endsection

@section('content')

{{-- Top bar --}}
<div class="drive-topbar">
    <div>
        <h5 class="fw-bold mb-0">Semua Dokumen</h5>
        <small class="text-muted">Pilih pegawai untuk melihat arsip dokumennya</small>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <form method="GET" action="{{ route('admin.dokumen.index') }}">
            <div class="drive-search">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Cari nama atau NIP..."
                    value="{{ request('search') }}" autocomplete="off">
            </div>
        </form>
        <a href="{{ route('admin.dokumen.create') }}" class="btn btn-success rounded-pill px-4">
            <i class="fas fa-cloud-upload-alt me-2"></i> Upload Dokumen
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-chip">
        <div class="stat-chip-icon green"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-chip-val">{{ number_format($totalPegawai) }}</div>
            <div class="stat-chip-lbl">Total Pegawai</div>
        </div>
    </div>
    <div class="stat-chip">
        <div class="stat-chip-icon blue"><i class="fas fa-file-alt"></i></div>
        <div>
            <div class="stat-chip-val">{{ number_format($totalDokumen) }}</div>
            <div class="stat-chip-lbl">Total Dokumen</div>
        </div>
    </div>
    <div class="stat-chip">
        <div class="stat-chip-icon green"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="stat-chip-val">{{ number_format($totalAktif) }}</div>
            <div class="stat-chip-lbl">Dokumen Aktif</div>
        </div>
    </div>
    <div class="stat-chip">
        <div class="stat-chip-icon amber"><i class="fas fa-calendar-alt"></i></div>
        <div>
            <div class="stat-chip-val">{{ number_format($total30Hari) }}</div>
            <div class="stat-chip-lbl">30 Hari Terakhir</div>
        </div>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-3 mb-3">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Filter tag aktif --}}
@if(request('search'))
<div class="filter-bar">
    <span class="filter-tag">
        <i class="fas fa-search" style="font-size:0.7rem"></i>
        "{{ request('search') }}"
        <a href="{{ route('admin.dokumen.index') }}">×</a>
    </span>
    <small class="text-muted">{{ $pegawais->count() }} hasil ditemukan</small>
</div>
@endif

{{-- Pegawai grid --}}
<div class="section-lbl">Daftar Pegawai</div>

@if($pegawais->count() > 0)
<div class="pegawai-grid">
    @foreach($pegawais as $pegawai)
    @php
        $inisial = collect(explode(' ', $pegawai->nama))
            ->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->implode('');
        $jumlahDok = $pegawai->dokumens_count ?? 0;
    @endphp
    <a href="{{ route('admin.dokumen.pegawai', $pegawai->id) }}" class="pegawai-card">
        <div class="pegawai-avatar">{{ $inisial }}</div>
        <div class="pegawai-nama">{{ $pegawai->nama }}</div>
        <div class="pegawai-nip">{{ $pegawai->nip }}</div>
        <div class="pegawai-unit">{{ $pegawai->unitKerja->nama ?? '-' }}</div>
        <div class="dok-badge">
            <i class="fas fa-file-alt" style="font-size:0.65rem"></i>
            {{ $jumlahDok }} dokumen
        </div>
    </a>
    @endforeach
</div>
@else
<div class="empty-state">
    <i class="fas fa-users"></i>
    <h5 class="text-muted">Tidak ada pegawai ditemukan</h5>
    @if(request('search'))
        <p class="text-muted">Coba kata kunci lain</p>
        <a href="{{ route('admin.dokumen.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            Reset pencarian
        </a>
    @endif
</div>
@endif

@endsection