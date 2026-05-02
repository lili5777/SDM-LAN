<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $fillable = [
        'pegawai_id',
        'folder_id',
        'judul',
        'nomor_dokumen',
        'tanggal_dokumen',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'keterangan',
        'status',
        'uploaded_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal_dokumen' => 'date',
        'file_size' => 'integer',
        'approved_at' => 'datetime',
    ];

    // Relasi ke Pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    // Relasi ke Folder
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    // Relasi ke User sebagai uploader
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Relasi ke User sebagai approver
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Relasi ke Pengajuan (untuk hapus)
    public function pengajuanHapus()
    {
        return $this->hasOne(Pengajuan::class)->where('jenis', 'hapus');
    }

    // Helper methods
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByPegawai($query, $pegawaiId)
    {
        return $query->where('pegawai_id', $pegawaiId);
    }

    public function scopeByFolder($query, $folderId)
    {
        return $query->where('folder_id', $folderId);
    }
}