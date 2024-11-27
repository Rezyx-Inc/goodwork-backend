<ul class="ss-cng-appli-hedpfl-ul">
    <li style="width:55%;">
        <span>{{ $userdetails->nurse->id }}</span>
        <h6>
            <img width="50px" height="50px"
                src="{{ URL::asset('public/images/nurses/profile/' . $userdetails->image) }}"
                onerror="this.onerror=null;this.src='{{ URL::asset('frontend/img/profile-pic-big.png') }}';"
                id="preview" style="object-fit: cover;" class="rounded-3" alt="Profile Picture">
            {{ $userdetails->first_name }} {{ $userdetails->last_name }}
        </h6>
    </li>
    @if ($hasFile == true)
        <li style="margin-right:10px;">
            <a style="cursor:pointer;" class="rounded-pill ss-apply-btn py-2 border-0 px-4" data-target="file"
                data-hidden_value="Yes" data-href="" data-title="Worker's Files" data-name="diploma"
                onclick="open_modal(this)">
                Consult worker files
            </a>
        </li>
        <li>
            <a onclick="askWorker(this, 'nursing_profession', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->recruiter_id }}', '{{ $offerdetails[0]->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')"
                class="rounded-pill ss-apply-btn py-2 border-0 px-4" style="cursor: pointer;">Chat Now</a>
        </li>
    @else
        <li>
            <a onclick="askWorker(this, 'nursing_profession', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->recruiter_id }}', '{{ $offerdetails[0]->organization_id }}', '{{ $userdetails->first_name }} {{ $userdetails->last_name }}')"
                class="rounded-pill ss-apply-btn py-2 border-0 px-4" style="cursor: pointer;">Chat Now</a>
        </li>
    @endif
</ul>
<div class="ss-appli-cng-abt-inf-dv">
    <h5>Applicant summary</h5>
    <p>{{ $userdetails->about_me }}</p>
</div>
<div class="ss-applicatio-infor-texts-dv">
    <ul class="row">
        <li class="col-md-6">
            <p>Profession</p>
            <h6>
                @isset($nursedetails['profession'])
                    {{ $nursedetails['profession'] }}
                @else
                    <a style="cursor: pointer;"
                        onclick="askWorker(this, 'highest_nursing_degree', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')">Ask
                        Worker</a>
                @endisset
            </h6>
        </li>
        <li class="col-md-6">
            <p>Specialty</p>
            <h6 class="mb-3">
                @isset($nursedetails['specialty'])
                    {{ $nursedetails['specialty'] }}
                @else
                    <a style="cursor: pointer;"
                        onclick="askWorker(this, 'specialty', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')">Ask
                        Worker</a>
                @endisset
            </h6>
        </li>
        <li class="col-md-6">
            <p>Traveler Distance From Facility</p>
            <h6 class="mb-3">
                @isset($nursedetails['distance_from_your_home'])
                    {{ $nursedetails['distance_from_your_home'] }}
                @else
                    <a style="cursor: pointer;"
                        onclick="askWorker(this, 'distance_from_your_home', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')">Ask
                        Worker</a>
                @endisset
            </h6>
        </li>
        <li class="col-md-6">
            <p>Facility</p>
            <h6 class="mb-3">
                @isset($nursedetails['worked_at_facility_before'])
                    {{ $nursedetails['worked_at_facility_before'] }}
                @else
                    <a style="cursor: pointer;"
                        onclick="askWorker(this, 'worked_at_facility_before', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')">Ask
                        Worker</a>
                @endisset
            </h6>
        </li>
        <li class="col-md-6">
            <p>Location</p>
            <h6 class="mb-3">
                @isset($nursedetails['state'])
                    {{ $nursedetails['state'] }}
                @else
                    <a style="cursor: pointer;"
                        onclick="askWorker(this, 'state', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')">Ask
                        Worker</a>
                @endisset
            </h6>
        </li>
        <li class="col-md-6">
            <p>Shift</p>
            <h6 class="mb-3">
                @isset($nursedetails['worker_shift_time_of_day'])
                    {{ $nursedetails['worker_shift_time_of_day'] }}
                @else
                    <a style="cursor: pointer;"
                        onclick="askWorker(this, 'worker_shift_time_of_day', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')">Ask
                        Worker</a>
                @endisset
            </h6>
        </li>
        <li class="col-md-6">
            <p>Distance from your home</p>
            <h6 class="mb-3">
                @isset($nursedetails['distance_from_your_home'])
                    {{ $nursedetails['distance_from_your_home'] }}
                @else
                    <a style="cursor: pointer;"
                        onclick="askWorker(this, 'distance_from_your_home', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')">Ask
                        Worker</a>
                @endisset
            </h6>
        </li>
        <li class="col-md-6">
            <p>Facilities you've worked at</p>
            <h6 class="mb-3">
                @isset($nursedetails['facilities_you_like_to_work_at'])
                    {{ $nursedetails['facilities_you_like_to_work_at'] }}
                @else
                    <a style="cursor: pointer;"
                        onclick="askWorker(this, 'facilities_you_like_to_work_at', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')">Ask
                        Worker</a>
                @endisset
            </h6>
        </li>
        <li class="col-md-6">
            <p>Start Date</p>
            <h6 class="mb-3">
                @isset($nursedetails['worker_start_date'])
                    {{ $nursedetails['worker_start_date'] }}
                @else
                    <a style="cursor: pointer;"
                        onclick="askWorker(this, 'worker_start_date', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')">Ask
                        Worker</a>
                @endisset
            </h6>
        </li>
        <li class="col-md-6">
            <p>RTO</p>
            <h6 class="mb-3">
                @isset($offerdetails['rto'])
                    {{ $offerdetails['rto'] }}
                @else
                    <a style="cursor: pointer;"
                        onclick="askWorker(this, 'rto', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]['job_id'] }}')">Ask
                        Worker</a>
                @endisset
            </h6>
        </li>
        <li class="col-md-6">
            <p>Shift Time of Day</p>
            <h6 class="mb-3">
                @isset($nursedetails['worker_shift_time_of_day'])
                    {{ $nursedetails['worker_shift_time_of_day'] }}
                @else
                    <a style="cursor: pointer;"
                        onclick="askWorker(this, 'worker_shift_time_of_day', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')">Ask
                        Worker</a>
                @endisset
            </h6>
        </li>
        <li class="col-md-6">
            <p>Weeks/Assignment</p>
            <h6 class="mb-3">
                @isset($offerdetails['worker_weeks_assignment'])
                    {{ $offerdetails['worker_weeks_assignment'] }}
                @else
                    <a style="cursor: pointer;"
                        onclick="askWorker(this, 'worker_weeks_assignment', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')">Ask
                        Worker</a>
                @endisset
            </h6>
        </li>
        <li class="col-md-6">
            <p>Organization Weekly Amount</p>
            <h6 class="mb-3">
                @isset($nursedetails['worker_organization_weekly_amount'])
                    {{ $nursedetails['worker_organization_weekly_amount'] }}
                @else
                    <a style="cursor: pointer;"
                        onclick="askWorker(this, 'worker_organization_weekly_amount', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')">Ask
                        Worker</a>
                @endisset
            </h6>
        </li>
        <li class="col-md-12 ss-chng-appli-slider-mn-dv">
            <p>Application details({{ $jobappliedcount }})</p>
        </li>
    </ul>
