<?php

namespace App\Http\Controllers\Api\UserProfile;
use Illuminate\Http\Request;

//Enums
use App\Enums\State;

//MODELS :
use App\Models\User;
use App\Models\Worker;
use App\Models\Availability;
use App\Models\Experience;
use App\Models\EmailTemplate;


use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
class UserProfileController extends Controller
{
    public function personalDetail(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $worker = Worker::where('user_id', $request->id)->first();
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            /* 'image' => 'nullable|max:1024|image|mimes:jpeg,png,jpg', */
            'first_name' => 'required|regex:/^[a-zA-Z]+$/|min:3|max:100',
            'last_name' => 'required|regex:/^[a-zA-Z]+$/|min:3|max:100',
            // 'mobile' => 'required|regex:/^[0-9 \+]+$/|min:4|max:20',
            'mobile' => 'required|min:4|max:20',
            'email' => $this->emailRegEx($user),
            'nursing_license_state' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:50',
            'nursing_license_number' => 'nullable|regex:/^[a-zA-Z0-9]+$/|min:2|max:50',
            'specialty' => 'required',
            'address' => 'required|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'city' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:50',
            'state' => 'required',
            'postcode' => 'required|regex:/^[a-zA-Z0-9]+$/|min:3|max:10',
            'country' => 'required|regex:/^[a-zA-Z ]+$/|min:3',
            'api_key' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $return_data = [];
            if ($user) {
                /* User */
                if (isset($request->first_name) && $request->first_name != "") $user->first_name = $request->first_name;
                if (isset($request->last_name) && $request->last_name != "") $user->last_name = $request->last_name;
                if (isset($request->email) && $request->email != "") $user->email = $request->email;
                if (isset($request->mobile) && $request->mobile != "") $user->mobile = $request->mobile;
                if ($request->hasFile('profile_image') && $request->file('profile_image') != null) {
                    // $request->file('profile_image')->storeAs('assets/workers/profile', $worker->id);
                    $profile_image_name_full = $request->file('image')->getClientOriginalName();
                    $profile_image_name = pathinfo($profile_image_name_full, PATHINFO_FILENAME);
                    $profile_image_ext = $request->file('image')->getClientOriginalExtension();
                    $profile_image = $profile_image_name.'_'.time().'.'.$profile_image_ext;

                    $destinationPath = 'images/workers/profile';
                    $request->file('image')->move(public_path($destinationPath), $profile_image);

                    $user->image = $profile_image;
                }
                $u = $user->update();
                /* User */

                /*  Worker */
                if (isset($request->specialty) && $request->specialty != "") $worker->specialty = $request->specialty;
                if (isset($request->address) && $request->address != "") $worker->address = $request->address;
                if (isset($request->city) && $request->city != "") $worker->city = $request->city;
                if (isset($request->state) && $request->state != "") $worker->state = $request->state;
                if (isset($request->postcode) && $request->postcode != "") $worker->postcode = $request->postcode;
                if (isset($request->country) && $request->country != "") $worker->country = $request->country;
                if (isset($request->nursing_license_number) && $request->nursing_license_number != "") $worker->nursing_license_number = $request->nursing_license_number;
                if (isset($request->search_status) && $request->search_status != "") $worker->search_status = $request->search_status;
                if (isset($request->license_type) && $request->license_type != "") $worker->license_type = $request->license_type;
                $n = $worker->update();
                /*  Worker */

                if ($u || $n) {
                    $this->check = "1";
                    $return_data = $this->profileCompletionFlagStatus($type = "", $user);
                    $this->message = "Personal detail updated successfully";
                } else {
                    $this->message = "Problem occurred while updating the profile detail, Please try again later";
                }
            } else {
                $this->message = "User not exists";
            }
            $this->return_data = $return_data;
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function availability(Request $request)
    {
        $messages = [
            "id.required" => "Id is required",
            "shift_duration.required" => "Select shift duration",
            "assignment_duration.required" => "Select assignment duration",
            "preferred_shift.required" => "Select preferred shift",
            // "days_of_the_week.required" => "Select preferred days of the week",
            "earliest_start_date.date" => "Earliest start date is not valid date",
            "work_location.required" => "Select work location",
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'hourly_pay_rate' => 'required|regex:/^[0-9]+$/|min:1|max:3',
            'shift_duration' => 'required',
            'assignment_duration' => 'required',
            'preferred_shift' => 'required',
            // 'days_of_the_week' => 'required',
            'earliest_start_date' => 'nullable|date|after_or_equal:now',
            'work_location' => 'required',
            'api_key' => 'required',
        ], $messages);

        $user_data = User::where('id', '=', $request->id)->first();
        if ($user_data != null) {
            if ($validator->fails()) {
                $this->message = $validator->errors()->first();
            } else {
                /* worker */
                $worker = Worker::where('user_id', '=', $request->id)->get()->first();
                if (isset($request->hourly_pay_rate) && $request->hourly_pay_rate != "") {
                    $tmpRate =  $request->hourly_pay_rate * 25 / 100;
                    $facility_hourly_pay_rate = $request->hourly_pay_rate + $tmpRate;
                    $worker->__set('facility_hourly_pay_rate', $facility_hourly_pay_rate);
                }
                $worker->hourly_pay_rate = $request->hourly_pay_rate;
                $n = $worker->update();
                /* worker */

                /* availability */
                $availability = Availability::where('worker_id', '=', $worker->id)->get()->first();
                if (isset($request->shift_duration) && $request->shift_duration != "") $availability->shift_duration = $request->shift_duration;
                if (isset($request->preferred_shift) && $request->preferred_shift != "") $availability->preferred_shift = $request->preferred_shift;
                if (isset($request->days_of_the_week) && $request->days_of_the_week != "") $availability->days_of_the_week = $request->days_of_the_week;
                if (isset($request->assignment_duration) && $request->assignment_duration != "") $availability->assignment_duration = $request->assignment_duration;
                if (isset($request->earliest_start_date) && $request->earliest_start_date != "") $availability->earliest_start_date = $request->earliest_start_date;
                if (isset($request->work_location) && $request->work_location != "") $availability->work_location = $request->work_location;
                if (isset($request->unavailable_dates) && $request->unavailable_dates != "") $availability->unavailable_dates = $request->unavailable_dates;

                // if (isset($request->unavailable_dates)) {
                //     $availability->unavailable_dates = explode(',', $request->unavailable_dates);
                // }else{
                //     $availability->unavailable_dates = array();
                // }

                $a = $availability->update();
                /* availability */
                // Hourly Rate & Availability Updated

                if ($a || $n) {
                    $this->check = "1";
                    $this->message = "Hourly rate & availability updated successfully";
                    $this->return_data = $this->profileCompletionFlagStatus($type = "", $user_data);
                } else {
                    $this->message = "Problem occurred while updating the profile detail, Please try again later";
                }
            }
        } else {
            $this->message = "User not exists";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
    public function getAvailability(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'worker_id' => 'required',
            'month' => 'required',
            'year' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $availability = Availability::where('worker_id', '=', $request->worker_id)->get()->first();

            $unavailable_dates = explode(',',$availability->unavailable_dates);

            $unavailable_datesArr['unavailable_dates'] = array();
            foreach($unavailable_dates as $row){
                $month = date('m',strtotime($row));
                $year = date('Y',strtotime($row));

                if($request->month == $month && $request->year == $year){
                    array_push($unavailable_datesArr['unavailable_dates'], $row);
                }
            }

            if ($unavailable_datesArr) {
                // $user = $user_info->first();
                $this->check = "1";
                $this->message = "Unavailable dates listed successfully";
                $this->return_data = $unavailable_datesArr;
            } else {
                $this->message = "Data not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);



    }

    public function shiftDuration()
    {
        $shifts = $this->getShifts()->pluck('title', 'id');
        $this->check = "1";
        $this->message = "Shift duration has been listed successfully";
        $data = [];
        foreach ($shifts as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        asort($data);
        $data1 = [];
        foreach ($data as $key1 => $value1) {
            $data1[] = ['id' => $value1['id'], "name" => $value1['name']];
        }

        $this->return_data = $data1;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function assignmentDurations()
    {
        $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
        // $assignmentDurations = Keyword::where('filter', 'AssignmentDuration')->get()->pluck('title', 'id');
        $data = [];
        foreach ($assignmentDurations as $key => $value) {
            // $name = explode(" ",$value);
            $data[] = ['id' => $key, "name" => $value];
            // $data[] = ['id' => $key, "name" => $name[0]];
        }
        $this->check = "1";
        $this->message = "Assignment duration's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function preferredShifts()
    {
        $preferredShifts = $this->getPreferredShift()->pluck('title', 'id');
        $data = [];
        foreach ($preferredShifts as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Preferred shift's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getWeekDay()
    {
        $weekDays = $this->getWeekDayOptions();
        $data = [];
        foreach ($weekDays as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Week day's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function stateList()
    {
        /* $this->return_data = $this->getStateOptions(); */
        $ret = [];
        foreach (State::getKeys() as $key => $value) {
            $ret[]['state'] = $value;
        }

        $this->check = "1";
        $this->message = "State's has been listed successfully";
        $this->return_data = $ret;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function Experience(Request $request)
    {
        $messages = [
            "highest_nursing_degree.required" => "Select highest nursing degree",
            "ehr_proficiency_cerner.required" => "Select Cerner",
            "ehr_proficiency_meditech.required" => "Select Meditech",
            "ehr_proficiency_epic.required" => "Select Epic",
            "ehr_proficiency_other.regex" => "Other ehr proficiency not valid",
            "college_uni_name.required" => "Please add college / university name",
            "college_uni_name.regex" => "College / university name not valid",
            "college_uni_name.min" => "College / university name is short",
            "college_uni_name.max" => "College / university name too long",
            "college_uni_city.required" => "Please add city",
            "college_uni_city.regex" => "City not valid",
            "college_uni_city.min" => "City is short",
            "college_uni_city.max" => "City is too long",
            "college_uni_country.required" => "Please add country",
            "college_uni_country.regex" => "Country not valid",
            "college_uni_country.min" => "Country is short",
            "college_uni_country.max" => "Country is too long",
            "experience_as_acute_care_facility.regex" => "Enter valid acute care facility experience",
            "experience_as_ambulatory_care_facility.regex" => "Enter valid non-acute care nursing experience",
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'highest_nursing_degree' => 'required',
            'college_uni_name' => 'required|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:255',
            'college_uni_city' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:50',
            'college_uni_state' => 'required',
            'college_uni_country' => 'required|regex:/^[a-zA-Z ]+$/|min:3',
            'experience_as_acute_care_facility' => 'nullable|regex:/^[0-9.\+]+$/|max:5',
            'experience_as_ambulatory_care_facility' => 'nullable|regex:/^[0-9.\+]+$/|max:5',
            'ehr_proficiency_cerner' => 'required',
            'ehr_proficiency_meditech' => 'required',
            'ehr_proficiency_epic' => 'required',
            'ehr_proficiency_other' => 'nullable|regex:/^[a-zA-Z 0-9]+$/|min:2|max:50',
            'api_key' => 'required',
        ], $messages);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', '=', $request->id);
            if ($user_info->count() > 0) {
                $user = $user_info->first();
                $worker_info = Worker::where('user_id', '=', $request->id);
                if ($worker_info->count() > 0) {
                    $worker = $worker_info->first();

                    $update_data = [
                        'highest_nursing_degree' => $request->highest_nursing_degree,
                        'college_uni_name' => $request->college_uni_name,
                        'college_uni_city' => $request->college_uni_city,
                        'college_uni_state' => $request->college_uni_state,
                        'college_uni_country' => $request->college_uni_country,
                        'experience_as_acute_care_facility' => $request->experience_as_acute_care_facility,
                        'experience_as_ambulatory_care_facility' => $request->experience_as_ambulatory_care_facility,
                        'ehr_proficiency_cerner' => $request->ehr_proficiency_cerner,
                        'ehr_proficiency_meditech' => $request->ehr_proficiency_meditech,
                        'ehr_proficiency_epic' => $request->ehr_proficiency_epic,
                        'ehr_proficiency_other' =>  $request->ehr_proficiency_other,
                    ];
                    $update = WORKER::where(['id' => $worker->id])->update($update_data);
                    if ($update) {
                        $this->check = "1";
                        $this->message = "Experience updated successfully";
                        $this->return_data = $this->profileCompletionFlagStatus($type = "", $user);
                        // $this->return_data = $experience;
                    } else {
                        $this->message = "Failed to update the experience, Please try again later";
                    }
                } else {
                    $this->message = "Worker not found";
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function workerExperience(Request $request)
    {
        $certifications = $this->getCertifications()->pluck('title', 'id');
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'worker_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            $worker = WORKER::where('id', $request->worker_id)->get()->first();
            $user = USER::where('id', $worker->user_id)->get()->first();
            if(isset($worker))
            {
                $experience = [];
                $exp = Experience::where(['worker_id' => $request->worker_id])->whereNull('deleted_at')->get();
                if ($exp->count() > 0) {
                    $e = $exp;
                    foreach ($e as $key => $v) {
                        $crt_data['experience_id'] = (isset($v->id) && $v->id != "") ? $v->id : "";
                        $crt_data['organization_name'] = (isset($v->organization_name) && $v->organization_name != "") ? $v->organization_name : "";
                        $crt_data['organization_department_name'] = (isset($v->organization_department_name) && $v->organization_department_name != "") ? $v->organization_department_name : "";
                        $crt_data['position_title'] = (isset($v->position_title) && $v->position_title != "") ? $v->position_title : "";
                        $crt_data['exp_city'] = (isset($v->exp_city) && $v->exp_city != "") ? $v->exp_city : "";
                        $crt_data['facility_type'] = (isset($v->facility_type) && $v->facility_type != "") ? $v->facility_type : "";
                        $crt_data['type'] = (isset($v->type) && $v->type != "") ? $v->type : "";

                        $crt_data['type_definition'] = (isset($certifications[$v->type]) && $certifications[$v->type] != "") ? $certifications[$v->type] : "";
                        $crt_data['position_title'] = (isset($v->position_title) && $v->position_title != "") ? $v->position_title : "";
                        $crt_data['unit'] = (isset($v->unit) && $v->unit != "") ? $v->unit : "";
                        $crt_data['start_date'] = (isset($v->start_date) && $v->start_date != "") ? date('m/d/Y', strtotime($v->start_date)) : "";
                        $crt_data['end_date'] = (isset($v->end_date) && $v->end_date != "") ? date('m/d/Y', strtotime($v->end_date)) : "";
                        $crt_data['is_current_job'] = (isset($v->is_current_job) && $v->is_current_job != "") ? $v->is_current_job : "";

                        $experience[] = $crt_data;

                    }
                }

                $this->check = "1";
                $this->message = "Worker exprience details listed successfully";
                $this->return_data = $experience;
            }else{
                $this->check = "1";
                $this->message = "Worker not found";
                $this->return_data = [];
            }

        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function facilityTypes()
    {
        $facilityTypes = $this->getFacilityType()->pluck('title', 'id');
        $data = [];
        foreach ($facilityTypes as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "facility type's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function workerExperienceSelectionOptions(Request $request)
    {
        $messages = [
            "id.required" => "Id is required"
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'api_key' => 'required',
        ], $messages);


        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $worker = Worker::where('user_id', '=', $request->id)->first();
            if ($worker != null) {
                $nuexperience = $this->workerExperienceSelection($worker);
                $data = [];
                foreach ($nuexperience as $key => $value) {
                    $data[] = ['id' => $key, "name" => $value];
                }
                $this->check = "1";
                $this->message = "facility type's has been listed successfully";
                $this->return_data = $nuexperience;
            } else {
                $this->message = "Worker not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
     // send reset link acccording to the role
     public function sendResetLinkEmail(Request $request)
     {
         $validator = \Validator::make($request->all(), [
             'email' => 'required|email',
             'api_key' => 'required',
         ]);

         if ($validator->fails()) {
             $this->message = $validator->errors()->first();
         } else {
             // We will send the password reset link to this user. Once we have attempted
             // to send the link, we will examine the response then see the message we
             // need to show to the user. Finally, we'll send out a proper response.

             $check_user = User::where(['email' => $request->email]);
             if ($check_user->count() > 0) {
                 $user = $check_user->first();

                 $temp = EmailTemplate::where(['slug' => 'worker_reset_password']);
                 if ($temp->count() > 0) {
                     $t = $temp->first();
                     $data = [
                         'to_email' => $user->email,
                         'to_name' => $user->first_name . ' ' . $user->last_name
                     ];
                     $token = $this->generate_token();
                     $replace_array = ['###RESETLINK###' => url('password/reset', $token)];
                     $this->basic_email($template = "worker_reset_password", $data, $replace_array);
                 }
                 $this->check = "1";
                 $this->message = "Reset password link sent successfully";
             } else {
                 $this->message = "User not found";
             }
         }

         return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
     }

     public function newPhoneNumber(Request $request)
     {
         $validator = \Validator::make($request->all(), [
             'user_id' => 'required',
             'phone_number' => 'required|regex:/^[0-9 \+]+$/|min:4|max:20',
             'api_key' => 'required',
         ]);

         if ($validator->fails()) {
             $this->message = $validator->errors()->first();
         } else {
             $user_info = USER::where('id', $request->user_id);
             if ($user_info->count() > 0) {
                 $user = $user_info->get()->first();
                 $otp = substr(str_shuffle("0123456789"), 0, 4);
                 $rand_enc = substr(str_shuffle("0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz"), 0, 6);
                 $update_otp = USER::where(['id' => $user->id])->update(['otp' => $otp, 'new_mobile' => $request->phone_number]);
                 if ($update_otp) {
                     $this->check = "1";
                     $this->message = "OTP send successfully to this number";
                     $this->return_data = ['otp' => $otp];
                 } else {
                     $this->message = "Failed to send otp, Please try again later";
                 }
             } else {
                 $this->message = "User not found";
             }
         }

         return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
     }

     public function settings(Request $request)
     {
         $validator = \Validator::make($request->all(), [
             'user_id' => 'required',
             'api_key' => 'required',
         ]);

         if ($validator->fails()) {
             $this->message = $validator->errors()->first();
         } else {
             $user_info = USER::where('id', $request->user_id);
             $response = [];
             if ($user_info->count() > 0) {
                 $user = $user_info->get()->first();
                 $worker_info = WORKER::where('user_id', $user->id);
                 if ($worker_info->count() > 0) {
                     $worker = $worker_info->get()->first();
                     $response["first_name"] = (isset($user->first_name) && $user->first_name != "") ? $user->first_name : "";
                     $response["last_name"] = (isset($user->last_name) && $user->last_name != "") ? $user->last_name : "";
                     $response["full_name"] = $user->first_name . " " . $user->last_name;
                     // $response["profile_picture"] = url('storage/assets/workers/profile/' . $worker->user->image);
                     $response['profile_picture'] = url('public/images/workers/profile/'.$user->image);

                     // $profileWorker = \Illuminate\Support\Facades\Storage::get('assets/workers/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
                     // if ($worker->user->image) {
                     //     $t = \Illuminate\Support\Facades\Storage::exists('assets/workers/profile/' . $worker->user->image);
                     //     if ($t) {
                     //         $profileWorker = \Illuminate\Support\Facades\Storage::get('assets/workers/profile/' . $worker->user->image);
                     //     }
                     // }
                     // $response["profile_picture_base"] = 'data:image/jpeg;base64,' . base64_encode($profileWorker);

                     $response["address"] = (isset($worker->address) && $worker->address != "") ? $worker->address : "";
                     $response["city"] = (isset($worker->city) && $worker->city != "") ? $worker->city : "";
                     $response["state"] = (isset($worker->state) && $worker->state != "") ? $worker->state : "";
                     $response["postcode"] = (isset($worker->postcode) && $worker->postcode != "") ? $worker->postcode : "";
                     $response["country"] = (isset($worker->country) && $worker->country != "") ? $worker->country : "";
                     $response["nursing_license_number"] = (isset($worker->nursing_license_number) && $worker->nursing_license_number != "") ? $worker->nursing_license_number : "";
                     $response["bil_rate"] = (isset($worker->hourly_pay_rate) && $worker->hourly_pay_rate != "") ? $worker->hourly_pay_rate : "5";
                     $exp = (isset($worker->experience_as_acute_care_facility) && $worker->experience_as_acute_care_facility != "") ? $worker->experience_as_acute_care_facility : "0";
                     $non_exp = (isset($worker->experience_as_ambulatory_care_facility) && $worker->experience_as_ambulatory_care_facility != "") ? $worker->experience_as_ambulatory_care_facility : "0";
                     $response["experience"] = strval($exp + $non_exp);
                     /* availability */
                     $availability = Availability::where('worker_id', $worker->id);
                     $response["shift"] = $response["shift_definition"] = "";
                     if ($availability->count() > 0) {
                         $preferredShifts = $this->getPreferredShift()->pluck('title', 'id');
                         $avail = $availability->get()->first();
                         $response["shift"] = (isset($avail->preferred_shift) && $avail->preferred_shift != "") ? strval($avail->preferred_shift) : "";
                         $response["shift_definition"] = (isset($preferredShifts[$avail->preferred_shift]) && $preferredShifts[$avail->preferred_shift] != "") ? $preferredShifts[$avail->preferred_shift] : "";
                     }
                     /* availability */
                     $this->check = "1";
                     $this->message = "Worker info listed successfully";
                 } else {
                     $this->message = "Worker not found";
                 }
                 $this->return_data =  $response;
             } else {
                 $this->message = "User not found";
             }
         }

         return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
     }

     public function WorkerProfileInfo(Request $request)
     {
         if(isset($request->role) && $request->role == 'worker'){
             $validator = \Validator::make($request->all(), [
                 'user_id' => 'required',
                 'api_key' => 'required',
                 'worker_id' => 'required',
             ]);
         }else{
             $validator = \Validator::make($request->all(), [
                 'user_id' => 'required',
                 'api_key' => 'required',
             ]);
         }

         if ($validator->fails()) {
             $this->message = $validator->errors()->first();
         } else {
             if(!empty($request->role) && isset($request->user_id))
             {
                 $worker = WORKER::where('id', $request->worker_id)->get()->first();
                 $user_info = USER::where('id', $request->user_id);
             }else{
                 $user_info = USER::where('id', $request->user_id);
                 $worker = WORKER::where('user_id', $request->user_id)->get()->first();
             }

             if ($user_info->count() > 0) {
                 $user = $user_info->get()->first();
                 $this->check = "1";
                 $this->message = "User profile details listed successfully";
                 $type = $worker->id;
                 $this->return_data = $this->workerProfileCompletionFlagStatus($type, $user);
             } else {
                 $this->message = "Worker not found";
             }
         }

         return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
     }
}
