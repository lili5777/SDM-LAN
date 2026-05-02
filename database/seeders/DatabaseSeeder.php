<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\Pegawai;
use App\Models\KategoriFolder;
use App\Models\Folder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========== STEP 1: Create Roles ==========
        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Administrator sistem'
        ]);

        $pegawaiRole = Role::create([
            'name' => 'pegawai',
            'description' => 'Pegawai biasa'
        ]);

        // ========== STEP 2: Create Permissions ==========
        $permissions = [
            // User management
            ['name' => 'user.create', 'description' => 'Membuat user'],
            ['name' => 'user.read', 'description' => 'Melihat user'],
            ['name' => 'user.update', 'description' => 'Mengupdate user'],
            ['name' => 'user.delete', 'description' => 'Menghapus user'],
            
            // Role management
            ['name' => 'role.create', 'description' => 'Membuat role'],
            ['name' => 'role.read', 'description' => 'Melihat role'],
            ['name' => 'role.update', 'description' => 'Mengupdate role'],
            ['name' => 'role.delete', 'description' => 'Menghapus role'],
            
            // JABLAYMEN Admin permissions
            ['name' => 'admin.access', 'description' => 'Akses panel admin'],
            ['name' => 'manage_folders', 'description' => 'Mengelola folder'],
            ['name' => 'manage_employees', 'description' => 'Mengelola data pegawai'],
            ['name' => 'manage_units', 'description' => 'Mengelola unit kerja'],
            ['name' => 'review_submissions', 'description' => 'Review pengajuan dokumen'],
            ['name' => 'view_all_documents', 'description' => 'Melihat semua dokumen'],
            
            // JABLAYMEN Pegawai permissions
            ['name' => 'pegawai.access', 'description' => 'Akses panel pegawai'],
            ['name' => 'upload_documents', 'description' => 'Upload dokumen'],
            ['name' => 'view_own_documents', 'description' => 'Melihat dokumen sendiri'],
            ['name' => 'request_deletion', 'description' => 'Mengajukan hapus dokumen'],
        ];

        foreach ($permissions as $perm) {
            Permission::create($perm);
        }

        // ========== STEP 3: Assign Permissions to Roles ==========
        // Admin gets all permissions
        $adminRole->permissions()->attach(Permission::all()->pluck('id'));

        // Pegawai gets specific permissions
        $pegawaiPermissions = Permission::whereIn('name', [
            'pegawai.access',
            'upload_documents',
            'view_own_documents',
            'request_deletion'
        ])->pluck('id');
        
        $pegawaiRole->permissions()->attach($pegawaiPermissions);

        // ========== STEP 4: Create Unit Kerja ==========
        $unit1 = UnitKerja::create([
            'kode' => 'PUSJAR',
            'nama' => 'Pusat Pengembangan Jabatan',
            'deskripsi' => 'Unit pengembangan karir pegawai'
        ]);

        $unit2 = UnitKerja::create([
            'kode' => 'SKMP',
            'nama' => 'Sekretariat Kompetensi',
            'deskripsi' => 'Unit pengelola kompetensi'
        ]);

        $unit3 = UnitKerja::create([
            'kode' => 'UMUM',
            'nama' => 'Umum',
            'deskripsi' => 'Unit umum'
        ]);

        // ========== STEP 5: Create Admin User ==========
        $adminUser = User::create([
            'name' => 'Administrator',
            'email' => 'admin@jablaymen.com',
            'no_telp' => '081234567890',
            'role_id' => $adminRole->id,
            'password' => Hash::make('password'),
        ]);

        // Create Admin Pegawai record
        $adminPegawai = Pegawai::create([
            'nip' => 'ADM001',
            'nama' => 'Administrator',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'golongan' => 'IV/a',
            'jabatan' => 'Administrator Sistem',
            'unit_kerja_id' => $unit3->id,
            'status' => 'aktif',
            'no_hp' => '081234567890',
            'user_id' => $adminUser->id,
        ]);

        // ========== STEP 6: Create Sample Pegawai ==========
        $pegawaiUser = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@jablaymen.com',
            'no_telp' => '081234567891',
            'role_id' => $pegawaiRole->id,
            'password' => Hash::make('password'),
        ]);

        Pegawai::create([
            'nip' => 'PEG001',
            'nama' => 'Budi Santoso',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1985-05-15',
            'jenis_kelamin' => 'L',
            'golongan' => 'III/b',
            'jabatan' => 'Staff',
            'unit_kerja_id' => $unit1->id,
            'status' => 'aktif',
            'no_hp' => '081234567891',
            'user_id' => $pegawaiUser->id,
        ]);

        $pegawaiUser2 = User::create([
            'name' => 'Sari Dewi',
            'email' => 'sari@jablaymen.com',
            'no_telp' => '081234567892',
            'role_id' => $pegawaiRole->id,
            'password' => Hash::make('password'),
        ]);

        Pegawai::create([
            'nip' => 'PEG002',
            'nama' => 'Sari Dewi',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1990-08-20',
            'jenis_kelamin' => 'P',
            'golongan' => 'III/a',
            'jabatan' => 'Staff',
            'unit_kerja_id' => $unit2->id,
            'status' => 'aktif',
            'no_hp' => '081234567892',
            'user_id' => $pegawaiUser2->id,
        ]);

        // ========== STEP 7: Create Kategori Folder ==========
        $kategoriUmum = KategoriFolder::create([
            'nama' => 'UMUM',
            'deskripsi' => 'Dokumen umum kepegawaian',
            'urutan' => 1,
            'is_active' => true,
            'created_by' => $adminUser->id,
        ]);

        $kategoriPembelajaran = KategoriFolder::create([
            'nama' => 'UNIT PEMBELAJARAN',
            'deskripsi' => 'Dokumen unit pembelajaran',
            'urutan' => 2,
            'is_active' => true,
            'created_by' => $adminUser->id,
        ]);

        // ========== STEP 8: Create Folders ==========
        $folders = [
            // UMUM category folders
            [
                'kategori_folder_id' => $kategoriUmum->id,
                'nama' => 'Kepegawaian',
                'deskripsi' => 'Dokumen kepegawaian',
                'ekstensi_allowed' => ['pdf', 'jpg', 'png'],
                'max_size_mb' => 10,
                'urutan' => 1,
                'is_active' => true,
                'created_by' => $adminUser->id,
            ],
            [
                'kategori_folder_id' => $kategoriUmum->id,
                'nama' => 'Sertifikat',
                'deskripsi' => 'Dokumen sertifikat',
                'ekstensi_allowed' => ['pdf', 'jpg', 'png'],
                'max_size_mb' => 10,
                'urutan' => 2,
                'is_active' => true,
                'created_by' => $adminUser->id,
            ],
            [
                'kategori_folder_id' => $kategoriUmum->id,
                'nama' => 'Kenaikan Pangkat',
                'deskripsi' => 'Dokumen kenaikan pangkat',
                'ekstensi_allowed' => ['pdf'],
                'max_size_mb' => 5,
                'urutan' => 3,
                'is_active' => true,
                'created_by' => $adminUser->id,
            ],
            // UNIT PEMBELAJARAN category folders
            [
                'kategori_folder_id' => $kategoriPembelajaran->id,
                'nama' => 'Materi Pelatihan',
                'deskripsi' => 'Materi pelatihan',
                'ekstensi_allowed' => ['pdf', 'doc', 'docx'],
                'max_size_mb' => 20,
                'urutan' => 1,
                'is_active' => true,
                'created_by' => $adminUser->id,
            ],
            [
                'kategori_folder_id' => $kategoriPembelajaran->id,
                'nama' => 'Sertifikat Pelatihan',
                'deskripsi' => 'Sertifikat pelatihan',
                'ekstensi_allowed' => ['pdf'],
                'max_size_mb' => 5,
                'urutan' => 2,
                'is_active' => true,
                'created_by' => $adminUser->id,
            ],
            [
                'kategori_folder_id' => $kategoriPembelajaran->id,
                'nama' => 'Laporan Kinerja',
                'deskripsi' => 'Laporan kinerja pegawai',
                'ekstensi_allowed' => ['pdf', 'doc', 'docx', 'xls', 'xlsx'],
                'max_size_mb' => 15,
                'urutan' => 3,
                'is_active' => true,
                'created_by' => $adminUser->id,
            ],
        ];

        foreach ($folders as $folder) {
            Folder::create($folder);
        }
    }
}