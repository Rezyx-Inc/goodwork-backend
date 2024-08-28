@extends('layouts.profile')
@section('mytitle', 'My Profile')

@section('form')
<form action="{{route('my-profile.store')}}" class="redirect-after-submit">
    <div class="ss-persnl-frm-hed">
        <p><span><img src="{{URL::asset('frontend/img/my-per--con-user.png')}}" /></span>Professional Information</p>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    <div class="ss-form-live-location">
        <div class="ss-form-group">
            <label>interview Date</label>
            <input type="text"  value="{{$model->worker_interview_dates}}"  name="worker_interview_dates">
        </div>
    </div>

    <div class="ss-form-live-location">
        <div class="ss-form-group ss-date-check-div">
            <ul>
                <li> <label>Start Date</label></li>
                <li><input type="checkbox" id="vehicle1" value="1" name="worker_as_soon_as_posible" {{ ($model->worker_as_soon_as_posible == '1') ? 'checked': '' }}>
                <label for="vehicle1"> As Soon As Possible</label></li>
            </ul>
            <input type="date" name="worker_start_date" value="{{$model->worker_start_date}}">
        </div>
    </div>

    {{-- <div class="ss-form-live-location">
        <div class="ss-form-group">
            <label>RTO</label>
        <input id="appt-time" type="time" name="appt-time" value="13:30" placeholder="Any time off?" />
        </div>
    </div> --}}

    <div class="ss-form-group">
        <label>Shift Time of Day </label>
        <select name="worker_shift_time_of_day"  >
            <option value="">Select</option>
            @foreach($shift_tile_of_day as $v)
                <option value="{{$v->title}}"  {{ ($model->worker_shift_time_of_day == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
            @endforeach
        </select>
    </div>

    <div class="ss-form-live-location">
        <div class="ss-form-group">
            <label>Hours/Week</label>
            <input type="text" value="{{$model->worker_hours_per_week}}"  name="worker_hours_per_week" placeholder="Ideal Hours per week?">
        </div>
    </div>

    <div class="ss-form-live-location">
        <div class="ss-form-group">
            <label>Guaranteed Hours</label>
            <input type="text" value="{{$model->worker_guaranteed_hours}}"  name="worker_guaranteed_hours" placeholder="Open to jobs with no guaranteed hours?">
        </div>
    </div>

    <div class="ss-form-group">
        <label>Hours/Shift</label>
        <input type="text" value="{{$model->worker_hours_shift}}"  name="worker_hours_shift" placeholder="How many weeks?">
    </div>

    <div class="ss-form-group">
        <label>Weeks/Assignment</label>
        <input type="text" value="{{$model->worker_weeks_assignment}}"  name="worker_weeks_assignment" placeholder="How many weeks?">
    </div>


    <div class="ss-form-group">
        <label>Shifts/Week</label>
        <input type="text" value="{{$model->worker_shifts_week}}"  name="worker_shifts_week" placeholder="Ideal shifts per week">
    </div>


    <div class="ss-form-group">
        <label>Referral Bonus</label>
        <input type="text" value="{{$model->worker_referral_bonus}}"  name="worker_referral_bonus" placeholder="# of people you have referred">
    </div>



    <div class="ss-prsn-form-btn-sec">
        <button type="button" class="ss-prsnl-skip-btn" id="skip-button" data-href="{{route('bonuses')}}" onclick="redirect_to(this)"> Skip </button>
        <button type="submit" class="ss-prsnl-save-btn"> Save & Next </button>
    </div>

</form>
@stop

@push('form-js')
<script>

</script>

@endpush
