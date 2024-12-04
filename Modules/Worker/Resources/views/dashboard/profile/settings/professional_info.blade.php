<div class="col-md-12 my-4 collapse-container">
    <p>
        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-2" role="button" aria-expanded="false"
            aria-controls="collapseExample">
            Summary
        </a>
    </p>
</div>

<div class="row collapse" id="collapse-2">

    {{-- Type --}}

    <div class="ss-form-group">

        <label>Type</label>
        <select name="worker_job_type" id="worker_job_type">
            <option value="" disabled selected hidden>
                Select Type
            </option>

            @if (isset($allKeywords['Type']))
                @foreach ($allKeywords['Type'] as $value)
                    <option value="{{ $value->title }}"
                        {{ !$worker->worker_job_type && !empty($worker->worker_job_type) && $worker->worker_job_type == $value->title ? 'selected' : '' }}>
                        {{ $value->title }}
                    </option>
                @endforeach
            @endif

        </select>
    </div>
    <span class="help-block-worker_job_type"></span>
    {{-- end Type --}}

    {{-- Terms --}}
    <div class="ss-form-group">
        <label>Terms</label>
        <select name="terms" id="term">
            <option value="" disabled selected hidden>
                Select a specefic term
            </option>

            @if (isset($allKeywords['Terms']))
                @foreach ($allKeywords['Terms'] as $value)
                    <option value="{{ $value->id }}"
                        {{ !$worker->terms && !empty($worker->terms) && $worker->terms == $value->id ? 'selected' : '' }}>
                        {{ $value->title }}
                    </option>
                @endforeach
            @endif

        </select>
    </div>
    <span class="help-block-terms"></span>

    {{-- Profession --}}
    <div class="ss-form-group">
        <label>Profession</label>
        <select name="profession" id="profession">
            <option value="" disabled selected hidden>
                What Kind of Professional are you?
            </option>

            @foreach ($professions as $profession)
                <option value="{{ $profession->full_name }}"
                    {{ !$worker->profession && !empty($worker->profession) && $worker->profession == $profession->full_name ? 'selected' : '' }}>
                    {{ $profession->full_name }}
                </option>
            @endforeach

        </select>
    </div>
    <span class="help-block-profession"></span>

    {{-- Specialty --}}
    <div class="ss-form-group ">
        <label>Specialty</label>
        <select name="specialty" id="specialty">
            <option value="" disabled selected hidden>
                Select Specialty
            </option>

            @foreach ($specialities as $specialty)
                <option value="{{ $specialty->full_name }}"
                    {{ !$worker->specialty && !empty($worker->specialty) && $worker->specialty == $specialty->full_name ? 'selected' : '' }}>
                    {{ $specialty->full_name }}
                </option>
            @endforeach

        </select>
    </div>
    <span class="help-block-specialty"></span>

    {{-- $/hr --}}

    <div class="ss-form-group col-md-12">
        <label>$/hr</label>
        <input type="number" name="worker_actual_hourly_rate" id="worker_actual_hourly_rate"
            placeholder="Minimum hourly rate you'd consider?"
            value="{{ !empty($worker->worker_actual_hourly_rate) ? $worker->worker_actual_hourly_rate : '' }}">
    </div>
    <span class="help-block-specialty"></span>

    {{-- $/Wk --}}

    <div class="ss-form-group col-md-12">
        <label>$/Wk</label>
        <input type="number" step="0.01" name="worker_organization_weekly_amount"
            id="worker_organization_weekly_amount" placeholder="Enter Weekly Pay"
            value="{{ !empty($worker->worker_organization_weekly_amount) ? $worker->worker_organization_weekly_amount : '' }}">
        <div>
            <span class="helper help-block-worker_organization_weekly_amount"></span>
        </div>
    </div>

    {{-- Hrs/Wk --}}

    <div class="ss-form-group col-md-12">
        <label>Hrs/Wk</label>
        <input type="number" step="0.01" name="worker_hours_per_week" id="worker_hours_per_week"
            placeholder="Enter Weekly Pay"
            value="{{ !empty($worker->worker_hours_per_week) ? $worker->worker_hours_per_week : '' }}">
        <div>
            <span class="helper help-block-worker_hours_per_week"></span>
        </div>
    </div>


    {{-- State Information --}}
    <div class="ss-form-group col-11">
        <label>State</label>
        <select name="state" id="state">

            <option value="" disabled selected hidden>
                What State are you located in?
            </option>

            @foreach ($states as $state)
                <option value="{{ $state->id }}"
                    {{ !$worker->state && !empty($worker->state) && $worker->state == $state->id ? 'selected' : '' }}>
                    {{ $state->name }}
                </option>
            @endforeach

        </select>
    </div>
    <span class="help-block-state"></span>


    {{-- city --}}

    <div class="ss-form-group">
        <label>City you'd like to work?</label>
        <select name="city" id="city">

            <option value="" disabled selected hidden>
                Select a City
            </option>

            @foreach ($allKeywords['City'] as $value)
                <option value="{{ $value->title }}"
                    {{ !$worker->city && !empty($worker->city) && $worker->city == $value->title ? 'selected' : '' }}>
                    {{ $value->title }}
                </option>
            @endforeach

        </select>
        <span class="help-block-city"></span>
        <span class="help-worker-facility-city">Please select a state first</span>
    </div>
    {{-- End city  --}}
