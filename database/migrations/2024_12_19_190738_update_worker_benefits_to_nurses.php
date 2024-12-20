<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWorkerBenefitsToNurses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->string('worker_benefits')->nullable()->change();
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
            // Replace null values with a default before making non-nullable
            DB::table('nurses')->whereNull('worker_benefits')
                ->update(['worker_benefits' => '']);
            $table->string('worker_benefits')->nullable(false)->change();
        });
    }
}
