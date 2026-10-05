<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentBranchUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_branch_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('branch_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('password');
            $table->string('image');
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
        Schema::dropIfExists('agent_branch_users');
    }
}
