<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Routing\Controller;
use DataTables;
use Carbon\Carbon;
use Validator;
use Hash;
use App\Traits\HelperTrait;

use App\Models\{User,Facility};

class UserController extends AdminController {

    use HelperTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->input());
            $data  = User::whereNull('deleted_at')->where(['role'=>'ADMIN'])->orderBy('created_at','DESC')->get();

            return DataTables::of($data)
            ->addColumn('checkbox', 'admin::ajax.checkbox')
            ->addIndexColumn()
            ->addColumn('name', function($model) {
                if ( !empty($model->first_name) ) {

                    return $model->getFullNameAttribute();
                }else{
                    return 'N/A';
                }
            })
            ->editColumn('facility_id', function($model) {
                if (!empty($model->facility->name)) {
                    return $model->facility->name;
                }
                return 'N/A';
            })
            ->editColumn('created_at', function($model) {
                return Carbon::parse($model->created_at)->format('m/d/Y h:i A');
            })
            ->editColumn('id', function($model) {
                return '<a href="' . Route('admins.edit', ['id' => $model->id]) . '">'.$model->id.'</a>';
            })
            ->addColumn('action', function($model){
                $html = '<div class="row">';
                $html .= '<div class="col-12"><a href="' . Route('admins.edit', ['id' => $model->id]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="Edit">'
                . '<i class="fa fa-edit"></i>'
                . '</a>'
                .'</div>'
                .'</div>';
                return $html;
            })
            ->rawColumns(['checkbox','action','id'])
            ->toJson();
        }
        return view('admin::admin.index');
    }

    public function create()
    {
        $data = [];
        return view('admin::admin.create', $data);
    }


    public function store(Request $request)
    {
        // dd($request->input());
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'mobile' => 'required|max:15|min:10',
            'password'=>'required|min:6',
            'confirm_password'=>'required_with:password|same:password',
            'email'=> 'email|max:255',
        ]);

        $validator->after(function($validator) use ($request){
            $check = User::where(['email'=>$request->email])->whereNull('deleted_at')->first();
            if (!empty($check)) {
                $validator->errors()->add('email', 'Already exist.');
            }
        });

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }else{
            $user = new User;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->password = Hash::make($request->input('password'));
            $user->active = '1';
            if ($request->has('active')) {
                $user->active = $request->active;
            }
            $user->role = 'ADMIN';
            $user->assignRole('ADMIN');
            $user->save();

            return new JsonResponse(['success' => true, 'msg'=>'Admin added successfully.'], 200);
        }
    }

    public function edit($id)
    {
        $data = [];
        $data['model'] = $model = User::findOrFail($id);
        return view('admin::admin.edit', $data);
    }

    public function update(Request $request, $id)
    {


        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'mobile' => 'required|max:15|min:10',
            'email'=> 'email|max:255',
        ]);

        $validator->after(function($validator) use ($request, $id){
            $model = User::findOrFail($id);
            $check = User::where(['email'=>$request->email,['id','<>', $id]])->whereNull('deleted_at')->first();
            if (!empty($check)) {
                $validator->errors()->add('email', 'Already exist.');
            }
        });

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }else{
            $key = User::findOrFail($id);
            $key->first_name = $request->first_name;
            $key->last_name = $request->last_name;
            $key->email = $request->email;
            $key->mobile = $request->mobile;
            if ($request->has('active')) {
                $key->active = $request->active;
            }
            $key->save();

            return new JsonResponse(['success' => true, 'msg'=>'Admin updated successfully.', 'link'=>route('admins.index')], 200);
        }
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('ids')) {
                for($i=0; $i<count($request->ids); $i++){
                    $job = User::findOrFail($request->ids[$i]);
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
}
