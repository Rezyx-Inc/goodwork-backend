<?php

namespace App\Http\Controllers\Api\Employer;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Worker;
use App\Models\Availability;
use App\Models\Certification;
use App\Models\Experience;
use App\Models\Job;
use App\Models\Notification;
use App\Enums\Role;
use App\Enums\State;
use App\Models\Offer;
use App\Models\WorkerAsset;
use App\Models\Follows;
use App\Models\FacilityFollows;
use App\Models\Facility;
use App\Models\FacilityRating;
use App\Models\States;
use App\Models\Cities;
use App\Models\JobAsset;
use App\Models\JobOffer;
use App\Models\WorkerRating;
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
use DB;
use Exception;
use Twilio\Rest\Client;
use Session;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Payout;
use Stripe\PaymentIntent;
use App\Http\Controllers\Controller;
class ApiEmployerController extends Controller
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
        // $controller = new Controller();
        // $specialties = $controller->getSpecialities()->pluck('title', 'id');
        // $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
        // $shifts = $this->getShifts()->pluck('title', 'id');
        // $workLocations = $controller->getGeographicPreferences()->pluck('title', 'id');
        // $leadershipRoles = $this->getLeadershipRoles()->pluck('title', 'id');
        // $seniorityLevels = $this->getSeniorityLevel()->pluck('title', 'id');
        // $jobFunctions = $this->getJobFunction()->pluck('title', 'id');
        // $ehrProficienciesExp = $this->getEHRProficiencyExp()->pluck('title', 'id');
        // $weekDays = $this->getWeekDayOptions();
        // $nursingDegrees = $this->getNursingDegrees()->pluck('title', 'id');
        // $certifications = $this->getCertifications()->pluck('title', 'id');
        // $preferredShifts = $this->getPreferredShift()->pluck('title', 'id');
        // $experiencesTypes = $this->getExperienceTypes()->pluck('title', 'id');
        // $licenseStatus = $this->getSearchStatus()->pluck('title', 'id');
        // $licenseType = $this->getLicenseType()->pluck('title', 'id');
    }

    public function generate_token()
    {
        return hash_hmac('sha256', Str::random(40) . time(), config('app.key'));
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
        $data = [];
        foreach ($assignmentDurations as $key => $value) {
            $name = explode(" ",$value);
            // $data[] = ['id' => $key, "name" => $value];
            $data[] = ['id' => $key, "name" => $name[0]];
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

    // Not complete
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
                        if (isset($user->role) && $user->role == "WORKER") {
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
    // End Codde Not Completed

    public function sendOtp(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            // 'phone_number' => 'required|regex:/^[0-9 \+]+$/|min:4|max:20',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $facility_info = Facility::where('facility_email', $request->id)->orWhere('facility_phone',$request->id);
            if ($facility_info->count() > 0) {
                $facility = $facility_info->get()->first();
                $otp = substr(str_shuffle("0123456789"), 0, 4);
                $rand_enc = substr(str_shuffle("0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz"), 0, 6);
                $update_otp = Facility::where(['id' => $facility->id])->update(['otp' => $otp]);
                if ($update_otp) {
                    $message = "Hi <b>".$facility->name."</b>,\r\n<br><br>".
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

                    mail($facility->email, $subject, $message, $headers);

                    $this->check = "1";
                    if($facility->mobile == $request->id){
                        $this->message = "OTP send successfully to your number";
                    }else{
                        $this->message = "OTP send successfully to your email";
                    }
                    $this->return_data = ['facility_id' => $facility->id,'otp' => $otp];
                } else {
                    $this->message = "Failed to send otp, Please try again later";
                }


            } else {
                $this->message = "facility not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);


    }

    public function mobileOtp(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $facility_info = Facility::Where('facility_phone',$request->id);
            if ($facility_info->count() > 0)
            {
                $facility = $facility_info->get()->first();
                $otp = substr(str_shuffle("0123456789"), 0, 4);
                $rand_enc = substr(str_shuffle("0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz"), 0, 6);
                $update_otp = Facility::where(['id' => $facility->id])->update(['otp' => $otp]);
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
                    if($facility->facility_phone == $request->id){
                        $this->message = "OTP send successfully to your number";
                    }else{
                        $this->message = "OTP send successfully to your email";
                    }
                    $this->return_data = ['facility_id' => $facility->id,'otp' => $otp];
                } else {
                    $this->message = "Failed to send otp, Please try again later";
                }

            } else {
                $this->message = "facility not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function confirmOTP(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'facility_id' => 'required',
            'otp' => 'required|min:4|max:4',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $return_data = [];
            $facility_info = Facility::where('id', $request->facility_id);
            if ($facility_info->count() > 0) {
                $facility = $facility_info->first();
                if(isset($facility->otp) && !empty($facility->otp)){

                    if ($facility->otp == $request->otp) {
                        $update_otp = Facility::where(['id' => $facility->id])->update(['otp' => NULL, 'fcm_token' => $request->fcm_token, 'email_verified_at' => date('Y-m-d H:i:s')]);
                        if ($update_otp) {
                            // $return_data = $this->profileCompletionFlagStatus($type = "login", $facility);
                            $return_data = $facility;
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

    public function registerEmployer(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|unique:facilities,facility_email',
            'api_key' => 'required',
            'mobile' => 'nullable|unique:facilities,facility_phone'
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
                $employer_data = Facility::where('facility_email', '=', $request->email)->first();
                if ($employer_data === null) {
                    $employer = Facility::create([
                        'name' => $request->first_name.' '.$request->last_name,
                        'facility_phone' => $request->mobile,
                        'facility_email' => $request->email,
                        'fcm_token' => $request->fcm_token,

                    ]);
                    $reg_user = Facility::where('facility_email', '=', $request->email)->get()->first();
                    $userArray = array();
                    $userArray['id'] = $reg_user->id;
                    $userArray['name'] = $reg_user->name;
                    $userArray['email'] = $reg_user->facility_email;
                    $userArray['image'] = $reg_user->facility_logo;
                    $userArray['mobile'] = $reg_user->facility_phone;

                    $this->check = "1";
                    $this->message = "Your employer account has been registered successfully";
                    $this->return_data = $userArray;
                } else {
                    $this->message = "Your employer account is already created please login..!";
                }
            } else {
                $this->message = $this->param_missing;
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function applications(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'employer_id' => 'required',
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
            }
        } else {
            $facility_info = Facility::where('id', $request->employer_id);
            if ($facility_info->count() > 0) {
                $facility = $facility_info->get()->first();
                $status = ['Apply','Screening','Submitted','Offered','Draft Offer','Onboarding','Working','Done'];
                $return_data = array();
                foreach($status as $value)
                {
                    $whereCond = [
                        'jobs.facility_id' => $facility->id,
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
                $this->check = "1";
                $this->message = "Data listed";
                $this->return_data = $return_data;
            }else{
                $this->check = "1";
                $this->message = "Facility not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function employerHomeScreen(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'employer_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
            if(!isset($request->user_id)){
                $result['Applied'] = 0;
                $result['Offered'] = 0;
                $result['Onboard'] = 0;
                $result['Working'] = 0;
                $result['Done'] = 0;
                $result['Rejected'] = 0;

                $this->check = "1";
                $this->message = "Jobs listed successfully";
                $this->return_data = $result;
            }
        } else {
            $employer_info = Facility::where('id', $request->employer_id);
            if ($employer_info->count() > 0) {
                $employer = $employer_info->get()->first();

                $New = DB::table('jobs')
                                ->join('offers','jobs.id', '=', 'offers.job_id')
                                ->where(['jobs.facility_id' => $request->employer_id, 'status' => 'Apply', 'jobs.is_closed' => '0'])
                                ->select('status', DB::raw('count(*) as total'))
                                ->groupBy('offers.status')
                                ->get();

                $Offered = DB::table('jobs')
                                ->join('offers','jobs.id', '=', 'offers.job_id')
                                ->where(['jobs.facility_id' => $request->employer_id, 'status' => 'Offered', 'jobs.is_closed' => '0'])
                                ->select('status', DB::raw('count(*) as total'))
                                ->groupBy('offers.status')
                                ->get();

                $Onboard = DB::table('jobs')
                                ->join('offers','jobs.id', '=', 'offers.job_id')
                                ->where(['jobs.facility_id' => $request->employer_id, 'status' => 'Onboarding', 'jobs.is_closed' => '0'])
                                ->select('status', DB::raw('count(*) as total'))
                                ->groupBy('offers.status')
                                ->get();

                $Working = DB::table('jobs')
                                ->join('offers','jobs.id', '=', 'offers.job_id')
                                ->where(['jobs.facility_id' => $request->employer_id, 'status' => 'Working', 'jobs.is_closed' => '0'])
                                ->select('status', DB::raw('count(*) as total'))
                                ->groupBy('offers.status')
                                ->get();

                $Done = DB::table('jobs')
                                ->join('offers','jobs.id', '=', 'offers.job_id')
                                ->where(['jobs.facility_id' => $request->employer_id, 'status' => 'Done', 'jobs.is_closed' => '0'])
                                ->select('status', DB::raw('count(*) as total'))
                                ->groupBy('offers.status')
                                ->get();

                $Rejected = DB::table('jobs')
                                ->join('offers','jobs.id', '=', 'offers.job_id')
                                ->where(['jobs.facility_id' => $request->employer_id, 'status' => 'Rejected', 'jobs.is_closed' => '0'])
                                ->select('status', DB::raw('count(*) as total'))
                                ->groupBy('offers.status')
                                ->get();


                $result['Applied'] = isset($New[0])?$New[0]->total:0;
                $result['Offered'] = isset($Offered[0])?$Offered[0]->total:0;
                $result['Onboard'] = isset($Onboard[0])?$Onboard[0]->total:0;
                $result['Working'] = isset($Working[0])?$Working[0]->total:0;
                $result['Done'] = isset($Done[0])?$Done[0]->total:0;
                $result['Rejected'] = isset($Rejected[0])?$Rejected[0]->total:0;

                $this->check = "1";
                $this->message = "Jobs listed successfully";
                $this->return_data = $result;

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function employerAccountInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'employer_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $employer_info = Facility::where('id', $request->employer_id);
            if ($employer_info->count() > 0) {

                $reg_employer = $employer_info->get()->first();
                $employer_info = [];
                $employer_info['name'] = $reg_employer->name;
                $employer_info['phone'] = $reg_employer->facility_phone;
                $employer_info['email'] = $reg_employer->facility_email;
                $employer_info['image'] = $reg_employer->facility_logo;

                $this->check = "1";
                $this->message = "Account info found";
                $this->return_data = $employer_info;

            }else{
                $this->check = "1";
                $this->message = "Employer not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function employerStatusCount(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'employer_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $employer_info = Facility::where('id', $request->employer_id);
            if ($employer_info->count() > 0) {

                $employer = $employer_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'facilities.id' => $employer->id,
                    'jobs.job_type' => '412'
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*')
                    ->Join('facilities', function ($join) {
                        $join->on('facilities.id', '=', 'jobs.facility_id');
                    })
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->where($whereCond);
                $result['permanent_jobs'] = $ret->distinct('jobs.id')->count();;

                $whereCond = [
                    'facilities.active' => true,
                    'facilities.id' => $employer->id,
                    'jobs.job_type' => '413'
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*')
                    ->leftJoin('facilities', function ($join) {
                        $join->on('facilities.id', '=', 'jobs.facility_id');
                    })
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->where($whereCond);
                $result['travel_jobs'] = $ret->distinct('jobs.id')->count();;

                $whereCond = [
                    'facilities.active' => true,
                    'facilities.id' => $employer->id,
                    'jobs.job_type' => '414'
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*')
                    ->leftJoin('facilities', function ($join) {
                        $join->on('facilities.id', '=', 'jobs.facility_id');
                    })
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->where($whereCond);
                $result['perdiem_jobs'] = $ret->distinct('jobs.id')->count();;

                $whereCond = [
                    'facilities.active' => true,
                    'facilities.id' => $employer->id,
                    // 'offers.status' => 'Working',
                    'jobs.job_type' => '415'
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.*')
                    ->leftJoin('facilities', function ($join) {
                        $join->on('facilities.id', '=', 'jobs.facility_id');
                    })
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->where($whereCond);
                $result['local_jobs'] = $ret->distinct('jobs.id')->count();

                $New = DB::table('jobs')
                            ->join('facilities', function ($join) {
                                $join->on('facilities.id', '=', 'jobs.facility_id');
                            })
                            ->join('offers','jobs.id', '=', 'offers.job_id')
                            ->where(['status' => 'Apply', 'facilities.id' => $employer->id])
                            ->select('status', DB::raw('count(*) as total'))
                            ->groupBy('offers.status')
                            ->get();

                $Screening = DB::table('jobs')
                            ->join('facilities', function ($join) {
                                $join->on('facilities.id', '=', 'jobs.facility_id');
                            })
                            ->join('offers','jobs.id', '=', 'offers.job_id')
                            ->where(['status' => 'Screening', 'facilities.id' => $employer->id])
                            ->select('status', DB::raw('count(*) as total'))
                            ->groupBy('offers.status')
                            ->get();


                $Submitted = DB::table('jobs')
                            ->join('facilities', function ($join) {
                                $join->on('facilities.id', '=', 'jobs.facility_id');
                            })
                            ->join('offers','jobs.id', '=', 'offers.job_id')
                            ->where(['status' => 'Submitted', 'facilities.id' => $employer->id])
                            ->select('status', DB::raw('count(*) as total'))
                            ->groupBy('offers.status')
                            ->get();

                $Offered = DB::table('jobs')
                            ->join('facilities', function ($join) {
                                $join->on('facilities.id', '=', 'jobs.facility_id');
                            })
                            ->join('offers','jobs.id', '=', 'offers.job_id')
                            ->where(['status' => 'Offered', 'facilities.id' => $employer->id])
                            ->select('status', DB::raw('count(*) as total'))
                            ->groupBy('offers.status')
                            ->get();

                $Done = DB::table('jobs')
                            ->join('facilities', function ($join) {
                                $join->on('facilities.id', '=', 'jobs.facility_id');
                            })
                            ->join('offers','jobs.id', '=', 'offers.job_id')
                            ->where(['status' => 'Done', 'facilities.id' => $employer->id])
                            ->select('status', DB::raw('count(*) as total'))
                            ->groupBy('offers.status')
                            ->get();

                $Onboard = DB::table('jobs')
                            ->join('facilities', function ($join) {
                                $join->on('facilities.id', '=', 'jobs.facility_id');
                            })
                            ->join('offers','jobs.id', '=', 'offers.job_id')
                            ->where(['status' => 'Onboarding', 'facilities.id' => $employer->id])
                            ->select('status', DB::raw('count(*) as total'))
                            ->groupBy('offers.status')
                            ->get();

                $Working = DB::table('jobs')
                            ->join('facilities', function ($join) {
                                $join->on('facilities.id', '=', 'jobs.facility_id');
                            })
                            ->join('offers','jobs.id', '=', 'offers.job_id')
                            ->where(['status' => 'Working', 'facilities.id' => $employer->id])
                            ->select('status', DB::raw('count(*) as total'))
                            ->groupBy('offers.status')
                            ->get();

                $Rejected = DB::table('jobs')
                            ->join('facilities', function ($join) {
                                $join->on('facilities.id', '=', 'jobs.facility_id');
                            })
                            ->join('offers','jobs.id', '=', 'offers.job_id')
                            ->where(['status' => 'Rejected', 'facilities.id' => $employer->id])
                            ->select('status', DB::raw('count(*) as total'))
                            ->groupBy('offers.status')
                            ->get();

                $Blocked = DB::table('jobs')
                            ->join('facilities', function ($join) {
                                $join->on('facilities.id', '=', 'jobs.facility_id');
                            })
                            ->join('offers','jobs.id', '=', 'offers.job_id')
                            ->where(['status' => 'Blocked', 'facilities.id' => $employer->id])
                            ->select('status', DB::raw('count(*) as total'))
                            ->groupBy('offers.status')
                            ->get();

                $result['New'] = isset($New[0])?$New[0]->total:0;
                $result['Screening'] = isset($Screening[0])?$Screening[0]->total:0;
                $result['Submitted'] = isset($Submitted[0])?$Submitted[0]->total:0;
                $result['Offered'] = isset($Offered[0])?$Offered[0]->total:0;
                $result['Onboard'] = isset($Onboard[0])?$Onboard[0]->total:0;
                $result['Working'] = isset($Working[0])?$Working[0]->total:0;
                $result['Done'] = isset($Done[0])?$Done[0]->total:0;
                $result['Rejected'] = isset($Rejected[0])?$Rejected[0]->total:0;
                $result['Blocked'] = isset($Blocked[0])?$Blocked[0]->total:0;

                $this->check = "1";
                $this->message = "Jobs listed successfully";
                $this->return_data = $result;

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function employerNewList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'employer_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $facility_info = Facility::where('id', $request->employer_id);
            if ($facility_info->count() > 0) {
                $facility = $facility_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.facility_id' => $facility->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Apply'
                ];

                $ret = Facility::select('jobs.id as job_id', 'jobs.*','offers.id as offer_id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'workers.*', 'offers.created_at as created_at', 'facilities.name as facility_name')
                    ->join('jobs', 'facilities.id', '=', 'jobs.facility_id')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('workers', 'offers.worker_id', '=', 'workers.id')
                    ->join('users', 'workers.user_id', '=', 'users.id')

                    ->where($whereCond)
                    // ->orderBy('offers.created_at', 'desc')
                ->orderBy('offers.worker_id', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $rec)
                {
                    $result['worker_id'] = $rec['id'];
                    $result['worker_user_id'] = $rec['user_id'];
                    $result['job_id'] = $rec['job_id'];
                    $result['worker_image'] = isset($rec['worker_image'])? url("public/images/workers/profile/" . $rec['worker_image']):"";
                    $result['worker_name'] = isset($rec['first_name'])?$rec['first_name'].' '.$rec['last_name']:"";
                    $result['job_name'] = isset($rec['job_name'])?$rec['job_name']:"";
                    $result['facility_name'] = isset($rec['facility_name'])?$rec['facility_name']:"";
                    $result['preferred_assignment_duration'] = isset($rec['worker_weeks_assignment'])?$rec['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($rec['worker_shift_time_of_day'])?$rec['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($rec['highest_nursing_degree'])?$rec['highest_nursing_degree']:"";
                    $result['specialty'] = isset($rec['specialty'])?$rec['specialty']:"";
                    $result['experience'] = isset($rec['experience'])?$rec['experience'].' Years of Experience':"";
                    $time_difference = time() - strtotime($rec['created_at']);
                    if($time_difference > 3599){
                        $result['recently'] = isset($rec['created_at']) ?$this->timeAgo(date(strtotime($rec['created_at']))) : "";
                    }else{
                        $result['recently'] = isset($rec['created_at']) ?'Recently Added' : "";
                    }
                    $record[] =  $result;
                }
                $this->check = "1";
                $this->message = "Data listed successfully";
                $this->return_data = $record;

            }else{
                $this->check = "1";
                $this->message = "Employer not found";

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function employerScreeningList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'employer_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $facility_info = Facility::where('id', $request->employer_id);
            if ($facility_info->count() > 0) {
                $facility = $facility_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.facility_id' => $facility->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Screening'
                ];

                $ret = Facility::select('jobs.id as job_id', 'jobs.*','offers.id as offer_id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'workers.*', 'offers.created_at as created_at', 'facilities.name as facility_name')
                    ->join('jobs', 'facilities.id', '=', 'jobs.facility_id')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('workers', 'offers.worker_id', '=', 'workers.id')
                    ->join('users', 'workers.user_id', '=', 'users.id')

                    ->where($whereCond)
                    // ->orderBy('offers.created_at', 'desc')
                ->orderBy('offers.worker_id', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $rec)
                {
                    $result['worker_id'] = $rec['id'];
                    $result['worker_user_id'] = $rec['user_id'];
                    $result['job_id'] = $rec['job_id'];
                    $result['worker_image'] = isset($rec['worker_image'])? url("public/images/workers/profile/" . $rec['worker_image']):"";
                    $result['worker_name'] = isset($rec['first_name'])?$rec['first_name'].' '.$rec['last_name']:"";
                    $result['job_name'] = isset($rec['job_name'])?$rec['job_name']:"";
                    $result['facility_name'] = isset($rec['facility_name'])?$rec['facility_name']:"";
                    $result['preferred_assignment_duration'] = isset($rec['worker_weeks_assignment'])?$rec['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($rec['worker_shift_time_of_day'])?$rec['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($rec['highest_nursing_degree'])?$rec['highest_nursing_degree']:"";
                    $result['specialty'] = isset($rec['specialty'])?$rec['specialty']:"";
                    $result['experience'] = isset($rec['experience'])?$rec['experience'].' Years of Experience':"";
                    $time_difference = time() - strtotime($rec['created_at']);
                    if($time_difference > 3599){
                        $result['recently'] = isset($rec['created_at']) ?$this->timeAgo(date(strtotime($rec['created_at']))) : "";
                    }else{
                        $result['recently'] = isset($rec['created_at']) ?'Recently Added' : "";
                    }
                    $record[] =  $result;
                }
                $this->check = "1";
                $this->message = "Data listed successfully";
                $this->return_data = $record;

            }else{
                $this->check = "1";
                $this->message = "Employer not found";

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function employerSubmittedList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'employer_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $facility_info = Facility::where('id', $request->employer_id);
            if ($facility_info->count() > 0) {
                $facility = $facility_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.facility_id' => $facility->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Submitted'
                ];

                $ret = Facility::select('jobs.id as job_id', 'jobs.*','offers.id as offer_id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'workers.*', 'offers.created_at as created_at', 'facilities.name as facility_name')
                    ->join('jobs', 'facilities.id', '=', 'jobs.facility_id')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('workers', 'offers.worker_id', '=', 'workers.id')
                    ->join('users', 'workers.user_id', '=', 'users.id')

                    ->where($whereCond)
                    // ->orderBy('offers.created_at', 'desc')
                ->orderBy('offers.worker_id', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $rec)
                {
                    $result['worker_id'] = $rec['id'];
                    $result['worker_user_id'] = $rec['user_id'];
                    $result['job_id'] = $rec['job_id'];
                    $result['worker_image'] = isset($rec['worker_image'])? url("public/images/workers/profile/" . $rec['worker_image']):"";
                    $result['worker_name'] = isset($rec['first_name'])?$rec['first_name'].' '.$rec['last_name']:"";
                    $result['job_name'] = isset($rec['job_name'])?$rec['job_name']:"";
                    $result['facility_name'] = isset($rec['facility_name'])?$rec['facility_name']:"";
                    $result['preferred_assignment_duration'] = isset($rec['worker_weeks_assignment'])?$rec['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($rec['worker_shift_time_of_day'])?$rec['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($rec['highest_nursing_degree'])?$rec['highest_nursing_degree']:"";
                    $result['specialty'] = isset($rec['specialty'])?$rec['specialty']:"";
                    $result['experience'] = isset($rec['experience'])?$rec['experience'].' Years of Experience':"";
                    $time_difference = time() - strtotime($rec['created_at']);
                    if($time_difference > 3599){
                        $result['recently'] = isset($rec['created_at']) ?$this->timeAgo(date(strtotime($rec['created_at']))) : "";
                    }else{
                        $result['recently'] = isset($rec['created_at']) ?'Recently Added' : "";
                    }
                    $record[] =  $result;
                }
                $this->check = "1";
                $this->message = "Data listed successfully";
                $this->return_data = $record;

            }else{
                $this->check = "1";
                $this->message = "Employer not found";

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function employerOffredList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'employer_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $facility_info = Facility::where('id', $request->employer_id);
            if ($facility_info->count() > 0) {
                $facility = $facility_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.facility_id' => $facility->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Offered'
                ];

                $ret = Facility::select('jobs.id as job_id', 'jobs.*','offers.id as offer_id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'workers.*', 'offers.created_at as created_at', 'facilities.name as facility_name')
                    ->join('jobs', 'facilities.id', '=', 'jobs.facility_id')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('workers', 'offers.worker_id', '=', 'workers.id')
                    ->join('users', 'workers.user_id', '=', 'users.id')

                    ->where($whereCond)
                    // ->orderBy('offers.created_at', 'desc')
                ->orderBy('offers.worker_id', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $rec)
                {
                    $result['worker_id'] = $rec['id'];
                    $result['worker_user_id'] = $rec['user_id'];
                    $result['job_id'] = $rec['job_id'];
                    $result['worker_image'] = isset($rec['worker_image'])? url("public/images/workers/profile/" . $rec['worker_image']):"";
                    $result['worker_name'] = isset($rec['first_name'])?$rec['first_name'].' '.$rec['last_name']:"";
                    $result['job_name'] = isset($rec['job_name'])?$rec['job_name']:"";
                    $result['facility_name'] = isset($rec['facility_name'])?$rec['facility_name']:"";
                    $result['preferred_assignment_duration'] = isset($rec['worker_weeks_assignment'])?$rec['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($rec['worker_shift_time_of_day'])?$rec['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($rec['highest_nursing_degree'])?$rec['highest_nursing_degree']:"";
                    $result['specialty'] = isset($rec['specialty'])?$rec['specialty']:"";
                    $result['experience'] = isset($rec['experience'])?$rec['experience'].' Years of Experience':"";
                    $time_difference = time() - strtotime($rec['created_at']);
                    if($time_difference > 3599){
                        $result['recently'] = isset($rec['created_at']) ?$this->timeAgo(date(strtotime($rec['created_at']))) : "";
                    }else{
                        $result['recently'] = isset($rec['created_at']) ?'Recently Added' : "";
                    }
                    $record[] =  $result;
                }
                $this->check = "1";
                $this->message = "Data listed successfully";
                $this->return_data = $record;

            }else{
                $this->check = "1";
                $this->message = "Employer not found";

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function employerDoneList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'employer_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $facility_info = Facility::where('id', $request->employer_id);
            if ($facility_info->count() > 0) {
                $facility = $facility_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.facility_id' => $facility->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Done'
                ];

                $ret = Facility::select('jobs.id as job_id', 'jobs.*','offers.id as offer_id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'workers.*', 'offers.created_at as created_at', 'facilities.name as facility_name')
                    ->join('jobs', 'facilities.id', '=', 'jobs.facility_id')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('workers', 'offers.worker_id', '=', 'workers.id')
                    ->join('users', 'workers.user_id', '=', 'users.id')

                    ->where($whereCond)
                    // ->orderBy('offers.created_at', 'desc')
                ->orderBy('offers.worker_id', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $rec)
                {
                    $result['worker_id'] = $rec['id'];
                    $result['worker_user_id'] = $rec['user_id'];
                    $result['job_id'] = $rec['job_id'];
                    $result['worker_image'] = isset($rec['worker_image'])? url("public/images/workers/profile/" . $rec['worker_image']):"";
                    $result['worker_name'] = isset($rec['first_name'])?$rec['first_name'].' '.$rec['last_name']:"";
                    $result['job_name'] = isset($rec['job_name'])?$rec['job_name']:"";
                    $result['facility_name'] = isset($rec['facility_name'])?$rec['facility_name']:"";
                    $result['preferred_assignment_duration'] = isset($rec['worker_weeks_assignment'])?$rec['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($rec['worker_shift_time_of_day'])?$rec['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($rec['highest_nursing_degree'])?$rec['highest_nursing_degree']:"";
                    $result['specialty'] = isset($rec['specialty'])?$rec['specialty']:"";
                    $result['experience'] = isset($rec['experience'])?$rec['experience'].' Years of Experience':"";
                    $time_difference = time() - strtotime($rec['created_at']);
                    if($time_difference > 3599){
                        $result['recently'] = isset($rec['created_at']) ?$this->timeAgo(date(strtotime($rec['created_at']))) : "";
                    }else{
                        $result['recently'] = isset($rec['created_at']) ?'Recently Added' : "";
                    }
                    $record[] =  $result;
                }
                $this->check = "1";
                $this->message = "Data listed successfully";
                $this->return_data = $record;

            }else{
                $this->check = "1";
                $this->message = "Employer not found";

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function employerOnboardingList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'employer_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $facility_info = Facility::where('id', $request->employer_id);
            if ($facility_info->count() > 0) {
                $facility = $facility_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.facility_id' => $facility->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Onboarding'
                ];

                $ret = Facility::select('jobs.id as job_id', 'jobs.*','offers.id as offer_id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'workers.*', 'offers.created_at as created_at', 'facilities.name as facility_name')
                    ->join('jobs', 'facilities.id', '=', 'jobs.facility_id')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('workers', 'offers.worker_id', '=', 'workers.id')
                    ->join('users', 'workers.user_id', '=', 'users.id')

                    ->where($whereCond)
                    // ->orderBy('offers.created_at', 'desc')
                ->orderBy('offers.worker_id', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $rec)
                {
                    $result['worker_id'] = $rec['id'];
                    $result['worker_user_id'] = $rec['user_id'];
                    $result['job_id'] = $rec['job_id'];
                    $result['worker_image'] = isset($rec['worker_image'])? url("public/images/workers/profile/" . $rec['worker_image']):"";
                    $result['worker_name'] = isset($rec['first_name'])?$rec['first_name'].' '.$rec['last_name']:"";
                    $result['job_name'] = isset($rec['job_name'])?$rec['job_name']:"";
                    $result['facility_name'] = isset($rec['facility_name'])?$rec['facility_name']:"";
                    $result['preferred_assignment_duration'] = isset($rec['worker_weeks_assignment'])?$rec['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($rec['worker_shift_time_of_day'])?$rec['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($rec['highest_nursing_degree'])?$rec['highest_nursing_degree']:"";
                    $result['specialty'] = isset($rec['specialty'])?$rec['specialty']:"";
                    $result['experience'] = isset($rec['experience'])?$rec['experience'].' Years of Experience':"";
                    $time_difference = time() - strtotime($rec['created_at']);
                    if($time_difference > 3599){
                        $result['recently'] = isset($rec['created_at']) ?$this->timeAgo(date(strtotime($rec['created_at']))) : "";
                    }else{
                        $result['recently'] = isset($rec['created_at']) ?'Recently Added' : "";
                    }
                    $record[] =  $result;
                }
                $this->check = "1";
                $this->message = "Data listed successfully";
                $this->return_data = $record;

            }else{
                $this->check = "1";
                $this->message = "Employer not found";

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function employerWorkingList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'employer_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $facility_info = Facility::where('id', $request->employer_id);
            if ($facility_info->count() > 0) {
                $facility = $facility_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.facility_id' => $facility->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Working'
                ];

                $ret = Facility::select('jobs.id as job_id', 'jobs.*','offers.id as offer_id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'workers.*', 'offers.created_at as created_at', 'facilities.name as facility_name')
                    ->join('jobs', 'facilities.id', '=', 'jobs.facility_id')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('workers', 'offers.worker_id', '=', 'workers.id')
                    ->join('users', 'workers.user_id', '=', 'users.id')

                    ->where($whereCond)
                    // ->orderBy('offers.created_at', 'desc')
                ->orderBy('offers.worker_id', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $rec)
                {
                    $result['worker_id'] = $rec['id'];
                    $result['worker_user_id'] = $rec['user_id'];
                    $result['job_id'] = $rec['job_id'];
                    $result['worker_image'] = isset($rec['worker_image'])? url("public/images/workers/profile/" . $rec['worker_image']):"";
                    $result['worker_name'] = isset($rec['first_name'])?$rec['first_name'].' '.$rec['last_name']:"";
                    $result['job_name'] = isset($rec['job_name'])?$rec['job_name']:"";
                    $result['facility_name'] = isset($rec['facility_name'])?$rec['facility_name']:"";
                    $result['preferred_assignment_duration'] = isset($rec['worker_weeks_assignment'])?$rec['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($rec['worker_shift_time_of_day'])?$rec['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($rec['highest_nursing_degree'])?$rec['highest_nursing_degree']:"";
                    $result['specialty'] = isset($rec['specialty'])?$rec['specialty']:"";
                    $result['experience'] = isset($rec['experience'])?$rec['experience'].' Years of Experience':"";
                    $time_difference = time() - strtotime($rec['created_at']);
                    if($time_difference > 3599){
                        $result['recently'] = isset($rec['created_at']) ?$this->timeAgo(date(strtotime($rec['created_at']))) : "";
                    }else{
                        $result['recently'] = isset($rec['created_at']) ?'Recently Added' : "";
                    }
                    $record[] =  $result;
                }
                $this->check = "1";
                $this->message = "Data listed successfully";
                $this->return_data = $record;

            }else{
                $this->check = "1";
                $this->message = "Employer not found";

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function employerRejectedList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'employer_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $facility_info = Facility::where('id', $request->employer_id);
            if ($facility_info->count() > 0) {
                $facility = $facility_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.facility_id' => $facility->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Rejected'
                ];

                $ret = Facility::select('jobs.id as job_id', 'jobs.*','offers.id as offer_id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'workers.*', 'offers.created_at as created_at', 'facilities.name as facility_name')
                    ->join('jobs', 'facilities.id', '=', 'jobs.facility_id')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('workers', 'offers.worker_id', '=', 'workers.id')
                    ->join('users', 'workers.user_id', '=', 'users.id')

                    ->where($whereCond)
                    // ->orderBy('offers.created_at', 'desc')
                ->orderBy('offers.worker_id', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $rec)
                {
                    $result['worker_id'] = $rec['id'];
                    $result['worker_user_id'] = $rec['user_id'];
                    $result['job_id'] = $rec['job_id'];
                    $result['worker_image'] = isset($rec['worker_image'])? url("public/images/workers/profile/" . $rec['worker_image']):"";
                    $result['worker_name'] = isset($rec['first_name'])?$rec['first_name'].' '.$rec['last_name']:"";
                    $result['job_name'] = isset($rec['job_name'])?$rec['job_name']:"";
                    $result['facility_name'] = isset($rec['facility_name'])?$rec['facility_name']:"";
                    $result['preferred_assignment_duration'] = isset($rec['worker_weeks_assignment'])?$rec['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($rec['worker_shift_time_of_day'])?$rec['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($rec['highest_nursing_degree'])?$rec['highest_nursing_degree']:"";
                    $result['specialty'] = isset($rec['specialty'])?$rec['specialty']:"";
                    $result['experience'] = isset($rec['experience'])?$rec['experience'].' Years of Experience':"";
                    $time_difference = time() - strtotime($rec['created_at']);
                    if($time_difference > 3599){
                        $result['recently'] = isset($rec['created_at']) ?$this->timeAgo(date(strtotime($rec['created_at']))) : "";
                    }else{
                        $result['recently'] = isset($rec['created_at']) ?'Recently Added' : "";
                    }
                    $record[] =  $result;
                }
                $this->check = "1";
                $this->message = "Data listed successfully";
                $this->return_data = $record;

            }else{
                $this->check = "1";
                $this->message = "Employer not found";

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function employerBlockedList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'employer_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $facility_info = Facility::where('id', $request->employer_id);
            if ($facility_info->count() > 0) {
                $facility = $facility_info->get()->first();

                $whereCond = [
                    'facilities.active' => true,
                    'jobs.facility_id' => $facility->id,
                    'jobs.is_closed' => "0",
                    'offers.status' => 'Blocked'
                ];

                $ret = Facility::select('jobs.id as job_id', 'jobs.*','offers.id as offer_id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.image as worker_image', 'workers.*', 'offers.updated_at as updated_at', 'facilities.name as facility_name')
                    ->join('jobs', 'facilities.id', '=', 'jobs.facility_id')
                    ->join('offers', 'jobs.id', '=', 'offers.job_id')
                    ->join('workers', 'offers.worker_id', '=', 'workers.id')
                    ->join('users', 'workers.user_id', '=', 'users.id')

                    ->where($whereCond)
                    // ->orderBy('offers.created_at', 'desc')
                ->orderBy('offers.worker_id', 'desc');
                $job_data = $ret->get();

                $result = [];
                $record = [];
                foreach($job_data as $rec)
                {
                    $result['worker_id'] = $rec['id'];
                    $result['worker_user_id'] = $rec['user_id'];
                    $result['job_id'] = $rec['job_id'];
                    $result['worker_image'] = isset($rec['worker_image'])? url("public/images/workers/profile/" . $rec['worker_image']):"";
                    $result['worker_name'] = isset($rec['first_name'])?$rec['first_name'].' '.$rec['last_name']:"";
                    $result['job_name'] = isset($rec['job_name'])?$rec['job_name']:"";
                    $result['facility_name'] = isset($rec['facility_name'])?$rec['facility_name']:"";
                    $result['preferred_assignment_duration'] = isset($rec['worker_weeks_assignment'])?$rec['worker_weeks_assignment']:"";
                    $result['preferred_shift'] = isset($rec['worker_shift_time_of_day'])?$rec['worker_shift_time_of_day']:"";
                    $result['profession'] = isset($rec['highest_nursing_degree'])?$rec['highest_nursing_degree']:"";
                    $result['specialty'] = isset($rec['specialty'])?$rec['specialty']:"";
                    $result['experience'] = isset($rec['experience'])?$rec['experience'].' Years of Experience':"";
                    $time_difference = time() - strtotime($rec['updated_at']);
                    if($time_difference > 3599){
                        $result['recently'] = isset($rec['updated_at']) ?$this->timeAgo(date(strtotime($rec['updated_at']))) : "";
                    }else{
                        $result['recently'] = isset($rec['updated_at']) ?'Recently Added' : "";
                    }
                    $record[] =  $result;
                }
                $this->check = "1";
                $this->message = "Data listed successfully";
                $this->return_data = $record;

            }else{
                $this->check = "1";
                $this->message = "Employer not found";

            }

        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function aboutEmployer(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'employer_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $whereCond = [
                'facilities.id' => $request->employer_id,
            ];
            $record = [];
            $result = DB::table('facilities')
                                ->where($whereCond)
                                ->select('facilities.cno_message as about_me','facilities.specialty_need as qualities','facilities.name as Agency_name', 'facilities.*')
                                ->first();

                $record['qualities'] = (isset($result->qualities) && $result->qualities != "") ? json_decode($result->qualities) : [];
                $record['about_me'] = (isset($result->about_me) && $result->about_me != "") ? strip_tags($result->about_me) : "";
                $record['Agency_name'] = (isset($result->Agency_name) && $result->Agency_name != "") ? strip_tags($result->Agency_name) : "";
                $record['instagram'] = (isset($result->instagram) && $result->instagram != "") ? strip_tags($result->instagram) : "";
                $record['facebook'] = (isset($result->facebook) && $result->facebook != "") ? strip_tags($result->facebook) : "";
                $record['twitter'] = (isset($result->twitter) && $result->twitter != "") ? strip_tags($result->twitter) : "";
                $record['linkedin'] = (isset($result->linkedin) && $result->linkedin != "") ? strip_tags($result->linkedin) : "";
                $record['facility_id'] = (isset($result->id) && $result->id != "") ? strip_tags($result->id) : "";
                $record['image'] = (isset($result->cno_image) && $result->cno_image != "") ? url("public/images/workers/profile/" . $result->cno_image) : "";


            $return_data = $record;
            $this->message = "Recruter listed succcessfully";
            $this->check = "1";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $return_data], 200);
    }

    function workerInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'worker_id' => 'required',
            'api_key' => 'required',
            'employer_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker = Worker::where('workers.id', $request->worker_id)
                    ->leftJoin('offers','offers.worker_id', '=', 'workers.id')
                    ->select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.worker_id=workers.id) as workers_applied"),'workers.*')
                    ->first();
            $worker_reference = WORKER::select('worker_references.name','worker_references.min_title_of_reference','worker_references.recency_of_reference')
                            ->leftJoin('worker_references','worker_references.worker_id', '=', 'workers.id')
                            ->where('workers.id', $worker['id'])->get();
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

            if(isset($worker['id'])){
                $Worker_user = User::where(['id' => $worker['user_id']])->first();
                $result = [];
                $result['worker_name'] = $Worker_user['first_name'].' '.$Worker_user['last_name'];
                $result['worker_image'] = isset($Worker_user['image'])?url("public/images/workers/profile/".$Worker_user['image']):'';
                $result['worker_id'] = $worker['id'];
                $result['worker_user_id'] = $Worker_user['id'];
                $result['employer_id'] = $request->employer_id;
                $result['applied_jobs'] = $worker['workers_applied'];

                $worker_info = [];
                $worker_vacc = [];
                $worker_cert = [];
                $data['worker'] = !empty($worker['highest_nursing_degree'])?$worker['highest_nursing_degree']:"";
                $data['name'] = 'Profession';
                $data['worker1'] = !empty($worker['specialty'])?$worker['specialty']:"";
                $data['name1'] = 'Speciality';
                $worker_info[] = $data;

                $data['worker'] = !empty($worker['nursing_license_state'])?$worker['nursing_license_state']:"";
                $data['name'] = 'Professional Licensure';
                $data['worker1'] = !empty($worker['experience'])?$worker['experience']:"";
                $data['name1'] = 'Experience';
                $worker_info[] = $data;

                $data['worker'] = isset($worker_number_of_references)?$worker_number_of_references:"";
                $data['name'] = 'Number Of References';
                $data['worker1'] = isset($worker_reference_title)?$worker_reference_title:"";
                $data['name1'] = 'Min Title Of References';
                $worker_info[] = $data;

                $data['worker'] = isset($worker_reference_recency_reference)?$worker_reference_recency_reference:"";
                $data['name'] = 'Recency Of Reference';
                $data['worker1'] = isset($worker['skills_checklists'])?url("public/images/workers/skill/".$worker['skills_checklists']):"";
                $data['name1'] = 'Skills Checklist';
                $worker_info[] = $data;

                $data['worker'] = isset($worker['eligible_work_in_us'])?$worker['eligible_work_in_us']:"";
                $data['name'] = 'Eligible To Work In The US';
                $data['worker1'] = isset($worker['worker_urgency'])?$worker['worker_urgency']:"";
                $data['name1'] = 'Urgency';
                $worker_info[] = $data;

                $data['worker'] = isset($worker['traveler_distance_from_facility'])?$worker['traveler_distance_from_facility']:"";
                $data['name'] = 'Traveler Distance from facility';
                $data['worker1'] = isset($worker['facilities_you_like_to_work_at'])?$worker['facilities_you_like_to_work_at']:"";
                $data['name1'] = 'Facility';
                $worker_info[] = $data;

                $data['worker'] = isset($worker['state'])?$worker['state']:"";
                $data['name'] = 'Location';
                $data['worker1'] = isset($worker['worker_shift_time_of_day'])?$worker['worker_shift_time_of_day']:"";
                $data['name1'] = 'Shift';
                $worker_info[] = $data;

                $data['worker'] = isset($worker['distance_from_your_home'])?$worker['distance_from_your_home']:"";
                $data['name'] = 'Distance from your home';
                $data['worker1'] = isset($worker['worked_at_facility_before'])?$worker['worked_at_facility_before']:"";
                $data['name1'] = "Facilities you've worket at";
                $worker_info[] = $data;

                $data['worker'] = isset($worker['worker_facility_city'])?$worker['worker_facility_state_code']:"";
                $data['name'] = 'Facility City';
                $data['worker1'] = isset($worker['worker_start_date'])?$worker['worker_start_date']:"";
                $data['name1'] = 'Start Date';
                $worker_info[] = $data;


                $data['worker'] = "";
                $data['name'] = 'RTO';
                $data['worker1'] = isset($worker['worker_shift_time_of_day'])?$worker['worker_shift_time_of_day']:"";
                $data['name1'] = 'Shift Time of Day';
                $worker_info[] = $data;

                $data['worker'] = isset($worker['worker_weeks_assignment'])?$worker['worker_weeks_assignment']:"";
                $data['name'] = 'Weeks/Assignment';
                $data['worker1'] = isset($worker['worker_employer_weekly_amount'])?$worker['worker_employer_weekly_amount']:"";
                $data['name1'] = 'Employer Weekly Amount';
                $worker_info[] = $data;


                $data['worker'] = isset($worker['worker_goodwork_number'])?$worker['worker_goodwork_number']:"";
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
                        $data['worker'] = isset($worker_vaccination[$i])?$worker_vaccination[$i]:"";
                        $data['name'] = 'Vaccinations & Immunications '.$worker_vaccination[$i];
                        $worker_info[] = $data;
                        $i++;
                    }
                }else{
                    $data['worker'] = '';
                    $data['name'] = 'Vaccinations & Immunications ';
                    $worker_info[] = $data;
                }

                $data['worker'] = '';
                $data['name'] = '';
                $data['worker1'] = '';
                $data['name1'] = '';
                $i = 0;
                if(isset($worker_certificate_name)){
                    foreach($worker_certificate_name as $job_vacc)
                    {
                        $data['worker'] = isset($worker_certificate_name[$i])?$worker_certificate_name[$i]:"";
                        $data['name'] = 'Certification Name';
                        $worker_info[] = $data;
                        $i++;
                    }
                }else{
                    $data['worker'] = '';
                    $data['name'] = 'Certification Name';
                    $worker_info[] = $data;
                }

                $result['worker_info'] = $worker_info;
                // $result['worker_certificate'] = $worker_cert;
                // $result['worker_vaccination'] = $worker_vacc;

                if(isset($result)){
                    $this->check = '1';
                    $this->message = 'Worker details fetched Successfully!';
                    $this->return_data = $result;
                }
            }else{
                $this->check = '1';
                $this->message = 'Worker not found';
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

}
