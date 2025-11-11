<?php

use Illuminate\Database\Seeder;

class AdminModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createMultipleModules = [
            ['name' => 'Admin users', 'created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Clients', 'created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Roles & Permissions', 'created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Support', 'created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Support ticket category', 'created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Settings', 'created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Email Templates','created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => ' Refferal Partners','created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Currency Settings','created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Add Money In Nium Account','created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            // ['name' => 'Transaction History - send money','created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Transaction History - Recieved money','created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Transactions','created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Activity log','created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Beneficiars','created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Direct Entry Dishonours','created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'NPP Returns','created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Purpose Codes','created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s')],
            
            
        ];

        DB::table('admin_modules')->insert($createMultipleModules);
    }
}
