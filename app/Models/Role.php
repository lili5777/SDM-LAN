<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    // Relasi ke User
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relasi ke Permission (many-to-many)
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    // Helper methods
    public function givePermissionTo($permissionName)
    {
        $permission = Permission::firstOrCreate(['name' => $permissionName]);
        $this->permissions()->syncWithoutDetaching([$permission->id]);
    }

    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }
}