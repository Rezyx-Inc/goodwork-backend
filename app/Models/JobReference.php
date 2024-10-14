<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;

class JobReference extends UuidModel implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;
    use LogsActivity;


    public $fillable = [
        'id',
        'job_id',
        'name',
        'email',
        'phone',
        'date_referred',
        'min_title_of_reference',
        'recency_of_reference',
        'image'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    public $dates = ['deleted_at'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('JobReference')
            ->setDescriptionForEvent(fn(string $eventName) => "This JobReference has been {$eventName}.")
            ->logFillable();
    }
}
