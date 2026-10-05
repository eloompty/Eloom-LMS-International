<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIssueDateToStudentOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_offers', function (Blueprint $table) {
            $table->date('issue_date')->nullable()->after('credit_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_offers', function (Blueprint $table) {
            $table->dropColumn('issue_date');
        });
    }
}
