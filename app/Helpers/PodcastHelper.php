<?php

namespace App\Helpers;

use App\Club;
use App\User;
use App\Podcast;
use Fusonic\OpenGraph\Consumer;

class PodcastHelper
{
  static function getNextPodcastForClub($clubId) {

    $podcast = Podcast::where('podcasts.club_id',$clubId)
                        ->join('club_user', function ($join) {
                            $join->on('podcasts.user_id', '=', 'club_user.user_id')
                                  ->on('podcasts.club_id', '=', 'club_user.club_id');

                        })
                        ->select('podcasts.*','last_submission')
                        ->orderBy('last_submission','asc')
                        ->orderBy('order','asc')
                        ->first();

      return $podcast;
  }

  static function successfullySentPodcast($podcast) {

    // Update when the user sent a podcast for a particular group.
    $date = new \DateTime();
    $pivot = \DB::table('club_user')
                ->where('user_id',$podcast->user_id)
                ->update(['last_submission' => $date->format('Y-m-d H:i:s')]);


  //  echo $pivot;
  //  $podcast = $podcasts[0];
    $podcast->delete();

    PodcastHelper::orderPodcastsForUser(User::find($podcast->user_id));
  }

  static function orderPodcastsForUser($user) {

    $userClubs = $user->clubs;

    foreach ($userClubs as $userClub) {
      $podcasts = $user->podcasts->where('club_id',$userClub->id);

      $i = 0;
      foreach ($podcasts as $podcast) {
        $podcast->order = $i;
        $podcast->update();
        $i++;
      }

    }
  }

  static function addPodcastForUser($user,$uri) {

    // Fetch metadata about the podcast.
    $consumer = new Consumer();
    $object = $consumer->loadUrl($uri);
    $description = str_replace( " on Apple Podcasts", "" , $object->title);
    $artwork = str_replace( "wp.png", ".png", $object->images[0]->url );

    // Get our objects in order.
    $clubs = $user->clubs()->get();

    // Iterate through the clubs that the user belongs to and add this podcast to each.
    foreach ($clubs as $club) {

      $podcast = new Podcast;
      $podcast->uri = $uri;
      $podcast->club_id = $club->id;
      $podcast->description = $description;
      $podcast->artwork = $artwork;

      // Set the order to the end.
      $podcast->order = Podcast::where('user_id',$user->id)
                                ->where('club_id',$club->id)
                                ->count();

      // Save everything.
      $user->podcasts()->save($podcast);
      $club->podcasts()->save($podcast);
      $podcast->save();

    }

  }

}
