<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuedCertificatesTable extends Migration
{
    public function up()
    {
        Schema::create('issued_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique(); // public verification token
            $table->unsignedBigInteger('certificate_template_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('student_intake_course_id')->nullable();
            $table->unsignedBigInteger('student_intake_unit_id')->nullable();
            $table->string('trigger_type'); // 'course_completion' | 'unit_completion' | 'manual' | 'event'
            $table->date('issued_date');
            $table->unsignedBigInteger('issued_by_user_id')->nullable();
            $table->string('file_path')->nullable(); // stored PDF path
            $table->boolean('emailed')->default(false);
            $table->boolean('revoked')->default(false);
            $table->text('revoke_reason')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('issued_certificates');
    }
}
