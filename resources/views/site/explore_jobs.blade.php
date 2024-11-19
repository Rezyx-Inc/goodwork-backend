@extends('layouts.main')
@section('mytitle', ' For Organizations | Saas')
@section('content')
@section('css')
<link rel='stylesheet' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css'>
@stop



<!-----------------Search for Nursing Jobs--------->
<section class="ss-ex-pg-srch-ban-sec">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <div class="ss-expl-srch-bxhed">
          <h6>Explore Jobs</h6>
          <h2>Search for Nursing Jobs</h2>
          <p>Set your specialty and preferred locations to find the perfect match.</p>

          <div class="input-group">
            <form method="get" action="{{ route('explore-jobs') }}" id="filter_form"> @csrf

              <div class="form-outline">
                <input type="text" id="gw" class="gw" name="gw" placeholder="Search by Good Work number" value="{{ request('gw') }}">
              </div>
              <button type="submit" class="btn ">
                <i class="fas fa-search"></i>
                </button>
              <div id="gwError" class="text-danger" style="display: none; margin-top: 10px;"></div> <!-- Error message display -->

            </form>

          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="ss-expl-pg-bn-ul-sc">
          <ul>
            <li>
              <h6>76+</h6>
            </li>
            <li>
              <p>Nurse <br>Registered</p>
            </li>
          </ul>
  
          <ul>
            <li>
              <h6>125+</h6>
            </li>
            <li>
              <p>jobs <br>Added</p>
            </li>
          </ul>
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
        <div>
          <h5>Filters</h5>
          <!---form--->
          <form method="get" action="{{ route('explore-jobs') }}" id="filter_form">

            <div class="ss-input-slct-grp">
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

            <div class="ss-input-slct-grp">
              <label>Specialty</label>
              <select name="specialty" id="specialty">
                <option value="">Select Specialty</option>
                @foreach ($specialities as $speciality)
                <option value="{{ $speciality->full_name }}">{{ $speciality->full_name }}</option>
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
      <button class="ss-fliter-btn" type="submit">Apply</button>
    </div>
    <input type="hidden" name="terms" value="" id="job_type">
    {{-- <input type="hidden" name="shifts" value="" id="shift"> --}}
    <input type="hidden" name="weekly_pay_from" value="{{ $weekly_pay_from }}" id="minval">
    <input type="hidden" name="weekly_pay_to" value="{{ $weekly_pay_to }}" id="maxval">
    <input type="hidden" name="hourly_pay_from" value="{{ $hourly_pay_from }}" id="hps_minval">
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
                <li><a href="#"><img class="icon_cards"
                      src="{{ URL::asset('frontend/img/specialty.png') }}">
                    {{ $j->specialty }}</a></li>
                <li><a href="#"><img class="icon_cards"
                      src="{{ URL::asset('frontend/img/specialty.png') }}">
                    {{ $j->profession }}</a></li>
              </ul>
            </div>
            <p class="col-2 text-center"><span>+{{ $j->getOfferCount() }} Applied</span></p>
          </div>
          {{-- row 2 --}}
          <div class="row">
            <div class="col-3">
              <ul>
                <li><a href="{{ route('worker_job-details', ['id' => $j->id]) }}"><img
                      class="icon_cards"
                      src="{{ URL::asset('frontend/img/job.png') }}">
                    {{ $j->job_name }}</a></li>
              </ul>
            </div>
          </div>
          {{-- row 3 --}}
          <div class="row">
            <div class="col-9">
              <ul>
                <li><a href="#"><img class="icon_cards"
                      src="{{ URL::asset('frontend/img/facility.png') }}">
                    {{ $j->facility_name ?? 'NA' }}</a></li>
                <li><a href="#"><img class="icon_cards"
                      src="{{ URL::asset('frontend/img/location.png') }}">
                    {{ $j->job_city }}, {{ $j->job_state }}</a></li>
              </ul>
            </div>
            <div class="col-3 d-flex justify-content-end">
              <ul>
                <li><a href="#"><img class="icon_cards"
                      src="{{ URL::asset('frontend/img/calendar.png') }}">
                    {{ $j->preferred_assignment_duration }} wks</a></li>

            </div>
          </div>
          {{-- row 4 --}}
          <div class="row">
            <div class="col-5">
              <ul>
                @if ($j->as_soon_as == false)
                <li>
                  <img class="icon_cards"
                    src="{{ URL::asset('frontend/img/calendar.png') }}"> <a
                    href="#" class="start-date"
                    data-start-date="{{ $j->start_date }}"></a>
                </li>
                @endif
              </ul>
            </div>
            <div class="col-7 d-flex justify-content-end">
              <ul>
                <li><img class="icon_cards"
                    src="{{ URL::asset('frontend/img/dollarcircle.png') }}"> Est.
                  {{ $j->weekly_pay * 4 * 12 }}/year
                </li>
                <li><img class="icon_cards"
                    src="{{ URL::asset('frontend/img/dollarcircle.png') }}">Est.
                  {{ $j->weekly_pay }}/wk
                </li>
                <li><img class="icon_cards"
                    src="{{ URL::asset('frontend/img/dollarcircle.png') }}"> Est.
                  {{ $j->actual_hourly_rate }}/hour
                </li>
              </ul>
            </div>
          </div>


          {{-- row 5 --}}
          <div class="row">
            <div class="col-6">
              <h5>Recently Added</h5>
            </div>
            <div class="col-6 d-flex justify-content-end">
              @if ($j->urgency == 'Auto Offer' || $j->as_soon_as == true)
              <p class="col-2 text-center">Urgent</p>
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
</section>

