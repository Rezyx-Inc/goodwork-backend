@php
    function truncateText($text, $limit = 35)
    {
        return mb_strimwidth($text, 0, $limit, '...');
    }
@endphp
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
            <a class="btn first-collapse" data-toggle="collapse" href="#collapse-0" role="button" aria-expanded="false"
                aria-controls="collapseExample">
                Summary
            </a>
        </p>
    </div>

    <div class="row mb-4 collapse text-center" style="padding:0px;" id="collapse-0">

        {{-- type --}}

        <div class="col-md-12">
            <span class="mt-3">Type</span>
        </div>
        <div id='worker_job_type' class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">

                <p class="profile_info_text" data-target="worker_job_type" data-title="Your Type !"
                    data-name="worker_job_type" onclick="open_multiselect_modal(this)">

                    @if (!!$userdetails->nurse->worker_job_type)
                        {{ truncateText($userdetails->nurse->worker_job_type) }}
                    @else
                        Your Type !
                    @endif
                </p>

            </div>
            <div class="col-md-6 ">
                <p>

                    @isset($offerdetails->type)
                        {{ $offerdetails->type }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'type', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endisset
                </p>
            </div>
        </div>
        {{-- terms --}}
        <div class="col-md-12">
            <span class="mt-3">Terms</span>
        </div>
        <div id="terms" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">

                <p class="profile_info_text" data-target="terms" data-title="What kind of terms are you?"
                    data-name="terms" onclick="open_multiselect_modal(this)">

                    @if (!!$userdetails->nurse->terms)
                        {{ truncateText($userdetails->nurse->terms) }}
                    @else
                        What kind of terms are you?
                    @endif
                </p>

            </div>
            <div class="col-md-6 ">
                <p>
                    @isset($offerdetails->terms)
                        {{ $offerdetails->terms }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'terms', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endisset
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Profession</span>
        </div>
        <div id="profession" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="profession" data-title="What kind of professional are you?"
                    data-name="profession" onclick="open_multiselect_modal(this)">

                    @if (!!$userdetails->nurse->profession)
                        {{ truncateText($userdetails->nurse->profession) }}
                    @else
                        What kind of professional are you?
                    @endif
                </p>

            </div>
            <div class="col-md-6 ">
                <p>
                    @isset($offerdetails->profession)
                        {{ $offerdetails->profession }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'profession', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endisset
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Specialty</span>
        </div>
        <div id="specialty" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="specialty" data-title="What's your specialty?"
                    data-name="specialty" onclick="open_multiselect_modal(this)">

                    @if (!!$userdetails->nurse->specialty)
                        {{ truncateText($userdetails->nurse->specialty) }}
                    @else
                        What's your specialty?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->specialty)
                        {{ $offerdetails->specialty }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'specialty', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- $/hr --}}
        <div class="col-md-12">
            <span class="mt-3">$/hr</span>
        </div>
        <div id="worker_actual_hourly_rate" class="row  d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input_number" data-title="What rate is fair?"
                    data-placeholder="What rate is fair?" data-name="worker_actual_hourly_rate"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_actual_hourly_rate))
                        $ {{ number_format($userdetails->nurse->worker_actual_hourly_rate) }}
                    @else
                        What rate is fair?
                    @endif
                </p>
            </div>
            <div class="col-md-6">
                <p>
                    {!! isset($offerdetails->actual_hourly_rate)
                        ? '$ ' . number_format($offerdetails->actual_hourly_rate)
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
        <div id="worker_organization_weekly_amount" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input_number" data-title="What rate is fair?"
                    data-placeholder="What rate is fair?" data-name="worker_organization_weekly_amount"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_organization_weekly_amount))
                        $ {{ number_format($userdetails->nurse->worker_organization_weekly_amount) }}
                    @else
                        What rate is fair?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->weekly_pay)
                        ? '$ ' . number_format($offerdetails->weekly_pay)
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
        <div id="worker_hours_per_week" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="multi_input_number" data-title="Maximum hours per week you'll work?"
                    data-placeholder="Ideal hours per week?" data-name="worker_hours_per_week" data-value=""
                    onclick="open_modal(this)">
                    @if (!isset($userdetails->nurse->worker_hours_per_week))
                        Ideal hours per week?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->hours_per_week)
                        ? number_format($offerdetails->hours_per_week) . ($offerdetails->hours_per_week > 1 ? ' hours' : ' hour')
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
        <div id="state" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="state" data-title="States you'd like to work?"
                    data-name="state" onclick="open_multiselect_modal(this)">

                    @if (!!$userdetails->nurse->state)
                        {{ truncateText($userdetails->nurse->state) }}
                    @else
                        States you'd like to work?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->state)
                        {{ $offerdetails->state }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'state', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- city --}}
        <div class="col-md-12">
            <span class="mt-3">City</span>
        </div>
        <div id="city" class="row  d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input" data-title="Cities you'd like to work?"
                 data-placeholder="Cities you'd like to work?'"    data-name="city" onclick="open_modal(this)">

                    @if (!!$userdetails->nurse->city)
                        {{ truncateText($userdetails->nurse->city) }}
                    @else
                        Cities you'd like to work?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->city)
                        {{ $offerdetails->city }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'city', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        @if (in_array($offerdetails->status, array('Screening','Submitted')))
            {{-- Resume --}}
            <div class="col-md-12">
                <span class="mt-3">Resume</span>
            </div>

            <div id="resume" class="row d-flex align-items-center" style="margin:auto;">
                <div class="col-md-6">
                    <p id="resume-placeholder" class="profile_info_text" data-target="resume_file" data-title="Resume"
                        data-name="resume" onclick="open_modal(this)">
                        @if (!!$userdetails->nurse->resume)
                            {{ truncateText($userdetails->nurse->resume) }}
                        @else
                            Resume
                        @endif
                    </p>
                </div>
                <div class="col-md-6 ">
                    {{-- is_resume required or ask recruiter --}}
                    <p>
                        @if ($offerdetails->is_resume_required)
                            Required
                        @else
                            <a style="cursor: pointer;"
                                onclick="askRecruiter(this, 'is_resume_required', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                                Recruiter</a>
                        @endif
                    </p>

                </div>
            </div>
        @endif

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
        <div id="worker_shift_time_of_day" class="row  d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="worker_shift_time_of_day" data-title="Fav shift?"
                    data-name="worker_shift_time_of_day" onclick="open_multiselect_modal(this)">

                    @if (!!$userdetails->nurse->worker_shift_time_of_day)
                        {{ truncateText($userdetails->nurse->worker_shift_time_of_day) }}
                    @else
                        Fav shift?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->preferred_shift_duration)
                        {{ $offerdetails->preferred_shift_duration }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'preferred_shift_duration', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Guaranteed Hours --}}
        <div class="col-md-12">
            <span class="mt-3">Guaranteed Hrs/wk</span>
        </div>
        <div id="worker_guaranteed_hours" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="multi_input_number" data-title="Minimum guaranteed Hrs/wk"
                     data-name="worker_guaranteed_hours"  data-value=""
                    onclick="open_modal(this)">
                    @if (!isset($userdetails->nurse->worker_guaranteed_hours))
                        Open to jobs with no guaranteed hours?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->guaranteed_hours)
                        ? number_format($offerdetails->guaranteed_hours) . ($offerdetails->guaranteed_hours > 1 ? ' hours' : ' hour')
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
            <span class="mt-3">Hrs/Shift</span>
        </div>
        <div id="worker_hours_shift" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="multi_input_number" data-title="Hours per shift you'll work?"
                    data-placeholder="Preferred hours per shift" data-name="worker_hours_shift" data-value=""
                    onclick="open_modal(this)">
                    @if (!isset($userdetails->nurse->worker_hours_shift))
                        Preferred hours per shift
                    @endif
                </p>

            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->hours_shift)
                        ? number_format($offerdetails->hours_shift) . ($offerdetails->hours_shift > 1 ? ' hours' : ' hour')
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
        <div id="worker_shifts_week" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="multi_input_number" data-title="Shifts per week?"
                    data-placeholder="Ideal shifts per week" data-name="worker_shifts_week" data-value=""
                    onclick="open_modal(this)">
                    @if (!isset($userdetails->nurse->worker_shifts_week))
                        Ideal shifts per week
                    @endif
                </p>
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
        <div id="worker_weeks_assignment" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="multi_input_number" data-title="How many weeks?"
                    data-placeholder="How many weeks?" data-name="worker_weeks_assignment"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_weeks_assignment))
                        How many weeks?
                    @endif
                </p>
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
        @if (isset($offerdetails->as_soon_as) && $offerdetails->as_soon_as == '1')
            <div id="worker_as_soon_as_possible" class="row ss-s-jb-apl-on-inf-txt-ul as_soon_as_item">
                <div class="col-md-6">
                    <p class="profile_info_text" data-target="binary" data-title="Can you start as soon as possible?"
                        data-name="worker_as_soon_as_possible" onclick="open_modal(this)">
                        @if (!!$userdetails->nurse->worker_as_soon_as_possible)
                            {{ $userdetails->nurse->worker_as_soon_as_possible == 1 ? ' as soon as possible' : ' as soon as possible' }}
                        @else
                            Can you start as soon as possible?
                        @endif
                    </p>
                </div>
                <div class="col-md-6">

                    <h6>As soon as possible</h6>
                </div>
            </div>
        @else
            <div id="worker_start_date" class="row ss-s-jb-apl-on-inf-txt-ul start_date_item ">

                <div class="col-md-6">

                    <p class="profile_info_text" data-target="date" data-title="When can you start?"
                        data-name="worker_start_date" onclick="open_modal(this)">
                        @if (!!$userdetails->nurse->worker_start_date)
                            {{ $userdetails->nurse->worker_start_date }}
                        @else
                            When can you start?
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <h6>{{ $offerdetails->start_date ?? 'As Soon As Possible' }} </h6>
                </div>
            </div>

        @endif
        {{-- End Date --}}

        <div class="col-md-12">
            <span class="mt-3">End Date</span>
        </div>
        <div id="worker_end_date" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="date" data-title="When can you end?"
                    data-name="worker_end_date" onclick="open_modal(this)">
                    @if (!!$userdetails->nurse->worker_end_date)
                        {{ $userdetails->nurse->worker_end_date }}
                    @else
                        When can you end?
                    @endif
                </p>

            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->end_date)
                        {{ $offerdetails->end_date }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'worker_end_date', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- rto --}}

        <div class="col-md-12">
            <span class="mt-3">RTO</span>
        </div>
        <div id="rto" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input" data-title="Any time off?"
                    data-placeholder="What rate is fair?" data-name="rto" onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->rto))
                        {{ $userdetails->nurse->rto }}
                    @else
                        Any time off?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->rto)
                        {{ $offerdetails->rto }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'rto', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
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
        <div id="worker_overtime_rate" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input_number" data-title="What rate is fair?"
                    data-placeholder="What rate is fair?" data-name="worker_overtime_rate"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_overtime_rate))
                        $ {{ number_format($userdetails->nurse->worker_overtime_rate) }}
                    @else
                        What rate is fair?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    {!! isset($offerdetails->overtime)
                        ? '$ ' . number_format($offerdetails->overtime)
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
        <div id="worker_on_call" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input_number" data-title="What rate is fair?"
                    data-placeholder="What rate is fair?" data-name="worker_on_call" onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_on_call))
                        $ {{ number_format($userdetails->nurse->worker_on_call) }}
                    @else
                        What rate is fair?
                    @endif
                </p>

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
        <div id="worker_call_back" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input_number" data-title="What rate is fair?"
                    data-placeholder="What rate is fair?" data-name="worker_call_back" onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_call_back))
                        $ {{ number_format($userdetails->nurse->worker_call_back) }}
                    @else
                        What rate is fair?
                    @endif
                </p>
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
        <div id="worker_orientation_rate" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input_number" data-title="What rate is fair?"
                    data-placeholder="What rate is fair?" data-name="worker_orientation_rate"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_orientation_rate))
                        $ {{ number_format($userdetails->nurse->worker_orientation_rate) }}
                    @else
                        What rate is fair?
                    @endif
                </p>
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
        {{-- <div id="worker_weekly_taxable_amount" --}}
        <div class="row d-flex align-items-center" style="margin:auto;">
            {{-- <div class="col-md-6">
                <p class="profile_info_text" data-target="input_number"
                    data-title="What rate is fair?" data-placeholder="What rate is fair?"
                    data-name="worker_weekly_taxable_amount" onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_weekly_taxable_amount))
                        $ {{ number_format($userdetails->nurse->worker_weekly_taxable_amount) }}
                    @else
                        What rate is fair?
                    @endif
            </div> --}}
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
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
        <div id="worker_weekly_non_taxable_amount_check" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="binary" data-title="Are you going to duplicate expenses?"
                    data-placeholder="Weekly non-taxable amount" data-name="worker_weekly_non_taxable_amount_check"
                    onclick="open_modal(this)">
                    @if (!!$userdetails->nurse->worker_weekly_non_taxable_amount_check)
                        {{ $userdetails->nurse->worker_weekly_non_taxable_amount_check == 1 ? 'Yes' : 'No' }}
                    @else
                        Are you going to duplicate expenses?
                    @endif
                </p>
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
        <div id="worker_feels_like_per_hour_check" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="binary"
                    data-title="Does this seem fair based on the market?"
                    data-placeholder="Does this seem fair based on the market?"
                    data-name="worker_feels_like_per_hour_check" onclick="open_modal(this)">
                    @if (!!$userdetails->nurse->worker_feels_like_per_hour_check)
                        {{ $userdetails->nurse->worker_feels_like_per_hour_check == 1 ? 'Yes' : 'No' }}
                    @else
                        Does this seem fair based on the market?
                    @endif
                </p>
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

        {{-- Referral Bonus --}}
        <div class="col-md-12">
            <span class="mt-3">Referral Bonus</span>
        </div>
        <div id="worker_referral_bonus" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                {{-- <h6> {{ isset($userdetails->nurse->worker_referral_bonus) ? '$ ' . number_format($userdetails->nurse->worker_referral_bonus) : 'Missing Referral Bonus Information' }}
                </h6> --}}
                <p class="profile_info_text" data-target="input_number" data-title="What rate is fair?"
                    data-placeholder="What rate is fair?" data-name="worker_referral_bonus"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_referral_bonus))
                        $ {{ number_format($userdetails->nurse->worker_referral_bonus) }}
                    @else
                        What rate is fair?
                    @endif
                </p>

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
        <div id="worker_sign_on_bonus" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input_number" data-title="What rate is fair?"
                    data-placeholder="What rate is fair?" data-name="worker_sign_on_bonus"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_sign_on_bonus))
                        $ {{ number_format($userdetails->nurse->worker_sign_on_bonus) }}
                    @else
                        What rate is fair?
                    @endif
                </p>
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
        <div id="worker_extension_bonus" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input_number" data-title="What rate is fair?"
                    data-placeholder="What rate is fair?" data-name="worker_extension_bonus"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_extension_bonus))
                        $ {{ number_format($userdetails->nurse->worker_extension_bonus) }}
                    @else
                        What rate is fair?
                    @endif
                </p>
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
        <div class="col-md-6">
        </div>
        <div class="col-md-6">
            <h6> {{ isset($offerdetails->total_organization_amount) ? '$ ' . number_format($offerdetails->total_organization_amount) : 'Missing Est. Total Organization Amount Information' }}
            </h6>
        </div>
        {{-- Pay Frequency --}}

        <div class="col-md-12">
            <span class="mt-3">Pay Frequency</span>
        </div>

        <div id="worker_pay_frequency" class="row d-flex align-items-center" style="margin:auto;">

            <div class="col-md-6">
                {{-- <h6>{{ $userdetails->nurse->worker_pay_frequency ?? 'Missing Pay Frequency Information' }}</h6> --}}
                <p class="profile_info_text" data-target="worker_pay_frequency" data-title="Pay frequency"
                    data-placeholder="Pay frequency?" data-name="worker_pay_frequency" onclick="open_multiselect_modal(this)">
                    @if (isset($userdetails->nurse->worker_pay_frequency))
                        {{ truncateText($userdetails->nurse->worker_pay_frequency) }}
                    @else
                        Pay frequency?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->pay_frequency)
                        {{ $offerdetails->pay_frequency }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'pay_frequency', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- Benefits --}}

        <div class="col-md-12">
            <span class="mt-3">Benefits</span>
        </div>

        <div id="worker_benefits" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                {{-- multi select open_multiselect_modal --}}
                <p class="profile_info_text" data-target="worker_benefits" data-title="Minimum benefits you require?"
                    data-placeholder="Minimum benefits you require?" data-name="worker_benefits"
                    onclick="open_multiselect_modal(this)">
                    @if (isset($userdetails->nurse->worker_benefits))
                        {{ truncateText($userdetails->nurse->worker_benefits) }}
                    @else
                        Minimum benefits you require?
                    @endif
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->benefits)
                        {{ $offerdetails->benefits }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'benefits', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
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
        <div id="clinical_setting_you_prefer" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="clinical_setting_you_prefer"
                    data-title="Settings you'll work in?" data-placeholder="Settings you'll work in?"
                    data-name="clinical_setting_you_prefer" onclick="open_multiselect_modal(this)">
                    @if (isset($userdetails->nurse->clinical_setting_you_prefer))
                        {{ truncateText($userdetails->nurse->clinical_setting_you_prefer) }}
                    @else
                        Settings you'll work in?
                    @endif
                </p>

            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->clinical_setting)
                        {{ $offerdetails->clinical_setting }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'clinical_setting', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- Preferred Work Location --}}
        <div class="col-md-12">
            <span class="mt-3">Adress</span>
        </div>
        <div id="worker_preferred_work_location" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input" data-title="Where do you prefer to work?"
                    data-placeholder="Where do you prefer to work?" data-name="worker_preferred_work_location"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_preferred_work_location))
                        {{ $userdetails->nurse->worker_preferred_work_location }}
                    @else
                        Where do you prefer to work?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->preferred_work_location)
                        {{ $offerdetails->preferred_work_location }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'preferred_work_location', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- Facility Name --}}

        <div class="col-md-12">
            <span class="mt-3">Facility</span>
        </div>
        <div id="worker_facility_name" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">

                <p class="profile_info_text" data-target="input" data-title="What facilities would you like to work at?"
                    data-placeholder="Where do you prefer to work?" data-name="worker_facility_name"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_facility_name) && $userdetails->nurse->worker_facility_name != '')
                        {{ $userdetails->nurse->worker_facility_name }}
                    @else
                        What facilities would you like to work at?
                    @endif
                </p>

            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->facility_name)
                        {{ $offerdetails->facility_name }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'facility_name', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- facilitys_parent_system | worker_facilitys_parent_system --}}
        <div class="col-md-12">
            <span class="mt-3">Facility's Parent System</span>
        </div>
        <div id="worker_facilitys_parent_system" class="row d-flex align-items-center" style="margin:auto;">

            <div class="col-md-6">
                
                <p class="profile_info_text" data-target="input" data-title="What system is the facility part of?"
                    data-placeholder="What system is the facility part of?" data-name="worker_facilitys_parent_system"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_facilitys_parent_system))
                        {{ $userdetails->nurse->worker_facilitys_parent_system }}
                    @else
                        What systems would you like to work at?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->facilitys_parent_system)
                        {{ $offerdetails->facilitys_parent_system }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'facilitys_parent_system', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Facility Shift Cancellation Policy --}}
        <div class="col-md-12">
            <span class="mt-3">Facility Shift Cancellation Policy</span>
        </div>
        <div id="facility_shift_cancelation_policy" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="facility_shift_cancelation_policy"
                    data-title="What is the facility's shift cancellation policy?"
                    data-placeholder="What is the facility's shift cancellation policy?"
                    data-name="facility_shift_cancelation_policy" onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->facility_shift_cancelation_policy))
                        {{ $userdetails->nurse->facility_shift_cancelation_policy }}
                    @else
                        What is the facility's shift cancellation policy?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->facility_shift_cancelation_policy)
                        {{ $offerdetails->facility_shift_cancelation_policy }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'facility_shift_cancelation_policy', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Contract Termination Policy --}}
        <div class="col-md-12">
            <span class="mt-3">Contract Termination Policy</span>
        </div>
        <div id="contract_termination_policy" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="contract_termination_policy"
                    data-title="What is the contract termination policy?"
                    data-placeholder="What is the contract termination policy?"
                    data-name="contract_termination_policy" onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->contract_termination_policy))
                        {{ $userdetails->nurse->contract_termination_policy }}
                    @else
                        What is the contract termination policy?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->contract_termination_policy)
                        {{ $offerdetails->contract_termination_policy }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'contract_termination_policy', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Traveler Distance From Facility --}}
        <div class="col-md-12">
            <span class="mt-3">Perm address miles from facility</span>
        </div>
        <div id="distance_from_your_home" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input_number"
                    data-title="How far are you willing to travel?"
                    data-placeholder="How far are you willing to travel?" data-name="distance_from_your_home"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->distance_from_your_home))
                        {{ $userdetails->nurse->distance_from_your_home }}
                        {{ $userdetails->nurse->distance_from_your_home > 1 ? 'miles Maximum' : 'mile Maximum' }}
                    @else
                        How far are you willing to travel?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->traveler_distance_from_facility)
                        {{ $offerdetails->traveler_distance_from_facility }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'traveler_distance_from_facility', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
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
            $offerLicenses = array_map('trim', $offerLicenses);
           
        @endphp

        <div id="worker_job_location"
         class="row  d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="worker_job_location" data-title="What licenses do you have?"
                    data-placeholder="What licenses do you have?" data-name="worker_job_location"
                    onclick="open_multiselect_modal(this)">
                    @if (!!$userdetails->nurse->worker_job_location)
                        {{ truncateText($userdetails->nurse->worker_job_location) }}
                    @else
                        Where are you licensed?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if (!empty($offerLicenses))
                        {{ implode(', ', $offerLicenses) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'job_location', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
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
                <p id="certification-placeholder" class="profile_info_text" class="certification_file"
                    data-target="certification_file" onclick="open_modal(this)" data-title="No certification?">
                </p>

            </div>
            <div class="col-md-6 ">
                <p>
                    @if (!empty($offerCertificates))
                        {{ implode(', ', $offerCertificates) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'certificate', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
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

        <div id="worker_description" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input" data-title="What are you looking for in a job?"
                    data-placeholder="What are you looking for in a job?" data-name="worker_description"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_description))
                        {{ $userdetails->nurse->worker_description }}
                    @else
                        What are you looking for in a job?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->description)
                        {{ $offerdetails->description }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'description', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- Experience --}}

        <div class="col-md-12">
            <span class="mt-3">Experience</span>
        </div>
        <div id="worker_experience" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input_number"
                    data-title="How many years of experience do you have?"
                    data-placeholder="How many years of experience do you have?" data-name="worker_experience"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_experience))
                        {{ $userdetails->nurse->worker_experience }}
                        {{ $userdetails->nurse->worker_experience > 1 ? 'Years' : 'Year' }}
                    @else
                        How many years of experience do you have?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->experience)
                        {{ $offerdetails->experience }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'experience', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
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
                <p id="skills-placeholder" class="profile_info_text" data-target="skills_file"
                    data-title="What skills do you have?" data-placeholder="What skills do you have?"
                    data-name="skills" onclick="open_modal(this)">
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

        <div id="worker_on_call_check" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="binary" data-title="Are you willing to work on call?"
                    data-placeholder="Are you willing to work on call?" data-name="worker_on_call_check"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_on_call_check))
                        @if ($userdetails->nurse->worker_on_call_check == '1')
                            Yes
                        @elseif($userdetails->nurse->worker_on_call_check == '0')
                            No
                        @else
                            Will you work on call?
                        @endif
                    @else
                        Are you willing to work on call?
                    @endif

                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if (isset($offerdetails->on_call))
                        @if ($offerdetails->on_call == '1')
                            Yes
                        @elseif($offerdetails->on_call == '0')
                            No
                        @else
                            <a style="cursor: pointer;"
                                onclick="askRecruiter(this, 'on_call', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                                Recruiter</a>
                        @endif
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'on_call', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>

        {{-- Block Scheduling --}}

        <div class="col-md-12">
            <span class="mt-3">Block scheduling</span>
        </div>
        <div id="block_scheduling" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="binary"
                    data-title="Do you require block scheduling?"
                    data-placeholder="Do you require block scheduling?" data-name="block_scheduling"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->block_scheduling))
                        @if ($userdetails->nurse->block_scheduling == '1')
                            Yes
                        @elseif($userdetails->nurse->block_scheduling == '0')
                            No
                        @else
                            Will you do block scheduling?
                        @endif
                    @else
                        Do you require block scheduling?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->block_scheduling == '1')
                        Yes
                    @elseif($offerdetails->block_scheduling == '0')
                        No
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'block_scheduling', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Float requirements --}}
        <div class="col-md-12">
            <span class="mt-3">Float requirements</span>
        </div>
        <div id="float_requirement" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="binary" data-title="Are you willing float?"
                    data-placeholder="Are you willing float?" data-name="float_requirement"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->float_requirement))
                        @if ($userdetails->nurse->float_requirement == '1')
                            Yes
                        @elseif($userdetails->nurse->float_requirement == '0')
                            No
                        @else
                            Will you float?
                        @endif
                    @else
                        Are you willing float?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->float_requirement == '1')
                        Yes
                    @elseif($offerdetails->float_requirement == '0')
                        No
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'float_requirement', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Patient ratio --}}
        <div class="col-md-12">
            <span class="mt-3">Patient Ratio Max</span>
        </div>
        <div id="worker_patient_ratio" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input_number"
                    data-title="What is the maximum patient ratio you are comfortable with?"
                    data-placeholder="What is the maximum patient ratio you are comfortable with?"
                    data-name="worker_patient_ratio" onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_patient_ratio))
                        {{ number_format($userdetails->nurse->worker_patient_ratio) }}
                    @else
                        What is the maximum patient ratio you are comfortable with?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->Patient_ratio)
                        {{ number_format($offerdetails->Patient_ratio) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'Patient_ratio', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Emr --}}
        <div class="col-md-12">
            <span class="mt-3">EMR</span>
        </div>
        <div id="worker_emr" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="worker_emr"
                    data-title="What EMR systems are you familiar with?"
                    data-placeholder="What EMR systems are you familiar with?" data-name="worker_emr"
                    onclick="open_multiselect_modal(this)">
                    @if (isset($userdetails->nurse->worker_emr))
                        {{ truncateText($userdetails->nurse->worker_emr) }}
                    @else
                        What EMR systems are you familiar with?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->Emr)
                        {{ $offerdetails->Emr }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'worker_emr', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
                    @endif
                </p>
            </div>
        </div>
        {{-- Unit --}}
        <div class="col-md-12">
            <span class="mt-3">Unit</span>
        </div>
        <div id="worker_unit" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="input" data-title="What unit do you prefer to work in?"
                    data-placeholder="What unit do you prefer to work in?" data-name="worker_unit"
                    onclick="open_modal(this)">
                    @if (isset($userdetails->nurse->worker_unit))
                        {{ $userdetails->nurse->worker_unit }}
                    @else
                        What unit do you prefer to work in?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->Unit)
                        {{ $offerdetails->Unit }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'worker_unit', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
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

        <div id="nurse_classification" class="row d-flex align-items-center" style="margin:auto;">
            <div class="col-md-6">
                <p class="profile_info_text" data-target="nurse_classification"
                    data-title="What is your nurse classification?"
                    data-placeholder="What is your nurse classification?" data-name="nurse_classification"
                    onclick="open_multiselect_modal(this)">
                    @if (isset($userdetails->nurse->nurse_classification))
                        {{ truncateText($userdetails->nurse->nurse_classification) }}
                    @else
                        What is your nurse classification?
                    @endif
                </p>
            </div>
            <div class="col-md-6 ">
                <p>
                    @if ($offerdetails->nurse_classification)
                        {{ $offerdetails->nurse_classification }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askRecruiter(this, 'nurse_classification', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->recruiter_id }}','{{ $offerdetails->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')">Ask
                            Recruiter</a>
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
                <p id="vaccination-placeholder" class="profile_info_text" class="vaccination_file"
                    data-target="vaccination_file" onclick="open_modal(this)" data-title="No vaccination?"
                    data-placeholder="No vaccination?">
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
        <button class="ss-counter-button" onclick="counterOffer('{{ $offerdetails->id }}')">Make
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
    function UpdateMultiNumberInputsValues(WorkerFieldName, workerFieldValue) {
            // Split the value by commas to create an array
            var multi_input_number_value = workerFieldValue.split(',');

            // Convert each item in the array to an integer
            multi_input_number_value = multi_input_number_value.map(function(item) {
                return parseInt(item, 10);
            });
        
            // Filter out any invalid numbers (NaN)
            multi_input_number_value = multi_input_number_value.filter(function(item) {
                return !isNaN(item);
            });
        
            // Map each item to a string with 'hour' or 'hours'
            multi_input_number_value = multi_input_number_value.map(function(item) {
                return item + (item > 1 ? ' hours' : ' hour');
            });
        
            // Join the array back into a string
            multi_input_number_value = multi_input_number_value.join(', ');

            // fill the input with the new value, get it by name 
            // var input = document.getElementsByName(WorkerFieldName);
            // if (input.length > 0) {
            //     input[0].value = multi_input_number_value;
            // } else {
            //     console.error('Input not found for name:', WorkerFieldName);
            // }
        
            // Find the element by WorkerFieldName and set its innerHTML
            var element = document.getElementById(WorkerFieldName);
            var element = element.querySelector('p');
            if (element) {
                
                element.setAttribute('data-value', workerFieldValue);
                console.log('workerFieldValue', workerFieldValue);
                console.log('element', element);
                element.innerHTML = multi_input_number_value;
            } else {
                console.error('Element not found for ID:', WorkerFieldName);
            }
        }
    
    document.addEventListener('DOMContentLoaded', function() {

        var workerId = @json($offerdetails['worker_user_id']);

        function activeWorkerClass(workerUserId) {
            try {
                var element = document.getElementById(workerUserId);
                element.classList.add('active');
            } catch (error) {
                console.error('error :', error);
            }
        }

        // trim description control

        let description = document.getElementById('job_description');

        if (description) {
            let descriptionText = description.innerText;
            if (descriptionText.length > 100) {
                description.innerText = descriptionText.substring(0, 100) + '...';
                let readMore = document.createElement('a');
                readMore.id = 'read-more';
                readMore.innerText = ' Read More';
                readMore.href = 'javascript:void(0)';
                // add a function to onclick
                readMore.onclick = readMoreDescreption;
                description.appendChild(readMore);
            }
        }


    });

    $(document).ready(async function() {

        var selectedFiles = [];
        var selectedValues = [];
        var worker_files_displayname_by_type = [];
        var worker_files = [];
        var no_files = false;

        // end trim description control

        var items = document.querySelectorAll('.list-items .item');
        //store selected file values

        items.forEach((item, index) => {
            item.addEventListener('click', (event) => {
                if (event.target.closest('.checkbox')) {
                    return;
                }
                const uploadInput = item.nextElementSibling;
                if (uploadInput) {
                    // class 'checked' check
                    if (!item.classList.contains('checked')) {
                        uploadInput.click();
                        uploadInput.addEventListener('change', function() {
                            if (this.files.length > 0) {
                                // Handling file selection
                                const file = this.files[0];
                                selectedFiles.push(file.name);
                            }
                        }, {
                            once: true //avoid multiple registrations
                        });
                    } else {
                        const index = selectedFiles.indexOf(uploadInput.files[0].name);
                        if (index > -1) {
                            selectedFiles.splice(index, 1);
                        }

                    }
                }
            });
        });

        items = document.querySelectorAll(".item");

        items.forEach(item => {
            item.addEventListener("click", () => {
                const value = item.getAttribute('value');
                item.classList.toggle("checked");

                if (item.classList.contains("checked")) {
                    // add item
                    selectedValues.push(value);
                } else {
                    // remove item
                    const index = selectedValues.indexOf(value);
                    if (index > -1) {
                        selectedValues.splice(index, 1);
                    }
                }
                let btnText = document.querySelector(".btn-text");
                if (selectedValues.length > 0) {
                    btnText.innerText = `${selectedValues.length} Selected`;
                } else {
                    btnText.innerText = "Select Language";
                }
            });
        })

        

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

        try{

            var worker_id = @json($offerdetails['worker_user_id']);
            var offer_id = @json($offerdetails['id']);
            var placeholder = document.getElementById(fileType + '-placeholder');

            if (file.length > 0) {

                if (fileType == "resume") {

                    placeholder.innerHTML = "Provided";

                } else {

                    placeholder.innerHTML = file.join(', ');
                }

            } else {
                let areaDiv = document.getElementById(fileType);
                areaDiv.classList.add('ss-s-jb-apl-bg-pink');
                placeholder.innerHTML = '<h6>Missing ' + fileType + ' Information';
            }

        }catch(error){
            console.error('error :', error);
        }

    }

    function get_all_files() {
        
        try{

            var worker_id = @json($offerdetails['worker_user_id']);
            return new Promise((resolve, reject) => {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('list-docs') }}',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        WorkerId: worker_id
                    }),
                    success: function(resp) {
                        let jsonResp = JSON.parse(resp);
                        let files = jsonResp;
                        worker_files = files;
                      //  console.log('worker_files', worker_files);
                        resolve(files);
                    },
                    error: function(resp) {
                        updateWorkerFilesList([], 'certification');
                        updateWorkerFilesList([], 'vaccination');
                        updateWorkerFilesList([], 'references');
                        updateWorkerFilesList([], 'skills');
                        reject(resp);
                    }
                });
            });

        }catch(error){
            console.error('error :', error);
        }

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
            // certification
            var job_certification = "{!! $offerdetails->certificate !!}";
            var job_certification_displayname = job_certification.split(',').map(function(item) {

                return item.trim();

            });

            const is_job_certif_exist_in_worker_files = job_certification_displayname.every(element =>
                worker_files_displayname_by_type.includes(element));
            updateWorkerFilesList(worker_files_displayname_by_type, 'certification');
           
            if (is_job_certif_exist_in_worker_files) {
                check = true;
            }

        } else if (inputName == 'vaccination') {

            // vaccination
            var job_vaccination = "{!! $offerdetails->vaccinations !!}";
            var job_vaccination_displayname = job_vaccination.split(',').map(function(item) {

                return item.trim();

            });



            const is_job_vaccin_exist_in_worker_files = job_vaccination_displayname.every(element =>
                worker_files_displayname_by_type.includes(element));
            updateWorkerFilesList(worker_files_displayname_by_type, 'vaccination');
            

            if (is_job_vaccin_exist_in_worker_files) {
                check = true;
            }

        } else if (inputName == 'references') {
            // references
            var number_of_references = "{!! $offerdetails->number_of_references !!}";

            updateWorkerFilesList(worker_files_displayname_by_type, 'references');
            if (number_of_references <= worker_files_displayname_by_type.length) {
                check = true;
            }

        } else if (inputName == 'skills') {


            // skills
            var job_skills = "{!! $offerdetails->skills !!}";
            var job_skills_displayname = job_skills.split(',').map(function(item) {
                return item.trim();

            });

            const is_job_skill_exist_in_worker_files = job_skills_displayname.every(element =>
                worker_files_displayname_by_type.includes(element));
            updateWorkerFilesList(worker_files_displayname_by_type, 'skills');
           

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

        } else if (inputName == "resume") {

            updateWorkerFilesList(worker_files_displayname_by_type, 'resume');

            let is_resume = @json($offerdetails['is_resume']);

            if (worker_files_displayname_by_type.length > 0 && is_resume) {

                check = true;

            } else if (worker_files_displayname_by_type.length > 0 && !is_resume) {
                check = true;
            } else {
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



    var offer_fields_vs_worker_fields = {
        'type': 'worker_job_type',
        'terms': 'terms',
        'profession': 'profession',
        'specialty': 'specialty',
        'actual_hourly_rate': 'worker_actual_hourly_rate',
        'weekly_pay': 'worker_organization_weekly_amount',
        'hours_per_week': 'worker_hours_per_week',
        'state': 'state',
        'city': 'city',
        'preferred_shift_duration': 'worker_shift_time_of_day',
        'guaranteed_hours': 'worker_guaranteed_hours',
        'hours_shift': 'worker_hours_shift',
        'weeks_shift': 'worker_shifts_week',
        'preferred_assignment_duration': 'worker_weeks_assignment',
        'start_date': 'worker_start_date',
        'end_date': 'worker_end_date',
        'rto': 'rto',
        'overtime': 'worker_overtime_rate',
        'on_call_rate': 'worker_on_call',
        'call_back_rate': 'worker_call_back',
        'orientation_rate': 'worker_orientation_rate',
        'weekly_non_taxable_amount': 'worker_weekly_non_taxable_amount_check',
        'feels_like_per_hour': 'worker_feels_like_per_hour_check',
        'referral_bonus': 'worker_referral_bonus',
        'sign_on_bonus': 'worker_sign_on_bonus',
        'extension_bonus': 'worker_extension_bonus',
        'pay_frequency': 'worker_pay_frequency',
        'benefits': 'worker_benefits',
        'clinical_setting': 'clinical_setting_you_prefer',
        'facility_name': 'worker_facility_name',
        'facilitys_parent_system': 'worker_facilitys_parent_system',
        'facility_shift_cancelation_policy': 'facility_shift_cancelation_policy',
        'contract_termination_policy': 'contract_termination_policy',
        'traveler_distance_from_facility': 'distance_from_your_home',
        'job_location': 'worker_job_location',
        'certificate': 'certificate',
        'description': 'worker_description',
        'preferred_experience': 'worker_experience',
        'number_of_references': 'number_of_references',
        'skills': 'skills',
        'on_call': 'worker_on_call_check',
        'block_scheduling': 'block_scheduling',
        'float_requirement': 'float_requirement',
        'Patient_ratio': 'worker_patient_ratio',
        'Emr': 'worker_emr',
        'Unit': 'worker_unit',
        'nurse_classification': 'nurse_classification',

    };

    var worker_fields = [
        'worker_job_type',
        'terms',
        'profession',
        'specialty',
        'worker_actual_hourly_rate',
        'worker_organization_weekly_amount',
        'worker_hours_per_week',
        'state',
        'city',
        'worker_shift_time_of_day',
        'worker_guaranteed_hours',
        'worker_hours_shift',
        'worker_shifts_week',
        'worker_weeks_assignment',
        'worker_start_date',
        'worker_end_date',
        'rto',
        'worker_overtime_rate',
        'worker_on_call',
        'worker_call_back',
        'worker_orientation_rate',
        'worker_weekly_non_taxable_amount',
        'worker_feels_like_per_hour',
        'worker_referral_bonus',
        'worker_sign_on_bonus',
        'worker_extension_bonus',
        'worker_pay_frequency',
        'worker_benefits',
        'clinical_setting_you_prefer',
        'worker_preferred_work_location',
        'worker_facility_name',
        'worker_facilitys_parent_system',
        'facility_shift_cancelation_policy',
        'contract_termination_policy',
        'distance_from_your_home',
        'worker_job_location',
        'certificate',
        'worker_description',
        'worker_experience',
        'number_of_references',
        'skills',
        'worker_on_call',
        'block_scheduling',
        'float_requirement',
        'worker_patient_ratio',
        'worker_emr',
        'worker_unit',
        'nurse_classification',
    ];


    var offer = @json($offerdetails);
    var worker = @json($userdetails->nurse);

    var numberFields = [
        'actual_hourly_rate',
        'weekly_pay',
        'hours_per_week',
        'guaranteed_hours',
        'hours_shift',
        'weeks_shift',
        'preferred_assignment_duration',
        'overtime',
        'on_call_rate',
        'call_back_rate',
        'orientation_rate',
        'weekly_non_taxable_amount',
        'feels_like_per_hour',
        'referral_bonus',
        'sign_on_bonus',
        'extension_bonus',
        'total_organization_amount',
        'traveler_distance_from_facility',
        'number_of_references'
    ];

    var dateFields = [
        'start_date',
        'end_date'
    ];

    var timeFields = [
        'worker_experience',
        'Patient_ratio'
    ];

    var multi_select_number_fields = [
        'guaranteed_hours',
        'hours_per_week',
        'hours_shift',
        'weeks_shift',
        'preferred_assignment_duration'
    ]

    var rateFields = [
        'distance_from_your_home',
        'actual_hourly_rate',
        'weekly_pay',
        'overtime',
        'on_call_rate',
        'call_back_rate',
        'orientation_rate',
        'weekly_non_taxable_amount',
        'referral_bonus',
        'sign_on_bonus',
        'extension_bonus',
        'total_organization_amount',
        'traveler_distance_from_facility',
        'number_of_references',
    ];

    var multiSelect = [
        'profession',
        'specialty',
        'state',
        'worker_job_type',
        'terms',
        'clinical_setting_you_prefer',
        'worker_benefits',
        'worker_job_location',
        'worker_pay_frequency',
        'worker_emr',
        'nurse_classification'
    ];

    var noMatchingField = [
        'worker_preferred_work_location',
        'worker_facility_name',
        'worker_facilitys_parent_system',
        'worker_description',
    ];

    var booleanFields = [
        'rto',
        'on_call',
        'block_scheduling',
        'float_requirement',
        'feels_like_per_hour',
    ];


    numberFields.forEach(function(field) {
        if (offer[field] != null) {
            offer[field] = Number(offer[field]);
        }
    });

    function compareFields() {
        try {
            for (const [key, value] of Object.entries(offer_fields_vs_worker_fields)) {
              //  console.log('key:', key);
              //  console.log('value:', value);
                let match = false;
                if (noMatchingField.includes(value)) {
                    match = null;
                } else if (worker[value] == null) {
                  //  console.log('worker value is null');
                    match = false;
                } else if (worker[value] == offer[key]) {
                  //  console.log('worker:', worker[value]);
                    match = true;
                } else if (multi_select_number_fields.includes(key)){
                    let workerValue = worker[value].split(',').map(function(item) {
                        return parseInt(item, 10); 
                    });
                    let offerValue = offer[key];
                    UpdateMultiNumberInputsValues(value, worker[value]);
                    // match = workerValue.includes(offerValue);
                    for (let element of workerValue) {
                        if (element >= offerValue) {
                            match = true;
                            break; 
                        }
                    }
                } else if (rateFields.includes(key)) {
                  //  console.log('worker rate field:', worker[value]);
                    match = worker[value] <= offer[key];
                } else if (timeFields.includes(key)) {
                  //  console.log('worker time field:', worker[value]);
                    match = worker[value] >= offer[key];
                } else if (multiSelect.includes(value)) {
                  //  console.log('worker multi select field:', worker[value]);
                  //  console.log('field:', value);
                    match = multi_select_match_with_worker(value, worker[value]);
                } else if (dateFields.includes(key)) {
                    let workerDate = new Date(worker[value]);
                    let offerDate = new Date(offer[key]);
                    if (key == 'start_date') {
                        match = workerDate <= offerDate;
                    } else if (key == 'end_date') {
                        match = workerDate >= offerDate;
                    }
                } else if (booleanFields.includes(key)) {
                    console.log('worker boolean field:', worker[value], 'workerfield name:', value);
                    if(key == 'rto') {
                        if (offer[key] == 'not allowed') {
                            match = false;
                        } else if (offer[key] == 'allowed') {
                            if (worker[value] != null && worker[value] != '') {
                                match = true;
                            } else {
                                match = false;
                            }
                        }
                    }else if(key == 'feels_like_per_hour'){
                        if (worker[value] == '0') {
                            match = false;
                        } else if (worker[value] == '1') {
                            match = true;
                        } 
                    }else if (offer[key] == '0') {
                        match = true;
                    } else if (offer[key] == '1') {
                        match = worker[value] == offer[key];
                    } 
                } else {
                    if(key == 'preferred_shift_duration'){

                        if(offer[key] == 'Nights or Days'){
                            match = worker[value].includes('Days') || worker[value].includes('Nights');
                        }else if (offer[key] == 'Nights & Days'){
                            console.log('worker:', worker[value]); 
                            match = worker[value] == 'Nights & Days' || worker[value].includes('Days, Nights') || worker[value].includes('Nights, Days') || worker[value].includes('Days, Days') || worker[value].includes('Nights, Nights'); 
                        }else{
                            match = worker[value].includes(offer[key]);
                        }

                    }
                }

                let DivElement = document.getElementById(value);
                if (DivElement) {
                    if (match == true) {
                        DivElement.classList.remove('ss-s-jb-apl-bg-pink');
                        DivElement.classList.add('ss-s-jb-apl-bg-blue');
                    } else if (match == false) {
                        DivElement.classList.remove('ss-s-jb-apl-bg-blue');
                        DivElement.classList.add('ss-s-jb-apl-bg-pink');
                    }
                } else {
                  //  console.log('Element not found for ID:', value);
                }
            }
        } catch (error) {
            //console.error('error :', error);
            return false;
        }
    }

    compareFields();

    function open_multiselect_modal(obj) {
        try{

            let target = $(obj).data('target');
            let target_modal = '#' + target + '_modal';

            //  console.log(target_modal);

            $(target_modal).modal('show');

        } catch (error) {

            console.error('error :', error);

        }

    }

    function open_modal(obj) {
        try{
            let name, title, modal, form, target;

            name = $(obj).data('name');
            title = $(obj).data('title');
            target = $(obj).data('target');
            let value = $(obj).attr('data-value') || '';

            modal = '#' + target + '_modal';
            form = modal + '_form';
            $(form).find('h4').html(title);
            // TODO :: check if there is already data selected and set it to the input
            switch (target) {
                case 'input':
                    $(form).find('input[type="text"]').attr('name', name);
                    $(form).find('input[type="text"]').attr('placeholder', $(obj).data('placeholder'));
                    break;
                case 'input_number':
                    $(form).find('input[type="number"]').attr('name', name);
                    $(form).find('input[type="number"]').attr('placeholder', $(obj).data('placeholder'));
                    break;
                case 'multi_input_number':
                    $(form).find('input[type="text"]').attr('name', name);
                    // reset and set the value
                    $(form).find('input[type="text"]').attr('value', value);
                    $(form).find('input[type="text"]').attr('placeholder', $(obj).data('placeholder'));
                    // clear the helper text
                    $(form).find('.helper').text('');
                    break;
                case 'binary':
                    $(form).find('input[type="radio"]').attr('name', name);
                    break;
                case 'dropdown':
                    $(form).find('select').attr('name', name);
                    get_dropdown(obj);
                    break;
                case 'date':
                    $(form).find('input[type="date"]').attr('name', name);
                    break;
                default:
                    break;
            }

            $(modal).modal('show');
        }catch(error){
            console.error('error :', error);
        }
    }

    function getOfferFieldIdByValue(value) {
        return Object.keys(offer_fields_vs_worker_fields).find(key => offer_fields_vs_worker_fields[key] === value);
    }

    function multi_select_match_with_worker(workerField, InsertedValue) {
        try {
            let match = false;

            if (noMatchingField.includes(workerField)) {

                match = null;

            } else if (multiSelect.includes(workerField)) {

                let workerFieldArray = InsertedValue.split(',');

                let workerFieldArrayTrimmed = workerFieldArray.map(function(item) {
                    return item.trim();
                });
                let offerField = getOfferFieldIdByValue(workerField);
                let offerFieldArray = offer[offerField].split(',');
                // console.log('offerFieldArray:', offerFieldArray);
                let offerFieldArrayTrimmed = offerFieldArray.map(function(item) {
                    return item.trim();
                });
                match = workerFieldArrayTrimmed.some(element => offerFieldArrayTrimmed.includes(element));
                // console.log('match:', match);

            } else {

                let offerField = getOfferFieldIdByValue(workerField);
                // console.log('offerField value :', offer[offerField]);
                // console.log('InsertedValue value :', InsertedValue);

                if (rateFields.includes(offerField)) {

                    match = InsertedValue <= offer[offerField];

                } else if (timeFields.includes(offerField)) {

                    match = InsertedValue >= offer[offerField];

                } else if (multi_select_number_fields.includes(offerField)){

                    let workerValue = InsertedValue.split(',').map(function(item) {
                        return parseInt(item, 10); 
                    });
                    let offerValue = offer[offerField];
                    for (let element of workerValue) {
                        if (element >= offerValue) {
                            match = true;
                            break; 
                        }
                    }
                    UpdateMultiNumberInputsValues(workerField, InsertedValue);

                } else if (dateFields.includes(offerField)) {

                    let insertedDate = new Date(InsertedValue);
                    let offerDate = new Date(offer[offerField]);

                    if (offerField == 'start_date') {

                        match = insertedDate <= offerDate;

                    } else if (offerField == 'end_date') {

                        match = insertedDate >= offerDate;

                    }
                }else if (booleanFields.includes(offerField)) {
                    
                    if(offerField == 'rto') {
                        if (offer[offerField] == 'not allowed') {
                            match = false;
                        } else if (offer[offerField] == 'allowed') {
                            if (InsertedValue != null && InsertedValue != '') {
                                match = true;
                            } else {
                                match = false;
                            }
                        }
                    }else if (offer[offerField] == '0') {
                        match = true;
                    } else if (offer[offerField] == '1') {
                        match = InsertedValue == offer[offerField];
                    } 

                }  else {

                    if(offerField == 'preferred_shift_duration'){

                        if(offer[offerField] == 'Nights or Days'){
                            match = InsertedValue.includes('Days') || InsertedValue.includes('Nights');
                        }else if (offer[offerField] == 'Nights & Days'){
                            console.log('worker:', InsertedValue); 
                            match = InsertedValue == 'Nights & Days' || InsertedValue.includes('Days, Nights') || InsertedValue.includes('Nights, Days') || InsertedValue.includes('Days, Days') || InsertedValue.includes('Nights, Nights'); 
                        }else{
                            match = InsertedValue.includes(offer[offerField]);
                        }

                    }else {
                            match = InsertedValue == offer[offerField];
                    }

                }

            }
        
            return match;

        } catch (error) {
            console.error('error :', error);
            return false;
        }
    }

    function multi_select_change({
        id,
        name,
        value
    }) {

      //  console.log(id, name, value);

        if (!id || !name) {
            console.error("Missing 'id' or 'name' in function call");
            return;
        }

        const parentSelector = '#' + CSS.escape(id);
        const childSelector = `p[data-name="${name}"]`;

        // Find the element
        let element = $(parentSelector).find(childSelector);

        // if value is null put the element title else put the value
        if (value == null) {
            element.text(element.data('title'));
        } else {
            element.text(truncateText(value));
        }

        // check if the value match with the worker field

        let match = multi_select_match_with_worker(name, value);

        let DivElement = document.getElementById(name);
        if (DivElement) {
            if (match == true) {
                DivElement.classList.remove('ss-s-jb-apl-bg-pink');
                DivElement.classList.add('ss-s-jb-apl-bg-blue');
            } else if (match == false) {
                DivElement.classList.remove('ss-s-jb-apl-bg-blue');
                DivElement.classList.add('ss-s-jb-apl-bg-pink');
            }
        } else {
          //  console.log('Element not found for ID:', name);
        }
    }


    function truncateText(text, limit = 35) {
        return text.length > limit ? text.substring(0, limit) + '...' : text;
    }

    async function saveData(event, type) {

        event.preventDefault();

        let form = event.target.closest('form');
        if (type == 'file') {
            try {
                let input = form.querySelector('input');
                let inputName = input.name;
                let resp = await sendMultipleFiles(inputName);
                if (resp == 404) {
                    return false;
                }
                worker_files = await get_all_files();
                await checkFileMatch(inputName);
                $('#' + inputName + '_file_modal').modal('hide');
            } catch (error) {
                console.error('Failed to send files:', error)
            }

            return;
        }

        // console.log('form', form);

        // Get the single input element
        let input;
        let name;
        let value;
        
        if (type === 'multi_input_number') {

            input = form.querySelector('input');
            value = input.value;
            name =  input.name;
            let numbers = value.split(',').map(num => num.trim());
            let valid = numbers.every(num => Number.isInteger(Number(num)) && num !== ''); 
            
            if (!valid) {

                $('.help-block-multi_input_number_modal').text('Please enter valid numbers separated by commas.');
                $('.help-block-multi_input_number_modal').addClass('text-danger');
                return;

            }else{

                $('.help-block-multi_input_number_modal').text('');
                $('.help-block-multi_input_number_modal').removeClass('text-danger');

            }

            value = numbers.join(', ');

            // update the input :)
            input.value = value;

        }else if (type === 'binary') {

            input = form.querySelector('input[type="radio"]:checked');
            if (!input) {
                console.error('No input selected for type:', type);
                return;
            }
            name = input.name;
            value = input.value;

        } else {

            input = form.querySelector('input');
            if (!input) {
                input = form.querySelector('select');
                if (!input) {
                    console.error('No input found in form:', form);
                    return;
                }

            }
            name = input.name;
            value = input.value;

        }

        let match = multi_select_match_with_worker(name, value);
        //  console.log('Match:', match);
        let element = document.getElementById(name);

        if (element) {

            let childElement = element.querySelector('p[data-name="' + name + '"]');
            if (type == 'binary') {

                childElement.innerText = value == '1' ? 'Yes' : 'No';

            } else if (type !== 'multi_input_number') {

                childElement.innerText = value;

            }
            if (match == true) {

                element.classList.remove('ss-s-jb-apl-bg-pink');
                element.classList.add('ss-s-jb-apl-bg-blue');

            } else if (match == false) {

                element.classList.remove('ss-s-jb-apl-bg-blue');
                // console.log(element);
                element.classList.add('ss-s-jb-apl-bg-pink');

            }

        } else {
            console.log('Element not found for ID:', name);
        }

        let formData = new FormData(form);
        console.log('formData:', formData);
        fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {

              //  console.log('Success:', data);
                $('#' + type + '_modal').modal('hide');
              // clear form
                form.reset();

            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }

    async function sendMultipleFiles(type) {

        const fileInputs = document.querySelectorAll('.files-upload');
        const fileReadPromises = [];
        var workerId = @json($offerdetails['worker_user_id']);

        if (type == 'references') {

            let referenceName = document.querySelector('input[name="name"]').value;
            let referencePhone = document.querySelector('input[name="phone"]').value;
            let referenceEmail = document.querySelector('input[name="reference_email"]').value;
            let referenceDate = document.querySelector('input[name="date_referred"]').value;
            let referenceMinTitle = document.querySelector('input[name="min_title_of_reference"]').value;
            let referenceRecency = document.querySelector('input[name="recency_of_reference"]:checked').value;
            let referenceImage = document.querySelector('input[name="image"]').files[0];

            if (!referenceName) {

                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-exclamation-triangle"></i> Reference Name is required',
                    time: 3
                });
                return 404;

            } else {

                if (!referencePhone && !referenceEmail) {
                    notie.alert({
                        type: 'error',
                        text: '<i class="fa fa-exclamation-triangle"></i> Phone Number or Email is required',
                        time: 3
                    });
                    return 404;
                }

            }

            var referenceInfo = {
                referenceName: referenceName,
                phoneNumber: referencePhone,
                email: referenceEmail,
                dateReferred: referenceDate,
                minTitle: referenceMinTitle,
                isLastAssignment: referenceRecency == 1 ? true : false
            };
            //console.log('referenceInfo', referenceInfo);


            var readerPromise;
            if (referenceImage) {

                readerPromise = new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        resolve({
                            name: referenceImage.name,
                            path: "",
                            type: type,
                            content: event.target.result,
                            displayName: referenceImage.name,
                            ReferenceInformation: referenceInfo
                        });
                    };
                    reader.onerror = reject;
                    reader.readAsDataURL(referenceImage);
                });
                
            } else {

                readerPromise = Promise.resolve({
                    name: 'null',
                    path: 'null',
                    type: type,
                    content: 'data:',
                    displayName: 'null',
                    ReferenceInformation: referenceInfo
                });

            }

            fileReadPromises.push(readerPromise);

        } else {

            fileInputs.forEach((input, index) => {

                let displayName = input.getAttribute("displayName");

                if (input.files[0]) {

                    const file = input.files[0];
                    const readerPromise = new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            resolve({
                                name: file.name,
                                path: file.name,
                                type: type,
                                content: event.target.result, // Base64 encoded content
                                displayName: displayName || file.name,
                            });
                        };
                        reader.onerror = reject;
                        reader.readAsDataURL(file);
                    });
                    fileReadPromises.push(readerPromise);

                }
            });
        }
        try {

            const files = await Promise.all(fileReadPromises);
            const response = await fetch('/worker/add-docs', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    workerId: workerId,
                    files: files
                })
            });
            const data = await response.json();
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i>' + "Uploaded Successfully",
                time: 3
            });

        } catch (error) {

            console.error('Error:', error); // Handle errors

        }

        // clear files inputs
        fileInputs.forEach((input) => {
            input.value = '';
        });
        selectedFiles = [];
    }

    function open_file(obj) {
        $(obj).parent().find('input[type="file"]').click();
    }
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

    .item-elem label {
        display: block;
        color: #3D2C39;
        font-size: 14px;
        padding-bottom: 4px;
        font-weight: 500;
        margin-left: 6px;
    }

    .profile_info_text {
        font-size: 14px;
        color: #333;
        cursor: pointer;
        font-weight: 500;
        text-decoration: underline;
    }
</style>
