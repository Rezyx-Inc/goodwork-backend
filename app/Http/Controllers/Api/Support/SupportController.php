<?php

namespace App\Http\Controllers\Api\Support;


//MODELS :
use App\Models\User;


use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use DB;
class SupportController extends Controller
{ 
    public function helpSupport(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
            'subject' => 'required',
            'issue' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0)
            {
                $user = $user_info->get()->first();
                $insert = array(
                    "user_id" => $request->user_id,
                    'subject' => $request->subject,
                    'issue' => $request->issue,
                    'comment_status' => "Pending for review",
                    'isPending' => "1",
                );
                
                \DB::table('help_support')->insert($insert);
                $this->check = "1";
                $this->message = "Comment submitted successfully";
                $this->return_data = "1";
            }else{
                $this->check = "1";
                $this->message = "User not found";
                $this->return_data = "0";
            }                
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getHelpComment(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0)
            {
                $user = $user_info->get()->first();
                $insert = array(
                    "user_id" => $request->user_id
                );
                
                $data = DB::table('help_support')->where($insert)->get();
                foreach($data as $val){
                    $val->created_at = isset($val->created_at) ? date('M d', strtotime($val->created_at)) : "";
                    $val->subject = isset($val->subject) ? $val->subject : "";
                    $val->issue = isset($val->issue) ? $val->issue : "";
                    $val->isPending = isset($val->isPending) ? $val->isPending : "1";
                    $val->comment_status = isset($val->comment_status) ? $val->comment_status : "";
                    $val->admin_comment = isset($val->admin_comment) ? $val->admin_comment : "";
                    $val->admin_reply_at = isset($val->admin_reply_at) ? $val->admin_reply_at : "";
                    $rec = USER::where('id', $val->user_id)->first();
                    $val->name = $rec['first_name'].' '.$rec['last_name'];
                } 
                $this->check = "1";
                $this->message = "Comment listed successfully";
                $this->return_data = $data;
            }else{
                $this->check = "1";
                $this->message = "User not found";
                $this->return_data = [];
            }                
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getHelpReplyComment(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'api_key' => 'required',
            'admin_comment' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $comment_info = DB::table('help_support')->where('id', $request->id)->get();
            if ($comment_info->count() > 0)
            {
                $update = array(
                    // 'comment_status' => isset($request->comment_status)?$request->comment_status:"",
                    'comment_status' => "Review Completed",
                    'admin_comment' => $request->admin_comment,
                    'isPending' => "0",
                    'admin_reply_at' => date('Y-m-d H:i:s')
                );
                
                \DB::table('help_support')->where('id', $request->id)->update($update);
                $this->check = "1";
                $this->message = "Admin reply submitted successfully";
                $this->return_data = "1";
            }else{
                $this->check = "1";
                $this->message = "Comment not found";
                $this->return_data = "0";
            }                
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
    public function getCommentByAdmin(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0)
            {
                $user = $user_info->get()->first();
                $insert = array(
                    "id" => $request->id
                );
                
                $val = DB::table('help_support')->where($insert)->first();
                if(isset($val)){
                    $val->created_at = isset($val->created_at) ? date('M d', strtotime($val->created_at)) : "";
                    $val->subject = isset($val->subject) ? $val->subject : "";
                    $val->issue = isset($val->issue) ? $val->issue : "";
                    $val->isPending = isset($val->isPending) ? $val->isPending : "1";
                    $val->comment_status = isset($val->comment_status) ? $val->comment_status : "";
                    $val->admin_comment = isset($val->admin_comment) ? $val->admin_comment : "";
                    $val->admin_reply_at = isset($val->admin_reply_at) ? date('M d', strtotime($val->admin_reply_at)) : "";
                    $rec = USER::where('id', $val->user_id)->first();
                    $val->name = $rec['first_name'].' '.$rec['last_name'];
                    $this->check = "1";
                    $this->message = "Admin reply Comment listed successfully";
                    $this->return_data = $val;
                } else {
                    $this->check = "1";
                    $this->message = "Comment not found";
                    $this->return_data = [];
                }
                
            }else{
                $this->check = "1";
                $this->message = "User not found";
                $this->return_data = [];
            }                
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
}
