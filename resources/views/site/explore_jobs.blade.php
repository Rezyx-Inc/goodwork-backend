@extends('layouts.main')
@section('mytitle', ' Explore Jobs')
@section('content')
@section('css')

    <link rel='stylesheet' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css'>
    <link href="{{ URL::asset('landing/css/bootstrap.min.css') }}" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/fontawesome_all.css') }}" />
    {{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" /> --}}

    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />

    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/style.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/mdb.min.css') }}" />
    @yield('css')

    <style>
        /* Ads Container */
        .ads-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            position: sticky;
            top: 10px;
            overflow-y: auto;
            z-index: 10;
        }

        .ad {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .ad:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        /* Ad Image */
        .ad-image {
            width: 100%;
            height: auto;
            /* Adjust height automatically */
            max-height: 200px;
            /* Limit maximum height */
            object-fit: contain;
            /* Ensure the image fits without cropping */
            display: block;
            margin: 0 auto;
            /* Center the image horizontally */
        }

        /* Ad Content */
        .ad-content {
            padding: 20px;
            text-align: center;
            /* Center-align all text */
        }

        .ad-content h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333333;
        }

        .ad-content p {
            font-size: 14px;
            color: #666666;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .ad-content ul {
            list-style-type: disc;
            padding-left: 20px;
            margin-bottom: 15px;
            text-align: left;
        }

        .ad-content ul li {
            font-size: 14px;
            color: #666666;
            line-height: 1.5;
        }

        /* Call-to-Action Button */
        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .cta-button:hover {
            background-color: #0056b3;
            color: #ffffff
        }
    </style>





@stop



<!-----------------Search for Nursing Jobs--------->
<section class="ss-ex-pg-srch-ban-sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="ss-expl-srch-bxhed">
                    <h3 style="color: #b5649e;">Explore Jobs</h3>
                </div>
            </div>
        </div>
    </div>
</section>


<!-----------------Search for Nursing Jobs--------->




<!-------------job Filters tabs sec---------------------->


<section class="ss-explore-job-mn-sec">
    <div class="row">
        <div class="col-lg-9">
            <div class="row">
                <div class="col-lg-4 ss-expl-filtr-lft-dv-bx" style="background-color:#fff8fd !important;">
                    <div style="padding:40px !important;">
                        <h4 class="text-center" style="padding-bottom: 10px; font-size: 30px; font-weight: 500;">Filters
                        </h4>
                        <!---form--->
                        <form method="get" action="{{ route('explore-jobs') }}" id="filter_form">
                            <div class="ss-fliter-btn-dv" style="display: flex; justify-content: space-between;">
                                <span class="ss-reset-btn" onclick="resetForm()">Clear search</span>&nbsp;&nbsp;
                                <button class="ss-fliter-btn" type="submit">Filter</button>
                            </div>

                            {{-- job type --}}
                            <div class="ss-input-slct-grp mb-3">
                                <label for="cars">Job Type</label>
                                <select name="job_type">
                                    <option value="">Select</option>
                                    <option value="Clinical" {{ $job_type == 'Clinical' ? 'selected' : '' }}>Clinical
                                    </option>
                                    <option value="Non-Clinical" {{ $job_type == 'Non-Clinical' ? 'selected' : '' }}>
                                        Non-Clinical</option>
                                </select>
                            </div>

                            {{-- facility --}}
                            {{-- <div class="ss-input-slct-grp mb-3">
                          <label for="cars">Facility</label>
                          <select name="facility_name">
                              <option value="">Select</option>
                              @php
                                  $uniqueFacilities = [];
                              @endphp
                              @foreach ($facilities as $v)
                                  @if (!in_array($v->facility_name, $uniqueFacilities))
                                      <option value="{{ $v->facility_name }}" 
                                              data-id="{{ $v->facility_name }}"
                                              {{ $facilityName == $v->facility_name ? 'selected' : '' }}>
                                          {{ $v->facility_name }}
                                      </option>
                                      @php
                                          $uniqueFacilities[] = $v->facility_name;
                                      @endphp
                                  @endif
                              @endforeach
                          </select>                                    
                        </div> --}}

                            {{-- profession --}}
                            <div class="ss-input-slct-grp mb-3">
                                <label for="cars">Profession</label>
                                <select name="profession">
                                    <option value="">Select</option>
                                    @foreach ($professions as $v)
                                        <option value="{{ $v->full_name }}" data-id="{{ $v->full_name }}"
                                            {{ $profession == $v->full_name ? 'selected' : '' }}>{{ $v->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- specialty --}}
                            <div class="ss-input-slct-grp mb-3">
                                <label>Specialty</label>
                                <select name="speciality" id="speciality">
                                    <option value="">Select Specialty</option>
                                    @foreach ($specialities as $v)
                                        <option value="{{ $v->full_name }}" data-id="{{ $v->full_name }}"
                                            {{ $speciality == $v->full_name ? 'selected' : '' }}>{{ $v->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- state --}}
                            <div class="ss-input-slct-grp mb-3">
                                <label> State </label>
                                <select name="state" id="state">
                                    @if (!empty($state))
                                        <option value="" selected>{{ $state }}</option>
                                    @else
                                        <option value="" disabled selected hidden>Select a State</option>
                                    @endif
                                    @foreach ($us_states as $state)
                                        <option id="{{ $state->id }}" value="{{ $state->name }}">
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- city --}}
                            <div class="ss-input-slct-grp mb-3">
                                <label>City</label>
                                <select name="city" id="city">
                                    @if (!empty($city))
                                        <option value="">Select a city</option>
                                        <option value="{{ $city }}" selected>{{ $city }}</option>
                                    @else
                                        <option value="">Select state first</option>
                                    @endif
                                </select>
                            </div>


                            {{-- terms --}}
                            <div class="ss-form-group ss-prsnl-frm-terms mb-3">
                                <label>Terms</label>
                                <div class="ss-speilty-exprnc-add-list terms-content"></div>
                                <ul style="align-items: flex-start; list-style: none;">
                                    <li class="row w-100 p-0 m-0">
                                        <div class="ps-0">
                                            <select class="m-0" id="termsSelect">
                                                <option value="">Select Terms</option>
                                                @foreach ($terms_key as $term)
                                                    <option value="{{ $term->id }}"
                                                        {{ in_array($term->id, $terms) ? 'selected' : '' }}>
                                                        {{ $term->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" id="termsAllValues" name="terms"
                                                value="{{ implode('-', $terms) }}">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ss-prsn-frm-plu-div">
                                            <a href="javascript:void(0)" onclick="addTerms('from_add')">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                                <div>
                                    <span class="helper help-block-terms"></span>
                                </div>
                            </div>


                            {{-- As soon As possible --}}
                            <div class="ss-form-group col-md-12 mb-3">
                                <div class="row">
                                    <div class="row col-lg-12 col-sm-12 col-md-12 col-xs-12"
                                        style="display: flex; justify-content: end; align-items:center;">
                                        <input type="hidden" name="as_soon_as" value="0">
                                        <input id="as_soon_as" name="as_soon_as" value="1" type="checkbox"
                                            {{ $as_soon_as ? 'checked' : '' }} style="box-shadow:none; width:auto;"
                                            class="col-2">
                                        <label class="col-10">
                                            As soon As possible
                                        </label>
                                    </div>
                                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                        <div class="ss-starrt-date">
                                            <label>Start Date</label>
                                            <input type="date" value="{{ $start_date }}" name="start_date"
                                                placeholder="Start Date">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- partial:index.partial.html -->
                            <div class="ss-price-week-sec">
                                <label>Weekly Pay</label>
                                <div id="slider"></div>
                            </div>
                            <!-- partial -->


                            <!-- partial:index.partial.html -->
                            <div class="ss-price-week-sec">
                                <label>Hours Per Shift</label>
                                <div id="slider2"></div>
                            </div>
                            <!-- partial -->
                            <!-- partial:index.partial.html -->
                            <div class="ss-price-week-sec">
                                <label>Hours Per Week</label>
                                <div id="slider3"></div>
                            </div>

                            {{-- job id --}}
                            <div class="ss-input-slct-grp job_id mt-5 mb-3">
                                <label for="cars">Job ID</label>
                                <div class="form-outline">
                                    <input type="text" id="gw" class="gw" name="gw"
                                        placeholder="Search by Job ID" value="{{ request('gw') }}">
                                </div>
                                <div id="gwError" class="text-danger" style="display: none; margin-top: 10px;">
                                </div>
                                <!-- Error message display -->
                            </div>

                            {{-- <input type="hidden" name="terms" value="" id="job_type"> --}}
                            {{-- <input type="hidden" name="shifts" value="" id="shift"> --}}
                            <input type="hidden" name="weekly_pay_from" value="{{ $weekly_pay_from }}"
                                id="minval">
                            <input type="hidden" name="weekly_pay_to" value="{{ $weekly_pay_to }}" id="maxval">
                            <input type="hidden" name="hourly_pay_from" value="{{ $hourly_pay_from }}"
                                id="hps_minval">
                            <input type="hidden" name="hourly_pay_to" value="{{ $hourly_pay_to }}"
                                id="hps_maxval">
                            <input type="hidden" name="hours_per_week_from" value="{{ $hours_per_week_from }}"
                                id="hpw_minval">
                            <input type="hidden" name="hours_per_week_to" value="{{ $hours_per_week_to }}"
                                id="hpw_maxval">
                            {{-- <input type="hidden" name="assignment_from" value="{{$assignment_from}}" id="al_minval">
                        <input type="hidden" name="assignment_to" value="{{$assignment_to}}" id="al_maxval"> --}}
                        </form>
                    </div>
                </div>


                <div class="col-lg-8">

                    <!-----------jobs profiles---------->

                    <div class="ss-dash-profile-jb-mn-dv">

                        <div class="ss-dash-profile-4-bx-dv" id="job-item-container">
                            @forelse($jobs as $j)
                                <div class="ss-job-prfle-sec job-item" data-users="{{ $allusers }}"
                                    data-id="{{ $j->id }}" data-job="{{ json_encode($j) }}">
                                    {{-- row 1 --}}
                                    <div class="row">
                                        <div class="col-10">
                                            <ul>
                                                @if (isset($j->profession))
                                                    <li><a href="#"><svg style="vertical-align: sub;"
                                                                xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor"
                                                                class="bi bi-briefcase" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5m1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0M1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5" />
                                                            </svg> {{ $j->profession }}</a></li>
                                                @endif
                                                @if (isset($j->preferred_specialty))
                                                    <li><a href="#"> {{ $j->preferred_specialty }}</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                        <p class="col-2 text-center" style="padding-right:20px;">
                                            <span>+{{ $j->getOfferCount() }} Applied</span>
                                        </p>
                                    </div>
                                    {{-- row 2 --}}
                                    <div class="row">
                                    </div>
                                    {{-- row 3 --}}
                                    <div class="row">
                                        <div class="col-7">
                                            <ul>
                                                @if (isset($j->job_city) && isset($j->job_state))
                                                    <li><a href="#"><img class="icon_cards"
                                                                src="{{ URL::asset('frontend/img/location.png') }}">
                                                            {{ $j->job_city }}, {{ $j->job_state }}</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="col-5 d-flex justify-content-end">
                                            <ul>
                                                @if (isset($j->preferred_assignment_duration) && isset($j->terms) && $j->terms == 'Contract')
                                                    <li><a href="#"><img class="icon_cards"
                                                                src="{{ URL::asset('frontend/img/calendar.png') }}">
                                                            {{ $j->preferred_assignment_duration }} wks / assignment
                                                        </a></li>
                                                @endif
                                                @if (isset($j->hours_per_week))
                                                    <li><a href="#"><img class="icon_cards"
                                                                src="{{ URL::asset('frontend/img/calendar.png') }}">
                                                            {{ $j->hours_per_week }} hrs/wk</a></li>
                                                @endif
                                        </div>
                                    </div>
                                    {{-- row 4 --}}

                                    <div class="row">

                                        <div class="col-4">
                                            <ul>
                                                @if (isset($j->preferred_shift_duration))
                                                    <li>
                                                        @if ($j->preferred_shift_duration == '5x8 Days' || $j->preferred_shift_duration == '4x10 Days')
                                                            <svg style="vertical-align: bottom;"
                                                                xmlns="http://www.w3.org/2000/svg" width="25"
                                                                height="25" fill="currentColor"
                                                                class="bi bi-brightness-alt-high-fill"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M8 3a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 3m8 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5m-13.5.5a.5.5 0 0 0 0-1h-2a.5.5 0 0 0 0 1zm11.157-6.157a.5.5 0 0 1 0 .707l-1.414 1.414a.5.5 0 1 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0m-9.9 2.121a.5.5 0 0 0 .707-.707L3.05 5.343a.5.5 0 1 0-.707.707zM8 7a4 4 0 0 0-4 4 .5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5 4 4 0 0 0-4-4" />
                                                            </svg>
                                                        @elseif ($j->preferred_shift_duration == '3x12 Nights or Days')
                                                            <svg style="vertical-align: text-bottom;"
                                                                xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="16" fill="currentColor"
                                                                class="bi bi-moon-stars" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277q.792-.001 1.533-.16a.79.79 0 0 1 .81.316.73.73 0 0 1-.031.893A8.35 8.35 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.75.75 0 0 1 6 .278M4.858 1.311A7.27 7.27 0 0 0 1.025 7.71c0 4.02 3.279 7.276 7.319 7.276a7.32 7.32 0 0 0 5.205-2.162q-.506.063-1.029.063c-4.61 0-8.343-3.714-8.343-8.29 0-1.167.242-2.278.681-3.286" />
                                                                <path
                                                                    d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.73 1.73 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.73 1.73 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.73 1.73 0 0 0 1.097-1.097zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z" />
                                                            </svg>
                                                        @endif
                                                        {{ $j->preferred_shift_duration }}
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>

                                        <div class="col-8 d-flex justify-content-end">
                                            <ul>
                                                @if (isset($j->actual_hourly_rate))
                                                    <li><img class="icon_cards"
                                                            src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                                                        {{ number_format($j->actual_hourly_rate) }}/hr
                                                    </li>
                                                @endif
                                                @if (isset($j->weekly_pay))
                                                    <li><img class="icon_cards"
                                                            src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                                                        {{ number_format($j->weekly_pay) }}/wk
                                                    </li>
                                                @endif
                                                @if (isset($j->weekly_pay))
                                                    <li style="font-weight: 600;"><img class="icon_cards"
                                                            src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                                                        {{ number_format($j->weekly_pay * 4 * 12) }}/yr
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>


                                    {{-- row 5 --}}
                                    <div class="row">
                                        {{-- <div class="col-6"><h5>Recently Added</h5></div> --}}

                                        <div class="col-6 d-flex justify-content-start">
                                            @if ($j->as_soon_as == true)
                                                <p class="col-12" style="padding-bottom: 0px; padding-top: 8px;">
                                                    As soon as possible</p>
                                            @endif
                                        </div>
                                        <div class="col-6 d-flex justify-content-end">
                                            @if ($j->urgency == 'Auto Offer' || $j->as_soon_as == true)
                                                <p class="col-2 text-center"
                                                    style="padding-bottom: 0px; padding-top: 8px;">Urgent</p>
                                            @endif
                                        </div>
                                    </div>



                                </div>
                            @empty
                                <div class="ss-job-prfle-sec">
                                    <h4>No Data found</h4>
                                </div>
                            @endforelse

                        </div>
                        <div id="loadTrigger"></div>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-lg-3">
            {{-- ads container --}}
            <div class="ads-container">
                <div class="ad">
                    <img src="{{ asset('images/debtmd.png') }}" alt="Ad Image" class="ad-image">
                    <div class="ad-content">
                        <h2>Become Debt-Free Today</h2>
                        <ul>
                            <li>Up to 50% lower monthly payments</li>
                            <li>Flexible payment plans from 12-48 months</li>
                            <li>Free consultation and zero up-front fees</li>
                        </ul>
                        <a href="https://www.bhmediatrack.com/cmp/M15NZ8/2MCHZ8R/?sub1=&sub2=&sub3="
                            class="cta-button" target="_blank">Start Now</a>
                    </div>
                </div>

                <div class="ad">
                    <img src="{{ asset('images/myPerfectResume.png') }}" alt="Ad Image" class="ad-image">
                    <div class="ad-content">
                        <h2>Get the help you need to land your next gig with a <b>custom resume!</b></h2>
                        <p>
                            Our Industry-leading resume tools take your job search from basic to next level.
                        </p>
                        <a href="https://www.bhmediatrack.com/25S2ZK4/2LWX2H7/?sub1=1&sub2=2&sub3=3"
                            class="cta-button" target="_blank">Free Trials</a>
                    </div>
                </div>

                <div class="ad">
                    <img src="{{ asset('images/chime.png') }}" alt="Ad Image" class="ad-image">
                    <div class="ad-content">
                        <h2>
                            The best way to get up to $500 before payday*
                        </h2>
                        <ul>
                            <li>No interest*</li>
                            <li>No credit check</li>
                            <li>No mandatory fees</li>
                        </ul>
                        <a href="https://www.bhmediatrack.com/25S2ZK4/6Z7MWN9/?sub1=1&sub2=2&sub3=3"
                            class="cta-button" target="_blank">Get Started</a>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="exampleModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="close_modal_button" data-dismiss="modal">Close</button>
                        <button type="button" class="apply_modal_button" onclick="applyButton()">Apply</button>
                    </div>
                </div>
            </div>
        </div>



</section>

<!---------------------mobile show----------------->

@stop


@section('js')

@include("site.explore_jobs_scripts")

@yield('js')
@include('partials.flashMsg')

<script>

    // Clear the form | very bad clearing method
    function resetForm() {
        window.location.href = "{{ route('explore-jobs') }}";
    }

    // get cities according to state :

    const jobState = document.getElementById('state');
    const jobCity = document.getElementById('city');
    let citiesData = [];
    const selectedJobState = jobState.value;
    const selectedState = $(jobState).find(':selected').attr('id');

    jobState.addEventListener('change', async function() {

        const selectedJobState = this.value;
        const selectedState = $(this).find(':selected').attr('id');

        await $.get(`/api/cities/${selectedState}`, function(cities) {
            citiesData = cities;
        });

        jobCity.innerHTML = '<option value="">Cities</option>';

        citiesData.forEach(function(City) {

            const option = document.createElement('option');
            option.value = City.name;
            option.textContent = City.name;
            jobCity.appendChild(option);

        });

    })

    $("#filter_form").submit(function(e) {
        //e.preventDefault();
        // Clear previous error message
        $('#gwError').hide().text('');
        // Get the value of the gw input
        var gwValue = $('#gw').val();

        const categoriesString = selectedCategories.map(function() {
            return $(this).val();
        }).get().join('-');
        // Set the categoriesString as the value of the hidden input field
        $("#job_type").val(categoriesString);
        // $(this).find("input[name='terms[]']").remove();
        // Change the value of the profession select to the text of the selected option
        const professionSelect = $("select[name='profession']");
        const selectedOptionText = professionSelect.find("option:selected").text();
        // Add a hidden input to the form with the text of the selected option
        $(this).append('<input type="hidden" name="profession_text" value="' + selectedOptionText +
            '">');
        this.submit(); // Submit the form
    });

    let terms = []; // Initialize terms as an array to store only values (texts)

    document.addEventListener('DOMContentLoaded', () => {

        const preselectedTerms = document.getElementById('termsAllValues').value.split('-');
        preselectedTerms.forEach(termValue => {
            if (termValue) {
                terms.push(termValue); // Add only the value to the array
            }
        });
        updateTermsList();

    });

    function addTerms(type) {
        const selectElement = document.getElementById('termsSelect');
        const selectedValue = selectElement.options[selectElement.selectedIndex].text; // Get the text

        if (!selectedValue || selectedValue === 'Select Terms') {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-times"></i> Please select a term.',
                time: 3
            });
            return;
        }

        if (!terms.includes(selectedValue)) {
            terms.push(selectedValue); // Add the text value to the array
            selectElement.value = ''; // Clear selection
            updateTermsList();
        } else {
            notie.alert({
                type: 'warning',
                text: '<i class="fa fa-exclamation"></i> This term is already added.',
                time: 3
            });
        }
    }

    function updateTermsList() {
        const termsContentDiv = document.querySelector('.terms-content');
        let termsHtml = '';

        terms.forEach(term => {
            termsHtml += `
                <ul class="row w-100" style="list-style: none;">
                    <li class="col-8">${term}</li>
                    <li class="col-4 text-end">
                        <button type="button" onclick="removeTerm('${term}')">
                            <img src="{{ URL::asset('frontend/img/delete-img.png') }}" />
                        </button>
                    </li>
                </ul>
            `;
        });

        termsContentDiv.innerHTML = termsHtml;

        // Update the hidden input field with the selected terms (joined by '-')
        document.getElementById('termsAllValues').value = terms.join('-');
    }

    function removeTerm(termValue) {
        const index = terms.indexOf(termValue);
        if (index > -1) {
            terms.splice(index, 1);
            updateTermsList();

            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Term removed successfully.',
                time: 3
            });
        } else {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-times"></i> Term not found.',
                time: 3
            });
        }
    }

    function collision($div1, $div2) {
        var x1 = $div1.offset().left;
        var w1 = 40;
        var r1 = x1 + w1;
        var x2 = $div2.offset().left;
        var w2 = 40;
        var r2 = x2 + w2;

        if (r1 < x2 || x1 > r2)
            return false;
        return true;
    }
    // Fetch Url value
    var getQueryString = function(parameter) {
        var href = window.location.href;
        var reg = new RegExp('[?&]' + parameter + '=([^&#]*)', 'i');
        var string = reg.exec(href);
        return string ? string[1] : null;
    };

    // End url
    // // slider call
    $(document).ready(function() {

        $('#slider').slider({
            range: true,
            min: 1000,
            max: 10000,
            step: 1,
            values: [
                $('#minval').val() ? parseInt($('#minval').val()) : 1000,
                $('#maxval').val() ? parseInt($('#maxval').val()) : 10000
            ],
            slide: function(event, ui) {
                $('#slider .ui-slider-handle:eq(0) .price-range-min').html('$' + ui.values[0]);
                $('#slider .ui-slider-handle:eq(1) .price-range-max').html('$' + ui.values[1]);
                $('#slider .price-range-both').html('<i>$' + ui.values[0] + ' - $' + ui.values[1] +
                    '</i>');

                // Update hidden inputs
                $("#minval").val(ui.values[0]);
                $("#maxval").val(ui.values[1]);

                // UI adjustments
                if (ui.values[0] == ui.values[1]) {
                    $('#slider .price-range-both i').css('display', 'none');
                } else {
                    $('#slider .price-range-both i').css('display', 'inline');
                }

                if (collision($('.price-range-min'), $('.price-range-max')) == true) {
                    $('#slider .price-range-min, .price-range-max').css('opacity', '0');
                    $('#slider .price-range-both').css('display', 'block');
                } else {
                    $('#slider .price-range-min, .price-range-max').css('opacity', '1');
                    $('#slider .price-range-both').css('display', 'none');
                }
            }
        });

        // Add dynamic price ranges
        $('#slider .ui-slider-range').append('<span class="price-range-both value"><i>$' + $('#slider').slider(
            'values', 0) + ' - $' + $('#slider').slider('values', 1) + '</i></span>');
        $('#slider .ui-slider-handle:eq(0)').append('<span class="price-range-min value">$' + $('#slider')
            .slider('values', 0) + '</span>');
        $('#slider .ui-slider-handle:eq(1)').append('<span class="price-range-max value">$' + $('#slider')
            .slider('values', 1) + '</span>');




        // // slider call
        $('#slider2').slider({
            range: true,
            min: 2,
            max: 24,
            step: 1,
            values: [$('#hps_minval').val() ? $('#hps_minval').val() : 2, $('#hps_maxval').val() ? $(
                '#hps_maxval').val() : 24],

            slide: function(event, ui) {

                $('#slider2 .ui-slider-handle:eq(0) .price-range-min-2').html(ui.values[0]);
                $('#slider2 .ui-slider-handle:eq(1) .price-range-max-2').html(ui.values[1]);
                $('#slider2 .price-range-both-2').html('<i>' + ui.values[0] + ' - ' + ui.values[1] +
                    '</i>');

                // get values of min and max
                $("#hps_minval").val(ui.values[0]);
                $("#hps_maxval").val(ui.values[1]);

                if (ui.values[0] == ui.values[1]) {
                    $('#slider2 .price-range-both-2 i').css('display', 'none');
                } else {
                    $('#slider2 .price-range-both-2 i').css('display', 'inline');
                }

                if (collision($('#slider2 .price-range-min-2'), $('#slider2 .price-range-max-2')) ==
                    true) {
                    $('#slider2 .price-range-min-2, .price-range-max-2').css('opacity', '0');
                    $('#slider2 .price-range-both-2').css('display', 'block');
                } else {
                    $('#slider2 .price-range-min-2, .price-range-max-2').css('opacity', '1');
                    $('#slider2 .price-range-both-2').css('display', 'none');
                }

            }
        });

        $('#slider2 .ui-slider-range').append('<span class="price-range-both-2 value"></span>');

        $('#slider2 .ui-slider-handle:eq(0)').append('<span class="price-range-min-2 value">' + $('#slider2')
            .slider('values', 0) + '</span>');

        $('#slider2 .ui-slider-handle:eq(1)').append('<span class="price-range-max-2 value">' + $('#slider2')
            .slider('values', 1) + '</span>');

        //slider3
        $('#slider3').slider({
            range: true,
            min: 10,
            max: 100,
            step: 1,
            values: [$('#hpw_minval').val() ? $('#hpw_minval').val() : 10, $('#hpw_maxval').val() ? $(
                '#hpw_maxval').val() : 100],

            slide: function(event, ui) {

                $('#slider3 .ui-slider-handle:eq(0) .price-range-min-3').html(ui.values[0]);
                $('#slider3 .ui-slider-handle:eq(1) .price-range-max-3').html(ui.values[1]);
                $('#slider3 .price-range-both-3').html('<i>' + ui.values[0] + ' - ' + ui.values[1] +
                    '</i>');

                // get values of min and max
                $("#hpw_minval").val(ui.values[0]);
                $("#hpw_maxval").val(ui.values[1]);

                if (ui.values[0] == ui.values[1]) {
                    $('#slider3 .price-range-both-3 i').css('display', 'none');
                } else {
                    $('#slider3 .price-range-both-3 i').css('display', 'inline');
                }

                if (collision($('#slider3 .price-range-min-3'), $('#slider3 .price-range-max-3')) ==
                    true) {
                    $('#slider3 .price-range-min-3, .price-range-max-3').css('opacity', '0');
                    $('#slider3 .price-range-both-3').css('display', 'block');
                } else {
                    $('#slider3 .price-range-min-3, .price-range-max-3').css('opacity', '1');
                    $('#slider3 .price-range-both-3').css('display', 'none');
                }

            }
        });

        $('#slider3 .ui-slider-range').append('<span class="price-range-both-3 value"></span>');

        $('#slider3 .ui-slider-handle:eq(0)').append('<span class="price-range-min-3 value">' + $('#slider3')
            .slider('values', 0) + '</span>');

        $('#slider3 .ui-slider-handle:eq(1)').append('<span class="price-range-max-3 value">' + $('#slider3')
            .slider('values', 1) + '</span>');

        $('#slider3 .ui-slider-handle:eq(1)').append('<span class="price-range-max-3 value">' + $('#slider3')
            .slider('values', 1) + '</span>');

        // Add an intersect Observer for infinite scroll
        var skip = 0;
        var el = document.querySelector('#loadTrigger');

        var observer = new window.IntersectionObserver(([entry]) => {

            // Only observe intersections
            if (entry.isIntersecting) {
                skip > 0 ? null : 0;

                //Do the Ajax call
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: full_path + "explore-jobs?skip=100",
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        ajaxindicatorstop();

                        addJobCards(data.message);
                        // Increment skip
                        skip+=10;
                    },
                    error: function(resp) {

                        notie.alert({
                            type: 'error',
                            text: '<i class="fa fa-check"></i> Oops ! Can\'t load more jobs ! Please try later.',
                            time: 5
                        });
                    }
                });

                // Handle the event listeners
                document.querySelectorAll(".job-item").forEach(item => {

                    item.removeEventListener("click",handleCardClickEvent);
                    item.removeEventListener("click",handleCardClickEvent, true);
                    item.addEventListener("click", handleCardClickEvent);
                });

                return
            }

        }, {
          root: null,
          threshold: 0.5,
        });

        // Observe
        observer.observe(el);

        document.querySelectorAll(".job-item").forEach(item => {

            item.addEventListener("click", function() {
                const jobData = this.dataset.job;
                const allusers = this.dataset.users; // Corrected from 'allusers' to 'users'
                try {
                    const job = JSON.parse(jobData);
                    const users = JSON.parse(allusers);
                    redirectToJobDetails(job, users);
                } catch (error) {
                    console.error("Invalid job data:", error);
                }
            });
        });

        function handleCardClickEvent(){
            var jobData = this.dataset.job;
            var allusers = this.dataset.users; // Corrected from 'allusers' to 'users'

            jobData = JSON.parse(jobData.replaceAll("'", "\""));
            allusers= JSON.parse(allusers.replaceAll("'", "\""));

            try {
                const job = jobData;
                const users = allusers;
                redirectToJobDetails(job, users);
            } catch (error) {
                console.error("Invalid job data:", error);
            }
        }
    });
