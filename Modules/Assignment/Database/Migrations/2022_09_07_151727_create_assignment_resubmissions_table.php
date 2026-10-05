<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentResubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_resubmissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id');
            $table->bigInteger('assignment_id');
            $table->bigInteger('user_id')->nullable(); // Admin or Trainer Id
            $table->string('user_type')->nullable(); // Admin or Trainer
            $table->date('approved_date')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('status')->default(0); // 0 => Requested, 1 => Approved, 2 => Rejected, 3 => Resubmitted
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
        Schema::dropIfExists('assignment_resubmissions');
    }
}
