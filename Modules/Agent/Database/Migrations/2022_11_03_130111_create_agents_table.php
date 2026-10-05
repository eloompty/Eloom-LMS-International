<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('password');
            $table->string('name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_registration')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('office_phone')->nullable();
            $table->string('url')->nullable();
            $table->string('mobile')->nullable();
            $table->string('image')->nullable();
            $table->integer('admin_id')->default(1);
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->double('rate', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agents');
    }
}
