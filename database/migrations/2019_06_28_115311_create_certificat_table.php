<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificat', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('realname');
            $table->string('certificat');
            $table->string('record');
            $table->string('time');
            $table->timestamps();
            $table->tinyInteger('audit_state')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificat', function (Blueprint $table) {
            Schema::dropIfExists('certificat');
        });
    }
}
