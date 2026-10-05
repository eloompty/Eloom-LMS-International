<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRiskScoringSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risk_scoring_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('attendance_weight')->default(30);
            $table->integer('assignment_weight')->default(25);
            $table->integer('grade_weight')->default(20);
            $table->integer('fee_weight')->default(15);
            $table->integer('engagement_weight')->default(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('risk_scoring_settings');
    }
}
