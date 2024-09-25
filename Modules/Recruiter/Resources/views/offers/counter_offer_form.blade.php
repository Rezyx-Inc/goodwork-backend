<div>
    <h4><img src="{{URL::asset('public/recruiter/assets/images/counter-left-img.png')}}"> Send Offer</h4>
    <div class="ss-job-view-off-text-fst-dv">
        <p class="mt-3">On behalf of <a href="">Albus Percival , Hogwarts</a> would like to offer <a href="#">{{$offerdetails['id']}}</a>
            to <a href="#">{{$userdetails->first_name}} {{$userdetails->last_name}}</a> with the following terms. This offer is only available for the next <a
            hre="#">6 weeks:</a>
        </p>

    </div>
</div>
<form class="ss-emplor-form-sec" id="send-job-offer">
<div class="ss-form-group">
    <label>Job Name</label>
    <input type="text" name="job_name" id="job_name" placeholder="Enter job name" value="{{$offerdetails['job_name']}}">
    <input type="text" class="d-none" id="offer_id" name="offer_id"  value="{{$offerdetails['id']}}">

</div>
<span class="help-block-job_name"></span>
<div class="ss-form-group">
    <label>Type</label>
    <select name="type" id="type">
        <option value="{{$offerdetails['type']}}">{{$offerdetails['type']}}</option>
@if (isset($allKeywords['Type']))
@foreach ($allKeywords['Type'] as $value)
    <option value="{{$value->title}}" {{($offerdetails['type'] == $value->id ? 'selected' : '')}}>{{$value->title}}</option>
@endforeach
@endif
    </select>
</div>
<span class="help-block-type"></span>
<div class="ss-form-group">
    <label>Terms</label>
    <select name="terms" id="terms">

@if (isset($allKeywords['Terms']))
@foreach ($allKeywords['Terms'] as $value)
    <option value="{{$value->id}}" {{($offerdetails['terms'] == $value->id ? 'selected' : '') }}>{{$value->title}}</option>
@endforeach
@endif
    </select>
</div>
<span class="help-block-term"></span>
<div class="ss-form-group">
    <h6>Description</h6>
    <textarea name="description" id="description" placeholder="Enter Job Description" cols="30" rows="2"> {{$offerdetails['description']}}</textarea>
</div>
<span class="help-block-description"></span>
<div class="ss-form-group">
    <label>Profession</label>
    <select name="profession" id="profession" >
    <option value="{{$offerdetails['profession']}}">{{$offerdetails['profession']}}</option>
@if (isset($allKeywords['Profession']))
@foreach ($allKeywords['Profession'] as $value)
    <option value="{{$value->id}}" {{ ($offerdetails['profession'] == $value->id ? 'selected' : '') }} > {{ $value->title }} </option>
@endforeach
@endif
    </select>
</div>
<span class="help-block-profession"></span>
<div class="ss-form-group ss-prsnl-frm-specialty">
    <label>Specialty</label>

            <div class="col-md-12">
                <select name="preferred_specialty" class="m-0" id="preferred_specialty">
                <option value="{{$offerdetails['preferred_specialty']}}">{{$offerdetails['preferred_specialty']}}</option>
@if (isset($allKeywords['Speciality']))
@foreach ($allKeywords['Speciality'] as $value)
    <option value="{{$value->id}}">{{$value->title}}</option>
@endforeach
@endif
                </select>
            </div>
</div>
<span class="help-block-preferred_specialty"></span>

<div class="ss-form-group row justify-contenet-center align-items-center" style="margin-top:36px;">
    <label class="col-lg-6 col-sm-8 col-xs-8 col-md-8" >Block scheduling</label>
    <input style="box-shadow:none; width: auto;" class="col-lg-6 col-sm-2 col-xs-2 col-md-2" type="radio" id="option1" name="option" value="1" autocompleted="" disabled>
</div>
<div class="ss-form-group">
    <label>Float requirements</label>
    <select name="float_requirement" class="float_requirement mb-3" id="float_requirement" value="{{$offerdetails['float_requirement']}}" >
