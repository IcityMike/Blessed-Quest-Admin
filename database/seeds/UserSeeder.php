<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'jek',
            'email' => 'jek@yopmail.com',
            'password' => Hash::make('123456789'),
            'password' => Hash::make('123456789')
        ]);
    }
}
