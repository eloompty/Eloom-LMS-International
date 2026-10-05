<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeAppealsTable extends Migration
{
    public function up()
    {
        Schema::create('grade_appeals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('mark_type'); // 'subject' | 'unit'
            $table->unsignedBigInteger('mark_id'); // student_intake_subject_mark_id or student_intake_unit_mark_id
            $table->text('reason');
            $table->string('status')->default('pending'); // pending | trainer_reviewed | resolved | rejected
            $table->text('trainer_response')->nullable();
            $table->unsignedBigInteger('trainer_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->unsignedBigInteger('admin_user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('grade_appeals');
    }
}
