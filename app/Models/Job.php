<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

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
        'msp'
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
        $nurse = $user->nurse;
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
                if (!empty($nurse->highest_nursing_degree) && $job->profession == $nurse->highest_nursing_degree){
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
                $data = [];
                $return_data = [];
                // $worker_certificate_name = json_decode($job_data['worker_certificate_name']);
                foreach($vaccninations as $k=>$v){
                    if (!empty($worker_vaccination[$k])) {
                        $data['match'] = true;
                    }else{
                        $data['match'] = false;
                    }
                    $data['name'] = 'worker_vaccination';
                    $data['type'] = 'file';
                    $data['value'] = $worker_vaccination[$k];
                    $return_data[] = $data;
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
                $data = [];
                $return_data = [];
                // $worker_certificate_name = json_decode($job_data['worker_certificate_name']);
                foreach($certifications as $k=>$v){
                    if (!empty($worker_certificate_name[$k])) {
                        $data['match'] = true;
                    }else{
                        $data['match'] = false;
                    }
                    $data['name'] = 'worker_certificate';
                    $data['type'] = 'file';
                    $data['value'] = $worker_certificate_name[$k];
                    $return_data[] = $data;
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
                $match = (!empty($nurse->eligible_work_in_us) && $nurse->eligible_work_in_us=='yes') ? true: false;
                $value = $nurse->eligible_work_in_us;
                $type = 'dropdown';
                $name = 'eligible_work_in_us';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },

            'urgency'=> function () use ($job, $nurse){
                $match = ($job->urgency == $nurse->worker_urgency);
                $value = $nurse->worker_urgency;
                $type = 'input';
                $name = 'worker_urgency';
                return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            },

            // 'job_state'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },

            // 'job_state'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },

            // 'job_city'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
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
            // 'preferred_assignment_duration'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
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
            // 'start_date'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'as_soon_as'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
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
            // 'traveler_distance_from_facility'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'clinical_setting'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'scrub_color'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'emr'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'rto'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
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
            // 'hours_per_week'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'guaranteed_hours'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'hours_shift'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'weeks_shift'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'referral_bonus'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'sign_on_bonus'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'completion_bonus'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'extension_bonus'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'other_bonus'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'four_zero_one_k'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'actual_hourly_rate'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'health_insaurance'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'dental'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'vision'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'feels_like_per_hour'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'overtime'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'holiday'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'on_call'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'call_back'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'orientation_rate'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'weekly_taxable_amount'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'weekly_non_taxable_amount'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'employer_weekly_amount'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
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
            // 'Patient_ratio'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'Unit'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
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
            // 'job_type'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
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
            // 'facility_shift_cancelation_policy'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'facilitys_parent_system'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
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
            // 'contract_termination_policy'=> function () use ($job, $nurse){
            //     $match = ($job->field == $nurse->worker_field);
            //     $value = $nurse->worker_field;
            //     $type = 'input';
            //     $name = 'worker_field';
            //     return ['match'=> $match, 'value'=>$value, 'name'=>$name, 'type'=> $type];
            // },
            // 'msp'=> ['match'=> ($this->field == $nurse->worker_field), 'value'=>$nurse->worker_field, 'name'=>$nurse->worker_field, 'type'=>'text']
        ];

        return $matches;


        // $worker = [
        //     'specialty',
        //     'experience',
        //     'mu_specialty',
        //     'credential_title',
        //     'nursing_license_state',
        //     'nursing_license_number',
        //     'compact_license',
        //     'highest_nursing_degree',
        //     'serving_preceptor',
        //     'serving_interim_nurse_leader',
        //     'leadership_roles',
        //     'address',
        //     'city',
        //     'state',
        //     'postcode',
        //     'country',
        //     'hourly_pay_rate',
        //     'experience_as_acute_care_facility',
        //     'experience_as_ambulatory_care_facility',
        //     'ehr_proficiency_cerner',
        //     'ehr_proficiency_meditech',
        //     'ehr_proficiency_epic',
        //     'ehr_proficiency_other',
        //     'summary',
        //     'active',
        //     'is_verified',
        //     'is_verified_nli',
        //     'clinical_educator',
        //     'is_daisy_award_winner',
        //     'employee_of_the_mth_qtr_yr',
        //     'other_nursing_awards',
        //     'is_professional_practice_council',
        //     'is_research_publications',
        //     'additional_photos',
        //     'languages',
        //     'facility_hourly_pay_rate',
        //     'additional_files',
        //     'college_uni_name',
        //     'college_uni_city',
        //     'college_uni_state',
        //     'college_uni_country',
        //     'nu_video',
        //     'search_status',
        //     'license_type',
        //     'worker_vaccination',
        //     'worker_ss_number',
        //     'worker_number_of_references',
        //     'worker_min_title_of_reference',
        //     'worker_recency_of_reference',
        //     'BLS',
        //     'ACLS',
        //     'PALS',
        //     'other',
        //     'other_certificate_name',
        //     'skills_checklists',
        //     'distance_from_your_home',
        //     'facilities_you_worked_at',
        //     'facilities_you_like_to_work_at',
        //     'avg_rating_by_facilities',
        //     'worker_avg_rating_by_recruiters',
        //     'worker_avg_rating_by_employers',
        //     'clinical_setting_you_prefer',
        //     'authority_Issue',
        //     'worker_patient_ratio',
        //     'worker_emr',
        //     'worker_unit',
        //     'worker_department',
        //     'worker_bed_size',
        //     'worker_trauma_level',
        //     'worker_scrub_color',
        //     'worker_facility_city',
        //     'worker_facility_state_code',
        //     'worker_interview_dates',
        //     'worker_start_date',
        //     'worker_rto',
        //     'worker_shift_time_of_day',
        //     'worker_hours_per_week',
        //     'worker_guaranteed_hours',
        //     'worker_hours_shift',
        //     'worker_weeks_assignment',
        //     'worker_shifts_week',
        //     'worker_people_you_have_refffered',
        //     'worker_referral_bonus',
        //     'worker_sign_on_bonus',
        //     'worker_completion_bonus',
        //     'worker_extension_bonus',
        //     'worker_other_bonus',
        //     'worker_health_insurance',
        //     'worker_dental',
        //     'worker_vision',
        //     'worker_actual_hourly_rate',
        //     'worker_feels_like_hour',
        //     'worker_overtime',
        //     'worker_holiday',
        //     'worker_on_call',
        //     'worker_call_back',
        //     'worker_orientation_rate',
        //     'worker_weekly_taxable_amount',
        //     'worker_weekly_non_taxable_amount',
        //     'worker_employer_weekly_amount',
        //     'worker_goodwork_weekly_amount',
        //     'worker_total_employer_amount',
        //     'worker_goodwork_number',
        //     'is_verified_sr',
        //     'license_status',
        //     'license_renewal_date',
        //     'license_expiry_date',
        //     'license_issue_date',
        //     'study_area',
        //     'graduation_date',
        //     'worker_urgency',
        //     'VMS',
        //     'MSP',
        //     'available_position',
        //     'submission_VMS',
        //     'block_scheduling',
        //     'float_requirement',
        //     'facility_shift_cancelation_policy',
        //     'contract_termination_policy',
        //     'worker_facility_parent_system',
        //     'how_much_k',
        //     'worker_total_goodwork_amount',
        //     'worker_total_contract_amount',
        //     'worker_as_soon_as_posible'
        // ];

    }
}
