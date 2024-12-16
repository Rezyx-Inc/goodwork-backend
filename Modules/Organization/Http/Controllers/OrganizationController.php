<?php

namespace Modules\Organization\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Events\NewPrivateMessage;
use App\Events\NotificationMessage;
use DB;
use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Offer;
use Exception;
use App\Models\Job;
use App\Models\User;
use App\Models\Nurse;
use App\Models\NotificationMessage as NotificationMessageModel;
use App\Models\NotificationJobModel;
use App\Models\NotificationOfferModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;
use App\Mail\register;
use App\Mail\RegisterRecruiter;


class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('organization::index');
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

    public function get_private_messages(Request $request)
    {
        $idRecruiter = $request->query('recruiterId');
        $idWorker = $request->query('workerId');
        $page = $request->query('page', 1);

        $idOrganization = Auth::guard('organization')->user()->id;

        // Calculate the number of messages to skip
        $skip = ($page - 1) * 10;

        // we need to set the organization static since we dont have a relation between those three roles yet so we choose "GWU000005"

        $chat = DB::connection('mongodb')
            ->collection('chat')
            ->raw(function ($collection) use ($idOrganization, $idRecruiter, $idWorker, $skip) {
                return $collection
                    ->aggregate([
                        [
                            '$match' => [
                                'organizationId' => $idOrganization,
                                // 'organizationId'=> $idorganization,
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

        return $chat[0];
    }

    public function get_direct_private_messages(Request $request)
    {
        //return $request->all();
        $idRecruiter = $request->query('recruiterId');
        $idWorker = $request->query('workerId');
        $page = $request->query('page', 1);

        $idOrganization = Auth::guard('organization')->user()->id;

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
        $id = Auth::guard('organization')->user()->id;

        $rooms = DB::connection('mongodb')
            ->collection('chat')
            ->raw(function ($collection) use ($id) {
                return $collection
                    ->aggregate([
                        [
                            '$match' => [
                                //'recruiterId' => $id,
                                // for now until get the offer works
                                'organizationId' => $id,
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

        $data = [];
        $data_User = [];
        foreach ($rooms as $room) {
            //$user = User:select :where('id', $room->workerId);
            $user = User::select('first_name', 'last_name')
                ->where('id', $room->workerId)
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
            $data_User['workerId'] = $room->workerId;
            $data_User['isActive'] = $room->isActive;
            $data_User['recruiterId'] = $room->recruiterId;
            $data_User['messages'] = $room->messages;

            array_push($data, $data_User);
        }
        $worker = User::select('first_name', 'last_name')
            ->where('id', $idWorker)
            ->get()
            ->first();

        if ($worker) {
            $nameworker = $user->fullName;
        } else {
            // Handle the case where no user is found
            $nameworker = 'Default Name';
        }
        $worker = User::where('id', $idWorker)
        ->get()
        ->first();
        return view('organization::organization/messages', compact('idWorker', 'idRecruiter', 'direct', 'id', 'data', 'nameworker','worker'));

    }


    public function get_rooms(Request $request)
    {
        $idorganization = Auth::guard('organization')->user()->id;

        $rooms = DB::connection('mongodb')
            ->collection('chat')
            ->raw(function ($collection) use ($idorganization) {
                return $collection
                    ->aggregate([
                        [
                            '$match' => [
                                'organizationId' => $idorganization,
                            ],
                        ],
                        [
                            '$project' => [
                                'organizationId' => 1,
                                'organizationId' => 1,
                                'workerId' => 1,
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

        $users = [];
        $data = [];
        foreach ($rooms as $room) {
            $user = User::where('id', $room->workerId)
                ->where('role', 'NURSE')
                ->select('first_name', 'last_name')
                ->get();

            $data_User['fullName'] = $user[0]->last_name;
            $data_User['lastMessage'] = $this->timeAgo($room->lastMessage);
            $data_User['workerId'] = $room->workerId;
            $data_User['recruiterId'] = $room->recruiterId;
            $data_User['isActive'] = $room->isActive;
            $data_User['messages'] = $room->messages;

            array_push($data, $data_User);
        }

        return response()->json($data);
    }

    public function get_messages(Request $request)
    {

        $worker_id = $request->input('worker_id');
        $recruiter_id = $request->input('recruiter_id');
        $organization_id = Auth::guard('organization')->user()->id;



        if (isset($worker_id) && isset($recruiter_id)) {
            $nurse_user_id = Nurse::where('id', $worker_id)->first()->user_id;
            // Check if a room with the given worker_id and organization_id already exists
            $room = DB::connection('mongodb')->collection('chat')->where('workerId', $nurse_user_id)->where('organizationId', $organization_id)->first();

            // If the room doesn't exist, create a new one
            if (!$room) {
                DB::connection('mongodb')->collection('chat')->insert([
                    'workerId' => $nurse_user_id,
                    'recruiterId' => $recruiter_id,
                    'organizationId' => $organization_id,
                    'lastMessage' => $this->timeAgo(now()),
                    'isActive' => true,
                    'messages' => [],
                ]);

                // Call the get_private_messages function
                $request->query->set('workerId', $nurse_user_id);
                $request->query->set('organizationId', $organization_id); // Replace this with the actual organizationId
                return $this->get_direct_private_messages($request);
            }else{
                $request->query->set('workerId', $nurse_user_id);
                $request->query->set('recruiterId', $recruiter_id);
                return $this->get_direct_private_messages($request);
            }
        }

        $id = Auth::guard('organization')->user()->id;

        $rooms = DB::connection('mongodb')
            ->collection('chat')
            ->raw(function ($collection) use ($id) {
                return $collection
                    ->aggregate([
                        [
                            '$match' => [
                                //'organizationId' => $id,
                                // for now until get the offer works
                                'organizationId' => $id,
                            ],
                        ],
                        [
                            '$project' => [
                                'organizationId' => 1,
                                'workerId' => 1,
                                'organizationId' => 1,
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

        $data = [];
        $data_User = [];
        foreach ($rooms as $room) {
            //$user = User:select :where('id', $room->workerId);
            $user = User::select('first_name', 'last_name')
                ->where('id', $room->workerId)
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
            $data_User['workerId'] = $room->workerId;
            $data_User['isActive'] = $room->isActive;
            $data_User['recruiterId'] = $room->recruiterId;
            $data_User['messages'] = $room->messages;

            array_push($data, $data_User);
        }
        $direct = false;
        $nameworker = '';
        $idWorker = '';
        $idOrganization = '';
        // all worker details by worker id

        $worker = User::where('id', $idWorker)
            ->get()
            ->first();
        return view('organization::organization/messages', compact('id', 'data', 'direct', 'idWorker', 'idOrganization', 'nameworker','worker'));
    }

    public function timeAgo($time = null)
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
        elseif ($min <= 60) {
            if ($min == 1) {
                $string = 'one minute ago';
            } else {
                $string = "$min minutes ago";
            }
        }
        // Check for hours
        elseif ($hrs <= 24) {
            if ($hrs == 1) {
                $string = 'an hour ago';
            } else {
                $string = "$hrs hours ago";
            }
        }
        // Check for days
        elseif ($days <= 7) {
            if ($days == 1) {
                $string = 'Yesterday';
            } else {
                $string = "$days days ago";
            }
        }
        // Check for weeks
        elseif ($weeks <= 4.3) {
            if ($weeks == 1) {
                $string = 'a week ago';
            } else {
                $string = "$weeks weeks ago";
            }
        }
        // Check for months
        elseif ($mnths <= 12) {
            if ($mnths == 1) {
                $string = 'a month ago';
            } else {
                $string = "$mnths months ago";
            }
        }
        // Check for years
        else {
            if ($yrs == 1) {
                $string = 'one year ago';
            } else {
                $string = "$yrs years ago";
            }
        }
        return $string;
    }

    public function sendMessages(Request $request)
    {
        $message = $request->message_data;
        $user = Auth::guard('organization')->user();
        $id = $user->id;
        $role = $user->role;
        $idWorker = $request->idWorker;
        $type = $request->type;
        $fileName = $request->fileName;

        // if i located the organization dash i should get the id of the organization from the request
        // we will use this id for now : "GWU000005"

        $full_name = $user->first_name . ' ' . $user->last_name;


        $idRecruiter = $request->idRecruiter;

        $time = now()->toDateTimeString();
        //return response()->json(['success' => true, 'message' => $message, 'id' => $id, 'idRecruiter' => $idRecruiter, 'idWorker' => $idWorker, 'role' => $role, 'time' => $time, 'type' => $type, 'fileName' => $fileName]);
        event(new NewPrivateMessage($message, $id, $idRecruiter, $idWorker, $role, $time, $type, $fileName));
        event(new NotificationMessage($message, false, $time, $idWorker, $id, $full_name));

        return true;
    }

    // adding jobs :
    public function addJob()
    {
        return view('organization::layouts.addJob');
    }

    public function addJobStore(Request $request)
    {
         //return $request->all();
        // return $request->input('active');
        try {

            $created_by = Auth::guard('organization')->user()->id;
            // Validate the form data

            $active = $request->input('active');

            // $active = $activeRequest['active'];
            $validatedData = [];

            if ($active == 'false') {

                $validatedData = $request->validate([
                    'job_type' => 'nullable|string',
                    'job_id' => 'nullable|string',
                    'job_name' => 'nullable|string',
                    'job_city' => 'nullable|string',
                    'is_resume' => 'nullable|string',
                    'job_state' => 'nullable|string',
                    'weekly_pay' => 'nullable|numeric',
                    'preferred_specialty' => 'nullable|string',
                    'preferred_work_location' => 'nullable|string',
                    'description' => 'nullable|string',
                    'terms' => 'nullable|string',
                    'start_date' => 'nullable|date',
                    'hours_shift' => 'nullable|integer',
                    'hours_per_week' => 'nullable|integer',
                    'preferred_experience' => 'nullable|integer',
                    'facility_shift_cancelation_policy' => 'nullable|string',
                    'traveler_distance_from_facility' => 'nullable|string',
                    'clinical_setting' => 'nullable|string',
                    'Patient_ratio' => 'nullable|string',
                    'Unit' => 'nullable|string',
                    'scrub_color' => 'nullable|string',
                    'rto' => 'nullable|string',
                    'guaranteed_hours' => 'nullable|string',

                    'weeks_shift' => 'nullable|string',
                    'referral_bonus' => 'nullable|string',
                    'sign_on_bonus' => 'nullable|string',
                    'completion_bonus' => 'nullable|string',
                    'extension_bonus' => 'nullable|string',
                    'other_bonus' => 'nullable|string',
                    'actual_hourly_rate' => 'nullable|string',
                    'overtime' => 'nullable|string',
                    'holiday' => 'nullable|string',
                    'orientation_rate' => 'nullable|string',
                    'on_call' => 'nullable|string',
                    'on_call_rate' => 'nullable|string',
                    'call_back_rate' => 'nullable|string',
                    'weekly_non_taxable_amount' => 'nullable|string',
                    'profession' => 'nullable|string',

                    'Emr' => 'nullable|string',
                    'preferred_assignment_duration' => 'nullable|string',
                    'block_scheduling' => 'nullable|string',
                    'contract_termination_policy' => 'nullable|string',

                    'job_location' => 'nullable|string',
                    'vaccinations' => 'nullable|string',
                    'number_of_references' => 'nullable|integer',
                    'min_title_of_reference' => 'nullable|string',
                    'eligible_work_in_us' => 'nullable|boolean',
                    'recency_of_reference' => 'nullable|integer',
                    'certificate' => 'nullable|string',
                    'skills' => 'nullable|string',
                    'urgency' => 'nullable|string',
                    'facilitys_parent_system' => 'nullable|string',
                    'facility_name' => 'nullable|string',
                    'nurse_classification' => 'nullable|string',
                    'pay_frequency' => 'nullable|string',
                    'benefits' => 'nullable|string',
                    'feels_like_per_hour' => 'nullable|string',
                    'preferred_shift_duration' => 'nullable|string',
                    'as_soon_as' => 'nullable|integer',
                    'professional_state_licensure' => 'nullable|string',
                ]);

                $job = new Job();

                try {
                    $fields = [
                        'job_type', 'type', 'job_id', 'job_name', 'job_city', 'job_state', 'weekly_pay', 'preferred_specialty',
                        'active', 'description', 'start_date', 'hours_shift', 'hours_per_week', 'preferred_experience',
                        'facility_shift_cancelation_policy', 'traveler_distance_from_facility', 'clinical_setting', 'Patient_ratio',
                        'Unit', 'scrub_color', 'rto', 'guaranteed_hours', 'weeks_shift', 'referral_bonus', 'sign_on_bonus',
                        'completion_bonus', 'extension_bonus', 'other_bonus', 'actual_hourly_rate', 'overtime', 'holiday',
                        'orientation_rate', 'on_call', 'on_call_rate', 'call_back_rate', 'weekly_non_taxable_amount', 'profession',
                        'specialty', 'terms', 'preferred_assignment_duration', 'block_scheduling', 'contract_termination_policy',
                        'Emr', 'job_location', 'vaccinations', 'number_of_references', 'min_title_of_reference', 'eligible_work_in_us',
                        'recency_of_reference', 'certificate', 'preferred_shift_duration', 'skills', 'urgency', 'facilitys_parent_system',
                        'facility_name', 'nurse_classification', 'pay_frequency', 'benefits', 'feels_like_per_hour', 'as_soon_as',
                        'professional_state_licensure', 'is_resume'
                    ];
                
                    foreach ($fields as $field) {
                        if (isset($validatedData[$field])) {
                            $job->$field = $validatedData[$field];
                        }
                    }
                
                    $job->organization_id = $created_by;
                    $job->created_by = $created_by;
                    $job->active = false;
                    $job->is_open = false;

                    // Check if the is_resume bool is set
                    if (!isset($request->is_resume)){
                        $job->is_resume = false;
                    }
                
                    $job->save();
                } catch (Exception $e) {
                    return response()->json(['success' => false, 'message' => $e->getMessage()]);
                }
            } elseif ($active == "true") {
                //return request()->all();

                $validatedData = $request->validate([
                    
                    'job_type' => 'required|string',
                    'job_name' => 'nullable|string',
                    'job_id' => 'nullable|string',
                    'job_city' => 'required|string',
                    'is_resume' => 'nullable|string',
                    'job_state' => 'required|string',
                    'weekly_pay' => 'required|numeric',
                    'preferred_specialty' => 'required|string',
                    'preferred_work_location' => 'nullable|string',
                    'preferred_experience' => 'nullable|integer',
                    'description' => 'nullable|string',
                    'terms' => 'nullable|string',
                    'start_date' => 'nullable|date',
                    'hours_shift' => 'nullable|integer',
                    'facility_shift_cancelation_policy' => 'nullable|string',
                    'traveler_distance_from_facility' => 'nullable|string',
                    'clinical_setting' => 'nullable|string',
                    'Patient_ratio' => 'nullable|string',
                    'Unit' => 'nullable|string',
                    'scrub_color' => 'nullable|string',
                    'rto' => 'nullable|string',
                    'guaranteed_hours' => 'nullable|string',
                    'weeks_shift' => 'nullable|string',
                    'referral_bonus' => 'nullable|string',
                    'sign_on_bonus' => 'nullable|string',
                    'completion_bonus' => 'nullable|numeric',
                    'extension_bonus' => 'nullable|numeric',
                    'other_bonus' => 'nullable|numeric',
                    'actual_hourly_rate' => 'nullable|numeric',
                    'overtime' => 'nullable|string',
                    'holiday' => 'nullable|string',
                    'orientation_rate' => 'nullable|string',
                    'on_call' => 'nullable|string',
                    'on_call_rate' => 'nullable|string',
                    'call_back_rate' => 'nullable|string',
                    'weekly_non_taxable_amount' => 'nullable|string',
                    'profession' => 'nullable|string',
                    'Emr' => 'nullable|string',
                    'preferred_assignment_duration' => 'nullable|string',
                    'block_scheduling' => 'nullable|string',
                    'contract_termination_policy' => 'nullable|string',
                    'job_location' => 'nullable|string',
                    'vaccinations' => 'nullable|string',
                    'number_of_references' => 'nullable|integer',
                    'min_title_of_reference' => 'nullable|string',
                    'eligible_work_in_us' => 'nullable|boolean',
                    'recency_of_reference' => 'nullable|integer',
                    'certificate' => 'nullable|string',
                    'preferred_shift_duration' => 'nullable|string',
                    'skills' => 'nullable|string',
                    'urgency' => 'nullable|string',
                    'facilitys_parent_system' => 'nullable|string',
                    'facility_name' => 'nullable|string',
                    'nurse_classification' => 'nullable|string',
                    'pay_frequency' => 'nullable|string',
                    'benefits' => 'nullable|string',
                    'feels_like_per_hour' => 'nullable|string',
                    'as_soon_as' => 'nullable|integer',
                    'professional_state_licensure' => 'nullable|string',
                ]);

                $job = new Job();
                $fields = [
                    'job_type', 'type', 'job_name', 'job_id', 'job_city', 'job_state', 'weekly_pay', 'preferred_specialty',
                    'description', 'start_date', 'hours_shift', 'preferred_experience', 'facility_shift_cancelation_policy',
                    'traveler_distance_from_facility', 'clinical_setting', 'Patient_ratio', 'Unit', 'scrub_color', 'rto',
                    'guaranteed_hours', 'weeks_shift', 'referral_bonus', 'sign_on_bonus', 'completion_bonus', 'extension_bonus',
                    'other_bonus', 'actual_hourly_rate', 'overtime', 'holiday', 'orientation_rate', 'on_call', 'call_back_rate',
                    'weekly_non_taxable_amount', 'profession', 'specialty', 'terms', 'preferred_work_location',
                    'preferred_assignment_duration', 'block_scheduling', 'contract_termination_policy', 'Emr', 'on_call_rate',
                    'job_location', 'vaccinations', 'number_of_references', 'min_title_of_reference', 'eligible_work_in_us',
                    'recency_of_reference', 'certificate', 'preferred_shift_duration', 'skills', 'urgency', 'facilitys_parent_system',
                    'facility_name', 'nurse_classification', 'pay_frequency', 'benefits', 'feels_like_per_hour', 'as_soon_as',
                    'professional_state_licensure', 'is_resume'
                ];
                
                foreach ($fields as $field) {
                    if (isset($validatedData[$field])) {
                        $job->$field = $validatedData[$field];
                    }
                }
                
                $job->organization_id = $created_by;
                $job->created_by = $created_by;
                $job->active = true;
                $job->is_open = true;

                // Check if the is_resume bool is set
                if (!isset($request->is_resume)){
                    $job->is_resume = false;
                }
                
                $job->hours_per_week = $job->weeks_shift * $job->hours_shift;
                $job->weekly_taxable_amount = $job->hours_per_week * $job->actual_hourly_rate;
                $job->organization_weekly_amount = $job->weekly_taxable_amount + $job->weekly_non_taxable_amount;
                $job->total_organization_amount = ($job->preferred_assignment_duration * $job->organization_weekly_amount) + ($job->sign_on_bonus + $job->completion_bonus);
                $job->goodwork_weekly_amount = ($job->organization_weekly_amount) * 0.05;
                $job->total_goodwork_amount = $job->goodwork_weekly_amount * $job->preferred_assignment_duration;
                $job->total_contract_amount = $job->total_goodwork_amount + $job->total_organization_amount;
                
                // Save the job data to the database
                $job->save();

                $AssignmentResponse = Http::post('http://localhost:4545/organizations/assignUpNextRecruiter', [
                    'id' => $job->organization_id,
                ]);

                if ($AssignmentResponse->status() == 200) {
                    $recruiterId = $AssignmentResponse->body();
                    $job->recruiter_id = $recruiterId;
                    $job->save();
                } 

            } else {

                //return response()->json(['success' => false, 'message' => $active]);
                //return redirect()->route('organization-opportunities-manager')->with('error', 'Please Try Again Later');
                return response()->json(['success' => false, 'message' => $active]);
            }

            //return response()->json(['success' => true, 'message' => $request->all()]);
            // Create a new Job instance with the validated data


            //return response()->json(['success' => true, 'message' => 'Job added successfully!']);

            // Redirect back to the add job form with a success message
            return redirect()->route('organization-opportunities-manager')->with('success', 'Job added successfully!');

            // return response()->json(['success' => true, 'message' => 'Job added successfully!']);
        } catch (QueryException $e) {
            // Log the error
            Log::error('Error saving job: ' . $e->getMessage());

            // Handle the error gracefully - display a generic error message or redirect with an error status
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
            return redirect()->route('organization-opportunities-manager')->with('error', 'Failed to add job. Please try again later.');
            // return response()->json(['success' => false, 'message' =>$e->getMessage()]);
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error('Exception: ' . $e->getMessage());

            // Display a generic error message or redirect with an error status
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
            return redirect()->route('organization-opportunities-manager')->with('error', 'Try again later');
            //return response()->json(['success' => false, 'message' => $request->input('') ]);
        }
    }

    public function saveJobAsDraft(Request $request)
    {
        try {
            $facility_id = Auth::guard('organization')->user()->facility_id;
            // Validate the form data
            $validatedData = $request->validate([
                    'job_type' => 'nullable|string',
                    'job_name' => 'nullable|string',
                    'job_id' => 'nullable|string',
                    'job_city' => 'nullable|string',
                    'is_resume' => 'nullable|string',
                    'job_state' => 'nullable|string',
                    'weekly_pay' => 'nullable|numeric',
                    'preferred_specialty' => 'nullable|string',
                    'preferred_work_location' => 'nullable|string',
                    'preferred_experience' => 'nullable|integer',
                    'description' => 'nullable|string',
                    'terms' => 'nullable|string',
                    'start_date' => 'nullable|date',
                    'hours_shift' => 'nullable|integer',
                    'facility_shift_cancelation_policy' => 'nullable|string',
                    'traveler_distance_from_facility' => 'nullable|string',
                    'clinical_setting' => 'nullable|string',
                    'Patient_ratio' => 'nullable|string',
                    'Unit' => 'nullable|string',
                    'scrub_color' => 'nullable|string',
                    'rto' => 'nullable|string',
                    'guaranteed_hours' => 'nullable|string',
                    'weeks_shift' => 'nullable|string',
                    'referral_bonus' => 'nullable|string',
                    'sign_on_bonus' => 'nullable|string',
                    'completion_bonus' => 'nullable|numeric',
                    'extension_bonus' => 'nullable|numeric',
                    'other_bonus' => 'nullable|numeric',
                    'actual_hourly_rate' => 'nullable|numeric',
                    'overtime' => 'nullable|string',
                    'holiday' => 'nullable|string',
                    'orientation_rate' => 'nullable|string',
                    'on_call' => 'nullable|string',
                    'on_call_rate' => 'nullable|string',
                    'call_back_rate' => 'nullable|string',
                    'weekly_non_taxable_amount' => 'nullable|string',
                    'profession' => 'nullable|string',
                    'Emr' => 'nullable|string',
                    'preferred_assignment_duration' => 'nullable|string',
                    'block_scheduling' => 'nullable|string',
                    'contract_termination_policy' => 'nullable|string',
                    'job_location' => 'nullable|string',
                    'vaccinations' => 'nullable|string',
                    'number_of_references' => 'nullable|integer',
                    'min_title_of_reference' => 'nullable|string',
                    'eligible_work_in_us' => 'nullable|boolean',
                    'recency_of_reference' => 'nullable|integer',
                    'certificate' => 'nullable|string',
                    'preferred_shift_duration' => 'nullable|string',
                    'skills' => 'nullable|string',
                    'urgency' => 'nullable|string',
                    'facilitys_parent_system' => 'nullable|string',
                    'facility_name' => 'nullable|string',
                    'nurse_classification' => 'nullable|string',
                    'pay_frequency' => 'nullable|string',
                    'benefits' => 'nullable|string',
                    'feels_like_per_hour' => 'nullable|string',
                    'as_soon_as' => 'nullable|integer',
                    'active' => '0',
                    'professional_state_licensure' => 'nullable|string',

            ]);

            // Create a new Job instance with the validated data
            $job = new Job();
            $fields = [
                'job_type', 'job_id', 'job_name', 'job_city', 'job_state', 'weekly_pay', 'preferred_specialty',
                'preferred_work_location', 'description', 'terms', 'start_date', 'hours_shift',
                'facility_shift_cancelation_policy', 'traveler_distance_from_facility', 'clinical_setting',
                'Patient_ratio', 'Unit', 'scrub_color', 'rto', 'guaranteed_hours', 'weeks_shift',
                'referral_bonus', 'sign_on_bonus', 'completion_bonus', 'extension_bonus', 'other_bonus',
                'actual_hourly_rate', 'overtime', 'holiday', 'orientation_rate', 'on_call', 'on_call_rate',
                'call_back_rate', 'weekly_non_taxable_amount', 'profession', 'Emr', 'preferred_assignment_duration',
                'block_scheduling', 'contract_termination_policy', 'job_location', 'vaccinations',
                'number_of_references', 'min_title_of_reference', 'eligible_work_in_us', 'recency_of_reference',
                'certificate', 'preferred_shift_duration', 'skills', 'urgency', 'facilitys_parent_system',
                'facility_name', 'nurse_classification', 'pay_frequency', 'benefits', 'feels_like_per_hour',
                'as_soon_as', 'professional_state_licensure','is_resume'
            ];
            
            foreach ($fields as $field) {
                if (isset($validatedData[$field])) {
                    $job->$field = $validatedData[$field];
                }
            }
            
            $job->organization_id = Auth::guard('organization')->user()->id;
            $job->created_by = Auth::guard('organization')->user()->id;
            $job->active = false;
            $job->is_open = false;
            
            // Check if the is_resume bool is set
            if (!isset($request->is_resume)){
                $job->is_resume = false;
            }

            // Save the job data to the database
            $job->save();

            // Redirect back to the add job form with a success message
            return redirect()->route('organization-opportunities-manager')->with('success', 'Job added successfully!');

            // return response()->json(['success' => true, 'message' => 'Job added successfully!']);
        } catch (QueryException $e) {
            // Log the error
            Log::error('Error saving job: ' . $e->getMessage());

            // Handle the error gracefully - display a generic error message or redirect with an error status
            return redirect()->route('organization-opportunities-manager')->with('error', 'Failed to add job. Please try again later.');
            // return response()->json(['success' => false, 'message' =>$e->getMessage()]);
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error('Exception: ' . $e->getMessage());

            // Display a generic error message or redirect with an error status

            return redirect()->route('organization-opportunities-manager')->with('error', $e->getMessage());
            // return response()->json(['success' => false, 'message' =>  $e->getMessage()]);
        }
    }


    function get_job_to_edit(Request $request)
    {

        try {
            $validated = $request->validate([
                'id' => 'required',
            ]);

            $job_id = $request->id;
            $job = Job::find($job_id);

            if ($job === null) {
                return response()->json(['error' => "Job not found"], 404);
            }

            return response()->json($job);
        } catch (\Exception $e) {
            return response()->json(['error' => "An error occurred: " . $e->getMessage()], 500);
        }
    }

    function edit_job(Request $request)
    {
        //return $request->all();
        $created_by = Auth::guard('organization')->user()->id;
        // Validate the form data

        $active = $request->input('active');

        // $active = $activeRequest['active'];
        $validatedData = [];
        try {
            $validatedData = $request->validate([
                'job_type' => 'required|string',
                'job_name' => 'required|string',
                'job_id' => 'nullable|string',
                'job_city' => 'required|string',
                'job_state' => 'required|string',
                'is_resume' => 'nullable|string',
                'weekly_pay' => 'required|numeric',
                'preferred_specialty' => 'required|string',
                'preferred_work_location' => 'nullable|string',
                'description' => 'nullable|string',
                'terms' => 'nullable|string',
                'start_date' => 'nullable|date',
                'hours_shift' => 'nullable|integer',
                'facility_shift_cancelation_policy' => 'nullable|string',
                'traveler_distance_from_facility' => 'nullable|string',
                'clinical_setting' => 'nullable|string',
                'Patient_ratio' => 'nullable|string',
                'Unit' => 'nullable|string',
                'scrub_color' => 'nullable|string',
                'preferred_experience' => 'nullable|integer',
                'rto' => 'nullable|string',
                'guaranteed_hours' => 'nullable|string',
                'weeks_shift' => 'nullable|string',
                'referral_bonus' => 'nullable|string',
                'sign_on_bonus' => 'nullable|string',
                'completion_bonus' => 'nullable|numeric',
                'extension_bonus' => 'nullable|numeric',
                'other_bonus' => 'nullable|numeric',
                'actual_hourly_rate' => 'nullable|numeric',
                'overtime' => 'nullable|string',
                'holiday' => 'nullable|string',
                'orientation_rate' => 'nullable|string',
                'on_call' => 'nullable|string',
                'call_back_rate' => 'nullable|string',
                'weekly_non_taxable_amount' => 'nullable|string',
                'profession' => 'nullable|string',
                'Emr' => 'nullable|string',
                'preferred_assignment_duration' => 'nullable|string',
                'block_scheduling' => 'nullable|string',
                'contract_termination_policy' => 'nullable|string',
                'on_call_rate' => 'nullable|string',
                'job_location' => 'nullable|string',
                'vaccinations' => 'nullable|string',
                'number_of_references' => 'nullable|integer',
                'min_title_of_reference' => 'nullable|string',
                'eligible_work_in_us' => 'nullable|boolean',
                'recency_of_reference' => 'nullable|integer',
                'certificate' => 'nullable|string',
                'preferred_shift_duration' => 'nullable|string',
                'skills' => 'nullable|string',
                'urgency' => 'nullable|string',
                'facilitys_parent_system' => 'nullable|string',
                'facility_name' => 'nullable|string',
                'nurse_classification' => 'nullable|string',
                'pay_frequency' => 'nullable|string',
                'benefits' => 'nullable|string',
                'feels_like_per_hour' => 'nullable|string',
                'as_soon_as' => 'nullable|integer',
                'professional_state_licensure' => 'nullable|string',

            ]);

            $job = Job::find($request->id);

            if ($job === null) {
                return response()->json(['error' => "Job not found"], 404);
            }

            $fields = [
                'job_type', 'type', 'job_name', 'job_id', 'job_city', 'job_state', 'weekly_pay', 'preferred_experience',
                'preferred_specialty', 'description', 'start_date', 'hours_shift', 'facility_shift_cancelation_policy',
                'traveler_distance_from_facility', 'clinical_setting', 'Patient_ratio', 'Unit', 'scrub_color', 'rto',
                'guaranteed_hours', 'weeks_shift', 'referral_bonus', 'sign_on_bonus', 'completion_bonus', 'extension_bonus',
                'other_bonus', 'actual_hourly_rate', 'overtime', 'holiday', 'orientation_rate', 'on_call', 'call_back_rate',
                'weekly_non_taxable_amount', 'profession', 'specialty', 'terms', 'preferred_work_location',
                'preferred_assignment_duration', 'block_scheduling', 'contract_termination_policy', 'Emr', 'on_call_rate',
                'job_location', 'vaccinations', 'number_of_references', 'min_title_of_reference', 'eligible_work_in_us',
                'recency_of_reference', 'certificate', 'preferred_shift_duration', 'skills', 'urgency', 'facilitys_parent_system',
                'facility_name', 'nurse_classification', 'pay_frequency', 'benefits', 'feels_like_per_hour', 'as_soon_as' , 'professional_state_licensure','is_resume'
            ];
            
            foreach ($fields as $field) {
                if (isset($validatedData[$field])) {
                    $job->$field = $validatedData[$field];
                }
            }
            
            $job->organization_id = $created_by;
            $job->created_by = $created_by;
            $job->active = true;
            $job->is_open = true;
            
            // Check if the is_resume bool is set
            if (!isset($request->is_resume)){
                $job->is_resume = false;
            }
            $job->hours_per_week = $job->weeks_shift * $job->hours_shift;
            $job->weekly_taxable_amount = $job->hours_per_week * $job->actual_hourly_rate;
            $job->organization_weekly_amount = $job->weekly_taxable_amount + $job->weekly_non_taxable_amount;
            $job->total_organization_amount = ($job->preferred_assignment_duration * $job->organization_weekly_amount) + ($job->sign_on_bonus + $job->completion_bonus);
            $job->goodwork_weekly_amount = ($job->organization_weekly_amount) * 0.05;
            $job->total_goodwork_amount = $job->goodwork_weekly_amount * $job->preferred_assignment_duration;
            $job->total_contract_amount = $job->total_goodwork_amount + $job->total_organization_amount;
            
            // update the job data to the database
            $job->save();

            return redirect()->route('organization-opportunities-manager')->with('success', 'Job updated successfully');

            return response()->json(['status' => 'success', 'message' => 'Job updated successfully']);

            //return response()->json($job);
        } catch (\Exception $e) {
            return response()->json(['error' => "An error occurred: " . $e->getMessage()], 500);
        }
    }


    public function read_organization_offer_notification(Request $request)
    {
        $sender = $request->senderId;
        $offerId = $request->offerId;
        $user = Auth::guard('organization')->user();
        $receiver = $user->id;

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

    public function read_organization_message_notification(Request $request)
    {
        $sender = $request->sender;
        $receiver = Auth::guard('organization')->user()->id;
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

    public function read_organization_job_notification(Request $request)
    {
        $sender = $request->sender;
        $job_id = $request->jobId;
        $receiver = Auth::guard('organization')->user()->id;

        try {
            $updateResult = NotificationJobModel::raw()->updateMany(
                [
                    'receiver' => $receiver,
                    'all_jobs_notifs.sender' => $sender
                ],
                [
                    '$set' => [
                        'all_jobs_notifs.$[elem].notifs_of_one_sender.$[notif].seen' => true
                    ]
                ],
                [
                    'arrayFilters' => [
                        ['elem.sender' => $sender],
                        ['notif.jobId' => $job_id] // Target the specific jobId within notifs_of_one_sender
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


    public function keys()
    {
        $id = Auth::guard('organization')->user()->id;
        $keys = DB::table('api_keys')->where('name', $id)->get();
        return view('organization::organization/organization_api_keys', compact('keys'));
    }


   
public function recruiters_management()
{
    $orgId = Auth::guard('organization')->user()->id;

    $scriptResponse = Http::get('http://localhost:4545/organizations/getRecruiters/' . $orgId);

   

    $responseData = $scriptResponse->json();
    if(isset($responseData)) {
    
    $ids = array_map(function($recruiter) {
        return $recruiter['id'];
    }, $responseData);

    $recruiters = User::whereIn('id', $ids)->where('role', 'RECRUITER')->get();
    } else {
        $recruiters = [];
    }
    return view('organization::organization.organization_recruiters_management', compact('recruiters'));
}

    public function delete_recruiter(Request $request)
    {
        
        $recruiter_id = $request->recruiter_id;
        $recruiter = User::find($recruiter_id);

        if ($recruiter === null) {
            return response()->json(['success' => false, 'message' => 'Recruiter not found']);
        }

        $orgId = Auth::guard('organization')->user()->id;

        $scriptResponse = Http::post('http://localhost:4545/organizations/deleteRecruiter/' . $orgId, [
            'id' => $recruiter_id,
        ]);

        //return $scriptResponse;

        if ($scriptResponse->failed()) {
            $data = [];
            $data['msg'] = "error 99";
            $data['success'] = false;
            return response()->json($data);
        }

        // Clear `recruiter_id` in jobs associated with this recruiter and organization
        Job::where('organization_id', $orgId)->where('recruiter_id', $recruiter_id)->update(['recruiter_id' => null]);


        // Check if `organization_name` is not empty
        if (!empty($recruiter->organization_name)) {
            $recruiter->organization_name = null;
            $recruiter->save();
            return response()->json(['success' => true, 'message' => 'Recruiter organization_name cleared']);
        }

        if ($recruiter === null) {
            return response()->json(['success' => false, 'message' => 'Recruiter not found']);
        }
        $recruiter->delete();
        return response()->json(['success' => true, 'message' => 'Recruiter deleted successfully']);
    }

    public function getapikey(Request $request)
    {

        $case = $request->input('action');
        if ($case == 'add') {


            $id = Auth::guard('organization')->user()->id;

            // Check if there are already 3 or more records with the same name
            $count = DB::table('api_keys')->where('name', $id)->count();
            if ($count >= 3) {
                return redirect()->route('organization-keys')->with('error', 'You had already 3 keys.');
            }
            // Generate a random string for the token
            $randomString = bin2hex(random_bytes(16)); // 16 bytes = 128 bits

            // Create the token by hashing the random string with the secret key
            $token = hash_hmac('sha256', $randomString, $id);

            // Optionally, you can encode the token in base64 for a shorter representation
            $base64Token = base64_encode($token);

            $data = ['name' => $id, 'key' => $base64Token, 'active' => '1'];
            DB::table('api_keys')->insert($data);

            return redirect()->route('organization-keys')->with('success', 'Key added successfully!');

        } else if ($case == 'save') {

            $id = Auth::guard('organization')->user()->id;
            $keys = DB::table('api_keys')->where('name', $id)->pluck('id')->toArray();

            $checkboxes = $request->input('keyCheckboxes');

            if ($checkboxes) {
                foreach ($checkboxes as $key => $value) {
                    DB::table('api_keys')
                        ->where('id', $value)
                        ->update([
                            'active' => '1',
                        ]);
                }

            }

            if ($checkboxes != null) {
                $uncheckedCheckboxes = array_diff($keys, $checkboxes);

                if ($uncheckedCheckboxes) {
                    foreach ($uncheckedCheckboxes as $key => $value) {
                        DB::table('api_keys')
                            ->where('id', $value)
                            ->update([
                                'active' => '0',
                            ]);
                    }

                }
            } else {
                DB::table('api_keys')
                    ->where('name', $id)
                    ->update([
                        'active' => '0',
                    ]);
            }

            return redirect()->route('organization-keys')->with('success', 'Saved');

        }

    }

    public function deleteapikey(Request $request){

        $valuekeyid =  $request->input('delete_key');
        DB::table('api_keys')
            ->where('id', $valuekeyid)
            ->delete();
        return redirect()->route('organization-keys')->with('success', 'Deleted successfully');

    }

    public function recruiter_registration(Request $request)
    {
        try{
                //$orgId = Auth::guard('organization')->user()->id;
                $orgId = Auth::guard('organization')->user();

                
                $validator = Validator::make($request->all(), [
                    'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
                    'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
                    'mobile' => ['nullable','regex:/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/'],
                    'email' => 'email:rfc,dns'
                ]);

                if ($validator->fails()) {
                    $data = [];
                    $data['msg'] =$validator->errors()->first();;
                    $data['success'] = false;
                    return response()->json($data);

                }else{
                    $recruiterAlreadyInOrg = User::where('email', $request->email)->whereNotNull('organization_name')->first();
                                    
                    $check = User::where(['email'=>$request->email])->whereNull('deleted_at')->first();

                    // if (!empty($check)) {
                    //     $data = [];
                    //     $data['msg'] ='Already exist.';
                    //     $data['success'] = false;
                    //     return response()->json($data);
                    // }

                    if (!empty($recruiterAlreadyInOrg)) {
                        return response()->json([
                            'msg' => 'Recruiter already in Organization',
                            'code' => 99,
                            'success' => false,
                        ]);
                    } elseif (!empty($check)) {
                        return response()->json([
                            'msg' => 'Already exist.',
                            'code' => 88,
                            'success' => false,
                        ]);
                    }

                    $response = [];
                    $model = User::create([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'mobile' => $request->mobile,
                        'email' => $request->email,
                        'organization_name' => $orgId->organization_name,
                        'user_name' => $request->email,
                        'active' => '1',
                        'role' => 'RECRUITER',
                    ]);

                    $scriptResponse = Http::post('http://localhost:4545/organizations/addRecruiter/' . $orgId->id, [
                        'id' => $model->id,
                        'worksAssigned' => 0,
                        'upNext' => true,
                    ]);

                    //return $scriptResponse;

                    if ($scriptResponse->failed()) {
                        $data = [];
                        $data['msg'] = "Unexpected error, please contact support@goodwork.world";
                        $data['success'] = false;
                        return response()->json($data);
                    }

                    $response['msg'] = 'Registered successfully!';
                    $response['success'] = true;

                    // sending mail infromation
                    $email_data = ['name' => $model->first_name . ' ' . $model->last_name, 'organization' => $orgId->organization_name,'subject' => 'Registration'];
                    Mail::to($model->email)->send(new RegisterRecruiter($email_data));
                    
                    return response()->json($response);
                }

        }catch(\Exception $e){
            $data = [];
            //$data['msg'] = $e->getMessage();
            $data['msg'] ='We encountered an error. Please try again later.';
            $data['success'] = false;
            return response()->json($data);
        }
    }

    
    public function recruiter_edit(Request $request)
    {
        try {
            $orgId = Auth::guard('organization')->user()->id;
        
            $validator = Validator::make($request->all(), [
                'recruiter_id' => 'required|exists:users,id',
                'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
                'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
                'mobile' => ['nullable','regex:/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/'],
                
                'email' => 'required|email:rfc,dns'
            ]);
        
            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->first(),
                    'success' => false
                ]);
            }
        
            $recruiter = User::where('id', $request->recruiter_id)->where('role', 'RECRUITER')->first();
        
            if (!$recruiter) {
                return response()->json([
                    'msg' => 'Recruiter not found.',
                    'success' => false
                ]);
            }
        
            $recruiter->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile' => $request->mobile,
                'email' => $request->email,
            ]);
        
            return response()->json([
                'msg' => 'Recruiter updated successfully!',
                'success' => true
            ]);
        
        } catch (\Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
                'success' => false
            ]);
        }
    }

    public function get_recruiter_data(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:users,id',
            ]);
        
            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->first(),
                    'success' => false
                ]);
            }
        
            $recruiter = User::where('id', $request->id)->where('role', 'RECRUITER')->first();

            if (!$recruiter) {
                return response()->json([
                    'msg' => 'Recruiter not found.',
                    'success' => false
                ]);
            }
        
            return response()->json([
                'msg' => 'Recruiter data retrieved successfully!',
                'success' => true,
                'data' => $recruiter
            ]);
        
        } catch (\Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
                'success' => false
            ]);
        }
    }

    public function get_preferences(Request $request){
        try{    

            // $columns = Schema::getColumnListing('jobs');
            // $columns = array_diff($columns, ['id','import_id','facility_id','created_at','updated_at','created_by','deleted_at','recruiter_id','organization_id']);
            $orgId = Auth::guard('organization')->user()->id;
            $columns = Http::get('http://localhost:4545/organizations/getFieldsRules');
            $requiredFields = Http::post('http://localhost:4545/organizations/get-preferences', [
                'id' => $orgId,
            ]);
            $requiredFields = $requiredFields->json();
            $columns = $columns->json();

            $columns = $columns[0]["ruleFields"];
            //return response()->json(['columns'=>$columns]);
            //return $columns;
            return view('organization::organization.organization_rules_management', compact('columns','requiredFields'));

        }catch(\Exception $ex){

            return response()->json([
                'msg' => $ex->getMessage(),
                'success' => false
            ]);

        }
    }


    public function add_preferences(Request $request){
        try{

            $orgId = Auth::guard('organization')->user()->id;
            $response = Http::post('http://localhost:4545/organizations/add-preferences', [
                'id' => $orgId,
                'preferences' => $request->preferences,
            ]);
            return $response;

        }catch(\Exeption $ex ){

            return response()->json([
                'msg' => $e->getMessage(),
                'success' => false
            ]);

        }
    }

    public function assign_recruiter_to_job(Request $request)
    {
        try{

            $jobId = $request->job_id;
            $recruiterId = $request->recruiter_id;
            $job = Job::where('id', $jobId)->first();
            $orgId = Auth::guard('organization')->user()->id;

            $AssignmentResponse = Http::post('http://localhost:4545/organizations/manuelRecruiterAssignment/' . $orgId, [
                'id' => $recruiterId,
            ]);

            if ($AssignmentResponse->status() == 200) {
                $offers = Offer::where('job_id', $jobId)->get();

                foreach ($offers as $offer) {
                    $offer->recruiter_id = $recruiterId;
                    $offer->save();
                }

                $job->recruiter_id = $recruiterId;
                $job->save();

            } else {

                return response()->json(['error' => "An error occurred: " . $AssignmentResponse->body()], 500);

            }

            return response()->json(['success' => true, 'message' => 'Recruiter assigned successfully']);

        }catch(\Exception $e){

            return response()->json(['error' => "An error occurred: " . $e->getMessage()], 500);

        }
    }

}
