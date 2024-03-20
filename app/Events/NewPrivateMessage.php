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
    public $RecruiterId;

    public $senderRole;
    public $messageTime;

    public function __construct($message, $EmployerId , $RecruiterId ,  $WorkerId , $senderRole , $messageTime)
    {
        $this->message = $message;
        $this->EmployerId = $EmployerId;
        $this->RecruiterId = $RecruiterId;
        $this->WorkerId = $WorkerId;        
        $this->senderRole = $senderRole;
        $this->messageTime = $messageTime;

    }

    public function broadcastOn()
    {
        $idEmployer = $this->EmployerId;
        $idWorker = $this->WorkerId;
        $idRecruiter = $this->RecruiterId;

        $privateChannel = 'private-chat.'.$idEmployer.'.'. $idRecruiter . '.'.$idWorker;
        return new PrivateChannel($privateChannel);
    }
}
