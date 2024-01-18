<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Notavailability;
use App\Models\Worker;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Notavailability::class, function (Faker $faker) {
    return [
        'id' => Str::uuid(),
        'worker_id' => function () {
            factory(Worker::class)->create()->id;
        },
        'specific_dates' => $faker->date($format = 'Y-m-d', $max = 'now')
    ];
});
