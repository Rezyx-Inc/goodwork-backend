<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('job_name');
            $table->string('status');
            $table->string('type');
            $table->string('terms');
            $table->string('specialty');
            $table->string('profession');
            $table->boolean('block_scheduling')->nullable();
            $table->boolean('float_requirement');
            $table->string('facility_shift_cancelation_policy');
            $table->string('contract_termination_policy');
            $table->string('traveler_distance_from_facility');
            $table->string('job_id', 36);
            $table->string('recruiter_id', 36);
            $table->string('worker_user_id', 36);
            $table->string('clinical_setting');
            $table->decimal('Patient_ratio', 8, 2);
            $table->string('emr');
            $table->string('Unit');
            $table->string('scrub_color');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('as_soon_as')->nullable();
            $table->string('rto');
            $table->decimal('hours_per_week', 8, 2);
            $table->decimal('guaranteed_hours', 8, 2);
            $table->decimal('hours_shift', 8, 2);
            $table->decimal('weeks_shift', 8, 2);
            $table->decimal('preferred_assignment_duration', 8, 2)->nullable();
            $table->decimal('referral_bonus', 8, 2);
            $table->decimal('sign_on_bonus', 8, 2);
            $table->decimal('completion_bonus', 8, 2);
            $table->decimal('extension_bonus', 8, 2);
            $table->decimal('other_bonus', 8, 2);
            $table->boolean('four_zero_one_k');
            $table->boolean('health_insaurance');
            $table->boolean('dental');
            $table->boolean('vision');
            $table->decimal('actual_hourly_rate', 8, 2);
            $table->decimal('overtime', 8, 2);
            $table->decimal('holiday', 8, 2);
            $table->decimal('on_call', 8, 2);
            $table->decimal('orientation_rate', 8, 2);
            $table->decimal('weekly_non_taxable_amount', 8, 2)->nullable();
            $table->string('description');
            $table->decimal('weekly_taxable_amount', 8, 2)->nullable();
            $table->decimal('employer_weekly_amount', 8, 2)->nullable();
            $table->decimal('goodwork_weekly_amount', 8, 2)->nullable();
            $table->decimal('total_employer_amount', 8, 2)->nullable();
            $table->decimal('total_goodwork_amount', 8, 2)->nullable();
            $table->decimal('total_contract_amount', 8, 2)->nullable();
            $table->decimal('weekly_pay', 8, 2);
            $table->boolean('is_draft');
            $table->boolean('is_counter');
            $table->string('created_by', 36);
            $table->string('tax_status', 36);
            $table->foreign('worker_user_id')->references('id')->on('nurses')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_payment_required')->nullable()->default(false);
            $table->boolean('is_payment_done')->nullable()->default(false);
            $table->string('worker_payment_status')->nullable();
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
        Schema::dropIfExists('offers');
    }
}
