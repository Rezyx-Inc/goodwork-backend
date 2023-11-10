@extends('admin::layouts.master')

@section('css')
<style>
    /* Override Bootstrap Tags Input styles */
.bootstrap-tagsinput {
    border-radius: 0;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    vertical-align: top;
    overflow: auto;
    resize: vertical;

}

.bootstrap-tagsinput .tag {
    /* Your custom styles for each tag */
    background:blue;
}

</style>
@stop

@section('content')

<!-- page content -->
<div class="ss-admin-job-edit-mn-sec">
    <div class="row">
        <div class="col-lg-12">
            <div class="ss-adm-jb-ed-hed">
                <ul>
                    <li><a href="#"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>
                    <li><h4>Add New Job</h4></li>
                    <li>
                        <div class="ss-rrol-pop-swtch-dv">
    <ul>
    <li><p>Status</p></li>
    <li><input type="checkbox" hidden="hidden" id="username">
<label class="switch" for="username"></label></li>
</ul>
</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->

@stop
@section('js')
@php
$specialty = [];
$experience = [];
$vaccinations = [];
$certificate = [];
if(!empty($model->preferred_specialty))
{
    $specialty = explode(',', $model->preferred_specialty);
}
if(!empty($model->preferred_experience))
{
    $experience = explode(',', $model->preferred_experience);
}
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


var speciality = {};
var vac_content = [];
var cer_content = [];

@if(count($specialty))
@for($i=0; $i<count($specialty);$i++)
speciality['{{$specialty[$i]}}'] = '{{$experience[$i]}}';
@endfor
@endif
console.log(speciality);

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
    var i = 1;
    for (const key in speciality) {
        if (speciality.hasOwnProperty(key)) {
            const value = speciality[key];
            str += '<div class="item form-group">';
            str += '<label class="col-form-label col-md-3 col-sm-3 label-align"></label>';
            str += '<div class="col-md-1 col-sm-1 ">';
            str += '<label class="col-form-label label-align"> '+(i++)+'.</label>';
            str += '</div>';
            str += '<div class="col-md-2 col-sm-2">';
            str += '<label class="col-form-label label-align"> '+key+'</label>';
            str += '</div>';
            str += '<div class="col-md-2 col-sm-2">';
            str += '<label class="col-form-label label-align">'+value+' </label>';
            str += '</div>';
            str += '<div class="col-md-2 col-sm-1">';
            str += '<label class="col-form-label label-align" title="delete"><button type="button" data-key="'+key+'" onclick="remove_speciality(this)"><i class="fa fa-trash"></i></button></label>';
            str += '</div>';
            str += '</div>';

        }
    }
    $('.speciality-content').html(str);
}



var vaccination = [];
function add_vaccination()
{
    if (!$('#vaccination').val()) {
        notie.alert({
            type: 'error',
            text: '<i class="fa fa-check"></i> Select the Vaccination please.',
            time: 3
        });
    }else{
        if (!vaccination.includes($('#vaccination').val())) {
            vaccination.push($('#vaccination').val());
            list_vaccination();
        }
        $('#vaccination').val('');
        console.log(vaccination);
    }
}

function remove_vaccination(obj)
{
    if (vaccination.includes($(obj).data('key'))) {
        const elementToRemove = $(obj).data('key');
        const newArray = vaccination.filter(item => item !== elementToRemove);
        vaccination = newArray;
        // $(obj).parent().parent().parent().remove();
        list_vaccination();
        console.log(vaccination);
    }
}


function list_vaccination()
{
    if (vaccination.length) {
        str = '';
        vaccination.forEach(function(value, index) {
            str += '<div class="item form-group">';
            str += '<label class="col-form-label col-md-3 col-sm-3 label-align"></label>';
            str += '<div class="col-md-1 col-sm-1 ">';
            str += '<label class="col-form-label label-align"> '+(index+1)+'.</label>';
            str += '</div>';
            str += '<div class="col-md-4 col-sm-4">';
            str += '<label class="col-form-label label-align"> '+value+'</label>';
            str += '</div>';
            str += '<div class="col-md-2 col-sm-1">';
            str += '<label class="col-form-label label-align" title="delete"><button type="button" data-key="'+value+'" onclick="remove_vaccination(this)"><i class="fa fa-trash"></i></button></label>';
            str += '</div>';
            str += '</div>';
        });
        $('.vaccination-content').html(str);
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

/* Job references count */
var old_references_count;
old_references_count = $('.job-references input[name="old_name[]"]').length;
console.log('Number: '+ old_references_count);



$(document).ready(function () {
    list_specialities();
    list_elements('vac');
    list_elements('cer');
    get_speciality($('select[name="profession"]'), false);

    $('#create-job-form').on('submit', function (event) {
        event.preventDefault();
        $('#create-job-form').parsley().reset();
        var form = $('#create-job-form');
        // Remove parsley error messages and styling from form elements
        form.find('.parsley-error').removeClass('parsley-error');
        form.find('.parsley-errors-list').remove();
        form.find('.parsley-custom-error-message').remove();
        ajaxindicatorstart();
        const url = $(this).attr('action');
        const specialities = Object.keys(speciality).join(',');
        const experiences = Object.values(speciality).join(',');
        const vaccinations = dynamic_elements.vac.items.join(',');
        const certificate = dynamic_elements.cer.items.join(',');
        var data = new FormData($(this)[0]);
        data.set('preferred_specialty', specialities);
        data.set('preferred_experience', experiences);
        data.set('vaccinations', vaccinations);
        data.set('certificate', certificate);
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
                        time: 5
                    });
                    setTimeout(() => {
                        window.location.href = resp.link;
                    }, 3000);
                }
            },
            error: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
                // Reset any previous errors
                $('#create-job-form').parsley().reset();

                if (resp.responseJSON && resp.responseJSON.errors) {
                    var errors = resp.responseJSON.errors;

                    // Loop through the errors and add classes and error messages
                    $.each(errors, function (field, messages) {
                        // Find the input element for the field
                        var inputElement = $('#create-job-form').find('[name="' + field + '"]');

                        // Add the parsley-error class to the input element
                        inputElement.addClass('parsley-error');

                        // Display the error messages near the input element
                        $.each(messages, function (index, message) {
                            inputElement.after('<ul class="parsley-errors-list filled"><li class="parsley-required">'+message+'</li></ul>');
                        });
                    });
                }
            }
        })
    });

});
</script>
<script>
    // $('#skills').tagsinput();
    $(document).ready(function () {
        $('#skills').tagsinput();
    });
</script>

@stop
