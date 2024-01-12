<?php

namespace Modules\Employer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\NewPrivateMessage;

use App\Models\Job;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

use App\Models\Offer;
use DB;
use Exception;

class EmployerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('employer::layouts.main');
    }

    public function addJob()
    {
        return view('employer::layouts.addJob');

    }


    public function addJobStore(Request $request)
    {
        try {

            $created_by = Auth::guard('employer')->user()->id;
            // Validate the form data

            $active = $request->input('active');

            // $active = $activeRequest['active'];
            $validatedData =[];

            if($active == '0'){
                $validatedData = $request->validate([
                    'job_type' => 'nullable|string',
                    'job_name' => 'string',
                    'job_city' => 'nullable|string',
                    'job_state' => 'nullable|string',
                    'preferred_assignment_duration' => 'nullable|string',
                    'weekly_pay' => 'nullable|numeric',
                    'preferred_specialty' => 'nullable|string',
                    'preferred_work_location'=>'nullable|string',
                    'description'=>'nullable|string',
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
                    'job_epic_exp' => 'nullable|string',
                    'seniority_level' => 'nullable|string',
                    'job_other_exp' => 'nullable|string',
                    'start_date' => 'nullable|date',
                    'end_date' => 'nullable|date',
                    'hours_shift' => 'nullable|integer',
                    'hours_per_week' => 'nullable|integer',
                    'responsibilities' => 'nullable|string',
                    'qualifications' => 'nullable|string',

                ]);
            }else if($active == '1'){
                $validatedData = $request->validate([
                    'job_type' => 'required|string',
                    'job_name' => 'required|string',
                    'job_city' => 'required|string',
                    'job_state' => 'required|string',
                    'preferred_assignment_duration' => 'nullable|string',
                    'weekly_pay' => 'required|numeric',
                    'preferred_specialty' => 'required|string',
                    'preferred_work_location'=>'nullable|string',
                    'description'=>'nullable|string',
                    'active' => 'string',
                    'preferred_shift_duration' => 'nullable|string',
                    'preferred_work_area' => 'nullable|string',
                    'preferred_days_of_the_week' => 'nullable|string',
                    'preferred_hourly_pay_rate' => 'nullable|string',
                    'preferred_experience' => 'nullable|string',
                    'preferred_shift' => 'nullable|string',
                    'job_function' => 'nullable|string',
                    'job_cerner_exp' => 'nullable|string',
                    'job_meditech_exp' => 'nullable|string',
                    'job_epic_exp' => 'nullable|string',
                    'seniority_level' => 'nullable|string',
                    'job_other_exp' => 'nullable|string',
                    'start_date' => 'nullable|date',
                    'end_date' => 'nullable|date',
                    'hours_shift' => 'nullable|integer',
                    'hours_per_week' => 'nullable|integer',
                    'responsibilities' => 'nullable|string',
                    'qualifications' => 'nullable|string',

                ]);
            }else{
                  //return response()->json(['success' => false, 'message' => $active]);
                  return redirect()->route('employer-opportunities-manager')->with('error', 'Please Try Again Later');
            }


            // Create a new Job instance with the validated data
            $job = new Job();
            $job->job_type = $validatedData['job_type'];
            $job->job_name = $validatedData['job_name'];
            $job->job_city = $validatedData['job_city'];
            $job->job_state = $validatedData['job_state'];
            $job->preferred_assignment_duration = $validatedData['preferred_assignment_duration'];
            $job->weekly_pay = $validatedData['weekly_pay'];
            $job->preferred_specialty = $validatedData['preferred_specialty'];
            $job->active = $validatedData['active'];
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
            $job->job_epic_exp = $validatedData['job_epic_exp'];
            $job->seniority_level = $validatedData['seniority_level'];
            $job->job_other_exp = $validatedData['job_other_exp'];
            $job->start_date = $validatedData['start_date'];
            $job->end_date = $validatedData['end_date'];
            $job->hours_shift = $validatedData['hours_shift'];
            $job->hours_per_week = $validatedData['hours_per_week'];
            $job->responsibilities = $validatedData['responsibilities'];
            $job->qualifications = $validatedData['qualifications'];
            $job->created_by = $created_by;
                        // facility id should be null for now since we dont add a facility with the employer signup
                        // $job->facility_id = $facility_id;

                        // Save the job data to the database
            $job->save();

            // Redirect back to the add job form with a success message
            return redirect()->route('employer-opportunities-manager')->with('success', 'Job added successfully!');

            // return response()->json(['success' => true, 'message' => 'Job added successfully!']);
        } catch (QueryException $e) {
            // Log the error
            Log::error('Error saving job: ' . $e->getMessage());

            // Handle the error gracefully - display a generic error message or redirect with an error status
             return redirect()->route('employer-opportunities-manager')->with('error', 'Failed to add job. Please try again later.');
              // return response()->json(['success' => false, 'message' =>$e->getMessage()]);
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error('Exception: ' . $e->getMessage());

            // Display a generic error message or redirect with an error status


             return redirect()->route('employer-opportunities-manager')->with('error','Try again later' );
            //return response()->json(['success' => false, 'message' => $request->input('') ]);
        }
    }


    public function saveJobAsDraft(Request $request)
    {
        try {

            $facility_id = Auth::guard('employer')->user()->facility_id;
            // Validate the form data
            $validatedData = $request->validate([
                'job_type' => 'nullable|string',
                'job_name' => 'nullable|string',
                'job_city' => 'nullable|string',
                'job_state' => 'nullable|string',
                'preferred_assignment_duration' => 'nullable|string',
                'weekly_pay' => 'nullable|numeric',
                'preferred_specialty' => 'nullable|string',
                'preferred_work_location'=>'nullable|string',
                'description'=>'nullable|string',

                'preferred_shift_duration' => 'nullable|string',
                'preferred_work_area' => 'nullable|string',
                'preferred_days_of_the_week' => 'nullable|string',
                'preferred_hourly_pay_rate' => 'nullable|string',
                'preferred_experience' => 'nullable|string',
                'preferred_shift' => 'nullable|string',
                'job_function' => 'nullable|string',
                'job_cerner_exp' => 'nullable|string',
                'job_meditech_exp' => 'nullable|string',
                'job_epic_exp' => 'nullable|string',
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
            $job->job_epic_exp = $validatedData['job_epic_exp'];
            $job->seniority_level = $validatedData['seniority_level'];
            $job->job_other_exp = $validatedData['job_other_exp'];
            $job->start_date = $validatedData['start_date'];
            $job->end_date = $validatedData['end_date'];
            $job->hours_shift = $validatedData['hours_shift'];
            $job->hours_per_week = $validatedData['hours_per_week'];
            $job->responsibilities = $validatedData['responsibilities'];
            $job->qualifications = $validatedData['qualifications'];

                        // facility id should be null for now since we dont add a facility with the employer signup
                        // $job->facility_id = $facility_id;

                        // Save the job data to the database
            $job->save();

            // Redirect back to the add job form with a success message
            return redirect()->route('employer-opportunities-manager')->with('success', 'Job added successfully!');

            // return response()->json(['success' => true, 'message' => 'Job added successfully!']);
        } catch (QueryException $e) {
            // Log the error
            Log::error('Error saving job: ' . $e->getMessage());

            // Handle the error gracefully - display a generic error message or redirect with an error status
             return redirect()->route('employer-opportunities-manager')->with('error', 'Failed to add job. Please try again later.');
              // return response()->json(['success' => false, 'message' =>$e->getMessage()]);
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error('Exception: ' . $e->getMessage());

            // Display a generic error message or redirect with an error status


             return redirect()->route('employer-opportunities-manager')->with('error',$e->getMessage() );
           // return response()->json(['success' => false, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('employer::create');
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
        return view('employer::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('employer::edit');
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
    public function home()
    {

        $statusList = ['Apply', 'Screening', 'Submitted', 'Offered', 'Done', 'Onboarding', 'Working', 'Rejected', 'Blocked', 'Hold'];
        $statusCounts = [];
        $offerLists = [];
        foreach ($statusList as $status) {
            $statusCounts[$status] = 0;
        }
        $statusCountsQuery = Offer::whereIn('status', $statusList)
            ->select(\DB::raw('status, count(*) as count'))
            ->groupBy('status')
            ->get();
        foreach ($statusCountsQuery as $statusCount) {
            if ($statusCount)
                $statusCounts[$statusCount->status] = $statusCount->count;
            else
                $statusCounts[$statusCount->status] = 0;
        }
        return view('employer::employer/home', compact('statusCounts'));
    }
    public function explore_employees()
    {
        return view('employer::employer/explore_employees');
    }

    public function opportunities_manager()
    {
        return view('employer::employer/opportunitiesmanager');
    }
    public function create_job_request()
    {
        return view('employer::employer/createjobrequest');
    }

    public function get_private_messages(Request $request)
{
    $idWorker = $request->query('workerId');
    $page = $request->query('page', 1);
    
    $idEmployer = Auth::guard('employer')->user()->id;
   
    // Calculate the number of messages to skip
    $skip = ($page - 1) * 10;

    // Get the messages string
    $chat = DB::connection('mongodb')->collection('chat')
    ->raw(function($collection) use ($idEmployer, $idWorker) {
        return $collection->findOne(
            ['employerId' => $idEmployer, 'workerId' => $idWorker],
            ['projection' => ['messages' => 1]]
        );
    });

    // Convert the messages string to an array
    $messages = json_decode($chat['messages'], true);

    // Get the messages for the specified page
    $messages = array_slice($messages, $skip, 10);

    return response()->json(['messages' => $messages]);
}

    public function get_messages(){
        $user = Auth::guard('employer')->user();
        $id = $user->id;

        return  view('employer::employer/messages',compact('id'));
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


    public function get_profile()
    {
        return view('employer::employer/Profile');
    }


    public function keys()
    {
        $id = Auth::guard('employer')->user()->id;
        $keys = DB::table('api_keys')->where('name',$id)->get();
        return view('employer::employer/employer_api_keys',compact('keys'));
        //return response()->json(['msg'=>$keys]);
    }

    public function getapikey(Request $request){

        $case = $request->input('action');
        if($case == 'add'){


                $id = Auth::guard('employer')->user()->id;

                // Check if there are already 3 or more records with the same name
                $count = DB::table('api_keys')->where('name', $id)->count();
                if ($count >= 3) {
                    return redirect()->route('employer-keys')->with('error', 'You had already 3 keys.');
                }
                // Generate a random string for the token
                $randomString = bin2hex(random_bytes(16)); // 16 bytes = 128 bits

                // Create the token by hashing the random string with the secret key
                $token = hash_hmac('sha256', $randomString, $id);

                // Optionally, you can encode the token in base64 for a shorter representation
                $base64Token = base64_encode($token);

                $data = ['name'=>$id,'key'=>$base64Token,'active'=>'1'];
                DB::table('api_keys')->insert($data);

                return redirect()->route('employer-keys')->with('success', 'Key added successfully!');

        }else if($case == 'save'){

            $id = Auth::guard('employer')->user()->id;
        $keys = DB::table('api_keys')->where('name', $id)->pluck('id')->toArray();

        $checkboxes = $request->input('keyCheckboxes');

        if($checkboxes){
            foreach($checkboxes as $key => $value){
                DB::table('api_keys')
        ->where('id', $value)
        ->update([
            'active' => '1',
        ]);
            }

        }

        if($checkboxes != null){
            $uncheckedCheckboxes = array_diff($keys,$checkboxes);

            if($uncheckedCheckboxes ){
                foreach($uncheckedCheckboxes as $key => $value){
                    DB::table('api_keys')
            ->where('id', $value)
            ->update([
                'active' => '0',
            ]);
                }

            }
        }else{
            DB::table('api_keys')
            ->where('name', $id )
            ->update([
                'active' => '0',
            ]);
        }

        return redirect()->route('employer-keys')->with('success', 'Saved');

        }

    }

    public function deleteapikey(Request $request){




                $valuekeyid =  $request->input('delete_key');

                DB::table('api_keys')
                    ->where('id', $valuekeyid)
                    ->delete();

                return redirect()->route('employer-keys')->with('success', 'Deleted successfully');


    }

    public function sendMessages()
    {
        $user = Auth::guard('employer')->user();
        $id = $user->id;
        $role = $user->role;
        // event(new MyEvent('hello world', $user ));
        // event(new NewMessage('hello', 'GWU000002'));
        $time = now()->toDateTimeString();
        event(new NewPrivateMessage('hello', $id,'GWU000005',$role,$time));
        //    event(new NewMessage('hello'));
    return true;
    }

    public function getMessages()
    {

        $user = Auth::guard('employer')->user();
        $id = $user->id;
        return view('employer::layouts.test',compact('id'));
    }



}
