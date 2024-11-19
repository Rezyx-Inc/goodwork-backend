<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Chat extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'chat';
    public $timestamps = false;

    // protected $fillable = ['lastMessage', 'isActive', 'worker', 'recruiter', 'organization', 'messages'];
    protected $fillable = ['lastMessage', 'isActive', 'worker', 'recruiter', 'organization', 'messages', 'organizationId', 'workerId', 'recruiterId'];

    protected $casts = [
        'isActive' => 'boolean',

    ];
}
