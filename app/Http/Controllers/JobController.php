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
use App\Models\{User, Nurse, Follows, NurseReference, Job, Offer, NurseAsset, Keyword, Facility, Availability, Countries, States, Cities, JobSaved, OffersLogs};
use Auth;
class JobController extends Controller
{
    public function explore(Request $request)
    {
        try {
            // commenting this for now we need to return only jobs data

            $data = [];
            $data['user'] = auth()->guard('frontend')->user();
            $data['jobSaved'] = new JobSaved();
            $data['professions'] = Keyword::where(['filter' => 'Profession', 'active' => '1'])->get();
            $data['terms'] = Keyword::where(['filter' => 'jobType', 'active' => '1'])->get();
            $data['prefered_shifts'] = Keyword::where(['filter' => 'PreferredShift', 'active' => '1'])->get();
            $data['usa'] = $usa = Countries::where(['iso3' => 'USA'])->first();
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
            $data['shifts'] = isset($request->shifts) ? explode('-', $request->shifts) : [];
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
            $jobs_id = Offer::where('nurse_id', $nurse->id)
                ->select('job_id')
                ->get();

            // $checkoffer = DB::table('blocked_users')->where('worker_id', $nurse['id'])->first();

            $whereCond = [
                'facilities.active' => true,
                'jobs.is_open' => '1',
                'jobs.is_hidden' => '0',
                'jobs.is_closed' => '0',
                // 'job_saved.is_delete'=>'0',
                // 'job_saved.nurse_id'=>$user->id,
            ];

            $ret = Job::select('jobs.id as job_id', 'jobs.auto_offers as auto_offers', 'jobs.*')
                ->leftJoin('facilities', function ($join) {
                    $join->on('facilities.id', '=', 'jobs.facility_id');
                })
                ->leftJoin('job_saved', function ($join) use ($user) {
                    $join->on('job_saved.job_id', '=', 'jobs.id')->where(function ($query) use ($user) {
                        $query->where('job_saved.is_delete', '=', 0)->where('job_saved.nurse_id', '=', $user->id);
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
            if ($data['city']) {
                $ret->where('jobs.job_city', '=', $data['city']);
            }

            if ($data['state']) {
                $ret->where('jobs.job_state', '=', $data['state']);
            }

            if (count($data['job_type'])) {
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

            $resl = Job::select('jobs.*', 'name')->leftJoin('facilities', function ($join) {
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

            return view('site.explore_jobs', $data);
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

    public function jobData($jobdata, $user_id = '')
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

            foreach ($jobdata as $key => $job) {
                $j_data['offer_id'] = isset($job->offer_id) ? $job->offer_id : '';
                $j_data['job_id'] = isset($job->job_id) ? $job->job_id : '';
                $j_data['end_date'] = isset($job->end_date) ? date('d F Y', strtotime($job->end_date)) : '';
                $j_data['job_type'] = isset($job->job_type) ? $job->job_type : '';
                $j_data['type'] = isset($job->type) ? $job->type : '';
                $j_data['job_name'] = isset($job->job_name) ? $job->job_name : '';
                $j_data['keyword_title'] = isset($job->keyword_title) ? $job->keyword_title : '';
                $j_data['keyword_filter'] = isset($job->keyword_filter) ? $job->keyword_filter : '';
                $j_data['auto_offers'] = isset($job->auto_offers) ? $job->auto_offers : '';

                $j_data['job_location'] = isset($job->job_location) ? $job->job_location : '';
                $j_data['position_available'] = isset($job->position_available) ? $job->position_available : '';
                $j_data['employer_weekly_amount'] = isset($job->employer_weekly_amount) ? $job->employer_weekly_amount : '';
                $j_data['weekly_pay'] = isset($job->weekly_pay) ? $job->weekly_pay : '';
                $j_data['hours_per_week'] = isset($job->hours_per_week) ? $job->hours_per_week : 0;

                $j_data['preferred_specialty'] = isset($job->preferred_specialty) ? $job->preferred_specialty : '';
                $j_data['preferred_specialty_definition'] = isset($specialties[$job->preferred_specialty]) ? $specialties[$job->preferred_specialty] : '';

                $j_data['preferred_assignment_duration'] = isset($job->preferred_assignment_duration) ? $job->preferred_assignment_duration : '';
                $j_data['preferred_assignment_duration_definition'] = isset($assignmentDurations[$job->preferred_assignment_duration]) ? $assignmentDurations[$job->preferred_assignment_duration] : '';
                if (isset($j_data['preferred_assignment_duration_definition']) && !empty($j_data['preferred_assignment_duration_definition'])) {
                    $assignment = explode(' ', $assignmentDurations[$job->preferred_assignment_duration]);
                    $j_data['preferred_assignment_duration_definition'] = $assignment[0]; // 12 Week
                }
                $j_data['preferred_shift_duration'] = isset($job->preferred_shift) ? $job->preferred_shift : '';
                // $j_data["preferred_shift_duration_definition"] = isset($shifts[$job->preferred_shift_duration]) ? $shifts[$job->preferred_shift_duration] : "";

                $j_data['preferred_work_location'] = isset($job->preferred_work_location) ? $job->preferred_work_location : '';
                $j_data['preferred_work_location_definition'] = isset($workLocations[$job->preferred_work_location]) ? $workLocations[$job->preferred_work_location] : '';

                $j_data['preferred_work_area'] = isset($job->preferred_work_area) ? $job->preferred_work_area : '';
                $j_data['preferred_days_of_the_week'] = isset($job->preferred_days_of_the_week) ? explode(',', $job->preferred_days_of_the_week) : [];
                $j_data['preferred_hourly_pay_rate'] = isset($job->preferred_hourly_pay_rate) ? $job->preferred_hourly_pay_rate : '';
                $j_data['preferred_experience'] = isset($job->preferred_experience) ? $job->preferred_experience : '';
                $j_data['description'] = isset($job->description) ? $job->description : '';
                $j_data['created_at'] = isset($job->created_at) ? date('d-F-Y h:i A', strtotime($job->created_at)) : '';
                // if(!empty($job->created_at) && $job->created_at){
                //     $j_data["created_at_definition"] = isset($job->created_at) ? "Recently Added" : "";
                // }else{
                $j_data['created_at_definition'] = isset($job->created_at) ? 'Posted ' . $this->timeAgo(date(strtotime($job->created_at))) : '';
                // }
                $j_data['updated_at'] = isset($job->updated_at) ? date('d F Y', strtotime($job->updated_at)) : '';
                $j_data['deleted_at'] = isset($job->deleted_at) ? date('d-F-Y h:i A', strtotime($job->deleted_at)) : '';
                $j_data['created_by'] = isset($job->created_by) ? $job->created_by : '';
                $j_data['slug'] = isset($job->slug) ? $job->slug : '';
                $j_data['active'] = isset($job->active) ? $job->active : '';
                $j_data['facility_id'] = isset($job->facility_id) ? $job->facility_id : '';
                $j_data['job_video'] = isset($job->job_video) ? $job->job_video : '';

                $j_data['seniority_level'] = isset($job->seniority_level) ? $job->seniority_level : '';
                $j_data['seniority_level_definition'] = isset($seniorityLevels[$job->seniority_level]) ? $seniorityLevels[$job->seniority_level] : '';

                $j_data['job_function'] = isset($job->job_function) ? $job->job_function : '';
                $j_data['job_function_definition'] = isset($jobFunctions[$job->job_function]) ? $jobFunctions[$job->job_function] : '';

                $j_data['responsibilities'] = isset($job->responsibilities) ? $job->responsibilities : '';
                $j_data['qualifications'] = isset($job->qualifications) ? $job->qualifications : '';

                $j_data['job_cerner_exp'] = isset($job->job_cerner_exp) ? $job->job_cerner_exp : '';
                $j_data['job_cerner_exp_definition'] = isset($ehrProficienciesExp[$job->job_cerner_exp]) ? $ehrProficienciesExp[$job->job_cerner_exp] : '';

                $j_data['job_meditech_exp'] = isset($job->job_meditech_exp) ? $job->job_meditech_exp : '';
                $j_data['job_meditech_exp_definition'] = isset($ehrProficienciesExp[$job->job_meditech_exp]) ? $ehrProficienciesExp[$job->job_meditech_exp] : '';

                $j_data['job_epic_exp'] = isset($job->job_epic_exp) ? $job->job_epic_exp : '';
                $j_data['job_epic_exp_definition'] = isset($ehrProficienciesExp[$job->job_epic_exp]) ? $ehrProficienciesExp[$job->job_epic_exp] : '';

                $j_data['job_other_exp'] = isset($job->job_other_exp) ? $job->job_other_exp : '';
                // $j_data["job_photos"] = isset($job->job_photos) ? $job->job_photos : "";
                $j_data['video_embed_url'] = isset($job->video_embed_url) ? $job->video_embed_url : '';
                $j_data['is_open'] = isset($job->is_open) ? $job->is_open : '';
                $j_data['name'] = isset($job->facility->name) ? $job->facility->name : '';
                $j_data['address'] = isset($job->facility->address) ? $job->facility->address : '';
                // $j_data["city"] = isset($job->facility->city) ? $job->facility->city : "";
                // $j_data["state"] = isset($job->facility->state) ? $job->facility->state : "";
                $j_data['city'] = isset($job->job_city) ? $job->job_city : '';
                $j_data['state'] = isset($job->job_state) ? $job->job_state : '';
                $j_data['postcode'] = isset($job->facility->postcode) ? $job->facility->postcode : '';
                $j_data['type'] = isset($job->facility->type) ? $job->facility->type : '';

                $j_data['facility_logo'] = isset($job->facility->facility_logo) ? url('public/images/facilities/' . $job->facility->facility_logo) : '';
                $j_data['facility_email'] = isset($job->facility->facility_email) ? $job->facility->facility_email : '';
                $j_data['facility_phone'] = isset($job->facility->facility_phone) ? $job->facility->facility_phone : '';
                $j_data['specialty_need'] = isset($job->facility->specialty_need) ? $job->facility->specialty_need : '';
                $j_data['cno_message'] = isset($job->facility->cno_message) ? $job->facility->cno_message : '';

                $j_data['cno_image'] = isset($job->facility->cno_image) ? url('public/images/facilities/cno_image' . $job->facility->cno_image) : '';

                $j_data['about_facility'] = isset($job->facility->about_facility) ? $job->facility->about_facility : '';
                $j_data['facility_website'] = isset($job->facility->facility_website) ? $job->facility->facility_website : '';

                $j_data['f_emr'] = isset($job->facility->f_emr) ? $job->facility->f_emr : '';
                $j_data['f_emr_other'] = isset($job->facility->f_emr_other) ? $job->facility->f_emr_other : '';
                $j_data['f_bcheck_provider'] = isset($job->facility->f_bcheck_provider) ? $job->facility->f_bcheck_provider : '';
                $j_data['f_bcheck_provider_other'] = isset($job->facility->f_bcheck_provider_other) ? $job->facility->f_bcheck_provider_other : '';
                $j_data['nurse_cred_soft'] = isset($job->facility->nurse_cred_soft) ? $job->facility->nurse_cred_soft : '';
                $j_data['nurse_cred_soft_other'] = isset($job->facility->nurse_cred_soft_other) ? $job->facility->nurse_cred_soft_other : '';
                $j_data['nurse_scheduling_sys'] = isset($job->facility->nurse_scheduling_sys) ? $job->facility->nurse_scheduling_sys : '';
                $j_data['nurse_scheduling_sys_other'] = isset($job->facility->nurse_scheduling_sys_other) ? $job->facility->nurse_scheduling_sys_other : '';
                $j_data['time_attend_sys'] = isset($job->facility->time_attend_sys) ? $job->facility->time_attend_sys : '';
                $j_data['time_attend_sys_other'] = isset($job->facility->time_attend_sys_other) ? $job->facility->time_attend_sys_other : '';
                $j_data['licensed_beds'] = isset($job->facility->licensed_beds) ? $job->facility->licensed_beds : '';
                $j_data['trauma_designation'] = isset($job->facility->trauma_designation) ? $job->facility->trauma_designation : '';
                $j_data['contract_termination_policy'] = isset($job->facility->contract_termination_policy) ? $job->facility->contract_termination_policy : '';
                $j_data['clinical_setting_you_prefer'] = isset($job->facility->clinical_setting_you_prefer) ? $job->facility->clinical_setting_you_prefer : '';
                $j_data['Shift'] = isset($job->facility->worker_shift_time_of_day) ? $job->facility->worker_shift_time_of_day : '';
                $j_data['worker_hours_shift'] = isset($job->facility->worker_hours_shift) ? $job->facility->worker_hours_shift : '';
                $j_data['worker_shifts_week'] = isset($job->facility->worker_shifts_week) ? $job->facility->worker_shifts_week : '';
                $j_data['facility_shift_cancelation_policy'] = isset($job->facility->facility_shift_cancelation_policy) ? $job->facility->facility_shift_cancelation_policy : '';

                /* total applied */
                $total_follow_count = Follows::where(['job_id' => $job->job_id, 'applied_status' => '1', 'status' => '1'])
                    ->distinct('user_id')
                    ->count();
                $j_data['total_applied'] = strval($total_follow_count);
                /* total applied */

                /* liked */
                $is_applied = '0';
                if ($user_id != '') {
                    // $is_applied = Follows::where(['job_id' => $job->job_id, "applied_status" => "1", 'status' => "1", "user_id" => $user_id])->count();
                    $is_applied = Follows::where(['job_id' => $job->job_id, 'applied_status' => '1', 'status' => '1', 'user_id' => $user_id])
                        ->distinct('user_id')
                        ->count();
                }
                /* liked */
                $j_data['is_applied'] = strval($is_applied);

                /* liked */
                $is_liked = '0';
                if ($user_id != '') {
                    $is_liked = Follows::where(['job_id' => $job->job_id, 'like_status' => '1', 'status' => '1', 'user_id' => $user_id])->count();
                }

                /* liked */
                $j_data['is_liked'] = strval($is_liked);

                // $j_data["shift"] = "Days";
                $j_data['start_date'] = date('d F Y', strtotime($job->start_date));

                $j_data['applied_nurses'] = '0';
                $applied_nurses = Offer::where(['job_id' => $job->job_id, 'status' => 'Apply'])->count();
                $j_data['applied_nurses'] = strval($applied_nurses);

                $is_saved = '0';
                if ($user_id != '') {
                    $nurse_info = NURSE::where('user_id', $user_id);
                    if ($nurse_info->count() > 0) {
                        $nurse = $nurse_info->first();
                        $whereCond = [
                            'job_saved.nurse_id' => $user_id,
                            'job_saved.job_id' => $job->job_id,
                        ];
                        $limit = 10;
                        $saveret = \DB::table('job_saved')->join('jobs', 'jobs.id', '=', 'job_saved.job_id')->where($whereCond);

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
                $j_data['is_saved'] = $is_saved;
                $result[] = $j_data;
            }
        }
        return $result;
    }

    public function timeAgo($time = null)
    {
        // Calculate difference between current
        // time and given timestamp in seconds
        $diff = time() - $time;
        // Time difference in seconds
        $sec = $diff;
        // Convert time difference in minutes
        $min = round($diff / 60);
        // Convert time difference in hours
        $hrs = round($diff / 3600);
        // Convert time difference in days
        $days = round($diff / 86400);
        // Convert time difference in weeks
        $weeks = round($diff / 604800);
        // Convert time difference in months
        $mnths = round($diff / 2600640);
        // Convert time difference in years
        $yrs = round($diff / 31207680);
        // Check for seconds
        if ($sec <= 60) {
            $string = "$sec seconds ago";
        }
        // Check for minutes
        elseif ($min <= 60) {
            if ($min == 1) {
                $string = 'one minute ago';
            } else {
                $string = "$min minutes ago";
            }
        }
        // Check for hours
        elseif ($hrs <= 24) {
            if ($hrs == 1) {
                $string = 'an hour ago';
            } else {
                $string = "$hrs hours ago";
            }
        }
        // Check for days
        elseif ($days <= 7) {
            if ($days == 1) {
                $string = 'Yesterday';
            } else {
                $string = "$days days ago";
            }
        }
        // Check for weeks
        elseif ($weeks <= 4.3) {
            if ($weeks == 1) {
                $string = 'a week ago';
            } else {
                $string = "$weeks weeks ago";
            }
        }
        // Check for months
        elseif ($mnths <= 12) {
            if ($mnths == 1) {
                $string = 'a month ago';
            } else {
                $string = "$mnths months ago";
            }
        }
        // Check for years
        else {
            if ($yrs == 1) {
                $string = 'one year ago';
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
                'jid' => 'required',
            ]);
            $user = auth()->guard('frontend')->user();
            $rec = JobSaved::where(['nurse_id' => $user->id, 'job_id' => $request->jid, 'is_delete' => '0'])->first();
            $input = [
                'job_id' => $request->jid,
                'is_save' => '1',
                'nurse_id' => $user->id,
            ];
            if (empty($rec)) {
                JobSaved::create($input);
                $img = asset('public/frontend/img/bookmark.png');
                $message = 'Job saved successfully.';
            } else {
                if ($rec->is_save == '1') {
                    $input['is_save'] = '0';
                    $img = asset('public/frontend/img/job-icon-bx-Vector.png');
                    $message = 'Job unsaved successfully.';
                } else {
                    $input['is_save'] = '1';
                    $img = asset('public/frontend/img/bookmark.png');
                    $message = 'Job saved successfully.';
                }
                $rec->update($input);
            }

            return new JsonResponse(['success' => true, 'msg' => $message, 'img' => $img], 200);
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
            'jid' => 'required',
        ]);
        $response = [];
        $user = auth()->guard('frontend')->user();
        $job = Job::findOrFail($request->jid);
        //return response()->json(['data'=>$job], 200);
        $rec = Offer::where(['worker_user_id' => $user->nurse->id, 'job_id' => $request->jid])
            ->whereNull('deleted_at')
            ->first();
        $input = [
            'job_id' => $request->jid,
            'created_by' => $job->created_by,
            'worker_user_id' => $user->nurse->id,
            'job_name' => $request->job_name,
            'job_name' => $job->job_name,
            'type' => $job->job_type,
            'terms' => $job->terms,
            'proffesion' => $job->proffesion,
            'block_scheduling' => $job->block_scheduling,
            'float_requirement' => $job->float_requirement,
            'facility_shift_cancelation_policy' => $job->facility_shift_cancelation_policy,
            'contract_termination_policy' => $job->contract_termination_policy,
            'traveler_distance_from_facility' => $job->traveler_distance_from_facility,
            'clinical_setting' => $job->clinical_setting,
            'Patient_ratio' => $job->Patient_ratio,
            'Emr' => $job->Emr,
            'Unit' => $job->Unit,
            'scrub_color' => $job->scrub_color,
            'start_date' => $job->start_date,
            'rto' => $job->rto,
            'hours_per_week' => $job->hours_per_week,
            'guaranteed_hours' => $job->guaranteed_hours,
            'hours_shift' => $job->hours_shift,
            'weeks_shift' => $job->weeks_shift,
            'preferred_assignment_duration' => $job->preferred_assignment_duration,
            'referral_bonus' => $job->referral_bonus,
            'sign_on_bonus' => $job->sign_on_bonus,
            'completion_bonus' => $job->completion_bonus,
            'extension_bonus' => $job->extension_bonus,
            'other_bonus' => $job->other_bonus,
            'four_zero_one_k' => $job->four_zero_one_k,
            'health_insaurance' => $job->health_insaurance,
            'dental' => $job->dental,
            'vision' => $job->vision,
            'actual_hourly_rate' => $job->actual_hourly_rate,
            'overtime' => $job->overtime,
            'holiday' => $job->holiday,
            'on_call' => $job->on_call,
            'orientation_rate' => $job->orientation_rate,
            'weekly_non_taxable_amount' => $job->weekly_non_taxable_amount,
            'description' => $job->description,
            'hours_shift' => $job->hours_shift,
            'weekly_non_taxable_amount' => $job->weekly_non_taxable_amount,
            'weekly_taxable_amount' => $job->weekly_taxable_amount,
            'employer_weekly_amount' => $job->employer_weekly_amount,
            'total_employer_amount' => $job->total_employer_amount,
            'weekly_pay' => $job->weekly_pay,
            'tax_status' => $job->tax_status,
            'status' => 'Apply',
            'recruiter_id' => $job->created_by,
        ];
        if (empty($rec)) {
            offer::create($input);
            $message = 'Job saved successfully.';
            $saved = JobSaved::where(['nurse_id' => $user->id, 'job_id' => $request->jid, 'is_delete' => '0', 'is_save' => '1'])->first();
            if (empty($rec)) {
                // $saved->delete();
            }
        } else {
            // if ($rec->is_save == '1') {
            //     $message = 'Job unsaved successfully.';
            // }else{
            //     $message = 'Job saved successfully.';
            // }
            $rec->update($input);
        }

        return new JsonResponse(['success' => true, 'msg' => 'Applied to job successfully'], 200);
    }

    public function my_work_journey()
    {
        $user = auth()->guard('frontend')->user();
        $whereCond = [
            'jobs.is_open' => '1',
            'jobs.is_closed' => '0',
            // 'job_saved.is_delete'=>'0',
            // 'job_saved.nurse_id'=>$user->id,
        ];

        $data = [];

        switch (request()->route()->getName()) {
            case 'my-work-journey':
                $whereCond['jobs.is_hidden'] = '0';
                $jobs = Job::select('jobs.*')
                    ->join('job_saved', function ($join) use ($user) {
                        $join->on('job_saved.job_id', '=', 'jobs.id')->where(function ($query) use ($user) {
                            $query
                                ->where('job_saved.is_delete', '=', '0')
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
                $jobs = Job::select('jobs.*')
                    ->join('offers', function ($join) use ($user) {
                        $join->on('offers.job_id', '=', 'jobs.id')->where(function ($query) use ($user) {
                            $query
                                ->whereIn('offers.status', ['Apply', 'Screening', 'Submitted'])
                                ->where('offers.active', '=', '1')
                                ->where('offers.nurse_id', '=', $user->nurse->id)
                                ->where(function ($q) {
                                    $q->whereNull('offers.expiration')->orWhere('offers.expiration', '>', date('Y-m-d'));
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
                $jobs = Job::select('jobs.*')
                    ->join('offers', function ($join) use ($user) {
                        $join->on('offers.job_id', '=', 'jobs.id')->where(function ($query) use ($user) {
                            $query
                                ->whereIn('offers.status', ['Onboarding', 'Working'])
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
                $jobs = Job::select('jobs.*')
                    ->join('offers', function ($join) use ($user) {
                        $join->on('offers.job_id', '=', 'jobs.id')->where(function ($query) use ($user) {
                            $query
                                ->whereIn('offers.status', ['Offered', 'Hold'])
                                ->where('offers.active', '=', '1')
                                ->where('offers.nurse_id', '=', $user->nurse->id)
                                ->where(function ($q) {
                                    $q->whereNull('offers.expiration')->orWhere('offers.expiration', '>', date('Y-m-d'));
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
                $jobs = Job::select('jobs.*')
                    ->join('offers', function ($join) use ($user) {
                        $join->on('offers.job_id', '=', 'jobs.id')->where(function ($query) use ($user) {
                            $query
                                ->where('offers.status', '=', 'Done')
                                ->where('offers.active', '=', '1')
                                ->where('offers.nurse_id', '=', $user->nurse->id)
                                ->where(function ($q) {
                                    $q->whereNull('offers.expiration')->orWhere('offers.expiration', '>', date('Y-m-d'));
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
        
        try {
            if ($request->ajax()) {
                $request->validate([
                    'jid' => 'required',
                    'type' => 'required',
                ]);
                $response = [];
                $response['success'] = true;
                $data = [];
                $job = Job::findOrFail($request->jid);
                $recruiter_id = $job->created_by;
                $recruiter = User::findOrFail($recruiter_id);
                $data['recruiter'] = $recruiter;

                $id = Auth::guard('frontend')->user()->id;
                $worker_id = Nurse::where('user_id', $id)->first();
                $offer_id = Offer::where('worker_user_id', $worker_id->id)
                    ->where('job_id', $job->id)
                    ->first();
                $data['worker_id'] = $worker_id->id;
                $data['model'] = $job;
                $data['offer_id'] = $offer_id->id;

                switch ($request->type) {
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
                        try {
                            $distinctFilters = Keyword::distinct()->pluck('filter');
                            $keywords = [];

                            foreach ($distinctFilters as $filter) {
                                $keyword = Keyword::where('filter', $filter)->get();
                                $keywords[$filter] = $keyword;
                            }

                            $data['keywords'] = $keywords;
                            $data['countries'] = Countries::where('flag', '1')->orderByRaw("CASE WHEN iso3 = 'USA' THEN 1 WHEN iso3 = 'CAN' THEN 2 ELSE 3 END")->orderBy('name', 'ASC')->get();
                            $data['usa'] = $usa = Countries::where(['iso3' => 'USA'])->first();
                            $data['us_states'] = States::where('country_id', $usa->id)->get();
                            $data['us_cities'] = Cities::where('country_id', $usa->id)->get();
                            $view = 'counter_offer';
                        } catch (\Exception $e) {
                            return response()->json(['success' => false, 'message' => 'here']);
                        }

                        break;
                    default:
                        return new JsonResponse(['success' => false, 'msg' => 'Oops! something went wrong.'], 400);
                        break;
                }

                $response['content'] = view('ajax.' . $view . '_job', $data)->render();
                return new JsonResponse($response, 200);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store_counter_offer(Request $request)
    {
        
        $user = auth()->guard('frontend')->user();
        $nurse = Nurse::where('user_id', $user->id)->first();
        $job_data = Job::where('id', $request->jobid)->first();
        $update_array['job_name'] = $job_data->job_name != $request->job_name ? $request->job_name : $job_data->job_name;
        $update_array['type'] = $job_data->type != $request->type ? $request->type : $job_data->type;
        $update_array['terms'] = $job_data->terms != $request->terms ? $request->terms : $job_data->terms;
        $update_array['profession'] = $job_data->profession != $request->profession ? $request->profession : $job_data->profession;
        $update_array['block_scheduling'] = $job_data->block_scheduling != $request->block_scheduling ? $request->block_scheduling : $job_data->block_scheduling;
        $update_array['float_requirement'] = $job_data->float_requirement != $request->float_requirement ? $request->float_requirement : $job_data->float_requirement;
        $update_array['facility_shift_cancelation_policy'] = $job_data->facility_shift_cancelation_policy != $request->facility_shift_cancelation_policy ? $request->facility_shift_cancelation_policy : $job_data->facility_shift_cancelation_policy;
        $update_array['contract_termination_policy'] = $job_data->contract_termination_policy != $request->contract_termination_policy ? $request->contract_termination_policy : $job_data->contract_termination_policy;
        $update_array['traveler_distance_from_facility'] = $job_data->traveler_distance_from_facility != $request->traveler_distance_from_facility ? $request->traveler_distance_from_facility : $job_data->traveler_distance_from_facility;
        $update_array['job_id'] = $request->jobid;
        $update_array['recruiter_id'] = $job_data->recruiter_id != $request->recruiter_id ? $request->recruiter_id : $job_data->recruiter_id;

        $update_array['worker_user_id'] = $nurse->id;
        $update_array['clinical_setting'] = $job_data->clinical_setting != $request->clinical_setting ? $request->clinical_setting : $job_data->clinical_setting;
        $update_array['Patient_ratio'] = $job_data->Patient_ratio != $request->Patient_ratio ? $request->Patient_ratio : $job_data->Patient_ratio;
        $update_array['emr'] = $job_data->emr != $request->emr ? $request->emr : $job_data->emr;
        $update_array['Unit'] = $job_data->Unit != $request->Unit ? $request->Unit : $job_data->Unit;
        $update_array['scrub_color'] = $job_data->scrub_color != $request->scrub_color ? $request->scrub_color : $job_data->scrub_color;
        $update_array['start_date'] = $job_data->start_date != $request->start_date ? $request->start_date : $job_data->start_date;
        $update_array['as_soon_as'] = $job_data->as_soon_as != $request->as_soon_as ? $request->as_soon_as : $job_data->as_soon_as;
        $update_array['rto'] = $job_data->rto != $request->rto ? $request->rto : $job_data->rto;
        $update_array['hours_per_week'] = $job_data->hours_per_week != $request->hours_per_week ? $request->hours_per_week : $job_data->hours_per_week;
        $update_array['guaranteed_hours'] = $job_data->guaranteed_hours != $request->guaranteed_hours ? $request->guaranteed_hours : $job_data->guaranteed_hours;
        $update_array['hours_shift'] = $job_data->hours_shift != $request->hours_shift ? $request->hours_shift : $job_data->hours_shift;
        $update_array['weeks_shift'] = $job_data->weeks_shift != $request->weeks_shift ? $request->weeks_shift : $job_data->weeks_shift;
        $update_array['preferred_assignment_duration'] = $job_data->preferred_assignment_duration != $request->preferred_assignment_duration ? $request->preferred_assignment_duration : $job_data->preferred_assignment_duration;
        $update_array['referral_bonus'] = $job_data->referral_bonus != $request->referral_bonus ? $request->referral_bonus : $job_data->referral_bonus;
        $update_array['sign_on_bonus'] = $job_data->sign_on_bonus != $request->sign_on_bonus ? $request->sign_on_bonus : $job_data->sign_on_bonus;
        $update_array['completion_bonus'] = $job_data->completion_bonus != $request->completion_bonus ? $request->completion_bonus : $job_data->completion_bonus;
        $update_array['extension_bonus'] = $job_data->extension_bonus != $request->extension_bonus ? $request->extension_bonus : $job_data->extension_bonus;
        $update_array['other_bonus'] = $job_data->other_bonus != $request->other_bonus ? $request->other_bonus : $job_data->other_bonus;
        $update_array['four_zero_one_k'] = $job_data->four_zero_one_k != $request->four_zero_one_k ? $request->four_zero_one_k : $job_data->four_zero_one_k;
        $update_array['health_insaurance'] = $job_data->health_insaurance != $request->health_insaurance ? $request->health_insaurance : $job_data->health_insaurance;
        $update_array['dental'] = $job_data->dental != $request->dental ? $request->dental : $job_data->dental;
        $update_array['vision'] = $job_data->vision != $request->vision ? $request->vision : $job_data->vision;
        $update_array['actual_hourly_rate'] = $job_data->actual_hourly_rate != $request->actual_hourly_rate ? $request->actual_hourly_rate : $job_data->actual_hourly_rate;
        $update_array['overtime'] = $job_data->overtime != $request->overtime ? $request->overtime : $job_data->overtime;
        $update_array['holiday'] = $job_data->holiday != $request->holiday ? $request->holiday : $job_data->holiday;
        $update_array['on_call'] = $job_data->on_call != $request->on_call ? $request->on_call : $job_data->on_call;
        $update_array['orientation_rate'] = $job_data->orientation_rate != $request->orientation_rate ? $request->orientation_rate : $job_data->orientation_rate;
        $update_array['weekly_non_taxable_amount'] = $job_data->weekly_non_taxable_amount != $request->weekly_non_taxable_amount ? $request->weekly_non_taxable_amount : $job_data->weekly_non_taxable_amount;
        $update_array['description'] = $job_data->description != $request->description ? $request->description : $job_data->description;
        $update_array['weekly_taxable_amount'] = 0;
        $update_array['employer_weekly_amount'] = 0;
        $update_array['goodwork_weekly_amount'] = 0;
        $update_array['total_employer_amount'] = 0;
        $update_array['total_goodwork_amount'] = 0;
        $update_array['total_contract_amount'] = 0;
        $update_array['weekly_pay'] = $job_data->weekly_pay;
        $update_array['is_draft'] = !empty($request->is_draft) ? $request->is_draft : '0';
        $update_array['is_counter'] = '1';
        $update_array['recruiter_id'] = $job_data->recruiter_id;
        /* create job */
        $update_array['created_by'] = $job_data->created_by;
        $update_array['status'] = 'Offered';
        $offerexist = DB::table('offers')
            ->where(['job_id' => $request->jobid, 'worker_user_id' => $nurse->id, 'recruiter_id' => $job_data->created_by])
            ->first();

          // return response()->json(['offer'=>$offerexist]);
          
          if ($offerexist) {
              $job = DB::table('offers')
                  ->where(['job_id' => $request->jobid, 'worker_user_id' => $nurse->id, 'recruiter_id' => $job_data->created_by])
                  ->first();
          
              if ($job) {
                  DB::table('offers')
                      ->where('id', $job->id)
                      ->update($update_array);
          
                 // return response()->json(['offer'=>$job]);
          
                  $offers_log = OffersLogs::create([
                      'original_offer_id' => $job->id,
                      'status' => 'Counter',
                      'employer_recruiter_id' => $job->created_by,
                      'nurse_id' => $nurse->id,
                      'details' => 'more infos',
                  ]);    
              }
          }
          else {
            $job = Offer::create($update_array);
            $offers_log = OffersLogs::create([
                'original_offer_id' => $job->id,
                'status' => 'Counter',
                'employer_recruiter_id' => $job->created_by,
                'nurse_id' => $nurse->id,
                'details' => 'more infos',
                'counter_offer_by' => 'nurse'


            ]); 
        }
        return response()->json(['success' => true, 'msg' => 'Counter offer created successfully']);
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

    public function accept_offer(Request $request)
    {
        try {
            $request->validate([
                'offer_id' => 'required',
            ]);

            $offer = Offer::where('id', $request->offer_id)->first();
            if (!$offer) {
                return response()->json(['success' => false, 'message' => 'Offer not found']);
            }

            $job = Job::where('id', $offer->job_id)->first();
            if (!$job) {
                return response()->json(['success' => false, 'message' => 'Job not found']);
            }

            $offer->update([
                'status' => 'Onboarding',
                'is_draft' => '0',
                'is_counter' => '0',
            ]);

            $job->update([
                'active' => '0',
                'is_open' => '0',
                'is_closed' => '1',
            ]);

            return response()->json(['msg' => 'offer accepted successfully ']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
