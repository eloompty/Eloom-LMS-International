<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentIntakeCourseFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_intake_course_fees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id');
            $table->bigInteger('intake_course_id');
            $table->string('name');
            $table->string('fee');
            $table->string('extra_fee')->nullable();
            $table->integer('installments')->default(1);
            $table->date('due_date')->nullable();
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
        Schema::dropIfExists('student_intake_course_fees');
    }
}
