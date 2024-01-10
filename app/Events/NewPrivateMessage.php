<?php

// NewPrivateMessage.php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewPrivateMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $EmployerId;
    public $WorkerId;

    public $senderRole;

    public function __construct($message, $EmployerId,$WorkerId,$senderRole)
    {
        $this->message = $message;
        $this->EmployerId = $EmployerId;
        $this->WorkerId = $WorkerId;
        $this->senderRole = $senderRole;

    }

    public function broadcastOn()
    {
        $idEmployer = $this->EmployerId;
        $idWorker = $this->WorkerId;

        $privateChannel = 'private-chat.'.$idEmployer.'.'.$idWorker;
        return new PrivateChannel($privateChannel);
    }
}
