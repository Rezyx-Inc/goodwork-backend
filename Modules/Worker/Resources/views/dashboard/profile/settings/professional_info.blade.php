{{-- collaps 1 --}}
<div class="col-md-12 my-4 collapse-container">
    <p>
        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-1" role="button" aria-expanded="false"
            aria-controls="collapseExample">
            Summary
        </a>
    </p>
</div>

<div class="row collapse" id="collapse-1">

    {{-- Type --}}
    <div class="ss-form-group">
        @include('worker::components.custom_multiple_select_input', [
            'id' => 'worker_job_type',
            'label' => 'Type',
            'placeholder' => 'Select Type',
            'name' => 'worker_job_type',
            'options' => $allKeywords['Type'],
            'option_attribute' => 'title',
            'selected' => old('worker_job_type', $worker->worker_job_type),
        ])

    </div>
    <span class="help-block-worker_job_type"></span>
    {{-- end Type --}}

    {{-- Terms --}}
    <div class="ss-form-group">

        @include('worker::components.custom_multiple_select_input', [
            'id' => 'terms',
            'label' => 'Terms',
            'placeholder' => 'Select a specefic term',
            'name' => 'terms',
            'options' => $allKeywords['Terms'],
            'option_attribute' => 'title',
            'selected' => old('terms', $worker->terms),
        ])

    </div>
    <span class="help-block-terms"></span>

    {{-- Profession --}}
    <div class="ss-form-group">

        @include('worker::components.custom_multiple_select_input', [
            'id' => 'profession',
            'label' => 'Professions',
            'placeholder' => 'What kind of Professional are you?',
            'name' => 'profession',
            'options' => $allKeywords['Profession'],
            'option_attribute' => 'title',
            'selected' => old('profession', $worker->profession),
        ])

    </div>
    <span class="help-block-profession"></span>

    {{-- Specialty --}}
    <div class="ss-form-group ">
        
        @include('worker::components.custom_multiple_select_input', [
            'id' => 'specialty',
            'label' => 'Specialties',
            'placeholder' => "What is your specialty?",
            'name' => 'specialty',
            'options' => $allKeywords['Speciality'],
            'option_attribute' => 'title',
            'selected' => old('specialty', $worker->specialty),
        ])

    </div>
    <span class="help-block-specialty"></span>

    {{-- $/hr --}}

    <div class="ss-form-group col-md-12">
        <label>Min $/hr</label>
        <input type="number" name="worker_actual_hourly_rate" id="worker_actual_hourly_rate"
            placeholder="Minimum hourly rate you'd consider?"
            value="{{ $formatAmount($worker->worker_actual_hourly_rate) }}">
    </div>
    <span class="help-block-specialty"></span>

    {{-- $/Wk --}}

    <div class="ss-form-group col-md-12">
        <label>Min $/Wk</label>
        <input type="number" step="0.01" name="worker_organization_weekly_amount"
            id="worker_organization_weekly_amount" placeholder="Minimum weekly rate you'd consider?"
            value="{{ $formatAmount($worker->worker_organization_weekly_amount) }}">
        <div>
            <span class="helper help-block-worker_organization_weekly_amount"></span>
        </div>
    </div>

    {{-- Hrs/Wk --}}

    <div class="ss-form-group col-md-12">
        <label>Hrs/Wk</label>
        <input type="number" step="0.01" name="worker_hours_per_week" id="worker_hours_per_week"
            placeholder="Maximum hours per week you'll work"
            value="{{ $formatAmount($worker->worker_hours_per_week) }}">
        <div>
            <span class="helper help-block-worker_hours_per_week"></span>
        </div>
    </div>


    {{-- State Information --}}
    <div class="ss-form-group">
   
        {{-- @include('worker::components.custom_multiple_select_input', [
            'id' => 'state',
            'label' => 'States',
            'placeholder' => "States you will work in?",
            'name' => 'state',
            'options' => $allKeywords['State'],
            'option_attribute' => 'title',
            'selected' => old('state', $worker->state),
        ]) --}}
         
        <label for="state">State</label>

        <select name="state" onchange="get_cities(this)">
            <option value="">Select a state</option>
            @foreach ($states as $v)
            <option value="{{$v->name}}" data-id="{{$v->id}}" {{ ($v->name == $worker->state) ? 'selected': ''}}>{{$v->name}}</option>
            @endforeach
        </select>
        <span class="help-block-state"></span>
    </div>


    {{-- city --}}

    <div class="ss-form-group">
 
        {{-- @include('worker::components.custom_multiple_select_input', [
            'id' => 'city',
            'label' => 'Cities',
            'placeholder' => "Cities you will work in?",
            'name' => 'city',
            'options' => [],
            'option_attribute' => 'title',
            'selected' => old('city', $worker->city),
        ]) --}}
        
        <label for="city">City</label>

        <select name="city" id="city">
            @if(!empty($worker->city))
            <option value="{{$worker->city}}" selected>{{$worker->city}}</option>
            @else
            <option value="">Select a state first</option>
            @endif
        </select>
        <span class="help-block-city"></span>
    </div>
    {{-- End city  --}}
