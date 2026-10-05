<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedStudentAssignmentAiSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $settings = [
            'enable_student_assignment_ai_bot_checking' => 'enable',
            'enable_student_assignment_paraphrasing' => 'enable',
            'enable_student_assignment_plagiarism_test' => 'enable',
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
            'enable_student_assignment_ai_bot_checking',
            'enable_student_assignment_paraphrasing',
            'enable_student_assignment_plagiarism_test',
        ])->delete();
    }
}
