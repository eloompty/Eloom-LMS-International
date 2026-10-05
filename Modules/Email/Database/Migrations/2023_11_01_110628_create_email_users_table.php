<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id'); // Student Id / Trainer Id / Agent Id
            $table->string('user_type'); // Student / Trainer / Agent
            $table->bigInteger('email_id');
            $table->bigInteger('email_template_id');
            $table->string('email_subject');
            $table->longText('email_content');
            $table->bigInteger('type_id')->nullable(); // Intake Id / Course Id etc
            $table->string('type')->nullable(); // Intake / Course etc
            $table->bigInteger('sender_id')->nullable(); // Admin Id / Trainer Id
            $table->string('sender_type')->nullable(); // Admin / Trainer
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
        Schema::dropIfExists('email_users');
    }
}
