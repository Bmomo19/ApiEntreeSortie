<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Historique;
use Faker\Generator as Faker;

$factory->define(Historique::class, function (Faker $faker) {
    return [
        'type' => '1',
        'user_mat' => 'S'.$faker->numberBetween($min = 1, $max = 20),
        'responsable' => $faker->name(),
    ];
});
