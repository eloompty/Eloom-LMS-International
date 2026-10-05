<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('unit_id');
            $table->string('type');
            $table->string('path')->nullable();
            $table->date('due_date');
            $table->integer('user_id'); // User Id of uploaded admin
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
        Schema::dropIfExists('unit_assignments');
    }
}
