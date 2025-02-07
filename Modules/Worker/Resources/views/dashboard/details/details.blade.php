@extends('worker::layouts.main')
@section('mytitle', 'My Profile')
@section('css')
@stop
@section('content')

    {{-- helpers --}}
    @php
        function truncateText($text, $limit = 35)
        {
            return mb_strimwidth($text, 0, $limit, '...');
        }
    @endphp


    <!--Main layout-->
    <main style="padding-top: 130px" class="ss-main-body-sec">
        <div class="container">

            <!------------------- apply on jobs ------------------->

            <div class="ss-apply-on-jb-mmn-dv">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Explore</h2>

                        <!------ Preview ------->
                        <div class="ss-apply-on-jb-mmn-dv-box-divs">

                            <div class="ss-job-prfle-sec">
                                {{-- row 1 --}}
                                <div class="row">
                                    <div class="col-10">
                                        <ul>
                                            <li><a href="#"><svg style="vertical-align: sub;"
                                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-briefcase" viewBox="0 0 16 16">
                                                        <path
                                                            d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5m1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0M1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5" />
                                                    </svg> {{ $model->profession }}</a></li>
                                            <li><a href="#"> {{ $model->preferred_specialty }}</a></li>
                                        </ul>
                                    </div>
                                    <p class="col-2 text-center" style="padding-right:20px;">
                                        <span>+{{ $model->getOfferCount() }} Applied</span>
                                    </p>
                                </div>
                                {{-- row 2 --}}
                                <div class="row">
                                    {{-- <div class="col-3"><ul><li><a href="{{route('worker_job-details',['id'=>$model->id])}}"><img class="icon_cards" src="{{URL::asset('frontend/img/job.png')}}"> {{$model->job_name}}</a></li></ul></div> --}}
                                </div>
                                {{-- row 3 --}}
                                <div class="row">
                                    <div class="col-7">
                                        <ul>
                                            <li><a href="#"><img class="icon_cards"
                                                        src="{{ URL::asset('frontend/img/location.png') }}">
                                                    {{ $model->job_city }}, {{ $model->job_state }}</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-5 d-flex justify-content-end">
                                        <ul>
                                            <li><a href="#"><img class="icon_cards"
                                                        src="{{ URL::asset('frontend/img/calendar.png') }}">
                                                    {{ $model->preferred_assignment_duration }} wks</a></li>
                                            <li><a href="#"><img class="icon_cards"
                                                        src="{{ URL::asset('frontend/img/calendar.png') }}">
                                                    {{ $model->hours_per_week }} hrs/wk</a></li>

                                    </div>
                                </div>
                                {{-- row 4 --}}
                                <div class="row">
                                    <div class="col-4">
                                        <ul>
                                            <li>
                                                @if ($model->preferred_shift_duration == '5x8 Days' || $model->preferred_shift_duration == '4x10 Days')
                                                    <svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg"
                                                        width="25" height="25" fill="currentColor"
                                                        class="bi bi-brightness-alt-high-fill" viewBox="0 0 16 16">
                                                        <path
                                                            d="M8 3a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 3m8 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5m-13.5.5a.5.5 0 0 0 0-1h-2a.5.5 0 0 0 0 1zm11.157-6.157a.5.5 0 0 1 0 .707l-1.414 1.414a.5.5 0 1 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0m-9.9 2.121a.5.5 0 0 0 .707-.707L3.05 5.343a.5.5 0 1 0-.707.707zM8 7a4 4 0 0 0-4 4 .5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5 4 4 0 0 0-4-4" />
                                                    </svg>
                                                @elseif ($model->preferred_shift_duration == '3x12 Nights or Days')
                                                    <svg style="vertical-align: text-bottom;"
                                                        xmlns="http://www.w3.org/2000/svg" width="20" height="16"
                                                        fill="currentColor" class="bi bi-moon-stars" viewBox="0 0 16 16">
                                                        <path
                                                            d="M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277q.792-.001 1.533-.16a.79.79 0 0 1 .81.316.73.73 0 0 1-.031.893A8.35 8.35 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.75.75 0 0 1 6 .278M4.858 1.311A7.27 7.27 0 0 0 1.025 7.71c0 4.02 3.279 7.276 7.319 7.276a7.32 7.32 0 0 0 5.205-2.162q-.506.063-1.029.063c-4.61 0-8.343-3.714-8.343-8.29 0-1.167.242-2.278.681-3.286" />
                                                        <path
                                                            d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.73 1.73 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.73 1.73 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.73 1.73 0 0 0 1.097-1.097zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z" />
                                                    </svg>
                                                @endif
                                                {{ $model->preferred_shift_duration }}
                                            </li>

                                        </ul>
                                    </div>
                                    <div class="col-8 d-flex justify-content-end">
                                        <ul>

                                            <li><img class="icon_cards"
                                                    src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                                                {{ number_format($model->actual_hourly_rate, 2) }}/hr</li>
                                            <li><img class="icon_cards"
                                                    src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                                                {{ number_format($model->weekly_pay) }}/wk</li>
                                            <li style="font-weight: 600;"><img class="icon_cards"
                                                    src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                                                {{ number_format($model->weekly_pay * 4 * 12) }}/yr</li>
                                        </ul>
                                    </div>
                                </div>


                                {{-- row 5 --}}
                                <div class="row">
                                    {{-- <div class="col-6"><h5>Recently Added</h5></div> --}}
                                    <div class="col-12 d-flex justify-content-end">
                                        @if ($model->urgency == 'Auto Offer' || $model->as_soon_as == true)
                                            <p class="col-2 text-center" style="padding-bottom: 0px; padding-top: 8px;">
                                                Urgent</p>
                                        @endif
                                    </div>
                                </div>

                                <a href="javascript:void(0)" data-id="{{ $model->id }}" onclick="save_jobs(this, event)"
                                    class="ss-jb-prfl-save-ico">
                                    @if ($jobSaved->check_if_saved($model->id))
                                        <img src="{{ URL::asset('frontend/img/bookmark.png') }}" />
                                    @else
                                        <img src="{{ URL::asset('frontend/img/job-icon-bx-Vector.png') }}" />
                                    @endif
                                </a>
                            </div>




                        </div>
                        <!----------------apply job : view details--------------->
                        <div class="ss-job-apply-on-view-detls-mn-dv">

                            <!---------------- Header ----------------->
                            <div class="ss-job-apply-on-tx-bx-hed-dv">
                                <ul>
                                    <li>
                                        <p>Recruiter</p>
                                    </li>
                                    <li>
                                        <img width="50px"
                                            src="{{ $model->recruiter && $model->recruiter->image ? URL::asset('uploads/' . $model->recruiter->image) : URL::asset('/frontend/img/profile-icon-img.png') }}"
                                            alt="Recruiter Image" loading="lazy" />{{ $model->recruiter->first_name }}
                                        {{ $model->recruiter->last_name }}
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
                                <h5>About Work</h5>
                                <ul>
                                    <li>
                                        <h6>Organization Name</h6>
                                        <p>{{ $model->recruiter->organization_name ? $model->recruiter->organization_name : 'Missing information' }}
                                        </p>
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
                                <p id="job_description">{!! $model->description !!} </p>
                            </div>


                            <!-------Work Information------->
                            <div class="ss-jb-apl-oninfrm-mn-dv">

                                @php
                                    $matches = [];

                                    foreach ($model->matchWithWorker() as $key => $closure) {
                                        $matches[$key] = $closure();
                                    }

                                @endphp
                                @php
                                    $userMatches = [];
                                    foreach ($model->matchWithWorker() as $key => $closure) {
                                        $userMatches[$key] = $closure();
                                    }
                                @endphp

                                {{-- fn check if value isset and not null --}}
                                @php
                                    function checkValue($value)
                                    {
                                        return isset($value) && $value != null && $value != '';
                                    }
                                @endphp

                                {{-- Summary --}}
                                <button class="btn first-collapse" data-toggle="collapse"
                                    data-target="#summary">Summary</button>
                                <div id="summary" class="collapse">
                                    <ul class="ss-jb-apply-on-inf-hed mt-3">
                                        <li>
                                            <h5>Work Information</h5>
                                        </li>
                                        <li>
                                            <h5>Your Information</h5>
                                        </li>
                                    </ul>


                                    <ul id="worker_job_type"
                                        class="ss-s-jb-apl-on-inf-txt-ul type_item {{ checkValue($model->job_type) ? ( $matches['job_type']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') : '' }}">
                                        <li>
                                            <span>Type</span>
                                            <h6>
                                                @if (checkValue($model->job_type))
                                                    {{ $model->job_type }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Type</span>

                                            <p class="profile_info_text" data-target="worker_job_type"
                                                data-title="Your Type !" data-name="worker_job_type"
                                                onclick="open_multiselect_modal(this)">

                                                @if (!!$nurse->worker_job_type)
                                                    {{ truncateText($nurse->worker_job_type) }}
                                                @else
                                                    Your Type !
                                                @endif
                                            </p>
                                        </li>
                                    </ul>


                                    <ul id="terms"
                                        class="ss-s-jb-apl-on-inf-txt-ul terms_item {{ checkValue($model->terms) ? ( $matches['terms']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Terms</span>

                                            <h6>
                                                @if (checkValue($model->terms))
                                                    {{ $model->terms }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Terms</span>

                                            <p class="profile_info_text" data-target="terms"
                                                data-title="Your Terms !" data-name="terms"
                                                onclick="open_multiselect_modal(this)">

                                                @if (!!$nurse->terms)
                                                    {{ truncateText($nurse->terms) }}
                                                @else
                                                    Your Terms !
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="profession"
                                        class="ss-s-jb-apl-on-inf-txt-ul profession_item {{ checkValue($model->profession) ? ( $matches['profession']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Profession</span>
                                            <h6>
                                                @if (checkValue($model->profession))
                                                    {{ $model->profession }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Profession</span>

                                            <p class="profile_info_text" data-target="profession"
                                                data-title="What kind of professional are you?" data-filter="Profession"
                                                data-name="profession" onclick="open_multiselect_modal(this)">

                                                @if (!!$nurse->profession)
                                                    {{ truncateText($nurse->profession) }}
                                                @else
                                                    What kind of professional are you?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="specialty"
                                        class="ss-s-jb-apl-on-inf-txt-ul preferred_specialty_item {{ checkValue($model->preferred_specialty) ? ( $matches['preferred_specialty']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Specialty</span>
                                            <h6>
                                                @if (checkValue($model->preferred_specialty))
                                                    {{ str_replace(',', ', ', $model->preferred_specialty) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        {{-- <li><p data-bs-toggle="modal" data-bs-target="#job-dtl-checklist">What's your specialty?</p></li> --}}
                                        <li>
                                            <span>Your Specialty</span>
                                            <p class="profile_info_text" data-target="specialty"
                                                data-title="What's your specialty?" data-filter="Speciality"
                                                data-name="specialty" onclick="open_multiselect_modal(this)">
                                                @if (!!$nurse->specialty)
                                                    {{ truncateText($nurse->specialty) }}
                                                @else
                                                    What's your specialty?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_actual_hourly_rate"
                                        class="ss-s-jb-apl-on-inf-txt-ul actual_hourly_rate_item {{ checkValue($model->actual_hourly_rate) ? ( $matches['actual_hourly_rate']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Est. Taxable Hourly Rate</span>
                                            <h6>
                                                @if (checkValue($model->actual_hourly_rate))
                                                    ${{ number_format($model->actual_hourly_rate) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Est. Taxable Hourly Rate</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="What rate is fair?" data-placeholder="What rate is fair?"
                                                data-name="worker_actual_hourly_rate" onclick="open_modal(this)">
                                                @if (!!$nurse->worker_actual_hourly_rate)
                                                    {{ $nurse->worker_actual_hourly_rate }}
                                                @else
                                                    What rate is fair?
                                                @endif
                                            </p>

                                        </li>
                                    </ul>

                                    <ul id="worker_weekly_rate" class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Est. Weekly Rate</span>
                                            <h6>
                                                @if (checkValue($model->hours_per_week) && checkValue($model->actual_hourly_rate))
                                                    ${{ number_format($model->weekly_pay) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul id="worker_hours_per_week"
                                        class="ss-s-jb-apl-on-inf-txt-ul hours_per_week_item {{ checkValue($model->hours_per_week) ? ( $matches['hours_per_week']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Hours/Week</span>
                                            <h6>
                                                @if (checkValue($model->hours_per_week))
                                                    {{ $model->hours_per_week }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Hours/Week</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="Ideal hours per week?"
                                                data-placeholder="Enter number Of Hours/Week"
                                                data-name="worker_hours_per_week" onclick="open_modal(this)">
                                                @if (!!$nurse->worker_hours_per_week)
                                                    {{ $nurse->worker_hours_per_week }}
                                                @else
                                                    Ideal hours per week?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="state"
                                        class="ss-s-jb-apl-on-inf-txt-ul job_state_item {{ checkValue($model->job_state) ? ( $matches['job_state']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Facility State</span>
                                            <h6>
                                                @if (checkValue($model->job_state))
                                                    {{ $model->job_state }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Facility State</span>
                                            <p class="profile_info_text" data-target="state"
                                                data-title="States you'd like to work?" data-filter="State"
                                                data-name="state" onclick="open_multiselect_modal(this)">
                                                @if (!!$nurse->state)
                                                    {{ truncateText($nurse->state) }}
                                                @else
                                                    States you'd like to work?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="city"
                                        class="ss-s-jb-apl-on-inf-txt-ul job_city_item {{ checkValue($model->job_city) ? ( $matches['job_city']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Facility City</span>
                                            <h6>
                                                @if (checkValue($model->job_city))
                                                    {{ $model->job_city }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Facility City</span>
                                            <p class="profile_info_text" data-target="input"
                                                data-placeholder="Cities you'd like to work?"
                                                data-title="Cities you'd like to work?" data-filter="City"
                                                data-name="city" onclick="open_modal(this)">
                                                @if (!!$nurse->city)
                                                    {{ $nurse->city }}
                                                @else
                                                    Cities you'll work in?
                                                @endif
                                            </p>

                                        </li>
                                    </ul>

                                    <ul id="resume" class="ss-s-jb-apl-on-inf-txt-ul resume_item ">
                                        <li>
                                            <span>Resume</span>
                                            <h6>{{ $model->is_resume ? 'Required' : 'Not Required' }}</h6>
                                        </li>
                                        <li>
                                            <p class="profile_info_text" data-target="resume_file"
                                                data-hidden_name="resume_cer" data-hidden_value="Yes"
                                                data-href="{{ route('info-required') }}"
                                                data-title="Upload your latest resume" data-name="resume"
                                                onclick="open_modal(this)">Upload your latest resume</p>
                                        </li>
                                    </ul>

                                </div>

                                {{-- Shift --}}
                                <button class="btn first-collapse mt-3" data-toggle="collapse"
                                    data-target="#shift">Shift</button>
                                <div id="shift" class="collapse">
                                    <ul class="ss-jb-apply-on-inf-hed mt-3">
                                        <li>
                                            <h5>Work Information</h5>
                                        </li>
                                        <li>
                                            <h5>Your Information</h5>
                                        </li>
                                    </ul>

                                    <ul id="worker_shift_time_of_day" class="ss-s-jb-apl-on-inf-txt-ul">

                                        <li>
                                            <span>Shift Time Of Day</span>
                                            <h6>
                                                @if (checkValue($model->preferred_shift_duration))
                                                    {{ $model->preferred_shift_duration }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Shift Time Of Day</span>
                                            <p class="profile_info_text" data-target="worker_shift_time_of_day"
                                                data-title="Fav shift?" data-filter="shift_time_of_day"
                                                data-name="worker_shift_time_of_day"
                                                onclick="open_multiselect_modal(this)">

                                                @if (!!$nurse->worker_shift_time_of_day)
                                                    {{ $nurse->worker_shift_time_of_day }}
                                                @else
                                                    Fav shift?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_guaranteed_hours"
                                        class="ss-s-jb-apl-on-inf-txt-ul guaranteed_hours_item {{ checkValue($model->guaranteed_hours) ? ( $matches['guaranteed_hours']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Guaranteed Hours</span>
                                            <h6>
                                                @if (checkValue($model->guaranteed_hours))
                                                    {{ number_format($model->guaranteed_hours) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Guaranteed Hours</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="Open to jobs with no guaranteed hours?"
                                                data-placeholder="Enter Guaranteed Hours"
                                                data-name="worker_guaranteed_hours" onclick="open_modal(this)">
                                                @if (!!$nurse->worker_guaranteed_hours)
                                                    {{ $nurse->worker_guaranteed_hours }}
                                                @else
                                                    Open to
                                                    jobs with no guaranteed hours?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_hours_shift"
                                        class="ss-s-jb-apl-on-inf-txt-ul hours_shift_item {{ checkValue($model->hours_shift) ? ( $matches['hours_shift']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Hours/Shift</span>
                                            <h6>
                                                @if (checkValue($model->hours_shift))
                                                    {{ $model->hours_shift }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Hours/Shift</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="Preferred hours per shift"
                                                data-placeholder="Enter number Of Hours/Shift"
                                                data-name="worker_hours_shift" onclick="open_modal(this)">
                                                @if (!!$nurse->worker_hours_shift)
                                                    {{ $nurse->worker_hours_shift }}
                                                @else
                                                    Preferred hours per shift
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_shifts_week"
                                        class="ss-s-jb-apl-on-inf-txt-ul weeks_shift_item {{ checkValue($model->weeks_shift) ? ( $matches['weeks_shift']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Shifts/Week</span>
                                            <h6>
                                                @if (checkValue($model->weeks_shift))
                                                    {{ number_format($model->weeks_shift) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Shifts/Week</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="Ideal shifts per week"
                                                data-placeholder="Enter ideal shift per week"
                                                data-name="worker_shifts_week" onclick="open_modal(this)">
                                                @if (!!$nurse->worker_shifts_week)
                                                    {{ $nurse->worker_shifts_week }}
                                                @else
                                                    Ideal shifts per week
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_weeks_assignment"
                                        class="ss-s-jb-apl-on-inf-txt-ul preferred_assignment_duration_item {{ checkValue($model->preferred_assignment_duration) ? ( $matches['preferred_assignment_duration']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Weeks/Assignment</span>
                                            <h6>
                                                @if (checkValue($model->preferred_assignment_duration))
                                                    {{ $model->preferred_assignment_duration }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Weeks/Assignment</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="How many weeks?"
                                                data-placeholder="Enter prefered weeks per assignment"
                                                data-name="worker_weeks_assignment" onclick="open_modal(this)">
                                                @if (!!$nurse->worker_weeks_assignment)
                                                    {{ $nurse->worker_weeks_assignment }}
                                                @else
                                                    How many weeks?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>


                                    @if (checkValue($model->as_soon_as) && $model->as_soon_as == '1')
                                        <ul id="worker_as_soon_as_possible"
                                            class="ss-s-jb-apl-on-inf-txt-ul as_soon_as_item {{ $matches['as_soon_as']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                            <li>
                                                <span>Start Date</span>
                                                <h6>As soon as possible</h6>
                                            </li>
                                            <li>
                                                <span>Your Start Date</span>
                                                <p class="profile_info_text" data-target="binary"
                                                    data-title="Can you start as soon as possible?"
                                                    data-name="worker_as_soon_as_possible" onclick="open_modal(this)">
                                                    @if (!!$nurse->worker_as_soon_as_possible)
                                                        {{ $nurse->worker_as_soon_as_possible }}
                                                    @else
                                                        Can you start as soon as possible?
                                                    @endif
                                                </p>
                                            </li>
                                        </ul>

                                        <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                            <li>
                                                <span>End Date</span>
                                            </li>
                                            <li>
                                                <h6>NA</h6>
                                            </li>
                                        </ul>
                                    @else
                                        <ul id="worker_start_date"
                                            class="ss-s-jb-apl-on-inf-txt-ul start_date_item {{ checkValue($model->start_date) ? ( $matches['start_date']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                            <li>
                                                <span>Start date</span>
                                                <h6>
                                                    @if (checkValue($model->start_date) && !!$model->start_date)
                                                        {{ $model->start_date }}
                                                    @else
                                                        <a style="cursor: pointer;"
                                                            onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                            Ask
                                                            Recruiter</a>
                                                    @endif
                                                </h6>
                                            </li>
                                            <li>
                                                <span>Your Start date</span>
                                                <p class="profile_info_text" data-target="date"
                                                    data-title="When can you start?" data-name="worker_start_date"
                                                    onclick="open_modal(this)">
                                                    @if (!!$nurse->worker_start_date)
                                                        {{ $nurse->worker_start_date }}
                                                    @else
                                                        When can you start?
                                                    @endif
                                                </p>
                                            </li>
                                        </ul>

                                        <ul id="worker_end_date" class="ss-s-jb-apl-on-inf-txt-ul">
                                            <li>
                                                <span>End Date</span>
                                                <h6>
                                                    @if (checkValue($model->end_date) && !!$model->end_date)
                                                        {{ $model->end_date }}
                                                    @else
                                                        <a style="cursor: pointer;"
                                                            onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                            Ask
                                                            Recruiter</a>
                                                    @endif
                                                </h6>
                                            </li>
                                        </ul>
                                    @endif

                                    <ul id="rto"
                                        class="ss-s-jb-apl-on-inf-txt-ul rto_item {{ checkValue($model->rto) ? ( $matches['rto']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>RTO</span>
                                            <h6>
                                                @if (checkValue($model->rto))
                                                    {{ $model->rto }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your RTO</span>
                                            <p class="profile_info_text" data-target="rto" data-title="Any time off?"
                                                data-placeholder="Any time off?" data-name="rto"
                                                onclick="open_modal(this)">
                                                @if (!!$nurse->rto)
                                                    {{ $nurse->rto }}
                                                @else
                                                    Any time off?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>
                                </div>

                                {{-- Pay --}}
                                <button class="btn first-collapse mt-3" data-toggle="collapse"
                                    data-target="#pay">Pay</button>
                                <div id="pay" class="collapse">
                                    <ul class="ss-jb-apply-on-inf-hed mt-3">
                                        <li>
                                            <h5>Work Information</h5>
                                        </li>
                                        <li>
                                            <h5>Your Information</h5>
                                        </li>
                                    </ul>

                                    <ul id="worker_overtime_rate"
                                        class="ss-s-jb-apl-on-inf-txt-ul overtime_item {{ checkValue($model->overtime) ? ( $matches['overtime']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Overtime</span>
                                            <h6>
                                                @if (checkValue($model->overtime))
                                                    {{ number_format($model->overtime) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>

                                            <span>Your Overtime</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="What rate is fair?" data-name="worker_overtime_rate"
                                                onclick="open_modal(this)">
                                                @if (!!$nurse->worker_overtime_rate)
                                                    {{ $nurse->worker_overtime_rate }}
                                                @else
                                                    What rate is fair?
                                                @endif
                                            </p>

                                        </li>
                                    </ul>

                                    <ul id="worker_on_call"
                                        class="ss-s-jb-apl-on-inf-txt-ul on_call_rate_item {{ checkValue($model->on_call_rate) ? ( $matches['on_call_rate']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>On Call Rate</span>
                                            <h6>
                                                @if (checkValue($model->on_call_rate))
                                                    ${{ number_format($model->on_call_rate) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your On Call Rate</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="What rate is fair?" data-placeholder="What rate is fair?"
                                                data-name="worker_on_call" onclick="open_modal(this)">
                                                @if (!!$nurse->worker_on_call)
                                                    {{ $nurse->worker_on_call }}
                                                @else
                                                    What rate is fair?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_call_back_check"
                                        class="ss-s-jb-apl-on-inf-txt-ul call_back_rate_item {{ checkValue($model->call_back_rate) ? ( $matches['call_back_rate']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Call Back Rate</span>
                                            <h6>
                                                @if (checkValue($model->call_back_rate))
                                                    ${{ number_format($model->call_back_rate) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Call Back Rate</span>
                                            <p class="profile_info_text" data-target="binary"
                                                data-title="Is this rate reasonable?" data-name="worker_call_back_check"
                                                onclick="open_modal(this)">
                                                @if (!!$nurse->worker_call_back_check)
                                                    {{ $nurse->worker_call_back_check }}
                                                @else
                                                    Is this rate reasonable?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_orientation_rate"
                                        class="ss-s-jb-apl-on-inf-txt-ul orientation_rate_item {{ checkValue($model->orientation_rate) ? ( $matches['orientation_rate']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Orientation Rate</span>
                                            <h6>
                                                @if (checkValue($model->orientation_rate))
                                                    ${{ number_format($model->orientation_rate) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>

                                        </li>
                                        <li>
                                            <span>Your Orientation Rate</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="What rate is fair?" data-placeholder="-"
                                                data-name="worker_orientation_rate" onclick="open_modal(this)">
                                                @if (!!$nurse->worker_orientation_rate)
                                                    {{ $nurse->worker_orientation_rate }}
                                                @else
                                                    What rate is fair?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_weekly_non_taxable_amount_check"
                                        class="ss-s-jb-apl-on-inf-txt-ul weekly_non_taxable_amount_item {{ checkValue($model->weekly_non_taxable_amount) ? ( $matches['weekly_non_taxable_amount']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Est. Weekly Non-Taxable Amount</span>
                                            <h6>
                                                @if (checkValue($model->weekly_non_taxable_amount))
                                                    ${{ number_format($model->weekly_non_taxable_amount) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Est. Weekly Non-Taxable Amount</span>
                                            <p class="profile_info_text" data-target="binary"
                                                data-title="Are you going to duplicate expenses?"
                                                data-placeholder="Weekly non-taxable amount"
                                                data-name="worker_weekly_non_taxable_amount_check"
                                                onclick="open_modal(this)">
                                                @if (!!$nurse->worker_weekly_non_taxable_amount_check)
                                                    {{ $nurse->worker_weekly_non_taxable_amount_check }}
                                                @else
                                                    Are you going to duplicate expenses?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_feels_like_per_hour_check"
                                        class="ss-s-jb-apl-on-inf-txt-ul feels_like_per_hour_item {{ checkValue($model->feels_like_per_hour) ? ( $matches['feels_like_per_hour']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Feels Like $/Hr</span>
                                            <h6>
                                                @if (checkValue($model->feels_like_per_hour))
                                                    ${{ number_format($model->feels_like_per_hour) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>

                                        </li>
                                        <li>
                                            <span>Your Feels Like $/Hr</span>
                                            <p class="profile_info_text" data-target="binary"
                                                data-title="Does this seem fair based on the market?"
                                                data-placeholder="Does this seem fair based on the market?"
                                                data-name="worker_feels_like_per_hour_check" onclick="open_modal(this)">
                                                @if (!!$nurse->worker_feels_like_per_hour_check)
                                                    {{ $nurse->worker_feels_like_per_hour_check }}
                                                @else
                                                    Does this seem fair based on the market?
                                                @endif
                                            </p>

                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Est. Goodwork Weekly Amount</span>
                                            <h6>
                                                @if (checkValue($model->goodwork_weekly_amount))
                                                    ${{ number_format($model->goodwork_weekly_amount) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>

                                        </li>
                                        <li>
                                            <h6> You have 5 days left before your rate drops form 5% to 2%</h6>
                                            {{-- <p data-target="input" data-title="You have 5 days left before your rate drops form 5% to 2%" data-placeholder="Goodwork Weekly Amount" data-name="worker_goodwork_weekly_amount" onclick="open_modal(this)">You have 5 days left before your rate drops form 5% to 2% </p> --}}
                                        </li>
                                    </ul>

                                    <ul id="worker_referral_bonus"
                                        class="ss-s-jb-apl-on-inf-txt-ul referral_bonus_item {{ checkValue($model->referral_bonus) ? ( $matches['referral_bonus']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Referral Bonus</span>
                                            <h6>
                                                @if (checkValue($model->referral_bonus))
                                                    {{ number_format($model->referral_bonus) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>

                                        </li>
                                        <li>
                                            <span>Your Referral Bonus</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="# of people you have referred?"
                                                data-placeholder="# of people you have referred?"
                                                data-name="worker_referral_bonus" onclick="open_modal(this)">
                                                @if (!!$nurse->worker_referral_bonus)
                                                    {{ $nurse->worker_referral_bonus }}
                                                @else
                                                    # of people you have referred?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_sign_on_bonus"
                                        class="ss-s-jb-apl-on-inf-txt-ul sign_on_bonus_item {{ checkValue($model->sign_on_bonus) ? ( $matches['sign_on_bonus']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Sign-On Bonus</span>
                                            <h6>
                                                @if (checkValue($model->sign_on_bonus))
                                                    ${{ number_format($model->sign_on_bonus) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Sign-On Bonus</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="What kind of bonus do you expect?"
                                                data-placeholder="What kind of bonus do you expect?"
                                                data-name="worker_sign_on_bonus" onclick="open_modal(this)">

                                                @if (!!$nurse->worker_sign_on_bonus)
                                                    {{ $nurse->worker_sign_on_bonus }}
                                                @else
                                                    What kind of bonus do you expect?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_extension_bonus"
                                        class="ss-s-jb-apl-on-inf-txt-ul extension_bonus_item {{ checkValue($model->extension_bonus) ? ( $matches['extension_bonus']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Extension Bonus</span>
                                            <h6>
                                                @if (checkValue($model->extension_bonus))
                                                    ${{ number_format($model->extension_bonus) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>

                                        </li>
                                        <li>
                                            <span>Your Extension Bonus</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="What are you comparing this to?"
                                                data-placeholder="What are you comparing this to?"
                                                data-name="worker_extension_bonus" onclick="open_modal(this)">
                                                @if (!!$nurse->worker_extension_bonus)
                                                    {{ $nurse->worker_extension_bonus }}
                                                @else
                                                    What are you comparing this to?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_completion_bonus"
                                        class="ss-s-jb-apl-on-inf-txt-ul completion_bonus_item {{ checkValue($model->completion_bonus) ? ( $matches['completion_bonus']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Completion Bonus</span>
                                            <h6>
                                                @if (checkValue($model->completion_bonus))
                                                    ${{ number_format($model->completion_bonus) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>

                                        </li>
                                        <li>
                                            <span>Your Completion Bonus</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="What kind of bonus do you deserve?"
                                                data-placeholder="What kind of bonus do you deserve?"
                                                data-name="worker_completion_bonus" onclick="open_modal(this)">
                                                @if (!!$nurse->worker_completion_bonus)
                                                    {{ $nurse->worker_completion_bonus }}
                                                @else
                                                    What kind of bonus do you deserve?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_other_bonus"
                                        class="ss-s-jb-apl-on-inf-txt-ul other_bonus_item {{ checkValue($model->other_bonus) ? ( $matches['other_bonus']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Other Bonus</span>
                                            <h6>
                                                @if (checkValue($model->other_bonus))
                                                    ${{ number_format($model->other_bonus) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Other Bonus</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="Other bonuses you want?"
                                                data-placeholder="Other bonuses you want?" data-name="worker_other_bonus"
                                                onclick="open_modal(this)">
                                                @if (!!$nurse->worker_other_bonus)
                                                    {{ $nurse->worker_other_bonus }}
                                                @else
                                                    Other bonuses you want?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_health_insurance"
                                        class="ss-s-jb-apl-on-inf-txt-ul health_insaurance_item {{ checkValue($model->health_insaurance) ? ( $matches['health_insaurance']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Health Insurance</span>

                                            <h6>
                                                @if (checkValue($model->health_insaurance))
                                                    {{ $model->health_insaurance == '1' ? 'Yes' : 'No' }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Health Insurance</span>
                                            <p class="profile_info_text" data-target="binary"
                                                data-title="How much do you want this?"
                                                data-name="worker_health_insurance"
                                                data-placeholder="How important is this to you?"
                                                onclick="open_modal(this)">
                                                @if (!!$nurse->worker_health_insurance)
                                                    {{ $nurse->worker_health_insurance }}
                                                @else
                                                    How much do you want this?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>


                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Pay Frequency</span>
                                            <h6>
                                                @if (checkValue($model->pay_frequency))
                                                    {{ $model->pay_frequency }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul id="worker_benefits"
                                        class="ss-s-jb-apl-on-inf-txt-ul benefits_item {{ checkValue($model->benefits) ? ( $matches['benefits']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Benefits</span>
                                            <h6>
                                                @if (checkValue($model->benefits))
                                                    {{ $model->benefits }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            {{-- binary : benefits -> tinyinteger 1->yes 2->maybe 0->no : Yes, Please | No, Thanks | preferable  --}}
                                            <span>Your Benefits</span>
                                            <p class="profile_info_text" data-target="benefits"
                                                data-title="Do you want benefits?"
                                                data-placeholder="Do you want benefits ?" data-name="worker_benefits"
                                                onclick="open_modal(this)">
                                                @if (!!$nurse->worker_benefits)
                                                    {{ $nurse->worker_benefits }}
                                                @else
                                                    Do you want benefits ?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_dental"
                                        class="ss-s-jb-apl-on-inf-txt-ul dental_item {{ checkValue($model->dental) ? ( $matches['dental']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Dental</span>
                                            <h6>
                                                @if (checkValue($model->dental))
                                                    {{ $model->dental == '1' ? 'Yes' : 'No' }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Dental</span>
                                            <p class="profile_info_text" data-target="binary"
                                                data-title="How important is this to you?" data-placeholder=""
                                                data-name="worker_dental" onclick="open_modal(this)">
                                                @if (!!$nurse->worker_dental)
                                                    {{ $nurse->worker_dental }}
                                                @else
                                                    How much do you want this
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_vision"
                                        class="ss-s-jb-apl-on-inf-txt-ul vision_item {{ checkValue($model->dental) ? ( $matches['vision']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Vision</span>
                                            <h6>
                                                @if (checkValue($model->vision))
                                                    {{ $model->vision == '1' ? 'Yes' : 'No' }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>

                                            <span>Your Vision</span>
                                            <p class="profile_info_text" data-target="binary"
                                                data-title="How important is this to you?"
                                                data-placeholder="How important is this to you?" data-name="worker_vision"
                                                onclick="open_modal(this)">
                                                @if (!!$nurse->worker_vision)
                                                    {{ $nurse->worker_vision }}
                                                @else
                                                    How much do you want this?
                                                @endif
                                            </p>

                                        </li>
                                    </ul>

                                    <ul id="worker_four_zero_one_k"
                                        class="ss-s-jb-apl-on-inf-txt-ul four_zero_one_k_item {{ checkValue($model->four_zero_one_k) ? ( $matches['four_zero_one_k']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>401K</span>

                                            <h6>
                                                @if (checkValue($model->four_zero_one_k))
                                                    {{ $model->four_zero_one_k == '1' ? 'Yes' : 'No' }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>

                                        </li>
                                        <li>
                                            <span>Your 401K</span>
                                            <p class="profile_info_text" data-target="binary"
                                                data-placeholder="How much do you want this?"
                                                data-title="How much do you want this?" data-name="worker_four_zero_one_k"
                                                onclick="open_modal(this)">
                                                @if (!!$nurse->worker_four_zero_one_k)
                                                    {{ $nurse->worker_four_zero_one_k }}
                                                @else
                                                    How much do you want this?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                </div>

                                {{-- Location --}}
                                <button class="btn first-collapse mt-3" data-toggle="collapse"
                                    data-target="#location">Location</button>
                                <div id="location" class="collapse">
                                    <ul class="ss-jb-apply-on-inf-hed mt-3">
                                        <li>
                                            <h5>Work Information</h5>
                                        </li>
                                        <li>
                                            <h5>Your Information</h5>
                                        </li>
                                    </ul>

                                    <ul id="clinical_setting_you_prefer"
                                        class="ss-s-jb-apl-on-inf-txt-ul clinical_setting_item {{ checkValue($model->clinical_setting) ? ( $matches['clinical_setting']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Clinical Setting</span>
                                            <h6>
                                                @if (checkValue($model->clinical_setting))
                                                    {{ $model->clinical_setting }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>

                                        </li>
                                        <li>
                                            <span>Your Clinical Setting</span>
                                            <p class="profile_info_text" data-target="dropdown"
                                                data-title="What setting do you prefer?" data-filter="ClinicalSetting"
                                                data-name="clinical_setting_you_prefer" onclick="open_modal(this)">
                                                @if (!!$nurse->clinical_setting_you_prefer)
                                                    {{ $nurse->clinical_setting_you_prefer }}
                                                @else
                                                    What setting do you prefer?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Address</span>
                                            <h6>
                                                @if (checkValue($model->preferred_work_location))
                                                    {{ $model->preferred_work_location }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Facility</span>
                                            <h6>
                                                @if (checkValue($model->facility_name))
                                                    {{ $model->facility_name }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul id="worker_facilitys_parent_system" class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Facility's Parent System</span>
                                            <h6>
                                                @if (checkValue($model->facilitys_parent_system))
                                                    {{ $model->facilitys_parent_system }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        {{-- <li>
                                                <span>Your Facility's Parent System</span>
                                                <p class="profile_info_text" data-target="input" data-title="What facilities would you like to work at?"
                                                    data-placeholder="Write Name Of Facilities"
                                                    data-name="worker_facilitys_parent_system" onclick="open_modal(this)">
                                                    @if (!!$nurse->worker_facilitys_parent_system)
                                                        {{ $nurse->worker_facilitys_parent_system }}
                                                    @else
                                                        What
                                                        facilities would you like to work at?
                                                    @endif
                                                </p>
                                            </li> --}}
                                    </ul>

                                    <ul id="facility_shift_cancelation_policy"
                                        class="ss-s-jb-apl-on-inf-txt-ul facility_shift_cancelation_policy_item {{ checkValue($model->facility_shift_cancelation_policy) ? ( $matches['facility_shift_cancelation_policy']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Facility Shift Cancellation Policy</span>
                                            <h6>
                                                @if (checkValue($model->facility_shift_cancelation_policy))
                                                    {{ $model->facility_shift_cancelation_policy }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Facility Shift Cancellation Policy</span>
                                            <p class="profile_info_text" data-target="input"
                                                data-title="What terms do you prefer?" data-filter="AssignmentDuration"
                                                data-name="facility_shift_cancelation_policy" onclick="open_modal(this)">
                                                @if (!!$nurse->facility_shift_cancelation_policy)
                                                    {{ $nurse->facility_shift_cancelation_policy }}
                                                @else
                                                    What terms do you prefer?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="contract_termination_policy"
                                        class="ss-s-jb-apl-on-inf-txt-ul contract_termination_policy_item {{ checkValue($model->contract_termination_policy) ? ( $matches['contract_termination_policy']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Contract Termination Policy</span>
                                            <h6>
                                                @if (checkValue($model->contract_termination_policy))
                                                    {{ $model->contract_termination_policy }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Contract Termination Policy</span>
                                            <p class="profile_info_text" data-target="dropdown"
                                                data-title="What terms do you prefer?"
                                                data-filter="ContractTerminationPolicy"
                                                data-name="contract_termination_policy" onclick="open_modal(this)">
                                                @if (!!$nurse->contract_termination_policy)
                                                    {{ $nurse->contract_termination_policy }}
                                                @else
                                                    What
                                                    terms do you prefer?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="distance_from_your_home"
                                        class="ss-s-jb-apl-on-inf-txt-ul traveler_distance_from_facility_item {{ checkValue($model->traveler_distance_from_facility) ? ( $matches['traveler_distance_from_facility']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Min Miles Must Live From Facility</span>
                                            <h6>
                                                @if (checkValue($model->traveler_distance_from_facility))
                                                    {{ $model->traveler_distance_from_facility }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Min Miles Must Live From Facility</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="Where does the IRS think you live?"
                                                data-placeholder="What's your google validated address ?"
                                                data-name="distance_from_your_home" onclick="open_modal(this)">
                                                @if (!!$nurse->distance_from_your_home)
                                                    {{ $nurse->distance_from_your_home }}
                                                @else
                                                    Where
                                                    does
                                                    the IRS think you live?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                </div>

                                {{-- Certs & Licences --}}
                                <button class="btn first-collapse mt-3" data-toggle="collapse"
                                    data-target="#certslicen">Certs & Licences</button>
                                <div id="certslicen" class="collapse">
                                    <ul class="ss-jb-apply-on-inf-hed mt-3">
                                        <li>
                                            <h5>Work Information</h5>
                                        </li>
                                        <li>
                                            <h5>Your Information</h5>
                                        </li>
                                    </ul>



                                    <ul id="nursing_license_state"
                                        class="ss-s-jb-apl-on-inf-txt-ul job_location_item {{ checkValue($model->job_location) ? ( $matches['job_location']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Professional Licensure</span>
                                            @if (checkValue($model->job_location))
                                                @php
                                                    $stateCode = explode(',', $model->job_location);
                                                @endphp

                                                @foreach ($stateCode as $v)
                                                    <h6>{{ $v }} Required</h6>
                                                @endforeach
                                            @else
                                                <h6><a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a></h6>
                                            @endif

                                        </li>
                                        <li>
                                            <span>Your Professional Licensure</span>
                                            <p class="profile_info_text nursing_license_state_file"
                                                data-target="nursing_license_state_file" onclick="open_modal(this)"
                                                data-title="Where are you licensed ?">
                                                @php
                                                    if (isset($docsList) && $docsList != null && count($docsList) > 0) {
                                                        $count = 0;
                                                        foreach ($docsList as $key => $doc) {
                                                            if ($doc->type == 'nursing_license_state') {
                                                                $count++;
                                                            }
                                                        }

                                                        if ($count > 0) {
                                                            echo $count . ' Files Uploaded';
                                                        } elseif (isset($stateCode)) {
                                                            foreach ($stateCode as $key => $v) {
                                                                echo "No $v ?";
                                                            }
                                                        } else {
                                                            echo "Where are you licensed?";
                                                        }
                                                    } elseif (isset($stateCode)) {
                                                        foreach ($stateCode as $key => $v) {
                                                            echo "No $v ?";
                                                        }
                                                    } else {
                                                        echo "Where are you licensed?";
                                                    }
                                                @endphp
                                            </p>

                                        </li>
                                    </ul>

                                    <ul id="certification"
                                        class="ss-s-jb-apl-on-inf-txt-ul certificate_item {{ checkValue($model->certificate) ? ( $matches['certificate']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Certifications</span>

                                            @if (checkValue($model->certificate))
                                                @php
                                                    $certificates = explode(',', $model->certificate);
                                                @endphp

                                                @foreach ($certificates as $v)
                                                    <h6>{{ $v }} Required</h6>
                                                @endforeach
                                            @else
                                                <h6><a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a></h6>
                                            @endif
                                        </li>
                                        <li>
                                            <span>Your Certifications</span>
                                            <p class="profile_info_text" class="certification_file"
                                                data-target="certification_file" onclick="open_modal(this)"
                                                data-title="No certification?">
                                                @php
                                                    if (isset($docsList) && $docsList != null && count($docsList) > 0) {
                                                        $count = 0;
                                                        foreach ($docsList as $key => $doc) {
                                                            if ($doc->type == 'certification') {
                                                                $count++;
                                                            }
                                                        }

                                                        if ($count > 0) {
                                                            echo $count . ' Files Uploaded';
                                                        } elseif (isset($certificates)) {
                                                            foreach ($certificates as $key => $v) {
                                                                echo "No $v ?";
                                                            }
                                                        } else {
                                                            echo "No certification ?";
                                                        } 
                                                    } elseif (isset($certificates)) {
                                                        foreach ($certificates as $key => $v) {
                                                            echo "No $v ?";
                                                        }
                                                    } else {
                                                        echo "No certification ?";
                                                    } 
                                                @endphp
                                            </p>
                                        </li>
                                    </ul>

                                </div>

                                {{-- Work Info --}}
                                <button class="btn first-collapse mt-3" data-toggle="collapse"
                                    data-target="#workInfo">Work Info</button>
                                <div id="workInfo" class="collapse">
                                    <ul class="ss-jb-apply-on-inf-hed mt-3">
                                        <li>
                                            <h5>Work Information</h5>
                                        </li>
                                        <li>
                                            <h5>Your Information</h5>
                                        </li>
                                    </ul>

                                    <ul id="worker_urgency"
                                        class="ss-s-jb-apl-on-inf-txt-ul urgency_item {{ checkValue($model->urgency) ? ( $matches['urgency']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Urgency</span>
                                            <h6>
                                                @if (checkValue($model->urgency))
                                                    {{ $model->urgency }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>

                                        </li>
                                        <li>
                                            <span>Your Urgency</span>
                                            <p class="profile_info_text" data-target="dropdown"
                                                data-title="How quickly you can be ready to submit?" data-filter="Urgency"
                                                data-placeholder="How quickly you can be ready to submit?"
                                                data-name="worker_urgency" onclick="open_modal(this)">

                                                @if (!!$nurse->worker_urgency)
                                                    {{ $nurse->worker_urgency }}
                                                @else
                                                    How quickly you
                                                    can
                                                    be ready to submit?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_experience"
                                        class="ss-s-jb-apl-on-inf-txt-ul preferred_experience_item {{ checkValue($model->preferred_experience) ? ( $matches['preferred_experience']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Experience</span>
                                            <h6>
                                                @if (checkValue($model->preferred_experience))
                                                    {{ $model->preferred_experience }} Years Required
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Experience</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-hidden_name="dl_cer" data-hidden_value="Yes"
                                                data-href="{{ route('info-required') }}"
                                                data-title="How long have you done this? <br/>(The specialty you’re applying for)"
                                                data-name="worker_experience" onclick="open_modal(this)">
                                                @if (!!$nurse->worker_experience)
                                                    {{ $nurse->worker_experience }}
                                                @else
                                                    How long have
                                                    you done this?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="references"
                                        class="ss-s-jb-apl-on-inf-txt-ul number_of_references_item {{ checkValue($model->number_of_references) ? ( $matches['number_of_references']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Number of references</span>
                                            <h6>
                                                @if (checkValue($model->number_of_references))
                                                    {{ $model->number_of_references }} references
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Number of references</span>
                                            <p class="profile_info_text" data-target="reference_file"
                                                onclick="open_modal(this)" data-title="Who are your References?">

                                                @php
                                                    if (isset($docsList) && $docsList != null && count($docsList) > 0) {
                                                        $count = 0;
                                                        foreach ($docsList as $key => $doc) {
                                                            if ($doc->type == 'references') {
                                                                $count++;
                                                            }
                                                        }

                                                        if ($count > 0) {
                                                            echo $count . ' Files Uploaded';
                                                        } else {
                                                            echo ' Who are your References?';
                                                        }
                                                    } else {
                                                        echo ' Who are your References?';
                                                    }
                                                @endphp
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="skills" class="ss-s-jb-apl-on-inf-txt-ul {{ checkValue($model->skills) ? ( $matches['skills']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Skills checklist</span>
                                            <h6>
                                                @if (checkValue($model->skills))
                                                    {{ str_replace(',', ', ', $model->skills) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Skills checklist</span>
                                            <p class="profile_info_text" data-target="skills_file"
                                                data-title="Upload your latest skills checklist"
                                                onclick="open_modal(this)">

                                                @php
                                                    if (isset($docsList) && $docsList != null && count($docsList) > 0) {
                                                        $count = 0;
                                                        foreach ($docsList as $key => $doc) {
                                                            if ($doc->type == 'skills') {
                                                                $count++;
                                                            }
                                                        }

                                                        if ($count > 0) {
                                                            echo $count . ' Files Uploaded';
                                                        } else {
                                                            echo ' Upload your latest skills checklist';
                                                        }
                                                    } else {
                                                        echo ' Upload your latest skills checklist';
                                                    }
                                                @endphp
                                            </p>

                                        </li>
                                    </ul>

                                    <ul id="worker_on_call_check"
                                        class="ss-s-jb-apl-on-inf-txt-ul on_call_item {{ checkValue($model->on_call) ? ( $matches['on_call']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>On call</span>
                                            <h6>
                                                @if (checkValue($model->on_call))
                                                    {{ $model->on_call == '1' ? 'Yes' : 'No' }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your On call</span>
                                            <p class="profile_info_text" data-target="binary"
                                                data-title="Will you do call?" data-name="worker_on_call_check"
                                                onclick="open_modal(this)">
                                                @if (!!$nurse->worker_on_call_check)
                                                    {{ $nurse->worker_on_call_check }}
                                                @else
                                                    Will you do
                                                    call?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="block_scheduling"
                                        class="ss-s-jb-apl-on-inf-txt-ul block_scheduling_item {{ checkValue($model->block_scheduling) ? ( $matches['block_scheduling']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Block Scheduling</span>
                                            <h6>
                                                @if (checkValue($model->block_scheduling))
                                                    {{ $model->block_scheduling == '1' ? 'Yes' : 'No' }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Block Scheduling</span>
                                            <p class="profile_info_text" data-target="binary"
                                                data-title="Do you want Block Scheduling?" data-name="block_scheduling"
                                                onclick="open_modal(this)">

                                                @if (!!$nurse->block_scheduling)
                                                    {{ $nurse->block_scheduling }}
                                                @else
                                                    Do you want
                                                    Block
                                                    Scheduling?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="float_requirement"
                                        class="ss-s-jb-apl-on-inf-txt-ul float_requirement_item {{ checkValue($model->float_requirement) ? ( $matches['float_requirement']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Float Requirements</span>
                                            <h6>
                                                @if (checkValue($model->float_requirement))
                                                    {{ $model->float_requirement == '1' ? 'Yes' : 'No' }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>

                                        </li>
                                        <li>
                                            <span>Your Float Requirements</span>
                                            <p class="profile_info_text" data-target="binary"
                                                data-title="Are you willing to float ?" data-name="float_requirement"
                                                onclick="open_modal(this)">
                                                @if (!!$nurse->float_requirement)
                                                    {{ $nurse->float_requirement }}
                                                @else
                                                    Are you willing to float ?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_patient_ratio"
                                        class="ss-s-jb-apl-on-inf-txt-ul Patient_ratio_item {{ checkValue($model->Patient_ratio) ? ( $matches['Patient_ratio']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>Patient Ratio</span>
                                            <h6>
                                                @if (checkValue($model->Patient_ratio))
                                                    {{ number_format($model->Patient_ratio) }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your Patient Ratio</span>
                                            <p class="profile_info_text" data-target="input_number"
                                                data-title="How many patients can you handle?"
                                                data-placeholder="How many patients can you handle?"
                                                data-name="worker_patient_ratio" onclick="open_modal(this)">
                                                @if (!!$nurse->worker_patient_ratio)
                                                    {{ $nurse->worker_patient_ratio }}
                                                @else
                                                    How many
                                                    patients can you handle?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                    <ul id="worker_emr"
                                        class="ss-s-jb-apl-on-inf-txt-ul emr_item {{ checkValue($model->Emr) ? ( $matches['emr']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                        <li>
                                            <span>EMR</span>
                                            <h6>
                                                @if (checkValue($model->Emr))
                                                    {{ $model->Emr }}
                                                @else
                                                    <a style="cursor: pointer;"
                                                        onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                        Ask
                                                        Recruiter</a>
                                                @endif
                                            </h6>
                                        </li>
                                        <li>
                                            <span>Your EMR</span>
                                            <p class="profile_info_text" data-target="worker_emr"
                                                data-title="What EMRs have you used?" data-filter="EMR"
                                                data-name="worker_emr" onclick="open_multiselect_modal(this)">
                                                @if (!!$nurse->worker_emr)
                                                    {{ truncateText($nurse->worker_emr) }}
                                                @else
                                                    What EMRs have you used?
                                                @endif
                                            </p>
                                        </li>
                                    </ul>

                                </div>

                                {{-- ID & Tax Info --}}
                                <button class="btn first-collapse mt-3" data-toggle="collapse" data-target="#idTax">ID &
                                    Tax Info</button>
                                <div id="idTax" class="collapse">
                                    <ul class="ss-jb-apply-on-inf-hed mt-3">
                                        <li>
                                            <h5>Work Information</h5>
                                        </li>
                                        <li>
                                            <h5>Your Information</h5>
                                        </li>
                                    </ul>

                                        <ul id="nurse_classification"
                                            class="ss-s-jb-apl-on-inf-txt-ul nurse_classification_item {{ checkValue($model->nurse_classification) ? ( $matches['nurse_classification']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                            <li>
                                                <span>Classification</span>
                                                <h6>
                                                    @if (checkValue($model->nurse_classification))
                                                        {{ $model->nurse_classification }}
                                                    @else
                                                        <a style="cursor: pointer;"
                                                            onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                            Ask
                                                            Recruiter</a>
                                                    @endif
                                                </h6>
                                            </li>
                                            <li>
                                                <span>Your Classification</span>
                                                <p class="profile_info_text" data-target="dropdown"
                                                    data-title="Classifications you'll work as ?"
                                                    data-filter="nurseClassification" data-name="nurse_classification"
                                                    onclick="open_modal(this)">
                                                    @if (!!$nurse->nurse_classification)
                                                        {{ $nurse->nurse_classification }}
                                                    @else
                                                        Classifications you'll work as ?
                                                    @endif
                                                </p>
                                            </li>
                                        </ul>

                                </div>

                                {{-- Medical info --}}
                                <button class="btn first-collapse mt-3" data-toggle="collapse"
                                    data-target="#medInf">Medical info</button>
                                <div id="medInf" class="collapse">
                                    <ul class="ss-jb-apply-on-inf-hed mt-3">
                                        <li>
                                            <h5>Work Information</h5>
                                        </li>
                                        <li>
                                            <h5>Your Information</h5>
                                        </li>
                                    </ul>

                                        <ul id="vaccination"
                                            class="ss-s-jb-apl-on-inf-txt-ul vaccinations_item {{ checkValue($model->vaccinations) ? ( $matches['vaccinations']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' ) : '' }}">
                                            <li>
                                                <span>Vaccinations & Immunizations</span>
                                                
                                                @if (checkValue($model->vaccinations))
                                                    @php
                                                        $vaccines = explode(',', $model->vaccinations);
                                                    @endphp

                                                    @foreach ($vaccines as $v)
                                                        <h6>{{ $v }} Required</h6>
                                                    @endforeach
                                                @else
                                                    <h6><a style="cursor: pointer;"
                                                            onclick="askRecruiter(this, 'type', '{{ $nurse->id }}', '{{ $model->recruiter_id }}','{{ $model->organization_id }}', '{{ $model->organization_name }}')">
                                                            Ask
                                                            Recruiter</a></h6>
                                                @endif

                                            </li>
                                            <li>
                                                <span>Your Vaccinations & Immunizations</span>
                                                <p class="profile_info_text" data-target="vaccination_file"
                                                    data-title="No vaccination ?" onclick="open_modal(this)">
                                                    @php
                                                        if (
                                                            isset($docsList) &&
                                                            $docsList != null &&
                                                            count($docsList) > 0
                                                        ) {
                                                            $count = 0;
                                                            foreach ($docsList as $key => $doc) {
                                                                if ($doc->type == 'vaccination') {
                                                                    $count++;
                                                                }
                                                            }

                                                            if ($count > 0) {
                                                                echo $count . ' Files Uploaded';
                                                            } elseif (isset($vaccines)) {
                                                                foreach ($vaccines as $key => $v) {
                                                                    echo "No $v ?";
                                                                }
                                                            } else {
                                                                echo "No vaccination ?";
                                                            }
                                                        } elseif (isset($vaccines)) {
                                                            foreach ($vaccines as $key => $v) {
                                                                echo "No $v ?";
                                                            }
                                                        } else {
                                                            echo "No vaccination ?";
                                                        }
                                                    @endphp
                                                </p>

                                            </li>
                                        </ul>
                                </div>

                                <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                    <li>
                                        <span style="font-size: larger">(*) : Required Fields</span>
                                    </li>
                                </ul>

                                <div class="ss-job-apl-on-app-btn">
                                    @if (!$model->checkIfApplied())
                                        <button id="applyButton" data-id="{{ $model->id }}"
                                            onclick="check_required_files_before_sent(this)">Apply
                                            Now</button>
                                        {{-- btn hidden -- content is loading --}}
                                        <button id="applyButtonLoading"
                                            class="btn btn-primary ss-job-apl-on-app-btn d-none" type="button">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            <span class="sr-only">Loading...</span>
                                        </button>
                                    @endif
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            {{-- Not required for now or pending DB management
            
                <ul id="worker_eligible_work_in_us"
                    class="ss-s-jb-apl-on-inf-txt-ul eligible_work_in_us_item {{ $matches['eligible_work_in_us']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                    <li>
                        <span>Eligible to work in the US</span>
                        <h6>Required</h6>
                    </li>
                    <li>
                        <p class="profile_info_text" data-target="binary" data-title="Does Congress allow you to work here?"
                            data-name="worker_eligible_work_in_us" onclick="open_modal(this)">Does
                            Congress allow you to work here?</p>
                    </li>
                </ul>

                @if (isset($model->scrub_color))
                    <ul id="worker_scrub_color"
                        class="ss-s-jb-apl-on-inf-txt-ul scrub_color_item {{ $matches['scrub_color']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                        <li>
                            <span>Scrub Color</span>
                            <h6>{{ $model->scrub_color }} </h6>
                        </li>
                        <li>
                            <p class="profile_info_text" data-target="input" data-title="Fav scrub brand?"
                                data-placeholder="Fav scrub brand?" data-name="worker_scrub_color"
                                onclick="open_modal(this)">Fav scrub brand?</p>
                        </li>
                    </ul>
                @endif

                @if (isset($model->Unit))
                    <ul id="worker_unit"
                        class="ss-s-jb-apl-on-inf-txt-ul Unit_item {{ $matches['Unit']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                        <li>
                            <span>Unit</span>
                            <h6>{{ $model->Unit }} </h6>
                        </li>
                        <li>
                            <p class="profile_info_text" data-target="input" data-title="Fav Unit?" data-placeholder="Fav Unit?"
                                data-name="worker_unit" onclick="open_modal(this)">Fav Unit?</p>
                        </li>
                    </ul>
                @endif

                @if (isset($model->organization_weekly_amount))
                    <ul id="worker_organization_weekly_amount"
                        class="ss-s-jb-apl-on-inf-txt-ul organization_weekly_amount_item {{ $matches['organization_weekly_amount']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                        <li>
                            <span>Est. Organization Weekly Amount</span>
                            <h6>${{ number_format($model->organization_weekly_amount) }}</h6>
                        </li>
                        <li>
                            <p class="profile_info_text" data-target="input_number" data-title="What range is reasonable?"
                                data-placeholder="What range is reasonable?"
                                data-name="worker_organization_weekly_amount" onclick="open_modal(this)">
                                What
                                range is reasonable?</p>
                        </li>
                    </ul>
                @endif

                <ul id="diploma"
                    class="ss-s-jb-apl-on-inf-txt-ul diploma_item {{ $matches['diploma']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">

                    <li>
                        <span>Diploma</span>
                        <h6>College Diploma</h6>
                    </li>
                    <li>
                        <p class="profile_info_text" data-target="diploma_file" data-hidden_name="diploma_cer"
                            data-hidden_value="Yes" data-href="{{ route('info-required') }}"
                            data-title="Did you really graduate? <br/>Upload a copy of your diploma" data-name="diploma"
                            onclick="open_modal(this)"> Did you really graduate ?</p>
                    </li>
                </ul>

                <ul id="driving_license"
                    class="ss-s-jb-apl-on-inf-txt-ul driving_license_item {{ $matches['driving_license']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                    <li>
                        <span>drivers license</span>
                        <h6>Required</h6>
                    </li>
                    <li>
                        <p class="profile_info_text" data-target="driving_license_file" data-hidden_name="dl_cer"
                            data-hidden_value="Yes" data-href="{{ route('info-required') }}"
                            data-title="Are you really allowed to drive?" data-name="driving_license"
                            onclick="open_modal(this)">Are you allowed to drive?</p>
                    </li>
                </ul>

                <ul id="worked_at_facility_before"
                    class="ss-s-jb-apl-on-inf-txt-ul worked_at_facility_before_item {{ $matches['worked_at_facility_before']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                    <li>
                        <span>Worked at Facility Before</span>
                        <h6>In the last 18 months</h6>
                    </li>
                    <li>
                        <p class="profile_info_text" data-target="binary" data-title="Are you sure you never worked here as staff?"
                            data-name="worked_at_facility_before" onclick="open_modal(this)">Are you sure
                            you never worked here as staff?</p>
                    </li>
                </ul>

                @if (isset($model->facility_id))
                    <ul id="facilities_you_like_to_work_at" class="ss-s-jb-apl-on-inf-txt-ul">
                        <li>
                            <span>Facility</span>
                            <h6>{{ $model->facility_id }} </h6>
                        </li>
                        <li>
                            <p class="profile_info_text" data-target="input" data-title="What Facilities have you worked at?"
                                data-placeholder="Write Name Of Facilities"
                                data-name="facilities_you_like_to_work_at" onclick="open_modal(this)">What
                                Facilities have you worked at?</p>
                        </li>
                    </ul>
                @endif

            --}}
            <!----------------job-details pop-up modals ---------------->
            @include('worker::dashboard.details.modals')
            @include('worker::dashboard.details.new_inputs_modals')


            <!-- Static Auto-Save Notification -->
            <div class="autoSaveBox">
                <strong>Auto-Save</strong>
                <div id="autoSaveMessage"></div>
                <div class="progress">
                    <div id="progressBar" class="progress-bar" style="width: 100%;"></div>
                </div>
                <u class="manualSave" onclick="manualSave()"><strong>Manually Save!</strong></u>
            </div>

        </div>

    </main>


@stop

{{-- page scripts --}}
@include('worker::dashboard.details.scripts')

<style>
    /* Google Fonts - Poppins*/
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');


    .container-multiselect {
        position: relative;
        max-width: 320px;
        width: 100%;
        margin: 30px auto 30px;
    }

    .select-btn {
        display: flex;
        height: 50px;
        align-items: center;
        justify-content: space-between;
        padding: 0 16px;
        border-radius: 8px;
        cursor: pointer;
        background-color: #fff;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    }

    .select-btn .btn-text {
        font-size: 17px;
        font-weight: 400;
        color: #333;
    }

    .select-btn .arrow-dwn {
        display: flex;
        height: 21px;
        width: 21px;
        color: #fff;
        font-size: 14px;
        border-radius: 50%;
        background: #3d2c39;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .select-btn.open .arrow-dwn {
        transform: rotate(-180deg);
    }

    .list-items {
        position: relative;
        margin-top: 15px;
        border-radius: 8px;
        padding: 16px;
        background-color: #fff;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        display: none;
        max-height: 500px;
        scroll-behavior: auto;
        overflow: auto;

    }

    .select-btn.open~.list-items {
        display: block;
    }

    .list-items .item {
        display: flex;
        align-items: center;
        list-style: none;
        height: 50px;
        cursor: pointer;
        transition: 0.3s;
        padding: 0 15px;
        border-radius: 8px;
    }

    .list-items .item:hover {
        background-color: #e7edfe;
    }

    .item .item-text {
        font-size: 16px;
        font-weight: 400;
        color: #333;
    }

    .item .checkbox {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 16px;
        width: 16px;
        border-radius: 4px;
        margin-right: 12px;
        border: 1.5px solid #c0c0c0;
        transition: all 0.3s ease-in-out;
    }

    .item.checked .checkbox {
        background-color: #3d2c39;
        border-color: #3d2c39;
    }

    .checkbox .check-icon {
        color: #fff;
        font-size: 11px;
        transform: scale(0);
        transition: all 0.2s ease-in-out;
    }

    .item.checked .check-icon {
        transform: scale(1);
    }

    .container-multiselect .ss-form-group {
        margin-top: 30px !important;
    }

    .ss-job-dtl-pop-sv-btn {
        margin-top: 30px !important;
    }

    .upload_label {
        height: 100px;
    }

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
        background: #FFEEEF;
    }
</style>


<style>
    .autoSaveBox {
        position: fixed;
        bottom: 30px;
        right: 20px;
        background: rgba(0, 0, 0, 0.45);
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        font-size: 14px;
        z-index: 1000;
        min-width: 220px;
        text-align: center;
    }

    .progress {
        height: 8px;
        background: #444;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 5px;
    }

    .progress-bar {
        height: 100%;
        background: #42d611!important;
        transition: width 1s linear;
    }

    .manualSave {
        color: #1d4112!important;
        cursor: pointer;
        display: block;
        margin-top: 5px;
    }
    
    .manualSave:hover {
        font-size: 16px;
        transition: 0.3s;
    }

</style>
