<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIntakeSemesterIdToIntakeUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('intake_units', function (Blueprint $table) {
            $table->integer('intake_semester_id')->after('intake_course_id');
            $table->integer('intake_subject_id')->after('intake_semester_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('intake_units', function (Blueprint $table) {
            $table->dropColumn('intake_semester_id');
            $table->dropColumn('intake_subject_id');
        });
    }
}
