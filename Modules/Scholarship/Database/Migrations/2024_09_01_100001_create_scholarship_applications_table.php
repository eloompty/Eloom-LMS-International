<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScholarshipApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('scholarship_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scholarship_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('intake_course_id');
            $table->unsignedBigInteger('student_intake_course_fee_id');
            $table->text('justification')->nullable();
            $table->unsignedBigInteger('applied_by');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=pending 1=approved 2=rejected');
            $table->timestamps();

            $table->foreign('scholarship_id')->references('id')->on('scholarships')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('scholarship_applications');
    }
}
