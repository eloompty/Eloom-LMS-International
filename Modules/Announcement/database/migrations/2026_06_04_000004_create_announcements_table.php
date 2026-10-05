<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnouncementsTable extends Migration
{
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            // audience_type: all | intake | course | role | student | trainer
            $table->string('audience_type')->default('all');
            $table->unsignedBigInteger('audience_id')->nullable(); // intake_id, course_id, etc.
            $table->string('priority')->default('normal'); // normal | important | urgent
            $table->boolean('require_acknowledgement')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('publish_at')->nullable(); // null = immediate
            $table->timestamp('expires_at')->nullable();
            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->string('created_by_type')->default('user'); // user | trainer
            $table->integer('status')->default(1);
            $table->timestamps();
        });

        Schema::create('announcement_acknowledgements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('announcement_id');
            $table->string('reader_type'); // student | trainer
            $table->unsignedBigInteger('reader_id');
            $table->timestamp('acknowledged_at');
            $table->timestamps();

            $table->unique(['announcement_id', 'reader_type', 'reader_id'], 'announcement_ack_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('announcement_acknowledgements');
        Schema::dropIfExists('announcements');
    }
}
