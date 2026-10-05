<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_offers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id');
            $table->string('intake_course_ids');
            $table->string('condition_title')->nullable();
            $table->text('condition_description')->nullable();
            $table->string('credit_title')->nullable();
            $table->text('credit_description')->nullable();
            $table->date('expiry_date')->nullable();
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
        Schema::dropIfExists('student_offers');
    }
}
