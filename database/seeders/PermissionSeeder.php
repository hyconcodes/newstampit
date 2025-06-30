<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Role::truncate();
        // Permission::truncate();
        
        $permissions = [
            // 'view.students',
            // 'create.students',
            // 'edit.students',
            // 'delete.students',
            // 'view.roles',
            // 'create.roles',
            // 'edit.roles',
            // 'delete.roles',
            // 'view.permissions',
            // 'create.permissions',
            // 'edit.permissions',
            // 'delete.permissions',

            'view.staffs',
            'create.staffs',
            'edit.staffs',
            'delete.staffs'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        // $adminRole = Role::create(['name' => 'super admin']);
        // $userRole = Role::create(['name' => 'student']);

        // Assign all permissions to admin role
        // $adminRole->givePermissionTo($permissions);

        // Assign limited permissions to user role
        // $userRole->givePermissionTo([
        //     'view.students',
        //     'view.roles',
        //     'view.permissions'
        // ]);
    }
}
