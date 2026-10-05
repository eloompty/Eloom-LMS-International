<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfferTemplateIdToStudentOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_offers', function (Blueprint $table) {
            $table->unsignedBigInteger('offer_template_id')->nullable()->after('student_id');
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
            $table->dropColumn('offer_template_id');
        });
    }
}
