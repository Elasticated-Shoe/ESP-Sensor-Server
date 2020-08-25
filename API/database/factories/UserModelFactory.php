<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
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

$factory->define('App\users', function ($faker) {
    return [
        'userEmail' => $faker->email,
        'userPass' => '$2y$12$ULxp625mVQGKiXO8BLwGB.BhmWCb7LpyYn4KtnP.bLRfUM456Z5ei', // password
        'isAdmin' => true,
        'isLocked' => false,
    ];
});
