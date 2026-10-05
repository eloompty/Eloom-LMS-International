<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedTeacherIdSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $settings = [
            'teacher_id_field' => 'manual',
            'teacher_id_number_start' => '1',
            'teacher_id_format' => 'none',
            'teacher_id_prefix' => '',
            'teacher_id_suffix' => '',
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
            'teacher_id_field',
            'teacher_id_number_start',
            'teacher_id_format',
            'teacher_id_prefix',
            'teacher_id_suffix',
        ])->delete();
    }
}
