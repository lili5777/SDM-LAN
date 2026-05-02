<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'is_read',
        'url',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function getTipeBadgeAttribute()
    {
        return match($this->tipe) {
            'info' => '<span class="badge bg-info">ℹ️ Info</span>',
            'sukses' => '<span class="badge bg-success">✅ Sukses</span>',
            'peringatan' => '<span class="badge bg-warning text-dark">⚠️ Peringatan</span>',
            'error' => '<span class="badge bg-danger">❌ Error</span>',
            default => '<span class="badge bg-secondary">' . $this->tipe . '</span>',
        };
    }

    public function markAsRead()
    {
        $this->is_read = true;
        return $this->save();
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}