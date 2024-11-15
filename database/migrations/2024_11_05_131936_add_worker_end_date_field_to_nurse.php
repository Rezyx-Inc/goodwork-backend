<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkerEndDateFieldToNurse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->date('worker_end_date')->nullable(); 
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
            $table->dropColumn('worker_end_date');
        });
    }
}
