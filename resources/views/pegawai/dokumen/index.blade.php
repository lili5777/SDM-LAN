@extends('admin.partials.layout')

@section('title', 'Dokumen Saya - JABLAYMEN')
@section('page-title', 'Dokumen Saya')

@section('styles')
<style>
*, *::before, *::after { box-sizing: border-box; }

/* ── Top bar ─────────────────────────────────────────── */
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
    max-width: 340px;
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

/* ── Stats row ───────────────────────────────────────── */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
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
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.stat-chip-icon.green { background: #d1fae5; color: #166534; }
.stat-chip-icon.blue  { background: #dbeafe; color: #1d4ed8; }
.stat-chip-icon.amber { background: #fef3c7; color: #92400e; }
.stat-chip-val { font-size: 1.35rem; font-weight: 700; color: #0f172a; line-height: 1; }
.stat-chip-lbl { font-size: 0.72rem; color: #64748b; margin-top: 2px; }

/* ── Section label ───────────────────────────────────── */
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
.section-lbl::after { content: ''; flex: 1; height: 1px; background: #f1f5f9; }

/* ── Folder grid ─────────────────────────────────────── */
.folder-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
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
    position: relative;
}
.folder-card:hover {
    border-color: #166534;
    background: #f0fdf4;
    transform: translateY(-1px);
}
.folder-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: #fef9c3;
    display: flex; align-items: center; justify-content: center;
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
.folder-count { font-size: 0.72rem; color: #64748b; margin-top: 2px; }

/* ── File list table ─────────────────────────────────── */
.file-table-wrap {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    margin-top: 1.25rem;
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
    font-size: 0.72rem;
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

.file-name-cell { display: flex; align-items: center; gap: 10px; }
.file-type-badge {
    width: 30px; height: 30px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.62rem; font-weight: 700; flex-shrink: 0;
}
.file-type-badge.pdf   { background: #fee2e2; color: #dc2626; }
.file-type-badge.image { background: #dbeafe; color: #2563eb; }
.file-type-badge.doc   { background: #d1fae5; color: #065f46; }
.file-type-badge.other { background: #fef3c7; color: #d97706; }
.file-judul {
    font-weight: 600; color: #0f172a;
    white-space: nowrap; overflow: hidden;
    text-overflow: ellipsis; max-width: 200px;
}
.file-nomor { font-size: 0.72rem; color: #64748b; margin-top: 1px; }

/* ── Action buttons ──────────────────────────────────── */
.actions { display: flex; gap: 4px; }
.act-btn {
    width: 30px; height: 30px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: transparent;
    color: #64748b;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.78rem;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s;
}
.act-btn:hover        { background: #f0fdf4; border-color: #166534; color: #166534; }
.act-btn.dl:hover     { background: #eff6ff; border-color: #3b82f6; color: #2563eb; }
.act-btn.del:hover    { background: #fff1f2; border-color: #f43f5e; color: #e11d48; }

/* ── View toggle ─────────────────────────────────────── */
.view-toggle {
    display: flex; gap: 4px;
    background: #f1f5f9;
    border-radius: 8px;
    padding: 3px;
}
.vt-btn {
    width: 30px; height: 28px;
    border: none; background: transparent;
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.8rem; color: #64748b;
    cursor: pointer; transition: all 0.15s;
}
.vt-btn.active { background: #fff; color: #166534; box-shadow: 0 1px 3px rgba(0,0,0,.1); }

/* ── Grid view untuk dokumen ─────────────────────────── */
.doc-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 10px;
    margin-top: 1.25rem;
}
.doc-grid-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem;
    transition: all 0.18s;
    position: relative;
}
.doc-grid-card:hover { border-color: #bbf7d0; box-shadow: 0 4px 12px rgba(22,101,52,0.1); }
.doc-grid-icon {
    width: 44px; height: 44px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; margin-bottom: 10px;
}
.doc-grid-icon.pdf   { background: #fee2e2; color: #dc2626; }
.doc-grid-icon.image { background: #dbeafe; color: #2563eb; }
.doc-grid-icon.doc   { background: #d1fae5; color: #065f46; }
.doc-grid-icon.other { background: #fef3c7; color: #d97706; }
.doc-grid-title {
    font-size: 0.8rem; font-weight: 600; color: #0f172a;
    overflow: hidden; text-overflow: ellipsis;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    margin-bottom: 4px;
}
.doc-grid-meta { font-size: 0.7rem; color: #94a3b8; }
.doc-grid-actions {
    display: flex; gap: 4px; margin-top: 10px;
    padding-top: 8px;
    border-top: 1px solid #f1f5f9;
}

/* ── Empty state ─────────────────────────────────────── */
.empty-state {
    text-align: center; padding: 3rem 2rem;
    color: #94a3b8;
}
.empty-state i { font-size: 3rem; opacity: 0.2; display: block; margin-bottom: 1rem; }

/* ── Back breadcrumb ─────────────────────────────────── */
.drive-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 0.8rem; margin-bottom: 1.25rem; flex-wrap: wrap;
}
.drive-breadcrumb a { color: #64748b; text-decoration: none; transition: color 0.15s; }
.drive-breadcrumb a:hover { color: #166534; }
.drive-breadcrumb .sep { color: #cbd5e1; }
.drive-breadcrumb .current { color: #0f172a; font-weight: 600; }

/* ── Folder view (show/hide) ─────────────────────────── */
#view-folders { display: block; }
#view-files   { display: none; }

@media (max-width: 576px) {
    .folder-grid { grid-template-columns: 1fr 1fr; }
    .doc-grid    { grid-template-columns: 1fr 1fr; }
    .file-table thead { display: none; }
    .file-table td { display: block; padding: 4px 14px; }
    .file-table tr { border-bottom: 1px solid #f1f5f9; display: block; padding: 8px 0; }
}
</style>
@endsection

@section('content')

{{-- ── TOP BAR ──────────────────────────────────────────── --}}
<div class="drive-topbar">
    <div>
        <h5 class="fw-bold mb-0">Dokumen Saya</h5>
        <small class="text-muted">Arsip dokumen pribadi Anda</small>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <div class="drive-search">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari dokumen..." autocomplete="off">
        </div>
        <a href="{{ route('dokumen.create') }}" class="btn btn-success rounded-pill px-4">
            <i class="fas fa-upload me-2"></i> Ajukan Dokumen
        </a>
    </div>
</div>

{{-- ── STATS ────────────────────────────────────────────── --}}
@php
    $totalDok    = $dokumenList->count();
    $totalKat    = count($struktur);
    $totalFolder = collect($struktur)->sum(fn($f) => count($f));
@endphp
<div class="stats-row">
    <div class="stat-chip">
        <div class="stat-chip-icon green"><i class="fas fa-file-alt"></i></div>
        <div>
            <div class="stat-chip-val">{{ number_format($totalDok) }}</div>
            <div class="stat-chip-lbl">Total Dokumen</div>
        </div>
    </div>
    <div class="stat-chip">
        <div class="stat-chip-icon blue"><i class="fas fa-layer-group"></i></div>
        <div>
            <div class="stat-chip-val">{{ $totalKat }}</div>
            <div class="stat-chip-lbl">Kategori</div>
        </div>
    </div>
    <div class="stat-chip">
        <div class="stat-chip-icon amber"><i class="fas fa-folder"></i></div>
        <div>
            <div class="stat-chip-val">{{ $totalFolder }}</div>
            <div class="stat-chip-lbl">Folder Aktif</div>
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

{{-- ── VIEW: FOLDER GRID ────────────────────────────────── --}}
<div id="view-folders">
    <div id="breadcrumb-root" class="drive-breadcrumb">
        <span class="current"><i class="fas fa-home me-1"></i> Dokumen Saya</span>
    </div>

    @forelse($struktur as $kategori => $foldersInKat)
    <div class="section-lbl">
        <i class="fas fa-layer-group" style="font-size:0.7rem"></i>
        {{ $kategori }}
    </div>
    <div class="folder-grid" style="margin-bottom:1.5rem">
        @foreach($foldersInKat as $folderNama => $item)
        @php $jumlah = count($item['dokumens']); @endphp
        <div class="folder-card"
            onclick="openFolder('{{ addslashes($kategori) }}', '{{ addslashes($folderNama) }}')">
            <div class="folder-icon {{ $jumlah == 0 ? 'empty' : '' }}">
                <i class="fas fa-folder{{ $jumlah > 0 ? '-open' : '' }}"></i>
            </div>
            <div style="min-width:0">
                <div class="folder-name">{{ $folderNama }}</div>
                @if($jumlah > 0)
                    <div class="folder-count">{{ $jumlah }} dokumen</div>
                @else
                    <div class="folder-count" style="color:#94a3b8;font-style:italic">Kosong</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-folder-open"></i>
        <h6 class="text-muted mb-2">Belum ada folder tersedia</h6>
        <p class="text-muted small mb-3">Hubungi Admin untuk mengatur folder dokumen</p>
    </div>
    @endforelse
</div>

{{-- ── VIEW: FILE LIST ──────────────────────────────────── --}}
<div id="view-files">
    {{-- Breadcrumb file view --}}
    <div class="drive-breadcrumb" id="breadcrumb-files">
        <a href="#" onclick="closeFolder(); return false;">
            <i class="fas fa-home me-1"></i> Dokumen Saya
        </a>
        <span class="sep">/</span>
        <span id="bc-kategori" class="text-muted"></span>
        <span class="sep">/</span>
        <span id="bc-folder" class="current"></span>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-3">
        <div style="font-size:0.82rem;color:#64748b">
            <span id="file-count-label"></span>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <div class="view-toggle">
                <button class="vt-btn active" id="btn-list" onclick="setView('list')" title="Tampilan list">
                    <i class="fas fa-list"></i>
                </button>
                <button class="vt-btn" id="btn-grid" onclick="setView('grid')" title="Tampilan grid">
                    <i class="fas fa-th-large"></i>
                </button>
            </div>
            <button onclick="closeFolder()" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </button>
        </div>
    </div>

    {{-- List view --}}
    <div id="list-view">
        <div class="file-table-wrap">
            <table class="file-table">
                <thead>
                    <tr>
                        <th width="42%">Nama Dokumen</th>
                        <th width="15%">Tanggal</th>
                        <th width="13%">Ukuran</th>
                        <th width="15%">No. Dokumen</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="file-table-body"></tbody>
            </table>
        </div>
    </div>

    {{-- Grid view --}}
    <div id="grid-view" style="display:none">
        <div class="doc-grid" id="doc-grid-body"></div>
    </div>
</div>

{{-- ── MODAL AJUKAN HAPUS ───────────────────────────────── --}}
<div class="modal fade" id="ajukanHapusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">
                    <i class="fas fa-trash-alt text-danger me-2"></i>
                    Ajukan Penghapusan Dokumen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="ajukanHapusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning rounded-3" style="font-size:0.82rem">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Dokumen tetap tersedia sampai Admin menyetujui penghapusan.
                    </div>
                    <div class="mb-1" style="font-size:0.82rem;color:#64748b">Dokumen yang akan dihapus:</div>
                    <div class="fw-bold text-danger mb-3" id="hapusJudul"></div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.82rem;font-weight:600">
                            Alasan Penghapusan <span class="text-danger">*</span>
                        </label>
                        <textarea name="alasan" class="form-control" rows="3" required
                            placeholder="Jelaskan alasan penghapusan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        <i class="fas fa-paper-plane me-1"></i> Ajukan Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Data JSON untuk JS ──────────────────────────────────── --}}
@php
$dokumenJson = [];
foreach ($struktur as $kat => $foldersInKat) {
    $dokumenJson[$kat] = [];
    foreach ($foldersInKat as $folderNama => $item) {
        $dokumenJson[$kat][$folderNama] = array_map(function($d) use ($kat) {
            return [
                'id'        => $d->id,
                'judul'     => $d->judul,
                'nomor'     => $d->nomor_dokumen,
                'tanggal'   => $d->tanggal_dokumen ? date('d/m/Y', strtotime($d->tanggal_dokumen)) : '-',
                'file_name' => $d->file_name,
                'file_size' => $d->formatted_file_size ?? '-',
                'file_url'  => asset('storage/' . $d->file_path),
                'ext'       => strtolower(pathinfo($d->file_name, PATHINFO_EXTENSION)),
                'folder'    => $d->folder->nama ?? '-',
                'kategori'  => $kat,
            ];
        }, $item['dokumens']);
    }
}
@endphp
<script>
const DOKUMEN_DATA = @json($dokumenJson);
const HAPUS_BASE_URL = "{{ url('dokumen-saya') }}";
</script>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    let currentView = 'list';
    let hapusModal;
    try { hapusModal = new bootstrap.Modal(document.getElementById('ajukanHapusModal')); } catch(e){}

    /* ── Type → class & label ────────────────────────── */
    function typeInfo(ext) {
        if (ext === 'pdf')                              return { cls: 'pdf',   lbl: 'PDF', icon: 'fa-file-pdf' };
        if (['jpg','jpeg','png','gif'].includes(ext))   return { cls: 'image', lbl: 'IMG', icon: 'fa-file-image' };
        if (['doc','docx'].includes(ext))               return { cls: 'doc',   lbl: 'DOC', icon: 'fa-file-word' };
        return { cls: 'other', lbl: ext.toUpperCase().slice(0,4) || 'FILE', icon: 'fa-file-alt' };
    }

    /* ── Open folder ─────────────────────────────────── */
    window.openFolder = function(kategori, folderNama) {
        const kat   = DOKUMEN_DATA[kategori];
        if (!kat) return;
        const docs  = kat[folderNama] || [];

        document.getElementById('bc-kategori').textContent = kategori;
        document.getElementById('bc-folder').textContent   = folderNama;
        document.getElementById('file-count-label').textContent =
            docs.length + ' dokumen dalam folder ini';

        renderList(docs);
        renderGrid(docs);

        document.getElementById('view-folders').style.display = 'none';
        document.getElementById('view-files').style.display   = 'block';
    };

    window.closeFolder = function() {
        document.getElementById('view-files').style.display   = 'none';
        document.getElementById('view-folders').style.display = 'block';
    };

    /* ── Render list view ────────────────────────────── */
    function renderList(docs) {
        const tbody = document.getElementById('file-table-body');
        if (!docs.length) {
            tbody.innerHTML = `<tr><td colspan="5">
                <div class="empty-state" style="padding:2rem">
                    <i class="fas fa-folder-open" style="font-size:2rem;opacity:.2;display:block;margin-bottom:.5rem"></i>
                    <div style="font-size:.82rem;color:#94a3b8">Folder ini kosong</div>
                </div></td></tr>`;
            return;
        }
        tbody.innerHTML = docs.map(d => {
            const t = typeInfo(d.ext);
            return `<tr>
                <td>
                    <div class="file-name-cell">
                        <div class="file-type-badge ${t.cls}">${t.lbl}</div>
                        <div style="min-width:0">
                            <div class="file-judul" title="${escHtml(d.judul)}">${escHtml(d.judul)}</div>
                        </div>
                    </div>
                </td>
                <td style="color:#64748b;font-size:.8rem;white-space:nowrap">${d.tanggal}</td>
                <td style="color:#64748b;font-size:.8rem;white-space:nowrap">${d.file_size}</td>
                <td style="color:#64748b;font-size:.8rem">${d.nomor ? escHtml(d.nomor) : '<span style="opacity:.4">—</span>'}</td>
                <td>
                    <div class="actions justify-content-center">
                        <a href="${d.file_url}" target="_blank" class="act-btn" title="Lihat">
                            <i class="fas fa-eye"></i></a>
                        <a href="${d.file_url}" download class="act-btn dl" title="Unduh">
                            <i class="fas fa-download"></i></a>
                        <button onclick="openHapus(${d.id}, '${escJs(d.judul)}')"
                            class="act-btn del" title="Ajukan Hapus">
                            <i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>`;
        }).join('');
    }

    /* ── Render grid view ────────────────────────────── */
    function renderGrid(docs) {
        const wrap = document.getElementById('doc-grid-body');
        if (!docs.length) {
            wrap.innerHTML = `<div style="grid-column:1/-1">
                <div class="empty-state" style="padding:2rem">
                    <i class="fas fa-folder-open" style="font-size:2rem;opacity:.2;display:block;margin-bottom:.5rem"></i>
                    <div style="font-size:.82rem;color:#94a3b8">Folder ini kosong</div>
                </div></div>`;
            return;
        }
        wrap.innerHTML = docs.map(d => {
            const t = typeInfo(d.ext);
            return `<div class="doc-grid-card">
                <div class="doc-grid-icon ${t.cls}"><i class="fas ${t.icon}"></i></div>
                <div class="doc-grid-title" title="${escHtml(d.judul)}">${escHtml(d.judul)}</div>
                <div class="doc-grid-meta">${d.tanggal} · ${d.file_size}</div>
                <div class="doc-grid-actions">
                    <a href="${d.file_url}" target="_blank" class="act-btn" title="Lihat" style="flex:1;justify-content:center">
                        <i class="fas fa-eye"></i></a>
                    <a href="${d.file_url}" download class="act-btn dl" title="Unduh" style="flex:1;justify-content:center">
                        <i class="fas fa-download"></i></a>
                    <button onclick="openHapus(${d.id}, '${escJs(d.judul)}')"
                        class="act-btn del" title="Hapus" style="flex:1;justify-content:center">
                        <i class="fas fa-trash"></i></button>
                </div>
            </div>`;
        }).join('');
    }

    /* ── Toggle list / grid ──────────────────────────── */
    window.setView = function(v) {
        currentView = v;
        document.getElementById('list-view').style.display = v === 'list' ? 'block' : 'none';
        document.getElementById('grid-view').style.display = v === 'grid' ? 'block' : 'none';
        document.getElementById('btn-list').classList.toggle('active', v === 'list');
        document.getElementById('btn-grid').classList.toggle('active', v === 'grid');
    };

    /* ── Modal hapus ─────────────────────────────────── */
    window.openHapus = function(id, judul) {
        document.getElementById('hapusJudul').textContent = judul;
        document.getElementById('ajukanHapusForm').action = `${HAPUS_BASE_URL}/${id}/ajukan-hapus`;
        hapusModal.show();
    };

    /* ── Search ──────────────────────────────────────── */
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        if (!q) {
            document.querySelectorAll('.folder-card').forEach(c => c.style.display = '');
            document.querySelectorAll('.kategori-group-wrap, .section-lbl').forEach(c => c.style.display = '');
            return;
        }
        document.querySelectorAll('.folder-card').forEach(card => {
            const name = card.querySelector('.folder-name').textContent.toLowerCase();
            card.style.display = name.includes(q) ? '' : 'none';
        });
    });

    /* ── Escape helpers ──────────────────────────────── */
    function escHtml(str) {
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function escJs(str) {
        return String(str).replace(/'/g, "\\'").replace(/\n/g,'');
    }
});
</script>
@endsection