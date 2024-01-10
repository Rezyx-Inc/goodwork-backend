<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Chat;
// use App\Events\NewPrivatePrivateMessage;

class NewPrivateMessageListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
    //     $emplyerId = $event->EmployerId;
    //     $workerId = $event->WorkerId;
    //     $message = $event->message;
    //     $senderRole = $event->senderRole;

    //     $chat = new Chat;
    // $chat->lastMessage = now();
    // $chat->isActive = true;
    // $chat->employerId = $emplyerId;
    // $chat->workerId = $workerId;
    // $chat->messages = [
    //     [
    //         'id' => uniqid(),
    //         'sender' => $senderRole,
    //         'type' => 'text',
    //         'time' => now(),
    //         'content' => $message
    //     ]
    // ];
    // $chat->save();

// store uncomming messages in the db

$employerId = $event->EmployerId;
$workerId = $event->WorkerId;
$message = $event->message;
$senderRole = $event->senderRole;

$chat = Chat::firstOrCreate(
    ['employerId' => $employerId, 'workerId' => $workerId],
    ['lastMessage' => now()->toDateTimeString(), 'isActive' => true]
);

$newMessage = [
    'id' => uniqid(),
    'sender' => $senderRole,
    'type' => 'text',
    // 'time' => now()->format('h:i A'),
    'time' => now()->toDateTimeString(),
    'content' => $message
];

$chat->push('messages', $newMessage);

$chat->lastMessage =now()->toDateTimeString();
$chat->save();

        //broadcast(new NewPrivatePrivateMessage($message, $receiverId))->toOthers();
    }
}
