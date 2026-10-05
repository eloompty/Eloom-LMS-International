<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfferCourseDetailsToIntakeCourses extends Migration
{
    /**
     * Offer-letter course-detail fields, stored per intake course and copied
     * to student_intake_courses when a student is assigned that intake course.
     */
    private array $tables = ['intake_courses', 'student_intake_courses'];

    public function up()
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('study_mode')->nullable();
                $table->string('study_location')->nullable();
                $table->string('work_placement')->nullable();
                $table->string('hours_per_week')->nullable();
                $table->text('holiday_breaks')->nullable();
                $table->text('entry_requirements')->nullable();
            });
        }
    }

    public function down()
    {
        $columns = ['study_mode', 'study_location', 'work_placement', 'hours_per_week', 'holiday_breaks', 'entry_requirements'];
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
}
