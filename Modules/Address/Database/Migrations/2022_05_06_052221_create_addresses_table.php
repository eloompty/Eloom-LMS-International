<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('country_id')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('local_body')->nullable();
            $table->string('ward')->nullable();
            $table->string('tole')->nullable();
            $table->string('address')->nullable();
            $table->string('type');
            $table->bigInteger('type_id');
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
        Schema::dropIfExists('addresses');
    }
}
