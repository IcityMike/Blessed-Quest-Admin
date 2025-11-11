<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserNotificationsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notifications_settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->enum('all_push_notifications',['0','1'])->default('0');
            $table->enum('traffic_route_updates',['0','1'])->default('0');
            $table->enum('receive_recommended_content',['0','1'])->default('0');
            $table->enum('reminders_credits_expire',['0','1'])->default('0');
            $table->enum('reminders_sub_autorenews',['0','1'])->default('0');
            $table->enum('notify_updates_changes_account',['0','1'])->default('0');
            $table->enum('notify_me_sub_and_sub_renewal_errors',['0','1'])->default('0');
            $table->enum('email_notifications',['0','1'])->default('0');
            $table->enum('receive_reminders_days_before_credits_expire_email',['0','1'])->default('0');
            $table->enum('reminders_sub_autorenews_email',['0','1'])->default('0');
            $table->enum('notify_updates_changes_account_email',['0','1'])->default('0');
            $table->enum('notify_me_sub_and_sub_renewal_errors_email',['0','1'])->default('0');

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
        Schema::dropIfExists('user_notifications_settings');
    }
}