<option value="{{$offerdetails['float_requirement']}}">{{$offerdetails['float_requirement']}}</option>
        <option value="Yes">Yes</option>
        <option value="No">No</option>
    </select>
</div>
<span class="help-block-float_requirement"></span>
<div class="ss-form-group">
    <label>Facility Shift Cancellation Policy</label>
    <select name="facility_shift_cancelation_policy" class="facility_shift_cancelation_policy mb-3" id="facility_shift_cancelation_policy" value="{{$offerdetails['facility_shift_cancelation_policy']}}" >
<option value="{{$offerdetails['facility_shift_cancelation_policy']}}">{{$offerdetails['facility_shift_cancelation_policy']}}</option>
@if (isset($allKeywords['AssignmentDuration']))
@foreach ($allKeywords['AssignmentDuration'] as $value)
    <option value="{{$value->id}}" {{($offerdetails['block_scheduling'] == $value->id ? 'selected' : '')}}>{{$value->title}}</option>
@endforeach
@endif
    </select>
</div>
<span class="help-block-facility_shift_cancelation_policy"></span>
<div class="ss-form-group">
    <label>Contract Termination Policy</label>
    <input type="text" id="contract_termination_policy" name="contract_termination_policy" placeholder="Enter Contract Termination Policy" value="{{(isset($offerdetails['contract_termination_policy']) ? $offerdetails['contract_termination_policy'] : '2 weeks of guaranteed pay unless canceled for cause')}}">
</div>
<span class="help-block-contract_termination_policy"></span>
<div class="ss-form-group">
    <label>Traveler Distance From Facility</label>
    <input type="number" id="traveler_distance_from_facility" name="traveler_distance_from_facility" placeholder="Enter Traveler Distance From Facility" value="{{$offerdetails['traveler_distance_from_facility']}}" >
</div>
<span class="help-block-traveler_distance_from_facility"></span>
<div class="ss-form-group">
    <label>Facility</label>
    <select name="facility" class="facility mb-3" id="facility">
    <option value="{{$offerdetails['facility']}}">{{$offerdetails['facility']}}</option>
@if (isset($allKeywords['FacilityName']))
@foreach ($allKeywords['FacilityName'] as $value)
    <option value="{{$value->id}}" {{($offerdetails['facility'] == $value->id ? 'selected' : '')}}>{{$value->title}}</option>
@endforeach
@endif
    </select>
</div>
<span class="help-block-facility"></span>
<div class="ss-form-group">
    <label>Clinical Setting</label>
    <input type="text" id="clinical_setting" name="clinical_setting" placeholder="Enter clinical setting" value="{{$offerdetails['clinical_setting']}}">
</div>
<span class="help-block-clinical_setting"></span>
<div class="ss-form-group">
    <label>Patient ratio</label>
    <input type="number" id="Patient_ratio" name="Patient_ratio" placeholder="How many patients can you handle?" value="{{$offerdetails['Patient_ratio']}}">
</div>
<span class="help-block-Patient_ratio"></span>
<div class="ss-form-group">
    <label>EMR</label>
    <select name="emr" class="emr mb-3" id="emr">
    <option value="{{$offerdetails['Emr']}}">{{$offerdetails['Emr']}}</option>';
@if (isset($allKeywords['EMR']))
@foreach ($allKeywords['EMR'] as $value)
    <option value="{{$value->id}}"  {{ ($offerdetails['Emr'] == $value->id ? 'selected' : '')}}>{{$value->title }}</option>
@endforeach
@endif
    </select>
</div>
<span class="help-block-emr"></span>
<div class="ss-form-group">
    <label>Unit</label>
    <input id="Unit" type="text" name="Unit" placeholder="Enter Unit" value="{{$offerdetails['Unit']}}">
</div>
<span class="help-block-Unit"></span>
<div class="ss-form-group">
    <label>Scrub Color</label>
    <input id="scrub_color" type="text" name="scrub_color" placeholder="Enter Scrub Color" value="{{$offerdetails['scrub_color']}}">
</div>

<span class="help-block-scrub_color"></span>

