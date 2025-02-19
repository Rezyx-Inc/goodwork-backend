<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\BuildLocal;
use App\Console\Commands\BuildRefresh;

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
        BuildLocal::class,
        BuildRefresh::class,
        Commands\dailyCron::class,
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
        $schedule->command('daily:update')->dailyAt('01:00');
        $schedule->command('backup:run')->dailyAt('02:00');
        $schedule->command('backup:clean')->dailyAt('02:30');
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
