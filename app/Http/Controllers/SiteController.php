<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Mail\login;
use App\Mail\register;
use Illuminate\Http\Request;
use Illuminate\Http\{Response, JsonResponse};
use Illuminate\Support\Facades\{ Hash, Auth, Session, Cache };
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Traits\HelperTrait;
use Carbon\Carbon;
use DB;
use Validator;
use App\Models\Job;
use Illuminate\Support\Facades\Artisan;

// ************ Requests ************
use App\Http\Requests\{LoginRequest, SignupRequest, ForgotRequest, ResetRequest, OTPRequest, ContactUsRequest};
// ************ models ************
use App\Models\{User, States, Countries, Nurse, Availability,Keyword};

class SiteController extends Controller {

    use HelperTrait,
    AuthenticatesUsers;


    public function test()
    {
        return view('user.dashboard');
    }


    /** Index page */
    public function index(Request $request) {
        $data = [];
        return view('site.index', $data);
    }

    /** Registration page */
    public function signup(Request $request) {
        $data = [];
        return view('site.signup', $data);
    }

    /** Login page */
    public function login(Request $request) {
        $data = [];
        return view('site.login', $data);
    }

    /** forgot password page */
    public function forgot_password(Request $request) {
        $data = [];
        return view('site.forgot_password', $data);
    }

    /** about us page */
    public function about_us(Request $request) {
        $data = [];
        // $data['model'] = Cms::where('slug','about-us')->first();
        return view('site.about_us', $data);
    }

    /** for-employers page */

    public function for_employers(Request $request){
        return view('site.for_employers');
    }

    /** for-employers page */

    public function for_recruiters(Request $request){
        return view('site.for_recruiters');
    }

    /** explore-jobs page */

    public function explore_jobs(Request $request){

        try {

           // commenting this for now we need to return only jobs data

        $data = [];


        $data['professions'] = Keyword::where(['filter'=>'Profession','active'=>'1'])->get();
        $data['terms'] = Keyword::where(['filter'=>'jobType','active'=>'1'])->get();
        $data['prefered_shifts'] = Keyword::where(['filter'=>'PreferredShift','active'=>'1'])->get();
        $data['usa'] = $usa =  Countries::where(['iso3'=>'USA'])->first();
        $data['us_states'] = States::where('country_id', $usa->id)->get();
        // $data['us_cities'] = Cities::where('country_id', $usa->id)->get();

        $data['profession'] = isset($request->profession) ? $request->profession : '';
        $data['speciality'] = isset($request->speciality) ? $request->speciality : '';
        $data['experience'] = isset($request->experience) ? $request->experience : '';
        $data['city'] = isset($request->city) ? $request->city : '';
        $data['state'] = isset($request->state) ? $request->state : '';
        $data['job_type'] = isset($request->job_type) ? explode('-', $request->job_type) : [];
        $data['start_date'] = isset($request->start_date) ? $request->start_date : '';
        $data['end_date'] = isset($request->end_date) ? $request->end_date : '';
        $data['shifts'] = isset($request->shifts) ? explode('-',$request->shifts) : [];
        $data['auto_offers'] = isset($request->auto_offers) ? $request->auto_offers : 0;

        $data['weekly_pay_from'] = isset($request->weekly_pay_from) ? $request->weekly_pay_from : 10;
        $data['weekly_pay_to'] = isset($request->weekly_pay_to) ? $request->weekly_pay_to : 10000;
        $data['hourly_pay_from'] = isset($request->hourly_pay_from) ? $request->hourly_pay_from : 2;
        $data['hourly_pay_to'] = isset($request->hourly_pay_to) ? $request->hourly_pay_to : 24;
        $data['hours_per_week_from'] = isset($request->hours_per_week_from) ? $request->hours_per_week_from : 10;
        $data['hours_per_week_to'] = isset($request->hours_per_week_to) ? $request->hours_per_week_to : 100;
        $data['assignment_from'] = isset($request->assignment_from) ? $request->assignment_from : 10;
        $data['assignment_to'] = isset($request->assignment_to) ? $request->assignment_to : 150;




        // $checkoffer = DB::table('blocked_users')->where('worker_id', $nurse['id'])->first();

        $whereCond = [
            'facilities.active' => true,
            'jobs.is_open' => "1",
            'jobs.is_hidden' => "0",
            'jobs.is_closed' => "0",
            // 'job_saved.is_delete'=>'0',
            // 'job_saved.nurse_id'=>$user->id,
        ];

            $resl = Job::select('jobs.*','name')->where('jobs.active','1')
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


        return view('site.explore_jobs',$data);
        //return response()->json(['message' =>  $data['jobs']]);
    } catch (\Exception $e) {
        // Handle other exceptions


        // Display a generic error message or redirect with an error status
         // return redirect()->route('jobs.explore')->with('error', 'An unexpected error occurred. Please try again later.');
        return response()->json(['success' => false, 'message' =>  $e->getMessage()]);
    }

    }

    /** contact us page */
    public function contact_us(Request $request) {
        $data = [];
        return view('site.contact_us', $data);
    }

