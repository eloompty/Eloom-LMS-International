<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_branches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agent_id');
            $table->string('name');
            $table->integer('country_id');
            $table->string('city');
            $table->string('address');
            $table->string('phone');
            $table->double('rate', 10, 2);
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
        Schema::dropIfExists('agent_branches');
    }
}
