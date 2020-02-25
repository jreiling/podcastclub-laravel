<?php

namespace App\Helpers;

use App\Club;
use App\User;
use App\Podcast;
use App\Events\MessageEvent;

class MessageHelper
{

  static function sendNewUserWelcome($user) {
    $dashboard = env('APP_URL', '') . '/members/' . $user->hash;
    MessageHelper::sendMessage($user->handle,"Why hello there! 👋👋👋 You've just been added to a Podcast Club. Whenever you find cool podcasts on Apple Podcasts, just text them to me and twice a week I'll share a new podcast with the Club. Find out more at your personal dashboard: $dashboard");
  }

  static function sendNewClubWelcome($user) {
    $dashboard = env('APP_URL', '') . '/members/' . $user->hash;
    MessageHelper::sendMessage($user->handle,"You've been added to another Podcast Club! 🎉 As a reminder, you can manage your podcasts here: $dashboard");
  }

  static function sendPodcastToClub($club,$podcast) {

    $user = $podcast->user;
    $memberNameOrHandle = $podcast->user->nameOrHandle();
    $uri = $podcast->uri;

    $message[] = "🚨🚨New podcast alert🚨🚨 Coming to you from 👉 $memberNameOrHandle $uri";
    $message[] = "Another day, another excellent podcast. (Thanks, $memberNameOrHandle!) $uri";
    $message[] = "Get your ears ready. It's podcast time. 🎧 (👊 $memberNameOrHandle) $uri";
    $message[] = "Look!  It's a podcast! And it's from $memberNameOrHandle 👀! $uri";

    $key = array_rand($message,1);
    MessageHelper::sendMessage($club->messages_chat_id, $message[$key]);

  }

  static function sendPodcastRecievedMessage($user) {
    $confirmationMessage[] = "Got it!";
    $confirmationMessage[] = "👌";
    $confirmationMessage[] = "🔥🔥🔥";
    $confirmationMessage[] = "Oh, I ❤️ that one.";
    $confirmationMessage[] = "🤖 Bleep blorp. Podcast received.⚡️⚡️⚡️";
    $confirmationMessage[] = "Proof you have excellent taste. 💅";

    $key = array_rand($confirmationMessage,1);
    MessageHelper::sendMessage($user->handle,$confirmationMessage[$key]);

  }

  static function sendMessage($id,$message) {

    event(new MessageEvent($id,$message));
  }
}
