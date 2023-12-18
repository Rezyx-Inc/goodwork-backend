<?php

namespace App\Http\Controllers;

use App\Models\{User, Nurse, Availability};
use Illuminate\Http\Request;
use Auth;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Mail\register;

class AuthController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'mobile' => ['required', 'regex:/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/'],
            //'email' => 'required|email|max:255',
            'email' => 'email:rfc,dns'
        ]);

        if ($validator->fails()) {
            $data = [];
            $data['msg'] = $validator->errors()->first();
            ;
            $data['success'] = false;
            return response()->json($data);

        } else {
            $check = User::where(['email' => $request->email])->whereNull('deleted_at')->first();
            if (!empty($check)) {
                $data = [];
                $data['msg'] = 'Already exist.';
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
            $email_data = ['name' => $model->first_name . ' ' . $model->last_name, 'subject' => 'Registration'];
            Mail::to($model->email)->send(new register($email_data));


            $otp = $this->rand_number(4);
            $model->update(['otp' => $otp, 'otp_expiry' => date('Y-m-d H:i:s', time() + 300)]);


            $response['msg'] = 'You are registered successfully! an OTP sent to your registered email and mobile number.';




            return response()->json($response);
        }

    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'verification_code');

        $user = User::where('email', $credentials['email'])
            ->where('otp', $credentials['verification_code'])
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        
        // Generate JWT for the user
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('token'));
    }

}
