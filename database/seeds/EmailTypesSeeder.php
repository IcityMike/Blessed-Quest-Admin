<?php

use Illuminate\Database\Seeder;

class EmailTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createMultipleEmailTypes = [
            ['name' => 'Email verification'],
            ['name' => 'Reset password'],
            ['name' => 'Reset password notification'],
            ['name' => 'Change password notification'],
            ['name' => 'Support ticket submission'],
            ['name' => 'Support ticket reply submission'],
            ['name' => 'Subscription notification'],
            ['name' => 'Registration Welcome Email'],
            ['name' => 'Referral welcome email'],
            ['name' => 'Email update notification'],
            ['name' => 'Profile update notification'],
            ['name' => 'Employee welcome email'],
            ['name' => 'Client data update email'],
            ['name' => 'Client welcome email']
        ];

        DB::table('email_types')->insert($createMultipleEmailTypes);
    }
}
