<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Blessed Quest',
            'default_dashboard' => 'admin',
            'status' => 'active',
            'created_at' => date('Y-md H:i:s')
        ]);
    }
}
