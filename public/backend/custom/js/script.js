/* global notie, full_path, grecaptcha, map, getLat, getLong */
function submit_form(form_id)
{
    $('#'+form_id).submit();
}

$(document).ready(function () {

$('#signup-form-submit').submit(function (event) {
    event.preventDefault();
    ajaxindicatorstart();
    $('.help-block').html('').closest('.mb-3').removeClass('has-error');
    var url = $(this).attr('action');
    var data = new FormData($(this)[0]);
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
                $('#signup-form-submit')[0].reset();
                setTimeout(function(){
                    window.location.href = resp.link;
                },4000);
            }
        },
        error: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
            $.each(resp.responseJSON.errors, function (key, val) {
                $('#signup-form-submit').find('input[name="' + key + '"]').closest('.mb-3').find('.help-block').html(val[0]);
                $('#signup-form-submit').find('input[name="' + key + '"]').closest('.mb-3').addClass('has-error');
                if (key == 'terms') {
                    $('#signup-form-submit').find('input[name="' + key + '"]').closest('.form-check').find('.help-block').html(val[0]);
                    $('#signup-form-submit').find('input[name="' + key + '"]').closest('.form-check').addClass('has-error');
                }
            });
        }
    })
});


$('#login-form').submit(function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.invalid-feedback').html('').closest('.is-invalid').removeClass('is-invalid');
        var url = $(this).attr('action');
        // alert(url);
        var data = new FormData($(this)[0]);
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
                     setTimeout(function(){
                         window.location.href = resp.link;
                     },3000);
                }else{
                    notie.alert({
                        type: 'error',
                        text: '<i class="fa fa-check"></i> ' + resp.msg,
                        time: 5
                    });
                }
            },
            error: function (resp) {
                ajaxindicatorstop();
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Wrong email or password' ,
                    time: 5
                });
                console.log(resp);
                $.each(resp.responseJSON.errors, function (key, val) {
                    console.log(val[0]);
                    $('#login-form').find('[name="' + key + '"]').closest('.col-12').find('.invalid-feedback').html(val[0]);
                    $('#login-form').find('[name="' + key + '"]').addClass('is-invalid');
                });
            }
        })
});


