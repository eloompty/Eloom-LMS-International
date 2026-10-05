<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddConfigurableAddressFieldsToAddressesTable extends Migration
{
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $columns = [
                'address_format',
                'address_line_1',
                'address_line_2',
                'building_name',
                'building_number',
                'flat_unit',
                'street_no',
                'street_address',
                'p_o_box',
                'suburb',
                'city',
                'state',
                'state_region',
                'county',
                'postal_code',
                'zip_code',
                'area',
                'emirate',
            ];

            foreach ($columns as $column) {
                if (!Schema::hasColumn('addresses', $column)) {
                    $table->string($column)->nullable()->after('address');
                }
            }
        });

        DB::table('settings')->updateOrInsert(
            ['key' => 'address_format'],
            ['value' => 'international', 'status' => 1, 'updated_at' => now(), 'created_at' => now()]
        );
    }

    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $columns = [
                'address_format',
                'address_line_1',
                'address_line_2',
                'building_name',
                'building_number',
                'flat_unit',
                'street_no',
                'street_address',
                'p_o_box',
                'suburb',
                'city',
                'state',
                'state_region',
                'county',
                'postal_code',
                'zip_code',
                'area',
                'emirate',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('addresses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
