<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="{{ asset('gambar/lanri.png') }}" alt="Logo LAN RI">
        </div>
        <div class="sidebar-title">
            <h5>JABLAYMEN</h5>
            <small>Layanan Dokumen SDM Online</small>
        </div>
    </div>

    <nav class="sidebar-menu">

        <!-- Dashboard -->
        <div class="menu-item">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="fas fa-home menu-icon"></i>
                <span class="menu-text">Dashboard</span>
            </a>
        </div>

        @if(auth()->user()->role->name === 'admin')

        {{-- ===== MENU ADMIN ===== --}}

        <!-- Pengajuan Masuk -->
        <div class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-inbox menu-icon"></i>
                <span class="menu-text">Pengajuan Masuk</span>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>
            <div class="submenu">
                <a href="#" class="submenu-link">
                    <i class="fas fa-clock me-2"></i> Menunggu Persetujuan
                    {{-- Badge jumlah pending --}}
                    @if($pendingCount ?? 0 > 0)
                        <span class="badge bg-danger ms-auto">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="#" class="submenu-link">
                    <i class="fas fa-history me-2"></i> Riwayat Pengajuan
                </a>
            </div>
        </div>

        <!-- Kelola Folder -->
        <div class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-folder-open menu-icon"></i>
                <span class="menu-text">Kelola Folder</span>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>
            <div class="submenu">
                <a href="#" class="submenu-link">
                    <i class="fas fa-layer-group me-2"></i> Kategori Folder
                </a>
                <a href="#" class="submenu-link">
                    <i class="fas fa-folder me-2"></i> Daftar Folder
                </a>
            </div>
        </div>

        <!-- Semua Dokumen -->
        <div class="menu-item">
            <a href="#" class="menu-link">
                <i class="fas fa-file-alt menu-icon"></i>
                <span class="menu-text">Semua Dokumen</span>
            </a>
        </div>

        <!-- Data SDM -->
        <div class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-users menu-icon"></i>
                <span class="menu-text">Data SDM</span>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>
            <div class="submenu">
                <a href="#" class="submenu-link">
                    <i class="fas fa-user-tie me-2"></i> Data Pegawai
                </a>
                <a href="#" class="submenu-link">
                    <i class="fas fa-sitemap me-2"></i> Unit Kerja
                </a>
            </div>
        </div>

        <!-- Pengaturan Sistem -->
        <div class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-cog menu-icon"></i>
                <span class="menu-text">Pengaturan Sistem</span>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('users.index') }}" class="submenu-link">
                    <i class="fas fa-users-cog me-2"></i> Manajemen User
                </a>
                <a href="{{ route('roles.index') }}" class="submenu-link">
                    <i class="fas fa-user-shield me-2"></i> Peran & Hak Akses
                </a>
            </div>
        </div>

        @elseif(auth()->user()->role->name === 'pegawai')

        {{-- ===== MENU PEGAWAI ===== --}}

        <!-- Ajukan Dokumen -->
        <div class="menu-item">
            <a href="#" class="menu-link">
                <i class="fas fa-upload menu-icon"></i>
                <span class="menu-text">Ajukan Dokumen</span>
            </a>
        </div>

        <!-- Status Pengajuan -->
        <div class="menu-item">
            <a href="#" class="menu-link">
                <i class="fas fa-clipboard-list menu-icon"></i>
                <span class="menu-text">Status Pengajuan</span>
                {{-- Badge jika ada pengajuan pending milik pegawai --}}
                @if($myPendingCount ?? 0 > 0)
                    <span class="badge bg-warning ms-auto">{{ $myPendingCount }}</span>
                @endif
            </a>
        </div>

        <!-- Dokumen Saya -->
        <div class="menu-item">
            <a href="#" class="menu-link">
                <i class="fas fa-folder-open menu-icon"></i>
                <span class="menu-text">Dokumen Saya</span>
            </a>
        </div>

        @endif

        {{-- ===== MENU BERSAMA ===== --}}

        <!-- Notifikasi -->
        <div class="menu-item">
            <a href="#" class="menu-link">
                <i class="fas fa-bell menu-icon"></i>
                <span class="menu-text">Notifikasi</span>
                @if($unreadNotif ?? 0 > 0)
                    <span class="badge bg-danger ms-auto">{{ $unreadNotif }}</span>
                @endif
            </a>
        </div>

        <!-- Logout -->
        <div class="menu-item">
            <a href="{{ route('logout') }}" class="menu-link">
                <i class="fas fa-sign-out-alt menu-icon"></i>
                <span class="menu-text">Keluar</span>
            </a>
        </div>

    </nav>
</aside>