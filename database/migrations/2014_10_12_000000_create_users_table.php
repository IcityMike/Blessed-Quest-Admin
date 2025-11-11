<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            //$table->string('email')->unique();
            //$table->string('email', 255)->unique()->change();
            $table->string('email')->unique()->charset('utf8');
            $table->string('language',191)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone_number')->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->string('target',50)->nullable();
            $table->string('state')->nullable();
            $table->bigInteger('default_img_id')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_address_line1')->nullable();
            $table->string('postal_address_line2')->nullable();
            $table->enum('gender',['M','F'])->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('referral_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->enum('status',['active','inactive','block'])->default('active');
            $table->string('weight',100)->nullable();
            $table->string('tall',100)->nullable();
            $table->bigInteger('reffered_by')->nullable();
            $table->string('google_id')->nullable();
            $table->string('facebook_id')->nullable();
            $table->dateTime('task_create_date')->nullable();
            $table->string('postal_address',255)->nullable();
            $table->enum('login_type',['1','2','0'])->nullable();
            $table->string('otp',191)->nullable();
            $table->string('bsb_number',191)->nullable();
            $table->timestamp('otp_created_at')->nullable();
            $table->string('bank_account_name',255)->nullable();
            $table->bigInteger('default_prayer_id')->nullable();
            $table->string('clientUniqueId',255)->nullable();
            $table->enum('isEmailVerifed',['0','1'])->default('0');
            $table->bigInteger('type_of_voice')->nullable();
            $table->enum('isPhoneNumberVerified',['0','1'])->default('0');
            $table->bigInteger('email_verification_otp')->nullable();
            $table->bigInteger('phone_no_verification_otp')->nullable();
            $table->enum('profile_status',['hide','show'])->nullable();
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
        Schema::dropIfExists('users');
    }
}
