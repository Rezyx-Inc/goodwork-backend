                    <div width="50px" height="50px" class="ss-job-apply-on-tx-bx-hed-dv">
                        <ul style="display:block; text-align:justify;">
                            <li>
                                <p>Recruiter</p>
                            </li>
                            <li>
                                <img src="{{ asset('uploads/' . $userdetails->image) }}"
                                    onerror="this.onerror=null;this.src='{{ asset('frontend/img/profile-pic-big.png') }}';"
                                    id="preview" width="50px" height="50px" style="object-fit: cover;"
                                    class="rounded-3" alt="Profile Picture">
                                {{ $userdetails->first_name }} {{ $userdetails->last_name }}
                            </li>
                        </ul>
                        <ul style="display:block;">
                            <li>
                                <span>{{ $jobdetails->id }}</span>
                                <h6>Org Job Id - {{ $jobdetails->job_id }}</h6>
                                <h6>{{ $jobappliedcount }} Applied</h6>
                            </li>
                        </ul>
                    </div>
                    <div class="ss-jb-aap-on-txt-abt-dv">
                        <h5>About job</h5>
                        <ul>
                            <li>
                                <h6>Organization Name</h6>
                                @php 
                                    $organization = \App\Models\User::where('id', $jobdetails->organization_id)->first();
                                @endphp
                                <p>{{ $organization->organization_name ?? 'Missing Information' }}</p>
                                </p>
                            </li>
                            <li>
                                <h6>Date Posted</h6>
                                <p>{{ date_format($jobdetails['created_at'], 'Y-m-d') }}</p>
                            </li>
                            <li>
                                <h6>Type</h6>
                                <p>{{ $jobdetails['job_type'] ?? 'Missing Job Type Information' }}</p>
                            </li>
                            <li>
                                <h6>Terms</h6>
                                <p>{{ $jobdetails['terms'] ?? 'Missing Terms Information' }}</p>
                            </li>
                        </ul>
                    </div>
                    <div class="ss-jb-apply-on-disc-txt col-md-12 mt-4 mb-3">
                        <h5>Description</h5>
                        <p class="mb-3">{{ $jobdetails->description ?? 'Missing Description information' }}</p>
                    </div>

                    <div class="ss-job-ap-on-offred-new-dv">
                        <ul class="row ss-s-jb-apl-on-inf-txt-ul mb-4"
                            style="display: flex; justify-content: center; align-items: center;">

                            <div class="col-md-12 mb-4 collapse-container">
                                <p>
                                    <a class="btn first-collapse" data-toggle="collapse">
                                        Summary
                                    </a>
                                </p>
                            </div>

                            {{-- Job Id --}}
                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Org Job Id</p>
                                <h6>{{ $jobdetails->job_id ?? 'Missing Org Job Id information' }}</h6>
                            </div>

                            {{-- Job Type --}}
                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Job Type</p>
                                <h6>{{ $jobdetails->job_type ?? 'Missing Job Type information' }}</h6>
                            </div>

                            {{-- Terms --}}
                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Terms</p>
                                <h6>{{ $jobdetails->terms ?? 'Missing Terms information' }}</h6>
                            </div>

                            {{-- Profession --}}
                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Profession</p>
                                <h6>{{ $jobdetails->profession ?? 'Missing Profession information' }}</h6>
                            </div>

                            {{-- Specialty --}}
                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Specialty</p>
                                <h6>{{ $jobdetails->preferred_specialty ?? 'Missing Specialty information' }}</h6>
                            </div>

                            {{-- Actual Hourly rate --}}
                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Actual Hourly rate</p>
                                <h6>{{ $jobdetails->actual_hourly_rate ?? 'Missing Actual Hourly rate information' }}
                                </h6>
                            </div>

                            {{-- Weekly Pay --}}
                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Weekly Pay</p>
                                <h6>{{ $jobdetails->weekly_pay ?? 'Missing Weekly Pay information' }}</h6>
                            </div>

                            {{-- Weekly Hours --}}
                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Hours/Week</p>
                                <h6>{{ $jobdetails->hours_per_week ?? 'Missing Hours/Week information' }}</h6>
                            </div>

                            {{-- job_state --}}
                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">State</p>
                                <h6>{{ $jobdetails->job_state ?? 'Missing State information' }}</h6>
                            </div>

                            {{-- job_city --}}

                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">City</p>
                                <h6>{{ $jobdetails->job_city ?? 'Missing City information' }}</h6>
                            </div>

                            {{-- is_resume --}}

                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Resume</p>
                                <h6>{{ $jobdetails->is_resume ? 'Required' : 'Not Required' }}</h6>
                            </div>



                            <div class="col-md-12 mb-4 collapse-container">
                                <p>
                                    <a class="btn first-collapse" data-toggle="collapse" href="#collapse-1"
                                        role="button" aria-expanded="false" aria-controls="collapseExample">
                                        Shift
                                    </a>
                                </p>
                            </div>

                            <div class="row collapse" id="collapse-1">

                                <ul class="row ss-s-jb-apl-on-inf-txt-ul mb-4""
                                    style="display: flex; justify-content: center; align-items: center;">
                                    {{-- SHIFT --}}

                                    {{-- preferred_shift_duration --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Shift Time</p>
                                        <h6>{{ $jobdetails->preferred_shift_duration ?? 'Missing Shift Duration information' }}
                                        </h6>
                                    </div>

                                    {{--  Guaranteed Hours --}}
                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Guaranteed Hrs/wk</p>
                                        <h6>{{ $jobdetails->guaranteed_hours ?? 'Missing Guaranteed Hours information' }}
                                        </h6>
                                    </div>

                                    {{-- hours_shift --}}
                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Reg Hrs/Shift</p>
                                        <h6>{{ $jobdetails->hours_shift ?? 'Missing Shift information' }}</h6>
                                    </div>

                                    {{-- weeks_shift --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Shifts/Wk</p>
                                        <h6>{{ $jobdetails->weeks_shift ?? 'Missing Weeks/Shift information' }}</h6>
                                    </div>
                                    {{-- Wks/Contract --}}
                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Wks/Contract</p>
                                        <h6>{{ $jobdetails->preferred_assignment_duration ?? 'Missing Wks/Contract information' }}
                                        </h6>
                                    </div>

                                    {{-- start_date --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Start Date</p>
                                        <h6>{{ $jobdetails->start_date ? $jobdetails->start_date : 'As Soon As Possible' }}
                                        </h6>
                                    </div>

                                    {{-- end date --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">End Date</p>
                                        <h6>{{ $jobdetails->end_date ?? 'Missing End Date information' }}</h6>
                                        </h6>
                                    </div>

                                    {{-- rto --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">RTO</p>
                                        <h6>{{ $jobdetails->rto ?? 'Missing RTO information' }}</h6>
                                    </div>
                                </ul>

                            </div>

                            <div class="col-md-12 mb-4 collapse-container">
                                <p>
                                    <a id="collapse-2-btn" class="btn first-collapse" data-toggle="collapse"
                                        href="#collapse-2" role="button" aria-expanded="false"
                                        aria-controls="collapseExample">
                                        Pay
                                    </a>
                                </p>
                            </div>
                            <div class="row collapse" id="collapse-2">

                                <ul class="row ss-s-jb-apl-on-inf-txt-ul mb-4""
                                    style="display: flex; justify-content: center; align-items: center;">

                                    {{-- overtime --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Overtime</p>
                                        <h6>{{ $jobdetails->overtime ?? 'Missing Overtime information' }}</h6>
                                    </div>

                                    {{-- On Call --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">On Call</p>
                                        <h6>{{ $jobdetails->on_call_rate ?? 'Missing On Call information' }}</h6>
                                    </div>

                                    {{--  Call Back --}}


                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Call Back</p>
                                        <h6>{{ $jobdetails->call_back_rate ?? 'Missing Call Back information' }}</h6>
                                    </div>

                                    {{--  Orientation Rate --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Orientation Rate</p>
                                        <h6>{{ $jobdetails->orientation_rate ?? 'Missing Orientation Rate information' }}
                                        </h6>
                                    </div>

                                    {{-- Weekly Taxable amount --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Weekly Taxable amount</p>
                                        <h6>{{ $jobdetails->weekly_taxable_amount ?? 'Missing Weekly Taxable amount information' }}
                                        </h6>
                                    </div>

                                    {{--  Weekly non-taxable amount --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Weekly non-taxable amount</p>
                                        <h6>{{ $jobdetails->weekly_non_taxable_amount ?? 'Missing Weekly non-taxable amount information' }}
                                        </h6>
                                    </div>

                                    {{-- Feels like per hour --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Feels Like $/hr</p>
                                        <h6>{{ $jobdetails->feels_like_per_hour ?? 'Missing Feels Like $/hr information' }}
                                        </h6>
                                    </div>

                                    {{--  Goodwork Weekly Amount --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Goodwork Weekly Amount</p>
                                        <h6>{{ $jobdetails->goodwork_weekly_amount ?? 'Missing Goodwork Weekly Amount information' }}
                                        </h6>
                                    </div>

                                    {{-- Referral Bonus  --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Referral Bonus</p>
                                        <h6>${{ $jobdetails->referral_bonus ?? 'Missing Referral Bonus information' }}
                                        </h6>
                                    </div>

                                    {{--  Sign-On Bonus --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Sign-On Bonus</p>
                                        <h6>${{ $jobdetails->sign_on_bonus ?? 'Missing Sign-On Bonus information' }}
                                        </h6>
                                    </div>

                                    {{--  Extension Bonus --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Extension Bonus</p>
                                        <h6>${{ $jobdetails->extension_bonus ?? 'Missing Extension Bonus information' }}
                                        </h6>
                                    </div>

                                    {{--  Completion Bonus --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Completion Bonus</p>
                                        <h6>${{ $jobdetails->completion_bonus ?? 'Missing Completion Bonus information' }}
                                        </h6>
                                    </div>

                                    {{--  Other Bonus --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Other Bonus</p>
                                        <h6>${{ $jobdetails->other_bonus ?? 'Missing Other Bonus information' }}</h6>
                                    </div>

                                    {{--  Total Organization Amount --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Total Organization Amount</p>
                                        <h6>{{ $jobdetails->total_organization_amount ?? 'Missing Total Organization Amount information' }}
                                        </h6>
                                    </div>

                                    {{--  Total Goodwork Amount --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Total Goodwork Amount</p>
                                        <h6>{{ $jobdetails->total_goodwork_amount ?? 'Missing Total Goodwork Amount information' }}
                                        </h6>
                                    </div>

                                    {{--  Total Contract Amount --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Total Contract Amount</p>
                                        <h6>{{ $jobdetails->total_contract_amount ?? 'Missing Total Contract Amount information' }}
                                        </h6>
                                    </div>

                                    {{-- Pay Frequency --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Pay Frequency</p>
                                        <h6>{{ $jobdetails->pay_frequency ?? 'Missing Pay Frequency information' }}
                                        </h6>
                                    </div>

                                    {{--  benefits --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Benefits</p>
                                        <h6>{{ $jobdetails->benefits ?? 'Missing Benefits information' }}</h6>
                                    </div>
                                </ul>

                            </div>

                            <div class="col-md-12 mb-4 collapse-container">
                                <p>
                                    <a class="btn first-collapse" data-toggle="collapse" href="#collapse-3"
                                        role="button" aria-expanded="false" aria-controls="collapseExample">
                                        Location
                                    </a>
                                </p>
                            </div>
                            <div class="row collapse" id="collapse-3">

                                <ul class="row ss-s-jb-apl-on-inf-txt-ul mb-4""
                                    style="display: flex; justify-content: center; align-items: center;">

                                    {{-- clinical_setting --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Clinical Setting</p>
                                        <h6>{{ $jobdetails->clinical_setting ?? 'Missing Clinical Setting information' }}
                                        </h6>
                                    </div>

                                    {{-- preferred_work_location --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Preferred Work Location</p>
                                        <h6>{{ $jobdetails->preferred_work_location ?? 'Missing Preferred Work Location information' }}
                                        </h6>
                                    </div>


                                    {{--  facility_name --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Facility</p>
                                        <h6>{{ $jobdetails->facility_name ?? 'Missing Facility Name information' }}
                                        </h6>
                                    </div>


                                    {{-- facilitys_parent_system --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Facility`s Parent System</p>
                                        <h6>{{ $jobdetails->facilitys_parent_system ?? 'Missing  information' }}</h6>
                                    </div>

                                    {{-- facility_shift_cancelation_policy --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Facility Shift Cancellation Policy</p>
                                        <h6>{{ $jobdetails->facility_shift_cancelation_policy ?? 'Missing Facility Shift Cancellation Policy information' }}
                                        </h6>
                                    </div>

                                    {{--  facility_shift_cancelation_policy --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Contract Termination Policy</p>
                                        <h6>{{ $jobdetails->contract_termination_policy ?? 'Missing Contract Termination Policy information' }}
                                        </h6>
                                    </div>

                                    {{--  facility_shift_cancelation_policy --}}

                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Min Distance From Facility</p>
                                        <h6>{{ $jobdetails->traveler_distance_from_facility ?? 'Missing Traveler Distance From Facility information' }}
                                            miles Maximum</h6>
                                    </div>
                                </ul>
                            </div>
                            <div class="col-md-12 mb-4 collapse-container">
                                <p>
                                    <a class="btn first-collapse" data-toggle="collapse" href="#collapse-4"
                                        role="button" aria-expanded="false" aria-controls="collapseExample">
                                        Certs & Licences
                                    </a>
                                </p>
                            </div>
                            <div class="row collapse" id="collapse-4">

                                <ul class="row ss-s-jb-apl-on-inf-txt-ul mb-4""
                                    style="display: flex; justify-content: center; align-items: center;">

                                    {{--  Professional Licensure --}}
                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <p class="mt-3">Professional Licensure</p>
                                        <h6>{{ $jobdetails->job_location ?? 'Missing Professional Licensure information' }}
                                        </h6>
                                    </div>



                                    {{-- Certifications --}}

                                    @if (isset($jobdetails->certificate))
                                        @foreach (explode(',', $jobdetails->certificate) as $value)
                                            @if (isset($value))
                                                <div class="col-lg-5 col-md-5 col-sm-12">
                                                    <p class="mt-3">Certifications</p>
                                                    <h6>{{ $value }}</h6>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>

                    

                    <div class="col-md-12 mb-4 collapse-container">
                        <p>
                            <a class="btn first-collapse" data-toggle="collapse" href="#collapse-5" role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                                Work Info
                            </a>
                        </p>
                    </div>
                    <div class="row collapse" id="collapse-5">

                        <ul class="row ss-s-jb-apl-on-inf-txt-ul mb-4"
                            style="display: flex; justify-content: center; align-items: center;">

                            {{--  Urgency --}}

                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Urgency</p>
                                <h6>{{ $jobdetails->urgency ?? 'Missing Urgency information' }}</h6>
                            </div>


                            {{--  preferred_experience --}}

                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Experience</p>
                                <h6>{{ $jobdetails->preferred_experience ?? '0' }} Years</h6>
                            </div>

                            {{--  Number of References --}}

                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">No of References</p>
                                <h6>{{ $jobdetails->number_of_references ?? 'Missing  No of References information' }}
                            </div>

                            {{-- skills  --}}

                            @if (isset($jobdetails->skills))
                                @foreach (explode(',', $jobdetails->skills) as $value)
                                    @if (isset($value))
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <p class="mt-3">Skills</p>
                                            <h6>{{ $value }}</h6>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="col-lg-5 col-md-5 col-sm-12">
                                    <p class="mt-3">Skills</p>
                                    <h6>Missing Skills information</h6>
                                </div>
                            @endif

                            {{--  block_scheduling --}}

                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Block scheduling</p>
                                <h6>{{ $jobdetails->block_scheduling == '1' ? 'Yes' : ($jobdetails->block_scheduling == '0' ? 'No' : 'Missing  information') }}
                                </h6>
                            </div>

                            {{-- float_requirement --}}

                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Float Requirement</p>
                                <h6>{{ $jobdetails->float_requirement == '1' ? 'Yes' : ($jobdetails->float_requirement == '0' ? 'No' : 'Missing  information') }}
                                </h6>
                            </div>


                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Patient ratio</p>
                                <h6>{{ $jobdetails->Patient_ratio ?? 'Missing Patient ratio information' }}</h6>
                            </div>


                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">EMR</p>
                                <h6>{{ $jobdetails->Emr ?? 'Missing EMR information' }}</h6>
                            </div>

                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Unit</p>
                                <h6>{{ $jobdetails->Unit ?? 'Missing Unit information' }}</h6>
                            </div>
                        </ul>
                    </div>

                    <div class="col-md-12 mb-4 collapse-container">
                        <p>
                            <a class="btn first-collapse" data-toggle="collapse" href="#collapse-6" role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                                ID & Tax Info
                            </a>
                        </p>
                    </div>
                    <div class="row collapse" id="collapse-6">
                        <ul class="row ss-s-jb-apl-on-inf-txt-ul mb-4"
                            style="display: flex; justify-content: center; align-items: center;">

                            {{-- nurse_classification --}}
                            @if (isset($jobdetails->nurse_classification))
                                @foreach (explode(',', $jobdetails->nurse_classification) as $value)
                                    @if (isset($value))
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <p class="mt-3">Nurse Classification</p>
                                            <h6>{{ $value }}</h6>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="col-lg-5 col-md-5 col-sm-12">
                                    <p class="mt-3">Nurse Classification</p>
                                    <h6>Missing Nurse Classification information</h6>
                                </div>
                            @endif
                        </ul>
                    </div>

                    <div class="col-md-12 mb-4 collapse-container">
                        <p>
                            <a class="btn first-collapse" data-toggle="collapse" href="#collapse-7" role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                                Medical info
                            </a>
                        </p>
                    </div>
                    <div class="row collapse" id="collapse-7">

                        <ul class="row ss-s-jb-apl-on-inf-txt-ul mb-4"
                            style="display: flex; justify-content: center; align-items: center;">
                            {{--  vaccinations --}}


                            @if (isset($jobdetails->vaccinations))
                                @foreach (explode(',', $jobdetails->vaccinations) as $value)
                                    @if (isset($value))
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <p class="mt-3">Vaccinations & Immunizations</p>
                                            <h6>{{ $value }}</h6>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="col-lg-5 col-md-5 col-sm-12">
                                    <p class="mt-3">Vaccinations & Immunizations</p>
                                    <h6>Missing Vaccinations & Immunizations information</h6>
                                </div>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-12 mb-4 collapse-container">
                        <p>
                            <a class="btn first-collapse" data-toggle="collapse" href="#collapse-8" role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                                Other Info
                            </a>
                        </p>
                    </div>
                    <div class="row collapse" id="collapse-8">

                        <ul class="row ss-s-jb-apl-on-inf-txt-ul mb-4"
                            style="display: flex; justify-content: center; align-items: center;">

                            {{-- job_name --}}

                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Job Name</p>
                                <h6>{{ $jobdetails->job_name ?? 'Missing Job Name information' }}</h6>
                            </div>

                            {{--  scrub_color --}}

                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Scrub Color</p>
                                <h6>{{ $jobdetails->scrub_color ?? 'Missing Scrub Color information' }}</h6>
                            </div>

                            {{-- holiday as skills --}}

                            @if (isset($jobdetails->holidays))
                                @foreach (explode(',', $jobdetails->holidays) as $value)
                                    @if (isset($value))
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <p class="mt-3">Holidays</p>
                                            <h6>{{ $value }}</h6>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="col-lg-5 col-md-5 col-sm-12">
                                    <p class="mt-3">Holidays</p>
                                    <h6>Missing Holidays information</h6>
                                </div>
                            @endif

                            {{-- professional_state_licensure --}}

                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <p class="mt-3">Professional State Licensure</p>
                                <h6>{{ $jobdetails->professional_state_licensure ?? 'Missing Professional State Licensure information' }}
                                </h6>
                            </div>
                        </ul>
                    </div>


                    </div>

                    {{-- <div class="ss-jb-aap-on-txt-abt-dv">
                        <div class="application-job-slider" style="text-align: justify;">
                            <div class="ss-chng-appli-slider-mn-dv" style="width:auto;">
                                <h5>Workers Applied ({{ $jobappliedcount }})</h5>
                                <div
                                    class="{{ $jobappliedcount > 1 ? 'owl-carousel application-job-slider-owl' : '' }} application-job-slider">
                                    @foreach ($jobapplieddetails as $value)
                                        @php
                                            $nursedetails = \App\Models\Nurse::join(
                                                'users',
                                                'nurses.user_id',
                                                '=',
                                                'users.id',
                                            )
                                                ->where('nurses.id', $value->worker_user_id)
                                                ->select('nurses.*', 'users.*')
                                                ->first();
                                        @endphp
                                        <div style="width:auto;"
                                            class="ss-chng-appli-slider-sml-dv ss-expl-applicion-div-box"
                                            onclick="opportunitiesType('{{ $type }}', '{{ $value->id }}', 'useralldetails')">
                                            <div class="ss-job-id-no-name">
                                                <ul>
                                                    <li class="w-50"><span>{{ $value->worker_user_id }}</span></li>
                                                    <li class="w-50">
                                                        <p>Recently Added</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <ul class="ss-expl-applicion-ul1">
                                                <li class="w-auto">
                                                    <img width="50px" height="50px"
                                                        src="{{ asset('uploads/' . $nursedetails->image) }}"
                                                        onerror="this.onerror=null;this.src='{{ asset('frontend/img/profile-pic-big.png') }}';"
                                                        id="preview" width="50px" height="50px"
                                                        style="object-fit: cover;" class="rounded-3"
                                                        alt="Profile Picture">
                                                </li>
                                                <li class="w-auto">
                                                    <h6>{{ $nursedetails->first_name }} {{ $nursedetails->last_name }}
                                                    </h6>
                                                </li>
                                            </ul>
                                            <ul class="ss-expl-applicion-ul2 d-block">
                                                @if (isset($nursedetails->highest_nursing_degree))
                                                    <li class="w-auto">
                                                        <a
                                                            href="#">{{ $nursedetails->highest_nursing_degree }}</a>
                                                    </li>
                                                @else
                                                    <li class="w-auto"></li>
                                                @endif
                                                @if (isset($nursedetails->specialty))
                                                    <li class="w-auto">
                                                        <a href="#">{{ $nursedetails->specialty }}</a>
                                                    </li>
                                                @else
                                                    <li class="w-auto"></li>
                                                @endif
                                                @if (isset($nursedetails->worker_shift_time_of_day))
                                                    <li class="w-auto">
                                                        <a
                                                            href="#">{{ $nursedetails->worker_shift_time_of_day }}</a>
                                                    </li>
                                                @else
                                                    <li class="w-auto"></li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    @if ($type != 'closed')
                        <div class="col-md-12" style="margin-bottom:30px;">
                            <div class="row" style="display: flex; justify-content: center; align-items: center;">
                                @if ($type == 'onhold')
                                    <div class="col-md-12">
                                        <a href="javascript:void(0)" class="ss-send-offer-btn d-block"
                                            onclick="changeStatus('unhidejob', '{{ $jobdetails->id }}')">Publish
                                            Job</a>
                                    </div>
                                @else
                                    <div class="col-md-5">
                                        <a href="javascript:void(0)" class="ss-reject-offer-btn d-block"
                                            onclick="changeStatus('hidejob', '{{ $jobdetails->id }}')">Hide Job</a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="javascript:void(0)" class="ss-send-offer-btn d-block"
                                            onclick="job_details_to_edit('{{ $jobdetails->id }}')">Edit Job</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    </ul>
                    </div>
                    </div>

                    @if (empty($jobdetails))
                        <div class="text-center"><span>Data Not found</span></div>
                    @endif

                    <style>
                        .btn.first-collapse,
                        .btn.first-collapse:hover,
                        .btn.first-collapse:focus,
                        .btn.first-collapse:active {
                            /* background-color: rgb(255, 237, 238); */
                            background-color: #fff8fd;
                            color: rgb(65, 41, 57);
                            font-size: 14px;
                            font-family: 'Neue Kabel';
                            font-style: normal;
                            width: 100%;
                        }
                        
                    </style>
