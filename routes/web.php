<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Club;
use App\User;
use App\Podcast;
use GuzzleHttp\Client;
use Fusonic\OpenGraph\Consumer;
use App\Helpers\PodcastHelper;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/members/{memberHash}', function ($memberHash) {

  // A little less elegant than I'd like...
  $u = User::where('hash',$memberHash)->first();

  $user = User::where('hash',$memberHash)->with('clubs')->with(['clubs.podcasts' => function ($query) use ($u) {
      $query->where('user_id', $u->id);
      $query->orderBy('order', 'asc');
  }])->first();

  if (!$user) abort(404);

  if ($user->first_name == "" ) {
    return view('member-name', $user);
  } else {
    return view('member', $user);
  }

  return "";

});

Route::get('/clubs/{clubId}', function ($clubId) {
    // A little less elegant than I'd like...
    $club = Club::find($clubId)->with('users','podcasts')->first();
    return $club;

});

Route::get('/clubs/{clubId}/next',function($clubId) {

  $podcast = PodcastHelper::getNextPodcastForClub($clubId);

  if ($podcast) {
    PodcastHelper::successfullySentPodcast($podcast);
    return $podcast;
  } else {
    return "";
  }
});






/*
Route::get('/podcast2',function() {

  $podcastLink = "https://podcasts.apple.com/us/podcast/the-daily/id1200361736?i=1000457536548";

  $client = new Client();
  $res = $client->request('GET', $podcastLink);

  preg_match_all('/<script name="schema:podcast-episode" \b[^>]*>([\s\S]*?)<\/script>/',$res->getBody(), $output_array);

  $jsonOutput = json_decode($output_array[1][0]);
  echo $output_array[1][0];

  return $jsonOutput->name . " / " . $jsonOutput->creator;

});*/