    /** faq page */
    public function faq(Request $request) {
        $data = [];
        $data['faqs'] = Faq::where('status', '1')->get();
        return view('site.faq', $data);
    }


    /** Privacy policy page */
    public function privacy_policy(Request $request) {
        $data = [];
        $data['model'] = Cms::where('slug','privacy')->first();
        return view('site.privacy_policy', $data);
    }

    /** Terms and conditions page */
    public function terms(Request $request) {
        $data = [];
        $data['model'] = Cms::where('slug','terms')->first();
        return view('site.terms', $data);
    }

    /**edit profile */
    public function my_profile() {
        $data = [];
        $id = Auth::guard('frontend')->user()->id;
        $data['model'] = User::findOrFail($id);
        if ($data['model']->type_id === '2') {
            $data['managers'] = User::select('id', 'first_name', 'last_name')->where(['type_id' => '3', 'status' => '1'])->get();
        }
        if (!Cache::has('statetbl')) {
            $states = State::select('id', 'name', 'abbrev', 'is_restrict')->where('is_restrict', '=', '0')->get();
            Cache::put('statetbl', $states);
        }
        $data['states'] = Cache::get('statetbl');
        return view('user.edit-profile', $data);
    }

    /** Signup form submit */
    public function post_signup(Request $request) {

        try{
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
                'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
                'mobile' => ['required', 'regex:/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/'],
                //'email' => 'required|email|max:255',
                'email' => 'email:rfc,dns'
            ]);

