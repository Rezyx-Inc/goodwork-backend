<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use DB;
use MongoDB\Client;

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

        // Passport key
        Artisan::call('passport:install');

        // Clear MongoDB database
        $this->clearMongoDB();

        $this->info('Application refreshed successfully.');
    }
    protected function clearMongoDB()
    {
        // Clear the chats MongoDB database
        $mongoUri = env('MONGODB_URI');
        $databaseName = env('YOUR_DATABASE_NAME');
        $mongoDB = new Client($mongoUri);
        $mongoDB->selectDatabase($databaseName)->drop();
    
        $this->info('Chat MongoDB database cleared.');
    
        // Clear the notifications MongoDB database
        $notificationDBName = env('NOTIFICATION_DB', 'Notifications');
        $mongoDB->selectDatabase($notificationDBName)->drop();
    
        $this->info('Notifications MongoDB database cleared.');
    }
}