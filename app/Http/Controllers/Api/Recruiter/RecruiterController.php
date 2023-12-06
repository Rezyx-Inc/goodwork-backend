<?php

namespace App\Http\Controllers\Api\Recruiter;

// Models
use Illuminate\Http\Request;
use App\Models\Nurse;
use App\Models\Job;
use App\Models\User;
use App\Models\Keyword;
use App\Models\Facility;
use App\Models\Notification;
use App\Models\Certification;
use App\Models\NurseAsset;
use App\Models\Offer;
use App\Models\JobAsset;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use DB;
use App\Http\Controllers\Controller;
class RecruiterController extends Controller
{

    //  should import models and used classes

    public function userRecruiter(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse_info = NURSE::where('user_id', $request->user_id);
            $user_info = USER::where('id', $request->user_id);
            if($nurse_info->count() > 0){
                $nurse = $nurse_info->first();
                $nurse_id = $nurse['id'];
            }
            if ($user_info->count() > 0) {
                $whereCond = [
                    'users.id' => $request->user_id,
                ];
                // $return_data = [];
                $facilitys = [];
                $agency_name = '';
                $data = DB::table('users')
                                    ->leftJoin('facilities', 'facilities.id', '=', 'users.facility_id')
                                    ->where($whereCond)
                                    ->select('facilities.name as Agency_name', 'users.*')
                                    ->first();
                $user = User::where('users.id', $request->user_id)->first();
                $facility = isset($user['facility_id'])?json_decode($user['facility_id']):'';
                foreach($facility as $fac){
                    $facility_list = Facility::where('id', $fac)->select('name')->first();
                    $facilitys[] = $facility_list['name'];

                }
                    if(isset($nurse_id)){
                        $data['nurse_id']  = $nurse_id;
                    }
                    $return_data['about_me'] = (isset($data->about_me) && $data->about_me != "") ? strip_tags($data->about_me) : "";
                    $return_data['image'] = (isset($data->image) && $data->image != "") ? url("public/images/nurses/profile/" . $data->image) : "";
                    $return_data['qualities'] = (isset($data->qualities) && $data->qualities != "") ? json_decode($data->qualities) : [];
                    // $return_data['Agency_name'] = (isset($data->Agency_name) && $data->Agency_name != "") ? $data->Agency_name : "";
                    $return_data['Agency_name'] = (isset($facilitys) && $facilitys != "") ? $facilitys : "";
                    $return_data['first_name'] = (isset($data->first_name) && $data->first_name != "") ? $data->first_name : "";
                    $return_data['last_name'] = (isset($data->last_name) && $data->last_name != "") ? $data->last_name : "";
                    $return_data['user_name'] = (isset($data->user_name) && $data->user_name != "") ? $data->user_name : "";
                    $return_data['fcm_token'] = (isset($data->fcm_token) && $data->fcm_token != "") ? $data->fcm_token : "";
                    $return_data['email_verified_at'] = (isset($data->email_verified_at) && $data->email_verified_at != "") ? $data->email_verified_at : "";
                    $return_data['date_of_birth'] = (isset($data->date_of_birth) && $data->date_of_birth != "") ? $data->date_of_birth : "";
                    $return_data['driving_license'] = (isset($data->driving_license) && $data->driving_license != "") ? $data->driving_license : "";
                    $return_data['security_number'] = (isset($data->security_number) && $data->security_number != "") ? $data->security_number : "";
                    $return_data['mobile'] = (isset($data->mobile) && $data->mobile != "") ? $data->mobile : "";
                    $return_data['last_login_at'] = (isset($data->last_login_at) && $data->last_login_at != "") ? $data->last_login_at : "";
                    $return_data['last_login_ip'] = (isset($data->last_login_ip) && $data->last_login_ip != "") ? $data->last_login_ip : "";
                    $return_data['facility_id'] = (isset($data->facility_id) && $data->facility_id != "") ? json_decode($data->facility_id) : "";

                $this->message = "Recruiter listed succcessfully";
                $this->check = "1";
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $return_data], 200);
    }

    public function editUserRecruiter(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->first();
                $user->about_me = isset($request->about_me)?$request->about_me:$user->about_me;
                $user->qualities = isset($request->qualities)?json_encode(explode(',', $request->qualities)):$user->qualities;
                // $user->facility_id = isset($request->facility_id)?json_encode(explode(',', $request->facility_id)):$user->facility_id;
                $update = User::where('id', $request->user_id)->update(['qualities' => $user->qualities, 'about_me' => $user->about_me]);
                // $update = User::where('id', $request->user_id)->update($user);
                if(isset($update)){
                    $this->message = "Recruiter Details update succcessfully";
                    $this->check = "1";
                    $return_data = '1';
                }else{
                    $this->message = "Recruiter Details not updated";
                    $this->check = "0";
                    $return_data = '0';
                }

            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $return_data], 200);
    }

    public function recruiterProfilePictureUpload(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'profile_image' => "required",
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            $response = [];
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                if ($request->hasFile('profile_image') && $request->file('profile_image') != null) {
                    $profile_image_name_full = $request->file('profile_image')->getClientOriginalName();
                    $profile_image_name = pathinfo($profile_image_name_full, PATHINFO_FILENAME);
                    $profile_image_ext = $request->file('profile_image')->getClientOriginalExtension();
                    $profile_image = $profile_image_name.'_'.time().'.'.$profile_image_ext;

                    $destinationPath = 'images/nurses/profile';
                    $request->file('profile_image')->move(public_path($destinationPath), $profile_image);

                    $update_array['image'] = $profile_image;
                    $update = USER::where(['id' => $user->id])->update($update_array);
                    if ($update == true) {
                        $this->check = "1";
                        $this->return_data = $user;
                        $this->message = "Profile picture updated successfully";
                    } else {
                        $this->message = "Failed to update profile picture, please try again later";
                    }
                } else {
                    $this->message = "Profile image not found";
                }

            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function registerRecruiter(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|unique:users,email',
            'api_key' => 'required',
            'mobile' => 'required|unique:users,mobile'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            if (
                isset($request->api_key) && $request->api_key != "" &&
                isset($request->first_name) && $request->first_name != "" &&
                isset($request->last_name) && $request->last_name != "" &&
                isset($request->email) && $request->email != "" &&
                isset($request->mobile) && $request->mobile != "" &&
                isset($request->fcm_token) && $request->fcm_token != ""
            ) {
                $facility_id = [];
                $facility_id[] = 'GWf000001';
                $facility = json_encode($facility_id);

                $user_data = User::where('email', '=', $request->email)->first();
                if ($user_data === null) {
                    $user = User::create([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'mobile' => $request->mobile,
                        'email' => $request->email,
                        'user_name' => $request->email,
                        'facility_id' => $facility,
                        // 'password' => Hash::make($request->password),
                        'role' => Role::getKey(Role::RECRUITER),
                        'fcm_token' => $request->fcm_token
                    ]);

                    $user->assignRole('Recruiter');

                    $reg_user = User::where('email', '=', $request->email)->get()->first();

                    /* mail */
                    // $data = [
                    //     'to_email' => $reg_user->email,
                    //     'to_name' => $reg_user->first_name . ' ' . $reg_user->last_name
                    // ];
                    // $replace_array = ['###USERNAME###' => $reg_user->first_name . ' ' . $reg_user->last_name];
                    // // $this->basic_email($template = "new_registration", $data, $replace_array);


                    // $userArray = array();

                    // $userArray['id'] = $reg_user->id;
                    // $userArray['first_name'] = $reg_user->first_name;
                    // $userArray['last_name'] = $reg_user->last_name;
                    // $userArray['email'] = $reg_user->email;
                    // $userArray['user_name'] = $reg_user->user_name;
                    // $userArray['mobile'] = $reg_user->mobile;
                    // $userArray['fcm_token'] = $reg_user->fcm_token;
                    // $userArray['facilty_id'] = $reg_user->facilty_id;
                    // $return_data = $userArray;

                    $return_data = $this->recruiterData($reg_user);

                    $this->check = "1";
                    $this->message = "Your account has been registered successfully";
                    $this->return_data = $return_data;
                    // if ($_SERVER['HTTP_HOST'] != "localhost" || $_SERVER['HTTP_HOST'] != "127.0.0.1:8000") $this->sendNotifyEmail($user);
                } else {
                    $this->message = "Your account is already created please login..!";
                }
            } else {
                $this->message = $this->param_missing;
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function homeScreen(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
            if(!isset($request->user_id)){
                $result['Applied'] = 0;
                $result['Offered'] = 0;
                $result['Onboard'] = 0;
                $result['Hired'] = 0;
                $result['Done'] = 0;
                $result['Rejected'] = 0;

                $result['total_goodwork_amount'] = '';
                $result['total_employer_amount'] = '';

                $this->check = "1";
                $this->message = "Jobs listed successfully";
                $this->return_data = $result;
            }

        } else {
            $user_info = USER::where('id', $request->user_id);


            if ($user_info->count() > 0) {

                $user = $user_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.job_type' => 'Local',
                    'jobs.is_closed' => "0",
                    'recruiter_id' => $request->user_id
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*')
                    ->leftJoin('facilities', function ($join) {
                        $join->on('facilities.id', '=', 'jobs.facility_id');
                    })
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->where($whereCond);

                $result['Local_jobs'] = $ret->count();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.job_type' => 'Shift',
                    'jobs.is_closed' => "0",
                    'recruiter_id' => $request->user_id
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*')
                    ->leftJoin('facilities', function ($join) {
                        $join->on('facilities.id', '=', 'jobs.facility_id');
                    })
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->where($whereCond);

                $result['Shift_jobs'] = $ret->count();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.job_type' => 'Travel',
                    'jobs.is_closed' => "0",
                    'recruiter_id' => $request->user_id
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*')
                    ->leftJoin('facilities', function ($join) {
                        $join->on('facilities.id', '=', 'jobs.facility_id');
                    })
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->where($whereCond);

                $result['Travel_jobs'] = $ret->count();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.job_type' => 'Permanent',
                    'jobs.is_closed' => "0",
                    'recruiter_id' => $request->user_id
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*')
                    ->leftJoin('facilities', function ($join) {
                        $join->on('facilities.id', '=', 'jobs.facility_id');
                    })
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->where($whereCond);

                $result['Permanent_jobs'] = $ret->count();

                $New = DB::table('jobs')
                                ->join('offers','jobs.id', '=', 'offers.job_id')
                                ->where(['jobs.recruiter_id' => $request->user_id, 'status' => 'Apply', 'jobs.is_closed' => '0'])
                                ->select('status', DB::raw('count(*) as total'))
                                ->groupBy('offers.status')
                                ->get();

                $Offered = DB::table('jobs')
                                ->join('offers','jobs.id', '=', 'offers.job_id')
                                ->where(['jobs.recruiter_id' => $request->user_id, 'status' => 'Offered', 'jobs.is_closed' => '0'])
                                ->select('status', DB::raw('count(*) as total'))
                                ->groupBy('offers.status')
                                ->get();

                $Onboard = DB::table('jobs')
                                ->join('offers','jobs.id', '=', 'offers.job_id')
                                ->where(['jobs.recruiter_id' => $request->user_id, 'status' => 'Onboarding', 'jobs.is_closed' => '0'])
                                ->select('status', DB::raw('count(*) as total'))
                                ->groupBy('offers.status')
                                ->get();

                $Working = DB::table('jobs')
                                ->join('offers','jobs.id', '=', 'offers.job_id')
                                ->where(['jobs.recruiter_id' => $request->user_id, 'status' => 'Working', 'jobs.is_closed' => '0'])
                                ->select('status', DB::raw('count(*) as total'))
                                ->groupBy('offers.status')
                                ->get();

                $Done = DB::table('jobs')
                                ->join('offers','jobs.id', '=', 'offers.job_id')
                                ->where(['jobs.recruiter_id' => $request->user_id, 'status' => 'Done', 'jobs.is_closed' => '0'])
                                ->select('status', DB::raw('count(*) as total'))
                                ->groupBy('offers.status')
                                ->get();

                $whereCond = [
                    'notifications.created_by' => $request->user_id,
                ];
                $result['Notification'] = Notification::select('offers.status as status', 'notifications.*')
                                        ->join('offers', 'notifications.job_id', '=', 'offers.job_id')
                                        ->where($whereCond)
                                        ->orderBy('notifications.created_at', 'desc')->distinct()
                                        ->get();
                if(isset($result['Notification'])){

                    foreach($result['Notification'] as $rec){
                        if($rec->status == "Apply"){
                            $rec->status = 1;
                        }else if($rec->status == 'Offered'){
                            $rec->status = 2;
                        }else if($rec->status == 'Onboarding'){
                            $rec->status = 3;
                        }else if($rec->status == 'Working'){
                            $rec->status = 4;
                        }else{
                            $rec->status = 5;
                        }

                        $rec->created_at_definition = $rec->created_at->format('l, jS F Y');
                        $rec->updated_at_definition = isset($rec->updated_at) ? $rec->updated_at->format('l, jS F Y') : NULL;
                        $rec->deleted_at_definition = isset($rec->deleted_at) ? $rec->deleted_at->format('l, jS F Y') : NULL;

                    }
                }

                $result['New'] = isset($New[0])?$New[0]->total:0;
                $result['Offered'] = isset($Offered[0])?$Offered[0]->total:0;
                $result['Onboard'] = isset($Onboard[0])?$Onboard[0]->total:0;
                $result['Working'] = isset($Working[0])?$Working[0]->total:0;
                $result['Done'] = isset($Done[0])?$Done[0]->total:0;

                $this->check = "1";
                $this->message = "Jobs listed successfully";
                $this->return_data = $result;

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function accountInfo(Request $request){

        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('id', $request->user_id);


            if ($user_info->count() > 0) {

                $reg_user = $user_info->get()->first();
                $return_data = $this->recruiterData($reg_user);

                $this->check = "1";
                $this->message = "Account info found";
                $this->return_data = $return_data;

            }else{


                $this->check = "1";
                $this->message = "User not found";
                // $this->return_data = $result;

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);


    }

    public function updateAccInfo(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
            'email' => 'string|email|unique:users,email,'.$request->user_id,
            'mobile' => 'unique:users,mobile,'.$request->user_id,
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('id', $request->user_id);

            if ($user_info->count() > 0) {
                $reg_user = $user_info->get()->first();
                $reg_user->first_name = $request->first_name?$request->first_name:$reg_user->first_name;
                $reg_user->last_name = $request->last_name?$request->last_name:$reg_user->last_name;
                $reg_user->email = $request->email?$request->email:$reg_user->email;
                $reg_user->user_name = $request->user_name?$request->user_name:$reg_user->user_name;
                $reg_user->mobile = $request->mobile?$request->mobile:$reg_user->mobile;

                $affected = DB::table('users')
                ->where('id', $request->user_id)
                ->update(['first_name' => $reg_user->first_name, 'last_name' => $reg_user->last_name, 'email' => $reg_user->email, 'user_name' => $reg_user->user_name, 'mobile' => $reg_user->mobile]);
                $return_data = $this->recruiterData($reg_user);

                $this->check = "1";
                $this->message = "Account Update successfully";
                $this->return_data = $return_data;

            }else{
                $this->check = "1";
                $this->message = "User not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);


    }

    public function accountInfoByMobile(Request $request){

        $validator = \Validator::make($request->all(), [
            'mobile' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('mobile', $request->mobile);


            if ($user_info->count() > 0) {

                $reg_user = $user_info->get()->first();



                $return_data = $this->recruiterData($reg_user);

                $this->check = "1";
                $this->message = "Account info found";
                $this->return_data = $return_data;

            }else{


                $this->check = "1";
                $this->message = "User not found";
                // $this->return_data = $result;

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);


    }

    public function applications(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);
        if ($validator->fails()) {

            $this->message = $validator->errors()->first();
            if(!isset($request->user_id)){
                $status = ['Apply','Screening','Submitted','Offered','Draft Offer','Onboarding','Working','Done'];
                $records = array();
                foreach($status as $value)
                {

                    if($value == 'Apply'){
                        $value = 'New';
                    }
                    $records[] = ['name' => $value, 'applicants' => 0];
                }
                $this->check = "1";
                $this->message = "Data listed";
                $this->return_data = $records;
                return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
            }
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0)
            {
                $user = $user_info->get()->first();
                // $status = ['Apply','Screening','Submitted','Offered','Onboarding','Working','Done','Rejected','Blocked','Hold'];
                $status = ['Apply','Screening','Submitted','Offered','Draft Offer','Onboarding','Working','Done'];
                $return_data = array();
                $draftoffer = DB::table('offer_jobs')->where(['recruiter_id' => $request->user_id, 'is_draft' => '1'])->count();
                foreach($status as $value)
                {
                    $whereCond = [
                        'jobs.recruiter_id' => $user->id,
                        'jobs.is_closed' => "0",
                        'offers.status' => $value,
                    ];
                    $ret = Job::select('jobs.id as job_id', 'jobs.*','offers.id as offer_id')
                        ->join('offers', 'jobs.id', '=', 'offers.job_id')
                        ->where($whereCond);

                    if($value == 'Apply'){
                        $value = 'New';
                    }
                    $return_data[] = ['name' => $value, 'applicants' => $ret->count()];
                }
                $records = [];
                foreach($return_data as $rec)
                {
                    if(($rec['name'] == 'Offered') && !empty($rec['applicants'])){
                        $rec['applicants'] = $rec['applicants']-$draftoffer;
                    }else{
                        $rec['applicants'] = $rec['applicants'];
                    }
                    if($rec['name'] == 'Draft Offer'){
                        $rec['applicants'] = $draftoffer;
                    }
                    $records[] = $rec;
                }
                $this->check = "1";
                $this->message = "Data listed";
                $this->return_data = $records;
            }else{
                $this->check = "1";
                $this->message = "User not found";
                // $this->return_data = $result;
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function newApplications(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.recruiter_id' => $user->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Apply'
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*','offers.id as offer_id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'nurses.*', 'facilities.name as facility_name')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('nurses', 'offers.nurse_id', '=', 'nurses.id')
                    ->join('users', 'nurses.user_id', '=', 'users.id')
                    ->Join('facilities', function ($join) {
                            $join->on('facilities.id', '=', 'jobs.facility_id');
                        })
                    ->where($whereCond)
                    // ->orderBy('offers.created_at', 'desc')
                ->orderBy('offers.nurse_id', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $rec)
                {
                    $result['worker_id'] = $rec['id'];
                    $result['worker_user_id'] = $rec['user_id'];
                    $result['job_id'] = $rec['job_id'];
                    $result['worker_image'] = isset($rec['worker_image'])? url("public/images/nurses/profile/" . $rec['worker_image']):"";
                    $result['worker_name'] = isset($rec['first_name'])?$rec['first_name'].' '.$rec['last_name']:"";
                    $result['job_name'] = isset($rec['job_name'])?$rec['job_name']:"";
                    $result['facility_name'] = isset($rec['facility_name'])?$rec['facility_name']:"";
                    $result['preferred_assignment_duration'] = isset($rec['worker_weeks_assignment'])?$rec['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($rec['worker_shift_time_of_day'])?$rec['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($rec['highest_nursing_degree'])?$rec['highest_nursing_degree']:"";
                    $result['specialty'] = isset($rec['specialty'])?$rec['specialty']:"";
                    $result['experience'] = isset($rec['experience'])?$rec['experience'].' Years of Experience':"";
                    $result['recently'] = 'Recently Added';
                    $record[] =  $result;
                }
                $this->check = "1";
                $this->message = "Data listed successfully";
                $this->return_data = $record;

            }else{
                $this->check = "1";
                $this->message = "User not found";

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function screeningApplications(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $whereCond = [
                    'facilities.active' => true,
                    'jobs.recruiter_id' => $user->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Screening'
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'nurses.*', 'offers.id as offer_id', 'facilities.name as facility_name')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('nurses', 'offers.nurse_id', '=', 'nurses.id')
                    ->join('users', 'nurses.user_id', '=', 'users.id')
                    ->Join('facilities', function ($join) {
                            $join->on('facilities.id', '=', 'jobs.facility_id');
                        })
                    ->leftJoin('keywords', 'jobs.job_type', '=', 'keywords.id')
                    ->where($whereCond)
                    ->orderBy('offers.created_at', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $screening)
                {
                    $result['worker_id'] = $screening['id'];
                    $result['worker_user_id'] = $screening['user_id'];
                    $result['job_id'] = $screening['job_id'];
                    $result['worker_image'] = isset($screening['worker_image'])? url("public/images/nurses/profile/" . $screening['worker_image']):"";
                    $result['worker_name'] = isset($screening['first_name'])?$screening['first_name'].' '.$screening['last_name']:"";
                    $result['job_name'] = isset($screening['job_name'])?$screening['job_name']:"";
                    $result['preferred_assignment_duration'] = isset($screening['worker_weeks_assignment'])?$screening['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($screening['worker_shift_time_of_day'])?$screening['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($screening['highest_nursing_degree'])?$screening['highest_nursing_degree']:"";
                    $result['specialty'] = isset($screening['specialty'])?$screening['specialty']:"";
                    $result['experience'] = isset($screening['experience'])?$screening['experience'].' Years of Experience':"";
                    $result['recently'] = 'Recently Added';
                    $record[] =  $result;
                }

                $this->check = "1";
                $this->message = "Screening Data listed successfully";
                $this->return_data = $record;
            }else{

                $this->check = "1";
                $this->message = "User not found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function submittedApplications(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $whereCond = [
                    'facilities.active' => true,
                    'jobs.recruiter_id' => $user->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Submitted'
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'nurses.*', 'offers.id as offer_id', 'facilities.name as facility_name')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('nurses', 'offers.nurse_id', '=', 'nurses.id')
                    ->join('users', 'nurses.user_id', '=', 'users.id')
                    ->Join('facilities', function ($join) {
                            $join->on('facilities.id', '=', 'jobs.facility_id');
                        })
                    ->leftJoin('keywords', 'jobs.job_type', '=', 'keywords.id')
                    ->where($whereCond)
                    ->orderBy('offers.created_at', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $submitted)
                {
                    $result['worker_id'] = $submitted['id'];
                    $result['worker_user_id'] = $submitted['user_id'];
                    $result['job_id'] = $submitted['job_id'];
                    $result['worker_image'] = isset($submitted['worker_image'])? url("public/images/nurses/profile/" . $submitted['worker_image']):"";
                    $result['worker_name'] = isset($submitted['first_name'])?$submitted['first_name'].' '.$submitted['last_name']:"";
                    $result['job_name'] = isset($submitted['job_name'])?$submitted['job_name']:"";
                    $result['preferred_assignment_duration'] = isset($submitted['worker_weeks_assignment'])?$submitted['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($submitted['worker_shift_time_of_day'])?$submitted['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($submitted['highest_nursing_degree'])?$submitted['highest_nursing_degree']:"";
                    $result['specialty'] = isset($submitted['specialty'])?$submitted['specialty']:"";
                    $result['experience'] = isset($submitted['experience'])?$submitted['experience'].' Years of Experience':"";
                    $result['recently'] = 'Recently Added';
                    $record[] =  $result;
                }

                $this->check = "1";
                $this->message = "Submitted Data listed successfully";
                $this->return_data = $record;
            }else{

                $this->check = "1";
                $this->message = "User not found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function offeredApplications(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $whereCond = [
                    'facilities.active' => true,
                    'jobs.recruiter_id' => $user->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Offered'
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'nurses.*', 'offers.id as offer_id', 'facilities.name as facility_name')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('nurses', 'offers.nurse_id', '=', 'nurses.id')
                    ->join('users', 'nurses.user_id', '=', 'users.id')
                    ->Join('facilities', function ($join) {
                            $join->on('facilities.id', '=', 'jobs.facility_id');
                        })
                    ->leftJoin('keywords', 'jobs.job_type', '=', 'keywords.id')
                    ->where($whereCond)
                    ->orderBy('offers.created_at', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $offered)
                {
                    $result['worker_id'] = $offered['id'];
                    $result['worker_user_id'] = $offered['user_id'];
                    $result['job_id'] = $offered['job_id'];
                    $result['worker_image'] = isset($offered['worker_image'])? url("public/images/nurses/profile/" . $offered['worker_image']):"";
                    $result['worker_name'] = isset($offered['first_name'])?$offered['first_name'].' '.$offered['last_name']:"";
                    $result['job_name'] = isset($offered['job_name'])?$offered['job_name']:"";
                    $result['preferred_assignment_duration'] = isset($offered['worker_weeks_assignment'])?$offered['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($offered['worker_shift_time_of_day'])?$offered['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($offered['highest_nursing_degree'])?$offered['highest_nursing_degree']:"";
                    $result['specialty'] = isset($offered['specialty'])?$offered['specialty']:"";
                    $result['experience'] = isset($offered['experience'])?$offered['experience'].' Years of Experience':"";
                    $result['recently'] = 'Recently Added';
                    $record[] =  $result;
                }

                $this->check = "1";
                $this->message = "Offered Data listed successfully";
                $this->return_data = $record;
            }else{

                $this->check = "1";
                $this->message = "User not found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function draftedApplications(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
            if(!isset($request->user_id)){
                $this->check = "1";
                $this->message = "Draft Applications listed successfully";
                $this->return_data = '';
            }
        } else {

            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.recruiter_id' => $user->id,
                    'jobs.is_closed' => "0",
                    'jobs.is_hidden' => "0",
                    'jobs.active' => '0',
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*', 'users.id as user_id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'facilities.name as facility_name')
                    ->Join('facilities', function ($join) {
                            $join->on('facilities.id', '=', 'jobs.facility_id');
                        })
                    ->join('users', 'jobs.recruiter_id', '=', 'users.id')
                    ->where($whereCond)
                    ->orderBy('jobs.created_at', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $draft)
                {
                    $result['recruiter_id'] = $draft['user_id'];
                    $result['job_id'] = $draft['job_id'];
                    $result['recruiter_image'] = isset($draft['worker_image'])? url("public/images/nurses/profile/" . $draft['worker_image']):"";
                    $result['recruiter_name'] = isset($draft['first_name'])?$draft['first_name'].' '.$draft['last_name']:"";
                    $result['job_type'] = isset($draft['job_type'])?$draft['job_type']:"";
                    $result['type'] = isset($draft['type'])?$draft['type']:"";
                    $result['job_name'] = isset($draft['job_name'])?$draft['job_name']:"";
                    $result['job_location'] = isset($draft['job_location'])?$draft['job_location']:"";
                    $result['job_city'] = isset($draft['job_city'])?$draft['job_city']:"";
                    $result['job_state'] = isset($draft['job_state'])?$draft['job_state']:"";
                    $result['employer_weekly_amount'] = isset($draft['employer_weekly_amount'])?$draft['employer_weekly_amount']:"";
                    $result['preferred_assignment_duration'] = isset($draft['preferred_assignment_duration'])?$draft['preferred_assignment_duration']:"";
                    $result['preferred_shift'] = isset($draft['preferred_shift'])?$draft['preferred_shift']:"";
                    $result['facility_name'] = isset($draft['facility_name'])?$draft['facility_name']:"";
                    $result['profession'] = isset($draft['profession'])?$draft['profession']:"";
                    $result['specialty'] = isset($draft['preferred_specialty'])?$draft['preferred_specialty']:"";
                    $result['experience'] = isset($draft['preferred_experience'])?$draft['preferred_experience']:"";
                    $record[] =  $result;
                }

                $this->check = "1";
                $this->message = "Draft Applications listed successfully";
                $this->return_data = $record;
            }else{
                $this->check = "1";
                $this->message = "User not found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function publishedApplications(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.created_by' => $user->id,
                    'jobs.is_closed' => "0",
                    'jobs.is_hidden' => "0",
                    'jobs.active' => '1'
                ];

                $ret = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.id as job_id', 'jobs.*','offers.id as offer_id', 'offers.nurse_id', 'users.id as user_id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'facilities.name as facility_name')
                    ->leftJoin('facilities', function ($join) {
                        $join->on('facilities.id', '=', 'jobs.facility_id');
                    })
                    ->leftJoin('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('users', 'jobs.recruiter_id', '=', 'users.id')
                    ->where($whereCond)
                    ->orderBy('offers.created_at', 'desc');

                $job_data = $ret->groupBy('id')->get();

                $result = [];
                $record = [];
                foreach($job_data as $published)
                {
                    $result['recruiter_id'] = $published['user_id'];
                    $result['job_id'] = $published['job_id'];
                    $result['total_applied'] = $published['workers_applied'];
                    $result['recruiter_image'] = isset($published['worker_image'])? url("public/images/nurses/profile/" . $published['worker_image']):"";
                    $result['recruiter_name'] = isset($published['first_name'])?$published['first_name'].' '.$published['last_name']:"";
                    $result['job_type'] = isset($published['job_type'])?$published['job_type']:"";
                    $result['type'] = isset($published['type'])?$published['type']:"";
                    $result['job_name'] = isset($published['job_name'])?$published['job_name']:"";
                    $result['job_location'] = isset($published['job_location'])?$published['job_location']:"";
                    $result['job_city'] = isset($published['job_city'])?$published['job_city']:"";
                    $result['job_state'] = isset($published['job_state'])?$published['job_state']:"";
                    $result['preferred_assignment_duration'] = isset($published['preferred_assignment_duration'])?$published['preferred_assignment_duration']:"";
                    $result['preferred_shift'] = isset($published['preferred_shift'])?$published['preferred_shift']:"";
                    $result['employer_weekly_amount'] = isset($published['employer_weekly_amount'])?$published['employer_weekly_amount']:"";
                    // $result['recently'] = isset($published['start_date'])?'Posted on '.date('M j Y', strtotime($published['start_date'])):"";
                    $result['recently'] = isset($published['created_at'])?'Posted on '. date('M d Y', strtotime($published['created_at'])):"";
                    $result['facility_name'] = isset($published['facility_name'])?$published['facility_name']:"";
                    $result['profession'] = isset($published['profession'])?$published['profession']:"";
                    $result['specialty'] = isset($published['preferred_specialty'])?$published['preferred_specialty']:"";
                    $result['experience'] = isset($published['preferred_experience'])?$published['preferred_experience']:"";
                    $record[] =  $result;
                }

                $this->check = "1";
                $this->message = "published Applications listed successfully";
                $this->return_data = $record;
            }else{
                $this->check = "1";
                $this->message = "User not found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function hiddenApplications(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.created_by' => $user->id,
                    'jobs.is_closed' => "0",
                    'jobs.is_hidden' => "1",
                    'jobs.active' => '1'
                ];

                $ret = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.id as job_id', 'jobs.*','offers.id as offer_id', 'offers.nurse_id', 'users.id as user_id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'facilities.name as facility_name')
                    ->leftJoin('facilities', function ($join) {
                        $join->on('facilities.id', '=', 'jobs.facility_id');
                    })
                    ->leftJoin('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('users', 'jobs.recruiter_id', '=', 'users.id')
                    ->where($whereCond)
                    ->orderBy('offers.created_at', 'desc');

                $job_data = $ret->groupBy('id')->get();

                $result = [];
                $record = [];
                foreach($job_data as $published)
                {
                    $result['recruiter_id'] = $published['user_id'];
                    $result['job_id'] = $published['job_id'];
                    $result['total_applied'] = $published['workers_applied'];
                    $result['recruiter_image'] = isset($published['worker_image'])? url("public/images/nurses/profile/" . $published['worker_image']):"";
                    $result['recruiter_name'] = isset($published['first_name'])?$published['first_name'].' '.$published['last_name']:"";
                    $result['job_type'] = isset($published['job_type'])?$published['job_type']:"";
                    $result['type'] = isset($published['type'])?$published['type']:"";
                    $result['job_name'] = isset($published['job_name'])?$published['job_name']:"";
                    $result['job_location'] = isset($published['job_location'])?$published['job_location']:"";
                    $result['job_city'] = isset($published['job_city'])?$published['job_city']:"";
                    $result['job_state'] = isset($published['job_state'])?$published['job_state']:"";
                    $result['preferred_assignment_duration'] = isset($published['preferred_assignment_duration'])?$published['preferred_assignment_duration']:"";
                    $result['preferred_shift'] = isset($published['preferred_shift'])?$published['preferred_shift']:"";
                    $result['employer_weekly_amount'] = isset($published['employer_weekly_amount'])?$published['employer_weekly_amount']:"";
                    $result['recently'] = isset($published['created_at'])?'Posted on '. date('M d Y', strtotime($published['created_at'])):"";
                    $result['facility_name'] = isset($published['facility_name'])?$published['facility_name']:"";
                    $result['profession'] = isset($published['profession'])?$published['profession']:"";
                    $result['specialty'] = isset($published['preferred_specialty'])?$published['preferred_specialty']:"";
                    $result['experience'] = isset($published['preferred_experience'])?$published['preferred_experience']:"";
                    $record[] =  $result;
                }

                $this->check = "1";
                $this->message = "published Applications listed successfully";
                $this->return_data = $record;
            }else{
                $this->check = "1";
                $this->message = "User not found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function closedApplications(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.recruiter_id' => $user->id,
                    'jobs.is_closed' => "1",
                    'jobs.active' => '1'
                ];

                $ret = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.id as job_id', 'jobs.*','offers.id as offer_id', 'offers.nurse_id', 'users.id as user_id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'facilities.name as facility_name')
                    ->leftJoin('facilities', function ($join) {
                        $join->on('facilities.id', '=', 'jobs.facility_id');
                    })
                    ->leftJoin('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('users', 'jobs.recruiter_id', '=', 'users.id')
                    ->where($whereCond)
                    ->orderBy('offers.created_at', 'desc');

                $job_data = $ret->groupBy('id')->get();

                $result = [];
                $record = [];
                foreach($job_data as $published)
                {
                    $result['recruiter_id'] = $published['user_id'];
                    $result['job_id'] = $published['job_id'];
                    $result['total_applied'] = $published['workers_applied'];
                    $result['recruiter_image'] = isset($published['worker_image'])? url("public/images/nurses/profile/" . $published['worker_image']):"";
                    $result['recruiter_name'] = isset($published['first_name'])?$published['first_name'].' '.$published['last_name']:"";
                    $result['job_type'] = isset($published['job_type'])?$published['job_type']:"";
                    $result['type'] = isset($published['type'])?$published['type']:"";
                    $result['job_name'] = isset($published['job_name'])?$published['job_name']:"";
                    $result['job_location'] = isset($published['job_location'])?$published['job_location']:"";
                    $result['job_city'] = isset($published['job_city'])?$published['job_city']:"";
                    $result['job_state'] = isset($published['job_state'])?$published['job_state']:"";
                    $result['preferred_assignment_duration'] = isset($published['preferred_assignment_duration'])?$published['preferred_assignment_duration']:"";
                    $result['preferred_shift'] = isset($published['preferred_shift'])?$published['preferred_shift']:"";
                    $result['employer_weekly_amount'] = isset($published['employer_weekly_amount'])?$published['employer_weekly_amount']:"";
                    $result['recently'] = isset($published['created_at'])?'Posted on '. date('M d Y', strtotime($published['created_at'])):"";
                    $result['facility_name'] = isset($published['facility_name'])?$published['facility_name']:"";
                    $result['profession'] = isset($published['profession'])?$published['profession']:"";
                    $result['specialty'] = isset($published['preferred_specialty'])?$published['preferred_specialty']:"";
                    $result['experience'] = isset($published['preferred_experience'])?$published['preferred_experience']:"";
                    $record[] =  $result;
                }

                $this->check = "1";
                $this->message = "published Applications listed successfully";
                $this->return_data = $record;
            }else{
                $this->check = "1";
                $this->message = "User not found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function onboardingApplications(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $whereCond = [
                    'facilities.active' => true,
                    'jobs.recruiter_id' => $user->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Onboarding'
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'nurses.*', 'offers.id as offer_id', 'facilities.name as facility_name')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('nurses', 'offers.nurse_id', '=', 'nurses.id')
                    ->join('users', 'nurses.user_id', '=', 'users.id')
                    ->Join('facilities', function ($join) {
                            $join->on('facilities.id', '=', 'jobs.facility_id');
                        })
                    ->leftJoin('keywords', 'jobs.job_type', '=', 'keywords.id')
                    ->where($whereCond)
                    ->orderBy('offers.created_at', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $onboarding)
                {
                    $result['worker_id'] = $onboarding['id'];
                    $result['worker_user_id'] = $onboarding['user_id'];
                    $result['job_id'] = $onboarding['job_id'];
                    $result['worker_image'] = isset($onboarding['worker_image'])? url("public/images/nurses/profile/" . $onboarding['worker_image']):"";
                    $result['worker_name'] = isset($onboarding['first_name'])?$onboarding['first_name'].' '.$onboarding['last_name']:"";
                    $result['job_name'] = isset($onboarding['job_name'])?$onboarding['job_name']:"";
                    $result['preferred_assignment_duration'] = isset($onboarding['worker_weeks_assignment'])?$onboarding['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($onboarding['worker_shift_time_of_day'])?$onboarding['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($onboarding['highest_nursing_degree'])?$onboarding['highest_nursing_degree']:"";
                    $result['specialty'] = isset($onboarding['specialty'])?$onboarding['specialty']:"";
                    $result['experience'] = isset($onboarding['experience'])?$onboarding['experience'].' Years of Experience':"";
                    $result['recently'] = 'Recently Added';
                    $record[] =  $result;
                }

                $this->check = "1";
                $this->message = "Onboarding Data listed successfully";
                $this->return_data = $record;
            }else{

                $this->check = "1";
                $this->message = "User not found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function workingApplications(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $whereCond = [
                    'facilities.active' => true,
                    'jobs.recruiter_id' => $user->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Working'
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'nurses.*', 'offers.id as offer_id', 'facilities.name as facility_name')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('nurses', 'offers.nurse_id', '=', 'nurses.id')
                    ->join('users', 'nurses.user_id', '=', 'users.id')
                    ->Join('facilities', function ($join) {
                            $join->on('facilities.id', '=', 'jobs.facility_id');
                        })
                    ->leftJoin('keywords', 'jobs.job_type', '=', 'keywords.id')
                    ->where($whereCond)
                    ->orderBy('offers.created_at', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $working)
                {
                    $result['worker_id'] = $working['id'];
                    $result['worker_user_id'] = $working['user_id'];
                    $result['job_id'] = $working['job_id'];
                    $result['worker_image'] = isset($working['worker_image'])? url("public/images/nurses/profile/" . $working['worker_image']):"";
                    $result['worker_name'] = isset($working['first_name'])?$working['first_name'].' '.$working['last_name']:"";
                    $result['job_name'] = isset($working['job_name'])?$working['job_name']:"";
                    $result['preferred_assignment_duration'] = isset($working['worker_weeks_assignment'])?$working['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($working['worker_shift_time_of_day'])?$working['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($working['highest_nursing_degree'])?$working['highest_nursing_degree']:"";
                    $result['specialty'] = isset($working['specialty'])?$working['specialty']:"";
                    $result['experience'] = isset($working['experience'])?$working['experience'].' Years of Experience':"";
                    $result['recently'] = 'Recently Added';
                    $record[] =  $result;
                }

                $this->check = "1";
                $this->message = "Working Data listed successfully";
                $this->return_data = $record;
            }else{

                $this->check = "1";
                $this->message = "User not found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function doneApplications(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $whereCond = [
                    'facilities.active' => true,
                    'jobs.recruiter_id' => $user->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Done'
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'nurses.*', 'offers.id as offer_id', 'facilities.name as facility_name')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('nurses', 'offers.nurse_id', '=', 'nurses.id')
                    ->join('users', 'nurses.user_id', '=', 'users.id')
                    ->Join('facilities', function ($join) {
                            $join->on('facilities.id', '=', 'jobs.facility_id');
                        })
                    ->leftJoin('keywords', 'jobs.job_type', '=', 'keywords.id')
                    ->where($whereCond)
                    ->orderBy('offers.created_at', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $done)
                {
                    $result['worker_id'] = $done['id'];
                    $result['worker_user_id'] = $done['user_id'];
                    $result['job_id'] = $done['job_id'];
                    $result['worker_image'] = isset($done['worker_image'])? url("public/images/nurses/profile/" . $done['worker_image']):"";
                    $result['worker_name'] = isset($done['first_name'])?$done['first_name'].' '.$done['last_name']:"";
                    $result['job_name'] = isset($done['job_name'])?$done['job_name']:"";
                    $result['preferred_assignment_duration'] = isset($done['worker_weeks_assignment'])?$done['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($done['worker_shift_time_of_day'])?$done['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($done['highest_nursing_degree'])?$done['highest_nursing_degree']:"";
                    $result['specialty'] = isset($done['specialty'])?$done['specialty']:"";
                    $result['experience'] = isset($done['experience'])?$done['experience'].' Years of Experience':"";
                    $result['recently'] = 'Recently Added';
                    $record[] =  $result;
                }

                $this->check = "1";
                $this->message = "Done Data listed successfully";
                $this->return_data = $record;
            }else{

                $this->check = "1";
                $this->message = "User not found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function rejectedApplications(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $whereCond = [
                    'facilities.active' => true,
                    'jobs.recruiter_id' => $user->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Rejected'
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'nurses.*', 'offers.id as offer_id', 'facilities.name as facility_name')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('nurses', 'offers.nurse_id', '=', 'nurses.id')
                    ->join('users', 'nurses.user_id', '=', 'users.id')
                    ->Join('facilities', function ($join) {
                            $join->on('facilities.id', '=', 'jobs.facility_id');
                        })
                    ->leftJoin('keywords', 'jobs.job_type', '=', 'keywords.id')
                    ->where($whereCond)
                    ->orderBy('offers.created_at', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $done)
                {
                    $result['worker_id'] = $done['id'];
                    $result['worker_user_id'] = $done['user_id'];
                    $result['job_id'] = $done['job_id'];
                    $result['worker_image'] = isset($done['worker_image'])? url("public/images/nurses/profile/" . $done['worker_image']):"";
                    $result['worker_name'] = isset($done['first_name'])?$done['first_name'].' '.$done['last_name']:"";
                    $result['job_name'] = isset($done['job_name'])?$done['job_name']:"";
                    $result['preferred_assignment_duration'] = isset($done['worker_weeks_assignment'])?$done['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($done['worker_shift_time_of_day'])?$done['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($done['highest_nursing_degree'])?$done['highest_nursing_degree']:"";
                    $result['specialty'] = isset($done['specialty'])?$done['specialty']:"";
                    $result['experience'] = isset($done['experience'])?$done['experience'].' Years of Experience':"";
                    $result['recently'] = 'Recently Added';
                    $record[] =  $result;
                }

                $this->check = "1";
                $this->message = "Done Data listed successfully";
                $this->return_data = $record;
            }else{

                $this->check = "1";
                $this->message = "User not found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function blockedApplications(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.recruiter_id' => $user->id,
                    'jobs.is_closed' => "0",
                    'blocked_users.recruiter_id' => $user->id
                ];

                $ret = DB::table('blocked_users')
                    ->leftJoin('offers', 'blocked_users.worker_id', '=', 'offers.nurse_id')
                    ->leftJoin('nurses', 'offers.nurse_id', '=', 'nurses.id')
                    ->leftJoin('users', 'nurses.user_id', '=', 'users.id')
                    ->leftJoin('jobs', 'offers.job_id', '=', 'jobs.id')
                    ->leftJoin('facilities', function ($join) {
                            $join->on('facilities.id', '=', 'jobs.facility_id');
                        })
                    ->where($whereCond)
                    ->orderBy('offers.created_at', 'desc')
                    ->select('jobs.id as job_id', 'jobs.*', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'nurses.*', 'offers.id as offer_id', 'facilities.name as facility_name');
                $job_data = $ret->get();
                $result = [];
                $record = [];
                foreach($job_data as $blocked)
                {
                    $result['worker_id'] = $blocked->id;
                    $result['worker_user_id'] = $blocked->user_id;
                    $result['job_id'] = $blocked->job_id;
                    $result['worker_image'] = isset($blocked->worker_image)? url("public/images/nurses/profile/" . $blocked->worker_image):"";
                    $result['worker_name'] = isset($blocked->first_name)?$blocked->first_name.' '.$blocked->last_name:"";
                    $result['job_name'] = isset($blocked->job_name)?$blocked->job_name:"";
                    $result['preferred_assignment_duration'] = isset($blocked->worker_weeks_assignment)?$blocked->worker_weeks_assignment:"";
                    $result['preferred_shift'] = isset($blocked->worker_shift_time_of_day)?$blocked->worker_shift_time_of_day:"";
                    $result['profession'] = isset($blocked->highest_nursing_degree)?$blocked->highest_nursing_degree:"";
                    $result['specialty'] = isset($blocked->specialty)?$blocked->specialty:"";
                    $result['experience'] = isset($blocked->experience)?$blocked->experience.' Years of Experience':"";
                    $result['recently'] = 'Recently Added';
                    $record[] =  $result;
                }

                $this->check = "1";
                $this->message = "Blocked Data listed successfully";
                $this->return_data = $record;
            }else{

                $this->check = "1";
                $this->message = "User not found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function applicationStatus(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
                $status = ['Screening','Submitted','Offered','Onboarding','Working','Done','Rejected','Blocked','Hold'];
                $this->check = "1";
                $this->message = "Data listed";
                $this->return_data = $status;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function workerDetails(Request $request)
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
        $nursingDegrees = $this->getNursingDegrees()->pluck('title', 'id');
        $certifications = $this->getCertifications()->pluck('title', 'id');
        $preferredShifts = $this->getPreferredShift()->pluck('title', 'id');
        $experiencesTypes = $this->getExperienceTypes()->pluck('title', 'id');
        $licenseStatus = $this->getSearchStatus()->pluck('title', 'id');
        $licenseType = $this->getLicenseType()->pluck('title', 'id');

        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'worker_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $worker_info  = Nurse::where('id', $request->worker_id);

                if($worker_info->count() > 0){
                    $worker = $worker_info->get()->first();

                    $whereCond = [
                            'facilities.active' => true,
                            'offers.nurse_id' => $worker->id,
                            // 'users.id' => $worker->user_id,
                        ];

                    $respond = Nurse::select('keywords.filter as job_filter', 'keywords.title as job_title', 'nurses.*', 'jobs.*', 'offers.job_id as job_id', 'offers.id as offer_id', 'user_id as worker_user_id', 'offers.nurse_id as worker_id', 'users.first_name', 'users.last_name', 'users.image', 'facilities.name as employer_name', 'offers.status as offer_status', 'offers.start_date as status_date', 'offers.expiration as status_enddate')
                    ->join('offers','offers.nurse_id', '=', 'nurses.id')
                    ->leftJoin('users','users.id', '=', 'nurses.user_id')
                    ->join('jobs', 'offers.job_id', '=', 'jobs.id')
                    ->leftJoin('keywords','jobs.job_type', '=', 'keywords.id')
                    ->leftJoin('facilities','jobs.facility_id', '=', 'facilities.id')
                    ->where($whereCond);
                    $job_data = $respond->get();

                    foreach($job_data as $job){
                        $job->preferred_shift_definition = (isset($preferredShifts[$job->preferred_shift]) &&  $preferredShifts[$job->preferred_shift] != "") ?  $preferredShifts[$job->preferred_shift] : "";
                        $job->job_location = isset($workLocations[$job->job_location]) ? $workLocations[$job->job_location] : "";
                        $job->preferred_specialty_definition = isset($specialties[$job->preferred_specialty])  ? $specialties[$job->preferred_specialty] : "";
                        $job->preferred_assignment_duration_definition = isset($assignmentDurations[$job->preferred_assignment_duration]) ? $assignmentDurations[$job->preferred_assignment_duration] : "";
                        if(isset($job->preferred_assignment_duration_definition)){
                            $assignment = explode(" ", $job->preferred_assignment_duration_definition);
                            $job->preferred_assignment_duration_definition = $assignment[0]; // 12 Week
                        }

                        $job->preferred_work_location_definition = isset($workLocations[$job->preferred_work_location]) ? $workLocations[$job->preferred_work_location] : "";
                        // $job->total_experience = isset($job->experience_as_acute_care_facility)?$job->experience_as_acute_care_facility:0+isset($job->experience_as_ambulatory_care_facility)?$job->experience_as_ambulatory_care_facility:0;
                        $job->total_experience = isset($job->experience)?$job->experience:0;
                        $job->total_experience = (int)$job->total_experience;
                        $job->resume_definition = (isset($job->resume) && $job->resume != "") ? url('storage/assets/nurses/resumes/' . $worker->id . '/' . $job->resume) : "";
                        $job->highest_nursing_degree_definition = (isset($worker->highest_nursing_degree) && $worker->highest_nursing_degree != "") ? \App\Providers\AppServiceProvider::keywordTitle($worker->highest_nursing_degree) : "";
                        $job->image = (isset($job->image) && $job->image != "") ? url("public/images/nurses/profile/" . $job->image) : "";

                        $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
                        if ($job->image) {
                            $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $job->image);
                            if ($t) {
                                $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $job->image);
                            }
                        }
                        // Certificate
                        $certitficate = [];
                        $cert = Certification::where(['nurse_id' => $worker->id])->whereNull('deleted_at')->get();
                        if ($cert->count() > 0) {
                            $c = $cert;
                            foreach ($c as $key => $v) {
                                $crt_data['certificate_id'] = (isset($v->id) && $v->id != "") ? $v->id : "";
                                $crt_data['type'] = (isset($v->type) && $v->type != "") ? $v->type : "";
                                $crt_data['type_definition'] = (isset($certifications[$v->type]) && $certifications[$v->type] != "") ? $certifications[$v->type] : "";
                                $crt_data['license_number'] = (isset($v->license_number) && $v->license_number != "") ? $v->license_number : "";
                                $crt_data['organization'] = (isset($v->organization) && $v->organization != "") ? $v->organization : "";
                                $crt_data['effective_date'] = (isset($v->effective_date) && $v->effective_date != "") ? date('m/d/Y', strtotime($v->effective_date)) : "";
                                $crt_data['expiration_date'] = (isset($v->expiration_date) && $v->expiration_date != "") ? date('m/d/Y', strtotime($v->expiration_date)) : "";
                                $crt_data['renewal_date'] = (isset($v->renewal_date) && $v->renewal_date != "") ? date('m/d/Y', strtotime($v->renewal_date)) : "";

                                $crt_data['certificate_image'] = (isset($v->certificate_image) && $v->certificate_image != "") ? url('storage/assets/nurses/certifications/' . $nurse->id . '/' . $v->certificate_image) : "";

                                $certificate_image_base = "";
                                if ($v->certificate_image) {
                                    $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/certifications/' . $v->certificate_image);
                                    if ($t) {
                                        $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/nurses/certifications/' . $v->certificate_image);
                                    }
                                }
                                $crt_data['created_at'] = (isset($v->created_at) && $v->created_at != "") ? $v->created_at : "";
                                $certitficate[] = $crt_data;
                            }
                        }
                        $job->certitficate = $certitficate;
                    }

                    $response = [];

                    foreach($job_data as $res){

                        if(isset($res->recruiter_id) && $res->recruiter_id == $request->user_id){
                            $response['main'] = $res;
                        }else{
                            $response['other_Facility_jobs'][] =  $res;
                        }
                    }

                    $this->check = "1";
                    $this->message = "Worker details listed successfully";
                    $this->return_data = $response;


                }else{
                    $this->check = "1";
                    $this->message = "Worker Not Found";
                }

            }else{
                $this->check = "1";
                $this->message = "User Not Found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function updateStatus(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'offer_id' => 'required',
            'status' => 'required',
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $notification = [];
            $status = null;
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $current_date = now()->format('Y-m-d');
                $records = Offer::where('id', $request->offer_id)->first();
                $fields = new Notification();
                $fields->created_by = $records['nurse_id'];
                $fields->job_id = $records['job_id'];
                $fields->updated_at = NULL;
                $fields->deleted_at = NULL;
                $fields->created_at = date('Y-m-d h:i:s');

                if($request->status == 'Offered'){
                    if(isset($records)){
                        $request->offer_date = isset($request->offer_date)?$request->offer_date:now()->format('Y-m-d');
                        $status = DB::table('offers')->where('id', $request->offer_id)->update(array('status' => $request->status, 'expiration' => $request->offer_date));
                        $fields->title = 'Submitted to Offered';
                        $fields->text = 'Your Application for job '. $records['job_id'] .' at Medical Solutions Recruiter was moved from Submitted to Offered by '. $user->first_name.' '.$user->last_name .' from Employer name';
                        $check = Notification::where(['job_id' => $records['job_id'], 'created_by' => $records['nurse_id']])->first();
                        if($status != 0){
                            if(empty($check)){
                                $notification = $fields->save();
                            }else{
                                $notification = DB::table('notifications')->where('id', $check->id)->update(array('title' => $fields->title, 'text' => $fields->text, 'updated_at' => date('Y-m-d h:i:s')));
                            }
                        }
                        if(isset($status)){
                            $this->check = "1";
                            $this->message = "Status updated successfully";
                            $this->return_data = $status;
                        }else{
                            $this->check = "1";
                            $this->message = "Status not updated, This job have already Status";
                        }
                    }else{
                        $this->check = "1";
                        $this->message = "Offer Record not found";
                    }

                }else if($request->status == 'Onboarding'){
                    if(isset($records)){
                        $request->on_board_date = isset($request->on_board_date)?$request->on_board_date:now()->format('Y-m-d');

                        // Check total job hire
                        $is_vacancy = DB::select("SELECT COUNT(id) as hired_jobs, job_id FROM `offers` WHERE status = 'Onboarding' AND job_id = ".'"'.$records['job_id'].'"');
                        if(isset($is_vacancy)){
                            $is_vacancy = $is_vacancy[0]->hired_jobs;
                        }else{
                            $is_vacancy = '0';
                        }
                        $check_rec = Job::where('id', $records['job_id'])->first();
                        if($check_rec['position_available'] > $is_vacancy)
                        {
                            $status = DB::table('offers')->where('id', $request->offer_id)->update(array('status' => $request->status, 'start_date' => $request->on_board_date));
                            $fields->title = 'Offered to Onboarding';
                            $fields->text = 'Your Application for job '. $records['job_id'] .' at Medical Solutions Recruiter was moved from Offered to Onboarding by '. $user->first_name.' '.$user->last_name .' from Employer name';
                            $check = Notification::where(['job_id' => $records['job_id'], 'created_by' => $records['nurse_id']])->first();
                            if($status != 0){
                                if(empty($check)){
                                    $notification = $fields->save();
                                }else{
                                    $notification = DB::table('notifications')->where('id', $check->id)->update(array('title' => $fields->title, 'text' => $fields->text, 'updated_at' => date('Y-m-d h:i:s')));
                                }
                            }
                            if(isset($status)){
                                $this->check = "1";
                                $this->message = "Status updated successfully";
                                $this->return_data = $status;
                            }else{
                                $this->check = "1";
                                $this->message = "Status not updated, This job have already Status";
                            }
                        }else{
                            if($check_rec['active'] != 0){
                                Job::where([
                                    'id' => $check_rec['job_id'],
                                ])->update(['is_closed' => '1']);
                            }
                            // Job::where([
                            //     'id' => $check_rec['job_id'],
                            // ])->update(['is_closed' => '1']);
                            $this->check = "1";
                            $this->message = "Status not updated, This job have already fullfill Positions";
                        }


                    }else{
                        $this->check = "1";
                        $this->message = "Offer Record not found";
                    }
                }else if($request->status == 'Working'){
                    if(isset($records)){
                        $request->working_date = isset($request->working_date)?$request->working_date:now()->format('Y-m-d');
                        $status = DB::table('offers')->where('id', $request->offer_id)->update(array('status' => $request->status, 'start_date' => $request->working_date));
                        $fields->title = 'Working to Done';
                        $fields->text = 'Your Application for job '. $records['job_id'] .' at Medical Solutions Recruiter was moved from Working to Done by '. $user->first_name.' '.$user->last_name .' from Employer name';
                        $check = Notification::where(['job_id' => $records['job_id'], 'created_by' => $records['nurse_id']])->first();

                        if($status != 0){
                            if(empty($check)){
                                $notification = $fields->save();
                            }else{
                                $notification = DB::table('notifications')->where('id', $check->id)->update(array('title' => $fields->title, 'text' => $fields->text, 'updated_at' => date('Y-m-d h:i:s')));
                            }
                        }
                        if(isset($status)){
                            $this->check = "1";
                            $this->message = "Status updated successfully";
                            $this->return_data = $status;
                        }else{
                            $this->check = "1";
                            $this->message = "Status not updated, This job have already Status";
                        }
                    }else{
                        $this->check = "1";
                        $this->message = "Offer Record not found";
                    }
                }else if($request->status == 'Done'){
                    if(isset($records)){
                        $request->start_date = isset($request->start_date)?$request->start_date:now()->format('Y-m-d');
                        $request->end_date = isset($request->end_date)?$request->end_date:now()->format('Y-m-d');
                        $status = DB::table('offers')->where('id', $request->offer_id)->update(array('status' => $request->status, 'start_date' => $request->start_date, 'expiration' => $request->end_date));
                        $fields->title = 'Done';
                        $fields->text = 'Your Application for job '. $records['job_id'] .' at Medical Solutions Recruiter was Done by '. $user->first_name.' '.$user->last_name .' from Employer name';
                        $check = Notification::where(['job_id' => $records['job_id'], 'created_by' => $records['nurse_id']])->first();
                        if($status != 0){
                            if(empty($check)){
                                $notification = $fields->save();
                            }else{
                                $notification = DB::table('notifications')->where('id', $check->id)->update(array('title' => $fields->title, 'text' => $fields->text, 'updated_at' => date('Y-m-d h:i:s')));
                            }
                        }
                        if(isset($status)){
                            $this->check = "1";
                            $this->message = "Status updated successfully";
                            $this->return_data = $status;
                        }else{
                            $this->check = "1";
                            $this->message = "Status not updated, This job have already Status";
                        }
                    }else{
                        $this->check = "1";
                        $this->message = "Offer Record not found";
                    }
                }else if($request->status == 'Submitted'){
                    if(isset($records)){
                        $request->submitted_date = isset($request->submitted_date)?$request->submitted_date:now()->format('Y-m-d');
                        $status = DB::table('offers')->where('id', $request->offer_id)->update(array('status' => $request->status, 'start_date' => $request->submitted_date));
                        $fields->title = 'Screening to Submitted';
                        $fields->text = 'Your Application for job '. $records['job_id'] .' at Medical Solutions Recruiter was moved from Screening to Submitted by '. $user->first_name.' '.$user->last_name .' from Employer name';
                        $check = Notification::where(['job_id' => $records['job_id'], 'created_by' => $records['nurse_id']])->first();
                        if($status != 0){
                            if(empty($check)){
                                $notification = $fields->save();
                            }else{
                                $notification = DB::table('notifications')->where('id', $check->id)->update(array('title' => $fields->title, 'text' => $fields->text, 'updated_at' => date('Y-m-d h:i:s')));
                            }
                        }
                        if(isset($status)){
                            $this->check = "1";
                            $this->message = "Status updated successfully";
                            $this->return_data = $status;
                        }else{
                            $this->check = "1";
                            $this->message = "Status not updated, This job have already Status";
                        }
                    }else{
                        $this->check = "1";
                        $this->message = "Offer Record not found";
                    }
                }else if($request->status == 'Rejected'){
                    if(isset($records)){
                        $offer = Offer::where('id', $request->offer_id)->first();
                        $whereCond = [
                            'nurse_id' => $offer['nurse_id'],
                            'job_id' => $offer['job_id']
                        ];
                        $checkoffer = DB::table('job_saved')->where($whereCond)->first();
                        if(isset($checkoffer))
                        {
                            DB::table('job_saved')->where('id', $checkoffer->id)->update(['is_delete' => '1']);
                        }else{
                            $insert = array(
                                "nurse_id" => $offer['nurse_id'],
                                'job_id' => $offer['job_id'],
                                'is_save' => '0',
                                'is_delete' => '1',
                            );
                            DB::table('job_saved')->insert($insert);
                        }

                        $request->rejected_date = isset($request->rejected_date)?$request->rejected_date:now()->format('Y-m-d');
                        $status = DB::table('offers')->where('id', $request->offer_id)->update(array('status' => $request->status, 'start_date' => $request->rejected_date));
                        $fields->title = 'Rejected';
                        $fields->text = 'Your Application for job '. $records['job_id'] .' at Medical Solutions Recruiter was moved to Rejected by '. $user->first_name.' '.$user->last_name .' from Employer name';
                        $check = Notification::where(['job_id' => $records['job_id'], 'created_by' => $records['nurse_id']])->first();
                        if($status != 0){
                            if(empty($check)){
                                $notification = $fields->save();
                            }else{
                                $notification = DB::table('notifications')->where('id', $check->id)->update(array('title' => $fields->title, 'text' => $fields->text, 'updated_at' => date('Y-m-d h:i:s')));
                            }
                        }
                        if(isset($status)){
                            $this->check = "1";
                            $this->message = "Status updated successfully";
                            $this->return_data = $status;
                        }else{
                            $this->check = "1";
                            $this->message = "Status not updated, This job have already Status";
                        }
                    }else{
                        $this->check = "1";
                        $this->message = "Offer Record not found";
                    }
                }else if($request->status == 'Blocked'){
                    if(isset($records))
                    {
                        if(isset($request->recruiter_id)){
                            $offer = Offer::where('id', $request->offer_id)->first();
                            $whereCond = [
                                'worker_id' => $offer['nurse_id'],
                                'recruiter_id' => $request->recruiter_id
                            ];
                            $checkoffer = DB::table('blocked_users')->where($whereCond)->first();
                            if(isset($checkoffer))
                            {
                                DB::table('blocked_users')->where('id', $checkoffer->id)->update(['status' => '1']);
                            }else{
                                $insert = array(
                                    "worker_id" => $offer['nurse_id'],
                                    'recruiter_id' => $request->recruiter_id,
                                    'status' => '1'
                                );
                                DB::table('blocked_users')->insert($insert);
                            }

                            $request->blocked_date = isset($request->blocked_date)?$request->blocked_date:now()->format('Y-m-d h:i:s');
                            $status = DB::table('offers')->where('id', $request->offer_id)->update(array('updated_at' => $request->blocked_date));
                            // $status = DB::table('offers')->where('id', $request->offer_id)->update(array('status' => $request->status, 'start_date' => $request->blocked_date));
                            $fields->title = 'Blocked';
                            $fields->text = 'Your Application for job '. $records['job_id'] .' at Medical Solutions Recruiter was moved to Blocked by '. $user->first_name.' '.$user->last_name .' from Employer name';
                            $check = Notification::where(['job_id' => $records['job_id'], 'created_by' => $records['nurse_id']])->first();
                            if($status != 0){
                                if(empty($check)){
                                    $notification = $fields->save();
                                }else{
                                    $notification = DB::table('notifications')->where('id', $check->id)->update(array('title' => $fields->title, 'text' => $fields->text, 'updated_at' => date('Y-m-d h:i:s')));
                                }
                            }
                            if(isset($status)){
                                $this->check = "1";
                                $this->message = "Status updated successfully";
                                $this->return_data = $status;
                            }else{
                                $this->check = "1";
                                $this->message = "Status not updated, This job have already Status";
                            }
                        }else{
                            $this->check = "1";
                            $this->message = "Recruiter not found";
                        }
                    }else{
                        $this->check = "1";
                        $this->message = "Offer Record not found";
                    }
                }else{
                    if(isset($records)){
                        $status = DB::table('offers')->where('id', $request->offer_id)->update(array('status' => $request->status));
                        $fields->title = 'New to Screening';
                        $fields->text = 'Your Application for job '. $records['job_id'] .' at Medical Solutions Recruiter was moved from New to Screening by '. $user->first_name.' '.$user->last_name .' from Employer name';
                        $check = Notification::where(['job_id' => $records['job_id'], 'created_by' => $records['nurse_id']])->first();
                        if($status != 0){
                            if(empty($check)){
                                $notification = $fields->save();
                            }else{
                                $notification = DB::table('notifications')->where('id', $check->id)->update(array('title' => $fields->title, 'text' => $fields->text, 'updated_at' => date('Y-m-d h:i:s')));
                            }
                            if(isset($status)){
                                $this->check = "1";
                                $this->message = "Status updated successfully";
                                $this->return_data = $status;
                            }else{
                                $this->check = "1";
                                $this->message = "Status not updated, This job have already Status";
                            }
                        }
                    }else{
                        $this->check = "1";
                        $this->message = "Offer Record not found";
                    }
                }

            }else{
                $this->check = "1";
                $this->message = "User not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);


    }

    public function exploreScreen(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.recruiter_id' => $request->user_id,
                    'jobs.is_closed' => "0"
                ];

                $respond = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.id as job_id', 'offers.nurse_id as worker_id', 'users.first_name', 'users.last_name', 'users.image', 'facilities.name as facility_name', 'jobs.*', 'jobs.created_at as posted_on')
                                ->leftJoin('offers','offers.job_id', '=', 'jobs.id')
                                ->join('users','users.id', '=', 'jobs.recruiter_id')
                                ->leftJoin('facilities','jobs.facility_id', '=', 'facilities.id')
                                ->where($whereCond);
                $job_data = $respond->groupBy('jobs.id')->get();

                foreach($job_data as $job){
                    $job->job_location = isset($job->job_location) ? $job->job_location : "";
                    $job->facility_name = isset($job->facility_name) ? $job->facility_name : "";
                    $job->job_name = isset($job->job_name) ? $job->job_name : "";
                    $job->preferred_specialty = isset($job->preferred_specialty) ? $job->preferred_specialty : "";
                    $job->preferred_shift = isset($job->preferred_shift) ? $job->preferred_shift : "";
                    $job->profession = isset($job->profession) ? $job->profession : "";
                    $job->hours_per_week = isset($job->hours_per_week) ? $job->hours_per_week : "";
                    $job->job_type = isset($job->job_type) ? $job->job_type : "";
                    $job->type = isset($job->type) ? $job->type : "";
                    $job->preferred_assignment_duration = isset($job->preferred_assignment_duration) ? $job->preferred_assignment_duration : "";
                    if(isset($job->posted_on)){
                        $job->posted_on = 'Posted on '.date('M j Y', strtotime($job->posted_on));
                    }else{
                        $job->posted_on = '';
                    }
                    $job->image = (isset($job->image) && $job->image != "") ? url("public/images/nurses/profile/" . $job->image) : "";

                    $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
                    if ($job->image) {
                        $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $job->image);
                        if ($t) {
                            $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $job->image);
                        }
                    }

                }

                $this->check = "1";
                $this->message = "Explore screen listed successfully";
                $this->return_data = $job_data;

            }else{
                $this->check = "1";
                $this->message = "User Not Found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);



    }

    // M.ELH Note : the infromation retrived for a (worker,jobs,recruiter) not just a recruiter
    public function recruiterInformation(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'worker_id' => 'required',
            'api_key' => 'required',
            'job_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_job = Job::where('id', $request->job_id)->first();
            if(isset($check_job) && $check_job['is_hidden'] != 1){
                $worker_info  = Nurse::where('id', $request->worker_id);
                if ($worker_info->count() > 0) {
                    $worker = $worker_info->get()->first();
                    $user_info = USER::where('id', $worker->user_id);
                    if($user_info->count() > 0){
                        $user = $user_info->get()->first();
                        $worker_name = $user->first_name.' '.$user->last_name;
                        $worker_id = $worker->id;
                        $worker_img = $user->image;
                        $whereCond = [
                                'facilities.active' => true,
                                'users.id' => $worker->user_id,
                                'nurses.id' => $worker->id,
                                'jobs.id' => $request->job_id,
                            ];

                        $respond = Nurse::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'nurses.*', 'jobs.*', 'offers.job_id as job_id', 'facilities.name as facility_name', 'facilities.city as facility_city', 'facilities.state as facility_state', 'nurses.block_scheduling as worker_block_scheduling', 'nurses.float_requirement as worker_float_requirement', 'nurses.facility_shift_cancelation_policy as worker_facility_shift_cancelation_policy', 'nurses.contract_termination_policy as worker_contract_termination_policy', 'offers.id as offer_id', 'offers.start_date as posted_on', 'offers.status as job_status', 'jobs.created_at as created_at')
                                        ->join('users','users.id', '=', 'nurses.user_id')
                                        ->leftJoin('offers','offers.nurse_id', '=', 'nurses.id')
                                        ->leftJoin('jobs', 'offers.job_id', '=', 'jobs.id')
                                        ->leftJoin('facilities','jobs.facility_id', '=', 'facilities.id')
                                        ->where($whereCond);
                        $job_data = $respond->groupBy('jobs.id')->first();
                        $job_data = $worker;
                        $job_data['worker_block_scheduling'] = $job_data['block_scheduling'];
                        $job_data['worker_float_requirement'] = $job_data['float_requirement'];
                        $job_data['worker_facility_shift_cancelation_policy'] = $job_data['facility_shift_cancelation_policy'];
                        $job_data['worker_contract_termination_policy'] = $job_data['contract_termination_policy'];

                        if(empty($job_data)){
                            $whereCond1 =  [
                                'facilities.active' => true,
                                'jobs.id' => $request->job_id,
                            ];

                            $worker_jobs = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.*', 'offers.job_id as job_id', 'facilities.name as facility_name', 'facilities.city as facility_city', 'facilities.state as facility_state', 'offers.start_date as posted_on', 'jobs.created_at as created_at')

                            ->leftJoin('offers','offers.job_id', '=', 'jobs.id')
                            ->leftJoin('facilities','jobs.facility_id', '=', 'facilities.id')
                            ->where($whereCond1)->groupBy('jobs.id')->first();
                            $job_data['workers_applied'] = $worker_jobs['workers_applied'];
                            $job_data['worker_contract_termination_policy'] = $worker_jobs['contract_termination_policy'];
                            $job_data['job_id'] = $worker_jobs['job_id'];
                            $job_data['facility_name'] = $worker_jobs['facility_name'];
                            $job_data['facility_city'] = $worker_jobs['facility_city'];
                            $job_data['facility_state'] = $worker_jobs['facility_state'];
                            // $job_data['posted_on'] = $worker_jobs['posted_on'];
                            $job_data['created_at'] = $worker_jobs['created_at'];
                        }
                        $job_data['posted_on'] = $job_data['created_at'];
                        if(isset($job_data['recruiter_id']) && !empty($job_data['recruiter_id'])){
                            $recruiter_info = USER::where('id', $job_data['recruiter_id'])->get()->first();
                            $recruiter_name = $recruiter_info->first_name.' '.$recruiter_info->last_name;
                            $recruiter_id = $job_data['recruiter_id'];
                        }else{
                            $recruiter_name = '';
                            $recruiter_id = '';
                        }
                        $worker_reference = NURSE::select('nurse_references.name','nurse_references.min_title_of_reference','nurse_references.recency_of_reference')
                        ->leftJoin('nurse_references','nurse_references.nurse_id', '=', 'nurses.id')
                        ->where('nurses.id', $worker->id)->get();

                        $job = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.*')->where('id', $request->job_id)->first();
                        $worker_reference_name = '';
                        $worker_reference_title ='';
                        $worker_reference_recency_reference ='';
                        foreach($worker_reference as $val){
                            if(!empty($val['name'])){
                                $worker_reference_name = $val['name'].','.$worker_reference_name;
                            }
                            if(!empty($val['min_title_of_reference'])){
                                $worker_reference_title = $val['min_title_of_reference'].','.$worker_reference_title;
                            }
                            if(!empty($val['recency_of_reference'])){
                                $worker_reference_recency_reference = $val['recency_of_reference'].','.$worker_reference_recency_reference;
                            }
                        }

                        // Check total job hire
                        $is_vacancy = DB::select("SELECT COUNT(id) as hired_jobs, job_id FROM `offers` WHERE status = 'Onboarding' AND job_id = ".'"'.$job['id'].'"');
                        if(isset($is_vacancy)){
                            $is_vacancy = $is_vacancy[0]->hired_jobs;
                        }else{
                            $is_vacancy = '0';
                        }

                        // Jobs speciality with experience
                        $speciality = explode(',',$job['preferred_specialty']);
                        $experiences = explode(',',$job['preferred_experience']);
                        $exp = [];
                        $spe = [];
                        $specialities = [];
                        $i = 0;
                        foreach($speciality as $special){
                            $spe[] = $special;
                            $i++;
                        }
                        foreach($experiences as $experience){
                            $exp[] = $experience;
                        }

                        for($j=0; $j< $i; $j++){
                            $specialities[$j]['spe'] = $spe[$j];
                            $specialities[$j]['exp'] = $exp[$j];
                        }

                        // Worker speciality
                        $worker_speciality = explode(',',$worker->specialty);
                        $worker_experiences = explode(',',$worker->experience);
                        $worker_exp = [];
                        $worker_spe = [];
                        $worker_specialities = [];
                        $i = 0;
                        foreach($speciality as $special){
                            $worker_spe[] = $special;
                            $i++;
                        }
                        foreach($experiences as $experience){
                            $worker_exp[] = $experience;
                        }

                        for($j=0; $j< $i; $j++){
                            $worker_specialities[$j]['spe'] = $worker_spe[$j];
                            $worker_specialities[$j]['exp'] = $worker_exp[$j];
                        }

                        $worker_certificate = [];
                        // $skills_checklists = [];
                        $vaccinations = explode(',',$job['vaccinations']);
                        $worker_vaccination = json_decode($job_data['worker_vaccination']);
                        $worker_certificate_name = json_decode($job_data['worker_certificate_name']);
                        $worker_certificate = json_decode($job_data['worker_certificate']);
                        $skills_checklists = explode(',', $job_data['skills_checklists']);
                        $i=0;
                        foreach($skills_checklists as $rec)
                        {
                            if(isset($rec) && !empty($rec)){
                                $skills_checklists[$i] = url('public/images/nurses/skill/'.$rec);
                                $i++;
                            }

                        }
                        $vacc_image = NurseAsset::where(['filter' => 'vaccination', 'nurse_id' => $worker->id])->get();
                        $cert_image = NurseAsset::where(['filter' => 'certificate', 'nurse_id' => $worker->id])->get();
                        $certificate = explode(',',$job['certificate']);

                        $result = [];
                        $result['job_id'] = isset($job['id'])?$job['id']:"";
                        $result['description'] = isset($job['description'])?$job['description']:"";
                        $result['posted_on'] = isset($job_data['posted_on'])?date('M j Y', strtotime($job_data['posted_on'])):"";
                        $result['type'] = isset($job['type'])?$job['type']:"";
                        $result['terms'] = isset($job['terms'])?$job['terms']:"";
                        $result['job_name'] = isset($job['job_name'])?$job['job_name']:"";
                        $result['job_status'] = isset($job_data['job_status'])?$job_data['job_status']:"";
                        $result['offer_id'] = isset($job_data['offer_id'])?$job_data['offer_id']:"";
                        $result['total_applied'] = isset($job['workers_applied'])?$job['workers_applied']:"";
                        $result['department'] = isset($job['Department'])?$job['Department']:"";
                        $result['worker_name'] = isset($worker_name)?$worker_name:"";
                        $result['worker_image'] = isset($worker_img)?$worker_img:"";
                        $result['worker_id'] = isset($worker_id)?$worker_id:"";
                        $result['recruiter_name'] = $recruiter_name;
                        $result['recruiter_id'] = $recruiter_id;
                        if(isset($job_data['worked_at_facility_before']) && ($job_data['worked_at_facility_before'] == 'yes')){
                            $recs = true;
                        }else{
                            $recs = false;
                        }

                        if(isset($job_data['license_type']) && ($job_data['license_type'] != null) && ($job_data['profession'] == $job_data['license_type'])){
                            $profession = true;
                        }else{
                            $profession = false;
                        }
                        if(isset($job_data['specialty']) && ($job_data['specialty'] != null) && ($job_data['preferred_specialty'] == $job_data['specialty'])){
                            $speciality = true;
                        }else{
                            $speciality = false;
                        }
                        if(isset($job_data['experience']) && ($job_data['experience'] != null) && ($job_data['preferred_experience'] == $job_data['experience'])){
                            $experience = true;
                        }else{
                            $experience = false;
                        }
                        $countable = explode(',',$worker_reference_name);
                        $num = [];
                        foreach($countable as $rec){
                            if(!empty($rec)){
                                $num[] = $rec;
                            }
                        }
                        $countable = count($num);
                        if($job_data['number_of_references'] == $countable){
                            $worker_ref_num = true;
                        }else{
                            $worker_ref_num = false;
                        }

                        $worker_info = [];
                        // $data =  [];
                        $data['job'] = 'College Diploma Required';
                        $data['match'] = !empty($job_data['diploma'])?true:false;
                        $data['worker'] = !empty($job_data['diploma'])?url('public/images/nurses/diploma/'.$job_data['diploma']):"";
                        $data['name'] = 'Diploma';
                        $data['match_title'] = 'Diploma';
                        $data['update_key'] = 'diploma';
                        $data['type'] = 'files';
                        $data['worker_title'] = 'Did you really graduate?';
                        $data['job_title'] = 'College Diploma Required';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $data['worker_image'] = !empty($job_data['diploma'])?url('public/images/nurses/diploma/'.$job_data['diploma']):"";
                        $worker_info[] = $data;
                        $data['worker_image'] = '';

                        $data['job'] = 'Drivers License';
                        $data['match'] = !empty($job_data['driving_license'])?true:false;
                        $data['worker'] = !empty($job_data['driving_license'])?url('public/images/nurses/driving_license/'.$job_data['driving_license']):"";
                        $data['name'] = 'driving_license';
                        $data['match_title'] = 'Driving License';
                        $data['update_key'] = 'driving_license';
                        $data['type'] = 'files';
                        $data['worker_title'] = 'Are you really allowed to drive?';
                        $data['job_title'] = 'Picture of Front and Back DL';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $data['worker_image'] = !empty($job_data['driving_license'])?url('public/images/nurses/driving_license/'.$job_data['driving_license']):"";
                        $worker_info[] = $data;
                        $data['worker_image'] = '';

                        $data['job'] = !empty($job['job_worked_at_facility_before'])?$job['job_worked_at_facility_before']:"";
                        $data['match'] = $recs;
                        $data['worker'] = !empty($job_data['worked_at_facility_before'])?$job_data['worked_at_facility_before']:"";
                        $data['name'] = 'Working at Facility Before';
                        $data['match_title'] = 'Worked at Facility Before';
                        $data['update_key'] = 'worked_at_facility_before';
                        $data['type'] = 'checkbox';
                        $data['worker_title'] = 'Are you sure you never worked here as staff?';
                        $data['job_title'] = 'Have you worked here in the last 18 months?';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = "Last 4 digit of SS# to submit";
                        $data['match'] = !empty($job_data['worker_ss_number'])?true:false;
                        $data['worker'] = !empty($job_data['worker_ss_number'])?$job_data['worker_ss_number']:"";
                        $data['name'] = 'SS Card Number';
                        $data['match_title'] = 'SS # Or SS Card';
                        $data['update_key'] = 'worker_ss_number';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Yes we need your SS# to submit you';
                        $data['job_title'] = 'last 4 digit of SS# to submit';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['profession'] == $job_data['highest_nursing_degree']){ $val = true; }else{ $val = false; }
                        $data['job'] = isset($job['profession'])?$job['profession']:"";
                        $data['match'] = $val;
                        $data['worker'] = !empty($job_data['highest_nursing_degree'])?$job_data['highest_nursing_degree']:"";
                        $data['name'] = 'Profession';
                        $data['match_title'] = 'Profession';
                        $data['update_key'] = 'highest_nursing_degree';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = 'What kind of Professional are you?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Profession';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['preferred_specialty'])?$job['preferred_specialty']:"";
                        $data['match'] = $speciality;
                        $data['worker'] = !empty($job_data['specialty'])?$job_data['specialty']:"";
                        $data['name'] = 'Speciality';
                        $data['match_title'] = 'Speciality';
                        $data['update_key'] = 'specialty';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = "What's your specialty?";
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Specialty';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['preferred_experience'])?$job['preferred_experience']:"";
                        $data['match'] = $experience;
                        $data['worker'] = !empty($job_data['experience'])?$job_data['experience']:"";
                        $data['name'] = 'Experience';
                        $data['match_title'] = 'Experience';
                        $data['update_key'] = 'experience';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'How long have you done this?';
                        $data['job_title'] = $data['job'].' Years';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job_data['nursing_license_state'] == $job['job_location']){ $val = true; }else{ $val = false; }
                        $data['job'] = isset($job['job_location'])?$job['job_location']:"";
                        $data['match'] = $val;
                        $data['worker'] = !empty($job_data['nursing_license_state'])?$job_data['nursing_license_state']:"";
                        $data['name'] = 'License State';
                        $data['match_title'] = 'Professional Licensure';
                        $data['update_key'] = 'nursing_license_state';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = 'Where are you licensed?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Professional Licensure';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $i = 0;
                        foreach($vaccinations as $job_vacc)
                        {
                            $data['job'] = isset($vaccinations[$i])?$vaccinations[$i]:"Vaccinations & Immunizations";
                            $data['match'] = !empty($worker_vaccination[$i])?true:false;
                            $data['worker'] = isset($worker_vaccination[$i])?$worker_vaccination[$i]:"";
                            $data['worker_image'] = isset($vacc_image[$i]['name'])?url('public/images/nurses/vaccination/'.$vacc_image[$i]['name']):"";
                            $data['name'] = $data['worker'].' vaccination';
                            $data['match_title'] = 'Vaccinations & Immunizations';
                            $data['update_key'] = 'worker_vaccination';
                            $data['type'] = 'file';
                            $data['worker_title'] = 'Did you get the '.$data['worker'].' Vaccines?';
                            $data['job_title'] = !empty($data['job'])?$data['job'].' Required':'Vaccinations & Immunizations';
                            $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                            $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                            $worker_info[] = $data;
                            $i++;
                            // $data['worker_image'] = '';
                        }
                        $data['worker_image'] = '';

                        $data['job'] = isset($job['number_of_references'])?$job['number_of_references']:"";
                        $data['match'] = $worker_ref_num;
                        $data['worker'] = isset($worker_reference_name)?$worker_reference_name:"";
                        $data['name'] = 'Reference';
                        $data['match_title'] = 'Number Of Reference';
                        $data['update_key'] = 'worker_reference_name';
                        $data['type'] = 'multiple';
                        $data['worker_title'] = 'Who are your References?';
                        $data['job_title'] = !empty($data['job'])?$data['job'].' References':'number of references';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['min_title_of_reference'])?$job['min_title_of_reference']:"";
                        $data['match'] = !empty($worker_reference_title)?true:false;
                        $data['worker'] = isset($worker_reference_title)?$worker_reference_title:"";
                        $data['name'] = 'Reference title';
                        $data['match_title'] = 'Min Title Of Reference';
                        $data['update_key'] = 'worker_reference_title';
                        $data['type'] = 'multiple';
                        $data['worker_title'] = 'What was their title?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'min title of reference';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['recency_of_reference'])?$job['recency_of_reference']:"";
                        $data['match'] = !empty($worker_reference_recency_reference)?true:false;
                        $data['worker'] = isset($worker_reference_recency_reference)?$worker_reference_recency_reference:"";
                        $data['name'] = 'Recency Reference Assignment';
                        $data['match_title'] = 'Recency Of Reference';
                        $data['update_key'] = 'worker_reference_recency_reference';
                        $data['type'] = 'multiple';
                        $data['worker_title'] = 'Is this from your last assignment?';
                        $data['job_title'] = !empty($data['job'])?$data['job'].' months':'recency of reference';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $i = 0;
                        foreach($certificate as $job_cert)
                        {
                            $data['job'] = isset($certificate[$i])?$certificate[$i]:"Certifications";
                            $data['match'] = !empty($worker_certificate_name[$i])?true:false;
                            $data['worker'] = isset($worker_certificate_name[$i])?$worker_certificate_name[$i]:"";
                            if(isset($worker_certificate_name[$i])){
                                $data['worker_image'] = isset($cert_image[$i]['name'])?url('public/images/nurses/certificate/'.$cert_image[$i]['name']):"";
                            }
                            $data['name'] = $data['worker'];
                            $data['match_title'] = 'Certifications';
                            $data['update_key'] = 'worker_certificate';
                            $data['type'] = 'file';
                            $data['worker_title'] = 'No '.$data['worker'];
                            $data['job_title'] = !empty($data['job'])?$data['job'].' Required':'Certifications';
                            $worker_info[] = $data;
                            $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                            $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                            $i++;
                        }
                        $data['worker_image'] = '';

                        $data['job'] = isset($job['skills'])?$job['skills']:"";
                        $data['match'] = !empty($job_data['skills_checklists'])?true:false;
                        $data['worker'] = isset($job_data['skills'])?$job_data['skills']:"";
                        if(isset($job_data['skills'])){
                            $data['worker_image'] = isset($skills_checklists)?$skills_checklists[0]:"";
                        }
                        $data['name'] = 'Skills';
                        $data['match_title'] = 'Skills checklist';
                        $data['update_key'] = 'skills_checklists';
                        $data['type'] = 'file';
                        $data['worker_title'] = 'Upload your latest skills checklist';
                        $data['job_title'] = $data['job'].' Skills checklist';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;
                        $data['worker_image'] = '';

                        if($job_data['eligible_work_in_us'] == 'yes'){ $eligible_work_in_us = true; }else{ $eligible_work_in_us = false; }
                        $data['job'] = "Eligible work in the us";
                        $data['match'] = $eligible_work_in_us;
                        $data['worker'] = isset($job_data['eligible_work_in_us'])?$job_data['eligible_work_in_us']:"";
                        $data['name'] = 'eligible_work_in_us';
                        $data['match_title'] = 'Eligible to work in the US';
                        $data['update_key'] = 'eligible_work_in_us';
                        $data['type'] = 'checkbox';
                        $data['worker_title'] = 'Does Congress allow you to work here?';
                        $data['job_title'] = 'Eligible to work in the US';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['urgency'])?$job['urgency']:"";
                        $data['match'] = !empty($job_data['worker_urgency'])?true:false;
                        $data['worker'] = isset($job_data['worker_urgency'])?$job_data['worker_urgency']:"";
                        $data['name'] = 'worker_urgency';
                        $data['match_title'] = 'Urgency';
                        $data['update_key'] = 'worker_urgency';
                        $data['type'] = 'input';
                        $data['worker_title'] = "How quickly can you be ready to submit?";
                        $data['job'] = isset($job['urgency'])?$job['urgency']:"";
                        if(isset($data['job']) && $data['job'] == '1'){ $data['job'] = 'Auto Offer'; }else{
                            $data['job'] = 'Urgency';
                        }
                        // $data['job_title'] = $data['job'];
                        $data['job_title'] = !empty($job['urgency'])?$job['urgency']:"Urgency";
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['position_available'])?$job['position_available']:"";
                        $data['match'] = !empty($job_data["available_position"])?true:false;
                        $data['worker'] = isset($job_data["available_position"])?$job_data["available_position"]:"";
                        $data['name'] = 'available_position';
                        $data['match_title'] = '# of Positions Available';
                        $data['update_key'] = 'available_position';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'You have applied to # jobs?';
                        $data['job_title'] = !empty($data['job'])?$is_vacancy.' of '.$data['job']:'# of Positions Available';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['msp'])?$job['msp']:"";
                        $data['match'] = !empty($job_data['MSP'])?true:false;
                        $data['worker'] = isset($job_data['MSP'])?$job_data['MSP']:"";
                        $data['name'] = 'MSP';
                        $data['match_title'] = 'MSP';
                        $data['update_key'] = 'MSP';
                        $data['worker_title'] = 'Any MSPs you prefer to avoid?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'MSP';
                        $data['type'] = 'dropdown';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['vms'])?$job['vms']:"";
                        $data['match'] = !empty($job_data['VMS'])?true:false;
                        $data['worker'] = isset($job_data['VMS'])?$job_data['VMS']:"";
                        $data['name'] = 'VMS';
                        $data['match_title'] = 'VMS';
                        $data['update_key'] = 'VMS';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = "Who's your favorite VMS?";
                        $data['job_title'] = !empty($data['job'])?$data['job']:'VMS';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['submission_of_vms'])?$job['submission_of_vms']:"";
                        $data['match'] = !empty($job_data['submission_VMS'])?true:false;
                        $data['worker'] = isset($job_data['submission_VMS'])?$job_data['submission_VMS']:"";
                        $data['name'] = 'submission_VMS';
                        $data['match_title'] = '# of Submissions in VMS';
                        $data['update_key'] = 'submission_VMS';
                        $data['type'] = 'input';
                        $data['worker_title'] = '# of Submissions in VMS';
                        $data['job_title'] = (isset($data['job']) && !empty($data['job']))?$data['job']:'# of Submissions in VMS';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['block_scheduling'])?$job['block_scheduling']:"";
                        $data['match'] = !empty($job_data['worker_block_scheduling'])?true:false;
                        $data['worker'] = isset($job_data['worker_block_scheduling'])?$job_data['worker_block_scheduling']:"";
                        $data['name'] = 'Block_scheduling';
                        $data['match_title'] = 'Block Scheduling';
                        $data['update_key'] = 'block_scheduling';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Do you want block scheduling?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Block Scheduling';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job_data['worker_float_requirement'] == 'Yes'){ $val = true; }else{ $val = false; }
                        $data['job'] = isset($job['float_requirement'])?$job['float_requirement']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_float_requirement'])?$job_data['worker_float_requirement']:"";
                        $data['name'] = 'Float Requirement';
                        $data['match_title'] = 'Float requirements';
                        $data['update_key'] = 'float_requirement';
                        $data['type'] = 'checkbox';
                        $data['worker_title'] = 'Are you willing float to?';
                        $data['job_title'] = !empty($job['float_requirement'])?$job['float_requirement']:'Float requirements';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['facility_shift_cancelation_policy'])?$job['facility_shift_cancelation_policy']:"";
                        $data['match'] = !empty($job_data['worker_facility_shift_cancelation_policy'])?true:false;
                        $data['worker'] = isset($job_data['worker_facility_shift_cancelation_policy'])?$job_data['worker_facility_shift_cancelation_policy']:"";
                        $data['name'] = 'Facility Shift Cancelation Policy';
                        $data['match_title'] = 'Facility Shift Cancellation Policy';
                        $data['update_key'] = 'facility_shift_cancelation_policy';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = 'What terms do you prefer?';
                        $data['job_title'] = !empty($job['facility_shift_cancelation_policy'])?$job['facility_shift_cancelation_policy']:'Facility Shift Cancellation Policy';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['contract_termination_policy'])?$job['contract_termination_policy']:"";
                        $data['match'] = !empty($job_data['worker_contract_termination_policy'])?true:false;
                        $data['worker'] = isset($job_data['worker_contract_termination_policy'])?$job_data['worker_contract_termination_policy']:"";
                        $data['name'] = 'Contract Terminology';
                        $data['match_title'] = 'Contract Termination Policy';
                        $data['update_key'] = 'contract_termination_policy';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = 'What terms do you prefer?';
                        $data['job_title'] = !empty($job['contract_termination_policy'])?$job['contract_termination_policy']:'Contract Termination Policy';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if(isset($job_data['distance_from_your_home']) && ($job_data['distance_from_your_home'] != 0) ){
                            $data['worker'] = $job_data['distance_from_your_home'];
                        }else{
                            $data['worker'] = "";
                        }
                        if($job['traveler_distance_from_facility'] == $job_data['distance_from_your_home']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['traveler_distance_from_facility'])?$job['traveler_distance_from_facility']:"";
                        $data['match'] = $val;
                        // $data['worker'] = isset($job_data['distance_from_your_home'])?$job_data['distance_from_your_home']:"";
                        $data['name'] = 'distance from facility';
                        $data['match_title'] = 'Traveler Distance From Facility';
                        $data['update_key'] = 'distance_from_your_home';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Where does the IRS think you live?';
                        $data['job_title'] = !empty($data['job'])?$data['job'].' miles':'Traveler Distance From Facility';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['facility'])?$job['facility']:"";
                        $data['match'] = !empty($job_data['facilities_you_like_to_work_at'])?true:false;
                        $data['worker'] = isset($job_data['facilities_you_like_to_work_at'])?$job_data['facilities_you_like_to_work_at']:"";
                        $data['name'] = 'Facility available upon request';
                        $data['match_title'] = 'Facility';
                        $data['update_key'] = 'facilities_you_like_to_work_at';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = 'What facilities have you worked at?';
                        $data['job_title'] = !empty($job['facility'])?$job['facility']:'Facility';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['facilitys_parent_system'])?$job['facilitys_parent_system']:"";
                        $data['match'] = !empty($job_data['worker_facility_parent_system'])?true:false;
                        $data['worker'] = isset($job_data['worker_facility_parent_system'])?$job_data['worker_facility_parent_system']:"";
                        $data['name'] = 'facility parent system';
                        $data['match_title'] = "Facility's Parent System";
                        $data['update_key'] = 'worker_facility_parent_system';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'What facilities would you like to work at?';
                        $data['job_title'] = !empty($job['facilitys_parent_system'])?$job['facilitys_parent_system']:"Facility's Parent System";
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if(isset($job_data['avg_rating_by_facilities']) && ($job_data['avg_rating_by_facilities'] != 0) ){
                            $data['worker'] = $job_data['avg_rating_by_facilities'];
                        }else{
                            $data['worker'] = "";
                        }
                        $data['job'] = isset($job['facility_average_rating'])?$job['facility_average_rating']:"";
                        $data['match'] = !empty($job_data['avg_rating_by_facilities'])?true:false;
                        // $data['worker'] = isset($job_data['avg_rating_by_facilities'])?$job_data['avg_rating_by_facilities']:"";
                        $data['name'] = 'facility average rating';
                        $data['match_title'] = 'Facility Average Rating';
                        $data['update_key'] = 'avg_rating_by_facilities';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Your average rating by your facilities';
                        $data['job_title'] = !empty($job['facility_average_rating'])?$job['facility_average_rating']:'Facility Average Rating';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if(isset($job_data['worker_avg_rating_by_recruiters']) && ($job_data['worker_avg_rating_by_recruiters'] != 0) ){
                            $data['worker'] = $job_data['worker_avg_rating_by_recruiters'];
                        }else{
                            $data['worker'] = "";
                        }
                        $data['job'] = isset($job['recruiter_average_rating'])?$job['recruiter_average_rating']:"";
                        $data['match'] = !empty($job_data['worker_avg_rating_by_recruiters'])?true:false;
                        // $data['worker'] = isset($job_data['worker_avg_rating_by_recruiters'])?$job_data['worker_avg_rating_by_recruiters']:"";
                        $data['name'] = 'recruiter average rating';
                        $data['match_title'] = 'Recruiter Average Rating';
                        $data['update_key'] = 'worker_avg_rating_by_recruiters';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Your average rating by your recruiters';
                        $data['job_title'] = !empty($job['recruiter_average_rating'])?$job['recruiter_average_rating']:'Recruiter Average Rating';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if(isset($job_data['worker_avg_rating_by_employers']) && ($job_data['worker_avg_rating_by_employers'] != 0) ){
                            $data['worker'] = $job_data['worker_avg_rating_by_employers'];
                        }else{
                            $data['worker'] = "";
                        }
                        $data['job'] = isset($job['employer_average_rating'])?$job['employer_average_rating']:"";
                        $data['match'] = !empty($job_data['worker_avg_rating_by_employers'])?true:false;
                        // $data['worker'] = isset($job_data['worker_avg_rating_by_employers'])?$job_data['worker_avg_rating_by_employers']:"";
                        $data['name'] = 'employer average rating';
                        $data['match_title'] = 'Employer Average Rating';
                        $data['update_key'] = 'worker_avg_rating_by_employers';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Your average rating by your employers';
                        $data['job_title'] = !empty($job['employer_average_rating'])?$job['employer_average_rating']:'Employer Average Rating';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['clinical_setting'] == $job_data['clinical_setting_you_prefer']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['clinical_setting'])?$job['clinical_setting']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['clinical_setting_you_prefer'])?$job_data['clinical_setting_you_prefer']:"";
                        $data['name'] = 'Clinical Setting';
                        $data['match_title'] = 'Clinical Setting';
                        $data['update_key'] = 'clinical_setting_you_prefer';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = 'What setting do you prefer?';
                        $data['job_title'] = (isset($data['job']) && !empty($data['job']))?$data['job']:' Clinical Setting';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['Patient_ratio'])?$job['Patient_ratio']:"";
                        $data['match'] = !empty($job_data['worker_patient_ratio'])?true:false;
                        $data['worker'] = isset($job_data['worker_patient_ratio'])?$job_data['worker_patient_ratio']:"";
                        $data['name'] = 'patient ratio';
                        $data['match_title'] = 'Patient Ratio';
                        $data['update_key'] = 'worker_patient_ratio';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'How many patients can you handle?';
                        $data['job_title'] = !empty($job['Patient_ratio'])?$job['Patient_ratio']:'Patient ratio';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['emr'])?$job['emr']:"";
                        $data['match'] = !empty($job_data['worker_emr'])?true:false;
                        $data['worker'] = isset($job_data['worker_emr'])?$job_data['worker_emr']:"";
                        $data['name'] = 'EMR';
                        $data['match_title'] = 'EMR';
                        $data['update_key'] = 'worker_emr';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = 'What EMRs have you used?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'EMR';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['Unit'])?$job['Unit']:"";
                        $data['match'] = !empty($job_data['worker_unit'])?true:false;
                        $data['worker'] = isset($job_data['worker_unit'])?$job_data['worker_unit']:"";
                        $data['name'] = 'Unit';
                        $data['match_title'] = 'Unit';
                        $data['update_key'] = 'worker_unit';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Fav Unit?';
                        $data['job_title'] = !empty($job['Unit'])?$job['Unit']:'Unit';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['Department'])?$job['Department']:"";
                        $data['match'] = !empty($job_data['worker_department'])?true:false;
                        $data['worker'] = isset($job_data['worker_department'])?$job_data['worker_department']:"";
                        $data['name'] = 'Department';
                        $data['match_title'] = 'Department';
                        $data['update_key'] = 'worker_department';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Fav Department?';
                        $data['job_title'] = !empty($job['Department'])?$job['Department']:'Department';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['Bed_Size'])?$job['Bed_Size']:"";
                        $data['match'] = !empty($job_data['worker_bed_size'])?true:false;
                        $data['worker'] = isset($job_data['worker_bed_size'])?$job_data['worker_bed_size']:"";
                        $data['name'] = 'Bed Size';
                        $data['match_title'] = 'Bed Size';
                        $data['update_key'] = 'worker_bed_size';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'king or california king ';
                        $data['job_title'] = !empty($job['Bed_Size'])?$job['Bed_Size']:'Bed Size';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['Trauma_Level'])?$job['Trauma_Level']:"";
                        $data['match'] = !empty($job_data['worker_trauma_level'])?true:false;
                        $data['worker'] = isset($job_data['worker_trauma_level'])?$job_data['worker_trauma_level']:"";
                        $data['name'] = 'Trauma Level';
                        $data['match_title'] = 'Trauma Level';
                        $data['update_key'] = 'worker_trauma_level';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Ideal trauma level?';
                        $data['job_title'] = !empty($job['Trauma_Level'])?$job['Trauma_Level']:'Trauma Level';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['scrub_color'] == $job_data['worker_scrub_color']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['scrub_color'])?$job['scrub_color']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_scrub_color'])?$job_data['worker_scrub_color']:"";
                        $data['name'] = 'Scrub color';
                        $data['match_title'] = 'Scrub Color';
                        $data['update_key'] = 'worker_scrub_color';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Fav Scrub Brand?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Scrub Color';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['job_state'] == $job_data['worker_facility_state_code']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['job_state'])?$job['job_state']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_facility_state_code'])?$job_data['worker_facility_state_code']:"";
                        $data['name'] = 'Facility state';
                        $data['match_title'] = 'Facility State Code';
                        $data['update_key'] = 'worker_facility_state_code';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = "States you'd like to work?";
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Facility State Code';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['job_city'] == $job_data['worker_facility_city']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['job_city'])?$job['job_city']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_facility_city'])?$job_data['worker_facility_city']:"";
                        $data['name'] = 'Facility City';
                        $data['match_title'] = 'Facility City';
                        $data['update_key'] = 'worker_facility_city';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = "Cities you'd like to work?";
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Facility City';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = "InterviewDates";
                        $data['match'] = !empty($job_data['worker_interview_dates'])?true:false;
                        $data['worker'] = isset($job_data['worker_interview_dates'])?$job_data['worker_interview_dates']:"";
                        $data['name'] = 'Interview dates';
                        $data['match_title'] = 'InterviewDates';
                        $data['update_key'] = 'worker_interview_dates';
                        $data['type'] = 'Interview dates';
                        $data['worker_title'] = "Any days you're not available?";
                        $data['job_title'] = 'InterviewDates';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if(isset($job['as_soon_as']) && ($job['as_soon_as'] == '1')){
                            $data['job'] = "As Soon As";
                        }else{
                            $data['job'] = isset($job['start_date'])?$job['start_date']:"";
                        }
                        if(isset($job_data['worker_as_soon_as_posible']) && ($job_data['worker_as_soon_as_posible'] == '1')){
                            $data['worker'] = "As Soon As";
                        }else{
                            $data['worker'] = isset($job_data['worker_start_date'])?$job_data['worker_start_date']:"";
                        }
                        if($data['worker'] == $data['job']){ $data['match'] = true;}else{ $data['match'] = false; }
                        $data['name'] = 'As Soon As';
                        $data['match_title'] = 'Start Date';
                        $data['update_key'] = 'worker_as_soon_as_posible';
                        $data['type'] = 'checkbox';
                        $data['worker_title'] = 'When can you start?';
                        $data['job_title'] = !empty($job['as_soon_as'])?$job['as_soon_as']:'Start Date';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['rto'] == $job_data['worker_rto']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['rto'])?$job['rto']:"";
                        $data['match'] = $val;
                        $data['worker'] = "";
                        $data['name'] = 'RTO';
                        $data['match_title'] = 'RTO';
                        $data['update_key'] = 'clinical_setting_you_prefer';
                        $data['type'] = 'input';
                        $data['worker_title'] = !empty($data['worker'])?$data['worker']:'Any time off?';
                        $data['job_title'] = (isset($data['job']) && !empty($data['job']))?$data['job']:'RTO';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['preferred_shift'] == $job_data['worker_shift_time_of_day']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['preferred_shift'])?$job['preferred_shift']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_shift_time_of_day'])?$job_data['worker_shift_time_of_day']:"";
                        $data['name'] = 'Shift';
                        $data['match_title'] = 'Shift Time of Day';
                        $data['update_key'] = 'worker_shift_time_of_day';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = 'Fav Shift?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Shift Time of Day';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;


                        if($job['hours_per_week'] == $job_data['worker_hours_per_week']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['hours_per_week'])?$job['hours_per_week']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_hours_per_week'])?$job_data['worker_hours_per_week']:"";
                        $data['name'] = 'Hours/week';
                        $data['match_title'] = 'Hours/Week';
                        $data['update_key'] = 'worker_hours_per_week';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Ideal Hours per week?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Hours/Week';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['guaranteed_hours'] == $job_data['worker_guaranteed_hours']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['guaranteed_hours'])?$job['guaranteed_hours']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_guaranteed_hours'])?$job_data['worker_guaranteed_hours']:"";
                        $data['name'] = 'Guaranteed Hours';
                        $data['match_title'] = 'Guaranteed Hours';
                        $data['update_key'] = 'worker_guaranteed_hours';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Open to jobs with no guaranteed hours?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Guaranteed Hours';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['hours_shift'] == $job_data['worker_hours_shift']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['hours_shift'])?$job['hours_shift']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_hours_shift'])?$job_data['worker_hours_shift']:"";
                        $data['name'] = 'Shift Hours';
                        $data['match_title'] = 'Hours/Shift';
                        $data['update_key'] = 'worker_hours_shift';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Prefered hours per shift?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Hours/Shift';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['preferred_assignment_duration'] == $job_data['worker_weeks_assignment']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['preferred_assignment_duration'])?$job['preferred_assignment_duration']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_weeks_assignment'])?$job_data['worker_weeks_assignment']:"";
                        $data['name'] = 'Assignment in weeks';
                        $data['match_title'] = 'Weeks/Assignment';
                        $data['update_key'] = 'worker_weeks_assignment';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'How many weeks?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Weeks/Assignment';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['weeks_shift'] == $job_data['worker_shifts_week']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['weeks_shift'])?$job['weeks_shift']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_shifts_week'])?$job_data['worker_shifts_week']:"";
                        $data['name'] = 'Shift Week';
                        $data['match_title'] = 'Shifts/Week';
                        $data['update_key'] = 'worker_shifts_week';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Ideal shifts per week?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Shifts/Week';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['referral_bonus'] == $job_data['worker_referral_bonus']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['referral_bonus'])?$job['referral_bonus']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_referral_bonus'])?$job_data['worker_referral_bonus']:"";
                        $data['name'] = 'Refferel Bonus';
                        $data['match_title'] = 'Referral Bonus';
                        $data['update_key'] = 'worker_referral_bonus';
                        $data['type'] = 'input';
                        $data['worker_title'] = '# of people you have referred';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Referral Bonus';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if(($job['sign_on_bonus'] == $job_data['worker_sign_on_bonus']) && (!empty($job_data['worker_sign_on_bonus']))){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['sign_on_bonus'])?$job['sign_on_bonus']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_sign_on_bonus'])?$job_data['worker_sign_on_bonus']:"";
                        $data['name'] = 'Sign On Bonus';
                        $data['match_title'] = 'Sign-On Bonus';
                        $data['update_key'] = 'worker_sign_on_bonus';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'What kind of bonus do you expect?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Sign-On Bonus';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['completion_bonus'] == $job_data['worker_completion_bonus']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['completion_bonus'])?$job['completion_bonus']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_completion_bonus'])?$job_data['worker_completion_bonus']:"";
                        $data['name'] = 'Completion Bonus';
                        $data['match_title'] = 'Completion Bonus';
                        $data['update_key'] = 'worker_completion_bonus';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'What kind of bonus do you deserve?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Completion Bonus';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['extension_bonus'] == $job_data['worker_extension_bonus']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['extension_bonus'])?$job['extension_bonus']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_extension_bonus'])?$job_data['worker_extension_bonus']:"";
                        $data['name'] = 'extension bonus';
                        $data['match_title'] = 'Extension Bonus';
                        $data['update_key'] = 'worker_extension_bonus';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'What are you comparing this too?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Extension Bonus';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['other_bonus'])?$job['other_bonus']:"";
                        $data['match'] = !empty($job_data['worker_other_bonus'])?true:false;
                        $data['worker'] = isset($job_data['worker_other_bonus'])?$job_data['worker_other_bonus']:"";
                        $data['name'] = 'Other Bonus';
                        $data['match_title'] = 'Other Bonus';
                        $data['update_key'] = 'worker_other_bonus';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Other bonuses you want?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Other Bonus';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['four_zero_one_k'])?$job['four_zero_one_k']:"";
                        $data['match'] = !empty($job_data['how_much_k'])?true:false;
                        $data['worker'] = isset($job_data['how_much_k'])?$job_data['how_much_k']:"";
                        $data['name'] = '401k';
                        $data['match_title'] = '401k';
                        $data['update_key'] = 'how_much_k';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = 'How much do you want this?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'401K';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['health_insaurance'])?$job['health_insaurance']:"";
                        $data['match'] = !empty($job_data['worker_health_insurance'])?true:false;
                        $data['worker'] = isset($job_data['worker_health_insurance'])?$job_data['worker_health_insurance']:"";
                        $data['name'] = 'Health Insaurance';
                        $data['match_title'] = 'Health Insurance';
                        $data['update_key'] = 'worker_health_insurance';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = 'How much do you want this?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Health Insurance';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['dental'])?$job['dental']:"";
                        $data['match'] = !empty($job_data['worker_dental'])?true:false;
                        $data['worker'] = isset($job_data['worker_dental'])?$job_data['worker_dental']:"";
                        $data['name'] = 'Dental';
                        $data['match_title'] = 'Dental';
                        $data['update_key'] = 'worker_dental';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = 'How much do you want this?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Dental';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['vision'])?$job['vision']:"";
                        $data['match'] = !empty($job_data['worker_vision'])?true:false;
                        $data['worker'] = isset($job_data['worker_vision'])?$job_data['worker_vision']:"";
                        $data['name'] = 'Vision';
                        $data['match_title'] = 'Vision';
                        $data['update_key'] = 'worker_vision';
                        $data['type'] = 'dropdown';
                        $data['worker_title'] = 'How much do you want this?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Vision';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['actual_hourly_rate'] == $job_data['worker_actual_hourly_rate']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['actual_hourly_rate'])?$job['actual_hourly_rate']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_actual_hourly_rate'])?$job_data['worker_actual_hourly_rate']:"";
                        $data['name'] = 'Actually Rate';
                        $data['match_title'] = 'Actual Hourly rate';
                        $data['update_key'] = 'worker_actual_hourly_rate';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'What range is fair?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Actual Hourly rate';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['feels_like_per_hour'] == $job_data['worker_feels_like_hour']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['feels_like_per_hour'])?$job['feels_like_per_hour']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_feels_like_hour'])?$job_data['worker_feels_like_hour']:"";
                        $data['name'] = 'feels/$hr';
                        $data['match_title'] = 'Feels Like $/hr';
                        $data['update_key'] = 'worker_feels_like_hour';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Does this seem fair based on the market?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Feels Like $/hr';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['overtime'])?$job['overtime']:"";
                        $data['match'] = !empty($job_data['worker_overtime'])?true:false;
                        $data['worker'] = isset($job_data['worker_overtime'])?$job_data['worker_overtime']:"";
                        $data['name'] = 'Overtime';
                        $data['match_title'] = 'Overtime';
                        $data['update_key'] = 'worker_overtime';
                        $data['type'] = 'checkbox';
                        $data['worker_title'] = 'Would you work more overtime for a higher OT rate?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Overtime';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['holiday'])?$job['holiday']:"";
                        $data['match'] = !empty($job_data['worker_holiday'])?true:false;
                        $data['worker'] = isset($job_data['worker_holiday'])?$job_data['worker_holiday']:"";
                        $data['name'] = 'Holiday';
                        $data['match_title'] = 'Holiday';
                        $data['update_key'] = 'worker_holiday';
                        $data['type'] = 'date';
                        $data['worker_title'] = 'Any holidays you refuse to work?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Holiday';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['on_call'])?$job['on_call']:"";
                        $data['match'] = !empty($job_data['worker_holiday'])?true:false;
                        $data['worker'] = isset($job_data['worker_on_call'])?$job_data['worker_on_call']:"";
                        $data['name'] = 'On call';
                        $data['match_title'] = 'On call';
                        $data['update_key'] = 'worker_on_call';
                        $data['type'] = 'checkbox';
                        $data['worker_title'] = 'Will you do call?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'On Call';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['call_back'])?$job['call_back']:"";
                        $data['match'] = !empty($job_data['worker_call_back'])?true:false;
                        $data['worker'] = isset($job_data['worker_call_back'])?$job_data['worker_call_back']:"";
                        $data['name'] = 'Call Back';
                        $data['match_title'] = 'Call Back';
                        $data['update_key'] = 'worker_call_back';
                        $data['type'] = 'checkbox';
                        $data['worker_title'] = 'Is this rate reasonable?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Call Back';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = isset($job['orientation_rate'])?$job['orientation_rate']:"";
                        $data['match'] = !empty($job_data['worker_orientation_rate'])?true:false;
                        $data['worker'] = isset($job_data['worker_orientation_rate'])?$job_data['worker_orientation_rate']:"";
                        $data['name'] = 'Orientation Rate';
                        $data['match_title'] = 'Orientation Rate';
                        $data['update_key'] = 'worker_orientation_rate';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Is this rate reasonable?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Orientation Rate';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['weekly_taxable_amount'] == $job_data['worker_weekly_taxable_amount']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['weekly_taxable_amount'])?$job['weekly_taxable_amount']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_weekly_taxable_amount'])?$job_data['worker_weekly_taxable_amount']:"";
                        $data['name'] = 'Weekly Taxable amount';
                        $data['match_title'] = 'Weekly Taxable Amount';
                        $data['update_key'] = 'worker_weekly_taxable_amount';
                        $data['type'] = 'input';
                        $data['worker_title'] = '';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Weekly Taxable amount';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['weekly_non_taxable_amount'] == $job_data['worker_weekly_non_taxable_amount']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['weekly_non_taxable_amount'])?$job['weekly_non_taxable_amount']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_weekly_non_taxable_amount'])?$job_data['worker_weekly_non_taxable_amount']:"";
                        $data['name'] = 'Weekly Non Taxable Amount';
                        $data['match_title'] = 'Weekly Non Taxable Amount';
                        $data['update_key'] = 'worker_weekly_non_taxable_amount';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'Are you going to duplicate expenses?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Weekly non-taxable amount';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['employer_weekly_amount'] == $job_data['worker_employer_weekly_amount']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['employer_weekly_amount'])?$job['employer_weekly_amount']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_employer_weekly_amount'])?$job_data['worker_employer_weekly_amount']:"";
                        $data['name'] = 'Employer Weekly Amount';
                        $data['match_title'] = 'Employer Weekly Amount';
                        $data['update_key'] = 'worker_employer_weekly_amount';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'What range is reasonable?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Employer Weekly Amount';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['goodwork_weekly_amount'] == $job_data['worker_goodwork_weekly_amount']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['goodwork_weekly_amount'])?$job['goodwork_weekly_amount']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_goodwork_weekly_amount'])?$job_data['worker_goodwork_weekly_amount']:"";
                        $data['name'] = 'Goodwork Weekly Amount';
                        $data['match_title'] = 'Goodwork Weekly Amount';
                        $data['update_key'] = 'worker_goodwork_weekly_amount';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'You only have (count down time) left before your rate drops from 5% to 2%';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Goodwork Weekly Amount';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['total_employer_amount'] == $job_data['worker_total_employer_amount']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['total_employer_amount'])?$job['total_employer_amount']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_total_employer_amount'])?$job_data['worker_total_employer_amount']:"";
                        $data['name'] = 'Total Employer Amount';
                        $data['match_title'] = 'Total Employer Amount';
                        $data['update_key'] = 'worker_total_employer_amount';
                        $data['type'] = 'input';
                        $data['worker_title'] = '';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Total Employer Amount';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['total_goodwork_amount'] == $job_data['worker_total_goodwork_amount']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['total_goodwork_amount'])?$job['total_goodwork_amount']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_total_goodwork_amount'])?$job_data['worker_total_goodwork_amount']:"";
                        $data['name'] = 'Total Goodwork Amount';
                        $data['match_title'] = 'Total Goodwork Amount';
                        $data['update_key'] = 'worker_total_goodwork_amount';
                        $data['type'] = 'input';
                        $data['worker_title'] = 'How would you spend those extra funds?';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Total Goodwork Amount';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        if($job['total_contract_amount'] == $job_data['worker_total_contract_amount']){ $val = true; }else{$val = false;}
                        $data['job'] = isset($job['total_contract_amount'])?$job['total_contract_amount']:"";
                        $data['match'] = $val;
                        $data['worker'] = isset($job_data['worker_total_contract_amount'])?$job_data['worker_total_contract_amount']:"";
                        $data['name'] = 'Total Contract Amount';
                        $data['match_title'] = 'Total Contract Amount';
                        $data['update_key'] = 'worker_total_contract_amount';
                        $data['type'] = 'input';
                        $data['worker_title'] = '';
                        $data['job_title'] = !empty($data['job'])?$data['job']:'Total Contract Amount';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $data['job'] = "Goodwork Number";
                        $data['match'] = !empty($job_data['worker_goodwork_number'])?true:false;
                        $data['worker'] = isset($job_data['worker_goodwork_number'])?$job_data['worker_goodwork_number']:"";
                        $data['name'] = 'goodwork number';
                        $data['match_title'] = 'Goodwork Number';
                        $data['update_key'] = 'worker_goodwork_number';
                        $data['type'] = 'input';
                        $data['worker_title'] = '';
                        $data['job_title'] = 'Goodwork Number';
                        $ask_worker = DB::table('ask_worker')->where(['update_key' => $data['update_key'], 'worker_id' => $result['worker_id']])->first();
                        $data['isAlreadyAsk'] = !empty($ask_worker)?true:false;
                        $worker_info[] = $data;

                        $result['worker_info'] = $worker_info;



                        $this->check = "1";
                        $this->message = "Matching details listed successfully";
                        // $this->return_data = $data;
                        $this->return_data = $result;

                    }else{
                        $this->check = "1";
                        $this->message = "User Not Found";
                    }

                }else{
                    $this->check = "1";
                    $this->message = "Worker Not Found";
                }
            }else{
                $this->check = "1";
                $this->message = "Job Not Found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    // M.ELH Note : the infromation retrived for a (worker,jobs,recruiter) not just a recruiter
    public function updateRecruiterInformation(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
            'worker_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker_info  = Nurse::where('id', $request->worker_id);
            $vaccination = [];
            if ($worker_info->count() > 0) {
                $worker = $worker_info->get()->first();

                $worker->specialty = isset($request->specialty)?$request->specialty:$worker->specialty;
                $worker->nursing_license_state = isset($request->nursing_license_state)?$request->nursing_license_state:$worker->nursing_license_state;
                $worker->license_type = isset($request->license_type)?$request->license_type:$worker->license_type;
                $worker->nursing_license_number = isset($request->nursing_license_number)?$request->nursing_license_number:$worker->nursing_license_number;
                $worker->highest_nursing_degree = isset($request->highest_nursing_degree)?$request->highest_nursing_degree:$worker->highest_nursing_degree;
                $worker->city = isset($request->city)?$request->city:$worker->city;
                $worker->state = isset($request->state)?$request->state:$worker->state;
                $worker->country = isset($request->country)?$request->country:$worker->country;
                $worker->hourly_pay_rate = isset($request->hourly_pay_rate)?$request->hourly_pay_rate:$worker->hourly_pay_rate;
                $worker->experience = isset($request->experience)?$request->experience:$worker->experience;
                $worker->recent_experience = isset($request->recent_experience)?$request->recent_experience:$worker->recent_experience;
                $worker->active = isset($request->active)?$request->active:$worker->active;
                $worker->clinical_educator = isset($request->clinical_educator)?$request->clinical_educator:$worker->clinical_educator;
                $worker->credential_title = isset($request->credential_title)?$request->credential_title:$worker->credential_title;
                $worker->languages = isset($request->languages)?$request->languages:$worker->languages;
                $worker->worker_vaccination = isset($worker->worker_vaccination)?$worker->worker_vaccination:null;
                // if(isset($worker->worker_vaccination) && !empty($worker->worker_vaccination))
                // {
                    if(isset($worker->worker_vaccination) && !empty($worker->worker_vaccination)){
                        $resu = json_decode($worker->worker_vaccination);
                        foreach($resu as $vac){
                            $vaccination[] = $vac;
                        }
                    }else{
                        $vaccination[0] = null;
                        $vaccination[1] = null;
                    }

                    // upload covid
                    if ($request->hasFile('covid') && $request->file('covid') != null)
                    {
                        $dele = NurseAsset::where('nurse_id', $request->worker_id)->where('filter', 'covid')->forceDelete();

                        if(!empty($vaccination[0])){
                            if(\File::exists(public_path('images/nurses/vaccination/').$vaccination[0]))
                            {
                                \File::delete(public_path('images/nurses/vaccination/').$vaccination[0]);
                            }
                        }

                        $covid_name_full = $request->file('covid')->getClientOriginalName();
                        $covid_name = pathinfo($covid_name_full, PATHINFO_FILENAME);
                        $covid_ext = $request->file('covid')->getClientOriginalExtension();
                        $covid = $covid_name.'_'.time().'.'.$covid_ext;
                        $destinationPath = 'images/nurses/vaccination';
                        $request->file('covid')->move(public_path($destinationPath), $covid);

                        // write image name in worker table
                        // $worker->covid = $covid;
                        $covid_date = isset($request->covid_date)?$request->covid_date:'';
                        $covid_asset = NurseAsset::create([
                            'nurse_id' => $request->worker_id,
                            'using_date' => $covid_date,
                            'name' => $covid,
                            'filter' => 'covid'
                        ]);
                        $vaccination[0] = $covid;
                        if(isset($covid_asset)){
                            $update = NurseAsset::where(['id' => $covid_asset['id']])->update([
                                'using_date' => $covid_date
                            ]);
                        }
                    }else if(isset($request->covid_date) && !isset($request->covid)){
                        NurseAsset::where('nurse_id', $request->worker_id)->where('filter', 'covid')->update([
                            'using_date' => $request->covid_date,
                        ]);
                    }

                    // Upload flu
                    if ($request->hasFile('flu') && $request->file('flu') != null)
                    {
                        NurseAsset::where('nurse_id', $request->worker_id)->where('filter', 'flu')->forceDelete();
                        if(!empty($vaccination[1])){
                            // unlink(public_path('images/nurses/vaccination/').$vaccination[1]);
                            if(\File::exists(public_path('images/nurses/vaccination/').$vaccination[1]))
                            {
                                \File::delete(public_path('images/nurses/vaccination/').$vaccination[1]);
                            }
                        }

                        $flu_name_full = $request->file('flu')->getClientOriginalName();
                        $flu_name = pathinfo($flu_name_full, PATHINFO_FILENAME);
                        $flu_ext = $request->file('flu')->getClientOriginalExtension();
                        $flu = $flu_name.'_'.time().'.'.$flu_ext;
                        $destinationPath = 'images/nurses/vaccination';
                        $request->file('flu')->move(public_path($destinationPath), $flu);

                        // write image name in worker table
                        // $worker->flu = $flu;
                        $flu_date = isset($request->flu_date)?$request->flu_date:'';
                        $flu_asset = NurseAsset::create([
                            'nurse_id' => $request->worker_id,
                            'name' => $flu,
                            'filter' => 'flu',
                            'using_date' => $flu_date,
                        ]);
                        $vaccination[1] = $flu;
                        if(isset($flu_asset)){
                            $update = NurseAsset::where(['id' => $flu_asset['id']])->update([
                                'using_date' => $flu_date
                            ]);
                        }
                    }else if(isset($request->flu_date)){
                        NurseAsset::where('nurse_id', $request->worker_id)->where('filter', 'flu')->update([
                            'using_date' => $request->flu_date,
                        ]);
                    }
                    $worker->worker_vaccination = json_encode($vaccination);

                // }
                // Diploma
                if ($request->hasFile('diploma') && $request->file('diploma') != null)
                {
                    NurseAsset::where('nurse_id', $request->worker_id)->where('filter', 'diploma')->forceDelete();
                    if(!empty($worker->diploma)){
                        if(\File::exists(public_path('images/nurses/diploma/').$worker->diploma))
                        {
                            \File::delete(public_path('images/nurses/diploma/').$worker->diploma);
                        }
                    }

                    $diploma_name_full = $request->file('diploma')->getClientOriginalName();
                    $diploma_name = pathinfo($diploma_name_full, PATHINFO_FILENAME);
                    $diploma_ext = $request->file('diploma')->getClientOriginalExtension();
                    $diploma = $diploma_name.'_'.time().'.'.$diploma_ext;
                    $destinationPath = 'images/nurses/diploma';
                    $request->file('diploma')->move(public_path($destinationPath), $diploma);

                    // write image name in worker table
                    $worker->diploma = $diploma;
                    $diploma_asset = NurseAsset::create([
                        'nurse_id' => $request->worker_id,
                        'name' => $diploma,
                        'filter' => 'diploma'
                    ]);
                }

                // Driving License
                if ($request->hasFile('driving_license') && $request->file('driving_license') != null)
                {
                    NurseAsset::where('nurse_id', $request->worker_id)->where('filter', 'driving_license')->forceDelete();
                    if(!empty($worker->driving_license)){
                        if(\File::exists(public_path('images/nurses/driving_license/').$worker->driving_license))
                        {
                            \File::delete(public_path('images/nurses/driving_license/').$worker->driving_license);
                        }
                    }

                    $driving_license_name_full = $request->file('driving_license')->getClientOriginalName();
                    $driving_license_name = pathinfo($driving_license_name_full, PATHINFO_FILENAME);
                    $driving_license_ext = $request->file('driving_license')->getClientOriginalExtension();
                    $driving_license = $driving_license_name.'_'.time().'.'.$driving_license_ext;
                    $destinationPath = 'images/nurses/driving_license';
                    $request->file('driving_license')->move(public_path($destinationPath), $driving_license);

                    // write image name in worker table
                    $worker->driving_license = $driving_license;
                    $license_expiration_date = isset($request->license_expiration_date)?$request->license_expiration_date:'';
                    $driving_license_asset = NurseAsset::create([
                                                'nurse_id' => $request->worker_id,
                                                'using_date' => $license_expiration_date,
                                                'name' => $driving_license,
                                                'filter' => 'driving_license',
                                            ]);

                    if(isset($driving_license_asset)){
                        $update = NurseAsset::where(['id' => $driving_license_asset['id']])->update([
                            'using_date' => $license_expiration_date
                        ]);
                    }
                }
                $worker->license_expiry_date = isset($request->license_expiry_date)?$request->license_expiry_date:$worker->license_expiry_date;
                $worker->compact_license = isset($request->compact_license)?$request->compact_license:$worker->compact_license;
                $worker->license_issue_date = isset($request->license_issue_date)?$request->license_issue_date:$worker->license_issue_date;

                $worker->worker_ss_number = isset($request->worker_ss_number)?$request->worker_ss_number:$worker->worker_ss_number;
                $worker->worker_number_of_references = isset($request->worker_number_of_references)?$request->worker_number_of_references:$worker->worker_number_of_references;

                // BLS
                if ($request->hasFile('BLS') && $request->file('BLS') != null)
                {
                    NurseAsset::where('nurse_id', $request->worker_id)->where('filter', 'BLS')->forceDelete();

                    $bls_name_full = $request->file('BLS')->getClientOriginalName();
                    $bls_name = pathinfo($bls_name_full, PATHINFO_FILENAME);
                    $bls_ext = $request->file('BLS')->getClientOriginalExtension();
                    if(!empty($worker->BLS)){
                        if(\File::exists(public_path('images/nurses/certificate/').$worker->BLS))
                        {
                            \File::delete(public_path('images/nurses/certificate/').$worker->BLS);
                        }
                    }
                    $bls = $bls_name.'_'.time().'.'.$bls_ext;
                    $destinationPath = 'images/nurses/certificate';
                    $request->file('BLS')->move(public_path($destinationPath), $bls);

                    // write image name in worker table
                    $worker->BLS = $bls;
                    $diploma_asset = NurseAsset::create([
                        'nurse_id' => $request->worker_id,
                        'name' => $bls,
                        'filter' => 'BLS'
                    ]);
                }

                // ACLS
                if ($request->hasFile('ACLS') && $request->file('ACLS') != null)
                {
                    NurseAsset::where('nurse_id', $request->worker_id)->where('filter', 'ACLS')->forceDelete();
                    // unlink(public_path('images/nurses/certificate/').$worker->ACLS);

                    $acls_name_full = $request->file('ACLS')->getClientOriginalName();
                    $acls_name = pathinfo($acls_name_full, PATHINFO_FILENAME);
                    $acls_ext = $request->file('ACLS')->getClientOriginalExtension();
                    if(!empty($worker->ACLS)){
                        if(\File::exists(public_path('images/nurses/certificate/').$worker->ACLS))
                        {
                            \File::delete(public_path('images/nurses/certificate/').$worker->ACLS);
                        }
                    }
                    $acls = $acls_name.'_'.time().'.'.$acls_ext;
                    $destinationPath = 'images/nurses/certificate';
                    $request->file('ACLS')->move(public_path($destinationPath), $acls);

                    // write image name in worker table
                    $worker->ACLS = $acls;
                    $diploma_asset = NurseAsset::create([
                        'nurse_id' => $request->worker_id,
                        'name' => $acls,
                        'filter' => 'ACLS'
                    ]);
                }

                // PALS
                if ($request->hasFile('PALS') && $request->file('PALS') != null)
                {
                    NurseAsset::where('nurse_id', $request->worker_id)->where('filter', 'PALS')->forceDelete();
                    // unlink(public_path('images/nurses/certificate/').$worker->PALS);

                    $pals_name_full = $request->file('PALS')->getClientOriginalName();
                    $pals_name = pathinfo($pals_name_full, PATHINFO_FILENAME);
                    $pals_ext = $request->file('PALS')->getClientOriginalExtension();
                    if(!empty($worker->PALS)){
                        // unlink(public_path('images/nurses/certificate/').$worker->PALS);
                        if(\File::exists(public_path('images/nurses/certificate/').$worker->PALS))
                        {
                            \File::delete(public_path('images/nurses/certificate/').$worker->PALS);
                        }
                    }
                    $pals = $pals_name.'_'.time().'.'.$pals_ext;
                    $destinationPath = 'images/nurses/certificate';
                    $request->file('PALS')->move(public_path($destinationPath), $pals);

                    // write image name in worker table
                    $worker->PALS = $pals;
                    $diploma_asset = NurseAsset::create([
                        'nurse_id' => $request->worker_id,
                        'name' => $pals,
                        'filter' => 'PALS'
                    ]);
                }

                // OTHER
                if ($request->hasFile('other') && $request->file('other') != null)
                {
                    NurseAsset::where('nurse_id', $request->worker_id)->where('filter', 'other')->forceDelete();
                    // unlink(public_path('images/nurses/certificate/').$worker->other);

                    $other_name_full = $request->file('other')->getClientOriginalName();
                    $other_name = pathinfo($other_name_full, PATHINFO_FILENAME);
                    $other_ext = $request->file('other')->getClientOriginalExtension();
                    if(($other_ext != 'pdf')  && (isset($worker->other))){
                        // unlink(public_path('images/nurses/certificate/').$worker->other);
                        if(\File::exists(public_path('images/nurses/certificate/').$worker->other))
                        {
                            \File::delete(public_path('images/nurses/certificate/').$worker->other);
                        }
                    }
                    $other = $other_name.'_'.time().'.'.$other_ext;
                    $destinationPath = 'images/nurses/certificate';
                    $request->file('other')->move(public_path($destinationPath), $other);

                    // write image name in worker table
                    $worker->other = $other;
                    $worker->other_certificate_name = isset($request->other_certificate_name)?$request->other_certificate_name:$worker->other_certificate_name;
                    $diploma_asset = NurseAsset::create([
                        'nurse_id' => $request->worker_id,
                        'name' => $other,
                        'filter' => 'Other'
                    ]);
                }
                $worker->other_certificate_name = isset($request->other_certificate_name)?$request->other_certificate_name:$worker->other_certificate_name;
                $worker->skills_checklists = isset($request->skills_checklists)?$request->skills_checklists:$worker->skills_checklists;
                $worker->distance_from_your_home = isset($request->distance_from_your_home)?$request->distance_from_your_home:$worker->distance_from_your_home;
                $worker->eligible_work_in_us = isset($request->eligible_work_in_us)?$request->eligible_work_in_us:$worker->eligible_work_in_us;
                $worker->worked_at_facility_before = isset($request->worked_at_facility_before)?$request->worked_at_facility_before:$worker->worked_at_facility_before;
                $worker->facilities_you_like_to_work_at = isset($request->facilities_you_like_to_work_at)?$request->facilities_you_like_to_work_at:$worker->facilities_you_like_to_work_at;
                $worker->avg_rating_by_facilities = isset($request->avg_rating_by_facilities)?$request->avg_rating_by_facilities:$worker->avg_rating_by_facilities;
                $worker->worker_avg_rating_by_recruiters = isset($request->worker_avg_rating_by_recruiters)?$request->worker_avg_rating_by_recruiters:$worker->worker_avg_rating_by_recruiters;
                // rto means worker time off
                $worker->worker_avg_rating_by_employers = isset($request->worker_avg_rating_by_employers)?$request->worker_avg_rating_by_employers:$worker->worker_avg_rating_by_employers;
                $worker->clinical_setting_you_prefer = isset($request->clinical_setting_you_prefer)?$request->clinical_setting_you_prefer:$worker->clinical_setting_you_prefer;

                $worker->worker_patient_ratio = isset($request->worker_patient_ratio)?$request->worker_patient_ratio:$worker->worker_patient_ratio;
                $worker->worker_emr = isset($request->worker_emr)?$request->worker_emr:$worker->worker_emr;
                $worker->worker_unit = isset($request->worker_unit)?$request->worker_unit:$worker->worker_unit;
                $worker->worker_department = isset($request->worker_department)?$request->worker_department:$worker->worker_department;
                $worker->worker_bed_size = isset($request->worker_bed_size)?$request->worker_bed_size:$worker->worker_bed_size;
                $worker->worker_trauma_level = isset($request->worker_trauma_level)?$request->worker_trauma_level:$worker->worker_trauma_level;
                $worker->worker_scrub_color = isset($request->worker_scrub_color)?$request->worker_scrub_color:$worker->worker_scrub_color;
                $worker->worker_facility_city = isset($request->worker_facility_city)?$request->worker_facility_city:$worker->worker_facility_city;
                $worker->worker_facility_state_code = isset($request->worker_facility_state_code)?$request->worker_facility_state_code:$worker->worker_facility_state_code;
                $worker->worker_interview_dates = isset($request->worker_interview_dates)?$request->worker_interview_dates:$worker->worker_interview_dates;
                $worker->worker_start_date = isset($request->worker_start_date)?$request->worker_start_date:$worker->worker_start_date;
                $worker->worker_as_soon_as_posible = isset($request->worker_as_soon_as_posible)?$request->worker_as_soon_as_posible:$worker->worker_as_soon_as_posible;
                $worker->worker_shift_time_of_day = isset($request->worker_shift_time_of_day)?$request->worker_shift_time_of_day:$worker->worker_shift_time_of_day;
                $worker->worker_hours_per_week = isset($request->worker_hours_per_week)?$request->worker_hours_per_week:$worker->worker_hours_per_week;
                $worker->worker_guaranteed_hours = isset($request->worker_guaranteed_hours)?$request->worker_guaranteed_hours:$worker->worker_guaranteed_hours;
                $worker->worker_hours_shift = isset($request->worker_hours_shift)?$request->worker_hours_shift:$worker->worker_hours_shift;
                $worker->worker_weeks_assignment = isset($request->worker_weeks_assignment)?$request->worker_weeks_assignment:$worker->worker_weeks_assignment;
                $worker->worker_shifts_week = isset($request->worker_shifts_week)?$request->worker_shifts_week:$worker->worker_shifts_week;
                $worker->worker_people_you_have_reffered = isset($request->worker_people_you_have_reffered)?$request->worker_people_you_have_reffered:$worker->worker_people_you_have_reffered;
                $worker->worker_referral_bonus = isset($request->worker_referral_bonus)?$request->worker_referral_bonus:$worker->worker_referral_bonus;
                $worker->worker_sign_on_bonus = isset($request->worker_sign_on_bonus)?$request->worker_sign_on_bonus:$worker->worker_sign_on_bonus;
                $worker->worker_completion_bonus = isset($request->worker_completion_bonus)?$request->worker_completion_bonus:$worker->worker_completion_bonus;
                $worker->worker_extension_bonus = isset($request->worker_extension_bonus)?$request->worker_extension_bonus:$worker->worker_extension_bonus;
                $worker->worker_other_bonus = isset($request->worker_other_bonus)?$request->worker_other_bonus:$worker->worker_other_bonus;
                $worker->how_much_k = isset($request->how_much_k)?$request->how_much_k:$worker->how_much_k;
                $worker->worker_health_insurance = isset($request->worker_health_insurance)?$request->worker_health_insurance:$worker->worker_health_insurance;
                $worker->worker_dental = isset($request->worker_dental)?$request->worker_dental:$worker->worker_dental;
                $worker->worker_vision = isset($request->worker_vision)?$request->worker_vision:$worker->worker_vision;
                $worker->worker_actual_hourly_rate = isset($request->worker_actual_hourly_rate)?$request->worker_actual_hourly_rate:$worker->worker_actual_hourly_rate;
                $worker->worker_feels_like_hour = isset($request->worker_feels_like_hour)?$request->worker_feels_like_hour:$worker->worker_feels_like_hour;
                $worker->worker_overtime = isset($request->worker_overtime)?$request->worker_overtime:$worker->worker_overtime;
                $worker->worker_holiday = isset($request->worker_holiday)?$request->worker_holiday:$worker->worker_holiday;
                $worker->worker_on_call = isset($request->worker_on_call)?$request->worker_on_call:$worker->worker_on_call;
                $worker->worker_call_back = isset($request->worker_call_back)?$request->worker_call_back:$worker->worker_call_back;
                $worker->available_position = isset($request->available_position)?$request->available_position:$worker->available_position;
                $worker->float_requirement = isset($request->float_requirement)?$request->float_requirement:$worker->float_requirement;
                $worker->facility_shift_cancelation_policy = isset($request->facility_shift_cancelation_policy)?$request->facility_shift_cancelation_policy:$worker->facility_shift_cancelation_policy;
                $worker->contract_termination_policy = isset($request->contract_termination_policy)?$request->contract_termination_policy:$worker->contract_termination_policy;
                $worker->worker_facility_parent_system = isset($request->worker_facility_parent_system)?$request->worker_facility_parent_system:$worker->worker_facility_parent_system;
                $worker->worker_urgency = isset($request->worker_urgency)?$request->worker_urgency:$worker->worker_urgency;
                $worker->MSP = isset($request->MSP)?$request->MSP:$worker->MSP;
                $worker->VMS = isset($request->VMS)?$request->VMS:$worker->VMS;
                $worker->submission_VMS = isset($request->submission_VMS)?$request->submission_VMS:$worker->submission_VMS;
                $worker->block_scheduling = isset($request->block_scheduling)?$request->block_scheduling:$worker->block_scheduling;
                $worker->worker_orientation_rate = isset($request->worker_orientation_rate)?$request->worker_orientation_rate:$worker->worker_orientation_rate;
                $worker->worker_weekly_taxable_amount = isset($request->worker_weekly_taxable_amount)?$request->worker_weekly_taxable_amount:$worker->worker_weekly_taxable_amount;
                $worker->worker_weekly_non_taxable_amount = isset($request->worker_weekly_non_taxable_amount)?$request->worker_weekly_non_taxable_amount:$worker->worker_weekly_non_taxable_amount;
                $worker->worker_employer_weekly_amount = isset($request->worker_employer_weekly_amount)?$request->worker_employer_weekly_amount:$worker->worker_employer_weekly_amount;
                $worker->worker_goodwork_weekly_amount = isset($request->worker_goodwork_weekly_amount)?$request->worker_goodwork_weekly_amount:$worker->worker_goodwork_weekly_amount;
                $worker->worker_total_employer_amount = isset($request->worker_total_employer_amount)?$request->worker_total_employer_amount:$worker->worker_total_employer_amount;
                $worker->worker_total_goodwork_amount = isset($request->worker_total_goodwork_amount)?$request->worker_total_goodwork_amount:$worker->worker_total_goodwork_amount;
                $worker->worker_total_contract_amount = isset($request->worker_total_contract_amount)?$request->worker_total_contract_amount:$worker->worker_total_contract_amount;
                $worker->worker_goodwork_number = $worker->id;

                $record = $worker->save();

                $user_info = USER::where('id', $worker->user_id);
                if(isset($record)){
                    $this->check = "1";
                    $this->message = "Worker record Updated successfully";
                    $this->return_data = $worker;
                }else{
                    $this->check = "1";
                    $this->message = "Worker record Not uploaded";
                }

            }else{
                $this->check = "1";
                $this->message = "Worker Not Found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getFacilityList(Request $request)
    {
        $validation_array = [
            'api_key' => 'required',
            'recruiter_id' => 'required'
        ];
        $validator = \Validator::make($request->all(), $validation_array);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            // $record = Facility::where(['created_by' => $request->recruiter_id,'active' => '1'])->get();
            $record = Facility::where(['active' => '1'])->get();

            if(isset($record) && !empty($record)){
                $this->check =  1;
                $this->message = 'Facility records listed successfully';
            }else{
                $this->check =  0;
                $this->message = 'Facility records not listed successfully';

            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $record], 200);
    }

    public function getShift(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $keywords = Keyword::where('filter', 'PreferredShift')->get()->pluck('title', 'id');
            $data = [];
            foreach ($keywords as $key => $value) {
                $data[] = ['id' => $key, "name" => $value];
            }

            $this->check = "1";
            $this->message = "Setting Types has been listed successfully";
            $this->return_data = $data;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getShiftTimeOfDay()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'shift_time_of_day')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Shift Time Of Day List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

     // M.ELH Note : same previous note
    public function recruiterInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'worker_id' => 'required',
            'api_key' => 'required',
            'job_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_job = Job::where('id', $request->job_id)->first();
            if(isset($check_job) && $check_job['is_hidden'] != 1){
                $worker_info  = Nurse::where('id', $request->worker_id);

                if ($worker_info->count() > 0) {
                    $worker = $worker_info->get()->first();
                    $user_info = USER::where('id', $worker->user_id);
                    $offer_check = Offer::where(['nurse_id' => $request->worker_id, 'job_id' => $request->job_id])->first();
                    if(isset($offer_check)){
                        if($user_info->count() > 0){
                            $user = $user_info->get()->first();
                            $worker_name = $user->first_name.' '.$user->last_name;
                            $worker_id = $worker->id;
                            $worker_img = $user->image;
                            $whereCond = [
                                    'facilities.active' => true,
                                    'users.id' => $worker->user_id,
                                    'nurses.id' => $worker->id,
                                    'jobs.id' => $request->job_id
                                ];

                            $respond = Nurse::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.nurse_id=nurses.id) as workers_applied"), 'nurses.*', 'jobs.*', 'offers.job_id as job_id', 'offers.id as offer_id', 'facilities.name as facility_name', 'facilities.city as facility_city', 'facilities.state as facility_state', 'nurses.block_scheduling as worker_block_scheduling', 'nurses.float_requirement as worker_float_requirement', 'nurses.facility_shift_cancelation_policy as worker_facility_shift_cancelation_policy', 'nurses.contract_termination_policy as worker_contract_termination_policy', 'offers.start_date as posted_on', 'jobs.created_at as created_at', 'jobs.recruiter_id as recruiter_id')
                                            ->join('users','users.id', '=', 'nurses.user_id')
                                            ->leftJoin('offers','offers.nurse_id', '=', 'nurses.id')
                                            ->leftJoin('jobs', 'offers.job_id', '=', 'jobs.id')
                                            ->leftJoin('facilities','jobs.facility_id', '=', 'facilities.id')
                                            ->where($whereCond);
                            $job_data = $respond->groupBy('jobs.id')->first();


                            $job_data['worker_block_scheduling'] = $job_data['block_scheduling'];
                            $job_data['worker_float_requirement'] = $job_data['float_requirement'];
                            $job_data['worker_facility_shift_cancelation_policy'] = $job_data['facility_shift_cancelation_policy'];
                            $job_data['worker_contract_termination_policy'] = $job_data['contract_termination_policy'];
                            // print_r($job_data);
                            // die();
                            if(empty($job_data)){
                                $whereCond1 =  [
                                    'facilities.active' => true,
                                    'jobs.id' => $request->job_id,
                                ];
                                // $job_data = $worker;
                                $worker_jobs = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.nurse_id=nurses.id) as workers_applied"), 'jobs.*', 'offers.job_id as job_id', 'offers.id as offer_id', 'facilities.name as facility_name', 'facilities.city as facility_city', 'facilities.state as facility_state', 'offers.start_date as posted_on', 'jobs.created_at as created_at', 'jobs.recruiter_id as recruiter_id')
                                // ->leftJoin('jobs', 'offers.job_id', '=', 'jobs.id')
                                ->leftJoin('offers','offers.job_id', '=', 'jobs.id')
                                ->leftJoin('facilities','jobs.facility_id', '=', 'facilities.id')
                                ->where($whereCond1)->groupBy('jobs.id')->first();
                                $job_data['jobs_applied'] = $worker_jobs['workers_applied'];
                                $job_data['worker_contract_termination_policy'] = $worker_jobs['contract_termination_policy'];
                                $job_data['job_id'] = $worker_jobs['job_id'];
                                $job_data['facility_name'] = $worker_jobs['facility_name'];
                                $job_data['facility_city'] = $worker_jobs['facility_city'];
                                $job_data['facility_state'] = $worker_jobs['facility_state'];
                                // $job_data['posted_on'] = isset($published['start_date'])?'Posted on '.date('M j Y', strtotime($worker_jobs['posted_on'])):"";
                                $job_data['created_at'] = $worker_jobs['created_at'];
                            }

                            if(isset($job_data['recruiter_id']) && !empty($job_data['recruiter_id'])){
                                $recruiter_info = USER::where('id', $job_data['recruiter_id'])->get()->first();
                                $recruiter_name = $recruiter_info->first_name.' '.$recruiter_info->last_name;
                                $recruiter_id = $recruiter_info->id;
                            }else{
                                $recruiter_name = '';
                                $recruiter_id = '';
                            }
                            $worker_reference = NURSE::select('nurse_references.name','nurse_references.min_title_of_reference','nurse_references.recency_of_reference')
                            ->leftJoin('nurse_references','nurse_references.nurse_id', '=', 'nurses.id')
                            ->where('nurses.id', $worker->id)->get();

                            $job = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.*')->where('id', $request->job_id)->first();
                            $job_data['posted_on'] = $job_data['created_at'];
                            $worker_reference_name = '';
                            $worker_reference_title ='';
                            $worker_reference_recency_reference ='';

                            foreach($worker_reference as $val){
                                if(!empty($val['name'])){
                                    $worker_reference_name = $val['name'].','.$worker_reference_name;
                                }
                                if(!empty($val['min_title_of_reference'])){
                                    $worker_reference_title = $val['min_title_of_reference'].','.$worker_reference_title;
                                }
                                if(!empty($val['recency_of_reference'])){
                                    $worker_reference_recency_reference = $val['recency_of_reference'].','.$worker_reference_recency_reference;
                                }
                            }

                            // Jobs speciality with experience
                            $speciality = explode(',',$job['preferred_specialty']);
                            $experiences = explode(',',$job['preferred_experience']);
                            $exp = [];
                            $spe = [];
                            $specialities = [];
                            $i = 0;
                            foreach($speciality as $special){
                                $spe[] = $special;
                                $i++;
                            }
                            foreach($experiences as $experience){
                                $exp[] = $experience;
                            }

                            for($j=0; $j< $i; $j++){
                                $specialities[$j]['spe'] = $spe[$j];
                                $specialities[$j]['exp'] = $exp[$j];
                            }

                            // Worker speciality
                            $worker_speciality = explode(',',$worker->specialty);
                            $worker_experiences = explode(',',$worker->experience);
                            $worker_exp = [];
                            $worker_spe = [];
                            $worker_specialities = [];
                            $i = 0;
                            foreach($speciality as $special){
                                $worker_spe[] = $special;
                                $i++;
                            }
                            foreach($experiences as $experience){
                                $worker_exp[] = $experience;
                            }

                            for($j=0; $j< $i; $j++){
                                $worker_specialities[$j]['spe'] = $worker_spe[$j];
                                $worker_specialities[$j]['exp'] = $worker_exp[$j];
                            }

                            // Worker vaccination
                            $worker_vaccination = json_decode($job_data['worker_vaccination']);
                            $worker_certificate_name = json_decode($job_data['worker_certificate_name']);
                            $worker_certificate = json_decode($job_data['worker_certificate']);
                            $skills_checklists = explode(',', $job_data['skills_checklists']);
                            $i=0;
                            foreach($skills_checklists as $rec)
                            {
                                if(isset($rec) && !empty($rec)){
                                    $skills_checklists[$i] = url('public/images/nurses/skill/'.$rec);
                                    $i++;
                                }

                            }
                            $vacc_image = NurseAsset::where(['filter' => 'vaccination', 'nurse_id' => $worker->id])->get();
                            $cert_image = NurseAsset::where(['filter' => 'certificate', 'nurse_id' => $worker->id])->get();
                            $result = [];

                            $result['jobs_applied'] = isset($job_data['workers_applied'])?$job_data['workers_applied']:"";
                            $result['job_id'] = isset($job['id'])?$job['id']:"";
                            $result['description'] = isset($job_data['description'])?$job_data['description']:"";
                            $result['posted_on'] = isset($job_data['posted_on'])?date('M j Y', strtotime($job_data['posted_on'])):"";
                            $result['type'] = isset($job['type'])?$job['type']:"";
                            $result['terms'] = isset($job['terms'])?$job['terms']:"";
                            $result['job_name'] = isset($job['job_name'])?$job['job_name']:"";
                            $result['total_applied'] = isset($job['jobs_applied'])?$job['jobs_applied']:"";
                            $result['department'] = isset($job['Department'])?$job['Department']:"";
                            $result['worker_name'] = isset($worker_name)?$worker_name:"";
                            $result['worker_image'] = isset($worker_img)?$worker_img:"";
                            $result['worker_id'] = isset($worker_id)?$worker_id:"";
                            $result['recruiter_name'] = $recruiter_name;
                            $result['recruiter_id'] = $recruiter_id;
                            $result['worker_userID'] = isset($worker->user_id)?$worker->user_id:'';
                            $result['offer_id'] = $job_data['offer_id'];
                            if(isset($job_data['worked_at_facility_before']) && ($job_data['worked_at_facility_before'] == 'yes')){
                                $recs = true;
                            }else{
                                $recs = false;
                            }

                            if(isset($job_data['license_type']) && ($job_data['license_type'] != null) && ($job_data['profession'] == $job_data['license_type'])){
                                $profession = true;
                            }else{
                                $profession = false;
                            }
                            if(isset($job_data['specialty']) && ($job_data['specialty'] != null) && ($job_data['preferred_specialty'] == $job_data['specialty'])){
                                $speciality = true;
                            }else{
                                $speciality = false;
                            }
                            if(isset($job_data['experience']) && ($job_data['experience'] != null) && ($job_data['preferred_experience'] == $job_data['experience'])){
                                $experience = true;
                            }else{
                                $experience = false;
                            }
                            $countable = explode(',',$worker_reference_name);
                            $num = [];
                            foreach($countable as $rec){
                                if(!empty($rec)){
                                    $num[] = $rec;
                                }
                            }
                            $countable = count($num);
                            if($job_data['number_of_references'] == $countable){
                                $worker_ref_num = true;
                            }else{
                                $worker_ref_num = false;
                            }
                            $worker_info = [];
                            $worker_vacc = [];
                            $worker_cert = [];
                            $data['worker'] = !empty($job_data['highest_nursing_degree'])?$job_data['highest_nursing_degree']:"-";
                            $data['name'] = 'Profession';
                            $data['worker1'] = !empty($job_data['specialty'])?$job_data['specialty']:"-";
                            $data['name1'] = 'Speciality';
                            $worker_info[] = $data;

                            if($job_data['nursing_license_state'] == $job_data['job_location']){ $val = true; }else{ $val = false; }
                            $data['worker'] = !empty($job_data['nursing_license_state'])?$job_data['nursing_license_state']:"-";
                            $data['name'] = 'Professional Licensure';
                            $data['worker1'] = !empty($job_data['experience'])?$job_data['experience']:"-";
                            $data['name1'] = 'Experience';
                            $worker_info[] = $data;

                            $data['worker'] = isset($worker_number_of_references)?$worker_number_of_references:"-";
                            $data['name'] = 'Number Of References';
                            $data['worker1'] = isset($worker_reference_title)?$worker_reference_title:"-";
                            $data['name1'] = 'Min Title Of References';
                            $worker_info[] = $data;


                            // $data['worker1'] = isset($job_data['BLS'])?$job_data['BLS']:"-";
                            // $data['name1'] = 'BLS';
                            // $worker_info[] = $data;

                            // $data['worker'] = isset($job_data['ACLS'])?$job_data['ACLS']:"-";
                            // $data['name'] = 'ACLS';
                            // $data['worker1'] = isset($job_data['PALS'])?$job_data['PALS']:"-";
                            // $data['name1'] = 'PALS';
                            // $worker_info[] = $data;

                            $data['worker'] = isset($worker_reference_recency_reference)?$worker_reference_recency_reference:"-";
                            $data['name'] = 'Recency Of Reference';
                            $data['worker1'] = isset($job_data['skills_checklists'])?$job_data['skills_checklists']:"-";
                            $data['name1'] = 'Skills Checklist';
                            $worker_info[] = $data;

                            $data['worker'] = isset($job_data['eligible_work_in_us'])?$job_data['eligible_work_in_us']:"-";
                            $data['name'] = 'Eligible To Work In The US';
                            $data['worker1'] = isset($job_data['worker_urgency'])?$job_data['worker_urgency']:"-";
                            $data['name1'] = 'Urgency';
                            $worker_info[] = $data;


                            if($job['traveler_distance_from_facility'] == $job_data['distance_from_your_home']){ $val = true; }else{$val = false;}
                            $data['worker'] = isset($job['traveler_distance_from_facility'])?$job['traveler_distance_from_facility']:"-";
                            $data['name'] = 'Traveler Distance from facility';
                            $data['worker1'] = isset($job_data['facilities_you_like_to_work_at'])?$job_data['facilities_you_like_to_work_at']:"-";
                            $data['name1'] = 'Facility';
                            $worker_info[] = $data;

                            $data['worker'] = isset($job_data['state'])?$job_data['state']:"-";
                            $data['name'] = 'Location';
                            $data['worker1'] = isset($job_data['worker_shift_time_of_day'])?$job_data['worker_shift_time_of_day']:"-";
                            $data['name1'] = 'Shift';
                            $worker_info[] = $data;

                            $data['worker'] = isset($job_data['distance_from_your_home'])?$job_data['distance_from_your_home']:"-";
                            $data['name'] = 'Distance from your home';
                            $data['worker1'] = isset($job_data['worked_at_facility_before'])?$job_data['worked_at_facility_before']:"-";
                            $data['name1'] = "Facilities you've worket at";
                            $worker_info[] = $data;

                            $data['worker'] = isset($job_data['worker_facility_city'])?$job_data['worker_facility_state_code']:"-";
                            $data['name'] = 'Facility City';
                            $data['worker1'] = isset($job_data['worker_start_date'])?$job_data['worker_start_date']:"-";
                            $data['name1'] = 'Start Date';
                            $worker_info[] = $data;


                            $data['worker'] = "-";
                            $data['name'] = 'RTO';
                            $data['worker1'] = isset($job_data['worker_shift_time_of_day'])?$job_data['worker_shift_time_of_day']:"-";
                            $data['name1'] = 'Shift Time of Day';
                            $worker_info[] = $data;

                            $data['worker'] = isset($job_data['worker_weeks_assignment'])?$job_data['worker_weeks_assignment']:"-";
                            $data['name'] = 'Assignment in weeks';
                            $data['worker1'] = isset($job_data['worker_employer_weekly_amount'])?$job_data['worker_employer_weekly_amount']:"-";
                            $data['name1'] = 'Employer Weekly Amount';
                            $worker_info[] = $data;


                            $data['worker'] = isset($job_data['worker_goodwork_number'])?$job_data['worker_goodwork_number']:"-";
                            $data['name'] = 'Goodwork Number';
                            $worker_info[] = $data;

                            $data['worker'] = '';
                            $data['name'] = '';
                            $data['worker1'] = '';
                            $data['name1'] = '';
                            $i = 0;
                            if(isset($worker_vaccination)){
                                foreach($worker_vaccination as $job_vacc)
                                {
                                    $data['worker'] = isset($worker_vaccination[$i])?$worker_vaccination[$i]:"-";
                                    $data['name'] = 'Vaccinations & Immunications '.$worker_vaccination[$i];
                                    $worker_vacc[] = $data;
                                    $i++;
                                }
                            }else{
                                $data['worker'] = '-';
                                $data['name'] = 'Vaccinations & Immunications ';
                                $worker_vacc[] = $data;
                            }


                            $i = 0;
                            if(isset($worker_certificate_name)){
                                foreach($worker_certificate_name as $job_vacc)
                                {
                                    $data['worker'] = isset($worker_certificate_name[$i])?$worker_certificate_name[$i]:"-";
                                    $data['name'] = 'Certification Name';
                                    $worker_cert[] = $data;
                                    $i++;
                                }
                            }else{
                                $data['worker'] = '-';
                                $data['name'] = 'Certification Name';
                                $worker_cert[] = $data;
                            }

                            $result['worker_info'] = $worker_info;
                            $result['worker_certificate'] = $worker_cert;
                            $result['worker_vaccination'] = $worker_vacc;

                            $this->check = "1";
                            $this->message = "Matching details listed successfully";
                            $this->return_data = $result;

                        }else{
                            $this->check = "1";
                            $this->message = "User Not Found";
                        }
                    }else{
                        $this->check = "1";
                        $this->message = "This worker offer Not Found";
                    }


                }else{
                    $this->check = "1";
                    $this->message = "Worker Not Found";
                }
            }else{
                $this->check = "1";
                $this->message = "Job Not Found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function recruiterAppliedJobs(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'worker_id' => 'required',
            'recruiter_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse_info = NURSE::where('id', $request->worker_id)->first();

            $user_info = USER::where('id', $request->recruiter_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.recruiter_id' => $user->id,
                    'offers.nurse_id' => $request->worker_id
                ];

                $ret = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.id as job_id', 'jobs.*', 'jobs.created_at as posted_on', 'offers.id as offer_id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'nurses.*', 'facilities.name as facility_name')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('nurses', 'offers.nurse_id', '=', 'nurses.id')
                    ->join('users', 'nurses.user_id', '=', 'users.id')
                    ->Join('facilities', function ($join) {
                            $join->on('facilities.id', '=', 'jobs.facility_id');
                        })
                    ->where($whereCond)
                ->orderBy('offers.nurse_id', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $rec)
                {

                    $result['worker_id'] = $rec['id'];
                    $result['worker_user_id'] = $rec['user_id'];
                    $result['job_id'] = $rec['job_id'];
                    $result['recruiter_id'] = $rec['recruiter_id'];
                    $result['offer_id'] = $rec['offer_id'];
                    $result['job_type'] = isset($rec['job_type'])?$rec['job_type']:"";
                    $result['type'] = isset($rec['type'])?$rec['type']:"";
                    $result['workers_applied'] = isset($rec['workers_applied'])?$rec['workers_applied']:0;
                    $result['posted_on'] = 'Posted on '.date('M j Y', strtotime($rec['posted_on']));
                    $result['job_name'] = isset($rec['job_name'])?$rec['job_name']:"";
                    $result['job_city'] = isset($rec['job_city'])?$rec['job_city'].',':"";
                    $result['job_state'] = isset($rec['job_state'])?$rec['job_state']:"";
                    // $result['job_location'] = isset($rec['job_location'])?$rec['job_location']:"";
                    $result['job_location'] = $result['job_city'].' '.$result['job_state'];
                    $result['preferred_shift'] = isset($rec['preferred_shift'])?$rec['preferred_shift']:"";
                    $result['preferred_assignment_duration'] = isset($rec['preferred_assignment_duration'])?$rec['preferred_assignment_duration']:"";
                    $result['worker_image'] = isset($rec['worker_image'])? url("public/images/nurses/profile/" . $rec['worker_image']):"";
                    $result['worker_name'] = isset($rec['first_name'])?$rec['first_name'].' '.$rec['last_name']:"";
                    $result['facility_name'] = isset($rec['facility_name'])?$rec['facility_name']:"";
                    $result['employer_weekly_amount'] = isset($rec['employer_weekly_amount'])?$rec['employer_weekly_amount']:"";
                    $result['profession'] = isset($rec['profession'])?$rec['profession']:"";
                    $result['specialty'] = isset($rec['preferred_specialty'])?$rec['preferred_specialty']:"";
                    $result['experience'] = isset($rec['preferred_experience'])?$rec['preferred_experience'].' Years of Experience':"";

                    $record[] =  $result;
                }
                $this->check = "1";
                $this->message = "Data listed successfully";
                $this->return_data = $record;

            }else{
                $this->check = "1";
                $this->message = "User not found";

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function applicationInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'job_id' => 'required',
            'recruiter_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $whereCond = [
                    'facilities.active' => true,
                    'jobs.id' => $request->job_id,
                ];

            $respond = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.*', 'offers.job_id as job_id', 'offers.id as offer_id', 'facilities.name as facility_name', 'facilities.city as facility_city', 'facilities.state as facility_state', 'jobs.created_at as posted_on')
                        ->leftJoin('facilities','jobs.facility_id', '=', 'facilities.id')
                        ->leftJoin('offers','offers.job_id', '=', 'jobs.id')
                        ->leftJoin('nurses', 'offers.nurse_id', '=', 'nurses.id')
                        ->leftJoin('users','nurses.user_id', '=', 'users.id')
                        ->where($whereCond);
            $job_data = $respond->groupBy('jobs.id')->first();
            $recruiter_info = User::where('id', $request->recruiter_id)->first();

            $result = [];
            $result['jobs_applied'] = isset($job_data['workers_applied'])?$job_data['workers_applied']:"";
            $result['job_id'] = isset($job_data['id'])?$job_data['id']:"";
            $result['description'] = isset($job_data['description'])?$job_data['description']:"";
            // $result['posted_on'] = isset($job_data['posted_on'])?'Posted on '.date('M j Y', strtotime($job_data['posted_on'])):"";
            $result['posted_on'] = isset($job_data['posted_on'])?date('M j', strtotime($job_data['posted_on'])):"";
            $result['type'] = isset($job_data['type'])?$job_data['type']:"";
            $result['terms'] = isset($job_data['terms'])?$job_data['terms']:"";
            $result['job_name'] = isset($job_data['job_name'])?$job_data['job_name']:"";
            $result['total_applied'] = isset($job_data['jobs_applied'])?$job_data['jobs_applied']:"";
            $result['department'] = isset($job_data['Department'])?$job_data['Department']:"";
            $result['recruiter_name'] = $recruiter_info['first_name'].' '.$recruiter_info['last_name'];
            $result['recruiter_id'] = $recruiter_info->id;
            $result['facility_name'] = $job_data['facility_name'];
            $result['offer_id'] = $job_data['offer_id'];

            $vaccinations = explode(',',$job_data['vaccinations']);
            $certificates = explode(',',$job_data['certificate']);
            $worker_info = [];

            $data['job'] = !empty($job_data['profession'])?$job_data['profession']:"-";
            $data['name'] = 'Profession';
            $data['job1'] = !empty($job_data['preferred_specialty'])?$job_data['preferred_specialty']:"-";
            $data['name1'] = 'Speciality';
            $worker_info[] = $data;

            $data['job'] = !empty($job_data['job_location'])?$job_data['job_location']:"-";
            $data['name'] = 'Professional Licensure';
            $data['job1'] = !empty($job_data['preferred_experience'])?$job_data['preferred_experience']:"-";
            $data['name1'] = 'Experience';
            $worker_info[] = $data;

            // $data['job'] = isset($vaccinations[0])?$vaccinations[0]:"-";
            // $data['name'] = 'Vaccinations & Immunications Covid';
            // $data['job1'] = isset($vaccinations[1])?$vaccinations[1]:"-";
            // $data['name1'] = 'Vaccinations & Immunications Flu';
            // $worker_info[] = $data;

            $data['job'] = isset($job_data['number_of_references'])?$job_data['number_of_references']:"-";
            $data['name'] = 'Number Of References';
            $data['job1'] = isset($job_data['min_title_of_reference'])?$job_data['min_title_of_reference']:"-";
            $data['name1'] = 'Min Title Of References';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['recency_of_reference'])?$job_data['recency_of_reference']:"-";
            $data['name'] = 'Recency Of Reference';
            $data['job1'] = isset($job_data['skills'])?$job_data['skills']:"-";
            $data['name1'] = 'Skills Checklist';
            $worker_info[] = $data;

            // $data['job'] = isset($certificates[1])?$certificates[1]:"-";
            // $data['name'] = 'ACLS';
            // $data['job1'] = isset($certificates[3])?$certificates[3]:"-";
            // $data['name1'] = 'PALS';
            // $worker_info[] = $data;

            // $data['job'] = isset($certificates[2])?$certificates[2]:"-";
            // $data['name'] = 'other';


            $data['job'] = isset($job_data['position_available'])?$job_data['position_available']:"-";
            $data['name'] = '# of Positions Available';
            $data['job1'] = isset($job_data['msp'])?$job_data['msp']:"-";
            $data['name1'] = 'MSP';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['vms'])?$job_data['vms']:"-";
            $data['name'] = 'VMS';
            $data['job1'] = isset($job_data['submission_of_vms'])?$job_data['submission_of_vms']:"-";
            $data['name1'] = '# of Submissions in VMS';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['block_scheduling'])?$job_data['block_scheduling']:"-";
            $data['name'] = 'Block Scheduling';
            $data['job1'] = isset($job_data['facility_shift_cancelation_policy'])?$job_data['facility_shift_cancelation_policy']:"-";
            $data['name1'] = 'Facility Shift Cancellation Policy';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['contract_termination_policy'])?$job_data['contract_termination_policy']:"-";
            $data['name'] = 'Contract Termination Policy';
            $data['job1'] = isset($job_data['traveler_distance_from_facility'])?$job_data['traveler_distance_from_facility'].' miles':"-";
            $data['name1'] = 'Traveler Distance From Facility';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['facility_name'])?$job_data['facility_name']:"-";
            $data['name'] = 'Facility';
            $data['job1'] = isset($job_data['facilitys_parent_system'])?$job_data['facilitys_parent_system']:"-";
            $data['name1'] = "Facility's Parent System";
            $worker_info[] = $data;

            $data['job'] = isset($job_data['facility_average_rating'])?$job_data['facility_average_rating']:"-";
            $data['name'] = "Facility Average Rating";
            $data['job1'] = isset($job_data['recruiter_average_rating'])?$job_data['recruiter_average_rating']:"-";
            $data['name1'] = "Recruiter Average Rating";
            $worker_info[] = $data;

            $data['job'] = isset($job_data['employer_average_rating'])?$job_data['employer_average_rating']:"-";
            $data['name'] = "Employer Average Rating";
            $data['job1'] = isset($job_data['clinical_setting'])?$job_data['clinical_setting']:"-";
            $data['name1'] = 'Clinical Setting';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['Patient_ratio'])?$job_data['Patient_ratio']:"-";
            $data['name'] = "Patient ratio";
            $data['job1'] = isset($job_data['emr'])?$job_data['emr']:"-";
            $data['name1'] = 'EMR';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['Unit'])?$job_data['Unit']:"-";
            $data['name'] = "Unit";
            $data['job1'] = isset($job_data['Department'])?$job_data['Department']:"-";
            $data['name1'] = 'Department';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['Bed_Size'])?$job_data['Bed_Size']:"-";
            $data['name'] = "Bed Size";
            $data['job1'] = isset($job_data['Trauma_Level'])?$job_data['Trauma_Level']:"-";
            $data['name1'] = 'Trauma Level';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['scrub_color'])?$job_data['scrub_color']:"-";
            $data['name'] = "Scrub Color";
            $data['job1'] = isset($job_data['facility_city'])?$job_data['facility_city']:"-";
            $data['name1'] = 'Facility City';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['facility_state'])?$job_data['facility_state']:"-";
            $data['name'] = "Facility State Code";
            $data['job1'] = isset($job_data['start_date'])?$job_data['start_date']:"-";
            $data['name1'] = 'Start Date';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['rto'])?$job_data['rto']:"-";
            $data['name'] = "RTO";
            $data['job1'] = isset($job_data['preferred_shift'])?$job_data['preferred_shift']:"-";
            $data['name1'] = 'Shift Time of Day';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['hours_per_week'])?$job_data['hours_per_week']:"-";
            $data['name'] = "Hours/Week";
            $data['job1'] = isset($job_data['guaranteed_hours'])?$job_data['guaranteed_hours']:"-";
            $data['name1'] = 'Guaranteed Hours';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['hours_shift'])?$job_data['hours_shift']:"-";
            $data['name'] = "Hours/Shift";
            $data['job1'] = isset($job_data['preferred_assignment_duration'])?$job_data['preferred_assignment_duration']:"-";
            $data['name1'] = 'Weeks/Assignment';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['weeks_shift'])?$job_data['weeks_shift']:"-";
            $data['name'] = "Shifts/Week";
            $data['job1'] = isset($job_data['referral_bonus'])?$job_data['referral_bonus']:"-";
            $data['name1'] = 'Referral Bonus';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['sign_on_bonus'])?$job_data['sign_on_bonus']:"-";
            $data['name'] = "Sign-On Bonus";
            $data['job1'] = isset($job_data['completion_bonus'])?$job_data['completion_bonus']:"-";
            $data['name1'] = 'Completion Bonus';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['extension_bonus'])?$job_data['extension_bonus']:"-";
            $data['name'] = "Extension Bonus";
            $data['job1'] = isset($job_data['other_bonus'])?$job_data['other_bonus']:"-";
            $data['name1'] = 'Other Bonus';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['four_zero_one_k'])?$job_data['four_zero_one_k']:"-";
            $data['name'] = "401K";
            $data['job1'] = isset($job_data['health_insaurance'])?$job_data['health_insaurance']:"-";
            $data['name1'] = 'Health Insurance';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['dental'])?$job_data['dental']:"-";
            $data['name'] = "Dental";
            $data['job1'] = isset($job_data['vision'])?$job_data['vision']:"-";
            $data['name1'] = 'Vision';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['actual_hourly_rate'])?$job_data['actual_hourly_rate']:"-";
            $data['name'] = "Actual Hourly rate";
            $data['job1'] = isset($job_data['feels_like_per_hour'])?$job_data['feels_like_per_hour']:"-";
            $data['name1'] = 'Feels Like $/hr';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['overtime'])?$job_data['overtime']:"-";
            $data['name'] = "Overtime";
            $data['job1'] = isset($job_data['holiday'])?$job_data['holiday']:"-";
            $data['name1'] = 'Holiday';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['on_call'])?$job_data['on_call']:"-";
            $data['name'] = "On Call";
            $data['job1'] = isset($job_data['call_back'])?$job_data['call_back']:"-";
            $data['name1'] = 'Call Back';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['orientation_rate'])?$job_data['orientation_rate']:"-";
            $data['name'] = "Orientation Rate";
            $data['job1'] = isset($job_data['weekly_taxable_amount'])?$job_data['weekly_taxable_amount']:"-";
            $data['name1'] = 'Weekly Taxable amount';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['weekly_non_taxable_amount'])?$job_data['weekly_non_taxable_amount']:"-";
            $data['name'] = "Weekly non-taxable amount";
            $data['job1'] = isset($job_data['employer_weekly_amount'])?$job_data['employer_weekly_amount']:"-";
            $data['name1'] = 'Employer Weekly Amount';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['goodwork_weekly_amount'])?$job_data['goodwork_weekly_amount']:"-";
            $data['name'] = "Goodwork Weekly Amount";
            $data['job1'] = isset($job_data['total_employer_amount'])?$job_data['total_employer_amount']:"-";
            $data['name1'] = 'Total Employer Amount';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['total_goodwork_amount'])?$job_data['total_goodwork_amount']:"-";
            $data['name'] = "Total Goodwork Amount";
            $data['job1'] = isset($job_data['total_contract_amount'])?$job_data['total_contract_amount']:"-";
            $data['name1'] = 'Total Contract Amount';
            $worker_info[] = $data;

            $data['job'] = isset($job_data['id'])?$job_data['id']:"-";
            $data['name'] = "Goodwork Number";
            $worker_info[] = $data;

            $data['job1'] ='';
            $data['name1'] ='';
            if(isset($vaccinations) && !empty($vaccinations[0])){
                $i=0;
                foreach($vaccinations as $vacc){
                    $data['job'] = isset($vaccinations[$i])?$vaccinations[$i]:"-";
                    $data['name'] = 'Vaccinations & Immunications '.$vaccinations[$i];
                    $i++;
                    $worker_vacc[] = $data;
                }
            }else{
                $worker_vacc = [];
            }

            if(isset($certificates) && $certificates[0] != ''){
                $i=0;
                foreach($certificates as $vacc){
                    $data['job'] = isset($certificates[$i])?$certificates[$i]:"-";
                    $data['name'] = 'Certification Name';
                    $i++;
                    $worker_cert[] = $data;
                }
            }else{
                $worker_cert = [];
            }

            $result['worker_info'] = $worker_info;
            $result['worker_vaccination'] = $worker_vacc;
            $result['worker_certificate'] = $worker_cert;

            $this->check = "1";
            $this->message = "Matching details listed successfully";
            $this->return_data = $result;

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobAppliedWorkers(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'job_id' => 'required',
            'recruiter_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.recruiter_id' => $request->recruiter_id,
                    'jobs.id' => $request->job_id
                ];

                $ret = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.id as job_id', 'jobs.*', 'jobs.created_at as posted_on', 'offers.id as offer_id', 'offers.created_at as offer_created_at', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'nurses.*', 'facilities.name as facility_name')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('nurses', 'offers.nurse_id', '=', 'nurses.id')
                    ->join('users', 'nurses.user_id', '=', 'users.id')
                    ->Join('facilities', function ($join) {
                            $join->on('facilities.id', '=', 'jobs.facility_id');
                        })
                    ->where($whereCond)
                ->orderBy('offers.nurse_id', 'desc');
                $job_data = $ret->get();
                // print_r($job_data);
                // die();
                $whereCond1 = [
                    'facilities.active' => true,
                    'jobs.recruiter_id' => $request->recruiter_id,
                    'jobs.id' => $request->job_id
                ];
                $jobs = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.id as job_id', 'jobs.*', 'jobs.created_at as posted_on', 'offers.id as offer_id', 'offers.created_at as created_at','facilities.name as facility_name')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->Join('facilities', function ($join) {
                            $join->on('facilities.id', '=', 'jobs.facility_id');
                        })
                    ->where($whereCond1)
                ->orderBy('offers.nurse_id', 'desc');
                $job = $jobs->first();

                $recruiter = User::where('id', $request->recruiter_id)->first();
                $result = [];
                $record = [];
                $val = [];

                $record['jobs_applied'] = isset($job['workers_applied'])?$job['workers_applied']:"";
                $record['job_id'] = isset($job['id'])?$job['id']:"";
                $record['description'] = isset($job['description'])?$job['description']:"";
                $record['posted_on'] = isset($job['created_at'])?'Applied '.date('M j', strtotime($job['created_at'])):"";
                $record['type'] = isset($job['type'])?$job['type']:"";
                $record['terms'] = isset($job['terms'])?$job['terms']:"";
                $record['job_name'] = isset($job['job_name'])?$job['job_name']:"";
                $record['total_applied'] = isset($job['jobs_applied'])?$job['jobs_applied']:"";
                $record['department'] = isset($job['Department'])?$job['Department']:"";
                $record['recruiter_name'] = $recruiter['first_name'].' '.$recruiter['last_name'];
                $record['recruiter_id'] = $recruiter['id'];
                $record['facility_name'] = isset($job['facility_name'])?$job['facility_name']:'';

                foreach($job_data as $rec)
                {
                    $result['worker_id'] = $rec['id'];
                    $result['worker_user_id'] = $rec['user_id'];
                    $result['job_id'] = $rec['job_id'];
                    $result['recruiter_id'] = $rec['recruiter_id'];
                    $result['offer_id'] = $rec['offer_id'];
                    $result['job_type'] = isset($rec['job_type'])?$rec['job_type']:"";
                    $result['type'] = isset($rec['type'])?$rec['type']:"";
                    $result['workers_applied'] = isset($rec['workers_applied'])?$rec['workers_applied']:0;
                    $result['posted_on'] = 'Applied on '.date('M j Y', strtotime($rec['offer_created_at']));
                    $result['job_name'] = isset($rec['job_name'])?$rec['job_name']:"";
                    $result['job_city'] = isset($rec['job_city'])?$rec['job_city']:"";
                    $result['job_state'] = isset($rec['job_state'])?$rec['job_state']:"";
                    $result['job_location'] = isset($rec['job_location'])?$rec['job_location']:"";
                    $result['preferred_shift'] = isset($rec['preferred_shift'])?$rec['preferred_shift']:"";
                    $result['preferred_assignment_duration'] = isset($rec['preferred_assignment_duration'])?$rec['preferred_assignment_duration']:"";
                    $result['worker_image'] = isset($rec['worker_image'])? url("public/images/nurses/profile/" . $rec['worker_image']):"";
                    $result['worker_name'] = isset($rec['first_name'])?$rec['first_name'].' '.$rec['last_name']:"";
                    $result['facility_name'] = isset($rec['facility_name'])?$rec['facility_name']:"";
                    $result['employer_weekly_amount'] = isset($rec['employer_weekly_amount'])?$rec['employer_weekly_amount']:"";
                    $result['profession'] = isset($rec['profession'])?$rec['profession']:"";
                    $result['specialty'] = isset($rec['preferred_specialty'])?$rec['preferred_specialty']:"";
                    $result['experience'] = isset($rec['preferred_experience'])?$rec['preferred_experience'].' Years of Experience':"";

                    $val[] =  $result;
                }
                $record["worker_info"] = $val;
                $this->check = "1";
                $this->message = "Data listed successfully";
                $this->return_data = $record;


        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function unblockWorker(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'worker_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse = Nurse::where('id', $request->worker_id)->first();
            if ($nurse) {
                $this->check = "1";
                $nurse_deleted = DB::table('blocked_users')->where('worker_id', '=', $request->worker_id)->delete();

                if($nurse_deleted){
                    $this->message = "Worker unblocked successfully";
                    $this->return_data = 1;
                }else{
                    $this->message = "Worker not unblocked";
                    $this->return_data = 0;
                }

            } else {
                $this->message = "Worker not exists";
                $this->return_data = 0;
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function hideStatusApplication(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'job_id' => 'required',
            'api_key' => 'required',
            'is_hidden' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $job_info = Job::where('id', $request->job_id);
            if ($job_info->count() > 0) {
                $job = $job_info->first();

                $whereCond = [
                    'id' => $job['id']
                ];
                if(isset($job)){
                    Job::where($whereCond)->update(['is_hidden' => $request->is_hidden]);
                    $this->check = "1";
                    if($request->is_hidden == '1'){
                        $this->message = "Applications hide successfully";
                    }else{
                        $this->message = "Applications unhide successfully";
                    }

                }else{
                    $this->check = "0";
                    $this->message = "Applications not hide";
                }

            }else{
                $this->check = "1";
                $this->message = "User not found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message], 200);
    }

    public function closeStatusApplication(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'job_id' => 'required',
            'api_key' => 'required',
            'is_closed' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $job_info = Job::where('id', $request->job_id);
            if ($job_info->count() > 0) {
                $job = $job_info->first();

                $whereCond = [
                    'id' => $job['id']
                ];
                if(isset($job)){
                    Job::where($whereCond)->update(['is_closed' => $request->is_closed]);
                    $this->check = "1";
                    if($request->is_closed == '1'){
                        $this->message = "Applications closed successfully";
                    }else{
                        $this->message = "Applications open successfully";
                    }

                }else{
                    $this->check = "0";
                    $this->message = "Applications not closed";
                }

            }else{
                $this->check = "1";
                $this->message = "User not found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message], 200);
    }

    public function sendRecordNotification(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'worker_id' => 'required',
            'update' => 'required',
            'update_key' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse = Nurse::where('id', $request->worker_id)->first();
            $user = User::where('id', $nurse['user_id'])->first();
            $check = DB::table('ask_worker')->where(['text_field' => $request->update, 'worker_id' => $request->worker_id])->first();
            if(empty($check)){

                $notification = Notification::create(['created_by' => $user['id'], 'title' => $request->update, 'job_id' => $request->job_id, 'isAskWorker' => '1', 'text' => 'Please update '.$request->update]);
                $record = DB::table('ask_worker')->insert(['text_field' => $request->update, 'update_key' => $request->update_key, 'worker_id' => $request->worker_id]);
                if($notification){
                    $this->check = "1";
                    $this->message = "Text saved successfully";
                    $this->return_data = $request->update;
                }else{
                    $this->check = "0";
                    $this->message = "Text not saved successfully";
                    $this->return_data = '0';
                }
            }else{
                $this->check = "1";
                $this->message = "Text already saved";
                $this->return_data = '0';
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function pushNotification(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'worker_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            // $recuiter = User::where('id', $request->recruiter_id)->first();
            $check = DB::table('ask_worker')->where('worker_id', $request->worker_id)->get();
            if(isset($check)){
                    $this->check = "1";
                    $this->message = "Push Notifiaction listed successfully";
                    $this->return_data = $check;
            }else{
                $this->check = "0";
                $this->message = "No Record Found";
                $this->return_data = '0';
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getJobKeys(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'job_id' => 'required',
            'api_key' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $return_data = [];
            $jobs = Job::where('id', $request->job_id)->first();

            if (isset($jobs)) {
                $job_data['job_id'] = $jobs['id'];
                $job_data["job_name"] = isset($jobs['job_name'])?$jobs['job_name']:'';
                $job_data["end_date"] = isset($jobs['end_date'])?$jobs['end_date']:'';
                $job_data["type"] = isset($jobs['type'])?$jobs['type']:'';
                $job_data["compact"] = isset($jobs['compact'])?$jobs['compact']:'';
                $job_data["term"] = isset($jobs['terms'])?$jobs['terms']:'';
                $job_data["profession"] = isset($jobs['profession'])?$jobs['profession']:'';
                $job_data["specialty"] = isset($jobs['preferred_specialty'])?$jobs['preferred_specialty']:'';
                $job_data["experience"] = isset($jobs['preferred_experience'])?$jobs['preferred_experience']:'';
                $job_data["professional_licensure"] = isset($jobs['job_location'])?$jobs['job_location']:'';
                $job_data["vaccinations"] = isset($jobs['vaccinations'])?$jobs['vaccinations']:'';
                $job_data["number_of_references"] = isset($jobs['number_of_references'])?$jobs['number_of_references']:'';
                $job_data["min_title_of_reference"] = isset($jobs['min_title_of_reference'])?$jobs['min_title_of_reference']:'';
                $job_data["recency_of_reference"] = isset($jobs['recency_of_reference'])?$jobs['recency_of_reference']:'';
                $job_data["certificate"] = isset($jobs['certificate'])?$jobs['certificate']:'';
                $job_data["skills_checklist"] = isset($jobs['skills'])?$jobs['skills']:'';
                $job_data["urgency"] = isset($jobs['urgency'])?$jobs['urgency']:'';
                $job_data["position_available"] = isset($jobs['position_available'])?$jobs['position_available']:'';
                $job_data["msp"] = isset($jobs['msp'])?$jobs['msp']:'';
                $job_data["vms"] = isset($jobs['vms'])?$jobs['vms']:'';
                $job_data["submission_of_vms"] = isset($jobs['submission_of_vms'])?$jobs['submission_of_vms']:'';
                $job_data["block_scheduling"] = isset($jobs['block_scheduling'])?$jobs['block_scheduling']:'';
                $job_data["float_requirement"] = isset($jobs['float_requirement'])?$jobs['float_requirement']:'';
                $job_data["facility_shift_cancelation_policy"] = isset($jobs['facility_shift_cancelation_policy'])?$jobs['facility_shift_cancelation_policy']:'';
                $job_data["contract_termination_policy"] = isset($jobs['contract_termination_policy'])?$jobs['contract_termination_policy']:'';
                $job_data["traveler_distance_from_facility"] = isset($jobs['traveler_distance_from_facility'])?$jobs['traveler_distance_from_facility']:'';
                // $job_data["facility"] = isset($jobs['facility'])?$jobs['facility']:'';
                // $job_data["facility_id"] = isset($jobs->facility_id)?$jobs->facility_id:$facility_id;
                $job_data["facility"] = 'Testing Facility';
                $job_data["facility_id"] = "GWf000001";

                $job_data["clinical_setting"] = isset($jobs['clinical_setting'])?$jobs['clinical_setting']:'';
                $job_data["Patient_ratio"] = isset($jobs['Patient_ratio'])?$jobs['Patient_ratio']:'';
                $job_data["emr"] = isset($jobs['emr'])?$jobs['emr']:'';
                $job_data["Unit"] = isset($jobs['Unit'])?$jobs['Unit']:'';
                $job_data["Department"] = isset($jobs['Department'])?$jobs['Department']:'';
                $job_data["Bed_Size"] = isset($jobs['Bed_Size'])?$jobs['Bed_Size']:'';
                $job_data["Trauma_Level"] = isset($jobs['Trauma_Level'])?$jobs['Trauma_Level']:'';
                $job_data["scrub_color"] = isset($jobs['scrub_color'])?$jobs['scrub_color']:'';
                $job_data["start_date"] = isset($jobs['start_date'])?$jobs['start_date']:'';
                $job_data["as_soon_as"] = isset($jobs['as_soon_as'])?$jobs['as_soon_as']:'';
                $job_data["rto"] = isset($jobs['rto'])?$jobs['rto']:'';
                $job_data["preferred_shift"] = isset($jobs['preferred_shift'])?$jobs['preferred_shift']:'';
                $job_data["hours_per_week"] = isset($jobs['hours_per_week'])?$jobs['hours_per_week']:'';
                $job_data["guaranteed_hours"] = isset($jobs['guaranteed_hours'])?$jobs['guaranteed_hours']:'';
                $job_data["hours_shift"] = isset($jobs['hours_shift'])?$jobs['hours_shift']:'';
                $job_data["weeks_shift"] = isset($jobs['weeks_shift'])?$jobs['weeks_shift']:'';
                $job_data["preferred_assignment_duration"] = isset($jobs['preferred_assignment_duration'])?$jobs['preferred_assignment_duration']:'';
                $job_data["referral_bonus"] = isset($jobs['referral_bonus'])?$jobs['referral_bonus']:'';
                $job_data["sign_on_bonus"] = isset($jobs['sign_on_bonus'])?$jobs['sign_on_bonus']:'';
                $job_data["completion_bonus"] = isset($jobs['completion_bonus'])?$jobs['completion_bonus']:'';
                $job_data["extension_bonus"] = isset($jobs['extension_bonus'])?$jobs['extension_bonus']:'';
                $job_data["other_bonus"] = isset($jobs['other_bonus'])?$jobs['other_bonus']:'';
                $job_data["four_zero_one_k"] = isset($jobs['four_zero_one_k'])?$jobs['four_zero_one_k']:'';
                $job_data["health_insaurance"] = isset($jobs['health_insaurance'])?$jobs['health_insaurance']:'';
                $job_data["dental"] = isset($jobs['dental'])?$jobs['dental']:'';
                $job_data["vision"] = isset($jobs['vision'])?$jobs['vision']:'';
                $job_data["actual_hourly_rate"] = isset($jobs['actual_hourly_rate'])?$jobs['actual_hourly_rate']:'';
                $job_data["overtime"] = isset($jobs['overtime'])?$jobs['overtime']:'';
                $job_data["holiday"] = isset($jobs['holiday'])?$jobs['holiday']:'';
                $job_data["on_call"] = isset($jobs['on_call'])?$jobs['on_call']:'';
                $job_data["orientation_rate"] = isset($jobs['orientation_rate'])?$jobs['orientation_rate']:'';
                $job_data["weekly_non_taxable_amount"] = isset($jobs['weekly_non_taxable_amount'])?$jobs['weekly_non_taxable_amount']:'';
                $job_data["description"] = isset($jobs['description'])?$jobs['description']:'';

                $job_data["facilitys_parent_system"] = isset($jobs['facilitys_parent_system'])?$jobs['facilitys_parent_system']:'';
                $job_data["facility_average_rating"] = isset($jobs['facility_average_rating'])?$jobs['facility_average_rating']:'';
                $job_data["recruiter_average_rating"] = isset($jobs['recruiter_average_rating'])?$jobs['recruiter_average_rating']:'';
                $job_data["employer_average_rating"] = isset($jobs['employer_average_rating'])?$jobs['employer_average_rating']:'';
                $job_data["city"] = isset($jobs['job_city'])?$jobs['job_city']:'';
                $job_data["state"] = isset($jobs['job_state'])?$jobs['job_state']:'';
                $job_data["active"] = isset($jobs['active'])?$jobs['active']:'0';
                $job_data["weekly_taxable_amount"] = $job_data["hours_per_week"]*$job_data["actual_hourly_rate"];
                $job_data["employer_weekly_amount"] = $job_data["weekly_taxable_amount"]+$job_data["weekly_non_taxable_amount"];
                $job_data["goodwork_weekly_amount"] = $job_data["employer_weekly_amount"]*0.05;
                $job_data["total_employer_amount"] = ($job_data["preferred_assignment_duration"]*$job_data["employer_weekly_amount"])+$job_data["sign_on_bonus"]+$job_data["completion_bonus"];
                $job_data["total_goodwork_amount"] = $job_data["preferred_assignment_duration"]*$job_data["goodwork_weekly_amount"];
                $job_data["total_contract_amount"] = $job_data["total_employer_amount"]+$job_data["total_goodwork_amount"];
                $job_data["additional_terms"] = isset($jobs['additional_terms'])?$jobs['additional_terms']:'';
                $job_data['weekly_pay'] = $job_data["employer_weekly_amount"];
                if($job_data["hours_per_week"] == 0){
                    $job_data["feels_like_per_hour"] = 0;
                }else{
                    $job_data["feels_like_per_hour"] = $job_data["employer_weekly_amount"]/$job_data["hours_per_week"];
                }

                $this->check = "1";
                $this->message = "Job listed successfully";
                $this->return_data = $job_data;
            } else {
                $this->check = "0";
                $this->message = "Job not found";
                $this->return_data = 0;
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function removeDraftJob(Request $request)
    {
        if (
            isset($request->recruiter_id) && $request->recruiter_id != "" &&
            isset($request->job_id) && $request->job_id != "" &&
            isset($request->api_key) && $request->api_key != ""
        ) {
            $user = User::where('id', '=', $request->recruiter_id)->first();

            $product = Job::where('active', '0')->where('id', $request->job_id)->forceDelete();
            if(isset($product)){
                $this->check = "1";
                $this->message = "Draft Job removed successfully";
            }else{
                $this->message = "This Job not deleted";
            }

        } else {
            $this->message = $this->param_missing;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

     // Send offer Jobs Work
    public function sendOfferJob(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'job_id' => 'required',
            'recruiter_id' => 'required',
            'worker_user_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $update_array["job_id"] = isset($request->job_id)?$request->job_id:'';
            $update_array["recruiter_id"] = isset($request->recruiter_id)?$request->recruiter_id:'';
            $update_array["worker_user_id"] = isset($request->worker_user_id)?$request->worker_user_id:'';
            $update_array["job_name"] = isset($request->job_name)?$request->job_name:'';
            $update_array["type"] = isset($request->type)?$request->type:'';
            $update_array["compact"] = isset($request->compact)?$request->compact:'';
            $update_array["terms"] = isset($request->term)?$request->term:'';
            $update_array["profession"] = isset($request->profession)?$request->profession:'';
            $update_array["preferred_specialty"] = isset($request->specialty)?$request->specialty:'';
            $update_array["preferred_experience"] = isset($request->experience)?$request->experience:'';
            $update_array["job_location"] = isset($request->professional_licensure)?$request->professional_licensure:'';
            $update_array["vaccinations"] = isset($request->vaccinations)?$request->vaccinations:'';
            $update_array["number_of_references"] = isset($request->number_of_references)?$request->number_of_references:'';
            $update_array["min_title_of_reference"] = isset($request->min_title_of_reference)?$request->min_title_of_reference:'';
            $update_array["recency_of_reference"] = isset($request->recency_of_reference)?$request->recency_of_reference:'';
            $update_array["certificate"] = isset($request->certificate)?$request->certificate:'';
            $update_array["skills"] = isset($request->skills_checklist)?$request->skills_checklist:'';
            $update_array["urgency"] = isset($request->urgency)?$request->urgency:'';
            $update_array["position_available"] = isset($request->position_available)?$request->position_available:'';
            $update_array["msp"] = isset($request->msp)?$request->msp:'';
            $update_array["vms"] = isset($request->vms)?$request->vms:'';
            $update_array["submission_of_vms"] = isset($request->submission_of_vms)?$request->submission_of_vms:'';
            $update_array["block_scheduling"] = isset($request->block_scheduling)?$request->block_scheduling:'';
            $update_array["float_requirement"] = isset($request->float_requirement)?$request->float_requirement:'';
            $update_array["facility_shift_cancelation_policy"] = isset($request->facility_shift_cancelation_policy)?$request->facility_shift_cancelation_policy:'';
            $update_array["contract_termination_policy"] = isset($request->contract_termination_policy)?$request->contract_termination_policy:'';
            $update_array["traveler_distance_from_facility"] = isset($request->traveler_distance_from_facility)?$request->traveler_distance_from_facility:'';
            $update_array["facility"] = isset($request->facility)?$request->facility:'';
            $update_array["facility_id"] = isset($request->facility_id)?$request->facility_id:'';

            $update_array["clinical_setting"] = isset($request->clinical_setting)?$request->clinical_setting:'';
            $update_array["Patient_ratio"] = isset($request->Patient_ratio)?$request->Patient_ratio:'';
            $update_array["emr"] = isset($request->emr)?$request->emr:'';
            $update_array["Unit"] = isset($request->Unit)?$request->Unit:'';
            $update_array["Department"] = isset($request->Department)?$request->Department:'';
            $update_array["Bed_Size"] = isset($request->Bed_Size)?$request->Bed_Size:'';
            $update_array["Trauma_Level"] = isset($request->Trauma_Level)?$request->Trauma_Level:'';
            $update_array["scrub_color"] = isset($request->scrub_color)?$request->scrub_color:'';
            $update_array["start_date"] = isset($request->start_date)?$request->start_date:'';
            $update_array["as_soon_as"] = isset($request->as_soon_as)?$request->as_soon_as:'';
            $update_array["rto"] = isset($request->rto)?$request->rto:'';
            $update_array["preferred_shift"] = isset($request->preferred_shift)?$request->preferred_shift:'';
            $update_array["hours_per_week"] = isset($request->hours_per_week)?$request->hours_per_week:'';
            $update_array["guaranteed_hours"] = isset($request->guaranteed_hours)?$request->guaranteed_hours:'';
            $update_array["hours_shift"] = isset($request->hours_shift)?$request->hours_shift:'';
            $update_array["weeks_shift"] = isset($request->weeks_shift)?$request->weeks_shift:'';
            $update_array["preferred_assignment_duration"] = isset($request->preferred_assignment_duration)?$request->preferred_assignment_duration:'';
            $update_array["referral_bonus"] = isset($request->referral_bonus)?$request->referral_bonus:'';
            $update_array["sign_on_bonus"] = isset($request->sign_on_bonus)?$request->sign_on_bonus:'';
            $update_array["completion_bonus"] = isset($request->completion_bonus)?$request->completion_bonus:'';
            $update_array["extension_bonus"] = isset($request->extension_bonus)?$request->extension_bonus:'';
            $update_array["other_bonus"] = isset($request->other_bonus)?$request->other_bonus:'';
            $update_array["four_zero_one_k"] = isset($request->four_zero_one_k)?$request->four_zero_one_k:'';
            $update_array["health_insaurance"] = isset($request->health_insaurance)?$request->health_insaurance:'';
            $update_array["dental"] = isset($request->dental)?$request->dental:'';
            $update_array["vision"] = isset($request->vision)?$request->vision:'';
            $update_array["actual_hourly_rate"] = isset($request->actual_hourly_rate)?$request->actual_hourly_rate:'';
            $update_array["overtime"] = isset($request->overtime)?$request->overtime:'';
            $update_array["holiday"] = isset($request->holiday)?$request->holiday:'';
            $update_array["on_call"] = isset($request->on_call)?$request->on_call:'';
            $update_array["orientation_rate"] = isset($request->orientation_rate)?$request->orientation_rate:'';
            $update_array["weekly_non_taxable_amount"] = isset($request->weekly_non_taxable_amount)?$request->weekly_non_taxable_amount:'';
            $update_array["description"] = isset($request->description)?$request->description:'';
            $update_array["additional_terms"] = isset($request->additional_terms)?$request->additional_terms:'';

            $update_array["weekly_taxable_amount"] = 0;
            $update_array["employer_weekly_amount"] = 0;
            $update_array["goodwork_weekly_amount"] = 0;
            $update_array["total_employer_amount"] = 0;
            $update_array["total_goodwork_amount"] = 0;
            $update_array["total_contract_amount"] = 0;
            // $update_array["weekly_taxable_amount"] = $update_array["hours_per_week"]*$update_array["actual_hourly_rate"];
            // $update_array["employer_weekly_amount"] = $update_array["weekly_taxable_amount"]+$update_array["weekly_non_taxable_amount"];
            // $update_array["goodwork_weekly_amount"] = $update_array["employer_weekly_amount"]*0.05;
            // $update_array["total_employer_amount"] = ($update_array["preferred_assignment_duration"]*$update_array["employer_weekly_amount"])+$update_array["sign_on_bonus"]+$update_array["completion_bonus"];
            // $update_array["total_goodwork_amount"] = $update_array["preferred_assignment_duration"]*$update_array["goodwork_weekly_amount"];
            // $update_array["total_contract_amount"] = $update_array["total_employer_amount"]+$update_array["total_goodwork_amount"];
            if($update_array["hours_per_week"] == 0){
                $update_array["feels_like_per_hour"] = 0;
            }else{
                $update_array["feels_like_per_hour"] = $update_array["employer_weekly_amount"]/$update_array["hours_per_week"];
            }
            $update_array['weekly_pay'] = $update_array["employer_weekly_amount"];
            $update_array["facilitys_parent_system"] = isset($request->facilitys_parent_system)?$request->facilitys_parent_system:'';
            $update_array["facility_average_rating"] = isset($request->facility_average_rating)?$request->facility_average_rating:'';
            $update_array["recruiter_average_rating"] = isset($request->recruiter_average_rating)?$request->recruiter_average_rating:'';
            $update_array["employer_average_rating"] = isset($request->employer_average_rating)?$request->employer_average_rating:'';
            $update_array["job_city"] = isset($request->city)?$request->city:'';
            $update_array["job_state"] = isset($request->state)?$request->state:'';

            $check = DB::table('offer_jobs')->where(['job_id' => $request->job_id, 'worker_user_id' => $request->worker_user_id, 'recruiter_id' => $request->recruiter_id])->first();
            if(isset($check))
            {
                // $update_array["active"] = isset($request->active)?$request->active:$check->active;
                $update_array["is_draft"] = isset($request->is_draft)?$request->is_draft:$check->is_draft;
                $update_array["is_counter"] = isset($request->is_counter)?$request->is_counter:'0';
                $job = DB::table('offer_jobs')->where(['job_id' => $request->job_id, 'worker_user_id' => $request->worker_user_id, 'recruiter_id' => $request->recruiter_id])->update($update_array);
                if ($job) {
                    $this->check = "1";
                    if($update_array["is_draft"] == '0'){
                        $this->message = "Offer update successfully!";
                    }else{
                        $this->message = "Draft offer update successfully!";
                    }
                    $this->return_data = $job;
                } else {
                    $this->check = "0";
                    if($update_array["is_draft"] == '0'){
                        $this->message = "Offer not update successfully!";
                    }else{
                        $this->message = "Draft offer not update successfully!";
                    }
                }
            }else{
                /* create job */
                // $update_array["active"] = isset($request->active)?$request->active:'';
                $update_array["is_draft"] = isset($request->is_draft)?$request->is_draft:$check->is_draft;
                $update_array["is_counter"] = isset($request->is_counter)?$request->is_counter:'0';
                $update_array["created_by"] = (isset($request->recruiter_id) && $request->recruiter_id != "") ? $request->recruiter_id : "";
                $job = DB::table('offer_jobs')->insert($update_array);
                /* create job */
                if ($job) {
                    $this->check = "1";
                    if($update_array["is_draft"] == '0'){
                        $this->message = "Offer send successfully!";
                    }else{
                        $this->message = "Draft offer send successfully!";
                    }
                    $this->return_data = $job;
                } else {
                    $this->check = "0";
                    if($update_array["is_draft"] == '0'){
                        $this->message = "Offer not send successfully!";
                    }else{
                        $this->message = "Offer send successfully!";
                    }

                }
            }
            if($update_array["is_draft"] == '0'){
                $worker = Nurse::where('user_id', $request->worker_user_id)->first();
                $recruiter_info = User::where('id', $request->recruiter_id)->first();
                $checkOffer = Offer::where(['nurse_id' => $worker->id, 'job_id' => $request->job_id])->first();
                if (isset($checkOffer)) {
                    $offer = Offer::where(['nurse_id' => $worker->id, 'job_id' => $request->job_id])->update(['status' => 'Offered', 'start_date' => date('Y-m-d')]);
                } else {
                    $offer = Offer::create(['nurse_id' => $worker->id, 'created_by' => $worker->id, 'job_id' => $request->job_id,'status' => 'Offered', 'start_date' => date('Y-m-d') ]);
                }
                $offer_status = 'Offered';
                $check_notification = Notification::where(['job_id' => $request->job_id, 'recruiter_id' => $request->recruiter_id, 'isAskWorker' => '0'])->first();

                $text = 'Received a Offer for job name- '.$update_array["job_name"].' ('. $request->job_id .') by ';
                if(empty($check_notification)){

                    $check_notification_again = Notification::where(['job_id' => $request->job_id, 'created_by' => $request->worker_user_id, 'recruiter_id' => NULL, 'isAskWorker' => '0'])->first();
                    if(empty($check_notification_again)){
                        $notification = Notification::create(['job_id' => $request->job_id, 'created_by' => $request->worker_user_id, 'recruiter_id' => NULL, 'title' => 'Send Offer', 'text' => $text]);
                    }else{
                        if($update_array["is_draft"] == '0'){
                            $this->message = "Offer update successfully!";
                        }else{
                            $this->message = "Draft offer update successfully!";
                        }
                    }
                }else if(isset($check_notification)){
                    $notification = Notification::where('id', $check_notification->id)->update(['job_id' => $request->job_id, 'created_by' => $request->worker_user_id, 'recruiter_id' => NULL, 'title' => 'Send Offer', 'text' => $text, 'updated_at' => date('Y-m-d h:i:s')]);

                }
            }


        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getOfferJob(Request $request)
    {
        {
            $validator = \Validator::make($request->all(), [
                'api_key' => 'required',
                'job_id' => 'required',
                'recruiter_id' => 'required',
                'worker_user_id' => 'required'
            ]);
            if ($validator->fails()) {
                $this->message = $validator->errors()->first();
            } else {
                $job = DB::table('offer_jobs')->select('offer_jobs.*')->where(['job_id' => $request->job_id, 'is_draft' => '0', 'offer_jobs.is_counter' => '0', 'worker_user_id' => $request->worker_user_id])->first();

                if ($job) {
                    $this->check = "1";
                    $this->message = "Send Offer Job listed successfully";
                    $this->return_data = $job;
                } else {
                    $this->check = "0";
                    $this->message = "No Record Found";
                }
            }
            return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
        }
    }

    public function getOfferJoblist(Request $request)
    {
        {
            $validator = \Validator::make($request->all(), [
                'api_key' => 'required',
                'worker_user_id' => 'required'
            ]);
            if ($validator->fails()) {
                $this->message = $validator->errors()->first();
            } else {
                $job = DB::table('offer_jobs')->select('offer_jobs.worker_user_id as worker_user_id', 'offer_jobs.is_draft as is_draft', 'offer_jobs.recruiter_id as recruiter_id', 'offer_jobs.is_counter as is_counter', 'nurses.id as worker_id', 'users.first_name as worker_first_name', 'users.last_name as worker_last_name', 'users.image as worker_image', 'jobs.id as job_id', 'jobs.job_name as job_name', 'jobs.profession as profession', 'jobs.preferred_shift as preferred_shift', 'jobs.preferred_specialty as preferred_specialty', 'jobs.preferred_experience as preferred_experience', 'jobs.type as type', 'jobs.job_city as city', 'jobs.job_state as state', 'jobs.preferred_assignment_duration as preferred_assignment_duration', 'jobs.employer_weekly_amount as employer_weekly_amount', 'jobs.created_at as created_at')
                    ->leftJoin('jobs', 'offer_jobs.job_id', 'jobs.id')
                    ->leftJoin('users', 'offer_jobs.worker_user_id', 'users.id')
                    ->leftJoin('nurses', 'users.id', 'nurses.user_id')
                    ->leftJoin('offers', 'offers.nurse_id', 'nurses.id')
                    // ->where(['offer_jobs.is_draft' => '0', 'offer_jobs.is_counter' => '0',  'offer_jobs.worker_user_id' => $request->worker_user_id])
                    ->where(['offers.status' => 'Offered', 'offer_jobs.worker_user_id' => $request->worker_user_id])
                    ->get();
                foreach($job as $rec){
                    $rec->worker_image = isset($rec->worker_image)?url("public/images/nurses/profile/" . $rec->worker_image):'';
                    $rec->preferred_experience = isset($rec->preferred_experience)?$rec->preferred_experience.' Years of Experience':'';
                    $rec->specialty = $rec->preferred_specialty;
                    $rec->experience = $rec->preferred_experience;
                    $rec->created_at_definition = isset($rec->created_at)?date('F d Y', strtotime($rec->created_at)):'';
                }

                if ($job) {
                    $this->check = "1";
                    $this->message = "Send Offer Job listed successfully";
                    $this->return_data = $job;
                } else {
                    $this->check = "0";
                    $this->message = "No Record Found";
                }
            }
            return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
        }
    }

    public function getDraftOfferJob(Request $request)
    {
        {
            $validator = \Validator::make($request->all(), [
                'api_key' => 'required',
                'job_id' => 'required',
                'recruiter_id' => 'required',
                'worker_user_id' => 'required'
            ]);
            if ($validator->fails()) {
                $this->message = $validator->errors()->first();
            } else {
                $job = DB::table('offer_jobs')->select('offer_jobs.*')->where(['job_id' => $request->job_id, 'is_draft' => '1', 'worker_user_id' => $request->worker_user_id])->first();

                if ($job) {
                    $this->check = "1";
                    $this->message = "Send draft Offer Job listed successfully";
                    $this->return_data = $job;
                } else {
                    $this->check = "0";
                    $this->message = "No Record Found";
                }
            }
            return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
        }
    }

    public function workerGetOfferJob(Request $request)
    {
        {
            $validator = \Validator::make($request->all(), [
                'api_key' => 'required',
                'job_id' => 'required',
                'recruiter_id' => 'required',
                'worker_user_id' => 'required'
            ]);
            if ($validator->fails()) {
                $this->message = $validator->errors()->first();
            } else {
                $this->return_data = [];
                $job = DB::table('offer_jobs')->select('offer_jobs.*')->where(['job_id' => $request->job_id, 'is_draft' => '0', 'worker_user_id' => $request->worker_user_id])->first();
                $job_data = Job::where(['id' => $request->job_id, 'active' => '1', 'is_closed' => '0', 'is_hidden' => '0'])->first();
                $recruiter = USER::where('id', $request->recruiter_id)->first();
                $worker = USER::where('id', $request->worker_user_id)->first();
                $nurse = Nurse::where('user_id', $request->worker_user_id)->first();
                $offer = Offer::where(['nurse_id' => $nurse->id, 'job_id' => $request->job_id])->first();
                if(isset($job_data['facility_id'])){
                    $facility = Facility::where('id', $job_data['facility_id'])->first();
                }
                $facility_name = isset($job_data['facility'])?$job_data['facility']:'';
                $worker_name = isset($worker['fullName'])?$worker['fullName']:'';
                $recruiter_name = isset($recruiter['fullName'])?$recruiter['fullName']:'';
                $result = [];
                $worker_info = [];
                $popup_info = [];
                $result['job_id'] = isset($job_data['id'])?$job_data['id']:"";
                $result['description'] = isset($job_data['description'])?$job_data['description']:"";
                $result['worker_name'] = $worker_name;
                $result['recruiter_name'] = $recruiter_name;
                $result['facility_name'] = $facility_name;
                $result['job_id'] = isset($job_data['id'])?$job_data['id']:'';
                $result['offer_id'] = $offer['id'];
                $result['recruiter_id'] = $request->recruiter_id;
                $result['offer_valid'] = isset($job_data['preferred_assignment_duration'])?$job_data['preferred_assignment_duration'].' Weeks':'';

                if(isset($job)){
                    $popup = [];
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(isset($job->type) && !empty($job->type) && ($job->type != $job_data['type']))
                    {
                        $popup['name'] = 'Type';
                        $popup['value'] = $job->type;
                        $jobs["name"] = 'Type';
                        $jobs["job"] = $job->type;
                    }else{
                        $jobs["name"] = 'Type';
                        $jobs["job"] = $job_data['type'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->terms != $job_data['terms']) && isset($job->terms) && !empty($job->terms))
                    {
                        $popup['name'] = 'Terms';
                        $popup['value'] = $job->terms;
                        $jobs["name1"] = 'Terms';
                        $jobs["job1"] = $job->terms;
                    }else{
                        $jobs["name1"] = 'Terms';
                        $jobs["job1"] = $job_data['terms'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->profession != $job_data['profession']) && isset($job->profession) && !empty($job->profession))
                    {
                        $popup['name'] = 'Profession';
                        $popup['value'] = $job->profession;
                        $jobs["name"] = 'Profession';
                        $jobs["job"] = $job->profession;
                    }else{
                        $jobs["name"] = 'Profession';
                        $jobs["job"] = $job_data['profession'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->preferred_specialty != $job_data['preferred_specialty']) && isset($job->preferred_specialty) && !empty($job->preferred_specialty))
                    {
                        $popup['name'] = 'Specialty';
                        $popup['value'] = $job->preferred_specialty;
                        $jobs["name1"] = 'Specialty';
                        $jobs["job1"] = $job->preferred_specialty;
                    }else{
                        $jobs["name1"] = 'Specialty';
                        $jobs["job1"] = $job_data['preferred_specialty'];
                    }
                    $popup_info[] = $popup;
                    $worker_info[] = $jobs;
                    $popup['name'] = '';
                    $popup['value'] = '';

                    if(($job->compact != $job_data['compact']) && isset($job->compact) && !empty($job->compact))
                    {
                        $popup['name'] = 'Compact';
                        $popup['value'] = $job->compact;
                        $jobs["compact"] = $job->compact;
                    }else{
                        $jobs["compact"] = $job_data['compact'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->job_location != $job_data['job_location']) && isset($job->job_location) && !empty($job->job_location))
                    {
                        $popup['name'] = 'Professional Licensure';
                        $popup['value'] = $job->preferred_experience;
                        $jobs["name"] = 'Professional Licensure';
                        $jobs["job"] = $job->job_location;
                    }else{
                        $jobs["name"] = 'Professional Licensure';
                        $jobs["job"] = $job_data['job_location'].','.$jobs["compact"];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->preferred_experience != $job_data['preferred_experience']) && isset($job->preferred_experience) && !empty($job->preferred_experience))
                    {
                        $popup['name'] = 'Experience';
                        $popup['value'] = $job->preferred_experience;
                        $jobs["name1"] = 'Experience';
                        $jobs["job1"] = $job->preferred_experience;
                    }else{
                        $jobs["name1"] = 'Experience';
                        $jobs["job1"] = $job_data['preferred_experience'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';

                    if(($job->number_of_references != $job_data['number_of_references']) && isset($job->number_of_references) && !empty($job->number_of_references))
                    {
                        $popup['name'] = 'Number Of References';
                        $popup['value'] = $job->number_of_references;
                        $jobs["name"] = 'Number Of References';
                        $jobs["job"] = $job->number_of_references;
                    }else{
                        $jobs["name"] = 'Number Of References';
                        $jobs["job"] = $job_data['number_of_references'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->min_title_of_reference != $job_data['min_title_of_reference']) && isset($job->min_title_of_reference) && !empty($job->min_title_of_reference))
                    {
                        $popup['name'] = 'Min Title Of References';
                        $popup['value'] = $job->min_title_of_reference;
                        $jobs["name1"] = 'Min Title Of References';
                        $jobs["job1"] = $job->min_title_of_reference;
                    }else{
                        $jobs["name1"] = 'Min Title Of References';
                        $jobs["job1"] = $job_data['min_title_of_reference'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->recency_of_reference != $job_data['recency_of_reference']) && isset($job->recency_of_reference) && !empty($job->recency_of_reference))
                    {
                        $popup['name'] = 'Recency Of Reference';
                        $popup['value'] = $job->recency_of_reference;
                        $jobs["name"] = 'Recency Of Reference';
                        $jobs["job"] = $job->recency_of_reference;
                    }else{
                        $jobs["name"] = 'Recency Of Reference';
                        $jobs["job"] = $job_data['recency_of_reference'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->skills != $job_data['skills']) && isset($job->skills) && !empty($job->skills))
                    {
                        $popup['name'] = 'Skills Checklist';
                        $popup['value'] = $job->skills;
                        $jobs["name1"] = 'Skills Checklist';
                        $jobs["job1"] = $job->skills;
                    }else{
                        $jobs["name1"] = 'Skills Checklist';
                        $jobs["job1"] = $job_data['skills'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->position_available != $job_data['position_available']) && isset($job->position_available) && !empty($job->position_available))
                    {
                        $popup['name'] = '# Possition Available';
                        $popup['value'] = $job->position_available;
                        $jobs["name"] = '# Possition Available';
                        $jobs["job"] = $job->position_available;
                    }else{
                        $jobs["name"] = '# Possition Available';
                        $jobs["job"] = $job_data['position_available'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->msp != $job_data['msp']) && isset($job->msp) && !empty($job->msp))
                    {
                        $popup['name'] = 'MSP';
                        $popup['value'] = $job->msp;
                        $jobs["name1"] = 'MSP';
                        $jobs["job1"] = $job->msp;
                    }else{
                        $jobs["name1"] = 'MSP';
                        $jobs["job1"] = $job_data['msp'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->vms != $job_data['vms']) && isset($job->vms) && !empty($job->vms))
                    {
                        $popup['name'] = 'VMS';
                        $popup['value'] = $job->vms;
                        $jobs["name"] = 'VMS';
                        $jobs["job"] = $job->vms;
                    }else{
                        $jobs["name"] = 'VMS';
                        $jobs["job"] = $job_data['vms'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->submission_of_vms != $job_data['submission_of_vms']) && isset($job->submission_of_vms) && !empty($job->submission_of_vms))
                    {
                        $popup['name'] = '# of Submission in VMS';
                        $popup['value'] = $job->submission_of_vms;
                        $jobs["name1"] = '# of Submission in VMS';
                        $jobs["job1"] = $job->submission_of_vms;
                    }else{
                        $jobs["name1"] = '# of Submission in VMS';
                        $jobs["job1"] = $job_data['submission_of_vms'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->block_scheduling != $job_data['block_scheduling']) && isset($job->block_scheduling) && !empty($job->block_scheduling))
                    {
                        $popup['name'] = 'Block Scheduling';
                        $popup['value'] = $job->block_scheduling;
                        $jobs["name"] = 'Block Scheduling';
                        $jobs["job"] = $job->block_scheduling;
                    }else{
                        $jobs["name"] = 'Block Scheduling';
                        $jobs["job"] = $job_data['block_scheduling'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->facility_shift_cancelation_policy != $job_data['facility_shift_cancelation_policy']) && isset($job->facility_shift_cancelation_policy) && !empty($job->facility_shift_cancelation_policy))
                    {
                        $popup['name'] = 'Facility Shift Cancelation Policy';
                        $popup['value'] = $job->facility_shift_cancelation_policy;
                        $jobs["name1"] = 'Facility Shift Cancelation Policy';
                        $jobs["job1"] = $job->facility_shift_cancelation_policy;
                    }else{
                        $jobs["name1"] = 'Facility Shift Cancelation Policy';
                        $jobs["job1"] = $job_data['facility_shift_cancelation_policy'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;

                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->contract_termination_policy != $job_data['contract_termination_policy']) && isset($job->contract_termination_policy) && !empty($job->contract_termination_policy))
                    {
                        $popup['name'] = 'Contract Termination Policy';
                        $popup['value'] = $job->contract_termination_policy;
                        $jobs["name"] = 'Contract Termination Policy';
                        $jobs["job"] = $job->contract_termination_policy;
                    }else{
                        $jobs["name"] = 'Contract Termination Policy';
                        $jobs["job"] = $job_data['contract_termination_policy'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->traveler_distance_from_facility != $job_data['traveler_distance_from_facility']) && isset($job->traveler_distance_from_facility) && !empty($job->traveler_distance_from_facility))
                    {
                        $popup['name'] = 'Traveler Distance From Facility';
                        $popup['value'] = $job->traveler_distance_from_facility;
                        $jobs["name1"] = 'Traveler Distance From Facility';
                        $jobs["job1"] = $job->traveler_distance_from_facility;
                    }else{
                        $jobs["name1"] = 'Traveler Distance From Facility';
                        $jobs["job1"] = $job_data['traveler_distance_from_facility'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->facility != $job_data['facility']) && isset($job->facility) && !empty($job->facility))
                    {
                        $popup['name'] = 'Facility';
                        $popup['value'] = $job->facility;
                        $jobs["name"] = 'Facility';
                        $jobs["job"] = $job->facility;
                    }else{
                        $jobs["name"] = 'Facility';
                        $jobs["job"] = $job_data['facility'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->facilitys_parent_system != $job_data['facilitys_parent_system']) && isset($job->facilitys_parent_system) && !empty($job->facilitys_parent_system))
                    {
                        $popup['name'] = "Facility's Parent System";
                        $popup['value'] = $job->facilitys_parent_system;
                        $jobs["name1"] = "Facility's Parent System";
                        $jobs["job1"] = $job->facilitys_parent_system;
                    }else{
                        $jobs["name1"] = "Facility's Parent System";
                        $jobs["job1"] = $job_data['facilitys_parent_system'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->facility_average_rating != $job_data['facility_average_rating']) && isset($job->facility_average_rating) && !empty($job->facility_average_rating))
                    {
                        $popup['name'] = "Facility Average Rating";
                        $popup['value'] = $job->facility_average_rating;
                        $jobs["name"] = "Facility Average Rating";
                        $jobs["job"] = $job->facility_average_rating;
                    }else{
                        $jobs["name"] = "Facility Average Rating";
                        $jobs["job"] = $job_data['facility_average_rating'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->recruiter_average_rating != $job_data['recruiter_average_rating']) && isset($job->recruiter_average_rating) && !empty($job->recruiter_average_rating))
                    {
                        $popup['name'] = "Recruiter Average Rating";
                        $popup['value'] = $job->recruiter_average_rating;
                        $jobs["name1"] = "Recruiter Average Rating";
                        $jobs["job1"] = isset($job->recruiter_average_rating)?$job->recruiter_average_rating:'';
                    }else{
                        $jobs["name1"] = "Recruiter Average Rating";
                        $jobs["job1"] = isset($job_data['recruiter_average_rating'])?$job_data['recruiter_average_rating']:'';
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->employer_average_rating != $job_data['employer_average_rating']) && isset($job->employer_average_rating) && !empty($job->employer_average_rating))
                    {
                        $popup['name'] = "Employer Average Rating";
                        $popup['value'] = $job->employer_average_rating;
                        $jobs["name"] = "Employer Average Rating";
                        $jobs["job"] = isset($job->employer_average_rating)?$job->employer_average_rating:'';
                    }else{
                        $jobs["name"] = "Employer Average Rating";
                        $jobs["job"] = isset($job_data['employer_average_rating'])?$job_data['employer_average_rating']:'';
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->clinical_setting != $job_data['clinical_setting']) && isset($job->clinical_setting) && !empty($job->clinical_setting))
                    {
                        $popup['name'] = "Clinical Setting";
                        $popup['value'] = $job->clinical_setting;
                        $jobs["name1"] = "Clinical Setting";
                        $jobs["job1"] = $job->clinical_setting;
                    }else{
                        $jobs["name1"] = "Clinical Setting";
                        $jobs["job1"] = $job_data['clinical_setting'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->Patient_ratio != $job_data['Patient_ratio']) && isset($job->Patient_ratio) && !empty($job->Patient_ratio))
                    {
                        $popup['name'] = "Patient Ratio";
                        $popup['value'] = $job->Patient_ratio;
                        $jobs["name"] = "Patient Ratio";
                        $jobs["job"] = $job->Patient_ratio;
                    }else{
                        $jobs["name1"] = "Patient Ratio";
                        $jobs["job"] = $job_data['Patient_ratio'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->emr != $job_data['emr']) && isset($job->emr) && !empty($job->emr))
                    {
                        $popup['name'] = "EMR";
                        $popup['value'] = $job->emr;
                        $jobs["name1"] = "EMR";
                        $jobs["job1"] = $job->emr;
                    }else{
                        $jobs["name1"] = "EMR";
                        $jobs["job1"] = $job_data['emr'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->Unit != $job_data['Unit']) && isset($job->Unit) && !empty($job->Unit))
                    {
                        $popup['name'] = "Unit";
                        $popup['value'] = $job->Unit;
                        $jobs["name"] = "Unit";
                        $jobs["job"] = $job->Unit;
                    }else{
                        $jobs["name"] = "Unit";
                        $jobs["job"] = $job_data['Unit'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->Department != $job_data['Department']) && isset($job->Department) && !empty($job->Department))
                    {
                        $popup['name'] = "Department";
                        $popup['value'] = $job->Department;
                        $jobs["name1"] = "Department";
                        $jobs["job1"] = $job->Department;
                    }else{
                        $jobs["name1"] = "Department";
                        $jobs["job1"] = $job_data['Department'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->Bed_Size != $job_data['Bed_Size']) && isset($job->Bed_Size) && !empty($job->Bed_Size))
                    {
                        $popup['name'] = "Bed Size";
                        $popup['value'] = $job->Bed_Size;
                        $jobs["name"] = "Bed Size";
                        $jobs["job"] = $job->Bed_Size;
                    }else{
                        $jobs["name"] = "Bed Size";
                        $jobs["job"] = $job_data['Bed_Size'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->Trauma_Level != $job_data['Trauma_Level']) && isset($job->Trauma_Level) && !empty($job->Trauma_Level))
                    {
                        $popup['name'] = "Trauma Level";
                        $popup['value'] = $job->Trauma_Level;
                        $jobs["name1"] = "Trauma Level";
                        $jobs["job1"] = $job->Trauma_Level;
                    }else{
                        $jobs["name1"] = "Trauma Level";
                        $jobs["job1"] = $job_data['Trauma_Level'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->scrub_color != $job_data['scrub_color']) && isset($job->scrub_color) && !empty($job->scrub_color))
                    {
                        $popup['name'] = "Scrub Color";
                        $popup['value'] = $job->scrub_color;
                        $jobs["name"] = "Scrub Color";
                        $jobs["job"] = $job->scrub_color;
                    }else{
                        $jobs["name"] = "Scrub Color";
                        $jobs["job"] = $job_data['scrub_color'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->job_city != $job_data['job_city']) && isset($job->job_city) && !empty($job->job_city))
                    {
                        $popup['name'] = "Facility City";
                        $popup['value'] = $job->job_city;
                        $jobs["name1"] = "Facility City";
                        $jobs["job1"] = $job->job_city;
                    }else{
                        $jobs["name1"] = "Facility City";
                        $jobs["job1"] = $job_data['job_city'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->job_state != $job_data['job_state']) && isset($job->job_state) && !empty($job->job_state))
                    {
                        $popup['name'] = "Facility State Code";
                        $popup['value'] = $job->job_state;
                        $jobs["name1"] = "Facility State Code";
                        $jobs["job1"] = $job->job_state;
                    }else{
                        $jobs["name1"] = "Facility State Code";
                        $jobs["job1"] = $job_data['job_state'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->start_date != $job_data['start_date']) && isset($job->start_date) && !empty($job->start_date))
                    {
                        $popup['name'] = "Start Date";
                        $popup['value'] = $job->start_date;
                        $jobs["name1"] = "Start Date";
                        $jobs["job1"] = $job->start_date;
                    }else{
                        $jobs["name1"] = "Start Date";
                        $jobs["job1"] = $job_data['start_date'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->rto != $job_data['rto']) && isset($job->rto) && !empty($job->rto))
                    {
                        $popup['name'] = "RTO";
                        $popup['value'] = $job->rto;
                        $jobs["name"] = "RTO";
                        $jobs["job"] = $job->rto;
                    }else{
                        $jobs["name"] = "RTO";
                        $jobs["job"] = $job_data['rto'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->preferred_shift != $job_data['preferred_shift']) && isset($job->preferred_shift) && !empty($job->preferred_shift))
                    {
                        $popup['name'] = "Shift Time Of Day";
                        $popup['value'] = $job->preferred_shift;
                        $jobs["name1"] = "Shift Time Of Day";
                        $jobs["job1"] = $job->preferred_shift;
                    }else{
                        $jobs["name1"] = "Shift Time Of Day";
                        $jobs["job1"] = $job_data['preferred_shift'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->hours_per_week != $job_data['hours_per_week']) && isset($job->hours_per_week) && !empty($job->hours_per_week))
                    {
                        $popup['name'] = "Hours/Week";
                        $popup['value'] = $job->hours_per_week;
                        $jobs["name"] = "Hours/Week";
                        $jobs["job"] = $job->hours_per_week;
                    }else{
                        $jobs["name"] = "Hours/Week";
                        $jobs["job"] = $job_data['hours_per_week'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->guaranteed_hours != $job_data['guaranteed_hours']) && isset($job->guaranteed_hours) && !empty($job->guaranteed_hours))
                    {
                        $popup['name'] = "Gauranteed Hours";
                        $popup['value'] = $job->guaranteed_hours;
                        $jobs["name1"] = "Gauranteed Hours";
                        $jobs["job1"] = $job->guaranteed_hours;
                    }else{
                        $jobs["name1"] = "Gauranteed Hours";
                        $jobs["job1"] = $job_data['guaranteed_hours'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->hours_shift != $job_data['hours_shift']) && isset($job->hours_shift) && !empty($job->hours_shift))
                    {
                        $popup['name'] = "Hours/Shift";
                        $popup['value'] = $job->hours_shift;
                        $jobs["name"] = "Hours/Shift";
                        $jobs["job"] = $job->hours_shift;
                    }else{
                        $jobs["name"] = "Hours/Shift";
                        $jobs["job"] = $job_data['hours_shift'];
                    }
                    if(($job->preferred_assignment_duration != $job_data['preferred_assignment_duration']) && isset($job->preferred_assignment_duration) && !empty($job->preferred_assignment_duration))
                    {
                        $popup['name'] = "Weeks/Assignment";
                        $popup['value'] = $job->preferred_assignment_duration;
                        $jobs["name1"] = "Weeks/Assignment";
                        $jobs["job1"] = $job->preferred_assignment_duration;
                    }else{
                        $jobs["name1"] = "Weeks/Assignment";
                        $jobs["job1"] = $job_data['preferred_assignment_duration'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->weeks_shift != $job_data['weeks_shift']) && isset($job->weeks_shift) && !empty($job->weeks_shift))
                    {
                        $popup['name'] = "Shifts/Week";
                        $popup['value'] = $job->weeks_shift;
                        $jobs["name"] = "Shifts/Week";
                        $jobs["job"] = $job->weeks_shift;
                    }else{
                        $jobs["name"] = "Shifts/Week";
                        $jobs["job"] = $job_data['weeks_shift'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->referral_bonus != $job_data['referral_bonus']) && isset($job->referral_bonus) && !empty($job->referral_bonus))
                    {
                        $popup['name'] = "Referral Bonus";
                        $popup['value'] = $job->referral_bonus;
                        $jobs["name1"] = "Referral Bonus";
                        $jobs["job1"] = $job->referral_bonus;
                    }else{
                        $jobs["name1"] = "Referral Bonus";
                        $jobs["job1"] = $job_data['referral_bonus'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->sign_on_bonus != $job_data['sign_on_bonus']) && isset($job->sign_on_bonus) && !empty($job->sign_on_bonus))
                    {
                        $popup['name'] = "Sign-On Bonus";
                        $popup['value'] = $job->sign_on_bonus;
                        $jobs["name"] = "Sign-On Bonus";
                        $jobs["job"] = $job->sign_on_bonus;
                    }else{
                        $jobs["name"] = "Sign-On Bonus";
                        $jobs["job"] = $job_data['sign_on_bonus'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->completion_bonus != $job_data['completion_bonus']) && isset($job->completion_bonus) && !empty($job->completion_bonus))
                    {
                        $popup['name'] = "Completion Bonus";
                        $popup['value'] = $job->completion_bonus;
                        $jobs["name1"] = "Completion Bonus";
                        $jobs["job1"] = $job->completion_bonus;
                    }else{
                        $jobs["name1"] = "Completion Bonus";
                        $jobs["job1"] = $job_data['completion_bonus'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->extension_bonus != $job_data['extension_bonus']) && isset($job->extension_bonus) && !empty($job->extension_bonus))
                    {
                        $popup['name'] = "Extension Bonus";
                        $popup['value'] = $job->extension_bonus;
                        $jobs["name"] = "Extension Bonus";
                        $jobs["job"] = $job->extension_bonus;
                    }else{
                        $jobs["name"] = "Extension Bonus";
                        $jobs["job"] = $job_data['extension_bonus'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->other_bonus != $job_data['other_bonus']) && isset($job->other_bonus) && !empty($job->other_bonus))
                    {
                        $popup['name'] = "Other Bonus";
                        $popup['value'] = $job->other_bonus;
                        $jobs["name1"] = "Other Bonus";
                        $jobs["job1"] = $job->other_bonus;
                    }else{
                        $jobs["name1"] = "Other Bonus";
                        $jobs["job1"] = $job_data['other_bonus'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';

                    if(($job->four_zero_one_k != $job_data['four_zero_one_k']) && isset($job->four_zero_one_k) && !empty($job->four_zero_one_k))
                    {
                        $popup['name'] = "401K";
                        $popup['value'] = $job->four_zero_one_k;
                        $jobs["name"] = "401K";
                        $jobs["job"] = $job->four_zero_one_k;
                    }else{
                        $jobs["name"] = "401K";
                        $jobs["job"] = $job_data['four_zero_one_k'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->health_insaurance != $job_data['health_insaurance']) && isset($job->health_insaurance) && !empty($job->health_insaurance))
                    {
                        $popup['name'] = "Health Insaurance";
                        $popup['value'] = $job->health_insaurance;
                        $jobs["name1"] = "Health Insaurance";
                        $jobs["job1"] = $job->health_insaurance;
                    }else{
                        $jobs["name1"] = "Health Insaurance";
                        $jobs["job1"] = $job_data['health_insaurance'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->dental != $job_data['dental']) && isset($job->dental) && !empty($job->dental))
                    {
                        $popup['name'] = "Dental";
                        $popup['value'] = $job->dental;
                        $jobs["name"] = "Dental";
                        $jobs["job"] = $job->dental;
                    }else{
                        $jobs["name"] = "Dental";
                        $jobs["job"] = $job_data['dental'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->vision != $job_data['vision']) && isset($job->vision) && !empty($job->vision))
                    {
                        $popup['name'] = "Vision";
                        $popup['value'] = $job->vision;
                        $jobs["name1"] = "Vision";
                        $jobs["job1"] = $job->vision;
                    }else{
                        $jobs["name1"] = "Vision";
                        $jobs["job1"] = $job_data['vision'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->actual_hourly_rate != $job_data['actual_hourly_rate']) && isset($job->actual_hourly_rate) && !empty($job->actual_hourly_rate))
                    {
                        $popup['name'] = "Actual Hourly Rate";
                        $popup['value'] = $job->actual_hourly_rate;
                        $jobs["name"] = "Actual Hourly Rate";
                        $jobs["job"] = $job->actual_hourly_rate;
                    }else{
                        $jobs["name"] = "Actual Hourly Rate";
                        $jobs["job"] = $job_data['actual_hourly_rate'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->feels_like_per_hour != $job_data['feels_like_per_hour']) && isset($job->feels_like_per_hour) && !empty($job->feels_like_per_hour))
                    {
                        $popup['name'] = "Feels Like $/hr";
                        $popup['value'] = $job->feels_like_per_hour;
                        $jobs["name1"] = "Feels Like $/hr";
                        $jobs["job1"] = $job->feels_like_per_hour;
                    }else{
                        $jobs["name1"] = "Feels Like $/hr";
                        $jobs["job1"] = $job_data['feels_like_per_hour'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->overtime != $job_data['overtime']) && isset($job->overtime) && !empty($job->overtime))
                    {
                        $popup['name'] = "Overtime";
                        $popup['value'] = $job->overtime;
                        $jobs["name"] = "Overtime";
                        $jobs["job"] = $job->overtime;
                    }else{
                        $jobs["name"] = "Overtime";
                        $jobs["job"] = $job_data['overtime'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->holiday != $job_data['holiday']) && isset($job->holiday) && !empty($job->holiday))
                    {
                        $popup['name'] = "Holiday";
                        $popup['value'] = $job->holiday;
                        $jobs["name1"] = "Holiday";
                        $jobs["job1"] = $job->holiday;
                    }else{
                        $jobs["name1"] = "Holiday";
                        $jobs["job1"] = $job_data['holiday'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->on_call != $job_data['on_call']) && isset($job->on_call) && !empty($job->on_call))
                    {
                        $popup['name'] = "On Call";
                        $popup['value'] = $job->on_call;
                        $jobs["name"] = "On Call";
                        $jobs["job"] = $job->on_call;
                    }else{
                        $jobs["name"] = "On Call";
                        $jobs["job"] = $job_data['on_call'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->call_back != $job_data['call_back']) && isset($job->call_back) && !empty($job->call_back))
                    {
                        $popup['name'] = "Call Back";
                        $popup['value'] = $job->call_back;
                        $jobs["name1"] = "Call Back";
                        $jobs["job1"] = $job->call_back;
                    }else{
                        $jobs["name1"] = "Call Back";
                        $jobs["job1"] = $job_data['call_back'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->orientation_rate != $job_data['orientation_rate']) && isset($job->orientation_rate) && !empty($job->orientation_rate))
                    {
                        $popup['name'] = "Orientation Rate";
                        $popup['value'] = $job->orientation_rate;
                        $jobs["name"] = "Orientation Rate";
                        $jobs["job"] = $job->orientation_rate;
                    }else{
                        $jobs["name"] = "Orientation Rate";
                        $jobs["job"] = $job_data['orientation_rate'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->weekly_taxable_amount != $job_data['weekly_taxable_amount']) && isset($job->weekly_taxable_amount) && !empty($job->weekly_taxable_amount))
                    {
                        $popup['name'] = "Weekly Taxable Amount";
                        $popup['value'] = $job->weekly_taxable_amount;
                        $jobs["name1"] = "Weekly Taxable Amount";
                        $jobs["job1"] = $job->weekly_taxable_amount;
                    }else{
                        $jobs["name1"] = "Weekly Taxable Amount";
                        $jobs["job1"] = $job_data['weekly_taxable_amount'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->weekly_non_taxable_amount != $job_data['weekly_non_taxable_amount']) && isset($job->weekly_non_taxable_amount) && !empty($job->weekly_non_taxable_amount))
                    {
                        $popup['name'] = "Weekly Non Taxable Amount";
                        $popup['value'] = $job->weekly_non_taxable_amount;
                        $jobs["name"] = "Weekly Non Taxable Amount";
                        $jobs["job"] = $job->weekly_non_taxable_amount;
                    }else{
                        $jobs["name"] = "Weekly Non Taxable Amount";
                        $jobs["job"] = $job_data['weekly_non_taxable_amount'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->employer_weekly_amount != $job_data['employer_weekly_amount']) && isset($job->employer_weekly_amount) && !empty($job->employer_weekly_amount))
                    {
                        $popup['name'] = "Employer Weekly Amount";
                        $popup['value'] = $job->employer_weekly_amount;
                        $jobs["name1"] = "Employer Weekly Amount";
                        $jobs["job1"] = $job->employer_weekly_amount;
                    }else{
                        $jobs["name1"] = "Employer Weekly Amount";
                        $jobs["job1"] = $job_data['employer_weekly_amount'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->goodwork_weekly_amount != $job_data['goodwork_weekly_amount']) && isset($job->goodwork_weekly_amount) && !empty($job->goodwork_weekly_amount))
                    {
                        $popup['name'] = "Goodwork Weekly Amount";
                        $popup['value'] = $job->goodwork_weekly_amount;
                        $jobs["name"] = "Goodwork Weekly Amount";
                        $jobs["job"] = $job->goodwork_weekly_amount;
                    }else{
                        $jobs["name"] = "Goodwork Weekly Amount";
                        $jobs["job"] = $job_data['goodwork_weekly_amount'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->total_employer_amount != $job_data['total_employer_amount']) && isset($job->total_employer_amount) && !empty($job->total_employer_amount))
                    {
                        $popup['name'] = "Total Employer Amount";
                        $popup['value'] = $job->total_employer_amount;
                        $jobs["name1"] = "Total Employer Amount";
                        $jobs["job1"] = $job->total_employer_amount;
                    }else{
                        $jobs["name1"] = "Total Employer Amount";
                        $jobs["job1"] = $job_data['total_employer_amount'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->total_goodwork_amount != $job_data['total_goodwork_amount']) && isset($job->total_goodwork_amount) && !empty($job->total_goodwork_amount))
                    {
                        $popup['name'] = "Total Goodwork Amount";
                        $popup['value'] = $job->total_goodwork_amount;
                        $jobs["name"] = "Total Goodwork Amount";
                        $jobs["job"] = $job->total_goodwork_amount;
                    }else{
                        $jobs["name"] = "Total Goodwork Amount";
                        $jobs["job"] = $job_data['total_goodwork_amount'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->total_contract_amount != $job_data['total_contract_amount']) && isset($job->total_contract_amount) && !empty($job->total_contract_amount))
                    {
                        $popup['name'] = "Total Contract Amount";
                        $popup['value'] = $job->total_contract_amount;
                        $jobs["name1"] = "Total Contract Amount";
                        $jobs["job1"] = $job->total_contract_amount;
                    }else{
                        $jobs["name1"] = "Total Contract Amount";
                        $jobs["job1"] = $job_data['total_contract_amount'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';

                    if(($job->goodwork_number != $job_data['goodwork_number']) && isset($job->goodwork_number) && !empty($job->goodwork_number))
                    {
                        $popup['name'] = "Goodwork Number";
                        $popup['value'] = $job->goodwork_number;
                        $jobs["name"] = "Goodwork Number";
                        $jobs["job"] = $job->goodwork_number;
                    }else{
                        $jobs["name"] = "Goodwork Number";
                        $jobs["job"] = $job_data['goodwork_number'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(isset($job->additional_terms) && !empty($job->additional_terms))
                    {
                        $popup['name'] = "Additional Terms";
                        $popup['value'] = $job->additional_terms;
                        $jobs["name1"] = "Additional Terms";
                        $jobs["job1"] = $job->additional_terms;
                    }else{
                        $jobs["name1"] = "Additional Terms";
                        $jobs["job1"] = "";
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    $jobs["name1"] = '';
                    $jobs["job1"] = '';
                    if(($job->vaccinations != $job_data['vaccinations']) && isset($job->vaccinations) && !empty($job->vaccinations))
                    {
                        $popup['name'] = 'Vaccinations & Immunizations';
                        $popup['value'] = $job->vaccinations;
                        $jod_details = explode(',', $job->vaccinations);
                        foreach($jod_details as $rec){
                            $jobs["name"] = 'Vaccinations & Immunizations';
                            $jobs["job"] = $rec;
                            $worker_info[] = $jobs;
                        }
                    }else{
                        $jod_details = explode(',', $job_data['vaccinations']);
                        foreach($jod_details as $rec){
                            $jobs["name"] = 'Vaccinations & Immunizations';
                            $jobs["job"] = $rec;
                            $worker_info[] = $jobs;
                        }
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';

                    if(($job->certificate != $job_data['certificate']) && isset($job->certificate) && !empty($job->certificate))
                    {
                        $popup['name'] = 'Certification';
                        $popup['value'] = $job->certificate;
                        $jod_details = explode(',', $job->certificate);
                        foreach($jod_details as $rec){
                            $jobs["name"] = 'Certification';
                            $jobs["job"] = $rec;
                            $worker_info[] = $jobs;
                        }
                    }else{
                        $jod_details = explode(',', $job_data['certificate']);
                        foreach($jod_details as $rec){
                            $jobs["name"] = 'Certification';
                            $jobs["job"] = $rec;
                            $worker_info[] = $jobs;
                        }
                    }
                    $popup_info[] = $popup;
                    $result['worker_info'] = $worker_info;
                    $result['popup_info'] = $popup_info;

                    $this->check = "1";
                    $this->message = "Get send Offer listed successfully";
                    $this->return_data = $result;
                } else {
                    $this->check = "1";
                    $this->message = "Get Send Offer Job Not Found";
                }

            }
            return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
        }
    }

    public function rejectedCounterOffer(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'job_id' => 'required',
            'recruiter_id' => 'required',
            'worker_user_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse = Nurse::where('user_id', $request->worker_user_id)->first();
            $offer_job = Offer::where(['nurse_id' => $nurse->id, 'job_id' => $request->job_id, 'status' => 'Offered'])->forceDelete();
            $job = DB::table('offer_jobs')->where(['recruiter_id' => $request->recruiter_id, 'job_id' => $request->job_id, 'worker_user_id' => $request->worker_user_id])->delete();

            if ($job) {
                $this->check = "1";
                $this->message = "Offer Rejected Successfully";
                $this->return_data = $job;
            } else {
                $this->check = "0";
                $this->message = "No Record Found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function counterOfferJob(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'job_id' => 'required',
            'recruiter_id' => 'required',
            'worker_user_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $update_array["job_id"] = isset($request->job_id)?$request->job_id:'';
            $update_array["recruiter_id"] = isset($request->recruiter_id)?$request->recruiter_id:'';
            $update_array["worker_user_id"] = isset($request->worker_user_id)?$request->worker_user_id:'';
            $update_array["job_name"] = isset($request->job_name)?$request->job_name:'';
            $update_array["type"] = isset($request->type)?$request->type:'';
            $update_array["compact"] = isset($request->compact)?$request->compact:'';
            $update_array["terms"] = isset($request->term)?$request->term:'';
            $update_array["profession"] = isset($request->profession)?$request->profession:'';
            $update_array["preferred_specialty"] = isset($request->specialty)?$request->specialty:'';
            $update_array["preferred_experience"] = isset($request->experience)?$request->experience:'';
            $update_array["job_location"] = isset($request->professional_licensure)?$request->professional_licensure:'';
            $update_array["vaccinations"] = isset($request->vaccinations)?$request->vaccinations:'';
            $update_array["number_of_references"] = isset($request->number_of_references)?$request->number_of_references:'';
            $update_array["min_title_of_reference"] = isset($request->min_title_of_reference)?$request->min_title_of_reference:'';
            $update_array["recency_of_reference"] = isset($request->recency_of_reference)?$request->recency_of_reference:'';
            $update_array["certificate"] = isset($request->certificate)?$request->certificate:'';
            $update_array["skills"] = isset($request->skills_checklist)?$request->skills_checklist:'';
            $update_array["urgency"] = isset($request->urgency)?$request->urgency:'';
            $update_array["position_available"] = isset($request->position_available)?$request->position_available:'';
            $update_array["msp"] = isset($request->msp)?$request->msp:'';
            $update_array["vms"] = isset($request->vms)?$request->vms:'';
            $update_array["submission_of_vms"] = isset($request->submission_of_vms)?$request->submission_of_vms:'';
            $update_array["block_scheduling"] = isset($request->block_scheduling)?$request->block_scheduling:'';
            $update_array["float_requirement"] = isset($request->float_requirement)?$request->float_requirement:'';
            $update_array["facility_shift_cancelation_policy"] = isset($request->facility_shift_cancelation_policy)?$request->facility_shift_cancelation_policy:'';
            $update_array["contract_termination_policy"] = isset($request->contract_termination_policy)?$request->contract_termination_policy:'';
            $update_array["traveler_distance_from_facility"] = isset($request->traveler_distance_from_facility)?$request->traveler_distance_from_facility:'';
            $update_array["facility"] = isset($request->facility)?$request->facility:'';
            $update_array["facility_id"] = isset($request->facility_id)?$request->facility_id:'';

            $update_array["clinical_setting"] = isset($request->clinical_setting)?$request->clinical_setting:'';
            $update_array["Patient_ratio"] = isset($request->Patient_ratio)?$request->Patient_ratio:'';
            $update_array["emr"] = isset($request->emr)?$request->emr:'';
            $update_array["Unit"] = isset($request->Unit)?$request->Unit:'';
            $update_array["Department"] = isset($request->Department)?$request->Department:'';
            $update_array["Bed_Size"] = isset($request->Bed_Size)?$request->Bed_Size:'';
            $update_array["Trauma_Level"] = isset($request->Trauma_Level)?$request->Trauma_Level:'';
            $update_array["scrub_color"] = isset($request->scrub_color)?$request->scrub_color:'';
            $update_array["start_date"] = isset($request->start_date)?$request->start_date:'';
            $update_array["as_soon_as"] = isset($request->as_soon_as)?$request->as_soon_as:'';
            $update_array["rto"] = isset($request->rto)?$request->rto:'';
            $update_array["preferred_shift"] = isset($request->preferred_shift)?$request->preferred_shift:'';
            $update_array["hours_per_week"] = isset($request->hours_per_week)?$request->hours_per_week:'';
            $update_array["guaranteed_hours"] = isset($request->guaranteed_hours)?$request->guaranteed_hours:'';
            $update_array["hours_shift"] = isset($request->hours_shift)?$request->hours_shift:'';
            $update_array["weeks_shift"] = isset($request->weeks_shift)?$request->weeks_shift:'';
            $update_array["preferred_assignment_duration"] = isset($request->preferred_assignment_duration)?$request->preferred_assignment_duration:'';
            $update_array["referral_bonus"] = isset($request->referral_bonus)?$request->referral_bonus:'';
            $update_array["sign_on_bonus"] = isset($request->sign_on_bonus)?$request->sign_on_bonus:'';
            $update_array["completion_bonus"] = isset($request->completion_bonus)?$request->completion_bonus:'';
            $update_array["extension_bonus"] = isset($request->extension_bonus)?$request->extension_bonus:'';
            $update_array["other_bonus"] = isset($request->other_bonus)?$request->other_bonus:'';
            $update_array["four_zero_one_k"] = isset($request->four_zero_one_k)?$request->four_zero_one_k:'';
            $update_array["health_insaurance"] = isset($request->health_insaurance)?$request->health_insaurance:'';
            $update_array["dental"] = isset($request->dental)?$request->dental:'';
            $update_array["vision"] = isset($request->vision)?$request->vision:'';
            $update_array["actual_hourly_rate"] = isset($request->actual_hourly_rate)?$request->actual_hourly_rate:'';
            $update_array["overtime"] = isset($request->overtime)?$request->overtime:'';
            $update_array["holiday"] = isset($request->holiday)?$request->holiday:'';
            $update_array["on_call"] = isset($request->on_call)?$request->on_call:'';
            $update_array["orientation_rate"] = isset($request->orientation_rate)?$request->orientation_rate:'';
            $update_array["weekly_non_taxable_amount"] = isset($request->weekly_non_taxable_amount)?$request->weekly_non_taxable_amount:'';
            $update_array["description"] = isset($request->description)?$request->description:'';
            $update_array["additional_terms"] = isset($request->additional_terms)?$request->additional_terms:'';

            $update_array["facilitys_parent_system"] = isset($request->facilitys_parent_system)?$request->facilitys_parent_system:'';
            $update_array["facility_average_rating"] = isset($request->facility_average_rating)?$request->facility_average_rating:'';
            $update_array["recruiter_average_rating"] = isset($request->recruiter_average_rating)?$request->recruiter_average_rating:'';
            $update_array["employer_average_rating"] = isset($request->employer_average_rating)?$request->employer_average_rating:'';
            $update_array["job_city"] = isset($request->city)?$request->city:'';
            $update_array["job_state"] = isset($request->state)?$request->state:'';



            $check = DB::table('offer_jobs')->where(['job_id' => $request->job_id, 'worker_user_id' => $request->worker_user_id, 'recruiter_id' => $request->recruiter_id])->first();
            if(isset($check))
            {
                $update_array["is_counter"] = isset($request->is_counter)?$request->is_counter:'1';
                $update_array["is_draft"] = isset($request->is_draft)?$request->is_draft:$check->is_draft;
                $job = DB::table('offer_jobs')->where(['job_id' => $request->job_id, 'worker_user_id' => $request->worker_user_id, 'recruiter_id' => $request->recruiter_id])->update($update_array);
                if ($job) {
                    $this->check = "1";
                    if($update_array["is_draft"] == '0'){
                        $this->message = "Send Counter Offer successfully";
                    }else{
                        $this->message = "Send Draft Counter Offer successfully";
                    }
                    $this->return_data = $job;
                } else {
                    $this->check = "0";
                    if($update_array["is_draft"] == '0'){
                        $this->message = "Send Counter Offered not Successfully";
                    }else{
                        $this->message = "Send Draft Counter Offered not Successfully";
                    }
                }
            }else{
                $this->check = "0";
                $this->message = "Job Not Found";
            }

            if($update_array["is_draft"] == '0'){
                $worker = Nurse::where('user_id', $request->worker_user_id)->first();
                $recruiter_info = User::where('id', $request->recruiter_id)->first();
                $checkOffer = Offer::where(['nurse_id' => $worker->id, 'job_id' => $request->job_id])->first();
                // if (isset($checkOffer)) {
                //     $offer = Offer::where(['nurse_id' => $worker->id, 'job_id' => $request->job_id])->update(['status' => 'Offered', 'start_date' => date('Y-m-d')]);
                // } else {
                //     $offer = Offer::create(['nurse_id' => $worker->id, 'created_by' => $worker->id, 'job_id' => $request->job_id,'status' => 'Offered', 'start_date' => date('Y-m-d') ]);
                // }
                $offer_status = 'Offered';
                $isAskWorker = '0';
                // $check_notification = Notification::where(['job_id' => $request->job_id, 'created_by' => $request->worker_user_id])->first();
                $check_notification = Notification::where(['job_id' => $request->job_id, 'isAskWorker' => $isAskWorker])->first();
                $text = 'Received a Counter Offer for job name- '.$update_array["job_name"].' ('. $request->job_id .') by ';
                if(empty($check_notification)){
                    $notification = Notification::create(['job_id' => $request->job_id, 'created_by' => $request->worker_user_id, 'recruiter_id' => $request->recruiter_id, 'title' => 'Send Counter Offer', 'text' => $text, 'updated_at' => date('Y-m-d h:i:s')]);
                }else{
                    $notification = Notification::where('id', $check_notification->id)->update(['job_id' => $request->job_id, 'created_by' => $request->worker_user_id, 'recruiter_id' => $request->recruiter_id, 'title' => 'Send Counter Offer', 'text' => $text, 'updated_at' => date('Y-m-d h:i:s')]);
                }
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getCounterOfferJoblist(Request $request)
    {
        {
            $validator = \Validator::make($request->all(), [
                'api_key' => 'required',
                'recruiter_id' => 'required'
            ]);
            if ($validator->fails()) {
                $this->message = $validator->errors()->first();
            } else {
                $job = DB::table('offer_jobs')->select('offer_jobs.worker_user_id as worker_user_id', 'nurses.id as worker_id', 'users.first_name as worker_first_name', 'users.last_name as worker_last_name', 'users.image as worker_image', 'jobs.id as job_id', 'jobs.profession as profession', 'jobs.preferred_shift as preferred_shift', 'jobs.preferred_specialty as preferred_specialty', 'jobs.preferred_experience as preferred_experience')
                    ->leftJoin('jobs', 'offer_jobs.job_id', 'jobs.id')
                    ->leftJoin('users', 'offer_jobs.worker_user_id', 'users.id')
                    ->leftJoin('nurses', 'users.id', 'nurses.user_id')
                    ->where(['offer_jobs.is_draft' => '0', 'offer_jobs.is_counter' => '1',  'offer_jobs.recruiter_id' => $request->recruiter_id])
                    ->get();
                foreach($job as $rec){
                    $rec->worker_image = isset($rec->worker_image)?url("public/images/nurses/profile/" . $rec->worker_image):'';
                    $rec->preferred_experience = isset($rec->preferred_experience)?$rec->preferred_experience.' Years of Experience':'';
                    $rec->specialty = $rec->preferred_specialty;
                    $rec->experience = $rec->preferred_experience;
                }

                if ($job) {
                    $this->check = "1";
                    $this->message = "Counter Offer Job listed successfully";
                    $this->return_data = $job;
                } else {
                    $this->check = "0";
                    $this->message = "No Record Found";
                }
            }
            return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
        }
    }

    public function getCounterOfferJob(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'job_id' => 'required',
            'recruiter_id' => 'required',
            'worker_user_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $this->return_data = [];
            $job = DB::table('offer_jobs')->select('offer_jobs.*')->where(['job_id' => $request->job_id, 'is_draft' => '0', 'is_counter' => '1', 'worker_user_id' => $request->worker_user_id])->first();
            $job_data = Job::where(['id' => $request->job_id, 'active' => '1', 'is_closed' => '0', 'is_hidden' => '0'])->first();
            $recruiter = USER::where('id', $request->recruiter_id)->first();
            $worker = USER::where('id', $request->worker_user_id)->first();
            $nurse = Nurse::where('user_id', $request->worker_user_id)->first();
            $offer = Offer::where(['nurse_id' => $nurse->id, 'job_id' => $request->job_id])->first();
            if(isset($offer)){
                if(isset($job_data['facility_id'])){
                    $facility = Facility::where('id', $job_data['facility_id'])->first();
                }
                $facility_name = isset($job_data['facility'])?$job_data['facility']:'';
                $worker_name = isset($worker['fullName'])?$worker['fullName']:'';
                $recruiter_name = isset($recruiter['fullName'])?$recruiter['fullName']:'';
                $result = [];
                $worker_info = [];
                $popup_info = [];

                $result['job_id'] = isset($job_data['id'])?$job_data['id']:"";
                $result['description'] = isset($job_data['description'])?$job_data['description']:"";
                $result['worker_name'] = $worker_name;
                $result['recruiter_name'] = $recruiter_name;
                $result['facility_name'] = $facility_name;
                $result['job_id'] = $job_data['id'];
                $result['offer_id'] = isset($offer['id'])?$offer['id']:'';
                $result['recruiter_id'] = $request->recruiter_id;
                $result['offer_valid'] = isset($job_data['preferred_assignment_duration'])?$job_data['preferred_assignment_duration'].' Weeks':'';

                if(isset($job)){
                    $popup = [];
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->type != $job_data['type']) && isset($job->type) && !empty($job->type))
                    {
                        $popup['name'] = 'Type';
                        $popup['value'] = $job->type;
                        $jobs["name"] = 'Type';
                        $jobs["job"] = $job->type;
                    }else{
                        $jobs["name"] = 'Type';
                        $jobs["job"] = $job_data['type'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->terms != $job_data['terms']) && isset($job->terms) && !empty($job->terms))
                    {
                        $popup['name'] = 'Terms';
                        $popup['value'] = $job->terms;
                        $jobs["name1"] = 'Terms';
                        $jobs["job1"] = $job->terms;
                    }else{
                        $jobs["name1"] = 'Terms';
                        $jobs["job1"] = $job_data['terms'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->profession != $job_data['profession']) && isset($job->profession) && !empty($job->profession))
                    {
                        $popup['name'] = 'Profession';
                        $popup['value'] = $job->profession;
                        $jobs["name"] = 'Profession';
                        $jobs["job"] = $job->profession;
                    }else{
                        $jobs["name"] = 'Profession';
                        $jobs["job"] = $job_data['profession'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->preferred_specialty != $job_data['preferred_specialty']) && isset($job->preferred_specialty) && !empty($job->preferred_specialty))
                    {
                        $popup['name'] = 'Specialty';
                        $popup['value'] = $job->preferred_specialty;
                        $jobs["name1"] = 'Specialty';
                        $jobs["job1"] = $job->preferred_specialty;
                    }else{
                        $jobs["name1"] = 'Specialty';
                        $jobs["job1"] = $job_data['preferred_specialty'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->compact != $job_data['compact']) && isset($job->compact) && !empty($job->compact))
                    {
                        $popup['name'] = 'Compact';
                        $popup['value'] = $job->compact;
                        $jobs["compact"] = $job->compact;
                    }else{
                        $jobs["compact"] = $job_data['compact'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->job_location != $job_data['job_location']) && isset($job->job_location) && !empty($job->job_location))
                    {
                        $popup['name'] = 'Professional Licensure';
                        $popup['value'] = $job->preferred_experience;
                        $jobs["name"] = 'Professional Licensure';
                        $jobs["job"] = $job->job_location;
                    }else{
                        $jobs["name"] = 'Professional Licensure';
                        $jobs["job"] = $job_data['job_location'].','.$jobs["compact"];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->preferred_experience != $job_data['preferred_experience']) && isset($job->preferred_experience) && !empty($job->preferred_experience))
                    {
                        $popup['name'] = 'Experience';
                        $popup['value'] = $job->preferred_experience;
                        $jobs["name1"] = 'Experience';
                        $jobs["job1"] = $job->preferred_experience;
                    }else{
                        $jobs["name1"] = 'Experience';
                        $jobs["job1"] = $job_data['preferred_experience'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->number_of_references != $job_data['number_of_references']) && isset($job->number_of_references) && !empty($job->number_of_references))
                    {
                        $popup['name'] = 'Number Of References';
                        $popup['value'] = $job->number_of_references;
                        $jobs["name"] = 'Number Of References';
                        $jobs["job"] = $job->number_of_references;
                    }else{
                        $jobs["name"] = 'Number Of References';
                        $jobs["job"] = $job_data['number_of_references'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->min_title_of_reference != $job_data['min_title_of_reference']) && isset($job->min_title_of_reference) && !empty($job->min_title_of_reference))
                    {
                        $popup['name'] = 'Min Title Of References';
                        $popup['value'] = $job->min_title_of_reference;
                        $jobs["name1"] = 'Min Title Of References';
                        $jobs["job1"] = $job->min_title_of_reference;
                    }else{
                        $jobs["name1"] = 'Min Title Of References';
                        $jobs["job1"] = $job_data['min_title_of_reference'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->recency_of_reference != $job_data['recency_of_reference']) && isset($job->recency_of_reference) && !empty($job->recency_of_reference))
                    {
                        $popup['name'] = 'Recency Of Reference';
                        $popup['value'] = $job->recency_of_reference;
                        $jobs["name"] = 'Recency Of Reference';
                        $jobs["job"] = $job->recency_of_reference;
                    }else{
                        $jobs["name"] = 'Recency Of Reference';
                        $jobs["job"] = $job_data['recency_of_reference'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->skills != $job_data['skills']) && isset($job->skills) && !empty($job->skills))
                    {
                        $popup['name'] = 'Skills Checklist';
                        $popup['value'] = $job->skills;
                        $jobs["name1"] = 'Skills Checklist';
                        $jobs["job1"] = $job->skills;
                    }else{
                        $jobs["name1"] = 'Skills Checklist';
                        $jobs["job1"] = $job_data['skills'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->position_available != $job_data['position_available']) && isset($job->position_available) && !empty($job->position_available))
                    {
                        $popup['name'] = '# Possition Available';
                        $popup['value'] = $job->position_available;
                        $jobs["name"] = '# Possition Available';
                        $jobs["job"] = $job->position_available;
                    }else{
                        $jobs["name"] = '# Possition Available';
                        $jobs["job"] = $job_data['position_available'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->msp != $job_data['msp']) && isset($job->msp) && !empty($job->msp))
                    {
                        $popup['name'] = 'MSP';
                        $popup['value'] = $job->msp;
                        $jobs["name1"] = 'MSP';
                        $jobs["job1"] = $job->msp;
                    }else{
                        $jobs["name1"] = 'MSP';
                        $jobs["job1"] = $job_data['msp'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->vms != $job_data['vms']) && isset($job->vms) && !empty($job->vms))
                    {
                        $popup['name'] = 'VMS';
                        $popup['value'] = $job->vms;
                        $jobs["name"] = 'VMS';
                        $jobs["job"] = $job->vms;
                    }else{
                        $jobs["name"] = 'VMS';
                        $jobs["job"] = $job_data['vms'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->submission_of_vms != $job_data['submission_of_vms']) && isset($job->submission_of_vms) && !empty($job->submission_of_vms))
                    {
                        $popup['name'] = '# of Submission in VMS';
                        $popup['value'] = $job->submission_of_vms;
                        $jobs["name1"] = '# of Submission in VMS';
                        $jobs["job1"] = $job->submission_of_vms;
                    }else{
                        $jobs["name1"] = '# of Submission in VMS';
                        $jobs["job1"] = $job_data['submission_of_vms'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->block_scheduling != $job_data['block_scheduling']) && isset($job->block_scheduling) && !empty($job->block_scheduling))
                    {
                        $popup['name'] = 'Block Scheduling';
                        $popup['value'] = $job->block_scheduling;
                        $jobs["name"] = 'Block Scheduling';
                        $jobs["job"] = $job->block_scheduling;
                    }else{
                        $jobs["name"] = 'Block Scheduling';
                        $jobs["job"] = $job_data['block_scheduling'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->facility_shift_cancelation_policy != $job_data['facility_shift_cancelation_policy']) && isset($job->facility_shift_cancelation_policy) && !empty($job->facility_shift_cancelation_policy))
                    {
                        $popup['name'] = 'Facility Shift Cancelation Policy';
                        $popup['value'] = $job->facility_shift_cancelation_policy;
                        $jobs["name1"] = 'Facility Shift Cancelation Policy';
                        $jobs["job1"] = $job->facility_shift_cancelation_policy;
                    }else{
                        $jobs["name1"] = 'Facility Shift Cancelation Policy';
                        $jobs["job1"] = $job_data['facility_shift_cancelation_policy'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';

                    if(($job->contract_termination_policy != $job_data['contract_termination_policy']) && isset($job->contract_termination_policy) && !empty($job->contract_termination_policy))
                    {
                        $popup['name'] = 'Contract Termination Policy';
                        $popup['value'] = $job->contract_termination_policy;
                        $jobs["name"] = 'Contract Termination Policy';
                        $jobs["job"] = $job->contract_termination_policy;
                    }else{
                        $jobs["name"] = 'Contract Termination Policy';
                        $jobs["job"] = $job_data['contract_termination_policy'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->traveler_distance_from_facility != $job_data['traveler_distance_from_facility']) && isset($job->traveler_distance_from_facility) && !empty($job->traveler_distance_from_facility))
                    {
                        $popup['name'] = 'Traveler Distance From Facility';
                        $popup['value'] = $job->traveler_distance_from_facility;
                        $jobs["name1"] = 'Traveler Distance From Facility';
                        $jobs["job1"] = $job->traveler_distance_from_facility;
                    }else{
                        $jobs["name1"] = 'Traveler Distance From Facility';
                        $jobs["job1"] = $job_data['traveler_distance_from_facility'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->facility != $job_data['facility']) && isset($job->facility) && !empty($job->facility))
                    {
                        $popup['name'] = 'Facility';
                        $popup['value'] = $job->facility;
                        $jobs["name"] = 'Facility';
                        $jobs["job"] = $job->facility;
                    }else{
                        $jobs["name"] = 'Facility';
                        $jobs["job"] = $job_data['facility'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->facilitys_parent_system != $job_data['facilitys_parent_system']) && isset($job->facilitys_parent_system) && !empty($job->facilitys_parent_system))
                    {
                        $popup['name'] = "Facility's Parent System";
                        $popup['value'] = $job->facilitys_parent_system;
                        $jobs["name1"] = "Facility's Parent System";
                        $jobs["job1"] = $job->facilitys_parent_system;
                    }else{
                        $jobs["name1"] = "Facility's Parent System";
                        $jobs["job1"] = $job_data['facilitys_parent_system'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->facility_average_rating != $job_data['facility_average_rating']) && isset($job->facility_average_rating) && !empty($job->facility_average_rating))
                    {
                        $popup['name'] = "Facility Average Rating";
                        $popup['value'] = $job->facility_average_rating;
                        $jobs["name"] = "Facility Average Rating";
                        $jobs["job"] = $job->facility_average_rating;
                    }else{
                        $jobs["name"] = "Facility Average Rating";
                        $jobs["job"] = $job_data['facility_average_rating'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->recruiter_average_rating != $job_data['recruiter_average_rating']) && isset($job->recruiter_average_rating) && !empty($job->recruiter_average_rating))
                    {
                        $popup['name'] = "Recruiter Average Rating";
                        $popup['value'] = isset( $job->recruiter_average_rating)? $job->recruiter_average_rating:'';
                        $jobs["name1"] = "Recruiter Average Rating";
                        $jobs["job1"] = isset($job->recruiter_average_rating)?$job->recruiter_average_rating:'';
                    }else{
                        $jobs["name1"] = "Recruiter Average Rating";
                        $jobs["job1"] = isset($job_data['recruiter_average_rating'])?$job_data['recruiter_average_rating']:'';
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->employer_average_rating != $job_data['employer_average_rating']) && isset($job->employer_average_rating) && !empty($job->employer_average_rating))
                    {
                        $popup['name'] = "Employer Average Rating";
                        $popup['value'] = isset($job->employer_average_rating)?$job->employer_average_rating:'';
                        $jobs["name"] = "Employer Average Rating";
                        $jobs["job"] = isset($job->employer_average_rating)?$job->employer_average_rating:'';
                    }else{
                        $jobs["name"] = "Employer Average Rating";
                        $jobs["job"] = isset($job_data['employer_average_rating'])?$job_data['employer_average_rating']:'';
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->clinical_setting != $job_data['clinical_setting']) && isset($job->clinical_setting) && !empty($job->clinical_setting))
                    {
                        $popup['name'] = "Clinical Setting";
                        $popup['value'] = $job->clinical_setting;
                        $jobs["name1"] = "Clinical Setting";
                        $jobs["job1"] = $job->clinical_setting;
                    }else{
                        $jobs["name1"] = "Clinical Setting";
                        $jobs["job1"] = $job_data['clinical_setting'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->Patient_ratio != $job_data['Patient_ratio']) && isset($job->Patient_ratio) && !empty($job->Patient_ratio))
                    {
                        $popup['name'] = "Patient Ratio";
                        $popup['value'] = $job->Patient_ratio;
                        $jobs["name"] = "Patient Ratio";
                        $jobs["job"] = $job->Patient_ratio;
                    }else{
                        $jobs["name1"] = "Patient Ratio";
                        $jobs["job"] = $job_data['Patient_ratio'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->emr != $job_data['emr']) && isset($job->emr) && !empty($job->emr))
                    {
                        $popup['name'] = "EMR";
                        $popup['value'] = $job->emr;
                        $jobs["name1"] = "EMR";
                        $jobs["job1"] = $job->emr;
                    }else{
                        $jobs["name1"] = "EMR";
                        $jobs["job1"] = $job_data['emr'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->Unit != $job_data['Unit']) && isset($job->Unit) && !empty($job->Unit))
                    {
                        $popup['name'] = "Unit";
                        $popup['value'] = $job->Unit;
                        $jobs["name"] = "Unit";
                        $jobs["job"] = $job->Unit;
                    }else{
                        $jobs["name"] = "Unit";
                        $jobs["job"] = $job_data['Unit'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->Department != $job_data['Department']) && isset($job->Department) && !empty($job->Department))
                    {
                        $popup['name'] = "Department";
                        $popup['value'] = $job->Department;
                        $jobs["name1"] = "Department";
                        $jobs["job1"] = $job->Department;
                    }else{
                        $jobs["name1"] = "Department";
                        $jobs["job1"] = $job_data['Department'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->Bed_Size != $job_data['Bed_Size']) && isset($job->Bed_Size) && !empty($job->Bed_Size))
                    {
                        $popup['name'] = "Bed Size";
                        $popup['value'] = $job->Bed_Size;
                        $jobs["name"] = "Bed Size";
                        $jobs["job"] = $job->Bed_Size;
                    }else{
                        $jobs["name"] = "Bed Size";
                        $jobs["job"] = $job_data['Bed_Size'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->Trauma_Level != $job_data['Trauma_Level']) && isset($job->Trauma_Level) && !empty($job->Trauma_Level))
                    {
                        $popup['name'] = "Trauma Level";
                        $popup['value'] = $job->Trauma_Level;
                        $jobs["name1"] = "Trauma Level";
                        $jobs["job1"] = $job->Trauma_Level;
                    }else{
                        $jobs["name1"] = "Trauma Level";
                        $jobs["job1"] = $job_data['Trauma_Level'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->scrub_color != $job_data['scrub_color']) && isset($job->scrub_color) && !empty($job->scrub_color))
                    {
                        $popup['name'] = "Scrub Color";
                        $popup['value'] = $job->scrub_color;
                        $jobs["name"] = "Scrub Color";
                        $jobs["job"] = $job->scrub_color;
                    }else{
                        $jobs["name"] = "Scrub Color";
                        $jobs["job"] = $job_data['scrub_color'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->job_city != $job_data['job_city']) && isset($job->job_city) && !empty($job->job_city))
                    {
                        $popup['name'] = "Facility City";
                        $popup['value'] = $job->job_city;
                        $jobs["name1"] = "Facility City";
                        $jobs["job1"] = $job->job_city;
                    }else{
                        $jobs["name1"] = "Facility City";
                        $jobs["job1"] = $job_data['job_city'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->job_state != $job_data['job_state']) && isset($job->job_state) && !empty($job->job_state))
                    {
                        $popup['name'] = "Facility State Code";
                        $popup['value'] = $job->job_state;
                        $jobs["name1"] = "Facility State Code";
                        $jobs["job1"] = $job->job_state;
                    }else{
                        $jobs["name1"] = "Facility State Code";
                        $jobs["job1"] = $job_data['job_state'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->start_date != $job_data['start_date']) && isset($job->start_date) && !empty($job->start_date))
                    {
                        $popup['name'] = "Start Date";
                        $popup['value'] = $job->start_date;
                        $jobs["name1"] = "Start Date";
                        $jobs["job1"] = $job->start_date;
                    }else{
                        $jobs["name1"] = "Start Date";
                        $jobs["job1"] = $job_data['start_date'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->rto != $job_data['rto']) && isset($job->rto) && !empty($job->rto))
                    {
                        $popup['name'] = "RTO";
                        $popup['value'] = $job->rto;
                        $jobs["name"] = "RTO";
                        $jobs["job"] = $job->rto;
                    }else{
                        $jobs["name"] = "RTO";
                        $jobs["job"] = $job_data['rto'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->preferred_shift != $job_data['preferred_shift']) && isset($job->preferred_shift) && !empty($job->preferred_shift))
                    {
                        $popup['name'] = "Shift Time Of Day";
                        $popup['value'] = $job->preferred_shift;
                        $jobs["name1"] = "Shift Time Of Day";
                        $jobs["job1"] = $job->preferred_shift;
                    }else{
                        $jobs["name1"] = "Shift Time Of Day";
                        $jobs["job1"] = $job_data['preferred_shift'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';

                    if(($job->hours_per_week != $job_data['hours_per_week']) && isset($job->hours_per_week) && !empty($job->hours_per_week))
                    {
                        $popup['name'] = "Hours/Week";
                        $popup['value'] = $job->hours_per_week;
                        $jobs["name"] = "Hors/Week";
                        $jobs["job"] = $job->hours_per_week;
                    }else{
                        $jobs["name"] = "Hors/Week";
                        $jobs["job"] = $job_data['hours_per_week'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->guaranteed_hours != $job_data['guaranteed_hours']) && isset($job->guaranteed_hours) && !empty($job->guaranteed_hours))
                    {
                        $popup['name'] = "Gauranteed Hours";
                        $popup['value'] = $job->guaranteed_hours;
                        $jobs["name1"] = "Gauranteed Hours";
                        $jobs["job1"] = $job->guaranteed_hours;
                    }else{
                        $jobs["name1"] = "Gauranteed Hours";
                        $jobs["job1"] = $job_data['guaranteed_hours'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->hours_shift != $job_data['hours_shift']) && isset($job->hours_shift) && !empty($job->hours_shift))
                    {
                        $popup['name'] = "Hours/Shift";
                        $popup['value'] = $job->hours_shift;
                        $jobs["name"] = "Hours/Shift";
                        $jobs["job"] = $job->hours_shift;
                    }else{
                        $jobs["name"] = "Hours/Shift";
                        $jobs["job"] = $job_data['hours_shift'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->preferred_assignment_duration != $job_data['preferred_assignment_duration']) && isset($job->preferred_assignment_duration) && !empty($job->preferred_assignment_duration))
                    {
                        $popup['name'] = "Weeks/Assignment";
                        $popup['value'] = $job->preferred_assignment_duration;
                        $jobs["name1"] = "Weeks/Assignment";
                        $jobs["job1"] = $job->preferred_assignment_duration;
                    }else{
                        $jobs["name1"] = "Weeks/Assignment";
                        $jobs["job1"] = $job_data['preferred_assignment_duration'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->weeks_shift != $job_data['weeks_shift']) && isset($job->weeks_shift) && !empty($job->weeks_shift))
                    {
                        $popup['name'] = "Shifts/Week";
                        $popup['value'] = $job->weeks_shift;
                        $jobs["name"] = "Shifts/Week";
                        $jobs["job"] = $job->weeks_shift;
                    }else{
                        $jobs["name"] = "Shifts/Week";
                        $jobs["job"] = $job_data['weeks_shift'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->referral_bonus != $job_data['referral_bonus']) && isset($job->referral_bonus) && !empty($job->referral_bonus))
                    {
                        $popup['name'] = "Referral Bonus";
                        $popup['value'] = $job->referral_bonus;
                        $jobs["name1"] = "Referral Bonus";
                        $jobs["job1"] = $job->referral_bonus;
                    }else{
                        $jobs["name1"] = "Referral Bonus";
                        $jobs["job1"] = $job_data['referral_bonus'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->sign_on_bonus != $job_data['sign_on_bonus']) && isset($job->sign_on_bonus) && !empty($job->sign_on_bonus))
                    {
                        $popup['name'] = "Sign-On Bonus";
                        $popup['value'] = $job->sign_on_bonus;
                        $jobs["name"] = "Sign-On Bonus";
                        $jobs["job"] = $job->sign_on_bonus;
                    }else{
                        $jobs["name"] = "Sign-On Bonus";
                        $jobs["job"] = $job_data['sign_on_bonus'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->completion_bonus != $job_data['completion_bonus']) && isset($job->completion_bonus) && !empty($job->completion_bonus))
                    {
                        $popup['name'] = "Completion Bonus";
                        $popup['value'] = $job->completion_bonus;
                        $jobs["name1"] = "Completion Bonus";
                        $jobs["job1"] = $job->completion_bonus;
                    }else{
                        $jobs["name1"] = "Completion Bonus";
                        $jobs["job1"] = $job_data['completion_bonus'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->extension_bonus != $job_data['extension_bonus']) && isset($job->extension_bonus) && !empty($job->extension_bonus))
                    {
                        $popup['name'] = "Extension Bonus";
                        $popup['value'] = $job->extension_bonus;
                        $jobs["name"] = "Extension Bonus";
                        $jobs["job"] = $job->extension_bonus;
                    }else{
                        $jobs["name"] = "Extension Bonus";
                        $jobs["job"] = $job_data['extension_bonus'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->other_bonus != $job_data['other_bonus']) && isset($job->other_bonus) && !empty($job->other_bonus))
                    {
                        $popup['name'] = "Other Bonus";
                        $popup['value'] = $job->other_bonus;
                        $jobs["name1"] = "Other Bonus";
                        $jobs["job1"] = $job->other_bonus;
                    }else{
                        $jobs["name1"] = "Other Bonus";
                        $jobs["job1"] = $job_data['other_bonus'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';

                    if(($job->four_zero_one_k != $job_data['four_zero_one_k']) && isset($job->four_zero_one_k) && !empty($job->four_zero_one_k))
                    {
                        $popup['name'] = "401K";
                        $popup['value'] = $job->four_zero_one_k;
                        $jobs["name"] = "401K";
                        $jobs["job"] = $job->four_zero_one_k;
                    }else{
                        $jobs["name"] = "401K";
                        $jobs["job"] = $job_data['four_zero_one_k'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->health_insaurance != $job_data['health_insaurance']) && isset($job->health_insaurance) && !empty($job->health_insaurance))
                    {
                        $popup['name'] = "Health Insaurance";
                        $popup['value'] = $job->health_insaurance;
                        $jobs["name1"] = "Health Insaurance";
                        $jobs["job1"] = $job->health_insaurance;
                    }else{
                        $jobs["name1"] = "Health Insaurance";
                        $jobs["job1"] = $job_data['health_insaurance'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->dental != $job_data['dental']) && isset($job->dental) && !empty($job->dental))
                    {
                        $popup['name'] = "Dental";
                        $popup['value'] = $job->dental;
                        $jobs["name"] = "Dental";
                        $jobs["job"] = $job->dental;
                    }else{
                        $jobs["name"] = "Dental";
                        $jobs["job"] = $job_data['dental'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->vision != $job_data['vision']) && isset($job->vision) && !empty($job->vision))
                    {
                        $popup['name'] = "Vision";
                        $popup['value'] = $job->vision;
                        $jobs["name1"] = "Vision";
                        $jobs["job1"] = $job->vision;
                    }else{
                        $jobs["name1"] = "Vision";
                        $jobs["job1"] = $job_data['vision'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->actual_hourly_rate != $job_data['actual_hourly_rate']) && isset($job->actual_hourly_rate) && !empty($job->actual_hourly_rate))
                    {
                        $popup['name'] = "Actual Hourly Rate";
                        $popup['value'] = $job->actual_hourly_rate;
                        $jobs["name"] = "Actual Hourly Rate";
                        $jobs["job"] = $job->actual_hourly_rate;
                    }else{
                        $jobs["name"] = "Actual Hourly Rate";
                        $jobs["job"] = $job_data['actual_hourly_rate'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->feels_like_per_hour != $job_data['feels_like_per_hour']) && isset($job->feels_like_per_hour) && !empty($job->feels_like_per_hour))
                    {
                        $popup['name'] = "Feels Like $/hr";
                        $popup['value'] = $job->feels_like_per_hour;
                        $jobs["name1"] = "Feels Like $/hr";
                        $jobs["job1"] = $job->feels_like_per_hour;
                    }else{
                        $jobs["name1"] = "Feels Like $/hr";
                        $jobs["job1"] = $job_data['feels_like_per_hour'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->overtime != $job_data['overtime']) && isset($job->overtime) && !empty($job->overtime))
                    {
                        $popup['name'] = "Overtime";
                        $popup['value'] = $job->overtime;
                        $jobs["name"] = "Overtime";
                        $jobs["job"] = $job->overtime;
                    }else{
                        $jobs["name"] = "Overtime";
                        $jobs["job"] = $job_data['overtime'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->holiday != $job_data['holiday']) && isset($job->holiday) && !empty($job->holiday))
                    {
                        $popup['name'] = "Holiday";
                        $popup['value'] = $job->holiday;
                        $jobs["name1"] = "Holiday";
                        $jobs["job1"] = $job->holiday;
                    }else{
                        $jobs["name1"] = "Holiday";
                        $jobs["job1"] = $job_data['holiday'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->on_call != $job_data['on_call']) && isset($job->on_call) && !empty($job->on_call))
                    {
                        $popup['name'] = "On Call";
                        $popup['value'] = $job->on_call;
                        $jobs["name"] = "On Call";
                        $jobs["job"] = $job->on_call;
                    }else{
                        $jobs["name"] = "On Call";
                        $jobs["job"] = $job_data['on_call'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->call_back != $job_data['call_back']) && isset($job->call_back) && !empty($job->call_back))
                    {
                        $popup['name'] = "Call Back";
                        $popup['value'] = $job->call_back;
                        $jobs["name1"] = "Call Back";
                        $jobs["job1"] = $job->call_back;
                    }else{
                        $jobs["name1"] = "Call Back";
                        $jobs["job1"] = $job_data['call_back'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->orientation_rate != $job_data['orientation_rate']) && isset($job->orientation_rate) && !empty($job->orientation_rate))
                    {
                        $popup['name'] = "Orientation Rate";
                        $popup['value'] = $job->orientation_rate;
                        $jobs["name"] = "Orientation Rate";
                        $jobs["job"] = $job->orientation_rate;
                    }else{
                        $jobs["name"] = "Orientation Rate";
                        $jobs["job"] = $job_data['orientation_rate'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->weekly_taxable_amount != $job_data['weekly_taxable_amount']) && isset($job->weekly_taxable_amount) && !empty($job->weekly_taxable_amount))
                    {
                        $popup['name'] = "Weekly Taxable Amount";
                        $popup['value'] = $job->weekly_taxable_amount;
                        $jobs["name1"] = "Weekly Taxable Amount";
                        $jobs["job1"] = $job->weekly_taxable_amount;
                    }else{
                        $jobs["name1"] = "Weekly Taxable Amount";
                        $jobs["job1"] = $job_data['weekly_taxable_amount'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->weekly_non_taxable_amount != $job_data['weekly_non_taxable_amount']) && isset($job->weekly_non_taxable_amount) && !empty($job->weekly_non_taxable_amount))
                    {
                        $popup['name'] = "Weekly Non Taxable Amount";
                        $popup['value'] = $job->weekly_non_taxable_amount;
                        $jobs["name"] = "Weekly Non Taxable Amount";
                        $jobs["job"] = $job->weekly_non_taxable_amount;
                    }else{
                        $jobs["name"] = "Weekly Non Taxable Amount";
                        $jobs["job"] = $job_data['weekly_non_taxable_amount'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->employer_weekly_amount != $job_data['employer_weekly_amount']) && isset($job->employer_weekly_amount) && !empty($job->employer_weekly_amount))
                    {
                        $popup['name'] = "Employer Weekly Amount";
                        $popup['value'] = $job->employer_weekly_amount;
                        $jobs["name1"] = "Employer Weekly Amount";
                        $jobs["job1"] = $job->employer_weekly_amount;
                    }else{
                        $jobs["name1"] = "Employer Weekly Amount";
                        $jobs["job1"] = $job_data['employer_weekly_amount'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->goodwork_weekly_amount != $job_data['goodwork_weekly_amount']) && isset($job->goodwork_weekly_amount) && !empty($job->goodwork_weekly_amount))
                    {
                        $popup['name'] = "Goodwork Weekly Amount";
                        $popup['value'] = $job->goodwork_weekly_amount;
                        $jobs["name"] = "Goodwork Weekly Amount";
                        $jobs["job"] = $job->goodwork_weekly_amount;
                    }else{
                        $jobs["name"] = "Goodwork Weekly Amount";
                        $jobs["job"] = $job_data['goodwork_weekly_amount'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->total_employer_amount != $job_data['total_employer_amount']) && isset($job->total_employer_amount) && !empty($job->total_employer_amount))
                    {
                        $popup['name'] = "Total Employer Amount";
                        $popup['value'] = $job->total_employer_amount;
                        $jobs["name1"] = "Total Employer Amount";
                        $jobs["job1"] = $job->total_employer_amount;
                    }else{
                        $jobs["name1"] = "Total Employer Amount";
                        $jobs["job1"] = $job_data['total_employer_amount'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->total_goodwork_amount != $job_data['total_goodwork_amount']) && isset($job->total_goodwork_amount) && !empty($job->total_goodwork_amount))
                    {
                        $popup['name'] = "Total Goodwork Amount";
                        $popup['value'] = $job->total_goodwork_amount;
                        $jobs["name"] = "Total Goodwork Amount";
                        $jobs["job"] = $job->total_goodwork_amount;
                    }else{
                        $jobs["name"] = "Total Goodwork Amount";
                        $jobs["job"] = $job_data['total_goodwork_amount'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(($job->total_contract_amount != $job_data['total_contract_amount']) && isset($job->total_contract_amount) && !empty($job->total_contract_amount))
                    {
                        $popup['name'] = "Total Contract Amount";
                        $popup['value'] = $job->total_contract_amount;
                        $jobs["name1"] = "Total Contract Amount";
                        $jobs["job1"] = $job->total_contract_amount;
                    }else{
                        $jobs["name1"] = "Total Contract Amount";
                        $jobs["job1"] = $job_data['total_contract_amount'];
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';

                    if(($job->goodwork_number != $job_data['goodwork_number']) && isset($job->goodwork_number) && !empty($job->goodwork_number))
                    {
                        $popup['name'] = "Goodwork Number";
                        $popup['value'] = $job->goodwork_number;
                        $jobs["name"] = "Goodwork Number";
                        $jobs["job"] = $job->goodwork_number;
                    }else{
                        $jobs["name"] = "Goodwork Number";
                        $jobs["job"] = $job_data['goodwork_number'];
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    if(isset($job->additional_terms) && !empty($job->additional_terms))
                    {
                        $popup['name'] = "Additional Terms";
                        $popup['value'] = $job->additional_terms;
                        $jobs["name1"] = "Additional Terms";
                        $jobs["job1"] = $job->additional_terms;
                    }else{
                        $jobs["name1"] = "Additional Terms";
                        $jobs["job1"] = "";
                    }
                    $worker_info[] = $jobs;
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';
                    $jobs["name1"] = '';
                    $jobs["job1"] = '';
                    if(($job->vaccinations != $job_data['vaccinations']) && isset($job->vaccinations) && !empty($job->vaccinations))
                    {
                        $popup['name'] = 'Vaccinations & Immunizations';
                        $popup['value'] = $job->vaccinations;
                        $jod_details = explode(',', $job->vaccinations);
                        foreach($jod_details as $rec){
                            $jobs["name"] = 'Vaccinations & Immunizations';
                            $jobs["job"] = $rec;
                            $worker_info[] = $jobs;
                        }
                    }else{
                        $jod_details = explode(',', $job_data['vaccinations']);
                        foreach($jod_details as $rec){
                            $jobs["name"] = 'Vaccinations & Immunizations';
                            $jobs["job"] = $rec;
                            $worker_info[] = $jobs;
                        }
                    }
                    $popup_info[] = $popup;
                    $popup['name'] = '';
                    $popup['value'] = '';

                    if(($job->certificate != $job_data['certificate']) && isset($job->certificate) && !empty($job->certificate))
                    {
                        $popup['name'] = 'Certification';
                        $popup['value'] = $job->certificate;
                        $jod_details = explode(',', $job->certificate);
                        foreach($jod_details as $rec){
                            $jobs["name"] = 'Certification';
                            $jobs["job"] = $rec;
                            $worker_info[] = $jobs;
                        }
                    }else{
                        $jod_details = explode(',', $job_data['certificate']);
                        foreach($jod_details as $rec){
                            $jobs["name"] = 'Certification';
                            $jobs["job"] = $rec;
                            $worker_info[] = $jobs;
                        }
                    }
                    $popup_info[] = $popup;
                    $result['worker_info'] = $worker_info;
                    $result['popup_info'] = $popup_info;

                    $this->check = "1";
                    $this->message = "Get send Offer listed successfully";
                    $this->return_data = $result;
                } else {
                    $this->check = "1";
                    $this->message = "Get Send Offer Job Not Found";
                }
            }else{
                $this->check = "1";
                $this->message = "Offer Not Found";
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function getDraftOfferedJoblist(Request $request)
    {
        {
            $validator = \Validator::make($request->all(), [
                'api_key' => 'required',
                'recruiter_id' => 'required'
            ]);
            if ($validator->fails()) {
                $this->message = $validator->errors()->first();
            } else {
                $job = DB::table('offer_jobs')->select('offer_jobs.worker_user_id as worker_user_id', 'nurses.id as worker_id', 'users.first_name as worker_first_name', 'users.last_name as worker_last_name', 'users.image as worker_image', 'jobs.id as job_id', 'jobs.profession as profession', 'jobs.preferred_shift as preferred_shift', 'jobs.preferred_specialty as preferred_specialty', 'jobs.preferred_experience as preferred_experience')
                    ->leftJoin('jobs', 'offer_jobs.job_id', 'jobs.id')
                    ->leftJoin('users', 'offer_jobs.worker_user_id', 'users.id')
                    ->leftJoin('nurses', 'users.id', 'nurses.user_id')
                    ->where(['offer_jobs.is_draft' => '1', 'offer_jobs.recruiter_id' => $request->recruiter_id])
                    ->get();
                    $recrod = [];
                foreach($job as $rec){
                    $rec->worker_image = isset($rec->worker_image)?url("public/images/nurses/profile/" . $rec->worker_image):'';
                    $rec->preferred_experience = isset($rec->preferred_experience)?$rec->preferred_experience.' Years of Experience':'';
                    $rec->specialty = $rec->preferred_specialty;
                    $rec->experience = $rec->preferred_experience;
                    $rec->worker_name = $rec->worker_first_name.' '.$rec->worker_last_name;

                }

                if ($job) {
                    $this->check = "1";
                    $this->message = "Draft send Offer Job listed successfully";
                    $this->return_data = $job;
                } else {
                    $this->check = "0";
                    $this->message = "No Record Found";
                }
            }
            return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
        }
    }

    public function getDraftCounterOfferedJoblist(Request $request)
    {
        {
            $validator = \Validator::make($request->all(), [
                'api_key' => 'required',
                'worker_user_id' => 'required'
            ]);
            if ($validator->fails()) {
                $this->message = $validator->errors()->first();
            } else {
                $job = DB::table('offer_jobs')->select('offer_jobs.worker_user_id as worker_user_id', 'nurses.id as worker_id', 'users.first_name as worker_first_name', 'users.last_name as worker_last_name', 'users.image as worker_image', 'jobs.id as job_id', 'jobs.profession as profession', 'jobs.preferred_shift as preferred_shift', 'jobs.preferred_specialty as preferred_specialty', 'jobs.preferred_experience as preferred_experience')
                    ->leftJoin('jobs', 'offer_jobs.job_id', 'jobs.id')
                    ->leftJoin('users', 'offer_jobs.worker_user_id', 'users.id')
                    ->leftJoin('nurses', 'users.id', 'nurses.user_id')
                    ->where(['offer_jobs.is_draft' => '1', 'offer_jobs.worker_user_id' => $request->worker_user_id])
                    ->get();
                foreach($job as $rec){
                    $rec->worker_image = isset($rec->worker_image)?url("public/images/nurses/profile/" . $rec->worker_image):'';
                    $rec->preferred_experience = isset($rec->preferred_experience)?$rec->preferred_experience.' Years of Experience':'';
                    $rec->specialty = $rec->preferred_specialty;
                    $rec->experience = $rec->preferred_experience;
                }

                if ($job) {
                    $this->check = "1";
                    $this->message = "Draft send Offer Job listed successfully";
                    $this->return_data = $job;
                } else {
                    $this->check = "0";
                    $this->message = "No Record Found";
                }
            }
            return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
        }
    }

    public function draftJob($type = "create", Request $request)
    {
        $messages = [
            "job_photos.*.mimes" => "Photos should be image or png jpg",
            "job_photos.*.max" => "Photos should not be more than 5mb"
        ];

        $validation_array = [
            'user_id' => 'required',
            'api_key' => 'required',
            'job_name' => 'required',
            // 'job_type' => 'required',
            'preferred_specialty' => 'required',
            'preferred_work_location' => 'required',
            'preferred_assignment_duration' => 'required',
            'preferred_hourly_pay_rate' => 'required|numeric',
            'description' => 'required|min:10',

        ];

        $validator = \Validator::make($request->all(), $validation_array, $messages);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $facility_id = Facility::where('created_by', $request->user_id)->get()->first();
            if(isset($facility_id) && !empty($facility_id)){
                $facility_id = $facility_id->id;
            }else{
                $facility_id =  '';
            }

            $update_array["facility_id"] = (isset($request->facility_id) && $request->facility_id != "") ? $request->facility_id : $facility_id;
            $update_array["preferred_assignment_duration"] = (isset($request->preferred_assignment_duration) && $request->preferred_assignment_duration != "") ? $request->preferred_assignment_duration : "";
            $update_array["job_name"] = (isset($request->job_name) && $request->job_name != "") ? $request->job_name : "";
            $update_array["preferred_specialty"] = (isset($request->preferred_specialty) && $request->preferred_specialty != "") ? $request->preferred_specialty : "";
            $update_array["job_type"] = (isset($request->job_type) && $request->job_type != "") ? $request->job_type : "415";
            $update_array["type"] = (isset($request->type) && $request->type != "") ? $request->type : "";
            $update_array["job_location"] = (isset($request->preferred_work_location) && $request->preferred_work_location != "") ? $request->preferred_work_location : "";
            $update_array["preferred_work_location"] = (isset($request->preferred_work_location) && $request->preferred_work_location != "") ? $request->preferred_work_location : "";
            $preferred_days_of_the_week = (isset($request->preferred_days_of_the_week) && $request->preferred_days_of_the_week != "") ? json_decode($request->preferred_days_of_the_week) : [];
            if (is_array($preferred_days_of_the_week) && !empty($preferred_days_of_the_week)) {
                $update_array["preferred_days_of_the_week"] = implode(',', $preferred_days_of_the_week);
            }
            $update_array["preferred_hourly_pay_rate"] = (isset($request->preferred_hourly_pay_rate) && $request->preferred_hourly_pay_rate != "") ? $request->preferred_hourly_pay_rate : "";
            $update_array["description"] = (isset($request->description) && $request->description != "") ? $request->description : "";
            $update_array["active"] = (isset($request->active) && $request->active != "") ? $request->active : "0";

            $update_array["start_date"] = (isset($request->start_date) && $request->start_date != "") ? date('Y-m-d', strtotime($request->start_date)) : NULL;
            $update_array["end_date"] = (isset($request->end_date) && $request->end_date != "") ? date('Y-m-d', strtotime($request->end_date)) : NULL;
            $update_array["recruiter_id"] = (isset($request->recruiter_id) && $request->recruiter_id != "") ? $request->recruiter_id : $request->user_id;

            if (isset($request->job_video) && $request->job_video != "") {
                if (preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->job_video, $vresult)) {
                    $youTubeID = $this->parse_youtube($request->job_video);
                    $embedURL = 'https://www.youtube.com/embed/' . $youTubeID[1];
                    $update_array['video_embed_url'] = $embedURL;
                } elseif (preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->job_video, $vresult)) {
                    $vimeoID = $this->parse_vimeo($request->job_video);
                    $embedURL = 'https://player.vimeo.com/video/' . $vimeoID[1];
                    $update_array['video_embed_url'] = $embedURL;
                }
            }

            /* create job */
            $update_array["created_by"] = (isset($request->user_id) && $request->user_id != "") ? $request->user_id : "";
            $job = Job::create($update_array);
            /* create job */

            if (!empty($job) && $job_photos = $request->file('job_photos')) {
                foreach ($job_photos as $job_photo) {
                    $job_photo_name_full = $job_photo->getClientOriginalName();
                    $job_photo_name = pathinfo($job_photo_name_full, PATHINFO_FILENAME);
                    $job_photo_ext = $job_photo->getClientOriginalExtension();
                    $job_photo_finalname = $job_photo_name . '_' . time() . '.' . $job_photo_ext;
                    //Upload Image
                    $job_id_val = ($type == "update") ? $job_id : $job->id;
                    $job_photo->storeAs('assets/jobs/' . $job_id_val, $job_photo_finalname);
                    JobAsset::create(['job_id' => $job_id_val, 'name' => $job_photo_finalname, 'filter' => 'job_photos']);
                }
            }

            if (isset($job)) {
                $insert_offer["nurse_id"] = $request->user_id;
                $insert_offer["created_by"] = $request->user_id;
                $insert_offer["job_id"] = $job['id'];
                $insert_offer["status"] = 'Screening';
                // $insert_offer["expiration"] = date("Y-m-d H:i:s", strtotime('+48 hours'));

                $offer = Offer::create($insert_offer);
            }

            if ($job) {
                $this->check = "1";
                $this->message = "Job drafted successfully";
                $this->return_data = $job;
            } else {
                $this->check = "0";
                $this->message = "Failed to create job, Please try again later";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

}
