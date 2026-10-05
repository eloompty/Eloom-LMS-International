<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->id();
            // Exactly one of these is set, matching the attendances convention.
            $table->unsignedBigInteger('intake_unit_id')->nullable();
            $table->unsignedBigInteger('intake_subject_id')->nullable();
            $table->date('date');
            $table->time('starts_at')->nullable();
            $table->time('ends_at')->nullable();
            $table->string('title')->nullable();          // "Lecture 1", "Lab", ...
            $table->unsignedBigInteger('trainer_id')->nullable();
            $table->tinyInteger('status')->default(1);     // 0 = inactive, 1 = active
            $table->timestamps();

            $table->index(['intake_unit_id', 'date']);
            $table->index(['intake_subject_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_sessions');
    }
}
