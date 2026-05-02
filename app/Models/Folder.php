<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $table = 'folder';

    protected $fillable = [
        'kategori_folder_id',
        'nama',
        'deskripsi',
        'ekstensi_allowed',
        'max_size_mb',
        'urutan',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'ekstensi_allowed' => 'array',
        'is_active' => 'boolean',
        'max_size_mb' => 'integer',
        'urutan' => 'integer',
    ];

    // Relasi ke KategoriFolder
    public function kategoriFolder()
    {
        return $this->belongsTo(KategoriFolder::class);
    }

    // Relasi ke Dokumen (one-to-many)
    public function dokumens()
    {
        return $this->hasMany(Dokumen::class);
    }

    // Relasi ke Pengajuan (one-to-many)
    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class);
    }

    // Relasi ke User sebagai creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper methods
    public function getDokumenCountAttribute()
    {
        return $this->dokumens()->where('status', 'aktif')->count();
    }

    public function isEkstensiAllowed($extension)
    {
        if (empty($this->ekstensi_allowed)) {
            return true;
        }
        return in_array(strtolower($extension), $this->ekstensi_allowed);
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