<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentBranchUserDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_branch_user_devices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agent_branch_user_id');
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
        Schema::dropIfExists('agent_branch_user_devices');
    }
}
