<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\{Response, JsonResponse};
/******* Request ******/
use Modules\Admin\Http\Requests\{LoginRequest, EditProfileRequest, ChangePasswordRequest, SignupRequest};
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends AdminController {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function get_login() {
        return view('admin::auth.login');
    }


    /** Login form submit */
    public function post_login(Request $request) {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255',
                'password' => 'required|min:6',
            ]);

            $validator->after(function ($validator) use ($request){
                $model = User::whereNull('deleted_at')->where(['email'=>$request->email, "active"=>'1'])->first();
                // dd($model);
                if (!empty($model)) {
                    $guard="admin";
                    if (Hash::check($request->password, $model->password)) {

                        if (Auth::guard($guard)->attempt(['email' => $model->email, 'password' => $request->password, 'active' => '0'])){
                            $validator->errors()->add('email', "Your account is in-active.");
                        }
                        else {

                        }
                    } else
                        $validator->errors()->add('password', "Incorrect Password.");
                } else
                    $validator->errors()->add('email', "User not found. Please sign up to login.");
            });

            if ($validator->fails()) {
                return new JsonResponse(['errors' => $validator->errors()], 422);
            }else{
                $data_msg = [];
                $guard = 'admin';
                $input = $request->only('email');
                $model = User::where('email', '=', $input['email'])->where("active","=","1")->first();

               if ($request->has('rememberMe') && !empty($request->input('rememberMe'))) {
                   $expire = time() + 172800;
                   setcookie('verifine_user_email', $request->input('email'), $expire);
                   setcookie('verifine_user_password', $request->input('password'), $expire);
               } else {
                   $expire = time() - 172800;
                   setcookie('verifine_user_email', '', $expire);
                   setcookie('verifine_user_password', '', $expire);
               }
                Auth::guard($guard)->login($model);
                $model->last_login_at = Carbon::now()->toDateTimeString();
                $model->last_login_ip = $request->ip();
                $model->save();

                $data_msg['msg'] = 'You are successfully logged in.';
                $data_msg['success'] = true;
                $data_msg['link'] = Route('admin-dashboard');
                if (session()->has('intended_url')) {
                    $data_msg['link'] = session()->get('intended_url');
                    session()->forget('intended_url');
                }
                return response()->json($data_msg);
            }
        }
    }


    /** Signup form submit */
    public function post_signup(SignupRequest $request) {
        if ($request->ajax()) {
            $data = [];
            $input = $request->input();
            $name = explode(' ', $input['name']);
            $input['first_name'] = $name[0] ?? '';
            $input['last_name'] = $name[1] ?? '';
            $input['email'] = $request->input('email');
            $input['type_id'] = $request->input('type');
            $input['password'] = Hash::make($request->input("password"));
            $input['ip_address'] = $request->ip();
            $input['email_verification'] = '0';
            $input['status'] = '0';
            $input['active_token'] = $this->rand_string(20);
            $input['created_at'] = Carbon::now()->toDateTimeString();
            $user = UserMaster::create($input);
            $this->sendActivationMail($user);
            $data['msg'] = 'A mail has been sent to your email for email verification.Also check your spam or junk file for email';
            $data['link'] = route('/');
            $data['success'] = true;
            return response()->json($data);
        }
    }

    /** logout */
    public function logout(Request $request) {
        $guard = "admin";
        Auth::guard($guard)->logout();
        $request->session()->invalidate();
        return redirect()->route('admin.login')->with('success', 'You are successfully logged out.');
    }

}
