<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllowSubmissionToIntakesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('intakes', function (Blueprint $table) {
            $table->string('allow_submission_after_due_date')->default('on')->after('starting_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('intakes', function (Blueprint $table) {
            $table->dropColumn('allow_submission_after_due_date');
        });
    }
}
