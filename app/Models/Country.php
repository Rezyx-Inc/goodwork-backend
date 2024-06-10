<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    // Specify the fillable attributes that can be mass-assigned
    protected $fillable = [
        'id',               // Unique identifier for the country
        'name',             // Name of the country
        'iso3',             // ISO 3-letter code of the country
        'numeric_code',     // Numeric code of the country
        'iso2',             // ISO 2-letter code of the country
        'phonecode',        // Phone code of the country
        'capital',          // Capital city of the country
        'currency',         // Currency used in the country
        'currency_symbol',  // Symbol representing the country's currency
        'tld',              // Top-level domain of the country
        'native',           // Native name of the country
        'region',           // Region where the country belongs
        'subregion',        // Subregion where the country belongs
        'timezones',        // Time zones associated with the country
        'translations',     // Translations or language variations for the country name
        'latitude',         // Latitude coordinate of the country
        'longitude',        // Longitude coordinate of the country
        'emoji',            // Emoji representation of the country
        'emojiU',           // Unicode representation of the emoji
        'created_at',       // Date and time when the record was created
        'updated_at',       // Date and time when the record was last updated
        'flag',             // Flag of the country (image or reference)
        
    ];

    
}
