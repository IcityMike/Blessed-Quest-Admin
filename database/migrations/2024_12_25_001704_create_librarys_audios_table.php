<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibrarysAudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('librarys_audios', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('library_id')->nullable();
            $table->bigInteger('voice_type')->nullable();
            $table->string('mp3_file_name',199)->nullable();
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
        Schema::dropIfExists('librarys_audios');
    }
}
