<?php

namespace Modules\Recruiter\Http\Controllers;

use DateTime;
use App\Models\Nurse;
use App\Models\User;
use App\Models\Offer;
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

 define('USER_IMG_RECRUITER', asset('public/frontend/img/profile-pic-big.png'));

class RecruiterDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $id = Auth::guard('recruiter')->user()->id;
        $alljobs = Job::where('recruiter_id', $id)->get();
        $statusCounts = [];
        $offerLists = [];
        foreach ($alljobs as $key => $value) {
            if(isset($value->id)){
                $statusList = ['Apply', 'Offered', 'Onboarding', 'Working', 'Done'];
                foreach ($statusList as $status) {
                    $statusCounts[$status] = 0;
                }
                $statusCountsQuery = Offer::whereIn('status', $statusList)
                    ->select(\DB::raw('status, count(*) as count'))
                    ->where('job_id', $value->id)
                    ->groupBy('status')
                    ->get();

                foreach ($statusCountsQuery as $statusCount) {
                    $statusCounts[$statusCount->status] = $statusCount->count;
                }
            }
        }
        $statusCounts = array_values($statusCounts);
        $statusCounts = implode(', ', $statusCounts);
        return view('recruiter::dashboard', compact('statusCounts'));
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

    public function profile()
    {
        $id = Auth::guard('recruiter')->user()->id;
        // facility_id qualities about_me are been added in user table
        $user = User::select('first_name', 'last_name', 'image', 'user_name', 'email', 'date_of_birth', 'mobile', 'about_me', 'qualities', 'facility_id')->find($id);
        if($user && isset($user->facility_id)){
            $facilityIds = json_decode($user->facility_id);
            if (!empty($facilityIds)) {
                $facilities = Facility::select('name')->whereIn('id', $facilityIds)->first();
                $user->facilities = $facilities->name;
            }
        }
        // help_support table and keywordsdosn't exist yet
        $helpsupportdata =[];
        $helpsupportcomments = [];

        // $helpsupportdata = DB::table('keywords')->where('filter', 'subjectType')->get();
        // $helpsupportcomments = DB::table('help_support')->get();
        // foreach ($helpsupportcomments as $key => $value) {
        //     $commentuser = User::select('first_name','last_name')->find($id);
        //     $value->first_name = $commentuser->first_name;
        //     $value->last_name = $commentuser->last_name;
        // }
        return view('recruiter::recruiter/profile', compact('user', 'helpsupportdata', 'helpsupportcomments'));
    }

    public function communication()
    {
        // $user = User::select('first_name', 'last_name', 'image')->find($id);
        // return view('recruiter::recruiter/communication', compact('user'));

        return view('recruiter::recruiter/communication');
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
            $id = Auth::guard('recruiter')->user()->id;
            $user_info = USER::where('id', $id);
            if ($user_info->count() > 0)
            {
                $user = $user_info->get()->first();
                $insert = array(
                    "user_id" => $id,
                    'subject' => $request->subject,
                    'issue' => $request->issue,
                );

                $data = DB::table('help_support')->insert($insert);
                if($data){
                    $responseData = [
                        'status' => 'success',
                        'message' => 'Help center added successfully',
                    ];
                }else{
                    $responseData = [
                        'status' => 'error',
                        'message' => 'Somthing went wrong',
                    ];
                }
            }else{
                $responseData = [
                    'status' => 'error',
                    'message' => 'Somthing went wrong',
                ];
            }
        }
        return response()->json($responseData);
    }
    public function updateProfile(Request $request){
        $aboutme = $request->input('about-me');
        $newqualities = $request->input('qualities');
        $id = Auth::guard('recruiter')->user()->id;
        $oldqualities = Auth::guard('recruiter')->user()->qualities;
        if(isset($oldqualities)){
            $newqualities = array_merge($newqualities, json_decode($oldqualities));
        }
        $insert = array(
            "about_me" => $aboutme,
            'qualities' => $newqualities,
        );
        $updated = User::where('id', $id)->update($insert);
        if($updated){
            $responseData = [
                'status' => 'success',
                'message' => 'Profile update successfully',
            ];
        }else{
            $responseData = [
                'status' => 'error',
                'message' => 'Somthing went wrong',
            ];
        }
        return response()->json($responseData);
    }
    public function recruiterRemoveQualities(Request $request){
        $quality = $request->input('quality');
        $id = Auth::guard('recruiter')->user()->id;
        $oldqualities = Auth::guard('recruiter')->user()->qualities;
        if(isset($oldqualities)){
            $myArray = array_filter(json_decode($oldqualities), function ($element) use ($quality) {
                return $element !== $quality;
            });
            $insert = array(
                'qualities' => json_encode($myArray),
            );
            $updated = User::where('id', $id)->update($insert);
            if($updated){
                $responseData = [
                    'status' => 'success',
                    'message' => 'Quality remove successfully',
                ];
            }else{
                $responseData = [
                    'status' => 'error',
                    'message' => 'Somthing went wrong',
                ];
            }
        }
        return response()->json($responseData);
    }
    public function askRecruiterNotification(Request $request){
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
            $check = DB::table('ask_worker')->where(['text_field' => $request->update_key, 'worker_id' => $request->worker_id])->first();
            if(empty($check)){
                $record = DB::table('ask_worker')->insert(['text_field' => $request->update_key, 'worker_id' => $request->worker_id]);
                $notification = Notification::create(['created_by' => $user['id'], 'title' => $request->update_key, 'job_id' => $request->job_id, 'isAskWorker' => '1', 'text' => 'Please update '.$request->update_key]);
                if($record){
                    $responseData = [
                        'status' => 'success',
                        'message' => 'Message send successfully',
                    ];
                }else{
                    $responseData = [
                        'status' => 'error',
                        'message' => 'Somthing went wrong',
                    ];
                }
            }else{
                $responseData = [
                    'status' => 'success',
                    'message' => 'Message send successfully',
                ];
            }
        }
        return response()->json($responseData);
    }
    public function getSingleNurseDetails(Request $request, $id){
        $nurse = Nurse::where('id', $id)->first();
        $data = User::select('first_name', 'last_name', 'image', 'mobile')->where('id', $nurse['user_id'])->first();
        $data->profession = $nurse->highest_nursing_degree;
        $responseData = [
            'data' => $data,
        ];
        return response()->json($responseData);
    }
}
