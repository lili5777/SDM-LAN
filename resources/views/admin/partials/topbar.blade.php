<style>
    /* ===== TOPBAR BARU — JABLAYMEN ===== */
    .topbar {
        position: fixed;
        top: 0;
        left: var(--sidebar-width);
        right: 0;
        height: var(--topbar-height);
        background: #ffffff;
        border-bottom: 1.5px solid #dcfce8;
        display: flex;
        align-items: center;
        padding: 0 1.75rem;
        gap: 1rem;
        z-index: 999;
        transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar.collapsed ~ .topbar {
        left: var(--sidebar-collapsed-width);
    }

    /* Toggle */
    .menu-toggle {
        width: 40px;
        height: 40px;
        border: 1.5px solid #dcfce8;
        background: #f0fdf6;
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s;
        color: #166534;
        flex-shrink: 0;
    }
    .menu-toggle:hover {
        background: #166534;
        border-color: #166534;
        color: white;
        transform: scale(1.05);
    }

    /* Breadcrumb */
    .breadcrumb-wrapper {
        flex: 1;
        min-width: 0;
    }
    .page-title {
        font-family: 'DM Serif Display', serif;
        font-size: 1.2rem;
        font-weight: 400;
        color: #0f172a;
        margin: 0;
        line-height: 1.2;
    }
    .breadcrumb {
        margin: 0;
        padding: 0;
        background: none;
        font-size: 0.78rem;
    }
    .breadcrumb-item a {
        color: #15803d;
        text-decoration: none;
        font-weight: 500;
    }
    .breadcrumb-item.active { color: #94a3b8; }
    .breadcrumb-item + .breadcrumb-item::before { color: #94a3b8; }

    /* ===== JAM — kartu pill kecil ===== */
    .topbar-clock {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        background: #f0fdf6;
        border: 1.5px solid #bbf7d2;
        border-radius: 12px;
        padding: 0.35rem 0.85rem;
        line-height: 1.3;
        flex-shrink: 0;
    }
    .topbar-clock .clock-time {
        font-family: 'DM Serif Display', serif;
        font-size: 1rem;
        color: #166534;
        font-weight: 400;
        letter-spacing: 0.5px;
    }
    .topbar-clock .clock-date {
        font-size: 0.7rem;
        color: #64748b;
        font-weight: 500;
        white-space: nowrap;
    }
    @media (max-width: 768px) {
        .topbar-clock { display: none; }
    }

    /* ===== DIVIDER ===== */
    .topbar-divider {
        width: 1px;
        height: 28px;
        background: #dcfce8;
        flex-shrink: 0;
    }

    /* ===== USER CHIP ===== */
    .user-chip {
        display: flex;
        align-items: center;
        gap: 0;
        background: #f0fdf6;
        border: 1.5px solid #bbf7d2;
        border-radius: 999px;
        padding: 4px 14px 4px 4px;
        cursor: pointer;
        transition: all 0.25s;
        text-decoration: none;
        flex-shrink: 0;
    }
    .user-chip:hover {
        border-color: #34d474;
        background: #dcfce8;
        box-shadow: 0 4px 12px rgba(22,163,74,0.12);
    }
    .user-chip-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #34d474, #166534);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: white;
        flex-shrink: 0;
        margin-right: 8px;
    }
    .user-chip-info {
        display: flex;
        flex-direction: column;
        line-height: 1.25;
    }
    .user-chip-name {
        font-size: 0.82rem;
        font-weight: 600;
        color: #0f172a;
        white-space: nowrap;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .user-chip-role {
        font-size: 0.68rem;
        color: #15803d;
        font-weight: 500;
    }
    .user-chip-caret {
        margin-left: 6px;
        font-size: 0.65rem;
        color: #94a3b8;
        transition: transform 0.2s;
    }
    .user-chip.show .user-chip-caret,
    .dropdown.show .user-chip-caret {
        transform: rotate(180deg);
    }
    @media (max-width: 576px) {
        .user-chip-info { display: none; }
        .user-chip { padding: 4px; }
        .user-chip-avatar { margin-right: 0; }
        .user-chip-caret { display: none; }
    }

    /* ===== DROPDOWN USER ===== */
    .user-dropdown-menu {
        min-width: 260px;
        border: 1.5px solid #dcfce8 !important;
        border-radius: 16px !important;
        box-shadow: 0 12px 32px rgba(22,101,52,0.12) !important;
        overflow: hidden;
        margin-top: 8px !important;
        padding: 0 !important;
    }
    .user-dropdown-header {
        background: linear-gradient(135deg, #166534, #15803d);
        padding: 1.25rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .user-dropdown-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        font-weight: 700;
        color: white;
        flex-shrink: 0;
    }
    .user-dropdown-info .name {
        font-size: 0.9rem;
        font-weight: 600;
        color: white;
        display: block;
    }
    .user-dropdown-info .email {
        font-size: 0.75rem;
        color: rgba(255,255,255,0.7);
        display: block;
        margin-top: 1px;
    }
    .user-dropdown-body {
        padding: 0.5rem;
    }
    .user-dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.65rem 0.85rem;
        border-radius: 10px;
        font-size: 0.85rem;
        color: #334155;
        text-decoration: none;
        transition: all 0.2s;
        font-weight: 500;
    }
    .user-dropdown-item:hover {
        background: #f0fdf6;
        color: #166534;
    }
    .user-dropdown-item i {
        width: 16px;
        text-align: center;
        color: #15803d;
        font-size: 0.9rem;
    }
    .user-dropdown-item.danger { color: #ef4444; }
    .user-dropdown-item.danger i { color: #ef4444; }
    .user-dropdown-item.danger:hover { background: #fef2f2; color: #dc2626; }
    .user-dropdown-divider {
        height: 1px;
        background: #f0fdf6;
        margin: 0.25rem 0.5rem;
    }
</style>

<header class="topbar">

    <!-- Toggle -->
    <button class="menu-toggle" id="menuToggle" aria-label="Toggle Sidebar">
        <i class="fas fa-bars" style="font-size: 1.1rem;"></i>
    </button>

    <!-- Breadcrumb -->
    <div class="breadcrumb-wrapper">
        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">@yield('page-title', 'Dashboard')</li>
            </ol>
        </nav>
    </div>

    <!-- Jam -->
    <div class="topbar-clock">
        <span class="clock-time" id="topbarTime">00:00:00</span>
        <span class="clock-date" id="topbarDate">—</span>
    </div>

    <div class="topbar-divider"></div>

    <!-- User Chip Dropdown -->
    <div class="dropdown">
        <a class="user-chip" href="#" id="userChipDropdown"
            data-bs-toggle="dropdown" aria-expanded="false">
            <div class="user-chip-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="user-chip-info">
                <span class="user-chip-name">{{ auth()->user()->name }}</span>
                <span class="user-chip-role">{{ auth()->user()->email }}</span>
            </div>
            <i class="fas fa-chevron-down user-chip-caret"></i>
        </a>

        <ul class="dropdown-menu user-dropdown-menu dropdown-menu-end">
            <!-- Header -->
            <div class="user-dropdown-header">
                <div class="user-dropdown-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="user-dropdown-info">
                    <span class="name">{{ auth()->user()->name }}</span>
                    <span class="email">{{ auth()->user()->email }}</span>
                </div>
            </div>

            <!-- Body -->
            <div class="user-dropdown-body">
                <a href="#" class="user-dropdown-item">
                    <i class="fas fa-user-cog"></i> Profil Saya
                </a>
                <a href="#" class="user-dropdown-item">
                    <i class="fas fa-question-circle"></i> Bantuan
                </a>
                <div class="user-dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="user-dropdown-item danger">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </div>
        </ul>
    </div>

</header>

<script>
    (function () {
        function updateTopbarClock() {
            const now = new Date();
            const timeEl = document.getElementById('topbarTime');
            const dateEl = document.getElementById('topbarDate');
            if (timeEl) {
                timeEl.textContent = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
                });
            }
            if (dateEl) {
                dateEl.textContent = now.toLocaleDateString('id-ID', {
                    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
                });
            }
        }
        updateTopbarClock();
        setInterval(updateTopbarClock, 1000);
    })();
</script>