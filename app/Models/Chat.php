<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Chat extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'chat';

    // protected $fillable = ['lastMessage', 'isActive', 'worker', 'recruiter', 'employer', 'messages'];
    protected $fillable = ['lastMessage', 'isActive', 'worker', 'recruiter', 'employer', 'messages', 'employerId', 'workerId', 'recruiterId'];

    protected $casts = [
        'isActive' => 'boolean',
        
    ];
}
