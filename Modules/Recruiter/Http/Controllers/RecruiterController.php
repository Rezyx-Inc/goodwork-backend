<?php

namespace Modules\Recruiter\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Events\NewPrivateMessage;
use DB;
use Auth;

use App\Models\Offer;
use Exception;
use App\Models\Job;
use App\Models\User;

class RecruiterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('recruiter::index');
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


    public function get_private_messages(Request $request)
{
    $idEmployer = $request->query('employerId');
    $idWorker = $request->query('workerId');
    $page = $request->query('page', 1);
    
    $idRecruiter = Auth::guard('recruiter')->user()->id;
   
    // Calculate the number of messages to skip
    $skip = ($page - 1) * 10;


    // we need to set the recruiter static since we dont have a relation between those three roles yet so we choose "GWU000005"

    $chat = DB::connection('mongodb')->collection('chat')
    ->raw(function($collection) use ( $idEmployer,$idRecruiter, $idWorker, $skip) {
        return $collection->aggregate([
            [
                '$match' => [
                    'employerId' => $idEmployer,
                    // 'recruiterId'=> $idRecruiter,
                    // for now until get the offer works
                    'recruiterId'=> 'GWU000005',

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

public function get_rooms(Request $request){
    $idRecruiter = Auth::guard('recruiter')->user()->id;

    $rooms = DB::connection('mongodb')->collection('chat')
    ->raw(function($collection) use ($idRecruiter) {
        return $collection->aggregate([
            [
                '$match' => [
                    'recruiterId' => $idRecruiter,
                ]
            ],
            [
                '$project' => [
                    'employerId' => 1,
                    'recruiterId'=>1,
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
    $data_User['employerId'] = $room->employerId;
    $data_User['isActive'] = $room->isActive;
    $data_User['messages'] = $room->messages;

    array_push($data,$data_User);
    }

    
     return response()->json($data);
}

    public function get_messages(){
        
        $id = Auth::guard('recruiter')->user()->id;

    $rooms = DB::connection('mongodb')->collection('chat')
    ->raw(function($collection) use ($id) {
        return $collection->aggregate([
            [
                '$match' => [
                    
                    //'recruiterId' => $id,

                    // for now until get the offer works 

                    'recruiterId' => 'GWU000005',
                    
                ]
            ],
            [
                '$project' => [
                    'employerId' => 1,
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

    $data = [];
    foreach($rooms as $room){
    $user = User::where('id', $room->workerId)->where('role','NURSE')->select("first_name",
    "last_name")->get();

    $data_User['fullName'] = $user[0]->fullName;
    $data_User['lastMessage'] = $this->timeAgo($room->lastMessage);
    $data_User['workerId'] = $room->workerId;
    $data_User['isActive'] = $room->isActive;
    $data_User['employerId'] = $room->employerId;
    $data_User['messages'] = $room->messages;

    array_push($data,$data_User);
    }

        return  view('recruiter::recruiter/messages',compact('id','data'));
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

    public function sendMessages(Request $request)
    {

        $message = $request->message_data;
        $user = Auth::guard('recruiter')->user();
        $id = $user->id;
        $role = $user->role;
        $idWorker = $request->idWorker;

        // if i located the recruiter dash i should get the id of the employer from the request
        // we will use this id for now : "GWU000005"  

        $idEmployer = $request->idEmployer;
       
        $time = now()->toDateTimeString();
        event(new NewPrivateMessage($message , $idEmployer, 'GWU000005' ,   $idWorker,$role,$time));
        
    return true;
    }
}
