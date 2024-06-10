@extends('layouts.profile')
@section('mytitle', 'My Profile')

@section('form')
<form action="{{route('my-profile.store')}}" class="redirect-after-submit">
    <div class="ss-persnl-frm-hed">
        <p><span><img src="{{URL::asset('frontend/img/my-per--con-user.png')}}" /></span>Professional Information</p>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    <div class="ss-form-live-location">
        <div class="ss-form-group">
            <label>Patient Ratio</label>
            <input type="text" value="{{$model->worker_patient_ratio}}" name="worker_patient_ratio" placeholder="How many patients can you handle?">
        </div>
    </div>

    <div class="ss-form-group">
        <label>EMR </label>
        <select name="worker_emr" >
            <option value="">What EMRs have you used?</option>
            @foreach($emrs as $v)
                <option value="{{$v->title}}"  {{ ($model->worker_emr == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
            @endforeach
        </select>
    </div>



    <div class="ss-form-live-location">
        <div class="ss-form-group">
            <label>Unit</label>
            <input type="text" value="{{$model->worker_unit}}" name="worker_unit"placeholder="Fav Unit?">
        </div>
    </div>

    <div class="ss-form-group">
        <label>Department</label>
        <input type="text" value="{{$model->worker_department}}" name="worker_department" placeholder="Fav Department?">
    </div>

    <div class="ss-form-group">
        <label>Bed Size</label>
        <input type="text" value="{{$model->worker_bed_size}}" name="worker_bed_size" placeholder="king or california king ?">
    </div>


    <div class="ss-form-group">
        <label>Trauma Level</label>
        <input type="text" value="{{$model->worker_trauma_level}}" name="worker_trauma_level" placeholder="Ideal trauma level?">
    </div>

    <div class="ss-form-group">
        <label>Scrub Color</label>
        <input type="text" value="{{$model->worker_scrub_color}}" name="worker_scrub_color" placeholder="Fav Scrub Brand?">
    </div>



    <div class="ss-form-group">
        <label>Facility City</label>
        <select name="worker_facility_city" >
            <option value="">cities you'd like to work?</option>
            @foreach($us_cities as $v)
                <option value="{{$v->name}}" {{ ($model->worker_facility_city == $v->name) ? 'selected' :'' }}>{{$v->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="ss-form-group">
        <label>Facility State Code</label>
        <select name="worker_facility_state_code" >
            <option value="">States you'd like to work?</option>
            @foreach($us_states as $v)
                <option value="{{$v->iso2}}" {{ ($model->worker_facility_state_code == $v->iso2) ? 'selected' :'' }}>{{$v->iso2}}</option>
            @endforeach
        </select>
    </div>


    <div class="ss-prsn-form-btn-sec">
        <button type="button" class="ss-prsnl-skip-btn" id="skip-button" data-href="{{route('interview-dates')}}" onclick="redirect_to(this)"> Skip </button>
        <button type="submit" class="ss-prsnl-save-btn"> Save & Next </button>
    </div>

</form>
@stop

@push('form-js')
<script>

</script>

@endpush
