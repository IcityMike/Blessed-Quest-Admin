<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientBeneficiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_beneficiaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('alias')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country_code')->nullable();
            $table->string('email')->nullable();
            $table->string('bank_account_type')->nullable();
            $table->string('account_type')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode');
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('identification_type')->nullable();
            $table->string('routing_code_type_1')->nullable();
            $table->string('routing_code_value_1')->nullable();
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
        Schema::dropIfExists('client_beneficiaries');
    }
}
