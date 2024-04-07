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

/* *********** Requests *********** */
use App\Http\Requests\{UserEditProfile, ChangePasswordRequest, ShippingRequest, BillingRequest};
// ************ models ************
/** Models */
use App\Models\{User, Nurse,Follows, NurseReference,Job,Offer, NurseAsset,
    Keyword, Facility, Availability, Countries, States, Cities, JobSaved,State};


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

    public function update_worker_profile(Request $request){

        // here we should add the validation for the request

        // end of validation

        $user = Auth::guard('frontend')->user();
        $nurse = Nurse::where('user_id', $user->id)->first();

        $nurse_data = [];
        isset($request->state) ? $nurse_data['state'] = $request->state : '';
        isset($request->city) ? $nurse_data['city'] = $request->city : '';
        isset($request->address) ? $nurse_data['address'] = $request->address : '';
        isset($request->specialty) ? $nurse_data['specialty'] = $request->specialty : '';
        isset($request->profession) ? $nurse_data['profession'] = $request->profession : '';
        isset($request->terms) ? $nurse_data['terms'] = $request->terms : '';
        isset($request->type) ? $nurse_data['type'] = $request->type : '';
        isset($request->block_scheduling ) ? $nurse_data['block_scheduling'] = $request->block_scheduling : '';
        isset($request->float_requirement) ? $nurse_data['float_requirement'] = $request->float_requirement : '';
        isset($request->facility_shift_cancelation_policy) ? $nurse_data['facility_shift_cancelation_policy'] = $request->facility_shift_cancelation_policy : '';
        isset($request->contract_termination_policy) ? $nurse_data['contract_termination_policy'] = $request->contract_termination_policy : '';
        isset($request->distance_from_your_home) ? $nurse_data['distance_from_your_home'] = $request->distance_from_your_home : '';
        isset($request->clinical_setting_you_prefer) ? $nurse_data['clinical_setting_you_prefer'] = $request->clinical_setting_you_prefer : '';
        isset($request->worker_patient_ratio) ? $nurse_data['worker_patient_ratio'] = $request->worker_patient_ratio : '';
        isset($request->worker_emr) ? $nurse_data['worker_emr'] = $request->worker_emr : '';
        isset($request->worker_unit) ? $nurse_data['worker_unit'] = $request->worker_unit : '';
        isset($request->worker_scrub_color) ? $nurse_data['worker_scrub_color'] = $request->worker_scrub_color : '';
        isset($request->rto) ? $nurse_data['rto'] = $request->rto : '';
        isset($request->worker_shift_time_of_day) ? $nurse_data['worker_shift_time_of_day'] = $request->worker_shift_time_of_day : '';
        isset($request->worker_hours_per_week) ? $nurse_data['worker_hours_per_week'] = $request->worker_hours_per_week : '';
        isset($request->worker_hours_per_shift) ? $nurse_data['worker_hours_per_shift'] = $request->worker_hours_per_shift : '';
        isset($request->worker_weeks_assignment) ? $nurse_data['worker_weeks_assignment'] = $request->worker_weeks_assignment : '';
        isset($request->worker_shifts_week) ? $nurse_data['worker_shifts_week'] = $request->worker_shifts_week : '';

        $nurse->update($nurse_data);

        $user_data = [];
        isset($request->first_name) ? $user_data['first_name'] = $request->first_name : '';
        isset($request->last_name) ? $user_data['last_name'] = $request->last_name : '';
        isset($request->mobile) ? $user_data['mobile'] = $request->mobile : '';
        isset($request->zip_code) ? $user_data['zip_code'] = $request->zip_code : '';

        $user->update($user_data);

        $nurse = $nurse->fresh();


        return response()->json(['msg'=>$request->all(), 'user'=>$user->id, 'nurse'=>$nurse]);
    }

    /** update password */
    public function post_change_password(ChangePasswordRequest $request)
    {
        if ($request->ajax()) {
            $user = Auth::guard('frontend')->user();
            $user->update(['password'=>Hash::make($request->new_password), 'updated_at'=>Carbon::now()->toDateTimeString()]);
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
        $data['worker'] = auth()->guard('frontend')->user();
        $data['specialities'] = Speciality::select('full_name')->get();
        $data['proffesions'] = Profession::select('full_name')->get();
        // send the states
        $distinctFilters = Keyword::distinct()->pluck('filter');
                $allKeywords = [];
                foreach ($distinctFilters as $filter) {
                    $keywords = Keyword::where('filter', $filter)->get();
                    $allKeywords[$filter] = $keywords;
                }
        $data['states'] = State::select('id','name')->get();
        $data['allKeywords'] = $allKeywords;
        
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
        
        try {

           // commenting this for now we need to return only jobs data

        $data = [];
        $data['user'] = auth()->guard('frontend')->user();
        $data['jobSaved'] = new JobSaved();
        //$data['professions'] = Keyword::where(['filter'=>'Profession','active'=>'1'])->get();
        $data['professions'] = Profession::all();
        $data['terms'] = Keyword::where(['filter'=>'Terms'])->get();
        $data['prefered_shifts'] = Keyword::where(['filter'=>'PreferredShift','active'=>'1'])->get();
        $data['usa'] = $usa =  Countries::where(['iso3'=>'USA'])->first();
        $data['us_states'] = States::where('country_id', $usa->id)->get();
        // $data['us_cities'] = Cities::where('country_id', $usa->id)->get();

        $data['profession'] = isset($request->profession_text) ? $request->profession_text : '';
        $data['speciality'] = isset($request->speciality) ? $request->speciality : '';
        $data['experience'] = isset($request->experience) ? $request->experience : '';
        $data['city'] = isset($request->city) ? $request->city : '';
        $data['state'] = isset($request->state) ? $request->state : '';
        $data['job_type'] = isset($request->job_type) ? explode('-', $request->job_type) : [];
        $data['start_date'] = isset($request->start_date) ? $request->start_date : '';
        $data['end_date'] = isset($request->end_date) ? $request->end_date : '';
        $data['start_date'] = new DateTime($data['start_date']);
        $data['start_date'] = $data['start_date']->format('Y-m-d');
        $data['end_date'] = new DateTime($data['end_date']);
        $data['end_date'] = $data['end_date']->format('Y-m-d');
        $data['shifts'] = isset($request->shifts) ? explode('-',$request->shifts) : [];

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
        $jobs_id = Offer::where('worker_user_id', $nurse->id)->select('job_id')->get();

        // $checkoffer = DB::table('blocked_users')->where('worker_id', $nurse['id'])->first();

        $whereCond = [
            'facilities.active' => true,
            'jobs.is_open' => "1",
            'jobs.is_hidden' => "0",
            'jobs.is_closed' => "0",
            // 'job_saved.is_delete'=>'0',
            // 'job_saved.nurse_id'=>$user->id,
        ];

        $ret = Job::select('jobs.*','name')
        ->leftJoin('facilities', function ($join) {
            $join->on('facilities.id', '=', 'jobs.facility_id');
        });

        if ($data['profession']) {
            $ret->where('jobs.proffesion', '=', $data['profession']);
        }

        if(count($data['job_type'])){
            $ret->whereIn('jobs.job_type', $data['job_type']);
        }


        if ($data['start_date']) {
            // return response()->json(['message end' =>  $data['end_date'], 'message start' =>  $data['start_date']]);
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

        $data['jobs'] = $result;
        //return response()->json(['success' => false, 'message' =>  $data]);
        return view('worker::dashboard.explore', $data);
        
        //return response()->json(['message' =>  $data['jobs']]);
    } catch (\Exception $e) {
        // Handle other exceptions


        // Display a generic error message or redirect with an error status
         return redirect()->route('worker.dashboard')->with('error', $e->getmessage());
        // return response()->json(['success' => false, 'message' =>  $e->getMessage()]);
    }
    }

    public function my_work_journey()
    {
        try{
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

        $model = $jobs;
        // return response()->json(['message' => $model]);
        return view('worker::dashboard.my_work_journey',compact('model'));
    }
    catch (\Exception $e) {
        // Handle other exceptions
        // Display a generic error message or redirect with an error status
        return redirect()->route('worker.dashboard')->with('error', $e->getmessage());
    }
    }
}