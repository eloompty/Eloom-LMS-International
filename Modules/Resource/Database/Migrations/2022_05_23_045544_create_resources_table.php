<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('resource_type'); // Type of file uploaded
            $table->string('path');
            $table->string('user_type');
            $table->integer('resource_category_id');
            $table->integer('course_id')->nullable();
            $table->integer('semester_id')->nullable();
            $table->integer('subject_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->string('uploaded_by'); // Trainer or Admin
            $table->bigInteger('uploaded_user_id'); // Id of uploaded user
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
        Schema::dropIfExists('resources');
    }
}
