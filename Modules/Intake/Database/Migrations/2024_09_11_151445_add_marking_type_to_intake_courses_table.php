<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMarkingTypeToIntakeCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('intake_courses', function (Blueprint $table) {
            $table->string('marking_type')->nullable()->after('trainer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('intake_courses', function (Blueprint $table) {
            $table->dropColumn('marking_type');
        });
    }
}
