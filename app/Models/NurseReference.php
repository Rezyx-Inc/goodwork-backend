<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;

class NurseReference extends UuidModel
{
    use SoftDeletes;
    use LogsActivity;

    /**
     *
     * @var string
     */
    public $nurse_id;

    /**
     *
     * @var string
     */
    // public $name;

    /**
     *
     * @var string
     */
    public $filter;

    /**
     *
     * @var boolean
     */
    public $active;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'id',
        'nurse_id',
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

    public function nurse()
    {
        return $this->belongsTo(Nurse::class);
    }
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('NurseReference')
            ->setDescriptionForEvent(fn(string $eventName) => "This NurseReference has been {$eventName}.")
            ->logFillable();
    }
}
