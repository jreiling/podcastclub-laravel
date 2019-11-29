<?php

use Illuminate\Http\Request;
use App\Club;
use App\User;
use App\Podcast;
use App\Helpers\PodcastHelper;
use App\Helpers\ClubHelper;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/handle-message',function(Request $request) {

  $errorState = '{"success":false}';
  $successState = '{"success":true}';

  // Check to make sure we have the right parameters.
  if ( !$request->has('text','handle') ) return $errorState;

  // Check to see if the handle exists within the users table
  if ( User::where( 'handle' , $request->input('handle') )->count() == 0 ) return $errorState;

  // Check to make sure this isn't a message sent to a group.
  if ( !$request->input('group') == null ) return $errorState;

  // Check to make sure this isn't a message sent by the system.
  if ( $request->input('fromMe') ) return $errorState;

  // Check to make sure it's just a link.
  $podcastValidator = '/(https:\/\/podcasts.apple.com\/us\/podcast\/)(.*?)?i=[0-9]*$/';
  if ( !preg_match($podcastValidator, $request->input('text'))) return $errorState;

  // We made it! Now let's add the podcast.
  $user = User::where( 'handle' , $request->input('handle') )->first();
  PodcastHelper::addPodcastForUser($user,$request->input('text'));

  return $successState;

});


Route::post('/handle-group-update',function(Request $request) {

  $errorState = '{"success":false}';
  $successState = '{"success":true}';


  // Validate input.
  if ( !$request->has('id','handles') ) return $errorState;

  // Fetch the relevant club.
  $club = Club::firstOrCreate(['messages_chat_id' => $request->input('id')]);

  ClubHelper::addClubMembersIfNotThere($club,$request->input('handles'));
  ClubHelper::removeClubMembersIfNotThere($club,$request->input('handles'));
  ClubHelper::renameClub($club,$request->input('name'));

  return $club;
});


Route::post('/update-podcast-order',function(Request $request) {

  $errorState = '{"success":false}';
  $successState = '{"success":true}';

  // Validate input.
  if ( !$request->has('podcasts') ) return $errorState;

  $podcasts = $request->input('podcasts');

  $order = 0;
  foreach ($podcasts as $podcast) {

    $p = Podcast::find($podcast['id']);
    $p->order = $order;
    $p->save();

    $order++;
  }
  return $successState;
});

Route::post('/delete-podcast',function(Request $request) {

  $errorState = '{"success":false}';
  $successState = '{"success":true}';

  // Validate input.
  if ( !$request->has('id') ) return $errorState;
  $p = Podcast::find($request->input('id'));
  $user = User::find($request->input('user_id'));
  $p->delete();

  PodcastHelper::orderPodcastsForUser($user);

  return $successState;
});

Route::post('/member-data', function (Request $request) {

  $memberHash = $request->input('memberHash');
  // A little less elegant than I'd like...
  $u = User::where('hash',$memberHash)->first();

  $user = User::where('hash',$memberHash)->with('clubs')->with(['clubs.podcasts' => function ($query) use ($u) {
      $query->where('user_id', $u->id);
      $query->orderBy('order', 'asc');
  }])->first();

  if (!$user) abort(404);

  return $user;

});

Route::post('/member-name', function (Request $request) {

  $memberHash = $request->input('memberHash');
  if (!$request->has('first_name')) return abort(404);

  // A little less elegant than I'd like...
  $user = User::where('hash',$memberHash)->first();
  if (!$user) abort(404);

  $user->first_name = $request->input('first_name');
  $user->save();

  return redirect('/members/'.$memberHash.'/');

});


/*

{
    text: 'Hello, world!',
    handle: '+15555555555',
    group: null,
    date: '2017-04-11T02:02:13.000Z',
    fromMe: false,
    guid: 'F79E08A5-4314-43B2-BB32-563A2BB76177'
}
*/
