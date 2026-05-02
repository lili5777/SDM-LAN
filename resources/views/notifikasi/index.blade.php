@extends('admin.partials.layout')

@section('title', 'Notifikasi - JABLAYMEN')
@section('page-title', 'Notifikasi')

@section('styles')
<style>
    .notification-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .notification-item {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.2s;
        cursor: pointer;
    }
    .notification-item:hover {
        background: #f8fafc;
    }
    .notification-item.unread {
        background: #f0fdf6;
        border-left: 3px solid #166534;
    }
    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .notification-icon.info { background: #dbeafe; color: #2563eb; }
    .notification-icon.sukses { background: #d1fae5; color: #065f46; }
    .notification-icon.peringatan { background: #fef3c7; color: #d97706; }
    .notification-icon.error { background: #fee2e2; color: #dc2626; }
    .notification-title {
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }
    .notification-message {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 0.25rem;
    }
    .notification-time {
        font-size: 0.7rem;
        color: #94a3b8;
    }
    .mark-read-btn {
        opacity: 0;
        transition: opacity 0.2s;
    }
    .notification-item:hover .mark-read-btn {
        opacity: 1;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="fas fa-bell me-2 text-success"></i> Notifikasi
            </h4>
            <a href="{{ route('notifikasi.read-all') }}" class="btn btn-sm btn-outline-success" 
               onclick="return confirm('Tandai semua notifikasi sebagai sudah dibaca?')">
                <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
            </a>
        </div>
        
        <div class="notification-card">
            @forelse($notifikasis as $notif)
            <div class="notification-item {{ !$notif->is_read ? 'unread' : '' }}" 
                 data-id="{{ $notif->id }}" data-url="{{ $notif->url }}">
                <div class="d-flex">
                    <div class="notification-icon {{ $notif->tipe }} me-3">
                        <i class="fas fa-{{ $notif->tipe == 'info' ? 'info-circle' : ($notif->tipe == 'sukses' ? 'check-circle' : ($notif->tipe == 'peringatan' ? 'exclamation-triangle' : 'times-circle')) }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="notification-title">{{ $notif->judul }}</div>
                        <div class="notification-message">{{ $notif->pesan }}</div>
                        <div class="notification-time">
                            <i class="far fa-clock me-1"></i> {{ $notif->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="mark-read-btn ms-2">
                        @if(!$notif->is_read)
                        <button class="btn btn-sm btn-outline-success mark-read" data-id="{{ $notif->id }}">
                            <i class="fas fa-check"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada notifikasi</h5>
                <p class="text-muted small">Semua notifikasi akan muncul di sini</p>
            </div>
            @endforelse
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $notifikasis->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function(e) {
            // Jangan redirect jika klik tombol mark-read
            if (e.target.closest('.mark-read')) return;
            
            const url = this.dataset.url;
            if (url && url !== '') {
                window.location.href = url;
            }
        });
    });
    
    document.querySelectorAll('.mark-read').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.dataset.id;
            
            fetch(`/notifikasi/${id}/read`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      const item = this.closest('.notification-item');
                      item.classList.remove('unread');
                      this.remove();
                  }
              });
        });
    });
</script>
@endsection