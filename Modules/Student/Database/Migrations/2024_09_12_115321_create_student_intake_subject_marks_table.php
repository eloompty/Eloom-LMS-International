<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentIntakeSubjectMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_intake_subject_marks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_intake_subject_id');
            $table->string('name')->nullable();
            $table->string('full_marks')->nullable();
            $table->string('pass_marks')->nullable();
            $table->string('obtain_marks')->nullable();
            $table->string('user_type')->nullable();
            $table->bigInteger('user_id')->nullable();
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
        Schema::dropIfExists('student_intake_subject_marks');
    }
}
