<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentIntakeUnitFeePaymentStripesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_intake_unit_fee_payment_stripes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_intake_unit_fee_payment_id');
            $table->string('stripe_id');
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
        Schema::dropIfExists('student_intake_unit_fee_payment_stripes');
    }
}
