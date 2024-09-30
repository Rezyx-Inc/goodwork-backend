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
        $idOrganization = $request->query('organizationId');
        $idWorker = $request->query('workerId');
        $page = $request->query('page', 1);

        $idorganization = Auth::guard('organization')->user()->id;

        // Calculate the number of messages to skip
        $skip = ($page - 1) * 10;

        // we need to set the organization static since we dont have a relation between those three roles yet so we choose "GWU000005"

        $chat = DB::connection('mongodb')
            ->collection('chat')
            ->raw(function ($collection) use ($idOrganization, $idorganization, $idWorker, $skip) {
                return $collection
                    ->aggregate([
                        [
                            '$match' => [
                                'organizationId' => $idOrganization,
                                // 'organizationId'=> $idorganization,
                                // for now until get the offer works
                                'organizationId' => $idorganization,

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
        $idOrganization = $request->query('organizationId');
        $idWorker = $request->query('workerId');
        $page = $request->query('page', 1);

        $idorganization = Auth::guard('organization')->user()->id;

        // Calculate the number of messages to skip
        $skip = ($page - 1) * 10;

        // we need to set the organization static since we dont have a relation between those three roles yet so we choose "GWU000005"

        $chat = DB::connection('mongodb')
            ->collection('chat')
            ->raw(function ($collection) use ($idOrganization, $idorganization, $idWorker, $skip) {
                return $collection
                    ->aggregate([
                        [
                            '$match' => [
                                'organizationId' => $idOrganization,
                                // 'organizationId'=> $idorganization,
                                // for now until get the offer works
                                'organizationId' => $idorganization,

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
            $data_User['organizationId'] = $room->organizationId;
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
        return view('organization::organization/messages', compact('idWorker', 'idOrganization', 'direct', 'id', 'data', 'nameworker'));

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
            $data_User['organizationId'] = $room->organizationId;
            $data_User['isActive'] = $room->isActive;
            $data_User['messages'] = $room->messages;

            array_push($data, $data_User);
        }

        return response()->json($data);
    }

    public function get_messages(Request $request)
    {

        $worker_id = $request->input('worker_id');
        $organization_id = Auth::guard('organization')->user()->id;



        if (isset($worker_id)) {
            $nurse_user_id = Nurse::where('id', $worker_id)->first()->user_id;
            // Check if a room with the given worker_id and organization_id already exists
            $room = DB::connection('mongodb')->collection('chat')->where('workerId', $nurse_user_id)->where('organizationId', $organization_id)->first();

            // If the room doesn't exist, create a new one
            if (!$room) {
                DB::connection('mongodb')->collection('chat')->insert([
                    'workerId' => $nurse_user_id,
                    'organizationId' => $organization_id,
                    'organizationId' => $organization_id, // Replace this with the actual organizationId
                    'lastMessage' => $this->timeAgo(now()),
                    'isActive' => true,
                    'messages' => [],
                ]);

                // Call the get_private_messages function
                $request->query->set('workerId', $nurse_user_id);
                $request->query->set('organizationId', $organization_id); // Replace this with the actual organizationId
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
            $data_User['organizationId'] = $room->organizationId;
            $data_User['messages'] = $room->messages;

            array_push($data, $data_User);
        }
        $direct = false;
        $nameworker = '';
        $idWorker = '';
        $idOrganization = '';
        return view('organization::organization/messages', compact('id', 'data', 'direct', 'idWorker', 'idOrganization', 'nameworker'));
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


        $idOrganization = $request->idOrganization;

        $time = now()->toDateTimeString();
        event(new NewPrivateMessage($message, $idOrganization, $id, $idWorker, $role, $time, $type, $fileName));
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
         return $request->all();
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
                    'job_name' => 'nullable|string',
                    'job_city' => 'nullable|string',
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
                    'preferred_shift_duration' => 'nullable|string'
                ]);

                $job = new Job();

                try {

                    if (isset($validatedData['job_type'])) {
                        $job->job_type = $validatedData['job_type'];
                    }
                    if (isset($validatedData['job_type'])) {
                        $job->type = $validatedData['job_type'];
                    }
                    if (isset($validatedData['job_name'])) {
                        $job->job_name = $validatedData['job_name'];
                    }
                    if (isset($validatedData['job_city'])) {
                        $job->job_city = $validatedData['job_city'];
                    }
                    if (isset($validatedData['job_state'])) {
                        $job->job_state = $validatedData['job_state'];
                    }
                    if (isset($validatedData['weekly_pay'])) {
                        $job->weekly_pay = $validatedData['weekly_pay'];
                    }
                    if (isset($validatedData['preferred_specialty'])) {
                        $job->preferred_specialty = $validatedData['preferred_specialty'];
                    }
                    if (isset($validatedData['active'])) {
                        $job->active = $validatedData['active'];
                    }
                    if (isset($validatedData['description'])) {
                        $job->description = $validatedData['description'];
                    }
                    if (isset($validatedData['start_date'])) {
                        $job->start_date = $validatedData['start_date'];
                    }
                    if (isset($validatedData['hours_shift'])) {
                        $job->hours_shift = $validatedData['hours_shift'];
                    }
                    if (isset($validatedData['hours_per_week'])) {
                        $job->hours_per_week = $validatedData['hours_per_week'];
                    }
                    if (isset($validatedData['preferred_experience'])) {
                        $job->preferred_experience = $validatedData['preferred_experience'];
                    }
                    if (isset($validatedData['facility_shift_cancelation_policy'])) {
                        $job->facility_shift_cancelation_policy = $validatedData['facility_shift_cancelation_policy'];
                    }
                    if (isset($validatedData['traveler_distance_from_facility'])) {
                        $job->traveler_distance_from_facility = $validatedData['traveler_distance_from_facility'];
                    }
                    if (isset($validatedData['clinical_setting'])) {
                        $job->clinical_setting = $validatedData['clinical_setting'];
                    }
                    if (isset($validatedData['Patient_ratio'])) {
                        $job->Patient_ratio = $validatedData['Patient_ratio'];
                    }
                    if (isset($validatedData['Unit'])) {
                        $job->Unit = $validatedData['Unit'];
                    }
                    if (isset($validatedData['scrub_color'])) {
                        $job->scrub_color = $validatedData['scrub_color'];
                    }
                    if (isset($validatedData['rto'])) {
                        $job->rto = $validatedData['rto'];
                    }
                    if (isset($validatedData['guaranteed_hours'])) {
                        $job->guaranteed_hours = $validatedData['guaranteed_hours'];
                    }
                    if (isset($validatedData['hours_per_week'])) {
                        $job->hours_per_week = $validatedData['hours_per_week'];
                    }
                    if (isset($validatedData['hours_shift'])) {
                        $job->hours_shift = $validatedData['hours_shift'];
                    }
                    if (isset($validatedData['weeks_shift'])) {
                        $job->weeks_shift = $validatedData['weeks_shift'];
                    }
                    if (isset($validatedData['referral_bonus'])) {
                        $job->referral_bonus = $validatedData['referral_bonus'];
                    }
                    if (isset($validatedData['sign_on_bonus'])) {
                        $job->sign_on_bonus = $validatedData['sign_on_bonus'];
                    }
                    if (isset($validatedData['completion_bonus'])) {
                        $job->completion_bonus = $validatedData['completion_bonus'];
                    }
                    if (isset($validatedData['extension_bonus'])) {
                        $job->extension_bonus = $validatedData['extension_bonus'];
                    }
                    if (isset($validatedData['other_bonus'])) {
                        $job->other_bonus = $validatedData['other_bonus'];
                    }
                    if (isset($validatedData['actual_hourly_rate'])) {
                        $job->actual_hourly_rate = $validatedData['actual_hourly_rate'];
                    }
                    if (isset($validatedData['overtime'])) {
                        $job->overtime = $validatedData['overtime'];
                    }
                    if (isset($validatedData['holiday'])) {
                        $job->holiday = $validatedData['holiday'];
                    }
                    if (isset($validatedData['orientation_rate'])) {
                        $job->orientation_rate = $validatedData['orientation_rate'];
                    }
                    if (isset($validatedData['on_call'])) {
                        $job->on_call = $validatedData['on_call'];
                    }
                    if (isset($validatedData['on_call_rate'])) {
                        $job->on_call_rate = $validatedData['on_call_rate'];
                    }
                    if (isset($validatedData['call_back_rate'])) {
                        $job->call_back_rate = $validatedData['call_back_rate'];
                    }
                    if (isset($validatedData['weekly_non_taxable_amount'])) {
                        $job->weekly_non_taxable_amount = $validatedData['weekly_non_taxable_amount'];
                    }
                    if (isset($validatedData['profession'])) {
                        $job->profession = $validatedData['profession'];
                    }
                    if (isset($validatedData['preferred_specialty'])) {
                        $job->specialty = $validatedData['preferred_specialty'];
                    }
                    if (isset($validatedData['terms'])) {
                        $job->terms = $validatedData['terms'];
                    }
                    if (isset($validatedData['preferred_assignment_duration'])) {
                        $job->preferred_assignment_duration = $validatedData['preferred_assignment_duration'];
                    }
                    if (isset($validatedData['block_scheduling'])) {
                        $job->block_scheduling = $validatedData['block_scheduling'];
                    }

                    if (isset($validatedData['contract_termination_policy'])) {
                        $job->contract_termination_policy = $validatedData['contract_termination_policy'];
                    }

                    if (isset($validatedData['Emr'])) {
                        $job->Emr = $validatedData['Emr'];
                    }


                    // added fields from sheets
                    if (isset($validatedData['job_location'])) {
                        $job->job_location = $validatedData['job_location'];
                    }
                    if (isset($validatedData['vaccinations'])) {
                        $job->vaccinations = $validatedData['vaccinations'];
                    }
                    if (isset($validatedData['number_of_references'])) {
                        $job->number_of_references = $validatedData['number_of_references'];
                    }
                    if (isset($validatedData['min_title_of_reference'])) {
                        $job->min_title_of_reference = $validatedData['min_title_of_reference'];
                    }
                    if (isset($validatedData['eligible_work_in_us'])) {
                        $job->eligible_work_in_us = $validatedData['eligible_work_in_us'];
                    }
                    if (isset($validatedData['recency_of_reference'])) {
                        $job->recency_of_reference = $validatedData['recency_of_reference'];
                    }
                    if (isset($validatedData['certificate'])) {
                        $job->certificate = $validatedData['certificate'];
                    }
                    if (isset($validatedData['preferred_shift_duration'])) {
                        $job->preferred_shift_duration = $validatedData['preferred_shift_duration'];
                    }
                    if (isset($validatedData['skills'])) {
                        $job->skills = $validatedData['skills'];
                    }
                    if (isset($validatedData['urgency'])) {
                        $job->urgency = $validatedData['urgency'];
                    }
                    if (isset($validatedData['facilitys_parent_system'])) {
                        $job->facilitys_parent_system = $validatedData['facilitys_parent_system'];
                    }
                    if (isset($validatedData['facility_name'])) {
                        $job->facility_name = $validatedData['facility_name'];
                    }

                    if (isset($validatedData['nurse_classification'])) {
                        $job->nurse_classification = $validatedData['nurse_classification'];
                    }
                    if (isset($validatedData['pay_frequency'])) {
                        $job->pay_frequency = $validatedData['pay_frequency'];
                    }
                    if (isset($validatedData['benefits'])) {
                        $job->benefits = $validatedData['benefits'];
                    }
                    if (isset($validatedData['feels_like_per_hour'])) {
                        $job->feels_like_per_hour = $validatedData['feels_like_per_hour'];
                    }
                    // end added fields from sheets


                    //return $job;

                } catch (Exception $e) {

                    return response()->json(['success' => false, 'message' => $e->getMessage()]);
                }

                $job->organization_id = $created_by;
                $job->created_by = $created_by;
                $job->active = false;
                $job->is_open = false;

                $job->save();

            } elseif ($active == "true") {
                //return request()->all();

                $validatedData = $request->validate([

                    'job_type' => 'required|string',
                    'job_name' => 'required|string',
                    'job_city' => 'required|string',
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
                ]);

                $job = new Job();
                $job->job_type = $validatedData['job_type'];
                $job->type = $validatedData['job_type'];
                $job->job_name = $validatedData['job_name'];
                $job->job_city = $validatedData['job_city'];
                $job->job_state = $validatedData['job_state'];
                $job->weekly_pay = $validatedData['weekly_pay'];
                $job->preferred_specialty = $validatedData['preferred_specialty'];
                $job->description = $validatedData['description'];
                $job->start_date = $validatedData['start_date'];
                $job->hours_shift = $validatedData['hours_shift'];
                $job->preferred_experience = $validatedData['preferred_experience'];
                $job->facility_shift_cancelation_policy = $validatedData['facility_shift_cancelation_policy'];
                $job->traveler_distance_from_facility = $validatedData['traveler_distance_from_facility'];
                $job->clinical_setting = $validatedData['clinical_setting'];
                $job->Patient_ratio = $validatedData['Patient_ratio'];
                $job->Unit = $validatedData['Unit'];
                $job->scrub_color = $validatedData['scrub_color'];
                $job->rto = $validatedData['rto'];
                $job->guaranteed_hours = $validatedData['guaranteed_hours'];
                $job->weeks_shift = $validatedData['weeks_shift'];
                $job->referral_bonus = $validatedData['referral_bonus'];
                $job->sign_on_bonus = $validatedData['sign_on_bonus'];
                $job->completion_bonus = $validatedData['completion_bonus'];
                $job->extension_bonus = $validatedData['extension_bonus'];
                $job->other_bonus = $validatedData['other_bonus'];
                $job->actual_hourly_rate = $validatedData['actual_hourly_rate'];
                $job->overtime = $validatedData['overtime'];
                $job->holiday = $validatedData['holiday'];
                $job->orientation_rate = $validatedData['orientation_rate'];
                $job->on_call = $validatedData['on_call'];
                $job->call_back_rate = $validatedData['call_back_rate'];
                $job->weekly_non_taxable_amount = $validatedData['weekly_non_taxable_amount'];
                $job->profession = $validatedData['profession'];
                $job->specialty = $validatedData['preferred_specialty'];
                $job->organization_id = $created_by;
                $job->created_by = $created_by;
                $job->active = true;
                $job->is_open = true;
                $job->terms = $validatedData['terms'];
                $job->preferred_work_location = $validatedData['preferred_work_location'];
                $job->preferred_assignment_duration = $validatedData['preferred_assignment_duration'];
                $job->block_scheduling = $validatedData['block_scheduling'];
                $job->contract_termination_policy = $validatedData['contract_termination_policy'];
                $job->Emr = $validatedData['Emr'];
                $job->on_call_rate = $validatedData['on_call_rate'];

                // added fields from sheets
                if (isset($validatedData['job_location'])) {
                    $job->job_location = $validatedData['job_location'];
                }
                if (isset($validatedData['vaccinations'])) {
                    $job->vaccinations = $validatedData['vaccinations'];
                }
                if (isset($validatedData['number_of_references'])) {
                    $job->number_of_references = $validatedData['number_of_references'];
                }
                if (isset($validatedData['min_title_of_reference'])) {
                    $job->min_title_of_reference = $validatedData['min_title_of_reference'];
                }
                if (isset($validatedData['eligible_work_in_us'])) {
                    $job->eligible_work_in_us = $validatedData['eligible_work_in_us'];
                }
                if (isset($validatedData['recency_of_reference'])) {
                    $job->recency_of_reference = $validatedData['recency_of_reference'];
                }
                if (isset($validatedData['certificate'])) {
                    $job->certificate = $validatedData['certificate'];
                }
                if (isset($validatedData['preferred_shift_duration'])) {
                    $job->preferred_shift_duration = $validatedData['preferred_shift_duration'];
                }
                if (isset($validatedData['skills'])) {
                    $job->skills = $validatedData['skills'];
                }
                if (isset($validatedData['urgency'])) {
                    $job->urgency = $validatedData['urgency'];
                }
                if (isset($validatedData['facilitys_parent_system'])) {
                    $job->facilitys_parent_system = $validatedData['facilitys_parent_system'];
                }
                if (isset($validatedData['facility_name'])) {
                    $job->facility_name = $validatedData['facility_name'];
                }


                if (isset($validatedData['nurse_classification'])) {
                    $job->nurse_classification = $validatedData['nurse_classification'];
                }
                if (isset($validatedData['pay_frequency'])) {
                    $job->pay_frequency = $validatedData['pay_frequency'];
                }
                if (isset($validatedData['benefits'])) {
                    $job->benefits = $validatedData['benefits'];
                }
                if (isset($validatedData['feels_like_per_hour'])) {
                    $job->feels_like_per_hour = $validatedData['feels_like_per_hour'];
                }
                // end added fields from sheets





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
            //return response()->json(['success' => false, 'message' => $e->getMessage()]);
            return redirect()->route('organization-opportunities-manager')->with('error', 'Failed to add job. Please try again later.');
            // return response()->json(['success' => false, 'message' =>$e->getMessage()]);
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error('Exception: ' . $e->getMessage());

            // Display a generic error message or redirect with an error status
            //return response()->json(['success' => false, 'message' => $e->getMessage()]);
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
                'job_city' => 'nullable|string',
                'job_state' => 'nullable|string',
                'preferred_assignment_duration' => 'nullable|string',
                'weekly_pay' => 'nullable|numeric',
                'preferred_specialty' => 'nullable|string',
                'preferred_work_location' => 'nullable|string',
                'description' => 'nullable|string',

                'preferred_shift_duration' => 'nullable|string',
                'preferred_work_area' => 'nullable|string',
                'preferred_days_of_the_week' => 'nullable|string',
                'preferred_hourly_pay_rate' => 'nullable|string',
                'preferred_experience' => 'nullable|integer',
                'preferred_shift' => 'nullable|string',
                'job_function' => 'nullable|string',
                'job_cerner_exp' => 'nullable|string',
                'job_meditech_exp' => 'nullable|string',

                'seniority_level' => 'nullable|string',
                'job_other_exp' => 'nullable|string',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'hours_shift' => 'nullable|integer',
                'hours_per_week' => 'nullable|integer',
                'responsibilities' => 'nullable|string',
                'qualifications' => 'nullable|string',
                'active' => '0',
            ]);

            // Create a new Job instance with the validated data
            $job = new Job();
            $job->job_type = $validatedData['job_type'];
            $job->job_name = $validatedData['job_name'];
            $job->job_city = $validatedData['job_city'];
            $job->job_state = $validatedData['job_state'];
            $job->preferred_assignment_duration = $validatedData['preferred_assignment_duration'];
            $job->weekly_pay = $validatedData['weekly_pay'];
            $job->preferred_specialty = $validatedData['preferred_specialty'];
            $job->description = $validatedData['description'];
            $job->preferred_shift_duration = $validatedData['preferred_shift_duration'];
            $job->preferred_work_area = $validatedData['preferred_work_area'];
            $job->preferred_days_of_the_week = $validatedData['preferred_days_of_the_week'];
            $job->preferred_hourly_pay_rate = $validatedData['preferred_hourly_pay_rate'];
            $job->preferred_experience = $validatedData['preferred_experience'];
            $job->preferred_shift = $validatedData['preferred_shift'];
            $job->job_function = $validatedData['job_function'];
            $job->job_cerner_exp = $validatedData['job_cerner_exp'];
            $job->job_meditech_exp = $validatedData['job_meditech_exp'];

            $job->seniority_level = $validatedData['seniority_level'];
            $job->job_other_exp = $validatedData['job_other_exp'];
            $job->start_date = $validatedData['start_date'];
            $job->end_date = $validatedData['end_date'];
            $job->hours_shift = $validatedData['hours_shift'];
            $job->hours_per_week = $validatedData['hours_per_week'];
            $job->responsibilities = $validatedData['responsibilities'];
            $job->qualifications = $validatedData['qualifications'];

            // facility id should be null for now since we dont add a facility with the organization signup
            // $job->facility_id = $facility_id;

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
                'job_city' => 'required|string',
                'job_state' => 'required|string',
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

            ]);

            $job = Job::find($request->job_id);

            if ($job === null) {
                return response()->json(['error' => "Job not found"], 404);
            }

            $job->job_type = $validatedData['job_type'];
            $job->type = $validatedData['job_type'];
            $job->job_name = $validatedData['job_name'];
            $job->job_city = $validatedData['job_city'];
            $job->job_state = $validatedData['job_state'];
            $job->weekly_pay = $validatedData['weekly_pay'];
            $job->preferred_experience = $validatedData['preferred_experience'];
            $job->preferred_specialty = $validatedData['preferred_specialty'];
            $job->description = $validatedData['description'];
            $job->start_date = $validatedData['start_date'];
            $job->hours_shift = $validatedData['hours_shift'];
            $job->facility_shift_cancelation_policy = $validatedData['facility_shift_cancelation_policy'];
            $job->traveler_distance_from_facility = $validatedData['traveler_distance_from_facility'];
            $job->clinical_setting = $validatedData['clinical_setting'];
            $job->Patient_ratio = $validatedData['Patient_ratio'];
            $job->Unit = $validatedData['Unit'];
            $job->scrub_color = $validatedData['scrub_color'];
            $job->rto = $validatedData['rto'];
            $job->guaranteed_hours = $validatedData['guaranteed_hours'];
            $job->weeks_shift = $validatedData['weeks_shift'];
            $job->referral_bonus = $validatedData['referral_bonus'];
            $job->sign_on_bonus = $validatedData['sign_on_bonus'];
            $job->completion_bonus = $validatedData['completion_bonus'];
            $job->extension_bonus = $validatedData['extension_bonus'];
            $job->other_bonus = $validatedData['other_bonus'];
            $job->actual_hourly_rate = $validatedData['actual_hourly_rate'];
            $job->overtime = $validatedData['overtime'];
            $job->holiday = $validatedData['holiday'];
            $job->orientation_rate = $validatedData['orientation_rate'];
            $job->on_call = $validatedData['on_call'];
            $job->call_back_rate = $validatedData['call_back_rate'];
            $job->weekly_non_taxable_amount = $validatedData['weekly_non_taxable_amount'];
            $job->profession = $validatedData['profession'];
            $job->specialty = $validatedData['preferred_specialty'];
            $job->organization_id = $created_by;
            $job->created_by = $created_by;
            $job->active = true;
            $job->is_open = true;
            $job->terms = $validatedData['terms'];
            $job->preferred_work_location = $validatedData['preferred_work_location'];
            $job->preferred_assignment_duration = $validatedData['preferred_assignment_duration'];
            $job->block_scheduling = $validatedData['block_scheduling'];
            $job->contract_termination_policy = $validatedData['contract_termination_policy'];
            $job->Emr = $validatedData['Emr'];
            $job->on_call_rate = $validatedData['on_call_rate'];
            // added field from sheets
            $job->job_location = $validatedData['job_location'];
            $job->vaccinations = $validatedData['vaccinations'];
            $job->number_of_references = $validatedData['number_of_references'];
            $job->min_title_of_reference = $validatedData['min_title_of_reference'];
            $job->eligible_work_in_us = $validatedData['eligible_work_in_us'];
            $job->recency_of_reference = $validatedData['recency_of_reference'];
            $job->certificate = $validatedData['certificate'];
            $job->preferred_shift_duration = $validatedData['preferred_shift_duration'];
            $job->skills = $validatedData['skills'];
            $job->urgency = $validatedData['urgency'];
            $job->facilitys_parent_system = $validatedData['facilitys_parent_system'];
            $job->facility_name = $validatedData['facility_name'];
            $job->nurse_classification = $validatedData['nurse_classification'];
            $job->pay_frequency = $validatedData['pay_frequency'];
            $job->benefits = $validatedData['benefits'];
            $job->feels_like_per_hour = $validatedData['feels_like_per_hour'];
            // end added field from sheets

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
                $orgId = Auth::guard('organization')->user()->id;

                
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
                    $check = User::where(['email'=>$request->email])->whereNull('deleted_at')->first();
                    if (!empty($check)) {
                        $data = [];
                        $data['msg'] ='Already exist.';
                        $data['success'] = false;
                        return response()->json($data);
                    }
                    $response = [];
                    $model = User::create([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'mobile' => $request->mobile,
                        'email' => $request->email,
                        'user_name' => $request->email,
                        'active' => '1',
                        'role' => 'RECRUITER',
                    ]);


                    $scriptResponse = Http::post('http://localhost:4545/organizations/addRecruiter/' . $orgId, [
                        'id' => $model->id,
                        'worksAssigned' => 0,
                        'upNext' => true,
                    ]);

                    //return $scriptResponse;

                    if ($scriptResponse->failed()) {
                        $data = [];
                        $data['msg'] = $response->json()['message'];
                        $data['success'] = false;
                        return response()->json($data);
                    }

                    $response['msg'] = 'Registered successfully!';
                    $response['success'] = true;
                    return response()->json($response);
                }

        }catch(\Exception $e){
            $data = [];
            $data['msg'] = $e->getMessage();
           //$data['msg'] ='We encountered an error. Please try again later.';
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

            $columns = Schema::getColumnListing('jobs');
            $columns = array_diff($columns, ['id','import_id','facility_id','created_at','updated_at','created_by','deleted_at','recruiter_id','organization_id']);
            $orgId = Auth::guard('organization')->user()->id;

            $requiredFields = Http::post('http://localhost:4545/organizations/get-preferences', [
                'id' => $orgId,
            ]);
            $requiredFields = $requiredFields->json();
            // return response()->json(['columns'=>$columns]);
            return view('organization::organization.organization_rules_management', compact('columns','requiredFields'));

        }catch(\Exception $ex){

            return response()->json([
                'msg' => $e->getMessage(),
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
            $job = Job::where('id', $jobId)->update(['recruiter_id' => $recruiterId]);

            if (!$job) {
                return response()->json(['error' => "Job not found"], 404);
            }

            return response()->json(['success' => true, 'message' => 'Recruiter assigned successfully']);

        }catch(\Exception $e){
            return response()->json(['error' => "An error occurred: " . $e->getMessage()], 500);
        }
    }





}
