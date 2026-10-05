<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_devices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id');
            $table->string('device_type'); // Android or iOS
            $table->string('device_name'); // Name of device
            $table->string('device_token'); // FCM token
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
        Schema::dropIfExists('student_devices');
    }
}
