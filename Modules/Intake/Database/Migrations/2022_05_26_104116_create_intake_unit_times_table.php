<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntakeUnitTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intake_unit_times', function (Blueprint $table) {
            $table->id();
            $table->integer('intake_course_time_id');
            $table->integer('intake_unit_id');
            $table->string('day');
            $table->time('from');
            $table->time('to');
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
        Schema::dropIfExists('intake_unit_times');
    }
}
