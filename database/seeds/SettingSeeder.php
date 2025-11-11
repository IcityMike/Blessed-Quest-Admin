<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('external_settings')->insert([
            [
                'site_title' => 'Blessed Quest',
                'site_logo' => 'logo.png',
                'footer_logo' => 'footer_logo.png',
                'site_favicon' => 'favicon.png',
                'email_address' => 'info@moneyapp.com.au',
                'phone_number' => '1300 528 376',
                'address' => '6/96 Wigram Street,Harris Park NSW 2150',
                'support_admin' => '1'
            ]
        ]);
    }
}
