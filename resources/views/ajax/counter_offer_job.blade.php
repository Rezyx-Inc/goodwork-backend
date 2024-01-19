<!----------------jobs applay view details--------------->

<div class="ss-counter-ofred-mn-div">
    <h4><a href="javascript:void(0)" title="Back"  data-id="{{$model->id}}" data-type="offered" onclick="fetch_job_content(this)"><img src="{{URL::asset('frontend/img/counter-left-img.png')}}" /></a> Counter Offer</h4>

    <div class="ss-job-view-off-text-fst-dv">
        <p>On behalf of <a href="">Albus Percival , Hogwarts</a> would like to offer <a href="#">GWJ234065</a> to <a href="#">James Bond</a> with the following terms. This offer is only available for the next <a hre="#">6 weeks:</a></p>
    </div>

<div class="ss-jb-apply-on-disc-txt">
    <h5>Description</h5>
    <p>This position is accountable and responsible for nursing care administered under the direction of a Registered Nurse (Nurse Manager, Charge Nurse, and/or Staff Nurse). Nurse interns must utilize personal protective equipment such as gloves, gown, mask. <a href="#">Read More</a></p>
</div>


<!-----counter form------>
<div class="ss-counter-form-mn-dv">
    <form method="post" enctype="multipart/form-data" action="{{route('post-counter-offer')}}" id="counter-offer-form">
        <div class="ss-form-group">
            <label>Type</label>
            <select name="type">
              <option value="">Select</option>
              @foreach($keywords['Type'] as $k=>$v)
              <option value="{{$v->title}}" {{ ($v->title == $model->type) ? 'selected' : ''}}>{{$v->title}}</option>
              @endforeach
            </select>
          </div>

           <div class="ss-form-group">
            <label>Terms</label>
            <select name="terms">
                <option value="">Select</option>
                @foreach($keywords['Terms'] as $k=>$v)
                <option value="{{$v->title}}" {{ ($v->title == $model->terms) ? 'selected' : ''}}>{{$v->title}}</option>
                @endforeach
            </select>
          </div>

           <div class="ss-form-group">
            <label>Profession</label>
            <select name="profession"  onchange="get_speciality(this)">
                <option value="">Select</option>
                @foreach($keywords['Profession'] as $k=>$v)
                <option value="{{$v->title}}" {{ ($v->title == $model->type) ? 'selected' : ''}} data-id="{{$v->id}}">{{$v->title}}</option>
                @endforeach
            </select>
          </div>

          <div class="ss-counter-form-uplpls-dv">
            <label>Specialty and Experience</label>
            <div class="ss-speilty-exprnc-add-list speciality-content">
             </div>
            <ul>
                <li>
                    <div class="ss-form-group">
                    <select name="speciality" id="speciality" class="ss-form-group">
                        <option value="">Select Specialty</option>
                    </select>
                    </div>
                </li>
                <li>
                    <div class="ss-form-group">
                        <input type="text"  name="experience" id="experience" placeholder="Enter Experience in years">
                    </div>

                 </li>
                 <li><div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="add_speciality(this)"><i class="fa fa-plus" aria-hidden="true"></i></a></div></li>
             </ul>
          </div>

          <div class="ss-count-profsn-lins-dv">
            <ul>
              <li>
                <label>Professional Licensure</label>
              </li>
              <li> <input type="checkbox" id="AutoOffers" name="compact" value="1"> <label for="AutoOffers">Compact</label></li>
            </ul>
            <select name="job_location">
                <option value="">Select</option>
                @foreach($us_states as $v)
                    <option value="{{$v->iso2}}" {{ ($model->job_location == $v->iso2) ? 'selected' :'' }}>{{$v->iso2}}</option>
                @endforeach
            </select>
          </div>

          <div class="ss-countr-vacny-imznt">
            <label>Vaccinations & Immunizations name</label>
            <div class="vaccination-content">
            </div>
          </div>

          <div class="ss-counter-immu-plus-div">
            <ul>
              <li>
                {{-- <input type="vaccinations" name="vaccinations" id="vaccination" placeholder="Enter Vacc. or Immu. name"> --}}
                <select name="vaccinations" id="vaccination">
                    <option value="">Select</option>
                    @foreach($keywords['Vaccinations'] as $k=>$v)
                    <option value="{{$v->title}}">{{$v->title}}</option>
                    @endforeach
                </select>
              </li>
              <li><div class="ss-prsn-frm-plu-div"><a href="javascript:void(0);" data-type="vac" onclick="add_element(this)"><i class="fa fa-plus" aria-hidden="true"></i></a></div></li>
            </ul>
          </div>


          <div class="ss-form-group">
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
          </div>

          <div class="ss-counter-immu-plus-div">
            <ul>
              <li>

                <select name="certificate" id="certificate">
                    <option value="">Select</option>
                    @foreach($keywords['Skills'] as $k=>$v)
                    <option value="{{$v->title}}">{{$v->title}}</option>
                    @endforeach
                </select>
              </li>
              <li><div class="ss-prsn-frm-plu-div"><a  href="javascript:void(0);" data-type="cer" onclick="add_element(this)"><i class="fa fa-plus" aria-hidden="true"></i></a></div></li>
            </ul>
          </div>

          <div class="ss-form-group">
            <label>Skills checklist</label>
            <select name="skills">
                <option value="">Select</option>
                @foreach($keywords['Skills'] as $k=>$v)
                <option value="{{$v->title}}">{{$v->title}}</option>
                @endforeach
            </select>
          </div>

          <div class="ss-form-group">
            <label>Urgency</label>
            <input type="text" name="urgency" value="{{$model->urgency}}" placeholder="Enter Urgency ">
          </div>

          <div class="ss-form-group">
            <label># of Positions Available</label>
            <input type="text" name="position_available" value="{{$model->position_available}}" placeholder="Enter # of Positions Available">
          </div>

          <div class="ss-form-group">
            <label>MSP</label>
            <select name="msp">
                <option value="">Select</option>
                @foreach($keywords['MSP'] as $k=>$v)
                <option value="{{$v->title}}" {{($v->title==$model->msp) ? 'selected' : ''}}>{{$v->title}}</option>
                @endforeach
            </select>
          </div>

          <div class="ss-form-group">
            <label>VMS</label>
            <select name="vms">
                <option value="">Select</option>
                @foreach($keywords['VMS'] as $k=>$v)
                <option value="{{$v->title}}" {{($v->title==$model->vms) ? 'selected' : ''}}>{{$v->title}}</option>
                @endforeach
            </select>
          </div>

          <div class="ss-form-group">
            <label># of Submissions in VMS</label>
            <input type="text" name="submission_of_vms" value="{{$model->submission_of_vms}}" placeholder="Enter # of Submissions in VMS">
          </div>

          <div class="ss-form-group">
            <label>Block scheduling</label>
            <select name="block_scheduling">
                <option value="Yes" {{($model->block_scheduling == 'Yes') ? 'selected' : ''}}>Yes</option>
                <option value="No" {{($model->block_scheduling == 'No') ? 'selected' : ''}}>No</option>
            </select>
          </div>

          <div class="ss-form-group">
            <label>Float requirements</label>
            <select name="float_requirement">
                <option value="Yes" {{($model->float_requirement == 'Yes') ? 'selected' : ''}}>Yes</option>
                <option value="No" {{($model->float_requirement == 'No') ? 'selected' : ''}}>No</option>
            </select>
          </div>

          <div class="ss-form-group">
            <label>Facility Shift Cancellation Policy</label>
            <select name="facility_shift_cancelation_policy">
                <option value="">Select</option>
                @foreach($keywords['AssignmentDuration'] as $k=>$v)
                <option value="{{$v->title}}" {{($v->title==$model->facility_shift_cancelation_policy) ? 'selected' : ''}}>{{$v->title}}</option>
                @endforeach
            </select>
          </div>

          <div class="ss-form-group">
            <label>Contract Termination Policy</label>
            <select name="contract_termination_policy">
                <option value="">Select</option>
                @foreach($keywords['ContractTerminationPolicy'] as $k=>$v)
                <option value="{{$v->title}}" {{($v->title==$model->contract_termination_policy) ? 'selected' : ''}}>{{$v->title}}</option>
                @endforeach
            </select>
          </div>

          <div class="ss-form-group">
            <label>Traveler Distance From Facility</label>
            <input type="text" name="traveler_distance_from_facility" value="{{$model->traveler_distance_from_facility}}" placeholder="Enter Traveler Distance From Facility">
          </div>

          <div class="ss-form-group">
            <label>Facility</label>
            <input type="text" name="facility_id" value="{{$model->facility_id}}" placeholder="Enter Facility">
          </div>

          <div class="ss-form-group">
            <label>Facility's Parent System</label>
            <input type="text" name="facilitys_parent_system" value="{{$model->facilitys_parent_system}}" placeholder="Enter Facility's Parent System">
          </div>

          <div class="ss-form-group">
            <label>Facility Average Rating</label>
            <input type="text" name="facility_average_rating" value="{{$model->facility_average_rating}}" placeholder="Enter Facility Average Rating">
          </div>

          <div class="ss-form-group">
            <label>Recruiter Average Rating</label>
            <input type="text" name="recruiter_average_rating" value="{{$model->recruiter_average_rating}}" placeholder="Enter Recruiter Average Rating">
          </div>

          <div class="ss-form-group">
            <label>Employer Average Rating</label>
            <input type="text" name="employer_average_rating" value="{{$model->employer_average_rating}}" placeholder="Enter Employer Average Rating">
          </div>

          <div class="ss-form-group">
            <label>Clinical Setting</label>
            <select name="clinical_setting">
                <option value="">Select</option>
                @foreach($keywords['SettingType'] as $k=>$v)
                <option value="{{$v->title}}" {{($v->title==$model->clinical_setting) ? 'selected' : ''}}>{{$v->title}}</option>
                @endforeach
            </select>
          </div>

          <div class="ss-form-group">
            <label>Patient ratio</label>
            <input type="text" name="Patient_ratio" value="{{$model->Patient_ratio}}" placeholder="How many patients can you handle?">
          </div>

          <div class="ss-form-group">
            <label>EMR</label>
            <input type="text" name="emr" value="{{$model->emr}}" placeholder="What EMRs have you used?">
          </div>

          <div class="ss-form-group">
            <label>Unit</label>
            <input type="text" name="unit" value="{{$model->unit}}" placeholder="Enter Unit">
          </div>

          <div class="ss-form-group">
            <label>Department</label>
            <input type="text" name="department" value="{{$model->department}}" placeholder="Enter Department">
          </div>

           <div class="ss-form-group">
            <label>Bed Size</label>
            <input type="text" name="Bed_Size" value="{{$model->Bed_Size}}" placeholder="Enter Bed Size">
          </div>

           <div class="ss-form-group">
            <label>Trauma Level</label>
            <input type="text" name="Trauma_Level" value="{{$model->Trauma_Level}}" placeholder="Enter Trauma Level">
          </div>

           <div class="ss-form-group">
            <label>Scrub Color</label>
            <input type="text" name="scrub_color" value="{{$model->scrub_color}}" placeholder="Enter Scrub Color">
          </div>

          <div class="ss-count-strt-dte ss-count-profsn-lins-dv">
            <ul>
              <li>
                <label>Start Date</label>
              </li>
              <li> <input type="checkbox" id="AutoOffers" name="as_soon_as" value="1"> <label for="AutoOffers">As soon As Posible</label></li>
            </ul>
            <input type="date"  name="start_date" placeholder="Select Date">
          </div>

           <div class="ss-form-group">
            <label>RTO</label>
            <input type="text" name="rto" value="{{$model->rto}}" placeholder="Enter RTO">
          </div>

          <div class="ss-form-group">
            <label>Shift Time of Day</label>
            <select name="preferred_shift" >
                <option value="">Select</option>
                @foreach($keywords['shift_time_of_day'] as $k=>$v)
                <option value="{{$v->title}}" {{($v->title==$model->preferred_shift) ? 'selected' : ''}}>{{$v->title}}</option>
                @endforeach
            </select>
          </div>

          <div class="ss-form-group">
            <label>Hours/Week</label>
            <input type="text" name="hours_per_week" value="{{$model->hours_per_week}}" placeholder="Enter Hours/Week">
          </div>

          <div class="ss-form-group">
            <label>Guaranteed Hours</label>
            <input type="text" name="guaranteed_hours" value="{{$model->guaranteed_hours}}" placeholder="Enter Guaranteed Hours">
          </div>

          <div class="ss-form-group">
            <label>Hours/Shift</label>
            <input type="text" name="hours_shift" value="{{$model->hours_shift}}" placeholder="Enter Hours/Shift">
          </div>

          <div class="ss-form-group">
            <label>Weeks/Assignment</label>
            <input type="text" name="preferred_assignment_duration" value="{{$model->preferred_assignment_duration}}" placeholder="Enter Hours/Shift">
          </div>

          <div class="ss-form-group">
            <label>Shifts/Week</label>
            <input type="text" name="weeks_shift" value="{{$model->weeks_shift}}" placeholder="Enter Shifts/Week">
          </div>

          <div class="ss-form-group">
            <label>Referral Bonus</label>
            <input type="text" name="referral_bonus" value="{{$model->referral_bonus}}" placeholder="Enter Referral Bonus">
          </div>

          <div class="ss-form-group">
            <label>Sign-On Bonus</label>
            <input type="text" name="sign_on_bonus" value="{{$model->sign_on_bonus}}" placeholder="Enter Sign-On Bonus">
          </div>

          <div class="ss-form-group">
            <label>Completion Bonus</label>
            <input type="text" name="completion_bonus" value="{{$model->completion_bonus}}" placeholder="Enter Completion Bonus">
          </div>

          <div class="ss-form-group">
            <label>Extension Bonus</label>
            <input type="text" name="extension_bonus" value="{{$model->extension_bonus}}" placeholder="Enter Extension Bonus">
          </div>

          <div class="ss-form-group">
            <label>Other Bonus</label>
            <input type="text" name="other_bonus" value="{{$model->other_bonus}}" placeholder="Enter Other Bonus">
          </div>

          <div class="ss-form-group">
            <label>401K</label>
            <select name="four_zero_one_k">
                <option value="">Select</option>
                @foreach($keywords['401k'] as $k=>$v)
                <option value="{{$v->title}}" {{($v->title==$model->four_zero_one_k) ? 'selected' : ''}}>{{$v->title}}</option>
                @endforeach
            </select>
          </div>

          <div class="ss-form-group">
            <label>Health Insurance</label>
            <select name="health_insaurance">
                <option value="">Select</option>
                @foreach($keywords['HealthInsurance'] as $k=>$v)
                <option value="{{$v->title}}" {{($v->title==$model->health_insaurance) ? 'selected' : ''}}>{{$v->title}}</option>
                @endforeach
            </select>
          </div>

          <div class="ss-form-group">
            <label>Dental</label>
            <select name="dental">
                <option value="">Select</option>
                @foreach($keywords['Dental'] as $k=>$v)
                <option value="{{$v->title}}" {{($v->title==$model->dental) ? 'selected' : ''}}>{{$v->title}}</option>
                @endforeach
            </select>
          </div>

          <div class="ss-form-group">
            <label>Vision</label>
            <select name="vision">
                <option value="">Select</option>
                @foreach($keywords['Vision'] as $k=>$v)
                <option value="{{$v->title}}" {{($v->title==$model->vision) ? 'selected' : ''}}>{{$v->title}}</option>
                @endforeach
            </select>
          </div>

          <div class="ss-form-group">
            <label>Actual Hourly rate</label>
            <input type="text" name="actual_hourly_rate" value="{{$model->actual_hourly_rate}}" placeholder="Enter Actual Rate">
          </div>

          <div class="ss-form-group">
            <label>Feels Like $/hrs</label>
            <input type="text" name="feels_like_per_hour" value="{{$model->feels_like_per_hour}}" placeholder="---">
          </div>

          <div class="ss-form-group">
            <label>Overtime</label>
            <input type="text" name="overtime" value="{{$model->overtime}}" placeholder="Enter Overtime">
          </div>

          <div class="ss-form-group">
            <label>Holiday</label>
            <input type="date" id="birthday" name="holiday">
          </div>

          <div class="ss-form-group">
            <label>On Call</label>
            <select name="on_call">
                <option value="Yes" {{($model->on_call == 'Yes') ? 'selected' : ''}}>Yes</option>
                <option value="No" {{($model->on_call == 'No') ? 'selected' : ''}}>No</option>
            </select>
          </div>

          <div class="ss-form-group">
            <label>Call Back</label>
            <select name="call_back">
                <option value="Yes" {{($model->call_back == 'Yes') ? 'selected' : ''}}>Yes</option>
                <option value="No" {{($model->call_back == 'No') ? 'selected' : ''}}>No</option>
            </select>
          </div>

          <div class="ss-form-group">
            <label>Orientation Rate</label>
            <input type="text" name="orientation_rate" value="{{$model->orientation_rate}}" placeholder="Enter Orientation Rate">
          </div>

          <div class="ss-form-group">
            <label>Weekly Taxable amount</label>
            <input type="text" name="weekly_taxable_amount" value="{{$model->weekly_taxable_amount}}" placeholder="---">
          </div>

          <div class="ss-form-group">
            <label>Weekly non-taxable amount</label>
            <input type="text" name="weekly_non_taxable_amount" value="{{$model->weekly_non_taxable_amount}}" placeholder="---">
          </div>

          <div class="ss-form-group">
            <label>Employer Weekly Amount</label>
            <input type="text" name="weekly_non_taxable_amount" value="{{$model->weekly_non_taxable_amount}}" placeholder="---">
          </div>

          <div class="ss-form-group">
            <label>Goodwork Weekly Amount</label>
            <input type="text" name="weekly_non_taxable_amount" value="{{$model->weekly_non_taxable_amount}}" placeholder="---">
          </div>

          <div class="ss-form-group">
            <label>Total Employer Amount</label>
            <input type="text" name="weekly_non_taxable_amount" value="{{$model->weekly_non_taxable_amount}}" id="Total Employer Amount" name="Total Employer Amount" placeholder="---">
          </div>

          <div class="ss-form-group">
            <label>Total Goodwork Amount</label>
            <input type="text" name="weekly_non_taxable_amount" value="{{$model->weekly_non_taxable_amount}}" placeholder="---">
          </div>

          <div class="ss-form-group">
            <label>Total Contract Amount</label>
            <input type="text" name="weekly_non_taxable_amount" value="{{$model->weekly_non_taxable_amount}}" placeholder="---">
          </div>

          <div class="ss-form-group">
            <label>Goodwork Number</label>
            <input type="text" name="weekly_non_taxable_amount" value="{{$model->weekly_non_taxable_amount}}" placeholder="Unique Key">
          </div>

         <div class="ss-counter-buttons-div">
           <button class="ss-counter-button">Counter Offer</button>
           <button class="counter-save-for-button">Save for Later</button>
         </div>
         <input type="hidden" name="jobid" value="{{$model->id}}">
         <input type="hidden" name="user_id" value="{{$model->id}}">
