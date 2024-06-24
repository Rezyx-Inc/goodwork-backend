<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class monthlyCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is for sending emails to nurses reminding to add availability of next 3 months, And this run on every 20th of month at 10:00 AM';

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
        exec('curl ' . url('cron-monthly-update'));
    }
}
