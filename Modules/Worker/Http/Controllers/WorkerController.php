<?php

namespace Modules\Worker\Http\Controllers;

use DateTime;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\{Hash, Auth, Session, Cache};
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
use Illuminate\Support\Facades\Validator;
use DB;
use Exception;

use App\Models\{
    User,
    Worker,
    Follows,
    NurseReference,
    Job,
    Offer,
    NurseAsset,
    Keyword,
    Facility,
    Availability,
    Countries,
    States,
    Cities,
    JobSaved,
    Nurse,
    NotificationOfferModel,
    OffersLogs,
    Speciality,
    Profession,
    State,
};

use App\Models\NotificationMessage as NotificationMessageModel;

if (!defined('USER_IMG')) {
    define('USER_IMG', asset('frontend/img/profile-pic-big.png'));
}

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
        try {

            $data = [];

            $worker = auth()->guard('frontend')->user();
            $data['worker'] = $worker;
            $data['nurse'] = $worker->nurse;
            // get listDocs for this worker from the listDocs function with worker id in the request

            $request = request();
            // append worker id to the request
            $request->merge(['WorkerId' => $worker->nurse->id]);

            $docsListResponse = $this->listDocs($request);

            $data['docsList'] = json_decode($docsListResponse);

            $data['model'] = Job::findOrFail($id);
            $recruiter_id = $data['model']->recruiter_id;
            $user = User::findOrFail($recruiter_id);
            

            $data['model']->organization_name = !!$user->organization_name && strlen($user->organization_name) ? $user->organization_name : 'Not Available';
            $data['requiredFieldsToApply'] = [];

            if (isset($recruiter_id)) {

                $requiredFields = Http::post('http://localhost:'. config('app.file_api_port') .'/organizations/checkRecruiter', [
                    'id' => $recruiter_id,
                ]);
                $requiredFields = $requiredFields->json();

                if ($requiredFields['success'] && isset($requiredFields[0]) && isset($requiredFields[0]['preferences']['requiredToApply'])) {

                    $requiredFieldsToApply = $requiredFields[0]['preferences']['requiredToApply'];
                    $data['requiredFieldsToApply'] = $requiredFieldsToApply;
                }
            } else {

                $organization_id = $data['model']->organization_id;
                $requiredFields = Http::post('http://localhost:'. config('app.file_api_port') .'/organizations/get-preferences', [
                    'id' => $organization_id,
                ]);
                $requiredFields = $requiredFields->json();
                if ($requiredFields['success'] && isset($requiredFields['requiredToApply'])) {
                    $requiredFieldsToApply = $requiredFields['requiredToApply'];
                    $data['requiredFieldsToApply'] = $requiredFieldsToApply;
                }
            }

            $distinctFilters = Keyword::distinct()->pluck('filter');
            $allKeywords = [];
            foreach ($distinctFilters as $filter) {
                $keywords = Keyword::where('filter', $filter)->get();
                $allKeywords[$filter] = $keywords;
            }
            $data['allKeywords'] = $allKeywords;
            $data['states'] = State::select('id', 'name')->get();
            // $user = auth()->guard('frontend')->user();
            // dd($data["model"]->matchWithWorker()['diploma']['match']);
            $data['jobSaved'] = new JobSaved();
            //return $data['requiredFieldsToApply'];
            return view('worker::dashboard.details.details', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function sendMessages(Request $request)
    {

        $message = $request->message_data;
        $user = Auth::guard('frontend')->user();
        $id = $user->id;
        //$role = $user->role;
        $idOrganization = $request->idOrganization;
        $idRecruiter = $request->idRecruiter;
        $type = $request->type;
        $fileName = $request->fileName;

        $full_name = $user->first_name . ' ' . $user->last_name;


        $time = now()->toDateTimeString();
        event(new NewPrivateMessage($message, $idOrganization, $idRecruiter, $id, 'WORKER', $time, $type, $fileName));
        event(new NotificationMessage($message, false, $time, $idRecruiter, $id, $full_name));


        return [$id, $idOrganization];
    }

    public function get_private_messages(Request $request)
    {
        $idRecruiter = $request->query('recruiterId');
        $idOrganization = $request->query('organizationId');
        $page = $request->query('page', 1);

        $idWorker = Auth::guard('frontend')->user()->id;

        // Calculate the number of messages to skip
        $skip = ($page - 1) * 10;


        $chat = DB::connection('mongodb')->collection('chat')
            ->raw(function ($collection) use ($idOrganization, $idWorker, $idRecruiter, $skip) {
                return $collection->aggregate([
                    [
                        '$match' => [
                            'recruiterId' => $idRecruiter,
                            'organizationId' => $idOrganization,
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
    public function get_rooms(Request $request)
    {

        $idWorker = Auth::guard('frontend')->user()->id;

        $rooms = DB::connection('mongodb')->collection('chat')
            ->raw(function ($collection) use ($idWorker) {
                return $collection->aggregate([
                    [
                        '$match' => [

                            'workerId' => $idWorker,

                        ]
                    ],
                    [
                        '$project' => [
                            'organizationId' => 1,
                            'workerId' => 1,
                            'recruiterId' => 1,
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
        foreach ($rooms as $room) {
            //$user = User::where('id', $room->organizationId)->select("first_name", "last_name")->get();
            $user = User::select('first_name', 'last_name')
                ->where('id', $room->organizationId)
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
            $data_User['organizationId'] = $room->organizationId;
            $data_User['recruiterId'] = $room->recruiterId;
            $data_User['isActive'] = $room->isActive;
            $data_User['messages'] = $room->messages;

            array_push($data, $data_User);
        }


        return response()->json($data);
    }


    public function get_messages(Request $request)
    {

        $id = Auth::guard('frontend')->user()->id;
        // $nurse = Nurse::where('user_id', $id)->first();
        // $nurse_user_id = $nurse->id;
        $name = $request->input('name');
        $recruiter_id = $request->input('recruiter_id');
        $organization_id = $request->input('organization_id');

        if (isset($recruiter_id) && isset($organization_id)) {
            
            // Check if a room with the given worker_id and recruiter_id already exists
            $room = DB::connection('mongodb')->collection('chat')->where('workerId', $id)->where('organizationId', $organization_id)->first();
            
            // If the room doesn't exist, create a new one
            if (!$room) {
                DB::connection('mongodb')->collection('chat')->insert([
                    'workerId' => $id,
                    'recruiterId' => $recruiter_id,
                    'organizationId' => $organization_id, // Replace this with the actual organizationId
                    'lastMessage' => $this->timeAgo(now()),
                    'isActive' => true,
                    'messages' => [],
                ]);

                // Call the get_private_messages function
                $request->query->set('recruiterId', $recruiter_id);
                $request->query->set('organizationId', $organization_id); // Replace this with the actual organizationId
                return $this->get_direct_private_messages($request);
            }else{
                $request->query->set('recruiterId', $recruiter_id);
                $request->query->set('organizationId', $organization_id);
                return $this->get_direct_private_messages($request);
            }
        }

        $rooms = DB::connection('mongodb')->collection('chat')
            ->raw(function ($collection) use ($id) {
                return $collection->aggregate([
                    [
                        '$match' => [
                            'workerId' => $id,
                        ]
                    ],
                    [
                        '$project' => [
                            'organizationId' => 1,
                            'workerId' => 1,
                            'recruiterId' => 1,
                            'lastMessage' => 1,
                            'isActive' => 1,
                            'messages' => [
                                '$slice' => [
                                    '$messages',
                                    1
                                ]
                            ],
                            'messagesLength' => [
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
        foreach ($rooms as $room) {
            //$user = User::where('id', $room->organizationId)->select("first_name","last_name")->get();
            $user = User::select('organization_name' , 'image')
                ->where('id', $room->organizationId)
                ->get()
                ->first();

            if ($user) {
                $name = $user->organization_name;
            } else {
                // Handle the case where no user is found
                $name = 'Default Name';
            }

            if ($user->image != null && $user->image != '') {
                $image = 'uploads/' . $user->image;
            } else {
                $image = "frontend/img/account-img.png";

            }

            $data_User['fullName'] = $name;
            $data_User['recruiterImage'] = $image;

            $data_User['lastMessage'] = $this->timeAgo($room->lastMessage);
            $data_User['organizationId'] = $room->organizationId;
            $data_User['isActive'] = $room->isActive;
            $data_User['messages'] = $room->messages;
            $data_User['messagesLength'] = $room->messagesLength;

            if (isset($room->recruiterId)) {
                $data_User['recruiterId'] = $room->recruiterId;
            }

            array_push($data, $data_User);
        }
        //return $data;
        //dd($data);
        return view('worker::worker/messages', compact('id', 'data'));
    }

    public function get_direct_private_messages(Request $request)
    {
        $idOrganization = $request->query('organizationId');
        $idRecruiter = $request->query('recruiterId');
        $page = $request->query('page', 1);

        $idWorker = Auth::guard('frontend')->user()->id;

        // Calculate the number of messages to skip
        $skip = ($page - 1) * 10;

        // we need to set the recruiter static since we dont have a relation between those three roles yet so we choose "GWU000005"

        $chat = DB::connection('mongodb')
            ->collection('chat')
            ->raw(function ($collection) use ($idOrganization, $idRecruiter, $idWorker, $skip) {
                return $collection
                    ->aggregate([
                        [
                            '$match' => [
                                'organizationId' => $idOrganization,
                                // 'recruiterId'=> $idRecruiter,
                                // for now until get the offer works
                                'recruiterId' => $idRecruiter,

                                'workerId' => $idWorker,
                            ],
                        ],
                        [
                            '$project' => [
                                'messages' => [
                                    '$slice' => ['$messages', $skip, 10],
                                ],
                            ],
                        ],
                    ])
                    ->toArray();
            });
        // {{ $room['workerId'] }}','{{ $room['fullName'] }}','{{ $room['organizationId'] }}')"
        $direct = true;
        $rooms = DB::connection('mongodb')
            ->collection('chat')
            ->raw(function ($collection) use ($idWorker) {
                return $collection
                    ->aggregate([
                        [
                            '$match' => [
                                //'recruiterId' => $id,
                                // for now until get the offer works
                                'workerId' => $idWorker,
                            ],
                        ],
                        [
                            '$project' => [
                                'organizationId' => 1,
                                'workerId' => 1,
                                'recruiterId' => 1,
                                'lastMessage' => 1,
                                'isActive' => 1,
                                'messages' => [
                                    '$slice' => ['$messages', 1],
                                ],
                            ],
                        ],
                    ])
                    ->toArray();
            });

        //return response()->json(['rooms',$rooms]);

        $data = [];
        $data_User = [];
        foreach ($rooms as $room) {
            //$user = User:select :where('id', $room->workerId);
            //$nurse = Nurse::where('id', $room->workerId)->first();
            $user = User::select('organization_name')
                ->where('id', $room->organizationId)
                ->get()
                ->first();

            if ($user) {
                $name = $user->organization_name;
            } else {
                // Handle the case where no user is found
                $name = 'Default Name';
            }

            $data_User['fullName'] = $name;
            $data_User['lastMessage'] = $this->timeAgo($room->lastMessage);
            $data_User['recruiterId'] = $room->recruiterId;
            $data_User['isActive'] = $room->isActive;
            $data_User['organizationId'] = $room->organizationId;
            $data_User['messages'] = $room->messages;

            array_push($data, $data_User);
        }
        $recruiter = User::select('first_name', 'last_name')
            ->where('id', $idOrganization)
            ->get()
            ->first();

        if ($recruiter) {
            $nameworker = $user->organization_name;
        } else {
            // Handle the case where no user is found
            $nameworker = 'Default Name';
        }
        $id = Auth::guard('frontend')->user()->id;
        return view('worker::worker/messages', compact('idWorker', 'idOrganization', 'direct', 'id', 'data', 'nameworker'));

    }

    public function timeAgo($time = NULL)
    {
        // If $time is not a timestamp, try to convert it
        if (!is_numeric($time)) {
            $time = strtotime($time);
        }

        // Calculate difference between current
        // time and given timestamp in seconds
        $diff = time() - $time;
        // Time difference in seconds
        $sec = $diff;
        // Convert time difference in minutes
        $min = round($diff / 60);
        // Convert time difference in hours
        $hrs = round($diff / 3600);
        // Convert time difference in days
        $days = round($diff / 86400);
        // Convert time difference in weeks
        $weeks = round($diff / 604800);
        // Convert time difference in months
        $mnths = round($diff / 2600640);
        // Convert time difference in years
        $yrs = round($diff / 31207680);
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

    // public function get_my_work_journey()
    // {
    //     $front_end_user = Auth::guard('frontend')->user();
    //     $user = Nurse::where('user_id', $front_end_user->id)->first();
    //     $whereCond = [
    //         'jobs.is_open' => "1",
    //         'jobs.is_closed' => "0",
    //         // 'job_saved.is_delete'=>'0',
    //         // 'job_saved.nurse_id'=>$user->id,
    //     ];

    //     $data = [];

    //     //return request()->route()->getName();

    //     switch (request()->route()->getName()) {
    //         case 'worker.my-work-journey':
    //             $whereCond['jobs.is_hidden'] = '0';
    //             $jobs = Job::select("jobs.*")
    //                 ->join('job_saved', function ($join) use ($user) {
    //                     $join->on('job_saved.job_id', '=', 'jobs.id')
    //                         ->where(function ($query) use ($user) {
    //                             $query->where('job_saved.is_delete', '=', '0')
    //                                 ->where('job_saved.is_save', '=', '1')
    //                                 ->where('job_saved.nurse_id', '=', $user->id);
    //                         });
    //                 })
    //                 ->where($whereCond)
    //                 ->orderBy('job_saved.created_at', 'DESC')
    //                 ->get();
    //             $data['type'] = 'saved';
    //             break;

    //         case 'applied-jobs':
    //             $jobs = Job::select("jobs.*")
    //                 ->join('offers', function ($join) use ($user) {
    //                     $join->on('offers.job_id', '=', 'jobs.id')
    //                         ->where(function ($query) use ($user) {
    //                             $query->whereIn('offers.status', ['Apply', 'Screening', 'Submitted'])
    //                                 ->where('offers.worker_user_id', '=', $user->id);
    //                         });
    //                 })
    //                 ->where($whereCond)
    //                 ->orderBy('offers.created_at', 'DESC')
    //                 ->get();
    //             $data['type'] = 'applied';
    //             break;

    //         case 'hired-jobs':
    //             unset($whereCond['jobs.is_closed']);
    //             $jobs = Job::select("jobs.*")
    //                 ->join('offers', function ($join) use ($user) {
    //                     $join->on('offers.job_id', '=', 'jobs.id')
    //                         ->where(function ($query) use ($user) {
    //                             $query->whereIn('offers.status', ['Onboarding', 'Working'])
    //                                 ->where('offers.worker_user_id', '=', $user->id);
    //                         });
    //                 })
    //                 ->where($whereCond)
    //                 ->orderBy('offers.created_at', 'DESC')
    //                 ->get();
    //             $data['type'] = 'hired';
    //             break;

    //         case 'offered-jobs':

    //             $jobs = Job::select("jobs.*", "offers.id as offer_id")
    //                 ->join('offers', function ($join) use ($user) {
    //                     $join->on('offers.job_id', '=', 'jobs.id')
    //                         ->where(function ($query) use ($user) {
    //                             $query->whereIn('offers.status', ['Offered', 'Hold'])
    //                                 ->where('offers.worker_user_id', '=', $user->id)
    //                             ;
    //                         });
    //                 })
    //                 ->where($whereCond)
    //                 ->orderBy('offers.created_at', 'DESC')
    //                 ->get();
    //             // return response()->json(['msg'=>$jobs]);
    //             $data['type'] = 'offered';
    //             break;

    //         case 'past-jobs':
    //             unset($whereCond['jobs.is_closed']);
    //             $jobs = Job::select("jobs.*")
    //                 ->join('offers', function ($join) use ($user) {
    //                     $join->on('offers.job_id', '=', 'jobs.id')
    //                         ->where(function ($query) use ($user) {
    //                             $query->where('offers.status', '=', 'Done')
    //                                 ->where('offers.worker_user_id', '=', $user->id);
    //                         });
    //                 })
    //                 ->where($whereCond)
    //                 ->orderBy('offers.created_at', 'DESC')
    //                 ->get();
    //             $data['type'] = 'past';
    //             break;
    //         case 'saved-jobs':
    //             $jobs = Job::select("jobs.*")
    //                 ->join('job_saved', function ($join) use ($user) {
    //                     $join->on('job_saved.job_id', '=', 'jobs.id')
    //                         ->where(function ($query) use ($user) {
    //                             $query->where('job_saved.is_delete', '=', '0')
    //                                 ->where('job_saved.is_save', '=', '1')
    //                                 ->where('job_saved.nurse_id', '=', $user->id);
    //                         });
    //                 })
    //                 ->where($whereCond)
    //                 ->orderBy('job_saved.created_at', 'DESC')
    //                 ->get();
    //             $data['type'] = 'saved';
    //             break;
    //         default:
    //             return redirect()->back();
    //             break;
    //     }

    //     $data['jobs'] = $jobs;
    //     return view('worker::jobs.jobs', $data);
    // }

    public function get_my_work_journey()
    {
        // auto move to working 
        $user = Auth::guard('frontend')->user();
        $worker = Nurse::where('user_id',$user->id)->first();
        $id = $worker->id;
        $offers = Offer::where('status', 'Onboarding')->where('worker_user_id', $id)->get();

        // Move this to a separate cron task
        foreach ($offers as $offer) {

            if($offer->as_soon_as == '1'){
                $offer->status = 'Working';
                $offer->save();
                continue;
            }

            $job = Job::where('id', $offer->job_id)->first();
            $start_date = new DateTime($offer->start_date);
            $today = new DateTime();

            if ($start_date <= $today) {
                $offer->status = 'Working';
                $offer->save();
            }
            
        } 

        
        $statusList = ['Apply', 'Screening', 'Submitted', 'Offered', 'Done', 'Onboarding', 'Cleared', 'Working', 'Rejected', 'Blocked', 'Hold'];
        $statusCounts = [];
        $offerLists = [];

        foreach ($statusList as $status) {
            $statusCounts[$status] = 0;
        }
        $distinctFilters = Keyword::distinct()->pluck('filter');
        $allKeywords = [];
        foreach ($distinctFilters as $filter) {
            $keywords = Keyword::where('filter', $filter)->get();
            $allKeywords[$filter] = $keywords;
        }
        $Cities = Cities::all()->pluck('name', 'id', 'state_id');
        // Count unique workers applying
        //$statusCountsQuery = Offer::where('recruiter_id', $recruiter->id)->whereIn('status', $statusList)->select(\DB::raw('status, count(distinct worker_user_id ) as count'))->groupBy('status')->get();

        // Count unique applications per worker
        $statusCountsQuery = Offer::where('worker_user_id', $id )->whereIn('status', $statusList)->select(\DB::raw('status, count(*) as count'))->groupBy('status')->get();
        foreach ($statusCountsQuery as $statusCount) {
            
            if ($statusCount) {

                $statusCounts[$statusCount->status] = $statusCount->count;
            
            } else {
                $statusCounts[$statusCount->status] = 0;
            }
        }
        $status_count_draft = Offer::where('is_draft', true)->count();
        $nurse = $worker;
        return view('worker::offers/applicationjourney', compact('statusCounts', 'status_count_draft', 'allKeywords', 'nurse', 'Cities'));
    }

    public function explore(Request $request)
    {
        try {
            // commenting this for now we need to return only jobs data

            $data = [];
            $data['user'] = auth()->guard('frontend')->user();
            $data['jobSaved'] = new JobSaved();
            $data['professions'] = Keyword::where(['filter' => 'Profession', 'active' => '1'])->get();
            $data['terms'] = Keyword::where(['filter' => 'jobType', 'active' => '1'])->get();
            $data['prefered_shifts'] = Keyword::where(['filter' => 'PreferredShift', 'active' => '1'])->get();
            $data['usa'] = $usa = Countries::where(['iso3' => 'USA'])->first();
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
            $data['shifts'] = isset($request->shifts) ? explode('-', $request->shifts) : [];
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
                ->leftJoin('job_saved', function ($join) use ($user) {
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
            if ($data['city']) {
                $ret->where('jobs.job_city', '=', $data['city']);
            }

            if ($data['state']) {

                $state = State::where('name' ,$data['state'])->get();

                $ret->where('job_state', '=', $state[0]->name)->orWhere('job_state', '=', $state[0]->iso2);
            }

            if (count($data['job_type'])) {
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

            $resl = Job::select('jobs.*', 'name')
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

    public function my_profile()
    {

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
                    $offerdetails = Offer::where('id', $offer_id->id)->first();
                    $jobdetails = Job::where('id', $job->id)->first();
                    $nursedetails = Nurse::where('id', $worker_id->id)->first();
                    $recruiter = User::where('id', $jobdetails->created_by)->first();
                    $data['offerdetails'] = $offerdetails;
                    $data['jobdetails'] = $jobdetails;
                    $data['nursedetails'] = $nursedetails;
                    $data['recruiter'] = $recruiter;
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
                        $data['jobdetails'] = $job;
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

    public function checkPaymentMethod($user_id)
    {
        $url = 'http://localhost:' . config('app.file_api_port') . '/payments/onboarding-status';
        $stripe_id = User::where('id', $user_id)->first()->stripeAccountId;
        $data = ['stripeId' => $stripe_id];
        $response = Http::get($url, $data);

        if ($stripe_id == null || $response->json()['status'] == false) {
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

        // if (!$this->checkPaymentMethod($user_id)) {
        //     return response()->json(['success' => false, 'message' => 'Please complete your payment method onboarding first']);
        // }

        try {
            $request->validate([
                'offer_id' => 'required',
            ]);

            $offer = Offer::where('id', $offer_id)->first();
            if (!$offer) {
                return response()->json(['success' => false, 'message' => 'Offer not found']);
            }

            $job = Job::where('id', $offer->job_id)->first();
            if (!$job) {
                return response()->json(['success' => false, 'message' => 'Job not found']);
            }


            $update_array['is_counter'] = '0';
            $update_array['is_draft'] = '0';
            $update_array['status'] = 'Hold';
            // $is_offer_update = DB::table('offers')
            //     ->where(['id' => $offer_id])
            //     ->update($update_array);
            $user_id = $job->created_by;
            $user = User::where('id', $user_id)->first();

            // $data = [
            //     'offerId' => $offer_id,
            //     'amount' => '1',
            //     'stripeId' => $user->stripeAccountId,
            //     'fullName' => $user->first_name . ' ' . $user->last_name,
            // ];

            //return response()->json(['message'=>$data]);

            // $url = 'http://localhost:' . config('app.file_api_port') . '/payments/customer/invoice';

            // return response()->json(['data'=>$data , 'url' => $url]);

            // Make the request
            // $responseInvoice = Http::post($url, $data);
            // return response()->json(['message'=>$responseInvoice->json()]);
            // $responseData = [];
            // if ($offer) {
            //     $responseData = [
            //         'status' => 'success',
            //         'message' => $responseInvoice->json()['message'],
            //     ];
            // }


            // event offer notification

            $id = $offer_id;
            $jobid = $offer->job_id;

            $time = now()->toDateTimeString();
            $receiver = $offer->recruiter_id;
            $job_name = Job::where('id', $jobid)->first()->job_name;

            event(new NotificationOffer('Hold', false, $time, $receiver, $nurse_id, $full_name, $jobid, $job_name, $id));
            return response()->json(['msg' => 'offer accepted successfully', 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
            //return response()->json(['success' => false, 'message' =>  "Something was wrong please try later !"]);
        }
    }

    public function reject_offer(Request $request)
    {
        $user = Auth::guard('frontend')->user();

        $full_name = $user->first_name . ' ' . $user->last_name;
        $nurse_id = $user->nurse->id;
        try {
            $request->validate([
                'offer_id' => 'required',
            ]);

            $offer = Offer::where('id', $request->offer_id)->first();
            if (!$offer) {
                return response()->json(['success' => false, 'message' => 'Offer not found']);
            }

            $job = Job::where('id', $offer->job_id)->first();
            if (!$job) {
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

                event(new NotificationOffer('Rejected', false, $time, $receiver, $nurse_id, $full_name, $jobid, $job_name, $id));
                return response()->json(['msg' => 'Offer rejected successfully', 'success' => true]);
            } else {
                return response()->json(['msg' => 'offer not rejected', 'success' => false]);
            }
        } catch (\Exception $e) {
            // return response()->json(['success' => false, 'message' =>  "$e->getMessage()"]);
            return response()->json(['success' => false, 'message' => "Something was wrong please try later !"]);
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

        try {
            $body = $request->getContent();

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withBody($body, 'application/json')->post('http://localhost:' . config('app.file_api_port') . '/documents/add-docs');
            
            $body = json_decode($response->body());

            if ($response->successful() && $body->success) {
                
                return response()->json([
                    'ok' => true,
                    'message' => 'Files uploaded successfully',
                ]);

            } else {

                return response()->json([
                    'ok' => false,
                    'message' => $body->message,
                ], $response->status());
                
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function listDocs(Request $request)
    {
        try {
            // validate WorkerId
            $validator = Validator::make($request->all(), [
                'WorkerId' => 'required|exists:nurses,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }

            $workerId = $request->input('WorkerId');

            $response = Http::get('http://localhost:'. config('app.file_api_port') .'/documents/list-docs', ['workerId' => $workerId]);

            $body = json_decode($response->body());

            if( $body->success)
            {
                return json_encode($body->data->list);
                // return response()->json(['success' => true, 'data' => $body->data->list]);
            }else{
                return response()->json(['success' => false, 'message' => $body->message], $response->status());
            }
           
        }catch(\Exception $e){

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
           
        }
    }

    public function getDoc(Request $request)
    {
        try {
             $validator = Validator::make($request->all(), [
                'bsonId' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }

            $bsonId = $request->input('bsonId');
            $response = Http::get('http://localhost:'. config('app.file_api_port') .'/documents/get-doc', ['bsonId' => $bsonId]);

            $body = json_decode($response->body());

            if( $body->success)
            {
                return json_encode($body->data);
            }else{
                return response()->json(['success' => false, 'message' => $body->message], $response->status());
            }
           
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function deleteDoc(Request $request)
    {
        $bsonId = $request->input('bsonId');

        $response = Http::post('http://localhost:'. config('app.file_api_port') .'/documents/del-doc', ['bsonId' => $bsonId]);
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
                $oldFilePath = public_path($destinationPath . '/' . $old_vac->name);
                if (file_exists($oldFilePath)) {
                    File::delete($oldFilePath);
                }
                $old_vac->forceDelete();
            }

            // Handle file upload
            $file = $request->file($vacc_file);
            $filename = $type . '_' . $id . '_vaccination.' . $file->getClientOriginalExtension();
            $file->move(public_path($destinationPath), $filename);

            NurseAsset::create([
                'nurse_id' => $id,
                'file_name' => $filename,
                'name' => $type,
                'filter' => 'vaccination'
            ]);

            $response = array('success' => true, 'msg' => $type . ' Updated Successfully!');
            return $response;
        } else {
            // Check and handle vaccination record removal if applicable
            $old_vac = NurseAsset::where('nurse_id', $id)->where('name', $type)->first();
            if ($old_vac) {
                $oldFilePath = public_path($destinationPath . '/' . $old_vac->name);
                if (file_exists($oldFilePath)) {
                    File::delete($oldFilePath);
                }
                $old_vac->forceDelete();
                $response = array('success' => true, 'msg' => $type . ' Removed Successfully!');
                return $response;
            }
        }

        $response = array('success' => false, 'msg' => 'No vaccination record selected!');
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
                $oldFilePath = public_path($destinationPath . '/' . $old_cer->name);
                if (file_exists($oldFilePath)) {
                    File::delete($oldFilePath);
                }
                $old_cer->forceDelete();
            }

            // Handle file upload
            $file = $request->file($cert_file);
            $filename = $type . '_' . $id . '_certificate.' . $file->getClientOriginalExtension();
            $file->move(public_path($destinationPath), $filename);

            NurseAsset::create([
                'nurse_id' => $id,
                'file_name' => $filename,
                'name' => $type,
                'filter' => 'certificate'
            ]);

            //$responses[] = ['success' => true, 'msg' => $certificate . ' Updated Successfully!'];
            $response = array('success' => true, 'msg' => $type . ' Updated Successfully!');
            return $response;
        } else {
            // Check and handle certificate removal if applicable
            $old_cer = NurseAsset::where('nurse_id', $id)->where('name', $type)->first();
            if ($old_cer) {
                $oldFilePath = public_path($destinationPath . '/' . $old_cer->name);
                if (file_exists($oldFilePath)) {
                    File::delete($oldFilePath);
                }
                $old_cer->forceDelete();
                $response = array('success' => true, 'msg' => $type . ' Removed Successfully!');
                return $response;
            }
        }

        $response = array('success' => false, 'msg' => 'No certificate selected!');
        return $response; // Return all responses after processing all certificates
    }


    public function match_worker_job(Request $request)
    {

        //return $request->all();
        $user = auth()->guard('frontend')->user();

        $id = $user->id;
        $nurse = Nurse::where('user_id', $id)->first();
        $inputFields = collect($request->all())->filter(function ($value) {
            return $value !== null;
        });

        $inputFields->put('updated_at', Carbon::now());

        // Filter request data to only include valid attributes
        $inputFields = $inputFields->only($nurse->getFillable());

        $nurse->fill($inputFields->all());
        $nurse->save();
        return new JsonResponse(['success' => true, 'msg' => 'Updated successfully.'], 200);
    }

    // get organization infromation of each offer type
    public function get_offers_by_type(Request $request)
    {

        try {
            $type = $request->type;
            if( $type == 'Apply'){
                    try {
                        $type = $request->type;
                        $user = Auth::guard('frontend')->user();
                        $worker = Nurse::where('user_id',$user->id)->first();
                        $offerLists = Offer::where('status', $type)->where('worker_user_id', $worker->id)->get();
                    
                        $nurses = [];
                        $offerData = [];
                        foreach ($offerLists as $value) {

                                $nurses[] = $value->worker_user_id;
                                $nurse = Nurse::where('id', $value->worker_user_id)->first();
                                $user = User::where('id', $nurse->user_id)->first();
                                $nowDate = Carbon::now();

                                $recently_added = $nowDate->isSameDay($value->created_at);
                                if($recently_added == false){
                                    $recently_added = $value->created_at->diffForHumans();
                                }
                                
                                $offerData[] = [
                                    'offerId' => $value->id,
                                    'workerUserId' => $value->worker_user_id,
                                    'image' => $user->image,
                                    'firstName' => $user->first_name,
                                    'lastName' => $user->last_name,
                                    'city' => $value->city ?? null,
                                    'state' => $value->state ?? null,
                                    'profession' => $value->profession ?? null,
                                    'specialty' => $value->specialty ?? null,
                                    'facilityName' => $value->facility_name ?? null,
                                    'preferred_shift_duration' => $value->preferred_shift_duration ?? null,
                                    'JobId' => $value->job_id,
                                    'status' => $value->status,
                                    'recently_added' => $recently_added
                                ];
                            
                        }
                        if (empty($offerData)) {
                            $noApplications = true;
                        } else {
                            $noApplications = false;
                        }
                        $response['content'] = view('worker::offers.applications', ['noApplications' => $noApplications, 'offerData' => $offerData])->render();


                        //return new JsonResponse($response, 200);
                        return response()->json($response);
                    } catch (\Exception $ex) {
                        return response()->json(["message" => $ex->getMessage()]);
                    }
            }else{
                    try{

                    $user = Auth::guard('frontend')->user();
                    $worker = Nurse::where('user_id',$user->id)->first();
                    $offerLists = Offer::where('status', $type)->where('worker_user_id', $worker->id)->get();    
                    
                    $organizations = [];
                    $offerData = [];

                    foreach ($offerLists as $value) {

                        if ($value && !in_array($value->organization_id, $organizations)) {
                             $organizations[] = $value->organization_id;
                            // $nurse = Nurse::where('id', $value->worker_user_id)->first();
                            $user = User::where('id', $value->organization_id)->first();

                            $nowDate = Carbon::now();

                            $recently_added = $nowDate->isSameDay($value->created_at);
                            if($recently_added == false){
                                $recently_added = $value->created_at->diffForHumans();
                            }

                            $offerData[] = [
                                'type' => $type,
                                'OrganizationId' => $value->organization_id,
                                'image' => $user->image,
                                'organization_name' => $user->organization_name,
                                'recently_added' => $recently_added
                            ];
                        }
                    }
                    if (empty($offerData)) {
                        $noApplications = true;
                    } else {
                        $noApplications = false;
                    }
                    $response['content'] = view('worker::offers.workers_cards_information', ['noApplications' => $noApplications, 'offerData' => $offerData])->render();
                    //return new JsonResponse($response, 200);
                    return response()->json($response);

                    } catch (\Exception $ex) {
                        return response()->json(["message" => $ex->getMessage()]);
                    }
            }
        }catch (\Exception $ex) {

            return response()->json(["message" => $ex->getMessage()]);

        }
    }


    function get_offers_of_each_organization(Request $request)
    {
        try {
            
            $user = Auth::guard('frontend')->user();
            $organization_id = $request->organization_id;
            $organization = User::where('id', $organization_id)->first();
            $type = $request->type;
            $nurse = Nurse::where('user_id', $user->id)->first();
            $worker_id = $nurse->id;
            $offers = Offer::where(['status' => $type, 'worker_user_id' => $worker_id, 'organization_id' => $organization_id])->get();
            $jobappliedcount = Offer::where(['status' => $type, 'worker_user_id' => $worker_id, 'organization_id' => $organization_id])->count();

            // file availablity check
            $hasFile = false;
            $urlDocs = 'http://localhost:' . config('app.file_api_port') . '/documents/get-docs';
            $fileresponse = Http::post($urlDocs, ['workerId' => $worker_id]);
            $files = [];

	        // return response()->json($fileresponse->json());	
            // if (!empty($fileresponse->json()['files'])) {
            //     $hasFile = true;
		
            //     foreach ($fileresponse->json()['files'] as $file) {
		    //         if(isset($file['content']) && isset($file['name']) && isset($file['type'])){	
            //             $files[] = [
            //                 'name' => $file['name'],
            //                 'content' => $file['content'],
            //                 'type' => $file['type']
            //             ];
		    //         }      
            //     }
            // }

            $response['content'] = view('worker::offers.workers_complete_information', ['type' => $type, 'hasFile' => $hasFile, 'userdetails' => $organization, 'nursedetails' => $nurse, 'jobappliedcount' => $jobappliedcount, 'offerdetails' => $offers])->render();
            $response['files'] = $files;

            return response()->json($response);

        } catch (\Exeption $ex) {
            return response()->json(["message" => $ex->getMessage()]);
        }
    }


    function get_one_offer_information(Request $request)
    {
        // return $request->all();
        try {
            $offer_id = $request->offer_id;
            $offer = Offer::where('id', $offer_id)->first();
            $offerLogs = OffersLogs::where('original_offer_id', $offer_id)->get();
            $worker_id = $offer->worker_user_id;
            $worker_details = Nurse::where('id', $worker_id)->first();
            $user = User::where('id', $worker_details->user_id)->first();
            $organization = User::where('id', $offer->organization_id)->first();
            $recruiter_id = $offer->recruiter_id;
            $recruiter = User::where('id', $recruiter_id)->first();

            if ($offer->status == 'Offered') {
                $offerLogsDiff = OffersLogs::select('details')->where('original_offer_id', $offer_id)->first();
                if(empty($offerLogsDiff)){
                    $offerLogsDiff = null;
                }
                $response['content'] = view('worker::offers.new_terms_vs_old_terms', ['userdetails' => $user, 'offerdetails' => $offer, 'offerLogs' => $offerLogs, 'diff' => $offerLogsDiff, 'organization' => $organization, 'recruiter' => $recruiter])->render();
                return response()->json($response);
            }

            $distinctFilters = Keyword::distinct()->pluck('filter');
            $allKeywords = [];
            foreach ($distinctFilters as $filter) {
                $keywords = Keyword::where('filter', $filter)->get();
                $allKeywords[$filter] = $keywords;
            }

            $response['content'] = view('worker::offers.offer_vs_worker_information', ['userdetails' => $user, 'offerdetails' => $offer, 'offerLogs' => $offerLogs, 'organization' => $organization, 'recruiter' => $recruiter , 'allKeywords' => $allKeywords])->render();
            
            return response()->json($response);
        
        } catch (\Exception $ex) {
            return response()->json(["message" => $ex->getMessage()]);
        }
    }


        // get offer information for form counter
    public function get_offer_information(Request $request)
    {
        try {
            $offer_id = $request->offer_id;
            $offerdetails = Offer::where('id', $offer_id)->first();
            // the one who counter an offer or send the first offer
            $user_id = $offerdetails->created_by;
            $userdetails = User::where('id', $user_id)->first();
            $specialities = Speciality::select('full_name')->get();
            $professions = Profession::select('full_name')->get();
            $applyCount = array();
            // send the states
            $states = State::select('id', 'name')->get();
            $distinctFilters = Keyword::distinct()->pluck('filter');
            $allKeywords = [];
            foreach ($distinctFilters as $filter) {
                $keywords = Keyword::where('filter', $filter)->get();
                $allKeywords[$filter] = $keywords;
            }
             
            $response['content'] = view('worker::offers.counter_offer_form', ['offerdetails' => $offerdetails, 'userdetails' => $userdetails, 'allKeywords' => $allKeywords, 'states' => $states, 'specialities' => $specialities, 'professions' => $professions])->render();
            
            //return new JsonResponse($response, 200);
            return response()->json($response);

        } catch (\Exception $ex) {
            return response()->json(["message" => $ex->getMessage()]);
        }
    }

    public function worker_counter_offer(Request $request)
    {

        try {
            $worker = Auth::guard('frontend')->user();
            $worker_id = $worker->nurse->id;
            $full_name = $worker->first_name . ' ' . $worker->last_name;
            $offer_id = $request->id;
            $data = $request->data;
            $diff = $request->diff;
            
            $offer = Offer::where('id', $offer_id)->first();

            $action  = '' ;

            if(OffersLogs::where('original_offer_id', $offer_id)->exists()){
                
                $offerLog = OffersLogs::where('original_offer_id', $offer_id)->first();
                $offerLog->update([
                    'details' => json_encode($diff),
                ]);

                $action = 'has sent you a counter offer' ;
                
            }else{
                OffersLogs::create([
                    'counter_offer_by' => $worker_id,
                    'nurse_id' => $offer['worker_user_id'],
                    'organization_recruiter_id' => $offer['organization_id'],
                    'original_offer_id' => $offer_id,
                    'details' => json_encode($diff),
                    'status' => 'Counter Offer'
                ]);

                $action = 'has sent you an offer' ;
            }


            // update it
            if ($offer) {
                
                $data['status'] = 'Offered';
      
                $offer->update($data);
                $jobid = $offer->job_id;
                $time = now()->toDateTimeString();
                $receiver = $offer->recruiter_id;
                $job_name = Job::where('id', $jobid)->first()->job_name;
                event(new NotificationOffer('Offered', false, $time, $receiver, $worker_id, $full_name, $jobid, $job_name, $offer_id));

                $message = $full_name . ' ' . $action;
                $idOrganization = $offer->organization_id;
                $idWorker = $worker->id;
                $recruiter_id = $offer->recruiter_id;
                $role = 'ADMIN';
                $type = 'text';
                $fileName = null;
                $time = now()->toDateTimeString();
                event(new NewPrivateMessage($message, $idOrganization, $recruiter_id, $idWorker, $role, $time, $type, $fileName));

                return response()->json([
                    'status' => 'success',
                    'message' => 'Offer updated successfully',
                    'offer' => $offer
                ]);

            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Offer not found'
                ], 404);
            }

        } catch (\Exception $ex) {
            return response()->json(["message" => $ex->getMessage()]);
        }


    }

    public function updateApplicationStatus(Request $request)
    {
        $worker = Auth::guard('frontend')->user();
        $worker_id = $worker->nurse->id;
        $full_name = $worker->first_name . ' ' . $worker->last_name;
        $offer_id = $request->id;
        $offer = Offer::where('id', $offer_id)->first();
        $status = $request->status;
        $jobid = $offer->job_id;

        if (isset($jobid)) {
            $job = Offer::where(['id' => $offer_id])->update(['status' => $status]);
            if ($job) {
                // send notification to the worker
                $time = now()->toDateTimeString();
                $receiver = $offer->recruiter_id;
                $job_name = Job::where('id', $jobid)->first()->job_name;
                event(new NotificationOffer($status, false, $time, $receiver, $worker_id, $full_name, $jobid, $job_name, $offer_id));
                $statusList = ['Apply', 'Screening', 'Submitted', 'Offered', 'Done', 'Onboarding', 'Cleared', 'Working', 'Rejected', 'Blocked', 'Hold'];
                $statusCounts = [];
                $offerLists = [];
                foreach ($statusList as $status) {
                    $statusCounts[$status] = 0;
                }
                $statusCountsQuery = Offer::where('worker_user_id', $worker_id)->whereIn('status', $statusList)->select(\DB::raw('status, count(*) as count'))->groupBy('status')->get();
                foreach ($statusCountsQuery as $statusCount) {
                    if ($statusCount) {
                        $statusCounts[$statusCount->status] = $statusCount->count;
                    } else {
                        $statusCounts[$statusCount->status] = 0;
                    }
                }
                return response()->json(['message' => 'Update Successfully', 'type' => $status, 'statusCounts' => $statusCounts]);
            } else {
                return response()->json(['message' => 'Something went wrong! Please check']);
            }
        } else {
            return response()->json(['message' => 'Something went wrong! Please check']);
        }
    }

    public function AcceptOrRejectJobOffer(Request $request)
    {
       
        try {
            $user = Auth::guard('frontend')->user();
            $worker_id = $user->nurse->id;
            $full_name = $user->first_name . ' ' . $user->last_name;
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'jobid' => 'required',
            ]);
            if ($validator->fails()) {
                $responseData = [
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ];
            } else {

                $offer = Offer::where('id', $request->id)->first();
                $time = now()->toDateTimeString();
                $action = $request->type == 'rejectcounter' ? 'Rejected' : 'Accepted';
                $message = $full_name . ' has ' . $action . ' the job offer';
                $idOrganization = $offer->organization_id;
                $idWorker = $user->id;
                $recruiter_id = $offer->recruiter_id;
                $role = 'ADMIN';
                $type = 'text';
                $fileName = null;

                if ($request->type == 'rejectcounter') {
                    $update_array['is_counter'] = '0';
                    $update_array['is_draft'] = '0';
                    $update_array['status'] = 'Rejected';
                    $job = DB::table('offers')
                        ->where(['id' => $request->id])
                        ->update($update_array);
                    if ($job) {
                        $responseData = [
                            'status' => 'success',
                            'message' => 'Job Rejected successfully',
                        ];
                        $offer = Offer::where('id', $request->id)->first();
                        $id = $request->id;
                        $jobid = $offer->job_id;
                        $time = now()->toDateTimeString();
                        $receiver = $offer->worker_user_id;
                        $job_name = Job::where('id', $jobid)->first()->job_name;

                        event(new NewPrivateMessage($message, $idOrganization, $recruiter_id, $idWorker, $role, $time, $type, $fileName));

                        //event(new NotificationOffer('Rejected', false, $time, $receiver, $worker_id, $full_name, $jobid, $job_name, $id));
                    }
                } else if ($request->type == 'offersend') {
                    $update_array['is_counter'] = '0';
                    $update_array['is_draft'] = '0';
                    $update_array['status'] = 'Onboarding';
                  
                    $offer_updated = $offer->update($update_array);
                    
                    $data = [
                        'offerId' => $request->id,
                        'amount' => '1',
                        'stripeId' => $user->stripeAccountId,
                        'fullName' => $user->first_name . ' ' . $user->last_name,
                    ];

                    $responseData = [];
                    if ($offer_updated) {
                        // $responseData = [
                        //     'status' => 'success',
                        //     'message' => $responseInvoice->json()['message'],
                        // ];
                        $offer = Offer::where('id', $request->id)->first();
                        $id = $request->id;
                        $jobid = $offer->job_id;
                        $time = now()->toDateTimeString();
                        $receiver = $offer->worker_user_id;
                        $job_name = Job::where('id', $jobid)->first()->job_name;

                        event(new NewPrivateMessage($message, $idOrganization, $recruiter_id, $idWorker, $role, $time, $type, $fileName));
                        //event(new NotificationOffer('Offered', false, $time, $receiver, $recruiter_id, $full_name, $jobid, $job_name, $id));

                    }
                }
                return response()->json($responseData);
            }

        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()]);
        }

    }

    
    public function worker_update_information(Request $request)
    {
        try {
            $worker = Auth::guard('frontend')->user();
            $worker_id = $worker->nurse->id;

            $worker = Nurse::find($worker_id);
            if (!$worker) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Worker not found',
                ], 404);
            }
        
            $update_array = $request->all();

            $fillableFields = (new Nurse)->getFillable();
            $filteredData = array_filter(
                $update_array,
                function ($key) use ($fillableFields) {
                    return in_array($key, $fillableFields);
                },
                ARRAY_FILTER_USE_KEY
            );

            $worker->update($filteredData);

            return response()->json([
                'success' => true,
                'msg' => 'Information updated successfully',
            ], 200);
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error('Error updating worker information: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'msg' => 'An error occurred while updating information',
            ], 500);
        }
    }

}
