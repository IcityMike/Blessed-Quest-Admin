<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('site_title')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('footer_logo')->nullable();
            $table->string('site_favicon')->nullable();
            $table->string('email_address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('linkedin_link')->nullable();
            $table->string('about_banner')->nullable();
            $table->string('details_banner')->nullable();
            $table->string('about_banner_link')->nullable();
            $table->string('details_banner_link')->nullable();
            $table->bigInteger('type_of_voice_id')->nullable();
            $table->bigInteger('default_event_id')->nullable();
            $table->bigInteger('enquiry_admin')->nullable();
            $table->bigInteger('support_admin')->nullable();
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
        Schema::dropIfExists('external_settings');
    }
}
