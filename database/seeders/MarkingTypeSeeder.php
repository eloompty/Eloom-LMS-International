<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MarkingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Schema::disableForeignKeyConstraints();
        DB::table('marking_types')->truncate();
        Schema::enableForeignKeyConstraints();

        $document_types = [
            ['name' => 'Attendance', 'full_marks' => 20, 'pass_marks' => 8, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Internal Mark', 'full_marks' => 20, 'pass_marks' => 8, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Practical Mark', 'full_marks' => 20, 'pass_marks' => 8, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Assignment 1 Mark', 'full_marks' => 10, 'pass_marks' => 8, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Assignment 2 Mark', 'full_marks' => 10, 'pass_marks' => 8, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Assignment 3 Mark', 'full_marks' => 10, 'pass_marks' => 8, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];
        DB::table('marking_types')->insert($document_types);
    }
}
