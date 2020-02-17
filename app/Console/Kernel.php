<?php

namespace App\Console;
use App\Club;
use App\User;
use App\Podcast;
use App\Helpers\PodcastHelper;
use App\Helpers\MessageHelper;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function () {

          $clubs = Club::all();

          foreach ($clubs as $club) {

            // Get the next podcast for that club.
            $podcast = PodcastHelper::getNextPodcastForClub($club->id);

            if ($podcast) {
              MessageHelper::sendPodcastToClub($club,$podcast);
              PodcastHelper::successfullySentPodcast($podcast);
            }
          }

//            DB::table('recent_users')->delete();
        })->dailyAt('13:00');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
