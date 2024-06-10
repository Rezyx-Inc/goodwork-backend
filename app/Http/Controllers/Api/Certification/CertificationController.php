<?php

namespace App\Http\Controllers\Api\Certification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

//MODELS :
use App\Models\User;
use App\Models\Certification;
use App\Models\Nurse;
use App\Http\Controllers\Controller;
class CertificationController extends Controller
{
    public function addCredentials(Request $request)
    {
        $messages = [
            "user_id.required" => "user_id is required",
            "type.required" => "Select Certification type",
            "type.exists" => "Selected Certification type does not exist",
            "effective_date.required" => "Enter Issue Date",
            "effective_date.date" => "Issue Date is not valid",
            "expiration_date.required" => "Enter Expiration Date",
            "expiration_date.date" => "Expiration Date is not valid",
            "expiration_date.after" => "Expiration Date should be after Isuue Date.",
            "certificate_image.max" => "Allowed File Size is 5 MB",
            "certificate_image.mimes" => "Allowed File Types are jpeg, png, jpg, pdf",
            "resume.mimes" => "Allowed File Types are doc, docx, pdf, txt",
            "resume.max" => "Allowed File Size is 2 MB",
        ];

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required|numeric|exists:keywords,id',
            'effective_date' => 'required|date',
            'expiration_date' => "required|date|after:effective_date",
            'renewal_date' => "date",
            'certificate_image' => 'nullable|max:5120|mimes:jpeg,png,jpg,pdf',
            'resume' => 'mimes:doc,docx,pdf,txt|max:2048',
            'api_key' => 'required',
        ], $messages);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            if(isset($request->role)){
                $nurse_info = Nurse::where('id', '=', $request->nurse_id);
            }else{
                $nurse_info = Nurse::where('user_id', '=', $request->user_id);
            }

            if ($nurse_info->count() > 0) {
                $nurse = $nurse_info->first();

                /* certification */
                $add_array = [
                    'nurse_id' => $nurse->id,
                    'type' => $request->type,
                    'effective_date' => $request->effective_date,
                    'expiration_date' => $request->expiration_date,
                    "organization" => $request->organization,
                    "renewal_date" => $request->renewal_date,
                    'license_number' => $request->license_number
                ];
                $check = Certification::where('nurse_id', '=', $nurse->id)->get()->first();
                if(isset($check)){
                    $certification = Certification::where('nurse_id', '=', $nurse->id)->update($add_array);
                    // DB::table('user')->where('email', $userEmail)->update(array('member_type' => $plan));
                }else{
                    $certification = Certification::create($add_array);
                }

                if ($request->hasFile('certificate_image')) {
                    $certificate_image_name_full = $request->file('certificate_image')->getClientOriginalName();
                    $certificate_image_name = pathinfo($certificate_image_name_full, PATHINFO_FILENAME);
                    $certificate_image_ext = $request->file('certificate_image')->getClientOriginalExtension();
                    $certificate_image = $certificate_image_name . '_' . time() . '.' . $certificate_image_ext;
                    $certification_array["certificate_image"] = $certificate_image;
                    $certification_img_update = Certification::where(['id' => $certification->id])->update($certification_array);
                    //Upload Image
                    $request->file('certificate_image')->storeAs('assets/nurses/certifications/' . $nurse->id, $certificate_image);
                }
                /* certification */

                if ($certification == true) {
                    $cert_ret = Certification::where('id', '=', isset($certification->id)?$certification->id:$check->id)->first();

                    /* certificate data */
                    $certifications = $this->getCertifications()->pluck('title', 'id');
                    $cert_data["id"] = (isset($cert_ret->id) && $cert_ret->id != "") ? $cert_ret->id : "";
                    $cert_data["nurse_id"] = (isset($cert_ret->nurse_id) && $cert_ret->nurse_id != "") ? $cert_ret->nurse_id : "";
                    $cert_data["type"] = (isset($cert_ret->type) && $cert_ret->type != "") ? $cert_ret->type : "";
                    $cert_data["type_definition"] = (isset($certifications[$cert_ret->type]) && $certifications[$cert_ret->type] != "") ? $certifications[$cert_ret->type] : "";
                    $cert_data["license_number"] = (isset($cert_ret->license_number) && $cert_ret->license_number != "") ? $cert_ret->license_number : "";
                    $cert_data["effective_date"] = (isset($cert_ret->effective_date) && $cert_ret->effective_date != "") ?  date('m/d/Y', strtotime($cert_ret->effective_date)) : "";
                    $cert_data["expiration_date"] = (isset($cert_ret->expiration_date) && $cert_ret->expiration_date != "") ?  date('m/d/Y', strtotime($cert_ret->expiration_date)) : "";
                    $cert_data["renewal_date"] = (isset($cert_ret->renewal_date) && $cert_ret->renewal_date != "") ?  date('m/d/Y', strtotime($cert_ret->renewal_date)) : "";
                    $cert_data["certificate_image"] = (isset($cert_ret->certificate_image) && $cert_ret->certificate_image != "") ? url('storage/assets/nurses/certifications/' . $nurse->id . '/' . $cert_ret->certificate_image) : "";
                    $cert_data["organization"] = (isset($cert_ret->organization) && $cert_ret->organization != "") ? $cert_ret->organization : "";
                    // $cert_data["deleted_at"] = (isset($cert_ret->deleted_at) && $cert_ret->deleted_at != "") ? date('m/d/Y H:i:s', strtotime($cert_ret->deleted_at)) : "";
                    $cert_data["created_at"] = (isset($cert_ret->created_at) && $cert_ret->created_at != "") ?  date('m/d/Y H:i:s', strtotime($cert_ret->created_at)) : "";
                    // $cert_data["updated_at"] = (isset($cert_ret->updated_at) && $cert_ret->updated_at != "") ?  date('m/d/Y H:i:s', strtotime($cert_ret->updated_at)) : "";
                    /* certificate data */

                    /* nurse data */
                    $nurse_return = Nurse::select('resume')->where('user_id', '=', $request->id)->first();
                    $cert_data["resume"] = (isset($nurse_return->resume) && $nurse_return->resume != "") ? url('storage/assets/nurses/resumes/' . $nurse->id . '/' . $nurse_return->resume) : "";
                    /* nurse data */

                    $this->check = "1";
                    $this->message = "Certification added successfully";
                    $this->return_data = $cert_data;
                } else {
                    $this->message = "Problem occurred while updating certification, Please try again later";
                }
            } else {
                $this->message = "Nurse not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
    public function editCredentials(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'certificate_id' => 'required',
            'type' => 'required|numeric|exists:keywords,id',
            'effective_date' => 'required|date',
            'expiration_date' => "required|date|after:effective_date",
            'renewal_date' => "date",
            'certificate_image' => 'nullable|max:5120|mimes:jpeg,png,jpg,pdf',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->first();
                $nurse_info = NURSE::where('user_id', $request->user_id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->first();
                    $certificate_array = [
                        "type" => $request->type,
                        "effective_date" => $request->effective_date,
                        "expiration_date" => $request->expiration_date,
                        "organization" => $request->organization,
                        "renewal_date" => $request->renewal_date,
                        'license_number' => $request->license_number
                    ];
                    if ($request->hasFile('certificate_image')) {
                        $certificate_image_name_full = $request->file('certificate_image')->getClientOriginalName();
                        $certificate_image_name = pathinfo($certificate_image_name_full, PATHINFO_FILENAME);
                        $certificate_image_ext = $request->file('certificate_image')->getClientOriginalExtension();
                        $certificate_image = $certificate_image_name . '_' . time() . '.' . $certificate_image_ext;
                        $certificate_array["certificate_image"] = $certificate_image;
                        //Upload Image
                        $request->file('certificate_image')->storeAs('assets/nurses/certifications/' . $nurse->id, $certificate_image);
                    }
                    $certification = Certification::where(['id' => $request->certificate_id])->update($certificate_array);
                    if ($certification == true) {
                        $this->check = "1";
                        $this->message = "Certificate updated successfully";
                        $this->return_data = $this->profileCompletionFlagStatus($type = "", $user);
                    } else {
                        $this->message = "Failed to update the certificate. Please try again later";
                    }
                } else {
                    $this->message = "Nurse not found";
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function removeCredentialDoc(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'certificate_id' => 'required',
            // 'certificate_image' => 'required'
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->first();
                $nurse_info = NURSE::where('user_id', $request->user_id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->first();

                    $certificate = Certification::where(['id' => $request->certificate_id])->whereNull('deleted_at')->get();
                    if ($certificate->count() > 0) {
                        $cert = $certificate->first();

                        $del = Storage::delete('assets/nurses/certifications/' . $nurse->id . '/' . $cert->certificate_image);
                        $remove = Certification::where(['id' => $request->certificate_id])->update(['deleted_at' => date('m/d/Y H:i:s')]);
                        if ($del && $remove) {
                            $this->check = "1";
                            $this->message = "Certificate removed successfully";
                        } else {
                            $this->message = "Failed to remove or certificate removed already. Please try again later";
                        }
                    } else {
                        $this->message = "Certificate already removed";
                    }

                    /* $file = explode("/", $request->certificate_image); //file_exists();
                    if (isset($file) && is_array($file) && !empty($file)) {
                        $t = Storage::exists('assets/nurses/certifications/' . $nurse->id . '/' . end($file));
                        if ($t) {
                        } else {
                            $this->message = "Certificate already removed";
                        }
                    } */
                }else{
                    $this->check = "1";
                    $this->message = "Nurse not exist";
                }
            }else{
                $this->check = "1";
                $this->message = "User not exist";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
}
