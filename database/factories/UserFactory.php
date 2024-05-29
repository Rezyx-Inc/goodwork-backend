<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;
use App\Enums\Role;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'role' => Role::getKey(Role::FULLADMIN),
            // 'first_name' => $faker->firstName,
            // 'last_name' => $faker->lastName,
            'first_name' => $this->faker->fantasyName('first_name'),
            'last_name' => $this->faker->fantasyName('last_name'),
            'image' => null,
            'email' => $this->faker->unique()->safeEmail,
            'user_name' => $this->faker->userName,
            'password' => Hash::make('password'), // password
            'date_of_birth' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'mobile' => $this->faker->phoneNumber,
            'email_notification' => true,
            'sms_notification' => true,
            'active' => true,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }
}
