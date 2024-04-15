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
use URL;
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
        $statusCountsQuery = Offer::whereIn('status', $statusList)->select(\DB::raw('status, count(*) as count'))->groupBy('status')->get();
        foreach ($statusCountsQuery as $statusCount) {
            if ($statusCount) {
                $statusCounts[$statusCount->status] = $statusCount->count;
            } else {
                $statusCounts[$statusCount->status] = 0;
            }
        }
        $status_count_draft = Offer::where('is_draft', true)->count();
        return view('recruiter::recruiter/applicationjourney', compact('statusCounts', 'status_count_draft'));
    }

    public function getApplicationListing(Request $request)
    {
      
        $type = $request->type;
        //return response()->json(['type'=>$type]); 
        $allspecialty = [];
        if($type == 'Draft'){
            $offerLists = Offer::where('is_draft', true)->get();
        }else{
            $offerLists = Offer::where('status', $type)->get();
        }
        
        if (0 >= count($offerLists)) {
            $responseData = [
                'applicationlisting' => '<div class="text-center"><span>No Application</span></div>',
                'applicationdetails' => '<div class="text-center"><span>Data Not found</span></div>',
            ];

            return response()->json($responseData);
        }

        $data = '';
        $data2 = '';
        // str_contains($nurse['worker_shift_time_of_day'], 'day') ? 'day' : '' . ' ' . str_contains($nurse['worker_shift_time_of_day'], 'night') ? 'night' : ''
        foreach ($offerLists as $key => $value) {
            if ($value) {
                $nurse = Nurse::where('id', $value->worker_user_id)->first();
                $user = user::where('id', $nurse->user_id)->first();
                $data .=
                    '
                    <div class="ss-job-prfle-sec ' .
                    ($request->id == $value->id ? 'active' : '') .
                    '" id="ss-expl-applicion-div-box" onclick="applicationType(\'' .
                    $type .
                    '\', \'' .
                    $value->id .
                    '\', \'userdetails\')">
                        <div class="ss-job-id-no-name">
                            <ul>
                                <li class="w-50">
                                    <span href="" class="mb-3">' .
                    $value->worker_user_id .
                    '</span>
                                </li>
                                <li class="w-50">
                                    <p>Recently Added</p>
                                </li>
                            </ul>
                        </div>
                        <ul>
                            <li>
                                <img src="' .
                    URL::asset('public/images/nurses/profile/' . $user['image']) .
                    '" onerror="this.onerror=null;this.src=' .
                    '\'' .
                    URL::asset('frontend/img/profile-pic-big.png') .
                    '\'' .
                    ';" id="preview" width="50px" height="50px" style="object-fit: cover;" class="rounded-3" alt="Profile Picture">
                            </li>
                            <li>
                                <h6></h6>
                            </li>
                            <li>
                                <h6 class="job-title">' .
                    $user['first_name'] .
                    ' ' .
                    $user['last_name'] .
                    '</h6>
                            </li>
                        </ul>
                        <ul class="ss-expl-applicion-ul2">';
                if (isset($nurse['facility_hourly_pay_rate'])) {
                    $data .= '<li"><a href="#"> Hourely pay rate ' . $nurse['facility_hourly_pay_rate'] . ' $</a></li>';
                }
                // new changes
                if (isset($nurse['city']) && isset($nurse['state'])) {
                    $data .= '<li"><a href="#">' . $nurse['city'] . ',' . $nurse['state'] . '</a></li>';
                }

                if (isset($nurse['mu_specialty'])) {
                    $data .= '<li"><a href="#">' . $nurse['mu_specialty'] . '</a></li>';
                }
                //end new changes
                if (isset($nurse['specialty'])) {
                    $data .= '<li"><a href="#">' . $nurse['specialty'] . '</a></li>';
                }
                if (isset($nurse['worker_shift_time_of_day'])) {
                    $data .= '<li"><a href="#">' . $nurse['worker_shift_time_of_day'] . '</a></li>';
                }
                $data .= '
                        </ul>
                </div>';
            }
        }
        if ($data == '') {
            $data = '<div class="text-center"><span>No Application</span></div>';
        }
        $offerdetails = '';
        $jobdetails = '';
        if (isset($request->id)) {
            $offerdetails = Offer::where(['status' => $type, 'id' => $request->id])->first();
        } else {
            $offerdetails = Offer::where('status', $type)->first();
        }
        if ($request->formtype == 'jobdetails') {
            $jobdetails = Job::where('id', $request->jobid)->first();
        } else {
            $jobdetails = Job::select('jobs.*')
                ->where('jobs.id', $offerdetails->job_id)
                ->first();
        }
        if (isset($offerdetails)) {
            $nursedetails = NURSE::select('nurses.*')
                ->where('nurses.id', $offerdetails['worker_user_id'])
                ->first();
            $userdetails = $nursedetails ? User::where('id', $nursedetails->user_id)->first() : '';
            $jobapplieddetails = $nursedetails ? Offer::where(['status' => $type, 'worker_user_id' => $offerdetails['worker_user_id']])->get() : '';
            $jobappliedcount = $nursedetails ? Offer::where(['status' => $type, 'worker_user_id' => $offerdetails['worker_user_id']])->count() : '';
            if ($request->formtype == 'useralldetails') {
                $data2 .=
                    '
                    <ul class="ss-cng-appli-hedpfl-ul">
                        <li>
                            <span>' .
                    $offerdetails['worker_user_id'] .
                    '</span>
                            <h6>
                                <img src="' .
                    URL::asset('public/images/nurses/profile/' . $userdetails->image) .
                    '" onerror="this.onerror=null;this.src=' .
                    '\'' .
                    URL::asset('public/frontend/img/profile-pic-big.png') .
                    '\'' .
                    ';" id="preview" width="50px" height="50px" style="object-fit: cover;" class="rounded-3" alt="Profile Picture">
                                ' .
                    $userdetails->first_name .
                    ' ' .
                    $userdetails->last_name .
                    '
                            </h6>
                        </li>
                        <li>';

                $data2 .= '
                        </li>
                    </ul>';
                $data2 .=
                    '
                    <div class="ss-chng-apcon-st-ssele">
                    
                        <label class="mb-2">Change Application Status</label>
                        <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-9">
                        <select name="status" id="status application-status">
                            <option value="">Select Status</option>
                            <option value="Apply" ' .
                    ($offerdetails['status'] === 'Apply' ? 'selected' : '') .
                    '>Apply</option>
                            <option value="Screening" ' .
                    ($offerdetails['status'] === 'Screening' ? 'selected' : '') .
                    '>Screening</option>
                            <option value="Submitted" ' .
                    ($offerdetails['status'] === 'Submitted' ? 'selected' : '') .
                    '>Submitted</option>
                            <option value="Offered" ' .
                    ($offerdetails['status'] === 'Offered' ? 'selected' : '') .
                    '>Offered</option>
                            <option value="Done" ' .
                    ($offerdetails['status'] === 'Done' ? 'selected' : '') .
                    '>Done</option>
                            <option value="Onboarding" ' .
                    ($offerdetails['status'] === 'Onboarding' ? 'selected' : '') .
                    '>Onboarding</option>
                            <option value="Working" ' .
                    ($offerdetails['status'] === 'Working' ? 'selected' : '') .
                    '>Working</option>
                            <option value="Rejected" ' .
                    ($offerdetails['status'] === 'Rejected' ? 'selected' : '') .
                    '>Rejected</option>
                            <option value="Blocked" ' .
                    ($offerdetails['status'] === 'Blocked' ? 'selected' : '') .
                    '>Blocked</option>
                            <option value="Hold" ' .
                    ($offerdetails['status'] === 'Hold' ? 'selected' : '') .
                    '>Hold</option>
                        </select>
                        </div>
                        <div class="col-3">
                        <button class="counter-save-for-button" style="margin-top:0px;" onclick="applicationStatus(document.getElementById(\'status application-status\').value, \'' .
                    $type .
                    '\', \'' .
                    $request->id .
                    '\', \'' .
                    $request->jobid .
                    '\')
                        ">Change Status</button>
                        </div>
                        </div>
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
                            <p>' .
                    ($nursedetails->diploma ?? '<u onclick="askWorker(this, \'diploma\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                        </li>
                        <li class="col-md-12">
                            <span class="mt-3">Drivers license</span>
                        </li>
                        <li class="col-md-6">
                            <h6>Required</h6>
                        </li>
                        <li class="col-md-6">
                            <p>' .
                    ($nursedetails->driving_license ?? '<u onclick="askWorker(this, \'driving_license\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                        </li>
                        <li class="col-md-12">
                            <span class="mt-3">Worked at Facility Before</span>
                        </li>
                        <li class="col-md-6">
                            <h6>Have you worked here in the last 18 months?</h6>
                        </li>
                        <li class="col-md-6">
                            <p>' .
                    ($nursedetails->worked_at_facility_before ?? '<u onclick="askWorker(this, \'worked_at_facility_before\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                        </li>
                        <li class="col-md-12">
                            <span class="mt-3">SS# or SS Card</span>
                        </li>
                        <li class="col-md-6">
                            <h6>Last 4 digits of SS#</h6>
                        </li>
                        <li class="col-md-6">
                            <p>' .
                    ($nursedetails->worker_ss_number ?? '----') .
                    '</p>
                        </li>';
                $data2 .=
                    ' <div class="col-md-12">
                <span class="mt-3">Profession</span>
            </div>
                <div class="row ' .
                    ($jobdetails->proffesion == $nursedetails->proffesion ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                    <div class="col-md-6">
                        <h6>' .
                    ($jobdetails->proffesion ?? '----') .
                    '</h6>
                    </div>
                    <div class="col-md-6 ' .
                    ($jobdetails->proffesion ? '' : 'd-none') .
                    '">
                        <p>' .
                    ($nursedetails->proffesion ?? '<u onclick="askWorker(this, \'nursing_profession\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                    </div>
                    </div>';

                $data2 .=
                    ' <div class="col-md-12">
                <span class="mt-3">Specialty</span>
            </div>
                <div class="row ' .
                    ($jobdetails->specialty == $nursedetails->specialty ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                    <div class="col-md-6">
                        <h6>' .
                    ($jobdetails->specialty ?? '----') .
                    '</h6>
                    </div>
                    <div class="col-md-6 ' .
                    ($jobdetails->specialty ? '' : 'd-none') .
                    '">
                        <p>' .
                    ($nursedetails->specialty ?? '<u onclick="askWorker(this, \'nursing_specialty\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                    </div>
                    </div>';
                $data2 .=
                    '
                            <div class="col-md-12">
                                <span class="mt-3">Block scheduling</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->block_scheduling === $nursedetails->block_scheduling ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->block_scheduling == '1' ? 'Yes' : ($jobdetails->block_scheduling == '0' ? 'No' : '----')) .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    (isset($jobdetails->block_scheduling) ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->block_scheduling == '1' ? 'Yes' : ($nursedetails->block_scheduling == '0' ? 'No' : '<u onclick="askWorker(this, \'block_scheduling\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>')) .
                    '</p>
                    </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Float requirements</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->float_requirement === $nursedetails->float_requirement ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->float_requirement == '1' ? 'Yes' : ($jobdetails->float_requirement == '0' ? 'No' : '----')) .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    (isset($jobdetails->float_requirement) ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->float_requirement == '1' ? 'Yes' : ($nursedetails->float_requirement == '0' ? 'No' : '<u onclick="askWorker(this, \'float_requirement\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>')) .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Facility Shift Cancellation Policy</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->facility_shift_cancelation_policy === $nursedetails->facility_shift_cancelation_policy ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->facility_shift_cancelation_policy ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->facility_shift_cancelation_policy ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->facility_shift_cancelation_policy ?? '<u onclick="askWorker(this, \'facility_shift_cancelation_policy\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Contract Termination Policy</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->contract_termination_policy === $nursedetails->contract_termination_policy ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->contract_termination_policy ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->contract_termination_policy ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->contract_termination_policy ?? '<u onclick="askWorker(this, \'contract_termination_policy\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Traveler Distance From Facility</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->traveler_distance_from_facility === $nursedetails->distance_from_your_home ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->traveler_distance_from_facility ?? '----') .
                    ' miles Maximum</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->traveler_distance_from_facility ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->distance_from_your_home ?? '<u onclick="askWorker(this, \'distance_from_your_home\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Facility</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->facility === $nursedetails->worked_at_facility_before ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->facility ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->facility ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worked_at_facility_before ?? '<u onclick="askWorker(this, \'worked_at_facility_before\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Clinical Setting</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->clinical_setting === $nursedetails->clinical_setting_you_prefer ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->clinical_setting ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->clinical_setting ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->clinical_setting_you_prefer ?? '<u onclick="askWorker(this, \'clinical_setting_you_prefer\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Patient ratio</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->patient_ratio === $nursedetails->worker_patient_ratio ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->Patient_ratio ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->Patient_ratio ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_patient_ratio ?? '<u onclick="askWorker(this, \'worker_patient_ratio\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">EMR</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->emr === $nursedetails->worker_emr ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->Emr ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->Emr ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_emr ?? '<u onclick="askWorker(this, \'worker_emr\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Unit</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->unit === $nursedetails->worker_unit ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->Unit ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->Unit ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_unit ?? '<u onclick="askWorker(this, \'worker_unit\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Scrub Color</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->scrub_color === $nursedetails->worker_scrub_color ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->scrub_color ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->scrub_color ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_scrub_color ?? '<u onclick="askWorker(this, \'worker_scrub_color\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Interview Dates</span>
                            </div>

                            <div class="col-md-6">
                                <h6>' .
                    $nursedetails->worker_interview_dates .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->worker_interview_dates ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_interview_dates ?? '<u onclick="askWorker(this, \'worker_interview_dates\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Start Date</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->start_date == $nursedetails->worker_start_date ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->start_date ? $jobdetails->start_date : 'As Soon As Possible') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->start_date ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_start_date ?? '<u onclick="askWorker(this, \'worker_start_date\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">RTO</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->rto === $nursedetails->rto ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->rto ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->rto ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->rto ?? '<u onclick="askWorker(this, \'rto\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Shift Time of Day</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->preferred_shift == $nursedetails->worker_shift_time_of_day ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->preferred_shift ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->preferred_shift ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_shift_time_of_day ?? '<u onclick="askWorker(this, \'worker_shift_time_of_day\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Hours/Week</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->hours_per_week == $nursedetails->worker_hours_per_week ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->hours_per_week ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->hours_per_week ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_hours_per_week ?? '<u onclick="askWorker(this, \'worker_hours_per_week\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Guaranteed Hours</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->guaranteed_hours == $nursedetails->worker_guaranteed_hours ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->guaranteed_hours ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->guaranteed_hours ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_guaranteed_hours ?? '<u onclick="askWorker(this, \'worker_guaranteed_hours\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Hours/Shift</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->preferred_assignment_duration == $nursedetails->worker_weeks_assignment ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->preferred_assignment_duration ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->preferred_assignment_duration ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_weeks_assignment ?? '<u onclick="askWorker(this, \'worker_weeks_assignment\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Weeks/Assignment</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->weeks_shift == $nursedetails->worker_shifts_week ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->preferred_assignment_duration ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->preferred_assignment_duration ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_weeks_assignment ?? '<u onclick="askWorker(this, \'worker_weeks_assignment\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Shifts/Week</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->weeks_shift == $nursedetails->worker_shifts_week ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->weeks_shift ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->weeks_shift ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_shifts_week ?? '<u onclick="askWorker(this, \'worker_shifts_week\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Referral Bonus</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->referral_bonus === $nursedetails->worker_referral_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>$' .
                    ($jobdetails->referral_bonus ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->referral_bonus ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_referral_bonus ?? '<u onclick="askWorker(this, \'worker_referral_bonus\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Sign-On Bonus</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->sign_on_bonus === $nursedetails->worker_sign_on_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>$' .
                    ($jobdetails->sign_on_bonus ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->sign_on_bonus ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_sign_on_bonus ?? '<u onclick="askWorker(this, \'worker_sign_on_bonus\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Completion Bonus</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->completion_bonus === $nursedetails->worker_completion_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>$' .
                    ($jobdetails->completion_bonus ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->completion_bonus ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_completion_bonus ?? '<u onclick="askWorker(this, \'worker_completion_bonus\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Extension Bonus</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->extension_bonus === $nursedetails->worker_extension_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>$' .
                    ($jobdetails->extension_bonus ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->extension_bonus ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_extension_bonus ?? '<u onclick="askWorker(this, \'worker_extension_bonus\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Other Bonus</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->other_bonus === $nursedetails->worker_other_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>$' .
                    ($jobdetails->other_bonus ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->other_bonus ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_other_bonus ?? '<u onclick="askWorker(this, \'worker_other_bonus\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">401K</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->four_zero_one_k === $nursedetails->how_much_k ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->four_zero_one_k == '1' ? 'Yes' : ($jobdetails->four_zero_one_k == '0' ? 'No' : '----')) .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    (isset($jobdetails->four_zero_one_k) ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->how_much_k ?? '<u onclick="askWorker(this, \'how_much_k\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Health Insurance</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->health_insaurance === $nursedetails->worker_health_insurance ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->health_insaurance == '1' ? 'Yes' : ($jobdetails->health_insaurance == '0' ? 'No' : '----')) .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    (isset($jobdetails->health_insaurance) ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_health_insurance == '1' ? 'Yes' : ($nursedetails->worker_health_insurance == '0' ? 'No' : '<u onclick="askWorker(this, \'worker_health_insurance\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>')) .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Dental</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->dental === $nursedetails->worker_dental ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->dental == '1' ? 'Yes' : ($jobdetails->dental == '0' ? 'No' : '----')) .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    (isset($jobdetails->dental) ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_dental == '1' ? 'Yes' : ($nursedetails->worker_dental == '0' ? 'No' : '<u onclick="askWorker(this, \'worker_dental\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>')) .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Vision</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->vision === $nursedetails->worker_vision ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->vision == '1' ? 'Yes' : ($jobdetails->vision == '0' ? 'No' : '----')) .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    (isset($jobdetails->vision) ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_vision == '1' ? 'Yes' : ($nursedetails->worker_vision == '0' ? 'No' : '<u onclick="askWorker(this, \'worker_vision\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>')) .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Actual Hourly rate</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->actual_hourly_rate === $nursedetails->worker_actual_hourly_rate ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->actual_hourly_rate ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->actual_hourly_rate ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_actual_hourly_rate ?? '<u onclick="askWorker(this, \'worker_actual_hourly_rate\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Overtime</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->overtime === $nursedetails->worker_overtime ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->overtime ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->overtime ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_overtime ?? '<u onclick="askWorker(this, \'worker_overtime\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Holiday</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->holiday === $nursedetails->worker_holiday ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->holiday ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->holiday ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_holiday ?? '<u onclick="askWorker(this, \'worker_holiday\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">On Call</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->on_call === $nursedetails->worker_on_call ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->on_call ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->on_call ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_on_call ?? '<u onclick="askWorker(this, \'worker_on_call\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Call Back</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->call_back === $nursedetails->worker_call_back ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->call_back ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->call_back ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_call_back ?? '<u onclick="askWorker(this, \'worker_call_back\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Orientation Rate</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->orientation_rate === $nursedetails->worker_orientation_rate ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->orientation_rate ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->orientation_rate ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_orientation_rate ?? '<u onclick="askWorker(this, \'worker_orientation_rate\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Weekly Taxable amount</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->weekly_taxable_amount === $nursedetails->worker_weekly_taxable_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->weekly_taxable_amount ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->weekly_taxable_amount ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_weekly_taxable_amount ?? '<u onclick="askWorker(this, \'worker_weekly_taxable_amount\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Employer Weekly Amount</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->employer_weekly_amount === $nursedetails->worker_employer_weekly_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->employer_weekly_amount ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->employer_weekly_amount ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_employer_weekly_amount ?? '<u onclick="askWorker(this, \'worker_employer_weekly_amount\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Weekly non-taxable amount</span>
                            </div>
                            <div class="row ' .
                    ($jobdetails->weekly_non_taxable_amount === $nursedetails->worker_weekly_non_taxable_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                    ' d-flex align-items-center" style="margin:auto;">
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->weekly_non_taxable_amount ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-6 ' .
                    ($jobdetails->weekly_non_taxable_amount ? '' : 'd-none') .
                    '">
                                <p>' .
                    ($nursedetails->worker_weekly_non_taxable_amount ?? '<u onclick="askWorker(this, \'worker_weekly_non_taxable_amount\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</p>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Goodwork Weekly Amount</span>
                            </div>
                            <div class="col-md-6">
                                <h6>' .
                    ($jobdetails->weekly_taxable_amount ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Total Employer Amount</span>
                            </div>
                            <div class="col-md-12">
                                <h6>' .
                    ($jobdetails->total_employer_amount ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Total Goodwork Amount</span>
                            </div>
                            <div class="col-md-12">
                                <h6>' .
                    ($jobdetails->total_goodwork_amount ?? '----') .
                    '</h6>
                            </div>
                            <div class="col-md-12">
                                <span class="mt-3">Total Contract Amount</span>
                            </div>
                            <div class="col-md-12">
                                <h6>' .
                    ($jobdetails->total_contract_amount ?? '----') .
                    '</h6>
                            </div>
                        </ul>
                    </div>';
                    if ($request->type != 'Done' && $request->type != 'Rejected' && $request->type != 'Blocked') {   
                    $data2 .='
                    <div class="ss-counter-buttons-div">
                    
                    <button class="counter-save-for-button" onclick="offerSend(\'' .
                    $offerdetails->id .
                    '\', \'' .
                    $jobdetails->id .
                    '\', \'rejectcounter\')">Reject Offer</button>
                    <button class="counter-save-for-button" onclick="applicationType(\'' .
                    $type .
                    '\', \'' .
                    $request->id .
                    '\', \'jobdetails\', \'' .
                    $request->jobid .
                    '\')">Counter Offer</button>
                    <button class="ss-counter-button" onclick="offerSend(\'' .
                    $offerdetails->id .
                    '\', \'' .
                    $jobdetails->id .
                    '\', \'offersend\')">Accept Offer</button>
                    </div>
                    ';}
            } elseif ($request->formtype == 'jobdetails') {
                $distinctFilters = Keyword::distinct()->pluck('filter');
                $allKeywords = [];
                // professions and specialities should retrived from their tables
                //$professions = Profession::all();
                //$specialities = Specialty::all();
                foreach ($distinctFilters as $filter) {
                    $keywords = Keyword::where('filter', $filter)->get();
                    $allKeywords[$filter] = $keywords;
                }
                $specialty = explode(',', $jobdetails['preferred_specialty']);
                $experience = explode(',', $jobdetails['preferred_experience']);
                if ($specialty && $experience) {
                    $specialtyCount = count($specialty);
                    $experienceCount = count($experience);
                    for ($i = 0; $i < $specialtyCount; $i++) {
                        $allspecialty[$specialty[$i] ?? ''] = $experience[$i] ?? '';
                    }
                }
                $allspecialty = (object) $allspecialty;
                $allusstate = States::all()->where('country_code', 'US');
                // $alluscities = Cities::all()->where('state_id', $jobdetails['job_state']);
                $alluscities = DB::table('cities')
                    ->where('state_id', $jobdetails['job_state'])
                    ->get();
                $data2 .=
                    '
                    <div>
                        <h4><img src="' .
                        URL::asset('public/recruiter/assets/images/counter-left-img.png') .
                    '"> Send Offer</h4>
                        <div class="ss-job-view-off-text-fst-dv">
                            <p class="mt-3">On behalf of <a href="">Albus Percival , Hogwarts</a> would like to offer <a href="#">' .
                    $jobdetails['id'] .
                    '</a>
                                to <a href="#">' .
                    $userdetails->first_name .
                    ' ' .
                    $userdetails->last_name .
                    '</a> with the following terms. This offer is only available for the next <a
                                hre="#">6 weeks:</a>
                            </p>
                            
                        </div>
                    </div>
                    <form class="ss-emplor-form-sec" id="send-job-offer">
                    <div class="ss-form-group">
                        <label>Job Name</label>
                        <input type="text" name="job_name" id="job_name" placeholder="Enter job name" value="' .
                    $jobdetails['job_name'] .
                    '">
                    
                        <input type="text" class="d-none" id="job_id" name="job_id" readonly value="' .
                    $jobdetails['id'] .
                    '">
                        <input type="text" class="d-none" id="recruiter_id" name="recruiter_id" readonly value="' .
                    $jobdetails['recruiter_id'] .
                    '">
                        <input type="text" class="d-none" id="worker_user_id" name="worker_user_id" readonly value="' .
                    $userdetails->id .
                    '">
                    </div>
                    <span class="help-block-job_name"></span>
                    <div class="ss-form-group">
                        <label>Type</label>
                        <select name="type" id="type">
                            <option value="'.$jobdetails['type'].'">'.$jobdetails['type'].'</option>';
                if (isset($allKeywords['Type'])) {
                    foreach ($allKeywords['Type'] as $value) {
                        $data2 .= '<option value="' . $value->title . '" ' . ($jobdetails['type'] == $value->id ? 'selected' : '') . '>' . $value->title . '</option>';
                    }
                }
                $data2 .= '
                        </select>
                    </div>
                    <span class="help-block-type"></span>
                    <div class="ss-form-group">
                        <label>Terms</label>
                        <select name="terms" id="term">
                        ';
                if (isset($allKeywords['Terms'])) {
                    foreach ($allKeywords['Terms'] as $value) {
                        $data2 .= '<option value="' . $value->id . '" ' . ($jobdetails['terms'] == $value->id ? 'selected' : '') . '>' . $value->title . '</option>';
                    }
                }
                $data2 .=
                    '
                        </select>
                    </div>
                    <span class="help-block-term"></span>
                    <div class="ss-form-group">
                        <h6>Description</h6>
                        <textarea name="description" id="description" placeholder="Enter Job Description" cols="30" rows="2"> ' .
                    $jobdetails['description'] .
                    '</textarea>
                    </div>
                    <span class="help-block-description"></span>
                    <div class="ss-form-group">
                        <label>Profession</label>
                        <select name="profession" id="profession" >
                        <option value="'.$jobdetails['proffesion'].'">'.$jobdetails['proffesion'].'</option>';
                if (isset($allKeywords['Profession'])) {
                    foreach ($allKeywords['Profession'] as $value) {
                        $data2 .= '<option value="' . $value->id . '" ' . ($jobdetails['proffesion'] == $value->id ? 'selected' : '') . '>' . $value->title . '</option>';
                    }
                }
                $data2 .= '
                        </select>
                    </div>
                    <span class="help-block-profession"></span>
                    <div class="ss-form-group ss-prsnl-frm-specialty">
                        <label>Specialty</label>
                        
                                <div class="col-md-12">
                                    <select name="preferred_specialty" class="m-0" id="preferred_specialty">
                                    <option value="'.$jobdetails['preferred_specialty'].'">'.$jobdetails['preferred_specialty'].'</option>';
                if (isset($allKeywords['Speciality'])) {
                    foreach ($allKeywords['Speciality'] as $value) {
                        $data2 .= '<option value="' . $value->id . '">' . $value->title . '</option>';
                    }
                }
                $data2 .=
                    '
                                    </select>
                                </div>
                               
                            
                    </div>
                    <span class="help-block-preferred_specialty"></span>
                    
                    <div class="ss-form-group row justify-contenet-center align-items-center" style="margin-top:36px;">
                        <label class="col-lg-6 col-sm-8 col-xs-8 col-md-8" >Block scheduling</label>
                        <input style="box-shadow:none; width: auto;" class="col-lg-6 col-sm-2 col-xs-2 col-md-2" type="radio" id="option1" name="option" value="1" autocompleted="" disabled>
                    </div>
                    <div class="ss-form-group">
                        <label>Float requirements</label>
                        <select name="float_requirement" class="float_requirement mb-3" id="float_requirement" value="' .
                    $jobdetails['float_requirement'] .
                    '" >
                    <option value="'.$jobdetails['float_requirement'].'">'.$jobdetails['float_requirement'].'</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <span class="help-block-float_requirement"></span>
                    <div class="ss-form-group">
                        <label>Facility Shift Cancellation Policy</label>
                        <select name="facility_shift_cancelation_policy" class="facility_shift_cancelation_policy mb-3" id="facility_shift_cancelation_policy" value="' .
                    $jobdetails['facility_shift_cancelation_policy'] .
                    '" >
                    <option value="'.$jobdetails['facility_shift_cancelation_policy'].'">'.$jobdetails['facility_shift_cancelation_policy'].'</option>';
                if (isset($allKeywords['AssignmentDuration'])) {
                    foreach ($allKeywords['AssignmentDuration'] as $value) {
                        $data2 .= '<option value="' . $value->id . '" ' . ($jobdetails['block_scheduling'] == $value->id ? 'selected' : '') . '>' . $value->title . '</option>';
                    }
                }
                $data2 .=
                    '
                        </select>
                    </div>
                    <span class="help-block-facility_shift_cancelation_policy"></span>
                    <div class="ss-form-group">
                        <label>Contract Termination Policy</label>
                        <input type="text" id="contract_termination_policy" name="contract_termination_policy" placeholder="Enter Contract Termination Policy" value="' .
                    (isset($jobdetails['contract_termination_policy']) ? $jobdetails['contract_termination_policy'] : '2 weeks of guaranteed pay unless canceled for cause') .
                    '">
                    </div>
                    <span class="help-block-contract_termination_policy"></span>
                    
                    <div class="ss-form-group">
                        <label>Traveler Distance From Facility</label>
                        <input type="number" id="traveler_distance_from_facility" name="traveler_distance_from_facility" placeholder="Enter Traveler Distance From Facility" value="' .
                    $jobdetails['traveler_distance_from_facility'] .
                    '" >
                    </div>
                    <span class="help-block-traveler_distance_from_facility"></span>
                    <div class="ss-form-group">
                        <label>Facility</label>
                        <select name="facility" class="facility mb-3" id="facility">
                        <option value="'.$jobdetails['facility'].'">'.$jobdetails['facility'].'</option>';
                if (isset($allKeywords['FacilityName'])) {
                    foreach ($allKeywords['FacilityName'] as $value) {
                        $data2 .= '<option value="' . $value->id . '" ' . ($jobdetails['facility'] == $value->id ? 'selected' : '') . '>' . $value->title . '</option>';
                    }
                }
                $data2 .=
                    '
                        </select>
                    </div>
                    <span class="help-block-facility"></span>
                    <div class="ss-form-group">
                        <label>Clinical Setting</label>
                        <input type="text" id="clinical_setting" name="clinical_setting" placeholder="Enter clinical setting" value="' .
                    $jobdetails['clinical_setting'] .
                    '">
                    </div>
                    <span class="help-block-clinical_setting"></span>
                    <div class="ss-form-group">
                        <label>Patient ratio</label>
                        <input type="number" id="Patient_ratio" name="Patient_ratio" placeholder="How many patients can you handle?" value="' .
                    $jobdetails['Patient_ratio'] .
                    '">
                    </div>
                    <span class="help-block-Patient_ratio"></span>
                    <div class="ss-form-group">
                        <label>EMR</label>
                        <select name="emr" class="emr mb-3" id="emr">
                        <option value="'.$jobdetails['Emr'].'">'.$jobdetails['Emr'].'</option>';
                if (isset($allKeywords['EMR'])) {
                    foreach ($allKeywords['EMR'] as $value) {
                        $data2 .= '<option value="' . $value->id . '" ' . ($jobdetails['Emr'] == $value->id ? 'selected' : '') . '>' . $value->title . '</option>';
                    }
                }
                $data2 .=
                    '
                        </select>
                    </div>
                    <span class="help-block-emr"></span>
                    <div class="ss-form-group">
                        <label>Unit</label>
                        <input id="Unit" type="text" name="Unit" placeholder="Enter Unit" value="' .
                    $jobdetails['Unit'] .
                    '">
                    </div>
                    <span class="help-block-Unit"></span>
                    <div class="ss-form-group">
                        <label>Scrub Color</label>
                        <input id="scrub_color" type="text" name="scrub_color" placeholder="Enter Scrub Color" value="' .
                    $jobdetails['scrub_color'] .
                    '">
                    </div>
                    
                    <span class="help-block-scrub_color"></span>

                    <div class="ss-form-group">
                         <div class="row">
                            <div class="col-lg-6 col-sm-12 col-md-12 col-xs-12">
                                <label>Start Date</label>
                            </div>
                            <div class="row col-lg-6 col-sm-12 col-md-12 col-xs-12" style="display: flex; justify-content: end;">
                                <input id="as_soon_as" name="as_soon_as" value="1" type="checkbox" style="box-shadow:none; width:auto;" class="col-6">
                                <label class="col-6">
                                    As soon As possible
                                </label>
                            </div>
                        </div>
                         <input id="start_date" type="date" min="2024-03-06" name="start_date" placeholder="Select Date" value="1994-03-02">
                    </div>
                    <span class="help-block-start_date"></span>
                    <div class="ss-form-group">
                        <label>Enter RTO</label>
                        <input id="rto" type="text" name="rto" placeholder="RTO" value="' .
                    $jobdetails['rto'] .
                    '">
                    </div>
                    <span class="help-block-rto"></span>
                    <div class="ss-form-group">
                        <label>Shift Time of Day</label>
                        <select name="preferred_shift" id="shift-of-day">
                        <option value="'.$jobdetails['preferred_shift'].'">'.$jobdetails['preferred_shift'].'</option>';
                if (isset($allKeywords['PreferredShift'])) {
                    foreach ($allKeywords['PreferredShift'] as $value) {
                        $data2 .= '<option value="' . $value->id . '" ' . ($jobdetails['preferred_shift'] == $value->id ? 'selected' : '') . '>' . $value->title . '</option>';
                    }
                }
                $data2 .=
                    '
                        </select>
                    </div>
                    <span class="help-block-shift-of-day"></span>
                    <div class="ss-form-group">
                        <label>Hours/Week</label>
                        <input id="hours_per_week" type="number" name="hours_per_week" placeholder="Enter Hours/Week" value="' .
                    $jobdetails['hours_per_week'] .
                    '">
                    </div>
                    <span class="help-block-hours_per_week"></span>
                    <div class="ss-form-group">
                        <label>Guaranteed Hours</label>
                        <input id="guaranteed_hours" type="number" name="guaranteed_hours" placeholder="Enter Guaranteed Hours" value="' .
                    $jobdetails['guaranteed_hours'] .
                    '">
                    </div>
                    <span class="help-block-guaranteed_hours"></span>
                    <div class="ss-form-group">
                        <label>Hours/Shift</label>
                        <input id="hours_shift" type="number" name="hours_shift" placeholder="Enter Hours/Shift" value="' .
                    $jobdetails['hours_shift'] .
                    '">
                    </div>
                    <span class="help-block-hours_shift"></span>
                    <div class="ss-form-group">
                        <label>Weeks/Assignment</label>
                        <input id="preferred_assignment_duration" type="number" name="preferred_assignment_duration" placeholder="Enter Weeks/Assignment" value="' .
                    $jobdetails['preferred_assignment_duration'] .
                    '">
                    </div>
                    <span class="help-block-preferred_assignment_duration"></span>
                    <div class="ss-form-group">
                        <label>Shifts/Week</label>
                        <input id="weeks_shift" type="number" name="weeks_shift" placeholder="Enter Shifts/Week" value="' .
                    $jobdetails['weeks_shift'] .
                    '">
                    </div>
                    <span class="help-block-weeks_shift"></span>
                    <div class="ss-form-group">
                        <label>Sign-On Bonus</label>
                        <input id="sign_on_bonus" type="number" name="sign_on_bonus" placeholder="Enter Sign-On Bonus" value="' .
                    $jobdetails['sign_on_bonus'] .
                    '">
                    </div>
                    <span class="help-block-sign_on_bonus"></span>
                    <div class="ss-form-group">
                        <label>Completion Bonus</label>
                        <input id="completion_bonus" type="number" name="completion_bonus" placeholder="Enter Completion Bonus" value="' .
                    $jobdetails['completion_bonus'] .
                    '">
                    </div>
                    <span class="help-block-completion_bonus"></span>
                    <div class="ss-form-group">
                        <label>Extension Bonus</label>
                        <input id="extension_bonus" type="number" name="extension_bonus" placeholder="Enter Extension Bonus" value="' .
                    $jobdetails['extension_bonus'] .
                    '">
                    </div>
                    <span class="help-block-extension_bonus"></span>
                    <div class="ss-form-group">
                        <label>Other Bonus</label>
                        <input id="other_bonus" type="number" name="other_bonus" placeholder="Enter Other Bonus" value="' .
                    $jobdetails['other_bonus'] .
                    '">
                    </div>
                    <span class="help-block-other_bonus"></span>
                    <div class="ss-form-group">
                        <label>Referral Bonus</label>
                        <input id="referral_bonus" type="number" name="referral_bonus" placeholder="Enter Referral Bonus" value="' .
                    $jobdetails['referral_bonus'] .
                    '">
                    </div>
                    <span class="help-block-referral_bonus"></span>
                    <div class="ss-form-group">
                        <label>401K</label>
                        <select name="four_zero_one_k" id="401k">
                            ';
                $data2 .= '
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <span class="help-block-401k"></span>
                    <div class="ss-form-group">
                        <label>Health Insurance</label>
                        <select name="health_insaurance" id="health-insurance">
                       
                            <option value="'.$jobdetails['health_insaurance'].'">'.(($jobdetails['health_insaurance'] == '1') ? 'Yes': 'No').'</option>
                            <option value="true">Yes</option>
                            <option value="false">No</option></option>
                            ';
                $data2 .= '
                        </select>
                    </div>
                    <span class="help-block-health-insurance"></span>
                    <div class="ss-form-group">
                        <label>Dental</label>
                        <select name="dental" id="dental">
                        <option value="'.$jobdetails['dental'].'">'.(($jobdetails['dental'] == '1') ? 'Yes': 'No').'</option>';
                $data2 .= '
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <span class="help-block-dental"></span>
                    <div class="ss-form-group">
                        <label>Vision</label>
                        <select name="vision" id="vision">
                        <option value="'.$jobdetails['vision'].'">'.(($jobdetails['vision'] == '1') ? 'Yes': 'No').'</option>';
                $data2 .=
                    '
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <span class="help-block-vision"></span>
                    <div class="ss-form-group">
                        <label>Actual Hourly rate</label>
                        <input id="actual_hourly_rate" type="number" name="actual_hourly_rate" placeholder="Enter Actual Hourly rate" value="' .
                    $jobdetails['actual_hourly_rate'] .
                    '">
                    </div>
                    <span class="help-block-actual_hourly_rate"></span>
                    <div class="ss-form-group">
                        <label>Overtime</label>
                        <select name="overtime" class="overtime mb-3" id="overtime" value="' .
                    $jobdetails['overtime'] .
                    '">
                    <option value="'.$jobdetails['overtime'].'">'.$jobdetails['overtime'].'</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <span class="help-block-overtime"></span>
                    <div class="ss-form-group">
                        <label>Holiday</label>
                        <input id="holiday" type="text" name="holiday" placeholder="Select Dates" value="' .
                    $jobdetails['holiday'] .
                    '">
                    </div>
                    <span class="help-block-holiday"></span>
                    <div class="ss-form-group">
                        <label>On Call</label>
                        <select  name="on_call" class="on_call mb-3" id="on_call" value="' .
                    $jobdetails['on_call'] .
                    '">
                    <option value="'.$jobdetails['on_call'].'">'.$jobdetails['on_call'].'</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <span class="help-block-on_call"></span>
                    <div class="ss-form-group">
                        <label>Tax Status</label>
                        <select name="tax_status" class="on_call mb-3" id="tax_status" value="">
                            <option value="W4">W4</option>
                            <option value="1099">1099</option>
                        </select>
                    </div>
                    <span class="help-block-tax_status"></span>
                    <div class="ss-form-group">
                        <label>Orientation Rate</label>
                        <input id="orientation_rate" type="number" name="orientation_rate" placeholder="Enter Orientation Rate" value="' .
                    $jobdetails['orientation_rate'] .
                    '">
                    </div>
                    <span class="help-block-orientation_rate"></span>
                    <div class="ss-form-group">
                        <label>Est. Weekly Taxable amount</label>
                        <input  type="number" name="weekly_taxable_amount" placeholder="---" value="' .
                    $jobdetails['weekly_taxable_amount'] .
                    '" readonly>
                    </div>
                    <div class="ss-form-group">
                        <label>Est. Weekly non-taxable amount</label>
                        <input type="number" name="weekly_non_taxable_amount" placeholder="---" value="' .
                    $jobdetails['weekly_non_taxable_amount'] .
                    '" readonly>
                    </div>
                    <div class="ss-form-group">
                        <label>Est. Employer Weekly Amount</label>
                        <input type="number" name="employer_weekly_amount" placeholder="---" value="' .
                    $jobdetails['employer_weekly_amount'] .
                    '" readonly>
                    </div>
                    <div class="ss-form-group">
                        <label>Est. Goodwork Weekly Amount</label>
                        <input type="number" name="goodwork_weekly_amount" placeholder="---" value="' .
                    $jobdetails['weekly_taxable_amount'] .
                    '" readonly>
                    </div>
                    <div class="ss-form-group">
                        <label>Est. Total Employer Amount</label>
                        <input type="number" name="total_employer_amount" placeholder="---" value="' .
                    $jobdetails['total_employer_amount'] .
                    '" readonly>
                    </div>
                    <div class="ss-form-group">
                        <label>Est. Total Goodwork Amount</label>
                        <input type="number" name="total_goodwork_amount" placeholder="---" value="' .
                    $jobdetails['total_goodwork_amount'] .
                    '" readonly>
                    </div>
                    <div class="ss-form-group">
                        <label>Est. Total Contract Amount</label>
                        <input type="text" name="total_contract_amount" placeholder="---" value="' .
                    $jobdetails['total_contract_amount'] .
                    '" readonly>
                    </div>
                    <div class="ss-account-btns">
                                            <a class="ss-counter-button d-block text-center" id="submit-job-offer" onclick="applicationType(\'' .
                    $type .
                    '\', \'' .
                    $request->id .
                    '\', \'joballdetails\', \'' .
                    $jobdetails['id'] .
                    '\')">Send Offer</a>
                                            <button class="counter-save-for-button" id="ss-reject-offer-btn" onclick="applicationType(\'' .
                    $type .
                    '\', \'' .
                    $request->id .
                    '\', \'createdraft\', \'' .
                    $jobdetails['id'] .
                    '\')">Save As Draft</button>
                    </div>
                    </form>
                    ';
            } elseif ($request->formtype == 'joballdetails') {
                $offerdetails = DB::table('offers')
                    ->where(['job_id' => $request->jobid, 'worker_user_id' => $userdetails->id])
                    ->first();
                $data2 .=
                    '
                        <div class="ss-jb-apl-oninfrm-mn-dv">
                            <div>
                                <h4><img src="' .
                                URL::asset('public/recruiter/assets/images/counter-left-img.png') .
                    '"> Send Offer</h4>
                                <div class="ss-job-view-off-text-fst-dv">
                                    <p class="mt-3">On behalf of <a href="">Albus Percival , Hogwarts</a> would like to offer <a href="#">' .
                    $jobdetails['id'] .
                    '</a>
                                        to <a href="#">' .
                    $userdetails->first_name .
                    ' ' .
                    $userdetails->last_name .
                    '</a> with the following terms. This offer is only available for the next <a hre="#">6 weeks:</a>
                                    </p>
                                </div>
                            </div>
                            <div class="ss-jb-apply-on-disc-txt mb-3">
                                <h5>Description</h5>
                                <p>' .
                    $jobdetails['description'] .
                    '</p>
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
            <p>' .
                    ($nursedetails->worker_ss_number ?? '----') .
                    '</p>
        </li>
        <li class="col-md-6 mb-3 ' .
                    ($jobdetails->proffesion != $offerdetails->profession ? 'ss-job-view-off-text-fst-dv' : '') .
                    '">
            <span class="mt-3">Profession</span>
            <h6>' .
                    ($offerdetails->profession ?? '----') .
                    '</h6>
        </li>';
                if (isset($jobdetails->specialty)) {
                    foreach (explode(',', $offerdetails->specialty) as $key => $value) {
                        if (isset($value)) {
                            $data2 .=
                                '
                    <div class="col-md-6 mb-3 ' .
                                ($jobdetails->specialty != $offerdetails->specialty ? 'ss-job-view-off-text-fst-dv' : '') .
                                '">
                        <span class="mt-3">Specialty</span>
                        <h6>' .
                                $value .
                                ' Required</h6>
                    </div>
                    ';
                        }
                    }
                }
                $data2 .=
                    '
        </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->block_scheduling != $offerdetails->block_scheduling ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Block scheduling</span>
                <h6>' .
                    ($offerdetails->block_scheduling ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->float_requirement != $offerdetails->float_requirement ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Float requirements</span>
                <h6>' .
                    ($offerdetails->float_requirement ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->facility_shift_cancelation_policy != $offerdetails->facility_shift_cancelation_policy ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Facility Shift Cancellation Policy</span>
                <h6>' .
                    ($offerdetails->facility_shift_cancelation_policy ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->contract_termination_policy != $offerdetails->contract_termination_policy ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Contract Termination Policy</span>
                <h6>' .
                    ($offerdetails->contract_termination_policy ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->traveler_distance_from_facility != $offerdetails->traveler_distance_from_facility ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Traveler Distance From Facility</span>
                <h6>' .
                    ($offerdetails->traveler_distance_from_facility ?? '----') .
                    ' miles Maximum</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->facility != $offerdetails->facility ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Facility</span>
                <h6>' .
                    ($offerdetails->facility ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->clinical_setting != $offerdetails->clinical_setting ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Clinical Setting</span>
                <h6>' .
                    ($offerdetails->clinical_setting ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->Patient_ratio != $offerdetails->Patient_ratio ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Patient ratio</span>
                <h6>' .
                    ($offerdetails->Patient_ratio ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->emr != $offerdetails->emr ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">EMR</span>
                <h6>' .
                    ($offerdetails->emr ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->Unit != $offerdetails->Unit ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Unit</span>
                <h6>' .
                    ($offerdetails->Unit ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->scrub_color != $offerdetails->scrub_color ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Scrub Color</span>
                <h6>' .
                    ($offerdetails->scrub_color ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3">
                <span class="mt-3">Interview Dates</span>
                <h6>' .
                    $nursedetails->worker_interview_dates .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->start_date != $offerdetails->start_date ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Start Date</span>
                <h6>' .
                    ($offerdetails->start_date ? $jobdetails->start_date : 'As Soon As Possible') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->rto != $offerdetails->rto ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">RTO</span>
                <h6>' .
                    ($offerdetails->rto ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->preferred_shift != $offerdetails->preferred_shift ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Shift Time of Day</span>
                <h6>' .
                    ($offerdetails->preferred_shift ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->hours_per_week != $offerdetails->hours_per_week ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Hours/Week</span>
                <h6>' .
                    ($offerdetails->hours_per_week ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->guaranteed_hours != $offerdetails->guaranteed_hours ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Guaranteed Hours</span>
                <h6>' .
                    ($offerdetails->guaranteed_hours ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->preferred_assignment_duration != $offerdetails->preferred_assignment_duration ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Hours/Shift</span>
                <h6>' .
                    ($offerdetails->preferred_assignment_duration ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->preferred_assignment_duration != $offerdetails->preferred_assignment_duration ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Weeks/Assignment</span>
                <h6>' .
                    ($offerdetails->preferred_assignment_duration ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->weeks_shift != $offerdetails->weeks_shift ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Shifts/Week</span>
                <h6>' .
                    ($offerdetails->weeks_shift ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->referral_bonus != $offerdetails->referral_bonus ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Referral Bonus</span>
                <h6>$' .
                    ($offerdetails->referral_bonus ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->sign_on_bonus != $offerdetails->sign_on_bonus ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Sign-On Bonus</span>
                <h6>$' .
                    ($offerdetails->sign_on_bonus ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->completion_bonus != $offerdetails->completion_bonus ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Completion Bonus</span>
                <h6>$' .
                    ($offerdetails->completion_bonus ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->extension_bonus != $offerdetails->extension_bonus ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Extension Bonus</span>
                <h6>$' .
                    ($offerdetails->extension_bonus ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->other_bonus != $offerdetails->other_bonus ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Other Bonus</span>
                <h6>$' .
                    ($offerdetails->other_bonus ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->four_zero_one_k != $offerdetails->four_zero_one_k ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">401K</span>
                <h6>' .
                    ($offerdetails->four_zero_one_k ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->health_insaurance != $offerdetails->health_insaurance ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Health Insurance</span>
                <h6>' .
                    ($offerdetails->health_insaurance ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->dental != $offerdetails->dental ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Dental</span>
                <h6>' .
                    ($offerdetails->dental ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->vision != $offerdetails->vision ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Vision</span>
                <h6>' .
                    ($offerdetails->vision ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->actual_hourly_rate != $offerdetails->actual_hourly_rate ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Actual Hourly rate</span>
                <h6>' .
                    ($offerdetails->actual_hourly_rate ?? '----') .
                    '</h6>
            </div>
           
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->overtime != $offerdetails->overtime ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Overtime</span>
                <h6>' .
                    ($offerdetails->overtime ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->holiday != $offerdetails->holiday ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Holiday</span>
                <h6>' .
                    ($offerdetails->holiday ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->on_call != $offerdetails->on_call ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">On Call</span>
                <h6>' .
                    ($offerdetails->on_call ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->call_back != $offerdetails->call_back ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Call Back</span>
                <h6>' .
                    ($offerdetails->call_back ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3 ' .
                    ($jobdetails->orientation_rate != $offerdetails->orientation_rate ? 'ss-job-view-off-text-fst-dv' : '') .
                    ' ">
                <span class="mt-3">Orientation Rate</span>
                <h6>' .
                    ($offerdetails->orientation_rate ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3">
                <span class="mt-3">Weekly Taxable amount</span>
                <h6>' .
                    ($offerdetails->weekly_taxable_amount ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3">
                <span class="mt-3">Employer Weekly Amount</span>
                <h6>' .
                    ($offerdetails->employer_weekly_amount ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3">
                <span class="mt-3">Weekly non-taxable amount</span>
                <h6>' .
                    ($offerdetails->weekly_non_taxable_amount ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3">
                <span class="mt-3">Goodwork Weekly Amount</span>
                <h6>' .
                    ($offerdetails->weekly_taxable_amount ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3">
                <p>You only have 5 days left before your rate drops from 5% to 2%</p>
            </div>
            <div class="col-md-6 mb-3">
                <span class="mt-3">Total Employer Amount</span>
                <h6>' .
                    ($offerdetails->total_employer_amount ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3">
                <span class="mt-3">Total Goodwork Amount</span>
                <h6>' .
                    ($offerdetails->total_goodwork_amount ?? '----') .
                    '</h6>
            </div>
            <div class="col-md-6 mb-3">
                <span class="mt-3">Total Contract Amount</span>
                <h6>' .
                    ($offerdetails->total_contract_amount ?? '----') .
                    '</h6>
            </div>
        </ul>
            </div>
            <div class="ss-counter-buttons-div">
                <button class="ss-counter-button" onclick="offerSend(\'' .
                    $offerdetails->id .
                    '\', \'' .
                    $jobdetails->id .
                    '\', \'offersend\')">Send Offer</button>
                <button class="counter-save-for-button" onclick="offerSend(\'' .
                    $offerdetails->id .
                    '\', \'' .
                    $jobdetails->id .
                    '\', \'rejectcounter\')">Reject Counter</button>
            </div>
            ';
            } else {
                $data2 .=
                    '
            <ul class="ss-cng-appli-hedpfl-ul">
                <li>
                    <span>' .
                    $offerdetails['worker_user_id'] .
                    '</span>
                    <h6>
                        <img src="' .
                    URL::asset('public/images/nurses/profile/' . $userdetails->image) .
                    '" onerror="this.onerror=null;this.src=' .
                    '\'' .
                    URL::asset('frontend/img/profile-pic-big.png') .
                    '\'' .
                    ';" id="preview" width="50px" height="50px" style="object-fit: cover;" class="rounded-3" alt="Profile Picture">
                        ' .
                    $userdetails->first_name .
                    ' ' .
                    $userdetails->last_name .
                    '
                    </h6>
                </li>
                <li>
                    <a href="' .
                    route('recruiter-messages') .
                    '" class="rounded-pill ss-apply-btn py-2 border-0 px-4" onclick="chatNow(\'' .
                    $offerdetails['worker_user_id'] .
                    '\')">Chat Now</a>
                    
                </li>
            </ul>
            <div class="ss-appli-cng-abt-inf-dv">
                <h5>Applicant Information</h5>
                <p>' .
                    $userdetails->about_me .
                    '</p>
            </div>
            <div class="ss-applicatio-infor-texts-dv">
                <ul class="row">
                    <li class="col-md-6">
                        <p>Profession</p>
                        <h6>' .
                    ($userdetails->highest_nursing_degree ?? '<u onclick="askWorker(this, \'highest_nursing_degree\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</h6>
                    </li>
                    <li class="col-md-6">
                        <p>Specialty</p>
                        <h6 class="mb-3">' .
                    ($nursedetails['specialty'] ?? '<u onclick="askWorker(this, \'specialty\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</h6>
                    </li>
                    ';
                $data2 .=
                    '
                        <li class="col-md-6">
                            <p>Traveler Distance From Facility</p>
                            <h6 class="mb-3">' .
                    ($nursedetails['distance_from_your_home'] ?? '<u onclick="askWorker(this, \'distance_from_your_home\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</h6>
                        </li>
                        <li class="col-md-6">
                            <p>Facility</p>
                            <h6 class="mb-3">' .
                    ($nursedetails['worked_at_facility_before'] ?? '<u onclick="askWorker(this, \'worked_at_facility_before\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</h6>
                        </li>
                        <li class="col-md-6">
                            <p>Location</p>
                            <h6 class="mb-3">' .
                    ($nursedetails['state'] ?? '<u onclick="askWorker(this, \'state\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</h6>
                        </li>
                        <li class="col-md-6">
                            <p>Shift</p>
                            <h6 class="mb-3">' .
                    ($nursedetails['worker_shift_time_of_day'] ?? '<u onclick="askWorker(this, \'worker_shift_time_of_day\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</h6>
                        </li>
                        <li class="col-md-6">
                            <p>Distance from your home</p>
                            <h6 class="mb-3">' .
                    ($nursedetails['distance_from_your_home'] ?? '<u onclick="askWorker(this, \'distance_from_your_home\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</h6>
                        </li>
                        <li class="col-md-6">
                            <p>Facilities you`ve worked at</p>
                            <h6 class="mb-3">' .
                    ($nursedetails['facilities_you_like_to_work_at'] ?? '<u onclick="askWorker(this, \'facilities_you_like_to_work_at\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</h6>
                        </li>
                        <li class="col-md-6">
                            <p>Start Date</p>
                            <h6 class="mb-3">' .
                    ($nursedetails['worker_start_date'] ?? '<u onclick="askWorker(this, \'worker_start_date\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</h6>
                        </li>
                        <li class="col-md-6">
                            <p>RTO</p>
                            <h6 class="mb-3">' .
                    ($offerdetails['rto'] ?? '<u onclick="askWorker(this, \'rto\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</h6>
                        </li>
                        <li class="col-md-6">
                            <p>Shift Time of Day</p>
                            <h6 class="mb-3">' .
                    ($nursedetails['worker_shift_time_of_day'] ?? '<u onclick="askWorker(this, \'worker_shift_time_of_day\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</h6>
                        </li>
                        <li class="col-md-6">
                            <p>Weeks/Assignment</p>
                            <h6 class="mb-3">' .
                    ($offerdetails['worker_weeks_assignment'] ?? '<u onclick="askWorker(this, \'worker_weeks_assignment\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</h6>
                        </li>
                        <li class="col-md-6">
                            <p>Employer Weekly Amount</p>
                            <h6 class="mb-3">' .
                    ($nursedetails['worker_employer_weekly_amount'] ?? '<u onclick="askWorker(this, \'worker_employer_weekly_amount\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') .
                    '</h6>
                        </li>
                        <li class="col-md-12 ss-chng-appli-slider-mn-dv">
                            <p>Applicants Information(' .
                    $jobappliedcount .
                    ')</p>
                    </ul>
                </div>
                <div class="ss-chng-appli-slider-mn-dv">
                <div class="' .
                    ($jobappliedcount > 1 ? 'owl-carousel application-job-slider-owl' : '') .
                    ' application-job-slider">
            ';
                foreach ($jobapplieddetails as $key => $value) {
                    if ($value) {
                        $appliednursecount = Offer::where(['status' => $type, 'job_id' => $value->job_id])->count();
                        $jobdetails = Job::where(['id' => $value->job_id])->first();
                        $data2 .=
                            '
                        <div style="width:100%;" class="ss-chng-appli-slider-sml-dv" onclick="applicationType(\'' .
                            $type .
                            '\', \'' .
                            $value->id .
                            '\', \'useralldetails\', \'' .
                            $value->job_id .
                            '\')">
                            <ul class="ss-cng-appli-slid-ul1">
                                <li class="d-flex">
                                    <p>' .
                            $jobdetails->terms .
                            '</p>
                                    <span>' .
                            $appliednursecount .
                            ' Workeds Applied</span>
                                </li>
                                <li>Posted on ' .
                            (new DateTime($jobdetails->start_date))->format('M d, Y') .
                            '</li>
                            </ul>
                            <h4>' .
                            $jobdetails->job_name .
                            '</h4>
                            <ul class="ss-cng-appli-slid-ul2 d-block">
                                <li class="d-inline-block">' .
                            $jobdetails->job_location .
                            ', ' .
                            $jobdetails->job_state .
                            '</li>
                                <li class="d-inline-block">' .
                            $jobdetails->preferred_shift .
                            'preferred_shift</li>
                                <li class="d-inline-block">' .
                            $jobdetails->preferred_days_of_the_week .
                            ' wks</li>
                            </ul>
                            <ul class="ss-cng-appli-slid-ul3">
                                <li><span>' .
                            $jobdetails->facility .
                            '</span></li>
                                <li><h6>$' .
                            $jobdetails->hours_per_week .
                            '/wk</h6></li>
                            </ul>
                            <h5>' .
                            $value->job_id .
                            '</h5>
                        </div>
                    ';
                    }
                }
                $data2 .= '</div>
            </div>
            </div>';
                if ($data2 == '') {
                    $data2 = '<div class="text-center"><span>Data Not found</span></div>';
                }
            }
        }
        $responseData = [
            'applicationlisting' => $data,
            'applicationdetails' => $data2,
            'allspecialty' => $allspecialty,
        ];
        return response()->json($responseData);
    }

    public function updateApplicationStatus(Request $request)
    {
        $type = $request->type;
        $id = $request->id;
        $formtype = $request->formtype;
        $jobid = $request->jobid;

        if (isset($request->jobid)) {
            $jobid = $request->jobid;
            $job = Offer::where(['job_id' => $jobid, 'id' => $id])->update(['status' => $formtype]);
            if ($job) {
                $statusList = ['Apply', 'Screening', 'Submitted', 'Offered', 'Done', 'Onboarding', 'Working', 'Rejected', 'Blocked', 'Hold'];
                $statusCounts = [];
                $offerLists = [];
                foreach ($statusList as $status) {
                    $statusCounts[$status] = 0;
                }
                $statusCountsQuery = Offer::whereIn('status', $statusList)->select(\DB::raw('status, count(*) as count'))->groupBy('status')->get();
                foreach ($statusCountsQuery as $statusCount) {
                    if ($statusCount) {
                        $statusCounts[$statusCount->status] = $statusCount->count;
                    } else {
                        $statusCounts[$statusCount->status] = 0;
                    }
                }

                return response()->json(['message' => 'Update Successfully', 'type' => $formtype, 'statusCounts' => $statusCounts]);
            } else {
                return response()->json(['message' => 'Something went wrong! Please check']);
            }
        } else {
            return response()->json(['message' => 'Something went wrong! Please check']);
        }
    }
    public function sendJobOffer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'worker_user_id' => 'required',
            'job_id' => 'required',
        ]);
        $responseData = [];
        $message = 'Try Again !';
        if ($validator->fails()) {
            $responseData = [
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ];
        } else {
            try {
                $offerLists = Offer::where('id', $request->id)->first();
                $nurse = Nurse::where('user_id', $request->worker_user_id)->first();
                $user = user::where('id', $request->worker_user_id)->first();
                $job_data = Job::where('id', $request->job_id)->first();
                $update_array['job_name'] = isset($request->job_name) ? $request->job_name : $job_data->job_name;
                $update_array['type'] = isset($request->type) ? $request->type : $job_data->type;
                $update_array['terms'] = isset($request->terms) ? $request->terms : $job_data->terms;
                $update_array['profession'] = isset($request->profession) ? $request->profession : $job_data->proffesion;
                $update_array['block_scheduling'] = isset($request->block_scheduling) ? $request->block_scheduling : $job_data->block_scheduling;
                $update_array['float_requirement'] = isset($request->float_requirement) ? $request->float_requirement : $job_data->float_requirement;
                $update_array['facility_shift_cancelation_policy'] = isset($request->facility_shift_cancelation_policy) ? $request->facility_shift_cancelation_policy : $job_data->facility_shift_cancelation_policy;
                $update_array['contract_termination_policy'] = isset($request->contract_termination_policy) ? $request->contract_termination_policy : $job_data->contract_termination_policy;
                $update_array['traveler_distance_from_facility'] = isset($request->traveler_distance_from_facility) ? $request->traveler_distance_from_facility : $job_data->traveler_distance_from_facility;
                $update_array['job_id'] = isset($request->job_id) ? $request->job_id : $job_data->job_id;
                $update_array['recruiter_id'] = isset($request->recruiter_id) ? $request->recruiter_id : $job_data->recruiter_id;
                $update_array['worker_user_id'] = isset($nurse->id) ? $nurse->id : '';
                $update_array['clinical_setting'] = isset($request->clinical_setting) ? $request->clinical_setting : $job_data->clinical_setting;
                $update_array['Patient_ratio'] = isset($request->Patient_ratio) ? $request->Patient_ratio : $job_data->Patient_ratio;
                $update_array['emr'] = isset($request->emr) ? $request->emr : $job_data->emr;
                $update_array['Unit'] = isset($request->Unit) ? $request->Unit : $job_data->Unit;
                $update_array['scrub_color'] = isset($request->scrub_color) ? $request->scrub_color : $job_data->scrub_color;
                $update_array['start_date'] = isset($request->start_date) ? $request->start_date : $job_data->start_date;
                $update_array['as_soon_as'] = isset($request->as_soon_as) ? $request->as_soon_as : $job_data->as_soon_as;
                $update_array['rto'] = isset($request->rto) ? $request->rto : $job_data->rto;
                $update_array['hours_per_week'] = isset($request->hours_per_week) ? $request->hours_per_week : $job_data->hours_per_week;
                $update_array['guaranteed_hours'] = isset($request->guaranteed_hours) ? $request->guaranteed_hours : $job_data->guaranteed_hours;
                $update_array['hours_shift'] = isset($request->hours_shift) ? $request->hours_shift : $job_data->hours_shift;
                $update_array['weeks_shift'] = isset($request->weeks_shift) ? $request->weeks_shift : $job_data->weeks_shift;
                $update_array['preferred_assignment_duration'] = isset($request->preferred_assignment_duration) ? $request->preferred_assignment_duration : $job_data->preferred_assignment_duration;
                $update_array['referral_bonus'] = isset($request->referral_bonus) ? $request->referral_bonus : $job_data->referral_bonus;
                $update_array['sign_on_bonus'] = isset($request->sign_on_bonus) ? $request->sign_on_bonus : $job_data->sign_on_bonus;
                $update_array['completion_bonus'] = isset($request->completion_bonus) ? $request->completion_bonus : $job_data->completion_bonus;
                $update_array['extension_bonus'] = isset($request->extension_bonus) ? $request->extension_bonus : $job_data->extension_bonus;
                $update_array['other_bonus'] = isset($request->other_bonus) ? $request->other_bonus : $job_data->other_bonus;
                $update_array['four_zero_one_k'] = isset($request->four_zero_one_k) ? $request->four_zero_one_k : $job_data->four_zero_one_k;
                $update_array['health_insaurance'] = isset($request->health_insaurance) ? $request->health_insaurance : $job_data->health_insaurance;
                $update_array['dental'] = isset($request->dental) ? $request->dental : $job_data->dental;
                $update_array['vision'] = isset($request->vision) ? $request->vision : $job_data->vision;
                $update_array['actual_hourly_rate'] = isset($request->actual_hourly_rate) ? $request->actual_hourly_rate : $job_data->actual_hourly_rate;
                $update_array['overtime'] = isset($request->overtime) ? $request->overtime : $job_data->overtime;
                $update_array['holiday'] = isset($request->holiday) ? $request->holiday : $job_data->holiday;
                $update_array['on_call'] = isset($request->on_call) ? $request->on_call : $job_data->on_call;
                $update_array['orientation_rate'] = isset($request->orientation_rate) ? $request->orientation_rate : $job_data->orientation_rate;
                $update_array['weekly_non_taxable_amount'] = isset($request->weekly_non_taxable_amount) ? $request->weekly_non_taxable_amount : $job_data->weekly_non_taxable_amount;
                $update_array['description'] = isset($request->description) ? $request->description : $job_data->description;
                $update_array['weekly_taxable_amount'] = isset($request->hours_shift) && isset($request->actual_hourly_rate) ? $request->hours_shift * $request->actual_hourly_rate : null;
                $update_array['employer_weekly_amount'] = isset($request->weekly_non_taxable_amount) && isset($update_array['weekly_taxable_amount']) ? $request->weekly_non_taxable_amount + $update_array['weekly_taxable_amount'] : null;
                $update_array['goodwork_weekly_amount'] = isset($update_array['weekly_taxable_amount']) ? $update_array['weekly_taxable_amount'] * 0.05 : 0;
                $update_array['total_employer_amount'] = isset($request->preferred_assignment_duration) && isset($update_array['employer_weekly_amount']) && isset($request->sign_on_bonus) && isset($request->completion_bonus) ? $request->preferred_assignment_duration + $update_array['employer_weekly_amount'] + $request->sign_on_bonus + $request->completion_bonus : null;
                $update_array['total_goodwork_amount'] = isset($request->preferred_assignment_duration) && isset($update_array['goodwork_weekly_amount']) ? $request->preferred_assignment_duration + $update_array['goodwork_weekly_amount'] : null;
                $update_array['total_contract_amount'] = isset($update_array['total_goodwork_amount']) && isset($update_array['total_employer_amount']) ? $update_array['total_goodwork_amount'] + isset($update_array['total_employer_amount']) : null;
                $update_array['weekly_pay'] = isset($job_data->weekly_pay) ? $job_data->weekly_pay : null;
                $update_array['tax_status'] = isset($request->tax_status) ? $request->tax_status : null;
                if ($request->funcionalityType == 'createdraft') {
                    $update_array['status'] = 'Draft';
                    $update_array['is_draft'] = '1';
                    $update_array['is_counter'] = '0';
                    $message = 'Draft Created Successfully !';
                } else {
                    $update_array['status'] = 'Offered';
                    $update_array['is_draft'] = '0';
                    $update_array['is_counter'] = '1';
                    $message = 'Counter Offer Created Successfully !';
                }
                /* create job */
                $update_array['created_by'] = isset($job_data->recruiter_id) && $job_data->recruiter_id != '' ? $job_data->recruiter_id : '';
                
                $offerexist = DB::table('offers')
                    ->where(['job_id' => $request->job_id, 'worker_user_id' => $nurse->id, 'recruiter_id' => $request->recruiter_id])
                    ->first();
                if ($offerexist) {
                    $job = DB::table('offers')
                        ->where(['job_id' => $request->job_id, 'worker_user_id' => $nurse->id, 'recruiter_id' => $request->recruiter_id])
                        ->update($update_array);
                } else {
                    $job = Offer::create($update_array);
                }
                /* create job */
                if ($job) {
                    $responseData = [
                        'status' => 'success',
                        'message' => $message,
                    ];
                } else {
                    $responseData = [
                        'status' => 'error',
                        'message' => $message,
                    ];
                }
            } catch (\Exception $e) {
                $responseData = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }
        }
        return response()->json($responseData);
    }

    public function sendJobOfferRecruiter(Request $request)
    {
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
                }
            } elseif ($request->type == 'offersend') {
                $update_array['is_counter'] = '0';
                $update_array['is_draft'] = '0';
                $update_array['status'] = 'Done';
                $job = DB::table('offers')
                    ->where(['id' => $request->id])
                    ->update($update_array);
                if ($job) {
                    $responseData = [
                        'status' => 'success',
                        'message' => 'Job Accepted successfully',
                    ];
                }
            }
            return response()->json($responseData);
        }
    }
}
