<?php

namespace Modules\Worker\Http\Controllers;

use DateTime;
use App\Models\Worker;
use App\Models\User;
use App\Models\Offer;
use App\Models\Country;
use App\Models\Job;
use App\Models\Follows;
use App\Models\Facility;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\{Response, JsonResponse};
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;
use App\Traits\HelperTrait;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use File;

/* *********** Requests *********** */
use App\Http\Requests\{UserEditProfile, ChangePasswordRequest, ShippingRequest, BillingRequest};
// ************ models ************
use App\Models\{Countries, Cities, States};

define('default_max_step', 5);
define('min_increment', 1);

define('USER_IMG_', asset('public/frontend/img/profile-pic-big.png'));

class WorkerDashboardController extends Controller
{

    use HelperTrait;
    /** dashboard page */
    public function dashboard()
    {
        $data = [];
        $data['user'] = $user = auth()->guard('frontend')->user();

        return view('worker.dashboard', $data);

    }
    /** verified users page */
    public function setting()
    {
        $data = [];
        $data['model'] = auth()->guard('frontend')->user();
        return view('user.profile', $data);
    }
    /** account settings page */
    public function account_setting()
    {
        $data = [];
        $data['model'] = auth()->guard('frontend')->user();
        $data['countries'] = Country::where('flag', 1)->get();
        return view('user.account_setting', $data);
    }

    /** update personal info */
    public function post_edit_profile(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->guard('frontend')->user();
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|max:100|regex:/^[a-zA-Z\s]+$/',
                'last_name' => 'required|max:100|regex:/^[a-zA-Z\s]+$/',
                'mobile' => 'required|min:10|max:15',
                // 'email' => 'required|email|max:255',
                'profile_picture' => 'mimes:jpg,jpeg,png',
            ]);
            if ($validator->fails()) {
                return new JsonResponse(['errors' => $validator->errors()], 422);
            }else{
                $input = $request->except(['email']);
                if ($request->hasFile('profile_picture')) {
                    if (!empty($user->image)) {
                        if (file_exists(public_path('images/workers/profile/'.$user->image))) {
                            File::delete(public_path('images/workers/profile/'.$user->image));
                        }
                    }
                    $file = $request->file('profile_picture');
                    $img_name = $file->getClientOriginalName() .'_'.time(). '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/workers/profile/'), $img_name);

                    $input['image'] = $img_name;
                }
                $input['updated_at'] = Carbon::now();
                $user->update($input);
                return new JsonResponse(['success' => true, 'msg'=>'Profile updated successfully.'], 200);
            }
        }
    }

    /** update password */
    public function post_change_password(ChangePasswordRequest $request)
    {
        if ($request->ajax()) {
            $user = Auth::guard('frontend')->user();
            $user->update(['password'=>Hash::make($request->new_password), 'updated_at'=>Carbon::now()->toDateTimeString()]);
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

    public function my_profile()
    {
        $data = [];
        $data['user'] = auth()->guard('frontend')->user();
        return view('dashboard.my_profile', $data);
    }

    public function messages()
    {
        $data = [];
        $data['user'] = auth()->guard('frontend')->user();
        return view('dashboard.messages', $data);
    }

    public function my_work_journey()
    {
        $data = [];
        $data['user'] = auth()->guard('frontend')->user();
        return view('dashboard.my_work_journey', $data);
    }

    public function explore()
    {
        $data = [];
        $data['user'] = auth()->guard('frontend')->user();
        return view('dashboard.explore', $data);
    }

}