<?php

namespace Modules\Employer\Http\Controllers;


use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\{Response, JsonResponse};
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\login;
use App\Mail\register;

class EmployerAuthController extends Controller
{

    

    public function __construct()
    {
        
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('employer::layouts.main');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */    
    public function get_login()
    {
        return view('employer::auth.login');
    }

    public function get_signup() {
        return view('employer::auth.signup');
    }


    public function post_login(Request $request) {
        if ($request->ajax()) {
            if ($request->ajax()) {
                $data_msg = [];
                $input = $request->only('id');
                $model = User::where('email', '=', $input['id'])->orWhere('mobile',$input['id'])->where('ROLE', 'EMPLOYER')->where("active","1")->first();
                
                session()->put('otp_user_id', $model->id);
                session()->save();
                // Check if the value has been stored in the session
                // Checked

                $otp = $this->rand_number(4);
                $model->update(['otp'=>$otp, 'otp_expiry'=>date('Y-m-d H:i:s', time()+300)]);

                 // sending email verification otp after registring 
                 $email_data = ['name'=>$model->first_name.' '.$model->last_name,'otp'=>$otp,'subject'=>'One Time for login'];
                 Mail::to($model->email)->send(new login($email_data));

                $data_msg['msg'] = 'OTP sent to your registered email and mobile number.';
                $data_msg['success'] = true;
                $data_msg['link'] = Route('employer.verify');

                return response()->json($data_msg);
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
                return view('employer::auth.verify', $data);
            }
        }
        return redirect()->route('employer::layouts.main');
    }

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
                    'role' => 'EMPLOYER',
                ]);

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
                $response['link'] = Route('employer.verify');

                return response()->json($response);
            }
        }
    }

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
                    $user->update($input);
                    // Auth::guard('frontend')->login($user, true);
                    Auth::guard('employer')->login($user, true);
                    session()->forget('otp_user_id');

                    $response['link'] = route('employer-dashboard');
                    if (session()->has('intended_url')) {
                        $response['link'] = session()->get('intended_url');
                        session()->forget('intended_url');
                    }
                }
            }
            return response()->json($response);
        }
    }
    public function logout(Request $request) {
        $guard = "employer";
        Auth::guard($guard)->logout();
        $request->session()->invalidate();
        // $request->session()->regenerateToken(); 
        return redirect()->route('/')->with('success', 'You are successfully logged out.');
    }

    public function rand_number($digits) {
        $alphanum = "123456789" . time();
        $rand = substr(str_shuffle($alphanum), 0, $digits);
        return $rand;
    }
}
