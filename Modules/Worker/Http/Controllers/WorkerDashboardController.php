<?php

namespace Modules\Worker\Http\Controllers;

use DateTime;

use App\Models\Profession;
use App\Models\Speciality;

use App\Models\Country;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\{Response, JsonResponse};
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Intervention\Image\ImageManagerStatic as Image;
use App\Traits\HelperTrait;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\Mail;
use App\Mail\support;
use Illuminate\Support\Facades\Http;


/* *********** Requests *********** */
use App\Http\Requests\{UserEditProfile, ChangePasswordRequest, ShippingRequest, BillingRequest};
// ************ models ************
/** Models */
use App\Models\{User, Nurse, Follows, NurseReference, Job, Offer, NurseAsset, Keyword, Facility, Availability, Countries, States, Cities, JobSaved, State};

define('default_max_step', 5);
define('min_increment', 1);

define('USER_IMG_', asset('public/frontend/img/profile-pic-big.png'));

class WorkerDashboardController extends Controller
{
    use HelperTrait;
    /** dashboard page */
    public function dashboard()
    {
        $data = [];
        $data['user'] = $user = auth()->guard('frontend')->user();

        return view('worker::dashboard.dashboard', $data);
    }
    /** verified users page */
    public function setting()
    {
        $data = [];
        $data['model'] = auth()->guard('frontend')->user();
        return view('worker::dashboard.profile', $data);
    }
    /** account settings page */
    public function account_setting()
    {
        $data = [];
        $data['model'] = auth()->guard('frontend')->user();
        $data['countries'] = Country::where('flag', 1)->get();
        return view('worker.account_setting', $data);
    }

    /** update personal info */
    // public function post_edit_profile(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $user = auth()->guard('frontend')->user();
    //         $validator = Validator::make($request->all(), [
    //             'first_name' => 'required|max:100|regex:/^[a-zA-Z\s]+$/',
    //             'last_name' => 'required|max:100|regex:/^[a-zA-Z\s]+$/',
    //             'mobile' => 'required|min:10|max:15',
    //             // 'email' => 'required|email|max:255',
    //             //'profile_picture' => 'mimes:jpg,jpeg,png',
    //         ]);
    //         if ($validator->fails()) {
    //             return new JsonResponse(['errors' => $validator->errors()], 422);
    //         }else{
    //             $input = $request->except(['email']);
    //             if ($request->hasFile('profile_picture')) {
    //                 if (!empty($user->image)) {
    //                     if (file_exists(public_path('images/workers/profile/'.$user->image))) {
    //                         File::delete(public_path('images/workers/profile/'.$user->image));
    //                     }
    //                 }
    //                 $file = $request->file('profile_picture');
    //                 $img_name = $file->getClientOriginalName() .'_'.time(). '.' . $file->getClientOriginalExtension();
    //                 $file->move(public_path('images/workers/profile/'), $img_name);

    //                 $input['image'] = $img_name;
    //             }
    //             $input['updated_at'] = Carbon::now();
    //             $user->update($input);
    //             return new JsonResponse(['success' => true, 'msg'=>'Profile updated successfully.'], 200);
    //         }
    //     }
    // }

    // new update function ( new file management )

