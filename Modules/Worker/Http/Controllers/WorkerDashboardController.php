<?php

namespace Modules\Worker\Http\Controllers;

use DateTime;
use App\Models\Nurse;
use App\Models\Profession;
use App\Models\Speciality;
use App\Models\Keyword;
use App\Models\User;
use App\Models\Offer;
use App\Models\Country;
use App\Models\Job;
use App\Models\Follows;
use App\Models\Facility;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\{Response, JsonResponse};
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;
use App\Traits\HelperTrait;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use File;

/* *********** Requests *********** */
use App\Http\Requests\{UserEditProfile, ChangePasswordRequest, ShippingRequest, BillingRequest};
// ************ models ************
use App\Models\{Countries, Cities, States};

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
    public function post_edit_profile(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->guard('frontend')->user();
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|max:100|regex:/^[a-zA-Z\s]+$/',
                'last_name' => 'required|max:100|regex:/^[a-zA-Z\s]+$/',
                'mobile' => 'required|min:10|max:15',
                // 'email' => 'required|email|max:255',
                'profile_picture' => 'mimes:jpg,jpeg,png',
            ]);
            if ($validator->fails()) {
                return new JsonResponse(['errors' => $validator->errors()], 422);
            }else{
                $input = $request->except(['email']);
                if ($request->hasFile('profile_picture')) {
                    if (!empty($user->image)) {
                        if (file_exists(public_path('images/workers/profile/'.$user->image))) {
                            File::delete(public_path('images/workers/profile/'.$user->image));
                        }
                    }
                    $file = $request->file('profile_picture');
                    $img_name = $file->getClientOriginalName() .'_'.time(). '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/workers/profile/'), $img_name);

                    $input['image'] = $img_name;
                }
                $input['updated_at'] = Carbon::now();
                $user->update($input);
                return new JsonResponse(['success' => true, 'msg'=>'Profile updated successfully.'], 200);
            }
        }
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

    public function explore()
    {
        /*
        $data = [];
        $data['worker'] = auth()->guard('frontend')->user();
        return view('worker::dashboard.explore', $data);
        */



        try {

            $data = [];
            $data['user'] = auth()->guard('frontend')->user();
            $data['professions'] = Profession::select(['*'])->get();
            $data['specialty'] = Speciality::select(['*'])->get();
            $data['terms'] = Keyword::where(['filter'=>'jobType','active'=>'1'])->get();
            $data['usa'] = $usa =  Countries::where(['iso3'=>'USA'])->first();
            $data['us_states'] = States::where('country_id', $usa->id)->get();
            //$data['jobSaved'] = new JobSaved();
            //$data['us_cities'] = Cities::where('country_id', $usa->id)->get();
            //$data['prefered_shifts'] = Keyword::where(['filter'=>'PreferredShift','active'=>'1'])->get();
            
            $data['profession'] = isset($request->profession) ? $request->profession : '';
            $data['speciality'] = isset($request->speciality) ? $request->speciality : '';
            $data['city'] = isset($request->city) ? $request->city : '';
            $data['state'] = isset($request->state) ? $request->state : '';
            $data['job_type'] = isset($request->job_type) ? explode('-', $request->job_type) : [];
            $data['start_date'] = isset($request->start_date) ? $request->start_date : '';
            $data['end_date'] = isset($request->end_date) ? $request->end_date : '';
            $data['shifts'] = isset($request->shifts) ? explode('-',$request->shifts) : [];
            //$data['auto_offers'] = isset($request->auto_offers) ? $request->auto_offers : 0;
            //$data['experience'] = isset($request->experience) ? $request->experience : '';

            $data['weekly_pay_from'] = isset($request->weekly_pay_from) ? $request->weekly_pay_from : 10;
            $data['weekly_pay_to'] = isset($request->weekly_pay_to) ? $request->weekly_pay_to : 10000;
            $data['hourly_pay_from'] = isset($request->hourly_pay_from) ? $request->hourly_pay_from : 2;
            $data['hourly_pay_to'] = isset($request->hourly_pay_to) ? $request->hourly_pay_to : 24;
            $data['hours_per_week_from'] = isset($request->hours_per_week_from) ? $request->hours_per_week_from : 10;
            $data['hours_per_week_to'] = isset($request->hours_per_week_to) ? $request->hours_per_week_to : 100;
            $data['assignment_from'] = isset($request->assignment_from) ? $request->assignment_from : 10;
            $data['assignment_to'] = isset($request->assignment_to) ? $request->assignment_to : 150;

            $user = auth()->guard('frontend')->user();
            $worker = NURSE::where('user_id', $user->id)->first();
            $jobs_id = Offer::where('nurse_id', $worker->id)->select('job_id')->get();
            // $checkoffer = DB::table('blocked_users')->where('worker_id', $nurse['id'])->first();

            $whereCond = [
                // 'facilities.active' => true,
                'jobs.is_open' => "1",
                'jobs.is_hidden' => "0",
                'jobs.is_closed' => "0",
                // 'job_saved.is_delete'=>'0',
                // 'job_saved.nurse_id'=>$user->id,
            ];

            $ret = Job::select('jobs.id as job_id', 'jobs.*')
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
            /*
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
            */
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


            return view('worker::dashboard.explore', $data);
            //return response()->json(['message' =>  $data['jobs']]);
        
        } catch (\Exception $e) {
            // Handle other exceptions


            // Display a generic error message or redirect with an error status
            return redirect()->route('worker::dashboard.explore')->with('error', 'An unexpected error occurred. Please try again later.');
            //return response()->json(['success' => false, 'message' =>  $e->getMessage()]);
        }
    }
}