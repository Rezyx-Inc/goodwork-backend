<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
			'id' => Str::uuid(),
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'fulladmin@nurseify.io',
            'user_name' => 'fulladmin@nurseify.io',
			'password' => Hash::make('IMC_2020_Ankur'),
            'mobile' => '1234657890'
        ]);

        $employer = User::create([
			'id' => Str::uuid(),
            'first_name' => 'employer',
            'last_name' => 'emp',
            'email' => 'employer@gmail.com',
            'ROLE'=>'EMPLOYER',
            'user_name' => 'employer',
            'mobile' => '+1 (555) 555-55',
            'facility_id' => '1'
        ]);

       // $admin->assignRole('Administrator');
    }
}
