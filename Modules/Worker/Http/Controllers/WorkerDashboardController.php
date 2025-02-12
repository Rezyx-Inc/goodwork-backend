<?php

namespace Modules\Worker\Http\Controllers;

use DateTime;

use App\Models\Profession;
use App\Models\Speciality;

use App\Models\Country;

use App\Models\Notification;
use App\Models\NotificationMessage;
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
use App\Events\NotificationJob;
use App\Events\NotificationOffer;
use App\Events\NewPrivateMessage;

/* *********** Requests *********** */
use App\Http\Requests\{UserEditProfile, ChangePasswordRequest, ShippingRequest, BillingRequest};
// ************ models ************
/** Models */

use App\Models\{User, Nurse, Follows, NurseReference, Job, Offer, NurseAsset, Keyword, Facility, Availability, Countries, States, Cities, JobSaved, State, OffersLogs};

define('default_max_step', 5);
define('min_increment', 1);

if (!defined('USER_IMG')) {
  define('USER_IMG', asset('public/frontend/img/profile-pic-big.png'));
}
class WorkerDashboardController extends Controller
{
  use HelperTrait;
  /** dashboard page */

  public function dashboard()
  {
    $data = [];
    $data['user'] = $user = auth()->guard('frontend')->user();

    $user_id = Auth::guard('frontend')->user()->id;
    $id = Nurse::where('user_id', $user_id)->first()->id;

    $statusList = ['Apply', 'Screening', 'Submitted', 'Offered', 'Onboarding', 'Cleared', 'Working'];
    $statusCounts = array_fill_keys($statusList, 0);

    $statusCountsQuery = Offer::whereIn('status', $statusList)
      ->select(\DB::raw('status, count(*) as count'))
      ->where('worker_user_id', $id)
      ->groupBy('status')
      ->get();

    foreach ($statusCountsQuery as $statusCount) {
      $statusCounts[$statusCount->status] = $statusCount->count;
    }

    $statusCounts = array_values($statusCounts);




    return view('worker::dashboard.dashboard', compact('statusCounts', 'data'));
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


  // new update function ( new file management )

  public function old_update_worker_profile(Request $request)
  {
    // return $request->all();
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

          'nursing_license_number' => 'nullable|string',
          'specialty' => 'nullable|string',
          'profession' => 'nullable|string',
          'terms' => 'nullable|string',
          'block_scheduling' => 'nullable|string',
          'float_requirement' => 'nullable|string',
          'facility_shift_cancelation_policy' => 'nullable|string',
          'contract_termination_policy' => 'nullable|string',
          'traveler_distance_from_facility' => 'nullable|string',
          'clinical_setting' => 'nullable|string',
          'Patient_ratio' => 'nullable|string',
          'Emr' => 'nullable|string',
          'Unit' => 'nullable|string',
          'scrub_color' => 'nullable|string',
          'rto' => 'nullable|string',
          'shift_of_day' => 'nullable|string',
          'hours_shift' => 'nullable|string',
          'preferred_assignment_duration' => 'nullable|string',
          'weeks_shift' => 'nullable|string',
          'worker_experience' => 'nullable|string',
          'worker_eligible_work_in_us' => 'nullable|string',
          'worker_facility_city' => 'nullable|string',
          'worker_facility_state' => 'nullable|string',
          'worker_four_zero_one_k' => 'nullable|string',
          'worker_dental' => 'nullable|string',
          'worker_overtime_rate' => 'nullable|string',
          'worker_on_call' => 'nullable|string',
          'worker_call_back' => 'nullable|string',
          'worker_on_call_check' => 'nullable|string',
          'worker_benefits' => 'nullable|string',
          'nurse_classification' => 'nullable|string',
          'worker_holiday' => 'nullable|date',
          'worker_job_type' => 'nullable|string',
          'worker_vision' => 'nullable|string',
        ]);

        $nurse_data = [];



