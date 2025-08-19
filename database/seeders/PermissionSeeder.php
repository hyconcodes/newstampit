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

        // Update existing permissions
        Permission::where('name', 'view.invoices')->update(['name' => 'view.igr.invoices']);
        Permission::where('name', 'create.invoices')->update(['name' => 'create.igr.invoices']);
        Permission::where('name', 'edit.invoices')->update(['name' => 'edit.igr.invoices']);
        Permission::where('name', 'delete.invoices')->update(['name' => 'delete.igr.invoices']);

        // $permissions = [
        //     'view.students',
        //     'create.students',
        //     'edit.students',
        //     'delete.students',
        //     'view.roles',
        //     'create.roles',
        //     'edit.roles',
        //     'delete.roles',
        //     'view.permissions',
        //     'create.permissions',
        //     'edit.permissions',
        //     'delete.permissions',

        //     'view.staffs',
        //     'create.staffs',
        //     'edit.staffs',
        //     'delete.staffs',

        //     'view.invoices',
        //     'upload.invoices',
        //     'create.invoices',
        //     'edit.invoices',
        //     'delete.invoices',

        //     'stamp.igr.invoices',
        //     'stamp.school.fees.invoices',

        //     'view.school.fees.invoices',
        //     'create.school.fees.invoices',
        //     'edit.school.fees.invoices',
        //     'delete.school.fees.invoices',

        //     'view.schools',
        //     'delete.schools',
        //     'create.schools',
        //     'edit.schools',
        // ];

        // foreach ($permissions as $permission) {
        //     Permission::create(['name' => $permission]);
        // }

        // // Create roles
        // $adminRole = Role::create(['name' => 'super admin']);
        // $userRole = Role::create(['name' => 'student']);

        // // Assign all permissions to admin role
        // $adminRole->givePermissionTo($permissions);

        // // Assign limited permissions to user role
        // $userRole->givePermissionTo([
        //     'view.students',
        //     'view.roles',
        //     'view.permissions'
        // ]);
    }
}
