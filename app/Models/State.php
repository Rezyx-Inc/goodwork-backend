<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    /**
     * Fillable fields for the State model.
     *
     * @var array
     */
    protected $fillable = [
        'id',             // Unique identifier for the state
        'name',           // Name of the state
        'country_id',     // Foreign key referencing the country to which the state belongs
        'country_code',   // Code for the country to which the state belongs
        'fips_code',      // FIPS code of the state
        'iso2',           // ISO 2-letter code of the state
        'type',           // Type or classification of the state
        'latitude',       // Latitude coordinate of the state
        'longitude',      // Longitude coordinate of the state
        'created_at',     // Timestamp for the creation of the state record
        'updated_at',     // Timestamp for the update of the state record
        
    ];
}
