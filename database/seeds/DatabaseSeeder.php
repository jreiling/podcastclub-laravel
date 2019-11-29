<?php

use Illuminate\Database\Seeder;
use App\Club;
use App\User;
use App\Podcast;
use App\Helpers\PodcastHelper;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

      // Create our users
      factory(App\User::class, 50)->create();

      // Create random clubs and randomly assign some users
      factory(App\Club::class, 10)->create()->each(function ($club) {
        $users = User::all()->random(3);

        foreach ($users as $user ) {
          $club->users()->save($user);
        }
      });

      // Create our podcasts.
      factory(App\Podcast::class, 100)->create();

      foreach (User::all() as $user) {
        PodcastHelper::orderPodcastsForUser($user);
      }



/*
        // $this->call(UsersTableSeeder::class);
        $club = new Club;
        $club->name = "Test Club";
        $club->messages_chat_id = 1;
        $club->save();

        $club2 = new Club;
        $club2->name = "Test Club 2";
        $club2->messages_chat_id = 1;
        $club2->save();

        $member = new User;
        $member->first_name = "Jon";
        $member->handle = "+15555555555";
        $member->hash = hash("md5",$member->handle);
        $member->save();

        $club->users()->save($member);
        $club2->users()->save($member);

        $podcast = new Podcast;
        $podcast->uri = "http://test.com/";
        $podcast->club_id = $club->id;
        $podcast->description = "The Daily";
        $podcast->artwork = "hi";
        $podcast->order = 1;

        $member->podcasts()->save($podcast);
        $club->podcasts()->save($podcast);

        $podcast->save();

        $podcast2 = new Podcast;
        $podcast2->uri = "http://test.com/";
        $podcast2->club_id = $club->id;
        $podcast2->description = "This american life";
        $podcast2->artwork = "hi";
        $podcast2->order = 0;

        $member->podcasts()->save($podcast2);
        $club->podcasts()->save($podcast2);

        $podcast->save();*/

    }
}
