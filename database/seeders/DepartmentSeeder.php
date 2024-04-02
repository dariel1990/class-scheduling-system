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
                'short_name'                => 'DCS',
                'description'               => 'Department of Computer Studies',
                'program_head'              => 'NELYNE LOURDES Y.PLAZA, Ph.D.',
                'program_head_position'     => 'Dept. of Computer Studies Chair',
                'college_dean'              => 'BORN CHRISTIAN A. ISIP, DTE',
                'college_dean_position'     => 'Dean, CITE',
            ],
        ];

        foreach ($departments as $key => $value) {
            $departments = Departments::firstOrCreate([
                'short_name'                => $value['short_name'],
                'description'               => $value['description'],
                'program_head'              => $value['program_head'],
                'program_head_position'     => $value['program_head_position'],
                'college_dean'              => $value['college_dean'],
                'college_dean_position'     => $value['college_dean_position'],
            ]);
        }
    }
}
