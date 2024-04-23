@extends('worker::layouts.main')
@section('mytitle', 'My Profile')
@section('content')
    @php
        $user = auth()->guard('frontend')->user();
    @endphp
    <!--Main layout-->
    <main style="padding-top: 130px; padding-bottom: 100px;" class="ss-main-body-sec">
        <div class="container">
            <div class="ss-my-profile--basic-mn-sec">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="ss-my-profil-div">
                            <h2>My <span class="ss-pink-color">Profile</span></h2>
                            <div class="ss-my-profil-img-div">
                                <img src="{{ URL::asset('frontend/img/account-img.png') }}"
                                    onerror="this.onerror=null;this.src='{{ USER_IMG }}';" id="preview"
                                    width="112px" height="112px" style="object-fit: cover;" />
                                <h4>{{$user->first_name}} {{$user->last_name}}</h4>
                                <p>{{$worker->id}}</p>
                            </div>
                            <div class="ss-profil-complet-div">
                               <div class="row d-flex justify-content-center align-items-center ">
                                    {{-- <li><img src="{{ URL::asset('frontend/img/progress.png') }}" /></li> --}}
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 m-0 p-0">
                                        <svg  viewBox="-25 -25 250 250" version="1.1" xmlns="http://www.w3.org/2000/svg" style="transform:rotate(-90deg)">
                                          <circle r="90" cx="100" cy="100" fill="transparent" stroke="#e9d1e2" stroke-width="16px" stroke-dasharray="565.48px" stroke-dashoffset="0"></circle>
                                          <circle r="90" cx="100" cy="100" stroke="#ad66a3" stroke-width="16px" stroke-linecap="round" stroke-dashoffset="118.692px" fill="transparent" stroke-dasharray="565.48px"></circle>
                                          <text x="71px" y="115px" fill="#3d2c39" font-size="40px" font-weight="bold" style="transform:rotate(90deg) translate(0px, -196px)">{{$progress_percentage}}%</text>
                                        </svg>
                                    </div>
                                    {{-- if the profile is not complete --}}
                                   <div id="profile_incomplete" class="row col-lg-9 col-md-6 col-sm-12 col-xs-12 p-0">
                                            <div class="col-12">
                                                <h6>Profile Incomplete</h6>
                                            </div>
                
                                            <div class="col-12">
                                                    <p>You're just a few percent away from revenue. Complete your profile and get 5%.
                                                    </p>
                                            </div>  
                                    </div>
                                    {{-- if the profile is complete --}}
                                    <div id="profile_complete" class="row col-lg-9 col-md-6 col-sm-12 col-xs-12 p-0 d-none">
                                        <div class="col-12">
                                            <h6>Profile complete</h6>
                                        </div>
                                        <div class="col-12">
                                                <p>Congratulations! Your profile is complete. You can now start earning.</p>
                                                </p>
                                        </div>  
                                    </div>
                                </div>
                            </div>

                            <div class="ss-my-presnl-btn-mn">

                                <div class="ss-my-prsnl-wrapper" >
                                    <div class="ss-my-prosnl-rdio-btn">
                                        <input type="radio" name="select" id="option-1" checked onclick="ProfileIinformationDisplay()"/>
                                        <label for="option-1" class="option option-1">
                                            <div class="dot"></div>
                                            <ul>
                                                <li><img src="{{ URL::asset('frontend/img/my-per--con-user.png') }}" /></li>
                                                <li>
                                                    <p>Profile settings</p>
                                                </li>
                                                <li><span class="img-white"><img
                                                            src="{{ URL::asset('frontend/img/arrowcircleright.png') }}" /></span>
                                                </li>
                                            </ul>
                                        </label>
                                    </div>

                                    <div class="ss-my-prosnl-rdio-btn" >
                                        <input type="radio" name="select" id="option-2" onclick="AccountSettingDisplay()">
                                        <label for="option-2" class="option option-2">
                                            <div class="dot"></div>
                                            <ul>
                                                <li><img src="{{ URL::asset('frontend/img/my-per--con-vaccine.png') }}" />
                                                </li>
                                                <li>
                                                    <p>Account settings</p>
                                                </li>
                                                <li><span class="img-white"><img
                                                            src="{{ URL::asset('frontend/img/arrowcircleright.png') }}" /></span>
                                                </li>
                                            </ul>
                                        </label>
                                    </div>

                                    <div class="ss-my-prosnl-rdio-btn">
                                        <input type="radio" name="select" id="option-3" onclick="BonusTransfersDisplay()">
                                        <label for="option-3" class="option option-3">
                                            <div class="dot"></div>
                                            <ul>
                                                <li><img src="{{ URL::asset('frontend/img/my-per--con-refren.png') }}" />
                                                </li>
                                                <li>
                                                    <p>Bonus Transfers</p>
                                                </li>
                                                <li><span class="img-white"><img
                                                            src="{{ URL::asset('frontend/img/arrowcircleright.png') }}" /></span>
                                                </li>
                                            </ul>
                                        </label>
                                    </div>

                                    <div class="ss-my-prosnl-rdio-btn">
                                        <input type="radio" name="select" id="option-4" onclick="SupportDisplay()">
                                        <label for="option-4" class="option option-4">
                                            <div class="dot"></div>
                                            <ul>
                                                <li><img src="{{ URL::asset('frontend/img/my-per--con-key.png') }}" /></li>
                                                <li>
                                                    <p>Support</p>
                                                </li>
                                                <li><span class="img-white"><img
                                                            src="{{ URL::asset('frontend/img/arrowcircleright.png') }}" /></span>
                                                </li>
                                            </ul>
                                        </label>
                                    </div>


                                    <div class="ss-my-prosnl-rdio-btn">
                                        <input type="radio" name="select" id="option-5" onclick="DisactivateAccountDisplay()">
                                        <label for="option-5" class="option option-5">
                                            <div class="dot"></div>
                                            <ul>
                                                <li><img src="{{ URL::asset('frontend/img/my-per--con-key.png') }}" /></li>
                                                <li>
                                                    <p>Disable account</p>
                                                </li>
                                                <li><span class="img-white"><img
                                                            src="{{ URL::asset('frontend/img/arrowcircleright.png') }}" /></span>
                                                </li>
                                            </ul>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{------------------------------------------------------------ Profile settings Form ------------------------------------------------------------}}
                    <div class="col-lg-7 bodyAll profile_setting">
                        <div class="ss-pers-info-form-mn-dv" >

                            <div class="ss-persnl-frm-hed" >
                                {{-- Basic Information Or Professional Information Or Document management --}}
                                <p id="information_type"><span><img
                                            src="{{ URL::asset('frontend/img/my-per--con-user.png') }}" /></span>Basic
                                    Information</p>
                                <div class="progress">
                                    <div id="progress" class="progress-bar" role="progressbar" style="width: 33%"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="form-outer">
                                <form method="post" action="{{ route('update-worker-profile') }}">
                                    {{-- <form> --}}
                                    @csrf
                                    <!-- first form slide Basic Information -->
                                    <div class="page slide-page">
                                        <div class="row justify-content-center">
                                            {{-- First Name --}}
                                            <div class="ss-form-group col-11">
                                                <label>First Name</label>
                                                <input type="text" name="first_name"
                                                    placeholder="Please enter your first name">
                                            </div>
                                            <span class="help-block-first_name"></span>
                                            {{-- Last Name --}}
                                            <div class="ss-form-group col-11">
                                                <label>Last Name</label>
                                                <input type="text" name="last_name"
                                                    placeholder="Please enter your last name">
                                            </div>
                                            <span class="help-block-last_name"></span>
                                            {{-- Phone Number --}}
                                            <div class="ss-form-group col-11">
                                                <label>Phone Number</label>
                                                <input id="contact_number" type="text" name="mobile"
                                                    placeholder="Please enter your phone number">
                                            </div>
                                            <span class="help-block-mobile"></span>
                                            {{-- Address Information --}}
                                            <div class="ss-form-group col-11">
                                                <label>Address</label>
                                                <input type="text" name="address"
                                                    placeholder="Please enter your address">
                                            </div>
                                            <span class="help-block-address"></span>
                                            {{-- State Information --}}
                                            <div class="ss-form-group col-11">
                                                <label>State</label>
                                                <select name="state" id="job_state">
                                                    <option value="">What State are you located in?</option>
                                                    @foreach ($states as $state)
                                                        <option id="{{ $state->id }}" value="{{ $state->name }}">
                                                            {{ $state->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="help-block-state"></span>
                                             {{-- City Information --}}
                                             <div class="ss-form-group col-11">
                                                <label>City</label>
                                                <select name="city" id="job_city">
                                                    <option value="">What City are you located in?</option>
                                                </select>
                                            </div>
                                            <span class="help-block-city"></span>
                                            <span class="help-city">Please select a state first</span>
                                            {{-- Zip Code Information --}}
                                            <div class="ss-form-group col-11">
                                                <label>Zip Code</label>
                                                <input type="text" name="zip_code"
                                                    placeholder="Please enter your Zip Code">
                                            </div>
                                            <span class="help-block-zip_code"></span>
                                            {{-- Skip && Save --}}
                                            <div class="ss-prsn-form-btn-sec col-11">
                                                <button type="text" class="ss-prsnl-save-btn" id="SaveBaiscInformation"> Save
                                                </button>
                                                <button type="text" class="ss-prsnl-save-btn firstNext"> Next
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end first form slide Basic Information -->

                                    <!-- second form slide Professional Information -->
                                    <div class="page slide-page">
                                        <div class="row justify-content-center">
                                            {{-- Profession --}}
                                            <div class="ss-form-group col-11">
                                                <label>Profession</label>
                                                <select name="profession" id="profession">
                                                    <option value="">What Kind of Professional are you?</option>
                                                    @foreach ($proffesions as $proffesion)
                                                        <option value="{{ $proffesion->full_name }}">
                                                            {{ $proffesion->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="help-block-profession"></span>
                                            {{-- Specialty --}}
                                            <div class="ss-form-group  col-11">
                                                <label>Specialty</label>
                                                <select name="specialty" id="specialty">
                                                    <option value="">Select Specialty</option>
                                                    @foreach ($specialities as $specialty)
                                                        <option value="{{ $specialty->full_name }}">
                                                            {{ $specialty->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="help-block-specialty"></span>
                                            {{-- Terms --}}
                                            <div class="ss-form-group col-11">
                                                <label>Terms</label>
                                                <select name="terms" id="term">
                                                    <option value="">Select a specefic term</option>
                                                    @if (isset($allKeywords['Terms']))
                                                        @foreach ($allKeywords['Terms'] as $value)
                                                            <option value="{{ $value->id }}">{{ $value->title }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <span class="help-block-terms"></span>
                                            {{-- Type --}}

                                            <div class="ss-form-group col-11">
                                                <label>Type</label>
                                                <select name="type" id="type">
                                                    <option value="">Select Type</option>
                                                    @if (isset($allKeywords['Type']))
                                                        @foreach ($allKeywords['Type'] as $value)
                                                            <option value="{{ $value->title }}">{{ $value->title }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <span class="help-block-type"></span>
                                            {{-- end Type --}}

                                            {{-- Block scheduling --}}
                                            <div class="ss-form-group row justify-content-center align-items-center col-11"
                                                style="margin-top:36px;">
                                                <label class="col-lg-6 col-sm-8 col-xs-8 col-md-8">Block scheduling</label>
                                                <input style="box-shadow:none; width: auto;"
                                                    class="col-lg-6 col-sm-2 col-xs-2 col-md-2" type="radio"
                                                    id="option1" name="block_scheduling" value="1"
                                                    autocompleted="" disabled>
                                            </div>
                                            {{-- end scheduling --}}
                                            {{-- Float requirements --}}
                                            <div class="ss-form-group col-11">
                                                <label>Float requirements</label>
                                                <select name="float_requirement" class="float_requirement mb-3"
                                                    id="float_requirement" value="">
                                                    <option value="">Select Float requirements</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>
                                            <span class="help-block-float_requirement"></span>
                                            {{-- end Float requirements --}}
                                            {{-- Facility Shift Cancellation Policy --}}
                                            <div class="ss-form-group  col-11">
                                                <label>Facility Shift Cancellation Policy</label>
                                                <select name="facility_shift_cancelation_policy"
                                                    class="facility_shift_cancelation_policy mb-3"
                                                    id="facility_shift_cancelation_policy" value="">
                                                    <option value="">Select Facility Shift Cancellation Policy
                                                    </option>
                                                    @if (isset($allKeywords['AssignmentDuration']))
                                                        @foreach ($allKeywords['AssignmentDuration'] as $value)
                                                            <option value="{{ $value->id }}">{{ $value->title }}
                                                            </option>;
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <span class="help-block-facility_shift_cancelation_policy"></span>
                                            {{-- End Facility Shift Cancellation Policy --}}
                                            {{-- Contract Termination Policy --}}
                                            <div class="ss-form-group col-11">
                                                <label>Contract Termination Policy</label>
                                                <input type="text" id="contract_termination_policy"
                                                    name="contract_termination_policy"
                                                    placeholder="Enter Contract Termination Policy">
                                            </div>
                                            <span class="help-block-contract_termination_policy"></span>
                                            {{-- end Contract Termination Policy --}}
                                            {{-- Traveler Distance From Facility --}}
                                            <div class="ss-form-group col-11">
                                                <label>Traveler Distance From Facility</label>
                                                <input type="number" id="traveler_distance_from_facility"
                                                    name="distance_from_your_home"
                                                    placeholder="Enter Traveler Distance From Facility">
                                            </div>
                                            <span class="help-block-traveler_distance_from_facility"></span>
                                            {{-- end Traveler Distance From Facility  --}}
                                            {{-- Clinical Setting --}}
                                            <div class="ss-form-group col-11">
                                                <label>Clinical Setting</label>
                                                <input type="text" id="clinical_setting"
                                                    name="clinical_setting_you_prefer"
                                                    placeholder="Enter clinical setting">
                                            </div>
                                            <span class="help-block-clinical_setting_you_prefer"></span>
                                            {{-- End Clinical Setting --}}
                                            {{-- Patient ratio --}}
                                            <div class="ss-form-group col-11">
                                                <label>Patient ratio</label>
                                                <input type="number" id="Patient_ratio" name="worker_patient_ratio"
                                                    placeholder="How many patients can you handle?">
                                            </div>
                                            <span class="help-block-worker_patient_ratio"></span>
                                            {{-- End Patient ratio --}}
                                            {{-- EMR --}}
                                            <div class="ss-form-group col-11">
                                                <label>EMR</label>
                                                <select name="worker_emr" class="emr mb-3" id="emr">
                                                    <option value="">Select EMR</option>
                                                    @if (isset($allKeywords['EMR']))
                                                        @foreach ($allKeywords['EMR'] as $value)
                                                            <option value="{{ $value->id }}">{{ $value->title }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <span class="help-block-worker_emr"></span>
                                            {{-- End EMR --}}
                                            {{-- Unit --}}
                                            <div class="ss-form-group col-11">
                                                <label>Unit</label>
                                                <input id="Unit" type="text" name="worker_unit"
                                                    placeholder="Enter Unit">
                                            </div>
                                            <span class="help-block-worker_unit"></span>
                                            {{-- End Unit --}}
                                            {{-- Scrub Color --}}
                                            <div class="ss-form-group col-11">
                                                <label>Scrub Color</label>
                                                <input id="scrub_color" type="text" name="worker_scrub_color"
                                                    placeholder="Enter Scrub Color">
                                            </div>
                                            <span class="help-block-worker_scrub_color"></span>
                                            {{-- End Scrub Color --}}
                                            {{-- RTO --}}
                                            <div class="ss-form-group col-11">
                                                <label>RTO</label>
                                                <input id="rto" type="text" name="rto"
                                                    placeholder="Enter RTO">
                                            </div>
                                            <span class="help-block-rto"></span>
                                            {{-- End RTO --}}
                                            {{-- Shift Time of Day --}}
                                            <div class="ss-form-group col-11">
                                                <label>Shift Time of Day</label>
                                                <select name="worker_shift_time_of_day" id="shift-of-day">
                                                    <option value="">Enter Shift Time of Day</option>
                                                    @if (isset($allKeywords['PreferredShift']))
                                                        @foreach ($allKeywords['PreferredShift'] as $value)
                                                            <option value="{{ $value->id }}">{{ $value->title }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <span class="help-block-worker_shift_time_of_day"></span>
                                            {{-- End Shift Time of Day --}}
                                            {{-- Hours/Week --}}
                                            <div class="ss-form-group col-11">
                                                <label>Hours/Week</label>
                                                <input id="hours_per_week" type="number" name="worker_hours_per_week"
                                                    placeholder="Enter Hours/Week">
                                            </div>
                                            <span class="help-block-worker_hours_per_week"></span>
                                            {{-- End Hours/Week --}}
                                            {{-- Hours/Shift --}}
                                            <div class="ss-form-group col-11">
                                                <label>Hours/Shift</label>
                                                <input id="hours_shift" type="number" name="worker_hours_per_shift"
                                                    placeholder="Enter Hours/Shift">
                                            </div>
                                            <span class="help-block-worker_hours_per_shift"></span>
                                            {{-- End Hours/Shift --}}
                                            {{-- Weeks/Assignment --}}
                                            <div class="ss-form-group col-11">
                                                <label>Weeks/Assignment</label>
                                                <input id="preferred_assignment_duration" type="number"
                                                    name="worker_weeks_assignment" placeholder="Enter Weeks/Assignment">
                                            </div>
                                            <span class="help-block-worker_weeks_assignment"></span>
                                            {{-- End Weeks/Assignment --}}
                                            {{-- Shifts/Week --}}
                                            <div class="ss-form-group col-11">
                                                <label>Shifts/Week</label>
                                                <input id="weeks_shift" type="number" name="worker_shifts_week"
                                                    placeholder="Enter Shifts/Week">
                                            </div>
                                            <span class="help-block-worker_shifts_week"></span>
                                            {{-- End Shifts/Week --}}
                                            {{-- Skip && Save --}}
                                            <div class="ss-prsn-form-btn-sec row col-11" style="gap:0px;">
                                                <div class="col-4" >
                                                <button type="text" class="ss-prsnl-skip-btn prev-1 btns_prof_info"> Previous
                                                </button>
                                                </div>
                                                <div class="col-4">
                                                <button type="text" class="ss-prsnl-save-btn next-1 btns_prof_info"> Next
                                                </button>
                                                </div>
                                                <div class="col-4">
                                                <button type="text" class="ss-prsnl-save-btn btns_prof_info" id="SaveProfessionalInformation"> Save
                                                </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end second form slide (Profession, Slide) -->

                                    <!-- Third form slide Document management -->
                                    <div class="page slide-page ">
                                        <div class="row justify-content-center">
                                            {{-- Upload Document --}}
                                            <div class="ss-form-group">
                                                <label>Upload Document</label>
                                                <input type="file" id="file" name="files" multiple
                                                    required><br><br>
                                                <label class="mt-2" for="file">Choose a file</label>
                                            </div>
                                            <span class="help-block-file"></span>
                                            {{-- manage file table --}}
                                            <table style="font-size: 16px;" class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Document Name</th>
                                                        <th scope="col">Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>

                                            {{-- Skip && Save --}}
                                            <div class="ss-prsn-form-btn-sec">
                                                <button type="text" class="ss-prsnl-skip-btn prev-2"> Previous
                                                </button>
                                                <button id="uploadForm" class="ss-prsnl-save-btn next-2"> Save Files
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end Third form slide Document management -->
                                    <div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                     {{------------------------------------------------------------ End Profile settings Form ------------------------------------------------------------}}
                    {{------------------------------------------------------------ Account settings Form ------------------------------------------------------------}}

                    <div class="col-lg-7 bodyAll account_setting d-none">
                        <div class="ss-pers-info-form-mn-dv" >
                            <div class="ss-persnl-frm-hed" >
                                <p><span><img
                                            src="{{ URL::asset('frontend/img/my-per--con-user.png') }}" /></span>Account
                                    Setting</p>
                            </div>
                            <div class="form-outer">
                                <form method="post" action="{{ route('update-worker-profile') }}">
                                    @csrf
                                    <!-- slide Account Setting -->
                                    <div class="page slide-page">
                                        <div class="row justify-content-center">
                                            {{-- Change User Name --}}
                                            <div class="ss-form-group col-11">
                                                <label>New User Name</label>
                                                <input type="text" name="user_name" placeholder="Please enter your new user name">
                                            </div>
                                            <span class="help-block-user_name"></span>
                                            {{-- Change Password --}}
                                            <div class="ss-form-group col-11">
                                                <label>New Password</label>
                                                <input type="text" name="password" placeholder="Please enter your new password">
                                            </div>
                                            {{-- Change 2FA --}}
                                            <div class="ss-form-group row col-11">
                                                <label>Two-factor authentication (2FA)</label>
                                                <div class="col-lg-6 col-sm-2 col-xs-2 col-md-2">
                                                <label>Enable</label>
                                                <input style="box-shadow:none; width: auto;"
                                                     type="radio"
                                                    id="option1" name="twoFa" value="1"
                                                >
                                                </div>
                                                <div class="col-lg-6 col-sm-2 col-xs-2 col-md-2">
                                                <label>Disable</label>
                                                <input style="box-shadow:none; width: auto;"
                                                     type="radio"
                                                    id="option2" name="twoFa" value="0"
                                                >
                                                </div>
                                            </div>
                                            {{-- Change Phone Number --}}
                                            <div class="ss-form-group col-11">
                                                <label>New Phone Number</label>
                                                <input id="new_contact_number" type="text" name="new_mobile" placeholder="Please enter your new phone number">
                                            </div>
                                            <span class="help-block-new_mobile"></span>
                                            {{-- Email Information --}}
                                            <div class="ss-form-group col-11">
                                                <label>New Email</label>
                                                <input type="text" name="email" placeholder="Please enter your new Email">
                                            </div>
                                            <span class="help-block-email"></span>
                                            <span class="help-block-validation"></span>
                                            {{-- Skip && Save --}}
                                            <div class="ss-prsn-form-btn-sec row col-11 d-flex justify-content-center align-items-center">
                                                <button type="text" class=" col-12 ss-prsnl-save-btn" id="SaveAccountInformation"> Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                           
                        </div>    
                    </div>
                    {{------------------------------------------------------------ End Account settings  ------------------------------------------------------------}}
                    {{------------------------------------------------------------  Bonus Area ----------------------------------------------------------------------}}
                    <div class="col-lg-7 bodyAll bonus_transfers d-none">
                        <div class="ss-pers-info-form-mn-dv" >
                            <div class="ss-persnl-frm-hed" >
                                <p><span><img
                                            src="{{ URL::asset('frontend/img/my-per--con-user.png') }}" /></span>Bonus
                                    Transfers</p>
                            </div>
                            <div class="form-outer">
                                {{-- <form method="post" action="{{ route('update-worker-profile') }}"> --}}
                                {{-- <form method="post" action="{{ route('update-bonus-transfer') }}">  --}}
                                <form method="post">  
                                    @csrf
                                    <!-- slide Bonus Transfer -->
                                    <div class="page slide-page">
                                        <div class="row justify-content-center">
                                            {{-- Full name payment--}}
                                            <div class="ss-form-group col-11">
                                                <label>Full Name</label>
                                                <input type="text" name="full_name_payment" placeholder="Please enter your full name">
                                            </div>
                                            <span class="help-block-full_name_payment"></span>
                                            {{-- Address payment --}}
                                            <div class="ss-form-group col-11">
                                                <label>Address</label>
                                                <input type="text" name="address_payment" placeholder="Please enter your address">
                                            </div>
                                            <span class="help-block-address_payment"></span>
                                            {{-- Email --}}
                                            <div class="ss-form-group row col-11">
                                                <label>Email</label>
                                                <input type="text" name="email_payment" placeholder="Please enter your new Email">
                                            </div>
                                            <span class="help-block-email_payment"></span>
                                            {{-- Bank Name --}}
                                            <div class="ss-form-group col-11">
                                                <label>Bank Name</label>
                                                <input id="bank_name_payment" type="text" name="bank_name_payment" placeholder="Please enter your bank name">
                                            </div>
                                            <span class="help-block-bank_name_payment"></span>
                                            {{-- Rooting Number Information --}}
                                            <div class="ss-form-group col-11">
                                                <label>Routing number payment</label>
                                                <input id="routing_number_payment" type="text" name="routing_number_payment" placeholder="Please enter your routing number payment">
                                            </div>
                                            <span class="help-block-routing_number_payment"></span>
                                            {{-- Bank Account Payment Number --}}
                                            <div class="ss-form-group col-11">
                                                <label>Bank Account Payment Number</label>
                                                <input id="bank_account_payment_number" type="text" name="bank_account_payment_number" placeholder="Please enter your bank account payment number">
                                            </div>
                                            <span class="help-block-bank_account_payment_number"></span>
                                            {{-- Phone Number --}}
                                            <div class="ss-form-group col-11">
                                                <label>Phone Number</label>
                                                <input id="phone_number_payment" type="text" name="phone_number_payment" placeholder="Please enter your phone number">
                                            </div>
                                            <span class="help-block-phone_number_payment"></span>
                                            {{-- Skip && Save --}}
                                            <div class="ss-prsn-form-btn-sec row col-11 d-flex justify-content-center align-items-center">
                                                <button type="text" class=" col-12 ss-prsnl-save-btn" id="SaveBonusInformation"> Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                           
                        </div>    
                    </div>
                    {{------------------------------------------------------------  End Bonus Area ----------------------------------------------------------------------}}
                       {{------------------------------------------------------------  Support Area ----------------------------------------------------------------------}}
                       <div class="col-lg-7 bodyAll support_info d-none">
                        <div class="ss-pers-info-form-mn-dv" >
                            <div class="ss-persnl-frm-hed" >
                                <h1 style="font-family: Neue Kabel; font-size: 32px; font-weight: 500; line-height: 34px; text-align: center;color:3D2C39;">
                                Help &Support
                                </h1>
                            </div>
                            <div class="form-outer">
                                <form method="post">  
                                    @csrf
                                    <!-- slide Support -->
                                    <div class="page slide-page">
                                        <div class="row justify-content-center">
                                            {{-- Support subject --}}
                                            <div class="ss-form-group col-11">
                                                <label>Subject</label>
                                                <select name="support_subject" id="support_subject">
                                                    <option value="">Please select your issue</option>
                                                    <option value="login">Login</option>
                                                    <option value="payment">Payment</option>
                                                    <option value="other">Other</option>
                                                </select>
                                               
                                            </div>
                                            <span class="help-block-support_subject"></span>
                                            {{-- Support issue --}}
                                            <div class="ss-form-group col-11">
                                                <label>Issue</label>
                                                <textarea style="width: 100%; height:40vh;" name="support_subject_issue" placeholder="Tell us how can we help."></textarea>
                                            </div>
                                            <span class="help-block-support_subject_issue"></span>                                                                 
                                            {{-- Skip && Save --}}
                                            <div class="ss-prsn-form-btn-sec row col-11 d-flex justify-content-center align-items-center">
                                                <button type="text" class=" col-12 ss-prsnl-save-btn" id="SaveSupportTicket">
                                                    {{-- spinner --}}
                                                    <span id="loading" class="d-none" >
                                                        <span id="loadSpan" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                            Loading...
                                                    </span>
                                                    <span id="send_ticket">Send now</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                           
                        </div>    
                    </div>
                          {{--------------------------------------------------------- End Support Area ----------------------------------------------------------------------}}

                          {{--------------------------------------------------------- Disable account area ----------------------------------------------------------------------}}
                          <div class="col-lg-7 bodyAll disable_account d-none">
                            <div class="ss-pers-info-form-mn-dv" >
                                <div class="ss-persnl-frm-hed" >
                                    <p><span><img
                                                src="{{ URL::asset('frontend/img/my-per--con-user.png') }}" /></span>Disactivate your 
                                        account</p>
                                </div>
                                <div class="form-outer">
                                    <form method="post">  
                                        @csrf
                                        <!-- slide Support -->
                                        <div class="page slide-page">
                                            <div class="row justify-content-center">                                                                 
                                                {{-- Disactivate your account --}}
                                                <div class="ss-prsn-form-btn-sec row col-11 d-flex justify-content-center align-items-center">
                                                    
                                                    <button type="text" class="col-12 ss-prsnl-save-btn" id="DisactivateAccount">
                                                        {{-- spinner --}}
                                                        <span id="loading_disableOption" class="d-none" >
                                                            <span style="width=5%" id="loadSpan_disableOption" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                Loading...
                                                        </span>
                                                        <span id="disactivate_account">Disable</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                               
                            </div>    
                        </div>
                           {{--------------------------------------------------------- Disable account area ----------------------------------------------------------------------}}
                </div>
    </main>
@stop

@section('js')
    <script type="text/javascript">

    // loding states cities docs on page load

        $(document).ready(function() {
            $('#contact_number').mask('+1 (999) 999-9999');
            $('#new_contact_number').mask('+1 (999) 999-9999');
            $('#phone_number_payment').mask('+1 (999) 999-9999');
            $('#routing_number_payment').mask('999-999-999');
            $('#bank_account_payment_number').mask('9999-9999-9999');
            // solution of the case that we got - when we type a caracter : 
            // $('#bank_account_payment_number').on('input', function() {
            // var inputValue = $(this).val();
            // var numericValue = inputValue.replace(/[^0-9]/g, '');
            // var formattedValue = numericValue.replace(/(\d{4})(\d{4})(\d{4})/, '$1-$2-$3');
            // $(this).val(formattedValue);
            // });
            // or we should use a nother plugin
           
            
            let account_tier = '{{ $progress_percentage }}';
            if(account_tier > 67){
                document.getElementById('profile_incomplete').classList.add('d-none');
                document.getElementById('profile_complete').classList.remove('d-none');
            }
    // -----------------------------  Profile Setting area  ---------------------------- //

            // loading cities according to the selected state
            $('#job_state').change(function() {
                const selectedState = $(this).find(':selected').attr('id');
                const CitySelect = $('#job_city');

                $.get(`/api/cities/${selectedState}`, function(data) {
                    CitySelect.empty();
                    CitySelect.append('<option value="">Select City</option>');
                    $.each(data, function(index, city) {
                        CitySelect.append(new Option(city.name, city.name));
                    });
                    document.querySelector('.help-city').style.display = 'none';
                });
            });
            // end loading cities according to the selected state

            // append each uploaded file to the table
            $('input[type="file"]').change(function() {
                var file = this.files[0]; // get the selected file
                var tbody = $('.table tbody');
                // tbody.empty(); // remove existing rows
                var row = $('<tr>');
                row.append($('<td>').text(file.name)); // display the file name
                var deleteButton = $('<button>').text('Delete Document').addClass('delete').attr('data-id',
                    file.id).prop('disabled', true); // disable the delete button
                row.append($('<td>').append(deleteButton));
                tbody.append(row);
            });
            // end loding uploading file

            // loding docs list and dispatch them on the table (consume api : /list-docs)
            @php
                $worker_id = $worker->id;
            @endphp
            const worker_id = '{!! $worker_id !!}';
            $.ajax({
                url: 'http://localhost:4545/documents/list-docs?workerId=' +
                    worker_id, // replace workerId with the actual workerId
                method: 'GET',
                success: function(resp) {
                    var tbody = $('.table tbody');
                    tbody.empty(); // remove existing rows
                    resp.forEach(function(file) {
                        var row = $('<tr>');
                        row.append($('<td>').text(file.name));
                        console.log(file.id);
                        var deleteButton = $('<button>').text('Delete Document').addClass(
                            'delete').attr('data-id', file.id);
                        deleteButton.click(function() {
                            $.ajax({
                                url: 'http://localhost:4545/documents/del-doc',
                                method: 'POST',
                                data: JSON.stringify({
                                    bsonId: file.id
                                }),
                                contentType: 'application/json',
                                success: function() {
                                    row
                                        .remove(); // remove the row from the table
                                },
                                error: function(resp) {
                                    console.log('Error:', resp);
                                }
                            });
                        });
                        row.append($('<td>').append(deleteButton));
                        tbody.append(row);
                    });
                },
                error: function(resp) {
                    console.log('Error:', resp);
                }
            });
        });
        // end loding states cities docs on page load


        // slide control
        const slidePage = document.querySelector(".slide-page");
        const nextBtnFirst = document.querySelector(".firstNext");
        const prevBtnSec = document.querySelector(".prev-1");
        const nextBtnSec = document.querySelector(".next-1");
        const prevBtnThird = document.querySelector(".prev-2");
        const nextBtnThird = document.querySelector(".next-2");
        const progress = document.getElementById("progress");
        // end slide control
        
        // inputs
        // Basic Info
        const first_name = document.querySelector('input[name="first_name"]');
        const last_name = document.querySelector('input[name="last_name"]');
        const mobile = document.querySelector('input[name="mobile"]');
        const address = document.querySelector('input[name="address"]');
        const city = document.querySelector('select[name="city"]');
        const state = document.querySelector('select[name="state"]');
        const zip_code = document.querySelector('input[name="zip_code"]');
        // Professional Info
        const profession = document.querySelector('select[name="profession"]');
        const specialty = document.querySelector('select[name="specialty"]');
        const terms = document.querySelector('select[name="terms"]');
        const type = document.querySelector('select[name="type"]');
        const block_scheduling = document.querySelector('input[name="block_scheduling"]');
        const float_requirement = document.querySelector('select[name="float_requirement"]');
        const facility_shift_cancelation_policy = document.querySelector('select[name="facility_shift_cancelation_policy"]');
        const contract_termination_policy = document.querySelector('input[name="contract_termination_policy"]');
        const traveler_distance_from_facility = document.querySelector('input[name="distance_from_your_home"]');
        const clinical_setting = document.querySelector('input[name="clinical_setting_you_prefer"]');
        const Patient_ratio = document.querySelector('input[name="worker_patient_ratio"]');
        const emr = document.querySelector('select[name="worker_emr"]');
        const Unit = document.querySelector('input[name="worker_unit"]');
        const scrub_color = document.querySelector('input[name="worker_scrub_color"]');
        const rto = document.querySelector('input[name="rto"]');
        const shift_of_day = document.querySelector('select[name="worker_shift_time_of_day"]');
        const hours_per_week = document.querySelector('input[name="worker_hours_per_week"]');
        const hours_shift = document.querySelector('input[name="worker_hours_per_shift"]');
        const preferred_assignment_duration = document.querySelector('input[name="worker_weeks_assignment"]');
        const weeks_shift = document.querySelector('input[name="worker_shifts_week"]');
        // Document Management
        const file = document.querySelector('input[type="file"]');
        // bonus transfer 
        const full_name_payment = document.querySelector('input[name="full_name_payment"]');
        const address_payment = document.querySelector('input[name="address_payment"]');
        const email_payment = document.querySelector('input[name="email_payment"]');
        const bank_name_payment = document.querySelector('input[name="bank_name_payment"]');
        const routing_number_payment = document.querySelector('input[name="routing_number_payment"]');
        const bank_account_payment_number = document.querySelector('input[name="bank_account_payment_number"]');
        const phone_number_payment = document.querySelector('input[name="phone_number_payment"]');
        // support input
        const support_subject_issue = document.querySelector('textarea[name="support_subject_issue"]');
        const support_subject = document.querySelector('select[name="support_subject"]');
        // end inputs
        
        // change info type title
        const infoType = document.getElementById("information_type");
        // end change info type title

        var regexPhone = /^\+1 \(\d{3}\) \d{3}-\d{4}$/;
        
        // validation basic information -ELH-
        function validateBasicInfo() {
            let isValid = true;
            if (first_name.value === '') {
                $('.help-block-first_name').text('Please enter a first name');
                $('.help-block-first_name').addClass('text-danger');
                isValid = false;
            }
            if (last_name.value === '') {
                $('.help-block-last_name').text('Please enter a last name');
                $('.help-block-last_name').addClass('text-danger');
                isValid = false;
            }
        
            if ((!regexPhone.test(mobile)) &&(mobile.value === '')) {
                $('.help-block-mobile').text('Please enter a mobile number');
                $('.help-block-mobile').addClass('text-danger');
                isValid = false;
            }
            if (address.value === '') {
                $('.help-block-address').text('Please enter an address');
                $('.help-block-address').addClass('text-danger');
                isValid = false;
            }
            if (city.value === '') {
                $('.help-block-city').text('Please enter a city');
                $('.help-block-city').addClass('text-danger');
                isValid = false;
            }
            if (state.value === '') {
                $('.help-block-state').text('Please enter a state');
                $('.help-block-state').addClass('text-danger');
                isValid = false;
            }
            if (zip_code.value === '') {
                $('.help-block-zip_code').text('Please enter a zip code');
                $('.help-block-zip_code').addClass('text-danger');
                isValid = false;
            }
            return isValid;
        }
        // end validation basic information
        // validation professional information
        function validateProfessionalInfo() {
            let isValid = true;
            if (profession.value === '') {
                $('.help-block-profession').text('Please enter a profession');
                $('.help-block-profession').addClass('text-danger');
                isValid = false;
            }
            if (specialty.value === '') {
                $('.help-block-specialty').text('Please enter a specialty');
                $('.help-block-specialty').addClass('text-danger');
                isValid = false;
            }
            if (terms.value === '') {
                $('.help-block-terms').text('Please enter a term');
                $('.help-block-terms').addClass('text-danger');
                isValid = false;
            }
            if (type.value === '') {
                $('.help-block-type').text('Please enter a type');
                $('.help-block-type').addClass('text-danger');
                isValid = false;
            }
            if (float_requirement.value === '') {
                $('.help-block-float_requirement').text('Please enter a float requirement');
                $('.help-block-float_requirement').addClass('text-danger');
                isValid = false;
            }
            if (facility_shift_cancelation_policy.value === '') {
                $('.help-block-facility_shift_cancelation_policy').text('Please enter a facility shift cancelation policy');
                $('.help-block-facility_shift_cancelation_policy').addClass('text-danger');
                isValid = false;
            }
            if (contract_termination_policy.value === '') {
                $('.help-block-contract_termination_policy').text('Please enter a contract termination policy');
                $('.help-block-contract_termination_policy').addClass('text-danger');
                isValid = false;
            }
            if (traveler_distance_from_facility.value === '') {
                $('.help-block-traveler_distance_from_facility').text('Please enter a traveler distance from facility');
                $('.help-block-traveler_distance_from_facility').addClass('text-danger');
                isValid = false;
            }
            if (clinical_setting.value === '') {
                $('.help-block-clinical_setting_you_prefer').text('Please enter a clinical setting');
                $('.help-block-clinical_setting_you_prefer').addClass('text-danger');
                isValid = false;
            }
            if (Patient_ratio.value === '') {
                $('.help-block-worker_patient_ratio').text('Please enter a patient ratio');
                $('.help-block-worker_patient_ratio').addClass('text-danger');
                isValid = false;
            }
            if (emr.value === '') {
                $('.help-block-worker_emr').text('Please enter an EMR');
                $('.help-block-worker_emr').addClass('text-danger');
                isValid = false;
            }
            if (Unit.value === '') {
                $('.help-block-worker_unit').text('Please enter a unit');
                $('.help-block-worker_unit').addClass('text-danger');
                isValid = false;
            }
            if (scrub_color.value === '') {
                $('.help-block-worker_scrub_color').text('Please enter a scrub color');
                $('.help-block-worker_scrub_color').addClass('text-danger');
                isValid = false;
            }
            if (rto.value === '') {
                $('.help-block-rto').text('Please enter an RTO');
                $('.help-block-rto').addClass('text-danger');
                isValid = false;
            }
            if (shift_of_day.value === '') {
                $('.help-block-worker_shift_time_of_day').text('Please enter a shift time of day');
                $('.help-block-worker_shift_time_of_day').addClass('text-danger');
                isValid = false;
            }
            if (hours_per_week.value === '') {
                $('.help-block-worker_hours_per_week').text('Please enter a hours per week');
                $('.help-block-worker_hours_per_week').addClass('text-danger');
                isValid = false;
            }
            if (hours_shift.value === '') {
                $('.help-block-worker_hours_per_shift').text('Please enter a hours per shift');
                $('.help-block-worker_hours_per_shift').addClass('text-danger');
                isValid = false;
            }
            if (preferred_assignment_duration.value === '') {
                $('.help-block-worker_weeks_assignment').text('Please enter a preferred assignment duration');
                $('.help-block-worker_weeks_assignment').addClass('text-danger');
                isValid = false;
            }
            if (weeks_shift.value === '') {
                $('.help-block-worker_shifts_week').text('Please enter a weeks shift');
                $('.help-block-worker_shifts_week').addClass('text-danger');
                isValid = false;
            }
            return isValid;
        }
        // end validation professional information
        // validation document management
        function validateDocumentManagement() {
            let isValid = true;
            if (file.value === '') {
                $('.help-block-file').text('Please select a file');
                $('.help-block-file').addClass('text-danger');
                isValid = false;
            }
            return isValid;
        }
        // end validation document management

        // validation bonus 

        function validateBonusInfo() {
            let isValid = true;

            // Full name validation
            const fullNameRegex_payment = /^[a-zA-Z\s]{1,255}$/;
            if (($('input[name="full_name_payment"]').val() === '') && (!fullNameRegex_payment.test(full_name_payment.value)) ) {
                $('.help-block-full_name_payment').text('Full name can only contain letters and spaces, and cannot be longer than 255 characters');
                $('.help-block-full_name_payment').addClass('text-danger');
                isValid = false;
            }


            if ($('input[name="address_payment"]').val() === '') {
                $('.help-block-address_payment').text('Please enter your address');
                $('.help-block-address_payment').addClass('text-danger');
                isValid = false;
            }

            const emailRegex_payment = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (($('input[name="email_payment"]').val() === '') && (!emailRegex_payment.test(email_payment.value))) {
                $('.help-block-email_payment').text('Please enter a valid email');
                $('.help-block-email_payment').addClass('text-danger');
                isValid = false;
            }

            if ($('input[name="bank_name_payment"]').val() === '') {
                $('.help-block-bank_name_payment').text('Please enter your bank name');
                $('.help-block-bank_name_payment').addClass('text-danger');
                isValid = false;
            }

            if ($('input[name="routing_number_payment"]').val() === '') {
                $('.help-block-routing_number_payment').text('Please enter your routing number');
                $('.help-block-routing_number_payment').addClass('text-danger');
                isValid = false;
            }

            if ($('input[name="bank_account_payment_number"]').val() === '') {
                $('.help-block-bank_account_payment_number').text('Please enter your bank account number');
                $('.help-block-bank_account_payment_number').addClass('text-danger');
                isValid = false;
            }
            const regexPhone_payment = /^\+1 \(\d{3}\) \d{3}-\d{4}$/;
            if (($('input[name="phone_number_payment"]').val() === '') && (!regexPhone_payment.test(phone_number_payment.value))) {
                $('.help-block-phone_number_payment').text('Please enter a valid phone number');
                $('.help-block-phone_number_payment').addClass('text-danger');
                isValid = false;
            }

            return isValid;
        }

        // end bonus validation

        // validation 

        function validateSupportForm() {
                let isValid = true;

                // Support subject validation
                if ($('select[name="support_subject"]').val() === '') {
                    $('.help-block-support_subject').text('Please select your issue');
                    $('.help-block-support_subject').addClass('text-danger');
                    isValid = false;
                }
            
                // Support issue validation
                if ($('textarea[name="support_subject_issue"]').val().trim() === '') {
                    $('.help-block-support_subject_issue').text('Please tell us how we can help');
                    $('.help-block-support_subject_issue').addClass('text-danger');
                    isValid = false;
                }
            
                return isValid;
        }

        // end validation

        // Save Basic Information 
        const SaveBaiscInformation = document.getElementById("SaveBaiscInformation");

        SaveBaiscInformation.addEventListener("click",function(event){
            event.preventDefault();
            if (!validateBasicInfo()) {
                return;
            }
            console.log(first_name.value);
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/worker/update-worker-profile',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        support_subject: first_name.value,
                        support_subject_issue: last_name.value,
                    }),
                    success: function(resp) {
                        console.log(resp);
                        if (resp.status) {
                            notie.alert({
                                type: 'success',
                                text: '<i class="fa fa-check"></i> Support ticket sent successfully.',
                                time: 5
                            });

                        }
                    },
                    error: function(resp) {
                        notie.alert({
                            type: 'error',
                            text: '<i class="fa fa-check"></i> Support ticket sent successfully.',
                            time: 5
                        });
                    }
                });
            
        });
        // end Saving Basic Information

        // Save Professional Information
        const SaveProfessionalInformation = document.getElementById("SaveProfessionalInformation");       
        SaveProfessionalInformation.addEventListener("click", function(event) {
            event.preventDefault();
            if (!validateProfessionalInfo()) {
                return;
            }
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $.ajax({
                url: '/worker/update-worker-profile',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    profession: profession.value,
                    specialty: specialty.value,
                    terms: terms.value,
                    type: type.value,
                    block_scheduling: block_scheduling.value,
                    float_requirement: float_requirement.value,
                    facility_shift_cancelation_policy: facility_shift_cancelation_policy.value,
                    contract_termination_policy: contract_termination_policy.value,
                    traveler_distance_from_facility: traveler_distance_from_facility.value,
                    clinical_setting: clinical_setting.value,
                    Patient_ratio: Patient_ratio.value,
                    emr: emr.value,
                    Unit: Unit.value,
                    scrub_color: scrub_color.value,
                    rto: rto.value,
                    shift_of_day: shift_of_day.value,
                    hours_per_week: hours_per_week.value,
                    hours_shift: hours_shift.value,
                    preferred_assignment_duration: preferred_assignment_duration.value,
                    weeks_shift: weeks_shift.value,
                    InfoType : "ProfessionalInformation"
                }),
                success: function(resp) {
                    console.log(resp);
                    if (resp.status) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Professional Information saved successfully',
                            time: 5
                        });

                    }
                },
                error: function(resp) {
                    notie.alert({
                        type: 'error',
                        text: '<i class="fa fa-check"></i>' + resp.message,
                        time: 5
                    });
                }
            });
        });
        // end Saving Professional Information

        // Save Bonus Transfer 
        const SaveBonusInformation = document.getElementById("SaveBonusInformation"); 
        SaveBonusInformation.addEventListener("click",function(event){
            event.preventDefault();
            if (!validateBonusInfo()) {
                return;
            }
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/worker/add-worker-payment',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        full_name_payment: full_name_payment.value,
                        address_payment: address_payment.value,
                        email_payment: email_payment.value,
                        bank_name_payment: bank_name_payment.value,
                        routing_number_payment: routing_number_payment.value,
                        bank_account_payment_number: bank_account_payment_number.value,
                        phone_number_payment: phone_number_payment.value,
                    }),
                    success: function(resp) {
                        console.log(resp);
                        if (resp.status) {
                            notie.alert({
                                type: 'success',
                                text: '<i class="fa fa-check"></i> Payment Information Successfully',
                                time: 5
                            });

                        }
                    },
                    error: function(resp) {
                        console.log(resp);
                        notie.alert({
                            type: 'error',
                            text: resp,
                            time: 5
                        });
                    }
                });
            
        }); 
        // end Saving Bonus Transfer

        // saving Support ticket

        const SaveSupportTicket = document.getElementById("SaveSupportTicket"); 

        SaveSupportTicket.addEventListener("click",function(event){
            event.preventDefault();
            if (!validateSupportForm()) {
               return;
            }
            $('#loading').removeClass('d-none');
            $('#send_ticket').addClass('d-none');
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });
            $.ajax({
                    url: '/worker/send-support-ticket',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        support_subject: support_subject.value,
                        support_subject_issue: support_subject_issue.value,
                        
                    }),
                    success: function(resp) {
                        console.log(resp);
                        if (resp.status) {
                            notie.alert({
                                type: 'success',
                                text: '<i class="fa fa-check"></i> Payment Information Successfully',
                                time: 5
                            });
                            $('#loading').addClass('d-none');
                            $('#send_ticket').removeClass('d-none');
                            support_subject_issue.value = "";
                        }
                    },
                    error: function(resp) {
                        console.log(resp);
                        notie.alert({
                            type: 'error',
                            text: resp,
                            time: 5
                        });
                    }
                });
        });

        // end saving support ticket

        // account disactivating

        const DisactivateAccount = document.getElementById("DisactivateAccount");

        DisactivateAccount.addEventListener("click",function(event){
            $('#loading_disableOption').removeClass('d-none');
                            $('#disactivate_account').addClass('d-none');
            event.preventDefault();
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });
            $.ajax({
                    url: '/worker/disactivate-account',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        access:true
                    }),
                    success: function(resp) {
                        console.log(resp);
                        if (resp.status) {
                            notie.alert({
                                type: 'success',
                                text: '<i class="fa fa-check"></i> Account disactivated Successfully',
                                time: 5
                            });
                            $('#loading_disableOption').addClass('d-none');
                            $('#disactivate_account').removeClass('d-none');
                            window.location.href = "/";
                        }
                    },
                    error: function(resp) {
                        console.log(resp);
                        notie.alert({
                            type: 'error',
                            text: resp,
                            time: 5
                        });
                    }
                });
        });

        // end account disactivating

        // next and prev buttons
        nextBtnFirst.addEventListener("click", function(event) {
            event.preventDefault();
            slidePage.style.marginLeft = "-25%";
            progress.style.width = "66%";
            // img need to be modified
            infoType.innerHTML =
                "<span><img src='{{ URL::asset('frontend/img/my-per--con-vaccine.png') }}' /></span>Professional Information";
        });

        nextBtnSec.addEventListener("click", function(event) {
            event.preventDefault();
            slidePage.style.marginLeft = "-50%";
            progress.style.width = "100%";
            // img need to be modified
            infoType.innerHTML =
                "<span><img src='{{ URL::asset('frontend/img/my-per--con-refren.png') }}' /></span>Document management";

        });

        prevBtnSec.addEventListener("click", function(event) {
            event.preventDefault();
            slidePage.style.marginLeft = "0%";
            progress.style.width = "25%";
            infoType.innerHTML =
                "<span><img src='{{ URL::asset('frontend/img/my-per--con-user.png') }}' /></span>Basic Information";

        });

        prevBtnThird.addEventListener("click", function(event) {
            event.preventDefault();
            slidePage.style.marginLeft = "-25%";
            progress.style.width = "75%";
            infoType.innerHTML =
                "<span><img src='{{ URL::asset('frontend/img/my-per--con-vaccine.png') }}' /></span>Professional Information";

        });
        // end next and prev buttons

        // upload files
        document.getElementById('uploadForm').addEventListener('click', function(event) {
            event.preventDefault();
            if (!validateDocumentManagement()) {
                return;
            }
            @php
                $worker_id_json = $worker->id;
            @endphp
            let worker_id = '{!! $worker_id_json !!}';
            console.log(worker_id);
            var workerId = worker_id;
            var filesInput = document.getElementById('file');
            var files = Array.from(filesInput.files);

            Promise.all(files.map(file => {
                return new Promise((resolve, reject) => {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        resolve({
                            name: file.name,
                            content: event.target.result.split(',')[1]
                        });
                    };
                    reader.readAsDataURL(file);
                });
            })).then(files => {
                var body = {
                    workerId: workerId,
                    files: files
                };

                /// using ajax
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: 'http://localhost:4545/documents/add-docs',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(body),
                    success: function(resp) {
                        console.log(resp);
                        ajaxindicatorstop();
                        if (resp.ok) {
                            notie.alert({
                                type: 'success',
                                text: '<i class="fa fa-check"></i> Files uploaded successfully',
                                time: 5
                            });

                        }
                    },
                    error: function(resp) {
                        notie.alert({
                            type: 'error',
                            text: '<i class="fa fa-check"></i> Error ! Please try again later .',
                            time: 5
                        });
                    }
                });
            });
        });
        // end upload files
    
    // --------------------------- end Profile Setting area  ---------------------------- //

    // --------------------------- Account Setting Area --------------------------------- //

    // inputs account settings
    
    const user_name = document.querySelector('input[name="user_name"]');
    const password = document.querySelector('input[name="password"]');
    const new_mobile = document.querySelector('input[name="new_mobile"]');
    const twoFactorAuth = document.querySelector('input[name="twoFa"]:checked');
    const email = document.querySelector('input[name="email"]');
    var inputs = [];    

    // account setting validation here 

    function validateAccountSettingInformation(){
                $('.help-block-new_mobile').text('');
                $('.help-block-validation').text('');
                $('.help-block-email').text('');
                $('.help-block-user_name').text('');
            let isValid = true;
            // Create an array of all inputs
            inputs = [user_name, password, new_mobile, email];
            
            // Add the value of the selected radio button to the inputs array, if a radio button is selected
            const twoFactorAuth = document.querySelector('input[name="twoFa"]:checked');
            if (twoFactorAuth) {
                inputs.push(twoFactorAuth);
            }
        
            // Check if all inputs are empty
            const allEmpty = inputs.every(input => input.value.trim() === '');
        
            // If all inputs are empty, show an error
            if (allEmpty) {
                $('.help-block-validation').text('Please fill at least one field');
                $('.help-block-validation').addClass('text-danger');
                isValid = false;
            }
        
            // Email validation
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(email.value)) {
                $('.help-block-email').text('Please enter a valid email');
                $('.help-block-email').addClass('text-danger');
                isValid = false;
            }
        
            // User name validation
            const userNameRegex = /^[a-zA-Z\s]{1,255}$/;
            if (!userNameRegex.test(user_name.value)) {
                $('.help-block-user_name').text('User name can only contain letters and spaces, and cannot be longer than 255 characters');
                $('.help-block-user_name').addClass('text-danger');
                isValid = false;
            }
        
            // New mobile number validation
            const regexNewPhone = /^\+1 \(\d{3}\) \d{3}-\d{4}$/;
            if (!regexNewPhone.test(new_mobile.value)) {
                $('.help-block-new_mobile').text('Please enter a valid mobile number');
                $('.help-block-new_mobile').addClass('text-danger');
                isValid = false;
            }
        
            return isValid;
}
    // end account setting validation
    

    // send request to update here 
    const SaveAccountInformation = document.getElementById('SaveAccountInformation');
    SaveAccountInformation.addEventListener("click", function(event) {
        event.preventDefault();
        if (!validateAccountSettingInformation()) {
                return;
            }

        // clear form data from empty values
        const formData = new FormData();
        inputs.forEach(input => {
        if (input.value.trim() !== '') {
            formData.append(input.name, input.value);
        }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({
            url: '/worker/update-worker-account-setting',
            type: 'POST',
            processData: false,  
            contentType: false,
            data: formData,
            success: function(resp) {
                console.log(resp);
                if (resp.status) {
                    notie.alert({
                        type: 'success',
                        text: '<i class="fa fa-check"></i> ' + resp.message,
                        time: 5
                    });

                }else{
                    notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> ' + resp.message,
                    time: 5
                });
                }
            },
            error: function(resp) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Please try again later !',
                    time: 5
                });
            }
        });
       

    });
    
    // this functions to display profile setting / account setting forms
    function AccountSettingDisplay(){
            $('.profile_setting').addClass('d-none');
            $('.account_setting').removeClass('d-none');
            $('.bonus_transfers').addClass('d-none');
        }

    function ProfileIinformationDisplay(){
        $('.account_setting').addClass('d-none');
        $('.profile_setting').removeClass('d-none');
        $('.bonus_transfers').addClass('d-none');
        }
    function BonusTransfersDisplay(){
        $('.account_setting').addClass('d-none');
        $('.profile_setting').addClass('d-none');
        $('.bonus_transfers').removeClass('d-none');
    }
    function SupportDisplay(){
        $('.account_setting').addClass('d-none');
        $('.profile_setting').addClass('d-none');
        $('.bonus_transfers').addClass('d-none');
        $('.support_info').removeClass('d-none');
    }
    function DisactivateAccountDisplay(){
        $('.account_setting').addClass('d-none');
        $('.profile_setting').addClass('d-none');
        $('.bonus_transfers').addClass('d-none');
        $('.support_info').addClass('d-none');
        $('.disable_account').removeClass('d-none');
    }
    </script>

