<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropStudentEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('student_emails');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('student_emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_id');
            $table->bigInteger('email_id');
            $table->bigInteger('email_template_id');
            $table->bigInteger('type_id')->nullable(); // Intake Id / Course Id etc
            $table->string('type')->nullable(); // Intake / Course etc
            $table->bigInteger('sender_id')->nullable(); // Admin Id / Trainer Id
            $table->string('sender_type')->nullable(); // Admin / Trainer
            $table->timestamps();
        });
    }
}
