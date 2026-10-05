<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntakeTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intake_times', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('intake_course_id')->nullable();
            $table->bigInteger('intake_semester_id')->nullable();
            $table->bigInteger('intake_subject_id')->nullable();
            $table->bigInteger('intake_unit_id')->nullable();
            $table->date('date')->nullable();
            $table->string('day')->nullable();
            $table->time('from')->nullable();
            $table->time('to')->nullable();
            $table->string('classroom')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('intake_times');
    }
}
