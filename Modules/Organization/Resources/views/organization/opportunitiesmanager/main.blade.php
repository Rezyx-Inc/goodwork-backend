
@extends('organization::layouts.main')

@section('content')
    <main class="ss-main-body-sec">
        <div class="container">
            <div class="ss-opport-mngr-mn-div-sc">

                {{-- opportunities Manager Header --}}
                <div class="ss-opport-mngr-hedr">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4>Opportunities Manager</h4>
                        </div>
                        <div class="col-lg-6">
                            <ul>
                                <li><button id="drafts" onclick="opportunitiesType('drafts')"
                                        class="ss-darfts-sec-draft-btn">Drafts</button></li>
                                <li><button id="published" onclick="opportunitiesType('published')"
                                        class="ss-darfts-sec-publsh-btn">Published</button></li>
                                <li><button id="onhold" onclick="opportunitiesType('onhold')"
                                        class="ss-darfts-sec-publsh-btn">On Hold</button></li>

                                <li><a href="#" onclick="request_job_form_appear()" class="ss-opr-mngr-plus-sec"><i
                                            class="fas fa-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- end opportunities Manager Header --}}

                {{-- Creat work --}}

                <div class="ss-no-job-mn-dv" id="no-job-posted">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ss-nojob-dv-hed">
                                <h6>Start Creating Work Request</h6>
                                <a href="#" onclick="request_job_form_appear()">Create Work Request</a>
                            </div>
                        </div>
                    </div>
                </div>
                    {{-- create form --}}
                        @include('organization::organization.opportunitiesmanager.create_job')
                    {{-- end create job from --}}

                {{-- End Creat work --}}
                
                <div class="ss-acount-profile d-none" id="published-job-details">
                    <div class="row">

                        <!-- DRAFT CARDS -->
                        <div class="col-lg-5 d-none" id="draftCards">
                            <div class="ss-account-form-lft-1">
                                <h5 class="mb-4 text-capitalize">Draft</h5>

                                @php $counter = 0 @endphp
                                @foreach ($draftJobs as $job)
                                    <div style="" class="col-12 ss-job-prfle-sec draft-cards" onclick="editDataJob(this),
                                    toggleActiveClass('{{$job->id}}_drafts','draft-cards')"
                                    job_id="{{ $counter }}"
                                        id="{{ $job->id}}_drafts">
                                        <h4>{{ $job->profession }} - {{ $job->preferred_specialty }}</h4>
                                        <h6>{{ $job->job_name }}</h6>
                                        <ul>
                                            <li><a href="#"><img
                                                        src=" {{ URL::asset('frontend/img/location.png') }}">
                                                    {{ $job->job_city }}, {{ $job->job_state }}</a></li>
                                            <li><a href="#"><img
                                                        src="{{ URL::asset('frontend/img/calendar.png') }}">
                                                    {{ $job->preferred_assignment_duration }}
                                                    wks</a></li>
                                            <li><a href="#"><img
                                                        src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                                                    {{ $job->weekly_pay }}/wk</a></li>
                                        </ul>

                                    </div>
                                    @php $counter++ @endphp
                                @endforeach
                                <div id="job-list-draft">
                                </div>
                            </div>
                        </div>
                        <!-- END DRAFT CARDS -->

                        <!-- PUBLISHED CARDS -->
                        <div class="col-lg-5 d-none" id="publishedCards">
                            <div class="ss-account-form-lft-1">
                                <h5 class="mb-4 text-capitalize">Published</h5>
                                @php $counter = 0 @endphp
                                @foreach ($publishedJobs as $key => $value)
                                    {{-- <div class="col-12 ss-job-prfle-sec" onclick="editDataJob(this)"
                                        id="{{ $counter }}"> --}}
                                        
                                    <div class="col-12 ss-job-prfle-sec published-cards"
                                        id ="{{$value->id}}_published"
                                        onclick="opportunitiesType('published','{{ $value->id }}','jobdetails'),
                                        toggleActiveClass('{{$value->id}}_published','published-cards')
                                        "
                                        >
                                        <p>Travel <span> {{ $applyCount[$key] }} Applied</span></p>
                                        <h4>{{ $value->profession }} - {{ $value->preferred_specialty }}</h4>
                                        <h6>{{ $value->job_name }}</h6>
                                        <ul>
                                            <li><a href="#"><img
                                                        src=" {{ URL::asset('frontend/img/location.png') }}">
                                                    {{ $value->job_city }}, {{ $value->job_state }}</a></li>
                                            <li><a href="#"><img
                                                        src="{{ URL::asset('frontend/img/calendar.png') }}">
                                                    {{ $value->preferred_assignment_duration }}
                                                    wks</a></li>
                                            <li><a href="#"><img
                                                        src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                                                    {{ $value->weekly_pay }}/wk</a></li>
                                        </ul>

                                    </div>
                                    @php $counter++ @endphp
                                @endforeach
                                @if (count($publishedJobs) == 0)
                                    <div id="job-list-published">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- END PUBLISHED CARDS -->

                        <!-- ONHOLD CARDS -->
                        <div class="col-lg-5 d-none" id="onholdCards">
                            <div class="ss-account-form-lft-1">
                                <h5 class="mb-4 text-capitalize">On Hold</h5>
                                @php $counter = 0 @endphp
                                @foreach ($onholdJobs as $job)
                                    <div class="col-12 ss-job-prfle-sec onhold-cards"
                                        onclick="opportunitiesType('onhold','{{ $job->id }}','jobdetails'),
                                        toggleActiveClass('{{$job->id}}_onhold','onhold-cards')"
                                        id="{{$job->id}}_onhold">
                                        <h4>{{ $job->profession }} - {{ $job->preferred_specialty }}</h4>
                                        <h6>{{ $value->job_name }}</h6>
                                        <ul>
                                            <li><a href="#"><img
                                                        src=" {{ URL::asset('frontend/img/location.png') }}">
                                                    {{ $job->job_city }}, {{ $job->job_state }}</a></li>
                                            <li><a href="#"><img
                                                        src="{{ URL::asset('frontend/img/calendar.png') }}">
                                                    {{ $job->preferred_assignment_duration }}
                                                    wks</a></li>
                                            <li><a href="#"><img
                                                        src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                                                    {{ $job->weekly_pay }}/wk</a></li>
                                        </ul>

                                    </div>
                                    @php $counter++ @endphp
                                @endforeach
                                @if (count($onholdJobs) == 0)
                                    <div id="job-list-onhold">
                                    </div>
                                @endif

                            </div>
                        </div>
                        <!-- END ONHOLD CARDS -->

                         {{-- drafts form --}}
                            @include('organization::organization.opportunitiesmanager.drafts')
                         {{-- end drafts from --}}

                        {{-- edit published jobs form --}}
                            @include('organization::organization.opportunitiesmanager.edit_job')
                        {{-- end edit published jobs form --}}

                        <!-- published details start-->
                        <div class="col-lg-7 d-none" id="details_published">
                            <div class="ss-journy-svd-jbdtl-dv">
                                <div class="ss-job-dtls-view-bx" style="border:2px solid #111011; padding-bottom:10px;">
                                    <div class="row" id="application-details-apply">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- published details end-->

                        <!-- onhold details start-->
                        <div class="col-lg-7 d-none" id="details_onhold">
                            <div class="ss-journy-svd-jbdtl-dv">
                                <div class="ss-job-dtls-view-bx" style="border:2px solid #111011; padding-bottom:10px;">
                                    <div class="row" id="application-details-apply-onhold">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- onhold details end-->

                        <!-- draft details start-->
                        <div class="col-lg-7 d-none" id="details_draft_none">
                            <div class="ss-journy-svd-jbdtl-dv">
                                <div class="ss-job-dtls-view-bx" style="border:2px solid #111011; padding-bottom:10px;">
                                    <div class="row" id="application-details-apply-draft">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- draft details end-->

                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('organization::organization.opportunitiesmanager.scripts.opportunitiesManagerStyles');
@include('organization::organization.opportunitiesmanager.scripts.opportunitiesManagerScripts');
@endsection




