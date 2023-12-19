<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class GenerateJwtToken implements ShouldQueue
{
    public function __construct()
    {

    }

    public function handle(UserCreated $event)
    {

        $user = $event->user;
        $tokenResult = $user->createToken('authToken')->accessToken;
    

    }
}
