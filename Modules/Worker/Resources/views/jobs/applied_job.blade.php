<!----------------jobs applay view details--------------->

<div class="ss-job-apply-on-view-detls-mn-dv">
    <div class="ss-job-apply-on-tx-bx-hed-dv">
        <ul>
            <li>
                <p>Recruiter</p>
            </li>
            <li><img width="50px" height="50px" src="{{ URL::asset('images/nurses/profile/' . $recruiter->image) }}"
                    onerror="this.onerror=null;this.src='{{ USER_IMG }}';" />{{ $recruiter->first_name . ' ' . $recruiter->last_name }}
            </li>
        </ul>
        <ul>
            <li>
                <span>{{ $model->id }}</span>
                <h6>{{ $model->getOfferCount() }} Applied</h6>
            </li>
        </ul>
    </div>

    <div class="ss-jb-aap-on-txt-abt-dv">
        <h5>About job</h5>
        <ul>
            <li>
                <h6>Organization Name</h6>
                <p>{{ $model->facility->name ?? 'NA' }}</p>
            </li>
            <li>
                <h6>Date Posted</h6>
                <p>{{ Carbon\Carbon::parse($model->created_at)->format('M d') }}</p>
            </li>
            <li>
                <h6>Type</h6>
                <p>{{ $model->job_type }}</p>
            </li>
            <li>
                <h6>Terms</h6>
                <p>{{ $model->terms }}</p>
            </li>

        </ul>
    </div>


    <div class="ss-jb-apply-on-disc-txt">
        <h5>Description</h5>
        <p>{!! $model->description !!}</p>
    </div>


    <!-------Work Information------->

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
            <div class="row {{ $jobdetails->type == $offerdetails->worker_job_type ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>
                        {{ $jobdetails->type ?? 'Missing job type Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @isset($offerdetails->worker_job_type)
                            {{ $offerdetails->worker_job_type }}
                        @else
                            ---
                        @endisset
                    </p>
                </div>
            </div>
            {{-- terms --}}
            <div class="col-md-12">
                <span class="mt-3">Terms</span>
            </div>
            <div class="row {{ $jobdetails->terms == $offerdetails->terms ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>
                        {{ $jobdetails->terms ?? 'Missing job terms Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @isset($offerdetails->terms)
                            {{ $offerdetails->terms }}
                        @else
                            ---
                        @endisset
                    </p>
                </div>
            </div>
            <div class="col-md-12">
                <span class="mt-3">Profession</span>
            </div>
            <div class="row {{ $jobdetails->profession == $offerdetails->profession ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>
                        {{ $jobdetails->profession ?? 'Missing Profession Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @isset($offerdetails->profession)
                            {{ $offerdetails->profession }}
                        @else
                            ---
                        @endisset
                    </p>
                </div>
            </div>
            <div class="col-md-12">
                <span class="mt-3">Specialty</span>
            </div>
            <div class="row {{ $jobdetails->specialty == $offerdetails->specialty ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->specialty ?? 'Missing Specialty Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->specialty)
                            {{ $offerdetails->specialty }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>
            {{-- $/hr --}}
            <div class="col-md-12">
                <span class="mt-3">Actual Hourly rate</span>
            </div>
            <div class="row {{ $jobdetails->actual_hourly_rate === $offerdetails->worker_actual_hourly_rate ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ isset($jobdetails->actual_hourly_rate) ? number_format($jobdetails->actual_hourly_rate) : 'Missing Actual Hourly Rate Information' }}
                    </h6>
                </div>
                <div class="col-md-6">
                    <p>
                        {!! isset($offerdetails->worker_actual_hourly_rate)
                            ? number_format($offerdetails->worker_actual_hourly_rate)
                            : '---' !!}

                    </p>
                </div>
            </div>
            {{-- $/wk --}}
            <div class="col-md-12">
                <span class="mt-3">Weekly Pay</span>
            </div>
            <div class="row {{ $jobdetails->weekly_pay === $offerdetails->worker_organization_weekly_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ isset($jobdetails->weekly_pay) ? number_format($jobdetails->weekly_pay) : 'Missing Weekly Pay Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_organization_weekly_amount)
                            ? number_format($offerdetails->worker_organization_weekly_amount)
                            : '---' !!}

                    </p>
                </div>
            </div>
            {{-- hrs/wk --}}
            <div class="col-md-12">
                <span class="mt-3">Hours/Week</span>
            </div>
            <div class="row {{ $jobdetails->hours_per_week == $offerdetails->worker_hours_per_week ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ isset($jobdetails->hours_per_week) ? number_format($jobdetails->hours_per_week) : 'Missing Hours/Week Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_hours_per_week) ? number_format($offerdetails->worker_hours_per_week) : '---' !!}
                    </p>
                </div>
            </div>
            {{-- state --}}
            <div class="col-md-12">
                <span class="mt-3">State</span>
            </div>
            <div class="row {{ $jobdetails->state == $offerdetails->state ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->state ?? 'Missing State Information' }}</h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->state)
                            {{ $offerdetails->state }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>
            {{-- city --}}
            <div class="col-md-12">
                <span class="mt-3">City</span>
            </div>
            <div class="row {{ $jobdetails->city == $offerdetails->city ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->city ?? 'Missing City Information' }}</h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->city)
                            {{ $offerdetails->city }}
                        @else
                            ---
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
            <div class="row {{ $jobdetails->preferred_shift_duration == $offerdetails->worker_shift_time_of_day ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->preferred_shift_duration ?? 'Missing Shift Time of Day Information' }}</h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->worker_shift_time_of_day)
                            {{ $offerdetails->worker_shift_time_of_day }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>
            {{-- Guaranteed Hours --}}
            <div class="col-md-12">
                <span class="mt-3">Guaranteed Hours</span>
            </div>
            <div class="row {{ $jobdetails->guaranteed_hours == $offerdetails->worker_guaranteed_hours ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ isset($jobdetails->guaranteed_hours) ? number_format($jobdetails->guaranteed_hours) : 'Missing Guaranteed Hours Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_guaranteed_hours)
                            ? number_format($offerdetails->worker_guaranteed_hours)
                            : '---' !!}
                    </p>
                </div>
            </div>
            {{-- Hours/Shift --}}
            <div class="col-md-12">
                <span class="mt-3">Hours/Shift</span>
            </div>
            <div class="row {{ $jobdetails->hours_shift == $offerdetails->worker_hours_shift ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ isset($jobdetails->hours_shift) ? number_format($jobdetails->hours_shift) : 'Missing Hours/Shift Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_hours_shift) ? number_format($offerdetails->worker_hours_shift) : '---' !!}
                    </p>
                </div>
            </div>
            {{-- Shifts/Week --}}
            <div class="col-md-12">
                <span class="mt-3">Shifts/Week</span>
            </div>
            <div class="row {{ $jobdetails->weeks_shift == $offerdetails->worker_shifts_week ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ isset($jobdetails->weeks_shift) ? number_format($jobdetails->weeks_shift) : 'Missing Shifts/Week Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_shifts_week) ? number_format($offerdetails->worker_shifts_week) : '---' !!}
                    </p>
                </div>
            </div>
            {{-- Wks/Contract --}}
            <div class="col-md-12">
                <span class="mt-3">Wks/Contract</span>
            </div>
            <div class="row {{ $jobdetails->preferred_assignment_duration == $offerdetails->worker_weeks_assignment ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ isset($jobdetails->preferred_assignment_duration) ? number_format($jobdetails->preferred_assignment_duration) : 'Missing Wks/Contract Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_weeks_assignment)
                            ? number_format($offerdetails->worker_weeks_assignment)
                            : '---' !!}
                    </p>
                    </p>
                </div>
            </div>
            {{-- Start Date --}}
            <div class="col-md-12">
                <span class="mt-3">Start Date</span>
            </div>
            <div class="row {{ $jobdetails->start_date == $offerdetails->worker_start_date ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->start_date ?? 'As Soon As Possible' }}</h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->worker_start_date)
                            {{ $offerdetails->worker_start_date }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>
            {{-- End Date --}}

            <div class="col-md-12">
                <span class="mt-3">End Date</span>
            </div>
            <div class="row {{ $jobdetails->end_date == $offerdetails->worker_end_date ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->end_date ?? 'Missing End Date Information' }}</h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->worker_end_date)
                            {{ $offerdetails->worker_end_date }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>

            {{-- rto --}}

            <div class="col-md-12">
                <span class="mt-3">RTO</span>
            </div>
            <div class="row {{ $jobdetails->rto === $offerdetails->rto ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->rto ?? 'Missing RTO Information' }}</h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->rto)
                            {{ $offerdetails->rto }}
                        @else
                            ---
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
            <div class="row {{ $jobdetails->overtime === $offerdetails->worker_overtime_rate ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6> {{ isset($jobdetails->overtime) ? number_format($jobdetails->overtime) : 'Missing Overtime Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_overtime_rate) ? number_format($offerdetails->worker_overtime_rate) : '---' !!}
                    </p>
                </div>
            </div>
            {{-- on call --}}
            <div class="col-md-12">
                <span class="mt-3">On Call Hourly Rate</span>
            </div>
            <div class="row {{ $jobdetails->on_call_rate === $offerdetails->worker_on_call ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6> {{ isset($jobdetails->on_call_rate) ? '$ ' . number_format($jobdetails->on_call_rate) : 'Missing On Call Information' }}
                    </h6>

                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_on_call) ? '$ ' . number_format($offerdetails->worker_on_call) : '---' !!}
                    </p>
                </div>
            </div>
            {{-- call back --}}
            <div class="col-md-12">
                <span class="mt-3">Call Back Hourly Rate</span>
            </div>
            <div class="row {{ $jobdetails->call_back_rate === $offerdetails->worker_call_back ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ isset($jobdetails->call_back_rate) ? '$ ' . number_format($jobdetails->call_back_rate) : 'Missing Call Back Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_call_back) ? '$ ' . number_format($offerdetails->worker_call_back) : '---' !!}
                    </p>
                </div>
            </div>
            {{-- Orientation Rate --}}
            <div class="col-md-12">
                <span class="mt-3">Orientation Rate</span>
            </div>
            <div class="row {{ $jobdetails->orientation_rate === $offerdetails->worker_orientation_rate ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ isset($jobdetails->orientation_rate) ? '$ ' . number_format($jobdetails->orientation_rate) : 'Missing Orientation Rate Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_orientation_rate)
                            ? '$ ' . number_format($offerdetails->worker_orientation_rate)
                            : '---' !!}
                    </p>
                </div>
            </div>
            {{-- Weekly Taxable amount --}}
            <div class="col-md-12">
                <span class="mt-3">Est. Weekly Taxable amount</span>
            </div>
            <div class="row {{ $jobdetails->weekly_taxable_amount === $offerdetails->worker_weekly_taxable_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6> {{ isset($jobdetails->weekly_taxable_amount) ? '$ ' . number_format($jobdetails->weekly_taxable_amount) : 'Missing Est. Weekly Taxable amount Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_weekly_taxable_amount)
                            ? '$ ' . number_format($offerdetails->worker_weekly_taxable_amount)
                            : '---' !!}

                    </p>
                </div>
            </div>
            {{-- Weekly non-taxable amount --}}
            <div class="col-md-12">
                <span class="mt-3">Est. Weekly non-taxable amount</span>
            </div>
            <div class="row {{ $jobdetails->weekly_non_taxable_amount === $offerdetails->worker_weekly_non_taxable_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ isset($jobdetails->weekly_non_taxable_amount) ? '$ ' . number_format($jobdetails->weekly_non_taxable_amount) : 'Missing Est. Weekly non-taxable amount Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_weekly_non_taxable_amount)
                            ? '$ ' . number_format($offerdetails->worker_weekly_non_taxable_amount)
                            : '---' !!}
                    </p>
                </div>
            </div>

            {{-- Feels Like $/hr --}}

            <div class="col-md-12">
                <span class="mt-3">Feels Like $/hr</span>
            </div>
            <div class="row {{ $jobdetails->feels_like_per_hour === $offerdetails->worker_feels_like_per_hour ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ isset($jobdetails->feels_like_per_hour) ? '$ ' . number_format($jobdetails->feels_like_per_hour) : 'Missing Feels Like Per Hour Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_feels_like_per_hour)
                            ? '$ ' . number_format($offerdetails->worker_feels_like_per_hour)
                            : '---' !!}
                    </p>
                </div>
            </div>

            {{--  Gw$/Wk --}}
            <div class="col-md-12">
                <span class="mt-3">Est. Goodwork Weekly Amount</span>
            </div>
            <div class="col-md-12">
                <h6>{{ isset($jobdetails->goodwork_weekly_amount) ? '$ ' . number_format($jobdetails->goodwork_weekly_amount) : 'Missing Est. Goodwork Weekly Amount Information' }}
                </h6>
            </div>
            {{-- Referral Bonus --}}
            <div class="col-md-12">
                <span class="mt-3">Referral Bonus</span>
            </div>
            <div class="row {{ $jobdetails->referral_bonus === $offerdetails->worker_referral_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6> {{ isset($jobdetails->referral_bonus) ? '$ ' . number_format($jobdetails->referral_bonus) : 'Missing Referral Bonus Information' }}
                    </h6>

                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_referral_bonus)
                            ? '$ ' . number_format($offerdetails->worker_referral_bonus)
                            : '---' !!}
                    </p>
                </div>
            </div>

            {{-- Sign-On Bonus --}}

            <div class="col-md-12">
                <span class="mt-3">Sign-On Bonus</span>
            </div>
            <div class="row {{ $jobdetails->sign_on_bonus === $offerdetails->worker_sign_on_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ isset($jobdetails->sign_on_bonus) ? '$ ' . number_format($jobdetails->sign_on_bonus) : 'Missing Sign-On Bonus Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_sign_on_bonus)
                            ? '$ ' . number_format($offerdetails->worker_sign_on_bonus)
                            : '---' !!}
                    </p>
                </div>
            </div>

            {{-- Completion Bonus --}}
            <div class="col-md-12">
                <span class="mt-3">Completion Bonus</span>
            </div>
            <div class="row {{ $jobdetails->completion_bonus === $offerdetails->worker_completion_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ isset($jobdetails->completion_bonus) ? '$ ' . number_format($jobdetails->completion_bonus) : 'Missing Completion Bonus Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_completion_bonus)
                            ? '$ ' . number_format($offerdetails->worker_completion_bonus)
                            : '---' !!}
                    </p>
                </div>
            </div>
            {{-- Extension Bonus --}}
            <div class="col-md-12">
                <span class="mt-3">Extension Bonus</span>
            </div>
            <div class="row {{ $jobdetails->extension_bonus === $offerdetails->worker_extension_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ isset($jobdetails->extension_bonus) ? '$ ' . number_format($jobdetails->extension_bonus) : 'Missing Extension Bonus Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_extension_bonus)
                            ? '$ ' . number_format($offerdetails->worker_extension_bonus)
                            : '---' !!}
                    </p>
                </div>
            </div>
            {{-- Other Bonus --}}
            <div class="col-md-12">
                <span class="mt-3">Other Bonus</span>
            </div>
            <div class="row {{ $jobdetails->other_bonus === $offerdetails->worker_other_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ isset($jobdetails->other_bonus) ? '$ ' . number_format($jobdetails->other_bonus) : 'Missing Other Bonus Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        {!! isset($offerdetails->worker_other_bonus) ? '$ ' . number_format($offerdetails->worker_other_bonus) : '---' !!}
                    </p>
                </div>
            </div>
            {{-- Pay Frequency --}}

            <div class="col-md-12">
                <span class="mt-3">Pay Frequency</span>
            </div>

            <div class="row {{ $jobdetails->pay_frequency === $offerdetails->worker_pay_frequency ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">

                <div class="col-md-6">
                    <h6>{{ $jobdetails->pay_frequency ?? 'Missing Pay Frequency Information' }}</h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->worker_pay_frequency)
                            {{ $offerdetails->worker_pay_frequency }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>

            {{-- Benefits --}}

            <div class="col-md-12">
                <span class="mt-3">Benefits</span>
            </div>
            @php
                $offerBenefits = !empty($jobdetails->benefits) ? explode(',', $jobdetails->benefits) : [];
                $userBenefits = !empty($offerdetails->worker_benefits)
                    ? explode(',', $offerdetails->worker_benefits)
                    : [];

                $offerBenefits = array_map('trim', $offerBenefits);
                $userBenefits = array_map('trim', $userBenefits);

                $benefitsMatch =
                    !empty($offerBenefits) && !empty($userBenefits) && array_intersect($offerBenefits, $userBenefits);
            @endphp

            <div class="row {{ $benefitsMatch ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ !empty($offerBenefits) ? implode(', ', $offerBenefits) : 'Missing Benefits Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if (!empty($userBenefits))
                            {{ implode(', ', $userBenefits) }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>

            {{-- Est. Total Organization Amount --}}
            <div class="col-md-12">
                <span class="mt-3">Est. Total Organization Amount</span>
            </div>
            <div class="col-md-12">
                <h6> {{ isset($jobdetails->total_organization_amount) ? '$ ' . number_format($jobdetails->total_organization_amount) : 'Missing Est. Total Organization Amount Information' }}
                </h6>
            </div>
            {{-- Total Goodwork Amount --}}
            <div class="col-md-12">
                <span class="mt-3">Est. Total Goodwork Amount</span>
            </div>
            <div class="col-md-12">
                <h6>{{ isset($jobdetails->total_goodwork_amount) ? '$ ' . number_format($jobdetails->total_goodwork_amount) : 'Missing Est. Total Goodwork Amount Information' }}
                </h6>
            </div>
            {{-- Total Contract Amount --}}
            <div class="col-md-12">
                <span class="mt-3">Est. Total Contract Amount</span>
            </div>
            <div class="col-md-12">
                <h6>{{ isset($jobdetails->total_contract_amount) ? '$ ' . number_format($jobdetails->total_contract_amount) : 'Missing Est. Total Contract Amount Information' }}
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
            <div class="row {{ $jobdetails->clinical_setting === $offerdetails->clinical_setting_you_prefer ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->clinical_setting ?? 'Missing Clinical Setting Information' }}</h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->clinical_setting_you_prefer)
                            {{ $offerdetails->clinical_setting_you_prefer }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>

            {{-- Preferred Work Location --}}
            <div class="col-md-12">
                <span class="mt-3">Preferred Work Location</span>
            </div>
            <div class="row {{ $jobdetails->preferred_work_location === $offerdetails->worker_preferred_work_location ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->preferred_work_location ?? 'Missing Preferred Work Location Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->worker_preferred_work_location)
                            {{ $offerdetails->worker_preferred_work_location }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>

            {{-- Facility Name --}}

            <div class="col-md-12">
                <span class="mt-3">Facility Name</span>
            </div>
            <div class="row {{ $jobdetails->facility_name === $offerdetails->worker_facility_name ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->facility_name ?? 'Missing Facility Name Information' }}</h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->worker_facility_name)
                            {{ $offerdetails->worker_facility_name }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>

            {{-- facilitys_parent_system | worker_facilitys_parent_system --}}
            <div class="col-md-12">
                <span class="mt-3">Facility's Parent System</span>
            </div>
            <div class="row {{ $jobdetails->facilitys_parent_system === $offerdetails->worker_facilitys_parent_system ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">

                <div class="col-md-6">
                    <h6>{{ $jobdetails->facilitys_parent_system ?? 'Missing Facility\'s Parent System Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->worker_facilitys_parent_system)
                            {{ $offerdetails->worker_facilitys_parent_system }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>
            {{-- Facility Shift Cancellation Policy --}}
            <div class="col-md-12">
                <span class="mt-3">Facility Shift Cancellation Policy</span>
            </div>
            <div class="row {{ $jobdetails->facility_shift_cancelation_policy === $offerdetails->facility_shift_cancelation_policy ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->facility_shift_cancelation_policy ?? 'Missing Facility Shift Cancellation Policy Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->facility_shift_cancelation_policy)
                            {{ $offerdetails->facility_shift_cancelation_policy }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>
            {{-- Contract Termination Policy --}}
            <div class="col-md-12">
                <span class="mt-3">Contract Termination Policy</span>
            </div>
            <div class="row {{ $jobdetails->contract_termination_policy === $offerdetails->contract_termination_policy ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->contract_termination_policy ?? 'Missing Contract Termination Policy Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->contract_termination_policy)
                            {{ $offerdetails->contract_termination_policy }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>
            {{-- Traveler Distance From Facility --}}
            <div class="col-md-12">
                <span class="mt-3">Traveler Distance From Facility</span>
            </div>
            <div class="row {{ $jobdetails->traveler_distance_from_facility === $offerdetails->distance_from_your_home ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->traveler_distance_from_facility ?? 'Missing Traveler Distance From Facility Information' }}
                        {{ $jobdetails->traveler_distance_from_facility ? 'miles Maximum' : '' }}</h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->distance_from_your_home)
                            {{ $offerdetails->distance_from_your_home }}
                        @else
                            ---
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
                $offerLicenses = !empty($jobdetails->job_location) ? explode(',', $jobdetails->job_location) : [];
                $userLicenses = !empty($offerdetails->worker_job_location)
                    ? explode(',', $offerdetails->worker_job_location)
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
                            ---
                        @endif
                    </p>
                </div>
            </div>

            {{-- Certifications --}}
            <div class="col-md-12">
                <span class="mt-3">Certifications</span>
            </div>

            @php
                $offerCertificates = !empty($jobdetails->certificate) ? explode(',', $jobdetails->certificate) : [];
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
                    <h6>{{ $jobdetails->description ?? 'Missing Description Information' }}</h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->worker_description)
                            {{ $offerdetails->worker_description }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>

            {{-- urgency --}}
            <div class="col-md-12">
                <span class="mt-3">Urgency</span>
            </div>
            <div class="row {{ $jobdetails->urgency === $offerdetails->worker_urgency ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>
                        @if ($jobdetails->urgency == 'Auto Offer')
                            Yes
                        @elseif(empty($jobdetails->urgency))
                            Missing Urgency Information
                        @else
                            No
                        @endif
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->worker_urgency == 'Auto Offer')
                            Yes
                        @elseif(empty($offerdetails->worker_urgency))
                            ---
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
            <div class="row {{ $jobdetails->preferred_experience === $offerdetails->worker_experience ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->preferred_experience . ' Year(s)' ?? 'Missing Preferred Experience Information' }}
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->worker_experience)
                            {{ $offerdetails->worker_experience . ' Year(s)' }}
                        @else
                            ---
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
                    <h6>{{ $jobdetails->number_of_references . ' Reference(s)' ?? 'Missing References Information' }}
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
                $offerSkills = !empty($jobdetails->skills) ? explode(',', $jobdetails->skills) : [];
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

            <div class="row {{ $jobdetails->on_call === $offerdetails->on_call ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>
                        @if ($jobdetails->on_call == '1')
                            Yes
                        @elseif($jobdetails->on_call == '0')
                            No
                        @else
                            ----
                        @endif
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->on_call == '1')
                            Yes
                        @elseif($offerdetails->on_call == '0')
                            No
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>

            {{-- Block Scheduling --}}

            <div class="col-md-12">
                <span class="mt-3">Block scheduling</span>
            </div>
            <div class="row {{ $jobdetails->block_scheduling === $offerdetails->block_scheduling ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>
                        @if ($jobdetails->block_scheduling == '1')
                            Yes
                        @elseif($jobdetails->block_scheduling == '0')
                            No
                        @else
                            ----
                        @endif
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->block_scheduling == '1')
                            Yes
                        @elseif($offerdetails->block_scheduling == '0')
                            No
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>
            {{-- Float requirements --}}
            <div class="col-md-12">
                <span class="mt-3">Float requirements</span>
            </div>
            <div class="row {{ $jobdetails->float_requirement === $offerdetails->float_requirement ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>
                        @if ($jobdetails->float_requirement == '1')
                            Yes
                        @elseif($jobdetails->float_requirement == '0')
                            No
                        @else
                            ----
                        @endif
                    </h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->float_requirement == '1')
                            Yes
                        @elseif($offerdetails->float_requirement == '0')
                            No
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>
            {{-- Patient ratio --}}
            <div class="col-md-12">
                <span class="mt-3">Patient ratio</span>
            </div>
            <div class="row {{ $jobdetails->patient_ratio === $offerdetails->worker_patient_ratio ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->patient_ratio ?? 'Missing Patient Ratio Information' }}</h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->worker_patient_ratio)
                            {{ $offerdetails->worker_patient_ratio }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>
            {{-- Emr --}}
            <div class="col-md-12">
                <span class="mt-3">EMR</span>
            </div>
            <div class="row {{ $jobdetails->emr === $offerdetails->worker_emr ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->emr ?? 'Missing EMR Information' }}</h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->worker_emr)
                            {{ $offerdetails->worker_emr }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>
            {{-- Unit --}}
            <div class="col-md-12">
                <span class="mt-3">Unit</span>
            </div>
            <div class="row {{ $jobdetails->unit === $offerdetails->worker_unit ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->unit ?? 'Missing Unit Information' }}</h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->worker_unit)
                            {{ $offerdetails->worker_unit }}
                        @else
                            ---
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

            <div class="row {{ $jobdetails->nurse_classification === $offerdetails->nurse_classification ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
                style="margin:auto;">
                <div class="col-md-6">
                    <h6>{{ $jobdetails->nurse_classification ?? 'Missing Classification Information' }}</h6>
                </div>
                <div class="col-md-6 ">
                    <p>
                        @if ($offerdetails->nurse_classification)
                            {{ $offerdetails->nurse_classification }}
                        @else
                            ---
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
                $offerVaccinations = !empty($jobdetails->vaccinations) ? explode(',', $jobdetails->vaccinations) : [];
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

        <div class="ss-job-apl-on-app-btn">
            <button type="button" disabled>Applied</button>
        </div>

    </div>


</div>

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

{{-- TODO :: get documents and update values of certifications, professional licenses, ... --}}
