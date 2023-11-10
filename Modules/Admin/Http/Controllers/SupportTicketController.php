<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Routing\Controller;
use DataTables;
use Carbon\Carbon;
use Validator;
use Str;
use App\Traits\HelperTrait;

use App\Models\{User,Keyword, SupportTicketReply, SupportTicket};

class SupportTicketController extends AdminController
{
    use HelperTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->input());
            $data  = SupportTicket::whereNull('deleted_at')->orderBy('created_at','DESC')->get();

            return DataTables::of($data)
            ->addColumn('checkbox', 'admin::ajax.checkbox')
            ->editColumn('id', function($model) {
                return '<a href="' . Route('tickets.edit', ['id' => $model->id]) . '">'.$model->id.'</a>';
            })
            ->addColumn('user_name', function($model){
                if (!empty($model->user->first_name)) {
                    return $model->user->getFullNameAttribute();
                }
                return 'N/A';
            })
            ->editColumn('created_at', function($model) {
                return Carbon::parse($model->created_at)->format('m/d/Y h:i A');
            })
            ->editColumn('description', function($model) {
                return Str::limit($model->description, 50);
            })
            ->addColumn('action', function($model){
                $html = '<div class="row">';
                $html .= '<div class="col-12"><a href="' . Route('tickets.edit', ['id' => $model->id]) . '" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="Edit">'
                . '<i class="fa fa-edit"></i>'
                . '</a>'
                .'</div>'
                .'</div>';
                return $html;
            })
            ->rawColumns(['checkbox','action','id'])
            ->toJson();
        }
        return view('admin::ticket.index');
    }

    public function create()
    {
        $data = [];
        $data['filters'] = $this->getKeywordOptions();
        return view('admin::ticket.create', $data);
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
        $data['model'] = $model = SupportTicket::findOrFail($id);
        return view('admin::ticket.edit', $data);
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
            $key = SupportTicket::findOrFail($id);
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
                    $job = SupportTicket::findOrFail($request->ids[$i]);
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
