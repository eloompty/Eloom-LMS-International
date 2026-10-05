<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClassRoomToIntakeUnitTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('intake_unit_times', function (Blueprint $table) {
            $table->string('classroom')->nullable()->after('to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('intake_unit_times', function (Blueprint $table) {
            $table->dropColumn('classroom');
        });
    }
}
