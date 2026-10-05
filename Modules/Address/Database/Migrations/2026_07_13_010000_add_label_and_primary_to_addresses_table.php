<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLabelAndPrimaryToAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('label')->nullable()->after('type_id');
            $table->boolean('is_primary')->default(false)->after('label');
        });

        // Existing students have exactly one address; make it the primary/current one
        // so primaryAddress() and the repointed address() relation keep resolving to it.
        DB::table('addresses')
            ->where('type', 'student')
            ->update(['is_primary' => true, 'label' => 'Current']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['label', 'is_primary']);
        });
    }
}
