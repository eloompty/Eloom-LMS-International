<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScholarshipsTable extends Migration
{
    public function up()
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['merit', 'need_based', 'staff', 'early_enrollment', 'agent_negotiated']);
            $table->enum('value_type', ['fixed', 'percentage']);
            $table->decimal('value', 10, 2);
            $table->decimal('max_value', 10, 2)->nullable()->comment('Cap for percentage discounts');
            $table->unsignedInteger('quota')->nullable()->comment('Max applications; null = unlimited');
            $table->text('description')->nullable();
            $table->text('eligibility_criteria')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0=inactive 1=active 2=deleted');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('scholarships');
    }
}
