<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineClassRecordingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_class_recordings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('online_class_id');
            $table->string('share_url')->nullable();
            $table->string('file_type')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('play_url')->nullable();
            $table->string('download_url')->nullable();
            $table->string('password')->nullable();
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
        Schema::dropIfExists('online_class_recordings');
    }
}
