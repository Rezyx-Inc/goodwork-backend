<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class weeklyCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weekly:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is for sending emails to workers on newly workers registered for the last 7 days, And this run on every monday 08:00 AM';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        exec('curl ' . url('cron-weekly-update'));
    }
}
