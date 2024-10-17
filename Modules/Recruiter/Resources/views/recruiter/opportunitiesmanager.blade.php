@extends('recruiter::layouts.main')

@section('content')
    <main class="ss-main-body-sec">
        <div class="container">
            <div class="ss-opport-mngr-mn-div-sc">
                <div class="ss-opport-mngr-hedr">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4>Opportunities Manager</h4>
                        </div>
                        <div class="col-lg-6">
                            <ul>
                                <li><button id="drafts" onclick="opportunitiesType('drafts')"
                                        class="ss-darfts-sec-draft-btn">Drafts</button></li>
                                <li><button id="published" onclick="opportunitiesType('published')"
                                        class="ss-darfts-sec-publsh-btn">Published</button></li>
                                <li><button id="onhold" onclick="opportunitiesType('onhold')"
                                        class="ss-darfts-sec-publsh-btn">On Hold</button></li>

                                <li><a href="#" onclick="request_job_form_appear()" class="ss-opr-mngr-plus-sec"><i
                                            class="fas fa-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="ss-no-job-mn-dv" id="no-job-posted">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ss-nojob-dv-hed">
                                <h6>Start Creating Work Request</h6>
                                <a href="#" onclick="request_job_form_appear()">Create Work Request</a>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- add Work form -->
                <div class="all d-none" id="create_job_request_form">
                    <div class="bodyAll">
                        <div class="ss-account-form-lft-1 container">
                            <header><span style="color: #b5649e;">Create</span> Work Request</header>
                            <div class="row progress-bar-item d-none">
                                <div class="col-3 step">
                                    <p>Work information</p>
                                    <div class="bullet">
                                        <span>1</span>
                                    </div>
                                    <div class="check fas fa-check"></div>
                                </div>

                                <div class=" col-2 step">
                                    <p>Preferences and Requirements</p>
                                    <div class="bullet">
                                        <span>2</span>
                                    </div>
                                    <div class="check fas fa-check"></div>
                                </div>
                                <div class="col-2 step">
                                    <p>Work Details</p>
                                    <div class="bullet">
                                        <span>3</span>
                                    </div>
                                    <div class="check fas fa-check"></div>
                                </div>

                                <div class="col-2 step">
                                    <p>other information</p>
                                    <div class="bullet">
                                        <span>4</span>
                                    </div>
                                    <div class="check fas fa-check"></div>
                                </div>

                                <div class="col-3 step">
                                    <p>Work Schedule & Requirements</p>
                                    <div class="bullet">
                                        <span>5</span>
                                    </div>
                                    <div class="check fas fa-check"></div>
                                </div>
                            </div>
                            <div class="form-outer">
                                <form method="post" id="create_job_form" action="{{ route('addJob.store') }}">
                                    @csrf
                                    <!-- first form slide required inputs for adding jobs -->

                                    <div class=" page slide-page">
                                        <div class="row">

                                            <div class="ss-form-group col-md-4 d-none">
                                                <input type="text" name="active" id="active">
                                            </div>
                                            <div class="ss-form-group col-md-4 d-none">
                                                <input type="text" name="is_open" id="is_open">
                                            </div>
                                            
                                            <div class="ss-form-group col-md-4">
                                                <label>Work Type</label>
                                                <select name="job_type" id="job_type">
                                                    <option value="" disabled selected hidden>Select a Work type
                                                    </option>
                                                    <option value="Clinical">Clinical
                                                    </option>
                                                    <option value="Non-Clinical">Non-Clinical
                                                    </option>
                                                </select>

                                                <span class="help-block-job_type"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Preferred Specialty</label>
                                                <select name="preferred_specialty" id="preferred_specialty">
                                                    <option value="" disabled selected hidden>Select a specialty
                                                    </option>
                                                    @foreach ($specialities as $specialty)
                                                        <option value="{{ $specialty->full_name }}">
                                                            {{ $specialty->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-preferred_specialty"></span>
                                            </div>


                                            <div class="ss-form-group col-md-4">
                                                <label>Preferred Profession</label>
                                                <select name="profession" id="perferred_profession">
                                                    <option value="" disabled selected hidden>Select a Profession
                                                    </option>
                                                    @foreach ($allKeywords['Profession'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-perferred_profession"></span>
                                            </div>



                                            <div class="ss-form-group col-md-4">
                                                <label> Work State </label>
                                                <select name="job_state" id="job_state">
                                                    <option value="" disabled selected hidden>Select a State</option>
                                                    @foreach ($states as $state)
                                                        <option id="{{ $state->id }}" value="{{ $state->name }}">
                                                            {{ $state->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-job_state"></span>


                                               
                                            </div>

                                            <div class="ss-form-group col-md-4">

                                               
                                                <label> Work City </label>
                                                <select name="job_city" id="job_city">
                                                    <option value="">Select a city</option>
                                                </select>

                                                <span class="help-block-job_city"></span>
                                            </div>


                                            
                                            <div class="ss-form-group col-md-4">
                                                <label>Weeks per Assignment</label>
                                                <input type="number" name="preferred_assignment_duration"
                                                    id="preferred_assignment_duration"
                                                    placeholder="Enter Work Duration Per Assignment">
                                                <span class="help-block-preferred_assignment_duration"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Est. Weekly Pay </label>
                                                <input type="number" step="0.01" name="weekly_pay" id="weekly_pay"
                                                    placeholder="Enter Weekly Pay">
                                                <span class="help-block-weekly_pay"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Terms</label>
                                                <select name="terms" id="terms">
                                                    <option value="" disabled selected hidden>Select a Term</option>
                                                    @foreach ($allKeywords['Terms'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-terms"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Est. Weekly non-taxable amount</label>
                                                <input type="number" name="weekly_non_taxable_amount"
                                                    id="weekly_non_taxable_amount"
                                                    placeholder="Enter Weekly non-taxable amount">
                                                <span class="help-block-weekly_non_taxable_amount"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Overtime Hourly rate</label>
                                                <input type="number" name="overtime" id="overtime"
                                                    placeholder="Enter actual Overtime Hourly rate">
                                                <span class="help-block-overtime"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Est. Taxable Hourly rate</label>
                                                <input type="number" name="actual_hourly_rate" id="actual_hourly_rate"
                                                    placeholder="Enter Taxable Regular Hourly rate">
                                                <span class="help-block-actual_hourly_rate"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Pay Frequency</label>
                                                <select name="pay_frequency" id="pay_frequency">
                                                    <option value="" disabled selected hidden>Select a pay frequency
                                                    </option>
                                                    @foreach ($allKeywords['PayFrequency'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-pay_frequency"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Guaranteed Hours per week</label>
                                                <input type="number" name="guaranteed_hours" id="guaranteed_hours"
                                                    placeholder="Enter Guaranteed Hours per week">
                                                <span class="help-block-guaranteed_hours"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Hours Per Week</label>
                                                <input type="number" name="hours_per_week" id="hours_per_week"
                                                    placeholder="Enter hours per week">
                                                <span class="help-block-hours_per_week"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Hours Per Shift</label>
                                                <input type="number" name="hours_shift" id="hours_shift"
                                                    placeholder="Enter Hours Per Shift">
                                                <span class="help-block-hours_shift"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Shift Per Weeks
                                                </label>
                                                <input type="number" name="weeks_shift" id="weeks_shift"
                                                    placeholder="Enter Shift Per Weeks">
                                                <span class="help-block-weeks_shift"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Eligible work in us ?</label>
                                                <select name="eligible_work_in_us" id="eligible_work_in_us">
                                                    <option value="" disabled selected hidden>Select an answer
                                                    </option>
                                                    <option value="1">Yes
                                                    </option>
                                                    <option value="0">No
                                                    </option>
                                                </select>
                                                <span class="help-block-eligible_work_in_us"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Preferred Experience</label>
                                                <input type="number" name="preferred_experience"
                                                    id="preferred_experience" placeholder="Enter Preferred Experience">
                                                <span class="help-block-preferred_experience"></span>
                                            </div>

                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Professional Licensure</label>
                                                <div class="ss-speilty-exprnc-add-list professional_licensure-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="professional_licensure">
                                                                <option value="" disabled selected hidden>Select a
                                                                    professional Licensure</option>
                                                                @if (isset($allKeywords['StateCode']))
                                                                    @foreach ($allKeywords['StateCode'] as $value)
                                                                        <option value="{{ $value->id }}">{{$value->title}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="professional_licensureAllValues"
                                                                name="job_location">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                onclick="addprofessional_licensure('from_add')"><i
                                                                    class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                                <span class="help-block-professional_licensure"></span>
                                            </div>



                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Shift Time of Day</label>
                                                <div class="ss-speilty-exprnc-add-list shifttimeofday-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="shifttimeofday">
                                                                <option value="">Select Shift Time of Day</option>
                                                                @if (isset($allKeywords['PreferredShift']))
                                                                    @foreach ($allKeywords['PreferredShift'] as $value)
                                                                        <option value="{{ $value->id }}">{{$value->title}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="shifttimeofdayAllValues"
                                                                name="preferred_shift_duration">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                onclick="addshifttimeofday('from_add')"><i
                                                                    class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                                <span class="help-block-shift_time_of_day"></span>
                                            </div>

                                            <div class="field btns col-12 d-flex justify-content-center">
                                                <button class="saveDrftBtn">Save as draft</button>
                                                <button class="firstNext next">Next</button>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Second form slide required inputs for adding jobs -->
                                    <div class="page">
                                        <div class="row">
                                            {{-- edits --}}
                                            
                                            <div class="ss-form-group col-md-4">
                                                <label>Min distance from facility</label>
                                                <input type="number" name="traveler_distance_from_facility"
                                                    id="traveler_distance_from_facility"
                                                    placeholder="Enter travel distance">
                                                <span class="help-block-traveler_distance_from_facility"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Clinical Setting</label>
                                                <select name="clinical_setting" id="clinical_setting">
                                                    <option value="" disabled selected hidden>Select a setting
                                                    </option>
                                                    @foreach ($allKeywords['ClinicalSetting'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-clinical_setting"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Patient ratio</label>
                                                <input type="number" name="Patient_ratio" id="Patient_ratio"
                                                    placeholder="Enter Patient ratio">
                                                <span class="help-block-Patient_ratio"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Unit</label>
                                                <input type="text" name="Unit" id="Unit"
                                                    placeholder="Enter Unit">
                                                <span class="help-block-Unit"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Scrub Color</label>
                                                <input type="text" name="scrub_color" id="scrub_color"
                                                    placeholder="Enter scrub color">
                                                <span class="help-block-scrub_color"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Rto</label>
                                                <select name="rto" id="rto">
                                                    <option value="" disabled selected hidden>Select an Rto</option>
                                                    <option value="allowed">Allowed
                                                    </option>
                                                    <option value="not allowed">Not Allowed
                                                    </option>
                                                </select>
                                                <span class="help-block-rto"></span>
                                            </div>
                                           <div class="ss-form-group col-md-4">
                                                <label> Work Id</label>
                                                <input type="text" name="job_id" id="job_id"
                                                    placeholder="Enter Work Id">
                                                <span class="help-block-job_id"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label> Work Name</label>
                                                <input type="text" name="job_name" id="job_name"
                                                    placeholder="Enter Work name">
                                                <span class="help-block-job_name"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Preferred Work Location</label>
                                                <input type="text" name="preferred_work_location"
                                                    id="preferred_work_location" placeholder="Enter Work Location">
                                                <span class="help-block-preferred_work_location"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Referral Bonus</label>
                                                <input type="number" name="referral_bonus" id="referral_bonus"
                                                    placeholder="Enter referral bonus">
                                                <span class="help-block-referral_bonus"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Sign on Bonus</label>
                                                <input type="number" name="sign_on_bonus" id="sign_on_bonus"
                                                    placeholder="Enter sign on bonus">
                                                <span class="help-block-sign_on_bonus"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Completion Bonus</label>
                                                <input type="number" name="completion_bonus" id="completion_bonus"
                                                    placeholder="Enter completion bonus">
                                                <span class="help-block-completion_bonus"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Extension Bonus</label>

                                                <input type="number" name="extension_bonus" id="extension_bonus"
                                                    placeholder="Enter extension bonus">
                                                <span class="help-block-extension_bonus"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Other bonus</label>
                                                <input type="number" name="other_bonus" id="other_bonus"
                                                    placeholder="Enter other bonus">
                                                <span class="help-block-other_bonus"></span>
                                            </div>
                                            

                                            
                                            <div class="ss-form-group col-md-4">
                                                <label>On Call</label>
                                                <select name="on_call" id="on_call">
                                                    <option value="" disabled selected hidden>Select an answer
                                                    </option>
                                                    <option value="Yes">Yes
                                                    </option>
                                                    <option value="No">No
                                                    </option>
                                                </select>
                                                <span class="help-block-on_call"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>On Call Rate</label>
                                                <input type="number" name="on_call_rate" id="on_call_rate"
                                                    placeholder="Enter a call back hourly rate">
                                                <span class="help-block-on_call_rate"></span>
                                            </div>

                                            
                                            <div class="ss-form-group col-md-4">
                                                <label>Work Description</label>
                                                <textarea type="text" name="description" id="description" placeholder="Enter Work Description"></textarea>
                                                <span class="help-block-description"></span>
                                            </div>

                                            <div class="field btns col-12 d-flex justify-content-center">
                                                <button class="saveDrftBtn">Save as draft</button>
                                                <button class="prev-1 prev">Previous</button>
                                                <button class="next-1 next">Next</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Third form slide required inputs for adding jobs -->

                                    <div class="page">
                                        <div class="row">
                                           
                                            <div class="ss-form-group col-md-4">
                                                <label>Holidy date</label>
                                                <input type="date" name="holiday" id="holiday"
                                                    placeholder="Enter Holidy hourly rate">
                                                <span class="help-block-holiday"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Orientation Hourly rate</label>
                                                <input type="number" name="orientation_rate" id="orientation_rate"
                                                    placeholder="Enter Orientation Hourly rate">
                                                <span class="help-block-orientation_rate"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Block scheduling</label>
                                                <select name="block_scheduling" id="block_scheduling">
                                                    <option value="" disabled selected hidden>Select an answer
                                                    </option>
                                                    <option value="Yes">Yes
                                                    </option>
                                                    <option value="No">No
                                                    </option>
                                                </select>
                                                <span class="help-block-block_scheduling"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Float requirements</label>
                                                <select name="float_requirement" id="float_requirement">
                                                    <option value="" disabled selected hidden>Select an answer
                                                    </option>
                                                    <option value="Yes">Yes
                                                    </option>
                                                    <option value="No">No
                                                    </option>
                                                </select>
                                                <span class="help-block-float_requirement"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Number Of References</label>
                                                <input type="number" name="number_of_references"
                                                    id="number_of_references" placeholder="Enter number of references">
                                                <span class="help-block-number_of_references"></span>
                                            </div>
                                            

                                            

                                            <div class="ss-form-group col-md-4">
                                                <label>Facility's Parent System</label>
                                                <input type="text" name="facilitys_parent_system"
                                                    id="facilitys_parent_system"
                                                    placeholder="Enter facility's parent system">
                                                <span class="help-block-facilitys_parent_system"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Facility Name</label>
                                                <input type="text" name="facility_name" id="facility_name"
                                                    placeholder="Enter facility name">
                                                <span class="help-block-facility_name"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Contract Termination Policy</label>
                                                <input type="text" name="contract_termination_policy"
                                                    id="contract_termination_policy"
                                                    placeholder="Enter your contract termination policy">
                                                <span class="help-block-contract_termination_policy"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Shift Cancellation Policy</label>
                                                <input type="text" name="facility_shift_cancelation_policy"
                                                    id="facility_shift_cancelation_policy"
                                                    placeholder="Select your shift cancellation policy">
                                                <span class="help-block-facility_shift_cancelation_policy"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>401K</label>
                                                <select name="four_zero_one_k" id="four_zero_one_k">
                                                    <option value="" disabled selected hidden>Select an answer
                                                    </option>
                                                    <option value="Yes">Yes
                                                    </option>
                                                    <option value="No">No
                                                    </option>
                                                </select>
                                                <span class="help-block-four_zero_one_k"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Health Insurance</label>
                                                <select name="health_insaurance" id="health_insaurance">
                                                    <option value="" disabled selected hidden>Select a Health
                                                        Insurance</option>
                                                    <option value="Yes">Yes
                                                    </option>
                                                    <option value="No">No
                                                    </option>
                                                </select>
                                                <span class="help-block-health_insaurance"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Feels Like $/hrs</label>
                                                <input type="number" name="feels_like_per_hour" id="feels_like_per_hour"
                                                    placeholder="Enter Feels Like $/hrs">
                                                <span class="help-block-feels_like_per_hour"></span>
                                            </div>



                                            <div class="ss-form-group col-md-4">
                                                <label>Call Back Hourly rate</label>
                                                <input type="number" name="call_back_rate" id="call_back_rate"
                                                    placeholder="Enter Call Back Hourly rate">
                                                <span class="help-block-call_back_rate"></span>
                                                <span class="help-block-call_back_rate"></span>
                                            </div>

                                            <div class="ss-form-group col-md-12">
                                                <div class="row">
                                                    <div class="col-lg-6 col-sm-12 col-md-12 col-xs-12" style="margin: 20px 0px;">
                                                        <label>Start Date</label>
                                                    </div>
                                                    <div class="row col-lg-6 col-sm-12 col-md-12 col-xs-12"
                                                        style="display: flex; justify-content: end; align-items:center;">
                                                        <input id="as_soon_as" name="as_soon_as" value="1"
                                                            type="checkbox" style="box-shadow:none; width:auto;"
                                                            class="col-6">
                                                        <label class="col-6">
                                                            As soon As possible
                                                        </label>
                                                    </div>
                                                </div>
                                                <input id="start_date" type="date" min="2024-03-06" name="start_date"
                                                    placeholder="Select Date" value="2024-03-06">
                                            </div>
                                            <span class="help-block-start_date"></span>

                                            <div class="row ss-form-group col-md-4 d-flex justify-content-end" style="margin-left: 17px; padding-bottom: 20px;">
                                                <label style="padding-bottom: 25px; padding-top: 25px;">Urgency</label>
                                                <div class="row justify-content-center" style="display:flex; align-items:end;">
                                                    <label class="col-6" for="urgency"
                                                        style="display:flex; justify-content:end;">Auto Offer</label>
                                                    <div class="col-6">
                                                        <input type="checkbox" name="urgency" id="urgency"
                                                            value="Auto Offer" style="box-shadow: none;">
                                                    </div>
                                                </div>
                                                
                                                <span class="help-block-urgency"></span>
                                            </div>
                                            
                                            <div class="vr p-0" style="margin-left: 30px; margin-right: 30px; margin-bottom: 10px;"></div>
                                              
                                              
                                            <div class="row ss-form-group d-flex justify-content-end col-md-7" style="padding-bottom: 20px;">
                                                <label style="padding-bottom: 25px; padding-top: 25px;">Professional State Licensure</label>
                                                
                                                    <div class="row col-6 justify-content-center align-items-end">
                                                        <label class="col-7" for="professional_state_licensure_pending">Accept
                                                            Pending
                                                        </label>
                                                        <div class="col-5">
                                                            <input type="radio" id="professional_state_licensure_pending"
                                                                name="professional_state_licensure" value="Accept Pending"
                                                                style="box-shadow: none;">
                                                        </div>
                                                    </div>
                                                    {{-- Radio option for "Active" --}}
                                                    <div class="row col-6 justify-content-center align-items-end">
                                                        <label  class="col-7" for="professional_state_licensure_active">
                                                            Active
                                                        </label>
                                                        <div class="col-5">
                                                            <input type="radio" id="professional_state_licensure_active"
                                                                name="professional_state_licensure" value="Active"
                                                                style="box-shadow: none;">
                                                        </div>
                                                    </div>
                                                <span class="help-block-professional_state_licensure"></span>
                                            </div>

                                            <div class="field btns col-12 d-flex justify-content-center">
                                                <button class="saveDrftBtn">Save as draft</button>
                                                <button class="prev-2 prev">Previous</button>
                                                <button class="next-2 next">Next</button>

                                            </div>

                                        </div>
                                    </div>

                                    {{-- slide added from sheets --}}

                                    <div class="page">
                                        <div class="row">

                                            
                                            {{-- <div class="ss-form-group col-md-4">
                                                <label>Worker Classification</label>
                                                <select name="nurse_classification" id="nurse_classification">
                                                    <option value="" disabled selected hidden>Select a Worker Classification</option>
                                                    @foreach ($allKeywords['NurseClassification'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-nurse_classification"></span>
                                            </div> --}}



                                            

                                            

                                            

                                            {{-- <div class="row ss-form-group col-md-4 d-flex justify-content-end">
                                                <label>Urgency</label>
                                                <div class="row" style="display:flex; align-items:end;">
                                                    <label class="col-6" for="urgency" style="display:flex; justify-content:end;">Auto Offer</label>
                                                    <div class="col-6">
                                                        <input type="checkbox" name="urgency" id="urgency" value="Auto Offer" style="box-shadow: none;">
                                                    </div>
                                                </div>
                                                <span class="help-block-urgency"></span>
                                            </div> --}}

                                            

                                            {{-- <div class="ss-form-group col-md-4">
                                                <label>Professional Licensure</label>
                                                <select name="job_location" id="job_location">
                                                    <option value="" disabled selected hidden>Select a Professional Licensure</option>
                                                    @foreach ($allKeywords['StateCode'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }} Compact
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-job_location"></span>
                                            </div> --}}

                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Worker Classification</label>
                                                <div class="ss-speilty-exprnc-add-list nurse_classification-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="nurse_classification">
                                                                <option value="" disabled selected hidden>Select a
                                                                    Worker Classification</option>
                                                                @if (isset($allKeywords['NurseClassification']))
                                                                    @foreach ($allKeywords['NurseClassification'] as $value)
                                                                        <option value="{{ $value->id }}">{{$value->title}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="nurse_classificationAllValues"
                                                                name="nurse_classification">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                onclick="addnurse_classification('from_add')"><i
                                                                    class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                                <span class="help-block-nurse_classification"></span>
                                            </div>
                                            

                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>EMR</label>
                                                <div class="ss-speilty-exprnc-add-list Emr-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="Emr">
                                                                <option value="" disabled selected hidden>Select an
                                                                    emr</option>
                                                                @if (isset($allKeywords['EMR']))
                                                                    @foreach ($allKeywords['EMR'] as $value)
                                                                        <option value="{{ $value->id }}">{{$value->title}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="EmrAllValues" name="Emr">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                onclick="addEmr('from_add')"><i class="fa fa-plus"
                                                                    aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                                <span class="help-block-Emr"></span>
                                            </div>

                                            <div id="benefits_id" class="d-none ss-form-group ss-prsnl-frm-specialty">
                                                <label>Benefits</label>
                                                <div class="ss-speilty-exprnc-add-list benefits-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="benefits">
                                                                <option value="" disabled selected hidden>Select a
                                                                    benefits</option>
                                                                @if (isset($allKeywords['Benefits']))
                                                                    @foreach ($allKeywords['Benefits'] as $value)
                                                                        <option value="{{ $value->id }}">{{$value->title}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="benefitsAllValues" name="benefits">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                onclick="addbenefits('from_add')"><i class="fa fa-plus"
                                                                    aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                                <span class="help-block-benefits"></span>
                                            </div>



                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Certifications</label>
                                                <div class="ss-speilty-exprnc-add-list certificate-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="certificate">
                                                                <option value="" disabled selected hidden>Select
                                                                    Certification</option>
                                                                @if (isset($allKeywords['Certification']))
                                                                    @foreach ($allKeywords['Certification'] as $value)
                                                                        <option value="{{ $value->id }}">{{$value->title}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="certificateAllValues"
                                                                name="certificate">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                onclick="addcertifications('from_add')"><i
                                                                    class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                                <span class="help-block-certificate"></span>
                                            </div>
                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Vaccinations & Immunizations name</label>
                                                <div class="ss-speilty-exprnc-add-list vaccinations-content">

                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="vaccinations">
                                                                <option value="" disabled selected hidden>Enter
                                                                    Vaccinations & Immunizations name</option>
                                                                @if (isset($allKeywords['Vaccinations']))
                                                                    @foreach ($allKeywords['Vaccinations'] as $value)
                                                                        <option value="{{ $value->id }}">{{$value->title}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="vaccinationsAllValues"
                                                                name="vaccinations">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                onclick="addvacc('from_add')"><i
                                                                    class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                                <span class="help-block-vaccinations"></span>
                                            </div>

                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Skills checklist</label>
                                                <div class="ss-speilty-exprnc-add-list skills-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="skills">
                                                                <option value="" disabled selected hidden>Select
                                                                    Skills</option>
                                                                @if (isset($allKeywords['Speciality']))
                                                                    @foreach ($allKeywords['Speciality'] as $value)
                                                                        <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="skillsAllValues" name="skills">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                onclick="addskills('from_add')"><i class="fa fa-plus"
                                                                    aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                                <span class="help-block-skills"></span>
                                            </div>

                                            <div class="field btns col-12 d-flex justify-content-center">
                                                <button class="saveDrftBtn">Save as draft</button>
                                                <button class="prev-3 prev">Previous</button>
                                                <button class="submit">Submit</button>
                                            </div>

                                        </div>
                                    </div>

                                    {{-- end slide added from sheets --}}


                                    <!-- Forth form slide for adding jobs -->

                                    <div class="page">
                                        <div class="row">


                                            


                                            

                                            {{-- <div class="ss-form-group col-md-4">
                                                <label>EMR</label>
                                                <select name="Emr"
                                                    id="emr">
                                                    <option value="" disabled selected hidden>Select an EMR</option>
                                                    @foreach ($allKeywords['EMR'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-emr"></span>
                                            </div> --}}

                                            

                                            
                                            {{-- <div class="ss-form-group col-md-4">
                                            <input type="text" name="responsibilities" id="responsibilities"
                                                placeholder="Enter Responsibilities">
                                        </div>

                                        <div class="ss-form-group col-md-4">
                                            <input type="text" name="qualifications" id="qualifications"
                                                placeholder="Enter Qualifications">
                                        </div> --}}
                                            
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end add Work form -->

                <div class="ss-acount-profile d-none" id="published-job-details">
                    <div class="row">

                        <!-- DRAFT CARDS -->
                        <div class="col-lg-5 d-none" id="draftCards">
                            <div class="ss-account-form-lft-1">
                                <h5 class="mb-4 text-capitalize">Draft</h5>

                                @php $counter = 0 @endphp
                                @foreach ($draftJobs as $job)
                                    <div style="" class="col-12 ss-job-prfle-sec draft-cards" onclick="editDataJob(this),
                                    toggleActiveClass('{{$job->id}}_drafts','draft-cards')"
                                    job_id="{{ $counter }}"
                                        id="{{ $job->id}}_drafts">
                                        <h4>{{ $job->profession }} - {{ $job->preferred_specialty }}</h4>
                                        <h6>{{ $job->job_name }}</h6>
                                        <ul>
                                            <li><a href="#"><img
                                                        src=" {{ URL::asset('frontend/img/location.png') }}">
                                                    {{ $job->job_city }}, {{ $job->job_state }}</a></li>
                                            <li><a href="#"><img
                                                        src="{{ URL::asset('frontend/img/calendar.png') }}">
                                                    {{ $job->preferred_assignment_duration }}
                                                    wks</a></li>
                                            <li><a href="#"><img
                                                        src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                                                    {{ $job->weekly_pay }}/wk</a></li>
                                        </ul>

                                    </div>
                                    @php $counter++ @endphp
                                @endforeach
                                <div id="job-list-draft">
                                </div>
                            </div>
                        </div>
                        <!-- END DRAFT CARDS -->
                        <!-- PUBLISHED CARDS -->
                        <div class="col-lg-5 d-none" id="publishedCards">
                            <div class="ss-account-form-lft-1">
                                <h5 class="mb-4 text-capitalize">Published</h5>
                                @php $counter = 0 @endphp
                                @foreach ($publishedJobs as $key => $value)
                                    {{-- <div class="col-12 ss-job-prfle-sec" onclick="editDataJob(this)"
                                        id="{{ $counter }}"> --}}
                                        
                                    <div class="col-12 ss-job-prfle-sec published-cards"
                                        id ="{{$value->id}}_published"
                                        onclick="opportunitiesType('published','{{ $value->id }}','jobdetails'),
                                        toggleActiveClass('{{$value->id}}_published','published-cards')
                                        "
                                        >
                                        <p>Travel <span> {{ $applyCount[$key] }} Applied</span></p>
                                        <h4>{{ $value->profession }} - {{ $value->preferred_specialty }}</h4>
                                        <h6>{{ $value->job_name }}</h6>
                                        <ul>
                                            <li><a href="#"><img
                                                        src=" {{ URL::asset('frontend/img/location.png') }}">
                                                    {{ $value->job_city }}, {{ $value->job_state }}</a></li>
                                            <li><a href="#"><img
                                                        src="{{ URL::asset('frontend/img/calendar.png') }}">
                                                    {{ $value->preferred_assignment_duration }}
                                                    wks</a></li>
                                            <li><a href="#"><img
                                                        src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                                                    {{ $value->weekly_pay }}/wk</a></li>
                                        </ul>

                                    </div>
                                    @php $counter++ @endphp
                                @endforeach
                                @if (count($publishedJobs) == 0)
                                    <div id="job-list-published">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- END PUBLISHED CARDS -->

                        <!-- ONHOLD CARDS -->
                        <div class="col-lg-5 d-none" id="onholdCards">
                            <div class="ss-account-form-lft-1">
                                <h5 class="mb-4 text-capitalize">On Hold</h5>
                                @php $counter = 0 @endphp
                                @foreach ($onholdJobs as $job)
                                    <div class="col-12 ss-job-prfle-sec onhold-cards"
                                        onclick="opportunitiesType('onhold','{{ $job->id }}','jobdetails'),
                                        toggleActiveClass('{{$job->id}}_onhold','onhold-cards')"
                                        id="{{$job->id}}_onhold">
                                        <h4>{{ $job->profession }} - {{ $job->preferred_specialty }}</h4>
                                        <h6>{{ $value->job_name }}</h6>
                                        <ul>
                                            <li><a href="#"><img
                                                        src=" {{ URL::asset('frontend/img/location.png') }}">
                                                    {{ $job->job_city }}, {{ $job->job_state }}</a></li>
                                            <li><a href="#"><img
                                                        src="{{ URL::asset('frontend/img/calendar.png') }}">
                                                    {{ $job->preferred_assignment_duration }}
                                                    wks</a></li>
                                            <li><a href="#"><img
                                                        src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                                                    {{ $job->weekly_pay }}/wk</a></li>
                                        </ul>

                                    </div>
                                    @php $counter++ @endphp
                                @endforeach
                                @if (count($onholdJobs) == 0)
                                    <div id="job-list-onhold">
                                    </div>
                                @endif

                            </div>
                        </div>
                        <!-- END ONHOLD CARDS -->

                        <!-- EDITING Draft FORM -->
                        <div class="all col-lg-7" id="details_draft">
                            <div class="bodyAll" style="width: 100%;">
                                <div class="ss-account-form-lft-1" style="width: 100%; margin-top: 0px;"
                                    id="details_info">
                                    <header>Select a Work from Drafts</header>
                                    <div class="row progress-bar-item d-none">
                                        <div class="col-3 step stepDraft">
                                            <p>Work information</p>
                                            <div class="bullet">
                                                <span>1</span>
                                            </div>
                                            <div class="check fas fa-check"></div>
                                        </div>

                                        <div class=" col-2 step stepDraft">
                                            <p>Preferences and Requirements</p>
                                            <div class="bullet">
                                                <span>2</span>
                                            </div>
                                            <div class="check fas fa-check"></div>
                                        </div>
                                        <div class="col-2 step stepDraft">
                                            <p>Work Details</p>
                                            <div class="bullet">
                                                <span>3</span>
                                            </div>
                                            <div class="check fas fa-check"></div>
                                        </div>

                                        <div class="col-2 step stepDraft">
                                            <p>other information</p>
                                            <div class="bullet">
                                                <span>4</span>
                                            </div>
                                            <div class="check fas fa-check"></div>
                                        </div>

                                        <div class="col-3 step stepDraft">
                                            <p>Work Schedule & Requirements</p>
                                            <div class="bullet">
                                                <span>5</span>
                                            </div>
                                            <div class="check fas fa-check"></div>
                                        </div>
                                    </div>
                                    <div class="form-outer">
                                        <form method="post" id="create_job_form" action="{{ route('addJob.store') }}">
                                            @csrf
                                            <!-- first form slide required inputs for adding jobs -->
                                       
                                            <div class=" page slide-pageDraft">
                                                <div class="row">
                                       
                                                    <div class="ss-form-group col-md-4 d-none">
                                                        <input type="text" name="active" id="activeDraft">
                                                    </div>
                                                    <div class="ss-form-group col-md-4 d-none">
                                                        <input type="text" name="is_open" id="is_openDraft">
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Work Type</label>
                                                        <select name="job_type" id="job_typeDraft">
                                                            <option value="" disabled selected hidden>Select a Work type
                                                            </option>
                                                            <option value="Clinical">Clinical
                                                            </option>
                                                            <option value="Non-Clinical">Non-Clinical
                                                            </option>
                                                        </select>
                                       
                                                        <span class="help-block-job_typeDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Preferred Specialty</label>
                                                        <select name="preferred_specialty" id="preferred_specialtyDraft">
                                                            <option value="" disabled selected hidden>Select a specialty
                                                            </option>
                                                            @foreach ($specialities as $specialty)
                                                                <option value="{{ $specialty->full_name }}">
                                                                    {{ $specialty->full_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-preferred_specialtyDraft"></span>
                                                    </div>
                                       
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Preferred Profession</label>
                                                        <select name="profession" id="perferred_professionDraft">
                                                            <option value="" disabled selected hidden>Select a Profession
                                                            </option>
                                                            @foreach ($allKeywords['Profession'] as $value)
                                                                <option value="{{ $value->title }}">{{ $value->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-perferred_professionDraft"></span>
                                                    </div>
                                       
                                       
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label> Work State </label>
                                                        <select name="job_state" id="job_stateDraft">
                                                            <option value="" disabled selected hidden>Select a State</option>
                                                            @foreach ($states as $state)
                                                                <option id="{{ $state->id }}" value="{{ $state->name }}">
                                                                    {{ $state->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-job_stateDraft"></span>
                                       
                                       
                                       
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                       
                                       
                                                        <label> Work City </label>
                                                        <select name="job_city" id="job_cityDraft">
                                                            <option value="">Select a city</option>
                                                        </select>
                                       
                                                        <span class="help-block-job_cityDraft"></span>
                                                    </div>
                                       
                                       
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Weeks per Assignment</label>
                                                        <input type="number" name="preferred_assignment_duration" id="preferred_assignment_durationDraft"
                                                            placeholder="Enter Work Duration Per Assignment">
                                                        <span class="help-block-preferred_assignment_durationDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Est. Weekly Pay </label>
                                                        <input type="number" step="0.01" name="weekly_pay" id="weekly_payDraft" placeholder="Enter Weekly Pay">
                                                        <span class="help-block-weekly_payDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Terms</label>
                                                        <select name="terms" id="termsDraft">
                                                            <option value="" disabled selected hidden>Select a Term</option>
                                                            @foreach ($allKeywords['Terms'] as $value)
                                                                <option value="{{ $value->title }}">{{ $value->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-termsDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Est. Weekly non-taxable amount</label>
                                                        <input type="number" name="weekly_non_taxable_amount" id="weekly_non_taxable_amountDraft"
                                                            placeholder="Enter Weekly non-taxable amount">
                                                        <span class="help-block-weekly_non_taxable_amountDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Overtime Hourly rate</label>
                                                        <input type="number" name="overtime" id="overtimeDraft" placeholder="Enter actual Overtime Hourly rate">
                                                        <span class="help-block-overtimeDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Est. Taxable Hourly rate</label>
                                                        <input type="number" name="actual_hourly_rate" id="actual_hourly_rateDraft"
                                                            placeholder="Enter Taxable Regular Hourly rate">
                                                        <span class="help-block-actual_hourly_rateDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Pay Frequency</label>
                                                        <select name="pay_frequency" id="pay_frequencyDraft">
                                                            <option value="" disabled selected hidden>Select a pay frequency
                                                            </option>
                                                            @foreach ($allKeywords['PayFrequency'] as $value)
                                                                <option value="{{ $value->title }}">{{ $value->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-pay_frequencyDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Guaranteed Hours per week</label>
                                                        <input type="number" name="guaranteed_hours" id="guaranteed_hoursDraft"
                                                            placeholder="Enter Guaranteed Hours per week">
                                                        <span class="help-block-guaranteed_hoursDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Hours Per Week</label>
                                                        <input type="number" name="hours_per_week" id="hours_per_weekDraft" placeholder="Enter hours per week">
                                                        <span class="help-block-hours_per_weekDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Hours Per Shift</label>
                                                        <input type="number" name="hours_shift" id="hours_shiftDraft" placeholder="Enter Hours Per Shift">
                                                        <span class="help-block-hours_shiftDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Shift Per Weeks
                                                        </label>
                                                        <input type="number" name="weeks_shift" id="weeks_shiftDraft" placeholder="Enter Shift Per Weeks">
                                                        <span class="help-block-weeks_shiftDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Eligible work in us ?</label>
                                                        <select name="eligible_work_in_us" id="eligible_work_in_usDraft">
                                                            <option value="" disabled selected hidden>Select an answer
                                                            </option>
                                                            <option value="1">Yes
                                                            </option>
                                                            <option value="0">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-eligible_work_in_usDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Preferred Experience</label>
                                                        <input type="number" name="preferred_experience" id="preferred_experienceDraft"
                                                            placeholder="Enter Preferred Experience">
                                                        <span class="help-block-preferred_experienceDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group ss-prsnl-frm-specialty">
                                                        <label>Professional Licensure</label>
                                                        <div class="ss-speilty-exprnc-add-list professional_licensure-content">
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="professional_licensureDraft">
                                                                        <option value="" disabled selected hidden>Select a
                                                                            professional Licensure</option>
                                                                        @if (isset($allKeywords['StateCode']))
                                                                            @foreach ($allKeywords['StateCode'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="professional_licensureAllValuesDraft" name="job_location">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addprofessional_licensure('from_draft')"><i class="fa fa-plus"
                                                                            aria-hidden="true"></i></a></div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-professional_licensure"></span>
                                                    </div>
                                       
                                       
                                       
                                                    <div class="ss-form-group ss-prsnl-frm-specialty">
                                                        <label>Shift Time of Day</label>
                                                        <div class="ss-speilty-exprnc-add-list shifttimeofday-content">
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="shifttimeofdayDraft">
                                                                        <option value="">Select Shift Time of Day</option>
                                                                        @if (isset($allKeywords['PreferredShift']))
                                                                            @foreach ($allKeywords['PreferredShift'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="shifttimeofdayAllValuesDraft" name="preferred_shift_duration">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addshifttimeofday('from_draft')"><i class="fa fa-plus"
                                                                            aria-hidden="true"></i></a></div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-shift_time_of_day"></span>
                                                    </div>
                                       
                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnDraft">Save as draft</button>
                                                        <button class="firstNextDraft next">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                       
                                       
                                            <!-- Second form slide required inputs for adding jobs -->
                                            <div class="page">
                                                <div class="row">
                                                    {{-- Drafts --}}
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Min distance from facility</label>
                                                        <input type="number" name="traveler_distance_from_facility" id="traveler_distance_from_facilityDraft"
                                                            placeholder="Enter travel distance">
                                                        <span class="help-block-traveler_distance_from_facilityDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Clinical Setting</label>
                                                        <select name="clinical_setting" id="clinical_settingDraft">
                                                            <option value="" disabled selected hidden>Select a setting
                                                            </option>
                                                            @foreach ($allKeywords['ClinicalSetting'] as $value)
                                                                <option value="{{ $value->title }}">{{ $value->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-clinical_settingDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Patient ratio</label>
                                                        <input type="number" name="Patient_ratio" id="Patient_ratioDraft" placeholder="Enter Patient ratio">
                                                        <span class="help-block-Patient_ratioDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Unit</label>
                                                        <input type="text" name="Unit" id="UnitDraft" placeholder="Enter Unit">
                                                        <span class="help-block-UnitDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Scrub Color</label>
                                                        <input type="text" name="scrub_color" id="scrub_colorDraft" placeholder="Enter scrub color">
                                                        <span class="help-block-scrub_colorDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Rto</label>
                                                        <select name="rto" id="rtoDraft">
                                                            <option value="" disabled selected hidden>Select an Rto</option>
                                                            <option value="allowed">Allowed
                                                            </option>
                                                            <option value="not allowed">Not Allowed
                                                            </option>
                                                        </select>
                                                        <span class="help-block-rtoDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label> Work Id</label>
                                                        <input type="text" name="job_id" id="job_idDraft" placeholder="Enter Work Id">
                                                        <span class="help-block-job_idDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label> Work Name</label>
                                                        <input type="text" name="job_name" id="job_nameDraft" placeholder="Enter Work name">
                                                        <span class="help-block-job_nameDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Preferred Work Location</label>
                                                        <input type="text" name="preferred_work_location" id="preferred_work_locationDraft"
                                                            placeholder="Enter Work Location">
                                                        <span class="help-block-preferred_work_locationDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Referral Bonus</label>
                                                        <input type="number" name="referral_bonus" id="referral_bonusDraft" placeholder="Enter referral bonus">
                                                        <span class="help-block-referral_bonusDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Sign on Bonus</label>
                                                        <input type="number" name="sign_on_bonus" id="sign_on_bonusDraft" placeholder="Enter sign on bonus">
                                                        <span class="help-block-sign_on_bonusDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Completion Bonus</label>
                                                        <input type="number" name="completion_bonus" id="completion_bonusDraft"
                                                            placeholder="Enter completion bonus">
                                                        <span class="help-block-completion_bonusDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Extension Bonus</label>
                                       
                                                        <input type="number" name="extension_bonus" id="extension_bonusDraft"
                                                            placeholder="Enter extension bonus">
                                                        <span class="help-block-extension_bonusDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Other bonus</label>
                                                        <input type="number" name="other_bonus" id="other_bonusDraft" placeholder="Enter other bonus">
                                                        <span class="help-block-other_bonusDraft"></span>
                                                    </div>
                                       
                                       
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>On Call</label>
                                                        <select name="on_call" id="on_callDraft">
                                                            <option value="" disabled selected hidden>Select an answer
                                                            </option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-on_callDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>On Call Rate</label>
                                                        <input type="number" name="on_call_rate" id="on_call_rateDraft"
                                                            placeholder="Enter a call back hourly rate">
                                                        <span class="help-block-on_call_rateDraft"></span>
                                                    </div>
                                       
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Work Description</label>
                                                        <textarea type="text" name="description" id="descriptionDraft" placeholder="Enter Work Description"></textarea>
                                                        <span class="help-block-descriptionDraft"></span>
                                                    </div>
                                       
                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnDraft">Save as draft</button>
                                                        <button class="prev-1Draft prev">Previous</button>
                                                        <button class="next-1Draft next">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                       
                                            <!-- Third form slide required inputs for adding jobs -->
                                       
                                            <div class="page">
                                                <div class="row">
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Holidy date</label>
                                                        <input type="date" name="holiday" id="holidayDraft" placeholder="Enter Holidy hourly rate">
                                                        <span class="help-block-holidayDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Orientation Hourly rate</label>
                                                        <input type="number" name="orientation_rate" id="orientation_rateDraft"
                                                            placeholder="Enter Orientation Hourly rate">
                                                        <span class="help-block-orientation_rateDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Block scheduling</label>
                                                        <select name="block_scheduling" id="block_schedulingDraft">
                                                            <option value="" disabled selected hidden>Select an answer
                                                            </option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-block_schedulingDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Float requirements</label>
                                                        <select name="float_requirement" id="float_requirementDraft">
                                                            <option value="" disabled selected hidden>Select an answer
                                                            </option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-float_requirementDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Number Of References</label>
                                                        <input type="number" name="number_of_references" id="number_of_referencesDraft"
                                                            placeholder="Enter number of references">
                                                        <span class="help-block-number_of_referencesDraft"></span>
                                                    </div>
                                       
                                       
                                       
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Facility's Parent System</label>
                                                        <input type="text" name="facilitys_parent_system" id="facilitys_parent_systemDraft"
                                                            placeholder="Enter facility's parent system">
                                                        <span class="help-block-facilitys_parent_systemDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Facility Name</label>
                                                        <input type="text" name="facility_name" id="facility_nameDraft" placeholder="Enter facility name">
                                                        <span class="help-block-facility_nameDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Contract Termination Policy</label>
                                                        <input type="text" name="contract_termination_policy" id="contract_termination_policyDraft"
                                                            placeholder="Enter your contract termination policy">
                                                        <span class="help-block-contract_termination_policyDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Shift Cancellation Policy</label>
                                                        <input type="text" name="facility_shift_cancelation_policy"
                                                            id="facility_shift_cancelation_policyDraft" placeholder="Select your shift cancellation policy">
                                                        <span class="help-block-facility_shift_cancelation_policyDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>401K</label>
                                                        <select name="four_zero_one_k" id="four_zero_one_kDraft">
                                                            <option value="" disabled selected hidden>Select an answer
                                                            </option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-four_zero_one_kDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Health Insurance</label>
                                                        <select name="health_insaurance" id="health_insauranceDraft">
                                                            <option value="" disabled selected hidden>Select a Health
                                                                Insurance</option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-health_insauranceDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Feels Like $/hrs</label>
                                                        <input type="number" name="feels_like_per_hour" id="feels_like_per_hourDraft"
                                                            placeholder="Enter Feels Like $/hrs">
                                                        <span class="help-block-feels_like_per_hourDraft"></span>
                                                    </div>
                                       
                                       
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Call Back Hourly rate</label>
                                                        <input type="number" name="call_back_rate" id="call_back_rateDraft"
                                                            placeholder="Enter Call Back Hourly rate">
                                                        <span class="help-block-call_back_rateDraft"></span>
                                                        
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-12">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-sm-12 col-md-12 col-xs-12" style="margin: 20px 0px;">
                                                                <label>Start Date</label>
                                                            </div>
                                                            <div class="row col-lg-6 col-sm-12 col-md-12 col-xs-12"
                                                                style="display: flex; justify-content: end; align-items:center;">
                                                                <input id="as_soon_asDraft" name="as_soon_as" value="1" type="checkbox"
                                                                    style="box-shadow:none; width:auto;" class="col-6">
                                                                <label class="col-6">
                                                                    As soon As possible
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <input id="start_dateDraft" type="date" min="2024-03-06" name="start_date" placeholder="Select Date"
                                                            value="2024-03-06">
                                                    </div>
                                                    <span class="help-block-start_dateDraft"></span>
                                       
                                                    <div class="row ss-form-group col-md-4 d-flex justify-content-end" style="margin-left: 17px; padding-bottom: 20px;">
                                                        <label style="padding-bottom: 25px; padding-top: 25px;">Urgency</label>
                                                        <div class="row justify-content-center" style="display:flex; align-items:end;">
                                                            <label class="col-6" for="urgency" style="display:flex; justify-content:end;">Auto
                                                                Offer</label>
                                                            <div class="col-6">
                                                                <input type="checkbox" name="urgency" id="urgencyDraft" value="Auto Offer"
                                                                    style="box-shadow: none;">
                                                            </div>
                                                        </div>
                                       
                                                        <span class="help-block-urgencyDraft"></span>
                                                    </div>
                                       
                                                    <div class="vr p-0" style="margin-left: 30px; margin-right: 30px; margin-bottom: 10px;"></div>
                                       
                                       
                                                    <div class="row ss-form-group d-flex justify-content-end col-md-7" style="padding-bottom: 20px;">
                                                        <label style="padding-bottom: 25px; padding-top: 25px;">Professional State Licensure</label>
                                       
                                                        <div class="row col-6 justify-content-center align-items-end">
                                                            <label class="col-7" for="professional_state_licensure_pending">Accept
                                                                Pending
                                                            </label>
                                                            <div class="col-5">
                                                                <input type="radio" id="professional_state_licensure_pendingDraft"
                                                                    name="professional_state_licensure" value="Accept Pending" style="box-shadow: none;">
                                                            </div>
                                                        </div>
                                                        {{-- Radio option for "Active" --}}
                                                        <div class="row col-6 justify-content-center align-items-end">
                                                            <label class="col-7" for="professional_state_licensure_active">
                                                                Active
                                                            </label>
                                                            <div class="col-5">
                                                                <input type="radio" id="professional_state_licensure_activeDraft"
                                                                    name="professional_state_licensure" value="Active" style="box-shadow: none;">
                                                            </div>
                                                        </div>
                                       
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    {{-- Removed the select element and its options --}}
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                       
                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnDraft">Save as draft</button>
                                                        <button class="prev-2Draft prev">Previous</button>
                                                        <button class="next-2Draft next">Next</button>
                                       
                                                    </div>
                                       
                                                </div>
                                            </div>
                                       
                                            {{-- slide added from sheets --}}
                                       
                                            <div class="page">
                                                <div class="row">
                                       
                                       
                                                    {{-- <div class="ss-form-group col-md-4">
                                                                                       <label>Worker Classification</label>
                                                                                       <select name="nurse_classification" id="nurse_classification">
                                                                                           <option value="" disabled selected hidden>Select a Worker Classification</option>
                                                                                           @foreach ($allKeywords['NurseClassification'] as $value)
                                                                                               <option value="{{ $value->title }}">{{ $value->title }}
                                                                                               </option>
                                                                                           @endforeach
                                                                                       </select>
                                                                                       <span class="help-block-nurse_classification"></span>
                                                                                   </div> --}}
                                       
                                       
                                       
                                       
                                       
                                       
                                       
                                       
                                       
                                                    {{-- <div class="row ss-form-group col-md-4 d-flex justify-content-end">
                                                                                       <label>Urgency</label>
                                                                                       <div class="row" style="display:flex; align-items:end;">
                                                                                           <label class="col-6" for="urgency" style="display:flex; justify-content:end;">Auto Offer</label>
                                                                                           <div class="col-6">
                                                                                               <input type="checkbox" name="urgency" id="urgency" value="Auto Offer" style="box-shadow: none;">
                                                                                           </div>
                                                                                       </div>
                                                                                       <span class="help-block-urgency"></span>
                                                                                   </div> --}}
                                       
                                       
                                       
                                                    {{-- <div class="ss-form-group col-md-4">
                                                                                       <label>Professional Licensure</label>
                                                                                       <select name="job_location" id="job_location">
                                                                                           <option value="" disabled selected hidden>Select a Professional Licensure</option>
                                                                                           @foreach ($allKeywords['StateCode'] as $value)
                                                                                               <option value="{{ $value->title }}">{{ $value->title }} Compact
                                                                                               </option>
                                                                                           @endforeach
                                                                                       </select>
                                                                                       <span class="help-block-job_location"></span>
                                                                                   </div> --}}
                                       
                                                    <div class="ss-form-group ss-prsnl-frm-specialty">
                                                        <label>Worker Classification</label>
                                                        <div class="ss-speilty-exprnc-add-list nurse_classification-content">
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="nurse_classificationDraft">
                                                                        <option value="" disabled selected hidden>Select a
                                                                            Worker Classification</option>
                                                                        @if (isset($allKeywords['NurseClassification']))
                                                                            @foreach ($allKeywords['NurseClassification'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="nurse_classificationAllValuesDraft" name="nurse_classification">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addnurse_classification('from_draft')"><i class="fa fa-plus"
                                                                            aria-hidden="true"></i></a></div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-nurse_classificationDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group ss-prsnl-frm-specialty">
                                                        <label>EMR</label>
                                                        <div class="ss-speilty-exprnc-add-list Emr-content">
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="EmrDraft">
                                                                        <option value="" disabled selected hidden>Select an
                                                                            emr</option>
                                                                        @if (isset($allKeywords['EMR']))
                                                                            @foreach ($allKeywords['EMR'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="EmrAllValuesDraft" name="Emr">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addEmr('from_draft')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-EmrDraft"></span>
                                                    </div>
                                       
                                                    <div id="benefits_id" class="d-none ss-form-group ss-prsnl-frm-specialty">
                                                        <label>Benefits</label>
                                                        <div class="ss-speilty-exprnc-add-list benefits-content">
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="benefitsDraft">
                                                                        <option value="" disabled selected hidden>Select a
                                                                            benefits</option>
                                                                        @if (isset($allKeywords['Benefits']))
                                                                            @foreach ($allKeywords['Benefits'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="benefitsAllValuesDraft" name="benefits">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addbenefits('from_draft')"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-benefitsDraft"></span>
                                                    </div>
                                       
                                       
                                       
                                                    <div class="ss-form-group ss-prsnl-frm-specialty">
                                                        <label>Certifications</label>
                                                        <div class="ss-speilty-exprnc-add-list certificate-content">
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="certificateDraft">
                                                                        <option value="" disabled selected hidden>Select
                                                                            Certification</option>
                                                                        @if (isset($allKeywords['Certification']))
                                                                            @foreach ($allKeywords['Certification'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="certificateAllValuesDraft" name="certificate">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addcertifications('from_draft')"><i class="fa fa-plus"
                                                                            aria-hidden="true"></i></a></div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-certificateDraft"></span>
                                                    </div>
                                                    <div class="ss-form-group ss-prsnl-frm-specialty">
                                                        <label>Vaccinations & Immunizations name</label>
                                                        <div class="ss-speilty-exprnc-add-list vaccinations-content">
                                       
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="vaccinationsDraft">
                                                                        <option value="" disabled selected hidden>Enter
                                                                            Vaccinations & Immunizations name</option>
                                                                        @if (isset($allKeywords['Vaccinations']))
                                                                            @foreach ($allKeywords['Vaccinations'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="vaccinationsAllValuesDraft" name="vaccinations">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addvacc('from_draft')"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-vaccinationsDraft"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group ss-prsnl-frm-specialty">
                                                        <label>Skills checklist</label>
                                                        <div class="ss-speilty-exprnc-add-list skills-content">
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="skillsDraft">
                                                                        <option value="" disabled selected hidden>Select
                                                                            Skills</option>
                                                                        @if (isset($allKeywords['Speciality']))
                                                                            @foreach ($allKeywords['Speciality'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="skillsAllValuesDraft" name="skills">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addskills('from_draft')"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-skillsDraft"></span>
                                                    </div>
                                       
                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnDraft">Save as draft</button>
                                                        <button class="prev-3Draft prev">Previous</button>
                                                        <button class="submitDraft">Submit</button>
                                                    </div>
                                       
                                                </div>
                                            </div>
                                       
                                            {{-- end slide added from sheets --}}
                                       
                                       
                                            <!-- Forth form slide for adding jobs -->
                                       
                                            <div class="page">
                                                <div class="row">
                                       
                                       
                                       
                                       
                                       
                                       
                                       
                                                    {{-- <div class="ss-form-group col-md-4">
                                                                                       <label>EMR</label>
                                                                                       <select name="Emr"
                                                                                           id="emr">
                                                                                           <option value="" disabled selected hidden>Select an EMR</option>
                                                                                           @foreach ($allKeywords['EMR'] as $value)
                                                                                               <option value="{{ $value->title }}">{{ $value->title }}
                                                                                               </option>
                                                                                           @endforeach
                                                                                       </select>
                                                                                       <span class="help-block-emr"></span>
                                                                                   </div> --}}
                                       
                                       
                                       
                                       
                                                    {{-- <div class="ss-form-group col-md-4">
                                                                                   <input type="text" name="responsibilities" id="responsibilities"
                                                                                       placeholder="Enter Responsibilities">
                                                                               </div>
                                       
                                                                               <div class="ss-form-group col-md-4">
                                                                                   <input type="text" name="qualifications" id="qualifications"
                                                                                       placeholder="Enter Qualifications">
                                                                               </div> --}}
                                       
                                                </div>
                                            </div>
                                        </form>
                                       
                                    </div>
                                </div>

                                <div id="no-job-for-draft" class="ss-account-form-lft-1 d-none text-center" style="width: 100%; margin-top: 0px;" ><span>No Job To Edit</span></div>

                                

                                <div id="job-details">
                                </div>
                            </div>
                        </div>
                        <!-- END EDITING Draft FORM -->



                        <!-- EDIT Work -->
                        <div class="all col-lg-7" id="details_edit_job">
                            <div class="bodyAll" style="width: 100%;">
                                <div class="ss-account-form-lft-1" style="width: 100%; margin-top: 0px;">
                                    <header><span style="color: #b5649e;">Edit</span> your selected job</header>
                                    <div class="row progress-bar-item d-none">
                                        <div class="col-3 step stepEdit">
                                            <p>Work information</p>
                                            <div class="d-none bullet">
                                                <span>1</span>
                                            </div>
                                            <div class="check fas fa-check"></div>
                                        </div>

                                        <div class=" col-2 step stepEdit">
                                            <p>Preferences and Requirements</p>
                                            <div class="d-none bullet">
                                                <span>2</span>
                                            </div>
                                            <div class="check fas fa-check"></div>
                                        </div>
                                        <div class="col-2 step stepEdit">
                                            <p>Work Details</p>
                                            <div class="d-none bullet">
                                                <span>3</span>
                                            </div>
                                            <div class="check fas fa-check"></div>
                                        </div>

                                        <div class="col-2 step stepEdit">
                                            <p>other information</p>
                                            <div class="d-none bullet">
                                                <span>4</span>
                                            </div>
                                            <div class="check fas fa-check"></div>
                                        </div>

                                        <div class="col-3 step stepEdit">
                                            <p>Work Schedule & Requirements</p>
                                            <div class="d-none bullet">
                                                <span>5</span>
                                            </div>
                                            <div class="check fas fa-check"></div>
                                        </div>
                                    </div>
                                    <div class="form-outer">
                                        <form method="post" id="create_job_form" action="{{ route('edit_job') }}">
                                            @csrf
                                            <!-- first form slide required inputs for adding jobs -->
                                       
                                            <div class=" page slide-pageEdit">
                                                <div class="row">
                                       
                                                    <div class="ss-form-group col-md-4 d-none">
                                                        <input type="text" name="active" id="activeEdit">
                                                    </div>
                                                    <div class="ss-form-group col-md-4 d-none">
                                                        <input type="text" name="is_open" id="is_openEdit">
                                                    </div>
                                                    <div class="ss-form-group col-md-4 d-none">
                                                        <input type="text" name="id" id="idEdit">
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Work Type</label>
                                                        <select name="job_type" id="job_typeEdit">
                                                            <option value="" disabled selected hidden>Select a Work type
                                                            </option>
                                                            <option value="Clinical">Clinical
                                                            </option>
                                                            <option value="Non-Clinical">Non-Clinical
                                                            </option>
                                                        </select>
                                       
                                                        <span class="help-block-job_typeEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Preferred Specialty</label>
                                                        <select name="preferred_specialty" id="preferred_specialtyEdit">
                                                            <option value="" disabled selected hidden>Select a specialty
                                                            </option>
                                                            @foreach ($specialities as $specialty)
                                                                <option value="{{ $specialty->full_name }}">
                                                                    {{ $specialty->full_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-preferred_specialtyEdit"></span>
                                                    </div>
                                       
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Preferred Profession</label>
                                                        <select name="profession" id="perferred_professionEdit">
                                                            <option value="" disabled selected hidden>Select a Profession
                                                            </option>
                                                            @foreach ($allKeywords['Profession'] as $value)
                                                                <option value="{{ $value->title }}">{{ $value->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-perferred_professionEdit"></span>
                                                    </div>
                                       
                                       
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label> Work State </label>
                                                        <select name="job_state" id="job_stateEdit">
                                                            <option value="" disabled selected hidden>Select a State</option>
                                                            @foreach ($states as $state)
                                                                <option id="{{ $state->id }}" value="{{ $state->name }}">
                                                                    {{ $state->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-job_stateEdit"></span>
                                       
                                       
                                       
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                       
                                       
                                                        <label> Work City </label>
                                                        <select name="job_city" id="job_cityEdit">
                                                            <option value="">Select a city</option>
                                                        </select>
                                       
                                                        <span class="help-block-job_cityEdit"></span>
                                                    </div>
                                       
                                       
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Weeks per Assignment</label>
                                                        <input type="number" name="preferred_assignment_duration" id="preferred_assignment_durationEdit"
                                                            placeholder="Enter Work Duration Per Assignment">
                                                        <span class="help-block-preferred_assignment_durationEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Est. Weekly Pay </label>
                                                        <input type="number" step="0.01" name="weekly_pay" id="weekly_payEdit" placeholder="Enter Weekly Pay">
                                                        <span class="help-block-weekly_payEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Terms</label>
                                                        <select name="terms" id="termsEdit">
                                                            <option value="" disabled selected hidden>Select a Term</option>
                                                            @foreach ($allKeywords['Terms'] as $value)
                                                                <option value="{{ $value->title }}">{{ $value->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-termsEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Est. Weekly non-taxable amount</label>
                                                        <input type="number" name="weekly_non_taxable_amount" id="weekly_non_taxable_amountEdit"
                                                            placeholder="Enter Weekly non-taxable amount">
                                                        <span class="help-block-weekly_non_taxable_amountEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Overtime Hourly rate</label>
                                                        <input type="number" name="overtime" id="overtimeEdit" placeholder="Enter actual Overtime Hourly rate">
                                                        <span class="help-block-overtimeEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Est. Taxable Hourly rate</label>
                                                        <input type="number" name="actual_hourly_rate" id="actual_hourly_rateEdit"
                                                            placeholder="Enter Taxable Regular Hourly rate">
                                                        <span class="help-block-actual_hourly_rateEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Pay Frequency</label>
                                                        <select name="pay_frequency" id="pay_frequencyEdit">
                                                            <option value="" disabled selected hidden>Select a pay frequency
                                                            </option>
                                                            @foreach ($allKeywords['PayFrequency'] as $value)
                                                                <option value="{{ $value->title }}">{{ $value->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-pay_frequencyEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Guaranteed Hours per week</label>
                                                        <input type="number" name="guaranteed_hours" id="guaranteed_hoursEdit"
                                                            placeholder="Enter Guaranteed Hours per week">
                                                        <span class="help-block-guaranteed_hoursEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Hours Per Week</label>
                                                        <input type="number" name="hours_per_week" id="hours_per_weekEdit" placeholder="Enter hours per week">
                                                        <span class="help-block-hours_per_weekEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Hours Per Shift</label>
                                                        <input type="number" name="hours_shift" id="hours_shiftEdit" placeholder="Enter Hours Per Shift">
                                                        <span class="help-block-hours_shiftEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Shift Per Weeks
                                                        </label>
                                                        <input type="number" name="weeks_shift" id="weeks_shiftEdit" placeholder="Enter Shift Per Weeks">
                                                        <span class="help-block-weeks_shiftEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Eligible work in us ?</label>
                                                        <select name="eligible_work_in_us" id="eligible_work_in_usEdit">
                                                            <option value="" disabled selected hidden>Select an answer
                                                            </option>
                                                            <option value="1">Yes
                                                            </option>
                                                            <option value="0">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-eligible_work_in_usEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Preferred Experience</label>
                                                        <input type="number" name="preferred_experience" id="preferred_experienceEdit"
                                                            placeholder="Enter Preferred Experience">
                                                        <span class="help-block-preferred_experienceEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group ss-prsnl-frm-specialty">
                                                        <label>Professional Licensure</label>
                                                        <div class="ss-speilty-exprnc-add-list professional_licensure-content">
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="professional_licensureEdit">
                                                                        <option value="" disabled selected hidden>Select a
                                                                            professional Licensure</option>
                                                                        @if (isset($allKeywords['StateCode']))
                                                                            @foreach ($allKeywords['StateCode'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="professional_licensureAllValuesEdit" name="job_location">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addprofessional_licensure('from_edit')"><i class="fa fa-plus"
                                                                            aria-hidden="true"></i></a></div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-professional_licensureEdit"></span>
                                                    </div>
                                       
                                       
                                       
                                                    <div class="ss-form-group ss-prsnl-frm-specialty">
                                                        <label>Shift Time of Day</label>
                                                        <div class="ss-speilty-exprnc-add-list shifttimeofday-content">
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="shifttimeofdayEdit">
                                                                        <option value="">Select Shift Time of Day</option>
                                                                        @if (isset($allKeywords['PreferredShift']))
                                                                            @foreach ($allKeywords['PreferredShift'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="shifttimeofdayAllValuesEdit" name="preferred_shift_duration">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addshifttimeofday('from_edit')"><i class="fa fa-plus"
                                                                            aria-hidden="true"></i></a></div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-shift_time_of_dayEdit"></span>
                                                    </div>
                                       
                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnEdit">Save as draft</button>
                                                        <button class="firstNextEdit next">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                       
                                       
                                            <!-- Second form slide required inputs for adding jobs -->
                                            <div class="page">
                                                <div class="row">
                                                    {{-- edits --}}
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Min distance from facility</label>
                                                        <input type="number" name="traveler_distance_from_facility" id="traveler_distance_from_facilityEdit"
                                                            placeholder="Enter travel distance">
                                                        <span class="help-block-traveler_distance_from_facilityEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Clinical Setting</label>
                                                        <select name="clinical_setting" id="clinical_settingEdit">
                                                            <option value="" disabled selected hidden>Select a setting
                                                            </option>
                                                            @foreach ($allKeywords['ClinicalSetting'] as $value)
                                                                <option value="{{ $value->title }}">{{ $value->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-clinical_settingEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Patient ratio</label>
                                                        <input type="number" name="Patient_ratio" id="Patient_ratioEdit" placeholder="Enter Patient ratio">
                                                        <span class="help-block-Patient_ratioEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Unit</label>
                                                        <input type="text" name="Unit" id="UnitEdit" placeholder="Enter Unit">
                                                        <span class="help-block-UnitEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Scrub Color</label>
                                                        <input type="text" name="scrub_color" id="scrub_colorEdit" placeholder="Enter scrub color">
                                                        <span class="help-block-scrub_colorEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Rto</label>
                                                        <select name="rto" id="rtoEdit">
                                                            <option value="" disabled selected hidden>Select an Rto</option>
                                                            <option value="allowed">Allowed
                                                            </option>
                                                            <option value="not allowed">Not Allowed
                                                            </option>
                                                        </select>
                                                        <span class="help-block-rtoEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label> Work Id</label>
                                                        <input type="text" name="job_id" id="job_idEdit" placeholder="Enter Work Id">
                                                        <span class="help-block-job_idEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label> Work Name</label>
                                                        <input type="text" name="job_name" id="job_nameEdit" placeholder="Enter Work name">
                                                        <span class="help-block-job_nameEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Preferred Work Location</label>
                                                        <input type="text" name="preferred_work_location" id="preferred_work_locationEdit"
                                                            placeholder="Enter Work Location">
                                                        <span class="help-block-preferred_work_locationEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Referral Bonus</label>
                                                        <input type="number" name="referral_bonus" id="referral_bonusEdit" placeholder="Enter referral bonus">
                                                        <span class="help-block-referral_bonusEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Sign on Bonus</label>
                                                        <input type="number" name="sign_on_bonus" id="sign_on_bonusEdit" placeholder="Enter sign on bonus">
                                                        <span class="help-block-sign_on_bonusEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Completion Bonus</label>
                                                        <input type="number" name="completion_bonus" id="completion_bonusEdit"
                                                            placeholder="Enter completion bonus">
                                                        <span class="help-block-completion_bonusEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Extension Bonus</label>
                                       
                                                        <input type="number" name="extension_bonus" id="extension_bonusEdit"
                                                            placeholder="Enter extension bonus">
                                                        <span class="help-block-extension_bonusEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Other bonus</label>
                                                        <input type="number" name="other_bonus" id="other_bonusEdit" placeholder="Enter other bonus">
                                                        <span class="help-block-other_bonusEdit"></span>
                                                    </div>
                                       
                                       
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>On Call</label>
                                                        <select name="on_call" id="on_callEdit">
                                                            <option value="" disabled selected hidden>Select an answer
                                                            </option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-on_callEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>On Call Rate</label>
                                                        <input type="number" name="on_call_rate" id="on_call_rateEdit"
                                                            placeholder="Enter a call back hourly rate">
                                                        <span class="help-block-on_call_rateEdit"></span>
                                                    </div>
                                       
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Work Description</label>
                                                        <textarea type="text" name="description" id="descriptionEdit" placeholder="Enter Work Description"></textarea>
                                                        <span class="help-block-descriptionEdit"></span>
                                                    </div>
                                                                         
                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnEdit">Save as draft</button>
                                                        <button class="prev-1Edit prev">Previous</button>
                                                        <button class="next-1Edit next">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                       
                                            <!-- Third form slide required inputs for adding jobs -->
                                       
                                            <div class="page">
                                                <div class="row">
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Holidy date</label>
                                                        <input type="date" name="holiday" id="holidayEdit" placeholder="Enter Holidy hourly rate">
                                                        <span class="help-block-holidayEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Orientation Hourly rate</label>
                                                        <input type="number" name="orientation_rate" id="orientation_rateEdit"
                                                            placeholder="Enter Orientation Hourly rate">
                                                        <span class="help-block-orientation_rateEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Block scheduling</label>
                                                        <select name="block_scheduling" id="block_schedulingEdit">
                                                            <option value="" disabled selected hidden>Select an answer
                                                            </option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-block_schedulingEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Float requirements</label>
                                                        <select name="float_requirement" id="float_requirementEdit">
                                                            <option value="" disabled selected hidden>Select an answer
                                                            </option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-float_requirementEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Number Of References</label>
                                                        <input type="number" name="number_of_references" id="number_of_referencesEdit"
                                                            placeholder="Enter number of references">
                                                        <span class="help-block-number_of_referencesEdit"></span>
                                                    </div>
                                       
                                       
                                       
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Facility's Parent System</label>
                                                        <input type="text" name="facilitys_parent_system" id="facilitys_parent_systemEdit"
                                                            placeholder="Enter facility's parent system">
                                                        <span class="help-block-facilitys_parent_systemEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Facility Name</label>
                                                        <input type="text" name="facility_name" id="facility_nameEdit" placeholder="Enter facility name">
                                                        <span class="help-block-facility_nameEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Contract Termination Policy</label>
                                                        <input type="text" name="contract_termination_policy" id="contract_termination_policyEdit"
                                                            placeholder="Enter your contract termination policy">
                                                        <span class="help-block-contract_termination_policyEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Shift Cancellation Policy</label>
                                                        <input type="text" name="facility_shift_cancelation_policy"
                                                            id="facility_shift_cancelation_policyEdit" placeholder="Select your shift cancellation policy">
                                                        <span class="help-block-facility_shift_cancelation_policyEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>401K</label>
                                                        <select name="four_zero_one_k" id="four_zero_one_kEdit">
                                                            <option value="" disabled selected hidden>Select an answer
                                                            </option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-four_zero_one_kEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Health Insurance</label>
                                                        <select name="health_insaurance" id="health_insauranceEdit">
                                                            <option value="" disabled selected hidden>Select a Health
                                                                Insurance</option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-health_insauranceEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Feels Like $/hrs</label>
                                                        <input type="number" name="feels_like_per_hour" id="feels_like_per_hourEdit"
                                                            placeholder="Enter Feels Like $/hrs">
                                                        <span class="help-block-feels_like_per_hourEdit"></span>
                                                    </div>
                                       
                                       
                                       
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Call Back Hourly rate</label>
                                                        <input type="number" name="call_back_rate" id="call_back_rateEdit"
                                                            placeholder="Enter Call Back Hourly rate">
                                                        <span class="help-block-call_back_rateEdit"></span>
                                                        
                                                    </div>
                                       
                                                    <div class="ss-form-group col-md-12">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-sm-12 col-md-12 col-xs-12" style="margin: 20px 0px;">
                                                                <label>Start Date</label>
                                                            </div>
                                                            <div class="row col-lg-6 col-sm-12 col-md-12 col-xs-12"
                                                                style="display: flex; justify-content: end; align-items:center;">
                                                                <input id="as_soon_asEdit" name="as_soon_as" value="1" type="checkbox"
                                                                    style="box-shadow:none; width:auto;" class="col-6">
                                                                <label class="col-6">
                                                                    As soon As possible
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <input id="start_dateEdit" type="date" min="2024-03-06" name="start_date" placeholder="Select Date"
                                                            value="2024-03-06">
                                                    </div>
                                                    <span class="help-block-start_dateEdit"></span>
                                       
                                                    <div class="row ss-form-group col-md-4 d-flex justify-content-end" style="margin-left: 17px; padding-bottom: 20px;">
                                                        <label style="padding-bottom: 25px; padding-top: 25px;">Urgency</label>
                                                        <div class="row justify-content-center" style="display:flex; align-items:end;">
                                                            <label class="col-6" for="urgency" style="display:flex; justify-content:end;">Auto
                                                                Offer</label>
                                                            <div class="col-6">
                                                                <input type="checkbox" name="urgency" id="urgencyEdit" value="Auto Offer"
                                                                    style="box-shadow: none;">
                                                            </div>
                                                        </div>
                                       
                                                        <span class="help-block-urgencyEdit"></span>
                                                    </div>
                                       
                                                    <div class="vr p-0" style="margin-left: 30px; margin-right: 30px; margin-bottom: 10px;"></div>
                                       
                                       
                                                    <div class="row ss-form-group d-flex justify-content-end col-md-7" style="padding-bottom: 20px;">
                                                        <label style="padding-bottom: 25px; padding-top: 25px;">Professional State Licensure</label>
                                       
                                                        <div class="row col-6 justify-content-center align-items-end">
                                                            <label class="col-7" for="professional_state_licensure_pending">Accept
                                                                Pending
                                                            </label>
                                                            <div class="col-5">
                                                                <input type="radio" id="professional_state_licensure_pendingEdit"
                                                                    name="professional_state_licensure" value="Accept Pending" style="box-shadow: none;">
                                                            </div>
                                                        </div>
                                                        {{-- Radio option for "Active" --}}
                                                        <div class="row col-6 justify-content-center align-items-end">
                                                            <label class="col-7" for="professional_state_licensure_active">
                                                                Active
                                                            </label>
                                                            <div class="col-5">
                                                                <input type="radio" id="professional_state_licensure_activeEdit"
                                                                    name="professional_state_licensure" value="Active" style="box-shadow: none;">
                                                            </div>
                                                        </div>
                                       
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    {{-- Removed the select element and its options --}}
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-professional_state_licensureEdit"></span>
                                                    </div>
                                       
                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnEdit">Save as draft</button>
                                                        <button class="prev-2Edit prev">Previous</button>
                                                        <button class="next-2Edit next">Next</button>
                                       
                                                    </div>
                                       
                                                </div>
                                            </div>
                                       
                                            {{-- slide added from sheets --}}
                                       
                                            <div class="page">
                                                <div class="row">
                                       
                                       
                                                    {{-- <div class="ss-form-group col-md-4">
                                                                                       <label>Worker Classification</label>
                                                                                       <select name="nurse_classification" id="nurse_classification">
                                                                                           <option value="" disabled selected hidden>Select a Worker Classification</option>
                                                                                           @foreach ($allKeywords['NurseClassification'] as $value)
                                                                                               <option value="{{ $value->title }}">{{ $value->title }}
                                                                                               </option>
                                                                                           @endforeach
                                                                                       </select>
                                                                                       <span class="help-block-nurse_classification"></span>
                                                                                   </div> --}}
                                       
                                       
                                       
                                       
                                       
                                       
                                       
                                       
                                       
                                                    {{-- <div class="row ss-form-group col-md-4 d-flex justify-content-end">
                                                                                       <label>Urgency</label>
                                                                                       <div class="row" style="display:flex; align-items:end;">
                                                                                           <label class="col-6" for="urgency" style="display:flex; justify-content:end;">Auto Offer</label>
                                                                                           <div class="col-6">
                                                                                               <input type="checkbox" name="urgency" id="urgency" value="Auto Offer" style="box-shadow: none;">
                                                                                           </div>
                                                                                       </div>
                                                                                       <span class="help-block-urgency"></span>
                                                                                   </div> --}}
                                       
                                       
                                       
                                                    {{-- <div class="ss-form-group col-md-4">
                                                                                       <label>Professional Licensure</label>
                                                                                       <select name="job_location" id="job_location">
                                                                                           <option value="" disabled selected hidden>Select a Professional Licensure</option>
                                                                                           @foreach ($allKeywords['StateCode'] as $value)
                                                                                               <option value="{{ $value->title }}">{{ $value->title }} Compact
                                                                                               </option>
                                                                                           @endforeach
                                                                                       </select>
                                                                                       <span class="help-block-job_location"></span>
                                                                                   </div> --}}
                                       
                                                    <div class="ss-form-group ss-prsnl-frm-specialty">
                                                        <label>Worker Classification</label>
                                                        <div class="ss-speilty-exprnc-add-list nurse_classification-content">
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="nurse_classificationEdit">
                                                                        <option value="" disabled selected hidden>Select a
                                                                            Worker Classification</option>
                                                                        @if (isset($allKeywords['NurseClassification']))
                                                                            @foreach ($allKeywords['NurseClassification'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="nurse_classificationAllValuesEdit" name="nurse_classification">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addnurse_classification('from_edit')"><i class="fa fa-plus"
                                                                            aria-hidden="true"></i></a></div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-nurse_classificationEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group ss-prsnl-frm-specialty">
                                                        <label>EMR</label>
                                                        <div class="ss-speilty-exprnc-add-list Emr-content">
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="EmrEdit">
                                                                        <option value="" disabled selected hidden>Select an
                                                                            emr</option>
                                                                        @if (isset($allKeywords['EMR']))
                                                                            @foreach ($allKeywords['EMR'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="EmrAllValuesEdit" name="Emr">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addEmr('from_edit')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-EmrEdit"></span>
                                                    </div>
                                       
                                                    <div id="benefits_id" class="d-none ss-form-group ss-prsnl-frm-specialty">
                                                        <label>Benefits</label>
                                                        <div class="ss-speilty-exprnc-add-list benefits-content">
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="benefitsEdit">
                                                                        <option value="" disabled selected hidden>Select a
                                                                            benefits</option>
                                                                        @if (isset($allKeywords['Benefits']))
                                                                            @foreach ($allKeywords['Benefits'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="benefitsAllValuesEdit" name="benefits">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addbenefits('from_edit')"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-benefitsEdit"></span>
                                                    </div>
                                       
                                       
                                       
                                                    <div class="ss-form-group ss-prsnl-frm-specialty">
                                                        <label>Certifications</label>
                                                        <div class="ss-speilty-exprnc-add-list certificate-content">
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="certificateEdit">
                                                                        <option value="" disabled selected hidden>Select
                                                                            Certification</option>
                                                                        @if (isset($allKeywords['Certification']))
                                                                            @foreach ($allKeywords['Certification'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="certificateAllValuesEdit" name="certificate">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addcertifications('from_edit')"><i class="fa fa-plus"
                                                                            aria-hidden="true"></i></a></div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-certificateEdit"></span>
                                                    </div>
                                                    <div class="ss-form-group ss-prsnl-frm-specialty">
                                                        <label>Vaccinations & Immunizations name</label>
                                                        <div class="ss-speilty-exprnc-add-list vaccinations-content">
                                       
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="vaccinationsEdit">
                                                                        <option value="" disabled selected hidden>Enter
                                                                            Vaccinations & Immunizations name</option>
                                                                        @if (isset($allKeywords['Vaccinations']))
                                                                            @foreach ($allKeywords['Vaccinations'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="vaccinationsAllValuesEdit" name="vaccinations">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addvacc('from_edit')"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-vaccinationsEdit"></span>
                                                    </div>
                                       
                                                    <div class="ss-form-group ss-prsnl-frm-specialty">
                                                        <label>Skills checklist</label>
                                                        <div class="ss-speilty-exprnc-add-list skills-content">
                                                        </div>
                                                        <ul>
                                                            <li class="row w-100 p-0 m-0">
                                                                <div class="ps-0">
                                                                    <select class="m-0" id="skillsEdit">
                                                                        <option value="" disabled selected hidden>Select
                                                                            Skills</option>
                                                                        @if (isset($allKeywords['Speciality']))
                                                                            @foreach ($allKeywords['Speciality'] as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="skillsAllValuesEdit" name="skills">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                                        onclick="addskills('from_edit')"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <span class="help-block-skillsEdit"></span>
                                                    </div>
                                       
                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnEdit">Save as draft</button>
                                                        <button class="prev-3Edit prev">Previous</button>
                                                        <button class="submitEdit">Submit</button>
                                                    </div>
                                       
                                                </div>
                                            </div>
                                       
                                            {{-- end slide added from sheets --}}
                                       
                                       
                                            <!-- Forth form slide for adding jobs -->
                                       
                                            <div class="page">
                                                <div class="row">
                                       
                                       
                                       
                                       
                                       
                                       
                                       
                                                    {{-- <div class="ss-form-group col-md-4">
                                                                                       <label>EMR</label>
                                                                                       <select name="Emr"
                                                                                           id="emr">
                                                                                           <option value="" disabled selected hidden>Select an EMR</option>
                                                                                           @foreach ($allKeywords['EMR'] as $value)
                                                                                               <option value="{{ $value->title }}">{{ $value->title }}
                                                                                               </option>
                                                                                           @endforeach
                                                                                       </select>
                                                                                       <span class="help-block-emr"></span>
                                                                                   </div> --}}
                                       
                                       
                                       
                                       
                                                    {{-- <div class="ss-form-group col-md-4">
                                                                                   <input type="text" name="responsibilities" id="responsibilities"
                                                                                       placeholder="Enter Responsibilities">
                                                                               </div>
                                       
                                                                               <div class="ss-form-group col-md-4">
                                                                                   <input type="text" name="qualifications" id="qualifications"
                                                                                       placeholder="Enter Qualifications">
                                                                               </div> --}}
                                       
                                                </div>
                                            </div>
                                        </form>
                                       
                                    </div>
                                </div>

                                <div id="job-details">
                                </div>
                            </div>
                        </div>
                        <!-- END Work EDiTING FORM -->



                        <!-- published details start-->

                        <div class="col-lg-7 d-none" id="details_published">
                            <div class="ss-journy-svd-jbdtl-dv">
                                <div class="ss-job-dtls-view-bx" style="border:2px solid #111011; padding-bottom:10px;">
                                    <div class="row" id="application-details-apply">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- published details end-->

                        <!-- onhold details start-->
                        <div class="col-lg-7 d-none" id="details_onhold">
                            <div class="ss-journy-svd-jbdtl-dv">
                                <div class="ss-job-dtls-view-bx" style="border:2px solid #111011; padding-bottom:10px;">
                                    <div class="row" id="application-details-apply-onhold">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- onhold details end-->

                        <!-- draft details start-->
                        <div class="col-lg-7 d-none" id="details_draft_none">
                            <div class="ss-journy-svd-jbdtl-dv">
                                <div class="ss-job-dtls-view-bx" style="border:2px solid #111011; padding-bottom:10px;">
                                    <div class="row" id="application-details-apply-draft">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- draft details end-->




                    </div>
                </div>
            </div>
        </div>



    </main>


    
{{-- script managing certifs  --}}

<script>

    const requiredToSubmit = @json($requiredFieldsToSubmit);
    
   function toggleActiveClass(workerUserId,type) {
            console.log("test");
            console.log(workerUserId);
            console.log(type);
            var element = document.getElementById(workerUserId);
            console.log(element);
            element.classList.add('active');
            
            var allElements = document.getElementsByClassName(type);
            console.log(allElements);

            for (var i = 0; i < allElements.length; i++) {
                if (allElements[i].classList.contains('active')) {
                    allElements[i].classList.remove('active');
                }
            }
            if (!element.classList.contains('active')) {
                element.classList.add('active');
            }
    }
    function addcertifications(type) {
        var id;
        var idtitle;
        if (type == 'from_add') {
            id = $('#certificate');
            idtitle = "certificate";
        } else if (type == 'from_draft') {
            id = $('#certificateDraft');
            idtitle = "certificateDraft";
        } else {
            id = $('#certificateEdit');
            idtitle = "certificateEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the certificate please.',
                time: 3
            });
        } else {
            if (!certificate.hasOwnProperty(id.val())) {

                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                certificate[id.val()] = optionText;

                certificateStr = Object.values(certificate).join(', ');
                // console.log(certificate);
                id.val('');
                list_certifications();
            }
        }

    }

    function list_certifications() {
        var str = '';
        // let certificatename = "";
        // certificatename = Object.values(certificate).join(', ');
        // console.log(certificatename);
        // document.getElementById("certificateEdit").value = certificatename;
        // console.log(certificate);

        for (const key in certificate) {
            console.log(certificate);

            let certificatename = "";
            @php
                $allKeywordsJSON = json_encode($allKeywords['Certification']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (certificate.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);

                data.forEach(function(item) {
                    if (key == item.id) {
                        certificatename = item.title;
                    }
                });
                const value = certificate[key];
                str += '<ul>';
                str += '<li class="w-50">' + certificatename + '</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-certificate" data-key="' +
                    key + '" onclick="remove_certificate(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.certificate-content').html(str);
    }

    function remove_certificate(obj, key) {
        if (certificate.hasOwnProperty(key)) {
            delete certificate[key];

            // to dlete the removed certificate from the hidden input
            certificateStr = Object.values(certificate).join(', ');

            list_certifications(); // Refresh the list to reflect the deletion
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Certificate removed successfully.',
                time: 3
            });
        } else {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-times"></i> Certificate not found.',
                time: 3
            });
        }
    }
</script>

{{-- script managing vaccinations  --}}
<script>
    

    function addvacc(type) {
        let id;
        let idtitle;
        if (type == 'from_add') {
            id = $('#vaccinations');
            idtitle = "vaccinations";
        } else if (type == 'from_draft') {
            id = $('#vaccinationsDraft');
            idtitle = "vaccinationsDraft";
        } else {
            id = $('#vaccinationsEdit');
            idtitle = "vaccinationsEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the vaccinations please.',
                time: 3
            });
        } else {
            if (!vaccinations.hasOwnProperty(id.val())) {
                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                vaccinations[id.val()] = optionText;
                vaccinationsStr = Object.values(vaccinations).join(', ');
                id.val('');
                list_vaccinations();
            }
        }
    }

    function list_vaccinations() {
        var str = '';
        console.log(vaccinations);

        for (const key in vaccinations) {
            console.log(vaccinations);

            let vaccinationsname = "";
            @php
                $allKeywordsJSON = json_encode($allKeywords['Vaccinations']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (vaccinations.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);

                data.forEach(function(item) {
                    if (key == item.id) {
                        vaccinationsname = item.title;
                    }
                });
                const value = vaccinations[key];
                str += '<ul>';
                str += '<li>' + vaccinationsname + '</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-vaccinations" data-key="' +
                    key + '" onclick="remove_vaccinations(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.vaccinations-content').html(str);
    }

    function remove_vaccinations(obj, key) {
        if (vaccinations.hasOwnProperty(key)) {
            delete vaccinations[key]; // Remove the vaccination from the object
            vaccinationsStr = Object.values(vaccinations).join(', '); // Update the hidden input value
            list_vaccinations(); // Refresh the list to reflect the deletion
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Vaccination removed successfully.',
                time: 3
            });
        } else {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-times"></i> Vaccination not found.',
                time: 3
            });
        }
    }
</script>

{{-- script managing skills --}}

<script>
   

    function addskills(type) {
        let id;
        let idtitle;
        if (type == 'from_add') {
            id = $('#skills');
            idtitle = "skills";
        } else if (type == 'from_draft') {
            id = $('#skillsDraft');
            idtitle = "skillsDraft";
        } else {
            id = $('#skillsEdit');
            idtitle = "skillsEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the skills please.',
                time: 3
            });
        } else {
            if (!skills.hasOwnProperty(id.val())) {
                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                skills[id.val()] = optionText;
                skillsStr = Object.values(skills).join(', ');
                id.val('');
                list_skills();
            }
        }
    }

    function list_skills() {
        var str = '';
        console.log('fomr skiiiils', skills);

        for (const key in skills) {
            console.log('skills', skills);


            let skillsname = "";
            let allspcldata = @json($allKeywords['Speciality']);

            console.log('allspcldata', allspcldata);

            if (skills.hasOwnProperty(key)) {
                var data = allspcldata;

                data.forEach(function(item) {
                    if (key == item.id) {
                        skillsname = item.title;
                    }
                });
                const value = skills[key];
                str += '<ul>';
                str += '<li>' + skillsname + '</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-skills" data-key="' + key +
                    '" onclick="remove_skills(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.skills-content').html(str);
    }

    function remove_skills(obj, key) {
        if (skills.hasOwnProperty(key)) {
            delete skills[key]; // Remove the skill from the object
            skillsStr = Object.values(skills).join(', '); // Update the hidden input value
            list_skills(); // Refresh the list to reflect the deletion
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Skill removed successfully.',
                time: 3
            });
        } else {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-times"></i> Skill not found.',
                time: 3
            });
        }
    }
</script>

{{-- script managing shifttimeofday --}}
<script>
    
    function addshifttimeofday(type) {
        let id;
        let idtitle;
        if (type == 'from_add') {
            id = $('#shifttimeofday');
            idtitle = "shifttimeofday";
        } else if (type == 'from_draft') {
            id = $('#shifttimeofdayDraft');
            idtitle = "shifttimeofdayDraft";
        } else {
            id = $('#shifttimeofdayEdit');
            idtitle = "shifttimeofdayEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the shifttimeofday please.',
                time: 3
            });
        } else {
            if (!shifttimeofday.hasOwnProperty(id.val())) {
                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                shifttimeofday[id.val()] = optionText;
                shifttimeofdayStr = Object.values(shifttimeofday).join(', ');
                console.log('shifttimeofdayStr', shifttimeofdayStr);
                id.val('');
                list_shifttimeofday();
            }
        }
    }

    function list_shifttimeofday() {
        var str = '';
        console.log(shifttimeofday);

        for (const key in shifttimeofday) {
            console.log(shifttimeofday);

            let shifttimeofdayname = "";
            @php
                $allKeywordsJSON = json_encode($allKeywords['PreferredShift']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (shifttimeofday.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);

                data.forEach(function(item) {
                    if (key == item.id) {
                        shifttimeofdayname = item.title;
                    }
                });
                const value = shifttimeofday[key];
                str += '<ul>';
                str += '<li>' + shifttimeofdayname + '</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-shifttimeofday" data-key="' +
                    key + '" onclick="remove_shifttimeofday(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.shifttimeofday-content').html(str);
    }

    function remove_shifttimeofday(obj, key) {
        if (shifttimeofday.hasOwnProperty(key)) {
            delete shifttimeofday[key]; // Remove the shifttimeofday from the object
            shifttimeofdayStr = Object.values(shifttimeofday).join(', '); // Update the hidden input value
            list_shifttimeofday(); // Refresh the list to reflect the deletion
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Shift Time Of Day removed successfully.',
                time: 3
            });
        } else {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-times"></i> Shift Time Of Day not found.',
                time: 3
            });
        }
    }
</script>

{{-- script managing benefits --}}
<script>
    

    function addbenefits(type) {
        let id;
        let idtitle;
        if (type == 'from_add') {
            id = $('#benefits');
            idtitle = "benefits";
        } else if (type == 'from_draft') {
            id = $('#benefitsDraft');
            idtitle = "benefitsDraft";
        } else {
            id = $('#benefitsEdit');
            idtitle = "benefitsEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the benefits please.',
                time: 3
            });
        } else {
            if (!benefits.hasOwnProperty(id.val())) {
                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                benefits[id.val()] = optionText;
                benefitsStr = Object.values(benefits).join(', ');
                console.log('benefitsStr', benefitsStr);
                id.val('');
                list_benefits();
            }
        }
    }

    function list_benefits() {
        var str = '';
        console.log(benefits);

        for (const key in benefits) {
            console.log(benefits);

            let benefitsname = "";
            @php
                $allKeywordsJSON = json_encode($allKeywords['Benefits']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (benefits.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);

                data.forEach(function(item) {
                    if (key == item.id) {
                        benefitsname = item.title;
                    }
                });
                const value = benefits[key];
                str += '<ul>';
                str += '<li>' + benefitsname + '</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-benefits" data-key="' + key +
                    '" onclick="remove_benefits(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.benefits-content').html(str);
    }

    function remove_benefits(obj, key) {
        if (benefits.hasOwnProperty(key)) {
            delete benefits[key]; // Remove the benefits from the object
            benefitsStr = Object.values(benefits).join(', '); // Update the hidden input value
            list_benefits(); // Refresh the list to reflect the deletion
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Shift Time Of Day removed successfully.',
                time: 3
            });
        } else {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-times"></i> Shift Time Of Day not found.',
                time: 3
            });
        }
    }
</script>

{{-- script managing professional_licensure  --}}
<script>
   

    function addprofessional_licensure(type) {
        let id;
        let idtitle;
        if (type == 'from_add') {
            id = $('#professional_licensure');
            idtitle = "professional_licensure";
        } else if (type == 'from_draft') {
            id = $('#professional_licensureDraft');
            idtitle = "professional_licensureDraft";
        } else {
            id = $('#professional_licensureEdit');
            idtitle = "professional_licensureEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the professional_licensure please.',
                time: 3
            });
        } else {
            if (!professional_licensure.hasOwnProperty(id.val())) {
                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                professional_licensure[id.val()] = optionText;
                professional_licensureStr = Object.values(professional_licensure).join(', ');
                console.log('professional_licensureStr', professional_licensureStr);
                id.val('');
                list_professional_licensure();
            }
        }
    }

    function list_professional_licensure() {
        var str = '';
        console.log(professional_licensure);

        for (const key in professional_licensure) {
            console.log(professional_licensure);

            let professional_licensurename = "";
            @php
                $allKeywordsJSON = json_encode($allKeywords['StateCode']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (professional_licensure.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);

                data.forEach(function(item) {
                    if (key == item.id) {
                        professional_licensurename = item.title;
                    }
                });
                const value = professional_licensure[key];
                str += '<ul>';
                str += '<li>' + professional_licensurename + '</li>';
                str +=
                    '<li class="w-50 text-end pe-3"><button type="button"  id="remove-professional_licensure" data-key="' +
                    key + '" onclick="remove_professional_licensure(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.professional_licensure-content').html(str);
    }

    function remove_professional_licensure(obj, key) {
        if (professional_licensure.hasOwnProperty(key)) {
            delete professional_licensure[key]; // Remove the professional_licensure from the object
            professional_licensureStr = Object.values(professional_licensure).join(
            ', '); // Update the hidden input value
            list_professional_licensure(); // Refresh the list to reflect the deletion
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Shift Time Of Day removed successfully.',
                time: 3
            });
        } else {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-times"></i> Shift Time Of Day not found.',
                time: 3
            });
        }
    }
</script>

{{-- script managing Emr  --}}
<script>
    

    function addEmr(type) {
        let id;
        let idtitle;
        if (type == 'from_add') {
            id = $('#Emr');
            idtitle = "Emr";
        } else if (type == 'from_draft') {
            id = $('#EmrDraft');
            idtitle = "EmrDraft";
        } else {
            id = $('#EmrEdit');
            idtitle = "EmrEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the Emr please.',
                time: 3
            });
        } else {
            if (!Emr.hasOwnProperty(id.val())) {
                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                Emr[id.val()] = optionText;
                EmrStr = Object.values(Emr).join(', ');
                console.log('EmrStr', EmrStr);
                id.val('');
                list_Emr();
            }
        }
    }

    function list_Emr() {
        var str = '';
        console.log(Emr);

        for (const key in Emr) {
            console.log(Emr);

            let Emrname = "";
            @php
                $allKeywordsJSON = json_encode($allKeywords['EMR']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (Emr.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);

                data.forEach(function(item) {
                    if (key == item.id) {
                        Emrname = item.title;
                    }
                });
                const value = Emr[key];
                str += '<ul>';
                str += '<li>' + Emrname + '</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-Emr" data-key="' + key +
                    '" onclick="remove_Emr(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.Emr-content').html(str);
    }
    

    function remove_Emr(obj, key) {
        if (Emr.hasOwnProperty(key)) {
            delete Emr[key]; // Remove the Emr from the object
            EmrStr = Object.values(Emr).join(', '); // Update the hidden input value
            list_Emr(); // Refresh the list to reflect the deletion
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Emr removed successfully.',
                time: 3
            });
        } else {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-times"></i> Shift Time Of Day not found.',
                time: 3
            });
        }
    }
</script>

{{-- script managing nurse_classification  --}}
<script>
    

    function addnurse_classification(type) {
        let id;
        let idtitle;
        if (type == 'from_add') {
            id = $('#nurse_classification');
            idtitle = "nurse_classification";
        } else if (type == 'from_draft') {
            id = $('#nurse_classificationDraft');
            idtitle = "nurse_classificationDraft";
        } else {
            id = $('#nurse_classificationEdit');
            idtitle = "nurse_classificationEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the nurse_classification please.',
                time: 3
            });
        } else {
            if (!nurse_classification.hasOwnProperty(id.val())) {
                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                nurse_classification[id.val()] = optionText;
                nurse_classificationStr = Object.values(nurse_classification).join(', ');
                console.log('nurse_classificationStr', nurse_classificationStr);
                id.val('');
                list_nurse_classification();
            }
        }
    }

    function list_nurse_classification() {
        var str = '';
        console.log(nurse_classification);

        for (const key in nurse_classification) {
            console.log(nurse_classification);

            let nurse_classificationname = "";
            @php
                $allKeywordsJSON = json_encode($allKeywords['NurseClassification']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (nurse_classification.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);

                data.forEach(function(item) {
                    if (key == item.id) {
                        nurse_classificationname = item.title;
                    }
                });
                const value = nurse_classification[key];
                str += '<ul>';
                str += '<li>' + nurse_classificationname + '</li>';
                str +=
                    '<li class="w-50 text-end pe-3"><button type="button"  id="remove-nurse_classification" data-key="' +
                    key + '" onclick="remove_nurse_classification(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.nurse_classification-content').html(str);
    }

    function remove_nurse_classification(obj, key) {
        if (nurse_classification.hasOwnProperty(key)) {
            delete nurse_classification[key]; // Remove the nurse_classification from the object
            nurse_classificationStr = Object.values(nurse_classification).join(', '); // Update the hidden input value
            list_nurse_classification(); // Refresh the list to reflect the deletion
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Shift Time Of Day removed successfully.',
                time: 3
            });
        } else {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-times"></i> Shift Time Of Day not found.',
                time: 3
            });
        }
    }
</script>

    <script>
        
         var certificate = {};
         var vaccinations = {};
         var skills = {};
         var shifttimeofday = {};
         var benefits = {};
         var professional_licensure = {};
         var nurse_classification = {};
         var Emr = {};

        function fillData() {
            const fields = {
                'job_id': 1,  // type number
                'job_name': 'job name', // type input text
                'job_type': 'Clinical', // type select
                'preferred_specialty': 'Adult Medicine', // type select
                'perferred_profession': 'Clerical', // type select
                'job_state': 'Arizona', // type select
                'weekly_pay': 250, // type number
                'terms': 'Shift (PRN, Per Diem)', // type select
                'preferred_assignment_duration': 3, // type number
                'facility_shift_cancelation_policy': 'shift cancellation policy', // type text
                'traveler_distance_from_facility': 50, // type number
                'clinical_setting': 'Clinic', // type select
                'Patient_ratio': 20, // type number 
                'Unit': 'Unit', // type text
                'scrub_color': 'Blue', // type text
                'rto': 'allowed', // type select 
                'guaranteed_hours': 7, // type number 
                'hours_per_week': 30, // type number
                'hours_shift': 5, // type number 
                'weeks_shift': 3, // type number 
                'referral_bonus': 30, // type number
                'sign_on_bonus': 10,  // type number
                'completion_bonus': 3, // type number
                'extension_bonus': 3, // type number
                'other_bonus': 3, // type number
                'actual_hourly_rate': 3, // type number
                'overtime': 3, // type number 
                'on_call': 'Yes', // type no/yes select
                'on_call_rate': 10, // type number
                'holiday': '2025-04-27', // type date
                'orientation_rate': 19, // type number
                'block_scheduling': 'No', // type no/yes select
                'float_requirement': 'No', // type no/yes select
                'number_of_references': 1, // type number
                'eligible_work_in_us': 0, // type select
                'urgency': 'Auto Offer', // type checkbox
                'facilitys_parent_system': 'Parent system', // type text
                'facility_name': 'Facility Name', // type text
                'pay_frequency': 'Daily', // type select
                'preferred_experience': 'preferred experience', // type number
                'contract_termination_policy': 'Contract policy', // type text
                'four_zero_one_k': 'No',  // type no/yes select
                'health_insaurance': 'No', // type no/yes select
                'feels_like_per_hour': 5, // type number 
                'call_back_rate': 16, // type number
                'weekly_non_taxable_amount': 100, // type number
                'start_date': '2025-04-27', // type date
                'preferred_experience': 10 // type number

            };

            for (const [id, value] of Object.entries(fields)) {
                document.getElementById(id).value = value;
            }
        }

        function checkWorkerClassification() {
            let benefitsElement = document.getElementById("benefits_id");
            benefitsElement.classList.remove('d-none');
            // let workerClassification = document.getElementById("nurse_classification");
            // workerClassification.addEventListener('change',function(){
            //     workerClassificationValue = this.value;
            //     let benefitsElement = document.getElementById("benefits_id");
            //     if(workerClassificationValue == 'W-2'){
            //         benefitsElement.classList.remove('d-none');
            //     }else{
            //         benefitsElement.classList.add('d-none');
            //     }
            // });
        }

        document.addEventListener('DOMContentLoaded', async function() {
            //fillData();
            // let workerClassification = document.getElementById("nurse_classification");
            // workerClassificationValue = workerClassification.value;
            // if(workerClassificationValue == 'W-2'){
            //     let benefitsElement = document.getElementById("benefits_id");
            //     benefitsElement.classList.remove('d-none');
            // }
            checkWorkerClassification();
            const jobTypeSelect = document.getElementById('job_type');
            const professionSelect = document.getElementById('perferred_profession');

            jobTypeSelect.addEventListener('change', function() {
                const selectedJobType = this.value;
                let professions = [];
                if (selectedJobType === 'Clinical') {
                    professions = @json($allKeywords['ClinicalProfession']);
                } else if (selectedJobType === 'Non-Clinical') {
                    professions = @json($allKeywords['Non-ClinicalProfession']);
                } else {
                    professions = @json($allKeywords['Profession']);
                }

                professionSelect.innerHTML = '<option value="">Profession</option>';

                professions.forEach(function(profession) {
                    const option = document.createElement('option');
                    option.value = profession.title;
                    option.textContent = profession.title;
                    professionSelect.appendChild(option);
                });
            });

            const jobState = document.getElementById('job_state');
            const jobCity = document.getElementById('job_city');
            let citiesData = [];
            const selectedJobState = jobState.value;
            const selectedState = $(jobState).find(':selected').attr('id');
            // console.log('id : ', selectedState);
            // console.log('value : ', selectedJobState);
            // 
            // await $.get(`/api/cities/${selectedState}`, function(cities) {
                // console.log('cities :', cities);
                // citiesData = [];
                // citiesData = cities;
            // });
            // jobCity.innerHTML = '<option value="">Cities</option>';
            // citiesData.forEach(function(City) {
                // const option = document.createElement('option');
                // option.value = City.name;
                // option.textContent = City.name;
                // jobCity.appendChild(option);
            // });
            // document.getElementById("job_city").value = 'Globe';

            jobState.addEventListener('change', async function() {

                const selectedJobState = this.value;
                const selectedState = $(this).find(':selected').attr('id');
                console.log('id : ', selectedState);
                console.log('value : ', selectedJobState);

                await $.get(`/api/cities/${selectedState}`, function(cities) {
                    console.log('cities :', cities);
                    citiesData = cities;
                });

                jobCity.innerHTML = '<option value="">Cities</option>';
                citiesData.forEach(function(City) {
                    const option = document.createElement('option');
                    option.value = City.name;
                    option.textContent = City.name;
                    jobCity.appendChild(option);
                });

            })

            const jobTypeSelectEdit = document.getElementById('job_typeEdit');
            const professionSelectEdit = document.getElementById('perferred_professionEdit');

            jobTypeSelectEdit.addEventListener('change', function() {
                const selectedJobType = this.value;
                let professions = [];
                if (selectedJobType === 'Clinical') {
                    professions = @json($allKeywords['ClinicalProfession']);
                } else if (selectedJobType === 'Non-Clinical') {
                    professions = @json($allKeywords['Non-ClinicalProfession']);
                } else {
                    professions = @json($allKeywords['Profession']);
                }

                professionSelectEdit.innerHTML = '<option value="">Profession</option>';

                professions.forEach(function(profession) {
                    const option = document.createElement('option');
                    option.value = profession.title;
                    option.textContent = profession.title;
                    professionSelectEdit.appendChild(option);
                });
            });

           


        });


        const myForm = document.getElementById('create_job_form');
        const draftJobs = @json($draftJobs);
        const publishedJobs = @json($publishedJobs);
        const onholdJobs = @json($onholdJobs);

        if (publishedJobs == 0) {
            $("#application-details-apply").html('<div class="text-center"><span>Data Not found</span></div>');
            $("#job-list-published").html('<div class="text-center"><span>No Job</span></div>');
        }

        if (onholdJobs == 0) {
            $("#application-details-apply-onhold").html('<div class="text-center"><span>Data Not found</span></div>');
            $("#job-list-onhold").html('<div class="text-center"><span>No Job</span></div>');
        }

        // the first draft job
    function getFirstDraftJob(){
        if(draftJobs.length !== 0)
        {
            var result = draftJobs[0]; 
            console.log('result',result);

            const fields = {
                'id': { id: 'idDraft', type: 'number' },
                'job_id': { id: 'job_idDraft', type: 'number' },
                'job_name': { id: 'job_nameDraft', type: 'text' },
                'job_type': { id: 'job_typeDraft', type: 'select' },
                'preferred_specialty': { id: 'preferred_specialtyDraft', type: 'select' },
                'profession': { id: 'perferred_professionDraft', type: 'select' },
                'job_state': { id: 'job_stateDraft', type: 'select' },
                'job_city': { id: 'job_cityDraft', type: 'select' },
                'weekly_pay': { id: 'weekly_payDraft', type: 'number' },
                'terms': { id: 'termsDraft', type: 'select' },
                'preferred_assignment_duration': { id: 'preferred_assignment_durationDraft', type: 'number' },
                'facility_shift_cancelation_policy': { id: 'facility_shift_cancelation_policyDraft', type: 'text' },
                'traveler_distance_from_facility': { id: 'traveler_distance_from_facilityDraft', type: 'number' },
                'clinical_setting': { id: 'clinical_settingDraft', type: 'select' },
                'Patient_ratio': { id: 'Patient_ratioDraft', type: 'number' },
                'Unit': { id: 'UnitDraft', type: 'text' },
                'scrub_color': { id: 'scrub_colorDraft', type: 'text' },
                'rto': { id: 'rtoDraft', type: 'select' },
                'guaranteed_hours': { id: 'guaranteed_hoursDraft', type: 'number' },
                'hours_per_week': { id: 'hours_per_weekDraft', type: 'number' },
                'hours_shift': { id: 'hours_shiftDraft', type: 'number' },
                'weeks_shift': { id: 'weeks_shiftDraft', type: 'number' },
                'referral_bonus': { id: 'referral_bonusDraft', type: 'number' },
                'sign_on_bonus': { id: 'sign_on_bonusDraft', type: 'number' },
                'completion_bonus': { id: 'completion_bonusDraft', type: 'number' },
                'extension_bonus': { id: 'extension_bonusDraft', type: 'number' },
                'other_bonus': { id: 'other_bonusDraft', type: 'number' },
                'actual_hourly_rate': { id: 'actual_hourly_rateDraft', type: 'number' },
                'overtime': { id: 'overtimeDraft', type: 'number' },
                'on_call': { id: 'on_callDraft', type: 'select', options: { 'No': '0', 'Yes': '1' } },
                'on_call_rate': { id: 'on_call_rateDraft', type: 'number' },
                'holiday': { id: 'holidayDraft', type: 'date' },
                'orientation_rate': { id: 'orientation_rateDraft', type: 'number' },
                'block_scheduling': { id: 'block_schedulingDraft', type: 'select', options: { 'No': '0', 'Yes': '1' } },
                'float_requirement': { id: 'float_requirementDraft', type: 'select', options: { 'No': '0', 'Yes': '1' } },
                'number_of_references': { id: 'number_of_referencesDraft', type: 'number' },
                'eligible_work_in_us': { id: 'eligible_work_in_usDraft', type: 'select' },
                'urgency': { id: 'urgencyDraft', type: 'checkbox' },
                'facilitys_parent_system': { id: 'facilitys_parent_systemDraft', type: 'text' },
                'facility_name': { id: 'facility_nameDraft', type: 'text' },
                'pay_frequency': { id: 'pay_frequencyDraft', type: 'select' },
                'preferred_experience': { id: 'preferred_experienceDraft', type: 'number' },
                'contract_termination_policy': { id: 'contract_termination_policyDraft', type: 'text' },
                'four_zero_one_k': { id: 'four_zero_one_kDraft', type: 'select', options: { 'No': '0', 'Yes': '1' } },
                'health_insaurance': { id: 'health_insauranceDraft', type: 'select', options: { 'No': '0', 'Yes': '1' } },
                'feels_like_per_hour': { id: 'feels_like_per_hourDraft', type: 'number' },
                'call_back_rate': { id: 'call_back_rateDraft', type: 'number' },
                'weekly_non_taxable_amount': { id: 'weekly_non_taxable_amountDraft', type: 'number' },
                'start_date': { id: 'start_dateDraft', type: 'date' },
                'preferred_experience': { id: 'preferred_experienceDraft', type: 'number' },
                'professional_state_licensure': { id: 'professional_state_licensure_pendingDraft', type: 'radio' },
                'description': { id: 'descriptionDraft', type: 'text' },
                'preferred_work_location': { id: 'preferred_work_locationDraft', type: 'text' },
                'as_soon_as': { id: 'as_soon_asDraft', type: 'checkbox' },
            };

            for (const [key, field] of Object.entries(fields)) {
                const element = document.getElementById(field.id);
                if (!element || result[key] == null) continue;
            
                if (field.type === 'select') {
                    if (field.options) {
                        element.value = result[key] == '1' ? 'Yes' : 'No';
                    } else {
                        const option = document.createElement('option');
                        option.value = result[key];
                        option.text = result[key];
                        option.hidden = true;
                        element.add(option);
                        element.value = result[key];

                    }
                } else if (field.type === 'checkbox') {
                    field.id === 'urgencyDraft' ? element.checked = result[key] === 'Auto Offer' : element.checked = result[key] === '1';
                    console.log('checkbox test', result[key]);
                }
                else if (field.type === 'radio') {
                    console.log('radio', result[key]);
                    if (result[key] === 'Accept Pending') {
                        document.getElementById('professional_state_licensure_pendingDraft').checked = true;
                    } else {
                        document.getElementById('professional_state_licensure_activeDraft').checked = true;
                    }
                }
                 else {
                    element.value = result[key];
                }
            }
            // list emr 
            var emr = result['Emr'];
            if(emr !== null){
            emr = emr.split(', ');
            
            emr.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['EMR']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        Emr[itemData.id] = item;
                    }
                });
            });
            }
            list_Emr();

            // list benefits

            var benefitsResult = result['benefits'];
            if(benefitsResult) {
            benefitsResult = benefitsResult.split(', ');
            
                benefitsResult.forEach(function(item) {
                    @php
                        $allKeywordsJSON = json_encode($allKeywords['Benefits']);
                    @endphp
                    let allspcldata = '{!! $allKeywordsJSON !!}';
                    var data = JSON.parse(allspcldata);
                    data.forEach(function(itemData) {
                        if (item == itemData.title) {
                            benefits[itemData.id] = item;
                        }
                    });
                });
            }
            list_benefits();

            // list nurse classification
            var nurse_classificationResult = result['nurse_classification'];
            if(nurse_classificationResult !== null){
            nurse_classificationResult = nurse_classificationResult.split(', ');
            
                nurse_classificationResult.forEach(function(item) {
                    @php
                        $allKeywordsJSON = json_encode($allKeywords['NurseClassification']);
                    @endphp
                    let allspcldata = '{!! $allKeywordsJSON !!}';
                    var data = JSON.parse(allspcldata);
                    data.forEach(function(itemData) {
                        if (item == itemData.title) {
                            nurse_classification[itemData.id] = item;
                        }
                    });
                });
            }

            list_nurse_classification();

            // list professional licensure

            var professional_licensureResult = result['job_location'];
            if(professional_licensureResult !== null){
            
            professional_licensureResult = professional_licensureResult.split(', ');
            

            professional_licensureResult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['StateCode']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        professional_licensure[itemData.id] = item;
                    }
                });
            });
            }
            list_professional_licensure();

            // list skills

            var skillsResult = result['skills'];
            if(skillsResult !== null){
            skillsResult = skillsResult.split(', ');
            
            skillsResult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['Speciality']);
                @endphp
                let allspcldata = {!! json_encode($allKeywordsJSON) !!};
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        skills[itemData.id] = item;
                    }
                });
            });
            }
            list_skills();

            // list shift time of day

            var shifttimeofdayresult = result['preferred_shift_duration'];
            console.log("sift time of day : ",shifttimeofdayresult);
            // shifttimeofday is a string use trim to check if it is empty
            if (shifttimeofdayresult !== null) {
                console.log('triiiiiiimed');
            shifttimeofdayresult = shifttimeofdayresult.split(', ');
            
            shifttimeofdayresult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['PreferredShift']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        shifttimeofday[itemData.id] = item;
                    }
                });
            });
            }
            list_shifttimeofday();

            // list certifications

            var certificationsResult = result['certificate'];
            if (certificationsResult !== null) {
            certificationsResult = certificationsResult.split(', ');
            
            certificationsResult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['Certification']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        certificate[itemData.id] = item;
                    }
                });
            });
            }

            list_certifications();

            // list vaccinations

            var vaccinationsResult = result['vaccinations'];
            if (vaccinationsResult !== null) {
            vaccinationsResult = vaccinationsResult.split(', ');
            vaccinationsResult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['Vaccinations']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        vaccinations[itemData.id] = item;
                    }
                });
            });
            }

            list_vaccinations();

        } else {
            
            $("#job-list-draft").html('<div class="text-center"><span>No Job</span></div>');
            

            // add d-none class to hide the form
            document.getElementById('details_info').classList.add('d-none');
            // remove d-none class to show the message
            document.getElementById('no-job-for-draft').classList.remove('d-none');

            

        }
    }


        var certificateStr = '';
        var vaccinationsStr = '';
        var skillsStr = '';
        var shifttimeofdayStr = '';
        var benefitsStr = '';
        var professional_licensureStr = '';
        var nurse_classificationStr = '';
        var EmrStr = '';

        // from edited job
        var certificateStrEdit = '';
        var vaccinationsStrEdit = '';
        var skillsStrEdit = '';
        var shifttimeofdayStrEdit = '';
        var benefitsStrEdit = '';
        var professional_licensureStrEdit = '';
        var nurse_classificationStrEdit = '';
        var EmrStrEdit = '';


        // $(document).ready(function() {
        //     $('#job_state').change(function() {
        //         console.log("selected");
        //         const selectedState = $(this).find(':selected').attr('id');
        //         const CitySelect = $('#job_city');
        //         $.get(`/api/cities/${selectedState}`, function(data) {
        //             CitySelect.empty();
        //             CitySelect.append('<option value="">Select City</option>');
        //             $.each(data, function(index, city) {
        //                 CitySelect.append(new Option(city.name, city.name));
        //             });
        //         });
        //     });
        // });


        $(document).ready(function() {
            $('#job_stateDraft').change(function() {
                const selectedState = $(this).find(':selected').attr('id');
                const CitySelect = $('#job_cityDraft');

                $.get(`/api/cities/${selectedState}`, function(data) {
                    CitySelect.empty();
                    CitySelect.append('<option value="">Select City</option>');
                    $.each(data, function(index, city) {
                        CitySelect.append(new Option(city.name, city.name));
                    });
                });
            });

            $('#job_stateEdit').change(function() {
                const selectedState = $(this).find(':selected').attr('id');
                const CitySelect = $('#job_cityEdit');

                $.get(`/api/cities/${selectedState}`, function(data) {
                    CitySelect.empty();
                    CitySelect.append('<option value="">Select City</option>');
                    $.each(data, function(index, city) {
                        CitySelect.append(new Option(city.name, city.name));
                    });
                });
            });
        });

        function request_job_form_appear() {
           
            certificate = {};
            vaccinations = {};
            skills = {};
            shifttimeofday = {};
            benefits = {};
            professional_licensure = {};
            nurse_classification = {};
            Emr = {};

            document.getElementById('no-job-posted').classList.add('d-none');
            document.getElementById('published-job-details').classList.add('d-none');
            document.getElementById('create_job_request_form').classList.remove('d-none');

        }

        function opportunitiesType(type, id = "", formtype) {

            certificate = {};
            vaccinations = {};
            skills = {};
            shifttimeofday = {};
            benefits = {};
            professional_licensure = {};
            nurse_classification = {};
            Emr = {};

            console.log(type);

            var draftsElement = document.getElementById('drafts');
            var publishedElement = document.getElementById('published');
            var onholdElement = document.getElementById('onhold');


            if (type == "drafts") {
                getFirstDraftJob();
                document.getElementById("onholdCards").classList.add('d-none');
                document.getElementById("draftCards").classList.remove('d-none');
                document.getElementById("publishedCards").classList.add('d-none');
                document.getElementById("details_edit_job").classList.add("d-none");

                document.getElementById("no-job-posted").classList.add("d-none");
                document.getElementById("details_draft").classList.remove("d-none");
                document.getElementById("details_published").classList.add("d-none");
                document.getElementById("details_onhold").classList.add("d-none");
                document.getElementById('published-job-details').classList.remove('d-none');
                document.getElementById("create_job_request_form").classList.add("d-none");



            } else if (type == "published") {
                document.getElementById("onholdCards").classList.add('d-none');
                document.getElementById("draftCards").classList.add('d-none');
                document.getElementById("publishedCards").classList.remove('d-none');
                document.getElementById("details_edit_job").classList.add("d-none");

                document.getElementById("no-job-posted").classList.add("d-none");
                document.getElementById("details_published").classList.remove("d-none");
                document.getElementById("details_onhold").classList.add("d-none");
                document.getElementById("details_draft").classList.add("d-none");

                document.getElementById('published-job-details').classList.remove('d-none');
                document.getElementById("create_job_request_form").classList.add("d-none");
            } else if (type == "onhold") {
                document.getElementById("draftCards").classList.add('d-none');
                document.getElementById("publishedCards").classList.add('d-none');
                document.getElementById("onholdCards").classList.remove('d-none');
                document.getElementById("details_edit_job").classList.add("d-none");


                document.getElementById("no-job-posted").classList.add("d-none");
                document.getElementById("details_onhold").classList.remove("d-none");
                document.getElementById("details_published").classList.add("d-none");
                document.getElementById("details_draft").classList.add("d-none");

                document.getElementById('published-job-details').classList.remove('d-none');
                document.getElementById("create_job_request_form").classList.add("d-none");
            }


            if (draftsElement.classList.contains("active")) {
                draftsElement.classList.remove("active");
                document.getElementById("create_job_request_form").classList.add("d-none");

            }
            if (publishedElement.classList.contains("active")) {
                publishedElement.classList.remove("active");
                document.getElementById("create_job_request_form").classList.add("d-none");

            }
            if (onholdElement.classList.contains("active")) {
                onholdElement.classList.remove("active");
                document.getElementById("create_job_request_form").classList.add("d-none");

            }

            document.getElementById(type).classList.add("active")

            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
            let activestatus = 0;
            // document.getElementById('opportunitylistname').innerHTML = type;
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    url: "{{ url('recruiter/get-job-listing') }}",
                    beforeSend: function(xhr) {
                        xhr.withCredentials = true;
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'type': type,
                        'id': id,
                        'formtype': formtype,
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(result) {
                        console.log(result);

                        // $("#job-list").html(result.joblisting);
                        // $("#job-details").html(result.jobdetails);

                        window.allspecialty = result.allspecialty;
                        window.allvaccinations = result.allvaccinations;
                        window.allcertificate = result.allcertificate;
                        window.allbenefits = result.allcertificate;
                        window.allprofessional_licensure = result.allcertificate;
                        window.allEmr = result.allcertificate;
                        window.allnurse_classification = result.allcertificate;



                        list_specialities();
                        list_vaccinations();
                        list_certifications();
                        list_benefits();
                        list_professional_licensure();
                        list_Emr();
                        list_nurse_classification();

                        if (result.joblisting != "") {
                            if (type == "published") {
                                document.getElementById("published-job-details").classList.remove("d-none");
                                document.getElementById("no-job-posted").classList.add("d-none");
                                $("#application-details-apply").html(result.jobdetails);
                                $("#job-list-published").html(result.joblisting);
                            } else if (type == "onhold") {
                                document.getElementById("published-job-details").classList.remove("d-none");
                                document.getElementById("no-job-posted").classList.add("d-none");
                                $("#application-details-apply-onhold").html(result.jobdetails);
                                $("#job-list-onhold").html(result.joblisting);
                            }

                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else {
                console.error('CSRF token not found.');
            }
            // editJob();
        }
        $(document).ready(function() {
            document.getElementById("details_draft").classList.add("d-none");
            document.getElementById('published-job-details').classList.add('d-none');
            document.getElementById('details_edit_job').classList.add('d-none');
        });

        function editOpportunity(id = "", formtype) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    url: "{{ url('recruiter/get-job-listing') }}",
                    beforeSend: function(xhr) {
                        xhr.withCredentials = true;
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'id': id,
                        'formtype': formtype
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(result) {

                        // $("#application-list").html(result.applicationlisting);
                        // $("#application-details").html(result.applicationdetails);
                    },
                    error: function(error) {
                        // Handle errors
                    }
                });
            } else {
                console.error('CSRF token not found.');
            }
        }

        function editJob(inputField) {
            var value = inputField.value;
            var name = inputField.name;

            if (value != "") {
                if (name = "benefits", name == "vaccinations" || name == "preferred_specialty" || name ==
                    "preferred_experience" || name ==
                    "certificate") {
                    var inputFields = document.querySelectorAll(name == "vaccinations" ? 'select[name="vaccinations"]' :
                        name == "preferred_specialty" ? 'select[name="preferred_specialty"]' :
                        name == "preferred_experience" ? 'input[name="preferred_experience"]' :
                        name == 'certificate' ? 'select[name="certificate"]' :
                        name == 'benefits' ? 'select[name="benefits"]' :
                        name == 'professional_licensure' ? 'select[name="job_location"]' :
                        name == 'nurse_classification' ? 'select[name="nurse_classification"]' :
                        'select[name="Emr"]'
                    );
                    var data = [];
                    inputFields.forEach(function(input) {
                        data.push(input.value);
                    });

                    value = data.join(', ');
                }

                var formData = {};
                formData[inputField.name] = value;
                formData['job_id'] = document.getElementById('job_id').value;
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    type: 'POST',
                    url: "{{ url('recruiter/recruiter-create-opportunity') }}/update",
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        // notie.alert({
                        //   type: 'success',
                        //   text: '<i class="fa fa-check"></i> ' + data.message,
                        //   time: 5
                        // });
                        if (document.getElementById('job_id').value.trim() == '' || document.getElementById(
                                'job_id').value.trim() == 'null' || document.getElementById('job_id').value
                            .trim() == null) {
                            document.getElementById("job_id").value = data.job_id;
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        }

        function addmoreexperience() {
            var allExperienceDiv = document.getElementById('all-experience');
            var newExperienceDiv = document.querySelector('.experience-inputs').cloneNode(true);
            newExperienceDiv.querySelector('select.specialty').selectedIndex = 0;
            newExperienceDiv.querySelector('input[type="number"]').value = '';
            allExperienceDiv.appendChild(newExperienceDiv);
        }

        function changeStatus(type, id = '0') {
            if (type == "draft") {
                notie.alert({
                    type: 'success',
                    text: '<i class="fa fa-check"></i> Draft Updated Successfully',
                    time: 5
                });
            } else {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                if (csrfToken) {
                    event.preventDefault();
                    let check_type = type;

                    // if (document.getElementById('job_id').value) {
                    if (id == '0') {
                        id = document.getElementById('job_id').value;
                    }
                    let formData = {
                        'job_id': id
                    }
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },

                        type: 'POST',
                        url: "{{ url('recruiter/recruiter-create-opportunity') }}/" + check_type,

                        data: formData,
                        dataType: 'json',
                        success: function(data) {
                            notie.alert({
                                type: 'success',
                                text: '<i class="fa fa-check"></i> ' + data.message,
                                time: 3
                            });

                            window.location.reload();

                            // if (type == "hidejob") {
                            //     console.log("this is data");
                            //     //console.log(data.message);
                            //     opportunitiesType('onhold',data.job_id)
                            // } else {
                            //     opportunitiesType('published',data.job_id)
                            // }
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                    // }
                } else {
                    console.error('CSRF token not found.');
                }
            }
        }




        function offerSend(id, jobid, type, workerid, recruiter_id) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                let counterstatus = "1";
                if (type == "rejectcounter") {
                    counterstatus = "0";
                }
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    url: "{{ url('recruiter/recruiter-send-job-offer') }}",
                    data: {
                        'token': csrfToken,
                        'id': id,
                        'job_id': jobid,
                        'counterstatus': counterstatus,
                        'worker_user_id': workerid,
                        'recruiter_id': recruiter_id,
                        'is_draft': "1",
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(result) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> ' + result.message,
                            time: 5
                        });
                    },
                    error: function(error) {
                        // Handle errors
                    }
                });
            } else {
                console.error('CSRF token not found.');
            }
        }
        setInterval(function() {
            $(document).ready(function() {
                $('.application-job-slider-owl').owlCarousel({
                    items: 3,
                    loop: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    margin: 20,
                    nav: false,
                    dots: false,
                    navText: ['<span class="fa fa-angle-left  fa-2x"></span>',
                        '<span class="fas fa fa-angle-right fa-2x"></span>'
                    ],
                    responsive: {
                        0: {
                            items: 1
                        },
                        480: {
                            items: 2
                        },
                        768: {
                            items: 3
                        },
                        992: {
                            items: 2
                        }
                    }
                })
            })
        }, 3000)

        function updateJob() {
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Work Updated Successfully.',
                time: 3
            });
        }
    </script>
    <script>
        var speciality = {};

        // console.log(window.allspecialty)
        // console.log(window.allvaccinations)
        // console.log(window.allcertificate)

        function add_speciality(obj) {
            if (!$('#preferred_specialty').val()) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Select the speciality please.',
                    time: 3
                });
            } else if (!$('#preferred_experience').val()) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Enter total year of experience.',
                    time: 3
                });
            } else {
                if (!speciality.hasOwnProperty($('#preferred_specialty').val())) {
                    speciality[$('#preferred_specialty').val()] = $('#preferred_experience').val();
                    $('#preferred_experience').val('');
                    $('#preferred_specialty').val('');
                    list_specialities();
                }
            }
        }

        function remove_speciality(obj, key) {
            if (speciality.hasOwnProperty($(obj).data('key'))) {
                var element = document.getElementById("remove-speciality");
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                if (csrfToken) {
                    event.preventDefault();
                    if (document.getElementById('job_id').value) {
                        let formData = {
                            'job_id': document.getElementById('job_id').value,
                            'specialty': key,
                        }
                        let removetype = 'specialty';
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            type: 'POST',
                            url: "{{ url('recruiter/remove') }}/" + removetype,
                            data: formData,
                            dataType: 'json',
                            success: function(data) {

                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    }
                    delete speciality[$(obj).data('key')];
                    delete window.allspecialty[$(obj).data('key')];
                } else {
                    console.error('CSRF token not found.');
                }
                list_specialities();
            }
        }

        function list_specialities() {
            var str = '';
            if (window.allspecialty) {
                speciality = Object.assign({}, speciality, window.allspecialty);
            }
            for (const key in speciality) {
                let specialityname = "";

                var select = document.getElementById("preferred_specialty");
                var allspcldata = [];
                for (var i = 0; i < select.options.length; i++) {
                    var obj = {
                        'id': select.options[i].value,
                        'title': select.options[i].textContent,
                    };
                    allspcldata.push(obj);
                }

                if (speciality.hasOwnProperty(key)) {
                    allspcldata.forEach(function(item) {
                        if (key == item.id) {
                            specialityname = item.title;
                        }
                    });
                    const value = speciality[key];
                    str += '<ul>';
                    str += '<li>' + specialityname + '</li>';
                    str += '<li>' + value + ' Years</li>';
                    str += '<li><button type="button"  id="remove-speciality" data-key="' + key +
                        '" onclick="remove_speciality(this, ' + key +
                        ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                    str += '</ul>';
                }
            }
            $('.speciality-content').html(str);
        }
    </script>
    

    <script>
        function askWorker(e, type, workerid, jobid) {
            let url = "{{ url('recruiter/recruiter-messages') }}";
            window.location = url + '?worker_id=' + workerid + '&job_id=' + jobid;
        }
        // const numberOfReferencesField = document.getElementById('number_of_references');
        // numberOfReferencesField.addEventListener('input', function () {
        //     if (numberOfReferencesField.value.length > 9) {
        //         numberOfReferencesField.value = numberOfReferencesField.value.substring(0, 9);
        //     }
        // });
        $(document).ready(function() {
            let formData = {
                'country_id': '233',
                'api_key': '123',
            }
            $.ajax({
                type: 'POST',
                url: "{{ url('api/get-states') }}",
                data: formData,
                dataType: 'json',
                success: function(data) {
                    var stateSelect = $('#facility-state-code');
                    stateSelect.empty();
                    stateSelect.append($('<option>', {
                        value: "",
                        text: "Select Facility State Code"
                    }));
                    $.each(data.data, function(index, state) {
                        stateSelect.append($('<option>', {
                            value: state.state_id,
                            text: state.name
                        }));
                    });
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        function searchCity(e) {
            var selectedValue = e.value;
            console.log("Selected Value: " + selectedValue);
            let formData = {
                'state_id': selectedValue,
                'api_key': '123',
            }
            $.ajax({
                type: 'POST',
                url: "{{ url('api/get-cities') }}",
                data: formData,
                dataType: 'json',
                success: function(data) {
                    var stateSelect = $('#facility-city');
                    stateSelect.empty();
                    stateSelect.append($('<option>', {
                        value: "",
                        text: "Select Facility City"
                    }));
                    $.each(data.data, function(index, state) {
                        stateSelect.append($('<option>', {
                            value: state.state_id,
                            text: state.name
                        }));
                    });
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function getSpecialitiesByProfession(e) {
            var selectedValue = e.value;
            let formData = {
                'profession_id': selectedValue,
                'api_key': '123',
            }
            $.ajax({
                type: 'POST',
                url: "{{ url('api/get-profession-specialities') }}",
                data: formData,
                dataType: 'json',
                success: function(data) {
                    var stateSelect = $('#preferred_specialty');
                    stateSelect.empty();
                    stateSelect.append($('<option>', {
                        value: "",
                        text: "Select Specialty"
                    }));
                    $.each(data.data, function(index, state) {
                        stateSelect.append($('<option>', {
                            value: state.state_id,
                            text: state.name
                        }));
                    });
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    </script>
@section('js')
    <script>
        function open_modal(obj) {
            let name, title, modal, form, target;

            name = $(obj).data('name');
            title = $(obj).data('title');
            target = $(obj).data('target');

            modal = '#' + target + '_modal';
            form = modal + '_form';
            $(form).find('h4').html(title);
            switch (target) {
                case 'file':
                    $(form).find('input[type="file"]').attr('name', name);
                    $(form).find('input[type="hidden"]').attr('name', $(obj).data('hidden_name'));
                    $(form).find('input[type="hidden"]').val($(obj).data('hidden_value'));
                    $(form).attr('action', $(obj).data('href'));
                    break;
                case 'input':
                    $(form).find('input[type="text"]').attr('name', name);
                    $(form).find('input[type="text"]').attr('placeholder', $(obj).data('placeholder'));
                    break;
                case 'binary':
                    $(form).find('input[type="radio"]').attr('name', name);
                    break;
                case 'dropdown':
                    $(form).find('select').attr('name', name);
                    get_dropdown(obj);
                    break;
                default:
                    break;
            }

            $(modal).modal('show');
        }

        function close_modal(obj) {
            let target = $(obj).data('target');
            $(target).modal('hide');
        }
    </script>
@stop
<script type="text/javascript">
    function editDataJob(element) {
        
        certificate = {};
        vaccinations = {};
        skills = {};
        shifttimeofday = {};
        benefits = {};
        professional_licensure = {};
        nurse_classification = {};
        Emr = {};
    
        const jobId = element.getAttribute('job_id');
        console.log(draftJobs[jobId].job_name)
        element.classList.add("active");

        var result = draftJobs[jobId]; 
            console.log('result',result);

            const fields = {
                'id': { id: 'idDraft', type: 'number' },
                'job_id': { id: 'job_idDraft', type: 'number' },
                'job_name': { id: 'job_nameDraft', type: 'text' },
                'job_type': { id: 'job_typeDraft', type: 'select' },
                'preferred_specialty': { id: 'preferred_specialtyDraft', type: 'select' },
                'profession': { id: 'perferred_professionDraft', type: 'select' },
                'job_state': { id: 'job_stateDraft', type: 'select' },
                'job_city': { id: 'job_cityDraft', type: 'select' },
                'weekly_pay': { id: 'weekly_payDraft', type: 'number' },
                'terms': { id: 'termsDraft', type: 'select' },
                'preferred_assignment_duration': { id: 'preferred_assignment_durationDraft', type: 'number' },
                'facility_shift_cancelation_policy': { id: 'facility_shift_cancelation_policyDraft', type: 'text' },
                'traveler_distance_from_facility': { id: 'traveler_distance_from_facilityDraft', type: 'number' },
                'clinical_setting': { id: 'clinical_settingDraft', type: 'select' },
                'Patient_ratio': { id: 'Patient_ratioDraft', type: 'number' },
                'Unit': { id: 'UnitDraft', type: 'text' },
                'scrub_color': { id: 'scrub_colorDraft', type: 'text' },
                'rto': { id: 'rtoDraft', type: 'select' },
                'guaranteed_hours': { id: 'guaranteed_hoursDraft', type: 'number' },
                'hours_per_week': { id: 'hours_per_weekDraft', type: 'number' },
                'hours_shift': { id: 'hours_shiftDraft', type: 'number' },
                'weeks_shift': { id: 'weeks_shiftDraft', type: 'number' },
                'referral_bonus': { id: 'referral_bonusDraft', type: 'number' },
                'sign_on_bonus': { id: 'sign_on_bonusDraft', type: 'number' },
                'completion_bonus': { id: 'completion_bonusDraft', type: 'number' },
                'extension_bonus': { id: 'extension_bonusDraft', type: 'number' },
                'other_bonus': { id: 'other_bonusDraft', type: 'number' },
                'actual_hourly_rate': { id: 'actual_hourly_rateDraft', type: 'number' },
                'overtime': { id: 'overtimeDraft', type: 'number' },
                'on_call': { id: 'on_callDraft', type: 'select', options: { 'No': '0', 'Yes': '1' } },
                'on_call_rate': { id: 'on_call_rateDraft', type: 'number' },
                'holiday': { id: 'holidayDraft', type: 'date' },
                'orientation_rate': { id: 'orientation_rateDraft', type: 'number' },
                'block_scheduling': { id: 'block_schedulingDraft', type: 'select', options: { 'No': '0', 'Yes': '1' } },
                'float_requirement': { id: 'float_requirementDraft', type: 'select', options: { 'No': '0', 'Yes': '1' } },
                'number_of_references': { id: 'number_of_referencesDraft', type: 'number' },
                'eligible_work_in_us': { id: 'eligible_work_in_usDraft', type: 'select' },
                'urgency': { id: 'urgencyDraft', type: 'checkbox' },
                'facilitys_parent_system': { id: 'facilitys_parent_systemDraft', type: 'text' },
                'facility_name': { id: 'facility_nameDraft', type: 'text' },
                'pay_frequency': { id: 'pay_frequencyDraft', type: 'select' },
                'preferred_experience': { id: 'preferred_experienceDraft', type: 'number' },
                'contract_termination_policy': { id: 'contract_termination_policyDraft', type: 'text' },
                'four_zero_one_k': { id: 'four_zero_one_kDraft', type: 'select', options: { 'No': '0', 'Yes': '1' } },
                'health_insaurance': { id: 'health_insauranceDraft', type: 'select', options: { 'No': '0', 'Yes': '1' } },
                'feels_like_per_hour': { id: 'feels_like_per_hourDraft', type: 'number' },
                'call_back_rate': { id: 'call_back_rateDraft', type: 'number' },
                'weekly_non_taxable_amount': { id: 'weekly_non_taxable_amountDraft', type: 'number' },
                'start_date': { id: 'start_dateDraft', type: 'date' },
                'preferred_experience': { id: 'preferred_experienceDraft', type: 'number' },
                'professional_state_licensure': { id: 'professional_state_licensure_pendingDraft', type: 'radio' },
                'description': { id: 'descriptionDraft', type: 'text' },
                'preferred_work_location': { id: 'preferred_work_locationDraft', type: 'text' },
                'as_soon_as': { id: 'as_soon_asDraft', type: 'checkbox' },
            };

            for (const [key, field] of Object.entries(fields)) {
                const element = document.getElementById(field.id);
                if (!element || result[key] == null) continue;
            
                if (field.type === 'select') {
                    if (field.options) {
                        element.value = result[key] == '1' ? 'Yes' : 'No';
                    } else {
                        const option = document.createElement('option');
                        option.value = result[key];
                        option.text = result[key];
                        option.hidden = true;
                        element.add(option);
                        element.value = result[key];

                    }
                } else if (field.type === 'checkbox') {
                    field.id === 'urgencyDraft' ? element.checked = result[key] === 'Auto Offer' : element.checked = result[key] === '1';
                    console.log('checkbox test', result[key]);
                }
                else if (field.type === 'radio') {
                    console.log('radio', result[key]);
                    if (result[key] === 'Accept Pending') {
                        document.getElementById('professional_state_licensure_pendingDraft').checked = true;
                    } else {
                        document.getElementById('professional_state_licensure_activeDraft').checked = true;
                    }
                }
                 else {
                    element.value = result[key];
                }
            }
            // list emr 
            var emr = result['Emr'];
            if(emr !== null){
            emr = emr.split(', ');
            
            emr.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['EMR']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        Emr[itemData.id] = item;
                    }
                });
            });
            }
            list_Emr();

            // list benefits

            var benefitsResult = result['benefits'];
            if(benefitsResult) {
            benefitsResult = benefitsResult.split(', ');
            
                benefitsResult.forEach(function(item) {
                    @php
                        $allKeywordsJSON = json_encode($allKeywords['Benefits']);
                    @endphp
                    let allspcldata = '{!! $allKeywordsJSON !!}';
                    var data = JSON.parse(allspcldata);
                    data.forEach(function(itemData) {
                        if (item == itemData.title) {
                            benefits[itemData.id] = item;
                        }
                    });
                });
            }
            list_benefits();

            // list nurse classification
            var nurse_classificationResult = result['nurse_classification'];
            if(nurse_classificationResult !== null){
            nurse_classificationResult = nurse_classificationResult.split(', ');
            
                nurse_classificationResult.forEach(function(item) {
                    @php
                        $allKeywordsJSON = json_encode($allKeywords['NurseClassification']);
                    @endphp
                    let allspcldata = '{!! $allKeywordsJSON !!}';
                    var data = JSON.parse(allspcldata);
                    data.forEach(function(itemData) {
                        if (item == itemData.title) {
                            nurse_classification[itemData.id] = item;
                        }
                    });
                });
            }

            list_nurse_classification();

            // list professional licensure

            var professional_licensureResult = result['job_location'];
            if(professional_licensureResult !== null){
            
            professional_licensureResult = professional_licensureResult.split(', ');
            

            professional_licensureResult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['StateCode']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        professional_licensure[itemData.id] = item;
                    }
                });
            });
            }
            list_professional_licensure();

            // list skills

            var skillsResult = result['skills'];
            if(skillsResult !== null){
            skillsResult = skillsResult.split(', ');
            
            skillsResult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['Speciality']);
                @endphp
                let allspcldata = {!! json_encode($allKeywordsJSON) !!};
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        skills[itemData.id] = item;
                    }
                });
            });
            }
            list_skills();

            // list shift time of day

            var shifttimeofdayresult = result['preferred_shift_duration'];
            console.log("sift time of day : ",shifttimeofdayresult);
            // shifttimeofday is a string use trim to check if it is empty
            if (shifttimeofdayresult !== null) {
                console.log('triiiiiiimed');
            shifttimeofdayresult = shifttimeofdayresult.split(', ');
            
            shifttimeofdayresult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['PreferredShift']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        shifttimeofday[itemData.id] = item;
                    }
                });
            });
            }
            list_shifttimeofday();

            // list certifications

            var certificationsResult = result['certificate'];
            if (certificationsResult !== null) {
            certificationsResult = certificationsResult.split(', ');
            
            certificationsResult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['Certification']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        certificate[itemData.id] = item;
                    }
                });
            });
            }

            list_certifications();

            // list vaccinations

            var vaccinationsResult = result['vaccinations'];
            if (vaccinationsResult !== null) {
            vaccinationsResult = vaccinationsResult.split(', ');
            vaccinationsResult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['Vaccinations']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        vaccinations[itemData.id] = item;
                    }
                });
            });
            }

            list_vaccinations();

        
    }



    const slidePage = document.querySelector(".slide-page");
    const nextBtnFirst = document.querySelector(".firstNext");
    const prevBtnSec = document.querySelector(".prev-1");
    const nextBtnSec = document.querySelector(".next-1");
    const prevBtnThird = document.querySelector(".prev-2");
    const nextBtnThird = document.querySelector(".next-2");

    const prevBtnFourth = document.querySelector(".prev-3");
   
    const submitBtn = document.querySelector(".submit");
    const saveDrftBtn = document.querySelectorAll(".saveDrftBtn");

    const progressText = document.querySelectorAll(".step p");
    const progressCheck = document.querySelectorAll(".step .check");
    const bullet = document.querySelectorAll(".step .bullet");
    let current = 1;

    // Validation the add job
    // first Slide
    function validateFirst() {
    
        var access = true;

        var jobType = document.getElementById("job_type").value;
        var specialty = document.getElementById("preferred_specialty").value;
        var profession = document.getElementById("perferred_profession").value;
        var city = document.getElementById("job_city").value;
        var state = document.getElementById("job_state").value;
        var weeklyPay = document.getElementById("weekly_pay").value;
        var terms = document.getElementById("terms").value;
        var preferred_experience = document.getElementById("preferred_experience").value;
        var eligible_work_in_us = document.getElementById("eligible_work_in_us").value;
        var hours_per_week = document.getElementById("hours_per_week").value;
        var guaranteed_hours = document.getElementById("guaranteed_hours").value;
        var hours_shift = document.getElementById("hours_shift").value;
        var weeks_shift = document.getElementById("weeks_shift").value;
        var pay_frequency = document.getElementById("pay_frequency").value;
        var actual_hourly_rate = document.getElementById("actual_hourly_rate").value;
        var overtime = document.getElementById("overtime").value;
        var weekly_non_taxable_amount = document.getElementById("weekly_non_taxable_amount").value;

        // weekly non taxable amount

        if (weekly_non_taxable_amount.trim() === '') {
            $('.help-block-weekly_non_taxable_amount').text('Please enter the Weekly non taxable amount');
            $('.help-block-weekly_non_taxable_amount').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-weekly_non_taxable_amount').text('');
        }

        // overtime
        if (overtime.trim() === '') {
            $('.help-block-overtime').text('Please enter the Overtime');
            $('.help-block-overtime').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-overtime').text('');
        }

        // actual hourly rate
        if (actual_hourly_rate.trim() === '') {
            $('.help-block-actual_hourly_rate').text('Please enter the Actual hourly rate');
            $('.help-block-actual_hourly_rate').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-actual_hourly_rate').text('');
        }

        // pay frequency
        if (pay_frequency.trim() === '') {
            $('.help-block-pay_frequency').text('Please enter the Pay frequency');
            $('.help-block-pay_frequency').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-pay_frequency').text('');
        }
        // hours per week
        if (hours_per_week.trim() === '') {
            $('.help-block-hours_per_week').text('Please enter the Hours per week');
            $('.help-block-hours_per_week').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-hours_per_week').text('');
        }

        if (guaranteed_hours.trim() === '') {
            $('.help-block-guaranteed_hours').text('Please enter the guaranteed hours');
            $('.help-block-guaranteed_hours').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-guaranteed_hours').text('');
        }


        if (hours_shift.trim() === '') {
            $('.help-block-hours_shift').text('Please enter the hours shift');
            $('.help-block-hours_shift').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-hours_shift').text('');
        }

        if (weeks_shift.trim() === '') {
            $('.help-block-weeks_shift').text('Please enter the weeks shift');
            $('.help-block-weeks_shift').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-weeks_shift').text('');
        }

        // eligible work in us
        if (eligible_work_in_us.trim() === '') {
            $('.help-block-eligible_work_in_us').text('Please enter the Eligible work in us');
            $('.help-block-eligible_work_in_us').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-eligible_work_in_us').text('');
        }

        // preferred experience

        if (preferred_experience.trim() === '') {
            $('.help-block-preferred_experience').text('Please enter the Preferred experience');
            $('.help-block-preferred_experience').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-preferred_experience').text('');
        }



        // shift time of day
        if (Object.keys(shifttimeofday).length === 0) {
            $('.help-block-shift_time_of_day').text('Please enter the Shift time of day');
            $('.help-block-shift_time_of_day').addClass('text-danger');
            access = false;
        }else{
            $('.help-block-shift_time_of_day').text('');
        }
        // professional licensure
        if (Object.keys(professional_licensure).length === 0) {
            $('.help-block-professional_licensure').text('Please enter the Professional licensure');
            $('.help-block-professional_licensure').addClass('text-danger');
            access = false; 
        }else{
            $('.help-block-professional_licensure').text('');
        }

        if (jobType.trim() === "") {
            $('.help-block-job_type').text('Please enter the Work type');
            $('.help-block-job_type').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_type').text('');

        }

        if (specialty.trim() === '') {
            $('.help-block-preferred_specialty').text('Please enter the Work speciality');
            $('.help-block-preferred_specialty').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-preferred_specialty').text('');

        }

        if (profession.trim() === '') {
            $('.help-block-perferred_profession').text('Please enter the Work profession');
            $('.help-block-perferred_profession').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-perferred_profession').text('');

        }
        if (terms.trim() === '') {
            $('.help-block-terms').text('Please enter the terms');
            $('.help-block-terms').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-terms').text('');
        }

        if (city.trim() === '') {
            $('.help-block-job_city').text('Please enter the Work city');
            $('.help-block-job_city').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_city').text('');

        }

        if (state.trim() === '') {
            $('.help-block-job_state').text('Please enter the Work state');
            $('.help-block-job_state').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_state').text('');
        }

        if (weeklyPay.trim() === '') {
            $('.help-block-weekly_pay').text('Please enter the Work weekly pay');
            $('.help-block-weekly_pay').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-weekly_pay').text('');

        }


        if (access) {
            return true;
        } else {
            return false;
        }
    }


    function validateRequiredFieldsToSubmit(slideFields){

        let access = true;
        const commonElements = slideFields.filter(element => requiredToSubmit.includes(element));

                    if(commonElements.length > 0) {

                        commonElements.forEach(element => {

                            if(element === 'professional_state_licensure'){
                                const activeElementHtml = document.getElementById('professional_state_licensure_active');
                                const pendingElementHtml = document.getElementById('professional_state_licensure_pending');

                                if(!activeElementHtml.checked && !pendingElementHtml.checked) {
                                    $(`.help-block-professional_state_licensure`).text(`This field is required`);
                                    $(`.help-block-professional_state_licensure`).addClass('text-danger');
                                    access = false;
                                } else {
                                    $(`.help-block-professional_state_licensure`).text('');
 
                                }

                                return;
                            }

                            if (element === 'urgency') {

                                const urgencyElementHtml = document.getElementById('urgency');
                                if(!urgencyElementHtml.checked) {
                                    $(`.help-block-urgency`).text(`This field is required`);
                                    $(`.help-block-urgency`).addClass('text-danger');
                                    access = false;
                                } else {
                                    $(`.help-block-urgency`).text('');
                                }

                                return;
                            }

                            const elementHtml = document.getElementById(element);
                            const elementValue = elementHtml.value;
                            if(elementValue.trim() === '') {

                                $(`.help-block-${element}`).text(`This field is required`);
                                $(`.help-block-${element}`).addClass('text-danger');
                                access = false;
                               
                            } else {

                                $(`.help-block-${element}`).text('');

                            }

                        });
                    }

        return access;
    }

    function validateRequiredMultiCheckFieldsToSubmit(slideFields) {
        let access = true;
        const commonElements = slideFields.filter(element => requiredToSubmit.includes(element));

        if(commonElements.length > 0) {

            commonElements.forEach(element => {

                const elementStr = element;
                const elementValue = window[elementStr];
                console.log(elementValue);

                if(Object.keys(elementValue).length === 0) {
                    $(`.help-block-${element}`).text(`This field is required`);
                    $(`.help-block-${element}`).addClass('text-danger');
                    access = false;
                } else {
                    $(`.help-block-${element}`).text('');
                }
            });
        }

        return access;
    }


    nextBtnFirst.addEventListener("click", function(event) {
        event.preventDefault();
        if (validateFirst()) {
            slidePage.style.marginLeft = "-20%";
            bullet[current - 1].classList.add("active");
            progressCheck[current - 1].classList.add("active");
            progressText[current - 1].classList.add("active");
            current += 1;
        }


    });
    nextBtnSec.addEventListener("click", function(event) {

        event.preventDefault();

        const SecondSlideFields = [
                              "traveler_distance_from_facility",
                              "clinical_setting",
                              "Patient_ratio",
                              "Unit",
                              "scrub_color",
                              "rto",
                              "job_id",
                              "job_name",
                              "preferred_work_location",
                              "referral_bonus",
                              "sign_on_bonus",
                              "completion_bonus",
                              "extension_bonus",
                              "other_bonus",
                              "on_call",
                              "on_call_rate",
                              "description"
                            ];

        if(validateRequiredFieldsToSubmit(SecondSlideFields)) { 
            slidePage.style.marginLeft = "-40%";
            bullet[current - 1].classList.add("active");
            progressCheck[current - 1].classList.add("active");
            progressText[current - 1].classList.add("active");
            current += 1;
        }
        
    });

    nextBtnThird.addEventListener("click", function(event) {
        event.preventDefault();

        const ThirdSlideFields = [
            "holiday",
            "orientation_rate",
            "block_scheduling",
            "float_requirement",
            "number_of_references",
            "facilitys_parent_system",
            "facility_name",
            "contract_termination_policy",
            "facility_shift_cancelation_policy",
            "four_zero_one_k",
            "health_insaurance",
            "feels_like_per_hour",
            "call_back_rate",
            "as_soon_as",
            "start_date",
            "urgency",
            "professional_state_licensure"
        ];

        if(validateRequiredFieldsToSubmit(ThirdSlideFields)) {
            slidePage.style.marginLeft = "-60%";
            bullet[current - 1].classList.add("active");
            progressCheck[current - 1].classList.add("active");
            progressText[current - 1].classList.add("active");
            current += 1;
        }
        
    });
    
    submitBtn.addEventListener("click", function(event) {

        event.preventDefault();
        let nurse_classification_all_values = document.getElementById("nurse_classificationAllValues");
        if (nurse_classification_all_values) {
            nurse_classification_all_values.value = nurse_classificationStr;
        }

        let professional_licensure_all_values = document.getElementById("professional_licensureAllValues");
        if (professional_licensure_all_values) {
            professional_licensure_all_values.value = professional_licensureStr;
        }

        let Emr_all_values = document.getElementById("EmrAllValues");
        if (Emr_all_values) {
            Emr_all_values.value = EmrStr;
        }

        let benefits_all_values = document.getElementById("benefitsAllValues");
        if (benefits_all_values) {
            benefits_all_values.value = benefitsStr;
        }

        let certif_all_values = document.getElementById("certificateAllValues");
        if (certif_all_values) {
            certif_all_values.value = certificateStr;
        }
        let vaccin_all_values = document.getElementById("vaccinationsAllValues");
        if (vaccin_all_values) {
            vaccin_all_values.value = vaccinationsStr;
        }
        let skills_all_values = document.getElementById("skillsAllValues");
        if (skills_all_values) {
            skills_all_values.value = skillsStr;
        }

        let shifttimeofday_all_values = document.getElementById("shifttimeofdayAllValues");
        if (shifttimeofday_all_values) {
            shifttimeofday_all_values.value = shifttimeofdayStr;
        }

        const slideFields = [
            "nurse_classification",
            "Emr",
            "benefits",
            "certificate",
            "vaccinations",
            "skills"
        ];

        if(validateRequiredMultiCheckFieldsToSubmit(slideFields)) {
            bullet[current - 1].classList.add("active");
            progressCheck[current - 1].classList.add("active");
            progressText[current - 1].classList.add("active");
            current += 1;
            document.getElementById("active").value = true;
            document.getElementById("is_open").value = true;
            
            event.target.form.submit();
        }

    });

    saveDrftBtn.forEach(function(btn) {
        btn.addEventListener("click", function(event) {
            event.preventDefault();
            let nurse_classification_all_values = document.getElementById("nurse_classificationAllValues");
        if (nurse_classification_all_values) {
            nurse_classification_all_values.value = nurse_classificationStr;
        }

        let professional_licensure_all_values = document.getElementById("professional_licensureAllValues");
        if (professional_licensure_all_values) {
            professional_licensure_all_values.value = professional_licensureStr;
        }

        let Emr_all_values = document.getElementById("EmrAllValues");
        if (Emr_all_values) {
            Emr_all_values.value = EmrStr;
        }

        let benefits_all_values = document.getElementById("benefitsAllValues");
        if (benefits_all_values) {
            benefits_all_values.value = benefitsStr;
        }

        let certif_all_values = document.getElementById("certificateAllValues");
        if (certif_all_values) {
            certif_all_values.value = certificateStr;
        }
        let vaccin_all_values = document.getElementById("vaccinationsAllValues");
        if (vaccin_all_values) {
            vaccin_all_values.value = vaccinationsStr;
        }
        let skills_all_values = document.getElementById("skillsAllValues");
        if (skills_all_values) {
            skills_all_values.value = skillsStr;
        }

        let shifttimeofday_all_values = document.getElementById("shifttimeofdayAllValues");
        if (shifttimeofday_all_values) {
            shifttimeofday_all_values.value = shifttimeofdayStr;
        }

            document.getElementById("active").value = false;
            document.getElementById("is_open").value = false;
            let act = document.getElementById("active").value;
            console.log(act);

            var jobName = document.getElementById("job_name").value;
            if (jobName.trim() === '') {
                $('.help-block-job_name').text('Enter at least a Work name');
                $('.help-block-job_name').addClass('text-danger');

            } else {
                $('.help-block-job_name').text('');
                event.target.form.submit();
            }
        });
    });

    prevBtnSec.addEventListener("click", function(event) {
        event.preventDefault();
        slidePage.style.marginLeft = "0%";
        bullet[current - 2].classList.remove("active");
        progressCheck[current - 2].classList.remove("active");
        progressText[current - 2].classList.remove("active");
        current -= 1;
    });
    prevBtnThird.addEventListener("click", function(event) {
        event.preventDefault();
        slidePage.style.marginLeft = "-20%";
        bullet[current - 2].classList.remove("active");
        progressCheck[current - 2].classList.remove("active");
        progressText[current - 2].classList.remove("active");
        current -= 1;
    });
    prevBtnFourth.addEventListener("click", function(event) {
        event.preventDefault();

        slidePage.style.marginLeft = "-40%";
        bullet[current - 2].classList.remove("active");
        progressCheck[current - 2].classList.remove("active");
        progressText[current - 2].classList.remove("active");
        current -= 1;
    });
    

    // for Work draft editing

    const slidePageDraft = document.querySelector(".slide-pageDraft");
    const nextBtnFirstDraft = document.querySelector(".firstNextDraft");
    const prevBtnSecDraft = document.querySelector(".prev-1Draft");
    const nextBtnSecDraft = document.querySelector(".next-1Draft");
    const prevBtnThirdDraft = document.querySelector(".prev-2Draft");
    const nextBtnThirdDraft = document.querySelector(".next-2Draft");
    
    const prevBtnFourthDraft = document.querySelector(".prev-3Draft");
    
    const submitBtnDraft = document.querySelector(".submitDraft");
    const saveDrftBtnDraft = document.querySelectorAll(".saveDrftBtnDraft");
    console.log(saveDrftBtnDraft);
    const progressTextDraft = document.querySelectorAll(".stepDraft p");
    const progressCheckDraft = document.querySelectorAll(".stepDraft .check");
    const bulletDraft = document.querySelectorAll(".stepDraft .bullet");

    let currentDraft = 1;


    function validateFirstDraft() {
        var access = true;
        var jobType = document.getElementById("job_typeDraft").value;
        var specialty = document.getElementById("preferred_specialtyDraft").value;
        var profession = document.getElementById("perferred_professionDraft").value;
        var city = document.getElementById("job_cityDraft").value;
        var state = document.getElementById("job_stateDraft").value;
        var weeklyPay = document.getElementById("weekly_payDraft").value;
        var terms = document.getElementById("termsDraft").value;
        var preferred_experience = document.getElementById("preferred_experienceDraft").value;
        var eligible_work_in_us = document.getElementById("eligible_work_in_usDraft").value;
        var hours_per_week = document.getElementById("hours_per_weekDraft").value;
        var guaranteed_hours = document.getElementById("guaranteed_hoursDraft").value;
        var hours_shift = document.getElementById("hours_shiftDraft").value;
        var weeks_shift = document.getElementById("weeks_shiftDraft").value;
        var pay_frequency = document.getElementById("pay_frequencyDraft").value;
        var actual_hourly_rate = document.getElementById("actual_hourly_rateDraft").value;
        var overtime = document.getElementById("overtimeDraft").value;
        var weekly_non_taxable_amount = document.getElementById("weekly_non_taxable_amountDraft").value;

        

        if (jobType.trim() === "") {
            $('.help-block-job_typeDraft').text('Please enter the Work type');
            $('.help-block-job_typeDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_typeDraft').text('');

        }

        if (specialty.trim() === '') {
            $('.help-block-preferred_specialtyDraft').text('Please enter the Work speciality');
            $('.help-block-preferred_specialtyDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-preferred_specialtyDraft').text('');

        }

        if (profession.trim() === '') {
            $('.help-block-perferred_professionDraft').text('Please enter the Work profession');
            $('.help-block-perferred_professionDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-perferred_professionDraft').text('');

        }

        if (terms.trim() === '') {
            $('.help-block-termsDraft').text('Please enter the terms');
            $('.help-block-termsDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-termsDraft').text('');
        }

        if (city.trim() === '') {
            $('.help-block-job_cityDraft').text('Please enter the Work city');
            $('.help-block-job_cityDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_cityDraft').text('');

        }

        if (state.trim() === '') {
            $('.help-block-job_stateDraft').text('Please enter the Work state');
            $('.help-block-job_stateDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_stateDraft').text('');
        }

        if (weeklyPay.trim() === '') {
            $('.help-block-weekly_payDraft').text('Please enter the Work weekly pay');
            $('.help-block-weekly_payDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-weekly_payDraft').text('');

        }

        if (preferred_experience.trim() === '') {
            $('.help-block-preferred_experienceDraft').text('Please enter the preferred experience');
            $('.help-block-preferred_experienceDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-preferred_experienceDraft').text('');
        }

        if (eligible_work_in_us.trim() === '') {
            $('.help-block-eligible_work_in_usDraft').text('Please enter the eligible work in us');
            $('.help-block-eligible_work_in_usDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-eligible_work_in_usDraft').text('');
        }

        if (hours_per_week.trim() === '') {
            $('.help-block-hours_per_weekDraft').text('Please enter the hours per week');
            $('.help-block-hours_per_weekDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-hours_per_weekDraft').text('');
        }

        if (guaranteed_hours.trim() === '') {
            $('.help-block-guaranteed_hoursDraft').text('Please enter the guaranteed hours');
            $('.help-block-guaranteed_hoursDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-guaranteed_hoursDraft').text('');
        }

        if (hours_shift.trim() === '') {
            $('.help-block-hours_shiftDraft').text('Please enter the hours shift');
            $('.help-block-hours_shiftDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-hours_shiftDraft').text('');
        }

        if (weeks_shift.trim() === '') {
            $('.help-block-weeks_shiftDraft').text('Please enter the weeks shift');
            $('.help-block-weeks_shiftDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-weeks_shiftDraft').text('');
        }

        if (pay_frequency.trim() === '') {
            $('.help-block-pay_frequencyDraft').text('Please enter the pay frequency');
            $('.help-block-pay_frequencyDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-pay_frequencyDraft').text('');
        }

        if (actual_hourly_rate.trim() === '') {
            $('.help-block-actual_hourly_rateDraft').text('Please enter the actual hourly rate');
            $('.help-block-actual_hourly_rateDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-actual_hourly_rateDraft').text('');
        }

        if (overtime.trim() === '') {
            $('.help-block-overtimeDraft').text('Please enter the overtime');
            $('.help-block-overtimeDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-overtimeDraft').text('');
        }

        if (weekly_non_taxable_amount.trim() === '') {
            $('.help-block-weekly_non_taxable_amountDraft').text('Please enter the weekly non taxable amount');
            $('.help-block-weekly_non_taxable_amountDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-weekly_non_taxable_amountDraft').text('');
        }

        if(Object.keys(shifttimeofday).length === 0){
            $('.help-block-shift_time_of_dayDraft').text('Please enter the Shift time of day');
            $('.help-block-shift_time_of_dayDraft').addClass('text-danger');
            access = false;
        }else{
            $('.help-block-shift_time_of_dayDraft').text('');
        }

        if(Object.keys(professional_licensure).length === 0){
            $('.help-block-professional_licensureDraft').text('Please enter the Professional licensure');
            $('.help-block-professional_licensureDraft').addClass('text-danger');
            access = false;
        }else{
            $('.help-block-professional_licensureDraft').text('');
        }


        if (access) {
            return true;
        } else {
            return false;
        }
    }


    function validateRequiredFieldsToSubmitDraft(slideFields){

            let access = true;
            const commonElements = slideFields.filter(element => requiredToSubmit.includes(element));

                        if(commonElements.length > 0) {
                        
                            commonElements.forEach(element => {
                            
                                if(element === 'professional_state_licensure'){
                                    const activeElementHtml = document.getElementById('professional_state_licensure_activeDraft');
                                    const pendingElementHtml = document.getElementById('professional_state_licensure_pendingDraft');
                                
                                    if(!activeElementHtml.checked && !pendingElementHtml.checked) {
                                        $(`.help-block-professional_state_licensureDraft`).text(`This field is required`);
                                        $(`.help-block-professional_state_licensureDraft`).addClass('text-danger');
                                        access = false;
                                    } else {
                                        $(`.help-block-professional_state_licensureDraft`).text('');
                                    
                                    }
                                
                                    return;
                                }
                            
                                if (element === 'urgency') {
                                
                                    const urgencyElementHtml = document.getElementById('urgencyDraft');
                                    if(!urgencyElementHtml.checked) {
                                        $(`.help-block-urgencyDraft`).text(`This field is required`);
                                        $(`.help-block-urgencyDraft`).addClass('text-danger');
                                        access = false;
                                    } else {
                                        $(`.help-block-urgencyDraft`).text('');
                                    }
                                
                                    return;
                                }
                            
                                const elementHtml = document.getElementById(element+'Draft');
                                const elementValue = elementHtml.value;
                                if(elementValue.trim() === '') {
                                
                                    $(`.help-block-${element}Draft`).text(`This field is required`);
                                    $(`.help-block-${element}Draft`).addClass('text-danger');
                                    access = false;
                                
                                } else {
                                
                                    $(`.help-block-${element}Draft`).text('');
                                
                                }
                            
                            });
                        }
                    
            return access;
    }

    function validateRequiredMultiCheckFieldsToSubmitDraft(slideFields) {
        let access = true;
        const commonElements = slideFields.filter(element => requiredToSubmit.includes(element));
        
        if(commonElements.length > 0) {
        
            commonElements.forEach(element => {
            
                const elementStr = element;
                const elementValue = window[elementStr];
                console.log(elementValue);
            
                if(Object.keys(elementValue).length === 0) {
                    $(`.help-block-${element}Draft`).text(`This field is required`);
                    $(`.help-block-${element}Draft`).addClass('text-danger');
                    access = false;
                } else {
                    $(`.help-block-${element}Draft`).text('');
                }
            });
        }

        return access;
    }

    nextBtnFirstDraft.addEventListener("click", function(event) {
        event.preventDefault();
        if (validateFirstDraft()) {
            slidePageDraft.style.marginLeft = "-20%";
            bulletDraft[currentDraft - 1].classList.add("active");
            progressCheckDraft[currentDraft - 1].classList.add("active");
            progressTextDraft[currentDraft - 1].classList.add("active");
            currentDraft += 1;
        }


    });
    nextBtnSecDraft.addEventListener("click", function(event) {
        event.preventDefault();
        
        const SecondSlideFields = [
                                "traveler_distance_from_facility",
                                "clinical_setting",
                                "Patient_ratio",
                                "Unit",
                                "scrub_color",
                                "rto",
                                "job_id",
                                "job_name",
                                "preferred_work_location",
                                "referral_bonus",
                                "sign_on_bonus",
                                "completion_bonus",
                                "extension_bonus",
                                "other_bonus",
                                "on_call",
                                "on_call_rate",
                                "description"
        ];
        if(validateRequiredFieldsToSubmitDraft(SecondSlideFields)) { 
            slidePageDraft.style.marginLeft = "-40%";
            bulletDraft[currentDraft - 1].classList.add("active");
            progressCheckDraft[currentDraft - 1].classList.add("active");
            progressTextDraft[currentDraft - 1].classList.add("active");
            currentDraft += 1;
        }

    });
    nextBtnThirdDraft.addEventListener("click", function(event) {
        event.preventDefault();
        const ThirdSlideFields = [
            "holiday",
            "orientation_rate",
            "block_scheduling",
            "float_requirement",
            "number_of_references",
            "facilitys_parent_system",
            "facility_name",
            "contract_termination_policy",
            "facility_shift_cancelation_policy",
            "four_zero_one_k",
            "health_insaurance",
            "feels_like_per_hour",
            "call_back_rate",
            "as_soon_as",
            "start_date",
            "urgency",
            "professional_state_licensure"
        ];

        if(validateRequiredFieldsToSubmitDraft(ThirdSlideFields)) {
            slidePageDraft.style.marginLeft = "-60%";
            bulletDraft[currentDraft - 1].classList.add("active");
            progressCheckDraft[currentDraft - 1].classList.add("active");
            progressTextDraft[currentDraft - 1].classList.add("active");
            currentDraft += 1;
        }
        
    });
    
    submitBtnDraft.addEventListener("click", function(event) {
        event.preventDefault();
        document.getElementById("activeDraft").value = true;
        document.getElementById("is_openDraft").value = true;

        let nurse_classification_all_values = document.getElementById("nurse_classificationAllValuesDraft");
        if (nurse_classification_all_values) {
            nurse_classification_all_values.value = nurse_classificationStr;
        }

        let professional_licensure_all_values = document.getElementById("professional_licensureAllValuesDraft");
        if (professional_licensure_all_values) {
            professional_licensure_all_values.value = professional_licensureStr;
        }

        let Emr_all_values = document.getElementById("EmrAllValuesDraft");
        if (Emr_all_values) {
            Emr_all_values.value = EmrStr;
        }

        let benefits_all_values = document.getElementById("benefitsAllValuesDraft");
        if (benefits_all_values) {
            benefits_all_values.value = benefitsStr;
        }

        let certif_all_values = document.getElementById("certificateAllValuesDraft");
        if (certif_all_values) {
            certif_all_values.value = certificateStr;
        }
        let vaccin_all_values = document.getElementById("vaccinationsAllValuesDraft");
        if (vaccin_all_values) {
            vaccin_all_values.value = vaccinationsStr;
        }
        let skills_all_values = document.getElementById("skillsAllValuesDraft");
        if (skills_all_values) {
            skills_all_values.value = skillsStr;
        }
        let shifttimeofday_all_values = document.getElementById("shifttimeofdayAllValuesDraft");
        if (shifttimeofday_all_values) {
            shifttimeofday_all_values.value = shifttimeofdayStr;
        }


        const slideFields = [
            "nurse_classification",
            "Emr",
            "benefits",
            "certificate",
            "vaccinations",
            "skills"
        ];
        
        if (validateRequiredMultiCheckFieldsToSubmitDraft(slideFields)) {
            bulletDraft[currentDraft - 1].classList.add("active");
            progressCheckDraft[currentDraft - 1].classList.add("active");
            progressTextDraft[currentDraft - 1].classList.add("active");
            currentDraft += 1;

            event.target.form.submit();
        }
    });

    saveDrftBtnDraft.forEach(function(saveDrftBtnDraft) {
        saveDrftBtnDraft.addEventListener("click", function(event) {
        event.preventDefault();
        let nurse_classification_all_values = document.getElementById("nurse_classificationAllValuesDraft");
        if (nurse_classification_all_values) {
            nurse_classification_all_values.value = nurse_classificationStr;
        }

        let professional_licensure_all_values = document.getElementById("professional_licensureAllValuesDraft");
        if (professional_licensure_all_values) {
            professional_licensure_all_values.value = professional_licensureStr;
        }

        let Emr_all_values = document.getElementById("EmrAllValuesDraft");
        if (Emr_all_values) {
            Emr_all_values.value = EmrStr;
        }

        let benefits_all_values = document.getElementById("benefitsAllValuesDraft");
        if (benefits_all_values) {
            benefits_all_values.value = benefitsStr;
        }

        let certif_all_values = document.getElementById("certificateAllValuesDraft");
        if (certif_all_values) {
            certif_all_values.value = certificateStr;
        }
        let vaccin_all_values = document.getElementById("vaccinationsAllValuesDraft");
        if (vaccin_all_values) {
            vaccin_all_values.value = vaccinationsStr;
        }
        let skills_all_values = document.getElementById("skillsAllValuesDraft");
        if (skills_all_values) {
            skills_all_values.value = skillsStr;
        }
        let shifttimeofday_all_values = document.getElementById("shifttimeofdayAllValuesDraft");
        if (shifttimeofday_all_values) {
            shifttimeofday_all_values.value = shifttimeofdayStr;
        }
            document.getElementById("activeDraft").value = false;
            document.getElementById("is_openDraft").value = false;
            var jobName = document.getElementById("job_nameDraft").value;
            if (jobName.trim() === '') {
                $('.help-block-job_name').text('Enter at least a Work name');
                $('.help-block-job_name').addClass('text-danger');

            } else {
                $('.help-block-job_name').text('');
                event.target.form.submit();
            }
        });
    });


    prevBtnSecDraft.addEventListener("click", function(event) {
        event.preventDefault();
        slidePageDraft.style.marginLeft = "-0%";
        bulletDraft[currentDraft - 2].classList.remove("active");
        progressCheckDraft[currentDraft - 2].classList.remove("active");
        progressTextDraft[currentDraft - 2].classList.remove("active");
        currentDraft -= 1;
    });
    prevBtnThirdDraft.addEventListener("click", function(event) {
        event.preventDefault();
        slidePageDraft.style.marginLeft = "-20%";
        bulletDraft[currentDraft - 2].classList.remove("active");
        progressCheckDraft[currentDraft - 2].classList.remove("active");
        progressTextDraft[currentDraft - 2].classList.remove("active");
        currentDraft -= 1;
    });
    prevBtnFourthDraft.addEventListener("click", function(event) {
        event.preventDefault();
        slidePageDraft.style.marginLeft = "-40%";
        bulletDraft[currentDraft - 2].classList.remove("active");
        progressCheckDraft[currentDraft - 2].classList.remove("active");
        progressTextDraft[currentDraft - 2].classList.remove("active");
        currentDraft -= 1;
    });
    


    // for Work editing

    const slidePageEdit = document.querySelector(".slide-pageEdit");
    const nextBtnFirstEdit = document.querySelector(".firstNextEdit");
    const prevBtnSecEdit = document.querySelector(".prev-1Edit");
    const nextBtnSecEdit = document.querySelector(".next-1Edit");
    const prevBtnThirdEdit = document.querySelector(".prev-2Edit");
    const nextBtnThirdEdit = document.querySelector(".next-2Edit");

    const prevBtnFourthEdit = document.querySelector(".prev-3Edit");
    
    const submitBtnEdit = document.querySelector(".submitEdit");
    const saveDrftBtnEdit = document.querySelectorAll(".saveDrftBtnEdit");
    const progressTextEdit = document.querySelectorAll(".stepEdit p");
    const progressCheckEdit = document.querySelectorAll(".stepEdit .check");
    const bulletEdit = document.querySelectorAll(".stepEdit .bullet");

    let currentEdit = 1;

    function validateFirstEdit() {
        var access = true;
        var jobType = document.getElementById("job_typeEdit").value;
        var specialty = document.getElementById("preferred_specialtyEdit").value;
        var profession = document.getElementById("perferred_professionEdit").value;
        var city = document.getElementById("job_cityEdit").value;
        var state = document.getElementById("job_stateEdit").value;
        var weeklyPay = document.getElementById("weekly_payEdit").value;
        var terms = document.getElementById("termsEdit").value;
        var preferred_experience = document.getElementById("preferred_experienceEdit").value;
        var eligible_work_in_us = document.getElementById("eligible_work_in_usEdit").value;
        var hours_per_week = document.getElementById("hours_per_weekEdit").value;
        var guaranteed_hours = document.getElementById("guaranteed_hoursEdit").value;
        var hours_shift = document.getElementById("hours_shiftEdit").value;
        var weeks_shift = document.getElementById("weeks_shiftEdit").value;
        var pay_frequency = document.getElementById("pay_frequencyEdit").value;
        var actual_hourly_rate = document.getElementById("actual_hourly_rateEdit").value;
        var overtime = document.getElementById("overtimeEdit").value;
        var weekly_non_taxable_amount = document.getElementById("weekly_non_taxable_amountEdit").value;

        

        if (jobType.trim() === "") {
            $('.help-block-job_typeEdit').text('Please enter the Work type');
            $('.help-block-job_typeEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_typeEdit').text('');

        }

        if (specialty.trim() === '') {
            $('.help-block-preferred_specialtyEdit').text('Please enter the Work speciality');
            $('.help-block-preferred_specialtyEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-preferred_specialtyEdit').text('');

        }

        if (profession.trim() === '') {
            $('.help-block-perferred_professionEdit').text('Please enter the Work profession');
            $('.help-block-perferred_professionEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-perferred_professionEdit').text('');

        }

        if (terms.trim() === '') {
            $('.help-block-termsEdit').text('Please enter the terms');
            $('.help-block-termsEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-termsEdit').text('');
        }

        if (city.trim() === '') {
            $('.help-block-job_cityEdit').text('Please enter the Work city');
            $('.help-block-job_cityEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_cityEdit').text('');

        }

        if (state.trim() === '') {
            $('.help-block-job_stateEdit').text('Please enter the Work state');
            $('.help-block-job_stateEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_stateEdit').text('');
        }

        if (weeklyPay.trim() === '') {
            $('.help-block-weekly_payEdit').text('Please enter the Work weekly pay');
            $('.help-block-weekly_payEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-weekly_payEdit').text('');

        }

        if (preferred_experience.trim() === '') {
            $('.help-block-preferred_experienceEdit').text('Please enter the preferred experience');
            $('.help-block-preferred_experienceEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-preferred_experienceEdit').text('');
        }

        if (eligible_work_in_us.trim() === '') {
            $('.help-block-eligible_work_in_usEdit').text('Please enter the eligible work in us');
            $('.help-block-eligible_work_in_usEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-eligible_work_in_usEdit').text('');
        }

        if (hours_per_week.trim() === '') {
            $('.help-block-hours_per_weekEdit').text('Please enter the hours per week');
            $('.help-block-hours_per_weekEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-hours_per_weekEdit').text('');
        }

        if (guaranteed_hours.trim() === '') {
            $('.help-block-guaranteed_hoursEdit').text('Please enter the guaranteed hours');
            $('.help-block-guaranteed_hoursEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-guaranteed_hoursEdit').text('');
        }

        if (hours_shift.trim() === '') {
            $('.help-block-hours_shiftEdit').text('Please enter the hours shift');
            $('.help-block-hours_shiftEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-hours_shiftEdit').text('');
        }

        if (weeks_shift.trim() === '') {
            $('.help-block-weeks_shiftEdit').text('Please enter the weeks shift');
            $('.help-block-weeks_shiftEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-weeks_shiftEdit').text('');
        }

        if (pay_frequency.trim() === '') {
            $('.help-block-pay_frequencyEdit').text('Please enter the pay frequency');
            $('.help-block-pay_frequencyEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-pay_frequencyEdit').text('');
        }

        if (actual_hourly_rate.trim() === '') {
            $('.help-block-actual_hourly_rateEdit').text('Please enter the actual hourly rate');
            $('.help-block-actual_hourly_rateEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-actual_hourly_rateEdit').text('');
        }

        if (overtime.trim() === '') {
            $('.help-block-overtimeEdit').text('Please enter the overtime');
            $('.help-block-overtimeEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-overtimeEdit').text('');
        }

        if (weekly_non_taxable_amount.trim() === '') {
            $('.help-block-weekly_non_taxable_amountEdit').text('Please enter the weekly non taxable amount');
            $('.help-block-weekly_non_taxable_amountEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-weekly_non_taxable_amountEdit').text('');
        }

        // shift time of day
        // the shift time of day is {} how can i check if it is empty ?
        
        if (Object.keys(shifttimeofday).length === 0) {
            $('.help-block-shift_time_of_day').text('Please enter the Shift time of day');
            $('.help-block-shift_time_of_day').addClass('text-danger');
            access = false;
        }else{
            $('.help-block-shift_time_of_day').text('');
        }
        // professional licensure
        if (Object.keys(professional_licensure).length === 0) {
            $('.help-block-professional_licensure').text('Please enter the Professional licensure');
            $('.help-block-professional_licensure').addClass('text-danger');
            access = false; 
        }else{
            $('.help-block-professional_licensure').text('');
        }

        if (access) {
            return true;
        } else {
            return false;
        }
    }


    function validateRequiredFieldsToSubmitEdit(slideFields){

        let access = true;
        const commonElements = slideFields.filter(element => requiredToSubmit.includes(element));

                    if(commonElements.length > 0) {
                    
                        commonElements.forEach(element => {
                        
                            if(element === 'professional_state_licensure'){
                                const activeElementHtml = document.getElementById('professional_state_licensure_activeEdit');
                                const pendingElementHtml = document.getElementById('professional_state_licensure_pendingEdit');
                            
                                if(!activeElementHtml.checked && !pendingElementHtml.checked) {
                                    $(`.help-block-professional_state_licensureEdit`).text(`This field is required`);
                                    $(`.help-block-professional_state_licensureEdit`).addClass('text-danger');
                                    access = false;
                                } else {
                                    $(`.help-block-professional_state_licensureEdit`).text('');
                                
                                }
                            
                                return;
                            }
                        
                            if (element === 'urgency') {
                            
                                const urgencyElementHtml = document.getElementById('urgencyEdit');
                                if(!urgencyElementHtml.checked) {
                                    $(`.help-block-urgencyEdit`).text(`This field is required`);
                                    $(`.help-block-urgencyEdit`).addClass('text-danger');
                                    access = false;
                                } else {
                                    $(`.help-block-urgencyEdit`).text('');
                                }
                            
                                return;
                            }
                        
                            const elementHtml = document.getElementById(element+'Edit');
                            const elementValue = elementHtml.value;
                            if(elementValue.trim() === '') {
                            
                                $(`.help-block-${element}Edit`).text(`This field is required`);
                                $(`.help-block-${element}Edit`).addClass('text-danger');
                                access = false;
                            
                            } else {
                            
                                $(`.help-block-${element}Edit`).text('');
                            
                            }
                        
                        });
                    }
                
        return access;
    }

    function validateRequiredMultiCheckFieldsToSubmitEdit(slideFields) {
        let access = true;
        const commonElements = slideFields.filter(element => requiredToSubmit.includes(element));

        if(commonElements.length > 0) {

            commonElements.forEach(element => {

                const elementStr = element;
                const elementValue = window[elementStr];
                console.log(elementValue);

                if(Object.keys(elementValue).length === 0) {
                    $(`.help-block-${element}Edit`).text(`This field is required`);
                    $(`.help-block-${element}Edit`).addClass('text-danger');
                    access = false;
                } else {
                    $(`.help-block-${element}Edit`).text('');
                }
            });
        }

        return access;
    }



    nextBtnFirstEdit.addEventListener("click", function(event) {
        event.preventDefault();
        if (validateFirstEdit()) {
            slidePageEdit.style.marginLeft = "-20%";
            bulletEdit[currentEdit - 1].classList.add("active");
            progressCheckEdit[currentEdit - 1].classList.add("active");
            progressTextEdit[currentEdit - 1].classList.add("active");
            currentEdit += 1;
        }


    });
    nextBtnSecEdit.addEventListener("click", function(event) {

        event.preventDefault();
        const SecondSlideFields = [
                                "traveler_distance_from_facility",
                                "clinical_setting",
                                "Patient_ratio",
                                "Unit",
                                "scrub_color",
                                "rto",
                                "job_id",
                                "job_name",
                                "preferred_work_location",
                                "referral_bonus",
                                "sign_on_bonus",
                                "completion_bonus",
                                "extension_bonus",
                                "other_bonus",
                                "on_call",
                                "on_call_rate",
                                "description"
                                ];

        if(validateRequiredFieldsToSubmitEdit(SecondSlideFields)) { 
            slidePageEdit.style.marginLeft = "-40%";
            bulletEdit[currentEdit - 1].classList.add("active");
            progressCheckEdit[currentEdit - 1].classList.add("active");
            progressTextEdit[currentEdit - 1].classList.add("active");
            currentEdit += 1;
        }

    });
    nextBtnThirdEdit.addEventListener("click", function(event) {
        event.preventDefault();
        const ThirdSlideFields = [
            "holiday",
            "orientation_rate",
            "block_scheduling",
            "float_requirement",
            "number_of_references",
            "facilitys_parent_system",
            "facility_name",
            "contract_termination_policy",
            "facility_shift_cancelation_policy",
            "four_zero_one_k",
            "health_insaurance",
            "feels_like_per_hour",
            "call_back_rate",
            "as_soon_as",
            "start_date",
            "urgency",
            "professional_state_licensure"
        ];

        if(validateRequiredFieldsToSubmitEdit(ThirdSlideFields)) {
            slidePageEdit.style.marginLeft = "-60%";
            bulletEdit[currentEdit - 1].classList.add("active");
            progressCheck[currentEdit - 1].classList.add("active");
            progressTextEdit[currentEdit - 1].classList.add("active");
            currentEdit += 1;
         }
    });
   
    submitBtnEdit.addEventListener("click", function(event) {

        event.preventDefault();
        let nurse_classification_all_values = document.getElementById("nurse_classificationAllValuesEdit");
        if (nurse_classification_all_values) {
            nurse_classification_all_values.value = nurse_classificationStr;
        }

        let professional_licensure_all_values = document.getElementById("professional_licensureAllValuesEdit");
        if (professional_licensure_all_values) {
            professional_licensure_all_values.value = professional_licensureStr;
        }

        let Emr_all_values = document.getElementById("EmrAllValuesEdit");
        if (Emr_all_values) {
            Emr_all_values.value = EmrStr;
        }

        let benefits_all_values = document.getElementById("benefitsAllValuesEdit");
        if (benefits_all_values) {
            benefits_all_values.value = benefitsStr;
        }

        let certif_all_values = document.getElementById("certificateAllValuesEdit");
        if (certif_all_values) {
            certif_all_values.value = certificateStr;
        }
        let vaccin_all_values = document.getElementById("vaccinationsAllValuesEdit");
        if (vaccin_all_values) {
            vaccin_all_values.value = vaccinationsStr;
        }
        let skills_all_values = document.getElementById("skillsAllValuesEdit");
        if (skills_all_values) {
            skills_all_values.value = skillsStr;
        }
        let shifttimeofday_all_values = document.getElementById("shifttimeofdayAllValuesEdit");
        if (shifttimeofday_all_values) {
            shifttimeofday_all_values.value = shifttimeofdayStr;
        }
        document.getElementById("activeEdit").value = true;
        document.getElementById("is_openEdit").value = true;

        console.log(EmrStr);
        console.log('edit' + nurse_classificationStr);

        const slideFields = [
            "nurse_classification",
            "Emr",
            "benefits",
            "certificate",
            "vaccinations",
            "skills"
        ];

        if (validateRequiredMultiCheckFieldsToSubmitEdit(slideFields)) {
            bulletEdit[currentEdit - 1].classList.add("active");
            progressCheckEdit[currentEdit - 1].classList.add("active");
            progressTextEdit[currentEdit - 1].classList.add("active");
            currentEdit += 1;
            event.target.form.submit();
        }
    });


    saveDrftBtnEdit.forEach(function(saveDrftBtnEdit) {
        saveDrftBtnEdit.addEventListener("click", function(event) {
            event.preventDefault();
            let nurse_classification_all_values = document.getElementById("nurse_classificationAllValuesEdit");
        if (nurse_classification_all_values) {
            nurse_classification_all_values.value = nurse_classificationStr;
        }

        let professional_licensure_all_values = document.getElementById("professional_licensureAllValuesEdit");
        if (professional_licensure_all_values) {
            professional_licensure_all_values.value = professional_licensureStr;
        }

        let Emr_all_values = document.getElementById("EmrAllValuesEdit");
        if (Emr_all_values) {
            Emr_all_values.value = EmrStr;
        }

        let benefits_all_values = document.getElementById("benefitsAllValuesEdit");
        if (benefits_all_values) {
            benefits_all_values.value = benefitsStr;
        }

        let certif_all_values = document.getElementById("certificateAllValuesEdit");
        if (certif_all_values) {
            certif_all_values.value = certificateStr;
        }
        let vaccin_all_values = document.getElementById("vaccinationsAllValuesEdit");
        if (vaccin_all_values) {
            vaccin_all_values.value = vaccinationsStr;
        }
        let skills_all_values = document.getElementById("skillsAllValuesEdit");
        if (skills_all_values) {
            skills_all_values.value = skillsStr;
        }
        let shifttimeofday_all_values = document.getElementById("shifttimeofdayAllValuesEdit");
        if (shifttimeofday_all_values) {
            shifttimeofday_all_values.value = shifttimeofdayStr;
        }
            document.getElementById("activeEdit").value = false;
            document.getElementById("is_openEdit").value = false;
            var jobName = document.getElementById("job_nameEdit").value;
            if (jobName.trim() === '') {
                $('.help-block-job_name').text('Enter at least a Work name');
                $('.help-block-job_name').addClass('text-danger');
            } else {
                $('.help-block-job_name').text('');
                event.target.form.submit();
            }
        });
    });


    prevBtnSecEdit.addEventListener("click", function(event) {
        event.preventDefault();
        slidePageEdit.style.marginLeft = "0%";
        bulletEdit[currentEdit - 2].classList.remove("active");
        progressCheckEdit[currentEdit - 2].classList.remove("active");
        progressTextEdit[currentEdit - 2].classList.remove("active");
        currentEdit -= 1;
    });
    prevBtnThirdEdit.addEventListener("click", function(event) {
        event.preventDefault();
        slidePageEdit.style.marginLeft = "-20%";
        bulletEdit[currentEdit - 2].classList.remove("active");
        progressCheckEdit[currentEdit - 2].classList.remove("active");
        progressTextEdit[currentEdit - 2].classList.remove("active");
        currentEdit -= 1;
    });
    prevBtnFourthEdit.addEventListener("click", function(event) {
        event.preventDefault();
        slidePageEdit.style.marginLeft = "-40%";
        bulletEdit[currentEdit - 2].classList.remove("active");
        progressCheck[currentEdit - 2].classList.remove("active");
        progressTextEdit[currentEdit - 2].classList.remove("active");
        currentEdit -= 1;
    });
    
    
    function job_details_to_edit(id) {
        document.getElementById('details_published').classList.add('d-none');
        document.getElementById('details_edit_job').classList.remove('d-none');

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: "{{ url('recruiter/get-job-to-edit') }}",
                data: {
                    'id': id
                },
                type: 'POST',
                dataType: 'json',
             
                success: function(result) {
        console.log(result);

        const fields = {
            
            'id': { id: 'idEdit', type: 'number' },
            'job_id': { id: 'job_idEdit', type: 'number' },
            'job_name': { id: 'job_nameEdit', type: 'text' },
            'job_type': { id: 'job_typeEdit', type: 'select' },
            'preferred_specialty': { id: 'preferred_specialtyEdit', type: 'select' },
            'profession': { id: 'perferred_professionEdit', type: 'select' },
            'job_state': { id: 'job_stateEdit', type: 'select' },
            'job_city': { id: 'job_cityEdit', type: 'select' },
            'weekly_pay': { id: 'weekly_payEdit', type: 'number' },
            'terms': { id: 'termsEdit', type: 'select' },
            'preferred_assignment_duration': { id: 'preferred_assignment_durationEdit', type: 'number' },
            'facility_shift_cancelation_policy': { id: 'facility_shift_cancelation_policyEdit', type: 'text' },
            'traveler_distance_from_facility': { id: 'traveler_distance_from_facilityEdit', type: 'number' },
            'clinical_setting': { id: 'clinical_settingEdit', type: 'select' },
            'Patient_ratio': { id: 'Patient_ratioEdit', type: 'number' },
            'Unit': { id: 'UnitEdit', type: 'text' },
            'scrub_color': { id: 'scrub_colorEdit', type: 'text' },
            'rto': { id: 'rtoEdit', type: 'select' },
            'guaranteed_hours': { id: 'guaranteed_hoursEdit', type: 'number' },
            'hours_per_week': { id: 'hours_per_weekEdit', type: 'number' },
            'hours_shift': { id: 'hours_shiftEdit', type: 'number' },
            'weeks_shift': { id: 'weeks_shiftEdit', type: 'number' },
            'referral_bonus': { id: 'referral_bonusEdit', type: 'number' },
            'sign_on_bonus': { id: 'sign_on_bonusEdit', type: 'number' },
            'completion_bonus': { id: 'completion_bonusEdit', type: 'number' },
            'extension_bonus': { id: 'extension_bonusEdit', type: 'number' },
            'other_bonus': { id: 'other_bonusEdit', type: 'number' },
            'actual_hourly_rate': { id: 'actual_hourly_rateEdit', type: 'number' },
            'overtime': { id: 'overtimeEdit', type: 'number' },
            'on_call': { id: 'on_callEdit', type: 'select', options: { 'No': '0', 'Yes': '1' } },
            'on_call_rate': { id: 'on_call_rateEdit', type: 'number' },
            'holiday': { id: 'holidayEdit', type: 'date' },
            'orientation_rate': { id: 'orientation_rateEdit', type: 'number' },
            'block_scheduling': { id: 'block_schedulingEdit', type: 'select', options: { 'No': '0', 'Yes': '1' } },
            'float_requirement': { id: 'float_requirementEdit', type: 'select', options: { 'No': '0', 'Yes': '1' } },
            'number_of_references': { id: 'number_of_referencesEdit', type: 'number' },
            'eligible_work_in_us': { id: 'eligible_work_in_usEdit', type: 'select' },
            'urgency': { id: 'urgencyEdit', type: 'checkbox' },
            'facilitys_parent_system': { id: 'facilitys_parent_systemEdit', type: 'text' },
            'facility_name': { id: 'facility_nameEdit', type: 'text' },
            'pay_frequency': { id: 'pay_frequencyEdit', type: 'select' },
            'preferred_experience': { id: 'preferred_experienceEdit', type: 'number' },
            'contract_termination_policy': { id: 'contract_termination_policyEdit', type: 'text' },
            'four_zero_one_k': { id: 'four_zero_one_kEdit', type: 'select', options: { 'No': '0', 'Yes': '1' } },
            'health_insaurance': { id: 'health_insauranceEdit', type: 'select', options: { 'No': '0', 'Yes': '1' } },
            'feels_like_per_hour': { id: 'feels_like_per_hourEdit', type: 'number' },
            'call_back_rate': { id: 'call_back_rateEdit', type: 'number' },
            'weekly_non_taxable_amount': { id: 'weekly_non_taxable_amountEdit', type: 'number' },
            'start_date': { id: 'start_dateEdit', type: 'date' },
            'preferred_experience': { id: 'preferred_experienceEdit', type: 'number' },
            'professional_state_licensure': { id: 'professional_state_licensure_pendingEdit', type: 'radio' },
            'description': { id: 'descriptionEdit', type: 'text' },
            'preferred_work_location': { id: 'preferred_work_locationEdit', type: 'text' },
            'as_soon_as': { id: 'as_soon_asEdit', type: 'checkbox' },
        };

        for (const [key, field] of Object.entries(fields)) {
            const element = document.getElementById(field.id);
            if (!element) continue;

            if (field.type === 'select') {
                if (field.options) {
                    element.value = result[key] == '1' ? 'Yes' : 'No';
                } else {
                    const option = document.createElement('option');
                    option.value = result[key];
                    option.text = result[key];
                    option.hidden = true;
                    element.add(option);
                    element.value = result[key];
                    
                }
            } else if (field.type === 'checkbox') {
                // one for the urgency and one for the as soon as
                field.id === 'urgencyEdit' ? element.checked = result[key] === 'Auto Offer' : element.checked = result[key] === '1';
                    console.log('checkbox test', result[key]);
            }
            else if (field.type === 'radio') {
                console.log('radio', result[key]);
                if (result[key] === 'Accept Pending') {
                    document.getElementById('professional_state_licensure_pendingEdit').checked = true;
                } else {
                    document.getElementById('professional_state_licensure_activeEdit').checked = true;
                }
            }
             else {
                element.value = result[key];
            }
        }
        // list emr 
        var emr = result['Emr'];
        if(emr !== null){
        emr = emr.split(', ');

        emr.forEach(function(item) {
            @php
                $allKeywordsJSON = json_encode($allKeywords['EMR']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            var data = JSON.parse(allspcldata);
            data.forEach(function(itemData) {
                if (item == itemData.title) {
                    Emr[itemData.id] = item;
                }
            });
        });
        }
        list_Emr();

        // list benefits

        var benefitsResult = result['benefits'];
        if(benefitsResult) {
        benefitsResult = benefitsResult.split(', ');
        
            benefitsResult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['Benefits']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        benefits[itemData.id] = item;
                    }
                });
            });
        }
        list_benefits();

        // list nurse classification
        var nurse_classificationResult = result['nurse_classification'];
        if(nurse_classificationResult !== null){
        nurse_classificationResult = nurse_classificationResult.split(', ');
        
            nurse_classificationResult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['NurseClassification']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        nurse_classification[itemData.id] = item;
                    }
                });
            });
        }
        
        list_nurse_classification();

        // list professional licensure

        var professional_licensureResult = result['job_location'];
        if(professional_licensureResult !== null){
       
        professional_licensureResult = professional_licensureResult.split(', ');
       
            
        professional_licensureResult.forEach(function(item) {
            @php
                $allKeywordsJSON = json_encode($allKeywords['StateCode']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            var data = JSON.parse(allspcldata);
            data.forEach(function(itemData) {
                if (item == itemData.title) {
                    professional_licensure[itemData.id] = item;
                }
            });
        });
        }
        list_professional_licensure();

        // list skills

        var skillsResult = result['skills'];
        if(skillsResult !== null){
        skillsResult = skillsResult.split(', ');
        
        skillsResult.forEach(function(item) {
            @php
                $allKeywordsJSON = json_encode($allKeywords['Speciality']);
            @endphp
            let allspcldata = {!! json_encode($allKeywordsJSON) !!};
            var data = JSON.parse(allspcldata);
            data.forEach(function(itemData) {
                if (item == itemData.title) {
                    skills[itemData.id] = item;
                }
            });
        });
        }
        list_skills();

        // list shift time of day

        var shifttimeofdayresult = result['preferred_shift_duration'];
        console.log("sift time of day : ",shifttimeofdayresult);
        // shifttimeofday is a string use trim to check if it is empty
        if (shifttimeofdayresult !== null) {
            console.log('triiiiiiimed');
        shifttimeofdayresult = shifttimeofdayresult.split(', ');
        
        shifttimeofdayresult.forEach(function(item) {
            @php
                $allKeywordsJSON = json_encode($allKeywords['PreferredShift']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            var data = JSON.parse(allspcldata);
            data.forEach(function(itemData) {
                if (item == itemData.title) {
                    shifttimeofday[itemData.id] = item;
                }
            });
        });
        }
        list_shifttimeofday();

        // list certifications

        var certificationsResult = result['certificate'];
        if (certificationsResult !== null) {
        certificationsResult = certificationsResult.split(', ');
        
        certificationsResult.forEach(function(item) {
            @php
                $allKeywordsJSON = json_encode($allKeywords['Certification']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            var data = JSON.parse(allspcldata);
            data.forEach(function(itemData) {
                if (item == itemData.title) {
                    certificate[itemData.id] = item;
                }
            });
        });
        }

        list_certifications();

        // list vaccinations

        var vaccinationsResult = result['vaccinations'];
        if (vaccinationsResult !== null) {
        vaccinationsResult = vaccinationsResult.split(', ');
        vaccinationsResult.forEach(function(item) {
            @php
                $allKeywordsJSON = json_encode($allKeywords['Vaccinations']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            var data = JSON.parse(allspcldata);
            data.forEach(function(itemData) {
                if (item == itemData.title) {
                    vaccinations[itemData.id] = item;
                }
            });
        });
        }

        list_vaccinations();




    },
    error: function(error) {
        console.log(error);
    }

            });
        } else {
            console.error('CSRF token not found.');
        }

    }
   
