<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();  // Unique identifier for the country
            $table->string('name');         // Name of the country
            $table->string('iso3');         // ISO 3-letter code of the country
            $table->integer('numeric_code');// Numeric code of the country
            $table->string('iso2');         // ISO 2-letter code of the country
            $table->string('phonecode');    // Phone code of the country
            $table->string('capital');      // Capital city of the country
            $table->string('currency');     // Currency used in the country
            $table->string('currency_symbol'); // Symbol representing the country's currency
            $table->string('tld');          // Top-level domain of the country
            $table->string('native');       // Native name of the country
            $table->string('region');       // Region where the country belongs
            $table->string('subregion');    // Subregion where the country belongs
            $table->json('timezones');      // Time zones associated with the country (stored as JSON)
            $table->json('translations');   // Translations or language variations for the country name (stored as JSON)
            $table->float('latitude', 8, 6); // Latitude coordinate of the country
            $table->float('longitude', 9, 6); // Longitude coordinate of the country
            $table->string('emoji');        // Emoji representation of the country
            $table->string('emojiU');       // Unicode representation of the emoji
            $table->string('flag');         // Flag of the country (image or reference)
            $table->string('wikiDataId');   // Identifier for the country in WikiData
            $table->date('deleted_at')->nullable(); 
            $table->timestamps();           // Timestamps for created_at and updated_at fields
        });
    }

    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
