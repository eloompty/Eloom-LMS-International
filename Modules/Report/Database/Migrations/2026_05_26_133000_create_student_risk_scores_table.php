<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentRiskScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_risk_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('student_intake_course_id');
            $table->integer('overall_score')->default(0);
            $table->string('risk_level')->default('low');
            $table->integer('attendance_score')->default(0);
            $table->integer('assignment_score')->default(0);
            $table->integer('grade_score')->default(0);
            $table->integer('fee_score')->default(0);
            $table->integer('engagement_score')->default(0);
            $table->json('details')->nullable();
            $table->timestamp('analyzed_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'student_intake_course_id'], 'student_risk_unique');
            $table->index('risk_level');
            $table->index('overall_score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_risk_scores');
    }
}
