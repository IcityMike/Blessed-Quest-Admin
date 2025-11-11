<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('beneficiar_user_id')->nullable();
            $table->string('beneficiar_name')->nullable();
            $table->string('beneficiar_email')->nullable();
            $table->string('beneficiary_contact_number')->nullable();
            $table->string('country_code')->nullable();
            $table->string('beneficiar_account_number')->nullable();
            $table->string('beneficiar_bank_name')->nullable();
            $table->string('beneficiar_bank_code')->nullable();
            $table->string('beneficiar_account_type')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_phone_number')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_reference_number')->nullable();
            $table->string('transaction_status')->nullable();
            $table->timestamp('transaction_created_at')->nullable();
            $table->timestamp('transaction_updated_at')->nullable();
            $table->timestamp('transaction_hold_fx_expires_on')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
