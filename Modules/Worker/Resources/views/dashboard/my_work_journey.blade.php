@extends('worker::layouts.main')
@section('mytitle', 'My Work Journey')
@section('content')
<!--Main layout-->
<main style="padding-top: 130px; padding-bottom: 100px;" class="ss-main-body-sec">
  <div class="container">

    <div class="ss-my-work-jorny-btn-dv">
      <div class="row">
        <div class="col-lg-6">
          <h2>My Work Journey</h2>
        </div>
  
        <div class="col-lg-6">
          <div class="ss-my-work-tab-div">
              {{-- <ul onclick="myFunction(event)" id='navList'> --}}
              <ul>
                  <li><a href="{{route('my-work-journey')}}" class="ss-saved-btn {{ ( request()->route()->getName() == 'my-work-journey' ) ? 'active' :'' }}">Saved</a></li>
                  <li><a href="{{route('applied-jobs')}}" class="ss-applied-btn {{ ( request()->route()->getName() == 'applied-jobs' ) ? 'active' :'' }}">Applied</a></li>
                  <li><a href="{{route('offered-jobs')}}" class="ss-offered-btn {{ ( request()->route()->getName() == 'offered-jobs' ) ? 'active' :'' }}">Offered</a></li>
                  <li><a href="{{route('hired-jobs')}}" class="ss-hired-btn {{ ( request()->route()->getName() == 'hired-jobs' ) ? 'active' :'' }}">Hired</a></li>
                  <li><a href="{{route('past-jobs')}}" class="ss-past-btn {{ ( request()->route()->getName() == 'past-jobs' ) ? 'active' :'' }}">Past</a></li>
              </ul>
          </div>
        </div>
      </div>
    </div>

    <!--------------my work journey saved---------------->
    <div class="ss-job-apply-on-view-detls-mn-dv ss-my-work-jorny-sved-div">
      <div class="ss-job-apply-on-tx-bx-hed-dv">
          <ul>
              <li><p>Recruiter</p></li>
              {{-- <li><img  src="{{URL::asset('images/nurses/profile/'.$model->recruiter->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';"/>{{$model->recruiter->first_name.' '.$model->recruiter->last_name}}</li> --}}
          </ul>
  
          <ul>
              <li>
                  <span>{{$model->id}}</span>
                  <h6>{{$model->getOfferCount()}} Applied</h6>
              </li>
          </ul>
      </div>
  
      <div class="ss-jb-aap-on-txt-abt-dv">
          <h5>About job</h5>
          <ul>
              <li>
                  <h6>Employer Name</h6>
                  <p>{{$model->facility->name ?? 'NA'}}</p>
              </li>
              <li>
                  <h6>Date Posted</h6>
                  <p>{{Carbon\Carbon::parse($model->created_at)->format('M d')}}</p>
              </li>
              <li>
                  <h6>Type</h6>
                  <p>{{$model->job_type}}</p>
              </li>
              <li>
                  <h6>Terms</h6>
                  <p>{{$model->terms}}</p>
              </li>
  
          </ul>
      </div>
  
  
      <div class="ss-jb-apply-on-disc-txt">
          <h5>Description</h5>
          <p>{{$model->description}}<a href="#">Read More</a></p>
      </div>
  
  
     <!-------Work Information------->
      <div class="ss-jb-apl-oninfrm-mn-dv">
          <ul class="ss-jb-apply-on-inf-hed">
              <li><h5>Work Information</h5></li>
              <li><h5>Your Information</h5></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>Diploma</span>
                  <h6>College Diploma</h6>
              </li>
              <li><p>Did you really graduate?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>drivers license</span>
                  <h6>Required</h6>
              </li>
              <li><p>Are you really allowed to drive?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>Worked at Facility Before</span>
                  <h6>In the last 18 months</h6>
              </li>
              <li><p>Are you sure you never worked here as staff?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>SS# or SS Card</span>
                  <h6>Last 4 digits of SS#</h6>
              </li>
              <li><p>Yes we need your SS# to submit you</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-blue">
              <li>
                  <span>Profession</span>
                  <h6>{{$model->profession}}</h6>
              </li>
              <li><p>What kind of professioal are you?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-pink">
              <li>
                  <span>Specialty</span>
                  <h6>{{str_replace(',',', ',$model->preferred_specialty)}}</h6>
              </li>
              <li><p>What's your specialty?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Professional Licensure</span>
              <h6>{{$model->job_location}}</h6>
              </li>
              <li><p>Where are you licensed?</p></li>
          </ul>
  
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Experience</span>
              <h6>{{str_replace(',',', ',$model->preferred_experience)}} Years</h6>
              </li>
              <li><p>How long have you done this?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  @php
                      $vaccines = explode(',', $model->vaccinations);
                  @endphp
                  <span>Vaccinations & Immunizations</span>
                  @foreach ($vaccines as $v)
                  <h6>{{$v}} Required</h6>
                  @endforeach
              </li>
              <li>
                  @foreach ($vaccines as $v)
                  <p>Did you get the {{$v}} Vaccines?</p>
                  @endforeach
  
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>References</span>
                  <h6>{{$model->number_of_references}}  references </h6>
                  <h6>{{$model->recency_of_reference}} months Recency</h6>
              </li>
              <li>
                  <p>Who are your References?</p>
                  <p>Is this from your last assignment?</p>
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  @php
                      $certificates = explode(',', $model->certificate);
                  @endphp
                  <span>Certifications</span>
                  @foreach ($certificates as $v)
                  <h6>{{$v}} Required</h6>
                  @endforeach
              </li>
              <li>
                  <p></p>
                  @foreach ($certificates as $v)
                  @if($v=='BLS')
                  <p>You don't have a {{$v}}?</p>
                  @else
                  <p>No {{$v}}?</p>
                  @endif
                  @endforeach
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Skills checklist</span>
              <h6>{{str_replace(',', ', ',$model->skills)}} </h6>
  
              </li>
              <li><p>Upload your latest skills checklist</p>
  
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>Eligible to work in the US</span>
                  <h6>Required</h6>
                  {{-- <h6>Flu 2022 Preferred</h6> --}}
              </li>
              <li>
                  <p>Does Congress allow you to work here?</p>
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Urgency</span>
              <h6>{{$model->urgency}} </h6>
  
              </li>
              <li>
                  <p>How quickly you can be ready to submit?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span># Of positions available</span>
              <h6>{{$model->position_available - $model->getOfferCount() }} of {{$model->position_available}}</h6>
              </li>
              <li>
                  <p>You have applied to # jobs?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>MSP</span>
              <h6>{{$model->msp}} </h6>
              </li>
              <li>
                  <p>Any MSPs you prefer to avoid?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>VMS</span>
              <h6>{{$model->vms}} </h6>
              </li>
              <li>
                  <p>Who's is your favorite VMS?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Block Scheduling</span>
              <h6>{{$model->block_scheduling}} </h6>
              </li>
              <li>
                  <p>Do you want Block Scheduling?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Float Requirements</span>
              <h6>{{$model->float_requirement}} </h6>
              </li>
              <li>
                  <p>Are you willing float to?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility Shift Cancellation Policy</span>
              <h6>{{$model->facility_shift_cancelation_policy}} </h6>
              </li>
              <li>
                  <p>What terms do you prefere?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Contact Termination Policy</span>
              <h6>{{$model->contract_termination_policy}} </h6>
              </li>
              <li>
                  <p>What terms do you prefere?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Traveller Distance From Facility</span>
              <h6>{{$model->traveler_distance_from_facility}} </h6>
              </li>
              <li>
                  <p>Where does the IRS think you live?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility</span>
              <h6>{{$model->facility_id}} </h6>
              </li>
              <li>
                  <p>What Facilities have you worked at?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility's Parent System</span>
              <h6>{{$model->facilitys_parent_system}} </h6>
              </li>
              <li>
                  <p>What Facilities would you like to work at?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility Average Rating</span>
              <h6>{{$model->facility_average_rating}} </h6>
              </li>
              <li>
                  <p>Your average rating by your facilities?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Recruiter Average Rating</span>
              <h6>{{$model->recruiter_average_rating}} </h6>
              </li>
              <li>
                  <p>Your average rating by your recruiters?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Employer Average Rating</span>
              <h6>{{$model->employer_average_rating}} </h6>
              </li>
              <li>
                  <p>Your average rating by your employers?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Clinical Setting</span>
              <h6>{{$model->clinical_setting}} </h6>
              </li>
              <li>
                  <p>What setting do you prefer?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Patient Ratio</span>
              <h6>{{$model->Patient_ratio}} </h6>
              </li>
              <li>
                  <p>How many patients can you handle?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>EMR</span>
              <h6>{{$model->emr}} </h6>
              </li>
              <li>
                  <p>What EMRs have you used?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Unit</span>
              <h6>{{$model->Unit}} </h6>
              </li>
              <li>
                  <p>Fav Unit?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Department</span>
              <h6>{{$model->Department}} </h6>
              </li>
              <li>
                  <p>Fav Department?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Bed Size</span>
              <h6>{{$model->Bed_Size}} </h6>
              </li>
              <li>
                  <p>King or California king?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Trauma Level</span>
              <h6>{{$model->Trauma_Level}} </h6>
              </li>
              <li>
                  <p>Ideal Trauma Level?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Scrub Color</span>
              <h6>{{$model->scrub_color}} </h6>
              </li>
              <li>
                  <p>Fav scrub brand?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility City</span>
              <h6>{{$model->job_city}} </h6>
              </li>
              <li>
                  <p>Cities you'd like to work?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility State Code</span>
              <h6>{{$model->job_state}} </h6>
              </li>
              <li>
                  <p>States you'd like to work?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Interview dates</span>
              <h6>IInterview dates </h6>
              </li>
              <li>
                  <p>Any days you'r not available?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Start date</span>
              <h6>{{ ($model->as_soon_as) ? 'As soon as possible' : $model->start_date}} </h6>
              </li>
              <li>
                  <p>When you can start?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>RTO</span>
              <h6>{{$model->rto}} </h6>
              </li>
              <li>
                  <p>Any time off?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Shift Time Of Day</span>
              <h6>{{$model->preferred_shift}} </h6>
              </li>
              <li>
                  <p>Fav shift?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Hours/Week</span>
              <h6>{{$model->hours_per_week}} </h6>
              </li>
              <li>
                  <p>Ideal hours per week?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Guaranteed Hours</span>
              <h6>{{$model->guaranteed_hours}} </h6>
              </li>
              <li>
                  <p>Open to jobs with no guaranteed hours?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Hours/Shift</span>
              <h6>{{$model->hours_shift}} </h6>
              </li>
              <li>
                  <p>Prefered hours per shift?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Weeks/Assignment</span>
              <h6>{{$model->preferred_assignment_duration}} </h6>
              </li>
              <li>
                  <p>How many weeks?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Shifts/Week</span>
              <h6>{{$model->weeks_shift}} </h6>
              </li>
              <li>
                  <p>Ideal shift per week?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Referral Bonus</span>
              <h6>{{$model->referral_bonus}} </h6>
              </li>
              <li>
                  <p># of people you have refered?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Sign-On Bonus</span>
              <h6>${{$model->sign_on_bonus}} </h6>
              </li>
              <li>
                  <p>What kind of bonus do you expect?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Completion Bonus</span>
              <h6>${{$model->completion_bonus}} </h6>
              </li>
              <li>
                  <p>What kind of bonus do you deserve?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Extension Bonus</span>
              <h6>${{$model->extension_bonus}} </h6>
              </li>
              <li>
                  <p>What are you comparing this to?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Other Bonus</span>
              <h6>${{$model->other_bonus}} </h6>
              </li>
              <li>
                  <p>Other bonuses you want?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>401K</span>
              <h6>{{$model->four_zero_one_k}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Health Insurance</span>
              <h6>{{$model->health_insaurance}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Dental</span>
              <h6>{{$model->dental}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Vision</span>
              <h6>{{$model->vision}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Actual Hourly Rate</span>
              <h6>${{$model->actual_hourly_rate}} </h6>
              </li>
              <li>
                  <p>What rate is fair?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Feels Like $/Hr</span>
              <h6>${{$model->feels_like_per_hour}} </h6>
              </li>
              <li>
                  <p>Does this seem fair based on the market?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Overtime</span>
              <h6>{{$model->overtime}} </h6>
              </li>
              <li>
                  <p>Would you work more overtime for higher OT rate?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Holiday</span>
              <h6>{{$model->holiday}} </h6>
              </li>
              <li>
                  <p>Any holiday you refuse to work?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>On call</span>
              <h6>{{$model->on_call}} </h6>
              </li>
              <li>
                  <p>Will you do call?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Call Back</span>
              <h6>{{$model->call_back}} </h6>
              </li>
              <li>
                  <p>Is this rate reasonable?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Orientation Rate</span>
              <h6>{{$model->orientation_rate}} </h6>
              </li>
              <li>
                  <p>Is this rate reasonable?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Weekly Taxable Amount</span>
              <h6>${{$model->weekly_taxable_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Employer Weekly Amount</span>
              <h6>${{$model->employer_weekly_amount}} </h6>
              </li>
              <li>
                  <p>What range is resonbable?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Weekly Non-Taxable Amount</span>
              <h6>${{$model->weekly_non_taxable_amount}} </h6>
              </li>
              <li>
                  <p>Are you going to duplicate expenses?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Goodwork Weekly Amount</span>
              <h6>${{$model->goodwork_weekly_amount}} </h6>
              </li>
              <li>
                  <p>You have 5 days left before your rate drops form 5% to 2% </p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Total Employer Amount</span>
              <h6>${{$model->total_employer_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Total Goodwork Amount</span>
              <h6>${{$model->total_goodwork_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Total Contract Amount</span>
              <h6>${{$model->total_contract_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
  
          <div class="ss-job-apl-on-app-btn">
              <button data-id="{{$model->id}}" onclick="apply_on_jobs(this, true)">Apply Now</button>
          </div>
  
      </div>
  
  </div>
    <!--------------my work journey saved---------------->

    <!--------------my work journey Applied---------------->

    <div class="ss-job-apply-on-view-detls-mn-dv ss-my-wrk-apply-mn-sec">
      <div class="ss-job-apply-on-tx-bx-hed-dv">
          <ul>
              <li><p>Recruiter</p></li>
              <li><img  src="{{URL::asset('images/nurses/profile/'.$model->recruiter->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';"/>{{$model->recruiter->first_name.' '.$model->recruiter->last_name}}</li>
          </ul>
  
          <ul>
              <li>
                  <span>{{$model->id}}</span>
                  <h6>{{$model->getOfferCount()}} Applied</h6>
              </li>
          </ul>
      </div>
  
      <div class="ss-jb-aap-on-txt-abt-dv">
          <h5>About job</h5>
          <ul>
              <li>
                  <h6>Employer Name</h6>
                  <p>{{$model->facility->name ?? 'NA'}}</p>
              </li>
              <li>
                  <h6>Date Posted</h6>
                  <p>{{Carbon\Carbon::parse($model->created_at)->format('M d')}}</p>
              </li>
              <li>
                  <h6>Type</h6>
                  <p>{{$model->job_type}}</p>
              </li>
              <li>
                  <h6>Terms</h6>
                  <p>{{$model->terms}}</p>
              </li>
  
          </ul>
      </div>
  
  
      <div class="ss-jb-apply-on-disc-txt">
          <h5>Description</h5>
          <p>{{$model->description}}<a href="#">Read More</a></p>
      </div>
  
  
     <!-------Work Information------->
      <div class="ss-jb-apl-oninfrm-mn-dv">
          <ul class="ss-jb-apply-on-inf-hed">
              <li><h5>Work Information</h5></li>
              <li><h5>Your Information</h5></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>Diploma</span>
                  <h6>College Diploma</h6>
              </li>
              <li><p>Did you really graduate?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>drivers license</span>
                  <h6>Required</h6>
              </li>
              <li><p>Are you really allowed to drive?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>Worked at Facility Before</span>
                  <h6>In the last 18 months</h6>
              </li>
              <li><p>Are you sure you never worked here as staff?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>SS# or SS Card</span>
                  <h6>Last 4 digits of SS#</h6>
              </li>
              <li><p>Yes we need your SS# to submit you</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-blue">
              <li>
                  <span>Profession</span>
                  <h6>{{$model->profession}}</h6>
              </li>
              <li><p>What kind of professioal are you?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-pink">
              <li>
                  <span>Specialty</span>
                  <h6>{{str_replace(',',', ',$model->preferred_specialty)}}</h6>
              </li>
              <li><p>What's your specialty?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Professional Licensure</span>
              <h6>{{$model->job_location}}</h6>
              </li>
              <li><p>Where are you licensed?</p></li>
          </ul>
  
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Experience</span>
              <h6>{{str_replace(',',', ',$model->preferred_experience)}} Years</h6>
              </li>
              <li><p>How long have you done this?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  @php
                      $vaccines = explode(',', $model->vaccinations);
                  @endphp
                  <span>Vaccinations & Immunizations</span>
                  @foreach ($vaccines as $v)
                  <h6>{{$v}} Required</h6>
                  @endforeach
              </li>
              <li>
                  @foreach ($vaccines as $v)
                  <p>Did you get the {{$v}} Vaccines?</p>
                  @endforeach
  
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>References</span>
                  <h6>{{$model->number_of_references}}  references </h6>
                  <h6>{{$model->recency_of_reference}} months Recency</h6>
              </li>
              <li>
                  <p>Who are your References?</p>
                  <p>Is this from your last assignment?</p>
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  @php
                      $certificates = explode(',', $model->certificate);
                  @endphp
                  <span>Certifications</span>
                  @foreach ($certificates as $v)
                  <h6>{{$v}} Required</h6>
                  @endforeach
              </li>
              <li>
                  <p></p>
                  @foreach ($certificates as $v)
                  @if($v=='BLS')
                  <p>You don't have a {{$v}}?</p>
                  @else
                  <p>No {{$v}}?</p>
                  @endif
                  @endforeach
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Skills checklist</span>
              <h6>{{str_replace(',', ', ',$model->skills)}} </h6>
  
              </li>
              <li><p>Upload your latest skills checklist</p>
  
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>Eligible to work in the US</span>
                  <h6>Required</h6>
                  {{-- <h6>Flu 2022 Preferred</h6> --}}
              </li>
              <li>
                  <p>Does Congress allow you to work here?</p>
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Urgency</span>
              <h6>{{$model->urgency}} </h6>
  
              </li>
              <li>
                  <p>How quickly you can be ready to submit?</p>
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span># Of positions available</span>
              <h6>{{$model->position_available - $model->getOfferCount() }} of {{$model->position_available}}</h6>
              </li>
              <li>
                  <p>You have applied to # jobs?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>MSP</span>
              <h6>{{$model->msp}} </h6>
              </li>
              <li>
                  <p>Any MSPs you prefer to avoid?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>VMS</span>
              <h6>{{$model->vms}} </h6>
              </li>
              <li>
                  <p>Who's is your favorite VMS?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Block Scheduling</span>
              <h6>{{$model->block_scheduling}} </h6>
              </li>
              <li>
                  <p>Do you want Block Scheduling?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Float Requirements</span>
              <h6>{{$model->float_requirement}} </h6>
              </li>
              <li>
                  <p>Are you willing float to?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility Shift Cancellation Policy</span>
              <h6>{{$model->facility_shift_cancelation_policy}} </h6>
              </li>
              <li>
                  <p>What terms do you prefere?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Contact Termination Policy</span>
              <h6>{{$model->contract_termination_policy}} </h6>
              </li>
              <li>
                  <p>What terms do you prefere?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Traveller Distance From Facility</span>
              <h6>{{$model->traveler_distance_from_facility}} </h6>
              </li>
              <li>
                  <p>Where does the IRS think you live?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility</span>
              <h6>{{$model->facility_id}} </h6>
              </li>
              <li>
                  <p>What Facilities have you worked at?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility's Parent System</span>
              <h6>{{$model->facilitys_parent_system}} </h6>
              </li>
              <li>
                  <p>What Facilities would you like to work at?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility Average Rating</span>
              <h6>{{$model->facility_average_rating}} </h6>
              </li>
              <li>
                  <p>Your average rating by your facilities?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Recruiter Average Rating</span>
              <h6>{{$model->recruiter_average_rating}} </h6>
              </li>
              <li>
                  <p>Your average rating by your recruiters?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Employer Average Rating</span>
              <h6>{{$model->employer_average_rating}} </h6>
              </li>
              <li>
                  <p>Your average rating by your employers?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Clinical Setting</span>
              <h6>{{$model->clinical_setting}} </h6>
              </li>
              <li>
                  <p>What setting do you prefer?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Patient Ratio</span>
              <h6>{{$model->Patient_ratio}} </h6>
              </li>
              <li>
                  <p>How many patients can you handle?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>EMR</span>
              <h6>{{$model->emr}} </h6>
              </li>
              <li>
                  <p>What EMRs have you used?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Unit</span>
              <h6>{{$model->Unit}} </h6>
              </li>
              <li>
                  <p>Fav Unit?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Department</span>
              <h6>{{$model->Department}} </h6>
              </li>
              <li>
                  <p>Fav Department?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Bed Size</span>
              <h6>{{$model->Bed_Size}} </h6>
              </li>
              <li>
                  <p>King or California king?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Trauma Level</span>
              <h6>{{$model->Trauma_Level}} </h6>
              </li>
              <li>
                  <p>Ideal Trauma Level?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Scrub Color</span>
              <h6>{{$model->scrub_color}} </h6>
              </li>
              <li>
                  <p>Fav scrub brand?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility City</span>
              <h6>{{$model->job_city}} </h6>
              </li>
              <li>
                  <p>Cities you'd like to work?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility State Code</span>
              <h6>{{$model->job_state}} </h6>
              </li>
              <li>
                  <p>States you'd like to work?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Interview dates</span>
              <h6>IInterview dates </h6>
              </li>
              <li>
                  <p>Any days you'r not available?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Start date</span>
              <h6>{{ ($model->as_soon_as) ? 'As soon as possible' : $model->start_date}} </h6>
              </li>
              <li>
                  <p>When you can start?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>RTO</span>
              <h6>{{$model->rto}} </h6>
              </li>
              <li>
                  <p>Any time off?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Shift Time Of Day</span>
              <h6>{{$model->preferred_shift}} </h6>
              </li>
              <li>
                  <p>Fav shift?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Hours/Week</span>
              <h6>{{$model->hours_per_week}} </h6>
              </li>
              <li>
                  <p>Ideal hours per week?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Guaranteed Hours</span>
              <h6>{{$model->guaranteed_hours}} </h6>
              </li>
              <li>
                  <p>Open to jobs with no guaranteed hours?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Hours/Shift</span>
              <h6>{{$model->hours_shift}} </h6>
              </li>
              <li>
                  <p>Prefered hours per shift?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Weeks/Assignment</span>
              <h6>{{$model->preferred_assignment_duration}} </h6>
              </li>
              <li>
                  <p>How many weeks?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Shifts/Week</span>
              <h6>{{$model->weeks_shift}} </h6>
              </li>
              <li>
                  <p>Ideal shift per week?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Referral Bonus</span>
              <h6>{{$model->referral_bonus}} </h6>
              </li>
              <li>
                  <p># of people you have refered?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Sign-On Bonus</span>
              <h6>${{$model->sign_on_bonus}} </h6>
              </li>
              <li>
                  <p>What kind of bonus do you expect?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Completion Bonus</span>
              <h6>${{$model->completion_bonus}} </h6>
              </li>
              <li>
                  <p>What kind of bonus do you deserve?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Extension Bonus</span>
              <h6>${{$model->extension_bonus}} </h6>
              </li>
              <li>
                  <p>What are you comparing this to?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Other Bonus</span>
              <h6>${{$model->other_bonus}} </h6>
              </li>
              <li>
                  <p>Other bonuses you want?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>401K</span>
              <h6>{{$model->four_zero_one_k}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Health Insurance</span>
              <h6>{{$model->health_insaurance}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Dental</span>
              <h6>{{$model->dental}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Vision</span>
              <h6>{{$model->vision}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Actual Hourly Rate</span>
              <h6>${{$model->actual_hourly_rate}} </h6>
              </li>
              <li>
                  <p>What rate is fair?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Feels Like $/Hr</span>
              <h6>${{$model->feels_like_per_hour}} </h6>
              </li>
              <li>
                  <p>Does this seem fair based on the market?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Overtime</span>
              <h6>{{$model->overtime}} </h6>
              </li>
              <li>
                  <p>Would you work more overtime for higher OT rate?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Holiday</span>
              <h6>{{$model->holiday}} </h6>
              </li>
              <li>
                  <p>Any holiday you refuse to work?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>On call</span>
              <h6>{{$model->on_call}} </h6>
              </li>
              <li>
                  <p>Will you do call?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Call Back</span>
              <h6>{{$model->call_back}} </h6>
              </li>
              <li>
                  <p>Is this rate reasonable?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Orientation Rate</span>
              <h6>{{$model->orientation_rate}} </h6>
              </li>
              <li>
                  <p>Is this rate reasonable?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Weekly Taxable Amount</span>
              <h6>${{$model->weekly_taxable_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Employer Weekly Amount</span>
              <h6>${{$model->employer_weekly_amount}} </h6>
              </li>
              <li>
                  <p>What range is resonbable?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Weekly Non-Taxable Amount</span>
              <h6>${{$model->weekly_non_taxable_amount}} </h6>
              </li>
              <li>
                  <p>Are you going to duplicate expenses?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Goodwork Weekly Amount</span>
              <h6>${{$model->goodwork_weekly_amount}} </h6>
              </li>
              <li>
                  <p>You have 5 days left before your rate drops form 5% to 2% </p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Total Employer Amount</span>
              <h6>${{$model->total_employer_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Total Goodwork Amount</span>
              <h6>${{$model->total_goodwork_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Total Contract Amount</span>
              <h6>${{$model->total_contract_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
  
          <div class="ss-job-apl-on-app-btn">
              <button type="button" disabled>Applied</button>
          </div>
  
      </div>
  
  </div>

  <!--------------my work journey Applied---------------->


  <!--------------my work journey offred---------------->

  <div class="ss-job-apply-on-view-detls-mn-dv ssmy-jorey-offred-mn-sec">
    <div class="ss-job-apply-on-tx-bx-hed-dv">
        <ul>
            <li><p>Recruiter</p></li>
            <li><img  src="{{URL::asset('images/nurses/profile/'.$model->recruiter->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';"/>{{$model->recruiter->first_name.' '.$model->recruiter->last_name}}</li>
        </ul>

        <ul>
            <li>
                <span>{{$model->id}}</span>
                <h6>{{$model->getOfferCount()}} Applied</h6>
            </li>
        </ul>
    </div>

    <div class="ss-jb-aap-on-txt-abt-dv">
        <h5>About job</h5>
        <ul>
            <li>
                <h6>Employer Name</h6>
                <p>{{$model->facility->name ?? 'NA'}}</p>
            </li>
            <li>
                <h6>Date Posted</h6>
                <p>{{Carbon\Carbon::parse($model->created_at)->format('M d')}}</p>
            </li>
            <li>
                <h6>Type</h6>
                <p>{{$model->job_type}}</p>
            </li>
            <li>
                <h6>Terms</h6>
                <p>{{$model->terms}}</p>
            </li>

        </ul>
    </div>


    <div class="ss-jb-apply-on-disc-txt">
        <h5>Description</h5>
        <p>{{$model->description}}<a href="#">Read More</a></p>
    </div>


   <!-------Work Information------->
    <div class="ss-jb-apl-oninfrm-mn-dv">
        <ul class="ss-jb-apply-on-inf-hed">
            <li><h5>Work Information</h5></li>
            <li><h5>Your Information</h5></li>
        </ul>

        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
                <span>Diploma</span>
                <h6>College Diploma</h6>
            </li>
            <li><p>Did you really graduate?</p></li>
        </ul>

        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
                <span>drivers license</span>
                <h6>Required</h6>
            </li>
            <li><p>Are you really allowed to drive?</p></li>
        </ul>

        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
                <span>Worked at Facility Before</span>
                <h6>In the last 18 months</h6>
            </li>
            <li><p>Are you sure you never worked here as staff?</p></li>
        </ul>

        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
                <span>SS# or SS Card</span>
                <h6>Last 4 digits of SS#</h6>
            </li>
            <li><p>Yes we need your SS# to submit you</p></li>
        </ul>

        <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-blue">
            <li>
                <span>Profession</span>
                <h6>{{$model->profession}}</h6>
            </li>
            <li><p>What kind of professioal are you?</p></li>
        </ul>

        <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-pink">
            <li>
                <span>Specialty</span>
                <h6>{{str_replace(',',', ',$model->preferred_specialty)}}</h6>
            </li>
            <li><p>What's your specialty?</p></li>
        </ul>

        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Professional Licensure</span>
            <h6>{{$model->job_location}}</h6>
            </li>
            <li><p>Where are you licensed?</p></li>
        </ul>


        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Experience</span>
            <h6>{{str_replace(',',', ',$model->preferred_experience)}} Years</h6>
            </li>
            <li><p>How long have you done this?</p></li>
        </ul>

        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
                @php
                    $vaccines = explode(',', $model->vaccinations);
                @endphp
                <span>Vaccinations & Immunizations</span>
                @foreach ($vaccines as $v)
                <h6>{{$v}} Required</h6>
                @endforeach
            </li>
            <li>
                @foreach ($vaccines as $v)
                <p>Did you get the {{$v}} Vaccines?</p>
                @endforeach

            </li>
        </ul>

        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
                <span>References</span>
                <h6>{{$model->number_of_references}}  references </h6>
                <h6>{{$model->recency_of_reference}} months Recency</h6>
            </li>
            <li>
                <p>Who are your References?</p>
                <p>Is this from your last assignment?</p>
            </li>
        </ul>

        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
                @php
                    $certificates = explode(',', $model->certificate);
                @endphp
                <span>Certifications</span>
                @foreach ($certificates as $v)
                <h6>{{$v}} Required</h6>
                @endforeach
            </li>
            <li>
                <p></p>
                @foreach ($certificates as $v)
                @if($v=='BLS')
                <p>You don't have a {{$v}}?</p>
                @else
                <p>No {{$v}}?</p>
                @endif
                @endforeach
            </li>
        </ul>

        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Skills checklist</span>
            <h6>{{str_replace(',', ', ',$model->skills)}} </h6>

            </li>
            <li><p>Upload your latest skills checklist</p>

            </li>
        </ul>

        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
                <span>Eligible to work in the US</span>
                <h6>Required</h6>
                {{-- <h6>Flu 2022 Preferred</h6> --}}
            </li>
            <li>
                <p>Does Congress allow you to work here?</p>
            </li>
        </ul>

        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Urgency</span>
            <h6>{{$model->urgency}} </h6>

            </li>
            <li>
                <p>How quickly you can be ready to submit?</p>
            </li>
        </ul>

        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span># Of positions available</span>
            <h6>{{$model->position_available - $model->getOfferCount() }} of {{$model->position_available}}</h6>
            </li>
            <li>
                <p>You have applied to # jobs?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>MSP</span>
            <h6>{{$model->msp}} </h6>
            </li>
            <li>
                <p>Any MSPs you prefer to avoid?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>VMS</span>
            <h6>{{$model->vms}} </h6>
            </li>
            <li>
                <p>Who's is your favorite VMS?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Block Scheduling</span>
            <h6>{{$model->block_scheduling}} </h6>
            </li>
            <li>
                <p>Do you want Block Scheduling?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Float Requirements</span>
            <h6>{{$model->float_requirement}} </h6>
            </li>
            <li>
                <p>Are you willing float to?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Facility Shift Cancellation Policy</span>
            <h6>{{$model->facility_shift_cancelation_policy}} </h6>
            </li>
            <li>
                <p>What terms do you prefere?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Contact Termination Policy</span>
            <h6>{{$model->contract_termination_policy}} </h6>
            </li>
            <li>
                <p>What terms do you prefere?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Traveller Distance From Facility</span>
            <h6>{{$model->traveler_distance_from_facility}} </h6>
            </li>
            <li>
                <p>Where does the IRS think you live?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Facility</span>
            <h6>{{$model->facility_id}} </h6>
            </li>
            <li>
                <p>What Facilities have you worked at?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Facility's Parent System</span>
            <h6>{{$model->facilitys_parent_system}} </h6>
            </li>
            <li>
                <p>What Facilities would you like to work at?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Facility Average Rating</span>
            <h6>{{$model->facility_average_rating}} </h6>
            </li>
            <li>
                <p>Your average rating by your facilities?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Recruiter Average Rating</span>
            <h6>{{$model->recruiter_average_rating}} </h6>
            </li>
            <li>
                <p>Your average rating by your recruiters?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Employer Average Rating</span>
            <h6>{{$model->employer_average_rating}} </h6>
            </li>
            <li>
                <p>Your average rating by your employers?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Clinical Setting</span>
            <h6>{{$model->clinical_setting}} </h6>
            </li>
            <li>
                <p>What setting do you prefer?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Patient Ratio</span>
            <h6>{{$model->Patient_ratio}} </h6>
            </li>
            <li>
                <p>How many patients can you handle?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>EMR</span>
            <h6>{{$model->emr}} </h6>
            </li>
            <li>
                <p>What EMRs have you used?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Unit</span>
            <h6>{{$model->Unit}} </h6>
            </li>
            <li>
                <p>Fav Unit?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Department</span>
            <h6>{{$model->Department}} </h6>
            </li>
            <li>
                <p>Fav Department?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Bed Size</span>
            <h6>{{$model->Bed_Size}} </h6>
            </li>
            <li>
                <p>King or California king?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Trauma Level</span>
            <h6>{{$model->Trauma_Level}} </h6>
            </li>
            <li>
                <p>Ideal Trauma Level?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Scrub Color</span>
            <h6>{{$model->scrub_color}} </h6>
            </li>
            <li>
                <p>Fav scrub brand?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Facility City</span>
            <h6>{{$model->job_city}} </h6>
            </li>
            <li>
                <p>Cities you'd like to work?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Facility State Code</span>
            <h6>{{$model->job_state}} </h6>
            </li>
            <li>
                <p>States you'd like to work?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Interview dates</span>
            <h6>IInterview dates </h6>
            </li>
            <li>
                <p>Any days you'r not available?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Start date</span>
            <h6>{{ ($model->as_soon_as) ? 'As soon as possible' : $model->start_date}} </h6>
            </li>
            <li>
                <p>When you can start?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>RTO</span>
            <h6>{{$model->rto}} </h6>
            </li>
            <li>
                <p>Any time off?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Shift Time Of Day</span>
            <h6>{{$model->preferred_shift}} </h6>
            </li>
            <li>
                <p>Fav shift?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Hours/Week</span>
            <h6>{{$model->hours_per_week}} </h6>
            </li>
            <li>
                <p>Ideal hours per week?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Guaranteed Hours</span>
            <h6>{{$model->guaranteed_hours}} </h6>
            </li>
            <li>
                <p>Open to jobs with no guaranteed hours?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Hours/Shift</span>
            <h6>{{$model->hours_shift}} </h6>
            </li>
            <li>
                <p>Prefered hours per shift?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Weeks/Assignment</span>
            <h6>{{$model->preferred_assignment_duration}} </h6>
            </li>
            <li>
                <p>How many weeks?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Shifts/Week</span>
            <h6>{{$model->weeks_shift}} </h6>
            </li>
            <li>
                <p>Ideal shift per week?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Referral Bonus</span>
            <h6>{{$model->referral_bonus}} </h6>
            </li>
            <li>
                <p># of people you have refered?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Sign-On Bonus</span>
            <h6>${{$model->sign_on_bonus}} </h6>
            </li>
            <li>
                <p>What kind of bonus do you expect?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Completion Bonus</span>
            <h6>${{$model->completion_bonus}} </h6>
            </li>
            <li>
                <p>What kind of bonus do you deserve?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Extension Bonus</span>
            <h6>${{$model->extension_bonus}} </h6>
            </li>
            <li>
                <p>What are you comparing this to?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Other Bonus</span>
            <h6>${{$model->other_bonus}} </h6>
            </li>
            <li>
                <p>Other bonuses you want?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>401K</span>
            <h6>{{$model->four_zero_one_k}} </h6>
            </li>
            <li>
                <p>How much do you want this?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Health Insurance</span>
            <h6>{{$model->health_insaurance}} </h6>
            </li>
            <li>
                <p>How much do you want this?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Dental</span>
            <h6>{{$model->dental}} </h6>
            </li>
            <li>
                <p>How much do you want this?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Vision</span>
            <h6>{{$model->vision}} </h6>
            </li>
            <li>
                <p>How much do you want this?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Actual Hourly Rate</span>
            <h6>${{$model->actual_hourly_rate}} </h6>
            </li>
            <li>
                <p>What rate is fair?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Feels Like $/Hr</span>
            <h6>${{$model->feels_like_per_hour}} </h6>
            </li>
            <li>
                <p>Does this seem fair based on the market?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Overtime</span>
            <h6>{{$model->overtime}} </h6>
            </li>
            <li>
                <p>Would you work more overtime for higher OT rate?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Holiday</span>
            <h6>{{$model->holiday}} </h6>
            </li>
            <li>
                <p>Any holiday you refuse to work?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>On call</span>
            <h6>{{$model->on_call}} </h6>
            </li>
            <li>
                <p>Will you do call?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Call Back</span>
            <h6>{{$model->call_back}} </h6>
            </li>
            <li>
                <p>Is this rate reasonable?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Orientation Rate</span>
            <h6>{{$model->orientation_rate}} </h6>
            </li>
            <li>
                <p>Is this rate reasonable?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Weekly Taxable Amount</span>
            <h6>${{$model->weekly_taxable_amount}} </h6>
            </li>
            {{-- <li>
                <p>?</p>
            </li> --}}
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Employer Weekly Amount</span>
            <h6>${{$model->employer_weekly_amount}} </h6>
            </li>
            <li>
                <p>What range is resonbable?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Weekly Non-Taxable Amount</span>
            <h6>${{$model->weekly_non_taxable_amount}} </h6>
            </li>
            <li>
                <p>Are you going to duplicate expenses?</p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Goodwork Weekly Amount</span>
            <h6>${{$model->goodwork_weekly_amount}} </h6>
            </li>
            <li>
                <p>You have 5 days left before your rate drops form 5% to 2% </p>
            </li>
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Total Employer Amount</span>
            <h6>${{$model->total_employer_amount}} </h6>
            </li>
            {{-- <li>
                <p>?</p>
            </li> --}}
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Total Goodwork Amount</span>
            <h6>${{$model->total_goodwork_amount}} </h6>
            </li>
            {{-- <li>
                <p>?</p>
            </li> --}}
        </ul>
        <ul class="ss-s-jb-apl-on-inf-txt-ul">
            <li>
            <span>Total Contract Amount</span>
            <h6>${{$model->total_contract_amount}} </h6>
            </li>
            {{-- <li>
                <p>?</p>
            </li> --}}
        </ul>

        <div class="ss-job-apl-on-offer-btn">
            <button class="ss-acpect-offer-btn">Accept Offer</button>
            <ul>
                <li><button  type="button" class="ss-counter-btn" data-id="{{$model->id}}" data-type="counter" onclick="fetch_job_content(this)">Counter offer</button></li>
                <li><button type="button" class="ss-reject-offer-btn">Reject Offer</button></li>
            </ul>
        </div>

    </div>

</div>

    
  <!--------------my work journey offred---------------->
    <!--------------my work journey Hired---------------->

    
    <div class="ss-job-apply-on-view-detls-mn-dv ss-my-work-jorny-hired">
      <div class="ss-job-apply-on-tx-bx-hed-dv">
          <ul>
              <li><p>Recruiter</p></li>
              <li><img  src="{{URL::asset('images/nurses/profile/'.$model->recruiter->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';"/>{{$model->recruiter->first_name.' '.$model->recruiter->last_name}}</li>
          </ul>
  
          <ul>
              <li>
                  <span>{{$model->id}}</span>
                  <h6>{{$model->getOfferCount()}} Applied</h6>
              </li>
          </ul>
      </div>
  
      <div class="ss-jb-aap-on-txt-abt-dv">
          <h5>About job</h5>
          <ul>
              <li>
                  <h6>Employer Name</h6>
                  <p>{{$model->facility->name ?? 'NA'}}</p>
              </li>
              <li>
                  <h6>Date Posted</h6>
                  <p>{{Carbon\Carbon::parse($model->created_at)->format('M d')}}</p>
              </li>
              <li>
                  <h6>Type</h6>
                  <p>{{$model->job_type}}</p>
              </li>
              <li>
                  <h6>Terms</h6>
                  <p>{{$model->terms}}</p>
              </li>
  
          </ul>
      </div>
  
  
      <div class="ss-jb-apply-on-disc-txt">
          <h5>Description</h5>
          <p>{{$model->description}}<a href="#">Read More</a></p>
      </div>
  
  
     <!-------Work Information------->
      <div class="ss-jb-apl-oninfrm-mn-dv">
          <ul class="ss-jb-apply-on-inf-hed">
              <li><h5>Work Information</h5></li>
              <li><h5>Your Information</h5></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>Diploma</span>
                  <h6>College Diploma</h6>
              </li>
              <li><p>Did you really graduate?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>drivers license</span>
                  <h6>Required</h6>
              </li>
              <li><p>Are you really allowed to drive?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>Worked at Facility Before</span>
                  <h6>In the last 18 months</h6>
              </li>
              <li><p>Are you sure you never worked here as staff?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>SS# or SS Card</span>
                  <h6>Last 4 digits of SS#</h6>
              </li>
              <li><p>Yes we need your SS# to submit you</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-blue">
              <li>
                  <span>Profession</span>
                  <h6>{{$model->profession}}</h6>
              </li>
              <li><p>What kind of professioal are you?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-pink">
              <li>
                  <span>Specialty</span>
                  <h6>{{str_replace(',',', ',$model->preferred_specialty)}}</h6>
              </li>
              <li><p>What's your specialty?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Professional Licensure</span>
              <h6>{{$model->job_location}}</h6>
              </li>
              <li><p>Where are you licensed?</p></li>
          </ul>
  
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Experience</span>
              <h6>{{str_replace(',',', ',$model->preferred_experience)}} Years</h6>
              </li>
              <li><p>How long have you done this?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  @php
                      $vaccines = explode(',', $model->vaccinations);
                  @endphp
                  <span>Vaccinations & Immunizations</span>
                  @foreach ($vaccines as $v)
                  <h6>{{$v}} Required</h6>
                  @endforeach
              </li>
              <li>
                  @foreach ($vaccines as $v)
                  <p>Did you get the {{$v}} Vaccines?</p>
                  @endforeach
  
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>References</span>
                  <h6>{{$model->number_of_references}}  references </h6>
                  <h6>{{$model->recency_of_reference}} months Recency</h6>
              </li>
              <li>
                  <p>Who are your References?</p>
                  <p>Is this from your last assignment?</p>
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  @php
                      $certificates = explode(',', $model->certificate);
                  @endphp
                  <span>Certifications</span>
                  @foreach ($certificates as $v)
                  <h6>{{$v}} Required</h6>
                  @endforeach
              </li>
              <li>
                  <p></p>
                  @foreach ($certificates as $v)
                  @if($v=='BLS')
                  <p>You don't have a {{$v}}?</p>
                  @else
                  <p>No {{$v}}?</p>
                  @endif
                  @endforeach
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Skills checklist</span>
              <h6>{{str_replace(',', ', ',$model->skills)}} </h6>
  
              </li>
              <li><p>Upload your latest skills checklist</p>
  
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>Eligible to work in the US</span>
                  <h6>Required</h6>
                  {{-- <h6>Flu 2022 Preferred</h6> --}}
              </li>
              <li>
                  <p>Does Congress allow you to work here?</p>
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Urgency</span>
              <h6>{{$model->urgency}} </h6>
  
              </li>
              <li>
                  <p>How quickly you can be ready to submit?</p>
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span># Of positions available</span>
              <h6>{{$model->position_available - $model->getOfferCount() }} of {{$model->position_available}}</h6>
              </li>
              <li>
                  <p>You have applied to # jobs?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>MSP</span>
              <h6>{{$model->msp}} </h6>
              </li>
              <li>
                  <p>Any MSPs you prefer to avoid?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>VMS</span>
              <h6>{{$model->vms}} </h6>
              </li>
              <li>
                  <p>Who's is your favorite VMS?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Block Scheduling</span>
              <h6>{{$model->block_scheduling}} </h6>
              </li>
              <li>
                  <p>Do you want Block Scheduling?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Float Requirements</span>
              <h6>{{$model->float_requirement}} </h6>
              </li>
              <li>
                  <p>Are you willing float to?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility Shift Cancellation Policy</span>
              <h6>{{$model->facility_shift_cancelation_policy}} </h6>
              </li>
              <li>
                  <p>What terms do you prefere?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Contact Termination Policy</span>
              <h6>{{$model->contract_termination_policy}} </h6>
              </li>
              <li>
                  <p>What terms do you prefere?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Traveller Distance From Facility</span>
              <h6>{{$model->traveler_distance_from_facility}} </h6>
              </li>
              <li>
                  <p>Where does the IRS think you live?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility</span>
              <h6>{{$model->facility_id}} </h6>
              </li>
              <li>
                  <p>What Facilities have you worked at?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility's Parent System</span>
              <h6>{{$model->facilitys_parent_system}} </h6>
              </li>
              <li>
                  <p>What Facilities would you like to work at?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility Average Rating</span>
              <h6>{{$model->facility_average_rating}} </h6>
              </li>
              <li>
                  <p>Your average rating by your facilities?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Recruiter Average Rating</span>
              <h6>{{$model->recruiter_average_rating}} </h6>
              </li>
              <li>
                  <p>Your average rating by your recruiters?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Employer Average Rating</span>
              <h6>{{$model->employer_average_rating}} </h6>
              </li>
              <li>
                  <p>Your average rating by your employers?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Clinical Setting</span>
              <h6>{{$model->clinical_setting}} </h6>
              </li>
              <li>
                  <p>What setting do you prefer?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Patient Ratio</span>
              <h6>{{$model->Patient_ratio}} </h6>
              </li>
              <li>
                  <p>How many patients can you handle?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>EMR</span>
              <h6>{{$model->emr}} </h6>
              </li>
              <li>
                  <p>What EMRs have you used?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Unit</span>
              <h6>{{$model->Unit}} </h6>
              </li>
              <li>
                  <p>Fav Unit?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Department</span>
              <h6>{{$model->Department}} </h6>
              </li>
              <li>
                  <p>Fav Department?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Bed Size</span>
              <h6>{{$model->Bed_Size}} </h6>
              </li>
              <li>
                  <p>King or California king?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Trauma Level</span>
              <h6>{{$model->Trauma_Level}} </h6>
              </li>
              <li>
                  <p>Ideal Trauma Level?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Scrub Color</span>
              <h6>{{$model->scrub_color}} </h6>
              </li>
              <li>
                  <p>Fav scrub brand?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility City</span>
              <h6>{{$model->job_city}} </h6>
              </li>
              <li>
                  <p>Cities you'd like to work?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility State Code</span>
              <h6>{{$model->job_state}} </h6>
              </li>
              <li>
                  <p>States you'd like to work?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Interview dates</span>
              <h6>IInterview dates </h6>
              </li>
              <li>
                  <p>Any days you'r not available?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Start date</span>
              <h6>{{ ($model->as_soon_as) ? 'As soon as possible' : $model->start_date}} </h6>
              </li>
              <li>
                  <p>When you can start?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>RTO</span>
              <h6>{{$model->rto}} </h6>
              </li>
              <li>
                  <p>Any time off?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Shift Time Of Day</span>
              <h6>{{$model->preferred_shift}} </h6>
              </li>
              <li>
                  <p>Fav shift?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Hours/Week</span>
              <h6>{{$model->hours_per_week}} </h6>
              </li>
              <li>
                  <p>Ideal hours per week?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Guaranteed Hours</span>
              <h6>{{$model->guaranteed_hours}} </h6>
              </li>
              <li>
                  <p>Open to jobs with no guaranteed hours?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Hours/Shift</span>
              <h6>{{$model->hours_shift}} </h6>
              </li>
              <li>
                  <p>Prefered hours per shift?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Weeks/Assignment</span>
              <h6>{{$model->preferred_assignment_duration}} </h6>
              </li>
              <li>
                  <p>How many weeks?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Shifts/Week</span>
              <h6>{{$model->weeks_shift}} </h6>
              </li>
              <li>
                  <p>Ideal shift per week?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Referral Bonus</span>
              <h6>{{$model->referral_bonus}} </h6>
              </li>
              <li>
                  <p># of people you have refered?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Sign-On Bonus</span>
              <h6>${{$model->sign_on_bonus}} </h6>
              </li>
              <li>
                  <p>What kind of bonus do you expect?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Completion Bonus</span>
              <h6>${{$model->completion_bonus}} </h6>
              </li>
              <li>
                  <p>What kind of bonus do you deserve?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Extension Bonus</span>
              <h6>${{$model->extension_bonus}} </h6>
              </li>
              <li>
                  <p>What are you comparing this to?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Other Bonus</span>
              <h6>${{$model->other_bonus}} </h6>
              </li>
              <li>
                  <p>Other bonuses you want?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>401K</span>
              <h6>{{$model->four_zero_one_k}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Health Insurance</span>
              <h6>{{$model->health_insaurance}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Dental</span>
              <h6>{{$model->dental}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Vision</span>
              <h6>{{$model->vision}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Actual Hourly Rate</span>
              <h6>${{$model->actual_hourly_rate}} </h6>
              </li>
              <li>
                  <p>What rate is fair?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Feels Like $/Hr</span>
              <h6>${{$model->feels_like_per_hour}} </h6>
              </li>
              <li>
                  <p>Does this seem fair based on the market?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Overtime</span>
              <h6>{{$model->overtime}} </h6>
              </li>
              <li>
                  <p>Would you work more overtime for higher OT rate?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Holiday</span>
              <h6>{{$model->holiday}} </h6>
              </li>
              <li>
                  <p>Any holiday you refuse to work?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>On call</span>
              <h6>{{$model->on_call}} </h6>
              </li>
              <li>
                  <p>Will you do call?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Call Back</span>
              <h6>{{$model->call_back}} </h6>
              </li>
              <li>
                  <p>Is this rate reasonable?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Orientation Rate</span>
              <h6>{{$model->orientation_rate}} </h6>
              </li>
              <li>
                  <p>Is this rate reasonable?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Weekly Taxable Amount</span>
              <h6>${{$model->weekly_taxable_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Employer Weekly Amount</span>
              <h6>${{$model->employer_weekly_amount}} </h6>
              </li>
              <li>
                  <p>What range is resonbable?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Weekly Non-Taxable Amount</span>
              <h6>${{$model->weekly_non_taxable_amount}} </h6>
              </li>
              <li>
                  <p>Are you going to duplicate expenses?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Goodwork Weekly Amount</span>
              <h6>${{$model->goodwork_weekly_amount}} </h6>
              </li>
              <li>
                  <p>You have 5 days left before your rate drops form 5% to 2% </p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Total Employer Amount</span>
              <h6>${{$model->total_employer_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Total Goodwork Amount</span>
              <h6>${{$model->total_goodwork_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Total Contract Amount</span>
              <h6>${{$model->total_contract_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
  
          <div class="ss-job-apl-on-app-btn">
              <button type="button" disabled>Chat</button>
          </div>
  
      </div>
  
  </div>
  



    <!--------------my work journey Hired---------------->

    <!--------------my work journey Past---------------->
    
    <div class="ss-job-apply-on-view-detls-mn-dv ss-my-jorney-past-mn-sec">
      <div class="ss-job-apply-on-tx-bx-hed-dv">
          <ul>
              <li><p>Recruiter</p></li>
              <li><img  src="{{URL::asset('images/nurses/profile/'.$model->recruiter->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';"/>{{$model->recruiter->first_name.' '.$model->recruiter->last_name}}</li>
          </ul>
  
          <ul>
              <li>
                  <span>{{$model->id}}</span>
                  <h6>{{$model->getOfferCount()}} Applied</h6>
              </li>
          </ul>
      </div>
  
      <div class="ss-jb-aap-on-txt-abt-dv">
          <h5>About job</h5>
          <ul>
              <li>
                  <h6>Employer Name</h6>
                  <p>{{$model->facility->name ?? 'NA'}}</p>
              </li>
              <li>
                  <h6>Date Posted</h6>
                  <p>{{Carbon\Carbon::parse($model->created_at)->format('M d')}}</p>
              </li>
              <li>
                  <h6>Type</h6>
                  <p>{{$model->job_type}}</p>
              </li>
              <li>
                  <h6>Terms</h6>
                  <p>{{$model->terms}}</p>
              </li>
  
          </ul>
      </div>
  
  
      <div class="ss-jb-apply-on-disc-txt">
          <h5>Description</h5>
          <p>{{$model->description}}<a href="#">Read More</a></p>
      </div>
  
  
     <!-------Work Information------->
      <div class="ss-jb-apl-oninfrm-mn-dv">
          <ul class="ss-jb-apply-on-inf-hed">
              <li><h5>Work Information</h5></li>
              <li><h5>Your Information</h5></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>Diploma</span>
                  <h6>College Diploma</h6>
              </li>
              <li><p>Did you really graduate?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>drivers license</span>
                  <h6>Required</h6>
              </li>
              <li><p>Are you really allowed to drive?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>Worked at Facility Before</span>
                  <h6>In the last 18 months</h6>
              </li>
              <li><p>Are you sure you never worked here as staff?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>SS# or SS Card</span>
                  <h6>Last 4 digits of SS#</h6>
              </li>
              <li><p>Yes we need your SS# to submit you</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-blue">
              <li>
                  <span>Profession</span>
                  <h6>{{$model->profession}}</h6>
              </li>
              <li><p>What kind of professioal are you?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-pink">
              <li>
                  <span>Specialty</span>
                  <h6>{{str_replace(',',', ',$model->preferred_specialty)}}</h6>
              </li>
              <li><p>What's your specialty?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Professional Licensure</span>
              <h6>{{$model->job_location}}</h6>
              </li>
              <li><p>Where are you licensed?</p></li>
          </ul>
  
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Experience</span>
              <h6>{{str_replace(',',', ',$model->preferred_experience)}} Years</h6>
              </li>
              <li><p>How long have you done this?</p></li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  @php
                      $vaccines = explode(',', $model->vaccinations);
                  @endphp
                  <span>Vaccinations & Immunizations</span>
                  @foreach ($vaccines as $v)
                  <h6>{{$v}} Required</h6>
                  @endforeach
              </li>
              <li>
                  @foreach ($vaccines as $v)
                  <p>Did you get the {{$v}} Vaccines?</p>
                  @endforeach
  
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>References</span>
                  <h6>{{$model->number_of_references}}  references </h6>
                  <h6>{{$model->recency_of_reference}} months Recency</h6>
              </li>
              <li>
                  <p>Who are your References?</p>
                  <p>Is this from your last assignment?</p>
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  @php
                      $certificates = explode(',', $model->certificate);
                  @endphp
                  <span>Certifications</span>
                  @foreach ($certificates as $v)
                  <h6>{{$v}} Required</h6>
                  @endforeach
              </li>
              <li>
                  <p></p>
                  @foreach ($certificates as $v)
                  @if($v=='BLS')
                  <p>You don't have a {{$v}}?</p>
                  @else
                  <p>No {{$v}}?</p>
                  @endif
                  @endforeach
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Skills checklist</span>
              <h6>{{str_replace(',', ', ',$model->skills)}} </h6>
  
              </li>
              <li><p>Upload your latest skills checklist</p>
  
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
                  <span>Eligible to work in the US</span>
                  <h6>Required</h6>
                  {{-- <h6>Flu 2022 Preferred</h6> --}}
              </li>
              <li>
                  <p>Does Congress allow you to work here?</p>
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Urgency</span>
              <h6>{{$model->urgency}} </h6>
  
              </li>
              <li>
                  <p>How quickly you can be ready to submit?</p>
              </li>
          </ul>
  
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span># Of positions available</span>
              <h6>{{$model->position_available - $model->getOfferCount() }} of {{$model->position_available}}</h6>
              </li>
              <li>
                  <p>You have applied to # jobs?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>MSP</span>
              <h6>{{$model->msp}} </h6>
              </li>
              <li>
                  <p>Any MSPs you prefer to avoid?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>VMS</span>
              <h6>{{$model->vms}} </h6>
              </li>
              <li>
                  <p>Who's is your favorite VMS?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Block Scheduling</span>
              <h6>{{$model->block_scheduling}} </h6>
              </li>
              <li>
                  <p>Do you want Block Scheduling?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Float Requirements</span>
              <h6>{{$model->float_requirement}} </h6>
              </li>
              <li>
                  <p>Are you willing float to?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility Shift Cancellation Policy</span>
              <h6>{{$model->facility_shift_cancelation_policy}} </h6>
              </li>
              <li>
                  <p>What terms do you prefere?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Contact Termination Policy</span>
              <h6>{{$model->contract_termination_policy}} </h6>
              </li>
              <li>
                  <p>What terms do you prefere?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Traveller Distance From Facility</span>
              <h6>{{$model->traveler_distance_from_facility}} </h6>
              </li>
              <li>
                  <p>Where does the IRS think you live?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility</span>
              <h6>{{$model->facility_id}} </h6>
              </li>
              <li>
                  <p>What Facilities have you worked at?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility's Parent System</span>
              <h6>{{$model->facilitys_parent_system}} </h6>
              </li>
              <li>
                  <p>What Facilities would you like to work at?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility Average Rating</span>
              <h6>{{$model->facility_average_rating}} </h6>
              </li>
              <li>
                  <p>Your average rating by your facilities?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Recruiter Average Rating</span>
              <h6>{{$model->recruiter_average_rating}} </h6>
              </li>
              <li>
                  <p>Your average rating by your recruiters?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Employer Average Rating</span>
              <h6>{{$model->employer_average_rating}} </h6>
              </li>
              <li>
                  <p>Your average rating by your employers?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Clinical Setting</span>
              <h6>{{$model->clinical_setting}} </h6>
              </li>
              <li>
                  <p>What setting do you prefer?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Patient Ratio</span>
              <h6>{{$model->Patient_ratio}} </h6>
              </li>
              <li>
                  <p>How many patients can you handle?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>EMR</span>
              <h6>{{$model->emr}} </h6>
              </li>
              <li>
                  <p>What EMRs have you used?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Unit</span>
              <h6>{{$model->Unit}} </h6>
              </li>
              <li>
                  <p>Fav Unit?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Department</span>
              <h6>{{$model->Department}} </h6>
              </li>
              <li>
                  <p>Fav Department?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Bed Size</span>
              <h6>{{$model->Bed_Size}} </h6>
              </li>
              <li>
                  <p>King or California king?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Trauma Level</span>
              <h6>{{$model->Trauma_Level}} </h6>
              </li>
              <li>
                  <p>Ideal Trauma Level?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Scrub Color</span>
              <h6>{{$model->scrub_color}} </h6>
              </li>
              <li>
                  <p>Fav scrub brand?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility City</span>
              <h6>{{$model->job_city}} </h6>
              </li>
              <li>
                  <p>Cities you'd like to work?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Facility State Code</span>
              <h6>{{$model->job_state}} </h6>
              </li>
              <li>
                  <p>States you'd like to work?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Interview dates</span>
              <h6>IInterview dates </h6>
              </li>
              <li>
                  <p>Any days you'r not available?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Start date</span>
              <h6>{{ ($model->as_soon_as) ? 'As soon as possible' : $model->start_date}} </h6>
              </li>
              <li>
                  <p>When you can start?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>RTO</span>
              <h6>{{$model->rto}} </h6>
              </li>
              <li>
                  <p>Any time off?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Shift Time Of Day</span>
              <h6>{{$model->preferred_shift}} </h6>
              </li>
              <li>
                  <p>Fav shift?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Hours/Week</span>
              <h6>{{$model->hours_per_week}} </h6>
              </li>
              <li>
                  <p>Ideal hours per week?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Guaranteed Hours</span>
              <h6>{{$model->guaranteed_hours}} </h6>
              </li>
              <li>
                  <p>Open to jobs with no guaranteed hours?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Hours/Shift</span>
              <h6>{{$model->hours_shift}} </h6>
              </li>
              <li>
                  <p>Prefered hours per shift?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Weeks/Assignment</span>
              <h6>{{$model->preferred_assignment_duration}} </h6>
              </li>
              <li>
                  <p>How many weeks?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Shifts/Week</span>
              <h6>{{$model->weeks_shift}} </h6>
              </li>
              <li>
                  <p>Ideal shift per week?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Referral Bonus</span>
              <h6>{{$model->referral_bonus}} </h6>
              </li>
              <li>
                  <p># of people you have refered?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Sign-On Bonus</span>
              <h6>${{$model->sign_on_bonus}} </h6>
              </li>
              <li>
                  <p>What kind of bonus do you expect?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Completion Bonus</span>
              <h6>${{$model->completion_bonus}} </h6>
              </li>
              <li>
                  <p>What kind of bonus do you deserve?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Extension Bonus</span>
              <h6>${{$model->extension_bonus}} </h6>
              </li>
              <li>
                  <p>What are you comparing this to?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Other Bonus</span>
              <h6>${{$model->other_bonus}} </h6>
              </li>
              <li>
                  <p>Other bonuses you want?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>401K</span>
              <h6>{{$model->four_zero_one_k}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Health Insurance</span>
              <h6>{{$model->health_insaurance}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Dental</span>
              <h6>{{$model->dental}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Vision</span>
              <h6>{{$model->vision}} </h6>
              </li>
              <li>
                  <p>How much do you want this?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Actual Hourly Rate</span>
              <h6>${{$model->actual_hourly_rate}} </h6>
              </li>
              <li>
                  <p>What rate is fair?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Feels Like $/Hr</span>
              <h6>${{$model->feels_like_per_hour}} </h6>
              </li>
              <li>
                  <p>Does this seem fair based on the market?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Overtime</span>
              <h6>{{$model->overtime}} </h6>
              </li>
              <li>
                  <p>Would you work more overtime for higher OT rate?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Holiday</span>
              <h6>{{$model->holiday}} </h6>
              </li>
              <li>
                  <p>Any holiday you refuse to work?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>On call</span>
              <h6>{{$model->on_call}} </h6>
              </li>
              <li>
                  <p>Will you do call?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Call Back</span>
              <h6>{{$model->call_back}} </h6>
              </li>
              <li>
                  <p>Is this rate reasonable?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Orientation Rate</span>
              <h6>{{$model->orientation_rate}} </h6>
              </li>
              <li>
                  <p>Is this rate reasonable?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Weekly Taxable Amount</span>
              <h6>${{$model->weekly_taxable_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Employer Weekly Amount</span>
              <h6>${{$model->employer_weekly_amount}} </h6>
              </li>
              <li>
                  <p>What range is resonbable?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Weekly Non-Taxable Amount</span>
              <h6>${{$model->weekly_non_taxable_amount}} </h6>
              </li>
              <li>
                  <p>Are you going to duplicate expenses?</p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Goodwork Weekly Amount</span>
              <h6>${{$model->goodwork_weekly_amount}} </h6>
              </li>
              <li>
                  <p>You have 5 days left before your rate drops form 5% to 2% </p>
              </li>
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Total Employer Amount</span>
              <h6>${{$model->total_employer_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Total Goodwork Amount</span>
              <h6>${{$model->total_goodwork_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
          <ul class="ss-s-jb-apl-on-inf-txt-ul">
              <li>
              <span>Total Contract Amount</span>
              <h6>${{$model->total_contract_amount}} </h6>
              </li>
              {{-- <li>
                  <p>?</p>
              </li> --}}
          </ul>
  
          <div class="ss-job-apl-on-app-btn">
              <button type="button" disabled>Chat</button>
          </div>
  
      </div>
  
  </div>
  
  <!--------------my work journey Past---------------->
  </div>

</main>
@stop

@section('js')
<script>
    $(document).ready(function(){
      $(".ss-saved-btn").click(function(){
        $(".ss-my-work-jorny-sved-div").show();
        $(".ss-my-wrk-apply-mn-sec").hide();
        $(".ssmy-jorey-offred-mn-sec").hide();
        $(".ss-my-work-jorny-hired").hide();
        $(".ss-my-jorney-past-mn-sec").hide();
      });

      $(".ss-applied-btn").click(function(){
       $(".ss-my-wrk-apply-mn-sec").show();
        $(".ss-my-work-jorny-sved-div").hide();
        $(".ssmy-jorey-offred-mn-sec").hide();
        $(".ss-my-work-jorny-hired").hide();
        $(".ss-my-jorney-past-mn-sec").hide();
      });

      $(".ss-offered-btn").click(function(){
        $(".ssmy-jorey-offred-mn-sec").show();
        $(".ss-my-work-jorny-sved-div").hide();
        $(".ss-my-wrk-apply-mn-sec").hide();
        $(".ss-my-work-jorny-hired").hide();
        $(".ss-my-jorney-past-mn-sec").hide();
      });

      $(".ss-hired-btn").click(function(){
        $(".ss-my-work-jorny-hired").show();
        $(".ss-my-work-jorny-sved-div").hide();
        $(".ss-my-wrk-apply-mn-sec").hide();
        $(".ssmy-jorey-offred-mn-sec").hide();
        $(".ss-my-jorney-past-mn-sec").hide();
      });

      $(".ss-past-btn").click(function(){
        $(".ss-my-jorney-past-mn-sec").show();
        $(".ss-my-work-jorny-sved-div").hide();
        $(".ss-my-wrk-apply-mn-sec").hide();
        $(".ss-my-work-jorny-hired").hide();
        $(".ssmy-jorey-offred-mn-sec").hide();
      });


    });
    </script>

    <!----------saved script----->
    <script>
    $(document).ready(function(){
      $(".my-work-sved-job-div1").click(function(){
        $(".ss-saved-jb-dtls-dv1").show();
        $(".ss-saved-jb-dtls-dv2").hide();
      });

      $(".my-work-sved-job-div2").click(function(){
       $(".ss-saved-jb-dtls-dv2").show();
        $(".ss-saved-jb-dtls-dv1").hide();
      });
    });
      </script>


      <!----------Applyed script----->
    <script>
    $(document).ready(function(){
      $(".ss-my-wrk-aply-jb-dv1").click(function(){
        $(".ss-applyd-jb-dtl-bx-1").show();
        $(".ss-applyd-jb-dtl-bx-2").hide();
        $(".ss-applyd-jb-dtl-bx-3").hide();
      });

      $(".ss-my-wrk-aply-jb-dv2").click(function(){
        $(".ss-applyd-jb-dtl-bx-2").show();
        $(".ss-applyd-jb-dtl-bx-1").hide();
        $(".ss-applyd-jb-dtl-bx-3").hide();
      });

      $(".ss-my-wrk-aply-jb-dv3").click(function(){
        $(".ss-applyd-jb-dtl-bx-3").show();
        $(".ss-applyd-jb-dtl-bx-2").hide();
        $(".ss-applyd-jb-dtl-bx-1").hide();
      });
    });


      </script>


       <!----------offred script----->
    <script>
    $(document).ready(function(){
      $(".ss-jb-offrd-dv1").click(function(){
        $(".ss-my-jrny-offrd-dtls1").show();
        $(".ss-my-jrny-offrd-dtls2").hide();
      });

      $(".ss-jb-offrd-dv2").click(function(){
        $(".ss-my-jrny-offrd-dtls2").show();
        $(".ss-my-jrny-offrd-dtls1").hide();
      });


    });

</script>




    <script>
     function myFunction(e) {
      if (document.querySelector('#navList a.active') !== null) {
        document.querySelector('#navList a.active').classList.remove('active');
      }
      e.target.className = "active";
    }
    </script>
@stop


@section('js')
<script>
    $(document).ready(function(){
      $(".ss-saved-btn").click(function(){
        $(".ss-my-work-jorny-sved-div").show();
        $(".ss-my-wrk-apply-mn-sec").hide();
        $(".ssmy-jorey-offred-mn-sec").hide();
        $(".ss-my-work-jorny-hired").hide();
        $(".ss-my-jorney-past-mn-sec").hide();
      });

      $(".ss-applied-btn").click(function(){
       $(".ss-my-wrk-apply-mn-sec").show();
        $(".ss-my-work-jorny-sved-div").hide();
        $(".ssmy-jorey-offred-mn-sec").hide();
        $(".ss-my-work-jorny-hired").hide();
        $(".ss-my-jorney-past-mn-sec").hide();
      });

      $(".ss-offered-btn").click(function(){
        $(".ssmy-jorey-offred-mn-sec").show();
        $(".ss-my-work-jorny-sved-div").hide();
        $(".ss-my-wrk-apply-mn-sec").hide();
        $(".ss-my-work-jorny-hired").hide();
        $(".ss-my-jorney-past-mn-sec").hide();
      });

      $(".ss-hired-btn").click(function(){
        $(".ss-my-work-jorny-hired").show();
        $(".ss-my-work-jorny-sved-div").hide();
        $(".ss-my-wrk-apply-mn-sec").hide();
        $(".ssmy-jorey-offred-mn-sec").hide();
        $(".ss-my-jorney-past-mn-sec").hide();
      });

      $(".ss-past-btn").click(function(){
        $(".ss-my-jorney-past-mn-sec").show();
        $(".ss-my-work-jorny-sved-div").hide();
        $(".ss-my-wrk-apply-mn-sec").hide();
        $(".ss-my-work-jorny-hired").hide();
        $(".ssmy-jorey-offred-mn-sec").hide();
      });

    });
    </script>

    <!----------saved script----->
    <script>
    $(document).ready(function(){
      $(".my-work-sved-job-div1").click(function(){
        $(".ss-saved-jb-dtls-dv1").show();
        $(".ss-saved-jb-dtls-dv2").hide();
      });

      $(".my-work-sved-job-div2").click(function(){
       $(".ss-saved-jb-dtls-dv2").show();
        $(".ss-saved-jb-dtls-dv1").hide();
      });
    });
      </script>


      <!----------Applyed script----->
    <script>
    $(document).ready(function(){
      $(".ss-my-wrk-aply-jb-dv1").click(function(){
        $(".ss-applyd-jb-dtl-bx-1").show();
        $(".ss-applyd-jb-dtl-bx-2").hide();
        $(".ss-applyd-jb-dtl-bx-3").hide();
      });

      $(".ss-my-wrk-aply-jb-dv2").click(function(){
        $(".ss-applyd-jb-dtl-bx-2").show();
        $(".ss-applyd-jb-dtl-bx-1").hide();
        $(".ss-applyd-jb-dtl-bx-3").hide();
      });

      $(".ss-my-wrk-aply-jb-dv3").click(function(){
        $(".ss-applyd-jb-dtl-bx-3").show();
        $(".ss-applyd-jb-dtl-bx-2").hide();
        $(".ss-applyd-jb-dtl-bx-1").hide();
      });
    });


      </script>


       <!----------offred script----->
    <script>
    $(document).ready(function(){
      $(".ss-jb-offrd-dv1").click(function(){
        $(".ss-my-jrny-offrd-dtls1").show();
        $(".ss-my-jrny-offrd-dtls2").hide();
      });

      $(".ss-jb-offrd-dv2").click(function(){
        $(".ss-my-jrny-offrd-dtls2").show();
        $(".ss-my-jrny-offrd-dtls1").hide();
      });


    });

</script>




    <script>
     function myFunction(e) {
      if (document.querySelector('#navList a.active') !== null) {
        document.querySelector('#navList a.active').classList.remove('active');
      }
      e.target.className = "active";
    }
    </script>

    <script>
        $(document).ready(function(){
            $('.job-list')[0].click('active');
        });
        // function fetch_job_content(obj)
        // {
            
        //     if (!$(obj).hasClass('active')) {
        //         $('.job-list').removeClass('active')
        //         ajaxindicatorstart();
        //         $.ajaxSetup({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             }
        //         });
        //         $.ajax({
        //             url: full_path+'worker/fetch-job-content',
        //             type: 'POST',
        //             dataType: 'json',
        //             // processData: false,
        //             // contentType: false,
        //             data: {
        //                 jid: $(obj).data('id'),
        //                 type: $(obj).data('type')
        //             },
        //             success: function (resp) {
        //                 console.log(resp);
        //                 ajaxindicatorstop();
        //                 if (resp.success) {

        //                     $('.job-content').html(resp.content);
        //                     $(obj).addClass('active')
        //                 }
        //             },
        //             error: function (resp) {
        //                 console.log(resp);
        //                 ajaxindicatorstop();
        //             }
        //         });
        //     }
        // }

        // function accept_job_offer(obj){
        //     ajaxindicatorstart();
        //     $.ajaxSetup({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             }
        //         });
        //         $.ajax({
        //             url: full_path+'accept-offer',
        //             type: 'POST',
        //             dataType: 'json',
        //             data: {
        //                 offer_id: $(obj).data('offer_id')
        //             },
        //             success: function (resp) {
        //                 console.log(resp);
        //                 ajaxindicatorstop();
        //                 if (resp.success) {

        //                     $('.job-content').html(resp.content);
        //                     $(obj).addClass('active')
        //                 }
        //             },
        //             error: function (resp) {
        //                 console.log(resp);
        //                 ajaxindicatorstop();
        //             }
        //         });

        // }

        function store_counter_offer(obj)
        {
                ajaxindicatorstart();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/worker/post-counter-offer',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        jid: $(obj).data('id'),
                        type: $(obj).data('type')
                    },
                    success: function (resp) {
                        console.log(resp);
                        ajaxindicatorstop();
                        if (resp.success) {

                            $('.job-content').html(resp.content);
                            $(obj).addClass('active')
                        }
                    },
                    error: function (resp) {
                        console.log(resp);
                        ajaxindicatorstop();
                    }
                });
            
        }
    </script>
@stop

