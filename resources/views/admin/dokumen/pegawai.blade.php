@extends('admin.partials.layout')

@section('title', 'Folder - {{ $pegawai->nama }} - JABLAYMEN')
@section('page-title', 'Dokumen Pegawai')

@section('styles')
<style>
    /* Breadcrumb */
    .drive-breadcrumb {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }
    .drive-breadcrumb a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s;
    }
    .drive-breadcrumb a:hover { color: #166534; }
    .drive-breadcrumb .sep { color: #cbd5e1; }
    .drive-breadcrumb .current { color: #0f172a; font-weight: 600; }

    /* Pegawai info bar */
    .pegawai-bar {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 1.75rem;
    }
    .peg-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        color: #166534;
        flex-shrink: 0;
    }
    .peg-nama  { font-size: 0.95rem; font-weight: 600; color: #0f172a; }
    .peg-meta  { font-size: 0.78rem; color: #64748b; margin-top: 1px; }
    .peg-badges { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 6px; }
    .peg-badge {
        font-size: 0.7rem;
        padding: 2px 10px;
        border-radius: 20px;
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .peg-badge.unit { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }

    /* Section label */
    .section-lbl {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94a3b8;
        margin: 1.25rem 0 0.75rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-lbl::after { content: ''; flex: 1; height: 1px; background: #f1f5f9; }

    /* Folder grid */
    .folder-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 10px;
        margin-bottom: 0.5rem;
    }
    .folder-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.875rem 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        transition: all 0.18s;
        cursor: pointer;
    }
    .folder-card:hover {
        border-color: #166534;
        background: #f0fdf4;
        transform: translateY(-1px);
    }
    .folder-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #fef9c3;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .folder-icon i { color: #ca8a04; font-size: 1.1rem; }
    .folder-icon.empty { background: #f1f5f9; }
    .folder-icon.empty i { color: #94a3b8; }
    .folder-name {
        font-size: 0.82rem;
        font-weight: 600;
        color: #0f172a;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .folder-count {
        font-size: 0.72rem;
        color: #64748b;
        margin-top: 2px;
    }
    .folder-empty-label {
        font-size: 0.68rem;
        color: #94a3b8;
        font-style: italic;
        margin-top: 2px;
    }

    /* Empty kategori */
    .no-folder {
        background: #f8fafc;
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        color: #94a3b8;
        font-size: 0.82rem;
        border: 1px dashed #e2e8f0;
    }

    /* Action bar */
    .action-bar {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        margin-bottom: 1.5rem;
    }
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
<div class="drive-breadcrumb">
    <a href="{{ route('admin.dokumen.index') }}">
        <i class="fas fa-home me-1"></i> Semua Dokumen
    </a>
    <span class="sep">/</span>
    <span class="current">{{ $pegawai->nama }}</span>
</div>

{{-- Action bar --}}
<div class="action-bar">
    <a href="{{ route('admin.dokumen.create') }}?pegawai_id={{ $pegawai->id }}"
        class="btn btn-success btn-sm rounded-pill px-3">
        <i class="fas fa-cloud-upload-alt me-1"></i> Upload ke Pegawai Ini
    </a>
    <a href="{{ route('admin.dokumen.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

{{-- Pegawai info bar --}}
@php
    $inisial = collect(explode(' ', $pegawai->nama))
        ->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->implode('');
@endphp
<div class="pegawai-bar">
    <div class="peg-avatar">{{ $inisial }}</div>
    <div class="flex-grow-1">
        <div class="peg-nama">{{ $pegawai->nama }}</div>
        <div class="peg-meta">NIP: {{ $pegawai->nip }}</div>
        <div class="peg-badges">
            <span class="peg-badge unit">{{ $pegawai->unitKerja->nama ?? '-' }}</span>
            <span class="peg-badge">{{ $pegawai->jabatan ?? '-' }}</span>
            @if($pegawai->golongan)
            <span class="peg-badge">Gol. {{ $pegawai->golongan }}</span>
            @endif
        </div>
    </div>
    <div class="text-end">
        <div style="font-size:1.5rem;font-weight:700;color:#166534;line-height:1">
            {{ number_format($totalDokumenPegawai) }}
        </div>
        <div style="font-size:0.72rem;color:#64748b">total dokumen</div>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-3 mb-3">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Folder per kategori --}}
@forelse($foldersByKategori as $kategoriNama => $folders)
<div class="section-lbl">
    <i class="fas fa-layer-group" style="font-size:0.7rem"></i>
    {{ $kategoriNama ?? 'Tanpa Kategori' }}
</div>

<div class="folder-grid">
    @foreach($folders as $folder)
    @php $jumlah = $folder->dokumens_by_pegawai_count ?? 0; @endphp
    <a href="{{ route('admin.dokumen.folder', ['pegawai' => $pegawai->id, 'folder' => $folder->id]) }}"
        class="folder-card">
        <div class="folder-icon {{ $jumlah == 0 ? 'empty' : '' }}">
            <i class="fas fa-folder{{ $jumlah == 0 ? '' : '-open' }}"></i>
        </div>
        <div style="min-width:0">
            <div class="folder-name">{{ $folder->nama }}</div>
            @if($jumlah > 0)
                <div class="folder-count">{{ $jumlah }} dokumen</div>
            @else
                <div class="folder-empty-label">Belum ada dokumen</div>
            @endif
        </div>
    </a>
    @endforeach
</div>
@empty
<div class="no-folder">
    <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-25"></i>
    Tidak ada folder tersedia
</div>
@endforelse

@endsection