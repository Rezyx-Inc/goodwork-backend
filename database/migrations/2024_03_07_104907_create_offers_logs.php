<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('original_offer_id');
            $table->foreign('original_offer_id')->references('id')->on('offers');
            $table->enum('counter_offer_by', ['Nurse', 'Employer']);
            $table->uuid('nurse_id');
            $table->foreign('nurse_id')->references('id')->on('nurses');
            $table->uuid('employer_recruiter_id')->nullable();
            $table->foreign('employer_recruiter_id')->references('id')->on('users');
            // $table->uuid('job_id');
            // $table->foreign('job_id')->references('id')->on('jobs');
            $table->text('details');
            $table->enum('status', ['Pending', 'Accepted', 'Declined', 'Counter'])->default('Pending');
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
        Schema::dropIfExists('offers_logs');
    }
}
