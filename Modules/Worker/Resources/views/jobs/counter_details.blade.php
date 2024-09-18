<!----------------jobs applay view details--------------->

<div class="ss-job-apply-on-view-detls-mn-dv">
    <div class="ss-job-apply-on-tx-bx-hed-dv">
        <ul>
            <li><p>Recruiter NNN</p></li>
            <li><img width="50px" height="50px"  src="{{URL::asset('images/nurses/profile/'.$recruiter->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';"/>{{$recruiter->first_name.' '.$recruiter->last_name}}</li>
        </ul>
        <ul>
            <li>
                <span>{{$offerdetails->id}}</span>
                <h6>{{$jobdetails->getOfferCount()}} Applied</h6>
            </li>
        </ul>
    </div>
        <div>
            <div class="ss-job-view-off-text-fst-dv">
                <p class="mt-3">On behalf of <a href="">{{ $recruiter->first_name }} {{ $recruiter->last_name }}</a> would like to offer <a href="#">{{ $jobdetails['id'] }}</a>
                    to <a href="#">{{ $nursedetails->first_name }} {{ $nursedetails->last_name }}</a> with the following terms. This offer is only available for the next <a href="#">6 weeks:</a>
                </p>
            </div>
        </div>
        <div class="ss-jb-apply-on-disc-txt mb-3">
            <h5>Description</h5>
            <p>{{ $jobdetails['description'] }}</p>
        </div>
        <ul class="ss-jb-apply-on-inf-hed-rec row">
            <li class="col-md-6 mb-3">
                <span class="mt-3">Diploma</span>
                <h6>College Diploma</h6>
            </li>
            <li class="col-md-6 mb-3">
                <span class="mt-3">Drivers license</span>
                <h6>Required</h6>
            </li>
            <li class="col-md-6 mb-3">
                <span class="mt-3">Worked at Facility Before</span>
                <h6>Have you worked here in the last 18 months?</h6>
            </li>
            <li class="col-md-6 mb-3">
                <span class="mt-3">SS# or SS Card</span>
                <h6>Last 4 digits of SS#</h6>
            </li>
            <li class="col-md-6 mb-3">
                <p>{{ $nursedetails->worker_ss_number ?? '----' }}</p>
            </li>
            <li class="col-md-6 mb-3 {{ $jobdetails->profession != $offerdetails->profession ? 'ss-job-view-off-text-fst-dv' : '' }}">
                <span class="mt-3">Profession</span>
                <h6>{{ $offerdetails->profession ?? '----' }}</h6>
            </li>
            @if (isset($jobdetails->specialty))
                @foreach (explode(',', $offerdetails->specialty) as $value)
                    @if ($value)
                        <div class="col-md-6 mb-3 {{ $jobdetails->specialty != $value ? 'ss-job-view-off-text-fst-dv' : '' }}">
                            <span class="mt-3">Specialty</span>
                            <h6>{{ $value }} Required</h6>
                        </div>
                    @endif
                @endforeach
            @endif
            @foreach (['block_scheduling', 'float_requirements', 'facility_shift_cancellation_policy', 'contract_termination_policy', 'traveler_distance_from_facility', 'clinical_setting', 'patient_ratio', 'emr', 'unit', 'scrub_color', 'interview_dates', 'start_date', 'rto', 'hours_per_week', 'guaranteed_hours', 'hours_shift', 'weeks_assignment', 'shifts_week', 'referral_bonus', 'sign_on_bonus', 'completion_bonus', 'extension_bonus', 'other_bonus', 'four_zero_one_k', 'health_insurance', 'dental', 'vision', 'actual_hourly_rate', 'overtime', 'holiday', 'on_call', 'orientation_rate', 'est_weekly_taxable_amount', 'est_employer_weekly_amount', 'est_weekly_non_taxable_amount', 'est_goodwork_weekly_amount', 'est_total_employer_amount', 'est_total_goodwork_amount', 'est_total_contract_amount'] as $item)
                <div class="col-md-6 mb-3 {{ $jobdetails->$item != $offerdetails->$item ? 'ss-job-view-off-text-fst-dv' : '' }}">
                    <span class="mt-3">{{ ucwords(str_replace('_', ' ', $item)) }}</span>
                    <h6>{{ $offerdetails->$item ?? '----' }}</h6>
                </div>
            @endforeach
        </ul>
        <div class="ss-job-apl-on-offer-btn">
            <button class="ss-acpect-offer-btn" data-offer_id="{{$offer_id}}" onclick="accept_job_offer(this)">Accept Offer</button>
            <ul>
                <li><button  type="button" class="ss-counter-btn" data-id="{{$model->id}}" data-type="counter" onclick="fetch_job_content(this)">Counter offer</button></li>
                <li><button type="button" class="ss-reject-offer-btn" data-offer_id="{{$offer_id}}" onclick="reject_job_offer(this)">Reject Offer</button></li>
            </ul>
        </div>
    </div>
</div>
</div>