</script>

<style>
    span.ss-reset-btn {
        border: 1px #3d2c39 solid;
        cursor: pointer;
        background: #3d2c3998;
        padding: 10px;
        text-align: center;
        color: #fff;
        font-size: 18px;
        width: 100%;
        border-radius: 100px;
    }

    .ss-prsnl-frm-terms ul {
        list-style-type: none;
        display: flex;
        gap: 22px;
        align-items: flex-start;
    }

    .ss-prsnl-frm-terms .terms-content ul {
        display: flex;
        align-items: center !important;
        margin-top: 8px;
    }

    .ss-prsnl-frm-terms .terms-content ul li:nth-child(1) {
        width: 90%;
    }

    .ss-prsnl-frm-terms .terms-content ul li:nth-child(2) {
        width: 40%;
    }

    .ss-prsn-frm-plu-div i {
        background: #3d2c39;
        width: 65px;
        height: 55px;
        line-height: 55px;
        color: #fff;
        text-align: center;
        font-size: 25px;
        border-radius: 14px;
    }

    .ss-speilty-exprnc-add-list ul {
        display: flex;
        align-items: center !important;
        margin-top: 8px;
    }

    .ss-speilty-exprnc-add-list {
        padding-bottom: 12px;
    }

    .ss-speilty-exprnc-add-list ul li:nth-child(1) {
        width: 40% !important;
    }

    .ss-speilty-exprnc-add-list ul li:nth-child(2) {
        width: 40%;
    }

    .ss-speilty-exprnc-add-list ul li:nth-child(3) {
        width: 20%;
        text-align: right;
    }

    .ss-speilty-exprnc-add-list button {
        border: 0;
        background: transparent;
    }

    .ss-starrt-date input {
        margin-bottom: 10px;
        width: 100%;
        border: 2px solid #3d2c39;
        box-shadow: 5px 5px 0px 0px #403b4be5;
        border-radius: 12px;
        padding: 12px 15px;
    }

    .job_id input {
        border: 2px solid #3D2C39 !important;
        width: 100%;
        padding: 10px !important;
        box-shadow: 10px 10px 0px 0px #3D2C39;
        border-radius: 15px;
        font-weight: 500;
        font-size: 15px;
    }

    .ss-job-prfle-sec-card-job-details-user-logout {

        box-shadow: 10px 10px 0px 0px #403B4BE5;
        border: 2px solid #3D2C39;
        padding: 15px;
        border-bottom: 5px;
        margin-bottom: 30px;
        border-radius: 10px;
        position: relative;
        background: #fff;
        cursor: pointer;
        margin-left: 0px !important;
    }

    .infos_width {
        width: 100% !important;
    }

    .model_content_width {
        width: 90% !important;
    }

    .header_content_width {
        margin-left: 0px !important;

    }
</style>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

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

    .close_modal_button {
        border: 1px #3d2c39 solid;
        cursor: pointer;
        background: #3d2c3998;
        padding: 10px 60px;
        text-align: center;
        color: #fff;
        border-radius: 20px;
    }

    .apply_modal_button {
        border: 0;
        background: #3d2c39;
        padding: 10px 60px;
        color: #fff;
        border-radius: 20px;
    }

    .ask_recruiter_href {
        color: #333;
    }

    .ask_recruiter_href:hover {
        color: black;
        text-decoration: underline;
    }
</style>

@stop
