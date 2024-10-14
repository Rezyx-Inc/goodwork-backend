<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class NotificationMessage extends Model
{
    protected $connection = 'mongodb_notification';
    protected $collection = 'Messages';

    // Update the fillable attributes to match the document structure
    protected $fillable = ['receiver', 'all_messages_notifs'];

    
}