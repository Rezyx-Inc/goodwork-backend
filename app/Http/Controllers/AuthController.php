<?php

namespace App\Http\Controllers;

use App\Models\{User, Nurse, Availability,API_KEY};
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Mail;
use App\Mail\register;

class AuthController extends Controller
{
    public function __construct()
    {
        // $this->middleware('api', ['except' => ['login', 'register']]);
    }
    // public function register(Request $request)
    // {
    //     try{  
    //             $validator = Validator::make($request->all(), [
    //                 'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
    //                 'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
    //                 'mobile' => ['nullable','regex:/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/'],
    //                 'email' => 'email:rfc,dns'
    //             ]);
    //             if ($validator->fails()) {
    //                 $data = [];
    //                 $data['msg'] =$validator->errors()->first();;
                     
    //                  return response()->json($data);
    //             }else{
    //                 $check = User::where(['email'=>$request->email])->whereNull('deleted_at')->first();
    //                 if (!empty($check)) {
    //                     $data = [];
    //                     $data['msg'] ='Already exist.';
    //                     return response()->json($data);
    //                 }     
    //                 $model = User::create([
    //                     'first_name' => $request->first_name,
    //                     'last_name' => $request->last_name,
    //                     'mobile' => $request->mobile,
    //                     'email' => $request->email,
    //                     'user_name' => $request->email,
    //                     // we should add facility id
    //                     'facility_id'=>'1',
    //                     'active' => '1',
    //                     'role' => 'EMPLOYER',
    //                 ]);
    //                 $tokenResult = $model->createToken('authToken')->accessToken;    
    //                 return response()->json(['token'=>$tokenResult,'user'=>$model]);
    //         }
    //     }catch(\Exception $e){
    //         return response()->json(['msg'=>$e->getMessage()]);
    //     }

    // }

    public function authorize_access(Request $request)
    {
        $email = $request->input('email');
        

        
        // Check if the user exists
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['error' => $email], 401);
        }

        $key = $request->input('api_key');
        $api_key  = API_KEY::where('key', $key)->first();
        // Check if is a valid api key
        if (!$api_key) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Generate JWT for the user
        $token = $user->createToken('authToken')->accessToken;
        return response()->json(compact('token'));
    }

}
