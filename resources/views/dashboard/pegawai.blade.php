@extends('admin.partials.layout')

@section('title', 'Dashboard Pegawai - JABLAYMEN')
@section('page-title', 'Dashboard')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
{{-- <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet"> --}}
<style>
*, *::before, *::after { box-sizing: border-box; }

:root {
    --g50:#e8f5e9; --g100:#c8e6c9; --g200:#a5d6a7; --g400:#4ade80;
    --g600:#166534; --g700:#15803d; --g800:#14532d; --g900:#052e16;
    --a50:#fff8e1; --a400:#fbbf24; --a600:#d97706; --a800:#92400e;
    --b50:#dbeafe; --b400:#60a5fa; --b600:#2563eb; --b800:#1e3a8a;
    --r50:#fee2e2; --r400:#f87171; --r600:#dc2626; --r800:#7f1d1d;
    --text:#0f172a; --text2:#475569; --text3:#94a3b8;
    --bd:#e8f0ea; --bg:#f0fdf4;
    --rad:14px; --radlg:20px;
}

body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); }

/* ── Hero ── */
.hero {
    background: linear-gradient(135deg, #14532d 0%, #166534 45%, #15803d 100%);
    border-radius: var(--radlg);
    padding: 2rem 2.5rem;
    position: relative;
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.hero::before { content:''; position:absolute; top:-40px; right:-40px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,0.06); }
.hero::after  { content:''; position:absolute; bottom:-60px; right:80px; width:150px; height:150px; border-radius:50%; background:rgba(255,255,255,0.04); }
.hero-live {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,0.15); border-radius: 20px;
    padding: 4px 12px; font-size: 11px; color: rgba(255,255,255,0.9); margin-bottom: 12px;
}
.live-dot { width:6px; height:6px; background:#4ade80; border-radius:50%; animation: pulse 2s infinite; }
.hero-title {
    font-family: 'Syne', sans-serif; font-size: 1.75rem; font-weight: 800;
    color: #fff; margin-bottom: 4px; line-height: 1.2;
}
.hero-sub  { font-size: 0.82rem; color: rgba(255,255,255,0.6); margin: 0; }
.hero-meta { display:flex; gap:10px; flex-wrap:wrap; margin-top:10px; }
.hero-meta span {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18);
    border-radius: 20px; padding: 4px 12px; font-size: 0.72rem;
    color: rgba(255,255,255,0.85);
}
.hero-deco {
    position: absolute; right: 2.5rem; top: 50%; transform: translateY(-50%);
    font-family: 'Syne', sans-serif; font-weight: 800;
    font-size: 5rem; opacity: 0.06; color: #fff;
}

