<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Definisikan Permissions
        $permissions = [
            // User Management
            ['name' => 'Kelola User', 'slug' => 'manage-users', 'description' => 'Melihat, menambah, mengedit, dan menghapus user'],
            ['name' => 'Kelola Role', 'slug' => 'manage-roles', 'description' => 'Melihat, menambah, mengedit, dan menghapus role'],
            
            // Asset Management
            ['name' => 'Lihat Aset', 'slug' => 'view-assets', 'description' => 'Melihat daftar dan detail aset'],
            ['name' => 'Tambah Aset', 'slug' => 'create-assets', 'description' => 'Menambahkan aset baru'],
            ['name' => 'Edit Aset', 'slug' => 'update-assets', 'description' => 'Memperbarui data aset'],
            ['name' => 'Hapus Aset', 'slug' => 'delete-assets', 'description' => 'Menghapus data aset'],
            ['name' => 'Cetak Label', 'slug' => 'print-labels', 'description' => 'Mencetak label QR aset'],
            
            // Software & Licenses
            ['name' => 'Kelola Software', 'slug' => 'manage-software', 'description' => 'Melihat dan mengelola lisensi software'],
            
            // Maintenance & Assignments
            ['name' => 'Kelola Maintenance', 'slug' => 'manage-maintenance', 'description' => 'Mengelola riwayat perbaikan aset'],
            ['name' => 'Kelola Assignment', 'slug' => 'manage-assignments', 'description' => 'Mengelola penugasan aset ke user'],
            
            // Reports
            ['name' => 'Lihat Laporan', 'slug' => 'view-reports', 'description' => 'Melihat dan mendownload laporan inventaris'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // 2. Buat Role Administrator
        $adminRole = Role::updateOrCreate(['slug' => 'admin'], [
            'name' => 'Administrator',
            'description' => 'Akses penuh ke seluruh sistem',
        ]);
        $adminRole->permissions()->sync(Permission::all());

        // 3. Buat Role Staff IT
        $staffRole = Role::updateOrCreate(['slug' => 'staff'], [
            'name' => 'Staff IT',
            'description' => 'Akses operasional harian (Aset, Scan, Maintenance)',
        ]);
        $staffRole->permissions()->sync(
            Permission::whereIn('slug', [
                'view-assets', 'create-assets', 'update-assets', 'print-labels',
                'manage-maintenance', 'manage-assignments'
            ])->get()
        );

        $staffRole->permissions()->sync(
            Permission::whereIn('slug', [
                'view-assets', 'create-assets', 'update-assets', 'print-labels',
                'manage-maintenance', 'manage-assignments'
            ])->get()
        );
    }
}
