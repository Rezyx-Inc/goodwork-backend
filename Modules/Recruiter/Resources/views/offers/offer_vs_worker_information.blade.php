<ul class="ss-cng-appli-hedpfl-ul">
    <li>
        <span>
            {{ $offerdetails['worker_user_id'] }}
        </span>
        <h6>
            <img width="50px" height="50px" src="{{ URL::asset('images/nurses/profile/' . $userdetails->image) }}"
                onerror="this.onerror=null;this.src='{{ URL::asset('frontend/img/profile-pic-big.png') }}';"
                id="preview" style="object-fit: cover;" class="rounded-3" alt="Profile Picture">
            {{ $userdetails->first_name }}
            {{ $userdetails->last_name }}
        </h6>
    </li>
    {{-- @if ($hasFile == true)
        <li style="margin-right:10px;">
            <a style="cursor:pointer;" class="rounded-pill ss-apply-btn py-2 border-0 px-4" data-target="file"
                data-hidden_value="Yes" data-href="" data-title="Worker's Files" data-name="diploma"
                onclick="open_modal(this)">
                Consult worker files
            </a>
        </li>
        <li>
            <a onclick="askWorker(this, 'nursing_profession', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')"
                class="rounded-pill ss-apply-btn py-2 border-0 px-4" style="cursor: pointer;">Chat Now</a>
        </li>
    @else
        <li>
            <a onclick="askWorker(this, 'nursing_profession', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')"
                class="rounded-pill ss-apply-btn py-2 border-0 px-4" style="cursor: pointer;">Chat Now</a>
        </li>
    @endif --}}
</ul>

<div class="row ss-chng-apcon-st-ssele d-flex justify-content-center align-items-center">
    <div class="col-12 row">
        <label class="mb-2">Change Application Status</label>
    </div>
    <div class="col-12 row d-flex justify-content-center align-items-center">
        <div class="col-9">
            @if ($offerdetails['status'] === 'Screening')
                <select name="status" id="status application-status">
                    <option value="" disabled hidden>Select Status</option>
                    <option value="Screening"
                        {{ $offerdetails['status'] === 'Screening' ? 'selected hidden disabled' : '' }}>Screening
                    </option>
                    <option value="Submitted">Submitted
                    </option>
                    <option value="Offered">Offered</option>
                    <option value="Done">Done</option>
                </select>
            @else
                <select name="status" id="status application-status">
                    <option value="" disabled hidden>Select Status</option>
                    @if ($offerdetails['status'] === 'Apply')
                        <option value="Apply" selected hidden disabled>Apply</option>
                    @endif
                    <option value="Screening"
                        {{ $offerdetails['status'] === 'Screening' ? 'selected hidden disabled' : '' }}>Screening
                    </option>
                    <option value="Submitted"
                        {{ $offerdetails['status'] === 'Submitted' ? 'selected hidden disabled' : '' }}>Submitted
                    </option>
                    <option value="Offered"
                        {{ $offerdetails['status'] === 'Offered' ? 'selected hidden disabled' : '' }}>Offered</option>
                    <option value="Done" {{ $offerdetails['status'] === 'Done' ? 'selected hidden disabled' : '' }}>
                        Done</option>
                    <option value="Onboarding"
                        {{ $offerdetails['status'] === 'Onboarding hidden disabled' ? 'selected' : '' }}>
                        Onboarding</option>
                    {{-- <option value="Working" {{ $offerdetails['status'] === 'Working' ? 'selected' : '' }}>Working
                </option> --}}
                    <option value="Rejected"
                        {{ $offerdetails['status'] === 'Rejected' ? 'selected hidden disabled' : '' }}>Rejected
                    </option>
                    <option value="Blocked"
                        {{ $offerdetails['status'] === 'Blocked' ? 'selected hidden disabled' : '' }}>Blocked
                    </option>
                    <option value="Hold" {{ $offerdetails['status'] === 'Hold' ? 'selected hidden disabled' : '' }}>
                        Hold</option>
                </select>
            @endif
        </div>
        <div class="col-3">

            <button class="counter-save-for-button" style="margin-top:0px;"
                onclick="applicationStatus(document.getElementById('status application-status').value, '{{ $offerdetails->id }}')">Change
                Status</button>
        </div>
    </div>
