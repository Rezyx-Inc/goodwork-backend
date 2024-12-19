<ul class="ss-cng-appli-hedpfl-ul">
    <li>
        <span>
            {{ $offerdetails['worker_user_id'] }}
        </span>
        <h6>
            <img width="50px" height="50px" src="{{ URL::asset('images/nurses/profile/' . $userdetails->image) }}"
                onerror="this.onerror=null;this.src='{{ URL::asset('frontend/img/profile-pic-big.png') }}';"
                id="preview" style="object-fit: cover;" class="rounded-3" alt="Profile Picture">
            {{ $userdetails->first_name }}
            {{ $userdetails->last_name }}
        </h6>
    </li>
</ul>
<div class="ss-job-view-off-text-fst-dv mt-3">
    <p>
        <a href="">{{ $recruiter->first_name }} {{ $recruiter->last_name }} </a>
        On behalf of <a href="">{{$organization->organization_name}}</a>
        is offering <a href="">{{ $userdetails->first_name }} {{ $userdetails->last_name }} </a> the opportunity as defined by the new terms and additional terms below and attached
    </p>

</div>
<div class="row ss-chng-apcon-st-ssele d-flex justify-content-center align-items-center">
    <div class="col-12 row">
        <label class="mb-2">Change Application Status</label>
    </div>
    <div class="col-12 row d-flex justify-content-center align-items-center">
        <div class="col-9">
            @if ($offerdetails['status'] === 'Screening')
                <select name="status" id="status application-status">
                    <option value="" disabled hidden>Select Status</option>
                    <option value="Screening"
                        {{ $offerdetails['status'] === 'Screening' ? 'selected hidden disabled' : '' }}>Screening
                    </option>
                    <option value="Submitted">Submitted
                    </option>
                    <option value="Offered">Offered</option>
                    <option value="Done">Done</option>
                </select>
            @else
                <select name="status" id="status application-status">
                    <option value="" disabled hidden>Select Status</option>
                    @if ($offerdetails['status'] === 'Apply')
                        <option value="Apply" selected hidden disabled>Apply</option>
                    @endif
                    <option value="Screening"
                        {{ $offerdetails['status'] === 'Screening' ? 'selected hidden disabled' : '' }}>Screening
                    </option>
                    <option value="Submitted"
                        {{ $offerdetails['status'] === 'Submitted' ? 'selected hidden disabled' : '' }}>Submitted
                    </option>
                    <option value="Offered"
                        {{ $offerdetails['status'] === 'Offered' ? 'selected hidden disabled' : '' }}>Offered</option>
                    <option value="Done" {{ $offerdetails['status'] === 'Done' ? 'selected hidden disabled' : '' }}>
                        Done</option>
                    <option value="Onboarding"
                        {{ $offerdetails['status'] === 'Onboarding' ? 'selected hidden disabled' : '' }}>
                        Onboarding</option>
                    <option value="Cleared"
                        {{ $offerdetails['status'] === 'Cleared' ? 'selected hidden disabled' : '' }}>
                        Cleared to Start</option>
                    {{-- <option value="Working" {{ $offerdetails['status'] === 'Working' ? 'selected' : '' }}>Working
                </option> --}}
                    <option value="Rejected"
                        {{ $offerdetails['status'] === 'Rejected' ? 'selected hidden disabled' : '' }}>Rejected
                    </option>
                    <option value="Blocked"
                        {{ $offerdetails['status'] === 'Blocked' ? 'selected hidden disabled' : '' }}>Blocked
                    </option>
                    <option value="Hold" {{ $offerdetails['status'] === 'Hold' ? 'selected hidden disabled' : '' }}>
                        Hold</option>
                </select>
            @endif
        </div>
        <div class="col-3">

            <button class="counter-save-for-button" style="margin-top:0px;"
                onclick="applicationStatus(document.getElementById('status application-status').value, '{{ $offerdetails->id }}')">Change
                Status</button>
        </div>
    </div>
