@extends('layouts.main')
@section('mytitle', ' For Organizations | Saas')
@section('content')
@section('css')
    <link rel='stylesheet' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css'>
    <link href="{{ URL::asset('landing/css/bootstrap.min.css') }}" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/fontawesome_all.css') }}" />
    {{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" /> --}}
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
    <!-- MDB -->
    <link href="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ URL::asset('backend/vendors/datatables/jquery.dataTables.min.css') }}" rel="stylesheet"> --}}
    {{-- Notie --}}
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/vendors/notie/dist/notie.css') }}">
    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/style.css') }}" />
    @yield('css')

    <!-- <link href="{{ URL::asset('landing/css/bootstrap.min.css') }}" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <link href="{{ URL::asset('landing/css/bootstrap.min.css') }}" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/mdb.min.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/fontawesome_all.css') }}" />
    {{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" /> --}}
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
    <!-- MDB -->
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/mdb.min.css') }}" />
    {{-- jquery confirm --}}
    <link href="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ URL::asset('backend/vendors/datatables/jquery.dataTables.min.css') }}" rel="stylesheet"> --}}
    {{-- Notie --}}
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/vendors/notie/dist/notie.css') }}">
    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/style.css') }}" />

    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    @yield('css')
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
    <div class="container">
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

                        {{-- Organization Name --}}
                        {{-- <div class="ss-input-slct-grp mb-3">
                          <label for="organization_name">Organization Name</label>
                          <select id="organization_name" name="organization_name">
                              <option value="">Select</option>
                              @foreach ($organizations as $v)
                                  <option value="{{ $v->organization_name }}"
                                      {{ $organization_name == $v->organization_name ? 'selected' : '' }}>{{ $v->organization_name }}
                                  </option>
                              @endforeach
                          </select>
                        </div> --}}

                        {{-- Recruiter Name --}}
                        {{-- <div class="ss-input-slct-grp mb-3">
                          <label for="recruiter_name">Recruiter Name</label>
                          <select id="recruiter_name" name="recruiter_name">
                              <option value="">Select</option>
                              @foreach ($recruiters as $v)
                                  <option value="{{ $v->first_name }} {{ $v->last_name }}"
                                      data-org="{{ $v->organization_name }}"
                                      {{ $recruiter_name == $v->first_name . ' ' . $v->last_name ? 'selected' : '' }}>
                                      {{ $v->first_name }} {{ $v->last_name }}
                                  </option>
                              @endforeach
                          </select>
                        </div> --}}

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
                            <div id="gwError" class="text-danger" style="display: none; margin-top: 10px;"></div>
                            <!-- Error message display -->
                        </div>

                        <!-- partial -->
                        <!-- partial:index.partial.html -->
                        {{-- <div class="ss-price-week-sec">
                        <label>Assignment Length</label>
                        <div id="slider4"></div>
                        </div> --}}
                        <!-- partial -->


                        {{-- <div class="ss-jobtype-dv ss-shift-type-inpy">
                            <label>Shift type</label>
                                 <ul class="ks-cboxtags">
                                    @foreach ($prefered_shifts as $k => $v)
                                    <li><input type="checkbox" name="shift[]" id="checkboxDay-{{$k}}" value="{{$v->title}}" {{ (in_array($v->title,$shifts)) ? 'checked': ''}}><label for="checkboxDay-{{$k}}">{{$v->title}}</label></li>
                        @endforeach
                        </ul>
                        </div> --}}

                        {{-- <input type="hidden" name="terms" value="" id="job_type"> --}}
                        {{-- <input type="hidden" name="shifts" value="" id="shift"> --}}
                        <input type="hidden" name="weekly_pay_from" value="{{ $weekly_pay_from }}" id="minval">
                        <input type="hidden" name="weekly_pay_to" value="{{ $weekly_pay_to }}" id="maxval">
                        <input type="hidden" name="hourly_pay_from" value="{{ $hourly_pay_from }}"
                            id="hps_minval">
                        <input type="hidden" name="hourly_pay_to" value="{{ $hourly_pay_to }}" id="hps_maxval">
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

                    <div class="ss-dash-profile-4-bx-dv">
                        @forelse($jobs as $j)
                            <div class="ss-job-prfle-sec job-item" data-users="{{ $allusers }}"
                                data-id="{{ $j }}" data-job="{{ json_encode($j) }}">
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
                                    {{-- <div class="col-3"><ul><li><a href="{{route('worker_job-details',['id'=>$j->id])}}"><img class="icon_cards" src="{{URL::asset('frontend/img/job.png')}}"> {{$j->job_name}}</a></li>
                  </ul>
                </div> --}}
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
                </div>

            </div>
        </div>

        <!-- Button trigger modal -->
        {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Launch demo modal
</button> --}}


        <div class="modal fade" id="exampleModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="close_modal_button" data-dismiss="modal">Close</button>
                        <button type="button" class="apply_modal_button" data-dismiss="modal">Apply</button>
                    </div>
                </div>
            </div>
        </div>



