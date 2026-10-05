<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type')->default('online'); // online | physical | hybrid
            $table->unsignedBigInteger('location_id')->nullable();
            $table->string('online_link')->nullable();
            $table->integer('capacity')->nullable();
            $table->timestamp('registration_deadline')->nullable();
            $table->boolean('waiting_list_enabled')->default(true);
            $table->boolean('issue_certificate')->default(false);
            $table->unsignedBigInteger('certificate_template_id')->nullable();
            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });

        Schema::create('event_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->string('title');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });

        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->string('registrant_type'); // student | trainer | user
            $table->unsignedBigInteger('registrant_id');
            $table->string('status')->default('registered'); // registered | waitlisted | attended | cancelled
            $table->timestamp('registered_at');
            $table->timestamp('attended_at')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'registrant_type', 'registrant_id'], 'event_reg_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('event_sessions');
        Schema::dropIfExists('events');
    }
}
