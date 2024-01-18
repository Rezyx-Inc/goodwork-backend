<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Availability extends UuidModel
{
    use SoftDeletes;

    protected $table = 'availability';

    /**
     *
     * @var string
     */
    private $worker_id;

    /**
     *
     * @var integer
     */
    private $assignment_duration;

    /**
     *
     * @var integer
     */
    private $shift_duration;

    /**
     *
     * @var string
     */
    private $days_of_the_week;

    /**
     *
     * @var integer
     */
    private $work_location;

    /**
     *
     * @var integer
     */
    private $preferred_shift;

    /**
     *
     * @var string
     */
    private $earliest_start_date;

    /**
     *
     * @var string
     */
    private $unavailable_dates;

    /**
     *
     * @var string
     */
    private $note;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'worker_id',
        'assignment_duration',
        'shift_duration',
        'days_of_the_week',
        'work_location',
        'preferred_shift',
        'earliest_start_date',
        'booking_availability',
        'unavailable_dates',
        'note'
    ];

    protected $guarded = ['_token','_method'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'earliest_start_date' => 'date',
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
