<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Certificat::class, function (Faker $faker) {
    return [
        'time' => '2014',
        'user_id' => rand(2, 70),
        'record' => 'u',
        'realname' => str_random(8),
        'certificat' => 'images/change/' . $faker->numberBetween(1, 30) . '.jpg',
    ];
});
