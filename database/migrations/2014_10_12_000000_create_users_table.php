<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum("role", \App\Enums\Role::getKeys());
            $table->string('first_name', 150);
            $table->string('last_name', 150);
            $table->string('image')->nullable();
            $table->string('email',190)->unique();
            $table->string('user_name',190)->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('mobile', 15)->nullable();
            $table->boolean('email_notification')->default(true);
            $table->boolean('sms_notification')->default(true);
            $table->boolean('active')->default(true);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
            // assembling user table
            $table->timestamp('banned_until')->nullable();
            $table->datetime('last_login_at')->nullable();
            $table->string('last_login_ip',100)->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('otp_expiry')->nullable();
            $table->string('about_me');
            $table->string('qualities');
            $table->string('facility_id');
            // adding zip_code
            $table->string('zip_code')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
