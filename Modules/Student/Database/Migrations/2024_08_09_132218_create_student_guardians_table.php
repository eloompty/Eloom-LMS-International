<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentGuardiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_guardians', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id');
            $table->string('name')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('email')->nullable();
            $table->string('occupation')->nullable();
            $table->string('relation')->nullable();
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
        Schema::dropIfExists('student_guardians');
    }
}
