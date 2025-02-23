<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;
use App\Models\Nurse;
use App\Models\NurseAsset;

class Job extends Model
{
    use SoftDeletes;
    // use HasMediaTrait;
    use LogsActivity;

    public static function boot()
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
        $nextCustomId = "GWJ" . str_pad(($maxCustomId + 1), 6, '0', STR_PAD_LEFT);

        return $nextCustomId;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'job_name',
        'job_id',
        'type',
        'compact',
        'terms',
        'job_location',
        'job_state',
        'job_city',
        'facility_id',
        'facility',
        'profession',
        'preferred_specialty',
        'preferred_assignment_duration',
        'preferred_shift',
        'preferred_shift_duration',
        'preferred_work_location',
        'preferred_work_area',
        'preferred_days_of_the_week',
        'preferred_hourly_pay_rate',
        'preferred_experience',
        'description',
        'start_date',
        'end_date',
        'as_soon_as',
        'end_date',
        'created_at',
        'updated_at',
        'deleted_at',
        'job_video',
        'job_photos',
        'seniority_level',
        'job_function',
        'responsibilities',
        'qualifications',
        'job_cerner_exp',
        'job_meditech_exp',
        'job_epic_exp',
        'job_other_exp',
        'created_by',
        'slug',
        'active',
        'is_open',
        'license_type',
        'vaccinations',
        'certificate',
        'number_of_references',
        'min_title_of_reference',
        'recency_of_reference',
        'skills',
        'traveler_distance_from_facility',
        'clinical_setting',
        'scrub_color',
        'emr',
        'rto',
        'call_coverage',
        'weekly_pay',
        'hours_per_week',
        'guaranteed_hours',
        'hours_shift',
        'weeks_shift',
        'referral_bonus',
        'sign_on_bonus',
        'completion_bonus',
        'extension_bonus',
        'other_bonus',
        'four_zero_one_k',
        'actual_hourly_rate',
        'health_insaurance',
        'dental',
        'vision',
        'feels_like_per_hour',
        'overtime',
        'holiday',
        'on_call',
        'on_call_rate',
        'call_back_rate',
        'orientation_rate',
        'weekly_taxable_amount',
        'weekly_non_taxable_amount',
        'organization_weekly_amount',
        'goodwork_weekly_amount',
        'total_organization_amount',
        'total_goodwork_amount',
        'total_contract_amount',
        'Patient_ratio',
        'Unit',
        'Department',
        'Bed_Size',
        'Trauma_Level',
        'goodwork_number',
        'recruiter_id',
        'job_type',
        'urgency',
        'position_available',
        'msp',
        'vms',
        'submission_of_vms',
        'block_scheduling',
        'float_requirement',
        'facility_shift_cancelation_policy',
        'facilitys_parent_system',
        'facility_average_rating',
        'recruiter_average_rating',
        'organization_average_rating',
        'contract_termination_policy',
        'facility_location',
        'eligible_work_in_us',
        'nurse_classification',
        'facility_name',
        'facility_city',
        'facility_state',
        'pay_frequency',
        'benefits',
        'professional_state_licensure',
        'is_resume',

    ];

    public static $logName = 'Job';
    public static $logFillable = true;
    public static $logOnlyDirty = true;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public $casts = [
        'job_photos' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    public $dates = ['deleted_at'];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function jobAssets()
    {
        return $this->hasMany(JobAsset::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function jobState()
    {
        return $this->belongsTo(States::class, 'job_state');
    }

    public function jobCity()
    {
        return $this->belongsTo(Cities::class, 'job_city');
    }


    public function jobLocation()
    {
        return $this->belongsTo(States::class, 'job_location');
    }


    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function getOfferCount()
    {
        return $this->hasMany(Offer::class)->where(['status' => 'Apply'])->whereNull('deleted_at')->count();
    }

    public function checkIfApplied()
    {
        $user = auth()->guard('frontend')->user();
        return $this->hasMany(Offer::class)->where(['worker_user_id' => $user->nurse->id, 'status' => 'Apply'])->whereNull('deleted_at')->count();
    }

    public function matchWithWorker()
    {
        $user = auth()->guard('frontend')->user();
        $nurse = Nurse::where('user_id', $user->id)->first();
        $job = $this;
        $matches = [
            


            'job_type' => function () use ($job, $nurse) {
                $match = false;
                $matchCount = 0;

                $job_jobType = explode(', ', $job->job_type);
                $nurse_jobType = explode(', ', $nurse->worker_job_type);

                // Find matches
                $matches = array_intersect($job_jobType, $nurse_jobType);
                // Check if there is at least one match
                if (count($matches) > 0) {

                    $match = true;
                    $matchCount = count($matches);
                } 

                $value = $nurse->worker_job_type;
                $type = 'input';
                $name = 'worker_job_type';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            
            'terms' => function () use ($job, $nurse) {

                $match = false;
                $matchCount = 0;

                $jobVal = explode(', ', $job->terms);
                $nurseVal = explode(', ', $nurse->terms);

                // Find matches
                $matches = array_intersect($jobVal, $nurseVal);
                // Check if there is at least one match
                if (count($matches) > 0) {

                    $match = true;
                    $matchCount = count($matches);
                } 

                $value = $nurse->terms;
                $type = 'dropdown';
                $name = 'terms';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
             },

            'profession' => function () use ($job, $nurse) {

                $match = false;
                $matchCount = 0;

                $jobVal = explode(', ', $job->profession);
                $nurseVal = explode(', ', $nurse->profession);

                // Find matches
                $matches = array_intersect($jobVal, $nurseVal);
                // Check if there is at least one match
                if (count($matches) > 0) {
                    $match = true;
                    $matchCount = count($matches);
                }

                $value = $nurse->profession;
                $type = 'dropdown';
                $name = 'profession';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'preferred_specialty' => function () use ($job, $nurse) {
                
                $match = false;
                $matchCount = 0;

                $jobVal = explode(', ', $job->preferred_specialty);
                $nurseVal = explode(', ', $nurse->specialty);

                // Find matches
                $matches = array_intersect($jobVal, $nurseVal);
                
                // Check if there is at least one match
                if (count($matches) > 0) {
                    $match = true;
                    $matchCount = count($matches);
                }

                $profile_info_text = "What’s your specialty ?";
                
                $value = $nurse->specialty;
                $type = 'dorpdown';
                $name = 'specialty';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            
            'job_state' => function () use ($job, $nurse) {
                $profile_info_text = "Do you want to work here?";

                $match = false;
                $matchCount = 0;

                $jobVal = explode(', ', $job->job_state);
                $nurseVal = explode(', ', $nurse->state);

                // Find matches
                $matches = array_intersect($jobVal, $nurseVal);
                
                // Check if there is at least one match
                if (count($matches) > 0) {
                    $match = true;
                    $matchCount = count($matches);
                }
                
                $value = $nurse->state;
                $type = 'input';
                $name = 'state';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            
            'job_city' => function () use ($job, $nurse) {
                $profile_info_text = "Do you want to work here?";
                
                $match = false;
                $matchCount = 0;

                $jobVal = explode(', ', $job->job_city);
                $nurseVal = explode(', ', $nurse->city);

                // Find matches
                $matches = array_intersect($jobVal, $nurseVal);
                
                // Check if there is at least one match
                if (count($matches) > 0) {
                    $match = true;
                    $matchCount = count($matches);
                }
                
                $value = $nurse->city;
                $type = 'input';
                $name = 'city';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },

            'float_requirement' => function () use ($job, $nurse) {
                $match = ($job->float_requirement == $nurse->float_requirement);
                $profile_info_text = "Are you willing to float?";
                // if (!empty($nurse->float_requirement)) {
                //     $profile_info_text = $nurse->float_requirement;
                // }
                $value = $nurse->float_requirement;
                $type = 'input';
                $name = 'float_requirement';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'job_location' => function () use ($job, $nurse) {
                $profile_info_text = "Where are you licensed?";
                // if (!empty($nurse->nursing_license_state)) {
                //     $profile_info_text = $nurse->nursing_license_state;
                // }
                $job_locations = explode(',', $job->job_location);
                $nursing_license_state = $nurse->nursing_license_state;
                $match = false;
                $name = 'nursing_license_state';
                $type = 'dropdown';
                $value = $nursing_license_state;
                foreach ($job_locations as $job_location) {
                    if ($job_location == $nursing_license_state) {
                        $match = true;
                        $name = 'nursing_license_state';
                        $type = 'dropdown';
                        $value = $nursing_license_state;
                    }
                }
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'preferred_experience' => function () use ($job, $nurse) {
                $profile_info_text = "How long have you done this?";
                // if (!empty($nurse->nursing_license_state)) {
                //     $profile_info_text = "You will need more experience to apply for this job";
                // }
                if (!empty($nurse->worker_experience) && $job->preferred_experience <= $nurse->worker_experience) {
                    $match = true;
                    $profile_info_text = "You are experienced.";
                } else {
                    $match = false;
                }
                $value = $nurse->worker_experience;
                $type = 'dorpdown';
                $name = 'worker_experience';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'as_soon_as' => function () use ($job, $nurse) {
                $profile_info_text = "you start as soon as possible?";
                // if (!empty($nurse->worker_as_soon_as_possible)) {
                //     $profile_info_text = "Ready to start on " . $job->worker_as_soon_as_possible;
                // }
                $match = ($job->as_soon_as == $nurse->worker_as_soon_as_possible);
                $value = $nurse->worker_as_soon_as_possible;
                $type = 'input';
                $name = 'worker_as_soon_as_possible';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'hours_per_week' => function () use ($job, $nurse) {
                $profile_info_text = "Are you sure you want to work this many hours per week?";
                // if (!empty($nurse->worker_hours_per_week)) {
                //     $profile_info_text =  $job->worker_hours_per_week . "is a match!";
                // }
                $match = ($job->hours_per_week == $nurse->worker_hours_per_week);
                $value = $nurse->worker_hours_per_week;
                $type = 'input';
                $name = 'worker_hours_per_week';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'preferred_shift_duration' => function () use ($job, $nurse) {

                if ($job->preferred_shift_duration == $nurse->worker_shift_time_of_day) {
                    $match = true;
                } else {
                    $match = false;
                };
                $profile_info_text = "Fav shift?";
                if (!empty($nurse->worker_shift_time_of_day && $job->preferred_shift_duration == $nurse->worker_shift_time_of_day)) {
                    $match = true;
                }
                $value = $nurse->worker_shift_time_of_day;
                $type = 'dropdown';
                $name = 'worker_shift_time_of_day';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'guaranteed_hours' => function () use ($job, $nurse) {
                $profile_info_text = "Open to job with no guaranteed hours?";
                // if (!empty($nurse->worker_guaranteed_hours)) {
                //     if ($job->guaranteed_hours == $nurse->worker_guaranteed_hours) {
                //         $profile_info_text = $nurse->worker_guaranteed_hours . " hours is a match";
                //     } else {
                //         $profile_info_text = "Are you ok with these guaranteed hours?";
                //     }
                // }
                $match = ($job->guaranteed_hours == $nurse->worker_guaranteed_hours);
                $value = $nurse->worker_guaranteed_hours;
                $type = 'input';
                $name = 'worker_guaranteed_hours';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'hours_shift' => function () use ($job, $nurse) {
                $profile_info_text = "Preferred hours per shift ?";
                // if (!empty($nurse->worker_hours_shift)) {
                //     if ($job->hours_shift == $nurse->worker_hours_shift) {
                //         $profile_info_text = $nurse->worker_hours_shift . " hours/shift is a match";
                //     } else {
                //         $profile_info_text = "Are you sure you want to work this many hours per shift?";
                //     }
                // }
                $match = ($job->hours_shift == $nurse->worker_hours_shift);
                $value = $nurse->hours_shift;
                $type = 'input';
                $name = 'worker_hours_shift';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'preferred_assignment_duration' => function () use ($job, $nurse) {
                $profile_info_text = "How long do you want to be on contract?";
                // if (!empty($nurse->worker_weeks_assignment)) {
                //     if ($job->preferred_assignment_duration < $nurse->worker_weeks_assignment) {
                //         $profile_info_text = "Are you ok with a shorter assignment?";
                //     } else if ($job->preferred_assignment_duration > $nurse->worker_weeks_assignment) {
                //         $profile_info_text = "Are you sure you want to work this many hours per shift?";
                //     } else {
                //         $profile_info_text = $nurse->worker_weeks_assignment . " weeks is a match";
                //     }
                // }
                $match = ($job->preferred_assignment_duration == $nurse->worker_weeks_assignment);
                $value = $nurse->worker_weeks_assignment;
                $type = 'input';
                $name = 'worker_weeks_assignment';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'weeks_shift' => function () use ($job, $nurse) {
                $profile_info_text = "Ideal shifts per week?";
                // if (!empty($nurse->worker_shifts_week)) {
                //     if ($job->weeks_shift < $nurse->worker_shifts_week) {
                //         $profile_info_text = "Are you ok with working shorter shifts?";
                //     } else if ($job->weeks_shift > $nurse->worker_shifts_week) {
                //         $profile_info_text = "Are you ok with working longer shifts?";
                //     } else {
                //         $profile_info_text = $nurse->worker_shifts_week . " weeks is a match";
                //     }
                // }
                $match = ($job->weeks_shift == $nurse->worker_shifts_week);
                $value = $nurse->worker_shifts_week;
                $type = 'input';
                $name = 'worker_shifts_week';
                return ['match' => $match, "profile_info_text" => $profile_info_text,    'value' => $value, 'name' => $name, 'type' => $type];
            },
            'health_insaurance' => function () use ($job, $nurse) {
                $profile_info_text = "How important is this to you ?";
                // if (!empty($nurse->worker_health_insurance)) {
                //     $profile_info_text = $nurse->worker_health_insurance == '1' ? 'Yes' : 'No';
                // }
                $match = ($job->health_insaurance == $nurse->worker_health_insurance);
                $value = $nurse->worker_health_insurance;
                $type = 'input';
                $name = 'worker_health_insurance';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'dental' => function () use ($job, $nurse) {
                $profile_info_text = "How important is this to you ?";
                // if (!empty($nurse->worker_dental)) {
                //     $profile_info_text = $nurse->worker_dental == '1' ? 'Yes' : 'No';
                // }
                $match = ($job->dental == $nurse->worker_dental);
                $value = $nurse->worker_dental;
                $type = 'input';
                $name = 'worker_dental';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'vision' => function () use ($job, $nurse) {
                $profile_info_text = "How important is this to you ?";
                // if (!empty($nurse->worker_vision)) {
                //     $profile_info_text = $nurse->worker_vision == '1' ? 'Yes' : 'No';
                // }
                $match = ($job->vision == $nurse->worker_vision);
                $value = $nurse->worker_vision;
                $type = 'input';
                $name = 'worker_vision';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'actual_hourly_rate' => function () use ($job, $nurse) {
                $profile_info_text = "What rate is fair ?";
                // if (!empty($nurse->worker_actual_hourly_rate)) {
                //     $profile_info_text = $nurse->worker_actual_hourly_rate;
                // }
                $match = ($job->actual_hourly_rate == $nurse->worker_actual_hourly_rate);
                $value = $nurse->worker_actual_hourly_rate;
                $type = 'input';
                $name = 'worker_actual_hourly_rate';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'overtime' => function () use ($job, $nurse) {
                $profile_info_text = "What rate is fair ?";
                // if (!empty($nurse->worker_overtime_rate)) {
                //     $profile_info_text = $nurse->worker_overtime_rate;
                // }
                $match = ($nurse->worker_overtime_rate == $job->overtime_rate);
                $value = $nurse->worker_overtime_rate;
                $type = 'input';
                $name = 'worker_overtime_rate';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },


















            'diploma' => function () use ($nurse) {
                $diploma = $nurse->nurseAssets->where('filter', 'diploma')->where('active', '1')->first();
                $match = empty($diploma) ? true : true;
                $profile_info_text = empty($diploma) ? "Did you really graduate ?" : "You got the smarts needed";
                $value = '';
                $type = 'file';
                $name = 'diploma';
                return ['match' => $match, "profile_info_text" => $profile_info_text, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'driving_license' => function () use ($nurse) {
                $dl = $nurse->nurseAssets->where('filter', 'driving_license')->where('active', '1')->first();
                $match = empty($dl) ? false : true;
                $value = '';
                $type = 'file';
                $name = 'driving_license';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'worked_at_facility_before' => function () use ($job, $nurse) {
                $offer = Offer::where('job_id', $job->id)->where('worker_user_id', $nurse->id)->first();
                if (empty($offer)) {
                    $match = false;
                    $value = '';
                    $type = 'dropdown';
                    $name = 'worked_at_facility_before';
                    return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
                } else {
                    $match = ($offer->worked_at_facility_before == '1');
                    $value = $offer->worked_at_facility_before;
                    $type = 'dropdown';
                    $name = 'worked_at_facility_before';
                    return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
                }
            },
            'worker_ss_number' => function () use ($nurse) {
                $match = empty($nurse->worker_ss_number) ? false : true;
                $value = $nurse->worker_ss_number;
                $type = 'input';
                $name = 'worker_ss_number';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },


            'nurse_classification' => function () use ($job, $nurse) {
                $job_nurse_classification = explode(',', $job->nurse_classification);
                $nurse_classification = $nurse->nurse_classification;
                $match = false;
                $name = 'nurse_classification';
                $type = 'dropdown';
                $value = $nurse_classification;
                foreach ($job_nurse_classification as $job_nurse_classification) {
                    if ($job_nurse_classification == $nurse_classification) {
                        $match = true;
                        $name = 'nurse_classification';
                        $type = 'dropdown';
                        $value = $nurse_classification;
                    }
                }
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },



            'vaccinations' => function () use ($job, $nurse) {
                $vaccninations = explode(',', $job->vaccinations);
                $worker_vaccination = NurseAsset::where('nurse_id', $nurse->id)->where('active', '1')->where('filter', 'vaccination')->pluck('name')->toArray();
                //$worker_vaccination = explode(',', $nurse->worker_vaccination);

                foreach ($vaccninations as $k => $v) {
                    if (!empty($worker_vaccination[$k])) {
                        $match = true;
                        $name = 'worker_vaccination';
                        $type = 'file';
                        $value = $worker_vaccination[$k];
                    } else {
                        $match = false;
                        $name = 'worker_vaccination';
                        $type = 'file';
                        $value = null;
                        return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
                    }
                }
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },


            'number_of_references' => function () use ($job, $nurse) {
                $worker_ref_count = $nurse->references->count();

                $match = ($job->number_of_references <= $worker_ref_count) ? true : false;
                $value = $worker_ref_count;
                $type = 'form';
                $name = 'refernces';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },


            'min_title_of_reference' => function () use ($job, $nurse) {
                $worker_ref_count = $nurse->references->count();
                $match = ($worker_ref_count) ? true : false;
                $value = $worker_ref_count;
                $type = 'form';
                $name = 'refernces';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },

            'recency_of_reference' => function () use ($job, $nurse) {
                $worker_ref_count = $nurse->references->count();
                $match = ($worker_ref_count) ? true : false;
                $value = $worker_ref_count;
                $type = 'form';
                $name = 'refernces';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },


            'certificate' => function () use ($job, $nurse) {

                // here we will compare the certificates of the job with the certificates of the worker comming from the mongodb database
                $certifications = explode(',', $job->certificate);
                $worker_certificate_name = json_decode($nurse->worker_certificate_name);
                $worker_certificate_name = NurseAsset::where('nurse_id', $nurse->id)->where('active', '1')->where('filter', 'certificate')->pluck('name')->toArray(); // this call will be replace with the file name comming from the mongodb database


                // $worker_certificate_name = json_decode($job_data['worker_certificate_name']);
                foreach ($certifications as $k => $v) {
                    if (!empty($worker_certificate_name[$k])) {
                        $match = true;
                        $name = 'worker_certificate';
                        $type = 'file';
                        $value = $worker_certificate_name[$k];
                    } else {
                        $match = false;
                        $name = 'worker_certificate';
                        $type = 'file';
                        $value = null;

                        return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
                    }
                }
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },

            'skills' => function () use ($job, $nurse) {
                $match = empty($nurse->skills_checklists) ? false : true;
                $value = $nurse->skills_checklists;
                $type = 'form';
                $name = 'skills_checklists';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },

            'eligible_work_in_us' => function () use ($nurse) {
                $match = (!empty($nurse->worker_eligible_work_in_us) && $nurse->worker_eligible_work_in_us == '1') ? true : false;
                $value = $nurse->worker_eligible_work_in_us;
                $type = 'dropdown';
                $name = 'worker_eligible_work_in_us';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },

            'urgency' => function () use ($job, $nurse) {
                $match = ($job->urgency == $nurse->worker_urgency);
                $value = $nurse->worker_urgency;
                $type = 'input';
                $name = 'urgency';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },

            'start_date' => function () use ($job, $nurse) {
                $match = ($job->start_date == $nurse->worker_start_date);
                $value = $nurse->worker_start_date;
                $type = 'input';
                $name = 'worker_start_date';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'traveler_distance_from_facility' => function () use ($job, $nurse) {
                $match = ($job->traveler_distance_from_facility == $nurse->distance_from_your_home);
                $value = $nurse->distance_from_your_home;
                $type = 'input';
                $name = 'distance_from_your_home';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'clinical_setting' => function () use ($job, $nurse) {
                $match = ($job->clinical_setting == $nurse->clinical_setting_you_prefer);
                $value = $nurse->clinical_setting_you_prefer;
                $type = 'input';
                $name = 'clinical_setting_you_prefer';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'scrub_color' => function () use ($job, $nurse) {
                $match = ($job->scrub_color == $nurse->worker_scrub_color);
                $value = $nurse->worker_scrub_color;
                $type = 'input';
                $name = 'worker_scrub_color';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'emr' => function () use ($job, $nurse) {
                $match = ($job->Emr == $nurse->worker_emr);
                $value = $nurse->worker_emr;
                $type = 'input';
                $name = 'worker_emr';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'rto' => function () use ($job, $nurse) {
                $match = ($job->rto == $nurse->rto);
                $value = $nurse->rto;
                $type = 'input';
                $name = 'rto';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },

            'referral_bonus' => function () use ($job, $nurse) {
                $match = ($job->referral_bonus == $nurse->worker_referral_bonus);
                $value = $nurse->worker_referral_bonus;
                $type = 'input';
                $name = 'worker_referral_bonus';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'sign_on_bonus' => function () use ($job, $nurse) {
                $match = ($job->sign_on_bonus == $nurse->worker_sign_on_bonus);
                $value = $nurse->worker_sign_on_bonus;
                $type = 'input';
                $name = 'worker_sign_on_bonus';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'completion_bonus' => function () use ($job, $nurse) {
                $match = ($job->completion_bonus == $nurse->worker_completion_bonus);
                $value = $nurse->worker_completion_bonus;
                $type = 'input';
                $name = 'worker_completion_bonus';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'extension_bonus' => function () use ($job, $nurse) {
                $match = ($job->extension_bonus == $nurse->worker_extension_bonus);
                $value = $nurse->worker_extension_bonus;
                $type = 'input';
                $name = 'worker_extension_bonus';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'other_bonus' => function () use ($job, $nurse) {
                $match = ($job->other_bonus == $nurse->worker_other_bonus);
                $value = $nurse->worker_other_bonus;
                $type = 'input';
                $name = 'worker_other_bonus';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'four_zero_one_k' => function () use ($job, $nurse) {
                $match = ($job->four_zero_one_k == $nurse->worker_four_zero_one_k);
                $value = $nurse->worker_four_zero_one_k;
                $type = 'input';
                $name = 'worker_four_zero_one_k';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'health_insaurance' => function () use ($job, $nurse) {
                $match = ($job->health_insaurance == $nurse->worker_health_insurance);
                $value = $nurse->worker_health_insurance;
                $type = 'input';
                $name = 'worker_health_insurance';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'feels_like_per_hour' => function () use ($job, $nurse) {
                $match = ($nurse->worker_feels_like_per_hour_check == '1');
                $value = $nurse->worker_feels_like_per_hour_check;
                $type = 'input';
                $name = 'worker_feels_like_per_hour_check';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'benefits' => function () use ($job, $nurse) {
                $match = ($nurse->worker_benefits == '1');
                $value = $nurse->worker_benefits;
                $type = 'input';
                $name = 'worker_benefits';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'overtime' => function () use ($job, $nurse) {
                $match = ($nurse->worker_overtime_rate == $job->overtime_rate);
                $value = $nurse->worker_overtime_rate;
                $type = 'input';
                $name = 'worker_overtime_rate';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'holiday' => function () use ($job, $nurse) {
                $match = ($job->holiday == $nurse->worker_holiday);
                $value = $nurse->worker_holiday;
                $type = 'input';
                $name = 'worker_holiday';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'on_call' => function () use ($job, $nurse) {
                $match = ($nurse->worker_on_call_check == $job->on_call);
                $value = $nurse->worker_on_call_check;
                $type = 'input';
                $name = 'worker_on_call_check';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'on_call_rate' => function () use ($job, $nurse) {
                $match = ($job->on_call_rate == $nurse->worker_on_call);
                $value = $nurse->worker_on_call;
                $type = 'input';
                $name = 'worker_on_call';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'call_back_rate' => function () use ($job, $nurse) {
                $match = ($nurse->worker_call_back_check == '1');
                $value = $nurse->worker_call_back_check;
                $type = 'input';
                $name = 'worker_call_back_check';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'orientation_rate' => function () use ($job, $nurse) {
                $match = ($nurse->worker_orientation_rate == $job->orientation_rate);
                $value = $nurse->worker_orientation_rate;
                $type = 'input';
                $name = 'worker_orientation_rate';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },

            'weekly_non_taxable_amount' => function () use ($job, $nurse) {
                $match = ($nurse->worker_weekly_non_taxable_amount_check == '1');
                $value = $nurse->worker_weekly_non_taxable_amount_check;
                $type = 'input';
                $name = 'worker_weekly_non_taxable_amount_check';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'organization_weekly_amount' => function () use ($job, $nurse) {
                $match = ($job->organization_weekly_amount == $nurse->worker_organization_weekly_amount);
                $value = $nurse->worker_organization_weekly_amount;
                $type = 'input';
                $name = 'worker_organization_weekly_amount';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },

            'Patient_ratio' => function () use ($job, $nurse) {
                $match = ($job->Patient_ratio == $nurse->worker_patient_ratio);
                $value = $nurse->worker_patient_ratio;
                $type = 'input';
                $name = 'worker_patient_ratio';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'Unit' => function () use ($job, $nurse) {
                $match = ($job->Unit == $nurse->worker_unit);
                $value = $nurse->worker_unit;
                $type = 'input';
                $name = 'worker_unit';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },

            'msp' => function () use ($job, $nurse) {
                $match = (!empty($nurse->MSP) && $job->msp == $nurse->MSP) ? true : false;
                $value = $nurse->MSP;
                $type = 'input';
                $name = 'MSP';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'vms' => function () use ($job, $nurse) {
                $match = (!empty($nurse->MSP) && $job->vms == $nurse->VMS) ? true : false;
                $value = $nurse->VMS;
                $type = 'input';
                $name = 'VMS';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },

            'block_scheduling' => function () use ($job, $nurse) {
                $match = ($job->block_scheduling == $nurse->block_scheduling);
                $value = $nurse->block_scheduling;
                $type = 'input';
                $name = 'block_scheduling';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'facility_shift_cancelation_policy' => function () use ($job, $nurse) {
                $match = ($job->facility_shift_cancelation_policy == $nurse->facility_shift_cancelation_policy);
                $value = $nurse->facility_shift_cancelation_policy;
                $type = 'input';
                $name = 'facility_shift_cancelation_policy';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'facilitys_parent_system' => function () use ($job, $nurse) {
                $match = ($job->facilitys_parent_system == $nurse->worker_facilitys_parent_system);
                $value = $nurse->worker_facilitys_parent_system;
                $type = 'input';
                $name = 'worker_facilitys_parent_system';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },
            'contract_termination_policy' => function () use ($job, $nurse) {
                $match = ($job->field == $nurse->contract_termination_policy);
                $value = $nurse->contract_termination_policy;
                $type = 'input';
                $name = 'contract_termination_policy';
                return ['match' => $match, 'value' => $value, 'name' => $name, 'type' => $type];
            },

        ];



        return $matches;
    }
}
