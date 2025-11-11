<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_user', function (Blueprint $table) {
            $table->id();
            $table->string('event_name',191)->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('event_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('user_song',255)->nullable();
            $table->bigInteger('library_id')->nullable();
            $table->bigInteger('admin_id')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
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
        Schema::dropIfExists('events_user');
    }
}
