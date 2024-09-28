<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Routing\Controller;
use DataTables;
use Carbon\Carbon;
use Validator;
use Hash;
use App\Enums\Role;
use File;
/** Models */
use App\Models\{User, Nurse, NurseReference, NurseAsset, Keyword, Facility, Availability, Countries, States, Cities};

use App\Traits\HelperTrait;

class NurseController extends AdminController
{

    use HelperTrait;
    public function index()
    {
        $data = User::select('users.id')
            ->join('nurses', 'users.id', '=', 'nurses.user_id')
            ->get();
        if ($data->count()) {
            $ids = [];
            foreach ($data as $key => $value) {
                $ids[] = $value->id;
            }
            if (count($ids)) {
                Nurse::whereNotIn('user_id', $ids)->delete();
            }
        }
        return view('admin::nurse.index');
    }

    /**getting data through ajax datatables */
    public function getData()
    {
        $data = Nurse::select('id', 'user_id', 'created_at')
            ->whereNull('deleted_at')->orderBy('created_at', 'DESC')->get();
        return DataTables::of($data)
            ->addColumn('checkbox', 'admin::ajax.checkbox')
            // ->addIndexColumn()
            ->addColumn('name', function ($model) {
                if (!empty($model->user->first_name)) {
                    return $model->user->getFullNameAttribute();
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('email', function ($model) {
                return $model->user->email;
            })
            ->addColumn('mobile', function ($model) {
                if (!empty($model->user->mobile)) {
                    return $model->user->mobile;
                }
                return 'N/A';
            })
            ->editColumn('id', function ($model) {
                return '<a href="' . Route('workers.edit', ['id' => $model->id]) . '">' . $model->id . '</a>';
            })
            // ->addColumn('last_login_at', function($model) {
            //     if ( !empty($model->user->last_login_at) ) {
            //         return $model->user->last_login_at->diffForHumans();
            //     }
            // })
            // ->editColumn('specialty', function($model) {
            //     return \App\Providers\AppServiceProvider::keywordTitle($model->specialty);
            // })
            ->editColumn('created_at', function ($model) {
                // return $model->created_at->diffForHumans();
                return Carbon::parse($model->created_at)->format('m/d/Y h:i A');
            })
            // ->editColumn('address', function(Nurse $model) {
            //     $str = '';
            //     $str .= $model->address;
            //     if ($str === '') {
            //         $str .= 'N/A';
            //     }
            //     return $str;

            // })
            ->addColumn('action', function ($model) {
                $html = '<div class="row">';
                $html .= '<div class="col-6"><a href="' . Route('workers.edit', ['id' => $model->id]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="Edit">'
                    . '<i class="fa fa-edit"></i>'
                    . '</a>'
                    . '</div>'
                    . '<div class="col-6"><a href="' . Route('workers.show', ['id' => $model->id]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="View">'
                    . '<i class="fa fa-eye"></i>'
                    . '</a>'
                    . '</div>'
                    // .'<div class="col-4"><a href="' . Route('workers.show', ['id' => $model->id]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="Invite">'
                    // . '<i class="fa fa-send"></i>'
                    // . '</a>'
                    // .'</div>'
                    . '</div>';
                return $html;
            })
            // ->addColumn('view', 'datatables.view_button')
            ->rawColumns(['checkbox', 'action', 'id'])
            ->make(true);
    }
    public function create()
    {
        return view('admin::nurse.create');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required',
            'mobile' => 'nullable|max:255',
            'email' => 'email|max:255',
        ]);

        $validator->after(function ($validator) use ($request) {
            $check = User::where(['email' => $request->email])->whereNull('deleted_at')->first();
            if (!empty($check)) {
                $validator->errors()->add('email', 'Already exist.');
            }
        });
        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        } else {
            $user = auth()->guard('admin')->user();
            // $password = $this->rand_string(6);
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'user_name' => $request->email,
                // 'password' => Hash::make($password),
                'active' => $request->active,
                'role' => Role::getKey(Role::NURSE),
                // 'fcm_token' => $request->fcm_token
            ]);

            $nurse = Nurse::create([
                'user_id' => $user->id,
            ]);
            $availability = Availability::create([
                'nurse_id' => $nurse->id
            ]);
            $user->assignRole('Nurse');
            /* mail */
            $data = [
                'to_email' => $user->email,
                'to_name' => $user->first_name . ' ' . $user->last_name
            ];
            $replace_array = ['###USERNAME###' => $user->first_name . ' ' . $user->last_name];
            $this->basic_email($template = "new_registration", $data, $replace_array);

            return new JsonResponse(['success' => true, 'msg' => 'Added successfully.'], 200);
        }
    }

