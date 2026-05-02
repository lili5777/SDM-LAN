<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'nip',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'golongan',
        'jabatan',
        'unit_kerja_id',
        'status',
        'no_hp',
        'foto',
        'user_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relasi ke UnitKerja (belongs to)
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    // Relasi ke User (belongs to)
    public function user()
    {
        return $this->belongsTo(User::class);
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

    // Helper methods
    public function getDokumenAktifCountAttribute()
    {
        return $this->dokumens()->where('status', 'aktif')->count();
    }

    public function getPengajuanPendingCountAttribute()
    {
        return $this->pengajuans()->where('status', 'menunggu')->count();
    }

    public function getNamaLengkapAttribute()
    {
        return $this->nama . ' (' . $this->nip . ')';
    }
}