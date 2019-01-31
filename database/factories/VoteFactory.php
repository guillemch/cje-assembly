<?php

use Faker\Generator as Faker;

$factory->define(App\Vote::class, function (Faker $faker) {
    return [
        'amendment_id' => 0,
        'vote_for' => rand(1, 3),
        'ip' => $faker->ipv4,
        'ua' => $faker->userAgent
    ];
});
