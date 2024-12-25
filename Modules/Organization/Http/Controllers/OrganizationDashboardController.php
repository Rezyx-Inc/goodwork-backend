<?php

namespace Modules\Organization\Http\Controllers;

use DateTime;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\support;
use Illuminate\Support\Facades\Http;
use App\Mail\VerifyNewMail;

/** Models */

use App\Models\{Profession, Speciality, Notification, User, Nurse, Follows, NurseReference, Job, Offer, NurseAsset, Keyword, Facility, Availability, Countries, States, Cities, JobSaved, State};

define('USER_IMG_ORGANIZATION', asset('public/frontend/img/profile-pic-big.png'));

class OrganizationDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function index()
    {
        $id = Auth::guard('organization')->user()->id;
        $alljobs = Job::where('organization_id', $id)->get();

        $statusList = ['Apply', 'Screening', 'Submitted', 'Offered', 'Onboarding', 'Cleared', 'Working'];
        $statusCounts = array_fill_keys($statusList, 0);

        foreach ($alljobs as $key => $value) {
            if (isset($value->id)) {
                $statusCountsQuery = Offer::whereIn('status', $statusList)
                    ->select(\DB::raw('status, count(*) as count'))
                    ->where('job_id', $value->id)
                    ->groupBy('status')
                    ->get();

                foreach ($statusCountsQuery as $statusCount) {
                    $statusCounts[$statusCount->status] += $statusCount->count;
                }
            }
        }

        $statusCounts = array_values($statusCounts);

        return view('organization::dashboard', compact('statusCounts'));
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('organization::create');
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
        return view('organization::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('organization::edit');
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

    public function profile(Request $request)
    {
        $id = Auth::guard('organization')->user()->id;
        // facility_id qualities about_me are been added in user table
        $type = $request->route('type');

        $user = User::select('first_name', 'last_name', 'image', 'user_name', 'email', 'date_of_birth', 'mobile', 'about_me', 'qualities', 'facility_id')->find($id);
        $data = [];
        $user = auth()->guard('organization')->user();

        $data['specialities'] = Speciality::select('full_name')->get();
        $data['professions'] = Profession::select('full_name')->get();
        // send the states
        $distinctFilters = Keyword::distinct()->pluck('filter');
        $allKeywords = [];
        foreach ($distinctFilters as $filter) {
            $keywords = Keyword::where('filter', $filter)->get();
            $allKeywords[$filter] = $keywords;
        }
        $data['states'] = State::select('id', 'name')->get();
        $data['allKeywords'] = $allKeywords;
        $data['type'] = $type;

        return view('organization::organization/organization_profile', $data);
    }

    public function communication()
    {
        // $user = User::select('first_name', 'last_name', 'image')->find($id);
        // return view('organization::organization/communication', compact('user'));

        return view('organization::organization/communication');
    }

    public function helpAndSupport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'issue' => 'required',
        ]);
        $responseData = [];
        if ($validator->fails()) {
            $responseData = [
                'status' => 'error',
                'message' => 'Somthing went wrong',
            ];
        } else {
            $id = Auth::guard('organization')->user()->id;
            $user_info = USER::where('id', $id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $insert = [
                    'user_id' => $id,
                    'subject' => $request->subject,
                    'issue' => $request->issue,
                ];

                $data = DB::table('help_support')->insert($insert);
                if ($data) {
                    $responseData = [
                        'status' => 'success',
                        'message' => 'Help center added successfully',
                    ];
                } else {
                    $responseData = [
                        'status' => 'error',
                        'message' => 'Somthing went wrong',
                    ];
                }
            } else {
                $responseData = [
                    'status' => 'error',
                    'message' => 'Somthing went wrong',
                ];
            }
        }
        return response()->json($responseData);
    }
    public function updateProfile(Request $request)
    {
        $aboutme = $request->input('about-me');
        $newqualities = $request->input('qualities');
        $id = Auth::guard('organization')->user()->id;
        $oldqualities = Auth::guard('organization')->user()->qualities;
        if (isset($oldqualities)) {
            $newqualities = array_merge($newqualities, json_decode($oldqualities));
        }
        $insert = [
            'about_me' => $aboutme,
            'qualities' => $newqualities,
        ];
        $updated = User::where('id', $id)->update($insert);
        if ($updated) {
            $responseData = [
                'status' => 'success',
                'message' => 'Profile update successfully',
            ];
        } else {
            $responseData = [
                'status' => 'error',
                'message' => 'Somthing went wrong',
            ];
        }
        return response()->json($responseData);
    }
    public function organizationRemoveQualities(Request $request)
    {
        $quality = $request->input('quality');
        $id = Auth::guard('organization')->user()->id;
        $oldqualities = Auth::guard('organization')->user()->qualities;
        if (isset($oldqualities)) {
            $myArray = array_filter(json_decode($oldqualities), function ($element) use ($quality) {
                return $element !== $quality;
            });
            $insert = [
                'qualities' => json_encode($myArray),
            ];
            $updated = User::where('id', $id)->update($insert);
            if ($updated) {
                $responseData = [
                    'status' => 'success',
                    'message' => 'Quality remove successfully',
                ];
            } else {
                $responseData = [
                    'status' => 'error',
                    'message' => 'Somthing went wrong',
                ];
            }
        }
        return response()->json($responseData);
    }
    public function askorganizationNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'worker_id' => 'required',
            'update_key' => 'required',
        ]);

        if ($validator->fails()) {
            $responseData = [
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ];
        } else {
            $nurse = Nurse::where('id', $request->worker_id)->first();
            $user = User::where('id', $nurse['user_id'])->first();
            $check = DB::table('ask_worker')
                ->where(['text_field' => $request->update_key, 'worker_id' => $request->worker_id])
                ->first();
            if (empty($check)) {
                $record = DB::table('ask_worker')->insert(['text_field' => $request->update_key, 'worker_id' => $request->worker_id]);
                $notification = Notification::create(['created_by' => $user['id'], 'title' => $request->update_key, 'job_id' => $request->job_id, 'isAskWorker' => '1', 'text' => 'Please update ' . $request->update_key]);
                if ($record) {
                    $responseData = [
                        'status' => 'success',
                        'message' => 'Message send successfully',
                    ];
                } else {
                    $responseData = [
                        'status' => 'error',
                        'message' => 'Somthing went wrong',
                    ];
                }
            } else {
                $responseData = [
                    'status' => 'success',
                    'message' => 'Message send successfully',
                ];
            }
        }
        return response()->json($responseData);
    }
    public function getSingleNurseDetails(Request $request, $id)
    {
        $nurse = Nurse::where('id', $id)->first();
        $data = User::select('first_name', 'last_name', 'image', 'mobile')
            ->where('id', $nurse['user_id'])
            ->first();
        $data->profession = $nurse->highest_nursing_degree;
        $responseData = [
            'data' => $data,
        ];
        return response()->json($responseData);
    }
    public function disactivate_account(Request $request)
    {
        try {
            $user = Auth::guard('organization')->user();
            $data['active'] = false;
            $user->update($data);
            $guard = 'organization';
            Auth::guard('organization')->logout();
            $request->session()->invalidate();
            return response()->json(['status' => true, 'message' => 'You are successfully disactivate your account.']);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => $e->errors()]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update_organization_profile(Request $request)
    {

        try {
            $user = Auth::guard('organization')->user();

            $oldOrganizationName = $user->organization_name;

            // check if the organization name already exist
            $organization = User::where('organization_name', $request->organization_name)->first();
            if ($organization && $organization->id != $user->id) {
                return response()->json(['status' => false, 'message' => 'Organization name already exist.']);
            }


            $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                // 'mobile' => 'nullable|string',
                // 'about_me' => 'required|string',
            ]);
            $user_data = [];

            if ($request->hasFile('profile_pic')) {
                $file = $request->file('profile_pic');
                $filename = time() . $user->id . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                $user_data['image'] = $filename;
            }

            isset($request->organization_name) ? ($user_data['organization_name'] = $request->organization_name) : '';
            isset($request->first_name) ? ($user_data['first_name'] = $request->first_name) : '';
            isset($request->last_name) ? ($user_data['last_name'] = $request->last_name) : '';
            isset($request->mobile) ? ($user_data['mobile'] = $request->mobile) : '';
            isset($request->about_me) ? ($user_data['about_me'] = $request->about_me) : '';

            $user->update($user_data);

            // If the organization name changes then update recruiter org name
            if (isset($user_data['organization_name']) && $user_data['organization_name'] !== $oldOrganizationName) {
                // all recruiters registered with this org
                $recruiters = User::where('role', 'RECRUITER')
                    ->where('organization_name', $oldOrganizationName)
                    ->get();

                foreach ($recruiters as $recruiter) {
                    $recruiter->update(['organization_name' => $user_data['organization_name']]);
                }
            }


            $user = $user->fresh();

            return response()->json(['msg' => $request->all(), 'user' => $user, 'status' => true]);
        } catch (\Exception $e) {
            //return response()->json(['msg'=>$e->getMessage(), 'status'=>false]);
            return response()->json(['msg' => $request->all(), 'status' => false]);
            // return response()->json(['msg'=>'"Something was wrong please try later !"', 'status'=>false]);
        }
    }

    public function update_organization_account_setting(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_name' => 'regex:/^[a-zA-Z\s]+$/|max:255',
                //'new_mobile' => ['nullable','regex:/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/'],
                '2fa' => 'in:0,1',
                //needs net` access
                'email' => 'email:rfc,dns',
            ]);

            $user = Auth::guard('organization')->user();

            isset($request->user_name) ? ($user_data['user_name'] = $request->user_name) : '';
            isset($request->new_mobile) ? ($user_data['new_mobile'] = $request->new_mobile) : '';
            isset($request->email) ? ($user_data['email'] = $request->email) : '';
            isset($request->password) ? ($user_data['password'] = Hash::make($request->password)) : '';
            isset($request->twoFa) ? ($user_data['2fa'] = $request->twoFa) : '';

            $user->update($user_data);
            //$UpdatedUser = $user->refresh();

            return response()->json(['status' => true, 'message' => 'Account settings updated successfully']);
        } catch (ValidationException $e) {
            // return response()->json(['message' => $e->getMessage()], 400);
            return response()->json(['status' => false, 'message' => 'An error occurred please check your infromation !']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'An error occurred while updating the account settings']);
        }
    }

    public function verify_new_email(Request $request)
    {
        try {

            $user = Auth::guard('organization')->user();
            dd($request->all(), $user);
            return $request->all();

            $user->email_verified_at = null;
            $user->save();

            // sending mail infromation
            $email_data = ['name' => $model->first_name . ' ' . $model->last_name, 'organization' => $orgId->organization_name, 'subject' => 'Registration'];
            Mail::to($model->email)->send(new VerifyNewMail($email_data));

            return response()->json(['status' => true, 'message' => 'Email verification link sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'An error occurred while sending the email verification link']);
        }
    }
    public function verifyEmail(Request $request)
{
    $validator = Validator::make($request->all(), [
        'verification_code' => 'required|digits:4',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first(),
        ]);
    }

    $user = Auth::guard('organization')->user();

    if (!$user) {
        return response()->json(['status' => 'error', 'message' => 'User not authenticated']);
    }

    if ($user->verification_code === $request->verification_code) {
        $user->update(['isVerified' => true, 'verification_code' => null]);

        return response()->json(['status' => 'success', 'message' => 'Email verified successfully']);
    }

    return response()->json(['status' => 'error', 'message' => 'Invalid verification code']);
}

    public function sendVerificationCode(Request $request)
    {
        try {
            $user = Auth::guard('organization')->user();

            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not authenticated']);
            }

            $verificationCode = rand(1000, 9999); // Generate 4-digit code
            $user->update(['verification_code' => $verificationCode]);

            // Send the code via email
            Mail::to($user->email)->send(new VerifyNewMail($verificationCode));

<<<<<<< HEAD
    //             return response()->json(['status' => 'success', 'message' => 'Verification code sent to your email']);
    //         } catch (\Exception $e) {
    //             return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    //         }
        }
=======
            return response()->json(['status' => 'success', 'message' => 'Verification code sent to your email']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
>>>>>>> parent of da617c55 (add new mail ( without otp veriy ))



    public function send_support_ticket(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'support_subject_issue' => 'required|max:500',
                'support_subject' => 'required',
            ]);

            $user = Auth::guard('organization')->user();
            $user_email = $user->email;
            $email_data = ['support_subject_issue' => $request->support_subject_issue, 'support_subject' => $request->support_subject, 'worker_email' => $user_email];
            Mail::to('support@goodwork.com')->send(new support($email_data));

            return response()->json(['status' => true, 'message' => 'Support ticket sent successfully']);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => $e->errors()]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function send_amount(Request $request)
    {

        try {
            $user = Auth::guard('organization')->user();
            $user_email = $user->email;

            // Define the data for the request
            $data = [
                'email' => $user_email,
            ];

            // Define the URL<
            $url = 'http://localhost:' . config('app.file_api_port') . '/payments/customer/create';

            // return response()->json(['data'=>$data , 'url' => $url]);

            // Make the request
            $response = Http::post($url, $data);

            $portal_link = $response->json()['message'];
            if (isset($response->json()['code'])) {
                if ($response->json()['code'] == 101) {
                    return response()->json(['status' => false, 'message' => 'Client Exist !']);
                }
            }

            return response()->json(['status' => true, 'message' => 'working on it !', 'portal_link' => $portal_link]);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => $e->errors()]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update_organization_profile_image(Request $request)
    {
        try {
            $user = Auth::guard('organization')->user();


            if ($request->hasFile('profile_pic')) {
                $file = $request->file('profile_pic');
                $filename = time() . $user->id . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                $user->image = $filename;
                $user->save();
            }

            return response()->json(['status' => true, 'message' => 'Profile image updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => $e->errors()]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
