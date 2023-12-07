@extends('layouts.profile')
@section('mytitle', 'My Profile')

@section('form')
<form action="{{route('my-profile.store')}}" class="redirect-after-submit">
    <div class="ss-persnl-frm-hed">
        <p><span><img src="{{URL::asset('frontend/img/my-per--con-user.png')}}" /></span>Professional Information</p>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 45%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>


    <div class="ss-form-group">
       <label>Float Requirement </label>
       <select name="float_requirement">
            <option value="">Are you willing float to?</option>
            <option value="yes" {{ ($model->float_requirement == 'yes') ? 'selected': ''}}>Yes</option>
            <option value="no" {{ ($model->float_requirement == 'no') ? 'selected': ''}}>No</option>
        </select>
    </div>

    <div class="ss-form-group">
       <label>Facility Shift Cancellation Policy</label>
       <select name="facility_shift_cancelation_policy" >
            <option value="">What terms do you prefer?</option>
            @foreach($assignment_durations as $v)
                <option value="{{$v->title}}"  {{ ($model->facility_shift_cancelation_policy == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
            @endforeach
        </select>
    </div>

    <div class="ss-form-group">
        <label>Contract Termination Policy</label>
        <select name="contract_termination_policy" >
            <option value="">What terms do you prefer?</option>
            @foreach($contract_policies as $v)
                <option value="{{$v->title}}" {{ ($model->contract_termination_policy == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
            @endforeach
        </select>
    </div>

    <div class="ss-form-live-location">
        <div class="ss-form-group">
            <label>Traveler Distance From Facility</label>
            <input type="text"  value="{{$model->distance_from_your_home}}" name="distance_from_your_home" placeholder="Where does the IRS think you live?">
        </div>
    </div>

    <div class="ss-form-group">
        <label>Facility</label>
        <select name="facilities_you_like_to_work_at"  class="form-control">
            <option value="">What facilities have you worked at?</option>
            @foreach($facilities as $v)
                <option value="{{$v->id}}" {{ ($model->facilities_you_like_to_work_at == $v->id) ? 'selected' :'' }}>{{$v->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="ss-form-group">
        <label>Facility's Parent System</label>
        <input type="text"  value="{{$model->worker_facility_parent_system}}" name="worker_facility_parent_system" placeholder="What facilities would you like to work at?">
    </div>


    <div class="ss-form-group">
        <label>Facility Average Rating</label>
        <input type="text" value="{{$model->avg_rating_by_facilities}}" class="form-control" name="avg_rating_by_facilities" placeholder="Your average rating by your facilities">
    </div>

    <div class="ss-form-group">
        <label>Recruiter Average Rating</label>
        <input type="text" value="{{$model->worker_avg_rating_by_recruiters}}" class="form-control" name="worker_avg_rating_by_recruiters" placeholder="Your average rating by your recruiters">
    </div>

    <div class="ss-form-group">
        <label>Employer Average Rating</label>
        <input type="text" value="{{$model->worker_avg_rating_by_employers}}" class="form-control" name="worker_avg_rating_by_employers" placeholder="Your average rating by your employers">
    </div>

    <div class="ss-form-group">
       <label>Clinical Setting</label>
       <select name="clinical_setting_you_prefer"  class="form-control">
            <option value="">What setting do you prefer?</option>
            @foreach($types as $v)
                <option value="{{$v->title}}" {{ ($model->clinical_setting_you_prefer == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
            @endforeach
        </select>
    </div>


    <div class="ss-prsn-form-btn-sec">
        <button type="button" class="ss-prsnl-skip-btn" id="skip-button" data-href="{{route('patient-ratio')}}" onclick="redirect_to(this)"> Skip </button>
        <button type="submit" class="ss-prsnl-save-btn"> Save & Next </button>
    </div>

      <div>
    </div>
</form>
@stop

@push('form-js')
<script>

</script>

@endpush
