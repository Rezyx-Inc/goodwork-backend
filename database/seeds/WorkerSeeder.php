<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Enums\Role;
use App\Models\User;
use App\Models\Worker;
use App\Models\Availability;
use Illuminate\Support\Str;

class WorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create([
			'first_name' => 'Michael',
            'last_name' => 'Nicolas',
            'email' => 'info@goodwork.io',
            'user_name' => 'info@goodwork.io',
			'password' => Hash::make('password'),
			'role' => Role::getKey(Role::WORKER),
			'mobile' => '9879510798'
        ]);
        $user->assignRole('Worker');
        
        $worker = factory(Worker::class)->create([
            'user_id' => $user->id,
            'slug' => Str::slug($user->first_name.' '.$user->last_name.' '.Str::uuid())        
        ]);

        $availability = Availability::create([
            'worker_id' => $worker->id,
            'work_location' => 38,
        ]);
    }
}