/* ── Stat Cards ── */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px,1fr)); gap: 14px; margin-bottom: 1.5rem; }
.sc {
    background: #fff; border: 1px solid var(--bd); border-radius: var(--rad);
    padding: 1.1rem 1.25rem; position: relative; overflow: hidden;
    transition: transform .22s, box-shadow .22s;
    animation: fadeUp .4s ease both;
}
.sc:hover { transform: translateY(-4px); box-shadow: 0 10px 26px rgba(22,101,52,.1); }
.sc:nth-child(1){animation-delay:.05s} .sc:nth-child(2){animation-delay:.1s}
.sc:nth-child(3){animation-delay:.15s} .sc:nth-child(4){animation-delay:.2s}
.sc-accent { height:3px; border-radius:var(--rad) var(--rad) 0 0; position:absolute; top:0; left:0; right:0; }
.sc-accent.g{background:var(--g600)} .sc-accent.b{background:var(--b600)}
.sc-accent.a{background:var(--a600)} .sc-accent.r{background:var(--r600)}
.sc-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; margin-bottom:12px; }
.sc-icon.g{background:var(--g50);color:var(--g600)} .sc-icon.b{background:var(--b50);color:var(--b600)}
.sc-icon.a{background:var(--a50);color:var(--a600)} .sc-icon.r{background:var(--r50);color:var(--r600)}
.sc-val { font-family:'Syne',sans-serif; font-size:2rem; font-weight:800; color:var(--text); line-height:1; }
.sc-lbl { font-size:0.75rem; color:var(--text2); margin-top:4px; font-weight:500; }
.sc-pill { display:inline-flex; align-items:center; gap:3px; font-size:0.67rem; font-weight:600; padding:2px 8px; border-radius:20px; margin-top:8px; }
.sc-pill.up   { background:#d1fae5; color:#065f46; }
.sc-pill.warn { background:#fef3c7; color:#92400e; }
.sc-pill.neu  { background:#f1f5f9; color:#64748b; }
.sc-pill.bad  { background:#fee2e2; color:#991b1b; }

/* ── Quick Actions ── */
.quick-actions { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:1.5rem; }
.qa-btn { background:#fff; border:1px solid var(--bd); border-radius:var(--rad); padding:1.25rem 1rem; text-align:center; text-decoration:none; transition:all .22s; display:flex; flex-direction:column; align-items:center; gap:8px; }
.qa-btn:hover { border-color:var(--g600); background:var(--g50); transform:translateY(-3px); box-shadow:0 8px 20px rgba(22,101,52,.1); }
.qa-icon-wrap { width:50px; height:50px; background:linear-gradient(135deg,#14532d,#15803d); border-radius:12px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.25rem; transition:transform .22s; }
.qa-btn:hover .qa-icon-wrap { transform:scale(1.1) rotate(-4deg); }
.qa-lbl { font-size:.82rem; font-weight:700; color:var(--text); }
.qa-sub { font-size:.71rem; color:var(--text3); margin-top:-4px; }

/* ── Layout ── */
.dash-cols { display:grid; grid-template-columns:1fr 320px; gap:16px; align-items:start; }
@media (max-width:900px) { .dash-cols { grid-template-columns:1fr; } }

/* ── Panel ── */
.panel { background:#fff; border:1px solid var(--bd); border-radius:var(--rad); overflow:hidden; margin-bottom:16px; }
.panel-head { display:flex; align-items:center; justify-content:space-between; padding:.875rem 1.25rem; border-bottom:1px solid #f1f5f9; }
.panel-head-title { font-size:.875rem; font-weight:700; color:var(--text); display:flex; align-items:center; gap:8px; }
.panel-head-icon { width:28px; height:28px; border-radius:8px; background:var(--g50); color:var(--g600); display:flex; align-items:center; justify-content:center; font-size:.78rem; }
.panel-body { padding:.875rem 1.1rem; }
.btn-see-all { font-size:.75rem; font-weight:600; color:var(--g600); border:1px solid var(--g200); border-radius:20px; padding:5px 13px; text-decoration:none; transition:all .2s; }
.btn-see-all:hover { background:var(--g600); color:#fff; border-color:transparent; }

/* ── Filter Tabs ── */
.filter-tabs { display:flex; gap:4px; padding:.625rem 1.1rem; border-bottom:1px solid #f1f5f9; flex-wrap:wrap; }
.f-tab { padding:4px 13px; border-radius:20px; font-size:.72rem; font-weight:600; cursor:pointer; transition:all .18s; color:var(--text2); border:none; background:transparent; font-family:'Plus Jakarta Sans',sans-serif; }
.f-tab.active { background:var(--g600); color:#fff; }
.f-tab:not(.active):hover { background:#f1f5f9; }

/* ── Pengajuan item ── */
.pj-item { display:flex; align-items:center; gap:12px; padding:.75rem .875rem; border-radius:10px; border:1px solid #f1f5f9; margin-bottom:8px; text-decoration:none; transition:all .18s; color:var(--text); }
.pj-item:hover { border-color:#bbf7d0; background:#f8fffb; }
.pj-icon { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:.9rem; flex-shrink:0; }
.pj-icon.upload { background:var(--b50); color:var(--b600); }
.pj-icon.hapus  { background:var(--r50); color:var(--r600); }
.pj-title { font-size:.82rem; font-weight:600; color:var(--text); line-height:1.3; }
.pj-sub   { font-size:.71rem; color:var(--text3); margin-top:2px; }
.pj-tags  { display:flex; flex-direction:column; gap:4px; align-items:flex-end; flex-shrink:0; }
.badge-pill { padding:2px 9px; border-radius:20px; font-size:.66rem; font-weight:600; white-space:nowrap; }
.badge-pill.upload    { background:var(--b50);  color:var(--b800); }
.badge-pill.hapus     { background:var(--r50);  color:var(--r800); }
.badge-pill.menunggu  { background:#fef9c3; color:#854d0e; }
.badge-pill.disetujui { background:#d1fae5; color:#065f46; }
.badge-pill.ditolak   { background:var(--r50); color:var(--r800); }
.pj-btn { width:28px; height:28px; border-radius:7px; border:1px solid #e2e8f0; background:#fff; display:flex; align-items:center; justify-content:center; text-decoration:none; color:var(--text2); font-size:.78rem; flex-shrink:0; transition:all .18s; }
.pj-btn:hover { background:var(--g600); color:#fff; border-color:transparent; }

/* ── Mini stats (sidebar) ── */
.mini-stats { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.ms { border-radius:10px; padding:.75rem 1rem; text-align:center; }
.ms-val { font-family:'Syne',sans-serif; font-size:1.6rem; font-weight:800; line-height:1; }
.ms-lbl { font-size:.68rem; color:var(--text2); margin-top:3px; font-weight:500; }
.ms.green-bg { background:var(--g50); } .ms.green-bg .ms-val { color:var(--g600); }
.ms.amber-bg { background:var(--a50); } .ms.amber-bg .ms-val { color:var(--a600); }
.ms.blue-bg  { background:var(--b50); } .ms.blue-bg  .ms-val { color:var(--b600); }
.ms.red-bg   { background:var(--r50); } .ms.red-bg   .ms-val { color:var(--r600); }

/* ── Empty state ── */
.empty-state { text-align:center; padding:2rem; }
.empty-state i { font-size:2.2rem; color:var(--text3); opacity:.4; display:block; margin-bottom:10px; }
.empty-state p { font-size:.82rem; color:var(--text2); margin-bottom:14px; }
.btn-primary-sm { display:inline-flex; align-items:center; gap:6px; background:var(--g600); color:#fff; border:none; padding:7px 18px; border-radius:20px; font-size:.78rem; font-weight:600; cursor:pointer; font-family:inherit; text-decoration:none; transition:background .2s; }
.btn-primary-sm:hover { background:var(--g800); color:#fff; }

/* ── Animations ── */
@keyframes pulse   { 0%,100%{opacity:1} 50%{opacity:.4} }
@keyframes fadeUp  { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

/* ── Responsive ── */
@media (max-width:576px) {
    .stats-grid    { grid-template-columns:1fr 1fr; }
    .hero-title    { font-size:1.3rem; }
    .hero-deco     { display:none; }
    .quick-actions { grid-template-columns:1fr 1fr; }
    .pj-tags       { flex-direction:column; gap:3px; }
}
</style>
@endsection

@section('content')

{{-- ── HERO ── --}}
<div class="hero">
    <div style="position:relative;z-index:1">
        <div class="hero-live"><span class="live-dot"></span> Sistem Aktif</div>
        <h1 class="hero-title">Selamat datang, {{ auth()->user()->name }}! 👋</h1>
        <p class="hero-sub">Sistem Layanan Dokumen SDM Online — JABLAYMEN</p>
        @if(isset($pegawai))
        <div class="hero-meta">
            <span><i class="fas fa-id-card me-1"></i> NIP: {{ $pegawai->nip ?? '-' }}</span>
            <span><i class="fas fa-building me-1"></i> {{ $pegawai->unitKerja->nama ?? '-' }}</span>
        </div>
        @endif
    </div>
    <div class="hero-deco d-none d-md-block">SDM</div>
</div>

{{-- ── STAT CARDS ── --}}
<div class="stats-grid">
    <div class="sc">
        <div class="sc-accent g"></div>
        <div class="sc-icon g"><i class="fas fa-file-alt"></i></div>
        <div class="sc-val count-up" data-target="{{ $total_dokumen }}">0</div>
        <div class="sc-lbl">Total Dokumen Saya</div>
        <div class="sc-pill up"><i class="fas fa-folder" style="font-size:.6rem"></i> Tersimpan</div>
    </div>
    <div class="sc">
        <div class="sc-accent a"></div>
        <div class="sc-icon a"><i class="fas fa-clock"></i></div>
        <div class="sc-val count-up" data-target="{{ $pending_pengajuan }}">0</div>
        <div class="sc-lbl">Pengajuan Pending</div>
        @if($pending_pengajuan > 0)
        <div class="sc-pill warn"><i class="fas fa-exclamation" style="font-size:.6rem"></i> Menunggu Review</div>
        @else
        <div class="sc-pill up"><i class="fas fa-check" style="font-size:.6rem"></i> Bersih</div>
        @endif
    </div>
    <div class="sc">
        <div class="sc-accent b"></div>
        <div class="sc-icon b"><i class="fas fa-check-circle"></i></div>
        <div class="sc-val count-up" data-target="{{ $disetujui_count }}">0</div>
        <div class="sc-lbl">Pengajuan Disetujui</div>
        <div class="sc-pill up"><i class="fas fa-check" style="font-size:.6rem"></i> Disetujui</div>
    </div>
    <div class="sc">
        <div class="sc-accent r"></div>
        <div class="sc-icon r"><i class="fas fa-times-circle"></i></div>
        <div class="sc-val count-up" data-target="{{ $ditolak_count }}">0</div>
        <div class="sc-lbl">Pengajuan Ditolak</div>
        <div class="sc-pill bad"><i class="fas fa-times" style="font-size:.6rem"></i> Ditolak</div>
    </div>
</div>

{{-- ── QUICK ACTIONS ── --}}
<div class="quick-actions">
    <a href="{{ route('dokumen.create') }}" class="qa-btn">
        <div class="qa-icon-wrap"><i class="fas fa-upload"></i></div>
        <div class="qa-lbl">Ajukan Dokumen Baru</div>
        <div class="qa-sub">Upload dokumen ke Admin</div>
    </a>
    <a href="{{ route('dokumen.saya') }}" class="qa-btn">
        <div class="qa-icon-wrap"><i class="fas fa-folder-open"></i></div>
        <div class="qa-lbl">Lihat Dokumen Saya</div>
        <div class="qa-sub">Kelola dokumen disetujui</div>
    </a>
</div>

{{-- ── MAIN COLUMNS ── --}}
<div class="dash-cols">

    {{-- Kiri: Riwayat Pengajuan --}}
    <div>
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-title">
                    <div class="panel-head-icon"><i class="fas fa-history"></i></div>
                    Riwayat Pengajuan Terbaru
                </div>
                <a href="{{ route('pengajuan.status') }}" class="btn-see-all">
                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="filter-tabs">
                <button class="f-tab active" onclick="filterTab(this,'semua')">Semua</button>
                <button class="f-tab" onclick="filterTab(this,'menunggu')">Menunggu</button>
                <button class="f-tab" onclick="filterTab(this,'disetujui')">Disetujui</button>
                <button class="f-tab" onclick="filterTab(this,'ditolak')">Ditolak</button>
            </div>

            <div class="panel-body">
                @forelse($recent_pengajuan as $pengajuan)
                <a href="{{ route('pengajuan.status.show', $pengajuan->id) }}"
                   class="pj-item" data-status="{{ $pengajuan->status }}">
                    <div class="pj-icon {{ $pengajuan->jenis }}">
                        <i class="fas fa-{{ $pengajuan->jenis == 'upload' ? 'upload' : 'trash' }}"></i>
                    </div>
                    <div class="flex-grow-1" style="min-width:0">
                        <div class="pj-title">{{ Str::limit($pengajuan->judul, 48) }}</div>
                        <div class="pj-sub">
                            <i class="fas fa-folder me-1"></i>{{ $pengajuan->folder->nama ?? '-' }}
                            &nbsp;·&nbsp;
                            <i class="fas fa-clock me-1"></i>{{ $pengajuan->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="pj-tags">
                        <span class="badge-pill {{ $pengajuan->jenis }}">{{ ucfirst($pengajuan->jenis) }}</span>
                        <span class="badge-pill {{ $pengajuan->status }}">
                            <i class="fas fa-{{ $pengajuan->status == 'menunggu' ? 'clock' : ($pengajuan->status == 'disetujui' ? 'check' : 'times') }} me-1"></i>
                            {{ ucfirst($pengajuan->status) }}
                        </span>
                    </div>
                    <div class="pj-btn"><i class="fas fa-eye"></i></div>
                </a>
                @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada riwayat pengajuan.</p>
                    <a href="{{ route('dokumen.create') }}" class="btn-primary-sm">
                        <i class="fas fa-upload"></i> Ajukan Sekarang
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Kanan: Ringkasan + Profil + Panduan --}}
    <div>

        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-title">
                    <div class="panel-head-icon"><i class="fas fa-chart-bar"></i></div>
                    Ringkasan Saya
                </div>
            </div>
            <div class="panel-body">
                <div class="mini-stats">
                    <div class="ms green-bg">
                        <div class="ms-val count-up" data-target="{{ $total_dokumen }}">0</div>
                        <div class="ms-lbl">Total Dokumen</div>
                    </div>
                    <div class="ms amber-bg">
                        <div class="ms-val count-up" data-target="{{ $pending_pengajuan }}">0</div>
                        <div class="ms-lbl">Pending</div>
                    </div>
                    <div class="ms blue-bg">
                        <div class="ms-val count-up" data-target="{{ $disetujui_count }}">0</div>
                        <div class="ms-lbl">Disetujui</div>
                    </div>
                    <div class="ms red-bg">
                        <div class="ms-val count-up" data-target="{{ $ditolak_count }}">0</div>
                        <div class="ms-lbl">Ditolak</div>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($pegawai))
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-title">
                    <div class="panel-head-icon"><i class="fas fa-id-badge"></i></div>
                    Profil Pegawai
                </div>
            </div>
            <div class="panel-body">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">
                    <div style="width:44px;height:44px;border-radius:50%;background:var(--g50);color:var(--g600);display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:800;font-size:.88rem;flex-shrink:0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(strstr(auth()->user()->name.' ', ' '), 1, 1)) }}
                    </div>
                    <div>
                        <div style="font-size:.875rem;font-weight:700;color:var(--text)">{{ auth()->user()->name }}</div>
                        <div style="font-size:.72rem;color:var(--text3)">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <div style="border-top:1px solid #f1f5f9;padding-top:12px">
                    <table style="width:100%;font-size:.78rem">
                        <tr>
                            <td style="color:var(--text3);padding:4px 0">NIP</td>
                            <td style="text-align:right;padding:4px 0;font-weight:600;color:var(--text)">{{ $pegawai->nip ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="color:var(--text3);padding:4px 0">Unit Kerja</td>
                            <td style="text-align:right;padding:4px 0;font-weight:600;color:var(--text)">{{ $pegawai->unitKerja->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="color:var(--text3);padding:4px 0">Golongan</td>
                            <td style="text-align:right;padding:4px 0;font-weight:600;color:var(--text)">{{ $pegawai->golongan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-title">
                    <div class="panel-head-icon"><i class="fas fa-lightbulb"></i></div>
                    Panduan Cepat
                </div>
            </div>
            <div class="panel-body">
                <div style="display:flex;flex-direction:column;gap:10px">
                    <div style="display:flex;gap:10px;align-items:flex-start">
                        <div style="width:24px;height:24px;border-radius:6px;background:var(--b50);color:var(--b600);display:flex;align-items:center;justify-content:center;font-size:.7rem;flex-shrink:0"><i class="fas fa-upload"></i></div>
                        <div style="font-size:.78rem;color:var(--text2);line-height:1.5">Klik <strong>Ajukan Dokumen Baru</strong> untuk upload dokumen ke sistem.</div>
                    </div>
                    <div style="display:flex;gap:10px;align-items:flex-start">
                        <div style="width:24px;height:24px;border-radius:6px;background:var(--a50);color:var(--a600);display:flex;align-items:center;justify-content:center;font-size:.7rem;flex-shrink:0"><i class="fas fa-clock"></i></div>
                        <div style="font-size:.78rem;color:var(--text2);line-height:1.5">Pengajuan akan direview oleh Admin dalam 1×24 jam kerja.</div>
                    </div>
                    <div style="display:flex;gap:10px;align-items:flex-start">
                        <div style="width:24px;height:24px;border-radius:6px;background:var(--g50);color:var(--g600);display:flex;align-items:center;justify-content:center;font-size:.7rem;flex-shrink:0"><i class="fas fa-check"></i></div>
                        <div style="font-size:.78rem;color:var(--text2);line-height:1.5">Dokumen yang disetujui otomatis masuk ke folder arsip Anda.</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.count-up').forEach(el => {
        const target = parseInt(el.dataset.target) || 0;
        let c = 0, step = target / 55;
        const tm = setInterval(() => {
            c += step;
            if (c >= target) { c = target; clearInterval(tm); }
            el.textContent = Math.round(c).toLocaleString('id-ID');
        }, 16);
    });
});

function filterTab(el, filter) {
    document.querySelectorAll('.f-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('.pj-item').forEach(item => {
        item.style.display = (filter === 'semua' || item.dataset.status === filter) ? 'flex' : 'none';
    });
}
</script>
@endsection