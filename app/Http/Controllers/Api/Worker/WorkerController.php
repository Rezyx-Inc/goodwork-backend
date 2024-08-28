<?php

namespace App\Http\Controllers\Api\Worker;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

// Models
use App\Models\User;
use App\Models\Nurse;
use App\Models\Availability;
use App\Models\Role;
use App\Models\NurseAsset;
use App\Models\NurseReference;
use App\Models\EmailTemplate;
use App\Models\Offer;
use App\Models\NurseRating;
use App\Models\Facility;
use App\Models\Job;
use App\Models\FacilityRating;


use DB;
use App\Http\Controllers\Controller;
class WorkerController extends Controller
{
    // register api for the worker
    public function register(Request $request)
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

                $user_data = User::where('email', '=', $request->email)->first();
                if ($user_data === null) {
                    $user = User::create([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'mobile' => $request->mobile,
                        'email' => $request->email,
                        'user_name' => $request->email,
                        // 'password' => Hash::make($request->password),
                        'role' => Role::getKey(Role::NURSE),
                        'fcm_token' => $request->fcm_token
                    ]);

                    $nurse = Nurse::create([
                        'user_id' => $user->id,
                        'worker_goodwork_number' => uniqid()
                    ]);
                    $availability = Availability::create([
                        'nurse_id' => $nurse->id,
                        // 'work_location' => $request->work_location,
                    ]);
                    $user->assignRole('Nurse');

                    $reg_user = User::where('email', '=', $request->email)->get()->first();

                    /* mail */
                    $data = [
                        'to_email' => $reg_user->email,
                        'to_name' => $reg_user->first_name . ' ' . $reg_user->last_name
                    ];
                    $replace_array = ['###USERNAME###' => $reg_user->first_name . ' ' . $reg_user->last_name];
                    // $this->basic_email($template = "new_registration", $data, $replace_array);



                    $return_data = $this->profileCompletionFlagStatus($type = "login", $reg_user);
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

    // nurse account information
    public function workerAccountInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'nurse_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker = NURSE::where('id', $request->nurse_id)->get()->first();

            if(isset($worker))
            {
                $worker->country = isset($request->country) ? $request->country : $worker->country;
                $worker->postcode = isset($request->postcode) ? $request->postcode : $worker->postcode;
                $worker->state = isset($request->state) ? $request->state : $worker->state;
                $worker->city = isset($request->city) ? $request->city : $worker->city;
                $worker->street_address = isset($request->street_address) ? $request->street_address : $worker->street_address;
                $worker->date_of_birth = isset($request->date_of_birth) ? $request->date_of_birth : $worker->date_of_birth;
                $worker->mobile = isset($request->mobile) ? $request->mobile : $worker->mobile;
                $worker->email = isset($request->email) ? $request->email : $worker->email;
                $worker->last_name = isset($request->last_name) ? $request->last_name : $worker->last_name;
                $worker->first_name = isset($request->first_name) ? $request->first_name : $worker->first_name;

                $nurse_update = USER::where(['id' => $worker->user_id])
                                    ->update([
                                        'country' => $worker->country,
                                        'postcode' => $worker->postcode,
                                        'state' => $worker->state,
                                        'city' => $worker->city,
                                        'street_address' => $worker->street_address,
                                        'date_of_birth' => $worker->date_of_birth,
                                        'mobile' => $worker->mobile,
                                        'email' => $worker->email,
                                        'last_name' => $worker->last_name,
                                        'first_name' => $worker->first_name,
                                    ]);

                $this->check = "1";
                $this->message = "Worker Account details listed successfully";
                $this->return_data = $worker;
            }else{
                $this->check = "1";
                $this->message = "Worker not found";
                $this->return_data = [];
            }

        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    // bank details setters for worker
    public function setBankingDetails(Request $request)
    {
        // $this->return_data = [];
        $validator = \Validator::make($request->all(), [
            'worker_user_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check = DB::table('nurse_account')->where(['worker_user_id' => $request->worker_user_id])->first();
            if(isset($request->paypal_details)){
                $acc_type = '1';
            }else{
                $acc_type = '0';
            }
            if (isset($check)) {
                $nurse_acc = DB::table('nurse_account')->where([
                    'worker_user_id' => $request->worker_user_id
                ])->update([
                    'worker_user_id' => $request->worker_user_id,
                    'acc_no' => $request->acc_no,
                    'routing_no' => $request->routing_no,
                    'acc_holder_name' => $request->acc_holder_name,
                    'paypal_details' => $request->paypal_details,
                    'acc_type' => $acc_type
                ]);

                $this->check = "1";
                $this->message = "Bank Details Update successfully";
                $this->return_data = $nurse_acc;

            } else {
                $nurse_acc = DB::table('nurse_account')->insert([
                    'worker_user_id' => $request->worker_user_id,
                    'acc_no' => $request->acc_no,
                    'routing_no' => $request->routing_no,
                    'acc_holder_name' => $request->acc_holder_name,
                    'paypal_details' => $request->paypal_details,
                    'acc_type' => $acc_type
                ]);

                $this->check = "1";
                $this->message = "Bank Details save successfully";
                $this->return_data = $nurse_acc;
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

     // bank details getters for worker
     public function getBankingDetails(Request $request)
     {
         // $this->return_data = [];
         $validator = \Validator::make($request->all(), [
             'worker_user_id' => 'required',
             'api_key' => 'required'
         ]);

         if ($validator->fails()) {
             $this->message = $validator->errors()->first();
         } else {
             $check = DB::table('nurse_account')->where(['worker_user_id' => $request->worker_user_id])->first();
             if(isset($check)){
                 $check->acc_no = isset($check->acc_no)?$check->acc_no:'';
                 $check->routing_no = isset($check->routing_no)?$check->routing_no:'';
                 $check->acc_holder_name = isset($check->acc_holder_name)?$check->acc_holder_name:'';
                 $check->paypal_details = isset($check->paypal_details)?$check->paypal_details:'';
                 $check->worker_user_id = isset($check->worker_user_id)?$check->worker_user_id:'';
                 $check->token_id = isset($check->token_id)?$check->token_id:'';
                 $check->email = isset($check->email)?$check->email:'';

                 $this->check = "1";
                 $this->message = "Bank Details listed successfully";
                 $this->return_data = $check;
             }else{
                 $this->check = "1";
                 $this->message = "Worker Not Found";
             }

         }
         return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
     }

     // Worker Profile side
    public function workerProfileHomeScreen(Request $request)
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
                $complete = 60;
                if($complete == 100){
                    // $result['get_profile'] = 1;
                    $get_profile = 1;
                }else{
                    // $result['get_profile'] = 0;
                    $get_profile = 0;
                }

                $this->check = "1";
                $this->message = "Jobs listed successfully";
                $this->return_data = $get_profile;
            }else{
                $this->check = "1";
                $this->message = "Users Not Found";
                $this->return_data = [];
            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    // worker home screen
    public function workerHomeScreen(Request $request)
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
                $nurse = Nurse::where('user_id', $request->user_id)->first();
               if(isset($nurse['worker_vaccination']) && !empty($nurse['worker_vaccination'])){
                $vacc = 25;
               }else{
                $vacc = 0;
               }
               if(isset($nurse['worker_certificate_name']) && !empty($nurse['worker_certificate_name'])){
                $cert = 25;
               }else{
                $cert = 0;
               }
               if(isset($nurse['worker_number_of_references']) && !empty($nurse['worker_number_of_references'])){
                $ref = 25;
               }else{
                $ref = 0;
               }

                $result['total_amount'] = isset($nurse['worker_employer_weekly_amount'])?$nurse['worker_employer_weekly_amount']:'';
                $result['completed'] = round($nurse['profession_information_per']+$vacc+$cert+$ref);
                $result['pending'] = round(100-$result['completed']);
                if($result['completed'] <= 99){
                    $result['completed_per'] = 2;
                }else{
                    $result['completed_per'] = 5;
                }
                $result['pending_per'] = 5;
                $result['revenue'] = round($result['completed_per']);
                $result['completed_amount'] = (!(empty($result['total_amount'])?$result['total_amount']:0)*(!empty($result['revenue'])?$result['revenue']:0))/100;
                $result['completed_amount'] = round($result['completed_amount']);
                if($result['completed_per'] == 100){
                    $result['pending_amount'] = 0;
                }else{
                    $result['pending_amount'] = ((!empty($result['total_amount'])?$result['total_amount']:0)*$result['pending_per'])/100;
                }
                $result['get_additional'] = 5;

                $this->check = "1";
                $this->message = "Jobs listed successfully";
                $this->return_data = $result;

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    // graph home screen information
    public function graphHomeScreen(Request $request)
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
                $nurse = Nurse::where('user_id', $request->user_id)->first();

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

                $Rejected = DB::table('jobs')
                                ->join('offers','jobs.id', '=', 'offers.job_id')
                                ->where(['jobs.recruiter_id' => $request->user_id, 'status' => 'Rejected', 'jobs.is_closed' => '0'])
                                ->select('status', DB::raw('count(*) as total'))
                                ->groupBy('offers.status')
                                ->get();


                $result['Applied'] = isset($New[0])?$New[0]->total:0;
                $result['Offered'] = isset($Offered[0])?$Offered[0]->total:0;
                $result['Onboard'] = isset($Onboard[0])?$Onboard[0]->total:0;
                $result['Hired'] = isset($Working[0])?$Working[0]->total:0;
                $result['Done'] = isset($Done[0])?$Done[0]->total:0;
                $result['Rejected'] = isset($Rejected[0])?$Rejected[0]->total:0;

                $result['total_goodwork_amount'] = isset($nurse['worker_total_goodwork_amount'])?$nurse['worker_total_goodwork_amount']:'';
                $result['total_employer_amount'] = isset($nurse['worker_total_employer_amount'])?$nurse['worker_total_employer_amount']:'';

                $this->check = "1";
                $this->message = "Jobs listed successfully";
                $this->return_data = $result;

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    // worker information
    public function workerInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
            'nurse_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse_data = [];
            $cert = 0;
            $cert_progress = 0.00;
            $ref = 0;
            $ref_progress = 0.00;
            $vacc = 0;
            $vacc_progress = 0.00;
            $basic_profile_comp = 0;
            $basic_progress = 0.00;
            $profileCompOverlay = 0;

            $worker = NURSE::where('id', $request->nurse_id)->get()->first();
            $user = USER::where('id', $request->user_id)->get()->first();
            $worker_reference = NurseReference::where('nurse_id', $request->nurse_id)->get();
            $worker_asset = NurseAsset::where('nurse_id', $request->nurse_id)->get();
            $reference = [];
            $worker_vaccination_name = $worker['worker_vaccination'];
            if(isset($worker_reference)){
                foreach($worker_reference as $rec){
                    // Reference
                    if(isset($rec) && !empty($rec)){
                        $ref = 25;
                        $ref_progress = 100.00;
                        $rec['image'] = url("public/images/nurses/reference/" . $rec['image']);
                    }
                    $reference[] = $rec;
                }
            }

            $vaccination = [];
            $skill = [];
            $driving_license = [];
            $diploma = [];
            $flu = 0;
            $covid = 0;
            $vaccination_names = isset($worker_vaccination_name)?json_decode($worker_vaccination_name):'';
            $z = 0;
            if(isset($worker_asset) && !empty($worker_asset)){
                foreach($worker_asset as $rec)
                {
                    if(($rec['filter'] == 'vaccination') && !empty($vaccination_names)){
                        $vacc_progress = 100.00;
                        $vacc = 25;
                        $rec['name'] = url("public/images/nurses/vaccination/" . $rec['name']);
                        $rec['vaccination_name'] = $vaccination_names[$z];
                        $rec['using_date'] = isset($rec['using_date'])?$rec['using_date']:"";
                        $vaccination[] = $rec;
                        $z++;
                    }

                    if($rec['filter'] == 'skill'){
                        $rec['name'] = url("public/images/nurses/skill/" . $rec['name']);
                        $rec['using_date'] = isset($rec['using_date'])?$rec['using_date']:"";
                        $skill[] = $rec;
                    }

                    if($rec['filter'] == 'driving_license'){
                        $rec['name'] = url("public/images/nurses/driving_license/" . $rec['name']);
                        $rec['using_date'] = isset($rec['using_date'])?$rec['using_date']:"";
                        $driving_license[] = $rec;
                    }
                    if($rec['filter'] == 'diploma'){
                        $rec['name'] = url("public/images/nurses/diploma/" . $rec['name']);
                        $rec['using_date'] = isset($rec['using_date'])?$rec['using_date']:"";
                        $diploma[] = $rec;
                    }
                }
            }

            if(isset($worker))
            {
                $nurse_data = $worker;
                // Basic Profile Information calculateion
                // Basic Info
                if(isset($worker->credential_title)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->credential_title="";}
                if(isset($worker->highest_nursing_degree)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->highest_nursing_degree="";}
                if(isset($worker->specialty)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->specialty="";}
                if(isset($worker->license_type)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->license_type="";}
                // Skills checklist
                if(isset($worker->diploma)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->diploma="";}
                if(isset($worker->driving_license)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->driving_license="";}
                if(isset($worker->recent_experience)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->recent_experience="";}
                if(isset($worker->worked_at_facility_before)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worked_at_facility_before="";}
                if(isset($worker->worker_ss_number)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_ss_number="";}
                if(isset($worker->eligible_work_in_us)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->eligible_work_in_us="";}
                if(isset($worker->skills_checklists)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->skills_checklists="";}
                // Urgency
                if(isset($worker->worker_urgency)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_urgency="";}
                if(isset($worker->available_position)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->available_position="";}
                if(isset($worker->MSP)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->MSP="";}
                if(isset($worker->VMS)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->VMS="";}
                if(isset($worker->submission_VMS)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->submission_VMS="";}
                if(isset($worker->block_scheduling)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->block_scheduling="";}
                // Float requirement
                if(isset($worker->float_requirement)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->float_requirement="";}
                if(isset($worker->facility_shift_cancelation_policy)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->facility_shift_cancelation_policy="";}
                if(isset($worker->contract_termination_policy)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->contract_termination_policy="";}
                if(isset($worker->distance_from_your_home)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->distance_from_your_home="";}
                if(isset($worker->facilities_you_like_to_work_at)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->facilities_you_like_to_work_at="";}
                if(isset($worker->worker_facility_parent_system)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_facility_parent_system="";}
                if(isset($worker->avg_rating_by_facilities)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->avg_rating_by_facilities="";}
                if(isset($worker->worker_avg_rating_by_recruiters)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_avg_rating_by_recruiters="";}
                if(isset($worker->worker_avg_rating_by_employers)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_avg_rating_by_employers="";}
                if(isset($worker->clinical_setting_you_prefer)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->clinical_setting_you_prefer="";}
                // Patient Ratio
                if(isset($worker->worker_patient_ratio)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_patient_ratio="";}
                if(isset($worker->worker_emr)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_emr="";}
                if(isset($worker->worker_unit)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_unit="";}
                if(isset($worker->worker_department)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_department="";}
                if(isset($worker->worker_bed_size)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_bed_size="";}
                if(isset($worker->worker_trauma_level)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_trauma_level="";}
                if(isset($worker->worker_scrub_color)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_scrub_color="";}
                if(isset($worker->worker_facility_city)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_facility_city="";}
                if(isset($worker->worker_facility_state_code)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_facility_state_code="";}
                // Interview dates
                if(isset($worker->worker_interview_dates)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_interview_dates="";}
                if(isset($worker->worker_start_date)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_start_date="";}
                if(isset($worker->worker_shift_time_of_day)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_shift_time_of_day="";}
                if(isset($worker->worker_hours_per_week)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_hours_per_week="";}
                if(isset($worker->worker_guaranteed_hours)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_guaranteed_hours="";}
                if(isset($worker->worker_hours_shift)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_hours_shift="";}
                if(isset($worker->worker_weeks_assignment)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_weeks_assignment="";}
                if(isset($worker->worker_shifts_week)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_shifts_week="";}
                if(isset($worker->worker_referral_bonus)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_referral_bonus="";}
                // Worker Bonus
                if(isset($worker->worker_sign_on_bonus)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_sign_on_bonus=0;}
                if(isset($worker->worker_completion_bonus)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_completion_bonus=0;}
                if(isset($worker->worker_extension_bonus)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_extension_bonus=0;}
                if(isset($worker->worker_other_bonus)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_other_bonus=0;}
                if(isset($worker->how_much_k)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->how_much_k="";}
                if(isset($worker->worker_health_insurance)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_health_insurance="";}
                if(isset($worker->worker_dental)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_dental="";}
                if(isset($worker->worker_vision)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_vision="";}
                if(isset($worker->worker_actual_hourly_rate)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_actual_hourly_rate="";}
                // Feels $/hr
                if(isset($worker->worker_overtime)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_overtime="";}
                if(isset($worker->worker_holiday)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_holiday="";}
                if(isset($worker->worker_on_call)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_on_call="";}
                if(isset($worker->worker_call_back)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_call_back="";}
                if(isset($worker->worker_orientation_rate)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_orientation_rate="";}
                if(isset($worker->worker_weekly_taxable_amount)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_weekly_taxable_amount=0;}
                if(isset($worker->worker_weekly_non_taxable_amount)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_weekly_non_taxable_amount=0;}
                if(isset($worker->worker_employer_weekly_amount)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_employer_weekly_amount=0;}
                if(isset($worker->worker_goodwork_weekly_amount)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_goodwork_weekly_amount=0;}
                if(isset($worker->worker_total_employer_amount)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_total_employer_amount=0;}
                if(isset($worker->worker_total_goodwork_amount)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_total_goodwork_amount=0;}
                if(isset($worker->worker_total_contract_amount)){$basic_profile_comp=$basic_profile_comp+0.373;}else{$worker->worker_total_contract_amount=0;}
                // check null or not
                $worker->skills = isset($worker->skills)?$worker->skills:"";
                $worker->license_issue_date = isset($worker->license_issue_date)?$worker->license_issue_date:"";
                $worker->license_expiry_date = isset($worker->license_expiry_date)?$worker->license_expiry_date:"";
                $worker->worker_vaccination = isset($worker->worker_vaccination)?$worker->worker_vaccination:0;
                $worker->worked_at_facility_before = isset($worker->worked_at_facility_before)?$worker->worked_at_facility_before:"";
                $worker->worker_number_of_references = isset($worker->worker_number_of_references)?$worker->worker_number_of_references:0;
                $worker->worker_people_you_have_reffered = isset($worker->worker_people_you_have_reffered)?$worker->worker_people_you_have_reffered:"";
                $worker->clinical_educator = isset($worker->clinical_educator)?$worker->clinical_educator:0;
                $worker->slug = isset($worker->slug)?$worker->slug:"";
                $worker->updated_at = isset($worker->updated_at)?$worker->updated_at:"";
                $worker->created_at = isset($worker->created_at)?$worker->created_at:"";
                $worker->deleted_at = isset($worker->deleted_at)?$worker->deleted_at:"";
                $worker->recent_experience = isset($worker->recent_experience)?$worker->recent_experience:"";
                $worker->experience = isset($worker->experience)?$worker->experience:"";
                $worker->hourly_pay_rate = isset($worker->hourly_pay_rate)?$worker->hourly_pay_rate:"";
                $worker->highest_nursing_degree = isset($worker->highest_nursing_degree)?$worker->highest_nursing_degree:"";
                $worker->nursing_license_number = isset($worker->nursing_license_number)?$worker->nursing_license_number:"";
                $worker->license_type = isset($worker->license_type)?$worker->license_type:"";
                $worker->nursing_license_state = isset($worker->nursing_license_state)?$worker->nursing_license_state:"";
                $worker->specialty = isset($worker->specialty)?$worker->specialty:"";
                $worker->worker_feels_like_hour = isset($worker->worker_feels_like_hour)?$worker->worker_feels_like_hour:"";
                $worker->other_certificate_name = isset($worker->other_certificate_name)?$worker->other_certificate_name:"";
                $worker->worker_goodwork_number = isset($worker->id)?$worker->id:"";
                $worker->worker_employer_weekly_amount = isset($worker->worker_employer_weekly_amount)?$worker->worker_employer_weekly_amount:0;
                $worker->eligible_work_in_us = isset($worker->eligible_work_in_us)?$worker->eligible_work_in_us:"";


                if($basic_profile_comp >= 24.5){
                    $basic_profile_comp = 25.00;
                }

                $nurse_data['worker_reference'] = $reference;
                $nurse_data['worker_vaccination'] = $vaccination;
                $nurse_data['worker_vaccination_name'] = isset($worker_vaccination_name)?json_decode($worker_vaccination_name):'';
                $nurse_data['skills_checklists'] = $skill;
                $nurse_data['driving_license'] = $driving_license;
                $nurse_data['diploma'] = $diploma;
                $nurse_data['worker_id'] = $worker->id;

                if(isset($worker->worker_certificate_name) && !empty($worker->worker_certificate_name)){
                    $worker_certificate = [];
                    $data = [];
                    $count = 0;
                    foreach(explode(',', $worker->worker_certificate) as $rec){
                        $image[] = url("public/images/nurses/certificate/" . $rec);
                    }
                    foreach(json_decode($worker->worker_certificate_name) as $info){
                        $data['name'][] = $info;
                        $data['image'][] = $image[$count];
                        $count++;
                    }

                    for($j=0; $j< $count; $j++){
                        $worker_certificate[$j]['name'] = $data['name'][$j];
                        $worker_certificate[$j]['image'] = $data['image'][$j];
                    }
                    $nurse_data['worker_certificate'] = $worker_certificate;
                    $nurse_data['worker_certificate_name'] = json_decode($worker->worker_certificate_name);

                    $cert = 25;
                    $cert_progress = 100.00;
                }else{
                    $nurse_data['worker_certificate'] = [];
                    $nurse_data['worker_certificate_name'] = [];
                }

                if($cert == 25){
                    $nurse_data['certifications_check'] = 1;
                }else{
                    $nurse_data['certifications_check'] = 0;
                }
                if($basic_profile_comp == 25){
                    $nurse_data['profession_information_check'] = 1;
                }else{
                    $nurse_data['profession_information_check'] = 0;
                }
                if($vacc == 25){
                    $nurse_data['vaccination_immunization_check'] = 1;
                }else{
                    $nurse_data['vaccination_immunization_check'] = 0;
                }
                if($ref == 25){
                    $nurse_data['references_check'] = 1;
                }else{
                    $nurse_data['references_check'] = 0;
                }

                $nurse_data['profile_comp'] = $cert+$vacc+$ref+$basic_profile_comp;
                $nurse_data['profile_comp'] = round($nurse_data['profile_comp']);
                $nurse_data['profileCompOverlay'] = round($nurse_data['profile_comp'])/100;
                $nurse_data['profession_information_per'] = $basic_profile_comp;
                $nurse_data['vaccination_immunization_per'] = $vacc;
                $nurse_data['references_per'] = $ref;
                $nurse_data['certifications_per'] = $cert;
                $nurse_data['certification_Progress'] = $cert_progress/100;
                $nurse_data['vaccination_Progress'] = $vacc_progress/100;
                $nurse_data['references_Progress'] = $ref_progress/100;
                $basic_progress = $basic_profile_comp*4;
                $nurse_data['profile_Basic_Progress'] = $basic_progress/100;

                if(isset($user)){
                    $nurse_data['user_id'] = $user->id;
                    if(isset($user->image)){
                        $nurse_data['image'] = url("public/images/nurses/profile/" . $user->image);
                    }else{
                        $nurse_data['image'] = "";
                    }
                    $nurse_data['Worker_name'] = $user->first_name.' '.$user->last_name;
                    $nurse_data['country'] = isset($user->country)?$user->country:"";
                    $nurse_data['state'] = isset($user->state)?$user->state:"";
                    $nurse_data['city'] = isset($user->city)?$user->city:"";
                    $nurse_data['date_of_birth'] = isset($user->date_of_birth)?$user->date_of_birth:"";
                    $nurse_data['street_address'] = isset($user->street_address)?$user->street_address:"";
                    $nurse_data['postcode'] = isset($user->postcode)?$user->postcode:"";
                }else{
                    $nurse_data['user_id'] = '';
                    $nurse_data['image'] = '';
                    $nurse_data['Worker_name'] = '';
                }

                // Specialty and experience
                if(isset($worker->specialty) && !empty($worker->specialty))
                {
                    $speciality = explode(',',$worker->specialty);
                    $experiences = explode(',',$worker->experience);
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
                }else{
                    $specialities = [];
                }
                $nurse_data['specialty'] = $specialities;
                if(isset($basic_profile_comp)){
                    Nurse::where('id', $request->nurse_id)->update(['profession_information_per' => $basic_profile_comp, 'worker_goodwork_number' => $request->nurse_id]);
                }
                $this->check = "1";
                $this->message = "Worker profile details listed successfully";
                $this->return_data = $nurse_data;
            }else{
                $this->check = "1";
                $this->message = "Worker not found";
                $this->return_data = [];
            }

        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    //worker basic information
    public function workerBasicInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            // 'user_id' => 'required',
            'api_key' => 'required',
            'nurse_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker = NURSE::where('id', $request->nurse_id)->get()->first();
            if(isset($worker))
            {
                $worker->credential_title = isset($request->title)?$request->title:$worker->credential_title;
                $worker->highest_nursing_degree = isset($request->profession)?$request->profession:$worker->highest_nursing_degree;
                $worker->specialty = isset($request->specialty)?$request->specialty:$worker->specialty;
                $worker->license_type = isset($request->licence)?$request->licence:$worker->license_type;
                $worker->nursing_license_number = isset($request->nursing_license_number)?$request->nursing_license_number:$worker->nursing_license_number;
                $worker->nursing_license_state = isset($request->nursing_license_state)?$request->nursing_license_state:$worker->nursing_license_state;
                $worker->license_expiry_date = isset($request->license_expiry_date)?$request->license_expiry_date:$worker->license_expiry_date;
                $worker->compact_license = isset($request->compact_license)?$request->compact_license:$worker->compact_license;
                $worker->experience = isset($request->experience)?$request->experience:$worker->experience;
                if(isset($worker->worker_goodwork_number) && $worker->worker_goodwork_number != 0 && $worker->worker_goodwork_number !== null && $worker->worker_goodwork_number !== ''){
                    $goodwork_number = $worker->worker_goodwork_number;
                }else{
                    $goodwork_number = uniqid();
                }
                $nurse_update = NURSE::where(['id' => $worker->id])
                                    ->update([
                                            'credential_title' => $worker->credential_title,
                                            'highest_nursing_degree' => $worker->highest_nursing_degree,
                                            'specialty' => $worker->specialty,
                                            'experience' => $worker->experience,
                                            'license_expiry_date' => $worker->license_expiry_date,
                                            'license_type' => $worker->license_type,
                                            'compact_license' => $worker->compact_license,
                                            'nursing_license_number' => $worker->nursing_license_number,
                                            'nursing_license_state' => $worker->nursing_license_state,
                                            'worker_goodwork_number' => $goodwork_number
                                        ]);

                $worker = NURSE::where('id', $request->nurse_id)->get()->first();
                $this->check = "1";
                $this->message = "Worker profile details listed successfully";
                $this->return_data = $worker;
            }else{
                $this->check = "1";
                $this->message = "Worker not found";
                $this->return_data = [];
            }

        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    // worker skilles
    public function workerSkills(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'nurse_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker = NURSE::where('id', $request->nurse_id)->get()->first();
            if(isset($worker))
            {
                // Upload driving license
                if ($request->hasFile('driving_license') && $request->file('driving_license') != null)
                {
                    NurseAsset::where('nurse_id', $request->nurse_id)->where('filter', 'driving_license')->forceDelete();
                    if(!empty($worker->driving_license)){
                        // unlink(public_path('images/nurses/driving_license/').$worker->driving_license);
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
                                                'nurse_id' => $request->nurse_id,
                                                'using_date' => $license_expiration_date,
                                                'name' => $driving_license,
                                                'filter' => 'driving_license',
                                            ]);

                    if(isset($driving_license_asset)){
                        $update = NurseAsset::where(['id' => $driving_license_asset['id']])->update([
                            'using_date' => $license_expiration_date
                        ]);
                    }
                }else{
                    $worker->driving_license = NULL;
                }

                // Upload diploma
                if ($request->hasFile('diploma') && $request->file('diploma') != null)
                {
                    NurseAsset::where('nurse_id', $request->nurse_id)->where('filter', 'diploma')->forceDelete();
                    if(!empty($worker->diploma)){
                        // unlink(public_path('images/nurses/diploma/').$worker->diploma);
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
                        'nurse_id' => $request->nurse_id,
                        'name' => $diploma,
                        'filter' => 'diploma'
                    ]);
                }else{
                    $worker->diploma = NULL;
                }
                // Upload skills
                $using_date = [];
                $count_num = 0;
                if ($request->hasFile('skill') && $request->file('skill') != null) {
                    $images = $request->file('skill');
                    $imageName='';
                    $skills_img = explode(',', $worker->skills_checklists);
                    NurseAsset::where('nurse_id', $request->nurse_id)->where('filter', 'skill')->forceDelete();
                    if(isset($request->completion_date)){
                        $using_date = explode(",",$request->completion_date);
                    }

                    foreach($skills_img as $img_rec){
                        if(isset($img_rec) && !empty($img_rec)){
                            // unlink(public_path('images/nurses/skill/').$img_rec);
                            if(\File::exists(public_path('images/nurses/skill/').$img_rec))
                            {
                                \File::delete(public_path('images/nurses/skill/').$img_rec);
                            }
                        }
                    }
                    foreach($images as $image)
                    {
                        $skill_name_full = $image->getClientOriginalName();
                        $skill_name = pathinfo($skill_name_full, PATHINFO_FILENAME);
                        $skill_ext = $image->getClientOriginalExtension();
                        $skill = $skill_name.'_'.time().'.'.$skill_ext;
                        $destinationPath = 'images/nurses/skill';
                        $image->move(public_path($destinationPath), $skill);
                        $imageName=$imageName.$skill.',';

                        // write image name in worker table
                        // $completion_date = isset($request->completion_date)?$request->completion_date:'';
                        $skill_asset = NurseAsset::create([
                            'nurse_id' => $request->nurse_id,
                            'name' => $skill,
                            'filter' => 'skill',
                            'using_date' => $using_date[$count_num]
                        ]);
                        if(isset($skill_asset)){
                            $update = NurseAsset::where(['id' => $skill_asset['id']])->update([
                                'using_date' => $using_date[$count_num]
                            ]);
                        }
                        $count_num++;
                    }
                    $worker->skills_checklists = $imageName;

                }else{
                    $worker->skills_checklists = NULL;
                    $worker->skills = NULL;
                }
                $worker->recent_experience = isset($request->recent_experience) ? $request->recent_experience : $worker->recent_experience;
                $worker->worked_at_facility_before = isset($request->worked_at_facility_before) ? $request->worked_at_facility_before : $worker->worked_at_facility_before;
                $worker->worker_ss_number = isset($request->ss_card) ? $request->ss_card : $worker->worker_ss_number;
                $worker->skills = isset($request->skills_name) ? $request->skills_name : $worker->skills;
                $worker->eligible_work_in_us = isset($request->eligible_work_in_us) ? $request->eligible_work_in_us : $worker->eligible_work_in_us;
                $nurse_update = NURSE::where(['id' => $worker->id])
                                    ->update([
                                            'worker_ss_number' => $worker->worker_ss_number,
                                            'driving_license' => $worker->driving_license,
                                            'recent_experience' => $worker->recent_experience,
                                            'worked_at_facility_before' => $worker->worked_at_facility_before,
                                            'eligible_work_in_us' => $worker->eligible_work_in_us,
                                            'skills_checklists' => $worker->skills_checklists,
                                            'skills' => $worker->skills,
                                            'diploma' => $worker->diploma,
                                        ]);
                $worker = NURSE::where('id', $request->nurse_id)->get()->first();
                $this->check = "1";
                $this->message = "Worker Skills details listed successfully";
                $this->return_data = $worker;
            }else{
                $this->check = "1";
                $this->message = "Worker not found";
                $this->return_data = [];
            }

        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    // worker vacinations details
    public function workerVaccination(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            // 'user_id' => 'required',
            'api_key' => 'required',
            'nurse_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker = NURSE::where('id', $request->nurse_id)->get()->first();
            $vaccination = [];
            $imageName='';
            if(isset($worker))
            {
                if(isset($request->worker_vaccination) && !empty($request->worker_vaccination))
                {
                    $worker_vaccination = explode(',', $request->worker_vaccination);
                    $vaccine_date = explode(',', $request->date);
                    foreach($worker_vaccination as $vac){
                        $vaccination[] = $vac;
                    }
                    foreach($vaccine_date as $dates){
                        $date[] = $dates;
                    }

                    // upload vaccination
                    if ($request->hasFile('image') && $request->file('image') != null)
                    {
                        $images = $request->file('image');
                        $workerVaccinationExist = NurseAsset::where(['nurse_id' => $request->nurse_id,'filter' => 'vaccination'])->get();
                        if(isset($request->worker_vaccination))
                        {
                            if(isset($workerVaccinationExist) && !empty($workerVaccinationExist)){
                                NurseAsset::where('nurse_id', $request->nurse_id)->where('filter', 'vaccination')->forceDelete();
                                foreach($workerVaccinationExist as $record)
                                {
                                    if(\File::exists(public_path('images/nurses/vaccination/').$record['name']))
                                    {
                                        \File::delete(public_path('images/nurses/vaccination/').$record['name']);
                                    }
                                }
                            }
                        }
                        $i = 0;
                        foreach($images as $image){
                            $vaccination_name_full = $image->getClientOriginalName();
                            $vaccination_name = pathinfo($vaccination_name_full, PATHINFO_FILENAME);
                            $vaccination_ext = $image->getClientOriginalExtension();
                            $vaccinationation = $vaccination_name.'_'.time().'.'.$vaccination_ext;
                            $destinationPath = 'images/nurses/vaccination';
                            $image->move(public_path($destinationPath), $vaccinationation);
                            if(isset($vaccinationation)){
                                $imageName=$imageName.$vaccinationation.',';
                            }


                            // write image name in worker table
                            $vaccination_asset = NurseAsset::create([
                                'nurse_id' => $request->nurse_id,
                                'name' => $vaccinationation,
                                'using_date' => $date[$i],
                                'filter' => 'vaccination'
                            ]);
                            $update = NurseAsset::where(['id' => $vaccination_asset['id']])->update([
                                'using_date' => $date[$i]
                            ]);
                            $i++;
                        }
                    }

                    $vaccination = json_encode($vaccination);
                    $nurse_update = NURSE::where(['id' => $worker->id])
                                        ->update([
                                                'worker_vaccination' => $vaccination,
                                            ]);
                    $worker = NURSE::where('id', $request->nurse_id)->get()->first();

                    $this->check = "1";
                    $this->message = "Worker Skills details listed successfully";
                    $this->return_data = $worker;
                }else{
                    $nurse_update = NURSE::where(['id' => $worker->id])
                                        ->update([
                                                // 'worker_vaccination' => $worker->worker_vaccination,
                                                'worker_vaccination' => NULL,
                                            ]);
                    $worker = NURSE::where('id', $request->nurse_id)->get()->first();
                    $this->check = "1";
                    $this->message = "Worker Skills details listed successfully";
                    $this->return_data = $worker;
                }
            }else{
                $this->check = "1";
                $this->message = "Worker not found";
                $this->return_data = [];
            }

        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    // worker references details
    public function workerReferrence(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'nurse_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker = NURSE::where('id', $request->nurse_id)->get()->first();
            $workerReferenceExist = NurseReference::where('nurse_id', $request->nurse_id)->get();

            if(isset($worker))
            {
                if(isset($request->number_of_reference) && $request->number_of_reference > 0){
                    if(isset($workerReferenceExist) && !empty($workerReferenceExist)){
                        NurseReference::where('nurse_id', $request->nurse_id)->forceDelete();
                        foreach($workerReferenceExist as $record)
                        {
                            if(\File::exists(public_path('images/nurses/reference/').$record['image']))
                            {
                                \File::delete(public_path('images/nurses/reference/').$record['image']);
                            }
                        }
                    }

                    $worker->worker_number_of_references = isset($request->number_of_reference) ? $request->number_of_reference : $worker->worker_number_of_references;
                    if(isset($request->name) && !empty($request->name)){
                        $name = explode(",",$request->name);
                    }
                    if(isset($request->phone) && !empty($request->phone)){
                        $phone = explode(",",$request->phone);
                    }
                    if(isset($request->email) && !empty($request->email)){
                        $email = explode(",",$request->email);
                    }
                    if(isset($request->date_referred) && !empty($request->date_referred)){
                        $date_referred = explode(",",$request->date_referred);
                    }
                    if(isset($request->title_of_reference) && !empty($request->title_of_reference)){
                        $title_of_reference = explode(",",$request->title_of_reference);
                    }
                    if(isset($request->recency_of_reference) && !empty($request->recency_of_reference)){
                        $recency_of_reference = explode(",",$request->recency_of_reference);
                    }

                    if ($request->hasFile('image') && $request->file('image') != null) {
                        $images = $request->file('image');
                        $imageName='';
                        $i=0;
                        foreach($images as $image){
                            $reference_name_full = $image->getClientOriginalName();
                            $reference_name = pathinfo($reference_name_full, PATHINFO_FILENAME);
                            $reference_ext = $image->getClientOriginalExtension();
                            $reference = $reference_name.'_'.time().'.'.$reference_ext;
                            $destinationPath = 'images/nurses/reference';
                            $image->move(public_path($destinationPath), $reference);
                            $imageName=$imageName.$reference.',';

                            // write image name in worker table
                            $reference_asset = NurseReference::create([
                                'nurse_id' => $request->nurse_id,
                                'name' => $name[$i],
                                'email' => $email[$i],
                                'phone' => $phone[$i],
                                'date_referred' => $date_referred[$i],
                                'min_title_of_reference' => $title_of_reference[$i],
                                'recency_of_reference' => $recency_of_reference[$i],
                                'image' => $reference
                            ]);
                            if(isset($reference_asset)){
                                $update = NurseReference::where(['id' => $reference_asset['id']])->update([
                                    'date_referred' => $date_referred[$i]
                                ]);
                            }
                            $i++;
                        }
                    }else{
                        $pointnum = $worker->worker_number_of_references;
                        if(isset($request->name) && !empty($request->name)){
                            $name = explode(",",$request->name);
                        }
                        if(isset($request->phone) && !empty($request->phone)){
                            $phone = explode(",",$request->phone);
                        }
                        if(isset($request->email) && !empty($request->email)){
                            $email = explode(",",$request->email);
                        }
                        if(isset($request->date_referred) && !empty($request->date_referred)){
                            $date_referred = explode(",",$request->date_referred);
                        }
                        if(isset($request->title_of_reference) && !empty($request->title_of_reference)){
                            $title_of_reference = explode(",",$request->title_of_reference);
                        }
                        if(isset($request->recency_of_reference) && !empty($request->recency_of_reference)){
                            $recency_of_reference = explode(",",$request->recency_of_reference);
                        }
                        // $i=0;
                        for($i=0; $i<$pointnum; $i++)
                        {
                            $reference_asset = NurseReference::create([
                                'nurse_id' => $request->nurse_id,
                                'name' => isset($name[$i])?$name[$i]:"",
                                'email' => isset($email[$i])?$email[$i]:"",
                                'phone' => isset($phone[$i])?$phone[$i]:"",
                                'date_referred' => isset($date_referred[$i])?$date_referred[$i]:"",
                                'min_title_of_reference' => isset($title_of_reference[$i])?$title_of_reference[$i]:"",
                                'recency_of_reference' => isset($recency_of_reference[$i])?$recency_of_reference[$i]:"",
                                'image' => null
                            ]);
                            if(isset($reference_asset)){
                                $update = NurseReference::where(['id' => $reference_asset['id']])->update([
                                    'date_referred' => $date_referred[$i]
                                ]);
                            }
                        }
                    }
                }
                $nurse_update = NURSE::where(['id' => $worker->id])
                                    ->update([
                                        'worker_number_of_references' => $worker->worker_number_of_references,
                                    ]);

                $this->check = "1";
                $this->message = "Worker Reference details listed successfully";
                $this->return_data = $worker;
            }else{
                $this->check = "1";
                $this->message = "Worker not found";
                $this->return_data = [];
            }

        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    // worker certificates details
    public function workerCertificates(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'nurse_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker = NURSE::where('id', $request->nurse_id)->get()->first();
            $imageName='';
            if(isset($worker))
            {
                $workerCertificateExist = NurseAsset::where(['nurse_id' => $request->nurse_id,'filter' => 'certificate'])->get();
                if(isset($request->worker_certificate_name) && $request->worker_certificate_name != '')
                {
                    if(isset($workerCertificateExist) && !empty($workerCertificateExist)){
                        NurseAsset::where('nurse_id', $request->nurse_id)->where('filter', 'certificate')->forceDelete();
                        foreach($workerCertificateExist as $record)
                        {
                            if(\File::exists(public_path('images/nurses/certificate/').$record['name']))
                            {
                                \File::delete(public_path('images/nurses/certificate/').$record['name']);
                            }
                        }
                    }
                }

                if ($request->hasFile('worker_certificate') && $request->file('worker_certificate') != null) {
                    $images = $request->file('worker_certificate');

                    foreach($images as $image){
                        $certificate_name_full = $image->getClientOriginalName();
                        $certificate_name = pathinfo($certificate_name_full, PATHINFO_FILENAME);
                        $certificate_ext = $image->getClientOriginalExtension();
                        $certificate = $certificate_name.'_'.time().'.'.$certificate_ext;
                        $destinationPath = 'images/nurses/certificate';
                        $image->move(public_path($destinationPath), $certificate);
                        if(isset($certificate) && !empty($certificate)){
                            $imageName=$imageName.$certificate.',';
                        }

                        // write image name in worker table
                        $certificate_asset = NurseAsset::create([
                            'nurse_id' => $request->nurse_id,
                            'name' => $certificate,
                            'filter' => 'certificate'
                        ]);
                    }
                }else{
                    $imageName = NULL;
                }

                if(!empty($request->worker_certificate_name)){
                    $worker->worker_certificate_name = explode(",",$request->worker_certificate_name);
                }else{
                    $worker->worker_certificate_name = NULL;
                }

                $nurse_update = NURSE::where(['id' => $worker->id])
                                ->update([
                                    'worker_certificate_name' => $worker->worker_certificate_name,
                                    'worker_certificate' => $imageName
                                ]);



                $this->check = "1";
                $this->message = "Worker certification details listed successfully";
                $this->return_data = $worker;
            }else{
                $this->check = "1";
                $this->message = "Worker not found";
                $this->return_data = [];
            }

        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    // Worker Urgency details
    public function workerUrgency(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'nurse_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker = NURSE::where('id', $request->nurse_id)->get()->first();

            if(isset($worker))
            {
                $worker->worker_urgency = isset($request->worker_urgency) ? $request->worker_urgency : $worker->worker_urgency;
                $worker->available_position = isset($request->available_position) ? $request->available_position : $worker->available_position;
                $worker->MSP = isset($request->MSP) ? $request->MSP : $worker->MSP;
                $worker->VMS = isset($request->VMS) ? $request->VMS : $worker->VMS;
                $worker->submission_VMS = isset($request->submission_VMS) ? $request->submission_VMS : $worker->submission_VMS;
                $worker->block_scheduling = isset($request->block_scheduling) ? $request->block_scheduling : $worker->block_scheduling;
                if(!empty($worker->worker_recency_of_reference)){
                    $title = explode(",",$worker->worker_min_title_of_reference);
                    $recency = explode(",",$worker->worker_recency_of_reference);
                }

                $nurse_update = NURSE::where(['id' => $worker->id])
                                    ->update([
                                        'worker_urgency' => $worker->worker_urgency,
                                        'available_position' => $worker->available_position,
                                        'MSP' => $worker->MSP,
                                        'VMS' => $worker->VMS,
                                        'submission_VMS' => $worker->submission_VMS,
                                        'block_scheduling' => $worker->block_scheduling,
                                    ]);

                $this->check = "1";
                $this->message = "Worker Urgency details listed successfully";
                $this->return_data = $worker;
            }else{
                $this->check = "1";
                $this->message = "Worker not found";
                $this->return_data = [];
            }

        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    // Worker Facility Info details
    public function workerFacilityInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'nurse_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker = NURSE::where('id', $request->nurse_id)->get()->first();

            if(isset($worker))
            {
                $worker->float_requirement = isset($request->float_requirement) ? $request->float_requirement : $worker->float_requirement;
                $worker->facility_shift_cancelation_policy = isset($request->facility_shift_cancelation_policy) ? $request->facility_shift_cancelation_policy : $worker->facility_shift_cancelation_policy;
                $worker->contract_termination_policy = isset($request->contract_termination_policy) ? $request->contract_termination_policy : $worker->contract_termination_policy;
                $worker->distance_from_your_home = isset($request->distance_from_your_home) ? $request->distance_from_your_home : $worker->distance_from_your_home;
                $worker->facilities_you_like_to_work_at = isset($request->facilities_you_like_to_work_at) ? $request->facilities_you_like_to_work_at : $worker->facilities_you_like_to_work_at;
                $worker->worker_facility_parent_system = isset($request->worker_facility_parent_system) ? $request->worker_facility_parent_system : $worker->worker_facility_parent_system;
                $worker->avg_rating_by_facilities = isset($request->avg_rating_by_facilities) ? $request->avg_rating_by_facilities : $worker->avg_rating_by_facilities;
                $worker->worker_avg_rating_by_recruiters = isset($request->worker_avg_rating_by_recruiters) ? $request->worker_avg_rating_by_recruiters : $worker->worker_avg_rating_by_recruiters;
                $worker->worker_avg_rating_by_employers = isset($request->worker_avg_rating_by_employers) ? $request->worker_avg_rating_by_employers : $worker->worker_avg_rating_by_employers;
                $worker->clinical_setting_you_prefer = isset($request->clinical_setting_you_prefer) ? $request->clinical_setting_you_prefer : $worker->clinical_setting_you_prefer;

                if($worker->distance_from_your_home == 'NA'){
                    $worker->distance_from_your_home = 0;
                }
                if($worker->avg_rating_by_facilities == 'NA'){
                    $worker->avg_rating_by_facilities = 0;
                }
                if($worker->worker_avg_rating_by_recruiters == 'NA'){
                    $worker->worker_avg_rating_by_recruiters = 0;
                }
                if($worker->worker_avg_rating_by_employers == 'NA'){
                    $worker->worker_avg_rating_by_employers = 0;
                }
                $nurse_update = NURSE::where(['id' => $worker->id])
                                    ->update([
                                        'float_requirement' => $worker->float_requirement,
                                        'facility_shift_cancelation_policy' => $worker->facility_shift_cancelation_policy,
                                        'contract_termination_policy' => $worker->contract_termination_policy,
                                        'distance_from_your_home' => $worker->distance_from_your_home,
                                        'facilities_you_like_to_work_at' => $worker->facilities_you_like_to_work_at,
                                        'worker_facility_parent_system' => $worker->worker_facility_parent_system,
                                        'avg_rating_by_facilities' => $worker->avg_rating_by_facilities,
                                        'worker_avg_rating_by_recruiters' => $worker->worker_avg_rating_by_recruiters,
                                        'worker_avg_rating_by_employers' => $worker->worker_avg_rating_by_employers,
                                        'clinical_setting_you_prefer' => $worker->clinical_setting_you_prefer
                                    ]);

                $this->check = "1";
                $this->message = "Worker Facility Info details listed successfully";
                $this->return_data = $worker;
            }else{
                $this->check = "1";
                $this->message = "Worker not found";
                $this->return_data = [];
            }

        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    // Worker patientRatio details
    public function patientRatio(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'nurse_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker = NURSE::where('id', $request->nurse_id)->get()->first();
            if(isset($worker))
            {
                $worker->worker_patient_ratio = isset($request->worker_patient_ratio) ? $request->worker_patient_ratio : $worker->worker_patient_ratio;
                $worker->worker_emr = isset($request->emr) ? $request->emr : $worker->worker_emr;
                $worker->worker_unit = isset($request->unit) ? $request->unit : $worker->worker_unit;
                $worker->worker_department = isset($request->department) ? $request->department : $worker->worker_department;
                $worker->worker_bed_size = isset($request->bed_size) ? $request->bed_size : $worker->worker_bed_size;
                $worker->worker_trauma_level = isset($request->trauma_level) ? $request->trauma_level : $worker->worker_trauma_level;
                $worker->worker_scrub_color = isset($request->scrub_color) ? $request->scrub_color : $worker->worker_scrub_color;
                $worker->worker_facility_city = isset($request->facility_city) ? $request->facility_city : $worker->worker_facility_city;
                $worker->worker_facility_state_code = isset($request->facility_state_code) ? $request->facility_state_code : $worker->worker_facility_state_code;

                $nurse_update = NURSE::where(['id' => $worker->id])
                                    ->update([
                                        'worker_patient_ratio' => $worker->worker_patient_ratio,
                                        'worker_emr' => $worker->worker_emr,
                                        'worker_unit' => $worker->worker_unit,
                                        'worker_department' => $worker->worker_department,
                                        'worker_bed_size' => $worker->worker_bed_size,
                                        'worker_trauma_level' => $worker->worker_trauma_level,
                                        'worker_scrub_color' => $worker->worker_scrub_color,
                                        'worker_facility_city' => $worker->worker_facility_city,
                                        'worker_facility_state_code' => $worker->worker_facility_state_code
                                    ]);

                $this->check = "1";
                $this->message = "Worker Skills details listed successfully";
                $this->return_data = $worker;
            }else{
                $this->check = "1";
                $this->message = "Worker not found";
                $this->return_data = [];
            }

        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    // interview details
    public function interviewDate(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'nurse_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker = NURSE::where('id', $request->nurse_id)->get()->first();
            if(isset($worker))
            {
                $worker->worker_interview_dates = isset($request->interview_date) ? $request->interview_date : $worker->worker_interview_dates;
                $worker->worker_start_date = isset($request->start_date) ? $request->start_date : $worker->worker_start_date;
                $worker->worker_as_soon_as_posible = isset($request->worker_as_soon_as_posible) ? $request->worker_as_soon_as_posible : $worker->worker_as_soon_as_posible;
                $worker->worker_shift_time_of_day = isset($request->shift_time_of_day) ? $request->shift_time_of_day : $worker->worker_shift_time_of_day;
                $worker->worker_hours_per_week = isset($request->hours_per_week) ? $request->hours_per_week : $worker->worker_hours_per_week;
                $worker->worker_guaranteed_hours = isset($request->guaranteed_hours) ? $request->guaranteed_hours : $worker->worker_guaranteed_hours;
                $worker->worker_hours_shift = isset($request->hour_shift) ? $request->hour_shift : $worker->worker_hours_shift;
                $worker->worker_weeks_assignment = isset($request->weeks_assignment) ? $request->weeks_assignment : $worker->worker_weeks_assignment;
                $worker->worker_shifts_week = isset($request->shift_week) ? $request->shift_week : $worker->worker_shifts_week;
                $worker->worker_referral_bonus = isset($request->referral_bonus) ? $request->referral_bonus : $worker->worker_referral_bonus;

                $nurse_update = NURSE::where(['id' => $worker->id])
                                    ->update([
                                        'worker_interview_dates' => $worker->worker_interview_dates,
                                        'worker_start_date' => $worker->worker_start_date,
                                        'worker_as_soon_as_posible' => $worker->worker_as_soon_as_posible,
                                        'worker_shift_time_of_day' => $worker->worker_shift_time_of_day,
                                        'worker_hours_per_week' => $worker->worker_hours_per_week,
                                        'worker_guaranteed_hours' => $worker->worker_guaranteed_hours,
                                        'worker_hours_shift' => $worker->worker_hours_shift,
                                        'worker_weeks_assignment' => $worker->worker_weeks_assignment,
                                        'worker_shifts_week' => $worker->worker_shifts_week,
                                        'worker_referral_bonus' => $worker->worker_referral_bonus
                                    ]);

                $this->check = "1";
                $this->message = "Worker Skills details listed successfully";
                $this->return_data = $worker;
            }else{
                $this->check = "1";
                $this->message = "Worker not found";
                $this->return_data = [];
            }

        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    // worker bonus details
    public function workerBonus(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'nurse_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker = NURSE::where('id', $request->nurse_id)->get()->first();
            if(isset($worker))
            {
                $worker->worker_sign_on_bonus = isset($request->sign_on_bonus) ? $request->sign_on_bonus : $worker->worker_sign_on_bonus;
                $worker->worker_completion_bonus = isset($request->completion_bonus) ? $request->completion_bonus : $worker->worker_completion_bonus;
                $worker->worker_extension_bonus = isset($request->extension_bonus) ? $request->extension_bonus : $worker->worker_extension_bonus;
                $worker->worker_other_bonus = isset($request->other_bonus) ? $request->other_bonus : $worker->worker_other_bonus;
                $worker->how_much_k = isset($request->how_much_k) ? $request->how_much_k : $worker->how_much_k;
                $worker->worker_health_insurance = isset($request->health_insurance) ? $request->health_insurance : $worker->worker_health_insurance;
                $worker->worker_dental = isset($request->dental) ? $request->dental : $worker->worker_dental;
                $worker->worker_vision = isset($request->vision) ? $request->vision : $worker->worker_vision;
                $worker->worker_actual_hourly_rate = isset($request->actual_hourly_rate) ? $request->actual_hourly_rate : $worker->worker_actual_hourly_rate;

                $nurse_update = NURSE::where(['id' => $worker->id])
                                    ->update([
                                        'worker_sign_on_bonus' => $worker->worker_sign_on_bonus,
                                        'worker_completion_bonus' => $worker->worker_completion_bonus,
                                        'worker_extension_bonus' => $worker->worker_extension_bonus,
                                        'worker_other_bonus' => $worker->worker_other_bonus,
                                        'how_much_k' => $worker->how_much_k,
                                        'worker_health_insurance' => $worker->worker_health_insurance,
                                        'worker_dental' => $worker->worker_dental,
                                        'worker_vision' => $worker->worker_vision,
                                        'worker_actual_hourly_rate' => $worker->worker_actual_hourly_rate
                                    ]);

                $this->check = "1";
                $this->message = "Worker Skills details listed successfully";
                $this->return_data = $worker;
            }else{
                $this->check = "1";
                $this->message = "Worker not found";
                $this->return_data = [];
            }

        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    // working hours
    public function workerFeelsLikeHour(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'nurse_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker = NURSE::where('id', $request->nurse_id)->get()->first();
            if(isset($worker))
            {
                $worker->worker_overtime = isset($request->overtime) ? $request->overtime : $worker->worker_overtime;
                $worker->worker_holiday = isset($request->holiday) ? $request->holiday : $worker->worker_holiday;
                $worker->worker_on_call = isset($request->on_call) ? $request->on_call : $worker->worker_on_call;
                $worker->worker_call_back = isset($request->call_back) ? $request->call_back : $worker->worker_call_back;
                $worker->worker_orientation_rate = isset($request->orientation_rate) ? $request->orientation_rate : $worker->worker_orientation_rate;
                $worker->worker_weekly_non_taxable_amount = isset($request->weekly_non_taxable_amount) ? $request->weekly_non_taxable_amount : $worker->worker_weekly_non_taxable_amount;


                if($worker->worker_weekly_non_taxable_amount == 'NA'){
                    $worker->worker_weekly_non_taxable_amount = 0;
                }
                if($worker->worker_weekly_taxable_amount == 'NA'){
                    $worker->worker_weekly_taxable_amount = 0;
                }
                if($worker->worker_sign_on_bonus == 'NA'){
                    $worker->worker_sign_on_bonus = 0;
                }
                if($worker->worker_completion_bonus == 'NA'){
                    $worker->worker_completion_bonus = 0;
                }
                if($worker->worker_hours_per_week == 'NA'){
                    $worker->worker_hours_per_week = 0;
                }
                if($worker->worker_actual_hourly_rate == 'NA'){
                    $worker->worker_actual_hourly_rate = 0;
                }
                if($worker->worker_weeks_assignment == 'NA'){
                    $worker->worker_weeks_assignment = 0;
                }
                $worker->worker_weekly_taxable_amount = $worker->worker_hours_per_week*$worker->worker_actual_hourly_rate;
                $worker->worker_employer_weekly_amount = $worker->worker_weekly_taxable_amount+$worker->worker_weekly_non_taxable_amount;
                $worker->worker_goodwork_weekly_amount = $worker->worker_employer_weekly_amount*0.05;
                $worker->worker_total_employer_amount = ($worker->worker_weeks_assignment*$worker->worker_employer_weekly_amount)+$worker->worker_sign_on_bonus+$worker->worker_completion_bonus;
                $worker->worker_total_goodwork_amount = $worker->worker_weeks_assignment*$worker->worker_goodwork_weekly_amount;
                $worker->worker_total_contract_amount = $worker->worker_total_employer_amount+$worker->worker_total_goodwork_amount;
                if($worker->worker_hours_per_week == 0){
                    $worker->worker_feels_like_hour = 0;
                }else{
                    $worker->worker_feels_like_hour = $worker->worker_employer_weekly_amount/$worker->worker_hours_per_week;
                }
                $nurse_update = NURSE::where(['id' => $worker->id])
                                    ->update([
                                        'worker_overtime' => $worker->worker_overtime,
                                        'worker_holiday' => $worker->worker_holiday,
                                        'worker_on_call' => $worker->worker_on_call,
                                        'worker_call_back' => $worker->worker_call_back,
                                        'worker_orientation_rate' => $worker->worker_orientation_rate,
                                        'worker_weekly_taxable_amount' => $worker->worker_weekly_taxable_amount,
                                        'worker_weekly_non_taxable_amount' => $worker->worker_weekly_non_taxable_amount,
                                        'worker_employer_weekly_amount' => $worker->worker_employer_weekly_amount,
                                        'worker_goodwork_weekly_amount' => $worker->worker_goodwork_weekly_amount,
                                        'worker_total_employer_amount' => $worker->worker_total_employer_amount,
                                        'worker_total_goodwork_amount' => $worker->worker_total_goodwork_amount,
                                        'worker_total_contract_amount' => $worker->worker_total_contract_amount,
                                        'worker_feels_like_hour' => $worker->worker_feels_like_hour,
                                    ]);

                $this->check = "1";
                $this->message = "Worker Feels Like/Hour details listed successfully";
                $this->return_data = $worker;
            }else{
                $this->check = "1";
                $this->message = "Worker not found";
                $this->return_data = [];
            }

        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    // worker mobile infromation
    public function NurseProfileInfoBymobile(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'mobile' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('mobile', $request->mobile);
            if ($user_info->count() > 0) {
                $user = $user_info->first();
                $this->check = "1";
                $this->message = "User profile details listed successfully";
                $this->return_data = $this->profileCompletionFlagStatus($type = "", $user);
            } else {
                $this->message = "Nurse not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    // Emedical Records Options : (Need getMedicalRecords() function to be implemented to the this controller)
    public function getEMedicalRecordsOptions()
    {
        $controller = new controller();
        // getEmedicalRecords() doesn't implemented yet
        $electronic_medical_records = $controller->getEMedicalRecords()->pluck('title', 'id');
        $data = [];
        foreach ($electronic_medical_records as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "EMedical records options has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    // Save Worker Profile picture
    public function profilePictureUpload(Request $request)
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
                $nurse_info = NURSE::where('user_id', $user->id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->get()->first();
                    if ($request->hasFile('profile_image') && $request->file('profile_image') != null) {
                        $profile_image_name_full = $request->file('profile_image')->getClientOriginalName();
                        $profile_image_name = pathinfo($profile_image_name_full, PATHINFO_FILENAME);
                        $profile_image_ext = $request->file('profile_image')->getClientOriginalExtension();
                        $profile_image = $profile_image_name.'_'.time().'.'.$profile_image_ext;

                        // $request->file('profile_image')->storeAs('assets/nurses/profile', $nurse->id);

                        $destinationPath = 'images/nurses/profile';
                        $request->file('profile_image')->move(public_path($destinationPath), $profile_image);

                        $update_array['image'] = $profile_image;
                        $update = USER::where(['id' => $user->id])->update($update_array);
                        if ($update == true) {
                            $this->check = "1";
                            $this->message = "Profile picture updated successfully";
                        } else {
                            $this->message = "Failed to update profile picture, please try again later";
                        }
                    } else {
                        $this->message = "Profile image not found";
                    }
                } else {
                    $this->message = "NUrse not found";
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    // Updating the worker details/Interests
    public function updateRoleInterest(Request $request)
    {
        $messages = [
            "additional_pictures.max" => "Additional Photos can't be more than 4.",
            "additional_files.max" => "Additional Files can't be more than 4.",
            "additional_pictures.*.mimes" => "Additional Photos should be image or png jpg",
            "additional_files.*.mimes" => "Additional Files should be doc or pdf",
            "additional_pictures.*.max" => "Additional Photos should not be more than 5mb",
            "additional_files.*.max" => "Additional Files should not be more than 1mb",
            "leadership_roles.required_if" => "Please select leadership role",
            "nu_video.url" => "YouTube and Vimeo should be a valid link",
            "nu_video.max" => "YouTube and Vimeo should be a valid link"
        ];

        $validator = \Validator::make(
            $request->all(),
            [
                'user_id' => 'required',
                'additional_pictures' => 'max:4',
                'additional_pictures.*' => 'nullable|max:5120|image|mimes:jpeg,png,jpg',
                'serving_preceptor' => 'boolean',
                'serving_interim_nurse_leader' => 'boolean',
                'leadership_roles' => 'required_if:serving_interim_nurse_leader,1',
                'clinical_educator' => 'boolean',
                'is_daisy_award_winner' => 'boolean',
                'employee_of_the_mth_qtr_yr' => 'boolean',
                'other_nursing_awards' => 'boolean',
                'is_professional_practice_council' => 'boolean',
                'is_research_publications' => 'boolean',
                'additional_files' => 'max:4',
                'additional_files.*' => 'nullable|max:1024|mimes:pdf,doc,docx',
                'nu_video' => 'nullable|url|max:255',
                'api_key' => 'required',
            ],
            $messages
        );

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            $response = [];
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $nurse_info = NURSE::where('user_id', $user->id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->get()->first();

                    if (preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->nu_video, $vresult)) {
                        $youTubeID = $this->parse_youtube($request->nu_video);
                        $embedURL = 'https://www.youtube.com/embed/' . $youTubeID[1];
                        $nurse_array["nu_video_embed_url"] = $embedURL;
                    } elseif (preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->nu_video, $vresult)) {
                        $vimeoID = $this->parse_vimeo($request->nu_video);
                        $embedURL = 'https://player.vimeo.com/video/' . $vimeoID[1];
                        $nurse_array["nu_video_embed_url"] = $embedURL;
                    }
                    if (isset($request->serving_preceptor) && $request->serving_preceptor != "") $nurse_array['serving_preceptor'] = $request->serving_preceptor;
                    else $nurse_array['serving_preceptor'] = "0";
                    if (isset($request->serving_interim_nurse_leader) && $request->serving_interim_nurse_leader != "") $nurse_array['serving_interim_nurse_leader'] = $request->serving_interim_nurse_leader;
                    else $nurse_array['serving_interim_nurse_leader'] = "0";
                    if (isset($request->clinical_educator) && $request->clinical_educator != "") $nurse_array['clinical_educator'] = $request->clinical_educator;
                    else $nurse_array['clinical_educator'] = "0";
                    if (isset($request->is_daisy_award_winner) && $request->is_daisy_award_winner != "") $nurse_array['is_daisy_award_winner'] = $request->is_daisy_award_winner;
                    else $nurse_array['is_daisy_award_winner'] = "0";
                    if (isset($request->employee_of_the_mth_qtr_yr) && $request->employee_of_the_mth_qtr_yr != "") $nurse_array['employee_of_the_mth_qtr_yr'] = $request->employee_of_the_mth_qtr_yr;
                    else $nurse_array['employee_of_the_mth_qtr_yr'] = "0";
                    if (isset($request->other_nursing_awards) && $request->other_nursing_awards != "") $nurse_array['other_nursing_awards'] = $request->other_nursing_awards;
                    else $nurse_array['other_nursing_awards'] = "0";
                    if (isset($request->is_professional_practice_council) && $request->is_professional_practice_council != "") $nurse_array['is_professional_practice_council'] = $request->is_professional_practice_council;
                    else $nurse_array['is_professional_practice_council'] = "0";
                    if (isset($request->is_research_publications) && $request->is_research_publications != "") $nurse_array['is_research_publications'] = $request->is_research_publications;
                    else $nurse_array['is_research_publications'] = "0";
                    if (isset($request->leadership_roles) && $request->leadership_roles != "") $nurse_array['leadership_roles'] = $request->leadership_roles;
                    if (isset($request->languages) && $request->languages != "") $nurse_array['languages'] = $request->languages;
                    if (isset($request->summary) && $request->summary != "") $nurse_array['summary'] = $request->summary;
                    /* if (isset($request->languages) && $request->languages) {
                        $explode = explode(",", $request->languages);
                        $nurse_array['languages'] = (is_array($explode) && !empty($explode)) ? implode(",", $explode) : "";
                    } */
                    $nurse_update = NURSE::where(['id' => $nurse->id])->update($nurse_array);

                    $additional_pictures_status = false;
                    if ($additional_photos = $request->file('additional_pictures')) {
                        foreach ($additional_photos as $additional_photo) {
                            $additional_photo_name_full = $additional_photo->getClientOriginalName();
                            $additional_photo_name = pathinfo($additional_photo_name_full, PATHINFO_FILENAME);
                            $additional_photo_ext = $additional_photo->getClientOriginalExtension();
                            $additional_photo_finalname = $additional_photo_name . '_' . time() . '.' . $additional_photo_ext;
                            //Upload Image
                            $additional_photo->storeAs('assets/nurses/additional_photos/' . $nurse->id, $additional_photo_finalname);
                            $additional_pictures_insert = NurseAsset::create([
                                'nurse_id' => $nurse->id,
                                'name' => $additional_photo_finalname,
                                'filter' => 'additional_photos'
                            ]);

                            if ($additional_pictures_insert) $additional_pictures_status = true;
                        }
                    }

                    $additional_files_status = false;
                    if ($additional_files = $request->file('additional_files')) {
                        foreach ($additional_files as $additional_file) {
                            $additional_file_name_full = $additional_file->getClientOriginalName();
                            $additional_file_name = pathinfo($additional_file_name_full, PATHINFO_FILENAME);
                            $additional_file_ext = $additional_file->getClientOriginalExtension();
                            $additional_file_finalname = $additional_file_name . '_' . time() . '.' . $additional_file_ext;
                            //Upload Image
                            $additional_file->storeAs('assets/nurses/additional_files/' . $nurse->id, $additional_file_finalname);
                            $additional_files_insert = NurseAsset::create([
                                'nurse_id' => $nurse->id,
                                'name' => $additional_file_finalname,
                                'filter' => 'additional_files'
                            ]);

                            if ($additional_files_insert) $additional_files_status = true;
                        }
                    }

                    if ($nurse_update == true || ($additional_pictures_status == true || $additional_files_status == true)) {
                        $this->check = "1";
                        $this->message = "Role Interest updated successfully";
                        $this->return_data = $this->profileCompletionFlagStatus($type = "", $user);
                    } else {
                        $this->message = "Failed to update role interest, Please try again later";
                    }
                }else{
                    $this->check = "1";
                    $this->message = "Nurse not exist";
                }
            }else{
                $this->check = "1";
                $this->message = "User not exist";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    // Upload Worker resume
    public function resume(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'resume' => 'required|mimes:doc,docx,pdf,txt|max:2048',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->first();
                $nurse_info = NURSE::where('user_id', $request->user_id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->first();
                    $nurse_update = false;
                    if ($request->hasFile('resume')) {
                        $resume_name_full = $request->file('resume')->getClientOriginalName();
                        $resume_name = pathinfo($resume_name_full, PATHINFO_FILENAME);
                        $resume_ext = $request->file('resume')->getClientOriginalExtension();
                        $resume = $resume_name . '_' . time() . '.' . $resume_ext;
                        $nurse_array["resume"] = $resume;
                        //Upload Image
                        $request->file('resume')->storeAs('assets/nurses/resumes/' . $nurse->id, $resume);
                        $nurse_update = NURSE::where(['id' => $nurse->id])->update($nurse_array);
                        $nurse->addMediaFromRequest('resume')->usingName($nurse->id)->toMediaCollection('resumes');
                    }

                    if ($nurse_update == true) {
                        $this->check = "1";
                        $this->message = "Resume updated successfully";
                        $this->return_data = $this->profileCompletionFlagStatus($type = "", $user);
                    } else {
                        $this->message = "Failed to update the resume. Please try again later";
                    }
                } else {
                    $this->message = "Nurse not found";
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

  // facility Ratings
  public function facilityRatings(Request $request)
  {
      $validator = \Validator::make($request->all(), [
          'user_id' => 'required',
          'facility_id' => 'required',
          'api_key' => 'required',
      ]);

      if ($validator->fails()) {
          $this->message = $validator->errors()->first();
      } else {
          $user_info = USER::where('id', $request->user_id);
          if ($user_info->count() > 0) {
              $user = $user_info->get()->first();
              $nurse_info = NURSE::where('user_id', $user->id);
              if ($nurse_info->count() > 0) {
                  $nurse = $nurse_info->get()->first();
                  $insert_array['nurse_id'] = $nurse->id;
                  if (isset($request->facility_id) && $request->facility_id != "")
                      $insert_array['facility_id'] = $request->facility_id;
                  if (isset($request->overall) && $request->overall != "")
                      $update_array['overall'] = $insert_array['overall'] = $request->overall;
                  if (isset($request->on_board) && $request->on_board != "")
                      $update_array['on_board'] = $insert_array['on_board'] = $request->on_board;
                  if (isset($request->nurse_team_work) && $request->nurse_team_work != "")
                      $update_array['nurse_team_work'] = $insert_array['nurse_team_work'] = $request->nurse_team_work;
                  if (isset($request->leadership_support) && $request->leadership_support != "")
                      $update_array['leadership_support'] = $insert_array['leadership_support'] = $request->leadership_support;
                  if (isset($request->tools_todo_my_job) && $request->tools_todo_my_job != "")
                      $update_array['tools_todo_my_job'] = $insert_array['tools_todo_my_job'] = $request->tools_todo_my_job;
                  if (isset($request->experience) && $request->experience != "")
                      $update_array['experience'] = $insert_array['experience'] = $request->experience;

                  $check_exists = FacilityRating::where(['nurse_id' => $nurse->id, 'facility_id' => $request->facility_id])->get();
                  if ($check_exists->count() > 0) {
                      $rating_row = $check_exists->first();
                      $data = FacilityRating::where(['id' => $rating_row->id])->update($update_array);
                  } else {
                      $data = FacilityRating::create($insert_array);
                  }

                  if (isset($data) && $data == true) {
                      $this->check = "1";
                      $this->message = "Your rating is submitted successfully";
                  } else {
                      $this->message = "Failed to update ratings, Please try again later";
                  }
              } else {
                  $this->message = "Nurse not found";
              }
          } else {
              $this->message = "User not found";
          }
      }

      return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
  }

    //changing password for worker
    public function changePassword(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
            'password' => 'required|string|min:6|max:255|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[\[\]\{\}\';:\.,#?!@$%^&*-]).{6,}$/'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);

            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $nurse_info = NURSE::where('user_id', $user->id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->get()->first();
                    $update_array['password'] = Hash::make($request->password);
                    $update = USER::where(['id' => $user->id])->update($update_array);
                    if ($update == true) {
                        $this->check = "1";
                        $this->message = "Password changed successfully";
                    } else {
                        $this->message = "Failed to change password, please try again later";
                    }
                } else {
                    $this->message = "Nurse not found";
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    // job offered to a worker
    public function viewJobOffered(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'offer_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse = Nurse::where('user_id', '=', $request->user_id)->get()->first();
            $whereCond = [
                'active' => true,
            ];
            /* 'status' => 'Pending' */
            $offers = Offer::where($whereCond)
                ->where('id', $request->offer_id)
                ->where('nurse_id', $nurse->id)
                ->whereNotNull('job_id')
                ->orderBy('created_at', 'desc');

            $o_data = [];
            if ($offers->count() > 0) {
                $result = $offers->get();
                // $result = $offers->paginate(10);

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

                    $about_job = [
                        'seniority_level' => (isset($jobinfo->seniority_level)) ? strval($jobinfo->seniority_level) : "",
                        'seniority_level_definition' => (isset($seniorityLevels[$jobinfo->seniority_level])) ? $seniorityLevels[$jobinfo->seniority_level] : "",
                        'preferred_shift_duration' => (isset($jobinfo->preferred_shift_duration)) ? strval($jobinfo->preferred_shift_duration) : "",
                        'preferred_shift_duration_definition' => (isset($shifts[$jobinfo->preferred_shift_duration])) ? $shifts[$jobinfo->preferred_shift_duration] : "",
                        'preferred_experience' => isset($jobinfo->preferred_experience) ? $jobinfo->preferred_experience : "",
                        'cerner' => (isset($jobinfo->job_cerner_exp)) ? strval($jobinfo->job_cerner_exp) : "",
                        'cerner_definition' => (isset($ehrProficienciesExp[$jobinfo->job_cerner_exp])) ? $ehrProficienciesExp[$jobinfo->job_cerner_exp] : "",
                        'meditech' => (isset($jobinfo->job_meditech_exp)) ? strval($jobinfo->job_meditech_exp) : "",
                        'meditech_definition' => (isset($ehrProficienciesExp[$jobinfo->job_meditech_exp])) ? $ehrProficienciesExp[$jobinfo->job_meditech_exp] : "",
                        'epic' => (isset($jobinfo->job_epic_exp)) ? strval($jobinfo->job_epic_exp) : "",
                        'epic_definition' => (isset($ehrProficienciesExp[$jobinfo->job_epic_exp])) ? $ehrProficienciesExp[$jobinfo->job_epic_exp] : "",
                    ];

                    $rating = [];
                    $rating_flag = "0";
                    $nurse_rating_info = NurseRating::where(['nurse_id' => $nurse->id, 'job_id' => $jobinfo->id, 'status' => '1', 'is_deleted' => '0']);
                    if ($nurse_rating_info->count() > 0) {
                        $rating_flag = "1";
                        $r = $nurse_rating_info->first();
                        $rating['overall'] = (isset($r->overall) && $r->overall != "") ? $r->overall : "0";
                        $rating['clinical_skills'] = (isset($r->clinical_skills) && $r->clinical_skills != "") ? $r->clinical_skills : "0";
                        $rating['nurse_teamwork'] = (isset($r->nurse_teamwork) && $r->nurse_teamwork != "") ? $r->nurse_teamwork : "0";
                        $rating['interpersonal_skills'] = (isset($r->interpersonal_skills) && $r->interpersonal_skills != "") ? $r->interpersonal_skills : "0";
                        $rating['work_ethic'] = (isset($r->work_ethic) && $r->work_ethic != "") ? $r->work_ethic : "0";
                        $rating['experience'] = (isset($r->experience) && $r->experience != "") ? $r->experience : "";
                    }

                    // $facility_logo = "";
                    // if ($facility_info->facility_logo) {
                    //     $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $facility_info->facility_logo);
                    //     if ($t) {
                    //         $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $facility_info->facility_logo);
                    //     }
                    // }

                    $o_data[] = [
                        "offer_id" => $off_val->id,
                        "job_id" => $off_val->job_id,
                        "facility_logo" => (isset($facility_info->facility_logo) && $facility_info->facility_logo != "") ? url("public/images/facilities/" . $facility_info->facility_logo) : "",
                        // "facility_logo_base" => ($facility_logo != "") ? 'data:image/jpeg;base64,' . base64_encode($facility_logo) : "",
                        "facility_name" => (isset($facility_info->name) && $facility_info->name != "") ? $facility_info->name : "",
                        "preferred_work_location" => (isset($jobinfo->preferred_work_location)) ? strval($jobinfo->preferred_work_location) : "",
                        "preferred_work_location_definition" => (isset($workLocations[$jobinfo->preferred_work_location])) ? $workLocations[$jobinfo->preferred_work_location] : "",
                        "job_title" => \App\Providers\AppServiceProvider::keywordTitle($jobinfo->preferred_specialty),
                        "job_description" => (isset($jobinfo->description)) ? $jobinfo->description : "",
                        "assignment_duration" => (isset($jobinfo->preferred_assignment_duration)) ? $jobinfo->preferred_assignment_duration : "",
                        "assignment_duration_definition" => (isset($assignmentDurations[$jobinfo->preferred_assignment_duration])) ? $assignmentDurations[$jobinfo->preferred_assignment_duration] : "",
                        "shift_definition" => "Days",
                        "working_days" => (!empty($days)) ? implode(",", $days) : "",
                        "working_days_definition" => $days,
                        "hourly_pay_rate" => isset($jobinfo->preferred_hourly_pay_rate) ? $jobinfo->preferred_hourly_pay_rate : "0",
                        "status" => "pending",
                        "about_job" => $about_job,
                        'start_date' => (isset($jobinfo->start_date) && $jobinfo->start_date != "") ? date('d F Y', strtotime($jobinfo->start_date)) : "",
                        'end_date' => (isset($jobinfo->end_date) && $jobinfo->end_date != "") ? date('d F Y', strtotime($jobinfo->end_date)) : "",
                        'rating_flag' => $rating_flag,
                        'rating' => (!empty($rating)) ? $rating : (object)array(),
                        /* 'job_data' => $jobinfo */
                    ];
                }
                // $o_data["current_page"] = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : "0";
                $this->check = "1";
                $this->message = "Job offers listed successfully";
            } else {
                $this->message = "Currently no offers for you";
            }
            $this->return_data = $o_data;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    // getting worker information
    public function workerInformation(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'worker_id' => 'required',
            'api_key' => 'required',
            'job_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker_info  = Nurse::where('id', $request->worker_id);

            if ($worker_info->count() > 0) {
                $worker = $worker_info->get()->first();
                $user_info = USER::where('id', $worker->user_id);
                if($user_info->count() > 0){
                    $user = $user_info->get()->first();
                    $worker_name = $user->first_name.' '.$user->last_name;
                    $worker_img = $user->image;
                    $whereCond = [
                            'facilities.active' => true,
                            'users.id' => $worker->user_id,
                            'nurses.id' => $worker->id,
                            'jobs.id' => $request->job_id
                        ];

                    $respond = Nurse::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'nurses.*', 'jobs.*', 'offers.job_id as job_id', 'offers.status as offer_status', 'facilities.name as facility_name', 'facilities.city as facility_city', 'facilities.state as facility_state', 'nurses.block_scheduling as worker_block_scheduling', 'nurses.float_requirement as worker_float_requirement', 'nurses.facility_shift_cancelation_policy as worker_facility_shift_cancelation_policy', 'nurses.contract_termination_policy as worker_contract_termination_policy', 'offers.start_date as posted_on', 'jobs.created_at as created_at')
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
                            'jobs.id' => $request->job_id
                        ];

                        $worker_jobs = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.*', 'offers.job_id as job_id', 'facilities.name as facility_name', 'facilities.city as facility_city', 'facilities.state as facility_state', 'offers.start_date as posted_on', 'jobs.created_at as created_at')
                        // ->leftJoin('jobs', 'offers.job_id', '=', 'jobs.id')
                        ->leftJoin('offers','offers.job_id', '=', 'jobs.id')
                        ->leftJoin('facilities','jobs.facility_id', '=', 'facilities.id')
                        ->where($whereCond1)->groupBy('jobs.id')->first();
                        $job_data['workers_applied'] = isset($worker_jobs['workers_applied'])?$worker_jobs['workers_applied']:'';
                        $job_data['worker_contract_termination_policy'] = $worker_jobs['contract_termination_policy'];
                        $job_data['job_id'] = $worker_jobs['job_id'];
                        $job_data['facility_name'] = $worker_jobs['facility_name'];
                        $job_data['facility_city'] = $worker_jobs['facility_city'];
                        $job_data['facility_state'] = $worker_jobs['facility_state'];
                        $job_data['created_at'] = $worker_jobs['created_at'];
                    }
                        // if(strtotime($job_data['posted_on'] > 0 ))
                        // {
                        //     $job_data['posted_on'] = $job_data['posted_on'];
                        // }else{
                        //     $job_data['posted_on'] = $job_data['offer_created_at'];
                        // }

                    $job = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.*')->where(['id' => $request->job_id])->first();
                    $job_data['posted_on'] = $job['created_at'];
                    if(isset($job_data['recruiter_id']) && !empty($job_data['recruiter_id'])){
                        $recruiter_info = USER::where('id', $job_data['recruiter_id'])->get()->first();
                        $recruiter_name = $recruiter_info->first_name.' '.$recruiter_info->last_name;
                    }else{
                        $recruiter_info = USER::where('id', $job['recruiter_id'])->get()->first();
                        $recruiter_name = $recruiter_info->first_name.' '.$recruiter_info->last_name;
                    }
                    $worker_reference = NURSE::select('nurse_references.name','nurse_references.min_title_of_reference','nurse_references.recency_of_reference')
                    ->leftJoin('nurse_references','nurse_references.nurse_id', '=', 'nurses.id')
                    ->where('nurses.id', $worker->id)->get();


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
                    if(isset($job_data['recruiter_id'])){
                        $recruiter_info = User::where('id', $job_data['recruiter_id'])->first();
                    }else{
                        $recruiter_info = User::where('id', $job['recruiter_id'])->first();
                    }

                    $result = [];
                    $result['job_id'] = isset($job['id'])?$job['id']:"";
                    $result['description'] = isset($job['description'])?$job['description']:"";
                    $result['posted_on'] = isset($job_data['posted_on'])?date('M j Y', strtotime($job_data['posted_on'])):"";
                    $result['type'] = isset($job['type'])?$job['type']:"";
                    $result['terms'] = isset($job['terms'])?$job['terms']:"";
                    $result['job_name'] = isset($job['job_name'])?$job['job_name']:"";
                    $result['total_applied'] = isset($job['workers_applied'])?$job['workers_applied']:"";
                    $result['department'] = isset($job['Department'])?$job['Department']:"";
                    $result['worker_name'] = isset($worker_name)?$worker_name:"";
                    $result['worker_image'] = isset($worker_img)?url("public/images/nurses/profile/" . $worker_img):"";
                    $result['recruiter_id'] = isset($job_data['recruiter_id'])?$job_data['recruiter_id']:$job['recruiter_id'];
                    $result['recruiter_image'] = isset($recruiter_info['image'])?url("public/images/nurses/profile/" . $recruiter_info['image']):"";
                    $result['recruiter_name'] = $recruiter_name;
                    $result['offer_status'] = isset($job_data['offer_status'])?$job_data['offer_status']:"";

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
                    $data['worker_image'] = !empty($job_data['diploma'])?url('public/images/nurses/diploma/'.$job_data['diploma']):"";
                    $worker_info[] = $data;
                    $data['worker_image'] = '';

                    $data['job'] = 'Drivers License';
                    $data['match'] = !empty($job_data['driving_license'])?true:false;
                    $data['worker'] = !empty($job_data['driving_license'])?url('public/images/nurses/driving_license/'.$job_data['driving_license']):"";
                    $data['name'] = 'Driving License';
                    $data['match_title'] = 'Driving License';
                    $data['update_key'] = 'driving_license';
                    $data['type'] = 'files';
                    $data['worker_title'] = 'Are you really allowed to drive?';
                    $data['job_title'] = 'Picture of Front and Back DL';
                    $data['worker_image'] = !empty($job_data['driving_license'])?url('public/images/nurses/driving_license/'.$job_data['driving_license']):"";
                    $worker_info[] = $data;
                    $data['worker_image'];

                    $data['job'] = !empty($job['job_worked_at_facility_before'])?$job['job_worked_at_facility_before']:"";
                    $data['match'] = $recs;
                    $data['worker'] = !empty($job_data['worked_at_facility_before'])?$job_data['worked_at_facility_before']:"";
                    $data['name'] = 'Working at Facility Before';
                    $data['match_title'] = 'Working at Facility Before';
                    $data['update_key'] = 'worked_at_facility_before';
                    $data['type'] = 'checkbox';
                    $data['worker_title'] = 'Are you sure you never worked here as staff?';
                    $data['job_title'] = 'Have you worked here in the last 18 months?';
                    $worker_info[] = $data;

                    $data['job'] = "Last 4 digit of SS# to submit";
                    $data['match'] = !empty($job_data['worker_ss_number'])?true:false;
                    $data['worker'] = !empty($job_data['worker_ss_number'])?$job_data['worker_ss_number']:"";
                    $data['name'] = 'SS Card Number';
                    $data['match_title'] = 'SS# Or SS Card';
                    $data['update_key'] = 'worker_ss_number';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'Yes we need your SS# to submit you';
                    $data['job_title'] = 'Last 4 digits of SS# to submit';
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
                    $worker_info[] = $data;

                    $data['job'] = isset($job['preferred_specialty'])?$job['preferred_specialty']:"";
                    $data['match'] = $speciality;
                    $data['worker'] = !empty($job_data['specialty'])?$job_data['specialty']:"";
                    $data['name'] = 'Speciality';
                    $data['match_title'] = 'Specialty';
                    $data['update_key'] = 'specialty';
                    $data['type'] = 'dropdown';
                    $data['worker_title'] = "What's your specialty?";
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Specialty';
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
                        $worker_info[] = $data;
                        $i++;

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
                        $i++;
                    }
                    $data['worker_image'] = '';

                    $data['job'] = !empty($job['skills'])?$job['skills']:"";
                    $data['match'] = !empty($job_data['skills'])?true:false;
                    $data['worker'] = isset($job_data['skills'])?$job_data['skills']:"";
                    if(isset($job_data['skills'])){
                        $data['worker_image'] = isset($skills_checklists)?$skills_checklists[0]:"";
                    }

                    $data['name'] = 'Skills';
                    $data['match_title'] = 'Skills checklist';
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
                    $data['match_title'] = 'Eligible to work in the US';
                    $data['update_key'] = 'eligible_work_in_us';
                    $data['type'] = 'checkbox';
                    $data['worker_title'] = 'Does Congress allow you to work here?';
                    $data['job_title'] = 'Eligible to work in the US';
                    $worker_info[] = $data;

                    $data['job'] = isset($job['urgency'])?$job['urgency']:"";
                    $data['match'] = !empty($job_data['worker_urgency'])?true:false;
                    $data['worker'] = isset($job_data['worker_urgency'])?$job_data['worker_urgency']:"";
                    $data['name'] = 'worker_urgency';
                    $data['match_title'] = 'Urgency';
                    $data['update_key'] = 'worker_urgency';
                    $data['type'] = 'input';
                    $data['worker_title'] = "How quickly can you be ready to submit?";
                    if(isset($data['job']) && $data['job'] == '1'){ $data['job'] = 'Auto Offer'; }else{
                        $data['job'] = 'Urgency';
                    }
                    // $data['job_title'] = $data['job'];
                    $data['job_title'] = !empty($job['urgency'])?$job['urgency']:"Urgency";
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
                    $worker_info[] = $data;

                    $data['job'] = isset($job['block_scheduling'])?$job['block_scheduling']:"";
                    $data['match'] = !empty($job_data['worker_block_scheduling'])?true:false;
                    $data['worker'] = isset($job_data['worker_block_scheduling'])?$job_data['worker_block_scheduling']:"";
                    $data['name'] = 'Block_scheduling';
                    $data['match_title'] = 'Block Scheduling';
                    $data['update_key'] = 'block_scheduling';
                    $data['type'] = 'checkbox';
                    $data['worker_title'] = 'Do you want block scheduling?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Block Scheduling';
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
                    $worker_info[] = $data;

                    $data['job'] = isset($job['Bed_Size'])?$job['Bed_Size']:"";
                    $data['match'] = !empty($job_data['worker_bed_size'])?true:false;
                    $data['worker'] = isset($job_data['worker_bed_size'])?$job_data['worker_bed_size']:"";
                    $data['name'] = 'Bed Size';
                    $data['match_title'] = 'Bed Size';
                    $data['update_key'] = 'worker_bed_size';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'king or california king ?';
                    $data['job_title'] = !empty($job['Bed_Size'])?$job['Bed_Size']:'Bed Size';
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
                    $worker_info[] = $data;

                    // Start Date
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
                    $data['job_title'] = (isset($data['job']) && !empty($data['job']))?$data['job']:'Start Date';
                    $worker_info[] = $data;
                    // End Start Date

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
                    $worker_info[] = $data;

                    if($job['hours_shift'] == $job_data['worker_hours_shift']){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['hours_shift'])?$job['hours_shift']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_hours_shift'])?$job_data['worker_hours_shift']:"";
                    $data['name'] = 'Shift Hours';
                    $data['match_title'] = 'Hours/Shift';
                    $data['update_key'] = 'worker_hours_shift';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'Preferred hours per shift';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Hours/Shift';
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
                    $worker_info[] = $data;

                    if($job['weeks_shift'] == $job_data['worker_shifts_week']){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['weeks_shift'])?$job['weeks_shift']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_shifts_week'])?$job_data['worker_shifts_week']:"";
                    $data['name'] = 'Shift Week';
                    $data['match_title'] = 'Shifts/Week';
                    $data['update_key'] = 'worker_shifts_week';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'Ideal shifts per week';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Shifts/Week';
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
                    $worker_info[] = $data;

                    $data['job'] = isset($job['four_zero_one_k'])?$job['four_zero_one_k']:"";
                    $data['match'] = !empty($job_data['how_much_k'])?true:false;
                    $data['worker'] = isset($job_data['how_much_k'])?$job_data['how_much_k']:"";
                    $data['name'] = '401k';
                    $data['match_title'] = '401K';
                    $data['update_key'] = 'how_much_k';
                    $data['type'] = 'dropdown';
                    $data['worker_title'] = 'How much do you want this?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'401K';
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
                    $worker_info[] = $data;

                    if($job['actual_hourly_rate'] == $job_data['worker_actual_hourly_rate']){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['actual_hourly_rate'])?$job['actual_hourly_rate']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_actual_hourly_rate'])?$job_data['worker_actual_hourly_rate']:"";
                    $data['name'] = 'Actual Rate';
                    $data['match_title'] = 'Actual Hourly Rate';
                    $data['update_key'] = 'worker_actual_hourly_rate';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'What range is fair?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Actual Hourly rate';
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
                    $worker_info[] = $data;

                    $data['job'] = isset($job['on_call'])?$job['on_call']:"";
                    $data['match'] = !empty($job_data['worker_holiday'])?true:false;
                    $data['worker'] = isset($job_data['worker_on_call'])?$job_data['worker_on_call']:"";
                    $data['name'] = 'On call';
                    $data['match_title'] = 'On Call';
                    $data['update_key'] = 'worker_on_call';
                    $data['type'] = 'checkbox';
                    $data['worker_title'] = 'Will you do call?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'On Call';
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
                    $worker_info[] = $data;

                    if($job['weekly_taxable_amount'] == $job_data['worker_weekly_taxable_amount']){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['weekly_taxable_amount'])?$job['weekly_taxable_amount']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_weekly_taxable_amount'])?$job_data['worker_weekly_taxable_amount']:"";
                    $data['name'] = 'Weekly Taxable Amount';
                    $data['match_title'] = 'Weekly Taxable Amount';
                    $data['update_key'] = 'worker_weekly_taxable_amount';
                    $data['type'] = 'input';
                    $data['worker_title'] = '';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Weekly Taxable Amount';
                    $worker_info[] = $data;

                    if($job['weekly_non_taxable_amount'] == $job_data['worker_weekly_non_taxable_amount']){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['weekly_non_taxable_amount'])?$job['weekly_non_taxable_amount']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_weekly_non_taxable_amount'])?$job_data['worker_weekly_non_taxable_amount']:"";
                    $data['name'] = 'Weekly Non Taxable Amount';
                    $data['match_title'] = 'Weekly Non-Taxable Amount';
                    $data['update_key'] = 'worker_weekly_non_taxable_amount';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'Are you going to duplicate expenses?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Weekly non-taxable amount';
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

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    // skipped worker information
    public function workerInformationSkip(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            // 'worker_id' => 'required',
            'api_key' => 'required',
            'job_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
                $job_data = [];
                    $whereCond = [
                            'facilities.active' => true,
                            'jobs.id' => $request->job_id
                        ];

                    $respond = Nurse::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'nurses.*', 'jobs.*', 'offers.job_id as job_id', 'facilities.name as facility_name', 'facilities.city as facility_city', 'facilities.state as facility_state', 'nurses.block_scheduling as worker_block_scheduling', 'nurses.float_requirement as worker_float_requirement', 'nurses.facility_shift_cancelation_policy as worker_facility_shift_cancelation_policy', 'nurses.contract_termination_policy as worker_contract_termination_policy', 'jobs.created_at as posted_on')
                                    ->join('users','users.id', '=', 'nurses.user_id')
                                    ->leftJoin('offers','offers.nurse_id', '=', 'nurses.id')
                                    ->leftJoin('jobs', 'offers.job_id', '=', 'jobs.id')
                                    ->leftJoin('facilities','jobs.facility_id', '=', 'facilities.id')
                                    ->where($whereCond);
                    $job_data = $respond->groupBy('jobs.id')->first();

                    if(empty($job_data)){
                        $whereCond1 =  [
                            'facilities.active' => true,
                            'jobs.id' => $request->job_id
                        ];

                        $job_data['worker_block_scheduling'] = '';
                        $job_data['worker_float_requirement'] = '';
                        $job_data['worker_facility_shift_cancelation_policy'] = '';
                        $job_data['worker_contract_termination_policy'] = '';

                        $worker_jobs = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.*', 'offers.job_id as job_id', 'facilities.name as facility_name', 'facilities.city as facility_city', 'facilities.state as facility_state', 'jobs.created_at as posted_on')

                        ->leftJoin('offers','offers.job_id', '=', 'jobs.id')
                        ->leftJoin('facilities','jobs.facility_id', '=', 'facilities.id')
                        ->where($whereCond1)->groupBy('jobs.id')->first();
                        // print_r($worker_jobs);
                        // die();
                        $job_data['workers_applied'] = isset($worker_jobs['workers_applied'])?$worker_jobs['workers_applied']:'';
                        $job_data['worker_contract_termination_policy'] = isset($worker_jobs['contract_termination_policy'])?$worker_jobs['contract_termination_policy']:'';
                        $job_data['job_id'] = isset($worker_jobs['job_id'])?$worker_jobs['job_id']:$request->job_id;
                        $job_data['facility_name'] = isset($worker_jobs['facility_name'])?$worker_jobs['facility_name']:'';
                        $job_data['facility_city'] = isset($worker_jobs['facility_city'])?$worker_jobs['facility_city']:'';
                        $job_data['facility_state'] = isset($worker_jobs['facility_state'])?$worker_jobs['facility_state']:'';
                        // $job_data['posted_on'] = $worker_jobs['posted_on'];

                    }
                    $job_data['posted_on'] = isset($job_data['created_at'])?$job_data['created_at']:'';
                    if(isset($job_data['recruiter_id']) && !empty($job_data['recruiter_id'])){
                        $recruiter_info = USER::where('id', $job_data['recruiter_id'])->get()->first();
                        $recruiter_name = $recruiter_info->first_name.' '.$recruiter_info->last_name;
                    }else{
                        $recruiter_name = '';
                    }

                    $job = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.*')->where('id', $request->job_id)->first();

                    $worker_reference_name = '';
                    $worker_reference_title ='';
                    $worker_reference_recency_reference ='';

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
                    $worker_speciality = '';
                    $worker_experiences = '';
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
                    $worker_vaccination = '';
                    $worker_certificate_name = '';
                    $worker_certificate = '';
                    $skills_checklists = '';
                    $i=0;

                    $vacc_image = '';
                    $cert_image = '';
                    $certificate = explode(',',$job['certificate']);

                    $result = [];
                    $result['job_id'] = isset($job['id'])?$job['id']:"";
                    $result['description'] = isset($job['description'])?$job['description']:"";
                    $result['posted_on'] = isset($job_data['posted_on'])?date('M j Y', strtotime($job_data['posted_on'])):"";
                    $result['type'] = isset($job['type'])?$job['type']:"";
                    $result['terms'] = isset($job['terms'])?$job['terms']:"";
                    $result['job_name'] = isset($job['job_name'])?$job['job_name']:"";
                    $result['total_applied'] = isset($job['workers_applied'])?$job['workers_applied']:"";
                    $result['department'] = isset($job['Department'])?$job['Department']:"";
                    $result['worker_name'] = isset($worker_name)?$worker_name:"";
                    $result['worker_image'] = isset($worker_img)?$worker_img:"";
                    $result['recruiter_name'] = $recruiter_name;
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
                    if(isset($job_data['number_of_references']) && ($job_data['number_of_references'] == $countable)){
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
                    $data['worker_image'] = !empty($job_data['driving_license'])?url('public/images/nurses/driving_license/'.$job_data['driving_license']):"";
                    $worker_info[] = $data;
                    $data['worker_image'] = '';

                    $data['job'] = !empty($job['job_worked_at_facility_before'])?$job['job_worked_at_facility_before']:"";
                    $data['match'] = $recs;
                    $data['worker'] = !empty($job_data['worked_at_facility_before'])?$job_data['worked_at_facility_before']:"";
                    $data['name'] = 'Working at Facility Before';
                    $data['match_title'] = 'Working at Facility Before';
                    $data['update_key'] = 'worked_at_facility_before';
                    $data['type'] = 'checkbox';
                    $data['worker_title'] = 'Are you sure you never worked here as staff?';
                    $data['job_title'] = 'Have you worked here in the last 18 months?';
                    $worker_info[] = $data;

                    $data['job'] = "Last 4 digit of SS# to submit";
                    $data['match'] = !empty($job_data['worker_ss_number'])?true:false;
                    $data['worker'] = !empty($job_data['worker_ss_number'])?$job_data['worker_ss_number']:"";
                    $data['name'] = 'SS Card Number';
                    $data['match_title'] = 'SS# Or SS Card';
                    $data['update_key'] = 'worker_ss_number';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'Yes we need your SS# to submit you';
                    $data['job_title'] = 'Last 4 digits of SS# to submit';
                    $worker_info[] = $data;

                    if($job['profession'] == (isset($job_data['highest_nursing_degree'])?$job_data['highest_nursing_degree']:"")){ $val = true; }else{ $val = false; }
                    $data['job'] = isset($job['profession'])?$job['profession']:"";
                    $data['match'] = $val;
                    $data['worker'] = !empty($job_data['highest_nursing_degree'])?$job_data['highest_nursing_degree']:"";
                    $data['name'] = 'Profession';
                    $data['match_title'] = 'Profession';
                    $data['update_key'] = 'highest_nursing_degree';
                    $data['type'] = 'dropdown';
                    $data['worker_title'] = 'What kind of Professional are you?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Profession';
                    $worker_info[] = $data;

                    $data['job'] = isset($job['preferred_specialty'])?$job['preferred_specialty']:"";
                    $data['match'] = $speciality;
                    $data['worker'] = !empty($job_data['specialty'])?$job_data['specialty']:"";
                    $data['name'] = 'Speciality';
                    $data['match_title'] = 'Specialty';
                    $data['update_key'] = 'specialty';
                    $data['type'] = 'dropdown';
                    $data['worker_title'] = "What's your specialty?";
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Specialty';
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
                    $worker_info[] = $data;

                    $job_data['nursing_license_state'] = isset($job_data['nursing_license_state'])?$job_data['nursing_license_state']:'';
                    $job['job_location'] = isset($job['job_location'])?$job['job_location']:'';
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
                        $i++;
                    }
                    $data['worker_image'] = '';

                    $data['job'] = isset($job['skills'])?$job['skills']:"";
                    $data['match'] = !empty($job_data['skills'])?true:false;
                    $data['worker'] = isset($job_data['skills'])?$job_data['skills']:"";
                    if(isset($job_data['skills'])){
                        $data['worker_image'] = isset($skills_checklists[0])?$skills_checklists[0]:"";
                    }

                    $data['name'] = 'Skills';
                    $data['match_title'] = 'Skills checklist';
                    $data['update_key'] = 'skills';
                    $data['type'] = 'file';
                    $data['worker_title'] = 'Upload your latest skills checklist';
                    $data['job_title'] = $data['job'].' Skills checklist';
                    $worker_info[] = $data;
                    $data['worker_image'] = '';

                    if(isset($job_data['eligible_work_in_us'])?$job_data['eligible_work_in_us']:"" == 'yes'){ $eligible_work_in_us = true; }else{ $eligible_work_in_us = false; }
                    $data['job'] = "Eligible to work in the US";
                    $data['match'] = $eligible_work_in_us;
                    $data['worker'] = isset($job_data['eligible_work_in_us'])?$job_data['eligible_work_in_us']:"";
                    $data['name'] = 'eligible_work_in_us';
                    $data['match_title'] = 'Eligible to work in the US';
                    $data['update_key'] = 'eligible_work_in_us';
                    $data['type'] = 'checkbox';
                    $data['worker_title'] = 'Does Congress allow you to work here?';
                    $data['job_title'] = 'Eligible to work in the US';
                    $worker_info[] = $data;

                    $data['job'] = isset($job['urgency'])?$job['urgency']:"";
                    $data['match'] = !empty($job_data['worker_urgency'])?true:false;
                    $data['worker'] = isset($job_data['worker_urgency'])?$job_data['worker_urgency']:"";
                    $data['name'] = 'worker_urgency';
                    $data['match_title'] = 'Urgency';
                    $data['update_key'] = 'worker_urgency';
                    $data['type'] = 'input';
                    $data['worker_title'] = "How quickly can you be ready to submit?";
                    if(isset($data['job']) && $data['job'] == '1'){ $data['job'] = 'Auto Offer'; }else{
                        $data['job'] = 'Urgency';
                    }
                    // $data['job_title'] = $data['job'];
                    $data['job_title'] = !empty($job['urgency'])?$job['urgency']:"Urgency";
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
                    $worker_info[] = $data;

                    if(isset($job_data['distance_from_your_home']) && ($job_data['distance_from_your_home'] != 0) ){
                        $data['worker'] = $job_data['distance_from_your_home'];
                    }else{
                        $data['worker'] = "";
                    }
                    if($job['traveler_distance_from_facility'] == $data['worker']){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['traveler_distance_from_facility'])?$job['traveler_distance_from_facility']:"";
                    $data['match'] = $val;
                    // $data['worker'] = isset($job_data['distance_from_your_home'])?$job_data['distance_from_your_home']:"";
                    $data['name'] = 'distance from facility';
                    $data['match_title'] = 'Traveler Distance From Facility';
                    $data['update_key'] = 'distance_from_your_home';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'Where does the IRS think you live?';
                    $data['job_title'] = !empty($data['job'])?$data['job'].' miles':'Traveler Distance From Facility';
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
                    $worker_info[] = $data;

                   // if(isset($job['clinical_setting'])?$job['clinical_setting']:'' == isset($job_data['clinical_setting_you_prefer'])?$job_data['clinical_setting_you_prefer']:""){ $val = true; }else{$val = false;}
                   //
                   if ((isset($job['clinical_setting']) ? $job['clinical_setting'] : '') == (isset($job_data['clinical_setting_you_prefer']) ? $job_data['clinical_setting_you_prefer'] : "")) {
                    $val = true;
                } else {
                    $val = false;
                }

                    $data['job'] = isset($job['clinical_setting'])?$job['clinical_setting']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['clinical_setting_you_prefer'])?$job_data['clinical_setting_you_prefer']:"";
                    $data['name'] = 'Clinical Setting';
                    $data['match_title'] = 'Clinical Setting';
                    $data['update_key'] = 'clinical_setting_you_prefer';
                    $data['type'] = 'dropdown';
                    $data['worker_title'] = 'What setting do you prefer?';
                    $data['job_title'] = (isset($data['job']) && !empty($data['job']))?$data['job']:' Clinical Setting';
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
                    $worker_info[] = $data;

                    $data['job'] = isset($job['Bed_Size'])?$job['Bed_Size']:"";
                    $data['match'] = !empty($job_data['worker_bed_size'])?true:false;
                    $data['worker'] = isset($job_data['worker_bed_size'])?$job_data['worker_bed_size']:"";
                    $data['name'] = 'Bed Size';
                    $data['match_title'] = 'Bed Size';
                    $data['update_key'] = 'worker_bed_size';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'king or california king ?';
                    $data['job_title'] = !empty($job['Bed_Size'])?$job['Bed_Size']:'Bed Size';
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
                    $worker_info[] = $data;

                    if($job['scrub_color'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['scrub_color'])?$job['scrub_color']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_scrub_color'])?$job_data['worker_scrub_color']:"";
                    $data['name'] = 'Scrub color';
                    $data['match_title'] = 'Scrub Color';
                    $data['update_key'] = 'worker_scrub_color';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'Fav Scrub Brand?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Scrub Color';
                    $worker_info[] = $data;

                    if($job['job_state'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['job_state'])?$job['job_state']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_facility_state_code'])?$job_data['worker_facility_state_code']:"";
                    $data['name'] = 'Facility state';
                    $data['match_title'] = 'Facility State Code';
                    $data['update_key'] = 'worker_facility_state_code';
                    $data['type'] = 'dropdown';
                    $data['worker_title'] = "States you'd like to work?";
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Facility State Code';
                    $worker_info[] = $data;

                    if($job['job_city'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['job_city'])?$job['job_city']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_facility_city'])?$job_data['worker_facility_city']:"";
                    $data['name'] = 'Facility City';
                    $data['match_title'] = 'Facility City';
                    $data['update_key'] = 'worker_facility_city';
                    $data['type'] = 'dropdown';
                    $data['worker_title'] = "Cities you'd like to work?";
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Facility City';
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
                    $worker_info[] = $data;

                    if($job['rto'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['rto'])?$job['rto']:"";
                    $data['match'] = $val;
                    $data['worker'] = "";
                    $data['name'] = 'RTO';
                    $data['match_title'] = 'RTO';
                    $data['update_key'] = 'clinical_setting_you_prefer';
                    $data['type'] = 'input';
                    $data['worker_title'] = !empty($data['worker'])?$data['worker']:'Any time off?';
                    $data['job_title'] = (isset($data['job']) && !empty($data['job']))?$data['job']:'RTO';
                    $worker_info[] = $data;

                    if($job['preferred_shift'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['preferred_shift'])?$job['preferred_shift']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_shift_time_of_day'])?$job_data['worker_shift_time_of_day']:"";
                    $data['name'] = 'Shift';
                    $data['match_title'] = 'Shift Time of Day';
                    $data['update_key'] = 'worker_shift_time_of_day';
                    $data['type'] = 'dropdown';
                    $data['worker_title'] = 'Fav Shift?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Shift Time of Day';
                    $worker_info[] = $data;


                    if($job['hours_per_week'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['hours_per_week'])?$job['hours_per_week']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_hours_per_week'])?$job_data['worker_hours_per_week']:"";
                    $data['name'] = 'Hours/week';
                    $data['match_title'] = 'Hours/Week';
                    $data['update_key'] = 'worker_hours_per_week';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'Ideal Hours per week?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Hours/Week';
                    $worker_info[] = $data;

                    if($job['guaranteed_hours'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['guaranteed_hours'])?$job['guaranteed_hours']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_guaranteed_hours'])?$job_data['worker_guaranteed_hours']:"";
                    $data['name'] = 'Guaranteed Hours';
                    $data['match_title'] = 'Guaranteed Hours';
                    $data['update_key'] = 'worker_guaranteed_hours';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'Open to jobs with no guaranteed hours?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Guaranteed Hours';
                    $worker_info[] = $data;

                    if($job['hours_shift'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['hours_shift'])?$job['hours_shift']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_hours_shift'])?$job_data['worker_hours_shift']:"";
                    $data['name'] = 'Shift Hours';
                    $data['match_title'] = 'Hours/Shift';
                    $data['update_key'] = 'worker_hours_shift';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'Preferred hours per shift';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Hours/Shift';
                    $worker_info[] = $data;

                    if($job['preferred_assignment_duration'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['preferred_assignment_duration'])?$job['preferred_assignment_duration']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_weeks_assignment'])?$job_data['worker_weeks_assignment']:"";
                    $data['name'] = 'Assignment in weeks';
                    $data['match_title'] = 'Weeks/Assignment';
                    $data['update_key'] = 'worker_weeks_assignment';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'How many weeks?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Weeks/Assignment';
                    $worker_info[] = $data;

                    if($job['weeks_shift'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['weeks_shift'])?$job['weeks_shift']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_shifts_week'])?$job_data['worker_shifts_week']:"";
                    $data['name'] = 'Shift Week';
                    $data['match_title'] = 'Shifts/Week';
                    $data['update_key'] = 'worker_shifts_week';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'Ideal shifts per week';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Shifts/Week';
                    $worker_info[] = $data;

                    if($job['referral_bonus'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['referral_bonus'])?$job['referral_bonus']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_referral_bonus'])?$job_data['worker_referral_bonus']:"";
                    $data['name'] = 'Refferel Bonus';
                    $data['match_title'] = 'Referral Bonus';
                    $data['update_key'] = 'worker_referral_bonus';
                    $data['type'] = 'input';
                    $data['worker_title'] = '# of people you have referred';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Referral Bonus';
                    $worker_info[] = $data;

                    if($job['sign_on_bonus'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['sign_on_bonus'])?$job['sign_on_bonus']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_sign_on_bonus'])?$job_data['worker_sign_on_bonus']:"";
                    $data['name'] = 'Sign On Bonus';
                    $data['match_title'] = 'Sign-On Bonus';
                    $data['update_key'] = 'worker_sign_on_bonus';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'What kind of bonus do you expect?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Sign-On Bonus';
                    $worker_info[] = $data;

                    if($job['completion_bonus'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['completion_bonus'])?$job['completion_bonus']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_completion_bonus'])?$job_data['worker_completion_bonus']:"";
                    $data['name'] = 'Completion Bonus';
                    $data['match_title'] = 'Completion Bonus';
                    $data['update_key'] = 'worker_completion_bonus';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'What kind of bonus do you deserve?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Completion Bonus';
                    $worker_info[] = $data;

                    if($job['extension_bonus'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['extension_bonus'])?$job['extension_bonus']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_extension_bonus'])?$job_data['worker_extension_bonus']:"";
                    $data['name'] = 'extension bonus';
                    $data['match_title'] = 'Extension Bonus';
                    $data['update_key'] = 'worker_extension_bonus';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'What are you comparing this too?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Extension Bonus';
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
                    $worker_info[] = $data;

                    $data['job'] = isset($job['four_zero_one_k'])?$job['four_zero_one_k']:"";
                    $data['match'] = !empty($job_data['how_much_k'])?true:false;
                    $data['worker'] = isset($job_data['how_much_k'])?$job_data['how_much_k']:"";
                    $data['name'] = '401k';
                    $data['match_title'] = '401K';
                    $data['update_key'] = 'how_much_k';
                    $data['type'] = 'dropdown';
                    $data['worker_title'] = 'How much do you want this?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'401K';
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
                    $worker_info[] = $data;

                    if($job['actual_hourly_rate'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['actual_hourly_rate'])?$job['actual_hourly_rate']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_actual_hourly_rate'])?$job_data['worker_actual_hourly_rate']:"";
                    $data['name'] = 'Actual Rate';
                    $data['match_title'] = 'Actual Hourly Rate';
                    $data['update_key'] = 'worker_actual_hourly_rate';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'What range is fair?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Actual Hourly rate';
                    $worker_info[] = $data;

                    if($job['feels_like_per_hour'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['feels_like_per_hour'])?$job['feels_like_per_hour']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_feels_like_hour'])?$job_data['worker_feels_like_hour']:"";
                    $data['name'] = 'feels/$hr';
                    $data['match_title'] = 'Feels Like $/hr';
                    $data['update_key'] = 'worker_feels_like_hour';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'Does this seem fair based on the market?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Feels Like $/hr';
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
                    $worker_info[] = $data;

                    $data['job'] = isset($job['on_call'])?$job['on_call']:"";
                    $data['match'] = !empty($job_data['worker_holiday'])?true:false;
                    $data['worker'] = isset($job_data['worker_on_call'])?$job_data['worker_on_call']:"";
                    $data['name'] = 'On call';
                    $data['match_title'] = 'On Call';
                    $data['update_key'] = 'worker_on_call';
                    $data['type'] = 'checkbox';
                    $data['worker_title'] = 'Will you do call?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'On Call';
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
                    $worker_info[] = $data;

                    if($job['weekly_taxable_amount'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['weekly_taxable_amount'])?$job['weekly_taxable_amount']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_weekly_taxable_amount'])?$job_data['worker_weekly_taxable_amount']:"";
                    $data['name'] = 'Weekly Taxable Amount';
                    $data['match_title'] = 'Weekly Taxable Amount';
                    $data['update_key'] = 'worker_weekly_taxable_amount';
                    $data['type'] = 'input';
                    $data['worker_title'] = '';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Weekly Taxable Amount';
                    $worker_info[] = $data;

                    if($job['weekly_non_taxable_amount'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['weekly_non_taxable_amount'])?$job['weekly_non_taxable_amount']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_weekly_non_taxable_amount'])?$job_data['worker_weekly_non_taxable_amount']:"";
                    $data['name'] = 'Weekly Non Taxable Amount';
                    $data['match_title'] = 'Weekly Non-Taxable Amount';
                    $data['update_key'] = 'worker_weekly_non_taxable_amount';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'Are you going to duplicate expenses?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Weekly non-taxable amount';
                    $worker_info[] = $data;

                    if($job['employer_weekly_amount'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['employer_weekly_amount'])?$job['employer_weekly_amount']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_employer_weekly_amount'])?$job_data['worker_employer_weekly_amount']:"";
                    $data['name'] = 'Employer Weekly Amount';
                    $data['match_title'] = 'Employer Weekly Amount';
                    $data['update_key'] = 'worker_employer_weekly_amount';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'What range is reasonable?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Employer Weekly Amount';
                    $worker_info[] = $data;

                    if($job['goodwork_weekly_amount'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['goodwork_weekly_amount'])?$job['goodwork_weekly_amount']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_goodwork_weekly_amount'])?$job_data['worker_goodwork_weekly_amount']:"";
                    $data['name'] = 'Goodwork Weekly Amount';
                    $data['match_title'] = 'Goodwork Weekly Amount';
                    $data['update_key'] = 'worker_goodwork_weekly_amount';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'You only have (count down time) left before your rate drops from 5% to 2%';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Goodwork Weekly Amount';
                    $worker_info[] = $data;

                    if($job['total_employer_amount'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['total_employer_amount'])?$job['total_employer_amount']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_total_employer_amount'])?$job_data['worker_total_employer_amount']:"";
                    $data['name'] = 'Total Employer Amount';
                    $data['match_title'] = 'Total Employer Amount';
                    $data['update_key'] = 'worker_total_employer_amount';
                    $data['type'] = 'input';
                    $data['worker_title'] = '';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Total Employer Amount';
                    $worker_info[] = $data;

                    if($job['total_goodwork_amount'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['total_goodwork_amount'])?$job['total_goodwork_amount']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_total_goodwork_amount'])?$job_data['worker_total_goodwork_amount']:"";
                    $data['name'] = 'Total Goodwork Amount';
                    $data['match_title'] = 'Total Goodwork Amount';
                    $data['update_key'] = 'worker_total_goodwork_amount';
                    $data['type'] = 'input';
                    $data['worker_title'] = 'How would you spend those extra funds?';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Total Goodwork Amount';
                    $worker_info[] = $data;

                    if($job['total_contract_amount'] == ''){ $val = true; }else{$val = false;}
                    $data['job'] = isset($job['total_contract_amount'])?$job['total_contract_amount']:"";
                    $data['match'] = $val;
                    $data['worker'] = isset($job_data['worker_total_contract_amount'])?$job_data['worker_total_contract_amount']:"";
                    $data['name'] = 'Total Contract Amount';
                    $data['match_title'] = 'Total Contract Amount';
                    $data['update_key'] = 'worker_total_contract_amount';
                    $data['type'] = 'input';
                    $data['worker_title'] = '';
                    $data['job_title'] = !empty($data['job'])?$data['job']:'Total Contract Amount';
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
                    $worker_info[] = $data;

                    $result['worker_info'] = $worker_info;



                    $this->check = "1";
                    $this->message = "Matching details listed successfully";
                    // $this->return_data = $data;
                    $this->return_data = $result;

                // }else{
                //     $this->check = "1";
                //     $this->message = "User Not Found";
                // }

            // }else{
            //     $this->check = "1";
            //     $this->message = "Worker Not Found";
            // }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    // updating worker information
    public function updateWorkerInformation(Request $request)
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
                // $worker->other_certificate_name = isset($request->other_certificate_name)?$request->other_certificate_name:$worker->other_certificate_name;
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

}
