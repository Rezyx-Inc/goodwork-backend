@extends('layouts.profile')
@section('mytitle', 'My Profile')

@section('form')
<form method="post" action="{{route('vaccination.store')}}" class="no-redirect-form">
    <div class="ss-persnl-frm-hed">
      <p><span><img src="{{URL::asset('frontend/img/my-per--con-vaccine.png')}}" /></span>Vaccinations & Immunizations</p>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

        <div class="ss-form-group">
            <label>COVID Vaccines </label>
            <select name="covid_vac"  data-type="covid-container" onchange="hide_show_select(this)">
                <option value="">Did you get the COVID Vaccines?</option>
                <option value="Yes" {{ (!empty($covidVac)) ? 'selected': ''}}>Yes</option>
                <option value="No"{{ (empty($covidVac)) ? 'selected': ''}}>No</option>
            </select>
        </div>

        <div class="covid-container {{ (empty($covidVac)) ? 'd-none': ''}}">
           <div class="ss-file-choose-sec">
                <div class="ss-form-group fileUploadInput">
                    <label>Upload File</label>
                    <input type="file" name="covid"/>
                    <button>Choose File</button>
                </div>
           </div>

            <div class="ss-form-group">
                <label>COVID Vaccine Date</label>
                <input type="date"  value="{{ (!empty($covidVac)) ? $covidVac->using_date: null}}" name="covid_date">
            </div>
        </div>







        <div class="ss-form-group">
            <label>Flu Vaccines </label>
            <select name="flu_vac"  data-type="flu-container" onchange="hide_show_select(this)">
                <option value="">Did you get the Flu Vaccines?</option>
                <option value="Yes" {{ (!empty($fluVac)) ? 'selected': ''}}>Yes</option>
                <option value="No"{{ (empty($fluVac)) ? 'selected': ''}}>No</option>
            </select>
       </div>
       <div class="flu-container {{ (empty($fluVac)) ? 'd-none': ''}}">

            <div class="ss-file-choose-sec">
                <div class="ss-form-group fileUploadInput">
                    <label>Upload File</label>
                    <input type="file" name="flu"/>
                    <button>Choose File</button>
                </div>
            </div>



            <div class="ss-form-group">
                <label>Flu Vaccine Date</label>
                <input type="date"  value="{{ (!empty($fluVac)) ? $fluVac->using_date: null}}" name="flu_date">
            </div>
       </div>

        <div class="ss-prsn-form-btn-sec">
            <button type="button" class="ss-prsnl-skip-btn" id="skip-button" data-href="{{route('references')}}" onclick="redirect_to(this)"> Skip </button>
            <button type="text" class="ss-prsnl-save-btn"> Save </button>
        </div>
  </form>
@stop

@push('form-js')
@php
$vaccinations = explode(',', $model->vaccinations);
$certificate = explode(',', $model->certificate);

$vaccinations = [];
$certificate = [];
if(!empty($model->vaccinations))
{
    $vaccinations = explode(',', $model->vaccinations);
}
if(!empty($model->certificate))
{
    $certificate = explode(',', $model->certificate);
}
@endphp
<script>

var vac_content = [];
var cer_content = [];

@if(count($vaccinations))
@for($i=0; $i<count($vaccinations);$i++)
vac_content.push('{{$vaccinations[$i]}}');
@endfor
@endif

@if(count($certificate))
@for($i=0; $i<count($certificate);$i++)
cer_content.push('{{$certificate[$i]}}');
@endfor
@endif

var dynamic_elements = {
    vac : {
        id : '#vaccination',
        name: 'Vaccination',
        listing_class : '.vaccination-content',
        items: vac_content
    },
    cer : {
        id : '#certificate',
        name: 'Certificate',
        listing_class : '.certifications-content',
        items: cer_content
    }
}

function add_element(obj)
{
    const type = $(obj).data('type');
    if (dynamic_elements.hasOwnProperty(type)) {
        let element, id, value,name;
        element = dynamic_elements[type];
        id = element.id;
        name =  element.name;
        value = $(id).val();

        if (!value) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the '+name+' please.',
                time: 3
            });
        }else{
            if (!element.items.includes(value)) {
                element.items.push($(id).val());
                console.log(element.items);
                list_elements(type);
            }
            $(id).val('');
        }
    }
}

function remove_element(obj)
{
    const type = $(obj).data('type');
    if (dynamic_elements.hasOwnProperty(type)) {
        let element = dynamic_elements[type];

        if (element.items.includes($(obj).data('key'))) {
            const elementToRemove = $(obj).data('key');
            const newArray = element.items.filter(item => item !== elementToRemove);
            element.items = newArray;
            // $(obj).parent().parent().parent().remove();
            list_elements(type);
            console.log(element.items);
        }
    }

}

function list_elements(type)
{
    if (dynamic_elements.hasOwnProperty(type)) {
        const element = dynamic_elements[type];
        if (element.items.length) {
            str = '';
            element.items.forEach(function(value, index) {
                str += '<div class="item form-group">';
                str += '<label class="col-form-label col-md-3 col-sm-3 label-align"></label>';
                str += '<div class="col-md-1 col-sm-1 ">';
                str += '<label class="col-form-label label-align"> '+(index+1)+'.</label>';
                str += '</div>';
                str += '<div class="col-md-4 col-sm-4">';
                str += '<label class="col-form-label label-align"> '+value+'</label>';
                str += '</div>';
                str += '<div class="col-md-2 col-sm-1">';
                str += '<label class="col-form-label label-align" title="delete"><button type="button" data-type="'+type+'" data-key="'+value+'" onclick="remove_element(this)"><i class="fa fa-trash"></i></button></label>';
                str += '</div>';
                str += '</div>';
            });
            $(element.listing_class).html(str);
        }
    }
}
</script>

@endpush
