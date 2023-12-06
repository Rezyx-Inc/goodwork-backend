<?php

namespace App\Http\Controllers\Api\Job;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class JobControllerApi extends Controller
{

    public function jobList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse = NURSE::where('user_id', $request->user_id)->first();
            $jobs_id = Offer::where('nurse_id', $nurse['id'])->select('job_id')->get();

            $checkoffer = DB::table('blocked_users')->where('worker_id', $nurse['id'])->first();
            if(isset($checkoffer))
            {
                $this->check = "1";
                $this->message = "This Worker Blocked by Recruiter";
                $this->return_data = [];
            }else{
                $whereCond = [
                    'facilities.active' => true,
                    'jobs.is_open' => "1",
                    'jobs.is_hidden' => "0",
                    'jobs.is_closed' => "0",
                    'jobs.active' => '1'
                ];
                $ret = Job::select('jobs.id as job_id', 'jobs.auto_offers as auto_offers', 'jobs.*')
                    ->leftJoin('facilities', function ($join) {
                        $join->on('facilities.id', '=', 'jobs.facility_id');
                    })
                    ->where($whereCond)
                    ->whereNotIN('jobs.id', $jobs_id)
                    ->orderBy('jobs.created_at', 'desc');

                if (isset($request->profession) && $request->profession != "") {
                    $ret->where('jobs.profession', '=', $request->profession);
                }

                if (isset($request->type) && $request->type != "") {
                    $ret->where('jobs.type', '=', $request->type);
                }

                if (isset($request->preferred_specialty) && $request->preferred_specialty != "") {
                    $ret->where('jobs.preferred_specialty', '=', $request->preferred_specialty);
                }

                if (isset($request->preferred_experience) && $request->preferred_experience != "") {
                    $ret->where('jobs.preferred_experience', '=', $request->preferred_experience);
                }

                if (isset($request->search_location) && $request->search_location != "") $ret->search(['job_city', 'job_state'], $request->search_location);

                if(isset($request->job_type) && $request->job_type != ""){
                    $ret->where('jobs.job_type', '=', $request->job_type);
                }

                if (isset($request->end_date) && !empty($request->end_date)) {
                    $ret->where('jobs.end_date', '<=', $request->end_date);
                }

                if (isset($request->preferred_shift) && $request->preferred_shift != "") {
                    $ret->where('jobs.preferred_shift', '=', $request->preferred_shift);
                }

                if (isset($request->auto_offers) && $request->auto_offers != "") {
                    $ret->where('jobs.auto_offers', '=', $request->auto_offers);
                }

                $weekly_pay_from = (isset($request->weekly_pay_from) && $request->weekly_pay_from != "") ? $request->weekly_pay_from : "";
                $weekly_pay_to = (isset($request->weekly_pay_to) && $request->weekly_pay_to != "") ? $request->weekly_pay_to : "";
                if ($weekly_pay_from != "" && $weekly_pay_to != "") {
                    $ret->where(function (Builder $query) use ($weekly_pay_from,  $weekly_pay_to) {
                        $query->whereBetween('weekly_pay', array(intval($weekly_pay_from), intval($weekly_pay_to)));
                    });
                }

                $hourly_pay_from = (isset($request->hourly_pay_from) && $request->hourly_pay_from != "") ? $request->hourly_pay_from : "";
                $hourly_pay_to = (isset($request->hourly_pay_to) && $request->hourly_pay_to != "") ? $request->hourly_pay_to : "";
                if ($hourly_pay_from != "" && $hourly_pay_to != "") {
                    $ret->where(function (Builder $query) use ($hourly_pay_from,  $hourly_pay_to) {
                        $query->whereBetween('hours_shift', array(intval($hourly_pay_from), intval($hourly_pay_to)));
                    });
                }

                $hours_per_week_from = (isset($request->hours_per_week_from) && $request->hours_per_week_from != "") ? $request->hours_per_week_from : "";
                $hours_per_week_to = (isset($request->hours_per_week_to) && $request->hours_per_week_to != "") ? $request->hours_per_week_to : "";
                if ($hours_per_week_from != "" && $hours_per_week_to != "") {
                    $ret->where(function (Builder $query) use ($hours_per_week_from,  $hours_per_week_to) {
                        $query->whereBetween('hours_per_week', array(intval($hours_per_week_from), intval($hours_per_week_to)));
                    });
                }

                $assignment_from = (isset($request->assignment_from) && $request->assignment_from != "") ? $request->assignment_from : "";
                $assignment_to = (isset($request->assignment_to) && $request->assignment_to != "") ? $request->assignment_to : "";
                if ($assignment_from != "" && $assignment_to != "") {
                    $ret->where(function (Builder $query) use ($assignment_from,  $assignment_to) {
                        $query->whereBetween('preferred_assignment_duration', array(intval($assignment_from), intval($assignment_to)));
                    });
                }

                $job_data = $ret->get();
                $records = $this->jobData($job_data, $request->user_id);

                $result = [];
                foreach($records as $check_rec){
                    $is_vacancy = DB::select("SELECT COUNT(id) as hired_jobs, job_id FROM `offers` WHERE status = 'Onboarding' AND job_id = ".'"'.$check_rec['job_id'].'"');
                    if($check_rec['position_available'] > $is_vacancy[0]->hired_jobs)
                    {
                        $is_delete = DB::table('job_saved')->where(['job_id' => $check_rec['job_id'], 'nurse_id' => $request->user_id, 'is_delete' => '1'])->first();
                        if(!isset($check_rec['start_date']) && $check_rec['start_date'] == ''){
                            $check_rec['start_date'] = NULL;
                        }
                        if(empty($is_delete)){
                            $result[] = $check_rec;
                        }
                    }else{
                        if($check_rec['active'] != 0){
                            Job::where([
                                'id' => $check_rec['job_id'],
                            ])->update(['is_closed' => '1']);
                        }
                    }
                }

                $num = 0;
                $newDate = '';
                $new_result = [];
                if (isset($request->start_date) && $request->start_date != "") {
                    foreach($result as $rec)
                    {
                        if($rec['start_date_comp'] >= $request->start_date){
                            // echo $rec['start_date_comp']." = ".$request->start_date;
                            // print_r("\n");
                            $new_result[] = $rec;
                            $new_result[$num]['created_at_definition'] = $rec['created_at_browse'];
                            $new_result[$num]['description'] = strip_tags($rec['description']);
                            $new_result[$num]['responsibilities'] = strip_tags($rec['responsibilities']);
                            $new_result[$num]['qualifications'] = strip_tags($rec['qualifications']);
                            $new_result[$num]['cno_message'] = strip_tags($rec['cno_message']);
                            $new_result[$num]['about_facility'] = strip_tags($rec['about_facility']);
                            $num++;
                        }
                    }
                }else{

                    foreach($result as $rec){
                        $new_result[] = $rec;
                        $new_result[$num]['created_at_definition'] = $rec['created_at_browse'];
                        $new_result[$num]['description'] = strip_tags($rec['description']);
                        $new_result[$num]['responsibilities'] = strip_tags($rec['responsibilities']);
                        $new_result[$num]['qualifications'] = strip_tags($rec['qualifications']);
                        $new_result[$num]['cno_message'] = strip_tags($rec['cno_message']);
                        $new_result[$num]['about_facility'] = strip_tags($rec['about_facility']);
                        $num++;
                    }
                }
                // print_r($new_result);
                // die();
                $data = [];
                foreach($new_result as $val){
                    if($val['is_applied'] != '0'){
                        continue;
                    }
                    $data[] = $val;
                }

                $datas = [];
                foreach($data as $val){
                    if($val['is_saved'] == '1'){
                        continue;
                    }
                    $datas[] = $val;
                }

                $this->check = "1";
                $this->message = "Jobs listed successfully";
                $this->return_data = $datas;
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function viewJob(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'user_id' => 'required',
            'api_key' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $whereCond = [
                'facilities.active' => true,
                'jobs.is_open' => "1",
                'jobs.active' => '1',
                'jobs.id' => $request->id,
                'jobs.is_closed' => "0"
            ];

            $ret = Job::select('keywords.title as keyword_title','keywords.filter as keyword_filter','jobs.id as job_id', 'jobs.*', 'facilities.*')
                ->leftJoin('facilities', function ($join) {
                    $join->on('facilities.id', '=', 'jobs.facility_id');
                })
                ->leftJoin('keywords', function ($join) {
                    $join->on('keywords.id', '=', 'jobs.job_type');
                })
                ->where($whereCond)
                ->orderBy('jobs.created_at', 'desc');

            $user_id =  isset($request->user_id)?$request->user_id:'';
            $jobdata = $ret->get();
            // $jobdata = $ret->paginate(10);
            $result = $this->jobData($jobdata, $user_id);
            $num = '0';
            foreach($result as $rec){
                $result[$num]['description'] = strip_tags($rec['description']);
                $result[$num]['responsibilities'] = strip_tags($rec['responsibilities']);
                $result[$num]['qualifications'] = strip_tags($rec['qualifications']);
                $result[$num]['cno_message'] = strip_tags($rec['cno_message']);
                $result[$num]['about_facility'] = strip_tags($rec['about_facility']);
                $num++;
            }

            $this->check = "1";
            $this->message = "View job listed successfully";
            $this->return_data = $result;
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobApplied(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'job_id' => 'required',
            'type' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_exists = Follows::where([
                'user_id' => $request->user_id,
                'job_id' => $request->job_id,
            ]);
            if ($check_exists->count() > 0) {
                $follows = Follows::where([
                    'user_id' => $request->user_id,
                    'job_id' => $request->job_id,
                ])->update(['applied_status' => $request->type]);
            } else {
                $follows = Follows::create([
                    'user_id' => $request->user_id,
                    'job_id' => $request->job_id,
                    'applied_status' => $request->type
                ]);
            }

            $this->check = "1";
            $this->message = ($request->type == "1") ? "Applied successfully" : "Apply removed successfully";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobLikes(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'job_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            if(!empty($request->role)){
                $record = NURSE::where(['id' => $request->user_id])->get()->first();
                $user_id = $record->user_id;
            }else{
                $user_id = $request->user_id;
            }

            $check_exists = DB::table('follows')->where(['user_id' => $user_id, 'job_id' => $request->job_id,])->get()->first();

            if (!empty($check_exists->id)) {
                if($check_exists->like_status != '1'){
                    $follows = DB::table('follows')->where([
                        'user_id' => $user_id,
                        'job_id' => $request->job_id,
                    ])->update(['like_status' => '1']);

                    $this->check = "1";
                    $this->message = "Job save successfully";
                    $this->return_data = $follows;

                }else{
                    $follows = Follows::where([
                        'user_id' => $user_id,
                        'job_id' => $request->job_id,
                    ])->update(['like_status' => '0']);
                $this->check = "1";
                $this->message = "Removed job from save successfully";
                $this->return_data = $follows;
                }

            } else {
                $follows = Follows::create([
                    'user_id' => $user_id,
                    'job_id' => $request->job_id,
                    'like_status' => $request->like_status ? $request->like_status:'1'
                ]);

                $this->check = "1";
                $this->message = "Job save successfully";
                $this->return_data = $follows;
            }

        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobPopular(Request $request)
    {
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

        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
            'job_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where(['id' => $request->user_id]);
            $job_info = Job::where(['id' => $request->job_id]);
            if ($user_info->count() > 0) {
                if ($job_info->count() > 0) {
                    $whereCond = [
                        'facilities.active' => true,
                        'jobs.is_open' => "1",
                        'jobs.is_closed' => "0"
                    ];

                    // new code
                    $ret = DB::table('jobs')
                    ->leftJoin('facilities', 'facilities.id', '=', 'jobs.facility_id')
                    ->leftJoin('keywords', 'keywords.id', '=', 'jobs.job_type')
                    ->select('keywords.title as keyword_title','keywords.filter as keyword_filter','jobs.id as job_id','jobs.job_type as job_type', 'jobs.*', 'facilities.*')
                    ->where($whereCond)
                    // ->orderBy('jobs.created_at', 'desc')->get();
                    ->orderBy('jobs.created_at', 'desc');
                    $job_data = $ret->get();
                    // $job_data = $ret->paginate(10);

                    $num = 0;
                    foreach($job_data as $val){
                        $val->shift = isset($val->preferred_shift)?$val->preferred_shift:'';
                        $val->job_location = isset($workLocations[$val->job_location]) ? $workLocations[$val->job_location] : "";
                        $val->created_at_definition = isset($val->created_at) ? "Posted " . $this->timeAgo(date(strtotime($val->created_at))) : "";

                        $is_applied = "0";
                        if ($request->user_id != "")
                            $is_applied = Follows::where(['job_id' => $val->job_id, "applied_status" => "1", 'status' => "1", "user_id" => $request->user_id])->distinct('user_id')->count();
                        $val->is_applied = strval($is_applied);

                        $val->applied_nurses = 0;
                        $applied_nurses = Offer::where(['job_id' => $val->job_id, 'status'=>'Apply'])->count();
                        $val->applied_nurses = strval($applied_nurses);

                        $is_saved = '0';
                        $whereCond = [
                            'job_saved.nurse_id' => $request->user_id,
                            'job_saved.job_id' => $val->job_id,
                        ];

                        $limit = 10;
                        $saveret = \DB::table('job_saved')
                        ->join('jobs', 'jobs.id', '=', 'job_saved.job_id')
                        ->where($whereCond);

                        if ($saveret->count() > 0) {
                            $is_saved = '1';
                        }
                        $val->is_saved = $is_saved;

                        $job_data[$num]->description = strip_tags($val->description);
                        $job_data[$num]->responsibilities = strip_tags($val->responsibilities);
                        $job_data[$num]->qualifications = strip_tags($val->qualifications);
                        $job_data[$num]->cno_message = strip_tags($val->cno_message);
                        $job_data[$num]->about_facility = strip_tags($val->about_facility);
                        $num++;
                    }

                    $data = [];
                    foreach($job_data as $val){
                        if($val->is_applied != '0'){
                            continue;
                        }
                        // print_r($val->is_applied);
                        $data[] = $val;
                    }

                    $this->check = "1";
                    $this->message = "Popular Jobs listed successfully";
                    $this->return_data = $data;
                }else{
                    $this->message = 'Job not found';
                }
            }else{
                $this->message = 'User not found';
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function jobOffered(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse = Nurse::where('user_id', '=', $request->user_id)->get();
            if ($nurse->count() > 0) {
                $nurse = $nurse->first();
                $whereCond = [
                    'active' => true,
                    'status' => 'Offered'
                ];
                $offers = Offer::where($whereCond)
                    ->where('nurse_id', $nurse->id)
                    ->where('expiration', '>=', date('Y-m-d H:i:s'))
                    ->whereNotNull('job_id')
                    ->orderBy('created_at', 'desc');

                $o_data['offer'] = [];
                $limit = 10;
                $total_pages = ceil($offers->count() / $limit);
                $o_data['total_pages_available'] =  strval($total_pages);
                $o_data["current_page"] = (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : "1";
                $o_data['results_per_page'] = strval($limit);

                if ($offers->count() > 0) {
                    // $result = $offers->paginate($limit);
                    $result = $offers->get();

                    /* common */
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
                    /* common */

                    foreach ($result as $key => $off_val) {
                        $jobinfo = Job::where(['id' => $off_val->job_id])->get()->first();
                        $facility_info = Facility::where(['id' => $jobinfo->facility_id])->get()->first();

                        $days = [];
                        if (isset($jobinfo->preferred_days_of_the_week)) {
                            $day_s = explode(",", $jobinfo->preferred_days_of_the_week);
                            if (is_array($day_s) && !empty($day_s)) {
                                foreach ($day_s as $day) {
                                    if ($day == "Sunday") $days[] = "Su";
                                    elseif ($day == "Monday") $days[] = "M";
                                    elseif ($day == "Tuesday") $days[] = "T";
                                    elseif ($day == "Wednesday") $days[] = "W";
                                    elseif ($day == "Thursday") $days[] = "Th";
                                    elseif ($day == "Friday") $days[] = "F";
                                    elseif ($day == "Saturday") $days[] = "Sa";
                                }
                            }
                        }

                        // $facility_logo = "";
                        // if ($facility_info->facility_logo) {
                        //     $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $facility_info->facility_logo);
                        //     if ($t) {
                        //         $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $facility_info->facility_logo);
                        //     }
                        // }

                        $o_data['offer'][] = [
                            "offer_expiration" => date('d-m-Y h:i A', strtotime($off_val->expiration)),
                            "offer_id" => $off_val->id,
                            "job_id" => $off_val->job_id,
                            "facility_logo" => (isset($facility_info->facility_logo) && $facility_info->facility_logo != "") ? url("public/images/facilities/" . $facility_info->facility_logo) : "",
                            // "facility_logo_base" => ($facility_logo != "") ? 'data:image/jpeg;base64,' . base64_encode($facility_logo) : "",
                            "facility_name" => (isset($facility_info->name) && $facility_info->name != "") ? $facility_info->name : "",
                            "job_title" => \App\Providers\AppServiceProvider::keywordTitle($jobinfo->preferred_specialty),
                            "assignment_duration" => (isset($jobinfo->preferred_assignment_duration)) ? $jobinfo->preferred_assignment_duration : "",
                            "assignment_duration_definition" => (isset($assignmentDurations[$jobinfo->preferred_assignment_duration])) ? $assignmentDurations[$jobinfo->preferred_assignment_duration] : "",
                            "shift_definition" => "Days",
                            "working_days" => (!empty($days)) ? implode(",", $days) : "",
                            "working_days_definition" => $days,
                            "hourly_pay_rate" => isset($jobinfo->preferred_hourly_pay_rate) ? $jobinfo->preferred_hourly_pay_rate : "0",
                            "start_date" => (isset($jobinfo->start_date) && $jobinfo->start_date != "") ? $jobinfo->start_date : "",
                            "end_date" => (isset($jobinfo->end_date) && $jobinfo->end_date != "") ? $jobinfo->end_date : "",
                            "status" => "pending",
                        ];
                    }
                    $this->check = "1";
                    $this->message = "Job offers listed successfully";
                } else {
                    $this->message = "Currently no offers for you";
                }
                $this->return_data = $o_data;
            } else {
                $this->message = "Nurse not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobAcceptPost(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'offer_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_offer = Offer::where(['id' => $request->offer_id, 'status' => 'Pending', 'active' => '1'])
                ->where('expiration', '>=', date('Y-m-d H:i:s'))->get();

            if ($check_offer->count() > 0) {
                $update = Offer::where(['id' => $request->offer_id])->update(['status' => 'Active']);
                if ($update) {
                    $offer = $check_offer->first();
                    $facility_email = $offer->creator->email;

                    $nurse_info = Nurse::where(['id' => $offer->nurse_id]);
                    if ($nurse_info->count() > 0) {
                        $nurse = $nurse_info->first();
                        $user_info = User::where(['id' => $nurse->user_id]);
                        if ($user_info->count() > 0) {
                            $user = $user_info->first(); // nurse user info
                            $facility_user_info = User::where(['id' => $offer->created_by]);
                            if ($facility_user_info->count() > 0) {
                                $facility_user = $facility_user_info->first(); // facility user info
                                $data = [
                                    'to_email' => $user->email,
                                    'to_name' => $user->first_name . ' ' . $user->last_name
                                ];
                                $replace_array = [
                                    '###NURSENAME###' => $user->first_name . ' ' . $user->last_name,
                                    '###FACILITYNAME###' => $facility_user->facilities[0]->name,
                                    '###FACILITYLOCATION###' => $facility_user->facilities[0]->city . ',' . $facility_user->facilities[0]->state,
                                    '###SPECIALITY###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_specialty),
                                    '###STARTDATE###' => date('d F Y', strtotime($offer->job->start_date)),
                                    '###ASSIGNMENTDURATION###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_assignment_duration),
                                    '###SHIFTDURATION###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_shift_duration),
                                    '###PREFERREDSHIFT###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_shift),
                                ];
                                $this->basic_email($template = "accept_offer_nurse", $data, $replace_array);

                                $facility_data = [
                                    'to_email' => $facility_user->email,
                                    'to_name' => $facility_user->first_name . ' ' . $facility_user->last_name
                                ];

                                $facility_replace_array = [
                                    '###USERNAME###' => $facility_user->first_name . ' ' . $facility_user->last_name,
                                    '###NURSENAME###' => $user->first_name . ' ' . $user->last_name,
                                    '###PREFERREDSPECIALITY###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_specialty),
                                    '###NURSEPROFILELINK###' => url('browse-nurses/' . $nurse->slug),
                                    '###FACILITYNAME###' => $facility_user->facilities[0]->name,
                                    '###SPECIALITY###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_specialty),
                                    '###STARTDATE###' => date('d F Y', strtotime($offer->job->start_date)),
                                    '###ASSIGNMENTDURATION###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_assignment_duration),
                                    '###SHIFTDURATION###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_shift_duration),
                                    '###PREFERREDSHIFT###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_shift),
                                ];

                                $this->basic_email($template = "accept_offer_confirmation_facility", $facility_data, $facility_replace_array);
                            }
                        }
                    }

                    $this->check = "1";
                    $this->message = "You have accepted this job successfully";
                    $this->return_data = $offer;
                } else {
                    $this->return_data = "Failed to accept the job, Please try again later";
                }
            } else {
                $this->message = "Offer not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobRejectPost(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'offer_id' => 'required',
            'api_key' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_offer = Offer::where(['id' => $request->offer_id, 'status' => 'Pending', 'active' => '1'])
                ->where('expiration', '>=', date('Y-m-d H:i:s'));
            if ($check_offer->count() > 0) {
                $offer = $check_offer->first();
                $update = Offer::where(['id' => $request->offer_id])->update(['status' => 'Rejected']);
                if ($update) {
                    $nurse_info = Nurse::where(['id' => $offer->nurse_id]);
                    if ($nurse_info->count() > 0) {
                        $nurse = $nurse_info->first();
                        $user_info = User::where(['id' => $nurse->user_id]);
                        if ($user_info->count() > 0) {
                            $user = $user_info->first(); // nurse user info
                            $facility_user_info = User::where(['id' => $offer->created_by]);
                            if ($facility_user_info->count() > 0) {
                                $facility_user = $facility_user_info->first(); // facility user info
                                /* nurse email */
                                $data = [
                                    'to_email' => $user->email,
                                    'to_name' => $user->first_name . ' ' . $user->last_name
                                ];
                                $replace_array = [
                                    '###NURSENAME###' => $user->first_name . ' ' . $user->last_name,
                                    '###FACILITYNAME###' => $facility_user->facilities[0]->name
                                ];
                                $this->basic_email($template = "reject_offer_nurse", $data, $replace_array);
                                /* nurse email */

                                /* facility user */
                                $facility_data = [
                                    'to_email' => $facility_user->email,
                                    'to_name' => $facility_user->first_name . ' ' . $facility_user->last_name
                                ];
                                $facility_replace_array = [
                                    '###USERNAME###' => $facility_user->first_name . ' ' . $facility_user->last_name,
                                    '###NURSENAME###' => $user->first_name . ' ' . $user->last_name,
                                    '###PREFERREDSPECIALITY###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_specialty),
                                    '###FACILITYNAME###' => $facility_user->facilities[0]->name,
                                ];
                                $this->basic_email($template = "reject_offer_facility", $facility_data, $facility_replace_array);
                                /* facility user */
                            }
                        }
                    }
                    $this->check = "1";
                    $this->return_data = "You have rejected this job successfully";
                    $this->return_data = $offer;
                } else {
                    $this->return_data = "Failed to reject the job, Please try again later";
                }
            } else {
                $this->message = "Offer not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function notification(Request $request)
    {
        if(isset($request->role) && !empty($request->role) && !empty($request->user_id))
        {
            $nurse_info = NURSE::where('user_id', $request->user_id);
            // for worker or nurse
            if ($nurse_info->count() > 0) {
                $nurse = $nurse_info->first();
                $whereCond = [
                    'notifications.created_by' => $request->user_id,
                    'active' => true,
                    'offers.nurse_id' => $nurse->id
                ];
                $result = [];
                $this->return_data = [];
                $ask_worker_notification = '';
                // $ret = Notification::select('offers.status as status', 'notifications.*')
                //                         ->join('offers', 'notifications.job_id', '=', 'offers.job_id')
                //                         ->where($whereCond)
                //                         ->whereNotNull('offers.job_id')
                //                         ->where('offers.is_view', false)
                //                         ->where('offers.expiration', '>=', date('Y-m-d H:i:s'))
                //                         ->orderBy('notifications.created_at', 'desc')->distinct()
                //                         ->get();

                $ask_worker_notification = Notification::where(['notifications.created_by' => $request->user_id])
                                            ->leftJoin('jobs', 'notifications.job_id', 'jobs.id')
                                            ->leftJoin('offers', 'notifications.job_id', 'offers.job_id')
                                            ->select('notifications.*', 'jobs.recruiter_id as recruiter_id')
                                            ->orderBy('notifications.created_at', 'desc')->distinct()
                                            ->get();
                if (isset($ask_worker_notification)) {
                    // $notifications = $ret;
                    $results = [];
                    foreach ($ask_worker_notification as $notification)
                    {
                        if($notification['title'] != "Send Counter Offer"){
                            $notification->created_at = Carbon::parse($notification['created_at']);
                            $notification->time = $notification->created_at->diffForHumans();
                            $notification->date = date('M j Y', strtotime($notification['created_at']));
                            $notification->recruiter_id = isset($notification['recruiter_id'])?$notification['recruiter_id']:'';
                            $notification->worker_user_id  = isset($notification['created_by'])?$notification['created_by']:'';
                            if(isset($notification['job_id'])){
                                $job = Job::where('id', $notification['job_id'])->first();
                                $notification->job_name  = isset($job['job_name'])?$job['job_name']:'';
                            }else{
                                $notification->job_name  = '';
                            }

                            if(isset($notification['worker_user_id'])){
                                $worker = User::where('id', $notification['worker_user_id'])->first();
                                if(isset($worker)){
                                    $notification->worker_name  = $worker['first_name'].' '.$worker['last_name'];
                                }else{
                                    $notification->worker_name  = '';
                                }
                            }else{
                                $notification->worker_name = '';
                            }

                            if(isset($notification['recruiter_id'])){
                                $recruiter = User::where('id', $notification['recruiter_id'])->first();
                                if(isset($recruiter)){
                                    $notification->recruiter_name  = $recruiter['first_name'].' '.$recruiter['last_name'];
                                }else{
                                    $notification->recruiter_name  = '';
                                }

                            }else{
                                $notification->recruiter_name = '';
                            }

                            if($notification['updated_at'] >= date('Y-m-d')){
                                $notification->status = 'New';
                            }else{
                                $notification->status = 'Older';
                            }

                            if($notification['title'] == 'Send Offer'){
                                $notification->isCounter = '0';
                                $notification->isAskWorker = '0';
                                $notification->is_notification = '1';
                            }else if($notification['title'] == 'Send Counter Offer'){
                                $notification->isCounter = '1';
                                $notification->isAskWorker = '0';
                                $notification->is_notification = '1';
                            }else if($notification['isAskWorker'] == '1'){
                                $notification->isCounter = '0';
                                $notification->isAskWorker = '1';
                                $notification->is_notification = '0';
                            }else{
                                $notification->isCounter = '0';
                                $notification->isAskWorker = '0';
                                $notification->is_notification = '1';
                            }
                            $results[] = $notification;
                        }


                    }
                    $this->check = "1";
                    $this->message = "Notifications has been listed successfully";
                    // $result['notification'] = isset($notifications)?$notifications:[];
                    // $this->return_data[] = $notifications;
                    $this->return_data = $results;
                } else {
                    $this->check = "1";
                    $this->message = "Currently there are no notifications";
                    $this->return_data = [];
                }
            } else {
                $this->message = "Nurse not found";
            }
        }else{

            // For recruiter
            $user_info = User::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->first();
                $whereCond = [
                    'notifications.created_by' => $request->user_id,
                    'active' => true,
                    'offers.created_by' => $user->id
                ];
                $result = [];
                $ret = Notification::select('offers.status as status', 'notifications.*')
                                        ->join('offers', 'notifications.job_id', '=', 'offers.job_id')
                                        ->where($whereCond)
                                        ->whereNotNull('offers.job_id')
                                        ->where('offers.is_view', false)
                                        ->where('offers.expiration', '>=', date('Y-m-d H:i:s'))
                                        ->orderBy('notifications.created_at', 'desc')->distinct()
                                        ->get();
                if ($ret->count() > 0) {
                    $n = [];
                    $notifications = $ret;

                    foreach ($notifications as $notification) {
                        $notification->created_at = Carbon::parse($notification->created_at);
                        $notification->date = $notification->created_at->diffForHumans();
                    }
                    $this->check = "1";
                    $this->message = "Notifications has been listed successfully";
                    $result['ask_worker'] = [];
                    $result['notification'] = $notifications;
                    $this->return_data = $result;
                } else {
                    $this->check = "1";
                    $this->message = "Currently there are no notifications";
                }
            } else {
                $this->message = "Recruiter not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

     // Send Offer and Counter Offer Notification
     public function offerNotification(Request $request)
     {
         if(isset($request->worker_user_id))
         {
             $validator = \Validator::make($request->all(), [
                 'api_key' => 'required',
                 'worker_user_id' => 'required'
             ]);
             if ($validator->fails()) {
                 $this->message = $validator->errors()->first();
             } else {
                 $nurse_info = NURSE::where('user_id', $request->worker_user_id);
                 // for worker or nurse
                 if ($nurse_info->count() > 0) {
                     $nurse = $nurse_info->first();
                     $whereCond = [
                         'notifications.created_by' => $request->worker_user_id
                     ];
                     $ret = Notification::select('offers.status as status', 'notifications.*')
                                             ->join('offers', 'notifications.job_id', '=', 'offers.job_id')
                                             ->where($whereCond)
                                             ->whereNotNull('offers.job_id')
                                             ->where('offers.is_view', false)
                                             ->where('offers.expiration', '>=', date('Y-m-d H:i:s'))
                                             ->orderBy('notifications.created_at', 'desc')->distinct()
                                             ->get();

                     if ($ret->count() > 0) {
                         $n = [];
                         $notifications = $ret;
                         foreach ($notifications as $notification) {
                             $notification->created_at = Carbon::parse($notification['created_at']);
                             $notification->date = $notification->created_at->diffForHumans();
                             $notification->recruiter_id = isset($notification['recruiter_id'])?$notification['recruiter_id']:'';
                             $notification->worker_user_id  = isset($notification['created_by'])?$notification['created_by']:'';
                             if(isset($notification['job_id'])){
                                 $job = Job::where('id', $notification['job_id'])->first();
                                 $notification->job_name  = isset($job['job_name'])?$job['job_name']:'';
                             }else{
                                 $notification->job_name  = '';
                             }
                             if(isset($notification['worker_user_id'])){
                                 $worker = User::where('id', $notification['worker_user_id'])->first();
                                 if(isset($worker)){
                                     $notification->worker_name  = $worker['first_name'].' '.$worker['last_name'];
                                 }else{
                                     $notification->worker_name  = '';
                                 }
                             }else{
                                 $notification->worker_name = '';
                             }
                             if(isset($notification['recruiter_id'])){
                                 $recruiter = User::where('id', $notification['recruiter_id'])->first();
                                 if(isset($recruiter)){
                                     $notification->recruiter_name  = $recruiter['first_name'].' '.$recruiter['last_name'];
                                 }else{
                                     $notification->recruiter_name  = '';
                                 }

                             }else{
                                 $notification->recruiter_name = '';
                             }
                             if($notification['updated_at'] >= date('Y-m-d')){
                                 $notification->status = 'New';
                             }else{
                                 $notification->status = 'Older';
                             }
                             if($notification['title'] == 'Send Offer'){
                                 $notification->isCounter = '0';
                             }else{
                                 $notification->isCounter = '1';
                             }

                         }
                         $this->check = "1";
                         $this->message = "Notifications has been listed successfully";
                         $this->return_data = $notifications;
                     } else {
                         $this->check = "1";
                         $this->message = "Currently there are no notifications";
                         $this->return_data = [];
                     }
                 } else {
                     $this->message = "Nurse not found";
                 }
             }

         }else{
             $validator = \Validator::make($request->all(), [
                 'api_key' => 'required',
                 'recruiter_id' => 'required'
             ]);
             if ($validator->fails()) {
                 $this->message = $validator->errors()->first();
             } else {
                 // For recruiter
                 $user_info = User::where('id', $request->recruiter_id);
                 if ($user_info->count() > 0) {
                     $user = $user_info->first();
                     $whereCond = [
                         'notifications.recruiter_id' => $request->recruiter_id
                     ];

                     $ret = Notification::select('offers.status as status', 'offers.nurse_id as nurse_id', 'notifications.*', 'nurses.id as worker_id')
                                             ->leftJoin('offers', 'notifications.job_id', 'offers.job_id')
                                             ->leftJoin('nurses', 'notifications.created_by', 'nurses.user_id')
                                             ->where($whereCond)
                                             ->whereNotNull('offers.job_id')
                                             ->where('offers.is_view', false)
                                             // ->where('offers.nurse_id', '=', 'nurses.id')
                                             ->where('offers.expiration', '>=', date('Y-m-d H:i:s'))
                                             ->orderBy('notifications.created_at', 'desc')
                                             ->get();
                     if ($ret->count() > 0) {
                         $n = [];
                         $notifications = $ret;
                         $result = [];
                         foreach ($notifications as $notification)
                         {
                             if($notification['worker_id'] == $notification['nurse_id']){
                                 $notification->created_at = Carbon::parse($notification['created_at']);
                                 $notification->date = $notification->created_at->diffForHumans();
                                 $notification->recruiter_id = isset($notification['recruiter_id'])?$notification['recruiter_id']:'';
                                 $notification->worker_user_id  = isset($notification['created_by'])?$notification['created_by']:'';

                                 if(isset($notification['job_id'])){
                                     $job = Job::where('id', $notification['job_id'])->first();
                                     $notification->job_name  = $job['job_name'];
                                 }else{
                                     $notification->job_name  = '';
                                 }
                                 if(isset($notification['worker_user_id'])){
                                     $worker = User::where('id', $notification['worker_user_id'])->first();
                                     if(isset($worker['first_name'])){
                                         $worker_name = $worker['first_name'].' '.$worker['last_name'];
                                     }else{
                                         $worker_name = '';
                                     }
                                     $nurse = Nurse::where('user_id', $notification['worker_user_id'])->first();
                                     if(isset($nurse['id'])){
                                         $worker_id = $nurse['id'];
                                     }else{
                                         $worker_id = '';
                                     }
                                     $notification->worker_name  = $worker_name;
                                     $notification->worker_id  = $worker_id;
                                 }else{
                                     $notification->worker_name = '';
                                     $notification->worker_id  = '';
                                 }

                                 if(isset($notification['recruiter_id'])){
                                     $recruiter = User::where('id', $notification['recruiter_id'])->first();
                                     if(isset($recruiter)){
                                         $notification->recruiter_name  = $recruiter['first_name'].' '.$recruiter['last_name'];
                                     }else{
                                         $notification->recruiter_name  = '';
                                     }
                                 }else{
                                     $notification->recruiter_name = '';
                                 }
                                 if($notification['updated_at'] >= date('Y-m-d')){
                                     $notification->status = 'New';
                                 }else{
                                     $notification->status = 'Older';
                                 }
                                 if($notification['title'] == 'Send Offer'){
                                     $notification->isCounter = '0';
                                 }else{
                                     $notification->isCounter = '1';
                                 }
                                 $result[] = $notification;
                             }

                         }
                         $this->check = "1";
                         $this->message = "Notifications has been listed successfully";
                         $this->return_data = $result;
                     } else {
                         $this->check = "1";
                         $this->message = "Currently there are no notifications";
                     }
                 } else {
                     $this->message = "Recruiter not found";
                 }
             }

         }

         return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
     }

     public function removeNotification(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            "notification_id" => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            if(isset($request->role) && !empty($request->user_id)){
                $nurse_info = NURSE::where('id', $request->user_id)->get();
            }else{
                $nurse_info = NURSE::where('user_id', $request->user_id)->get();
            }

            if ($nurse_info->count() > 0) {
                $nurse = $nurse_info->first();

                $whereCond = ['active' => true, 'id' => $request->notification_id];
                $ret = Offer::where($whereCond)
                    ->where('nurse_id', $nurse->id)
                    ->whereNotNull('job_id')
                    ->where('is_view', false)
                    // ->where('expiration', '>=', date('Y-m-d H:i:s'))
                    ->orderBy('created_at', 'desc')->get();
                if ($ret->count() > 0) {
                    $notification = $ret->first();

                    $update_array['is_view'] = "1";
                    $update_array['is_view_date'] = date('Y-m-d H:i:s');
                    $update = Offer::where(['id' => $notification->id])->update($update_array);
                    if ($update == true) {
                        $this->check = "1";
                        $this->message = "Notification cleared successfully";
                        // $this->return_data = $notification;
                    } else {
                        $this->message = "Failed to clear notification, Please try again later";
                    }
                } else {
                    $this->message = "Notification already viewed/cleared";
                }
            } else {
                $this->message = "Nurse not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
    public function jobCompleted(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            /*  dropdown data's */
            $controller = new Controller();
            $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
            $specialties = $controller->getSpecialities()->pluck('title', 'id');
            /*  dropdown data's */
            $nurse_info = Nurse::where(['user_id' => $request->user_id]);
            if ($nurse_info->count() > 0) {
                $nurse = $nurse_info->first();

                $limit = 25;
                $ret = Offer::where(['active' => "1", 'nurse_id' => $nurse->id])
                    ->orderBy('created_at', 'desc');
                // ->skip(0)->take($limit);
                // $offer_info = $ret->paginate($limit);
                $offer_info = $ret->get();

                $tot_res = 0;
                $my_jobs['data'] = [];
                if ($offer_info->count() > 0) {
                    foreach ($offer_info as $key => $off) {
                        if ($off->job->end_date < date('Y-m-d')) {
                            $o['offer_id'] = $off->id;

                            /* facility info */
                            $o['facility_id'] = $o['facility_logo'] = $o['facility_name'] = "";
                            $facility_info = User::where(['id' => $off->created_by]);
                            if ($facility_info->count() > 0) {
                                $facility = $facility_info->first();
                                $o['facility_id'] = (isset($facility->facilities[0]->id) && $facility->facilities[0]->id != "") ? $facility->facilities[0]->id : "";

                                $o['facility_logo'] = (isset($facility->facilities[0]->facility_logo)) ? url('public/images/facilities/' . $facility->facilities[0]->facility_logo) : "";


                                // $facility_logo = "";
                                // if (isset($facility->facilities[0]->facility_logo)) {
                                //     $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $facility->facilities[0]->facility_logo);
                                //     if ($t) {
                                //         $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $facility->facilities[0]->facility_logo);
                                //     }
                                // }
                                // $o["facility_logo_base"] = ($facility_logo != "") ? 'data:image/jpeg;base64,' . base64_encode($facility_logo) : "";

                                $o['facility_name'] = (isset($facility->facilities[0]->name) && $facility->facilities[0]->name != "") ? $facility->facilities[0]->name : "";
                            }
                            /* facility info */

                            $o['title'] = (isset($specialties[$off->job->preferred_specialty]) && $specialties[$off->job->preferred_specialty] != "") ? $specialties[$off->job->preferred_specialty] : "";
                            $o['work_duration'] = (isset($off->job->preferred_shift_duration) && $off->job->preferred_shift_duration != "") ? strval($off->job->preferred_shift_duration) : "";
                            $o['work_duration_definition'] = (isset($off->job->preferred_shift_duration) && $off->job->preferred_shift_duration != "") ? \App\Providers\AppServiceProvider::keywordTitle($off->job->preferred_shift_duration) : "";
                            $o['shift'] = (isset($off->job->preferred_shift) && $off->job->preferred_shift != "") ? strval($off->job->preferred_shift) : "";
                            $o['shift_definition'] = (isset($off->job->preferred_shift) && $off->job->preferred_shift != "") ? \App\Providers\AppServiceProvider::keywordTitle($off->job->preferred_shift) : "";
                            $o['work_days'] = (isset($off->job->preferred_days_of_the_week) && $off->job->preferred_days_of_the_week != "") ? $off->job->preferred_days_of_the_week : "";
                            $days = [];
                            if (isset($off->job->preferred_days_of_the_week)) {
                                $day_s = explode(",", $off->job->preferred_days_of_the_week);
                                if (is_array($day_s) && !empty($day_s)) {
                                    foreach ($day_s as $day) {
                                        if ($day == "Sunday") $days[] = "Su";
                                        elseif ($day == "Monday") $days[] = "M";
                                        elseif ($day == "Tuesday") $days[] = "T";
                                        elseif ($day == "Wednesday") $days[] = "W";
                                        elseif ($day == "Thursday") $days[] = "Th";
                                        elseif ($day == "Friday") $days[] = "F";
                                        elseif ($day == "Saturday") $days[] = "Sa";
                                    }
                                }
                            }
                            $o['work_days_array'] = ($o['work_days'] != "") ? $days : [];
                            $o['work_days_string'] = ($o['work_days'] != "") ? implode(",", $days) : "";
                            $o['hourly_rate'] = (isset($off->job->preferred_hourly_pay_rate) && $off->job->preferred_hourly_pay_rate != "") ? strval($off->job->preferred_hourly_pay_rate) : "0";
                            $o['start_date'] = date('d F Y', strtotime($off->job->start_date));
                            $o['end_date'] = date('d F Y', strtotime($off->job->end_date));

                            if ($tot_res == 0) $tot_res += 1; //initialized first page`
                            $tot_res += 1;
                            $my_jobs['data'][] = $o;
                        }
                    }
                }

                $total_pages = ceil($ret->count() / $limit);
                $my_jobs['total_pages_available'] =  strval($total_pages);
                $my_jobs["current_page"] = (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : "1";
                $my_jobs['results_per_page'] = strval($limit);

                $this->check = "1";
                $this->message = "Completed jobs listed successfully";
                $this->return_data = $my_jobs;
            } else {
                $this->message = "Nurse not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

}
