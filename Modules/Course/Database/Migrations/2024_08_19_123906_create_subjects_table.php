<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('course_id');
            $table->bigInteger('semester_id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('credits')->nullable();
            $table->string('full_marks')->nullable();
            $table->string('theory')->nullable();
            $table->string('practical')->nullable();
            $table->string('internal')->nullable();
            $table->string('type')->default('Core');
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('subjects');
    }
}
