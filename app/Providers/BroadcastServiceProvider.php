<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();
        Broadcast::channel('private-chat.{receiverId}', function ($user, $receiverId) {
            // Replace this with your actual authorization logic
            return $user->id === $receiverId;
        });

        Broadcast::channel('private-notification.{receiverId}', function ($user, $receiverId) {
            
            return $user->id === $receiverId;
        });

        Broadcast::channel('private-job-notification.{receiverId}', function ($user, $receiverId) {
           
            return $user->id === $receiverId;
        });


        require base_path('routes/channels.php');
    }
}