<div class="ss-form-group">
     <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12 col-xs-12">
            <label>Start Date</label>
        </div>
        <div class="row col-lg-6 col-sm-12 col-md-12 col-xs-12" style="display: flex; justify-content: end;">
            <input id="as_soon_as" name="as_soon_as" value="1" type="checkbox" style="box-shadow:none; width:auto;" class="col-6">
            <label class="col-6">
                As soon As possible
            </label>
        </div>
    </div>
     <input id="start_date" type="date" min="2024-03-06" name="start_date" placeholder="Select Date" value="{{$offerdetails['start_date']}}">
</div>
<span class="help-block-start_date"></span>
<div class="ss-form-group">
    <label>Enter RTO</label>
    <input id="rto" type="text" name="rto" placeholder="RTO" value="{{$offerdetails['rto']}}">
</div>
<span class="help-block-rto"></span>
<div class="ss-form-group">
    <label>Shift Time of Day</label>
    <select name="preferred_shift" id="preferred_shift">
    <option value="{{$offerdetails['preferred_shift']}}">{{$offerdetails['preferred_shift']}}</option>
@if(isset($allKeywords['PreferredShift']))
@foreach ($allKeywords['PreferredShift'] as $value)
    <option value="{{$value->id}}" {{($offerdetails['preferred_shift'] == $value->id ? 'selected' : '')}}>{{ $value->title}}</option>
    @endforeach
@endif

    </select>
</div>
<span class="help-block-shift-of-day"></span>
<div class="ss-form-group">
    <label>Hours/Week</label>
    <input id="hours_per_week" type="number" name="hours_per_week" placeholder="Enter Hours/Week" value="{{$offerdetails['hours_per_week']}}">
</div>
<span class="help-block-hours_per_week"></span>
<div class="ss-form-group">
    <label>Guaranteed Hours</label>
    <input id="guaranteed_hours" type="number" name="guaranteed_hours" placeholder="Enter Guaranteed Hours" value="{{$offerdetails['guaranteed_hours']}}">
</div>
<span class="help-block-guaranteed_hours"></span>
<div class="ss-form-group">
    <label>Hours/Shift</label>
    <input id="hours_shift" type="number" name="hours_shift" placeholder="Enter Hours/Shift" value="{{$offerdetails['hours_shift']}}">
</div>
<span class="help-block-hours_shift"></span>
<div class="ss-form-group">
    <label>Weeks/Assignment</label>
    <input id="preferred_assignment_duration" type="number" name="preferred_assignment_duration" placeholder="Enter Weeks/Assignment" value="{{$offerdetails['preferred_assignment_duration']}}">
</div>
<span class="help-block-preferred_assignment_duration"></span>
<div class="ss-form-group">
    <label>Shifts/Week</label>
    <input id="weeks_shift" type="number" name="weeks_shift" placeholder="Enter Shifts/Week" value="{{$offerdetails['weeks_shift']}}">
</div>
<span class="help-block-weeks_shift"></span>
<div class="ss-form-group">
    <label>Sign-On Bonus</label>
    <input id="sign_on_bonus" type="number" name="sign_on_bonus" placeholder="Enter Sign-On Bonus" value="{{$offerdetails['sign_on_bonus']}}">
</div>
<span class="help-block-sign_on_bonus"></span>
<div class="ss-form-group">
    <label>Completion Bonus</label>
    <input id="completion_bonus" type="number" name="completion_bonus" placeholder="Enter Completion Bonus" value="{{$offerdetails['completion_bonus']}}">
</div>
<span class="help-block-completion_bonus"></span>
<div class="ss-form-group">
    <label>Extension Bonus</label>
    <input id="extension_bonus" type="number" name="extension_bonus" placeholder="Enter Extension Bonus" value="{{$offerdetails['extension_bonus']}}">
</div>
<span class="help-block-extension_bonus"></span>
<div class="ss-form-group">
    <label>Other Bonus</label>
    <input id="other_bonus" type="number" name="other_bonus" placeholder="Enter Other Bonus" value="{{$offerdetails['other_bonus']}}">
