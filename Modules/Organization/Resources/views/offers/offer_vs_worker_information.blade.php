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
    <li>

    </li>
</ul>

<div class="ss-chng-apcon-st-ssele">

    <label class="mb-2">Change Application Status</label>
    <div class="row d-flex justify-content-center align-items-center">
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
    <ul class="ss-jb-apply-on-inf-hed-rec row">
        <li class="col-md-6">
            <h5 class="mt-3">Job Information</h5>
        </li>
        <li class="col-md-6">
            <h5 class="mt-3">Worker Information</h5>
        </li>

        <div class="col-md-12">
            <span class="mt-3">Profession</span>
        </div>
        <div class="row {{ $offerdetails->profession == $userdetails->nurse->profession ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>
                    {{ $offerdetails->profession ?? '----' }}
                </h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->profession ? '' : 'd-none' }}">
                <p>
                    @isset($userdetails->nurse->profession)
                        {{ $userdetails->nurse->profession }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'nursing_profession', '{{ $userdetails->nurse->id }}', '{{ $offerdetails->id }}')">Ask
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
                <h6>{{ $offerdetails->specialty ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->specialty ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->specialty)
                        {{ $userdetails->nurse->specialty }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'nursing_specialty', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
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
            <div class="col-md-6 {{ isset($offerdetails->block_scheduling) ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->block_scheduling == '1')
                        Yes
                    @elseif($userdetails->nurse->block_scheduling == '0')
                        No
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'block_scheduling', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
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
            <div class="col-md-6 {{ isset($offerdetails->float_requirement) ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->float_requirement == '1')
                        Yes
                    @elseif($userdetails->nurse->float_requirement == '0')
                        No
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'float_requirement', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Facility Shift Cancellation Policy</span>
        </div>
        <div class="row {{ $offerdetails->facility_shift_cancelation_policy === $userdetails->nurse->facility_shift_cancelation_policy ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->facility_shift_cancelation_policy ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->facility_shift_cancelation_policy ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->facility_shift_cancelation_policy)
                        {{ $userdetails->nurse->facility_shift_cancelation_policy }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'facility_shift_cancelation_policy', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Contract Termination Policy</span>
        </div>
        <div class="row {{ $offerdetails->contract_termination_policy === $userdetails->nurse->contract_termination_policy ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->contract_termination_policy ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->contract_termination_policy ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->contract_termination_policy)
                        {{ $userdetails->nurse->contract_termination_policy }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'contract_termination_policy', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Traveler Distance From Facility</span>
        </div>
        <div class="row {{ $offerdetails->traveler_distance_from_facility === $userdetails->nurse->distance_from_your_home ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->traveler_distance_from_facility ?? '----' }} miles Maximum</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->traveler_distance_from_facility ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->distance_from_your_home)
                        {{ $userdetails->nurse->distance_from_your_home }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'distance_from_your_home', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Facility</span>
        </div>
        <div class="row {{ $offerdetails->facility === $userdetails->nurse->worked_at_facility_before ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->facility ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->facility ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worked_at_facility_before)
                        {{ $userdetails->nurse->worked_at_facility_before }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worked_at_facility_before', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Clinical Setting</span>
        </div>
        <div class="row {{ $offerdetails->clinical_setting === $userdetails->nurse->clinical_setting_you_prefer ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->clinical_setting ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->clinical_setting ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->clinical_setting_you_prefer)
                        {{ $userdetails->nurse->clinical_setting_you_prefer }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'clinical_setting_you_prefer', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Patient ratio</span>
        </div>
        <div class="row {{ $offerdetails->patient_ratio === $userdetails->nurse->worker_patient_ratio ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->patient_ratio ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->patient_ratio ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_patient_ratio)
                        {{ $userdetails->nurse->worker_patient_ratio }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_patient_ratio', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">EMR</span>
        </div>
        <div class="row {{ $offerdetails->emr === $userdetails->nurse->worker_emr ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->emr ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->emr ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_emr)
                        {{ $userdetails->nurse->worker_emr }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_emr', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Unit</span>
        </div>
        <div class="row {{ $offerdetails->unit === $userdetails->nurse->worker_unit ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->unit ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->unit ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_unit)
                        {{ $userdetails->nurse->worker_unit }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_unit', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Scrub Color</span>
        </div>
        <div class="row {{ $offerdetails->scrub_color === $userdetails->nurse->worker_scrub_color ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->scrub_color ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->scrub_color ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_scrub_color)
                        {{ $userdetails->nurse->worker_scrub_color }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_scrub_color', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Interview Dates</span>
        </div>
        <div class="col-md-6">
            <h6>{{ $userdetails->nurse->worker_interview_dates }}</h6>
        </div>
        <div class="col-md-6 {{ $offerdetails->worker_interview_dates ? '' : 'd-none' }}">
            <p>
                @if ($userdetails->nurse->worker_interview_dates)
                    {{ $userdetails->nurse->worker_interview_dates }}
                @else
                    <a style="cursor: pointer;"
                        onclick="askWorker(this, 'worker_interview_dates', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                        Worker</a>
                @endif
            </p>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Start Date</span>
        </div>
        <div class="row {{ $offerdetails->start_date == $userdetails->nurse->worker_start_date ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->start_date ?? 'As Soon As Possible' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->start_date ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_start_date)
                        {{ $userdetails->nurse->worker_start_date }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_start_date', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">RTO</span>
        </div>
        <div class="row {{ $offerdetails->rto === $userdetails->nurse->rto ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->rto ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->rto ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->rto)
                        {{ $userdetails->nurse->rto }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'rto', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Shift Time of Day</span>
        </div>
        <div class="row {{ $offerdetails->preferred_shift == $userdetails->nurse->worker_shift_time_of_day ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->preferred_shift ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->preferred_shift ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_shift_time_of_day)
                        {{ $userdetails->nurse->worker_shift_time_of_day }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_shift_time_of_day', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Hours/Week</span>
        </div>
        <div class="row {{ $offerdetails->hours_per_week == $userdetails->nurse->worker_hours_per_week ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ number_format($offerdetails->hours_per_week) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->hours_per_week ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_hours_per_week)
                        {{ number_format($userdetails->nurse->worker_hours_per_week) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_hours_per_week', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Guaranteed Hours</span>
        </div>
        <div class="row {{ $offerdetails->guaranteed_hours == $userdetails->nurse->worker_guaranteed_hours ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ number_format($offerdetails->guaranteed_hours) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->guaranteed_hours ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_guaranteed_hours)
                        {{ number_format($userdetails->nurse->worker_guaranteed_hours) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_guaranteed_hours', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Hours/Shift</span>
        </div>
        <div class="row {{ $offerdetails->preferred_assignment_duration == $userdetails->nurse->worker_weeks_assignment ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ number_format($offerdetails->preferred_assignment_duration) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->preferred_assignment_duration ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_weeks_assignment)
                        {{ number_format($userdetails->nurse->worker_weeks_assignment) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_weeks_assignment', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Weeks/Assignment</span>
        </div>
        <div class="row {{ $offerdetails->weeks_shift == $userdetails->nurse->worker_shifts_week ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ number_format($offerdetails->preferred_assignment_duration) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->preferred_assignment_duration ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_weeks_assignment)
                        {{ number_format($userdetails->nurse->worker_weeks_assignment) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_weeks_assignment', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Shifts/Week</span>
        </div>
        <div class="row {{ $offerdetails->weeks_shift == $userdetails->nurse->worker_shifts_week ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ number_format($offerdetails->weeks_shift) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->weeks_shift ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_shifts_week)
                        {{ number_format($userdetails->nurse->worker_shifts_week) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_shifts_week', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Referral Bonus</span>
        </div>
        <div class="row {{ $offerdetails->referral_bonus === $userdetails->nurse->worker_referral_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>${{ number_format($offerdetails->referral_bonus) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->referral_bonus ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_referral_bonus)
                        {{ number_format($userdetails->nurse->worker_referral_bonus) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_referral_bonus', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Sign-On Bonus</span>
        </div>
        <div class="row {{ $offerdetails->sign_on_bonus === $userdetails->nurse->worker_sign_on_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>${{ number_format($offerdetails->sign_on_bonus) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->sign_on_bonus ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_sign_on_bonus)
                        {{ number_format($userdetails->nurse->worker_sign_on_bonus) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_sign_on_bonus', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Completion Bonus</span>
        </div>
        <div class="row {{ $offerdetails->completion_bonus === $userdetails->nurse->worker_completion_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>${{ number_format($offerdetails->completion_bonus) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->completion_bonus ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_completion_bonus)
                        {{ number_format($userdetails->nurse->worker_completion_bonus) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_completion_bonus', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Extension Bonus</span>
        </div>
        <div class="row {{ $offerdetails->extension_bonus === $userdetails->nurse->worker_extension_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>${{ number_format($offerdetails->extension_bonus) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->extension_bonus ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_extension_bonus)
                        {{ number_format($userdetails->nurse->worker_extension_bonus) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_extension_bonus', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Other Bonus</span>
        </div>
        <div class="row {{ $offerdetails->other_bonus === $userdetails->nurse->worker_other_bonus ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>${{ number_format($offerdetails->other_bonus) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->other_bonus ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_other_bonus)
                        {{ number_format($userdetails->nurse->worker_other_bonus) }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_other_bonus', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">401K</span>
        </div>
        <div class="row {{ $offerdetails->four_zero_one_k === $userdetails->nurse->how_much_k ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->four_zero_one_k == '1' ? 'Yes' : ($offerdetails->four_zero_one_k == '0' ? 'No' : '----') }}
                </h6>
            </div>
            <div class="col-md-6 {{ isset($offerdetails->four_zero_one_k) ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->how_much_k)
                        {{ $userdetails->nurse->how_much_k }}
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'how_much_k', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Health Insurance</span>
        </div>
        <div class="row {{ $offerdetails->health_insurance === $userdetails->nurse->worker_health_insurance ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->health_insurance == '1' ? 'Yes' : ($offerdetails->health_insurance == '0' ? 'No' : '----') }}
                </h6>
            </div>
            <div class="col-md-6 {{ isset($offerdetails->health_insurance) ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_health_insurance == '1')
                        Yes
                    @elseif($userdetails->nurse->worker_health_insurance == '0')
                        No
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_health_insurance', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Dental</span>
        </div>
        <div class="row {{ $offerdetails->dental === $userdetails->nurse->worker_dental ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->dental == '1' ? 'Yes' : ($offerdetails->dental == '0' ? 'No' : '----') }}</h6>
            </div>
            <div class="col-md-6 {{ isset($offerdetails->dental) ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_dental == '1')
                        Yes
                    @elseif($userdetails->nurse->worker_dental == '0')
                        No
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_dental', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Vision</span>
        </div>
        <div class="row {{ $offerdetails->vision === $userdetails->nurse->worker_vision ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->vision == '1' ? 'Yes' : ($offerdetails->vision == '0' ? 'No' : '----') }}</h6>
            </div>
            <div class="col-md-6 {{ isset($offerdetails->vision) ? '' : 'd-none' }}">
                <p>
                    @if ($userdetails->nurse->worker_vision == '1')
                        Yes
                    @elseif($userdetails->nurse->worker_vision == '0')
                        No
                    @else
                        <a style="cursor: pointer;"
                            onclick="askWorker(this, 'worker_vision', '{{ $userdetails->nurse['id'] }}', '{{ $offerdetails['id'] }}')">Ask
                            Worker</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Actual Hourly rate</span>
        </div>
        <div class="row {{ $offerdetails->actual_hourly_rate === $userdetails->nurse->worker_actual_hourly_rate ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ number_format($offerdetails->actual_hourly_rate) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->actual_hourly_rate ? '' : 'd-none' }}">
                <p>
                    {!! number_format($userdetails->nurse->worker_actual_hourly_rate) ??
                        '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_actual_hourly_rate\', \'' .
                            $userdetails->nurse['id'] .
                            '\', \'' .
                            $offerdetails['id'] .
                            '\')">Ask Worker</a>' !!}

                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Overtime</span>
        </div>
        <div class="row {{ $offerdetails->overtime === $userdetails->nurse->worker_overtime ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ number_format($offerdetails->overtime) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->overtime ? '' : 'd-none' }}">

                <p>
                    {!! $userdetails->nurse->worker_overtime
                        ? number_format($userdetails->nurse->worker_overtime)
                        : '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_overtime\', \'' .
                            $userdetails->nurse['id'] .
                            '\', \'' .
                            $offerdetails['id'] .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Holiday</span>
        </div>
        <div class="row {{ $offerdetails->holiday === $userdetails->nurse->worker_holiday ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->holiday ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->holiday ? '' : 'd-none' }}">
                <p>
                    {!! $userdetails->nurse->worker_holiday ??
                        '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_holiday\', \'' .
                            $userdetails->nurse['id'] .
                            '\', \'' .
                            $offerdetails['id'] .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">On Call</span>
        </div>
        <div class="row {{ $offerdetails->on_call === $userdetails->nurse->worker_on_call ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ number_format($offerdetails->on_call) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->on_call ? '' : 'd-none' }}">
                <p>
                    {!! number_format($userdetails->nurse->worker_on_call) ??
                        '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_on_call\', \'' .
                            $userdetails->nurse['id'] .
                            '\', \'' .
                            $offerdetails['id'] .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Call Back</span>
        </div>
        <div class="row {{ $offerdetails->call_back === $userdetails->nurse->worker_call_back ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ $offerdetails->call_back ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->call_back ? '' : 'd-none' }}">
                <p>
                    {!! $userdetails->nurse->worker_call_back ??
                        '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_call_back\', \'' .
                            $userdetails->nurse['id'] .
                            '\', \'' .
                            $offerdetails['id'] .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Orientation Rate</span>
        </div>
        <div class="row {{ $offerdetails->orientation_rate === $userdetails->nurse->worker_orientation_rate ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">

                <h6>{{ number_format($offerdetails->orientation_rate) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->orientation_rate ? '' : 'd-none' }}">
                <p>
                    {!! $userdetails->nurse->worker_orientation_rate ??
                        '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_orientation_rate\', \'' .
                            $userdetails->nurse['id'] .
                            '\', \'' .
                            $offerdetails['id'] .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Est. Weekly Taxable amount</span>
        </div>
        <div class="row {{ $offerdetails->weekly_taxable_amount === $userdetails->nurse->worker_weekly_taxable_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ number_format($offerdetails->weekly_taxable_amount) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->weekly_taxable_amount ? '' : 'd-none' }}">
                <p>
                    {!! number_format($userdetails->nurse->worker_weekly_taxable_amount) ??
                        '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_weekly_taxable_amount\', \'' .
                            $userdetails->nurse['id'] .
                            '\', \'' .
                            $offerdetails['id'] .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Est. Organization Weekly Amount</span>
        </div>
        <div class="row {{ $offerdetails->organization_weekly_amount === $userdetails->nurse->worker_organization_weekly_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ number_format($offerdetails->organization_weekly_amount) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->organization_weekly_amount ? '' : 'd-none' }}">
                <p>
                    {!! number_format($userdetails->nurse->worker_organization_weekly_amount) ??
                        '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_organization_weekly_amount\', \'' .
                            $userdetails->nurse['id'] .
                            '\', \'' .
                            $offerdetails['id'] .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Est. Weekly non-taxable amount</span>
        </div>
        <div class="row {{ $offerdetails->weekly_non_taxable_amount === $userdetails->nurse->worker_weekly_non_taxable_amount ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} d-flex align-items-center"
            style="margin:auto;">
            <div class="col-md-6">
                <h6>{{ number_format($offerdetails->weekly_non_taxable_amount) ?? '----' }}</h6>
            </div>
            <div class="col-md-6 {{ $offerdetails->weekly_non_taxable_amount ? '' : 'd-none' }}">
                <p>
                    {!! number_format($userdetails->nurse->worker_weekly_non_taxable_amount) ??
                        '<a style="cursor: pointer;" onclick="askWorker(this, \'worker_weekly_non_taxable_amount\', \'' .
                            $userdetails->nurse['id'] .
                            '\', \'' .
                            $offerdetails['id'] .
                            '\')">Ask Worker</a>' !!}
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Est. Goodwork Weekly Amount</span>
        </div>
        <div class="col-md-6">
            <h6>{{ number_format($offerdetails->weekly_taxable_amount) ?? '----' }}</h6>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Est. Total Organization Amount</span>
        </div>
        <div class="col-md-12">
            <h6>{{ number_format($offerdetails->total_organization_amount) ?? '----' }}</h6>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Est. Total Goodwork Amount</span>
        </div>
        <div class="col-md-12">
            <h6>{{ number_format($offerdetails->total_goodwork_amount) ?? '----' }}</h6>
        </div>
        <div class="col-md-12">
            <span class="mt-3">Est. Total Contract Amount</span>
        </div>
        <div class="col-md-12">
            <h6>{{ number_format($offerdetails->total_contract_amount) ?? '----' }}</h6>
        </div>
        @if ($offerdetails->status == 'Offered' || $offerdetails->status == 'Screening')
            <div class="ss-counter-buttons-div">
                <button class="counter-save-for-button"
                    onclick="AcceptOrRejectJobOffer('{{ $offerdetails->id }}', '{{ $offerdetails->job_id }}', 'rejectcounter')">Reject
                    Offer</button>
            </div>
        @endif
        @if ($offerdetails->status == 'Offered' && count($offerLogs) > 0)
            <button class="ss-counter-button" onclick="counterOffer('{{ $offerdetails->id }}')">Counter
                Offer</button>
            <button class="ss-counter-button"
                onclick="AcceptOrRejectJobOffer('{{ $offerdetails->id }}', '{{ $offerdetails->job_id }}', 'offersend')">Accept
                Offer</button>
        @endif

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        var workerId = @json($offerdetails['worker_user_id']);
        console.log(workerId);
        console.log('workerId', workerId);

        function activeWorkerClass(workerUserId) {

            var element = document.getElementById(workerUserId);
            console.log('element', element);
            element.classList.add('active');

        }
        
    });
</script>

<style>

</style>