@stop

<style>
    .add {
        border: 1px solid #3D2C39 !important;
        color: #fff !important;
        border-radius: 100px;
        padding: 10px;
        text-align: center;
        width: fit-content;
        margin-top: 25px;
        background: #3D2C39 !important;
        margin-right: 6px;
    }

    .save {
        border: 1px solid #3D2C39 !important;
        color: #3D2C39 !important;
        border-radius: 100px;
        padding: 10px;
        text-align: center;
        width: fit-content;
        margin-top: 25px;
        background: transparent !important;
        margin-right: 6px;
    }

    .form-check-input[type=checkbox] {
        border-radius: .25rem;
        margin-top: .19em;
        margin-right: 6px
    }

    .form-check-input[type=checkbox]:focus:after {
        content: "";
        position: absolute;
        width: .875rem;
        height: .875rem;
        z-index: 1;
        display: block;
        border-radius: 0;
        background-color: #fff
    }

    .form-check-input[type=checkbox]:checked {
        background-image: none;
        background-color: black;
    }

    .form-check-input[type=checkbox]:checked:after {
        display: block;
        transform: rotate(45deg)
            /*!rtl:ignore*/
        ;
        width: .375rem;
        height: .8125rem;
        border: .125rem solid #fff;
        border-top: 0;
        border-left: 0
            /*!rtl:ignore*/
        ;
        margin-left: .25rem;
        margin-top: -1px;
        background-color: transparent
    }

    .form-check-input[type=checkbox]:checked:focus {
        background-color: black
    }

    .form-check-input[type=checkbox]:indeterminate {
        border-color: black
    }

    .form-check-input:checked {
        border-color: black;
    }

    .form-check-input:checked:focus {
        border-color: black;
    }

    .delete {
        border-radius: 57px;
        border: 1px solid var(--border, #111011);
        background: var(--light-bg-purple, #FFF8FD);
        width: 170px;
        height: 32px;
        color: var(--darkpurple, #3D2C39);
        text-align: center;
        font-family: Poppins;
        font-size: 12px;
        font-style: normal;
        font-weight: 600;
        line-height: 20px;
        text-transform: capitalize;
    }

    .ss-my-prsnl-wrapper .option {
        height: auto !important;
    }
</style>

<style>

    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
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


    .container .form-outer {
        width: 100%;
        overflow: hidden;
    }

    .container .form-outer form {
        display: flex;
        width: 400%;
    }

    .form-outer form .page {
        width: 25%;
        transition: margin-left 0.3s ease-in-out;
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
    .btns_prof_info{
        width: 100% !important;
    }
    .slide-page span {
        margin-top : 10px;
    } 
    #loading,#send_ticket,#loadSpan,#disactivate_account,#loading_disableOption,#loadSpan_disableOption{
        color:#fff;
    }
</style>
