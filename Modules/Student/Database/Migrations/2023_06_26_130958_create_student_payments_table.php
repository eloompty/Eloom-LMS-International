<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id');
            $table->bigInteger('intake_course_id');
            $table->float('taxable_amount');
            $table->float('total_amount'); 
            $table->integer('agent_commission_percent')->default(0);
            $table->float('agent_commission_amount')->default(0);
            $table->integer('gst_percent')->default(0);
            $table->float('gst')->default(0);
            $table->integer('gst_waiver')->default(0);
            $table->integer('paid_to_agent')->default(0);
            $table->integer('branch_commission_percent')->default(0);
            $table->float('branch_commission_amount')->default(0);
            $table->float('student_discount')->default(0);
            $table->string('payment_type');
            $table->float('paid_amount');
            $table->float('remaining_amount');
            $table->date('paid_date');
            $table->date('received_date');
            $table->string('receipt')->nullable();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('student_payments');
    }
}
