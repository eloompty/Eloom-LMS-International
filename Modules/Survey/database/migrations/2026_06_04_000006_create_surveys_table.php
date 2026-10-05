<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysTable extends Migration
{
    public function up()
    {
        // Survey templates (created by admin or trainer)
        Schema::create('survey_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('created_by_type')->default('user'); // user | trainer
            $table->unsignedBigInteger('created_by_id');
            $table->boolean('is_anonymous')->default(false);
            $table->integer('status')->default(1);
            $table->timestamps();
        });

        // Questions within a template
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_template_id');
            $table->string('question');
            $table->string('type'); // likert | mcq | text | rating | nps
            $table->json('options')->nullable(); // for mcq: ["Option A","Option B",...]
            $table->integer('sequence')->default(0);
            $table->boolean('required')->default(true);
            $table->timestamps();

            $table->foreign('survey_template_id')->references('id')->on('survey_templates')->onDelete('cascade');
        });

        // Dispatched survey instances (linked to intake/course/event etc.)
        Schema::create('survey_instances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_template_id');
            $table->string('target_type')->default('all'); // all | intake | course | event
            $table->unsignedBigInteger('target_id')->nullable();
            $table->timestamp('dispatch_at')->nullable(); // null = immediate
            $table->timestamp('closes_at')->nullable();
            $table->unsignedBigInteger('created_by_id');
            $table->string('created_by_type')->default('user');
            $table->integer('status')->default(1);
            $table->timestamps();
        });

        // Individual responses per respondent
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_instance_id');
            $table->string('respondent_type'); // student | trainer
            $table->unsignedBigInteger('respondent_id');
            $table->json('answers'); // [{question_id:1, answer:"4"}, ...]
            $table->timestamp('submitted_at');
            $table->timestamps();

            $table->unique(['survey_instance_id', 'respondent_type', 'respondent_id'], 'survey_response_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('survey_responses');
        Schema::dropIfExists('survey_instances');
        Schema::dropIfExists('survey_questions');
        Schema::dropIfExists('survey_templates');
    }
}
