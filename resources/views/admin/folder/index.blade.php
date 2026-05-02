@extends('admin.partials.layout')

@section('title', 'Kelola Folder - JABLAYMEN')
@section('page-title', 'Kelola Folder')

@section('styles')
<style>
    .kategori-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .kategori-header {
        background: linear-gradient(135deg, #166534 0%, #15803d 100%);
        padding: 1rem 1.5rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .kategori-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }
    .kategori-header .badge {
        background: rgba(255,255,255,0.2);
        font-size: 0.75rem;
    }
    .folder-list {
        padding: 1rem;
    }
    .folder-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .folder-item:hover {
        border-color: #bbf7d2;
        box-shadow: 0 2px 8px rgba(22,101,52,0.1);
    }
    .folder-info h6 {
        margin: 0 0 5px 0;
        font-size: 0.95rem;
        font-weight: 600;
    }
    .folder-info p {
        margin: 0;
        font-size: 0.75rem;
        color: #64748b;
    }
    .folder-stats {
        display: flex;
        gap: 1rem;
        margin-top: 5px;
    }
    .folder-stats span {
        font-size: 0.7rem;
        color: #94a3b8;
    }
    .btn-folder-action {
        padding: 0.3rem 0.6rem;
        margin: 0 2px;
        border-radius: 8px;
    }
    .status-badge {
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 500;
    }
    .status-badge.active {
        background: #d1fae5;
        color: #065f46;
    }
    .status-badge.inactive {
        background: #fee2e2;
        color: #dc2626;
    }
    .empty-folder {
        text-align: center;
        padding: 2rem;
        color: #94a3b8;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Tombol Aksi -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <a href="{{ route('folder.kategori.create') }}" class="btn btn-outline-success me-2">
                    <i class="fas fa-layer-group me-1"></i> Tambah Kategori
                </a>
                <a href="{{ route('folder.create') }}" class="btn btn-success">
                    <i class="fas fa-folder-plus me-1"></i> Tambah Folder
                </a>
            </div>
            <div>
                <a href="{{ route('folder.kategori.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-cog me-1"></i> Kelola Kategori
                </a>
            </div>
        </div>

        <!-- Daftar Kategori dan Folder -->
        @forelse($kategoris as $kategori)
        <div class="kategori-card">
            <div class="kategori-header">
                <h5>
                    <i class="fas fa-folder-open me-2"></i> {{ $kategori->nama }}
                </h5>
                <span class="badge">{{ $kategori->folders->count() }} Folder</span>
            </div>
            <div class="folder-list">
                @forelse($kategori->folders as $folder)
                <div class="folder-item">
                    <div class="folder-info">
                        <h6>
                            {{ $folder->nama }}
                            @if(!$folder->is_active)
                            <span class="status-badge inactive ms-2">Nonaktif</span>
                            @else
                            <span class="status-badge active ms-2">Aktif</span>
                            @endif
                        </h6>
                        <p>{{ $folder->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                        <div class="folder-stats">
                            <span><i class="fas fa-file-alt me-1"></i> {{ $folder->dokumens->count() }} Dokumen</span>
                            <span><i class="fas fa-weight-hanging me-1"></i> Max {{ $folder->max_size_mb }} MB</span>
                            <span><i class="fas fa-file me-1"></i> {{ $folder->ekstensi_allowed ? implode(', ', $folder->ekstensi_allowed) : 'Semua' }}</span>
                        </div>
                    </div>
                    <div class="folder-actions">
                        <a href="{{ route('folder.toggle', $folder->id) }}" class="btn btn-sm btn-folder-action {{ $folder->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                           onclick="return confirm('Ubah status folder ini?')">
                            <i class="fas fa-{{ $folder->is_active ? 'ban' : 'check' }}"></i>
                        </a>
                        <a href="{{ route('folder.edit', $folder->id) }}" class="btn btn-sm btn-outline-primary btn-folder-action">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-danger btn-folder-action delete-folder" 
                                data-id="{{ $folder->id }}" data-name="{{ $folder->nama }}"
                                data-dokumen="{{ $folder->dokumens->count() }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                @empty
                <div class="empty-folder">
                    <i class="fas fa-folder-open fa-3x mb-2 opacity-50"></i>
                    <p class="mb-0">Belum ada folder dalam kategori ini</p>
                    <a href="{{ route('folder.create') }}?kategori={{ $kategori->id }}" class="btn btn-sm btn-link text-success mt-2">
                        <i class="fas fa-plus me-1"></i> Tambah Folder
                    </a>
                </div>
                @endforelse
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-5x text-muted mb-3 opacity-25"></i>
            <h5 class="text-muted">Belum ada kategori folder</h5>
            <p class="text-muted">Buat kategori folder terlebih dahulu untuk mulai mengelola dokumen</p>
            <a href="{{ route('folder.kategori.create') }}" class="btn btn-success mt-2">
                <i class="fas fa-plus me-1"></i> Buat Kategori Pertama
            </a>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Hapus Folder -->
<div class="modal fade" id="deleteFolderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center px-4 pb-4">
                <div class="mb-3">
                    <i class="fas fa-exclamation-triangle fa-4x text-warning"></i>
                </div>
                <h5 class="mb-3">Hapus Folder?</h5>
                <p class="text-muted mb-2">Anda akan menghapus folder: <strong id="deleteFolderName"></strong></p>
                <div id="deleteWarning" class="alert alert-warning mt-3" style="display: none;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span id="warningMessage"></span>
                </div>
                <form id="deleteFolderForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 mt-3">
                        <i class="fas fa-trash me-2"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Delete folder confirmation
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteFolderModal'));
    const deleteForm = document.getElementById('deleteFolderForm');
    const deleteFolderName = document.getElementById('deleteFolderName');
    const deleteWarning = document.getElementById('deleteWarning');
    const warningMessage = document.getElementById('warningMessage');

    document.querySelectorAll('.delete-folder').forEach(btn => {
        btn.addEventListener('click', function() {
            const folderId = this.dataset.id;
            const folderName = this.dataset.name;
            const dokumenCount = parseInt(this.dataset.dokumen);
            
            deleteFolderName.textContent = folderName;
            deleteForm.action = `{{ url('folder') }}/${folderId}`;
            
            if (dokumenCount > 0) {
                warningMessage.textContent = `Folder ini memiliki ${dokumenCount} dokumen. Folder tidak bisa dihapus jika masih memiliki dokumen.`;
                deleteWarning.style.display = 'block';
                deleteForm.querySelector('button[type="submit"]').disabled = true;
            } else {
                deleteWarning.style.display = 'none';
                deleteForm.querySelector('button[type="submit"]').disabled = false;
            }
            
            deleteModal.show();
        });
    });
</script>
@endsection