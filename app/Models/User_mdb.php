<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;// Import the missing class

class User_mdb extends Model
{
    protected  $connection = 'mongodb';
     protected  $collection = 'User_mdb';
     protected  $fillable = ['email', 'oauth_id', 'oauth_type'];

}
