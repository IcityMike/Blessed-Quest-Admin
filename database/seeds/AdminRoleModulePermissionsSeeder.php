<?php

use Illuminate\Database\Seeder;

class AdminRoleModulePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createMultipleModules = [
            ['role_id' => 1, 'module_permission_id' => 9,'created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['role_id' => 1, 'module_permission_id' => 10,'created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['role_id' => 1, 'module_permission_id' => 11,'created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['role_id' => 1, 'module_permission_id' => 12,'created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
        ];

        DB::table('admin_role_module_permissions')->insert($createMultipleModules);
    }
}