</div>


{{-- collaps 2 --}}
<div class="col-md-12 my-4 collapse-container">
    <p>
        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-2" role="button" aria-expanded="false"
            aria-controls="collapseExample">
            Shift
        </a>
    </p>
</div>


<div class="row collapse" id="collapse-2">

    {{-- Shift Time of Day --}}
    <div class="ss-form-group">

        
        @include('worker::components.custom_multiple_select_input', [
            'id' => 'worker_shift_time_of_day',
            'label' => 'Shift Times',
            'placeholder' => "Shifts you will work?",
            'name' => 'worker_shift_time_of_day',
            'options' => $allKeywords['PreferredShift'],
            'option_attribute' => 'title',
            'selected' => old('worker_shift_time_of_day', $worker->worker_shift_time_of_day),
        ])

    </div>
    <span class="help-block-worker_shift_time_of_day"></span>
    {{-- End Shift Time of Day --}}

    {{-- worker_guaranteed_hours --}}
    <div class="ss-form-group">
        <label>Min guaranteed Hrs/wk</label>
        <input id="worker_guaranteed_hours" type="number" name="worker_guaranteed_hours"
            placeholder="Minimum guaranteed Hrs/wk"
            value="{{ $formatAmount($worker->worker_guaranteed_hours) }}">
    </div>
    <span class="help-block-worker_guaranteed_hours"></span>
    {{-- End worker_guaranteed_hours  --}}

    {{-- Hours/Shift --}}
    <div class="ss-form-group">
        <label>Hrs/Shift</label>
        <input id="hours_shift" type="number" name="worker_hours_shift" placeholder="Hours per shift you'll work?"
            value="{{ $formatAmount($worker->worker_hours_shift) }}">
    </div>
    <span class="help-block-worker_hours_shift"></span>
    {{-- End Hours/Shift --}}

    {{-- Shifts/Week --}}
    <div class="ss-form-group">
        <label>Shifts/Wk</label>
        <input id="weeks_shift" type="number" name="worker_shifts_week" placeholder="Shifts per week?"
            value="{{ $formatAmount($worker->worker_shifts_week) }}">
    </div>
    <span class="help-block-worker_shifts_week"></span>
    {{-- End Shifts/Week --}}

    {{-- Weeks/Assignment --}}
    <div class="ss-form-group">
        <label>Min Wks/Contract</label>
        <input id="preferred_assignment_duration" type="number" name="worker_weeks_assignment"
            placeholder="How many weeks?"
            value="{{ $formatAmount($worker->worker_weeks_assignment) }}">
    </div>
    <span class="help-block-worker_weeks_assignment"></span>
    {{-- End Weeks/Assignment --}}

    {{-- worker_start_date --}}
    <div class="ss-form-group">
        <label>Start Dates</label>
        <input id="worker_start_date" type="date" name="worker_start_date" placeholder="When can you start?"
            value="{{ !empty($worker->worker_start_date) ? $worker->worker_start_date : '' }}">
    </div>
    <span class="help-block-worker_start_date"></span>
    {{-- End worker_start_date  --}}

    {{-- RTO --}}
    <div class="ss-form-group">
        <label>RTO Dates</label>
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

        <input id="rto" type="date" name="rto" placeholder="Any time off?"
            value="{{ !empty($worker->rto) ? $worker->rto : '' }}">
    </div>
    <span class="help-block-rto"></span>
    {{-- End RTO --}}