</div>


<div class="ss-chng-appli-slider-mn-dv">
    <div class="{{ $jobappliedcount > 1 ? 'owl-carousel application-job-slider-owl' : '' }} application-job-slider">
        @foreach ($offerdetails as $value)
            <div style="width:100%;" class="ss-chng-appli-slider-sml-dv"
            @if ($value['status'] == 'Apply')
               onclick="applicationStatusToScreening('Screening','{{ $value->worker_user_id }}', '{{ $value->id }}')"
            @else
                onclick="getOneOfferInformation('{{ $value->id }}')"
            @endif
            >
                <ul class="ss-cng-appli-slid-ul1">
                    <li class="d-flex">
                        <p>{{ $value->terms }}</p>
                        <span>{{ $jobappliedcount }} Workeds Applied</span>
                    </li>
                    <li>Posted on {{ \Carbon\Carbon::parse($value->start_date)->format('M d, Y') }}</li>
                </ul>
                <h4>{{ $value->job_name }}</h4>
                <ul class="ss-cng-appli-slid-ul2 d-block">
                    @if(isset($value->job->job_city) && isset($value->job->job_state))
                        <li class="d-inline-block">{{ $value->job->job_city }}, {{ $value->job->job_state }}</li>
                    @elseif(isset($value->job->job_city))
                        <li class="d-inline-block">{{ $value->job->job_city }}</li>
                    @elseif(isset($value->job->job_state))
                        <li class="d-inline-block">{{ $value->job->job_state }}</li>
                    @endif
                    @if(isset($value->job->preferred_shift_duration))
                        <li class="d-inline-block">{{ $value->job->preferred_shift_duration }} </li>
                    @endif
                    @if(isset($value->job->weeks_shift))
                        <li class="d-inline-block">{{ $value->job->weeks_shift }} wks</li>
                    @endif
                </ul>
                <ul class="ss-cng-appli-slid-ul3">
                    <li><span>{{ $value->facility }}</span></li>
                    <li>
                        <h6>${{ $value->hours_per_week }}/wk</h6>
                    </li>
                </ul>
                <h5>{{ $value->job_id }}</h5>
            </div>
        @endforeach
    </div>
</div>

<script>
   
</script>

<style>
        .application-job-slider{
            cursor: pointer;
        }
</style>
