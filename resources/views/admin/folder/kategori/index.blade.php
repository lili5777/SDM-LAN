@extends('admin.partials.layout')

@section('title', 'Kelola Kategori Folder - JABLAYMEN')
@section('page-title', 'Kelola Kategori Folder')

@section('styles')
<style>
    .kategori-table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
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
    .btn-action {
        padding: 0.3rem 0.6rem;
        border-radius: 8px;
        margin: 0 2px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="fas fa-layer-group me-2 text-success"></i> Daftar Kategori Folder
            </h4>
            <a href="{{ route('folder.kategori.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> Tambah Kategori
            </a>
        </div>

        <div class="kategori-table-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="30%">Nama Kategori</th>
                            <th width="40%">Deskripsi</th>
                            <th width="15%">Status</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoris as $index => $kategori)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ $kategori->nama }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-folder me-1"></i> {{ $kategori->folders->count() }} Folder
                                </small>
                            </td>
                            <td>{{ $kategori->deskripsi ?: '-' }}</td>
                            <td>
                                <span class="status-badge {{ $kategori->is_active ? 'active' : 'inactive' }}">
                                    {{ $kategori->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('folder.kategori.edit', $kategori->id) }}" class="btn btn-sm btn-outline-primary btn-action">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger btn-action delete-kategori" 
                                        data-id="{{ $kategori->id }}" data-name="{{ $kategori->nama }}"
                                        data-folder="{{ $kategori->folders->count() }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-layer-group fa-3x text-muted mb-3 opacity-25"></i>
                                <h5 class="text-muted">Belum ada kategori folder</h5>
                                <p class="text-muted">Buat kategori folder untuk mengelompokkan folder dokumen</p>
                                <a href="{{ route('folder.kategori.create') }}" class="btn btn-success mt-2">
                                    <i class="fas fa-plus me-1"></i> Buat Kategori Pertama
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus Kategori -->
<div class="modal fade" id="deleteKategoriModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center px-4 pb-4">
                <div class="mb-3">
                    <i class="fas fa-exclamation-triangle fa-4x text-warning"></i>
                </div>
                <h5 class="mb-3">Hapus Kategori?</h5>
                <p class="text-muted mb-2">Anda akan menghapus kategori: <strong id="deleteKategoriName"></strong></p>
                <div id="deleteKategoriWarning" class="alert alert-warning mt-3" style="display: none;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span id="warningKategoriMessage"></span>
                </div>
                <form id="deleteKategoriForm" method="POST">
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
    // Delete kategori confirmation
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteKategoriModal'));
    const deleteForm = document.getElementById('deleteKategoriForm');
    const deleteKategoriName = document.getElementById('deleteKategoriName');
    const deleteWarning = document.getElementById('deleteKategoriWarning');
    const warningMessage = document.getElementById('warningKategoriMessage');

    document.querySelectorAll('.delete-kategori').forEach(btn => {
        btn.addEventListener('click', function() {
            const kategoriId = this.dataset.id;
            const kategoriName = this.dataset.name;
            const folderCount = parseInt(this.dataset.folder);
            
            deleteKategoriName.textContent = kategoriName;
            deleteForm.action = `{{ url('folder/kategori') }}/${kategoriId}`;
            
            if (folderCount > 0) {
                warningMessage.textContent = `Kategori ini memiliki ${folderCount} folder. Kategori tidak bisa dihapus jika masih memiliki folder.`;
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