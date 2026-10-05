<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFeesToIntakeCourseFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('intake_course_fees', function (Blueprint $table) {
            $table->string('extra_fee')->nullable()->after('fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('intake_course_fees', function (Blueprint $table) {
            $table->dropColumn('extra_fee');
        });
    }
}
