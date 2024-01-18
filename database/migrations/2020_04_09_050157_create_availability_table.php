<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvailabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('availability', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('worker_id');
            $table->foreign('worker_id')
                ->references('id')->on('workers');
            $table->unsignedBigInteger('assignment_duration')->nullable();
            $table->unsignedBigInteger('shift_duration')->nullable();
            $table->string("days_of_the_week")->nullable();
            $table->unsignedBigInteger('work_location')->nullable();
            $table->unsignedBigInteger('preferred_shift')->nullable();
            $table->date('earliest_start_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('availability');
    }
}
