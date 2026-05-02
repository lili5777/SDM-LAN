<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriFolder extends Model
{
    use HasFactory;

    protected $table = 'kategori_folder';

    protected $fillable = [
        'nama',
        'deskripsi',
        'urutan',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    // Relasi ke Folder (one-to-many)
    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    // Relasi ke User sebagai creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper methods
    public function getFoldersAktifAttribute()
    {
        return $this->folders()->where('is_active', true)->get();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('nama');
    }
}