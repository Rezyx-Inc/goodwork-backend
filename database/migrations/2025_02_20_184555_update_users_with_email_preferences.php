<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersWithEmailPreferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->boolean('email_messages')->default(true);
            $table->boolean('email_new_applications')->default(true);
            $table->boolean('email_application_stages')->default(true);
            $table->boolean('email_counter_offer')->default(true);
            $table->boolean('email_new_offer')->default(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_messages');
            $table->dropColumn('email_new_applications');
            $table->dropColumn('email_application_stages');
            $table->dropColumn('email_counter_offer');
            $table->dropColumn('email_new_offer');
        });
    }
}
