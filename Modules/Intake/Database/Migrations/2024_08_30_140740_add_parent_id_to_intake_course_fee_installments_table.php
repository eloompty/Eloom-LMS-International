<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentIdToIntakeCourseFeeInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('intake_course_fee_installments', function (Blueprint $table) {
            $table->bigInteger('parent_id')->default(0)->after('due_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('intake_course_fee_installments', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
}
