<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineClassTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_class_teams', function (Blueprint $table) {
            $table->id();
            $table->text('meeting_id');
            $table->string('subject')->nullable();
            $table->string('content')->nullable();
            $table->string('join_url')->nullable();
            $table->date('start_date')->nullable();
            $table->time('start_time')->nullable();
            $table->date('end_date')->nullable();
            $table->time('end_time')->nullable();
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
        Schema::dropIfExists('online_class_teams');
    }
}
