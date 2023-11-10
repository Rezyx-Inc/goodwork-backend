<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportTicket extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'status',
        'type',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected static function boot()
	{
		parent::boot();

		static::creating(function ($model) {
			$model->{$model->getKeyName()} = $model->generateCustomId();
		});
	}

	public function getIncrementing()
	{
		return false;
	}

	public function getKeyType()
	{
		return 'string';
	}

    public function generateCustomId()
    {
        // Fetch the current maximum custom ID in the database
        $maxCustomId = DB::table($this->getTable())
            ->select(DB::raw('MAX(CAST(SUBSTRING(id, 4) AS SIGNED)) as max_custom_id'))
            ->value('max_custom_id');

        // Increment the maximum custom ID and format it with leading zeros
        $nextCustomId = "GWT" . str_pad(($maxCustomId + 1), 6, '0', STR_PAD_LEFT);

        return $nextCustomId;
    }

    public function  replies()
    {
        return $this->hasMany(SupportTicketReply::class, 'ticket_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
