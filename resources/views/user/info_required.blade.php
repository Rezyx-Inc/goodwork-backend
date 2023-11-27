@extends('layouts.profile')
@section('mytitle', 'My Profile')

@section('form')
<form method="post" action="{{route('info-required')}}" class="redirect-after-submit">
    <div class="ss-persnl-frm-hed">
      <p><span><img src="{{URL::asset('frontend/img/my-per--con-user.png')}}" /></span>Professional Information</p>
      <div class="progress">
        <div class="progress-bar" role="progressbar" style="width: 30%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

     <div class="ss-form-group">
       <label>Diploma</label>
       <select  name="diploma_cer" data-type="diploma-container" onchange="hide_show_select(this)">
             <option value="">Did you really graduate?</option>
             <option value="Yes" {{ (!empty($diplomaCer)) ? 'selected': ''}}>Yes</option>
            <option value="No" {{ (empty($diplomaCer)) ? 'selected': ''}}>No</option>

       </select>
     </div>

     <div class="ss-file-choose-sec diploma-container {{ (empty($diplomaCer)) ? 'd-none': ''}}">
        <div class="ss-form-group fileUploadInput">
            <label>Upload File</label>
            <input type="file" name="diploma"/>
            <button>Choose File</button>
        </div>
    </div>

     <div class="ss-form-group">
       <label>drivers license</label>
       <select name="dl_cer" data-type="dl-container" onchange="hide_show_select(this)">
         <option value="">Are you really allowed to drive?</option>
         <option value="Yes" {{ (!empty($driving_license)) ? 'selected': ''}}>Yes</option>
         <option value="No" {{ (empty($driving_license)) ? 'selected': ''}}>No</option>
       </select>
     </div>
    <div class="ss-file-choose-sec dl-container {{ (empty($driving_license)) ? 'd-none': ''}}">
        <div class="ss-form-group fileUploadInput">
            <label>Upload File</label>
            <input type="file" name="driving_license"/>
            <button>Choose File</button>
        </div>
    </div>

   <div class="ss-form-group">
     <label>Recent Experience</label>
     <input type="text"  name="recent_experience" value="{{$model->recent_experience}}" placeholder="Where did you work recently">
   </div>

    <div class="ss-form-group">
       <label>Worked at Facility Before</label>
       <select name="worked_at_facility_before" class="form-control" >
            <option value="">Have been on staff here before?</option>
            <option value="Yes" {{ ($model->worked_at_facility_before == 'Yes') ? 'selected': ''}}>Yes</option>
            <option value="No" {{ ($model->worked_at_facility_before == 'No') ? 'selected': ''}}>No</option>
        </select>
    </div>

    <div class="ss-form-group">
        <label>SS# or SS Card</label>
        <input type="text"  name="worker_ss_number" value="{{$model->worker_ss_number}}" placeholder="Yes we need your SS# to submit you">
    </div>

    <div class="ss-form-group">
       <label>Eligible to work in the US</label>
       <select name="eligible_work_in_us" class="form-control" >
         <option value="">Does Congress allow you to work here?</option>
         <option value="yes" {{ ($model->eligible_work_in_us == 'yes') ? 'selected': ''}}>Yes</option>
         <option value="no" {{ ($model->eligible_work_in_us == 'no') ? 'selected': ''}}>No</option>
       </select>
    </div>


    <div class="ss-prsnl-skill-chk-sec skills-content">
       <h6>Skills Checklist</h6>
       @forelse($skillsCer as $s)
        <input type="hidden" value="{{$s->id}}" name="old_skills_ids[]">
        <div class="ss-form-group">
            <label>Completion Date</label>
            <input type="date"  name="old_completion_date[]" value="{{$s->using_date}}">
        </div>

        <div class="ss-file-choose-sec">
            <div class="ss-form-group fileUploadInput">
                <label>Upload File</label>
                <input type="file"  name="old_skill[]"/>
                <button>Choose File</button>
            </div>
        </div>
        @empty

        <div class="ss-form-group">
            <label>Completion Date</label>
            <input type="date"  name="completion_date[]">
        </div>

        <div class="ss-file-choose-sec">
            <div class="ss-form-group fileUploadInput">
                <label>Upload File</label>
                <input type="file"  name="skill[]"/>
                <button>Choose File</button>
            </div>
        </div>
        @endforelse
    </div>

     <div class="ss-add-more-sec">
       <a href="javascript:void(0);" onclick="add_more_skills(this)">Add More</a>
     </div>

    <div class="ss-prsn-form-btn-sec">
       <button type="button" class="ss-prsnl-skip-btn" id="skip-button" data-href="{{route('urgency')}}" onclick="redirect_to(this)"> Skip </button>
       <button type="submit" class="ss-prsnl-save-btn"> Save & Next </button>
    </div>

</form>
@stop

@push('form-js')
<script>
    function add_more_skills(obj)
    {
        let skill_parent = $('.skills-content')
        let str = '';
        str += '<div class="ss-form-group">';
        str += '<label>Completion Date</label>';
        str += '<input type="date"  name="completion_date[]">';
        str += '</div>';
        str += '<div class="ss-file-choose-sec">';
        str += '<div class="ss-form-group fileUploadInput">';
        str += '<label>Upload File</label>';
        str += '<input type="file"  name="skill[]"/>';
        str += '<button>Choose File</button>';
        str += '</div>';
        str += '</div>';
        skill_parent.append(str);
    }
    </script>

@endpush
