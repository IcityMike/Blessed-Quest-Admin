<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailHistoryLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('email_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('email_history_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->enum('user_type',['A', 'C', 'R', 'E', 'AD'])->comment('A - Aadmin, C - Client, R - Referral, E - Employee, AD - Advisor');
            $table->integer('email_type')->unsigned();
            $table->bigInteger('associated_id')->nullable();
            $table->enum('associated_type',['ST','STR','PSR','USR','PRS','PRCS','BRC','BRR','DN','LN','WR','ETF','LIC','MF','CR','SEC','FI','MP','IMP','EMP','LMP','MFP','EQP'])->nullable();
            $table->text('attachments')->nullable();
            $table->longText('content')->nullable();
            $table->string('subject')->nullable();
            $table->string('sent_to')->nullable();
            $table->timestamp('sent_on')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('email_type')->references('id')->on('email_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_history_log');
        Schema::dropIfExists('email_types');
    }
}
