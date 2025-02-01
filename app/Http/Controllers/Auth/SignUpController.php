<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Events\UserCreated;
use App\Mail\login;
use App\Mail\register;
use App\Models\Nurse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SignUpController extends Controller
{

	/** web */
	public function signup(Request $request)
	{
		$data = [];
		return view('auth.signup', $data);
	}


	/** API :: Signup form submit */
	public function post_signup(Request $request)
	{
        try {
            if ($request->ajax()) {
                $validator = Validator::make($request->all(), [
                    'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
                    'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
                    'mobile' => ['nullable', 'regex:/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/'],
                    'email' => [
						'required',
						'email:rfc,dns',
						'unique:users,email',
					],
					'organization_name' => ['nullable', 'unique:users,organization_name'],
                ],[
					'first_name.required' => 'The first name field is required.',
					'first_name.regex' => 'The first name may only contain letters and spaces.',
					'first_name.max' => 'The first name must not exceed 255 characters.',
				
					'last_name.required' => 'The last name field is required.',
					'last_name.regex' => 'The last name may only contain letters and spaces.',
					'last_name.max' => 'The last name must not exceed 255 characters.',
				
					'mobile.regex' => 'The mobile number format is invalid.',
				
					'email.required' => 'The email field is required.',
					'email.email' => 'The email format is invalid.',
					'email.unique' => 'This email has already been taken.',
				
					'organization_name.unique' => 'This organization name has already been taken.',
				]);

                if ($validator->fails()) {
                    $data = [];
                    $data['msg'] = $validator->errors()->first();
                    $data['success'] = false;
                    return response()->json($data);
                } else {
                    $response = [];
					$data = [
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'mobile' => $request->mobile,
                        'email' => $request->email,
                        'user_name' => $request->email,
                        // we should add facility id
                        'facility_id' => '1',
                        'active' => '1',
                        'role' => 'NURSE',
                    ];
					dd($data);
                    $model = User::create([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'mobile' => $request->mobile,
                        'email' => $request->email,
                        'user_name' => $request->email,
                        // we should add facility id
                        'facility_id' => '1',
                        'active' => '1',
                        'role' => 'NURSE',
                    ]);

                    Nurse::create([
                        'user_id' => $model->id,
                        'active' => '1'
                    ]);

                    // dispatching the event after creating user before validate
                    event(new UserCreated($model));

                    // sending mail infromation
                    $email_data = ['name' => $model->first_name . ' ' . $model->last_name, 'subject' => 'Registration'];
                    Mail::to($model->email)->send(new register($email_data));

                    session()->put('otp_user_id', $model->id);
                    $otp = $this->rand_number(4);
                    $model->update(['otp' => $otp, 'otp_expiry' => date('Y-m-d H:i:s', time() + 300)]);

                    // sending email verification otp after registring
                    $email_data = ['name' => $model->first_name . ' ' . $model->last_name, 'otp' => $otp, 'subject' => 'One Time for login'];
                    Mail::to($model->email)->send(new login($email_data));

                    $response['msg'] = 'You are registered successfully! an OTP sent to your registered email and mobile number.';
                    $response['success'] = true;
                    $response['link'] = Route('worker.verify');

                    return response()->json($response);
                }
            }
        } catch (\Exception $e) {
            $data = [];
            $data['msg'] = $e->getMessage();
            //$data['msg'] ='We encountered an error. Please try again later.';
            $data['success'] = false;
            return response()->json($data);
        }
	}
}
