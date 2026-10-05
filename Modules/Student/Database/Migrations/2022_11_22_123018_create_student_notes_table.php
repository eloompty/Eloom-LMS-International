<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_notes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id');
            $table->text('notes');
            $table->bigInteger('user_id'); // Admin Id
            $table->string('user_type'); // Admin
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('student_notes');
    }
}
