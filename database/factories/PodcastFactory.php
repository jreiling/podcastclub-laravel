<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Club;
use App\User;
use App\Podcast;
use Faker\Generator as Faker;

$factory->define(Podcast::class, function (Faker $faker) {

  $randomClub = Club::all()->random();
  $randomUser = $randomClub->users->random();
  $order = $randomUser->podcasts->count();

  return [
    'uri' => $faker->url,
    'description' => $faker->catchPhrase,
    'artwork' => $faker->imageUrl,
    'order' => rand ( 0,1000 ),
    'club_id' => $randomClub->id,
    'user_id' => $randomUser->id
  ];
});

/*

$table->string('uri');
$table->string('description');
$table->string('artwork');
$table->boolean('shared')->default(false);
$table->boolean('duplicated')->default(false);
$table->integer('order');

*/
