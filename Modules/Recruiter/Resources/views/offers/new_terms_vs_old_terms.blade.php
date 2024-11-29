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
    {{-- @if ($hasFile == true)
        <li style="margin-right:10px;">
            <a style="cursor:pointer;" class="rounded-pill ss-apply-btn py-2 border-0 px-4" data-target="file"
                data-hidden_value="Yes" data-href="" data-title="Worker's Files" data-name="diploma"
                onclick="open_modal(this)">
                Consult worker files
            </a>
        </li>
        <li>
            <a onclick="askWorker(this, 'nursing_profession', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')"
                class="rounded-pill ss-apply-btn py-2 border-0 px-4" style="cursor: pointer;">Chat Now</a>
        </li>
    @else
        <li>
            <a onclick="askWorker(this, 'nursing_profession', '{{ $nursedetails['id'] }}', '{{ $offerdetails[0]->job_id }}')"
                class="rounded-pill ss-apply-btn py-2 border-0 px-4" style="cursor: pointer;">Chat Now</a>
        </li>
    @endif --}}
</ul>

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
                        {{ $offerdetails['status'] === 'Onboarding hidden disabled' ? 'selected' : '' }}>
                        Onboarding</option>
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
            <h5 class="mt-3 mb-3 text-center">New Terms</h5>
        </div>
        <div class="col-md-6">
            <h5 class="mt-3 mb-3 text-center">Old Terms</h5>
        </div>
    </div>

    <div id="summary-section" class="section">
        <div class="row col-md-12  mt-4 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-0">
                    Summary
                </a>
            </p>
        </div>
        <div class="row mb-4 collapse-static-container" style="padding:0px;" id="collapse-0">
        </div>
    </div>

    <div id="shift-section" class="section">
        <div class="col-md-12 mt-4 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-1" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Shift
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row mb-4 collapse-static-container" id="collapse-1">
        </div>
    </div>

    <div id="pay-section" class="section">
        <div class="col-md-12 mt-4 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-2" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Pay
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row mb-4 collapse-static-container" id="collapse-2">
        </div>
    </div>

    <div id="location-section" class="section">
        <div class="col-md-12 mt-4 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-4" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Location
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row mb-4 collapse-static-container" id="collapse-4">
        </div>
    </div>

    <div id="certs-licenses-section" class="section">
        <div class="col-md-12 mt-4 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-5" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Certs & Licences
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row mb-4 collapse-static-container" id="collapse-5">
        </div>
    </div>

    <div id="work-infos-section" class="section">
        <div class="col-md-12 mt-4 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-6" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Work Infos
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row mb-4 collapse-static-container" id="collapse-6">
        </div>
    </div>

    <div id="id-tax-info-section" class="section">
        <div class="col-md-12 mt-4 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-7" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    ID & Tax Info
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row mb-4 collapse-static-container" id="collapse-7">
        </div>
    </div>

    <div id="medical-info-section" class="section">
        <div class="col-md-12 mt-4 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-8" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Medical info
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row mb-4 collapse-static-container" id="collapse-8">
        </div>
    </div>

    <div id="other-info-section" class="section">
        <div class="col-md-12 mt-4 collapse-container">
            <p>
                <a class="btn first-collapse mb-4" data-toggle="collapse" href="#collapse-9" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Other Info
                </a>
            </p>
        </div>
        <div style="padding:0px; margin:0px;" class="row mb-4 collapse-static-container" id="collapse-9">
        </div>
    </div>

    @if ($offerdetails->status == 'Screening')
        <div class="ss-counter-buttons-div">
            <button class="ss-acpect-offer-btn"
                onclick="applicationStatus('Offered', '{{ $offerdetails->id }}')">Send
                1st
                Offer</button>
        </div>
        <div class="ss-counter-buttons-div">
            <button class="ss-counter-button" onclick="ChangeOfferInfo('{{ $offerdetails->id }}')">Change
                Offer</button>
        </div>
        <div class="ss-counter-buttons-div">
            <button class="ss-reject-offer-btn"
                onclick="AcceptOrRejectJobOffer('{{ $offerdetails->id }}', '{{ $offerdetails->job_id }}', 'rejectcounter')">Reject
                Offer</button>
        </div>
    @endif

    @if ($offerdetails->status == 'Offered')
        <div class="ss-counter-buttons-div">
            <button class="ss-reject-offer-btn"
                onclick="AcceptOrRejectJobOffer('{{ $offerdetails->id }}', '{{ $offerdetails->job_id }}', 'rejectcounter')">Reject
                Offer</button>
        </div>
        <div class="ss-counter-buttons-div">
            <button class="ss-counter-button" onclick="ChangeOfferInfo('{{ $offerdetails->id }}')">Change
                Offer</button>
        </div>
        @if (count($offerLogs) > 0)
        <div class="ss-counter-buttons-div">
            <button class="counter-save-for-button" onclick="counterOffer('{{ $offerdetails->id }}')">Counter
                Offer</button>
        </div>
        <div class="ss-counter-buttons-div">
            <button class="ss-acpect-offer-btn"
                onclick="AcceptOrRejectJobOffer('{{ $offerdetails->id }}', '{{ $offerdetails->job_id }}', 'offersend')">Accept
                Offer</button>
        </div>
        @endif
    @endif