</div>


{{-- collaps 3 --}}
<div class="col-md-12 my-4 collapse-container">
    <p>
        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-3" role="button"
            aria-expanded="false" aria-controls="collapseExample">
            Pay
        </a>
    </p>
</div>

<div class="row collapse" id="collapse-3">

    {{-- worker_overtime --}}
    <div class="ss-form-group">
        <label>Min OT $/Hr</label>
        <input id="worker_overtime" type="number" name="worker_overtime" placeholder="Minimun hourly rate for Overtime?"
            value="{{ $formatAmount($worker->worker_overtime) }}">
    </div>
    <span class="help-block-worker_overtime"></span>
    {{-- End worker_overtime  --}}

    {{-- worker_on_call --}}
    <div class="ss-form-group">
        <label>Min On Call $/Hr</label>
        <input id="worker_on_call" type="number" name="worker_on_call" placeholder="Minimun hourly rate for On Call?"
            value="{{ $formatAmount($worker->worker_on_call) }}">
    </div>
    <span class="help-block-worker_on_call"></span>
    {{-- End worker_on_call  --}}

    {{-- worker_call_back --}}
    <div class="ss-form-group">
        <label>Min Call Back $/Hr</label>
        <input id="worker_call_back" type="number" name="worker_call_back" placeholder="Minimun hourly rate for Call Back?"
            value="{{ $formatAmount($worker->worker_call_back) }}">
    </div>
    <span class="help-block-worker_call_back"></span>
    {{-- End worker_call_back  --}}

    {{-- worker_orientation_rate --}}
    <div class="ss-form-group">
        <label>Min Orientation $/Hr</label>
        <input id="worker_orientation_rate" type="number" name="worker_orientation_rate"
            placeholder="Minimun hourly rate for Orientation?"
            value="{{ $formatAmount($worker->worker_orientation_rate) }}">
    </div>
    <span class="help-block-worker_orientation_rate"></span>
    {{-- End worker_orientation_rate  --}}

    {{-- worker_weekly_taxable_amount --}}
    <div class="ss-form-group">
        <label>Min Taxable $/Wk</label>
        <input id="worker_weekly_taxable_amount" type="number" name="worker_weekly_taxable_amount"
            placeholder="Minimum taxable $ per week?"
            value="{{ $formatAmount($worker->worker_weekly_taxable_amount) }}">
    </div>
    <span class="help-block-worker_weekly_taxable_amount"></span>
    {{-- End worker_weekly_taxable_amount  --}}

    {{-- worker_weekly_non_taxable_amount --}}
    <div class="ss-form-group">
        <label>Min Non-taxable $/Wk</label>
        <input id="worker_weekly_non_taxable_amount" type="number" name="worker_weekly_non_taxable_amount"
            placeholder="Minimum Non-taxable $ per week?"
            value="{{ $formatAmount($worker->worker_weekly_non_taxable_amount) }}">
    </div>
    <span class="help-block-worker_weekly_non_taxable_amount"></span>
    {{-- End worker_weekly_non_taxable_amount  --}}

    {{-- worker_feels_like_per_hour --}}
    <div class="ss-form-group">
        <label>Min Feels Like $/hr</label>
        <input id="worker_feels_like_per_hour" type="number" name="worker_feels_like_per_hour"
            placeholder="Minimum 'Feels Like' $ per hour?"
            value="{{ $formatAmount($worker->worker_feels_like_per_hour) }}">
    </div>
    <span class="help-block-worker_feels_like_per_hour"></span>
    {{-- End worker_feels_like_per_hour  --}}

    {{-- worker_pay_frequency --}}
    <div class="ss-form-group">
        <label>Pay Frequency</label>
        <select name="worker_pay_frequency" id="worker_pay_frequency">

            <option value="" {{ empty($worker->worker_pay_frequency) ? 'selected' : '' }} disabled>
                Pay frequency
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
                Minimum benefits you require?
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



