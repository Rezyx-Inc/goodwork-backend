<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class BuildRefresh extends Command
{
    protected $signature = 'build:refresh';

    protected $description = 'Runs commands to refresh the application';

    public function handle()
    {
        // Fresh migrate and seed the database
        Artisan::call('migrate:fresh');
        Artisan::call('migrate --seed');

        // Clear configuration cache
        Artisan::call('config:clear');

        // Clear application cache
        Artisan::call('cache:clear');

        // Cache configuration
        Artisan::call('config:cache');

        // Clear compiled views
        Artisan::call('view:clear');

        // Create symbolic link for storage
        Artisan::call('storage:link');

         // Paasport key
         Artisan::call('passport:install');

         // generate the api key
         Artisan::call('apikey:generate goodworkapikey');

        $this->info('Application refreshed successfully.');
    }
}
