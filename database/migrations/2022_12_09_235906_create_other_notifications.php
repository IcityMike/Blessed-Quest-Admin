<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('associated_id')->unsigned();
            $table->enum('associated_type',['support_reply','new_ticket','birthday_reminder','monoova_add_money','monoova_to_nium','sent_to_beneficiary','npp_return','npp_payment_status','direct_entry_dishonours']);
            $table->text('text');
            $table->integer('user_id')->unsigned();
            $table->enum('user_type',['A', 'C', 'R', 'E'])->comment('A - Aadmin, C - Client, R - Referral, E - Employee');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('other_notifications');
    }
}
