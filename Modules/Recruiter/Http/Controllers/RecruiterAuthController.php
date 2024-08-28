<?php

namespace Modules\Recruiter\Http\Controllers;

use Modules\Recruiter\Http\Controllers\CommonFunctionController;
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
use App\Events\UserCreated;

class RecruiterAuthController extends Controller
{

    protected $commonFunctionController;

    public function __construct()
    {
        $this->commonFunctionController = new CommonFunctionController();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('recruiter::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('recruiter::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('recruiter::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('recruiter::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function get_login()
    {
        return view('recruiter::auth.login');
    }

    public function get_signup() {
        return view('recruiter::auth.signup');
    }

    public function post_login(Request $request) {
        try{
        if ($request->ajax()) {

                $validator = Validator::make($request->all(), [
                    'id' => 'email:rfc,dns',
                ]);
                if ($validator->fails()) {
                    $data = [];
                    // $data['msg'] = $e->getMessage();
                    $data['msg'] =$validator->errors()->first();
                    $data['success'] = false;

                     return response()->json($data);
                }
                $data_msg = [];
                $input = $request->only('id');

                // $model = User::where('email', '=', $input['id'])->orWhere('mobile',$input['id'])->where('ROLE', 'RECRUITER')->where("active","1")->first();

                $model = User::where(function ($query) use ($input) {
                    $query->where('email', $input['id'])
                        ->orWhere('mobile', $input['id']);
                })
                ->where('ROLE', 'RECRUITER')
                ->where('active', '1')
                ->first();

                if (isset($model)) {
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
                $data_msg['link'] = Route('recruiter.verify');

                return response()->json($data_msg);
            }else{
                $data = [];
                // $data['msg'] = $e->getMessage();
                $data['msg'] ='Wrong login information. Have you created an account ?';
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

    public function verify() {
       if (session()->has('otp_user_id')) {
            $data = [];
            $user = User::findOrFail(session()->get('otp_user_id'));
            if (time() < strtotime($user->otp_expiry) ) {
                $expiry = strtotime($user->otp_expiry)- time();
                $data['expiry'] = ($expiry/60).':'.($expiry%60);
                return view('recruiter::auth.verify', $data);
            }
        }
        return redirect()->route('recruiter::recruiter.dashboard');
    }

    public function post_signup(Request $request) {
        try{
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
                'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
                'mobile' => ['nullable','regex:/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/'],
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
                    'role' => 'RECRUITER',
                ]);

                // dispatching the event after creating user before validate
                // new trigger needed for recruter or passing role as params 
                // event(new UserCreated($model));

                 // sending mail infromation
                 $email_data = ['name'=>$model->first_name.' '.$model->last_name,'subject'=>'Registration'];
                 Mail::to($model->email)->send(new register($email_data));

                session()->put('otp_user_id', $model->id);
                session()->save();
                $otp = $this->rand_number(4);
                $model->update(['otp'=>$otp,'otp_expiry'=>date('Y-m-d H:i:s', time()+300)]);

                 // sending email verification otp after registring
                 $email_data = ['name'=>$model->first_name.' '.$model->last_name,'otp'=>$otp,'subject'=>'One Time for login'];
                 Mail::to($model->email)->send(new login($email_data));

                $response['msg'] = 'You are registered successfully! an OTP sent to your registered email and mobile number.';
                $response['success'] = true;
                $response['link'] = Route('recruiter.verify');

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
                    Auth::guard('recruiter')->login($user, true);
                    session()->forget('otp_user_id');

                     // generate accesstoken

                //    $token = $user->createToken('authToken')->accessToken;
                $token = $user->createToken('authToken',['some_Permession'])->accessToken;

                    $response['link'] = route('recruiter-dashboard');
                    if (session()->has('intended_url')) {
                        $response['link'] = session()->get('intended_url');
                        session()->forget('intended_url');
                    }
                }
            }
            return response()->json($response);
        }
    }
    /** resend otp */
    public function resend_otp(Request $request)
    {
        if ($request->ajax()) {
            $response = [];
            $response['success'] = false;
            if (session()->has('otp_user_id')) {
                $model = User::where(['id'=>session()->get('otp_user_id') , 'active'=>'1'])->first();
                if (!empty($model)) {
                  session()->put('otp_user_id', $model->id);            
                  $otp = $this->rand_number(4);            
                  $model->update(['otp'=>$otp, 'otp_expiry'=>date('Y-m-d H:i:s', time()+300)]);         
                  $email_data = ['name'=>$model->first_name.' '.$model->last_name,'otp'=>$otp,'subject'=>'One Time for login'];           
                  Mail::to($model->email)->send(new login($email_data));            
                  $data_msg['msg'] = 'OTP sent to your registered email and mobile number.';           
                  $data_msg['success'] = true;            
                  $data_msg['link'] = Route('worker.verify');           
                  return response()->json($data_msg);
                }
            }
            return response()->json($response, 200);
        }
    }
    public function logout(Request $request) {
        $guard = "recruiter";
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