</section>

<!---------------------mobile show----------------->

@stop


@section('js')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{{-- var isLoggedIn = {{ auth()->guard('frontend')->check() ? 'true' : 'false' }}; --}}

<script>
    var isLoggedIn = @json(auth()->guard('frontend')->check());

    function redirectToJobDetails(job, users) {
        if (isLoggedIn) {
            window.location.href = `job/${job.id}/details`;
        } else {
            showJobModal(job, users);
        }
    }

    function showJobModal(job, users) {

        // Image paths from Blade
        const locationIcon = @json(asset('frontend/img/location.png'));
        const calendarIcon = @json(asset('frontend/img/calendar.png'));
        const dollarIcon = @json(asset('frontend/img/dollarcircle.png'));

        // Default recruiter image if not provided
        const recruiterImage = (job.recruiter && job.recruiter.image) ? job.recruiter.image : 'default-image.png';

        // Path for profile images
        const userProfilePath = @json(asset('images/nurses/profile/'));

        // full name
        const creator = users.find(user => user.id === job.created_by);
        const fullName = creator ? creator.first_name + ' ' + creator.last_name : 'Unknown';
        const userRole = creator ? creator.role : 'Unknown';

        // org name
        const org = users.find(user => user.id === job.organization_id);
        const orgrName = org ? org.organization_name : 'Unknown';

        // set the set ask recruiter as a link to message
        let askRecruiter = '<a class="ask_recruiter_href" href="{{ route('worker.login') }}" >Ask recruiter</a>';


        // Set job data in the modal
        document.querySelector("#exampleModal .modal-body").innerHTML = `
    <main class="ss-main-body-sec px-1">
        <div class="ss-apply-on-jb-mmn-dv">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ss-apply-on-jb-mmn-dv-box-divs model_content_width">
                        <div class="ss-job-prfle-sec header_content_width">
                            <div class="row">
                                <div class="col-10">
                                    <ul>
                                        <li>
                                            <a href="#">
                                                <svg style="vertical-align: sub;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-briefcase" viewBox="0 0 16 16">
                                                    <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5m1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0M1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5" />
                                                </svg>
                                                ${job.profession}
                                            </a>
                                        </li>
                                        <li><a href="#">${job.preferred_specialty}</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-7">
                                    <ul>
                                        <li>
                                            <a href="#">
                                                <img class="icon_cards" src="${locationIcon}">
                                                ${job.job_city}, ${job.job_state}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-5 d-flex justify-content-end">
                                    <ul>
                                        <li>
                                            <a href="#">
                                                <img class="icon_cards" src="${calendarIcon}">
                                                ${job.preferred_assignment_duration} wks
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <img class="icon_cards" src="${calendarIcon}">
                                                ${job.hours_per_week} hrs/wk
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <ul>
                                        <li>
                                            <img class="icon_cards" src="frontend/img/dollarcircle.png">
                                            ${(Number(job.actual_hourly_rate) || 0).toFixed(2)}/hr
                                        </li>
                                        <li>
                                            <img class="icon_cards" src="frontend/img/dollarcircle.png">
                                            ${(Number(job.weekly_pay) || 0).toFixed(2)}/hr
                                        </li>
                                        <li style="font-weight: 600;">
                                            <img class="icon_cards" src="frontend/img/dollarcircle.png">
                                            ${(job.weekly_pay * 4 * 12).toFixed(2)}/yr
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="ss-job-apply-on-view-detls-mn-dv infos_width">
                            <div class="ss-job-apply-on-tx-bx-hed-dv">
                                <ul>
                                    <li>
                                        <p>${userRole}</p>
                                    </li>
                                    <li>
                                        <img width="50px" height="50px" src="${userProfilePath}/${recruiterImage}" onerror="this.onerror=null;this.src='default-image.png';" />
                                        ${fullName}
                                    </li>
                                </ul>
                                <ul>
                                    <li>
                                        <span>${job.id}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="ss-jb-aap-on-txt-abt-dv">
                                <h5>About Work</h5>
                                <ul>
                                    <li>
                                        <h6>Organization Name</h6>
                                        <p>${orgrName}</p>
                                    </li>
                                    <li>
                                        <h6>Date Posted</h6>
                                        <p>${new Date(job.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</p>
                                    </li>
                                    <li>
                                        <h6>Type</h6>
                                        <p>${job.job_type}</p>
                                    </li>
                                    <li>
                                        <h6>Terms</h6>
                                        <p>${job.terms}</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="ss-jb-apply-on-disc-txt">
                                <h5>Description</h5>
                                <p id="job_description">${job.description}</p>
                            </div>



                            <div class="ss-jb-apl-oninfrm-mn-dv">
                                <center>
                                    <div class="mb-3">
                                        <h5>Work Information</h5>
                                    </div>
                                </center>
                                <button class="btn first-collapse" data-toggle="collapse"
                                    data-target="#summary">Summary</button>
                                <div id="summary" class="collapse">
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul type_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Type</span>
                                            
                                        </li>
                                        <li>
                                            <h6>
                                              ${job.job_type}
                                            </h6>
                                        </li>
                                    </ul>
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul terms_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Terms</span>
                                        </li>
                                        <li>
                                             <h6>
                                               ${job.terms}
                                            </h6>
                                        </li>
                                    </ul>
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul terms_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Terms</span>
                                        </li>
                                        <li>
                                             <h6>
                                               ${job.terms}
                                            </h6>
                                        </li>
                                    </ul>
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul terms_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Profession</span>
                                        </li>
                                        <li>
                                             <h6>
                                               ${job.profession}
                                            </h6>
                                        </li>
                                    </ul>
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul terms_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Specialty</span>
                                        </li>
                                        <li>
                                             <h6>
                                               ${job.preferred_specialty}
                                            </h6>
                                        </li>
                                    </ul>
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul actual_hourly_rate_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Est. Taxable Hourly Rate</span>
                                        </li>
                                        <li>
                                            <h6>
                                                $${job.actual_hourly_rate}
                                            </h6>
                                        </li>
                                    </ul>
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul hours_per_week_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Hours/Week</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.hours_per_week}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul job_state_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Facility State</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.job_state}
                                            </h6>
                                        </li>
                                    </ul>
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul job_city_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Facility City</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.job_city}
                                            </h6>
                                        </li>
                                    </ul>
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul resume_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Resume</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.is_resume ? 'Required' : 'Not Required'}
                                            </h6>
                                        </li>
                                    </ul>
                                </div>

                                <button class="btn first-collapse mt-3" data-toggle="collapse"
                                    data-target="#shift">Shift</button>
                                <div id="shift" class="collapse">

                                    <ul id="worker_shift_time_of_day" class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Shift Time Of Day</span>
                                        
                                        </li>
                                        <li>
                                             <h6>
                                                ${job.preferred_shift_duration ? job.preferred_shift_duration : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul type_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Guaranteed Hours</span>

                                        </li>
                                        <li>
                                           <h6>
                                                ${job.guaranteed_hours ? job.guaranteed_hours.toLocaleString() : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul type_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Hours/Shift</span>

                                        </li>
                                        <li>
                                           <h6>
                                                ${job.hours_shift ? job.hours_shift : askRecruiter}
                                                </h6>
                                                </li>
                                                </ul>
                                                
                                                <ul class="ss-s-jb-apl-on-inf-txt-ul type_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Shifts/Week</span>
                                        
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.weeks_shift ? job.weeks_shift.toLocaleString() : askRecruiter}
                                            </h6>
                                        </li>
                                        </ul>
                                        
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul type_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Weeks/Assignment</span>
                                            
                                            </li>
                                        <li>
                                             <h6>
                                                ${job.preferred_assignment_duration ? job.preferred_assignment_duration : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul type_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Start Date</span>
                                            
                                            </li>
                                        <li>
                                            <h6>
                                                ${job.as_soon_as == 1 ? 'As soon as possible' : (job.start_date || askRecruiter)}
                                            </h6>
                                        </li>
                                        </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul type_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>RTO</span>
                                            
                                            </li>
                                            <li>
                                                <h6>
                                                    ${job.rto ? job.rto : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>
                                </div>

                                <button class="btn first-collapse mt-3" data-toggle="collapse"
                                   data-target="#pay">Pay</button>
                                <div id="pay" class="collapse">

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul overtime_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Overtime</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.overtime ? job.overtime : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul on_call_rate_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>On Call Rate</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.on_call_rate ? `$${job.on_call_rate}` : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul call_back_rate_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Call Back Rate</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.call_back_rate ? `$${job.call_back_rate}` : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul orientation_rate_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Orientation Rate</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.orientation_rate ? job.orientation_rate : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul weekly_non_taxable_amount_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Est. Weekly Non-Taxable Amount</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.weekly_non_taxable_amount ? `$${job.weekly_non_taxable_amount}` : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul feels_like_per_hour_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Feels Like $/Hr</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.feels_like_per_hour ?  `$${job.feels_like_per_hour}` : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Est. Goodwork Weekly Amount</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.goodwork_weekly_amount ? `$${job.goodwork_weekly_amount}` : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul referral_bonus_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Referral Bonus</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.referral_bonus ? job.referral_bonus : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul sign_on_bonus_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Sign-On Bonus</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.sign_on_bonus ? `$${job.sign_on_bonus}` : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul extension_bonus_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Extension Bonus</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.extension_bonus ? '$' + job.extension_bonus.toLocaleString() : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul completion_bonus_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Completion Bonus</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.completion_bonus ? '$' + job.completion_bonus.toLocaleString() : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul other_bonus_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Other Bonus</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.other_bonus ? '$' + job.other_bonus.toLocaleString() : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul health_insaurance_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Health Insurance</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.health_insaurance ? (job.health_insaurance == '1' ? 'Yes' : 'No') : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Pay Frequency</span>
                                            </li>
                                            <li>
                                            <h6>
                                                ${job.pay_frequency ? job.pay_frequency : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul id="worker_benefits" class="ss-s-jb-apl-on-inf-txt-ul benefits_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Benefits</span>
                                            </li>
                                            <li>
                                            <h6>
                                                ${job.benefits ? job.benefits : askRecruiter}
                                                </h6>
                                            
                                        </li>
                                    </ul>

                                    <ul id="worker_dental" class="ss-s-jb-apl-on-inf-txt-ul dental_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Dental</span>
                                        
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.dental ? (job.dental === '1' ? 'Yes' : 'No') : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul id="worker_vision" class="ss-s-jb-apl-on-inf-txt-ul vision_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Vision</span>

                                        </li>
                                        <li>
                                            <h6>
                                                ${job.vision ? (job.vision === '1' ? 'Yes' : 'No') : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul id="worker_four_zero_one_k" class="ss-s-jb-apl-on-inf-txt-ul four_zero_one_k_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>401K</span>

                                        </li>
                                        <li>
                                            <h6>
                                                ${job.four_zero_one_k ? (job.four_zero_one_k === '1' ? 'Yes' : 'No') : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>


                                </div>

                                <button class="btn first-collapse mt-3" data-toggle="collapse"
                                    data-target="#location">Location</button>
                                <div id="location" class="collapse">

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul clinical_setting_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Clinical Setting</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.clinical_setting ? job.clinical_setting : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Address</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.preferred_work_location ? job.preferred_work_location : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Facility</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.facility_name ? job.facility_name : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Facility's Parent System</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.facilitys_parent_system ? job.facilitys_parent_system : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul facility_shift_cancelation_policy_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Facility Shift Cancellation Policy</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.facility_shift_cancelation_policy ? job.facility_shift_cancelation_policy : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul contract_termination_policy_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Contract Termination Policy</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.contract_termination_policy ? job.contract_termination_policy : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul traveler_distance_from_facility_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Min Miles Must Live From Facility</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.traveler_distance_from_facility ? job.traveler_distance_from_facility : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>
   
                                </div>

                                <button class="btn first-collapse mt-3" data-toggle="collapse"
                                    data-target="#certslicen">Certs & Licences</button>
                                <div id="certslicen" class="collapse">

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul job_location_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Professional Licensure</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.job_location ? job.job_location.split(',').map(v => `${v} Required`).join('<br>') : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>
                                    
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul certificate_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Certifications</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.certificate ? job.certificate.split(',').map(v => `${v} Required`).join('<br>') : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                </div>

                                <button class="btn first-collapse mt-3" data-toggle="collapse"
                                    data-target="#workInfo">Work Info</button>
                                <div id="workInfo" class="collapse">

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul urgency_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Urgency</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.urgency ? job.urgency : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul preferred_experience_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Experience</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.preferred_experience ? `${job.preferred_experience} Years Required` : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul number_of_references_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Number of references</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.number_of_references ? `${job.number_of_references} references` : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul skills_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Skills checklist</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.skills ? job.skills.replace(/,/g, ', ') : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul on_call_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>On call</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.on_call ? (job.on_call === '1' ? 'Yes' : 'No') : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul block_scheduling_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Block Scheduling</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.block_scheduling ? (job.block_scheduling === '1' ? 'Yes' : 'No') : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul float_requirement_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Float Requirements</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.float_requirement ? (job.float_requirement === '1' ? 'Yes' : 'No') : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul Patient_ratio_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Patient Ratio</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.Patient_ratio ? job.Patient_ratio.toLocaleString() : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul emr_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>EMR</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.Emr ? job.Emr : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>

                                </div>

                                <button class="btn first-collapse mt-3" data-toggle="collapse" data-target="#idTax">ID &
                                    Tax Info</button>
                                <div id="idTax" class="collapse">

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul urgency_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Classification</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.nurse_classification ? job.nurse_classification : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>
                                </div>

                                <button class="btn first-collapse mt-3" data-toggle="collapse" data-target="#medInf">
                                    Medical info</button>
                                <div id="medInf" class="collapse">
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul vaccinations_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Vaccinations & Immunizations</span>
                                        </li>
                                        <li>
                                            <h6>
                                                ${job.vaccinations ? job.vaccinations.split(',').map(v => `${v} Required`).join('<br>') : askRecruiter}
                                            </h6>
                                        </li>
                                    </ul>
                                </div>

                                <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                    <li>
                                        <span style="font-size: larger">(*) : Required Fields</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                         
                    </div>
                </div>
            </div>
        </div>
    </main>
    `;


        // Show the modal
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        myModal.show();
    }

    document.addEventListener("DOMContentLoaded", function() {
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
    });
