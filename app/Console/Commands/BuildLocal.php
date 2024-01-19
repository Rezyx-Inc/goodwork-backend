<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class BuildLocal extends Command
{
    protected $signature = 'build:local';

    protected $description = 'Runs commands to build for local environment';

    public function handle()
    {
        // Migrate and seed the database
        Artisan::call('migrate --seed');

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

        $this->info('Local environment built successfully.');
    }
}
