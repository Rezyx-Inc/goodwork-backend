<?php

use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   

        $states = DB::table('states')->get();

        foreach ($states as $state) {
            
            echo 'Loading ' . $state->iso2 . PHP_EOL;
            
            $citiesJson = File::get('database/data/' . $state->iso2 . '.json');
            $citiesDecoded = json_decode($citiesJson);

            $cities = array();

            foreach ($citiesDecoded as $tempCity) {
                
                $city = [
                    'name' => $tempCity->name,
                    'state_id' => $state->id,
                    'state_code' => $state->iso2,
                    'country_id' => '1',
                    'latitude' => $tempCity->location->latitude,
                    'longitude' => $tempCity->location->longitude,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null
                ];

                array_push($cities, $city);
            }

            // Inserting states into the database
            DB::table('cities')->insert($cities);
        }
    }
}
