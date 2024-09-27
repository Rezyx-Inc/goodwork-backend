<?php

namespace Modules\Worker\Http\Controllers;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\{ Hash, Auth, Session, Cache };
use App\Enums\Role;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use App\Events\NewPrivateMessage;
use App\Events\NotificationMessage;
use App\Events\NotificationJob;
use App\Events\NotificationOffer;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use DB;
use Exception;

use App\Models\{User, Worker,Follows, NurseReference,Job,Offer, NurseAsset,
    Keyword, Facility, Availability, Countries, States, Cities, JobSaved,Nurse,NotificationOfferModel};

use App\Models\NotificationMessage as NotificationMessageModel;

define('USER_IMG', asset('frontend/img/profile-pic-big.png'));

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

    public function details($id)
    {
        $data = [];
        $data['model'] = Job::findOrFail($id);
        $distinctFilters = Keyword::distinct()->pluck('filter');
        $allKeywords = [];
        foreach ($distinctFilters as $filter) {
            $keywords = Keyword::where('filter', $filter)->get();
            $allKeywords[$filter] = $keywords;
        }
        $data['allKeywords'] = $allKeywords;
        // $user = auth()->guard('frontend')->user();
        // dd($user->nurse->id);
        $data['jobSaved'] = new JobSaved();
        return view('worker::dashboard.details', $data);
    }

    public function sendMessages(Request $request)
    {

        $message = $request->message_data;
        $user = Auth::guard('frontend')->user();
        $id = $user->id;
        //$role = $user->role;
        $idEmployer = $request->idEmployer;
        $idRecruiter = $request->idRecruiter;
        $type = $request->type;
        $fileName = $request->fileName;

        $full_name = $user->first_name . ' ' . $user->last_name;


        $time = now()->toDateTimeString();
        event(new NewPrivateMessage($message , $idEmployer,$idRecruiter, $id, 'WORKER',$time,$type,$fileName));
        event(new NotificationMessage($message,false,$time,$idRecruiter,$id,$full_name));


        return [$id, $idEmployer];
    }

    public function get_private_messages(Request $request)
    {
        $idRecruiter = $request->query('recruiterId');
        $idEmployer = $request->query('employerId');
        $page = $request->query('page', 1);

        $idWorker = Auth::guard('frontend')->user()->id;

        // Calculate the number of messages to skip
        $skip = ($page - 1) * 10;


        $chat = DB::connection('mongodb')->collection('chat')
        ->raw(function($collection) use ($idEmployer, $idWorker, $idRecruiter, $skip) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'recruiterId'=> $idRecruiter,
                        'employerId' => $idEmployer,
                        'workerId' => $idWorker
                    ]
                ],
                [
                    '$project' => [
                        'messages' => [
                            '$slice' => [
                                '$messages',
                                $skip,
                                10
                            ]
                        ]
                    ]
                ]
            ])->toArray();
        });
        return $chat[0];

    }

    // Why is it still here ??????????????????
    public function get_rooms(Request $request){

        $idWorker = Auth::guard('frontend')->user()->id;

        $rooms = DB::connection('mongodb')->collection('chat')
        ->raw(function($collection) use ($idWorker) {
            return $collection->aggregate([
                [
                    '$match' => [

                        'workerId' => $idWorker,

                    ]
                ],
                [
                    '$project' => [
                        'employerId' => 1,
                        'workerId' => 1,
                        'recruiterId'=>1,
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
            //$user = User::where('id', $room->employerId)->select("first_name", "last_name")->get();
            $user = User::select('first_name', 'last_name')
                ->where('id', $room->employerId)
                ->get()
                ->first();
            if ($user) {
                $name = $user->last_name;
            } else {
                // Handle the case where no user is found
                $name = 'Default Name';
            }
            $data_User['fullName'] = $name;
            $data_User['lastMessage'] = $this->timeAgo($room->lastMessage);
            $data_User['employerId'] = $room->employerId;
            $data_User['recruiterId'] = $room->recruiterId;
            $data_User['isActive'] = $room->isActive;
            $data_User['messages'] = $room->messages;

            array_push($data, $data_User);

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
                        'recruiterId'=>1,
                        'lastMessage' => 1,
                        'isActive' => 1,
                        'messages' => [
                            '$slice' => [
                                '$messages',
                                1
                            ]
                        ],
                        'messagesLength'=> [
                            '$cond' =>
                            [
                                'if' =>
                                    [
                                        '$isArray' => '$messages'
                                    ],
                                'then' =>
                                    [
                                        '$size' => '$messages'
                                    ],
                                'else' => 'NA'
                            ]
                        ]

                    ]
                ]
            ])->toArray();
        });

        $data = [];
        foreach($rooms as $room){
            //$user = User::where('id', $room->employerId)->select("first_name","last_name")->get();
            $user = User::select('first_name', 'last_name')
                ->where('id', $room->recruiterId)
                ->get()
                ->first();

            if ($user) {
                $name = $user->fullName;
            } else {
                // Handle the case where no user is found
                $name = 'Default Name';
            }

            $data_User['fullName'] = $name;

            $data_User['lastMessage'] = $this->timeAgo($room->lastMessage);
            $data_User['employerId'] = $room->employerId;
            $data_User['isActive'] = $room->isActive;
            $data_User['messages'] = $room->messages;
            $data_User['messagesLength'] = $room->messagesLength;

            if(isset($room->recruiterId)) {
                $data_User['recruiterId'] = $room->recruiterId;
            }

            array_push($data,$data_User);
        }

        return  view('worker::worker/messages',compact('id','data'));
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

    public function get_my_work_journey()
    {
        $front_end_user = Auth::guard('frontend')->user();
        $user = Nurse::where('user_id', $front_end_user->id)->first();
        $whereCond = [
            'jobs.is_open' => "1",
            'jobs.is_closed' => "0",
            // 'job_saved.is_delete'=>'0',
            // 'job_saved.nurse_id'=>$user->id,
        ];

        $data = [];

        //return request()->route()->getName();

        switch(request()->route()->getName())
        {
            case 'worker.my-work-journey':
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
                        ->where('offers.worker_user_id', '=', $user->id);
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
                        ->where('offers.worker_user_id', '=', $user->id);
                    });
                })
                ->where($whereCond)
                ->orderBy('offers.created_at', 'DESC')
                ->get();
                $data['type'] = 'hired';
                break;

            case 'offered-jobs':

                $jobs = Job::select("jobs.*","offers.id as offer_id")
                ->join('offers', function ($join) use ($user){
                    $join->on('offers.job_id', '=', 'jobs.id')
                    ->where(function ($query) use ($user) {
                        $query->whereIn('offers.status', ['Offered', 'Hold'])
                        ->where('offers.worker_user_id', '=', $user->id)
                        ;
                    });
                })
                ->where($whereCond)
                ->orderBy('offers.created_at', 'DESC')
                ->get();
                // return response()->json(['msg'=>$jobs]);
                $data['type'] = 'offered';
                break;

            case 'past-jobs':
                unset($whereCond['jobs.is_closed']);
                $jobs = Job::select("jobs.*")
                ->join('offers', function ($join) use ($user){
                    $join->on('offers.job_id', '=', 'jobs.id')
                    ->where(function ($query) use ($user) {
                        $query->where('offers.status', '=', 'Done')
                        ->where('offers.worker_user_id', '=', $user->id);
                    });
                })
                ->where($whereCond)
                ->orderBy('offers.created_at', 'DESC')
                ->get();
                $data['type'] = 'past';
                break;
            case 'saved-jobs':
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
            default:
                return redirect()->back();
                break;
        }

        $data['jobs'] = $jobs;
        return view('worker::jobs.jobs', $data);
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

    public function fetch_job_content(Request $request)
    {
        try {

                $request->validate([
                    'jid' => 'required',
                    'type' => 'required',
                ]);
                $response = [];
                $response['success'] = true;
                $data = [];
                $job = Job::findOrFail($request->jid);
                $recruiter_id = $job->created_by;
                $recruiter = User::findOrFail($recruiter_id);
                $data['recruiter'] = $recruiter;

                $id = Auth::guard('frontend')->user()->id;
                $worker_id = Nurse::where('user_id', $id)->first();
                $offer_id = Offer::where('worker_user_id', $worker_id->id)
                    ->where('job_id', $job->id)
                    ->first();
                $data['worker_id'] = $worker_id->id;
                $data['model'] = $job;
                $data['offer_id'] = $offer_id->id;

                switch ($request->type) {
                    case 'saved':
                        $view = 'saved';
                        break;
                    case 'applied':
                        $view = 'applied';
                        break;
                    case 'hired':
                        $offerdetails = Offer::where('id', $offer_id->id)->first();
                        $jobdetails = Job::where('id', $job->id)->first();
                        $nursedetails = Nurse::where('id', $worker_id->id)->first();
                        $recruiter = User::where('id', $jobdetails->created_by)->first();
                        $data['offerdetails'] = $offerdetails;
                        $data['jobdetails'] = $jobdetails;
                        $data['nursedetails'] = $nursedetails;
                        $data['recruiter'] = $recruiter;

                        $response['content'] = view('worker::jobs.hired', $data)->render();
                        return new JsonResponse($response, 200);
                        $view = 'hired';
                        break;
                    case 'offered':
                        $offerdetails = Offer::where('id', $offer_id->id)->first();
                        $jobdetails = Job::where('id', $job->id)->first();
                        $nursedetails = Nurse::where('id', $worker_id->id)->first();
                        $recruiter = User::where('id', $jobdetails->created_by)->first();
                        $data['offerdetails'] = $offerdetails;
                        $data['jobdetails'] = $jobdetails;
                        $data['nursedetails'] = $nursedetails;
                        $data['recruiter'] = $recruiter;
                        $response['content'] = view('worker::jobs.counter_details', $data)->render();
                        return new JsonResponse($response, 200);
                        break;
                    case 'past':
                        $offerdetails = Offer::where('id', $offer_id->id)->first();
                        $jobdetails = Job::where('id', $job->id)->first();
                        $nursedetails = Nurse::where('id', $worker_id->id)->first();
                        $recruiter = User::where('id', $jobdetails->created_by)->first();
                        $data['offerdetails'] = $offerdetails;
                        $data['jobdetails'] = $jobdetails;
                        $data['nursedetails'] = $nursedetails;
                        $data['recruiter'] = $recruiter;
                        $response['content'] = view('worker::jobs.past', $data)->render();
                        return new JsonResponse($response, 200);
                        $view = 'past';
                        break;
                    case 'counter':
                        try {
                            $distinctFilters = Keyword::distinct()->pluck('filter');
                            $keywords = [];
                            foreach ($distinctFilters as $filter) {
                                $keyword = Keyword::where('filter', $filter)->get();
                                $keywords[$filter] = $keyword;
                            }
                            $data['keywords'] = $keywords;
                            $data['countries'] = Countries::where('flag', '1')->orderByRaw("CASE WHEN iso3 = 'USA' THEN 1 WHEN iso3 = 'CAN' THEN 2 ELSE 3 END")->orderBy('name', 'ASC')->get();
                            $data['usa'] = $usa = Countries::where(['iso3' => 'USA'])->first();
                            $data['us_states'] = States::where('country_id', $usa->id)->get();
                            $data['us_cities'] = Cities::where('country_id', $usa->id)->get();
                            $offerdetails = Offer::where('id', $offer_id->id)->first();
                            $data['model'] = $offerdetails;
                            $view = 'counter_offer';
                        } catch (\Exception $e) {
                            return response()->json(['success' => false, 'message' => 'here']);
                        }
                        break;
                    default:
                        return new JsonResponse(['success' => false, 'msg' => 'Oops! something went wrong.'], 400);
                        break;
                }

                $response['content'] = view('worker::jobs.' . $view . '_job', $data)->render();
                return new JsonResponse($response, 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    // check stripe account onboarding

    public function checkPaymentMethod($user_id) {
        $url = 'http://localhost:' . config('app.file_api_port') . '/payments/onboarding-status';
        $stripe_id = User::where('id', $user_id)->first()->stripeAccountId;
        $data = ['stripeId' => $stripe_id];
        $response = Http::get($url, $data);

        if ($stripe_id == null ||  $response->json()['status'] == false) {
            return false;
        }
        return true;
    }


    public function accept_offer(Request $request)
    {

        // check first stripe account onboarding
        $user = Auth::guard('frontend')->user();
        $user_id = $user->id;
        $nurse_id = $user->nurse->id;
        $full_name = $user->first_name . ' ' . $user->last_name;
        $offer_id = $request->offer_id;

        if (!$this->checkPaymentMethod($user_id)) {
            return response()->json(['success' => false, 'message' => 'Please complete your payment method onboarding first']);
        }

        try{
            $request->validate([
                'offer_id'=>'required',
            ]);

            $offer = Offer::where('id',$offer_id)->first();
            if(!$offer){
                return response()->json(['success' => false, 'message' => 'Offer not found']);
            }

            $job = Job::where('id',$offer->job_id)->first();
            if(!$job){
                return response()->json(['success' => false, 'message' => 'Job not found']);
            }


                $update_array['is_counter'] = '0';
                $update_array['is_draft'] = '0';
                $update_array['status'] = 'Hold';
                $is_offer_update = DB::table('offers')
                    ->where(['id' => $offer_id])
                    ->update($update_array);
                $user_id = $job->created_by;
                $user = User::where('id',$user_id)->first();

                $data = [
                    'offerId' => $offer_id,
                    'amount' => '1',
                    'stripeId' =>$user->stripeAccountId,
                    'fullName' => $user->first_name . ' ' . $user->last_name,
                ];

                //return response()->json(['message'=>$data]);

                $url = 'http://localhost:' . config('app.file_api_port') . '/payments/customer/invoice';

                // return response()->json(['data'=>$data , 'url' => $url]);

                // Make the request
                $responseInvoice = Http::post($url, $data);
                // return response()->json(['message'=>$responseInvoice->json()]);
                $responseData = [];
                if ($offer) {
                    $responseData = [
                        'status' => 'success',
                        'message' => $responseInvoice->json()['message'],
                    ];
                }


            // event offer notification

            $id = $offer_id;
            $jobid = $offer->job_id;

            $time = now()->toDateTimeString();
            $receiver = $offer->recruiter_id;
            $job_name = Job::where('id', $jobid)->first()->job_name;

            event(new NotificationOffer('Hold',false,$time,$receiver,$nurse_id,$full_name,$jobid,$job_name, $id));
            return response()->json(['msg'=>'offer accepted successfully','success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' =>  $e->getMessage()]);
           //return response()->json(['success' => false, 'message' =>  "Something was wrong please try later !"]);
        }
    }

    public function reject_offer(Request $request)
    {
        $user = Auth::guard('frontend')->user();

        $full_name = $user->first_name . ' ' . $user->last_name;
        $nurse_id = $user->nurse->id;
        try{
            $request->validate([
                'offer_id'=>'required',
            ]);

            $offer = Offer::where('id',$request->offer_id)->first();
            if(!$offer){
                return response()->json(['success' => false, 'message' => 'Offer not found']);
            }

            $job = Job::where('id',$offer->job_id)->first();
            if(!$job){
                return response()->json(['success' => false, 'message' => 'Job not found']);
            }

            $updated = $offer->update([
                'status' => 'Rejected',
                'is_draft' => '0',
                'is_counter' => '0'
            ]);

            if ($updated) {
                  // event offer notification
            $id = $offer->id;
            $jobid = $offer->job_id;

            $time = now()->toDateTimeString();
            $receiver = $offer->recruiter_id;
            $job_name = Job::where('id', $jobid)->first()->job_name;

            event(new NotificationOffer('Rejected',false,$time,$receiver,$nurse_id,$full_name,$jobid,$job_name, $id));
                return response()->json(['msg'=>'Offer rejected successfully','success' => true]);
            } else {
                return response()->json(['msg'=>'offer not rejected', 'success' => false]);
            }


        } catch (\Exception $e) {
           // return response()->json(['success' => false, 'message' =>  "$e->getMessage()"]);
           return response()->json(['success' => false, 'message' =>  "Something was wrong please try later !"]);
        }
    }


public function read_message_notification(Request $request)
{
    $sender = $request->sender;
    $receiver = Auth::guard('frontend')->user()->id;

    try {


        $updateResult = NotificationMessageModel::raw()->updateMany(
            [
                'receiver' => $receiver,
                'all_messages_notifs.sender' => $sender
            ],
            [
                '$set' => [
                    'all_messages_notifs.$[elem].notifs_of_one_sender.$[].seen' => true
                ]
            ],
            [
                'arrayFilters' => [
                    ['elem.sender' => $sender]
                ]
            ]
        );

                if ($updateResult->getModifiedCount() > 0) {
                    return response()->json(['success' => true, 'message' => 'Notifications marked as read successfully']);
                } else {
                    return response()->json(['success' => false, 'message' => 'No notifications to update']);
                }
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => "Something went wrong, please try again later!"]);
            }
}



public function read_offer_notification(Request $request)
{
    $sender = $request->senderId;
    $offerId = $request->offerId;
    $user = Auth::guard('frontend')->user();
    $receiver = $user->nurse->id;

    try {
        $updateResult = NotificationOfferModel::raw()->updateMany(
            [
                'receiver' => $receiver,
                'all_offers_notifs.sender' => $sender,
                'all_offers_notifs.notifs_of_one_sender.offer_id' => $offerId,
                'all_offers_notifs.notifs_of_one_sender.seen' => false
            ],
            [
                '$set' => [
                    'all_offers_notifs.$[].notifs_of_one_sender.$[notif].seen' => true
                ]
            ],
            [
                'arrayFilters' => [
                    ['notif.offer_id' => $offerId, 'notif.seen' => false]
                ],
                'multi' => true
            ]
        );

        if ($updateResult->getModifiedCount() > 0) {
            return response()->json(['success' => true, 'message' => 'Notifications marked as read successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'No notifications to update']);
        }
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => "Something went wrong, please try again later!"]);
    }
}

public function addDocuments(Request $request)
    {

        try{
        $body = $request->getContent();
        $bodyArray = json_decode($body, true);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBody($body, 'application/json')->post('http://localhost:4545/documents/add-docs');
        return $response;
        if ($response->successful()) {
            return response()->json([
                'ok' => true,
                'message' => 'Files uploaded successfully',
            ]);
        } else {
            return response()->json([
                'ok' => false,
                'message' => $response->body(),
            ], $response->status());
        }
        }catch(\Exception $e){
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

public function listDocs(Request $request)
{
    try{
    $workerId = $request->WorkerId;
    //return response()->json(['workerId' => $workerId]);

    $response = Http::get('http://localhost:4545/documents/list-docs', ['workerId' => $workerId]);
    if ($response->successful()) {
        return $response->body();
    } else {
        return response()->json(['success' => false], $response->status());
    }


    }catch(\Exception $e){
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

public function getDoc(Request $request){
    try {
        $bsonId = $request->input('bsonId');
        $response = Http::get('http://localhost:4545/documents/get-doc', ['bsonId' => $bsonId]);

        // Pass through the response from Node.js API
        return $response->body();
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}


public function deleteDoc(Request $request)
{
    $bsonId = $request->input('bsonId');

    $response = Http::post('http://localhost:4545/documents/del-doc', ['bsonId' => $bsonId]);
    if ($response->successful()) {
        return response()->json(['success' => true]);
    } else {
        return response()->json(['success' => false], $response->status());
    }
}


public function vaccination_submit(Request $request)
{
    $user = auth()->guard('frontend')->user();
    $id = $user->nurse->id;
    $destinationPath = 'images/nurses/vaccination';

    // Vaccination type
    $type = $request->type;
    $vacc_field = strtolower(str_replace(['.', '(', ')', ' '], '', $type));
    $vacc_file = $vacc_field;
    $vacc_flag = $vacc_field . '_vac';

    if ($request->$vacc_flag == 'Yes' && $request->hasFile($vacc_file)) {
        // Delete old vaccination record
        $old_vac = NurseAsset::where('nurse_id', $id)->where('name', $type)->first();
        if ($old_vac) {
            $oldFilePath = public_path($destinationPath.'/'.$old_vac->name);
            if (file_exists($oldFilePath)) {
                File::delete($oldFilePath);
            }
            $old_vac->forceDelete();
        }

        // Handle file upload
        $file = $request->file($vacc_file);
        $filename = $type.'_'.$id.'_vaccination.'.$file->getClientOriginalExtension();
        $file->move(public_path($destinationPath), $filename);

        NurseAsset::create([
            'nurse_id' => $id,
            'file_name' => $filename,
            'name' => $type,
            'filter' => 'vaccination'
        ]);

        $response = array('success'=>true, 'msg'=> $type . ' Updated Successfully!');
        return $response;
    } else {
        // Check and handle vaccination record removal if applicable
        $old_vac = NurseAsset::where('nurse_id', $id)->where('name', $type)->first();
        if ($old_vac) {
            $oldFilePath = public_path($destinationPath.'/'.$old_vac->name);
            if (file_exists($oldFilePath)) {
                File::delete($oldFilePath);
            }
            $old_vac->forceDelete();
            $response = array('success'=>true, 'msg'=>$type . ' Removed Successfully!');
            return $response;
        }
    }

    $response = array('success'=>false, 'msg'=>'No vaccination record selected!');
    return $response; // Return the response
}


public function certification_submit(Request $request)
{
    $user = auth()->guard('frontend')->user();
    $id = $user->nurse->id;
    $destinationPath = 'images/nurses/certificate';

    // certif type
    $type = $request->type;
        $cert_field = strtolower(str_replace(['.', '(', ')', ' '], '', $type));
        $cert_file = $cert_field;
        $cert_flag = $cert_field . '_cer';

        if ($request->$cert_flag == 'Yes' && $request->hasFile($cert_file)) {
            // Delete old certificate
            $old_cer = NurseAsset::where('nurse_id', $id)->where('name', $type)->first();
            if ($old_cer) {
                $oldFilePath = public_path($destinationPath.'/'.$old_cer->name);
                if (file_exists($oldFilePath)) {
                    File::delete($oldFilePath);
                }
                $old_cer->forceDelete();
            }

            // Handle file upload
            $file = $request->file($cert_file);
            $filename = $type.'_'.$id.'_certificate.'.$file->getClientOriginalExtension();
            $file->move(public_path($destinationPath), $filename);

            NurseAsset::create([
                'nurse_id' => $id,
                'file_name' => $filename,
                'name' => $type,
                'filter' => 'certificate'
            ]);

            //$responses[] = ['success' => true, 'msg' => $certificate . ' Updated Successfully!'];
            $response = array('success'=>true,'msg'=> $type . ' Updated Successfully!');
            return $response;
        } else {
            // Check and handle certificate removal if applicable
            $old_cer = NurseAsset::where('nurse_id', $id)->where('name', $type)->first();
            if ($old_cer) {
                $oldFilePath = public_path($destinationPath.'/'.$old_cer->name);
                if (file_exists($oldFilePath)) {
                    File::delete($oldFilePath);
                }
                $old_cer->forceDelete();
                $response = array('success'=>true,'msg'=>$type . ' Removed Successfully!');
                return $response;
            }
        }

    $response = array('success'=>false,'msg'=>'No certificate selected!');
    return $response; // Return all responses after processing all certificates
}


public function match_worker_job(Request $request)
{

        // dd($request->input());
        $user = auth()->guard('frontend')->user();

        $id = $user->id;
        $model = Nurse::where('user_id',$id)->first();
        $inputFields = collect($request->all())->filter(function ($value) {
            return $value !== null;
        });

        $inputFields->put('updated_at', Carbon::now());
        // dd($inputFields);
        $model->fill($inputFields->all());
        $model->save();
        return new JsonResponse(['success' => true, 'msg'=>'Updated successfully.'], 200);

}



}
