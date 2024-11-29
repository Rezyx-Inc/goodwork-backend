<form onsubmit="return false;" method="post" action="{{ route('update-worker-profile') }}">
    {{-- <form> --}}
    @csrf
    <!-- first form slide Basic Information -->
    <div class="page slide-page">
        <div class="row justify-content-center">
            {{-- First Name --}}
            <div class="ss-form-group col-11">
                <label>First Name</label>
                <input type="text" name="first_name" placeholder="Please enter your first name"
                    value="{{ isset($user->first_name) ? $user->first_name : '' }}">
            </div>
            <span class="help-block-first_name"></span>
            {{-- Last Name --}}
            <div class="ss-form-group col-11">
                <label>Last Name</label>
                <input type="text" name="last_name" placeholder="Please enter your last name"
                    value="{{ isset($user->last_name) ? $user->last_name : '' }}">
            </div>
            <span class="help-block-last_name"></span>
            {{-- Phone Number --}}
            <div class="ss-form-group col-11">
                <label>Phone Number</label>
                <input id="contact_number" type="text" name="mobile" placeholder="Please enter your phone number"
                    value="{{ isset($user->mobile) ? $user->mobile : '' }}">
            </div>
            <span class="help-block-mobile"></span>
            {{-- Address Information --}}
            <div class="ss-form-group col-11">
                <label>Address</label>
                <input type="text" name="address" placeholder="Please enter your address"
                    value="{{ isset($worker->address) ? $worker->address : '' }}">
            </div>
            <span class="help-block-address"></span>
            {{-- State Information --}}
            <div class="ss-form-group col-11">
                <label>State</label>
                <select name="state" id="job_state">
                    <option value="{{ !empty($worker->state) ? $worker->state : '' }}" disabled selected hidden>
                        {{ !empty($worker->state) ? $worker->state : 'What State are you located in?' }}
                    </option>
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
                    <option value="{{ !empty($worker->city) ? $worker->city : '' }}" disabled selected hidden>
                        {{ !empty($worker->city) ? $worker->city : 'What City are you located in?' }}
                    </option>
                </select>
            </div>
            <span class="help-block-city"></span>
            <span class="help-city">Please select a state first</span>
            {{-- Zip Code Information --}}
            <div class="ss-form-group col-11">
                <label>Zip Code</label>
                <input type="number" name="zip_code" placeholder="Please enter your Zip Code"
                    value="{{ isset($user->zip_code) ? $user->zip_code : '' }}">
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
            {{-- Licence number --}}
            <div class="ss-form-group col-11">
                <label>Licence number</label>
                <input type="text" id="nursing_license_number" name="nursing_license_number"
                    placeholder="Enter licence number"
                    value="{{ !empty($worker->nursing_license_number) ? $worker->nursing_license_number : '' }}">
            </div>
            <span class="help-block-licence"></span>

            {{-- Profession --}}
            <div class="ss-form-group col-11">
                <label>Profession</label>
                <select name="profession" id="profession">
                    <option value="{{ !empty($worker->profession) ? $worker->profession : '' }}" disabled selected
                        hidden>
                        {{ !empty($worker->profession) ? $worker->profession : 'What Kind of Professional are you?' }}
                    </option>
                    @foreach ($professions as $profession)
                        <option value="{{ $profession->full_name }}">
                            {{ $profession->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <span class="help-block-profession"></span>
            {{-- Specialty --}}
            <div class="ss-form-group  col-11">
                <label>Specialty</label>
                <select name="specialty" id="specialty">
                    <option value="{{ !empty($worker->specialty) ? $worker->specialty : '' }}" disabled selected
                        hidden>
                        {{ !empty($worker->specialty) ? $worker->specialty : 'Select Specialty' }}
                    </option>

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
                    <option value="{{ !empty($worker->terms) ? $worker->terms : '' }}" disabled selected hidden>
                        {{ !empty($worker->terms) ? $worker->terms : 'Select a specefic term' }}
                    </option>

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
                <select name="worker_job_type" id="worker_job_type">
                    <option value="{{ !empty($worker->worker_job_type) ? $worker->worker_job_type : '' }}" disabled
                        selected hidden>
                        {{ !empty($worker->worker_job_type) ? $worker->worker_job_type : 'Select Type' }}
                    </option>

                    @if (isset($allKeywords['Type']))
                        @foreach ($allKeywords['Type'] as $value)
                            <option value="{{ $value->title }}">{{ $value->title }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <span class="help-block-worker_job_type"></span>
            {{-- end Type --}}

            {{-- Block scheduling --}}
            <div class="ss-form-group col-11">
                <label>Block scheduling</label>

                <select name="block_scheduling" class="block_scheduling mb-3" id="block_scheduling" value="">
                    <option
                        value="{{ $worker->block_scheduling == '0' ? 'No' : ($worker->block_scheduling == '1' ? 'Yes' : '') }}"
                        disabled selected hidden>

                        {{ $worker->block_scheduling == '0' ? 'No' : ($worker->block_scheduling == '1' ? 'Yes' : 'Select Block scheduling') }}
                    </option>

                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <span class="help-block-block_scheduling"></span>
            {{-- end scheduling --}}
            {{-- Float requirements --}}
            <div class="ss-form-group col-11">
                <label>Float requirements</label>

                <select name="float_requirement" class="float_requirement mb-3" id="float_requirement" value="">
                    <option value="{{ !empty($worker->float_requirement) ? $worker->float_requirement : '' }}"
                        disabled selected hidden>
                        {{ !empty($worker->float_requirement) ? $worker->float_requirement : 'Select Float requirements' }}
                    </option>

                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <span class="help-block-float_requirement"></span>
            {{-- end Float requirements --}}
            {{-- Facility Shift Cancellation Policy --}}
            <div class="ss-form-group  col-11">
                <label>Facility Shift Cancellation Policy</label>
                <select name="facility_shift_cancelation_policy" class="facility_shift_cancelation_policy mb-3"
                    id="facility_shift_cancelation_policy" value="">
                    <option
                        value="{{ !empty($worker->facility_shift_cancelation_policy) ? $worker->facility_shift_cancelation_policy : '' }}"
                        disabled selected hidden>
                        {{ !empty($worker->facility_shift_cancelation_policy) ? $worker->facility_shift_cancelation_policy : 'Select Facility Shift Cancellation Policy' }}
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
                <input type="text" id="contract_termination_policy" name="contract_termination_policy"
                    placeholder="Enter Contract Termination Policy"
                    value="{{ !empty($worker->contract_termination_policy) ? $worker->contract_termination_policy : '' }}">
            </div>
            <span class="help-block-contract_termination_policy"></span>
            {{-- end Contract Termination Policy --}}
            {{-- Traveler Distance From Facility --}}
            <div class="ss-form-group col-11">
                <label>Distance from your home</label>
                <input type="number" id="traveler_distance_from_facility" name="distance_from_your_home"
                    placeholder="Enter the distance from your home."
                    value="{{ !empty($worker->distance_from_your_home) ? $worker->distance_from_your_home : '' }}">
            </div>
            <span class="help-block-traveler_distance_from_facility"></span>
            {{-- end Traveler Distance From Facility  --}}
            {{-- Clinical Setting --}}
            <div class="ss-form-group col-11">
                <label>Clinical Setting</label>
                <input type="text" id="clinical_setting" name="clinical_setting_you_prefer"
                    placeholder="Enter clinical setting"
                    value="{{ !empty($worker->clinical_setting_you_prefer) ? $worker->clinical_setting_you_prefer : '' }}">
            </div>
            <span class="help-block-clinical_setting_you_prefer"></span>
            {{-- End Clinical Setting --}}
            {{-- Patient ratio --}}
            <div class="ss-form-group col-11">
                <label>Patient ratio</label>
                <input type="number" id="Patient_ratio" name="worker_patient_ratio"
                    placeholder="How many patients can you handle?"
                    value="{{ !empty($worker->worker_patient_ratio) ? $worker->worker_patient_ratio : '' }}">
            </div>
            <span class="help-block-worker_patient_ratio"></span>
            {{-- End Patient ratio --}}
            {{-- EMR --}}
            <div class="ss-form-group col-11">
                <label>EMR</label>
                <select name="worker_emr" class="emr mb-3" id="emr">
                    <option value="{{ !empty($worker->worker_emr) ? $worker->worker_emr : '' }}" disabled selected
                        hidden>
                        {{ !empty($worker->worker_emr) ? $worker->worker_emr : 'Select EMR' }}
                    </option>

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
                <input id="Unit" type="text" name="worker_unit" placeholder="Enter Unit"
                    value="{{ !empty($worker->worker_unit) ? $worker->worker_unit : '' }}">
            </div>
            <span class="help-block-worker_unit"></span>
            {{-- End Unit --}}
            {{-- Scrub Color --}}
            <div class="ss-form-group col-11">
                <label>Scrub Color</label>
                <input id="scrub_color" type="text" name="worker_scrub_color" placeholder="Enter Scrub Color"
                    value="{{ !empty($worker->worker_scrub_color) ? $worker->worker_scrub_color : '' }}">
            </div>
            <span class="help-block-worker_scrub_color"></span>
            {{-- End Scrub Color --}}
            {{-- RTO --}}
            <div class="ss-form-group col-11">
                <label>Rto</label>
                <select name="rto" id="rto">
                    <option value="{{ !empty($worker->rto) ? $worker->rto : '' }}" disabled selected hidden>
                        {{ !empty($worker->rto) ? $worker->rto : 'Select Rto' }} </option>
                    <option value="allowed">Allowed
                    </option>
                    <option value="not allowed">Not Allowed
                    </option>
                </select>
            </div>
            <span class="help-block-rto"></span>
            {{-- End RTO --}}

            {{-- Shift Time of Day --}}
            <div class="ss-form-group col-11">
                <label>Shift Time of Day</label>
                <select name="worker_shift_time_of_day" id="shift-of-day">
                    <option
                        value="{{ !empty($worker->worker_shift_time_of_day) ? $worker->worker_shift_time_of_day : '' }}"
                        disabled selected hidden>
                        {{ !empty($worker->worker_shift_time_of_day) ? $worker->worker_shift_time_of_day : 'Enter Shift Time of Day' }}
                    </option>
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

            {{-- Hours/Shift --}}
            <div class="ss-form-group col-11">
                <label>Hours/Shift</label>
                <input id="hours_shift" type="number" name="worker_hours_shift" placeholder="Enter Hours/Shift"
                    value="{{ !empty($worker->worker_hours_shift) ? $worker->worker_hours_shift : '' }}">
            </div>
            <span class="help-block-worker_hours_shift"></span>
            {{-- End Hours/Shift --}}
            {{-- Weeks/Assignment --}}
            <div class="ss-form-group col-11">
                <label>Weeks/Assignment</label>
                <input id="preferred_assignment_duration" type="number" name="worker_weeks_assignment"
                    placeholder="Enter Weeks/Assignment"
                    value="{{ !empty($worker->worker_weeks_assignment) ? $worker->worker_weeks_assignment : '' }}">
            </div>
            <span class="help-block-worker_weeks_assignment"></span>
            {{-- End Weeks/Assignment --}}
            {{-- Shifts/Week --}}
            <div class="ss-form-group col-11">
                <label>Shifts/Week</label>
                <input id="weeks_shift" type="number" name="worker_shifts_week" placeholder="Enter Shifts/Week"
                    value="{{ !empty($worker->worker_shifts_week) ? $worker->worker_shifts_week : '' }}">
            </div>
            <span class="help-block-worker_shifts_week"></span>
            {{-- End Shifts/Week --}}
            {{-- added fields to match job details in explore jobs --}}
            {{-- Experience  --}}
            <div class="ss-form-group col-11">
                <label>Experience</label>
                <input id="worker_experience" type="number" name="worker_experience"
                    placeholder="Enter your experience"
                    value="{{ !empty($worker->worker_experience) ? $worker->worker_experience : '' }}">
            </div>
            <span class="help-block-worker_experience"></span>
            {{-- End Experience --}}


            {{-- nursing_license_state --}}
            {{-- <div class="ss-form-group col-11">
                <label>Where are you licensed?</label>
                <select name="nursing_license_state" id="nursing_license_state">
                    <option
                        value="{{ !empty($worker->nursing_license_state) ? $worker->nursing_license_state : '' }}" disabled selected hidden>
                        {{ !empty($worker->nursing_license_state) ? $worker->nursing_license_state : 'Select a State' }}
                    </option>
                    @foreach ($allKeywords['StateCode'] as $value)
                        <option value="{{ $value->title }}">{{ $value->title }} Compact
                        </option>
                    @endforeach
                </select>
                <span class="help-block-nursing_license_state"></span>
            </div> --}}
            {{-- End nursing_license_state --}}
            {{-- worker_eligible_work_in_us --}}

            <div class="ss-form-group col-11">
                <label>Eligible to work in the US</label>
                <select name="worker_eligible_work_in_us" id="worker_eligible_work_in_us">
                    <option
                        value="{{ $worker->worker_eligible_work_in_us == '0' ? 'No' : ($worker->worker_eligible_work_in_us == '1' ? 'Yes' : '') }}"
                        disabled selected hidden>

                        {{ $worker->worker_eligible_work_in_us == '0' ? 'No' : ($worker->worker_eligible_work_in_us == '1' ? 'Yes' : 'Select Eligible to work in the US') }}
                    </option>
                    <option value="">Select an option</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <span class="help-block-worker_eligible_work_in_us"></span>
            </div>

            {{-- End worker_eligible_work_in_us --}}


            {{-- worker_facility_state --}}

            <div class="ss-form-group col-11">
                <label>State you'd like to work?</label>
                <select name="worker_facility_state" id="worker_facility_state">
                    <option value="{{ !empty($worker->worker_facility_state) ? $worker->worker_facility_state : '' }}"
                        disabled selected hidden>
                        {{ !empty($worker->worker_facility_state) ? $worker->worker_facility_state : 'Select a State' }}
                    </option>
                    @foreach ($states as $state)
                        <option id="{{ $state->id }}" value="{{ $state->name }}">
                            {{ $state->name }}
                        </option>
                    @endforeach
                </select>
                <span class="help-block-worker_facility_state"></span>
            </div>
            {{-- End worker_facility_state  --}}

            {{-- worker_facility_city --}}

            <div class="ss-form-group col-11">
                <label>City you'd like to work?</label>
                <select name="worker_facility_city" id="worker_facility_city">
                    <option value="{{ !empty($worker->worker_facility_city) ? $worker->worker_facility_city : '' }}"
                        disabled selected hidden>
                        {{ !empty($worker->worker_facility_city) ? $worker->worker_facility_city : 'Select a City' }}
                    </option>
                    @foreach ($allKeywords['City'] as $value)
                        <option value="{{ $value->title }}">{{ $value->title }}
                        </option>
                    @endforeach
                </select>
                <span class="help-block-worker_facility_city"></span>
                <span class="help-worker-facility-city">Please select a state first</span>
            </div>
            {{-- End worker_facility_city  --}}

            {{-- worker_start_date --}}
            <div class="ss-form-group col-11">
                <label>When can you start?</label>
                <input id="worker_start_date" type="date" name="worker_start_date"
                    placeholder="Enter your start date"
                    value="{{ !empty($worker->worker_start_date) ? $worker->worker_start_date : '' }}">
            </div>
            <span class="help-block-worker_start_date"></span>
            {{-- End worker_start_date  --}}

            {{-- worker_guaranteed_hours --}}
            <div class="ss-form-group col-11">
                <label>Guaranteed Hours</label>
                <input id="worker_guaranteed_hours" type="number" name="worker_guaranteed_hours"
                    placeholder="Enter your guaranteed hours"
                    value="{{ !empty($worker->worker_guaranteed_hours) ? $worker->worker_guaranteed_hours : '' }}">
            </div>
            <span class="help-block-worker_guaranteed_hours"></span>
            {{-- End worker_guaranteed_hours  --}}

            {{-- worker_sign_on_bonus --}}

            <div class="ss-form-group col-11">
                <label>Sign on Bonus</label>
                <input id="worker_sign_on_bonus" type="number" name="worker_sign_on_bonus"
                    placeholder="What rate is fair ? "
                    value="{{ !empty($worker->worker_sign_on_bonus) ? $worker->worker_sign_on_bonus : '' }}">
            </div>
            <span class="help-block-worker_sign_on_bonus"></span>
            {{-- End worker_sign_on_bonus  --}}

            {{-- worker_completion_bonus --}}
            <div class="ss-form-group col-11">
                <label>Completion Bonus</label>
                <input id="worker_completion_bonus" type="number" name="worker_completion_bonus"
                    placeholder="What rate is fair ? "
                    value="{{ !empty($worker->worker_completion_bonus) ? $worker->worker_completion_bonus : '' }}">
            </div>
            <span class="help-block-worker_completion_bonus"></span>
            {{-- End worker_completion_bonus  --}}

            {{-- worker_extension_bonus --}}
            <div class="ss-form-group col-11">
                <label>Extension Bonus</label>
                <input id="worker_extension_bonus" type="number" name="worker_extension_bonus"
                    placeholder="What rate is fair ? "
                    value="{{ !empty($worker->worker_extension_bonus) ? $worker->worker_extension_bonus : '' }}">
            </div>
            <span class="help-block-worker_extension_bonus"></span>
            {{-- End worker_extension_bonus  --}}

            {{-- worker_other_bonus --}}
            <div class="ss-form-group col-11">
                <label>Other Bonus</label>
                <input id="worker_other_bonus" type="number" name="worker_other_bonus"
                    placeholder="What rate is fair ? "
                    value="{{ !empty($worker->worker_other_bonus) ? $worker->worker_other_bonus : '' }}">
            </div>
            <span class="help-block-worker_other_bonus"></span>
            {{-- End worker_other_bonus  --}}

            {{-- worker_four_zero_one_k --}}
            <div class="ss-form-group col-11">
                <label>401K</label>
                <select name="worker_four_zero_one_k" id="worker_four_zero_one_k">
                    <option
                        value="{{ $worker->worker_four_zero_one_k == '0' ? 'No' : ($worker->worker_four_zero_one_k == '1' ? 'Yes' : '') }}"
                        disabled selected hidden>
                        {{ $worker->worker_four_zero_one_k == '0' ? 'No' : ($worker->worker_four_zero_one_k == '1' ? 'Yes' : 'Select an option') }}
                    </option>

                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <span class="help-block-worker_four_zero_one_k"></span>
            </div>
            {{-- End worker_four_zero_one_k  --}}

            {{-- worker_health_insurance --}}
            <div class="ss-form-group col-11">
                <label>Health Insurance</label>
                <select name="worker_health_insurance" id="worker_health_insurance">
                    <option
                        value="{{ $worker->worker_health_insurance == '0' ? 'No' : ($worker->worker_health_insurance == '1' ? 'Yes' : '') }}"
                        disabled selected hidden>
                        {{ $worker->worker_health_insurance == '0' ? 'No' : ($worker->worker_health_insurance == '1' ? 'Yes' : 'Select an option') }}
                    </option>
                    <option value="">Select an option</option>
                    <option value="1">Yes</option>
                    <option value="2">No</option>
                </select>
                <span class="help-block-worker_health_insurance"></span>
            </div>
            {{-- End worker_health_insurance  --}}

            {{-- worker_dental --}}
            <div class="ss-form-group col-11">
                <label>Dental</label>
                <select name="worker_dental" id="worker_dental">
                    <option
                        value="{{ $worker->worker_dental == '0' ? 'No' : ($worker->worker_dental == '1' ? 'Yes' : '') }}"
                        disabled selected hidden>
                        {{ $worker->worker_dental == '0' ? 'No' : ($worker->worker_dental == '1' ? 'Yes' : 'Select an option') }}
                    </option>
                    <option value="">do you want this ?</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <span class="help-block-worker_dental"></span>
            </div>
            {{-- End worker_dental  --}}

            {{-- worker_vision --}}

            <div class="ss-form-group col-11">
                <label>Vision</label>
                <select name="worker_vision" id="worker_vision">
                    <option
                        value="{{ $worker->worker_vision == '0' ? 'No' : ($worker->worker_vision == '1' ? 'Yes' : '') }}"
                        disabled selected hidden>
                        {{ $worker->worker_vision == '0' ? 'No' : ($worker->worker_vision == '1' ? 'Yes' : 'Select an option') }}
                    </option>
                    <option value="">do you want this ?</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <span class="help-block-worker_vision"></span>
            </div>
            {{-- End worker_vision  --}}

            {{-- worker_overtime_rate --}}
            <div class="ss-form-group col-11">
                <label>Overtime Rate</label>
                <input id="worker_overtime_rate" type="number" name="worker_overtime_rate"
                    placeholder="What rate is fair?"
                    value="{{ !empty($worker->worker_overtime_rate) ? $worker->worker_overtime_rate : '' }}">
            </div>
            <span class="help-block-worker_overtime_rate"></span>
            {{-- End worker_overtime_rate  --}}

            {{-- worker_holiday its a date  --}}
            <div class="ss-form-group col-11">
                <label>Holiday</label>
                <input id="worker_holiday" type="date" name="worker_holiday"
                    placeholder="Any holiday you refuse to work?"
                    value="{{ !empty($worker->worker_holiday) ? \Carbon\Carbon::parse($worker->worker_holiday)->format('Y-m-d') : '' }}">
            </div>
            <span class="help-block-worker_holiday"></span>
            {{-- End worker_holiday  --}}

            {{-- worker_on_call_check --}}
            <div class="ss-form-group col-11">
                <label>On Call</label>
                <select name="worker_on_call_check" id="worker_on_call_check">
                    <option
                        value="{{ $worker->worker_on_call_check == '0' ? 'No' : ($worker->worker_on_call_check == '1' ? 'Yes' : '') }}"
                        disabled selected hidden>
                        {{ !empty($worker->worker_on_call_check) ? ($worker->worker_on_call_check == 1 ? 'Yes' : 'No') : 'Will you do call?' }}
                    </option>
                    <option value="">Select an option</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <span class="help-block-worker_on_call_check"></span>
            </div>
            {{-- End worker_call  --}}

            {{-- worker_on_call --}}
            <div class="ss-form-group col-11">
                <label>On Call Rate</label>
                <input id="worker_on_call" type="number" name="worker_on_call" placeholder="What rate is fair?"
                    value="{{ !empty($worker->worker_on_call) ? $worker->worker_on_call : '' }}">
            </div>
            <span class="help-block-worker_on_call"></span>
            {{-- End worker_on_call  --}}

            {{-- worker_call_back --}}
            <div class="ss-form-group col-11">
                <label>On Call Back Rate</label>
                <input id="worker_call_back" type="number" name="worker_call_back" placeholder="What rate is fair?"
                    value="{{ !empty($worker->worker_call_back) ? $worker->worker_call_back : '' }}">
            </div>
            <span class="help-block-worker_call_back"></span>
            {{-- End worker_call_back  --}}

            {{-- worker_orientation_rate --}}
            <div class="ss-form-group col-11">
                <label>Orientation Rate</label>
                <input id="worker_orientation_rate" type="number" name="worker_orientation_rate"
                    placeholder="Is this rate reasonable?"
                    value="{{ !empty($worker->worker_orientation_rate) ? $worker->worker_orientation_rate : '' }}">
            </div>
            <span class="help-block-worker_orientation_rate"></span>
            {{-- End worker_orientation_rate  --}}

            {{-- worker_benefits --}}

            <div class="ss-form-group col-11">
                <label>Worker benefits</label>
                <select name="worker_benefits" class="worker_benefits mb-3" id="worker_benefits" value="">
                    <option
                        value="{{ $worker->worker_benefits == '0' ? 'No' : ($worker->worker_benefits == '1' ? 'Yes' : '') }}"
                        disabled selected hidden>
                        {{ !empty($worker->worker_benefits) ? $worker->worker_benefits : 'Select your benefits choice' }}
                    </option>
                    <option value="1">Yes, Please</option>
                    <option value="2">Preferable</option>
                    <option value="0">No, Thanks</option>
                </select>
            </div>
            <span class="help-block-worker_benefits"></span>

            {{-- nurse_classification --}}
            <div class="ss-form-group col-11">
                <label>Nurse Classification</label>
                <select name="nurse_classification" id="nurse_classification">
                    <option value="{{ !empty($worker->nurse_classification) ? $worker->nurse_classification : '' }}"
                        disabled selected hidden>
                        {{ !empty($worker->nurse_classification) ? $worker->nurse_classification : 'Select Nurse Classification' }}
                    </option>
                    @foreach ($allKeywords['NurseClassification'] as $value)
                        <option value="{{ $value->title }}">{{ $value->title }}
                        </option>
                    @endforeach
                </select>
                <span class="help-block-nurse_classification"></span>
            </div>
            {{-- End nurse_classification  --}}


            {{-- end worker benefits --}}

            {{-- Skip && Save --}}
            <div class="ss-prsn-form-btn-sec row col-11" style="gap:0px;">
                <div class="col-4">
                    <button type="text" class="ss-prsnl-skip-btn prev-1 btns_prof_info"> Previous
                    </button>
                </div>
                <div class="col-4">
                    <button type="text" class="ss-prsnl-save-btn next-1 btns_prof_info"> Next
                    </button>
                </div>
                <div class="col-4">
                    <button type="text" class="ss-prsnl-save-btn btns_prof_info" id="SaveProfessionalInformation">
                        Save
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
            {{-- <div class="ss-form-group">
                <label>Upload Document</label>
                <input type="file" id="document_file" name="files" multiple
                    required><br><br>
                <label class="mt-2" for="file">Choose a file</label>
            </div>
            <span class="help-block-file"></span> --}}
            <div class="ss-form-group "
                style="
                display: flex;
                justify-content: right;
                align-items: center;
            ">
                <span style="margin:0px; margin-right:20px;">Add your documents here
                    !</span>
                <a href="#" onclick="open_modal(this)" class="ss-opr-mngr-plus-sec"
                    style="
                    background: #3d2c39;
                    width: 40px;
                    height: 40px;
                    line-height: 40px;
                    text-align: center;
                    border-radius: 100px;
                    color: #52DEC1 !important;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    "
                    data-bs-toggle="modal" data-bs-target="#job-dtl-Dcouments"><i class="fas fa-plus"></i></a>


                <br><br>

            </div>
            {{-- manage file table --}}
            <table style="font-size: 16px;" class="table row">
                <thead>
                    <tr class="row">
                        <th class="col-3">Document Name</th>
                        <th class="col-3">Type</th>
                        <th class="col-3">View</th>
                        <th class="col-3">Delete</th>
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
