<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            [
                'name' => 'Alabama',
                'country_id' => 1, // Replace with the actual country ID for the US
                'fips_code' => 'US01',
                'iso2' => 'AL',
                'type' => 'State',
                'latitude' => 32.806671,
                'longitude' => -86.791130,
                'created_at' => now(),
                'updated_at' => now(),
                'wikiDataId' => 'Q1738',
                'deleted_at' => null,
            ],
            // Add more states in a similar format
            [
                'name' => 'Wyoming',
                'country_id' => 1, // Replace with the actual country ID for the US
                'fips_code' => 'US56',
                'iso2' => 'WY',
                'type' => 'State',
                'latitude' => 43.075970,
                'longitude' => -107.290283,
                'created_at' => now(),
                'updated_at' => now(),
                'wikiDataId' => 'Q1214',
                'deleted_at' => null,
            ],
        ];

        // Inserting states into the database
        DB::table('states')->insert($states);
    }
}
