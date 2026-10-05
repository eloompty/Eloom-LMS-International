<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScholarshipDisbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarship_disbursements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scholarship_application_id');
            $table->unsignedBigInteger('intake_semester_id')->nullable()->comment('null for one_off');
            $table->unsignedTinyInteger('sequence')->nullable()->comment('semester order, 1-based');
            $table->decimal('planned_amount', 10, 2);
            $table->decimal('actual_amount', 10, 2)->nullable()->comment('set when released');
            $table->unsignedBigInteger('fee_discount_id')->nullable()->comment('the materialised discount when released');
            $table->enum('state', ['scheduled', 'released', 'withheld', 'cancelled'])->default('scheduled');
            $table->decimal('maintenance_percentage_achieved', 5, 2)->nullable();
            $table->text('state_reason')->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();

            $table->index('scholarship_application_id');
            $table->index(['state', 'intake_semester_id']);
            $table->foreign('scholarship_application_id')->references('id')->on('scholarship_applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scholarship_disbursements');
    }
}
