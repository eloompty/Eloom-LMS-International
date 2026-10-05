<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_name');
            $table->text('details')->nullable();
            $table->string('course_code');
            $table->string('cricos_code')->nullable();
            $table->text('entry_requirements')->nullable();
            $table->text('pathways')->nullable();
            $table->string('reference_name')->nullable();
            $table->string('delivery_mode')->nullable();
            $table->integer('duration'); // In weeks
            $table->integer('study_period'); // In weeks
            $table->integer('study_break');  // In weeks
            $table->float('fee')->default(0);
            $table->float('onshore_fee')->default(0);
            $table->float('enrollment_fee')->default(0);
            $table->float('material_fee')->default(0);
            $table->integer('total_units');
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
        Schema::dropIfExists('courses');
    }
}
