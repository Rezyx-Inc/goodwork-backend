<?php

namespace Modules\Recruiter\Http\Controllers;

use DateTime;
use App\Models\Job;
use App\Models\User;
use App\Models\Offer;
use App\Models\States;
use App\Models\Cities;
use App\Models\Nurse;
use App\Models\Keyword;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\{Request, jsonResponse};
use Illuminate\Contracts\Support\Renderable;
class ApplicationController extends Controller
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

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function application()
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
        return view('recruiter::recruiter/applicationjourney', compact('statusCounts'));
    }

    public function getApplicationListing(Request $request)
    {
        $type = $request->type;
        $allspecialty = [];
        $allvaccinations = [];
        $allcertificate = [];
        $offerLists = Offer::where('status', $type)->get();
        if (0 >= count($offerLists)) {
            $responseData = [
                'applicationlisting' => '<div class="text-center"><span>No Application</span></div>',
                'applicationdetails' => '<div class="text-center"><span>Data Not found</span></div>',
            ];

            return response()->json($responseData);
        }
        $data = "";
        $data2 = "";
        // str_contains($nurse['worker_shift_time_of_day'], 'day') ? 'day' : '' . ' ' . str_contains($nurse['worker_shift_time_of_day'], 'night') ? 'night' : ''
        foreach ($offerLists as $key => $value) {
            if ($value) {
                $nurse = Nurse::where('id', $value->nurse_id)->first();
                $user = user::where('id', $nurse->user_id)->first();
                $data .= '
                    <div class="ss-job-prfle-sec ' . ( $request->id == $value->id ? 'active' : '') . '" id="ss-expl-applicion-div-box" onclick="applicationType(\'' . $type . '\', \'' . $value->id . '\', \'userdetails\')">
                        <div class="ss-job-id-no-name">
                            <ul>
                                <li class="w-50">
                                    <span href="" class="mb-3">' . $value->nurse_id . '</span>
                                </li>
                                <li class="w-50">
                                    <p>Recently Added</p>
                                </li>
                            </ul>
                        </div>
                        <ul>
                            <li>
                                <img src="' . asset('public/images/nurses/profile/' . $user['image']) . '" onerror="this.onerror=null;this.src=' . '\'' . asset('public/frontend/img/profile-pic-big.png') . '\'' . ';" id="preview" width="50px" height="50px" style="object-fit: cover;" class="rounded-3" alt="Profile Picture">
                            </li>
                            <li>
                                <h6></h6>
                            </li>
                            <li>
                                <h6 class="job-title">' . $user['first_name'] . ' ' . $user['last_name'] . '</h6>
                            </li>
                        </ul>
                        <ul class="ss-expl-applicion-ul2">';
                            if (isset($nurse['highest_nursing_degree'])) {
                                $data .= '<li"><a href="#">' . $nurse['highest_nursing_degree'] . '</a></li>';
                            }
                            if (isset($nurse['specialty'])) {
                                $data .= '<li"><a href="#">' . $nurse['specialty'] . '</a></li>';
                            }
                            if (isset($nurse['worker_shift_time_of_day'])) {
                                $data .= '<li"><a href="#">' . $nurse['worker_shift_time_of_day'] . '</a></li>';
                            }
                            if (isset($nurse['experience'])) {
                                $data .= '<li"><a href="#">' . array_sum(explode(",", $nurse['experience'])) . ' years experience </a></li>';
                            }
                            $data .= '
                        </ul>
                </div>';
            }
        }
        if ($data == "") {
            $data = '<div class="text-center"><span>No Application</span></div>';
        }
        $offerdetails = "";
        $jobdetails = "";
        if (isset($request->id)) {
            $offerdetails = Offer::where(['status' => $type, 'id' => $request->id])->first();
        } else {
            $offerdetails = Offer::where('status', $type)->first();
        }
        if($request->formtype == 'jobdetails'){
            $jobdetails = Job::where('id', $request->jobid)->first();
        }else{
            // there is no job referneces table 

            // $jobdetails = Job::select('jobs.*','job_references.name','job_references.min_title_of_reference','job_references.recency_of_reference')
            // ->leftJoin('job_references','job_references.job_id', '=', 'jobs.id')
            // ->where('jobs.id', $offerdetails->job_id)->first();

            $jobdetails = Job::select('jobs.*')
            ->where('jobs.id', $offerdetails->job_id)->first();
        }
        if(isset($offerdetails)){
            // there is no nurse references 

            // $nursedetails = NURSE::select('nurses.*','nurse_references.name','nurse_references.min_title_of_reference','nurse_references.recency_of_reference')
            // ->leftJoin('nurse_references','nurse_references.nurse_id', '=', 'nurses.id')
            // ->where('nurses.id', $offerdetails['nurse_id'])->first();

            $nursedetails = NURSE::select('nurses.*')
            ->where('nurses.id', $offerdetails['nurse_id'])->first();

            $userdetails = $nursedetails ? User::where('id', $nursedetails->user_id)->first() : "";
            $jobapplieddetails = $nursedetails ? Offer::where(['status' => $type, 'nurse_id' => $offerdetails['nurse_id']])->get() : "";
            $jobappliedcount = $nursedetails ? Offer::where(['status' => $type, 'nurse_id' => $offerdetails['nurse_id']])->count() : "";

            if ($request->formtype == 'useralldetails') {
                $data2 .= '
                    <ul class="ss-cng-appli-hedpfl-ul">
                        <li>
                            <span>' . $offerdetails['nurse_id'] . '</span>
                            <h6>
                                <img src="' . asset('public/images/nurses/profile/' . $userdetails->image) . '" onerror="this.onerror=null;this.src=' . '\'' . asset('public/frontend/img/profile-pic-big.png') . '\'' . ';" id="preview" width="50px" height="50px" style="object-fit: cover;" class="rounded-3" alt="Profile Picture">
                                ' . $userdetails->first_name . ' ' . $userdetails->last_name . '
                            </h6>
                        </li>
                        <li>';

                            if($request->type == 'Apply'|| $request->type == 'Screening' || $request->type == 'Submitted'){
                                $data2 .= '
                                    <button class="rounded-pill ss-apply-btn py-2 border-0 px-4" onclick="applicationType(\'' . $type . '\', \'' . $request->id . '\', \'jobdetails\', \'' . $request->jobid . '\')">Send Offer</button>
                                ';
                            }
                            $data2 .= '
                        </li>
                    </ul>';
                $data2 .= '
                    <div class="ss-chng-apcon-st-ssele">
                        <label class="mb-2">Change Application Status</label>
                        <select name="status" id="status application-status" onchange="applicationStatus(this.value, \'' . $type . '\', \'' . $request->id . '\', \'' . $request->jobid . '\')">
                            <option value="">Select Status</option>
                            <option value="Apply" ' . ($offerdetails['status'] === 'Apply' ? 'selected' : '') . '>Apply</option>
                            <option value="Screening" ' . ($offerdetails['status'] === 'Screening' ? 'selected' : '') . '>Screening</option>
                            <option value="Submitted" ' . ($offerdetails['status'] === 'Submitted' ? 'selected' : '') . '>Submitted</option>
                            <option value="Offered" ' . ($offerdetails['status'] === 'Offered' ? 'selected' : '') . '>Offered</option>
                            <option value="Done" ' . ($offerdetails['status'] === 'Done' ? 'selected' : '') . '>Done</option>
                            <option value="Onboarding" ' . ($offerdetails['status'] === 'Onboarding' ? 'selected' : '') . '>Onboarding</option>
                            <option value="Working" ' . ($offerdetails['status'] === 'Working' ? 'selected' : '') . '>Working</option>
                            <option value="Rejected" ' . ($offerdetails['status'] === 'Rejected' ? 'selected' : '') . '>Rejected</option>
                            <option value="Blocked" ' . ($offerdetails['status'] === 'Blocked' ? 'selected' : '') . '>Blocked</option>
                            <option value="Hold" ' . ($offerdetails['status'] === 'Hold' ? 'selected' : '') . '>Hold</option>
                        </select>
                    </div>
                    <div class="ss-jb-apl-oninfrm-mn-dv">
                        <ul class="ss-jb-apply-on-inf-hed-rec row">

                        <li class="col-md-6">
                            <h5 class="mt-3">Job Information</h5>
                        </li>
                        <li class="col-md-6">
                            <h5 class="mt-3">Worker Information</h5>
                        </li>
                        <li class="col-md-12">
                            <span class="mt-3">Diploma</span>
                        </li>
                        <li class="col-md-6">
                            <h6>College Diploma</h6>
                        </li>
                        <li class="col-md-6">
                            <p>' . ($nursedetails->diploma ?? '<u onclick="askWorker(this, \'diploma\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </li>
                        <li class="col-md-12">
                            <span class="mt-3">Drivers license</span>
                        </li>
                        <li class="col-md-6">
                            <h6>Required</h6>
                        </li>
                        <li class="col-md-6">
                            <p>' . ($nursedetails->driving_license ?? '<u onclick="askWorker(this, \'driving_license\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </li>
                        <li class="col-md-12">
                            <span class="mt-3">Worked at Facility Before</span>
                        </li>
                        <li class="col-md-6">
                            <h6>Have you worked here in the last 18 months?</h6>
                        </li>
                        <li class="col-md-6">
                            <p>' . ($nursedetails->worked_at_facility_before ?? '<u onclick="askWorker(this, \'worked_at_facility_before\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </li>
                        <li class="col-md-12">
                            <span class="mt-3">SS# or SS Card</span>
                        </li>
                        <li class="col-md-6">
                            <h6>Last 4 digits of SS#</h6>
                        </li>
                        <li class="col-md-6">
                            <p>' . ($nursedetails->worker_ss_number ?? '----' ) . '</p>
                        </li>
                        <li class="col-md-12">
                            <span class="mt-3">Profession</span>
                        </li>
                        <li class="col-md-6">
                            <h6>' . ($jobdetails->profession ?? '----') . '</h6>
                        </li>
                        <li class="col-md-6">
                            <p>' . ($nursedetails->highest_nursing_degree ?? '<u onclick="askWorker(this, \'highest_nursing_degree\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</p>
                        </li>
                        <li class="col-md-12">
                            <span class="mt-3">Specialty</span>
                        </li>';
                        if(isset($jobdetails->specialty)){
                            foreach (explode(",", $nursedetails->specialty) as $key => $value) {
                                if(isset($value)){

                                    $data2 .= '
                                    <div class="col-md-6 ">
                                        <h6>' . $value . ' Required</h6>
                                    </div>
                                    <div class="col-md-6 ">
                                        <p><u onclick="askWorker(this, \'specialty\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u></p>
                                    </div>
                                    ';
                                }
                            }
                        }
                        $data2 .= '
                        <div class="col-md-12">
                            <span class="mt-3">Professional Licensure</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails->job_state ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails->job_state ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails->nursing_license_state ?? '<u onclick="askWorker(this, \'nursing_license_state\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Experience</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails->preferred_experience ?? '----') . ' Years </h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails->preferred_experience ? '' : 'd-none' ) . '">
                            <p>' . (array_sum(explode(",", $nursedetails['experience'])) ? (array_sum(explode(",", $nursedetails['experience'])) . ' years') : '<u onclick="askWorker(this, \'experience\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Vaccinations & Immunizations</span>
                        </div>';
                        if(isset($jobdetails->vaccinations)){
                            foreach (explode(",", $jobdetails->vaccinations) as $key => $value) {
                                if(isset($value)){
                                    $data2 .= '
                                    <div class="col-md-6 ">
                                        <h6>' . $value . ' Required</h6>
                                    </div>
                                    <div class="col-md-6 ">
                                        <p><u onclick="askWorker(this, \'vaccinations\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u></p>
                                    </div>
                                    ';
                                }
                            }
                        }
                        $data2 .= '
                        <div class="col-md-12">
                            <span class="mt-3">References</span>
                        </div>';
                        if(isset($jobdetails->number_of_references)){
                            foreach (explode(",", $jobdetails->number_of_references) as $key => $value) {
                                if(isset($value)){
                                    $data2 .= '
                                    <div class="col-md-6 ">
                                        <h6>' . $value . ' references</h6>
                                    </div>
                                    <div class="col-md-6 ">
                                        <p><u onclick="askWorker(this, \'number_of_references\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u></p>
                                    </div>
                                    ';
                                }
                            }
                        }
                        $data2 .= '
                        <div class="col-md-12">
                            <span class="mt-3">Certifications</span>
                        </div>';
                        if(isset($jobdetails->certificate)){
                            foreach (explode(",", $nursedetails->certificate) as $key => $value) {
                                if(isset($value)){
                                    $data2 .= '
                                    <div class="col-md-6 ">
                                        <h6>' . $value . ' Required</h6>
                                    </div>
                                    <div class="col-md-6 ">
                                        <p><u onclick="askWorker(this, \'certificate\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u></p>
                                    </div>
                                    ';
                                }
                            }
                        }
                        $data2 .= '
                            <div class="col-md-12">
                                <span class="mt-3">Skills checklist</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->skills ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->skills ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->skills ?? '<u onclick="askWorker(this, \'skills\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Eligible to work in the US</span>
                            </div>
                            <div class="col-md-6">
                                <h6>Required</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->profession ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->eligible_work_in_us ?? '<u onclick="askWorker(this, \'eligible_work_in_us\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Urgency</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->urgency ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->urgency ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_urgency ?? '<u onclick="askWorker(this, \'worker_urgency\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3"># of Positions Available</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->position_available ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->position_available ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->available_position ?? '<u onclick="askWorker(this, \'available_position\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">MSP</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->msp ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->msp ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->MSP ?? '<u onclick="askWorker(this, \'MSP\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">VMS</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->vms ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->vms ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->VMS ?? '<u onclick="askWorker(this, \'VMS\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Block scheduling</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->block_scheduling ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->block_scheduling ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->block_scheduling ?? '<u onclick="askWorker(this, \'block_scheduling\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Float requirements</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->float_requirement ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->float_requirement ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->float_requirement ?? '<u onclick="askWorker(this, \'float_requirement\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Facility Shift Cancellation Policy</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->facility_shift_cancelation_policy ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->facility_shift_cancelation_policy ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->facility_shift_cancelation_policy ?? '<u onclick="askWorker(this, \'facility_shift_cancelation_policy\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Contract Termination Policy</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->contract_termination_policy ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->contract_termination_policy ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->contract_termination_policy ?? '<u onclick="askWorker(this, \'contract_termination_policy\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Traveler Distance From Facility</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->traveler_distance_from_facility ?? '----') . ' miles Maximum</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->traveler_distance_from_facility ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->distance_from_your_home ?? '<u onclick="askWorker(this, \'distance_from_your_home\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Facility</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->facility ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->facility ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worked_at_facility_before ?? '<u onclick="askWorker(this, \'worked_at_facility_before\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Facility`s Parent System</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->facilitys_parent_system ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->facilitys_parent_system ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_facility_parent_system ?? '<u onclick="askWorker(this, \'worker_facility_parent_system\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Facility Average Rating</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->facility_average_rating ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->facility_average_rating ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->avg_rating_by_facilities ?? '<u onclick="askWorker(this, \'avg_rating_by_facilities\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Recruiter Average Rating</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->recruiter_average_rating ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->recruiter_average_rating ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_avg_rating_by_recruiters ?? '<u onclick="askWorker(this, \'worker_avg_rating_by_recruiters\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Employer Average Rating</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->employer_average_rating ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->employer_average_rating ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_avg_rating_by_employers ?? '<u onclick="askWorker(this, \'worker_avg_rating_by_employers\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Clinical Setting</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->clinical_setting ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->clinical_setting ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->clinical_setting_you_prefer ?? '<u onclick="askWorker(this, \'clinical_setting_you_prefer\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Patient ratio</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->Patient_ratio ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->Patient_ratio ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_patient_ratio ?? '<u onclick="askWorker(this, \'worker_patient_ratio\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">EMR</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->emr ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->emr ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_emr ?? '<u onclick="askWorker(this, \'worker_emr\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Unit</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->Unit ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->Unit ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_unit ?? '<u onclick="askWorker(this, \'worker_unit\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Department</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->Department ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->Department ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_department ?? '<u onclick="askWorker(this, \'worker_department\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Bed Size</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->Bed_Size ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->Bed_Size ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_bed_size ?? '<u onclick="askWorker(this, \'worker_bed_size\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Trauma Level</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->Trauma_Level ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->Trauma_Level ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_trauma_level ?? '<u onclick="askWorker(this, \'worker_trauma_level\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Scrub Color</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->scrub_color ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->scrub_color ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_scrub_color ?? '<u onclick="askWorker(this, \'worker_scrub_color\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Facility City</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->job_city ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->job_city ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_facility_city ?? '<u onclick="askWorker(this, \'worker_facility_city\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Facility State Code</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->job_state ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->job_state ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_facility_state_code ?? '<u onclick="askWorker(this, \'worker_facility_state_code\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Interview Dates</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . $nursedetails->worker_interview_dates . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($nursedetails->worker_interview_dates ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_interview_dates ?? '<u onclick="askWorker(this, \'worker_interview_dates\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Start Date</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->start_date ? $jobdetails->start_date : 'As Soon As Possible') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->start_date ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_start_date ?? '<u onclick="askWorker(this, \'worker_start_date\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">RTO</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->rto ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->rto ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_as_soon_as_posible ?? '<u onclick="askWorker(this, \'worker_as_soon_as_posible\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Shift Time of Day</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->preferred_shift ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->preferred_shift ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_shift_time_of_day ?? '<u onclick="askWorker(this, \'worker_shift_time_of_day\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Hours/Week</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->hours_per_week ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->hours_per_week ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_hours_per_week ?? '<u onclick="askWorker(this, \'worker_hours_per_week\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Guaranteed Hours</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->guaranteed_hours ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->guaranteed_hours ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_guaranteed_hours ?? '<u onclick="askWorker(this, \'worker_guaranteed_hours\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Hours/Shift</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->preferred_assignment_duration ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->preferred_assignment_duration ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_weeks_assignment ?? '<u onclick="askWorker(this, \'worker_weeks_assignment\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Weeks/Assignment</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->preferred_assignment_duration ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->preferred_assignment_duration ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_weeks_assignment ?? '<u onclick="askWorker(this, \'worker_weeks_assignment\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Shifts/Week</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->weeks_shift ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->weeks_shift ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_shifts_week ?? '<u onclick="askWorker(this, \'worker_shifts_week\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Referral Bonus</span>
                            </div>
                            <div class="col-md-6">
                                <h6>$' . ($jobdetails->referral_bonus ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->referral_bonus ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_referral_bonus ?? '<u onclick="askWorker(this, \'worker_referral_bonus\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Sign-On Bonus</span>
                            </div>
                            <div class="col-md-6">
                                <h6>$' . ($jobdetails->sign_on_bonus ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->sign_on_bonus ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_sign_on_bonus ?? '<u onclick="askWorker(this, \'worker_sign_on_bonus\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Completion Bonus</span>
                            </div>
                            <div class="col-md-6">
                                <h6>$' . ($jobdetails->completion_bonus ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->completion_bonus ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_completion_bonus ?? '<u onclick="askWorker(this, \'worker_completion_bonus\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Extension Bonus</span>
                            </div>
                            <div class="col-md-6">
                                <h6>$' . ($jobdetails->extension_bonus ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->extension_bonus ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_extension_bonus ?? '<u onclick="askWorker(this, \'worker_extension_bonus\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Other Bonus</span>
                            </div>
                            <div class="col-md-6">
                                <h6>$' . ($jobdetails->other_bonus ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->other_bonus ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_other_bonus ?? '<u onclick="askWorker(this, \'worker_other_bonus\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">401K</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->four_zero_one_k ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->four_zero_one_k ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->how_much_k ?? '<u onclick="askWorker(this, \'how_much_k\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Health Insurance</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->health_insaurance ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->health_insaurance ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_health_insurance ?? '<u onclick="askWorker(this, \'worker_health_insurance\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Dental</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->dental ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->dental ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_dental ?? '<u onclick="askWorker(this, \'worker_dental\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Vision</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->vision ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->vision ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_vision ?? '<u onclick="askWorker(this, \'worker_vision\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Actual Hourly rate</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->actual_hourly_rate ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->actual_hourly_rate ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_actual_hourly_rate ?? '<u onclick="askWorker(this, \'worker_actual_hourly_rate\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Feels Like $/hr</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->feels_like_per_hour ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->feels_like_per_hour ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_feels_like_hour ?? '<u onclick="askWorker(this, \'worker_feels_like_hour\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Overtime</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->overtime ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->overtime ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_overtime ?? '<u onclick="askWorker(this, \'worker_overtime\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Holiday</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->holiday ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->holiday ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_holiday ?? '<u onclick="askWorker(this, \'worker_holiday\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">On Call</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->on_call ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->on_call ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_on_call ?? '<u onclick="askWorker(this, \'worker_on_call\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Call Back</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->call_back ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->call_back ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_call_back ?? '<u onclick="askWorker(this, \'worker_call_back\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Orientation Rate</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->orientation_rate ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->orientation_rate ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_orientation_rate ?? '<u onclick="askWorker(this, \'worker_orientation_rate\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Weekly Taxable amount</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->weekly_taxable_amount ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->weekly_taxable_amount ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_weekly_taxable_amount ?? '<u onclick="askWorker(this, \'worker_weekly_taxable_amount\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Employer Weekly Amount</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->employer_weekly_amount ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->employer_weekly_amount ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_employer_weekly_amount ?? '<u onclick="askWorker(this, \'worker_employer_weekly_amount\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Weekly non-taxable amount</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->weekly_non_taxable_amount ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 ' . ($jobdetails->weekly_non_taxable_amount ? '' : 'd-none' ) . '">
                                <p>' . ($nursedetails->worker_weekly_non_taxable_amount ?? '<u onclick="askWorker(this, \'worker_weekly_non_taxable_amount\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Goodwork Weekly Amount</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->weekly_taxable_amount ?? '----') . '</h6>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Total Employer Amount</span>
                            </div>
                            <div class="col-md-12">
                                <h6>' . ($jobdetails->total_employer_amount ?? '----') . '</h6>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Total Goodwork Amount</span>
                            </div>
                            <div class="col-md-12">
                                <h6>' . ($jobdetails->total_goodwork_amount ?? '----') . '</h6>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Total Contract Amount</span>
                            </div>
                            <div class="col-md-12">
                                <h6>' . ($jobdetails->total_contract_amount ?? '----') . '</h6>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Goodwork Number</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' . ($jobdetails->goodwork_number ?? '----') . '</h6>
                            </div>
                        </ul>
                    </div>
                    ';
            } else if($request->formtype == 'jobdetails'){
                $distinctFilters = Keyword::distinct()->pluck('filter');
                $allKeywords = [];
                foreach ($distinctFilters as $filter) {
                    $keywords = Keyword::where('filter', $filter)->get();
                    $allKeywords[$filter] = $keywords;
                }
                $specialty = explode(',', $jobdetails['preferred_specialty']);
                $experience = explode(',', $jobdetails['preferred_experience']);
                if($specialty && $experience){
                    $specialtyCount = count($specialty);
                    $experienceCount = count($experience);
                    for ($i = 0; $i < $specialtyCount; $i++) {
                        $allspecialty[$specialty[$i] ?? ""] = $experience[$i] ?? "";
                    }
                }
                $allspecialty = (object)$allspecialty;
                if($jobdetails['vaccinations']){
                    foreach (explode(',', $jobdetails['vaccinations']) as $key => $valuee) {
                        if(isset($allKeywords['Vaccinations']))
                        {
                            foreach ($allKeywords['Vaccinations'] as $value){
                                if($valuee == $value->id){
                                    $allvaccinations[$value->id] = $value->title;
                                }
                            }
                        }
                    }
                }
                if($jobdetails['certificate']){
                    foreach (explode(',', $jobdetails['certificate']) as $key => $valuee) {
                        if(isset($allKeywords['Certification'])){
                            foreach ($allKeywords['Certification'] as $value){
                                if($valuee == $value->id){
                                    $allcertificate[$value->id] = $value->title;
                                }
                            }
                        }
                    }
                }
                $allusstate = States::all()->where('country_code', "US");
                // $alluscities = Cities::all()->where('state_id', $jobdetails['job_state']);
                $alluscities = DB::table('cities')->where('state_id', $jobdetails['job_state'])->get();
                $allvaccinations = (object)$allvaccinations;
                $allcertificate = (object)$allcertificate;
                $data2 .= '
                    <div>
                        <h4><img src="'.asset('public/recruiter/assets/images/counter-left-img.png').'"> Send Offer</h4>
                        <div class="ss-job-view-off-text-fst-dv">
                            <p class="mt-3">On behalf of <a href="">Albus Percival , Hogwarts</a> would like to offer <a href="#">' . $jobdetails['id'] . '</a>
                                to <a href="#">' . $userdetails->first_name . ' ' . $userdetails->last_name . '</a> with the following terms. This offer is only available for the next <a
                                hre="#">6 weeks:</a>
                            </p>
                        </div>
                    </div>
                    <form class="ss-emplor-form-sec" id="send-job-offer">
                    <div class="ss-form-group">
                        <label>Job Name</label>
                        <input type="text" name="job_name" placeholder="Enter job name" value="' . $jobdetails['job_name'] . '">
                        <input type="text" class="d-none" id="job_id" name="job_id" readonly value="' . $jobdetails['id'] . '">
                        <input type="text" class="d-none" id="recruiter_id" name="recruiter_id" readonly value="' . $jobdetails['recruiter_id'] . '">
                        <input type="text" class="d-none" id="worker_user_id" name="worker_user_id" readonly value="' . $userdetails->id . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Type</label>
                        <select name="type" id="type">
                            <option value="">Select Type</option>';
                            if(isset($allKeywords['Type'])){
                                foreach ($allKeywords['Type'] as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['type'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                                }
                            }
                    $data2 .= '
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Terms</label>
                        <select name="terms" id="term">
                            <option value="">Select Terms</option>';
                            if(isset($allKeywords['Terms'])){
                                foreach ($allKeywords['Terms'] as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['terms'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                                }
                            }
                    $data2 .= '
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <h6>Description</h6>
                        <textarea name="description" id="description" placeholder="Enter Job Description" cols="30" rows="2"> ' . $jobdetails['description'] . '</textarea>
                    </div>
                    <div class="ss-form-group">
                        <label>Profession</label>
                        <select name="profession" id="profession" onchange="getSpecialitiesByProfession(this)">
                            <option value="">Select Profession</option>';
                            if(isset($allKeywords['Profession'])){
                                foreach ($allKeywords['Profession'] as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['profession'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                                }
                            }
                    $data2 .= '
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Professional Licensure</label>
                        <select name="job_location" id="professional-licensure">
                            <option value="">Select Professional Licensure</option>';
                            if(isset($allusstate)){
                                foreach ($allusstate as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['job_location'] == $value->name ? 'selected' : '') . '>' .$value->name .'</option>';
                                }
                            }
                    $data2 .= '</select>
                    </div>
                    <div class="ss-form-group ss-prsnl-frm-specialty">
                        <label>Specialty and Experience</label>
                        <div class="ss-speilty-exprnc-add-list speciality-content">

                        </div>
                        <ul>
                            <li class="row">
                                <div class="col-md-6">
                                    <select name="preferred_specialty" class="m-0" id="preferred_specialty" onfocusout="editJob(this)">
                                        <option value="">Select Specialty</option>';
                                            if(isset($allKeywords['Speciality_old'])){
                                                foreach ($allKeywords['Speciality_old'] as $value){
                                                    $data2 .= '<option value="'. $value->id .'">' .$value->title .'</option>';
                                                }
                                            }
                                        $data2 .= '
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="number" name="preferred_experience" id="preferred_experience" placeholder="Enter Experience in years">
                                </div>
                            </li>
                            <li>
                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="add_speciality(this)"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                            </li>
                        </ul>
                    </div>
                    <div class="ss-form-group ss-prsnl-frm-specialty">
                        <label>Vaccinations & Immunizations name</label>
                        <div class="ss-speilty-exprnc-add-list vaccinations-content">

                        </div>
                        <ul>
                            <li>
                                <div>
                                    <select name="vaccinations" class="m-0" id="vaccinations" onfocusout="editJob(this)">
                                        <option value="">Enter Vaccinations & Immunizations name</option>';
                                            if(isset($allKeywords['Vaccinations'])){
                                                foreach ($allKeywords['Vaccinations'] as $value){
                                                    $data2 .= '<option value="'. $value->id .'">' .$value->title .'</option>';
                                                }
                                            }
                                        $data2 .= '
                                    </select>
                                </div>
                            </li>
                            <li>
                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addvacc(this)"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                            </li>
                        </ul>
                    </div>
                    <div class="ss-form-group">
                        <label>Number of references</label>
                        <input type="number" name="number_of_references" placeholder="Enter Number of Reference" value="' . $jobdetails['number_of_references'] . '"  maxlength="9">
                    </div>
                    <div class="ss-form-group">
                        <label>Min title of reference</label>
                        <input type="text" name="min_title_of_reference" placeholder="Enter Min Title of Reference" value="test">
                    </div>
                    <div class="ss-form-group">
                        <label>Recency of reference</label>
                        <input type="number" name="recency_of_reference" placeholder="Enter Recency of Reference" value="' . $jobdetails['recency_of_reference'] . '">
                    </div>
                    <div class="ss-form-group ss-prsnl-frm-specialty">
                        <label>Certifications</label>
                        <div class="ss-speilty-exprnc-add-list certificate-content">

                        </div>
                        <ul>
                            <li>
                                <div>
                                    <select name="certificate" class="m-0" id="certificate" onfocusout="editJob(this)">
                                        <option value="">Select Certification</option>';
                                        if(isset($allKeywords['Certification'])){
                                            foreach ($allKeywords['Certification'] as $value){
                                                $data2 .= '<option value="'. $value->id .'">' .$value->title .'</option>';
                                            }
                                        }
                                    $data2 .= '
                                    </select>
                                </div>
                            </li>
                            <li>
                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addcertifications(this)"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                            </li>
                        </ul>
                    </div>
                    <div class="ss-form-group">
                        <label>Skills checklist</label>
                        <select name="skills" class="skills mb-3" id="skills">
                            <option value="">Enter Skills</option>';
                            if(isset($allKeywords['Skills'])){
                                foreach ($allKeywords['Skills'] as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['skills'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                                }
                            }
                            $data2 .= '
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Urgency</label>
                        <input type="text" name="urgency" placeholder="Enter Urgency" value="' . $jobdetails['urgency'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label># of Positions Available</label>
                        <input type="number" name="position_available" placeholder="Enter # of Positions Available" value="' . $jobdetails['position_available'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>MSP</label>
                        <select name="msp" class="msp mb-3" id="msp" onfocusout="editJob(this)">
                            <option value="">Enter MSP</option>';
                            if(isset($allKeywords['MSP'])){
                                foreach ($allKeywords['MSP'] as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['msp'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                                }
                            }
                            $data2 .= '
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>VMS</label>
                        <select name="vms" class="vms mb-3" id="vms" onfocusout="editJob(this)">
                            <option value="">Enter VMS</option>';
                            if(isset($allKeywords['VMS'])){
                                foreach ($allKeywords['VMS'] as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['vms'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                                }
                            }
                            $data2 .= '
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label># of Submissions in VMS</label>
                        <input type="text" name="submission_of_vms" placeholder="Enter # of Submissions in VMS" value="' . $jobdetails['submission_of_vms'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Block scheduling</label>
                        <select name="block_scheduling" class="block_scheduling mb-3" id="block_scheduling" >
                            <option value="">Enter Block scheduling</option>';
                            if(isset($allKeywords['NSchedulingSystem'])){
                                foreach ($allKeywords['NSchedulingSystem'] as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['block_scheduling'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                                }
                            }
                        $data2 .= '
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Float requirements</label>
                        <select name="float_requirement" class="float_requirement mb-3" id="float_requirement" value="' . $jobdetails['float_requirement'] . '" >
                            <option value="">Enter Float requirements</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Facility Shift Cancellation Policy</label>
                        <select name="facility_shift_cancelation_policy" class="facility_shift_cancelation_policy mb-3" id="facility_shift_cancelation_policy" value="' . $jobdetails['facility_shift_cancelation_policy'] . '" >
                            <option value="">Enter Facility Shift Cancellation Policy</option>';
                            if(isset($allKeywords['AssignmentDuration'])){
                                foreach ($allKeywords['AssignmentDuration'] as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['block_scheduling'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                                }
                            }
                        $data2 .= '
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Contract Termination Policy</label>
                        <select name="contract_termination_policy" class="contract_termination_policy mb-3" id="contract_termination_policy">
                            <option value="">Enter Contract Termination Policy</option>';
                            if(isset($allKeywords['ContractTerminationPolicy'])){
                                foreach ($allKeywords['ContractTerminationPolicy'] as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['contract_termination_policy'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                                }
                            }
                        $data2 .= '
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Traveler Distance From Facility</label>
                        <input type="number" name="traveler_distance_from_facility" placeholder="Enter Traveler Distance From Facility" value="' . $jobdetails['traveler_distance_from_facility'] . '" >
                    </div>
                    <div class="ss-form-group">
                        <label>Facility</label>
                        <select name="facility" class="facility mb-3" id="facility">
                            <option value="">Enter Facility</option>';
                            if(isset($allKeywords['FacilityName'])){
                                foreach ($allKeywords['FacilityName'] as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['facility'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                                }
                            }
                        $data2 .= '
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Facility`s Parent System</label>
                        <input type="text" name="facilitys_parent_system" placeholder="Enter Facility`s Parent System" value="' . $jobdetails['facilitys_parent_system'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Facility Average Rating</label>
                        <input type="number" name="facility_average_rating" placeholder="No Ratings yet" value="' . $jobdetails['facility_average_rating'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Recruiter Average Rating</label>
                        <input type="number" name="recruiter_average_rating" placeholder="No Ratings yet" value="' . $jobdetails['recruiter_average_rating'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Employer Average Rating</label>
                        <input type="number" name="employer_average_rating" placeholder="No Ratings yet" value="' . $jobdetails['employer_average_rating'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Clinical Setting</label>
                        <select name="clinical_setting" class="clinical_setting mb-3" id="clinical_setting">
                            <option value="">What setting do you prefer?</option>';
                            if(isset($allKeywords['SettingType'])){
                                foreach ($allKeywords['SettingType'] as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['clinical_setting'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                                }
                            }
                        $data2 .= '
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Patient ratio</label>
                        <input type="number" name="patient_ratio" placeholder="How many patients can you handle?" value="' . $jobdetails['Patient_ratio'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>EMR</label>
                        <select name="emr" class="emr mb-3" id="emr">
                            <option value="">Enter EMR</option>';
                            if(isset($allKeywords['EMR'])){
                                foreach ($allKeywords['EMR'] as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['emr'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                                }
                            }
                        $data2 .= '
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Unit</label>
                        <input type="number" name="Unit" placeholder="Enter Unit" value="' . $jobdetails['Unit'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Department</label>
                        <input type="text" name="department" placeholder="Enter Department" value="' . $jobdetails['Department'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Bed Size</label>
                        <input type="number" name="bed_size" placeholder="Enter Bed Size" value="' . $jobdetails['Bed_Size'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Trauma Level</label>
                        <input type="number" name="trauma_level" placeholder="Enter Trauma Level" value="' . $jobdetails['Trauma_Level'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Scrub Color</label>
                        <input type="text" name="scrub_color" placeholder="Enter Scrub Color" value="' . $jobdetails['scrub_color'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Facility State Code</label>
                        <select name="job_state" id="facility-state-code" onchange="searchCity(this)">
                            <option value="">Select Facility State Code</option>';
                            if(isset($allusstate)){
                                foreach ($allusstate as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['job_state'] == $value->id ? 'selected' : '') . '>' .$value->name .'</option>';
                                }
                            }
                        $data2 .= '
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Facility City</label>
                        <select name="job_city" id="facility-city">
                            <option value="">Select Facility City</option>';
                            if(isset($alluscities)){
                                foreach ($alluscities as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['job_city'] == $value->id ? 'selected' : '') . '>' .$value->name .'</option>';
                                }
                            }
                        $data2 .= '
                        </select>
                    </div>

                    <div class="ss-form-group">
                        <label>Start Date</label>
<<<<<<< HEAD
                        <input type="date" onfocusout="editJob(this)" name="start_date" placeholder="Select Date" value="' . $jobdetails['start_date'] . '">
=======
                        <input type="date" min="' . date('Y-m-d') . '" name="start_date" placeholder="Select Date" value="' . $jobdetails['start_date'] . '">
>>>>>>> 1174fd451ad65bdd969652464be44233e6fd86c7
                    </div>
                    <div class="ss-form-group">
                        <label>Enter RTO</label>
                        <input type="text" name="rto" placeholder="RTO" value="' . $jobdetails['rto'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Shift Time of Day</label>
                        <select name="preferred_shift" id="shift-of-day">
                            <option value="">Select Shift of Day</option>';
                            if(isset($allKeywords['PreferredShift'])){
                                foreach ($allKeywords['PreferredShift'] as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['preferred_shift'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                                }
                            }
                        $data2 .= '
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Hours/Week</label>
                        <input type="number" name="hours_per_week" placeholder="Enter Hours/Week" value="' . $jobdetails['hours_per_week'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Guaranteed Hours</label>
                        <input type="number" name="guaranteed_hours" placeholder="Enter Guaranteed Hours" value="' . $jobdetails['guaranteed_hours'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Hours/Shift</label>
                        <input type="number" name="hours_shift" placeholder="Enter Hours/Shift" value="' . $jobdetails['hours_shift'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Weeks/Assignment</label>
                        <input type="number" name="preferred_assignment_duration" placeholder="Enter Weeks/Assignment" value="' . $jobdetails['preferred_assignment_duration'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Shifts/Week</label>
                        <input type="number" name="weeks_shift" placeholder="Enter Shifts/Week" value="' . $jobdetails['weeks_shift'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Sign-On Bonus</label>
                        <input type="number" name="sign_on_bonus" placeholder="Enter Sign-On Bonus" value="' . $jobdetails['sign_on_bonus'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Completion Bonus</label>
                        <input type="number" name="completion_bonus" placeholder="Enter Completion Bonus" value="' . $jobdetails['completion_bonus'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Extension Bonus</label>
                        <input type="number" name="extension_bonus" placeholder="Enter Extension Bonus" value="' . $jobdetails['extension_bonus'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Other Bonus</label>
                        <input type="number" name="other_bonus" placeholder="Enter Other Bonus" value="' . $jobdetails['other_bonus'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Referral Bonus</label>
                        <input type="number" name="referral_bonus" placeholder="Enter Referral Bonus" value="' . $jobdetails['referral_bonus'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>401K</label>
                        <select name="four_zero_one_k" id="401k">
                            <option value="">Select</option>';
                            // if(isset($allKeywords['401k'])){
                            //     foreach ($allKeywords['401k'] as $value){
                            //         $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['four_zero_one_k'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                            //     }
                            // }
                        $data2 .= '
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Health Insurance</label>
                        <select name="health_insaurance" id="health-insurance">
                            <option value="">Select</option>'
                            ;
                        //     if(isset($allKeywords['HealthInsurance'])){
                        //         foreach ($allKeywords['HealthInsurance'] as $value){
                        //             $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['health_insaurance'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                        //         }
                        //     }
                        $data2 .=
                        '

                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Dental</label>
                        <select name="dental" id="dental">
                            <option value="">Select</option>';
                            // if(isset($allKeywords['Dental'])){
                            //     foreach ($allKeywords['Dental'] as $value){
                            //         $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['dental'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                            //     }
                            // }
                        $data2 .= '
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Vision</label>
                        <select name="vision" id="vision">
                            <option value="">Select</option>';
                            // if(isset($allKeywords['Vision'])){
                            //     foreach ($allKeywords['Vision'] as $value){
                            //         $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['vision'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                            //     }
                            // }
                        $data2 .= '
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Actual Hourly rate</label>
                        <input type="number" name="actual_hourly_rate" placeholder="Enter Actual Hourly rate" value="' . $jobdetails['actual_hourly_rate'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Feels Like $/hr</label>
                        <input type="number" name="feels_like_per_hour" placeholder="---" value="' . $jobdetails['feels_like_per_hour'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Overtime</label>
                        <select name="overtime" class="overtime mb-3" id="overtime" value="' . $jobdetails['overtime'] . '">
                            <option value="">Enter Overtime</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Holiday</label>
                        <input type="date" name="holiday" placeholder="Select Dates" value="' . $jobdetails['holiday'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>On Call</label>
                        <select name="on_call" class="on_call mb-3" id="on_call" value="' . $jobdetails['on_call'] . '">
                            <option value="">Enter On Call</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Orientation Rate</label>
                        <input type="number" name="orientation_rate" placeholder="Enter Orientation Rate" value="' . $jobdetails['orientation_rate'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Weekly Taxable amount</label>
                        <input type="number" name="weekly_taxable_amount" placeholder="---" value="' . $jobdetails['weekly_taxable_amount'] . '" readonly>
                    </div>
                    <div class="ss-form-group">
                        <label>Weekly non-taxable amount</label>
                        <input type="number" name="weekly_non_taxable_amount" placeholder="---" value="' . $jobdetails['weekly_non_taxable_amount'] . '" readonly>
                    </div>
                    <div class="ss-form-group">
                        <label>Employer Weekly Amount</label>
                        <input type="number" name="employer_weekly_amount" placeholder="---" value="' . $jobdetails['employer_weekly_amount'] . '" readonly>
                    </div>
                    <div class="ss-form-group">
                        <label>Goodwork Weekly Amount</label>
                        <input type="number" name="goodwork_weekly_amount" placeholder="---" value="' . $jobdetails['weekly_taxable_amount'] . '" readonly>
                    </div>
                    <div class="ss-form-group">
                        <label>Total Employer Amount</label>
                        <input type="number" name="total_employer_amount" placeholder="---" value="' . $jobdetails['total_employer_amount'] . '" readonly>
                    </div>
                    <div class="ss-form-group">
                        <label>Total Goodwork Amount</label>
                        <input type="number" name="total_goodwork_amount" placeholder="---" value="' . $jobdetails['total_goodwork_amount'] . '" readonly>
                    </div>
                    <div class="ss-form-group">
                        <label>Total Contract Amount</label>
                        <input type="text" name="total_contract_amount" placeholder="---" value="' . $jobdetails['total_contract_amount'] . '" readonly>
                    </div>
                    <div class="ss-form-group">
                        <label>Goodwork Number</label>
                        <input type="text" name="goodwork_number" placeholder="Unique Key" value="' . $jobdetails['goodwork_number'] . '" readonly>
                    </div>
                    <div class="ss-account-btns">
<<<<<<< HEAD
                        <a class="ss-counter-button d-block text-center" id="submit-job-offer" onclick="applicationType(\'' . $type . '\', \'' . $request->id . '\', \'joballdetails\', \''. $jobdetails['id'] .'\')">Send Offer</a>
                        <button class="counter-save-for-button" id="ss-reject-offer-btn" onclick="applicationType(\'' . $type . '\', \'' . $request->id . '\', \'createdraft\', \''. $jobdetails['id'] .'\')">Save As Draft</button>
=======
                        <a class="ss-counter-button d-block text-center" id="submit-job-offer" onclick="applicationType(\'' . $type . '\', \'' . $request->id . '\', \'joballdetails\', \''. $jobdetails['id'] .'\')">Send Offer</a>
                        <button class="counter-save-for-button" id="ss-reject-offer-btn">Save As Draft</button>
>>>>>>> 82fff7c95dbf688b56d844133e29739f020b432d
                    </div>
                </form>
                ';
            } else if($request->formtype == 'joballdetails'){
                $offerdetails = DB::table('offer_jobs')->where(['job_id' => $request->jobid, 'worker_user_id' => $userdetails->id])->first();
                $data2 .= '
                    <div class="ss-jb-apl-oninfrm-mn-dv">
                        <div>
                            <h4><img src="'.asset('public/recruiter/assets/images/counter-left-img.png').'"> Send Offer</h4>
                            <div class="ss-job-view-off-text-fst-dv">
                                <p class="mt-3">On behalf of <a href="">Albus Percival , Hogwarts</a> would like to offer <a href="#">' . $jobdetails['id'] . '</a>
                                    to <a href="#">' . $userdetails->first_name . ' ' . $userdetails->last_name . '</a> with the following terms. This offer is only available for the next <a hre="#">6 weeks:</a>
                                </p>
                            </div>
                        </div>
                        <div class="ss-jb-apply-on-disc-txt mb-3">
                            <h5>Description</h5>
                            <p>' . $jobdetails['description'] . '</p>
                        </div>
                        <ul class="ss-jb-apply-on-inf-hed-rec row">
                        <li class="col-md-6 mb-3">
                            <span class="mt-3">Diploma</span>
                            <h6>College Diploma</h6>
                        </li>
                        <li class="col-md-6 mb-3">
                            <span class="mt-3">Drivers license</span>
                            <h6>Required</h6>
                        </li>
                        <li class="col-md-6 mb-3">
                            <span class="mt-3">Worked at Facility Before</span>
                            <h6>Have you worked here in the last 18 months?</h6>
                        </li>
                        <li class="col-md-6 mb-3">
                            <span class="mt-3">SS# or SS Card</span>
                            <h6>Last 4 digits of SS#</h6>
                        </li>
                        <li class="col-md-6 mb-3">
                            <p>' . ($nursedetails->worker_ss_number ?? '----' ) . '</p>
                        </li>
                        <li class="col-md-6 mb-3 ' . ($jobdetails->profession != $offerdetails->profession ? 'ss-job-view-off-text-fst-dv' : '') . '">
                            <span class="mt-3">Profession</span>
                            <h6>' . ($offerdetails->profession ?? '----') . '</h6>
                        </li>';

                        if(isset($jobdetails->specialty)){
                            foreach (explode(",", $offerdetails->specialty) as $key => $value) {
                                if(isset($value)){

                                    $data2 .= '
                                    <div class="col-md-6 mb-3 ' . ($jobdetails->specialty != $offerdetails->specialty ? 'ss-job-view-off-text-fst-dv' : '') . '">
                                        <span class="mt-3">Specialty</span>
                                        <h6>' . $value . ' Required</h6>
                                    </div>
                                    ';
                                }
                            }
                        }
                        $data2 .= '
                        <div class="col-md-6 mb-3 ' . ($jobdetails->job_state != $offerdetails->job_state ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                            <span class="mt-3">Professional Licensure</span>
                            <h6>' . ($offerdetails->job_state ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 mb-3 ' . ($jobdetails->preferred_experience != $offerdetails->preferred_experience ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                            <span class="mt-3">Experience</span>
                            <h6>' . ($offerdetails->preferred_experience ?? '----') . ' Years </h6>
                        </div>';
                        if(isset($jobdetails->vaccinations)){
                            foreach (explode(",", $jobdetails->vaccinations) as $key => $value) {
                                if(isset($value)){
                                    $data2 .= '
                                    <div class="col-md-6 mb-3 ' . ($jobdetails->vaccinations != $offerdetails->vaccinations ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                        <span class="mt-3">Vaccinations & Immunizations</span>
                                        <h6>' . $value . ' Required</h6>
                                    </div>
                                    ';
                                }
                            }
                        }
                        if(isset($jobdetails->number_of_references)){
                            foreach (explode(",", $jobdetails->number_of_references) as $key => $value) {
                                if(isset($value)){
                                    $data2 .= '
                                    <div class="col-md-6 mb-3 ' . ($jobdetails->number_of_references != $offerdetails->number_of_references ? 'ss-job-view-off-text-fst-dv' : '') . '  ">
                                        <span class="mt-3">References</span>
                                        <h6>' . $value . ' references</h6>
                                    </div>
                                    ';
                                }
                            }
                        }
                        $data2 .= '
                        <div class="col-md-6 mb-3 ' . ($jobdetails->certificate != $offerdetails->certificate ? 'ss-job-view-off-text-fst-dv' : '') . '  mb-3">';
                        if(isset($jobdetails->certificate)){
                            foreach (explode(",", $nursedetails->certificate) as $key => $value) {
                                if(isset($value)){
                                    $data2 .= '
                                    <span class="mt-3">Certifications</span>
                                    <div class="col-md-6 mb-3 ' . ($jobdetails->certificate != $offerdetails->certificate ? 'ss-job-view-off-text-fst-dv' : '') . '  ">
                                        <h6>' . $value . ' Required</h6>
                                    </div>
                                    ';
                                }
                            }
                        }
                        $data2 .= '
                        </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->skills != $offerdetails->skills ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Skills checklist</span>
                                <h6>' . ($offerdetails->skills ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="mt-3">Eligible to work in the US</span>
                                <h6>Required</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->urgency != $offerdetails->urgency ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Urgency</span>
                                <h6>' . ($offerdetails->urgency ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->position_available != $offerdetails->position_available ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3"># of Positions Available</span>
                                <h6>' . ($offerdetails->position_available ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->msp != $offerdetails->msp ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">MSP</span>
                                <h6>' . ($offerdetails->msp ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->vms != $offerdetails->vms ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">VMS</span>
                                <h6>' . ($offerdetails->vms ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->block_scheduling != $offerdetails->block_scheduling ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Block scheduling</span>
                                <h6>' . ($offerdetails->block_scheduling ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->float_requirement != $offerdetails->float_requirement ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Float requirements</span>
                                <h6>' . ($offerdetails->float_requirement ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->facility_shift_cancelation_policy != $offerdetails->facility_shift_cancelation_policy ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Facility Shift Cancellation Policy</span>
                                <h6>' . ($offerdetails->facility_shift_cancelation_policy ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->contract_termination_policy != $offerdetails->contract_termination_policy ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Contract Termination Policy</span>
                                <h6>' . ($offerdetails->contract_termination_policy ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->traveler_distance_from_facility != $offerdetails->traveler_distance_from_facility ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Traveler Distance From Facility</span>
                                <h6>' . ($offerdetails->traveler_distance_from_facility ?? '----') . ' miles Maximum</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->facility != $offerdetails->facility ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Facility</span>
                                <h6>' . ($offerdetails->facility ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->facilitys_parent_system != $offerdetails->facilitys_parent_system ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Facility`s Parent System</span>
                                <h6>' . ($offerdetails->facilitys_parent_system ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->facility_average_rating != $offerdetails->facility_average_rating ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Facility Average Rating</span>
                                <h6>' . ($offerdetails->facility_average_rating ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->recruiter_average_rating != $offerdetails->recruiter_average_rating ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Recruiter Average Rating</span>
                                <h6>' . ($offerdetails->recruiter_average_rating ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->employer_average_rating != $offerdetails->employer_average_rating ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Employer Average Rating</span>
                                <h6>' . ($offerdetails->employer_average_rating ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->clinical_setting != $offerdetails->clinical_setting ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Clinical Setting</span>
                                <h6>' . ($offerdetails->clinical_setting ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->Patient_ratio != $offerdetails->Patient_ratio ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Patient ratio</span>
                                <h6>' . ($offerdetails->Patient_ratio ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->emr != $offerdetails->emr ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">EMR</span>
                                <h6>' . ($offerdetails->emr ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->Unit != $offerdetails->Unit ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Unit</span>
                                <h6>' . ($offerdetails->Unit ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->Department != $offerdetails->Department ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Department</span>
                                <h6>' . ($offerdetails->Department ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->Bed_Size != $offerdetails->Bed_Size ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Bed Size</span>
                                <h6>' . ($offerdetails->Bed_Size ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->Trauma_Level != $offerdetails->Trauma_Level ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Trauma Level</span>
                                <h6>' . ($offerdetails->Trauma_Level ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->scrub_color != $offerdetails->scrub_color ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Scrub Color</span>
                                <h6>' . ($offerdetails->scrub_color ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->job_city != $offerdetails->job_city ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Facility City</span>
                                <h6>' . ($offerdetails->job_city ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->job_state != $offerdetails->job_state ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Facility State Code</span>
                                <h6>' . ($offerdetails->job_state ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="mt-3">Interview Dates</span>
                                <h6>' . $nursedetails->worker_interview_dates . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->start_date != $offerdetails->start_date ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Start Date</span>
                                <h6>' . ($offerdetails->start_date ? $jobdetails->start_date : 'As Soon As Possible') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->rto != $offerdetails->rto ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">RTO</span>
                                <h6>' . ($offerdetails->rto ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->preferred_shift != $offerdetails->preferred_shift ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Shift Time of Day</span>
                                <h6>' . ($offerdetails->preferred_shift ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->hours_per_week != $offerdetails->hours_per_week ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Hours/Week</span>
                                <h6>' . ($offerdetails->hours_per_week ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->guaranteed_hours != $offerdetails->guaranteed_hours ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Guaranteed Hours</span>
                                <h6>' . ($offerdetails->guaranteed_hours ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->preferred_assignment_duration != $offerdetails->preferred_assignment_duration ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Hours/Shift</span>
                                <h6>' . ($offerdetails->preferred_assignment_duration ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->preferred_assignment_duration != $offerdetails->preferred_assignment_duration ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Weeks/Assignment</span>
                                <h6>' . ($offerdetails->preferred_assignment_duration ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->weeks_shift != $offerdetails->weeks_shift ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Shifts/Week</span>
                                <h6>' . ($offerdetails->weeks_shift ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->referral_bonus != $offerdetails->referral_bonus ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Referral Bonus</span>
                                <h6>$' . ($offerdetails->referral_bonus ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->sign_on_bonus != $offerdetails->sign_on_bonus ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Sign-On Bonus</span>
                                <h6>$' . ($offerdetails->sign_on_bonus ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->completion_bonus != $offerdetails->completion_bonus ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Completion Bonus</span>
                                <h6>$' . ($offerdetails->completion_bonus ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->extension_bonus != $offerdetails->extension_bonus ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Extension Bonus</span>
                                <h6>$' . ($offerdetails->extension_bonus ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->other_bonus != $offerdetails->other_bonus ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Other Bonus</span>
                                <h6>$' . ($offerdetails->other_bonus ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->four_zero_one_k != $offerdetails->four_zero_one_k ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">401K</span>
                                <h6>' . ($offerdetails->four_zero_one_k ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->health_insaurance != $offerdetails->health_insaurance ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Health Insurance</span>
                                <h6>' . ($offerdetails->health_insaurance ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->dental != $offerdetails->dental ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Dental</span>
                                <h6>' . ($offerdetails->dental ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->vision != $offerdetails->vision ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Vision</span>
                                <h6>' . ($offerdetails->vision ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->actual_hourly_rate != $offerdetails->actual_hourly_rate ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Actual Hourly rate</span>
                                <h6>' . ($offerdetails->actual_hourly_rate ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->feels_like_per_hour != $offerdetails->feels_like_per_hour ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Feels Like $/hr</span>
                                <h6>' . ($offerdetails->feels_like_per_hour ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->overtime != $offerdetails->overtime ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Overtime</span>
                                <h6>' . ($offerdetails->overtime ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->holiday != $offerdetails->holiday ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Holiday</span>
                                <h6>' . ($offerdetails->holiday ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->on_call != $offerdetails->on_call ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">On Call</span>
                                <h6>' . ($offerdetails->on_call ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->call_back != $offerdetails->call_back ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Call Back</span>
                                <h6>' . ($offerdetails->call_back ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3 ' . ($jobdetails->orientation_rate != $offerdetails->orientation_rate ? 'ss-job-view-off-text-fst-dv' : '') . ' ">
                                <span class="mt-3">Orientation Rate</span>
                                <h6>' . ($offerdetails->orientation_rate ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="mt-3">Weekly Taxable amount</span>
                                <h6>' . ($offerdetails->weekly_taxable_amount ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="mt-3">Employer Weekly Amount</span>
                                <h6>' . ($offerdetails->employer_weekly_amount ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="mt-3">Weekly non-taxable amount</span>
                                <h6>' . ($offerdetails->weekly_non_taxable_amount ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="mt-3">Goodwork Weekly Amount</span>
                                <h6>' . ($offerdetails->weekly_taxable_amount ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p>You only have 5 days left before your rate drops from 5% to 2%</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="mt-3">Total Employer Amount</span>
                                <h6>' . ($offerdetails->total_employer_amount ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="mt-3">Total Goodwork Amount</span>
                                <h6>' . ($offerdetails->total_goodwork_amount ?? '----') . '</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="mt-3">Total Contract Amount</span>
                                <h6>' . ($offerdetails->total_contract_amount ?? '----') . '</h6>
                            </div>
                            <div class="col-md-12 mb-2">
                                <span class="mt-3">Goodwork Number</span>
                                <h6>' . ($offerdetails->goodwork_number ?? '----') . '</h6>
                            </div>
                        </ul>
                    </div>
                    <div class="ss-counter-buttons-div">
                        <button class="ss-counter-button" onclick="offerSend(\'' . $offerdetails->id . '\', \''. $jobdetails->id .'\', \'offersend\')">Send Offer</button>
                        <button class="counter-save-for-button" onclick="offerSend(\'' . $offerdetails->id . '\', \''. $jobdetails->id .'\', \'rejectcounter\')">Reject Counter</button>
                    </div>
                    ';
            } else {
                $data2 .= '
                    <ul class="ss-cng-appli-hedpfl-ul">
                        <li>
                            <span>' . $offerdetails['nurse_id'] . '</span>
                            <h6>
                                <img src="' . asset('public/images/nurses/profile/' . $userdetails->image) . '" onerror="this.onerror=null;this.src=' . '\'' . asset('public/frontend/img/profile-pic-big.png') . '\'' . ';" id="preview" width="50px" height="50px" style="object-fit: cover;" class="rounded-3" alt="Profile Picture">
                                ' . $userdetails->first_name . ' ' . $userdetails->last_name . '
                            </h6>
                        </li>
                        <li>
                            <a href="' . route('recruiter-messages') . '" class="rounded-pill ss-apply-btn py-2 border-0 px-4" onclick="chatNow(\'' . $offerdetails['nurse_id'] . '\')">Chat Now</a>
                        </li>
                    </ul>
                    <div class="ss-appli-cng-abt-inf-dv">
                        <h5>Applicant Information</h5>
                        <p>' . $userdetails->about_me . '</p>
                    </div>
                    <div class="ss-applicatio-infor-texts-dv">
                        <ul class="row">
                            <li class="col-md-6">
                                <p>Profession</p>
                                <h6>' . ($userdetails->highest_nursing_degree ?? '<u onclick="askWorker(this, \'highest_nursing_degree\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                            </li>
                            <li class="col-md-6">
                                <p>Specialty</p>
                                <h6 class="mb-3">' . ($nursedetails['specialty'] ?? '<u onclick="askWorker(this, \'specialty\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                            </li>
                            <li class="col-md-6">
                                <p>Professional Licensure</p>
                                <h6 class="mb-3">' . ($nursedetails['nursing_license_state'] ?? '<u onclick="askWorker(this, \'nursing_license_state\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</h6>
                            </li>
                            <li class="col-md-6">
                                <p>Experience</p>
                                <h6 class="mb-3">' . (array_sum(explode(",", $nursedetails['experience'])) ? array_sum(explode(",", $nursedetails['experience'])) : 0) . 'years</h6>
                            </li>
                            ';
                            if(isset($nursedetails['worker_vaccination'])){
                                foreach (explode(",", trim($nursedetails['worker_vaccination'], "[]")) as $key => $value) {
                                    if(isset($value)){

                                        $data2 .= '
                                            <li class="col-md-6">
                                                <p>Vaccinations & Immunizations name</p>
                                                <h6 class="mb-3">' . ($value) . '</h6>
                                            </li>';
                                    }
                                }
                            }
                            $data2 .= '
                                <li class="col-md-6">
                                    <p>number of references</p>
                                    <h6 class="mb-3">' . ($nursedetails['worker_number_of_references'] ?? '<u onclick="askWorker(this, \'worker_number_of_references\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>min title of reference</p>
                                    <h6 class="mb-3">' . ($nursedetails['min_title_of_reference'] ?? '<u onclick="askWorker(this, \'min_title_of_reference\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Recency of reference</p>
                                    <h6 class="mb-3">' . ($nursedetails['recency_of_reference'] ?? '<u onclick="askWorker(this, \'recency_of_reference\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>certification name</p>
                                    <h6 class="mb-3">' . ($nursedetails['other_certificate_name'] ?? '<u onclick="askWorker(this, \'other_certificate_name\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Skills checklist</p>
                                    <h6 class="mb-3">' . ($nursedetails['skills'] ?? '<u onclick="askWorker(this, \'skills\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Eligible to work in the US</p>
                                    <h6 class="mb-3">' . ($nursedetails['eligible_work_in_us'] ?? '<u onclick="askWorker(this, \'eligible_work_in_us\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Urgency</p>
                                    <h6 class="mb-3">' . ($nursedetails['worker_urgency'] ?? '<u onclick="askWorker(this, \'worker_urgency\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Traveler Distance From Facility</p>
                                    <h6 class="mb-3">' . ($nursedetails['distance_from_your_home'] ?? '<u onclick="askWorker(this, \'distance_from_your_home\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Facility</p>
                                    <h6 class="mb-3">' . ($nursedetails['worked_at_facility_before'] ?? '<u onclick="askWorker(this, \'worked_at_facility_before\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Skills checklist</p>
                                    <h6 class="mb-3">' . ($nursedetails['skills_checklists'] ?? '<u onclick="askWorker(this, \'skills_checklists\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Location</p>
                                    <h6 class="mb-3">' . ($nursedetails['state'] ?? '<u onclick="askWorker(this, \'state\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Shift</p>
                                    <h6 class="mb-3">' . ($nursedetails['worker_shift_time_of_day'] ?? '<u onclick="askWorker(this, \'worker_shift_time_of_day\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Distance from your home</p>
                                    <h6 class="mb-3">' . ($nursedetails['distance_from_your_home'] ?? '<u onclick="askWorker(this, \'distance_from_your_home\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Facilities you`ve worked at</p>
                                    <h6 class="mb-3">' . ($nursedetails['facilities_you_like_to_work_at'] ?? '<u onclick="askWorker(this, \'facilities_you_like_to_work_at\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Facility City</p>
                                    <h6 class="mb-3">' . ($nursedetails['worker_facility_city'] ?? '<u onclick="askWorker(this, \'worker_facility_city\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Start Date</p>
                                    <h6 class="mb-3">' . ($nursedetails['worker_start_date'] ?? '<u onclick="askWorker(this, \'worker_start_date\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>RTO</p>
                                    <h6 class="mb-3">' . ($offerdetails['rto'] ?? '<u onclick="askWorker(this, \'rto\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Shift Time of Day</p>
                                    <h6 class="mb-3">' . ($nursedetails['worker_shift_time_of_day'] ?? '<u onclick="askWorker(this, \'worker_shift_time_of_day\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Weeks/Assignment</p>
                                    <h6 class="mb-3">' . ($offerdetails['worker_weeks_assignment'] ?? '<u onclick="askWorker(this, \'worker_weeks_assignment\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-6">
                                    <p>Employer Weekly Amount</p>
                                    <h6 class="mb-3">' . ($nursedetails['worker_employer_weekly_amount'] ?? '<u onclick="askWorker(this, \'worker_employer_weekly_amount\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-12">
                                    <p>Goodwork Number</p>
                                    <h6 class="mb-3">' . ($nursedetails['goodwork_number'] ?? '<u onclick="askWorker(this, \'goodwork_number\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</h6>
                                </li>
                                <li class="col-md-12 ss-chng-appli-slider-mn-dv">
                                    <p>Job Applied(' . $jobappliedcount . ')</p>
                            </ul>
                        </div>
                        <div class="ss-chng-appli-slider-mn-dv">

                        <div class="' . ($jobappliedcount > 1 ? 'owl-carousel application-job-slider-owl' : '') . ' application-job-slider">

                    ';
                    foreach ($jobapplieddetails as $key => $value) {
                        if ($value) {
                            $appliednursecount = Offer::where(['status' => $type, 'job_id' => $value->job_id])->count();
                            $jobdetails = Job::where(['id' => $value->job_id])->first();
                            // $route = route('recruiter-single-job', ['id' => $value->job_id]);
                            $data2 .= '
                                <div class="ss-chng-appli-slider-sml-dv" onclick="applicationType(\'' . $type . '\', \'' . $value->id . '\', \'useralldetails\', \''. $value->job_id .'\')">
                                    <ul class="ss-cng-appli-slid-ul1">
                                        <li class="d-flex">
                                            <p>' . $jobdetails->terms . '</p>
                                            <span>' . $appliednursecount . ' Workeds Applied</span>
                                        </li>
                                        <li>Posted on ' . (new DateTime($jobdetails->start_date))->format('M d, Y') . '</li>
                                    </ul>
                                    <h4>' . $jobdetails->job_name . '</h4>
                                    <ul class="ss-cng-appli-slid-ul2 d-block">
                                        <li class="d-inline-block">' . $jobdetails->job_location . ', ' . $jobdetails->job_state . '</li>
                                        <li class="d-inline-block">' . $jobdetails->preferred_shift . 'preferred_shift</li>
                                        <li class="d-inline-block">' . $jobdetails->preferred_days_of_the_week . ' wks</li>
                                    </ul>
                                    <ul class="ss-cng-appli-slid-ul3">
                                        <li><span>' . $jobdetails->facility . '</span></li>
                                        <li><h6>$' . $jobdetails->hours_per_week . '/wk</h6></li>
                                    </ul>
                                    <h5>' . $value->job_id . '</h5>
                                </div>
                            ';
                        }
                    }
                    $data2 .= '</div>
                    </div>
                    </div>';
                if ($data2 == "") {
                    $data2 = '<div class="text-center"><span>Data Not found</span></div>';
                }
            }
        }

        $responseData = [
            'applicationlisting' => $data,
            'applicationdetails' => $data2,
            'allspecialty' => $allspecialty,
            'allcertificate' => $allcertificate,
            'allvaccinations' => $allvaccinations,
        ];

        return response()->json($responseData);
    }
    public function updateApplicationStatus(Request $request){
        $type = $request->type;
        $id = $request->id;
        $formtype = $request->formtype;
        $jobid = $request->jobid;

        if (isset($request->jobid)) {
            $jobid = $request->jobid;
            $job = Offer::where(['job_id' => $jobid, 'id'=> $id ])->update(['status' => $formtype]);
            if($job){
                return response()->json(['message' => 'Update Successfully']);
            }else{
                return response()->json(['message' => 'Something went wrong! Please check']);
            }
        } else {
            return response()->json(['message' => 'Something went wrong! Please check']);
        }
    }
    public function sendJobOffer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'id' => 'required',
            // 'nurse_id' => 'required',
            'worker_user_id' => 'required',
            'job_id' => 'required',
        ]);
        $responseData = [];
        if ($validator->fails()) {
            $responseData = [
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ];
        } else {



            $offerLists = Offer::where('id', $request->id)->first();
            // $nurse = Nurse::where('id', $request->nurse_id)->first();
            $user = user::where('id', $request->worker_user_id)->first();
            $job_data = Job::where('id', $request->job_id)->first();
            $update_array["job_name"] = isset($request->job_name) ? $request->job_name : $job_data->job_name;
            $update_array["type"] = isset($request->type) ? $request->type : $job_data->type;
            $update_array["terms"] = isset($request->terms) ? $request->terms : $job_data->terms;
            $update_array["profession"] = isset($request->profession) ? $request->profession : $job_data->profession;
            $update_array["preferred_specialty"] = isset($request->preferred_specialty) ? $request->preferred_specialty : $job_data->preferred_specialty;
            $update_array["preferred_experience"] = isset($request->preferred_experience) ? $request->preferred_experience : $job_data->preferred_experience;
            $update_array["number_of_references"] = isset($request->number_of_references) ? $request->number_of_references : $job_data->number_of_references;
            $update_array["min_title_of_reference"] = isset($request->min_title_of_reference) ? $request->min_title_of_reference : $job_data->min_title_of_reference;
            $update_array["recency_of_reference"] = isset($request->recency_of_reference) ? $request->recency_of_reference : $job_data->recency_of_reference;
            $update_array["skills"] = isset($request->skills) ? $request->skills : $job_data->skills;
            $update_array["urgency"] = isset($request->urgency) ? $request->urgency : $job_data->urgency;
            $update_array["msp"] = isset($request->msp) ? $request->msp : $job_data->msp;
            $update_array["vms"] = isset($request->vms) ? $request->vms : $job_data->vms;
            $update_array["facility"] = isset($request->facility) ? $request->facility : $job_data->facility;
            $update_array["job_location"] = isset($request->job_location) ? $request->job_location : $job_data->job_location;
            $update_array["vaccinations"] = isset($request->vaccinations) ? $request->vaccinations : $job_data->vaccinations;
            $update_array["certificate"] = isset($request->certificate) ? $request->certificate : $job_data->certificate;
            $update_array["position_available"] = isset($request->position_available) ? $request->position_available : $job_data->position_available;
            $update_array["submission_of_vms"] = isset($request->submission_of_vms) ? $request->submission_of_vms : $job_data->submission_of_vms;
            $update_array["block_scheduling"] = isset($request->block_scheduling) ? $request->block_scheduling : $job_data->block_scheduling;
            $update_array["float_requirement"] = isset($request->float_requirement) ? $request->float_requirement : $job_data->float_requirement;
            $update_array["facility_shift_cancelation_policy"] = isset($request->facility_shift_cancelation_policy) ? $request->facility_shift_cancelation_policy : $job_data->facility_shift_cancelation_policy;
            $update_array["contract_termination_policy"] = isset($request->contract_termination_policy) ? $request->contract_termination_policy : $job_data->contract_termination_policy;
            $update_array["traveler_distance_from_facility"] = isset($request->traveler_distance_from_facility) ? $request->traveler_distance_from_facility : $job_data->traveler_distance_from_facility;
            $update_array["job_id"] = isset($request->job_id) ? $request->job_id : $job_data->job_id;
            $update_array["recruiter_id"] = isset($request->recruiter_id) ? $request->recruiter_id : $job_data->recruiter_id;
            $update_array["worker_user_id"] = isset($request->worker_user_id)?$request->worker_user_id:'';
            $update_array["compact"] = isset($request->compact) ? $request->compact : $job_data->compact;
            $update_array["facility_id"] = isset($request->facility_id) ? $request->facility_id : $job_data->facility_id;
            $update_array["clinical_setting"] = isset($request->clinical_setting) ? $request->clinical_setting : $job_data->clinical_setting;
            $update_array["Patient_ratio"] = isset($request->Patient_ratio) ? $request->Patient_ratio : $job_data->Patient_ratio;
            $update_array["emr"] = isset($request->emr) ? $request->emr : $job_data->emr;
            $update_array["Unit"] = isset($request->Unit) ? $request->Unit : $job_data->Unit;
            $update_array["Department"] = isset($request->Department) ? $request->Department : $job_data->Department;
            $update_array["Bed_Size"] = isset($request->Bed_Size) ? $request->Bed_Size : $job_data->Bed_Size;
            $update_array["Trauma_Level"] = isset($request->Trauma_Level) ? $request->Trauma_Level : $job_data->Trauma_Level;
            $update_array["scrub_color"] = isset($request->scrub_color) ? $request->scrub_color : $job_data->scrub_color;
            $update_array["start_date"] = isset($request->start_date) ? $request->start_date : $job_data->start_date;
            $update_array["as_soon_as"] = isset($request->as_soon_as) ? $request->as_soon_as : $job_data->as_soon_as;
            $update_array["rto"] = isset($request->rto) ? $request->rto : $job_data->rto;
            $update_array["preferred_shift"] = isset($request->preferred_shift) ? $request->preferred_shift : $job_data->preferred_shift;
            $update_array["hours_per_week"] = isset($request->hours_per_week) ? $request->hours_per_week : $job_data->hours_per_week;
            $update_array["guaranteed_hours"] = isset($request->guaranteed_hours) ? $request->guaranteed_hours : $job_data->guaranteed_hours;
            $update_array["hours_shift"] = isset($request->hours_shift) ? $request->hours_shift : $job_data->hours_shift;
            $update_array["weeks_shift"] = isset($request->weeks_shift) ? $request->weeks_shift : $job_data->weeks_shift;
            $update_array["preferred_assignment_duration"] = isset($request->preferred_assignment_duration) ? $request->preferred_assignment_duration : $job_data->preferred_assignment_duration;
            $update_array["referral_bonus"] = isset($request->referral_bonus) ? $request->referral_bonus : $job_data->referral_bonus;
            $update_array["sign_on_bonus"] = isset($request->sign_on_bonus) ? $request->sign_on_bonus : $job_data->sign_on_bonus;
            $update_array["completion_bonus"] = isset($request->completion_bonus) ? $request->completion_bonus : $job_data->completion_bonus;
            $update_array["extension_bonus"] = isset($request->extension_bonus) ? $request->extension_bonus : $job_data->extension_bonus;
            $update_array["other_bonus"] = isset($request->other_bonus) ? $request->other_bonus : $job_data->other_bonus;
            $update_array["four_zero_one_k"] = isset($request->four_zero_one_k) ? $request->four_zero_one_k : $job_data->four_zero_one_k;
            $update_array["health_insaurance"] = isset($request->health_insaurance) ? $request->health_insaurance : $job_data->health_insaurance;
            $update_array["dental"] = isset($request->dental) ? $request->dental : $job_data->dental;
            $update_array["vision"] = isset($request->vision) ? $request->vision : $job_data->vision;
            $update_array["actual_hourly_rate"] = isset($request->actual_hourly_rate) ? $request->actual_hourly_rate : $job_data->actual_hourly_rate;
            $update_array["overtime"] = isset($request->overtime) ? $request->overtime : $job_data->overtime;
            $update_array["holiday"] = isset($request->holiday) ? $request->holiday : $job_data->holiday;
            $update_array["on_call"] = isset($request->on_call) ? $request->on_call : $job_data->on_call;
            $update_array["orientation_rate"] = isset($request->orientation_rate) ? $request->orientation_rate : $job_data->orientation_rate;
            $update_array["weekly_non_taxable_amount"] = isset($request->weekly_non_taxable_amount) ? $request->weekly_non_taxable_amount : $job_data->weekly_non_taxable_amount;
            $update_array["description"] = isset($request->description) ? $request->description : $job_data->description;
            $update_array["weekly_taxable_amount"] = 0;
            $update_array["employer_weekly_amount"] = 0;
            $update_array["goodwork_weekly_amount"] = 0;
            $update_array["total_employer_amount"] = 0;
            $update_array["total_goodwork_amount"] = 0;
            $update_array["total_contract_amount"] = 0;
            if($update_array["hours_per_week"] == 0){
                $update_array["feels_like_per_hour"] = 0;
            }else{
                $update_array["feels_like_per_hour"] = $update_array["employer_weekly_amount"]/$update_array["hours_per_week"];
            }
            $update_array['weekly_pay'] = isset($job_data->weekly_pay)?$job_data->weekly_pay:'';
            $update_array["facilitys_parent_system"] = isset($job_data->facilitys_parent_system)?$job_data->facilitys_parent_system:'';
            $update_array["facility_average_rating"] = isset($job_data->facility_average_rating)?$job_data->facility_average_rating:'';
            $update_array["recruiter_average_rating"] = isset($job_data->recruiter_average_rating)?$job_data->recruiter_average_rating:'';
            $update_array["employer_average_rating"] = isset($job_data->employer_average_rating)?$job_data->employer_average_rating:'';
            $update_array["job_city"] = isset($job_data->job_city)?$job_data->job_city:'';
            $update_array["job_state"] = isset($job_data->job_state)?$job_data->job_state:'';
            $update_array["is_draft"] = isset($request->is_draft)?$request->is_draft:'1';
            $update_array["is_counter"] = isset($request->counterstatus) ? $request->counterstatus:'0';


            /* create job */
            $update_array["created_by"] = (isset($job_data->recruiter_id) && $job_data->recruiter_id != "") ? $job_data->recruiter_id : "";

            $offerexist = DB::table('offer_jobs')->where(['job_id' => $request->job_id, 'worker_user_id' => $request->worker_user_id, 'recruiter_id' => $request->recruiter_id])->first();

            if($offerexist){
                $job = DB::table('offer_jobs')->where(['job_id' => $request->job_id, 'worker_user_id' => $request->worker_user_id, 'recruiter_id' => $request->recruiter_id])->update($update_array);
            }else{
                $job = DB::table('offer_jobs')->insert($update_array);
            }
            /* create job */
            if ($job) {
                $responseData = [
                    'status' => 'success',
                    'message' => 'Send Offer Job created successfully',
                ];
            } else {
                $responseData = [
                    'status' => 'success',
                    'message' => 'Job Offer Already Send',
                ];
            }
        }
        return response()->json($responseData);
    }
    public function sendJobOfferRecruiter(Request $request){
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
            if($request->type == "rejectcounter"){
                $update_array["is_counter"] = '0';
            }
            $update_array["is_draft"] = '0';
            $job = DB::table('offer_jobs')->where(['id' => $request->id])->first();
            if($job->is_draft == "0"){
                $responseData = [
                    'status' => 'success',
                    'message' => 'Job Offer Already Send',
                ];
            }else{
                $job = DB::table('offer_jobs')->where(['id' => $request->id])->update($update_array);
                if ($job) {
                    $responseData = [
                        'status' => 'success',
                        'message' => 'Job Offer Send successfully',
                    ];
                } else {
                    $responseData = [
                        'status' => 'error',
                        'message' => 'Somthing went wrong!',
                    ];
                }
            }
            return response()->json($responseData);
        }
    }
}
