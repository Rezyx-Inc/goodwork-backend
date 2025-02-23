<!----------------jobs applay view details--------------->

<div class="ss-counter-ofred-mn-div">
    <h4><a href="javascript:void(0)" title="Back" data-id="{{ $model->job_id }}" data-type="offered"
            onclick="fetch_job_content(this)"><img
                src="{{ URL::asset('frontend/img/counter-left-img.png') }}" /></a>Counter Offer</h4>

    <div class="ss-job-view-off-text-fst-dv">
        <p class="mt-3">{{ $recruiter->first_name }} {{ $recruiter->last_name }}
            {{ $recruiter->organization_name && $recruiter->organization_name != '' ? 'on behalf of ' . $recruiter->organization_name : '' }}
            would like to offer job
            {{ $model->job_id }} to you
            with the below terms</p>

    </div>

    <div class="ss-jb-apply-on-disc-txt">
        <h5>Description</h5>
        <p>{!! $jobdetails['description'] !!}</p>
    </div>


    <!-----counter form------>
    <div class="ss-counter-form-mn-dv">
        <form method="post" enctype="multipart/form-data" action="{{ route('post-counter-offer') }}"
            id="counter-offer-form">
            <div class="ss-form-group">
                <label>Job Name</label>
                <input type="text" name="job_name" id="job_name" placeholder="Enter job name"
                    value="{{ $model->job_name }}">
            </div>
            <span class="help-block-job_name"></span>

            <div class="ss-form-group">
                <label>Type</label>
                <select name="type" id="type">
                    <option value="{{ $model->type }}">{{ $model->type }}</option>
                    @if (isset($keywords['Type']))
                        @foreach ($keywords['Type'] as $value)
                            <option
                                value="{{ $value->title }}
      @if ($model->type == $value->title) 'selected' @else '' @endif
        ">
                                {{ $value->title }}
                            </option>
                        @endforeach
                    @endif

                </select>
            </div>
            <span class="help-block-type"></span>
            <div class="ss-form-group">
                <label>Terms</label>
                <select name="terms" id="term">
                    @if (isset($keywords['Terms']))
                        @foreach ($keywords['Terms'] as $value)
                            <option value="{{ $value->title }}"
                                @if ($model->terms == $value->id) 'selected' @else '' @endif>{{ $value->title }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <span class="help-block-term"></span>
            <div class="ss-form-group">
                <h6>Description</h6>
                <input name="description" id="description" placeholder="Enter Job Description" cols="30"
                    rows="2" value="{{ $model->description }}" />
            </div>
            <span class="help-block-description"></span>

            <div class="ss-form-group">
                <label>Profession</label>
                <select name="profession" onchange="get_speciality(this)">
                    <option value="{{ $model->profession }}">{{ $model->profession }}</option>
                    @foreach ($keywords['Profession'] as $k => $v)
                        <option value="{{ $v->title }}" {{ $v->title == $model->type ? 'selected' : '' }}
                            data-id="{{ $v->id }}">{{ $v->title }}</option>
                    @endforeach
                </select>
            </div>
            <span class="help-block-profession"></span>

            <div class="ss-form-group ss-prsnl-frm-specialty">
                <label>Specialty</label>

                <div class="col-md-12">
                    <select name="specialty" class="m-0" id="preferred_specialty">
                        <option value="{{ $model->specialty }}">{{ $model->specialty }}</option>
                        @if (isset($keywords['Speciality']))
                            @foreach ($keywords['Speciality'] as $value)
                                <option value="{{ $value->id }}"> {{ $value->title }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <span class="help-block-preferred_specialty"></span>


            {{-- <div class="ss-count-profsn-lins-dv">
          <ul>
            <li>
              <label>Professional Licensure</label>
            </li>
            <li> <input type="checkbox" id="AutoOffers" name="compact" value="1"> <label for="AutoOffers">Compact</label></li>
          </ul>
          <select name="job_location">
              <option value="">Select</option>
              @foreach ($us_states as $v)
                  <option value="{{$v->iso2}}" {{ ($model->job_location == $v->iso2) ? 'selected' :'' }}>{{$v->iso2}}</option>
              @endforeach
          </select>
        </div> --}}

            {{-- <div class="ss-countr-vacny-imznt">
          <label>Vaccinations & Immunizations name</label>
          <div class="vaccination-content">
          </div>
        </div> --}}

            {{-- <div class="ss-counter-immu-plus-div">
          <ul>
            <li>
               <input type="vaccinations" name="vaccinations" id="vaccination" placeholder="Enter Vacc. or Immu. name">
              <select name="vaccinations" id="vaccination">
                  <option value="">Select</option>
                  @foreach ($keywords['Vaccinations'] as $k => $v)
                  <option value="{{$v->title}}">{{$v->title}}</option>
                  @endforeach
              </select>
            </li>
            <li><div class="ss-prsn-frm-plu-div"><a href="javascript:void(0);" data-type="vac" onclick="add_element(this)"><i class="fa fa-plus" aria-hidden="true"></i></a></div></li>
          </ul>
        </div> --}}


            {{-- <div class="ss-form-group">
          <label>number of references</label>
          <input type="text" name="number_of_references" value="{{$model->number_of_references}}" placeholder="number of references">
        </div>

        <div class="ss-form-group">
          <label>min title of reference</label>
          <input type="text" name="min_title_of_reference" value="{{$model->min_title_of_reference}}" placeholder="min title of reference">
        </div>

        <div class="ss-form-group">
          <label>recency of reference</label>
          <input type="text" name="recency_of_reference" value="{{$model->recency_of_reference}}" placeholder="recency of reference">
        </div>


        <div class="ss-countr-certifctn-dv ss-countr-vacny-imznt">
          <label>Certifications</Label>
          <div class="certifications-content">
          </div>
        </div> --}}

            {{-- <div class="ss-counter-immu-plus-div">
          <ul>
            <li>

              <select name="certificate" id="certificate">
                  <option value="">Select</option>
                  @foreach ($keywords['Skills'] as $k => $v)
                  <option value="{{$v->title}}">{{$v->title}}</option>
                  @endforeach
              </select>
            </li>
            <li><div class="ss-prsn-frm-plu-div"><a  href="javascript:void(0);" data-type="cer" onclick="add_element(this)"><i class="fa fa-plus" aria-hidden="true"></i></a></div></li>
          </ul>
        </div> --}}

            {{-- <div class="ss-form-group">
          <label>Skills checklist</label>
          <select name="skills">
              <option value="">Select</option>
              @foreach ($keywords['Skills'] as $k => $v)
              <option value="{{$v->title}}">{{$v->title}}</option>
              @endforeach
          </select>
        </div> --}}

            {{-- <div class="ss-form-group">
          <label>Urgency</label>
          <input type="text" name="urgency" value="{{$model->urgency}}" placeholder="Enter Urgency ">
        </div>

        <div class="ss-form-group">
          <label># of Positions Available</label>
          <input type="text" name="position_available" value="{{$model->position_available}}" placeholder="Enter # of Positions Available">
        </div> --}}

            {{-- <div class="ss-form-group">
          <label>MSP</label>
          <select name="msp">
              <option value="">Select</option>
              @foreach ($keywords['MSP'] as $k => $v)
              <option value="{{$v->title}}" {{($v->title==$model->msp) ? 'selected' : ''}}>{{$v->title}}</option>
              @endforeach
          </select>
        </div> --}}

            {{-- <div class="ss-form-group">
          <label>VMS</label>
          <select name="vms">
              <option value="">Select</option>
              @foreach ($keywords['VMS'] as $k => $v)
              <option value="{{$v->title}}" {{($v->title==$model->vms) ? 'selected' : ''}}>{{$v->title}}</option>
              @endforeach
          </select>
        </div> --}}

            {{-- <div class="ss-form-group">
          <label># of Submissions in VMS</label>
          <input type="text" name="submission_of_vms" value="{{$model->submission_of_vms}}" placeholder="Enter # of Submissions in VMS">
        </div> --}}

            <div class="ss-form-group">
                <label>Block scheduling</label>
                <select name="block_scheduling">
                    <option value="Yes" {{ $model->block_scheduling == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $model->block_scheduling == 'No' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="ss-form-group">
                <label>Float requirements</label>
                <select name="float_requirement">
                    <option value="Yes" {{ $model->float_requirement == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $model->float_requirement == 'No' ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <span class="help-block-float_requirement"></span>

            <div class="ss-form-group">
                <label>Facility Shift Cancellation Policy</label>
                <select name="facility_shift_cancelation_policy">
                    <option value="{{ $model->facility_shift_cancelation_policy }}">
                        {{ $model->facility_shift_cancelation_policy }}</option>
                    @foreach ($keywords['AssignmentDuration'] as $k => $v)
                        <option value="{{ $v->title }}"
                            {{ $v->title == $model->facility_shift_cancelation_policy ? 'selected' : '' }}>
                            {{ $v->title }}</option>
                    @endforeach
                </select>
            </div>
            <span class="help-block-facility_shift_cancelation_policy"></span>
            {{-- <div class="ss-form-group">
          <label>Contract Termination Policy</label>
          <select name="contract_termination_policy">
              <option value="">Select</option>
              @foreach ($keywords['ContractTerminationPolicy'] as $k => $v)
              <option value="{{$v->title}}" {{($v->title==$model->contract_termination_policy) ? 'selected' : ''}}>{{$v->title}}</option>
              @endforeach
          </select>
        </div> --}}

            <div class="ss-form-group">
                <label>Contract Termination Policy</label>
                <input type="text" id="contract_termination_policy" name="contract_termination_policy"
                    placeholder="Enter Contract Termination Policy" value="{{ $model->contract_termination_policy }}">
            </div>
            <span class="help-block-contract_termination_policy"></span>

            <div class="ss-form-group">
                <label>Traveler Distance From Facility</label>
                <input type="text" name="traveler_distance_from_facility"
                    value="{{ $model->traveler_distance_from_facility }}"
                    placeholder="Enter Traveler Distance From Facility">
            </div>

            {{-- <div class="ss-form-group">
          <label>Facility</label>
          <input type="text" name="facility_id" value="{{$model->facility}}" placeholder="Enter Facility">
        </div> --}}

            <div class="ss-form-group">
                <label>Clinical Setting</label>
                <input type="text" id="clinical_setting" name="clinical_setting" placeholder="Enter clinical setting"
                    value="{{ $model->clinical_setting }}
      ">
            </div>
            <span class="help-block-clinical_setting"></span>

            <div class="ss-form-group">
                <label>Patient ratio</label>
                <input type="text" name="Patient_ratio" value="{{ $model->Patient_ratio }}"
                    placeholder="How many patients can you handle?">
            </div>

            <div class="ss-form-group">
                <label>EMR</label>
                <input type="text" name="emr" value="{{ $model->emr }}"
                    placeholder="What EMRs have you used?">
            </div>

            <div class="ss-form-group">
                <label>Unit</label>
                <input type="text" name="Unit" value="{{ $model->Unit }}" placeholder="Enter Unit">
            </div>


            <div class="ss-form-group">
                <label>Scrub Color</label>
                <input type="text" name="scrub_color" value="{{ $model->scrub_color }}"
                    placeholder="Enter Scrub Color">
            </div>

            <div class="ss-form-group">
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-md-12 col-xs-12">
                        <label>Start Date</label>
                    </div>
                    <div class="row col-lg-6 col-sm-12 col-md-12 col-xs-12"
                        style="display: flex; justify-content: end;">
                        <input id="as_soon_as" name="as_soon_as" value="1" type="checkbox"
                            style="box-shadow:none; width:auto;" class="col-6">
                        <label class="col-6">
                            As soon As possible
                        </label>
                    </div>
                </div>
                <input id="start_date" type="date" min="2024-03-06" name="start_date" placeholder="Select Date"
                    value="2024-03-06">
            </div>
            <span class="help-block-start_date"></span>

            <div class="ss-form-group">
                <label>RTO</label>
                <input type="text" name="rto" value="{{ $model->rto }}" placeholder="Enter RTO">
            </div>

            {{-- <div class="ss-form-group">
          <label>Shift Time of Day</label>
          <select name="preferred_shift" id="shift-of-day">
          <option value="{{$model->preferred_shift}}">{{$model->preferred_shift}}</option>
  @if (isset($keywords['PreferredShift']))
      @foreach ($keywords['PreferredShift'] as $value)
          <option value="{{$value->id}}"
            @if ($model->preferred_shift == $value->id)
              'selected'
            @else
            ''> {{$value->title}}
            @endif
            </option>
      @endforeach
  @endif
          </select>
      </div>
      <span class="help-block-shift-of-day"></span> --}}

            <div class="ss-form-group">
                <label>Hours/Week</label>
                <input type="text" name="hours_per_week" value="{{ $model->hours_per_week }}"
                    placeholder="Enter Hours/Week">
            </div>

            <div class="ss-form-group">
                <label>Guaranteed Hours</label>
                <input type="text" name="guaranteed_hours" value="{{ $model->guaranteed_hours }}"
                    placeholder="Enter Guaranteed Hours">
            </div>

            <div class="ss-form-group">
                <label>Hours/Shift</label>
                <input type="text" name="hours_shift" value="{{ $model->hours_shift }}"
                    placeholder="Enter Hours/Shift">
            </div>

            <div class="ss-form-group">
                <label>Weeks/Assignment</label>
                <input type="text" name="preferred_assignment_duration"
                    value="{{ $model->preferred_assignment_duration }}" placeholder="Enter Hours/Shift">
            </div>

            <div class="ss-form-group">
                <label>Shifts/Week</label>
                <input type="text" name="weeks_shift" value="{{ $model->weeks_shift }}"
                    placeholder="Enter Shifts/Week">
            </div>

            <div class="ss-form-group">
                <label>Referral Bonus</label>
                <input type="text" name="referral_bonus" value="{{ $model->referral_bonus }}"
                    placeholder="Enter Referral Bonus">
            </div>

            <div class="ss-form-group">
                <label>Sign-On Bonus</label>
                <input type="text" name="sign_on_bonus" value="{{ $model->sign_on_bonus }}"
                    placeholder="Enter Sign-On Bonus">
            </div>

            <div class="ss-form-group">
                <label>Completion Bonus</label>
                <input type="text" name="completion_bonus" value="{{ $model->completion_bonus }}"
                    placeholder="Enter Completion Bonus">
            </div>

            <div class="ss-form-group">
                <label>Extension Bonus</label>
                <input type="text" name="extension_bonus" value="{{ $model->extension_bonus }}"
                    placeholder="Enter Extension Bonus">
            </div>

            <div class="ss-form-group">
                <label>Other Bonus</label>
                <input type="text" name="other_bonus" value="{{ $model->other_bonus }}"
                    placeholder="Enter Other Bonus">
            </div>

            <div class="ss-form-group">
                <label>401K</label>
                <select name="four_zero_one_k" id="401k">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <span class="help-block-401k"></span>

            <div class="ss-form-group">
                <label>Health Insurance</label>
                <select name="health_insaurance" id="health-insurance">

                    <option value="{{ $model->health_insaurance }}">
                        @if ($model->health_insaurance == '1')
                            Yes
                        @else
                            No
                        @endif
                    </option>
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                    </option>
                </select>
            </div>
            <span class="help-block-health-insurance"></span>

            <div class="ss-form-group">
                <label>Dental</label>
                <select name="dental" id="dental">
                    <option value="{{ $model->dental }}">
                        @if ($model->dental == '1')
                            Yes
                        @else
                            No
                        @endif
                    </option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <span class="help-block-dental"></span>

            <div class="ss-form-group">
                <label>Vision</label>
                <select name="vision" id="vision">
                    <option value="{{ $model->vision }}">
                        @if ($model->vision == '1')
                            Yes
                        @else
                            No
                        @endif
                    </option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <span class="help-block-vision"></span>

            <div class="ss-form-group">
                <label>Actual Hourly rate</label>
                <input type="text" name="actual_hourly_rate" value="{{ $model->actual_hourly_rate }}"
                    placeholder="Enter Actual Rate">
            </div>



            <div class="ss-form-group">
                <label>Overtime</label>
                <input type="text" name="overtime" value="{{ $model->overtime }}" placeholder="Enter Overtime">
            </div>

            <div class="ss-form-group">
                <label>Holiday</label>
                <input id="holiday" type="text" name="holiday" placeholder="Select Dates"
                    value="{{ $model->holiday }}
        ">
            </div>
            <span class="help-block-holiday"></span>

            <div class="ss-form-group">
                <label>On Call</label>
                <select name="on_call">
                    <option value="Yes" {{ $model->on_call == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $model->on_call == 'No' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="ss-form-group">
                <label>Call Back</label>
                <select name="call_back">
                    <option value="Yes" {{ $model->call_back == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $model->call_back == 'No' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="ss-form-group">
                <label>Orientation Rate</label>
                <input type="text" name="orientation_rate" value="{{ $model->orientation_rate }}"
                    placeholder="Enter Orientation Rate">
            </div>

            <div class="ss-form-group">
                <label>Weekly Taxable amount</label>
                <input type="number" name="weekly_taxable_amount" value="{{ $model->weekly_taxable_amount }}"
                    placeholder="---">
            </div>

            <div class="ss-form-group">
                <label>Weekly non-taxable amount</label>
                <input type="number" name="weekly_non_taxable_amount"
                    value="{{ $model->weekly_non_taxable_amount }}" placeholder="---">
            </div>

            <div class="ss-form-group">
                <label>Organization Weekly Amount</label>
                <input type="number" name="organization_weekly_amount"
                    value="{{ $model->organization_weekly_amount }}" placeholder="---">
            </div>

            <div class="ss-form-group">
                <label>Goodwork Weekly Amount</label>
                <input type="number" name="weekly_non_taxable_amount"
                    value="{{ $model->weekly_non_taxable_amount }}" placeholder="---">
            </div>

            <div class="ss-form-group">
                <label>Total Organization Amount</label>
                <input type="text" name="total_organization_amount"
                    value="{{ $model->weekly_non_taxable_amount }}" id="Total Organization Amount"
                    name="Total Organization Amount" placeholder="---">
            </div>

            <div class="ss-form-group">
                <label>Total Goodwork Amount</label>
                <input type="text" name="weekly_non_taxable_amount"
                    value="{{ $model->weekly_non_taxable_amount }}" placeholder="---">
            </div>

            <div class="ss-form-group">
                <label>Total Contract Amount</label>
                <input type="text" name="weekly_non_taxable_amount"
                    value="{{ $model->weekly_non_taxable_amount }}" placeholder="---">
            </div>

            <div class="ss-form-group">
                <label>Goodwork Number</label>
                <input type="text" name="weekly_non_taxable_amount"
                    value="{{ $model->weekly_non_taxable_amount }}" placeholder="Unique Key">
            </div>

            <div class="ss-counter-buttons-div">
                <button class="ss-counter-button" onclick="store_counter_offer(this)">Counter Offer</button>
                <button class="counter-save-for-button">Save for Later</button>
            </div>
            <input type="hidden" name="jobid" value="{{ $model->job_id }}">
            <input type="hidden" name="offer_id" value="{{ $model->id }}">
            <input type="hidden" name="user_id" value="{{ $model->worker_user_id }}">
        </form>
    </div>

    @php
        $specialty = [];
        $experience = [];
        $vaccinations = [];
        $certificate = [];
        if (!empty($model->preferred_specialty)) {
            $specialty = explode(',', $model->preferred_specialty);
        }
        if (!empty($model->preferred_experience)) {
            $experience = explode(',', $model->preferred_experience);
        }
        if (!empty($model->vaccinations)) {
            $vaccinations = explode(',', $model->vaccinations);
        }
        if (!empty($model->certificate)) {
            $certificate = explode(',', $model->certificate);
        }
    @endphp
    <script>
        var speciality = {};
        var vac_content = [];
        var cer_content = [];


        var dynamic_elements = {
            vac: {
                id: '#vaccination',
                name: 'Vaccination',
                listing_class: '.vaccination-content',
                items: vac_content
            },
            cer: {
                id: '#certificate',
                name: 'Certificate',
                listing_class: '.certifications-content',
                items: cer_content
            }
        }


        function add_element(obj) {
            const type = $(obj).data('type');
            if (dynamic_elements.hasOwnProperty(type)) {
                let element, id, value, name;
                element = dynamic_elements[type];
                id = element.id;
                name = element.name;
                value = $(id).val();

                if (!value) {
                    notie.alert({
                        type: 'error',
                        text: '<i class="fa fa-check"></i> Select the ' + name + ' please.',
                        time: 3
                    });
                } else {
                    if (!element.items.includes(value)) {
                        element.items.push($(id).val());
                        console.log(element.items);
                        list_elements(type);
                    }
                    $(id).val('');
                }
            }
        }

        function remove_element(obj) {
            const type = $(obj).data('type');
            if (dynamic_elements.hasOwnProperty(type)) {
                let element = dynamic_elements[type];

                if (element.items.includes($(obj).data('key'))) {
                    const elementToRemove = $(obj).data('key');
                    const newArray = element.items.filter(item => item !== elementToRemove);
                    element.items = newArray;
                    // $(obj).parent().parent().parent().remove();
                    list_elements(type);
                    console.log(element.items);
                }
            }

        }

        function list_elements(type) {
            if (dynamic_elements.hasOwnProperty(type)) {
                const element = dynamic_elements[type];
                if (element.items.length) {
                    str = '';
                    element.items.forEach(function(value, index) {
                        str += '<ul>';
                        str += '<li>';
                        str += '<p>' + value + '</p>';
                        str += '</li>';
                        str += '<li><a href="#" data-type="' + type + '" data-key="' + value +
                            '" onclick="remove_element(this)"><img src="{{ URL::asset('frontend/img/delete-img.png') }}"/></a></li>';
                        str += '</ul>';

                    });
                    $(element.listing_class).html(str);
                }
            }
        }

        function add_speciality(obj) {
            if (!$('#speciality').val()) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Select the speciality please.',
                    time: 3
                });
            } else if (!$('#experience').val()) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Enter total year of experience.',
                    time: 3
                });
            } else {
                if (!speciality.hasOwnProperty($('#speciality').val())) {
                    speciality[$('#speciality').val()] = $('#experience').val();
                    $('#experience').val('');
                    $('#speciality').val('');
                    list_specialities();
                }
                console.log(speciality);
            }
        }

        function remove_speciality(obj) {
            if (speciality.hasOwnProperty($(obj).data('key'))) {
                delete speciality[$(obj).data('key')];
                // $(obj).parent().parent().parent().remove();
                list_specialities();
                console.log(speciality);
            }
        }

        function list_specialities() {
            // $('.speciality-content').empty();
            var str = '';
            for (const key in speciality) {
                if (speciality.hasOwnProperty(key)) {
                    const value = speciality[key];
                    str += '<ul>';
                    str += '<li>' + key + '</li>';
                    str += '<li>' + value + ' Years</li>';
                    str += '<li><button type="button" data-key="' + key + '" onclick="remove_speciality(this)"><img src="' +
                        full_path + 'public/frontend/img/delete-img.png' + '" /></button></li>';
                    str += '</ul>';
                }
            }
            $('.speciality-content').html(str);
        }


        $(document).ready(function() {
            //list_specialities();
            list_elements('vac');
            list_elements('cer');
            get_speciality($('select[name="profession"]'), false);
            $('#counter-offer-form').on('submit', function(event) {
                var form = $(this);
                $('.help-block').html('').closest('.has-error').removeClass('has-error');
                event.preventDefault();
                ajaxindicatorstart();
                const url = form.attr('action');
                const specialities = Object.keys(speciality).join(',');
                const experiences = Object.values(speciality).join(',');
                const vaccinations = dynamic_elements.vac.items.join(',');
                const certificate = dynamic_elements.cer.items.join(',');
                var data = new FormData($(this)[0]);

                data.set('preferred_specialty', specialities);
                data.set('preferred_experience', experiences);
                data.set('vaccinations', vaccinations);
                data.set('certificate', certificate);
                console.log(data);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(resp) {
                        console.log(resp);
                        ajaxindicatorstop();
                        if (resp.success) {
                            notie.alert({
                                type: 'success',
                                text: '<i class="fa fa-check"></i> ' + resp.msg,
                                time: 3
                            });
                            // setTimeout(() => {
                            //     window.location.href = $('#skip-button').data('href');
                            // }, 3000);
                        }
                    },
                    error: function(resp) {
                        ajaxindicatorstop();
                        console.log(resp);
                        $.each(resp.responseJSON.errors, function(key, val) {
                            console.log(val[0]);
                            form.find('[name="' + key + '"]').closest('.ss-form-group')
                                .find('.help-block').html(val[0]);
                            form.find('[name="' + key + '"]').closest('.ss-form-group')
                                .addClass('has-error');
                        });
                    }
                })
            });

        });
    </script>