</div>


{{-- collaps 3 --}}
<div class="col-md-12 my-4 collapse-container">
    <p>
        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-3" role="button" aria-expanded="false"
            aria-controls="collapseExample">
            Shift
        </a>
    </p>
</div>


<div class="row collapse" id="collapse-3">

    {{-- Shift Time of Day --}}
    <div class="ss-form-group">
        <label>Shift Time</label>
        <select name="worker_shift_time_of_day" id="shift-of-day">

            <option value="" disabled selected hidden>
                Enter Shift Time of Day
            </option>

            @if (isset($allKeywords['PreferredShift']))
                @foreach ($allKeywords['PreferredShift'] as $value)
                    <option value="{{ $value->id }}"
                        {{ !$worker->worker_shift_time_of_day && !empty($worker->worker_shift_time_of_day) && $worker->worker_shift_time_of_day == $value->id ? 'selected' : '' }}>
                        {{ $value->title }}
                    </option>
                @endforeach
            @endif

        </select>
    </div>
    <span class="help-block-worker_shift_time_of_day"></span>
    {{-- End Shift Time of Day --}}

    {{-- worker_guaranteed_hours --}}
    <div class="ss-form-group">
        <label>Guaranteed Hrs/wk</label>
        <input id="worker_guaranteed_hours" type="number" name="worker_guaranteed_hours"
            placeholder="Enter your guaranteed hours"
            value="{{ !empty($worker->worker_guaranteed_hours) ? $worker->worker_guaranteed_hours : '' }}">
    </div>
    <span class="help-block-worker_guaranteed_hours"></span>
    {{-- End worker_guaranteed_hours  --}}

    {{-- Hours/Shift --}}
    <div class="ss-form-group">
        <label>Hrs/Shift</label>
        <input id="hours_shift" type="number" name="worker_hours_shift" placeholder="Enter Hours/Shift"
            value="{{ !empty($worker->worker_hours_shift) ? $worker->worker_hours_shift : '' }}">
    </div>
    <span class="help-block-worker_hours_shift"></span>
    {{-- End Hours/Shift --}}

    {{-- Shifts/Week --}}
    <div class="ss-form-group">
        <label>Shifts/Wk</label>
        <input id="weeks_shift" type="number" name="worker_shifts_week" placeholder="Enter Shifts/Week"
            value="{{ !empty($worker->worker_shifts_week) ? $worker->worker_shifts_week : '' }}">
    </div>
    <span class="help-block-worker_shifts_week"></span>
    {{-- End Shifts/Week --}}

    {{-- Weeks/Assignment --}}
    <div class="ss-form-group">
        <label>Wks/Contract</label>
        <input id="preferred_assignment_duration" type="number" name="worker_weeks_assignment"
            placeholder="Enter Weeks/Assignment"
            value="{{ !empty($worker->worker_weeks_assignment) ? $worker->worker_weeks_assignment : '' }}">
    </div>
    <span class="help-block-worker_weeks_assignment"></span>
    {{-- End Weeks/Assignment --}}

    {{-- worker_start_date --}}
    <div class="ss-form-group">
        <label>Start Date</label>
        <input id="worker_start_date" type="date" name="worker_start_date" placeholder="Enter your start date"
            value="{{ !empty($worker->worker_start_date) ? $worker->worker_start_date : '' }}">
    </div>
    <span class="help-block-worker_start_date"></span>
    {{-- End worker_start_date  --}}

    {{-- worker_end_date --}}
    <div class="ss-form-group">
        <label>End Date</label>
        <input id="worker_end_date" type="date" name="worker_end_date" placeholder="Enter your End date"
            value="{{ !empty($worker->worker_end_date) ? $worker->worker_end_date : '' }}">
    </div>
    <span class="help-block-worker_end_date"></span>
    {{-- End worker_end_date  --}}

    {{-- RTO --}}
    <div class="ss-form-group">
        <label>Rto</label>
        {{-- TODO :: make it a select input --}}
        {{-- <select name="rto" id="rto">

            <option value="" {{ empty($worker->rto) ? 'selected' : '' }} disabled>Select Rto </option>
            <option value="allowed" {{ !empty($worker->rto) && $worker->rto == 'allowed' ? 'selected' : '' }}>
                Allowed
            </option>
            <option value="not allowed" {{ !empty($worker->rto) && $worker->rto == 'not allowed' ? 'selected' : '' }}>
                Not Allowed
            </option>

        </select> --}}

        <input id="rto" type="date" name="rto" placeholder="Enter your End date"
            value="{{ !empty($worker->rto) ? $worker->rto : '' }}">
    </div>
    <span class="help-block-rto"></span>
    {{-- End RTO --}}

