<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAwardStructureToScholarshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scholarships', function (Blueprint $table) {
            // How much of the tuition it covers.
            $table->enum('award_scope', ['full', 'partial'])->default('partial')->after('type');
            // When it applies.
            $table->enum('disbursement', ['one_off', 'per_semester'])->default('one_off')->after('award_scope');
            // Whether continued eligibility is conditional on performance.
            $table->boolean('requires_maintenance')->default(false)->after('disbursement');
            $table->decimal('maintenance_min_percentage', 5, 2)->nullable()->after('requires_maintenance')
                ->comment('Min overall % required in the prior semester to keep the award');
            $table->unsignedTinyInteger('max_semesters')->nullable()->after('maintenance_min_percentage')
                ->comment('Cap on semesters disbursed; null = full course duration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->dropColumn([
                'award_scope',
                'disbursement',
                'requires_maintenance',
                'maintenance_min_percentage',
                'max_semesters',
            ]);
        });
    }
}
