<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(AdminModuleSeeder::class);
        $this->call(AdminModulePermissionSeeder::class);
        $this->call(AdminRoleModulePermissionsSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(EmailTemplateSeeder::class);
        $this->call(EmailTypesSeeder::class);
        $this->call(SupportTicketCategory::class);
        $this->call(SupportTicketStatus::class);
        $this->call(CurrencySettingsTableSeeder::class);
        $this->call(PurposeCodesTableSeeder::class);
    }
}
