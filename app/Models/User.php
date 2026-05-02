<?php

namespace App\Models;

use App\Traits\NotifiableCustom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable,NotifiableCustom;

    protected $fillable = [
        'name',
        'email',
        'no_telp',
        'role_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Relasi ke Pegawai (one-to-one)
    public function pegawai()
    {
        return $this->hasOne(Pegawai::class);
    }

    // Relasi ke KategoriFolder sebagai creator
    public function createdKategoriFolder()
    {
        return $this->hasMany(KategoriFolder::class, 'created_by');
    }

    // Relasi ke Folder sebagai creator
    public function createdFolder()
    {
        return $this->hasMany(Folder::class, 'created_by');
    }

    // Relasi ke Dokumen sebagai uploader
    public function uploadedDokumen()
    {
        return $this->hasMany(Dokumen::class, 'uploaded_by');
    }

    // Relasi ke Dokumen sebagai approver
    public function approvedDokumen()
    {
        return $this->hasMany(Dokumen::class, 'approved_by');
    }

    // Relasi ke Pengajuan sebagai reviewer
    public function reviewedPengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'reviewed_by');
    }

    // Relasi ke Notifikasi
    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class);
    }

    // Helper methods
    public function hasPermission($permissionName)
    {
        return $this->role
            ->permissions()
            ->where('name', $permissionName)
            ->exists();
    }

    public function isAdmin()
    {
        return $this->role && $this->role->name === 'admin';
    }

    public function isPegawai()
    {
        return $this->role && $this->role->name === 'pegawai';
    }

    public function getUnreadNotificationsCountAttribute()
    {
        return $this->notifikasis()->where('is_read', false)->count();
    }
}