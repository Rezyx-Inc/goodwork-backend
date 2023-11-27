@extends('layouts.profile')
@section('mytitle', 'My Profile')

@section('form')
<form action="{{route('my-profile')}}" class="redirect-after-submit">
    <div class="ss-persnl-frm-hed">
      <p><span><img src="{{URL::asset('frontend/img/my-per--con-user.png')}}" /></span>Professional Information</p>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 35%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    <div class="ss-form-group">
        <label>Urgency</label>
        <input type="text"  value="{{$model->worker_urgency}}" name="worker_urgency" placeholder="How quickly can you be ready to submit?">
    </div>

    <div class="ss-form-group">
     <label># of Positions Available</label>
     <input type="text" value="{{$model->available_position}}" name="available_position" placeholder="You have applied to # jobs?">
   </div>

    <div class="ss-form-group">
       <label>You have applied to # jobs?</label>
       <select name="MSP"  class="form-control">
            <option value="">Any MSPs you prefer to avoid?</option>
            @foreach($msps as $v)
                <option value="{{$v->title}}" data-id="{{$v->id}}" {{ ($model->MSP == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
            @endforeach
        </select>
    </div>

    <div class="ss-form-group">
       <label>VMS</label>
       <select name="VMS"  class="form-control">
            <option value="">Who's your favorite VMS?</option>
            @foreach($vmss as $v)
                <option value="{{$v->title}}" data-id="{{$v->id}}" {{ ($model->VMS == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
            @endforeach
        </select>
    </div>

    <div class="ss-form-group">
     <label># of Submissions in VMS</label>
     <input type="text" value="{{$model->submission_VMS}}" name="submission_VMS" placeholder="">
   </div>

   <div class="ss-form-group">
       <label>Block scheduling</label>
       <select name="block_scheduling"  class="form-control">
            <option value="">Do you want block scheduling?</option>
            <option value="yes" {{ ($model->block_scheduling == 'Yes') ? 'selected': ''}}>Yes</option>
            <option value="no" {{ ($model->block_scheduling == 'No') ? 'selected': ''}}>No</option>
        </select>
     </div>


     <div class="ss-prsn-form-btn-sec">
        <button type="button" class="ss-prsnl-skip-btn" id="skip-button" data-href="{{route('float-requirement')}}" onclick="redirect_to(this)"> Skip </button>
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
