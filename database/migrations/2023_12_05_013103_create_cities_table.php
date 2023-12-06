<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name'); // Name of the city
            $table->bigInteger('state_id')->length(20); // ID referencing the state to which the city belongs
            $table->string('state_code'); // Code for the state
            $table->bigInteger('country_id')->length(20); // ID referencing the country to which the city belongs
            $table->string('country_code'); // Code for the country
            $table->float('latitude', 8, 6); // Latitude coordinate of the city
            $table->float('longitude', 9, 6); // Longitude coordinate of the city
            $table->date('deleted_at')->nullable(); // Column for start date
            // Foreign key constraints
            // $table->foreign('state_id')->references('id')->on('states');
            // $table->foreign('country_id')->references('id')->on('countries');

            $table->timestamps(); // Created_at and updated_at timestamps

             // Indexes for state_id and country_id columns for better query performance

             $table->index(['state_id']);
             $table->index(['country_id']);
        });

       
   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
