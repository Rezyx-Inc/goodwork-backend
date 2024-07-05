<?php

namespace App\Listeners;

use App\Events\NotificationOffer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\NotificationOfferModel;

class NotificationOfferListener
{
    /**
     * Handle the event.
     *
     * @param  NotificationOffer  $event
     * @return void
     */
    public function handle(NotificationOffer $event)
    {
        $newContent = [
            'id' => uniqid(),
            'jobId' => $event->jobId,
            'offer_id' => $event->offerId,
            'createdAt' => $event->createdAt,
            'job_name' => $event->job_name,
            'full_name' => $event->full_name,
            'seen' => $event->seen,
            'type' => $event->type,
            'createdAt' => $event->createdAt,
        ];

        $receiver = $event->receiver;

        $updateResult = NotificationOfferModel::raw()->updateOne(
            // the condition 
            ['receiver' => $receiver, 'all_offers_notifs.sender' => $event->sender],
            // the update (adding new notif)
            [
                '$push' => [
                    'all_offers_notifs.$.notifs_of_one_sender' => [
                        '$each' => [$newContent],
                        '$position' => 0,
                        '$slice' => 1 
                    ]
                ]
            ]
        );

        // If the receiver does not exist in any document, add a new receiver with the notification
        if ($updateResult->getModifiedCount() == 0) {
            NotificationOfferModel::raw()->updateOne(
                // the condition
                ['receiver' => $receiver],
                // the update (adding new receiver with the notification)
                [
                    '$push' => [
                        'all_offers_notifs' => [
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