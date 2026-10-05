<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIntakeTimeIdToClassSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_sessions', function (Blueprint $table) {
            // Links a session to the timetable slot (intake_times row) it was materialised from.
            $table->unsignedBigInteger('intake_time_id')->nullable()->after('id');
            $table->index('intake_time_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_sessions', function (Blueprint $table) {
            $table->dropIndex(['intake_time_id']);
            $table->dropColumn('intake_time_id');
        });
    }
}
