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
</ul>

<div class="ss-jb-apl-oninfrm-mn-dv">

    <div class="ss-jb-apply-on-inf-hed-rec row">
        <div class="col-md-6">
            <h5 class="mt-3 mb-3 text-center">Worker Information</h5>
        </div>
        <div class="col-md-6">
            <h5 class="mt-3 mb-3 text-center">Job Information</h5>
        </div>
    </div>

    {{-- Summary --}}
    <div class="row col-md-12 mb-4 mt-4 collapse-container">
        <p>
            <a class="btn first-collapse" data-toggle="collapse" href="#collapse-0" role="button" aria-expanded="false" aria-controls="collapseExample">
                Summary
            </a>
        </p>
    </div>

    <div class="row mb-4 collapse text-center" style="padding:0px;" id="collapse-0">

        {{-- type --}}

        <div class="col-md-12">
            <span class="mt-3">Type</span>
        </div>
        <div class="row {{ $offerdetails->type == $userdetails->nurse->worker_job_type ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>
                    {{ $userdetails->nurse->worker_job_type ?? 'Missing job type Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    
                    @isset($offerdetails->type)
                        {{ $offerdetails->type }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'type', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                    {{ $userdetails->nurse->terms ?? 'Missing job terms Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @isset($offerdetails->terms)
                        {{ $offerdetails->terms }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'terms', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                            onclick="askRecruiter(this, 'profession', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                <h6>{{  $userdetails->nurse->specialty  ?? 'Missing Specialty Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->specialty)
                        {{ $offerdetails->specialty }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'specialty', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- $/hr --}}
        <div class="col-md-12">
            <span class="mt-3">$/hr</span>
        </div>
        <div class="row {{ $offerdetails->actual_hourly_rate === $userdetails->nurse->worker_actual_hourly_rate ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($userdetails->nurse->worker_actual_hourly_rate) ? number_format($userdetails->nurse->worker_actual_hourly_rate) : 'Missing Actual Hourly Rate Information' }}
                </h6>
            </div>
            <div class="col-md-6">
                <p>
                    {!! isset($offerdetails->actual_hourly_rate)
                        ? number_format($offerdetails->actual_hourly_rate)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'actual_hourly_rate\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}

                </p>
            </div>
        </div>
        {{-- $/wk --}}
        <div class="col-md-12">
            <span class="mt-3">$/wk</span>
        </div>
        <div class="row {{ $offerdetails->weekly_pay === $userdetails->nurse->worker_organization_weekly_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($userdetails->nurse->worker_organization_weekly_amount) ? number_format($userdetails->nurse->worker_organization_weekly_amount) : 'Missing Weekly Pay Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->weekly_pay)
                        ? number_format($offerdetails->weekly_pay)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'weekly_pay\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}

                </p>
            </div>
        </div>
        {{-- hrs/wk --}}
        <div class="col-md-12">
            <span class="mt-3">Hrs/Wk</span>
        </div>
        <div class="row {{ $offerdetails->hours_per_week == $userdetails->nurse->worker_hours_per_week ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($userdetails->nurse->worker_hours_per_week) ? number_format($userdetails->nurse->worker_hours_per_week) : 'Missing Hours/Week Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->hours_per_week)
                        ? number_format($offerdetails->hours_per_week)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'hours_per_week\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}
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
                <h6>{{ $userdetails->nurse->state ?? 'Missing State Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->state)
                        {{ $offerdetails->state }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'state', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                <h6>{{ $userdetails->nurse->city ?? 'Missing City Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->city)
                        {{ $offerdetails->city}}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'city', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Resume --}}
        <div class="col-md-12">
            <span class="mt-3">Resume</span>
        </div>

        <div id="resume" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->is_resume ? 'Required' : 'Not Required' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p id="resume-placeholder">
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
            <span class="mt-3">Shift Time</span>
        </div>
        <div class="row {{ $offerdetails->preferred_shift_duration == $userdetails->nurse->worker_shift_time_of_day ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $userdetails->nurse->worker_shift_time_of_day ?? 'Missing Shift Time of Day Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->preferred_shift_duration)
                        {{ $offerdetails->preferred_shift_duration }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'preferred_shift_duration', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Guaranteed Hours --}}
        <div class="col-md-12">
            <span class="mt-3">Guaranteed Hrs/wk</span>
        </div>
        <div class="row {{ $offerdetails->guaranteed_hours == $userdetails->nurse->worker_guaranteed_hours ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($userdetails->nurse->worker_guaranteed_hours) ? number_format($userdetails->nurse->worker_guaranteed_hours) : 'Missing Guaranteed Hours Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->guaranteed_hours)
                        ? number_format($offerdetails->guaranteed_hours)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'guaranteed_hours\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}
                </p>
            </div>
        </div>
        {{-- Hours/Shift --}}
        <div class="col-md-12">
            <span class="mt-3">Reg Hrs/Shift</span>
        </div>
        <div class="row {{ $offerdetails->hours_shift == $userdetails->nurse->worker_hours_shift ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($userdetails->nurse->worker_hours_shift) ? number_format($userdetails->nurse->worker_hours_shift) : 'Missing Hours/Shift Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->hours_shift)
                        ? number_format($offerdetails->hours_shift)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'worker_hours_shift\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}
                </p>
            </div>
        </div>
        {{-- Shifts/Week --}}
        <div class="col-md-12">
            <span class="mt-3">Shifts/Wk</span>
        </div>
        <div class="row {{ $offerdetails->weeks_shift == $userdetails->nurse->worker_shifts_week ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($userdetails->nurse->worker_shifts_week) ? number_format($userdetails->nurse->worker_shifts_week) : 'Missing Shifts/Week Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->weeks_shift)
                        ? number_format($offerdetails->weeks_shift)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'weeks_shift\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}
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
                <h6>{{ isset($userdetails->nurse->worker_weeks_assignment) ? number_format($userdetails->nurse->worker_weeks_assignment) : 'Missing Wks/Contract Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->preferred_assignment_duration)
                        ? number_format($offerdetails->preferred_assignment_duration)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'preferred_assignment_duration\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}
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
                <h6>{{ $userdetails->nurse->worker_start_date ?? 'As Soon As Possible' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->start_date )
                        {{ $offerdetails->start_date  }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'start_date', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                <h6>{{ $userdetails->nurse->worker_end_date ?? 'Missing End Date Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->end_date)
                        {{ $offerdetails->end_date }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'worker_end_date', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                <h6>{{ $userdetails->nurse->rto ?? 'Missing RTO Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->rto)
                        {{ $offerdetails->rto }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'rto', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
            <span class="mt-3">OT $/Hr</span>
        </div>
        <div class="row {{ $offerdetails->overtime === $userdetails->nurse->worker_overtime_rate ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6> {{ isset($userdetails->nurse->worker_overtime_rate) ? number_format($userdetails->nurse->worker_overtime_rate) : 'Missing Overtime Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->overtime)
                        ? number_format($offerdetails->overtime)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'overtime\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}
                </p>
            </div>
        </div>
        {{-- on call --}}
        <div class="col-md-12">
            <span class="mt-3">On Call $/Hr</span>
        </div>
        <div class="row {{ $offerdetails->on_call_rate === $userdetails->nurse->worker_on_call ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6> {{ isset($userdetails->nurse->worker_on_call) ? '$ ' . number_format($userdetails->nurse->worker_on_call) : 'Missing On Call Information' }}
                </h6>

            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->on_call_rate)
                        ? '$ ' . number_format($offerdetails->on_call_rate)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'on_call_rate\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}
                </p>
            </div>
        </div>
        {{-- call back --}}
        <div class="col-md-12">
            <span class="mt-3">Call Back $/Hr</span>
        </div>
        <div class="row {{ $offerdetails->call_back_rate === $userdetails->nurse->worker_call_back ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($userdetails->nurse->worker_call_back) ? '$ ' . number_format($userdetails->nurse->worker_call_back) : 'Missing Call Back Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->call_back_rate)
                        ? '$ ' . number_format($offerdetails->call_back_rate)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'call_back_rate\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}
                </p>
            </div>
        </div>
        {{-- Orientation Rate --}}
        <div class="col-md-12">
            <span class="mt-3">Orientation $/Hr</span>
        </div>
        <div class="row {{ $offerdetails->orientation_rate === $userdetails->nurse->worker_orientation_rate ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($userdetails->nurse->worker_orientation_rate) ? '$ ' . number_format($userdetails->nurse->worker_orientation_rate) : 'Missing Orientation Rate Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->orientation_rate)
                        ? '$ ' . number_format($offerdetails->orientation_rate)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'orientation_rate\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}
                </p>
            </div>
        </div>
        {{-- Weekly Taxable amount --}}
        <div class="col-md-12">
            <span class="mt-3">Taxable/Wk</span>
        </div>
        <div class="row {{ $offerdetails->weekly_taxable_amount === $userdetails->nurse->worker_weekly_taxable_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6> {{ isset($userdetails->nurse->worker_weekly_taxable_amount ) ? '$ ' . number_format($userdetails->nurse->worker_weekly_taxable_amount ) : 'Missing Est. Weekly Taxable amount Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->weekly_taxable_amount)
                        ? '$ ' . number_format($offerdetails->weekly_taxable_amount)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'weekly_taxable_amount\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}

                </p>
            </div>
        </div>
        {{-- Weekly non-taxable amount --}}
        <div class="col-md-12">
            <span class="mt-3">Non-taxable/Wk</span>
        </div>
        <div class="row {{ $offerdetails->weekly_non_taxable_amount === $userdetails->nurse->worker_weekly_non_taxable_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ isset($userdetails->nurse->worker_weekly_non_taxable_amount) ? '$ ' . number_format($userdetails->nurse->worker_weekly_non_taxable_amount) : 'Missing Est. Weekly non-taxable amount Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->weekly_non_taxable_amount)
                        ? '$ ' . number_format($offerdetails->weekly_non_taxable_amount)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'weekly_non_taxable_amount\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}
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
                <h6>{{ isset($userdetails->nurse->worker_feels_like_per_hour) ? '$ ' . number_format($userdetails->nurse->worker_feels_like_per_hour) : 'Missing Feels Like Per Hour Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->feels_like_per_hour)
                        ? '$ ' . number_format($offerdetails->feels_like_per_hour)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'feels_like_per_hour\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}
                </p>
            </div>
        </div>

        {{--  Gw$/Wk --}}
        {{-- <div class="col-md-12">
            <span class="mt-3">Est. Goodwork Weekly Amount</span>
        </div>
        <div class="col-md-12">
            <h6>{{ isset($offerdetails->goodwork_weekly_amount) ? '$ ' . number_format($offerdetails->goodwork_weekly_amount) : 'Missing Est. Goodwork Weekly Amount Information' }}
            </h6>
        </div> --}}
        {{-- Referral Bonus --}}
        <div class="col-md-12">
            <span class="mt-3">Referral Bonus</span>
        </div>
        <div class="row {{ $offerdetails->referral_bonus === $userdetails->nurse->worker_referral_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6> {{ isset($userdetails->nurse->worker_referral_bonus) ? '$ ' . number_format($userdetails->nurse->worker_referral_bonus) : 'Missing Referral Bonus Information' }}
                </h6>

            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->referral_bonus)
                        ? '$ ' . number_format($offerdetails->referral_bonus)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'referral_bonus\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}
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
                <h6>{{ isset($userdetails->nurse->worker_sign_on_bonus) ? '$ ' . number_format($userdetails->nurse->worker_sign_on_bonus) : 'Missing Sign-On Bonus Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->sign_on_bonus)
                        ? '$ ' . number_format($offerdetails->sign_on_bonus)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'sign_on_bonus\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}
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
                <h6>{{ isset($userdetails->nurse->worker_extension_bonus) ? '$ ' . number_format($userdetails->nurse->worker_extension_bonus) : 'Missing Extension Bonus Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->extension_bonus)
                        ? '$ ' . number_format($offerdetails->extension_bonus)
                        : '<a style="cursor: pointer;" onclick="askRecruiter(this, \'worker_extension_bonus\', \'' .
                            $userdetails->nurse->id .
                            '\', \'' .
                            $offerdetails->recruiter_id .
                            '\', \'' .
                             $offerdetails->organization_id .
                            '\', \'' .
                            $userdetails->first_name .
                            ' ' .
                            $userdetails->last_name .
                            '\')">Ask Recruiter</a>' !!}
                </p>
            </div>
        </div>
        {{-- Other Bonus --}}

         {{-- Est. Total Organization Amount --}}
         <div class="col-md-12">
            <span class="mt-3">$/Org</span>
        </div>
        <div class="col-md-12">
            <h6> {{ isset($offerdetails->total_organization_amount) ? '$ ' . number_format($offerdetails->total_organization_amount) : 'Missing Est. Total Organization Amount Information' }}
            </h6>
        </div>
        {{-- Pay Frequency --}}

        <div class="col-md-12">
            <span class="mt-3">Pay Frequency</span>
        </div>

        <div class="row {{ $offerdetails->pay_frequency === $userdetails->nurse->worker_pay_frequency ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">

            <div class="col-md-6">
                <h6>{{ $userdetails->nurse->worker_pay_frequency ?? 'Missing Pay Frequency Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->pay_frequency)
                        {{ $offerdetails->pay_frequency }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'pay_frequency', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                <h6>{{ !empty($userBenefits) ? implode(', ', $userBenefits) : 'Missing Benefits Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if (!empty($offerBenefits))
                        {{ implode(', ', $offerBenefits) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'benefits', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
                    @endif
                </p>
            </div>
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
                <h6>{{ $userdetails->nurse->clinical_setting_you_prefer ?? 'Missing Clinical Setting Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->clinical_setting)
                        {{ $offerdetails->clinical_setting }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'clinical_setting', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- Preferred Work Location --}}
        <div class="col-md-12">
            <span class="mt-3">Adress</span>
        </div>
        <div class="row {{ $offerdetails->preferred_work_location === $userdetails->nurse->worker_preferred_work_location ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $userdetails->nurse->worker_preferred_work_location ?? 'Missing Preferred Work Location Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->preferred_work_location)
                        {{ $offerdetails->preferred_work_location }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'preferred_work_location', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- Facility Name --}}

        <div class="col-md-12">
            <span class="mt-3">Facility</span>
        </div>
        <div class="row {{ $offerdetails->facility_name === $userdetails->nurse->worker_facility_name ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $userdetails->nurse->worker_facility_name ?? 'Missing Facility Name Information' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->facility_name)
                        {{ $offerdetails->facility_name }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'facility_name', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                <h6>{{ $userdetails->nurse->worker_facilitys_parent_system ?? 'Missing Facility\'s Parent System Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->facilitys_parent_system)
                        {{ $offerdetails->facilitys_parent_system }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'facilitys_parent_system', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                <h6>{{  $userdetails->nurse->facility_shift_cancelation_policy ?? 'Missing Facility Shift Cancellation Policy Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->facility_shift_cancelation_policy)
                        {{ $offerdetails->facility_shift_cancelation_policy }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'facility_shift_cancelation_policy', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                <h6>{{ $userdetails->nurse->contract_termination_policy ?? 'Missing Contract Termination Policy Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->contract_termination_policy)
                        {{ $offerdetails->contract_termination_policy }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'contract_termination_policy', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Traveler Distance From Facility --}}
        <div class="col-md-12">
            <span class="mt-3">Perm address miles from facility</span>
        </div>
        <div class="row {{ $offerdetails->traveler_distance_from_facility === $userdetails->nurse->distance_from_your_home ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $userdetails->nurse->distance_from_your_home ?? 'Missing Traveler Distance From Facility Information' }}
                    {{ $userdetails->nurse->distance_from_your_home ? 'miles Maximum' : '' }}</h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->traveler_distance_from_facility)
                        {{ $offerdetails->traveler_distance_from_facility }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'traveler_distance_from_facility', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                <h6>{{ !empty($userLicenses) ? implode(', ', $userLicenses) : 'Missing Professional Licensure Information' }}
                </h6>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if (!empty($offerLicenses))
                        {{ implode(', ', $offerLicenses) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'job_location', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
            <div class="col-md-6 ">
                <p id="certification-placeholder">
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if (!empty($offerCertificates))
                        {{ implode(', ', $offerCertificates) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'certificate', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
                    @endif
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
                            onclick="askRecruiter(this, 'worker_description', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- Experience --}}

        <div class="col-md-12">
            <span class="mt-3">Experience</span>
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
                            onclick="askRecruiter(this, 'worker_experience', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- References --}}
        <div class="col-md-12">
            <span class="mt-3">References</span>
        </div>
        <div id="references" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6 ">
                <p id="references-placeholder">
                </p>
            </div>
            <div class="col-md-6">
                <h6>{{ $offerdetails->number_of_references . ' Reference(s)' ?? 'Missing References Information' }}
                </h6>
            </div>
        </div>

        {{-- Skills --}}
        <div class="col-md-12">
            <span class="mt-3">Skills checklist</span>
        </div>

        @php
            $offerSkills = !empty($offerdetails->skills) ? explode(',', $offerdetails->skills) : [];
            $offerSkills = array_map('trim', $offerSkills);

        @endphp

        <div id="skills" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6 ">
                <p id="skills-placeholder">
                </p>
            </div>
            <div class="col-md-6">
                <h6>{{ !empty($offerSkills) ? implode(', ', $offerSkills) : 'Missing Skills Information' }}</h6>
            </div>    
        </div>


        {{-- On Call --}}

        <div class="col-md-12">
            <span class="mt-3">On Call</span>
        </div>

        <div class="row {{ $offerdetails->on_call === $userdetails->nurse->worker_on_call ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
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
                            onclick="askRecruiter(this, 'on_call', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                            onclick="askRecruiter(this, 'block_scheduling', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                            onclick="askRecruiter(this, 'float_requirement', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Patient ratio --}}
        <div class="col-md-12">
            <span class="mt-3">Patient Ratio Max</span>
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
                            onclick="askRecruiter(this, 'worker_patient_ratio', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                            onclick="askRecruiter(this, 'worker_emr', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                            onclick="askRecruiter(this, 'worker_unit', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
                            onclick="askRecruiter(this, 'nurse_classification', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask Recruiter</a>
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
            <div class="col-md-6 ">
                <p id="vaccination-placeholder">
                </p>
            </div>
            <div class="col-md-6">
                <h6>{{ !empty($offerVaccinations) ? implode(', ', $offerVaccinations) : 'Missing Vaccinations Information' }}
                </h6>
            </div>
        </div>

    </div>

</div>

@if ($offerdetails->status == 'Screening' || $offerdetails->status == 'Submitted')
    <div class="ss-counter-buttons-div">
        <button class="ss-counter-button" onclick="counterOffer('{{ $offerdetails->id }}')" >Make
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

    $(document).ready(async function() {

        worker_files = await get_all_files();
        checkFileMatch('certification');
        checkFileMatch('vaccination');
        checkFileMatch('references');
        checkFileMatch('skills');
        //checkFileMatch('driving_license');
        //checkFileMatch('diploma');
        checkFileMatch('resume');

    });

    function updateWorkerFilesList(file, fileType) {

        var worker_id = @json($offerdetails['worker_user_id']);
        var offer_id = @json($offerdetails['id']);
        var placeholder = document.getElementById(fileType + '-placeholder');

        if (file.length > 0 && no_files == false) {
            
            if(fileType == "resume"){
            
                placeholder.innerHTML = "Provided";
            
            }else{
                
                placeholder.innerHTML = file.join(', ');
            }
        
        } else {
            let areaDiv = document.getElementById(fileType);
            areaDiv.classList.add('ss-s-jb-apl-bg-pink');
            placeholder.innerHTML = '<h6>Missing ' + fileType + ' Information';
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

        let worker_files_displayname_by_type = [];

        try {

            worker_files_displayname_by_type = await get_all_files_displayName_by_type(inputName);

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

        } else if (inputName == "resume"){
            
            updateWorkerFilesList(worker_files_displayname_by_type, 'resume');

            let is_resume = @json($offerdetails["is_resume"]);

            if (worker_files_displayname_by_type.length > 0 && is_resume) {

                check = true;
            
            }else if (worker_files_displayname_by_type.length > 0 && !is_resume){
                check = true;
            }else{
                check = false;
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

        function activeWorkerClass(workerUserId) {

            var element = document.getElementById(workerUserId);
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
