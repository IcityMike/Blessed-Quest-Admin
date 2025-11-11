<?php

use Illuminate\Database\Seeder;

class SupportTicketStatus extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createMultipleStatus = [
            ['name' => 'Open','color'=> 'red','status' => '1','template' => 'Ticket status changed to open', 'created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s'), 'color_code' => '#FC2406'],
            ['name' => 'In Progress','color'=> 'blue','status' => '1','template' => 'Ticket status changed to in progress', 'created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s'), 'color_code' => '#526BDC'],
            ['name' => 'Closed','color'=> 'green','status' => '1','template' => 'Ticket status changed to closed', 'created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s'), 'color_code' => '#169F52'],
            ['name' => 'Re-open','color'=> 'orange','status' => '1','template' => 'Ticket status changed to Re-open', 'created_at' => date('Y-m-d H:i:s') , 'updated_at' => date('Y-m-d H:i:s'), 'color_code' => '#ffa500'],
        ];

        DB::table('support_ticket_status')->insert($createMultipleStatus);
    }
}
