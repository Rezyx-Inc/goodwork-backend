<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class NotificationOfferModel extends Model
{
    protected $connection = 'mongodb_notification';
    protected $collection = 'Offers'; // Assuming the collection name is 'Offers'

    protected $fillable = ['receiver', 'all_offers_notifs'];
}