<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificateTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // completion | diploma | degree | transcript | badge
            $table->json('layout'); // JSON: {background_color, logo_url, title_text, body_text, fields:[...]}
            $table->boolean('is_default')->default(false);
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificate_templates');
    }
}
