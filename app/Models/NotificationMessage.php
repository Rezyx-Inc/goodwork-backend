<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class NotificationMessage extends Eloquent
{
    protected $connection = 'mongodb_notification';
    protected $collection = 'Messages';
    protected $fillable = ['receiver', 'all_messages_notifs'];
}
