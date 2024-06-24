<?php

namespace Modules\Admin\Http\Controllers;


use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Routing\Controller;
use DataTables;
use Carbon\Carbon;
use Validator;
use App\Traits\HelperTrait;

use App\Models\{User,States, Countries};

class StateController extends AdminController
{
    use HelperTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->input());
            $data  = States::whereNull('deleted_at')->where('country_id',$request->country)->orderBy('name','ASC')->get();

            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', 'admin::ajax.checkbox')
            // ->addColumn('user_name', function($model){
            //     if (!empty($model->user->first_name)) {
            //         return $model->user->getFullNameAttribute();
            //     }
            //     return 'N/A';
            // })
            ->editColumn('created_at', function($model) {
                return Carbon::parse($model->created_at)->format('Y.m.d h:i A');
            })
            ->addColumn('action', function($model){
                $html = '<div class="row">';
                $html .= '<div class="col-12"><a href="' . Route('states.edit', ['id' => $model->id]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="Edit">'
                . '<i class="fa fa-edit"></i>'
                . '</a>'
                .'</div>'
                .'</div>';
                return $html;
            })
            ->rawColumns(['checkbox','action'])
            ->toJson();
        }
        $data['countries'] =  Countries::where('flag','1')
        ->orderByRaw("CASE WHEN iso3 = 'USA' THEN 1 WHEN iso3 = 'CAN' THEN 2 ELSE 3 END")
        ->orderBy('name','ASC')->get();
        return view('admin::state.index', $data);
    }

    public function create()
    {
        $data = [];
        $data['countries'] = Countries::where('flag','1')
        ->orderByRaw("CASE WHEN iso3 = 'USA' THEN 1 WHEN iso3 = 'CAN' THEN 2 ELSE 3 END")
        ->orderBy('name','ASC')->get();
        return view('admin::state.create', $data);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'iso2' => 'required',
            'country_id' => 'required',
            'flag' => 'nullable',
        ]);


        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }else{
            $user = auth()->guard('admin')->user();
            $state = new States;
            $state->name = $request->name;
            $state->iso2 = $request->iso2;
            $state->country_id = $request->country_id;
            $state->flag = '1';
            if ($request->has('flag')) {
                $state->flag = $request->flag;
            }
            $state->save();

            return new JsonResponse(['success' => true, 'msg'=>'State added successfully.'], 200);
        }
    }

    public function edit($id)
    {
        $data = [];
        $data['model'] = $model = States::findOrFail($id);
        $data['countries'] =  Countries::where('flag','1')
        ->orderByRaw("CASE WHEN iso3 = 'USA' THEN 1 WHEN iso3 = 'CAN' THEN 2 ELSE 3 END")
        ->orderBy('name','ASC')->get();
        return view('admin::state.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'iso2' => 'required',
            'country_id' => 'required',
            'flag' => 'nullable',
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }else{
            $user = auth()->guard('admin')->user();
            $state = States::findOrFail($id);
            $state->name = $request->name;
            $state->iso2 = $request->iso2;
            $state->country_id = $request->country_id;
            if ($request->has('flag')) {
                $state->flag = $request->flag;
            }
            $state->save();

            return new JsonResponse(['success' => true, 'msg'=>'Updated successfully.', 'link'=>route('states.index')], 200);
        }
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('ids')) {
                for($i=0; $i<count($request->ids); $i++){
                    $job = States::findOrFail($request->ids[$i]);
                    $delete_date = Carbon::now();
                    $job->deleted_at = $delete_date;
                    $job->flag = '0';
                    $job->updated_at = $delete_date;
                    $job->save();
                }
                $response = array('success'=>true,'msg'=>'Deleted Successfully!');
                return $response;
            }
        }
    }
}
