<?php

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountriesTableSeeder extends Seeder
{
    public function run()
    {
        // Data for 5 example countries
        $countries = [
            [
                'name' => 'United States',
                'iso3' => 'USA',
                'numeric_code' => 840,
                'iso2' => 'US',
                'phonecode' => '+1',
                'capital' => 'Washington, D.C.',
                'currency' => 'USD',
                'currency_symbol' => '$',
                'tld' => '.us',
                'native' => 'United States',
                'region' => 'Americas',
                'subregion' => 'Northern America',
                'timezones' => '["UTC-12:00", "UTC-11:00"]', // Insert actual timezone data as JSON
                'translations' => '{"de":"Vereinigte Staaten von Amerika"}', // Actual translations in JSON
                'latitude' => 37.09024,
                'longitude' => -95.712891,
                'emoji' => '🇺🇸',
                'emojiU' => 'U+1F1FA U+1F1F8',
                'flag' => 'https://example.com/us-flag.png', // URL to the country's flag image
                'wikiDataId' => 'Q30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Canada',
                'iso3' => 'CAN',
                'numeric_code' => 124,
                'iso2' => 'CA',
                'phonecode' => '+1',
                'capital' => 'Ottawa',
                'currency' => 'CAD',
                'currency_symbol' => '$',
                'tld' => '.ca',
                'native' => 'Canada',
                'region' => 'Americas',
                'subregion' => 'Northern America',
                'timezones' => '["UTC-08:00", "UTC-07:00", "UTC-06:00", "UTC-05:00", "UTC-04:00", "UTC-03:30"]',
                'translations' => '{"de":"Kanada"}',
                'latitude' => 56.130366,
                'longitude' => -106.346771,
                'emoji' => '🇨🇦',
                'emojiU' => 'U+1F1E8 U+1F1E6',
                'flag' => 'https://example.com/ca-flag.png',
                'wikiDataId' => 'Q16',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [

                    'name' => 'Germany',
                    'iso3' => 'DEU',
                    'numeric_code' => 276,
                    'iso2' => 'DE',
                    'phonecode' => '+49',
                    'capital' => 'Berlin',
                    'currency' => 'EUR',
                    'currency_symbol' => '€',
                    'tld' => '.de',
                    'native' => 'Deutschland',
                    'region' => 'Europe',
                    'subregion' => 'Western Europe',
                    'timezones' => '["UTC+01:00", "UTC+02:00"]',
                    'translations' => '{"fr":"Allemagne", "es":"Alemania", "it":"Germania"}',
                    'latitude' => 51.165691,
                    'longitude' => 10.451526,
                    'emoji' => '🇩🇪',
                    'emojiU' => 'U+1F1E9 U+1F1EA',
                    'flag' => 'https://example.com/de-flag.png',
                    'wikiDataId' => 'Q183',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'France',
                    'iso3' => 'FRA',
                    'numeric_code' => 250,
                    'iso2' => 'FR',
                    'phonecode' => '+33',
                    'capital' => 'Paris',
                    'currency' => 'EUR',
                    'currency_symbol' => '€',
                    'tld' => '.fr',
                    'native' => 'France',
                    'region' => 'Europe',
                    'subregion' => 'Western Europe',
                    'timezones' => '["UTC-10:00", "UTC-09:30", "UTC-09:00", "UTC-08:00", "UTC+01:00", "UTC+02:00"]',
                    'translations' => '{"de":"Frankreich", "es":"Francia", "it":"Francia"}',
                    'latitude' => 46.603354,
                    'longitude' => 1.888334,
                    'emoji' => '🇫🇷',
                    'emojiU' => 'U+1F1EB U+1F1F7',
                    'flag' => 'https://example.com/fr-flag.png',
                    'wikiDataId' => 'Q142',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Add more Western European countries following a similar structure...
            ];

            // Add data for other countries here
       

        // Insert data into the countries table using Eloquent ORM
        foreach ($countries as $countryData) {
            Country::create($countryData);
        }
    }
}
