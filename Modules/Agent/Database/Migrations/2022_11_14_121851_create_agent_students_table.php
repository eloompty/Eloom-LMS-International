<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agent_id');
            $table->bigInteger('branch_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('salutation');
            $table->string('first_name');
            $table->string('family_name');
            $table->date('date_of_birth');
            $table->string('passport_no')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('phone');
            $table->string('mobile');
            $table->string('email');
            $table->string('image');
            $table->string('address');
            $table->string('emergency_contact_person');
            $table->string('emergency_contact_number');
            $table->string('emergency_contact_relation');
            $table->integer('status')->default(0); // 0 => Entry, 1 => Applied, 2 => Approved, 3 => Accepted, 4 => Rejected, 5 => Enrolled
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
        Schema::dropIfExists('agent_students');
    }
}
