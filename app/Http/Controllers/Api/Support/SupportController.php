<?php

/**
 * This file contains the implementation of the SupportController class, which is responsible for handling API requests related to Support.
 */

namespace App\Http\Controllers\Api\Support;


//MODELS :
use App\Models\User;


use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

/**
 * The SupportController class handles the retrieval and submission of support-related data, such as comments and help.
 */
class SupportController extends Controller
{
    protected $request;

    /**
     * Constructor method for the DetailsController class.
     * @param Request $request The HTTP request object.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handles the submission of help and support requests.
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the success or failure of the comment submission.
     */
    public function helpSupport()
    {
        $validator = \Validator::make($this->request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
            'subject' => 'required',
            'issue' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $this->request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $insert = array(
                    "user_id" => $this->request->user_id,
                    'subject' => $this->request->subject,
                    'issue' => $this->request->issue,
                    'comment_status' => "Pending for review",
                    'isPending' => "1",
                );

                \DB::table('help_support')->insert($insert);
                $this->check = "1";
                $this->message = "Comment submitted successfully";
                $this->return_data = "1";
            } else {
                $this->check = "1";
                $this->message = "User not found";
                $this->return_data = "0";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /**
     * Handles the retrieval of help and support comments for a specific user.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the list of comments or an error message.
     */
    public function getHelpComment()
    {
        $validator = \Validator::make($this->request->all(), [
            'user_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $this->request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $insert = array(
                    "user_id" => $this->request->user_id
                );

                $data = DB::table('help_support')->where($insert)->get();
                foreach ($data as $val) {
                    $val->created_at = isset($val->created_at) ? date('M d', strtotime($val->created_at)) : "";
                    $val->subject = isset($val->subject) ? $val->subject : "";
                    $val->issue = isset($val->issue) ? $val->issue : "";
                    $val->isPending = isset($val->isPending) ? $val->isPending : "1";
                    $val->comment_status = isset($val->comment_status) ? $val->comment_status : "";
                    $val->admin_comment = isset($val->admin_comment) ? $val->admin_comment : "";
                    $val->admin_reply_at = isset($val->admin_reply_at) ? $val->admin_reply_at : "";
                    $rec = USER::where('id', $val->user_id)->first();
                    $val->name = $rec['first_name'] . ' ' . $rec['last_name'];
                }
                $this->check = "1";
                $this->message = "Comment listed successfully";
                $this->return_data = $data;
            } else {
                $this->check = "1";
                $this->message = "User not found";
                $this->return_data = [];
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /**
     * Handles the submission of admin replies to help and support comments.
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the success or failure of the admin reply submission.
     */
    public function getHelpReplyComment()
    {
        $validator = \Validator::make($this->request->all(), [
            'id' => 'required',
            'api_key' => 'required',
            'admin_comment' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $comment_info = DB::table('help_support')->where('id', $this->request->id)->get();
            if ($comment_info->count() > 0) {
                $update = array(
                    // 'comment_status' => isset($request->comment_status)?$request->comment_status:"",
                    'comment_status' => "Review Completed",
                    'admin_comment' => $this->request->admin_comment,
                    'isPending' => "0",
                    'admin_reply_at' => date('Y-m-d H:i:s')
                );

                \DB::table('help_support')->where('id', $this->request->id)->update($update);
                $this->check = "1";
                $this->message = "Admin reply submitted successfully";
                $this->return_data = "1";
            } else {
                $this->check = "1";
                $this->message = "Comment not found";
                $this->return_data = "0";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /**
     * Handles the retrieval of a specific help and support comment along with admin replies.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the comment details or an error message.
     */
    public function getCommentByAdmin()
    {
        $validator = \Validator::make($this->request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $this->request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $insert = array(
                    "id" => $this->request->id
                );

                $val = DB::table('help_support')->where($insert)->first();
                if (isset($val)) {
                    $val->created_at = isset($val->created_at) ? date('M d', strtotime($val->created_at)) : "";
                    $val->subject = isset($val->subject) ? $val->subject : "";
                    $val->issue = isset($val->issue) ? $val->issue : "";
                    $val->isPending = isset($val->isPending) ? $val->isPending : "1";
                    $val->comment_status = isset($val->comment_status) ? $val->comment_status : "";
                    $val->admin_comment = isset($val->admin_comment) ? $val->admin_comment : "";
                    $val->admin_reply_at = isset($val->admin_reply_at) ? date('M d', strtotime($val->admin_reply_at)) : "";
                    $rec = USER::where('id', $val->user_id)->first();
                    $val->name = $rec['first_name'] . ' ' . $rec['last_name'];
                    $this->check = "1";
                    $this->message = "Admin reply Comment listed successfully";
                    $this->return_data = $val;
                } else {
                    $this->check = "1";
                    $this->message = "Comment not found";
                    $this->return_data = [];
                }

            } else {
                $this->check = "1";
                $this->message = "User not found";
                $this->return_data = [];
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
}
