<?php

use Illuminate\Database\Migrations\Migration;

class CreateFailedJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Kept only for compatibility with older migration histories.
        // The table is created by 0001_01_01_000002_create_jobs_table.php.
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
