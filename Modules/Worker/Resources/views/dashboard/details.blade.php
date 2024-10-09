@extends('worker::layouts.main')
@section('mytitle', 'My Profile')
@section('css')
@stop
@section('content')
    <!--Main layout-->
    <main style="padding-top: 130px" class="ss-main-body-sec">
        <div class="container">
            <!-------------------applay-on jobs--------->

            <div class="ss-apply-on-jb-mmn-dv">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Explore</h2>

                        <!------------->
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
                                            <li><a href="#"><svg style="vertical-align: sub;"
                                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                                                        <path
                                                            d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0" />
                                                        <path
                                                            d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z" />
                                                    </svg> {{ $model->specialty }}</a></li>
                                        </ul>
                                    </div>
                                    <p class="col-2 text-center" style="padding-right:20px;">
                                        <span>+{{ $model->getOfferCount() }} Applied</span></p>
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
                        <!----------------jobs applay view details--------------->

                        <div class="ss-job-apply-on-view-detls-mn-dv">
                            <div class="ss-job-apply-on-tx-bx-hed-dv">
                                <ul>
                                    <li>
                                        <p>Recruiter</p>
                                    </li>
                                    {{-- <li><img src="{{URL::asset('images/nurses/profile/'.$model->recruiter->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';"/>{{$model->recruiter->first_name}} {{$model->recruiter->last_name}}</li> --}}
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
                                        {{-- <p>{{$model->recruiter->first_name}} {{$model->recruiter->last_name}}</p> --}}
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
                                <p id="job_description">{{ $model->description }} </p>
                            </div>


                            <!-------Work Information------->
                            <div class="ss-jb-apl-oninfrm-mn-dv">
                                <ul class="ss-jb-apply-on-inf-hed">
                                    <li>
                                        <h5>Work Information</h5>
                                    </li>
                                    <li>
                                        <h5>Your Information</h5>
                                    </li>
                                </ul>
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
                                <ul id="diploma"
                                    class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['diploma']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                    <li>
                                        <span>Diploma (*)</span>
                                        <h6>College Diploma</h6>
                                    </li>
                                    <li>
                                        <p data-target="diploma_file" data-hidden_name="diploma_cer" data-hidden_value="Yes"
                                            data-href="{{ route('info-required') }}" data-title="Did you really graduate?"
                                            data-name="diploma" onclick="open_modal(this)">Did you really graduate?</p>
                                    </li>
                                </ul>

                                <ul id="driving_license"
                                    class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['driving_license']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                    <li>
                                        <span>drivers license (*)</span>
                                        <h6>Required</h6>
                                    </li>
                                    <li>
                                        <p data-target="driving_license_file" data-hidden_name="dl_cer"
                                            data-hidden_value="Yes" data-href="{{ route('info-required') }}"
                                            data-title="Are you really allowed to drive?" data-name="driving_license"
                                            onclick="open_modal(this)">Are you really allowed to drive?</p>
                                    </li>
                                </ul>

                                @if (isset($model->preferred_experience))
                                    <ul id="worker_experience"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['preferred_experience']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Experience</span>
                                            <h6>{{ $model->preferred_experience }} Years Required</h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-hidden_name="dl_cer"
                                                data-hidden_value="Yes" data-href="{{ route('info-required') }}"
                                                data-title="How long have you done this?" data-name="worker_experience"
                                                onclick="open_modal(this)">How long have you done this?</p>
                                        </li>
                                    </ul>
                                @endif

                                <ul id="worked_at_facility_before"
                                    class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['worked_at_facility_before']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                    <li>
                                        <span>Worked at Facility Before (*)</span>
                                        <h6>In the last 18 months</h6>
                                    </li>
                                    <li>
                                        <p data-target="binary" data-title="Are you sure you never worked here as staff?"
                                            data-name="worked_at_facility_before" onclick="open_modal(this)">Are you sure
                                            you never worked here as staff?</p>
                                    </li>
                                </ul>






                                @if (isset($model->profession))
                                    <ul id="profession"
                                        class="ss-s-jb-apl-on-inf-txt-ul  {{ $matches['profession']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Profession</span>
                                            <h6>{{ $model->profession }}</h6>
                                        </li>
                                        <li>
                                            <p data-target="dropdown" data-title="What kind of professional are you?"
                                                data-filter="Profession" data-name="profession"
                                                onclick="open_modal(this)">
                                                What kind of professional are you?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->preferred_specialty))
                                    <ul id="specialty"
                                        class="ss-s-jb-apl-on-inf-txt-ul  {{ $matches['preferred_specialty']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Specialty</span>
                                            <h6>{{ str_replace(',', ', ', $model->preferred_specialty) }}</h6>
                                        </li>
                                        {{-- <li><p data-bs-toggle="modal" data-bs-target="#job-dtl-checklist">What's your specialty?</p></li> --}}
                                        <li>
                                            <p data-target="dropdown" data-title="What's your specialty?"
                                                data-filter="Speciality" data-name="specialty"
                                                onclick="open_modal(this)">
                                                What's your specialty?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->job_location))
                                    <ul id="nursing_license_state"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['job_location']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Professional Licensure</span>
                                            <h6>{{ $model->job_location }}</h6>
                                        </li>

                                        <li>
                                            <p data-target="dropdown" data-title="Where are you licensed?"
                                                data-filter="StateCode" data-placeholder="Where are you licensed?"
                                                data-name="nursing_license_state" onclick="open_modal(this)">Where are you
                                                licensed?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->vaccinations))
                                    <ul id="vaccination"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['vaccinations']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            @php
                                                $vaccines = explode(',', $model->vaccinations);
                                            @endphp
                                            <span>Vaccinations & Immunizations</span>
                                            @foreach ($vaccines as $v)
                                                <h6>{{ $v }} Required</h6>
                                            @endforeach
                                        </li>
                                        <li>
                                            @foreach ($vaccines as $v)
                                                <p data-target="vaccination_file"
                                                    data-hidden_name="{{ strtolower($v) }}_vac" data-hidden_value="Yes"
                                                    data-hidden_type="{{ $v }}"
                                                    data-href="{{ route('worker.vaccination') }}"
                                                    data-title="No {{ $v }}?"
                                                    data-name="{{ strtolower($v) }}" onclick="open_modal(this)">No
                                                    {{ $v }}?</p>
                                            @endforeach

                                        </li>
                                    </ul>
                                @endif


                                @if (isset($model->number_of_references))
                                    <ul id="references"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['number_of_references']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Numnber of references</span>
                                            <h6>{{ $model->number_of_references }} references </h6>
                                        </li>
                                        <li>
                                            <p data-target="reference_file" onclick="open_modal(this)">Who are your
                                                References?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->certificate))
                                    <ul id="certification"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['certificate']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            @php
                                                $certificates = explode(',', $model->certificate);
                                            @endphp
                                            <span>Certifications</span>
                                            @foreach ($certificates as $v)
                                                <h6>{{ $v }} Required</h6>
                                            @endforeach
                                        </li>
                                        <li>
                                            <p></p>
                                            @foreach ($certificates as $v)
                                                <p data-target="certification_file"
                                                    data-hidden_name="{{ strtolower($v) }}_cer" data-hidden_value="Yes"
                                                    data-hidden_type="{{ $v }}"
                                                    data-href="{{ route('worker.certification') }}"
                                                    data-title="No {{ $v }}?"
                                                    data-name="{{ strtolower($v) }}" onclick="open_modal(this)">No
                                                    {{ $v }}?</p>
                                            @endforeach
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->skills))
                                    <ul id="skills" class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Skills checklist</span>
                                            <h6>{{ str_replace(',', ', ', $model->skills) }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="skills_file" data-title="Upload your latest skills checklist"
                                                onclick="open_modal(this)">Upload your latest skills checklist</p>

                                        </li>
                                    </ul>
                                @endif

                                <ul id="worker_eligible_work_in_us"
                                    class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['eligible_work_in_us']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                    <li>
                                        <span>Eligible to work in the US</span>
                                        <h6>Required</h6>
                                        {{-- <h6>Flu 2022 Preferred</h6> --}}
                                    </li>
                                    <li>
                                        <p data-target="binary" data-title="Does Congress allow you to work here?"
                                            data-name="worker_eligible_work_in_us" onclick="open_modal(this)">Does
                                            Congress allow you to work here?</p>
                                    </li>
                                </ul>

                                {{-- @if (isset($model->urgency))
                                    <ul id="worker_urgency"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['urgency']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Urgency</span>
                                            <h6>{{ $model->urgency }} </h6>

                                        </li>
                                        <li>
                                            <p data-target="dropdown" data-title="How quickly you can be ready to submit?"
                                                data-filter="Urgency"
                                                data-placeholder="How quickly you can be ready to submit?"
                                                data-name="worker_urgency" onclick="open_modal(this)">How quickly you can
                                                be ready to submit?</p>
                                        </li>
                                    </ul>
                                @endif --}}

                                @if (isset($model->block_scheduling))
                                    <ul id="block_scheduling"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['block_scheduling']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Block Scheduling</span>


                                            <h6>{{ $model->block_scheduling == '1' ? 'Yes' : 'No' }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="binary" data-title="Do you want Block Scheduling?"
                                                data-name="block_scheduling" onclick="open_modal(this)">Do you want Block
                                                Scheduling?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->float_requirement))
                                    <ul id="float_requirement"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['float_requirement']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Float Requirements</span>
                                            <h6>{{ $model->float_requirement == '1' ? 'Yes' : 'No' }} </h6>

                                        </li>
                                        <li>
                                            <p data-target="binary" data-title="Are you willing float to?"
                                                data-name="float_requirement" onclick="open_modal(this)">Are you willing
                                                float to?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->facility_shift_cancelation_policy))
                                    <ul id="facility_shift_cancelation_policy"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['facility_shift_cancelation_policy']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Facility Shift Cancellation Policy</span>
                                            <h6>{{ $model->facility_shift_cancelation_policy }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="dropdown" data-title="What terms do you prefer?"
                                                data-filter="AssignmentDuration"
                                                data-name="facility_shift_cancelation_policy" onclick="open_modal(this)">
                                                What terms do you prefer?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->contract_termination_policy))
                                    <ul id="contract_termination_policy"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['contract_termination_policy']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Contact Termination Policy</span>
                                            <h6>{{ $model->contract_termination_policy }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="dropdown" data-title="What terms do you prefer?"
                                                data-filter="ContractTerminationPolicy"
                                                data-name="contract_termination_policy" onclick="open_modal(this)">What
                                                terms do you prefer?</p>
                                        </li>
                                    </ul>
                                @endif


                                @if (isset($model->traveler_distance_from_facility))
                                    <ul id="distance_from_your_home"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['traveler_distance_from_facility']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Distance from your home</span>
                                            <h6>{{ $model->traveler_distance_from_facility }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="Where does the IRS think you live?"
                                                data-placeholder="What's your google validated address ?"
                                                data-name="distance_from_your_home" onclick="open_modal(this)">Where does
                                                the IRS think you live?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->facility_id))
                                    <ul id="facilities_you_like_to_work_at" class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Facility</span>
                                            <h6>{{ $model->facility_id }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="input" data-title="What Facilities have you worked at?"
                                                data-placeholder="Write Name Of Facilities"
                                                data-name="facilities_you_like_to_work_at" onclick="open_modal(this)">What
                                                Facilities have you worked at?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->facilitys_parent_system))
                                    <ul id="worker_facilitys_parent_system"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['facilitys_parent_system']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} ">
                                        <li>
                                            <span>Facility's Parent System</span>
                                            <h6>{{ $model->facilitys_parent_system }}</h6>
                                        </li>
                                        {{-- <li>
                                            <p data-target="input" data-title="What facilities would you like to work at?"
                                                data-placeholder="Write Name Of Facilities"
                                                data-name="worker_facilitys_parent_system" onclick="open_modal(this)">What
                                                facilities would you like to work at?</p>
                                        </li> --}}
                                    </ul>
                                @endif


                                @if (isset($model->clinical_setting))
                                    <ul id="clinical_setting_you_prefer"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['clinical_setting']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Clinical Setting</span>
                                            <h6>{{ $model->clinical_setting }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="dropdown" data-title="What setting do you prefer?"
                                                data-filter="ClinicalSetting" data-name="clinical_setting_you_prefer"
                                                onclick="open_modal(this)">What setting do you prefer?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->Patient_ratio))
                                    <ul id="worker_patient_ratio"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['Patient_ratio']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Patient Ratio</span>
                                            <h6>{{ number_format($model->Patient_ratio) }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="How many patients can you handle?"
                                                data-placeholder="How many patients can you handle?"
                                                data-name="worker_patient_ratio" onclick="open_modal(this)">How many
                                                patients can you handle?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->Emr))
                                    <ul id="worker_emr"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['emr']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>EMR</span>
                                            <h6>{{ $model->Emr }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="multi_select" data-title="What EMRs have you used?"
                                                data-filter="EMR" data-name="worker_emr" onclick="open_modal(this)">What
                                                EMRs have you used?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->Unit))
                                    <ul id="worker_unit"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['Unit']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Unit</span>
                                            <h6>{{ $model->Unit }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="input" data-title="Fav Unit?" data-placeholder="Fav Unit?"
                                                data-name="worker_unit" onclick="open_modal(this)">Fav Unit?</p>
                                        </li>
                                    </ul>
                                @endif


                                @if (isset($model->scrub_color))
                                    <ul id="worker_scrub_color"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['scrub_color']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Scrub Color</span>
                                            <h6>{{ $model->scrub_color }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="input" data-title="Fav scrub brand?"
                                                data-placeholder="Fav scrub brand?" data-name="worker_scrub_color"
                                                onclick="open_modal(this)">Fav scrub brand?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->job_city))
                                    <ul id="worker_facility_city"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['job_city']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Facility City</span>
                                            <h6>{{ $model->job_city }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="dropdown" data-title="Cities you'd like to work?"
                                                data-filter="City" data-name="worker_facility_city"
                                                onclick="open_modal(this)">Cities you'd like to work?</p>

                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->job_state))
                                    <ul id="worker_facility_state"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['job_state']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Facility State Code</span>
                                            <h6>{{ $model->job_state }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="dropdown" data-title="States you'd like to work?"
                                                data-filter="State" data-name="worker_facility_state"
                                                onclick="open_modal(this)">States you'd like to work?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->as_soon_as) && $model->as_soon_as == '1')
                                    <ul id="worker_as_soon_as"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['as_soon_as']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Start date</span>
                                            <h6>As soon as possible</h6>
                                        </li>
                                        <li>
                                            <p data-target="binary" data-title="Can you start as soon as possible?"
                                                data-name="worker_as_soon_as" onclick="open_modal(this)">Can you start as
                                                soon as possible?</p>
                                        </li>
                                    </ul>
                                @else 
                                    <ul id="worker_start_date"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['start_date']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Start date</span>
                                            <h6>{{ $model->start_date }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="date" data-title="When can you start?"
                                                data-name="worker_start_date" onclick="open_modal(this)">When can you
                                                start?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->rto))
                                    <ul id="rto"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['rto']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>RTO</span>
                                            <h6>{{ $model->rto }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="rto" data-title="Any time off?"
                                                data-placeholder="Any time off?" data-name="rto"
                                                onclick="open_modal(this)">Any time off?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->preferred_shift))
                                    <ul id="worker_shift_time_of_day" class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Shift Time Of Day</span>
                                            <h6>{{ $model->preferred_shift }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="dropdown" data-title="Fav shift?"
                                                data-filter="shift_time_of_day" data-name="worker_shift_time_of_day"
                                                onclick="open_modal(this)">Fav shift?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->hours_per_week))
                                    <ul id="worker_hours_per_week"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['hours_per_week']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Hours/Week</span>
                                            <h6>{{ $model->hours_per_week }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="Ideal hours per week?"
                                                data-placeholder="Enter number Of Hours/Week"
                                                data-name="worker_hours_per_week" onclick="open_modal(this)">Ideal hours
                                                per week?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->guaranteed_hours))
                                    <ul id="worker_guaranteed_hours"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['guaranteed_hours']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Guaranteed Hours</span>
                                            <h6>{{ number_format($model->guaranteed_hours) }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number"
                                                data-title="Open to jobs with no guaranteed hours?"
                                                data-placeholder="Enter Guaranteed Hours"
                                                data-name="worker_guaranteed_hours" onclick="open_modal(this)">Open to
                                                jobs with no guaranteed hours?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->hours_shift))
                                    <ul id="worker_hours_shift"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['hours_shift']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Hours/Shift</span>
                                            <h6>{{ $model->hours_shift }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="Preferred hours per shift"
                                                data-placeholder="Enter number Of Hours/Shift"
                                                data-name="worker_hours_shift" onclick="open_modal(this)">Preferred hours
                                                per shift</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->preferred_assignment_duration))
                                    <ul id="worker_weeks_assignment"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['preferred_assignment_duration']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Weeks/Assignment</span>
                                            <h6>{{ $model->preferred_assignment_duration }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="How many weeks?"
                                                data-placeholder="Enter prefered weeks per assignment"
                                                data-name="worker_weeks_assignment" onclick="open_modal(this)">How many
                                                weeks?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->weeks_shift))
                                    <ul id="worker_shifts_week"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['weeks_shift']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Shifts/Week</span>
                                            <h6>{{ number_format($model->weeks_shift) }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="Ideal shifts per week"
                                                data-placeholder="Enter ideal shift per week"
                                                data-name="worker_shifts_week" onclick="open_modal(this)">Ideal shifts per
                                                week</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->referral_bonus))
                                    <ul id="worker_referral_bonus"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['referral_bonus']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Referral Bonus</span>
                                            <h6>{{ number_format($model->referral_bonus) }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="# of people you have referred?"
                                                data-placeholder="# of people you have referred?"
                                                data-name="worker_referral_bonus" onclick="open_modal(this)"># of people
                                                you have referred?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->sign_on_bonus))
                                    <ul id="worker_sign_on_bonus"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['sign_on_bonus']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Sign-On Bonus</span>
                                            <h6>{{ number_format($model->sign_on_bonus) }} $ </h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="What kind of bonus do you expect?"
                                                data-placeholder="What kind of bonus do you expect?"
                                                data-name="worker_sign_on_bonus" onclick="open_modal(this)">What kind of
                                                bonus do you expect?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->completion_bonus))
                                    <ul id="worker_completion_bonus"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['completion_bonus']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Completion Bonus</span>
                                            <h6>{{ number_format($model->completion_bonus) }} $ </h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="What kind of bonus do you deserve?"
                                                data-placeholder="What kind of bonus do you deserve?"
                                                data-name="worker_completion_bonus" onclick="open_modal(this)">What kind
                                                of bonus do you deserve?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->extension_bonus))
                                    <ul id="worker_extension_bonus"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['extension_bonus']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Extension Bonus</span>
                                            <h6>{{ number_format($model->extension_bonus) }} $ </h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="What are you comparing this to?"
                                                data-placeholder="What are you comparing this to?"
                                                data-name="worker_extension_bonus" onclick="open_modal(this)">What are you
                                                comparing this to?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->other_bonus))
                                    <ul id="worker_other_bonus"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['other_bonus']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Other Bonus</span>
                                            <h6>{{ number_format($model->other_bonus) }} $ </h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="Other bonuses you want?"
                                                data-placeholder="Other bonuses you want?" data-name="worker_other_bonus"
                                                onclick="open_modal(this)">Other bonuses you want?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->four_zero_one_k))
                                    <ul id="worker_four_zero_one_k"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['four_zero_one_k']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>401K</span>

                                            <h6>{{ $model->four_zero_one_k == '1' ? 'Yes' : 'No' }} </h6>

                                        </li>
                                        <li>
                                            <p data-target="binary" data-placeholder="How much do you want this?"
                                                data-title="How much do you want this?" data-name="worker_four_zero_one_k"
                                                onclick="open_modal(this)">How much do you want this?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->health_insaurance))
                                    <ul id="worker_health_insurance"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['health_insaurance']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Health Insurance</span>

                                            <h6> {{ $model->health_insaurance == '1' ? 'Yes' : 'No' }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="binary" data-title="How much do you want this?"
                                                data-name="worker_health_insurance"
                                                data-placeholder="How much do you want this?" onclick="open_modal(this)">
                                                How much do you want this?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->dental))
                                    <ul id="worker_dental"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['dental']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Dental</span>
                                            <h6> {{ $model->dental == '1' ? 'Yes' : 'No' }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="binary" data-title="How much do you want this?"
                                                data-placeholder="" data-name="worker_dental" onclick="open_modal(this)">
                                                How much do you want this?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->vision))
                                    <ul id="worker_vision"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['vision']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Vision</span>
                                            <h6> {{ $model->vision == '1' ? 'Yes' : 'No' }} </h6>
                                        </li>
                                        <li>

                                            <p data-target="binary" data-title="How much do you want this?"
                                                data-placeholder="How much do you want this?" data-name="worker_vision"
                                                onclick="open_modal(this)">How much do you want this?</p>

                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->benefits))
                                    <ul id="worker_benefits"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['benefits']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Benefits</span>
                                            <h6>{{ $model->benefits }} </h6>
                                        </li>
                                        <li>
                                            {{-- binary : benefits -> tinyinteger 1->yes 2->maybe 0->no : Yes, Please | No, Thanks | preferable  --}}
                                            <p data-target="benefits" data-title="Do you want benefits?"
                                                data-placeholder="Do you want benefits ?" data-name="worker_benefits"
                                                onclick="open_modal(this)">How much do you want this?</p>
                                        </li>
                                    </ul>
                                @endif


                                @if (isset($model->actual_hourly_rate))
                                    <ul id="worker_actual_hourly_rate"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['actual_hourly_rate']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Est. Texable Hourly Rate</span>
                                            <h6>{{ number_format($model->actual_hourly_rate) }} $</h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="What rate is fair?"
                                                data-placeholder="What rate is fair?"
                                                data-name="worker_actual_hourly_rate" onclick="open_modal(this)">What rate
                                                is fair?</p>

                                        </li>
                                    </ul>
                                @endif

                                {{-- @if (isset($model->feels_like_per_hour))
                                    <ul id="worker_feels_like_per_hour_check"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['feels_like_per_hour']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Feels Like $/Hr</span>
                                            <h6>${{ $model->feels_like_per_hour }} </h6>

                                        </li>
                                        <li>
                                            <p data-target="binary" data-title="Does this seem fair based on the market?"
                                                data-placeholder="Does this seem fair based on the market?"
                                                data-name="worker_feels_like_per_hour_check" onclick="open_modal(this)">
                                                Does this seem fair based on the market?</p>

                                        </li>
                                    </ul>
                                @endif --}}

                                @if (isset($model->overtime))
                                    <ul id="worker_overtime_rate"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['overtime']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Overtime</span>
                                            <h6>{{ number_format($model->overtime) }} </h6>
                                        </li>
                                        <li>

                                            <p data-target="input_number" data-title="What rate is fair?"
                                                data-name="worker_overtime_rate" onclick="open_modal(this)">What rate is
                                                fair?</p>

                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->holiday))
                                    <ul id="worker_holiday"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['holiday']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Holiday</span>
                                            <h6>{{ $model->holiday }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="date" data-title="Any holiday you refuse to work?"
                                                data-name="worker_holiday" onclick="open_modal(this)">Any holiday
                                                you refuse to work?</p>

                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->on_call))
                                    <ul id="worker_on_call_check"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['on_call']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>On call</span>
                                            <h6>{{ $model->on_call == '1' ? 'Yes' : 'No' }} </h6>
                                        </li>
                                        <li>
                                            <p data-target="binary" data-title="Will you do call?"
                                                data-name="worker_on_call_check" onclick="open_modal(this)">Will you do
                                                call?</p>
                                        </li>
                                    </ul>
                                @endif
                                @if (isset($model->on_call_rate))
                                    <ul id="worker_on_call_rate"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['on_call_rate']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>On Call Rate</span>
                                            <h6>{{ number_format($model->on_call_rate) }} $</h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="What rate is fair?"
                                                data-placeholder="What rate is fair?" data-name="worker_on_call_rate"
                                                onclick="open_modal(this)">What rate is fair?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->call_back_rate))
                                    <ul id="worker_call_back_rate"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['call_back_rate']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>On Call Back Rate</span>
                                            <h6>{{ number_format($model->call_back_rate) }} $</h6>
                                        </li>
                                        <li>
                                            <p data-target="binary" data-title="Is this rate reasonable?"
                                                data-name="worker_call_back_rate" onclick="open_modal(this)">Is this rate
                                                reasonable?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->orientation_rate))
                                    <ul id="worker_orientation_rate"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['orientation_rate']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Orientation Rate</span>
                                            <h6>{{ number_format($model->orientation_rate) }} $ </h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="What rate is fair?"
                                                data-placeholder="-" data-name="worker_orientation_rate"
                                                onclick="open_modal(this)">What rate is fair?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->weekly_taxable_amount))
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Est. Weekly Taxable Amount</span>
                                            <h6>{{ number_format($model->weekly_taxable_amount) }} $ </h6>
                                        </li>
                                        {{-- <li>
            <p>?</p>
        </li> --}}
                                    </ul>
                                @endif

                                @if (isset($model->organization_weekly_amount))
                                    <ul id="worker_organization_weekly_amount"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['organization_weekly_amount']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Est. Organization Weekly Amount</span>
                                            <h6>{{ number_format($model->organization_weekly_amount) }} $</h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="What range is reasonable?"
                                                data-placeholder="What range is reasonable?"
                                                data-name="worker_organization_weekly_amount" onclick="open_modal(this)">
                                                What
                                                range is reasonable?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->weekly_non_taxable_amount))
                                    <ul id="worker_weekly_non_taxable_amount_check"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['weekly_non_taxable_amount']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Est. Weekly Non-Taxable Amount</span>
                                            <h6>{{ number_format($model->weekly_non_taxable_amount) }} $</h6>
                                        </li>
                                        <li>
                                            <p data-target="binary" data-title="Are you going to duplicate expenses?"
                                                data-placeholder="Weekly non-taxable amount"
                                                data-name="worker_weekly_non_taxable_amount_check"
                                                onclick="open_modal(this)">Are you going to duplicate expenses?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->weekly_taxable_amount))
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Est. Goodwork Weekly Amount</span>
                                            <h6>{{ number_format($model->weekly_taxable_amount) }} $</h6>
                                        </li>
                                        <li>
                                            <h6> You have 5 days left before your rate drops form 5% to 2%</h6>
                                            {{-- <p data-target="input" data-title="You have 5 days left before your rate drops form 5% to 2%" data-placeholder="Goodwork Weekly Amount" data-name="worker_goodwork_weekly_amount" onclick="open_modal(this)">You have 5 days left before your rate drops form 5% to 2% </p> --}}
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->total_organization_amount))
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Est. Total Organization Amount</span>
                                            <h6>{{ number_format($model->total_organization_amount) }} $</h6>
                                        </li>

                                    </ul>
                                @endif

                                @if (isset($model->total_goodwork_amount))
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Est. Total Goodwork Amount</span>
                                            <h6>{{ number_format($model->total_goodwork_amount) }} $</h6>
                                        </li>

                                    </ul>
                                @endif

                                @if (isset($model->total_contract_amount))
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Est. Total Contract Amount</span>
                                            <h6>{{ number_format($model->total_contract_amount) }} $</h6>
                                        </li>
                                        {{-- <li>
            <p>?</p>
        </li> --}}
                                    </ul>
                                @endif

                                <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                    <li>
                                        <span>(*) : Required Fields</span>
                                    </li>
                                </ul>

                                <div class="ss-job-apl-on-app-btn">
                                    <button id="applyButton" data-id="{{ $model->id }}"
                                        onclick="match_worker_with_jobs_update(dataToSend)">Save
                                    </button>
                                    @if (!$model->checkIfApplied())
                                        <button id="applyButton" data-id="{{ $model->id }}"
                                            onclick="check_required_files_before_sent(this)">Apply
                                            Now</button>
                                    @endif
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>




            <!----------------job-detls popup form----------->

            <!-----------Did you really graduate?------------>
            <!-- Certification  Modal -->

            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="certification_file_modal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-target="#certification_file_modal"
                                onclick="close_modal(this)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
                                <form name="certification" method="post" action="{{ route('worker-upload-files') }}"
                                    id="certification_file_modal_form" class="modal-form" enctype="multipart/form-data">
                                    @csrf
                                    <div class="ss-job-dtl-pop-frm-sml-dv"></div>
                                    <h4></h4>
                                    {{-- certification --}}
                                    <div class="container-multiselect" id="certificate">
                                        <div class="select-btn">
                                            <span class="btn-text">Select Certification</span>
                                            <span class="arrow-dwn">
                                                <i class="fa-solid fa-chevron-down"></i>
                                            </span>
                                        </div>
                                        <ul class="list-items">
                                            @if (isset($allKeywords['Certification']))
                                                @foreach ($allKeywords['Certification'] as $value)
                                                    <li class="item" value="{{ $value->title }}">
                                                        <span class="checkbox">
                                                            <i class="fa-solid fa-check check-icon"></i>
                                                        </span>
                                                        <span class="item-text">{{ $value->title }}</span>
                                                    </li>
                                                    <input name="certification" displayName="{{ $value->title }}"
                                                        type="file" id="upload-{{ $loop->index }}"
                                                        class="files-upload" style="display: none;" />
                                                @endforeach
                                            @endif
                                        </ul>
                                        <button class="ss-job-dtl-pop-sv-btn"
                                            onclick="collect_data(event,'file')">Save</button>
                                    </div>
                                    {{-- <button class="ss-job-dtl-pop-sv-btn" onclick="collect_data(event,'file')">Save</button> --}}
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!-- Vaccination  Modal -->

            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="vaccination_file_modal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-target="#vaccination_file_modal"
                                onclick="close_modal(this)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
                                <form name="vaccination" method="post" action="{{ route('worker-upload-files') }}"
                                    id="vaccination_file_modal_form" class="modal-form" enctype="multipart/form-data">
                                    @csrf
                                    <div class="ss-job-dtl-pop-frm-sml-dv"></div>
                                    <h4></h4>
                                    <div class="container-multiselect" id="vaccinations">
                                        <div class="select-btn">
                                            <span class="btn-text">Select Vaccinations</span>
                                            <span class="arrow-dwn">
                                                <i class="fa-solid fa-chevron-down"></i>
                                            </span>
                                        </div>
                                        <ul class="list-items">
                                            @if (isset($allKeywords['Vaccinations']))
                                                @foreach ($allKeywords['Vaccinations'] as $value)
                                                    <li class="item" value="{{ $value->title }}">
                                                        <span class="checkbox">
                                                            <i class="fa-solid fa-check check-icon"></i>
                                                        </span>
                                                        <span class="item-text">{{ $value->title }}</span>
                                                    </li>
                                                    <input name="vaccination" displayName="{{ $value->title }}"
                                                        type="file" class="files-upload" style="display: none;" />
                                                @endforeach
                                            @endif
                                        </ul>
                                        <button class="ss-job-dtl-pop-sv-btn"
                                            onclick="collect_data(event,'file')">Save</button>
                                    </div>
                                    {{-- <button class="ss-job-dtl-pop-sv-btn" onclick="collect_data(event,'file')">Save</button> --}}
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- References Modal --}}
            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="reference_file_modal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-target="#reference_file_modal"
                                onclick="close_modal(this)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
                                <form name="references" method="post" action="{{ route('worker-upload-files') }}"
                                    id="reference_file_modal_form" class="modal-form" enctype="multipart/form-data">
                                    @csrf
                                    {{-- reference --}}
                                    <div class="container-multiselect" id="references">
                                        <h4>Who are your References?</h4>
                                        <div class="ss-form-group">
                                            <label>Reference Name</label>
                                            <input type="text" name="name" placeholder="Name of Reference">
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="ss-form-group">
                                            <label>Phone Number</label>
                                            <input id="ref_phone_number" type="tel" name="phone"
                                                placeholder="Phone Number of Reference">
                                            <span class="help-block"></span>
                                        </div>

                                        <div class="ss-form-group">
                                            <label>Email</label>
                                            <input type="text" name="reference_email"
                                                placeholder="Email of Reference">
                                            <span class="help-block"></span>
                                        </div>

                                        <div class="ss-form-group">
                                            <label>Date Referred</label>
                                            <input type="date" name="date_referred">
                                            <span class="help-block"></span>
                                        </div>

                                        <div class="ss-form-group">
                                            <label>Min Title of Reference</label>
                                            <select name="min_title_of_reference">
                                                <option value="">Select a min title</option>
                                                @if (isset($allKeywords['MinTitleOfReference']))
                                                    @foreach ($allKeywords['MinTitleOfReference'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            <span class="help-block"></span>
                                        </div>
                                        <div class="ss-form-group">
                                            <label>Is this from your last assignment?</label>
                                            <select name="recency_of_reference">
                                                <option value="">Select a recency period</option>
                                                @if (isset($allKeywords['RecencyOfReference']))
                                                    @foreach ($allKeywords['RecencyOfReference'] as $value)
                                                        <option value="{{ $value->title }}">{{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="help-block"></span>
                                        </div>

                                        <div class="ss-form-group fileUploadInput"
                                            style="display: flex;
                                            justify-content: center;
                                            align-items: center;
                                            ">
                                            <label class="upload_label">Upload Image <span
                                                    cass="text-danger">(optional)</span></label>
                                            <input hidden type="file" name="image">
                                            <button type="button" onclick="open_file(this)">Choose File</button>
                                            <span class="help-block"></span>
                                        </div>
                                        <button class="ss-job-dtl-pop-sv-btn"
                                            onclick="collect_data(event,'file')">Save</button>
                                    </div>
                                    {{-- <button class="ss-job-dtl-pop-sv-btn" onclick="collect_data(event,'file')">Save</button> --}}
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Skills Modal --}}
            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="skills_file_modal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-target="#skills_file_modal"
                                onclick="close_modal(this)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
                                <form name="skills" method="post" action="{{ route('worker-upload-files') }}"
                                    id="skills_file_modal_form" class="modal-form" enctype="multipart/form-data">
                                    <div class="ss-job-dtl-pop-frm-sml-dv"></div>
                                    <h4></h4>
                                    @csrf
                                    {{-- skills --}}
                                    <div class="container-multiselect" id="skills_checklists">
                                        <div class="select-btn">
                                            <span class="btn-text">Select Skills</span>
                                            <span class="arrow-dwn">
                                                <i class="fa-solid fa-chevron-down"></i>
                                            </span>
                                        </div>
                                        <ul class="list-items">
                                            @if (isset($allKeywords['Speciality']))
                                                @foreach ($allKeywords['Speciality'] as $value)
                                                    <li class="item" value="{{ $value->title }}">
                                                        <span class="checkbox">
                                                            <i class="fa-solid fa-check check-icon"></i>
                                                        </span>
                                                        <span class="item-text">{{ $value->title }}</span>
                                                    </li>
                                                    <input name="skills" displayName="{{ $value->title }}"
                                                        type="file" id="upload-{{ $loop->index }}"
                                                        class="files-upload" style="display: none;" />
                                                @endforeach
                                            @endif
                                        </ul>
                                        <button onclick="collect_data(event,'file')"
                                            class="ss-job-dtl-pop-sv-btn">Save</button>
                                    </div>
                                    {{-- <button class="ss-job-dtl-pop-sv-btn" onclick="collect_data(event,'file')">Save</button> --}}
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            {{-- driving_licence Modal --}}
            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="driving_license_file_modal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-target="#driving_license_file_modal"
                                onclick="close_modal(this)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
                                <form name="driving_license" method="post" action="{{ route('worker-upload-files') }}"
                                    id="driving_license_file_modal_form" class="modal-form"
                                    enctype="multipart/form-data">
                                    <div class="ss-job-dtl-pop-frm-sml-dv"></div>
                                    <h4></h4>
                                    @csrf
                                    {{-- driving license --}}
                                    <div id="driving_license">
                                        <div class="container-multiselect" style="justify-content: center;display: flex;margin-bottom: 30px;">
                                            <div class="ss-form-group fileUploadInput"
                                                style="
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                    ">
                                                <input name="driving_license" displayName="Driving Licence"
                                                    type="file" class="files-upload" style="padding: 0px;">
                                                <div class="list-items">
                                                    <input hidden type="text" name="type" value="driving licence"
                                                        class="item">
                                                </div>
                                                <button type="button" onclick="open_file(this)">Choose File</button>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <button onclick="collect_data(event,'file')"
                                            class="ss-job-dtl-pop-sv-btn">Save</button>
                                    </div>
                                    {{-- <button class="ss-job-dtl-pop-sv-btn" onclick="collect_data(event,'file')">Save</button> --}}
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            {{-- driving_licence Modal --}}
            {{-- <div class="modal fade ss-jb-dtl-pops-mn-dv" id="ss_number_file_modal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-target="#ss_number_file_modal"
                                onclick="close_modal(this)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form">
                                <form method="post" action="{{ route('my-profile.store') }}"
                                    id="ss_number_file_modal_form" class="modal-form">
                                    <div class="ss-job-dtl-pop-frm-sml-dv">
                                        <div></div>
                                    </div>
                                    <h4></h4>
                                    <div class="ss-form-group">
                                        <input type="number" name="worker_ss_number" placeholder="">
                                        <span class="help-block"></span>
                                    </div>

                                    <button type="submit" class="ss-job-dtl-pop-sv-btn"
                                        onclick="collect_data(event,'input_number')">Save</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div> --}}
            {{-- diploma Modal --}}
            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="diploma_file_modal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-target="#diploma_file_modal"
                                onclick="close_modal(this)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
                                <form name="diploma" method="post" action="{{ route('worker-upload-files') }}"
                                    id="diploma_file_modal_form" class="modal-form" enctype="multipart/form-data">
                                    <div class="ss-job-dtl-pop-frm-sml-dv"></div>
                                    <h4></h4>
                                    @csrf
                                    {{-- driving license --}}
                                    <div id="diploma">

                                        <div class="container-multiselect" style="justify-content: center;display: flex;margin-bottom: 30px;">
                                            <div class="ss-form-group fileUploadInput"
                                                style="
                                                                display: flex !important;
                                                                justify-content: center !important;
                                                                align-items: center !important;
                                                            ">
                                                <input name="diploma" displayName="Diploma" type="file"
                                                    class="d-none files-upload">
                                                <div class="list-items">
                                                    <input hidden type="text" name="type" value="diploma"
                                                        class="item">
                                                </div>
                                                <button type="button" onclick="open_file(this)">Choose File</button>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <button class="ss-job-dtl-pop-sv-btn"
                                            onclick="collect_data(event,'file')">Save</button>
                                    </div>
                                    {{-- <button class="ss-job-dtl-pop-sv-btn" onclick="collect_data(event,'file')">Save</button> --}}
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>



            {{-- number model --}}

            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="input_number_modal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-target="#input_number_modal"
                                onclick="close_modal(this)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form">
                                <form method="post" action="{{ route('my-profile.store') }}"
                                    id="input_number_modal_form" class="modal-form">
                                    <div class="ss-job-dtl-pop-frm-sml-dv">
                                        <div></div>
                                    </div>
                                    <h4></h4>
                                    <div class="ss-form-group">
                                        <input type="number" name="" placeholder="">
                                        <span class="help-block"></span>
                                    </div>
                                    <button type="submit" class="ss-job-dtl-pop-sv-btn"
                                        onclick="collect_data(event,'input_number')">Save</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- end number model --}}



            <!-----------Are you sure you never worked here as staff?------------>
            <!-- Modal -->

            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="binary_modal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-target="#binary_modal"
                                onclick="close_modal(this)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form">
                                <form method="post" action="{{ route('my-profile.store') }}" id="binary_modal_form"
                                    class="modal-form">
                                    @csrf
                                    <div class="ss-job-dtl-pop-frm-sml-dv">
                                        <div></div>
                                    </div>
                                    <h4></h4>
                                    <ul class="ss-jb-dtlpop-chck">
                                        <li>
                                            <label>
                                                <input type="radio" name="radio" name="" value="1">
                                                <span>Yes</span>
                                            </label>
                                        </li>

                                        <li>
                                            <label>
                                                <input type="radio" name="radio" name="" value="0">
                                                <span>No</span>
                                            </label>
                                        </li>
                                    </ul>
                                    <button class="ss-job-dtl-pop-sv-btn"
                                        onclick="collect_data(event,'binary')">Save</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            {{-- beneftis modal --}}

            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="benefits_modal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-target="#benefits_modal"
                                onclick="close_modal(this)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form">
                                <form method="post" action="{{ route('my-profile.store') }}" id="benefits_modal_form"
                                    class="modal-form">
                                    @csrf
                                    <div class="ss-job-dtl-pop-frm-sml-dv">
                                        <div></div>
                                    </div>
                                    <h4></h4>
                                    <ul class="row d-flex justify-content-center ss-jb-dtlpop-chck">
                                        <li>
                                            <label>
                                                <input type="radio" name="worker_benefits" value="1">
                                                <span>Yes, Please</span>
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="radio" name="worker_benefits" value="2">
                                                <span>Preferable</span>
                                            </label>
                                        </li>

                                        <li>
                                            <label>
                                                <input type="radio" name="worker_benefits" value="0">
                                                <span>No, Thanks</span>
                                            </label>
                                        </li>
                                    </ul>
                                    <button class="ss-job-dtl-pop-sv-btn"
                                        onclick="collect_data(event,'binary')">Save</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- end beneftis modal --}}

            {{-- rto modal  --}}
            <!-----------Are you sure you never worked here as staff?------------>
            <!-- Modal -->

            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="rto_modal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-target="#rto_modal"
                                onclick="close_modal(this)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form">
                                <form method="post" action="{{ route('my-profile.store') }}" id="rto_modal_form"
                                    class="modal-form">
                                    @csrf
                                    <div class="ss-job-dtl-pop-frm-sml-dv">
                                        <div></div>
                                    </div>
                                    <h4></h4>
                                    <ul class="ss-jb-dtlpop-chck">
                                        <li>
                                            <label>
                                                <input type="radio" name="radio" name="" value="allowed">
                                                <span>Allowed</span>
                                            </label>
                                        </li>

                                        <li>
                                            <label>
                                                <input type="radio" name="radio" name="" value="not allowed">
                                                <span>Not Allowed</span>
                                            </label>
                                        </li>
                                    </ul>
                                    <button class="ss-job-dtl-pop-sv-btn"
                                        onclick="collect_data(event,'rto')">Save</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!-----------Yes we need your SS# to submit you------------>
            <!-- Modal -->

            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="input_modal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-target="#input_modal"
                                onclick="close_modal(this)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form">
                                <form method="post" action="{{ route('my-profile.store') }}" id="input_modal_form"
                                    class="modal-form">
                                    @csrf
                                    <div class="ss-job-dtl-pop-frm-sml-dv">
                                        <div></div>
                                    </div>
                                    <h4></h4>
                                    <div class="ss-form-group">
                                        <input type="text" name="" placeholder="">
                                        <span class="help-block"></span>
                                    </div>
                                    <button type="submit" class="ss-job-dtl-pop-sv-btn"
                                        onclick="collect_data(event,'input')">Save</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- date modal --}}

            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="date_modal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-target="#date_modal"
                                onclick="close_modal(this)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form">
                                <form method="post" action="{{ route('my-profile.store') }}" id="date_modal_form"
                                    class="modal-form">
                                    @csrf
                                    <div class="ss-job-dtl-pop-frm-sml-dv">
                                        <div></div>
                                    </div>
                                    <h4></h4>
                                    <div class="ss-form-group">
                                        {{-- date format "yyyy-mm-dd" --}}
                                        <input type="date" name="" placeholder="yyyy/mm/dd">
                                        <span class="help-block
                        "></span>
                                    </div>
                                    <button type="submit" class="ss-job-dtl-pop-sv-btn"
                                        onclick="collect_data(event,'date')">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-----------What's your specialty?------------>
            <!-- Modal -->

            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="job-dtl-specialty" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form">
                                <form>
                                    @csrf
                                    <div class="ss-job-dtl-pop-frm-sml-dv">
                                        <div></div>
                                    </div>
                                    <h4>Yes we need your SS# to submit you</h4>
                                    <div class="ss-form-group">
                                        <select name="cars"></select>
                                    </div>
                                    <div class="ss-jb-dtl-pop-check"><input type="checkbox" id="vehicle1"
                                            name="vehicle1" value="Bike">
                                        <label for="vehicle1"> This is a compact license</label>
                                    </div>
                                    <button class="ss-job-dtl-pop-sv-btn"
                                        onclick="collect_data(event,'input')">Save</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Dropdown modal --}}
            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="dropdown_modal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-target="#dropdown_modal"
                                onclick="close_modal(this)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form">
                                <form method="post" action="{{ route('my-profile.store') }}" id="dropdown_modal_form"
                                    class="modal-form">
                                    @csrf
                                    <h4></h4>
                                    <div class="ss-form-group">
                                        <select name=""></select>
                                    </div>
                                    {{-- <div class="ss-jb-dtl-pop-check"><input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                        <label for="vehicle1"> This is a compact license</label>
                    </div> --}}
                                    <button class="ss-job-dtl-pop-sv-btn"
                                        onclick="collect_data(event,'dropdown')">Save</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>



            <!-----------What's your specialty?------------>
            <!-- Modal -->

            {{-- <div class="modal fade ss-jb-dtl-pops-mn-dv" id="job-dtl-References" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                id="references-modal-form-btn"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form ss-job-dtl-pop-form-refrnc">
                                <form name="" method="post" action="{{ route('references.store') }}" id="ref-modal-form"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="ss-job-dtl-pop-frm-sml-dv">
                                        <div></div>
                                    </div>
                                    <h4>Who are your References?</h4>
                                    <div class="ss-form-group">
                                        <label>Reference Name</label>
                                        <input type="text" name="name[]" placeholder="Name of Reference">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="ss-form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="phone[]" placeholder="Phone Number of Reference">
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="ss-form-group">
                                        <label>Email</label>
                                        <input type="text" name="email[]" placeholder="Email of Reference">
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="ss-form-group">
                                        <label>Date Referred</label>
                                        <input type="date" name="date_referred[]">
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="ss-form-group">
                                        <label>Min Title of Reference</label>
                                        <input type="text" name="min_title_of_reference[]"
                                            placeholder="Min Title of Reference">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="ss-form-group">
                                        <label>Is this from your last assignment?</label>
                                        <select name="recency_of_reference[]">
                                            <option value="">Select</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="ss-form-group fileUploadInput">
                                        <label>Upload Image</label>
                                        <input type="file" name="image[]">
                                        <button type="button" onclick="open_file(this)">Choose File</button>
                                        <span class="help-block"></span>
                                    </div>

                                    <button class="ss-job-dtl-pop-sv-btn"
                                        onclick="collect_data(event,'file')">Save</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div> --}}


            <!-----------Upload your latest skills checklist------------>
            <!-- Modal -->

            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="job-dtl-checklist" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form ss-job-dtl-pop-form-refrnc">
                                <form>
                                    @csrf
                                    <div class="ss-job-dtl-pop-frm-sml-dv">
                                        <div></div>
                                    </div>
                                    <h4>Upload your latest skills checklist</h4>
                                    <div class="ss-form-group">
                                        <label>Skills Name</label>
                                        <input type="text" name="Name of Reference"
                                            placeholder="Phone Number of Reference">
                                    </div>


                                    <div class="ss-form-group">
                                        <label>Completion Date</label>
                                        <input type="date" id="birthday" name="birthday">
                                    </div>


                                    <div class="ss-form-group fileUploadInput">
                                        <label>Completion Date</label>
                                        <input type="file">
                                        <button>Choose File</button>
                                    </div>

                                    <div class="ss-add-more-se"><a href="#">Add More</a></div>
                                    <button class="ss-job-dtl-pop-sv-btn" onclick="collect_data(event)">Save</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>




            <!-----------Upload your latest skills checklist------------>
            <!-- Modal -->

            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="job-dtl-pop-cale" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form ss-job-dtl-pop-form-refrnc">
                                <form>
                                    @csrf
                                    <div class="ss-job-dtl-pop-frm-sml-dv">
                                        <div></div>
                                    </div>
                                    <h4>Upload your latest skills checklist</h4>

                                    <div class="container-calendar">
                                        <h3 id="monthAndYear"></h3>

                                        <div class="button-container-calendar">
                                            <button id="previous" onclick="previous()">&#8249;</button>
                                            <button id="next" onclick="next()">&#8250;</button>
                                        </div>

                                        <table class="table-calendar" id="calendar" data-lang="en">
                                            <thead id="thead-month"></thead>
                                            <tbody id="calendar-body"></tbody>
                                        </table>

                                        <div class="footer-container-calendar">
                                            <label for="month">Jump To: </label>
                                            <select id="month" onchange="jump()">
                                                <option value=0>Jan</option>
                                                <option value=1>Feb</option>
                                                <option value=2>Mar</option>
                                                <option value=3>Apr</option>
                                                <option value=4>May</option>
                                                <option value=5>Jun</option>
                                                <option value=6>Jul</option>
                                                <option value=7>Aug</option>
                                                <option value=8>Sep</option>
                                                <option value=9>Oct</option>
                                                <option value=10>Nov</option>
                                                <option value=11>Dec</option>
                                            </select>
                                            <select id="year" onchange="jump()"></select>
                                        </div>

                                    </div>
                                    <p>Add More</p>
                                    <button class="ss-job-dtl-pop-sv-btn">Save</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="multi_select_modal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="ss-pop-cls-vbtn">
                            <button type="button" class="btn-close" data-target="#multi_select_modal"
                                onclick="close_modal(this)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-job-dtl-pop-form">
                                <form method="post" action="{{ route('my-profile.store') }}"
                                    id="multi_select_modal_form" class="modal-form">
                                    @csrf
                                    <div class="ss-job-dtl-pop-frm-sml-dv">
                                        <div></div>
                                    </div>
                                    <h4></h4>
                                    <div class="ss-form-group ss-prsnl-frm-specialty">
                                        <label>EMR</label>
                                        <div class="ss-speilty-exprnc-add-list Emr-content">
                                        </div>
                                        <ul>
                                            <li class="row w-100 p-0 m-0">
                                                <div class="ps-0">
                                                    <select class="m-0" id="Emr">
                                                        <option value="" disabled selected hidden>Select an emr
                                                        </option>
                                                        @if (isset($allKeywords['EMR']))
                                                            @foreach ($allKeywords['EMR'] as $value)
                                                                <option value="{{ $value->id }}">
                                                                    {{ $value->title }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <input type="text" hidden id="EmrAllValues" name="worker_emr">
                                                </div>
                                            </li>
                                            <li>
                                                <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                                        onclick="addEmr('from_add')"><i class="fa fa-plus"
                                                            aria-hidden="true"></i></a></div>
                                            </li>
                                        </ul>
                                    </div>

                                    <button class="ss-job-dtl-pop-sv-btn"
                                        onclick="collect_data(event,'multi-select')">Save</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>



        </div>

    </main>




@stop

@section('js')
    <script type="text/javascript" src="{{ URL::asset('frontend/vendor/mask/jquery.mask.min.js') }}"></script>
    <script>
        var Emr = {};

        function addEmr(type) {
            let id;
            let idtitle;
            if (type == 'from_add') {
                id = $('#Emr');
                idtitle = "Emr";
            } else if (type == 'from_draft') {
                id = $('#EmrDraft');
                idtitle = "EmrDraft";
            } else {
                id = $('#EmrEdit');
                idtitle = "EmrEdit";
            }

            if (!id.val()) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Select the Emr please.',
                    time: 3
                });
            } else {
                if (!Emr.hasOwnProperty(id.val())) {
                    console.log(id.val());

                    var select = document.getElementById(idtitle);
                    var selectedOption = select.options[select.selectedIndex];
                    var optionText = selectedOption.textContent;

                    Emr[id.val()] = optionText;
                    EmrStr = Object.values(Emr).join(', ');
                    console.log('EmrStr', EmrStr);
                    id.val('');
                    list_Emr();
                }
            }
        }

        function list_Emr() {
            var str = '';
            console.log(Emr);

            for (const key in Emr) {
                console.log(Emr);

                let Emrname = "";
                @php
                    $allKeywordsJSON = json_encode($allKeywords['EMR']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                if (Emr.hasOwnProperty(key)) {
                    var data = JSON.parse(allspcldata);

                    data.forEach(function(item) {
                        if (key == item.id) {
                            Emrname = item.title;
                        }
                    });
                    const value = Emr[key];
                    str += '<ul>';
                    str += '<li>' + Emrname + '</li>';
                    str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-Emr" data-key="' + key +
                        '" onclick="remove_Emr(this, ' + key +
                        ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                    str += '</ul>';
                }
            }
            $('.Emr-content').html(str);
        }

        function remove_Emr(obj, key) {
            if (Emr.hasOwnProperty(key)) {
                delete Emr[key]; // Remove the Emr from the object
                EmrStr = Object.values(Emr).join(', '); // Update the hidden input value
                list_Emr(); // Refresh the list to reflect the deletion
                notie.alert({
                    type: 'success',
                    text: '<i class="fa fa-check"></i> Shift Time Of Day removed successfully.',
                    time: 3
                });
            } else {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-times"></i> Shift Time Of Day not found.',
                    time: 3
                });
            }
        }
    </script>
    <script>
        function daysUntilWorkStarts(dateString) {
            const workStartDate = new Date(dateString);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const differenceInMilliseconds = workStartDate - today;
            const differenceInDays = Math.ceil(differenceInMilliseconds / (1000 * 60 * 60 * 24));
            return `Work starts in ${differenceInDays} days`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.start-date').forEach(function(element) {
                const startDate = element.getAttribute('data-start-date');
                element.textContent = daysUntilWorkStarts(startDate);
            });
        });

        $('#ref_phone_number').mask('+1 (999) 999-9999');
        var worker_files_displayname_by_type = [];
        var worker_files = [];
        // certification
        var job_certification = "{!! $model->certificate !!}";
        var job_certification_displayname = job_certification.split(',').map(function(item) {
            return item.trim();
        });
        console.log('certifications : ', job_certification_displayname);

        // vaccination
        var job_vaccination = "{!! $model->vaccinations !!}";
        var job_vaccination_displayname = job_vaccination.split(',').map(function(item) {
            return item.trim();
        });
        console.log('vaccinations : ', job_vaccination_displayname);

        // references
        var number_of_references = "{!! $model->number_of_references !!}";

        // skills
        var job_skills = "{!! $model->skills !!}";
        var job_skills_displayname = job_skills.split(',').map(function(item) {
            return item.trim();
        });
        console.log('skills : ', job_skills_displayname);


        var worker_id = "{!! auth()->guard('frontend')->user()->nurse->id !!}";

        function get_all_files() {
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
                        console.log('Success:', resp);

                        let jsonResp = JSON.parse(resp);
                        files = jsonResp;
                        resolve(
                            files
                        ); // Resolve the promise with the display names
                    },
                    error: function(resp) {
                        console.log('Error:', resp);
                        reject(resp); // Reject the promise with the error response
                    }
                });
            });
        }
        // file types we have : ['professional_license', 'diploma', 'references', 'vaccination', 'certification'
        // ];
        // get_all_files('certification');

        var selectedFiles = [];
        var selectedValues = [];
        var selectedCertificates = [];
        var selectedVaccinations = [];
        document.addEventListener('DOMContentLoaded', function() {

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


            // end trim description control

            const items = document.querySelectorAll('.list-items .item');
            //store selected file values

            items.forEach((item, index) => {
                item.addEventListener('click', (event) => {
                    if (event.target.closest('.checkbox')) {
                        return;
                    }
                    const uploadInput = item.nextElementSibling;
                    if (uploadInput) {
                        // class 'checked' check
                        if (item.classList.contains('checked')) {
                            uploadInput.click();
                            uploadInput.addEventListener('change', function() {
                                if (this.files.length > 0) {
                                    // Handling file selection
                                    const file = this.files[0];
                                    selectedFiles.push(file.name);
                                    console.log(selectedFiles);
                                }
                            }, {
                                once: true //avoid multiple registrations
                            });
                        } else {
                            const index = selectedFiles.indexOf(uploadInput.files[0].name);
                            if (index > -1) {
                                selectedFiles.splice(index, 1);
                            }
                            console.log(selectedFiles);

                        }
                    }
                });
            });


        });

        const selectBtn = document.querySelectorAll(".select-btn"),

            items = document.querySelectorAll(".item");


        selectBtn.forEach(selectBtn => {
            selectBtn.addEventListener("click", () => {
                selectBtn.classList.toggle("open");
            });
        });

        items.forEach(item => {
            item.addEventListener("click", () => {
                const value = item.getAttribute('value');
                item.classList.toggle("checked");

                if (item.classList.contains("checked")) {
                    // add item
                    selectedValues.push(value);
                    console.log(selectedValues);
                } else {
                    // remove item
                    const index = selectedValues.indexOf(value);
                    if (index > -1) {
                        selectedValues.splice(index, 1);
                        console.log(selectedValues);
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

        async function sendMultipleFiles(type) {
            const fileInputs = document.querySelectorAll('.files-upload');
            console.log(fileInputs);
            const fileReadPromises = [];
            console.log(worker_id);
            var workerId = worker_id;

            if (type == 'references') {
                let referenceName = document.querySelector('input[name="name"]').value;
                let referencePhone = document.querySelector('input[name="phone"]').value;
                let referenceEmail = document.querySelector('input[name="reference_email"]').value;
                let referenceDate = document.querySelector('input[name="date_referred"]').value;
                let referenceMinTitle = document.querySelector('select[name="min_title_of_reference"]').value;
                let referenceRecency = document.querySelector('select[name="recency_of_reference"]').value;
                let referenceImage = document.querySelector('input[name="image"]').files[0];

                if (!referenceName || !referencePhone || !referenceEmail || !referenceDate || !referenceMinTitle ||
                    !referenceRecency) {
                    notie.alert({
                        type: 'error',
                        text: '<i class="fa fa-exclamation-triangle"></i> Please fill the required fields',
                        time: 3
                    });
                    return;
                }


                var referenceInfo = {
                    referenceName: referenceName,
                    phoneNumber: referencePhone,
                    email: referenceEmail,
                    dateReferred: referenceDate,
                    minTitle: referenceMinTitle,
                    isLastAssignment: referenceRecency == 1 ? true : false
                };
                console.log(referenceInfo);
                var readerPromise;
                if (referenceImage) {
                    readerPromise = new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            resolve({
                                name: referenceImage.name,
                                path: referenceImage.name,
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
                    readerPromise = {
                        name: 'null',
                        path: 'null',
                        type: type,
                        content: 'data:',
                        displayName: 'null',
                        ReferenceInformation: referenceInfo
                    };
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
                console.log(files);
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
                console.log(data); // Handle success
                notie.alert({
                    type: 'success',
                    text: '<i class="fa fa-check"></i>' + data.message,
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

        function readMoreDescreption() {
            var description = document.getElementById('job_description');


            if (description) {
                var descriptionText = description.innerText;
                if (descriptionText.length > 100) {
                    description.innerText = '{!! $model->description !!}';
                    var readMore = document.getElementById('read-more');
                    var readLess = document.getElementById('read-less');

                    if (readLess) {
                        console.log(readLess);
                        readMore.classList.add('d-none');
                        readLess.classList.remove('d-none');
                    } else {
                        console.log('readLess is not null')

                        var readLess = document.createElement('a');
                        readLess.id = 'read-less';
                        readLess.innerText = ' Read Less';
                        readLess.href = 'javascript:void(0)';
                        // add a function to onclick
                        readLess.onclick = readLessDescreption;
                        description.appendChild(readLess);
                    }
                }
            }
        }

        function readLessDescreption() {
            let description = document.getElementById('job_description');
            let readMore = document.getElementById('read-more');
            let readLess = document.getElementById('read-less');
            if (description) {
                let descriptionText = description.innerText;
                if (descriptionText.length > 100) {
                    description.innerText = descriptionText.substring(0, 100) + '...';
                    readLess.classList.add('d-none');
                    let readMore = document.createElement('a');
                    readMore.id = 'read-more';
                    readMore.innerText = ' Read More';
                    readMore.href = 'javascript:void(0)';
                    // add a function to onclick
                    readMore.onclick = readMoreDescreption;
                    description.appendChild(readMore);

                }
            }
        }
    </script>
    <script>
        var dataToSend = {};
        var EmrStr = '';

        function matchWithWorker(workerField, InsertedValue) {
            let match = false;
            var job = @json($model);

            switch (workerField) {
                case 'profession':
                    if (job['profession'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'specialty':
                    if (job['preferred_specialty'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'nursing_license_state':
                    let job_location = job['job_location'];
                    let states = job_location.split(", ").map(state => state.trim());
                    if (states.includes(InsertedValue)) {
                        match = true;
                    }
                    break;
                case 'worker_experience':
                    if (job['preferred_experience'] == InsertedValue) {
                        match = true;
                    }
                    break;
                    // case 'vaccinations':
                    //     // Complete logic for vaccinations
                    //     break;
                    // case 'number_of_references':
                    //    // Complete logic for min_title_of_reference
                    //     break;
                    // case 'min_title_of_reference':
                    //     // Complete logic for min_title_of_reference
                    //     break;
                    // case 'recency_of_reference':
                    //     // Complete logic for recency_of_reference
                    //     break;
                case 'certificate':
                    // Complete logic for certificate
                    break;
                    // case 'skills':
                    //      // Complete logic for skills
                    //     break;
                case 'worker_eligible_work_in_us':
                    if (InsertedValue == '1') {
                        match = true;
                    }
                    break;
                case 'worker_urgency':
                    if (job['urgency'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_facility_state':
                    if (job['job_state'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_facility_city':
                    if (job['job_city'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_weeks_assignment':
                    if (job['preferred_assignment_duration'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_start_date':
                    if (job['start_date'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_as_soon_as':
                    if (job['as_soon_as'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'distance_from_your_home':
                    if (job['traveler_distance_from_facility'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'clinical_setting_you_prefer':
                    if (job['clinical_setting'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_scrub_color':
                    if (job['scrub_color'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_emr':
                    if (job['Emr'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'rto':
                    if (job['rto'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_hours_per_week':
                    if (job['hours_per_week'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_guaranteed_hours':
                    if (job['guaranteed_hours'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_hours_shift':
                    if (job['hours_shift'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_shifts_week':
                    if (job['weeks_shift'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_referral_bonus':
                    if (job['referral_bonus'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_sign_on_bonus':
                    if (job['sign_on_bonus'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_completion_bonus':
                    if (job['completion_bonus'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_extension_bonus':
                    if (job['extension_bonus'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_other_bonus':
                    if (job['other_bonus'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_four_zero_one_k':
                    if (job['four_zero_one_k'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_actual_hourly_rate':
                    if (job['actual_hourly_rate'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_health_insurance':
                    if (job['health_insaurance'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_dental':
                    if (job['dental'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_vision':
                    if (job['vision'] == InsertedValue) {
                        match = true;
                    }
                    break;
                    // case 'worker_feels_like_per_hour_check':
                    //     if (InsertedValue == '1') {
                    //         match = true;
                    //     }
                    //     break;
                case 'worker_overtime_rate':
                    if (InsertedValue == job['overtime_rate']) {
                        match = true;
                    }
                    break;
                case 'worker_holiday':
                    if (job['holiday'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_on_call_check':
                    job['on_call'] = job['on_call'] == 'Yes' ? '1' : '0';
                    if (job['on_call'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_on_call_rate':
                    if (job['on_call_rate'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_call_back_check':
                    if (InsertedValue == '1') {
                        match = true;
                    }
                    break;
                case 'worker_call_back_rate':
                    if (job['call_back_rate'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_orientation_rate':
                    if (InsertedValue == job['orientation_rate']) {
                        match = true;
                    }
                    break;
                case 'worker_weekly_non_taxable_amount_check':
                    if (InsertedValue == '1') {
                        match = true;
                    }
                    break;
                case 'worker_organization_weekly_amount':
                    if (job['organization_weekly_amount'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_patient_ratio':
                    if (job['Patient_ratio'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_unit':
                    if (job['Unit'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_job_type':
                    if (job['job_type'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'MSP':
                    if (job['msp'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'VMS':
                    if (job['vms'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'block_scheduling':
                    if (job['block_scheduling'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'float_requirement':
                    if (job['float_requirement'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'facility_shift_cancelation_policy':
                    if (job['facility_shift_cancelation_policy'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_facilitys_parent_system':
                    if (job['facilitys_parent_system'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'contract_termination_policy':
                    if (job['contract_termination_policy'] == InsertedValue) {
                        match = true;
                    }
                    break;
                default:
                    match = undefined;
            }

            return match;
        }

        async function collect_data(event, type) {
            event.preventDefault();
            // targiting the input form and collectiong data

            let button = $(event.target);
            var form = button.closest('form');
            let formData = new FormData(form[0]);
            if (type !== 'file' && type !== 'multi-select') {
                let data = Object.fromEntries(formData.entries());
                console.log(data);
                dataToSend = {
                    ...dataToSend,
                    ...data
                };
            }


            var inputName = '';
            if (type == 'binary') {
                inputName = form.find('input[type="radio"]').attr('name');
            } else if (type == 'input') {
                inputName = form.find('input[type="text"]').attr('name');
            } else if (type == 'input_number') {
                inputName = form.find('input[type="number"]').attr('name');
            } else if (type == 'file') {
                inputName = form.attr('name');;
            } else if (type == 'rto') {
                inputName = form.find('input[type="radio"]').attr('name');
            } else if (type == 'dropdown') {
                inputName = form.find('select').attr('name');
            } else if (type == 'date') {
                inputName = form.find('input[type="date"]').attr('name');
            } else if (type = 'multi-select') {
                console.log('form', form);
                console.log('form data', formData);
                inputName = form.find('input[type="text"]').attr('id');
                if (inputName == 'EmrAllValues') {
                    inputName.value = EmrStr;
                    data = {
                        worker_emr: EmrStr
                    };
                    dataToSend = {
                        ...dataToSend,
                        ...data
                    };
                    inputName = form.find('input[type="text"]').attr('name');
                }

                console.log("data", dataToSend);
            }

            let job = @json($model);
            console.log('input name :', inputName);
            console.log('its value :', dataToSend[inputName]);


            // matching job / worker infromation

            if (matchWithWorker(inputName, dataToSend[inputName]) != undefined) {
                if (matchWithWorker(inputName, dataToSend[inputName])) {
                    let areaDiv = document.getElementById(inputName);
                    areaDiv.classList.remove('ss-s-jb-apl-bg-pink');
                    areaDiv.classList.add('ss-s-jb-apl-bg-blue');
                } else {
                    let areaDiv = document.getElementById(inputName);
                    areaDiv.classList.remove('ss-s-jb-apl-bg-blue');
                    areaDiv.classList.add('ss-s-jb-apl-bg-pink');
                }
            } else if (dataToSend[inputName] == '1') {
                console.log('here');
                let areaDiv = document.getElementById(inputName);
                areaDiv.classList.remove('ss-s-jb-apl-bg-pink');
                areaDiv.classList.add('ss-s-jb-apl-bg-blue');
                console.log(areaDiv);
            } else if (type == 'file') {
                try {
                    await sendMultipleFiles(inputName);
                } catch (error) {
                    console.error('Failed to send files:', error)
                }
                worker_files = await get_all_files();
                await checkFileMatch(inputName);
            }
            closeModal();
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
                console.log('job certification job name :', job_certification_displayname)
                console.log('worker_files_displayname_by_type', worker_files_displayname_by_type)
                console.log('is_job_certif_exist_in_worker_files', is_job_certif_exist_in_worker_files);
                if (is_job_certif_exist_in_worker_files) {
                    check = true;
                }
            } else if (inputName == 'vaccination') {
                const is_job_vaccin_exist_in_worker_files = job_vaccination_displayname.every(element =>
                    worker_files_displayname_by_type.includes(element));
                console.log('job vaccination job name :', job_vaccination_displayname)
                console.log('worker_files_displayname_by_type', worker_files_displayname_by_type)
                console.log('is_job_vaccin_exist_in_worker_files', is_job_vaccin_exist_in_worker_files);

                if (is_job_vaccin_exist_in_worker_files) {
                    check = true;
                }
            } else if (inputName == 'references') {
                if (number_of_references <= worker_files_displayname_by_type.length) {
                    check = true;
                }
            } else if (inputName == 'skills') {
                const is_job_skill_exist_in_worker_files = job_skills_displayname.every(element =>
                    worker_files_displayname_by_type.includes(element));
                // console.log('job skills job name :', job_skills_displayname)
                // console.log('worker_files_displayname_by_type', worker_files_displayname_by_type)
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

        function closeModal() {
            let buttons = document.querySelectorAll('.btn-close');
            buttons.forEach(button => {
                button.click();
            });
        }

        async function get_all_files_displayName_by_type(type) {
            let files = worker_files.filter(file => file.type == type);
            let displayNames = files.map(file => file.displayName);
            worker_files_displayname_by_type = displayNames;
            return displayNames;
        }


        async function check_required_files_before_sent(obj) {
            let diploma = [];
            let driving_license = [];

            let worked_bfore = dataToSend['worked_at_facility_before'];


            try {
                diploma = await get_all_files_displayName_by_type('diploma');

            } catch (error) {
                console.error('Failed to get files:', error);
            }
            try {
                driving_license = await get_all_files_displayName_by_type('driving_license');

            } catch (error) {
                console.error('Failed to get files:', error);
            }

            console.log(diploma);
            console.log(driving_license);
            console.log(worked_bfore);


            if (diploma.length == 0 || driving_license.length == 0 || worked_bfore == null) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-exclamation-triangle"></i> Please upload all required files',
                    time: 3
                });
                return false;
            } else {
                apply_on_jobs(obj, worked_bfore);
            }

        }

        $(document).ready(async function() {
            worker_files = await get_all_files();
            console.log('Worker files:', worker_files);
            checkFileMatch('certification');
            checkFileMatch('vaccination');
            checkFileMatch('references');
            checkFileMatch('skills');
            checkFileMatch('driving_license');
            checkFileMatch('diploma');
            let matches = @json($matches);
            console.log((matches));

            let usematches = @json($userMatches);
            console.log((usematches));
            $('input[name="phone[]"]').mask('(999) 999-9999');
        });

        function open_file(obj) {
            $(obj).parent().find('input[type="file"]').click();
        }

        function open_modal(obj) {
            let name, title, modal, form, target;

            name = $(obj).data('name');
            title = $(obj).data('title');
            target = $(obj).data('target');

            modal = '#' + target + '_modal';
            form = modal + '_form';
            $(form).find('h4').html(title);
            switch (target) {
                case 'input':
                    $(form).find('input[type="text"]').attr('name', name);
                    $(form).find('input[type="text"]').attr('placeholder', $(obj).data('placeholder'));
                    break;
                case 'input_number':
                    $(form).find('input[type="number"]').attr('name', name);
                    $(form).find('input[type="number"]').attr('placeholder', $(obj).data('placeholder'));
                    break;
                case 'binary':
                    $(form).find('input[type="radio"]').attr('name', name);
                    break;
                case 'rto':
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
        }

        function close_modal(obj) {
            let target = $(obj).data('target');
            $(target).modal('hide');
        }
    </script>
@stop


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
</style>
