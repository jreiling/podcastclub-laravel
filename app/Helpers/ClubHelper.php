<?php

namespace App\Helpers;

use App\Club;
use App\User;
use App\Podcast;
use App\Helpers\MessageHelper;

class ClubHelper
{
  static function addClubMembersIfNotThere($club,$handles) {

    // See if any new users were added.
    foreach ($handles as $handle) {

      // See if that handle is already in the club.
      $handleIsInClub = ( $club->users()->where('handle' , $handle)->count() != 0 );

      // There's no user in this club.
      if ( !$handleIsInClub ) {

        $userAlreadyExists = ( User::where('handle' , $handle)->count() != 0 );

        if ($userAlreadyExists) {
          // We have a new user (to this club). Let's add them.
          $existingUser = User::where('handle' , $handle)->first();
          $club->users()->attach($existingUser);

          MessageHelper::sendNewClubWelcome($existingUser);

        } else {

          $newUser = new User();
          $newUser->handle = $handle;
          $newUser->first_name = "";
          $newUser->hash = hash('md5',$handle);
          $newUser->save();
          $club->users()->save($newUser);

          MessageHelper::sendNewUserWelcome($newUser);
        }
      }
    }
  }

    static function removeClubMembersIfNotThere($club,$handles) {

      // See if any users are missing and remove them.
      foreach ($club->users()->get() as $currentUser) {

        // Compare the Club's users with the incoming handle array.
        // If there are Club users who are NOT in the handle array, remove them.
        if ( !in_array($currentUser->handle,$handles) ) {

          // We have a user that was removed from this club.
          $club->users()->detach($currentUser);
        }
      }

    }

    static function renameClub($club,$proposedName) {

      // Rename the club, if necessary.
      if ( $proposedName == null ) {

        // There's no group name, so let's make one up.
        $users = $club->users()->get();
        $names = [];
        // Get all the first names and/or handles into an array.
        foreach ( $users as $user ) {
          $names[] = ($user->first_name == "") ? $user->handle : $user->first_name;
        }

        // Make a Club name by joining the names/handles of the users.
        $club->name = "Club with " . implode(', ', $names );
        $club->save();

      } else { // We have a proper name, so set it.

        $club->name = $proposedName;
        $club->save();
      }

    }



}
