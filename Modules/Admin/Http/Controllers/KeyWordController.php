<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Routing\Controller;
use DataTables;
use Carbon\Carbon;
use Validator;
use App\Traits\HelperTrait;

use App\Models\{User,Keyword};

class KeyWordController extends AdminController
{

    use HelperTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->input());
            $data  = Keyword::whereNull('deleted_at')->orderBy('filter','ASC')->get();

            return DataTables::of($data)
            ->addColumn('checkbox', 'admin::ajax.checkbox')
            ->addColumn('user_name', function($model){
                if (!empty($model->user->first_name)) {
                    return $model->user->getFullNameAttribute();
                }
                return 'N/A';
            })
            ->editColumn('created_at', function($model) {
                return Carbon::parse($model->created_at)->format('Y.m.d h:i A');
            })
            ->addColumn('action', function($model){
                $html = '<div class="row">';
                $html .= '<div class="col-12"><a href="' . Route('keywords.edit', ['id' => $model->id]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="Edit">'
                . '<i class="fa fa-edit"></i>'
                . '</a>'
                .'</div>'
                .'</div>';
                return $html;
            })
            ->rawColumns(['checkbox','action'])
            ->toJson();
        }
        return view('admin::keyword.index');
    }

    public function create()
    {
        $data = [];
        $data['filters'] = $this->getKeywordOptions();
        return view('admin::keyword.create', $data);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'filter' => 'required',
            'description'=> 'nullable|max:255',
            'active' => 'nullable',
        ]);


        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }else{
            $user = auth()->guard('admin')->user();
            $key = new Keyword;
            $key->title = $request->title;
            $key->filter = $request->filter;
            $key->description = $request->description;
            $key->active = '1';
            if ($request->has('active')) {
                $key->active = $request->active;
            }
            $key->created_by = $user->id;
            $key->save();

            return new JsonResponse(['success' => true, 'msg'=>'Keyword added successfully.'], 200);
        }
    }

    public function edit($id)
    {
        $data = [];
        $data['model'] = $model = keyword::findOrFail($id);
        $data['filters'] = $this->getKeywordOptions();
        return view('admin::keyword.edit', $data);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'filter' => 'required',
            'description'=> 'nullable|max:255',
            'active' => 'nullable',
        ]);


        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }else{
            $user = auth()->guard('admin')->user();
            $key = Keyword::findOrFail($id);
            $key->title = $request->title;
            $key->filter = $request->filter;
            $key->description = $request->description;
            if ($request->has('active')) {
                $key->active = $request->active;
            }
            $key->updated_at= Carbon::now();
            $key->save();

            return new JsonResponse(['success' => true, 'msg'=>'Keyword updated successfully.', 'link'=>route('keywords.index')], 200);
        }
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('ids')) {
                for($i=0; $i<count($request->ids); $i++){
                    $job = Keyword::findOrFail($request->ids[$i]);
                    $delete_date = Carbon::now();
                    $job->deleted_at = $delete_date;
                    $job->active = '0';
                    $job->updated_at = $delete_date;
                    $job->save();
                }
                $response = array('success'=>true,'msg'=>'Deleted Successfully!');
                return $response;
            }
        }
    }

    public function settings(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->input());
            $data  = Keyword::whereNull('deleted_at')->where('filter', $request->filter)->orderBy('title','ASC')->get();

            return DataTables::of($data)
            ->addColumn('checkbox', 'admin::ajax.checkbox')
            ->addColumn('user_name', function($model){
                if (!empty($model->user->first_name)) {
                    return $model->user->getFullNameAttribute();
                }
                return 'N/A';
            })
            ->editColumn('created_at', function($model) {
                return Carbon::parse($model->created_at)->format('Y.m.d h:i A');
            })
            ->addColumn('action', function($model) use ($request){
                $html = '<div class="row">';
                $html .= '<div class="col-12"><a href="' . Route('edit-setting', ['id' => $model->id]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="Edit">'
                . '<i class="fa fa-edit"></i>'
                . '</a>'
                .'</div>';
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['checkbox','action'])
            ->toJson();
        }
        $page = request()->route()->getName();
        $data = [];
        switch ($page) {
            case 'key.profession':
                $data['page_name'] = 'Professions';
                $data['filter'] = 'Profession';
                break;
            case 'key.msp':
                $data['page_name'] = 'Managed Service Providers';
                $data['filter'] = 'MSP';
                break;
            case 'key.vms':
                $data['page_name'] = 'Vendor Management Systems';
                $data['filter'] = 'VMS';
                break;
            case 'key.shift':
                $data['page_name'] = 'Shifts';
                $data['filter'] = 'shift_time_of_day';
                break;
            case 'key.clinical':
                $data['page_name'] = 'Clinical Settings';
                $data['filter'] = 'SettingType';
                break;
            default:
                break;
        }
        return view('admin::setting.index', $data);
    }

    public function create_setting()
    {
        $page = request()->route()->getName();
        $data = [];
        switch ($page) {
            case 'add-profession':
                $data['page_name'] = 'Add Profession';
                $data['filter'] = 'Profession';
                break;
            case 'add-speciality':
                $data['page_name'] = 'Add Speciality';
                $data['filter'] = 'Fliter';
                break;
            case 'add-msp':
                $data['page_name'] = 'Add Managed Service Provider';
                $data['filter'] = 'MSP';
                break;
            case 'add-vms':
                $data['page_name'] = 'Add Vendor Management Systems';
                $data['filter'] = 'VMS';
                break;
            case 'add-shift':
                $data['page_name'] = 'Add Shift';
                $data['filter'] = 'shift_time_of_day';
                break;
            case 'add-clinical':
                $data['page_name'] = 'Add Clinical Setting';
                $data['filter'] = 'SettingType';
                break;
            default:
                break;
        }
        return view('admin::setting.create', $data);
    }

    public function store_setting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'filter' => 'required',
            'description'=> 'nullable|max:255',
            'active' => 'nullable',
        ]);

        $validator->after(function($validator) use ($request){
            $check = Keyword::where(['title'=>$request->title, 'filter'=>$request->filter])->whereNull('deleted_at')->first();
            if (!empty($check)) {
                $validator->errors()->add('title', 'Already exist.');
            }
        });
        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }else{
            $user = auth()->guard('admin')->user();
            $key = new Keyword;
            $key->title = $request->title;
            $key->filter = $request->filter;
            $key->description = $request->description;
            $key->active = '1';
            if ($request->has('active')) {
                $key->active = $request->active;
            }
            $key->created_by = $user->id;
            $key->save();

            return new JsonResponse(['success' => true, 'msg'=>'Added successfully.'], 200);
        }
    }

    public function edit_setting($id)
    {
        $data = [];
        $data['model'] = $model = Keyword::findOrFail($id);
        return view('admin::setting.edit', $data);
    }

    public function update_setting(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'description'=> 'nullable|max:255',
            'active' => 'nullable',
        ]);
        $validator->after(function($validator) use ($request, $id){
            $model = Keyword::findOrFail($id);
            $check = Keyword::where(['title'=>$request->title, 'filter'=>$model->filter, ['id','<>', $id]])->whereNull('deleted_at')->first();
            if (!empty($check)) {
                $validator->errors()->add('title', 'Already exist.');
            }
        });
        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }else{
            $user = auth()->guard('admin')->user();
            $key = Keyword::findOrFail($id);
            $key->title = $request->title;
            $key->description = $request->description;
            if ($request->has('active')) {
                $key->active = $request->active;
            }
            if ($request->has('filter')) {
                $key->filter = $request->filter;
            }
            $key->updated_at= Carbon::now();
            $key->save();

            switch ($key->filter) {
                case 'Profession':
                    $link = route('key.profession');
                    break;
                case 'MSP':
                    $link = route('key.msp');
                    break;
                case 'VMS':
                    $link = route('key.vms');
                    break;
                case 'shift_time_of_day':
                    $link = route('key.shift');
                    break;
                case 'SettingType':
                    $link = route('key.clinical');
                    break;
                default:
                    $link = route('key.speciality');
                    break;
            }
            return new JsonResponse(['success' => true, 'msg'=>'Updated successfully.', 'link'=>$link], 200);
        }
    }

    public function specialities(Request $request)
    {

        if ($request->ajax()) {
            // dd($request->input());
            $data  = Keyword::whereNull('deleted_at')->where('filter', $request->filter)->orderBy('title','ASC')->get();

            return DataTables::of($data)
            ->addColumn('checkbox', 'admin::ajax.checkbox')
            ->addColumn('user_name', function($model){
                if (!empty($model->user->first_name)) {
                    return $model->user->getFullNameAttribute();
                }
                return 'N/A';
            })
            ->editColumn('created_at', function($model) {
                return Carbon::parse($model->created_at)->format('Y.m.d h:i A');
            })
            ->addColumn('action', function($model) use ($request){
                $html = '<div class="row">';
                $html .= '<div class="col-12"><a href="' . Route('edit-speciality', ['id' => $model->id]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="Edit">'
                . '<i class="fa fa-edit"></i>'
                . '</a>'
                .'</div>';
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['checkbox','action'])
            ->toJson();
        }
        $data = [];
        $data['professions'] = Keyword::where(['filter'=>'Profession', 'active'=>1])->orderBy('title','ASC')->get();
        return view('admin::setting.specialities', $data);
    }

    public function add_speciality()
    {
        $data = [];
        $data['professions'] = Keyword::where(['filter'=>'Profession', 'active'=>1])->orderBy('title','ASC')->get();
        return view('admin::setting.create_speciality', $data);
    }


    public function edit_speciality($id)
    {
        $data = [];
        $data['model'] = Keyword::findOrFail($id);
        $data['professions'] = Keyword::where(['filter'=>'Profession', 'active'=>1])->orderBy('title','ASC')->get();
        return view('admin::setting.edit_speciality', $data);
    }
}
