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
        $staffRole = Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']);

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

        // === BARANG RUSAK PERMISSIONS ===
        Permission::firstOrCreate(['name' => 'view_barang_rusaks', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create_barang_rusaks', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit_barang_rusaks', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete_barang_rusaks', 'guard_name' => 'web']);

        // === LOG AKTIVITAS PERMISSIONS ===
        Permission::firstOrCreate(['name' => 'view_log_aktivitas', 'guard_name' => 'web']);

        // === DASHBOARD & LAPORAN PERMISSIONS ===
        Permission::firstOrCreate(['name' => 'view_dashboard', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view_laporan', 'guard_name' => 'web']);

        // === SYSTEM PERMISSIONS ===
        Permission::firstOrCreate(['name' => 'backup_system', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'restore_system', 'guard_name' => 'web']);

        // Assign all permissions to Admin Sistem role
        $adminRole->syncPermissions(Permission::all());

        // Assign limited permissions to Staff role
        $staffRole->syncPermissions([
            'view_users',
            'view_kategoris',
            'view_barangs',
            'view_lokasis',
            'view_transaksi_barangs',
            'create_transaksi_barangs',
            'edit_transaksi_barangs',
            'view_barang_rusaks',
            'create_barang_rusaks',
            'edit_barang_rusaks',
            'view_log_aktivitas',
            'view_dashboard',
            'view_laporan',
        ]);

        // Assign Admin Sistem role to existing admin user if exists
        $adminUser = User::where('email', 'admin@inventaris.test')->first();
        if ($adminUser) {
            $adminUser->assignRole('Admin Sistem');
            $this->command->info("✓ Role 'Admin Sistem' assigned to {$adminUser->name}");
        }

        // Assign Staff role to other users if exists
        User::where('email', '!=', 'admin@inventaris.test')
            ->whereDoesntHave('roles')
            ->each(function ($user) use ($staffRole) {
                $user->assignRole('Staff');
            });

        $this->command->info('✓ Roles and Permissions seeded successfully!');
    }
}
