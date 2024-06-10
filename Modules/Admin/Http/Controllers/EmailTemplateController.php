<?php

namespace Modules\Admin\Http\Controllers;


use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Routing\Controller;
use DataTables;
use Carbon\Carbon;
use Validator;
use App\Traits\HelperTrait;

use App\Models\{User,EmailTemplate};

class EmailTemplateController extends AdminController
{
    use HelperTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->input());
            $data  = EmailTemplate::whereNull('deleted_at')->orderBy('label','ASC')->get();

            return DataTables::of($data)
            ->addColumn('checkbox', 'admin::ajax.checkbox')
            ->addIndexColumn()
            ->editColumn('created_at', function($model) {
                return Carbon::parse($model->created_at)->format('Y.m.d h:i A');
            })
            ->addColumn('action', function($model){
                $html = '<div class="row">';
                $html .= '<div class="col-12"><a href="' . Route('email-templates.edit', ['id' => $model->id]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="Edit">'
                . '<i class="fa fa-edit"></i>'
                . '</a>'
                .'</div>'
                .'</div>';
                return $html;
            })
            ->rawColumns(['checkbox','action'])
            ->toJson();
        }
        return view('admin::email_template.index');
    }

    public function create()
    {
        $data = [];
        return view('admin::email_template.create', $data);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'required|max:100',
            'slug' => 'required|unique:email_templates',
            'content'=> 'nullable',
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }else{
            $user = auth()->guard('admin')->user();
            $email = new EmailTemplate;
            $email->label = $request->label;
            $email->slug = $request->slug;
            $email->content = $request->content;
            $email->status = '1';
            if ($request->has('status')) {
                $email->status = $request->status;
            }
            $email->save();

            return new JsonResponse(['success' => true, 'msg'=>'Email added successfully.'], 200);
        }
    }

    public function edit($id)
    {
        $data = [];
        $data['model'] = $model = EmailTemplate::findOrFail($id);
        return view('admin::email_template.edit', $data);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'label' => 'required|max:100',
            'slug' => 'required',
            'content'=> 'nullable',
            'status' => 'nullable',
        ]);

        $validator->after(function($validator) use ($request, $id){
            $model = EmailTemplate::findOrFail($id);
            $check = EmailTemplate::where(['slug'=>$request->slug, ['id','<>', $id]])->whereNull('deleted_at')->first();
            if (!empty($check)) {
                $validator->errors()->add('slug', 'Already exist.');
            }
        });

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }else{
            $user = auth()->guard('admin')->user();
            $email = EmailTemplate::findOrFail($id);
            $email->label = $request->label;
            $email->slug = $request->slug;
            $email->content = $request->content;
            if ($request->has('status')) {
                $email->status = $request->status;
            }
            $email->save();

            return new JsonResponse(['success' => true, 'msg'=>'Email updated successfully.', 'link'=>route('email-templates.index')], 200);
        }
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('ids')) {
                for($i=0; $i<count($request->ids); $i++){
                    $job = EmailTemplate::findOrFail($request->ids[$i]);
                    $delete_date = Carbon::now();
                    $job->deleted_at = $delete_date;
                    $job->status = '0';
                    $job->updated_at = $delete_date;
                    $job->save();
                }
                $response = array('success'=>true,'msg'=>'Deleted Successfully!');
                return $response;
            }
        }
    }
}
