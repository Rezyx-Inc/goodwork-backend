<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class Nurse extends Model implements HasMedia
{
    use SoftDeletes;
    use HasMediaTrait;
    use LogsActivity;

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
        $nextCustomId = "GWW" . str_pad(($maxCustomId + 1), 6, '0', STR_PAD_LEFT);

        return $nextCustomId;
    }

    protected $fillable = [
        'user_id',
        'specialty',
        'experience',
        'mu_specialty',
        'credential_title',
        'nursing_license_state',
        'nursing_license_number',
        'compact_license',
        'highest_nursing_degree',
        'serving_preceptor',
        'serving_interim_nurse_leader',
        'leadership_roles',
        'address',
        'city',
        'state',
        'postcode',
        'country',
        'hourly_pay_rate',
        'experience_as_acute_care_facility',
        'experience_as_ambulatory_care_facility',
        'ehr_proficiency_cerner',
        'ehr_proficiency_meditech',
        'ehr_proficiency_epic',
        'ehr_proficiency_other',
        'summary',
        'active',
        'is_verified',
        'is_verified_nli',
        'clinical_educator',
        'is_daisy_award_winner',
        'employee_of_the_mth_qtr_yr',
        'other_nursing_awards',
        'is_professional_practice_council',
        'is_research_publications',
        'additional_photos',
        'languages',
        'facility_hourly_pay_rate',
        'additional_files',
        'college_uni_name',
        'college_uni_city',
        'college_uni_state',
        'college_uni_country',
        'nu_video',
        'search_status',
        'license_type',
        'worker_vaccination',
        'worker_ss_number',
        'worker_number_of_references',
        'worker_min_title_of_reference',
        'worker_recency_of_reference',
        'BLS',
        'ACLS',
        'PALS',
        'other',
        'other_certificate_name',
        'skills_checklists',
        'distance_from_your_home',
        'facilities_you_worked_at',
        'facilities_you_like_to_work_at',
        'avg_rating_by_facilities',
        'worker_avg_rating_by_recruiters',
        'worker_avg_rating_by_employers',
        'clinical_setting_you_prefer',
        'authority_Issue',
        'worker_patient_ratio',
        'worker_emr',
        'worker_unit',
        'worker_department',
        'worker_bed_size',
        'worker_trauma_level',
        'worker_scrub_color',
        'worker_facility_city',
        'worker_facility_state',
        'worker_facility_state_code',
        'worker_interview_dates',
        'worker_start_date',
        'worker_rto',
        'worker_shift_time_of_day',
        'worker_hours_per_week',
        'worker_guaranteed_hours',
        'worker_hours_shift',
        'worker_weeks_assignment',
        'worker_shifts_week',
        'worker_people_you_have_refffered',
        'worker_referral_bonus',
        'worker_sign_on_bonus',
        'worker_completion_bonus',
        'worker_extension_bonus',
        'worker_other_bonus',
        'worker_health_insurance',
        'worker_dental',
        'worker_vision',
        'worker_actual_hourly_rate',
        'worker_feels_like_hour',
        'worker_overtime',
        'worker_holiday',
        'worker_on_call',
        'worker_call_back',
        'worker_orientation_rate',
        'worker_weekly_taxable_amount',
        'worker_weekly_non_taxable_amount',
        'worker_employer_weekly_amount',
        'worker_goodwork_weekly_amount',
        'worker_total_employer_amount',
        'worker_goodwork_number',
        'is_verified_sr',
        'license_status',
        'license_renewal_date',
        'license_expiry_date',
        'license_issue_date',
        'study_area',
        'graduation_date',
        'worker_urgency',
        'VMS',
        'MSP',
        'available_position',
        'submission_VMS',
        'block_scheduling',
        'float_requirement',
        'facility_shift_cancelation_policy',
        'contract_termination_policy',
        'worker_facility_parent_system',
        'how_much_k',
        'worker_total_goodwork_amount',
        'worker_total_contract_amount',
        'worker_as_soon_as_posible',
        'eligible_work_in_us',
        'worked_at_facility_before',
        'account_tier',
        'terms',
        'worker_job_type',
        'profession',
        'full_name_payment' ,
        'address_payment',
        'email_payment',
        'bank_name_payment',
        'routing_number_payment',
        'bank_account_payment_number',
        'phone_number_payment',
        'rto',
        'worker_facilitys_parent_system',
        'worker_urgency',
        'skills_checklists',
        'worker_vaccination',
        'worker_certificate_name',
        'worker_eligible_work_in_us',
        'worker_feels_like_per_hour',
        'worker_four_zero_one_k',
        // check recruiter/employer conditions
        'worker_feels_like_per_hour_check',
        'worker_overtime_rate',


        'worker_weekly_non_taxable_amount_check',
        'worker_call_back_rate',
        'worker_on_call_rate',
        'worker_on_call_check',
        'worker_experience',
        'worker_benefits',
        'nurse_classification'

    ];
    protected static $logName = 'Nurse';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'gig_account_create_date', 'gig_account_invite_date'];

    public function getCityStateAttribute()
    {
        if ($this->__get('city') && $this->__get('state')) {
            return Str::title($this->__get('city')) . ', ' . $this->__get('state');
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function availability()
    {
        return $this->hasOne(Availability::class)->withTrashed();
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function notavailabilities()
    {
        return $this->hasMany(Notavailability::class);
    }

    public function nurseAssets()
    {
        return $this->hasMany(NurseAsset::class, 'nurse_id','id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function references()
    {
        return $this->hasMany(NurseReference::class);
    }

    public function profile_percentage(){

    }
}
