<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('admins')->truncate();
        DB::table('admins')->insert([
            [
                'role_id' => '1',
                'first_name' => 'Admin',
                'last_name' => 'Spaculus',
                'email' => 'admin@yopmail.com',
                'password' => bcrypt('admin@123'),
                'status' => 'active',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'type' => 'super'
            ]
        ]);

        
    }
}
