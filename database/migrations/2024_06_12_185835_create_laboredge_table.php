<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboredgeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboredge', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->uuid('user_id');
            $table->string('le_password');
            $table->string('le_username');
            $table->string('le_organization_code');
            $table->string('le_client_id');
            $table->boolean('initiated')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laboredge');
    }
}