    public function show($id)
    {

        $data = [];
        $data['model'] = Nurse::findOrFail($id);
        return view('admin::nurse.show', $data);
    }

    public function edit($id)
    {
        $data = [];

        $data['model'] = $model = Nurse::findOrFail($id);
        // dd($data['model']);
        // $data['countries'] = $country = Countries::select('id','name')->where('flag','1')->orderBy('name','ASC')->get();
        // $data['states'] = $state = States::select('id','name')->where(['flag'=>'1', 'country_id'=>$model->country])->orderBy('name','ASC')->get();
        // $data['cities'] = Cities::select('id','name')->where(['flag'=>'1', 'state_id'=>$model->state])->orderBy('name','ASC')->get();

        $data['countries'] = Countries::where('flag', '1')
            ->orderByRaw("CASE WHEN iso3 = 'USA' THEN 1 WHEN iso3 = 'CAN' THEN 2 ELSE 3 END")
            ->orderBy('name', 'ASC')->get();
        $data['usa'] = $usa = Countries::where(['iso3' => 'USA'])->first();
        $data['us_states'] = States::where('country_id', $usa->id)->get();
        $data['us_cities'] = Cities::where('country_id', $usa->id)->get();

        $data['types'] = Keyword::where(['filter' => 'SettingType', 'active' => '1'])->get();
        $data['terms'] = Keyword::where(['filter' => 'jobType', 'active' => '1'])->get();
        $data['professions'] = Keyword::where(['filter' => 'Profession', 'active' => '1'])->get();
        $data['vaccinations'] = Keyword::where(['filter' => 'Vaccinations', 'active' => '1'])->get();
        $data['prefered_shifts'] = Keyword::where(['filter' => 'PreferredShift', 'active' => '1'])->get();
        $data['license_types'] = Keyword::where(['filter' => 'LicenseType', 'active' => '1'])->get();
        $data['msps'] = Keyword::where(['filter' => 'MSP', 'active' => '1'])->get();
        $data['vmss'] = Keyword::where(['filter' => 'VMS', 'active' => '1'])->get();
        $data['assignment_durations'] = Keyword::where(['filter' => 'AssignmentDuration', 'active' => '1'])->get();
        $data['contract_policies'] = Keyword::where(['filter' => 'ContractTerminationPolicy', 'active' => '1'])->get();
        $data['emrs'] = Keyword::where(['filter' => 'EMR', 'active' => '1'])->get();
        $data['shift_tile_of_day'] = Keyword::where(['filter' => 'shift_time_of_day', 'active' => '1'])->get();

        $data['visions'] = Keyword::where(['filter' => 'Vision', 'active' => '1'])->get();
        $data['insurances'] = Keyword::where(['filter' => 'HealthInsurance', 'active' => '1'])->get();
        $data['four_zero_1k'] = Keyword::where(['filter' => '401k', 'active' => '1'])->get();
        $data['dentals'] = Keyword::where(['filter' => 'Dental', 'active' => '1'])->get();

        $data['references'] = NurseReference::where(['nurse_id' => $id])->get();
        $data['covidVac'] = NurseAsset::where(['nurse_id' => $id, 'filter' => 'covid'])->whereNull('deleted_at')->first();
        $data['fluVac'] = NurseAsset::where(['nurse_id' => $id, 'filter' => 'flu'])->whereNull('deleted_at')->first();
        $data['palsCer'] = NurseAsset::where(['nurse_id' => $id, 'filter' => 'PALS'])->whereNull('deleted_at')->first();
        $data['blsCer'] = NurseAsset::where(['nurse_id' => $id, 'filter' => 'BLS'])->whereNull('deleted_at')->first();
        $data['aclsCer'] = NurseAsset::where(['nurse_id' => $id, 'filter' => 'ACLS'])->whereNull('deleted_at')->first();
        $data['otherCer'] = NurseAsset::where(['nurse_id' => $id, 'filter' => 'Other'])->whereNull('deleted_at')->first();
        $data['diplomaCer'] = NurseAsset::where(['nurse_id' => $id, 'filter' => 'diploma'])->whereNull('deleted_at')->first();
        $data['driving_license'] = NurseAsset::where(['nurse_id' => $id, 'filter' => 'driving_license'])->whereNull('deleted_at')->first();
        $data['skillsCer'] = NurseAsset::where(['nurse_id' => $id, 'filter' => 'skill'])->whereNull('deleted_at')->get();
        $data['facilities'] = Facility::whereNull('deleted_at')->where(['active' => '1'])->orderBy('name', 'DESC')->get();
        // dd($data['skillsCer']);

        return view('admin::nurse.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $model = Nurse::findOrFail($id);
        $inputFields = collect($request->all())->filter(function ($value) {
            return $value !== null;
        });

        $inputFields->put('updated_at', Carbon::now());
        // dd($inputFields);
        $model->fill($inputFields->all());
        $model->save();
        return new JsonResponse(['success' => true, 'msg' => 'Updated successfully.'], 200);

    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('ids')) {
                for ($i = 0; $i < count($request->ids); $i++) {
                    $nurse = Nurse::findOrFail($request->ids[$i]);
                    $user = $nurse->user;
                    $delete_date = Carbon::now();
                    $nurse->deleted_at = $delete_date;
                    $nurse->active = '0';
                    $nurse->updated_at = $delete_date;
                    $nurse->save();
                    $user->deleted_at = $delete_date;
                    $user->active = '0';
                    $user->updated_at = $delete_date;
                    $user->save();
                }
                $response = array('success' => true, 'msg' => 'Deleted Successfully!');
                // dd($request->ids);
                return $response;
            }
        }
    }

    /* Invite Workers */
    public function invite(Request $request)
    {

        if ($request->ajax()) {
            if ($request->has('ids')) {
                for ($i = 0; $i < count($request->ids); $i++) {
                    $nurse = Nurse::findOrFail($request->ids[$i]);
                    $resp = $this->sendInvite($nurse->user);
                }
                $response = array('success' => $resp['success'], 'msg' => $resp['msg']);
                return $response;
            }
        }
    }

    public function get_state(Request $request)
    {

        if ($request->ajax()) {
            if ($request->has('country_id')) {
                $states = $this->get_states($request->country_id);
                $content = '';
                if ($states->count()) {
                    foreach ($states as $s) {
                        $content .= '<option value="' . $s->id . '">' . $s->name . '</option>';
                    }
                } else {
                    $content .= '<option>No state found.</option>';
                }
                $response = array('success' => true, 'content' => $content);
                return $response;
            }
        }
    }

    public function get_city(Request $request)
    {

        if ($request->ajax()) {
            if ($request->has('state_id')) {
                $cities = $this->get_cities($request->state_id);
                $content = '';
                if ($cities->count()) {
                    foreach ($cities as $c) {
                        $content .= '<option value="' . $c->id . '">' . $c->name . '</option>';
                    }
                } else {
                    $content .= '<option>No city found.</option>';
                }
                $response = array('success' => true, 'content' => $content);
                return $response;
            }
        }
    }

    public function submit_worker_reference(Request $request, $id)
    {
        // dd($request->input());
        if ($request->ajax()) {
            if ($request->has('old_ids')) {
                $to_be_deleted = NurseReference::where(['nurse_id' => $id])->whereNotIn('id', $request->old_ids)->get();
                foreach ($to_be_deleted as $key => $m) {
                    if (!empty($m->image)) {
                        if (file_exists(public_path('images/nurses/reference/' . $m->image))) {
                            File::delete(public_path('images/nurses/reference/' . $m->image));
                        }
                    }
                    $m->delete();
                }
                foreach ($request->old_ids as $k => $v) {
                    $model = NurseReference::findOrFail($v);
                    $input = [];
                    $input['name'] = $request->old_name[$k];
                    $input['email'] = $request->old_email[$k];
                    $input['phone'] = $request->old_phone[$k];
                    $input['date_referred'] = $request->old_date_referred[$k];
                    $input['min_title_of_reference'] = $request->old_min_title_of_reference[$k];
                    $input['recency_of_reference'] = $request->old_recency_of_reference[$k];
                    if ($request->hasFile('old_image')) {
                        $old_images = $request->file('old_image');
                        if (array_key_exists($k, $old_images)) {
                            if ($old_images[$k]->isValid()) {
                                if (!empty($model->image)) {
                                    if (file_exists(public_path('images/nurses/reference/' . $model->image))) {
                                        File::delete(public_path('images/nurses/reference/' . $model->image));
                                    }
                                }
                                $reference_name = $old_images[$k]->getClientOriginalName();
                                $reference_ext = $old_images[$k]->getClientOriginalExtension();
                                $img_name = $reference_name . '_' . time() . '.' . $reference_ext;

                                $old_images[$k]->move(public_path('images/nurses/reference/'), $img_name);
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
                    $input['nurse_id'] = $id;
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
                            $img_name = $reference_name . '_' . time() . '.' . $reference_ext;
                            $images[$k]->move(public_path('images/nurses/reference/'), $img_name);
                            $input['image'] = $img_name;
                        }
                    }
                    NurseReference::create($input);
                }
            }
            // dd($request->worker_number_of_references);
            // Nurse::findOrFail($id)->update(['worker_number_of_references'=>$request->worker_number_of_references]);
            $response = array('success' => true, 'msg' => 'Updated Successfully!');
            return $response;
        }
    }

    public function vaccination_submit(Request $request, $id)
    {
        // dd($request->input());
        $worker = NURSE::findOrFail($id);
        $vaccination = [];
        if (isset($worker->worker_vaccination) && !empty($worker->worker_vaccination)) {
            $resu = json_decode($worker->worker_vaccination);
            foreach ($resu as $vac) {
                $vaccination[] = $vac;
            }
        } else {
            $vaccination[0] = "";
            $vaccination[1] = "";
        }
        // upload covid
        if ($request->covid_vac == 'Yes') {
            $asset = NurseAsset::where('nurse_id', $id)->where('filter', 'covid')->first();
            $input = [];
            $input['using_date'] = isset($request->covid_date) ? $request->covid_date : '';
            $input['nurse_id'] = $id;
            $input['filter'] = 'covid';
            if ($request->hasFile('covid')) {
                $file = $request->file('covid');
                $name = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $covid_name = $name . '_' . time() . '.' . $ext;
                $destinationPath = 'images/nurses/vaccination';
                $file->move(public_path($destinationPath), $covid_name);
                $input['name'] = $covid_name;

            }

            if (!empty($asset)) {

                if (isset($input['name'])) {
                    if (!empty($asset->name)) {
                        if (file_exists(public_path('images/nurses/vaccination/' . $asset->name))) {
                            File::delete(public_path('images/nurses/vaccination/' . $asset->name));
                        }
                    }
                }
                $asset->update($input);
            } else {
                $asset = NurseAsset::create($input);
            }
            $nurse_covid_name = !empty($asset->name) ? $asset->name : "";

        } else {
            $covid = NurseAsset::where('nurse_id', $id)->where('filter', 'covid')->first();
            if ($covid) {
                if (!empty($covid->name)) {
                    if (file_exists(public_path('images/nurses/vaccination/' . $covid->name))) {
                        File::delete(public_path('images/nurses/vaccination/' . $covid->name));
                    }
                }
                $covid->delete();
            }
            $nurse_covid_name = "";
        }

        // Upload flu
        if ($request->flu_vac == 'Yes') {
            $asset = NurseAsset::where('nurse_id', $id)->where('filter', 'flu')->first();

            $input['using_date'] = isset($request->flu_date) ? $request->flu_date : '';
            $input['filter'] = 'flu';
            $input['nurse_id'] = $id;

            if ($request->hasFile('flu')) {
                $file = $request->file('flu');
                $name = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $flu_name = $name . '_' . time() . '.' . $ext;
                $destinationPath = 'images/nurses/vaccination';
                $file->move(public_path($destinationPath), $flu_name);
                $input['name'] = $flu_name;

            }
            if (!empty($asset)) {
                if (isset($input['name'])) {
                    if (!empty($asset->name)) {
                        if (file_exists(public_path('images/nurses/vaccination/' . $asset->name))) {
                            File::delete(public_path('images/nurses/vaccination/' . $asset->name));
                        }
                    }
                }
                $asset->update($input);
            } else {
                $asset = NurseAsset::create($input);
            }
            $nurse_flu_name = !empty($asset->name) ? $asset->name : "";
        } else {
            $flu = NurseAsset::where('nurse_id', $id)->where('filter', 'flu')->first();
            if ($flu) {
                if (!empty($flu->name)) {
                    if (file_exists(public_path('images/nurses/vaccination/' . $flu->name))) {
                        File::delete(public_path('images/nurses/vaccination/' . $flu->name));
                    }
                }
                $flu->delete();
            }
            $nurse_flu_name = "";
        }

        $vaccination[0] = $nurse_covid_name;
        $vaccination[1] = $nurse_flu_name;
        $vaccination = json_encode($vaccination);

        NURSE::where(['id' => $worker->id])->update([
            'worker_vaccination' => $vaccination,
        ]);

        $response = array('success' => true, 'msg' => 'Updated Successfully!');
        return $response;

    }

    public function certification_submit(Request $request, $id)
    {
        $worker = NURSE::findOrFail($id);
        // BLS
        if ($request->bls_cer == 'Yes') {
            if ($request->hasFile('bls')) {
                NurseAsset::where('nurse_id', $id)->where('filter', 'BLS')->forceDelete();
                if (!empty($worker->BLS)) {
                    if (file_exists(public_path('images/nurses/certificate/') . $worker->BLS)) {
                        File::delete(public_path('images/nurses/certificate/') . $worker->BLS);
                    }
                }
                $file = $request->file('bls');
                $bls_name = $file->getClientOriginalName();
                $bls_ext = $file->getClientOriginalExtension();
                $bls = $bls_name . '_' . time() . '.' . $bls_ext;
                $destinationPath = 'images/nurses/certificate';
                $file->move(public_path($destinationPath), $bls);

                // write image name in worker table
                $worker->BLS = $bls;
                NurseAsset::create([
                    'nurse_id' => $id,
                    'name' => $bls,
                    'filter' => 'BLS'
                ]);
            }
        } else {
            NurseAsset::where('nurse_id', $id)->where('filter', 'BLS')->forceDelete();
            if (!empty($worker->BLS)) {
                if (file_exists(public_path('images/nurses/certificate/') . $worker->BLS)) {
                    File::delete(public_path('images/nurses/certificate/') . $worker->BLS);
                }
            }
            $worker->BLS = '';
        }

        // ACLS
        if ($request->acls_cer == 'Yes') {
            if ($request->hasFile('acls')) {
                NurseAsset::where('nurse_id', $id)->where('filter', 'ACLS')->forceDelete();
                if (!empty($worker->ACLS)) {
                    if (file_exists(public_path('images/nurses/certificate/') . $worker->ACLS)) {
                        File::delete(public_path('images/nurses/certificate/') . $worker->ACLS);
                    }
                }

                $acls_name_full = $request->file('acls')->getClientOriginalName();
                $acls_name = pathinfo($acls_name_full, PATHINFO_FILENAME);
                $acls_ext = $request->file('acls')->getClientOriginalExtension();
                $acls = $acls_name . '_' . time() . '.' . $acls_ext;
                $destinationPath = 'images/nurses/certificate';
                $request->file('acls')->move(public_path($destinationPath), $acls);

                // write image name in worker table
                $worker->ACLS = $acls;
                NurseAsset::create([
                    'nurse_id' => $id,
                    'name' => $acls,
                    'filter' => 'ACLS'
                ]);
            }
        } else {
            NurseAsset::where('nurse_id', $id)->where('filter', 'ACLS')->forceDelete();
            if (!empty($worker->ACLS)) {
                if (file_exists(public_path('images/nurses/certificate/') . $worker->ACLS)) {
                    File::delete(public_path('images/nurses/certificate/') . $worker->ACLS);
                }
            }
            $worker->ACLS = '';
        }

        // PALS
        if ($request->pals_cer == 'Yes') {
            if ($request->hasFile('pals')) {
                NurseAsset::where('nurse_id', $id)->where('filter', 'PALS')->forceDelete();
                if (!empty($worker->PALS)) {
                    if (file_exists(public_path('images/nurses/certificate/') . $worker->PALS)) {
                        File::delete(public_path('images/nurses/certificate/') . $worker->PALS);
                    }
                }

                $pals_name_full = $request->file('pals')->getClientOriginalName();
                $pals_name = pathinfo($pals_name_full, PATHINFO_FILENAME);
                $pals_ext = $request->file('pals')->getClientOriginalExtension();
                $pals = $pals_name . '_' . time() . '.' . $pals_ext;
                $destinationPath = 'images/nurses/certificate';
                $request->file('pals')->move(public_path($destinationPath), $pals);

                // write image name in worker table
                $worker->PALS = $pals;
                NurseAsset::create([
                    'nurse_id' => $id,
                    'name' => $pals,
                    'filter' => 'PALS'
                ]);
            }
        } else {
            NurseAsset::where('nurse_id', $id)->where('filter', 'PALS')->forceDelete();
            if (!empty($worker->PALS)) {
                if (file_exists(public_path('images/nurses/certificate/') . $worker->PALS)) {
                    File::delete(public_path('images/nurses/certificate/') . $worker->PALS);
                }
            }
            $worker->PALS = '';
        }

        // OTHER
        if (!empty($request->other_certificate_name)) {
            if ($request->hasFile('other')) {

                NurseAsset::where('nurse_id', $id)->where('filter', 'Other')->forceDelete();
                if (!empty($worker->other)) {
                    if (file_exists(public_path('images/nurses/certificate/') . $worker->other)) {
                        File::delete(public_path('images/nurses/certificate/') . $worker->other);
                    }
                }

                $other_name_full = $request->file('other')->getClientOriginalName();
                $other_name = pathinfo($other_name_full, PATHINFO_FILENAME);
                $other_ext = $request->file('other')->getClientOriginalExtension();
                $other = $other_name . '_' . time() . '.' . $other_ext;
                $destinationPath = 'images/nurses/certificate';
                $request->file('other')->move(public_path($destinationPath), $other);

                // write image name in worker table
                $worker->other = $other;
                $worker->other_certificate_name = isset($request->other_certificate_name) ? $request->other_certificate_name : $worker->other_certificate_name;
                NurseAsset::create([
                    'nurse_id' => $id,
                    'name' => $other,
                    'filter' => 'Other'
                ]);
            }
        } else {
            NurseAsset::where('nurse_id', $id)->where('filter', 'Other')->forceDelete();
            if (!empty($worker->other)) {
                if (file_exists(public_path('images/nurses/certificate/') . $worker->other)) {
                    File::delete(public_path('images/nurses/certificate/') . $worker->other);
                }
            }
            $worker->other = '';
        }


        NURSE::where(['id' => $worker->id])
            ->update([
                'BLS' => $worker->BLS,
                'ACLS' => $worker->ACLS,
                'PALS' => $worker->PALS,
                'other' => $worker->other,
                'other_certificate_name' => $request->other_certificate_name
            ]);
        $response = array('success' => true, 'msg' => 'Updated Successfully!');
        return $response;
    }

    public function skills_submit(Request $request, $id)
    {
        // dd($request->input());
        $worker = NURSE::findOrFail($id);
        // Upload driving license
        if ($request->dl_cer == 'Yes') {
            if ($request->hasFile('driving_license')) {

                NurseAsset::where('nurse_id', $id)->where('filter', 'driving_license')->forceDelete();
                if (!empty($worker->driving_license)) {
                    if (file_exists(public_path('images/nurses/driving_license/') . $worker->driving_license)) {
                        File::delete(public_path('images/nurses/driving_license/') . $worker->driving_license);
                    }
                }

                $driving_license_name_full = $request->file('driving_license')->getClientOriginalName();
                $driving_license_name = pathinfo($driving_license_name_full, PATHINFO_FILENAME);
                $driving_license_ext = $request->file('driving_license')->getClientOriginalExtension();
                $driving_license = $driving_license_name . '_' . time() . '.' . $driving_license_ext;
                $destinationPath = 'images/nurses/driving_license';
                $request->file('driving_license')->move(public_path($destinationPath), $driving_license);

                // write image name in worker table
                $worker->driving_license = $driving_license;
                $license_expiration_date = isset($request->license_expiration_date) ? $request->license_expiration_date : '';
                $driving_license_asset = NurseAsset::create([
                    'nurse_id' => $id,
                    'name' => $driving_license,
                    'filter' => 'driving_license',
                    'using_date' => $license_expiration_date,
                ]);
            }
        } else {
            NurseAsset::where('nurse_id', $id)->where('filter', 'driving_license')->forceDelete();
            if (!empty($worker->driving_license)) {
                if (file_exists(public_path('images/nurses/driving_license/') . $worker->driving_license)) {
                    File::delete(public_path('images/nurses/driving_license/') . $worker->driving_license);
                }
            }
            $worker->driving_license = '';
        }

        // Upload diploma
        if ($request->diploma_cer == 'Yes') {
            if ($request->hasFile('diploma')) {

                NurseAsset::where('nurse_id', $id)->where('filter', 'diploma')->forceDelete();
                if (!empty($worker->diploma)) {
                    if (file_exists(public_path('images/nurses/diploma/') . $worker->diploma)) {
                        File::delete(public_path('images/nurses/diploma/') . $worker->diploma);
                    }
                }

                $diploma_name_full = $request->file('diploma')->getClientOriginalName();
                $diploma_name = pathinfo($diploma_name_full, PATHINFO_FILENAME);
                $diploma_ext = $request->file('diploma')->getClientOriginalExtension();
                $diploma = $diploma_name . '_' . time() . '.' . $diploma_ext;
                $destinationPath = 'images/nurses/diploma';
                $request->file('diploma')->move(public_path($destinationPath), $diploma);

                // write image name in worker table
                $worker->diploma = $diploma;
                $diploma_asset = NurseAsset::create([
                    'nurse_id' => $id,
                    'name' => $diploma,
                    'filter' => 'diploma'
                ]);
            }
        } else {
            NurseAsset::where('nurse_id', $id)->where('filter', 'diploma')->forceDelete();
            if (!empty($worker->diploma)) {
                if (file_exists(public_path('images/nurses/diploma/') . $worker->diploma)) {
                    File::delete(public_path('images/nurses/diploma/') . $worker->diploma);
                }
            }
            $worker->diploma = '';
        }

        // Upload skills
        $skills_img = explode(',', $worker->skills_checklists);
        if ($request->has('old_skills_ids')) {
            $skills = NurseAsset::where('nurse_id', $id)->where('filter', 'skill')->whereNotIn('id', $request->old_skills_ids)->get();
            foreach ($skills as $img) {
                if (!empty($img->name)) {
                    if (file_exists(public_path('images/nurses/skill/') . $img->name)) {
                        File::delete(public_path('images/nurses/skill/') . $img->name);
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
                $skill = NurseAsset::findOrFail($value);
                if ($request->hasFile('old_skill')) {
                    $old_skills = $request->file('old_skill');
                    if (array_key_exists($k, $old_skills)) {
                        if ($old_skills[$k]->isValid()) {
                            if (!empty($skill->name)) {
                                if (file_exists(public_path('images/nurses/skill/' . $skill->name))) {
                                    File::delete(public_path('images/nurses/skill/' . $skill->name));
                                }
                            }
                            $name = $old_skills[$k]->getClientOriginalName();
                            $ext = $old_skills[$k]->getClientOriginalExtension();
                            $img_name = $name . '_' . time() . '.' . $ext;
                            $keyToDelete = array_search($skill->name, $skills_img);
                            if ($keyToDelete !== false) {
                                $skills_img[$keyToDelete] = $img_name;
                            }
                            $old_skills[$k]->move(public_path('images/nurses/skill/'), $img_name);
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

            foreach ($images as $k => $image) {
                $skill_name = $image->getClientOriginalName();
                $skill_ext = $image->getClientOriginalExtension();
                $skill = $skill_name . '_' . time() . '.' . $skill_ext;
                $destinationPath = 'images/nurses/skill';
                $image->move(public_path($destinationPath), $skill);
                $skills_img[] = $skill;

                // write image name in worker table
                $completion_date = isset($request->completion_date[$k]) ? $request->completion_date[$k] : '';
                $skill_asset = NurseAsset::create([
                    'nurse_id' => $id,
                    'name' => $skill,
                    'filter' => 'skill',
                    'using_date' => $completion_date
                ]);
            }

        }

        $worker->skills_checklists = implode(',', $skills_img);

        $worker->recent_experience = !empty($request->recent_experience) ? $request->recent_experience : '';
        $worker->worked_at_facility_before = !empty($request->worked_at_facility_before) ? $request->worked_at_facility_before : '';
        $worker->worker_ss_number = !empty($request->worker_ss_number) ? $request->worker_ss_number : '';
        $worker->eligible_work_in_us = !empty($request->eligible_work_in_us) ? $request->eligible_work_in_us : '';
        NURSE::where(['id' => $worker->id])->update([
            'worker_ss_number' => $worker->worker_ss_number,
            'driving_license' => $worker->driving_license,
            'recent_experience' => $worker->recent_experience,
            'worked_at_facility_before' => $worker->worked_at_facility_before,
            'eligible_work_in_us' => $worker->eligible_work_in_us,
            'skills_checklists' => $worker->skills_checklists,
            'diploma' => $worker->diploma,
        ]);
        $response = array('success' => true, 'msg' => 'Updated Successfully!');
        return $response;

    }

    public function urgency_submit(Request $request, $id)
    {
        $model = NURSE::findOrFail($id);
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
        $model = NURSE::findOrFail($id);
        $model->update([
            'float_requirement' => $request->float_requirement,
            'facility_shift_cancelation_policy' => $request->facility_shift_cancelation_policy,
            'contract_termination_policy' => $request->contract_termination_policy,
            'distance_from_your_home' => $request->distance_from_your_home,
            'facilities_you_like_to_work_at' => $request->facilities_you_like_to_work_at,
            'worker_facility_parent_system' => $request->worker_facility_parent_system,
            'avg_rating_by_facilities' => $request->avg_rating_by_facilities,
            'worker_avg_rating_by_recruiters' => $request->worker_avg_rating_by_recruiters,
            'worker_avg_rating_by_organizations' => $request->worker_avg_rating_by_organizations,
            'clinical_setting_you_prefer' => $request->clinical_setting_you_prefer
        ]);
    }

    public function patient_ratio_submit(Request $request, $id)
    {
        $model = NURSE::findOrFail($id);
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
        $model = NURSE::findOrFail($id);
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
        $model = NURSE::findOrFail($id);
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
        $model = NURSE::findOrFail($id);
        $model->update([
            'worker_overtime' => $request->worker_overtime,
            'worker_holiday' => $request->worker_holiday,
            'worker_on_call' => $request->worker_on_call,
            'worker_call_back' => $request->worker_call_back,
            'worker_orientation_rate' => $request->worker_orientation_rate,
            'worker_weekly_taxable_amount' => $request->worker_weekly_taxable_amount,
            'worker_weekly_non_taxable_amount' => $request->worker_weekly_non_taxable_amount,
            'worker_organization_weekly_amount' => $request->worker_organization_weekly_amount,
            'worker_goodwork_weekly_amount' => $request->worker_goodwork_weekly_amount,
            'worker_total_organization_amount' => $request->worker_total_organization_amount,
            'worker_total_goodwork_amount' => $request->worker_total_goodwork_amount,
            'worker_total_contract_amount' => $request->worker_total_contract_amount,
        ]);
    }

}
