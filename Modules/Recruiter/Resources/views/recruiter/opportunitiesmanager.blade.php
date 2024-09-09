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
                                <li ><button id="onhold" onclick="opportunitiesType('onhold')"
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
                            <header>Create Work Request</header>
                            <div class="row progress-bar-item">
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
                                                <label> Work Name</label>
                                                <input type="text" name="job_name" id="job_name"
                                                    placeholder="Enter Work name">
                                                <span class="help-block-job_name"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Work Type</label>
                                                <select name="job_type" id="job_type">
                                                    <option value="" disabled selected hidden>Select a Work type</option>
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
                                                    <option value="" disabled selected hidden>Select a specialty</option>
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
                                                <select name="proffesion" id="perferred_profession">
                                                    <option value="" disabled selected hidden>Select a Profession</option>
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


                                                <!-- <input type="text" name="job_state" id="job_state"
                                                        placeholder="Enter Work Location (State)">
                                                    <span class="help-block-job_state"></span> -->
                                            </div>

                                            <div class="ss-form-group col-md-4">

                                                <!-- <input type="text" name="job_city" id="job_city"
                                                        placeholder="Enter Work Location (City)"> -->
                                                <label> Work City </label>
                                                <select name="job_city" id="job_city">
                                                    <option value="">Select a city</option>
                                                </select>

                                                <span class="help-block-job_city"></span>
                                            </div>


                                            <div class="ss-form-group col-md-4">
                                                <label>Preferred Work Location</label>
                                                <input type="text" name="preferred_work_location"
                                                    id="preferred_work_location" placeholder="Enter Work Location">
                                                <span style="color:#b5649e;" id="passwordHelpInline" class="form-text">
                                                    (Location not required)
                                                </span>
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
                                                <label>Work Description</label>
                                                <textarea type="text" name="description" id="description" placeholder="Enter Work Description"></textarea>
                                                <span style="color:#b5649e;" id="passwordHelpInline" class="form-text">
                                                    (Description not required)
                                                </span>
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
                                                                @if(isset($allKeywords['PreferredShift']))
                                                                @foreach ($allKeywords['PreferredShift'] as $value)
                                                                <option value="{{$value->id}}">{{$value->title}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="shifttimeofdayAllValues" name="preferred_shift_duration" >
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addshifttimeofday('from_add')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
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
                                                <label>Shift Cancellation Policy</label>
                                                <input type="text" name="facility_shift_cancelation_policy" id="facility_shift_cancelation_policy"
                                                placeholder="Select your shift cancellation policy">
                                                <span class="help-block-facility_shift_cancelation_policy"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Min distance from facility</label>
                                                <input type="number" name="traveler_distance_from_facility"
                                                    id="traveler_distance_from_facility"
                                                    placeholder="Enter travel distance">
                                                <span class="help-block-traveler_distance_from_facility"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Clinical Setting</label>
                                                <select name="clinical_setting"
                                                    id="clinical_setting">
                                                    <option value="" disabled selected hidden>Select a setting</option>
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
                                            <span style="color:#b5649e;" id="passwordHelpInline" class="form-text">
                                                ( The above fields are not required )
                                            </span>
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
                                                <label>Est. Taxable Hourly rate</label>
                                                <input type="number" name="actual_hourly_rate" id="actual_hourly_rate"
                                                    placeholder="Enter Taxable Regular Hourly rate">
                                                <span class="help-block-actual_hourly_rate"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Overtime Hourly rate</label>
                                                <input type="number" name="overtime" id="overtime"
                                                    placeholder="Enter actual Overtime Hourly rate">
                                                <span class="help-block-overtime"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>On Call</label>
                                                <select name="on_call" id="on_call">
                                                    <option value="" disabled selected hidden>Select an answer</option>
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
                                                    <option value="" disabled selected hidden>Select an answer</option>
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
                                                    <option value="" disabled selected hidden>Select an answer</option>
                                                    <option value="Yes">Yes
                                                    </option>
                                                    <option value="No">No
                                                    </option>
                                                </select>
                                                <span class="help-block-float_requirement"></span>
                                            </div>


                                            <span style="color:#b5649e;" id="passwordHelpInline" class="form-text">
                                                ( The above fields are not required )
                                            </span>

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

                                            <div class="ss-form-group col-md-4">
                                                <label>Number Of References</label>
                                                <input type="number" name="number_of_references" id="number_of_references"
                                                    placeholder="Enter number of references">
                                                <span class="help-block-number_of_references"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Eligible work in us ?</label>
                                                <select name="eligible_work_in_us" id="eligible_work_in_us">
                                                    <option value="" disabled selected hidden>Select an answer</option>
                                                    <option value="1">Yes
                                                    </option>
                                                    <option value="0">No
                                                    </option>
                                                </select>
                                                <span class="help-block-eligible_work_in_us"></span>
                                            </div>

                                            <div class="row ss-form-group col-md-4 d-flex justify-content-end">
                                                <label>Urgency</label>
                                                <div class="row" style="display:flex; align-items:end;">
                                                    <label class="col-6" for="urgency" style="display:flex; justify-content:end;">Auto Offer</label>
                                                    <div class="col-6">
                                                        <input type="checkbox" name="urgency" id="urgency" value="Auto Offer" style="box-shadow: none;">
                                                    </div>
                                                </div>
                                                <span class="help-block-urgency"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Facility's Parent System</label>
                                                <input type="text" name="facilitys_parent_system" id="facilitys_parent_system"
                                                    placeholder="Enter facility's parent system">
                                                <span class="help-block-facilitys_parent_system"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Facility Name</label>
                                                <input type="text" name="facility_name" id="facility_name"
                                                    placeholder="Enter facility name">
                                                <span class="help-block-facility_name"></span>
                                            </div>
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



                                            <div class="ss-form-group col-md-4">
                                                <label>Pay Frequency</label>
                                                <select name="pay_frequency" id="pay_frequency">
                                                    <option value="" disabled selected hidden>Select a pay frequency</option>
                                                    @foreach ($allKeywords['PayFrequency'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-pay_frequency"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Preferred Experience</label>
                                                <input type="number" name="preferred_experience" id="preferred_experience"
                                                    placeholder="Enter Preferred Experience">
                                                    <span class="help-block-preferred_experience"></span>
                                            </div>

                                            <div class="row ss-form-group ss-prsnl-frm-specialty d-flex justify-content-end col-md-4">
                                                <label>Professional State Licensure</label>
                                                <div class="ss-speilty-exprnc-add-list professional_state_licensure-content" style="display: flex;
                                                justify-content: space-around;" >
                                                    {{-- Radio option for "Accept Pending" --}}
                                                    <div>
                                                        <input type="radio" id="professional_state_licensure_pending" name="professional_state_licensure" value="Accept Pending" style="box-shadow: none;">
                                                        <label for="professional_state_licensure_pending">Accept Pending</label>
                                                    </div>
                                                    {{-- Radio option for "Active" --}}
                                                    <div>
                                                        <input type="radio" id="professional_state_licensure_active" name="professional_state_licensure" value="Active" style="box-shadow: none;">
                                                        <label for="professional_state_licensure_active">Active</label>
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

                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Worker Classification</label>
                                                <div class="ss-speilty-exprnc-add-list nurse_classification-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="nurse_classification">
                                                                <option value="" disabled selected hidden>Select a Worker Classification</option>
                                                                @if(isset($allKeywords['NurseClassification']))
                                                                @foreach ($allKeywords['NurseClassification'] as $value)
                                                                <option value="{{$value->id}}">{{$value->title}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="nurse_classificationAllValues" name="nurse_classification" >
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addnurse_classification('from_add')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                            </div>

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
                                                <label>Professional Licensure</label>
                                                <div class="ss-speilty-exprnc-add-list professional_licensure-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="professional_licensure">
                                                                <option value="" disabled selected hidden>Select a professional Licensure</option>
                                                                @if(isset($allKeywords['StateCode']))
                                                                @foreach ($allKeywords['StateCode'] as $value)
                                                                <option value="{{$value->id}}">{{$value->title}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="professional_licensureAllValues" name="job_location" >
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addprofessional_licensure('from_add')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div id="benefits_id" class="d-none ss-form-group ss-prsnl-frm-specialty">
                                                <label>Benefits</label>
                                                <div class="ss-speilty-exprnc-add-list benefits-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="benefits">
                                                                <option value="" disabled selected hidden>Select a benefits</option>
                                                                @if(isset($allKeywords['Benefits']))
                                                                @foreach ($allKeywords['Benefits'] as $value)
                                                                <option value="{{$value->id}}">{{$value->title}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="benefitsAllValues" name="benefits" >
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addbenefits('from_add')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                            </div>



                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Certifications</label>
                                                <div class="ss-speilty-exprnc-add-list certificate-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="certificate">
                                                                <option value="" disabled selected hidden>Select Certification</option>
                                                                @if(isset($allKeywords['Certification']))
                                                                @foreach ($allKeywords['Certification'] as $value)
                                                                <option value="{{$value->id}}">{{$value->title}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="certificateAllValues" name="certificate" >
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addcertifications('from_add')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Vaccinations & Immunizations name</label>
                                                <div class="ss-speilty-exprnc-add-list vaccinations-content">

                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="vaccinations">
                                                                <option value="" disabled selected hidden>Enter Vaccinations & Immunizations name</option>
                                                                @if(isset($allKeywords['Vaccinations']))
                                                                    @foreach ($allKeywords['Vaccinations'] as $value)
                                                                        <option value="{{$value->id}}">{{$value->title}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="vaccinationsAllValues" name="vaccinations" >
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" id="from_add" onclick="addvacc('from_add')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Skills checklist</label>
                                                <div class="ss-speilty-exprnc-add-list skills-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="skills">
                                                                <option value="" disabled selected hidden>Select Skills</option>
                                                                @if(isset($allKeywords['Speciality']))
                                                                @foreach ($allKeywords['Speciality'] as $value)
                                                                <option value="{{$value->title}}">{{$value->title}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="skillsAllValues" name="skills" >
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addskills('from_add')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                            </div>



                                            <div class="field btns col-12 d-flex justify-content-center">
                                                <button class="saveDrftBtn">Save as draft</button>
                                                <button class="prev-3 prev">Previous</button>
                                                <button class="next-3 next">Next</button>

                                            </div>

                                        </div>
                                    </div>

                                    {{-- end slide added from sheets --}}


                                    <!-- Forth form slide for adding jobs -->

                                    <div class="page">
                                        <div class="row">


                                            <div class="ss-form-group col-md-4">
                                                <label>Contract Termination Policy</label>
                                                <input type="text" name="contract_termination_policy" id="contract_termination_policy"
                                                placeholder="Enter your contract termination policy">
                                                <span class="help-block-contract_termination_policy"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>401K</label>
                                                <select name="four_zero_one_k" id="four_zero_one_k">
                                                    <option value="" disabled selected hidden>Select an answer</option>
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
                                                    <option value="" disabled selected hidden>Select a Health Insurance</option>
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
                                                <label>On Call Back</label>
                                                <select name="on_call_back" id="on_call_back">
                                                    <option value="" disabled selected hidden>Select an option</option>
                                                    <option value="Yes">Yes
                                                    </option>
                                                    <option value="No">No
                                                    </option>
                                                </select>
                                                <span class="help-block-on_call_back"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Call Back Hourly rate</label>
                                                <input type="number" name="call_back_rate" id="call_back_rate"
                                                    placeholder="Enter Call Back Hourly rate">
                                                <span class="help-block-call_back_rate"></span>
                                                <span class="help-block-call_back_rate"></span>
                                            </div>


                                            <div class="ss-form-group col-md-4">
                                                <label>Est. Weekly non-taxable amount</label>
                                                <input type="number" name="weekly_non_taxable_amount"
                                                    id="weekly_non_taxable_amount"
                                                    placeholder="Enter Weekly non-taxable amount">
                                                <span class="help-block-weekly_non_taxable_amount"></span>
                                            </div>

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

                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>EMR</label>
                                                <div class="ss-speilty-exprnc-add-list Emr-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="Emr">
                                                                <option value="" disabled selected hidden>Select an emr</option>
                                                                @if(isset($allKeywords['EMR']))
                                                                @foreach ($allKeywords['EMR'] as $value)
                                                                <option value="{{$value->id}}">{{$value->title}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="EmrAllValues" name="Emr" >
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addEmr('from_add')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="ss-form-group col-md-12">
                                                <div class="row">
                                                    <div class="col-lg-6 col-sm-12 col-md-12 col-xs-12">
                                                        <label>Start Date</label>
                                                    </div>
                                                    <div class="row col-lg-6 col-sm-12 col-md-12 col-xs-12"
                                                        style="display: flex; justify-content: end;">
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
                                            {{-- <div class="ss-form-group col-md-4">
                                            <input type="text" name="responsibilities" id="responsibilities"
                                                placeholder="Enter Responsibilities">
                                        </div>

                                        <div class="ss-form-group col-md-4">
                                            <input type="text" name="qualifications" id="qualifications"
                                                placeholder="Enter Qualifications">
                                        </div> --}}
                                            <span style="color:#b5649e;" id="passwordHelpInline" class="form-text">
                                                ( The above fields are not required )
                                            </span>
                                            <div class="field btns col-12 d-flex justify-content-center">
                                                <button class="saveDrftBtn">Save as draft</button>
                                                <button class="prev-4 prev">Previous</button>
                                                <button class="submit">Submit</button>
                                            </div>
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
                                    <div style="" class="col-12 ss-job-prfle-sec" onclick="editDataJob(this)"
                                        id="{{ $counter }}">
                                        <h4>{{ $job->proffesion }} - {{ $job->preferred_specialty }}</h4>
                                        <h6>{{ $job->job_name}}</h6>
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
                                    <div class="col-12 ss-job-prfle-sec"
                                        onclick="opportunitiesType('published','{{ $value->id }}','jobdetails')"
                                        id="{{ $counter }}">
                                        <p>Travel <span> {{ $applyCount[$key] }} Applied</span></p>
                                        <h4>{{ $value->proffesion }} - {{ $value->preferred_specialty }}</h4>
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

                                    <div class="col-12 ss-job-prfle-sec" onclick="opportunitiesType('onhold','{{ $job->id }}','jobdetails')"
                                        id="{{ $counter }}">
                                        <h4>{{ $job->proffesion }} - {{ $job->preferred_specialty }}</h4>
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
                                <div class="ss-account-form-lft-1" style="width: 100%; margin-top: 0px;" id="details_info">
                                    <header>Select a Work from Drafts</header>
                                    <div class="row progress-bar-item">
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
                                        <form method="post" action="{{ route('addJob.store') }}">
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
                                                        <label> Work Name</label>
                                                        <input type="text" name="job_name" id="job_nameDraft"
                                                            placeholder="Enter Work name">
                                                        <span class="help-block-job_name"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Work Type</label>

                                                        <select name="job_type" id="job_typeDraft">

                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="Clinical">Clinical
                                                            </option>
                                                            <option value="Non-Clinical">Non-Clinical
                                                            </option>
                                                        </select>
                                                        <span class="help-block-job_type"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>Preferred Specialty</label>
                                                        <select name="preferred_specialty" id="preferred_specialtyDraft">
                                                            <option value="" disabled selected hidden>Select an option</option>
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
                                                        <select name="proffesion" id="perferred_professionDraft">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            @foreach ($proffesions as $proffesion)
                                                                <option value="{{ $proffesion->full_name }}">
                                                                    {{ $proffesion->full_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-perferred_profession"></span>
                                                    </div>



                                                    <div class="ss-form-group col-md-4">
                                                        <label> Work State </label>
                                                        <select name="job_state" id="job_stateDraft">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            @foreach ($states as $state)
                                                                <option id="{{ $state->id }}"
                                                                    value="{{ $state->name }}">
                                                                    {{ $state->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-job_stateDraft"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">

                                                        <label> Work City </label>
                                                        <select name="job_city" id="job_cityDraft">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                        </select>

                                                        <span class="help-block-job_city"></span>
                                                    </div>


                                                    <div class="ss-form-group col-md-4">
                                                        <label>Preferred Work Location</label>
                                                        <input type="text" name="preferred_work_location"
                                                            id="preferred_work_locationDraft"
                                                            placeholder="Enter Work Location">
                                                        <span style="color:#b5649e;" id="passwordHelpInline"
                                                            class="form-text">
                                                            (Location not required)
                                                        </span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Weeks per Assignment</label>
                                                        <input type="number" name="preferred_assignment_duration"
                                                            id="preferred_assignment_durationDraft"
                                                            placeholder="Enter Work Duration Per Assignment">
                                                        <span class="help-block-preferred_assignment_duration"></span>

                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Est. Weekly Pay </label>
                                                        <input type="number" step="0.01" name="weekly_pay"
                                                            id="weekly_payDraft" placeholder="Enter Weekly Pay">
                                                        <span class="help-block-weekly_pay"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>Terms</label>
                                                        <select name="terms" id="termsDraft">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            @foreach ($allKeywords['Terms'] as $value)
                                                                <option value="{{ $value->title }}">{{ $value->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-terms"></span>
                                                    </div>


                                                    <div class="ss-form-group col-md-4">
                                                        <label>Work Description</label>
                                                        <textarea type="text" name="description" id="descriptionDraft" placeholder="Enter Work Description"></textarea>
                                                        <span style="color:#b5649e;" id="passwordHelpInline"
                                                            class="form-text">
                                                            (Description not required)
                                                        </span>
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
                                                                        @if(isset($allKeywords['PreferredShift']))
                                                                        @foreach ($allKeywords['PreferredShift'] as $value)
                                                                        <option value="{{$value->id}}">{{$value->title}}</option>
                                                                        @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="shifttimeofdayAllValuesDraft" name="preferred_shift_duration" >
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addshifttimeofday('from_draft')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnDraft">Save as draft</button>
                                                        <button class="firstNextDraft next">Next</button>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- edits end --}}

                                            {{-- second edited --}}
                                            <!-- Second form slide required inputs for adding jobs -->
                                            <div class="page">
                                                <div class="row">
                                                    {{-- edits --}}
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Shift Cancellation Policy</label>
                                                        <input type="text" name="facility_shift_cancelation_policy" id="facility_shift_cancelation_policyDraft"
                                                        placeholder="Select your shift cancellation policy">
                                                        <span class="help-block-facility_shift_cancelation_policy"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Min distance from facility</label>
                                                        <input type="number" name="traveler_distance_from_facility"
                                                            id="traveler_distance_from_facilityDraft"
                                                            placeholder="Enter travel distance">
                                                        <span class="help-block-traveler_distance_from_facility"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Clinical Setting</label>
                                                        <select name="clinical_setting"
                                                    id="clinical_settingDraft">
                                                    <option value="" disabled selected hidden>Select an option</option>
                                                    @foreach ($allKeywords['ClinicalSetting'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                        <span class="help-block-clinical_setting"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Patient ratio</label>
                                                        <input type="number" name="Patient_ratio"
                                                            id="Patient_ratioDraft" placeholder="Enter Patient ratio">
                                                        <span class="help-block-Patient_ratio"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Unit</label>
                                                        <input type="text" name="Unit" id="UnitDraft"
                                                            placeholder="Enter Unit">
                                                        <span class="help-block-Unit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Scrub Color</label>
                                                        <input type="text" name="scrub_color" id="scrub_colorDraft"
                                                            placeholder="Enter scrub color">
                                                        <span class="help-block-scrub_color"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Rto</label>
                                                        <select name="rto" id="rtoDraft">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="allowed">Allowed
                                                            </option>
                                                            <option value="not allowed">Not Allowed
                                                            </option>
                                                        </select>
                                                <span class="help-block-rto"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Guaranteed Hours per week</label>
                                                        <input type="number" name="guaranteed_hours"
                                                            id="guaranteed_hoursDraft"
                                                            placeholder="Enter Guaranteed Hours per week">
                                                        <span class="help-block-guaranteed_hours"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>Hours Per Week</label>
                                                        <input type="number" name="hours_per_week" id="hours_per_weekDraft"
                                                            placeholder="Enter hours per week">
                                                        <span class="help-block-hours_per_weekDraft"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>
                                                            Hours Per Shift
                                                        </label>
                                                        <input type="number" name="hours_shift" id="hours_shiftDraft"
                                                            placeholder="Enter Hours Per Shift">
                                                        <span class="help-block-hours_shift"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Shift Per Weeks</label>
                                                        <input type="number" name="weeks_shift" id="weeks_shiftDraft"
                                                            placeholder="Enter Shift Per Weeks">
                                                        <span class="help-block-weeks_shift"></span>
                                                    </div>




                                                    <span style="color:#b5649e;" id="passwordHelpInline"
                                                        class="form-text">
                                                        ( The above fields are not required )
                                                    </span>
                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnDraft">Save as draft</button>
                                                        <button class="prev-1Draft prev">Previous</button>
                                                        <button class="next-1Draft next">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end second edited --}}
                                            {{-- third slide draft --}}
                                            <div class="page">
                                                <div class="row">
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Referral Bonus</label>
                                                        <input type="number" name="referral_bonus"
                                                            id="referral_bonusDraft" placeholder="Enter referral bonus">
                                                        <span class="help-block-referral_bonus"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Sign on Bonus</label>
                                                        <input type="number" name="sign_on_bonus"
                                                            id="sign_on_bonusDraft" placeholder="Enter sign on bonus">
                                                        <span class="help-block-sign_on_bonus"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Completion Bonus</label>
                                                        <input type="number" name="completion_bonus"
                                                            id="completion_bonusDraft"
                                                            placeholder="Enter completion bonus">
                                                        <span class="help-block-completion_bonus"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Extension Bonus</label>

                                                        <input type="number" name="extension_bonus"
                                                            id="extension_bonusDraft" placeholder="Enter extension bonus">
                                                        <span class="help-block-extension_bonus"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Other bonus</label>
                                                        <input type="number" name="other_bonus" id="other_bonusDraft"
                                                            placeholder="Enter other bonus">
                                                        <span class="help-block-other_bonus"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Est. Texable Hourly Rate</label>
                                                        <input type="number" name="actual_hourly_rate"
                                                            id="actual_hourly_rateDraft"
                                                            placeholder="Enter taxable regular hourly rate">
                                                        <span class="help-block-actual_hourly_rate"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>Overtime Hourly rate</label>
                                                        <input type="number" name="overtime" id="overtimeDraft"
                                                            placeholder="Enter actual Overtime Hourly rate">
                                                        <span class="help-block-overtime"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>On Call</label>
                                                        <select name="on_call" id="on_callDraft">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-on_call"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>On Call Rate</label>
                                                        <input type="number" name="on_call_rate" id="on_call_rateDraft"
                                                        placeholder="Enter a call back hourly rate">
                                                        <span class="help-block-on_call_rate"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Holidy date</label>
                                                        <input type="date" name="holiday" id="holidayDraft"
                                                            placeholder="Enter Holidy hourly rate">
                                                        <span class="help-block-holiday"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Orientation Hourly rate</label>
                                                        <input type="number" name="orientation_rate"
                                                            id="orientation_rateDraft"
                                                            placeholder="Enter Orientation Hourly rate">
                                                        <span class="help-block-orientation_rate"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>Block scheduling</label>
                                                        <select name="block_scheduling" id="block_schedulingDraft">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-block_scheduling"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Float requirements</label>
                                                        <select name="float_requirement" id="float_requirementDraft">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-float_requirement"></span>
                                                    </div>


                                                    <span style="color:#b5649e;" id="passwordHelpInline"
                                                        class="form-text">
                                                        ( The above fields are not required )
                                                    </span>

                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnDraft">Save as draft</button>
                                                        <button class="prev-2Draft prev">Previous</button>
                                                        <button class="next-2Draft next">Next</button>
                                                    </div>

                                                </div>
                                            </div>
                                            {{-- end third slide draft --}}

                                            {{-- slide added from sheets --}}

                                    <div class="page">
                                        <div class="row">
                                            <div class="ss-form-group col-md-4">
                                                <label>Professional Licensure</label>
                                                <select name="job_location" id="job_locationDraft">
                                                    <option value="" disabled selected hidden>Select an option</option>
                                                    @foreach ($allKeywords['StateCode'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }} Compact
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-job_locationDraft"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Number Of References</label>
                                                <input type="number" name="number_of_references" id="number_of_referencesDraft"
                                                    placeholder="Enter number of references">
                                                <span class="help-block-number_of_referencesDraft"></span>
                                            </div>
                                            {{-- <div class="ss-form-group col-md-4">
                                                <label>Min Title Of Reference</label>
                                                <input type="text" name="min_title_of_reference" id="min_title_of_referenceDraft"
                                                    placeholder="Enter min title of reference">
                                                <span class="help-block-min_title_of_referenceDraft"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Recency Of Reference</label>
                                                <input type="number" name="recency_of_reference" id="recency_of_referenceDraft"
                                                    placeholder="Enter # recency of reference">
                                                <span class="help-block-recency_of_referenceDraft"></span>
                                            </div> --}}
                                            <div class="ss-form-group col-md-4">
                                                <label>Eligible work in us ?</label>
                                                <select name="eligible_work_in_us" id="eligible_work_in_usDraft">
                                                    <option value="" disabled selected hidden>Select an option</option>
                                                    <option value="1">Yes
                                                    </option>
                                                    <option value="0">No
                                                    </option>
                                                </select>
                                                <span class="help-block-eligible_work_in_usDraft"></span>
                                            </div>



                                            <div class="ss-form-group col-md-4">
                                                <label>Urgency</label>
                                                <div>
                                                    <input type="checkbox" name="urgency" id="urgencyDraft" value="Auto Offer" >
                                                    <label for="urgency">Auto Offer</label>
                                                </div>
                                                <span class="help-block-urgencyDraft"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Facility's Parent System</label>
                                                <input type="text" name="facilitys_parent_system" id="facilitys_parent_systemDraft"
                                                    placeholder="Enter facility's parent system">
                                                <span class="help-block-facilitys_parent_systemDraft"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Facility Name</label>
                                                <input type="text" name="facility_name" id="facility_nameDraft"
                                                    placeholder="Enter facility name">
                                                <span class="help-block-facility_nameDraft"></span>
                                            </div>


                                            <div class="ss-form-group col-md-4">
                                                <label>Worker Classification</label>
                                                <select name="nurse_classification" id="nurse_classificationDraft">
                                                    <option value="" disabled selected hidden>Select an option</option>
                                                    @foreach ($allKeywords['NurseClassification'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-nurse_classificationDraft"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Pay Frequency</label>
                                                <select name="pay_frequency" id="pay_frequencyDraft">
                                                    <option value="" disabled selected hidden>Select an option</option>
                                                    @foreach ($allKeywords['PayFrequency'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-pay_frequencyDraft"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Benefits</label>
                                                <select name="benefits" id="benefitsDraft">
                                                    <option value="" disabled selected hidden>Select an option</option>
                                                    @foreach ($allKeywords['Benefits'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-benefitsDraft"></span>
                                            </div>





                                            <div class="ss-form-group col-md-4">
                                                <label>Preferred Experience</label>
                                                <input type="number" name="preferred_experience" id="preferred_experienceDraft"
                                                    placeholder="Enter Preferred Experience">
                                                    <span class="help-block-preferred_experienceDraft"></span>
                                            </div>

                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Certifications</label>
                                                <div class="ss-speilty-exprnc-add-list certificate-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select  class="m-0" id="certificateDraft">
                                                                <option value="">Select Certification</option>
                                                                @if(isset($allKeywords['Certification']))
                                                                @foreach ($allKeywords['Certification'] as $value)
                                                                <option value="{{$value->id}}">{{$value->title}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="certificateAllValuesDraft" name="certificate" >
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addcertifications('from_draft')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Vaccinations & Immunizations name</label>
                                                <div class="ss-speilty-exprnc-add-list vaccinations-content">

                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="vaccinationsDraft">
                                                                <option value="" disabled selected hidden>Enter Vaccinations & Immunizations name</option>
                                                                @if(isset($allKeywords['Vaccinations']))
                                                                    @foreach ($allKeywords['Vaccinations'] as $value)
                                                                        <option value="{{$value->id}}">{{$value->title}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="vaccinationsAllValuesDraft" name="vaccinations" >
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"  onclick="addvacc('from_draft')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                            </div>


                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Skills checklist</label>
                                                <div class="ss-speilty-exprnc-add-list skills-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="skillsDraft">
                                                                <option value="" disabled selected hidden>Select Skills</option>
                                                                @if(isset($allKeywords['Speciality']))
                                                                @foreach ($allKeywords['Speciality'] as $value)
                                                                <option value="{{$value->title}}">{{$value->title}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="skillsAllValuesDraft" name="skills" >
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addskills('from_draft')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                            </div>





                                            <div class="field btns col-12 d-flex justify-content-center">
                                                <button class="saveDrftBtnDraft">Save as draft</button>
                                                <button class="prev-3Draft prev">Previous</button>
                                                <button class="next-3Draft next">Next</button>

                                            </div>

                                        </div>
                                    </div>

                                    {{-- end slide added from sheets --}}

                                            <!-- Forth form slide for adding jobs -->
                                            {{-- edits forth --}}
                                            <div class="page">
                                                <div class="row">
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Contract Termination Policy</label>
                                                        <input type="text" name="contract_termination_policy" id="contract_termination_policyDraft"
                                                        placeholder="Enter your contract termination policy">
                                                        <span class="help-block-contract_termination_policy"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>EMR</label>
                                                        <select name="Emr"
                                                    id="emrDraft">
                                                    <option value="" disabled selected hidden>Select an option</option>
                                                    @foreach ($allKeywords['EMR'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                        <span class="help-block-emr"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>401K</label>
                                                        <select name="four_zero_one_k" id="four_zero_one_kDraft">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-four_zero_one_k"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Health Insurance</label>
                                                        <select name="health_insaurance" id="health_insauranceDraft">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-health_insaurance"></span>
                                                    </div>


                                                    <div class="ss-form-group col-md-4">
                                                        <label>Feels Like $/hrs</label>
                                                        <input type="number" name="feels_like_per_hour" id="feels_like_per_hourDraft"
                                                            placeholder="Enter Feels Like $/hrs">
                                                        <span class="help-block-feels_like_per_hourDraft"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>On Call Back</label>
                                                        <select name="on_call_back" id="on_call_backDraft">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-on_call_back"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>Call Back Hourly rate</label>
                                                        <input type="number" name="call_back_rate" id="call_back_rateDraft"
                                                            placeholder="Enter Call Back Hourly rate">
                                                        <span class="help-block-call_back_rate"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>Est. Weekly non-taxable amount</label>
                                                        <input type="number" name="weekly_non_taxable_amount"
                                                            id="weekly_non_taxable_amountDraft"
                                                            placeholder="Enter Weekly non-taxable amount">
                                                        <span class="help-block-weekly_non_taxable_amount"></span>
                                                    </div>



                                                    <div class="ss-form-group col-md-12">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-sm-12 col-md-12 col-xs-12">
                                                                <label>Start Date</label>
                                                            </div>
                                                            <div class="row col-lg-6 col-sm-12 col-md-12 col-xs-12"
                                                                style="display: flex; justify-content: end;">
                                                                <input id="as_soon_asDraft" name="as_soon_as"
                                                                    value="1" type="checkbox"
                                                                    style="box-shadow:none; width:auto;" class="col-6">
                                                                <label class="col-6">
                                                                    As soon As possible
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <input id="start_dateDraft" type="date" min="2024-03-06"
                                                            name="start_date" placeholder="Select Date"
                                                            value="2024-03-06">
                                                    </div>
                                                    <span class="help-block-start_date"></span>

                                                    <span style="color:#b5649e;" id="passwordHelpInline"
                                                        class="form-text">
                                                        ( The above fields are not required )
                                                    </span>
                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnDraft">Save as draft</button>
                                                        <button class="prev-4Draft prev">Previous</button>
                                                        <button class="submitDraft">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end edits forth --}}

                                        </form>
                                    </div>
                                </div>

                                <div id="job-details">
                                </div>
                            </div>
                        </div>
                        <!-- END EDITING Draft FORM -->



                        <!-- EDIT Work -->
                        <div class="all col-lg-7" id="details_edit_job">
                            <div class="bodyAll" style="width: 100%;">
                                <div class="ss-account-form-lft-1" style="width: 100%; margin-top: 0px;">
                                    <header>Edit your selected job</header>
                                    <div class="row progress-bar-item">
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
                                        <form method="post" action="{{ route('edit_job') }}">
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

                                                    <div class="ss-form-group col-md-4">
                                                        <label> Work Name</label>
                                                        <input type="text" name="job_name" id="job_nameEdit"
                                                            placeholder="Enter Work name">
                                                        <span class="help-block-job_name"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Work Type</label>

                                                        <select name="job_type" id="job_typeEdit">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="Clinical">Clinical
                                                            </option>
                                                            <option value="Non-Clinical">Non-Clinical
                                                            </option>
                                                        </select>
                                                        <span class="help-block-job_type"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>Preferred Specialty</label>
                                                        <select name="preferred_specialty" id="preferred_specialtyEdit">
                                                            <option value="" disabled selected hidden>Select an option</option>
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
                                                        <select name="proffesion" id="perferred_professionEdit">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            @foreach ($proffesions as $proffesion)
                                                                <option value="{{ $proffesion->full_name }}">
                                                                    {{ $proffesion->full_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-perferred_profession"></span>
                                                    </div>



                                                    <div class="ss-form-group col-md-4">
                                                        <label> Work State </label>
                                                        <select name="job_state" id="job_stateEdit">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            @foreach ($states as $state)
                                                                <option id="{{ $state->id }}"
                                                                    value="{{ $state->name }}">
                                                                    {{ $state->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-job_stateEdit"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">

                                                        <label> Work City </label>
                                                        <select name="job_city" id="job_cityEdit">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                        </select>

                                                        <span class="help-block-job_city"></span>
                                                    </div>


                                                    <div class="ss-form-group col-md-4">
                                                        <label>Preferred Work Location</label>
                                                        <input type="text" name="preferred_work_location"
                                                            id="preferred_work_locationEdit"
                                                            placeholder="Enter Work Location">
                                                        <span style="color:#b5649e;" id="passwordHelpInline"
                                                            class="form-text">
                                                            (Location not required)
                                                        </span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Weeks per Assignment</label>
                                                        <input type="number" name="preferred_assignment_duration"
                                                            id="preferred_assignment_durationEdit"
                                                            placeholder="Enter Work Duration Per Assignment">
                                                        <span class="help-block-preferred_assignment_duration"></span>

                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Est. Weekly Pay </label>
                                                        <input type="number" step="0.01" name="weekly_pay"
                                                            id="weekly_payEdit" placeholder="Enter Weekly Pay">
                                                        <span class="help-block-weekly_pay"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>Terms</label>
                                                        <select name="terms" id="termsEdit">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            @foreach ($allKeywords['Terms'] as $value)
                                                                <option value="{{ $value->title }}">{{ $value->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block-terms"></span>
                                                    </div>


                                                    <div class="ss-form-group col-md-4">
                                                        <label>Work Description</label>
                                                        <textarea type="text" name="description" id="descriptionEdit" placeholder="Enter Work Description"></textarea>
                                                        <span style="color:#b5649e;" id="passwordHelpInline"
                                                            class="form-text">
                                                            (Description not required)
                                                        </span>
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
                                                                        @if(isset($allKeywords['PreferredShift']))
                                                                        @foreach ($allKeywords['PreferredShift'] as $value)
                                                                        <option value="{{$value->id}}">{{$value->title}}</option>
                                                                        @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" id="shifttimeofdayAllValuesEdit" name="preferred_shift_duration" >
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addshifttimeofday('from_edit')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnEdit">Save as draft</button>
                                                        <button class="firstNextEdit next">Next</button>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- edits end --}}

                                            {{-- second edited --}}
                                            <!-- Second form slide required inputs for adding jobs -->
                                            <div class="page">
                                                <div class="row">
                                                    {{-- edits --}}
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Shift Cancellation Policy</label>
                                                        <input type="text" name="facility_shift_cancelation_policy" id="facility_shift_cancelation_policyEdit"
                                                        placeholder="Select your shift cancellation policy">
                                                        <span class="help-block-facility_shift_cancelation_policy"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Min distance from facility</label>
                                                        <input type="number" name="traveler_distance_from_facility"
                                                            id="traveler_distance_from_facilityEdit"
                                                            placeholder="Enter travel distance">
                                                        <span class="help-block-traveler_distance_from_facility"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Clinical Setting</label>
                                                        <select name="clinical_setting"
                                                    id="clinical_settingEdit">
                                                    <option value="" disabled selected hidden>Select an option</option>
                                                    @foreach ($allKeywords['ClinicalSetting'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                        <span class="help-block-clinical_setting"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Patient ratio</label>
                                                        <input type="number" name="Patient_ratio"
                                                            id="Patient_ratioEdit" placeholder="Enter Patient ratio">
                                                        <span class="help-block-Patient_ratio"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Unit</label>
                                                        <input type="text" name="Unit" id="UnitEdit"
                                                            placeholder="Enter Unit">
                                                        <span class="help-block-Unit"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Scrub Color</label>
                                                        <input type="text" name="scrub_color" id="scrub_colorEdit"
                                                            placeholder="Enter scrub color">
                                                        <span class="help-block-scrub_color"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Rto</label>
                                                        <select name="rto" id="rtoEdit">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="allowed">Allowed
                                                            </option>
                                                            <option value="not allowed">Not Allowed
                                                            </option>
                                                        </select>
                                                <span class="help-block-rto"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Guaranteed Hours per week</label>
                                                        <input type="number" name="guaranteed_hours"
                                                            id="guaranteed_hoursEdit"
                                                            placeholder="Enter Guaranteed Hours per week">
                                                        <span class="help-block-guaranteed_hours"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>Hours Per Week</label>
                                                        <input type="number" name="hours_per_week" id="hours_per_weekEdit"
                                                            placeholder="Enter hours per week">
                                                        <span class="help-block-hours_per_weekEdit"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>
                                                            Hours Per Shift
                                                        </label>
                                                        <input type="number" name="hours_shift" id="hours_shiftEdit"
                                                            placeholder="Enter Hours Per Shift">
                                                        <span class="help-block-hours_shift"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Shift Per Weeks</label>
                                                        <input type="number" name="weeks_shift" id="weeks_shiftEdit"
                                                            placeholder="Enter Shift Per Weeks">
                                                        <span class="help-block-weeks_shift"></span>
                                                    </div>




                                                    <span style="color:#b5649e;" id="passwordHelpInline"
                                                        class="form-text">
                                                        ( The above fields are not required )
                                                    </span>
                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnEdit">Save as draft</button>
                                                        <button class="prev-1Edit prev">Previous</button>
                                                        <button class="next-1Edit next">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end second edited --}}
                                            {{-- third slide draft --}}
                                            <div class="page">
                                                <div class="row">
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Referral Bonus</label>
                                                        <input type="number" name="referral_bonus"
                                                            id="referral_bonusEdit" placeholder="Enter referral bonus">
                                                        <span class="help-block-referral_bonus"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Sign on Bonus</label>
                                                        <input type="number" name="sign_on_bonus"
                                                            id="sign_on_bonusEdit" placeholder="Enter sign on bonus">
                                                        <span class="help-block-sign_on_bonus"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Completion Bonus</label>
                                                        <input type="number" name="completion_bonus"
                                                            id="completion_bonusEdit"
                                                            placeholder="Enter completion bonus">
                                                        <span class="help-block-completion_bonus"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Extension Bonus</label>

                                                        <input type="number" name="extension_bonus"
                                                            id="extension_bonusEdit" placeholder="Enter extension bonus">
                                                        <span class="help-block-extension_bonus"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Other bonus</label>
                                                        <input type="number" name="other_bonus" id="other_bonusEdit"
                                                            placeholder="Enter other bonus">
                                                        <span class="help-block-other_bonus"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Est. Texable Hourly Rate</label>
                                                        <input type="number" name="actual_hourly_rate"
                                                            id="actual_hourly_rateEdit"
                                                            placeholder="Enter taxable regular hourly rate">
                                                        <span class="help-block-actual_hourly_rate"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>Overtime Hourly rate</label>
                                                        <input type="number" name="overtime" id="overtimeEdit"
                                                            placeholder="Enter actual Overtime Hourly rate">
                                                        <span class="help-block-overtime"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>On Call</label>
                                                        <select name="on_call" id="on_callEdit">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-on_call"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>On Call Rate</label>
                                                        <input type="number" name="on_call_rate" id="on_call_rateEdit"
                                                        placeholder="Enter a call back hourly rate">
                                                        <span class="help-block-on_call_rate"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Holidy date</label>
                                                        <input type="date" name="holiday" id="holidayEdit"
                                                            placeholder="Enter Holidy hourly rate">
                                                        <span class="help-block-holiday"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Orientation Hourly rate</label>
                                                        <input type="number" name="orientation_rate"
                                                            id="orientation_rateEdit"
                                                            placeholder="Enter Orientation Hourly rate">
                                                        <span class="help-block-orientation_rate"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>Block scheduling</label>
                                                        <select name="block_scheduling" id="block_schedulingEdit">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-block_scheduling"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Float requirements</label>
                                                        <select name="float_requirement" id="float_requirementEdit">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-float_requirement"></span>
                                                    </div>


                                                    <span style="color:#b5649e;" id="passwordHelpInline"
                                                        class="form-text">
                                                        ( The above fields are not required )
                                                    </span>

                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnEdit">Save as draft</button>
                                                        <button class="prev-2Edit prev">Previous</button>
                                                        <button class="next-2Edit next">Next</button>
                                                    </div>

                                                </div>
                                            </div>
                                            {{-- end third slide draft --}}

                                            {{-- slide added from sheets --}}

                                    <div class="page">
                                        <div class="row">
                                            <div class="ss-form-group col-md-4">
                                                <label>Professional Licensure</label>
                                                <select name="job_location" id="job_locationEdit">
                                                    <option value="" disabled selected hidden>Select an option</option>
                                                    @foreach ($allKeywords['StateCode'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }} Compact
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-job_locationEdit"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Number Of References</label>
                                                <input type="number" name="number_of_references" id="number_of_referencesEdit"
                                                    placeholder="Enter number of references">
                                                <span class="help-block-number_of_referencesEdit"></span>
                                            </div>
                                            {{-- <div class="ss-form-group col-md-4">
                                                <label>Min Title Of Reference</label>
                                                <input type="text" name="min_title_of_reference" id="min_title_of_referenceEdit"
                                                    placeholder="Enter min title of reference">
                                                <span class="help-block-min_title_of_referenceEdit"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Recency Of Reference</label>
                                                <input type="number" name="recency_of_reference" id="recency_of_referenceEdit"
                                                    placeholder="Enter # recency of reference">
                                                <span class="help-block-recency_of_referenceEdit"></span>
                                            </div> --}}
                                            <div class="ss-form-group col-md-4">
                                                <label>Eligible work in us ?</label>
                                                <select name="eligible_work_in_us" id="eligible_work_in_usEdit">
                                                    <option value="" disabled selected hidden>Select an option</option>
                                                    <option value="1">Yes
                                                    </option>
                                                    <option value="0">No
                                                    </option>
                                                </select>
                                                <span class="help-block-eligible_work_in_usEdit"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Urgency</label>
                                                <div>
                                                    <input type="checkbox" name="urgency" id="urgencyEdit" value="Auto Offer" >
                                                    <label for="urgency">Auto Offer</label>
                                                </div>
                                                <span class="help-block-urgencyEdit"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Facility's Parent System</label>
                                                <input type="text" name="facilitys_parent_system" id="facilitys_parent_systemEdit"
                                                    placeholder="Enter facility's parent system">
                                                <span class="help-block-facilitys_parent_systemEdit"></span>
                                            </div>
                                            <div class="ss-form-group col-md-4">
                                                <label>Facility Name</label>
                                                <input type="text" name="facility_name" id="facility_nameEdit"
                                                    placeholder="Enter facility name">
                                                <span class="help-block-facility_nameEdit"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Worker Classification</label>
                                                <select name="nurse_classification" id="nurse_classificationEdit">
                                                    <option value="" disabled selected hidden>Select an option</option>
                                                    @foreach ($allKeywords['NurseClassification'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-nurse_classificationEdit"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Pay Frequency</label>
                                                <select name="pay_frequency" id="pay_frequencyEdit">
                                                    <option value="" disabled selected hidden>Select an option</option>
                                                    @foreach ($allKeywords['PayFrequency'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-pay_frequencyEdit"></span>
                                            </div>

                                            <div class="d-none ss-form-group col-md-4">
                                                <label>Benefits</label>
                                                <select name="benefits" id="benefitsEdit">
                                                    <option value="" disabled selected hidden>Select an option</option>
                                                    @foreach ($allKeywords['Benefits'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block-benefitsEdit"></span>
                                            </div>

                                            <div class="ss-form-group col-md-4">
                                                <label>Preferred Experience</label>
                                                <input type="number" name="preferred_experience" id="preferred_experienceEdit"
                                                    placeholder="Enter Preferred Experience">
                                                    <span class="help-block-preferred_experienceEdit"></span>
                                            </div>

                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Certifications</label>
                                                <div class="ss-speilty-exprnc-add-list certificate-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="certificateEdit">
                                                                <option value="">Select Certification</option>
                                                                @if(isset($allKeywords['Certification']))
                                                                @foreach ($allKeywords['Certification'] as $value)
                                                                <option value="{{$value->id}}">{{$value->title}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="certificateAllValuesEdit" name="certificate" >
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addcertifications('from_Edit')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Vaccinations & Immunizations name</label>
                                                <div class="ss-speilty-exprnc-add-list vaccinations-content">

                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select  class="m-0" id="vaccinationsEdit">
                                                                <option value="" disabled selected hidden>Enter Vaccinations & Immunizations name</option>
                                                                @if(isset($allKeywords['Vaccinations']))
                                                                    @foreach ($allKeywords['Vaccinations'] as $value)
                                                                        <option value="{{$value->id}}">{{$value->title}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="vaccinationsAllValuesEdit" name="vaccinations" >
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"  onclick="addvacc('from_Edit')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                            </div>



                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <label>Skills checklist</label>
                                                <div class="ss-speilty-exprnc-add-list skills-content">
                                                </div>
                                                <ul>
                                                    <li class="row w-100 p-0 m-0">
                                                        <div class="ps-0">
                                                            <select class="m-0" id="skillsEdit">
                                                                <option value="" disabled selected hidden>Select Skills</option>
                                                                @if(isset($allKeywords['Speciality']))
                                                                @foreach ($allKeywords['Speciality'] as $value)
                                                                <option value="{{$value->title}}">{{$value->title}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" id="skillsAllValuesEdit" name="skills" >
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addskills('from_Edit')"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                            </div>




                                            <div class="field btns col-12 d-flex justify-content-center">
                                                <button class="saveDrftBtnEdit">Save as draft</button>
                                                <button class="prev-3Edit prev">Previous</button>
                                                <button class="next-3Edit next">Next</button>

                                            </div>

                                        </div>
                                    </div>

                                    {{-- end slide added from sheets --}}

                                            <!-- Forth form slide for adding jobs -->
                                            {{-- edits forth --}}
                                            <div class="page">
                                                <div class="row">
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Contract Termination Policy</label>
                                                        <input type="text" name="contract_termination_policy" id="contract_termination_policyEdit"
                                                        placeholder="Enter your contract termination policy">
                                                        <span class="help-block-contract_termination_policy"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>EMR</label>
                                                        <select name="Emr"
                                                    id="emrEdit">
                                                    <option value="" disabled selected hidden>Select an option</option>
                                                    @foreach ($allKeywords['EMR'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                        <span class="help-block-emr"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>401K</label>
                                                        <select name="four_zero_one_k" id="four_zero_one_kEdit">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-four_zero_one_k"></span>
                                                    </div>
                                                    <div class="ss-form-group col-md-4">
                                                        <label>Health Insurance</label>
                                                        <select name="health_insaurance" id="health_insauranceEdit">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-health_insaurance"></span>
                                                    </div>


                                                    <div class="ss-form-group col-md-4">
                                                        <label>Feels Like $/hrs</label>
                                                        <input type="number" name="feels_like_per_hour" id="feels_like_per_hourEdit"
                                                            placeholder="Enter Feels Like $/hrs">
                                                        <span class="help-block-feels_like_per_hourEdit"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>On Call Back</label>
                                                        <select name="on_call_back" id="on_call_backEdit">
                                                            <option value="" disabled selected hidden>Select an option</option>
                                                            <option value="Yes">Yes
                                                            </option>
                                                            <option value="No">No
                                                            </option>
                                                        </select>
                                                        <span class="help-block-on_call_back"></span>
                                                    </div>


                                                    <div class="ss-form-group col-md-4">
                                                        <label>Call Back Hourly rate</label>
                                                        <input type="number" name="call_back_rate" id="call_back_rateEdit"
                                                            placeholder="Enter Call Back Hourly rate">
                                                        <span class="help-block-call_back_rate"></span>
                                                    </div>

                                                    <div class="ss-form-group col-md-4">
                                                        <label>Est. Weekly non-taxable amount</label>
                                                        <input type="number" name="weekly_non_taxable_amount"
                                                            id="weekly_non_taxable_amountEdit"
                                                            placeholder="Enter Weekly non-taxable amount">
                                                        <span class="help-block-weekly_non_taxable_amount"></span>
                                                    </div>



                                                    <div class="ss-form-group col-md-12">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-sm-12 col-md-12 col-xs-12">
                                                                <label>Start Date</label>
                                                            </div>
                                                            <div class="row col-lg-6 col-sm-12 col-md-12 col-xs-12"
                                                                style="display: flex; justify-content: end;">
                                                                <input id="as_soon_asEdit" name="as_soon_as"
                                                                    value="1" type="checkbox"
                                                                    style="box-shadow:none; width:auto;" class="col-6">
                                                                <label class="col-6">
                                                                    As soon As possible
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <input id="start_dateEdit" type="date" min="2024-03-06"
                                                            name="start_date" placeholder="Select Date"
                                                            value="2024-03-06">
                                                            <input id="job_idEdit" hidden
                                                            name="job_id" >
                                                    </div>
                                                    <span class="help-block-start_date"></span>



                                                    <span style="color:#b5649e;" id="passwordHelpInline"
                                                        class="form-text">
                                                        ( The above fields are not required )
                                                    </span>


                                                    <div class="field btns col-12 d-flex justify-content-center">
                                                        <button class="saveDrftBtnEdit">Save as draft</button>
                                                        <button class="prev-4Edit prev">Previous</button>
                                                        <button class="submitEdit">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end edits forth --}}

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

    <script>


function fillData() {
    const fields = {
        'job_name': 'job name',
        'job_type': 'Clinical',
        'preferred_specialty': 'Adult Medicine',
        'perferred_profession': 'Clerical',
        'job_state': 'Arizona',
        'weekly_pay': 250,
        'terms': 'Shift (PRN, Per Diem)',
        'preferred_assignment_duration': 3,
        'facility_shift_cancelation_policy': 'shift cancellation policy',
        'traveler_distance_from_facility': 50,
        'clinical_setting': 'Clinic',
        'Patient_ratio': 20,
        'Unit': 'Unit',
        'scrub_color': 'Blue',
        'rto': 'allowed',
        'guaranteed_hours': 7,
        'hours_per_week': 30,
        'hours_shift': 5,
        'weeks_shift': 3,
        'referral_bonus': 30,
        'sign_on_bonus': 10,
        'completion_bonus': 3,
        'extension_bonus': 3,
        'other_bonus': 3,
        'actual_hourly_rate': 3,
        'overtime': 3,
        'on_call': 'Yes',
        'on_call_rate': 10,
        'holiday': '2025-04-27',
        'orientation_rate': 19,
        'block_scheduling': 'No',
        'float_requirement': 'No',
        'number_of_references': 1,
        'eligible_work_in_us': 0,
        'urgency': 'Auto Offer',
        'facilitys_parent_system': 'Parent system',
        'facility_name': 'Facility Name',
        'pay_frequency': 'Daily',
        'preferred_experience': 'preferred experience',
        'contract_termination_policy': 'Contract policy',
        'four_zero_one_k': 'No',
        'health_insaurance': 'No',
        'feels_like_per_hour': 5,
        'on_call_back': 'No',
        'call_back_rate': 16,
        'weekly_non_taxable_amount': 100,
        'start_date': '2025-04-27',
        'preferred_experience':10
    };

    for (const [id, value] of Object.entries(fields)) {
        document.getElementById(id).value = value;
    }
}

function checkWorkerClassification(){
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

    document.addEventListener('DOMContentLoaded', async function () {
        fillData();
        // let workerClassification = document.getElementById("nurse_classification");
        // workerClassificationValue = workerClassification.value;
        // if(workerClassificationValue == 'W-2'){
        //     let benefitsElement = document.getElementById("benefits_id");
        //     benefitsElement.classList.remove('d-none');
        // }
        checkWorkerClassification();
        const jobTypeSelect = document.getElementById('job_type');
        const professionSelect = document.getElementById('perferred_profession');

        jobTypeSelect.addEventListener('change', function () {
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

        professions.forEach(function (profession) {
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
        console.log('id : ', selectedState );
        console.log('value : ',selectedJobState);

            await $.get(`/api/cities/${selectedState}`, function(cities) {
                console.log('cities :',cities);
                citiesData = [];
                citiesData = cities;
            });
            jobCity.innerHTML = '<option value="">Cities</option>';
            citiesData.forEach(function (City) {
            const option = document.createElement('option');
            option.value = City.name;
            option.textContent = City.name;
            jobCity.appendChild(option);
            });
            document.getElementById("job_city").value = 'Globe';

        jobState.addEventListener('change', async function (){

            const selectedJobState = this.value;
            const selectedState = $(this).find(':selected').attr('id');
            console.log('id : ', selectedState );
            console.log('value : ',selectedJobState);

            await $.get(`/api/cities/${selectedState}`, function(cities) {
                console.log('cities :',cities);
                citiesData = cities;
            });

            jobCity.innerHTML = '<option value="">Cities</option>';
            citiesData.forEach(function (City) {
            const option = document.createElement('option');
            option.value = City.name;
            option.textContent = City.name;
            jobCity.appendChild(option);
            });

        })


    });


    const myForm = document.getElementById('create_job_form');
    const draftJobs = @json($draftJobs);
    const publishedJobs = @json($publishedJobs);
    const onholdJobs = @json($onholdJobs);

    if (publishedJobs == 0){
        $("#application-details-apply").html('<div class="text-center"><span>Data Not found</span></div>');
        $("#job-list-published").html('<div class="text-center"><span>No Job</span></div>');
    }

    if (onholdJobs == 0){
        $("#application-details-apply-onhold").html('<div class="text-center"><span>Data Not found</span></div>');
        $("#job-list-onhold").html('<div class="text-center"><span>No Job</span></div>');
    }


if (draftJobs.length !== 0) {

    let job_name = draftJobs[0].job_name;
    let job_type = draftJobs[0].job_type;
    let preferred_specialty = draftJobs[0].preferred_specialty;
    let job_state = draftJobs[0].job_state;
    let job_city = draftJobs[0].job_city;
    let preferred_work_location = draftJobs[0].preferred_work_location;
    let preferred_assignment_duration = draftJobs[0].preferred_assignment_duration;
    let weekly_pay = draftJobs[0].weekly_pay;
    let description = draftJobs[0].description;
    let proffesion = draftJobs[0].proffesion;
    let facility_shift_cancelation_policy = draftJobs[0].facility_shift_cancelation_policy;
    let traveler_distance_from_facility = draftJobs[0].traveler_distance_from_facility;
    let clinical_setting = draftJobs[0].clinical_setting;
    let Patient_ratio = draftJobs[0].Patient_ratio;
    let Unit = draftJobs[0].Unit;
    let scrub_color = draftJobs[0].scrub_color;
    let rto = draftJobs[0].rto;
    let guaranteed_hours = draftJobs[0].guaranteed_hours;
    let hours_shift = draftJobs[0].hours_shift;
    let weeks_shift = draftJobs[0].weeks_shift;
    let referral_bonus = draftJobs[0].referral_bonus;
    let sign_on_bonus = draftJobs[0].sign_on_bonus;
    let completion_bonus = draftJobs[0].completion_bonus;
    let extension_bonus = draftJobs[0].extension_bonus;
    let other_bonus = draftJobs[0].other_bonus;
    let actual_hourly_rate = draftJobs[0].actual_hourly_rate;
    let overtime = draftJobs[0].overtime;
    let on_call = draftJobs[0].on_call;
    let on_call_rate = draftJobs[0].on_call_rate;
    let holiday = draftJobs[0].holiday;
    let orientation_rate = draftJobs[0].orientation_rate;
    let terms = draftJobs[0].terms;
    let block_scheduling = draftJobs[0].block_scheduling;
    let float_requirement = draftJobs[0].float_requirement;
    let contract_termination_policy = draftJobs[0].contract_termination_policy;
    let Emr = draftJobs[0].Emr;
    let four_zero_one_k = draftJobs[0].four_zero_one_k;
    let health_insaurance = draftJobs[0].health_insaurance;
    let call_back_rate = draftJobs[0].call_back_rate;
    let on_call_back = draftJobs[0].on_call_back;
    let feels_like_per_hour = draftJobs[0].feels_like_per_hour;
    let weekly_non_taxable_amount = draftJobs[0].weekly_non_taxable_amount;
    let start_date = draftJobs[0].start_date;
    let as_soon_as = draftJobs[0].as_soon_as;

    if (job_name !== null) {
        document.getElementById("job_nameDraft").value = job_name;
    }
    if (job_type !== null) {
        var jobtype = job_type;
                        var select = document.getElementById('job_typeDraft');
                        var option = document.createElement('option');
                        option.value = jobtype;
                        option.text = jobtype;

                        select.add(option);
                        select.value = jobtype;
    }
    if (preferred_specialty !== null) {
        var preferredSpecialty = preferred_specialty;
                        var select = document.getElementById('preferred_specialtyDraft');
                        var option = document.createElement('option');
                        option.value = preferredSpecialty;
                        option.text = preferredSpecialty;

                        select.add(option);
                        select.value = preferredSpecialty;
    }
    if (job_state !== null) {
        document.getElementById("job_stateDraft").value = job_state;
    }
    if (job_city !== null) {
        var city = job_city;
    var select = document.getElementById('job_cityDraft');
    var option = document.createElement('option');
    option.value = city;
    option.text = city;

    select.add(option);
    select.value = city;

    }
    if (preferred_work_location !== null) {
        document.getElementById("preferred_work_locationDraft").value = preferred_work_location;
    }
    if (preferred_assignment_duration !== null) {
        document.getElementById("preferred_assignment_durationDraft").value = preferred_assignment_duration;
    }
    if (weekly_pay !== null) {
        document.getElementById("weekly_payDraft").value = weekly_pay;
    }
    if (description !== null) {
        document.getElementById("descriptionDraft").value = description;
    }
    if (proffesion !== null) {
        var proffesionValue = proffesion;
    var select = document.getElementById('perferred_professionDraft');
    var option = document.createElement('option');
    option.value = proffesionValue;
    option.text = proffesionValue;

    select.add(option);
    select.value = proffesionValue;
        //document.getElementById("perferred_professionDraft").value = proffesion;
    }
    if (facility_shift_cancelation_policy !== null) {
        document.getElementById('facility_shift_cancelation_policyDraft').value = facility_shift_cancelation_policy;

    }
    if (traveler_distance_from_facility !== null) {
        document.getElementById("traveler_distance_from_facilityDraft").value = traveler_distance_from_facility;
    }
    if (clinical_setting !== null) {
        var ClinicalSettingValue = clinical_setting;
    var select = document.getElementById('clinical_settingDraft');
    var option = document.createElement('option');
    option.value = ClinicalSettingValue;
    option.text = ClinicalSettingValue;

    select.add(option);
    select.value = ClinicalSettingValue;

       // document.getElementById("clinical_settingDraft").value = clinical_setting;
    }
    if (Patient_ratio !== null) {
        document.getElementById("Patient_ratioDraft").value = Patient_ratio;
    }
    if (Unit !== null) {
        document.getElementById("UnitDraft").value = Unit;
    }
    if (scrub_color !== null) {
        document.getElementById("scrub_colorDraft").value = scrub_color;
    }
    if (rto !== null) {
        document.getElementById("rtoDraft").value = rto;
    }
    if (guaranteed_hours !== null) {
        document.getElementById("guaranteed_hoursDraft").value = guaranteed_hours;
    }
    if (hours_shift !== null) {
        document.getElementById("hours_shiftDraft").value = hours_shift;
    }
    if (weeks_shift !== null) {
        document.getElementById("weeks_shiftDraft").value = weeks_shift;
    }
    if (referral_bonus !== null) {
        document.getElementById("referral_bonusDraft").value = referral_bonus;
    }
    if (sign_on_bonus !== null) {
        document.getElementById("sign_on_bonusDraft").value = sign_on_bonus;
    }
    if (completion_bonus !== null) {
        document.getElementById("completion_bonusDraft").value = completion_bonus;
    }
    if (extension_bonus !== null) {
        document.getElementById("extension_bonusDraft").value = extension_bonus;
    }
    if (other_bonus !== null) {
        document.getElementById("other_bonusDraft").value = other_bonus;
    }
    if (actual_hourly_rate !== null) {
        document.getElementById("actual_hourly_rateDraft").value = actual_hourly_rate;
    }
    if (overtime !== null) {
        document.getElementById("overtimeDraft").value = overtime;
    }
    if (on_call !== null) {
        document.getElementById("on_callDraft").value = (on_call == 0) ? 'No' : 'Yes';

    }
    if (on_call_rate !== null) {
        document.getElementById("on_call_rateDraft").value = on_call_rate;
    }
    if (holiday !== null) {
        document.getElementById("holidayDraft").value = holiday;
    }
    if (orientation_rate !== null) {
        document.getElementById("orientation_rateDraft").value = orientation_rate;
    }
    if (terms !== null) {
        var Terms = terms;
    var select = document.getElementById('termsDraft');
    var option = document.createElement('option');
    option.value = Terms;
    option.text = Terms;

    select.add(option);
    select.value = Terms;

    }
    if (block_scheduling !== null) {
        document.getElementById("block_schedulingDraft").value = (block_scheduling == 0) ? 'No' : 'Yes';

    }
    if (float_requirement !== null) {
        document.getElementById("float_requirementDraft").value = (float_requirement == 0) ? 'No' : 'Yes';

    }
    if (contract_termination_policy !== null) {
        document.getElementById("contract_termination_policyDraft").value = contract_termination_policy;
    }
    if (Emr !== null) {

        var EmrValue = Emr;
    var select = document.getElementById('emrDraft');
    var option = document.createElement('option');
    option.value = EmrValue;
    option.text = EmrValue;

    select.add(option);
    select.value = EmrValue;


    }
    if (four_zero_one_k !== null) {
        document.getElementById("four_zero_one_kDraft").value = (four_zero_one_k == 0) ? 'No' : 'Yes';

    }
    if (health_insaurance !== null) {
        document.getElementById("health_insauranceDraft").value = (health_insaurance == 0) ? 'No' : 'Yes';

    }

    if(feels_like_per_hour !== null){
        document.getElementById("feels_like_per_hourDraft").value = feels_like_per_hour;
    }
    if (call_back_rate !== null) {
        document.getElementById("call_back_rateDraft").value = call_back_rate;
    }
    if (on_call_back !== null) {
        document.getElementById("on_call_backDraft").value = (on_call_back == 0) ? 'No' : 'Yes';
    }
    if (weekly_non_taxable_amount !== null) {
        document.getElementById("weekly_non_taxable_amountDraft").value = weekly_non_taxable_amount;
    }
    if (start_date !== null) {
        document.getElementById("start_dateDraft").value = start_date;
    }
    if (as_soon_as !== null) {
        document.getElementById("as_soon_asDraft").checked = as_soon_as;
    }
}else{
    document.getElementById('details_info').innerHTML = '<div class="text-center"><span>Data Not found</span></div>';
    $("#job-list-draft").html('<div class="text-center"><span>No Job</span></div>');

}


        var certificateStr = '';
        var vaccinationStr = '';
        var skillsStr = '';
        var shifttimeofdayStr = '';
        var benefitsStr = '';
        var professional_licensureStr = '';
        var nurse_classificationStr = '';
        var EmrStr = '';


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
            document.getElementById('no-job-posted').classList.add('d-none');
            document.getElementById('published-job-details').classList.add('d-none');
            document.getElementById('create_job_request_form').classList.remove('d-none');

        }



        function opportunitiesType(type, id = "", formtype) {

            console.log(type);

            var draftsElement = document.getElementById('drafts');
            var publishedElement = document.getElementById('published');
            var onholdElement = document.getElementById('onhold');


            if (type == "drafts") {
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
                    beforeSend:function(xhr){
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
                            if(type == "published"){
                                document.getElementById("published-job-details").classList.remove("d-none");
                                document.getElementById("no-job-posted").classList.add("d-none");
                                $("#application-details-apply").html(result.jobdetails);
                                $("#job-list-published").html(result.joblisting);
                            } else if(type == "onhold"){
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
                    beforeSend:function(xhr){
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
                if (name="benefits", name == "vaccinations" || name == "preferred_specialty" || name == "preferred_experience" || name ==
                    "certificate") {
                    var inputFields = document.querySelectorAll(name == "vaccinations" ? 'select[name="vaccinations"]' :
                        name == "preferred_specialty" ? 'select[name="preferred_specialty"]' :
                        name == "preferred_experience" ? 'input[name="preferred_experience"]' :
                        name == 'certificate' ? 'select[name="certificate"]' :
                        name == 'benefits' ? 'select[name="benefits"]' :
                        name == 'professional_licensure' ? 'select[name="professional_licensure"]' :
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
                    url: "{{ url('employer/employer-create-opportunity') }}/update",
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
                            url: "{{ url('employer/remove') }}/" + removetype,
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
        var vaccinations = {};

        function addvacc() {

            // var container = document.getElementById('add-more-certifications');

            // var newSelect = document.createElement('select');
            // newSelect.name = 'certificate';
            // newSelect.className = 'mb-3';

            // var originalSelect = document.getElementById('certificate');
            // var options = originalSelect.querySelectorAll('option');
            // for (var i = 0; i < options.length; i++) {
            //     var option = options[i].cloneNode(true);
            //     newSelect.appendChild(option);
            // }
            // container.querySelector('.col-md-11').appendChild(newSelect);

            if (!$('#vaccinations').val()) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Select the vaccinations please.',
                    time: 3
                });
            } else {
                if (!vaccinations.hasOwnProperty($('#vaccinations').val())) {

                    var select = document.getElementById("vaccinations");
                    var selectedOption = select.options[select.selectedIndex];
                    var optionText = selectedOption.textContent;

                    vaccinations[$('#vaccinations').val()] = optionText;
                    $('#vaccinations').val('');
                    list_vaccinations();
                }
            }
        }

        function list_vaccinations() {
            var str = '';
            if (window.allvaccinations) {
                vaccinations = Object.assign({}, vaccinations, window.allvaccinations);
            }
            for (const key in vaccinations) {
                let vaccinationsname = "";
                var select = document.getElementById("vaccinations");

                var allspcldata = [];
                for (var i = 0; i < select.options.length; i++) {
                    var obj = {
                        'id': select.options[i].value,
                        'title': select.options[i].textContent,
                    };
                    allspcldata.push(obj);
                }

                if (vaccinations.hasOwnProperty(key)) {

                    allspcldata.forEach(function(item) {
                        if (key == item.id) {
                            vaccinationsname = item.title;
                        }
                    });
                    const value = vaccinations[key];
                    str += '<ul>';
                    str += '<li class="w-50">' + vaccinationsname + '</li>';
                    str += '<li class="w-50 text-end"><button type="button"  id="remove-vaccinations" data-key="' + key +
                        '" onclick="remove_vaccinations(this, ' + key +
                        ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                    str += '</ul>';
                }
            }
            $('.vaccinations-content').html(str);
        }

        function remove_vaccinations(obj, key) {
            if (vaccinations.hasOwnProperty($(obj).data('key'))) {

                var element = document.getElementById("remove-vaccinations");
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                if (csrfToken) {
                    event.preventDefault();
                    if (document.getElementById('job_id').value) {
                        let formData = {
                            'job_id': document.getElementById('job_id').value,
                            'vaccinations': key,
                        }
                        let removetype = 'vaccinations';
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            type: 'POST',
                            url: "{{ url('employer/remove') }}/" + removetype,
                            data: formData,
                            dataType: 'json',
                            success: function(data) {
                                // notie.alert({
                                //     type: 'success',
                                //     text: '<i class="fa fa-check"></i> ' + data.message,
                                //     time: 5
                                // });
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    }
                    delete window.allvaccinations[$(obj).data('key')];
                    delete vaccinations[$(obj).data('key')];
                } else {
                    console.error('CSRF token not found.');
                }
                list_vaccinations();
            }
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
        console.log(element.id)

        var activeDocs = document.getElementsByClassName('col-12 ss-job-prfle-sec active')
        if(activeDocs.length > 0){
            for(i of activeDocs){
                i.classList.remove("active");
            }
        }

        const jobId = element.id;
        console.log(draftJobs[jobId].job_name)
        element.classList.add("active");

        let job_name = draftJobs[jobId].job_name;
        let job_type = draftJobs[jobId].job_type;
        let preferred_specialty = draftJobs[jobId].preferred_specialty;
        let job_state = draftJobs[jobId].job_state;
        let job_city = draftJobs[jobId].job_city;
        let preferred_work_location = draftJobs[jobId].preferred_work_location;
        let preferred_assignment_duration = draftJobs[jobId].preferred_assignment_duration;
        let weekly_pay = draftJobs[jobId].weekly_pay;
        let description = draftJobs[jobId].description;
        let proffesion = draftJobs[jobId].proffesion;
        let facility_shift_cancelation_policy = draftJobs[jobId].facility_shift_cancelation_policy;
        let traveler_distance_from_facility = draftJobs[jobId].traveler_distance_from_facility;
        let clinical_setting = draftJobs[jobId].clinical_setting;
        let Patient_ratio = draftJobs[jobId].Patient_ratio;
        let Unit = draftJobs[jobId].Unit;
        let scrub_color = draftJobs[jobId].scrub_color;
        let rto = draftJobs[jobId].rto;
        let guaranteed_hours = draftJobs[jobId].guaranteed_hours;
        let hours_shift = draftJobs[jobId].hours_shift;
        let weeks_shift = draftJobs[jobId].weeks_shift;
        let referral_bonus = draftJobs[jobId].referral_bonus;
        let sign_on_bonus = draftJobs[jobId].sign_on_bonus;
        let completion_bonus = draftJobs[jobId].completion_bonus;
        let extension_bonus = draftJobs[jobId].extension_bonus;
        let other_bonus = draftJobs[jobId].other_bonus;
        let actual_hourly_rate = draftJobs[jobId].actual_hourly_rate;
        let overtime = draftJobs[jobId].overtime;
        let on_call = draftJobs[jobId].on_call;
        let on_call_rate = draftJobs[jobId].on_call_rate;
        let holiday = draftJobs[jobId].holiday;
        let orientation_rate = draftJobs[jobId].orientation_rate;
        let terms = draftJobs[jobId].terms;
        let block_scheduling = draftJobs[jobId].block_scheduling;
        let float_requirement = draftJobs[jobId].float_requirement;
        let contract_termination_policy = draftJobs[jobId].contract_termination_policy;
        let Emr = draftJobs[jobId].Emr;
        let four_zero_one_k = draftJobs[jobId].four_zero_one_k;
        let health_insaurance = draftJobs[jobId].health_insaurance;
        let feels_like_per_hour = draftJobs[jobId].feels_like_per_hour;
        let call_back_rate = draftJobs[jobId].call_back_rate;
        let on_call_back = draftJobs[jobId].on_call_back;
        let weekly_non_taxable_amount = draftJobs[jobId].weekly_non_taxable_amount;
        let start_date = draftJobs[jobId].start_date;
        let as_soon_as = draftJobs[jobId].as_soon_as;

        if (job_name !== null) {
            document.getElementById("job_nameDraft").value = job_name;
        }else{
            document.getElementById("job_nameDraft").value = '';
        }
        if (job_type !== null) {
        var jobtype = job_type;
                        var select = document.getElementById('job_typeDraft');
                        var option = document.createElement('option');
                        option.value = jobtype;
                        option.text = jobtype;

                        select.add(option);
                        select.value = jobtype;
        }else{
            document.getElementById("job_typeDraft").value = '';
        }
        if (preferred_specialty !== null) {
        var preferredSpecialty = preferred_specialty;
                        var select = document.getElementById('preferred_specialtyDraft');
                        var option = document.createElement('option');
                        option.value = preferredSpecialty;
                        option.text = preferredSpecialty;

                        select.add(option);
                        select.value = preferredSpecialty;
            }else{
                document.getElementById("preferred_specialtyDraft").value = '';
            }
            if (job_state !== null) {
                document.getElementById("job_stateDraft").value = job_state;
            }else{
                document.getElementById("job_stateDraft").value = '';
            }
            if (job_city !== null) {
                var city = job_city;
            var select = document.getElementById('job_cityDraft');
            var option = document.createElement('option');
            option.value = city;
            option.text = city;

            select.add(option);
            select.value = city;

            }else{
                document.getElementById("job_cityDraft").value = '';
            }
            if (preferred_work_location !== null) {
                document.getElementById("preferred_work_locationDraft").value = preferred_work_location;
            }else{
                document.getElementById("preferred_work_locationDraft").value = '';
            }
            if (preferred_assignment_duration !== null) {
                document.getElementById("preferred_assignment_durationDraft").value = preferred_assignment_duration;
            }else   {
                document.getElementById("preferred_assignment_durationDraft").value = '';
            }
            if (weekly_pay !== null) {
                document.getElementById("weekly_payDraft").value = weekly_pay;
            }else{
                document.getElementById("weekly_payDraft").value = '';
            }
            if (description !== null) {
                document.getElementById("descriptionDraft").value = description;
            }else{
                document.getElementById("descriptionDraft").value = '';
            }
            if (proffesion !== null) {
                var proffesionValue = proffesion;
            var select = document.getElementById('perferred_professionDraft');
            var option = document.createElement('option');
            option.value = proffesionValue;
            option.text = proffesionValue;

            select.add(option);
            select.value = proffesionValue;
                //document.getElementById("perferred_professionDraft").value = proffesion;
            }else{
                document.getElementById("perferred_professionDraft").value = '';
            }
            if (facility_shift_cancelation_policy !== null) {
                document.getElementById('facility_shift_cancelation_policyDraft').value = facility_shift_cancelation_policy;
            }else{
                document.getElementById("facility_shift_cancelation_policyDraft").value = '';
            }
            if (traveler_distance_from_facility !== null) {
                document.getElementById("traveler_distance_from_facilityDraft").value = traveler_distance_from_facility;
            }else{
                document.getElementById("traveler_distance_from_facilityDraft").value = '';
            }
            if (clinical_setting !== null) {
                var ClinicalSettingValue = clinical_setting;
            var select = document.getElementById('clinical_settingDraft');
            var option = document.createElement('option');
            option.value = ClinicalSettingValue;
            option.text = ClinicalSettingValue;

            select.add(option);
            select.value = ClinicalSettingValue;
               // document.getElementById("clinical_settingDraft").value = clinical_setting;
            }else{
                document.getElementById("clinical_settingDraft").value = '';
            }
            if (Patient_ratio !== null) {
                document.getElementById("Patient_ratioDraft").value = Patient_ratio;
            }else{
                document.getElementById("Patient_ratioDraft").value = '';
            }
            if (Unit !== null) {
                document.getElementById("UnitDraft").value = Unit;
            }else{
                document.getElementById("UnitDraft").value = '';
            }
            if (scrub_color !== null) {
                document.getElementById("scrub_colorDraft").value = scrub_color;
            }else{
                document.getElementById("scrub_colorDraft").value = '';
            }
            if (rto !== null) {
                document.getElementById("rtoDraft").value = rto;
            }else{
                document.getElementById("rtoDraft").value = '';
            }
            if (guaranteed_hours !== null) {
                document.getElementById("guaranteed_hoursDraft").value = guaranteed_hours;
            }else{
                document.getElementById("guaranteed_hoursDraft").value = '';
            }
            if (hours_shift !== null) {
                document.getElementById("hours_shiftDraft").value = hours_shift;
            }else{
                document.getElementById("hours_shiftDraft").value = '';
            }
            if (weeks_shift !== null) {
                document.getElementById("weeks_shiftDraft").value = weeks_shift;
            }else{
                document.getElementById("weeks_shiftDraft").value = '';
            }
            if (referral_bonus !== null) {
                document.getElementById("referral_bonusDraft").value = referral_bonus;
            }else{
                document.getElementById("referral_bonusDraft").value = '';
            }
            if (sign_on_bonus !== null) {
                document.getElementById("sign_on_bonusDraft").value = sign_on_bonus;
            }else{
                document.getElementById("sign_on_bonusDraft").value = '';
            }
            if (completion_bonus !== null) {
                document.getElementById("completion_bonusDraft").value = completion_bonus;
            }else{
                document.getElementById("completion_bonusDraft").value = '';
            }
            if (extension_bonus !== null) {
                document.getElementById("extension_bonusDraft").value = extension_bonus;
            }else{
                document.getElementById("extension_bonusDraft").value = '';
            }
            if (other_bonus !== null) {
                document.getElementById("other_bonusDraft").value = other_bonus;
            }else  {
                document.getElementById("other_bonusDraft").value = '';
            }
            if (actual_hourly_rate !== null) {
                document.getElementById("actual_hourly_rateDraft").value = actual_hourly_rate;
            }else {
                document.getElementById("actual_hourly_rateDraft").value = '';
            }
            if (overtime !== null) {
                document.getElementById("overtimeDraft").value = overtime;
            } else {
                document.getElementById("overtimeDraft").value = '';
            }
            if (on_call !== null) {
                document.getElementById("on_callDraft").value = (on_call == 0) ? 'No' : 'Yes';

            }else {
                document.getElementById("on_callDraft").value = '';
            }
            if (holiday !== null) {
                document.getElementById("holidayDraft").value = holiday;
            }else {
                document.getElementById("holidayDraft").value = '';
            }
            if (orientation_rate !== null) {
                document.getElementById("orientation_rateDraft").value = orientation_rate;
            }else {
                document.getElementById("orientation_rateDraft").value = '';
            }
            if (terms !== null) {
                var Terms = terms;
            var select = document.getElementById('termsDraft');
            var option = document.createElement('option');
            option.value = Terms;
            option.text = Terms;

            select.add(option);
            select.value = Terms;
               // document.getElementById("termsDraft").value = terms;
            }else {
                document.getElementById("termsDraft").value = '';
            }
            if (block_scheduling !== null) {
                document.getElementById("block_schedulingDraft").value = (block_scheduling == 0) ? 'No' : 'Yes';

            }else {
                document.getElementById("block_schedulingDraft").value = '';
            }
            if (float_requirement !== null) {
                document.getElementById("float_requirementDraft").value = (float_requirement == 0) ? 'No' : 'Yes';

            }else {
                document.getElementById("float_requirementDraft").value = '';
            }
            if (contract_termination_policy !== null) {
                document.getElementById("contract_termination_policyDraft").value = contract_termination_policy;
            }else {
                document.getElementById("contract_termination_policyDraft").value = '';
            }
            if (Emr !== null) {


                var EmrValue = Emr;
            var select = document.getElementById('emrDraft');
            var option = document.createElement('option');
            option.value = EmrValue;
            option.text = EmrValue;

            select.add(option);
            select.value = EmrValue;



            }else {
                document.getElementById("emrDraft").value = '';
            }
            if (four_zero_one_k !== null) {
                document.getElementById("four_zero_one_kDraft").value = (four_zero_one_k == 0) ? 'No' : 'Yes';

            }else {
                document.getElementById("four_zero_one_kDraft").value = '';
            }
            if (health_insaurance !== null) {
        document.getElementById("health_insauranceDraft").value = (health_insaurance == 0) ? 'No' : 'Yes';

            }else {
                document.getElementById("health_insauranceDraft").value = '';
            }
            if(feels_like_per_hour !== null){
                document.getElementById("feels_like_per_hourDraft").value = feels_like_per_hour;
            }else {
                document.getElementById("feels_like_per_hourDraft").value = '';
            }
            if (call_back_rate !== null) {
                document.getElementById("call_back_rateDraft").value = call_back_rate;
            }else {
                document.getElementById("call_back_rateDraft").value = '';
            }
            if (on_call_back !== null) {
                document.getElementById("on_call_backDraft").value = (on_call_back == 0) ? 'No' : 'Yes';
            }else {
                document.getElementById("on_call_backDraft").value = '';
            }
            if (weekly_non_taxable_amount !== null) {
                document.getElementById("weekly_non_taxable_amountDraft").value = weekly_non_taxable_amount;
            }else {
                document.getElementById("weekly_non_taxable_amountDraft").value = '';
            }
            if (start_date !== null) {
                document.getElementById("start_dateDraft").value = start_date;
            }else {
                document.getElementById("start_dateDraft").value = '';
            }
            if (as_soon_as !== null) {
                document.getElementById("as_soon_asDraft").checked = as_soon_as;
            }else {
                document.getElementById("as_soon_asDraft").checked = '';
            }
    }



    const slidePage = document.querySelector(".slide-page");
    const nextBtnFirst = document.querySelector(".firstNext");
    const prevBtnSec = document.querySelector(".prev-1");
    const nextBtnSec = document.querySelector(".next-1");
    const prevBtnThird = document.querySelector(".prev-2");
    const nextBtnThird = document.querySelector(".next-2");
    const nextBtnForth = document.querySelector(".next-3");
    const prevBtnFourth = document.querySelector(".prev-3");
    const prevBtnFifth = document.querySelector(".prev-4");
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

    function validateseconde() {
        var access = true;
        // edits
        var facility_shift_cancelation_policy = document.getElementById("facility_shift_cancelation_policy").value;
        var traveler_distance_from_facility = document.getElementById("traveler_distance_from_facility").value;
        var clinical_setting = document.getElementById("clinical_setting").value;
        var Patient_ratio = document.getElementById("Patient_ratio").value;
        var Unit = document.getElementById("Unit").value;
        var scrub_color = document.getElementById("scrub_color").value;
        var rto = document.getElementById("rto").value;
        var guaranteed_hours = document.getElementById("guaranteed_hours").value;
        var hours_shift = document.getElementById("hours_shift").value;
        var weeks_shift = document.getElementById("weeks_shift").value;


        if (facility_shift_cancelation_policy.trim() === '') {
            $('.help-block-facility_shift_cancelation_policy').text(
                'Please enter the Shift Cancellation Policy');
            $('.help-block-facility_shift_cancelation_policy').addClass('text-danger');
            access = false;
        } else {

            $('.help-block-facility_shift_cancelation_policy').text('');
        }

        if (traveler_distance_from_facility.trim() === '') {
            $('.help-block-traveler_distance_from_facility').text('Please enter the min distance from facility');
            $('.help-block-traveler_distance_from_facility').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-traveler_distance_from_facility').text('');
        }

        if (clinical_setting.trim() === '') {
            $('.help-block-clinical_setting').text('Please enter the clinical setting');
            $('.help-block-clinical_setting').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-clinical_setting').text('');
        }


        if (Patient_ratio.trim() === '') {
            $('.help-block-Patient_ratio').text('Please enter the patient ratio');
            $('.help-block-Patient_ratio').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-Patient_ratio').text('');
        }


        if (Unit.trim() === '') {
            $('.help-block-Unit').text('Please enter the unit');
            $('.help-block-Unit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-Unit').text('');
        }

        if (scrub_color.trim() === '') {
            $('.help-block-scrub_color').text('Please enter the scrub color');
            $('.help-block-scrub_color').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-scrub_color').text('');
        }

        if (rto.trim() === '') {
            $('.help-block-rto').text('Please enter the rto');
            $('.help-block-rto').addClass('text-danger');
            access = false;

        } else {
            $('.help-block-rto').text('');
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



        if (access) {
            return true;
        } else {
            return false;
        }
    }

    function validateThird() {
        var access = true;
        var referral_bonus = document.getElementById("referral_bonus").value;
        var sign_on_bonus = document.getElementById("sign_on_bonus").value;
        var completion_bonus = document.getElementById("completion_bonus").value;
        var extension_bonus = document.getElementById("extension_bonus").value;
        var other_bonus = document.getElementById("other_bonus").value;
        var actual_hourly_rate = document.getElementById("actual_hourly_rate").value;
        var overtime = document.getElementById("overtime").value;
        var holiday = document.getElementById("holiday").value;
        var orientation_rate = document.getElementById("orientation_rate").value;
        var on_call = document.getElementById("on_call").value;
        var on_call_rate = document.getElementById("on_call_rate").value;
        var block_scheduling = document.getElementById("block_scheduling").value;

        var float_requirement = document.getElementById("float_requirement").value;

        if (referral_bonus.trim() === '') {
            $('.help-block-referral_bonus').text('Please enter the referral bonus');
            $('.help-block-referral_bonus').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-referral_bonus').text('');
        }

        if (sign_on_bonus.trim() === '') {
            $('.help-block-sign_on_bonus').text('Please enter the sign on bonus');
            $('.help-block-sign_on_bonus').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-sign_on_bonus').text('');
        }

        if (completion_bonus.trim() === '') {
            $('.help-block-completion_bonus').text('Please enter the completion bonus');
            $('.help-block-completion_bonus').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-completion_bonus').text('');
        }

        if (extension_bonus.trim() === '') {
            $('.help-block-extension_bonus').text('Please enter the extension bonus');
            $('.help-block-extension_bonus').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-extension_bonus').text('');
        }

        if (other_bonus.trim() === '') {
            $('.help-block-other_bonus').text('Please enter the other bonus');
            $('.help-block-other_bonus').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-other_bonus').text('');
        }

        if (actual_hourly_rate.trim() === '') {
            $('.help-block-actual_hourly_rate').text('Please enter the actual hourly rate');
            $('.help-block-actual_hourly_rate').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-actual_hourly_rate').text('');
        }

        if (overtime.trim() === '') {
            $('.help-block-overtime').text('Please enter the overtime');
            $('.help-block-overtime').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-overtime').text('');
        }


        if (holiday.trim() === '') {
            $('.help-block-holiday').text('Please enter the holiday');
            $('.help-block-holiday').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-holiday').text('');
        }

        if (orientation_rate.trim() === '') {
            $('.help-block-orientation_rate').text('Please enter the orientation rate');
            $('.help-block-orientation_rate').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-orientation_rate').text('');
        }
        if (on_call.trim() === '') {
            $('.help-block-on_call').text('Please enter the on call rate');
            $('.help-block-on_call').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-on_call').text('');
        }

        if (on_call_rate.trim() === '') {
            $('.help-block-on_call_rate').text('Please enter the on call rate');
            $('.help-block-on_call_rate').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-on_call_rate').text('');
        }



        if (block_scheduling.trim() === '') {
            $('.help-block-block_scheduling').text('Please enter the block scheduling');
            $('.help-block-block_scheduling').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-block_scheduling').text('');
        }



        if (float_requirement.trim() === '') {
            $('.help-block-float_requirement').text('Please enter the float requirement');
            $('.help-block-float_requirement').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-float_requirement').text('');
        }



        if (access) {
            return true;
        } else {
            return false;
        }

    }

    function validateForth() {
        var access = true;
        var contract_termination_policy = document.getElementById("contract_termination_policy").value;
        var four_zero_one_k = document.getElementById("four_zero_one_k").value;
        var health_insaurance = document.getElementById("health_insaurance").value;
        var feels_like_per_hour = document.getElementById("feels_like_per_hour").value;
        var call_back_rate = document.getElementById("call_back_rate").value;
        var on_call_back = document.getElementById("on_call_back").value;
        //var weekly_taxable_amount = document.getElementById("weekly_taxable_amount").value;
        var weekly_non_taxable_amount = document.getElementById("weekly_non_taxable_amount").value;
        // var hours_per_week = document.getElementById("hours_per_week").value;
        var hours_shift = document.getElementById("hours_shift").value;
        var start_date = document.getElementById("start_date").value;
        var as_soon_as = document.getElementById("as_soon_as").value;


        if (contract_termination_policy.trim() === '') {
            $('.help-block-contract_termination_policy').text('Please enter the contract termination policy');
            $('.help-block-contract_termination_policy').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-contract_termination_policy').text('');
        }



        if (four_zero_one_k.trim() === '') {
            $('.help-block-four_zero_one_k').text('Please enter the four zero one k');
            $('.help-block-four_zero_one_k').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-four_zero_one_k').text('');
        }


        if (health_insaurance.trim() === '') {
            $('.help-block-health_insaurance').text('Please enter the health insurance');
            $('.help-block-health_insaurance').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-health_insaurance').text('');
        }



        if (feels_like_per_hour.trim() === '') {
            $('.help-block-feels_like_per_hour').text('Please enter the feels like per hour');
            $('.help-block-feels_like_per_hour').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-feels_like_per_hour').text('');
        }

        if (call_back_rate.trim() === '') {
            $('.help-block-call_back_rate').text('Please enter the call back');
            $('.help-block-call_back_rate').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-call_back_rate').text('');
        }

        if (on_call_back.trim() === '') {
            $('.help-block-on_call_back').text('Please enter the on call back');
            $('.help-block-on_call_back').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-on_call_back').text('');
        }

        // if (weekly_taxable_amount.trim() === '') {
        //     $('.help-block-weekly_taxable_amount').text('Please enter the weekly taxable amount');
        //     $('.help-block-weekly_taxable_amount').addClass('text-danger');
        //     access = false;
        // } else {
        //     $('.help-block-weekly_taxable_amount').text('');
        // }

        if (weekly_non_taxable_amount.trim() === '') {
            $('.help-block-weekly_non_taxable_amount').text('Please enter the weekly non taxable amount');
            $('.help-block-weekly_non_taxable_amount').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-weekly_non_taxable_amount').text('');
        }

        // if (hours_per_week.trim() === '') {
        //     $('.help-block-hours_per_week').text('Please enter the hours per week');
        //     $('.help-block-hours_per_week').addClass('text-danger');
        //     access = false;
        // } else {
        //     $('.help-block-hours_per_week').text('');
        // }

        if (hours_shift.trim() === '') {
            $('.help-block-hours_shift').text('Please enter the hours shift');
            $('.help-block-hours_shift').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-hours_shift').text('');
        }

        if (start_date.trim() === '') {
            $('.help-block-start_date').text('Please enter the start date');
            $('.help-block-start_date').addClass('text-danger');
            access = false;
            if (as_soon_as.trim() === '') {
                $('.help-block-as_soon_as').text('Please enter the as soon as possible');
                $('.help-block-as_soon_as').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-as_soon_as').text('');
            }
        } else {
            $('.help-block-start_date').text('');
        }

        if (access) {
            return true;
        } else {
            return false;
        }

    }

    function validateFifth() {
        var access = true;
        return access;
        // if (access) {
        //     return true;
        // } else {
        //     return false;
        // }
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
        if (validateseconde()) {
            slidePage.style.marginLeft = "-40%";
            bullet[current - 1].classList.add("active");
            progressCheck[current - 1].classList.add("active");
            progressText[current - 1].classList.add("active");
            current += 1;
        }
    });
    nextBtnThird.addEventListener("click", function(event) {
        event.preventDefault();
        if (validateThird()) {
            slidePage.style.marginLeft = "-60%";
            bullet[current - 1].classList.add("active");
            progressCheck[current - 1].classList.add("active");
            progressText[current - 1].classList.add("active");
            current += 1;
        }
    });
    nextBtnForth.addEventListener("click", function(event) {
        event.preventDefault();
        if (validateFifth()) {
            slidePage.style.marginLeft = "-80%";
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
            vaccin_all_values.value = vaccinationStr;
        }
        let skills_all_values = document.getElementById("skillsAllValues");
        if (skills_all_values) {
            skills_all_values.value = skillsStr;
        }

        let shifttimeofday_all_values = document.getElementById("shifttimeofdayAllValues");
        if (shifttimeofday_all_values) {
            shifttimeofday_all_values.value = shifttimeofdayStr;
        }

        if (validateForth()) {
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
    prevBtnFifth.addEventListener("click", function(event) {
        event.preventDefault();
        slidePage.style.marginLeft = "-60%";
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
    const nextBtnForthDraft = document.querySelector(".next-3Draft");
    const prevBtnFourthDraft = document.querySelector(".prev-3Draft");
    const prevBtnFifthDraft = document.querySelector(".prev-4Draft");
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
        var weeklyPay = document.getElementById("weekly_payDraft").value;;
        var terms = document.getElementById("termsDraft").value;

        document.getElementById("active").value = false;
        document.getElementById("is_open").value = false;



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

    function validatesecondeDraft() {
        var access = true;
        // edits
        var facility_shift_cancelation_policy = document.getElementById("facility_shift_cancelation_policyDraft").value;
        var traveler_distance_from_facility = document.getElementById("traveler_distance_from_facilityDraft").value;
        var clinical_setting = document.getElementById("clinical_settingDraft").value;
        var Patient_ratio = document.getElementById("Patient_ratioDraft").value;
        var Unit = document.getElementById("UnitDraft").value;
        var scrub_color = document.getElementById("scrub_colorDraft").value;
        var rto = document.getElementById("rtoDraft").value;
        var guaranteed_hours = document.getElementById("guaranteed_hoursDraft").value;
        var hours_per_week = document.getElementById("hours_per_weekDraft").value;
        var hours_shift = document.getElementById("hours_shiftDraft").value;
        var weeks_shift = document.getElementById("weeks_shiftDraft").value;


        if (facility_shift_cancelation_policy.trim() === '') {
            $('.help-block-facility_shift_cancelation_policy').text(
                'Please enter the Shift Cancellation Policy');
            $('.help-block-facility_shift_cancelation_policy').addClass('text-danger');
            access = false;
        } else {

            $('.help-block-facility_shift_cancelation_policy').text('');
        }

        if (traveler_distance_from_facility.trim() === '') {
            $('.help-block-traveler_distance_from_facility').text('Please enter the min distance from facility');
            $('.help-block-traveler_distance_from_facility').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-traveler_distance_from_facility').text('');
        }

        if (clinical_setting.trim() === '') {
            $('.help-block-clinical_setting').text('Please enter the clinical setting');
            $('.help-block-clinical_setting').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-clinical_setting').text('');
        }


        if (Patient_ratio.trim() === '') {
            $('.help-block-Patient_ratio').text('Please enter the patient ratio');
            $('.help-block-Patient_ratio').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-Patient_ratio').text('');
        }


        if (Unit.trim() === '') {
            $('.help-block-Unit').text('Please enter the unit');
            $('.help-block-Unit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-Unit').text('');
        }

        if (scrub_color.trim() === '') {
            $('.help-block-scrub_color').text('Please enter the scrub color');
            $('.help-block-scrub_color').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-scrub_color').text('');
        }

        if (rto.trim() === '') {
            $('.help-block-rto').text('Please enter the rto');
            $('.help-block-rto').addClass('text-danger');
            access = false;

        } else {
            $('.help-block-rto').text('');
        }

        if (guaranteed_hours.trim() === '') {
            $('.help-block-guaranteed_hours').text('Please enter the guaranteed hours');
            $('.help-block-guaranteed_hours').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-guaranteed_hours').text('');
        }

        if (hours_per_week.trim() === '') {
            $('.help-block-hours_per_week').text('Please enter the hours per week');
            $('.help-block-hours_per_week').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-hours_per_week').text('');
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

        if (access) {
            return true;
        } else {
            return false;
        }
    }

    function validateThirdDraft() {
        var access = true;
        var referral_bonus = document.getElementById("referral_bonusDraft").value;
        var sign_on_bonus = document.getElementById("sign_on_bonusDraft").value;
        var completion_bonus = document.getElementById("completion_bonusDraft").value;
        var extension_bonus = document.getElementById("extension_bonusDraft").value;
        var other_bonus = document.getElementById("other_bonusDraft").value;
        var actual_hourly_rate = document.getElementById("actual_hourly_rateDraft").value;
        var overtime = document.getElementById("overtimeDraft").value;
        var holiday = document.getElementById("holidayDraft").value;
        var orientation_rate = document.getElementById("orientation_rateDraft").value;
        var on_call = document.getElementById("on_callDraft").value;
        var on_call_rate = document.getElementById("on_call_rateDraft").value;
        var block_scheduling = document.getElementById("block_schedulingDraft").value;

        var float_requirement = document.getElementById("float_requirementDraft").value;

        if (referral_bonus.trim() === '') {
            $('.help-block-referral_bonus').text('Please enter the referral bonus');
            $('.help-block-referral_bonus').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-referral_bonus').text('');
        }

        if (sign_on_bonus.trim() === '') {
            $('.help-block-sign_on_bonus').text('Please enter the sign on bonus');
            $('.help-block-sign_on_bonus').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-sign_on_bonus').text('');
        }

        if (completion_bonus.trim() === '') {
            $('.help-block-completion_bonus').text('Please enter the completion bonus');
            $('.help-block-completion_bonus').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-completion_bonus').text('');
        }

        if (extension_bonus.trim() === '') {
            $('.help-block-extension_bonus').text('Please enter the extension bonus');
            $('.help-block-extension_bonus').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-extension_bonus').text('');
        }

        if (other_bonus.trim() === '') {
            $('.help-block-other_bonus').text('Please enter the other bonus');
            $('.help-block-other_bonus').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-other_bonus').text('');
        }

        if (actual_hourly_rate.trim() === '') {
            $('.help-block-actual_hourly_rate').text('Please enter the actual hourly rate');
            $('.help-block-actual_hourly_rate').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-actual_hourly_rate').text('');
        }

        if (overtime.trim() === '') {
            $('.help-block-overtime').text('Please enter the overtime');
            $('.help-block-overtime').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-overtime').text('');
        }


        if (holiday.trim() === '') {
            $('.help-block-holiday').text('Please enter the holiday');
            $('.help-block-holiday').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-holiday').text('');
        }

        if (orientation_rate.trim() === '') {
            $('.help-block-orientation_rate').text('Please enter the orientation rate');
            $('.help-block-orientation_rate').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-orientation_rate').text('');
        }
        if (on_call.trim() === '') {
            $('.help-block-on_call').text('Please enter the on call rate');
            $('.help-block-on_call').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-on_call').text('');
        }

        if (on_call_rate.trim() === '') {
            $('.help-block-on_call_rate').text('Please enter the on call rate');
            $('.help-block-on_call_rate').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-on_call_rate').text('');
        }

        if (block_scheduling.trim() === '') {
            $('.help-block-block_scheduling').text('Please enter the block scheduling');
            $('.help-block-block_scheduling').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-block_scheduling').text('');
        }



        if (float_requirement.trim() === '') {
            $('.help-block-float_requirement').text('Please enter the float requirement');
            $('.help-block-float_requirement').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-float_requirement').text('');
        }



        if (access) {
            return true;
        } else {
            return false;
        }

    }

    function validateForthDraft() {
        var access = true;
        var contract_termination_policy = document.getElementById("contract_termination_policyDraft").value;
        var emr = document.getElementById("emrDraft").value;
        var four_zero_one_k = document.getElementById("four_zero_one_kDraft").value;
        var health_insaurance = document.getElementById("health_insauranceDraft").value;
        var feels_like_per_hour = document.getElementById("feels_like_per_hourDraft").value;
        var call_back_rate = document.getElementById("call_back_rateDraft").value;
        var on_call_back = document.getElementById("on_call_backDraft").value;
        //var weekly_taxable_amount = document.getElementById("weekly_taxable_amount").value;
        var weekly_non_taxable_amount = document.getElementById("weekly_non_taxable_amountDraft").value;
        // var hours_per_week = document.getElementById("hours_per_week").value;
        var hours_shift = document.getElementById("hours_shiftDraft").value;
        var start_date = document.getElementById("start_dateDraft").value;
        var as_soon_as = document.getElementById("as_soon_asDraft").value;


        if (contract_termination_policy.trim() === '') {
            $('.help-block-contract_termination_policy').text('Please enter the contract termination policy');
            $('.help-block-contract_termination_policy').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-contract_termination_policy').text('');
        }

        if (emr.trim() === '') {
            $('.help-block-emr').text('Please enter the emr');
            $('.help-block-emr').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-emr').text('');
        }

        if (four_zero_one_k.trim() === '') {
            $('.help-block-four_zero_one_k').text('Please enter the four zero one k');
            $('.help-block-four_zero_one_k').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-four_zero_one_k').text('');
        }


        if (health_insaurance.trim() === '') {
            $('.help-block-health_insaurance').text('Please enter the health insurance');
            $('.help-block-health_insaurance').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-health_insaurance').text('');
        }



        if (feels_like_per_hour.trim() === '') {
            $('.help-block-feels_like_per_hourDraft').text('Please enter the feels like per hour');
            $('.help-block-feels_like_per_hourDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-feels_like_per_hourDraft').text('');
        }

        if (call_back_rate.trim() === '') {
            $('.help-block-call_back_rate').text('Please enter the call back');
            $('.help-block-call_back_rate').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-call_back_rate').text('');
        }

        if (on_call_back.trim() === '') {
            $('.help-block-on_call_back').text('Please enter the on call back');
            $('.help-block-on_call_back').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-on_call_back').text('');
        }

        // if (weekly_taxable_amount.trim() === '') {
        //     $('.help-block-weekly_taxable_amount').text('Please enter the weekly taxable amount');
        //     $('.help-block-weekly_taxable_amount').addClass('text-danger');
        //     access = false;
        // } else {
        //     $('.help-block-weekly_taxable_amount').text('');
        // }

        if (weekly_non_taxable_amount.trim() === '') {
            $('.help-block-weekly_non_taxable_amount').text('Please enter the weekly non taxable amount');
            $('.help-block-weekly_non_taxable_amount').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-weekly_non_taxable_amount').text('');
        }

        // if (hours_per_week.trim() === '') {
        //     $('.help-block-hours_per_week').text('Please enter the hours per week');
        //     $('.help-block-hours_per_week').addClass('text-danger');
        //     access = false;
        // } else {
        //     $('.help-block-hours_per_week').text('');
        // }

        if (hours_shift.trim() === '') {
            $('.help-block-hours_shift').text('Please enter the hours shift');
            $('.help-block-hours_shift').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-hours_shift').text('');
        }

        if (start_date.trim() === '') {
            $('.help-block-start_date').text('Please enter the start date');
            $('.help-block-start_date').addClass('text-danger');
            access = false;
            if (as_soon_as.trim() === '') {
                $('.help-block-as_soon_as').text('Please enter the as soon as possible');
                $('.help-block-as_soon_as').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-as_soon_as').text('');
            }
        } else {
            $('.help-block-start_date').text('');
        }

        if (access) {
            return true;
        } else {
            return false;
        }

    }

    function validateFifthDraft() {
        var access = true;
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
        if (validatesecondeDraft()) {
            slidePageDraft.style.marginLeft = "-40%";
            bulletDraft[currentDraft - 1].classList.add("active");
            progressCheckDraft[currentDraft - 1].classList.add("active");
            progressTextDraft[currentDraft - 1].classList.add("active");
            currentDraft += 1;
        }

    });
    nextBtnThirdDraft.addEventListener("click", function(event) {
        event.preventDefault();
        if (validateThirdDraft()) {
            slidePageDraft.style.marginLeft = "-60%";
            bulletDraft[currentDraft - 1].classList.add("active");
            progressCheckDraft[currentDraft - 1].classList.add("active");
            progressTextDraft[currentDraft - 1].classList.add("active");
            currentDraft += 1;
        }
    });
    nextBtnForthDraft.addEventListener("click", function(event) {
        event.preventDefault();
        if (validateFifthDraft()) {
            slidePageDraft.style.marginLeft = "-80%";
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
            vaccin_all_values.value = vaccinationStr;
        }
        let skills_all_values = document.getElementById("skillsAllValuesDraft");
        if (skills_all_values) {
            skills_all_values.value = skillsStr;
        }
        let shifttimeofday_all_values = document.getElementById("shifttimeofdayAllValuesDraft");
        if (shifttimeofday_all_values) {
            shifttimeofday_all_values.value = shifttimeofdayStr;
        }


        if (validateForthDraft()) {
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
        slidePageDraft.style.marginLeft = "0%";
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
    prevBtnFifthDraft.addEventListener("click", function(event) {
        event.preventDefault();
        slidePageDraft.style.marginLeft = "-60%";
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
    const nextBtnForthEdit = document.querySelector(".next-3Edit");
    const prevBtnFourthEdit = document.querySelector(".prev-3Edit");
    const prevBtnFifthEdit = document.querySelector(".prev-4Edit");
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

        document.getElementById("active").value = false;
        document.getElementById("is_open").value = false;



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

        if (terms.trim() === '') {
            $('.help-block-terms').text('Please enter the terms');
            $('.help-block-terms').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-terms').text('');
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

    function validatesecondeEdit() {
        var access = true;
        // edits
        var facility_shift_cancelation_policy = document.getElementById("facility_shift_cancelation_policyEdit").value;
        var traveler_distance_from_facility = document.getElementById("traveler_distance_from_facilityEdit").value;
        var clinical_setting = document.getElementById("clinical_settingEdit").value;
        var Patient_ratio = document.getElementById("Patient_ratioEdit").value;
        var Unit = document.getElementById("UnitEdit").value;
        var scrub_color = document.getElementById("scrub_colorEdit").value;
        var rto = document.getElementById("rtoEdit").value;
        var guaranteed_hours = document.getElementById("guaranteed_hoursEdit").value;
        var hours_per_week = document.getElementById("hours_per_weekEdit").value;
        var hours_shift = document.getElementById("hours_shiftEdit").value;
        var weeks_shift = document.getElementById("weeks_shiftEdit").value;


        if (facility_shift_cancelation_policy.trim() === '') {
            $('.help-block-facility_shift_cancelation_policy').text(
                'Please enter the Shift Cancellation Policy');
            $('.help-block-facility_shift_cancelation_policy').addClass('text-danger');
            access = false;
        } else {

            $('.help-block-facility_shift_cancelation_policy').text('');
        }

        if (traveler_distance_from_facility.trim() === '') {
            $('.help-block-traveler_distance_from_facility').text('Please enter the min distance from facility');
            $('.help-block-traveler_distance_from_facility').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-traveler_distance_from_facility').text('');
        }

        if (clinical_setting.trim() === '') {
            $('.help-block-clinical_setting').text('Please enter the clinical setting');
            $('.help-block-clinical_setting').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-clinical_setting').text('');
        }


        if (Patient_ratio.trim() === '') {
            $('.help-block-Patient_ratio').text('Please enter the patient ratio');
            $('.help-block-Patient_ratio').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-Patient_ratio').text('');
        }


        if (Unit.trim() === '') {
            $('.help-block-Unit').text('Please enter the unit');
            $('.help-block-Unit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-Unit').text('');
        }

        if (scrub_color.trim() === '') {
            $('.help-block-scrub_color').text('Please enter the scrub color');
            $('.help-block-scrub_color').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-scrub_color').text('');
        }

        if (rto.trim() === '') {
            $('.help-block-rto').text('Please enter the rto');
            $('.help-block-rto').addClass('text-danger');
            access = false;

        } else {
            $('.help-block-rto').text('');
        }

        if (guaranteed_hours.trim() === '') {
            $('.help-block-guaranteed_hours').text('Please enter the guaranteed hours');
            $('.help-block-guaranteed_hours').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-guaranteed_hours').text('');
        }

        if (hours_per_week.trim() === '') {
            $('.help-block-hours_per_week').text('Please enter the hours per week');
            $('.help-block-hours_per_week').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-hours_per_week').text('');
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

        if (access) {
            return true;
        } else {
            return false;
        }
    }

    function validateThirdEdit() {
        var access = true;
        var referral_bonus = document.getElementById("referral_bonusEdit").value;
        var sign_on_bonus = document.getElementById("sign_on_bonusEdit").value;
        var completion_bonus = document.getElementById("completion_bonusEdit").value;
        var extension_bonus = document.getElementById("extension_bonusEdit").value;
        var other_bonus = document.getElementById("other_bonusEdit").value;
        var actual_hourly_rate = document.getElementById("actual_hourly_rateEdit").value;
        var overtime = document.getElementById("overtimeEdit").value;
        var holiday = document.getElementById("holidayEdit").value;
        var orientation_rate = document.getElementById("orientation_rateEdit").value;
        var on_call = document.getElementById("on_callEdit").value;
        var on_call_rate = document.getElementById("on_call_rateEdit").value;
        var block_scheduling = document.getElementById("block_schedulingEdit").value;

        var float_requirement = document.getElementById("float_requirementEdit").value;

        if (referral_bonus.trim() === '') {
            $('.help-block-referral_bonus').text('Please enter the referral bonus');
            $('.help-block-referral_bonus').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-referral_bonus').text('');
        }

        if (sign_on_bonus.trim() === '') {
            $('.help-block-sign_on_bonus').text('Please enter the sign on bonus');
            $('.help-block-sign_on_bonus').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-sign_on_bonus').text('');
        }

        if (completion_bonus.trim() === '') {
            $('.help-block-completion_bonus').text('Please enter the completion bonus');
            $('.help-block-completion_bonus').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-completion_bonus').text('');
        }

        if (extension_bonus.trim() === '') {
            $('.help-block-extension_bonus').text('Please enter the extension bonus');
            $('.help-block-extension_bonus').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-extension_bonus').text('');
        }

        if (other_bonus.trim() === '') {
            $('.help-block-other_bonus').text('Please enter the other bonus');
            $('.help-block-other_bonus').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-other_bonus').text('');
        }

        if (actual_hourly_rate.trim() === '') {
            $('.help-block-actual_hourly_rate').text('Please enter the actual hourly rate');
            $('.help-block-actual_hourly_rate').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-actual_hourly_rate').text('');
        }

        if (overtime.trim() === '') {
            $('.help-block-overtime').text('Please enter the overtime');
            $('.help-block-overtime').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-overtime').text('');
        }


        if (holiday.trim() === '') {
            $('.help-block-holiday').text('Please enter the holiday');
            $('.help-block-holiday').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-holiday').text('');
        }

        if (orientation_rate.trim() === '') {
            $('.help-block-orientation_rate').text('Please enter the orientation rate');
            $('.help-block-orientation_rate').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-orientation_rate').text('');
        }
        if (on_call.trim() === '') {
            $('.help-block-on_call').text('Please enter the on call rate');
            $('.help-block-on_call').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-on_call').text('');
        }
        if(on_call_rate.trim() === '') {
            $('.help-block-on_call_rate').text('Please enter the on call rate');
            $('.help-block-on_call_rate').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-on_call_rate').text('');
        }



        if (block_scheduling.trim() === '') {
            $('.help-block-block_scheduling').text('Please enter the block scheduling');
            $('.help-block-block_scheduling').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-block_scheduling').text('');
        }



        if (float_requirement.trim() === '') {
            $('.help-block-float_requirement').text('Please enter the float requirement');
            $('.help-block-float_requirement').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-float_requirement').text('');
        }



        if (access) {
            return true;
        } else {
            return false;
        }

    }

    function validateForthEdit() {
        var access = true;
        var contract_termination_policy = document.getElementById("contract_termination_policyEdit").value;
        var emr = document.getElementById("emrEdit").value;
        var four_zero_one_k = document.getElementById("four_zero_one_kEdit").value;
        var health_insaurance = document.getElementById("health_insauranceEdit").value;
         var feels_like_per_hour = document.getElementById("feels_like_per_hourEdit").value;
        var call_back_rate = document.getElementById("call_back_rateEdit").value;
        var on_call_back = document.getElementById("on_call_backEdit").value;
        //var weekly_taxable_amount = document.getElementById("weekly_taxable_amount").value;
        var weekly_non_taxable_amount = document.getElementById("weekly_non_taxable_amountEdit").value;
        // var hours_per_week = document.getElementById("hours_per_week").value;
        var hours_shift = document.getElementById("hours_shiftEdit").value;
        var start_date = document.getElementById("start_dateEdit").value;
        var as_soon_as = document.getElementById("as_soon_asEdit").value;


        if (contract_termination_policy.trim() === '') {
            $('.help-block-contract_termination_policy').text('Please enter the contract termination policy');
            $('.help-block-contract_termination_policy').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-contract_termination_policy').text('');
        }

        if (emr.trim() === '') {
            $('.help-block-emr').text('Please enter the emr');
            $('.help-block-emr').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-emr').text('');
        }

        if (four_zero_one_k.trim() === '') {
            $('.help-block-four_zero_one_k').text('Please enter the four zero one k');
            $('.help-block-four_zero_one_k').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-four_zero_one_k').text('');
        }


        if (health_insaurance.trim() === '') {
            $('.help-block-health_insaurance').text('Please enter the health insurance');
            $('.help-block-health_insaurance').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-health_insaurance').text('');
        }



        if (feels_like_per_hour.trim() === '') {
            $('.help-block-feels_like_per_hourEdit').text('Please enter the feels like per hour');
            $('.help-block-feels_like_per_hourEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-feels_like_per_hour').text('');
        }



        if (call_back_rate.trim() === '') {
            $('.help-block-call_back_rate').text('Please enter the call back');
            $('.help-block-call_back_rate').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-call_back_rate').text('');
        }

        if (on_call_back.trim() === '') {
            $('.help-block-on_call_back').text('Please enter the on call back');
            $('.help-block-on_call_back').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-on_call_back').text('');
        }



        if (weekly_non_taxable_amount.trim() === '') {
            $('.help-block-weekly_non_taxable_amount').text('Please enter the weekly non taxable amount');
            $('.help-block-weekly_non_taxable_amount').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-weekly_non_taxable_amount').text('');
        }



        if (hours_shift.trim() === '') {
            $('.help-block-hours_shift').text('Please enter the hours shift');
            $('.help-block-hours_shift').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-hours_shift').text('');
        }

        if (start_date.trim() === '') {
            $('.help-block-start_date').text('Please enter the start date');
            $('.help-block-start_date').addClass('text-danger');
            access = false;
            if (as_soon_as.trim() === '') {
                $('.help-block-as_soon_as').text('Please enter the as soon as possible');
                $('.help-block-as_soon_as').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-as_soon_as').text('');
            }
        } else {
            $('.help-block-start_date').text('');
        }

        if (access) {
            return true;
        } else {
            return false;
        }

    }

    function validateFifthEdit() {
        var access = true;
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
        if (validatesecondeEdit()) {
            slidePageEdit.style.marginLeft = "-40%";
            bulletEdit[currentEdit - 1].classList.add("active");
            progressCheckEdit[currentEdit - 1].classList.add("active");
            progressTextEdit[currentEdit - 1].classList.add("active");
            currentEdit += 1;
        }

    });
    nextBtnThirdEdit.addEventListener("click", function(event) {
        event.preventDefault();
        if (validateThirdEdit()) {
            slidePageEdit.style.marginLeft = "-60%";
            bulletEdit[current - 1].classList.add("active");
            progressCheck[currentEdit - 1].classList.add("active");
            progressTextEdit[currentEdit - 1].classList.add("active");
            currentEdit += 1;
        }
    });
    nextBtnForthEdit.addEventListener("click", function(event) {
        event.preventDefault();
        if (validateFifthEdit()) {
            slidePageEdit.style.marginLeft = "-80%";
            bulletEdit[currentEdit - 1].classList.add("active");
            progressCheckEdit[currentEdit - 1].classList.add("active");
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
            vaccin_all_values.value = vaccinationStr;
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

        if (validateForthEdit()) {
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
        document.getElementById("activeEdit").value = true;
        document.getElementById("is_openEdit").value = true;
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
    prevBtnFifthEdit.addEventListener("click", function(event) {
        event.preventDefault();
        slidePageEdit.style.marginLeft = "-60%";
        bulletEdit[currentEdit - 2].classList.remove("active");
        progressCheckEdit[currentEdit - 2].classList.remove("active");
        progressTextEdit[currentEdit - 2].classList.remove("active");
        currentEdit -= 1;
    });

    function job_details_to_edit(id){
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
                        document.getElementById("job_nameEdit").value = result.job_name;

                        // here
                        var jobtype = result.job_type;
                        var select = document.getElementById('job_typeEdit');
                        var option = document.createElement('option');
                        option.value = jobtype;
                        option.text = jobtype;

                        select.add(option);
                        select.value = jobtype;

                        //document.getElementById("job_typeEdit").value = result.job_type;

                        var preferredSpecialty = result.preferred_specialty;
                        var select = document.getElementById('preferred_specialtyEdit');
                        var option = document.createElement('option');
                        option.value = preferredSpecialty;
                        option.text = preferredSpecialty;

                        select.add(option);
                        select.value = preferredSpecialty;
                        //document.getElementById("preferred_specialtyEdit").value = result.preferred_specialty;

                        var proffesionValue = result.proffesion;
                        var select = document.getElementById('perferred_professionEdit');
                        var option = document.createElement('option');
                        option.value = proffesionValue;
                        option.text = proffesionValue;

                        select.add(option);
                        select.value = proffesionValue;
                       // document.getElementById("perferred_professionEdit").value = result.proffesion; // corrected here

                        // here
                        document.getElementById("job_stateEdit").value = result.job_state;

                        var city = result.job_city;
                        var select = document.getElementById('job_cityEdit');
                        var option = document.createElement('option');
                        option.value = city;
                        option.text = city;

                        select.add(option);
                        select.value = city;

                        document.getElementById("preferred_work_locationEdit").value = result.preferred_work_location;

                        document.getElementById("weekly_payEdit").value = result.weekly_pay;
                        document.getElementById("descriptionEdit").value = result.description;
                        document.getElementById("preferred_assignment_durationEdit").value = result.preferred_assignment_duration;
                        document.getElementById('facility_shift_cancelation_policyEdit').value = result.facility_shift_cancelation_policy;
                        //document.getElementById("facility_shift_cancelation_policyEdit").value = result.facility_shift_cancelation_policy;
                        document.getElementById("traveler_distance_from_facilityEdit").value = result.traveler_distance_from_facility;
                        var ClinicalSettingValue = result.clinical_setting;
                        var select = document.getElementById('clinical_settingEdit');
                        var option = document.createElement('option');
                        option.value = ClinicalSettingValue;
                        option.text = ClinicalSettingValue;

                        select.add(option);
                        select.value = ClinicalSettingValue;
                        //document.getElementById("clinical_settingEdit").value = result.clinical_setting;
                        document.getElementById("Patient_ratioEdit").value = result.Patient_ratio;
                        document.getElementById("UnitEdit").value = result.Unit;
                        document.getElementById("scrub_colorEdit").value = result.scrub_color;
                        document.getElementById("rtoEdit").value = result.rto;
                        document.getElementById("guaranteed_hoursEdit").value = result.guaranteed_hours;
                        document.getElementById("hours_shiftEdit").value = result.hours_shift;
                        document.getElementById("weeks_shiftEdit").value = result.weeks_shift;
                        document.getElementById("referral_bonusEdit").value = result.referral_bonus;
                        document.getElementById("sign_on_bonusEdit").value = result.sign_on_bonus;
                        document.getElementById("completion_bonusEdit").value = result.completion_bonus;
                        document.getElementById("extension_bonusEdit").value = result.extension_bonus;
                        document.getElementById("other_bonusEdit").value = result.other_bonus;
                        document.getElementById("actual_hourly_rateEdit").value = result.actual_hourly_rate;
                        document.getElementById("overtimeEdit").value = result.overtime;
                        document.getElementById("holidayEdit").value = result.holiday;
                        document.getElementById("orientation_rateEdit").value = result.orientation_rate;
                        document.getElementById("on_callEdit").value = (result.on_call == 0) ? 'No' : 'Yes';
                        document.getElementById("on_call_rateEdit").value = result.on_call_rate;
                        document.getElementById("block_schedulingEdit").value = (result.block_scheduling == 0) ? 'No' : 'Yes';
                        var Terms = result.terms;
                        var select = document.getElementById('termsEdit');
                        var option = document.createElement('option');
                        option.value = Terms;
                        option.text = Terms;
                        select.add(option);
                        select.value = Terms;
                        //document.getElementById("termsEdit").value = result.terms;
                        document.getElementById("float_requirementEdit").value = (result.float_requirement == 0) ? 'No' : 'Yes';
                        document.getElementById("contract_termination_policyEdit").value = result.contract_termination_policy;
                        var EmrValue = result.Emr;
                        var select = document.getElementById('emrEdit');
                        var option = document.createElement('option');
                        option.value = EmrValue;
                        option.text = EmrValue;
                        select.add(option);
                        select.value = EmrValue;
                       // document.getElementById("emrEdit").value = result.Emr; // corrected here
                        document.getElementById("four_zero_one_kEdit").value = (result.four_zero_one_k == 0) ? 'No' : 'Yes';
                        document.getElementById("health_insauranceEdit").value = (result.health_insaurance == 0) ? 'No' : 'Yes';
                        document.getElementById("feels_like_per_hourEdit").value = result.feels_like_per_hour;
                        document.getElementById("call_back_rateEdit").value = result.call_back_rate;
                        document.getElementById("on_call_backEdit").value = (result.on_call_back == 0) ? 'No' : 'Yes';
                        document.getElementById("weekly_non_taxable_amountEdit").value = result.weekly_non_taxable_amount;
                        document.getElementById("hours_shiftEdit").value = result.hours_shift;
                        document.getElementById("start_dateEdit").value = result.start_date;
                        // removed as_soon_asEdit as it does not exist in the provided object
                        document.getElementById("job_idEdit").value = result.id;
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

{{-- script managing certifs  --}}

<script>
    var certificate = {};

    function addcertifications(type){
        var id;
        var idtitle;
        if (type == 'from_add') {
            id  = $('#certificate');
            idtitle = "certificate";
        }else if (type == 'from_draft'){
           id = $('#certificateDraft');
           idtitle = "certificateDraft";
        }else{
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
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-certificate" data-key="' + key + '" onclick="remove_certificate(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
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
    var vaccinations = {};

    function addvacc(type) {
        let id ;
        let idtitle;
        if (type == 'from_add') {
            id = $('#vaccinations');
            idtitle = "vaccinations";
        }else if (type == 'from_draft'){
            id = $('#vaccinationsDraft');
            idtitle = "vaccinationsDraft";
        }else{
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
                vaccinationStr = Object.values(vaccinations).join(', ');
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
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-vaccinations" data-key="' + key + '" onclick="remove_vaccinations(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.vaccinations-content').html(str);
    }

    function remove_vaccinations(obj, key) {
    if (vaccinations.hasOwnProperty(key)) {
        delete vaccinations[key]; // Remove the vaccination from the object
        vaccinationStr = Object.values(vaccinations).join(', '); // Update the hidden input value
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
    var skills = {};

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
        console.log(skills);

        for (const key in skills) {
            console.log('skills',skills);


            let skillsname = "";
            let allspcldata = @json($allKeywords['Speciality']);

            console.log('allspcldata',allspcldata);

            if (skills.hasOwnProperty(key)) {
                var data = allspcldata;

                data.forEach(function(item) {
                    if (key == item.title) {
                        skillsname = item.title;
                    }
                });
                const value = skills[key];
                str += '<ul>';
                str += '<li>' + skillsname + '</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-skills" data-key="' + key + '" onclick="remove_skills(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
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
    var shifttimeofday = {};

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
                console.log('shifttimeofdayStr',shifttimeofdayStr);
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
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-shifttimeofday" data-key="' + key + '" onclick="remove_shifttimeofday(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
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
    var benefits = {};

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
                console.log('benefitsStr',benefitsStr);
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
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-benefits" data-key="' + key + '" onclick="remove_benefits(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
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
    var professional_licensure = {};

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
                console.log('professional_licensureStr',professional_licensureStr);
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
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-professional_licensure" data-key="' + key + '" onclick="remove_professional_licensure(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.professional_licensure-content').html(str);
    }

    function remove_professional_licensure(obj, key) {
        if (professional_licensure.hasOwnProperty(key)) {
            delete professional_licensure[key]; // Remove the professional_licensure from the object
            professional_licensureStr = Object.values(professional_licensure).join(', '); // Update the hidden input value
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
    var Emr = {};

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
                console.log('EmrStr',EmrStr);
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
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-Emr" data-key="' + key + '" onclick="remove_Emr(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
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

{{-- script managing nurse_classification  --}}
<script>
    var nurse_classification = {};

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
                console.log('nurse_classificationStr',nurse_classificationStr);
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
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-nurse_classification" data-key="' + key + '" onclick="remove_nurse_classification(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
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


<style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

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
        width: 80%;
        margin: 0 auto;


    }

    ::selection {
        color: #fff;
        background: #b5649e;
    }

    .container {

        margin-top: 9%;
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
        margin: 0 0 30px 0;
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
    .saveDrftBtnEdit{
        margin-right: 3px;
    }
    .saveDrftBtnDraft{
        margin-right: 3px;
    }
</style>
@endsection
