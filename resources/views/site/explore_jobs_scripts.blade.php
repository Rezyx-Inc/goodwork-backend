@section('js')

<script type="text/javascript">

    const applyButton = () => {
        window.location.href = "{{ route('worker.login') }}";
    }

    var isLoggedIn = @json(auth()->guard('frontend')->check());

    function redirectToJobDetails(job, users) {
        if (isLoggedIn) {
            window.location.href = `worker/job/${job.id}/details`;
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
        let askRecruiter = `<a class="ask_recruiter_href" href="{{ route('worker.login') }}" >Ask recruiter</a>`;

        // set modal title
        document.querySelector("#exampleModal .modal-title").innerHTML = `job ID: ${job.job_id}`


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
                                    <ul class="ss-s-jb-apl-on-inf-txt-ul terms_item ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Est. Weekly Rate</span>
                                        </li>
                                        <li>
                                             <h6>
                                                $${(Number(job.weekly_pay) || 0).toFixed(2)}
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

</script>
