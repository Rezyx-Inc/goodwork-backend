<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use DB;
class Offer extends Model
{
    protected $table = 'offers';
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

    // protected $fillable = [
    //     'nurse_id',
    //     'created_by',
    //     'job_id',
    //     'status',
    //     'active',
    //     'expiration',
    //     'is_view',
    //     'is_view_date',
    //     'start_date'
    // ];

    protected $fillable = [
        'job_name',
        'type',
        'status',
        'terms',
        'profession',
        'specialty',
        'city',
        'state',
        'block_scheduling',
        'float_requirement',
        'facility_shift_cancelation_policy',
        'contract_terminat_policy',
        'traveler_distance_from_facility',
        'job_id',
        'recruiter_id',
        'worker_user_id',
        'clinical_setting',
        'Patient_ratio',
        'Emr',
        'Unit',
        'scrub_color',
        'start_date',
        'end_date',
        'as_soon_as',
        'rto',
        'hours_per_week',
        'guaranteed_hours',
        'hours_shift',
        'weeks_shift',
        'preferred_assignment_duration',
        'referral_bonus',
        'sign_on_bonus',
        'completion_bonus',
        'extension_bonus',
        'other_bonus',
        'four_zero_one_k',
        'health_insaurance',
        'dental',
        'vision',
        'actual_hourly_rate',
        'overtime',
        'holiday',
        'on_call',
        'orientation_rate',
        'weekly_non_taxable_amount',
        'description',
        'weekly_taxable_amount',
        'organization_weekly_amount',
        'goodwork_weekly_amount',
        'total_organization_amount',
        'total_goodwork_amount',
        'total_contract_amount',
        'weekly_pay',
        'is_draft',
        'is_counter',
        'created_by',
        'worked_at_facility_before',
        'preferred_shift_duration',
        'feels_like_per_hour',
        'preferred_work_location',
        'facility_name',
        'facilitys_parent_system',
        'certificate',
        'job_location',
        'urgency',
        'city',
        'state',
        'preferred_experience',
        'skills',
        'nurse_classification',
        'vaccinations',
        'on_call',
        'on_call_rate',
        'call_back_rate',
        'pay_frequency',
        'benefits',
        'number_of_references',
        'organization_id',
        'contract_termination_policy'
        
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
