<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailRegardsNameToEmailUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_users', function (Blueprint $table) {
            $table->string('email_regards_name')->nullable()->after('email_content');
            $table->string('email_regards_position')->nullable()->after('email_regards_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_users', function (Blueprint $table) {
            $table->dropColumn('email_regards_name');
            $table->dropColumn('email_regards_position');
        });
    }
}