{{-- collaps 4 --}}
<div class="col-md-12 my-4 collapse-container">
    <p>
        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-4" role="button"
            aria-expanded="false" aria-controls="collapseExample">
            Location
        </a>
    </p>
</div>

<div class="row collapse" id="collapse-4">

    {{-- Clinical Setting --}}
    <div class="ss-form-group">
        <label>Clinical Settings</label>
        <input type="text" id="clinical_setting" name="clinical_setting_you_prefer"
            placeholder="Settings you'll work in?"
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
        <label>Fav Cities</label>
        <input type="text" name="worker_facility_city" id="worker_facility_city" placeholder="Fav Cities">
    </div>
    <span class="helper help-block-worker_facility_city"></span>

</div>


{{-- collaps 5 --}}
<div class="col-md-12 my-4 collapse-container">
    <p>
        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-5" role="button"
            aria-expanded="false" aria-controls="collapseExample">
            {{-- TODO :: change "Documents" => "Certs & Licences" and fix items order --}}
            Certs & Licences
        </a>
    </p>
</div>

<div class="row collapse" id="collapse-5">

    {{-- worker_job_location --}}
    <div class="ss-form-group">
        @include('worker::components.custom_multiple_select_input', [
            'id' => 'worker_job_location',
            'label' => 'Active Professional Licensures',
            'placeholder' => 'Where are you licensed?',
            'name' => 'worker_job_location',
            'options' => $allKeywords['StateCode'],
            'option_attribute' => 'title',
            'selected' => old('worker_job_location', $worker->worker_job_location),
        ])

    </div>
    <span class="help-block-worker_job_location"></span>
    {{-- end Type --}}

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
        <input id="worker_experience" type="number" name="worker_experience" placeholder="Years worked in this profession?"
            value="{{  $formatAmount($worker->worker_experience)}}">
    </div>
    <span class="help-block-worker_experience"></span>
    {{-- End Experience --}}

    {{-- worker_on_call_check --}}
    <div class="ss-form-group">
        <label>On Call</label>
        <select name="worker_on_call_check" id="worker_on_call_check">
            <option value="" {{ empty($worker->worker_on_call_check) ? 'selected' : '' }} disabled hidden>
                Will you do call?
            </option>
            <option value="Yes"
                {{ !empty($worker->worker_on_call_check) && $worker->worker_on_call_check == 'Yes' ? 'selected' : '' }}>Yes
            </option>
            <option value="No"
                {{ !empty($worker->worker_on_call_check) && $worker->worker_on_call_check == 'No' ? 'selected' : '' }}>No</option>
        </select>
        <span class="help-block-worker_on_call_check"></span>
    </div>
    {{-- End worker_call  --}}

    {{-- Block scheduling --}}
    <div class="ss-form-group">
        <label>Block scheduling</label>

        <select name="block_scheduling" class="block_scheduling mb-3" id="block_scheduling" value="">
            <option value="" {{ empty($worker->block_scheduling) ? 'selected' : '' }} disabled hidden>
                Do you require block scheduling?
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
        <label>Float</label>

        <select name="float_requirement" class="float_requirement mb-3" id="float_requirement" value="">

            <option value="" {{ empty($worker->float_requirement) ? 'selected' : '' }} disabled hidden>
                Are you willing float?
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
        <label>Patient Ratio Max</label>
        <input type="number" id="Patient_ratio" name="worker_patient_ratio"
            placeholder="Max patients you will take responsibility for?"
            value="{{  $formatAmount($worker->worker_patient_ratio)}}">
    </div>
    <span class="help-block-worker_patient_ratio"></span>
    {{-- End Patient ratio --}}

    {{-- EMR --}}
    <div class="ss-form-group">
        <label>EMR</label>
        <select name="worker_emr" class="emr mb-3" id="emr">
            <option value="" {{ empty($worker->worker_emr) ? 'selected' : '' }} disabled hidden>
                What EMRs have you used?
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
        <input id="Unit" type="text" name="worker_unit" placeholder="Units you'll work in?"
            value="{{ !empty($worker->worker_unit) ? $worker->worker_unit : '' }}">
    </div>
    <span class="help-block-worker_unit"></span>
   {{-- End Unit --}}
</div>
