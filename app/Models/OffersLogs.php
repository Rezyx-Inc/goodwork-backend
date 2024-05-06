<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;


class OffersLogs extends Model
{
    protected $table = 'offers_logs';
    
    protected $fillable = [ 
        'original_offer_id',
        'nurse_id',
        'employer_recruiter_id',
        'details',
        'status',
        'counter_offer_by',
    ];
}
