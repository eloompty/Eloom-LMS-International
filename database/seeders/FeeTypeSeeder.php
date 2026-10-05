<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FeeTypeSeeder extends Seeder
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
        DB::table('fee_types')->truncate();
        Schema::enableForeignKeyConstraints();

        $document_types = [
            ['name' => 'Application Fee', 'key' => 'application_fee', 'amount' => 1000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Admission Fee', 'key' => 'admission_fee', 'amount' => 30000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Security Deposite Fee', 'key' => 'security_deposite_fee', 'amount' => 15000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Semester Fee', 'key' => 'semester_fee', 'amount' => 82500, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Library Fee', 'key' => 'library_fee', 'amount' => 5000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Labratory Fee', 'key' => 'labratory_fee', 'amount' => 5000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Project Fee', 'key' => 'project_fee', 'amount' => 15000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Exam Fee', 'key' => 'exam_fee', 'amount' => 1500, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Reexam Fee', 'key' => 'reexam_fee', 'amount' => 1500, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Picnic Fee', 'key' => 'picnic_fee', 'amount' => 2000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];
        DB::table('fee_types')->insert($document_types);
    }
}
