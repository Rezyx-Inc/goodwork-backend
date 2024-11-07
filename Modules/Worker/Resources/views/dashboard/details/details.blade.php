@extends('worker::layouts.main')
@section('mytitle', 'My Profile')
@section('css')
@stop
@section('content')
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
                                    <li><img width="50px" src="{{URL::asset('images/nurses/profile/'.$model->recruiter->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';"/>{{$model->recruiter->first_name}} {{$model->recruiter->last_name}}</li>
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
                                        <h6>Work Name</h6>
                                        <p>{{ $model->job_name }}</p>
                                        {{-- <p>{{$model->recruiter->first_name}} {{$model->recruiter->last_name}}</p> --}}
                                    </li>
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


                                @if (isset($model->profession))
                                    <ul id="profession"
                                        class="ss-s-jb-apl-on-inf-txt-ul  {{ $matches['profession']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Profession</span>
                                            <h6>{{ $model->profession }}</h6>
                                        </li>
                                        <li>
                                            <p data-target="dropdown" data-title="What kind of professional are you?"
                                                data-filter="Profession" data-name="profession" onclick="open_modal(this)">
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
                                                data-filter="Speciality" data-name="specialty" onclick="open_modal(this)">
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

                                @if (isset($model->preferred_experience))
                                    <ul id="worker_experience"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['preferred_experience']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Experience</span>
                                            <h6>{{ $model->preferred_experience }} Years Required</h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-hidden_name="dl_cer" data-hidden_value="Yes"
                                                data-href="{{ route('info-required') }}"
                                                data-title="How long have you done this?" data-name="worker_experience"
                                                onclick="open_modal(this)">How long have you done this?</p>
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
                                            <p data-target="vaccination_file"
                                                    data-title="No vaccination ?" onclick="open_modal(this)">
                                                @foreach ($vaccines as $v)
                                                    No {{ $v }}?
                                                @endforeach
                                            </p>

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
                                            <p data-target="reference_file" onclick="open_modal(this)"
                                                data-title="Who are your References?">Who are your
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
                                            <p class="certification_file" data-target="certification_file" onclick="open_modal(this)"
                                            data-title="No certification?">
                                                @foreach ($certificates as $v)
                                                    No {{ $v }}?
                                                @endforeach
                                            </p>
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
                                            <p data-target="input" data-title="What terms do you prefer?"
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
                                            <span>Contract Termination Policy</span>
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

                                @if (isset($model->facilitys_parent_system))
                                    <ul id="worker_facilitys_parent_system" class="ss-s-jb-apl-on-inf-txt-ul">
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

                                <ul id="diploma"
                                    class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['diploma']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                    <li>
                                        <span>Diploma</span>
                                        <h6>College Diploma</h6>
                                    </li>
                                    <li>
                                        <p data-target="diploma_file" data-hidden_name="diploma_cer"
                                            data-hidden_value="Yes" data-href="{{ route('info-required') }}"
                                            data-title="Did you really graduate?" data-name="diploma"
                                            onclick="open_modal(this)">Did you really graduate?</p>
                                    </li>
                                </ul>

                                <ul id="driving_license"
                                    class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['driving_license']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                    <li>
                                        <span>drivers license</span>
                                        <h6>Required</h6>
                                    </li>
                                    <li>
                                        <p data-target="driving_license_file" data-hidden_name="dl_cer"
                                            data-hidden_value="Yes" data-href="{{ route('info-required') }}"
                                            data-title="Are you really allowed to drive?" data-name="driving_license"
                                            onclick="open_modal(this)">Are you really allowed to drive?</p>
                                    </li>
                                </ul>

                                <ul id="worked_at_facility_before"
                                    class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['worked_at_facility_before']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                    <li>
                                        <span>Worked at Facility Before</span>
                                        <h6>In the last 18 months</h6>
                                    </li>
                                    <li>
                                        <p data-target="binary" data-title="Are you sure you never worked here as staff?"
                                            data-name="worked_at_facility_before" onclick="open_modal(this)">Are you sure
                                            you never worked here as staff?</p>
                                    </li>
                                </ul>


























                                {{-- old order --}}


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
                                    <ul id="worker_as_soon_as_possible"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['as_soon_as']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Start date</span>
                                            <h6>As soon as possible</h6>
                                        </li>
                                        <li>
                                            <p data-target="binary" data-title="Can you start as soon as possible?"
                                                data-name="worker_as_soon_as_possible" onclick="open_modal(this)">Can you
                                                start as
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
                                            <h6>${{ number_format($model->sign_on_bonus) }} </h6>
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
                                            <h6>${{ number_format($model->completion_bonus) }} </h6>
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
                                            <h6>${{ number_format($model->extension_bonus) }} </h6>
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
                                            <h6>${{ number_format($model->other_bonus) }} </h6>
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
                                            <span>Est. Taxable Hourly Rate</span>
                                            <h6>${{ number_format($model->actual_hourly_rate) }}</h6>
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
                                    <ul id="worker_on_call"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['on_call_rate']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>On Call Rate</span>
                                            <h6>${{ number_format($model->on_call_rate) }}</h6>
                                        </li>
                                        <li>
                                            <p data-target="input_number" data-title="What rate is fair?"
                                                data-placeholder="What rate is fair?" data-name="worker_on_call"
                                                onclick="open_modal(this)">What rate is fair?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->call_back_rate))
                                    <ul id="worker_call_back_check"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['call_back_rate']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>On Call Back Rate</span>
                                            <h6>${{ number_format($model->call_back_rate) }}</h6>
                                        </li>
                                        <li>
                                            <p data-target="binary" data-title="Is this rate reasonable?"
                                                data-name="worker_call_back_check" onclick="open_modal(this)">Is this rate
                                                reasonable?</p>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->orientation_rate))
                                    <ul id="worker_orientation_rate"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['orientation_rate']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Orientation Rate</span>
                                            <h6>${{ number_format($model->orientation_rate) }} </h6>
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
                                            <h6>${{ number_format($model->weekly_taxable_amount) }} </h6>
                                        </li>
                                    </ul>
                                @endif

                                @if (isset($model->organization_weekly_amount))
                                    <ul id="worker_organization_weekly_amount"
                                        class="ss-s-jb-apl-on-inf-txt-ul {{ $matches['organization_weekly_amount']['match'] ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
                                        <li>
                                            <span>Est. Organization Weekly Amount</span>
                                            <h6>${{ number_format($model->organization_weekly_amount) }}</h6>
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
                                            <h6>${{ number_format($model->weekly_non_taxable_amount) }}</h6>
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
                                            <h6>${{ number_format($model->weekly_taxable_amount) }}</h6>
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
                                            <h6>${{ number_format($model->total_organization_amount) }}</h6>
                                        </li>

                                    </ul>
                                @endif

                                @if (isset($model->total_goodwork_amount))
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Est. Total Goodwork Amount</span>
                                            <h6>${{ number_format($model->total_goodwork_amount) }}</h6>
                                        </li>

                                    </ul>
                                @endif

                                @if (isset($model->total_contract_amount))
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Est. Total Contract Amount</span>
                                            <h6>${{ number_format($model->total_contract_amount) }}</h6>
                                        </li>
                                    </ul>
                                @endif

                                <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                    <li>
                                        <span style="font-size: larger">(*) : Required Fields</span>
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


            <!----------------job-details pop-up modals ---------------->
            @include('worker::dashboard.details.modals')


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
</style>
