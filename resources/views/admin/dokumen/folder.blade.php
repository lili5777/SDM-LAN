@extends('admin.partials.layout')

@section('title', '{{ $folder->nama }} - {{ $pegawai->nama }} - JABLAYMEN')
@section('page-title', 'Isi Folder')

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
    .drive-breadcrumb a { color: #64748b; text-decoration: none; transition: color 0.15s; }
    .drive-breadcrumb a:hover { color: #166534; }
    .drive-breadcrumb .sep { color: #cbd5e1; }
    .drive-breadcrumb .current { color: #0f172a; font-weight: 600; }

    /* Folder info bar */
    .folder-bar {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 1.5rem;
    }
    .folder-bar-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #fef9c3;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .folder-bar-icon i { color: #ca8a04; font-size: 1.2rem; }
    .peg-badge {
        font-size: 0.7rem;
        padding: 2px 10px;
        border-radius: 20px;
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
        display: inline-block;
        margin-right: 4px;
    }

    /* Filter toolbar */
    .filter-toolbar {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    .filter-toolbar input {
        flex: 1;
        min-width: 180px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.82rem;
        outline: none;
    }
    .filter-toolbar input:focus {
        border-color: #166534;
        box-shadow: 0 0 0 2px rgba(22,101,52,0.08);
    }
    .filter-toolbar select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 0.82rem;
        background: #fff;
        outline: none;
    }

    /* File table */
    .file-table-wrap {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
    }
    .file-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.83rem;
    }
    .file-table thead tr {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .file-table thead th {
        padding: 10px 14px;
        font-weight: 600;
        color: #64748b;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }
    .file-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.1s;
    }
    .file-table tbody tr:last-child { border-bottom: none; }
    .file-table tbody tr:hover { background: #f8fafc; }
    .file-table td { padding: 10px 14px; vertical-align: middle; }

    /* File name cell */
    .file-name-cell { display: flex; align-items: center; gap: 10px; }
    .file-type-badge {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: 700;
        flex-shrink: 0;
    }
    .file-type-badge.pdf   { background: #fee2e2; color: #dc2626; }
    .file-type-badge.image { background: #dbeafe; color: #2563eb; }
    .file-type-badge.doc   { background: #d1fae5; color: #065f46; }
    .file-type-badge.other { background: #fef3c7; color: #d97706; }

    .file-judul {
        font-weight: 600;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 240px;
    }
    .file-nomor { font-size: 0.72rem; color: #64748b; margin-top: 1px; }

    /* Status badge */
    .status-pill {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .status-pill.aktif      { background: #d1fae5; color: #166534; }
    .status-pill.diarsipkan { background: #fee2e2; color: #991b1b; }

    /* Action buttons */
    .actions { display: flex; gap: 4px; }
    .act-btn {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: transparent;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s;
    }
    .act-btn:hover { background: #f0fdf4; border-color: #166534; color: #166534; }
    .act-btn.dl:hover  { background: #eff6ff; border-color: #3b82f6; color: #2563eb; }
    .act-btn.prv:hover { background: #fef9c3; border-color: #ca8a04; color: #92400e; }

    /* Empty state */
    .empty-folder {
        text-align: center;
        padding: 4rem 2rem;
        color: #94a3b8;
    }
    .empty-folder i { font-size: 3rem; opacity: 0.2; display: block; margin-bottom: 1rem; }

    /* Summary bar */
    .summary-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 14px;
        border-top: 1px solid #f1f5f9;
        font-size: 0.78rem;
        color: #64748b;
        background: #f8fafc;
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
    <a href="{{ route('admin.dokumen.pegawai', $pegawai->id) }}">{{ $pegawai->nama }}</a>
    <span class="sep">/</span>
    <span class="current">{{ $folder->nama }}</span>
</div>

{{-- Action bar --}}
<div class="d-flex justify-content-end gap-2 mb-3">
    <a href="{{ route('admin.dokumen.create') }}?pegawai_id={{ $pegawai->id }}&folder_id={{ $folder->id }}"
        class="btn btn-success btn-sm rounded-pill px-3">
        <i class="fas fa-cloud-upload-alt me-1"></i> Upload ke Folder Ini
    </a>
    <a href="{{ route('admin.dokumen.pegawai', $pegawai->id) }}"
        class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Folder
    </a>
</div>

{{-- Folder info bar --}}
<div class="folder-bar">
    <div class="folder-bar-icon">
        <i class="fas fa-folder-open"></i>
    </div>
    <div class="flex-grow-1">
        <div style="font-size:0.95rem;font-weight:600;color:#0f172a">{{ $folder->nama }}</div>
        <div style="font-size:0.78rem;color:#64748b;margin-top:2px">
            {{ $folder->kategoriFolder->nama ?? '-' }}
        </div>
        <div style="margin-top:6px">
            <span class="peg-badge">{{ $pegawai->nama }}</span>
            <span class="peg-badge" style="background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe">
                {{ $pegawai->nip }}
            </span>
        </div>
    </div>
    <div class="text-end">
        <div style="font-size:1.5rem;font-weight:700;color:#166534;line-height:1">
            {{ number_format($dokumens->total()) }}
        </div>
        <div style="font-size:0.72rem;color:#64748b">dokumen</div>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-3 mb-3">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Filter toolbar --}}
<form method="GET" action="{{ route('admin.dokumen.folder', ['pegawai' => $pegawai->id, 'folder' => $folder->id]) }}">
    <div class="filter-toolbar">
        <i class="fas fa-search text-muted" style="font-size:0.8rem"></i>
        <input type="text" name="search" placeholder="Cari judul atau nomor dokumen..."
            value="{{ request('search') }}">
        <select name="status" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="aktif"      {{ request('status') == 'aktif'      ? 'selected' : '' }}>Aktif</option>
            <option value="diarsipkan" {{ request('status') == 'diarsipkan' ? 'selected' : '' }}>Diarsipkan</option>
        </select>
        <button type="submit" class="btn btn-success btn-sm px-3 rounded-pill">
            <i class="fas fa-search me-1"></i> Cari
        </button>
        @if(request()->hasAny(['search','status']))
        <a href="{{ route('admin.dokumen.folder', ['pegawai' => $pegawai->id, 'folder' => $folder->id]) }}"
            class="btn btn-outline-secondary btn-sm px-2 rounded-pill" title="Reset">
            <i class="fas fa-times"></i>
        </a>
        @endif
    </div>
</form>

{{-- File list table --}}
<div class="file-table-wrap">
    <table class="file-table">
        <thead>
            <tr>
                <th width="40%">Nama Dokumen</th>
                <th width="15%">Tanggal</th>
                <th width="12%">Ukuran</th>
                <th width="13%">Status</th>
                <th width="20%" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dokumens as $dokumen)
            @php
                $ext = strtolower(pathinfo($dokumen->file_name, PATHINFO_EXTENSION));
                $typeClass = 'other';
                $typeLabel = strtoupper($ext);
                if ($ext === 'pdf') $typeClass = 'pdf';
                elseif (in_array($ext, ['jpg','jpeg','png','gif'])) { $typeClass = 'image'; $typeLabel = 'IMG'; }
                elseif (in_array($ext, ['doc','docx'])) { $typeClass = 'doc'; $typeLabel = 'DOC'; }
            @endphp
            <tr>
                <td>
                    <div class="file-name-cell">
                        <div class="file-type-badge {{ $typeClass }}">{{ $typeLabel }}</div>
                        <div style="min-width:0">
                            <div class="file-judul" title="{{ $dokumen->judul }}">{{ $dokumen->judul }}</div>
                            @if($dokumen->nomor_dokumen)
                                <div class="file-nomor">No: {{ $dokumen->nomor_dokumen }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td style="color:#64748b;font-size:0.8rem;white-space:nowrap">
                    {{ $dokumen->tanggal_dokumen ? date('d/m/Y', strtotime($dokumen->tanggal_dokumen)) : '-' }}
                </td>
                <td style="color:#64748b;font-size:0.8rem;white-space:nowrap">
                    {{ $dokumen->formatted_file_size }}
                </td>
                <td>
                    <span class="status-pill {{ $dokumen->status }}">
                        <i class="fas fa-{{ $dokumen->status == 'aktif' ? 'circle' : 'archive' }}"
                            style="font-size:0.55rem"></i>
                        {{ ucfirst($dokumen->status) }}
                    </span>
                </td>
                <td>
                    <div class="actions justify-content-center">
                        <a href="{{ route('admin.dokumen.show', $dokumen->id) }}"
                            class="act-btn" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.dokumen.download', $dokumen->id) }}"
                            class="act-btn dl" title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                        @if(in_array($ext, ['pdf','jpg','jpeg','png']))
                        <a href="{{ route('admin.dokumen.preview', $dokumen->id) }}" target="_blank"
                            class="act-btn prv" title="Preview">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="empty-folder">
                        <i class="fas fa-folder-open"></i>
                        <h6 class="text-muted mb-1">Folder ini kosong</h6>
                        <p class="text-muted small mb-3">Belum ada dokumen di folder ini</p>
                        <a href="{{ route('admin.dokumen.create') }}?pegawai_id={{ $pegawai->id }}&folder_id={{ $folder->id }}"
                            class="btn btn-success btn-sm rounded-pill px-4">
                            <i class="fas fa-cloud-upload-alt me-1"></i> Upload Dokumen
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($dokumens->total() > 0)
    <div class="summary-bar">
        <span>
            Menampilkan {{ $dokumens->firstItem() }}–{{ $dokumens->lastItem() }}
            dari {{ number_format($dokumens->total()) }} dokumen
        </span>
        <div>
            {{ $dokumens->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

@endsection