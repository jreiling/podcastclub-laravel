<?php

namespace App\Helpers;

use App\Club;
use App\User;
use App\Podcast;
use App\Events\MessageEvent;

class MessageHelper
{

  static function sendNewUserWelcome($user) {
    MessageHelper::sendMessage($user->handle,"Welcome, new user!");
  }

  static function sendNewClubWelcome($user) {
    MessageHelper::sendMessage($user->handle,"Welcome, to a new PodcastClub!");
  }

  static function sendPodcastToClub($club,$podcast) {

  }

  static function sendMessage($id,$message) {

    event(new MessageEvent($id,$message));
  }
}
