<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

//MODELS :
use App\Models\User;
use App\Models\Keyword;

//WORKER
use App\Models\Nurse;
use App\Models\Availability;
use App\Models\Certification;
use App\Models\Experience;
use App\Models\Job;
use App\Models\Notification;
use App\Enums\Role;
use App\Enums\State;
use App\Models\Offer;
use App\Models\NurseAsset;
use App\Models\NurseReference;
use App\Models\Follows;

//FACILITY
use App\Models\FacilityFollows;
use App\Models\Facility;
use App\Models\FacilityRating;
use App\Models\States;
use App\Models\Cities;
use App\Models\JobAsset;
use App\Models\JobOffer;
use App\Models\NurseRating;
use App\Models\EmailTemplate;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Providers\AppServiceProvider;
use Exception;
use Twilio\Rest\Client;
use Session;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Account;
use Stripe\Payout;
use Stripe\PaymentIntent;

use DB;

class ApiController extends Controller
{
    /**
     * Class constructor.
     */
    private $check;
    private $message;
    private $return_data;

    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->check = "0";
        $this->return_data = (object) array();
        $this->message = "User data";
        $this->param_missing = "Required parameters not found";
        $this->invalid_request  = "Invalid request method";
    }

    public function generate_token()
    {
        return hash_hmac('sha256', Str::random(40) . time(), config('app.key'));
    }


    // we can use 

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

    public function getSpecialities()
    {
        $controller = new Controller();
        $specialties = $controller->getSpecialities()->pluck('title', 'id');
        $spl = [];
        if (!empty($specialties)) {
            foreach ($specialties as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Specialities has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getSpecialitiesByProfession(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'profession_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $id = $request->profession_id;
            $keywords = Keyword::where(['active' => true, 'filter' => $id])->get()->pluck('title', 'id');
            
            $data = [];
            foreach ($keywords as $key => $value) {
                $data[] = ['id' => $key, "name" => $value];
            }
            $this->check = "1";
            $this->message = "Specialities has been listed successfully";
            $this->return_data = $data;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getSettingType(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $keywords = Keyword::where('filter', 'SettingType')->get()->pluck('title', 'id');
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

    public function getContractTerminologyPolicy(Request $request)
    {
        $keywords = Keyword::where('filter', 'ContractTerminationPolicy')->get()->pluck('title', 'id');
        $data = [];
        foreach ($keywords as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Contract Terminology Policy has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getStateLicense()
    {
        $controller = new Controller();
        $specialties = $controller->getSpecialities()->pluck('title', 'id');
        $spl = [];
        if (!empty($specialties)) {
            foreach ($specialties as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Specialities has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getChartingSystem()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'Charting')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Charting System has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getProfessionList()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'Profession')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Profession List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getSkillList()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'Skills')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Profession List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getType()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'Type')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "clinical List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getVMSType()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'VMS')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "VMS List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getMSPType()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'MSP')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "MSP List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getDentalList()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'Dental')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Dental List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getVisionList()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'Vision')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Vision List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getHealthInsuranceList()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'HealthInsurance')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Health Insurance List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getHowMuchKList()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', '401k')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "401k List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getTermsList()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'Terms')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Terms List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

 
    public function getGeographicPreferences()
    {
        $controller = new Controller();
        $workLocations = $controller->getGeographicPreferences()->pluck('title', 'id');
        $work_location = [];
        if (!empty($workLocations)) {
            foreach ($workLocations as $key => $val) {
                $work_location[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Work location's has been listed successfully";
        $this->return_data = $work_location;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function stateList()
    {
        /* $this->return_data = $this->getStateOptions(); */
        $ret = [];
        foreach (State::getKeys() as $key => $value) {
            $ret[]['state'] = $value;
        }
       
        $this->check = "1";
        $this->message = "State's has been listed successfully";
        $this->return_data = $ret;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function shiftDuration()
    {
        $shifts = $this->getShifts()->pluck('title', 'id');
        $this->check = "1";
        $this->message = "Shift duration has been listed successfully";
        $data = [];
        foreach ($shifts as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        asort($data);
        $data1 = [];
        foreach ($data as $key1 => $value1) {
            $data1[] = ['id' => $value1['id'], "name" => $value1['name']];
        }

        $this->return_data = $data1;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function assignmentDurations()
    {
        $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
        // $assignmentDurations = Keyword::where('filter', 'AssignmentDuration')->get()->pluck('title', 'id');
        $data = [];
        foreach ($assignmentDurations as $key => $value) {
            // $name = explode(" ",$value);
            $data[] = ['id' => $key, "name" => $value];
            // $data[] = ['id' => $key, "name" => $name[0]];
        }
        $this->check = "1";
        $this->message = "Assignment duration's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function preferredShifts()
    {
        $preferredShifts = $this->getPreferredShift()->pluck('title', 'id');
        $data = [];
        foreach ($preferredShifts as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Preferred shift's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getWeekDay()
    {
        $weekDays = $this->getWeekDayOptions();
        $data = [];
        foreach ($weekDays as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Week day's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    // login according to the role 
    public function login(Request $request)
    {
        if (isset($request->email) && $request->email != "" && isset($request->password) && $request->password != "" && isset($request->fcm_token) && $request->fcm_token != "") {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'active' => true])) {
                $return_data = [];
                $user_data = User::where('email', '=', $request->email)->get()->first();
                if (!empty($user_data) && $user_data != null) {
                    $user_data->fcm_token = $request->fcm_token;
                    if ($user_data->update()) {
                        $user = User::where('id', '=', $user_data->id)->get()->first();
                        if (isset($user->role) && $user->role == "NURSE") {
                            $return_data = $this->profileCompletionFlagStatus($type = "login", $user);
                        } else {
                            $return_data = $this->facilityProfileCompletionFlagStatus($type = "login", $user);
                        }
                        $this->check = "1";
                        $this->message = "Logged in successfully";
                    } else $this->message = "Problem occurred while updating the token, Please try again later";
                } else $this->message = "User record not found";

                $this->return_data = $return_data;
            } else {
                $this->message = "Invalid email or password";
            }
        } else {
            $this->message = $this->param_missing;
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    // send reset link acccording to the role 
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

    public function profileCompletionFlagStatus($type = "", $user)
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
        // $data = [];
        // foreach ($licenseType as $key => $value) {
        //     $data[] = ['id' => strval($key), "name" => $value];
        // }
        
        $nurse = Nurse::where('user_id', '=', $user->id)->get()->first();
        
        if(isset($nurse->id)){
            $offer = DB::select("SELECT status FROM `offers` WHERE nurse_id = '$nurse->id'");
            if(!empty($offer)){
                $offer = $offer[0];
            }
        }
        $availability = Availability::where('nurse_id', '=', $nurse->id)->get()->first();
                   
        /* profile status flag */
        $profile_detail_flag = "0"; 
        $profile_completion = 0;
        
        if (
            (isset($user->first_name) && $user->first_name != "") &&
            (isset($user->last_name) && $user->last_name != "") &&
            (isset($user->email) && $user->email != "") &&
            (isset($user->mobile) && $user->mobile != "") &&
            (isset($nurse->nursing_license_state) && $nurse->nursing_license_state != "") &&
            (isset($nurse->nursing_license_number) && $nurse->nursing_license_number != "") &&
            (isset($nurse->specialty) && $nurse->specialty != "") &&
            (isset($availability->work_location) && $availability->work_location != "") &&
            // (isset($nurse->address) && $nurse->address != "") &&
            // (isset($nurse->city) && $nurse->city != "") &&
            (isset($nurse->license_type) && $nurse->license_type != "" ) &&
            (isset($nurse->state) && $nurse->state != "") &&
            // (isset($nurse->postcode) && $nurse->postcode != "") &&
            (isset($nurse->country) && $nurse->country != "")
            
        ) $profile_detail_flag = "1";
        /* profile status flag */

        /* Hourly rate and availability */
        $hourly_rate_and_availability = "0";
        if ((isset($nurse->hourly_pay_rate) && $nurse->hourly_pay_rate != "") &&
            (isset($availability->shift_duration) && $availability->shift_duration != "") &&
            (isset($availability->assignment_duration) && $availability->assignment_duration != "") &&
            (isset($availability->preferred_shift) && $availability->preferred_shift != "") &&
            // (isset($availability->days_of_the_week) && $availability->days_of_the_week != "") &&
            (isset($availability->earliest_start_date) && $availability->earliest_start_date != "")
        ) $hourly_rate_and_availability = "1";
        /* Hourly rate and availability */

        $return_data['id'] = (isset($user->id) && $user->id != "") ? $user->id : "";
        $return_data['job_title'] = (isset($jobs->job_title) && $jobs->job_title != "") ? $jobs->job_title : "";
        $return_data['job_filter'] = (isset($jobs->job_filter) && $jobs->job_filter != "") ? $jobs->job_filter : "";
        $return_data['nurse_id'] = (isset($nurse->id) && $nurse->id != "") ? $nurse->id : "";
        $return_data['offer_status'] = (isset($offer->status) && $offer->status != "") ? $offer->status : "";
        $return_data['role'] = (isset($user->role) && $user->role != "") ? $user->role : "";
        $return_data['fcm_token'] = (isset($user->fcm_token) && $user->fcm_token != "") ? $user->fcm_token : "";
        $return_data['fullName'] = (isset($user->fullName) && $user->fullName != "") ? $user->fullName : "";
        $return_data['date_of_birth'] = (isset($user->date_of_birth) && $user->date_of_birth != "") ? $user->date_of_birth : "";
        $return_data['driving_license'] = (isset($user->driving_license) && $user->driving_license != "") ? $user->driving_license : "";
        $return_data['security_number'] = (isset($user->security_number) && $user->security_number != "") ? $user->security_number : "";
        $return_data['email_notification'] = (isset($user->email_notification) && $user->email_notification != "") ? strval($user->email_notification) : "";
        $return_data['sms_notification'] = (isset($user->sms_notification) && $user->sms_notification != "") ? strval($user->sms_notification) : "";
        
        $return_data['first_name'] = (isset($user->first_name) && $user->first_name != "") ? $user->first_name : "";
        $return_data['last_name'] = (isset($user->last_name) && $user->last_name != "") ? $user->last_name : "";
        $return_data['email'] = (isset($user->email) && $user->email != "") ? $user->email : "";

        $return_data['image'] = (isset($user->image) && $user->image != "") ? url("public/images/nurses/profile/" . $user->image) : "";

        $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
        if (isset($user->image)) {
            $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $user->image);
            if ($t) {
                $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $user->image);
            }
        }
        // $return_data["image_base"] = 'data:image/jpeg;base64,' . base64_encode($profileNurse);

        $return_data['mobile'] = (isset($user->mobile) && $user->mobile != "") ? $user->mobile : "";
        $return_data['nursing_license_state'] = (isset($nurse->nursing_license_state) && $nurse->nursing_license_state != "") ? $nurse->nursing_license_state : "";
        $return_data['nursing_license_number'] = (isset($nurse->nursing_license_number) && $nurse->nursing_license_number != "") ? $nurse->nursing_license_number : "";
        $return_data['authority_Issue'] = (isset($nurse->authority_Issue) && $nurse->authority_Issue != "") ? $nurse->authority_Issue : "";
        $return_data['highest_nursing_degree'] = (isset($nurse->highest_nursing_degree) && $nurse->highest_nursing_degree != "") ? $nurse->highest_nursing_degree : "";
        $return_data['highest_nursing_degree_definition'] = (isset($nurse->highest_nursing_degree) && $nurse->highest_nursing_degree != "") ? \App\Providers\AppServiceProvider::keywordTitle($nurse->highest_nursing_degree) : "";
        $return_data['specialty'] = $spl = [];
        if (isset($nurse->specialty) && $nurse->specialty != "") {
            $specialty_array = explode(",", $nurse->specialty);
            if (is_array($specialty_array)) {
                foreach ($specialty_array as $key => $spl_id) {
                    $spl_name = (isset($specialties[$spl_id])) ? $specialties[$spl_id] : "";
                    $spl[] = ['id' => $spl_id, 'name' => $spl_name];
                }
            }
            $return_data['specialty'] = $spl;
        }
        $return_data['work_location'] = (isset($availability->work_location) && $availability->work_location != "") ? strval($availability->work_location) : "";
        // if(isset($return_data['work_location']) && !empty($return_data['work_location'])){
        //     $return_data['work_location_definition'] = isset($workLocations[strval($availability->work_location)]) ? $workLocations[strval($availability->work_location)] : "";
        // }else{
        //     $return_data['work_location_definition'] = '';
        // }
        
        $return_data['address'] = (isset($nurse->address) && $nurse->address != "") ? $nurse->address : "";

        $return_data['search_status'] = (isset($nurse->search_status) && $nurse->search_status != "") ? strval($nurse->search_status) : "";
        $return_data['search_status_definition'] = (isset($nurse->search_status) && $nurse->search_status != "") ? \App\Providers\AppServiceProvider::keywordTitle($nurse->search_status) : "";
        $return_data['license_type'] = (isset($nurse->license_type) && $nurse->license_type != "") ? strval($nurse->license_type) : "";
        $return_data['license_type_definition'] = (isset($nurse->license_type) && $nurse->license_type != "") ? \App\Providers\AppServiceProvider::keywordTitle($nurse->license_type) : "";
        $return_data['license_status'] = (isset($nurse->license_status) && $nurse->license_status != "") ? strval($nurse->license_status) : "";
        $return_data['license_status_definition'] = (isset($nurse->license_status) && $nurse->license_status != "") ? \App\Providers\AppServiceProvider::keywordTitle($nurse->license_status) : "";
        $return_data['license_expiry_date'] = (isset($nurse->license_expiry_date) && $nurse->license_expiry_date != "") ? strval($nurse->license_expiry_date) : "";
        $return_data['license_issue_date'] = (isset($nurse->license_issue_date) && $nurse->license_issue_date != "") ? strval($nurse->license_issue_date) : "";
        $return_data['license_renewal_date'] = (isset($nurse->license_renewal_date) && $nurse->license_renewal_date != "") ? strval($nurse->license_renewal_date) : "";

        $return_data['city'] = (isset($nurse->city) && $nurse->city != "") ? $nurse->city : "";
        $return_data['state'] = (isset($nurse->state) && $nurse->state != "") ? $nurse->state : "";
        $return_data['postcode'] = (isset($nurse->postcode) && $nurse->postcode != "") ? $nurse->postcode : "";
        $return_data['country'] = (isset($nurse->country) && $nurse->country != "") ? $nurse->country : "";
        $return_data['hourly_pay_rate'] = (isset($nurse->hourly_pay_rate) && $nurse->hourly_pay_rate != "") ? strval($nurse->hourly_pay_rate) : "";
        $return_data['shift_duration'] = (isset($availability->shift_duration) && $availability->shift_duration != "") ? strval($availability->shift_duration) : "";
        // $return_data['shift_duration_definition'] = (isset($shifts[$availability->shift_duration]) && $shifts[$availability->shift_duration] != "") ? $shifts[strval($availability->shift_duration)] : "";
        $return_data['assignment_duration'] = (isset($availability->assignment_duration) && $availability->assignment_duration != "") ? strval($availability->shift_duration) : "";
        // $return_data['assignment_duration_definition'] = (isset($assignmentDurations[$availability->assignment_duration]) && $assignmentDurations[$availability->assignment_duration] != "") ? $assignmentDurations[strval($availability->assignment_duration)] : "";
        $return_data['preferred_shift'] = (isset($availability->preferred_shift) && $availability->preferred_shift != "") ? strval($availability->preferred_shift) : "";
        // $return_data['preferred_shift_definition'] = (isset($preferredShifts[$availability->preferred_shift]) &&  $preferredShifts[$availability->preferred_shift] != "") ?  $preferredShifts[$availability->preferred_shift] : "";
        // $return_data['days_of_the_week'] = [];
        // if ($availability->days_of_the_week != "") $return_data['days_of_the_week'] = explode(",", $availability->days_of_the_week);
        $return_data['earliest_start_date'] = (isset($availability->earliest_start_date) && $availability->earliest_start_date != "") ? date('m/d/Y', strtotime($availability->earliest_start_date)) : "";

        $experience = [];
        $exp = Experience::where(['nurse_id' => $nurse->id])->whereNull('deleted_at')->get();
        if ($exp->count() > 0) {
            $e = $exp;
            foreach ($e as $key => $v) {
                $crt_data['experience_id'] = (isset($v->id) && $v->id != "") ? $v->id : "";
                $crt_data['type'] = (isset($v->type) && $v->type != "") ? $v->type : "";
                $crt_data['type_definition'] = (isset($certifications[$v->type]) && $certifications[$v->type] != "") ? $certifications[$v->type] : "";
                $crt_data['position_title'] = (isset($v->position_title) && $v->position_title != "") ? $v->position_title : "";
                $crt_data['unit'] = (isset($v->unit) && $v->unit != "") ? $v->unit : "";
                $crt_data['start_date'] = (isset($v->start_date) && $v->start_date != "") ? date('m/d/Y', strtotime($v->start_date)) : "";
                $crt_data['end_date'] = (isset($v->end_date) && $v->end_date != "") ? date('m/d/Y', strtotime($v->end_date)) : "";
                $crt_data['is_current_job'] = (isset($v->is_current_job) && $v->is_current_job != "") ? $v->is_current_job : "";
                $crt_data["experience_as_acute_care_facility"] = (isset($nurse->experience_as_acute_care_facility) && $nurse->experience_as_acute_care_facility != "") ? $nurse->experience_as_acute_care_facility : "";
                $crt_data["experience_as_ambulatory_care_facility"] = (isset($nurse->experience_as_ambulatory_care_facility) && $nurse->experience_as_ambulatory_care_facility != "") ? $nurse->experience_as_ambulatory_care_facility : "";
                $exp_acute_care = isset($nurse->experience_as_acute_care_facility)? $nurse->experience_as_acute_care_facility : '0';
                $exp_ambulatory_care = isset($nurse->experience_as_ambulatory_care_facility)? $nurse->experience_as_ambulatory_care_facility : '0';
                $crt_data['total_experience'] = $exp_acute_care+$exp_ambulatory_care;
                $crt_data['total_experience'] = (int)$crt_data['total_experience'];
                $experience[] = $crt_data;
        
            }
        }
        $return_data['experience'] = $experience;
        /* experience */

        /* certitficate */
        $certitficate = [];
        $cert = Certification::where(['nurse_id' => $nurse->id])->whereNull('deleted_at')->get();
        if ($cert->count() > 0) {
            $c = $cert;
            foreach ($c as $key => $v) {
                // if ($v->deleted_at != "") {
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
                // $crt_data['certificate_image_base'] = ($certificate_image_base != "") ? 'data:image/jpeg;base64,' . base64_encode($certificate_image_base) : "";


                // $crt_data['active'] = (isset($v->active) && $v->active != "") ? $v->active : "";
                // $crt_data['deleted_at'] = (isset($v->deleted_at) && $v->deleted_at != "") ? $v->deleted_at : "";
                   $crt_data['created_at'] = (isset($v->created_at) && $v->created_at != "") ? $v->created_at : "";
                    // $crt_data['updated_at'] = (isset($v->updated_at) && $v->updated_at != "") ? $v->updated_at : ""; 
                $certitficate[] = $crt_data;
                // }
            }
        }
        $return_data['certitficate'] = $certitficate;
        $return_data['resume'] = (isset($nurse->resume) && $nurse->resume != "") ? url('storage/assets/nurses/resumes/' . $nurse->id . '/' . $nurse->resume) : "";
        /* certitficate */


        /* role interest */
        $optyesno = ['1' => "Yes", '0' => "No"];

        $role_interest["nu_video_embed_url"] = (isset($nurse->nu_video_embed_url) && $nurse->nu_video_embed_url != "") ? $nurse->nu_video_embed_url : "";
        $role_interest["nu_video_embed_url_definition"] = (isset($optyesno[$nurse->nu_video_embed_url]) && $optyesno[$nurse->nu_video_embed_url] != "") ? $optyesno[$nurse->nu_video_embed_url] : "";
        $role_interest['serving_preceptor'] = (isset($nurse->serving_preceptor)) ? strval($nurse->serving_preceptor) : "";
        $role_interest['serving_preceptor_definition'] = (isset($optyesno[$nurse->serving_preceptor]) && $optyesno[$nurse->serving_preceptor] != "") ? $optyesno[$nurse->serving_preceptor] : "";
        $role_interest['serving_interim_nurse_leader'] = (isset($nurse->serving_interim_nurse_leader)) ? strval($nurse->serving_interim_nurse_leader) : "";
        $role_interest['serving_interim_nurse_leader_definition'] = (isset($optyesno[$nurse->serving_interim_nurse_leader]) && $optyesno[$nurse->serving_interim_nurse_leader] != "") ? $optyesno[$nurse->serving_interim_nurse_leader] : "";
        $role_interest['clinical_educator'] = (isset($nurse->clinical_educator)) ? strval($nurse->clinical_educator) : "";
        $role_interest['clinical_educator_definition'] = (isset($optyesno[$nurse->clinical_educator]) && $optyesno[$nurse->clinical_educator] != "") ? $optyesno[$nurse->clinical_educator] : "";
        $role_interest['is_daisy_award_winner'] = (isset($nurse->is_daisy_award_winner)) ? strval($nurse->is_daisy_award_winner) : "";
        $role_interest['is_daisy_award_winner_definition'] = (isset($optyesno[$nurse->is_daisy_award_winner]) && $optyesno[$nurse->is_daisy_award_winner] != "") ? $optyesno[$nurse->is_daisy_award_winner] : "";
        $role_interest['employee_of_the_mth_qtr_yr'] = (isset($nurse->employee_of_the_mth_qtr_yr)) ? strval($nurse->employee_of_the_mth_qtr_yr) : "";
        $role_interest['employee_of_the_mth_qtr_yr_definition'] = (isset($optyesno[$nurse->employee_of_the_mth_qtr_yr]) && $optyesno[$nurse->employee_of_the_mth_qtr_yr] != "") ? $optyesno[$nurse->employee_of_the_mth_qtr_yr] : "";
        $role_interest['other_nursing_awards'] = (isset($nurse->other_nursing_awards)) ? strval($nurse->other_nursing_awards) : "";
        $role_interest['other_nursing_awards_definition'] = (isset($optyesno[$nurse->other_nursing_awards]) && $optyesno[$nurse->other_nursing_awards] != "") ? $optyesno[$nurse->other_nursing_awards] : "";
        $role_interest['is_professional_practice_council'] = (isset($nurse->is_professional_practice_council)) ? strval($nurse->is_professional_practice_council) : "";
        $role_interest['is_professional_practice_council_definition'] = (isset($optyesno[$nurse->is_professional_practice_council]) && $optyesno[$nurse->is_professional_practice_council] != "") ? $optyesno[$nurse->is_professional_practice_council] : "";
        $role_interest['is_research_publications'] = (isset($nurse->is_research_publications)) ? strval($nurse->is_research_publications) : "";
        $role_interest['is_research_publications_definition'] = (isset($optyesno[$nurse->is_research_publications]) && $optyesno[$nurse->is_research_publications] != "") ? $optyesno[$nurse->is_research_publications] : "";
        $role_interest['leadership_roles'] = (isset($nurse->leadership_roles) && $nurse->leadership_roles != "") ? strval($nurse->leadership_roles) : "";
        $role_interest['leadership_roles_definition'] = (isset($leadershipRoles[$nurse->leadership_roles]) && $leadershipRoles[$nurse->leadership_roles] != "") ? $leadershipRoles[$nurse->leadership_roles] : "";

        $role_interest['summary'] = (isset($nurse->summary) && $nurse->summary != "") ? $nurse->summary : "";
        $role_interest['languages'] = (isset($nurse->languages) && $nurse->languages != "") ? explode(",", $nurse->languages) : "";

        /* nurse assets */
        $role_interest['additional_pictures'] = $role_interest['additional_files'] = [];
        $nurse_assets = NurseAsset::where(['nurse_id' => $nurse->id, 'active' => '1'])->get();

        if ($nurse_assets->count() > 0) {
            foreach ($nurse_assets as $nac_ => $na) {
                if ($na->filter == "additional_photos") $role_interest['additional_pictures'][] = ['asset_id' => $na->id, 'photo' => url('storage/assets/nurses/additional_photos/' . $nurse->id . '/' . $na->name)];
                else $role_interest['additional_files'][] = ['asset_id' => $na->id, 'photo' => url('storage/assets/nurses/additional_files/' . $nurse->id . '/' . $na->name)];
            }
        }
        /* nurse assets */
        $return_data['role_interest'] = $role_interest;
        /* role interest */
        //profile completion
        if (
            (isset($user->date_of_birth) && $user->date_of_birth != "") &&
            (isset($user->driving_license) && $user->driving_license != "") &&
            (isset($user->security_number) && $user->security_number != "")
        ){
            $profile_completion++;
            $return_data['profile_details'] = 'true';
        }else{
            $return_data['profile_details'] = 'false';
        } 

        if(!empty($return_data['highest_nursing_degree'])){
            $profile_completion++;
            $return_data['qualification_details'] = 'true';
        }else{
            $return_data['qualification_details'] = 'false';
        }

        if(!empty($return_data['nursing_license_number'])){
            $profile_completion++;
            $return_data['license_details'] = 'true';
        }else{
            $return_data['license_details'] = 'false';
        }

        if(!empty($return_data['resume'])){
            $profile_completion++;
            $return_data['resume_details'] = 'true';
        }else{
            $return_data['resume_details'] = 'false';
        }

        if(!empty($return_data['certitficate'])){
            $profile_completion++;
            $return_data['certificate_details'] = 'true';
        }else{
            $return_data['certificate_details'] = 'false';
        }

        if(!empty($return_data['experience'])){
            $profile_completion++;
            $return_data['experience_details'] = 'true';
        } else{
            $return_data['experience_details'] = 'false';
        }

        if($profile_completion ==  6){
            $return_data['isUserProfile'] = 'true';
        }else{
            $return_data['isUserProfile'] = 'false';
        }

        $return_data['profile_completion'] = $profile_completion;
        // end profile completion
        return $return_data;
    }

    public function personalDetail(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $nurse = Nurse::where('user_id', $request->id)->first();
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            /* 'image' => 'nullable|max:1024|image|mimes:jpeg,png,jpg', */
            'first_name' => 'required|regex:/^[a-zA-Z]+$/|min:3|max:100',
            'last_name' => 'required|regex:/^[a-zA-Z]+$/|min:3|max:100',
            // 'mobile' => 'required|regex:/^[0-9 \+]+$/|min:4|max:20',
            'mobile' => 'required|min:4|max:20',
            'email' => $this->emailRegEx($user),
            'nursing_license_state' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:50',
            'nursing_license_number' => 'nullable|regex:/^[a-zA-Z0-9]+$/|min:2|max:50',
            'specialty' => 'required',
            'address' => 'required|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'city' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:50',
            'state' => 'required',
            'postcode' => 'required|regex:/^[a-zA-Z0-9]+$/|min:3|max:10',
            'country' => 'required|regex:/^[a-zA-Z ]+$/|min:3',
            'api_key' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $return_data = [];
            if ($user) {
                /* User */
                if (isset($request->first_name) && $request->first_name != "") $user->first_name = $request->first_name;
                if (isset($request->last_name) && $request->last_name != "") $user->last_name = $request->last_name;
                if (isset($request->email) && $request->email != "") $user->email = $request->email;
                if (isset($request->mobile) && $request->mobile != "") $user->mobile = $request->mobile;
                if ($request->hasFile('profile_image') && $request->file('profile_image') != null) {
                    // $request->file('profile_image')->storeAs('assets/nurses/profile', $nurse->id);
                    $profile_image_name_full = $request->file('image')->getClientOriginalName();
                    $profile_image_name = pathinfo($profile_image_name_full, PATHINFO_FILENAME);
                    $profile_image_ext = $request->file('image')->getClientOriginalExtension();
                    $profile_image = $profile_image_name.'_'.time().'.'.$profile_image_ext;
                    
                    $destinationPath = 'images/nurses/profile';
                    $request->file('image')->move(public_path($destinationPath), $profile_image);   
                
                    $user->image = $profile_image;
                }
                $u = $user->update();
                /* User */

                /*  Nurse */
                if (isset($request->specialty) && $request->specialty != "") $nurse->specialty = $request->specialty;
                if (isset($request->address) && $request->address != "") $nurse->address = $request->address;
                if (isset($request->city) && $request->city != "") $nurse->city = $request->city;
                if (isset($request->state) && $request->state != "") $nurse->state = $request->state;
                if (isset($request->postcode) && $request->postcode != "") $nurse->postcode = $request->postcode;
                if (isset($request->country) && $request->country != "") $nurse->country = $request->country;
                if (isset($request->nursing_license_number) && $request->nursing_license_number != "") $nurse->nursing_license_number = $request->nursing_license_number;
                if (isset($request->search_status) && $request->search_status != "") $nurse->search_status = $request->search_status;
                if (isset($request->license_type) && $request->license_type != "") $nurse->license_type = $request->license_type;
                $n = $nurse->update();
                /*  Nurse */

                if ($u || $n) {
                    $this->check = "1";
                    $return_data = $this->profileCompletionFlagStatus($type = "", $user);
                    $this->message = "Personal detail updated successfully";
                } else {
                    $this->message = "Problem occurred while updating the profile detail, Please try again later";
                }
            } else {
                $this->message = "User not exists";
            }
            $this->return_data = $return_data;
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function availability(Request $request)
    {
        $messages = [
            "id.required" => "Id is required",
            "shift_duration.required" => "Select shift duration",
            "assignment_duration.required" => "Select assignment duration",
            "preferred_shift.required" => "Select preferred shift",
            // "days_of_the_week.required" => "Select preferred days of the week",
            "earliest_start_date.date" => "Earliest start date is not valid date",
            "work_location.required" => "Select work location",
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'hourly_pay_rate' => 'required|regex:/^[0-9]+$/|min:1|max:3',
            'shift_duration' => 'required',
            'assignment_duration' => 'required',
            'preferred_shift' => 'required',
            // 'days_of_the_week' => 'required',
            'earliest_start_date' => 'nullable|date|after_or_equal:now',
            'work_location' => 'required',
            'api_key' => 'required',
        ], $messages);

        $user_data = User::where('id', '=', $request->id)->first();
        if ($user_data != null) {
            if ($validator->fails()) {
                $this->message = $validator->errors()->first();
            } else {
                /* nurse */
                $nurse = Nurse::where('user_id', '=', $request->id)->get()->first();
                if (isset($request->hourly_pay_rate) && $request->hourly_pay_rate != "") {
                    $tmpRate =  $request->hourly_pay_rate * 25 / 100;
                    $facility_hourly_pay_rate = $request->hourly_pay_rate + $tmpRate;
                    $nurse->__set('facility_hourly_pay_rate', $facility_hourly_pay_rate);
                }
                $nurse->hourly_pay_rate = $request->hourly_pay_rate;
                $n = $nurse->update();
                /* nurse */

                /* availability */
                $availability = Availability::where('nurse_id', '=', $nurse->id)->get()->first();
                if (isset($request->shift_duration) && $request->shift_duration != "") $availability->shift_duration = $request->shift_duration;
                if (isset($request->preferred_shift) && $request->preferred_shift != "") $availability->preferred_shift = $request->preferred_shift;
                if (isset($request->days_of_the_week) && $request->days_of_the_week != "") $availability->days_of_the_week = $request->days_of_the_week;
                if (isset($request->assignment_duration) && $request->assignment_duration != "") $availability->assignment_duration = $request->assignment_duration;
                if (isset($request->earliest_start_date) && $request->earliest_start_date != "") $availability->earliest_start_date = $request->earliest_start_date;
                if (isset($request->work_location) && $request->work_location != "") $availability->work_location = $request->work_location;
                if (isset($request->unavailable_dates) && $request->unavailable_dates != "") $availability->unavailable_dates = $request->unavailable_dates;

                // if (isset($request->unavailable_dates)) {
                //     $availability->unavailable_dates = explode(',', $request->unavailable_dates);
                // }else{
                //     $availability->unavailable_dates = array();
                // }
                
                $a = $availability->update();
                /* availability */
                // Hourly Rate & Availability Updated

                if ($a || $n) {
                    $this->check = "1";
                    $this->message = "Hourly rate & availability updated successfully";
                    $this->return_data = $this->profileCompletionFlagStatus($type = "", $user_data);
                } else {
                    $this->message = "Problem occurred while updating the profile detail, Please try again later";
                }
            }
        } else {
            $this->message = "User not exists";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function sendNotifyEmail($user)
    {
        Mail::send(
            new RegistrationMailable(
                $user->first_name,
                $user->last_name,
                $user->email
            )
        );
    }

    public function facilityTypes()
    {
        $facilityTypes = $this->getFacilityType()->pluck('title', 'id');
        $data = [];
        foreach ($facilityTypes as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "facility type's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function NursingDegrees()
    {
        $nursingDegrees = $this->getNursingDegrees()->pluck('title', 'id');
        $data = [];
        foreach ($nursingDegrees as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Nursing degree's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function searchForCredentialsOptions()
    {
        $certifications = $this->getCertifications()->pluck('title', 'id');
        $data = [];
        foreach ($certifications as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Search for credentials has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getVaccinationList()
    {
        $keywords = Keyword::where('filter', 'Vaccinations')->get()->pluck('title', 'id');
            $data = [];
            foreach ($keywords as $key => $value) {
                $data[] = ['id' => $key, "name" => $value];
            }
        $this->check = "1";
        $this->message = "Vaccinations has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getEMRList()
    {
        $keywords = Keyword::where('filter', 'EMR')->get()->pluck('title', 'id');
            $data = [];
            foreach ($keywords as $key => $value) {
                $data[] = ['id' => $key, "name" => $value];
            }
        $this->check = "1";
        $this->message = "EMR has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobTypes(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $experience = $this->getjobTypes()->pluck('title', 'id');
            $data = [];
            foreach ($experience as $key => $value) {
                $data[] = ['id' => $key, "name" => $value];
            }
            $this->check = "1";
            $this->message = "Job types has been listed successfully";
            $this->return_data = $data;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function subjectTypes(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $experience = $this->getsubjectTypes()->pluck('title', 'id');
            $data = [];
            foreach ($experience as $key => $value) {
                $data[] = ['id' => $key, "name" => $value];
            }
            $this->check = "1";
            $this->message = "Subject types has been listed successfully";
            $this->return_data = $data;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
   

    }

    public function getMediaOptions(Nurse $nurse)
    {
        $certs = $nurse->getMedia('certificates');
        $data = [];
        foreach ($certs as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Search for credentials has been listed successfully";
        $this->return_data = $certs;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getEHRProficiencyExpOptions()
    {
        $ehrProficienciesExp = $this->getEHRProficiencyExp()->pluck('title', 'id');
        $data = [];
        foreach ($ehrProficienciesExp as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        asort($data);
        $data1 = [];
        foreach ($data as $key1 => $value1) {
            $data1[] = ['id' => strval($value1['id']), "name" => $value1['name']];
        }
        $this->check = "1";
        $this->message = "EHR proficiency exp options has been listed successfully";
        $this->return_data = $data1;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getNursingDegreesOptions()
    {
        $nursingDegrees = $this->getNursingDegrees()->pluck('title', 'id');
        $data = [];
        foreach ($nursingDegrees as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "EHR proficiency exp options has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function nurseExperienceSelectionOptions(Request $request)
    {
        $messages = [
            "id.required" => "Id is required"
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'api_key' => 'required',
        ], $messages);


        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse = Nurse::where('user_id', '=', $request->id)->first();
            if ($nurse != null) {
                $nuexperience = $this->nurseExperienceSelection($nurse);
                $data = [];
                foreach ($nuexperience as $key => $value) {
                    $data[] = ['id' => $key, "name" => $value];
                }
                $this->check = "1";
                $this->message = "facility type's has been listed successfully";
                $this->return_data = $nuexperience;
            } else {
                $this->message = "Nurse not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function Experience(Request $request)
    {
        $messages = [
            "highest_nursing_degree.required" => "Select highest nursing degree",
            "ehr_proficiency_cerner.required" => "Select Cerner",
            "ehr_proficiency_meditech.required" => "Select Meditech",
            "ehr_proficiency_epic.required" => "Select Epic",
            "ehr_proficiency_other.regex" => "Other ehr proficiency not valid",
            "college_uni_name.required" => "Please add college / university name",
            "college_uni_name.regex" => "College / university name not valid",
            "college_uni_name.min" => "College / university name is short",
            "college_uni_name.max" => "College / university name too long",
            "college_uni_city.required" => "Please add city",
            "college_uni_city.regex" => "City not valid",
            "college_uni_city.min" => "City is short",
            "college_uni_city.max" => "City is too long",
            "college_uni_country.required" => "Please add country",
            "college_uni_country.regex" => "Country not valid",
            "college_uni_country.min" => "Country is short",
            "college_uni_country.max" => "Country is too long",
            "experience_as_acute_care_facility.regex" => "Enter valid acute care facility experience",
            "experience_as_ambulatory_care_facility.regex" => "Enter valid non-acute care nursing experience",
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'highest_nursing_degree' => 'required',
            'college_uni_name' => 'required|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:255',
            'college_uni_city' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:50',
            'college_uni_state' => 'required',
            'college_uni_country' => 'required|regex:/^[a-zA-Z ]+$/|min:3',
            'experience_as_acute_care_facility' => 'nullable|regex:/^[0-9.\+]+$/|max:5',
            'experience_as_ambulatory_care_facility' => 'nullable|regex:/^[0-9.\+]+$/|max:5',
            'ehr_proficiency_cerner' => 'required',
            'ehr_proficiency_meditech' => 'required',
            'ehr_proficiency_epic' => 'required',
            'ehr_proficiency_other' => 'nullable|regex:/^[a-zA-Z 0-9]+$/|min:2|max:50',
            'api_key' => 'required',
        ], $messages);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', '=', $request->id);
            if ($user_info->count() > 0) {
                $user = $user_info->first();
                $nurse_info = Nurse::where('user_id', '=', $request->id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->first();

                    $update_data = [
                        'highest_nursing_degree' => $request->highest_nursing_degree,
                        'college_uni_name' => $request->college_uni_name,
                        'college_uni_city' => $request->college_uni_city,
                        'college_uni_state' => $request->college_uni_state,
                        'college_uni_country' => $request->college_uni_country,
                        'experience_as_acute_care_facility' => $request->experience_as_acute_care_facility,
                        'experience_as_ambulatory_care_facility' => $request->experience_as_ambulatory_care_facility,
                        'ehr_proficiency_cerner' => $request->ehr_proficiency_cerner,
                        'ehr_proficiency_meditech' => $request->ehr_proficiency_meditech,
                        'ehr_proficiency_epic' => $request->ehr_proficiency_epic,
                        'ehr_proficiency_other' =>  $request->ehr_proficiency_other,
                    ];
                    $update = NURSE::where(['id' => $nurse->id])->update($update_data);
                    if ($update) {
                        $this->check = "1";
                        $this->message = "Experience updated successfully";
                        $this->return_data = $this->profileCompletionFlagStatus($type = "", $user);
                        // $this->return_data = $experience;
                    } else {
                        $this->message = "Failed to update the experience, Please try again later";
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

    public function addCredentials(Request $request)
    {
        $messages = [
            "user_id.required" => "user_id is required",
            "type.required" => "Select Certification type",
            "type.exists" => "Selected Certification type does not exist",
            "effective_date.required" => "Enter Issue Date",
            "effective_date.date" => "Issue Date is not valid",
            "expiration_date.required" => "Enter Expiration Date",
            "expiration_date.date" => "Expiration Date is not valid",
            "expiration_date.after" => "Expiration Date should be after Isuue Date.",
            "certificate_image.max" => "Allowed File Size is 5 MB",
            "certificate_image.mimes" => "Allowed File Types are jpeg, png, jpg, pdf",
            "resume.mimes" => "Allowed File Types are doc, docx, pdf, txt",
            "resume.max" => "Allowed File Size is 2 MB",
        ];

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required|numeric|exists:keywords,id',
            'effective_date' => 'required|date',
            'expiration_date' => "required|date|after:effective_date",
            'renewal_date' => "date",
            'certificate_image' => 'nullable|max:5120|mimes:jpeg,png,jpg,pdf',
            'resume' => 'mimes:doc,docx,pdf,txt|max:2048',
            'api_key' => 'required',
        ], $messages);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            if(isset($request->role)){
                $nurse_info = Nurse::where('id', '=', $request->nurse_id);
            }else{
                $nurse_info = Nurse::where('user_id', '=', $request->user_id);
            }
            
            if ($nurse_info->count() > 0) {
                $nurse = $nurse_info->first();
                
                /* certification */
                $add_array = [
                    'nurse_id' => $nurse->id,
                    'type' => $request->type,
                    'effective_date' => $request->effective_date,
                    'expiration_date' => $request->expiration_date,
                    "organization" => $request->organization,
                    "renewal_date" => $request->renewal_date,
                    'license_number' => $request->license_number
                ];
                $check = Certification::where('nurse_id', '=', $nurse->id)->get()->first();
                if(isset($check)){
                    $certification = Certification::where('nurse_id', '=', $nurse->id)->update($add_array);
                    // DB::table('user')->where('email', $userEmail)->update(array('member_type' => $plan));
                }else{
                    $certification = Certification::create($add_array);
                }

                if ($request->hasFile('certificate_image')) {
                    $certificate_image_name_full = $request->file('certificate_image')->getClientOriginalName();
                    $certificate_image_name = pathinfo($certificate_image_name_full, PATHINFO_FILENAME);
                    $certificate_image_ext = $request->file('certificate_image')->getClientOriginalExtension();
                    $certificate_image = $certificate_image_name . '_' . time() . '.' . $certificate_image_ext;
                    $certification_array["certificate_image"] = $certificate_image;
                    $certification_img_update = Certification::where(['id' => $certification->id])->update($certification_array);
                    //Upload Image
                    $request->file('certificate_image')->storeAs('assets/nurses/certifications/' . $nurse->id, $certificate_image);
                }
                /* certification */

                if ($certification == true) {
                    $cert_ret = Certification::where('id', '=', isset($certification->id)?$certification->id:$check->id)->first();

                    /* certificate data */
                    $certifications = $this->getCertifications()->pluck('title', 'id');
                    $cert_data["id"] = (isset($cert_ret->id) && $cert_ret->id != "") ? $cert_ret->id : "";
                    $cert_data["nurse_id"] = (isset($cert_ret->nurse_id) && $cert_ret->nurse_id != "") ? $cert_ret->nurse_id : "";
                    $cert_data["type"] = (isset($cert_ret->type) && $cert_ret->type != "") ? $cert_ret->type : "";
                    $cert_data["type_definition"] = (isset($certifications[$cert_ret->type]) && $certifications[$cert_ret->type] != "") ? $certifications[$cert_ret->type] : "";
                    $cert_data["license_number"] = (isset($cert_ret->license_number) && $cert_ret->license_number != "") ? $cert_ret->license_number : "";
                    $cert_data["effective_date"] = (isset($cert_ret->effective_date) && $cert_ret->effective_date != "") ?  date('m/d/Y', strtotime($cert_ret->effective_date)) : "";
                    $cert_data["expiration_date"] = (isset($cert_ret->expiration_date) && $cert_ret->expiration_date != "") ?  date('m/d/Y', strtotime($cert_ret->expiration_date)) : "";
                    $cert_data["renewal_date"] = (isset($cert_ret->renewal_date) && $cert_ret->renewal_date != "") ?  date('m/d/Y', strtotime($cert_ret->renewal_date)) : "";
                    $cert_data["certificate_image"] = (isset($cert_ret->certificate_image) && $cert_ret->certificate_image != "") ? url('storage/assets/nurses/certifications/' . $nurse->id . '/' . $cert_ret->certificate_image) : "";
                    $cert_data["organization"] = (isset($cert_ret->organization) && $cert_ret->organization != "") ? $cert_ret->organization : "";
                    // $cert_data["deleted_at"] = (isset($cert_ret->deleted_at) && $cert_ret->deleted_at != "") ? date('m/d/Y H:i:s', strtotime($cert_ret->deleted_at)) : "";
                    $cert_data["created_at"] = (isset($cert_ret->created_at) && $cert_ret->created_at != "") ?  date('m/d/Y H:i:s', strtotime($cert_ret->created_at)) : "";
                    // $cert_data["updated_at"] = (isset($cert_ret->updated_at) && $cert_ret->updated_at != "") ?  date('m/d/Y H:i:s', strtotime($cert_ret->updated_at)) : "";
                    /* certificate data */

                    /* nurse data */
                    $nurse_return = Nurse::select('resume')->where('user_id', '=', $request->id)->first();
                    $cert_data["resume"] = (isset($nurse_return->resume) && $nurse_return->resume != "") ? url('storage/assets/nurses/resumes/' . $nurse->id . '/' . $nurse_return->resume) : "";
                    /* nurse data */

                    $this->check = "1";
                    $this->message = "Certification added successfully";
                    $this->return_data = $cert_data;
                } else {
                    $this->message = "Problem occurred while updating certification, Please try again later";
                }
            } else {
                $this->message = "Nurse not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function editCredentials(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'certificate_id' => 'required',
            'type' => 'required|numeric|exists:keywords,id',
            'effective_date' => 'required|date',
            'expiration_date' => "required|date|after:effective_date",
            'renewal_date' => "date",
            'certificate_image' => 'nullable|max:5120|mimes:jpeg,png,jpg,pdf',
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
                    $certificate_array = [
                        "type" => $request->type,
                        "effective_date" => $request->effective_date,
                        "expiration_date" => $request->expiration_date,
                        "organization" => $request->organization,
                        "renewal_date" => $request->renewal_date,
                        'license_number' => $request->license_number
                    ];
                    if ($request->hasFile('certificate_image')) {
                        $certificate_image_name_full = $request->file('certificate_image')->getClientOriginalName();
                        $certificate_image_name = pathinfo($certificate_image_name_full, PATHINFO_FILENAME);
                        $certificate_image_ext = $request->file('certificate_image')->getClientOriginalExtension();
                        $certificate_image = $certificate_image_name . '_' . time() . '.' . $certificate_image_ext;
                        $certificate_array["certificate_image"] = $certificate_image;
                        //Upload Image
                        $request->file('certificate_image')->storeAs('assets/nurses/certifications/' . $nurse->id, $certificate_image);
                    }
                    $certification = Certification::where(['id' => $request->certificate_id])->update($certificate_array);
                    if ($certification == true) {
                        $this->check = "1";
                        $this->message = "Certificate updated successfully";
                        $this->return_data = $this->profileCompletionFlagStatus($type = "", $user);
                    } else {
                        $this->message = "Failed to update the certificate. Please try again later";
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

    public function removeCredentialDoc(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'certificate_id' => 'required',
            // 'certificate_image' => 'required'
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

                    $certificate = Certification::where(['id' => $request->certificate_id])->whereNull('deleted_at')->get();
                    if ($certificate->count() > 0) {
                        $cert = $certificate->first();

                        $del = Storage::delete('assets/nurses/certifications/' . $nurse->id . '/' . $cert->certificate_image);
                        $remove = Certification::where(['id' => $request->certificate_id])->update(['deleted_at' => date('m/d/Y H:i:s')]);
                        if ($del && $remove) {
                            $this->check = "1";
                            $this->message = "Certificate removed successfully";
                        } else {
                            $this->message = "Failed to remove or certificate removed already. Please try again later";
                        }
                    } else {
                        $this->message = "Certificate already removed";
                    }

                    /* $file = explode("/", $request->certificate_image); //file_exists();
                    if (isset($file) && is_array($file) && !empty($file)) {
                        $t = Storage::exists('assets/nurses/certifications/' . $nurse->id . '/' . end($file));
                        if ($t) {
                        } else {
                            $this->message = "Certificate already removed";
                        }
                    } */
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

    public function leadershipRoles()
    {
        $leadershipRoles = $this->getLeadershipRoles()->pluck('title', 'id');
        $data = [];
        foreach ($leadershipRoles as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Leadership roles has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getLanguages()
    {
        $languages = $this->getLanguageOptions();
        $data = [];
        foreach ($languages as $key => $value) {
            $data[] = ["language" => $value];
        }
        $this->check = "1";
        $this->message = "Languages has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function rolePage1(Request $request)
    {
        $messages = [
            "id.required" => "Id is required",
            "leadership_roles.required_if" => "Please select leadership role",
        ];

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'serving_preceptor' => 'boolean',
            'serving_interim_nurse_leader' => 'boolean',
            'leadership_roles' => 'required_if:serving_interim_nurse_leader,1',
            'clinical_educator' => 'boolean',
            'is_daisy_award_winner' => 'boolean',
            'employee_of_the_mth_qtr_yr' => 'boolean',
            'other_nursing_awards' => 'boolean',
            'is_professional_practice_council' => 'boolean',
            'is_research_publications' => 'boolean',
            'api_key' => 'required',
        ], $messages);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $return_data = [];
            $nurse =  Nurse::where('user_id', '=', $request->id)->first();
            $params = $request->toArray();
            $params['serving_preceptor'] =
                isset($params['serving_preceptor']) && !!$params['serving_preceptor'];
            $params['serving_interim_nurse_leader'] =
                isset($params['serving_interim_nurse_leader']) && !!$params['serving_interim_nurse_leader'];
            $params['clinical_educator'] =
                isset($params['clinical_educator']) && !!$params['clinical_educator'];
            $params['is_daisy_award_winner'] =
                isset($params['is_daisy_award_winner']) && !!$params['is_daisy_award_winner'];
            $params['employee_of_the_mth_qtr_yr'] =
                isset($params['employee_of_the_mth_qtr_yr']) && !!$params['employee_of_the_mth_qtr_yr'];
            $params['other_nursing_awards'] =
                isset($params['other_nursing_awards']) && !!$params['other_nursing_awards'];
            $params['is_professional_practice_council'] =
                isset($params['is_professional_practice_council']) && !!$params['is_professional_practice_council'];
            $params['is_research_publications'] =
                isset($params['is_research_publications']) && !!$params['is_research_publications'];
            $nurse->update($params);
            $this->check = "1";
            $this->return_data = $nurse;
            $this->message = "Role and Interest Updated Successfully";
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function rolePage2(Request $request)
    {
        $messages = [
            'id' => 'ID is required',
            "additional_pictures.max" => "Additional Photos can't be more than 4.",
            "additional_files.max" => "Additional Files can't be more than 4.",
            "additional_pictures.*.mimes" => "Additional Photos should be image or png jpg",
            "additional_files.*.mimes" => "Additional Files should be doc or pdf",
            "additional_pictures.*.max" => "Additional Photos should not be more than 5mb",
            "additional_files.*.max" => "Additional Files should not be more than 1mb",
            "nu_video.url" => "YouTube and Vimeo should be a valid link",
            "nu_video.max" => "YouTube and Vimeo should be a valid link"
        ];

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'additional_pictures' => 'max:4',
            'additional_pictures.*' => 'nullable|max:5120|image|mimes:jpeg,png,jpg',
            'additional_files' => 'max:4',
            'additional_files.*' => 'nullable|max:1024|mimes:pdf,doc,docx',
            'nu_video' => 'nullable|url|max:255',
            'api_key' => 'required',
        ], $messages);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $return_data = [];
            $nurse =  Nurse::where('user_id', '=', $request->id)->first();
            $nurse->summary = $request->summary;
            $nurse->save();
            if (preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->nu_video, $vresult)) {
                $youTubeID = $this->parse_youtube($request->nu_video);
                $embedURL = 'https://www.youtube.com/embed/' . $youTubeID[1];
                $nurse->__set('nu_video_embed_url', $embedURL);
                $nurse->update();
            } elseif (preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->nu_video, $vresult)) {
                $vimeoID = $this->parse_vimeo($request->nu_video);
                $embedURL = 'https://player.vimeo.com/video/' . $vimeoID[1];
                $nurse->__set('nu_video_embed_url', $embedURL);
                $nurse->update();
            }
            if ($additional_photos = $request->file('additional_pictures')) {
                foreach ($additional_photos as $additional_photo) {
                    $additional_photo_name_full = $additional_photo->getClientOriginalName();
                    $additional_photo_name = pathinfo($additional_photo_name_full, PATHINFO_FILENAME);
                    $additional_photo_ext = $additional_photo->getClientOriginalExtension();
                    $additional_photo_finalname = $additional_photo_name . '_' . time() . '.' . $additional_photo_ext;
                    //Upload Image
                    $additional_photo->storeAs('assets/nurses/additional_photos/' . $nurse->id, $additional_photo_finalname);
                    NurseAsset::create([
                        'nurse_id' => $nurse->id,
                        'name' => $additional_photo_finalname,
                        'filter' => 'additional_photos'
                    ]);
                }
            }
            if ($additional_files = $request->file('additional_files')) {
                foreach ($additional_files as $additional_file) {
                    $additional_file_name_full = $additional_file->getClientOriginalName();
                    $additional_file_name = pathinfo($additional_file_name_full, PATHINFO_FILENAME);
                    $additional_file_ext = $additional_file->getClientOriginalExtension();
                    $additional_file_finalname = $additional_file_name . '_' . time() . '.' . $additional_file_ext;
                    //Upload Image
                    $additional_file->storeAs('assets/nurses/additional_files/' . $nurse->id, $additional_file_finalname);
                    NurseAsset::create([
                        'nurse_id' => $nurse->id,
                        'name' => $additional_file_finalname,
                        'filter' => 'additional_files'
                    ]);
                }
            }
            $this->check = "1";
            $this->return_data = $nurse;
            $this->message = "Role and Interest Updated Successfully";
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

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
                $j_data["offer_id"] = isset($job['offer_id']) ? $job['offer_id'] : "";
                $j_data["job_id"] = isset($job['job_id']) ? $job['job_id'] : "";
                $j_data["end_date"] = isset($job['end_date']) ? date('d F Y', strtotime($job['end_date'])) : "";
                $j_data["end_date_comp"] = isset($job['end_date'])?date("Y-m-d", strtotime($job['end_date'])):'';
                $j_data["start_date_comp"] = isset($job['start_date'])?date("Y-m-d", strtotime($job['start_date'])):'';
                $j_data["start_date"] = isset($job['start_date']) ? $job['start_date'] : "";
                $j_data["job_type"] = isset($job['job_type']) ? $job['job_type'] : "";
                $j_data["type"] = isset($job['type']) ? $job['type'] : "";
                $j_data["job_name"] = isset($job['job_name']) ? $job['job_name'] : "";
                $j_data["keyword_title"] = isset($job['keyword_title']) ? $job['keyword_title'] : "";
                $j_data["keyword_filter"] = isset($job['keyword_filter']) ? $job['keyword_filter'] : "";
                $j_data["auto_offers"] = isset($job['auto_offers']) ? $job['auto_offers'] : "";
                $j_data["active"] = isset($job['active']) ? $job['active'] : "";

                $j_data["job_location"] = isset($job['job_location']) ? $job['job_location'] : "";
                $j_data["position_available"] = isset($job['position_available']) ? $job['position_available'] : "";
                $j_data["employer_weekly_amount"] = isset($job['employer_weekly_amount']) ? $job['employer_weekly_amount'] : "";
                $j_data["weekly_pay"] = isset($job['weekly_pay']) ? $job['weekly_pay'] : "";
                $j_data["hours_per_week"] = isset($job['hours_per_week']) ? $job['hours_per_week'] : 0;
                
                $j_data["preferred_specialty"] = isset($job['preferred_specialty']) ? $job['preferred_specialty'] : "";
                $j_data["preferred_specialty_definition"] = isset($specialties[$job['preferred_specialty']])  ? $specialties[$job['preferred_specialty']] : "";

                $j_data["preferred_assignment_duration"] = isset($job['preferred_assignment_duration']) ? $job['preferred_assignment_duration'] : "";
                $j_data["preferred_assignment_duration_definition"] = isset($assignmentDurations[$job['preferred_assignment_duration']]) ? $assignmentDurations[$job['preferred_assignment_duration']] : "";
                if(isset($j_data["preferred_assignment_duration_definition"]) && !empty($j_data["preferred_assignment_duration_definition"])){
                    $assignment = explode(" ", $assignmentDurations[$job['preferred_assignment_duration']]);
                    $j_data["preferred_assignment_duration_definition"] = $assignment[0]; // 12 Week
                } 
                $j_data["preferred_shift_duration"] = isset($job['preferred_shift']) ? $job['preferred_shift'] : "";
                // $j_data["preferred_shift_duration_definition"] = isset($shifts[$job->preferred_shift_duration]) ? $shifts[$job->preferred_shift_duration] : "";
                
                $j_data["preferred_work_location"] = isset($job['preferred_work_location']) ? $job['preferred_work_location'] : "";
                $j_data["preferred_work_location_definition"] = isset($workLocations[$job['preferred_work_location']]) ? $workLocations[$job['preferred_work_location']] : "";

                $j_data["preferred_work_area"] = isset($job['preferred_work_area']) ? $job['preferred_work_area'] : "";
                $j_data["preferred_days_of_the_week"] = isset($job['preferred_days_of_the_week']) ? explode(",", $job['preferred_days_of_the_week']) : [];
                $j_data["preferred_hourly_pay_rate"] = isset($job['preferred_hourly_pay_rate']) ? $job['preferred_hourly_pay_rate'] : "";
                $j_data["preferred_experience"] = isset($job['preferred_experience']) ? $job['preferred_experience'] : "";
                $j_data["description"] = isset($job['description']) ? $job['description'] : "";
                // $time_difference = strtotime($job['created_at']);
                $time_difference = time() - strtotime($job['created_at']);
                if($time_difference > 3599){
                    $j_data["created_at_browse"] = isset($job['created_at']) ?$this->timeAgo(date(strtotime($job['created_at']))) : "";
                }else{
                    $j_data["created_at_browse"] = isset($job['created_at']) ?'Recently Added' : "";
                }
                $j_data["created_at"] = isset($job['created_at']) ? date('d-F-Y h:i A', strtotime($job['created_at'])) : "";
                $j_data["created_at_definition"] = isset($job['created_at']) ?date('M d Y', strtotime($job['created_at'])) : "";
                
                $j_data["updated_at"] = isset($job->updated_at) ? date('M d Y', strtotime($job->updated_at)) : "";
                $j_data["deleted_at"] = isset($job->deleted_at) ? date('d-F-Y h:i A', strtotime($job->deleted_at)) : "";
                $j_data["created_by"] = isset($job->created_by) ? $job->created_by : "";
                $j_data["slug"] = isset($job['slug']) ? $job['slug'] : "";
                $j_data["facility_id"] = isset($job['facility_id']) ? $job['facility_id'] : "";
                
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
                $j_data["is_open"] = isset($job['is_open']) ? $job['is_open'] : "";
                $j_data["name"] = isset($job['facility']->name) ? $job['facility']->name : "";
                // $j_data["city"] = isset($job->facility->city) ? $job->facility->city : "";
                // $j_data["state"] = isset($job->facility->state) ? $job->facility->state : "";
                $j_data["city"] = isset($job['job_city']) ? $job['job_city'] : "";
                $j_data["state"] = isset($job['job_state']) ? $job['job_state']: "";
                $j_data["postcode"] = isset($job['facility']->postcode) ? $job['facility']->postcode : "";
                
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
                $total_follow_count = Follows::where(['job_id' => $job['job_id'], "applied_status" => "1", 'status' => "1"])->distinct('user_id')->count();
                $j_data["total_applied"] = strval($total_follow_count);
                /* total applied */

                /* liked */
                $is_applied = "0";
                if ($user_id != "")
                    // $is_applied = Follows::where(['job_id' => $job->job_id, "applied_status" => "1", 'status' => "1", "user_id" => $user_id])->count();
                    $is_applied = Follows::where(['job_id' => $job['job_id'], "applied_status" => "1", 'status' => "1", "user_id" => $user_id])->distinct('user_id')->count();
                /* liked */
                $j_data["is_applied"] = strval($is_applied);

                /* liked */
                $is_liked = "0";
                if ($user_id != "")
                    $is_liked = Follows::where(['job_id' => $job['job_id'], "like_status" => "1", 'status' => "1", "user_id" => $user_id])->count();
                
                /* liked */
                $j_data["is_liked"] = strval($is_liked);

                // $j_data["shift"] = "Days";
                $j_data["start_date"] = date('d F Y', strtotime($job['start_date']));

                $j_data['applied_nurses'] = '0';
                $applied_nurses = Offer::where(['job_id' => $job['job_id'], 'status'=>'Apply'])->count();
                $j_data['applied_nurses'] = strval($applied_nurses);

                $is_saved = '0';
                if ($user_id != ""){
                    $nurse_info = NURSE::where('user_id', $user_id);
                    if ($nurse_info->count() > 0) {
                        $nurse = $nurse_info->first();
                        $whereCond = [
                            'job_saved.nurse_id' => $user_id,
                            'job_saved.job_id' => $job['job_id'],
                        ];
                        $limit = 10;
                        $saveret = \DB::table('job_saved')
                        ->join('jobs', 'jobs.id', '=', 'job_saved.job_id')
                        ->where($whereCond);
                        
                        if ($saveret->count() > 0) {
                            $is_saved = '1';
                        }
                        
                    }
                }
                $j_data["is_saved"] = $is_saved;
                $result[] = $j_data;
            }
        }
        return $result;
    }

    public function browse_facilities(Request $request)
    {
        if (isset($request->facility_id) && $request->facility_id != "") {
            $whereCond = ['facilities.id' => $request->facility_id, 'facilities.active' => true];
        } else {
            // $whereCond = ['facilities.active' => true, 'jobs.is_open' => "1"];
            $whereCond = ['facilities.active' => true];
        }

        $ret = Facility::select('facilities.id as facility_id', 'facilities.*', 'jobs.preferred_specialty')
            ->leftJoin('jobs', function ($join) {
                $join->on('facilities.id', '=', 'jobs.facility_id');
            })
            ->where($whereCond);

        if (isset($request->facility_type) && $request->facility_type  != "") {
            $type = $request->facility_type;
            $ret->where(function (Builder $query) use ($type) {
                $query->whereIn('type', $type);
            });
        }

        if (isset($request->electronic_medical_records) && $request->electronic_medical_records != "") {
            $electronic_medical_records = $request->electronic_medical_records;
            $ret->where(function (Builder $query) use ($electronic_medical_records) {
                $query->whereIn('f_emr', $electronic_medical_records);
            });
        }

        /* name search for api */
        if (isset($request->search_keyword) && $request->search_keyword != "") {
            $search_keyword = $request->search_keyword;
            $ret->search([
                'name'
            ], $search_keyword);
        }
        /* name search for api */

        /*new update jan 10*/
        $open_assignment_type = (isset($request->open_assignment_type) && $request->open_assignment_type != "") ? $request->open_assignment_type : "";
        /*if ($open_assignment_type) {
                $ret->where('jobs.preferred_specialty', '=', $open_assignment_type);
        }*/
        if ($open_assignment_type != "") {
            $ret->where(function (Builder $query) use ($open_assignment_type) {
                $query->whereIn('jobs.preferred_specialty', $open_assignment_type);
            });
        }
        /*new update jan 10*/

        /*new update jan 10*/
        /* state city and postcode new update */
        $states = (isset($request->state) && $request->state != "") ? $request->state : "";
        if (isset($states) && $states != "") {
            $getStates = States::where(['id' => $states])->get();
            if ($getStates->count() > 0) {
                $selected_state = $getStates->first();
                $name = $selected_state->name;
                $iso2 = $selected_state->iso2;
                $ret->where(function (Builder $query1) use ($name, $iso2) {
                    $query1->where('state', array($name));
                    $query1->orWhere('state', array($iso2));
                });
            }
        }

        $cities = (isset($request->city) && $request->city != "") ? $request->city : "";
        if (isset($cities) && $cities != "") {
            $getCities = Cities::where(['id' => $cities])->get();
            if ($getCities->count() > 0) {
                $selected_city = $getCities->first();
                $name = $selected_city->name;
                $ret->where(function (Builder $query1) use ($name) {
                    $query1->where('city', array($name));
                });
            }
        }

        $zipcode = (isset($request->zipcode) && $request->zipcode != "") ? $request->zipcode : "";
        if (isset($zipcode) && $zipcode != "") {
            $ret->where(function (Builder $query_zip) use ($zipcode) {
                $query_zip->where('postcode', array($zipcode));
            });
            /*$zipcode_inp = [];
                $nearest = $this->getNearestMiles($zipcode);
                if (isset($nearest['results']) && !empty($nearest['results'])) {
                    foreach ($nearest['results'] as $zipkey => $zip_res) {
                        $zipcode_inp[] = $zip_res['code'];
                    }
                }
                if (!empty($zipcode_inp)) {
                    $ret->where(function (Builder $query_zip) use ($zipcode_inp) {
                        $query_zip->whereIn('postcode', $zipcode_inp);
                    });
                } else {
                    $ret->where(function (Builder $query_zip) use ($zipcode) {
                        $query_zip->where('postcode', array($zipcode));
                    });
                }*/
        }
        /* state city and postcode new update */
        /*new update jan 10*/

        $ret->groupBy('facilities.id')->orderBy('created_at', 'desc');
        $facility_data = (isset($request->facility_id) && $request->facility_id != "") ? $ret->paginate(1) : $ret->paginate(10);
        $user_id = (isset($request->user_id) && $request->user_id != "") ? $request->user_id : "";

        $response = $this->facilityData($facility_data, $user_id);
        // $response = $this->facilityData($facility_data, $user_id = $request->user_id);

        $this->check = "1";
        $this->message = "Facilities listed below";
        $this->return_data = $response;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function facilityFollows(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'facility_id' => 'required',
            'type' => 'required',
            'api_key' => 'required',
        ]);


        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_exists = FacilityFollows::where([
                'user_id' => $request->user_id,
                'facility_id' => $request->facility_id,
            ]);
            if ($check_exists->count() > 0) {
                $follows = FacilityFollows::where([
                    'user_id' => $request->user_id,
                    'facility_id' => $request->facility_id,
                ])->update(['follow_status' => strval($request->type)]);
            } else {
                $follows = FacilityFollows::create([
                    'user_id' => $request->user_id,
                    'facility_id' => $request->facility_id,
                    'follow_status' => strval($request->type)
                ]);
            }

            $this->check = "1";
            $this->message = ($request->type == "1") ? "Followed successfully" : "Unfollowed successfully";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function facilityLikes(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'facility_id' => 'required',
            'like' => 'required',
            'api_key' => 'required',
        ]);


        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_exists = FacilityFollows::where([
                'user_id' => $request->user_id,
                'facility_id' => $request->facility_id,
            ]);
            if ($check_exists->count() > 0) {
                $follows = FacilityFollows::where([
                    'user_id' => $request->user_id,
                    'facility_id' => $request->facility_id,
                ])->update(['like_status' => $request->like]);
            } else {
                $follows = FacilityFollows::create([
                    'user_id' => $request->user_id,
                    'facility_id' => $request->facility_id,
                    'like_status' => $request->like
                ]);
            }

            $this->check = "1";
            $this->message = ($request->like == "1") ? "Liked successfully" : "Disliked successfully";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function facilityData($facility_result, $user_id = "")
    {
        $result = [];
        if (!empty($facility_result)) {
            foreach ($facility_result as $key => $facility_data) {
                $facility["id"] = (isset($facility_data->id) && $facility_data->id != "") ? $facility_data->facility_id : "";
                $facility["facility_logo"]  = (isset($facility_data->facility_logo) && $facility_data->facility_logo != "") ?  url("public/images/facilities/" . $facility_data->facility_logo) : "";
                
                $facility["created_by"] = (isset($facility_data->created_by) && $facility_data->created_by != "") ? $facility_data->created_by : "";
                $facility["name"] = (isset($facility_data->name) && $facility_data->name != "") ? $facility_data->name : "";
                $facility["address"] = (isset($facility_data->address) && $facility_data->address != "") ? $facility_data->address : "";
                $facility["city"] = (isset($facility_data->city) && $facility_data->city != "") ? $facility_data->city : "";
                $facility["state"] = (isset($facility_data->state) && $facility_data->state != "") ? $facility_data->state : "";
                $facility["postcode"] = (isset($facility_data->postcode) && $facility_data->postcode != "") ? $facility_data->postcode : "";
                $facility["facility_type"] = (isset($facility_data->type) && $facility_data->type != "") ? strval($facility_data->type) : "";
                $facility["facility_type_definition"] = (isset($facility_data->type) && $facility_data->type != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->type) : "";
                $facility["active"] = (isset($facility_data->active) && $facility_data->active != "") ? $facility_data->active : "";
                $facility["deleted_at"] = (isset($facility_data->deleted_at) && $facility_data->deleted_at != "") ? $facility_data->deleted_at : "";
                $facility["created_at"] = (isset($facility_data->created_at) && $facility_data->created_at != "") ? $facility_data->created_at : "";
                $facility["updated_at"] = (isset($facility_data->updated_at) && $facility_data->updated_at != "") ? $facility_data->updated_at : "";
                $facility["facility_email"] = (isset($facility_data->facility_email) && $facility_data->facility_email != "") ? $facility_data->facility_email : "";
                $facility["facility_phone"] = (isset($facility_data->facility_phone) && $facility_data->facility_phone != "") ? $facility_data->facility_phone : "";
                $facility["specialty_need"] = (isset($facility_data->specialty_need) && $facility_data->specialty_need != "") ? $facility_data->specialty_need : "";
                $facility["slug"] = (isset($facility_data->slug) && $facility_data->slug != "") ? $facility_data->slug : "";
                $facility["cno_message"] = (isset($facility_data->cno_message) && $facility_data->cno_message != "") ? $facility_data->cno_message : "";

                $facility["cno_image"] = (isset($facility_data->cno_image) && $facility_data->cno_image != "") ? url('public/images/facilities/cno_image/' . $facility_data->cno_image) : "";
                $facility["gallery_images"] = (isset($facility_data->gallary_images) && $facility_data->gallary_images != "") ? $facility_data->gallary_images : "";
                $facility["video"] = (isset($facility_data->video) && $facility_data->video != "") ? $facility_data->video : "";
                $facility["facebook"] = (isset($facility_data->facebook) && $facility_data->facebook != "") ? $facility_data->facebook : "";
                $facility["twitter"] = (isset($facility_data->twitter) && $facility_data->twitter != "") ? $facility_data->twitter : "";
                $facility["linkedin"] = (isset($facility_data->linkedin) && $facility_data->linkedin != "") ? $facility_data->linkedin : "";
                $facility["instagram"] = (isset($facility_data->instagram) && $facility_data->instagram != "") ? $facility_data->instagram : "";
                $facility["pinterest"] = (isset($facility_data->pinterest) && $facility_data->pinterest != "") ? $facility_data->pinterest : "";
                $facility["tiktok"] = (isset($facility_data->tiktok) && $facility_data->tiktok != "") ? $facility_data->tiktok : "";
                $facility["sanpchat"] = (isset($facility_data->sanpchat) && $facility_data->sanpchat != "") ? $facility_data->sanpchat : "";
                $facility["youtube"] = (isset($facility_data->youtube) && $facility_data->youtube != "") ? $facility_data->youtube : "";
                $facility["about_facility"] = (isset($facility_data->about_facility) && $facility_data->about_facility != "") ? $facility_data->about_facility : "";
                $facility["facility_website"] = (isset($facility_data->facility_website) && $facility_data->facility_website != "") ? $facility_data->facility_website : "";
                $facility["video_embed_url"] = (isset($facility_data->video_embed_url) && $facility_data->video_embed_url != "") ? $facility_data->video_embed_url : "";
                $facility["f_lat"] = (isset($facility_data->f_lat) && $facility_data->f_lat != "") ? $facility_data->f_lat : "";
                $facility["f_lang"] = (isset($facility_data->f_lang) && $facility_data->f_lang != "") ? $facility_data->f_lang : "";
                $facility["f_emr"] = (isset($facility_data->f_emr) && $facility_data->f_emr != "") ? $facility_data->f_emr : "";
                $facility["f_emr_definition"] = (isset($facility_data->f_emr) && $facility_data->f_emr != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->f_emr) : "";
                $facility["f_emr_other"] = (isset($facility_data->f_emr_other) && $facility_data->f_emr_other != "") ? $facility_data->f_emr_other : "";

                $facility["f_bcheck_provider"] = (isset($facility_data->f_bcheck_provider) && $facility_data->f_bcheck_provider != "") ? $facility_data->f_bcheck_provider : "";
                if ($facility["f_bcheck_provider"] == "0") $facility["f_bcheck_provider_definition"] = "Other";
                else $facility["f_bcheck_provider_definition"] = (isset($facility_data->f_bcheck_provider) && $facility_data->f_bcheck_provider != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->f_bcheck_provider) : "";
                $facility["f_bcheck_provider_other"] = (isset($facility_data->f_bcheck_provider_other) && $facility_data->f_bcheck_provider_other != "") ? $facility_data->f_bcheck_provider_other : "";

                $facility["nurse_cred_soft"] = (isset($facility_data->nurse_cred_soft) && $facility_data->nurse_cred_soft != "") ? $facility_data->nurse_cred_soft : "";
                if ($facility["nurse_cred_soft"] == "0") $facility["nurse_cred_soft_definition"] = "Other";
                else $facility["nurse_cred_soft_definition"] = (isset($facility_data->nurse_cred_soft) && $facility_data->nurse_cred_soft != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->nurse_cred_soft) : "";
                $facility["nurse_cred_soft_other"] = (isset($facility_data->nurse_cred_soft_other) && $facility_data->nurse_cred_soft_other != "") ? $facility_data->nurse_cred_soft_other : "";

                $facility["nurse_scheduling_sys"] = (isset($facility_data->nurse_scheduling_sys) && $facility_data->nurse_scheduling_sys != "") ? $facility_data->nurse_scheduling_sys : "";
                if ($facility["nurse_scheduling_sys"] == "0") $facility["nurse_scheduling_sys_definition"] = "Other";
                else $facility["nurse_scheduling_sys_definition"] = (isset($facility_data->nurse_scheduling_sys) && $facility_data->nurse_scheduling_sys != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->nurse_scheduling_sys) : "";
                $facility["nurse_scheduling_sys_other"] = (isset($facility_data->nurse_scheduling_sys_other) && $facility_data->nurse_scheduling_sys_other != "") ? $facility_data->nurse_scheduling_sys_other : "";

                $facility["time_attend_sys"] = (isset($facility_data->time_attend_sys) && $facility_data->time_attend_sys != "") ? $facility_data->time_attend_sys : "";
                $facility["time_attend_sys_definition"] = (isset($facility_data->time_attend_sys) && $facility_data->time_attend_sys != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->time_attend_sys) : "";
                $facility["time_attend_sys_other"] = (isset($facility_data->time_attend_sys_other) && $facility_data->time_attend_sys_other != "") ? $facility_data->time_attend_sys_other : "";

                $facility["licensed_beds"] = (isset($facility_data->licensed_beds) && $facility_data->licensed_beds != "") ? $facility_data->licensed_beds : "";
                $facility["licensed_beds_definition"] = (isset($facility_data->licensed_beds) && $facility_data->licensed_beds != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->licensed_beds) : "";
                $facility["trauma_designation"] = (isset($facility_data->trauma_designation) && $facility_data->trauma_designation != "") ? $facility_data->trauma_designation : "";
                $facility["trauma_designation_definition"] = (isset($facility_data->trauma_designation) && $facility_data->trauma_designation != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->trauma_designation) : "";
                $facility["preferred_specialty"] = (isset($facility_data->preferred_specialty) && $facility_data->preferred_specialty != "") ? strval($facility_data->preferred_specialty) : "";
                $facility["preferred_specialty_definition"] = (isset($facility_data->preferred_specialty) && $facility_data->preferred_specialty != "") ? \App\Providers\AppServiceProvider::keywordTitle($facility_data->preferred_specialty) : "";

                $facility["total_jobs"] = Job::where(['active' => '1', 'facility_id' => $facility_data->id])->count();

                /* rating */
                if ($user_id != "") {
                    $nurse_id = "";
                    $nurse = Nurse::where(['user_id' => $user_id]);
                    if ($nurse->count() > 0) {
                        $nurse_data = $nurse->first();
                        $nurse_id = (isset($nurse_data->id) && $nurse_data->id != "") ? $nurse_data->id : "";
                    }
                    if ($nurse_id != "")
                        $facility_rating_where = ['facility_id' => $facility_data->id, 'nurse_id' => $nurse_id];
                    else $facility_rating_where = ['facility_id' => $facility_data->id];
                } else {
                    $facility_rating_where = ['facility_id' => $facility_data->id];
                }
                $rating_info = FacilityRating::where($facility_rating_where);
                $overall = $on_board = $nurse_team_work = $leadership_support = $tools_todo_my_job = $a = [];
                if ($rating_info->count() > 0) {
                    foreach ($rating_info->get() as $key => $r) {
                        $overall[] = $r->overall;
                        $on_board[] = $r->on_board;
                        $nurse_team_work[] = $r->nurse_team_work;
                        $leadership_support[] = $r->leadership_support;
                        $tools_todo_my_job[] = $r->tools_todo_my_job;
                    }
                }
                $rating['over_all'] = $this->ratingCalculation(count($overall), $overall);
                $rating['on_board'] = $this->ratingCalculation(count($on_board), $on_board);
                $rating['nurse_team_work'] = $this->ratingCalculation(count($nurse_team_work), $nurse_team_work);
                $rating['leadership_support'] = $this->ratingCalculation(count($leadership_support), $leadership_support);
                $rating['tools_todo_my_job'] = $this->ratingCalculation(count($tools_todo_my_job), $tools_todo_my_job);

                /* rating */
                $facility["rating"] = $rating;

                $is_follow = "0";
                if ($user_id != "")
                    $is_follow = FacilityFollows::where(['facility_id' => $facility_data->facility_id, "follow_status" => "1", 'status' => "1", "user_id" => $user_id])->count();

                $facility["is_follow"] = $is_follow;

                $is_like = "0";
                if ($user_id != "")
                    $is_like = FacilityFollows::where(['facility_id' => $facility_data->facility_id, "like_status" => "1", 'status' => "1", "user_id" => $user_id])->count();

                $facility["is_like"] = $is_like;

                $result[] = $facility;
            }
        }
        return $result;
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

    public function jobActive(Request $request)
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
                        if ($off->job->end_date >= date('Y-m-d')) {
                            $o['offer_id'] = $off->id;

                            /* facility info */
                            $o['facility_id'] = $o['facility_logo'] = $o['facility_name'] = "";
                            $facility_info = User::where(['id' => $off->created_by]);
                            if ($facility_info->count() > 0) {
                                $facility = $facility_info->first();
                                $o['facility_id'] = (isset($facility->facilities[0]->id) && $facility->facilities[0]->id != "") ? $facility->facilities[0]->id : "";

                                $o['facility_logo'] = (isset($facility->facilities[0]->facility_logo)) ? url('public/images/facilities/' . $facility->facilities[0]->facility_logo) : "";
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
                $this->message = "Active jobs listed successfully";
                $this->return_data = $my_jobs;
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

    public function settings(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
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
                    $response["first_name"] = (isset($user->first_name) && $user->first_name != "") ? $user->first_name : "";
                    $response["last_name"] = (isset($user->last_name) && $user->last_name != "") ? $user->last_name : "";
                    $response["full_name"] = $user->first_name . " " . $user->last_name;
                    // $response["profile_picture"] = url('storage/assets/nurses/profile/' . $nurse->user->image);
                    $response['profile_picture'] = url('public/images/nurses/profile/'.$user->image);

                    // $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
                    // if ($nurse->user->image) {
                    //     $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $nurse->user->image);
                    //     if ($t) {
                    //         $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $nurse->user->image);
                    //     }
                    // }
                    // $response["profile_picture_base"] = 'data:image/jpeg;base64,' . base64_encode($profileNurse);

                    $response["address"] = (isset($nurse->address) && $nurse->address != "") ? $nurse->address : "";
                    $response["city"] = (isset($nurse->city) && $nurse->city != "") ? $nurse->city : "";
                    $response["state"] = (isset($nurse->state) && $nurse->state != "") ? $nurse->state : "";
                    $response["postcode"] = (isset($nurse->postcode) && $nurse->postcode != "") ? $nurse->postcode : "";
                    $response["country"] = (isset($nurse->country) && $nurse->country != "") ? $nurse->country : "";
                    $response["nursing_license_number"] = (isset($nurse->nursing_license_number) && $nurse->nursing_license_number != "") ? $nurse->nursing_license_number : "";
                    $response["bil_rate"] = (isset($nurse->hourly_pay_rate) && $nurse->hourly_pay_rate != "") ? $nurse->hourly_pay_rate : "5";
                    $exp = (isset($nurse->experience_as_acute_care_facility) && $nurse->experience_as_acute_care_facility != "") ? $nurse->experience_as_acute_care_facility : "0";
                    $non_exp = (isset($nurse->experience_as_ambulatory_care_facility) && $nurse->experience_as_ambulatory_care_facility != "") ? $nurse->experience_as_ambulatory_care_facility : "0";
                    $response["experience"] = strval($exp + $non_exp);
                    /* availability */
                    $availability = Availability::where('nurse_id', $nurse->id);
                    $response["shift"] = $response["shift_definition"] = "";
                    if ($availability->count() > 0) {
                        $preferredShifts = $this->getPreferredShift()->pluck('title', 'id');
                        $avail = $availability->get()->first();
                        $response["shift"] = (isset($avail->preferred_shift) && $avail->preferred_shift != "") ? strval($avail->preferred_shift) : "";
                        $response["shift_definition"] = (isset($preferredShifts[$avail->preferred_shift]) && $preferredShifts[$avail->preferred_shift] != "") ? $preferredShifts[$avail->preferred_shift] : "";
                    }
                    /* availability */
                    $this->check = "1";
                    $this->message = "Nurse info listed successfully";
                } else {
                    $this->message = "Nurse not found";
                }
                $this->return_data =  $response;
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function NurseProfileInfo(Request $request)
    {        
        if(isset($request->role) && $request->role == 'nurse'){
            $validator = \Validator::make($request->all(), [
                'user_id' => 'required',
                'api_key' => 'required',
                'nurse_id' => 'required',
            ]);
        }else{
            $validator = \Validator::make($request->all(), [
                'user_id' => 'required',
                'api_key' => 'required',
            ]);
        }
        
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            if(!empty($request->role) && isset($request->user_id))
            {
                $nurse = NURSE::where('id', $request->nurse_id)->get()->first();
                $user_info = USER::where('id', $request->user_id);
            }else{
                $user_info = USER::where('id', $request->user_id);
                $nurse = NURSE::where('user_id', $request->user_id)->get()->first();
            }
            
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $this->check = "1";
                $this->message = "User profile details listed successfully";
                $type = $nurse->id;
                $this->return_data = $this->nurseProfileCompletionFlagStatus($type, $user);
            } else {
                $this->message = "Nurse not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function nurseProfileCompletionFlagStatus($type, $user)
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
        // $data = [];
        // foreach ($licenseType as $key => $value) {
        //     $data[] = ['id' => strval($key), "name" => $value];
        // }
        
        $nurse = Nurse::where('id', '=', $type)->get()->first();
        if(isset($nurse->id)){
            $offer = DB::select("SELECT status FROM `offers` WHERE nurse_id = '$nurse->id'");
            if(!empty($offer)){
                $offer = $offer[0];
            }
        }
        $availability = Availability::where('nurse_id', '=', $nurse->id)->get()->first();
        /* profile status flag */
        $profile_detail_flag = "0";
        $profile_completion = 0;
        if (
            (isset($user->first_name) && $user->first_name != "") &&
            (isset($user->last_name) && $user->last_name != "") &&
            (isset($user->email) && $user->email != "") &&
            (isset($user->mobile) && $user->mobile != "") &&
            (isset($nurse->nursing_license_state) && $nurse->nursing_license_state != "") &&
            (isset($nurse->nursing_license_number) && $nurse->nursing_license_number != "") &&
            (isset($nurse->specialty) && $nurse->specialty != "") &&
            (isset($availability->work_location) && $availability->work_location != "") &&
            (isset($nurse->address) && $nurse->address != "") &&
            (isset($nurse->city) && $nurse->city != "") &&
            (isset($nurse->license_type) && $nurse->license_type != "" ) &&
            (isset($nurse->state) && $nurse->state != "") &&
            (isset($nurse->postcode) && $nurse->postcode != "") &&
            (isset($nurse->country) && $nurse->country != "")
            
        ) $profile_detail_flag = "1";
        /* profile status flag */

        /* Hourly rate and availability */
        $hourly_rate_and_availability = "0";
        if ((isset($nurse->hourly_pay_rate) && $nurse->hourly_pay_rate != "") &&
            (isset($availability->shift_duration) && $availability->shift_duration != "") &&
            (isset($availability->assignment_duration) && $availability->assignment_duration != "") &&
            (isset($availability->preferred_shift) && $availability->preferred_shift != "") &&
            // (isset($availability->days_of_the_week) && $availability->days_of_the_week != "") &&
            (isset($availability->earliest_start_date) && $availability->earliest_start_date != "")
        ) $hourly_rate_and_availability = "1";
        /* Hourly rate and availability */

        $return_data['id'] = (isset($user->id) && $user->id != "") ? $user->id : "";
        $return_data['nurse_id'] = (isset($nurse->id) && $nurse->id != "") ? $nurse->id : "";
        $return_data['offer_status'] = (isset($offer->status) && $offer->status != "") ? $offer->status : "";
        $return_data['role'] = (isset($user->role) && $user->role != "") ? $user->role : "";
        $return_data['fcm_token'] = (isset($user->fcm_token) && $user->fcm_token != "") ? $user->fcm_token : "";
        $return_data['fullName'] = (isset($user->fullName) && $user->fullName != "") ? $user->fullName : "";
        $return_data['date_of_birth'] = (isset($user->date_of_birth) && $user->date_of_birth != "") ? $user->date_of_birth : "";
        $return_data['driving_license'] = (isset($user->driving_license) && $user->driving_license != "") ? $user->driving_license : "";
        $return_data['security_number'] = (isset($user->security_number) && $user->security_number != "") ? $user->security_number : "";
        $return_data['email_notification'] = (isset($user->email_notification) && $user->email_notification != "") ? strval($user->email_notification) : "";
        $return_data['sms_notification'] = (isset($user->sms_notification) && $user->sms_notification != "") ? strval($user->sms_notification) : "";

        $return_data['first_name'] = (isset($user->first_name) && $user->first_name != "") ? $user->first_name : "";
        $return_data['last_name'] = (isset($user->last_name) && $user->last_name != "") ? $user->last_name : "";
        $return_data['email'] = (isset($user->email) && $user->email != "") ? $user->email : "";

        $return_data['image'] = (isset($user->image) && $user->image != "") ? url("public/images/nurses/profile/" . $user->image) : "";

        $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
        if (isset($user->image)) {
            $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $user->image);
            if ($t) {
                $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $user->image);
            }
        }
        // $return_data["image_base"] = 'data:image/jpeg;base64,' . base64_encode($profileNurse);

        $return_data['mobile'] = (isset($user->mobile) && $user->mobile != "") ? $user->mobile : "";
        $return_data['nursing_license_state'] = (isset($nurse->nursing_license_state) && $nurse->nursing_license_state != "") ? $nurse->nursing_license_state : "";
        $return_data['nursing_license_number'] = (isset($nurse->nursing_license_number) && $nurse->nursing_license_number != "") ? $nurse->nursing_license_number : "";
        $return_data['authority_Issue'] = (isset($nurse->authority_Issue) && $nurse->authority_Issue != "") ? $nurse->authority_Issue : "";
        $return_data['highest_nursing_degree'] = (isset($nurse->highest_nursing_degree) && $nurse->highest_nursing_degree != "") ? $nurse->highest_nursing_degree : "";
        $return_data['highest_nursing_degree_definition'] = (isset($nurse->highest_nursing_degree) && $nurse->highest_nursing_degree != "") ? \App\Providers\AppServiceProvider::keywordTitle($nurse->highest_nursing_degree) : "";
        $return_data['specialty'] = $spl = [];
        if (isset($nurse->specialty) && $nurse->specialty != "") {
            $specialty_array = explode(",", $nurse->specialty);
            if (is_array($specialty_array)) {
                foreach ($specialty_array as $key => $spl_id) {
                    $spl_name = (isset($specialties[$spl_id])) ? $specialties[$spl_id] : "";
                    $spl[] = ['id' => $spl_id, 'name' => $spl_name];
                }
            }
            $return_data['specialty'] = $spl;
        }
        $return_data['work_location'] = (isset($availability->work_location) && $availability->work_location != "") ? strval($availability->work_location) : "";
        // $return_data['work_location_definition'] = isset($workLocations[strval($availability->work_location)]) ? $workLocations[strval($availability->work_location)] : "";
        $return_data['address'] = (isset($nurse->address) && $nurse->address != "") ? $nurse->address : "";

        $return_data['search_status'] = (isset($nurse->search_status) && $nurse->search_status != "") ? strval($nurse->search_status) : "";
        // $return_data['search_status_definition'] = (isset($nurse->search_status) && $nurse->search_status != "") ? \App\Providers\AppServiceProvider::keywordTitle($nurse->search_status) : "";
        $return_data['license_type'] = (isset($nurse->license_type) && $nurse->license_type != "") ? strval($nurse->license_type) : "";
        // $return_data['license_type_definition'] = (isset($nurse->license_type) && $nurse->license_type != "") ? \App\Providers\AppServiceProvider::keywordTitle($nurse->license_type) : "";
        $return_data['license_status'] = (isset($nurse->license_status) && $nurse->license_status != "") ? strval($nurse->license_status) : "";
        // $return_data['license_status_definition'] = (isset($nurse->license_status) && $nurse->license_status != "") ? \App\Providers\AppServiceProvider::keywordTitle($nurse->license_status) : "";
        $return_data['license_expiry_date'] = (isset($nurse->license_expiry_date) && $nurse->license_expiry_date != "") ? strval($nurse->license_expiry_date) : "";
        $return_data['license_issue_date'] = (isset($nurse->license_issue_date) && $nurse->license_issue_date != "") ? strval($nurse->license_issue_date) : "";
        $return_data['license_renewal_date'] = (isset($nurse->license_renewal_date) && $nurse->license_renewal_date != "") ? strval($nurse->license_renewal_date) : "";

        $return_data['city'] = (isset($nurse->city) && $nurse->city != "") ? $nurse->city : "";
        $return_data['state'] = (isset($nurse->state) && $nurse->state != "") ? $nurse->state : "";
        $return_data['postcode'] = (isset($nurse->postcode) && $nurse->postcode != "") ? $nurse->postcode : "";
        $return_data['country'] = (isset($nurse->country) && $nurse->country != "") ? $nurse->country : "";
        $return_data['hourly_pay_rate'] = (isset($nurse->hourly_pay_rate) && $nurse->hourly_pay_rate != "") ? strval($nurse->hourly_pay_rate) : "";
        $return_data['shift_duration'] = (isset($availability->shift_duration) && $availability->shift_duration != "") ? strval($availability->shift_duration) : "";
        // $return_data['shift_duration_definition'] = (isset($shifts[$availability->shift_duration]) && $shifts[$availability->shift_duration] != "") ? $shifts[strval($availability->shift_duration)] : "";
        $return_data['assignment_duration'] = (isset($availability->assignment_duration) && $availability->assignment_duration != "") ? strval($availability->shift_duration) : "";
        // $return_data['assignment_duration_definition'] = (isset($assignmentDurations[$availability->assignment_duration]) && $assignmentDurations[$availability->assignment_duration] != "") ? $assignmentDurations[strval($availability->assignment_duration)] : "";
        $return_data['preferred_shift'] = (isset($availability->preferred_shift) && $availability->preferred_shift != "") ? strval($availability->preferred_shift) : "";
        // $return_data['preferred_shift_definition'] = (isset($preferredShifts[$availability->preferred_shift]) &&  $preferredShifts[$availability->preferred_shift] != "") ?  $preferredShifts[$availability->preferred_shift] : "";
        // $return_data['days_of_the_week'] = [];
        // if ($availability->days_of_the_week != "") $return_data['days_of_the_week'] = explode(",", $availability->days_of_the_week);
        // $return_data['earliest_start_date'] = (isset($availability->earliest_start_date) && $availability->earliest_start_date != "") ? date('m/d/Y', strtotime($availability->earliest_start_date)) : "";

        $return_data['profile_detail_flag'] = $profile_detail_flag;
        $return_data['hourly_rate_and_avail_flag'] = $hourly_rate_and_availability;

        // Education details
        // $return_data['college_uni_name'] = (isset($nurse->college_uni_name) && $nurse->college_uni_name != "") ? $nurse->college_uni_name : "";
        // $return_data['study_area'] = (isset($nurse->study_area) && $nurse->study_area != "") ? $nurse->study_area : "";
        // $return_data['graduation_date'] = (isset($nurse->graduation_date) && $nurse->graduation_date != "") ? $nurse->graduation_date : "";

        // $return_data['unavailable_dates'] = array();
        // if($availability->unavailable_dates){
        //     $return_data['unavailable_dates'] = explode(',',$availability->unavailable_dates);
        // }

        $experience = [];
        $exp = Experience::where(['nurse_id' => $nurse->id])->whereNull('deleted_at')->get();
        if ($exp->count() > 0) {
            $e = $exp;
            foreach ($e as $key => $v) {
                $crt_data['experience_id'] = (isset($v->id) && $v->id != "") ? $v->id : "";
                $crt_data['type'] = (isset($v->type) && $v->type != "") ? $v->type : "";
                $crt_data['type_definition'] = (isset($certifications[$v->type]) && $certifications[$v->type] != "") ? $certifications[$v->type] : "";
                $crt_data['position_title'] = (isset($v->position_title) && $v->position_title != "") ? $v->position_title : "";
                $crt_data['unit'] = (isset($v->unit) && $v->unit != "") ? $v->unit : "";
                $crt_data['start_date'] = (isset($v->start_date) && $v->start_date != "") ? date('m/d/Y', strtotime($v->start_date)) : "";
                $crt_data['end_date'] = (isset($v->end_date) && $v->end_date != "") ? date('m/d/Y', strtotime($v->end_date)) : "";
                $crt_data['is_current_job'] = (isset($v->is_current_job) && $v->is_current_job != "") ? $v->is_current_job : "";
                $crt_data["experience_as_acute_care_facility"] = (isset($nurse->experience_as_acute_care_facility) && $nurse->experience_as_acute_care_facility != "") ? $nurse->experience_as_acute_care_facility : "";
                $crt_data["experience_as_ambulatory_care_facility"] = (isset($nurse->experience_as_ambulatory_care_facility) && $nurse->experience_as_ambulatory_care_facility != "") ? $nurse->experience_as_ambulatory_care_facility : "";
                $exp_acute_care = isset($nurse->experience_as_acute_care_facility)? $nurse->experience_as_acute_care_facility : '0';
                $exp_ambulatory_care = isset($nurse->experience_as_ambulatory_care_facility)? $nurse->experience_as_ambulatory_care_facility : '0';
                $crt_data['total_experience'] = $exp_acute_care+$exp_ambulatory_care;
                $crt_data['total_experience'] = (int)$crt_data['total_experience'];
                $experience[] = $crt_data;
        
            }
        }
        $return_data['experience'] = $experience;
        /* experience */

        /* certitficate */
        $certitficate = [];
        $cert = Certification::where(['nurse_id' => $nurse->id])->whereNull('deleted_at')->get();
        if ($cert->count() > 0) {
            $c = $cert;
            foreach ($c as $key => $v) {
                // if ($v->deleted_at != "") {
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
                // $crt_data['certificate_image_base'] = ($certificate_image_base != "") ? 'data:image/jpeg;base64,' . base64_encode($certificate_image_base) : "";


                // $crt_data['active'] = (isset($v->active) && $v->active != "") ? $v->active : "";
                // $crt_data['deleted_at'] = (isset($v->deleted_at) && $v->deleted_at != "") ? $v->deleted_at : "";
                   $crt_data['created_at'] = (isset($v->created_at) && $v->created_at != "") ? $v->created_at : "";
                    // $crt_data['updated_at'] = (isset($v->updated_at) && $v->updated_at != "") ? $v->updated_at : ""; 
                $certitficate[] = $crt_data;
                // }
            }
        }
        $return_data['certitficate'] = $certitficate;
        $return_data['resume'] = (isset($nurse->resume) && $nurse->resume != "") ? url('storage/assets/nurses/resumes/' . $nurse->id . '/' . $nurse->resume) : "";
        /* certitficate */


        /* role interest */
        $optyesno = ['1' => "Yes", '0' => "No"];

        $role_interest['clinical_educator'] = (isset($nurse->clinical_educator)) ? strval($nurse->clinical_educator) : "";
        // $role_interest['clinical_educator_definition'] = (isset($optyesno[$nurse->clinical_educator]) && $optyesno[$nurse->clinical_educator] != "") ? $optyesno[$nurse->clinical_educator] : "";
        $role_interest['is_daisy_award_winner'] = (isset($nurse->is_daisy_award_winner)) ? strval($nurse->is_daisy_award_winner) : "";
        // $role_interest['is_daisy_award_winner_definition'] = (isset($optyesno[$nurse->is_daisy_award_winner]) && $optyesno[$nurse->is_daisy_award_winner] != "") ? $optyesno[$nurse->is_daisy_award_winner] : "";
        $role_interest['languages'] = (isset($nurse->languages) && $nurse->languages != "") ? explode(",", $nurse->languages) : "";

        /* nurse assets */
        $role_interest['additional_pictures'] = $role_interest['additional_files'] = [];
        $nurse_assets = NurseAsset::where(['nurse_id' => $nurse->id, 'active' => '1'])->get();

        if ($nurse_assets->count() > 0) {
            foreach ($nurse_assets as $nac_ => $na) {
                if ($na->filter == "additional_photos") $role_interest['additional_pictures'][] = ['asset_id' => $na->id, 'photo' => url('storage/assets/nurses/additional_photos/' . $nurse->id . '/' . $na->name)];
                else $role_interest['additional_files'][] = ['asset_id' => $na->id, 'photo' => url('storage/assets/nurses/additional_files/' . $nurse->id . '/' . $na->name)];
            }
        }
        /* nurse assets */
        $return_data['role_interest'] = $role_interest;
        /* role interest */
        
        // new check
        if (
            (isset($user->date_of_birth) && $user->date_of_birth != "") &&
            (isset($user->driving_license) && $user->driving_license != "") &&
            (isset($user->security_number) && $user->security_number != "")
        ){
            $profile_completion++;
            $return_data['profile_details'] = 'true';
        }else{
            $return_data['profile_details'] = 'false';
        } 
        // end new check

        if(!empty($return_data['highest_nursing_degree'])){
            $profile_completion++;
            $return_data['qualification_details'] = 'true';
        }else{
            $return_data['qualification_details'] = 'false';
        }

        if(!empty($return_data['nursing_license_number'])){
            $profile_completion++;
            $return_data['license_details'] = 'true';
        }else{
            $return_data['license_details'] = 'false';
        }

        if(!empty($return_data['resume'])){
            $profile_completion++;
            $return_data['resume_details'] = 'true';
        }else{
            $return_data['resume_details'] = 'false';
        }

        if(!empty($return_data['certitficate'])){
            $profile_completion++;
            $return_data['certificate_details'] = 'true';
        }else{
            $return_data['certificate_details'] = 'false';
        }

        if(!empty($return_data['experience'])){
            $profile_completion++;
            $return_data['experience_details'] = 'true';
        } else{
            $return_data['experience_details'] = 'false';
        }

        if($profile_completion ==  6){
            $return_data['isUserProfile'] = 'true';
        }else{
            $return_data['isUserProfile'] = 'false';
        }
        $return_data['worker_goodwork_number'] = (isset($nurse->worker_goodwork_number) && $nurse->worker_goodwork_number != "") ? $nurse->worker_goodwork_number : "";
        $return_data['profile_completion'] = $profile_completion;
        // end profile completion
        return $return_data;
    }

    public function workerExperience(Request $request)
    {
        $certifications = $this->getCertifications()->pluck('title', 'id');
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'worker_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            
            $worker = NURSE::where('id', $request->worker_id)->get()->first();
            $user = USER::where('id', $worker->user_id)->get()->first();
            if(isset($worker))
            {
                $experience = [];
                $exp = Experience::where(['nurse_id' => $request->worker_id])->whereNull('deleted_at')->get();
                if ($exp->count() > 0) {
                    $e = $exp;
                    foreach ($e as $key => $v) {
                        $crt_data['experience_id'] = (isset($v->id) && $v->id != "") ? $v->id : "";
                        $crt_data['organization_name'] = (isset($v->organization_name) && $v->organization_name != "") ? $v->organization_name : "";
                        $crt_data['organization_department_name'] = (isset($v->organization_department_name) && $v->organization_department_name != "") ? $v->organization_department_name : "";
                        $crt_data['position_title'] = (isset($v->position_title) && $v->position_title != "") ? $v->position_title : "";
                        $crt_data['exp_city'] = (isset($v->exp_city) && $v->exp_city != "") ? $v->exp_city : "";
                        $crt_data['facility_type'] = (isset($v->facility_type) && $v->facility_type != "") ? $v->facility_type : "";
                        $crt_data['type'] = (isset($v->type) && $v->type != "") ? $v->type : "";
                        
                        $crt_data['type_definition'] = (isset($certifications[$v->type]) && $certifications[$v->type] != "") ? $certifications[$v->type] : "";
                        $crt_data['position_title'] = (isset($v->position_title) && $v->position_title != "") ? $v->position_title : "";
                        $crt_data['unit'] = (isset($v->unit) && $v->unit != "") ? $v->unit : "";
                        $crt_data['start_date'] = (isset($v->start_date) && $v->start_date != "") ? date('m/d/Y', strtotime($v->start_date)) : "";
                        $crt_data['end_date'] = (isset($v->end_date) && $v->end_date != "") ? date('m/d/Y', strtotime($v->end_date)) : "";
                        $crt_data['is_current_job'] = (isset($v->is_current_job) && $v->is_current_job != "") ? $v->is_current_job : "";
                        
                        $experience[] = $crt_data;
        
                    }
                }
                
                $this->check = "1";
                $this->message = "Worker exprience details listed successfully";
                $this->return_data = $experience;
            }else{
                $this->check = "1";
                $this->message = "Worker not found";
                $this->return_data = [];
            }
            
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function destroyRoleInterestDocument(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            "asset_id" => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse_info = NURSE::where('user_id', $request->user_id)->get();
            if ($nurse_info->count() > 0) {
                $nurse = $nurse_info->first();
                $nurse_assets = NurseAsset::where(['id' => $request->asset_id])->get();
                if ($nurse_assets->count() > 0) {
                    $nurseAsset = $nurse_assets->first();
                    $t = Storage::exists('assets/nurses/' . $nurseAsset->filter . '/' . $nurse->id . '/' . $nurseAsset->name);
                    if ($t && $nurseAsset->name) {
                        Storage::delete('assets/nurses/' . $nurseAsset->filter . '/' . $nurse->id . '/' . $nurseAsset->name);
                    }
                    $delete = $nurseAsset->delete();
                    if ($delete) {
                        $this->check = "1";
                        $this->message = "Document removed successfully";
                    } else {
                        $this->message = "Failed to remove document, Please try again later";
                    }
                } else {
                    $this->message = "Document already removed/not found";
                }
            } else {
                $this->message = "Nurse not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
    
    public function termsAndConditions()
    {
        $this->message = "Terms and Conditions";
        $this->check = "1";
        $this->return_data = '<p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site.</p><p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p> <p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p>';
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

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

    public function newPhoneNumber(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'phone_number' => 'required|regex:/^[0-9 \+]+$/|min:4|max:20',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $otp = substr(str_shuffle("0123456789"), 0, 4);
                $rand_enc = substr(str_shuffle("0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz"), 0, 6);
                $update_otp = USER::where(['id' => $user->id])->update(['otp' => $otp, 'new_mobile' => $request->phone_number]);
                if ($update_otp) {
                    $this->check = "1";
                    $this->message = "OTP send successfully to this number";
                    $this->return_data = ['otp' => $otp];
                } else {
                    $this->message = "Failed to send otp, Please try again later";
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function sendOtp(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            // 'phone_number' => 'required|regex:/^[0-9 \+]+$/|min:4|max:20',
            'id' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('email', $request->id)->orWhere('mobile',$request->id);
            // $user_info = USER::where('email', $request->id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                if($user->role == strtoupper($request->role)){
                    $otp = substr(str_shuffle("0123456789"), 0, 4);
                    $rand_enc = substr(str_shuffle("0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz"), 0, 6);
                    $update_otp = USER::where(['id' => $user->id])->update(['otp' => $otp]);
                    if ($update_otp) {
                        $message = "Hi <b>".$user->first_name.' '.$user->last_name."</b>,\r\n<br><br>".
                                    "OTP: <b>" . $otp."</b> is your one time password for sign in\r\n<br><br>".
                                    "Thank you,\r\n<br>".
                                    "Team Goodwork\r\n<br><br>".
                                    "<b>Please do not reply to this email. </b>\r\n<br><br>".
                                    "<b>© 2023 GOODWORK. ALL RIGHTS RESERVED.</b><br>";

                        $from_user = "=?UTF-8?B?" . base64_encode('Goodwork') . "?=";
                        $subject = "=?UTF-8?B?" . base64_encode('One Time Password for login') . "?=";
                        $user_mail    =  env("MAIL_USERNAME");
                        
                        // $headers = "From: $from_user <team@goodwork.com>\r\n" .
                        $headers = "From: $from_user <$user_mail>\r\n" .
                            "MIME-Version: 1.0" . "\r\n" .
                            "Content-type: text/html; charset=UTF-8" . "\r\n";
    
                        mail($user->email, $subject, $message, $headers);
    
                        $this->check = "1";
                        if($user->mobile == $request->id){
                            $this->message = "OTP send successfully to your number";
                        }else{
                            $this->message = "OTP send successfully to your email";
                        }
                        $this->return_data = ['user_id' => $user->id,'otp' => $otp];
                    } else {
                        $this->message = "Failed to send otp, Please try again later";
                    }
                }else{
                    $this->message = "Role does not match with your user id! Please check";
                }
                
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    

    }

    public function mobileOtp(Request $request)  
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'id' => 'required',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('email', $request->id)->orWhere('mobile',$request->id);
            
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                if($user->role == strtoupper($request->role)){
                    $otp = substr(str_shuffle("0123456789"), 0, 4);
                    $rand_enc = substr(str_shuffle("0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz"), 0, 6);
                    $update_otp = USER::where(['id' => $user->id])->update(['otp' => $otp]);
                    if ($update_otp) {
                        $message = "OTP: " . $otp;
                        $sid    =  env("TWILIO_SID");
                        $token  =  env("TWILIO_AUTH_TOKEN");
                        $twilio = new Client($sid, $token);
                        $message = $twilio->messages
                        ->create($request->id, // to
                            array(
                            "from" => env("TWILIO_NUMBER"),
                            "body" => 'Your Account verification code is: '.$otp
                            )
                        );
                
                        $this->check = "1";
                        if($user->mobile == $request->id){
                            $this->message = "OTP send successfully to your number";
                        }else{
                            $this->message = "OTP send successfully to your email";
                        }
                        $this->return_data = ['user_id' => $user->id,'otp' => $otp];
                    } else {
                        $this->message = "Failed to send otp, Please try again later";
                    }
                }else{
                    $this->message = "Role does not match with your user id! Please check";
                }
                
            } else {
                $this->message = "User not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function confirmOTP(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'user_id' => 'required',
            'otp' => 'required|min:4|max:4',
            // 'fcm_token' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $return_data = [];
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                if(isset($user->otp) && !empty($user->otp)){
                    
                    if ($user->otp == $request->otp) {
                        $update_otp = USER::where(['id' => $user->id])->update(['otp' => NULL, 'fcm_token' => $request->fcm_token, 'email_verified_at' => date('Y-m-d H:i:s')]);
                        if ($update_otp) {
    
                            if (isset($user->role) && $user->role == "NURSE") {
                                $return_data = $this->profileCompletionFlagStatus($type = "login", $user);
                                // $return_data = $this->profileCompletionFlagStatus($type = "login", $user);
                            } else {
                                $return_data['facility_details'] = $this->facilityProfileCompletionFlagStatus($type = "login", $user);
                                $return_data['user_details'] = $user;
                            }
                            
                            $this->check = "1";
                            $this->message = "Logged in successfully";
                        } else {
                            $this->check = "1";
                            $this->message = "User not found";
                        }
                    } else {
                        $this->check = "0";
                        $this->message = "Invalid OTP, Please enter the correct otp";
                        $return_data['user_details'] = $request->otp;
                    }
                }else{
                    $this->check = "0";
                    $this->message = "OTP not found";
                }
            } else {
                $this->check = "1";
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $return_data], 200);
    }

    public function getCountries()
    {

        $controller = new controller();
        $countries = $controller->getCountries()->pluck('name', 'id');
        $data = [];
        foreach ($countries as $key => $value) {
            $data[] = ['country_id' => strval($key), "name" => $value];
        }
        // moved usa and canada to top of the row
        $this->moveElement($data, 235, 0);
        $this->moveElement($data, 40, 1);
        // moved usa and canada to top of the row
        $this->check = "1";
        $this->message = "Countries listed successfully";
        $this->return_data = $data;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    function moveElement(&$array, $a, $b)
    {
        $out = array_splice($array, $a, 1);
        array_splice($array, $b, 0, $out);
    }

    public function getStates(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'country_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $get_states = States::where(['country_id' => $request->country_id])->get();
            if ($get_states->count() > 0) {
                $states = $get_states;
                $data = [];
                foreach ($states as $key => $value) {
                    $data[] = ['state_id' => strval($value->id), "name" => $value->name, 'iso_name' => $value->iso2];
                }
                $this->check = "1";
                $this->message = "States listed successfully";
                $this->return_data = $data;
            } else {
                $this->return_data = [];
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getCities(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'state_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $get_cities = Cities::where(['state_id' => $request->state_id])->get();
            if ($get_cities->count() > 0) {
                $cities = $get_cities;
                $data = [];
                foreach ($cities as $key => $value) {
                    $data[] = ['city_id' => strval($value->id), "name" => $value->name];
                }
                $this->check = "1";
                $this->message = "Cities listed successfully";
                $this->return_data = $data;
            } else {
                $this->return_data = [];
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function facilityProfileCompletionFlagStatus($type = "", $user)
    {
        $facility = $user->facilities()->get()->first();

        $states = $this->getStateOptions();
        $facilityTypes = $this->getFacilityType()->pluck('title', 'id');
        
        $eMedicalRecords = $this->getEMedicalRecords()->pluck('title', 'id');
        $eMedicalRecords['0'] = 'Other';

        $bCheckProviders = $this->getBCheckProvider()->pluck('title', 'id');
        $bCheckProviders['0'] = 'Other';

        $nCredentialingSoftwares = $this->getNCredentialingSoftware()->pluck('title', 'id');
        $nCredentialingSoftwares['0'] = 'Other';

        $nSchedulingSystems = $this->getNSchedulingSystem()->pluck('title', 'id');
        $nSchedulingSystems['0'] = 'Other';

        $timeAttendanceSystems = $this->getTimeAttendanceSystem()->pluck('title', 'id');
        $timeAttendanceSystems['0'] = 'Other';

        $traumaDesignations = $this->getTraumaDesignation()->pluck('title', 'id');
        $traumaDesignations['0'] = 'N/A';

        // $facility_info["facility_id"] = (isset($facility->id) && $facility->id != "") ? $facility->id : "";
        // $facility_info["created_by"] = (isset($facility->created_by) && $facility->created_by != "") ? $facility->created_by : "";
        $facility_info["role"] = "FACILITYADMIN";
        $facility_info["user_id"] = (isset($facility->pivot_user_id) && $facility->pivot_user_id != "") ? $facility->pivot_user_id : "";
        $facility_info["facility_id"] = (isset($facility->pivot_facility_id) && $facility->pivot_facility_id != "") ? $facility->pivot_facility_id : "";
        // $facility_info["user_id"] = (isset($facility->pivot->user_id) && $facility->pivot->user_id != "") ? $facility->pivot->user_id : "";
        // $facility_info["facility_id"] = (isset($facility->pivot->facility_id) && $facility->pivot->facility_id != "") ? $facility->pivot->facility_id : "";
        $facility_info["facility_name"] = (isset($facility->name) && $facility->name != "") ? $facility->name : "";
        $facility_info["facility_address"] = (isset($facility->address) && $facility->address != "") ? $facility->address : "";
        $facility_info["facility_city"] = (isset($facility->city) && $facility->city != "") ? $facility->city : "";
        $facility_info["facility_state"] = (isset($facility->state) && $facility->state != "") ? $facility->state : "";
        $facility_info["facility_postcode"] = (isset($facility->postcode) && $facility->postcode != "") ? $facility->postcode : "";

        $facility_info["facility_logo"] = (isset($facility->facility_logo) && $facility->facility_logo != "") ? url('public/images/facilities/' . $facility->facility_logo) : "";
        // $facility_logo = "";
        // if ($facility->facility_logo) {
        //     $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $facility->facility_logo);
        //     if ($t) {
        //         $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $facility->facility_logo);
        //     }
        // }
        // $facility_info["facility_logo_base"] = ($facility_logo != "") ? 'data:image/jpeg;base64,' . base64_encode($facility_logo) : "";
        
        $facility_info["facility_email"] = (isset($facility->facility_email) && $facility->facility_email != "") ? $facility->facility_email : "";
        $facility_info["facility_phone"] = (isset($facility->facility_phone) && $facility->facility_phone != "") ? $facility->facility_phone : "";
        $facility_info["specialty_need"] = (isset($facility->specialty_need) && $facility->specialty_need != "") ? $facility->specialty_need : "";
        
        $facility_info["facility_type"] = (isset($facility->type) && $facility->type != "") ? strval($facility->type) : "";
        if(isset($facility) && !empty($facility)){
            $facility_info["facility_type_definition"] = (isset($facilityTypes[$facility->type]) && $facilityTypes[$facility->type] != "") ? $facilityTypes[$facility->type] : "";
        }else{
            $facility_info["facility_type_definition"] = "";
        }
        
        // $facility_info["active"] = (isset($facility->active) && $facility->active != "") ? $facility->active : "";
        // $facility_info["deleted_at"] = (isset($facility->deleted_at) && $facility->deleted_at != "") ? $facility->deleted_at : "";
        // $facility_info["created_at"] = (isset($facility->created_at) && $facility->created_at != "") ? $facility->created_at : "";
        // $facility_info["updated_at"] = (isset($facility->updated_at) && $facility->updated_at != "") ? $facility->updated_at : "";

        $facility_info["slug"] = (isset($facility->slug) && $facility->slug != "") ? $facility->slug : "";
        $facility_info["cno_message"] = (isset($facility->cno_message) && $facility->cno_message != "") ? $facility->cno_message : "";
        $facility_info["cno_image"] = (isset($facility->cno_image) && $facility->cno_image != "") ? url('public/images/facilities/cno_image/' . $facility->cno_image) : "";

        // $cno_image = "";
        // if ($facility->cno_image) {
        //     $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/cno_image/' . $facility->cno_image);
        //     if ($t) {
        //         $cno_image = \Illuminate\Support\Facades\Storage::get('assets/facilities/cno_image/' . $facility->cno_image);
        //     }
        // }
        // $facility_info["cno_image_base"] = ($cno_image != "") ? 'data:image/jpeg;base64,' . base64_encode($cno_image) : "";

        $facility_info["gallary_images"] = (isset($facility->gallary_images) && $facility->gallary_images != "") ? $facility->gallary_images : "";

        $facility_info["video"] = (isset($facility->video) && $facility->video != "") ? $facility->video : "";

        $facility_info["about_facility"] = (isset($facility->about_facility) && $facility->about_facility != "") ? $facility->about_facility : "";
        $facility_info["facility_website"] = (isset($facility->facility_website) && $facility->facility_website != "") ? $facility->facility_website : "";
        $facility_info["video_embed_url"] = (isset($facility->video_embed_url) && $facility->video_embed_url != "") ? $facility->video_embed_url : "";
        // $facility_info["f_lat"] = (isset($facility->f_lat) && $facility->f_lat != "") ? $facility->f_lat : "";
        // $facility_info["f_lang"] = (isset($facility->f_lang) && $facility->f_lang != "") ? $facility->f_lang : "";

        /* facility_emr */
        $facility_info["facility_emr"] = (isset($facility->f_emr) && $facility->f_emr != "") ? strval($facility->f_emr) : "";
        
        if(isset($facility) && !empty($facility)){
            $facility_info["facility_emr_definition"] = (isset($eMedicalRecords[$facility->f_emr]) && $eMedicalRecords[$facility->f_emr] != "") ? $eMedicalRecords[$facility->f_emr] : "";
        }else{
            $facility_info["facility_emr_definition"] = "";
        }
        $facility_info["facility_emr_other"] = (($facility_info["facility_emr"] == "other" || $facility_info["facility_emr"] == "0") && ((isset($facility->f_emr_other) && $facility->f_emr_other != ""))) ? $facility->f_emr_other : "";
        /* facility_emr */

        /* facility_bcheck_provider */
        $facility_info["facility_bcheck_provider"] = (isset($facility->f_bcheck_provider) && $facility->f_bcheck_provider != "") ? strval($facility->f_bcheck_provider) : "";
        
        if(isset($facility) && !empty($facility)){
            $facility_info["facility_bcheck_provider_definition"] = (isset($bCheckProviders[$facility->f_bcheck_provider]) && $bCheckProviders[$facility->f_bcheck_provider] != "") ? $bCheckProviders[$facility->f_bcheck_provider] : "";
        }else{
            $facility_info["facility_bcheck_provider_definition"] = "";
        }
        $facility_info["facility_bcheck_provider_other"] = (($facility_info["facility_bcheck_provider"] == "other" || $facility_info["facility_bcheck_provider"] == "0") && ((isset($facility->f_bcheck_provider_other) && $facility->f_bcheck_provider_other != ""))) ? $facility->f_bcheck_provider_other : "";
        /* facility_bcheck_provider */

        /* nurse_cred_soft */
        $facility_info["nurse_cred_soft"] = (isset($facility->nurse_cred_soft) && $facility->nurse_cred_soft != "") ? strval($facility->nurse_cred_soft) : "";
        
        if(isset($facility) && !empty($facility)){
            $facility_info["nurse_cred_soft_definition"] = (isset($nCredentialingSoftwares[$facility->nurse_cred_soft]) && $nCredentialingSoftwares[$facility->nurse_cred_soft] != "") ? $nCredentialingSoftwares[$facility->nurse_cred_soft] : "";
        }else{
            $facility_info["nurse_cred_soft_definition"] = "";
        }
        $facility_info["nurse_cred_soft_other"] = (($facility_info["nurse_cred_soft"] == "other" || $facility_info["nurse_cred_soft"] == "0") && ((isset($facility->nurse_cred_soft_other) && $facility->nurse_cred_soft_other != ""))) ? $facility->nurse_cred_soft_other : "";
        /* nurse_cred_soft */

        /* nurse_scheduling_sys */
        $facility_info["nurse_scheduling_sys"] = (isset($facility->nurse_scheduling_sys) && $facility->nurse_scheduling_sys != "") ? strval($facility->nurse_scheduling_sys) : "";
        
        if(isset($facility) && !empty($facility)){
            $facility_info["nurse_scheduling_sys_definition"] = (isset($nSchedulingSystems[$facility->nurse_scheduling_sys]) && $nSchedulingSystems[$facility->nurse_scheduling_sys] != "") ? $nSchedulingSystems[$facility->nurse_scheduling_sys] : "";
        }else{
            $facility_info["nurse_scheduling_sys_definition"] = "";
        }
        $facility_info["nurse_scheduling_sys_other"] = (($facility_info["nurse_scheduling_sys"] == "other" || $facility_info["nurse_scheduling_sys"] == "0") && ((isset($facility->nurse_scheduling_sys_other) && $facility->nurse_scheduling_sys_other != ""))) ? $facility->nurse_scheduling_sys_other : "";
        /* nurse_scheduling_sys */

        /* time_attend_sys */
        $facility_info["time_attend_sys"] = (isset($facility->time_attend_sys) && $facility->time_attend_sys != "") ? strval($facility->time_attend_sys) : "";
        
        if(isset($facility) && !empty($facility)){
            $facility_info["time_attend_sys_definition"] = (isset($timeAttendanceSystems[$facility->time_attend_sys]) &&  $timeAttendanceSystems[$facility->time_attend_sys] != "") ?  $timeAttendanceSystems[$facility->time_attend_sys] : "";
        }else{
            $facility_info["time_attend_sys_definition"] = "";
        }
        $facility_info["time_attend_sys_other"] = (($facility_info["time_attend_sys"] == "other" || $facility_info["time_attend_sys"] == "0") && ((isset($facility->time_attend_sys_other) && $facility->time_attend_sys_other != ""))) ? $facility->time_attend_sys_other : "";
        /* time_attend_sys */

        $facility_info["licensed_beds"] = (isset($facility->licensed_beds) && $facility->licensed_beds != "") ? $facility->licensed_beds : "";

        /* trauma_designation */
        $facility_info["trauma_designation"] = (isset($facility->trauma_designation) && $facility->trauma_designation != "") ? strval($facility->trauma_designation) : "";
        
        if(isset($facility) && !empty($facility)){
            $facility_info["trauma_designation_definition"] = (isset($traumaDesignations[$facility->trauma_designation]) && $traumaDesignations[$facility->trauma_designation] != "") ? $traumaDesignations[$facility->trauma_designation] : "";
        }else{
            $facility_info["trauma_designation_definition"] = "";
        }
        /* trauma_designation */

        /* social logins */
        $facility_social = [];
        $facility_social["facebook"] = (isset($facility->facebook) && $facility->facebook != "") ? $facility->facebook : "";
        $facility_social["twitter"] = (isset($facility->twitter) && $facility->twitter != "") ? $facility->twitter : "";
        $facility_social["linkedin"] = (isset($facility->linkedin) && $facility->linkedin != "") ? $facility->linkedin : "";
        $facility_social["instagram"] = (isset($facility->instagram) && $facility->instagram != "") ? $facility->instagram : "";
        $facility_social["pinterest"] = (isset($facility->pinterest) && $facility->pinterest != "") ? $facility->pinterest : "";
        $facility_social["tiktok"] = (isset($facility->tiktok) && $facility->tiktok != "") ? $facility->tiktok : "";
        $facility_social["sanpchat"] = (isset($facility->sanpchat) && $facility->sanpchat != "") ? $facility->sanpchat : "";
        $facility_social["youtube"] = (isset($facility->youtube) && $facility->youtube != "") ? $facility->youtube : "";
        $facility_info['facility_social'] = $facility_social;
        /* social logins */

        $facility_info['facility_profile_flag'] = "0";
        if (
            $facility_info["facility_name"] != "" &&
            $facility_info["facility_type"] != "" &&
            $facility_info["facility_phone"] != "" &&
            $facility_info["facility_address"] != "" &&
            $facility_info["facility_city"] != "" &&
            $facility_info["facility_state"] != "" &&
            $facility_info["facility_postcode"] != "" &&
            $facility_info["facility_emr"] != "" &&
            $facility_info["facility_bcheck_provider"] != "" &&
            $facility_info["nurse_cred_soft"] != "" &&
            $facility_info["nurse_scheduling_sys"] != "" &&
            $facility_info["time_attend_sys"] != ""
        ) $facility_info['facility_profile_flag'] = "1";

        return $facility_info;
    }

    /* average rating calculation */
    public function ratingCalculation($count, $array)
    {
        $max = 0;
        $n = $count; // get the count of comments
        if (!empty($array)) {
            foreach ($array as $key => $rating) { // iterate through array
                $max = $max + $rating;
            }
        }

        return ($max == 0 || $n == 0) ? "5" : strval(round(($max / $n), 1));
    }
    /* average rating calculation */

    /* major cities in us, */
    public function citiesList()
    {
        $cities_list = [
            "anchorage" => "99507", "juneau" => "99801", "fairbanks" => "99709", "badger" => "99705", "palmer" => "99645", "birmingham" => "35211", "huntsville" => "35810", "montgomery" => "36117", "mobile" => "36695", "tuscaloosa" => "35405", "little rock" => "72204", "fayetteville" => "72701", "fort smith" => "72903", "springdale" => "72764", "jonesboro" => "72401", "phoenix" => "85032", "tucson" => "85710", "mesa" => "85204", "chandler" => "85225", "scottsdale" => "85251", "los angeles" => "90011", "san diego" => "92154", "san jose" => "95123", "san francisco" => "94112", "fresno" => "93722", "denver" => "80219", "colorado springs" => "80918", "aurora" => "80013", "fort collins" => "80525", "lakewood" => "80226", "bridgeport" => "06606", "new haven" => "06511", "stamford" => "06902", "hartford" => "06106", "waterbury" => "06708", "washington" => "20011", "shaw" => "20001", "adams morgan" => "20009", "chevy chase" => "20015", "bloomingdale" => "20001", "wilmington" => "19805", "dover" => "19904", "newark" => "19711", "middletown" => "19709", "bear" => "19701", "jacksonville" => "32210", "miami" => "33186", "tampa" => "33647", "orlando" => "32811", "st. petersburg" => "33710", "atlanta" => "30349", "augusta" => "30906", "columbus" => "31907", "savannah" => "31405", "athens" => "30606", "honolulu" => "96817", "east honolulu" => "96818", "pearl city" => "96782", "hilo" => "96720", "kailua" => "96740", "des moines" => "50317", "cedar rapids" => "52402", "davenport" => "52806", "sioux city" => "51106", "iowa city" => "52240", "boise" => "83709", "meridian" => "83646", "nampa" => "83686", "idaho falls" => "83401", "caldwell" => "83605", "chicago" => "60629", "aurora" => "60505", "naperville" => "60565", "joilet" => "60435", "rockford" => "61107", "indianapolis" => "46227", "fort wayne" => "46835", "evansville" => "47714", "carmel" => "46032", "south bend" => "46614", "wichita" => "67212", "overland park" => "66212", "kansas city" => "66102", "olathe" => "66062", "topeka" => "66614", "louisville" => "40299", "lexington" => "40509", "bowling green" => "42101", "owensboro" => "42301", "covington" => "41011", "new orleans" => "70119", "baton rouge" => "70808", "shreveport" => "71106", "metairie" => "70003", "lafayette" => "70506", "boston" => "02124", "worcester" => "01604", "springfield" => "01109", "cambridge" => "02139", "lowell" => "01852", "baltimore" => "21215", "columbia" => "21044", "germantown" => "20874", "silver spring" => "20906", "waldorf" => "20602", "detroit" => "48228", "grand rapids" => "49504", "warren" => "48089", "sterling heights" => "48310", "lansing " => "48911", "minneapolis" => "55407", "st. paul" => "55106", "rochester" => "55901", "duluth" => "55811", "bloomington" => "55420", "kansas city" => "64114", "st. louis" => "63116", "springfield" => "65807", "columbia" => "65203", "independence " => "64055", "jackson" => "39212", "gulfport" => "39503", "southaven" => "38671", "biloxi" => "39531", "hattiesburg" => "39401", "billings" => "59101", "missoula" => "59808", "great falls" => "59401", "bozeman" => "59715", "butte" => "59701", "charlotte" => "28205", "raleigh" => "27603", "greensboro" => "27413", "durham" => "27703", "winston-salem" => "27101", "fargo" => "58102", "bismarck" => "58501", "grand forks" => "58201", "minot" => "58701", "west fargo" => "58078", "omaha" => "68007", "lincon " => "68501", "bellevue" => "68005", "grand island" => "68801", "kearney" => "68845", "manchester" => "03101", "nashua" => "03060", "concord" => "03301", "dover" => "03820", "rochester" => "03867", "newark" => "07101", "jersey city" => "07302", "paterson" => "07501", "elizabeth" => "07201", "toms river" => "08753", "albuquerque" => "87101", "las cruces" => "88001", "rio rancho" => "87144", "santa fe" => "87501", "roswell" => "88202", "las vegas" => "88901", "henderson" => "89002", "reno" => "89502", "north las vegas" => "89030", "paradise" => "89103", "new york" => "10011", "buffalo" => "14201", "rochester" => "14602", "yonkers" => "10701", "syracuse" => "13201", "columbus" => "43210", "cleveland" => "44101", "cincinnati" => "45003", "toledo" => "43604", "akron" => "44320", "oklahoma city" => "73008", "tulsa" => "74008", "norman" => "73019", "broken arrow" => "74011", "edmond" => "73003", "portland" => "97201", "salem" => "97301", "eugene" => "97402", "hillsboro" => "97124", "gresham" => "97080", "philadelphia" => "19102", "pittsburgh" => "15222", "allentown" => "18104", "erie" => "16504", "reading" => "19602", "providence" => "02901", "warwick" => "02886", "cranston" => "02920", "pawtucket" => "02861", "east providence" => "02914", "north charleston" => "29405", "mount pleasant" => "29464", "rock hill" => "29732", "greenville" => "29611", "summerville" => "29485", "sioux falls" => "57101", "rapid city" => "57701", "aberdeen" => "57401", "brookings" => "57006", "watertown" => "57201", "nashville" => "37011", "memphis" => "37501", "knoxville" => "37901", "clarksville" => "37040", "chattanooga" => "37341", "houston" => "77002", "austin" => "78701", "san antonio" => "78204", "dallas" => "75201", "fort worth" => "76102", "salt lake city" => "84101", "west valley city" => "84119", "west jordan" => "84081", "provo" => "84097", "orem" => "84058", "virginia beach" => "23451", "chesapeake" => "23320", "norfolk" => "23502", "arlington" => "22206", "richmond" => "23220", "burlington" => "05401", "south burlington" => "05403", "rutland" => "05701", "essex junction" => "05451", "bennington" => "05201", "seattle" => "98121", "spokane" => "99201", "tacoma" => "98402", "vancouver" => "98660", "kent" => "98032", "milwaukee" => "53201", "madison" => "53558", "green bay" => "54229", "kenosha" => "53140", "racine" => "53401", "charleston" => "25301", "huntington" => "25701", "morgantown" => "26501", "parkersburg" => "26101", "wheeling" => "26003", "cheyenne" => "82001", "casper" => "82609", "laramie" => "82070", "gillette" => "82716", "rock springs" => "82901"
        ];

        return $cities_list;
    }
    /* major cities in us, */

    public function nurseInfo($nurse)
    {
        $controller = new Controller();
        $specialties = $controller->getSpecialities()->pluck('title', 'id');
        $ehrProficienciesExp = $this->getEHRProficiencyExp()->pluck('title', 'id');

        $nurse_result = [];
        foreach ($nurse as $key => $n) {
            $nurse_data["nurse_id"] = (isset($n->id) && $n->id != "") ? $n->id : "";
            $nurse_data["user_id"] = (isset($n->user_id) && $n->user_id != "") ? $n->user_id : "";

            /* user tables records */
            $nurse_data["role"] = (isset($n->user->role) && $n->user->role != "") ? $n->user->role : "";
            $nurse_data["first_name"] = (isset($n->user->first_name) && $n->user->first_name != "") ? $n->user->first_name : "";
            $nurse_data["last_name"] = (isset($n->user->last_name) && $n->user->last_name != "") ? $n->user->last_name : "";

            // $nurse_data["nurse_logo"] = (isset($n->user->image) && $n->user->image != "") ? url('storage/assets/nurses/profile/' . $n->user->image) : "";
            $nurse_data['nurse_logo'] = (isset($n->user->image) && $n->user->image != '') ? url('public/images/nurses/profile/'.$n->user->image) : '';
            // $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
            // if ($n->user->image) {
            //     $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $n->user->image);
            //     if ($t) {
            //         $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $n->user->image);
            //     }
            // }
            // $nurse_data["nurse_logo_base"] = 'data:image/jpeg;base64,' . base64_encode($profileNurse);

            $nurse_data["nurse_email"] = (isset($n->user->email) && $n->user->email != "") ? $n->user->email : "";
            $nurse_data["user_name"] = (isset($n->user->user_name) && $n->user->user_name != "") ? $n->user->user_name : "";
            $nurse_data["fcm_token"] = (isset($n->user->fcm_token) && $n->user->fcm_token != "") ? $n->user->fcm_token : "";
            // $nurse_data["email_verified_at"] = (isset($n->user->email_verified_at) && $n->user->email_verified_at != "") ? $n->user->email_verified_at : "";
            $nurse_data["date_of_birth"] = (isset($n->user->date_of_birth) && $n->user->date_of_birth != "") ? $n->user->date_of_birth : "";
            $nurse_data["mobile"] = (isset($n->user->mobile) && $n->user->mobile != "") ? $n->user->mobile : "";
            $nurse_data["new_mobile"] = (isset($n->user->new_mobile) && $n->user->new_mobile != "") ? $n->user->new_mobile : "";
            // $nurse_data["otp"] = (isset($n->user->otp) && $n->user->otp != "") ? $n->user->otp : "";
            $nurse_data["email_notification"] = (isset($n->user->email_notification) && $n->user->email_notification != "") ? strval($n->user->email_notification) : "";
            $nurse_data["sms_notification"] = (isset($n->user->sms_notification) && $n->user->sms_notification != "") ? strval($n->user->sms_notification) : "";
            // $nurse_data["active"] = (isset($n->user->active) && $n->user->active != "") ? $n->user->active : "";
            // $nurse_data["remember_token"] = (isset($n->user->remember_token) && $n->user->remember_token != "") ? $n->user->remember_token : "";
            // $nurse_data["deleted_at"] = (isset($n->user->deleted_at) && $n->user->deleted_at != "") ? $n->user->deleted_at : "";
            // $nurse_data["created_at"] = (isset($n->user->created_at) && $n->user->created_at != "") ? $n->user->created_at : "";
            // $nurse_data["updated_at"] = (isset($n->user->updated_at) && $n->user->updated_at != "") ? $n->user->updated_at : "";
            // $nurse_data["banned_until"] = (isset($n->user->banned_until) && $n->user->banned_until != "") ? $n->user->banned_until : "";
            // $nurse_data["last_login_at"] = (isset($n->user->last_login_at) && $n->user->last_login_at != "") ? $n->user->last_login_at : "";
            // $nurse_data["last_login_ip"] = (isset($n->user->last_login_ip) && $n->user->last_login_ip != "") ? $n->user->last_login_ip : "";
            /* user tables records */

            $nurse_data["specialty"] = (isset($n->specialty) && $n->specialty != "") ? $n->specialty : "";
            $specialties_array = ($nurse_data["specialty"] != "") ? $spl = explode(",", $nurse_data["specialty"]) : [];
            $nurse_data["specialty_definition"] = [];
            foreach ($specialties_array as $spl) {
                $spl_name = (isset($specialties[$spl]) && $specialties[$spl] != "") ? $specialties[$spl] : "";
                if ($spl_name != "")
                    $nurse_data["specialty_definition"][] = ['id' => $spl, 'name' => $spl_name];
            }
            $nurse_data["nursing_license_state"] = (isset($n->nursing_license_state) && $n->nursing_license_state != "") ? $n->nursing_license_state : "";
            $nurse_data["nursing_license_number"] = (isset($n->nursing_license_number) && $n->nursing_license_number != "") ? $n->nursing_license_number : "";
            $nurse_data["highest_nursing_degree"] = (isset($n->highest_nursing_degree) && $n->highest_nursing_degree != "") ? strval($n->highest_nursing_degree) : "";
            $nurse_data["serving_preceptor"] = (isset($n->serving_preceptor) && $n->serving_preceptor != "") ? strval($n->serving_preceptor) : "";
            $nurse_data["serving_interim_nurse_leader"] = (isset($n->serving_interim_nurse_leader) && $n->serving_interim_nurse_leader != "") ? strval($n->serving_interim_nurse_leader) : "";
            $nurse_data["leadership_roles"] = (isset($n->leadership_roles) && $n->leadership_roles != "") ? strval($n->leadership_roles) : "";
            $nurse_data["address"] = (isset($n->address) && $n->address != "") ? $n->address : "";
            $nurse_data["city"] = (isset($n->city) && $n->city != "") ? $n->city : "";
            $nurse_data["state"] = (isset($n->state) && $n->state != "") ? $n->state : "";
            $nurse_data["postcode"] = (isset($n->postcode) && $n->postcode != "") ? $n->postcode : "";
            $nurse_data["country"] = (isset($n->country) && $n->country != "") ? $n->country : "";
            $nurse_data["hourly_pay_rate"] = (isset($n->hourly_pay_rate) && $n->hourly_pay_rate != "") ? $n->hourly_pay_rate : "";
            $nurse_data["experience_as_acute_care_facility"] = (isset($n->experience_as_acute_care_facility) && $n->experience_as_acute_care_facility != "") ? $n->experience_as_acute_care_facility : "";
            $nurse_data["experience_as_ambulatory_care_facility"] = (isset($n->experience_as_ambulatory_care_facility) && $n->experience_as_ambulatory_care_facility != "") ? $n->experience_as_ambulatory_care_facility : "";
            // $nurse_data["active"] = (isset($n->active) && $n->active != "") ? $n->active : "";
            // $nurse_data["deleted_at"] = (isset($n->deleted_at) && $n->deleted_at != "") ? $n->deleted_at : "";
            // $nurse_data["created_at"] = (isset($n->created_at) && $n->created_at != "") ? $n->created_at : "";
            // $nurse_data["updated_at"] = (isset($n->updated_at) && $n->updated_at != "") ? $n->updated_at : "";

            $nurse_data["ehr_proficiency_cerner"] = (isset($n->ehr_proficiency_cerner) && $n->ehr_proficiency_cerner != "") ? strval($n->ehr_proficiency_cerner) : "";
            $nurse_data["ehr_proficiency_cerner_definition"] = (isset($ehrProficienciesExp[$n->ehr_proficiency_cerner]) && $ehrProficienciesExp[$n->ehr_proficiency_cerner] != "") ? $ehrProficienciesExp[$n->ehr_proficiency_cerner] : "";
            $nurse_data["ehr_proficiency_meditech"] = (isset($n->ehr_proficiency_meditech) && $n->ehr_proficiency_meditech != "") ? strval($n->ehr_proficiency_meditech) : "";
            $nurse_data["ehr_proficiency_meditech_definition"] = (isset($ehrProficienciesExp[$n->ehr_proficiency_meditech]) && $ehrProficienciesExp[$n->ehr_proficiency_meditech] != "") ? $ehrProficienciesExp[$n->ehr_proficiency_meditech] : "";
            $nurse_data["ehr_proficiency_epic"] = (isset($n->ehr_proficiency_epic) && $n->ehr_proficiency_epic != "") ? strval($n->ehr_proficiency_epic) : "";
            $nurse_data["ehr_proficiency_epic_definition"] = (isset($ehrProficienciesExp[$n->ehr_proficiency_epic]) && $ehrProficienciesExp[$n->ehr_proficiency_epic] != "") ? $ehrProficienciesExp[$n->ehr_proficiency_epic] : "";


            $nurse_data["slug"] = (isset($n->slug) && $n->slug != "") ? $n->slug : "";
            $nurse_data["summary"] = (isset($n->summary) && $n->summary != "") ? $n->summary : "";
            $nurse_data["nurses_video"] = (isset($n->nurses_video) && $n->nurses_video != "") ? $n->nurses_video : "";
            $nurse_data["nurses_facebook"] = (isset($n->nurses_facebook) && $n->nurses_facebook != "") ? $n->nurses_facebook : "";
            $nurse_data["nurses_twitter"] = (isset($n->nurses_twitter) && $n->nurses_twitter != "") ? $n->nurses_twitter : "";
            $nurse_data["nurses_linkedin"] = (isset($n->nurses_linkedin) && $n->nurses_linkedin != "") ? $n->nurses_linkedin : "";
            $nurse_data["nurses_instagram"] = (isset($n->nurses_instagram) && $n->nurses_instagram != "") ? $n->nurses_instagram : "";
            $nurse_data["nurses_pinterest"] = (isset($n->nurses_pinterest) && $n->nurses_pinterest != "") ? $n->nurses_pinterest : "";
            $nurse_data["nurses_tiktok"] = (isset($n->nurses_tiktok) && $n->nurses_tiktok != "") ? $n->nurses_tiktok : "";
            $nurse_data["nurses_sanpchat"] = (isset($n->nurses_sanpchat) && $n->nurses_sanpchat != "") ? $n->nurses_sanpchat : "";
            $nurse_data["nurses_youtube"] = (isset($n->nurses_youtube) && $n->nurses_youtube != "") ? $n->nurses_youtube : "";
            $nurse_data["clinical_educator"] = (isset($n->clinical_educator) && $n->clinical_educator != "") ? strval($n->clinical_educator) : "";
            $nurse_data["is_daisy_award_winner"] = (isset($n->is_daisy_award_winner) && $n->is_daisy_award_winner != "") ? strval($n->is_daisy_award_winner) : "";
            $nurse_data["employee_of_the_mth_qtr_yr"] = (isset($n->employee_of_the_mth_qtr_yr) && $n->employee_of_the_mth_qtr_yr != "") ? strval($n->employee_of_the_mth_qtr_yr) : "";
            $nurse_data["other_nursing_awards"] = (isset($n->other_nursing_awards) && $n->other_nursing_awards != "") ? strval($n->other_nursing_awards) : "";
            $nurse_data["is_professional_practice_council"] = (isset($n->is_professional_practice_council) && $n->is_professional_practice_council != "") ? strval($n->is_professional_practice_council) : "";
            $nurse_data["is_research_publications"] = (isset($n->is_research_publications) && $n->is_research_publications != "") ? $n->is_research_publications : "";
            $nurse_data["credential_title"] = (isset($n->credential_title) && $n->credential_title != "") ? $n->credential_title : "";
            $nurse_data["mu_specialty"] = (isset($n->mu_specialty) && $n->mu_specialty != "") ? $n->mu_specialty : "";
            $nurse_data["additional_photos"] = (isset($n->additional_photos) && $n->additional_photos != "") ? $n->additional_photos : "";
            $nurse_data["languages"] = (isset($n->languages) && $n->languages != "") ? $n->languages : "";
            $nurse_data["additional_files"] = (isset($n->additional_files) && $n->additional_files != "") ? $n->additional_files : "";
            $nurse_data["college_uni_name"] = (isset($n->college_uni_name) && $n->college_uni_name != "") ? $n->college_uni_name : "";
            $nurse_data["college_uni_city"] = (isset($n->college_uni_city) && $n->college_uni_city != "") ? $n->college_uni_city : "";
            $nurse_data["college_uni_state"] = (isset($n->college_uni_state) && $n->college_uni_state != "") ? $n->college_uni_state : "";
            $nurse_data["college_uni_country"] = (isset($n->college_uni_country) && $n->college_uni_country != "") ? $n->college_uni_country : "";
            $nurse_data["facility_hourly_pay_rate"] = (isset($n->facility_hourly_pay_rate) && $n->facility_hourly_pay_rate != "") ? $n->facility_hourly_pay_rate : "";
            $nurse_data["n_lat"] = (isset($n->n_lat) && $n->n_lat != "") ? $n->n_lat : "";
            $nurse_data["n_lang"] = (isset($n->n_lang) && $n->n_lang != "") ? $n->n_lang : "";
            $nurse_data["resume"] = (isset($n->resume) && $n->resume != "") ? $n->resume : "";
            $nurse_data["nu_video"] = (isset($n->nu_video) && $n->nu_video != "") ? $n->nu_video : "";
            $nurse_data["nu_video_embed_url"] = (isset($n->nu_video_embed_url) && $n->nu_video_embed_url != "") ? $n->nu_video_embed_url : "";
            $nurse_data["is_verified"] = (isset($n->is_verified) && $n->is_verified != "") ? $n->is_verified : "";
            $nurse_data["is_verified_nli"] = (isset($n->is_verified_nli) && $n->is_verified_nli != "") ? $n->is_verified_nli : "";
            $nurse_data["gig_account_id"] = (isset($n->gig_account_id) && $n->gig_account_id != "") ? $n->gig_account_id : "";
            $nurse_data["is_gig_invite"] = (isset($n->is_gig_invite) && $n->is_gig_invite != "") ? $n->is_gig_invite : "";
            $nurse_data["gig_account_create_date"] = (isset($n->gig_account_create_date) && $n->gig_account_create_date != "") ? $n->gig_account_create_date : "";
            $nurse_data["gig_account_invite_date"] = (isset($n->gig_account_invite_date) && $n->gig_account_invite_date != "") ? $n->gig_account_invite_date : "";
            $nurse_data["search_status"] = (isset($n->search_status) && $n->search_status != "") ? strval($n->search_status) : "";
            $nurse_data["search_status_definition"] = (isset($n->search_status) && $n->search_status != "") ? \App\Providers\AppServiceProvider::keywordTitle($n->search_status) : "";
            $nurse_data["license_type"] = (isset($n->license_type) && $n->license_type != "") ? strval($n->license_type) : "";
            $nurse_data["license_type_definition"] = (isset($n->license_type) && $n->license_type != "") ? \App\Providers\AppServiceProvider::keywordTitle($n->license_type) : "";
            /* rating */
            // for these below columns need to be created in DB
            /* $rating['over_all'] = (isset($n->over_all) && $n->over_all != "") ? $n->over_all : "0";
            $rating['clinical_skills'] = (isset($n->clinical_skills) && $n->clinical_skills != "") ? $n->clinical_skills : "0";
            $rating['nurse_teamwork'] = (isset($n->nurse_teamwork) && $n->nurse_teamwork != "") ? $n->nurse_teamwork : "0";
            $rating['interpersonal_skills'] = (isset($n->interpersonal_skills) && $n->interpersonal_skills != "") ? $n->interpersonal_skills : "0";
            $rating['work_ethic'] = (isset($n->work_ethic) && $n->work_ethic != "") ? $n->work_ethic : "0"; */

            $rating_info = NurseRating::where(['nurse_id' => $n->id]);
            $overall = $clinical_skills = $nurse_teamwork = $interpersonal_skills = $work_ethic = $a = [];
            if ($rating_info->count() > 0) {
                foreach ($rating_info->get() as $key => $r) {
                    $overall[] = $r->overall;
                    $clinical_skills[] = $r->clinical_skills;
                    $nurse_teamwork[] = $r->nurse_teamwork;
                    $interpersonal_skills[] = $r->interpersonal_skills;
                    $work_ethic[] = $r->work_ethic;
                }
            }
            $rating['over_all'] = $this->ratingCalculation(count($overall), $overall);
            $rating['clinical_skills'] = $this->ratingCalculation(count($clinical_skills), $clinical_skills);
            $rating['nurse_teamwork'] = $this->ratingCalculation(count($nurse_teamwork), $nurse_teamwork);
            $rating['interpersonal_skills'] = $this->ratingCalculation(count($interpersonal_skills), $interpersonal_skills);
            $rating['work_ethic'] = $this->ratingCalculation(count($work_ethic), $work_ethic);
            $nurse_data["rating"] = $rating;
            /* rating */
            $nurse_data['work_experience'] = (isset($n->work_experience) && $n->work_experience != "") ? $n->work_experience : "";

            $nurse_result[] = $nurse_data;
        }
        return $nurse_result;
    }

    public function createJob($check_type = "create", Request $request)
    {
        $messages = [
            "job_photos.*.mimes" => "Photos should be image or png jpg",
            "job_photos.*.max" => "Photos should not be more than 5mb"
        ];

        $validation_array = [
            'user_id' => 'required',
            'api_key' => 'required',
            'job_name' => 'required',
            'type' => 'required'
            
        ];
        if ($check_type == "update") {
            $validation_array = array_merge($validation_array, ['job_id' => 'required']);
        }
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
            $update_array["job_name"] = $request->job_name;
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
            // $update_array["facility_id"] = isset($request->facility_id)?$request->facility_id:$facility_id;
            $update_array["facility"] = 'Testing Facility';
            $update_array["facility_id"] = "GWf000001";
            
            $update_array["clinical_setting"] = isset($request->clinical_setting)?$request->clinical_setting:'';
            $update_array["Patient_ratio"] = isset($request->Patient_ratio)?$request->Patient_ratio:'';
            $update_array["emr"] = isset($request->emr)?$request->emr:'';
            $update_array["Unit"] = isset($request->Unit)?$request->Unit:'';
            $update_array["Department"] = isset($request->Department)?$request->Department:'';
            $update_array["Bed_Size"] = isset($request->Bed_Size)?$request->Bed_Size:'';
            $update_array["Trauma_Level"] = isset($request->Trauma_Level)?$request->Trauma_Level:'';
            $update_array["scrub_color"] = isset($request->scrub_color)?$request->scrub_color:'';
            $update_array["as_soon_as"] = isset($request->as_soon_as)?$request->as_soon_as:'';
            if($update_array["as_soon_as"] == '1'){
                $update_array["start_date"] = isset($request->start_date)?$request->start_date:date('M j Y');
            }else{
                $update_array["start_date"] = isset($request->start_date)?$request->start_date:'';
            }
            
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
            if($update_array["hours_per_week"] == ''){
                $update_array["hours_per_week"] = 0;
            }
            if($update_array["actual_hourly_rate"] == ''){
                $update_array["actual_hourly_rate"] = 0;
            }
            if($update_array["weekly_non_taxable_amount"] == ''){
                $update_array["weekly_non_taxable_amount"] = 0;
            }
            if($update_array["preferred_assignment_duration"] == ''){
                $update_array["preferred_assignment_duration"] = 0;
            }
            if($update_array["sign_on_bonus"] == ''){
                $update_array["sign_on_bonus"] = 0;
            }
            if($update_array["completion_bonus"] == ''){
                $update_array["completion_bonus"] = 0;
            }
            $update_array["weekly_taxable_amount"] = $update_array["hours_per_week"]*$update_array["actual_hourly_rate"];
            $update_array["employer_weekly_amount"] = $update_array["weekly_taxable_amount"]+$update_array["weekly_non_taxable_amount"];
            $update_array["goodwork_weekly_amount"] = $update_array["employer_weekly_amount"]*0.05;
            $update_array["total_employer_amount"] = ($update_array["preferred_assignment_duration"]*$update_array["employer_weekly_amount"])+$update_array["sign_on_bonus"]+$update_array["completion_bonus"];
            $update_array["total_goodwork_amount"] = $update_array["preferred_assignment_duration"]*$update_array["goodwork_weekly_amount"];
            $update_array["total_contract_amount"] = $update_array["total_employer_amount"]+$update_array["total_goodwork_amount"];
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
            $update_array["active"] = isset($request->active)?$request->active:'0';
            
            if ($check_type == "update") {
                /* update job */
                if(isset($request->job_id)){
                    $job_id = $request->job_id;
                    $job = Job::where(['id' => $job_id])->update($update_array);
                }else{
                    $this->check = "0";
                    $this->message = "Something went wrong! Please check job_id";
                }
                /* update job */
            } else {
                /* create job */
                $update_array["created_by"] = (isset($request->user_id) && $request->user_id != "") ? $request->user_id : "";
                $update_array["recruiter_id"] = (isset($request->user_id) && $request->user_id != "") ? $request->user_id : "";
                $update_array["goodwork_number"] = uniqid();
                $job = Job::create($update_array);
                Job::where('id', $job['id'])->update(['goodwork_number' => $job['id']]);
                $job = Job::where('id', $job['id'])->first();
                /* create job */
            }

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

            if ($job) {
                $this->check = "1";
                $this->message = "Job " . $check_type . "d successfully";
                if ($check_type == "update") {
                    $job_data = Job::where(['id' => $job_id]);
                    if ($job_data->count() > 0) {
                        $this->return_data = $job_data->first();
                    }
                } else {
                    $this->return_data = $job;
                }
            } else {
                $this->check = "0";
                $this->message = "Failed to create job, Please try again later";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }    

    public function getAvailability(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'nurse_id' => 'required',
            'month' => 'required',
            'year' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $availability = Availability::where('nurse_id', '=', $request->nurse_id)->get()->first();

            $unavailable_dates = explode(',',$availability->unavailable_dates);

            $unavailable_datesArr['unavailable_dates'] = array();
            foreach($unavailable_dates as $row){
                $month = date('m',strtotime($row));
                $year = date('Y',strtotime($row));

                if($request->month == $month && $request->year == $year){
                    array_push($unavailable_datesArr['unavailable_dates'], $row);
                }
            }

            if ($unavailable_datesArr) {
                // $user = $user_info->first();
                $this->check = "1";
                $this->message = "Unavailable dates listed successfully";
                $this->return_data = $unavailable_datesArr;
            } else {
                $this->message = "Data not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
   


    }

    public function explore_jobData($jobdata, $user_id = "")
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
                $j_data["keyword_id"] = isset($job->keyword_id) ? $job->keyword_id : "";
                $j_data["job_title"] = isset($job->job_title) ? $job->job_title : "";
                $j_data["job_filter"] = isset($job->job_filter) ? $job->job_filter : "";
                $j_data["offer_id"] = isset($job->offer_id) ? $job->offer_id : "";
                $j_data["job_id"] = isset($job->job_id) ? $job->job_id : "";
                $j_data["job_type"] = isset($job->job_type) ? $job->job_type : "";
                $j_data["type"] = isset($job->type) ? $job->type : "";
                $j_data["job_name"] = isset($job->job_name) ? $job->job_name : "";
                $j_data["job_location"] = isset($job->job_location) ? $job->job_location : "";
                $j_data["employer_weekly_amount"] = isset($job->employer_weekly_amount) ? $job->employer_weekly_amount : "";
                $j_data["weekly_pay"] = isset($job->weekly_pay) ? $job->weekly_pay : "";
                $j_data["hours_per_week"] = isset($job->hours_per_week) ? $job->hours_per_week : "";

                $j_data["preferred_specialty"] = isset($job->preferred_specialty) ? $job->preferred_specialty : "";
                $j_data["preferred_specialty_definition"] = isset($specialties[$job->preferred_specialty])  ? $specialties[$job->preferred_specialty] : "";
                
                $j_data["preferred_assignment_duration"] = isset($job->preferred_assignment_duration) ? $job->preferred_assignment_duration : "";
                $j_data["preferred_assignment_duration_definition"] = isset($assignmentDurations[$job->preferred_assignment_duration]) ? $assignmentDurations[$job->preferred_assignment_duration] : "";
                
                $j_data["preferred_shift"] = isset($job->preferred_shift) ? $job->preferred_shift : "";
                $j_data["preferred_shift_duration"] = isset($job->preferred_shift_duration) ? $job->preferred_shift_duration : "";
                $j_data["preferred_shift_duration_definition"] = isset($shifts[$job->preferred_shift_duration]) ? $shifts[$job->preferred_shift_duration] : "";

                $j_data["preferred_work_location"] = isset($job->preferred_work_location) ? $job->preferred_work_location : "";
                $j_data["preferred_work_location_definition"] = isset($workLocations[$job->preferred_work_location]) ? $workLocations[$job->preferred_work_location] : "";
                
                $j_data["preferred_work_area"] = isset($job->preferred_work_area) ? $job->preferred_work_area : "";
                $j_data["preferred_days_of_the_week"] = isset($job->preferred_days_of_the_week) ? explode(",", $job->preferred_days_of_the_week) : [];
                $j_data["preferred_hourly_pay_rate"] = isset($job->preferred_hourly_pay_rate) ? $job->preferred_hourly_pay_rate : "";
                $j_data["preferred_experience"] = isset($job->preferred_experience) ? $job->preferred_experience : "";
                $j_data["description"] = isset($job->description) ? $job->description : "";
                $j_data["created_at"] = isset($job->created_at) ? date('d-F-Y h:i A', strtotime($job->created_at)) : "";
                $j_data["created_at_definition"] = isset($job->created_at) ? "Posted " . $this->timeAgo(date(strtotime($job->created_at))) : "";
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
                $j_data["city"] = isset($job->facility->city) ? $job->facility->city : "";
                $j_data["state"] = isset($job->facility->state) ? $job->facility->state : "";
                $j_data["postcode"] = isset($job->facility->postcode) ? $job->facility->postcode : "";
                $j_data["type"] = isset($job->facility->type) ? $job->facility->type : "";

                $j_data["facility_logo"] = isset($job->facility->facility_logo) ? url("public/images/facilities/" . $job->facility->facility_logo) : "";
         
                $j_data["facility_email"] = isset($job->facility->facility_email) ? $job->facility->facility_email : "";
                $j_data["facility_phone"] = isset($job->facility->facility_phone) ? $job->facility->facility_phone : "";
                $j_data["specialty_need"] = isset($job->facility->specialty_need) ? $job->facility->specialty_need : "";
                $j_data["cno_message"] = isset($job->facility->cno_message) ? $job->facility->cno_message : "";

                $j_data["cno_image"] = isset($job->facility->cno_image) ? url("public/images/facilities/cno_image".$job->facility->cno_image) : "";
                // $cno_image = "";
                // if ($job->facility->cno_image) {
                //     $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/cno_image/' . $job->facility->cno_image);
                //     if ($t) {
                //         $cno_image = \Illuminate\Support\Facades\Storage::get('assets/facilities/cno_image/' . $job->facility->cno_image);
                //     }
                // }
                // $j_data["cno_image_base"] = ($cno_image != "") ? 'data:image/jpeg;base64,' . base64_encode($cno_image) : "";

                $j_data["gallary_images"] = isset($job->facility->gallary_images) ? $job->facility->gallary_images : "";
                $j_data["video"] = isset($job->facility->video) ? $job->facility->video : "";
                $j_data["facebook"] = isset($job->facility->facebook) ? $job->facility->facebook : "";
                $j_data["twitter"] = isset($job->facility->twitter) ? $job->facility->twitter : "";
                $j_data["linkedin"] = isset($job->facility->linkedin) ? $job->facility->linkedin : "";
                $j_data["instagram"] = isset($job->facility->instagram) ? $job->facility->instagram : "";
                $j_data["pinterest"] = isset($job->facility->pinterest) ? $job->facility->pinterest : "";
                $j_data["tiktok"] = isset($job->facility->tiktok) ? $job->facility->tiktok : "";
                $j_data["sanpchat"] = isset($job->facility->sanpchat) ? $job->facility->sanpchat : "";
                $j_data["youtube"] = isset($job->facility->youtube) ? $job->facility->youtube : "";
                $j_data["about_facility"] = isset($job->facility->about_facility) ? $job->facility->about_facility : "";
                $j_data["facility_website"] = isset($job->facility->facility_website) ? $job->facility->facility_website : "";
                $j_data["f_lat"] = isset($job->facility->f_lat) ? $job->facility->f_lat : "";
                $j_data["f_lang"] = isset($job->facility->f_lang) ? $job->facility->f_lang : "";
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
                
                /* total applied */
                $total_follow_count = Follows::where(['job_id' => $job->job_id, "applied_status" => "1", 'status' => "1"])->count();
                $j_data["total_applied"] = strval($total_follow_count);
                /* total applied */

                /* liked */
                $is_applied = "0";
                if ($user_id != "")
                    $is_applied = Follows::where(['job_id' => $job->job_id, "applied_status" => "1", 'status' => "1", "user_id" => $user_id])->count();
                /* liked */
                $j_data["is_applied"] = strval($is_applied);

                /* liked */
                $is_liked = "0";
                if ($user_id != "")
                    $is_liked = Follows::where(['job_id' => $job->job_id, "like_status" => "1", 'status' => "1", "user_id" => $user_id])->count();
                /* liked */
                $j_data["is_liked"] = strval($is_liked);

                $j_data["shift"] = "Days";
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
                            // 'job_saved.nurse_id' => $nurse->id,
                            'job_saved.nurse_id' => $nurse->user_id,
                            'job_saved.job_id' => $job->job_id,
                        ];
        
                        $limit = 10;
                        $saveret = \DB::table('job_saved')
                        ->join('jobs', 'jobs.id', '=', 'job_saved.job_id')
                        ->where($whereCond);

                        if ($saveret->count() > 0) {
                            $is_saved = 1;
                        }



                        $whereCond1 = [
                            'facilities.active' => true,
                            // 'jobs.is_open' => "1",
                            'offers.status' => 'Offered',
                            'offers.nurse_id' => $nurse->id
                        ];
    
                        $ret = Job::select('jobs.id as job_id','jobs.job_type as job_type', 'jobs.*')
                            ->leftJoin('facilities', function ($join) {
                                $join->on('facilities.id', '=', 'jobs.facility_id');
                            })
                            ->join('offers', 'jobs.id', '=', 'offers.job_id')
                            ->where($whereCond1)
                            ->orderBy('offers.created_at', 'desc');
                    
    
                        // $job_data = $ret->paginate(10);
                        $job_data = $ret->get();
    
                        $j_data['nurses_applied'] = $this->explore_jobData($job_data, $user_id);
                    }
                }
                $j_data["is_saved"] = $is_saved;

                $j_data["popular_jobs"] = array();
                
                if ($user_id != ""){
                    $whereCond = [
                        'facilities.active' => true,
                        'jobs.job_type' => $j_data["keyword_id"],
                        'jobs.is_open' => "1",
                    ];

                    // $ret = Job::select('jobs.id as job_id','jobs.job_type as job_type', 'jobs.*')
                    //     ->leftJoin('facilities', function ($join) {
                    //         $join->on('facilities.id', '=', 'jobs.facility_id');
                    //     })
                    //     ->leftJoin('offers', function ($join) {
                    //         $join->on('offers.job_id', '=', 'jobs.id');
                    //     })
                    //     ->where($whereCond)
                    //     ->orderBy('jobs.created_at', 'desc');
                    $ret = DB::table('jobs')
                                ->leftJoin('facilities', 'facilities.id', '=', 'jobs.facility_id')
                                ->select('jobs.id as job_id','jobs.job_type as job_type', 'jobs.*')
                                ->where($whereCond)
                                ->orderBy('jobs.created_at', 'desc');

                    $job_data = $ret->get();
                    // $job_data = $ret->paginate(10);

                    $j_data['popular_jobs'] = $this->jobData($job_data);
                    $num = 0;
                    foreach($j_data['popular_jobs'] as $rec){
                        $j_data['popular_jobs'][$num]['description'] = strip_tags($rec['description']);
                        $j_data['popular_jobs'][$num]['responsibilities'] = strip_tags($rec['responsibilities']);
                        $j_data['popular_jobs'][$num]['qualifications'] = strip_tags($rec['qualifications']);
                        $j_data['popular_jobs'][$num]['cno_message'] = strip_tags($rec['cno_message']);
                        $j_data['popular_jobs'][$num]['about_facility'] = strip_tags($rec['about_facility']);
                        $num++;
                    }
                }
                $result[] = $j_data;
            }
        }
        return $result;
    }

    public function explore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $controller = new Controller();
            $specialties = $controller->getSpecialities()->pluck('title', 'id');
            $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
            
            $user_info = USER::where('id', $request->user_id);

            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $nurse_info = NURSE::where('user_id', $request->user_id);
                if ($nurse_info->count() > 0) {
                    $result = array();
                    $check_data = array();
                    $nurse = $nurse_info->get()->first();

                    $whereCond = [
                        'facilities.active' => true,
                        'jobs.is_open' => "1",
                    ];

                    $ret = DB::table('jobs')
                            ->leftJoin('facilities', 'facilities.id', '=', 'jobs.facility_id')
                            ->leftJoin('keywords', 'keywords.id', '=', 'jobs.job_type')
                            ->leftJoin('job_saved', 'job_saved.job_id', '=', 'jobs.id')
                            ->select('keywords.id as keyword_id','keywords.title as job_title','keywords.filter as job_filter', 'jobs.id as job_id','jobs.job_type as job_type', 'jobs.*')
                            ->where($whereCond)
                            ->orderBy('jobs.created_at', 'desc')->distinct('jobs.id');
                    $job_data = $ret->get();
                    // $job_data = $ret->paginate(10);
                    
                    // $result['popular_jobs'] = $this->explore_jobData($job_data, $request->user_id);
                    $check_data['popular_jobs'] = $this->explore_jobData($job_data, $request->user_id);
                    foreach($check_data['popular_jobs'] as $check_rec){
                        $is_delete = DB::table('job_saved')->where(['job_id' => $check_rec['job_id'], 'nurse_id' => $request->user_id, 'is_delete' => '1'])->first();
                        if(empty($is_delete)){
                            $result['popular_jobs'][] = $check_rec;
                        }
                    }
                    
                    $n_c = 0;
                    foreach($result['popular_jobs'] as $rec){
                        $result['popular_jobs'][$n_c]['description'] = strip_tags($rec['description']);
                        $result['popular_jobs'][$n_c]['responsibilities'] = strip_tags($rec['responsibilities']);
                        $result['popular_jobs'][$n_c]['qualifications'] = strip_tags($rec['qualifications']);
                        $result['popular_jobs'][$n_c]['cno_message'] = strip_tags($rec['cno_message']);
                        $result['popular_jobs'][$n_c]['about_facility'] = strip_tags($rec['about_facility']);
                        $n_c++;
                    }

                    // skip is_applied rrecord
                    $data = [];
                    foreach($result['popular_jobs'] as $val){
                        if($val['is_applied'] != '0'){
                            continue;
                        }
                        // print_r($val['is_applied']);
                        $data['popular_jobs'][] = $val;
                    }
                    // end skip code
                    
                    $whereCond1 = [
                        'facilities.active' => true,
                        'jobs.is_open' => "1",
                        'jobs.preferred_specialty' => $nurse->specialty
                    ];

                    $ret1 = Job::select('keywords.id as keyword_id','keywords.title as job_title','keywords.filter as job_filter','jobs.id as job_id', 'jobs.job_type as job_type', 'jobs.*')
                        ->leftJoin('facilities', function ($join) {
                            $join->on('facilities.id', '=', 'jobs.facility_id');
                        })
                        ->leftJoin('keywords', function ($join) {
                            $join->on('keywords.id', '=', 'jobs.job_type');
                        })
                        ->leftJoin('job_saved', function ($join) {
                            $join->on('job_saved.job_id', '=', 'jobs.id');
                        })
                        ->where($whereCond1)
                        ->orderBy('jobs.created_at', 'desc')->distinct('jobs.id');
                    $job_data1 = $ret1->get();
                    // $job_data1 = $ret1->paginate(10);

                    $result['recommended_jobs'] = $this->explore_jobData($job_data1, $request->user_id);
                        
                    // skip is_applied rrecord
                    if($result['recommended_jobs']){
                        foreach($result['recommended_jobs'] as $val){
                            if($val['is_applied'] != '0'){
                                continue;
                            }
                            // print_r($val['is_applied']);
                            $data['recommended_jobs'][] = $val;
                        }
                    }
                    // end skip code
                    
                    // Recently added jobs
                    $whereCond2 = [
                        'facilities.active' => true,
                        'jobs.is_open' => "1",
                    ];

                    $ret2 = Job::select('keywords.id as keyword_id','keywords.title as job_title','keywords.filter as job_filter','jobs.id as job_id', 'jobs.job_type as job_type', 'jobs.*')
                        ->leftJoin('facilities', function ($join) {
                            $join->on('facilities.id', '=', 'jobs.facility_id');
                        })
                        ->leftJoin('keywords', function ($join) {
                            $join->on('keywords.id', '=', 'jobs.job_type');
                        })
                        ->leftJoin('job_saved', function ($join) {
                            $join->on('job_saved.job_id', '=', 'jobs.id');
                        })
                        ->where($whereCond2)
                        ->orderBy('jobs.created_at', 'desc')->distinct('jobs.id')->limit(3);
                

                    $job_data2 = $ret2->get();
                    // $job_data2 = $ret2->paginate(10);

                    $result['recently_added'] = $this->explore_jobData($job_data2, $request->user_id);
                        
                    // skip is_applied rrecord
                    if($result['recently_added']){
                        foreach($result['recently_added'] as $val){
                            if($val['is_applied'] != '0'){
                                continue;
                            }
                            // print_r($val['is_applied']);
                            $data['recently_added'][] = $val;
                        }
                    }
                    
                    // end skip code

                    $this->check = "1";
                    $this->message = "Jobs listed successfully";
                    $this->return_data = $data;
                    // $this->return_data = $result;

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


    public function save_jobData($jobdata, $user_id = "")
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

                $j_data["offer_id"] = isset($job['offer_id']) ? $job['offer_id'] : "";
                $j_data["job_id"] = isset($job['job_id']) ? $job['job_id'] : "";
                $j_data["job_type"] = isset($job['job_type']) ? $job['job_type'] : "";
                $j_data["job_city"] = isset($job['job_city']) ? $job['job_city'] : "";
                $j_data["job_state"] = isset($job['job_state']) ? $job['job_state'] : "";
                $j_data["job_location"] = isset($job['job_location']) ? $job['job_location'] : "";
                $j_data["type"] = isset($job['type']) ? $job['type'] : "";
                $j_data["job_name"] = isset($job['job_name']) ? $job['job_name'] : "";
                $j_data["keyword_id"] = isset($job['keyword_id']) ? $job['keyword_id'] : "";
                
                // $j_data["job_location"] = isset($workLocations[$job->job_location]) ? $workLocations[$job->job_location] : "";
                $j_data["weekly_pay"] = isset($job['weekly_pay']) ? $job['weekly_pay'] : "";
                $j_data["employer_weekly_amount"] = isset($job['employer_weekly_amount']) ? $job['employer_weekly_amount'] : "";
                $j_data["hours_per_week"] = isset($job['hours_per_week']) ? $job['hours_per_week'] : 0;

                $j_data["preferred_shift"] = isset($job['preferred_shift']) ? $job['preferred_shift'] : "";
                $j_data["job_location"] = isset($job['job_location']) ? $job['job_location'] : "";
                $j_data["preferred_assignment_duration"] = isset($job['preferred_assignment_duration']) ? $job['preferred_assignment_duration'] : "";
                $j_data["preferred_work_area"] = isset($job['preferred_work_area']) ? $job['preferred_work_area'] : "";
                $j_data["preferred_days_of_the_week"] = isset($job['preferred_days_of_the_week']) ? explode(",", $job['preferred_days_of_the_week']) : [];
                $j_data["preferred_hourly_pay_rate"] = isset($job['preferred_hourly_pay_rate']) ? $job['preferred_hourly_pay_rate'] : "";
                $j_data["preferred_experience"] = isset($job['preferred_experience']) ? $job['preferred_experience'] : "";
                $j_data["description"] = isset($job['description']) ? $job['description'] : "";
                $j_data["created_at"] = isset($job['created_at']) ? date('d-F-Y h:i A', strtotime($job['created_at'])) : "";
                $j_data["created_at_definition"] = isset($job['created_at']) ? "Posted " . $this->timeAgo(date(strtotime($job['created_at']))) : "";
                $j_data["updated_at"] = isset($job->updated_at) ? date('d F Y', strtotime($job->updated_at)) : "";
                $j_data["deleted_at"] = isset($job->deleted_at) ? date('d-F-Y h:i A', strtotime($job->deleted_at)) : "";
                $j_data["created_by"] = isset($job->created_by) ? $job->created_by : "";
                $j_data["slug"] = isset($job['slug']) ? $job['slug'] : "";
                $j_data["active"] = isset($job['active']) ? $job['active'] : "";
                $j_data["facility_id"] = isset($job['facility_id']) ? $job['facility_id'] : "";
                $j_data["job_video"] = isset($job['job_video']) ? $job['job_video'] : "";

                $j_data["seniority_level"] = isset($job->seniority_level) ? $job->seniority_level : "";
                // $j_data["seniority_level_definition"] = isset($seniorityLevels[$job->seniority_level]) ? $seniorityLevels[$job->seniority_level] : "";

                $j_data["job_function"] = isset($job->job_function) ? $job->job_function : "";
                // $j_data["job_function_definition"] = isset($jobFunctions[$job->job_function]) ? $jobFunctions[$job->job_function] : "";

                $j_data["responsibilities"] = isset($job->responsibilities) ? $job->responsibilities : "";
                $j_data["qualifications"] = isset($job->qualifications) ? $job->qualifications : "";

                $j_data["job_cerner_exp"] = isset($job->job_cerner_exp) ? $job->job_cerner_exp : "";
                // $j_data["job_cerner_exp_definition"] = isset($ehrProficienciesExp[$job->job_cerner_exp]) ? $ehrProficienciesExp[$job->job_cerner_exp] : "";

                $j_data["job_meditech_exp"] = isset($job->job_meditech_exp) ? $job->job_meditech_exp : "";
                // $j_data["job_meditech_exp_definition"] = isset($ehrProficienciesExp[$job->job_meditech_exp]) ? $ehrProficienciesExp[$job->job_meditech_exp] : "";

                $j_data["job_epic_exp"] = isset($job->job_epic_exp) ? $job->job_epic_exp : "";
                // $j_data["job_epic_exp_definition"] = isset($ehrProficienciesExp[$job->job_epic_exp]) ? $ehrProficienciesExp[$job->job_epic_exp] : "";

                $j_data["job_other_exp"] = isset($job->job_other_exp) ? $job->job_other_exp : "";
                // $j_data["job_photos"] = isset($job->job_photos) ? $job->job_photos : "";
                $j_data["video_embed_url"] = isset($job->video_embed_url) ? $job->video_embed_url : "";
                $j_data["is_open"] = isset($job['is_open']) ? $job['is_open'] : "";
                $j_data["name"] = isset($job['facility']->name) ? $job['facility']->name : "";
                $j_data["address"] = isset($job->facility->address) ? $job->facility->address : "";
                $j_data["city"] = isset($job->facility->city) ? $job->facility->city : "";
                $j_data["state"] = isset($job->facility->state) ? $job->facility->state : "";
                $j_data["postcode"] = isset($job->facility->postcode) ? $job->facility->postcode : "";

                $j_data["facility_logo"] = isset($job->facility->facility_logo) ? url("public/images/facilities/" . $job->facility->facility_logo) : "";
         
                $j_data["facility_email"] = isset($job->facility->facility_email) ? $job->facility->facility_email : "";
                $j_data["facility_phone"] = isset($job->facility->facility_phone) ? $job->facility->facility_phone : "";
                $j_data["specialty_need"] = isset($job->facility->specialty_need) ? $job->facility->specialty_need : "";
                $j_data["cno_message"] = isset($job->facility->cno_message) ? $job->facility->cno_message : "";

                $j_data["cno_image"] = isset($job->facility->cno_image) ? url("public/images/facilities/cno_image".$job->facility->cno_image) : "";
                
                $j_data["gallary_images"] = isset($job->facility->gallary_images) ? $job->facility->gallary_images : "";
                $j_data["video"] = isset($job->facility->video) ? $job->facility->video : "";
                $j_data["facebook"] = isset($job->facility->facebook) ? $job->facility->facebook : "";
                $j_data["twitter"] = isset($job->facility->twitter) ? $job->facility->twitter : "";
                $j_data["linkedin"] = isset($job->facility->linkedin) ? $job->facility->linkedin : "";
                $j_data["instagram"] = isset($job->facility->instagram) ? $job->facility->instagram : "";
                $j_data["pinterest"] = isset($job->facility->pinterest) ? $job->facility->pinterest : "";
                $j_data["tiktok"] = isset($job->facility->tiktok) ? $job->facility->tiktok : "";
                $j_data["sanpchat"] = isset($job->facility->sanpchat) ? $job->facility->sanpchat : "";
                $j_data["youtube"] = isset($job->facility->youtube) ? $job->facility->youtube : "";
                $j_data["about_facility"] = isset($job->facility->about_facility) ? $job->facility->about_facility : "";
                $j_data["facility_website"] = isset($job->facility->facility_website) ? $job->facility->facility_website : "";
                $j_data["f_lat"] = isset($job->facility->f_lat) ? $job->facility->f_lat : "";
                $j_data["f_lang"] = isset($job->facility->f_lang) ? $job->facility->f_lang : "";
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

                /* total applied */
                $total_follow_count = Follows::where(['job_id' => $job['job_id'], "applied_status" => "1", 'status' => "1"])->count();
                $j_data["total_applied"] = strval($total_follow_count);
                /* total applied */

                /* liked */
                $is_applied = "0";
                if ($user_id != "")
                    $is_applied = Follows::where(['job_id' => $job['job_id'], "applied_status" => "1", 'status' => "1", "user_id" => $user_id])->distinct()->count();
                /* liked */
                $j_data["is_applied"] = strval($is_applied);

                /* liked */
                $is_liked = "0";
                if ($user_id != "")
                    $is_liked = Follows::where(['job_id' => $job['job_id'], "like_status" => "1", 'status' => "1", "user_id" => $user_id])->count();
                /* liked */
                $j_data["is_liked"] = strval($is_liked);

                // $j_data["shift"] = "Days";
                // $j_data["start_date"] = date('d F Y', strtotime($job->start_date));

                $j_data['applied_nurses'] = '0';
                $applied_nurses = Offer::where(['job_id' => $job['job_id'], 'status'=>'Apply'])->count();
                $j_data['applied_nurses'] = strval($applied_nurses);


                $is_saved = '0';
                if ($user_id != ""){
                    $nurse_info = NURSE::where('user_id', $user_id);
                    if ($nurse_info->count() > 0) {
                        $nurse = $nurse_info->first();

                        $whereCond = [
                            // 'job_saved.nurse_id' => $nurse->id,
                            'job_saved.nurse_id' => $user_id,
                            'job_saved.job_id' => $job['job_id'],
                            'job_saved.is_save' => "1",
                        ];
        
                        $limit = 10;
                        $saveret = \DB::table('job_saved')
                        ->join('jobs', 'jobs.id', '=', 'job_saved.job_id')
                        ->where($whereCond);

                        if ($saveret->count() > 0) {
                            $is_saved = 1;
                        }



                        $whereCond1 = [
                            'facilities.active' => true,
                            // 'jobs.is_open' => "1",
                            'offers.status' => 'Offered',
                            'offers.nurse_id' => $nurse->id
                        ];
    
                        $ret = Job::select('jobs.id as job_id','jobs.job_type as job_type', 'jobs.*')
                            ->leftJoin('facilities', function ($join) {
                                $join->on('facilities.id', '=', 'jobs.facility_id');
                            })
                            ->join('offers', 'jobs.id', '=', 'offers.job_id')
                            ->where($whereCond1)
                            ->orderBy('offers.created_at', 'desc');
                    
    
                        $job_data = $ret->get();
                        // $job_data = $ret->paginate(10);
    
                        $j_data['nurses_applied'] = $this->jobData($job_data, $user_id);
                    }
                }
                $j_data["is_saved"] = $is_saved;

                // $j_data["popular_jobs"] = array();
                
                // if ($user_id != ""){
                //     $whereCond = [
                //         'facilities.active' => true,
                //         'jobs.job_type' => $j_data["job_type"],
                //         'jobs.is_open' => "1"
                //     ];

                //     $ret = Job::select('keywords.title as keyword_title','jobs.id as job_id','jobs.job_type as job_type', 'jobs.*')
                //         ->leftJoin('facilities', function ($join) {
                //             $join->on('facilities.id', '=', 'jobs.facility_id');
                //         })
                //         ->leftJoin('keywords', 'jobs.job_type', '=', 'keywords.id')
                //         ->leftJoin('offers', function ($join) {
                //             $join->on('offers.job_id', '=', 'jobs.id');
                //         })
                //         ->where($whereCond)
                //         ->orderBy('jobs.created_at', 'desc');
                

                //     $job_data = $ret->paginate(10);

                //     $j_data['popular_jobs'] = $this->jobData($job_data);
                //     $num = 0;
                //     foreach($j_data['popular_jobs'] as $rec){
                        
                //         $j_data['popular_jobs'][$num]['description'] = strip_tags($rec['description']);
                //         $j_data['popular_jobs'][$num]['responsibilities'] = strip_tags($rec['responsibilities']);
                //         $j_data['popular_jobs'][$num]['qualifications'] = strip_tags($rec['qualifications']);
                //         $j_data['popular_jobs'][$num]['cno_message'] = strip_tags($rec['cno_message']);
                //         $j_data['popular_jobs'][$num]['about_facility'] = strip_tags($rec['about_facility']);
                //         $num++;
                //     }
                // }

                $result[] = $j_data;
            }
        }
        return $result;
    }

    public function my_saved_jobData($jobdata, $user_id = "")
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

                $j_data["offer_id"] = isset($job->offer_id) ? $job->offer_id : "";
                $j_data["job_id"] = isset($job->job_id) ? $job->job_id : "";
                $j_data["job_type"] = isset($job->job_type) ? $job->job_type : "";
                $j_data["city"] = isset($job->job_city) ? $job->job_city : "";
                $j_data["state"] = isset($job->job_state) ? $job->job_state : "";
                $j_data["type"] = isset($job->type) ? $job->type : "";
                $j_data["job_name"] = isset($job->job_name) ? $job->job_name : "";
                $j_data["keyword_id"] = isset($job->keyword_id) ? $job->keyword_id : "";
                
                // $j_data["job_location"] = isset($workLocations[$job->job_location]) ? $workLocations[$job->job_location] : "";
                $j_data["weekly_pay"] = isset($job->weekly_pay) ? $job->weekly_pay : "";
                $j_data["employer_weekly_amount"] = isset($job->employer_weekly_amount) ? $job->employer_weekly_amount : "";
                $j_data["hours_per_week"] = isset($job->hours_per_week) ? $job->hours_per_week : 0;

                $j_data["preferred_shift"] = isset($job->preferred_shift) ? $job->preferred_shift : "";
                $j_data["job_location"] = isset($job->job_location) ? $job->job_location : "";
                $j_data["preferred_assignment_duration"] = isset($job->preferred_assignment_duration) ? $job->preferred_assignment_duration : "";
                $j_data["preferred_work_area"] = isset($job->preferred_work_area) ? $job->preferred_work_area : "";
                $j_data["preferred_days_of_the_week"] = isset($job->preferred_days_of_the_week) ? explode(",", $job->preferred_days_of_the_week) : [];
                $j_data["preferred_hourly_pay_rate"] = isset($job->preferred_hourly_pay_rate) ? $job->preferred_hourly_pay_rate : "";
                $j_data["preferred_experience"] = isset($job->preferred_experience) ? $job->preferred_experience : "";
                $j_data["description"] = isset($job->description) ? $job->description : "";
                $j_data["created_at"] = isset($job->created_at) ? date('d-F-Y h:i A', strtotime($job->created_at)) : "";
                $j_data["created_at_definition"] = isset($job->created_at) ? "Posted " . $this->timeAgo(date(strtotime($job->created_at))) : "";
                $j_data["updated_at"] = isset($job->updated_at) ? date('d F Y', strtotime($job->updated_at)) : "";
                $j_data["deleted_at"] = isset($job->deleted_at) ? date('d-F-Y h:i A', strtotime($job->deleted_at)) : "";
                $j_data["created_by"] = isset($job->created_by) ? $job->created_by : "";
                $j_data["slug"] = isset($job->slug) ? $job->slug : "";
                $j_data["active"] = isset($job->active) ? $job->active : "";
                $j_data["facility_id"] = isset($job->facility_id) ? $job->facility_id : "";

                
                $j_data["is_open"] = isset($job->is_open) ? $job->is_open : "";
                $j_data["name"] = isset($job->facility->name) ? $job->facility->name : "";
                
                /* total applied */
                $total_follow_count = Follows::where(['job_id' => $job->job_id, "applied_status" => "1", 'status' => "1"])->count();
                $j_data["total_applied"] = strval($total_follow_count);
                /* total applied */

                /* liked */
                $is_applied = "0";
                if ($user_id != "")
                    $is_applied = Follows::where(['job_id' => $job->job_id, "applied_status" => "1", 'status' => "1", "user_id" => $user_id])->distinct()->count();
                /* liked */
                $j_data["is_applied"] = strval($is_applied);

                /* liked */
                $is_liked = "0";
                if ($user_id != "")
                    $is_liked = Follows::where(['job_id' => $job->job_id, "like_status" => "1", 'status' => "1", "user_id" => $user_id])->count();
                /* liked */
                $j_data["is_liked"] = strval($is_liked);

                // $j_data["shift"] = "Days";
                // $j_data["start_date"] = date('d F Y', strtotime($job->start_date));

                $j_data['applied_nurses'] = '0';
                $applied_nurses = Offer::where(['job_id' => $job->job_id, 'status'=>'Apply'])->count();
                $j_data['applied_nurses'] = strval($applied_nurses);


                $is_saved = '0';
                if ($user_id != ""){
                    $nurse_info = NURSE::where('user_id', $user_id);
                    if ($nurse_info->count() > 0) {
                        $nurse = $nurse_info->first();

                        $whereCond = [
                            // 'job_saved.nurse_id' => $nurse->id,
                            'job_saved.nurse_id' => $user_id,
                            'job_saved.job_id' => $job->job_id,
                            'job_saved.is_save' => "1",
                        ];
        
                        $limit = 10;
                        $saveret = \DB::table('job_saved')
                        ->join('jobs', 'jobs.id', '=', 'job_saved.job_id')
                        ->where($whereCond);

                        if ($saveret->count() > 0) {
                            $is_saved = 1;
                        }



                        $whereCond1 = [
                            'facilities.active' => true,
                            // 'jobs.is_open' => "1",
                            'offers.status' => 'Offered',
                            'offers.nurse_id' => $nurse->id
                        ];
    
                        $ret = Job::select('jobs.id as job_id','jobs.job_type as job_type', 'jobs.*')
                            ->leftJoin('facilities', function ($join) {
                                $join->on('facilities.id', '=', 'jobs.facility_id');
                            })
                            ->join('offers', 'jobs.id', '=', 'offers.job_id')
                            ->where($whereCond1)
                            ->orderBy('offers.created_at', 'desc');
                    
    
                        $job_data = $ret->get();
                        // $job_data = $ret->paginate(10);
    
                        $j_data['nurses_applied'] = $this->jobData($job_data, $user_id);
                    }
                }
                $j_data["is_saved"] = $is_saved;
                $result[] = $j_data;
            }
        }
        return $result;
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


    public function helpSupport(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
            'subject' => 'required',
            'issue' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0)
            {
                $user = $user_info->get()->first();
                $insert = array(
                    "user_id" => $request->user_id,
                    'subject' => $request->subject,
                    'issue' => $request->issue,
                    'comment_status' => "Pending for review",
                    'isPending' => "1",
                );
                
                \DB::table('help_support')->insert($insert);
                $this->check = "1";
                $this->message = "Comment submitted successfully";
                $this->return_data = "1";
            }else{
                $this->check = "1";
                $this->message = "User not found";
                $this->return_data = "0";
            }                
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getHelpComment(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0)
            {
                $user = $user_info->get()->first();
                $insert = array(
                    "user_id" => $request->user_id
                );
                
                $data = DB::table('help_support')->where($insert)->get();
                foreach($data as $val){
                    $val->created_at = isset($val->created_at) ? date('M d', strtotime($val->created_at)) : "";
                    $val->subject = isset($val->subject) ? $val->subject : "";
                    $val->issue = isset($val->issue) ? $val->issue : "";
                    $val->isPending = isset($val->isPending) ? $val->isPending : "1";
                    $val->comment_status = isset($val->comment_status) ? $val->comment_status : "";
                    $val->admin_comment = isset($val->admin_comment) ? $val->admin_comment : "";
                    $val->admin_reply_at = isset($val->admin_reply_at) ? $val->admin_reply_at : "";
                    $rec = USER::where('id', $val->user_id)->first();
                    $val->name = $rec['first_name'].' '.$rec['last_name'];
                } 
                $this->check = "1";
                $this->message = "Comment listed successfully";
                $this->return_data = $data;
            }else{
                $this->check = "1";
                $this->message = "User not found";
                $this->return_data = [];
            }                
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getHelpReplyComment(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'api_key' => 'required',
            'admin_comment' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $comment_info = DB::table('help_support')->where('id', $request->id)->get();
            if ($comment_info->count() > 0)
            {
                $update = array(
                    // 'comment_status' => isset($request->comment_status)?$request->comment_status:"",
                    'comment_status' => "Review Completed",
                    'admin_comment' => $request->admin_comment,
                    'isPending' => "0",
                    'admin_reply_at' => date('Y-m-d H:i:s')
                );
                
                \DB::table('help_support')->where('id', $request->id)->update($update);
                $this->check = "1";
                $this->message = "Admin reply submitted successfully";
                $this->return_data = "1";
            }else{
                $this->check = "1";
                $this->message = "Comment not found";
                $this->return_data = "0";
            }                
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getCommentByAdmin(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0)
            {
                $user = $user_info->get()->first();
                $insert = array(
                    "id" => $request->id
                );
                
                $val = DB::table('help_support')->where($insert)->first();
                if(isset($val)){
                    $val->created_at = isset($val->created_at) ? date('M d', strtotime($val->created_at)) : "";
                    $val->subject = isset($val->subject) ? $val->subject : "";
                    $val->issue = isset($val->issue) ? $val->issue : "";
                    $val->isPending = isset($val->isPending) ? $val->isPending : "1";
                    $val->comment_status = isset($val->comment_status) ? $val->comment_status : "";
                    $val->admin_comment = isset($val->admin_comment) ? $val->admin_comment : "";
                    $val->admin_reply_at = isset($val->admin_reply_at) ? date('M d', strtotime($val->admin_reply_at)) : "";
                    $rec = USER::where('id', $val->user_id)->first();
                    $val->name = $rec['first_name'].' '.$rec['last_name'];
                    $this->check = "1";
                    $this->message = "Admin reply Comment listed successfully";
                    $this->return_data = $val;
                } else {
                    $this->check = "1";
                    $this->message = "Comment not found";
                    $this->return_data = [];
                }
                
            }else{
                $this->check = "1";
                $this->message = "User not found";
                $this->return_data = [];
            }                
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    
    public function employers(Request $request)
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

                $facility_name = array();
                if($user->facility_id && $user->facility_id != 'null'){
                    $facility_name = Facility::whereIn('id', json_decode($user->facility_id))->pluck('name')->toArray();
                }

                $this->check = "1";
                $this->message = "Employers listed successfully";
                $this->return_data = $facility_name;
    
            }else{


                $this->check = "1";
                $this->message = "User not found";
                // $this->return_data = $result;

            }
                
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);


    }


    public function recruiterDetails(Request $request)
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
            'job_id' => 'required',
            'worker_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker = Nurse::where('id', $request->worker_id)->get()->first();
            $user = USER::where('id', $worker->user_id)->get()->first();

            if(!empty($worker))
            {
                $whereCond = [
                    'facilities.active' => true,
                    'offers.nurse_id' => $worker->id,
                    'jobs.id' => $request->job_id
                ];

                $respond = Nurse::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'keywords.filter as job_filter', 'keywords.title as job_title', 'nurses.*', 'jobs.*', 'offers.job_id as job_id', 'offers.id as offer_id', 'user_id as worker_user_id', 'offers.nurse_id as worker_id', 'users.first_name', 'users.last_name', 'users.image', 'facilities.name as employer_name',  'offers.status as offer_status', 'offers.start_date as status_date', 'offers.expiration as status_enddate')
                ->join('offers','offers.nurse_id', '=', 'nurses.id')
                ->leftJoin('users','users.id', '=', 'nurses.user_id')
                ->join('jobs', 'offers.job_id', '=', 'jobs.id')
                ->leftJoin('keywords','jobs.job_type', '=', 'keywords.id')
                ->leftJoin('facilities','jobs.facility_id', '=', 'facilities.id')
                ->where($whereCond);
                $job_data = $respond->get();
                
                foreach($job_data as $job)
                {
                    if(isset($job->license_type)){
                        $job->license_type_definition = DB::table('keywords')->where('id', '=', $job->license_type)->select('title')->first();
                        $job->license_type_definition = isset($job->license_type_definition)?$job->license_type_definition->title:'';
                    }
                    if(isset($job->worker_license_type)){
                        $job->worker_license_type_definition = DB::table('keywords')->where('id', '=', $job->worker_license_type)->select('title')->first();
                        $job->worker_license_type_definition = isset($job->worker_license_type_definition)?$job->worker_license_type_definition->title:'';
                    }
                    // Get recruiter details and name
                    if(isset($job->recruiter_id)){
                        $recruiter = DB::table('users')->where('id', '=', $job->recruiter_id)->select('first_name','last_name')->first();
                        $job->recruiter_first_name = isset($recruiter['first_name'])?$recruiter['first_name']:'';
                        $job->recruiter_last_name = isset($recruiter['last_name'])?$recruiter['last_name']:'';
                    }

                    if(isset($job->vaccinations) && !empty($job->vaccinations)){
                        $job->vaccinations = json_decode($job->vaccinations);
                        $vaccination = [];
                        $nums = 0;
                        foreach($job->vaccinations as $rec){
                            $vaccination[] = DB::table('keywords')->where('id', '=', $rec)->select('title','id')->first();
                            if(isset($vaccination[$nums]) && $vaccination[$nums]->id == 420){
                                $covid = $vaccination[$nums]->title;
                            }
                            if(isset($vaccination[$nums]) && $vaccination[$nums]->id == 421){
                                $flu = $vaccination[$nums]->title;
                            }
                            $nums++;
                        }
                        $job->vaccinations = $vaccination;
                    }

                    if(isset($job->worker_vaccination)){
                        $job->worker_vaccination = json_decode($job->worker_vaccination);
                        $worker_vaccination = [];
                        // $num = 0;
                        foreach($job->worker_vaccination as $recs){
                            $worker_vaccination[] = $recs;
                            // $num++;
                        }
                        $job->worker_vaccination = $worker_vaccination;
                    }

                    if(isset($job->certificate) && !empty($job->certificate)){
                        $job->certificate = json_decode($job->certificate);
                        $certificate = [];
                        $num_count = 0;
                        foreach($job->certificate as $result){
                            $certificate[] = DB::table('keywords')->where('id', '=', $result)->select('title','id')->first();
                            if($certificate[$num_count]->id == 43){
                                $job->ACLS = 'ACLS';
                            }
                            if($certificate[$num_count]->id == 44){
                                $job->BLS = 'BLS';
                            }
                            if($certificate[$num_count]->id == 89){
                                $job->CCRN = 'CCRN';
                            }
                            if($certificate[$num_count]->id == 193){
                                $job->PALS = 'PALS';
                            }
                            if($certificate[$num_count]->id !=  193 && $certificate[$num_count]->id !=  89 && $certificate[$num_count]->id !=  44 && $certificate[$num_count]->id !=  43){
                                $job->title = $job->title;
                            }
                            
                            $num_count++;
                        }
                        $job->certificate = $certificate;
                    }

                    if(isset($job->skills)){
                        $job->skills = json_decode($job->skills);
                        $skills = [];
                        $nums = 0;
                        foreach($job->skills as $result){
                            $skills[] = DB::table('keywords')->where('id', '=', $result)->select('title','id')->first();
                            if($skills[$nums]->id == 422){
                                $job->skills_peds = $skills[$nums]->title;
                            }
                            $nums++;
                        }
                        $job->skills = $skills;
                    }
                    
                    $job->recency_of_reference = isset($job->recency_of_reference) ? $job->recency_of_reference.' month' : '';
                    $job->start_date = isset($job->start_date) ? $job->start_date : 'As Soon As Possible';
                    $job->preferred_specialty_definition = isset($specialties[$job->preferred_specialty])  ? $specialties[$job->preferred_specialty] : "";
                    $job->worker_specialty_definition = isset($specialties[$job->specialty])  ? $specialties[$job->specialty] : "";
                    $job->preferred_shift_definition = (isset($preferredShifts[$job->preferred_shift]) &&  $preferredShifts[$job->preferred_shift] != "") ?  $preferredShifts[$job->preferred_shift] : "";
                    $job->preferred_assignment_duration_definition = isset($assignmentDurations[$job->preferred_assignment_duration]) ? $assignmentDurations[$job->preferred_assignment_duration] : "";
                    
                    if(isset($job->preferred_assignment_duration_definition)){
                        $assignment = explode(" ", $job->preferred_assignment_duration_definition);
                        $job->preferred_assignment_duration_definition = $assignment[0]; // 12 Week
                    }
                   
                    $job->preferred_work_location_definition = isset($workLocations[$job->preferred_work_location]) ? $workLocations[$job->preferred_work_location] : "";
                    // $job->total_experience = isset($job->experience_as_acute_care_facility)?$job->experience_as_acute_care_facility:""+isset($job->experience_as_ambulatory_care_facility)?$job->experience_as_ambulatory_care_facility:"";                        
                    $job->total_experience = isset($job->experience)?$job->experience:"";                        
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
                    $worker_certitficate = [];
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
                            $worker_certitficate[] = $crt_data;
                            
                        }
                    }
                    $job->worker_certitficate = $worker_certitficate;
                }
                
                $result = [];
                $result[0]['job'] = $job->job_state;
                $result[0]['worker'] = $job->state;
                $result[0]['name'] = 'location';

                $result[1]['job'] = $job->preferred_specialty_definition;
                $result[1]['worker'] = $job->worker_specialty_definition;
                $result[1]['name'] = 'speciality';

                $result[2]['job'] = $job->license_type_definition;
                $result[2]['worker'] = $job->worker_license_type_definition;
                $result[2]['name'] = 'License_type';

                $result[3]['job'] = $job->preferred_experience;
                $result[3]['worker'] = $job->total_experience;
                $result[3]['name'] = 'Experience';

                $result[4]['job'] = $job->number_of_references;
                $result[4]['worker'] = $job->worker_number_of_references;
                $result[4]['name'] = 'No_of_Reference';

                $result[5]['job'] = $job->min_title_of_reference;
                $result[5]['worker'] = $job->worker_min_title_of_reference;
                $result[5]['name'] = 'min_title_of_reference';

                $result[6]['job'] = $job->recency_of_reference;
                $result[6]['worker'] = $job->worker_recency_of_reference;
                $result[6]['name'] = 'recency_of_reference';

                $result[7]['job'] = $job->BLS;
                $result[7]['worker'] = $job->worker_BLS;
                $result[7]['name'] = 'BLS';

                $result[8]['job'] = $job->ACLS;
                $result[8]['worker'] = $job->worker_ACLS;
                $result[8]['name'] = 'ACLS';

                $result[9]['job'] = $job->PALS;
                $result[9]['worker'] = $job->worker_PALS;
                $result[9]['name'] = 'PALS';

                $result[10]['job'] = $job->CCRN;
                $result[10]['worker'] = $job->worker_CCRN;
                $result[10]['name'] = 'CCRN';

                $result[11]['job'] = $job->skills_peds;
                $result[11]['worker'] = $job->skills_checklists;
                $result[11]['name'] = 'skills_checklists';

                $result[12]['name'] = 'ss_number';
                $result[12]['worker'] = $job->worker_ss_number;

                $result[13]['job'] = $job->traveler_distance_from_facility;
                $result[13]['worker'] = $job->distance_from_your_home;
                $result[13]['name'] = 'distance';

                $result[14]['job'] = $job->facility;
                $result[14]['worker'] = $job->facilities_you_worked_at;
                $result[14]['name'] = 'facility';

                $result[15]['job'] = $job->clinical_setting;
                $result[15]['worker'] = $job->clinical_setting_you_prefer;
                $result[15]['name'] = 'clinical_setting';

                $result[16]['job'] = $job->scrub_color;
                $result[16]['worker'] = $job->worker_scrub_color;
                $result[16]['name'] = 'scrub_color';

                $result[17]['job'] = $job->facility_city;
                $result[17]['worker'] = $job->worker_facility_city;
                $result[17]['name'] = 'facility_city';

                $result[18]['job'] = $job->facility_state;
                $result[18]['worker'] = $job->worker_facility_state_code;
                $result[18]['name'] = 'facility_state';

                $result[19]['job'] = $job->start_date;
                $result[19]['worker'] = $job->worker_start_date;
                $result[19]['name'] = 'start_date';

                $result[20]['job'] = $job->rto;
                $result[20]['worker'] = $job->worker_rto;
                $result[20]['name'] = 'rto';

                $result[21]['job'] = $job->preferred_shift_definition;
                $result[21]['worker'] = $job->worker_shift_time_of_day;
                $result[21]['name'] = 'shifts';

                $result[22]['job'] = $job->hours_per_week;
                $result[22]['worker'] = $job->worker_hours_per_week;
                $result[22]['name'] = 'hours_weeks';

                $result[23]['job'] = $job->guaranteed_hours;
                $result[23]['worker'] = $job->worker_guaranteed_hours;
                $result[23]['name'] = 'guaranteed_hours';

                $result[24]['job'] = $job->hours_shift;
                $result[24]['worker'] = $job->worker_hours_shift;
                $result[24]['name'] = 'shift_hours';

                $result[25]['job'] = $job->preferred_assignment_duration_definition;
                $result[25]['worker'] = $job->worker_weeks_assignment;
                $result[25]['name'] = 'week_assignnment';

                $result[26]['job'] = $job->weeks_shift;
                $result[26]['worker'] = $job->worker_shifts_week;
                $result[26]['name'] = 'weeks_shift';

                $result[27]['job'] = $job->referral_bonus;
                $result[27]['worker'] = $job->worker_referral_bonus;
                $result[27]['name'] = 'refferel_bonus';

                $result[28]['job'] = $job->sign_on_bonus;
                $result[28]['worker'] = $job->worker_sign_on_bonus;
                $result[28]['name'] = 'signon_bonus';

                $result[29]['job'] = $job->completion_bonus;
                $result[29]['worker'] = $job->worker_completion_bonus;
                $result[29]['name'] = 'completion_bonus';

                $result[30]['job'] = $job->extension_bonus;
                $result[30]['worker'] = $job->worker_extension_bonus;
                $result[30]['name'] = 'extension_bonus';

                $result[31]['job'] = $job->other_bonus;
                $result[31]['worker'] = $job->worker_other_bonus;
                $result[31]['name'] = 'other_bonus';

                $result[32]['job'] = $job->actual_hourly_rate;
                $result[32]['worker'] = $job->worker_actual_hourly_rate;
                $result[32]['name'] = 'actual_hourly_rate';

                $result[33]['job'] = $job->feels_like_per_hour;
                $result[33]['worker'] = $job->worker_feels_like_hour;
                $result[33]['name'] = 'feels_like_per_hour';

                $result[34]['job'] = $job->orientation_rate;
                $result[34]['worker'] = $job->worker_orientation_rate;
                $result[34]['name'] = 'orientation_rate';

                $result[35]['job'] = $job->health_insaurance;
                $result[35]['worker'] = $job->worker_health_insurance;
                $result[35]['name'] = 'health_insurance';

                $result[36]['job'] = $job->weekly_taxable_amount;
                $result[36]['worker'] = $job->worker_weekly_taxable_amount;
                $result[36]['name'] = 'weekly_taxable';

                $result[37]['job'] = $job->weekly_non_taxable_amount;
                $result[37]['worker'] = $job->worker_weekly_non_taxable_amount;
                $result[37]['name'] = 'weekly_non_taxable';

                $result[38]['job'] = $job->employer_weekly_amount;
                $result[38]['worker'] = $job->worker_employer_weekly_amount;
                $result[38]['name'] = 'employer_weekly_amount';

                $result[39]['job'] = $job->goodwork_weekly_amount;
                $result[39]['worker'] = $job->worker_goodwork_weekly_amount;
                $result[39]['name'] = 'goodwork_weekly_amount';

                $result[40]['job'] = $job->total_employer_amount;
                $result[40]['worker'] = $job->worker_total_employer_amount;
                $result[40]['name'] = 'total_employer_amount';

                $result[41]['job'] = $job->total_goodwork_amount;
                $result[41]['worker'] = $job->worker_total_goodwork_amount;
                $result[41]['name'] = 'total_goodwork_amount';

                $result[42]['job'] = $job->total_contract_amount;
                $result[42]['worker'] = $job->worker_total_contract_amount;
                $result[42]['name'] = 'total_contract_amount';

                $result[43]['job'] = $job->Patient_ratio;
                $result[43]['worker'] = $job->worker_patient_ratio;
                $result[43]['name'] = 'Patient_ratio';

                $result[44]['job'] = $job->emr;
                $result[44]['worker'] = $job->worker_emr;
                $result[44]['name'] = 'emr';

                $result[45]['job'] = $job->Unit;
                $result[45]['worker'] = $job->worker_unit;
                $result[45]['name'] = 'unit';

                $result[46]['job'] = $job->Department;
                $result[46]['worker'] = $job->worker_department;
                $result[46]['name'] = 'Department';

                $result[47]['job'] = $job->goodwork_number;
                $result[47]['worker'] = $job->worker_goodwork_number;
                $result[47]['name'] = 'goodwork_number';

                $result[48]['job'] = isset($covid)?$covid:'';
                $result[48]['worker'] = $job->worker_vaccination[0];
                $result[48]['name'] = 'covid';

                $result[48]['job'] = isset($flu)?$flu:'';
                $result[48]['worker'] = $job->worker_vaccination[1];
                $result[48]['name'] = 'flu';

                $result[49]['job'] = $job->terms;
                $result[49]['worker'] = $job->worker_terms;
                $result[49]['name'] = 'terms';

                $result[50]['job'] = $job->driving_license;
                $result[50]['worker'] = $job->driving_license;
                $result[50]['name'] = 'type';

                $result[51]['job'] = '';
                $result[51]['worker'] = $job->worker_type;
                $result[51]['name'] = 'driving license';

                $result[52]['job'] = '';
                $result[52]['worker'] = $job->worked_at_facility_before;
                $result[52]['name'] = 'worked_at_here_last_timing';

                $result[53]['worker_id'] = $job->worker_id; 
                $result[53]['job_id'] = $job->job_id; 
                $result[53]['worker_user_id'] = $job->user_id; 
                $result[53]['recruiter_name'] = $job->recruiter_first_name.' '.$job->recruiter_last_name; 
                $result[53]['employer_name'] = $job->facility; 
                $result[53]['workers_applied'] = $job->workers_applied; 
                $result[53]['worker_diploma'] = $job->diploma; 
                $result[53]['posted_on'] = $job->status_date; 
                $result[53]['description'] = $job->description;
                
                $this->check = "1";
                $this->message = "Worker details listed successfully";
                $this->return_data = $result;
            }else{
                $this->check = "1";
                $this->message = "Worker Not Found";
            }
                
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function nurseOfferStatus($type, $user, $status)
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
        
        $nurse = Nurse::where('id', '=', $type)->get()->first();
        if(isset($nurse->id)){
            $offer = DB::select("SELECT * FROM `offers` WHERE nurse_id = '$nurse->id' AND status = '$status'");
            if(!empty($offer)){
                $offer = $offer[0];
            }

            $user = User::where('id', '=', $nurse->user_id)->get()->first();

        }
        
        $availability = Availability::where('nurse_id', '=', $nurse->id)->get()->first();
        
        /* Hourly rate and availability */
        $hourly_rate_and_availability = "0";
        if ((isset($nurse->hourly_pay_rate) && $nurse->hourly_pay_rate != "") &&
            (isset($availability->shift_duration) && $availability->shift_duration != "") &&
            (isset($availability->assignment_duration) && $availability->assignment_duration != "") &&
            (isset($availability->preferred_shift) && $availability->preferred_shift != "") &&
            // (isset($availability->days_of_the_week) && $availability->days_of_the_week != "") &&
            (isset($availability->earliest_start_date) && $availability->earliest_start_date != "")
        ) $hourly_rate_and_availability = "1";
        /* Hourly rate and availability */

        $return_data['user_id'] = (isset($user->id) && $user->id != "") ? $user->id : $nurse->user_id;
        $return_data['nurse_id'] = (isset($nurse->id) && $nurse->id != "") ? $nurse->id : "";
        $return_data['offer_status'] = (isset($offer->status) && $offer->status != "") ? $offer->status : "";
        $return_data['role'] = (isset($user->role) && $user->role != "") ? $user->role : "";
        $return_data['fcm_token'] = (isset($user->fcm_token) && $user->fcm_token != "") ? $user->fcm_token : "";
        $return_data['fullName'] = (isset($user->fullName) && $user->fullName != "") ? $user->fullName : "";
        $return_data['date_of_birth'] = (isset($user->date_of_birth) && $user->date_of_birth != "") ? $user->date_of_birth : "";
        $return_data['driving_license'] = (isset($user->driving_license) && $user->driving_license != "") ? $user->driving_license : "";
        $return_data['security_number'] = (isset($user->security_number) && $user->security_number != "") ? $user->security_number : "";
        $return_data['email_notification'] = (isset($user->email_notification) && $user->email_notification != "") ? strval($user->email_notification) : "";
        $return_data['sms_notification'] = (isset($user->sms_notification) && $user->sms_notification != "") ? strval($user->sms_notification) : "";

        $return_data['first_name'] = (isset($user->first_name) && $user->first_name != "") ? $user->first_name : "";
        $return_data['last_name'] = (isset($user->last_name) && $user->last_name != "") ? $user->last_name : "";
        $return_data['email'] = (isset($user->email) && $user->email != "") ? $user->email : "";

        $return_data['image'] = (isset($user->image) && $user->image != "") ? url("public/images/nurses/profile/" . $user->image) : "";

        $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
        if (isset($user->image)) {
            $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $user->image);
            if ($t) {
                $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $user->image);
            }
        }
        // $return_data["image_base"] = 'data:image/jpeg;base64,' . base64_encode($profileNurse);

        $return_data['mobile'] = (isset($user->mobile) && $user->mobile != "") ? $user->mobile : "";
        $return_data['nursing_license_state'] = (isset($nurse->nursing_license_state) && $nurse->nursing_license_state != "") ? $nurse->nursing_license_state : "";
        $return_data['nursing_license_number'] = (isset($nurse->nursing_license_number) && $nurse->nursing_license_number != "") ? $nurse->nursing_license_number : "";
        $return_data['authority_Issue'] = (isset($nurse->authority_Issue) && $nurse->authority_Issue != "") ? $nurse->authority_Issue : "";
        $return_data['highest_nursing_degree'] = (isset($nurse->highest_nursing_degree) && $nurse->highest_nursing_degree != "") ? $nurse->highest_nursing_degree : "";
        $return_data['highest_nursing_degree_definition'] = (isset($nurse->highest_nursing_degree) && $nurse->highest_nursing_degree != "") ? \App\Providers\AppServiceProvider::keywordTitle($nurse->highest_nursing_degree) : "";
        $return_data['specialty'] = $spl = [];
        if (isset($nurse->specialty) && $nurse->specialty != "") {
            $specialty_array = explode(",", $nurse->specialty);
            if (is_array($specialty_array)) {
                foreach ($specialty_array as $key => $spl_id) {
                    $spl_name = (isset($specialties[$spl_id])) ? $specialties[$spl_id] : "";
                    $spl[] = ['id' => $spl_id, 'name' => $spl_name];
                }
            }
            $return_data['specialty'] = $spl;
        }

        $return_data['license_type'] = (isset($nurse->license_type) && $nurse->license_type != "") ? strval($nurse->license_type) : "";
        $return_data['license_type_definition'] = (isset($nurse->license_type) && $nurse->license_type != "") ? \App\Providers\AppServiceProvider::keywordTitle($nurse->license_type) : "";
        $return_data['license_status'] = (isset($nurse->license_status) && $nurse->license_status != "") ? strval($nurse->license_status) : "";
        $return_data['license_status_definition'] = (isset($nurse->license_status) && $nurse->license_status != "") ? \App\Providers\AppServiceProvider::keywordTitle($nurse->license_status) : "";
        $return_data['license_expiry_date'] = (isset($nurse->license_expiry_date) && $nurse->license_expiry_date != "") ? strval($nurse->license_expiry_date) : "";
        $return_data['license_issue_date'] = (isset($nurse->license_issue_date) && $nurse->license_issue_date != "") ? strval($nurse->license_issue_date) : "";
        $return_data['license_renewal_date'] = (isset($nurse->license_renewal_date) && $nurse->license_renewal_date != "") ? strval($nurse->license_renewal_date) : "";

        $return_data['hourly_pay_rate'] = (isset($nurse->hourly_pay_rate) && $nurse->hourly_pay_rate != "") ? strval($nurse->hourly_pay_rate) : "";
        $return_data['days_of_the_week'] = [];
        
        // Education details
        $return_data['college_uni_name'] = (isset($nurse->college_uni_name) && $nurse->college_uni_name != "") ? $nurse->college_uni_name : "";
        $return_data['study_area'] = (isset($nurse->study_area) && $nurse->study_area != "") ? $nurse->study_area : "";
        $return_data['graduation_date'] = (isset($nurse->graduation_date) && $nurse->graduation_date != "") ? $nurse->graduation_date : "";

        $return_data['unavailable_dates'] = array();
        if($availability->unavailable_dates){
            $return_data['unavailable_dates'] = explode(',',$availability->unavailable_dates);
        }

        $experience = [];
        $exp = Experience::where(['nurse_id' => $nurse->id])->whereNull('deleted_at')->get();
        if ($exp->count() > 0) {
            $e = $exp;
            foreach ($e as $key => $v) {
                $crt_data['experience_id'] = (isset($v->id) && $v->id != "") ? $v->id : "";
                $crt_data['type'] = (isset($v->type) && $v->type != "") ? $v->type : "";
                $crt_data['type_definition'] = (isset($certifications[$v->type]) && $certifications[$v->type] != "") ? $certifications[$v->type] : "";
                $crt_data['position_title'] = (isset($v->position_title) && $v->position_title != "") ? $v->position_title : "";
                $crt_data['unit'] = (isset($v->unit) && $v->unit != "") ? $v->unit : "";
                $crt_data['start_date'] = (isset($v->start_date) && $v->start_date != "") ? date('m/d/Y', strtotime($v->start_date)) : "";
                $crt_data['end_date'] = (isset($v->end_date) && $v->end_date != "") ? date('m/d/Y', strtotime($v->end_date)) : "";
                $crt_data['is_current_job'] = (isset($v->is_current_job) && $v->is_current_job != "") ? $v->is_current_job : "";
                $crt_data["experience_as_acute_care_facility"] = (isset($nurse->experience_as_acute_care_facility) && $nurse->experience_as_acute_care_facility != "") ? $nurse->experience_as_acute_care_facility : "";
                $crt_data["experience_as_ambulatory_care_facility"] = (isset($nurse->experience_as_ambulatory_care_facility) && $nurse->experience_as_ambulatory_care_facility != "") ? $nurse->experience_as_ambulatory_care_facility : "";
                $exp_acute_care = isset($nurse->experience_as_acute_care_facility)? $nurse->experience_as_acute_care_facility : '0';
                $exp_ambulatory_care = isset($nurse->experience_as_ambulatory_care_facility)? $nurse->experience_as_ambulatory_care_facility : '0';
                $crt_data['total_experience'] = $exp_acute_care+$exp_ambulatory_care;
                $crt_data['total_experience'] = (int)$crt_data['total_experience'];
                $experience[] = $crt_data;
        
            }
        }
        $return_data['experience'] = $experience;
        /* experience */

        /* certitficate */
        $certitficate = [];
        $cert = Certification::where(['nurse_id' => $nurse->id])->whereNull('deleted_at')->get();
        if ($cert->count() > 0) {
            $c = $cert;
            foreach ($c as $key => $v) {
                // if ($v->deleted_at != "") {
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
                // $crt_data['certificate_image_base'] = ($certificate_image_base != "") ? 'data:image/jpeg;base64,' . base64_encode($certificate_image_base) : "";


                // $crt_data['active'] = (isset($v->active) && $v->active != "") ? $v->active : "";
                // $crt_data['deleted_at'] = (isset($v->deleted_at) && $v->deleted_at != "") ? $v->deleted_at : "";
                   $crt_data['created_at'] = (isset($v->created_at) && $v->created_at != "") ? $v->created_at : "";
                    // $crt_data['updated_at'] = (isset($v->updated_at) && $v->updated_at != "") ? $v->updated_at : ""; 
                $certitficate[] = $crt_data;
                // }
            }
        }
        $return_data['certitficate'] = $certitficate;
        $return_data['resume'] = (isset($nurse->resume) && $nurse->resume != "") ? url('storage/assets/nurses/resumes/' . $nurse->id . '/' . $nurse->resume) : "";
        /* certitficate */

        return $return_data;
    }
    
    // Remove extra charecter
    function RemoveSpecialChar($str) 
    {
        $res = str_replace( array('[', ']', '/', 'null', "\"", ","), "", $str);
        return $res;
    }

    // Payment Gateway Strip

    public function createAccount(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('stripe.sk'));
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            // 'email' => 'required',
            // 'routing_number' => 'required',
            // 'account_number' => 'required',
            'worker_user_id' => 'required'
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
                if(isset($request->account_number) && isset($request->routing_number) && isset($request->email)){
                    $account = \Stripe\Account::create([
                        'type' => 'standard',
                        'country' => 'US',
                        'email' => $request->email,
                        'external_account' => [
                            'object' => 'bank_account',
                            'country' => 'US',
                            'currency' => 'usd',
                            'account_number' => $request->account_number,
                            'routing_number' => $request->routing_number, // IFSC code
                        ],
                    ]);
                }

                $account = DB::table('nurse_account')->where([
                    'worker_user_id' => $request->worker_user_id
                ])->update([
                    'worker_user_id' => $request->worker_user_id,
                    'acc_no' => $request->account_number,
                    'routing_no' => $request->routing_number,
                    'acc_holder_name' => $request->acc_holder_name,
                    'paypal_details' => $request->paypal_details,
                    'token_id' => isset($account->id)?$account->id:NULL,
                    'email' => $request->email,
                    'acc_type' => $acc_type
                ]);

                $this->check = "1";
                $this->message = "Bank Details Update successfully";

            } else {
                if(isset($request->paypal_details)){
                    $acc_type = '1';
                }else{
                    $acc_type = '0';
                    if(isset($request->account_number) && isset($request->routing_number) && isset($request->email)){
                        $account = \Stripe\Account::create([
                            'type' => 'standard',
                            'country' => 'US',
                            'email' => $request->email,
                            'external_account' => [
                                'object' => 'bank_account',
                                'country' => 'US',
                                'currency' => 'usd',
                                'account_number' => $request->account_number,
                                'routing_number' => $request->routing_number, // IFSC code
                            ],
                        ]);
                    }
                }

                $account = DB::table('nurse_account')->insert([
                    'worker_user_id' => $request->worker_user_id,
                    'acc_no' => $request->account_number,
                    'routing_no' => $request->routing_number,
                    'acc_holder_name' => $request->acc_holder_name,
                    'paypal_details' => $request->paypal_details,
                    'email' => $request->email,
                    'token_id' => isset($account->id)?$account->id:NULL,
                    'acc_type' => $acc_type
                ]);
                
                $this->check = "1";
                $this->message = "Account Created Successfully";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $account], 200);
    }

    public function send_money(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('stripe.sk'));
        
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'amount' => 'required',
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $transfer = \Stripe\Transfer::create([
                'amount' => $request->amount, // in paisa
                'currency' => 'usd',
                'destination' => $request->id, // the connected account ID
                'transfer_group' => 'ORDER_95', // a unique identifier for the transfer
            ]);
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $transfer], 200);
    }


}
