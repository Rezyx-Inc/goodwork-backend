<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportTicketReply extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'ticket_id',
        'message',
        'active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
