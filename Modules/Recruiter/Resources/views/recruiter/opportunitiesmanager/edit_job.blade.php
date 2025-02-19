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

                        <div class=" page slide-page">

                            <div class="row">
                                <div class="ss-form-group col-md-12 d-none">
                                    <input hidden type="text" name="active" id="activeEdit">
                                </div>
                                <div class="ss-form-group col-md-12 d-none">
                                    <input hidden type="text" name="is_open" id="is_openEdit">
                                </div>
                                <div class="ss-form-group col-md-12 d-none">
                                    <input hidden type="text" name="id" id="idEdit">
                                </div>
                                
                                
                                <div class="col-md-12 mb-4 collapse-container">
                                    <p>
                                        <a class="btn first-collapse" data-toggle="collapse" >
                                            Summary
                                        </a>
                                    </p>
                                </div>
                                {{-- Org Job Id --}}

                                <div class="ss-form-group col-md-12">
                                    <label>Org Job Id</label>
                                    <input type="text" name="job_id" id="job_idEdit"
                                        placeholder="Enter Work Id">
                                    <div> 
                                            <span class="helper help-block-job_idEdit"></span>
                                    </div>
                                </div>

                                {{-- Type --}}

                                <div class="ss-form-group col-md-12">
                                    <label>Type</label>
                                    <select name="job_type" id="job_typeEdit">
                                        <option value="" disabled selected hidden>Select a type
                                        </option>
                                        <option value="Clinical">Clinical
                                        </option>
                                        <option value="Non-Clinical">Non-Clinical
                                        </option>
                                    </select>

                                    <div> 
                                    <span class="helper help-block-job_typeEdit"></span>
                                    </div>
                                </div>

                                {{-- Terms --}}

                                <div class="ss-form-group col-md-12">
                                    <label>Terms</label>
                                    <select name="terms" id="termsEdit">
                                        <option value="" disabled selected hidden>Select a Term</option>
                                        @foreach ($allKeywords['Terms'] as $value)
                                            <option value="{{ $value->title }}">{{ $value->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div> 
                                    <span class="helper help-block-termsEdit"></span>
                                    </div>
                                </div>

                                 {{-- Profession --}}

                                 <div class="ss-form-group col-md-12">
                                    <label>Profession</label>
                                    <select name="profession" id="perferred_professionEdit">
                                        <option value="" disabled selected hidden>Select a Profession
                                        </option>
                                        @foreach ($allKeywords['Profession'] as $value)
                                            <option value="{{ $value->title }}">{{ $value->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div> 
                                    <span class="helper help-block-perferred_professionEdit"></span>
                                    </div>
                                </div>

                                {{-- Specialty --}}

                                <div class="ss-form-group col-md-12">
                                    <label>Specialty</label>
                                    <select name="preferred_specialty" id="preferred_specialtyEdit">
                                        <option value="" disabled selected hidden>Select a specialty
                                        </option>
                                        @foreach ($specialities as $specialty)
                                            <option value="{{ $specialty->full_name }}">
                                                {{ $specialty->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div> 
                                    <span class="helper help-block-preferred_specialtyEdit"></span>
                                    </div>
                                </div>

                                {{-- $/hr --}}

                                <div class="ss-form-group col-md-12">
                                    <label>$/hr</label>
                                    <input type="number" name="actual_hourly_rate" id="actual_hourly_rateEdit"
                                        placeholder="Enter Taxable Regular Hourly rate">
                                    <div> 
                                        <span class="helper help-block-actual_hourly_rateEdit"></span>
                                    </div>
                                </div>

                                 {{-- $/Wk --}}

                                 <div class="ss-form-group col-md-12">
                                    <label>$/Wk</label>
                                    <input type="number" step="0.01" name="weekly_pay" id="weekly_payEdit"
                                        placeholder="Enter Weekly Pay">
                                    <div> 
                                        <span class="helper help-block-weekly_payEdit"></span>
                                    </div>
                                </div>

                                {{-- State --}}

                                <div class="ss-form-group col-md-12">
                                    <label> State </label>
                                    <select name="job_state" id="job_stateEdit">
                                        <option value="" disabled selected hidden>Select a State</option>
                                        @foreach ($states as $state)
                                            <option id="{{ $state->id }}" value="{{ $state->name }}">
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div> 
                                    <span class="helper help-block-job_stateEdit"></span>
                                    </div>
                                </div>

                                {{-- City --}}

                                <div class="ss-form-group col-md-12"> 
                                    <label> City </label>
                                    <select name="job_city" id="job_cityEdit">
                                        <option value="">Select a city</option>
                                    </select>
                                    <div>
                                        <div> 
                                        <span class="helper help-block-job_cityEdit"></span>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Resume --}}

                                <div class="row ss-form-group col-md-12 d-flex justify-content-end" style="margin-left: 17px; padding-bottom: 20px;">
                                    <label style="padding-bottom: 25px; padding-top: 25px;">Resume</label>
                                    <div class="row justify-content-center" style="display:flex; align-items:end;">
                                        <div class="col-6">
                                        <label for="is_resume"
                                            style="display:flex; justify-content:center;">Resume is required</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="checkbox" name="is_resume" id="job_isResumeEdit"
                                                value="1" style="box-shadow: none;">
                                        </div>
                                    </div>
                                    
                                    <div> 
                                        <span class="helper help-block-resumeEdit"></span>
                                    </div>
                                </div>
                                

                            <div class="col-md-12 mb-4 collapse-container">
                                <p>
                                    <a class="btn first-collapse" data-toggle="collapse" href="#collapse-1Edit" role="button" aria-expanded="false" aria-controls="collapseExample">
                                        Shift
                                    </a>
                                </p>
                            </div>

                            <div class="row collapse" id="collapse-1Edit">

                                {{-- Shift Time --}}

                                <div class="ss-form-group ss-prsnl-frm-specialty">
                                    <label>Shift Time</label>
                                    <div class="ss-speilty-exprnc-add-list shifttimeofday-content">
                                    </div>
                                    <ul style="align-items: flex-start;">
                                        <li class="row w-100 p-0 m-0">
                                            <div class="ps-0">
                                                <select class="m-0" id="shifttimeofdayEdit">
                                                    <option value="">Select Shift Time of Day</option>
                                                    @if (isset($allKeywords['PreferredShift']))
                                                        @foreach ($allKeywords['PreferredShift'] as $value)
                                                            <option value="{{ $value->id }}">{{$value->title}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input type="hidden" id="shifttimeofdayAllValuesEdit"
                                                    name="preferred_shift_duration">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                    onclick="addshifttimeofday('from_edit')"><i
                                                        class="fa fa-plus" aria-hidden="true"></i></a></div>
                                        </li>
                                    </ul>
                                    <div> 
                                    <span class="helper help-block-shift_time_of_dayEdit"></span>
                                    </div>
                                </div>

                                {{-- Hrs/Wk  --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Hrs/Wk</label>
                                    <input type="number" name="hours_per_week" id="hours_per_weekEdit"
                                        placeholder="Enter hours per week">
                                    <div> 
                                        <span class="helper help-block-hours_per_weekEdit"></span>
                                    </div>
                                </div>
                                {{-- Guaranteed Hrs/wk --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Guaranteed Hrs/wk</label>
                                    <input type="number" name="guaranteed_hours" id="guaranteed_hoursEdit"
                                        placeholder="Enter Guaranteed Hours per week">
                                    <div> 
                                        <span class="helper help-block-guaranteed_hoursEdit"></span>
                                    </div>
                                    </div>
                                {{-- Hrs/Shift --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Hrs/Shift</label>
                                    <input type="number" name="hours_shift" id="hours_shiftEdit"
                                        placeholder="Enter Hours Per Shift">
                                    <div> 
                                        <span class="helper help-block-hours_shiftEdit"></span>
                                    </div>
                                </div>
                                {{-- Shifts/Wk --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Shifts/Wk
                                    </label>
                                    <input type="number" name="weeks_shift" id="weeks_shiftEdit"
                                        placeholder="Enter Shift Per Weeks">
                                    <div> 
                                        <span class="helper help-block-weeks_shiftEdit"></span>
                                    </div>
                                </div>
                                {{-- Wks/Contract --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Wks/Contract</label>
                                    <input type="number" name="preferred_assignment_duration"
                                        id="preferred_assignment_durationEdit"
                                        placeholder="Enter Work Duration Per Assignment">
                                    <div> 
                                        <span class="helper help-block-preferred_assignment_durationEdit"></span>
                                    </div>
                                    </div>
                                {{-- Start Date --}}
                                <div class="ss-form-group col-md-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 col-md-12 col-xs-12" style="margin: 20px 0px;">
                                            <label>Start Date</label>
                                        </div>
                                        <div class="row col-lg-6 col-sm-12 col-md-12 col-xs-12"
                                            style="display: flex; justify-content: end; align-items:center;">
                                            <input id="as_soon_asEdit" name="as_soon_as" value="1"
                                                type="checkbox" style="box-shadow:none; width:auto;"
                                                class="col-6">
                                            <label class="col-6">
                                                As soon As possible
                                            </label>
                                        </div>
                                    </div>
                                    <input id="start_dateEdit" type="date" min="2024-03-06" name="start_date"
                                        placeholder="Select Date" value="{{ date('Y-m-d') }}">
                                    <div> 
                                        <span class="helper help-block-start_dateEdit"></span>
                                    </div>
                                </div>
                                {{-- End Date --}}
                                <div class="ss-form-group col-md-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12" style="margin: 20px 0px;">
                                            <label>End Date</label>
                                        </div>
                                        
                                    </div>
                                    <input id="end_dateEdit" type="date" min="2024-03-06" name="end_date"
                                        placeholder="Select Date" value="{{ date('Y-m-d') }}">
                                    <div> 
                                        <span class="helper help-block-end_dateEdit"></span>
                                    </div>
                                </div>
                                {{-- RTO --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Rto</label>
                                    <select name="rto" id="rtoEdit">
                                        <option value="" disabled selected hidden>Select an Rto</option>
                                        <option value="allowed">Allowed
                                        </option>
                                        <option value="not allowed">Not Allowed
                                        </option>
                                    </select>
                                    <div> 
                                    <span class="helper help-block-rtoEdit"></span>
                                    </div>
                                </div>

                                </div>
                                <div class="col-md-12 mb-4 collapse-container">
                                    <p>
                                        <a id="collapse-2-btn" class="btn first-collapse" data-toggle="collapse" href="#collapse-2Edit" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            Pay
                                        </a>
                                    </p>
                                </div>
                                <div class="row collapse" id="collapse-2Edit">
                                
                                {{-- OT/Hr --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Overtime Hourly rate</label>
                                    <input type="number" name="overtime" id="overtimeEdit"
                                        placeholder="Enter actual Overtime Hourly rate">
                                    <div> 
                                        <span class="helper help-block-overtimeEdit"></span>
                                    </div>
                                </div>
                                {{-- on call check --}}
                                <div class="ss-form-group col-md-12">
                                    <label>On Call</label>
                                    <select name="on_call" id="on_callEdit">
                                        <option value="" disabled selected hidden>Select an answer
                                        </option>
                                        <option value="Yes">Yes
                                        </option>
                                        <option value="No">No
                                        </option>
                                    </select>
                                    <div> 
                                    <span class="helper help-block-on_callEdit"></span>
                                    </div>
                                </div>
                                {{-- On Call/Hr --}}
                                <div class="ss-form-group col-md-12">
                                    <label>On Call Hourly Rate</label>
                                    <input type="number" name="on_call_rate" id="on_call_rateEdit"
                                        placeholder="Enter a call back hourly rate">
                                    <div> 
                                        <span class="helper help-block-on_call_rateEdit"></span>
                                    </div>
                                </div>
                                {{-- Call Back/Hr --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Call Back Hourly rate</label>
                                    <input type="number" name="call_back_rate" id="call_back_rateEdit"
                                        placeholder="Enter Call Back Hourly rate">
                                    <div> 
                                        <span class="helper help-block-call_back_rateEdit"></span>
                                    </div>
                                </div>
                                {{-- Orientation/Hr --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Orientation Hourly rate</label>
                                    <input type="number" name="orientation_rate" id="orientation_rateEdit"
                                        placeholder="Enter Orientation Hourly rate">
                                    <div> 
                                        <span class="helper help-block-orientation_rateEdit"></span>
                                    </div>
                                </div>
                                {{-- Taxable/Wk  --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Est. Taxable/Wk</label>
                                    <input type="number" name="weekly_taxable_amount"
                                        id="weekly_taxable_amountEdit"
                                        placeholder="Enter Weekly non-taxable amount">
                                    <div> 
                                        <span class="helper help-block-weekly_taxable_amountEdit"></span>
                                    </div>
                                </div>
                                {{-- Non-taxable/Wk  --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Est. Non-taxable/Wk</label>
                                    <input type="number" name="weekly_non_taxable_amount"
                                        id="weekly_non_taxable_amountEdit"
                                        placeholder="Enter Weekly non-taxable amount">
                                    <div> 
                                        <span class="helper help-block-weekly_non_taxable_amountEdit"></span>
                                    </div>
                                </div>
                                {{-- Feels Like $/hr --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Feels Like $/hrs</label>
                                    <input type="number" name="feels_like_per_hour" id="feels_like_per_hourEdit"
                                        placeholder="Enter Feels Like $/hrs">
                                    <div> 
                                        <span class="helper help-block-feels_like_per_hourEdit"></span>
                                    </div>
                                </div>
                                {{-- Gw$/Wk --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Gw$/Wk</label>
                                    <input type="number" name="goodwork_weekly_amount" id="goodwork_weekly_amountEdit"
                                        placeholder="Enter Gw$/Wk">
                                    <div> 
                                        <span class="helper help-block-goodwork_weekly_amountEdit"></span>
                                    </div>
                                </div>
                                {{-- Bonus --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Referral Bonus</label>
                                    <input type="number" name="referral_bonus" id="referral_bonusEdit"
                                        placeholder="Enter referral bonus">
                                    <div> 
                                        <span class="helper help-block-referral_bonusEdit"></span>
                                    </div>
                                    </div>
                                <div class="ss-form-group col-md-12">
                                    <label>Sign on Bonus</label>
                                    <input type="number" name="sign_on_bonus" id="sign_on_bonusEdit"
                                        placeholder="Enter sign on bonus">
                                    <div> 
                                        <span class="helper help-block-sign_on_bonusEdit"></span>
                                    </div>
                                    </div>
                                <div class="ss-form-group col-md-12">
                                    <label>Completion Bonus</label>
                                    <input type="number" name="completion_bonus" id="completion_bonusEdit"
                                        placeholder="Enter completion bonus">
                                    <div> 
                                        <span class="helper help-block-completion_bonusEdit"></span>
                                    </div>
                                    </div>
                                <div class="ss-form-group col-md-12">
                                    <label>Extension Bonus</label>

                                    <input type="number" name="extension_bonus" id="extension_bonusEdit"
                                        placeholder="Enter extension bonus">
                                    <div> 
                                        <span class="helper help-block-extension_bonusEdit"></span>
                                    </div>
                                    </div>
                                <div class="ss-form-group col-md-12">
                                    <label>Other bonus</label>
                                    <input type="number" name="other_bonus" id="other_bonusEdit"
                                        placeholder="Enter other bonus">
                                    <div> 
                                        <span class="helper help-block-other_bonusEdit"></span>
                                    </div>
                                </div>

                                {{-- total_organization_amount --}}

                                <div class="ss-form-group col-md-12">
                                    <label>Total Organization Amount</label>
                                    <input type="number" name="total_organization_amount"
                                        id="total_organization_amountEdit"
                                        placeholder="Enter Total Organization Amount">
                                    <div> 
                                        <span class="helper help-block-total_organization_amountEdit"></span>
                                    </div>
                                </div>

                                {{-- total_goodwork_amount --}}

                                <div class="ss-form-group col-md-12">
                                    <label>Total Goodwork Amount</label>
                                    <input type="number" name="total_goodwork_amount"
                                        id="total_goodwork_amountEdit"
                                        placeholder="Enter Total Goodwork Amount">
                                    <div> 
                                        <span class="helper help-block-total_goodwork_amountEdit"></span>
                                    </div>
                                </div>

                                {{-- total_contract_amount --}}

                                <div class="ss-form-group col-md-12">
                                    <label>Total Contract Amount</label>
                                    <input type="number" name="total_contract_amount"
                                        id="total_contract_amountEdit"
                                        placeholder="Enter Total Contract Amount">
                                    <div> 
                                        <span class="helper help-block-total_contract_amountEdit"></span>
                                    </div>
                                </div>

                                {{-- Pay Frequency --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Pay Frequency</label>
                                    <select name="pay_frequency" id="pay_frequencyEdit">
                                        <option value="" disabled selected hidden>Select a pay frequency
                                        </option>
                                        @foreach ($allKeywords['PayFrequency'] as $value)
                                            <option value="{{ $value->title }}">{{ $value->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div> 
                                    <span class="helper help-block-pay_frequencyEdit"></span>
                                    </div>
                                </div>
                                {{-- Benefits --}}
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
                                                            <option value="{{ $value->id }}">{{$value->title}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input type="hidden" id="benefitsAllValuesEdit" name="benefits">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                    onclick="addbenefits('from_edit')"><i class="fa fa-plus"
                                                        aria-hidden="true"></i></a></div>
                                        </li>
                                    </ul>
                                    <div> 
                                    <span class="helper help-block-benefitsEdit"></span>
                                    </div>
                                </div>

                                </div>
                                <div class="col-md-12 mb-4 collapse-container">
                                    <p>
                                        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-3Edit" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            Location
                                        </a>
                                    </p>
                                </div>
                            <div class="row collapse" id="collapse-3Edit">
                                {{-- Clinical Setting --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Clinical Setting</label>
                                    <select name="clinical_setting" id="clinical_settingEdit">
                                        <option value="" disabled selected hidden>Select a setting
                                        </option>
                                        @foreach ($allKeywords['ClinicalSetting'] as $value)
                                            <option value="{{ $value->title }}">{{ $value->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div> 
                                    <span class="helper help-block-clinical_settingEdit"></span>
                                    </div>
                                </div>
                                
                                {{-- Address --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Facilty address</label>
                                    <input type="text" name="preferred_work_location"
                                        id="preferred_work_locationEdit" placeholder="Enter Facilty address">
                                    <div> 
                                        <span class="helper help-block-preferred_work_locationEdit"></span>
                                    </div>
                                </div>

                                {{-- Facility --}}

                                <div class="ss-form-group col-md-12">
                                    <label>Facility Name</label>
                                    <input type="text" name="facility_name" id="facility_nameEdit"
                                        placeholder="Enter facility name">
                                    <div> 
                                        <span class="helper help-block-facility_nameEdit"></span>
                                    </div>
                                </div>

                                {{-- Facility's Parent System  --}}

                                <div class="ss-form-group col-md-12">
                                    <label>Facility's Parent System</label>
                                    <input type="text" name="facilitys_parent_system"
                                        id="facilitys_parent_systemEdit"
                                        placeholder="Enter facility's parent system">
                                    <div> 
                                        <span class="helper help-block-facilitys_parent_systemEdit"></span>
                                    </div>
                                </div>
                                {{-- Shift Cancellation Policy --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Shift Cancellation Policy</label>
                                    <input type="text" name="facility_shift_cancelation_policy"
                                        id="facility_shift_cancelation_policyEdit"
                                        placeholder="Select your shift cancellation policy">
                                    <div> 
                                        <span class="helper help-block-facility_shift_cancelation_policyEdit"></span>
                                    </div>
                                </div>
                                {{-- Contract Termination Policy --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Contract Termination Policy</label>
                                    <input type="text" name="contract_termination_policy"
                                        id="contract_termination_policyEdit"
                                        placeholder="Enter your contract termination policy">
                                    <div> 
                                        <span class="helper help-block-contract_termination_policyEdit"></span>
                                    </div>
                                </div>

                                {{-- Min Miles Must Live From Facility --}}
                                <div class="ss-form-group col-md-">
                                    <label>Min distance from facility</label>
                                    <input type="number" name="traveler_distance_from_facility"
                                        id="traveler_distance_from_facilityEdit"
                                        placeholder="Enter travel distance">
                                    <div> 
                                        <span class="helper help-block-traveler_distance_from_facilityEdit"></span>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12 mb-4 collapse-container">
                                <p>
                                    <a class="btn first-collapse" data-toggle="collapse" href="#collapse-4Edit" role="button" aria-expanded="false" aria-controls="collapseExample">
                                        Certs & Licences 
                                    </a>
                                </p>
                            </div>
                            <div class="row collapse" id="collapse-4Edit">
                                {{--  Professional Licensure --}}
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
                                                            <option value="{{ $value->id }}">{{$value->title}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input type="hidden" id="professional_licensureAllValuesEdit"
                                                    name="job_location">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                    onclick="addprofessional_licensure('from_edit')"><i
                                                        class="fa fa-plus" aria-hidden="true"></i></a></div>
                                        </li>
                                    </ul>
                                    <div> 
                                    <span class="helper help-block-professional_licensureEdit"></span>
                                    </div>
                                </div>
                                {{-- Certifications --}}
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
                                                            <option value="{{ $value->id }}">{{$value->title}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input type="hidden" id="certificateAllValuesEdit"
                                                    name="certificate">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                    onclick="addcertifications('from_edit')"><i
                                                        class="fa fa-plus" aria-hidden="true"></i></a></div>
                                        </li>
                                    </ul>
                                    <div> 
                                    <span class="helper help-block-certificateEdit"></span>
                                    </div>
                                </div>


                            </div>
                            <div class="col-md-12 mb-4 collapse-container">
                                <p>
                                    <a class="btn first-collapse" data-toggle="collapse" href="#collapse-5Edit" role="button" aria-expanded="false" aria-controls="collapseExample">
                                        Work Info
                                    </a>
                                </p>
                            </div>
                            <div class="row collapse" id="collapse-5Edit">
                                {{-- Description --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Description</label>
                                    <textarea type="text" name="description" id="descriptionEdit" placeholder="Enter Work Description"></textarea>
                                    <div> 
                                    <span class="helper help-block-descriptionEdit"></span>
                                    </div>
                                </div>
                                    {{-- Auto Offer --}}
                                <div class="row ss-form-group col-md-12 d-flex justify-content-end" style="margin-left: 17px; padding-bottom: 20px;">
                                    <label style="padding-bottom: 25px; padding-top: 25px;">Urgency</label>
                                    <div class="row justify-content-center" style="display:flex; align-items:end;">
                                        <div class="col-6">
                                        <label for="urgency"
                                            style="display:flex; justify-content:center;">Auto Offer</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="checkbox" name="urgency" id="urgencyEdit"
                                                value="Auto Offer" style="box-shadow: none;">
                                        </div>
                                    </div>
                                    
                                    <div> 
                                        <span class="helper help-block-urgencyEdit"></span>
                                    </div>
                                </div>
                               
                                {{-- Experience --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Experience (Yrs)</label>
                                    <input type="number" name="preferred_experience"
                                        id="preferred_experienceEdit" placeholder="Enter Experience">
                                    <div> 
                                        <span class="helper help-block-preferred_experienceEdit"></span>
                                    </div>
                                </div>
                                {{-- number of references --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Number Of References</label>
                                    <input type="number" name="number_of_references"
                                        id="number_of_referencesEdit" placeholder="Enter number of references">
                                    <div> 
                                        <span class="helper help-block-number_of_referencesEdit"></span>
                                    </div>
                                </div>
                                {{-- Skills --}}
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
                                                    onclick="addskills('from_edit')"><i class="fa fa-plus"
                                                        aria-hidden="true"></i></a></div>
                                        </li>
                                    </ul>
                                    <div> 
                                    <span class="helper help-block-skillsEdit"></span>
                                    </div>
                                </div>
                                {{-- Block scheduling --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Block scheduling</label>
                                    <select name="block_scheduling" id="block_schedulingEdit">
                                        <option value="" disabled selected hidden>Select an answer
                                        </option>
                                        <option value="Yes">Yes
                                        </option>
                                        <option value="No">No
                                        </option>
                                    </select>
                                    <div> 
                                    <span class="helper help-block-block_schedulingEdit"></span>
                                    </div>
                                </div>
                                {{-- Floating requirements --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Floating requirements</label>
                                    <select name="float_requirement" id="float_requirementEdit">
                                        <option value="" disabled selected hidden>Select an answer
                                        </option>
                                        <option value="Yes">Yes
                                        </option>
                                        <option value="No">No
                                        </option>
                                    </select>
                                    <div> 
                                    <span class="helper help-block-float_requirementEdit"></span>
                                    </div>
                                </div>
                                {{-- Patient Ratio Max --}}
                                <div class="ss-form-group col-md-12">
                                    <label>Patient ratio Max</label>
                                    <input type="number" name="Patient_ratio" id="Patient_ratioEdit"
                                        placeholder="Enter Patient ratio">
                                    <div> 
                                        <span class="helper help-block-Patient_ratioEdit"></span>
                                    </div>
                                </div>

                                {{-- EMR  --}}

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
                                                            <option value="{{ $value->id }}">{{$value->title}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input type="hidden" id="EmrAllValuesEdit" name="Emr">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                    onclick="addEmr('from_edit')"><i class="fa fa-plus"
                                                        aria-hidden="true"></i></a></div>
                                        </li>
                                    </ul>
                                    <div> 
                                    <span class="helper help-block-EmrEdit"></span>
                                    </div>
                                </div>

                                {{-- Unit --}}

                                <div class="ss-form-group col-md-12">
                                    <label>Unit</label>
                                    <input type="text" name="Unit" id="UnitEdit"
                                        placeholder="Enter Unit">
                                    <div> 
                                        <span class="helper help-block-UnitEdit"></span>
                                    </div>
                                </div>

                                </div>
                                <div class="col-md-12 mb-4 collapse-container">
                                    <p>
                                        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-6Edit" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            ID & Tax Info
                                        </a>
                                    </p>
                                </div>
                                <div class="row collapse" id="collapse-6Edit">

                                    {{-- Classification --}}
                                <div class="ss-form-group ss-prsnl-frm-specialty">
                                    <label>Classification</label>
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
                                                            <option value="{{ $value->id }}">{{$value->title}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input type="hidden" id="nurse_classificationAllValuesEdit"
                                                    name="nurse_classification">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                    onclick="addnurse_classification('from_edit')"><i
                                                        class="fa fa-plus" aria-hidden="true"></i></a></div>
                                        </li>
                                    </ul>
                                    <div> 
                                    <span class="helper help-block-nurse_classificationEdit"></span>
                                    </div>
                                </div>


                                </div>
                                <div class="col-md-12 mb-4 collapse-container">
                                    <p>
                                        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-7Edit" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            Medical info
                                        </a>
                                    </p>
                                </div>
                                <div class="row collapse" id="collapse-7Edit">

                                

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
                                                                <option value="{{ $value->id }}">{{$value->title}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <input type="hidden" id="vaccinationsAllValuesEdit"
                                                        name="vaccinations">
                                                </div>
                                            </li>
                                            <li>
                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                        onclick="addvacc('from_edit')"><i
                                                            class="fa fa-plus" aria-hidden="true"></i></a></div>
                                            </li>
                                        </ul>
                                        <div> 
                                        <span class="helper help-block-vaccinationsEdit"></span>
                                        </div>
                                    </div>


                                     </div>
                                     <div class="col-md-12 mb-4 collapse-container">
                                        <p>
                                            <a class="btn first-collapse" data-toggle="collapse" href="#collapse-8Edit" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                Other Info
                                            </a>
                                        </p>
                                    </div>
                                <div class="row collapse" id="collapse-8Edit">
                                    
                                    
                                <div class="ss-form-group col-md-12">
                                    <label>Scrub Color</label>
                                    <input type="text" name="scrub_color" id="scrub_colorEdit"
                                        placeholder="Enter scrub color">
                                    <div> 
                                        <span class="helper help-block-scrub_colorEdit"></span>
                                    </div>
                                    </div>
                                
                                
                                <div class="ss-form-group col-md-12">
                                    <label>Name</label>
                                    <input type="text" name="job_name" id="job_nameEdit"
                                        placeholder="Enter Work name">
                                    <div> 
                                        <span class="helper help-block-job_nameEdit"></span>
                                    </div>
                                </div>
                                 

                                    {{-- Holiday Dates --}}
                                <div class="ss-form-group ss-prsnl-frm-specialty">
                                    <label>Holiday</label>
                                    <div class="ss-speilty-exprnc-add-list holiday-content">
                                    </div>
                                    <ul>
                                        <li class="row w-100 p-0 m-0">
                                            <div class="ps-0">
                                                <input type="date" id="holidayDraft"
                                                    placeholder="Enter Holidy hourly rate" value="{{ date('Y-m-d') }}">
                                                <div> 
                                                <input type="hidden" id="holidayAllValuesDraft"
                                                    name="holiday">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                    onclick="addholidays('from_draft')"><i
                                                        class="fa fa-plus" aria-hidden="true"></i></a></div>
                                        </li>
                                    </ul>
                                    <div> 
                                        <span class="helper help-block-holidayEdit"></span>
                                    </div>
                                </div>
                                          
                                <div class="row d-flex justify-content-center col-md-12" style="padding-bottom: 20px;">
                                    <label style="padding-bottom: 25px; padding-top: 25px;">Professional State Licensure</label>
                                    
                                        <div class="row col-6 justify-content-center align-items-end">
                                            <label class="col-7" for="professional_state_licensure_pendingEdit">Accept
                                                Pending
                                            </label>
                                            <div class="col-5">
                                                <input type="radio" id="professional_state_licensure_pendingEdit"
                                                    name="professional_state_licensure" value="Accept Pending"
                                                    style="box-shadow: none;">
                                            </div>
                                        </div>
                                        {{-- Radio option for "Active" --}}
                                        <div class="row col-6 justify-content-center align-items-end">
                                            <label  class="col-7" for="professional_state_licensure_activeEdit">
                                                Active
                                            </label>
                                            <div class="col-5">
                                                <input type="radio" id="professional_state_licensure_activeEdit"
                                                    name="professional_state_licensure" value="Active"
                                                    style="box-shadow: none;">
                                            </div>
                                        </div>
                                        <div> 
                                        <span class="helper help-block-professional_state_licensureEdit"></span>
                                        </div>
                                </div>
                            </div>

                                        

                                <div class="field btns col-12 d-flex justify-content-center">
                                    <button class="saveDrftBtnEdit">Save as draft</button>
                                    {{-- <button class="firstNext next">Next</button> --}}
                                    <button class="submitEdit">Submit</button>
                                </div>
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
