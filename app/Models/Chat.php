<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Chat extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'chat';

    protected $fillable = [
        'lastMessage', 
        'isActive', 
        'worker', 
        'recruiter', 
        'organization', 
        'messages', 
        'organizationId', 
        'workerId', 
        'recruiterId'
    ];

    protected $casts = [
        'isActive' => 'boolean',
    ];
}
