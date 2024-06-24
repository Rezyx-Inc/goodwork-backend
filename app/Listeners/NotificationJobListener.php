<?php

namespace App\Listeners;

use App\Events\NotificationJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\NotificationJobModel;


class NotificationJobListener
{
    /**
     * Handle the event.
     *
     * @param  NotificationJob  $event
     * @return void
     */
    public function handle(NotificationJob $event)
    {
        $newContent = [
            'id' => uniqid(),
            'jobId' => $event->jobId,
            'createdAt' => $event->createdAt,
            'seen' => $event->seen,
            'type' => $event->type,
            'sender' => $event->sender,
        ];

        $receiver = $event->receiver;

        // Attempt to add the new notification for an existing receiver
        $updateResult = NotificationJobModel::raw()->updateOne(
            // the condition 
            ['receiver' => $receiver, 'all_jobs_notifs.sender' => $event->sender],
            // the update (adding new notif)
            [
                '$push' => [
                    'all_jobs_notifs.$.notifs_of_one_sender' => [
                        '$each' => [$newContent],
                        '$position' => 0,
                        '$slice' => 100 // Adjust the slice value as needed
                    ]
                ]
            ]
        );

        // If the receiver does not exist in any document, add a new receiver with the notification
        if ($updateResult->getModifiedCount() == 0) {
            NotificationJobModel::raw()->updateOne(
                // the condtion
                ['receiver' => $receiver],
                [
                    // the update (adding new receiver with the notification)
                    '$push' => [
                        'all_jobs_notifs' => [
                            'id' => uniqid(),
                            'sender' => $event->sender,
                            'notifs_of_one_sender' => [$newContent]
                        ]
                    ]
                ],
                ['upsert' => true]
            );
        }
        
    }
}