</div>

<script>
    $(document).ready(function() {
        var fields = {
            // Summary
            'summary': [
                'type',
                'terms',
                'profession',
                'specialty',
                'actual_hourly_rate',
                'weekly_pay',
                'hours_per_week',
                'state',
                'city'
            ],
            // Shift
            'shift': [
                'preferred_shift_duration',
                'guaranteed_hours',
                'hours_shift',
                'weeks_shift',
                'preferred_assignment_duration',
                'as_soon_as',
                'start_date',
                'end_date',
                'rto'
            ],
            // Pay
            'pay': [
                'overtime',
                'on_call',
                'on_call_rate',
                'call_back_rate',
                'orientation_rate',
                'weekly_taxable_amount',
                'weekly_non_taxable_amount',
                'feels_like_per_hour',
                'goodwork_weekly_amount',
                'referral_bonus',
                'sign_on_bonus',
                'completion_bonus',
                'extension_bonus',
                'other_bonus',
                'pay_frequency',
                'benefits',
                'total_organization_amount',
                'total_goodwork_amount',
                'total_contract_amount'
            ],
            // Location
            'location': [
                'clinical_setting',
                'preferred_work_location',
                'facility_name',
                'facilitys_parent_system',
                'facility_shift_cancelation_policy',
                'contract_termination_policy',
                'traveler_distance_from_facility'
            ],
            // Certs & Licences
            'certs-licenses': [
                'job_location',
                'certificate'
            ],
            // Work Infos
            'work-infos': [
                'description',
                'urgency',
                'preferred_experience',
                'number_of_references',
                'skills',
                'block_scheduling',
                'float_requirement',
                'Patient_ratio',
                'Emr',
                'Unit'
            ],
            // ID & Tax Info
            'id-tax-info': [
                'nurse_classification'
            ],
            // Medical info
            'medical-info': [
                'vaccinations'
            ],
            // Other Info
            'other-info': [
                'scrub_color',
                'job_name',
                'holiday',
            ]
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
            'goodwork_weekly_amount',
            'referral_bonus',
            'sign_on_bonus',
            'completion_bonus',
            'extension_bonus',
            'other_bonus',
            'total_organization_amount',
            'total_goodwork_amount',
            'total_contract_amount',
            'traveler_distance_from_facility',
            'number_of_references'
        ];

        
        var diff = @json($diff);
        if (diff == null) {
            diff = {};
        }else{
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

        function createFieldElement(field, newValue, oldValue) {
            if (field == 'on_call' || field == 'block_scheduling' || field == 'float_requirement' || field == 'as_soon_as') {
                    newValue = newValue == 1 ? 'Yes' : 'No';
                    oldValue = oldValue == 1 ? 'Yes' : 'No';
            }

            var fieldDiv = document.createElement('div');
            fieldDiv.className = 'col-md-12';
            var span = document.createElement('h5');
            span.className = 'mt-3';
            span.style.fontSize = '17px';
            span.style.color = '#3d2c39';
            span.textContent = field.replace(/_/g, ' ');
            fieldDiv.appendChild(span);

            var rowDiv = document.createElement('div');
            rowDiv.className = 'row d-flex align-items-center';
            rowDiv.style.margin = 'auto';
            rowDiv.id = field + '-div';

            var newValueDiv = document.createElement('div');
            newValueDiv.className = 'col-md-6';
            var newValueH6 = document.createElement('h6');
            newValueH6.id = field + '-new-value';

            newValueH6.textContent = newValue;
            newValueDiv.appendChild(newValueH6);

            var oldValueDiv = document.createElement('div');
            oldValueDiv.className = 'col-md-6';
            var oldValueH6 = document.createElement('h6');
            oldValueH6.id = field + '-old-value';
            oldValueH6.textContent = oldValue;
            oldValueDiv.appendChild(oldValueH6);

            rowDiv.appendChild(newValueDiv);
            rowDiv.appendChild(oldValueDiv);

            if (diff[field] != undefined) {
                rowDiv.classList.add('ss-s-jb-apl-bg-pink');
            }

            return [fieldDiv, rowDiv];
        }

        Object.keys(fields).forEach(function(section) {
            fields[section].forEach(function(field) {

                var oldValue = diff[field] != undefined ? diff[field] : offerdetails[field];
                var newValue = offerdetails[field];
                var elements = createFieldElement(field, newValue, oldValue);
                console.log('element:', section);
                document.getElementById(section + '-section').querySelector(
                    '.collapse-static-container, .collapse').appendChild(elements[0]);
                document.getElementById(section + '-section').querySelector(
                    '.collapse-static-container, .collapse').appendChild(elements[1]);
            });

        });
        $('.first-collapse').click();

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
</style>
