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

        // Admin is the first User created in the system, and it has all permissions, its id is "GWU000001"

        $admin = User::create([
			'id' => Str::uuid(),
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'fulladmin@nurseify.io',
            'user_name' => 'fulladmin@nurseify.io',
			'password' => Hash::make('IMC_2020_Ankur'),
            'mobile' => '1234657890'
        ]);

        // we seed this employer with the id "GWU000002" just for testing jobs, this record related to default seeding jobs

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

        // we seed this workers with the id "GWU000003" & "GWU000004" just for testing messages, this record related to default seeding messages


        $worker1 = User::create([
            'id' => Str::uuid(),
            'first_name' => 'worker',
            'last_name' => 'one',
            'email' => 'worker1@gmail.com',
            'ROLE'=>'WORKER',
            'user_name' => 'worker1',
            'mobile' => '+1 (555) 555-55',
            'facility_id' => '1'
        ]);

        $worker2 = User::create([
            'id' => Str::uuid(),
            'first_name' => 'worker',
            'last_name' => 'two',
            'email' => 'worker2@gmail.com',
            'ROLE'=>'WORKER',
            'user_name' => 'worker2',
            'mobile' => '+1 (555) 555-55',
            'facility_id' => '1'
        ]);

    
    }
}