    public function update_worker_profile(Request $request)
    {
        try {
            // Validate InfoType
            $request->validate([
                'InfoType' => 'required|in:BasicInformation,ProfessionalInformation',
            ]);

            $user = Auth::guard('frontend')->user();
            $nurse = Nurse::where('user_id', $user->id)->first();

            if ($request->InfoType == 'ProfessionalInformation') {
                // Validate fields for ProfessionalInformation
                $request->validate([
                    'specialty' => 'required|string',
                    'profession' => 'required|string',
                    'terms' => 'required|string',
                    'type' => 'required|string',
                    'block_scheduling' => 'required|boolean',
                    'float_requirement' => 'required|boolean',
                    'facility_shift_cancelation_policy' => 'required|string',
                    'contract_termination_policy' => 'required|string',
                    'distance_from_your_home' => 'required|string',
                    'clinical_setting_you_prefer' => 'required|string',
                    'worker_patient_ratio' => 'required|string',
                    'worker_emr' => 'required|string',
                    'worker_unit' => 'required|string',
                    'worker_scrub_color' => 'required|string',
                    'rto' => 'required|string',
                    'worker_shift_time_of_day' => 'required|string',
                    'worker_hours_per_week' => 'required|integer',
                    'worker_hours_per_shift' => 'required|integer',
                    'worker_weeks_assignment' => 'required|integer',
                    'worker_shifts_week' => 'required|integer',
                ]);

                $nurse_data = [];

                isset($request->specialty) ? ($nurse_data['specialty'] = $request->specialty) : '';
                isset($request->profession) ? ($nurse_data['profession'] = $request->profession) : '';
                isset($request->terms) ? ($nurse_data['terms'] = $request->terms) : '';
                isset($request->type) ? ($nurse_data['type'] = $request->type) : '';
                isset($request->block_scheduling) ? ($nurse_data['block_scheduling'] = $request->block_scheduling) : '';
                isset($request->float_requirement) ? ($nurse_data['float_requirement'] = $request->float_requirement) : '';
                isset($request->facility_shift_cancelation_policy) ? ($nurse_data['facility_shift_cancelation_policy'] = $request->facility_shift_cancelation_policy) : '';
                isset($request->contract_termination_policy) ? ($nurse_data['contract_termination_policy'] = $request->contract_termination_policy) : '';
                isset($request->distance_from_your_home) ? ($nurse_data['distance_from_your_home'] = $request->distance_from_your_home) : '';
                isset($request->clinical_setting_you_prefer) ? ($nurse_data['clinical_setting_you_prefer'] = $request->clinical_setting_you_prefer) : '';
                isset($request->worker_patient_ratio) ? ($nurse_data['worker_patient_ratio'] = $request->worker_patient_ratio) : '';
                isset($request->worker_emr) ? ($nurse_data['worker_emr'] = $request->worker_emr) : '';
                isset($request->worker_unit) ? ($nurse_data['worker_unit'] = $request->worker_unit) : '';
                isset($request->worker_scrub_color) ? ($nurse_data['worker_scrub_color'] = $request->worker_scrub_color) : '';
                isset($request->rto) ? ($nurse_data['rto'] = $request->rto) : '';
                isset($request->worker_shift_time_of_day) ? ($nurse_data['worker_shift_time_of_day'] = $request->worker_shift_time_of_day) : '';
                isset($request->worker_hours_per_week) ? ($nurse_data['worker_hours_per_week'] = $request->worker_hours_per_week) : '';
                isset($request->worker_hours_per_shift) ? ($nurse_data['worker_hours_per_shift'] = $request->worker_hours_per_shift) : '';
                isset($request->worker_weeks_assignment) ? ($nurse_data['worker_weeks_assignment'] = $request->worker_weeks_assignment) : '';
                isset($request->worker_shifts_week) ? ($nurse_data['worker_shifts_week'] = $request->worker_shifts_week) : '';

                $nurse->update($nurse_data);
            }

            if ($request->InfoType == 'BasicInformation') {
                $request->validate([
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'mobile' => 'required|string',
                    //'mobile' => ['nullable','regex:/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/'],
                    'zip_code' => 'required|string',
                    'state' => 'required|string',
                    'city' => 'required|string',
                    'address' => 'required|string',
                ]);
                $user_data = [];
                $nurse_data = [];
                isset($request->first_name) ? ($user_data['first_name'] = $request->first_name) : '';
                isset($request->last_name) ? ($user_data['last_name'] = $request->last_name) : '';
                isset($request->mobile) ? ($user_data['mobile'] = $request->mobile) : '';
                isset($request->zip_code) ? ($user_data['zip_code'] = $request->zip_code) : '';
                isset($request->state) ? ($nurse_data['state'] = $request->state) : '';
                isset($request->city) ? ($nurse_data['city'] = $request->city) : '';
                isset($request->address) ? ($nurse_data['address'] = $request->address) : '';

                $user->update($user_data);
                $nurse->update($nurse_data);
            }

            $nurse = $nurse->fresh();
            $user = $user->fresh();

            return response()->json(['msg' => $request->all(), 'user' => $user, 'nurse' => $nurse, 'status' => true]);
        } catch (\Exception $e) {
            //return response()->json(['msg'=>$e->getMessage(), 'status'=>false]);
            return response()->json(['msg' => $request->all(), 'status' => false]);
            // return response()->json(['msg'=>'"Something was wrong please try later !"', 'status'=>false]);
        }
    }

