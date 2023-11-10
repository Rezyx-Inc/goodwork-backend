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
            <h3>Create Job</h3>
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
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Create Job</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        {{-- <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a class="dropdown-item" href="#">Settings 1</a>
                                </li>
                                <li><a class="dropdown-item" href="#">Settings 2</a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li> --}}
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form id="create-job-form" method="post" action="{{route('jobs.store')}}" data-parsley-validate class="form-horizontal form-label-left">

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Job Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="text" required="required" class="form-control" name="job_name">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Type <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                                <select name="type" class="form-control" required="required">
                                    <option value="">Select</option>
                                    @foreach($types as $v)
                                        <option value="{{$v->title}}">{{$v->title}}</option>
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
                                        <option value="{{$v->title}}">{{$v->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Recruiter </label>
                            <div class="col-md-6 col-sm-6 ">
                                <select name="recruiter_id" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($recruiters as $v)
                                        <option value="{{$v->id}}">{{$v->first_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Country</label>
                            <div class="col-md-6 col-sm-6 ">
                                <select name="country" id="country" class="form-control" onchange="get_states(this)" required="required">
                                    @foreach($countries as $c)
                                        <option value="{{$c->id}}" {{ ($usa->id) ? : '' }}>{{$c->name}}</option>
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
                                        <option value="{{$v->title}}" data-id="{{$v->id}}">{{$v->title}}</option>
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
                                        <option value="{{$v->id}}">{{$v->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-2">
                                <label class="col-form-label label-align"> Compact <input type="checkbox" class="fomm-control" value="1" name="compact"></label>
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
                                {{-- <input type="text" class="form-control" id="skills" name="skills" placeholder="Skills"  data-role="tagsinput"> --}}
                                <textarea  class="resizable_textarea form-control"  id="skills" name="skills" placeholder="Skills"  data-role="tagsinput"></textarea>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Urgency</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="urgency" class="form-control" placeholder="Auto Offer"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> # Of Positions Available</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="position_available" class="form-control" placeholder="2 of 3"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> MSP</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="msp" class="form-control" placeholder="Right Sourcing"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> VMS</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="vms" class="form-control" placeholder="Not Available"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> # Of Submissions In VMS</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="submission_of_vms" class="form-control" placeholder="Not Available"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Block Scheduling</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="block_scheduling" class="form-control" placeholder="Not Allowed"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Float Requirements</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="float_requirement" class="form-control" placeholder="Float Requirements"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Facility Shift Cancellation Policy</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="facility_shift_cancelation_policy" class="form-control" placeholder="Facility Shift Cancellation Plicy"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Contract termination policy</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="contract_termination_policy" class="form-control" placeholder="Contract termination policy"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Traveler Distance from Facility</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="traveler_distance_from_facility" class="form-control" placeholder="75 miles"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Facility</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="facility_id" class="form-control" placeholder="Available Upon Request"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Facility's Parent System</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="facilitys_parent_system" class="form-control" placeholder="Facility's Parent System"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Facility Average Rating</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="facility_average_rating" class="form-control" placeholder="Facility Average Rating"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Recruiter Average Rating</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="recruiter_average_rating" class="form-control" placeholder="Recruiter Average Rating"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Employer Average Rating</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="employer_average_rating" class="form-control" placeholder="Employer Average Rating"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Clinical Setting</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="clinical_setting" class="form-control" placeholder="School"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Patient Ratio</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="Patient_ratio" class="form-control" placeholder="Available Upon Request"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> EMR</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="emr" class="form-control" placeholder="Not Available"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Unit</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="Unit" class="form-control" placeholder="Enter Unit"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Department</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="Department" class="form-control" placeholder="Department"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Bed Size</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="Bed_Size" class="form-control" placeholder="Bed Size"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Trauma Level</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="Trauma_Level" class="form-control" placeholder="Trauma Level"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Scrub Color</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="scrub_color" class="form-control" placeholder="Navy Blue"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> RTO</label>
                            <div class="col-md-6 col-sm-6">
                                <input name="rto" type="text" class="form-control" placeholder="Allowed"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Prefered Shift </label>
                            <div class="col-md-6 col-sm-6 ">
                                <select name="preferred_shift" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($prefered_shifts as $v)
                                        <option value="{{$v->title}}">{{$v->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Guaranteed Hours</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="guaranteed_hours" class="form-control" placeholder="0"/>
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Hours/Shift</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="hours_shift" class="form-control" placeholder="12"/>
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Shifts/Week</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="weeks_shift" class="form-control" placeholder="3"/>
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Weeks/Assignment</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="preferred_assignment_duration" class="form-control" placeholder="13"/>
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Referral Bonus($)</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="referral_bonus" class="form-control" placeholder="1000"/>
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Sign On Bonus($)</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="sign_on_bonus" class="form-control" placeholder="1000"/>
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Completion Bonus($)</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="completion_bonus" class="form-control" placeholder="5000"/>
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Extension Bonus($)</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="extension_bonus" class="form-control" placeholder="1000"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Other Bonus </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="other_bonus" class="form-control" placeholder="Enter Bonus"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">401K </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="four_zero_one_k" class="form-control" placeholder="Enter 401K"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Health Insurance </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="health_insaurance" class="form-control" placeholder="Enter Health Insurance"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Dental </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="dental" class="form-control" placeholder="Enter Dental"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Vision </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="vision" class="form-control" placeholder="Enter Vision"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Actual Hourly Rate($)</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="actual_hourly_rate" class="form-control" placeholder="28"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Feels Like $/Hour</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="feels_like_per_hour" class="form-control" placeholder="28"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Overtime </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="overtime" class="form-control" placeholder="Enter Overtime"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Holiday </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="holiday" class="form-control" placeholder="Enter Holiday"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">On Call </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="on_call" class="form-control" placeholder="Enter On Call"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Orientation Rate($) </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="orientation_rate" class="form-control" placeholder="Enter Orientation Rate"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Weekly Taxable Amount($) </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="weekly_taxable_amount" class="form-control" placeholder="1001"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Weekly Non-Taxable Amount($) </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="weekly_non_taxable_amount" class="form-control" placeholder="1009"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Employer Weekly Amount($) </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="employer_weekly_amount" class="form-control" placeholder="2100"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Goodwork Weekly Amount($) </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="goodwork_weekly_amount" class="form-control" placeholder="105"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Total Employer Amount($) </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="total_employer_amount" class="form-control" placeholder="33,300"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Total Goodwork Amount($) </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="total_goodwork_amount" class="form-control" placeholder="1,365"/>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Total Contract Amount($) </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="total_contract_amount" class="form-control" placeholder="34,665"/>
                            </div>
                        </div>





                        <div class="item form-group">
                            <label for="state" class="col-form-label col-md-3 col-sm-3 label-align">State</label>
                            <div class="col-md-6 col-sm-6 ">
                                <select name="job_state" id="state" onchange="get_cities(this)" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($us_states as $v)
                                        <option value="{{$v->id}}">{{$v->name}}</option>
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
                                <textarea id="description" class="resizable_textarea form-control" name="description" placeholder="Description" maxlength="255"></textarea>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Status</label>
                            <div class="col-md-6 col-sm-6 ">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary focus active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                        <input type="radio" name="active" value="1" class="join-btn" checked> Active
                                    </label>
                                    <label class="btn btn-secondary" type="button" data-toggle-class="btn-primary" >
                                        <input type="radio" name="active" value="0" class="join-btn"> &nbsp; Inactive &nbsp;
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Starting date
                            </label>
                            <div class="col-md-4 col-sm-4 ">
                                <input class="date-picker form-control" name="start_date" placeholder="dd-mm-yyyy" type="text" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                            <div class="col-md-2 col-sm-2">
                                <label class="col-form-label label-align"> As Soon As <input type="checkbox" class="fomm-control" value="1" name="as_soon_as"></label>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-6 col-sm-6 offset-md-3">
                                {{-- <button class="btn btn-primary" type="button">Cancel</button> --}}
                                <button class="btn btn-primary" type="reset">Reset</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /page content -->

@stop
@section('js')
<script>

var speciality = {};

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

var dynamic_elements = {
    vac : {
        id : '#vaccination',
        name: 'Vaccination',
        listing_class : '.vaccination-content',
        items: []
    },
    cer : {
        id : '#certificate',
        name: 'Certificate',
        listing_class : '.certifications-content',
        items: []
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
$(document).ready(function () {

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
        data.set('specialty', specialities);
        data.set('experience', experiences);
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
                    // setTimeout(() => {
                    //     window.location.href = resp.link;
                    // }, 3000);
                    $('#create-job-form')[0].reset();
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
