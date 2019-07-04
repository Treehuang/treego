<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'username' => str_random(10),
        'email' => $faker->unique()->safeEmail,
        'sex' => 's',
        'campus' => 'b',
        'phone' => $faker->numberBetween(15626114752, 16000000000),
        'college' => str_random(10) . '学院',
        'introduction' => str_random(30),
        'avatar' => 'images/change/' . $faker->numberBetween(1, 30) . '.jpg',
        'state' => $faker->numberBetween(0, 1),
        'is_check' => 0,
        'is_manager' => $faker->numberBetween(0, 1),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
    ];
});
