@extends('layouts.profile')
@section('mytitle', 'My Profile')

@section('form')
<form method="post" action="{{route('certification.store')}}" class="no-redirect-form">
    <div class="ss-persnl-frm-hed">
        <p><span><img src="{{URL::asset('frontend/img/my-per--con-key.png')}}" /></span>Certifications</p>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
   </div>


    <div class="ss-form-group">
        <label>BLS </label>
        <select  name="bls_cer" data-type="bls-container" onchange="hide_show_select(this)">
            <option value="">Select</option>
            <option value="Yes" {{ (!empty($blsCer)) ? 'selected': ''}}>Yes</option>
            <option value="No" {{ (empty($blsCer)) ? 'selected': ''}}>No</option>
       </select>
    </div>

    <div class="ss-file-choose-sec bls-container {{ (empty($blsCer)) ? 'd-none': ''}}">
        <div class="ss-form-group fileUploadInput">
            <label>Upload Doc</label>
            <input type="file"  name="bls">
            <button>Choose File</button>
        </div>
    </div>

    <div class="ss-form-group">
        <label>ACLS </label>
        <select name="acls_cer" data-type="acls-container" onchange="hide_show_select(this)">
            <option value="">Select</option>
            <option value="Yes" {{ (!empty($aclsCer)) ? 'selected': ''}}>Yes</option>
            <option value="No" {{ (empty($aclsCer)) ? 'selected': ''}}>No</option>
        </select>
    </div>


    <div class="ss-file-choose-sec acls-container {{ (empty($aclsCer)) ? 'd-none': ''}}">
        <div class="ss-form-group fileUploadInput">
            <label>Upload Doc</label>
            <input type="file"  name="acls">
            <button>Choose File</button>
        </div>
    </div>


    <div class="ss-form-group">
        <label>PALS </label>
        <select name="pals_cer" data-type="pals-container" onchange="hide_show_select(this)">
            <option value="">Select</option>
            <option value="Yes" {{ (!empty($palsCer)) ? 'selected': ''}}>Yes</option>
            <option value="No" {{ (empty($palsCer)) ? 'selected': ''}}>No</option>
        </select>
    </div>

    <div class="ss-file-choose-sec pals-container {{ (empty($palsCer)) ? 'd-none': ''}}">
        <div class="ss-form-group fileUploadInput">
            <label>Upload Doc</label>
            <input type="file" name="pals">
            <button>Choose File</button>
        </div>
    </div>


    <div class="ss-form-group">
        <label>Any Other </label>
        <input type="text"  value="{{$model->other_certificate_name}}" name="other_certificate_name" placeholder="Others">
    </div>

    <div class="ss-file-choose-sec">
        <div class="ss-form-group fileUploadInput">
            <label>Upload Doc</label>
            <input type="file" name="other">
            <button>Choose File</button>
        </div>
    </div>

     <div class="ss-prsn-form-btn-sec">
       <button type="button" class="ss-prsnl-skip-btn" id="skip-button" data-href="{{route('references')}}" onclick="redirect_to(this)"> Skip </button>
       <button type="text" class="ss-prsnl-save-btn"> Save </button>
     </div>

      <div>
    </div>
</form>
@stop

@push('form-js')
<script>

</script>

@endpush