<!---------------------mobile show----------------->

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

    $('#slider2 .ui-slider-range').append('<span class="price-range-both-2 value"><i>' + $('#slider2')
      .slider('values', 0) + ' - ' + $('#slider2').slider('values', 1) + '</i></span>');

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

    $('#slider3 .ui-slider-range').append('<span class="price-range-both-3 value"><i>' + $('#slider3')
      .slider('values', 0) + ' - ' + $('#slider3').slider('values', 1) + '</i></span>');

    $('#slider3 .ui-slider-handle:eq(0)').append('<span class="price-range-min-3 value">' + $('#slider3')
      .slider('values', 0) + '</span>');

    $('#slider3 .ui-slider-handle:eq(1)').append('<span class="price-range-max-3 value">' + $('#slider3')
      .slider('values', 1) + '</span>');




  });
</script>
<script>
  $(document).ready(function() {
        $("#filter_form").submit(function(e) {
          e.preventDefault(); // Prevent the form from submitting initially

          // Get all selected checkboxes with the name "categories[]"
          const selectedCategories = $("input[name='terms[]']:checked");


          // Extract the values (category names) and join them into a comma-separated string
          const categoriesString = selectedCategories.map(function() {
            return $(this).val();
          }).get().join('-');
          // Set the categoriesString as the value of the hidden input field
          $("#job_type").val(categoriesString);

          // const shiftTypes = $("input[name='shift[]']:checked");
          // const shiftString = shiftTypes.map(function() {
          // 	return $(this).val();
          // }).get().join('-');
          // Set the categoriesString as the value of the hidden input field
          // $("#shift").val(shiftString);
          $(this).find("input[name='terms[]']").remove();
          // $(this).find("input[name='shift[]']").remove();

          // Change the value of the profession select to the text of the selected option
          const professionSelect = $("select[name='profession']");
          const selectedOptionText = professionSelect.find("option:selected").text();

          // Add a hidden input to the form with the text of the selected option
          $(this).append('<input type="hidden" name="profession_text" value="' + selectedOptionText +
            '">');


          // Now, you can submit the form programmatically
          console.log('my form');
          console.log($(this).serializeArray());
          // setInterval(() => {
          //   this.submit();
          // }, 500000);

          this.submit();
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
      //   // First character should be 'G' or 'g'
      //   $('#gwError').text('The GoodWork Number must start with "G".').show();
      // } else if (gwValue.length > 1 && gwValue[1].toLowerCase() !== 'w') {
      //   // Second character should be 'W' or 'w'
      //   $('#gwError').text('The GoodWork Number must start with "GW".').show();
      // } else if (gwValue.length > 2 && gwValue[2].toLowerCase() !== 'j') {
      //   // Third character should be 'J' or 'j'
      //   $('#gwError').text('The GoodWork Number must start with "GWJ".').show();
      // } else if (gwValue.length > 3 && !/^\d+$/.test(gwValue.slice(3))) {
      //   // After the third character, it should only be numbers
      //   $('#gwError').text('The GoodWork Number must be followed by numbers after "GWJ".').show();
      // } else {
      
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

        // Change the value of the profession select to the text of the selected option
        const professionSelect = $("select[name='profession']");
        const selectedOptionText = professionSelect.find("option:selected").text();

        // Add a hidden input to the form with the text of the selected option
        $(this).append('<input type="hidden" name="profession_text" value="' + selectedOptionText + '">');

        // Now, you can submit the form programmatically
        console.log('my form');
        console.log($(this).serializeArray());

        this.submit(); // Submit the form
      // }
    });
  });
</script>

@stop