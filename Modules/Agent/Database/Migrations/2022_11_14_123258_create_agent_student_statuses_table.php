<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentStudentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_student_statuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agent_student_id');
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
        Schema::dropIfExists('agent_student_statuses');
    }
}
