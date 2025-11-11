<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('site_title')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('site_favicon')->nullable();
            $table->string('email_address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('client_subscription_title')->nullable();
            $table->text('client_subscription_text')->nullable();
            $table->double('client_monthly_payment', 8, 2)->nullable();
            $table->string('payment_currency')->nullable();
            $table->integer('free_trial_period_in_months')->nullable();
            $table->enum('payment_mode',['sandbox','live'])->default('sandbox');
            $table->string('paypal_username')->nullable();
            $table->string('paypal_password')->nullable();
            $table->string('paypal_signature')->nullable();
            $table->integer('forum_admin')->nullable();
            $table->integer('support_admin')->nullable();
            $table->string('fsg_file')->nullable();
            $table->string('subscription_file')->nullable();
            $table->string('omnilife_advisor_id')->nullable();
            $table->string('omnilife_dealergroupintegrationkey')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
