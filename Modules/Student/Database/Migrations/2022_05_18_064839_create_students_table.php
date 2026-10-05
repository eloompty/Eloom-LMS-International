<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('salutation');
            $table->string('first_name');
            $table->string('family_name');
            $table->date('date_of_birth');
            $table->string('passport_no')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('phone');
            $table->string('mobile');
            $table->string('email');
            $table->string('password')->nullable();
            $table->string('image');
            $table->string('id_no')->nullable(); // manual student_id
            $table->string('email_alternative')->nullable();
            $table->string('gender')->nullable();
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
        Schema::dropIfExists('students');
    }
}