        isset($request->nursing_license_number) ? ($nurse_data['nursing_license_number'] = $request->nursing_license_number) : '';
        isset($request->specialty) ? ($nurse_data['specialty'] = $request->specialty) : '';
        isset($request->profession) ? ($nurse_data['profession'] = $request->profession) : '';
        isset($request->terms) ? ($nurse_data['terms'] = $request->terms) : '';
        isset($request->worker_job_type) ? ($nurse_data['worker_job_type'] = $request->worker_job_type) : '';
        isset($request->block_scheduling) ? ($nurse_data['block_scheduling'] = $request->block_scheduling) : '';
        isset($request->float_requirement) ? ($nurse_data['float_requirement'] = $request->float_requirement) : '';
        isset($request->facility_shift_cancelation_policy) ? ($nurse_data['facility_shift_cancelation_policy'] = $request->facility_shift_cancelation_policy) : '';
        isset($request->contract_termination_policy) ? ($nurse_data['contract_termination_policy'] = $request->contract_termination_policy) : '';
        isset($request->traveler_distance_from_facility) ? ($nurse_data['distance_from_your_home'] = $request->traveler_distance_from_facility) : '';
        isset($request->clinical_setting) ? ($nurse_data['clinical_setting_you_prefer'] = $request->clinical_setting) : '';
        isset($request->Patient_ratio) ? ($nurse_data['worker_patient_ratio'] = $request->Patient_ratio) : '';
        isset($request->Emr) ? ($nurse_data['worker_emr'] = $request->Emr) : '';
        isset($request->Unit) ? ($nurse_data['worker_unit'] = $request->Unit) : '';
        isset($request->scrub_color) ? ($nurse_data['worker_scrub_color'] = $request->scrub_color) : '';
        isset($request->rto) ? ($nurse_data['rto'] = $request->rto) : '';
        isset($request->shift_of_day) ? ($nurse_data['worker_shift_time_of_day'] = $request->shift_of_day) : '';
        isset($request->hours_per_week) ? ($nurse_data['worker_hours_per_week'] = $request->hours_per_week) : '';
        isset($request->hours_shift) ? ($nurse_data['worker_hours_shift'] = $request->hours_shift) : '';
        isset($request->preferred_assignment_duration) ? ($nurse_data['worker_weeks_assignment'] = $request->preferred_assignment_duration) : '';
        isset($request->weeks_shift) ? ($nurse_data['worker_shifts_week'] = $request->weeks_shift) : '';
        isset($request->worker_experience) ? ($nurse_data['worker_experience'] = $request->worker_experience) : '';
        isset($request->worker_eligible_work_in_us) ? ($nurse_data['worker_eligible_work_in_us'] = $request->worker_eligible_work_in_us) : '';
        isset($request->worker_facility_city) ? ($nurse_data['worker_facility_city'] = $request->worker_facility_city) : '';
        isset($request->worker_facility_state) ? ($nurse_data['worker_facility_state'] = $request->worker_facility_state) : '';
        isset($request->worker_four_zero_one_k) ? ($nurse_data['worker_four_zero_one_k'] = $request->worker_four_zero_one_k) : '';
        isset($request->worker_dental) ? ($nurse_data['worker_dental'] = $request->worker_dental) : '';
        isset($request->worker_overtime_rate) ? ($nurse_data['worker_overtime_rate'] = $request->worker_overtime_rate) : '';
        isset($request->worker_on_call) ? ($nurse_data['worker_on_call'] = $request->worker_on_call) : '';
        isset($request->worker_on_call_check) ? ($nurse_data['worker_on_call_check'] = $request->worker_on_call_check) : '';
        isset($request->worker_call_back) ? ($nurse_data['worker_call_back'] = $request->worker_call_back) : '';
        isset($request->worker_benefits) ? ($nurse_data['worker_benefits'] = $request->worker_benefits) : '';
        isset($request->nurse_classification) ? ($nurse_data['nurse_classification'] = $request->nurse_classification) : '';
        isset($request->worker_holiday) ? ($nurse_data['worker_holiday'] = $request->worker_holiday) : '';
        isset($request->worker_vision) ? ($nurse_data['worker_vision'] = $request->worker_vision) : '';

