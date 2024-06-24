<?php

namespace App\Listeners;
use App\Models\NotificationMessage as NotificationMessageModel;

class NotificationMessageListener
{
    public function handle($event)
    {
        $newContent = [
            'id' => uniqid(),
            'content' => $event->content,
            'createdAt' => $event->createdAt,
            'seen' => $event->seen,
        ];

        $receiver = $event->receiver;
        $sender = $event->sender;
        $full_name = $event->full_name;

        // Attempt to add the new notification for an existing sender
        $updateResult = NotificationMessageModel::raw()->updateOne(
            // the condition 
            ['receiver' => $receiver, 'all_messages_notifs.sender' => $sender,'all_messages_notifs.full_name' => $full_name],
            // the update (adding new notif)
            [
                '$push' => [
                    'all_messages_notifs.$.notifs_of_one_sender' => [
                        '$each' => [$newContent],
                        '$position' => 0,
                        '$slice' => 100 // Adjust the slice value as needed
                    ]
                ]
            ]
        );

        // If the sender does not exist in any document, add a new sender with the notification
        if ($updateResult->getModifiedCount() == 0) {
            NotificationMessageModel::raw()->updateOne(
                // the condtion
                ['receiver' => $receiver],
                [
                    // the update (adding new sender with the notification)
                    '$push' => [
                        'all_messages_notifs' => [
                            'id' => uniqid(),
                            'sender' => $sender,
                            'full_name' => $full_name,
                            'notifs_of_one_sender' => [$newContent]
                        ]
                    ]
                ],
                ['upsert' => true]
            );
        }
    }
}