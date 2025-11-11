<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectEntryDishonoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direct_entry_dishonours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('ReturnDate')->nullable();
            $table->double('Amount',8,2)->nullable();
            $table->string('Bsb')->nullable();
            $table->string('AccountNumber')->nullable();
            $table->string('AccountName')->nullable();
            $table->string('Token')->nullable();
            $table->string('Type')->nullable();
            $table->text('ReturnReason')->nullable();
            $table->timestamp('TransactionDate')->nullable();
            $table->string('OriginalTransactionId')->nullable();
            $table->text('TransactionReference')->nullable();
            $table->string('request_ip')->nullable();
            $table->string('request_url')->nullable();
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
        Schema::dropIfExists('direct_entry_dishonours');
    }
}
