<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(User::class, function (Faker $faker) {

    $mat = 'S'.$faker->unique()->numberBetween($min = 1, $max = 200);
    $path = asset('img/qr_code/'.$mat.'_qr.png');
    return [
        'matricule' => $mat,
        'nom' => $faker->firstName(),
        'prenoms' => $faker->lastName(),
        'type' => $faker->randomElement($array = array ('Personnel','Academicien','OCI', 'Visiter')),
        'login' => $faker->userName(),
        'password' => Hash::make('password'),
        'motif' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'photo' => $path,
        'tel' => $faker->e164PhoneNumber()

    ];
});
