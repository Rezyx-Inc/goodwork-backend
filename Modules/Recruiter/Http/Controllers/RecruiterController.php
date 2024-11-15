<?php

namespace Modules\Recruiter\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Events\NewPrivateMessage;
use App\Events\NotificationMessage;
use DB;
use Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Offer;
use Exception;
use App\Models\Job;
use App\Models\User;
use App\Models\Nurse;
use App\Models\NotificationMessage as NotificationMessageModel;
use App\Models\NotificationJobModel;
use App\Models\NotificationOfferModel;

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
        $idOrganization = $request->query('organizationId');
        $idWorker = $request->query('workerId');
        $page = $request->query('page', 1);

        $idRecruiter = Auth::guard('recruiter')->user()->id;

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

        return $chat[0];
    }

    public function get_direct_private_messages(Request $request)
    {
        $idOrganization = $request->query('organizationId');
        $idWorker = $request->query('workerId');
        $page = $request->query('page', 1);

        $idRecruiter = Auth::guard('recruiter')->user()->id;

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
        $id = Auth::guard('recruiter')->user()->id;

        $rooms = DB::connection('mongodb')
            ->collection('chat')
            ->raw(function ($collection) use ($id) {
                return $collection
                    ->aggregate([
                        [
                            '$match' => [
                                //'recruiterId' => $id,
                                // for now until get the offer works
                                'recruiterId' => $id,
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
        return view('recruiter::recruiter/messages', compact('idWorker', 'idOrganization', 'direct', 'id', 'data', 'nameworker'));

    }

    public function get_rooms(Request $request)
    {
        $idRecruiter = Auth::guard('recruiter')->user()->id;

        $rooms = DB::connection('mongodb')
            ->collection('chat')
            ->raw(function ($collection) use ($idRecruiter) {
                return $collection
                    ->aggregate([
                        [
                            '$match' => [
                                'recruiterId' => $idRecruiter,
                            ],
                        ],
                        [
                            '$project' => [
                                'organizationId' => 1,
                                'recruiterId' => 1,
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
        $recruiter_id = Auth::guard('recruiter')->user()->id;



        if (isset($worker_id)) {
            $nurse_user_id = Nurse::where('id', $worker_id)->first()->user_id;
            // Check if a room with the given worker_id and recruiter_id already exists
            $room = DB::connection('mongodb')->collection('chat')->where('workerId', $nurse_user_id)->where('recruiterId', $recruiter_id)->first();

            // If the room doesn't exist, create a new one
            if (!$room) {
                DB::connection('mongodb')->collection('chat')->insert([
                    'workerId' => $nurse_user_id,
                    'recruiterId' => $recruiter_id,
                    'organizationId' => $recruiter_id, // Replace this with the actual organizationId
                    'lastMessage' => $this->timeAgo(now()),
                    'isActive' => true,
                    'messages' => [],
                ]);

                // Call the get_private_messages function
                $request->query->set('workerId', $nurse_user_id);
                $request->query->set('organizationId', $recruiter_id); // Replace this with the actual organizationId
                return $this->get_direct_private_messages($request);
            }
        }

        $id = Auth::guard('recruiter')->user()->id;

        $rooms = DB::connection('mongodb')
            ->collection('chat')
            ->raw(function ($collection) use ($id) {
                return $collection
                    ->aggregate([
                        [
                            '$match' => [
                                //'recruiterId' => $id,
                                // for now until get the offer works
                                'recruiterId' => $id,
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
            $data_User['organizationId'] = $room->organizationId;
            $data_User['messages'] = $room->messages;

            array_push($data, $data_User);
        }
        $direct = false;
        $nameworker = '';
        $idWorker = '';
        $idOrganization = '';
        return view('recruiter::recruiter/messages', compact('id', 'data', 'direct', 'idWorker', 'idOrganization', 'nameworker'));
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
        $user = Auth::guard('recruiter')->user();
        $id = $user->id;
        $role = $user->role;
        $idWorker = $request->idWorker;
        $type = $request->type;
        $fileName = $request->fileName;

        // if i located the recruiter dash i should get the id of the organization from the request
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
        return view('recruiter::layouts.addJob');
    }

    public function addJobStore(Request $request)
    {
        // return $request->all();
        // return $request->input('active');
        try {

            $created_by = Auth::guard('recruiter')->user()->id;
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
                    'job_state' => 'nullable|string',
                    'weekly_pay' => 'nullable|numeric',
                    'preferred_specialty' => 'nullable|string',
                    'preferred_work_location' => 'nullable|string',
                    'description' => 'nullable|string',
                    'terms' => 'nullable|string',
                    'start_date' => 'nullable|date',
                    'end_date' => 'nullable|date',
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
                    'weekly_taxable_amount' => 'nullable|string',
                    'goodwork_weekly_amount' => 'nullable|string',
                    'profession' => 'nullable|string',
                    'Emr' => 'nullable|string',
                    'preferred_assignment_duration' => 'nullable|string',
                    'block_scheduling' => 'nullable|string',
                    'contract_termination_policy' => 'nullable|string',
                    'job_location' => 'nullable|string',
                    'vaccinations' => 'nullable|string',
                    'number_of_references' => 'nullable|integer',
                    'min_title_of_reference' => 'nullable|string',
                    // 'eligible_work_in_us' => 'nullable|boolean',
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
                    'total_goodwork_amount' => 'nullable|string',
                    'total_contract_amount' => 'nullable|string',
                    'total_organization_amount' => 'nullable|string',
                ]);

                $job = new Job();

                try {
                    $fields = [
                        'job_type', 'type', 'job_id', 'job_name', 'job_city', 'job_state', 'weekly_pay', 'preferred_specialty',
                        'active', 'description', 'start_date', 'end_date', 'hours_shift', 'hours_per_week', 'preferred_experience',
                        'facility_shift_cancelation_policy', 'traveler_distance_from_facility', 'clinical_setting', 'Patient_ratio',
                        'Unit', 'scrub_color', 'rto', 'guaranteed_hours', 'weeks_shift', 'referral_bonus', 'sign_on_bonus',
                        'completion_bonus', 'extension_bonus', 'other_bonus', 'actual_hourly_rate', 'overtime', 'holiday',
                        'orientation_rate', 'on_call', 'on_call_rate', 'call_back_rate', 'weekly_non_taxable_amount','weekly_taxable_amount','goodwork_weekly_amount', 'profession',
                        'specialty', 'terms', 'preferred_assignment_duration', 'block_scheduling', 'contract_termination_policy',
                        'Emr', 'job_location', 'vaccinations', 'number_of_references', 'min_title_of_reference', 
                        'recency_of_reference', 'certificate', 'preferred_shift_duration', 'skills', 'urgency', 'facilitys_parent_system',
                        'facility_name', 'nurse_classification', 'pay_frequency', 'benefits', 'feels_like_per_hour', 'as_soon_as',
                        'professional_state_licensure', 'total_goodwork_amount' , 'total_contract_amount', 'total_organization_amount'
                    ];
                
                    foreach ($fields as $field) {
                        if (isset($validatedData[$field])) {
                            $job->$field = $validatedData[$field];
                        }
                    }
                
                    $job->recruiter_id = $created_by;
                    $job->created_by = $created_by;
                    $job->active = false;
                    $job->is_open = false;
                
                    $job->save();
                } catch (Exception $e) {
                    return response()->json(['success' => false, 'message' => $e->getMessage()]);
                }

            } elseif ($active == "true") {
                //return request()->all();

                $validatedData = $request->validate([
                    
                    'job_type' => 'nullable|string',
                    'job_name' => 'nullable|string',
                    'job_id' => 'nullable|string',
                    'job_city' => 'required|string',
                    'job_state' => 'required|string',
                    'weekly_pay' => 'nullable|numeric',
                    'preferred_specialty' => 'required|string',
                    'preferred_work_location' => 'nullable|string',
                    'preferred_experience' => 'nullable|integer',
                    'description' => 'nullable|string',
                    'terms' => 'nullable|string',
                    'start_date' => 'nullable|date',
                    'end_date' => 'nullable|date',
                    'hours_shift' => 'nullable|integer',
                    'hours_per_week' => 'nullable|integer',
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
                    'weekly_taxable_amount' => 'nullable|string',
                    'goodwork_weekly_amount' => 'nullable|string',
                    'profession' => 'nullable|string',
                    'Emr' => 'nullable|string',
                    'preferred_assignment_duration' => 'nullable|string',
                    'block_scheduling' => 'nullable|string',
                    'contract_termination_policy' => 'nullable|string',
                    'job_location' => 'nullable|string',
                    'vaccinations' => 'nullable|string',
                    'number_of_references' => 'nullable|integer',
                    'min_title_of_reference' => 'nullable|string',
                    
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
                    'total_goodwork_amount' => 'nullable|string',
                    'total_contract_amount' => 'nullable|string',
                    'total_organization_amount' => 'nullable|string',
                ]);

                $job = new Job();
                $fields = [
                    'job_type', 'type', 'job_name', 'job_id', 'job_city', 'job_state', 'weekly_pay', 'preferred_specialty',
                    'description', 'start_date','end_date','hours_shift', 'hours_per_week', 'preferred_experience', 'facility_shift_cancelation_policy',
                    'traveler_distance_from_facility', 'clinical_setting', 'Patient_ratio', 'Unit', 'scrub_color', 'rto',
                    'guaranteed_hours', 'weeks_shift', 'referral_bonus', 'sign_on_bonus', 'completion_bonus', 'extension_bonus',
                    'other_bonus', 'actual_hourly_rate', 'overtime', 'holiday', 'orientation_rate', 'on_call', 'call_back_rate',
                    'weekly_non_taxable_amount','weekly_taxable_amount','goodwork_weekly_amount', 'profession', 'specialty', 'terms', 'preferred_work_location',
                    'preferred_assignment_duration', 'block_scheduling', 'contract_termination_policy', 'Emr', 'on_call_rate',
                    'job_location', 'vaccinations', 'number_of_references', 'min_title_of_reference',
                    'recency_of_reference', 'certificate', 'preferred_shift_duration', 'skills', 'urgency', 'facilitys_parent_system',
                    'facility_name', 'nurse_classification', 'pay_frequency', 'benefits', 'feels_like_per_hour', 'as_soon_as',
                    'professional_state_licensure', 'total_goodwork_amount' , 'total_contract_amount', 'total_organization_amount'
                ];
                
                foreach ($fields as $field) {
                    if (isset($validatedData[$field])) {
                        $job->$field = $validatedData[$field];
                    }
                }
                
                $job->recruiter_id = $created_by;
                $job->created_by = $created_by;
                $job->active = true;
                $job->is_open = true;
                
                // WE DON'T NEED THE CALCULATION FOR NOW UNTIL WE GET THE FIRST VERSION OF THE APP

                // $job->hours_per_week = $job->weeks_shift * $job->hours_shift; --
                // $job->weekly_taxable_amount = $job->hours_per_week * $job->actual_hourly_rate; --
                // $job->organization_weekly_amount = $job->weekly_taxable_amount + $job->weekly_non_taxable_amount;
                // $job->total_organization_amount = ($job->preferred_assignment_duration * $job->organization_weekly_amount) + ($job->sign_on_bonus + $job->completion_bonus);
                // $job->goodwork_weekly_amount = ($job->organization_weekly_amount) * 0.05; --
                // $job->total_goodwork_amount = $job->goodwork_weekly_amount * $job->preferred_assignment_duration;
                // $job->total_contract_amount = $job->total_goodwork_amount + $job->total_organization_amount;
                
                // Save the job data to the database
                $job->save();

            } else {

                //return response()->json(['success' => false, 'message' => $active]);
                //return redirect()->route('recruiter-opportunities-manager')->with('error', 'Please Try Again Later');
                return response()->json(['success' => false, 'message' => $active]);
            }

            //return response()->json(['success' => true, 'message' => $request->all()]);
            // Create a new Job instance with the validated data


            //return response()->json(['success' => true, 'message' => 'Job added successfully!']);

            // Redirect back to the add job form with a success message
            return redirect()->route('recruiter-opportunities-manager')->with('success', 'Job added successfully!');

            // return response()->json(['success' => true, 'message' => 'Job added successfully!']);
        } catch (QueryException $e) {
            // Log the error
            Log::error('Error saving job: ' . $e->getMessage());

            // Handle the error gracefully - display a generic error message or redirect with an error status
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
            return redirect()->route('recruiter-opportunities-manager')->with('error', 'Failed to add job. Please try again later.');
            // return response()->json(['success' => false, 'message' =>$e->getMessage()]);
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error('Exception: ' . $e->getMessage());

            // Display a generic error message or redirect with an error status
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
            return redirect()->route('recruiter-opportunities-manager')->with('error', 'Try again later');
            //return response()->json(['success' => false, 'message' => $request->input('') ]);
        }
    }

    public function saveJobAsDraft(Request $request)
    {
        try {
            $facility_id = Auth::guard('recruiter')->user()->facility_id;
            // Validate the form data
            $validatedData = $request->validate([
                    'job_type' => 'nullable|string',
                    'job_name' => 'nullable|string',
                    'job_id' => 'nullable|string',
                    'job_city' => 'nullable|string',
                    'job_state' => 'nullable|string',
                    'weekly_pay' => 'nullable|numeric',
                    'preferred_specialty' => 'nullable|string',
                    'preferred_work_location' => 'nullable|string',
                    'preferred_experience' => 'nullable|integer',
                    'description' => 'nullable|string',
                    'terms' => 'nullable|string',
                    'start_date' => 'nullable|date',
                    'end_date' => 'nullable|date',
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
                    'total_goodwork_amount' => 'nullable|string',
                    'total_contract_amount' => 'nullable|string',
                    'total_organization_amount' => 'nullable|string',

            ]);

            // Create a new Job instance with the validated data
            $job = new Job();
            $fields = [
                'job_type', 'job_id', 'job_name', 'job_city', 'job_state', 'weekly_pay', 'preferred_specialty',
                'preferred_work_location', 'description', 'terms', 'start_date','end_date', 'hours_shift',
                'facility_shift_cancelation_policy', 'traveler_distance_from_facility', 'clinical_setting',
                'Patient_ratio', 'Unit', 'scrub_color', 'rto', 'guaranteed_hours', 'weeks_shift',
                'referral_bonus', 'sign_on_bonus', 'completion_bonus', 'extension_bonus', 'other_bonus',
                'actual_hourly_rate', 'overtime', 'holiday', 'orientation_rate', 'on_call', 'on_call_rate',
                'call_back_rate', 'weekly_non_taxable_amount', 'profession', 'Emr', 'preferred_assignment_duration',
                'block_scheduling', 'contract_termination_policy', 'job_location', 'vaccinations',
                'number_of_references', 'min_title_of_reference', 'eligible_work_in_us', 'recency_of_reference',
                'certificate', 'preferred_shift_duration', 'skills', 'urgency', 'facilitys_parent_system',
                'facility_name', 'nurse_classification', 'pay_frequency', 'benefits', 'feels_like_per_hour',
                'as_soon_as', 'professional_state_licensure', 'total_goodwork_amount' , 'total_contract_amount', 'total_organization_amount'
            ];
            
            foreach ($fields as $field) {
                if (isset($validatedData[$field])) {
                    $job->$field = $validatedData[$field];
                }
            }
            
            $job->recruiter_id = Auth::guard('recruiter')->user()->id;
            $job->created_by = Auth::guard('recruiter')->user()->id;
            $job->active = false;
            $job->is_open = false;
            
            // Save the job data to the database
            $job->save();

            // Redirect back to the add job form with a success message
            return redirect()->route('recruiter-opportunities-manager')->with('success', 'Job added successfully!');

            // return response()->json(['success' => true, 'message' => 'Job added successfully!']);
        } catch (QueryException $e) {
            // Log the error
            Log::error('Error saving job: ' . $e->getMessage());

            // Handle the error gracefully - display a generic error message or redirect with an error status
            return redirect()->route('recruiter-opportunities-manager')->with('error', 'Failed to add job. Please try again later.');
            // return response()->json(['success' => false, 'message' =>$e->getMessage()]);
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error('Exception: ' . $e->getMessage());

            // Display a generic error message or redirect with an error status

            return redirect()->route('recruiter-opportunities-manager')->with('error', $e->getMessage());
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
        $created_by = Auth::guard('recruiter')->user()->id;
        // Validate the form data

        $active = $request->input('active');

        // $active = $activeRequest['active'];
        $validatedData = [];
        try {
            $validatedData = $request->validate([
                'job_type' => 'nullable|string',
                'job_name' => 'required|string',
                'job_id' => 'nullable|string',
                'job_city' => 'required|string',
                'job_state' => 'required|string',
                'weekly_pay' => 'nullable|numeric',
                'preferred_specialty' => 'required|string',
                'preferred_work_location' => 'nullable|string',
                'description' => 'nullable|string',
                'terms' => 'nullable|string',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
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
                'profession' => 'required|string',
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
                'total_goodwork_amount' => 'nullable|string',
                'total_contract_amount' => 'nullable|string',
                'total_organization_amount' => 'nullable|string',


            ]);

            $job = Job::find($request->id);

            if ($job === null) {
                return response()->json(['error' => "Job not found"], 404);
            }

            $fields = [
                'job_type', 'type', 'job_name', 'job_id', 'job_city', 'job_state', 'weekly_pay', 'preferred_experience',
                'preferred_specialty', 'description', 'start_date','end_date', 'hours_shift', 'facility_shift_cancelation_policy',
                'traveler_distance_from_facility', 'clinical_setting', 'Patient_ratio', 'Unit', 'scrub_color', 'rto',
                'guaranteed_hours', 'weeks_shift', 'referral_bonus', 'sign_on_bonus', 'completion_bonus', 'extension_bonus',
                'other_bonus', 'actual_hourly_rate', 'overtime', 'holiday', 'orientation_rate', 'on_call', 'call_back_rate',
                'weekly_non_taxable_amount', 'profession', 'specialty', 'terms', 'preferred_work_location',
                'preferred_assignment_duration', 'block_scheduling', 'contract_termination_policy', 'Emr', 'on_call_rate',
                'job_location', 'vaccinations', 'number_of_references', 'min_title_of_reference', 'eligible_work_in_us',
                'recency_of_reference', 'certificate', 'preferred_shift_duration', 'skills', 'urgency', 'facilitys_parent_system',
                'facility_name', 'nurse_classification', 'pay_frequency', 'benefits', 'feels_like_per_hour', 'as_soon_as' , 'professional_state_licensure', 'total_goodwork_amount' , 'total_contract_amount', 'total_organization_amount'
            ];
            
            foreach ($fields as $field) {
                if (isset($validatedData[$field])) {
                    $job->$field = $validatedData[$field];
                }
            }
            
            $job->recruiter_id = $created_by;
            $job->created_by = $created_by;
            $job->active = true;
            $job->is_open = true;
            
            $job->hours_per_week = $job->weeks_shift * $job->hours_shift;
            $job->weekly_taxable_amount = $job->hours_per_week * $job->actual_hourly_rate;
            $job->organization_weekly_amount = $job->weekly_taxable_amount + $job->weekly_non_taxable_amount;
            $job->total_organization_amount = ($job->preferred_assignment_duration * $job->organization_weekly_amount) + ($job->sign_on_bonus + $job->completion_bonus);
            $job->goodwork_weekly_amount = ($job->organization_weekly_amount) * 0.05;
            $job->total_goodwork_amount = $job->goodwork_weekly_amount * $job->preferred_assignment_duration;
            $job->total_contract_amount = $job->total_goodwork_amount + $job->total_organization_amount;
            
            // update the job data to the database
            $job->save();

            return redirect()->route('recruiter-opportunities-manager')->with('success', 'Job updated successfully');

            return response()->json(['status' => 'success', 'message' => 'Job updated successfully']);

            //return response()->json($job);
        } catch (\Exception $e) {
            return response()->json(['error' => "An error occurred: " . $e->getMessage()], 500);
        }
    }


    public function read_recruiter_offer_notification(Request $request)
    {
        $sender = $request->senderId;
        $offerId = $request->offerId;
        $user = Auth::guard('recruiter')->user();
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

    public function read_recruiter_message_notification(Request $request)
    {

        $sender = $request->sender;
        $receiver = Auth::guard('recruiter')->user()->id;

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

    public function read_recruiter_job_notification(Request $request)
    {
        $sender = $request->sender;
        $job_id = $request->jobId;
        $receiver = Auth::guard('recruiter')->user()->id;

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

}
