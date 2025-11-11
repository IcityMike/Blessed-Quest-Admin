<?php

use Illuminate\Database\Seeder;

class CurrencySettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currency_settings')->insert([
            [
                'to_currency' => 'INR',
                'to_currency_code' => NULL,
                'from_currency' => 'AUD',
                'from_currency_code' => NULL,
                'current_rate' => 54.03,
                'expiry_current_rate' => date('Y-m-d H:i:s'),
                'status' => 'active',
                'currency_id' => NULL,
                'margin' => 2,
                'from_country_full_name' => 'Austraila',
                'to_country_full_name' => 'India'
            ],
            [
                'to_currency' => 'NPR',
                'to_currency_code' => NULL,
                'from_currency' => 'AUD',
                'from_currency_code' => NULL,
                'current_rate' => 55.03,
                'expiry_current_rate' => date('Y-m-d H:i:s'),
                'status' => 'active',
                'currency_id' => NULL,
                'margin' => 2,
                'from_country_full_name' => 'Austraila',
                'to_country_full_name' => 'Nepal'
            ],
            [
                'to_currency' => 'LKR',
                'to_currency_code' => NULL,
                'from_currency' => 'AUD',
                'from_currency_code' => NULL,
                'current_rate' => 56.03,
                'expiry_current_rate' => date('Y-m-d H:i:s'),
                'status' => 'active',
                'currency_id' => NULL,
                'margin' => 2,
                'from_country_full_name' => 'Austraila',
                'to_country_full_name' => 'Sri Lanka'
            ],
            [
                'to_currency' => 'BDT',
                'to_currency_code' => NULL,
                'from_currency' => 'AUD',
                'from_currency_code' => NULL,
                'current_rate' => 57.03,
                'expiry_current_rate' => date('Y-m-d H:i:s'),
                'status' => 'active',
                'currency_id' => NULL,
                'margin' => 2,
                'from_country_full_name' => 'Austraila',
                'to_country_full_name' => 'Bangladesh'
            ]
        ]);

        
    }
}
