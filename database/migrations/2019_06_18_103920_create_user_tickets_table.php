<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->tinyInteger('ticket_id');
            $table->string('leader');
            $table->string('start_place');
            $table->string('terminus');
            $table->string('from_time');
            $table->string('arrival_time');
            $table->date('date');
            $table->tinyInteger('ticket_num');
            $table->tinyInteger('state');
            $table->integer('price');
            $table->timestamps();
            $table->index(['user_id', 'state', 'ticket_id', 'terminus']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_tickets');
    }
}
