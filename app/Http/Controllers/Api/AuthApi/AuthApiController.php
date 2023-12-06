<?php

namespace App\Http\Controllers\Api\AuthApi;

//MODELS :
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{

    public function login(Request $request)
    {
        if (isset($request->email) && $request->email != "" && isset($request->password) && $request->password != "" && isset($request->fcm_token) && $request->fcm_token != "") {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'active' => true])) {
                $return_data = [];
                $user_data = User::where('email', '=', $request->email)->get()->first();
                if (!empty($user_data) && $user_data != null) {
                    $user_data->fcm_token = $request->fcm_token;
                    if ($user_data->update()) {
                        $user = User::where('id', '=', $user_data->id)->get()->first();
                        if (isset($user->role) && $user->role == "NURSE") {
                            $return_data = $this->profileCompletionFlagStatus($type = "login", $user);
                        } else {
                            $return_data = $this->facilityProfileCompletionFlagStatus($type = "login", $user);
                        }
                        $this->check = "1";
                        $this->message = "Logged in successfully";
                    } else $this->message = "Problem occurred while updating the token, Please try again later";
                } else $this->message = "User record not found";

                $this->return_data = $return_data;
            } else {
                $this->message = "Invalid email or password";
            }
        } else {
            $this->message = $this->param_missing;
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function sendOtp(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            // 'phone_number' => 'required|regex:/^[0-9 \+]+$/|min:4|max:20',
            'id' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $user_info = USER::where('email', $request->id)->orWhere('mobile',$request->id);
            // $user_info = USER::where('email', $request->id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                if($user->role == strtoupper($request->role)){
                    $otp = substr(str_shuffle("0123456789"), 0, 4);
                    $rand_enc = substr(str_shuffle("0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz"), 0, 6);
                    $update_otp = USER::where(['id' => $user->id])->update(['otp' => $otp]);
                    if ($update_otp) {
                        $message = "Hi <b>".$user->first_name.' '.$user->last_name."</b>,\r\n<br><br>".
                                    "OTP: <b>" . $otp."</b> is your one time password for sign in\r\n<br><br>".
                                    "Thank you,\r\n<br>".
                                    "Team Goodwork\r\n<br><br>".
                                    "<b>Please do not reply to this email. </b>\r\n<br><br>".
                                    "<b>© 2023 GOODWORK. ALL RIGHTS RESERVED.</b><br>";

                        $from_user = "=?UTF-8?B?" . base64_encode('Goodwork') . "?=";
                        $subject = "=?UTF-8?B?" . base64_encode('One Time Password for login') . "?=";
                        $user_mail    =  env("MAIL_USERNAME");
                        
                        // $headers = "From: $from_user <team@goodwork.com>\r\n" .
                        $headers = "From: $from_user <$user_mail>\r\n" .
                            "MIME-Version: 1.0" . "\r\n" .
                            "Content-type: text/html; charset=UTF-8" . "\r\n";
    
                        mail($user->email, $subject, $message, $headers);
    
                        $this->check = "1";
                        if($user->mobile == $request->id){
                            $this->message = "OTP send successfully to your number";
                        }else{
                            $this->message = "OTP send successfully to your email";
                        }
                        $this->return_data = ['user_id' => $user->id,'otp' => $otp];
                    } else {
                        $this->message = "Failed to send otp, Please try again later";
                    }
                }else{
                    $this->message = "Role does not match with your user id! Please check";
                }
                
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    

    }

    public function mobileOtp(Request $request)  
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'id' => 'required',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('email', $request->id)->orWhere('mobile',$request->id);
            
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                if($user->role == strtoupper($request->role)){
                    $otp = substr(str_shuffle("0123456789"), 0, 4);
                    $rand_enc = substr(str_shuffle("0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz"), 0, 6);
                    $update_otp = USER::where(['id' => $user->id])->update(['otp' => $otp]);
                    if ($update_otp) {
                        $message = "OTP: " . $otp;
                        $sid    =  env("TWILIO_SID");
                        $token  =  env("TWILIO_AUTH_TOKEN");
                        $twilio = new Client($sid, $token);
                        $message = $twilio->messages
                        ->create($request->id, // to
                            array(
                            "from" => env("TWILIO_NUMBER"),
                            "body" => 'Your Account verification code is: '.$otp
                            )
                        );
                
                        $this->check = "1";
                        if($user->mobile == $request->id){
                            $this->message = "OTP send successfully to your number";
                        }else{
                            $this->message = "OTP send successfully to your email";
                        }
                        $this->return_data = ['user_id' => $user->id,'otp' => $otp];
                    } else {
                        $this->message = "Failed to send otp, Please try again later";
                    }
                }else{
                    $this->message = "Role does not match with your user id! Please check";
                }
                
            } else {
                $this->message = "User not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }
    public function getUser(Request $request) {
        return $request->user();
    }
}
