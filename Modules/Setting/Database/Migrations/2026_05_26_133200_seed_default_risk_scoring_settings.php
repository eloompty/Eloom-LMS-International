<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedDefaultRiskScoringSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('risk_scoring_settings')->updateOrInsert(
            ['id' => 1],
            [
                'attendance_weight' => 30,
                'assignment_weight' => 25,
                'grade_weight' => 20,
                'fee_weight' => 15,
                'engagement_weight' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('risk_scoring_settings')->where('id', 1)->delete();
    }
}
