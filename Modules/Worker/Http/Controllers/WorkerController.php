<?php

namespace Modules\Worker\Http\Controllers;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\{ Hash, Auth, Session, Cache };
use App\Enums\Role;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use App\Events\NewPrivateMessage;


use DB;
use Exception;

use App\Models\{User, Worker,Follows, NurseReference,Job,Offer, NurseAsset,
    Keyword, Facility, Availability, Countries, States, Cities, JobSaved};


class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('worker::layouts.main');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('worker::create');
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
        return view('worker::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('worker::edit');
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

    public function get_profile()
    {
        return view('worker::dashboard/my-profile');
    }

    public function sendMessages(Request $request)
    {

        $message = $request->message_data;
        $user = Auth::guard('frontend')->user();
        $id = $user->id;
        $role = $user->role;
        $idEmployer = $request->idEmployer;
       
        $time = now()->toDateTimeString();
        event(new NewPrivateMessage($message , $id,  $idEmployer,$role,$time));
        
    return true;
    }

    public function getMessages()
    {

        $user = Auth::guard('frontend')->user();
        $id = $user->id;
        return view('employer::layouts.test',compact('id'));
    }

    public function get_rooms(Request $request){
        $idEmployer = Auth::guard('frontend')->user()->id;
    
        $rooms = DB::connection('mongodb')->collection('chat')
        ->raw(function($collection) use ($idEmployer) {
            return $collection->aggregate([
                [
                    '$match' => [
                        
                        'employerId' => $idEmployer,
                        
                    ]
                ],
                [
                    '$project' => [
                        'employerId' => 1,
                        'workerId' => 1,
                        'lastMessage' => 1,
                        'isActive' => 1,
                        'messages' => [
                            '$slice' => [
                                '$messages',
                                1
                            ]
                        ]
                    ]
                ]
            ])->toArray();
        });
    
       
        $users = [];
        $data = [];
        foreach($rooms as $room){
        $user = User::where('id', $room->workerId)->where('role','NURSE')->select("first_name",
        "last_name")->get();
    
        $data_User['fullName'] = $user[0]->fullName;
        $data_User['lastMessage'] = $this->timeAgo($room->lastMessage);
        $data_User['workerId'] = $room->workerId;
        $data_User['isActive'] = $room->isActive;
        $data_User['messages'] = $room->messages;
    
        array_push($data,$data_User);
        }
    
        
         return response()->json($data);
    }
    

    public function get_messages(){
        
        $id = Auth::guard('frontend')->user()->id;

    $rooms = DB::connection('mongodb')->collection('chat')
    ->raw(function($collection) use ($id) {
        return $collection->aggregate([
            [
                '$match' => [
                    
                    'workerId' => $id,
                    
                ]
            ],
            [
                '$project' => [
                    'employerId' => 1,
                    'workerId' => 1,
                    'lastMessage' => 1,
                    'isActive' => 1,
                    'messages' => [
                        '$slice' => [
                            '$messages',
                            1
                        ]
                    ]
                ]
            ]
        ])->toArray();
    });

    $data = [];
    foreach($rooms as $room){
    $user = User::where('id', $room->workerId)->where('role','EMPLOYER')->select("first_name",
    "last_name")->get();

    $data_User['fullName'] = $user[0]->fullName;
    $data_User['lastMessage'] = $this->timeAgo($room->lastMessage);
    $data_User['employerId'] = $room->employerId;
    $data_User['isActive'] = $room->isActive;
    $data_User['messages'] = $room->messages;

    array_push($data,$data_User);
    }

        return  view('employer::employer/messages',compact('id','data'));
    }

    public function timeAgo($time = NULL)
    {
         // If $time is not a timestamp, try to convert it
    if (!is_numeric($time)) {
        $time = strtotime($time);
    }

        // Calculate difference between current
        // time and given timestamp in seconds
        $diff     = time() - $time;
        // Time difference in seconds
        $sec     = $diff;
        // Convert time difference in minutes
        $min     = round($diff / 60);
        // Convert time difference in hours
        $hrs     = round($diff / 3600);
        // Convert time difference in days
        $days     = round($diff / 86400);
        // Convert time difference in weeks
        $weeks     = round($diff / 604800);
        // Convert time difference in months
        $mnths     = round($diff / 2600640);
        // Convert time difference in years
        $yrs     = round($diff / 31207680);
        // Check for seconds
        if ($sec <= 60) {
            $string = "$sec seconds ago";
        }
        // Check for minutes
        else if ($min <= 60) {
            if ($min == 1) {
                $string = "one minute ago";
            } else {
                $string = "$min minutes ago";
            }
        }
        // Check for hours
        else if ($hrs <= 24) {
            if ($hrs == 1) {
                $string = "an hour ago";
            } else {
                $string = "$hrs hours ago";
            }
        }
        // Check for days
        else if ($days <= 7) {
            if ($days == 1) {
                $string = "Yesterday";
            } else {
                $string = "$days days ago";
            }
        }
        // Check for weeks
        else if ($weeks <= 4.3) {
            if ($weeks == 1) {
                $string = "a week ago";
            } else {
                $string = "$weeks weeks ago";
            }
        }
        // Check for months
        else if ($mnths <= 12) {
            if ($mnths == 1) {
                $string = "a month ago";
            } else {
                $string = "$mnths months ago";
            }
        }
        // Check for years
        else {
            if ($yrs == 1) {
                $string = "one year ago";
            } else {
                $string = "$yrs years ago";
            }
        }
        return $string;
    }

    public function get_get_my_work_journey()
    {
        $user = auth()->guard('frontend')->user();
        $whereCond = [
            'jobs.is_open' => "1",
            'jobs.is_closed' => "0",
            // 'job_saved.is_delete'=>'0',
            // 'job_saved.nurse_id'=>$user->id,
        ];

        $data = [];

        switch(request()->route()->getName())
        {
            case 'my-work-journey':
                $whereCond['jobs.is_hidden'] = '0';
                $jobs = Job::select("jobs.*")
                ->join('job_saved', function ($join) use ($user){
                    $join->on('job_saved.job_id', '=', 'jobs.id')
                    ->where(function ($query) use ($user) {
                        $query->where('job_saved.is_delete', '=', '0')
                        ->where('job_saved.is_save', '=', '1')
                        ->where('job_saved.nurse_id', '=', $user->id);
                    });
                })
                ->where($whereCond)
                ->orderBy('job_saved.created_at', 'DESC')
                ->get();
                $data['type'] = 'saved';
                break;

            case 'applied-jobs':
                $jobs = Job::select("jobs.*")
                ->join('offers', function ($join) use ($user){
                    $join->on('offers.job_id', '=', 'jobs.id')
                    ->where(function ($query) use ($user) {
                        $query->whereIn('offers.status', ['Apply', 'Screening', 'Submitted'])
                        ->where('offers.active', '=', '1')
                        ->where('offers.nurse_id', '=', $user->nurse->id)
                        ->where(function($q){
                            $q->whereNull('offers.expiration')
                            ->orWhere('offers.expiration', '>', date('Y-m-d'));
                        });
                    });
                })
                ->where($whereCond)
                ->orderBy('offers.created_at', 'DESC')
                ->get();
                $data['type'] = 'applied';
                break;

            case 'hired-jobs':
                unset($whereCond['jobs.is_closed']);
                $jobs = Job::select("jobs.*")
                ->join('offers', function ($join) use ($user){
                    $join->on('offers.job_id', '=', 'jobs.id')
                    ->where(function ($query) use ($user) {
                        $query->whereIn('offers.status', ['Onboarding', 'Working'])
                        ->where('offers.active', '=', '1')
                        ->where('offers.nurse_id', '=', $user->nurse->id);

                    });
                })
                ->where($whereCond)
                ->orderBy('offers.created_at', 'DESC')
                ->get();
                $data['type'] = 'hired';
                break;

            case 'offered-jobs':
                $jobs = Job::select("jobs.*")
                ->join('offers', function ($join) use ($user){
                    $join->on('offers.job_id', '=', 'jobs.id')
                    ->where(function ($query) use ($user) {
                        $query->whereIn('offers.status', ['Offered', 'Rejected', 'Hold'])
                        ->where('offers.active', '=', '1')
                        ->where('offers.nurse_id', '=', $user->nurse->id)
                        ->where(function($q){
                            $q->whereNull('offers.expiration')
                            ->orWhere('offers.expiration', '>', date('Y-m-d'));
                        });
                    });
                })
                ->where($whereCond)
                ->orderBy('offers.created_at', 'DESC')
                ->get();
                $data['type'] = 'offered';
                break;

            case 'past-jobs':
                unset($whereCond['jobs.is_closed']);
                $jobs = Job::select("jobs.*")
                ->join('offers', function ($join) use ($user){
                    $join->on('offers.job_id', '=', 'jobs.id')
                    ->where(function ($query) use ($user) {
                        $query->where('offers.status', '=', 'Done')
                        ->where('offers.active', '=', '1')
                        ->where('offers.nurse_id', '=', $user->nurse->id)
                        ->where(function($q){
                            $q->whereNull('offers.expiration')
                            ->orWhere('offers.expiration', '>', date('Y-m-d'));
                        });
                    });
                })
                ->where($whereCond)
                ->orderBy('offers.created_at', 'DESC')
                ->get();
                $data['type'] = 'past';
                break;
            default:
                return redirect()->back();
                break;
        }

        $data['jobs'] = $jobs;
        return view('jobs.jobs', $data);
    }

    public function explore(Request $request)
    {
        try {
           // commenting this for now we need to return only jobs data

        $data = [];
        $data['user'] = auth()->guard('frontend')->user();
        $data['jobSaved'] = new JobSaved();
        $data['professions'] = Keyword::where(['filter'=>'Profession','active'=>'1'])->get();
        $data['terms'] = Keyword::where(['filter'=>'jobType','active'=>'1'])->get();
        $data['prefered_shifts'] = Keyword::where(['filter'=>'PreferredShift','active'=>'1'])->get();
        $data['usa'] = $usa =  Countries::where(['iso3'=>'USA'])->first();
        $data['us_states'] = States::where('country_id', $usa->id)->get();
        // $data['us_cities'] = Cities::where('country_id', $usa->id)->get();

        $data['profession'] = isset($request->profession) ? $request->profession : '';
        $data['speciality'] = isset($request->speciality) ? $request->speciality : '';
        $data['experience'] = isset($request->experience) ? $request->experience : '';
        $data['city'] = isset($request->city) ? $request->city : '';
        $data['state'] = isset($request->state) ? $request->state : '';
        $data['job_type'] = isset($request->job_type) ? explode('-', $request->job_type) : [];
        $data['start_date'] = isset($request->start_date) ? $request->start_date : '';
        $data['end_date'] = isset($request->end_date) ? $request->end_date : '';
        $data['shifts'] = isset($request->shifts) ? explode('-',$request->shifts) : [];
        $data['auto_offers'] = isset($request->auto_offers) ? $request->auto_offers : 0;

        $data['weekly_pay_from'] = isset($request->weekly_pay_from) ? $request->weekly_pay_from : 10;
        $data['weekly_pay_to'] = isset($request->weekly_pay_to) ? $request->weekly_pay_to : 10000;
        $data['hourly_pay_from'] = isset($request->hourly_pay_from) ? $request->hourly_pay_from : 2;
        $data['hourly_pay_to'] = isset($request->hourly_pay_to) ? $request->hourly_pay_to : 24;
        $data['hours_per_week_from'] = isset($request->hours_per_week_from) ? $request->hours_per_week_from : 10;
        $data['hours_per_week_to'] = isset($request->hours_per_week_to) ? $request->hours_per_week_to : 100;
        $data['assignment_from'] = isset($request->assignment_from) ? $request->assignment_from : 10;
        $data['assignment_to'] = isset($request->assignment_to) ? $request->assignment_to : 150;

        $user = auth()->guard('frontend')->user();

        $worker = WORKER::where('user_id', $user->id)->first();
        $jobs_id = Offer::where('nurse_id', $worker->id)->select('job_id')->get();

        // $checkoffer = DB::table('blocked_users')->where('worker_id', $nurse['id'])->first();

        $whereCond = [
            'facilities.active' => true,
            'jobs.is_open' => "1",
            'jobs.is_hidden' => "0",
            'jobs.is_closed' => "0",
            // 'job_saved.is_delete'=>'0',
            // 'job_saved.nurse_id'=>$user->id,
        ];

        $ret = Job::select('jobs.id as job_id', 'jobs.auto_offers as auto_offers', 'jobs.*')
            ->leftJoin('facilities', function ($join) {
                $join->on('facilities.id', '=', 'jobs.facility_id');
            })
            ->leftJoin('job_saved', function ($join) use ($user){
                $join->on('job_saved.job_id', '=', 'jobs.id')
                ->where(function ($query) use ($user) {
                    $query->where('job_saved.is_delete', '=', 0)
                    ->where('job_saved.nurse_id', '=', $user->id);
                });
            })
            ->where($whereCond)
            ->whereNotIN('jobs.id', $jobs_id)
            ->orderBy('jobs.created_at', 'desc');

        if ($data['profession']) {
            $ret->where('jobs.profession', '=', $data['profession']);
        }

        if ($data['speciality']) {
            $ret->where('jobs.preferred_specialty', '=', $data['speciality']);
        }

        if ($data['experience']) {
            $ret->where('jobs.preferred_experience', '=', $data['experience']);
        }
        if($data['city'])
        {
            $ret->where('jobs.job_city', '=', $data['city']);
        }

        if($data['state'])
        {
            $ret->where('jobs.job_state', '=', $data['state']);
        }

        if(count($data['job_type'])){
            $ret->whereIn('jobs.job_type', $data['job_type']);
        }

        if ($data['start_date']) {
            $ret->where('jobs.start_date', '<=', $data['start_date']);
            $ret->where('jobs.end_date', '>=', $data['start_date']);
        }

        if ($data['end_date']) {
            $ret->where('jobs.start_date', '<=', $data['end_date']);
            $ret->where('jobs.end_date', '>=', $data['end_date']);
        }

        if ($data['shifts']) {
            $ret->whereIn('jobs.preferred_shift', $data['shifts']);
        }

        if ($data['auto_offers']) {
            $ret->where('jobs.auto_offers', '=', $data['auto_offers']);
        }

        if ($data['weekly_pay_from']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('weekly_pay', '>=', $data['weekly_pay_from']);
            });
        }

        if ($data['weekly_pay_to']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('weekly_pay', '<=', $data['weekly_pay_to']);
            });
        }

        if ($data['hourly_pay_from']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('hours_shift', '>=', $data['hourly_pay_from']);
            });
        }

        if ($data['hourly_pay_to']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('hours_shift', '<=', $data['hourly_pay_to']);
            });
        }

        if ($data['hours_per_week_from']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('hours_per_week', '>=', $data['hours_per_week_from']);
            });
        }

        if ($data['hours_per_week_to']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('hours_per_week', '<=', $data['hours_per_week_to']);
            });
        }

        if ($data['assignment_from']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('preferred_assignment_duration', '>=', $data['assignment_from']);
            });
        }


        if ($data['assignment_to']) {
            $ret->where(function (Builder $query) use ($data) {
                $query->where('preferred_assignment_duration', '<=', $data['assignment_to']);
            });
        }

        $result = $ret->get();




            $resl = Job::select('jobs.*','name')
            ->leftJoin('facilities', function ($join) {
                $join->on('facilities.id', '=', 'jobs.facility_id');
            });
            $data['jobs'] = $resl->get();


            // $data['jobSaved'] = [""];
            // $data['prefered_shifts '] = [""];
            // $data['terms'] = [""];
            // $data['us_states'] = [""];
            // $data['speciality'] = [""];
            // $data['professions'] = ['title'=>'','id'=>''];
            // $data['profession'] = "";

        //$data['jobs'] = $result;


        return view('jobs.explore', $data);
        //return response()->json(['message' =>  $data['jobs']]);
    } catch (\Exception $e) {
        // Handle other exceptions


        // Display a generic error message or redirect with an error status
         return redirect()->route('jobs.explore')->with('error', 'An unexpected error occurred. Please try again later.');
        //return response()->json(['success' => false, 'message' =>  $e->getMessage()]);
    }
    }

    public function my_profile() {
        $data = [];
        $id = Auth::guard('frontend')->user()->id;
        $data['model'] = User::findOrFail($id);
        if ($data['model']->type_id === '2') {
            $data['managers'] = User::select('id', 'first_name', 'last_name')->where(['type_id' => '3', 'status' => '1'])->get();
        }
        if (!Cache::has('statetbl')) {
            $states = States::select('id', 'name', 'abbrev', 'is_restrict')->where('is_restrict', '=', '0')->get();
            Cache::put('statetbl', $states);
        }
        $data['states'] = Cache::get('statetbl');
        return view('user.edit-profile', $data);
    }
}