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
            $table->string('status')->nullable();
            $table->string('type')->nullable();
            $table->string('terms')->nullable();
            $table->string('specialty')->nullable();
            $table->string('profession')->nullable();
            $table->boolean('block_scheduling')->nullable();
            $table->boolean('float_requirement')->nullable();
            $table->string('facility_shift_cancelation_policy')->nullable();
            $table->string('contract_termination_policy')->nullable();
            $table->string('traveler_distance_from_facility')->nullable();
            $table->string('job_id', 36)->nullable();
            $table->string('recruiter_id', 36)->nullable();
            $table->string('worker_user_id', 36)->nullable();
            $table->string('clinical_setting')->nullable();
            $table->decimal('Patient_ratio', 8, 2)->nullable();
            $table->string('emr')->nullable();
            $table->string('Unit')->nullable();
            $table->string('scrub_color')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('as_soon_as')->nullable();
            $table->string('rto')->nullable();
            $table->decimal('hours_per_week', 8, 2)->nullable();
            $table->decimal('guaranteed_hours', 8, 2)->nullable();
            $table->decimal('hours_shift', 8, 2)->nullable();
            $table->decimal('weeks_shift', 8, 2)->nullable();
            $table->decimal('preferred_assignment_duration', 8, 2)->nullable();
            $table->decimal('referral_bonus', 8, 2)->nullable();
            $table->decimal('sign_on_bonus', 8, 2)->nullable();
            $table->decimal('completion_bonus', 8, 2)->nullable();
            $table->decimal('extension_bonus', 8, 2)->nullable();
            $table->decimal('other_bonus', 8, 2)->nullable();
            $table->boolean('four_zero_one_k')->nullable()->default(false);
            $table->boolean('health_insaurance')->nullable()->default(false);
            $table->boolean('dental')->nullable()->default(false);
            $table->boolean('vision')->nullable()->default(false);
            $table->decimal('actual_hourly_rate', 8, 2)->nullable();
            $table->decimal('overtime', 8, 2)->nullable();
            $table->decimal('holiday', 8, 2)->nullable();
            $table->decimal('on_call', 8, 2)->nullable();
            $table->decimal('orientation_rate', 8, 2)->nullable();
            $table->decimal('weekly_non_taxable_amount', 8, 2)->nullable();
            $table->string('description')->nullable();
            $table->decimal('weekly_taxable_amount', 8, 2)->nullable();
            $table->decimal('organization_weekly_amount', 8, 2)->nullable();
            $table->decimal('goodwork_weekly_amount', 8, 2)->nullable();
            $table->decimal('total_organization_amount', 8, 2)->nullable();
            $table->decimal('total_goodwork_amount', 8, 2)->nullable();
            $table->decimal('total_contract_amount', 8, 2)->nullable();
            $table->decimal('weekly_pay', 8, 2)->nullable();
            $table->boolean('is_draft')->nullable()->default(false);
            $table->boolean('is_counter')->nullable()->default(false);
            $table->boolean('worked_at_facility_before')->nullable()->default(false);
            $table->string('created_by', 36)->nullable();
            $table->string('tax_status', 36)->nullable();
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
