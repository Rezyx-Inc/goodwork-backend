<?php

namespace Modules\Employer\Http\Controllers;

use App\Enums\Specialty;
use DB;
use DateTime;
use App\Models\Job;
use App\Models\Speciality;
use App\Models\User;
use App\Models\States;
use App\Models\Cities;
use App\Models\Offer;
use App\Models\Profession;
use App\Models\Nurse;
use App\Models\Keyword;
use App\Models\Facility;
use App\Models\JobAsset;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;

class OpportunitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $created_by = Auth::guard('employer')->user()->id;
        $darftJobs = Job::where('created_by', $created_by)->where('active',0)->select('id','job_name','job_type','preferred_specialty','proffesion','job_city','job_state','preferred_work_location','preferred_assignment_duration','weekly_pay','description','preferred_work_area','preferred_experience','preferred_shift_duration','preferred_days_of_the_week','preferred_hourly_pay_rate','preferred_shift','job_function','job_cerner_exp','job_meditech_exp','seniority_level','job_epic_exp','job_other_exp','start_date','end_date','hours_shift','hours_per_week','responsibilities','qualifications')->get();
        $publishedJobs = Job::where('created_by', $created_by)->where('active',1)->where('is_open','1')->select('is_open','id','job_name','job_type','preferred_specialty','proffesion','job_city','job_state','preferred_work_location','preferred_assignment_duration','weekly_pay','description','preferred_work_area','preferred_experience','preferred_shift_duration','preferred_days_of_the_week','preferred_hourly_pay_rate','preferred_shift','job_function','job_cerner_exp','job_meditech_exp','seniority_level','job_epic_exp','job_other_exp','start_date','end_date','hours_shift','hours_per_week','responsibilities','qualifications')->get();
        $onholdJobs = Job::where('created_by', $created_by)->where('active',1)->where('is_open','0')->select('is_open','id','job_name','job_type','preferred_specialty','proffesion','job_city','job_state','preferred_work_location','preferred_assignment_duration','weekly_pay','description','preferred_work_area','preferred_experience','preferred_shift_duration','preferred_days_of_the_week','preferred_hourly_pay_rate','preferred_shift','job_function','job_cerner_exp','job_meditech_exp','seniority_level','job_epic_exp','job_other_exp','start_date','end_date','hours_shift','hours_per_week','responsibilities','qualifications')->get();
        $specialities = Speciality::select('full_name')->get();
        $proffesions = Profession::select('full_name')->get();

        return view('employer::employer/opportunitiesmanager',compact('darftJobs','specialities','proffesions','publishedJobs','onholdJobs'));
      // return response()->json(['success' => false, 'message' =>  $publishedJobs]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

        $distinctFilters = Keyword::distinct()->pluck('filter');
        $allKeywords = [];

        $allusstate = States::all()->where('country_code', "US");

        foreach ($distinctFilters as $filter) {
            $keywords = Keyword::where('filter', $filter)->get();
            $allKeywords[$filter] = $keywords;
        }
        return view('employer::employer/createopportunities', compact('allKeywords', 'allusstate'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $check_type = "create")
    {
        $user_id = Auth::guard('employer')->user()->id;
        if ($check_type == "update") {
            $validation_array = ['job_id' => 'required'];
        } else if ($check_type == "published") {
            $validation_array = ['job_id' => 'required'];
        } else if ($check_type == "hidejob" || $check_type == "unhidejob") {
            $validation_array = ['job_id' => 'required'];
        } else {
            $validation_array = [
                'job_name' => 'required',
                'type' => 'required'
            ];
        }
        $validator = Validator::make($request->all(), $validation_array);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()]);
        } else {
            $facility_id = Facility::where('created_by', $user_id)->get()->first();
            if (isset($facility_id) && !empty($facility_id)) {
                $facility_id = $facility_id->id;
            } else {
                $facility_id =  '';
            }

            $update_array = $request->except('job_id');

            $jobexist = Job::where('id', $request->job_id)->first();

            if($jobexist){
                $newstring = "";
                if ($request->preferred_specialty) {
                    $update_array['preferred_specialty'] = preg_replace('/^,/', '', $jobexist->preferred_specialty . ',' . $request->preferred_specialty);
                }
                if ($request->preferred_experience) {
                    $update_array['preferred_experience'] = preg_replace('/^,/', '', $jobexist->preferred_experience . ',' . $request->preferred_experience);
                }
                if ($request->vaccinations) {
                    $update_array['vaccinations'] = preg_replace('/^,/', '', $jobexist->vaccinations . ',' . $request->vaccinations);
                }
                if ($request->certificate) {
                    $update_array['certificate'] = preg_replace('/^,/', '', $jobexist->certificate . ',' . $request->certificate);
                }
            }

            // if (isset($request->job_video) && $request->job_video != "") {
            //     if (preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->job_video, $vresult)) {
            //         $youTubeID = $this->parse_youtube($request->job_video);
            //         $embedURL = 'https://www.youtube.com/embed/' . $youTubeID[1];
            //         $update_array['video_embed_url'] = $embedURL;
            //     } elseif (preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->job_video, $vresult)) {
            //         $vimeoID = $this->parse_vimeo($request->job_video);
            //         $embedURL = 'https://player.vimeo.com/video/' . $vimeoID[1];
            //         $update_array['video_embed_url'] = $embedURL;
            //     }
            // }

            if ($check_type == "published") {
                $jobexist = Job::find($request->job_id);
                if (isset($jobexist->job_type))
                    if (isset($jobexist->job_name))
                        if (isset($jobexist->job_location))
                            if (isset($jobexist->preferred_shift))
                                // if (isset($jobexist->preferred_days_of_the_week))
                                if (isset($jobexist->facility))
                                    // if (isset($jobexist->weekly_pay))
                                    $job = Job::where(['id' => $request->job_id])->update(['active' => '1']);
                                // else
                                //     return response()->json(['message' => 'Something went wrong! Please check weekly_pay']);
                                else
                                    return response()->json(['message' => 'Something went wrong! Please check Facility']);
                            // else
                            // return response()->json(['message' => 'Something went wrong! Please check preferred_days_of_the_week']);
                            else
                                return response()->json(['message' => 'Something went wrong! Please check Shift of Day']);
                        else
                            return response()->json(['message' => 'Something went wrong! Please check Professional Licensure']);
                    else
                        return response()->json(['message' => 'Something went wrong! Please check Job Name']);
                else
                    return response()->json(['message' => 'Something went wrong! Please check Job Type']);
            } else if ($check_type == "update") {
                /* update job */
                if (isset($request->job_id)) {
                    $job_id = $request->job_id;

                    $job = Job::where(['id' => $job_id])->update($update_array);


                    $jobDetails = Job::where('id', $request->job_id)->first();

                    if($request->hours_per_week || $request->actual_hourly_rate || $request->weekly_taxable_amount || $request->weekly_non_taxable_amount || $request->employer_weekly_amount || $request->sign_on_bonus || $request->completion_bonus || $request->weeks_assignment || $request->goodwork_weekly_amount || $request->total_contract_amount || $request->total_employer_amount || $request->total_goodwork_amount){
                        $update_amount = [];
                        if(isset($jobDetails->hours_per_week) && isset($jobDetails->actual_hourly_rate)){
                            $update_amount['weekly_taxable_amount'] = $jobDetails->hours_per_week * $jobDetails->actual_hourly_rate;
                        }
                        if(isset($jobDetails->weekly_taxable_amount) && isset($request->weekly_non_taxable_amount)){
                            $update_amount['employer_weekly_amount'] = $jobDetails->weekly_taxable_amount + $request->weekly_non_taxable_amount;
                            $update_amount['goodwork_weekly_amount'] = $jobDetails->employer_weekly_amount * 0.05;
                        }
                        if((isset($request->weeks_assignment) && isset($jobDetails->employer_weekly_amount)) || isset($request->sign_on_bonus) || isset($request->completion_bonus)){
                            $update_amount['total_employer_amount'] = ($jobDetails->weeks_assignment * $jobDetails->employer_weekly_amount) + $jobDetails->sign_on_bonus + $jobDetails->completion_bonus;
                        }
                        if(isset($request->weeks_assignment) && isset($jobDetails->goodwork_weekly_amount)){
                            $update_amount['total_goodwork_amount'] = $jobDetails->weeks_assignment * $jobDetails->goodwork_weekly_amount;
                        }
                        if(isset($jobDetails->total_employer_amount) && isset($jobDetails->total_goodwork_amount)){
                            $update_amount['total_contract_amount'] = $jobDetails->total_employer_amount + $jobDetails->total_goodwork_amount;
                        }
                        $job = Job::where(['id' => $job_id])->update($update_amount);
                    }
                } else {
                    // return back()->with('error', 'Something went wrong! Please check job_id');
                    return response()->json(['message' => 'Something went wrong! Please check job_id']);
                }
                /* update job */
            } else if ($check_type == "hidejob" || $check_type == "unhidejob") {
                /* update job */
                // is_hidden
                if (isset($request->job_id)) {
                    $job_id = $request->job_id;
                    $job = Job::where(['id' => $job_id])->update(['is_hidden'=> $check_type == "hidejob" ? '1': '0']);
                } else {
                    // return back()->with('error', 'Something went wrong! Please check job_id');
                    return response()->json(['message' => 'Something went wrong! Please check job_id']);
                }
                /* update job */
            } else {
                /* create job */
                $update_array["created_by"] = (isset($user_id) && $user_id != "") ? $user_id : "";
                $update_array["employer_id"] = (isset($user_id) && $user_id != "") ? $user_id : "";
                $update_array["goodwork_number"] = uniqid();
                $update_array["active"] = "0";
                $job = Job::create($update_array);
                Job::where('id', $job['id'])->update(['goodwork_number' => $job['id']]);
                $job = Job::where('id', $job['id'])->first();
                /* create job */
            }

            if (!empty($job) && $job_photos = $request->file('job_photos')) {
                foreach ($job_photos as $job_photo) {
                    $job_photo_name_full = $job_photo->getClientOriginalName();
                    $job_photo_name = pathinfo($job_photo_name_full, PATHINFO_FILENAME);
                    $job_photo_ext = $job_photo->getClientOriginalExtension();
                    $job_photo_finalname = $job_photo_name . '_' . time() . '.' . $job_photo_ext;
                    //Upload Image
                    $job_id_val = ($check_type == "update") ? $job_id : $job->id;
                    $job_photo->storeAs('assets/jobs/' . $job_id_val, $job_photo_finalname);
                    JobAsset::create(['job_id' => $job_id_val, 'name' => $job_photo_finalname, 'filter' => 'job_photos']);
                }
            }
            if ($job) {
                if($check_type == 'hidejob'){
                    $check_type = 'hide';
                }else if($check_type == "unhidejob"){
                    $check_type = 'unhide';
                }
                return response()->json(['message' => "Job {$check_type} successfully", 'job_id' => $job['id'], 'goodwork_number' => $job['goodwork_number']]);
                // return back()->with('success', "Job " . $check_type . "d successfully");
            } else {
                return response()->json(['message' => 'Failed to create job, Please try again later']);
                // return back()->with('error', "Failed to create job, Please try again later");
            }
        }
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
    public function getJobListing(Request $request)
    {
        $type = $request->type;
        $allspecialty = [];
        $allvaccinations = [];
        $allcertificate = [];

        // $formtype = $request->formtype;
        // $activestatus = "1";
        // if($type == 'draft'){
        //     $activestatus = "0";
        // }

        if ($type == "drafts") {
            $jobLists = Job::where(['active' => '0'])->get();
        } else if ($type == "hidden") {
            $jobLists = Job::where(['is_hidden' => '1'])->get();
        } else if ($type == "closed") {
            $jobLists = Job::where(['is_closed' => '1'])->get();
        } else {
            $jobLists = Job::where(['active' => '1', 'is_hidden' => '0', 'is_closed' => '0'])->get();
        }

        if ($request->type != 'draft') {
            if (0 >= count($jobLists)) {
                $responseData = [
                    'joblisting' => '<div class="text-center"><span>No Job</span></div>',
                    'jobdetails' => '<div class="text-center"><span>Data Not found</span></div>',
                ];
                return response()->json($responseData);
            }
        }
        $data = "";
        $data2 = "";
        foreach ($jobLists as $key => $value) {
            if ($value) {
                // $userapplied = DB::table('offer_jobs')->where(['job_id' => $value->id])->count();
                $userapplied = Offer::where('job_id', $value->id)->count();

                $data .= '
                <div class="ss-chng-appli-slider-sml-dv ' . ( $request->id == $value['id'] ? 'active' : '') . '" id="opportunity-list" onclick="opportunitiesType(\'' . $type . '\', \'' . $value['id'] . '\', \'jobdetails\')">
                    <ul class="ss-cng-appli-slid-ul1">
                        <li class="d-flex">
                            <p>' . $value['job_type'] . '</p>
                            <span>' . $userapplied . ' Workers Applied</span>
                        </li>
                    </ul>
                    <h4 class="job-title">' . $value['job_name'] . '</h4>
                    <ul class="ss-cng-appli-slid-ul2 d-block">
                        <li class="d-inline-block">' . ($value['job_location'] ? ($value['job_location']) . ', ' : "") . ' ' . $value['job_state'] . '</li>
                        <li class="d-inline-block">' . $value['preferred_shift'] . '</li>
                        <li class="d-inline-block">' . $value['preferred_days_of_the_week'] . ' wks</li>
                    </ul>
                    <ul class="ss-cng-appli-slid-ul3">
                        <li><span>' . $value['facility'] . '</span></li>
                        <li><h6>$' . $value['hours_per_week'] . '/wk</h6></li>
                    </ul>
                </div>';
            }
        }
        if ($data == "") {
            $data = '<div class="text-center"><span>No Opportunities</span></div>';
        }

        // $offerdetails = "";

        if (isset($request->id)) {
            $jobdetails = Job::select('jobs.*', 'job_references.name', 'job_references.min_title_of_reference', 'job_references.recency_of_reference')
                ->leftJoin('job_references', 'job_references.job_id', '=', 'jobs.id')
                ->where(['jobs.id' => $request->id])->first();
        } else {
            if ($type == "drafts") {
                $jobdetails = Job::select('jobs.*', 'job_references.name', 'job_references.min_title_of_reference', 'job_references.recency_of_reference')
                    ->leftJoin('job_references', 'job_references.job_id', '=', 'jobs.id')
                    ->where(['jobs.active' => '0'])->first();
            } else if ($type == "hidden") {
                $jobdetails = Job::select('jobs.*', 'job_references.name', 'job_references.min_title_of_reference', 'job_references.recency_of_reference')
                    ->leftJoin('job_references', 'job_references.job_id', '=', 'jobs.id')
                    ->where(['jobs.is_hidden' => '1'])->first();
            } else if ($type == "closed") {
                $jobdetails = Job::select('jobs.*', 'job_references.name', 'job_references.min_title_of_reference', 'job_references.recency_of_reference')
                    ->leftJoin('job_references', 'job_references.job_id', '=', 'jobs.id')
                    ->where(['jobs.is_closed' => '1'])->first();
            } else {
                $jobdetails = Job::select('jobs.*', 'job_references.name', 'job_references.min_title_of_reference', 'job_references.recency_of_reference')
                    ->leftJoin('job_references', 'job_references.job_id', '=', 'jobs.id')
                    ->where(['jobs.active' => '1', 'jobs.is_hidden' => '0', 'jobs.is_closed' => '0'])->first();
            }
        }
        $userdetails = $jobdetails ? User::where('id', $jobdetails['created_by'])->first() : "";
        $jobappliedcount = $jobdetails ? Offer::where('job_id', $jobdetails->id)->count() : '';
        $jobapplieddetails = $jobdetails ? Offer::where('job_id', $jobdetails->id)->get() : "";


        // $jobapplieddetails = $nursedetails ? Offer::where(['status' => $activestatus, 'nurse_id' => $offerdetails->nurse_id])->get() : "";
        // $jobappliedcount = $nursedetails ? Offer::where(['status' => $activestatus, 'nurse_id' => $offerdetails->nurse_id])->count() : "";

        if ($request->formtype == 'useralldetails') {
            $offerdetails = Offer::where('id', $request->id)->first();
            $nursedetails = Nurse::where('id', $offerdetails['nurse_id'])->first();
            $userdetails = User::where('id', $nursedetails->user_id)->first();
            $jobdetails = Job::where('id', $offerdetails['job_id'])->first();

            $data2 .= '
                <ul class="ss-cng-appli-hedpfl-ul">
                    <li>
                        <span>' . $offerdetails['nurse_id'] . '</span>
                        <h6>
                            <img src="' . asset('public/images/nurses/profile/' . $userdetails['image']) . '" onerror="this.onerror=null;this.src=' . '\'' . asset('public/frontend/img/profile-pic-big.png') . '\'' . ';" id="preview" width="50px" height="50px" style="object-fit: cover;" class="rounded-3" alt="Profile Picture">
                            ' . $userdetails['first_name'] . ' ' . $userdetails['last_name'] . '
                        </h6>
                    </li>
                    <li>';

                        if($request->type == 'Apply'|| $request->type == 'Screening' || $request->type == 'Submitted'){
                            $data2 .= '
                                <button class="rounded-pill ss-apply-btn py-2 border-0 px-4" onclick="applicationType(\'' . $type . '\', \'' . $value->id . '\', \'jobdetails\', \'' . $request->jobid . '\')">Send Offer</button>
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
                        <p>' . ($nursedetails['diploma'] ?? '<u onclick="askWorker(this, \'diploma\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                    </li>
                    <li class="col-md-12">
                        <span class="mt-3">Drivers license</span>
                    </li>
                    <li class="col-md-6">
                        <h6>Required</h6>
                    </li>
                    <li class="col-md-6">
                        <p>' . ($nursedetails['driving_license'] ?? '<u onclick="askWorker(this, \'driving_license\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                    </li>
                    <li class="col-md-12">
                        <span class="mt-3">Worked at Facility Before</span>
                    </li>
                    <li class="col-md-6">
                        <h6>Have you worked here in the last 18 months?</h6>
                    </li>
                    <li class="col-md-6">
                        <p>' . ($nursedetails['worked_at_facility_before'] ?? '<u onclick="askWorker(this, \'worked_at_facility_before\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                    </li>
                    <li class="col-md-12">
                        <span class="mt-3">SS# or SS Card</span>
                    </li>
                    <li class="col-md-6">
                        <h6>Last 4 digits of SS#</h6>
                    </li>
                    <li class="col-md-6">
                        <p>' . ($nursedetails['worker_ss_number'] ?? '----' ) . '</p>
                    </li>
                    <li class="col-md-12">
                        <span class="mt-3">Profession</span>
                    </li>
                    <li class="col-md-6">
                        <h6>' . ($jobdetails->profession ?? '----') . '</h6>
                    </li>
                    <li class="col-md-6">
                        <p>' . ($nursedetails['highest_nursing_degree'] ?? '<u onclick="askWorker(this, \'highest_nursing_degree\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</p>
                    </li>
                    <li class="col-md-12">
                        <span class="mt-3">Specialty</span>
                    </li>';
                    if(isset($jobdetails->specialty)){
                        foreach (explode(",", $nursedetails['specialty']) as $key => $value) {
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
                        <h6>' . ($jobdetails['job_state'] ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6 ' . ($jobdetails['job_state'] ? '' : 'd-none' ) . '">
                        <p>' . ($nursedetails['nursing_license_state'] ?? '<u onclick="askWorker(this, \'nursing_license_state\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                    </div>
                    <div class="col-md-12">
                        <span class="mt-3">Experience</span>
                    </div>
                    <div class="col-md-6">
                        <h6>' . ($jobdetails['preferred_experience'] ?? '----') . ' Years </h6>
                    </div>
                    <div class="col-md-6 ' . ($jobdetails['preferred_experience'] ? '' : 'd-none' ) . '">
                        <p>' . (array_sum(explode(",", $nursedetails['experience'])) ? (array_sum(explode(",", $nursedetails['experience'])) . ' years') : '<u onclick="askWorker(this, \'experience\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>') . '</p>
                    </div>
                    <div class="col-md-12">
                        <span class="mt-3">Vaccinations & Immunizations</span>
                    </div>';
                    if(isset($jobdetails['vaccinations'])){
                        foreach (explode(",", $jobdetails['vaccinations']) as $key => $value) {
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
                    if(isset($jobdetails['number_of_references'])){
                        foreach (explode(",", $jobdetails['number_of_references']) as $key => $value) {
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
                    if(isset($jobdetails['certificate'])){
                        foreach (explode(",", $nursedetails['certificate']) as $key => $value) {
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
                            <h6>' . ($jobdetails['skills'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['skill'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['skills'] ?? '<u onclick="askWorker(this, \'skills\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Eligible to work in the US</span>
                        </div>
                        <div class="col-md-6">
                            <h6>Required</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['profession'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['eligible_work_in_us'] ?? '<u onclick="askWorker(this, \'eligible_work_in_us\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Urgency</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['urgency'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['urgency'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_urgency'] ?? '<u onclick="askWorker(this, \'worker_urgency\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3"># of Positions Available</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['position_available'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['position_available'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['available_position'] ?? '<u onclick="askWorker(this, \'available_position\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">MSP</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['msp'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['msp'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['MSP'] ?? '<u onclick="askWorker(this, \'MSP\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">VMS</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['vms'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['vms'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['VMS'] ?? '<u onclick="askWorker(this, \'VMS\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Block scheduling</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['block_scheduling'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['block_scheduling'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['block_scheduling'] ?? '<u onclick="askWorker(this, \'block_scheduling\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Float requirements</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['float_requirement'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['float_requirement'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['float_requirement'] ?? '<u onclick="askWorker(this, \'float_requirement\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Facility Shift Cancellation Policy</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['facility_shift_cancelation_policy'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['facility_shift_cancelation_policy'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['facility_shift_cancelation_policy'] ?? '<u onclick="askWorker(this, \'facility_shift_cancelation_policy\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Contract Termination Policy</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['contract_termination_policy'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['contract_termination_policy'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['contract_termination_policy'] ?? '<u onclick="askWorker(this, \'contract_termination_policy\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Traveler Distance From Facility</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['traveler_distance_from_facility'] ?? '----') . ' miles Maximum</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['traveler_distance_from_facility'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['distance_from_your_home'] ?? '<u onclick="askWorker(this, \'distance_from_your_home\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Facility</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['facility'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['facility'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worked_at_facility_before'] ?? '<u onclick="askWorker(this, \'worked_at_facility_before\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Facility`s Parent System</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['facilitys_parent_system'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['facilitys_parent_system'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_facility_parent_system'] ?? '<u onclick="askWorker(this, \'worker_facility_parent_system\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Facility Average Rating</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['facility_average_rating'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['facility_average_rating'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['avg_rating_by_facilities'] ?? '<u onclick="askWorker(this, \'avg_rating_by_facilities\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">employer Average Rating</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['employer_average_rating'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['employer_average_rating'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_avg_rating_by_employers'] ?? '<u onclick="askWorker(this, \'worker_avg_rating_by_employers\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Employer Average Rating</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['employer_average_rating'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['employer_average_rating'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_avg_rating_by_employers'] ?? '<u onclick="askWorker(this, \'worker_avg_rating_by_employers\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Clinical Setting</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['clinical_setting'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['clinical_setting'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['clinical_setting_you_prefer'] ?? '<u onclick="askWorker(this, \'clinical_setting_you_prefer\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Patient ratio</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['Patient_ratio'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['Patient_ratio'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_patient_ratio'] ?? '<u onclick="askWorker(this, \'worker_patient_ratio\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">EMR</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['emr'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['emr'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_emr'] ?? '<u onclick="askWorker(this, \'worker_emr\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Unit</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['Unit'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['Unit'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_unit'] ?? '<u onclick="askWorker(this, \'worker_unit\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Department</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['Department'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['Department'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_department'] ?? '<u onclick="askWorker(this, \'worker_department\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Bed Size</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['Bed_Size'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['Bed_Size'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_bed_size'] ?? '<u onclick="askWorker(this, \'worker_bed_size\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Trauma Level</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['Trauma_Level'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['Trauma_Level'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_trauma_level'] ?? '<u onclick="askWorker(this, \'worker_trauma_level\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Scrub Color</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['scrub_color'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['scrub_color'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_scrub_color'] ?? '<u onclick="askWorker(this, \'worker_scrub_color\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Facility City</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['job_city'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['job_city'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_facility_city'] ?? '<u onclick="askWorker(this, \'worker_facility_city\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Facility State Code</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['job_state'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['job_state'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_facility_state_code'] ?? '<u onclick="askWorker(this, \'worker_facility_state_code\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Interview Dates</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . $nursedetails['worker_interview_dates'] . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($nursedetails['worker_interview_dates'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_interview_dates'] ?? '<u onclick="askWorker(this, \'worker_interview_dates\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Start Date</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['start_date'] ? $jobdetails['start_date'] : 'As Soon As Possible') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['start_date'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_start_date'] ?? '<u onclick="askWorker(this, \'worker_start_date\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">RTO</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['rto'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['rto'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_as_soon_as_posible'] ?? '<u onclick="askWorker(this, \'worker_as_soon_as_posible\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Shift Time of Day</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['preferred_shift'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['worker_shift_time_of_day'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_shift_time_of_day'] ?? '<u onclick="askWorker(this, \'worker_shift_time_of_day\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Hours/Week</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['hours_per_week'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['hours_per_week'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_hours_per_week'] ?? '<u onclick="askWorker(this, \'worker_hours_per_week\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Guaranteed Hours</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['guaranteed_hours'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['guaranteed_hours'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_guaranteed_hours'] ?? '<u onclick="askWorker(this, \'worker_guaranteed_hours\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Hours/Shift</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['preferred_assignment_duration'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['preferred_assignment_duration'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_weeks_assignment'] ?? '<u onclick="askWorker(this, \'worker_weeks_assignment\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Weeks/Assignment</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['preferred_assignment_duration'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['preferred_assignment_duration'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_weeks_assignment'] ?? '<u onclick="askWorker(this, \'worker_weeks_assignment\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Shifts/Week</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['weeks_shift'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['weeks_shift'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_shifts_week'] ?? '<u onclick="askWorker(this, \'worker_shifts_week\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Referral Bonus</span>
                        </div>
                        <div class="col-md-6">
                            <h6>$' . ($jobdetails['referral_bonus'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['referral_bonus'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_referral_bonus'] ?? '<u onclick="askWorker(this, \'worker_referral_bonus\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Sign-On Bonus</span>
                        </div>
                        <div class="col-md-6">
                            <h6>$' . ($jobdetails['sign_on_bonus'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['sign_on_bonus'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_sign_on_bonus'] ?? '<u onclick="askWorker(this, \'worker_sign_on_bonus\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Completion Bonus</span>
                        </div>
                        <div class="col-md-6">
                            <h6>$' . ($jobdetails['completion_bonus'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['completion_bonus'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_completion_bonus'] ?? '<u onclick="askWorker(this, \'worker_completion_bonus\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Extension Bonus</span>
                        </div>
                        <div class="col-md-6">
                            <h6>$' . ($jobdetails['extension_bonus'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['extension_bonus'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_extension_bonus'] ?? '<u onclick="askWorker(this, \'worker_extension_bonus\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Other Bonus</span>
                        </div>
                        <div class="col-md-6">
                            <h6>$' . ($jobdetails['other_bonus'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['other_bonus'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_other_bonus'] ?? '<u onclick="askWorker(this, \'worker_other_bonus\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">401K</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['four_zero_one_k'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['four_zero_one_k'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['how_much_k'] ?? '<u onclick="askWorker(this, \'how_much_k\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Health Insurance</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['health_insaurance'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['health_insaurance'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_health_insurance'] ?? '<u onclick="askWorker(this, \'worker_health_insurance\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Dental</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['dental'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['dental'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_dental'] ?? '<u onclick="askWorker(this, \'worker_dental\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Vision</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['vision'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['vision'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_vision'] ?? '<u onclick="askWorker(this, \'worker_vision\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Actual Hourly rate</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['actual_hourly_rate'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['actual_hourly_rate'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_actual_hourly_rate'] ?? '<u onclick="askWorker(this, \'worker_actual_hourly_rate\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Feels Like $/hr</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['feels_like_per_hour'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['feels_like_per_hour'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_feels_like_hour'] ?? '<u onclick="askWorker(this, \'worker_feels_like_hour\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Overtime</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['overtime'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['overtime'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_overtime'] ?? '<u onclick="askWorker(this, \'worker_overtime\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Holiday</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['holiday'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['holiday'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_holiday'] ?? '<u onclick="askWorker(this, \'worker_holiday\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">On Call</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['on_call'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['on_call'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_on_call'] ?? '<u onclick="askWorker(this, \'worker_on_call\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Call Back</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['call_back'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['call_back'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_call_back'] ?? '<u onclick="askWorker(this, \'worker_call_back\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Orientation Rate</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['orientation_rate'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['orientation_rate'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_orientation_rate'] ?? '<u onclick="askWorker(this, \'worker_orientation_rate\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Weekly Taxable amount</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['weekly_taxable_amount'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['weekly_taxable_amount'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_weekly_taxable_amount'] ?? '<u onclick="askWorker(this, \'worker_weekly_taxable_amount\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Employer Weekly Amount</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['employer_weekly_amount'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['employer_weekly_amount'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_employer_weekly_amount'] ?? '<u onclick="askWorker(this, \'worker_employer_weekly_amount\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Weekly non-taxable amount</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['weekly_non_taxable_amount'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-6 ' . ($jobdetails['weekly_non_taxable_amount'] ? '' : 'd-none' ) . '">
                            <p>' . ($nursedetails['worker_weekly_non_taxable_amount'] ?? '<u onclick="askWorker(this, \'worker_weekly_non_taxable_amount\', \''. $nursedetails['id'] . '\', \''. $jobdetails['id'] . '\')">Ask Worker</u>' ) . '</p>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Goodwork Weekly Amount</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['weekly_taxable_amount'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Total Employer Amount</span>
                        </div>
                        <div class="col-md-12">
                            <h6>' . ($jobdetails['total_employer_amount'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Total Goodwork Amount</span>
                        </div>
                        <div class="col-md-12">
                            <h6>' . ($jobdetails['total_goodwork_amount'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Total Contract Amount</span>
                        </div>
                        <div class="col-md-12">
                            <h6>' . ($jobdetails['total_contract_amount'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-12">
                            <span class="mt-3">Goodwork Number</span>
                        </div>
                        <div class="col-md-6">
                            <h6>' . ($jobdetails['goodwork_number'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-12">

                            <button class="ss-counter-button w-100" onclick="offerSend(\'' . $request->id . '\', \''. $jobdetails['id'] .'\', \'offersend\', \'' . $nursedetails->user_id .'\', \'' . $jobdetails['employer_id'] .'\')">Send Offer</button>
                        </div>
                    </ul>
                </div>
                ';

        }else if ($request->type == 'drafts') {
            $distinctFilters = Keyword::distinct()->pluck('filter');
            $allKeywords = [];

            foreach ($distinctFilters as $filter) {
                $keywords = Keyword::where('filter', $filter)->get();
                $allKeywords[$filter] = $keywords;
            }
            $specialty = explode(',', $jobdetails['preferred_specialty']);
            $experience = explode(',', $jobdetails['preferred_experience']);

            // for ($i = 0; $i < count($specialty); $i++) {
            //     $allspecialty[$specialty[$i]] = $experience[$i];
            // }

            if($specialty && $experience){
                $specialtyCount = count($specialty);
                $experienceCount = count($experience);

                // if ($specialtyCount === $experienceCount) {
                    for ($i = 0; $i < $specialtyCount; $i++) {
                        $allspecialty[$specialty[$i] ?? ""] = $experience[$i] ?? "";
                    }
                // }
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
            $alluscities = DB::table('cities')->where('state_id', $jobdetails['job_state'])->get();

            $allvaccinations = (object)$allvaccinations;
            $allcertificate = (object)$allcertificate;
            $data2 .= '
                <form class="ss-emplor-form-sec" id="send-job-offer">
                    <div class="ss-form-group">
                        <label>Job Name</label>
                        <input type="text" onfocusout="editJob(this)" name="job_name" placeholder="Enter job name" value="' . $jobdetails['job_name'] . '">
                        <input type="text" class="d-none" id="job_id" name="job_id" readonly value="' . $jobdetails['id'] . '">
                        <input type="text" class="d-none" id="employer_id" name="employer_id" readonly value="' . $jobdetails['employer_id'] . '">
                        <input type="text" class="d-none" id="worker_user_id" name="worker_user_id" readonly value="' . $request->id . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Type</label>
                        <select onfocusout="editJob(this)" name="type" id="type">
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
                        <select onfocusout="editJob(this)" name="terms" id="term">
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
                        <textarea name="description" onfocusout="editJob(this)" id="description" placeholder="Enter Job Description" cols="30" rows="2"> ' . $jobdetails['description'] . '</textarea>
                    </div>
                    <div class="ss-form-group">
                        <label>Profession</label>
                        <select onfocusout="editJob(this)" name="profession" id="profession" onchange="getSpecialitiesByProfession(this)">
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
                        <select onfocusout="editJob(this)" name="job_location" id="professional-licensure">
                            <option value="">Select Professional Licensure</option>';
                            if(isset($allusstate)){
                                foreach ($allusstate as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['job_location'] == $value->id ? 'selected' : '') . '>' .$value->name .'</option>';
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
                                    <input type="number" name="preferred_experience" onfocusout="editJob(this)" id="preferred_experience" placeholder="Enter Experience in years">
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
                        <input type="number" onfocusout="editJob(this)" name="number_of_references" placeholder="Enter Number of Reference" value="' . $jobdetails['number_of_references'] . '" maxlength="9">
                    </div>
                    <div class="ss-form-group">
                        <label>Min title of reference</label>
                        <input type="text" onfocusout="editJob(this)" name="min_title_of_reference" placeholder="Enter Min Title of Reference" value="test">
                    </div>
                    <div class="ss-form-group">
                        <label>Recency of reference</label>
                        <input type="number" onfocusout="editJob(this)" name="recency_of_reference" placeholder="Enter Recency of Reference" value="' . $jobdetails['recency_of_reference'] . '">
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
                        <select name="skills" onfocusout="editJob(this)" class="skills mb-3" id="skills">
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
                        <input type="text" onfocusout="editJob(this)" name="urgency" placeholder="Enter Urgency" value="' . $jobdetails['urgency'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label># of Positions Available</label>
                        <input type="number" onfocusout="editJob(this)" name="position_available" placeholder="Enter # of Positions Available" value="' . $jobdetails['position_available'] . '">
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
                        <input type="text" onfocusout="editJob(this)" name="submission_of_vms" placeholder="Enter # of Submissions in VMS" value="' . $jobdetails['submission_of_vms'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Block scheduling</label>
                        <select name="block_scheduling" class="block_scheduling mb-3" id="block_scheduling" onfocusout="editJob(this)" >
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
                        <select name="float_requirement" onfocusout="editJob(this)" class="float_requirement mb-3" id="float_requirement" value="' . $jobdetails['float_requirement'] . '" >
                            <option value="">Enter Float requirements</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Facility Shift Cancellation Policy</label>
                        <select name="facility_shift_cancelation_policy" onfocusout="editJob(this)" class="facility_shift_cancelation_policy mb-3" id="facility_shift_cancelation_policy" value="' . $jobdetails['facility_shift_cancelation_policy'] . '" >
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
                        <select onfocusout="editJob(this)" name="contract_termination_policy" class="contract_termination_policy mb-3" id="contract_termination_policy">
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
                        <input type="number" onfocusout="editJob(this)" name="traveler_distance_from_facility" placeholder="Enter Traveler Distance From Facility" value="' . $jobdetails['traveler_distance_from_facility'] . '" >
                    </div>
                    <div class="ss-form-group">
                        <label>Facility</label>
                        <select name="facility" onfocusout="editJob(this)" class="facility mb-3" id="facility">
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
                        <input type="text" onfocusout="editJob(this)" name="facilitys_parent_system" placeholder="Enter Facility`s Parent System" value="' . $jobdetails['facilitys_parent_system'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Facility Average Rating</label>
                        <input type="number" onfocusout="editJob(this)" name="facility_average_rating" placeholder="No Ratings yet" value="' . $jobdetails['facility_average_rating'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>employer Average Rating</label>
                        <input type="number" onfocusout="editJob(this)" name="employer_average_rating" placeholder="No Ratings yet" value="' . $jobdetails['employer_average_rating'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Employer Average Rating</label>
                        <input type="number" onfocusout="editJob(this)" name="employer_average_rating" placeholder="No Ratings yet" value="' . $jobdetails['employer_average_rating'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Clinical Setting</label>
                        <select name="clinical_setting" onfocusout="editJob(this)" class="clinical_setting mb-3" id="clinical_setting">
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
                        <input type="number" onfocusout="editJob(this)" name="patient_ratio" placeholder="How many patients can you handle?" value="' . $jobdetails['Patient_ratio'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>EMR</label>
                        <select name="emr" onfocusout="editJob(this)" class="emr mb-3" id="emr">
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
                        <input type="number" onfocusout="editJob(this)" name="Unit" placeholder="Enter Unit" value="' . $jobdetails['Unit'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Department</label>
                        <input type="text" onfocusout="editJob(this)" name="department" placeholder="Enter Department" value="' . $jobdetails['Department'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Bed Size</label>
                        <input type="number" onfocusout="editJob(this)" name="bed_size" placeholder="Enter Bed Size" value="' . $jobdetails['Bed_Size'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Trauma Level</label>
                        <input type="number" onfocusout="editJob(this)" name="trauma_level" placeholder="Enter Trauma Level" value="' . $jobdetails['Trauma_Level'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Scrub Color</label>
                        <input type="text" onfocusout="editJob(this)" name="scrub_color" placeholder="Enter Scrub Color" value="' . $jobdetails['scrub_color'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Facility State Code</label>
                        <select onfocusout="editJob(this)" name="job_state" id="facility-state-code" onchange="searchCity(this)">
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
                        <select onfocusout="editJob(this)" name="job_city" id="facility-city">
                            <option value="">Select Facility City</option>';
                            if(isset($alluscities)){
                                foreach ($alluscities as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['job_city'] == $value->id ? 'selected' : '') . '>' .$value->name .'</option>';
                                }
                            }
                        $data2 .= '
                            <option value="florida" ' . ($jobdetails['job_city'] == 'florida' ? 'selected' : '') . '>Florida</option>
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Start Date</label>
                        <input type="date" min="' . date('Y-m-d') . '" onfocusout="editJob(this)" name="start_date" placeholder="Select Date" value="' . $jobdetails['start_date'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Enter RTO</label>
                        <input type="text" onfocusout="editJob(this)" name="rto" placeholder="RTO" value="' . $jobdetails['rto'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Scrub Color</label>
                        <input type="text" onfocusout="editJob(this)" name="scrub_color" placeholder="Enter Scrub Color" value="' . $jobdetails['scrub_color'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Shift Time of Day</label>
                        <select onfocusout="editJob(this)" name="preferred_shift" id="shift-of-day">
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
                        <input type="number" onfocusout="editJob(this)" name="hours_per_week" placeholder="Enter Hours/Week" value="' . $jobdetails['hours_per_week'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Guaranteed Hours</label>
                        <input type="number" onfocusout="editJob(this)" name="guaranteed_hours" placeholder="Enter Guaranteed Hours" value="' . $jobdetails['guaranteed_hours'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Hours/Shift</label>
                        <input type="number" onfocusout="editJob(this)" name="hours_shift" placeholder="Enter Hours/Shift" value="' . $jobdetails['hours_shift'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Weeks/Assignment</label>
                        <input type="number" onfocusout="editJob(this)" name="preferred_assignment_duration" placeholder="Enter Weeks/Assignment" value="' . $jobdetails['preferred_assignment_duration'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Shifts/Week</label>
                        <input type="number" onfocusout="editJob(this)" name="weeks_shift" placeholder="Enter Shifts/Week" value="' . $jobdetails['weeks_shift'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Sign-On Bonus</label>
                        <input type="number" onfocusout="editJob(this)" name="sign_on_bonus" placeholder="Enter Sign-On Bonus" value="' . $jobdetails['sign_on_bonus'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Completion Bonus</label>
                        <input type="number" onfocusout="editJob(this)" name="completion_bonus" placeholder="Enter Completion Bonus" value="' . $jobdetails['completion_bonus'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Extension Bonus</label>
                        <input type="number" onfocusout="editJob(this)" name="extension_bonus" placeholder="Enter Extension Bonus" value="' . $jobdetails['extension_bonus'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Other Bonus</label>
                        <input type="number" onfocusout="editJob(this)" name="other_bonus" placeholder="Enter Other Bonus" value="' . $jobdetails['other_bonus'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Referral Bonus</label>
                        <input type="number" onfocusout="editJob(this)" name="referral_bonus" placeholder="Enter Referral Bonus" value="' . $jobdetails['referral_bonus'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>401K</label>
                        <select onfocusout="editJob(this)" name="four_zero_one_k" id="401k">
                            <option value="">Select</option>';
                            // if(isset($allKeywords['401k'])){
                            //     foreach ($allKeywords['401k'] as $value){
                            //         $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['four_zero_one_k'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                            //     }
                            // }
                        $data2 .= '
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Health Insurance</label>
                        <select onfocusout="editJob(this)" name="health_insaurance" id="health-insurance">
                            <option value="">Select</option>';
                            // if(isset($allKeywords['HealthInsurance'])){
                            //     foreach ($allKeywords['HealthInsurance'] as $value){
                            //         $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['health_insaurance'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                            //     }
                            // }
                        $data2 .= '
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Dental</label>
                        <select onfocusout="editJob(this)" name="dental" id="dental">
                            <option value="">Select</option>';
                            if(isset($allKeywords['Dental'])){
                                foreach ($allKeywords['Dental'] as $value){
                                    $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['dental'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                                }
                            }
                        $data2 .= '
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Vision</label>
                        <select onfocusout="editJob(this)" name="vision" id="vision">
                            <option value="">Select</option>';
                            // if(isset($allKeywords['Vision'])){
                            //     foreach ($allKeywords['Vision'] as $value){
                            //         $data2 .= '<option value="'. $value->id .'" ' . ($jobdetails['vision'] == $value->id ? 'selected' : '') . '>' .$value->title .'</option>';
                            //     }
                            // }
                        $data2 .= '
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Actual Hourly rate</label>
                        <input type="number" onfocusout="editJob(this)" name="actual_hourly_rate" placeholder="Enter Actual Hourly rate" value="' . $jobdetails['actual_hourly_rate'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Feels Like $/hr</label>
                        <input type="number" onfocusout="editJob(this)" name="feels_like_per_hour" placeholder="---" value="' . $jobdetails['feels_like_per_hour'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Overtime</label>
                        <select name="overtime" class="overtime mb-3" id="overtime" onfocusout="editJob(this)" value="' . $jobdetails['overtime'] . '">
                            <option value="">Enter Overtime</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Holiday</label>
                        <input type="date" onfocusout="editJob(this)" name="holiday" placeholder="Select Dates" value="' . $jobdetails['holiday'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>On Call</label>
                        <select name="on_call" class="on_call mb-3" id="on_call" onfocusout="editJob(this)" value="' . $jobdetails['on_call'] . '">
                            <option value="">Enter On Call</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="ss-form-group">
                        <label>Orientation Rate</label>
                        <input type="number" onfocusout="editJob(this)" name="orientation_rate" placeholder="Enter Orientation Rate" value="' . $jobdetails['orientation_rate'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Weekly Taxable amount</label>
                        <input type="number" onfocusout="editJob(this)" name="weekly_taxable_amount" placeholder="---" value="' . $jobdetails['weekly_taxable_amount'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Weekly non-taxable amount</label>
                        <input type="number" onfocusout="editJob(this)" name="weekly_non_taxable_amount" placeholder="---" value="' . $jobdetails['weekly_non_taxable_amount'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Employer Weekly Amount</label>
                        <input type="number" onfocusout="editJob(this)" name="employer_weekly_amount" placeholder="---" value="' . $jobdetails['employer_weekly_amount'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Goodwork Weekly Amount</label>
                        <input type="number" onfocusout="editJob(this)" name="goodwork_weekly_amount" placeholder="---" value="' . $jobdetails['weekly_taxable_amount'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Total Employer Amount</label>
                        <input type="number" onfocusout="editJob(this)" name="total_employer_amount" placeholder="---" value="' . $jobdetails['total_employer_amount'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Total Goodwork Amount</label>
                        <input type="number" onfocusout="editJob(this)" name="total_goodwork_amount" placeholder="---" value="' . $jobdetails['total_goodwork_amount'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Total Contract Amount</label>
                        <input type="text" onfocusout="editJob(this)" name="total_contract_amount" placeholder="---" value="' . $jobdetails['total_contract_amount'] . '">
                    </div>
                    <div class="ss-form-group">
                        <label>Goodwork Number</label>
                        <input type="text" onfocusout="editJob(this)" name="goodwork_number" placeholder="Unique Key" value="' . $jobdetails['goodwork_number'] . '">
                    </div>
                    <div class="ss-account-btns">
                    ';
                    if($jobdetails['active'] == "1"){
                        $data2 .= '
                            <a herf="javascript:void(0)" class="ss-counter-button text-center d-block" onclick="updateJob()">Update Job</a>
                        ';
                    }else{
                        $data2 .= '
                            <a herf="javascript:void(0)" class="d-block ss-reject-offer-btn text-center" id="ss-reject-offer-btn" onclick="changeStatus(\'draft\')">Save As Draft</a>
                            <a herf="javascript:void(0)" class="d-block ss-counter-button text-center" id="submit-job-offer" onclick="changeStatus(\'published\')">Published Now</a>
                        ';
                    }
                    $data2 .= '
                    </div>
                </form>
            ';
        } else {
            $data2 .= '
                    <div class="ss-job-apply-on-tx-bx-hed-dv">
                        <ul>
                            <li>
                                <p>employer</p>
                            </li>
                            <li>
                                <img src="' . asset('public/images/nurses/profile/' . $userdetails->image) . '" onerror="this.onerror=null;this.src=' . '\'' . asset('public/frontend/img/profile-pic-big.png') . '\'' . ';" id="preview" width="50px" height="50px" style="object-fit: cover;" class="rounded-3" alt="Profile Picture">
                                ' . $userdetails->first_name . ' ' . $userdetails->last_name . '
                            </li>
                        </ul>
                        <ul>
                            <li>
                                <span>' . $userdetails->id . '</span>
                                <h6>' . $jobappliedcount . ' Applied</h6>
                            </li>
                        </ul>
                    </div>
                    <div class="ss-jb-aap-on-txt-abt-dv">
                        <h5>About job</h5>
                        <ul>
                            <li>
                                <h6>Employer Name</h6>
                                <p>' . $userdetails->first_name . '</p>
                            </li>
                            <li>
                                <h6>Date Posted</h6>
                                <p>' . $jobdetails['start_date'] . '</p>
                            </li>
                            <li>
                                <h6>Type</h6>
                                <p>' . $jobdetails['type'] . '</p>
                            </li>
                            <li>
                                <h6>Terms</h6>
                                <p>' . $jobdetails['terms'] . '</p>
                            </li>
                        </ul>
                    </div>
                    <div class="ss-jb-aap-on-txt-abt-dv">
                        <div class="application-job-slider">
                            <div class="ss-chng-appli-slider-mn-dv">
                                <h5>Workers Applied (' . $jobappliedcount . ')</h5>
                                <div class="' . ($jobappliedcount > 1 ? 'owl-carousel application-job-slider-owl' : '') . ' application-job-slider">
                                    ';
                                foreach ($jobapplieddetails as $key => $value) {
                                    $nursedetails = Nurse::join('users', 'nurses.user_id', '=', 'users.id')
                                        ->where('nurses.id', $value->nurse_id)
                                        ->select('nurses.*', 'users.*')
                                        ->first();
                                    $totalexp = 0;
                                    $allexp = explode(",", $nursedetails->experience);
                                    $totalexp = 0;
                                    foreach ($allexp as $valuee) {
                                        $totalexp += (int)$valuee;
                                    }

                                    $data2 .= '
                                    <div class="ss-chng-appli-slider-sml-dv ss-expl-applicion-div-box" onclick="opportunitiesType(\'' . $type . '\', \'' . $value->id . '\', \'useralldetails\')"">
                                        <div class="ss-job-id-no-name">
                                            <ul>
                                                <li class="w-50"><span>' . $value->nurse_id . '</span></li>
                                                <li class="w-50">
                                                    <p>Recently Added</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <ul class="ss-expl-applicion-ul1">
                                            <li class="w-auto">
                                                <img src="' . asset('public/images/nurses/profile/' . $nursedetails->image) . '" onerror="this.onerror=null;this.src=' . '\'' . asset('public/frontend/img/profile-pic-big.png') . '\'' . ';" id="preview" width="50px" height="50px" style="object-fit: cover;" class="rounded-3" alt="Profile Picture">
                                            </li>
                                            <li class="w-auto">
                                                <h6>' . $nursedetails->first_name . ' ' . $nursedetails->last_name . '</h6>
                                            </li>
                                        </ul>
                                        <ul class="ss-expl-applicion-ul2 d-block">
                                            <li class="w-auto"><a href="#">' . $nursedetails->highest_nursing_degree . '</a></li>
                                            <li class="w-auto"><a href="#">' . $nursedetails->specialty . '</a></li>
                                            <li class="w-auto"><a href="#">' . $nursedetails->worker_shift_time_of_day . '</a></li>
                                            <li class="w-auto"><a href="#">' . $totalexp . ' Years of Experience</a></li>
                                        </ul>
                                    </div>
                                    ';
                                }

                                $data2 .= '
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ss-jb-apply-on-disc-txt col-md-12 mt-4 mb-3">
                        <h5>Description</h5>
                        <p class="mb-3">' . ($userdetails->description ?? "----") . '</p>
                    </div>
                ';

            $data2 .= '
            <div class="ss-job-ap-on-offred-new-dv">
                <ul class="row ss-s-jb-apl-on-inf-txt-ul">
                    <div class="col-md-6">
                        <p class="mt-3">Profession</p>
                        <h6>' . ($jobdetails->profession ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Specialty</p>
                        <h6>' . ($jobdetails->preferred_specialty ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Professional Licensure</p>
                        <h6>' . ($jobdetails->job_location ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Experience</p>
                        <h6>' . ($jobdetails->preferred_experience ?? '0') . ' Years </h6>
                    </div>
                ';
            if (isset($jobdetails->vaccinations)) {
                foreach (explode(",", $jobdetails->vaccinations) as $key => $value) {
                    if (isset($value)) {
                        $data2 .= '
                            <div class="col-md-6">
                                <p class="mt-3">Vaccinations & Immunizations</p>
                                <h6>' . $value . '</h6>
                            </div>
                            ';
                    }
                }
            }

            // if($jobdetails->job_name == 'New Opportunity'){
            //     dd($jobdetails);
            // }

            $data2 .= '
                <div class="col-md-6">
                    <p class="mt-3">No of References</p>
                    <h6>' . ($jobdetails->number_of_references ?? '----') . '</h6>
                </div>
                <div class="col-md-6">
                    <p class="mt-3">Min title of reference</p>
                    <h6>' . ($jobdetails->min_title_of_reference ?? '----') . '</h6>
                </div>
                <div class="col-md-6">
                    <p class="mt-3">Recency of reference</p>
                    <p class="mb-3">' . ($jobdetails->recency_of_reference ?? "----") . '</p>
                </div>';
            if (isset($jobdetails->certificate)) {
                foreach (explode(",", $jobdetails->certificate) as $key => $value) {
                    if (isset($value)) {
                        $data2 .= '
                            <div class="col-md-6 ">
                                <p class="mt-3">Certifications</p>
                                <h6>' . $value . ' </h6>
                            </div>
                            ';
                    }
                }
            }
            $data2 .= '
                    <div class="col-md-6">
                        <p class="mt-3">Skills checklist</p>
                        <h6>' . ($jobdetails->skills ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">ACLS</p>
                        <h6>Required</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">PALS</p>
                        <h6>Required</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">CCRN</p>
                        <h6>Required</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3"># of Positions Available</p>
                        <h6>' . ($jobdetails->position_available ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">MSP</p>
                        <h6>' . ($jobdetails->msp ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">VMS</p>
                        <h6>' . ($jobdetails->vms ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3"># of Submissions in VMS</p>
                        <h6>' . ($jobdetails->submission_of_vms ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Block scheduling</p>
                        <h6>' . ($jobdetails->block_scheduling ?? '----') . '</h6>
                    </div>




                    <div class="col-md-6">
                        <p class="mt-3">Facility Shift Cancellation Policy</p>
                        <h6>' . ($jobdetails->facility_shift_cancelation_policy ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Contract Termination Policy</p>
                        <h6>' . ($jobdetails->contract_termination_policy ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Traveler Distance From Facility</p>
                        <h6>' . ($jobdetails->traveler_distance_from_facility ?? '----') . ' miles Maximum</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Facility</p>
                        <h6>' . ($jobdetails->facility ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Facility`s Parent System</p>
                        <h6>' . ($jobdetails->facilitys_parent_system ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Facility Average Rating</p>
                        <h6>' . ($jobdetails->facility_average_rating ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">employer Average Rating</p>
                        <h6>' . ($jobdetails->employer_average_rating ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Employer Average Rating</p>
                        <h6>' . ($jobdetails->employer_average_rating ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Clinical Setting</p>
                        <h6>' . ($jobdetails->clinical_setting ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Patient ratio</p>
                        <h6>' . ($jobdetails->Patient_ratio ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">EMR</p>
                        <h6>' . ($jobdetails->emr ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Unit</p>
                        <h6>' . ($jobdetails->Unit ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Department</p>
                        <h6>' . ($jobdetails->Department ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Bed Size</p>
                        <h6>' . ($jobdetails->Bed_Size ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Trauma Level</p>
                        <h6>' . ($jobdetails->Trauma_Level ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Scrub Color</p>
                        <h6>' . ($jobdetails->scrub_color ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Facility City</p>
                        <h6>' . ($jobdetails->job_city ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Facility State Code</p>
                        <h6>' . ($jobdetails->job_state ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Start Date</p>
                        <h6>' . ($jobdetails->start_date ? $jobdetails->start_date : 'As Soon As Possible') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">RTO</p>
                        <h6>' . ($jobdetails->rto ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Shift Time of Day</p>
                        <h6>' . ($jobdetails->preferred_shift ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Hours/Week</p>
                        <h6>' . ($jobdetails->hours_per_week ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Guaranteed Hours</p>
                        <h6>' . ($jobdetails->guaranteed_hours ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Hours/Shift</p>
                        <h6>' . ($jobdetails->preferred_assignment_duration ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Weeks/Assignment</p>
                        <h6>' . ($jobdetails->preferred_assignment_duration ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Shifts/Week</p>
                        <h6>' . ($jobdetails->weeks_shift ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Referral Bonus</p>
                        <h6>$' . ($jobdetails->referral_bonus ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Sign-On Bonus</p>
                        <h6>$' . ($jobdetails->sign_on_bonus ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Completion Bonus</p>
                        <h6>$' . ($jobdetails->completion_bonus ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Extension Bonus</p>
                        <h6>$' . ($jobdetails->extension_bonus ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Other Bonus</p>
                        <h6>$' . ($jobdetails->other_bonus ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">401K</p>
                        <h6>' . ($jobdetails->four_zero_one_k ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Health Insurance</p>
                        <h6>' . ($jobdetails->health_insaurance ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Dental</p>
                        <h6>' . ($jobdetails->dental ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Vision</p>
                        <h6>' . ($jobdetails->vision ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Actual Hourly rate</p>
                        <h6>' . ($jobdetails->actual_hourly_rate ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Feels Like $/hr</p>
                        <h6>' . ($jobdetails->feels_like_per_hour ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Overtime</p>
                        <h6>' . ($jobdetails->overtime ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Holiday</p>
                        <h6>' . ($jobdetails->holiday ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">On Call</p>
                        <h6>' . ($jobdetails->on_call ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Call Back</p>
                        <h6>' . ($jobdetails->call_back ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Orientation Rate</p>
                        <h6>' . ($jobdetails->orientation_rate ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Weekly Taxable amount</p>
                        <h6>' . ($jobdetails->weekly_taxable_amount ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Employer Weekly Amount</p>
                        <h6>' . ($jobdetails->employer_weekly_amount ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Weekly non-taxable amount</p>
                        <h6>' . ($jobdetails->weekly_non_taxable_amount ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Goodwork Weekly Amount</p>
                        <h6>' . ($jobdetails->weekly_taxable_amount ?? '----') . '</h6>
                    </div>
                    <div class="col-md-12">
                        <p class="mt-3">Total Employer Amount</p>
                        <h6>' . ($jobdetails->total_employer_amount ?? '----') . '</h6>
                    </div>
                    <div class="col-md-12">
                        <p class="mt-3">Total Goodwork Amount</p>
                        <h6>' . ($jobdetails->total_goodwork_amount ?? '----') . '</h6>
                    </div>
                    <div class="col-md-12">
                        <p class="mt-3">Total Contract Amount</p>
                        <h6>' . ($jobdetails->total_contract_amount ?? '----') . '</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mt-3">Goodwork Number</p>
                        <h6>' . ($jobdetails->goodwork_number ?? '----') . '</h6>
                    </div>';

                    if($type != "closed"){
                        $data2 .= '
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">';
                                if($type == "hidden"){
                                    $data2 .= '<a href="javascript:void(0)" class="ss-reject-offer-btn d-block" onclick="changeStatus(\'unhidejob\', \'' . $jobdetails->id . '\')">Unhide Job</a>';
                                }else{
                                    $data2 .= '<a href="javascript:void(0)" class="ss-reject-offer-btn d-block" onclick="changeStatus(\'hidejob\', \'' . $jobdetails->id . '\')">Hide Job</a>';
                                }
                            $data2 .= '
                                </div>
                                <div class="col-md-6">
                                    <a href="javascript:void(0)" class="ss-send-offer-btn d-block" onclick="opportunitiesType(\'drafts\', \'' . $jobdetails->id . '\',\'jobdetails\')">Edit Job</a>
                                </div>
                            </div>
                        </div>';
                    }
                    $data2 .= '
                </ul>
                </div>
            ';
            $data2 .= '</div>';
            if ($data2 == "") {
                $data2 = '<div class="text-center"><span>Data Not found</span></div>';
            }
        }

        $responseData = [
            'joblisting' => $data,
            'jobdetails' => $data2,
            'allspecialty' => $allspecialty,
            'allcertificate' => $allcertificate,
            'allvaccinations' => $allvaccinations,
        ];

        return response()->json($responseData);
    }
    function employerRemoveInfo(Request $request, $type){
        $jobexist = Job::find($request->job_id);
        if($type == 'certificate'){
            $certificateid = $request->certificate;
            $certificate = explode(",",$jobexist->certificate);
            $key = array_search($certificateid, $certificate);
            if ($key !== false) {
                unset($certificate[$key]);
            }
            $certificate = implode(",",$certificate);
            $job = Job::where(['id' => $request->job_id])->update(['certificate' => $certificate]);
            if($job){
                $responseData = [
                    'status' => 'success',
                    'message' => 'Deleted Successfully',
                ];
                return response()->json($responseData);
            }
        }else if($type == 'vaccinations'){
            $vaccinationsid = $request->vaccinations;
            $vaccinations = explode(",",$jobexist->vaccinations);
            $key = array_search($vaccinationsid, $vaccinations);
            if ($key !== false) {
                unset($vaccinations[$key]);
            }
            $vaccinations = implode(",",$vaccinations);
            $job = Job::where(['id' => $request->job_id])->update(['vaccinations' => $vaccinations]);
            if($job){
                $responseData = [
                    'status' => 'success',
                    'message' => 'Deleted Successfully',
                ];
                return response()->json($responseData);
            }
        }else{
            $specialtyid = $request->specialty;
            if (isset($jobexist->preferred_specialty)){
                $specialty = explode(",",$jobexist->preferred_specialty);
                $key = array_search($specialtyid, $specialty);
                if ($key !== false) {
                    unset($specialty[$key]);
                }
                $specialty = implode(",",$specialty);
                if(count(explode(",",$jobexist->preferred_specialty)) == explode(",", $jobexist->preferred_experience)){
                    if (isset($jobexist->preferred_experience)){
                        $experience = explode(",",$jobexist->preferred_experience);
                        if ($key !== false) {
                            unset($experience[$key]);
                        }
                        $experience = implode(",",$experience);
                        $job = Job::where(['id' => $request->job_id])->update(['preferred_experience' => $experience]);
                    }
                }
                $job = Job::where(['id' => $request->job_id])->update(['preferred_specialty' => $specialty]);
                if($job){
                    $responseData = [
                        'status' => 'success',
                        'message' => 'Deleted Successfully',
                    ];
                    return response()->json($responseData);
                }
            }
        }
        // $jobdetails = Job::where('id', $offerdetails['job_id'])->first();s
    }
}
