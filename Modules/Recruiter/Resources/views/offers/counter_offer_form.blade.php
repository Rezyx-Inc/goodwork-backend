<div>
    <div class="mb-3">
        <h4 class="d-flex align-items-center"><img style="cursor: pointer; margin-right:10px;" onclick="getOneOfferInformation('{{ $offerdetails['id'] }}')" src="{{ URL::asset('recruiter/assets/images/counter-left-img.png') }}"> Send Offer</h4>
    </div>
    <div class="ss-job-view-off-text-fst-dv">
        <p class="mt-3">On behalf of <a href="">Albus Percival , Hogwarts</a> would like to offer <a
                href="#">{{ $offerdetails['id'] }}</a>
            to <a href="#">{{ $userdetails->first_name }} {{ $userdetails->last_name }}</a> 
           
        </p>

    </div>
</div>

<form class="ss-emplor-form-sec" id="send-job-offer">
    @csrf
    <!-- first form slide required inputs for adding jobs -->

    <div class=" page slide-page">

        <div class="row">
            <div class="ss-form-group col-md-12 d-none">
                <input hidden type="text" name="active" id="active">
            </div>
            <div class="ss-form-group col-md-12 d-none">
                <input hidden type="text" name="is_open" id="is_open">
            </div>
            
            
            <div class="col-md-12 mt-4 collapse-container">
                <p>
                    <a class="btn first-collapse" data-toggle="collapse" >
                        Summary
                    </a>
                </p>
            </div>
            {{-- offer id hidden --}}

            <div class="ss-form-group col-md-12 d-none">
                <input hidden type="text" name="offer_id" id="offer_id" value = "{{ $offerdetails['id'] }}">
            </div>

            {{-- Org Job Id --}}

            <div class="ss-form-group col-md-12">
                <label>Org Job Id</label>
                <input type="text" name="job_id" id="job_id"
                    placeholder="Enter Work Id">
                <div> 
                        <span class="help-block-job_id"></span>
                </div>
            </div>

            {{-- Type --}}

            <div class="ss-form-group col-md-12">
                <label>Type</label>
                <select name="type" id="type">
                    <option value="" disabled selected hidden>Select a type
                    </option>
                    <option value="Clinical">Clinical
                    </option>
                    <option value="Non-Clinical">Non-Clinical
                    </option>
                </select>

                <div> 
                <span class="help-block-type"></span>
                </div>
            </div>

            {{-- Terms --}}

            <div class="ss-form-group col-md-12">
                <label>Terms</label>
                <select name="terms" id="terms">
                    <option value="" disabled selected hidden>Select a Term</option>
                    @foreach ($allKeywords['Terms'] as $value)
                        <option value="{{ $value->title }}">{{ $value->title }}
                        </option>
                    @endforeach
                </select>
                <div> 
                <span class="help-block-terms"></span>
                </div>
            </div>

             {{-- Profession --}}

             <div class="ss-form-group col-md-12">
                <label>Profession</label>
                <select name="profession" id="profession">
                    <option value="" disabled selected hidden>Select a Profession
                    </option>
                    @foreach ($allKeywords['Profession'] as $value)
                        <option value="{{ $value->title }}">{{ $value->title }}
                        </option>
                    @endforeach
                </select>
                <div> 
                <span class="help-block-profession"></span>
                </div>
            </div>

            {{-- Specialty --}}

            <div class="ss-form-group col-md-12">
                <label>Specialty</label>
                <select name="specialty" id="specialty">
                    <option value="" disabled selected hidden>Select a specialty
                    </option>
                    @foreach ($specialities as $specialty)
                        <option value="{{ $specialty->full_name }}">
                            {{ $specialty->full_name }}
                        </option>
                    @endforeach
                </select>
                <div> 
                <span class="help-block-specialty"></span>
                </div>
            </div>

            {{-- $/hr --}}

            <div class="ss-form-group col-md-12">
                <label>$/hr</label>
                <input type="number" name="actual_hourly_rate" id="actual_hourly_rate"
                    placeholder="Enter Taxable Regular Hourly rate">
                <div> 
                    <span class="help-block-actual_hourly_rate"></span>
                </div>
            </div>

             {{-- $/Wk --}}

             <div class="ss-form-group col-md-12">
                <label>$/Wk</label>
                <input type="number" step="0.01" name="weekly_pay" id="weekly_pay"
                    placeholder="Enter Weekly Pay">
                <div> 
                    <span class="help-block-weekly_pay"></span>
                </div>
            </div>

            {{-- Hrs/Wk  --}}
            <div class="ss-form-group col-md-12">
                <label>Hrs/Wk</label>
                <input type="number" name="hours_per_week" id="hours_per_week"
                    placeholder="Enter hours per week">
                <div> 
                    <span class="help-block-hours_per_week"></span>
                </div>
            </div>

            {{-- State --}}

            <div class="ss-form-group col-md-12">
                <label> State </label>
                <select name="state" id="state">
                    <option value="" disabled selected hidden>Select a State</option>
                    @foreach ($states as $state)
                        <option id="{{ $state->id }}" value="{{ $state->name }}">
                            {{ $state->name }}
                        </option>
                    @endforeach
                </select>
                <div> 
                <span class="help-block-state"></span>
                </div>
            </div>

            {{-- City --}}

            <div class="ss-form-group col-md-12"> 
                <label> City </label>
                <select name="city" id="city">
                    <option value="">Select a city</option>
                </select>
                <div>
                    <div> 
                    <span class="help-block-city"></span>
                    </div>
                </div>
            </div>
            

        <div class="col-md-12 mt-4 collapse-container">
            <p>
                <a class="btn first-collapse" data-toggle="collapse" href="#collapse-1" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Shift
                </a>
            </p>
        </div>

        <div style="padding:0px; margin:0px;" class="row collapse" id="collapse-1">

            {{-- Shift Time --}}

            <div class="ss-form-group ss-prsnl-frm-specialty">
                <label>Shift Time</label>
                <div class="ss-speilty-exprnc-add-list shifttimeofday-content">
                </div>
                <ul style="align-items: flex-start;">
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
                <div> 
                <span class="help-block-shift_time_of_day"></span>
                </div>
            </div>

            
            {{-- Guaranteed Hrs/wk --}}
            <div class="ss-form-group col-md-12">
                <label>Guaranteed Hrs/wk</label>
                <input type="number" name="guaranteed_hours" id="guaranteed_hours"
                    placeholder="Enter Guaranteed Hours per week">
                <div> 
                    <span class="help-block-guaranteed_hours"></span>
                </div>
                </div>
            {{-- Hrs/Shift --}}
            <div class="ss-form-group col-md-12">
                <label>Hrs/Shift</label>
                <input type="number" name="hours_shift" id="hours_shift"
                    placeholder="Enter Hours Per Shift">
                <div> 
                    <span class="help-block-hours_shift"></span>
                </div>
            </div>
            {{-- Shifts/Wk --}}
            <div class="ss-form-group col-md-12">
                <label>Shifts/Wk
                </label>
                <input type="number" name="weeks_shift" id="weeks_shift"
                    placeholder="Enter Shift Per Weeks">
                <div> 
                    <span class="help-block-weeks_shift"></span>
                </div>
            </div>
            {{-- Wks/Contract --}}
            <div class="ss-form-group col-md-12">
                <label>Wks/Contract</label>
                <input type="number" name="preferred_assignment_duration"
                    id="preferred_assignment_duration"
                    placeholder="Enter Work Duration Per Assignment">
                <div> 
                    <span class="help-block-preferred_assignment_duration"></span>
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
                        <input type="hidden" name="as_soon_as" value="0">
                        <input id="as_soon_as" name="as_soon_as" value="1"
                            type="checkbox" style="box-shadow:none; width:auto;"
                            class="col-6">
                        <label class="col-6">
                            As soon As possible
                        </label>
                    </div>
                </div>
                <input id="start_date" type="date" min="2024-03-06" name="start_date"
                    placeholder="Select Date" value="{{ date('Y-m-d') }}">
                <div> 
                    <span class="help-block-start_date"></span>
                </div>
            </div>
            {{-- End Date --}}
            <div class="ss-form-group col-md-12">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12" style="margin: 20px 0px;">
                        <label>End Date</label>
                    </div>
                    
                </div>
                <input id="end_date" type="date" min="2024-03-06" name="end_date"
                    placeholder="Select Date" value="{{ date('Y-m-d') }}">
                <div> 
                    <span class="help-block-end_date"></span>
                </div>
            </div>
            {{-- RTO --}}
            <div class="ss-form-group col-md-12">
                <label>Rto</label>
                <select name="rto" id="rto">
                    <option value="" disabled selected hidden>Select an Rto</option>
                    <option value="allowed">Allowed
                    </option>
                    <option value="not allowed">Not Allowed
                    </option>
                </select>
                <div> 
                <span class="help-block-rto"></span>
                </div>
            </div>

            </div>
            <div class="col-md-12 mt-4 collapse-container">
                <p>
                    <a id="collapse-2-btn" class="btn first-collapse" data-toggle="collapse" href="#collapse-2" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Pay
                    </a>
                </p>
            </div>
            <div style="padding:0px; margin:0px;" class="row collapse" id="collapse-2">
            
            {{-- OT/Hr --}}
            <div class="ss-form-group col-md-12">
                <label>Overtime Hourly rate</label>
                <input type="number" name="overtime" id="overtime"
                    placeholder="Enter actual Overtime Hourly rate">
                <div> 
                    <span class="help-block-overtime"></span>
                </div>
            </div>
            {{-- on call check --}}
            <div class="ss-form-group col-md-12">
                <label>On Call</label>
                <select name="on_call" id="on_call">
                    <option value="" disabled selected hidden>Select an answer
                    </option>
                    <option value="1">Yes
                    </option>
                    <option value="0">No
                    </option>
                </select>
                <div> 
                <span class="help-block-on_call"></span>
                </div>
            </div>
            {{-- On Call/Hr --}}
            <div class="ss-form-group col-md-12">
                <label>On Call Hourly Rate</label>
                <input type="number" name="on_call_rate" id="on_call_rate"
                    placeholder="Enter a call back hourly rate">
                <div> 
                    <span class="help-block-on_call_rate"></span>
                </div>
            </div>
            {{-- Call Back/Hr --}}
            <div class="ss-form-group col-md-12">
                <label>Call Back Hourly rate</label>
                <input type="number" name="call_back_rate" id="call_back_rate"
                    placeholder="Enter Call Back Hourly rate">
                <div> 
                    <span class="help-block-call_back_rate"></span>
                </div>
                    <div> 
                <span class="help-block-call_back_rate"></span>
                    </div>
            </div>
            {{-- Orientation/Hr --}}
            <div class="ss-form-group col-md-12">
                <label>Orientation Hourly rate</label>
                <input type="number" name="orientation_rate" id="orientation_rate"
                    placeholder="Enter Orientation Hourly rate">
                <div> 
                    <span class="help-block-orientation_rate"></span>
                </div>
            </div>
            {{-- Taxable/Wk  --}}
            <div class="ss-form-group col-md-12">
                <label>Est. Taxable/Wk</label>
                <input type="number" name="weekly_taxable_amount"
                    id="weekly_taxable_amount"
                    placeholder="Enter Weekly non-taxable amount">
                <div> 
                    <span class="help-block-weekly_taxable_amount"></span>
                </div>
            </div>
            {{-- Non-taxable/Wk  --}}
            <div class="ss-form-group col-md-12">
                <label>Est. Non-taxable/Wk</label>
                <input type="number" name="weekly_non_taxable_amount"
                    id="weekly_non_taxable_amount"
                    placeholder="Enter Weekly non-taxable amount">
                <div> 
                    <span class="help-block-weekly_non_taxable_amount"></span>
                </div>
            </div>
            {{-- Feels Like $/hr --}}
            <div class="ss-form-group col-md-12">
                <label>Feels Like $/hrs</label>
                <input type="number" name="feels_like_per_hour" id="feels_like_per_hour"
                    placeholder="Enter Feels Like $/hrs">
                <div> 
                    <span class="help-block-feels_like_per_hour"></span>
                </div>
            </div>
            {{-- Gw$/Wk --}}
            <div class="ss-form-group col-md-12">
                <label>Gw$/Wk</label>
                <input type="number" name="goodwork_weekly_amount" id="goodwork_weekly_amount"
                    placeholder="Enter Gw$/Wk">
                <div> 
                    <span class="help-block-goodwork_weekly_amount"></span>
                </div>
            </div>
            {{-- Bonus --}}
            <div class="ss-form-group col-md-12">
                <label>Referral Bonus</label>
                <input type="number" name="referral_bonus" id="referral_bonus"
                    placeholder="Enter referral bonus">
                <div> 
                    <span class="help-block-referral_bonus"></span>
                </div>
                </div>
            <div class="ss-form-group col-md-12">
                <label>Sign on Bonus</label>
                <input type="number" name="sign_on_bonus" id="sign_on_bonus"
                    placeholder="Enter sign on bonus">
                <div> 
                    <span class="help-block-sign_on_bonus"></span>
                </div>
                </div>
            <div class="ss-form-group col-md-12">
                <label>Completion Bonus</label>
                <input type="number" name="completion_bonus" id="completion_bonus"
                    placeholder="Enter completion bonus">
                <div> 
                    <span class="help-block-completion_bonus"></span>
                </div>
                </div>
            <div class="ss-form-group col-md-12">
                <label>Extension Bonus</label>

                <input type="number" name="extension_bonus" id="extension_bonus"
                    placeholder="Enter extension bonus">
                <div> 
                    <span class="help-block-extension_bonus"></span>
                </div>
                </div>
            <div class="ss-form-group col-md-12">
                <label>Other bonus</label>
                <input type="number" name="other_bonus" id="other_bonus"
                    placeholder="Enter other bonus">
                <div> 
                    <span class="help-block-other_bonus"></span>
                </div>
                </div>

            {{-- Pay Frequency --}}
            <div class="ss-form-group col-md-12">
                <label>Pay Frequency</label>
                <select name="pay_frequency" id="pay_frequency">
                    <option value="" disabled selected hidden>Select a pay frequency
                    </option>
                    @foreach ($allKeywords['PayFrequency'] as $value)
                        <option value="{{ $value->title }}">{{ $value->title }}
                        </option>
                    @endforeach
                </select>
                <div> 
                <span class="help-block-pay_frequency"></span>
                </div>
            </div>
            {{-- Benefits --}}
            <div id="benefits_id" class="ss-form-group ss-prsnl-frm-specialty">
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
                <div> 
                <span class="help-block-benefits"></span>
                </div>
            </div>

            {{-- total_organization_amount --}}

            <div class="ss-form-group col-md-12">
                <label>Total Organization Amount</label>
                <input type="number" name="total_organization_amount"
                    id="total_organization_amount"
                    placeholder="Enter Total Organization Amount">
                <div> 
                    <span class="help-block-total_organization_amount"></span>
                </div>
            </div>

            {{-- total_goodwork_amount --}}

            <div class="ss-form-group col-md-12">
                <label>Total Goodwork Amount</label>
                <input type="number" name="total_goodwork_amount"
                    id="total_goodwork_amount"
                    placeholder="Enter Total Goodwork Amount">
                <div> 
                    <span class="help-block-total_goodwork_amount"></span>
                </div>
            </div>

            {{-- total_contract_amount --}}

            <div class="ss-form-group col-md-12">
                <label>Total Contract Amount</label>
                <input type="number" name="total_contract_amount"
                    id="total_contract_amount"
                    placeholder="Enter Total Contract Amount">
                <div> 
                    <span class="help-block-total_contract_amount"></span>
                </div>
            </div>

            </div>
            <div class="col-md-12 mt-4 collapse-container">
                <p>
                    <a class="btn first-collapse" data-toggle="collapse" href="#collapse-3" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Location
                    </a>
                </p>
            </div>
        <div style="padding:0px; margin:0px;" class="row collapse" id="collapse-3">
            {{-- Clinical Setting --}}
            <div class="ss-form-group col-md-12">
                <label>Clinical Setting</label>
                <select name="clinical_setting" id="clinical_setting">
                    <option value="" disabled selected hidden>Select a setting
                    </option>
                    @foreach ($allKeywords['ClinicalSetting'] as $value)
                        <option value="{{ $value->title }}">{{ $value->title }}
                        </option>
                    @endforeach
                </select>
                <div> 
                <span class="help-block-clinical_setting"></span>
                </div>
            </div>
            
            {{-- Address --}}
            <div class="ss-form-group col-md-12">
                <label>Facilty address</label>
                <input type="text" name="preferred_work_location"
                    id="preferred_work_location" placeholder="Enter Facilty address">
                <div> 
                    <span class="help-block-preferred_work_location"></span>
                </div>
            </div>

            {{-- Facility --}}

            <div class="ss-form-group col-md-12">
                <label>Facility Name</label>
                <input type="text" name="facility_name" id="facility_name"
                    placeholder="Enter facility name">
                <div> 
                    <span class="help-block-facility_name"></span>
                </div>
            </div>

            {{-- Facility's Parent System  --}}

            <div class="ss-form-group col-md-12">
                <label>Facility's Parent System</label>
                <input type="text" name="facilitys_parent_system"
                    id="facilitys_parent_system"
                    placeholder="Enter facility's parent system">
                <div> 
                    <span class="help-block-facilitys_parent_system"></span>
                </div>
            </div>
            {{-- Shift Cancellation Policy --}}
            <div class="ss-form-group col-md-12">
                <label>Shift Cancellation Policy</label>
                <input type="text" name="facility_shift_cancelation_policy"
                    id="facility_shift_cancelation_policy"
                    placeholder="Select your shift cancellation policy">
                <div> 
                    <span class="help-block-facility_shift_cancelation_policy"></span>
                </div>
            </div>
            {{-- Contract Termination Policy --}}
            <div class="ss-form-group col-md-12">
                <label>Contract Termination Policy</label>
                <input type="text" name="contract_termination_policy"
                    id="contract_termination_policy"
                    placeholder="Enter your contract termination policy">
                <div> 
                    <span class="help-block-contract_termination_policy"></span>
                </div>
            </div>

            {{-- Min Miles Must Live From Facility --}}
            <div class="ss-form-group col-md-">
                <label>Min distance from facility</label>
                <input type="number" name="traveler_distance_from_facility"
                    id="traveler_distance_from_facility"
                    placeholder="Enter travel distance">
                <div> 
                    <span class="help-block-traveler_distance_from_facility"></span>
                </div>
            </div>

        </div>

        <div class="col-md-12 mt-4 collapse-container">
            <p>
                <a class="btn first-collapse" data-toggle="collapse" href="#collapse-4" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Certs & Licences 
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row collapse" id="collapse-4">
            {{--  Professional Licensure --}}
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
                <div> 
                <span class="help-block-professional_licensure"></span>
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
                <div> 
                <span class="help-block-certificate"></span>
                </div>
            </div>


        </div>
        <div class="col-md-12 mt-4 collapse-container">
            <p>
                <a class="btn first-collapse" data-toggle="collapse" href="#collapse-5" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Work Info
                </a>
            </p>
        </div>
            <div style="padding:0px; margin:0px;" class="row collapse" id="collapse-5">
            {{-- Description --}}
            <div class="ss-form-group col-md-12">
                <label>Description</label>
                <textarea type="text" name="description" id="description" placeholder="Enter Work Description"></textarea>
                <div> 
                <span class="help-block-description"></span>
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
                        <input type="checkbox" name="urgency" id="urgency"
                            value="Auto Offer" style="box-shadow: none;">
                    </div>
                </div>
                
                <div> 
                <span class="help-block-urgency"></span>
                </div>
            </div>
           
            {{-- Experience --}}
            <div class="ss-form-group col-md-12">
                <label>Experience (Yrs)</label>
                <input type="number" name="preferred_experience"
                    id="preferred_experience" placeholder="Enter Experience">
                <div> 
                    <span class="help-block-preferred_experience"></span>
                </div>
            </div>
            {{-- number of references --}}
            <div class="ss-form-group col-md-12">
                <label>Number Of References</label>
                <input type="number" name="number_of_references"
                    id="number_of_references" placeholder="Enter number of references">
                <div> 
                    <span class="help-block-number_of_references"></span>
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
                <div> 
                <span class="help-block-skills"></span>
                </div>
            </div>
            {{-- Block scheduling --}}
            <div class="ss-form-group col-md-12">
                <label>Block scheduling</label>
                <select name="block_scheduling" id="block_scheduling">
                    <option value="" disabled selected hidden>Select an answer
                    </option>
                    <option value="1">Yes
                    </option>
                    <option value="0">No
                    </option>
                </select>
                <div> 
                <span class="help-block-block_scheduling"></span>
                </div>
            </div>
            {{-- Floating requirements --}}
            <div class="ss-form-group col-md-12">
                <label>Floating requirements</label>
                <select name="float_requirement" id="float_requirement">
                    <option value="" disabled selected hidden>Select an answer
                    </option>
                    <option value="1">Yes
                    </option>
                    <option value="0">No
                    </option>
                </select>
                <div> 
                <span class="help-block-float_requirement"></span>
                </div>
            </div>
            {{-- Patient Ratio Max --}}
            <div class="ss-form-group col-md-12">
                <label>Patient ratio Max</label>
                <input type="number" name="Patient_ratio" id="Patient_ratio"
                    placeholder="Enter Patient ratio">
                <div> 
                    <span class="help-block-Patient_ratio"></span>
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
                <div> 
                <span class="help-block-Emr"></span>
                </div>
            </div>

            {{-- Unit --}}

            <div class="ss-form-group col-md-12">
                <label>Unit</label>
                <input type="text" name="Unit" id="Unit"
                    placeholder="Enter Unit">
                <div> 
                    <span class="help-block-Unit"></span>
                </div>
            </div>

            </div>
            <div class="col-md-12 mt-4 collapse-container">
                <p>
                    <a class="btn first-collapse" data-toggle="collapse" href="#collapse-6" role="button" aria-expanded="false" aria-controls="collapseExample">
                        ID & Tax Info
                    </a>
                </p>
            </div>
            <div style="padding:0px; margin:0px;" class="row collapse" id="collapse-6">

                {{-- Classification --}}
            <div class="ss-form-group ss-prsnl-frm-specialty">
                <label>Classification</label>
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
                <div> 
                <span class="help-block-nurse_classification"></span>
                </div>
            </div>
           
            </div>
            <div class="col-md-12 mt-4 collapse-container">
                <p>
                    <a class="btn first-collapse" data-toggle="collapse" href="#collapse-7" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Medical info
                    </a>
                </p>
            </div>
            <div style="padding:0px; margin:0px;" class="row collapse" id="collapse-7">

            

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
                    <div> 
                    <span class="help-block-vaccinations"></span>
                    </div>
                </div>


                 </div>
                 <div class="col-md-12 mt-4 collapse-container">
                    <p>
                        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-8" role="button" aria-expanded="false" aria-controls="collapseExample">
                            Other Info
                        </a>
                    </p>
                </div>
            <div style="padding:0px; margin:0px;" class="row collapse" id="collapse-8">

                <div class="ss-form-group col-md-12">
                    <label>Scrub Color</label>
                    <input type="text" name="scrub_color" id="scrub_color"
                        placeholder="Enter scrub color">
                    <div> 
                        <span class="help-block-scrub_color"></span>
                    </div>
                </div>
                
               
                <div class="ss-form-group col-md-12">
                    <label>Name</label>
                    <input type="text" name="job_name" id="job_name"
                        placeholder="Enter Work name">
                    <div> 
                        <span class="help-block-job_name"></span>
                    </div>
                </div>
            
                    <div class="ss-form-group col-md-12">
                        <label>Holidy date</label>
                        <input type="date" name="holiday" id="holiday"
                            placeholder="Enter Holidy hourly rate">
                        <div> 
                            <span class="help-block-holiday"></span>
                        </div>
                    </div>                  
                      
                    <div class="row d-flex justify-content-center col-md-12" style="padding-bottom: 20px;">
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
                            <div> 
                            <span class="help-block-professional_state_licensure"></span>
                            </div>
                    </div>
                </div>

                <div class="ss-counter-buttons-div">
                    <button class="ss-counter-button" id="ss-reject-offer-btn" onclick="offerSend(event)">Save</button>
                </div>
        </div>
    </div>    
</form>


@include('recruiter::partials.multiselectFunctions')

<script>

    $(document).ready(function() {
        getOfferDataToEdit();
    });



</script>


<style>
    .btn.first-collapse,
    .btn.first-collapse:hover,
    .btn.first-collapse:focus,
    .btn.first-collapse:active 
    {
        /* background-color: rgb(255, 237, 238); */
        background-color: #fff8fd;
        color: rgb(65, 41, 57);
        font-size: 14px;
        font-family: 'Neue Kabel';
        font-style: normal;
        width: 100%;
    }  
    </style>  