<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentIntakeSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_intake_subjects', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_intake_course_id');
            $table->bigInteger('student_intake_semester_id');
            $table->bigInteger('intake_subject_id');
            $table->integer('duration')->nullable();
            $table->date('starting_date')->nullable();
            $table->date('ending_date')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('sequence')->nullable();
            $table->integer('is_complete')->default(0);
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
        Schema::dropIfExists('student_intake_subjects');
    }
}
