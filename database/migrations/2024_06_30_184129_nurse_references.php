<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NurseReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nurse_references', function (Blueprint $table) {
            // Creating the primary key as a UUID
            $table->uuid('id')->primary();

            // Foreign key reference to nurses table
            $table->uuid('nurse_id');
            $table->foreign('nurse_id')->references('id')->on('nurses')->onDelete('cascade');

            // Other fields
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_referred')->nullable();
            $table->string('min_title_of_reference')->nullable();
            $table->integer('recency_of_reference')->nullable();
            $table->string('image')->nullable();
            $table->boolean('active')->default(true);

            // Timestamps and soft deletes
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nurse_references');
    }
}
