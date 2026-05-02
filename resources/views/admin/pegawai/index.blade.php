@extends('admin.partials.layout')

@section('title', 'Data Pegawai - JABLAYMEN')
@section('page-title', 'Data Pegawai')

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
    .stats-icon.info { background: #e3f2fd; color: #0ea5e9; }
    .stats-icon.warning { background: #fef3c7; color: #d97706; }
    
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }
    
    .pegawai-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .pegawai-item {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .pegawai-item:hover {
        background: #f8fafc;
    }
    
    .avatar-small {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #166534, #15803d);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .status-badge.aktif { background: #d1fae5; color: #065f46; }
    .status-badge.pensiun { background: #fef3c7; color: #d97706; }
    .status-badge.mutasi { background: #dbeafe; color: #2563eb; }
    .status-badge.berhenti { background: #fee2e2; color: #dc2626; }
    
    .btn-action-sm {
        padding: 0.3rem 0.6rem;
        border-radius: 8px;
        margin: 0 2px;
    }
    
    .has-account {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    .has-account.yes { background: #10b981; box-shadow: 0 0 5px #10b981; }
    .has-account.no { background: #ef4444; box-shadow: 0 0 5px #ef4444; }
</style>
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ number_format($pegawais->total()) }}</div>
                    <div class="stats-label">Total Pegawai</div>
                </div>
                <div class="stats-icon primary">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ number_format($pegawais->where('status', 'aktif')->count()) }}</div>
                    <div class="stats-label">Pegawai Aktif</div>
                </div>
                <div class="stats-icon primary">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ number_format($pegawais->whereNotNull('user_id')->count()) }}</div>
                    <div class="stats-label">Memiliki Akun</div>
                </div>
                <div class="stats-icon info">
                    <i class="fas fa-laptop"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ number_format($pegawais->whereNull('user_id')->count()) }}</div>
                    <div class="stats-label">Belum Punya Akun</div>
                </div>
                <div class="stats-icon warning">
                    <i class="fas fa-user-times"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="fas fa-user-tie me-2 text-success"></i> Daftar Pegawai
            </h4>
            <a href="{{ route('pegawai.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> Tambah Pegawai
            </a>
        </div>

        <!-- Filter Card -->
        <div class="filter-card">
            <form method="GET" action="{{ route('pegawai.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Unit Kerja</label>
                    <select name="unit_kerja_id" class="form-select">
                        <option value="">Semua Unit</option>
                        @foreach($unitKerjas ?? [] as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="pensiun" {{ request('status') == 'pensiun' ? 'selected' : '' }}>Pensiun</option>
                        <option value="mutasi" {{ request('status') == 'mutasi' ? 'selected' : '' }}>Mutasi</option>
                        <option value="berhenti" {{ request('status') == 'berhenti' ? 'selected' : '' }}>Berhenti</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama, NIP, atau jabatan..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Pegawai List -->
        <div class="pegawai-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="30%">Pegawai</th>
                            <th width="20%">NIP / Unit</th>
                            <th width="15%">Golongan / Jabatan</th>
                            <th width="10%">Status</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pegawais as $index => $pegawai)
                        <tr class="pegawai-item">
                            <td>{{ $loop->iteration + ($pegawais->currentPage() - 1) * $pegawais->perPage() }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-small">
                                        {{ strtoupper(substr($pegawai->nama, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $pegawai->nama }}</div>
                                        <div class="small text-muted">
                                            @if($pegawai->user_id)
                                            <span class="has-account yes"></span> Ada akun
                                            @else
                                            <span class="has-account no"></span> Belum ada akun
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $pegawai->nip }}</div>
                                <div class="small text-muted">{{ $pegawai->unitKerja->nama ?? '-' }}</div>
                            </td>
                            <td>
                                <div>{{ $pegawai->golongan ?? '-' }}</div>
                                <div class="small text-muted">{{ $pegawai->jabatan ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="status-badge {{ $pegawai->status }}">
                                    {{ ucfirst($pegawai->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    @if(!$pegawai->user_id)
                                    <a href="{{ route('pegawai.create-user', $pegawai->id) }}" class="btn btn-sm btn-outline-info btn-action-sm" title="Buat Akun">
                                        <i class="fas fa-user-plus"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-sm btn-outline-primary btn-action-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-action-sm delete-pegawai" 
                                            data-id="{{ $pegawai->id }}" data-name="{{ $pegawai->nama }}"
                                            data-dokumen="{{ $pegawai->dokumens->count() }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-users fa-4x text-muted mb-3 opacity-25"></i>
                                <h5 class="text-muted">Belum ada data pegawai</h5>
                                <p class="text-muted">Klik tombol "Tambah Pegawai" untuk menambahkan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($pegawais->total() > 0)
            <div class="card-footer bg-white py-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Menampilkan {{ $pegawais->firstItem() ?? 0 }} - {{ $pegawais->lastItem() ?? 0 }} dari {{ $pegawais->total() }} pegawai
                    </small>
                </div>
            </div>
            @endif
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $pegawais->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Modal Hapus Pegawai -->
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
                <h5 class="mb-3">Hapus Pegawai?</h5>
                <p class="text-muted mb-2">Anda akan menghapus pegawai: <strong id="deletePegawaiName"></strong></p>
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
    // Delete pegawai confirmation
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteForm = document.getElementById('deleteForm');
    const deletePegawaiName = document.getElementById('deletePegawaiName');
    const deleteWarning = document.getElementById('deleteWarning');
    const warningMessage = document.getElementById('warningMessage');

    document.querySelectorAll('.delete-pegawai').forEach(btn => {
        btn.addEventListener('click', function() {
            const pegawaiId = this.dataset.id;
            const pegawaiName = this.dataset.name;
            const dokumenCount = parseInt(this.dataset.dokumen);
            
            deletePegawaiName.textContent = pegawaiName;
            deleteForm.action = `{{ url('pegawai') }}/${pegawaiId}`;
            
            if (dokumenCount > 0) {
                warningMessage.textContent = `Pegawai ini memiliki ${dokumenCount} dokumen. Pegawai tidak bisa dihapus jika masih memiliki dokumen.`;
                deleteWarning.style.display = 'block';
                deleteForm.querySelector('button[type="submit"]').disabled = true;
            } else {
                deleteWarning.style.display = 'none';
                deleteForm.querySelector('button[type="submit"]').disabled = false;
            }
            
            deleteModal.show();
        });
    });
    
    // Auto submit filter on change
    document.querySelectorAll('.filter-card select').forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
</script>
@endsection