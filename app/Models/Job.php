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
        'call_back',
        'orientation_rate',
        'weekly_taxable_amount',
        'weekly_non_taxable_amount',
        'employer_weekly_amount',
        'goodwork_weekly_amount',
        'total_employer_amount',
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
        'employer_average_rating',
        'contract_termination_policy',
        'facility_location',
        'eligible_work_in_us',
        'nurse_classification',
        'facility_name',
        'facility_city',
        'facility_state',
        'pay_frequency',
        'benefits',
        
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
        return $this->hasMany(Offer::class)->where(['status'=>'Apply'])->whereNull('deleted_at')->count();
    }

    public function checkIfApplied()
    {
        $user = auth()->guard('frontend')->user();
        return $this->hasMany(Offer::class)->where(['worker_user_id'=>$user->nurse->id, 'status'=>'Apply'])->whereNull('deleted_at')->count();
    }

    public function matchWithWorker()
    {
        $user = auth()->guard('frontend')->user();
        $nurse = Nurse::where('user_id', $user->id)->first();
        $job = $this;
        $matches = [
            'diploma'=> function () use ($nurse){
                $diploma = $nurse->nurseAssets->where('filter', 'diploma')->where('active', '1')->first();
                $match = empty($diploma) ? false : true;
                $value = '';
                $type = 'file';
                $name = 'diploma';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'driving_license'=> function () use ($nurse){
                $dl = $nurse->nurseAssets->where('filter', 'driving_license')->where('active', '1')->first();
                $match = empty($dl) ? false : true;
                $value = '';
                $type = 'file';
                $name = 'driving_license';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'worked_at_facility_before'=> function () use ($nurse){
                $match = (!empty($nurse->worked_at_facility_before) && $nurse->worked_at_facility_before=='Yes') ? true: false ;
                $value = $nurse->worked_at_facility_before;
                $type = 'dropdown';
                $name = 'worked_at_facility_before';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'worker_ss_number'=> function () use ($nurse){
                $match = empty($nurse->worker_ss_number) ? false : true;
                $value = $nurse->worker_ss_number;
                $type = 'input';
                $name = 'worker_ss_number';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'profession'=> function () use ($job, $nurse){
                if ( $job->proffesion == $nurse->profession){
                    $match = true;
                }else{
                    $match = false;
                };
                $value = $nurse->highest_nursing_degree;
                $type = 'dropdown';
                $name = 'highest_nursing_degree';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },

            'preferred_specialty'=> function () use ($job, $nurse){
                if(!empty($nurse->specialty) && $job->preferred_specialty==$nurse->specialty){
                    $match = true;
                }else{
                    $match = false;
                }
                $value = $nurse->specialty;
                $type = 'dorpdown';
                $name = 'specialty';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },

            'job_location'=> function () use ($job, $nurse){
                $match = ($job->job_location == $nurse->nursing_license_state);
                $value = $nurse->nursing_license_state;
                $type = 'dropdown';
                $name = 'nursing_license_state';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },

            'preferred_experience'=> function () use ($job, $nurse){
                if(!empty($nurse->experience) && $job->preferred_experience==$nurse->experience){
                    $match = true;
                }else{
                    $match = false;
                }
                $value = $nurse->experience;
                $type = 'dorpdown';
                $name = 'experience';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },


            'vaccinations'=> function () use ($job, $nurse){
                $vaccninations = explode(',', $job->vaccinations);
                $worker_vaccination = json_decode($nurse->worker_vaccination);
                $worker_vaccination = explode(',', $nurse->worker_vaccination);
                $data = [];
                $return_data = [];
                // $worker_certificate_name = json_decode($job_data['worker_certificate_name']);
                foreach($vaccninations as $k=>$v){
                    if (!empty($worker_vaccination[$k])) {
                        $data['match'] = true;
                        $data['name'] = 'worker_vaccination';
                    $data['type'] = 'file';
                    $data['value'] = $worker_vaccination[$k];
                    $return_data[] = $data;
                    }else{
                        $data['match'] = false;
                        $data['name'] = 'worker_vaccination';
                    $data['type'] = 'file';
                    $data['value'] = null;
                    $return_data[] = $data;
                    }
                    
                }
                return $return_data;
            },


            'number_of_references'=> function () use ($job, $nurse){
                $worker_ref_count = $nurse->references->count();

                $match = ($job->number_of_references <= $worker_ref_count) ? true : false;
                $value = $worker_ref_count;
                $type = 'form';
                $name = 'refernces';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },


            'min_title_of_reference'=> function () use ($job, $nurse){
                $worker_ref_count = $nurse->references->count();
                $match = ($worker_ref_count) ? true: false;
                $value = $worker_ref_count;
                $type = 'form';
                $name = 'refernces';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },

            'recency_of_reference'=> function () use ($job, $nurse){
                $worker_ref_count = $nurse->references->count();
                $match = ($worker_ref_count) ? true: false;
                $value = $worker_ref_count;
                $type = 'form';
                $name = 'refernces';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },


            'certificate'=> function () use ($job, $nurse){
                $certifications = explode(',', $job->certificate);
                $worker_certificate_name = json_decode($nurse->worker_certificate_name);
                $worker_certificate_name = explode(',', $nurse->worker_certificate_name);
                $data = [];
                $return_data = [];
                // $worker_certificate_name = json_decode($job_data['worker_certificate_name']);
                foreach($certifications as $k=>$v){
                    if (!empty($worker_certificate_name[$k])) {
                        $data['match'] = true;
                        $data['name'] = 'worker_certificate';
                    $data['type'] = 'file';
                    $data['value'] = $worker_certificate_name[$k];
                    $return_data[] = $data;

                    }else{
                        $data['match'] = false;
                        $data['name'] = 'worker_certificate';
                    $data['type'] = 'file';
                    $data['value'] = null;
                    $return_data[] = $data;
                    }
                    
                }
                return $return_data;
            },

            'skills'=> function () use ($job, $nurse){
                $match = empty($nurse->skills_checklists) ? false: true;
                $value = $nurse->skills_checklists;
                $type = 'form';
                $name = 'skills_checklists';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },

            'eligible_work_in_us'=> function () use ($nurse){
                $match = (!empty($nurse->worker_eligible_work_in_us) && $nurse->worker_eligible_work_in_us == '1') ? true: false;
                $value = $nurse->worker_eligible_work_in_us;
                $type = 'dropdown';
                $name = 'worker_eligible_work_in_us';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },

            'urgency'=> function () use ($job, $nurse){
                $match = ($job->urgency == $nurse->worker_urgency);
                $value = $nurse->worker_urgency;
                $type = 'input';
                $name = 'urgency';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },

            // 'job_state'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },

            'job_state'=> function () use ($job, $nurse){
                $match = ($job->job_state == $nurse->worker_job_state);
                $value = $nurse->worker_job_state;
                $type = 'input';
                $name = 'worker_job_state';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },

            'job_city'=> function () use ($job, $nurse){
                $match = ($job->job_city == $nurse->worker_job_city);
                $value = $nurse->worker_job_city;
                $type = 'input';
                $name = 'worker_job_city';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            // 'facility_id'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'facility'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'profession'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            'preferred_assignment_duration'=> function () use ($job, $nurse){
                $match = ($job->preferred_assignment_duration == $nurse->worker_weeks_assignment);
                $value = $nurse->worker_weeks_assignment;
                $type = 'input';
                $name = 'worker_weeks_assignment';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            // 'preferred_shift'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'preferred_shift_duration'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'preferred_work_location'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'preferred_work_area'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'preferred_days_of_the_week'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'preferred_hourly_pay_rate'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'description'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            'start_date'=> function () use ($job, $nurse){
                $match = ($job->start_date == $nurse->worker_start_date);
                $value = $nurse->worker_start_date;
                $type = 'input';
                $name = 'worker_start_date';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'as_soon_as'=> function () use ($job, $nurse){
                $match = ($job->as_soon_as == $nurse->worker_as_soon_as);
                $value = $nurse->worker_as_soon_as;
                $type = 'input';
                $name = 'worker_as_soon_as';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            // 'end_date'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'job_video'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'job_photos'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'seniority_level'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'job_function'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'responsibilities'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'qualifications'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'job_cerner_exp'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'job_meditech_exp'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'job_epic_exp'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'job_other_exp'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'license_type'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            'traveler_distance_from_facility'=> function () use ($job, $nurse){
                $match = ($job->traveler_distance_from_facility == $nurse->distance_from_your_home);
                $value = $nurse->distance_from_your_home;
                $type = 'input';
                $name = 'distance_from_your_home';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'clinical_setting'=> function () use ($job, $nurse){
                $match = ($job->clinical_setting == $nurse->clinical_setting_you_prefer);
                $value = $nurse->clinical_setting_you_prefer;
                $type = 'input';
                $name = 'clinical_setting_you_prefer';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'scrub_color'=> function () use ($job, $nurse){
                $match = ($job->scrub_color == $nurse->worker_scrub_color);
                $value = $nurse->worker_scrub_color;
                $type = 'input';
                $name = 'worker_scrub_color';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'emr'=> function () use ($job, $nurse){
                $match = ($job->Emr == $nurse->worker_emr);
                $value = $nurse->worker_emr;
                $type = 'input';
                $name = 'worker_emr';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'rto'=> function () use ($job, $nurse){
                $match = ($job->rto == $nurse->rto);
                $value = $nurse->rto;
                $type = 'input';
                $name = 'rto';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            // 'call_coverage'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'weekly_pay'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            'hours_per_week'=> function () use ($job, $nurse){
                $match = ($job->hours_per_week == $nurse->worker_hours_per_week);
                $value = $nurse->worker_hours_per_week;
                $type = 'input';
                $name = 'worker_hours_per_week';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'guaranteed_hours'=> function () use ($job, $nurse){
                $match = ($job->guaranteed_hours == $nurse->worker_guaranteed_hours);
                $value = $nurse->worker_guaranteed_hours;
                $type = 'input';
                $name = 'worker_guaranteed_hours';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'hours_shift'=> function () use ($job, $nurse){
                $match = ($job->hours_shift == $nurse->worker_hours_shift);
                $value = $nurse->hours_shift;
                $type = 'input';
                $name = 'worker_hours_shift';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'weeks_shift'=> function () use ($job, $nurse){
                $match = ($job->weeks_shift == $nurse->worker_shifts_week);
                $value = $nurse->worker_shifts_week;
                $type = 'input';
                $name = 'worker_shifts_week';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'referral_bonus'=> function () use ($job, $nurse){
                $match = ($job->referral_bonus == $nurse->worker_referral_bonus);
                $value = $nurse->worker_referral_bonus;
                $type = 'input';
                $name = 'worker_referral_bonus';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'sign_on_bonus'=> function () use ($job, $nurse){
                $match = ($job->sign_on_bonus == $nurse->worker_sign_on_bonus);
                $value = $nurse->worker_sign_on_bonus;
                $type = 'input';
                $name = 'worker_sign_on_bonus';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'completion_bonus'=> function () use ($job, $nurse){
                $match = ($job->completion_bonus == $nurse->worker_completion_bonus);
                $value = $nurse->worker_completion_bonus;
                $type = 'input';
                $name = 'worker_completion_bonus';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'extension_bonus'=> function () use ($job, $nurse){
                $match = ($job->extension_bonus == $nurse->worker_extension_bonus);
                $value = $nurse->worker_extension_bonus;
                $type = 'input';
                $name = 'worker_extension_bonus';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'other_bonus'=> function () use ($job, $nurse){
                $match = ($job->other_bonus == $nurse->worker_other_bonus);
                $value = $nurse->worker_other_bonus;
                $type = 'input';
                $name = 'worker_other_bonus';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'four_zero_one_k'=> function () use ($job, $nurse){
                $match = ($job->four_zero_one_k == $nurse->worker_four_zero_one_k);
                $value = $nurse->worker_four_zero_one_k;
                $type = 'input';
                $name = 'worker_four_zero_one_k';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'actual_hourly_rate'=> function () use ($job, $nurse){
                $match = ($job->actual_hourly_rate == $nurse->worker_actual_hourly_rate);
                $value = $nurse->worker_actual_hourly_rate;
                $type = 'input';
                $name = 'worker_actual_hourly_rate';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'health_insaurance'=> function () use ($job, $nurse){
                $match = ($job->health_insaurance == $nurse->worker_health_insurance);
                $value = $nurse->worker_health_insurance;
                $type = 'input';
                $name = 'worker_health_insurance';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'dental'=> function () use ($job, $nurse){
                $match = ($job->dental == $nurse->worker_dental);
                $value = $nurse->worker_dental;
                $type = 'input';
                $name = 'worker_dental';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'vision'=> function () use ($job, $nurse){
                $match = ($job->vision == $nurse->worker_vision);
                $value = $nurse->worker_vision;
                $type = 'input';
                $name = 'worker_vision';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'feels_like_per_hour'=> function () use ($job, $nurse){
                $match = ($nurse->worker_feels_like_per_hour_check == '1');
                $value = $nurse->worker_feels_like_per_hour_check;
                $type = 'input';
                $name = 'worker_feels_like_per_hour_check';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'overtime'=> function () use ($job, $nurse){
                $match = ($nurse->worker_overtime_check == '1');
                $value = $nurse->worker_overtime_check;
                $type = 'input';
                $name = 'worker_overtime_check';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'holiday'=> function () use ($job, $nurse){
                $match = ($job->holiday == $nurse->worker_holiday);
                $value = $nurse->worker_holiday;
                $type = 'input';
                $name = 'worker_holiday';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'on_call'=> function () use ($job, $nurse){
                $match = ($nurse->worker_on_call_check == true);
                $value = $nurse->worker_on_call_check;
                $type = 'input';
                $name = 'worker_on_call_check';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'call_back'=> function () use ($job, $nurse){
                $match = ($nurse->worker_call_back_check == '1');
                $value = $nurse->worker_call_back_check;
                $type = 'input';
                $name = 'worker_call_back_check';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'orientation_rate'=> function () use ($job, $nurse){
                $match = ($nurse->worker_orientation_rate_check == '1');
                $value = $nurse->worker_orientation_rate_check;
                $type = 'input';
                $name = 'worker_orientation_rate_check';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            // 'weekly_taxable_amount'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            'weekly_non_taxable_amount'=> function () use ($job, $nurse){
                $match = ($nurse->worker_weekly_non_taxable_amount_check == '1');
                $value = $nurse->worker_weekly_non_taxable_amount_check;
                $type = 'input';
                $name = 'worker_field';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'employer_weekly_amount'=> function () use ($job, $nurse){
                $match = ($job->employer_weekly_amount == $nurse->worker_employer_weekly_amount);
                $value = $nurse->worker_employer_weekly_amount;
                $type = 'input';
                $name = 'worker_employer_weekly_amount';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            // 'goodwork_weekly_amount'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'total_employer_amount'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'total_goodwork_amount'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'total_contract_amount'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            'Patient_ratio'=> function () use ($job, $nurse){
                $match = ($job->Patient_ratio == $nurse->worker_patient_ratio);
                $value = $nurse->worker_patient_ratio;
                $type = 'input';
                $name = 'worker_patient_ratio';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'Unit'=> function () use ($job, $nurse){
                $match = ($job->Unit == $nurse->worker_unit);
                $value = $nurse->worker_field;
                $type = 'input';
                $name = 'worker_field';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            // 'Department'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'Bed_Size'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'Trauma_Level'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'goodwork_number'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'recruiter_id'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            'job_type'=> function () use ($job, $nurse){
                $match = ($job->field == $nurse->worker_field);
                $value = $nurse->worker_field;
                $type = 'input';
                $name = 'worker_field';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            // 'position_available'=> function () use ($job, $nurse){
            //     $match = empty($nurse->available_position) ? false : true;
            //     $value = $nurse->available_position;
            //     $type = 'input';
            //     $name = 'available_position';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            'msp'=> function () use ($job, $nurse){
                $match = (!empty($nurse->MSP) && $job->msp==$nurse->MSP) ? true: false ;
                $value = $nurse->MSP;
                $type = 'input';
                $name = 'MSP';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'vms'=> function () use ($job, $nurse){
                $match = (!empty($nurse->MSP) && $job->vms==$nurse->VMS) ? true: false ;
                $value = $nurse->VMS;
                $type = 'input';
                $name = 'VMS';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            // 'submission_of_vms'=> function () use ($nurse){
            //     $match = empty( $nurse->worker_field) ? false: true ;
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            'block_scheduling'=> function () use ($job, $nurse){
                $match = ($job->block_scheduling == $nurse->block_scheduling);
                $value = $nurse->block_scheduling;
                $type = 'input';
                $name = 'block_scheduling';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'float_requirement'=> function () use ($job, $nurse){
                $match = ($job->float_requirement == $nurse->float_requirement);
                $value = $nurse->float_requirement;
                $type = 'input';
                $name = 'float_requirement';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'facility_shift_cancelation_policy'=> function () use ($job, $nurse){
                $match = ($job->facility_shift_cancelation_policy == $nurse->facility_shift_cancelation_policy);
                $value = $nurse->facility_shift_cancelation_policy;
                $type = 'input';
                $name = 'worker_field';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            'facilitys_parent_system'=> function () use ($job, $nurse){
                $match = ($job->facilitys_parent_system == $nurse->worker_facilitys_parent_system);
                $value = $nurse->worker_facilitys_parent_system;
                $type = 'input';
                $name = 'worker_facilitys_parent_system';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            // 'facility_average_rating'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'recruiter_average_rating'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'employer_average_rating'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            'contract_termination_policy'=> function () use ($job, $nurse){
                $match = ($job->field == $nurse->worker_field);
                $value = $nurse->worker_field;
                $type = 'input';
                $name = 'worker_field';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },
            // 'msp'=> ['match'=> ($this->field == $nurse->worker_field), 'value'=>$nurse->worker_field, 'name'=>$nurse->worker_field, 'type'=>'text']
        ];

        

        return $matches;


        

    }
}