</form>
</div>

@php
$specialty = [];
$experience = [];
$vaccinations = [];
$certificate = [];
if(!empty($model->preferred_specialty))
{
    $specialty = explode(',', $model->preferred_specialty);
}
if(!empty($model->preferred_experience))
{
    $experience = explode(',', $model->preferred_experience);
}
if(!empty($model->vaccinations))
{
    $vaccinations = explode(',', $model->vaccinations);
}
if(!empty($model->certificate))
{
    $certificate = explode(',', $model->certificate);
}
@endphp
<script>


var speciality = {};
var vac_content = [];
var cer_content = [];

@if(count($specialty))
@for($i=0; $i<count($specialty);$i++)
speciality['{{$specialty[$i]}}'] = '{{$experience[$i]}}';
@endfor
@endif
console.log(speciality);

@if(count($vaccinations))
@for($i=0; $i<count($vaccinations);$i++)
vac_content.push('{{$vaccinations[$i]}}');
@endfor
@endif

@if(count($certificate))
@for($i=0; $i<count($certificate);$i++)
cer_content.push('{{$certificate[$i]}}');
@endfor
@endif

var dynamic_elements = {
    vac : {
        id : '#vaccination',
        name: 'Vaccination',
        listing_class : '.vaccination-content',
        items: vac_content
    },
    cer : {
        id : '#certificate',
        name: 'Certificate',
        listing_class : '.certifications-content',
        items: cer_content
    }
}


