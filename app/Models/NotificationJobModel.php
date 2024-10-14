<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class NotificationJobModel extends Model
{
    protected $connection = 'mongodb_notification';
    protected $collection = 'Jobs';

    protected $fillable = ['receiver', 'all_jobs_notifs'];
}
