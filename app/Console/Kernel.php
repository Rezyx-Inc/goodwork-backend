<?php

namespace App\Console;

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
        Commands\weeklyCron::class,
        Commands\monthlyCron::class,
        Commands\BuildLocal::class,
        Commands\BuildRefresh::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /* $schedule->call(function () {
            exec('curl ' . url('cron-weekly-update'));
        })->weekly()->mondays()->at('08:00'); */
        $schedule->command('weekly:update')->weekly()->mondays()->at('08:00');
        $schedule->command('monthly:update')->monthlyOn(20, '10:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
