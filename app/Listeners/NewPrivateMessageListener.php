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

        $employerId = $event->EmployerId;
        $recruiterId = $event->RecruiterId;
        $workerId = $event->WorkerId;
        $message = $event->message;
        $senderRole = $event->senderRole;
        $messageTime = $event->messageTime;
        $type = $event->type;
        $fileName = $event->fileName;

        
        // Find existing chat between the employer and worker
        $chat = Chat::where('employerId', $employerId)
                     ->where('workerId', $workerId)
                     ->where('recruiterId', $recruiterId)
                     ->first();
        
        if ($chat) {
            // If chat exists, add new message to the start of the messages array
            $newMessage = [
                'id' => uniqid(),
                'sender' => $senderRole,
                'type' => $type,
                'time' => $messageTime,
                'content' => $message,
                'fileName' => $fileName
            ];
            $chat->messages = collect($chat->messages)->prepend($newMessage)->all();
        } else {
            // If chat doesn't exist, create a new one
            $chat = new Chat;
            $chat->employerId = $employerId;
            $chat->workerId = $workerId;
            $chat->recruiterId = $recruiterId;
            $chat->messages = [
                [
                    'id' => uniqid(),
                    'sender' => $senderRole,
                    'type' => $type,
                    'time' => $messageTime,
                    'content' => $message,
                    'fileName' => $fileName
                ]
            ];
        }
        
        $chat->lastMessage = now()->toDateTimeString();
        $chat->isActive = true;
        $chat->save();

// store uncomming messages in the db

// $employerId = $event->EmployerId;
// $workerId = $event->WorkerId;
// $message = $event->message;
// $senderRole = $event->senderRole;
// $messageTime = $event->messageTime;

// $chat = Chat::firstOrCreate(
//     ['employerId' => $employerId, 'workerId' => $workerId],
//     ['lastMessage' => now()->toDateTimeString(), 'isActive' => true],
//     ['messages' => []],
// );

// $newMessage = [
//     'id' => uniqid(),
//     'sender' => $senderRole,
//     'type' => 'text',
//     // 'time' => now()->format('h:i A'),
//     'time' => $messageTime,
//     'content' => $message
// ];


// // Get the current messages, or initialize as an empty array if null
// $messages = $chat->messages ?? [];

// // Prepend the new message
// array_unshift($messages, $newMessage);

// // Set the messages attribute to the new array
// $chat->messages = $messages;

// $chat->lastMessage = now()->toDateTimeString();
// $chat->save();

        //broadcast(new NewPrivatePrivateMessage($message, $receiverId))->toOthers();
    }
}
