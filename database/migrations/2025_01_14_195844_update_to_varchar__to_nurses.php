<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateToVarcharToNurses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->string('worker_guaranteed_hours', 255)->nullable()->change();
            $table->string('worker_hours_per_week', 255)->nullable()->change();
            $table->string('worker_hours_shift', 255)->change();
            $table->string('worker_shifts_week', 255)->nullable()->change();
            $table->string('worker_weeks_assignment', 255)->nullable()->change();
            $table->string('worker_benefits', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->string('worker_guaranteed_hours', 255)->nullable(false)->change();
            $table->string('worker_hours_per_week', 255)->nullable(false)->change();
            $table->string('worker_hours_shift', 255)->change();
            $table->string('worker_shifts_week', 255)->nullable(false)->change();
            $table->string('worker_weeks_assignment', 255)->nullable(false)->change();
            $table->string('worker_benefits', 255)->nullable(false)->change();
        });
    }
}
