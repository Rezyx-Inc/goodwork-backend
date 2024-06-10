<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NurseReference extends UuidModel
{
    use SoftDeletes;
    // use HasMediaTrait;
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
}
