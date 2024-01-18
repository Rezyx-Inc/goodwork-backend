<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Carbon\Carbon;
use Validator;
use Hash;
use App\Enums\Role;
use File;
/** Models */
use App\Models\{User, Worker, WorkerReference, WorkerAsset,Keyword, Facility, Availability, Countries, States, Cities};

class UserController extends Controller
{
    public function show()
    {

        $data = [];
        $data['model'] = Worker::findOrFail($id);
        return view('user.show', $data);
    }

    public function edit()
    {
        $data = [];
        $user = auth()->guard('frontend')->user();
        $id = $user->worker->id;
        $data['user'] = $user;
        $data['model'] = $user->worker;
        // dd($data['model']);
        // $data['countries'] = $country = Countries::select('id','name')->where('flag','1')->orderBy('name','ASC')->get();
        // $data['states'] = $state = States::select('id','name')->where(['flag'=>'1', 'country_id'=>$model->country])->orderBy('name','ASC')->get();
        // $data['cities'] = Cities::select('id','name')->where(['flag'=>'1', 'state_id'=>$model->state])->orderBy('name','ASC')->get();

        $data['countries'] = Countries::where('flag','1')
        ->orderByRaw("CASE WHEN iso3 = 'USA' THEN 1 WHEN iso3 = 'CAN' THEN 2 ELSE 3 END")
        ->orderBy('name','ASC')->get();
        $data['usa'] = $usa =  Countries::where(['iso3'=>'USA'])->first();
        $data['us_states'] = States::where('country_id', $usa->id)->get();
        $data['us_cities'] = Cities::where('country_id', $usa->id)->get();

        $data['types'] = Keyword::where(['filter'=>'SettingType','active'=>'1'])->get();
        $data['terms'] = Keyword::where(['filter'=>'jobType','active'=>'1'])->get();
        $data['professions'] = Keyword::where(['filter'=>'Profession','active'=>'1'])->get();
        $data['vaccinations'] = Keyword::where(['filter'=>'Vaccinations','active'=>'1'])->get();
        $data['prefered_shifts'] = Keyword::where(['filter'=>'PreferredShift','active'=>'1'])->get();
        $data['license_types'] = Keyword::where(['filter'=>'LicenseType','active'=>'1'])->get();
        $data['msps'] = Keyword::where(['filter'=>'MSP','active'=>'1'])->get();
        $data['vmss'] = Keyword::where(['filter'=>'VMS','active'=>'1'])->get();
        $data['assignment_durations'] = Keyword::where(['filter'=>'AssignmentDuration','active'=>'1'])->get();
        $data['contract_policies'] = Keyword::where(['filter'=>'ContractTerminationPolicy','active'=>'1'])->get();
        $data['emrs'] = Keyword::where(['filter'=>'EMR','active'=>'1'])->get();
        $data['shift_tile_of_day'] = Keyword::where(['filter'=>'shift_time_of_day','active'=>'1'])->get();

        $data['visions'] = Keyword::where(['filter'=>'Vision','active'=>'1'])->get();
        $data['insurances'] = Keyword::where(['filter'=>'HealthInsurance','active'=>'1'])->get();
        $data['four_zero_1k'] = Keyword::where(['filter'=>'401k','active'=>'1'])->get();
        $data['dentals'] = Keyword::where(['filter'=>'Dental','active'=>'1'])->get();

        $data['references'] = WorkerReference::where(['worker_id'=>$id])->get();
        $data['covidVac'] = WorkerAsset::where(['worker_id'=> $id,'filter'=> 'covid'])->whereNull('deleted_at')->first();
        $data['fluVac'] = WorkerAsset::where(['worker_id'=> $id,'filter'=> 'flu'])->whereNull('deleted_at')->first();
        // dd($data['covidVac']);
        $data['palsCer'] = WorkerAsset::where(['worker_id'=> $id,'filter'=> 'PALS'])->whereNull('deleted_at')->first();
        $data['blsCer'] = WorkerAsset::where(['worker_id'=> $id,'filter'=> 'BLS'])->whereNull('deleted_at')->first();
        $data['aclsCer'] = WorkerAsset::where(['worker_id'=> $id,'filter'=> 'ACLS'])->whereNull('deleted_at')->first();
        $data['otherCer'] = WorkerAsset::where(['worker_id'=> $id,'filter'=> 'Other'])->whereNull('deleted_at')->first();
        $data['diplomaCer'] = WorkerAsset::where(['worker_id'=> $id,'filter'=> 'diploma'])->whereNull('deleted_at')->first();
        $data['driving_license'] = WorkerAsset::where(['worker_id'=> $id,'filter'=> 'driving_license'])->whereNull('deleted_at')->first();
        $data['skillsCer'] = WorkerAsset::where(['worker_id'=> $id,'filter'=> 'skill'])->whereNull('deleted_at')->get();
        $data['facilities'] = Facility::whereNull('deleted_at')->where(['active'=>'1'])->orderBy('name','DESC')->get();
        $view = '';
        switch (request()->route()->getName()) {
            case 'my-profile':
                $view = 'basic_info';
                break;
            case 'vaccination':
                $view = 'vaccination';
                break;
            case 'certification':
                $view = 'certification';
                break;

            case 'references':
                $view = 'references';
                break;
            case 'info-required':
                $view = 'info_required';
                break;
            case 'urgency':
                $view = 'urgency';
                break;

            case 'float-requirement':
                $view = 'float_requirement';
                break;
            case 'patient-ratio':
                $view = 'patient_ratio';
                break;
            case 'interview-dates':
                $view = 'interview_dates';
                break;
            case 'bonuses':
                $view = 'bonuses';
                break;
            case 'work-hours':
                $view = 'work_hours';
                break;
            default:
                return redirect()->back();
                break;
        }

        return view('user.'.$view, $data);
    }

