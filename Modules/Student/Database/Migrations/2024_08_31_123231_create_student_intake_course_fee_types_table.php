<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentIntakeCourseFeeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_intake_course_fee_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_intake_course_fee_id');
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('student_intake_course_fee_types');
    }
}
