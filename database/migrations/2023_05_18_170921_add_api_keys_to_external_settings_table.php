<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiKeysToExternalSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_settings', function (Blueprint $table) {
            $table->string('NIUM_X_API_KEY')->nullable();
            $table->string('SWIFTID_SECRET_KEY')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_settings', function (Blueprint $table) {
            $table->dropColumn(['NIUM_X_API_KEY','SWIFTID_SECRET_KEY']);
        });
    }
}