    // function to update the account setting

    public function update_worker_account_setting(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_name' => 'regex:/^[a-zA-Z\s]+$/|max:255',
                //'new_mobile' => ['nullable','regex:/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/'],
                '2fa' => 'in:0,1',
                //needs net` access
                'email' => 'email:rfc,dns',
            ]);

            $user = Auth::guard('frontend')->user();

            isset($request->user_name) ? ($user_data['user_name'] = $request->user_name) : '';
            isset($request->new_mobile) ? ($user_data['new_mobile'] = $request->new_mobile) : '';
            isset($request->email) ? ($user_data['email'] = $request->email) : '';
            isset($request->password) ? ($user_data['password'] = Hash::make($request->password)) : '';
            isset($request->twoFa) ? ($user_data['2fa'] = $request->twoFa) : '';

            $user->update($user_data);
            //$UpdatedUser = $user->refresh();

            return response()->json(['status' => true, 'message' => 'Account settings updated successfully']);
        } catch (ValidationException $e) {
            // return response()->json(['message' => $e->getMessage()], 400);
            return response()->json(['status' => false, 'message' => 'An error occurred please check your infromation !']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'An error occurred while updating the account settings']);
        }
    }

    /** update password */
    public function post_change_password(ChangePasswordRequest $request)
    {
        if ($request->ajax()) {
            $user = Auth::guard('frontend')->user();
            $user->update(['password' => Hash::make($request->new_password), 'updated_at' => Carbon::now()->toDateTimeString()]);
            $response = [];
            $response['success'] = true;
            $response['msg'] = 'Password updated successfully';
            return response()->json($response);
        }
    }

    /** Help center page */
    public function help_center()
    {
        $data = [];
        return view('user.help_center', $data);
    }

    public function my_profile()
    {
        $data = [];
        $user = auth()->guard('frontend')->user();
        $nurse = Nurse::where('user_id', $user->id)->first();
        $data['worker'] = $nurse;
        $data['specialities'] = Speciality::select('full_name')->get();
        $data['proffesions'] = Profession::select('full_name')->get();
        // send the states
        $distinctFilters = Keyword::distinct()->pluck('filter');
        $allKeywords = [];
        foreach ($distinctFilters as $filter) {
            $keywords = Keyword::where('filter', $filter)->get();
            $allKeywords[$filter] = $keywords;
        }
        $data['states'] = State::select('id', 'name')->get();
        $data['allKeywords'] = $allKeywords;
        //
        $progress = 0;

        if (isset($nurse['specialty']) && isset($nurse['profession']) && isset($nurse['terms']) && isset($nurse['type']) && isset($nurse['block_scheduling']) && isset($nurse['float_requirement']) && isset($nurse['facility_shift_cancelation_policy']) && isset($nurse['contract_termination_policy']) && isset($nurse['distance_from_your_home']) && isset($nurse['clinical_setting_you_prefer']) && isset($nurse['worker_patient_ratio']) && isset($nurse['worker_emr']) && isset($nurse['worker_unit']) && isset($nurse['worker_scrub_color']) && isset($nurse['rto']) && isset($nurse['worker_shift_time_of_day']) && isset($nurse['worker_hours_per_week']) && isset($nurse['worker_hours_per_shift']) && isset($nurse['worker_weeks_assignment']) && isset($nurse['worker_shifts_week'])) {
            $progress += 1;
        }

        if (isset($user->first_name) && isset($user->last_name) && isset($user->mobile) && isset($user->zip_code)) {
            $progress += 1;
        }
        $nurse_data['account_tier'] = $progress;
        $nurse->update($nurse_data);
        $nurse = $nurse->fresh();
        $data['worker'] = $nurse;

        $data['progress_percentage'] = $progress * 33 + 1;

        return view('worker::dashboard.worker_profile', $data);
    }

    public function get_messages()
    {
        $data = [];
        $data['worker'] = auth()->guard('frontend')->user();
        return view('worker::worker.messages', $data);
    }

    public function get_my_work_journey()
    {
        $data = [];
        $data['worker'] = auth()->guard('frontend')->user();
        return view('worker::dashboard.my_work_journey', $data);
    }

    public function explore(Request $request)
    {
       //return $request->all();
            // commenting this for now we need to return only jobs data

            $data = [];
            $data['user'] = auth()->guard('frontend')->user();
            $data['jobSaved'] = new JobSaved();
            //$data['professions'] = Keyword::where(['filter'=>'Profession','active'=>'1'])->get();
           // $data['professions'] = Profession::all();
            $data['specialities'] = Speciality::select('full_name')->get();
        $data['professions'] = Profession::select('full_name')->get();
            $data['terms_key'] = Keyword::where(['filter' => 'Terms'])->get();
            $data['prefered_shifts'] = Keyword::where(['filter' => 'PreferredShift', 'active' => '1'])->get();
            $data['usa'] = $usa = Countries::where(['iso3' => 'USA'])->first();
            $data['us_states'] = States::where('country_id', $usa->id)->get();
            // $data['us_cities'] = Cities::where('country_id', $usa->id)->get();

            $data['profession'] = isset($request->profession) ? $request->profession : '';
            $data['speciality'] = isset($request->speciality) ? $request->speciality : '';
            $data['experience'] = isset($request->experience) ? $request->experience : '';
            $data['city'] = isset($request->city) ? $request->city : '';
            $data['state'] = isset($request->state) ? $request->state : '';
            $data['terms'] = isset($request->terms) ? explode('-', $request->terms) : [];
            $data['start_date'] = isset($request->start_date) ? $request->start_date : '';
            $data['end_date'] = isset($request->end_date) ? $request->end_date : '';
            $data['start_date'] = new DateTime($data['start_date']);
            $data['start_date'] = $data['start_date']->format('Y-m-d');
            
            $data['shifts'] = isset($request->shifts) ? explode('-', $request->shifts) : [];

            $data['weekly_pay_from'] = isset($request->weekly_pay_from) ? $request->weekly_pay_from : 10;
            $data['weekly_pay_to'] = isset($request->weekly_pay_to) ? $request->weekly_pay_to : 10000;
            $data['hourly_pay_from'] = isset($request->hourly_pay_from) ? $request->hourly_pay_from : 2;
            $data['hourly_pay_to'] = isset($request->hourly_pay_to) ? $request->hourly_pay_to : 24;
            $data['hours_per_week_from'] = isset($request->hours_per_week_from) ? $request->hours_per_week_from : 10;
            $data['hours_per_week_to'] = isset($request->hours_per_week_to) ? $request->hours_per_week_to : 100;
          

            $user = auth()->guard('frontend')->user();

            $nurse = NURSE::where('user_id', $user->id)->first();
            $jobs_id = Offer::where('worker_user_id', $nurse->id)
                ->select('job_id')
                ->get();

            
        $whereCond = [
            'active' => '1'
        ];

        $ret = Job::select('*')
            ->where($whereCond)
            ;
 

            if ($data['profession']) {
                
                $ret->where('proffesion', '=', $data['profession']);
                
            }

            if (count($data['terms'])) {
               
                $ret->whereIn('terms', $data['terms']);
            }

            // if (isset($request->start_date)) {
               
            //     $ret->where('start_date', '>=', $data['start_date']);
            //     //$ret->where('end_date', '>=', $data['start_date']);
            // }

            if (isset($request->start_date)) {
                
                $ret->where('start_date', '<=', $data['start_date']);
                
            }

            if ($data['shifts']) {
                
                $ret->whereIn('preferred_shift', $data['shifts']);
            }


            if (isset($request->weekly_pay_from)) {
               
                $ret->where('weekly_pay', '>=', $data['weekly_pay_from']);
            }

            if (isset($request->weekly_pay_to)) {
                $ret->where('weekly_pay', '<=', $data['weekly_pay_to']);
            }

            if (isset($request->hourly_pay_from)) {
                $ret->where('hours_shift', '>=', $data['hourly_pay_from']);
            }

            if (isset($request->hourly_pay_to)) {
                $ret->where('hours_shift', '<=', $data['hourly_pay_to']);
            }

            if (isset($request->hours_per_week_from)) {
                $ret->where('hours_per_week', '>=', $data['hours_per_week_from']);
            }

            if (isset($request->hours_per_week_to)) {
                $ret->where('hours_per_week', '<=', $data['hours_per_week_to']);
            }

            if(isset($request->state)){
                $ret->where('job_state', '=', $data['state']);
            }

            if(isset($request->city)){
                $ret->where('job_city', '=', $data['city']);
            }


            //return response()->json(['message' =>  $ret->get()]);
            $data['jobs'] = $ret->get();

           
            return view('worker::dashboard.explore', $data);

           
        
    }

    public function my_work_journey()
    {
        try {
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
                    // $jobs = Job::select("jobs.*")
                    // ->join('job_saved', function ($join) use ($user){
                    //     $join->on('job_saved.job_id', '=', 'jobs.id')
                    //     ->where(function ($query) use ($user) {
                    //         $query->where('job_saved.is_delete', '=', '0')
                    //         ->where('job_saved.is_save', '=', '1')
                    //         ->where('job_saved.nurse_id', '=', $user->id);
                    //     });
                    // })
                    // ->where($whereCond)
                    // ->orderBy('job_saved.created_at', 'DESC')
                    // ->get();

                    $jobs = Job::first();
                    $data['type'] = 'saved';
                    //return response()->json(['message' =>  $user]);
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
                                    ->whereIn('offers.status', ['Offered', 'Rejected', 'Hold'])
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

            $model = $jobs;
            // return response()->json(['message' => $model]);
            return view('worker::dashboard.my_work_journey', compact('model'));
        } catch (\Exception $e) {
            // Handle other exceptions
            // Display a generic error message or redirect with an error status
            return redirect()->route('worker.dashboard')->with('error', $e->getmessage());
        }
    }

    public function add_worker_payment(Request $request)
    {
        // testing request
        //return response()->json(['msg'=>$request->all()]);
        try {
            $validatedData = $request->validate([
                'full_name_payment' => 'required|max:255',
                'address_payment' => 'required',
                'email_payment' => 'required|email:rfc,dns',
                'bank_name_payment' => 'required|max:255',
                'routing_number_payment' => 'required',
                'bank_account_payment_number' => 'required',
                'phone_number_payment' => 'required|regex:/^\+1 \(\d{3}\) \d{3}-\d{4}$/',
            ]);

            $user = Auth::guard('frontend')->user();
            $nurse = Nurse::where('user_id', $user->id)->first();

            isset($request->full_name_payment) ? ($user_data['full_name_payment'] = $request->full_name_payment) : '';
            isset($request->address_payment) ? ($user_data['address_payment'] = $request->address_payment) : '';
            isset($request->email_payment) ? ($user_data['email_payment'] = $request->email_payment) : '';
            isset($request->bank_name_payment) ? ($user_data['bank_name_payment'] = $request->bank_name_payment) : '';
            isset($request->routing_number_payment) ? ($user_data['routing_number_payment'] = $request->routing_number_payment) : '';
            isset($request->bank_account_payment_number) ? ($user_data['bank_account_payment_number'] = $request->bank_account_payment_number) : '';
            isset($request->phone_number_payment) ? ($user_data['phone_number_payment'] = $request->phone_number_payment) : '';

            // testi
            //return response()->json(['msg'=>$user_data]);

            $nurse->update($user_data);

            return response()->json(['status' => true, 'message' => 'Bonus info updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => $e->getmessage()]);
            //return response()->json(['status' => false,'message' => 'An error occurred please check your information !']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getmessage()]);
            //return response()->json(['status' => false,'message' => $e->getmessage()]);
        }
    }

    
    public function send_support_ticket(Request $request){
            try {
                $validatedData = $request->validate([
                    'support_subject_issue' => 'required|max:500',
                    'support_subject' => 'required',
                ]);
            
                $user = Auth::guard('frontend')->user();
                $user_email =  $user->email;
                $email_data = ['support_subject_issue'=>$request->support_subject_issue,'support_subject'=>$request->support_subject,'worker_email'=>$user_email ];
                Mail::to('support@goodwork.com')->send(new support($email_data));
            
                return response()->json(['status' => true, 'message' => 'Support ticket sent successfully']);
            } catch (ValidationException $e) {
                return response()->json(['status' => false, 'message' => $e->errors()]);
            } catch (\Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
    }

    public function disactivate_account(Request $request){
        try {
        $user = Auth::guard('frontend')->user();
        $data['active'] = false;
        $user->update($data);
        $guard = "frontend";
        Auth::guard('frontend')->logout();
        $request->session()->invalidate();
        return response()->json(['status' => true, 'message' => 'You are successfully disactivate your account.']);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => $e->errors()]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    
    // adding add stripe function 

    
public function add_stripe_account(Request $request)
{
    try {
        $user = Auth::guard('frontend')->user();
        $user_email  = $user->email;
        $user_id = $user->id;

        // Define the data for the request
        $data = [
            'userId' => $user_id,
            'email' => $user_email
        ];
    
        // Define the URL<
        $url = 'http://localhost:' . config('app.file_api_port') . '/payments/create';

        // return response()->json(['data'=>$data , 'url' => $url]);   

        // Make the request
        $response = Http::post($url, $data);

    $stripeId = $response->json()['message'];

    
    $user_data['stripeAccountId'] = $stripeId;
    // if(!isset($user_data)){
    //     return response()->json(['stripeidnot'=>$stripeId]);
    // }

    $user->update($user_data);
        

    // Check the response
    if ($response->successful()) {
        $get_account_url = 'http://localhost:' . config('app.file_api_port') . '/payments/account-link';
        $data_account_url = [
            'stripeId' => $response->json()['message'],
        ];
        $get_account_link_response = Http::get($get_account_url, $data_account_url);

        return response()->json(['status'=>true,'account_link'=>$get_account_link_response->json()['message'] ]);

    } else {
        return response()->json(['status' => false, 'message' => 'Error']);
    }

        } catch (\Exception $e) {
            // Log the exception or return a response
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
}

public function check_onboarding_status(Request $request)
{
    $user = Auth::guard('frontend')->user();

    $stripeId = $user->stripeAccountId;

    if (!$stripeId) {
        return response()->json(['status' => false, 'message' => 'Missing stripeId']);
    }

    $get_onboarding_status_url = 'http://localhost:' . config('app.file_api_port') . '/payments/onboarding-status';
    $data = ['stripeId' => $stripeId];
    $get_onboarding_status_response = Http::get($get_onboarding_status_url, $data);

    if ($get_onboarding_status_response->successful()) {
        return response()->json(['status' => true, 'message' => $get_onboarding_status_response->json()['message']]);
    } else {
        return response()->json(['status' => false, 'message' => 'Error checking onboarding status']);
    }
}

public function login_to_stripe_account(Request $request){

    $user = Auth::guard('frontend')->user();

    $stripeId = $user->stripeAccountId;
    
    if (!$stripeId) {
        return response()->json(['status' => false, 'message' => 'Missing stripeId']);
    }
    $data = ['stripeId' => $stripeId];
        // Get login link
        $get_login_url = 'http://localhost:' . config('app.file_api_port') . '/payments/login-link';
        $get_login_link_response = Http::get($get_login_url,  $data);
        //return response()->json(['login_link',$get_login_link_response->json()['message'] ]);

        if ($get_login_link_response->successful()) {
            return response()->json([
                'status' => true, 
                'message' => 'You have successfully created a Stripe account.', 
              
                'login_link' => $get_login_link_response->json()['message']
            ]);
        } else {
            return response()->json(['status' => false, 'message' => 'Error getting login link']);
        }
    


}



}
