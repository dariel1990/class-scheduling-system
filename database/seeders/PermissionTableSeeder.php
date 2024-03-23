<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Permissions
        $permissions = [
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
            'student-read',
            'student-create',
            'student-update',
            'student-delete',
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

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