</div>
<div class="ss-jb-apl-oninfrm-mn-dv">
    <div class="ss-jb-apply-on-inf-hed-rec row">
        <div class="col-md-6">
            <h5 class="mt-3 mb-3 text-center">Old Terms</h5>
        </div>
        <div class="col-md-6">
            <h5 class="mt-3 mb-3 text-center">New Terms</h5>
        </div>
    </div>

    <div id="summary-section" class="section">
        <div class="row col-md-12  mt-4 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-0" role="button" aria-expanded="false">
                    Summary
                </a>
            </p>
        </div>
        <div class="row mb-4 collapse text-center" style="padding:0px;" id="collapse-0">
        </div>
    </div>

    <div id="shift-section" class="section">
        <div class="row col-md-12 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-1" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Shift
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row mb-4 collapse text-center" id="collapse-1">
        </div>
    </div>

    <div id="pay-section" class="section">
        <div class="row col-md-12 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-2" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Pay
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row mb-4 collapse text-center" id="collapse-2">
        </div>
    </div>

    <div id="location-section" class="section">
        <div class="row col-md-12 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-4" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Location
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row mb-4 collapse text-center" id="collapse-4">
        </div>
    </div>

    <div id="certs-licenses-section" class="section">
        <div class="row col-md-12 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-5" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Certs & Licences
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row mb-4 collapse text-center" id="collapse-5">
        </div>
    </div>

    <div id="work-infos-section" class="section">
        <div class="row col-md-12 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-6" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Work Infos
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row mb-4 collapse text-center" id="collapse-6">
        </div>
    </div>

    <div id="id-tax-info-section" class="section">
        <div class="row col-md-12 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-7" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    ID & Tax Info
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row mb-4 collapse text-center" id="collapse-7">
        </div>
    </div>

    <div id="medical-info-section" class="section">
        <div class="row col-md-12 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-8" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Medical info
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row mb-4 collapse text-center" id="collapse-8">
        </div>
    </div>

    <div class="ss-counter-buttons-div">
        <button class="ss-acpect-offer-btn"
            onclick="AcceptOrRejectJobOffer('{{ $offerdetails->id }}', '{{ $offerdetails->job_id }}', 'offersend')">Accept
            Offer</button>
    </div>
    {{-- <div class="ss-counter-buttons-div">
        <button class="ss-counter-button" onclick="ChangeOfferInfo('{{ $offerdetails->id }}')">Change
            Offer</button>
    </div> --}}
    {{-- comment the condition for now until we have the counter 1st offer from the worker worked --}}
    {{-- @if (count($offerLogs) > 0) --}}
        <div class="ss-counter-buttons-div">
            <button class="counter-save-for-button" onclick="counterOffer('{{ $offerdetails->id }}')">Counter
                Offer</button>
        </div>
    {{-- @endif --}}
    <div class="ss-counter-buttons-div">
        <button class="ss-reject-offer-btn"
            onclick="AcceptOrRejectJobOffer('{{ $offerdetails->id }}', '{{ $offerdetails->job_id }}', 'rejectcounter')">Reject
            Offer</button>
    </div>
    

</div>

