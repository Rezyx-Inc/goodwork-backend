<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialLinkToWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->string('workers_video')->nullable();
            $table->string('workers_facebook')->nullable();
            $table->string('workers_twitter')->nullable();
            $table->string('workers_linkedin')->nullable();
            $table->string('workers_instagram')->nullable();
            $table->string('workers_pinterest')->nullable();
            $table->string('workers_tiktok')->nullable();
            $table->string('workers_sanpchat')->nullable();
            $table->string('workers_youtube')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->dropColumn('workers_video');
            $table->dropColumn('workers_facebook');
            $table->dropColumn('workers_twitter');
            $table->dropColumn('workers_linkedin');
            $table->dropColumn('workers_instagram');
            $table->dropColumn('workers_pinterest');
            $table->dropColumn('workers_tiktok');
            $table->dropColumn('workers_sanpchat');
            $table->dropColumn('workers_youtube');
        });
    }
}