</script>






<!-- Option 1: Bootstrap Bundle with Popper -->
<!-- <script src="{{ URL::asset('landing/js/bootstrap.bundle.min.js') }}"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script> -->
<script src="{{ URL::asset('landing/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>

<!--Main layout-->
<!-- MDB -->
<script type="text/javascript" src="{{ URL::asset('frontend/js/mdb.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.js') }}"
    type="text/javascript"></script>
{{-- <script type="text/javascript" src="{{ URL::asset('backend/vendors/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script> --}}
<script type="text/javascript" src="{{ URL::asset('backend/vendors/notie/dist/notie.min.js') }}"></script>
{{-- CK editor --}}
<script src="{{ URL::asset('backend/vendors/ckeditor/ckeditor.js') }}"></script>
{{-- Jquery Mask --}}
<script type="text/javascript" src="{{ URL::asset('frontend/vendor/mask/jquery.mask.min.js') }}"></script>
<!-- Custom scripts -->
<script type="text/javascript" src="{{ URL::asset('frontend/js/nav-bar-script.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('frontend/custom/js/profile.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('frontend/custom/js/script.js') }}"></script>

@yield('js')
@include('partials.flashMsg')

<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<script>
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
</script>

<script>
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
</script>

<script>
    function resetForm() {
        window.location.href = "{{ route('explore-jobs') }}";
    }

    const recruitersName = @json($recruiters); // Recruiters data from the backend
    const orgSelect = document.getElementById('organization_name');
    const recruiterSelect = document.getElementById('recruiter_name');

    // Function to populate recruiters based on the selected organization
    function populateRecruiters(selectedOrg, selectedRecruiter) {
        // Clear recruiter dropdown
        recruiterSelect.innerHTML = '<option value="">Select</option>';

        // Filter and add recruiters based on the organization
        recruitersName.forEach(recruiter => {
            const recruiterOrg = recruiter.organization_name;
            if (recruiterOrg === selectedOrg || !selectedOrg) {
                // Create and append option
                const option = document.createElement('option');
                option.value = `${recruiter.first_name} ${recruiter.last_name}`;
                option.textContent = `${recruiter.first_name} ${recruiter.last_name}`;
                if (option.value === selectedRecruiter) {
                    option.selected = true; // Persist selected recruiter
                }
                recruiterSelect.appendChild(option);
            }
        });
    }

    // Event listener for organization dropdown change
    orgSelect.addEventListener('change', function() {
        const selectedOrg = this.value;
        populateRecruiters(selectedOrg, recruiterSelect.value);
    });

    // Populate recruiters on page load (for persistence after form submission)
    document.addEventListener('DOMContentLoaded', function() {
        const selectedOrg = orgSelect.value; // Get currently selected organization
        const selectedRecruiter = recruiterSelect.value; // Get currently selected recruiter
        populateRecruiters(selectedOrg, selectedRecruiter);
    });
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
        // $('#slider').slider({
        //     range: true,
        //     min: 1000,
        //     max: 10000,
        //     step: 1,
        //     values: [$('#minval').val() ? $('#minval').val() : 3000, $('#maxval').val() ? $('#maxval')
        //         .val() : 6000
        //     ],

        //     slide: function(event, ui) {

        //         $('#slider .ui-slider-handle:eq(0) .price-range-min').html('$' + ui.values[0]);
        //         $('#slider .ui-slider-handle:eq(1) .price-range-max').html('$' + ui.values[1]);
        //         $('#slider .price-range-both').html('<i>$' + ui.values[0] + ' - $' + ui.values[1] +
        //             '</i>');

        //         // get values of min and max
        //         $("#minval").val(ui.values[0]);
        //         $("#maxval").val(ui.values[1]);

        //         if (ui.values[0] == ui.values[1]) {
        //             // alert('kir');
        //             $('#slider .price-range-both i').css('display', 'none');
        //         } else {
        //             $('#slider .price-range-both i').css('display', 'inline');
        //         }

        //         if (collision($('.price-range-min'), $('.price-range-max')) == true) {
        //             $('#slider .price-range-min, .price-range-max').css('opacity', '0');
        //             $('#slider .price-range-both').css('display', 'block');
        //         } else {
        //             $('#slider .price-range-min, .price-range-max').css('opacity', '1');
        //             $('#slider .price-range-both').css('display', 'none');
        //         }

        //     }
        // });

        // $('#slider .ui-slider-range').append('<span class="price-range-both value"><i>$' + $('#slider').slider(
        //     'values', 0) + ' - $' + $('#slider').slider('values', 1) + '</i></span>');

        // $('#slider .ui-slider-handle:eq(0)').append('<span class="price-range-min value">$' + $('#slider')
        //     .slider('values', 0) + '</span>');

        // $('#slider .ui-slider-handle:eq(1)').append('<span class="price-range-max value">$' + $('#slider')
        //     .slider('values', 1) + '</span>');

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

    });
</script>


<script>
    $(document).ready(function() {
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
