<?php

namespace App\Console;

use App\Events\WeatherFetchEvent;
use App\Http\Middleware\ProfileJsonResponse;
use Cog\Laravel\Love\Console\Commands\Recount;
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
        Recount::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('forecast:get')->everySixHours();
         $schedule->command('reset:table weathers')->everySixHours();
         $schedule->command('forecast:peak')->everyFifteenMinutes();
         $schedule->command('price:check')->everyFifteenMinutes();
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