</div>
<span class="help-block-other_bonus"></span>
<div class="ss-form-group">
    <label>Referral Bonus</label>
    <input id="referral_bonus" type="number" name="referral_bonus" placeholder="Enter Referral Bonus" value="{{$offerdetails['referral_bonus']}}">
</div>
<span class="help-block-referral_bonus"></span>
<div class="ss-form-group">
    <label>401K</label>
    <select name="four_zero_one_k" id="four_zero_one_k">
        <option value="Yes">Yes</option>
        <option value="No">No</option>
    </select>
</div>
<span class="help-block-401k"></span>
<div class="ss-form-group">
    <label>Health Insurance</label>
    <select name="health_insaurance" id="health_insaurance">

        <option value="{{$offerdetails['health_insaurance']}}">{{($offerdetails['health_insaurance'] == '1') ? 'Yes': 'No'}}</option>
        <option value="true">Yes</option>
        <option value="false">No</option></option>
    </select>
</div>
<span class="help-block-health-insurance"></span>
<div class="ss-form-group">
    <label>Dental</label>
    <select name="dental" id="dental">
    <option value="{{$offerdetails['dental']}}">{{(($offerdetails['dental'] == '1') ? 'Yes': 'No')}}</option>
        <option value="Yes">Yes</option>
        <option value="No">No</option>
    </select>
</div>
<span class="help-block-dental"></span>
<div class="ss-form-group">
    <label>Vision</label>
    <select name="vision" id="vision">
    <option value="{{$offerdetails['vision']}}">{{(($offerdetails['vision'] == '1') ? 'Yes': 'No')}}</option>
        <option value="Yes">Yes</option>
        <option value="No">No</option>
    </select>
</div>
<span class="help-block-vision"></span>
<div class="ss-form-group">
    <label>Actual Hourly rate</label>
    <input id="actual_hourly_rate" type="number" name="actual_hourly_rate" placeholder="Enter Actual Hourly rate" value="{{$offerdetails['actual_hourly_rate']}}">
</div>
<span class="help-block-actual_hourly_rate"></span>
<div class="ss-form-group">
    <label>Overtime</label>
    <select name="overtime" class="overtime mb-3" id="overtime" value="{{$offerdetails['overtime']}}">
<option value="{{$offerdetails['overtime']}}">{{$offerdetails['overtime']}}</option>
        <option value="Yes">Yes</option>
        <option value="No">No</option>
    </select>
</div>
<span class="help-block-overtime"></span>
<div class="ss-form-group">
    <label>Holiday</label>
    <input id="holiday" type="text" name="holiday" placeholder="Select Dates" value="{{$offerdetails['holiday']}}">
</div>
<span class="help-block-holiday"></span>
<div class="ss-form-group">
    <label>On Call</label>
    <select  name="on_call" class="on_call mb-3" id="on_call" value="{{$offerdetails['on_call']}}">
<option value="{{$offerdetails['on_call']}}">{{$offerdetails['on_call']}}</option>
        <option value="Yes">Yes</option>
        <option value="No">No</option>
    </select>
</div>
<span class="help-block-on_call"></span>
<div class="ss-form-group">
    <label>Tax Status</label>
    <select name="tax_status" class="on_call mb-3" id="tax_status" value="">
        <option value="W4">W4</option>
        <option value="1099">1099</option>
    </select>
</div>
<span class="help-block-tax_status"></span>
<div class="ss-form-group">
    <label>Orientation Rate</label>
    <input id="orientation_rate" type="number" name="orientation_rate" placeholder="Enter Orientation Rate" value="{{$offerdetails['orientation_rate']}}">
</div>
<span class="help-block-orientation_rate"></span>
<div class="ss-form-group">
    <label>Est. Weekly Taxable amount</label>
    <input  type="number" name="weekly_taxable_amount" id="weekly_taxable_amount" placeholder="---" value="{{$offerdetails['weekly_taxable_amount']}}" readonly>
</div>
<div class="ss-form-group">
    <label>Est. Weekly non-taxable amount</label>
    <input type="number" name="weekly_non_taxable_amount" id="weekly_non_taxable_amount" placeholder="---" value="{{$offerdetails['weekly_non_taxable_amount']}}" readonly>
