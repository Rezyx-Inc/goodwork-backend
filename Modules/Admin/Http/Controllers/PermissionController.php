<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Routing\Controller;
use DataTables;
use Carbon\Carbon;
use Validator;
use App\Traits\HelperTrait;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Str;
use App\Models\{User};

class PermissionController extends AdminController
{
    use HelperTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->input());
            $data  = Permission::whereNull('deleted_at')->orderBy('created_at','DESC')->get();
            // dd($data);
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', 'admin::ajax.role_checkbox')
            ->editColumn('created_at', function($model) {
                return Carbon::parse($model->created_at)->format('Y.m.d h:i A');
            })
            ->addColumn('action', function($model){
                $html = '<div class="row">';
                $html .= '<div class="col-12"><a href="' . Route('permissions.edit', ['id' => $model->name]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="Edit">'
                . '<i class="fa fa-edit"></i>'
                . '</a>'
                .'</div>'
                .'</div>';
                return $html;
            })
            ->rawColumns(['checkbox','action'])
            ->toJson();
        }
        return view('admin::permission.index');
    }

    public function create()
    {
        $data = [];
        return view('admin::permission.create', $data);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
        ]);

        $validator->after(function($validator) use ($request){
            $check = Permission::where(['name'=>$request->name])->first();
            if (!empty($check)) {
                $validator->errors()->add('name', 'Already exist.');
            }
        });

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }else{
            Permission::create(['name'=>$request->name]);
            return new JsonResponse(['success' => true, 'msg'=>'Role added successfully.'], 200);
        }
    }

    public function edit($id)
    {
        $data = [];
        $data['model'] = $model = Permission::findByName($id);
        return view('admin::permission.edit', $data);
    }

    public function update(Request $request, $id)
    {
        // dd($request->input());
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100'
        ]);

        $validator->after(function($validator) use ($request, $id){
            $check = Permission::where(['name'=>$request->name, ['name', '<>', $id]])->first();
            if (!empty($check)) {
                $validator->errors()->add('name', 'Already exist.');
            }
        });

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }else{
            $user = auth()->guard('admin')->user();
            $role = Permission::findByName($id);
            $role->name = $request->name;
            $role->updated_at= Carbon::now();
            $role->save();

            return new JsonResponse(['success' => true, 'msg'=>'Permission updated successfully.', 'link'=>route('permissions.index')], 200);
        }
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('ids')) {
                for($i=0; $i<count($request->ids); $i++){
                    $model = Permission::findByName($request->ids[$i]);
                    $model->delete();
                    // $delete_date = Carbon::now();
                    // $job->deleted_at = $delete_date;
                    // $job->updated_at = $delete_date;
                    // $job->save();
                }
                $response = array('success'=>true,'msg'=>'Deleted Successfully!');
                return $response;
            }
        }
    }
}
