<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumniProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('alumni_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->unique();
            $table->unsignedBigInteger('student_intake_course_id')->nullable(); // completion that triggered alumni status
            $table->date('graduation_date')->nullable();
            // Employment outcome (self-reported)
            $table->string('employer_name')->nullable();
            $table->string('job_title')->nullable();
            $table->date('employment_start_date')->nullable();
            $table->string('industry')->nullable();
            // Directory opt-in
            $table->boolean('directory_visible')->default(false);
            // Re-enrollment interest
            $table->boolean('interested_in_reenrollment')->default(false);
            $table->text('reenrollment_notes')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alumni_profiles');
    }
}
