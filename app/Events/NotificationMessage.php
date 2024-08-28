<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationMessage  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $content;
    public $seen;
    public $createdAt;
    public $receiver;
    public $sender;
    public $full_name;

    public function __construct($content,$seen,$createdAt,$receiver,$sender,$full_name)
    {
        $this->content = $content;
        $this->seen = $seen;
        $this->createdAt = $createdAt;
        $this->receiver = $receiver;
        $this->sender = $sender;
        $this->full_name = $full_name;
    }
   

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('private-notification.'.$this->receiver);
    }
}
