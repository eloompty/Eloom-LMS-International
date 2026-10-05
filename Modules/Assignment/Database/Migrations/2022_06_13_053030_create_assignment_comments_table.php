<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('assignment_id');
            $table->bigInteger('user_id'); // Commented by i.e. Trainer/Student
            $table->bigInteger('user_type'); // Commenter Id
            $table->text('comment');
            $table->integer('status');
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
        Schema::dropIfExists('assignment_comments');
    }
}
