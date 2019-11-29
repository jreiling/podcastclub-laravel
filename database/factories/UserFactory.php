<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
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

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'handle' => $faker->phoneNumber,
        'hash' => hash("md5",Str::random(10))
    ];
});


/*
$member = new User;
$member->first_name = "Jon";
$member->handle = "+15555555555";
$member->hash = hash("md5",$member->handle);
$member->save();
*/
