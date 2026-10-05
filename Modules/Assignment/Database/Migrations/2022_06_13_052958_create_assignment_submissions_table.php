<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('assignment_id');
            $table->bigInteger('student_id');
            $table->string('path')->nullable();
            $table->integer('assignment_grade_id')->nullable();
            $table->text('remarks')->nullable();
            $table->float('credits')->nullable();
            $table->date('graded_date')->nullable();
            $table->bigInteger('assignment_resubmission_id')->default(0);
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
        Schema::dropIfExists('assignment_submissions');
    }
}
