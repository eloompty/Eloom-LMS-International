<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnshoreInitialToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->float('fee_initial')->default(0)->after('fee');
            $table->integer('fee_installment')->default(0)->after('fee_initial');
            $table->float('onshore_initial')->default(0)->after('onshore_fee');
            $table->integer('onshore_installment')->default(0)->after('onshore_initial');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('fee_initial');
            $table->dropColumn('fee_installment');
            $table->dropColumn('onshore_initial');
            $table->dropColumn('onshore_installment');
        });
    }
}
