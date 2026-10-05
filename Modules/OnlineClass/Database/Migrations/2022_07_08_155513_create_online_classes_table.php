<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_classes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('meeting_id');
            $table->string('topic')->nullable();
            $table->string('agenda')->nullable();
            $table->string('join_url');
            $table->string('password')->nullable();
            $table->integer('created_user_id');
            $table->string('created_user_type');
            $table->integer('intake_unit_id');
            $table->integer('online_class_group_id')->default(0);
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
        Schema::dropIfExists('online_classes');
    }
}
