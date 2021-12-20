<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Historique;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(Historique::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement($array = array ('0', '1')),
        'user_mat' => 'S'.$faker->unique()->numberBetween($min = 1, $max = 200),
        'responsable' => $faker->name(),
    ];
});


