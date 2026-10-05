<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntakeSemestersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intake_semesters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('intake_course_id');
            $table->bigInteger('semester_id');
            $table->date('starting_date')->nullable();
            $table->date('ending_date')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('sequence')->nullable();
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
        Schema::dropIfExists('intake_semesters');
    }
}
