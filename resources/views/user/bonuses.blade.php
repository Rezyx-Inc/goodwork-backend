@extends('layouts.profile')
@section('mytitle', 'My Profile')

@section('form')
<form action="{{route('my-profile.store')}}" class="redirect-after-submit">
    <div class="ss-persnl-frm-hed">
        <p><span><img src="{{URL::asset('frontend/img/my-per--con-user.png')}}" /></span>Professional Information</p>
        <div class="progress">
        <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    <div class="ss-form-live-location">
        <div class="ss-form-group">
        <label>Sign-On Bonus</label>
        <input type="text" name="worker_sign_on_bonus" value="{{$model->worker_sign_on_bonus}}" placeholder="What kind of bonus do you expect?">
        </div>
    </div>


    <div class="ss-form-live-location">
        <div class="ss-form-group">
        <label>Completion Bonus</label>
        <input type="text" name="worker_completion_bonus" value="{{$model->worker_completion_bonus}}" placeholder="What kind of bonus do you deserve?">
        </div>
    </div>

    <div class="ss-form-live-location">
        <div class="ss-form-group">
        <label>Extension Bonus</label>
        <input type="text" name="worker_extension_bonus" value="{{$model->worker_extension_bonus}}" placeholder="What are you comparing this too?">
        </div>
    </div>

    <div class="ss-form-live-location">
        <div class="ss-form-group">
        <label>Other Bonus</label>
        <input type="text" name="worker_other_bonus" value="{{$model->worker_other_bonus}}" placeholder="Other bonuses you want?">
        </div>
    </div>



    <div class="ss-form-group">
        <label>401K </label>
        <select name="how_much_k">
            <option value="">Select</option>
            @foreach($four_zero_1k as $v)
                <option value="{{$v->title}}"  {{ ($model->how_much_k == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
            @endforeach
        </select>
    </div>

    <div class="ss-form-group">
        <label>Health Insurance </label>
        <select name="worker_health_insurance">
            <option value="">Select</option>
            @foreach($insurances as $v)
                <option value="{{$v->title}}"  {{ ($model->worker_health_insurance == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
            @endforeach
        </select>
    </div>

    <div class="ss-form-group">
        <label>Dental </label>
        <select name="worker_dental">
            <option value="">Select</option>
            @foreach($dentals as $v)
                <option value="{{$v->title}}"  {{ ($model->worker_dental == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
            @endforeach
        </select>
    </div>


    <div class="ss-form-group">
        <label>Vision </label>
        <select name="worker_vision">
            <option value="">Select</option>
            @foreach($visions as $v)
                <option value="{{$v->title}}"  {{ ($model->worker_vision == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
            @endforeach
        </select>
    </div>

    <div class="ss-form-live-location">
        <div class="ss-form-group">
        <label>Actual Hourly rate</label>
        <input type="text" name="worker_actual_hourly_rate" value="{{$model->worker_actual_hourly_rate}}" placeholder="What range is fair?">
        </div>
    </div>

    <div class="ss-prsn-form-btn-sec">
        <button type="button" class="ss-prsnl-skip-btn" id="skip-button" data-href="{{route('work-hours')}}" onclick="redirect_to(this)"> Skip </button>
        <button type="submit" class="ss-prsnl-save-btn"> Save & Next </button>
    </div>

</form>
@stop

@push('form-js')
<script>

</script>

@endpush
