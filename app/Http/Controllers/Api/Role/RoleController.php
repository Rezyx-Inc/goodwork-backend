<?php

namespace App\Http\Controllers\Api\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class RoleController extends Controller
{
    public function rolePage1(Request $request)
    {
        $messages = [
            "id.required" => "Id is required",
            "leadership_roles.required_if" => "Please select leadership role",
        ];

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'serving_preceptor' => 'boolean',
            'serving_interim_worker_leader' => 'boolean',
            'leadership_roles' => 'required_if:serving_interim_worker_leader,1',
            'clinical_educator' => 'boolean',
            'is_daisy_award_winner' => 'boolean',
            'employee_of_the_mth_qtr_yr' => 'boolean',
            'other_nursing_awards' => 'boolean',
            'is_professional_practice_council' => 'boolean',
            'is_research_publications' => 'boolean',
            'api_key' => 'required',
        ], $messages);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $return_data = [];
            $worker =  Worker::where('user_id', '=', $request->id)->first();
            $params = $request->toArray();
            $params['serving_preceptor'] =
                isset($params['serving_preceptor']) && !!$params['serving_preceptor'];
            $params['serving_interim_worker_leader'] =
                isset($params['serving_interim_worker_leader']) && !!$params['serving_interim_worker_leader'];
            $params['clinical_educator'] =
                isset($params['clinical_educator']) && !!$params['clinical_educator'];
            $params['is_daisy_award_winner'] =
                isset($params['is_daisy_award_winner']) && !!$params['is_daisy_award_winner'];
            $params['employee_of_the_mth_qtr_yr'] =
                isset($params['employee_of_the_mth_qtr_yr']) && !!$params['employee_of_the_mth_qtr_yr'];
            $params['other_nursing_awards'] =
                isset($params['other_nursing_awards']) && !!$params['other_nursing_awards'];
            $params['is_professional_practice_council'] =
                isset($params['is_professional_practice_council']) && !!$params['is_professional_practice_council'];
            $params['is_research_publications'] =
                isset($params['is_research_publications']) && !!$params['is_research_publications'];
            $worker->update($params);
            $this->check = "1";
            $this->return_data = $worker;
            $this->message = "Role and Interest Updated Successfully";
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
    public function rolePage2(Request $request)
    {
        $messages = [
            'id' => 'ID is required',
            "additional_pictures.max" => "Additional Photos can't be more than 4.",
            "additional_files.max" => "Additional Files can't be more than 4.",
            "additional_pictures.*.mimes" => "Additional Photos should be image or png jpg",
            "additional_files.*.mimes" => "Additional Files should be doc or pdf",
            "additional_pictures.*.max" => "Additional Photos should not be more than 5mb",
            "additional_files.*.max" => "Additional Files should not be more than 1mb",
            "nu_video.url" => "YouTube and Vimeo should be a valid link",
            "nu_video.max" => "YouTube and Vimeo should be a valid link"
        ];

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'additional_pictures' => 'max:4',
            'additional_pictures.*' => 'nullable|max:5120|image|mimes:jpeg,png,jpg',
            'additional_files' => 'max:4',
            'additional_files.*' => 'nullable|max:1024|mimes:pdf,doc,docx',
            'nu_video' => 'nullable|url|max:255',
            'api_key' => 'required',
        ], $messages);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $return_data = [];
            $worker =  Worker::where('user_id', '=', $request->id)->first();
            $worker->summary = $request->summary;
            $worker->save();
            if (preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->nu_video, $vresult)) {
                $youTubeID = $this->parse_youtube($request->nu_video);
                $embedURL = 'https://www.youtube.com/embed/' . $youTubeID[1];
                $worker->__set('nu_video_embed_url', $embedURL);
                $worker->update();
            } elseif (preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->nu_video, $vresult)) {
                $vimeoID = $this->parse_vimeo($request->nu_video);
                $embedURL = 'https://player.vimeo.com/video/' . $vimeoID[1];
                $worker->__set('nu_video_embed_url', $embedURL);
                $worker->update();
            }
            if ($additional_photos = $request->file('additional_pictures')) {
                foreach ($additional_photos as $additional_photo) {
                    $additional_photo_name_full = $additional_photo->getClientOriginalName();
                    $additional_photo_name = pathinfo($additional_photo_name_full, PATHINFO_FILENAME);
                    $additional_photo_ext = $additional_photo->getClientOriginalExtension();
                    $additional_photo_finalname = $additional_photo_name . '_' . time() . '.' . $additional_photo_ext;
                    //Upload Image
                    $additional_photo->storeAs('assets/workers/additional_photos/' . $worker->id, $additional_photo_finalname);
                    WorkerAsset::create([
                        'worker_id' => $worker->id,
                        'name' => $additional_photo_finalname,
                        'filter' => 'additional_photos'
                    ]);
                }
            }
            if ($additional_files = $request->file('additional_files')) {
                foreach ($additional_files as $additional_file) {
                    $additional_file_name_full = $additional_file->getClientOriginalName();
                    $additional_file_name = pathinfo($additional_file_name_full, PATHINFO_FILENAME);
                    $additional_file_ext = $additional_file->getClientOriginalExtension();
                    $additional_file_finalname = $additional_file_name . '_' . time() . '.' . $additional_file_ext;
                    //Upload Image
                    $additional_file->storeAs('assets/workers/additional_files/' . $worker->id, $additional_file_finalname);
                    WorkerAsset::create([
                        'worker_id' => $worker->id,
                        'name' => $additional_file_finalname,
                        'filter' => 'additional_files'
                    ]);
                }
            }
            $this->check = "1";
            $this->return_data = $worker;
            $this->message = "Role and Interest Updated Successfully";
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
    public function destroyRoleInterestDocument(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            "asset_id" => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker_info = WORKER::where('user_id', $request->user_id)->get();
            if ($worker_info->count() > 0) {
                $worker = $worker_info->first();
                $worker_assets = WorkerAsset::where(['id' => $request->asset_id])->get();
                if ($worker_assets->count() > 0) {
                    $workerAsset = $worker_assets->first();
                    $t = Storage::exists('assets/workers/' . $workerAsset->filter . '/' . $worker->id . '/' . $workerAsset->name);
                    if ($t && $workerAsset->name) {
                        Storage::delete('assets/workers/' . $workerAsset->filter . '/' . $worker->id . '/' . $workerAsset->name);
                    }
                    $delete = $workerAsset->delete();
                    if ($delete) {
                        $this->check = "1";
                        $this->message = "Document removed successfully";
                    } else {
                        $this->message = "Failed to remove document, Please try again later";
                    }
                } else {
                    $this->message = "Document already removed/not found";
                }
            } else {
                $this->message = "Worker not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
}
