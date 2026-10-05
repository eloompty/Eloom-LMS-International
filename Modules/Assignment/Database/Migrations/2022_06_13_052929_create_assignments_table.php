<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('unit_assignment_id')->nullable();
            $table->integer('intake_unit_id');
            $table->integer('trainer_id')->nullable();
            $table->string('type');
            $table->string('path')->nullable();
            $table->date('due_date');
            $table->string('uploaded_by'); // Uploaded by i.e. Admin/Trainer
            $table->bigInteger('uploaded_user_id'); // Uploader Id
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
        Schema::dropIfExists('assignments');
    }
}