$('#keyword-form').on('submit', function (event) {
    event.preventDefault();
    var form = $('#keyword-form');
    // Remove parsley error messages and styling from form elements
    form.find('.parsley-error').removeClass('parsley-error');
    form.find('.parsley-errors-list').remove();
    form.find('.parsley-custom-error-message').remove();
    ajaxindicatorstart();
    var url = $(this).attr('action');
    var data = new FormData($(this)[0]);
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
                $('#keyword-form')[0].reset();
            }
        },
        error: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
            // Reset any previous errors
            $('#keyword-form').parsley().reset();

            if (resp.responseJSON && resp.responseJSON.errors) {
                var errors = resp.responseJSON.errors;

                 // Loop through the errors and add classes and error messages
                $.each(errors, function (field, messages) {
                    // Find the input element for the field
                    var inputElement = $('#keyword-form').find('[name="' + field + '"]');

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

$('#update-keyword-form').on('submit', function (event) {
    event.preventDefault();
    $('#update-keyword-form').parsley().reset();
    var form = $('#update-keyword-form');
    // Remove parsley error messages and styling from form elements
    form.find('.parsley-error').removeClass('parsley-error');
    form.find('.parsley-errors-list').remove();
    form.find('.parsley-custom-error-message').remove();
    ajaxindicatorstart();
    var url = $(this).attr('action');
    var data = new FormData($(this)[0]);
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
            $('#update-keyword-form').parsley().reset();

            if (resp.responseJSON && resp.responseJSON.errors) {
                var errors = resp.responseJSON.errors;

                 // Loop through the errors and add classes and error messages
                $.each(errors, function (field, messages) {
                    // Find the input element for the field
                    var inputElement = $('#update-keyword-form').find('[name="' + field + '"]');

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


$('#job-reference-form').on('submit', function (event) {
    event.preventDefault();
    var form = $('#job-reference-form');
    // Remove parsley error messages and styling from form elements
    form.find('.parsley-error').removeClass('parsley-error');
    form.find('.parsley-errors-list').remove();
    form.find('.parsley-custom-error-message').remove();
    ajaxindicatorstart();
    var url = $(this).attr('action');
    var data = new FormData($(this)[0]);
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
                    location.reload();
                }, 3000);
            }
        },
        error: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
            // Reset any previous errors
            $('#job-reference-form').parsley().reset();

            if (resp.responseJSON && resp.responseJSON.errors) {
                var errors = resp.responseJSON.errors;

                 // Loop through the errors and add classes and error messages
                $.each(errors, function (field, messages) {
                    // Find the input element for the field
                    var inputElement = $('#job-reference-form').find('[name="' + field + '"]');

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


$('#vaccination-form').on('submit', function (event) {
    event.preventDefault();
    var form = $('#vaccination-form');
    // Remove parsley error messages and styling from form elements
    form.find('.parsley-error').removeClass('parsley-error');
    form.find('.parsley-errors-list').remove();
    form.find('.parsley-custom-error-message').remove();
    ajaxindicatorstart();
    var url = $(this).attr('action');
    var data = new FormData($(this)[0]);
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
                    location.reload();
                }, 3000);
            }
        },
        error: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
            // Reset any previous errors
            $('#vaccination-form').parsley().reset();

            if (resp.responseJSON && resp.responseJSON.errors) {
                var errors = resp.responseJSON.errors;

                 // Loop through the errors and add classes and error messages
                $.each(errors, function (field, messages) {
                    // Find the input element for the field
                    var inputElement = $('#vaccination-form').find('[name="' + field + '"]');

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


$('#certification-form').on('submit', function (event) {
    event.preventDefault();
    var form = $('#certification-form');
    // Remove parsley error messages and styling from form elements
    form.find('.parsley-error').removeClass('parsley-error');
    form.find('.parsley-errors-list').remove();
    form.find('.parsley-custom-error-message').remove();
    ajaxindicatorstart();
    var url = $(this).attr('action');
    var data = new FormData($(this)[0]);
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
                    location.reload();
                }, 3000);
            }
        },
        error: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
            // Reset any previous errors
            $('#certification-form').parsley().reset();

            if (resp.responseJSON && resp.responseJSON.errors) {
                var errors = resp.responseJSON.errors;

                 // Loop through the errors and add classes and error messages
                $.each(errors, function (field, messages) {
                    // Find the input element for the field
                    var inputElement = $('#certification-form').find('[name="' + field + '"]');

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


$('#skills-form').on('submit', function (event) {
    event.preventDefault();
    var form = $('#skills-form');
    // Remove parsley error messages and styling from form elements
    form.find('.parsley-error').removeClass('parsley-error');
    form.find('.parsley-errors-list').remove();
    form.find('.parsley-custom-error-message').remove();
    ajaxindicatorstart();
    var url = $(this).attr('action');
    var data = new FormData($(this)[0]);
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
                // setTimeout(() => {
                //     location.reload();
                // }, 3000);
            }
        },
        error: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
            // Reset any previous errors
            $('#skills-form').parsley().reset();

            if (resp.responseJSON && resp.responseJSON.errors) {
                var errors = resp.responseJSON.errors;

                 // Loop through the errors and add classes and error messages
                $.each(errors, function (field, messages) {
                    // Find the input element for the field
                    var inputElement = $('#skills-form').find('[name="' + field + '"]');

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

function copyToClipboard(text) {
navigator.clipboard.writeText(text)
    .then(function() {
    console.log('Text copied to clipboard');
    })
    .catch(function(error) {
    console.error('Unable to copy text to clipboard:', error);
    });
}

function get_states(obj)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: full_path+'get-states',
        type: 'POST',
        dataType: 'json',
        // processData: false,
        // contentType: false,
        data: {
            country_id: $(obj).val()
        },
        success: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
            if (resp.success) {
                $('#state').html(resp.content);
            }
        },
        error: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
        }
    });
}


function get_cities(obj)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: full_path+'get-cities',
        type: 'POST',
        dataType: 'json',
        // processData: false,
        // contentType: false,
        data: {
            state_id: $(obj).val()
        },
        success: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
            if (resp.success) {
                $('#city').html(resp.content);
            }
        },
        error: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
        }
    });
}

function get_speciality(obj, empty_content=true)
{
    speciality = {};
    if (empty_content) {
        $('.speciality-content').empty();
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: full_path+'get-speciality',
        type: 'POST',
        dataType: 'json',
        // processData: false,
        // contentType: false,
        data: {
            kid: $(obj).find('option:selected').data('id')
        },
        success: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
            if (resp.success) {
                $('#speciality').html(resp.content);
            }
        },
        error: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
        }
    });

}

function references(obj)
{
    let value = $(obj).val();
    let parent = $('.job-references');
    let childElements = parent.children();
    // old_references_count = childElements.length;
    let content = '';
    let suffix;
    let index = 1;
    console.log('Number: '+ old_references_count);
    console.log('Value: '+ value);
    if (old_references_count > 0) {
        index = old_references_count+1;
    }
    if(old_references_count > value)
    {
        total_to_delete = old_references_count - value;
        // Delete the last two elements
        for (let i = 0; i < total_to_delete ; i++) {
            childElements[old_references_count - 1].remove();
            old_references_count--;
        }
    }else{
        console.log('Index: '+ index);
        for (index ; index <= value; index++) {
            content += '<div class="reference-details">';
            content += '<div class="item form-group d-flex justify-content-center">';
            if (index == 1) {
                suffix = 'st';
            }else if(index == 2)
            {
                suffix = 'nd';
            }else if(index == 3)
            {
                suffix = 'rd';
            }
            else {
                suffix = 'th';
            }
            content += '<label class="col-form-label col-md-7 col-sm-7 label-align">Details of '+index+suffix+' Reference</label>';
            content += '<div class="col-md-5 col-sm-5">';
            content += '</div>';
            content += '</div>';
            content += '<div class="item form-group">';
            content += '<label class="col-form-label col-md-3 col-sm-3 label-align"> Name</label>';
            content += '<div class="col-md-6 col-sm-6">';
            content += '<input type="text" name="name[]" class="form-control" required="required" placeholder=""/>';
            content += '</div>';
            content += '</div>';
            content += '<div class="item form-group">';
            content += '<label class="col-form-label col-md-3 col-sm-3 label-align"> Email</label>';
            content +=  '<div class="col-md-6 col-sm-6">';
            content +=  '<input type="email" name="email[]" class="form-control" placeholder=""/>';
            content += '</div>';
            content += '</div>';
            content +='<div class="item form-group">';
            content += '<label class="col-form-label col-md-3 col-sm-3 label-align"> Phone</label>';
            content +='<div class="col-md-6 col-sm-6">';
            content += '<input type="text" name="phone[]" class="form-control"/>';
            content +='</div>';
            content +='</div>';
            content +='<div class="item form-group">';
            content +='<label class="col-form-label col-md-3 col-sm-3 label-align"> Date Refered</label>';
            content +='<div class="col-md-6 col-sm-6">';
            content +='<input type="date" name="date_referred[]" class="form-control" placeholder=""/>';
            content +='</div>';
            content +='</div>';
            content +='<div class="item form-group">';
            content += '<label class="col-form-label col-md-3 col-sm-3 label-align"> Min Title Of Reference</label>';
            content +='<div class="col-md-6 col-sm-6">';
            content += '<input type="text" name="min_title_of_reference[]" class="form-control" placeholder=""/>';
            content +='</div>';
            content +='</div>';
            content +='<div class="item form-group">';
            content +='<label class="col-form-label col-md-3 col-sm-3 label-align"> Recency Of Reference</label>';
            content +='<div class="col-md-6 col-sm-6">';
            content += '<select name="recency_of_reference[]" id="" class="form-control">';
            content +=  '<option value="Yes" selected>Yes</option>';
            content +=  '<option value="No">No</option>';
            content += '</select>';

            content +='</div>';
            content +='</div>';
            content +='<div class="item form-group">';
            content += '<label class="col-form-label col-md-3 col-sm-3 label-align"> Upload DOC</label>';
            content += '<div class="col-md-6 col-sm-6">';
            content +=  '<input type="file" name="image[]" class="form-control" />';
            content += '</div>';
            content += '</div>';
            content +='</div>';
            old_references_count++;
        }
        parent.append(content);
        console.log('Number after: '+ old_references_count);
    }

}

function hide_show_select(obj) {
    let value, type, container;
    value = $(obj).val();
    type = $(obj).data('type');
    console.log(type);
    container = $('.'+type);

    if (value === 'Yes') {
        container.removeClass('d-none');
    }else{
        container.addClass('d-none');
    }
}
