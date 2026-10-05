<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntakeCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intake_courses', function (Blueprint $table) {
            $table->id();
            $table->integer('intake_id');
            $table->integer('course_id');
            $table->string('reference_name');
            $table->date('starting_date');
            $table->date('ending_date');
            $table->integer('duration');
            $table->bigInteger('trainer_id')->nullable();
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
        Schema::dropIfExists('intake_courses');
    }
}
