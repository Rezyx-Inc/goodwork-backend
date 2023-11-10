<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Faq;

class FaqController extends AdminController {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index() {
        $data = [];
        $data['question'] = $question = isset($_GET['question']) ? $_GET['question'] : "";
        $data['status'] = $status = isset($_GET['status']) ? $_GET['status'] : "";
        $data['search_filter'] = isset($_GET['search_filter']) ? $_GET['search_filter'] : "";
        $query = Faq::where('status', '<>', '3');
        if ($question != "") {
            $query->where('question', 'like', '%' . $question . '%');
        }
        if ($status != "") {
            if ($status == 'inactive') {
                $query->where('status', '=', '0');
            } else if ($status == 'active') {
                $query->where('status', '=', '1');
            } else {
                
            }
        }
        $query->orderBy('id', 'DESC');
        $model = $query->paginate(10);
        $data['model'] = $model;
        return view('admin::faq.index', $data);
    }

    public function get_create() {
        $data = [];
        return view('admin::faq.create', $data);
    }

    public function post_create(Request $request) {
        $validator = Validator::make($request->all(), [
                    'question' => 'required',
                    'answer' => 'required',
        ]);
        if ($validator->passes()) {
            $model = new Faq;
            $model->question = $request->input('question');
            $model->answer = $request->input('answer');
            $model->created_at = date('Y-m-d H:i:s');
            $model->save();
            return redirect()->route('admin-faqs')->with('success', 'Faq created successfully.');
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }

    public function view(Request $request, $id) {
        $data = [];
        $data['model'] = $model = Faq::findOrFail($id);
        if (!empty($model) && $model->status != '3') {
            return view('admin::faq.view', $data);
        } else {
            $request->session()->flash('danger', 'Oops. Something went wrong.');
            return redirect()->route('admin-users');
        }
    }

    public function get_update(Request $request, $id) {
        $data = [];
        $data['model'] = $model = Faq::findOrFail($id);
        if (!empty($model) && $model->status != '3') {
            return view('admin::faq.update', $data);
        } else {
            $request->session()->flash('danger', 'Oops. Something went wrong.');
            return redirect()->route('admin-faqs');
        }
    }

    public function post_update(Request $request, $id) {
        $data = [];
        $model = Faq::findOrFail($id);
        $validator = Validator::make($request->all(), [
                    'question' => 'required',
                    'answer' => 'required',
                    'status' => 'required',
        ]);
        if ($validator->passes()) {
            $model->question = $request->input('question');
            $model->answer = $request->input('answer');
            $model->status = $request->input('status');
            $model->updated_at = date('Y-m-d H:i:s');
            $model->save();
            $request->session()->flash('success', 'Details updated successfully.');
        }
        return redirect()->route('admin-updatefaq', ['id' => $id])->withErrors($validator)->withInput();
    }

    public function delete(Request $request) {
        if (isset($_GET['id']) && $_GET['id'] != "") {
            $model = Faq::findOrFail($_GET['id']);
            if (!empty($model) && $model->status != '3') {
                $model->status = '3';
                $model->save();
                $request->session()->flash('success', 'Faq deleted successfully.');
            } else {
                $request->session()->flash('danger', 'Oops. Something went wrong.');
            }
        } else {
            $request->session()->flash('danger', 'Oops. Something went wrong.');
        }
        return redirect()->route('admin-faqs');
    }

}
