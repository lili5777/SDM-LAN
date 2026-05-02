<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\DokumenPegawaiController;
use App\Http\Controllers\StatusPengajuanController;
use App\Http\Controllers\DokumenAdminController;
use Illuminate\Support\Facades\Route;

// ========== ROUTE AUTHENTICATION ==========
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'proses_login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ========== ROUTE PROTECTED AREA ==========
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    });
    
    // Notifikasi
    Route::prefix('notifikasi')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::get('/unread-count', [NotifikasiController::class, 'unreadCount'])->name('notifikasi.unread-count');
        Route::put('/{id}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
        Route::put('/read-all', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.read-all');
    });
    
    // ========== ROUTES MANAJEMEN USER & ROLE (YANG SUDAH ADA) ==========
    Route::middleware('permission:role.create')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::delete('/roles/{id}', [RoleController::class, 'delete'])->name('roles.delete');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    });
    
    Route::middleware('permission:user.create')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'delete'])->name('users.delete');
    });
    
    // ========== ROUTES UNTUK ADMIN ==========
    Route::middleware('permission:admin.access')->group(function () {
        
        // Unit Kerja
        Route::prefix('unit-kerja')->group(function () {
            Route::get('/', [UnitKerjaController::class, 'index'])->name('unit-kerja.index');
            Route::get('/create', [UnitKerjaController::class, 'create'])->name('unit-kerja.create');
            Route::post('/', [UnitKerjaController::class, 'store'])->name('unit-kerja.store');
            Route::get('/{id}/edit', [UnitKerjaController::class, 'edit'])->name('unit-kerja.edit');
            Route::put('/{id}', [UnitKerjaController::class, 'update'])->name('unit-kerja.update');
            Route::delete('/{id}', [UnitKerjaController::class, 'destroy'])->name('unit-kerja.destroy');
        });
        
        // Pegawai
        Route::prefix('pegawai')->group(function () {
            Route::get('/', [PegawaiController::class, 'index'])->name('pegawai.index');
            Route::get('/create', [PegawaiController::class, 'create'])->name('pegawai.create');
            Route::post('/', [PegawaiController::class, 'store'])->name('pegawai.store');
            Route::get('/{id}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
            Route::put('/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
            Route::delete('/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
            Route::get('/{id}/create-user', [PegawaiController::class, 'createUser'])->name('pegawai.create-user');
            Route::post('/{id}/create-user', [PegawaiController::class, 'storeUser'])->name('pegawai.store-user');
        });
        
        // Kelola Folder
        Route::prefix('folder')->group(function () {
            // Kategori
            Route::get('/kategori', [FolderController::class, 'indexKategori'])->name('folder.kategori.index');
            Route::get('/kategori/create', [FolderController::class, 'createKategori'])->name('folder.kategori.create');
            Route::post('/kategori', [FolderController::class, 'storeKategori'])->name('folder.kategori.store');
            Route::get('/kategori/{id}/edit', [FolderController::class, 'editKategori'])->name('folder.kategori.edit');
            Route::put('/kategori/{id}', [FolderController::class, 'updateKategori'])->name('folder.kategori.update');
            Route::delete('/kategori/{id}', [FolderController::class, 'destroyKategori'])->name('folder.kategori.destroy');
            
            // Folder
            Route::get('/', [FolderController::class, 'indexFolder'])->name('folder.index');
            Route::get('/create', [FolderController::class, 'createFolder'])->name('folder.create');
            Route::post('/', [FolderController::class, 'storeFolder'])->name('folder.store');
            Route::get('/{id}/edit', [FolderController::class, 'editFolder'])->name('folder.edit');
            Route::put('/{id}', [FolderController::class, 'updateFolder'])->name('folder.update');
            Route::delete('/{id}', [FolderController::class, 'destroyFolder'])->name('folder.destroy');
            Route::patch('/{id}/toggle', [FolderController::class, 'toggleFolder'])->name('folder.toggle');
        });
        
        // Pengajuan (Admin Review)
        Route::prefix('pengajuan')->group(function () {
            Route::get('/pending', [PengajuanController::class, 'pending'])->name('pengajuan.pending');
            Route::get('/history', [PengajuanController::class, 'history'])->name('pengajuan.history');
            Route::get('/{id}', [PengajuanController::class, 'show'])->name('pengajuan.show');
            Route::post('/{id}/approve', [PengajuanController::class, 'approve'])->name('pengajuan.approve');
            Route::post('/{id}/reject', [PengajuanController::class, 'reject'])->name('pengajuan.reject');
        });
        
        // Semua Dokumen (Admin View)
        Route::prefix('admin-dokumen')->group(function () {
 
            // ── Statis (harus di atas route dinamis /{id}) ──────────────────────
            Route::get('/',        [DokumenAdminController::class, 'index'])   ->name('admin.dokumen.index');
            Route::get('/create',  [DokumenAdminController::class, 'create'])  ->name('admin.dokumen.create');
            Route::post('/',       [DokumenAdminController::class, 'store'])   ->name('admin.dokumen.store');
        
            // ── Dua route baru: layar 2 (grid folder) & layar 3 (list file) ─────
            Route::get('/pegawai/{pegawai}',
                [DokumenAdminController::class, 'pegawaiView'])
                ->name('admin.dokumen.pegawai');
        
            Route::get('/pegawai/{pegawai}/folder/{folder}',
                [DokumenAdminController::class, 'folderView'])
                ->name('admin.dokumen.folder');
        
            // ── Dinamis /{id} — tetap di bawah ──────────────────────────────────
            Route::get('/{id}',          [DokumenAdminController::class, 'show'])    ->name('admin.dokumen.show');
            Route::get('/{id}/download', [DokumenAdminController::class, 'download'])->name('admin.dokumen.download');
            Route::get('/{id}/preview',  [DokumenAdminController::class, 'preview']) ->name('admin.dokumen.preview');
        });

    });
    
    // ========== ROUTES UNTUK PEGAWAI ==========
    Route::middleware('permission:pegawai.access')->group(function () {
        
        // Dokumen Saya
        Route::prefix('dokumen-saya')->group(function () {
            Route::get('/', [DokumenPegawaiController::class, 'index'])->name('dokumen.saya');
            Route::get('/create', [DokumenPegawaiController::class, 'create'])->name('dokumen.create');
            Route::post('/', [DokumenPegawaiController::class, 'store'])->name('dokumen.store');
            Route::post('/{id}/ajukan-hapus', [DokumenPegawaiController::class, 'ajukanHapus'])->name('dokumen.ajukan-hapus');
        });
        
        // Status Pengajuan
        Route::prefix('pengajuan-saya')->group(function () {
            Route::get('/', [StatusPengajuanController::class, 'index'])->name('pengajuan.status');
            Route::get('/{id}', [StatusPengajuanController::class, 'show'])->name('pengajuan.status.show');
            Route::delete('/{id}/batalkan', [StatusPengajuanController::class, 'batalkan'])->name('pengajuan.status.batalkan');
        });
    });
});