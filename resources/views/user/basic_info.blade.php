@extends('layouts.profile')
@section('mytitle', 'My Profile')

@section('form')
<form method="post" action="{{route('my-profile.store')}}" id="basic-info-form">
    <div class="ss-persnl-frm-hed">
      <p><span><img src="{{URL::asset('frontend/img/my-per--con-user.png')}}" /></span>Professional Information</p>
      <div class="progress">
         <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
     </div>
   </div>

     <div class="ss-form-group">
       <label>Title</label>
       <input type="text"  value="{{$model->credential_title}}" name="credential_title" placeholder="What should they call you?">
       <span class="help-block"></span>
     </div>
     <div class="ss-form-group">
       <label>Profession</label>
       <select name="highest_nursing_degree" onchange="get_speciality(this)">
         <option value="">Select</option>
            @foreach($professions as $v)
                <option value="{{$v->title}}" data-id="{{$v->id}}" {{ ($model->highest_nursing_degree == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
            @endforeach
       </select>
       <span class="help-block"></span>
     </div>
     <div class="ss-form-group ss-prsnl-frm-specialty">
       <label>Specialty and Experience</label>
       <div class="ss-speilty-exprnc-add-list speciality-content">
            {{-- <ul>
                <li>ICU</li>
                <li>4 Years</li>
                <li><button><img src="{{URL::asset('frontend/img/delete-img.png')}}" /></button></li>
            </ul>
            <ul>
                <li>L&D</li>
                <li>2 Years</li>
                <li><button><img src="{{URL::asset('frontend/img/delete-img.png')}}" /></button></li>
            </ul> --}}
        </div>
       <ul>
            <li>
            <select name="speciality" id="speciality">
                <option value="">Select Specialty</option>
            </select>
                <input type="text"  name="experience" id="experience" placeholder="Enter Experience in years">
            </li>
            <li><div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="add_speciality(this)"><i class="fa fa-plus" aria-hidden="true"></i></a></div></li>
        </ul>
     </div>

     <div class="ss-professional-license-div">
       <h6>Professional License</h6>

       <div class="ss-form-group">
       <label>License Type</label>
        <select name="license_type">
            <option value="">Select</option>
            @foreach($license_types as $v)
                <option value="{{$v->title}}"  {{ ($model->license_type == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
            @endforeach
        </select>
        <span class="help-block"></span>
     </div>


     <div class="ss-form-group">
       <label>License State</label>

       <select name="nursing_license_state">
        <option value="">Select</option>
        @foreach($us_states as $v)
            <option value="{{$v->id}}" {{ ($model->nursing_license_state == $v->id) ? 'selected' :'' }}>{{$v->name}}</option>
        @endforeach
        </select>
        <span class="help-block"></span>
     </div>

     <div class="ss-form-group">
       <label>Expiration Date</label>
       <input type="date"  value="{{$model->license_expiry_date}}" name="license_expiry_date" placeholder="Month Day, Year">
       <span class="help-block"></span>
     </div>
     </div>

     <div class="ss-ss-licens-check">
       <input type="checkbox" value="1" name="compact_license" {{ ($model->compact_license == '1') ? 'checked': '' }}>
       <label>This is a compact license</label>

     </div>
     <div class="ss-prsn-form-btn-sec">
       <button type="button" class="ss-prsnl-skip-btn" id="skip-button" data-href="{{route('info-required')}}" onclick="redirect_to(this)"> Skip </button>
       <button type="submit" class="ss-prsnl-save-btn"> Save & Next </button>
     </div>

      <div>
    </div>
</form>
@stop

@push('form-js')
@php
$specialty = explode(',', $model->specialty);
$experience = explode(',', $model->experience);
$vaccinations = explode(',', $model->vaccinations);
$certificate = explode(',', $model->certificate);

$specialty = [];
$experience = [];
if(!empty($model->specialty))
{
    $specialty = explode(',', $model->specialty);
}
if(!empty($model->experience))
{
    $experience = explode(',', $model->experience);
}
@endphp
<script>


var speciality = {};

@if(count($specialty))
@for($i=0; $i<count($specialty);$i++)
speciality['{{$specialty[$i]}}'] = '{{$experience[$i]}}';
@endfor
@endif
console.log(speciality);

function add_speciality(obj) {
    if (!$('#speciality').val()) {
        notie.alert({
            type: 'error',
            text: '<i class="fa fa-check"></i> Select the speciality please.',
            time: 3
        });
    }else if(!$('#experience').val())
    {
        notie.alert({
            type: 'error',
            text: '<i class="fa fa-check"></i> Enter total year of experience.',
            time: 3
        });
    }else{
        if (!speciality.hasOwnProperty($('#speciality').val())) {
            speciality[$('#speciality').val()] = $('#experience').val();
            $('#experience').val('');
            $('#speciality').val('');
            list_specialities();
        }
        console.log(speciality);
    }
}

function remove_speciality(obj) {
    if (speciality.hasOwnProperty($(obj).data('key'))) {
        delete speciality[$(obj).data('key')];
        // $(obj).parent().parent().parent().remove();
        list_specialities();
        console.log(speciality);
    }
}

function list_specialities()
{
    // $('.speciality-content').empty();
    var str = '';
    for (const key in speciality) {
        if (speciality.hasOwnProperty(key)) {
            const value = speciality[key];
            str += '<ul>';
            str += '<li>'+key+'</li>';
            str += '<li>'+value+' Years</li>';
            str += '<li><button type="button" data-key="'+key+'" onclick="remove_speciality(this)"><img src="'+full_path+'frontend/img/delete-img.png'+'" /></button></li>';
            str += '</ul>';
        }
    }
    $('.speciality-content').html(str);
}


$(document).ready(function () {
    list_specialities();
    $('#basic-info-form').on('submit', function (event) {
        var form = $(this);
        $('.help-block').html('').closest('.has-error').removeClass('has-error');
        event.preventDefault();
        ajaxindicatorstart();
        const url = form.attr('action');
        const specialities = Object.keys(speciality).join(',');
        const experiences = Object.values(speciality).join(',');
        var data = new FormData(form[0]);
        data.set('specialty', specialities);
        data.set('experience', experiences);
        console.log(data);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
                if (resp.success) {
                    notie.alert({
                        type: 'success',
                        text: '<i class="fa fa-check"></i> ' + resp.msg,
                        time: 3
                    });
                    setTimeout(() => {
                        window.location.href = $('#skip-button').data('href');
                    }, 3000);
                }
            },
            error: function (resp) {
                ajaxindicatorstop();
                console.log(resp);
                $.each(resp.responseJSON.errors, function (key, val) {
                    console.log(val[0]);
                    form.find('[name="' + key + '"]').closest('.ss-form-group').find('.help-block').html(val[0]);
                    form.find('[name="' + key + '"]').closest('.ss-form-group').addClass('has-error');
                });

            }
        })
    });

});
</script>
@endpush
