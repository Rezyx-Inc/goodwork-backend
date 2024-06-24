<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use App\Models\Email;

class EmailController extends AdminController {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index() {
        $data = [];
        $data['about'] = $about = isset($_GET['about']) ? $_GET['about'] : "";
        $data['subject'] = $subject = isset($_GET['subject']) ? $_GET['subject'] : "";
        $data['search_filter'] = isset($_GET['search_filter']) ? $_GET['search_filter'] : "";
        $query = Email::where('about', 'like', '%' . $about . '%');
        if ($about != "") {
            $query->where('about', 'like', '%' . $about . '%');
        }
        if ($subject != "") {
            $query->where('subject', 'like', '%' . $subject . '%');
        }
        $model = $query->paginate(10);
        $data['model'] = $model;
        return view('admin::email.index', $data);
    }

    public function view($id) {
        $data = [];
        $data['model'] = Email::findOrFail($id);
        return view('admin::email.view', $data);
    }

    public function get_update($id) {
        $data = [];
        $data['model'] = Email::findOrFail($id);
        return view('admin::email.update', $data);
    }

    public function post_update(Request $request, $id) {
        $data = [];
        $model = Email::findOrFail($id);
        $validator = Validator::make($request->all(), [
                    'about' => 'required|max:255',
                    'subject' => 'required|max:255',
                    'body' => 'required',
        ]);
        if ($validator->passes()) {
            $model->about = $request->input('about');
            $model->subject = $request->input('subject');
            $model->body = $request->input('body');
            $model->updated_at = date('Y-m-d H:i:s');
            $model->save();
            $request->session()->flash('success', 'Content updated successfully.');
        }
        return redirect()->route('admin-updateemail', ['id' => $id])->withErrors($validator)->withInput();
    }

}
