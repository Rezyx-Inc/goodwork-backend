<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')->on('users');
            $table->string('name');
            // dropped
           // $table->string('image')->nullable();
            $table->string('address')->nullable();
            $table->string('city', 50)->nullable();
            $table->enum("state", \App\Enums\State::getKeys())->nullable();
            $table->string('postcode', 15)->nullable();
            $table->unsignedBigInteger('type')->nullable();
            $table->boolean('active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->string('facility_logo')->nullable();
            $table->string('facility_email')->nullable();
            $table->string('facility_phone',20)->nullable();
            $table->string('specialty_need')->nullable();
            $table->text('slug')->nullable();
            $table->text('cno_message')->nullable();
            $table->string('cno_image')->nullable();
            $table->string('gallary_images')->nullable();
            $table->string('video')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('pinterest')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('sanpchat')->nullable();
            $table->string('youtube')->nullable();
            $table->text('about_facility')->nullable();
            $table->string('facility_website')->nullable();
            $table->string('video_embed_url')->nullable();
            $table->string('f_emr',150)->nullable();
            $table->string('f_emr_other',150)->nullable();
            $table->string('f_bcheck_provider',150)->nullable();
            $table->string('f_bcheck_provider_other',150)->nullable();
            $table->string('worker_cred_soft',150)->nullable();
            $table->string('worker_cred_soft_other',150)->nullable();
            $table->string('worker_scheduling_sys',150)->nullable();
            $table->string('worker_scheduling_sys_other',150)->nullable();
            $table->string('time_attend_sys',150)->nullable();
            $table->string('time_attend_sys_other',150)->nullable();
            $table->string('licensed_beds',50)->nullable();
            $table->string('trauma_designation',150)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facilities');
    }
}
