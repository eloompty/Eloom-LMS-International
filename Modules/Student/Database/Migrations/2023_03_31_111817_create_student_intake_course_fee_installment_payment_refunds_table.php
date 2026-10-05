<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentIntakeCourseFeeInstallmentPaymentRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_intake_course_fee_installment_payment_refunds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_intake_course_fee_installment_payment_id');
            $table->float('refunded_amount');
            $table->text('comment')->nullable();
            $table->string('receipt')->nullable();
            $table->integer('reinstate')->default(0);
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
        Schema::dropIfExists('student_intake_course_fee_installment_payment_refunds');
    }
}
