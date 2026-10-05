<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkPlacementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_placements', function (Blueprint $table) {
            $table->id();
            $table->string('placement_company_name');
            $table->string('type'); // 'course' for course table
            $table->integer('type_id'); // course_id for course table
            $table->string('contact_person');
            $table->bigInteger('contact_person_mobile');
            $table->string('position');
            $table->integer('placement_hours');
            $table->text('placement_description');
            $table->string('site_name');
            $table->date('starting_date');
            $table->date('ending_date');
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
        Schema::dropIfExists('work_placements');
    }
}
