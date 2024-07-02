@extends('worker::layouts.main')
@section('mytitle', 'My Profile')
@section('css')
@stop
@section('content')
<!--Main layout-->
<main style="padding-top: 130px" class="ss-main-body-sec">
    <div class="container">
  <!-------------------applay-on jobs--------->

      <div class="ss-apply-on-jb-mmn-dv">
        <div class="row">
          <div class="col-lg-12">
        <h2>Explore</h2>

        <!------------->
        <div class="ss-apply-on-jb-mmn-dv-box-divs">
        <div class="ss-job-prfle-sec">
            <p>{{$model->job_type}} <span>+50 Applied</span></p>
            <h4>{{$model->job_name}}</h4>
            <h6>{{ $model->facility->name ?? 'NA' }}</h6>
            <ul>
              <li><a href="javascript:void(0)"><img src="{{URL::asset('frontend/img/location.png')}}"> {{$model->job_city}}, {{$model->job_state}}</a></li>
              <li><a href="javascript:void(0)"><img src="{{URL::asset('frontend/img/calendar.png')}}">  {{$model->preferred_assignment_duration}} wks</a></li>
              <li><a href="javascript:void(0)"><img src="{{URL::asset('frontend/img/dollarcircle.png')}}">  {{$model->weekly_pay}}/wk</a></li>
            </ul>
            <h5>Recently Added</h5>
            <a href="javascript:void(0)" data-id="{{$model->id}}" onclick="save_jobs(this)" class="ss-jb-prfl-save-ico">
                @if($jobSaved->check_if_saved($model->id))
                <img src="{{URL::asset('frontend/img/bookmark.png')}}" />
                @else
                <img src="{{URL::asset('frontend/img/job-icon-bx-Vector.png')}}" />
                @endif
            </a>
          </div>

  </div>
  <!----------------jobs applay view details--------------->

  <div class="ss-job-apply-on-view-detls-mn-dv">
    <div class="ss-job-apply-on-tx-bx-hed-dv">
      <ul>
      <li><p>Recruiter</p></li>
      {{-- <li><img src="{{URL::asset('images/nurses/profile/'.$model->recruiter->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';"/>{{$model->recruiter->first_name}} {{$model->recruiter->last_name}}</li> --}}
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
        {{-- <p>{{$model->recruiter->first_name}} {{$model->recruiter->last_name}}</p> --}}
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
    @php
    $matches = [];
    
    foreach ($model->matchWithWorker() as $key => $closure) {
        $matches[$key] = $closure();
    }

    
    @endphp
    @php
    $userMatches = [];
    foreach ($model->matchWithWorker() as $key => $closure) {
        $userMatches[$key] = $closure();
    }
    @endphp
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['diploma']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
            <span>Diploma</span>
            <h6>College Diploma</h6>
        </li>
        <li><p data-target="file" data-hidden_name="diploma_cer" data-hidden_value="Yes" data-href="{{route('info-required')}}" data-title="Did you really graduate?" data-name="diploma" onclick="open_modal(this)">Did you really graduate?</p></li>
    </ul>

    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['driving_license']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
            <span>drivers license</span>
            <h6>Required</h6>
        </li>
        <li><p data-target="file" data-hidden_name="dl_cer" data-hidden_value="Yes" data-href="{{route('info-required')}}" data-title="Are you really allowed to drive?" data-name="driving_license" onclick="open_modal(this)">Are you really allowed to drive?</p></li>
    </ul>

    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['worked_at_facility_before']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
            <span>Worked at Facility Before</span>
            <h6>In the last 18 months</h6>
        </li>
        <li><p data-target="binary" data-title="Are you sure you never worked here as staff?" data-name="worked_at_facility_before" onclick="open_modal(this)">Are you sure you never worked here as staff?</p></li>
    </ul>

    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['worker_ss_number']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
            <span>SS# or SS Card</span>
            <h6>Last 4 digits of SS#</h6>
        </li>
        <li><p  data-target="input" data-title="Yes we need your SS# to submit you" data-placeholder="SS number" data-name="worker_ss_number" onclick="open_modal(this)">Yes we need your SS# to submit you</p></li>
    </ul>

    <ul class="ss-s-jb-apl-on-inf-txt-ul  {{ ($matches['profession']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
            <span>Profession</span>
            <h6>{{$model->proffesion}}</h6>
        </li>
        <li><p data-target="dropdown" data-title="What kind of professional are you?" data-filter="Profession" data-name="profession" onclick="open_modal(this)">What kind of professional are you?</p></li>
    </ul>

    <ul class="ss-s-jb-apl-on-inf-txt-ul  {{ ($matches['preferred_specialty']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
            <span>Specialty</span>
            <h6>{{str_replace(',',', ',$model->preferred_specialty)}}</h6>
        </li>
        {{-- <li><p data-bs-toggle="modal" data-bs-target="#job-dtl-checklist">What's your specialty?</p></li> --}}
        <li><p data-target="dropdown" data-title="What's your specialty?" data-filter="Speciality" data-name="specialty" onclick="open_modal(this)">What's your specialty?</p></li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['job_location']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Professional Licensure</span>
        <h6>{{$model->job_location}}</h6>
        </li>

        <li>
            <p  data-target="input" data-title="Where are you licensed?" data-placeholder="Where are you licensed?" data-name="nursing_license_state" onclick="open_modal(this)">Where are you licensed?</p>
        </li>
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

    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['number_of_references']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
            <span>References</span>
            <h6>{{$model->number_of_references}}  references </h6>
            <h6>{{$model->recency_of_reference}} months Recency</h6>
        </li>
        <li>
            <p data-bs-toggle="modal" data-bs-target="#job-dtl-References">Who are your References?</p>
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
            <p data-target="file" data-hidden_name="{{strtolower($v)}}_cer" data-hidden_value="Yes" data-href="{{route('certification')}}" data-title="No {{$v}}?" data-name="{{strtolower($v)}}" onclick="open_modal(this)">No {{$v}}?</p>
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

    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['eligible_work_in_us']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
            <span>Eligible to work in the US</span>
            <h6>Required</h6>
            {{-- <h6>Flu 2022 Preferred</h6> --}}
        </li>
        <li>
            <p data-target="binary" data-title="Does Congress allow you to work here?" data-name="worker_eligible_work_in_us" onclick="open_modal(this)">Does Congress allow you to work here?</p>
        </li>
    </ul>

    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['urgency']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Urgency</span>
        <h6>{{$model->urgency}} </h6>

        </li>
        <li>
            <p  data-target="input" data-title="How quickly you can be ready to submit?" data-placeholder="How quickly you can be ready to submit?" data-name="worker_urgency" onclick="open_modal(this)">How quickly you can be ready to submit?</p>
        </li>
    </ul>

    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['block_scheduling']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Block Scheduling</span>

       
        <h6>{{$model->block_scheduling == '1' ? 'Yes' : 'No'}} </h6>
        </li>
        <li>
            <p data-target="binary" data-title="Do you want Block Scheduling?" data-name="block_scheduling" onclick="open_modal(this)">Do you want Block Scheduling?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['float_requirement']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Float Requirements</span>
        <h6>{{$model->float_requirement == '1' ? 'Yes' : 'No'}} </h6>
       
        </li>
        <li>
            <p data-target="binary" data-title="Are you willing float to?" data-name="float_requirement" onclick="open_modal(this)">Are you willing float to?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['facility_shift_cancelation_policy']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
        <li>
        <span>Facility Shift Cancellation Policy</span>
        <h6>{{$model->facility_shift_cancelation_policy}} </h6>
        </li>
        <li>
            <p data-target="dropdown" data-title="What terms do you prefer?" data-filter="AssignmentDuration" data-name="facility_shift_cancelation_policy" onclick="open_modal(this)">What terms do you prefer?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['contract_termination_policy']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
        <li>
        <span>Contact Termination Policy</span>
        <h6>{{$model->contract_termination_policy}} </h6>
        </li>
        <li>
            <p data-target="dropdown" data-title="What terms do you prefer?" data-filter="ContractTerminationPolicy" data-name="contract_termination_policy" onclick="open_modal(this)">What terms do you prefer?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['traveler_distance_from_facility']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
        <li>
        <span>Traveller Distance From Facility</span>
        <h6>{{$model->traveler_distance_from_facility}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="Where does the IRS think you live?" data-placeholder="Where does the IRS think you live?" data-name="distance_from_your_home" onclick="open_modal(this)">Where does the IRS think you live?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul">
        <li>
        <span>Facility</span>
        <h6>{{$model->facility_id}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="What Facilities have you worked at?" data-placeholder="Write Name Of Facilities" data-name="facilities_you_like_to_work_at" onclick="open_modal(this)">What Facilities have you worked at?</p>
        </li>
    </ul>

    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['facilitys_parent_system']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }} ">
        <li>
        <span>Facility's Parent System</span>
        <h6>{{$model->facilitys_parent_system}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="What facilities would you like to work at?" data-placeholder="Write Name Of Facilities" data-name="worker_facilitys_parent_system" onclick="open_modal(this)">What facilities would you like to work at?</p>
        </li>
    </ul>
   
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['clinical_setting']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
        <li>
        <span>Clinical Setting</span>
        <h6>{{$model->clinical_setting}} </h6>
        </li>
        <li>
            <p data-target="dropdown" data-title="What setting do you prefer?" data-filter="ClinicalSetting" data-name="clinical_setting_you_prefer" onclick="open_modal(this)">What setting do you prefer?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['Patient_ratio']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
        <li>
        <span>Patient Ratio</span>
        <h6>{{$model->Patient_ratio}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="How many patients can you handle?" data-placeholder="How many patients can you handle?" data-name="worker_patient_ratio" onclick="open_modal(this)">How many patients can you handle?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['emr']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
        <li>
        <span>EMR</span>
        <h6>{{$model->Emr}} </h6>
        </li>
        <li>
            <p data-target="dropdown" data-title="What EMRs have you used?" data-filter="EMR" data-name="worker_emr" onclick="open_modal(this)">What EMRs have you used?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['Unit']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
        <li>
        <span>Unit</span>
        <h6>{{$model->Unit}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="Fav Unit?" data-placeholder="Fav Unit?" data-name="worker_unit" onclick="open_modal(this)">Fav Unit?</p>
        </li>
    </ul>
   
 
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['scrub_color']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
        <li>
        <span>Scrub Color</span>
        <h6>{{$model->scrub_color}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="Fav scrub brand?" data-placeholder="Fav scrub brand?" data-name="worker_scrub_color" onclick="open_modal(this)">Fav scrub brand?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['job_city']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
        <li>
        <span>Facility City</span>
        <h6>{{$model->job_city}} </h6>
        </li>
        <li>
            <p data-target="dropdown" data-title="Cities you'd like to work?" data-filter="City" data-name="worker_facility_city" onclick="open_modal(this)" >Cities you'd like to work?</p>

        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['job_state']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
        <li>
        <span>Facility State Code</span>
        <h6>{{$model->job_state}} </h6>
        </li>
        <li>
            <p  data-target="dropdown" data-title="States you'd like to work?" data-filter="State" data-name="worker_facility_state" onclick="open_modal(this)">States you'd like to work?</p>
        </li>
    </ul>
    
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($model->as_soon_as) ? (($matches['as_soon_as']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') : (($matches['start_date']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink') }}">
        <li>
        <span>Start date</span>
        <h6>{{ ($model->as_soon_as) ? 'As soon as possible' : $model->start_date}} </h6>
        </li>
        <li>
            @if($model->as_soon_as)
            <p data-target="binary" data-title="Can you start as soon as possible?" data-name="worker_as_soon_as" onclick="open_modal(this)">Can you start as soon as possible?</p>
            @else
            <p  data-target="date" data-title="When can you start?" data-name="worker_start_date" onclick="open_modal(this)">When can you start?</p>
            @endif
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['rto']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
        <li>
        <span>RTO</span>
        <h6>{{$model->rto}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="Any time off?" data-placeholder="Any time off?" data-name="rto" onclick="open_modal(this)">Any time off?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul">
        <li>
        <span>Shift Time Of Day</span>
        <h6>{{$model->preferred_shift}} </h6>
        </li>
        <li>
            <p data-target="dropdown" data-title="Fav shift?" data-filter="shift_time_of_day" data-name="worker_shift_time_of_day" onclick="open_modal(this)">Fav shift?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['hours_per_week']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink' }}">
        <li>
        <span>Hours/Week</span>
        <h6>{{$model->hours_per_week}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="Ideal hours per week?" data-placeholder="Enter number Of Hours/Week" data-name="worker_hours_per_week" onclick="open_modal(this)">Ideal hours per week?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['guaranteed_hours']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Guaranteed Hours</span>
        <h6>{{$model->guaranteed_hours}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="Open to jobs with no guaranteed hours?" data-placeholder="Enter Guaranteed Hours" data-name="worker_guaranteed_hours" onclick="open_modal(this)">Open to jobs with no guaranteed hours?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['hours_shift']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Hours/Shift</span>
        <h6>{{$model->hours_shift}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="Preferred hours per shift" data-placeholder="Enter number Of Hours/Shift" data-name="worker_hours_shift" onclick="open_modal(this)">Preferred hours per shift</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['preferred_assignment_duration']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Weeks/Assignment</span>
        <h6>{{$model->preferred_assignment_duration}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="How many weeks?" data-placeholder="Enter prefered weeks per assignment" data-name="worker_weeks_assignment" onclick="open_modal(this)">How many weeks?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['weeks_shift']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Shifts/Week</span>
        <h6>{{$model->weeks_shift}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="Ideal shifts per week" data-placeholder="Enter ideal shift per week" data-name="worker_shifts_week" onclick="open_modal(this)">Ideal shifts per week</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['referral_bonus']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Referral Bonus</span>
        <h6>{{$model->referral_bonus}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="# of people you have referred?" data-placeholder="# of people you have referred?" data-name="worker_referral_bonus" onclick="open_modal(this)"># of people you have referred?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['sign_on_bonus']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Sign-On Bonus</span>
        <h6>${{$model->sign_on_bonus}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="What kind of bonus do you expect?" data-placeholder="What kind of bonus do you expect?" data-name="worker_sign_on_bonus" onclick="open_modal(this)">What kind of bonus do you expect?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['completion_bonus']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Completion Bonus</span>
        <h6>${{$model->completion_bonus}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="What kind of bonus do you deserve?" data-placeholder="What kind of bonus do you deserve?" data-name="worker_completion_bonus" onclick="open_modal(this)">What kind of bonus do you deserve?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['extension_bonus']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Extension Bonus</span>
        <h6>${{$model->extension_bonus}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="What are you comparing this to?" data-placeholder="What are you comparing this to?" data-name="worker_extension_bonus" onclick="open_modal(this)">What are you comparing this to?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['other_bonus']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Other Bonus</span>
        <h6>${{$model->other_bonus}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="Other bonuses you want?" data-placeholder="Other bonuses you want?" data-name="worker_other_bonus" onclick="open_modal(this)">Other bonuses you want?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['four_zero_one_k']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>401K</span>
        
        <h6>{{$model->four_zero_one_k == '1' ? 'Yes' : 'No'}} </h6>
        
        </li>
        <li>
            <p data-target="binary" data-placeholder="How much do you want this?" data-title="How much do you want this?"  data-name="worker_four_zero_one_k" onclick="open_modal(this)">How much do you want this?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['health_insaurance']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Health Insurance</span>
        
        <h6> {{$model->health_insaurance == '1' ? 'Yes' : 'No'}} </h6>
        </li>
        <li>
            <p data-target="binary" data-title="How much do you want this?" data-name="worker_health_insurance" data-placeholder="How much do you want this?" onclick="open_modal(this)">How much do you want this?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['dental']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Dental</span>
        <h6> {{$model->dental == '1' ? 'Yes' : 'No'}} </h6>
        </li>
        <li> 
            <p data-target="binary" data-title="How much do you want this?" data-placeholder="" data-name="worker_dental" onclick="open_modal(this)">How much do you want this?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['vision']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Vision</span>
        <h6> {{$model->vision == '1' ? 'Yes' : 'No'}} </h6>
        </li>
        <li>
             
            <p data-target="binary" data-title="How much do you want this?" data-placeholder="How much do you want this?" data-name="worker_vision" onclick="open_modal(this)">How much do you want this?</p>
          
        </li>
    </ul>

    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['actual_hourly_rate']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Actual Hourly Rate</span>
        <h6>${{$model->actual_hourly_rate}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="What rate is fair?" data-placeholder="What rate is fair?" data-name="worker_actual_hourly_rate" onclick="open_modal(this)">What rate is fair?</p>
            
        </li>
    </ul>
    
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['feels_like_per_hour']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Feels Like $/Hr</span>
        <h6>${{$model->feels_like_per_hour}} </h6>
        
        </li>
        <li>
            <p data-target="binary" data-title="Does this seem fair based on the market?" data-placeholder="Does this seem fair based on the market?" data-name="worker_feels_like_per_hour_check" onclick="open_modal(this)">Does this seem fair based on the market?</p>
            
        </li>
    </ul>

    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['overtime']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Overtime</span>
        <h6>{{$model->overtime}} </h6>
        </li>
        <li>
            
            <p data-target="binary" data-title="Would you work more overtime for higher OT rate?" data-name="worker_overtime_check" onclick="open_modal(this)">Would you work more overtime for higher OT rate?</p> 

        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['holiday']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Holiday</span>
        <h6>{{$model->holiday}} </h6>
        </li>
        <li>
            <p data-target="date" data-title="Any holiday you refuse to work?" data-name="worker_holiday_check" onclick="open_modal(this)">Any holiday you refuse to work?</p>
            
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['on_call']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>On call</span>
        <h6>{{$model->on_call}} </h6>
        </li>
        <li>
            <p data-target="binary" data-title="Will you do call?" data-name="worker_on_call_check" onclick="open_modal(this)">Will you do call?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['call_back']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Call Back</span>
        <h6>{{$model->call_back}} </h6>
        </li>
        <li>
            <p data-target="binary" data-title="Is this rate reasonable?" data-name="worker_call_back_check" onclick="open_modal(this)">Is this rate reasonable?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['orientation_rate']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Orientation Rate</span>
        <h6>{{$model->orientation_rate}} </h6>
        </li>
        <li>
            <p data-target="binary" data-title="Is this rate reasonable?" data-placeholder="-" data-name="worker_orientation_rate_check" onclick="open_modal(this)">Is this rate reasonable?</p>
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
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['employer_weekly_amount']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Employer Weekly Amount</span>
        <h6>${{$model->employer_weekly_amount}} </h6>
        </li>
        <li>
            <p data-target="input" data-title="What range is reasonable?" data-placeholder="What range is reasonable?" data-name="worker_employer_weekly_amount" onclick="open_modal(this)">What range is reasonable?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul {{ ($matches['weekly_non_taxable_amount']['match']) ? 'ss-s-jb-apl-bg-blue' : 'ss-s-jb-apl-bg-pink'}}">
        <li>
        <span>Weekly Non-Taxable Amount</span>
        <h6>${{$model->weekly_non_taxable_amount}} </h6>
        </li>
        <li>
            <p data-target="binary" data-title="Are you going to duplicate expenses?" data-placeholder="Weekly non-taxable amount" data-name="worker_weekly_non_taxable_amount_check" onclick="open_modal(this)">Are you going to duplicate expenses?</p>
        </li>
    </ul>
    <ul class="ss-s-jb-apl-on-inf-txt-ul">
        <li>
        <span>Goodwork Weekly Amount</span>
        <h6>${{$model->weekly_taxable_amount}} </h6>
        </li>
        <li>
            <h6> You have 5 days left before your rate drops form 5% to 2%</h6>
            {{-- <p data-target="input" data-title="You have 5 days left before your rate drops form 5% to 2%" data-placeholder="Goodwork Weekly Amount" data-name="worker_goodwork_weekly_amount" onclick="open_modal(this)">You have 5 days left before your rate drops form 5% to 2% </p> --}}
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
    @if(!$model->checkIfApplied())
    <button data-id="{{$model->id}}" onclick="apply_on_jobs(this)">Apply Now</button>
    @endif
  </div>

  </div>

  </div>

  </div>
  </div>
      </div>




<!----------------job-detls popup form----------->

<!-----------Did you really graduate?------------>
<!-- Modal -->

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="file_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="ss-pop-cls-vbtn">
        <button type="button" class="btn-close" data-target="#file_modal" onclick="close_modal(this)" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
            <form method="post" action="{{route('worker-upload-files')}}" id="file_modal_form" class="modal-form" enctype="multipart/form-data">
                @csrf
                <div class="ss-job-dtl-pop-frm-sml-dv"></div>
                <h4></h4>
                <div class="ss-form-group fileUploadInput">
                    <input type="file" name="">
                    <input type="hidden" name="" value="">
                    <button type="button" onclick="open_file(this)">Choose File</button>
                </div>
             <button class="ss-job-dtl-pop-sv-btn">Save</button>
            </form>
        </div>
      </div>

    </div>
  </div>
</div>



<!-----------Are you sure you never worked here as staff?------------>
<!-- Modal -->

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="binary_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="ss-pop-cls-vbtn">
        <button type="button" class="btn-close"  data-target="#binary_modal" onclick="close_modal(this)" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="ss-job-dtl-pop-form">
            <form method="post" action="{{route('my-profile.store')}}" id="binary_modal_form" class="modal-form">
                @csrf
                <div class="ss-job-dtl-pop-frm-sml-dv"><div></div></div>
                <h4></h4>
                <ul class="ss-jb-dtlpop-chck">
                    <li>
                        <label>
                            <input type="radio" name="radio" name="" value="1">
                            <span>Yes</span>
                        </label>
                    </li>

                    <li>
                        <label>
                            <input type="radio" name="radio" name="" value="0">
                            <span>No</span>
                        </label>
                    </li>
                </ul>
             <button class="ss-job-dtl-pop-sv-btn">Save</button>
            </form>
        </div>
      </div>

    </div>
  </div>
</div>


<!-----------Yes we need your SS# to submit you------------>
<!-- Modal -->

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="input_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="ss-pop-cls-vbtn">
        <button type="button" class="btn-close" data-target="#input_modal" onclick="close_modal(this)" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="ss-job-dtl-pop-form">
            <form method="post" action="{{route('my-profile.store')}}" id="input_modal_form" class="modal-form">
                @csrf
                <div class="ss-job-dtl-pop-frm-sml-dv"><div></div></div>
                <h4></h4>
                <div class="ss-form-group">
                    <input type="text" name="" placeholder="">
                    <span class="help-block"></span>
                </div>
                <button type="submit" class="ss-job-dtl-pop-sv-btn">Save</button>
            </form>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- date modal --}}

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="date_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="ss-pop-cls-vbtn">
          <button type="button" class="btn-close" data-target="#date_modal" onclick="close_modal(this)" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="ss-job-dtl-pop-form">
              <form method="post" action="{{route('my-profile.store')}}" id="date_modal_form" class="modal-form">
                  @csrf
                  <div class="ss-job-dtl-pop-frm-sml-dv"><div></div></div>
                  <h4></h4>
                  <div class="ss-form-group">
                      <input type="date" name="" placeholder="">
                      <span class="help-block
                        "></span>
                    </div>
                    <button type="submit" class="ss-job-dtl-pop-sv-btn">Save</button>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>



<!-----------What's your specialty?------------>
<!-- Modal -->

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="job-dtl-specialty" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="ss-pop-cls-vbtn">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="ss-job-dtl-pop-form">
            <form>
                @csrf
                <div class="ss-job-dtl-pop-frm-sml-dv"><div></div></div>
                <h4>Yes we need your SS# to submit you</h4>
                <div class="ss-form-group">
                    <select name="cars"></select>
                </div>
                <div class="ss-jb-dtl-pop-check"><input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
<label for="vehicle1"> This is a compact license</label></div>
             <button class="ss-job-dtl-pop-sv-btn">Save</button>
            </form>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- Dropdown modal --}}
<div class="modal fade ss-jb-dtl-pops-mn-dv" id="dropdown_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="ss-pop-cls-vbtn">
          <button type="button" class="btn-close" data-target="#dropdown_modal" onclick="close_modal(this)" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="ss-job-dtl-pop-form">
                <form method="post"  action="{{route('my-profile.store')}}" id="dropdown_modal_form" class="modal-form">
                    @csrf
                    <h4></h4>
                    <div class="ss-form-group">
                        <select name=""></select>
                    </div>
                    {{-- <div class="ss-jb-dtl-pop-check"><input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                        <label for="vehicle1"> This is a compact license</label>
                    </div> --}}
                    <button class="ss-job-dtl-pop-sv-btn">Save</button>
                </form>
            </div>
        </div>

      </div>
    </div>
</div>



<!-----------What's your specialty?------------>
<!-- Modal -->

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="job-dtl-References" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="ss-pop-cls-vbtn">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="references-modal-form-btn"></button>
      </div>
      <div class="modal-body">
        <div class="ss-job-dtl-pop-form ss-job-dtl-pop-form-refrnc">
            <form method="post" action="{{route('references.store')}}" id="ref-modal-form" enctype="multipart/form-data">
                @csrf
                <div class="ss-job-dtl-pop-frm-sml-dv"><div></div></div>
                <h4>Who are your References?</h4>
                <div class="ss-form-group">
                    <label>Reference Name</label>
                    <input type="text" name="name[]" placeholder="Name of Reference">
                    <span class="help-block"></span>
                </div>
                <div class="ss-form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone[]" placeholder="Phone Number of Reference">
                    <span class="help-block"></span>
                </div>

                <div class="ss-form-group">
                    <label>Email</label>
                    <input type="text" name="email[]" placeholder="Email of Reference">
                    <span class="help-block"></span>
                </div>

                <div class="ss-form-group">
                    <label>Date Referred</label>
                    <input type="date" name="date_referred[]">
                    <span class="help-block"></span>
                </div>

                <div class="ss-form-group">
                    <label>Min Title of Reference</label>
                    <input type="text" name="min_title_of_reference[]" placeholder="Min Title of Reference">
                    <span class="help-block"></span>
                </div>
                <div class="ss-form-group">
                    <label>Is this from your last assignment?</label>
                    <select name="recency_of_reference[]">
                        <option value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    <span class="help-block"></span>
                </div>

                <div class="ss-form-group fileUploadInput">
                    <label>Upload Image</label>
                    <input type="file" name="image[]">
                    <button type="button" onclick="open_file(this)">Choose File</button>
                    <span class="help-block"></span>
                </div>

                <button class="ss-job-dtl-pop-sv-btn">Save</button>
            </form>
        </div>
      </div>

    </div>
  </div>
</div>


<!-----------Upload your latest skills checklist------------>
<!-- Modal -->

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="job-dtl-checklist" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="ss-pop-cls-vbtn">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="ss-job-dtl-pop-form ss-job-dtl-pop-form-refrnc">
            <form>
                @csrf
                <div class="ss-job-dtl-pop-frm-sml-dv"><div></div></div>
                <h4>Upload your latest skills checklist</h4>
                <div class="ss-form-group">
                    <label>Skills Name</label>
                    <input type="text" name="Name of Reference" placeholder="Phone Number of Reference">
                </div>


                <div class="ss-form-group">
                    <label>Completion Date</label>
                    <input type="date" id="birthday" name="birthday">
                </div>


                <div class="ss-form-group fileUploadInput">
                    <label>Completion Date</label>
              <input type="file">
              <button>Choose File</button>
            </div>

           <div class="ss-add-more-se"><a href="#">Add More</a></div>
             <button class="ss-job-dtl-pop-sv-btn">Save</button>
            </form>
        </div>
      </div>

    </div>
  </div>
</div>




<!-----------Upload your latest skills checklist------------>
<!-- Modal -->

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="job-dtl-pop-cale" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="ss-pop-cls-vbtn">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="ss-job-dtl-pop-form ss-job-dtl-pop-form-refrnc">
            <form>
                @csrf
                <div class="ss-job-dtl-pop-frm-sml-dv"><div></div></div>
                <h4>Upload your latest skills checklist</h4>

                <div class="container-calendar">
        <h3 id="monthAndYear"></h3>

        <div class="button-container-calendar">
            <button id="previous" onclick="previous()">&#8249;</button>
            <button id="next" onclick="next()">&#8250;</button>
        </div>

        <table class="table-calendar" id="calendar" data-lang="en">
            <thead id="thead-month"></thead>
            <tbody id="calendar-body"></tbody>
        </table>

        <div class="footer-container-calendar">
             <label for="month">Jump To: </label>
             <select id="month" onchange="jump()">
                 <option value=0>Jan</option>
                 <option value=1>Feb</option>
                 <option value=2>Mar</option>
                 <option value=3>Apr</option>
                 <option value=4>May</option>
                 <option value=5>Jun</option>
                 <option value=6>Jul</option>
                 <option value=7>Aug</option>
                 <option value=8>Sep</option>
                 <option value=9>Oct</option>
                 <option value=10>Nov</option>
                 <option value=11>Dec</option>
             </select>
             <select id="year" onchange="jump()"></select>
        </div>

    </div>



            <p>Add More</p>
             <button class="ss-job-dtl-pop-sv-btn">Save</button>
            </form>
        </div>
      </div>

    </div>
  </div>
</div>



    </div>

</main>




@stop

@section('js')
<script>


    $(document).ready(function(){
        let matches = @json($matches);
        console.log((matches));

        let usematches = @json($userMatches);
        console.log((usematches));
        $('input[name="phone[]"]').mask('(999) 999-9999');
    });
    function open_file(obj){
        $(obj).parent().find('input[type="file"]').click();
    }

    function open_modal(obj){
        let name, title, modal, form, target;

        name = $(obj).data('name');
        title = $(obj).data('title');
        target = $(obj).data('target');

        modal = '#'+target+'_modal';
        form = modal+'_form';
        $(form).find('h4').html(title);
        switch(target)
        {
            case 'file':
                $(form).find('input[type="file"]').attr('name',name);
                $(form).find('input[type="hidden"]').attr('name',$(obj).data('hidden_name'));
                $(form).find('input[type="hidden"]').val($(obj).data('hidden_value'));
                $(form).attr('action', $(obj).data('href'));
                $(form).append('<input type="hidden" name="_token" value="{{ csrf_token() }}">');
                break;
            case 'input':
                $(form).find('input[type="text"]').attr('name',name);
                $(form).find('input[type="text"]').attr('placeholder',$(obj).data('placeholder'));
                break;
            case 'binary':
                $(form).find('input[type="radio"]').attr('name',name);
                break;
            case 'dropdown':
                $(form).find('select').attr('name',name);
                get_dropdown(obj);
                break;
            case 'date':
                $(form).find('input[type="date"]').attr('name',name);
                break;
            default:
                break;
        }

        $(modal).modal('show');
    }

    function close_modal(obj){
        let target = $(obj).data('target');
        $(target).modal('hide');
    }
    
</script>
@stop
