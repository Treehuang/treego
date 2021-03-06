<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('username');
            $table->string('sex')->nullable()->default('s');
            $table->string('campus')->nullable()->default('b');
            $table->string('phone')->unique()->nullable();
            $table->string('avatar');
            $table->string('college')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('introduction')->nullable();
            $table->string('password');
            $table->tinyInteger('state')->default(1);
            $table->tinyInteger('is_check')->default(0);
            $table->tinyInteger('is_manager')->default(0);
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
        Schema::dropIfExists('users');
    }
}
