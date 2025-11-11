<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createMultiplePermission = [
            ['name' => 'Create','order' => '2'],
            ['module_id' => 'Edit','order' => '3'],
            ['module_id' => 'Delete','order' => '4'],
            ['module_id' => 'View','order' => '1'],
        ];

        DB::table('permissions')->insert($createMultiplePermission);

    }
}