        $nurse->update($nurse_data);
      }

      if ($request->InfoType == 'BasicInformation') {
        //return $request->all();
        $request->validate([
          'first_name' => 'nullable|string',
          'last_name' => 'nullable|string',
          'mobile' => 'nullable|string',
          //'mobile' => ['nullable','regex:/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/'],
          'zip_code' => 'nullable|string',
          'state' => 'nullable|string',
          'city' => 'nullable|string',
          'address' => 'nullable|string',
        ]);
        $user_data = [];
        $nurse_data = [];
        isset($request->first_name) ? ($user_data['first_name'] = $request->first_name) : '';
        isset($request->last_name) ? ($user_data['last_name'] = $request->last_name) : '';
        $user_data['mobile'] = $request->mobile;
        $user_data['zip_code'] = $request->zip_code;
        $nurse_data['state'] = $request->state;
        $nurse_data['city'] = $request->city;
        $nurse_data['address'] = $request->address;

        if ($request->hasFile('profile_pic')) {
          $file = $request->file('profile_pic');
          $filename = time() . $nurse->id . '.' . $file->getClientOriginalExtension();
          $file->move(public_path('uploads'), $filename);
          $user_data['image'] = $filename;
        }

        $user->update($user_data);
        $nurse->update($nurse_data);
      }

      $nurse = $nurse->fresh();
      $user = $user->fresh();

      return response()->json(['msg' => $request->all(), 'user' => $user, 'nurse' => $nurse, 'status' => true]);
    } catch (\Exception $e) {

      return response()->json(['msg' => $e->getMessage(), 'status' => false], 500);
    }
  }


  public function update_worker_profile(Request $request)
  {

    try {

      $user = Auth::guard('frontend')->user();
      $nurse = $user->nurse;

      // dd($request->all());

      // Filter request data to only include valid attributes
      $userAttributes = $request->only($user->getFillable());
      // Update the user model with the user attributes
      $user->fill($userAttributes);
      // Save the updated user model
      $user->save();
      $user = $user->fresh();

      // Filter request data to only include valid attributes
      $nurseAttributes = $request->only($nurse->getFillable());
      // Update the nurse model with the valid attributes
      $nurse->fill($nurseAttributes);
      // // Save the updated nurse model
      $nurse->save();

      // dd( $nurseAttributes);
      return response()->json(['msg' => $request->all(), 'user' => $user, 'nurse' => $nurse, 'status' => true]);
    } catch (\Exception $e) {

      return response()->json(['msg' => $e->getMessage(), 'status' => false], 500);
    }
  }

  // function to update the account setting

  public function update_worker_account_setting(Request $request)
  {
    try {
      $validatedData = $request->validate([
        // 'user_name' => 'regex:/^[a-zA-Z\s]+$/|max:255',
        //'new_mobile' => ['nullable','regex:/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/'],
        // '2fa' => 'in:0,1',
        //needs net` access
        'email' => 'email:rfc,dns',
      ]);

      $user = Auth::guard('frontend')->user();

      isset($request->user_name) ? ($user_data['user_name'] = $request->user_name) : '';
      isset($request->new_mobile) ? ($user_data['mobile'] = $request->new_mobile) : '';
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

  public function my_profile(Request $request)
  {

    $data = [];
    $type = $request->route('type');
    $user = auth()->guard('frontend')->user();
    $nurse = Nurse::where('user_id', $user->id)->first();
    $data['worker'] = $nurse;
    $data['specialities'] = Speciality::select('full_name')->get();
    $data['professions'] = Profession::select('full_name')->get();
    // send the states
    $distinctFilters = Keyword::distinct()->pluck('filter');
    $allKeywords = [];
    foreach ($distinctFilters as $filter) {
      $keywords = Keyword::where('filter', $filter)->get();
      $allKeywords[$filter] = $keywords;
    }
    $data['states'] = State::select('id', 'name')->get();
    $data['allKeywords'] = $allKeywords;

    $progress = 0;

    if (isset($nurse['specialty']) && isset($nurse['profession']) && isset($nurse['terms']) && isset($nurse['type']) && isset($nurse['block_scheduling']) && isset($nurse['float_requirement']) && isset($nurse['facility_shift_cancelation_policy']) && isset($nurse['contract_termination_policy']) && isset($nurse['distance_from_your_home']) && isset($nurse['clinical_setting_you_prefer']) && isset($nurse['worker_patient_ratio']) && isset($nurse['worker_emr']) && isset($nurse['worker_unit']) && isset($nurse['worker_scrub_color']) && isset($nurse['rto']) && isset($nurse['worker_shift_time_of_day']) && isset($nurse['worker_hours_per_week']) && isset($nurse['worker_hours_shift']) && isset($nurse['worker_weeks_assignment']) && isset($nurse['worker_shifts_week'])) {
      $progress += 1;
    }

    if (isset($user['first_name']) && isset($user['last_name']) && isset($user['mobile']) && isset($user['zip_code'])) {
      $progress += 1;
    }

    $url = "http://localhost:" . config('app.file_api_port') . "/documents/list-docs";
    $worker_id = ['workerId' => $nurse['id']];
    $response = Http::get($url, $worker_id);

    $body = json_decode($response->body());

    if ($body->success) {
      $progress += 1;
    }

    $nurse_data['account_tier'] = $progress;
    $nurse->update($nurse_data);
    $nurse = $nurse->fresh();
    $data['worker'] = $nurse;

    $data['progress_percentage'] = $progress * 33 + 1;
    $data['type'] = $type;

    // dd($data['worker']->toArray());
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
    //dd($request->all());
    // GW Number
    $gwNumber = $request->input('gw', '');

    // Build the query
    $ret = Job::where('is_open', '1')->where('active', '1') ;


    // Initialize data array
    $data = [];
    $data['user'] = auth()->guard('frontend')->user();
    $data['jobSaved'] = new JobSaved();

    // Fetch related data
    $data['organizations'] = User::where('role', 'ORGANIZATION')->get();
    $data['recruiters'] = User::where('role', 'RECRUITER')->get();
    $data['facilities'] = Job::where('active', '1')->get();
    $data['specialities'] = Speciality::select('full_name')->get();
    $data['professions'] = Profession::select('full_name')->get();
    $data['terms_key'] = Keyword::where(['filter' => 'terms'])->get();
    $data['prefered_shifts'] = Keyword::where(['filter' => 'PreferredShift', 'active' => '1'])->get();
    $usa = Countries::where(['iso3' => 'USA'])->first();
    //$data['us_states'] = States::where('country_id', $usa->id)->get();
    $data['us_states'] = State::select('id', 'name')->get();
    
    // Set filter values from the request, use null as the default if not provided
    $data['organization_name'] = $request->input('organization_name', null);
    $data['recruiter_name'] = $request->input('recruiter_name', null);
    // $data['recruiter_first_name'] = $request->input('recruiter_first_name', null);
    // $data['recruiter_last_name'] = $request->input('recruiter_last_name', null);
    $data['facilityName'] = $request->input('facility_name', null);
    $data['job_id'] = $request->input('gw', null);
    $data['profession'] = $request->input('profession');
    $data['speciality'] = $request->input('speciality');
    $data['experience'] = $request->input('experience');
    $data['city'] = $request->input('city');
    $data['state'] = $request->input('state');
    $data['terms'] = $request->has('terms') ? explode('-', $request->terms) : [];
    // dd($request->terms);
    $data['start_date'] = $request->input('start_date', null);
    $data['end_date'] = $request->input('end_date', null);
    $data['start_date'] = $data['start_date'] ? (new DateTime($data['start_date']))->format('Y-m-d') : null;
    $data['shifts'] = $request->has('shifts') ? explode('-', $request->shifts) : [];
    $data['job_type'] = $request->input('job_type', null);
    $data['as_soon_as'] = $request->input('as_soon_as', null);

    // Pay and hour filters
    $data['weekly_pay_from'] = $request->input('weekly_pay_from');
    $data['weekly_pay_to'] = $request->input('weekly_pay_to');
    $data['hourly_pay_from'] = $request->input('hourly_pay_from');
    $data['hourly_pay_to'] = $request->input('hourly_pay_to');
    $data['hours_per_week_from'] = $request->input('hours_per_week_from');
    $data['hours_per_week_to'] = $request->input('hours_per_week_to');
    
    //return response()->json(['message' => $data['as_soon_as']]); 

    if (!empty($gwNumber)) {

      if (str_starts_with($gwNumber, 'GWJ')) {

        $ret->where('id', $gwNumber);
      } else {

        $ret->where('job_id', $gwNumber);
      }

      $data['jobs'] = $ret->get();
      return view('worker::dashboard.explore', $data);
    }

    $data['organizations_id'] = [];
    if (!empty($data['organization_name'])) {
        
      foreach ($data['organizations'] as $org) {

          // if only organization name is provided
          if ($org->organization_name == $data['organization_name']) {
            $data['organizations_id'][] = $org->id;
          }                
        }
        // Apply query filter if organizations_id is not empty
        if (!empty($data['organizations_id'])) {
            $ret->whereIn('organization_id', $data['organizations_id']);
        } else {
            // no matching organization is found
            $ret->whereRaw('1 = 0'); // No results will be returned
        }
    }

    $data['recruiters_id'] = [];
    if (!empty($data['recruiter_name'])) {
        foreach ($data['recruiters'] as $recruiter) {
            // Combine first and last name for matching
            $fullName = $recruiter->first_name . ' ' . $recruiter->last_name;
        
            // Check if the selected name matches the full name
            if ($fullName === $data['recruiter_name']) {
                $data['recruiters_id'][] = $recruiter->id;
            }
        }
      
        // Apply query filter if recruiters_id is not empty
        if (!empty($data['recruiters_id'])) {
            $ret->whereIn('recruiter_id', $data['recruiters_id']);
        } else {
            // No matching recruiter found
            $ret->whereRaw('1 = 0'); // No results will be returned
        }
    }


        
    if (!empty($data['facilityName'])) {
      $ret->where('facility_name', 'like', $data['facilityName']);
    }


    if (!empty($data['job_type'])) {
      $ret->where('job_type', 'like', $data['job_type']);
    }

    if (!empty($data['profession'])) {
      $ret->where('profession', 'like', $data['profession']);
    }

    if (!empty($data['speciality'])) {
      $ret->where('preferred_specialty', 'like', $data['speciality']);
    }

    if (!empty($data['terms']) && !is_null($request->input('terms')) && is_array($data['terms']) && count($data['terms']) > 0) {
      $ret->where(function ($query) use ($data) {
          foreach ($data['terms'] as $term) {
              $query->orWhere('terms', $term);
          }
      });
    }
  
  
    if (!empty($data['as_soon_as'])) {
      $ret->where('as_soon_as', '=', $data['as_soon_as']);
    } elseif (!empty($data['start_date'])) {
      $ret->where('start_date', '>=', $data['start_date']);
    }

    // hidden for now
    /* if (!empty($data['shifts'])) {
        $ret->whereIn('preferred_shift', $data['shifts']);
      }
    */

    if (!empty($data['weekly_pay_from'])) {
      $ret->where('weekly_pay', '>=', $data['weekly_pay_from']);

     }

    if (!empty($data['weekly_pay_to'])) {
      $ret->where('weekly_pay', '<=', $data['weekly_pay_to']);
    }

    if (!empty($data['hourly_pay_from'])) {
      $ret->where('hours_shift', '>=', $data['hourly_pay_from']);
    }

    if (!empty($data['hourly_pay_to'])) {
      $ret->where('hours_shift', '<=', $data['hourly_pay_to']);
    }

    if (!empty($data['hours_per_week_from'])) {
      $ret->where('hours_per_week', '>=', $data['hours_per_week_from']);
    }
    if (!empty($data['hours_per_week_to'])) {
      $ret->where('hours_per_week', '<=', $data['hours_per_week_to']);
    }
    


    if (isset($request->state)) {
      $ret->where('job_state', 'like', $data['state']);
    }

    if (isset($request->city)) {
      $ret->where('job_city', 'like', $data['city']);
    }

    //return response()->json(['message' =>  $ret->get()]);
    $skip = $request->input('skip');

    if(!empty($skip) && $skip > 0){

      $data['jobs'] = $ret->orderBy('id','desc')->skip($skip)->take(10)->get();

      foreach ($data['jobs'] as $key => $value) {

        $userapplied = Offer::where('job_id', $value->id)->count();
        $data['jobs'][$key]['offer_count'] = $userapplied;
      }

      $jobSaved = new JobSaved;
      $data['jobSaved'] = $jobSaved;
      $data['skip']=$skip;

      unset($data['specialities']);
      unset($data['professions']);
      unset($data['us_states']);
      unset($data['specialities']);
      unset($data['terms_key']);

      return response()->json(['message' =>  $data]);

    }else{

      $data['jobs'] = $ret->orderBy('id','desc')->skip(0)->take(10)->get();
    }

    $jobSaved = new JobSaved;

    $data['jobSaved'] = $jobSaved;


    return view('worker::dashboard.explore')->with($data);
  }




  public function add_save_jobs(Request $request)
  {
    // return asset('public/frontend/img/job-icon-bx-Vector.png');
    try {


      $request->validate([
        'jid' => 'required',
      ]);
      $user = auth()->guard('frontend')->user();
      $nurse = NURSE::where('user_id', $user->id)->first();
      $rec = JobSaved::where(['nurse_id' => $nurse->id, 'job_id' => $request->jid, 'is_delete' => '0'])->first();
      $input = [
        'job_id' => $request->jid,
        'is_save' => '1',
        'nurse_id' => $nurse->id,
      ];

      if (empty($rec)) {

        JobSaved::create($input);

        $img = asset('frontend/img/bookmark.png');
        $message = 'Job saved successfully.';
      } else {

        if ($rec->is_save == '1') {

          $input['is_save'] = '0';
          $img = asset('frontend/img/job-icon-bx-Vector.png');
          $message = 'Job unsaved successfully.';
        } else {

          $input['is_save'] = '1';
          $img = asset('frontend/img/bookmark.png');
          $message = 'Job saved successfully.';
        }

        $rec->update($input);
      }

      return new JsonResponse(['success' => true, 'msg' => $message, 'img' => $img], 200);
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function apply_on_jobs(Request $request)
  {

    try {

      DB::beginTransaction();

      $request->validate([
        'jid' => 'required',
      ]);
      $response = [];
      $user = auth()->guard('frontend')->user();
      $job = Job::findOrFail($request->jid);
      // return $job;
      //return response()->json(['data'=>$job], 200);
      $rec = Offer::where(['worker_user_id' => $user->nurse->id, 'job_id' => $request->jid])
        ->whereNull('deleted_at')
        ->first();
      $input = [
        // Summary
        'job_id' => $request->jid,
        'worked_at_facility_before' => $request->worked_at_facility_before,
        'created_by' => $job->created_by,
        'worker_user_id' => $user->nurse->id,
        'job_name' => $request->job_name,
        'job_name' => $job->job_name,
        'type' => $job->job_type,
        'terms' => $job->terms,
        'profession' => $job->profession,
        'specialty' => $job->preferred_specialty,
        'actual_hourly_rate' => $job->actual_hourly_rate,
        'weekly_pay' => $job->weekly_pay,
        'hours_per_week' => $job->hours_per_week,
        'state' => $job->job_state,
        'city' => $job->job_city,
        // Shift
        'preferred_shift_duration' => $job->preferred_shift_duration,
        'guaranteed_hours' => $job->guaranteed_hours,
        'hours_shift' => $job->hours_shift,
        'weeks_shift' => $job->weeks_shift,
        'preferred_assignment_duration' => $job->preferred_assignment_duration,
        'start_date' => $job->start_date,
        'end_date' => $job->end_date,
        'rto' => $job->rto,
        'overtime' => $job->overtime,
        'on_call_rate' => $job->on_call_rate,
        'call_back_rate' => $job->call_back_rate,
        'orientation_rate' => $job->orientation_rate,
        'weekly_taxable_amount' => $job->weekly_taxable_amount,
        'weekly_non_taxable_amount' => $job->weekly_non_taxable_amount,
        'feels_like_per_hour' => $job->feels_like_per_hour,
        'goodwork_weekly_amount' => $job->goodwork_weekly_amount,
        'referral_bonus' => $job->referral_bonus,
        'sign_on_bonus' => $job->sign_on_bonus,
        'completion_bonus' => $job->completion_bonus,
        'extension_bonus' => $job->extension_bonus,
        'other_bonus' => $job->other_bonus,
        'pay_frequency' => $job->pay_frequency,
        'benefits' => $job->benefits,
        'total_organization_amount' => $job->total_organization_amount,
        'total_goodwork_amount' => $job->total_goodwork_amount,
        'total_contract_amount' => $job->total_contract_amount,
        // Location 
        'clinical_setting' => $job->clinical_setting,
        'preferred_work_location' => $job->preferred_work_location,
        'facility_name' => $job->facility_name,
        'facilitys_parent_system' => $job->facilitys_parent_system,
        'facility_shift_cancelation_policy' => $job->facility_shift_cancelation_policy,
        'contract_termination_policy' => $job->contract_termination_policy,
        'traveler_distance_from_facility' => $job->traveler_distance_from_facility,
        'job_location' => $job->job_location,
        // Certifications
        'certificate' => $job->certificate,
        // Work Info
        'description' => $job->description,
        'urgency' => $job->urgency,
        'as_soon_as' => $job->as_soon_as,
        'preferred_experience' => $job->preferred_experience,
        'number_of_references' => $job->number_of_references,
        'skills' => $job->skills,
        'on_call' => $job->on_call,
        'block_scheduling' => $job->block_scheduling,
        'float_requirement' => $job->float_requirement,
        'Patient_ratio' => $job->Patient_ratio,
        'Emr' => $job->Emr,
        'Unit' => $job->Unit,
        // ID & Tax Info
        'nurse_classification' => $job->nurse_classification,
        // Medical Info
        'vaccinations' => $job->vaccinations,
        // other
        'scrub_color' => $job->scrub_color,
        'holiday' => $job->holiday,
        'organization_weekly_amount' => $job->organization_weekly_amount,
        'tax_status' => $job->tax_status,
        'status' => 'Apply',
        'recruiter_id' => $job->recruiter_id,
        'organization_id' => $job->organization_id,
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

      $time = now()->toDateTimeString();
      event(new NotificationJob('Apply', false, $time, $job->created_by, $user->id, $user->full_name, $request->jid, $job->job_name));

      $message = $user->full_name . ' has applied to job ' . $job->id;
      $idOrganization = $job->organization_id;
      $recruiter_id = $job->recruiter_id;
      $idWorker = $user->id;
      $role = 'ADMIN';
      $type = 'text';
      $fileName = null;
      event(new NewPrivateMessage($message, $idOrganization, $recruiter_id, $idWorker, $role, $time, $type, $fileName));
      //event(new NotificationOffer($status, false, $time, $receiver, $recruiter_id, $full_name, $jobid, $job_name, $offer_id));

      DB::commit();

      return new JsonResponse(['success' => true, 'msg' => 'Applied to job successfully'], 200);
    } catch (\Exception $e) {
      DB::rollBack();
      // return err :
      // return new JsonResponse(['success' => false, 'msg' => $e->getMessage()], 500);
      return response()->json(["success" => false,"message" => "Something went wrong." ] , 500);
    }
  }

  // 
  public function thanks_for_applying()
  {
    $data = [];
    $data['user'] = $user = auth()->guard('frontend')->user();

    return view('worker::dashboard.details.thanks_for_applying_msg', compact('data'));
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
        'bank_name_payment' => 'required',
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


  public function send_support_ticket(Request $request)
  {
    try {
      $validatedData = $request->validate([
        'support_subject_issue' => 'required|max:500',
        'support_subject' => 'required',
      ]);

      $user = Auth::guard('frontend')->user();
      $user_email = $user->email;
      $email_data = ['support_subject_issue' => $request->support_subject_issue, 'support_subject' => $request->support_subject, 'worker_email' => $user_email];
      Mail::to('support@goodwork.com')->send(new support($email_data));

      return response()->json(['status' => true, 'message' => 'Support ticket sent successfully']);
    } catch (ValidationException $e) {
      return response()->json(['status' => false, 'message' => $e->errors()]);
    } catch (\Exception $e) {
      return response()->json(['status' => false, 'message' => $e->getMessage()]);
    }
  }

  public function disactivate_account(Request $request)
  {
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


  public function store_counter_offer(Request $request)
  {
    try {


      $user = auth()->guard('frontend')->user();

      $full_name = $user->first_name . ' ' . $user->last_name;
      $nurse = Nurse::where('user_id', $user->id)->first();
      $job_data = Job::where('id', $request->jobid)->first();
      //return response()->json([$job_data,$request->jobid]);
      $offer = Offer::where('id', $request->offer_id)->first();
      // return response()->json(['success' => false, 'message' => $job_data]);
      $update_array['job_name'] = $job_data->job_name != $request->job_name ? $request->job_name : $job_data->job_name;
      $update_array['type'] = $job_data->type != $request->type ? $request->type : $job_data->type;
      $update_array['terms'] = $job_data->terms != $request->terms ? $request->terms : $job_data->terms;
      $update_array['profession'] = $job_data->profession != $request->profession ? $request->profession : $job_data->profession;
      $update_array['block_scheduling'] = $job_data->block_scheduling != $request->block_scheduling ? $request->block_scheduling : $job_data->block_scheduling;
      $update_array['float_requirement'] = $job_data->float_requirement != $request->float_requirement ? $request->float_requirement : $job_data->float_requirement;
      $update_array['facility_shift_cancelation_policy'] = $job_data->facility_shift_cancelation_policy != $request->facility_shift_cancelation_policy ? $request->facility_shift_cancelation_policy : $job_data->facility_shift_cancelation_policy;
      $update_array['contract_termination_policy'] = $job_data->contract_termination_policy != $request->contract_termination_policy ? $request->contract_termination_policy : $job_data->contract_termination_policy;
      $update_array['traveler_distance_from_facility'] = $job_data->traveler_distance_from_facility != $request->traveler_distance_from_facility ? $request->traveler_distance_from_facility : $job_data->traveler_distance_from_facility;
      //$update_array['job_id'] = $request->jobid;
      //$update_array['recruiter_id'] = $job_data->recruiter_id != $request->recruiter_id ? $request->recruiter_id : $job_data->recruiter_id;

      //$update_array['worker_user_id'] = $nurse->id;
      $update_array['clinical_setting'] = $job_data->clinical_setting != $request->clinical_setting ? $request->clinical_setting : $job_data->clinical_setting;
      $update_array['Patient_ratio'] = $job_data->Patient_ratio != $request->Patient_ratio ? $request->Patient_ratio : $job_data->Patient_ratio;
      $update_array['Emr'] = $job_data->Emr != $request->Emr ? $request->Emr : $job_data->Emr;
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




      $update_array['weekly_pay'] = $job_data->weekly_pay;
      $update_array['hours_per_week'] = $update_array['weeks_shift'] * $update_array['hours_shift'];
      $update_array['weekly_taxable_amount'] = $update_array['hours_per_week'] * $update_array['actual_hourly_rate'];
      $update_array['organization_weekly_amount'] = $update_array['weekly_taxable_amount'] + $update_array['weekly_non_taxable_amount'];
      $update_array['total_organization_amount'] = ($update_array['preferred_assignment_duration'] * $update_array['organization_weekly_amount']) + ($update_array['sign_on_bonus'] + $update_array['completion_bonus']);
      $update_array['goodwork_weekly_amount'] = ($update_array['organization_weekly_amount']) * 0.05;
      $update_array['total_goodwork_amount'] = $update_array['goodwork_weekly_amount'] * $update_array['preferred_assignment_duration'];
      $update_array['total_contract_amount'] = $update_array['total_goodwork_amount'] + $update_array['total_organization_amount'];


      $update_array['is_draft'] = !empty($request->is_draft) ? $request->is_draft : '0';
      $update_array['is_counter'] = '1';
      //$update_array['recruiter_id'] = $job_data->recruiter_id;
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
            'organization_recruiter_id' => $job->created_by,
            'nurse_id' => $nurse->id,
            'details' => 'more infos',
          ]);
        }
      } else {
        $job = Offer::create($update_array);
        $offers_log = OffersLogs::create([
          'original_offer_id' => $job->id,
          'status' => 'Counter',
          'organization_recruiter_id' => $job->created_by,
          'nurse_id' => $nurse->id,
          'details' => 'more infos',
          'counter_offer_by' => 'nurse'
        ]);
      }

      // event offer notification
      $id = $offerexist->id;
      $jobid = $offerexist->job_id;
      $nurse_id = $nurse->id;
      $time = now()->toDateTimeString();
      $receiver = $offerexist->recruiter_id;
      $job_name = Job::where('id', $jobid)->first()->job_name;

      event(new NotificationOffer('Offered', false, $time, $receiver, $nurse_id, $full_name, $jobid, $job_name, $id));

      return response()->json(['success' => true, 'msg' => 'Counter offer created successfully']);
    } catch (\Exception $e) {
      return response()->json(['error message' => $e->getMessage()]);
    }
  }

  public function update_worker_profile_picture(Request $request)
  {
    try {
      $user = Auth::guard('frontend')->user();

      if ($request->hasFile('profile_pic')) {
        $file = $request->file('profile_pic');
        $filename = time() . $user->id . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);
        $user->image = $filename;
        $user->save();
      }
      return response()->json(['status' => true, 'message' => 'Profile image updated successfully']);
    } catch (ValidationException $e) {
      return response()->json(['status' => false, 'message' => $e->errors()]);
    } catch (\Exception $e) {
      return response()->json(['status' => false, 'message' => $e->getMessage()]);
    }
  }
}
