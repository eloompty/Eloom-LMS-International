<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeDiscountsTable extends Migration
{
    public function up()
    {
        Schema::create('fee_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scholarship_application_id')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('student_intake_course_fee_id');
            $table->enum('type', ['merit', 'need_based', 'staff', 'early_enrollment', 'agent_negotiated', 'manual']);
            $table->enum('value_type', ['fixed', 'percentage']);
            $table->decimal('value', 10, 2);
            $table->decimal('discount_amount', 10, 2);
            $table->text('justification');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('approved_by');
            $table->timestamp('approved_at');
            $table->tinyInteger('status')->default(1)->comment('0=inactive 1=active 2=deleted');
            $table->timestamps();

            $table->foreign('scholarship_application_id')->references('id')->on('scholarship_applications')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('fee_discounts');
    }
}
