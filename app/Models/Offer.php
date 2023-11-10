<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use DB;
class Offer extends Model
{
    use SoftDeletes;

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
        $nextCustomId = "GWO" . str_pad(($maxCustomId + 1), 6, '0', STR_PAD_LEFT);

        return $nextCustomId;
    }

    /**
     *
     * @var string
     */
    private $nurse_id;

    /**
     *
     * @var string
     */
    private $created_by;

    /**
     *
     * @var string
     */
    private $status;

    /**
     *
     * @var boolean
     */
    private $active;

    /**
     *
     * @var string
     */
    private $expiration;

    /**
     *
     * @var string
     */
    private $is_view_date;

    /**
     *
     * @var boolean
     */
    private $is_view;

    /**
     *
     * @var string
     */
    private $job_id;

    /**
     *
     * @var string
     */
    private $start_date;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nurse_id',
        'created_by',
        'job_id',
        'status',
        'active',
        'expiration',
        'is_view',
        'is_view_date',
        'start_date'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expiration' => 'datetime',
        'is_view_date' => 'datetime',
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


    public function user()
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
