<?php

namespace Modules\Recruiter\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Services\CustomMailer;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;

class CommonFunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('recruiter::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('recruiter::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('recruiter::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('recruiter::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function sendMail($data) {
        $view = view('mail-templates.template', ['content'=>$data['body']])->render();

        $data['content'] = $view;
        (new CustomMailer)->sendSmtpMail($data);
    }

    public function getEmailData($slug, $replacedata = array()) {
        $email_data = EmailTemplate::where(['slug' => $slug])->first();
        $email_msg = "";
        $email_array = array();
        $email_msg = $email_data->content;
        $subject = $email_data->label;
        if (!empty($replacedata)) {
            foreach ($replacedata as $key => $value) {
                $email_msg = str_replace("###" . $key . "###", $value, $email_msg);
            }
        }
        return array('body' => $email_msg, 'subject' => $subject);
    }
    public function rand_number($digits) {
        $alphanum = "123456789" . time();
        $rand = substr(str_shuffle($alphanum), 0, $digits);
        return $rand;
    }
    
}
