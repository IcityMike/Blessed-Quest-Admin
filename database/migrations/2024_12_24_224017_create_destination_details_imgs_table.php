<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDestinationDetailsImgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destination_details_imgs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('destination_details_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('img',255)->nullable();
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
        Schema::dropIfExists('destination_details_imgs');
    }
}
