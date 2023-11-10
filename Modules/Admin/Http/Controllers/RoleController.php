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

class RoleController extends AdminController
{
    use HelperTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->input());
            $data  = Role::whereNull('deleted_at')->orderBy('created_at','DESC')->get();
            // dd($data);
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', 'admin::ajax.role_checkbox')
            ->editColumn('created_at', function($model) {
                return Carbon::parse($model->created_at)->format('Y.m.d h:i A');
            })
            ->addColumn('action', function($model){
                $html = '<div class="row">';
                $html .= '<div class="col-12"><a href="' . Route('roles.edit', ['id' => $model->name]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="Edit">'
                . '<i class="fa fa-edit"></i>'
                . '</a>'
                .'</div>'
                .'</div>';
                return $html;
            })
            ->rawColumns(['checkbox','action'])
            ->toJson();
        }
        return view('admin::role.index');
    }

    public function create()
    {
        $data = [];
        $data['permissions'] = Permission::whereNull('deleted_at')->orderBy('name', 'ASC')->get();
        return view('admin::role.create', $data);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
        ]);

        $validator->after(function($validator) use ($request){
            $check = Role::where(['name'=>$request->name])->first();
            if (!empty($check)) {
                $validator->errors()->add('name', 'Already exist.');
            }
        });

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }else{
            $role = Role::create(['name'=>$request->name]);
            if ($request->has('permissions')) {
                $permissions = $request->permissions;
                $role->syncPermissions($permissions);
            }
            return new JsonResponse(['success' => true, 'msg'=>'Role added successfully.'], 200);
        }
    }

    public function edit($id)
    {
        $data = [];
        $data['model'] = $model = Role::findByName($id);
        $data['permissions'] = Permission::whereNull('deleted_at')->orderBy('name', 'ASC')->get();
        $data['current_permissions'] =  $model->permissions->pluck('name')->toArray();
        return view('admin::role.edit', $data);
    }

    public function update(Request $request, $id)
    {
        // dd($request->input());
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100'
        ]);

        $validator->after(function($validator) use ($request, $id){
            $check = Role::where(['name'=>$request->name, ['name', '<>', $id]])->first();
            if (!empty($check)) {
                $validator->errors()->add('name', 'Already exist.');
            }
        });

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }else{
            $user = auth()->guard('admin')->user();
            $role = Role::findByName($id);
            $role->name = $request->name;
            $role->updated_at= Carbon::now();
            $role->save();
            if ($request->has('permissions')) {
                $permissions = $request->permissions;
                $role->syncPermissions($permissions);
            }

            return new JsonResponse(['success' => true, 'msg'=>'Role updated successfully.', 'link'=>route('roles.index')], 200);
        }
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('ids')) {
                for($i=0; $i<count($request->ids); $i++){
                    $model = Role::findByName($request->ids[$i]);
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
