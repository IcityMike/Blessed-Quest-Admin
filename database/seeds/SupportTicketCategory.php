<?php

use Illuminate\Database\Seeder;

class SupportTicketCategory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('support_ticket_categories')->insert([
            [
                'name' => 'category1',
                'admin_id' => '1',
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }
}
