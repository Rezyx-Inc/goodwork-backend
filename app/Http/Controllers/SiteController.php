<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\{Response, JsonResponse};
use Illuminate\Support\Facades\{ Hash, Auth, Session, Cache };
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Traits\HelperTrait;
use Carbon\Carbon;
use DB;
use Validator;
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
        $data['model'] = Cms::where('slug','about-us')->first();
        return view('site.about_us', $data);
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
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|max:255',
                'last_name' => 'required',
                'mobile'=> 'nullable|max:255',
                'email' => 'email|max:255',
            ]);

            $validator->after(function($validator) use ($request){
                $check = User::where(['email'=>$request->email])->whereNull('deleted_at')->first();
                if (!empty($check)) {
                    $validator->errors()->add('email', 'Already exist.');
                }
            });
            if ($validator->fails()) {
                return new JsonResponse(['errors' => $validator->errors()], 422);
            }else{
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
                $model->assignRole('NURSE');

                $email_data = $this->getEmailData('new_registration', ['NAME'=>$model->first_name.' '.$model->last_name]);
                $email_data['to'] = $model->email;
                $this->sendMail($email_data);

                session()->put('otp_user_id', $model->id);
                $otp = $this->rand_number(4);
                $model->update(['otp'=>$otp,'otp_expiry'=>date('Y-m-d H:i:s', time()+300)]);

                $email_data = $this->getEmailData('otp-for', ['NAME'=>$model->first_name.' '.$model->last_name,'OTP'=> $otp, 'FOR'=> 'sign in']);
                $email_data['to'] = $model->email;
                $email_data['subject'] = 'One Time Password for login';
                $this->sendMail($email_data);

                $response['msg'] = 'You are registered successfully! an OTP sent to your registered email and mobile number.';
                $response['success'] = true;
                $response['link'] = Route('verify');


                return response()->json($response);
            }
        }
    }

    /** Login form submit */
    public function post_login(LoginRequest $request) {
        if ($request->ajax()) {
            $data_msg = [];
            $input = $request->only('id');
            $model = User::where('email', '=', $input['id'])->orWhere('mobile',$input['id'])->where('ROLE', 'NURSE')->where("active","1")->first();
            session()->put('otp_user_id', $model->id);
            $otp = $this->rand_number(4);
            $model->update(['otp'=>$otp, 'otp_expiry'=>date('Y-m-d H:i:s', time()+300)]);

            $email_data = $this->getEmailData('otp-for', ['NAME'=>$model->first_name.' '.$model->last_name,'OTP'=> $otp, 'FOR'=> 'sign in']);
            $email_data['to'] = $model->email;
            $email_data['subject'] = 'One Time Password for login';
            $this->sendMail($email_data);

            $data_msg['msg'] = 'OTP sent to your registered email and mobile number.';
            $data_msg['success'] = true;
            $data_msg['link'] = Route('verify');

            return response()->json($data_msg);
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

                    $response['link'] = route('dashboard');
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
}
