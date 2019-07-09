<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\UserTicket::class, function (Faker $faker) {
    return [
        'user_id' => rand(3, 70),
        'ticket_id' => rand(1, 4),
        'leader' => str_random(8),
        'start_place' => str_random(8),
        'terminus' => str_random(8),
        'from_time' => '18:30:00',
        'arrival_time' => '23:30:00',
        'date' => '2019-09-10',
        'ticket_num' => rand(1, 4),
        'state' => 1,
        'price' => 102,
        'username' => str_random(8),
    ];
});
