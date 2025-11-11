<?php

use Illuminate\Database\Seeder;

class AdminModulePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createMultipleModulesPermission = [
            ['module_id' => '1','permission_id' => '1'],
            ['module_id' => '1','permission_id' => '2'],
            ['module_id' => '1','permission_id' => '3'],
            ['module_id' => '1','permission_id' => '4'],

            ['module_id' => '2','permission_id' => '1'],
            ['module_id' => '2','permission_id' => '2'],
            ['module_id' => '2','permission_id' => '3'],
            ['module_id' => '2','permission_id' => '4'],

            ['module_id' => '3','permission_id' => '1'],
            ['module_id' => '3','permission_id' => '2'],
            ['module_id' => '3','permission_id' => '3'],
            ['module_id' => '3','permission_id' => '4'],

            ['module_id' => '4','permission_id' => '1'],
            ['module_id' => '4','permission_id' => '2'],
            ['module_id' => '4','permission_id' => '3'],
            ['module_id' => '4','permission_id' => '4'],

            ['module_id' => '5','permission_id' => '1'],
            ['module_id' => '5','permission_id' => '2'],
            ['module_id' => '5','permission_id' => '3'],
            ['module_id' => '5','permission_id' => '4'],

            ['module_id' => '6','permission_id' => '1'],
            ['module_id' => '6','permission_id' => '2'],
            ['module_id' => '6','permission_id' => '3'],
            ['module_id' => '6','permission_id' => '4'],

            ['module_id' => '7','permission_id' => '1'],
            ['module_id' => '7','permission_id' => '2'],
            ['module_id' => '7','permission_id' => '3'],
            ['module_id' => '7','permission_id' => '4'],

            ['module_id' => '8','permission_id' => '1'],
            ['module_id' => '8','permission_id' => '2'],
            ['module_id' => '8','permission_id' => '3'],
            ['module_id' => '8','permission_id' => '4'],

            ['module_id' => '9','permission_id' => '1'],
            ['module_id' => '9','permission_id' => '2'],
            ['module_id' => '9','permission_id' => '3'],
            ['module_id' => '9','permission_id' => '4'],

            ['module_id' => '10','permission_id' => '1'],
            ['module_id' => '10','permission_id' => '2'],
            ['module_id' => '10','permission_id' => '3'],
            ['module_id' => '10','permission_id' => '4'],

            ['module_id' => '11','permission_id' => '1'],
            ['module_id' => '11','permission_id' => '2'],
            ['module_id' => '11','permission_id' => '3'],
            ['module_id' => '11','permission_id' => '4'],

            ['module_id' => '12','permission_id' => '1'],
            ['module_id' => '12','permission_id' => '2'],
            ['module_id' => '12','permission_id' => '3'],
            ['module_id' => '12','permission_id' => '4'],

            ['module_id' => '13','permission_id' => '1'],
            ['module_id' => '13','permission_id' => '2'],
            ['module_id' => '13','permission_id' => '3'],
            ['module_id' => '13','permission_id' => '4'],

            ['module_id' => '14','permission_id' => '1'],
            ['module_id' => '14','permission_id' => '2'],
            ['module_id' => '14','permission_id' => '3'],
            ['module_id' => '14','permission_id' => '4'],

            ['module_id' => '15','permission_id' => '1'],
            ['module_id' => '15','permission_id' => '2'],
            ['module_id' => '15','permission_id' => '3'],
            ['module_id' => '15','permission_id' => '4'],

            ['module_id' => '16','permission_id' => '1'],
            ['module_id' => '16','permission_id' => '2'],
            ['module_id' => '16','permission_id' => '3'],
            ['module_id' => '16','permission_id' => '4'],

            ['module_id' => '17','permission_id' => '1'],
            ['module_id' => '17','permission_id' => '2'],
            ['module_id' => '17','permission_id' => '3'],
            ['module_id' => '17','permission_id' => '4'],
        ];
        // 15 - direct entry dishonours
        DB::table('admin_module_permissions')->insert($createMultipleModulesPermission);
    }
}
