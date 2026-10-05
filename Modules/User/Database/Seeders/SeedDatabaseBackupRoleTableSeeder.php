<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SeedDatabaseBackupRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        $roles = [
            ['name' => 'Super Admin', 'user_type' => 'super_admin', 'key' => 'database_backup', 'value' => 'view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Super Admin', 'user_type' => 'super_admin', 'key' => 'database_backup', 'value' => 'add', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        DB::table('user_roles')->insert($roles);
    }
}
