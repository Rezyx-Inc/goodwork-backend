<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCeipalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ceipal', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->uuid('user_id');
            $table->string('password');
            $table->string('username');
            $table->string('email');
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
        Schema::dropIfExists('ceipal');
    }
}
