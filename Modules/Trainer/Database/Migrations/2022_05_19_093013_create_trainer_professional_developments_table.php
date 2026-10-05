<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainerProfessionalDevelopmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainer_professional_developments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trainer_id');
            $table->string('title');
            $table->integer('duration');
            $table->date('start_date');
            $table->date('end_date');
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
        Schema::dropIfExists('trainer_professional_developments');
    }
}