    public function update(Request $request)
    {
        // dd($request->input());
        $user = auth()->guard('frontend')->user();
        $id = $user->worker->id;
        $model = Worker::findOrFail($id);
        $inputFields = collect($request->all())->filter(function ($value) {
            return $value !== null;
        });

        $inputFields->put('updated_at', Carbon::now());
        // dd($inputFields);
        $model->fill($inputFields->all());
        $model->save();
        return new JsonResponse(['success' => true, 'msg'=>'Updated successfully.'], 200);

    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('ids')) {
                for($i=0; $i<count($request->ids); $i++){
                    $worker = Worker::findOrFail($request->ids[$i]);
                    $user = $worker->user;
                    $delete_date = Carbon::now();
                    $worker->deleted_at = $delete_date;
                    $worker->active = '0';
                    $worker->updated_at = $delete_date;
                    $worker->save();
                    $user->deleted_at = $delete_date;
                    $user->active = '0';
                    $user->updated_at = $delete_date;
                    $user->save();
                }
                $response = array('success'=>true,'msg'=>'Deleted Successfully!');
                // dd($request->ids);
                return $response;
            }
        }
    }

    /* Invite Workers */
    public function invite(Request $request){

        if ($request->ajax()) {
            if ($request->has('ids')) {
                for($i=0; $i<count($request->ids); $i++){
                    $worker = Worker::findOrFail($request->ids[$i]);
                    $resp = $this->sendInvite($worker->user);
                }
                $response = array('success'=>$resp['success'],'msg'=>$resp['msg']);
                return $response;
            }
        }
    }

    public function get_state(Request $request){

        if ($request->ajax()) {
            if ($request->has('country_id')) {
                $states = $this->get_states($request->country_id);
                $content = '';
                if ($states->count()) {
                    foreach($states as $s)
                    {
                        $content .= '<option value="'.$s->id.'">'.$s->name.'</option>';
                    }
                }else{
                    $content .= '<option>No state found.</option>';
                }
                $response = array('success'=>true ,'content'=>$content);
                return $response;
            }
        }
    }

    public function get_city(Request $request){

        if ($request->ajax()) {
            if ($request->has('state_id')) {
                $cities = $this->get_cities($request->state_id);
                $content = '';
                if ($cities->count()) {
                    foreach($cities as $c)
                    {
                        $content .= '<option value="'.$c->id.'">'.$c->name.'</option>';
                    }
                }else{
                    $content .= '<option>No city found.</option>';
                }
                $response = array('success'=>true ,'content'=>$content);
                return $response;
            }
        }
    }

    public function post_references(Request $request)
    {

        if ($request->ajax()) {
            $user = auth()->guard('frontend')->user();
            $id = $user->worker->id;
            if ($request->has('old_ids')) {
                $to_be_deleted = WorkerReference::where(['worker_id'=>$id])->whereNotIn('id',$request->old_ids)->get();
                foreach ($to_be_deleted as $key => $m) {
                    if (!empty($m->image)) {
                        if(file_exists(public_path('images/workers/reference/' . $m->image)))
                        {
                            File::delete(public_path('images/workers/reference/' . $m->image));
                        }
                    }
                    $m->delete();
                    // WorkerReference::find($m->id)->forceDelete();
                }
                // dd($request->input());
                foreach ($request->old_ids as $k => $v) {
                    $model = WorkerReference::findOrFail($v);
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
                                    if(file_exists(public_path('images/workers/reference/' . $model->image)))
                                    {
                                        File::delete(public_path('images/workers/reference/' . $model->image));
                                    }
                                }
                                $reference_name = $old_images[$k]->getClientOriginalName();
                                $reference_ext = $old_images[$k]->getClientOriginalExtension();
                                $img_name = $reference_name.'_'.time().'.'.$reference_ext;

                                $old_images[$k]->move(public_path('images/workers/reference/'), $img_name);
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
                    $input['worker_id'] = $id;
                    $input['name'] = $value;
                    $input['email'] = $request->email[$k];
                    $input['phone'] = $request->phone[$k];
                    $input['date_referred'] = $request->date_referred[$k];
                    $input['min_title_of_reference'] = $request->min_title_of_reference[$k];
                    $input['recency_of_reference'] = $request->recency_of_reference[$k];
                    if ($request->hasFile('image')) {
                        $images = $request->file('image');
                        if ($images[$k]->isValid()) {
                            $reference_name = $images[$k]->getClientOriginalName();
                                $reference_ext = $images[$k]->getClientOriginalExtension();
                                $img_name = $reference_name.'_'.time().'.'.$reference_ext;
                            $images[$k]->move(public_path('images/workers/reference/'), $img_name);
                            $input['image'] = $img_name;
                        }
                    }
                    WorkerReference::create($input);
                }
            }
            // dd($request->worker_number_of_references);
            // Worker::findOrFail($id)->update(['worker_number_of_references'=>$request->worker_number_of_references]);
            $response = array('success'=>true,'msg'=>'Updated Successfully!');
            return $response;
        }
    }

    public function vaccination_submit(Request $request)
    {
        // dd($request->input());
        $user = auth()->guard('frontend')->user();
        $id = $user->worker->id;
        $worker = WORKER::findOrFail($id);
        $vaccination = [];
        if(isset($worker->worker_vaccination) && !empty($worker->worker_vaccination)){
            $resu = json_decode($worker->worker_vaccination);
            foreach($resu as $vac){
                $vaccination[] = $vac;
            }
        }else{
            $vaccination[0] = "";
            $vaccination[1] = "";
        }
        // upload covid
        if ($request->covid_vac == 'Yes') {
            $asset = WorkerAsset::where('worker_id', $id)->where('filter', 'covid')->first();
            $input = [];
            $input['using_date'] = isset($request->covid_date)?$request->covid_date:'';
            $input['worker_id'] = $id;
            $input['filter'] = 'covid';
            if ($request->hasFile('covid'))
            {
                $file = $request->file('covid');
                $name = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $covid_name = $name.'_'.time().'.'.$ext;
                $destinationPath = 'images/workers/vaccination';
                $file->move(public_path($destinationPath), $covid_name);
                $input['name'] = $covid_name;

            }

            if (!empty($asset)) {

                if (isset($input['name'])) {
                    if(!empty($asset->name)){
                        if(file_exists(public_path('images/workers/vaccination/' . $asset->name)))
                        {
                            File::delete(public_path('images/workers/vaccination/' . $asset->name));
                        }
                    }
                }
                $asset->update($input);
            }else{
                $asset = WorkerAsset::create($input);
            }
            $worker_covid_name = !empty($asset->name) ? $asset->name : "";

        }else{
            $covid = WorkerAsset::where('worker_id', $id)->where('filter', 'covid')->first();
            if ($covid) {
                if(!empty($covid->name)){
                    if(file_exists(public_path('images/workers/vaccination/' . $covid->name)))
                    {
                        File::delete(public_path('images/workers/vaccination/' . $covid->name));
                    }
                }
                $covid->delete();
            }
            $worker_covid_name = "";
        }

        // Upload flu
        if ($request->flu_vac == 'Yes') {
            $asset = WorkerAsset::where('worker_id', $id)->where('filter', 'flu')->first();

            $input['using_date'] = isset($request->flu_date)?$request->flu_date:'';
            $input['filter'] = 'flu';
            $input['worker_id'] = $id;

            if ($request->hasFile('flu'))
            {
                $file = $request->file('flu');
                $name = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $flu_name = $name.'_'.time().'.'.$ext;
                $destinationPath = 'images/workers/vaccination';
                $file->move(public_path($destinationPath), $flu_name);
                $input['name'] = $flu_name;

            }
            if (!empty($asset)) {
                if (isset($input['name'])) {
                    if(!empty($asset->name)){
                        if(file_exists(public_path('images/workers/vaccination/' . $asset->name)))
                        {
                            File::delete(public_path('images/workers/vaccination/' . $asset->name));
                        }
                    }
                }
                $asset->update($input);
            }else{
                $asset = WorkerAsset::create($input);
            }
            $worker_flu_name =  !empty($asset->name) ? $asset->name : "";
        }else{
            $flu = WorkerAsset::where('worker_id', $id)->where('filter', 'flu')->first();
            if ($flu) {
                if(!empty($flu->name)){
                    if(file_exists(public_path('images/workers/vaccination/' . $flu->name)))
                    {
                        File::delete(public_path('images/workers/vaccination/' . $flu->name));
                    }
                }
                $flu->delete();
            }
            $worker_flu_name = "";
        }

        $vaccination[0] = $worker_covid_name;
        $vaccination[1] = $worker_flu_name;
        $vaccination = json_encode($vaccination);

        WORKER::where(['id' => $worker->id])->update([
            'worker_vaccination' => $vaccination,
        ]);

        $response = array('success'=>true,'msg'=>'Updated Successfully!');
        return $response;

    }

    public function certification_submit(Request $request)
    {
        $user = auth()->guard('frontend')->user();
        $id = $user->worker->id;
        $worker = WORKER::findOrFail($id);
        // BLS
        if ($request->bls_cer == 'Yes') {
            if ($request->hasFile('bls'))
            {
                WorkerAsset::where('worker_id', $id)->where('filter', 'BLS')->forceDelete();
                if (!empty($worker->BLS)) {
                    if(file_exists(public_path('images/workers/certificate/').$worker->BLS))
                    {
                        File::delete(public_path('images/workers/certificate/').$worker->BLS);
                    }
                }
                $file = $request->file('bls');
                $bls_name = $file->getClientOriginalName();
                $bls_ext = $file->getClientOriginalExtension();
                $bls = $bls_name.'_'.time().'.'.$bls_ext;
                $destinationPath = 'images/workers/certificate';
                $file->move(public_path($destinationPath), $bls);

                // write image name in worker table
                $worker->BLS = $bls;
                WorkerAsset::create([
                    'worker_id' => $id,
                    'name' => $bls,
                    'filter' => 'BLS'
                ]);
            }
        }else{
            WorkerAsset::where('worker_id', $id)->where('filter', 'BLS')->forceDelete();
            if (!empty($worker->BLS)) {
                if(file_exists(public_path('images/workers/certificate/').$worker->BLS))
                {
                    File::delete(public_path('images/workers/certificate/').$worker->BLS);
                }
            }
            $worker->BLS = '';
        }

        // ACLS
        if ($request->acls_cer == 'Yes') {
            if ($request->hasFile('acls'))
            {
                WorkerAsset::where('worker_id', $id)->where('filter', 'ACLS')->forceDelete();
                if (!empty($worker->ACLS)) {
                    if(file_exists(public_path('images/workers/certificate/').$worker->ACLS))
                    {
                        File::delete(public_path('images/workers/certificate/').$worker->ACLS);
                    }
                }

                $acls_name_full = $request->file('acls')->getClientOriginalName();
                $acls_name = pathinfo($acls_name_full, PATHINFO_FILENAME);
                $acls_ext = $request->file('acls')->getClientOriginalExtension();
                $acls = $acls_name.'_'.time().'.'.$acls_ext;
                $destinationPath = 'images/workers/certificate';
                $request->file('acls')->move(public_path($destinationPath), $acls);

                // write image name in worker table
                $worker->ACLS = $acls;
                WorkerAsset::create([
                    'worker_id' => $id,
                    'name' => $acls,
                    'filter' => 'ACLS'
                ]);
            }
        }else{
            WorkerAsset::where('worker_id', $id)->where('filter', 'ACLS')->forceDelete();
            if (!empty($worker->ACLS)) {
                if(file_exists(public_path('images/workers/certificate/').$worker->ACLS))
                {
                    File::delete(public_path('images/workers/certificate/').$worker->ACLS);
                }
            }
            $worker->ACLS = '';
        }

        // PALS
        if ($request->pals_cer == 'Yes') {
            if ($request->hasFile('pals'))
            {
                WorkerAsset::where('worker_id', $id)->where('filter', 'PALS')->forceDelete();
                if (!empty($worker->PALS)) {
                    if(file_exists(public_path('images/workers/certificate/').$worker->PALS))
                    {
                        File::delete(public_path('images/workers/certificate/').$worker->PALS);
                    }
                }

                $pals_name_full = $request->file('pals')->getClientOriginalName();
                $pals_name = pathinfo($pals_name_full, PATHINFO_FILENAME);
                $pals_ext = $request->file('pals')->getClientOriginalExtension();
                $pals = $pals_name.'_'.time().'.'.$pals_ext;
                $destinationPath = 'images/workers/certificate';
                $request->file('pals')->move(public_path($destinationPath), $pals);

                // write image name in worker table
                $worker->PALS = $pals;
                WorkerAsset::create([
                    'worker_id' => $id,
                    'name' => $pals,
                    'filter' => 'PALS'
                ]);
            }
        }else{
            WorkerAsset::where('worker_id', $id)->where('filter', 'PALS')->forceDelete();
            if (!empty($worker->PALS)) {
                if(file_exists(public_path('images/workers/certificate/').$worker->PALS))
                {
                    File::delete(public_path('images/workers/certificate/').$worker->PALS);
                }
            }
            $worker->PALS = '';
        }

        // OTHER
        if (!empty($request->other_certificate_name)) {
            if ($request->hasFile('other'))
            {

                WorkerAsset::where('worker_id', $id)->where('filter', 'Other')->forceDelete();
                if (!empty($worker->other)) {
                    if(file_exists(public_path('images/workers/certificate/').$worker->other))
                    {
                        File::delete(public_path('images/workers/certificate/').$worker->other);
                    }
                }

                $other_name_full = $request->file('other')->getClientOriginalName();
                $other_name = pathinfo($other_name_full, PATHINFO_FILENAME);
                $other_ext = $request->file('other')->getClientOriginalExtension();
                $other = $other_name.'_'.time().'.'.$other_ext;
                $destinationPath = 'images/workers/certificate';
                $request->file('other')->move(public_path($destinationPath), $other);

                // write image name in worker table
                $worker->other = $other;
                $worker->other_certificate_name = isset($request->other_certificate_name)?$request->other_certificate_name:$worker->other_certificate_name;
                WorkerAsset::create([
                    'worker_id' => $id,
                    'name' => $other,
                    'filter' => 'Other'
                ]);
            }
        }else{
            WorkerAsset::where('worker_id', $id)->where('filter', 'Other')->forceDelete();
            if (!empty($worker->other)) {
                if(file_exists(public_path('images/workers/certificate/').$worker->other))
                {
                    File::delete(public_path('images/workers/certificate/').$worker->other);
                }
            }
            $worker->other = '';
        }


        // WORKER::where(['id' => $worker->id])
        //     ->update([
        //         'BLS' => $worker->BLS,
        //         'ACLS' => $worker->ACLS,
        //         'PALS' => $worker->PALS,
        //         'other' => $worker->other,
        //         'other_certificate_name' => $request->other_certificate_name
        //     ]);
        $response = array('success'=>true,'msg'=>'Updated Successfully!');
        return $response;
    }

    public function skills_submit(Request $request)
    {
        // dd($request->input());
        $user = auth()->guard('frontend')->user();
        $id = $user->worker->id;
        $worker = WORKER::findOrFail($id);
        // Upload driving license
        if ($request->has('dl_cer')) {
            if ($request->dl_cer == 'Yes') {
                if ($request->hasFile('driving_license'))
                {

                    WorkerAsset::where('worker_id', $id)->where('filter', 'driving_license')->forceDelete();
                    if (!empty($worker->driving_license)) {
                        if(file_exists(public_path('images/workers/driving_license/').$worker->driving_license))
                        {
                            File::delete(public_path('images/workers/driving_license/').$worker->driving_license);
                        }
                    }

                    $driving_license_name_full = $request->file('driving_license')->getClientOriginalName();
                    $driving_license_name = pathinfo($driving_license_name_full, PATHINFO_FILENAME);
                    $driving_license_ext = $request->file('driving_license')->getClientOriginalExtension();
                    $driving_license = $driving_license_name.'_'.time().'.'.$driving_license_ext;
                    $destinationPath = 'images/workers/driving_license';
                    $request->file('driving_license')->move(public_path($destinationPath), $driving_license);

                    // write image name in worker table
                    $worker->driving_license = $driving_license;
                    $license_expiration_date = isset($request->license_expiration_date)?$request->license_expiration_date:'';
                    $driving_license_asset = WorkerAsset::create([
                        'worker_id' => $id,
                        'name' => $driving_license,
                        'filter' => 'driving_license',
                        'using_date' => $license_expiration_date,
                    ]);
                }
            }
            else {
                WorkerAsset::where('worker_id', $id)->where('filter', 'driving_license')->forceDelete();
                if (!empty($worker->driving_license)) {
                    if(file_exists(public_path('images/workers/driving_license/').$worker->driving_license))
                    {
                        File::delete(public_path('images/workers/driving_license/').$worker->driving_license);
                    }
                }
                $worker->driving_license = '';
            }
        }

        // Upload diploma
        if ($request->has('diploma_cer')) {
            if ($request->diploma_cer == 'Yes') {
                if ($request->hasFile('diploma'))
                {

                    WorkerAsset::where('worker_id', $id)->where('filter', 'diploma')->forceDelete();
                    if (!empty($worker->diploma)) {
                        if(file_exists(public_path('images/workers/diploma/').$worker->diploma))
                        {
                            File::delete(public_path('images/workers/diploma/').$worker->diploma);
                        }
                    }

                    $diploma_name_full = $request->file('diploma')->getClientOriginalName();
                    $diploma_name = pathinfo($diploma_name_full, PATHINFO_FILENAME);
                    $diploma_ext = $request->file('diploma')->getClientOriginalExtension();
                    $diploma = $diploma_name.'_'.time().'.'.$diploma_ext;
                    $destinationPath = 'images/workers/diploma';
                    $request->file('diploma')->move(public_path($destinationPath), $diploma);

                    // write image name in worker table
                    $worker->diploma = $diploma;
                    $diploma_asset = WorkerAsset::create([
                        'worker_id' => $id,
                        'name' => $diploma,
                        'filter' => 'diploma'
                    ]);
                }
            } else {
                WorkerAsset::where('worker_id', $id)->where('filter', 'diploma')->forceDelete();
                if (!empty($worker->diploma)) {
                    if(file_exists(public_path('images/workers/diploma/').$worker->diploma))
                    {
                        File::delete(public_path('images/workers/diploma/').$worker->diploma);
                    }
                }
                $worker->diploma = '';
            }
        }

        // Upload skills
        $skills_img = explode(',', $worker->skills_checklists);
        if ($request->has('old_skills_ids')) {
            $skills = WorkerAsset::where('worker_id', $id)->where('filter', 'skill')->whereNotIn('id', $request->old_skills_ids)->get();
            foreach($skills as $img){
                if (!empty($img->name)) {
                    if(file_exists(public_path('images/workers/skill/').$img->name))
                    {
                        File::delete(public_path('images/workers/skill/').$img->name);
                    }
                    $keyToDelete = array_search($img->name, $skills_img);
                    if ($keyToDelete !== false) {
                        unset($skills_img[$keyToDelete]);
                    }
                }
                $img->forceDelete();
            }
            // $worker->skills_checklists = implode(',',$skills_img);
            foreach ($request->old_skills_ids as $k => $value) {
                $input = [];
                $skill = WorkerAsset::findOrFail($value);
                if ($request->hasFile('old_skill')) {
                    $old_skills = $request->file('old_skill');
                    if (array_key_exists($k,$old_skills)) {
                        if ($old_skills[$k]->isValid()) {
                            if (!empty($skill->name)) {
                                if(file_exists(public_path('images/workers/skill/' . $skill->name)))
                                {
                                    File::delete(public_path('images/workers/skill/' . $skill->name));
                                }
                            }
                            $name = $old_skills[$k]->getClientOriginalName();
                            $ext = $old_skills[$k]->getClientOriginalExtension();
                            $img_name = $name.'_'.time().'.'.$ext;
                            $keyToDelete = array_search($skill->name, $skills_img);
                            if ($keyToDelete !== false) {
                                $skills_img[$keyToDelete] = $img_name;
                            }
                            $old_skills[$k]->move(public_path('images/workers/skill/'), $img_name);
                            $input['name'] = $img_name;
                        }
                    }
                }
                $input['using_date'] = isset($request->old_completion_date[$k]) ? $request->old_completion_date[$k] : '';
                $skill->update($input);
            }
        }

        if ($request->hasFile('skill')) {
            $images = $request->file('skill');

            foreach($images as $k=>$image)
            {
                $skill_name = $image->getClientOriginalName();
                $skill_ext = $image->getClientOriginalExtension();
                $skill = $skill_name.'_'.time().'.'.$skill_ext;
                $destinationPath = 'images/workers/skill';
                $image->move(public_path($destinationPath), $skill);
                $skills_img[]=$skill;

                // write image name in worker table
                $completion_date = isset($request->completion_date[$k])?$request->completion_date[$k]:'';
                $skill_asset = WorkerAsset::create([
                    'worker_id' => $id,
                    'name' => $skill,
                    'filter' => 'skill',
                    'using_date' => $completion_date
                ]);
            }

        }

        $worker->skills_checklists = implode(',',$skills_img);

        $worker->recent_experience = !empty($request->recent_experience) ? $request->recent_experience : '';
        $worker->worked_at_facility_before = !empty($request->worked_at_facility_before) ? $request->worked_at_facility_before : '';
        $worker->worker_ss_number = !empty($request->worker_ss_number) ? $request->worker_ss_number : '';
        $worker->eligible_work_in_us = !empty($request->eligible_work_in_us) ? $request->eligible_work_in_us : '';
        WORKER::where(['id' => $worker->id])->update([
            'worker_ss_number' => $worker->worker_ss_number,
            'driving_license' => $worker->driving_license,
            'recent_experience' => $worker->recent_experience,
            'worked_at_facility_before' => $worker->worked_at_facility_before,
            'eligible_work_in_us' => $worker->eligible_work_in_us,
            'skills_checklists' => $worker->skills_checklists,
            'diploma' => $worker->diploma,
        ]);
        $response = array('success'=>true,'msg'=>'Updated Successfully!');
        return $response;

    }

    public function urgency_submit(Request $request, $id)
    {
        $model = WORKER::findOrFail($id);
        $model->update([
            'worker_urgency' => $request->worker_urgency,
            'available_position' => $request->available_position,
            'MSP' => $request->MSP,
            'VMS' => $request->VMS,
            'submission_VMS' => $request->submission_VMS,
            'block_scheduling' => $request->block_scheduling,
        ]);
    }

    public function float_requirement_submit(Request $request, $id)
    {
        $model = WORKER::findOrFail($id);
        $model->update([
            'float_requirement' => $request->float_requirement,
            'facility_shift_cancelation_policy' => $request->facility_shift_cancelation_policy,
            'contract_termination_policy' => $request->contract_termination_policy,
            'distance_from_your_home' => $request->distance_from_your_home,
            'facilities_you_like_to_work_at' => $request->facilities_you_like_to_work_at,
            'worker_facility_parent_system' => $request->worker_facility_parent_system,
            'avg_rating_by_facilities' => $request->avg_rating_by_facilities,
            'worker_avg_rating_by_recruiters' => $request->worker_avg_rating_by_recruiters,
            'worker_avg_rating_by_employers' => $request->worker_avg_rating_by_employers,
            'clinical_setting_you_prefer' => $request->clinical_setting_you_prefer
        ]);
    }

    public function patient_ratio_submit(Request $request, $id){
        $model = WORKER::findOrFail($id);
        $model->update([
            'worker_patient_ratio' => $request->worker_patient_ratio,
            'worker_emr' => $request->worker_emr,
            'worker_unit' => $request->worker_unit,
            'worker_department' => $request->worker_department,
            'worker_bed_size' => $request->worker_bed_size,
            'worker_trauma_level' => $request->worker_trauma_level,
            'worker_scrub_color' => $request->worker_scrub_color,
            'worker_facility_city' => $request->worker_facility_city,
            'worker_facility_state_code' => $request->worker_facility_state_code
        ]);
    }

    public function interview_dates_post(Request $request, $id)
    {
        $model = WORKER::findOrFail($id);
        $model->update([
            'worker_interview_dates' => $request->worker_interview_dates,
            'worker_start_date' => $request->worker_start_date,
            'worker_as_soon_as_posible' => $request->worker_as_soon_as_posible,
            'worker_shift_time_of_day' => $request->worker_shift_time_of_day,
            'worker_hours_per_week' => $request->worker_hours_per_week,
            'worker_guaranteed_hours' => $request->worker_guaranteed_hours,
            'worker_hours_shift' => $request->worker_hours_shift,
            'worker_weeks_assignment' => $request->worker_weeks_assignment,
            'worker_shifts_week' => $request->worker_shifts_week,
            'worker_referral_bonus' => $request->worker_referral_bonus
        ]);
    }

    public function worker_bonuses_submit(Request $request, $id)
    {
        $model = WORKER::findOrFail($id);
        $model->update([
            'worker_sign_on_bonus' => $request->worker_sign_on_bonus,
            'worker_completion_bonus' => $request->worker_completion_bonus,
            'worker_extension_bonus' => $request->worker_extension_bonus,
            'worker_other_bonus' => $request->worker_other_bonus,
            'how_much_k' => $request->how_much_k,
            'worker_health_insurance' => $request->worker_health_insurance,
            'worker_dental' => $request->worker_dental,
            'worker_vision' => $request->worker_vision,
            'worker_actual_hourly_rate' => $request->worker_actual_hourly_rate
        ]);
    }

    public function worker_overtime_submit(Request $request, $id)
    {
        $model = WORKER::findOrFail($id);
        $model->update([
            'worker_overtime' => $request->worker_overtime,
            'worker_holiday' => $request->worker_holiday,
            'worker_on_call' => $request->worker_on_call,
            'worker_call_back' => $request->worker_call_back,
            'worker_orientation_rate' => $request->worker_orientation_rate,
            'worker_weekly_taxable_amount' => $request->worker_weekly_taxable_amount,
            'worker_weekly_non_taxable_amount' => $request->worker_weekly_non_taxable_amount,
            'worker_employer_weekly_amount' => $request->worker_employer_weekly_amount,
            'worker_goodwork_weekly_amount' => $request->worker_goodwork_weekly_amount,
            'worker_total_employer_amount' => $request->worker_total_employer_amount,
            'worker_total_goodwork_amount' => $request->worker_total_goodwork_amount,
            'worker_total_contract_amount' => $request->worker_total_contract_amount,
        ]);
    }

    public function update_user_status(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'id'=>'required|integer'
            ]);
            $response = [];
            $user = auth()->guard('frontend')->user();
            $contact = UserMaster::where(['company'=>$user->id,'id'=>$request->id,'status'=>'1'])->first();
            $response['msg'] = 'The user deleted sucessfully.';
            $response['success'] = false;
            if (!empty($contact)) {
                $contact->status = '3';
                $contact->updated_at = Carbon::now();
                $contact->save();
                $response['msg'] = 'The user deleted sucessfully.';
                $response['success'] = true;
                return response()->json($response);
            }
        }
    }

    public function load_dropdown(Request $request)
    {
        if ($request->ajax()) {

        }
    }

}
