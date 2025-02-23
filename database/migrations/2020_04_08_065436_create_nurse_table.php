<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNurseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nurses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')
            ->references('id')->on('users');
            $table->string('nursing_license_state')->nullable();
            $table->string('specialty', 100)->nullable();
            $table->string('nursing_license_number', 190)->unique()->nullable();
            $table->string('highest_nursing_degree')->nullable();
            $table->boolean('serving_preceptor')->default(false)->nullable();
            $table->boolean('serving_interim_nurse_leader')->default(false)->nullable();
            $table->unsignedBigInteger('leadership_roles')->nullable();
            $table->string('address')->nullable();
            $table->string('city', 50)->nullable();
            $table->string("state")->nullable();
            $table->string('postcode', 15)->nullable();
            $table->string('country', 150)->nullable();
            $table->string('hourly_pay_rate')->nullable();
            $table->decimal('experience_as_acute_care_facility', 6, 2)->nullable();
            $table->decimal('experience_as_ambulatory_care_facility', 6, 2)->nullable();
            $table->boolean('active')->default(true);
            $table->softDeletes();
            $table->timestamps();


            // fix nurse table
            $table->unsignedBigInteger('ehr_proficiency_cerner')->nullable();
            $table->unsignedBigInteger('ehr_proficiency_meditech')->nullable();
            $table->unsignedBigInteger('ehr_proficiency_epic')->nullable();
            $table->string('ehr_proficiency_other', 100)->nullable();

            $table->text('summary')->nullable();

            $table->string('nurses_video')->nullable();
            $table->string('nurses_facebook')->nullable();
            $table->string('nurses_twitter')->nullable();
            $table->string('nurses_linkedin')->nullable();
            $table->string('nurses_instagram')->nullable();
            $table->string('nurses_pinterest')->nullable();
            $table->string('nurses_tiktok')->nullable();
            $table->string('nurses_sanpchat')->nullable();
            $table->string('nurses_youtube')->nullable();

            $table->boolean('clinical_educator')->default(false);
            $table->boolean('is_daisy_award_winner')->default(false);
            $table->boolean('employee_of_the_mth_qtr_yr')->default(false);
            $table->boolean('other_nursing_awards')->default(false);
            $table->boolean('is_professional_practice_council')->default(false);
            $table->boolean('is_research_publications')->default(false);


            $table->string('mu_specialty', 50)->nullable();




            $table->string('languages')->default('English');


            $table->text('additional_photos')->nullable();
            $table->text('additional_files')->nullable();
            $table->string('college_uni_name')->nullable();
            $table->string('college_uni_city', 50)->nullable();
            $table->enum("college_uni_state", \App\Enums\State::getKeys())->nullable();
            $table->string('college_uni_country', 150)->nullable();
            $table->string('facility_hourly_pay_rate')->nullable();

            $table->string('resume')->nullable();
            $table->string('nu_video')->nullable();
            $table->string('nu_video_embed_url')->nullable();
            $table->boolean('is_verified')->default(false);

            $table->text('gig_account_id')->nullable();
            $table->boolean('is_gig_invite')->default(false);
            $table->dateTime('gig_account_create_date')->nullable();
            $table->dateTime('gig_account_invite_date')->nullable();

            // added new fields

            $table->string('diploma')->nullable();
            $table->string('driving_license')->nullable();
            $table->string('worked_at_facility_before')->nullable();
            $table->string('worker_ss_number')->nullable();
            $table->boolean('block_scheduling')->nullable();
            $table->boolean('float_requirement')->default(false);
            $table->string('facility_shift_cancelation_policy')->nullable();
            $table->string('contract_termination_policy')->nullable();
            $table->string('distance_from_your_home', 40)->nullable();
            $table->string('clinical_setting_you_prefer')->nullable();
            $table->decimal('worker_patient_ratio', 8, 2)->nullable();
            $table->string('worker_emr')->nullable();
            $table->string('worker_unit')->nullable();
            $table->string('worker_scrub_color')->nullable();
            $table->string('worker_interview_dates')->nullable();
            $table->string('worker_start_date', 40)->nullable();
            $table->boolean('worker_as_soon_as_possible')->default(false);
            $table->string('worker_shift_time_of_day')->nullable();
            $table->integer('worker_hours_per_week')->nullable();
            $table->decimal('worker_guaranteed_hours', 8, 2)->nullable();
            $table->unsignedBigInteger('worker_weeks_assignment')->nullable(); // match the preferred_assignment_duration on jobs
            $table->decimal('worker_shifts_week', 8, 2)->nullable();
            $table->decimal('worker_referral_bonus', 8, 2)->nullable();
            $table->decimal('worker_sign_on_bonus', 8, 2)->nullable();
            $table->decimal('worker_completion_bonus', 8, 2)->nullable();
            $table->decimal('worker_extension_bonus', 8, 2)->nullable();
            $table->decimal('worker_other_bonus', 8, 2)->nullable();
            $table->string('how_much_k')->nullable();
            $table->boolean('worker_four_zero_one_k')->default(false);
            $table->boolean('worker_health_insurance')->nullable();
            $table->boolean('worker_dental')->nullable();
            $table->boolean('worker_vision')->nullable();
            $table->decimal('worker_actual_hourly_rate', 8, 2)->nullable();
            $table->decimal('worker_overtime', 8, 2)->nullable();
            $table->date('worker_holiday')->nullable();
            $table->decimal('worker_on_call', 8, 2)->nullable();
            $table->decimal('worker_call_back', 8, 2)->nullable();
            $table->decimal('worker_orientation_rate', 8, 2)->nullable();
            $table->decimal('worker_weekly_taxable_amount', 8, 2)->nullable();
            $table->decimal('worker_organization_weekly_amount', 8, 2)->nullable();
            $table->decimal('worker_weekly_non_taxable_amount', 8, 2)->nullable();
            // add new fields
            $table->string('rto', 50)->nullable();
            $table->string('profession', 50)->nullable();
            // add tier field (from 0 to 3 (min 3 documents for T3))
            $table->unsignedTinyInteger('account_tier')->default(0);
            // add missing fields for the profile nurses (workers)
            $table->string('terms')->deault('');
            $table->string('worker_job_type')->deault('');
            $table->decimal('worker_hours_shift', 8, 2);
            // payment information
            $table->string('full_name_payment', 100)->nullable();
            $table->string('address_payment', 100)->nullable();
            $table->string('email_payment', 100)->nullable();
            $table->string('bank_name_payment', 100)->nullable();
            $table->string('routing_number_payment', 40)->nullable();
            $table->string('bank_account_payment_number', 40)->nullable();
            $table->string('phone_number_payment', 40)->nullable();

            // added fields from sheets
            $table->string('worker_urgency')->nullable();
            $table->string('skills_checklists')->nullable();
            $table->string('worker_facilitys_parent_system')->nullable();
            $table->string('worker_vaccination')->nullable(); // done
            $table->string('worker_certificate_name')->nullable();
            $table->boolean('worker_eligible_work_in_us')->default(false);
            $table->decimal('worker_feels_like_per_hour', 8, 2)->nullable(); // done

            $table->string('worker_facility_city', 36)->nullable(); // done
            $table->string('worker_facility_state', 36)->nullable(); // done
            $table->boolean('worker_feels_like_per_hour_check')->default(false); // done
            $table->string('worker_overtime_rate', 36)->nullable(); // done
            $table->boolean('worker_on_call_check')->default(false); // done
            $table->decimal('worker_weekly_non_taxable_amount_check', 8, 2)->nullable(); // done
            $table->boolean('worker_call_back_check')->default(false); // done

            // Experience
            $table->integer('worker_experience')->nullable();

            // adding benifits
            $table->unsignedTinyInteger('worker_benefits')->default(0);

            // adding worker classification
            $table->string('nurse_classification')->nullable();






        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nurses');
    }
}
