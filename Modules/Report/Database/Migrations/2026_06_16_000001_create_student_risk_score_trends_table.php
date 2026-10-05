<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentRiskScoreTrendsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('student_risk_score_trends');

        Schema::create('student_risk_score_trends', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('student_intake_course_id');
            $table->integer('overall_score');
            $table->string('risk_level')->index();
            $table->integer('attendance_score');
            $table->integer('assignment_score');
            $table->integer('grade_score');
            $table->integer('fee_score');
            $table->integer('engagement_score');
            $table->date('analyzed_at');
            $table->timestamps();

            $table->unique(['student_id', 'student_intake_course_id', 'analyzed_at'], 'srst_student_course_date_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_risk_score_trends');
    }
}
