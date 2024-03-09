<?php

namespace Database\Seeders;

use App\Models\Departments;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'short_name'         => 'CITE',
                'description'        => 'College of Information Technology Eduction',
                'program_head'       => 'NONE',
            ],
            [
                'short_name'         => 'CTE',
                'description'        => 'College of Teacher Education',
                'program_head'       => 'NONE',
            ],
            [
                'short_name'         => 'CBM',
                'description'        => 'College of Business Management',
                'program_head'       => 'NONE',
            ],
        ];

        foreach ($departments as $key => $value) {
            $departments = Departments::firstOrCreate([
                'short_name'         => $value['short_name'],
                'description'        => $value['description'],
                'program_head'       => $value['program_head'],
            ]);
        }
    }
}
