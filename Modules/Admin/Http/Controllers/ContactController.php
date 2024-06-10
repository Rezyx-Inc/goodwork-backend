<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Admin\Emails\ContactUsReply;
use App\Models\UserMaster;
use App\Models\ContactUs;

class ContactController extends AdminController {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index() {
        $data = [];
        $data['name'] = $name = isset($_GET['name']) ? $_GET['name'] : "";
        $data['email'] = $email = isset($_GET['email']) ? $_GET['email'] : "";
        $data['phone'] = $phone = isset($_GET['phone']) ? $_GET['phone'] : "";
        $data['status'] = $status = isset($_GET['status']) ? $_GET['status'] : "";
        $data['search_filter'] = isset($_GET['search_filter']) ? $_GET['search_filter'] : "";
        $query = ContactUs::where('id', '<>', 0);
        if ($name != "") {
            $query->where('name', 'like', '%' . $name . '%');
        }
        if ($email != "") {
            $query->where('email', 'like', '%' . $email . '%');
        }
        if ($phone != "") {
            $query->where('phone_no', 'like', $phone . '%');
        }
        if ($status != "") {
            $query->where('reply_status', '=', $status);
        }
        $query->orderBy('id', 'desc');
        $model = $query->paginate(10);
        $data['model'] = $model;
        return view('admin::contact.index', $data);
    }

    public function view(Request $request, $id) {
        $data = [];
        $data['model'] = $model = ContactUs::findOrFail($id);
        if ($model) {
            return view('admin::contact.view', $data);
        } else {
            $request->session()->flash('danger', 'Oops! Something went wrong.');
            return redirect()->route('admin-contact');
        }
    }

    public function send_reply(Request $request) {
        $data = [];
        $model = ContactUs::findOrFail($request->id);
        $validator = Validator::make($request->all(), [
                    'reply' => 'required',
        ]);
        if ($validator->passes()) {
           $email_setting = $this->get_email_data('admin_reply', array('NAME' => $model->name, 'MESSAGE' => $request->reply));
           $email_data = [
               'to' => $model->email,
               'subject' => $email_setting['subject'],
               'template' => 'contact_reply',
               'data' => ['message' => $email_setting['body']]
           ];
           $this->SendMail($email_data);

            $model->reply_message = $request->input('reply');
            $model->reply_status = '1';
            $model->save();
            // Mail::to($model->email)->send(new ContactUsReply($model));
            
            $request->session()->flash('success', 'Your message Mailed to the user Sucessfully');
        }
        return redirect()->route('admin-viewcontact', ['id' => $model->id])->withErrors($validator)->withInput();
    }

}
