<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends UuidModel
{
    use SoftDeletes;

    /**
     *
     * @var string
     */
    private $created_by;

    /**
     *
     * @var string
     */
    private $job_id;

    /**
     *
     * @var string
     */
    private $title;

    /**
     *
     * @var string
     */
    private $text;

    /**
     *
     * @var string
     */
    private $is_view;

    /**
     *
     * @var string
     */
    private $isAskWorker;

    /**
     *
     * @var boolean
     */
    private $created_at;

    /**
     *
     * @var string
     */
    private $updated_at;

    /**
     *
     * @var string
     */
    private $deleted_at;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'created_by',
        'job_id',
        'title',
        'text',
        'is_view',
        'isAskWorker',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'craeted_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function nurse()
    {
        return $this->belongsTo(Nurse::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
    
}
