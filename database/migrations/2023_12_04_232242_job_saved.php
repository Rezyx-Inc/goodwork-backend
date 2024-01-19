<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class JobSaved extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_saved', function (Blueprint $table) {
        $table->id();
        $table->string('job_id', 36);
        $table->string('nurse_id', 36);
        $table->boolean('is_delete')->default(false);
        $table->boolean('is_save')->default(true);
        $table->timestamps();
        $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        $table->foreign('nurse_id')->references('id')->on('nurses')->onDelete('cascade');

        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_saved');
    }
}
