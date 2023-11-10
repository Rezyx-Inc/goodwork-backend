<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Mail;
use App\Models\{Email, EmailTemplate};
define('USER_IMG', asset('public/backend/assets/images/picture.jpg'));

class AdminController extends Controller {

    public function rand_string($digits) {
        $alphanum = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz" . time();
        $rand = substr(str_shuffle($alphanum), 0, $digits);
        return $rand;
    }

    public function rand_number($digits) {
        $alphanum = "123456789" . time();
        $rand = substr(str_shuffle($alphanum), 0, $digits);
        return $rand;
    }

    public function SendMail($data) {
        $template = view('mail.layouts.template')->render();
        $content = view('mail.' . $data['template'], $data['data'])->render();
        $view = str_replace('[[email_message]]', $content, $template);
        $data['content'] = $view;
//           print_r($data);
//           exit();

//        $headers = "MIME-Version: 1.0" . "\r\n";
//        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
//        $headers .= 'From: admin@laravel.com' . "\r\n" .
//                'Reply-To: no-reply@laravel.com' . "\r\n" .
//                'X-Mailer: PHP/' . phpversion();
//        $va = str_replace('[[email_message]]', $content, $template);
//        return mail($data['to'], $data['subject'], $va, $headers);
        Mail::send([], [], function ($message) use ($data) {
            $message->from('admin@sandtires.com', env('PROJECT_NAME', 'Demo'));
            $message->replyTo('no-reply@sandtires.com', env('PROJECT_NAME', 'Demo'));
            $message->subject($data['subject']);
            $message->setBody($data['content'], 'text/html');
            $message->to($data['to']);
        });
    }

    public function get_email_data($slug, $replacedata = array()) {
        $email_data = Email::where(['slug' => $slug])->first();
        $email_msg = "";
        $email_array = array();
        $email_msg = $email_data->body;
        $subject = $email_data->subject;
        if (!empty($replacedata)) {
            foreach ($replacedata as $key => $value) {
                $email_msg = str_replace("{{" . $key . "}}", $value, $email_msg);
            }
        }
        return array('body' => $email_msg, 'subject' => $subject);
    }

    public function basic_email($template = null, $data = [], $replace_array = [])
    {
        $body_content = $arr['subject'] = "";
        $temp = EmailTemplate::where(['status' => '1', 'slug' => $template]);
        if ($temp->count() > 0) {
            $t = $temp->first();
            $arr['subject'] = $t->label;
         //   if ($t->slug == "new_registration") { $arr['cc'] = "rama@nurseify.app"; }
            $body_content = strtr($t->content, $replace_array);
        }

        $arr['to_email'] = (isset($data['to_email']) && $data['to_email'] != "") ? $data['to_email'] : "";
        $arr['to_name'] = (isset($data['to_name']) && $data['to_name'] != "") ? $data['to_name'] : "";

        Mail::send(['html' => 'mail-templates.template'], array("content" => $body_content), function ($message) use ($arr) {
        //   if (isset($arr['cc']) && $arr['cc'] != "") {
        //       $message->to($arr['to_email'], $arr['to_name'])->cc($arr['cc'])->subject($arr['subject']);
        //   } else {
        //       $message->to($arr['to_email'], $arr['to_name'])->subject($arr['subject']);
        //   }
            $message->to($arr['to_email'], $arr['to_name'])->subject($arr['subject']);
            $message->from('noreply@nurseify.app', 'Team Nurseify');
        });
        // echo "Basic Email Sent. Check your inbox.";
    }

}
