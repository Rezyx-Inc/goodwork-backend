<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key

            $table->string('name'); // Name of the state
            $table->bigInteger('country_id')->length(20); // Code for the country to which the state belongs
            $table->string('fips_code')->nullable(); // FIPS code of the state (nullable)
            $table->string('iso2'); // ISO 2-letter code of the state
            $table->string('type')->nullable(); // Type or classification of the state (nullable)
            $table->float('latitude', 8, 6)->nullable(); // Latitude coordinate of the state (nullable)
            $table->float('longitude', 9, 6)->nullable(); // Longitude coordinate of the state (nullable)
            $table->timestamps(); // Timestamps for created_at and updated_at fields
            $table->string('wikiDataId')->nullable(); // Identifier for the state in WikiData (nullable)
            $table->date('deleted_at')->nullable(); // Column for start date
            // $table->foreign('country_code')
            // ->references('id')->on('countries');  // Foreign key referencing the country to which the state belongs

            // Indexes
         //   $table->index(['country_id']); // Index for the foreign key column country_id
        });
    }

    public function down()
    {
        Schema::dropIfExists('states');
    }
}