</div>
<div class="ss-jb-apl-oninfrm-mn-dv">

    <div class="ss-jb-apply-on-inf-hed-rec row">
        <div class="col-md-6">
            <h5 class="mt-3 mb-3 text-center">Job Information</h5>
        </div>
        <div class="col-md-6">
            <h5 class="mt-3 mb-3 text-center">Worker Information</h5>
        </div>
    </div>

    {{-- Summary --}}
    <div class="row col-md-12 mb-4 mt-4 collapse-container">
        <p>
            <a class="btn first-collapse" data-toggle="collapse" href="#collapse-0">
                Summary
            </a>
        </p>
    </div>

    <div class="row mb-4 collapse-static-container" style="padding:0px;" id="collapse-0">

        {{-- type --}}

        <div class="col-md-12">
            <span class="mt-3">Job type</span>
        </div>
        <div class="row {{ $offerdetails->type == $userdetails->nurse->worker_job_type ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>
                    {{ $offerdetails->type ?? 'Missing job type Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @isset($userdetails->nurse->worker_job_type)
                        {{ $userdetails->nurse->worker_job_type }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_job_type', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endisset
                </p>
            </div>
        </div>
        {{-- terms --}}
        <div class="col-md-12">
            <span class="mt-3">Terms</span>
        </div>
        <div class="row {{ $offerdetails->terms == $userdetails->nurse->terms ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>
                    {{ $offerdetails->terms ?? 'Missing job terms Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @isset($userdetails->nurse->terms)
                        {{ $userdetails->nurse->terms }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'terms', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endisset
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Profession</span>
        </div>
        <div class="row {{ $offerdetails->profession == $userdetails->nurse->profession ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>
                    {{ $offerdetails->profession ?? 'Missing Profession Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @isset($userdetails->nurse->profession)
                        {{ $userdetails->nurse->profession }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'profession', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endisset
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Specialty</span>
        </div>
        <div class="row {{ $offerdetails->specialty == $userdetails->nurse->specialty ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->specialty ?? 'Missing Specialty Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->specialty)
                        {{ $userdetails->nurse->specialty }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'nursing_specialty', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- $/hr --}}
        <div class="col-md-12">
            <span class="mt-3">Actual Hourly rate</span>
        </div>
        <div class="row {{ $offerdetails->actual_hourly_rate === $userdetails->nurse->worker_actual_hourly_rate ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($offerdetails->actual_hourly_rate) ? number_format($offerdetails->actual_hourly_rate) : 'Missing Actual Hourly Rate Information' }}
                </h6>
            </div>
            <div class="col-md-6">
                <p>
                    {!! isset($userdetails->nurse->worker_actual_hourly_rate)
                        ? number_format($userdetails->nurse->worker_actual_hourly_rate)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_actual_hourly_rate\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}

                </p>
            </div>
        </div>
        {{-- $/wk --}}
        <div class="col-md-12">
            <span class="mt-3">Weekly Pay</span>
        </div>
        <div class="row {{ $offerdetails->weekly_pay === $userdetails->nurse->worker_organization_weekly_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($offerdetails->weekly_pay) ? number_format($offerdetails->weekly_pay) : 'Missing Weekly Pay Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_organization_weekly_amount)
                        ? number_format($userdetails->nurse->worker_organization_weekly_amount)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_organization_weekly_amount\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}

                </p>
            </div>
        </div>
        {{-- hrs/wk --}}
        <div class="col-md-12">
            <span class="mt-3">Hours/Week</span>
        </div>
        <div class="row {{ $offerdetails->hours_per_week == $userdetails->nurse->worker_hours_per_week ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($offerdetails->hours_per_week) ? number_format($offerdetails->hours_per_week) : 'Missing Hours/Week Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_hours_per_week)
                        ? number_format($userdetails->nurse->worker_hours_per_week)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_hours_per_week\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        {{-- state --}}
        <div class="col-md-12">
            <span class="mt-3">State</span>
        </div>
        <div class="row {{ $offerdetails->state == $userdetails->nurse->state ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->state ?? 'Missing State Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->state)
                        {{ $userdetails->nurse->state }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'state', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- city --}}
        <div class="col-md-12">
            <span class="mt-3">City</span>
        </div>
        <div class="row {{ $offerdetails->city == $userdetails->nurse->city ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->city ?? 'Missing City Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->city)
                        {{ $userdetails->nurse->city }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'city', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>

    </div>
    {{-- End  Summary --}}
    {{-- Shift --}}
    <div class="row col-md-12 mb-4 collapse-container">
        <p>
            <a class="btn first-collapse" data-toggle="collapse" href="#collapse-1" role="button"
                aria-expanded="false" aria-controls="collapseExample">
                Shift
            </a>
        </p>
    </div>

    <div style="margin:auto;" class="row collapse text-center mb-4" id="collapse-1">

        <div class="col-md-12">
            <span class="mt-3">Shift Time of Day</span>
        </div>
        <div class="row {{ $offerdetails->preferred_shift_duration == $userdetails->nurse->worker_shift_time_of_day ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->preferred_shift_duration ?? 'Missing Shift Time of Day Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->worker_shift_time_of_day)
                        {{ $userdetails->nurse->worker_shift_time_of_day }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_shift_time_of_day', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Guaranteed Hours --}}
        <div class="col-md-12">
            <span class="mt-3">Guaranteed Hours</span>
        </div>
        <div class="row {{ $offerdetails->guaranteed_hours == $userdetails->nurse->worker_guaranteed_hours ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($offerdetails->guaranteed_hours) ? number_format($offerdetails->guaranteed_hours) : 'Missing Guaranteed Hours Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_guaranteed_hours)
                        ? number_format($userdetails->nurse->worker_guaranteed_hours)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_guaranteed_hours\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        {{-- Hours/Shift --}}
        <div class="col-md-12">
            <span class="mt-3">Hours/Shift</span>
        </div>
        <div class="row {{ $offerdetails->hours_shift == $userdetails->nurse->worker_hours_shift ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($offerdetails->hours_shift) ? number_format($offerdetails->hours_shift) : 'Missing Hours/Shift Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_hours_shift)
                        ? number_format($userdetails->nurse->worker_hours_shift)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_hours_shift\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        {{-- Shifts/Week --}}
        <div class="col-md-12">
            <span class="mt-3">Shifts/Week</span>
        </div>
        <div class="row {{ $offerdetails->weeks_shift == $userdetails->nurse->worker_shifts_week ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($offerdetails->weeks_shift) ? number_format($offerdetails->weeks_shift) : 'Missing Shifts/Week Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_shifts_week)
                        ? number_format($userdetails->nurse->worker_shifts_week)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_shifts_week\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        {{-- Wks/Contract --}}
        <div class="col-md-12">
            <span class="mt-3">Wks/Contract</span>
        </div>
        <div class="row {{ $offerdetails->preferred_assignment_duration == $userdetails->nurse->worker_weeks_assignment ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($offerdetails->preferred_assignment_duration) ? number_format($offerdetails->preferred_assignment_duration) : 'Missing Wks/Contract Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_weeks_assignment)
                        ? number_format($userdetails->nurse->worker_weeks_assignment)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_weeks_assignment\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
                </p>
            </div>
        </div>
        {{-- Start Date --}}
        <div class="col-md-12">
            <span class="mt-3">Start Date</span>
        </div>
        <div class="row {{ $offerdetails->start_date == $userdetails->nurse->worker_start_date ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->start_date ?? 'As Soon As Possible' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->worker_start_date)
                        {{ $userdetails->nurse->worker_start_date }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_start_date', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- End Date --}}

        <div class="col-md-12">
            <span class="mt-3">End Date</span>
        </div>
        <div class="row {{ $offerdetails->end_date == $userdetails->nurse->worker_end_date ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->end_date ?? 'Missing End Date Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->worker_end_date)
                        {{ $userdetails->nurse->worker_end_date }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_end_date', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- rto --}}

        <div class="col-md-12">
            <span class="mt-3">RTO</span>
        </div>
        <div class="row {{ $offerdetails->rto === $userdetails->nurse->rto ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->rto ?? 'Missing RTO Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->rto)
                        {{ $userdetails->nurse->rto }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'rto', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
    </div>
    {{-- End Shift --}}
    {{-- Pay --}}
    <div class="row col-md-12 mb-4 collapse-container">
        <p>
            <a id="collapse-2-btn" class="btn first-collapse" data-toggle="collapse" href="#collapse-2"
                role="button" aria-expanded="false" aria-controls="collapseExample">
                Pay
            </a>
        </p>
    </div>
    <div style="margin:auto;" class="row collapse text-center mb-4" id="collapse-2">
        {{-- Overtime --}}
        <div class="col-md-12">
            <span class="mt-3">Overtime</span>
        </div>
        <div class="row {{ $offerdetails->overtime === $userdetails->nurse->worker_overtime_rate ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6> {{ isset($offerdetails->overtime) ? number_format($offerdetails->overtime) : 'Missing Overtime Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_overtime_rate)
                        ? number_format($userdetails->nurse->worker_overtime_rate)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_overtime_rate\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        {{-- on call --}}
        <div class="col-md-12">
            <span class="mt-3">On Call Hourly Rate</span>
        </div>
        <div class="row {{ $offerdetails->on_call_rate === $userdetails->nurse->worker_on_call ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6> {{ isset($offerdetails->on_call_rate) ? '$ ' . number_format($offerdetails->on_call_rate) : 'Missing On Call Information' }}
                </h6>

            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_on_call)
                        ? '$ ' . number_format($userdetails->nurse->worker_on_call)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_on_call\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        {{-- call back --}}
        <div class="col-md-12">
            <span class="mt-3">Call Back Hourly Rate</span>
        </div>
        <div class="row {{ $offerdetails->call_back_rate === $userdetails->nurse->worker_call_back ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($offerdetails->call_back_rate) ? '$ ' . number_format($offerdetails->call_back_rate) : 'Missing Call Back Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_call_back)
                        ? '$ ' . number_format($userdetails->nurse->worker_call_back)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_call_back\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        {{-- Orientation Rate --}}
        <div class="col-md-12">
            <span class="mt-3">Orientation Rate</span>
        </div>
        <div class="row {{ $offerdetails->orientation_rate === $userdetails->nurse->worker_orientation_rate ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($offerdetails->orientation_rate) ? '$ ' . number_format($offerdetails->orientation_rate) : 'Missing Orientation Rate Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_orientation_rate)
                        ? '$ ' . number_format($userdetails->nurse->worker_orientation_rate)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_orientation_rate\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        {{-- Weekly Taxable amount --}}
        <div class="col-md-12">
            <span class="mt-3">Est. Weekly Taxable amount</span>
        </div>
        <div class="row {{ $offerdetails->weekly_taxable_amount === $userdetails->nurse->worker_weekly_taxable_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6> {{ isset($offerdetails->weekly_taxable_amount) ? '$ ' . number_format($offerdetails->weekly_taxable_amount) : 'Missing Est. Weekly Taxable amount Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_weekly_taxable_amount)
                        ? '$ ' . number_format($userdetails->nurse->worker_weekly_taxable_amount)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_weekly_taxable_amount\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}

                </p>
            </div>
        </div>
        {{-- Weekly non-taxable amount --}}
        <div class="col-md-12">
            <span class="mt-3">Est. Weekly non-taxable amount</span>
        </div>
        <div class="row {{ $offerdetails->weekly_non_taxable_amount === $userdetails->nurse->worker_weekly_non_taxable_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($offerdetails->weekly_non_taxable_amount) ? '$ ' . number_format($offerdetails->weekly_non_taxable_amount) : 'Missing Est. Weekly non-taxable amount Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_weekly_non_taxable_amount)
                        ? '$ ' . number_format($userdetails->nurse->worker_weekly_non_taxable_amount)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_weekly_non_taxable_amount\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>

        {{-- Feels Like $/hr --}}

        <div class="col-md-12">
            <span class="mt-3">Feels Like $/hr</span>
        </div>
        <div class="row {{ $offerdetails->feels_like_per_hour === $userdetails->nurse->worker_feels_like_per_hour ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($offerdetails->feels_like_per_hour) ? '$ ' . number_format($offerdetails->feels_like_per_hour) : 'Missing Feels Like Per Hour Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_feels_like_per_hour)
                        ? '$ ' . number_format($userdetails->nurse->worker_feels_like_per_hour)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_feels_like_per_hour\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>

        {{--  Gw$/Wk --}}
        <div class="col-md-12">
            <span class="mt-3">Est. Goodwork Weekly Amount</span>
        </div>
        <div class="col-md-12">
            <h6>{{ isset($offerdetails->goodwork_weekly_amount) ? '$ ' . number_format($offerdetails->goodwork_weekly_amount) : 'Missing Est. Goodwork Weekly Amount Information' }}
            </h6>
        </div>
        {{-- Referral Bonus --}}
        <div class="col-md-12">
            <span class="mt-3">Referral Bonus</span>
        </div>
        <div class="row {{ $offerdetails->referral_bonus === $userdetails->nurse->worker_referral_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6> {{ isset($offerdetails->referral_bonus) ? '$ ' . number_format($offerdetails->referral_bonus) : 'Missing Referral Bonus Information' }}
                </h6>

            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_referral_bonus)
                        ? '$ ' . number_format($userdetails->nurse->worker_referral_bonus)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_referral_bonus\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>

        {{-- Sign-On Bonus --}}

        <div class="col-md-12">
            <span class="mt-3">Sign-On Bonus</span>
        </div>
        <div class="row {{ $offerdetails->sign_on_bonus === $userdetails->nurse->worker_sign_on_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($offerdetails->sign_on_bonus) ? '$ ' . number_format($offerdetails->sign_on_bonus) : 'Missing Sign-On Bonus Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_sign_on_bonus)
                        ? '$ ' . number_format($userdetails->nurse->worker_sign_on_bonus)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_sign_on_bonus\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>

        {{-- Completion Bonus --}}
        <div class="col-md-12">
            <span class="mt-3">Completion Bonus</span>
        </div>
        <div class="row {{ $offerdetails->completion_bonus === $userdetails->nurse->worker_completion_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($offerdetails->completion_bonus) ? '$ ' . number_format($offerdetails->completion_bonus) : 'Missing Completion Bonus Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_completion_bonus)
                        ? '$ ' . number_format($userdetails->nurse->worker_completion_bonus)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_completion_bonus\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        {{-- Extension Bonus --}}
        <div class="col-md-12">
            <span class="mt-3">Extension Bonus</span>
        </div>
        <div class="row {{ $offerdetails->extension_bonus === $userdetails->nurse->worker_extension_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($offerdetails->extension_bonus) ? '$ ' . number_format($offerdetails->extension_bonus) : 'Missing Extension Bonus Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_extension_bonus)
                        ? '$ ' . number_format($userdetails->nurse->worker_extension_bonus)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_extension_bonus\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        {{-- Other Bonus --}}
        <div class="col-md-12">
            <span class="mt-3">Other Bonus</span>
        </div>
        <div class="row {{ $offerdetails->other_bonus === $userdetails->nurse->worker_other_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($offerdetails->other_bonus) ? '$ ' . number_format($offerdetails->other_bonus) : 'Missing Other Bonus Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($userdetails->nurse->worker_other_bonus)
                        ? '$ ' . number_format($userdetails->nurse->worker_other_bonus)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_other_bonus\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        {{-- Pay Frequency --}}

        <div class="col-md-12">
            <span class="mt-3">Pay Frequency</span>
        </div>

        <div class="row {{ $offerdetails->pay_frequency === $userdetails->nurse->worker_pay_frequency ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">

            <div class="col-md-6">
                <h6>{{ $offerdetails->pay_frequency ?? 'Missing Pay Frequency Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->worker_pay_frequency)
                        {{ $userdetails->nurse->worker_pay_frequency }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_pay_frequency', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- Benefits --}}

        <div class="col-md-12">
            <span class="mt-3">Benefits</span>
        </div>
        @php
            $offerBenefits = !empty($offerdetails->benefits) ? explode(',', $offerdetails->benefits) : [];
            $userBenefits = !empty($userdetails->nurse->worker_benefits)
                ? explode(',', $userdetails->nurse->worker_benefits)
                : [];

            $offerBenefits = array_map('trim', $offerBenefits);
            $userBenefits = array_map('trim', $userBenefits);

            $benefitsMatch =
                !empty($offerBenefits) && !empty($userBenefits) && array_intersect($offerBenefits, $userBenefits);
        @endphp

        <div class="row {{ $benefitsMatch ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ !empty($offerBenefits) ? implode(', ', $offerBenefits) : 'Missing Benefits Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if (!empty($userBenefits))
                        {{ implode(', ', $userBenefits) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_benefits', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- Est. Total Organization Amount --}}
        <div class="col-md-12">
            <span class="mt-3">Est. Total Organization Amount</span>
        </div>
        <div class="col-md-12">
            <h6> {{ isset($offerdetails->total_organization_amount) ? '$ ' . number_format($offerdetails->total_organization_amount) : 'Missing Est. Total Organization Amount Information' }}
            </h6>
        </div>
        {{-- Total Goodwork Amount --}}
        <div class="col-md-12">
            <span class="mt-3">Est. Total Goodwork Amount</span>
        </div>
        <div class="col-md-12">
            <h6>{{ isset($offerdetails->total_goodwork_amount) ? '$ ' . number_format($offerdetails->total_goodwork_amount) : 'Missing Est. Total Goodwork Amount Information' }}
            </h6>
        </div>
        {{-- Total Contract Amount --}}
        <div class="col-md-12">
            <span class="mt-3">Est. Total Contract Amount</span>
        </div>
        <div class="col-md-12">
            <h6>{{ isset($offerdetails->total_contract_amount) ? '$ ' . number_format($offerdetails->total_contract_amount) : 'Missing Est. Total Contract Amount Information' }}
            </h6>
        </div>
    </div>
    {{-- End Pay --}}
    {{-- Location --}}
    <div class="row col-md-12 mb-4 collapse-container">
        <p>
            <a id="collapse-3-btn" class="btn first-collapse" data-toggle="collapse" href="#collapse-3"
                role="button" aria-expanded="false" aria-controls="collapseExample">
                Location
            </a>
        </p>
    </div>
    <div style="margin:auto;" class="row collapse text-center mb-4" id="collapse-3">
        {{-- clinical setting --}}

        <div class="col-md-12">
            <span class="mt-3">Clinical Setting</span>
        </div>
        <div class="row {{ $offerdetails->clinical_setting === $userdetails->nurse->clinical_setting_you_prefer ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->clinical_setting ?? 'Missing Clinical Setting Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->clinical_setting_you_prefer)
                        {{ $userdetails->nurse->clinical_setting_you_prefer }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'clinical_setting_you_prefer', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- Preferred Work Location --}}
        <div class="col-md-12">
            <span class="mt-3">Preferred Work Location</span>
        </div>
        <div class="row {{ $offerdetails->preferred_work_location === $userdetails->nurse->worker_preferred_work_location ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->preferred_work_location ?? 'Missing Preferred Work Location Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->worker_preferred_work_location)
                        {{ $userdetails->nurse->worker_preferred_work_location }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_preferred_work_location', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- Facility Name --}}

        <div class="col-md-12">
            <span class="mt-3">Facility Name</span>
        </div>
        <div class="row {{ $offerdetails->facility_name === $userdetails->nurse->worker_facility_name ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->facility_name ?? 'Missing Facility Name Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->worker_facility_name)
                        {{ $userdetails->nurse->worker_facility_name }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_facility_name', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- facilitys_parent_system | worker_facilitys_parent_system --}}
        <div class="col-md-12">
            <span class="mt-3">Facility's Parent System</span>
        </div>
        <div class="row {{ $offerdetails->facilitys_parent_system === $userdetails->nurse->worker_facilitys_parent_system ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">

            <div class="col-md-6">
                <h6>{{ $offerdetails->facilitys_parent_system ?? 'Missing Facility\'s Parent System Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->worker_facilitys_parent_system)
                        {{ $userdetails->nurse->worker_facilitys_parent_system }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_facilitys_parent_system', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Facility Shift Cancellation Policy --}}
        <div class="col-md-12">
            <span class="mt-3">Facility Shift Cancellation Policy</span>
        </div>
        <div class="row {{ $offerdetails->facility_shift_cancelation_policy === $userdetails->nurse->facility_shift_cancelation_policy ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->facility_shift_cancelation_policy ?? 'Missing Facility Shift Cancellation Policy Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->facility_shift_cancelation_policy)
                        {{ $userdetails->nurse->facility_shift_cancelation_policy }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'facility_shift_cancelation_policy', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Contract Termination Policy --}}
        <div class="col-md-12">
            <span class="mt-3">Contract Termination Policy</span>
        </div>
        <div class="row {{ $offerdetails->contract_termination_policy === $userdetails->nurse->contract_termination_policy ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->contract_termination_policy ?? 'Missing Contract Termination Policy Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->contract_termination_policy)
                        {{ $userdetails->nurse->contract_termination_policy }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'contract_termination_policy', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Traveler Distance From Facility --}}
        <div class="col-md-12">
            <span class="mt-3">Traveler Distance From Facility</span>
        </div>
        <div class="row {{ $offerdetails->traveler_distance_from_facility === $userdetails->nurse->distance_from_your_home ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->traveler_distance_from_facility ?? 'Missing Traveler Distance From Facility Information' }}
                    {{ $offerdetails->traveler_distance_from_facility ? 'miles Maximum' : '' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->distance_from_your_home)
                        {{ $userdetails->nurse->distance_from_your_home }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'distance_from_your_home', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>

    </div>

    {{-- Certifications --}}

    <div class="row col-md-12 mb-4 collapse-container">
        <p>
            <a id="collapse-4-btn" class="btn first-collapse" data-toggle="collapse" href="#collapse-4"
                role="button" aria-expanded="false" aria-controls="collapseExample">
                Certifications
            </a>
        </p>
    </div>
    <div style="margin:auto;" class="row collapse text-center mb-4" id="collapse-4">
        {{-- Professional Licensure --}}
        <div class="col-md-12">
            <span class="mt-3">Professional Licensure</span>
        </div>
        @php
            $offerLicenses = !empty($offerdetails->job_location) ? explode(',', $offerdetails->job_location) : [];
            $userLicenses = !empty($userdetails->nurse->worker_job_location)
                ? explode(',', $userdetails->nurse->worker_job_location)
                : [];

            $offerLicenses = array_map('trim', $offerLicenses);
            $userLicenses = array_map('trim', $userLicenses);

            $licensesMatch =
                !empty($offerLicenses) && !empty($userLicenses) && array_intersect($offerLicenses, $userLicenses);
        @endphp

        <div class="row {{ $licensesMatch ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ !empty($offerLicenses) ? implode(', ', $offerLicenses) : 'Missing Professional Licensure Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if (!empty($userLicenses))
                        {{ implode(', ', $userLicenses) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'job_location', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- Certifications --}}
        <div class="col-md-12">
            <span class="mt-3">Certifications</span>
        </div>

        @php
            $offerCertificates = !empty($offerdetails->certificate) ? explode(',', $offerdetails->certificate) : [];
            $offerCertificates = array_map('trim', $offerCertificates);
        @endphp

        <div id="certification" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ !empty($offerCertificates) ? implode(', ', $offerCertificates) : 'Missing Certifications Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p id="certification-placeholder">
                </p>
            </div>
        </div>




    </div>

    {{-- End Certifications --}}

    {{-- Work Info --}}

    <div class="row col-md-12 mb-4 collapse-container">
        <p>
            <a id="collapse-5-btn" class="btn first-collapse" data-toggle="collapse" href="#collapse-5"
                role="button" aria-expanded="false" aria-controls="collapseExample">
                Work Info
            </a>
        </p>
    </div>

    <div style="margin:auto;" class="row collapse text-center mb-4" id="collapse-5">
        {{-- description --}}
        <div class="col-md-12">
            <span class="mt-3">Description</span>
        </div>

        <div class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->description ?? 'Missing Description Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->worker_description)
                        {{ $userdetails->nurse->worker_description }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_description', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- urgency --}}
        <div class="col-md-12">
            <span class="mt-3">Urgency</span>
        </div>
        <div class="row {{ $offerdetails->urgency === $userdetails->nurse->worker_urgency ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>
                    @if ($offerdetails->urgency == 'Auto Offer')
                        Yes
                    @elseif(empty($offerdetails->urgency))
                        Missing Urgency Information
                    @else
                        No
                    @endif
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->worker_urgency == 'Auto Offer')
                        Yes
                    @elseif(empty($userdetails->nurse->worker_urgency))
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_urgency', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @else
                        No
                    @endif
                </p>
            </div>
        </div>

        {{-- Experience --}}

        <div class="col-md-12">
            <span class="mt-3">Preferred Experience</span>
        </div>
        <div class="row {{ $offerdetails->preferred_experience === $userdetails->nurse->worker_experience ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->preferred_experience . ' Year(s)' ?? 'Missing Preferred Experience Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->worker_experience)
                        {{ $userdetails->nurse->worker_experience . ' Year(s)' }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_experience', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- References --}}
        <div class="col-md-12">
            <span class="mt-3">References</span>
        </div>
        <div id="references" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->number_of_references . ' Reference(s)' ?? 'Missing References Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p id="references-placeholder">
                </p>
            </div>
        </div>

        {{-- Skills --}}
        <div class="col-md-12">
            <span class="mt-3">Skills</span>
        </div>

        @php
            $offerSkills = !empty($offerdetails->skills) ? explode(',', $offerdetails->skills) : [];
            $offerSkills = array_map('trim', $offerSkills);

        @endphp

        <div id="skills" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ !empty($offerSkills) ? implode(', ', $offerSkills) : 'Missing Skills Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p id="skills-placeholder">
                </p>
            </div>
        </div>


        {{-- On Call --}}

        <div class="col-md-12">
            <span class="mt-3">On Call</span>
        </div>

        <div class="row {{ $offerdetails->on_call === $userdetails->nurse->on_call ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>
                    @if ($offerdetails->on_call == '1')
                        Yes
                    @elseif($offerdetails->on_call == '0')
                        No
                    @else
                        ----
                    @endif
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->on_call == '1')
                        Yes
                    @elseif($userdetails->nurse->on_call == '0')
                        No
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'on_call', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- Block Scheduling --}}

        <div class="col-md-12">
            <span class="mt-3">Block scheduling</span>
        </div>
        <div class="row {{ $offerdetails->block_scheduling === $userdetails->nurse->block_scheduling ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>
                    @if ($offerdetails->block_scheduling == '1')
                        Yes
                    @elseif($offerdetails->block_scheduling == '0')
                        No
                    @else
                        ----
                    @endif
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->block_scheduling == '1')
                        Yes
                    @elseif($userdetails->nurse->block_scheduling == '0')
                        No
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'block_scheduling', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Float requirements --}}
        <div class="col-md-12">
            <span class="mt-3">Float requirements</span>
        </div>
        <div class="row {{ $offerdetails->float_requirement === $userdetails->nurse->float_requirement ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>
                    @if ($offerdetails->float_requirement == '1')
                        Yes
                    @elseif($offerdetails->float_requirement == '0')
                        No
                    @else
                        ----
                    @endif
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->float_requirement == '1')
                        Yes
                    @elseif($userdetails->nurse->float_requirement == '0')
                        No
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'float_requirement', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Patient ratio --}}
        <div class="col-md-12">
            <span class="mt-3">Patient ratio</span>
        </div>
        <div class="row {{ $offerdetails->Patient_ratio === $userdetails->nurse->worker_patient_ratio ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->Patient_ratio ?? 'Missing Patient Ratio Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->worker_patient_ratio)
                        {{ $userdetails->nurse->worker_patient_ratio }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_patient_ratio', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Emr --}}
        <div class="col-md-12">
            <span class="mt-3">EMR</span>
        </div>
        <div class="row {{ $offerdetails->Emr === $userdetails->nurse->worker_emr ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->Emr ?? 'Missing EMR Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->worker_emr)
                        {{ $userdetails->nurse->worker_emr }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_emr', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Unit --}}
        <div class="col-md-12">
            <span class="mt-3">Unit</span>
        </div>
        <div class="row {{ $offerdetails->Unit === $userdetails->nurse->worker_unit ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->Unit ?? 'Missing Unit Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->worker_unit)
                        {{ $userdetails->nurse->worker_unit }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_unit', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>

    </div>
    {{-- ID & Tax Info --}}
    <div class="row col-md-12 mb-4 collapse-container">
        <p>
            <a id="collapse-6-btn" class="btn first-collapse" data-toggle="collapse" href="#collapse-6"
                role="button" aria-expanded="false" aria-controls="collapseExample">
                ID & Tax Info
            </a>
        </p>
    </div>

    <div style="margin:auto;" class="row collapse text-center mb-4" id="collapse-6">
        {{-- classification / nurse_classification --}}
        <div class="col-md-12">
            <span class="mt-3">Classification</span>
        </div>

        <div class="row {{ $offerdetails->nurse_classification === $userdetails->nurse->nurse_classification ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->nurse_classification ?? 'Missing Classification Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($userdetails->nurse->nurse_classification)
                        {{ $userdetails->nurse->nurse_classification }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'nurse_classification', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>

    </div>

    {{-- Medical info --}}
    <div class="row col-md-12 mb-4 collapse-container">
        <p>
            <a id="collapse-7-btn" class="btn first-collapse" data-toggle="collapse" href="#collapse-7"
                role="button" aria-expanded="false" aria-controls="collapseExample">
                Medical Info
            </a>
        </p>
    </div>

    <div style="margin:auto;" class="row collapse text-center mb-4" id="collapse-7">
        {{-- vaccinations / worker_vaccination --}}

        <div class="col-md-12">
            <span class="mt-3">Vaccinations</span>
        </div>

        @php
            $offerVaccinations = !empty($offerdetails->vaccinations) ? explode(',', $offerdetails->vaccinations) : [];
            $offerVaccinations = array_map('trim', $offerVaccinations);
        @endphp

        <div id="vaccination" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ !empty($offerVaccinations) ? implode(', ', $offerVaccinations) : 'Missing Vaccinations Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p id="vaccination-placeholder">
                </p>
            </div>
        </div>

    </div>

</div>

@if ($offerdetails->status == 'Screening')
    <div class="ss-counter-buttons-div">
        <button class="ss-acpect-offer-btn" onclick="applicationStatus('Offered', '{{ $offerdetails->id }}')">Send
            1st
            Offer</button>
    </div>
    <div class="ss-counter-buttons-div">
        <button class="ss-counter-button" onclick="ChangeOfferInfo('{{ $offerdetails->id }}')">Change
            Offer</button>
    </div>
    <div class="ss-counter-buttons-div">
        <button class="ss-reject-offer-btn"
            onclick="AcceptOrRejectJobOffer('{{ $offerdetails->id }}', '{{ $offerdetails->job_id }}', 'rejectcounter')">Reject
            Offer</button>
    </div>
@endif

@if ($offerdetails->status == 'Offered')
    <div class="ss-counter-buttons-div">
        <button class="ss-reject-offer-btn"
            onclick="AcceptOrRejectJobOffer('{{ $offerdetails->id }}', '{{ $offerdetails->job_id }}', 'rejectcounter')">Reject
            Offer</button>
    </div>
    <div class="ss-counter-buttons-div">
        <button class="ss-counter-button" onclick="ChangeOfferInfo('{{ $offerdetails->id }}')">Change
            Offer</button>
    </div>
    @if (count($offerLogs) > 0)
        <div class="ss-counter-buttons-div">
            <button class="counter-save-for-button" onclick="counterOffer('{{ $offerdetails->id }}')">Counter
                Offer</button>
        </div>
        <div class="ss-counter-buttons-div">
            <button class="ss-acpect-offer-btn"
                onclick="AcceptOrRejectJobOffer('{{ $offerdetails->id }}', '{{ $offerdetails->job_id }}', 'offersend')">Accept
                Offer</button>
        </div>
    @endif
@endif

</div>
<script>
    var worker_files_displayname_by_type = [];
    var worker_files = [];
    var no_files = false;

    // certification
    var job_certification = "{!! $offerdetails->certificate !!}";
    var job_certification_displayname = job_certification.split(',').map(function(item) {

        return item.trim();

    });

    // vaccination
    var job_vaccination = "{!! $offerdetails->vaccinations !!}";
    var job_vaccination_displayname = job_vaccination.split(',').map(function(item) {

        return item.trim();

    });

    // references
    var number_of_references = "{!! $offerdetails->number_of_references !!}";

    // skills
    var job_skills = "{!! $offerdetails->skills !!}";
    var job_skills_displayname = job_skills.split(',').map(function(item) {

        return item.trim();

    });
    console.log('skills : ', job_skills_displayname);

    $(document).ready(async function() {

        worker_files = await get_all_files();
        console.log('Worker files:', worker_files);
        checkFileMatch('certification');
        checkFileMatch('vaccination');
        checkFileMatch('references');
        checkFileMatch('skills');
        // checkFileMatch('driving_license');
        // checkFileMatch('diploma');

    });

    function updateWorkerFilesList(file, fileType) {

        var worker_id = @json($offerdetails['worker_user_id']);
        var offer_id = @json($offerdetails['id']);
        var placeholder = document.getElementById(fileType + '-placeholder');
        console.log('file type:', fileType);
        console.log('Placeholder:', placeholder);

        if (file.length > 0 && no_files == false) {
            placeholder.innerHTML = file.join(', ');
        } else {
            let areaDiv = document.getElementById(fileType);
            areaDiv.classList.add('ss-s-jb-apl-bg-pink');
            placeholder.innerHTML = '<a style="cursor: pointer;" onclick="askWorker(this, \'' + fileType + '\', \'' +
                worker_id + '\', \'' + offer_id + '\')">Ask Worker</a>';
        }

    }

    function get_all_files() {

        var worker_id = @json($offerdetails['worker_user_id']);
        return new Promise((resolve, reject) => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('list-worker-docs') }}',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    WorkerId: worker_id
                }),
                success: function(resp) {
                    console.log('Success:', resp);

                    let jsonResp = JSON.parse(resp);
                    files = jsonResp;
                    resolve(
                        files
                    );
                },
                error: function(resp) {
                    no_files = true;
                    updateWorkerFilesList([], 'certification');
                    updateWorkerFilesList([], 'vaccination');
                    updateWorkerFilesList([], 'references');
                    updateWorkerFilesList([], 'skills');
                    console.log('Error:', resp);
                    reject(resp);
                }
            });
        });

    }

    async function get_all_files_displayName_by_type(type) {

        let files = worker_files.filter(file => file.type == type);
        let displayNames = [];
        if (type == 'references') {
            displayNames = files.map(file => file.ReferenceInformation.referenceName + ' - ' + file
                .ReferenceInformation.minTitle);
        } else {
            displayNames = files.map(file => file.displayName);
        }
        worker_files_displayname_by_type = displayNames;
        return displayNames;

    }

    async function checkFileMatch(inputName) {

        console.log('Checking files for:', inputName);
        let worker_files_displayname_by_type = [];

        try {

            worker_files_displayname_by_type = await get_all_files_displayName_by_type(inputName);
            console.log('Files:', worker_files_displayname_by_type);

        } catch (error) {

            console.error('Failed to get files:', error);

        }

        let areaDiv = document.getElementById(inputName);
        let check = false;

        if (inputName == 'certification') {

            const is_job_certif_exist_in_worker_files = job_certification_displayname.every(element =>
                worker_files_displayname_by_type.includes(element));
            updateWorkerFilesList(worker_files_displayname_by_type, 'certification');
            // console.log('job certification job name :', job_certification_displayname);
            // console.log('worker_files_displayname_by_type', worker_files_displayname_by_type);
            // console.log('is_job_certif_exist_in_worker_files', is_job_certif_exist_in_worker_files);

            if (is_job_certif_exist_in_worker_files) {
                check = true;
            }

        } else if (inputName == 'vaccination') {

            const is_job_vaccin_exist_in_worker_files = job_vaccination_displayname.every(element =>
                worker_files_displayname_by_type.includes(element));
            updateWorkerFilesList(worker_files_displayname_by_type, 'vaccination');
            // console.log('job vaccination job name :', job_vaccination_displayname);
            // console.log('worker_files_displayname_by_type', worker_files_displayname_by_type);
            // console.log('is_job_vaccin_exist_in_worker_files', is_job_vaccin_exist_in_worker_files);

            if (is_job_vaccin_exist_in_worker_files) {
                check = true;
            }

        } else if (inputName == 'references') {

            updateWorkerFilesList(worker_files_displayname_by_type, 'references');
            if (number_of_references <= worker_files_displayname_by_type.length) {
                check = true;
            }

        } else if (inputName == 'skills') {

            const is_job_skill_exist_in_worker_files = job_skills_displayname.every(element =>
                worker_files_displayname_by_type.includes(element));
            updateWorkerFilesList(worker_files_displayname_by_type, 'skills');
            // console.log('job skills job name :', job_skills_displayname)
            // console.log('worker_files_displayname_by_type', worker_files_displayname_by_type);
            // console.log('is_job_skill_exist_in_worker_files', is_job_skill_exist_in_worker_files);

            if (is_job_skill_exist_in_worker_files) {
                check = true;
            }

        } else if (inputName == 'driving_license') {

            if (worker_files_displayname_by_type.length > 0) {
                check = true;
            }

        } else if (inputName == 'diploma') {

            if (worker_files_displayname_by_type.length > 0) {
                check = true;
            }

        }

        if (check) {
            areaDiv.classList.remove('ss-s-jb-apl-bg-pink');
            areaDiv.classList.add('ss-s-jb-apl-bg-blue');
        } else {
            areaDiv.classList.remove('ss-s-jb-apl-bg-blue');
            areaDiv.classList.add('ss-s-jb-apl-bg-pink');
        }
    }


    document.addEventListener('DOMContentLoaded', function() {

        var workerId = @json($offerdetails['worker_user_id']);
        console.log(workerId);
        console.log('worker id', workerId);

        function activeWorkerClass(workerUserId) {

            var element = document.getElementById(workerUserId);
            console.log('element', element);
            element.classList.add('active');

        }
    });
</script>

<style>
    .btn.first-collapse,
    .btn.first-collapse:hover,
    .btn.first-collapse:focus,
    .btn.first-collapse:active {
        background-color: #fff8fd;
        color: rgb(65, 41, 57);
        font-size: 14px;
        font-family: 'Neue Kabel';
        font-style: normal;
        width: 100%;
    }

    .collapse-container,
    .collapse-static-container {
        margin: auto;
        text-align: center;
    }

    .green-bg {
        background-color: rgb(82, 222, 193);
    }
</style>
