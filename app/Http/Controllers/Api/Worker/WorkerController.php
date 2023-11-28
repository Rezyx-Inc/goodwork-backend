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

use DB;

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

    // worker termsAndConditions
    public function termsAndConditions()
    {
        $this->message = "Terms and Conditions";
        $this->check = "1";
        $this->return_data = '<p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site.</p><p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p> <p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p>';
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    // worker privacyPolicy
    public function privacyPolicy(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $aboutapp =  \DB::table('setting')->where('slug', '=', 'privacy')->get();
            $this->return_data = $aboutapp[0]->content;
            $this->message = "Privacy Policy";
            $this->check = "1";
            $this->url = url('/privacy-policy');
            
            // $this->return_data = '<p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site.</p><p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p> <p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p>';
            return response()->json(["api_status" => $this->check, "About-web" => $this->url, "message" => $this->message, "data" => $this->return_data], 200);
        }
    }

    // worker aboutAPP
    public function aboutAPP(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $aboutapp =  \DB::table('setting')->where('slug', '=', 'about_app')->get();
            $this->return_data = $aboutapp[0]->content;
            $this->message = "About App";
            $this->check = "1";
            $this->url = url('/aboutus');
            // $this->return_data = '<p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site.</p><p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p> <p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p>';
            return response()->json(["api_status" => $this->check, "about_web" => $this->url, "message" => $this->message, "data" => $this->return_data], 200);
        }
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
    // send reset link
    public function sendResetLinkEmail(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.

            $check_user = User::where(['email' => $request->email]);
            if ($check_user->count() > 0) {
                $user = $check_user->first();

                $temp = EmailTemplate::where(['slug' => 'nurse_reset_password']);
                if ($temp->count() > 0) {
                    $t = $temp->first();
                    $data = [
                        'to_email' => $user->email,
                        'to_name' => $user->first_name . ' ' . $user->last_name
                    ];
                    $token = $this->generate_token();
                    $replace_array = ['###RESETLINK###' => url('password/reset', $token)];
                    $this->basic_email($template = "nurse_reset_password", $data, $replace_array);
                }
                $this->check = "1";
                $this->message = "Reset password link sent successfully";
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }




}
