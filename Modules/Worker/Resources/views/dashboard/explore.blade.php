@extends('worker::layouts.main')
@section('mytitle', 'Explore Jobs')
@section('css')
<link rel='stylesheet' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css'>
@stop
@section('content')

<!--Main layout-->
<main style="padding-top: 130px" class="ss-main-body-sec">
  <div class="container">

    <!--------Explore Jobs------->

    <div class="ss-dsh-explre-jb-mn-dv">
      <div class="row">
        <div class="col-lg-12">
          <h2>Explore</h2>
        </div>

        <div class="col-lg-4">
          <div class="ss-dash-explr-job-dv" style="padding:40px !important;">
            <h4>Filters</h4>
            <form method="get" action="{{route('worker.explore')}}" id="filter_form"> @csrf

              <div class="ss-input-slct-grp">
                <label for="cars">Good Work number</label>
                <div class="form-outline">
                  <input type="text" id="gw" class="gw" name="gw" placeholder="Search by Good Work number" value="{{ request('gw') }}">
                </div>
                <div id="gwError" class="text-danger" style="display: none; margin-top: 10px;"></div> <!-- Error message display -->
              </div>
              <div class="ss-input-slct-grp">
                <label for="cars">Profession</label>
                <select name="profession">
                  <option value="">Select</option>
                  @foreach($professions as $v)
                  <option value="{{$v->full_name}}" data-id="{{$v->full_name}}" {{ ($profession == $v->full_name) ? 'selected': ''}}>{{$v->full_name}}</option>
                  @endforeach
                </select>
              </div>

              <div class="ss-input-slct-grp">
                <label>Specialty</label>
                <select name="speciality" id="speciality">
                  <option value="">Select Specialty</option>
                  @foreach($specialities as $speciality)
                  <option value="{{$speciality->full_name}}">{{$speciality->full_name}}</option>
                  @endforeach
                </select>
              </div>



              <div class="ss-input-slct-grp">
                <label>State</label>
                <select name="state" onchange="get_cities(this)">
                  <option value="">Select</option>
                  @foreach ($us_states as $v)
                  <option value="{{ $v->name }}" data-id="{{ $v->id }}"
                    {{ $v->iso2 == $state ? 'selected' : '' }}>
                    {{ $v->name }}({{ $v->iso2 }})
                  </option>
                  @endforeach
                </select>
              </div>

              <div class="ss-input-slct-grp">
                <label>City</label>
                <select name="city" id="city">
                  <option value="">Select</option>
                  @if (!empty($city))
                  <option value="{{ $city }}" selected>{{ $city }}</option>
                  @else
                  <option value="">Select City</option>
                  @endif
                </select>
              </div>


              <div class="ss-jobtype-dv">
                <label>Terms</label>
                <ul class="ks-cboxtags">
                  @foreach ($terms_key as $k => $v)
                  <li><input type="checkbox" name="terms[]" id="checkbox-{{ $k }}"
                      value="{{ $v->title }}"
                      {{ in_array($v->title, $terms) ? 'checked' : '' }}><label
                      for="checkbox-{{ $k }}">{{ $v->title }}</label></li>
                  @endforeach
                </ul>
              </div>


              <div class="ss-explr-datepkr">
                <label>Start Date</label>
                <ul class="ss-date-with">
                  <li><input type="date" value="{{ $start_date }}" name="start_date"
                      placeholder="Start Date"></li>
                </ul>
              </div>
              {{-- <div class="ss-explr-datepkr">
                <label>End Date</label>
                <ul class="ss-date-with">
                  <li><div class="ss-end-date"><input type="date" value="{{$end_date}}" name="end_date" placeholder="End Date">
          </div>
          </li>
          </ul>
        </div> --}}

        <!-----price range------->

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

      <div class="ss-fliter-btn-dv" style="margin-top:50px;">
        <button class="ss-fliter-btn" type="submit">Search</button>
      </div>
      <input type="hidden" name="terms" value="" id="job_type">
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
        <div class="ss-job-prfle-sec" onclick="redirectToJobDetails(`{{ $j->id }}`)">
          {{-- row 1 --}}
          <div class="row">
            <div class="col-10">
              <ul>
                @if(isset($j->profession))
                <li><a href="#"><svg style="vertical-align: sub;"
                      xmlns="http://www.w3.org/2000/svg" width="16"
                      height="16" fill="currentColor" class="bi bi-briefcase"
                      viewBox="0 0 16 16">
                      <path
                        d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5m1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0M1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5" />
                    </svg> {{ $j->profession }}</a></li>
                @endif
                @if(isset($j->specialty))
                <li><a href="#"><svg style="vertical-align: sub;"
                      xmlns="http://www.w3.org/2000/svg" width="16"
                      height="16" fill="currentColor" class="bi bi-gear"
                      viewBox="0 0 16 16">
                      <path
                        d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0" />
                      <path
                        d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z" />
                    </svg> {{ $j->specialty }}</a></li>
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
              @if(isset($j->job_city) && isset($j->job_state))
              <li><a href="#"><img class="icon_cards"
                    src="{{ URL::asset('frontend/img/location.png') }}">
                  {{ $j->job_city }}, {{ $j->job_state }}</a></li>
              @endif
            </ul>
          </div>
          <div class="col-5 d-flex justify-content-end">
            <ul>
              @if(isset($j->preferred_assignment_duration))
              <li><a href="#"><img class="icon_cards"
                    src="{{ URL::asset('frontend/img/calendar.png') }}">
                  {{ $j->preferred_assignment_duration }} wks</a></li>
              @endif
              @if(isset($j->hours_per_week))
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
              @if(isset($j->actual_hourly_rate))
              <li><img class="icon_cards"
                  src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                {{ number_format($j->actual_hourly_rate) }}/hr
              </li>
              @endif
              @if(isset($j->weekly_pay))
              <li><img class="icon_cards"
                  src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                {{ number_format($j->weekly_pay) }}/wk
              </li>
              @endif
              @if(isset($j->weekly_pay))
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
          <div class="col-12 d-flex justify-content-end">
            @if ($j->urgency == 'Auto Offer' || $j->as_soon_as == true)
            <p class="col-2 text-center"
              style="padding-bottom: 0px; padding-top: 8px;">Urgent</p>
            @endif
          </div>
        </div>

        <a href="javascript:void(0)" data-id="{{ $j->id }}"
          onclick="save_jobs(this, event)" class="ss-jb-prfl-save-ico">
          @if ($jobSaved->check_if_saved($j->id))
          <img src="{{ URL::asset('frontend/img/bookmark.png') }}" />
          @else
          <img src="{{ URL::asset('frontend/img/job-icon-bx-Vector.png') }}" />
          @endif
        </a>
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


  <!--------Explore Jobs End------->
  </div>

