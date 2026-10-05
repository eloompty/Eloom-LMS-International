<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentPaymentCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_payment_commissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_payment_id');
            $table->bigInteger('agent_id')->nullable();
            $table->date('paid_date');
            $table->string('payment_mode')->default('Cash');
            $table->string('receipt')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('student_payment_commissions');
    }
}
