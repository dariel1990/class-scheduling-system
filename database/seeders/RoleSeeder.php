<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminPermissions = [
            'dashboard',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'period-list',
            'period-create',
            'period-edit',
            'period-delete',
            'class-list',
            'class-create',
            'class-edit',
            'class-delete',
            'department-list',
            'department-create',
            'department-edit',
            'department-delete',
            'faculty-list',
            'faculty-create',
            'faculty-edit',
            'faculty-delete',
            'subject-list',
            'subject-create',
            'subject-edit',
            'subject-delete',
            'dashboard',
            'settings-list',
            'settings-update'
        ];
        foreach ($adminPermissions as $permission) {
            $adminRole->givePermissionTo($permission);
        }

        $facultyRole = Role::firstOrCreate(['name' => 'Faculty']);
    }
}
