<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use function Psy\debug;

class CreateStudentIntakeCourseFeeInstallmentPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_intake_course_fee_installment_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_intake_course_fee_installment_id');
            $table->float('taxable_amount'); // total_amount - (enrollment_fee + material_fee)
            $table->float('total_amount'); 
            $table->float('student_discount')->default(0);
            $table->string('payment_type');
            $table->float('paid_amount');
            $table->float('remaining_amount');
            $table->date('paid_date');
            $table->date('received_date');
            $table->string('receipt')->nullable();
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
        Schema::dropIfExists('student_intake_course_fee_installment_payments');
    }
}
