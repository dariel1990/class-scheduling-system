<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Admin Seeder
        $user = User::create([
            'username'      => 'admin',
            'password'      => bcrypt('password'),
            'email'         => 'nemsulc@nemsu.edu.ph',
        ]);

        $role = Role::where('name', 'Admin')->first();

        $user->assignRole([$role->id]);
    }
}
