<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, JsonResponse};
use Carbon\Carbon;
use Validator;
use Hash;
use DB;
use App\Enums\Role;
use File;
use Illuminate\Database\Eloquent\Builder;
/** Models */
use App\Models\{User, Nurse,Follows, NurseReference,Job,Offer, NurseAsset,
    Keyword, Facility, Availability, Countries, States, Cities, JobSaved};

class JobController extends Controller
{
    public function explore(Request $request)
    {
        try {

           // commenting this for now we need to return only jobs data

        $data = [];
        $data['user'] = auth()->guard('frontend')->user();
        $data['jobSaved'] = new JobSaved();
        $data['professions'] = Keyword::where(['filter'=>'Profession','active'=>'1'])->get();
        $data['terms'] = Keyword::where(['filter'=>'jobType','active'=>'1'])->get();
        $data['prefered_shifts'] = Keyword::where(['filter'=>'PreferredShift','active'=>'1'])->get();
        $data['usa'] = $usa =  Countries::where(['iso3'=>'USA'])->first();
        $data['us_states'] = States::where('country_id', $usa->id)->get();
        // $data['us_cities'] = Cities::where('country_id', $usa->id)->get();

        $data['profession'] = isset($request->profession) ? $request->profession : '';
        $data['speciality'] = isset($request->speciality) ? $request->speciality : '';
        $data['experience'] = isset($request->experience) ? $request->experience : '';
        $data['city'] = isset($request->city) ? $request->city : '';
        $data['state'] = isset($request->state) ? $request->state : '';
        $data['job_type'] = isset($request->job_type) ? explode('-', $request->job_type) : [];
        $data['start_date'] = isset($request->start_date) ? $request->start_date : '';
        $data['end_date'] = isset($request->end_date) ? $request->end_date : '';
        $data['shifts'] = isset($request->shifts) ? explode('-',$request->shifts) : [];
        $data['auto_offers'] = isset($request->auto_offers) ? $request->auto_offers : 0;

        $data['weekly_pay_from'] = isset($request->weekly_pay_from) ? $request->weekly_pay_from : 10;
        $data['weekly_pay_to'] = isset($request->weekly_pay_to) ? $request->weekly_pay_to : 10000;
        $data['hourly_pay_from'] = isset($request->hourly_pay_from) ? $request->hourly_pay_from : 2;
        $data['hourly_pay_to'] = isset($request->hourly_pay_to) ? $request->hourly_pay_to : 24;
        $data['hours_per_week_from'] = isset($request->hours_per_week_from) ? $request->hours_per_week_from : 10;
        $data['hours_per_week_to'] = isset($request->hours_per_week_to) ? $request->hours_per_week_to : 100;
        $data['assignment_from'] = isset($request->assignment_from) ? $request->assignment_from : 10;
        $data['assignment_to'] = isset($request->assignment_to) ? $request->assignment_to : 150;

        $user = auth()->guard('frontend')->user();

        $nurse = NURSE::where('user_id', $user->id)->first();
        $jobs_id = Offer::where('nurse_id', $nurse->id)->select('job_id')->get();

        // $checkoffer = DB::table('blocked_users')->where('worker_id', $nurse['id'])->first();

        $whereCond = [
            'facilities.active' => true,
            'jobs.is_open' => "1",
            'jobs.is_hidden' => "0",
            'jobs.is_closed' => "0",
            // 'job_saved.is_delete'=>'0',
            // 'job_saved.nurse_id'=>$user->id,
        ];

        $ret = Job::select('jobs.id as job_id', 'jobs.auto_offers as auto_offers', 'jobs.*')
            ->leftJoin('facilities', function ($join) {
                $join->on('facilities.id', '=', 'jobs.facility_id');
            })
            ->leftJoin('job_saved', function ($join) use ($user){
                $join->on('job_saved.job_id', '=', 'jobs.id')
                ->where(function ($query) use ($user) {
                    $query->where('job_saved.is_delete', '=', 0)
                    ->where('job_saved.nurse_id', '=', $user->id);
                });
            })
            ->where($whereCond)
            ->whereNotIN('jobs.id', $jobs_id)
            ->orderBy('jobs.created_at', 'desc');

        if ($data['profession']) {
            $ret->where('jobs.profession', '=', $data['profession']);
        }

        if ($data['speciality']) {
            $ret->where('jobs.preferred_specialty', '=', $data['speciality']);
        }

        if ($data['experience']) {
            $ret->where('jobs.preferred_experience', '=', $data['experience']);
        }
        if($data['city'])
        {
            $ret->where('jobs.job_city', '=', $data['city']);
        }

        if($data['state'])
        {
            $ret->where('jobs.job_state', '=', $data['state']);
        }

        if(count($data['job_type'])){
            $ret->whereIn('jobs.job_type', $data['job_type']);
        }

        if ($data['start_date']) {
            $ret->where('jobs.start_date', '<=', $data['start_date']);
            $ret->where('jobs.end_date', '>=', $data['start_date']);
        }

        if ($data['end_date']) {
            $ret->where('jobs.start_date', '<=', $data['end_date']);
            $ret->where('jobs.end_date', '>=', $data['end_date']);
        }

        if ($data['shifts']) {
            $ret->whereIn('jobs.preferred_shift', $data['shifts']);
        }

        if ($data['auto_offers']) {
            $ret->where('jobs.auto_offers', '=', $data['auto_offers']);
        }

        if ($data['weekly_pay_from']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('weekly_pay', '>=', $data['weekly_pay_from']);
            });
        }

