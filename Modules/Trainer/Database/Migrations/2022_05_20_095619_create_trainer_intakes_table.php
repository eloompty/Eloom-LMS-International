<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainerIntakesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainer_intakes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trainer_id');
            $table->integer('intake_course_id');
            $table->integer('intake_semester_id');
            $table->integer('intake_subject_id');
            $table->integer('intake_unit_id');
            $table->integer('duration')->nullable();
            $table->date('starting_date')->nullable();
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
        Schema::dropIfExists('trainer_intakes');
    }
}
