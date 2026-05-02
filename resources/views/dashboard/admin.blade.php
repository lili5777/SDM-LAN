@extends('admin.partials.layout')

@section('title', 'Dashboard Admin - JABLAYMEN')
@section('page-title', 'Dashboard')

@section('styles')
<style>
/* ── Reset & base ─────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; }

/* ── Welcome hero ─────────────────────────────────────── */
.hero {
    background: #166534;
    border-radius: 20px;
    padding: 1.75rem 2rem;
    color: #fff;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute;
    right: -40px; top: -40px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
}
.hero::after {
    content: '';
    position: absolute;
    right: 60px; bottom: -60px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
}
.hero-title   { font-size: 1.45rem; font-weight: 700; margin: 0 0 4px; }
.hero-sub     { font-size: 0.82rem; opacity: .65; margin: 0; }
.hero-date    { font-size: 0.75rem; opacity: .5; margin-top: 6px; }
.hero-badge   {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 30px;
    padding: 4px 12px;
    font-size: 0.72rem;
    margin-top: 10px;
}
.hero-icon-bg {
    position: absolute; right: 2rem; top: 50%;
    transform: translateY(-50%);
    font-size: 5rem;
    opacity: .07;
}

/* ── Stat cards ───────────────────────────────────────── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 1.5rem;
}
.stat-card {
    background: #fff;
    border: 1px solid #e8f0ea;
    border-radius: 16px;
    padding: 1.1rem 1.25rem;
    position: relative;
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(22,101,52,.1);
}
.stat-card-accent {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 16px 16px 0 0;
}
.stat-card-accent.green  { background: #166534; }
.stat-card-accent.amber  { background: #d97706; }
.stat-card-accent.blue   { background: #2563eb; }
.stat-card-accent.red    { background: #dc2626; }
.stat-icon-wrap {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; margin-bottom: 12px; flex-shrink: 0;
}
.stat-icon-wrap.green { background: #d1fae5; color: #166534; }
.stat-icon-wrap.amber { background: #fef3c7; color: #d97706; }
.stat-icon-wrap.blue  { background: #dbeafe; color: #2563eb; }
.stat-icon-wrap.red   { background: #fee2e2; color: #dc2626; }
.stat-val  { font-size: 1.9rem; font-weight: 800; color: #0f172a; line-height: 1; }
.stat-lbl  { font-size: 0.75rem; color: #64748b; margin-top: 4px; }
.stat-trend {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: 0.68rem; font-weight: 600;
    padding: 2px 7px; border-radius: 20px;
    margin-top: 8px;
}
.stat-trend.up   { background: #d1fae5; color: #166534; }
.stat-trend.warn { background: #fef3c7; color: #92400e; }
.stat-trend.neu  { background: #f1f5f9; color: #64748b; }

/* Mini sparkline canvas */
.sparkline { width: 100%; height: 36px; display: block; margin-top: 10px; }

/* ── Two-column layout ────────────────────────────────── */
.dash-cols {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 16px;
    align-items: start;
}
@media (max-width: 900px) {
    .dash-cols { grid-template-columns: 1fr; }
}

/* ── Panel cards ──────────────────────────────────────── */
.panel {
    background: #fff;
    border: 1px solid #e8f0ea;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 16px;
}
.panel-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: .875rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
}
.panel-head-title {
    font-size: .88rem; font-weight: 700; color: #0f172a;
    display: flex; align-items: center; gap: 8px;
}
.panel-head-icon {
    width: 28px; height: 28px; border-radius: 8px;
    background: #d1fae5; color: #166534;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem;
}
.panel-body { padding: 1rem 1.25rem; }

