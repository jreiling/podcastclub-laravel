<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Club;
use Faker\Generator as Faker;

$factory->define(Club::class, function (Faker $faker) {
    return [
      'name' => $faker->company,
      'messages_chat_id' => Str::random(10)
    ];
});


/*



*/
