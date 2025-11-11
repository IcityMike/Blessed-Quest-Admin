<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibrarysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('librarys', function (Blueprint $table) {
            $table->id();
            $table->string('name',191)->nullable();
            $table->text('mp3_file_name')->nullable();
            $table->bigInteger('events_id')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->bigInteger('admin_id')->nullable();
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
        Schema::dropIfExists('librarys');
    }
}
