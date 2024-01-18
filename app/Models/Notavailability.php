<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Notavailability extends UuidModel
{
    use SoftDeletes;

    /**
     *
     * @var string
     */
    private $worker_id;

    /**
     *
     * @var string
     */
    private $specific_dates;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'worker_id',
        'specific_dates'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'specific_dates' => 'date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function worker()
	{
		return $this->belongsTo(Worker::class);
	}
}
