<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddIntakeSubjectIdToAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'intake_subject_id')) {
                $table->integer('intake_subject_id')->nullable()->after('intake_unit_id');
            }

            $table->integer('intake_unit_id')->nullable()->change();
            $table->index(['student_id', 'date', 'intake_unit_id'], 'attendances_student_date_unit_index');
            $table->index(['student_id', 'date', 'intake_subject_id'], 'attendances_student_date_subject_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('attendances')->whereNull('intake_unit_id')->delete();

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('attendances_student_date_unit_index');
            $table->dropIndex('attendances_student_date_subject_index');
            $table->dropColumn('intake_subject_id');
            $table->integer('intake_unit_id')->nullable(false)->change();
        });
    }
}
