<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdNoToTrainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainers', function (Blueprint $table) {
            if (!Schema::hasColumn('trainers', 'id_no')) {
                $table->string('id_no')->nullable()->after('image');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trainers', function (Blueprint $table) {
            if (Schema::hasColumn('trainers', 'id_no')) {
                $table->dropColumn('id_no');
            }
        });
    }
}
