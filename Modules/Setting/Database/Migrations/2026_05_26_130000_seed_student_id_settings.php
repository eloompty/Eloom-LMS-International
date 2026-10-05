<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedStudentIdSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $settings = [
            'student_id_field' => 'manual',
            'student_id_number_start' => '1',
            'student_id_format' => 'none',
            'student_id_prefix' => '',
            'student_id_suffix' => '',
        ];

        foreach ($settings as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                [
                    'value' => $value,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('settings')->whereIn('key', [
            'student_id_field',
            'student_id_number_start',
            'student_id_format',
            'student_id_prefix',
            'student_id_suffix',
        ])->delete();
    }
}
