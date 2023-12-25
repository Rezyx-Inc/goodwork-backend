<?php

namespace Modules\Employer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Job;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

use App\Models\Offer;

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

            $facility_id = Auth::guard('employer')->user()->facility_id;
            // Validate the form data
            $validatedData = $request->validate([
                'job_type' => 'required|string',
                'job_name' => 'required|string',
                'job_city' => 'required|string',
                'job_state' => 'required|string',
                'preferred_assignment_duration' => 'required|string',
                'weekly_pay' => 'required|string',
                'preferred_specialty' => 'required|string',
                'preferred_work_location'=>'required|string',
                'description'=>'required|string',
                
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
             return redirect()->route('employer-opportunities-manager')->with('error','An unexpected error occurred. Please try again later.' );
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

    public function get_messages()
    {
        return view('employer::employer/messages');
    }
    public function get_profile()
    {
        return view('employer::employer/Profile');
    }
}
