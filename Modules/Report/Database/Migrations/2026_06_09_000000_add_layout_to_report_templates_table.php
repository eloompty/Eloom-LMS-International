<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLayoutToReportTemplatesTable extends Migration
{
    public function up()
    {
        Schema::table('report_templates', function (Blueprint $table) {
            $table->text('layout')->nullable()->after('footer');
        });
    }

    public function down()
    {
        Schema::table('report_templates', function (Blueprint $table) {
            $table->dropColumn('layout');
        });
    }
}
