<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationJob implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $receiver;
    public $jobId;
    public $type;
    public $seen;
    public $sender;
    public $createdAt;
    public $full_name;
    
    public function __construct($type,$seen,$createdAt,$receiver,$sender,$full_name,$jobId)
    {
        $this->receiver = $receiver;
        $this->jobId = $jobId;
        $this->type = $type;
        $this->seen = $seen;
        $this->sender = $sender;
        $this->createdAt = $createdAt;
        $this->full_name = $full_name;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('private-job-notification.'.$this->receiver);
    }

    
}


