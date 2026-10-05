<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatAttendeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_attendees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('chat_id');
            $table->bigInteger('user_id')->nullable();
            $table->string('user_type')->nullable();
            $table->integer('is_owner')->default(0);
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
        Schema::dropIfExists('chat_attendees');
    }
}
