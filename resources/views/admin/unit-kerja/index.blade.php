@extends('admin.partials.layout')

@section('title', 'Unit Kerja - JABLAYMEN')
@section('page-title', 'Unit Kerja')

@section('styles')
<style>
    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s;
        height: 100%;
    }
    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(22,101,52,0.1);
    }
    .stats-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1;
    }
    .stats-label {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 5px;
    }
    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stats-icon.primary { background: #e8f5e9; color: #166534; }
    
    .unit-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .unit-item {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .unit-item:hover {
        background: #f8fafc;
    }
    
    .badge-unit {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        background: #dbeafe;
        color: #2563eb;
    }
</style>
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-4 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ number_format($unitKerjas->count()) }}</div>
                    <div class="stats-label">Total Unit Kerja</div>
                </div>
                <div class="stats-icon primary">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ number_format($unitKerjas->sum('pegawais_count')) }}</div>
                    <div class="stats-label">Total Pegawai</div>
                </div>
                <div class="stats-icon primary">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ number_format($unitKerjas->avg('pegawais_count'), 1) }}</div>
                    <div class="stats-label">Rata-rata Pegawai/Unit</div>
                </div>
                <div class="stats-icon primary">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="fas fa-sitemap me-2 text-success"></i> Daftar Unit Kerja
            </h4>
            <a href="{{ route('unit-kerja.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> Tambah Unit Kerja
            </a>
        </div>

        <div class="unit-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Kode</th>
                            <th width="35%">Nama Unit Kerja</th>
                            <th width="20%">Jumlah Pegawai</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unitKerjas as $index => $unit)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge-unit">{{ $unit->kode }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $unit->nama }}</div>
                                @if($unit->deskripsi)
                                <div class="small text-muted">{{ Str::limit($unit->deskripsi, 50) }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $unit->pegawais_count }} pegawai</div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('unit-kerja.edit', $unit->id) }}" class="btn btn-sm btn-outline-primary btn-action-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-action-sm delete-unit" 
                                        data-id="{{ $unit->id }}" data-name="{{ $unit->nama }}"
                                        data-pegawai="{{ $unit->pegawais_count }}" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-building fa-4x text-muted mb-3 opacity-25"></i>
                                <h5 class="text-muted">Belum ada unit kerja</h5>
                                <p class="text-muted">Klik tombol "Tambah Unit Kerja" untuk menambahkan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus Unit Kerja -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center px-4 pb-4">
                <div class="mb-3">
                    <i class="fas fa-exclamation-triangle fa-4x text-warning"></i>
                </div>
                <h5 class="mb-3">Hapus Unit Kerja?</h5>
                <p class="text-muted mb-2">Anda akan menghapus unit: <strong id="deleteUnitName"></strong></p>
                <div id="deleteWarning" class="alert alert-warning mt-3" style="display: none;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span id="warningMessage"></span>
                </div>
                <form id="deleteForm" method="POST">
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
    // Delete unit confirmation
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteForm = document.getElementById('deleteForm');
    const deleteUnitName = document.getElementById('deleteUnitName');
    const deleteWarning = document.getElementById('deleteWarning');
    const warningMessage = document.getElementById('warningMessage');

    document.querySelectorAll('.delete-unit').forEach(btn => {
        btn.addEventListener('click', function() {
            const unitId = this.dataset.id;
            const unitName = this.dataset.name;
            const pegawaiCount = parseInt(this.dataset.pegawai);
            
            deleteUnitName.textContent = unitName;
            deleteForm.action = `{{ url('unit-kerja') }}/${unitId}`;
            
            if (pegawaiCount > 0) {
                warningMessage.textContent = `Unit ini memiliki ${pegawaiCount} pegawai. Unit tidak bisa dihapus jika masih memiliki pegawai.`;
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