</div>


{{-- collaps 4 --}}
<div class="col-md-12 my-4 collapse-container">
    <p>
        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-4" role="button"
            aria-expanded="false" aria-controls="collapseExample">
            Pay
        </a>
    </p>
</div>

<div class="row collapse" id="collapse-4">

    {{-- worker_on_call --}}
    <div class="ss-form-group">
        <label>Min On Call $/Hr</label>
        <input id="worker_on_call" type="number" name="worker_on_call" placeholder="What rate is fair?"
            value="{{ !empty($worker->worker_on_call) ? $worker->worker_on_call : '' }}">
    </div>
    <span class="help-block-worker_on_call"></span>
    {{-- End worker_on_call  --}}

    {{-- worker_call_back --}}
    <div class="ss-form-group">
        <label>Call Back $/Hr</label>
        <input id="worker_call_back" type="number" name="worker_call_back" placeholder="What rate is fair?"
            value="{{ !empty($worker->worker_call_back) ? $worker->worker_call_back : '' }}">
    </div>
    <span class="help-block-worker_call_back"></span>
    {{-- End worker_call_back  --}}

    {{-- worker_orientation_rate --}}
    <div class="ss-form-group">
        <label>Orientation $/Hr</label>
        <input id="worker_orientation_rate" type="number" name="worker_orientation_rate"
            placeholder="Is this rate reasonable?"
            value="{{ !empty($worker->worker_orientation_rate) ? $worker->worker_orientation_rate : '' }}">
    </div>
    <span class="help-block-worker_orientation_rate"></span>
    {{-- End worker_orientation_rate  --}}

    {{-- worker_weekly_taxable_amount --}}
    <div class="ss-form-group">
        <label>Taxable $/Wk</label>
        <input id="worker_weekly_taxable_amount" type="number" name="worker_weekly_taxable_amount"
            placeholder="Minimum taxable $ per week?"
            value="{{ !empty($worker->worker_weekly_taxable_amount) ? $worker->worker_weekly_taxable_amount : '' }}">
    </div>
    <span class="help-block-worker_weekly_taxable_amount"></span>
    {{-- End worker_weekly_taxable_amount  --}}

    {{-- worker_weekly_non_taxable_amount --}}
    <div class="ss-form-group">
        <label>Non-taxable $/Wk</label>
        <input id="worker_weekly_non_taxable_amount" type="number" name="worker_weekly_non_taxable_amount"
            placeholder="Minimum Non-taxable $ per week?"
            value="{{ !empty($worker->worker_weekly_non_taxable_amount) ? $worker->worker_weekly_non_taxable_amount : '' }}">
    </div>
    <span class="help-block-worker_weekly_non_taxable_amount"></span>
    {{-- End worker_weekly_non_taxable_amount  --}}

    {{-- worker_feels_like_per_hour --}}
    <div class="ss-form-group">
        <label>Feels Like $/hr</label>
        <input id="worker_feels_like_per_hour" type="number" name="worker_feels_like_per_hour"
            placeholder="Minimum Feels Like $ per hour?"
            value="{{ !empty($worker->worker_feels_like_per_hour) ? $worker->worker_feels_like_per_hour : '' }}">
    </div>
    <span class="help-block-worker_feels_like_per_hour"></span>
    {{-- End worker_feels_like_per_hour  --}}

    {{-- worker_pay_frequency --}}
    <div class="ss-form-group">
        <label>Pay Frequency</label>
        <select name="worker_pay_frequency" id="worker_pay_frequency">

            <option value="" {{ empty($worker->worker_pay_frequency) ? 'selected' : '' }} disabled>
                Select a Pay frequency
            </option>
            <option value="Same Day Pay"
                {{ !empty($worker->worker_pay_frequency) && $worker->worker_pay_frequency == 'Same Day Pay' ? 'selected' : '' }}>
                Same Day Pay
            </option>
            <option value="Daily"
                {{ !empty($worker->worker_pay_frequency) && $worker->worker_pay_frequency == 'Daily' ? 'selected' : '' }}>
                Daily
            </option>
            <option value="Weekly"
                {{ !empty($worker->worker_pay_frequency) && $worker->worker_pay_frequency == 'Weekly' ? 'selected' : '' }}>
                Weekly
            </option>
            <option value="Every other week"
                {{ !empty($worker->worker_pay_frequency) && $worker->worker_pay_frequency == 'Every other week' ? 'selected' : '' }}>
                Every other week
            </option>
            <option value="Bi-Monthly"
                {{ !empty($worker->worker_pay_frequency) && $worker->worker_pay_frequency == 'Bi-Monthly' ? 'selected' : '' }}>
                Bi-Monthly
            </option>
            <option value="Monthly"
                {{ !empty($worker->worker_pay_frequency) && $worker->worker_pay_frequency == 'Monthly' ? 'selected' : '' }}>
                Monthly
            </option>

        </select>
    </div>
    <span class="help-block-worker_pay_frequency"></span>
    {{-- End worker_pay_frequency --}}

    {{-- worker_benefits --}}

    <div class="ss-form-group">
        <label>Benefits</label>
        <select name="worker_benefits" class="worker_benefits mb-3" id="worker_benefits" value="">
            <option value="" {{ empty($worker->worker_benefits) ? 'selcted' : '' }}>
                Select your benefits choice
            </option>
            @if (isset($allKeywords['Benefits']))
                @foreach ($allKeywords['Benefits'] as $value)
                    <option value="{{ $value->title }}"
                        {{ !empty($worker->worker_benefits) && $worker->worker_benefits == $value->title ? 'selcted' : '' }}>
                        {{ $value->title }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <span class="help-block-worker_benefits"></span>
    {{-- end worker benefits --}}

</div>



{{-- collaps 5 --}}
<div class="col-md-12 my-4 collapse-container">
    <p>
        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-5" role="button"
            aria-expanded="false" aria-controls="collapseExample">
            Location
        </a>
    </p>
</div>

<div class="row collapse" id="collapse-5">

    {{-- Clinical Setting --}}
    <div class="ss-form-group">
        <label>Clinical Setting</label>
        <input type="text" id="clinical_setting" name="clinical_setting_you_prefer"
            placeholder="Enter clinical setting"
            value="{{ !empty($worker->clinical_setting_you_prefer) ? $worker->clinical_setting_you_prefer : '' }}">
    </div>
    <span class="help-block-clinical_setting_you_prefer"></span>
    {{-- End Clinical Setting --}}

    {{-- Address --}}
    <div class="ss-form-group col-md-12">
        <label>Current Address</label>
        <input type="text" name="worker_preferred_work_location" id="worker_preferred_work_location"
            placeholder="Where are you staying now?">
    </div>
    <span class="helper help-block-worker_preferred_work_location"></span>

    {{-- Facility --}}

    <div class="ss-form-group col-md-12">
        <label>Facilities you've worked at?</label>
        <input type="text" name="worker_facility_name" id="worker_facility_name"
            placeholder="What facilities have you worked at?">
    </div>
    <span class="helper help-block-worker_facility_name"></span>


    {{-- Facility Parent Systems --}}

    <div class="ss-form-group col-md-12">
        <label>Fav Facility Parent Systems</label>
        <input type="text" name="worker_facilitys_parent_system" id="worker_facilitys_parent_system"
            placeholder="What systems would you like to work at?">
    </div>
    <span class="helper help-block-worker_facilitys_parent_system"></span>


    {{-- TODO :: ask what is Fav Facilities in nurse model --}}
    {{-- <div class="ss-form-group col-md-12">
        <label>Fav Facilities</label>
        <input type="text" name="xxxxxxxxxxxxxxxxxxx" id="xxxxxxxxxxxxxxxxxxx"
            placeholder="What systems would you like to work at?">
    </div>
    <span class="helper help-block-xxxxxxxxxxxxxxxxxxx"></span> --}}


    {{-- worker_facility_state --}}

    <div class="ss-form-group col-md-12">
        <label>Fav States</label>
        <input type="text" name="worker_facility_state" id="worker_facility_state" placeholder="Fav States">
    </div>
    <span class="helper help-block-worker_facility_state"></span>


    {{-- worker_facility_city --}}

    <div class="ss-form-group col-md-12">
        <label>Fav States</label>
        <input type="text" name="worker_facility_city" id="worker_facility_city" placeholder="Fav Cities">
    </div>
    <span class="helper help-block-worker_facility_city"></span>

</div>


{{-- collaps 6 --}}
<div class="col-md-12 my-4 collapse-container">
    <p>
        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-6" role="button"
            aria-expanded="false" aria-controls="collapseExample">
            {{-- TODO :: change "Documents" => "Certs & Licences" and fix items order --}}
            Documents
        </a>
    </p>
</div>

<div class="row collapse" id="collapse-6">

    @include('worker::dashboard.profile.settings.documents_info')

</div>



{{-- collaps 7 --}}
<div class="col-md-12 my-4 collapse-container">
    <p>
        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-7" role="button"
            aria-expanded="false" aria-controls="collapseExample">
            Work Info
        </a>
    </p>
</div>

<div class="row collapse" id="collapse-7">

    {{-- Experience  --}}
    <div class="ss-form-group">
        <label>Experience in profession</label>
        <input id="worker_experience" type="number" name="worker_experience" placeholder="Enter your experience"
            value="{{ !empty($worker->worker_experience) ? $worker->worker_experience : '' }}">
    </div>
    <span class="help-block-worker_experience"></span>
    {{-- End Experience --}}

    {{-- worker_on_call --}}
    <div class="ss-form-group">
        <label>On Call</label>
        <select name="worker_on_call" id="worker_on_call">
            <option value="" {{ empty($worker->worker_on_call) ? 'selected' : '' }} disabled hidden>
                Select an option
            </option>
            <option value="Yes"
                {{ !empty($worker->worker_on_call) && $worker->worker_on_call == 'Yes' ? 'selected' : '' }}>Yes
            </option>
            <option value="No"
                {{ !empty($worker->worker_on_call) && $worker->worker_on_call == 'No' ? 'selected' : '' }}>No</option>
        </select>
        <span class="help-block-worker_on_call"></span>
    </div>
    {{-- End worker_call  --}}

    {{-- Block scheduling --}}
    <div class="ss-form-group">
        <label>Block scheduling</label>

        <select name="block_scheduling" class="block_scheduling mb-3" id="block_scheduling" value="">
            <option value="" {{ empty($worker->block_scheduling) ? 'selected' : '' }} disabled hidden>
                Select an option
            </option>
            <option value="Yes"
                {{ !empty($worker->block_scheduling) && $worker->block_scheduling == 'Yes' ? 'selected' : '' }}>Yes
            </option>
            <option value="No"
                {{ !empty($worker->block_scheduling) && $worker->block_scheduling == 'No' ? 'selected' : '' }}>No
            </option>
        </select>
    </div>
    <span class="help-block-block_scheduling"></span>
    {{-- end scheduling --}}


    {{-- Float requirements --}}
    <div class="ss-form-group">
        <label>Float requirements</label>

        <select name="float_requirement" class="float_requirement mb-3" id="float_requirement" value="">

            <option value="" {{ empty($worker->float_requirement) ? 'selected' : '' }} disabled hidden>
                Select an option
            </option>
            <option value="Yes"
                {{ !empty($worker->float_requirement) && $worker->float_requirement == 'Yes' ? 'selected' : '' }}>Yes
            </option>
            <option value="No"
                {{ !empty($worker->float_requirement) && $worker->float_requirement == 'No' ? 'selected' : '' }}>No
            </option>
        </select>
    </div>
    <span class="help-block-float_requirement"></span>
    {{-- end Float requirements --}}

    {{-- Patient ratio --}}
    <div class="ss-form-group">
        <label>Patient ratio</label>
        <input type="number" id="Patient_ratio" name="worker_patient_ratio"
            placeholder="Max patients you will take responsibility for?"
            value="{{ !empty($worker->worker_patient_ratio) ? $worker->worker_patient_ratio : '' }}">
    </div>
    <span class="help-block-worker_patient_ratio"></span>
    {{-- End Patient ratio --}}

    {{-- EMR --}}
    <div class="ss-form-group">
        <label>EMR</label>
        <select name="worker_emr" class="emr mb-3" id="emr">
            <option value="" {{ empty($worker->worker_emr) ? 'selected' : '' }} disabled hidden>
                Select an option
            </option>
            @if (isset($allKeywords['EMR']))
                @foreach ($allKeywords['EMR'] as $value)
                    <option value="{{ $value->id }}"
                        {{ !empty($worker->worker_emr) && $worker->worker_emr == $value->id ? 'selected' : '' }}>
                        {{ $value->title }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
    <span class="help-block-worker_emr"></span>
    {{-- End EMR --}}

    {{-- Unit --}}
    <div class="ss-form-group">
        <label>Unit</label>
        <input id="Unit" type="text" name="worker_unit" placeholder="Enter Unit"
            value="{{ !empty($worker->worker_unit) ? $worker->worker_unit : '' }}">
    </div>
    <span class="help-block-worker_unit"></span>
    {{-- End Unit --}}

    {{-- nurse_classification --}}
    <div class="ss-form-group">
        <label>Nurse Classification</label>
        <select name="nurse_classification" id="nurse_classification">

            <option value="" {{ empty($worker->nurse_classification) ? 'selected' : '' }} disabled hidden>
                Select Nurse Classification
            </option>
            @foreach ($allKeywords['NurseClassification'] as $value)
                <option value="{{ $value->title }}"
                    {{ !empty($worker->nurse_classification) && $worker->nurse_classification == $value->title ? 'selected' : '' }}>
                    {{ $value->title }}
                </option>
            @endforeach
        </select>
        <span class="help-block-nurse_classification"></span>
    </div>
    {{-- End nurse_classification  --}}

</div>

















<!--

{{-- rest from old form --}}

<div class="row justify-content-center d-none">


    {{-- Licence number --}}
    <div class="ss-form-group">
        <label>Licence number</label>
        <input type="text" id="nursing_license_number" name="nursing_license_number"
            placeholder="Enter licence number"
            value="{{ !empty($worker->nursing_license_number) ? $worker->nursing_license_number : '' }}">
    </div>
    <span class="help-block-licence"></span>

    {{-- Facility Shift Cancellation Policy --}}
    <div class="ss-form-group ">
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
    <div class="ss-form-group">
        <label>Contract Termination Policy</label>
        <input type="text" id="contract_termination_policy" name="contract_termination_policy"
            placeholder="Enter Contract Termination Policy"
            value="{{ !empty($worker->contract_termination_policy) ? $worker->contract_termination_policy : '' }}">
    </div>
    <span class="help-block-contract_termination_policy"></span>
    {{-- end Contract Termination Policy --}}
    {{-- Traveler Distance From Facility --}}
    <div class="ss-form-group">
        <label>Distance from your home</label>
        <input type="number" id="traveler_distance_from_facility" name="distance_from_your_home"
            placeholder="Enter the distance from your home."
            value="{{ !empty($worker->distance_from_your_home) ? $worker->distance_from_your_home : '' }}">
    </div>
    <span class="help-block-traveler_distance_from_facility"></span>
    {{-- end Traveler Distance From Facility  --}}

    {{-- Scrub Color --}}
    <div class="ss-form-group">
        <label>Scrub Color</label>
        <input id="scrub_color" type="text" name="worker_scrub_color" placeholder="Enter Scrub Color"
            value="{{ !empty($worker->worker_scrub_color) ? $worker->worker_scrub_color : '' }}">
    </div>
    <span class="help-block-worker_scrub_color"></span>
    {{-- End Scrub Color --}}
    {{-- added fields to match job details in explore jobs --}}

    {{-- nursing_license_state --}}
    {{-- <div class="ss-form-group">
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

    <div class="ss-form-group">
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

    <div class="ss-form-group">
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



    {{-- worker_sign_on_bonus --}}

    <div class="ss-form-group">
        <label>Sign on Bonus</label>
        <input id="worker_sign_on_bonus" type="number" name="worker_sign_on_bonus"
            placeholder="What rate is fair ? "
            value="{{ !empty($worker->worker_sign_on_bonus) ? $worker->worker_sign_on_bonus : '' }}">
    </div>
    <span class="help-block-worker_sign_on_bonus"></span>
    {{-- End worker_sign_on_bonus  --}}

    {{-- worker_completion_bonus --}}
    <div class="ss-form-group">
        <label>Completion Bonus</label>
        <input id="worker_completion_bonus" type="number" name="worker_completion_bonus"
            placeholder="What rate is fair ? "
            value="{{ !empty($worker->worker_completion_bonus) ? $worker->worker_completion_bonus : '' }}">
    </div>
    <span class="help-block-worker_completion_bonus"></span>
    {{-- End worker_completion_bonus  --}}

    {{-- worker_extension_bonus --}}
    <div class="ss-form-group">
        <label>Extension Bonus</label>
        <input id="worker_extension_bonus" type="number" name="worker_extension_bonus"
            placeholder="What rate is fair ? "
            value="{{ !empty($worker->worker_extension_bonus) ? $worker->worker_extension_bonus : '' }}">
    </div>
    <span class="help-block-worker_extension_bonus"></span>
    {{-- End worker_extension_bonus  --}}

    {{-- worker_other_bonus --}}
    <div class="ss-form-group">
        <label>Other Bonus</label>
        <input id="worker_other_bonus" type="number" name="worker_other_bonus" placeholder="What rate is fair ? "
            value="{{ !empty($worker->worker_other_bonus) ? $worker->worker_other_bonus : '' }}">
    </div>
    <span class="help-block-worker_other_bonus"></span>
    {{-- End worker_other_bonus  --}}

    {{-- worker_four_zero_one_k --}}
    <div class="ss-form-group">
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
    <div class="ss-form-group">
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
    <div class="ss-form-group">
        <label>Dental</label>
        <select name="worker_dental" id="worker_dental">
            <option value="{{ $worker->worker_dental == '0' ? 'No' : ($worker->worker_dental == '1' ? 'Yes' : '') }}"
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

    <div class="ss-form-group">
        <label>Vision</label>
        <select name="worker_vision" id="worker_vision">
            <option value="{{ $worker->worker_vision == '0' ? 'No' : ($worker->worker_vision == '1' ? 'Yes' : '') }}"
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
    <div class="ss-form-group">
        <label>Overtime Rate</label>
        <input id="worker_overtime_rate" type="number" name="worker_overtime_rate" placeholder="What rate is fair?"
            value="{{ !empty($worker->worker_overtime_rate) ? $worker->worker_overtime_rate : '' }}">
    </div>
    <span class="help-block-worker_overtime_rate"></span>
    {{-- End worker_overtime_rate  --}}

    {{-- worker_holiday its a date  --}}
    <div class="ss-form-group">
        <label>Holiday</label>
        <input id="worker_holiday" type="date" name="worker_holiday"
            placeholder="Any holiday you refuse to work?"
            value="{{ !empty($worker->worker_holiday) ? \Carbon\Carbon::parse($worker->worker_holiday)->format('Y-m-d') : '' }}">
    </div>
    <span class="help-block-worker_holiday"></span>
    {{-- End worker_holiday  --}}

    {{-- Skip && Save --}}
    <div class="ss-prsn-form-btn-sec row" style="gap:0px;">
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

-->
