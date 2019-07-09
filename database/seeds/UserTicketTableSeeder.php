<?php

use Illuminate\Database\Seeder;

class UserTicketTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\UserTicket::class, 70)->create();
    }
}