            if ($validator->fails()) {
                $data = [];
                $data['msg'] =$validator->errors()->first();;
                 $data['success'] = false;
                 return response()->json($data);

            }else{
                    $check = User::where(['email'=>$request->email])->whereNull('deleted_at')->first();
                    if (!empty($check)) {
                        $data = [];
                        $data['msg'] ='Already exist.';
                        $data['success'] = false;
                        return response()->json($data);
                    }


                $response = [];
                $model = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'mobile' => $request->mobile,
                    'email' => $request->email,
                    'user_name' => $request->email,
                    'active' => '1',
                    'role' => 'NURSE',
                ]);

                $nurse = Nurse::create([
                    'user_id' => $model->id,
                ]);
                $availability = Availability::create([
                    'nurse_id' => $nurse->id
                ]);

                // suppose Nurse role should be inserted on db, had this error : at register "{\"Errors\":\"There is no role named `NURSE`.\"}"
                // $model->assignRole('NURSE');


                 // sending mail infromation
                  $email_data = ['name'=>$model->first_name.' '.$model->last_name,'subject'=>'Registration'];
                  Mail::to($model->email)->send(new register($email_data));

                session()->put('otp_user_id', $model->id);
                $otp = $this->rand_number(4);
                $model->update(['otp'=>$otp,'otp_expiry'=>date('Y-m-d H:i:s', time()+300)]);


                // sending email verification otp after registring
                $email_data = ['name'=>$model->first_name.' '.$model->last_name,'otp'=>$otp,'subject'=>'One Time for login'];
                Mail::to($model->email)->send(new login($email_data));

                $response['msg'] = 'You are registered successfully! an OTP sent to your registered email and mobile number.';
                $response['success'] = true;
                $response['link'] = Route('verify');


                return response()->json($response);
            }
        }
    }catch(\Exception $e){
        $data = [];
       // $data['msg'] = $e->getMessage();
       $data['msg'] ='We encountered an error. Please try again later.';
        $data['success'] = false;
        return response()->json($data);
    }
    }

    /** Login form submit */
    public function post_login(Request $request) {
        try{
            // issue if the id is a phone numbe
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'id' => 'email',
            ]);
            if ($validator->fails()) {
                $data = [];
                // $data['msg'] = $e->getMessage();
                $data['msg'] ='it must be a valid email address';
                 $data['success'] = false;

                 return response()->json($data);
            }
            $data_msg = [];
            $input = $request->only('id');
            // $model = User::where('email', '=', $input['id'])->orWhere('mobile',$input['id'])->where('ROLE', 'NURSE')->where("active","1")->first();
            $model = User::where(function ($query) use ($input) {
                $query->where('email', $input['id'])
                    ->orWhere('mobile', $input['id']);
            })
            ->where('ROLE', 'WORKER')
            ->where('active', '1')
            ->first();
            if (isset($model)) {
            session()->put('otp_user_id', $model->id);
            $otp = $this->rand_number(4);
            $model->update(['otp'=>$otp, 'otp_expiry'=>date('Y-m-d H:i:s', time()+300)]);


           // sending mail verification
           $email_data = ['name'=>$model->first_name.' '.$model->last_name,'otp'=>$otp,'subject'=>'One Time for login'];
           Mail::to($model->email)->send(new login($email_data));

            $data_msg['msg'] = 'OTP sent to your registered email and mobile number.';
            $data_msg['success'] = true;
            $data_msg['link'] = Route('verify');

            return response()->json($data_msg);
            }else{
                $data = [];
                // $data['msg'] = $e->getMessage();
                $data['msg'] ='Wrong login information.';
                 $data['success'] = false;
                 return response()->json($data);
            }
        }
    }catch(\Exception $e){
        $data = [];
       // $data['msg'] = $e->getMessage();
       $data['msg'] ='We encountered an error. Please try again later.';
        $data['success'] = false;
        return response()->json($data);
    }
    }

    /** logout */
    public function logout(Request $request) {
        Auth::guard('frontend')->logout();
        $request->session()->invalidate();
        return redirect('/')->with('success', 'You are successfully logged out.');
    }


    /** resend otp */
    public function resend_otp(Request $request)
    {
        if ($request->ajax()) {
            $response = [];
            $response['success'] = false;
            if (session()->has('otp_user_id')) {
                $user = User::where(['id'=>session()->get('otp_user_id') , 'active'=>'1'])->first();
                if (!empty($user)) {
                    $response['success'] = true;
                    session()->put('otp_user_id', $user->id);
                    $otp = $this->rand_number(4);
                    $user->update(['otp'=>$otp, 'otp_expiry'=>date('Y-m-d H:i:s', time()+300)]);

                    $email_data = $this->getEmailData('otp-for', ['NAME'=>$user->first_name.' '.$user->last_name,'OTP'=> $otp, 'FOR'=> 'sign in']);
                    $email_data['to'] = $user->email;
                    $email_data['subject'] = 'One Time Password for login';
                    $this->sendMail($email_data);

                    $response['msg'] = 'A new OTP has been sent to your registered email and mobile number.';
                }
            }
            return response()->json($response, 200);
        }
    }

    /** otp submit and verification */
    public function submit_otp(Request $request)
    {
        if ($request->ajax()) {
            $response = [];
            $response['success'] = false;
            $response['msg'] = 'Wrong OTP.';
            if (session()->has('otp_user_id')) {
                $otp = (int) $request->otp1.$request->otp2.$request->otp3.$request->otp4;
                $user = User::where(['id'=>session()->get('otp_user_id'), 'active'=>'1','otp'=>$otp, ['otp_expiry', '>', date('Y-m-d H:i:s')]])->first();
                if (!empty($user)) {
                    $response['success'] = true;
                    $response['msg'] = 'You are logged in successfully.';
                    $input = [];
                    $input['otp'] = null;
                    $input['otp_expiry'] = null;
                    $user->update($input);
                    Auth::guard('frontend')->login($user, true);
                    session()->forget('otp_user_id');

                    $response['link'] = route('home');
                    if (session()->has('intended_url')) {
                        $response['link'] = session()->get('intended_url');
                        session()->forget('intended_url');
                    }
                }
            }
            return response()->json($response);
        }
    }

    /** get all states of a country and return it in json format */

    public function get_state(Request $request){

        if ($request->ajax()) {
            if ($request->has('country_id')) {
                $states = $this->get_states($request->country_id);
                $content = '';
                if ($states->count()) {
                    foreach($states as $s)
                    {
                        $content .= '<option value="'.$s->id.'">'.$s->name.'</option>';
                    }
                }else{
                    $content .= '<option>No state found.</option>';
                }
                $response = array('success'=>true ,'content'=>$content);
                return $response;
            }
        }
    }







    public function get_city(Request $request){

        if ($request->ajax()) {
            if ($request->has('state_id')) {
                $cities = $this->get_cities($request->state_id);
                $content = '';
                if ($cities->count()) {
                    foreach($cities as $c)
                    {
                        $content .= '<option value="'.$c->name.'">'.$c->name.'</option>';
                    }
                }else{
                    $content .= '<option value="">No city found.</option>';
                }
                $response = array('success'=>true ,'content'=>$content);
                return $response;
            }
        }
    }


    public function get_speciality(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('kid')) {
                $keywords = Keyword::where(['active'=>'1','filter'=>$request->kid])->get();
                $content = '<option value="">Select</option>';
                if ($keywords->count()) {
                    foreach($keywords as $k)
                    {
                        $content .= '<option value="'.$k->title.'">'.$k->title.'</option>';
                    }
                }else{
                    $content = '<option value="">No speciality found.</option>';
                }
                $response = array('success'=>true ,'content'=>$content);
                return $response;
            }
        }
    }

    public function verify() {

        if (session()->has('otp_user_id')) {
            $data = [];
            $user = User::findOrFail(session()->get('otp_user_id'));
            if (time() < strtotime($user->otp_expiry) ) {
                $expiry = strtotime($user->otp_expiry)- time();
                $data['expiry'] = ($expiry/60).':'.($expiry%60);
                return view('site.verify', $data);
            }
        }
        return redirect()->route('login');
    }

    public function get_dorpdown(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('filter')) {
                $keywords = Keyword::where(['active'=>'1','filter'=>$request->filter])->get();
                $content = '<option value="">Select</option>';
                if ($keywords->count()) {
                    foreach($keywords as $k)
                    {
                        $content .= '<option value="'.$k->title.'">'.$k->title.'</option>';
                    }
                }else{
                    $content = '<option value="">No data found.</option>';
                }
                $response = array('success'=>true ,'content'=>$content);
                return $response;
            }
        }
    }
    public function clear_cache() {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        return "Cache,View is cleared";
    }
}
