<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\UpdateLastLoggedInAt',
        ],
        'Illuminate\Auth\Events\Lockout' => [
            'App\Listeners\LogLockout',
        ],'App\Events\UserCreated' => [
            'App\Listeners\GenerateJwtToken',
        ] ,'App\Events\NewPrivateMessage' => [
            'App\Listeners\NewPrivateMessageListener',
        ],
        'App\Events\NotificationMessage' => [
            'App\Listeners\NotificationMessageListener',
        ],
        'App\Events\NotificationJob' => [
            'App\Listeners\NotificationJobListener',
        ],
        'App\Events\NotificationOffer' => [
            'App\Listeners\NotificationOfferListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
