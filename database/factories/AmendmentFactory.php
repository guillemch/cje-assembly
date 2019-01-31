<?php

use Faker\Generator as Faker;

$factory->define(App\Amendment::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(4, true),
        'option_1' => 'Sí',
        'option_2' => 'No',
        'option_3' => 'Abstención',
        'open' => 0
    ];
});
