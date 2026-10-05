<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentSubmissionFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_submission_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('assignment_submission_id');
            $table->string('file')->nullable();
            $table->string('graded_file')->nullable();
            $table->integer('show_to_student')->default(0);
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
        Schema::dropIfExists('assignment_submission_files');
    }
}
