<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;

    protected $table = 'unit_kerja';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
    ];

    // Relasi ke Pegawai (one-to-many)
    public function pegawais()
    {
        return $this->hasMany(Pegawai::class);
    }

    // Helper methods
    public function getPegawaiAktifCountAttribute()
    {
        return $this->pegawais()->where('status', 'aktif')->count();
    }
}