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
            'dashboard-read',
            'role-read',
            'role-create',
            'role-update',
            'role-delete',
            'user-read',
            'user-create',
            'user-update',
            'user-delete',
            'period-read',
            'period-create',
            'period-update',
            'period-delete',
            'class-read',
            'class-create',
            'class-update',
            'class-delete',
            'department-read',
            'department-create',
            'department-update',
            'department-delete',
            'faculty-read',
            'faculty-create',
            'faculty-update',
            'faculty-delete',
            'subject-read',
            'subject-create',
            'subject-update',
            'subject-delete',
            'settings-read',
            'settings-update',
            'student-read',
            'student-create',
            'student-update',
            'student-delete',
            'student-import',
        ];
        foreach ($adminPermissions as $permission) {
            $adminRole->givePermissionTo($permission);
        }

        $facultyRole = Role::firstOrCreate(['name' => 'Faculty']);
        $facultyRole = Role::firstOrCreate(['name' => 'Student']);
    }
}
