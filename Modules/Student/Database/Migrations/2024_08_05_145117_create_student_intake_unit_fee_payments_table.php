<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentIntakeUnitFeePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_intake_unit_fee_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_intake_unit_fee_id');
            $table->float('fee_amount');
            $table->date('paid_date');
            $table->text('receipt')->nullable();
            $table->text('remarks')->nullable();
            $table->string('payment_type');
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
        Schema::dropIfExists('student_intake_unit_fee_payments');
    }
}
