<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            // only job name is required since in the job saving process no required fields are required

            $table->uuid('id')->primary();
            // type of speciallity changed from unsignedBigInteger to string since we have no relation between specialities and jobs table and we need the name of speciality in jobs table
            $table->string('preferred_specialty')->nullable();
            $table->unsignedBigInteger('preferred_assignment_duration')->nullable();
            $table->unsignedBigInteger('preferred_shift_duration')->nullable();
            $table->string('preferred_work_location')->nullable();
            $table->unsignedBigInteger('preferred_work_area')->nullable();
            $table->string("preferred_days_of_the_week")->nullable();
            $table->string('preferred_hourly_pay_rate',4)->nullable();
            $table->string('preferred_experience',10)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->uuid('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')->on('users');
            $table->softDeletes();
            $table->text('slug')->nullable();
            $table->boolean('active')->default(true);
            $table->uuid('facility_id')->nullable();
            $table->foreign('facility_id')
                ->references('id')->on('facilities');
                $table->string('job_video')->nullable();
                $table->unsignedBigInteger('seniority_level')->nullable();
                $table->unsignedBigInteger('job_function')->nullable();
                $table->text('description')->nullable()->change();
                $table->text('responsibilities')->nullable();
                $table->text('qualifications')->nullable();
                $table->unsignedBigInteger('job_cerner_exp')->nullable();
                $table->unsignedBigInteger('job_meditech_exp')->nullable();
                $table->unsignedBigInteger('job_epic_exp')->nullable();
                $table->string('job_other_exp',100)->nullable();
                $table->text('job_photos')->nullable();
                $table->string('video_embed_url')->nullable();
                $table->boolean('is_open')->default(true);
                $table->uuid('recruiter_id')->nullable();
                $table->string('job_name', 36);

                // Adding string columns as nullable
            $table->string('proffesion')->nullable(); // Column for job profession
            $table->string('preferred_shift')->nullable(); // Column for preferred shift
            $table->string('job_city')->nullable(); // Column for job city
            $table->string('job_state')->nullable(); // Column for job state
            $table->string('job_type')->nullable(); // Column for job type

            // Adding nullable float column
            $table->float('weekly_pay', 8, 2)->nullable(); // Column for weekly pay

            // Adding nullable date columns
            $table->date('start_date')->nullable(); // Column for start date
            $table->date('end_date')->nullable(); // Column for end date

            // Adding integer columns as nullable
            $table->integer('hours_shift')->nullable(); // Column for hours per shift
            $table->integer('hours_per_week')->nullable(); // Column for hours per week

            // Adding integer column with default value
            $table->integer('auto_offers')->default(0); // Column for auto offers with default value of 0

            // Adding boolean columns with default values
            $table->boolean('is_hidden')->default(false); // Column to indicate if job is hidden, default value is false
            $table->boolean('is_closed')->default(false); // Column to indicate if job is closed, default value is false
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
