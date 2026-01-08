<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin Sistem', 'guard_name' => 'web']);
        $petugasInventarisRole = Role::firstOrCreate(['name' => 'Petugas Inventaris', 'guard_name' => 'web']);
        $kepalaSekolahRole = Role::firstOrCreate(['name' => 'Kepala Sekolah', 'guard_name' => 'web']);

        // === USER MANAGEMENT PERMISSIONS ===
        Permission::firstOrCreate(['name' => 'view_users', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create_users', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit_users', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete_users', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'reset_password', 'guard_name' => 'web']);

        // === KATEGORI BARANG PERMISSIONS ===
        Permission::firstOrCreate(['name' => 'view_kategoris', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create_kategoris', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit_kategoris', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete_kategoris', 'guard_name' => 'web']);

        // === BARANG PERMISSIONS ===
        Permission::firstOrCreate(['name' => 'view_barangs', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create_barangs', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit_barangs', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete_barangs', 'guard_name' => 'web']);

        // === LOKASI PERMISSIONS ===
        Permission::firstOrCreate(['name' => 'view_lokasis', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create_lokasis', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit_lokasis', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete_lokasis', 'guard_name' => 'web']);

        // === TRANSAKSI PERMISSIONS ===
        Permission::firstOrCreate(['name' => 'view_transaksi_barangs', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create_transaksi_barangs', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit_transaksi_barangs', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete_transaksi_barangs', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'approve_transaksi_barangs', 'guard_name' => 'web']);

        // === BARANG RUSAK PERMISSIONS ===
        Permission::firstOrCreate(['name' => 'view_barang_rusaks', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create_barang_rusaks', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit_barang_rusaks', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete_barang_rusaks', 'guard_name' => 'web']);

        // === LOG AKTIVITAS PERMISSIONS ===
        Permission::firstOrCreate(['name' => 'view_log_aktivitas', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete_log_aktivitas', 'guard_name' => 'web']);

        // === DASHBOARD & LAPORAN PERMISSIONS ===
        Permission::firstOrCreate(['name' => 'view_dashboard', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view_laporan', 'guard_name' => 'web']);

        // === SYSTEM PERMISSIONS ===
        Permission::firstOrCreate(['name' => 'backup_system', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'restore_system', 'guard_name' => 'web']);

        // =============================================
        // ADMIN SISTEM PERMISSIONS (FULL ACCESS)
        // =============================================
        // Admin: Akses penuh ke semua menu dan fitur
        $adminRole->syncPermissions([
            // User Management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'reset_password',
            // Kategori Barang
            'view_kategoris',
            'create_kategoris',
            'edit_kategoris',
            'delete_kategoris',
            // Barang
            'view_barangs',
            'create_barangs',
            'edit_barangs',
            'delete_barangs',
            // Lokasi
            'view_lokasis',
            'create_lokasis',
            'edit_lokasis',
            'delete_lokasis',
            // Barang Rusak
            'view_barang_rusaks',
            'create_barang_rusaks',
            'edit_barang_rusaks',
            'delete_barang_rusaks',
            // Transaksi Barang
            'view_transaksi_barangs',
            'create_transaksi_barangs',
            'edit_transaksi_barangs',
            'delete_transaksi_barangs',
            'approve_transaksi_barangs',
            // Log Aktivitas
            'view_log_aktivitas',
            'delete_log_aktivitas',
            // Backup & Restore
            'backup_system',
            'restore_system',
            // Dashboard & Laporan
            'view_dashboard',
            'view_laporan',
        ]);

        // =============================================
        // PETUGAS INVENTARIS PERMISSIONS
        // =============================================
        // Petugas: Kelola Barang, Barang Rusak, Transaksi Masuk/Keluar, Dashboard Laporan
        $petugasInventarisRole->syncPermissions([
            // Data Barang Management
            'view_barangs',
            'create_barangs',
            'edit_barangs',
            'delete_barangs',
            // Barang Rusak
            'view_barang_rusaks',
            'create_barang_rusaks',
            'edit_barang_rusaks',
            'delete_barang_rusaks',
            // Transaksi Barang (Masuk & Keluar)
            'view_transaksi_barangs',
            'create_transaksi_barangs',
            'edit_transaksi_barangs',
            'delete_transaksi_barangs',
            // Dashboard & Laporan
            'view_dashboard',
            'view_laporan',
        ]);

        // =============================================
        // KEPALA SEKOLAH PERMISSIONS
        // =============================================
        // Kepala Sekolah: Dashboard Laporan, View Barang/Barang Rusak, Approve Permintaan
        $kepalaSekolahRole->syncPermissions([
            // Dashboard & Laporan
            'view_dashboard',
            'view_laporan',
            // View Only: Barang & Barang Rusak
            'view_barangs',
            'view_barang_rusaks',
            // Transaksi: View + Approve
            'view_transaksi_barangs',
            'approve_transaksi_barangs',
        ]);

        // Assign roles to existing users
        $adminUser = User::where('email', 'admin@inventaris.test')->first();
        if ($adminUser) {
            $adminUser->syncRoles(['Admin Sistem']);
            $this->command->info("✓ Role 'Admin Sistem' assigned to {$adminUser->name}");
        }

        $petugasUser = User::where('email', 'petugas@inventaris.test')->first();
        if ($petugasUser) {
            $petugasUser->syncRoles(['Petugas Inventaris']);
            $this->command->info("✓ Role 'Petugas Inventaris' assigned to {$petugasUser->name}");
        }

        $kepalaUser = User::where('email', 'kepala@inventaris.test')->first();
        if ($kepalaUser) {
            $kepalaUser->syncRoles(['Kepala Sekolah']);
            $this->command->info("✓ Role 'Kepala Sekolah' assigned to {$kepalaUser->name}");
        }

        $this->command->info('✓ Roles and Permissions seeded successfully!');
        $this->command->info('');
        $this->command->info('📋 Role Summary:');
        $this->command->info('  • Admin Sistem: User Management, Kategori, Log, Backup/Restore');
        $this->command->info('  • Petugas Inventaris: Barang, Barang Rusak, Transaksi, Dashboard');
        $this->command->info('  • Kepala Sekolah: Dashboard, View Barang, Approve Transaksi');
    }
}
