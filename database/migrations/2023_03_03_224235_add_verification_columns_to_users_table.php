<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerificationColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('isEmailVerifed',['0','1'])->default('0');
            $table->enum('isPhoneNumberVerified',['0','1'])->default('0');
            $table->integer('email_verification_otp')->nullable();
            $table->integer('phone_no_verification_otp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['isEmailVerifed','isPhoneNumberVerified','email_verification_otp','phone_no_verification_otp']);
        });
    }
}