</script>



<style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

    .ss-job-prfle-sec:after {
        display: none !important;
    }
    .all {
        font-family: 'Neue Kabel';
        margin: 0;
        padding: 0;
        outline: none;

    }

    .bodyAll {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        overflow: hidden;
        width: 100%;
        margin: 0 auto;


    }

    ::selection {
        color: #fff;
        background: #b5649e;
    }

    .container {

        margin-top: 7%;
        margin-bottom: 7%;
        /* background: #fff; */
        text-align: center;
        /* border-radius: 5px; */
        padding: 50px 35px 10px 35px;
        /* shadow */
        border: 2px solid #3D2C39 !important;
        box-shadow: 10px 10px 0px 0px #3D2C39;
        border-radius: 12px;
    }

    .container header {
        font-size: 35px;
        font-weight: 500;
        margin: 0 0 50px 0;
    }

    .container .form-outer {
        width: 100%;
        overflow: hidden;
    }

    .container .form-outer form {
        display: flex;
        width: 500%;
    }

    .form-outer form .page {
        width: 20%;
        transition: margin-left 0.3s ease-in-out;
    }

    .form-outer form .page .title {
        text-align: left;
        font-size: 25px;
        font-weight: 500;
    }

    .form-outer form .page .field {

        height: 45px;
        margin: 45px 0;
        display: flex;
        position: relative;
    }

    form .page .field .label {
        position: absolute;
        top: -30px;
        font-weight: 500;
        display: block;
        color: #000;
        font-size: 16px;
        font-weight: 500;
    }

    form .page .field input {
        height: 100%;
        width: 100%;
        /* border: 1px solid lightgrey; */
        /* border-radius: 5px;
        padding-left: 15px;
        font-size: 18px; */


    }

    .ss-account-form-lft-1 .ss-form-group select {
        border: 2px solid #3D2C39 !important;
        width: 90%;
        padding: 10px !important;
        box-shadow: 10px 10px 0px 0px #3D2C39;
        border-radius: 12px;
        background: #fff !important;
    }

    .ss-account-form-lft-1 input,
    .ss-account-form-lft-1 select {
        border: 2px solid #3D2C39 !important;
        width: 90%;
        padding: 10px !important;
        box-shadow: 10px 10px 0px 0px #3D2C39;
        border-radius: 12px;
        background: #fff !important;
        margin-bottom: 2px !important;
    }

    textarea {
        border: 2px solid #3D2C39 !important;
        width: 90%;
        padding: 10px !important;
        box-shadow: 10px 10px 0px 0px #3D2C39;
        border-radius: 12px;
        background: #fff !important;
    }


    form .page .field select {
        width: 100%;
        padding-left: 10px;
        font-size: 17px;
        font-weight: 500;
    }

    form .page .field button {
        width: fit-content;
        height: calc(100% + 5px);
        border: none;
        /* background: #d33f8d; */
        margin-top: -20px;
        /* border-radius: 5px; */
        color: #fff;
        cursor: pointer;
        font-size: 18px;
        font-weight: 500;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: 0.5s ease;

        background: #3D2C39;
        color: #fff;
        padding: 10px;
        border-radius: 100px;

    }

    form .page .field button:hover {
        background: #000;
    }

    form .page .btns button {
        margin-top: -20px !important;
    }

    form .page .btns button.prev {
        margin-right: 3px;
        font-size: 17px;
    }

    form .page .btns button.next {
        margin-left: 3px;
    }

    .container .progress-bar-item {
        display: flex;
        margin: 40px 0;
        user-select: none;
    }

    .container .progress-bar-item .step {
        text-align: center;
        position: relative;
        display: flex;

        flex-direction: column;

        align-items: center;
        justify-content: space-between;
    }

    .container .progress-bar-item .step p {
        font-weight: 500;
        font-size: 18px;
        color: #000;
        margin-bottom: 8px;
    }

    .progress-bar-item .step .bullet {
        height: 25px;
        width: 25px;
        border: 2px solid #000;
        display: inline-block;
        border-radius: 50%;
        position: relative;
        transition: 0.2s;
        font-weight: 500;
        font-size: 17px;
        line-height: 25px;
    }

    .progress-bar-item .step .bullet.active {
        border-color: #b5649e;
        background: #b5649e;
    }

    .progress-bar-item .step .bullet span {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }

    .progress-bar-item .step .bullet.active span {
        display: none;
    }

    /* transition progress line */

    /* .progress-bar-item .step .bullet:before,
    .progress-bar-item .step .bullet:after {
        position: absolute;
        content: '';
        bottom: 11px;

        height: 3px;
        width: 1000%;
        background: #262626;
    } */

    .progress-bar-item .step .bullet.active:after {
        background: #b5649e;
        transform: scaleX(0);
        transform-origin: left;
        animation: animate 0.3s linear forwards;
    }

    @keyframes animate {
        100% {
            transform: scaleX(1);
        }
    }

    .progress-bar-item .step:last-child .bullet:before,
    .progress-bar-item .step:last-child .bullet:after {
        display: none;
    }

    .progress-bar-item .step p.active {
        color: #b5649e !important;
        transition: 0.2s linear;
    }

    .progress-bar-item .step .check {
        position: absolute;
        left: 50%;
        top: 70%;
        font-size: 15px;
        transform: translate(-50%, -50%);
        display: none;
    }

    .progress-bar-item .step .check.active {
        display: block;
        color: #fff;
    }

    .saveDrftBtn {
        border: 1px solid #3D2C39 !important;
        color: #3D2C39 !important;
        border-radius: 100px;
        padding: 10px;
        text-align: center;
        width: 100%;
        margin-top: 25px;
        background: transparent !important;
        margin-right: 6px;

    }

    label {
        display: block;
        color: #000;
        font-size: 16px;
        font-weight: 500;
    }

    .saveDrftBtnEdit {
        margin-right: 3px;
    }

    .saveDrftBtnDraft {
        margin-right: 3px;
    }

    #assign-container{
        display: flex;
        justify-content: center;
        align-items: baseline;
    }

    .ss-form-group {
        display: grid;
    }

    .ss-form-group span {
        margin-top: 10px;
    }
</style>
@endsection