        if ($data['weekly_pay_to']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('weekly_pay', '<=', $data['weekly_pay_to']);
            });
        }

        if ($data['hourly_pay_from']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('hours_shift', '>=', $data['hourly_pay_from']);
            });
        }

        if ($data['hourly_pay_to']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('hours_shift', '<=', $data['hourly_pay_to']);
            });
        }

        if ($data['hours_per_week_from']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('hours_per_week', '>=', $data['hours_per_week_from']);
            });
        }

        if ($data['hours_per_week_to']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('hours_per_week', '<=', $data['hours_per_week_to']);
            });
        }

        if ($data['assignment_from']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('preferred_assignment_duration', '>=', $data['assignment_from']);
            });
        }


        if ($data['assignment_to']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('preferred_assignment_duration', '<=', $data['assignment_to']);
            });
        }

        $result = $ret->get();




            $resl = Job::select('jobs.*','name')
            ->leftJoin('facilities', function ($join) {
                $join->on('facilities.id', '=', 'jobs.facility_id');
            });
            $data['jobs'] = $resl->get();


            // $data['jobSaved'] = [""];
            // $data['prefered_shifts '] = [""];
            // $data['terms'] = [""];
            // $data['us_states'] = [""];
            // $data['speciality'] = [""];
            // $data['professions'] = ['title'=>'','id'=>''];
            // $data['profession'] = "";

        //$data['jobs'] = $result;


        return view('jobs.explore', $data);
        //return response()->json(['message' =>  $data['jobs']]);
    } catch (\Exception $e) {
        // Handle other exceptions


        // Display a generic error message or redirect with an error status
         return redirect()->route('jobs.explore')->with('error', 'An unexpected error occurred. Please try again later.');
        //return response()->json(['success' => false, 'message' =>  $e->getMessage()]);
    }
    }

    public function counter_offer($id)
    {
        $data = [];
        $data['model'] = Job::findOrFail($id);
        return view('jobs.counter_offer', $data);
    }

    public function jobData($jobdata, $user_id = "")
    {
        $result = [];
        if (!empty($jobdata)) {
            $controller = new Controller();
            $specialties = $controller->getSpecialities()->pluck('title', 'id');
            $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
            $shifts = $this->getShifts()->pluck('title', 'id');
            $workLocations = $controller->getGeographicPreferences()->pluck('title', 'id');
            $leadershipRoles = $this->getLeadershipRoles()->pluck('title', 'id');
            $seniorityLevels = $this->getSeniorityLevel()->pluck('title', 'id');
            $jobFunctions = $this->getJobFunction()->pluck('title', 'id');
            $ehrProficienciesExp = $this->getEHRProficiencyExp()->pluck('title', 'id');
            $weekDays = $this->getWeekDayOptions();

            foreach ($jobdata as $key => $job)
            {
                $j_data["offer_id"] = isset($job->offer_id) ? $job->offer_id : "";
                $j_data["job_id"] = isset($job->job_id) ? $job->job_id : "";
                $j_data["end_date"] = isset($job->end_date) ? date('d F Y', strtotime($job->end_date)) : "";
                $j_data["job_type"] = isset($job->job_type) ? $job->job_type : "";
                $j_data["type"] = isset($job->type) ? $job->type : "";
                $j_data["job_name"] = isset($job->job_name) ? $job->job_name : "";
                $j_data["keyword_title"] = isset($job->keyword_title) ? $job->keyword_title : "";
                $j_data["keyword_filter"] = isset($job->keyword_filter) ? $job->keyword_filter : "";
                $j_data["auto_offers"] = isset($job->auto_offers) ? $job->auto_offers : "";

                $j_data["job_location"] = isset($job->job_location) ? $job->job_location : "";
                $j_data["position_available"] = isset($job->position_available) ? $job->position_available : "";
                $j_data["employer_weekly_amount"] = isset($job->employer_weekly_amount) ? $job->employer_weekly_amount : "";
                $j_data["weekly_pay"] = isset($job->weekly_pay) ? $job->weekly_pay : "";
                $j_data["hours_per_week"] = isset($job->hours_per_week) ? $job->hours_per_week : 0;

                $j_data["preferred_specialty"] = isset($job->preferred_specialty) ? $job->preferred_specialty : "";
                $j_data["preferred_specialty_definition"] = isset($specialties[$job->preferred_specialty])  ? $specialties[$job->preferred_specialty] : "";

                $j_data["preferred_assignment_duration"] = isset($job->preferred_assignment_duration) ? $job->preferred_assignment_duration : "";
                $j_data["preferred_assignment_duration_definition"] = isset($assignmentDurations[$job->preferred_assignment_duration]) ? $assignmentDurations[$job->preferred_assignment_duration] : "";
                if(isset($j_data["preferred_assignment_duration_definition"]) && !empty($j_data["preferred_assignment_duration_definition"])){
                    $assignment = explode(" ", $assignmentDurations[$job->preferred_assignment_duration]);
                    $j_data["preferred_assignment_duration_definition"] = $assignment[0]; // 12 Week
                }
                $j_data["preferred_shift_duration"] = isset($job->preferred_shift) ? $job->preferred_shift : "";
                // $j_data["preferred_shift_duration_definition"] = isset($shifts[$job->preferred_shift_duration]) ? $shifts[$job->preferred_shift_duration] : "";

                $j_data["preferred_work_location"] = isset($job->preferred_work_location) ? $job->preferred_work_location : "";
                $j_data["preferred_work_location_definition"] = isset($workLocations[$job->preferred_work_location]) ? $workLocations[$job->preferred_work_location] : "";

                $j_data["preferred_work_area"] = isset($job->preferred_work_area) ? $job->preferred_work_area : "";
                $j_data["preferred_days_of_the_week"] = isset($job->preferred_days_of_the_week) ? explode(",", $job->preferred_days_of_the_week) : [];
                $j_data["preferred_hourly_pay_rate"] = isset($job->preferred_hourly_pay_rate) ? $job->preferred_hourly_pay_rate : "";
                $j_data["preferred_experience"] = isset($job->preferred_experience) ? $job->preferred_experience : "";
                $j_data["description"] = isset($job->description) ? $job->description : "";
                $j_data["created_at"] = isset($job->created_at) ? date('d-F-Y h:i A', strtotime($job->created_at)) : "";
                // if(!empty($job->created_at) && $job->created_at){
                //     $j_data["created_at_definition"] = isset($job->created_at) ? "Recently Added" : "";
                // }else{
                    $j_data["created_at_definition"] = isset($job->created_at) ? "Posted " . $this->timeAgo(date(strtotime($job->created_at))) : "";
                // }
                $j_data["updated_at"] = isset($job->updated_at) ? date('d F Y', strtotime($job->updated_at)) : "";
                $j_data["deleted_at"] = isset($job->deleted_at) ? date('d-F-Y h:i A', strtotime($job->deleted_at)) : "";
                $j_data["created_by"] = isset($job->created_by) ? $job->created_by : "";
                $j_data["slug"] = isset($job->slug) ? $job->slug : "";
                $j_data["active"] = isset($job->active) ? $job->active : "";
                $j_data["facility_id"] = isset($job->facility_id) ? $job->facility_id : "";
                $j_data["job_video"] = isset($job->job_video) ? $job->job_video : "";

                $j_data["seniority_level"] = isset($job->seniority_level) ? $job->seniority_level : "";
                $j_data["seniority_level_definition"] = isset($seniorityLevels[$job->seniority_level]) ? $seniorityLevels[$job->seniority_level] : "";

                $j_data["job_function"] = isset($job->job_function) ? $job->job_function : "";
                $j_data["job_function_definition"] = isset($jobFunctions[$job->job_function]) ? $jobFunctions[$job->job_function] : "";

                $j_data["responsibilities"] = isset($job->responsibilities) ? $job->responsibilities : "";
                $j_data["qualifications"] = isset($job->qualifications) ? $job->qualifications : "";

                $j_data["job_cerner_exp"] = isset($job->job_cerner_exp) ? $job->job_cerner_exp : "";
                $j_data["job_cerner_exp_definition"] = isset($ehrProficienciesExp[$job->job_cerner_exp]) ? $ehrProficienciesExp[$job->job_cerner_exp] : "";

                $j_data["job_meditech_exp"] = isset($job->job_meditech_exp) ? $job->job_meditech_exp : "";
                $j_data["job_meditech_exp_definition"] = isset($ehrProficienciesExp[$job->job_meditech_exp]) ? $ehrProficienciesExp[$job->job_meditech_exp] : "";

                $j_data["job_epic_exp"] = isset($job->job_epic_exp) ? $job->job_epic_exp : "";
                $j_data["job_epic_exp_definition"] = isset($ehrProficienciesExp[$job->job_epic_exp]) ? $ehrProficienciesExp[$job->job_epic_exp] : "";

                $j_data["job_other_exp"] = isset($job->job_other_exp) ? $job->job_other_exp : "";
                // $j_data["job_photos"] = isset($job->job_photos) ? $job->job_photos : "";
                $j_data["video_embed_url"] = isset($job->video_embed_url) ? $job->video_embed_url : "";
                $j_data["is_open"] = isset($job->is_open) ? $job->is_open : "";
                $j_data["name"] = isset($job->facility->name) ? $job->facility->name : "";
                $j_data["address"] = isset($job->facility->address) ? $job->facility->address : "";
                // $j_data["city"] = isset($job->facility->city) ? $job->facility->city : "";
                // $j_data["state"] = isset($job->facility->state) ? $job->facility->state : "";
                $j_data["city"] = isset($job->job_city) ? $job->job_city : "";
                $j_data["state"] = isset($job->job_state) ? $job->job_state : "";
                $j_data["postcode"] = isset($job->facility->postcode) ? $job->facility->postcode : "";
                $j_data["type"] = isset($job->facility->type) ? $job->facility->type : "";

                $j_data["facility_logo"] = isset($job->facility->facility_logo) ? url("public/images/facilities/" . $job->facility->facility_logo) : "";
                $j_data["facility_email"] = isset($job->facility->facility_email) ? $job->facility->facility_email : "";
                $j_data["facility_phone"] = isset($job->facility->facility_phone) ? $job->facility->facility_phone : "";
                $j_data["specialty_need"] = isset($job->facility->specialty_need) ? $job->facility->specialty_need : "";
                $j_data["cno_message"] = isset($job->facility->cno_message) ? $job->facility->cno_message : "";

                $j_data["cno_image"] = isset($job->facility->cno_image) ? url("public/images/facilities/cno_image".$job->facility->cno_image) : "";

                $j_data["about_facility"] = isset($job->facility->about_facility) ? $job->facility->about_facility : "";
                $j_data["facility_website"] = isset($job->facility->facility_website) ? $job->facility->facility_website : "";

                $j_data["f_emr"] = isset($job->facility->f_emr) ? $job->facility->f_emr : "";
                $j_data["f_emr_other"] = isset($job->facility->f_emr_other) ? $job->facility->f_emr_other : "";
                $j_data["f_bcheck_provider"] = isset($job->facility->f_bcheck_provider) ? $job->facility->f_bcheck_provider : "";
                $j_data["f_bcheck_provider_other"] = isset($job->facility->f_bcheck_provider_other) ? $job->facility->f_bcheck_provider_other : "";
                $j_data["nurse_cred_soft"] = isset($job->facility->nurse_cred_soft) ? $job->facility->nurse_cred_soft : "";
                $j_data["nurse_cred_soft_other"] = isset($job->facility->nurse_cred_soft_other) ? $job->facility->nurse_cred_soft_other : "";
                $j_data["nurse_scheduling_sys"] = isset($job->facility->nurse_scheduling_sys) ? $job->facility->nurse_scheduling_sys : "";
                $j_data["nurse_scheduling_sys_other"] = isset($job->facility->nurse_scheduling_sys_other) ? $job->facility->nurse_scheduling_sys_other : "";
                $j_data["time_attend_sys"] = isset($job->facility->time_attend_sys) ? $job->facility->time_attend_sys : "";
                $j_data["time_attend_sys_other"] = isset($job->facility->time_attend_sys_other) ? $job->facility->time_attend_sys_other : "";
                $j_data["licensed_beds"] = isset($job->facility->licensed_beds) ? $job->facility->licensed_beds : "";
                $j_data["trauma_designation"] = isset($job->facility->trauma_designation) ? $job->facility->trauma_designation : "";
                $j_data["contract_termination_policy"] = isset($job->facility->contract_termination_policy) ? $job->facility->contract_termination_policy : "";
                $j_data["clinical_setting_you_prefer"] = isset($job->facility->clinical_setting_you_prefer) ? $job->facility->clinical_setting_you_prefer : "";
                $j_data["Shift"] = isset($job->facility->worker_shift_time_of_day) ? $job->facility->worker_shift_time_of_day : "";
                $j_data["worker_hours_shift"] = isset($job->facility->worker_hours_shift) ? $job->facility->worker_hours_shift : "";
                $j_data["worker_shifts_week"] = isset($job->facility->worker_shifts_week) ? $job->facility->worker_shifts_week : "";
                $j_data["facility_shift_cancelation_policy"] = isset($job->facility->facility_shift_cancelation_policy) ? $job->facility->facility_shift_cancelation_policy : "";

                /* total applied */
                $total_follow_count = Follows::where(['job_id' => $job->job_id, "applied_status" => "1", 'status' => "1"])->distinct('user_id')->count();
                $j_data["total_applied"] = strval($total_follow_count);
                /* total applied */

                /* liked */
                $is_applied = "0";
                if ($user_id != "")
                    // $is_applied = Follows::where(['job_id' => $job->job_id, "applied_status" => "1", 'status' => "1", "user_id" => $user_id])->count();
                    $is_applied = Follows::where(['job_id' => $job->job_id, "applied_status" => "1", 'status' => "1", "user_id" => $user_id])->distinct('user_id')->count();
                /* liked */
                $j_data["is_applied"] = strval($is_applied);

                /* liked */
                $is_liked = "0";
                if ($user_id != "")
                    $is_liked = Follows::where(['job_id' => $job->job_id, "like_status" => "1", 'status' => "1", "user_id" => $user_id])->count();

                /* liked */
                $j_data["is_liked"] = strval($is_liked);

                // $j_data["shift"] = "Days";
                $j_data["start_date"] = date('d F Y', strtotime($job->start_date));

                $j_data['applied_nurses'] = '0';
                $applied_nurses = Offer::where(['job_id' => $job->job_id, 'status'=>'Apply'])->count();
                $j_data['applied_nurses'] = strval($applied_nurses);

                $is_saved = '0';
                if ($user_id != ""){
                    $nurse_info = NURSE::where('user_id', $user_id);
                    if ($nurse_info->count() > 0) {
                        $nurse = $nurse_info->first();
                        $whereCond = [
                            'job_saved.nurse_id' => $user_id,
                            'job_saved.job_id' => $job->job_id,
                        ];
                        $limit = 10;
                        $saveret = \DB::table('job_saved')
                        ->join('jobs', 'jobs.id', '=', 'job_saved.job_id')
                        ->where($whereCond);

                        if ($saveret->count() > 0) {
                            $is_saved = '1';
                        }

                        // $whereCond1 = [
                        //     'facilities.active' => true,
                        //     // 'jobs.is_open' => "1",
                        //     'offers.status' => 'Offered',
                        //     'offers.nurse_id' => $nurse->id
                        // ];
                        // $ret = Job::select('jobs.id as job_id','jobs.job_type as job_type', 'jobs.*')
                        //     ->leftJoin('facilities', function ($join) {
                        //         $join->on('facilities.id', '=', 'jobs.facility_id');
                        //     })
                        //     ->join('offers', 'jobs.id', '=', 'offers.job_id')
                        //     ->where($whereCond1)
                        //     ->orderBy('offers.created_at', 'desc');
                        // $job_data = $ret->paginate(10);
                        // $j_data['nurses_applied'] = $this->jobData($job_data, $user_id);

                    }
                }
                $j_data["is_saved"] = $is_saved;
                $result[] = $j_data;
            }
        }
        return $result;
    }

    public function timeAgo($time = NULL)
    {
        // Calculate difference between current
        // time and given timestamp in seconds
        $diff     = time() - $time;
        // Time difference in seconds
        $sec     = $diff;
        // Convert time difference in minutes
        $min     = round($diff / 60);
        // Convert time difference in hours
        $hrs     = round($diff / 3600);
        // Convert time difference in days
        $days     = round($diff / 86400);
        // Convert time difference in weeks
        $weeks     = round($diff / 604800);
        // Convert time difference in months
        $mnths     = round($diff / 2600640);
        // Convert time difference in years
        $yrs     = round($diff / 31207680);
        // Check for seconds
        if ($sec <= 60) {
            $string = "$sec seconds ago";
        }
        // Check for minutes
        else if ($min <= 60) {
            if ($min == 1) {
                $string = "one minute ago";
            } else {
                $string = "$min minutes ago";
            }
        }
        // Check for hours
        else if ($hrs <= 24) {
            if ($hrs == 1) {
                $string = "an hour ago";
            } else {
                $string = "$hrs hours ago";
            }
        }
        // Check for days
        else if ($days <= 7) {
            if ($days == 1) {
                $string = "Yesterday";
            } else {
                $string = "$days days ago";
            }
        }
        // Check for weeks
        else if ($weeks <= 4.3) {
            if ($weeks == 1) {
                $string = "a week ago";
            } else {
                $string = "$weeks weeks ago";
            }
        }
        // Check for months
        else if ($mnths <= 12) {
            if ($mnths == 1) {
                $string = "a month ago";
            } else {
                $string = "$mnths months ago";
            }
        }
        // Check for years
        else {
            if ($yrs == 1) {
                $string = "one year ago";
            } else {
                $string = "$yrs years ago";
            }
        }
        return $string;
    }

    public function add_save_jobs(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'jid'=>'required'
            ]);
            $user = auth()->guard('frontend')->user();
            $rec = JobSaved::where(['nurse_id'=>$user->id, 'job_id'=>$request->jid,'is_delete'=>'0'])->first();
            $input = [
                'job_id'=>$request->jid,
                'is_save'=>'1',
                'nurse_id'=>$user->id,
            ];
            if (empty($rec)) {
                JobSaved::create($input);
                $img = asset('public/frontend/img/bookmark.png');
                $message = 'Job saved successfully.';
            }else{
                if ($rec->is_save == '1') {
                    $input['is_save'] = '0';
                    $img = asset('public/frontend/img/job-icon-bx-Vector.png');
                    $message = 'Job unsaved successfully.';
                }else{
                    $input['is_save'] = '1';
                    $img = asset('public/frontend/img/bookmark.png');
                    $message = 'Job saved successfully.';
                }
                $rec->update($input);
            }

            return new JsonResponse(['success'=>true, 'msg'=>$message, 'img'=>$img], 200);
        }

    }

    public function details($id)
    {
        $data = [];
        $data['model'] = Job::findOrFail($id);
        // $user = auth()->guard('frontend')->user();
        // dd($user->nurse->id);
        $data['jobSaved'] = new JobSaved();
        return view('jobs.details', $data);
    }

    public function apply_on_jobs(Request $request)
    {
        
        $request->validate([
            'jid'=>'required'
        ]);
        $response = [];
        $user = auth()->guard('frontend')->user();
        $job = Job::findOrFail($request->jid);
        //return response()->json(['data'=>$job], 200);
        $rec = Offer::where(['worker_user_id'=>$user->nurse->id, 'job_id'=>$request->jid])->whereNull('deleted_at')->first();
        $input = [
            'job_id'=>$request->jid,
            'created_by'=>$user->id,
            'worker_user_id'=>$user->nurse->id,
            'job_name'=>$request->job_name,
            'job_name'=>$job->job_name,
            'type'=>$job->type,
            'terms'=>$job->terms,
            'proffesion'=>$job->proffesion,
            'block_scheduling'=>$job->block_scheduling,
            'float_requirement'=>$job->float_requirement,
            'facility_shift_cancelation_policy'=>$job->facility_shift_cancelation_policy,
            'contract_termination_policy'=>$job->contract_termination_policy,
            'traveler_distance_from_facility'=>$job->traveler_distance_from_facility,
            'clinical_setting'=>$job->clinical_setting,
            'Patient_ratio'=>$job->Patient_ratio,
            'Emr'=>$job->Emr,
            'Unit'=>$job->Unit,
            'scrub_color'=>$job->scrub_color,
            'start_date'=>$job->start_date,
            'rto'=>$job->rto,
            'hours_per_week'=>$job->hours_per_week,
            'guaranteed_hours'=>$job->guaranteed_hours,
            'hours_shift'=>$job->hours_shift,
            'weeks_shift'=>$job->weeks_shift,
            'preferred_assignment_duration'=>$job->preferred_assignment_duration,
            'referral_bonus'=>$job->referral_bonus,
            'sign_on_bonus'=>$job->sign_on_bonus,
            'completion_bonus'=>$job->completion_bonus,
            'extension_bonus'=>$job->extension_bonus,
            'other_bonus'=>$job->other_bonus,
            'four_zero_one_k'=>$job->four_zero_one_k,
            'health_insaurance'=>$job->health_insaurance,
            'dental'=>$job->dental,
            'vision'=>$job->vision,
            'actual_hourly_rate'=>$job->actual_hourly_rate,
            'overtime'=>$job->overtime,
            'holiday'=>$job->holiday,
            'on_call'=>$job->on_call,
            'orientation_rate'=>$job->orientation_rate,
            'weekly_non_taxable_amount'=>$job->weekly_non_taxable_amount,
            'description'=>$job->description,
            'hours_shift'=>$job->hours_shift,
            'weekly_non_taxable_amount'=>$job->weekly_non_taxable_amount,
            'weekly_taxable_amount'=>$job->weekly_taxable_amount,
            'employer_weekly_amount'=>$job->employer_weekly_amount,
            'total_employer_amount'=>$job->total_employer_amount,
            'weekly_pay'=>$job->weekly_pay,
            'tax_status'=>$job->tax_status,
            'status'=>'Apply',
        ];
        if (empty($rec)) {
            offer::create($input);
            $message = 'Job saved successfully.';
            $saved = JobSaved::where(['nurse_id'=>$user->id, 'job_id'=>$request->jid,'is_delete'=>'0','is_save'=>'1'])->first();
            if (empty($rec)) {
                // $saved->delete();
            }
        }
        else{
            // if ($rec->is_save == '1') {
            //     $message = 'Job unsaved successfully.';
            // }else{
            //     $message = 'Job saved successfully.';
            // }
            $rec->update($input);
        }

        return new JsonResponse(['success'=>true, 'msg'=>'Applied to job successfully'], 200);
    }

    public function my_work_journey()
    {
        $user = auth()->guard('frontend')->user();
        $whereCond = [
            'jobs.is_open' => "1",
            'jobs.is_closed' => "0",
            // 'job_saved.is_delete'=>'0',
            // 'job_saved.nurse_id'=>$user->id,
        ];

        $data = [];

        switch(request()->route()->getName())
        {
            case 'my-work-journey':
                $whereCond['jobs.is_hidden'] = '0';
                $jobs = Job::select("jobs.*")
                ->join('job_saved', function ($join) use ($user){
                    $join->on('job_saved.job_id', '=', 'jobs.id')
                    ->where(function ($query) use ($user) {
                        $query->where('job_saved.is_delete', '=', '0')
                        ->where('job_saved.is_save', '=', '1')
                        ->where('job_saved.nurse_id', '=', $user->id);
                    });
                })
                ->where($whereCond)
                ->orderBy('job_saved.created_at', 'DESC')
                ->get();
                $data['type'] = 'saved';
                break;

            case 'applied-jobs':
                $jobs = Job::select("jobs.*")
                ->join('offers', function ($join) use ($user){
                    $join->on('offers.job_id', '=', 'jobs.id')
                    ->where(function ($query) use ($user) {
                        $query->whereIn('offers.status', ['Apply', 'Screening', 'Submitted'])
                        ->where('offers.active', '=', '1')
                        ->where('offers.nurse_id', '=', $user->nurse->id)
                        ->where(function($q){
                            $q->whereNull('offers.expiration')
                            ->orWhere('offers.expiration', '>', date('Y-m-d'));
                        });
                    });
                })
                ->where($whereCond)
                ->orderBy('offers.created_at', 'DESC')
                ->get();
                $data['type'] = 'applied';
                break;

            case 'hired-jobs':
                unset($whereCond['jobs.is_closed']);
                $jobs = Job::select("jobs.*")
                ->join('offers', function ($join) use ($user){
                    $join->on('offers.job_id', '=', 'jobs.id')
                    ->where(function ($query) use ($user) {
                        $query->whereIn('offers.status', ['Onboarding', 'Working'])
                        ->where('offers.active', '=', '1')
                        ->where('offers.nurse_id', '=', $user->nurse->id);

                    });
                })
                ->where($whereCond)
                ->orderBy('offers.created_at', 'DESC')
                ->get();
                $data['type'] = 'hired';
                break;

            case 'offered-jobs':
                $jobs = Job::select("jobs.*")
                ->join('offers', function ($join) use ($user){
                    $join->on('offers.job_id', '=', 'jobs.id')
                    ->where(function ($query) use ($user) {
                        $query->whereIn('offers.status', ['Offered', 'Rejected', 'Hold'])
                        ->where('offers.active', '=', '1')
                        ->where('offers.nurse_id', '=', $user->nurse->id)
                        ->where(function($q){
                            $q->whereNull('offers.expiration')
                            ->orWhere('offers.expiration', '>', date('Y-m-d'));
                        });
                    });
                })
                ->where($whereCond)
                ->orderBy('offers.created_at', 'DESC')
                ->get();
                $data['type'] = 'offered';
                break;

            case 'past-jobs':
                unset($whereCond['jobs.is_closed']);
                $jobs = Job::select("jobs.*")
                ->join('offers', function ($join) use ($user){
                    $join->on('offers.job_id', '=', 'jobs.id')
                    ->where(function ($query) use ($user) {
                        $query->where('offers.status', '=', 'Done')
                        ->where('offers.active', '=', '1')
                        ->where('offers.nurse_id', '=', $user->nurse->id)
                        ->where(function($q){
                            $q->whereNull('offers.expiration')
                            ->orWhere('offers.expiration', '>', date('Y-m-d'));
                        });
                    });
                })
                ->where($whereCond)
                ->orderBy('offers.created_at', 'DESC')
                ->get();
                $data['type'] = 'past';
                break;
            default:
                return redirect()->back();
                break;
        }

        $data['jobs'] = $jobs;
        return view('jobs.jobs', $data);
    }

    public function fetch_job_content(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'jid'=>'required',
                'type'=>'required'
            ]);
            $response = [];
            $response['success'] = true;
            $data = [];
            $job = Job::findOrFail($request->jid);
            switch($request->type)
            {
                case 'saved':
                    // $jobs = $jobCOntent;
                    $view = 'saved';
                    break;
                case 'applied':
                    // $jobs = $jobCOntent;
                    $view = 'applied';
                    break;
                case 'hired':
                    // $jobs = $jobCOntent;
                    $view = 'hired';
                    break;
                case 'offered':
                    // $jobs = $jobCOntent;
                    $view = 'offered';
                    break;
                case 'past':
                    // $jobs = $jobCOntent;
                    $view = 'past';
                    break;
                case 'counter':
                    // $jobs = $jobCOntent;

                    $distinctFilters = Keyword::distinct()->pluck('filter');
                    $keywords = [];

                    foreach ($distinctFilters as $filter) {
                        $keyword = Keyword::where('filter', $filter)->get();
                        $keywords[$filter] = $keyword;
                    }

                    $data['keywords'] = $keywords;
                    $data['countries'] = Countries::where('flag','1')
                    ->orderByRaw("CASE WHEN iso3 = 'USA' THEN 1 WHEN iso3 = 'CAN' THEN 2 ELSE 3 END")
                    ->orderBy('name','ASC')->get();
                    $data['usa'] = $usa =  Countries::where(['iso3'=>'USA'])->first();
                    $data['us_states'] = States::where('country_id', $usa->id)->get();
                    $data['us_cities'] = Cities::where('country_id', $usa->id)->get();
                    $view = 'counter_offer';

                    break;
                default:
                    return new JsonResponse(['success'=>false, 'msg'=>'Oops! something went wrong.'], 400);
                    break;
            }




            $data['model'] = $job;
            $response['content'] = view('ajax.'.$view.'_job', $data)->render();
            return new JsonResponse($response, 200);
        }
    }

    public function store_counter_offer(Request $request)
    {
        // $offerLists = Offer::where('id', $request->id)->first();
        // $nurse = Nurse::where('id', $offerLists->nurse_id)->first();
        $user = auth()->guard('frontend')->user();
        $job_data = Job::where('id', $request->jobid)->first();
        $update_array["job_name"] = ($job_data->job_name != $request->job_name)?$request->job_name:'';
        $update_array["type"] = ($job_data->type != $request->type)?$request->type:'';
        $update_array["terms"] = ($job_data->terms != $request->terms)?$request->terms:'';
        $update_array["profession"] = ($job_data->profession != $request->profession)?$request->profession:'';
        $update_array["preferred_specialty"] = ($job_data->preferred_specialty != $request->preferred_specialty)?$request->preferred_specialty:'';
        $update_array["preferred_experience"] = ($job_data->preferred_experience != $request->preferred_experience)?$request->preferred_experience:'';
        $update_array["number_of_references"] = ($job_data->number_of_references != $request->number_of_references)?$request->number_of_references:'';
        $update_array["min_title_of_reference"] = ($job_data->min_title_of_reference != $request->min_title_of_reference)?$request->min_title_of_reference:'';
        $update_array["recency_of_reference"] = ($job_data->recency_of_reference != $request->recency_of_reference)?$request->recency_of_reference:'';
        $update_array["skills"] = ($job_data->skills != $request->skills)?$request->skills:'';
        $update_array["urgency"] = ($job_data->urgency != $request->urgency)?$request->urgency:'';
        $update_array["msp"] = ($job_data->msp != $request->msp)?$request->msp:'';
        $update_array["vms"] = ($job_data->vms != $request->vms)?$request->vms:'';
        $update_array["facility"] = ($job_data->facility != $request->facility)?$request->facility:'';
        $update_array["job_location"] = ($job_data->job_location != $request->job_location)?$request->job_location:'';
        $update_array["vaccinations"] = ($job_data->vaccinations != $request->vaccinations)?$request->vaccinations:'';
        $update_array["certificate"] = ($job_data->certificate != $request->certificate)?$request->certificate:'';
        $update_array["position_available"] = ($job_data->position_available != $request->position_available)?$request->position_available:'';
        $update_array["submission_of_vms"] = ($job_data->submission_of_vms != $request->submission_of_vms)?$request->submission_of_vms:'';
        $update_array["block_scheduling"] = ($job_data->block_scheduling != $request->block_scheduling)?$request->block_scheduling:'';
        $update_array["float_requirement"] = ($job_data->float_requirement != $request->float_requirement)?$request->float_requirement:'';
        $update_array["facility_shift_cancelation_policy"] = ($job_data->facility_shift_cancelation_policy != $request->facility_shift_cancelation_policy)?$request->facility_shift_cancelation_policy:'';
        $update_array["contract_termination_policy"] = ($job_data->contract_termination_policy != $request->contract_termination_policy)?$request->contract_termination_policy:'';
        $update_array["traveler_distance_from_facility"] = ($job_data->traveler_distance_from_facility != $request->traveler_distance_from_facility)?$request->traveler_distance_from_facility:'';
        $update_array["job_id"] = ($job_data->job_id != $request->id)?$request->id:'';
        $update_array["recruiter_id"] = ($job_data->recruiter_id != $request->recruiter_id)?$request->recruiter_id:'';
        $update_array["worker_user_id"] = ($job_data->worker_user_id != $user->id)?$user->id:'';
        $update_array["compact"] = ($job_data->compact != $request->compact)?$request->compact:'';
        $update_array["facility_id"] = ($job_data->facility_id != $request->facility_id)?$request->facility_id:'';
        $update_array["clinical_setting"] = ($job_data->clinical_setting != $request->clinical_setting)?$request->clinical_setting:'';
        $update_array["Patient_ratio"] = ($job_data->Patient_ratio != $request->Patient_ratio)?$request->Patient_ratio:'';
        $update_array["emr"] = ($job_data->emr != $request->emr)?$request->emr:'';
        $update_array["Unit"] = ($job_data->Unit != $request->Unit)?$request->Unit:'';
        $update_array["Department"] = ($job_data->Department != $request->Department)?$request->Department:'';
        $update_array["Bed_Size"] = ($job_data->Bed_Size != $request->Bed_Size)?$request->Bed_Size:'';
        $update_array["Trauma_Level"] = ($job_data->Trauma_Level != $request->Trauma_Level)?$request->Trauma_Level:'';
        $update_array["scrub_color"] = ($job_data->scrub_color != $request->scrub_color)?$request->scrub_color:'';
        $update_array["start_date"] = ($job_data->start_date != $request->start_date)?$request->start_date:'';
        $update_array["as_soon_as"] = ($job_data->as_soon_as != $request->as_soon_as)?$request->as_soon_as:'';
        $update_array["rto"] = ($job_data->rto != $request->rto)?$request->rto:'';
        $update_array["preferred_shift"] = ($job_data->preferred_shift != $request->preferred_shift)?$request->preferred_shift:'';
        $update_array["hours_per_week"] = ($job_data->hours_per_week != $request->hours_per_week)?$request->hours_per_week:'';
        $update_array["guaranteed_hours"] = ($job_data->guaranteed_hours != $request->guaranteed_hours)?$request->guaranteed_hours:'';
        $update_array["hours_shift"] = ($job_data->hours_shift != $request->hours_shift)?$request->hours_shift:'';
        $update_array["weeks_shift"] = ($job_data->weeks_shift != $request->weeks_shift)?$request->weeks_shift:'';
        $update_array["preferred_assignment_duration"] = ($job_data->preferred_assignment_duration != $request->preferred_assignment_duration)?$request->preferred_assignment_duration:'';
        $update_array["referral_bonus"] = ($job_data->referral_bonus != $request->referral_bonus)?$request->referral_bonus:'';
        $update_array["sign_on_bonus"] = ($job_data->sign_on_bonus != $request->sign_on_bonus)?$request->sign_on_bonus:'';
        $update_array["completion_bonus"] = ($job_data->completion_bonus != $request->completion_bonus)?$request->completion_bonus:'';
        $update_array["extension_bonus"] = ($job_data->extension_bonus != $request->extension_bonus)?$request->extension_bonus:'';
        $update_array["other_bonus"] = ($job_data->other_bonus != $request->other_bonus)?$request->other_bonus:'';
        $update_array["four_zero_one_k"] = ($job_data->four_zero_one_k != $request->four_zero_one_k)?$request->four_zero_one_k:'';
        $update_array["health_insaurance"] = ($job_data->health_insaurance != $request->health_insaurance)?$request->health_insaurance:'';
        $update_array["dental"] = ($job_data->dental != $request->dental)?$request->dental:'';
        $update_array["vision"] = ($job_data->vision != $request->vision)?$request->vision:'';
        $update_array["actual_hourly_rate"] = ($job_data->actual_hourly_rate != $request->actual_hourly_rate)?$request->actual_hourly_rate:'';
        $update_array["overtime"] = ($job_data->overtime != $request->overtime)?$request->overtime:'';
        $update_array["holiday"] = ($job_data->holiday != $request->holiday)?$request->holiday:'';
        $update_array["on_call"] = ($job_data->on_call != $request->on_call)?$request->on_call:'';
        $update_array["orientation_rate"] = ($job_data->orientation_rate != $request->orientation_rate)?$request->orientation_rate:'';
        $update_array["weekly_non_taxable_amount"] = ($job_data->weekly_non_taxable_amount != $request->weekly_non_taxable_amount)?$request->weekly_non_taxable_amount:'';
        $update_array["description"] = ($job_data->description != $request->description)?$request->description:'';
        $update_array["weekly_taxable_amount"] = 0;
        $update_array["employer_weekly_amount"] = 0;
        $update_array["goodwork_weekly_amount"] = 0;
        $update_array["total_employer_amount"] = 0;
        $update_array["total_goodwork_amount"] = 0;
        $update_array["total_contract_amount"] = 0;
        if($update_array["hours_per_week"] == 0){
            $update_array["feels_like_per_hour"] = 0;
        }else{
            $update_array["feels_like_per_hour"] = $update_array["employer_weekly_amount"]/$update_array["hours_per_week"];
        }
        $update_array['weekly_pay'] = ($job_data->weekly_pay != $request->weekly_pay)?$request->weekly_pay:'';
        $update_array["facilitys_parent_system"] = ($job_data->facilitys_parent_system != $request->facilitys_parent_system)?$request->facilitys_parent_system:'';
        $update_array["facility_average_rating"] = ($job_data->facility_average_rating != $request->facility_average_rating)?$request->facility_average_rating:'';
        $update_array["recruiter_average_rating"] = ($job_data->recruiter_average_rating != $request->recruiter_average_rating)?$request->recruiter_average_rating:'';
        $update_array["employer_average_rating"] = ($job_data->employer_average_rating != $request->employer_average_rating)?$request->employer_average_rating:'';
        $update_array["job_city"] = ($job_data->job_city != $request->job_city)?$request->job_city:'';
        $update_array["job_state"] = ($job_data->job_state != $request->job_state)?$request->job_state:'';
        $update_array["is_draft"] = !empty($request->is_draft) ? $request->is_draft : '0';
        $update_array["is_counter"] = '1';
        $update_array["recruiter_id"] = $job_data->recruiter_id;

        /* create job */
        $update_array["created_by"] = $user->id;
        $job = DB::table('offer_jobs')->insert($update_array);
        return response()->json(['success'=>true, 'msg'=>'Counter offer created successfully']);;
    }

    public function render_matching_fields(Request $request)
    {
        if ($request->ajax()) {
            $response = [];
            switch ($request->type) {
                case 'input':
                    # code...
                    break;

                case 'value':
                    # code...
                    break;
                default:
                    # code...
                    break;
            }
        }
    }


    function test()
    {
        $data['job'] = 'College Diploma Required';
        $data['match'] = !empty($job_data['diploma']) ? true : false;
        $data['worker'] = !empty($job_data['diploma']) ? $job_data['diploma']:"";
        $data['name'] = 'Diploma';
        $data['update_key'] = 'diploma';
        $data['type'] = 'files';
        $data['worker_title'] = 'Did you really graduate?';
        $data['job_title'] = 'College Diploma Required';
        $worker_info[] = $data;

        $data['job'] = 'Drivers License';
        $data['match'] = !empty($job_data['driving_license'])?true:false;
        $data['worker'] = !empty($job_data['driving_license'])?$job_data['driving_license']:"";
        $data['name'] = 'driving_license';
        $data['update_key'] = 'driving_license';
        $data['type'] = 'files';
        $data['worker_title'] = 'Are you really allowed to drive?';
        $data['job_title'] = 'Picture of Front and Back DL';
        $worker_info[] = $data;

        $data['job'] = !empty($job['job_worked_at_facility_before'])?$job['job_worked_at_facility_before']:"";
        $data['match'] = $recs;
        $data['worker'] = !empty($job_data['worked_at_facility_before'])?$job_data['worked_at_facility_before']:"";
        $data['name'] = 'Working at Facility Before';
        $data['update_key'] = 'worked_at_facility_before';
        $data['type'] = 'checkbox';
        $data['worker_title'] = 'Are you sure you never worked here as staff?';
        $data['job_title'] = 'Have you worked here in the last 18 months?';
        $worker_info[] = $data;

        $data['job'] = "Last 4 digit of SS# to submit";
        $data['match'] = !empty($job_data['worker_ss_number'])?true:false;
        $data['worker'] = !empty($job_data['worker_ss_number'])?$job_data['worker_ss_number']:"";
        $data['name'] = 'SS Card Number';
        $data['update_key'] = 'worker_ss_number';
        $data['type'] = 'input';
        $data['worker_title'] = 'Yes we need your SS# to submit you';
        $data['job_title'] = 'Last 4 digits of SS# to submit';
        $worker_info[] = $data;

        if($job['profession'] == $job_data['highest_nursing_degree']){ $val = true; }else{ $val = false; }
        $data['job'] = isset($job['profession'])?$job['profession']:"";
        $data['match'] = $val;
        $data['worker'] = !empty($job_data['highest_nursing_degree'])?$job_data['highest_nursing_degree']:"";
        $data['name'] = 'Preofession';
        $data['update_key'] = 'highest_nursing_degree';
        $data['type'] = 'dropdown';
        $data['worker_title'] = 'What kind of Professional are you?';
        $data['job_title'] = !empty($data['job'])?$data['job']:'Profession';
        $worker_info[] = $data;

        $data['job'] = isset($job['preferred_specialty'])?$job['preferred_specialty']:"";
        $data['match'] = $speciality;
        $data['worker'] = !empty($job_data['specialty'])?$job_data['specialty']:"";
        $data['name'] = 'Speciality';
        $data['update_key'] = 'specialty';
        $data['type'] = 'dropdown';
        $data['worker_title'] = "What's your specialty?";
        $data['job_title'] = !empty($data['job'])?$data['job']:'Specialty';
        $worker_info[] = $data;

        if($job_data['nursing_license_state'] == $job_data['job_location']){ $val = true; }else{ $val = false; }
        $data['job'] = isset($job['job_location'])?$job['job_location']:"";
        $data['match'] = $val;
        $data['worker'] = !empty($job_data['nursing_license_state'])?$job_data['nursing_license_state']:"";
        $data['name'] = 'License State';
        $data['update_key'] = 'nursing_license_state';
        $data['type'] = 'dropdown';
        $data['worker_title'] = 'Where are you licensed?';
        $data['job_title'] = !empty($data['job'])?$data['job']:'Professional Licensure';
        $worker_info[] = $data;

        $data['job'] = isset($job['preferred_experience'])?$job['preferred_experience']:"";
        $data['match'] = $experience;
        $data['worker'] = !empty($job_data['experience'])?$job_data['experience']:"";
        $data['name'] = 'Experience';
        $data['update_key'] = 'experience';
        $data['type'] = 'input';
        $data['worker_title'] = 'How long have you done this?';
        $data['job_title'] = $data['job'].' Years';
        $worker_info[] = $data;

        $i = 0;
        foreach($vaccinations as $job_vacc)
        {
            $data['job'] = isset($vaccinations[$i])?$vaccinations[$i]:"Vaccinations & Immunizations";
            $data['match'] = !empty($worker_vaccination[$i])?true:false;
            $data['worker'] = isset($worker_vaccination[$i])?$worker_vaccination[$i]:"";
            $data['worker_image'] = isset($vacc_image[$i]['name'])?$vacc_image[$i]['name']:"";
            $data['name'] = $data['worker'].' vaccination';
            $data['update_key'] = 'worker_vaccination';
            $data['type'] = 'file';
            $data['worker_title'] = 'Did you get the '.$data['worker'].' Vaccines?';
            $data['job_title'] = !empty($data['job'])?$data['job'].' Required':'Vaccinations & Immunizations';
            $worker_info[] = $data;
            $i++;
            $data['worker_image'] = '';
        }

        $data['job'] = isset($job['number_of_references'])?$job['number_of_references']:"";
        $data['match'] = $worker_ref_num;
        $data['worker'] = isset($worker_reference_name)?$worker_reference_name:"";
        $data['name'] = 'Reference';
        $data['update_key'] = 'worker_reference_name';
        $data['type'] = 'multiple';
        $data['worker_title'] = 'Who are your References?';
        $data['job_title'] = !empty($data['job'])?$data['job'].' References':'number of references';
        $worker_info[] = $data;

        $data['job'] = isset($job['min_title_of_reference'])?$job['min_title_of_reference']:"";
        $data['match'] = !empty($worker_reference_title)?true:false;
        $data['worker'] = isset($worker_reference_title)?$worker_reference_title:"";
        $data['name'] = 'Reference title';
        $data['update_key'] = 'worker_reference_title';
        $data['type'] = 'multiple';
        $data['worker_title'] = 'What was their title?';
        $data['job_title'] = !empty($data['job'])?$data['job']:'min title of reference';
        $worker_info[] = $data;

        $data['job'] = isset($job['recency_of_reference'])?$job['recency_of_reference']:"";
        $data['match'] = !empty($worker_reference_recency_reference)?true:false;
        $data['worker'] = isset($worker_reference_recency_reference)?$worker_reference_recency_reference:"";
        $data['name'] = 'Recency Reference Assignment';
        $data['update_key'] = 'worker_reference_recency_reference';
        $data['type'] = 'multiple';
        $data['worker_title'] = 'Is this from your last assignment?';
        $data['job_title'] = !empty($data['job'])?$data['job'].' months':'recency of reference';
        $worker_info[] = $data;

        $i = 0;
        foreach($certificate as $job_cert)
        {
            $data['job'] = isset($certificate[$i])?$certificate[$i]:"Certifications";
            $data['match'] = !empty($worker_certificate_name[$i])?true:false;
            $data['worker'] = isset($worker_certificate_name[$i])?$worker_certificate_name[$i]:"";
            if(isset($worker_certificate_name[$i])){
                $data['worker_image'] = isset($cert_image[$i]['name'])?$cert_image[$i]['name']:"";
            }
            $data['name'] = $data['worker'];
            $data['update_key'] = 'worker_certificate';
            $data['type'] = 'file';
            $data['worker_title'] = 'No '.$data['worker'];
            $data['job_title'] = !empty($data['job'])?$data['job'].' Required':'Certifications';
            $worker_info[] = $data;
            $i++;
            $data['worker_image'] = '';
        }

        $data['job'] = isset($job['skills'])?$job['skills']:"";
        $data['match'] = !empty($job_data['skills'])?true:false;
        $data['worker'] = isset($job_data['skills'])?$job_data['skills']:"";
        if(isset($job_data['skills'])){
            $data['worker_image'] = isset($skills_checklists)?$skills_checklists:"";
        }

        $data['name'] = 'Skills';
        $data['update_key'] = 'skills';
        $data['type'] = 'file';
        $data['worker_title'] = 'Upload your latest skills checklist';
        $data['job_title'] = $data['job'].' Skills checklist';
        $worker_info[] = $data;
        $data['worker_image'] = '';

        if($job_data['eligible_work_in_us'] == 'yes'){ $eligible_work_in_us = true; }else{ $eligible_work_in_us = false; }
        $data['job'] = "Eligible to work in the US";
        $data['match'] = $eligible_work_in_us;
        $data['worker'] = isset($job_data['eligible_work_in_us'])?$job_data['eligible_work_in_us']:"";
        $data['name'] = 'eligible_work_in_us';
        $data['update_key'] = 'eligible_work_in_us';
        $data['type'] = 'checkbox';
        $data['worker_title'] = 'Does Congress allow you to work here?';
        $data['job_title'] = 'Eligible to work in the US';
        $worker_info[] = $data;

        $data['job'] = isset($job['urgency'])?$job['urgency']:"";
        $data['match'] = !empty($job_data['worker_urgency'])?true:false;
        $data['worker'] = isset($job_data['worker_urgency'])?$job_data['worker_urgency']:"";
        $data['name'] = 'worker_urgency';
        $data['update_key'] = 'worker_urgency';
        $data['type'] = 'input';
        $data['worker_title'] = "How quickly can you be ready to submit?";
        if(isset($data['job']) && $data['job'] == '1'){ $data['job'] = 'Auto Offer'; }else{
            $data['job'] = 'Urgency';
        }
        $data['job_title'] = $data['job'];
        // $data['job_title'] = isset($data['job'])?$data['job']:" Urgency";
        $worker_info[] = $data;

        $data['job'] = isset($job['position_available'])?$job['position_available']:"";
        $data['match'] = !empty($job_data["available_position"])?true:false;
        $data['worker'] = isset($job_data["available_position"])?$job_data["available_position"]:"";
        $data['name'] = 'available_position';
        $data['update_key'] = 'available_position';
        $data['type'] = 'input';
        $data['worker_title'] = 'You have applied to # jobs?';
        $data['job_title'] = !empty($data['job'])?$is_vacancy.' of '.$data['job']:'# of Positions Available';
        $worker_info[] = $data;

        $data['job'] = isset($job['msp'])?$job['msp']:"";
        $data['match'] = !empty($job_data['MSP'])?true:false;
        $data['worker'] = isset($job_data['MSP'])?$job_data['MSP']:"";
        $data['name'] = 'MSP';
        $data['update_key'] = 'MSP';
        $data['worker_title'] = 'Any MSPs you prefer to avoid?';
        $data['job_title'] = isset($data['job'])?$data['job']:'MSP';
        $data['type'] = 'dropdown';
        $worker_info[] = $data;

        $data['job'] = isset($job['vms'])?$job['vms']:"";
        $data['match'] = !empty($job_data['VMS'])?true:false;
        $data['worker'] = isset($job_data['VMS'])?$job_data['VMS']:"";
        $data['name'] = 'VMS';
        $data['update_key'] = 'VMS';
        $data['type'] = 'dropdown';
        $data['worker_title'] = "Who's your favorite VMS?";
        $data['job_title'] = isset($data['job'])?$data['job']:'VMS';
        $worker_info[] = $data;

        $data['job'] = isset($job['submission_of_vms'])?$job['submission_of_vms']:"";
        $data['match'] = !empty($job_data['submission_VMS'])?true:false;
        $data['worker'] = isset($job_data['submission_VMS'])?$job_data['submission_VMS']:"";
        $data['name'] = 'submission_VMS';
        $data['update_key'] = 'submission_VMS';
        $data['type'] = 'input';
        $data['worker_title'] = '';
        $data['job_title'] = isset($data['job'])?$data['job']:'# of Submissions in VMS';
        $worker_info[] = $data;

        $data['job'] = isset($job['block_scheduling'])?$job['block_scheduling']:"";
        $data['match'] = !empty($job_data['worker_block_scheduling'])?true:false;
        $data['worker'] = isset($job_data['worker_block_scheduling'])?$job_data['worker_block_scheduling']:"";
        $data['name'] = 'Block_scheduling';
        $data['update_key'] = 'block_scheduling';
        $data['type'] = 'input';
        $data['worker_title'] = 'Do you want block scheduling?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Block scheduling';
        $worker_info[] = $data;

        if($job_data['worker_float_requirement'] == 'yes'){ $val = true; }else{ $val = false; }
        $data['job'] = isset($job['float_requirement'])?$job['float_requirement']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_float_requirement'])?$job_data['worker_float_requirement']:"";
        $data['name'] = 'Float Requirement';
        $data['update_key'] = 'float_requirement';
        $data['type'] = 'checkbox';
        $data['worker_title'] = 'Are you willing float to?';
        $data['job_title'] = 'Float requirements';
        $worker_info[] = $data;

        $data['job'] = isset($job['facility_shift_cancelation_policy'])?$job['facility_shift_cancelation_policy']:"";
        $data['match'] = !empty($job_data['worker_facility_shift_cancelation_policy'])?true:false;
        $data['worker'] = isset($job_data['worker_facility_shift_cancelation_policy'])?$job_data['worker_facility_shift_cancelation_policy']:"";
        $data['name'] = 'Facility Shift Cancelation Policy';
        $data['update_key'] = 'facility_shift_cancelation_policy';
        $data['type'] = 'dropdown';
        $data['worker_title'] = 'What terms do you prefer?';
        $data['job_title'] = 'Facility Shift Cancellation Policy';
        $worker_info[] = $data;

        $data['job'] = isset($job['contract_termination_policy'])?$job['contract_termination_policy']:"";
        $data['match'] = !empty($job_data['worker_contract_termination_policy'])?true:false;
        $data['worker'] = isset($job_data['worker_contract_termination_policy'])?$job_data['worker_contract_termination_policy']:"";
        $data['name'] = 'Contract Terminology';
        $data['update_key'] = 'contract_termination_policy';
        $data['type'] = 'dropdown';
        $data['worker_title'] = 'What terms do you prefer?';
        $data['job_title'] = 'Contract Termination Policy';
        $worker_info[] = $data;

        if($job['traveler_distance_from_facility'] == $job_data['distance_from_your_home']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['traveler_distance_from_facility'])?$job['traveler_distance_from_facility']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['distance_from_your_home'])?$job_data['distance_from_your_home']:"";
        $data['name'] = 'distance from facility';
        $data['update_key'] = 'distance_from_your_home';
        $data['type'] = 'input';
        $data['worker_title'] = 'Where does the IRS think you live?';
        $data['job_title'] = !empty($data['job'])?$data['job'].' miles':'Traveler Distance From Facility';
        $worker_info[] = $data;

        $data['job'] = isset($job['facility'])?$job['facility']:"";
        $data['match'] = !empty($job_data['facilities_you_like_to_work_at'])?true:false;
        $data['worker'] = isset($job_data['facilities_you_like_to_work_at'])?$job_data['facilities_you_like_to_work_at']:"";
        $data['name'] = 'Facility available upon request';
        $data['update_key'] = 'facilities_you_like_to_work_at';
        $data['type'] = 'dropdown';
        $data['worker_title'] = 'What facilities have you worked at?';
        $data['job_title'] = isset($job['facility'])?$job['facility']:'Facility';
        $worker_info[] = $data;

        $data['job'] = isset($job['facilitys_parent_system'])?$job['facilitys_parent_system']:"";
        $data['match'] = !empty($job_data['worker_facility_parent_system'])?true:false;
        $data['worker'] = isset($job_data['worker_facility_parent_system'])?$job_data['worker_facility_parent_system']:"";
        $data['name'] = 'facility parent system';
        $data['update_key'] = 'worker_facility_parent_system';
        $data['type'] = 'input';
        $data['worker_title'] = 'What facilities would you like to work at?';
        $data['job_title'] = "Facility's Parent System";
        $worker_info[] = $data;

        $data['job'] = isset($job['facility_average_rating'])?$job['facility_average_rating']:"";
        $data['match'] = !empty($job_data['avg_rating_by_facilities'])?true:false;
        $data['worker'] = isset($job_data['avg_rating_by_facilities'])?$job_data['avg_rating_by_facilities']:"";
        $data['name'] = 'facility average rating';
        $data['update_key'] = 'avg_rating_by_facilities';
        $data['type'] = 'input';
        $data['worker_title'] = 'your average rating by your facilities';
        $data['job_title'] = 'Facility Average Rating';
        $worker_info[] = $data;

        $data['job'] = isset($job['recruiter_average_rating'])?$job['recruiter_average_rating']:"";
        $data['match'] = !empty($job_data['worker_avg_rating_by_recruiters'])?true:false;
        $data['worker'] = isset($job_data['worker_avg_rating_by_recruiters'])?$job_data['worker_avg_rating_by_recruiters']:"";
        $data['name'] = 'recruiter average rating';
        $data['update_key'] = 'worker_avg_rating_by_recruiters';
        $data['type'] = 'input';
        $data['worker_title'] = 'your average rating by your recruiters';
        $data['job_title'] = 'Recruiter Average Rating';
        $worker_info[] = $data;

        $data['job'] = isset($job['employer_average_rating'])?$job['employer_average_rating']:"";
        $data['match'] = !empty($job_data['worker_avg_rating_by_employers'])?true:false;
        $data['worker'] = isset($job_data['worker_avg_rating_by_employers'])?$job_data['worker_avg_rating_by_employers']:"";
        $data['name'] = 'employer average rating';
        $data['update_key'] = 'worker_avg_rating_by_employers';
        $data['type'] = 'input';
        $data['worker_title'] = 'your average rating by your employers';
        $data['job_title'] = 'Employer Average Rating';
        $worker_info[] = $data;

        if($job['clinical_setting'] == $job_data['clinical_setting_you_prefer']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['clinical_setting'])?$job['clinical_setting']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['clinical_setting_you_prefer'])?$job_data['clinical_setting_you_prefer']:"";
        $data['name'] = 'Clinical Setting';
        $data['update_key'] = 'clinical_setting_you_prefer';
        $data['type'] = 'dropdown';
        $data['worker_title'] = 'What setting do you prefer?';
        $data['job_title'] = isset($data['job'])?$data['job']:' Clinical Setting';
        $worker_info[] = $data;

        $data['job'] = isset($job['Patient_ratio'])?$job['Patient_ratio']:"";
        $data['match'] = !empty($job_data['worker_patient_ratio'])?true:false;
        $data['worker'] = isset($job_data['worker_patient_ratio'])?$job_data['worker_patient_ratio']:"";
        $data['name'] = 'patient ratio';
        $data['update_key'] = 'worker_patient_ratio';
        $data['type'] = 'input';
        $data['worker_title'] = 'How many patients can you handle?';
        $data['job_title'] = 'Patient ratio';
        $worker_info[] = $data;

        $data['job'] = isset($job['emr'])?$job['emr']:"";
        $data['match'] = !empty($job_data['worker_emr'])?true:false;
        $data['worker'] = isset($job_data['worker_emr'])?$job_data['worker_emr']:"";
        $data['name'] = 'EMR';
        $data['update_key'] = 'worker_emr';
        $data['type'] = 'dropdown';
        $data['worker_title'] = 'What EMRs have you used?';
        $data['job_title'] = isset($data['job'])?$data['job']:'EMR';
        $worker_info[] = $data;

        $data['job'] = isset($job['Unit'])?$job['Unit']:"";
        $data['match'] = !empty($job_data['worker_unit'])?true:false;
        $data['worker'] = isset($job_data['worker_unit'])?$job_data['worker_unit']:"";
        $data['name'] = 'Unit';
        $data['update_key'] = 'worker_unit';
        $data['type'] = 'input';
        $data['worker_title'] = 'Fav Unit?';
        $data['job_title'] = 'Unit';
        $worker_info[] = $data;

        $data['job'] = isset($job['Department'])?$job['Department']:"";
        $data['match'] = !empty($job_data['worker_department'])?true:false;
        $data['worker'] = isset($job_data['worker_department'])?$job_data['worker_department']:"";
        $data['name'] = 'Department';
        $data['update_key'] = 'worker_department';
        $data['type'] = 'input';
        $data['worker_title'] = 'Fav Department?';
        $data['job_title'] = 'Department';
        $worker_info[] = $data;

        $data['job'] = isset($job['Bed_Size'])?$job['Bed_Size']:"";
        $data['match'] = !empty($job_data['worker_bed_size'])?true:false;
        $data['worker'] = isset($job_data['worker_bed_size'])?$job_data['worker_bed_size']:"";
        $data['name'] = 'Bed Size';
        $data['update_key'] = 'worker_bed_size';
        $data['type'] = 'input';
        $data['worker_title'] = 'king or california king ?';
        $data['job_title'] = isset($job['Bed_Size'])?$job['Bed_Size']:'Bed Size';
        $worker_info[] = $data;

        $data['job'] = isset($job['Trauma_Level'])?$job['Trauma_Level']:"";
        $data['match'] = !empty($job_data['worker_trauma_level'])?true:false;
        $data['worker'] = isset($job_data['worker_trauma_level'])?$job_data['worker_trauma_level']:"";
        $data['name'] = 'Trauma Level';
        $data['update_key'] = 'worker_trauma_level';
        $data['type'] = 'input';
        $data['worker_title'] = 'Ideal trauma level?';
        $data['job_title'] = isset($job['Trauma_Level'])?$job['Trauma_Level']:'Trauma Level';
        $worker_info[] = $data;

        if($job['scrub_color'] == $job_data['worker_scrub_color']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['scrub_color'])?$job['scrub_color']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_scrub_color'])?$job_data['worker_scrub_color']:"";
        $data['name'] = 'Scrub color';
        $data['update_key'] = 'worker_scrub_color';
        $data['type'] = 'input';
        $data['worker_title'] = 'Fav Scrub Brand?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Scrub Color';
        $worker_info[] = $data;

        if($job['job_city'] == $job_data['worker_facility_city']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['job_city'])?$job['job_city']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_facility_city'])?$job_data['worker_facility_city']:"";
        $data['name'] = 'Facility City';
        $data['update_key'] = 'worker_facility_city';
        $data['type'] = 'dropdown';
        $data['worker_title'] = "Cities you'd like to work?";
        $data['job_title'] = isset($data['job'])?$data['job']:'Facility City';
        $worker_info[] = $data;


        if($job['job_state'] == $job_data['worker_facility_state_code']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['job_state'])?$job['job_state']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_facility_state_code'])?$job_data['worker_facility_state_code']:"";
        $data['name'] = 'Facility state';
        $data['update_key'] = 'worker_facility_state_code';
        $data['type'] = 'dropdown';
        $data['worker_title'] = "States you'd like to work?";
        $data['job_title'] = isset($data['job'])?$data['job']:'Facility State Code';
        $worker_info[] = $data;

        $data['job'] = "InterviewDates";
        $data['match'] = !empty($job_data['worker_interview_dates'])?true:false;
        $data['worker'] = isset($job_data['worker_interview_dates'])?$job_data['worker_interview_dates']:"";
        $data['name'] = 'Interview dates';
        $data['update_key'] = 'worker_interview_dates';
        $data['type'] = 'Interview dates';
        $data['worker_title'] = "Any days you're not available?";
        $data['job_title'] = 'InterviewDates';
        $worker_info[] = $data;

        $data['job'] = isset($job['as_soon_as'])?$job['as_soon_as']:"";
        $data['match'] = !empty($job_data['worker_as_soon_as_posible'])?true:false;
        $data['worker'] = isset($job_data['worker_as_soon_as_posible'])?$job_data['worker_as_soon_as_posible']:"";
        $data['name'] = 'As Soon As';
        $data['update_key'] = 'worker_as_soon_as_posible';
        $data['type'] = 'checkbox';
        $data['worker_title'] = 'When can you start?';
        $data['job_title'] = isset($job['as_soon_as'])?$job['as_soon_as']:'Start Date';
        $worker_info[] = $data;

        if($job['rto'] == $job_data['worker_rto']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['rto'])?$job['rto']:"";
        $data['match'] = $val;
        $data['worker'] = "Any time Off?";
        $data['name'] = 'RTO';
        $data['update_key'] = 'clinical_setting_you_prefer';
        $data['type'] = 'input';
        $data['worker_title'] = 'Any time off?';
        $data['job_title'] = isset($data['job'])?$data['job']:'RTO';
        $worker_info[] = $data;

        if($job['preferred_shift'] == $job_data['worker_shift_time_of_day']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['preferred_shift'])?$job['preferred_shift']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_shift_time_of_day'])?$job_data['worker_shift_time_of_day']:"";
        $data['name'] = 'Shift';
        $data['update_key'] = 'worker_shift_time_of_day';
        $data['type'] = 'dropdown';
        $data['worker_title'] = 'Fav Shift?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Shift Time of Day';
        $worker_info[] = $data;


        if($job['hours_per_week'] == $job_data['worker_hours_per_week']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['hours_per_week'])?$job['hours_per_week']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_hours_per_week'])?$job_data['worker_hours_per_week']:"";
        $data['name'] = 'Hours/week';
        $data['update_key'] = 'worker_hours_per_week';
        $data['type'] = 'input';
        $data['worker_title'] = 'Ideal Hours per week?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Hours/Week';
        $worker_info[] = $data;

        if($job['guaranteed_hours'] == $job_data['worker_guaranteed_hours']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['guaranteed_hours'])?$job['guaranteed_hours']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_guaranteed_hours'])?$job_data['worker_guaranteed_hours']:"";
        $data['name'] = 'Guaranteed Hours';
        $data['update_key'] = 'worker_guaranteed_hours';
        $data['type'] = 'input';
        $data['worker_title'] = 'open to jobs with no guaranteed hours?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Guaranteed Hours';
        $worker_info[] = $data;

        if($job['hours_shift'] == $job_data['worker_hours_shift']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['hours_shift'])?$job['hours_shift']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_hours_shift'])?$job_data['worker_hours_shift']:"";
        $data['name'] = 'Shift Hours';
        $data['update_key'] = 'worker_hours_shift';
        $data['type'] = 'input';
        $data['worker_title'] = 'prefered hours per shift?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Hours/Shift';
        $worker_info[] = $data;

        if($job['preferred_assignment_duration'] == $job_data['worker_weeks_assignment']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['preferred_assignment_duration'])?$job['preferred_assignment_duration']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_weeks_assignment'])?$job_data['worker_weeks_assignment']:"";
        $data['name'] = 'Assignment in weeks';
        $data['update_key'] = 'worker_weeks_assignment';
        $data['type'] = 'input';
        $data['worker_title'] = 'How many weeks?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Weeks/Assignment';
        $worker_info[] = $data;

        if($job['weeks_shift'] == $job_data['worker_shifts_week']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['weeks_shift'])?$job['weeks_shift']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_shifts_week'])?$job_data['worker_shifts_week']:"";
        $data['name'] = 'Shift Week';
        $data['update_key'] = 'worker_shifts_week';
        $data['type'] = 'input';
        $data['worker_title'] = 'Ideal shifts per week?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Shifts/Week';
        $worker_info[] = $data;

        if($job['referral_bonus'] == $job_data['worker_referral_bonus']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['referral_bonus'])?$job['referral_bonus']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_referral_bonus'])?$job_data['worker_referral_bonus']:"";
        $data['name'] = 'Refferel Bonus';
        $data['update_key'] = 'worker_referral_bonus';
        $data['type'] = 'input';
        $data['worker_title'] = '# of people you have referred';
        $data['job_title'] = isset($data['job'])?$data['job']:'Referral Bonus';
        $worker_info[] = $data;

        if(($job['sign_on_bonus'] == $job_data['worker_sign_on_bonus']) && (!empty($job_data['worker_sign_on_bonus']))){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['sign_on_bonus'])?$job['sign_on_bonus']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_sign_on_bonus'])?$job_data['worker_sign_on_bonus']:"";
        $data['name'] = 'Sign On Bonus';
        $data['update_key'] = 'worker_sign_on_bonus';
        $data['type'] = 'input';
        $data['worker_title'] = 'What kind of bonus do you expect?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Sign-On Bonus';
        $worker_info[] = $data;

        if($job['completion_bonus'] == $job_data['worker_completion_bonus']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['completion_bonus'])?$job['completion_bonus']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_completion_bonus'])?$job_data['worker_completion_bonus']:"";
        $data['name'] = 'Completion Bonus';
        $data['update_key'] = 'worker_completion_bonus';
        $data['type'] = 'input';
        $data['worker_title'] = 'What kind of bonus do you deserve?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Completion Bonus';
        $worker_info[] = $data;

        if($job['extension_bonus'] == $job_data['worker_extension_bonus']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['extension_bonus'])?$job['extension_bonus']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_extension_bonus'])?$job_data['worker_extension_bonus']:"";
        $data['name'] = 'extension bonus';
        $data['update_key'] = 'worker_extension_bonus';
        $data['type'] = 'input';
        $data['worker_title'] = 'What are you comparing this too?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Extension Bonus';
        $worker_info[] = $data;

        $data['job'] = isset($job['other_bonus'])?$job['other_bonus']:"";
        $data['match'] = !empty($job_data['worker_other_bonus'])?true:false;
        $data['worker'] = isset($job_data['worker_other_bonus'])?$job_data['worker_other_bonus']:"";
        $data['name'] = 'Other Bonus';
        $data['update_key'] = 'worker_other_bonus';
        $data['type'] = 'input';
        $data['worker_title'] = 'Other bonuses you want?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Other Bonus';
        $worker_info[] = $data;

        $data['job'] = isset($job['four_zero_one_k'])?$job['four_zero_one_k']:"";
        $data['match'] = !empty($job_data['how_much_k'])?true:false;
        $data['worker'] = isset($job_data['how_much_k'])?$job_data['how_much_k']:"";
        $data['name'] = '401k';
        $data['update_key'] = 'how_much_k';
        $data['type'] = 'dropdown';
        $data['worker_title'] = 'How much do you want this?';
        $data['job_title'] = isset($data['job'])?$data['job']:'401K';
        $worker_info[] = $data;

        $data['job'] = isset($job['health_insaurance'])?$job['health_insaurance']:"";
        $data['match'] = !empty($job_data['worker_health_insurance'])?true:false;
        $data['worker'] = isset($job_data['worker_health_insurance'])?$job_data['worker_health_insurance']:"";
        $data['name'] = 'Health Insaurance';
        $data['update_key'] = 'worker_health_insurance';
        $data['type'] = 'dropdown';
        $data['worker_title'] = 'How much do you want this?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Health Insurance';
        $worker_info[] = $data;

        $data['job'] = isset($job['dental'])?$job['dental']:"";
        $data['match'] = !empty($job_data['worker_dental'])?true:false;
        $data['worker'] = isset($job_data['worker_dental'])?$job_data['worker_dental']:"";
        $data['name'] = 'Dental';
        $data['update_key'] = 'worker_dental';
        $data['type'] = 'dropdown';
        $data['worker_title'] = 'How much do you want this?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Dental';
        $worker_info[] = $data;

        $data['job'] = isset($job['vision'])?$job['vision']:"";
        $data['match'] = !empty($job_data['worker_vision'])?true:false;
        $data['worker'] = isset($job_data['worker_vision'])?$job_data['worker_vision']:"";
        $data['name'] = 'Vision';
        $data['update_key'] = 'worker_vision';
        $data['type'] = 'dropdown';
        $data['worker_title'] = 'How much do you want this?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Vision';
        $worker_info[] = $data;

        if($job['actual_hourly_rate'] == $job_data['worker_actual_hourly_rate']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['actual_hourly_rate'])?$job['actual_hourly_rate']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_actual_hourly_rate'])?$job_data['worker_actual_hourly_rate']:"";
        $data['name'] = 'Actually Rate';
        $data['update_key'] = 'worker_actual_hourly_rate';
        $data['type'] = 'input';
        $data['worker_title'] = 'What range is fair?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Actual Hourly rate';
        $worker_info[] = $data;

        if($job['feels_like_per_hour'] == $job_data['worker_feels_like_hour']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['feels_like_per_hour'])?$job['feels_like_per_hour']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_feels_like_hour'])?$job_data['worker_feels_like_hour']:"";
        $data['name'] = 'feels/$hr';
        $data['update_key'] = 'worker_feels_like_hour';
        $data['type'] = 'input';
        $data['worker_title'] = 'Does this seem fair based on the market?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Feels Like $/hr';
        $worker_info[] = $data;

        $data['job'] = isset($job['overtime'])?$job['overtime']:"";
        $data['match'] = !empty($job_data['worker_overtime'])?true:false;
        $data['worker'] = isset($job_data['worker_overtime'])?$job_data['worker_overtime']:"";
        $data['name'] = 'Overtime';
        $data['update_key'] = 'worker_overtime';
        $data['type'] = 'input';
        $data['worker_title'] = 'Would you work more overtime for a higher OT rate?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Overtime';
        $worker_info[] = $data;

        $data['job'] = isset($job['holiday'])?$job['holiday']:"";
        $data['match'] = !empty($job_data['worker_holiday'])?true:false;
        $data['worker'] = isset($job_data['worker_holiday'])?$job_data['worker_holiday']:"";
        $data['name'] = 'Holiday';
        $data['update_key'] = 'worker_holiday';
        $data['type'] = 'date';
        $data['worker_title'] = 'Any holidays you refuse to work?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Holiday';
        $worker_info[] = $data;

        $data['job'] = isset($job['on_call'])?$job['on_call']:"";
        $data['match'] = !empty($job_data['worker_holiday'])?true:false;
        $data['worker'] = isset($job_data['worker_on_call'])?$job_data['worker_on_call']:"";
        $data['name'] = 'On call';
        $data['update_key'] = 'worker_on_call';
        $data['type'] = 'input';
        $data['worker_title'] = 'Will you do call?';
        $data['job_title'] = isset($data['job'])?$data['job']:'On Call';
        $worker_info[] = $data;

        $data['job'] = isset($job['call_back'])?$job['call_back']:"";
        $data['match'] = !empty($job_data['worker_call_back'])?true:false;
        $data['worker'] = isset($job_data['worker_call_back'])?$job_data['worker_call_back']:"";
        $data['name'] = 'Call Back';
        $data['update_key'] = 'worker_call_back';
        $data['type'] = 'input';
        $data['worker_title'] = 'Is this rate reasonable?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Call Back';
        $worker_info[] = $data;

        $data['job'] = isset($job['orientation_rate'])?$job['orientation_rate']:"";
        $data['match'] = !empty($job_data['worker_orientation_rate'])?true:false;
        $data['worker'] = isset($job_data['worker_orientation_rate'])?$job_data['worker_orientation_rate']:"";
        $data['name'] = 'Orientation Rate';
        $data['update_key'] = 'worker_orientation_rate';
        $data['type'] = 'input';
        $data['worker_title'] = 'Is this rate reasonable?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Orientation Rate';
        $worker_info[] = $data;

        if($job['weekly_taxable_amount'] == $job_data['worker_weekly_taxable_amount']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['weekly_taxable_amount'])?$job['weekly_taxable_amount']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_weekly_taxable_amount'])?$job_data['worker_weekly_taxable_amount']:"";
        $data['name'] = 'Weekly Taxable amount';
        $data['update_key'] = 'worker_weekly_taxable_amount';
        $data['type'] = 'input';
        $data['worker_title'] = '';
        $data['job_title'] = isset($data['job'])?$data['job']:'Weekly Taxable amount';
        $worker_info[] = $data;

        if($job['weekly_non_taxable_amount'] == $job_data['worker_weekly_non_taxable_amount']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['weekly_non_taxable_amount'])?$job['weekly_non_taxable_amount']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_weekly_non_taxable_amount'])?$job_data['worker_weekly_non_taxable_amount']:"";
        $data['name'] = 'Weekly Non Taxable Amount';
        $data['update_key'] = 'worker_weekly_non_taxable_amount';
        $data['type'] = 'input';
        $data['worker_title'] = 'Are you going to duplicate expenses?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Weekly non-taxable amount';
        $worker_info[] = $data;

        if($job['employer_weekly_amount'] == $job_data['worker_employer_weekly_amount']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['employer_weekly_amount'])?$job['employer_weekly_amount']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_employer_weekly_amount'])?$job_data['worker_employer_weekly_amount']:"";
        $data['name'] = 'Employer Weekly Amount';
        $data['update_key'] = 'worker_employer_weekly_amount';
        $data['type'] = 'input';
        $data['worker_title'] = 'What range is reasonable?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Employer Weekly Amount';
        $worker_info[] = $data;

        if($job['goodwork_weekly_amount'] == $job_data['worker_goodwork_weekly_amount']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['goodwork_weekly_amount'])?$job['goodwork_weekly_amount']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_goodwork_weekly_amount'])?$job_data['worker_goodwork_weekly_amount']:"";
        $data['name'] = 'Goodwork Weekly Amount';
        $data['update_key'] = 'worker_goodwork_weekly_amount';
        $data['type'] = 'input';
        $data['worker_title'] = 'you only have (count down time) left before your rate drops from 5% to 2%';
        $data['job_title'] = isset($data['job'])?$data['job']:'Goodwork Weekly Amount';
        $worker_info[] = $data;

        if($job['total_employer_amount'] == $job_data['worker_total_employer_amount']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['total_employer_amount'])?$job['total_employer_amount']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_total_employer_amount'])?$job_data['worker_total_employer_amount']:"";
        $data['name'] = 'Total Employer Amount';
        $data['update_key'] = 'worker_total_employer_amount';
        $data['type'] = 'input';
        $data['worker_title'] = '';
        $data['job_title'] = isset($data['job'])?$data['job']:'Total Employer Amount';
        $worker_info[] = $data;

        if($job['total_goodwork_amount'] == $job_data['worker_total_goodwork_amount']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['total_goodwork_amount'])?$job['total_goodwork_amount']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_total_goodwork_amount'])?$job_data['worker_total_goodwork_amount']:"";
        $data['name'] = 'Total Goodwork Amount';
        $data['update_key'] = 'worker_total_goodwork_amount';
        $data['type'] = 'input';
        $data['worker_title'] = 'How would you spend those extra funds?';
        $data['job_title'] = isset($data['job'])?$data['job']:'Total Goodwork Amount';
        $worker_info[] = $data;

        if($job['total_contract_amount'] == $job_data['worker_total_contract_amount']){ $val = true; }else{$val = false;}
        $data['job'] = isset($job['total_contract_amount'])?$job['total_contract_amount']:"";
        $data['match'] = $val;
        $data['worker'] = isset($job_data['worker_total_contract_amount'])?$job_data['worker_total_contract_amount']:"";
        $data['name'] = 'Total Contract Amount';
        $data['update_key'] = 'worker_total_contract_amount';
        $data['type'] = 'input';
        $data['worker_title'] = '';
        $data['job_title'] = isset($data['job'])?$data['job']:'Total Contract Amount';
        $worker_info[] = $data;

        $data['job'] = "Goodwork Number";
        $data['match'] = !empty($worker_goodwork_number)?true:false;
        $data['worker'] = isset($job_data['worker_goodwork_number'])?$job_data['worker_goodwork_number']:"";
        $data['name'] = 'goodwork number';
        $data['update_key'] = 'worker_goodwork_number';
        $data['type'] = 'input';
        $data['worker_title'] = '';
        $data['job_title'] = 'Goodwork Number';
        $worker_info[] = $data;

        $result['worker_info'] = $worker_info;
    }

}
