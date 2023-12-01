<?php

namespace App\Http\Controllers\Api\Facility;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;


// Models

use App\Models\Nurse;
use App\Models\Job;
use App\Models\User;
use App\Models\Follows;
use App\Models\Keyword;
use App\Models\Experience;

//FACILITY
use App\Models\FacilityFollows;
use App\Models\Facility;
use App\Models\FacilityRating;
use App\Models\States;
use App\Models\Cities;
use App\Models\JobAsset;
use App\Models\JobOffer;
use App\Models\NurseRating;
use App\Models\EmailTemplate;


use DB;

class FacilityController extends Controller
{
    
    public function facilityDropdown($type)
    {
        if ($type == "getmedicalrecords") {
            $eMedicalRecords = $this->getEMedicalRecords()->pluck('title', 'id');
            $eMedicalRecords['0'] = 'Other';
            $data = [];
            foreach ($eMedicalRecords as $key => $value) {
                $data[] = ["id" => $key, "name" => $value];
            }
            $this->return_data = $data;
        } elseif ($type == "getbcheckprovider") {
            $bCheckProviders = $this->getBCheckProvider()->pluck('title', 'id');
            $bCheckProviders['0'] = 'Other';
            $data = [];
            foreach ($bCheckProviders as $key => $value) {
                $data[] = ["id" => $key, "name" => $value];
            }
            $this->return_data = $data;
        } elseif ($type == "getncredentialingsoftware") {
            $nCredentialingSoftwares = $this->getNCredentialingSoftware()->pluck('title', 'id');
            $nCredentialingSoftwares['0'] = 'Other';
            $data = [];
            foreach ($nCredentialingSoftwares as $key => $value) {
                $data[] = ["id" => $key, "name" => $value];
            }
            $this->return_data = $data;
        } elseif ($type == "getnschedulingsystem") {
            $nSchedulingSystems = $this->getNSchedulingSystem()->pluck('title', 'id');
            $nSchedulingSystems['0'] = 'Other';
            $data = [];
            foreach ($nSchedulingSystems as $key => $value) {
                $data[] = ["id" => $key, "name" => $value];
            }
            $this->return_data = $data;
        } elseif ($type == "gettraumadesignation") {
            $traumaDesignations = $this->getTraumaDesignation()->pluck('title', 'id');
            $traumaDesignations['0'] = 'N/A';
            $data = [];
            foreach ($traumaDesignations as $key => $value) {
                $data[] = ["id" => $key, "name" => $value];
            }
            $this->return_data = $data;
        } elseif ($type == 'gettimeattendancesystem') {
            $timeAttendanceSystems = $this->getTimeAttendanceSystem()->pluck('title', 'id');
            $timeAttendanceSystems['0'] = 'Other';
            $data = [];
            foreach ($timeAttendanceSystems as $key => $value) {
                $data[] = ["id" => $key, "name" => $value];
            }
            $this->return_data = $data;
        }

        $this->check = "1";
        $this->message = "Dropdown options listed successfully";
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function facilityDetail(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'facility_id' => 'required',
            'name' => 'required|min:10|max:255',
            'type' => 'required',
            'facility_email' => 'nullable|email|max:255',
            'facility_phone' => 'required|min:10|max:15',
            'address' => 'required|min:5|max:190',
            'city' => 'required|min:3|max:20',
            'state' => 'required',
            'postcode' => 'required|min:4|max:6',
            'facility_logo' => 'nullable|max:1024|image|mimes:jpeg,png,jpg,gif',
            'cno_image' => 'nullable|max:1024|image|mimes:jpeg,png,jpg,gif',
            'video_link' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'pinterest' => 'nullable|url|max:255',
            'tiktok' => 'nullable|url|max:255',
            'sanpchat' => 'max:255',
            'youtube' => 'nullable|url|max:255',
            'facility_website' => 'nullable|url|max:255',
            'f_emr' => 'required',
            'f_emr_other' => 'nullable|required_if:f_emr,0|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'f_bcheck_provider' => 'required',
            'f_bcheck_provider_other' => 'nullable|required_if:f_bcheck_provider,0|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'nurse_cred_soft' => 'required',
            'nurse_cred_soft_other' => 'nullable|required_if:nurse_cred_soft,0|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'nurse_scheduling_sys' => 'required',
            'nurse_scheduling_sys_other' => 'nullable|required_if:nurse_scheduling_sys,0|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'time_attend_sys' => 'required',
            'time_attend_sys_other' => 'nullable|required_if:time_attend_sys,0|regex:/^[a-zA-Z 0-9,\-\/]+$/|min:1|max:150',
            'licensed_beds' => 'nullable|regex:/^[0-9]+$/|min:1|max:20',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $update_array = [];

            $embedURL = "";
            if (preg_match('/https?:\/\/(?:[\w]+\.)*youtube\.com\/watch\?v=[^&]+/', $request->video_link)) {
                $youTubeID = $this->parse_youtube($request->video_link);
                $embedURL = 'https://www.youtube.com/embed/' . $youTubeID[1];
            } elseif (preg_match('/https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*+/', $request->video_link)) {
                $vimeoID = $this->parse_vimeo($request->video_link);
                $embedURL = 'https://player.vimeo.com/video/' . $vimeoID[1];
            }
            if ($embedURL != "") $update_array['video_embed_url'] = $embedURL;

            /* required */
            $update_array['name'] = $request->name;
            $update_array['type'] = $request->type;
            $update_array['facility_phone'] = $request->facility_phone;
            $update_array['address'] = $request->address;
            $update_array['city'] = $request->city;
            $update_array['state'] = $request->state;
            $update_array['postcode'] = $request->postcode;
            $update_array['f_emr'] = $request->f_emr;
            $update_array['f_bcheck_provider'] = $request->f_bcheck_provider;
            $update_array['nurse_cred_soft'] = $request->nurse_cred_soft;
            $update_array['nurse_scheduling_sys'] = $request->nurse_scheduling_sys;
            $update_array['time_attend_sys'] = $request->time_attend_sys;
            /* required */

            $facility_id = "";
            if (isset($request->facility_id) && $request->facility_id != "") $facility_id = $request->facility_id;
            if (isset($request->facility_email) && $request->facility_email != "") $update_array["facility_email"] = $request->facility_email;
            if (isset($request->facebook) && $request->facebook != "") $update_array["facebook"] = $request->facebook;
            if (isset($request->twitter) && $request->twitter != "") $update_array["twitter"] = $request->twitter;
            if (isset($request->linkedin) && $request->linkedin != "") $update_array["linkedin"] = $request->linkedin;
            if (isset($request->instagram) && $request->instagram != "") $update_array["instagram"] = $request->instagram;
            if (isset($request->pinterest) && $request->pinterest != "") $update_array["pinterest"] = $request->pinterest;
            if (isset($request->tiktok) && $request->tiktok != "") $update_array["tiktok"] = $request->tiktok;
            if (isset($request->sanpchat) && $request->sanpchat != "") $update_array["sanpchat"] = $request->sanpchat;
            if (isset($request->youtube) && $request->youtube != "") $update_array["youtube"] = $request->youtube;
            if (isset($request->facility_website) && $request->facility_website != "") $update_array["facility_website"] = $request->facility_website;
            if (isset($request->trauma) && $request->trauma != "") $update_array["trauma_designation"] = $request->trauma;
            if (isset($request->senior_leader_message) && $request->senior_leader_message != "") $update_array["cno_message"] = $request->senior_leader_message;
            if (isset($request->about_facility) && $request->about_facility != "") $update_array["about_facility"] = $request->about_facility;

            if (isset($request->f_emr) && $request->f_emr != "") $update_array["f_emr"] = $request->f_emr;
            if (isset($update_array["f_emr"]) && ($update_array["f_emr"] == "other" || $update_array["f_emr"] == "0")) {
                if (isset($update_array["f_emr_other"]) && $update_array["f_emr_other"] != "")  $request->f_emr_other;
            } else $update_array["f_emr_other"] = "";

            if (isset($update_array["f_bcheck_provider"]) && ($update_array["f_bcheck_provider"] == "other" || $update_array["f_bcheck_provider"] == "0")) {
                if (isset($request->f_bcheck_provider_other) && $request->f_bcheck_provider_other != "") $update_array["f_bcheck_provider_other"] = $request->f_bcheck_provider_other;
            } else $update_array["f_bcheck_provider_other"] = "";

            if (isset($update_array["nurse_cred_soft"]) && ($update_array["nurse_cred_soft"] == "other" || $update_array["nurse_cred_soft"] == "0")) {
                if (isset($request->nurse_cred_soft_other) && $request->nurse_cred_soft_other != "") $update_array["nurse_cred_soft_other"] = $request->nurse_cred_soft_other;
            } else $update_array["nurse_cred_soft_other"] = "";

            if (isset($update_array["nurse_scheduling_sys"]) && ($update_array["nurse_scheduling_sys"] == "other" || $update_array["nurse_scheduling_sys"] == "0")) {
                if (isset($request->nurse_scheduling_sys_other) && $request->nurse_scheduling_sys_other != "") $update_array["nurse_scheduling_sys_other"] = $request->nurse_scheduling_sys_other;
            } else $update_array["nurse_scheduling_sys_other"] = "";

            if (isset($update_array["time_attend_sys"]) && ($update_array["time_attend_sys"] == "other" || $update_array["time_attend_sys"] == "0")) {
                if (isset($request->time_attend_sys_other) && $request->time_attend_sys_other != "") $update_array["time_attend_sys_other"] = $request->time_attend_sys_other;
            } else $update_array["time_attend_sys_other"] = "";

            if (isset($request->licensed_beds) && $request->licensed_beds != "") $update_array["licensed_beds"] = $request->licensed_beds;

            if ($request->hasFile('facility_logo')) {
                $facility_logo_name_full = $request->file('facility_logo')->getClientOriginalName();
                $facility_logo_name = pathinfo($facility_logo_name_full, PATHINFO_FILENAME);
                $facility_logo_ext = $request->file('facility_logo')->getClientOriginalExtension();
                $facility_logo = $facility_logo_name . '_' . time() . '.' . $facility_logo_ext;
                $update_array['facility_logo'] = $facility_logo;
                //Upload Image
                // $request->file('facility_logo')->storeAs('assets/facilities/facility_logo', $facility_logo);
                $destinationPath = 'images/facilities';
                $request->file('facility_logo')->move(public_path($destinationPath), $facility_logo);   
        
            }

            if ($request->hasFile('cno_image')) {
                $cno_image_name_full = $request->file('cno_image')->getClientOriginalName();
                $cno_image_name = pathinfo($cno_image_name_full, PATHINFO_FILENAME);
                $cno_image_ext = $request->file('cno_image')->getClientOriginalExtension();
                $cno_image = $cno_image_name . '_' . time() . '.' . $cno_image_ext;
                $update_array['cno_image'] = $cno_image;
                //Upload Image
                // $request->file('cno_image')->storeAs('assets/facilities/cno_image', $cno_image);
                // $facility->update();
                $destinationPath = 'images/facilities/cno_image';
                $request->file('cno_image')->move(public_path($destinationPath), $cno_image);   
        
            }

            if (isset($update_array) && !empty($update_array) && $facility_id != "") {
                $update = Facility::where(['id' => $facility_id])->update($update_array);
                if ($update) {
                    $user_id = $request->user_id;
                    $user = User::where(['id' => $user_id])->get()->first();
                    $this->check = "1";
                    $this->message = "Profile updated successfully";
                    $this->return_data = $this->facilityProfileCompletionFlagStatus($type = "", $user);
                } else {
                    $this->message = "Failed to update profile, Please try again later";
                }
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function changeFacilityLogo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'facility_id' => 'required',
            'facility_logo' => 'required|max:1024|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $facility_id = (isset($request->facility_id) && $request->facility_id != "") ? $request->facility_id : "";
            // dd($request->all());
            if ($request->hasFile('facility_logo') && $facility_id != "") {
                $facility_logo_name_full = $request->file('facility_logo')->getClientOriginalName();
                $facility_logo_name = pathinfo($facility_logo_name_full, PATHINFO_FILENAME);
                $facility_logo_ext = $request->file('facility_logo')->getClientOriginalExtension();
                $facility_logo = $facility_logo_name . '_' . time() . '.' . $facility_logo_ext;
                $update_array['facility_logo'] = $facility_logo;
                //Upload Image
                // $request->file('facility_logo')->storeAs('assets/facilities/facility_logo', $facility_logo);
                $destinationPath = 'images/facilities';
                $request->file('facility_logo')->move(public_path($destinationPath), $facility_logo);   
        

                $update = Facility::where(['id' => $facility_id])->update($update_array);
                if ($update) {
                    $this->check = "1";
                    $this->message = "Facility logo updated successfully";
                } else {
                    $this->message = "Failed to update profile, Please try again later";
                }
            } else {
                $this->message = "Required parameters not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function browseNurses(Request $request)
    {
        $whereCond = ['active' => true];
        if (isset($request->nurse_id) && $request->nurse_id != "") {
            $whereCond = array_merge($whereCond, ['id' => $request->nurse_id]);
        }
        $ret = [];
        Nurse::where($whereCond)
            ->orderBy('created_at', 'desc');
        /* new */

        // dd($request->all());
        $whereCond = [
            'active' => true
        ];
        $ret = Nurse::where($whereCond)
            ->orderBy('created_at', 'desc');

        $certification = $request->certification;

        $specialty = (isset($request->specialty) && $request->specialty != "") ? json_decode($request->specialty) : [];
        /*specialty filter nwe update 06/dec/2021 */
        if (is_array($specialty) && !empty($specialty)) {
            $ret->where(function (Builder $query) use ($specialty) {
                foreach ($specialty as $key => $search_spl_id) {
                    if ($search_spl_id != "")
                        $query->orWhere('specialty', 'like', '%' . $search_spl_id . '%');
                }
            });
        }
        /*specialty filter nwe update 06/dec/2021 */

        $availability = (isset($request->availability) && $request->availability != "") ? json_decode($request->availability) : [];
        if (is_array($availability) && !empty($availability)) {
            $ret->whereHas('availability', function (Builder $query1) use ($availability) {
                $query1->whereIn('days_of_the_week', $availability);
            });
        }

        $is_verified = (isset($request->is_verified) && $request->is_verified != "") ? $request->is_verified : "";
        if ($is_verified != "") {
            $ret->where(function (Builder $query1) use ($is_verified) {
                $query1->where('is_verified', array("1"));
            });
        }

        $search_status = (isset($request->search_status) && $request->search_status != "") ? $request->search_status : "";
        if ($search_status != "") {
            $ret->where(function (Builder $query1) use ($search_status) {
                $query1->where('search_status', array($search_status));
            });
        }

        $search_bill_rate_from = (isset($request->search_bill_rate_from) && $request->search_bill_rate_from != "") ? $request->search_bill_rate_from : "";
        $search_bill_rate_to = (isset($request->search_bill_rate_to) && $request->search_bill_rate_to != "") ? $request->search_bill_rate_to : "";
        if ($search_bill_rate_from != "" && $search_bill_rate_to != "") {
            $ret->where(function (Builder $query) use ($search_bill_rate_from,  $search_bill_rate_to) {
                $query->whereBetween('facility_hourly_pay_rate', array(intval($search_bill_rate_from), intval($search_bill_rate_to)));
            });
        }

        $search_tenure_from = (isset($request->search_tenure_from) && $request->search_tenure_from != "") ? $request->search_tenure_from : "";
        $search_tenure_to = (isset($request->search_tenure_to) && $request->search_tenure_to != "") ? $request->search_tenure_to : "";
        if ($search_tenure_from != "" && $search_tenure_to != "") {
            $ret->where(function (Builder $query) use ($search_tenure_from, $search_tenure_to) {
                $query->whereBetween('experience_as_acute_care_facility', array(intval($search_tenure_from), intval($search_tenure_to)));
                $query->orWhere(function (Builder $query) use ($search_tenure_from, $search_tenure_to) {
                    $query->whereBetween('experience_as_ambulatory_care_facility', array(intval($search_tenure_from), intval($search_tenure_to)));
                });
            });
        }

        $certification = (isset($request->certification) && $request->certification != "") ? $request->certification : "";
        if ($certification != "") {
            $ret->whereHas('certifications', function (Builder $query) use ($certification) {
                $query->whereIn('type', $certification);
            });
        }

        /* state city and postcode new update */
        $states = (isset($request->state) && $request->state != "") ? $request->state : "";
        if (isset($states) && $states != "") {
            $getStates = States::where(['id' => $states])->get();
            if ($getStates->count() > 0) {
                $selected_state = $getStates->first();
                $name = $selected_state->name;
                $iso2 = $selected_state->iso2;
                $ret->where(function (Builder $query1) use ($name, $iso2) {
                    $query1->where('state', array($name));
                    $query1->orWhere('state', array($iso2));
                });
            }
        }

        $cities = (isset($request->city) && $request->city != "") ? $request->city : "";
        if (isset($cities) && $cities != "") {
            $getCities = Cities::where(['id' => $cities])->get();
            if ($getCities->count() > 0) {
                $selected_city = $getCities->first();
                $name = $selected_city->name;
                $ret->where(function (Builder $query1) use ($name) {
                    $query1->where('city', array($name));
                });
            }
        }

        $zipcode = (isset($request->zipcode) && $request->zipcode != "") ? $request->zipcode : "";
        if (isset($zipcode) && $zipcode != "") {
            $zipcode_inp = [];
            $nearest = $this->getNearestMiles($zipcode);
            if (isset($nearest['results']) && !empty($nearest['results'])) {
                foreach ($nearest['results'] as $zipkey => $zip_res) {
                    $zipcode_inp[] = $zip_res['code'];
                }
            }
            if (!empty($zipcode_inp)) {
                $ret->where(function (Builder $query_zip) use ($zipcode_inp) {
                    $query_zip->whereIn('postcode', $zipcode_inp);
                });
            } else {
                $ret->where(function (Builder $query_zip) use ($zipcode) {
                    $query_zip->where('postcode', array($zipcode));
                });
            }
        }
        /* state city and postcode new update */

        /* keywords filter */
        $search_keyword = (isset($request->search_keyword) && $request->search_keyword != "") ? $request->search_keyword : "";
        if ($search_keyword) {
            $ret->search([
                'user.first_name',
                'user.last_name',
                'user.email',
                'nursing_license_state',
                'nursing_license_number',
                'availability.days_of_the_week',
                'experiences.organization_name',
                'experiences.organization_department_name',
                'experiences.description_job_duties'
            ], $search_keyword);
        }
        /* keywords filter */

        /* new */
        $nurses_list = $ret->count();
        if ($ret->count() > 0) {
            $limit = 25;
            $total_pages = ceil($ret->count() / $limit);
            $nurse_data = $ret->get();
            // $nurse_data = $ret->paginate($limit);
            $nurse_info['data'] = $this->nurseInfo($nurse_data);
            $nurse_info['total_pages_available'] =  strval($total_pages);
            $nurse_info["current_page"] = (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : "1";
            $nurse_info['results_per_page'] = strval($limit);

            $this->check = "1";
            $this->message = "Nurses listed successfully";
            $this->return_data = $nurse_info;
        } else {
            $this->message = "No nurses found";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getSeniorityLevelOptions()
    {
        $seniorityLevels = $this->getSeniorityLevel()->pluck('title', 'id');
        $data = [];
        foreach ($seniorityLevels as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Seniority level's has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function offeredNurses($type, Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            /*  dropdown data's */
            $controller = new Controller();
            $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
            $specialties = $controller->getSpecialities()->pluck('title', 'id');
            $preferredShifts = $this->getPreferredShift()->pluck('title', 'id');
            /*  dropdown data's */
            $user_info = USER::where(['id' => $request->user_id])->get();
            if ($user_info->count() > 0) {
                $user = $user_info->first();

                $page = (isset($request->page_number) && $request->page_number != "") ? $request->page_number : "1";
                if ($type == "active")  $where = ['active' => '1', 'created_by' => $user->id, 'status' => 'Active'];
                elseif ($type == "completed") $where = ['active' => '1', 'created_by' => $user->id, 'status' => 'Active'];
                else $where = ['active' => '1', 'created_by' => $user->id, 'status' => 'Pending'];

                $limit = 25;
                $offers_info = Offer::where($where)
                    ->orderBy('created_at', 'desc')
                    // ->paginate($limit);
                    ->get();

                $total_pages = ceil($offers_info->count() / $limit);
                $offered['total_pages_available'] =  strval($total_pages);
                $offered["current_page"] = (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : "1";
                $offered['results_per_page'] = strval($limit);

                $offered['data'] = [];
                if ($offers_info->count() > 0) {
                    foreach ($offers_info as $key => $off) {
                        /* $o['creator'] = $off->creator;
                        $o['nurse'] = $off->nurse;
                        $o['job'] = $off->job; */

                        $nurse_info = USER::where(['id' => $off->nurse->user_id])->get();
                        $first_name = $last_name = $image = "";
                        if ($user_info->count() > 0) {
                            $nurse = $nurse_info->first();
                            $first_name = (isset($nurse->first_name) && $nurse->first_name != "") ? $nurse->first_name : "";
                            $last_name = (isset($nurse->last_name) && $nurse->last_name != "") ? $nurse->last_name : "";
                            $image = (isset($nurse->image) && $nurse->image != "") ? url('public/images/nurses/profile/' . $nurse->image) : "";

                            $image_base = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
                            if ($nurse->image) {
                                $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $nurse->image);
                                if ($t) {
                                    $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $nurse->image);
                                }
                            }
                            $image_base = 'data:image/jpeg;base64,' . base64_encode($profileNurse);
                        }
                        $o['nurse_first_name'] = $first_name;
                        $o['nurse_last_name'] = $last_name;
                        $o['nurse_image'] = $image;
                        // $o['nurse_image_base'] = $image_base;

                        if($off->job){

                            $o['preferred_shift'] = (isset($off->job->preferred_shift) && $off->job->preferred_shift != "") ? strval($off->job->preferred_shift) : "";
                            $o['preferred_shift_definition'] = (isset($preferredShifts[$off->job->preferred_shift]) && $preferredShifts[$off->job->preferred_shift] != "") ? $preferredShifts[$off->job->preferred_shift] : "";
                            $o['preferred_shift_duration'] = (isset($off->job->preferred_shift_duration) && $off->job->preferred_shift_duration != "") ? strval($off->job->preferred_shift_duration) : "";
                            $o['preferred_shift_duration_definition'] = (isset($specialties[$off->job->preferred_shift_duration]) && $specialties[$off->job->preferred_shift_duration] != "") ? $specialties[$off->job->preferred_shift_duration] : "";
                            $o['preferred_specialty'] = (isset($off->job->preferred_specialty) && $off->job->preferred_specialty != "") ? strval($off->job->preferred_specialty) : "";
                            $o['preferred_specialty_definition'] = (isset($specialties[$off->job->preferred_specialty]) && $specialties[$off->job->preferred_specialty] != "") ? $specialties[$off->job->preferred_specialty] : "";
                            $o['preferred_assignment_duration'] = (isset($off->job->preferred_assignment_duration) && $off->job->preferred_assignment_duration != "") ? strval($off->job->preferred_assignment_duration) : "0";
                            $o['preferred_assignment_duration_definition'] = (isset($assignmentDurations[$off->job->preferred_assignment_duration]) && $assignmentDurations[$off->job->preferred_assignment_duration] != "") ? $assignmentDurations[$off->job->preferred_assignment_duration] : "0";
                            if(isset($o['preferred_assignment_duration_definition'])){
                                $assignment = explode(" ", $assignmentDurations[$off->job->preferred_assignment_duration]);
                                $o['preferred_assignment_duration_definition'] = $assignment[0]; // 12 Week
                            }
                            /* nurse_info */
                            $nurse_info = USER::where(['id' => $request->user_id])->get();
                            /* nurse_info */
                            $o['preferred_hourly_pay_rate'] = (isset($off->job->preferred_hourly_pay_rate) && $off->job->preferred_hourly_pay_rate != "") ? strval($off->job->preferred_hourly_pay_rate) : "0";
                            $o['preferred_days_of_the_week'] = (isset($off->job->preferred_days_of_the_week) && $off->job->preferred_days_of_the_week != "") ? $off->job->preferred_days_of_the_week : "";
                            $days = [];
                            if (isset($off->job->preferred_days_of_the_week)) {
                                $day_s = explode(",", $off->job->preferred_days_of_the_week);
                                if (is_array($day_s) && !empty($day_s)) {
                                    foreach ($day_s as $day) {
                                        if ($day == "Sunday") $days[] = "Su";
                                        elseif ($day == "Monday") $days[] = "M";
                                        elseif ($day == "Tuesday") $days[] = "T";
                                        elseif ($day == "Wednesday") $days[] = "W";
                                        elseif ($day == "Thursday") $days[] = "Th";
                                        elseif ($day == "Friday") $days[] = "F";
                                        elseif ($day == "Saturday") $days[] = "Sa";
                                    }
                                }
                            }
                            $o['preferred_days_of_the_week_array'] = ($o['preferred_days_of_the_week'] != "") ? $days : [];
                            $o['preferred_days_of_the_week_string'] = ($o['preferred_days_of_the_week'] != "") ? implode(",", $days) : "";
                            $o['offered_at'] = (isset($off->created_at) && $off->created_at != "") ? date('D h:i A', strtotime($off->created_at)) : date('D h:i A');

                            /* rating */
                            $nurse_rating_info = NurseRating::where(['nurse_id' => $off->nurse_id, 'status' => '1', 'is_deleted' => '0']);
                            $overall = [];
                            $rating_flag = "0";
                            if ($nurse_rating_info->count() > 0) {
                                $rating_flag = "1";
                                foreach ($nurse_rating_info->get() as $key => $r) {
                                    $overall[] = $r->overall;
                                }
                            }
                            $rating = $this->ratingCalculation(count($overall), $overall);
                            /* rating */

                            if ($type == "active" || $type == "completed") {
                                $o['start_date'] = date('d F Y', strtotime($off->job->start_date));
                            }
                            if ($type == "completed") {
                                $o['end_date'] = date('d F Y', strtotime($off->job->end_date));
                            }

                            if (($type == "completed") && ($off->job->end_date < date('Y-m-d'))) {
                                $o['rating_flag'] = $rating_flag;
                                $o['rating'] = $rating;
                                $o['ck_end'] = $off->job->end_date;
                                $offered['data'][] = $o;
                            } elseif (($type == "active") && ($off->job->end_date >= date('Y-m-d'))) {
                                $offered['data'][] = $o;
                            } elseif ($type == "list") {
                                $offered['data'][] = $o;
                            }
                        }
                    }

                    $this->check = "1";
                    $this->message = "Job offered listed successfully";
                    $this->return_data = $offered;
                } else {
                    $this->message = "Currently nothing " . $type;
                }
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getJobFunctionOptions()
    {
        $jobFunctions = $this->getJobFunction()->pluck('title', 'id');
        $data = [];
        foreach ($jobFunctions as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Job function's has been listed successfully";
        $this->return_data = $data;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function apiJobApply(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'nurse_id' => 'required',
            'api_key' => 'required',
            'job_id' => 'required',
            // 'start_date' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $insert_offer["nurse_id"] = $request->nurse_id;
            $insert_offer["created_by"] = $request->nurse_id;
            $insert_offer["job_id"] = $request->job_id;
            $now = date("Y-m-d");
            // $now = isset($request->start_date)?$request->start_date:date("Y-m-d", strtotime('+48 hours', strtotime($now)));
            
            $insert_offer["expiration"] = date("Y-m-d", strtotime('+48 hours', strtotime($now)));
            $insert_offer["start_date"] = $now;
            // follow records
            $insert_follow["user_id"] = $request->user_id;
            $insert_follow["job_id"] = $request->job_id;
            $insert_follow["applied_status"] = '1';
            $insert_follow["like_status"] = '0';
            $insert_follow["status"] = '1';
            // end follow data
            $check_offer = Offer::where(['nurse_id' => $request->nurse_id, 'job_id' => $request->job_id])->get()->first();
            $today = date('Y-m-d H:i:s');
            
            if(isset($check_offer) && $check_offer['expiration'] > $today){
                $this->check = "0";
                $this->message = "You are already apply for this job";
            }else{
                DB::table('offers')->where('nurse_id', $request->nurse_id)->where('job_id', $request->job_id)->delete();
                $offer = Offer::create($insert_offer);
                Follows::create($insert_follow);
                if ($offer) {

                    $off_data["id"] = (isset($offer->id) && $offer->id != "") ? $offer->id : "";
                    $off_data["nurse_id"] = (isset($offer->nurse_id) && $offer->nurse_id != "") ? $offer->nurse_id : "";
                    $off_data["start_date"] = (isset($offer->start_date) && $offer->start_date != "") ? $offer->start_date : "";
                    $off_data["job_id"] = (isset($offer->job_id) && $offer->job_id != "") ? $offer->job_id : "";
                    $off_data["expiration"] = (isset($offer->expiration) && $offer->expiration != "") ? $offer->expiration : "";
    
                    /* mail */
                    $nurse_info = Nurse::where(['id' => $request->nurse_id]);
                    if ($nurse_info->count() > 0) {
                        $nurse = $nurse_info->first();
                        $user_info = User::where(['id' => $nurse->user_id]);
                        if ($user_info->count() > 0) {
                            $user = $user_info->first(); // nurse user info
                            $facility_user_info = User::where(['id' => $offer->created_by]);
                            if ($facility_user_info->count() > 0) {
                                $facility_user = $facility_user_info->first(); // facility user info
                                $data = [
                                    'to_email' => $user->email,
                                    'to_name' => $user->first_name . ' ' . $user->last_name
                                ];
                                $replace_array = [
                                    '###NURSENAME###' => $user->first_name . ' ' . $user->last_name,
                                    '###FACILITYNAME###' => $facility_user->facilities[0]->name,
                                    '###LOCATION###' => $facility_user->facilities[0]->city . ',' . $facility_user->facilities[0]->state,
                                    '###SPECIALITY###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_specialty),
                                    '###STARTDATE###' => date('d F Y', strtotime($offer->job->start_date)),
                                    '###DURATION###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_assignment_duration),
                                    '###SHIFT###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_shift),
                                    '###WORKINGDAYS###' => $offer->job->preferred_days_of_the_week,
                                ];
                                $this->basic_email($template = "facility_make_offer", $data, $replace_array);
                            }
                        }
                    }
                    /* mail */
    
                    $this->check = "1";
                    $this->message = "Job applied successfully";
                    $this->return_data = $off_data;
                } else {
                    $this->check = "0";
                    $this->message = "Failed to apply for job, Please try again later";
                }
            }
            
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
  

    }

    public function apiJobInvite(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'nurse_id' => 'required',
            'facility_id' => 'required',
            'api_key' => 'required',
            'job_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $insert_offer["nurse_id"] = $request->nurse_id;
            $insert_offer["created_by"] = $request->facility_id;
            $insert_offer["job_id"] = $request->job_id;
            $insert_offer["expiration"] = date("Y-m-d H:i:s", strtotime('+48 hours'));

            $offer = Offer::create($insert_offer);
            if ($offer) {

                $off_data["id"] = (isset($offer->id) && $offer->id != "") ? $offer->id : "";
                $off_data["nurse_id"] = (isset($offer->nurse_id) && $offer->nurse_id != "") ? $offer->nurse_id : "";
                $off_data["facility"] = (isset($offer->created_by) && $offer->created_by != "") ? $offer->created_by : "";
                $off_data["job_id"] = (isset($offer->job_id) && $offer->job_id != "") ? $offer->job_id : "";
                $off_data["expiration"] = (isset($offer->expiration) && $offer->expiration != "") ? date('d-m-Y', strtotime($offer->expiration)) : "";

                /* mail */
                $nurse_info = Nurse::where(['id' => $request->nurse_id]);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->first();
                    $user_info = User::where(['id' => $nurse->user_id]);
                    if ($user_info->count() > 0) {
                        $user = $user_info->first(); // nurse user info
                        $facility_user_info = User::where(['id' => $offer->created_by]);
                        if ($facility_user_info->count() > 0) {
                            $facility_user = $facility_user_info->first(); // facility user info
                            $data = [
                                'to_email' => $user->email,
                                'to_name' => $user->first_name . ' ' . $user->last_name
                            ];
                            $replace_array = [
                                '###NURSENAME###' => $user->first_name . ' ' . $user->last_name,
                                '###FACILITYNAME###' => $facility_user->facilities[0]->name,
                                '###LOCATION###' => $facility_user->facilities[0]->city . ',' . $facility_user->facilities[0]->state,
                                '###SPECIALITY###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_specialty),
                                '###STARTDATE###' => date('d F Y', strtotime($offer->job->start_date)),
                                '###DURATION###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_assignment_duration),
                                '###SHIFT###' => \App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_shift),
                                '###WORKINGDAYS###' => $offer->job->preferred_days_of_the_week,
                            ];
                            $this->basic_email($template = "facility_make_offer", $data, $replace_array);
                        }
                    }
                }
                /* mail */

                $this->check = "1";
                $this->message = "Offer sent successfully";
                $this->return_data = $off_data;
            } else {
                $this->check = "0";
                $this->message = "Failed to sent offer, Please try again later";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function facilityPostedJobs($type, Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            /*  dropdown data's */
            $controller = new Controller();
            $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
            $specialties = $controller->getSpecialities()->pluck('title', 'id');
            /*  dropdown data's */
            $user_info = USER::where(['id' => $request->user_id])->get();
            if ($user_info->count() > 0) {
                $user = $user_info->first();

                // $page = (isset($request->page_number) && $request->page_number != "") ? $request->page_number : "1";
                /* if ($type == "active")  $where = ['active' => '1', 'created_by' => $user->id, 'status' => 'Active'];
                elseif ($type == "completed") $where = ['active' => '1', 'created_by' => $user->id, 'status' => 'Completed'];
                else $where = ['active' => '1', 'created_by' => $user->id, 'status' => 'Pending']; */

                $limit = 25;
                $whereCond = ['active' => '1', 'created_by' => $user->id];
                $ret = Job::where($whereCond)
                    ->orderBy('created_at', 'desc');
                $jobs_info = $ret->get();
                // $jobs_info = $ret->paginate($limit);


                $tot_res = 0;
                $my_jobs['data'] = [];
                if ($jobs_info->count() > 0) {
                    foreach ($jobs_info as $key => $job) {
                        /* $o['creator'] = $job->creator;
                        $o['nurse'] = $job->nurse;
                        $o['job'] = $job->job; */

                        $o['job_id'] = (isset($job->id) && $job->id != "") ? $job->id : "";
                        $o['facility_first_name'] = (isset($job->creator->first_name) && $job->creator->first_name != "") ? $job->creator->first_name : "";
                        $o['facility_last_name'] = (isset($job->creator->last_name) && $job->creator->last_name != "") ? $job->creator->last_name : "";
                        // $o['faci'] = $job->facility;
                        $o['facility_image'] = (isset($job->facility->facility_logo) && $job->facility->facility_logo != "") ? url('public/images/facilities/' . $job->facility->facility_logo) : "";

                        $o['preferred_shift'] = (isset($job->preferred_shift) && $job->preferred_shift != "") ? strval($job->preferred_shift) : "";
                        $o['preferred_shift_definition'] = (isset($job->preferred_shift) && $job->preferred_shift != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_shift) : "";
                        $o['preferred_specialty'] = (isset($job->preferred_specialty) && $job->preferred_specialty != "") ? strval($job->preferred_specialty) : "";
                        $o['preferred_specialty_definition'] = (isset($specialties[$job->preferred_specialty]) && $specialties[$job->preferred_specialty] != "") ? $specialties[$job->preferred_specialty] : "";
                        $o['preferred_assignment_duration'] = (isset($job->preferred_assignment_duration) && $job->preferred_assignment_duration != "") ? strval($job->preferred_assignment_duration) : "0";
                        $o['preferred_assignment_duration_definition'] = (isset($assignmentDurations[$job->preferred_assignment_duration]) && $assignmentDurations[$job->preferred_assignment_duration] != "") ? $assignmentDurations[$job->preferred_assignment_duration] : "0";
                        if(isset($o['preferred_assignment_duration_definition'])){
                            $assignment = explode(" ", $assignmentDurations[$job->preferred_assignment_duration]);
                            $o['preferred_assignment_duration_definition'] = $assignment[0]; // 12 Week
                        }
                        $o['preferred_hourly_pay_rate'] = (isset($job->preferred_hourly_pay_rate) && $job->preferred_hourly_pay_rate != "") ? strval($job->preferred_hourly_pay_rate) : "0";
                        $o['preferred_days_of_the_week'] = (isset($job->preferred_days_of_the_week) && $job->preferred_days_of_the_week != "") ? $job->preferred_days_of_the_week : "";
                        $days = [];
                        if (isset($job->preferred_days_of_the_week)) {
                            $day_s = explode(",", $job->preferred_days_of_the_week);
                            if (is_array($day_s) && !empty($day_s)) {
                                foreach ($day_s as $day) {
                                    if ($day == "Sunday") $days[] = "Su";
                                    elseif ($day == "Monday") $days[] = "M";
                                    elseif ($day == "Tuesday") $days[] = "T";
                                    elseif ($day == "Wednesday") $days[] = "W";
                                    elseif ($day == "Thursday") $days[] = "Th";
                                    elseif ($day == "Friday") $days[] = "F";
                                    elseif ($day == "Saturday") $days[] = "Sa";
                                }
                            }
                        }
                        $o['preferred_days_of_the_week_array'] = ($o['preferred_days_of_the_week'] != "") ? $days : [];
                        $o['preferred_days_of_the_week_string'] = ($o['preferred_days_of_the_week'] != "") ? implode(",", $days) : "";

                        $o['facility_id'] = (isset($job->facility_id) && $job->facility_id != "") ? $job->facility_id : "";

                        $o['seniority_level'] = (isset($job->seniority_level) && $job->seniority_level != "") ? $job->seniority_level : "";
                        $o['seniority_level_definition'] = (isset($job->seniority_level) && $job->seniority_level != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->seniority_level) : "";

                        $o['job_function'] = (isset($job->job_function) && $job->job_function != "") ? strval($job->job_function) : "";
                        $o['job_function_definition'] = (isset($job->job_function) && $job->job_function != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->job_function) : "";

                        $o['preferred_shift_duration'] = (isset($job->preferred_shift_duration) && $job->preferred_shift_duration != "") ? strval($job->preferred_shift_duration) : "";
                        $o['preferred_shift_duration_definition'] = (isset($job->preferred_shift_duration) && $job->preferred_shift_duration != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_shift_duration) : "";

                        $o['preferred_work_location'] = (isset($job->preferred_work_location) && $job->preferred_work_location != "") ? strval($job->preferred_work_location) : "";
                        $o['preferred_work_location_definition'] = (isset($job->preferred_work_location) && $job->preferred_work_location != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_work_location) : "";

                        $o['preferred_experience'] = (isset($job->preferred_experience) && $job->preferred_experience != "") ? strval($job->preferred_experience) : "";
                        $o['preferred_experience_definition'] = (isset($job->preferred_experience) && $job->preferred_experience != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_experience) : "";

                        $o['job_cerner_exp'] = (isset($job->job_cerner_exp) && $job->job_cerner_exp != "") ? strval($job->job_cerner_exp) : "";
                        $o['job_cerner_exp_definition'] = (isset($job->job_cerner_exp) && $job->job_cerner_exp != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->job_cerner_exp) : "";

                        $o['job_meditech_exp'] = (isset($job->job_meditech_exp) && $job->job_meditech_exp != "") ? strval($job->job_meditech_exp) : "";
                        $o['job_meditech_exp_definition'] = (isset($job->job_meditech_exp) && $job->job_meditech_exp != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->job_meditech_exp) : "";

                        $o['job_epic_exp'] = (isset($job->job_epic_exp) && $job->job_epic_exp != "") ? strval($job->job_epic_exp) : "";
                        $o['job_epic_exp_definition'] = (isset($job->job_epic_exp) && $job->job_epic_exp != "") ? \App\Providers\AppServiceProvider::keywordTitle($job->job_epic_exp) : "";

                        $o['job_other_exp'] = (isset($job->job_other_exp) && $job->job_other_exp != "") ? $job->job_other_exp : "";
                        $o['description'] = (isset($job->description) && $job->description != "") ? $job->description : "";
                        $o['responsibilities'] = (isset($job->responsibilities) && $job->responsibilities != "") ? $job->responsibilities : "";
                        $o['qualifications'] = (isset($job->qualifications) && $job->qualifications != "") ? $job->qualifications : "";
                        $o['job_video'] = (isset($job->job_video) && $job->job_video != "") ? $job->job_video : "";
                        $o['active'] = (isset($job->active) && $job->active != "") ? $job->active : "";

                        /* offered nurse id */
                        $o['offered_nurse_id'] = "";
                        if (isset($job->offers) && !empty($job->offers)) {
                            foreach ($job->offers as $key => $val) {
                                if (isset($val->status) && $val->status == "Active") {
                                    $o['offered_nurse_id'] = (isset($val->nurse_id) && $val->nurse_id != "") ? $val->nurse_id : "";
                                }
                            }
                        }

                        $comment = [];
                        if ($o['offered_nurse_id'] != "") {
                            $nurse_rating_info = NurseRating::where(['nurse_id' => $o['offered_nurse_id'], 'job_id' => $job->id]);
                            if ($nurse_rating_info->count() > 0) {
                                $facility_commented = $nurse_rating_info->first();
                                $comment['rating'] = (isset($facility_commented->overall) && $facility_commented->overall != "") ? $facility_commented->overall : "0";
                                $comment['experience'] = (isset($facility_commented->experience) && $facility_commented->experience != "") ? $facility_commented->experience : "";
                                $nurse_user_id = (isset($facility_commented->nurse->user_id) && $facility_commented->nurse->user_id != "") ? $facility_commented->nurse->user_id : "";
                                if ($nurse_user_id != "") {
                                    $nurse_user_info = User::where(['id' => $nurse_user_id]);
                                    if ($nurse_user_info->count() > 0) {
                                        $nui = $nurse_user_info->first();
                                        $comment['nurse_name'] = $nui->first_name . ' ' . $nui->last_name;
                                        $comment['nurse_image'] = (isset($nui->image) && $nui->image != "") ? url('public/images/nurses/profile/' . $nui->image) : "";

                                        $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
                                        if ($nui->image) {
                                            $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $nui->image);
                                            if ($t) {
                                                $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $nui->image);
                                            }
                                        }
                                        $comment["nurse_image_base"] = 'data:image/jpeg;base64,' . base64_encode($profileNurse);
                                    }
                                }
                            }
                        }
                        $o['rating_comment'] = (!empty($comment)) ? $comment : (object)array();

                        /* offered nurse id */

                        /* job assets */
                        $job_uploads = [];
                        $job_assets = JobAsset::where(['active' => '1', 'job_id' => $job->id, "deleted_at" => NULL]);
                        if ($job_assets->count() > 0) {
                            foreach ($job_assets->get() as $key => $asset) {
                                $job_uploads[] = ['asset_id' => $asset->id, 'name' => url('storage/assets/jobs/' . $job->id . '/' . $asset->name)];
                            }
                        }
                        $o["job_photos"] = $job_uploads;
                        /* job assets */


                        if ($type == "posted") {
                            $count_applied = Follows::where(['job_id' => $job->id])->count();
                            $o['applied'] = strval($count_applied);
                        }

                        $o['start_date'] = date('d F Y', strtotime($job->start_date));
                        $o['end_date'] = date('d F Y', strtotime($job->end_date));

                        if ($type == "posted" && ((empty($job->offers[0])) || (isset($job->offers[0]->status) && $job->offers[0]->status == "Pending"))) {
                            if ($tot_res == 0) $tot_res += 1; //initialized first page`
                            $tot_res += 1;
                            $my_jobs['data'][] = $o;
                        } elseif ($type == "active" && (isset($job->offers[0]->status) && $job->offers[0]->status == "Active" && ($job->end_date >= date('Y-m-d')))) {
                            if ($tot_res == 0) $tot_res += 1; //initialized first page`
                            $tot_res += 1;
                            $my_jobs['data'][] = $o;
                        } elseif ($type == "completed" && (isset($job->offers[0]->status) && $job->offers[0]->status == "Active" && ($job->end_date < date('Y-m-d')))) {
                            if ($tot_res == 0) $tot_res += 1; //initialized first page`
                            $tot_res += 1;
                            $rating_info = NurseRating::where(['job_id' => $job->id]);
                            $o['rating_flag'] = ($rating_info->count() > 0) ? "1" : "0";
                            $my_jobs['data'][] = $o;
                        }
                    }

                    $this->check = "1";
                    $this->message = "Job offered listed successfully";
                } else {
                    $this->message = "Currently nothing " . $type;
                }
                $total_pages = ceil($tot_res / $limit);
                $my_jobs['total_pages_available'] =  strval($total_pages);
                $my_jobs["current_page"] = (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : "1";
                $my_jobs['results_per_page'] = strval($limit);

                $this->return_data = $my_jobs;
            } else {
                $this->message = "User not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function apiJobsList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'facility_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $jobs = [];
            $ret = Job::where('active', true)
                ->orderBy('created_at', 'desc');

            $ret->where('facility_id', $request->facility_id);

            $ids = [];
            $nurse = Nurse::where(['user_id' => $request->user_id])->first();
            if ($nurse->count() > 0) {
                if (isset($nurse->offers) && count($nurse->offers) > 0) {
                    $ids = $nurse->offers->whereNotNull('job_id')->pluck('id');
                }
            }
            $ret->whereDoesntHave('offers', function (Builder $query) use ($ids) {
                $query->whereIn('id', $ids);
            });
            $temp = $ret->get();
            foreach ($temp as $job) {
                $job_info = Job::where(['id' => $job->id, 'active' => '1']);
                $content = [];
                if ($job_info->count() > 0) {
                    $job = $job_info->first();
                    $content = [
                        'name' => $job->facility->name,
                        'location' => $job->facility->city . ', ' . $job->facility->state,
                        'specialty' => $job->preferred_specialty ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_specialty) : 'N/A',
                        'jobDetail' => [
                            'start_date' => $job->start_date ? date("jS F Y", strtotime($job->start_date)) : 'N/A',
                            'duration' => $job->preferred_assignment_duration ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_assignment_duration) : 'N/A',
                            'shift' => $job->preferred_shift_duration ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_shift_duration) : 'N/A',
                            'workdays' => $job->preferred_days_of_the_week ?: 'N/A',
                        ], 'terms' => '<p><strong>TERMS ACKNOWLEDGMENT</strong></p> <p>By clicking on the &ldquo;Make an Offer&rdquo; your facility agrees to pay the hourly bill rate reflected on the nurse&rsquo;s profile page per the terms established in the Nurseify vendor agreement</p> <p><strong>NEXT STEPS</strong></p> <ul> <li><strong>Webo User</strong>&nbsp;will have 48 hours to accept your booking request</li> <li>You will receive an email notice after the nurse accepts or rejects the request</li> <li>Assuming the nurse accepts, a Nurseify Consultant will contact you to coordinate onboarding logistics</li> <li>If the nurse rejects, we will provide additional nurses that may meet your need</li> <li>Contact us anytime at&nbsp;<a href="mailto:info@nurseify.app">info@nurseify.app</a></li> </ul>'
                    ];
                }

                $jobs[] = ['job_id' => $job->id, 'job' => $job->facility->name . ' - ' . \App\Providers\AppServiceProvider::keywordTitle($job->preferred_specialty), 'content' => $content];
            }

            if (!empty($jobs)) {
                $this->check = "1";
                $this->message = "Invite nurse for the jobs. Listed successfully";
                $this->return_data = $jobs;
            } else {
                $this->message = "No jobs available for this nurse";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function apiJobFacility(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'job_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $job_info = Job::where(['id' => $request->job_id, 'active' => '1']);
            if ($job_info->count() > 0) {
                $job = $job_info->first();
                $this->check = "1";
                $this->message = "Job information listed successfully";
                $this->return_data = [
                    'name' => $job->facility->name,
                    'location' => $job->facility->city . ', ' . $job->facility->state,
                    'specialty' => $job->preferred_specialty ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_specialty) : 'N/A',
                    'jobDetail' => [
                        'startdate' => $job->created_at ? date("jS F Y", strtotime($job->created_at)) : 'N/A',
                        'duration' => $job->preferred_assignment_duration ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_assignment_duration) : 'N/A',
                        'shift' => $job->preferred_shift_duration ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_shift_duration) : 'N/A',
                        'workdays' => $job->preferred_days_of_the_week ?: 'N/A',
                    ]
                ];
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function appliedNurses(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'job_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_exists = Follows::where([
                'status' => '1',
                'job_id' => $request->job_id,
            ]);

            if ($check_exists->count() > 0) {
                $nurses_applied = $check_exists->get();
                $response = [];
                foreach ($nurses_applied as $key => $applied) {
                    $data['nurse_user_id']  = (isset($applied->creator->id) && $applied->creator->id != "") ? $applied->creator->id : "";
                    $nurse_info = NURSE::where(['user_id' => $data['nurse_user_id']]);

                    $data['nurse_id'] = "";
                    if ($nurse_info->count() > 0) {
                        $nurse = $nurse_info->first();
                        $data['nurse_id'] = $nurse->id;
                    }
                    $fname = (isset($applied->creator->first_name) && $applied->creator->first_name != "") ? $applied->creator->first_name : "";
                    $lname = (isset($applied->creator->last_name) && $applied->creator->last_name != "") ? $applied->creator->last_name : "";
                    $data['name'] = $fname . ' ' . $lname;
                    $data['profile'] = (isset($applied->creator->image) && $applied->creator->image != "") ? url('public/images/nurses/profile/' . $applied->creator->image) : "";

                    $profileNurse = "";
                    if ($applied->creator->image) {
                        $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $applied->creator->image);
                        if ($t) {
                            $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $applied->creator->image);
                        }
                    }
                    $data["profile_base"] = ($profileNurse != "") ? 'data:image/jpeg;base64,' . base64_encode($profileNurse) : "";
                    /* rating */
                    $data['rating'] = "0";
                    $overall = [];
                    if ($data['nurse_id'] != "") {
                        $nurse_rating_info = NurseRating::where(['nurse_id' => $data['nurse_id'], 'status' => '1', 'is_deleted' => '0']);
                        if ($nurse_rating_info->count() > 0) {
                            foreach ($nurse_rating_info->get() as $key => $r) {
                                $overall[] = $r->overall;
                            }
                        }
                    }
                    $data['rating'] = $this->ratingCalculation(count($overall), $overall);
                    /* rating */

                    $response[] = $data;
                }
                if (!empty($response)) {
                    $this->check = "1";
                    $this->message = "Applied nurses listed successfully";
                    $this->return_data = $response;
                } else {
                    $this->message = "Nurse applied jobs looks empty";
                }
            } else {
                $this->message = "Nurse applied jobs looks empty";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function nurseRating(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'nurse_id' => 'required',
            'job_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $nurse_info = NURSE::where('id', $request->nurse_id);
                if ($nurse_info->count() > 0) {
                    $nurse = $nurse_info->get()->first();
                    $insert_array['nurse_id'] = $nurse->id;
                    if (isset($request->job_id) && $request->job_id != "")
                        $insert_array['job_id'] = $request->job_id;
                    if (isset($request->overall) && $request->overall != "")
                        $update_array['overall'] = $insert_array['overall'] = $request->overall;
                    if (isset($request->clinical_skills) && $request->clinical_skills != "")
                        $update_array['clinical_skills'] = $insert_array['clinical_skills'] = $request->clinical_skills;
                    if (isset($request->nurse_teamwork) && $request->nurse_teamwork != "")
                        $update_array['nurse_teamwork'] = $insert_array['nurse_teamwork'] = $request->nurse_teamwork;
                    if (isset($request->interpersonal_skills) && $request->interpersonal_skills != "")
                        $update_array['interpersonal_skills'] = $insert_array['interpersonal_skills'] = $request->interpersonal_skills;
                    if (isset($request->work_ethic) && $request->work_ethic != "")
                        $update_array['work_ethic'] = $insert_array['work_ethic'] = $request->work_ethic;
                    if (isset($request->experience) && $request->experience != "")
                        $update_array['experience'] = $insert_array['experience'] = $request->experience;

                    $check_exists = NurseRating::where(['nurse_id' => $nurse->id, 'job_id' => $request->job_id]);
                    if ($check_exists->count() > 0) {
                        $rating_row = $check_exists->first();
                        $data = NurseRating::where(['id' => $rating_row->id])->update($update_array);
                    } else {
                        $data = NurseRating::create($insert_array);
                    }

                    if (isset($data) && $data == true) {
                        $this->check = "1";
                        $this->message = "Your rating is submitted successfully";
                    } else {
                        $this->message = "Failed to update ratings, Please try again later";
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

    public function removeJobDocument(Request $request)
    {
        $validator = \Validator::make($request->all(), ['job_id' => 'required', 'asset_id' => 'required',
        'api_key' => 'required',]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $id = (isset($request->asset_id) && $request->asset_id != "") ? $request->asset_id : "";
            $job_id = (isset($request->job_id) && $request->job_id != "") ? $request->job_id : "";

            $jobAsset = JobAsset::where(['id' => $id, 'job_id' => $job_id, 'active' => '1', 'deleted_at' => NULL]);
            if ($jobAsset->count() > 0) {
                $job_asset = $jobAsset->first();
                $t = Storage::exists('assets/jobs/' . $job_asset->id . '/' . $job_asset->name);
                if ($t && $job_asset->name) {
                    Storage::delete('assets/jobs/' . $job_asset->id . '/' . $job_asset->name);
                }
                $delete = $job_asset->delete();
                if ($delete) {
                    $this->check = "1";
                    $this->message = "Job photo removed successfully";
                } else {
                    $this->message = "Job photo not found or already removed";
                }
            } else {
                $this->message = "Job photo not found or already removed";
            }
        }


        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function settingsFacility(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'facility_id' => 'required',
            'api_key' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $facility_id = (isset($request->facility_id) && $request->facility_id != "") ? $request->facility_id : "";
            $facility_info = Facility::where(['id' => $facility_id, 'active' => '1']);
            if ($facility_info->count() > 0) {
                $facility = $facility_info->first();

                $return_data['facility_name'] = (isset($facility->name) && $facility->name != "") ? $facility->name : "";
                $return_data['address'] = (isset($facility->address) && $facility->address != "") ? $facility->address : "";
                $return_data['city'] = (isset($facility->city) && $facility->city != "") ? $facility->city : "";
                $return_data['state'] = (isset($facility->state) && $facility->state != "") ? $facility->state : "";
                $return_data['postcode'] = (isset($facility->postcode) && $facility->postcode != "") ? $facility->postcode : "";


                /* rating */
                $rating_info = FacilityRating::where(['facility_id' => $facility->id]);
                $overall = $on_board = $nurse_team_work = $leadership_support = $tools_todo_my_job = $a = [];
                if ($rating_info->count() > 0) {
                    foreach ($rating_info->get() as $key => $r) {
                        $overall[] = $r->overall;
                        $on_board[] = $r->on_board;
                        $nurse_team_work[] = $r->nurse_team_work;
                        $leadership_support[] = $r->leadership_support;
                        $tools_todo_my_job[] = $r->tools_todo_my_job;
                    }
                }
                $rating['over_all'] = $this->ratingCalculation(count($overall), $overall);
                $rating['on_board'] = $this->ratingCalculation(count($on_board), $on_board);
                $rating['nurse_team_work'] = $this->ratingCalculation(count($nurse_team_work), $nurse_team_work);
                $rating['leadership_support'] = $this->ratingCalculation(count($leadership_support), $leadership_support);
                $rating['tools_todo_my_job'] = $this->ratingCalculation(count($tools_todo_my_job), $tools_todo_my_job);
                $return_data["rating"] = $rating;
                /* rating */
                $return_data['review'] = strval($rating_info->count());
                $follow_count = FacilityFollows::where(['facility_id' => $facility->id])->count();
                $return_data['followers'] = strval($follow_count);

                $this->check = "1";
                $this->message = "Facility settings data listed successfully";
                $this->return_data = $return_data;
            } else {
                $this->message = "Facility not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function notificationFacility(Request $request)
    {

        $created_by = (isset($request->user_id) && $request->user_id != "") ? $request->user_id : "";

        $ret = Offer::whereIn('job_id', function ($query) use ($created_by) {
            $query->select('id')
                ->from(with(new Job)->getTable())
                // ->whereIn('category_id', ['223', '15'])
                ->where('created_by', $created_by)
                ->where('active', '1');
        })->where('is_view', false)
            ->where('expiration', '>=', date('m/d/Y H:i:s'))
            ->orderBy('created_at', 'desc');

        if ($ret->count() > 0) {
            $n = [];
            $notifications = $ret->get();
            foreach ($notifications as $notification) {
                $user = USER::where(['id' => $notification->nurse->user_id])->first();
                $n[] = [
                    "notification_id" => $notification->id, "message" => "You have sent a new offer to " . $user->first_name . ' ' . $user->last_name . " that matches your <b style='color:#2BE3BD'> " . \App\Providers\AppServiceProvider::keywordTitle($notification->job->preferred_specialty) . " </b> job assignment preference and or profile."
                ];
            }
            $this->check = "1";
            $this->message = "Notifications has been listed successfully";
            $this->return_data = $n;
        } else {
            $this->check = "1";
            $this->message = "Currently there are no notifications";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobInformation(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            // 'worker_id' => 'required',
            'api_key' => 'required',
            'job_id' => 'required'
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            
            $whereCond = [
                    'facilities.active' => true,
                    'jobs.id' => $request->job_id
                ];

            $respond = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.*', 'offers.job_id as job_id', 'facilities.name as facility_name', 'facilities.city as facility_city', 'facilities.state as facility_state', 'jobs.created_at as posted_on')
                            ->leftJoin('offers','offers.job_id', '=', 'jobs.id')
                            ->leftJoin('facilities','jobs.facility_id', '=', 'facilities.id')
                            ->where($whereCond);
            $job_data = $respond->groupBy('jobs.id')->first();
            
            if(empty($job_data)){
                $whereCond1 =  [
                    'facilities.active' => true,
                    'jobs.id' => $request->job_id,
                ];
                
                $worker_jobs = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.*', 'offers.job_id as job_id', 'facilities.name as facility_name', 'facilities.city as facility_city', 'facilities.state as facility_state', 'jobs.created_at as posted_on')
                
                ->leftJoin('offers','offers.job_id', '=', 'jobs.id')
                ->leftJoin('facilities','jobs.facility_id', '=', 'facilities.id')
                ->where($whereCond1)->groupBy('jobs.id')->first();
                $job_data['workers_applied'] = $worker_jobs['workers_applied'];
                $job_data['worker_contract_termination_policy'] = $worker_jobs['contract_termination_policy'];
                $job_data['job_id'] = $worker_jobs['job_id'];
                $job_data['facility_name'] = $worker_jobs['facility_name'];
                $job_data['facility_city'] = $worker_jobs['facility_city'];
                $job_data['facility_state'] = $worker_jobs['facility_state'];
                // $job_data['posted_on'] = $worker_jobs['posted_on'];
            }
            $job_data['posted_on'] = $job_data['created_at'];
            if(isset($job_data['recruiter_id']) && !empty($job_data['recruiter_id'])){
                $recruiter_info = USER::where('id', $job_data['recruiter_id'])->get()->first();
                $recruiter_name = $recruiter_info->first_name.' '.$recruiter_info->last_name;
            }else{
                $recruiter_name = '';
            }

            $job = Job::select(DB::raw("(SELECT COUNT(id) AS applied_people FROM offers WHERE offers.job_id=jobs.id) as workers_applied"), 'jobs.*')->where('id', $request->job_id)->first();
            // Check total job hire
            $is_vacancy = DB::select("SELECT COUNT(id) as hired_jobs, job_id FROM `offers` WHERE status = 'Onboarding' AND job_id = ".'"'.$job['id'].'"');
            if(isset($is_vacancy)){
                $is_vacancy = $is_vacancy[0]->hired_jobs;
            }else{
                $is_vacancy = '0';
            }
            $worker_reference_name = '';
            $worker_reference_title ='';
            $worker_reference_recency_reference ='';
            
            // Jobs speciality with experience 
            $speciality = explode(',',$job['preferred_specialty']);
            $experiences = explode(',',$job['preferred_experience']);
            $exp = [];
            $spe = [];
            $specialities = [];
            $i = 0;
            foreach($speciality as $special){
                $spe[] = $special;
                $i++;
            }
            foreach($experiences as $experience){
                $exp[] = $experience;
            }
            
            for($j=0; $j< $i; $j++){
                $specialities[$j]['spe'] = $spe[$j]; 
                $specialities[$j]['exp'] = $exp[$j]; 
            }

            // Jobs Certificate
            $certificate = explode(',',$job['certificate']); 
            $worker_certificate = [];
            // $skills_checklists = [];
            $vaccinations = explode(',',$job['vaccinations']);
            $worker_vaccination = json_decode($job_data['worker_vaccination']);
            $worker_certificate_name = json_decode($job_data['worker_certificate_name']);
            $worker_certificate = json_decode($job_data['worker_certificate']);
            $skills_checklists = explode(',', $job_data['skills_checklists']);
            $i=0;
            foreach($skills_checklists as $rec)
            {
                if(isset($rec) && !empty($rec)){
                    $i++;
                    $skills_checklists[$i] = url('public/images/nurses/skill/'.$rec);
                }
                
            }
            // $vacc_image = NurseAsset::where(['filter' => 'vaccination', 'nurse_id' => $worker->id])->get();
            // $cert_image = NurseAsset::where(['filter' => 'certificate', 'nurse_id' => $worker->id])->get();
            $vacc_image = '';
            $cert_image = '';
            $certificate = explode(',',$job['certificate']); 
            if(isset($job_data['recruiter_id'])){
                $recruiter_info = User::where('id', $job_data['recruiter_id'])->first();
            }else{
                $recruiter_info = User::where('id', $job['recruiter_id'])->first();
            }

            $result = [];
            $result['job_id'] = isset($job['id'])?$job['id']:"";
            $result['description'] = isset($job['description'])?$job['description']:"";
            $result['posted_on'] = isset($job_data['posted_on'])?$job_data['posted_on']:"";
            $result['type'] = isset($job['type'])?$job['type']:"";
            $result['terms'] = isset($job['terms'])?$job['terms']:"";
            $result['job_name'] = isset($job['job_name'])?$job['job_name']:"";
            $result['total_applied'] = isset($job['workers_applied'])?$job['workers_applied']:"";
            $result['department'] = isset($job['Department'])?$job['Department']:"";
            $result['worker_name'] = isset($worker_name)?$worker_name:"";
            $result['worker_image'] = isset($worker_img)?$worker_img:"";
            $result['recruiter_name'] = $recruiter_name;
            if(isset($job_data['worked_at_facility_before']) && ($job_data['worked_at_facility_before'] == 'yes')){
                $recs = true;
            }else{
                $recs = false;
            }

            if(isset($job_data['license_type']) && ($job_data['license_type'] != null) && ($job_data['profession'] == $job_data['license_type'])){
                $profession = true;
            }else{
                $profession = false;
            }
            if(isset($job_data['specialty']) && ($job_data['specialty'] != null) && ($job_data['preferred_specialty'] == $job_data['specialty'])){
                $speciality = true;
            }else{
                $speciality = false;
            }
            if(isset($job_data['experience']) && ($job_data['experience'] != null) && ($job_data['preferred_experience'] == $job_data['experience'])){
                $experience = true;
            }else{
                $experience = false;
            }
            $countable = explode(',',$worker_reference_name);
            $num = [];
            foreach($countable as $rec){
                if(!empty($rec)){
                    $num[] = $rec;        
                }
            }
            $countable = count($num);
            if($job_data['number_of_references'] == $countable){
                $worker_ref_num = true;
            }else{
                $worker_ref_num = false;
            }

            $worker_info = [];
            // $data =  [];
            $data['job'] = 'College Diploma';
            $data['match'] = !empty($job_data['diploma'])?true:false;
            $data['worker'] = !empty($job_data['diploma'])?url('public/images/nurses/diploma/'.$job_data['diploma']):"";
            $data['name'] = 'Diploma';
            $data['update_key'] = 'diploma';
            $data['type'] = 'files';
            $data['worker_title'] = 'Did you really graduate?';
            $data['job_title'] = 'College Diploma Required';
            $data['worker_image'] = !empty($job_data['diploma'])?url('public/images/nurses/diploma/'.$job_data['diploma']):"";
            $worker_info[] = $data;
            $data['worker_image'] = '';

            $data['job'] = 'Drivers License';
            $data['match'] = !empty($job_data['driving_license'])?true:false;
            $data['worker'] = !empty($job_data['driving_license'])?url('public/images/nurses/driving_license/'.$job_data['driving_license']):"";
            $data['name'] = 'driving_license';
            $data['update_key'] = 'driving_license';
            $data['type'] = 'files';
            $data['worker_title'] = 'Are you really allowed to drive?';
            $data['job_title'] = 'Drivers License';
            $data['worker_image'] = !empty($job_data['driving_license'])?url('public/images/nurses/driving_license/'.$job_data['driving_license']):"";
            $worker_info[] = $data;
            $data['worker_image'] = '';

            $data['job'] = !empty($job['job_worked_at_facility_before'])?$job['job_worked_at_facility_before']:"";
            $data['match'] = $recs;
            $data['worker'] = !empty($job_data['worked_at_facility_before'])?$job_data['worked_at_facility_before']:"";
            $data['name'] = 'Working at Facility Before';
            $data['update_key'] = 'worked_at_facility_before';
            $data['type'] = 'checkbox';
            $data['worker_title'] = 'Are you sure you never worked here as staff?';
            $data['job_title'] = 'Have you worked here in last 18 months?';
            $worker_info[] = $data;

            $data['job'] = "Last 4 digit of SS# to submit";
            $data['match'] = !empty($job_data['worker_ss_number'])?true:false;
            $data['worker'] = !empty($job_data['worker_ss_number'])?$job_data['worker_ss_number']:"";
            $data['name'] = 'SS Card Number';
            $data['update_key'] = 'worker_ss_number';
            $data['type'] = 'input';
            $data['worker_title'] = 'Yes we need your SS# to submit you';
            $data['job_title'] = 'Last 4 digit of SS# to submit';
            $worker_info[] = $data;

            if($job['profession'] == $job_data['highest_nursing_degree']){ $val = true; }else{ $val = false; }
            $data['job'] = isset($job['profession'])?$job['profession']:"";
            $data['match'] = $val;
            $data['worker'] = !empty($job_data['highest_nursing_degree'])?$job_data['highest_nursing_degree']:"";
            $data['name'] = 'Profession';
            $data['update_key'] = 'highest_nursing_degree';
            $data['type'] = 'dropdown';
            $data['worker_title'] = 'What kind of Professional are you?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Profession';
            $worker_info[] = $data;

            $data['job'] = isset($job['preferred_specialty'])?$job['preferred_specialty']:"";
            $data['match'] = $speciality;
            $data['worker'] = !empty($job_data['specialty'])?$job_data['specialty']:"";
            $data['name'] = 'Speciality';
            $data['update_key'] = 'specialty';
            $data['type'] = 'dropdown';
            $data['worker_title'] = "What's your specialty?";
            $data['job_title'] = !empty($data['job'])?$data['job']:'Specialty';
            $worker_info[] = $data;

            $data['job'] = isset($job['preferred_experience'])?$job['preferred_experience']:"";
            $data['match'] = $experience;
            $data['worker'] = !empty($job_data['experience'])?$job_data['experience']:"";
            $data['name'] = 'Experience';
            $data['update_key'] = 'experience';
            $data['type'] = 'input';
            $data['worker_title'] = 'How long have you done this?';
            $data['job_title'] = $data['job'].' Years';
            $worker_info[] = $data;

            if($job_data['nursing_license_state'] == $job['job_location']){ $val = true; }else{ $val = false; }
            $data['job'] = isset($job['job_location'])?$job['job_location']:"";
            $data['match'] = $val;
            $data['worker'] = !empty($job_data['nursing_license_state'])?$job_data['nursing_license_state']:"";
            $data['name'] = 'License State';
            $data['update_key'] = 'nursing_license_state';
            $data['type'] = 'dropdown';
            $data['worker_title'] = 'Where are you licensed?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Professional Licensure';
            $worker_info[] = $data;

            $i = 0;
            foreach($vaccinations as $job_vacc)
            {
                $data['job'] = isset($vaccinations[$i])?$vaccinations[$i]:"Vaccinations & Immunizations";
                $data['match'] = !empty($worker_vaccination[$i])?true:false;
                $data['worker'] = isset($worker_vaccination[$i])?$worker_vaccination[$i]:"";
                $data['worker_image'] = isset($vacc_image[$i]['name'])?url('public/images/nurses/vaccination/'.$vacc_image[$i]['name']):"";
                $data['name'] = $data['worker'].' vaccination';
                $data['match_title'] = 'Vaccinations & Immunizations';
                $data['update_key'] = 'worker_vaccination';
                $data['type'] = 'file';
                $data['worker_title'] = 'Did you get the '.$data['worker'].' Vaccines?';
                $data['job_title'] = !empty($data['job'])?$data['job'].' Required':'Vaccinations & Immunizations';
                $worker_info[] = $data;
                $i++;
                // $data['worker_image'] = '';
            }
            $data['worker_image'] = '';

            // $data['job'] = isset($vaccinations[0])?$vaccinations[0]:"Covid";
            // $data['match'] = !empty($worker_vaccination[0])?true:false;
            // $data['worker'] = isset($worker_vaccination[0])?$worker_vaccination[0]:"";
            // $data['name'] = 'Covid vaccination';
            // $data['update_key'] = 'covid';
            // $data['type'] = 'file';
            // $data['worker_title'] = 'Did you get the COVID Vaccines?';
            // $data['job_title'] = !empty($data['job'])?$data['job'].' Required':'Vaccinations & Immunizations name';
            // $worker_info[] = $data;

            // $data['job'] = isset($vaccinations[1])?$vaccinations[1]:"Flu";
            // $data['match'] = !empty($worker_vaccination[1])?true:false;
            // $data['worker'] = isset($worker_vaccination[1])?$worker_vaccination[1]:"";
            // $data['name'] = 'Flu vaccination';
            // $data['update_key'] = 'flu';
            // $data['type'] = 'file';
            // $data['worker_title'] = 'Did you get the Flu Vaccines?';
            // $data['job_title'] = !empty($data['job'])?$data['job'].' Required':'Vaccinations & Immunizations name';
            // $worker_info[] = $data;

            $data['job'] = isset($job['number_of_references'])?$job['number_of_references']:"";
            $data['match'] = $worker_ref_num;
            $data['worker'] = isset($worker_reference_name)?$worker_reference_name:"";
            $data['name'] = 'Reference';
            $data['update_key'] = 'worker_reference_name';
            $data['type'] = 'multiple';
            $data['worker_title'] = 'Who are your References?';
            $data['job_title'] = !empty($data['job'])?$data['job'].' References':'number of references';
            $worker_info[] = $data;

            $data['job'] = isset($job['min_title_of_reference'])?$job['min_title_of_reference']:"";
            $data['match'] = !empty($worker_reference_title)?true:false;
            $data['worker'] = isset($worker_reference_title)?$worker_reference_title:"";
            $data['name'] = 'Reference title';
            $data['update_key'] = 'worker_reference_title';
            $data['type'] = 'multiple';
            $data['worker_title'] = 'What was their title?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'min title of reference';
            $worker_info[] = $data;

            $data['job'] = isset($job['recency_of_reference'])?$job['recency_of_reference']:"";
            $data['match'] = !empty($worker_reference_recency_reference)?true:false;
            $data['worker'] = isset($worker_reference_recency_reference)?$worker_reference_recency_reference:"";
            $data['name'] = 'Recency Reference Assignment';
            $data['update_key'] = 'worker_reference_recency_reference';
            $data['type'] = 'multiple';
            $data['worker_title'] = 'Is this from your last assignment?';
            $data['job_title'] = !empty($data['job'])?$data['job'].' months':'recency of reference';
            $worker_info[] = $data;

            $i = 0;
            foreach($certificate as $job_cert)
            {
                $data['job'] = isset($certificate[$i])?$certificate[$i]:"Certifications";
                $data['match'] = !empty($worker_certificate_name[$i])?true:false;
                $data['worker'] = isset($worker_certificate_name[$i])?$worker_certificate_name[$i]:"";
                if(isset($worker_certificate_name[$i])){
                    $data['worker_image'] = isset($cert_image[$i]['name'])?url('public/images/nurses/certificate/'.$cert_image[$i]['name']):"";
                }
                $data['name'] = $data['worker'];
                $data['match_title'] = 'Certifications';
                $data['update_key'] = 'worker_certificate';
                $data['type'] = 'file';
                $data['worker_title'] = 'No '.$data['worker'];
                $data['job_title'] = !empty($data['job'])?$data['job'].' Required':'Certifications';
                $worker_info[] = $data;
                $i++;
            }
            $data['worker_image'] = '';

            // $data['job'] = isset($certificate[0])?$certificate[0]:"";
            // $data['match'] = !empty($job_data['BLS'])?true:false;
            // $data['worker'] = isset($job_data['BLS'])?$job_data['BLS']:"";
            // $data['name'] = 'BLS';
            // $data['update_key'] = 'BLS';
            // $data['type'] = 'file';
            // $data['worker_title'] = "You don't have a BLS?";
            // $data['job_title'] = !empty($data['job'])?$data['job'].' Required':'Certifications BLS';
            // $worker_info[] = $data;

            // $data['job'] = isset($certificate[1])?$certificate[1]:"";
            // $data['match'] = !empty($job_data['ACLS'])?true:false;
            // $data['worker'] = isset($job_data['ACLS'])?$job_data['ACLS']:"";
            // $data['name'] = 'ACLS';
            // $data['update_key'] = 'ACLS';
            // $data['type'] = 'file';
            // $data['worker_title'] = "No ACLS?";
            // $data['job_title'] = !empty($data['job'])?$data['job'].' Required':'Certifications ACLS';
            // $worker_info[] = $data;

            // $data['job'] = isset($certificate[3])?$certificate[3]:"";
            // $data['match'] = !empty($job_data['PALS'])?true:false;
            // $data['worker'] = isset($job_data['PALS'])?$job_data['PALS']:"";
            // $data['name'] = 'PALS';
            // $data['update_key'] = 'PALS';
            // $data['type'] = 'file';
            // $data['worker_title'] = "No PALS?";
            // $data['job_title'] = !empty($data['job'])?$data['job'].' Required':'Certifications PALS';
            // $worker_info[] = $data;

            // $data['job'] = isset($certificate[2])?$certificate[2]:"";
            // $data['match'] = !empty($job_data['other'])?true:false;
            // $data['worker'] = isset($job_data['other'])?$job_data['other']:"";
            // $data['name'] = 'other';
            // $data['update_key'] = 'other';
            // $data['type'] = 'file';
            // $data['worker_title'] = "No CCRN?";
            // $data['job_title'] = !empty($data['job'])?$data['job'].' Required':'Certifications other';
            // $worker_info[] = $data;

            $data['job'] = isset($job['skills'])?$job['skills']:"";
            $data['match'] = !empty($job_data['skills_checklists'])?true:false;
            $data['worker'] = isset($job_data['skills_checklists'])?$job_data['skills_checklists']:"";
            if(isset($job_data['skills'])){
                $data['worker_image'] = isset($skills_checklists)?$skills_checklists[0]:"";
            }
            $data['name'] = 'Skills';
            $data['update_key'] = 'skills_checklists';
            $data['type'] = 'file';
            $data['worker_title'] = 'Upload your latest skills checklist';
            $data['job_title'] = $data['job'].' Skills checklist';
            $worker_info[] = $data;

            if($job_data['eligible_work_in_us'] == 'yes'){ $eligible_work_in_us = true; }else{ $eligible_work_in_us = false; }
            $data['job'] = "Eligible work in the us";
            $data['match'] = $eligible_work_in_us;
            $data['worker'] = isset($job_data['eligible_work_in_us'])?$job_data['eligible_work_in_us']:"";
            $data['name'] = 'eligible_work_in_us';
            $data['update_key'] = 'eligible_work_in_us';
            $data['type'] = 'checkbox';
            $data['worker_title'] = 'Does Congress allow you to work here?';
            $data['job_title'] = 'Eligible to work in the US Required?';
            $worker_info[] = $data;

            $data['job'] = isset($job['urgency'])?$job['urgency']:"";
            if(isset($data['job']) && $data['job'] == '1'){ $data['job'] = 'Auto Offer'; }else{
                $data['job'] = 'Urgency';
            }
            // $data['job_title'] = $data['job'];
            $data['job_title'] = !empty($job['urgency'])?$job['urgency']:"Urgency";
            $data['match'] = !empty($job_data['worker_urgency'])?true:false;
            $data['worker'] = isset($job_data['worker_urgency'])?$job_data['worker_urgency']:"";
            $data['name'] = 'worker_urgency';
            $data['update_key'] = 'worker_urgency';
            $data['type'] = 'input';
            $data['worker_title'] = "How quickly can you be ready to submit?";
            $worker_info[] = $data;

            $data['job'] = isset($job['position_available'])?$job['position_available']:"";
            $data['match'] = !empty($job_data["available_position"])?true:false;
            $data['worker'] = isset($job_data["available_position"])?$job_data["available_position"]:"";
            $data['name'] = 'available_position';
            $data['update_key'] = 'available_position';
            $data['type'] = 'input';
            $data['worker_title'] = 'You have applied to # jobs?';
            $data['job_title'] = !empty($data['job'])?$is_vacancy.' of '.$data['job']:'# of Positions Available';
            $worker_info[] = $data;

            $data['job'] = isset($job['msp'])?$job['msp']:"";
            $data['match'] = !empty($job_data['MSP'])?true:false;
            $data['worker'] = isset($job_data['MSP'])?$job_data['MSP']:"";
            $data['name'] = 'MSP';
            $data['update_key'] = 'MSP';
            $data['worker_title'] = 'Any MSPs you prefer to avoid?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'MSP';
            $data['type'] = 'dropdown';
            $worker_info[] = $data;

            $data['job'] = isset($job['vms'])?$job['vms']:"";
            $data['match'] = !empty($job_data['VMS'])?true:false;
            $data['worker'] = isset($job_data['VMS'])?$job_data['VMS']:"";
            $data['name'] = 'VMS';
            $data['update_key'] = 'VMS';
            $data['type'] = 'dropdown';
            $data['worker_title'] = "Who's your favorite VMS?";
            $data['job_title'] = !empty($data['job'])?$data['job']:'VMS';
            $worker_info[] = $data;

            $data['job'] = isset($job['submission_of_vms'])?$job['submission_of_vms']:"";
            $data['match'] = !empty($job_data['submission_VMS'])?true:false;
            $data['worker'] = isset($job_data['submission_VMS'])?$job_data['submission_VMS']:"";
            $data['name'] = 'submission_VMS';
            $data['update_key'] = 'submission_VMS';
            $data['type'] = 'input';
            $data['worker_title'] = '# of Submissions in VMS';
            $data['job_title'] = (isset($data['job']) && !empty($data['job']))?$data['job']:'# of Submissions in VMS';
            $worker_info[] = $data;

            $data['job'] = isset($job['block_scheduling'])?$job['block_scheduling']:"";
            $data['match'] = !empty($job_data['worker_block_scheduling'])?true:false;
            $data['worker'] = isset($job_data['worker_block_scheduling'])?$job_data['worker_block_scheduling']:"";
            $data['name'] = 'Block_scheduling';
            $data['update_key'] = 'block_scheduling';
            $data['type'] = 'input';
            $data['worker_title'] = 'Do you want block scheduling?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Block Scheduling';
            $worker_info[] = $data;

            if($job_data['worker_float_requirement'] == 'Yes'){ $val = true; }else{ $val = false; }
            $data['job'] = isset($job['float_requirement'])?$job['float_requirement']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_float_requirement'])?$job_data['worker_float_requirement']:"";
            $data['name'] = 'Float Requirement';
            $data['update_key'] = 'float_requirement';
            $data['type'] = 'checkbox';
            $data['worker_title'] = 'Are you willing float to?';
            $data['job_title'] = !empty($job['float_requirement'])?$job['float_requirement']:'Float requirements';
            $worker_info[] = $data;
            
            $data['job'] = isset($job['facility_shift_cancelation_policy'])?$job['facility_shift_cancelation_policy']:"";
            $data['match'] = !empty($job_data['worker_facility_shift_cancelation_policy'])?true:false;
            $data['worker'] = isset($job_data['worker_facility_shift_cancelation_policy'])?$job_data['worker_facility_shift_cancelation_policy']:"";
            $data['name'] = 'Facility Shift Cancelation Policy';
            $data['update_key'] = 'facility_shift_cancelation_policy';
            $data['type'] = 'dropdown';
            $data['worker_title'] = 'What terms do you prefer?';
            $data['job_title'] = !empty($job['facility_shift_cancelation_policy'])?$job['facility_shift_cancelation_policy']:'Facility Shift Cancellation Policy';
            $worker_info[] = $data;

            $data['job'] = isset($job['contract_termination_policy'])?$job['contract_termination_policy']:"";
            $data['match'] = !empty($job_data['worker_contract_termination_policy'])?true:false;
            $data['worker'] = isset($job_data['worker_contract_termination_policy'])?$job_data['worker_contract_termination_policy']:"";
            $data['name'] = 'Contract Terminology';
            $data['update_key'] = 'contract_termination_policy';
            $data['type'] = 'dropdown';
            $data['worker_title'] = 'What terms do you prefer?';
            $data['job_title'] = !empty($job['contract_termination_policy'])?$job['contract_termination_policy']:'Contract Termination Policy';
            $worker_info[] = $data;

            if(isset($job_data['distance_from_your_home']) && ($job_data['distance_from_your_home'] != 0) ){
                $data['worker'] = $job_data['distance_from_your_home'];
            }else{
                $data['worker'] = "";
            }
            if($job['traveler_distance_from_facility'] == $job_data['distance_from_your_home']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['traveler_distance_from_facility'])?$job['traveler_distance_from_facility']:"";
            $data['match'] = $val;
            // $data['worker'] = isset($job_data['distance_from_your_home'])?$job_data['distance_from_your_home']:"";
            $data['name'] = 'distance from facility';
            $data['update_key'] = 'distance_from_your_home';
            $data['type'] = 'input';
            $data['worker_title'] = 'Where does the IRS think you live?';
            $data['job_title'] = !empty($data['job'])?$data['job'].' miles Distance From Facility':'Traveler Distance From Facility';
            $worker_info[] = $data;

            $data['job'] = isset($job['facility'])?$job['facility']:"";
            $data['match'] = !empty($job_data['facilities_you_like_to_work_at'])?true:false;
            $data['worker'] = isset($job_data['facilities_you_like_to_work_at'])?$job_data['facilities_you_like_to_work_at']:"";
            $data['name'] = 'Facility available upon request';
            $data['update_key'] = 'facilities_you_like_to_work_at';
            $data['type'] = 'dropdown';
            $data['worker_title'] = 'What facilities have you worked at?';
            $data['job_title'] = !empty($job['facility'])?$job['facility']:'Facility';
            $worker_info[] = $data;

            $data['job'] = isset($job['facilitys_parent_system'])?$job['facilitys_parent_system']:"";
            $data['match'] = !empty($job_data['worker_facility_parent_system'])?true:false;
            $data['worker'] = isset($job_data['worker_facility_parent_system'])?$job_data['worker_facility_parent_system']:"";
            $data['name'] = 'facility parent system';
            $data['update_key'] = 'worker_facility_parent_system';
            $data['type'] = 'input';
            $data['worker_title'] = 'What facilities would you like to work at?';
            $data['job_title'] = !empty($job['facilitys_parent_system'])?$job['facilitys_parent_system']:"Facility's Parent System";
            $worker_info[] = $data;

            if(isset($job_data['avg_rating_by_facilities']) && ($job_data['avg_rating_by_facilities'] != 0) ){
                $data['worker'] = $job_data['avg_rating_by_facilities'];
            }else{
                $data['worker'] = "";
            }
            $data['job'] = isset($job['facility_average_rating'])?$job['facility_average_rating']:"";
            $data['match'] = !empty($job_data['avg_rating_by_facilities'])?true:false;
            // $data['worker'] = isset($job_data['avg_rating_by_facilities'])?$job_data['avg_rating_by_facilities']:"";
            $data['name'] = 'facility average rating';
            $data['update_key'] = 'avg_rating_by_facilities';
            $data['type'] = 'input';
            $data['worker_title'] = 'Your average rating by your facilities';
            $data['job_title'] = !empty($job['facility_average_rating'])?$job['facility_average_rating']:'Facility Average Rating';
            $worker_info[] = $data;

            if(isset($job_data['worker_avg_rating_by_recruiters']) && ($job_data['worker_avg_rating_by_recruiters'] != 0) ){
                $data['worker'] = $job_data['worker_avg_rating_by_recruiters'];
            }else{
                $data['worker'] = "";
            }
            $data['job'] = isset($job['recruiter_average_rating'])?$job['recruiter_average_rating']:"";
            $data['match'] = !empty($job_data['worker_avg_rating_by_recruiters'])?true:false;
            // $data['worker'] = isset($job_data['worker_avg_rating_by_recruiters'])?$job_data['worker_avg_rating_by_recruiters']:"";
            $data['name'] = 'recruiter average rating';
            $data['update_key'] = 'worker_avg_rating_by_recruiters';
            $data['type'] = 'input';
            $data['worker_title'] = 'Your average rating by your recruiters';
            $data['job_title'] = !empty($job['recruiter_average_rating'])?$job['recruiter_average_rating']:'Recruiter Average Rating';
            $worker_info[] = $data;

            if(isset($job_data['worker_avg_rating_by_employers']) && ($job_data['worker_avg_rating_by_employers'] != 0) ){
                $data['worker'] = $job_data['worker_avg_rating_by_employers'];
            }else{
                $data['worker'] = "";
            }
            $data['job'] = isset($job['employer_average_rating'])?$job['employer_average_rating']:"";
            $data['match'] = !empty($job_data['worker_avg_rating_by_employers'])?true:false;
            // $data['worker'] = isset($job_data['worker_avg_rating_by_employers'])?$job_data['worker_avg_rating_by_employers']:"";
            $data['name'] = 'employer average rating';
            $data['update_key'] = 'worker_avg_rating_by_employers';
            $data['type'] = 'input';
            $data['worker_title'] = 'Your average rating by your employers';
            $data['job_title'] = !empty($job['employer_average_rating'])?$job['employer_average_rating']:'Employer Average Rating';
            $worker_info[] = $data;

            if($job['clinical_setting'] == $job_data['clinical_setting_you_prefer']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['clinical_setting'])?$job['clinical_setting']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['clinical_setting_you_prefer'])?$job_data['clinical_setting_you_prefer']:"";
            $data['name'] = 'Clinical Setting';
            $data['update_key'] = 'clinical_setting_you_prefer';
            $data['type'] = 'dropdown';
            $data['worker_title'] = 'What setting do you prefer?';
            $data['job_title'] = (isset($data['job']) && !empty($data['job']))?$data['job']:' Clinical Setting';
            $worker_info[] = $data;
            
            $data['job'] = isset($job['Patient_ratio'])?$job['Patient_ratio']:"";
            $data['match'] = !empty($job_data['worker_patient_ratio'])?true:false;
            $data['worker'] = isset($job_data['worker_patient_ratio'])?$job_data['worker_patient_ratio']:"";
            $data['name'] = 'patient ratio';
            $data['update_key'] = 'worker_patient_ratio';
            $data['type'] = 'input';
            $data['worker_title'] = 'How many patients can you handle?';
            $data['job_title'] = !empty($job['Patient_ratio'])?$job['Patient_ratio']:'Patient ratio';
            $worker_info[] = $data;

            $data['job'] = isset($job['emr'])?$job['emr']:"";
            $data['match'] = !empty($job_data['worker_emr'])?true:false;
            $data['worker'] = isset($job_data['worker_emr'])?$job_data['worker_emr']:"";
            $data['name'] = 'EMR';
            $data['update_key'] = 'worker_emr';
            $data['type'] = 'dropdown';
            $data['worker_title'] = 'What EMRs have you used?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'EMR';
            $worker_info[] = $data;

            $data['job'] = isset($job['Unit'])?$job['Unit']:"";
            $data['match'] = !empty($job_data['worker_unit'])?true:false;
            $data['worker'] = isset($job_data['worker_unit'])?$job_data['worker_unit']:"";
            $data['name'] = 'Unit';
            $data['update_key'] = 'worker_unit';
            $data['type'] = 'input';
            $data['worker_title'] = 'Fav Unit?';
            $data['job_title'] = !empty($job['Unit'])?$job['Unit']:'Unit';
            $worker_info[] = $data;

            $data['job'] = isset($job['Department'])?$job['Department']:"";
            $data['match'] = !empty($job_data['worker_department'])?true:false;
            $data['worker'] = isset($job_data['worker_department'])?$job_data['worker_department']:"";
            $data['name'] = 'Department';
            $data['update_key'] = 'worker_department';
            $data['type'] = 'input';
            $data['worker_title'] = 'Fav department?';
            $data['job_title'] = !empty($job['Department'])?$job['Department']:'Department';
            $worker_info[] = $data;

            $data['job'] = isset($job['Bed_Size'])?$job['Bed_Size']:"";
            $data['match'] = !empty($job_data['worker_bed_size'])?true:false;
            $data['worker'] = isset($job_data['worker_bed_size'])?$job_data['worker_bed_size']:"";
            $data['name'] = 'Bed Size';
            $data['update_key'] = 'worker_bed_size';
            $data['type'] = 'input';
            $data['worker_title'] = 'king or california king ?';
            $data['job_title'] = !empty($job['Bed_Size'])?$job['Bed_Size']:'Bed Size';
            $worker_info[] = $data;

            $data['job'] = isset($job['Trauma_Level'])?$job['Trauma_Level']:"";
            $data['match'] = !empty($job_data['worker_trauma_level'])?true:false;
            $data['worker'] = isset($job_data['worker_trauma_level'])?$job_data['worker_trauma_level']:"";
            $data['name'] = 'Trauma Level';
            $data['update_key'] = 'worker_trauma_level';
            $data['type'] = 'input';
            $data['worker_title'] = 'Ideal trauma level?';
            $data['job_title'] = !empty($job['Trauma_Level'])?$job['Trauma_Level']:'Trauma Level';
            $worker_info[] = $data;

            if($job['scrub_color'] == $job_data['worker_scrub_color']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['scrub_color'])?$job['scrub_color']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_scrub_color'])?$job_data['worker_scrub_color']:"";
            $data['name'] = 'Scrub color';
            $data['update_key'] = 'worker_scrub_color';
            $data['type'] = 'input';
            $data['worker_title'] = 'Fav Scrub Brand?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Scrub Color';
            $worker_info[] = $data;

            if($job['job_state'] == $job_data['worker_facility_state_code']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['job_state'])?$job['job_state']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_facility_state_code'])?$job_data['worker_facility_state_code']:"";
            $data['name'] = 'Facility state';
            $data['update_key'] = 'worker_facility_state_code';
            $data['type'] = 'dropdown';
            $data['worker_title'] = "States you'd like to work?";
            $data['job_title'] = !empty($data['job'])?$data['job']:'Facility State Code';
            $worker_info[] = $data;

            if($job['job_city'] == $job_data['worker_facility_city']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['job_city'])?$job['job_city']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_facility_city'])?$job_data['worker_facility_city']:"";
            $data['name'] = 'Facility City';
            $data['update_key'] = 'worker_facility_city';
            $data['type'] = 'dropdown';
            $data['worker_title'] = "cities you'd like to work?";
            $data['job_title'] = !empty($data['job'])?$data['job']:'Facility City';
            $worker_info[] = $data;

            $data['job'] = "InterviewDates";
            $data['match'] = !empty($job_data['worker_interview_dates'])?true:false;
            $data['worker'] = isset($job_data['worker_interview_dates'])?$job_data['worker_interview_dates']:"";
            $data['name'] = 'Interview dates';
            $data['update_key'] = 'worker_interview_dates';
            $data['type'] = 'Interview dates';
            $data['worker_title'] = "Any days you're not available?";
            $data['job_title'] = 'InterviewDates';
            $worker_info[] = $data;

            if(isset($job['as_soon_as']) && ($job['as_soon_as'] == '1')){
                $data['job'] = "As Soon As";
            }else{
                $data['job'] = isset($job['start_date'])?$job['start_date']:"";
            }
            if(isset($job_data['worker_as_soon_as_posible']) && ($job_data['worker_as_soon_as_posible'] == '1')){
                $data['worker'] = "As Soon As";
            }else{
                $data['worker'] = isset($job_data['worker_start_date'])?$job_data['worker_start_date']:"";
            }
            if($data['worker'] == $data['job']){ $data['match'] = true;}else{ $data['match'] = false; }
            $data['name'] = 'As Soon As';
            $data['update_key'] = 'worker_as_soon_as_posible';
            $data['type'] = 'checkbox';
            $data['worker_title'] = 'When can you start?';
            $data['job_title'] = (isset($data['job']) && !empty($data['job']))?$data['job']:'Start Date';
            $worker_info[] = $data;

            if($job['rto'] == $job_data['worker_rto']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['rto'])?$job['rto']:"";
            $data['match'] = $val;
            $data['worker'] = "";
            $data['name'] = 'RTO';
            $data['update_key'] = 'clinical_setting_you_prefer';
            $data['type'] = 'input';
            $data['worker_title'] = !empty($data['worker'])?$data['worker']:'Any time off?';
            $data['job_title'] = (isset($data['job']) && !empty($data['job']))?$data['job']:'RTO';
            $worker_info[] = $data;

            if($job['preferred_shift'] == $job_data['worker_shift_time_of_day']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['preferred_shift'])?$job['preferred_shift']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_shift_time_of_day'])?$job_data['worker_shift_time_of_day']:"";
            $data['name'] = 'Shift';
            $data['update_key'] = 'worker_shift_time_of_day';
            $data['type'] = 'dropdown';
            $data['worker_title'] = 'Fav Shift?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Shift Time of Day';
            $worker_info[] = $data;


            if($job['hours_per_week'] == $job_data['worker_hours_per_week']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['hours_per_week'])?$job['hours_per_week']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_hours_per_week'])?$job_data['worker_hours_per_week']:"";
            $data['name'] = 'Hours/week';
            $data['update_key'] = 'worker_hours_per_week';
            $data['type'] = 'input';
            $data['worker_title'] = 'Ideal Hours per week?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Hours/Week';
            $worker_info[] = $data;

            if($job['guaranteed_hours'] == $job_data['worker_guaranteed_hours']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['guaranteed_hours'])?$job['guaranteed_hours']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_guaranteed_hours'])?$job_data['worker_guaranteed_hours']:"";
            $data['name'] = 'Guaranteed Hours';
            $data['update_key'] = 'worker_guaranteed_hours';
            $data['type'] = 'input';
            $data['worker_title'] = 'Open to jobs with no guaranteed hours?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Guaranteed Hours';
            $worker_info[] = $data;

            if($job['hours_shift'] == $job_data['worker_hours_shift']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['hours_shift'])?$job['hours_shift']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_hours_shift'])?$job_data['worker_hours_shift']:"";
            $data['name'] = 'Shift Hours';
            $data['update_key'] = 'worker_hours_shift';
            $data['type'] = 'input';
            $data['worker_title'] = 'Prefered hours per shift?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Hours/Shift';
            $worker_info[] = $data;

            if($job['preferred_assignment_duration'] == $job_data['worker_weeks_assignment']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['preferred_assignment_duration'])?$job['preferred_assignment_duration']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_weeks_assignment'])?$job_data['worker_weeks_assignment']:"";
            $data['name'] = 'Assignment in weeks';
            $data['update_key'] = 'worker_weeks_assignment';
            $data['type'] = 'input';
            $data['worker_title'] = 'How many weeks?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Weeks/Assignment';
            $worker_info[] = $data;

            if($job['weeks_shift'] == $job_data['worker_shifts_week']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['weeks_shift'])?$job['weeks_shift']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_shifts_week'])?$job_data['worker_shifts_week']:"";
            $data['name'] = 'Shift Week';
            $data['update_key'] = 'worker_shifts_week';
            $data['type'] = 'input';
            $data['worker_title'] = 'ideal shifts per week?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Shifts/Week';
            $worker_info[] = $data;

            if($job['referral_bonus'] == $job_data['worker_referral_bonus']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['referral_bonus'])?$job['referral_bonus']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_referral_bonus'])?$job_data['worker_referral_bonus']:"";
            $data['name'] = 'Refferel Bonus';
            $data['update_key'] = 'worker_referral_bonus';
            $data['type'] = 'input';
            $data['worker_title'] = '# of people you have referred';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Referral Bonus';
            $worker_info[] = $data;

            if(($job['sign_on_bonus'] == $job_data['worker_sign_on_bonus']) && (!empty($job_data['worker_sign_on_bonus']))){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['sign_on_bonus'])?$job['sign_on_bonus']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_sign_on_bonus'])?$job_data['worker_sign_on_bonus']:"";
            $data['name'] = 'Sign On Bonus';
            $data['update_key'] = 'worker_sign_on_bonus';
            $data['type'] = 'input';
            $data['worker_title'] = 'What kind of bonus do you expect?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Sign-On Bonus';
            $worker_info[] = $data;

            if($job['completion_bonus'] == $job_data['worker_completion_bonus']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['completion_bonus'])?$job['completion_bonus']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_completion_bonus'])?$job_data['worker_completion_bonus']:"";
            $data['name'] = 'Completion Bonus';
            $data['update_key'] = 'worker_completion_bonus';
            $data['type'] = 'input';
            $data['worker_title'] = 'What kind of bonus do you deserve?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Completion Bonus';
            $worker_info[] = $data;

            if($job['extension_bonus'] == $job_data['worker_extension_bonus']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['extension_bonus'])?$job['extension_bonus']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_extension_bonus'])?$job_data['worker_extension_bonus']:"";
            $data['name'] = 'extension bonus';
            $data['update_key'] = 'worker_extension_bonus';
            $data['type'] = 'input';
            $data['worker_title'] = 'What are you comparing this too?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Extension Bonus';
            $worker_info[] = $data;

            $data['job'] = isset($job['other_bonus'])?$job['other_bonus']:"";
            $data['match'] = !empty($job_data['worker_other_bonus'])?true:false;
            $data['worker'] = isset($job_data['worker_other_bonus'])?$job_data['worker_other_bonus']:"";
            $data['name'] = 'Other Bonus';
            $data['update_key'] = 'worker_other_bonus';
            $data['type'] = 'input';
            $data['worker_title'] = 'Other bonuses you want?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Other Bonus';
            $worker_info[] = $data;

            $data['job'] = isset($job['four_zero_one_k'])?$job['four_zero_one_k']:"";
            $data['match'] = !empty($job_data['how_much_k'])?true:false;
            $data['worker'] = isset($job_data['how_much_k'])?$job_data['how_much_k']:"";
            $data['name'] = '401k';
            $data['update_key'] = 'how_much_k';
            $data['type'] = 'dropdown';
            $data['worker_title'] = 'How much do you want this?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'401K';
            $worker_info[] = $data;

            $data['job'] = isset($job['health_insaurance'])?$job['health_insaurance']:"";
            $data['match'] = !empty($job_data['worker_health_insurance'])?true:false;
            $data['worker'] = isset($job_data['worker_health_insurance'])?$job_data['worker_health_insurance']:"";
            $data['name'] = 'Health Insaurance';
            $data['update_key'] = 'worker_health_insurance';
            $data['type'] = 'dropdown';
            $data['worker_title'] = 'How much do you want this?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Health Insurance';
            $worker_info[] = $data;

            $data['job'] = isset($job['dental'])?$job['dental']:"";
            $data['match'] = !empty($job_data['worker_dental'])?true:false;
            $data['worker'] = isset($job_data['worker_dental'])?$job_data['worker_dental']:"";
            $data['name'] = 'Dental';
            $data['update_key'] = 'worker_dental';
            $data['type'] = 'dropdown';
            $data['worker_title'] = 'How much do you want this?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Dental';
            $worker_info[] = $data;

            $data['job'] = isset($job['vision'])?$job['vision']:"";
            $data['match'] = !empty($job_data['worker_vision'])?true:false;
            $data['worker'] = isset($job_data['worker_vision'])?$job_data['worker_vision']:"";
            $data['name'] = 'Vision';
            $data['update_key'] = 'worker_vision';
            $data['type'] = 'dropdown';
            $data['worker_title'] = 'How much do you want this?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Vision';
            $worker_info[] = $data;

            if($job['actual_hourly_rate'] == $job_data['worker_actual_hourly_rate']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['actual_hourly_rate'])?$job['actual_hourly_rate']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_actual_hourly_rate'])?$job_data['worker_actual_hourly_rate']:"";
            $data['name'] = 'Actually Rate';
            $data['update_key'] = 'worker_actual_hourly_rate';
            $data['type'] = 'input';
            $data['worker_title'] = 'What range is fair?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Actual Hourly rate';
            $worker_info[] = $data;

            if($job['feels_like_per_hour'] == $job_data['worker_feels_like_hour']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['feels_like_per_hour'])?$job['feels_like_per_hour']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_feels_like_hour'])?$job_data['worker_feels_like_hour']:"";
            $data['name'] = 'feels/$hr';
            $data['update_key'] = 'worker_feels_like_hour';
            $data['type'] = 'input';
            $data['worker_title'] = 'Does this seem fair based on the market?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Feels Like $/hr';
            $worker_info[] = $data;

            $data['job'] = isset($job['overtime'])?$job['overtime']:"";
            $data['match'] = !empty($job_data['worker_overtime'])?true:false;
            $data['worker'] = isset($job_data['worker_overtime'])?$job_data['worker_overtime']:"";
            $data['name'] = 'Overtime';
            $data['update_key'] = 'worker_overtime';
            $data['type'] = 'checkbox';
            $data['worker_title'] = 'Would you work more overtime for a higher OT rate?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Overtime';
            $worker_info[] = $data;

            $data['job'] = isset($job['holiday'])?$job['holiday']:"";
            $data['match'] = !empty($job_data['worker_holiday'])?true:false;
            $data['worker'] = isset($job_data['worker_holiday'])?$job_data['worker_holiday']:"";
            $data['name'] = 'Holiday';
            $data['update_key'] = 'worker_holiday';
            $data['type'] = 'date';
            $data['worker_title'] = 'Any Holiday to refuse your work?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Holiday';
            $worker_info[] = $data;

            $data['job'] = isset($job['on_call'])?$job['on_call']:"";
            $data['match'] = !empty($job_data['worker_holiday'])?true:false;
            $data['worker'] = isset($job_data['worker_on_call'])?$job_data['worker_on_call']:"";
            $data['name'] = 'On call';
            $data['update_key'] = 'worker_on_call';
            $data['type'] = 'checkbox';
            $data['worker_title'] = 'Will you do call?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'On Call';
            $worker_info[] = $data;

            $data['job'] = isset($job['call_back'])?$job['call_back']:"";
            $data['match'] = !empty($job_data['worker_call_back'])?true:false;
            $data['worker'] = isset($job_data['worker_call_back'])?$job_data['worker_call_back']:"";
            $data['name'] = 'Call Back';
            $data['update_key'] = 'worker_call_back';
            $data['type'] = 'checkbox';
            $data['worker_title'] = 'Is this rate reasonable?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Call Back';
            $worker_info[] = $data;

            $data['job'] = isset($job['orientation_rate'])?$job['orientation_rate']:"";
            $data['match'] = !empty($job_data['worker_orientation_rate'])?true:false;
            $data['worker'] = isset($job_data['worker_orientation_rate'])?$job_data['worker_orientation_rate']:"";
            $data['name'] = 'Orientation Rate';
            $data['update_key'] = 'worker_orientation_rate';
            $data['type'] = 'input';
            $data['worker_title'] = 'Is this rate reasonable?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Orientation Rate';
            $worker_info[] = $data;

            if($job['weekly_taxable_amount'] == $job_data['worker_weekly_taxable_amount']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['weekly_taxable_amount'])?$job['weekly_taxable_amount']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_weekly_taxable_amount'])?$job_data['worker_weekly_taxable_amount']:"";
            $data['name'] = 'Weekly Taxable amount';
            $data['update_key'] = 'worker_weekly_taxable_amount';
            $data['type'] = 'input';
            $data['worker_title'] = '';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Weekly Taxable Amount';
            $worker_info[] = $data;

            if($job['weekly_non_taxable_amount'] == $job_data['worker_weekly_non_taxable_amount']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['weekly_non_taxable_amount'])?$job['weekly_non_taxable_amount']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_weekly_non_taxable_amount'])?$job_data['worker_weekly_non_taxable_amount']:"";
            $data['name'] = 'Weekly Non Taxable Amount';
            $data['update_key'] = 'worker_weekly_non_taxable_amount';
            $data['type'] = 'input';
            $data['worker_title'] = 'Are you going to duplicate expenses?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Weekly non-taxable amount';
            $worker_info[] = $data;

            if($job['employer_weekly_amount'] == $job_data['worker_employer_weekly_amount']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['employer_weekly_amount'])?$job['employer_weekly_amount']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_employer_weekly_amount'])?$job_data['worker_employer_weekly_amount']:"";
            $data['name'] = 'Employer Weekly Amount';
            $data['update_key'] = 'worker_employer_weekly_amount';
            $data['type'] = 'input';
            $data['worker_title'] = 'What range is reasonable?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Employer Weekly Amount';
            $worker_info[] = $data;

            if($job['goodwork_weekly_amount'] == $job_data['worker_goodwork_weekly_amount']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['goodwork_weekly_amount'])?$job['goodwork_weekly_amount']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_goodwork_weekly_amount'])?$job_data['worker_goodwork_weekly_amount']:"";
            $data['name'] = 'Goodwork Weekly Amount';
            $data['update_key'] = 'worker_goodwork_weekly_amount';
            $data['type'] = 'input';
            $data['worker_title'] = 'You only have (count down time) left before your rate drops from 5% to 2%';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Goodwork Weekly Amount';
            $worker_info[] = $data;

            if($job['total_employer_amount'] == $job_data['worker_total_employer_amount']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['total_employer_amount'])?$job['total_employer_amount']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_total_employer_amount'])?$job_data['worker_total_employer_amount']:"";
            $data['name'] = 'Total Employer Amount';
            $data['update_key'] = 'worker_total_employer_amount';
            $data['type'] = 'input';
            $data['worker_title'] = '';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Total Employer Amount';
            $worker_info[] = $data;

            if($job['total_goodwork_amount'] == $job_data['worker_total_goodwork_amount']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['total_goodwork_amount'])?$job['total_goodwork_amount']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_total_goodwork_amount'])?$job_data['worker_total_goodwork_amount']:"";
            $data['name'] = 'Total Goodwork Amount';
            $data['update_key'] = 'worker_total_goodwork_amount';
            $data['type'] = 'input';
            $data['worker_title'] = 'How would you spend those extra funds?';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Total Goodwork Amount';
            $worker_info[] = $data;

            if($job['total_contract_amount'] == $job_data['worker_total_contract_amount']){ $val = true; }else{$val = false;}
            $data['job'] = isset($job['total_contract_amount'])?$job['total_contract_amount']:"";
            $data['match'] = $val;
            $data['worker'] = isset($job_data['worker_total_contract_amount'])?$job_data['worker_total_contract_amount']:"";
            $data['name'] = 'Total Contract Amount';
            $data['update_key'] = 'worker_total_contract_amount';
            $data['type'] = 'input';
            $data['worker_title'] = '';
            $data['job_title'] = !empty($data['job'])?$data['job']:'Total Contract Amount';
            $worker_info[] = $data;

            $data['job'] = "Goodwork Number";
            $data['match'] = !empty($job_data['worker_goodwork_number'])?true:false;
            $data['worker'] = isset($job_data['worker_goodwork_number'])?$job_data['worker_goodwork_number']:"";
            $data['name'] = 'goodwork number';
            $data['update_key'] = 'worker_goodwork_number';
            $data['type'] = 'input';
            $data['worker_title'] = '';
            $data['job_title'] = 'Goodwork Number';
            $worker_info[] = $data;

            $result['worker_info'] = $worker_info;
            

            
            $this->check = "1";
            $this->message = "Matching details listed successfully";
            // $this->return_data = $data;
            $this->return_data = $result;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function userImages(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_ids' => 'required',
            'api_key' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_ids = (isset($request->user_ids) && $request->user_ids != "") ? $request->user_ids : "";
            if ($user_ids != "") {
                $user_id = explode(",", $user_ids);
                if (is_array($user_id) && !empty($user_id)) {
                    $user_info = User::whereIn('id', $user_id);
                    if ($user_info->count() > 0) {
                        $users = $user_info->get();
                        $a = [];
                        foreach ($users as $key => $u) {
                            if ($u->role == "NURSE") {
                                $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
                                if ($u->image) {
                                    $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $u->image);
                                    if ($t) {
                                        $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $u->image);
                                    }
                                }

                                $a[] = ['id' => $u->id, 'image' => 'data:image/jpeg;base64,' . base64_encode($profileNurse)];
                            } elseif ($u->role == "FACILITYADMIN") {
                                $facility_logo = "";
                                if ($u->facilities[0]->facility_logo) {
                                    $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $u->facilities[0]->facility_logo);
                                    if ($t) {
                                        $facility_logo = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $u->facilities[0]->facility_logo);
                                    }
                                }
                                $facility_logo_base = ($facility_logo != "") ? 'data:image/jpeg;base64,' . base64_encode($facility_logo) : "";

                                $a[] = ['id' => $u->id, 'image' => $facility_logo_base];
                            }
                        }

                        $this->check = "1";
                        $this->message = "Users images listed successfully";
                        $this->return_data = $a;
                    } else {
                        $this->message = "No users found";
                    }
                } else {
                    $this->message = "Input error";
                }
            } else {
                $this->message = "User ids looks empty";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function test(Request $request)
    {
        /* $follows = new Follows();
        $user_data = Follows::where('email', '=', $request->email)->get()->first(); */

        /* $nurse = JobOffer::create([
            'job_id' => "8245d08d-732c-45bc-bbaf-e3f19bcdadfb",
            'offer_id' => "1d779d61-5be9-47e7-a6be-d1c757a7c7c1",
        ]); */
        // $nurse->save();

        /* $facility_rating = FacilityRating::create(['nurse_id' => '1d779d61-5be9-47e7-a6be-d1c757a7c7c1', 'facility_id' => '1d779d61-5be9-47e7-a6be-d1c757a7c7c1']);
        $facility_rating->save(); */

        /*  $test = (object) [];
        $messages = [
            "id.required" => "Id is required",
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $this->check = "1";
            $this->message = "Testing Response";
            $test->response = ["msg" => "test message"];
        } */
        /*$template = EmailTemplate::create([
            'label' => "choosepassword",
            'content' => "choose password content",
        ]);*/
        // $template = NurseRating::create([
        //     'label' => "choosepassword",
        //     'content' => "choose password content",
        // ]);
        // $template->save();

        /*$this->message == "NO";
        if (exists('/storage/assets/facilities/facility_logo/image20_1641315145.jpeg')) {
            $this->message == "Yes";
        }*/
        $this->return_data = "";

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getSearchStatusOptions()
    {
        $jobFunctions = $this->getSearchStatus()->pluck('title', 'id');
        $data = [];
        foreach ($jobFunctions as $key => $value) {
            $data[] = ['id' => strval($key), "name" => $value];
        }
        $this->check = "1";
        $this->message = "Search statuses has been listed successfully";
        $this->return_data = $data;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getLicenseTypeOptions()
    {
        // $jobFunctions = $this->getLicenseType()->pluck('title', 'id');
        // $data = [];
        // foreach ($jobFunctions as $key => $value) {
        //     $data[] = ['id' => strval($key), "name" => $value];
        // }
        $controller = new Controller();
        $charting = Keyword::where('filter', 'LicenseType')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Nurse license type has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getLicenseStatusOptions()
    {
        $jobFunctions = $this->getLicenseStatus()->pluck('title', 'id');
        $data = [];
        foreach ($jobFunctions as $key => $value) {
            $data[] = ['id' => strval($key), "name" => $value];
        }
        $this->check = "1";
        $this->message = "Nurse license type has been listed successfully";
        $this->return_data = $data;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function nurseLicenseDetail(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            if(isset($request->role)){
                $user = User::where('id', $request->user_id)->first();
                $nurse = Nurse::where('id', $request->nurse_id)->first();
            }else{
                $user = User::where('id', $request->user_id)->first();
                $nurse = Nurse::where('user_id', $request->user_id)->first();
            }

            $return_data = [];
            if ($user) {
                

                /*  Nurse */
                if (isset($request->nursing_license_number) && $request->nursing_license_number != "") $nurse->nursing_license_number = $request->nursing_license_number;
                if (isset($request->nursing_license_state) && $request->nursing_license_state != "") $nurse->nursing_license_state = $request->nursing_license_state;
                if (isset($request->license_expiry_date) && $request->license_expiry_date != "") $nurse->license_expiry_date = $request->license_expiry_date;
                if (isset($request->license_issue_date) && $request->license_issue_date != "") $nurse->license_issue_date = $request->license_issue_date;
                if (isset($request->license_renewal_date) && $request->license_renewal_date != "") $nurse->license_renewal_date = $request->license_renewal_date;
                if (isset($request->license_status) && $request->license_status != "") $nurse->license_status = $request->license_status;
                if (isset($request->license_type) && $request->license_type != "") $nurse->license_type = $request->license_type;
                if (isset($request->authority_Issue) && $request->authority_Issue != "") $nurse->authority_Issue = $request->authority_Issue;
                $n = $nurse->update();
                /*  Nurse */
                

                if ($n) {
                    $this->check = "1";
                    $return_data = $this->profileCompletionFlagStatus($type = "", $user);
                    $this->message = "License  details updated successfully";
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

    public function addUserActivity(Request $request){
        // echo $request->user_id;exit;
        // print_r($request->all());exit;
        if (
            isset($request->user_id) && $request->user_id != "" &&
            isset($request->activity_type) && $request->activity_type != "" &&
            isset($request->ip) && $request->ip != "" &&
            isset($request->device_type) && $request->device_type != ""
        ) {

                $insert = array(
                    "user_id" => $request->user_id,
                    'activity_type' => $request->activity_type,
                    'ip' => $request->ip,
                    'device_type' => $request->device_type,
                    'device_company' => $request->device_company,
                    'device_version' => $request->device_version,
                    'device_location' => $request->device_location,
                    'device_lat' => $request->device_lat,
                    'device_lang' => $request->device_lang
                );
                \DB::table('user_activity')->insert($insert);
            
                $this->check = "1";
                $this->message = "User activity has been added successfully";
                $this->return_data = $insert;
             
        } else {
            $this->message = $this->param_missing;
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }


    public function exploreJobList(Request $request)
    {
        $whereCond = [
            'facilities.active' => true,
            'jobs.is_open' => "1",
            'jobs.active' => '1',
            'jobs.is_closed' => "0"
        ];
        $ret = Job::select('jobs.id as job_id', 'jobs.auto_offers as auto_offers', 'jobs.*')
            ->leftJoin('facilities', function ($join) {
                $join->on('facilities.id', '=', 'jobs.facility_id');
            })
            ->where($whereCond)
            ->orderBy('jobs.created_at', 'desc');

        if (isset($request->profession) && $request->profession != "") {
            $ret->where('jobs.profession', '=', $request->profession);
        }

        if (isset($request->type) && $request->type != "") {
            $ret->where('jobs.type', '=', $request->type);
        }

        if (isset($request->preferred_specialty) && $request->preferred_specialty != "") {
            $ret->where('jobs.preferred_specialty', '=', $request->preferred_specialty);
        }

        if (isset($request->preferred_experience) && $request->preferred_experience != "") {
            $ret->where('jobs.preferred_experience', '=', $request->preferred_experience);
        }

        if (isset($request->search_location) && $request->search_location != "") $ret->search(['job_city', 'job_state'], $request->search_location);

        if(isset($request->job_type) && $request->job_type != ""){
            $ret->where('jobs.job_type', '=', $request->job_type);
        }
        
        if (isset($request->end_date) && !empty($request->end_date)) {
            $ret->where('jobs.end_date', '<=', $request->end_date);
        }

        if (isset($request->preferred_shift) && $request->preferred_shift != "") {
            $ret->where('jobs.preferred_shift', '=', $request->preferred_shift);
        }

        if (isset($request->auto_offers) && $request->auto_offers != "") {
            $ret->where('jobs.auto_offers', '=', $request->auto_offers);
        }
        
        $weekly_pay_from = (isset($request->weekly_pay_from) && $request->weekly_pay_from != "") ? $request->weekly_pay_from : "";
        $weekly_pay_to = (isset($request->weekly_pay_to) && $request->weekly_pay_to != "") ? $request->weekly_pay_to : "";
        if ($weekly_pay_from != "" && $weekly_pay_to != "") {
            $ret->where(function (Builder $query) use ($weekly_pay_from,  $weekly_pay_to) {
                $query->whereBetween('weekly_pay', array(intval($weekly_pay_from), intval($weekly_pay_to)));
            });
        }

        $hourly_pay_from = (isset($request->hourly_pay_from) && $request->hourly_pay_from != "") ? $request->hourly_pay_from : "";
        $hourly_pay_to = (isset($request->hourly_pay_to) && $request->hourly_pay_to != "") ? $request->hourly_pay_to : "";
        if ($hourly_pay_from != "" && $hourly_pay_to != "") {
            $ret->where(function (Builder $query) use ($hourly_pay_from,  $hourly_pay_to) {
                $query->whereBetween('hours_shift', array(intval($hourly_pay_from), intval($hourly_pay_to)));
            });
        }

        $hours_per_week_from = (isset($request->hours_per_week_from) && $request->hours_per_week_from != "") ? $request->hours_per_week_from : "";
        $hours_per_week_to = (isset($request->hours_per_week_to) && $request->hours_per_week_to != "") ? $request->hours_per_week_to : "";
        if ($hours_per_week_from != "" && $hours_per_week_to != "") {
            $ret->where(function (Builder $query) use ($hours_per_week_from,  $hours_per_week_to) {
                $query->whereBetween('hours_per_week', array(intval($hours_per_week_from), intval($hours_per_week_to)));
            });
        }

        $assignment_from = (isset($request->assignment_from) && $request->assignment_from != "") ? $request->assignment_from : "";
        $assignment_to = (isset($request->assignment_to) && $request->assignment_to != "") ? $request->assignment_to : "";
        if ($assignment_from != "" && $assignment_to != "") {
            $ret->where(function (Builder $query) use ($assignment_from,  $assignment_to) {
                $query->whereBetween('preferred_assignment_duration', array(intval($assignment_from), intval($assignment_to)));
            });
        }
        
        $job_data = $ret->get();
        $result = [];
        $data = [];
        $newDate = '';
        if (isset($request->start_date) && $request->start_date != "") {
            foreach($job_data as $val)
            {    
                $newDate = isset($val['start_date'])?date("Y-m-d", strtotime($val['start_date'])):'';
                if(($newDate >= $request->start_date) && ($val['start_date'] != '')){
                    $result['start_date'] = isset($val['start_date'])?$val['start_date']:"";
                    $result['job_id'] = isset($val['job_id'])?$val['job_id']:"";
                    $result['job_type'] = isset($val['job_type'])?$val['job_type']:"";
                    $result['type'] = isset($val['type'])?$val['type']:"";
                    $result['job_name'] = isset($val['job_name'])?$val['job_name']:"";
                    $result['job_location'] = isset($val['job_location'])?$val['job_location']:"";
                    $result['city'] = isset($val['job_city'])?$val['job_city']:"";
                    $result['state'] = isset($val['job_state'])?$val['job_state']:"";
                    $result['preferred_shift'] = isset($val['preferred_shift'])?$val['preferred_shift']:"";
                    $result['preferred_shift_duration'] = isset($val['preferred_shift'])?$val['preferred_shift']:"";
                    $result['preferred_assignment_duration'] = isset($val['preferred_assignment_duration'])?$val['preferred_assignment_duration']:"";
                    $result['employer_weekly_amount'] = isset($val['employer_weekly_amount'])?$val['employer_weekly_amount']:"";
                    $result['weekly_pay'] = isset($val['weekly_pay'])?$val['weekly_pay']:"";
                    $result['hours_per_week'] = isset($val['hours_per_week'])?$val['hours_per_week']:"";
                    $result['created_at'] = isset($val['created_at'])? date('d-F-Y h:i A', strtotime($val['created_at'])) :"";
                    $time_difference = time() - strtotime($val['created_at']);
                    if($time_difference > 3599){
                        $j_data["created_at_definition"] = isset($val['created_at']) ?$this->timeAgo(date(strtotime($val['created_at']))) : "";
                    }else{
                        $j_data["created_at_definition"] = isset($val['created_at']) ?'Recently Added' : "";
                    }
                    // $result['created_at_definition'] = 'Recently Added';
                    $data[] = $result;
                }
            }
        }else{
            foreach($job_data as $val)
            {    
                $result['start_date'] = isset($val['start_date'])?$val['start_date']:"";
                $result['job_id'] = isset($val['job_id'])?$val['job_id']:"";
                $result['job_type'] = isset($val['job_type'])?$val['job_type']:"";
                $result['type'] = isset($val['type'])?$val['type']:"";
                $result['job_name'] = isset($val['job_name'])?$val['job_name']:"";
                $result['job_location'] = isset($val['job_location'])?$val['job_location']:"";
                $result['city'] = isset($val['job_city'])?$val['job_city']:"";
                $result['state'] = isset($val['job_state'])?$val['job_state']:"";
                $result['preferred_shift'] = isset($val['preferred_shift'])?$val['preferred_shift']:"";
                $result['preferred_shift_duration'] = isset($val['preferred_shift'])?$val['preferred_shift']:"";
                $result['preferred_assignment_duration'] = isset($val['preferred_assignment_duration'])?$val['preferred_assignment_duration']:"";
                $result['employer_weekly_amount'] = isset($val['employer_weekly_amount'])?$val['employer_weekly_amount']:"";
                $result['weekly_pay'] = isset($val['weekly_pay'])?$val['weekly_pay']:"";
                $result['hours_per_week'] = isset($val['hours_per_week'])?$val['hours_per_week']:"";
                $result['created_at'] = isset($val['created_at'])? date('d-F-Y h:i A', strtotime($val['created_at'])) :"";
                $result['created_at_definition'] = 'Recently Added';
                $data[] = $result;
            }
        }

        $this->check = "1";
        $this->message = "Jobs listed successfully";
        $this->return_data = $data;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function saveJob(Request $request)
    {
        if (
            isset($request->nurse_id) && $request->nurse_id != "" &&
            isset($request->job_id) && $request->job_id != "" &&
            isset($request->api_key) && $request->api_key != "" 
        ) {
            if(!empty($request->role)){
                $record = NURSE::where(['id' => $request->nurse_id])->get()->first();
                $nurse_id = $record->user_id;
                $user_id = $record->user_id;
            }else{
                $nurse_id = $request->nurse_id;
                $user_id = $request->nurse_id;
            }

            $whereCond = [
                'nurse_id' => $nurse_id,
                'job_id' => $request->job_id
            ];

            $check = DB::table('job_saved')->where($whereCond)->first();

            if($check){
                // $this->message = "Job already exist";
                // DB::table('job_saved')->insert($insert);
                DB::table('job_saved')->where('id', $check->id)->delete();

                $this->check = "1";
                $this->message = "Remove saved job successfully";
            }else{
                $insert = array(
                    "nurse_id" => $nurse_id,
                    'job_id' => $request->job_id,
                    'is_save' => '1',
                    'is_delete' => '0',
                );
                DB::table('job_saved')->insert($insert);

                $this->check = "1";
                $this->message = "Job added to saved list successfully";
            }

        } else {
            $this->message = $this->param_missing;
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function removesavedJob(Request $request)
    {
        if (
            isset($request->nurse_id) && $request->nurse_id != "" &&
            isset($request->job_id) && $request->job_id != "" &&
            isset($request->api_key) && $request->api_key != "" 
        ) {
            $nurse = Nurse::where('id', '=', $request->nurse_id)->first();

            $product = \DB::table('job_saved')->where('nurse_id', $nurse->user_id)->where('job_id', $request->job_id)->first();
  
            if (!is_null($product)) {
                \DB::table('job_saved')->where('nurse_id', $nurse->user_id)->where('job_id', $request->job_id)->update(['is_delete' => '1']);
            }else{
                $insert = array(
                    "nurse_id" => $nurse->user_id,
                    'job_id' => $request->job_id,
                    'is_save' => '0',
                    'is_delete' => '1'
                );
                DB::table('job_saved')->insert($insert);
            }
            $check = \DB::table('job_saved')->where('nurse_id', $nurse->user_id)->where('job_id', $request->job_id)->first();
            if($check){
                $this->check = "1";
                $this->message = "Data removed successfully";
            }else{
                $this->message = "Data not found";
            }
             
        } else {
            $this->message = $this->param_missing;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function jobSaved(Request $request)
    {    
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            
            /*  dropdown data's */
            $controller = new Controller();
            $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
            $specialties = $controller->getSpecialities()->pluck('title', 'id');
            /*  dropdown data's */
            $nurse_info = Nurse::where(['user_id' => $request->user_id]);
            
            if ($nurse_info->count() > 0) {
                $result = array();
                $nurse = $nurse_info->first();
                $checkoffer = DB::table('blocked_users')->where('worker_id', $nurse['id'])->first();
                if(isset($checkoffer))
                {
                    $this->check = "1";
                    $this->message = "This Worker Blocked by Recruiter";
                    $this->return_data = [];
                }else{
                    $whereCond = [
                        'job_saved.nurse_id' => $request->user_id,
                        'jobs.is_closed' => "0"
                    ];

                    $limit = 10;
                    $ret = \DB::table('job_saved')
                            ->join('jobs', 'jobs.id', '=', 'job_saved.job_id')
                            ->where($whereCond)
                            ->select('jobs.*' ,'job_saved.*');
                            
                            // ->paginate($limit);
                            $jobdata = $ret->get();
                            $result = $this->my_saved_jobData($jobdata, $request->user_id);
                            // IS SAVED JOBS
                            $data = [];
                            foreach($result as $val){
                                if($val['is_saved'] != '1'){
                                    continue;
                                }
                                $data[] = $val;
                            }

                    $this->check = "1";
                    $this->message = "Saved jobs listed successfully";
                    $this->return_data = $data;
                }
            } else {
                $this->message = "Nurse not found";
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function nurseJobSaved(Request $request)
    {    
        $validator = \Validator::make($request->all(), [
            'nurse_id' => 'required',
            'api_key' => 'required',
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            /*  dropdown data's */
            $controller = new Controller();
            $assignmentDurations = $this->getAssignmentDurations()->pluck('title', 'id');
            $specialties = $controller->getSpecialities()->pluck('title', 'id');
            /*  dropdown data's */
            $nurse_info = Nurse::where(['id' => $request->nurse_id]);
            
            if ($nurse_info->count() > 0) {
                $result = array();
                $nurse = $nurse_info->get()->first();
                // $user_id = $nurse->user_id;
                
                $whereCond = [
                    'facilities.active' => true,
                    'jobs.is_open' => "1",
                    'jobs.is_closed' => "0",
                    'job_saved.nurse_id' => $request->user_id,
                    'job_saved.is_delete' => '0'
                ];

                $ret = Job::select('jobs.id as job_id', 'jobs.created_at as created_at', 'jobs.updated_at as updated_at', 'facilities.*', 'jobs.*')
                ->leftJoin('facilities', function ($join) {
                    $join->on('facilities.id', '=', 'jobs.facility_id');
                })
                ->Join('job_saved', function ($join) {
                    $join->on('job_saved.job_id', '=', 'jobs.id');
                })
                ->where($whereCond)
                ->orderBy('jobs.created_at', 'desc');

                $user_id =  isset($request->user_id)?$request->user_id:'';
                $jobdata = $ret->get();
                // $jobdata = $ret->paginate(10);
                $result = $this->save_jobData($jobdata, $user_id);
                $n_c = 0;
                foreach($result as $rec)
                {
                    $result[$n_c]['city'] = isset($rec['job_city'])?$rec['job_city']:'';
                    $result[$n_c]['state'] = isset($rec['job_state'])?$rec['job_state']:'';
                    $result[$n_c]['description'] = strip_tags($rec['description']);
                    $result[$n_c]['responsibilities'] = strip_tags($rec['responsibilities']);
                    $result[$n_c]['qualifications'] = strip_tags($rec['qualifications']);
                    $result[$n_c]['cno_message'] = strip_tags($rec['cno_message']);
                    $result[$n_c]['about_facility'] = strip_tags($rec['about_facility']);
                    $n_c++;
                }                

                $data = [];
                foreach($result as $val){
                    if($val['is_applied'] != '0'){
                        continue;
                    }
                    $data[] = $val;
                }

                $this->check = "1";
                $this->message = "Saved jobs listed successfully";
                $this->return_data = $data;
            } else {
                $this->message = "Nurse not found";
            }
        }
        
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);

    }

    public function myjobApplied(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {

                $user = $user_info->get()->first();
                $nurse_info = NURSE::where('user_id', $request->user_id);
                if ($nurse_info->count() > 0) {
                    $result = array();

                    $nurse = $nurse_info->get()->first();
                    $checkoffer = DB::table('blocked_users')->where('worker_id', $nurse['id'])->first();
                    if(isset($checkoffer))
                    {
                        $this->check = "1";
                        $this->message = "This Worker Blocked by Recruiter";
                        $this->return_data = [];
                    }else{
                        $whereCond = [
                            'facilities.active' => true,
                            // 'jobs.is_open' => "1",
                            'jobs.is_closed' => "0",
                            'offers.status' => 'Apply',
                            'offers.nurse_id' => $nurse->id
                        ];
    
                        $ret = Job::select('jobs.id as job_id', 'jobs.*', 'offers.created_at as created_at')
                            ->leftJoin('facilities', function ($join) {
                                $join->on('facilities.id', '=', 'jobs.facility_id');
                            })
                            ->join('offers', 'jobs.id', '=', 'offers.job_id')
                            ->where($whereCond)
                            ->orderBy('offers.created_at', 'desc');
                    
                            $job_data = $ret->get();
                            // $job_data = $ret->paginate(10);
    
                        $result = $this->jobData($job_data, $request->user_id);
    
                        $num = 0;
                        foreach($result as $rec){
                            $result[$num]['description'] = strip_tags($rec['description']);
                            $result[$num]['responsibilities'] = strip_tags($rec['responsibilities']);
                            $result[$num]['qualifications'] = strip_tags($rec['qualifications']);
                            $result[$num]['cno_message'] = strip_tags($rec['cno_message']);
                            $result[$num]['about_facility'] = strip_tags($rec['about_facility']);
                            $num++;
                        }
    
                        $this->check = "1";
                        $this->message = "Jobs applied successfully";
                        $this->return_data = $result;
                    }

                }else{
                    $this->check = "1";
                    $this->message = "Nurse not exist";
                }

            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    } 

    public function myjobOffered(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                
                $user = $user_info->get()->first();
                $nurse_info = NURSE::where('user_id', $request->user_id);
                if ($nurse_info->count() > 0) {
                    $result = array();
                    $nurse = $nurse_info->get()->first();
                    $checkoffer = DB::table('blocked_users')->where('worker_id', $nurse['id'])->first();
                    if(isset($checkoffer))
                    {
                        $this->check = "1";
                        $this->message = "This Worker Blocked by Recruiter";
                        $this->return_data = [];
                    }else{
                        $whereCond = [
                            'facilities.active' => true,
                            'jobs.active' => "1",
                            'jobs.is_closed' => "0",
                            'offers.status' => 'Offered',
                            'offers.nurse_id' => $nurse->id
                        ];

                        $ret = Job::select('jobs.id as job_id', 'jobs.*', 'offers.updated_ as created_at')
                            ->leftJoin('facilities', function ($join) {
                                $join->on('facilities.id', '=', 'jobs.facility_id');
                            })
                            ->join('offers', 'jobs.id', '=', 'offers.job_id')
                            ->where($whereCond)
                            ->orderBy('offers.created_at', 'desc');
                            

                        // $job_data = $ret->paginate(10);
                        $job_data = $ret->get();
                        $result = $this->jobData($job_data, $request->user_id);
                        $num = 0;
                        foreach($result as $rec){
                            $result[$num]['description'] = strip_tags($rec['description']);
                            $result[$num]['responsibilities'] = strip_tags($rec['responsibilities']);
                            $result[$num]['qualifications'] = strip_tags($rec['qualifications']);
                            $result[$num]['cno_message'] = strip_tags($rec['cno_message']);
                            $result[$num]['about_facility'] = strip_tags($rec['about_facility']);
                            $num++;
                        }
                        $this->check = "1";
                        $this->message = "Jobs listed successfully";
                        $this->return_data = $result;
                    }
                }else{
                    $this->check = "1";
                    $this->message = "Nurse not exist";
                }

            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    

    }

    public function myjobHired(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->first();
                $nurse_info = NURSE::where('user_id', $request->user_id);
                if ($nurse_info->count() > 0) {
                    $result = array();

                    $nurse = $nurse_info->get()->first();
                    $checkoffer = DB::table('blocked_users')->where('worker_id', $nurse['id'])->first();
                    if(isset($checkoffer))
                    {
                        $this->check = "1";
                        $this->message = "This Worker Blocked by Recruiter";
                        $this->return_data = [];
                    }else{
                        
                        $whereCond = [
                            'facilities.active' => true,
                            'jobs.active' => "1",
                            'jobs.is_closed' => "0",
                            'offers.status' => 'Onboarding',
                            'offers.nurse_id' => $nurse->id
                        ];

                        // new code
                        // $ret = DB::table('jobs')
                        $ret = Job::where($whereCond)
                        ->leftJoin('facilities', 'facilities.id', '=', 'jobs.facility_id')
                        ->leftJoin('offers', 'jobs.id', '=', 'offers.job_id')
                        ->select('offers.status AS offers_status','offers.id AS offer_id', 'jobs.created_at AS start_date', 'jobs.id as job_id', 'jobs.*', 'jobs.created_at as created_at')
                        
                        ->orderBy('jobs.created_at', 'desc');
                        // $jobdata = $ret->paginate(10);
                        $jobdata = $ret->get();
                        $result = [];
                        foreach($jobdata as $rec){
                            $res['type'] = $rec['type'];
                            $res['job_id'] = $rec['job_id'];
                            $res['worker_id'] = $nurse->id;
                            $res['worker_user_id'] = $nurse->user_id;
                            $res['job_name'] = $rec['job_name'];
                            $res['Worker_name'] = $nurse->first_name.' '.$nurse->last_name;
                            $res['profession'] = $rec['profession'];
                            $res['specialty'] = $rec['preferred_specialty'];
                            $res['experience'] = $rec['preferred_experience'];
                            $res['weekly_pay'] = $rec['employer_weekly_amount'];
                            $res['preferred_assignment_duration'] = $rec['preferred_assignment_duration'];
                            $res['preferred_shift'] = $rec['preferred_shift'];
                            $res['city'] = $rec['job_city'];
                            $res['state'] = $rec['job_state'];
                            
                            $res['created_at_definition'] = isset($rec['created_at']) ? "Start Date: " .date('M j, Y', strtotime($rec['created_at'])) : "";
                            $result[] =$res;
                        }
                        
                        
                        $this->check = "1";
                        $this->message = "Hired Jobs listed successfully";
                        $this->return_data = $result;
                    }
                }else{
                    $this->check = "1";
                    $this->message = "Nurse not exist";
                }

            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    
    }

    public function myjobPast(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $user_info = USER::where('id', $request->user_id);
            if ($user_info->count() > 0) {
                $user = $user_info->get()->first();
                $nurse_info = NURSE::where('user_id', $request->user_id);
                if ($nurse_info->count() > 0) {
                    $result = array();
                    $nurse = $nurse_info->get()->first();
                    $checkoffer = DB::table('blocked_users')->where('worker_id', $nurse['id'])->first();
                    if(isset($checkoffer))
                    {
                        $this->check = "1";
                        $this->message = "This Worker Blocked by Recruiter";
                        $this->return_data = [];
                    }else{
                        $whereCond = [
                            'facilities.active' => true,
                            'offers.status' => 'Done',
                            'jobs.is_closed' => "0",
                            'offers.nurse_id' => $nurse->id
                        ];
                        $ret = Job::select('offers.id AS offer_id','jobs.id as job_id', 'jobs.*', 'offers.start_date as start_date', 'offers.expiration as end_date', 'jobs.created_at as created_at')
                            ->leftJoin('facilities', function ($join) {
                                $join->on('facilities.id', '=', 'jobs.facility_id');
                            })
                            ->join('offers', 'jobs.id', '=', 'offers.job_id')
                            // ->where('jobs.end_date', '<', date('Y-m-d') )
                            // ->where('offers.expiration', '<', date('Y-m-d') )
                            ->where($whereCond)
                            ->orderBy('offers.created_at', 'desc');
                        // $job_data = $ret->paginate(10);
                        $job_data = $ret->get();

                        $result = [];
                        foreach($job_data as $rec){
                            $res['type'] = $rec['type'];
                            $res['job_id'] = $rec['job_id'];
                            $res['worker_id'] = $nurse->id;
                            $res['worker_user_id'] = $nurse->user_id;
                            $res['job_name'] = $rec['job_name'];
                            $res['Worker_name'] = $nurse->first_name.' '.$nurse->last_name;
                            $res['profession'] = $rec['profession'];
                            $res['specialty'] = $rec['preferred_specialty'];
                            $res['experience'] = $rec['preferred_experience'];
                            $res['weekly_pay'] = $rec['employer_weekly_amount'];
                            $res['preferred_assignment_duration'] = $rec['preferred_assignment_duration'];
                            $res['preferred_shift'] = $rec['preferred_shift'];
                            $res['city'] = $rec['job_city'];
                            $res['state'] = $rec['job_state'];
                            
                            $res['start_date'] = isset($rec['start_date']) ? "Start Date: " .date('M j Y', strtotime($rec['start_date'])) : "";
                            $res['end_date'] = isset($rec['end_date']) ? "End Date: " .date('M j Y', strtotime($rec['end_date'])) : "";
                            $res['posted_on'] = isset($rec['created_at']) ?date('M j Y', strtotime($rec['created_at'])) : "";
                            $result[] =$res;
                        }
                        // $result = $this->jobData($job_data, $request->user_id);
                        // $num = 0;
                        // foreach($result as $rec){
                        //     $result[$num]['description'] = strip_tags($rec['description']);
                        //     $result[$num]['responsibilities'] = strip_tags($rec['responsibilities']);
                        //     $result[$num]['qualifications'] = strip_tags($rec['qualifications']);
                        //     $result[$num]['cno_message'] = strip_tags($rec['cno_message']);
                        //     $result[$num]['about_facility'] = strip_tags($rec['about_facility']);
                        //     $num++;
                        // }
                        $this->check = "1";
                        $this->message = "Past Jobs listed successfully";
                        $this->return_data = $result;
                    }
                }else{
                    $this->check = "1";
                    $this->message = "Nurse not exist";
                }
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function nursepersonalDetail(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $nurse = Nurse::where('user_id', $request->user_id)->first();
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $return_data = [];
            if ($user) {
                /* User */
                if (isset($request->date_of_birth) && $request->date_of_birth != "") $user->date_of_birth = $request->date_of_birth;
                if (isset($request->driving_license) && $request->driving_license != "") $user->driving_license = $request->driving_license;
                if (isset($request->security_number) && $request->security_number != "") $user->security_number = $request->security_number;
                
                $u = $user->update();
                /* User */

                

                if ($u) {
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


    // delete Nurse
    public function deleteNurse(Request $request)
    {
        $nurse = Nurse::where('user_id', $request->user_id)->first();
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $return_data = [];
           
            if ($nurse) {   
                $this->check = "1";
                $nurse_id = $nurse->id;
                $nurse_deleted = DB::table('nurses')->where('id', '=', $nurse_id)->delete();
                $user_deleted = DB::table('users')->where('id', '=', $request->user_id)->delete();
                // In job saved table nurse_id is user_id
                DB::table('job_saved')->where('nurse_id', '=', $request->user_id)->delete();
                DB::table('nurse_assets')->where('nurse_id', '=', $nurse_id)->delete();
                DB::table('nurse_references')->where('nurse_id', '=', $nurse_id)->delete();
                DB::table('offers')->where('nurse_id', '=', $nurse_id)->delete();
                DB::table('nurse_ratings')->where('nurse_id', '=', $nurse_id)->delete();
                DB::table('notifications')->where('created_by', '=', $nurse_id)->delete();
                if($nurse_deleted && $user_deleted){
                    $this->message = "Nurse record deleted successfully";
                }else{
                    $this->message = "Nurse record not deleted";
                }
                
            } else {
                $this->message = "Nurse not exists";
            }
            $this->return_data = $return_data;
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    } 

    public function nurseEducationDetail(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'api_key' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {

            if(isset($request->role)){
                $user = User::where('id', $request->user_id)->first();
                $nurse = Nurse::where('id', $request->nurse_id)->first();
            }else{
                $user = User::where('id', $request->user_id)->first();
                $nurse = Nurse::where('user_id', $request->user_id)->first();
            }

            $return_data = [];
            $is_completion = 0;
            if ($user) {
                
                /*  Nurse */
                if (isset($request->college_uni_name) && $request->college_uni_name != "") $nurse->college_uni_name = $request->college_uni_name;
                if (isset($request->study_area) && $request->study_area != "") $nurse->study_area = $request->study_area;
                if (isset($request->graduation_date) && $request->graduation_date != "") $nurse->graduation_date = $request->graduation_date;
                // if (isset($request->highest_nursing_degree ) && $request->highest_nursing_degree  != "") $nurse->highest_nursing_degree  = $request->highest_nursing_degree ;
                if (isset($request->highest_nursing_degree ) && $request->highest_nursing_degree  != ""){
                    
                    // nurse degree changed into id
                    if($request->highest_nursing_degree == 'Master of Science in Nursing (MSN)'){
                        $nurse->highest_nursing_degree  = '23';
                    }else if($request->highest_nursing_degree == 'Associate Degree in Nursing (ADN)'){
                        $nurse->highest_nursing_degree  = '21';
                    }else if($request->highest_nursing_degree == 'Bachelor of Science in Nursing (BSN)'){
                        $nurse->highest_nursing_degree  = '22';
                    }else{
                        $nurse->highest_nursing_degree  = '24';
                    }
                // end nurse degree convert into id
                } 
                
                $n = $nurse->update();
                /*  Nurse */
                
                if ($n) {
                    $this->check = "1";
                    $return_data = $this->profileCompletionFlagStatus($type = "", $user);
                    $this->message = "Education  details updated successfully";
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

    public function addnurseExperienceDetail(Request $request)
    {
        $messages = [
            "user_id.required" => "user_id is required",
            "type.required" => "Select Experience type",
            "type.exists" => "Selected Experience type does not exist",
            "start_date.date" => "Start Date is not valid",
            "end_date.required" => "Enter End Date",
            "end_date.date" => "Ennd Date is not valid",
            "end_date.after" => "End Date should be after Start Date.",
        ];

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required|numeric|exists:keywords,id',
            'start_date' => 'required|date',
            'end_date' => "required|date|after:start_date",
            'api_key' => 'required',
        ], $messages);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            if(isset($request->role)){
                $nurse = Nurse::where('id', '=', $request->nurse_id)->first();
            }else{
                $nurse = Nurse::where('user_id', '=', $request->user_id)->first();
            }
            
            $check = Experience::where('nurse_id', '=', $nurse->id)->get()->first();
            if(isset($check)){
                $this->check = "1";
                $this->message = "Nurse record already exist";
                $this->return_data = $check;
            }else{

                if (isset($nurse)) {
    
                    /* experience */
                    $add_array = [
                        'nurse_id' => $nurse->id,
                        'type' => $request->type,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        "position_title" => $request->position_title,
                        "unit" => $request->unit,
                        "is_current_job" => $request->is_current_job,
                    ];
                    $experience = Experience::create($add_array);
    
                    
                    /* experience */
    
                    if ($experience == true) {
                        $cert_ret = Experience::where('id', '=', $experience->id)->first();
    
                        /* experience data */
                        $experiences = $this->getExperienceTypes()->pluck('title', 'id');
                        $cert_data["experience_id"] = (isset($cert_ret->id) && $cert_ret->id != "") ? $cert_ret->id : "";
                        $cert_data["nurse_id"] = (isset($cert_ret->nurse_id) && $cert_ret->nurse_id != "") ? $cert_ret->nurse_id : "";
                        $cert_data["type"] = (isset($cert_ret->type) && $cert_ret->type != "") ? $cert_ret->type : "";
                        $cert_data["type_definition"] = (isset($experiences[$cert_ret->type]) && $experiences[$cert_ret->type] != "") ? $experiences[$cert_ret->type] : "";
                        $cert_data["position_title"] = (isset($cert_ret->position_title) && $cert_ret->position_title != "") ? $cert_ret->position_title : "";
                        $cert_data["start_date"] = (isset($cert_ret->start_date) && $cert_ret->start_date != "") ?  date('m/d/Y', strtotime($cert_ret->start_date)) : "";
                        $cert_data["end_date"] = (isset($cert_ret->end_date) && $cert_ret->end_date != "") ?  date('m/d/Y', strtotime($cert_ret->end_date)) : "";
                        $cert_data["unit"] = (isset($cert_ret->unit) && $cert_ret->unit != "") ? $cert_ret->unit : "";
                        $cert_data["created_at"] = (isset($cert_ret->created_at) && $cert_ret->created_at != "") ?  date('m/d/Y H:i:s', strtotime($cert_ret->created_at)) : "";
                        $cert_data["is_current_job"] = (isset($cert_ret->is_current_job) && $cert_ret->is_current_job != "") ?  $cert_ret->is_current_job : "";
                        /* experience data */
    
                        $this->check = "1";
                        $this->message = "Experience added successfully";
                        $this->return_data = $cert_data;
                    } else {
                        $this->message = "Problem occurred while updating experience, Please try again later";
                    }
                } else {
                    $this->message = "Nurse not found";
                }
            }

            
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
   

    }

    public function editnurseExperienceDetail(Request $request)
    {
        $messages = [
            "user_id.required" => "user_id is required",
            "type.required" => "Select Experience type",
            "type.exists" => "Selected Experience type does not exist",
            "start_date.date" => "Start Date is not valid",
            "end_date.required" => "Enter End Date",
            "end_date.date" => "Ennd Date is not valid",
            "end_date.after" => "End Date should be after Start Date.",
        ];

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required|numeric|exists:keywords,id',
            'start_date' => 'required|date',
            'end_date' => "required|date|after:start_date",
            'api_key' => 'required',
            'experience_id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $nurse_info = Nurse::where('user_id', '=', $request->user_id);

            if ($nurse_info->count() > 0) {
                $nurse = $nurse_info->first();

                /* experience */
                $experience_array = [
                    'nurse_id' => $nurse->id,
                    'type' => $request->type,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    "position_title" => $request->position_title,
                    "unit" => $request->unit,
                    "is_current_job" => $request->is_current_job,
                ];
                $experience = Experience::where(['id' => $request->experience_id])->update($experience_array);

                
                /* experience */

                if ($experience == true) {
                    $cert_ret = Experience::where('id', '=', $request->experience_id)->first();

                    /* experience data */
                    $experiences = $this->getExperienceTypes()->pluck('title', 'id');
                    $cert_data["experience_id"] = (isset($cert_ret->id) && $cert_ret->id != "") ? $cert_ret->id : "";
                    $cert_data["nurse_id"] = (isset($cert_ret->nurse_id) && $cert_ret->nurse_id != "") ? $cert_ret->nurse_id : "";
                    $cert_data["type"] = (isset($cert_ret->type) && $cert_ret->type != "") ? $cert_ret->type : "";
                    $cert_data["type_definition"] = (isset($experiences[$cert_ret->type]) && $experiences[$cert_ret->type] != "") ? $experiences[$cert_ret->type] : "";
                    $cert_data["position_title"] = (isset($cert_ret->position_title) && $cert_ret->position_title != "") ? $cert_ret->position_title : "";
                    $cert_data["start_date"] = (isset($cert_ret->start_date) && $cert_ret->start_date != "") ?  date('m/d/Y', strtotime($cert_ret->start_date)) : "";
                    $cert_data["end_date"] = (isset($cert_ret->end_date) && $cert_ret->end_date != "") ?  date('m/d/Y', strtotime($cert_ret->end_date)) : "";
                    $cert_data["unit"] = (isset($cert_ret->unit) && $cert_ret->unit != "") ? $cert_ret->unit : "";
                    $cert_data["created_at"] = (isset($cert_ret->created_at) && $cert_ret->created_at != "") ?  date('m/d/Y H:i:s', strtotime($cert_ret->created_at)) : "";
                    $cert_data["is_current_job"] = (isset($cert_ret->is_current_job) && $cert_ret->is_current_job != "") ?  $cert_ret->is_current_job : "";
                    /* experience data */

                    $this->check = "1";
                    $this->message = "Experience added successfully";
                    $this->return_data = $cert_data;
                } else {
                    $this->message = "Problem occurred while updating experience, Please try again later";
                }
            } else {
                $this->message = "Nurse not found";
            }
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
   



    }

    public function experienceTpesOptions(Request $request){
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $experience = $this->getExperienceTypes()->pluck('title', 'id');
            $data = [];
            foreach ($experience as $key => $value) {
                $data[] = ['id' => $key, "name" => $value];
            }
            $this->check = "1";
            $this->message = "Experience types has been listed successfully";
            $this->return_data = $data;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function getfacilities(Request $request) 
    {
        $facilities = Facility::where('active','=', true)->select('id','name')->get();

        $this->check = "1";
        $this->message = "Employer has been listed successfully";
        $this->return_data = $facilities;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    
    public function browse_facilities(Request $request)
    {
        if (isset($request->facility_id) && $request->facility_id != "") {
            $whereCond = ['facilities.id' => $request->facility_id, 'facilities.active' => true];
        } else {
            // $whereCond = ['facilities.active' => true, 'jobs.is_open' => "1"];
            $whereCond = ['facilities.active' => true];
        }

        $ret = Facility::select('facilities.id as facility_id', 'facilities.*', 'jobs.preferred_specialty')
            ->leftJoin('jobs', function ($join) {
                $join->on('facilities.id', '=', 'jobs.facility_id');
            })
            ->where($whereCond);

        if (isset($request->facility_type) && $request->facility_type  != "") {
            $type = $request->facility_type;
            $ret->where(function (Builder $query) use ($type) {
                $query->whereIn('type', $type);
            });
        }

        if (isset($request->electronic_medical_records) && $request->electronic_medical_records != "") {
            $electronic_medical_records = $request->electronic_medical_records;
            $ret->where(function (Builder $query) use ($electronic_medical_records) {
                $query->whereIn('f_emr', $electronic_medical_records);
            });
        }

        /* name search for api */
        if (isset($request->search_keyword) && $request->search_keyword != "") {
            $search_keyword = $request->search_keyword;
            $ret->search([
                'name'
            ], $search_keyword);
        }
        /* name search for api */

        /*new update jan 10*/
        $open_assignment_type = (isset($request->open_assignment_type) && $request->open_assignment_type != "") ? $request->open_assignment_type : "";
        /*if ($open_assignment_type) {
                $ret->where('jobs.preferred_specialty', '=', $open_assignment_type);
        }*/
        if ($open_assignment_type != "") {
            $ret->where(function (Builder $query) use ($open_assignment_type) {
                $query->whereIn('jobs.preferred_specialty', $open_assignment_type);
            });
        }
        /*new update jan 10*/

        /*new update jan 10*/
        /* state city and postcode new update */
        $states = (isset($request->state) && $request->state != "") ? $request->state : "";
        if (isset($states) && $states != "") {
            $getStates = States::where(['id' => $states])->get();
            if ($getStates->count() > 0) {
                $selected_state = $getStates->first();
                $name = $selected_state->name;
                $iso2 = $selected_state->iso2;
                $ret->where(function (Builder $query1) use ($name, $iso2) {
                    $query1->where('state', array($name));
                    $query1->orWhere('state', array($iso2));
                });
            }
        }

        $cities = (isset($request->city) && $request->city != "") ? $request->city : "";
        if (isset($cities) && $cities != "") {
            $getCities = Cities::where(['id' => $cities])->get();
            if ($getCities->count() > 0) {
                $selected_city = $getCities->first();
                $name = $selected_city->name;
                $ret->where(function (Builder $query1) use ($name) {
                    $query1->where('city', array($name));
                });
            }
        }

        $zipcode = (isset($request->zipcode) && $request->zipcode != "") ? $request->zipcode : "";
        if (isset($zipcode) && $zipcode != "") {
            $ret->where(function (Builder $query_zip) use ($zipcode) {
                $query_zip->where('postcode', array($zipcode));
            });
            /*$zipcode_inp = [];
                $nearest = $this->getNearestMiles($zipcode);
                if (isset($nearest['results']) && !empty($nearest['results'])) {
                    foreach ($nearest['results'] as $zipkey => $zip_res) {
                        $zipcode_inp[] = $zip_res['code'];
                    }
                }
                if (!empty($zipcode_inp)) {
                    $ret->where(function (Builder $query_zip) use ($zipcode_inp) {
                        $query_zip->whereIn('postcode', $zipcode_inp);
                    });
                } else {
                    $ret->where(function (Builder $query_zip) use ($zipcode) {
                        $query_zip->where('postcode', array($zipcode));
                    });
                }*/
        }
        /* state city and postcode new update */
        /*new update jan 10*/

        $ret->groupBy('facilities.id')->orderBy('created_at', 'desc');
        $facility_data = (isset($request->facility_id) && $request->facility_id != "") ? $ret->paginate(1) : $ret->paginate(10);
        $user_id = (isset($request->user_id) && $request->user_id != "") ? $request->user_id : "";

        $response = $this->facilityData($facility_data, $user_id);
        // $response = $this->facilityData($facility_data, $user_id = $request->user_id);

        $this->check = "1";
        $this->message = "Facilities listed below";
        $this->return_data = $response;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    
    public function facilityFollows(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'facility_id' => 'required',
            'type' => 'required',
            'api_key' => 'required',
        ]);


        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_exists = FacilityFollows::where([
                'user_id' => $request->user_id,
                'facility_id' => $request->facility_id,
            ]);
            if ($check_exists->count() > 0) {
                $follows = FacilityFollows::where([
                    'user_id' => $request->user_id,
                    'facility_id' => $request->facility_id,
                ])->update(['follow_status' => strval($request->type)]);
            } else {
                $follows = FacilityFollows::create([
                    'user_id' => $request->user_id,
                    'facility_id' => $request->facility_id,
                    'follow_status' => strval($request->type)
                ]);
            }

            $this->check = "1";
            $this->message = ($request->type == "1") ? "Followed successfully" : "Unfollowed successfully";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
    
    public function facilityLikes(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'facility_id' => 'required',
            'like' => 'required',
            'api_key' => 'required',
        ]);


        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $check_exists = FacilityFollows::where([
                'user_id' => $request->user_id,
                'facility_id' => $request->facility_id,
            ]);
            if ($check_exists->count() > 0) {
                $follows = FacilityFollows::where([
                    'user_id' => $request->user_id,
                    'facility_id' => $request->facility_id,
                ])->update(['like_status' => $request->like]);
            } else {
                $follows = FacilityFollows::create([
                    'user_id' => $request->user_id,
                    'facility_id' => $request->facility_id,
                    'like_status' => $request->like
                ]);
            }

            $this->check = "1";
            $this->message = ($request->like == "1") ? "Liked successfully" : "Disliked successfully";
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

}
