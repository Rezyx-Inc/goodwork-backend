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
            $table->string('job_id')->nullable();
            // type of speciallity changed from unsignedBigInteger to string since we have no relation between specialities and jobs table and we need the name of speciality in jobs table
            $table->string('preferred_specialty')->nullable();
            $table->string('import_id')->nullable();
            $table->unsignedBigInteger('preferred_assignment_duration')->nullable();
            $table->string('preferred_shift_duration')->nullable();
            $table->string('preferred_work_location')->nullable();
            $table->unsignedBigInteger('preferred_work_area')->nullable();
            $table->string("preferred_days_of_the_week")->nullable();
            $table->string('preferred_hourly_pay_rate', 4)->nullable();
            $table->integer('preferred_experience')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->uuid('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->softDeletes();
            $table->text('slug')->nullable();
            $table->boolean('active')->default(true);
            $table->uuid('facility_id')->nullable();
            $table->foreign('facility_id')->references('id')->on('facilities');
            $table->string('job_video')->nullable();
            $table->unsignedBigInteger('seniority_level')->nullable();
            $table->unsignedBigInteger('job_function')->nullable();
            $table->text('description')->nullable()->change();
            $table->text('responsibilities')->nullable();
            $table->text('qualifications')->nullable();
            $table->unsignedBigInteger('job_cerner_exp')->nullable();
            $table->unsignedBigInteger('job_meditech_exp')->nullable();
            $table->unsignedBigInteger('job_epic_exp')->nullable();
            $table->string('job_other_exp', 100)->nullable();
            $table->text('job_photos')->nullable();
            $table->string('video_embed_url')->nullable();
            $table->boolean('is_open')->default(true);
            $table->uuid('recruiter_id')->nullable();
            $table->uuid('organization_id')->nullable();
            $table->string('job_name', 36)->nullable();

            // Adding string columns as nullable
            $table->string('profession')->nullable(); // Column for job profession
            $table->string('preferred_shift')->nullable(); // Column for preferred shift
            $table->string('job_city')->nullable(); // Column for job city
            $table->string('job_state')->nullable(); // Column for job state
            $table->string('job_type')->nullable(); // Column for job type

            // Adding nullable float column
            $table->float('weekly_pay', 8, 2)->nullable(); // Column for weekly pay

            // Adding nullable date columns
            $table->date('start_date')->nullable(); // Column for start date
            $table->date('end_date')->nullable(); // Column for end date
            $table->boolean('as_soon_as')->default(false); // Column for as_soon_as

            // Adding integer columns as nullable
            $table->integer('hours_shift')->nullable(); // Column for hours per shift
            $table->integer('hours_per_week')->nullable(); // Column for hours per week

            // Adding integer column with default value
            $table->integer('auto_offers')->default(0); // Column for auto offers with default value of 0

            // Adding boolean columns with default values
            $table->boolean('is_hidden')->default(false); // Column to indicate if job is hidden, default value is false
            $table->boolean('is_closed')->default(false); // Column to indicate if job is closed, default value is false


            // new fields
            $table->string('highest_nursing_degree')->nullable();
            $table->string('specialty')->nullable();
            $table->boolean('block_scheduling')->nullable();
            $table->boolean('float_requirement')->default(false);
            $table->string('facility_shift_cancelation_policy')->nullable();
            $table->string('contract_termination_policy')->nullable();
            $table->string('traveler_distance_from_facility')->nullable();
            $table->string('facility')->nullable();
            $table->string('clinical_setting')->nullable();
            $table->string('clinical_setting_you_prefer')->nullable();
            $table->decimal('Patient_ratio', 8, 2)->nullable();
            $table->string('Emr')->nullable();
            $table->string('Unit')->nullable();
            $table->string('scrub_color')->nullable();
            $table->string('rto')->nullable();
            $table->decimal('guaranteed_hours', 8, 2)->nullable();
            $table->decimal('weeks_shift', 8, 2)->nullable();
            $table->decimal('referral_bonus', 8, 2)->nullable();
            $table->decimal('sign_on_bonus', 8, 2)->nullable();
            $table->decimal('completion_bonus', 8, 2)->nullable();
            $table->decimal('extension_bonus', 8, 2)->nullable();
            $table->decimal('other_bonus', 8, 2)->nullable();
            $table->boolean('four_zero_one_k')->default(false);
            $table->boolean('health_insaurance')->default(false);
            $table->boolean('dental')->default(false);
            $table->boolean('vision')->default(false);
            $table->decimal('actual_hourly_rate', 8, 2)->nullable();
            $table->decimal('overtime', 8, 2)->nullable();
            $table->date('holiday')->nullable();
            // call backs
            $table->boolean('on_call')->default(false);
            $table->decimal('on_call_rate', 8, 2)->nullable();
            $table->decimal('call_back_rate', 8, 2)->nullable();
            // end call backs
            $table->decimal('orientation_rate', 8, 2)->nullable();
            $table->decimal('weekly_taxable_amount', 8, 2)->nullable();
            $table->decimal('organization_weekly_amount', 8, 2)->nullable();
            $table->decimal('weekly_non_taxable_amount', 8, 2)->nullable();
            $table->decimal('total_organization_amount', 8, 2)->nullable();
            $table->decimal('total_goodwork_amount', 8, 2)->nullable();
            $table->decimal('total_contract_amount', 8, 2)->nullable();
            $table->decimal('goodwork_weekly_amount', 8, 2)->nullable();
            $table->string('tax_status', 36);
            $table->string('terms');
            $table->string('type')->nullable();
            // Adding new columns (from docs)
            //not required
            $table->string('job_location')->nullable();  // done
            $table->string('vaccinations')->nullable(); // done
            $table->integer('number_of_references')->nullable(); // done
            $table->string('min_title_of_reference')->nullable(); // done
            $table->boolean('eligible_work_in_us')->default(false); // done
            $table->integer('recency_of_reference')->nullable(); // done
            $table->string('certificate')->nullable(); // done
            $table->string('skills')->nullable();  // done
            $table->string('urgency')->nullable(); // need to be in card job // done
            $table->string('facilitys_parent_system')->nullable(); // done
            $table->string('facility_name')->nullable(); // done
            $table->string('facility_location')->nullable(); // done

            $table->string('nurse_classification')->nullable(); // done
            $table->string('pay_frequency')->nullable(); // need to be in card job // done
            $table->string('benefits')->nullable(); // done
            $table->decimal('feels_like_per_hour', 8, 2)->nullable(); // done
            // required
            $table->string('facility_city', 36); // done
            $table->string('facility_state', 36); // done

            // professionalLicensure
            $table->string('professional_licensure', 36);
            $table->string('professional_state_licensure');


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
