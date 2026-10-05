<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentIntakeCourseFeeInstallmentPaymentNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_intake_course_fee_installment_payment_notes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_intake_course_fee_installment_payment_id');
            $table->text('notes');
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
        Schema::dropIfExists('student_intake_course_fee_installment_payment_notes');
    }
}
