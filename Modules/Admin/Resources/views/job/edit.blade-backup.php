@extends('admin::layouts.master')

@section('css')
<style>
    /* Override Bootstrap Tags Input styles */
.bootstrap-tagsinput {
    border-radius: 0;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    vertical-align: top;
    overflow: auto;
    resize: vertical;

}

.bootstrap-tagsinput .tag {
    /* Your custom styles for each tag */
    background:blue;
}

</style>
@stop

@section('content')

<!-- page content -->
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Edit Job</h3>
        </div>

        {{-- <div class="title_right">
            <div class="col-md-5 col-sm-5  form-group pull-right top_search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">Go!</button>
                    </span>
                </div>
            </div>
        </div> --}}
    </div>
    <div class="clearfix"></div>
    <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="true">Details</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="profile-tab" data-toggle="tab" href="#offers" role="tab" aria-controls="offers" aria-selected="false">References </a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_content">
                            <br />
                            <form id="create-job-form" method="post" action="{{route('jobs.update',['id'=>$model->id])}}" data-parsley-validate class="form-horizontal form-label-left">
                                @method('PATCH')
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Job Name <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="text" required="required" value="{{$model->job_name}}" class="form-control" name="job_name">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Type <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="type" class="form-control" required="required">
                                            <option value="">Select</option>
                                            @foreach($types as $v)
                                                <option value="{{$v->title}}" {{ ($model->type == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Terms <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="terms" class="form-control" required="required">
                                            <option value="">Select</option>
                                            @foreach($terms as $v)
                                                <option value="{{$v->title}}"  {{ ($model->terms == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Recruiter</label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="recruiter_id" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($recruiters as $v)
                                                <option value="{{$v->id}}" {{ ($model->recruiter_id == $v->id) ? 'selected' :'' }}>{{$v->first_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Country</label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="country" id="country" class="form-control" onchange="get_states(this)" required="required">
                                            @foreach($countries as $c)
                                                <option value="{{$c->id}}" {{ ($usa->id == $c->id) ? 'selected': '' }}>{{$c->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Profession </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="profession" onchange="get_speciality(this)" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($professions as $v)
                                                <option value="{{$v->title}}" data-id="{{$v->id}}" {{ ($model->profession == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Speciality and Experience:</label>
                                </div>
                                <div class="speciality-content">
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Speciality </label>
                                    <div class="col-md-3 col-sm-3">
                                        <select name="specialty" id="speciality" class="form-control">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <input type="number" class="form-control" name="experience" id="experience" placeholder="Experience in years">
                                    </div>
                                    <div class="col-md-1 col-sm-1">
                                        <button type="button" onclick="add_speciality(this)"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Professional Licensure </label>
                                    <div class="col-md-4 col-sm-4">
                                        <select name="job_location" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($us_states as $v)
                                                <option value="{{$v->id}}" {{ ($model->job_location == $v->id) ? 'selected' :'' }}>{{$v->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <label class="col-form-label label-align"> Compact <input type="checkbox" class="fomm-control" value="1" name="compact" {{ ($model->compact == '1') ? 'checked': '' }}></label>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Vaccination & Immunization :</label>
                                </div>
                                <div class="vaccination-content">
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> </label>
                                    <div class="col-md-5 col-sm-5">
                                        <input name="vaccinations" id="vaccination" class="form-control" placeholder="Vaccination & Immunization Name" />
                                    </div>
                                    <div class="col-md-1 col-sm-1">
                                        <button type="button" data-type="vac" onclick="add_element(this)"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Certifications :</label>
                                </div>
                                <div class="certifications-content">
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> </label>
                                    <div class="col-md-5 col-sm-5">
                                        <input name="certificate" id="certificate" class="form-control" placeholder="Certificate Name" />
                                    </div>
                                    <div class="col-md-1 col-sm-1">
                                        <button type="button" data-type="cer" onclick="add_element(this)"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Skills </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" class="form-control" id="skills" name="skills" value="{{$model->skills}}" placeholder="Skills"  data-role="tagsinput">
                                        {{-- <textarea  class="resizable_textarea form-control"  id="skills" name="skills" value="{{$model->skills}}" placeholder="Skills"  data-role="tagsinput"> </textarea> --}}
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Urgency</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="urgency" value="{{$model->urgency}}" class="form-control" placeholder="Auto Offer"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> # Of Positions Available</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="position_available" value="{{$model->position_available}}" class="form-control" placeholder="2 of 3"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> MSP</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="msp" value="{{$model->msp}}" class="form-control" placeholder="Right Sourcing"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> VMS</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="vms" value="{{$model->vms}}" class="form-control" placeholder="Not Available"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> # Of Submissions In VMS</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="submission_of_vms" value="{{$model->submission_of_vms}}" class="form-control" placeholder="Not Available"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Block Scheduling</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="block_scheduling" value="{{$model->block_scheduling}}" class="form-control" placeholder="Not Allowed"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Float Requirements</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="float_requirement" value="{{$model->float_requirement}}" class="form-control" placeholder="Float Requirements"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Facility Shift Cancellation Policy</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="facility_shift_cancelation_policy" value="{{$model->facility_shift_cancelation_policy}}" class="form-control" placeholder="Facility Shift Cancellation Plicy"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Contract termination policy</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="contract_termination_policy" value="{{$model->contract_termination_policy}}" class="form-control" placeholder="Contract termination policy"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Traveler Distance from Facility</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="traveler_distance_from_facility" value="{{$model->traveler_distance_from_facility}}" class="form-control" placeholder="75 miles"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Facility</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="facility_id" value="{{$model->facility_id}}" class="form-control" placeholder="Available Upon Request"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Facility's Parent System</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="facilitys_parent_system" value="{{$model->facilitys_parent_system}}" class="form-control" placeholder="Facility's Parent System"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Facility Average Rating</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="facility_average_rating" value="{{$model->facility_average_rating}}" class="form-control" placeholder="Facility Average Rating"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Recruiter Average Rating</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="recruiter_average_rating" value="{{$model->recruiter_average_rating}}" class="form-control" placeholder="Recruiter Average Rating"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Employer Average Rating</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="employer_average_rating" value="{{$model->employer_average_rating}}" class="form-control" placeholder="Employer Average Rating"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Clinical Setting</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="clinical_setting" value="{{$model->clinical_setting}}" class="form-control" placeholder="School"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Patient Ratio</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="Patient_ratio" value="{{$model->Patient_ratio}}" class="form-control" placeholder="Available Upon Request"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> EMR</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="emr" value="{{$model->emr}}" class="form-control" placeholder="Not Available"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Unit</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="Unit" value="{{$model->Unit}}" class="form-control" placeholder="Enter Unit"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Department</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="Department" value="{{$model->Department}}" class="form-control" placeholder="Department"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Bed Size</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="Bed_Size" value="{{$model->Bed_Size}}" class="form-control" placeholder="Bed Size"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Trauma Level</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="Trauma_Level" value="{{$model->Trauma_Level}}" class="form-control" placeholder="Trauma Level"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Scrub Color</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="scrub_color" value="{{$model->scrub_color}}" class="form-control" placeholder="Navy Blue"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> RTO</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input name="rto" type="text" value="{{$model->rto}}" class="form-control" placeholder="Allowed"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Prefered Shift </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="preferred_shift" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($prefered_shifts as $v)
                                                <option value="{{$v->title}}" {{ ($v->title == $model->preferred_shift ) ? 'selected': '' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Guaranteed Hours</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="guaranteed_hours" value="{{$model->guaranteed_hours}}" class="form-control" placeholder="0"/>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Hours/Shift</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="hours_shift" value="{{$model->hours_shift}}" class="form-control" placeholder="12"/>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Shifts/Week</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="weeks_shift" value="{{$model->weeks_shift}}" class="form-control" placeholder="3"/>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Weeks/Assignment</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="preferred_assignment_duration" value="{{$model->preferred_assignment_duration}}" class="form-control" placeholder="13"/>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Referral Bonus($)</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="referral_bonus" value="{{$model->referral_bonus}}" class="form-control" placeholder="1000"/>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Sign On Bonus($)</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="sign_on_bonus" value="{{$model->sign_on_bonus}}" class="form-control" placeholder="1000"/>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Completion Bonus($)</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="completion_bonus" value="{{$model->completion_bonus}}" class="form-control" placeholder="5000"/>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Extension Bonus($)</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="extension_bonus" value="{{$model->extension_bonus}}" class="form-control" placeholder="1000"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Other Bonus </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="other_bonus" value="{{$model->other_bonus}}" class="form-control" placeholder="Enter Bonus"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">401K </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="four_zero_one_k" value="{{$model->four_zero_one_k}}" class="form-control" placeholder="Enter 401K"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Health Insurance </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="health_insaurance" value="{{$model->health_insaurance}}" class="form-control" placeholder="Enter Health Insurance"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Dental </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="dental" value="{{$model->dental}}" class="form-control" placeholder="Enter Dental"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Vision </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="vision" value="{{$model->vision}}" class="form-control" placeholder="Enter Vision"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Actual Hourly Rate($)</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="actual_hourly_rate" value="{{$model->actual_hourly_rate}}" class="form-control" placeholder="28"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Feels Like $/Hour</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="feels_like_per_hour" value="{{$model->feels_like_per_hour}}" class="form-control" placeholder="28"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Overtime </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="overtime" value="{{$model->overtime}}" class="form-control" placeholder="Enter Overtime"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Holiday </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="holiday" value="{{$model->holiday}}" class="form-control" placeholder="Enter Holiday"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">On Call </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="on_call" value="{{$model->on_call}}" class="form-control" placeholder="Enter On Call"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Orientation Rate($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="orientation_rate" value="{{$model->orientation_rate}}" class="form-control" placeholder="Enter Orientation Rate"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Weekly Taxable Amount($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="weekly_taxable_amount" value="{{$model->weekly_taxable_amount}}" class="form-control" placeholder="1001"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Weekly Non-Taxable Amount($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="weekly_non_taxable_amount" value="{{$model->weekly_non_taxable_amount}}" class="form-control" placeholder="1009"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Employer Weekly Amount($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="employer_weekly_amount" value="{{$model->employer_weekly_amount}}" class="form-control" placeholder="2100"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Goodwork Weekly Amount($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="goodwork_weekly_amount" value="{{$model->goodwork_weekly_amount}}" class="form-control" placeholder="105"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Total Employer Amount($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="total_employer_amount" value="{{$model->total_employer_amount}}" class="form-control" placeholder="33,300"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Total Goodwork Amount($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="total_goodwork_amount" value="{{$model->total_goodwork_amount}}" class="form-control" placeholder="1,365"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Total Contract Amount($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="total_contract_amount" value="{{$model->total_contract_amount}}" class="form-control" placeholder="34,665"/>
                                    </div>
                                </div>





                                <div class="item form-group">
                                    <label for="state" class="col-form-label col-md-3 col-sm-3 label-align">State</label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="job_state" id="state" onchange="get_cities(this)" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($us_states as $v)
                                                <option value="{{$v->id}}" {{ ($v->id == $model->job_state ) ? 'selected': '' }}>{{$v->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label for="city" class="col-form-label col-md-3 col-sm-3 label-align">City</label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="job_city" id="city" class="form-control">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label for="address" class="col-form-label col-md-3 col-sm-3 label-align">Description</label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <textarea id="description" class="resizable_textarea form-control" name="description" placeholder="Description" maxlength="255">{{$model->description}}</textarea>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Status</label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <div class="btn-group" data-toggle="buttons">
                                            <label class="btn btn-primary {{ ($model->active == '1') ? 'focus active': ''}}" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                                <input type="radio" name="active" value="1" class="join-btn" {{ ($model->active == '1') ? 'checked': ''}}> Active
                                            </label>
                                            <label class="btn btn-secondary {{ ($model->active == '0') ? 'focus active': ''}}" type="button" data-toggle-class="btn-primary" >
                                                <input type="radio" name="active" value="0" class="join-btn" {{ ($model->active == '0') ? 'checked': ''}}> &nbsp; Inactive &nbsp;
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Starting date
                                    </label>
                                    <div class="col-md-4 col-sm-4 ">
                                        <input class="date-picker form-control" name="start_date" value="{{$model->start_date}}" placeholder="dd-mm-yyyy" type="text" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                                        <script>
                                            function timeFunctionLong(input) {
                                                setTimeout(function() {
                                                    input.type = 'text';
                                                }, 60000);
                                            }
                                        </script>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <label class="col-form-label label-align"> As Soon As <input type="checkbox" class="fomm-control" value="1" name="as_soon_as" {{ ($model->as_soon_as == '1') ? 'checked': '' }}></label>
                                    </div>
                                </div>
                                <div class="ln_solid"></div>
                                <div class="item form-group">
                                    <div class="col-md-6 col-sm-6 offset-md-3 text-center">
                                        <a class="btn btn-primary" href="{{url()->previous()}}">Cancel</a>
                                        {{-- <button class="btn btn-primary" type="reset">Reset</button> --}}
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="offers" role="tabpanel" aria-labelledby="profile-tab">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_content">
                            </br>
                            <form id="job-reference-form" method="post" action="{{route('job-references',['id'=>$model->id])}}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Number of refrences </label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="number_of_references" class="form-control" onchange="references(this)">
                                            <option value="">Select</option>
                                            @for($i=1;$i<10;$i++)
                                            <option value="{{$i}}" {{($model->number_of_references == $i) ? 'selected': ''}}>{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="job-references" id="job-references">
                                    @foreach($references as $k=>$ref)
                                    @php
                                    $suffix = '';
                                    if ( $k == 0) {
                                        $suffix = 'st';
                                    }elseif($k == 1)
                                    {
                                        $suffix = 'nd';
                                    }
                                    elseif($k == 2)
                                    {
                                        $suffix = 'rd';
                                    }
                                    else{
                                        $suffix = 'th';
                                    }
                                    @endphp
                                    <div class="reference-details">
                                        <input type="hidden" value={{$ref->id}} name="old_ids[]">
                                        <div class="item form-group d-flex justify-content-center">
                                            <label class="col-form-label col-md-7 col-sm-7 label-align">Details of {{($k+1).$suffix}} Reference</label>
                                            <div class="col-md-5 col-sm-5">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Name</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" name="old_name[]" value="{{$ref->name}}" class="form-control" placeholder=""/>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Email</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="email" name="old_email[]" value="{{$ref->email}}" class="form-control" placeholder="" required="required"/>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Phone</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" name="old_phone[]" value="{{$ref->phone}}" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Date Refered</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="date" name="old_date_referred[]" value="{{$ref->date_referred}}" class="form-control" placeholder=""/>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Min Title Of Reference</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" name="old_min_title_of_reference[]" value="{{$ref->min_title_of_reference}}" class="form-control" placeholder=""/>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Recency Of Reference</label>
                                            <div class="col-md-6 col-sm-6">
                                                <select name="old_recency_of_reference[]" id="" class="form-control">
                                                    <option value="Yes" {{( $ref->recency_of_reference == 'Yes') ? 'selected': ''}}>Yes</option>
                                                    <option value="No" {{( $ref->recency_of_reference == 'No') ? 'selected': ''}}>No</option>
                                                </select>

                                            </div>
                                        </div>
                                        @php
                                        $file_name = '';
                                        $file_label = 'Upload DOC';
                                        if (!empty($ref->image)) {
                                            $string = $ref->image;
                                            $underscorePosition = strpos($string, "_");
                                            if ($underscorePosition !== false) {
                                                $file_name = substr($string, $underscorePosition + 1);
                                                $file_label .= ' (New)';
                                            }
                                        }
                                        @endphp

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> {{$file_label}}</label>
                                            @if(!empty($ref->image))
                                            <div class="col-md-4 col-sm-4">
                                                <input type="file" name="old_image[]" class="form-control" />
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <a href="{{URL::asset('uploads/frontend/jobs/images/'.$ref->image)}}" target="_blank" class="btn brn-success" data-toggle="tooltip" data-placement="bottom" title="View"><i class="fa fa-download"></i>{{$file_name}} </a>
                                            </div>
                                            @else
                                            <div class="col-md-6 col-sm-6">
                                                <input type="file" name="old_image[]" class="form-control" />
                                            </div>
                                            @endif
                                        </div>

                                    </div>
                                    @endforeach
                                </div>
                                <div class="ln_solid"></div>
                                <div class="item form-group">
                                    <div class="col-md-6 col-sm-6 offset-md-3 text-center">
                                        <a class="btn btn-primary" href="{{url()->previous()}}">Cancel</a>
                                        {{-- <button class="btn btn-primary" type="reset">Reset</button> --}}
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /page content -->

@stop
@section('js')
@php
$specialty = [];
$experience = [];
$vaccinations = [];
$certificate = [];
if(!empty($model->preferred_specialty))
{
    $specialty = explode(',', $model->preferred_specialty);
}
if(!empty($model->preferred_experience))
{
    $experience = explode(',', $model->preferred_experience);
}
if(!empty($model->vaccinations))
{
    $vaccinations = explode(',', $model->vaccinations);
}
if(!empty($model->certificate))
{
    $certificate = explode(',', $model->certificate);
}
@endphp
<script>


var speciality = {};
var vac_content = [];
var cer_content = [];

@if(count($specialty))
@for($i=0; $i<count($specialty);$i++)
speciality['{{$specialty[$i]}}'] = '{{$experience[$i]}}';
@endfor
@endif
console.log(speciality);

@if(count($vaccinations))
@for($i=0; $i<count($vaccinations);$i++)
vac_content.push('{{$vaccinations[$i]}}');
@endfor
@endif

@if(count($certificate))
@for($i=0; $i<count($certificate);$i++)
cer_content.push('{{$certificate[$i]}}');
@endfor
@endif

var dynamic_elements = {
    vac : {
        id : '#vaccination',
        name: 'Vaccination',
        listing_class : '.vaccination-content',
        items: vac_content
    },
    cer : {
        id : '#certificate',
        name: 'Certificate',
        listing_class : '.certifications-content',
        items: cer_content
    }
}

function add_speciality(obj) {
    if (!$('#speciality').val()) {
        notie.alert({
            type: 'error',
            text: '<i class="fa fa-check"></i> Select the speciality please.',
            time: 3
        });
    }else if(!$('#experience').val())
    {
        notie.alert({
            type: 'error',
            text: '<i class="fa fa-check"></i> Enter total year of experience.',
            time: 3
        });
    }else{
        if (!speciality.hasOwnProperty($('#speciality').val())) {
            speciality[$('#speciality').val()] = $('#experience').val();
            $('#experience').val('');
            $('#speciality').val('');
            list_specialities();
        }
        console.log(speciality);
    }
}

function remove_speciality(obj) {
    if (speciality.hasOwnProperty($(obj).data('key'))) {
        delete speciality[$(obj).data('key')];
        // $(obj).parent().parent().parent().remove();
        list_specialities();
        console.log(speciality);
    }
}

function list_specialities()
{
    // $('.speciality-content').empty();
    var str = '';
    var i = 1;
    for (const key in speciality) {
        if (speciality.hasOwnProperty(key)) {
            const value = speciality[key];
            str += '<div class="item form-group">';
            str += '<label class="col-form-label col-md-3 col-sm-3 label-align"></label>';
            str += '<div class="col-md-1 col-sm-1 ">';
            str += '<label class="col-form-label label-align"> '+(i++)+'.</label>';
            str += '</div>';
            str += '<div class="col-md-2 col-sm-2">';
            str += '<label class="col-form-label label-align"> '+key+'</label>';
            str += '</div>';
            str += '<div class="col-md-2 col-sm-2">';
            str += '<label class="col-form-label label-align">'+value+' </label>';
            str += '</div>';
            str += '<div class="col-md-2 col-sm-1">';
            str += '<label class="col-form-label label-align" title="delete"><button type="button" data-key="'+key+'" onclick="remove_speciality(this)"><i class="fa fa-trash"></i></button></label>';
            str += '</div>';
            str += '</div>';

        }
    }
    $('.speciality-content').html(str);
}



var vaccination = [];
function add_vaccination()
{
    if (!$('#vaccination').val()) {
        notie.alert({
            type: 'error',
            text: '<i class="fa fa-check"></i> Select the Vaccination please.',
            time: 3
        });
    }else{
        if (!vaccination.includes($('#vaccination').val())) {
            vaccination.push($('#vaccination').val());
            list_vaccination();
        }
        $('#vaccination').val('');
        console.log(vaccination);
    }
}

function remove_vaccination(obj)
{
    if (vaccination.includes($(obj).data('key'))) {
        const elementToRemove = $(obj).data('key');
        const newArray = vaccination.filter(item => item !== elementToRemove);
        vaccination = newArray;
        // $(obj).parent().parent().parent().remove();
        list_vaccination();
        console.log(vaccination);
    }
}


function list_vaccination()
{
    if (vaccination.length) {
        str = '';
        vaccination.forEach(function(value, index) {
            str += '<div class="item form-group">';
            str += '<label class="col-form-label col-md-3 col-sm-3 label-align"></label>';
            str += '<div class="col-md-1 col-sm-1 ">';
            str += '<label class="col-form-label label-align"> '+(index+1)+'.</label>';
            str += '</div>';
            str += '<div class="col-md-4 col-sm-4">';
            str += '<label class="col-form-label label-align"> '+value+'</label>';
            str += '</div>';
            str += '<div class="col-md-2 col-sm-1">';
            str += '<label class="col-form-label label-align" title="delete"><button type="button" data-key="'+value+'" onclick="remove_vaccination(this)"><i class="fa fa-trash"></i></button></label>';
            str += '</div>';
            str += '</div>';
        });
        $('.vaccination-content').html(str);
    }
}


function add_element(obj)
{
    const type = $(obj).data('type');
    if (dynamic_elements.hasOwnProperty(type)) {
        let element, id, value,name;
        element = dynamic_elements[type];
        id = element.id;
        name =  element.name;
        value = $(id).val();

        if (!value) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the '+name+' please.',
                time: 3
            });
        }else{
            if (!element.items.includes(value)) {
                element.items.push($(id).val());
                console.log(element.items);
                list_elements(type);
            }
            $(id).val('');
        }
    }
}

function remove_element(obj)
{
    const type = $(obj).data('type');
    if (dynamic_elements.hasOwnProperty(type)) {
        let element = dynamic_elements[type];

        if (element.items.includes($(obj).data('key'))) {
            const elementToRemove = $(obj).data('key');
            const newArray = element.items.filter(item => item !== elementToRemove);
            element.items = newArray;
            // $(obj).parent().parent().parent().remove();
            list_elements(type);
            console.log(element.items);
        }
    }

}

function list_elements(type)
{
    if (dynamic_elements.hasOwnProperty(type)) {
        const element = dynamic_elements[type];
        if (element.items.length) {
            str = '';
            element.items.forEach(function(value, index) {
                str += '<div class="item form-group">';
                str += '<label class="col-form-label col-md-3 col-sm-3 label-align"></label>';
                str += '<div class="col-md-1 col-sm-1 ">';
                str += '<label class="col-form-label label-align"> '+(index+1)+'.</label>';
                str += '</div>';
                str += '<div class="col-md-4 col-sm-4">';
                str += '<label class="col-form-label label-align"> '+value+'</label>';
                str += '</div>';
                str += '<div class="col-md-2 col-sm-1">';
                str += '<label class="col-form-label label-align" title="delete"><button type="button" data-type="'+type+'" data-key="'+value+'" onclick="remove_element(this)"><i class="fa fa-trash"></i></button></label>';
                str += '</div>';
                str += '</div>';
            });
            $(element.listing_class).html(str);
        }
    }
}

/* Job references count */
var old_references_count;
old_references_count = $('.job-references input[name="old_name[]"]').length;
console.log('Number: '+ old_references_count);



$(document).ready(function () {
    list_specialities();
    list_elements('vac');
    list_elements('cer');
    get_speciality($('select[name="profession"]'), false);

    $('#create-job-form').on('submit', function (event) {
        event.preventDefault();
        $('#create-job-form').parsley().reset();
        var form = $('#create-job-form');
        // Remove parsley error messages and styling from form elements
        form.find('.parsley-error').removeClass('parsley-error');
        form.find('.parsley-errors-list').remove();
        form.find('.parsley-custom-error-message').remove();
        ajaxindicatorstart();
        const url = $(this).attr('action');
        const specialities = Object.keys(speciality).join(',');
        const experiences = Object.values(speciality).join(',');
        const vaccinations = dynamic_elements.vac.items.join(',');
        const certificate = dynamic_elements.cer.items.join(',');
        var data = new FormData($(this)[0]);
        data.set('preferred_specialty', specialities);
        data.set('preferred_experience', experiences);
        data.set('vaccinations', vaccinations);
        data.set('certificate', certificate);
        console.log(data);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
                if (resp.success) {
                    notie.alert({
                        type: 'success',
                        text: '<i class="fa fa-check"></i> ' + resp.msg,
                        time: 5
                    });
                    setTimeout(() => {
                        window.location.href = resp.link;
                    }, 3000);
                }
            },
            error: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
                // Reset any previous errors
                $('#create-job-form').parsley().reset();

                if (resp.responseJSON && resp.responseJSON.errors) {
                    var errors = resp.responseJSON.errors;

                    // Loop through the errors and add classes and error messages
                    $.each(errors, function (field, messages) {
                        // Find the input element for the field
                        var inputElement = $('#create-job-form').find('[name="' + field + '"]');

                        // Add the parsley-error class to the input element
                        inputElement.addClass('parsley-error');

                        // Display the error messages near the input element
                        $.each(messages, function (index, message) {
                            inputElement.after('<ul class="parsley-errors-list filled"><li class="parsley-required">'+message+'</li></ul>');
                        });
                    });
                }
            }
        })
    });

});
</script>
<script>
    // $('#skills').tagsinput();
    $(document).ready(function () {
        $('#skills').tagsinput();
    });
</script>

@stop