<script>
   $(document).ready(function() {
    var fields = {
        // Summary
        'summary': [
            {'id': 'type', 'display-name': 'Type'},
            {'id': 'terms', 'display-name': 'Terms'},
            {'id': 'profession', 'display-name': 'Profession'},
            {'id': 'specialty', 'display-name': 'Specialty'},
            {'id': 'actual_hourly_rate', 'display-name': '$/hr'},
            {'id': 'weekly_pay', 'display-name': '$/wk'},
            {'id': 'hours_per_week', 'display-name': 'Hrs/Wk'},
            {'id': 'state', 'display-name': 'State'},
            {'id': 'city', 'display-name': 'City'},
        ],
        // Shift
        'shift': [
            {'id': 'preferred_shift_duration', 'display-name': 'Shift Time'},
            {'id': 'guaranteed_hours', 'display-name': 'Guaranteed Hrs/wk'},
            {'id': 'hours_shift', 'display-name': 'Reg Hrs/Shift'},
            {'id': 'weeks_shift', 'display-name': 'Shifts/Wk'},
            {'id': 'preferred_assignment_duration', 'display-name': 'Wks/Contract'},
            {'id': 'as_soon_as', 'display-name': 'As Soon As'},
            {'id': 'start_date', 'display-name': 'Start Date'},
            {'id': 'end_date', 'display-name': 'End Date'},
            {'id': 'rto', 'display-name': 'RTO'}
        ],
        // Pay
        'pay': [
            {'id': 'overtime', 'display-name': 'OT $/Hr'},
            {'id': 'on_call_rate', 'display-name': 'On Call $/Hr'},
            {'id': 'call_back_rate', 'display-name': 'Call Back $/Hr'},
            {'id': 'orientation_rate', 'display-name': 'Orientation $/Hr'},
            {'id': 'weekly_taxable_amount', 'display-name': 'Taxable/Wk'},
            {'id': 'weekly_non_taxable_amount', 'display-name': 'Non-taxable/Wk'},
            {'id': 'feels_like_per_hour', 'display-name': 'Feels Like $/hr'},
            {'id': 'referral_bonus', 'display-name': 'Referral Bonus'},
            {'id': 'sign_on_bonus', 'display-name': 'Sign-On Bonus'},
            {'id': 'extension_bonus', 'display-name': 'Extension Bonus'},
            {'id': 'total_organization_amount', 'display-name': '$/Org'},
            {'id': 'pay_frequency', 'display-name': 'Pay Frequency'},
            {'id': 'benefits', 'display-name': 'Benefits'}
        ],
        // Location
        'location': [
            {'id': 'clinical_setting', 'display-name': 'Clinical Setting'},
            {'id': 'preferred_work_location', 'display-name': 'Adress'},
            {'id': 'facility_name', 'display-name': 'Facility'},
            {'id': 'facilitys_parent_system', 'display-name': 'Facility\'s Parent System'},
            {'id': 'facility_shift_cancelation_policy', 'display-name': 'Facility Shift Cancelation Policy'},
            {'id': 'contract_termination_policy', 'display-name': 'Contract Termination Policy'},
            {'id': 'traveler_distance_from_facility', 'display-name': 'Perm address miles from facility'}
        ],
        // Certs & Licences
        'certs-licenses': [
            {'id': 'job_location', 'display-name': 'Professional Licensure'},
            {'id': 'certificate', 'display-name': 'Certifications'}
        ],
        // Work Infos
        'work-infos': [
            {'id': 'description', 'display-name': 'Description'},
            {'id': 'preferred_experience', 'display-name': 'Experience'},
            {'id': 'number_of_references', 'display-name': 'References'},
            {'id': 'skills', 'display-name': 'Skills checklist'},
            {'id': 'on_call', 'display-name': 'On Call'},
            {'id': 'block_scheduling', 'display-name': 'Block Scheduling'},
            {'id': 'float_requirement', 'display-name': 'Float Requirement'},
            {'id': 'Patient_ratio', 'display-name': 'Patient Ratio Max'},
            {'id': 'Emr', 'display-name': 'EMR'},
            {'id': 'Unit', 'display-name': 'Unit'}
        ],
        // ID & Tax Info
        'id-tax-info': [
            {'id': 'nurse_classification', 'display-name': 'Classification'}
        ],
        // Medical info
        'medical-info': [
            {'id': 'vaccinations', 'display-name': 'Vaccinations'}
        ],
    };

    // fix all number fields to number 
    var numberFields = [
        'actual_hourly_rate',
        'weekly_pay',
        'hours_per_week',
        'guaranteed_hours',
        'hours_shift',
        'weeks_shift',
        'preferred_assignment_duration',
        'overtime',
        'on_call_rate',
        'call_back_rate',
        'orientation_rate',
        'weekly_taxable_amount',
        'weekly_non_taxable_amount',
        'feels_like_per_hour',
        'referral_bonus',
        'sign_on_bonus',
        'extension_bonus',
        'total_organization_amount',
        'traveler_distance_from_facility',
        'number_of_references'
    ];

    var diff = @json($diff);
    if (diff == null) {
        diff = {};
    } else {
        diff = JSON.parse(diff['details']);
    }
    var offerdetails = @json($offerdetails);

    numberFields.forEach(function(field) {
        if (offerdetails[field] != null) {
            offerdetails[field] = Number(offerdetails[field]);
        }
    });

    numberFields.forEach(function(field) {
        if (diff[field] != null) {
            diff[field] = Number(diff[field]);
        }
    });

    console.log('diff:', diff);
    console.log('offerdetails:', offerdetails);

    function createFieldElement(field, displayName, newValue, oldValue) {
        if (field == 'on_call' || field == 'block_scheduling' || field == 'float_requirement' || field == 'as_soon_as') {
            newValue = newValue == 1 ? 'Yes' : 'No';
            oldValue = oldValue == 1 ? 'Yes' : 'No';
        }

        var fieldDiv = document.createElement('div');
        fieldDiv.className = 'col-md-12';
        var span = document.createElement('h5');
        span.className = 'mt-3 mb-3';
        span.style.fontSize = '17px';
        span.style.color = '#3d2c39';
        span.textContent = displayName;
        fieldDiv.appendChild(span);

        var rowDiv = document.createElement('div');
        rowDiv.className = 'row d-flex align-items-center';
        rowDiv.style.margin = 'auto';
        rowDiv.id = field + '-div';

        var newValueDiv = document.createElement('div');
        newValueDiv.className = 'col-md-6';
        var newValueH6 = document.createElement('h6');
        newValueH6.id = field + '-new-value';
        newValueH6.textContent = oldValue;
        newValueDiv.appendChild(newValueH6);

        var oldValueDiv = document.createElement('div');
        oldValueDiv.className = 'col-md-6';
        var oldValueH6 = document.createElement('h6');
        oldValueH6.id = field + '-old-value';
        oldValueH6.textContent = newValue;
        oldValueDiv.appendChild(oldValueH6);

        rowDiv.appendChild(newValueDiv);
        rowDiv.appendChild(oldValueDiv);

        if (diff[field] != undefined) {
            rowDiv.classList.add('ss-s-jb-apl-bg-bl');
        }

        return [fieldDiv, rowDiv];
    }

    Object.keys(fields).forEach(function(section) {
        fields[section].forEach(function(field) {
            var fieldId = field.id;
            var displayName = field['display-name'];
            var oldValue = diff[fieldId] != undefined ? diff[fieldId] : offerdetails[fieldId];
            var newValue = offerdetails[fieldId];
            var elements = createFieldElement(fieldId, displayName, newValue, oldValue);
            var rowDivResult = elements[1];
            if (diff[fieldId] != undefined) {
                document.getElementById(section + '-section').querySelector('.collapse').classList.add('show');
            }
            console.log('element:', section);
            document.getElementById(section + '-section').querySelector('.collapse').appendChild(elements[0]);
            document.getElementById(section + '-section').querySelector('.collapse').appendChild(elements[1]);
        });
    });

});
</script>

<style>
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
    }

    .collapse-container,
    .collapse-static-container {
        margin: auto;
        text-align: center;
    }

    .green-bg {
        background-color: rgb(82, 222, 193);
    }
    .ss-s-jb-apl-bg-bl{
        background-color: rgb(186 215 255);
    }
</style>