/* ── Pengajuan list ───────────────────────────────────── */
.pj-item {
    display: flex; align-items: center; gap: 12px;
    padding: .75rem .875rem;
    border-radius: 12px;
    border: 1px solid #f1f5f9;
    margin-bottom: 8px;
    transition: border-color .15s, background .15s;
    text-decoration: none;
}
.pj-item:hover { border-color: #bbf7d0; background: #f8fffb; }
.pj-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: #d1fae5; color: #166534;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; font-weight: 700; flex-shrink: 0;
}
.pj-judul  { font-size: .82rem; font-weight: 600; color: #0f172a; line-height: 1.3; }
.pj-sub    { font-size: .72rem; color: #64748b; margin-top: 2px; }
.pj-badge  {
    padding: 2px 9px; border-radius: 20px;
    font-size: .67rem; font-weight: 600;
    flex-shrink: 0; white-space: nowrap;
}
.pj-badge.upload   { background: #dbeafe; color: #1d4ed8; }
.pj-badge.hapus    { background: #fee2e2; color: #991b1b; }
.pj-badge.menunggu { background: #fef9c3; color: #854d0e; }

/* ── Activity feed ────────────────────────────────────── */
.activity-item {
    display: flex; gap: 12px;
    padding: .625rem 0;
    border-bottom: 1px solid #f8fafc;
}
.activity-item:last-child { border-bottom: none; }
.act-dot {
    width: 8px; height: 8px; border-radius: 50%;
    margin-top: 5px; flex-shrink: 0;
}
.act-dot.green { background: #22c55e; }
.act-dot.amber { background: #f59e0b; }
.act-dot.blue  { background: #3b82f6; }
.act-text { font-size: .78rem; color: #475569; line-height: 1.5; }
.act-time { font-size: .68rem; color: #94a3b8; margin-top: 1px; }

/* ── Donut chart area ─────────────────────────────────── */
.donut-wrap {
    display: flex; align-items: center; gap: 1rem;
    padding: .75rem 0;
}
.donut-legend { flex: 1; }
.legend-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 5px 0;
    font-size: .78rem;
}
.legend-dot {
    width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
}

/* ── Quick action buttons ─────────────────────────────── */
.quick-actions {
    display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
    margin-bottom: 16px;
}
.qa-btn {
    background: #fff;
    border: 1px solid #e8f0ea;
    border-radius: 14px;
    padding: .875rem 1rem;
    text-align: center;
    text-decoration: none;
    transition: all .18s;
    cursor: pointer;
}
.qa-btn:hover { border-color: #166534; background: #f0fdf4; }
.qa-btn i { font-size: 1.3rem; color: #166534; display: block; margin-bottom: 6px; }
.qa-btn span { font-size: .75rem; font-weight: 600; color: #0f172a; display: block; }
.qa-btn small { font-size: .67rem; color: #94a3b8; }

/* ── Responsive ───────────────────────────────────────── */
@media (max-width: 576px) {
    .stats-grid { grid-template-columns: 1fr 1fr; }
    .hero-title { font-size: 1.15rem; }
    .hero-icon-bg { display: none; }
}

/* ── Counter animation ────────────────────────────────── */
.count-up { transition: opacity .3s; }
</style>
@endsection

@section('content')

{{-- ── HERO ─────────────────────────────────────────────── --}}
<div class="hero">
    <div style="position:relative;z-index:1">
        <p class="hero-sub">Selamat datang kembali</p>
        <h1 class="hero-title">{{ auth()->user()->name }}</h1>
        <p class="hero-date">
            <i class="fas fa-calendar-alt me-1"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </p>
        <div class="hero-badge">
            <i class="fas fa-shield-alt" style="font-size:.7rem"></i>
            Administrator · JABLAYMEN
        </div>
    </div>
    <i class="fas fa-file-alt hero-icon-bg"></i>
</div>

{{-- ── STAT CARDS ───────────────────────────────────────── --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-accent green"></div>
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-icon-wrap green"><i class="fas fa-file-alt"></i></div>
                <div class="stat-val count-up" data-target="{{ $total_dokumen }}">0</div>
                <div class="stat-lbl">Total Dokumen</div>
                <div class="stat-trend up"><i class="fas fa-arrow-up" style="font-size:.6rem"></i> Aktif</div>
            </div>
        </div>
        <canvas class="sparkline" id="spark1"></canvas>
    </div>
    <div class="stat-card">
        <div class="stat-card-accent blue"></div>
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-icon-wrap blue"><i class="fas fa-users"></i></div>
                <div class="stat-val count-up" data-target="{{ $total_pegawai }}">0</div>
                <div class="stat-lbl">Total Pegawai</div>
                <div class="stat-trend neu"><i class="fas fa-minus" style="font-size:.6rem"></i> Terdaftar</div>
            </div>
        </div>
        <canvas class="sparkline" id="spark2"></canvas>
    </div>
    <div class="stat-card">
        <div class="stat-card-accent amber"></div>
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-icon-wrap amber"><i class="fas fa-clock"></i></div>
                <div class="stat-val count-up" data-target="{{ $pending_pengajuan }}">0</div>
                <div class="stat-lbl">Pengajuan Pending</div>
                @if($pending_pengajuan > 0)
                <div class="stat-trend warn"><i class="fas fa-exclamation" style="font-size:.6rem"></i> Perlu tindakan</div>
                @else
                <div class="stat-trend up"><i class="fas fa-check" style="font-size:.6rem"></i> Bersih</div>
                @endif
            </div>
        </div>
        <canvas class="sparkline" id="spark3"></canvas>
    </div>
    <div class="stat-card">
        <div class="stat-card-accent green"></div>
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-icon-wrap green"><i class="fas fa-upload"></i></div>
                <div class="stat-val count-up" data-target="{{ $pending_upload }}">0</div>
                <div class="stat-lbl">Upload Pending</div>
                <div class="stat-trend neu"><i class="fas fa-inbox" style="font-size:.6rem"></i> Antrian</div>
            </div>
        </div>
        <canvas class="sparkline" id="spark4"></canvas>
    </div>
</div>

{{-- ── MAIN CONTENT ─────────────────────────────────────── --}}
<div class="dash-cols">

    {{-- Kiri: pengajuan terbaru --}}
    <div>
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-title">
                    <div class="panel-head-icon"><i class="fas fa-inbox"></i></div>
                    Pengajuan Terbaru
                </div>
                <a href="{{ route('pengajuan.pending') }}"
                    class="btn btn-sm btn-outline-success rounded-pill px-3"
                    style="font-size:.75rem">
                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="panel-body" style="padding-top:.75rem">
                @forelse($pengajuan_terbaru as $p)
                @php
                    $inisial = collect(explode(' ', $p->pegawai->nama ?? 'X'))
                        ->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->implode('');
                @endphp
                <a href="{{ route('pengajuan.show', $p->id) }}" class="pj-item">
                    <div class="pj-avatar">{{ $inisial }}</div>
                    <div class="flex-grow-1" style="min-width:0">
                        <div class="pj-judul">{{ Str::limit($p->judul, 45) }}</div>
                        <div class="pj-sub">
                            <i class="fas fa-user me-1"></i>{{ $p->pegawai->nama ?? '-' }}
                            &nbsp;·&nbsp;
                            <i class="fas fa-folder me-1"></i>{{ $p->folder->nama ?? '-' }}
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px">
                        <span class="pj-badge {{ $p->jenis }}">
                            <i class="fas fa-{{ $p->jenis == 'upload' ? 'upload' : 'trash' }} me-1"></i>
                            {{ ucfirst($p->jenis) }}
                        </span>
                        <span class="pj-badge menunggu">
                            <i class="fas fa-clock me-1"></i>Menunggu
                        </span>
                    </div>
                </a>
                @empty
                <div class="text-center py-4" style="color:#94a3b8">
                    <i class="fas fa-inbox fa-3x mb-2 d-block opacity-25"></i>
                    <div style="font-size:.82rem">Tidak ada pengajuan pending</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Kanan: ringkasan + quick action --}}
    <div>
        {{-- Quick actions --}}
        <div class="quick-actions">
            <a href="{{ route('admin.dokumen.create') }}" class="qa-btn">
                <i class="fas fa-cloud-upload-alt"></i>
                <span>Upload Dokumen</span>
                <small>Langsung aktif</small>
            </a>
            <a href="{{ route('admin.dokumen.index') }}" class="qa-btn">
                <i class="fas fa-folder-open"></i>
                <span>Semua Dokumen</span>
                <small>Kelola arsip</small>
            </a>
            <a href="{{ route('pengajuan.pending') }}" class="qa-btn">
                <i class="fas fa-tasks"></i>
                <span>Review Pengajuan</span>
                <small>{{ $pending_pengajuan }} menunggu</small>
            </a>
            <a href="{{ route('pegawai.index') }}" class="qa-btn">
                <i class="fas fa-users"></i>
                <span>Data Pegawai</span>
                <small>{{ $total_pegawai }} terdaftar</small>
            </a>
        </div>

        {{-- Ringkasan pengajuan --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-title">
                    <div class="panel-head-icon"><i class="fas fa-chart-pie"></i></div>
                    Ringkasan Pengajuan
                </div>
            </div>
            <div class="panel-body">
                <div class="donut-wrap">
                    <canvas id="donutChart" width="100" height="100" style="flex-shrink:0"></canvas>
                    <div class="donut-legend">
                        <div class="legend-row">
                            <div style="display:flex;align-items:center;gap:6px">
                                <div class="legend-dot" style="background:#3b82f6"></div>
                                <span style="color:#475569">Upload</span>
                            </div>
                            <strong style="color:#0f172a;font-size:.82rem">{{ $pending_upload }}</strong>
                        </div>
                        <div class="legend-row">
                            <div style="display:flex;align-items:center;gap:6px">
                                <div class="legend-dot" style="background:#ef4444"></div>
                                <span style="color:#475569">Hapus</span>
                            </div>
                            <strong style="color:#0f172a;font-size:.82rem">{{ $pending_hapus }}</strong>
                        </div>
                        <div class="legend-row" style="border-top:1px solid #f1f5f9;padding-top:8px;margin-top:4px">
                            <div style="display:flex;align-items:center;gap:6px">
                                <div class="legend-dot" style="background:#166534"></div>
                                <span style="color:#475569;font-weight:600">Total</span>
                            </div>
                            <strong style="color:#166534;font-size:.88rem">{{ $pending_pengajuan }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Activity feed --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-title">
                    <div class="panel-head-icon"><i class="fas fa-bolt"></i></div>
                    Aktivitas Sistem
                </div>
            </div>
            <div class="panel-body">
                @foreach($pengajuan_terbaru->take(4) as $p)
                <div class="activity-item">
                    <div class="act-dot {{ $p->jenis == 'upload' ? 'blue' : 'amber' }}"></div>
                    <div>
                        <div class="act-text">
                            <strong>{{ $p->pegawai->nama ?? 'Pegawai' }}</strong>
                            mengajukan {{ $p->jenis }} dokumen
                            <em>"{{ Str::limit($p->judul, 30) }}"</em>
                        </div>
                        <div class="act-time">
                            <i class="fas fa-clock" style="font-size:.6rem"></i>
                            {{ $p->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @endforeach
                @if($pengajuan_terbaru->isEmpty())
                <div style="text-align:center;padding:1rem;color:#94a3b8;font-size:.78rem">
                    Belum ada aktivitas terbaru
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Counter animation ──────────────────────────────── */
    function animateCount(el) {
        const target = parseInt(el.dataset.target) || 0;
        const duration = 900;
        const step = target / (duration / 16);
        let current = 0;
        const timer = setInterval(() => {
            current += step;
            if (current >= target) { current = target; clearInterval(timer); }
            el.textContent = Math.floor(current).toLocaleString('id-ID');
        }, 16);
    }
    document.querySelectorAll('.count-up').forEach(animateCount);

    /* ── Sparklines ─────────────────────────────────────── */
    function drawSparkline(id, color, data) {
        const canvas = document.getElementById(id);
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const W = canvas.offsetWidth || 180;
        const H = 36;
        canvas.width = W;
        canvas.height = H;
        const max = Math.max(...data, 1);
        const step = W / (data.length - 1);

        ctx.beginPath();
        ctx.moveTo(0, H - (data[0] / max) * (H - 6) - 3);
        data.forEach((v, i) => {
            ctx.lineTo(i * step, H - (v / max) * (H - 6) - 3);
        });
        ctx.strokeStyle = color;
        ctx.lineWidth = 2;
        ctx.lineJoin = 'round';
        ctx.stroke();

        /* fill under line */
        ctx.lineTo(W, H);
        ctx.lineTo(0, H);
        ctx.closePath();
        ctx.fillStyle = color + '18';
        ctx.fill();
    }

    const r = () => Math.floor(Math.random() * 8) + 3;
    drawSparkline('spark1', '#166534', [r(),r(),r(),r(),r(),r(),r(),{{ $total_dokumen > 0 ? $total_dokumen % 10 + 5 : 5 }}]);
    drawSparkline('spark2', '#2563eb', [r(),r(),r(),r(),r(),r(),r(),{{ $total_pegawai > 0 ? $total_pegawai % 10 + 3 : 3 }}]);
    drawSparkline('spark3', '#d97706', [r(),r(),r(),r(),r(),r(),r(),{{ $pending_pengajuan + 2 }}]);
    drawSparkline('spark4', '#166534', [r(),r(),r(),r(),r(),r(),r(),{{ $pending_upload + 2 }}]);

    /* ── Donut chart ────────────────────────────────────── */
    const donut = document.getElementById('donutChart');
    if (donut) {
        const ctx = donut.getContext('2d');
        const upload = {{ $pending_upload }};
        const hapus  = {{ $pending_hapus }};
        const total  = upload + hapus || 1;
        const cx = 50, cy = 50, r = 38, innerR = 24;
        const TWO_PI = Math.PI * 2;

        function slice(start, end, color) {
            ctx.beginPath();
            ctx.moveTo(cx, cy);
            ctx.arc(cx, cy, r, start, end);
            ctx.closePath();
            ctx.fillStyle = color;
            ctx.fill();
            ctx.beginPath();
            ctx.moveTo(cx, cy);
            ctx.arc(cx, cy, innerR, start, end);
            ctx.closePath();
            ctx.fillStyle = '#fff';
            ctx.fill();
        }

        const uploadAngle = (upload / total) * TWO_PI;
        slice(-Math.PI / 2, -Math.PI / 2 + uploadAngle, '#3b82f6');
        slice(-Math.PI / 2 + uploadAngle, -Math.PI / 2 + TWO_PI, '#ef4444');

        ctx.fillStyle = '#0f172a';
        ctx.font = 'bold 14px sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(total, cx, cy);
    }

    /* ── Auto refresh 60s ───────────────────────────────── */
    setTimeout(() => location.reload(), 60000);
});
</script>
@endsection