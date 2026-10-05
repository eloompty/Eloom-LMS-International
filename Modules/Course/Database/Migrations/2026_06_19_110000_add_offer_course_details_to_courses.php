<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfferCourseDetailsToCourses extends Migration
{
    /**
     * Offer-letter course-detail defaults held on the base course. These flow
     * to intake_courses when the course is added to an intake, and then to
     * student_intake_courses when a student is assigned that intake course.
     * (entry_requirements already exists on the courses table and is reused.)
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('study_mode')->nullable();
            $table->string('study_location')->nullable();
            $table->string('work_placement')->nullable();
            $table->string('hours_per_week')->nullable();
            $table->text('holiday_breaks')->nullable();
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['study_mode', 'study_location', 'work_placement', 'hours_per_week', 'holiday_breaks']);
        });
    }
}