</div>
<div class="ss-form-group">
    <label>Est. Employer Weekly Amount</label>
    <input type="number" name="employer_weekly_amount" id="employer_weekly_amount" placeholder="---" value="{{$offerdetails['employer_weekly_amount']}}" readonly>
</div>
<div class="ss-form-group">
    <label>Est. Goodwork Weekly Amount</label>
    <input type="number" name="goodwork_weekly_amount" id="goodwork_weekly_amount" placeholder="---" value="{{$offerdetails['weekly_taxable_amount']}}" readonly>
</div>
<div class="ss-form-group">
    <label>Est. Total Employer Amount</label>
    <input type="number" name="total_employer_amount" id="total_employer_amount" placeholder="---" value="{{$offerdetails['total_employer_amount']}}" readonly>
</div>
<div class="ss-form-group">
    <label>Est. Total Goodwork Amount</label>
    <input type="number" name="total_goodwork_amount" id="total_goodwork_amount" placeholder="---" value="{{$offerdetails['total_goodwork_amount']}}" readonly>
</div>
<div class="ss-form-group">
    <label>Est. Total Contract Amount</label>
    <input type="text" name="total_contract_amount" id="total_contract_amount" placeholder="---" value="{{$offerdetails['total_contract_amount']}}" readonly>
</div>
<div class="ss-counter-buttons-div">
    <button class="ss-counter-button" id="ss-reject-offer-btn" onclick="offerSend(event)">Counter Offer</button>
</div>
</form>

<script>
    var data = {};
    const OfferFieldsName =
    [
                'job_name',
                'type',
                'terms',
                'description',
                'profession',
                'preferred_specialty',
                'float_requirement',
                'facility_shift_cancelation_policy',
                'contract_termination_policy',
                'traveler_distance_from_facility',
                'facility',
                'clinical_setting',
                'Patient_ratio',
                'emr',
                'Unit',
                'scrub_color',
                'start_date',
                'as_soon_as',
                'start_date',
                'rto',
                'preferred_shift',
                'hours_per_week',
                'guaranteed_hours',
                'hours_shift',
                'preferred_assignment_duration',
                'weeks_shift',
                'sign_on_bonus',
                'completion_bonus',
                'extension_bonus',
                'other_bonus',
                'referral_bonus',
                'four_zero_one_k',
                'health_insaurance',
                'dental',
                'vision',
                'actual_hourly_rate',
                'overtime',
                'holiday',
                'on_call',
                'tax_status',
                'orientation_rate',
                'weekly_taxable_amount',
                'weekly_non_taxable_amount',
                'employer_weekly_amount',
                'goodwork_weekly_amount',
                'total_employer_amount',
                'total_goodwork_amount',
                'total_contract_amount',
    ];

    function getValues(OfferFieldsName) {
    try {
        for (let i = 0; i < OfferFieldsName.length; i++) {
            let fieldName = OfferFieldsName[i];
            let fieldValue = document.getElementById(fieldName).value;
            if (fieldValue !== null && fieldValue.trim() !== '') {
                data[fieldName] = fieldValue;
            }
            console.log(fieldName);
        }
    } catch(error) {
        console.log(error);
    }
}

    function offerSend(event) {
                try{
                    event.preventDefault();
                    getValues(OfferFieldsName);
                    let id = document.getElementById('offer_id').value;
                    console.log(id);
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    if (csrfToken) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            url: "{{ url('recruiter/recruiter-counter-offer') }}",
                            data: {
                                'token': csrfToken,
                                'data' : data,
                                'id':id
                            },
                            type: 'POST',
                            dataType: 'json',
                            success: function(result) {
                                notie.alert({
                                    type: 'success',
                                    text: '<i class="fa fa-check"></i> Counter Offer Sended',
                                    time: 2
                                });
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    } else {
                        console.error('CSRF token not found.');
                    }
                }catch(error){
                        console.log(error);
                    }
                console.log('offer countered data', data);
        }
</script>
