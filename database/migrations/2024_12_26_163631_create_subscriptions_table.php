<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('subscription_type',191)->nullable();
            $table->string('services',255)->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->string('title',191)->nullable();
            $table->string('sub_title',191)->nullable();
            $table->integer('amount')->nullable();
            $table->integer('per_year_amount')->nullable();
            $table->string('description',255)->nullable();
            $table->string('detail_description',255)->nullable();
            $table->string('detail_page_message',191)->nullable();
            $table->string('try_bottom_button_text',191)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
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
        Schema::dropIfExists('subscriptions');
    }
}
