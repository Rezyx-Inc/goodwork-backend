<?php

namespace Modules\Organization\Http\Controllers;

use DB;
use DateTime;
use App\Models\Job;
use App\Models\User;
use App\Models\States;
use App\Models\Cities;
use App\Models\Offer;
use App\Models\Nurse;
use App\Models\Keyword;
use App\Models\Facility;
use App\Models\JobAsset;
use App\Models\Speciality;
use App\Models\Profession;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Http;

class OrganizationOpportunitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $organization_id = Auth::guard('organization')->user()->id;
        $scriptResponse = Http::get('http://localhost:'. config('app.file_api_port') .'/organizations/getRecruiters/' . $organization_id);
        $responseData = $scriptResponse->json();
        $allRecruiters = [];
        $ids = [];
        if(isset($responseData)) {
            $ids = array_map(function($recruiter) {
            return $recruiter['id'];
            }, $responseData);
        }
        $allRecruiters = User::whereIn('id', $ids)->where('role', 'RECRUITER')->get();

        //return response()->json(['success' => false, 'message' => $allRecruiters]);
        
        
        // jobs created by orgs or the recuiters id is in $allRecruiters array
        $draftJobs = Job::where(function($query) use ($organization_id, $ids) { $query->where('organization_id', $organization_id)->where('active', 0)->orWhereIn('recruiter_id', $ids)->where('active', 0);})->get();
        $publishedJobs = Job::where(function($query) use ($organization_id, $ids) { $query->where('organization_id', $organization_id)->where('active', 1)->orWhereIn('recruiter_id', $ids)->where('active', 1);})->get();
        $onholdJobs = Job::where(function($query) use ($organization_id, $ids) { $query->where('organization_id', $organization_id)->where('is_open', 0)->orWhereIn('recruiter_id', $ids)->where('is_open', 0);})->get();
        $specialities = Speciality::select('full_name')->get();
        $professions = Profession::select('full_name')->get();
        $applyCount = array();

        // send the states
        $states = State::select('id', 'name')->get();

        $allKeywords = [];

        $distinctFilters = Keyword::distinct()->pluck('filter');

        foreach ($distinctFilters as $filter) {
            $keywords = Keyword::where('filter', $filter)->get();
            $allKeywords[$filter] = $keywords;
        }

        foreach ($publishedJobs as $key => $value) {

            $userapplied = Offer::where('job_id', $value->id)->count();
            $applyCount[$key] = $userapplied;

        }

        $orgId = Auth::guard('organization')->user()->id;
        $requiredFields = Http::post('http://localhost:'. config('app.file_api_port') .'/organizations/get-preferences', [
            'id' => $orgId,
        ]);
        $requiredFields = $requiredFields->json();
        $requiredFieldsToSubmit = $requiredFields['requiredToSubmit'];
        
        return view('organization::organization/opportunitiesmanager', compact('draftJobs', 'specialities', 'professions', 'publishedJobs', 'onholdJobs', 'states', 'allKeywords', 'applyCount', 'requiredFieldsToSubmit'));
        //return response()->json(['success' => false, 'message' =>  $states]);
        //return view('organization::organization/opportunitiesmanager');
    }

    public function get_cities(Request $request)
    {
        $id = $request->id;

        $cities = Cities::select('id', 'name')->where('state_id', $id)->get();

        return response()->json($cities);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $distinctFilters = Keyword::distinct()->pluck('filter');
        $allKeywords = [];

        $allusstate = States::all()->where('country_code', 'US');

        foreach ($distinctFilters as $filter) {
            $keywords = Keyword::where('filter', $filter)->get();
            $allKeywords[$filter] = $keywords;
        }
        return view('organization::organization/createopportunities', compact('allKeywords', 'allusstate'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    // public function store(Request $request, $check_type = "create")
    // {
    //     $user_id = Auth::guard('organization')->user()->id;
    //     if ($check_type == "update") {
    //         $validation_array = ['job_id' => 'required'];
    //     } else if ($check_type == "published") {
    //         $validation_array = ['job_id' => 'required'];
    //     } else if ($check_type == "hidejob" || $check_type == "unhidejob") {
    //         $validation_array = ['job_id' => 'required'];
    //     } else {
    //         $validation_array = [
    //             'job_name' => 'required',
    //             'type' => 'required'
    //         ];
    //     }
    //     $validator = Validator::make($request->all(), $validation_array);

    //     if ($validator->fails()) {
    //         return response()->json(['message' => $validator->errors()]);
    //     } else {
    //         $facility_id = Facility::where('created_by', $user_id)->get()->first();
    //         if (isset($facility_id) && !empty($facility_id)) {
    //             $facility_id = $facility_id->id;
    //         } else {
    //             $facility_id =  '';
    //         }

    //         $update_array = $request->except('job_id');

    //         $jobexist = Job::where('id', $request->job_id)->first();

    //         if($jobexist){
    //             $newstring = "";
    //             if ($request->preferred_specialty) {
    //                 $update_array['preferred_specialty'] = preg_replace('/^,/', '', $jobexist->preferred_specialty . ',' . $request->preferred_specialty);
    //             }
    //             if ($request->preferred_experience) {
    //                 $update_array['preferred_experience'] = preg_replace('/^,/', '', $jobexist->preferred_experience . ',' . $request->preferred_experience);
    //             }
    //             if ($request->vaccinations) {
    //                 $update_array['vaccinations'] = preg_replace('/^,/', '', $jobexist->vaccinations . ',' . $request->vaccinations);
    //             }
    //             if ($request->certificate) {
    //                 $update_array['certificate'] = preg_replace('/^,/', '', $jobexist->certificate . ',' . $request->certificate);
    //             }
    //         }

    //         // if (isset($request->job_video) && $request->job_video != "") {
    //         //     if (preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->job_video, $vresult)) {
    //         //         $youTubeID = $this->parse_youtube($request->job_video);
    //         //         $embedURL = 'https://www.youtube.com/embed/' . $youTubeID[1];
    //         //         $update_array['video_embed_url'] = $embedURL;
    //         //     } elseif (preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->job_video, $vresult)) {
    //         //         $vimeoID = $this->parse_vimeo($request->job_video);
    //         //         $embedURL = 'https://player.vimeo.com/video/' . $vimeoID[1];
    //         //         $update_array['video_embed_url'] = $embedURL;
    //         //     }
    //         // }

    //         if ($check_type == "published") {
    //             $jobexist = Job::find($request->job_id);
    //             if (isset($jobexist->job_type))
    //                 if (isset($jobexist->job_name))
    //                     if (isset($jobexist->job_location))
    //                         if (isset($jobexist->preferred_shift))
    //                             // if (isset($jobexist->preferred_days_of_the_week))
    //                             if (isset($jobexist->facility))
    //                                 // if (isset($jobexist->weekly_pay))
    //                                 $job = Job::where(['id' => $request->job_id])->update(['active' => '1']);
    //                             // else
    //                             //     return response()->json(['message' => 'Something went wrong! Please check weekly_pay']);
    //                             else
    //                                 return response()->json(['message' => 'Something went wrong! Please check Facility']);
    //                         // else
    //                         // return response()->json(['message' => 'Something went wrong! Please check preferred_days_of_the_week']);
    //                         else
    //                             return response()->json(['message' => 'Something went wrong! Please check Shift of Day']);
    //                     else
    //                         return response()->json(['message' => 'Something went wrong! Please check Professional Licensure']);
    //                 else
    //                     return response()->json(['message' => 'Something went wrong! Please check Job Name']);
    //             else
    //                 return response()->json(['message' => 'Something went wrong! Please check Job Type']);
    //         } else if ($check_type == "update") {
    //             /* update job */
    //             if (isset($request->job_id)) {
    //                 $job_id = $request->job_id;

    //                 $job = Job::where(['id' => $job_id])->update($update_array);

    //                 $jobDetails = Job::where('id', $request->job_id)->first();

    //                 if($request->hours_per_week || $request->actual_hourly_rate || $request->weekly_taxable_amount || $request->weekly_non_taxable_amount || $request->organization_weekly_amount || $request->sign_on_bonus || $request->completion_bonus || $request->weeks_assignment || $request->goodwork_weekly_amount || $request->total_contract_amount || $request->total_organization_amount || $request->total_goodwork_amount){
    //                     $update_amount = [];
    //                     if(isset($jobDetails->hours_per_week) && isset($jobDetails->actual_hourly_rate)){
    //                         $update_amount['weekly_taxable_amount'] = $jobDetails->hours_per_week * $jobDetails->actual_hourly_rate;
    //                     }
    //                     if(isset($jobDetails->weekly_taxable_amount) && isset($request->weekly_non_taxable_amount)){
    //                         $update_amount['organization_weekly_amount'] = $jobDetails->weekly_taxable_amount + $request->weekly_non_taxable_amount;
    //                         $update_amount['goodwork_weekly_amount'] = $jobDetails->organization_weekly_amount * 0.05;
    //                     }
    //                     if((isset($request->weeks_assignment) && isset($jobDetails->organization_weekly_amount)) || isset($request->sign_on_bonus) || isset($request->completion_bonus)){
    //                         $update_amount['total_organization_amount'] = ($jobDetails->weeks_assignment * $jobDetails->organization_weekly_amount) + $jobDetails->sign_on_bonus + $jobDetails->completion_bonus;
    //                     }
    //                     if(isset($request->weeks_assignment) && isset($jobDetails->goodwork_weekly_amount)){
    //                         $update_amount['total_goodwork_amount'] = $jobDetails->weeks_assignment * $jobDetails->goodwork_weekly_amount;
    //                     }
    //                     if(isset($jobDetails->total_organization_amount) && isset($jobDetails->total_goodwork_amount)){
    //                         $update_amount['total_contract_amount'] = $jobDetails->total_organization_amount + $jobDetails->total_goodwork_amount;
    //                     }
    //                     $job = Job::where(['id' => $job_id])->update($update_amount);
    //                 }
    //             } else {
    //                 // return back()->with('error', 'Something went wrong! Please check job_id');
    //                 return response()->json(['message' => 'Something went wrong! Please check job_id']);
    //             }
    //             /* update job */
    //         } else if ($check_type == "hidejob" || $check_type == "unhidejob") {
    //             /* update job */
    //             // is_hidden
    //             if (isset($request->job_id)) {
    //                 $job_id = $request->job_id;
    //                 $job = Job::where(['id' => $job_id])->update(['is_hidden'=> $check_type == "hidejob" ? '1': '0']);
    //             } else {
    //                 // return back()->with('error', 'Something went wrong! Please check job_id');
    //                 return response()->json(['message' => 'Something went wrong! Please check job_id']);
    //             }
    //             /* update job */
    //         } else {
    //             /* create job */
    //             $update_array["created_by"] = (isset($user_id) && $user_id != "") ? $user_id : "";
    //             $update_array["organization_id"] = (isset($user_id) && $user_id != "") ? $user_id : "";
    //             $update_array["goodwork_number"] = uniqid();
    //             $update_array["active"] = "0";
    //             $job = Job::create($update_array);
    //             Job::where('id', $job['id'])->update(['goodwork_number' => $job['id']]);
    //             $job = Job::where('id', $job['id'])->first();
    //             /* create job */
    //         }

    //         if (!empty($job) && $job_photos = $request->file('job_photos')) {
    //             foreach ($job_photos as $job_photo) {
    //                 $job_photo_name_full = $job_photo->getClientOriginalName();
    //                 $job_photo_name = pathinfo($job_photo_name_full, PATHINFO_FILENAME);
    //                 $job_photo_ext = $job_photo->getClientOriginalExtension();
    //                 $job_photo_finalname = $job_photo_name . '_' . time() . '.' . $job_photo_ext;
    //                 //Upload Image
    //                 $job_id_val = ($check_type == "update") ? $job_id : $job->id;
    //                 $job_photo->storeAs('assets/jobs/' . $job_id_val, $job_photo_finalname);
    //                 JobAsset::create(['job_id' => $job_id_val, 'name' => $job_photo_finalname, 'filter' => 'job_photos']);
    //             }
    //         }
    //         if ($job) {
    //             if($check_type == 'hidejob'){
    //                 $check_type = 'hide';
    //             }else if($check_type == "unhidejob"){
    //                 $check_type = 'unhide';
    //             }
    //             return response()->json(['message' => "Job {$check_type} successfully", 'job_id' => $job['id'], 'goodwork_number' => $job['goodwork_number']]);
    //             // return back()->with('success', "Job " . $check_type . "d successfully");
    //         } else {
    //             return response()->json(['message' => 'Failed to create job, Please try again later']);
    //             // return back()->with('error', "Failed to create job, Please try again later");
    //         }
    //     }
    // }

    // edited store without the missing fields

    public function store(Request $request, $check_type = 'create')
    {
        $user_id = Auth::guard('organization')->user()->id;

        if ($check_type == 'update') {
            $validation_array = ['job_id' => 'required'];
        } elseif ($check_type == 'published') {
            $validation_array = ['job_id' => 'required'];
        } elseif ($check_type == 'hidejob' || $check_type == 'unhidejob') {
            $validation_array = ['job_id' => 'required'];
        } else {
            $validation_array = [
                'job_name' => 'required',
                'job_type' => 'required',
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
                $facility_id = '';
            }

            $update_array = $request->except('job_id');

            $jobexist = Job::where('id', $request->job_id)->first();

            if ($jobexist) {
                $newstring = '';
                if ($request->preferred_specialty) {
                    $update_array['preferred_specialty'] = preg_replace('/^,/', '', $jobexist->preferred_specialty . ',' . $request->preferred_specialty);
                }
                if ($request->preferred_experience) {
                    $update_array['preferred_experience'] = preg_replace('/^,/', '', $jobexist->preferred_experience . ',' . $request->preferred_experience);
                }
                // there is no vaccination or certificate fields in the job table

                // if ($request->vaccinations) {
                //     $update_array['vaccinations'] = preg_replace('/^,/', '', $jobexist->vaccinations . ',' . $request->vaccinations);
                // }
                // if ($request->certificate) {
                //     $update_array['certificate'] = preg_replace('/^,/', '', $jobexist->certificate . ',' . $request->certificate);
                // }
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

            if ($check_type == 'published') {
                $jobexist = Job::find($request->job_id);
                if (isset($jobexist->job_type)) {
                    if (isset($jobexist->job_name)) {
                        if (isset($jobexist->job_location)) {
                            if (isset($jobexist->preferred_shift)) {
                                if (isset($jobexist->facility_id)) {
                                    // if (isset($jobexist->preferred_days_of_the_week))

                                    // we have a facility_id field in the job table not the facilty field
                                    // if (isset($jobexist->facility))
                                    // if (isset($jobexist->weekly_pay))
                                    $job = Job::where(['id' => $request->job_id])->update(['active' => '1']);
                                }
                                // else
                                //     return response()->json(['message' => 'Something went wrong! Please check weekly_pay']);
                                else {
                                    return response()->json(['message' => 'Something went wrong! Please check Facility']);
                                }
                            }
                            // else
                            // return response()->json(['message' => 'Something went wrong! Please check preferred_days_of_the_week']);
                            else {
                                return response()->json(['message' => 'Something went wrong! Please check Shift of Day']);
                            }
                        } else {
                            return response()->json(['message' => 'Something went wrong! Please check Professional Licensure']);
                        }
                    } else {
                        return response()->json(['message' => 'Something went wrong! Please check Job Name']);
                    }
                } else {
                    return response()->json(['message' => 'Something went wrong! Please check Job Type']);
                }
            } elseif ($check_type == 'update') {
                /* update job */
                if (isset($request->job_id)) {
                    $job_id = $request->job_id;

                    $job = Job::where(['id' => $job_id])->update($update_array);

                    $jobDetails = Job::where('id', $request->job_id)->first();

                    // there is no actual_hourly_rate field in the job table

                    if ($request->hours_per_week || $request->actual_hourly_rate || $request->weekly_taxable_amount || $request->weekly_non_taxable_amount || $request->organization_weekly_amount || $request->sign_on_bonus || $request->completion_bonus || $request->weeks_assignment || $request->goodwork_weekly_amount || $request->total_contract_amount || $request->total_organization_amount || $request->total_goodwork_amount) {
                        $update_amount = [];
                        // if(isset($jobDetails->hours_per_week) && isset($jobDetails->actual_hourly_rate)){
                        //     $update_amount['weekly_taxable_amount'] = $jobDetails->hours_per_week * $jobDetails->actual_hourly_rate;
                        // }

                        // there is no weekly_taxable_amount or weekly_taxable_amount fields in the job table
                        // if(isset($jobDetails->weekly_taxable_amount) && isset($request->weekly_non_taxable_amount)){
                        //     $update_amount['organization_weekly_amount'] = $jobDetails->weekly_taxable_amount + $request->weekly_non_taxable_amount;
                        //     $update_amount['goodwork_weekly_amount'] = $jobDetails->organization_weekly_amount * 0.05;
                        // }
                        // if((isset($request->weeks_assignment) && isset($jobDetails->organization_weekly_amount)) || isset($request->sign_on_bonus) || isset($request->completion_bonus)){
                        //     $update_amount['total_organization_amount'] = ($jobDetails->weeks_assignment * $jobDetails->organization_weekly_amount) + $jobDetails->sign_on_bonus + $jobDetails->completion_bonus;
                        // }
                        // if(isset($request->weeks_assignment) && isset($jobDetails->goodwork_weekly_amount)){
                        //     $update_amount['total_goodwork_amount'] = $jobDetails->weeks_assignment * $jobDetails->goodwork_weekly_amount;
                        // }
                        // if(isset($jobDetails->total_organization_amount) && isset($jobDetails->total_goodwork_amount)){
                        //     $update_amount['total_contract_amount'] = $jobDetails->total_organization_amount + $jobDetails->total_goodwork_amount;
                        // }
                        $job = Job::where(['id' => $job_id])->update($update_amount);
                    }
                } else {
                    // return back()->with('error', 'Something went wrong! Please check job_id');
                    return response()->json(['message' => 'Something went wrong! Please check job_id']);
                }
                /* update job */
            } elseif ($check_type == 'hidejob' || $check_type == 'unhidejob') {
                /* update job */
                // is_hidden
                if (isset($request->job_id)) {
                    $job_id = $request->job_id;
                    $job = Job::where(['id' => $job_id])->update(['is_hidden' => $check_type == 'hidejob' ? '1' : '0']);
                } else {
                    // return back()->with('error', 'Something went wrong! Please check job_id');
                    return response()->json(['message' => 'Something went wrong! Please check job_id']);
                }
                /* update job */
            } else {
                /* create job */
                $update_array['created_by'] = isset($user_id) && $user_id != '' ? $user_id : '';
                $update_array['organization_id'] = isset($user_id) && $user_id != '' ? $user_id : '';
                // $update_array["goodwork_number"] = uniqid();
                $update_array['active'] = '0';
                $job = Job::create($update_array);
                //Job::where('id', $job['id'])->update(['goodwork_number' => $job['id']]);
                $job = Job::where('id', $job['id'])->first();
                /* create job */
            }

            if (!empty($job) && ($job_photos = $request->file('job_photos'))) {
                foreach ($job_photos as $job_photo) {
                    $job_photo_name_full = $job_photo->getClientOriginalName();
                    $job_photo_name = pathinfo($job_photo_name_full, PATHINFO_FILENAME);
                    $job_photo_ext = $job_photo->getClientOriginalExtension();
                    $job_photo_finalname = $job_photo_name . '_' . time() . '.' . $job_photo_ext;
                    //Upload Image
                    $job_id_val = $check_type == 'update' ? $job_id : $job->id;
                    $job_photo->storeAs('assets/jobs/' . $job_id_val, $job_photo_finalname);
                    JobAsset::create(['job_id' => $job_id_val, 'name' => $job_photo_finalname, 'filter' => 'job_photos']);
                }
            }
            if ($job) {
                if ($check_type == 'hidejob') {
                    $check_type = 'hide';
                } elseif ($check_type == 'unhidejob') {
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

    public function hide_job(Request $request)
    {
        // return $request->all();
        $param = $request->route('check_type');



        try {
            $validated = $request->validate([
                'job_id' => 'required',
            ]);

            $job_id = $request->job_id;
            if ($param == 'hidejob') {
                $job = Job::where(['id' => $job_id])->update(['is_open' => '0']);
                return response()->json(['message' => 'Job is onhold', 'job_id' => $job_id]);
            } else {
                $job = Job::where(['id' => $job_id])->update(['is_open' => '1']);
                return response()->json(['message' => 'Job is published', 'job_id' => $job_id]);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
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
    public function getJobListing(Request $request)
    {
        // return response()->json(['message'=>$request->all()]);

        $type = $request->type;
        $allspecialty = [];
        $allvaccinations = [];
        $allcertificate = [];
        $organization_id = Auth::guard('organization')->user()->id;

        // $formtype = $request->formtype;
        // $activestatus = "1";
        // if($type == 'draft'){
        //     $activestatus = "0";
        // }

        if ($type == 'drafts') {
            $jobLists = Job::where(['active' => '0', 'organization_id' => $organization_id])->get();
            if (0 >= count($jobLists)) {
                $responseData = [
                    'joblisting' => '<div class="text-center"><span>No Job</span></div>',
                    'jobdetails' => '<div class="text-center"><span>Data Not found</span></div>',
                ];
                return response()->json($responseData);
            }
        } elseif ($type == 'hidden') {
            $jobLists = Job::where(['is_hidden' => '1', 'organization_id' => $organization_id])->get();
            if (0 >= count($jobLists)) {
                $responseData = [
                    'joblisting' => '<div class="text-center"><span>No Job</span></div>',
                    'jobdetails' => '<div class="text-center"><span>Data Not found</span></div>',
                ];
                return response()->json($responseData);
            }
        } elseif ($type == 'closed') {
            $jobLists = Job::where(['is_closed' => '1', 'organization_id' => $organization_id])->get();
            if (0 >= count($jobLists)) {
                $responseData = [
                    'joblisting' => '<div class="text-center"><span>No Job</span></div>',
                    'jobdetails' => '<div class="text-center"><span>Data Not found</span></div>',
                ];
                return response()->json($responseData);
            }
        } elseif ($type == 'onhold') {
            $jobLists = Job::where(['active' => '1', 'is_open' => '0', 'organization_id' => $organization_id])->get();
            if (0 >= count($jobLists)) {
                $responseData = [
                    'joblisting' => '<div class="text-center"><span>No Job</span></div>',
                    'jobdetails' => '<div class="text-center"><span>Data Not found</span></div>',
                ];
                return response()->json($responseData);
            }
        } else {

            $jobLists = Job::where(['active' => '1', 'is_hidden' => '0', 'is_closed' => '0', 'organization_id' => $organization_id, 'is_open' => '1'])->get();
            //return $jobLists;
            if (0 >= count($jobLists)) {
                $responseData = [
                    'joblisting' => '<div class="text-center"><span>No Job</span></div>',
                    'jobdetails' => '<div class="text-center"><span>Data Not found</span></div>',
                ];
                return response()->json($responseData);
            }
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
        $data = '';
        $data2 = '';
        foreach ($jobLists as $key => $value) {
            if ($value) {
                // $userapplied = DB::table('offer_jobs')->where(['job_id' => $value->id])->count();
                $userapplied = Offer::where('job_id', $value->id)->count();

                $data .=
                    '
                <div class="ss-chng-appli-slider-sml-dv ' .
                    ($request->id == $value['id'] ? 'active' : '') .
                    '" id="opportunity-list" onclick="opportunitiesType(\'' .
                    $type .
                    '\', \'' .
                    $value['id'] .
                    '\', \'jobdetails\')">
                    <ul class="ss-cng-appli-slid-ul1">
                        <li class="d-flex">
                            <p>' .
                    $value['job_type'] .
                    '</p>
                            <span>' .
                    $userapplied .
                    ' Workers Applied</span>
                        </li>
                    </ul>
                    <h4 class="job-title">' .
                    $value['job_name'] .
                    '</h4>
                    <ul class="ss-cng-appli-slid-ul2 d-block">
                        <li class="d-inline-block">' .
                    ($value['job_location'] ? $value['job_location'] . ', ' : '') .
                    ' ' .
                    $value['job_state'] .
                    '</li>
                        <li class="d-inline-block">' .
                    $value['preferred_shift'] .
                    '</li>
                        <li class="d-inline-block">' .
                    $value['preferred_days_of_the_week'] .
                    ' wks</li>
                    </ul>
                    <ul class="ss-cng-appli-slid-ul3">
                        <li><span>' .
                    $value['facility'] .
                    '</span></li>
                        <li><h6>$' .
                    $value['hours_per_week'] .
                    '/wk</h6></li>
                    </ul>
                </div>';
            }
        }
        if ($data == '') {
            $data = '<div class="text-center"><span>No Opportunities</span></div>';
        }

        // $offerdetails = "";

        if (isset($request->id)) {
            $jobdetails = Job::select('jobs.*')
                ->where(['jobs.id' => $request->id])
                ->where('organization_id', $organization_id)
                ->first();
        } else {
            if ($type == 'drafts') {
                $jobdetails = Job::select('jobs.*')
                    ->where(['jobs.active' => '0'])
                    ->where('organization_id', $organization_id)
                    ->first();
            } elseif ($type == 'hidden') {
                $jobdetails = Job::select('jobs.*')
                    ->where(['jobs.is_hidden' => '1'])
                    ->where('organization_id', $organization_id)
                    ->first();
            } elseif ($type == 'closed') {
                $jobdetails = Job::select('jobs.*')
                    ->where(['jobs.is_closed' => '1'])
                    ->where('organization_id', $organization_id)
                    ->first();
            } elseif ($type == 'onhold') {
                $jobdetails = Job::select('jobs.*')
                    ->where(['jobs.is_open' => '0'])
                    ->where(['jobs.active' => '1'])
                    ->where('organization_id', $organization_id)
                    ->first();
            } else {

                $jobdetails = Job::select('jobs.*')
                    ->where(['jobs.active' => '1', 'jobs.is_hidden' => '0', 'jobs.is_closed' => '0', 'jobs.is_open' => '1'])
                    ->where('organization_id', $organization_id)
                    ->first();
            }
        }
        //return response()->json(['joblisting' => $data, 'organization_id' => $jobdetails]);
        $userdetails = $jobdetails ? User::where('id', $jobdetails['organization_id'])->first() : '';
        $jobappliedcount = $jobdetails ? Offer::where('job_id', $jobdetails->id)->count() : '';
        $jobapplieddetails = $jobdetails ? Offer::where('job_id', $jobdetails->id)->get() : '';

         // $jobapplieddetails = $nursedetails ? Offer::where(['status' => $activestatus, 'nurse_id' => $offerdetails->nurse_id])->get() : "";
        // $jobappliedcount = $nursedetails ? Offer::where(['status' => $activestatus, 'nurse_id' => $offerdetails->nurse_id])->count() : "";

        if ($request->formtype == 'useralldetails') {
            $offerdetails = Offer::where('id', $request->id)
                ->where('organization_id', $organization_id)
                ->first();

            $nursedetails = Nurse::where('id', $offerdetails['worker_user_id'])->first();
            $userdetails = User::where('id', $nursedetails->user_id)->first();
            $jobdetails = Job::where('id', $offerdetails['job_id'])->first();

            $data2 .=
                '
                <ul class="ss-cng-appli-hedpfl-ul">
                    <li>
                        <span>' .
                $offerdetails['worker_user_id'] .
                '</span>

                        <h6>
                            <img width="50px" height="50px" src="' .
                asset('images/nurses/profile/' . $userdetails['image']) .
                '" onerror="this.onerror=null;this.src=' .
                '\'' .
                asset('frontend/img/profile-pic-big.png') .
                '\'' .
                ';" id="preview" width="50px" height="50px" style="object-fit: cover;" class="rounded-3" alt="Profile Picture">
                            ' .
                $userdetails['first_name'] .
                ' ' .
                $userdetails['last_name'] .
                '
                        </h6>
                    </li>
                    <li>';

            if ($request->type == 'Apply' || $request->type == 'Screening' || $request->type == 'Submitted') {
                $data2 .=
                    '
                                <button class="rounded-pill ss-apply-btn py-2 border-0 px-4" onclick="applicationType(\'' .
                    $type .
                    '\', \'' .
                    $value->id .
                    '\', \'jobdetails\', \'' .
                    $request->jobid .
                    '\')">Send Offer</button>
                            ';
            }
            $data2 .= '
                    </li>
                </ul>';
            $data2 .=
                '
                <div class="ss-chng-apcon-st-ssele">

                    <label class="mb-2">Change Application Status</label>
                    <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-9 ss-change-appli-mn-div" style="border:none;">
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
                    <li class="col-md-5">
                        <h5 class="mt-3">Job Information</h5>
                    </li>
                    <li class="col-md-5">
                        <h5 class="mt-3">Worker Information</h5>
                    </li>
                   ';
            $data2 .=
                ' <div style="display:flex;"  class="col-md-12">
            <span class="mt-3">Profession</span>
         </div>
            <div class="row ' .
                ($jobdetails->profession == $nursedetails->profession ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                <div class="col-md-5">
                    <h6>' .
                ($jobdetails->profession ?? '----') .
                '</h6>
                </div>
                <div class="col-md-5 ' .
                ($jobdetails->profession ? '' : 'd-none') .
                '">
                    <p>' .
                ($nursedetails->profession ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'nursing_profession\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                </div>
                </div>';

            $data2 .=
                ' <div style="display:flex;"  class="col-md-12">
            <span class="mt-3">Specialty</span>
         </div>
            <div class="row ' .
                ($jobdetails->specialty == $nursedetails->specialty ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                <div class="col-md-5">
                    <h6>' .
                ($jobdetails->specialty ?? '----') .
                '</h6>
                </div>
                <div class="col-md-5 ' .
                ($jobdetails->specialty ? '' : 'd-none') .
                '">
                    <p>' .
                ($nursedetails->specialty ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'nursing_specialty\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                </div>
                </div>';
            $data2 .=
                '
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Block scheduling</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->block_scheduling === $nursedetails->block_scheduling ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->block_scheduling == '1' ? 'Yes' : ($jobdetails->block_scheduling == '0' ? 'No' : '----')) .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                (isset($jobdetails->block_scheduling) ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->block_scheduling == '1' ? 'Yes' : ($nursedetails->block_scheduling == '0' ? 'No' : '<a style="cursor: pointer;" onclick="askWorker(this, \'block_scheduling\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>')) .
                '</p>
                </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Float requirements</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->float_requirement === $nursedetails->float_requirement ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->float_requirement == '1' ? 'Yes' : ($jobdetails->float_requirement == '0' ? 'No' : '----')) .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                (isset($jobdetails->float_requirement) ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->float_requirement == '1' ? 'Yes' : ($nursedetails->float_requirement == '0' ? 'No' : '<a style="cursor: pointer;" onclick="askWorker(this, \'float_requirement\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>')) .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Facility Shift Cancellation Policy</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->facility_shift_cancelation_policy === $nursedetails->facility_shift_cancelation_policy ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->facility_shift_cancelation_policy ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->facility_shift_cancelation_policy ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->facility_shift_cancelation_policy ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'facility_shift_cancelation_policy\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Contract Termination Policy</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->contract_termination_policy === $nursedetails->contract_termination_policy ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->contract_termination_policy ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->contract_termination_policy ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->contract_termination_policy ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'contract_termination_policy\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Traveler Distance From Facility</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->traveler_distance_from_facility === $nursedetails->distance_from_your_home ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->traveler_distance_from_facility ?? '----') .
                ' miles Maximum</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->traveler_distance_from_facility ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->distance_from_your_home ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'distance_from_your_home\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Facility</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->facility === $nursedetails->worked_at_facility_before ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->facility ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->facility ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worked_at_facility_before ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worked_at_facility_before\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Clinical Setting</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->clinical_setting === $nursedetails->clinical_setting_you_prefer ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->clinical_setting ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->clinical_setting ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->clinical_setting_you_prefer ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'clinical_setting_you_prefer\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Patient ratio</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->patient_ratio === $nursedetails->worker_patient_ratio ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->Patient_ratio ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->Patient_ratio ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_patient_ratio ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_patient_ratio\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                         <div style="display:flex;"  class="col-md-12">
                        <span class="mt-3">Professional Licensure</span>
                    </div>
                    <div class="row ' .
                ($jobdetails->job_location === $nursedetails->nursing_license_state ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                    <div class="col-md-5">
                        <h6>' . ($jobdetails['job_location'] ?? '----') . '</h6>
                    </div>

                    <div class="col-md-5' . ($jobdetails['job_location'] ? '' : 'd-none') . '">
                        <p>' . ($nursedetails['nursing_license_state'] ?? '<u  style="cursor: pointer;" onclick="askWorker(this, \'nursing_license_state\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') . '</p>
                    </div>
                    </div>

                        <div style="display:flex;"  class="col-md-12">
                        <span class="mt-3">Vaccinations & Immunizations</span>
                    </div>';
            if (isset($jobdetails['vaccinations'])) {
                foreach (explode(",", $jobdetails['vaccinations']) as $key => $value) {
                    if (isset($value)) {
                        $data2 .= '
                                <div class="col-md-5 ">
                                    <h6>' . $value . ' Required</h6>
                                </div>
                                <div class="col-md-5 ">
                                    <p><u style="cursor: pointer;" onclick="askWorker(this, \'vaccinations\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u></p>
                                </div>
                                ';
                    }
                }
            }
            $data2 .= '<div style="display:flex;"  class="col-md-12">
                        <span class="mt-3">References</span>
                    </div>';
            if (isset($jobdetails['number_of_references'])) {
                foreach (explode(",", $jobdetails['number_of_references']) as $key => $value) {
                    if (isset($value)) {
                        $data2 .= '
                                <div class="col-md-5 ">
                                    <h6>' . $value . ' references</h6>
                                    <h6>' . $jobdetails['recency_of_reference'] . ' months of recency</h6>
                                    <h6>' . $jobdetails['min_title_of_reference'] . '</h6>
                                </div>
                                <div class="col-md-5 ">
                                    <p><u style="cursor: pointer;" onclick="askWorker(this, \'number_of_references\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u></p>
                                </div>
                                ';
                    }
                }
            }
            $data2 .= '
                    <div style="display:flex;"  class="col-md-12">
                        <span class="mt-3">Certifications</span>
                    </div>';
            if (isset($jobdetails['certificate'])) {
                foreach (explode(",", $jobdetails['certificate']) as $key => $value) {
                    if (isset($value)) {
                        $data2 .= '
                                <div class="col-md-5 ">
                                    <h6>' . $value . ' Required</h6>
                                </div>
                                <div class="col-md-5 ">
                                    <p><u style="cursor: pointer;" onclick="askWorker(this, \'certificate\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u></p>
                                </div>
                                ';
                    }
                }
            }
            $data2 .= '
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Skills checklist</span>
                        </div>
                        <div class="row ' .
                ($jobdetails['skills'] === $nursedetails['skills'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' . ($jobdetails['skills'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-5 ' . ($jobdetails['skills'] ? '' : 'd-none') . '">
                            <p>' . ($nursedetails['skills'] ?? '<u onclick="askWorker(this, \'skills\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') . '</p>
                        </div>
                        </div>



                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Urgency</span>
                        </div>
                        <div class="row ' .
                ($jobdetails['urgency'] === $nursedetails['worker_urgency'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">

                        <div class="col-md-5">
                            <h6>' . ($jobdetails['urgency'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-5 ' . ($jobdetails['urgency'] ? '' : 'd-none') . '">
                            <p>' . ($nursedetails['worker_urgency'] ?? '<u onclick="askWorker(this, \'urgency\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') . '</p>
                        </div>
                        </div>



                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Eligible to work in the US</span>
                        </div>
                        <div class="row ' .
                ($jobdetails['eligible_work_in_us'] === $nursedetails['eligible_work_in_us'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' . ($jobdetails['eligible_work_in_us'] == '1' ? 'Yes' : ($jobdetails['eligible_work_in_us'] == '0' ? 'No' : '----')) . '</h6>
                        </div>
                        <div class="col-md-5 ' . (isset($jobdetails['eligible_work_in_us']) ? '' : 'd-none') . '">
                            <p>' . ($nursedetails['eligible_work_in_us'] == '1' ? 'Yes' : ($nursedetails['eligible_work_in_us'] == '0' ? 'No' : '<u onclick="askWorker(this, \'eligible_work_in_us\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>')) . '</p>
                        </div>
                        </div>

                         <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Facility`s Parent System</span>
                        </div>

                        <div class="row ' .
                ($jobdetails['facilitys_parent_system'] === $nursedetails['worker_facility_parent_system'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' . ($jobdetails['facilitys_parent_system'] ?? '----') . '</h6>
                        </div>
                        <div class="col-md-5 ' . ($jobdetails['facilitys_parent_system'] ? '' : 'd-none') . '">
                            <p>' . ($nursedetails['worker_facility_parent_system'] ?? '<u onclick="askWorker(this, \'worker_facility_parent_system\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') . '</p>
                        </div>
                      </div>

                        <div style="display:flex;"  class="col-md-12">
                                <span class="mt-3">Facility State</span>
                            </div>
                            <div class="row ' .
                ($jobdetails['facility_state'] === $nursedetails['state'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                                <div class="col-md-5">
                                    <h6>' . ($jobdetails['facility_state'] ?? '----') . '</h6>
                                </div>
                                <div class="col-md-5 ' . ($jobdetails['facility_state'] ? '' : 'd-none') . '">
                                    <p>' . ($nursedetails['state'] ?? '<u onclick="askWorker(this, \'facility_state\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') . '</p>
                                </div>
                            </div>
                            <div style="display:flex;"  class="col-md-12">
                                <span class="mt-3">Facility City</span>
                            </div>
                            <div class="row ' .
                ($jobdetails['facility_city'] === $nursedetails['city'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                                <div class="col-md-5">
                                    <h6>' . ($jobdetails['facility_city'] ?? '----') . '</h6>
                                </div>
                                <div class="col-md-5 ' . ($jobdetails['facility_city'] ? '' : 'd-none') . '">
                                    <p>' . ($nursedetails['city'] ?? '<u onclick="askWorker(this, \'facility_city\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</u>') . '</p>
                                </div>
                            </div>



                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">EMR</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->emr === $nursedetails->worker_emr ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->Emr ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->Emr ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_emr ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_emr\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Unit</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->unit === $nursedetails->worker_unit ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->Unit ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->Unit ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_unit ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_unit\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Scrub Color</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->scrub_color === $nursedetails->worker_scrub_color ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->scrub_color ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->scrub_color ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_scrub_color ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_scrub_color\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>

                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Start Date</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->start_date == $nursedetails->worker_start_date ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->start_date ? $jobdetails->start_date : 'As Soon As Possible') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->start_date ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_start_date ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_start_date\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">RTO</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->rto === $nursedetails->rto ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->rto ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->rto ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->rto ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'rto\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Shift Time of Day</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->preferred_shift == $nursedetails->worker_shift_time_of_day ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->preferred_shift ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->preferred_shift ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_shift_time_of_day ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_shift_time_of_day\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Hours/Week</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->hours_per_week == $nursedetails->worker_hours_per_week ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->hours_per_week ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->hours_per_week ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_hours_per_week ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_hours_per_week\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Guaranteed Hours</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->guaranteed_hours == $nursedetails->worker_guaranteed_hours ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->guaranteed_hours ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->guaranteed_hours ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_guaranteed_hours ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_guaranteed_hours\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Hours/Shift</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->preferred_assignment_duration == $nursedetails->worker_weeks_assignment ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->preferred_assignment_duration ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->preferred_assignment_duration ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_weeks_assignment ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_weeks_assignment\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Weeks/Assignment</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->weeks_shift == $nursedetails->worker_shifts_week ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->preferred_assignment_duration ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->preferred_assignment_duration ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_weeks_assignment ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_weeks_assignment\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Shifts/Week</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->weeks_shift == $nursedetails->worker_shifts_week ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->weeks_shift ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->weeks_shift ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_shifts_week ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_shifts_week\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Referral Bonus</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->referral_bonus === $nursedetails->worker_referral_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>$' .
                ($jobdetails->referral_bonus ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->referral_bonus ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_referral_bonus ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_referral_bonus\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Sign-On Bonus</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->sign_on_bonus === $nursedetails->worker_sign_on_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>$' .
                ($jobdetails->sign_on_bonus ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->sign_on_bonus ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_sign_on_bonus ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_sign_on_bonus\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Completion Bonus</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->completion_bonus === $nursedetails->worker_completion_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>$' .
                ($jobdetails->completion_bonus ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->completion_bonus ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_completion_bonus ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_completion_bonus\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Extension Bonus</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->extension_bonus === $nursedetails->worker_extension_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>$' .
                ($jobdetails->extension_bonus ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->extension_bonus ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_extension_bonus ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_extension_bonus\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Other Bonus</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->other_bonus === $nursedetails->worker_other_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>$' .
                ($jobdetails->other_bonus ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->other_bonus ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_other_bonus ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_other_bonus\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">401K</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->four_zero_one_k === $nursedetails->how_much_k ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->four_zero_one_k == '1' ? 'Yes' : ($jobdetails->four_zero_one_k == '0' ? 'No' : '----')) .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                (isset($jobdetails->four_zero_one_k) ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->how_much_k ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'how_much_k\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Health Insurance</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->health_insaurance === $nursedetails->worker_health_insurance ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->health_insaurance == '1' ? 'Yes' : ($jobdetails->health_insaurance == '0' ? 'No' : '----')) .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                (isset($jobdetails->health_insaurance) ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_health_insurance == '1' ? 'Yes' : ($nursedetails->worker_health_insurance == '0' ? 'No' : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_health_insurance\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>')) .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Dental</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->dental === $nursedetails->worker_dental ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->dental == '1' ? 'Yes' : ($jobdetails->dental == '0' ? 'No' : '----')) .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                (isset($jobdetails->dental) ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_dental == '1' ? 'Yes' : ($nursedetails->worker_dental == '0' ? 'No' : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_dental\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>')) .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Vision</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->vision === $nursedetails->worker_vision ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->vision == '1' ? 'Yes' : ($jobdetails->vision == '0' ? 'No' : '----')) .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                (isset($jobdetails->vision) ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_vision == '1' ? 'Yes' : ($nursedetails->worker_vision == '0' ? 'No' : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_vision\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>')) .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Actual Hourly rate</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->actual_hourly_rate === $nursedetails->worker_actual_hourly_rate ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->actual_hourly_rate ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->actual_hourly_rate ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_actual_hourly_rate ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_actual_hourly_rate\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Overtime</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->overtime === $nursedetails->worker_overtime ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->overtime ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->overtime ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_overtime ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_overtime\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Holiday</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->holiday === $nursedetails->worker_holiday ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->holiday ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->holiday ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_holiday ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_holiday\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">On Call</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->on_call === $nursedetails->worker_on_call ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->on_call ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->on_call ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_on_call ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_on_call\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Call Back</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->call_back === $nursedetails->worker_call_back ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->call_back ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->call_back ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_call_back ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_call_back\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Orientation Rate</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->orientation_rate === $nursedetails->worker_orientation_rate ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->orientation_rate ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->orientation_rate ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_orientation_rate ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_orientation_rate\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Est. Weekly Taxable amount</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->weekly_taxable_amount === $nursedetails->worker_weekly_taxable_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->weekly_taxable_amount ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->weekly_taxable_amount ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_weekly_taxable_amount ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_weekly_taxable_amount\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Est. Organization Weekly Amount</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->organization_weekly_amount === $nursedetails->worker_organization_weekly_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->organization_weekly_amount ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->organization_weekly_amount ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_organization_weekly_amount ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_organization_weekly_amount\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Est. Weekly non-taxable amount</span>
                        </div>
                        <div class="row ' .
                ($jobdetails->weekly_non_taxable_amount === $nursedetails->worker_weekly_non_taxable_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') .
                ' d-flex align-items-center" style="margin:auto;">
                        <div class="col-md-5">
                            <h6>' .
                ($jobdetails->weekly_non_taxable_amount ?? '----') .
                '</h6>
                        </div>
                        <div class="col-md-5 ' .
                ($jobdetails->weekly_non_taxable_amount ? '' : 'd-none') .
                '">
                            <p>' .
                ($nursedetails->worker_weekly_non_taxable_amount ?? '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_weekly_non_taxable_amount\', \'' . $nursedetails['id'] . '\', \'' . $jobdetails['id'] . '\')">Ask Worker</a>') .
                '</p>
                        </div>
                        </div>
                        <div style="display:flex;"  class="col-md-12">
                            <span class="mt-3">Est. Goodwork Weekly Amount</span>
                        </div>
                        <div class="col-md-12">
                            <h6>' .
                ($jobdetails->weekly_taxable_amount ?? '----') .
                '</h6>
                        </div>
                        <div style="display:flex;" class="col-md-12">
                            <span class="mt-3">Est. Total Organization Amount</span>
                        </div>
                        <div class="col-md-12">
                            <h6>' .
                ($jobdetails->total_organization_amount ?? '----') .
                '</h6>
                        </div>
                        <div style="display:flex;" class="col-md-12">
                            <span class="mt-3">Est. Total Goodwork Amount</span>
                        </div>
                        <div class="col-md-12">
                            <h6>' .
                ($jobdetails->total_goodwork_amount ?? '----') .
                '</h6>
                        </div>
                        <div style="display:flex;" class="col-md-12">
                            <span class="mt-3">Est. Total Contract Amount</span>
                        </div>
                        <div class="col-md-12">
                            <h6>' .
                ($jobdetails->total_contract_amount ?? '----') .
                '</h6>
                        </div>
                    </ul>
                </div>

                            <button class="ss-counter-button w-100" onclick="offerSend(\'' .
                $request->id .
                '\', \'' .
                $jobdetails['id'] .
                '\', \'offersend\', \'' .
                $nursedetails->user_id .
                '\', \'' .
                $jobdetails['organization_id'] .
                '\')">Send Offer</button>
                        </div>
                    </ul>
                </div>
                ';
        } elseif ($request->type == 'drafts') {
        } else{
            $orgId = Auth::guard('organization')->user()->id;
            $scriptResponse = Http::get('http://localhost:'. config('app.file_api_port') .'/organizations/getRecruiters/' . $orgId);
            $responseData = $scriptResponse->json();
            $allRecruiters = [];
            $ids = [];
            if(isset($responseData)) {
                $ids = array_map(function($recruiter) {
                return $recruiter['id'];
                }, $responseData);
            }
            $allRecruiters = User::whereIn('id', $ids)->where('role', 'RECRUITER')->get();

            $data2 = view('organization::organization/organizationInformation', ['userdetails' => $userdetails, 'jobdetails' => $jobdetails, 'jobapplieddetails' => $jobapplieddetails, 'jobappliedcount' => $jobappliedcount, 'type' => $type , 'allRecruiters' => $allRecruiters])->render();

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
    function organizationRemoveInfo(Request $request, $type)
    {
        $jobexist = Job::find($request->job_id);
        if ($type == 'certificate') {
            $certificateid = $request->certificate;
            $certificate = explode(',', $jobexist->certificate);
            $key = array_search($certificateid, $certificate);
            if ($key !== false) {
                unset($certificate[$key]);
            }
            $certificate = implode(',', $certificate);
            $job = Job::where(['id' => $request->job_id])->update(['certificate' => $certificate]);
            if ($job) {
                $responseData = [
                    'status' => 'success',
                    'message' => 'Deleted Successfully',
                ];
                return response()->json($responseData);
            }
        } elseif ($type == 'vaccinations') {
            $vaccinationsid = $request->vaccinations;
            $vaccinations = explode(',', $jobexist->vaccinations);
            $key = array_search($vaccinationsid, $vaccinations);
            if ($key !== false) {
                unset($vaccinations[$key]);
            }
            $vaccinations = implode(',', $vaccinations);
            $job = Job::where(['id' => $request->job_id])->update(['vaccinations' => $vaccinations]);
            if ($job) {
                $responseData = [
                    'status' => 'success',
                    'message' => 'Deleted Successfully',
                ];
                return response()->json($responseData);
            }
        } else {
            $specialtyid = $request->specialty;
            if (isset($jobexist->preferred_specialty)) {
                $specialty = explode(',', $jobexist->preferred_specialty);
                $key = array_search($specialtyid, $specialty);
                if ($key !== false) {
                    unset($specialty[$key]);
                }
                $specialty = implode(',', $specialty);
                if (count(explode(',', $jobexist->preferred_specialty)) == explode(',', $jobexist->preferred_experience)) {
                    if (isset($jobexist->preferred_experience)) {
                        $experience = explode(',', $jobexist->preferred_experience);
                        if ($key !== false) {
                            unset($experience[$key]);
                        }
                        $experience = implode(',', $experience);
                        $job = Job::where(['id' => $request->job_id])->update(['preferred_experience' => $experience]);
                    }
                }
                $job = Job::where(['id' => $request->job_id])->update(['preferred_specialty' => $specialty]);
                if ($job) {
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
