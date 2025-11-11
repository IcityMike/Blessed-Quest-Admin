<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlessedLocationListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blessed_location_list', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('blessed_location_details_id')->nullable();
            $table->text('description')->nullable();
            $table->string('img',191)->nullable();
            $table->string('latitude',191)->nullable();
            $table->string('longitude',191)->nullable();
            $table->string('mile',191)->nullable();
            $table->string('name',191)->nullable();

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
        Schema::dropIfExists('blessed_location_list');
    }
}