function add_element(obj)
{
    const type = $(obj).data('type');
    if (dynamic_elements.hasOwnProperty(type)) {
        let element, id, value,name;
        element = dynamic_elements[type];
        id = element.id;
        name =  element.name;
        value = $(id).val();

        if (!value) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the '+name+' please.',
                time: 3
            });
        }else{
            if (!element.items.includes(value)) {
                element.items.push($(id).val());
                console.log(element.items);
                list_elements(type);
            }
            $(id).val('');
        }
    }
}

function remove_element(obj)
{
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

function list_elements(type)
{
    if (dynamic_elements.hasOwnProperty(type)) {
        const element = dynamic_elements[type];
        if (element.items.length) {
            str = '';
            element.items.forEach(function(value, index) {
                str += '<ul>';
                str += '<li>';
                str += '<p>'+value+'</p>';
                str += '</li>';
                str += '<li><a href="#" data-type="'+type+'" data-key="'+value+'" onclick="remove_element(this)"><img src="{{URL::asset('frontend/img/delete-img.png')}}"/></a></li>';
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
    }else if(!$('#experience').val())
    {
        notie.alert({
            type: 'error',
            text: '<i class="fa fa-check"></i> Enter total year of experience.',
            time: 3
        });
    }else{
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

function list_specialities()
{
    // $('.speciality-content').empty();
    var str = '';
    for (const key in speciality) {
        if (speciality.hasOwnProperty(key)) {
            const value = speciality[key];
            str += '<ul>';
            str += '<li>'+key+'</li>';
            str += '<li>'+value+' Years</li>';
            str += '<li><button type="button" data-key="'+key+'" onclick="remove_speciality(this)"><img src="'+full_path+'public/frontend/img/delete-img.png'+'" /></button></li>';
            str += '</ul>';
        }
    }
    $('.speciality-content').html(str);
}


$(document).ready(function () {
    list_specialities();
    list_elements('vac');
    list_elements('cer');
    get_speciality($('select[name="profession"]'), false);
    $('#counter-offer-form').on('submit', function (event) {
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
            success: function (resp) {
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
            error: function (resp) {
                ajaxindicatorstop();
                console.log(resp);
                $.each(resp.responseJSON.errors, function (key, val) {
                    console.log(val[0]);
                    form.find('[name="' + key + '"]').closest('.ss-form-group').find('.help-block').html(val[0]);
                    form.find('[name="' + key + '"]').closest('.ss-form-group').addClass('has-error');
                });

            }
        })
    });

});
</script>
