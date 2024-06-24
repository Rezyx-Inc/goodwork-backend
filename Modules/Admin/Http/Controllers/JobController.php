<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Routing\Controller;
use DataTables;
use Carbon\Carbon;
use Validator;
use DB;
use File;
use Str;
/** Models */
use App\Models\{Job,JobReference,Offer, User, Nurse, Countries, States, Cities, Keyword};

use App\Traits\HelperTrait;

class JobController extends AdminController
{
    use HelperTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->input());

            if (!session()->has('total_jobs')) {
                $total_records  = Job::with('jobState','jobCity')
                ->select('jobs.id', 'jobs.job_name','jobs.created_at','jobs.preferred_hourly_pay_rate','jobs.job_city','jobs.job_state', DB::raw('COUNT(offers.job_id) AS total_applications'))
                ->leftJoin('offers', 'jobs.id', '=', 'offers.job_id')
                ->whereNull('jobs.deleted_at')
                ->groupBy('jobs.id')->orderBy('jobs.created_at', 'DESC')->get()->count();
                session()->put('total_jobs', $total_records);
            }
            else {
                $total_records = session()->get('total_jobs');
            }
            $data =  Job::
            select('jobs.id', 'jobs.job_name','jobs.created_at','jobs.preferred_hourly_pay_rate','jobs.job_city','jobs.job_state', DB::raw('COUNT(offers.job_id) AS total_applications'))
            ->leftJoin('offers', 'jobs.id', '=', 'offers.job_id')
            ->whereNull('jobs.deleted_at')
            ->groupBy('jobs.id')
            ->skip($request->start)
            ->limit($request->length)
            ->orderBy('jobs.created_at', 'DESC')->get();
            return DataTables::of($data)
            ->setTotalRecords($total_records)
            ->skipPaging()
            ->addColumn('checkbox', 'admin::ajax.checkbox')
            // ->addIndexColumn()
            ->editColumn('created_at', function($model) {
                return Carbon::parse($model->created_at)->format('m/d/Y h:i A');
            })
            ->editColumn('id', function($model) {
                return '<a href="' . Route('jobs.edit', ['id' => $model->id]) . '">'.$model->id.'</a>';
            })
            ->editColumn('preferred_hourly_pay_rate', function($model){
                return $model->preferred_hourly_pay_rate ?? 'N/A';
            })

            ->editColumn('job_name', function($model){
                return $model->job_name;
            })
            ->editColumn('job_city', function($model) {
                $str = [];
                if (!empty($model->job_city)) {
                    $str[] = $model->jobCity->name;
                }
                if (!empty($model->job_state)) {
                    $str[] = $model->jobState->name;
                }
                if (!count($str)) {
                    $str[] = 'N/A';
                }
                return implode(', ', $str);
            })
            ->addColumn('action', function($model){
                $html = '<div class="row">';
                $html .= '<div class="col-6"><a href="' . Route('jobs.edit', ['id' => $model->id]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="Edit">'
                . '<i class="fa fa-edit"></i>'
                . '</a>'
                .'</div>'
                .'<div class="col-6"><a href="' . Route('jobs.show', ['id' => $model->id]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="View">'
                . '<i class="fa fa-eye"></i>'
                . '</a>'
                .'</div>'
                .'</div>';
                return $html;
            })
            ->rawColumns(['checkbox','action','id'])
            ->toJson();
        }
        return view('admin::job.index');
    }

    public function create()
    {
        $data = [];
        $data['countries'] = Countries::where('flag','1')
        ->orderByRaw("CASE WHEN iso3 = 'USA' THEN 1 WHEN iso3 = 'CAN' THEN 2 ELSE 3 END")
        ->orderBy('name','ASC')->get();
        $data['usa'] = $usa =  Countries::where(['iso3'=>'USA'])->first();
        $data['us_states'] = States::where('country_id', $usa->id)->get();

        $data['types'] = Keyword::where(['filter'=>'SettingType','active'=>'1'])->get();
        $data['terms'] = Keyword::where(['filter'=>'jobType','active'=>'1'])->get();
        $data['professions'] = Keyword::where(['filter'=>'Profession','active'=>'1'])->get();
        $data['vaccinations'] = Keyword::where(['filter'=>'Vaccinations','active'=>'1'])->get();
        $data['prefered_shifts'] = Keyword::where(['filter'=>'PreferredShift','active'=>'1'])->get();

        $data['recruiters'] = User::where(['active'=>'1,', 'role'=>'RECRUITER'])->orderBy('first_name','ASC')->get();
        return view('admin::job.create', $data);
    }


    public function store(Request $request)
    {
        // dd($request->input());
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'job_name' => 'required|max:100',
                'type' => 'required',
                'terms' => 'required',
                // 'recruiter_id'=>'required'
            ]);
            if ($validator->fails()) {
                return new JsonResponse(['errors' => $validator->errors()], 422);
            }else{
                $user = auth()->guard('admin')->user();
                $input = [];
                $input["job_name"] = $request->job_name;
                $input["type"] = isset($request->type) ? $request->type:'';
                $input["recruiter_id"] = isset($request->recruiter_id) ? $request->recruiter_id:'';
                $input["compact"] = isset($request->compact)?$request->compact:'';
                $input["terms"] = isset($request->terms)?$request->terms:'';
                $input["profession"] = isset($request->profession)?$request->profession:'';
                $input["preferred_specialty"] = $request->specialty;
                $input["preferred_experience"] = $request->experience;
                $input["job_location"] = isset($request->professional_licensure)?$request->professional_licensure:'';
                $input["vaccinations"] = $request->vaccinations;
                /*
                $input["number_of_references"] = isset($request->number_of_references)?$request->number_of_references:'';
                $input["min_title_of_reference"] = isset($request->min_title_of_reference)?$request->min_title_of_reference:'';
                $input["recency_of_reference"] = isset($request->recency_of_reference)?$request->recency_of_reference:'';
                */
                $input["certificate"] = $request->certificate;
                $input["skills"] = isset($request->skills_checklist)?$request->skills_checklist:'';
                $input["urgency"] = isset($request->urgency)?$request->urgency:'';
                $input["position_available"] = isset($request->position_available)?$request->position_available:'';
                $input["msp"] = isset($request->msp)?$request->msp:'';
                $input["vms"] = isset($request->vms)?$request->vms:'';
                $input["submission_of_vms"] = isset($request->submission_of_vms)?$request->submission_of_vms:'';
                $input["block_scheduling"] = isset($request->block_scheduling)?$request->block_scheduling:'';
                $input["float_requirement"] = isset($request->float_requirement)?$request->float_requirement:'';
                $input["facility_shift_cancelation_policy"] = isset($request->facility_shift_cancelation_policy)?$request->facility_shift_cancelation_policy:'';
                $input["contract_termination_policy"] = isset($request->contract_termination_policy)?$request->contract_termination_policy:'';
                $input["traveler_distance_from_facility"] = isset($request->traveler_distance_from_facility)?$request->traveler_distance_from_facility:'';
                $input["facility_id"] = isset($request->facility_id)?$request->facility_id : '';
                $input["clinical_setting"] = isset($request->clinical_setting)?$request->clinical_setting:'';
                $input["Patient_ratio"] = isset($request->Patient_ratio)?$request->Patient_ratio:'';
                $input["emr"] = isset($request->emr)?$request->emr:'';
                $input["Unit"] = isset($request->Unit)?$request->Unit:'';
                $input["Department"] = isset($request->Department)?$request->Department:'';
                $input["Bed_Size"] = isset($request->Bed_Size)?$request->Bed_Size:'';
                $input["Trauma_Level"] = isset($request->Trauma_Level)?$request->Trauma_Level:'';
                $input["scrub_color"] = isset($request->scrub_color)?$request->scrub_color:'';
                $input["start_date"] = isset($request->start_date)?$request->start_date:'';
                $input["as_soon_as"] = isset($request->as_soon_as)?$request->as_soon_as:'';
                $input["rto"] = isset($request->rto)?$request->rto:'';

                $input["preferred_shift"] = isset($request->preferred_shift)?$request->preferred_shift:'';
                $input["hours_per_week"] = isset($request->hours_per_week)?$request->hours_per_week:'';
                $input["guaranteed_hours"] = isset($request->guaranteed_hours)?$request->guaranteed_hours:'';
                $input["hours_shift"] = isset($request->hours_shift)?$request->hours_shift:'';
                $input["weeks_shift"] = isset($request->weeks_shift)?$request->weeks_shift:'';
                $input["preferred_assignment_duration"] = isset($request->preferred_assignment_duration)?$request->preferred_assignment_duration:'';
                $input["referral_bonus"] = isset($request->referral_bonus)?$request->referral_bonus:'';
                $input["sign_on_bonus"] = isset($request->sign_on_bonus)?$request->sign_on_bonus:'';
                $input["completion_bonus"] = isset($request->completion_bonus)?$request->completion_bonus:'';
                $input["extension_bonus"] = isset($request->extension_bonus)?$request->extension_bonus:'';
                $input["other_bonus"] = isset($request->other_bonus)?$request->other_bonus:'';
                $input["four_zero_one_k"] = isset($request->four_zero_one_k)?$request->four_zero_one_k:'';
                $input["health_insaurance"] = isset($request->health_insaurance)?$request->health_insaurance:'';
                $input["dental"] = isset($request->dental)?$request->dental:'';
                $input["vision"] = isset($request->vision)?$request->vision:'';
                $input["actual_hourly_rate"] = isset($request->actual_hourly_rate)?$request->actual_hourly_rate:'';
                $input["feels_like_per_hour"] = isset($request->feels_like_per_hour)?$request->feels_like_per_hour:'';
                $input["overtime"] = isset($request->overtime)?$request->overtime:'';
                $input["holiday"] = isset($request->holiday)?$request->holiday:'';
                $input["on_call"] = isset($request->on_call)?$request->on_call:'';
                $input["orientation_rate"] = isset($request->orientation_rate)?$request->orientation_rate:'';
                $input["weekly_taxable_amount"] = isset($request->weekly_taxable_amount)?$request->weekly_taxable_amount:'';
                $input["weekly_non_taxable_amount"] = isset($request->weekly_non_taxable_amount)?$request->weekly_non_taxable_amount:'';
                $input["employer_weekly_amount"] = isset($request->employer_weekly_amount)?$request->employer_weekly_amount:'';
                $input["goodwork_weekly_amount"] = isset($request->goodwork_weekly_amount)?$request->goodwork_weekly_amount:'';
                $input["total_employer_amount"] = isset($request->total_employer_amount)?$request->total_employer_amount:'';
                $input["total_goodwork_amount"] = isset($request->total_goodwork_amount)?$request->total_goodwork_amount:'';
                $input["total_contract_amount"] = isset($request->total_contract_amount)?$request->total_contract_amount:'';

                $input["facilitys_parent_system"] = isset($request->facilitys_parent_system)?$request->facilitys_parent_system:'';
                $input["facility_average_rating"] = isset($request->facility_average_rating)?$request->facility_average_rating:'';
                $input["recruiter_average_rating"] = isset($request->recruiter_average_rating)?$request->recruiter_average_rating:'';
                $input["employer_average_rating"] = isset($request->employer_average_rating)?$request->employer_average_rating:'';
                $input["job_city"] = isset($request->city)?$request->city:'';
                $input["job_state"] = isset($request->state)?$request->state:'';
                $input["active"] = isset($request->active)?$request->active:'0';

                $input["created_by"] = $user->id;
                $input["goodwork_number"] = uniqid();
                Job::create($input);
                return new JsonResponse(['success' => true, 'msg'=>'Job created successfully.'], 200);
            }
        }
    }

    public function show(Request $request, $id)
    {
        $data = [];
        $data['model'] = Job::findOrFail($id);
        if ($request->ajax()) {
            $data =  Offer::where(['job_id'=>$id])
            ->orderBy('created_at', 'DESC')->get();

            return DataTables::of($data)
            ->addColumn('checkbox', 'admin::ajax.checkbox')
            // ->addIndexColumn()
            ->editColumn('created_at', function($model) {
                return Carbon::parse($model->created_at)->format('Y.m.d h:i A');
            })
            ->editColumn('worker', function($model){
                return $model->user->first_name ?? 'N/A';
            })
            ->addColumn('action', function($model){
                $html = '<div class="row">';
                $html .= '<div class="col-6"><a href="' . Route('jobs.edit', ['id' => $model->id]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="Edit">'
                . '<i class="fa fa-edit"></i>'
                . '</a>'
                .'</div>'
                .'<div class="col-6"><a href="' . Route('jobs.show', ['id' => $model->id]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="View">'
                . '<i class="fa fa-eye"></i>'
                . '</a>'
                .'</div>'
                .'</div>';
                return $html;
            })
            ->rawColumns(['checkbox','action','job_name'])
            ->toJson();
        }
        return view('admin::job.show', $data);
    }

    public function edit($id)
    {
        $data = [];
        $data['model'] = $model = Job::findOrFail($id);

        // dd($model->job_type);
        // $data['countries'] = $country = Countries::select('id','name')->where('flag','1')->orderBy('name','ASC')->get();
        // $data['states'] = $state = States::select('id','name')->where(['flag'=>'1', 'country_id'=>$model->country])->orderBy('name','ASC')->get();
        // $data['cities'] = Cities::select('id','name')->where(['flag'=>'1', 'state_id'=>$model->state])->orderBy('name','ASC')->get();
        $data['countries'] = Countries::where('flag','1')
        ->orderByRaw("CASE WHEN iso3 = 'USA' THEN 1 WHEN iso3 = 'CAN' THEN 2 ELSE 3 END")
        ->orderBy('name','ASC')->get();
        $data['usa'] = $usa =  Countries::where(['iso3'=>'USA'])->first();
        $data['us_states'] = States::where('country_id', $usa->id)->get();

        $data['types'] = Keyword::where(['filter'=>'SettingType','active'=>'1'])->get();
        $data['terms'] = Keyword::where(['filter'=>'jobType','active'=>'1'])->get();
        $data['professions'] = Keyword::where(['filter'=>'Profession','active'=>'1'])->get();
        $data['vaccinations'] = Keyword::where(['filter'=>'Vaccinations','active'=>'1'])->get();
        $data['prefered_shifts'] = Keyword::where(['filter'=>'PreferredShift','active'=>'1'])->get();

        $data['recruiters'] = User::where(['active'=>'1,', 'role'=>'RECRUITER'])->orderBy('first_name','ASC')->get();

        $data['references'] = JobReference::where(['job_id'=>$id])->get();
        // dd($data['references']);
        return view('admin::job.edit', $data);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'job_name' => 'required|max:100',
            'type' => 'required',
            'terms' => 'required',
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }else{
            $model = Job::findOrFail($id);
            $inputFields = collect($request->all())->filter(function ($value) {
                return $value !== null;
            });

            $inputFields->put('updated_at', Carbon::now());
            $model->fill($inputFields->all());
            $model->save();
            return new JsonResponse(['success' => true, 'msg'=>'Updated successfully.', 'link'=>route('jobs.index')], 200);
        }
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('ids')) {
                for($i=0; $i<count($request->ids); $i++){
                    $job = Job::findOrFail($request->ids[$i]);
                    $delete_date = Carbon::now();
                    $job->deleted_at = $delete_date;
                    $job->active = '0';
                    $job->updated_at = $delete_date;
                    $job->save();
                }
                $response = array('success'=>true,'msg'=>'Deleted Successfully!');
                // dd($request->ids);
                return $response;
            }
        }
    }

    public function get_speciality(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('kid')) {
                $keywords = Keyword::where(['active'=>'1','filter'=>$request->kid])->get();
                $content = '<option value="">Select</option>';
                if ($keywords->count()) {
                    foreach($keywords as $k)
                    {
                        $content .= '<option value="'.$k->title.'">'.$k->title.'</option>';
                    }
                }else{
                    $content = '<option value="">No speciality found.</option>';
                }
                $response = array('success'=>true ,'content'=>$content);
                return $response;
            }
        }
    }

    public function deleteOffers(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('ids')) {
                for($i=0; $i<count($request->ids); $i++){
                    $job = Offer::findOrFail($request->ids[$i]);
                    $delete_date = Carbon::now();
                    $job->deleted_at = $delete_date;
                    $job->active = '0';
                    $job->updated_at = $delete_date;
                    $job->save();
                }
                $response = array('success'=>true,'msg'=>'Deleted Successfully!');
                // dd($request->ids);
                return $response;
            }
        }
    }

    public function submit_job_reference(Request $request, $id)
    {
        // dd($request->input());
        if ($request->ajax()) {
            if ($request->has('old_ids')) {
                $to_be_deleted = JobReference::where(['job_id'=>$id])->whereNotIn('id',$request->old_ids)->get();
                foreach ($to_be_deleted as $key => $m) {
                    if (!empty($m->image)) {
                        if(file_exists(public_path('uploads/frontend/jobs/images/' . $m->image)))
                        {
                            File::delete(public_path('uploads/frontend/jobs/images/' . $m->image));
                        }
                    }
                    $m->delete();
                }
                foreach ($request->old_ids as $k => $v) {
                    $model = JobReference::findOrFail($v);
                    $input = [];
                    $input['name'] = $request->old_name[$k];
                    $input['email'] = $request->old_email[$k];
                    $input['phone'] = $request->old_phone[$k];
                    $input['date_referred'] = $request->old_date_referred[$k];
                    $input['min_title_of_reference'] = $request->old_min_title_of_reference[$k];
                    $input['recency_of_reference'] = $request->old_recency_of_reference[$k];
                    if ($request->hasFile('old_image')) {
                        $old_images = $request->file('old_image');
                        if (array_key_exists($k,$old_images)) {
                            if ($old_images[$k]->isValid()) {
                                if (!empty($model->image)) {
                                    if(file_exists(public_path('uploads/frontend/jobs/images/' . $model->image)))
                                    {
                                        File::delete(public_path('uploads/frontend/jobs/images/' . $model->image));
                                    }
                                }

                                $img_name = Str::uuid() . '_' . $old_images[$k]->getClientOriginalName();
                                $old_images[$k]->move(public_path('uploads/frontend/jobs/images/'), $img_name);
                                $input['image'] = $img_name;
                            }
                        }
                    }
                    $model->update($input);
                }
            }

            if ($request->has('name')) {
                foreach ($request->name as $k => $value) {
                    $input = [];
                    $input['job_id'] = $id;
                    $input['name'] = $value;
                    $input['email'] = $request->email[$k];
                    $input['phone'] = $request->phone[$k];
                    $input['date_referred'] = $request->date_referred[$k];
                    $input['min_title_of_reference'] = $request->min_title_of_reference[$k];
                    $input['recency_of_reference'] = $request->recency_of_reference[$k];
                    if ($request->hasFile('image')) {
                        $images = $request->file('image');
                        if ($images[$k]->isValid()) {
                            $img_name = Str::uuid() . '_' . $images[$k]->getClientOriginalName();
                            $images[$k]->move(public_path('uploads/frontend/jobs/images/'), $img_name);
                            $input['image'] = $img_name;
                        }
                    }
                    JobReference::create($input);
                }
            }
            Job::findOrFail($id)->update(['number_of_references'=>$request->number_of_references]);
            $response = array('success'=>true,'msg'=>'Updated Successfully!', 'link'=>route('jobs.edit',['id'=>$id]));
            return $response;
        }
    }


}
