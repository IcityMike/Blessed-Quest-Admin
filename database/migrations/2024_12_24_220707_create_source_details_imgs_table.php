<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourceDetailsImgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('source_details_imgs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('source_details_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('image',199)->nullable();
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
        Schema::dropIfExists('source_details_imgs');
    }
}
