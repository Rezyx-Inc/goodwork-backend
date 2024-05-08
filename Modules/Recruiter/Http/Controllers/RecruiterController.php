<?php

namespace Modules\Recruiter\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Events\NewPrivateMessage;
use DB;
use Auth;
use Illuminate\Support\Facades\Log;

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

        $chat = DB::connection('mongodb')
            ->collection('chat')
            ->raw(function ($collection) use ($idEmployer, $idRecruiter, $idWorker, $skip) {
                return $collection
                    ->aggregate([
                        [
                            '$match' => [
                                'employerId' => $idEmployer,
                                // 'recruiterId'=> $idRecruiter,
                                // for now until get the offer works
                                'recruiterId' => 'GWU000005',

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
                                'employerId' => 1,
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
            $data_User['employerId'] = $room->employerId;
            $data_User['isActive'] = $room->isActive;
            $data_User['messages'] = $room->messages;

            array_push($data, $data_User);
        }

        return response()->json($data);
    }

    public function get_messages()
    {
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
                                'employerId' => 1,
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
            $data_User['employerId'] = $room->employerId;
            $data_User['messages'] = $room->messages;

            array_push($data, $data_User);
        }

        return view('recruiter::recruiter/messages', compact('id', 'data'));
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

        // if i located the recruiter dash i should get the id of the employer from the request
        // we will use this id for now : "GWU000005"

        $idEmployer = $request->idEmployer;

        $time = now()->toDateTimeString();
        event(new NewPrivateMessage($message, $idEmployer, 'GWU000005', $idWorker, $role, $time, $type, $fileName));

        return true;
    }

    // adding jobs :
    public function addJob()
    {
        return view('recruiter::layouts.addJob');
    }

    public function addJobStore(Request $request)
    {
        try {
            $created_by = Auth::guard('recruiter')->user()->id;
            // Validate the form data

            $active = $request->input('active');

            // $active = $activeRequest['active'];
            $validatedData = [];

            if ($active == '0') {
                $validatedData = $request->validate([
                    'job_type' => 'nullable|string',
                    'job_name' => 'string',
                    'job_city' => 'nullable|string',
                    'job_state' => 'nullable|string',
                    'preferred_assignment_duration' => 'nullable|string',
                    'weekly_pay' => 'nullable|numeric',
                    'preferred_specialty' => 'nullable|string',
                    'preferred_work_location' => 'nullable|string',
                    'description' => 'nullable|string',
                    'active' => 'nullable|string',
                    'preferred_shift_duration' => 'nullable|string',
                    'preferred_work_area' => 'nullable|string',
                    'preferred_days_of_the_week' => 'nullable|string',
                    'preferred_hourly_pay_rate' => 'nullable|string',
                    'preferred_experience' => 'nullable|string',
                    'preferred_shift' => 'nullable|string',
                    'job_function' => 'nullable|string',
                    'job_cerner_exp' => 'nullable|string',
                    'job_meditech_exp' => 'nullable|string',

                    'weekly_taxable_amount' => 'nullable|integer',
                    'job_other_exp' => 'nullable|string',
                    'start_date' => 'nullable|date',

                    'hours_shift' => 'nullable|integer',
                    'hours_per_week' => 'nullable|integer',

                    'qualifications' => 'nullable|string',
                    'weekly_non_taxable_amount' => 'nullable|integer'
                ]);
            } elseif ($active == '1') {
                $validatedData = $request->validate([
                    'job_type' => 'required|string',
                    'job_name' => 'required|string',
                    'job_city' => 'required|string',
                    'job_state' => 'required|string',

                    'weekly_pay' => 'required|numeric',
                    'preferred_specialty' => 'required|string',
                    'preferred_work_location' => 'nullable|string',
                    'description' => 'nullable|string',
                    'active' => 'string',
                    'preferred_shift_duration' => 'nullable|string',
                    'preferred_work_area' => 'nullable|string',
                    'preferred_days_of_the_week' => 'nullable|string',
                    'preferred_hourly_pay_rate' => 'nullable|string',
                    'preferred_experience' => 'nullable|string',
                    'preferred_shift' => 'nullable|string',
                    'job_function' => 'nullable|string',





                    'start_date' => 'nullable|date',

                    'hours_shift' => 'nullable|integer',
                    'hours_per_week' => 'nullable|integer',

                    'qualifications' => 'nullable|string',
                    'facility_shift_cancelation_policy' => 'nullable|string',
                    'traveler_distance_from_facility' => 'nullable|string',
                    'clinical_setting' => 'nullable|string',
                    'Patient_ratio' => 'nullable|string',
                    'Unit' => 'nullable|string',
                    'scrub_color' => 'nullable|string',
                    'rto' => 'nullable|string',
                    'guaranteed_hours' => 'nullable|string',
                    'weekly_taxable_amount' => 'nullable|integer',
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
                    'weekly_non_taxable_amount' => 'nullable|integer'

                ]);
            } else {
                //return response()->json(['success' => false, 'message' => $active]);
                return redirect()->route('recruiter-opportunities-manager')->with('error', 'Please Try Again Later');
            }
            //return response()->json(['success' => true, 'message' => $request->all()]);
            // Create a new Job instance with the validated data
            $job = new Job();
            $job->job_type = $validatedData['job_type'];
            $job->job_name = $validatedData['job_name'];
            $job->job_city = $validatedData['job_city'];
            $job->job_state = $validatedData['job_state'];
            $job->weekly_pay = $validatedData['weekly_pay'];
            $job->preferred_specialty = $validatedData['preferred_specialty'];
            $job->active = $validatedData['active'];
            $job->description = $validatedData['description'];
            $job->start_date = $validatedData['start_date'];
            $job->hours_shift = $validatedData['hours_shift'];
            $job->hours_per_week = $validatedData['hours_per_week'];
            $job->facility_shift_cancelation_policy = $validatedData['facility_shift_cancelation_policy'];
            $job->traveler_distance_from_facility = $validatedData['traveler_distance_from_facility'];
            $job->clinical_setting = $validatedData['clinical_setting'];
            $job->Patient_ratio = $validatedData['Patient_ratio'];
            $job->Unit = $validatedData['Unit'];
            $job->scrub_color = $validatedData['scrub_color'];
            $job->rto = $validatedData['rto'];
            $job->guaranteed_hours = $validatedData['guaranteed_hours'];
            $job->hours_per_week = $validatedData['hours_per_week'];
            $job->hours_shift = $validatedData['hours_shift'];
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
            $job->weekly_taxable_amount = $validatedData['weekly_taxable_amount'];
            $job->weekly_non_taxable_amount = $validatedData['weekly_non_taxable_amount'];
            $job->created_by = $created_by;

            // Save the job data to the database
            $job->save();

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
                'preferred_experience' => 'nullable|string',
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

            // facility id should be null for now since we dont add a facility with the recruiter signup
            // $job->facility_id = $facility_id;

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
}