</main>
@stop

@section('js')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
  function redirectToJobDetails(id) {
    window.location.href = `job/${id}/details`;
  }

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
    $('#slider').slider({
      range: true,
      min: 1000,
      max: 10000,
      step: 1,
      values: [$('#minval').val() ? $('#minval').val() : 3000, $('#maxval').val() ? $('#maxval')
        .val() : 6000
      ],

      slide: function(event, ui) {

        $('#slider .ui-slider-handle:eq(0) .price-range-min').html('$' + ui.values[0]);
        $('#slider .ui-slider-handle:eq(1) .price-range-max').html('$' + ui.values[1]);
        $('#slider .price-range-both').html('<i>$' + ui.values[0] + ' - $' + ui.values[1] +
          '</i>');

        // get values of min and max
        $("#minval").val(ui.values[0]);
        $("#maxval").val(ui.values[1]);

        if (ui.values[0] == ui.values[1]) {
          // alert('kir');
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

    $('#slider3 .ui-slider-handle:eq(1)').append('<span class="price-range-max-3 value">' + $('#slider3').slider('values', 1) + '</span>');

  });
</script>





<script>
  $(document).ready(function() {
    $("#filter_form").submit(function(e) {
      e.preventDefault();

      // Clear previous error message
      $('#gwError').hide().text('');

      // Get the value of the gw input
      var gwValue = $('#gw').val();

      // Validation checks
      // if (gwValue.length > 0 && gwValue[0].toLowerCase() !== 'g') {
      //       // First character should be 'G' or 'g'
      //       $('#gwError').text('The GoodWork Number must start with "G".').show();
      //   } else if (gwValue.length > 1 && gwValue[1].toLowerCase() !== 'w') {
      //       // Second character should be 'W' or 'w'
      //       $('#gwError').text('The GoodWork Number must start with "GW".').show();
      //   } else if (gwValue.length > 2 && gwValue[2].toLowerCase() !== 'j') {
      //       // Third character should be 'J' or 'j'
      //       $('#gwError').text('The GoodWork Number must start with "GWJ".').show();
      //   } else if (gwValue.length > 3 && !/^\d+$/.test(gwValue.slice(3))) {
      //       // After the third character, it should only be numbers
      //       $('#gwError').text('The GoodWork Number must be followed by numbers after "GWJ".').show();
      //   } else {
      // Check if terms input is empty and remove it
      var termsInput = $('input[name="terms"]');
      if (termsInput.length && termsInput.val() === '') {
        termsInput.remove(); // Remove the empty terms input
      }

      // Get all selected checkboxes with the name "categories[]"
      const selectedCategories = $("input[name='terms[]']:checked");

      // Extract the values (category names) and join them into a comma-separated string
      const categoriesString = selectedCategories.map(function() {
        return $(this).val();
      }).get().join('-');
      // Set the categoriesString as the value of the hidden input field
      $("#job_type").val(categoriesString);

      $(this).find("input[name='terms[]']").remove();

      // Change the value of the profession select to the text of the selected option
      const professionSelect = $("select[name='profession']");
      const selectedOptionText = professionSelect.find("option:selected").text();

      // Add a hidden input to the form with the text of the selected option

      $(this).append('<input type="hidden" name="profession_text" value="' + selectedOptionText + '">');


      this.submit(); // Submit the form
      // }
    });
  });
</script>

<style>
  .value {
    left: 0%;
  }
</style>

@stop