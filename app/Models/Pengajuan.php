<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';

    protected $fillable = [
        'pegawai_id',
        'jenis',
        'dokumen_id',
        'folder_id',
        'judul',
        'nomor_dokumen',
        'tanggal_dokumen',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'keterangan',
        'alasan_pengajuan',
        'status',
        'catatan_admin',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'tanggal_dokumen' => 'date',
        'file_size' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    // Relasi ke Pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    // Relasi ke Dokumen (untuk pengajuan hapus)
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class);
    }

    // Relasi ke Folder
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    // Relasi ke User sebagai reviewer
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Helper methods
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'menunggu' => '<span class="badge bg-warning text-dark">⏳ Menunggu</span>',
            'disetujui' => '<span class="badge bg-success">✅ Disetujui</span>',
            'ditolak' => '<span class="badge bg-danger">❌ Ditolak</span>',
            default => '<span class="badge bg-secondary">' . $this->status . '</span>',
        };
    }

    public function getJenisBadgeAttribute()
    {
        return match($this->jenis) {
            'upload' => '<span class="badge bg-info">📤 Upload</span>',
            'hapus' => '<span class="badge bg-danger">🗑️ Hapus</span>',
            default => '<span class="badge bg-secondary">' . $this->jenis . '</span>',
        };
    }

    public function isPending()
    {
        return $this->status === 'menunggu';
    }

    public function isApproved()
    {
        return $this->status === 'disetujui';
    }

    public function isRejected()
    {
        return $this->status === 'ditolak';
    }

    public function scopePending($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    public function scopeByPegawai($query, $pegawaiId)
    {
        return $query->where('pegawai_id', $pegawaiId);
    }
}