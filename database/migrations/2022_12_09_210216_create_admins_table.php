<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->enum('default_dashboard',['admin','staff'])->default('admin');
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();
        });


        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->string('name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('profile_picture')->nullable();
            //$table->string('email')->unique();
            $table->string('email')->unique()->charset('utf8');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->enum('gender',['male','female'])->nullable();
            $table->enum('type',['super','sub'])->nullable();
            $table->bigInteger('added_by')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('admins_password_resets', function (Blueprint $table) {
            $table->string('email');
            $table->string('token');
            $table->timestamp('created_at');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins_password_resets');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('roles');
    }
}
