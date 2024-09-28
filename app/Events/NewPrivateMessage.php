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
    public $OrganizationId;
    public $WorkerId;
    public $RecruiterId;

    public $senderRole;
    public $messageTime;
    public $type;
    public $fileName;


    public function __construct($message, $OrganizationId, $RecruiterId, $WorkerId, $senderRole, $messageTime, $type, $fileName)
    {
        $this->message = $message;
        $this->OrganizationId = $OrganizationId;
        $this->RecruiterId = $RecruiterId;
        $this->WorkerId = $WorkerId;
        $this->senderRole = $senderRole;
        $this->messageTime = $messageTime;
        $this->type = $type;
        $this->fileName = $fileName;



    }

    public function broadcastOn()
    {
        $idOrganization = $this->OrganizationId;
        $idWorker = $this->WorkerId;
        $idRecruiter = $this->RecruiterId;

        $privateChannel = 'private-chat.' . $idOrganization . '.' . $idRecruiter . '.' . $idWorker;
        return new PrivateChannel($privateChannel);
    }
}
