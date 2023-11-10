<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use App\Models\Cms;
use Intervention\Image\ImageManagerStatic as Image;

class CmsController extends AdminController {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index() {
        $data = [];
        $data['page_name'] = $page_name = isset($_GET['page_name']) ? $_GET['page_name'] : "";
        $data['content_name'] = $content_name = isset($_GET['content_name']) ? $_GET['content_name'] : "";
        $data['search_filter'] = isset($_GET['search_filter']) ? $_GET['search_filter'] : "";
        $query = Cms::where('slug', 'like', '%');
        if ($page_name != "") {
            $query->where('page_name', 'like', '%' . $page_name . '%');
        }
        if ($content_name != "") {
            $query->where('content_name', 'like', '%' . $content_name . '%');
        }
        $model = $query->paginate(10);
        $data['model'] = $model;
        return view('admin::cms.index', $data);
    }

    public function view($id) {
        $data = [];
        $data['model'] = Cms::findOrFail($id);
        return view('admin::cms.view', $data);
    }

    public function get_update($id) {
        $data = [];
        $data['model'] = Cms::findOrFail($id);
        return view('admin::cms.update', $data);
    }

    public function post_update(Request $request, $id) {
        $data = [];
        $model = Cms::findOrFail($id);
        if ($model->type == '2') {
            $validator = Validator::make($request->all(), [
                        'content_body' => 'mimes:jpg,jpeg,png|max:15360',
            ]);
        } else if ($model->type == '3') {
            $validator = Validator::make($request->all(), [
                        'content_body' => 'mimetypes:video/mp4|max:15360',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                        'content_body' => 'required',
            ]);
        }
        $validator->after(function($validator)use ($request) {
            if ($request->input('page_name') != "" && strlen($request->input('page_name')) > 100)
                $validator->errors()->add('page_name', 'The page name may not be greater than 100 characters.');
            else if ($request->input('page_name') == "")
                $validator->errors()->add('page_name', 'The page name field is required.');
            if ($request->input('content_name') != "" && strlen($request->input('content_name')) > 100)
                $validator->errors()->add('content_name', 'The content name may not be greater than 100 characters.');
            else if ($request->input('content_name') == "")
                $validator->errors()->add('content_name', 'The content name field is required.');
        });
        if ($validator->passes()) {
            $model->page_name = $request->input('page_name');
            $model->content_name = $request->input('content_name');
            if ($model->type == '2') {
                if ($request->file('content_body')) {
                    $img_name = $this->rand_string(20) . '.' . $request->file('content_body')->getClientOriginalExtension();
                    $file = $request->file('content_body');
                    $file->move(public_path('uploads/admin/cms/pictures/original/'), $img_name);
                    Image::make(public_path('uploads/admin/cms/pictures/original/') . $img_name)->resize(1220, 500)->save(public_path('uploads/admin/cms/pictures/preview/') . $img_name);
                    $model->content_body = $img_name;
                }
            } else if ($model->type == '3') {
                if ($request->file('content_body')) {
                    $vdo_name = $this->rand_string(20) . '.' . $request->file('content_body')->getClientOriginalExtension();
                    $file = $request->file('content_body');
                    $file->move(public_path('uploads/admin/cms/videos/'), $vdo_name);
                    $model->content_body = $vdo_name;
                }
            } else {
                $model->content_body = $request->input('content_body');
            }
            $model->updated_at = date('Y-m-d H:i:s');
            $model->save();
            $request->session()->flash('success', 'Content updated successfully.');
        }
        return redirect()->route('admin-updatecms', ['id' => $id])->withErrors($validator)->withInput();
    }

}
