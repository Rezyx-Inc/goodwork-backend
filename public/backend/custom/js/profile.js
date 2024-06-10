function ajaxindicatorstart() {
    if (jQuery('body').find('#resultLoading').attr('id') != 'resultLoading') {
        jQuery('body').append('<div id="resultLoading" style="display:none"><div><i style="font-size: 46px;color: #e86432;" class="fa fa-spinner fa-spin fa-2x fa-fw" aria-hidden="true"></i></div><div class="bg"></div></div>');
    }
    jQuery('#resultLoading').css({
        'width': '100%',
        'height': '100%',
        'position': 'fixed',
        'z-index': '10000000',
        'top': '0',
        'left': '0',
        'right': '0',
        'bottom': '0',
        'margin': 'auto'
    });
    jQuery('#resultLoading .bg').css({
        'background': '#ffffff',
        'opacity': '0.8',
        'width': '100%',
        'height': '100%',
        'position': 'absolute',
        'top': '0'
    });
    jQuery('#resultLoading>div:first').css({
        'width': '100%',
        'height': '50vh',
        'text-align': 'center',
        'position': 'fixed',
        'top': '0',
        'left': '0',
        'right': '0',
        'bottom': '0',
        'margin': 'auto',
        'font-size': '16px',
        'z-index': '10',
        'color': '#ffffff'
    });
    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeIn(300);
    jQuery('body').css('cursor', 'wait');
}

function ajaxindicatorstop() {
    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeOut(300);
    jQuery('body').css('cursor', 'default');
}

$(document).ready(function () {

    $('#update-profile-form').on('submit', function (event) {
        event.preventDefault();
        var form = $('#update-profile-form');
        // Remove parsley error messages and styling from form elements
        form.find('.parsley-error').removeClass('parsley-error');
        form.find('.parsley-errors-list').remove();
        form.find('.parsley-custom-error-message').remove();
        ajaxindicatorstart();
        var url = $(this).attr('action');
        var data = new FormData($(this)[0]);

        let profile_picture = $('#imageUpload')[0]
        if (profile_picture.files.length > 0) {
            let file = profile_picture.files[0];
            data.append('profile_picture', file, file.name);
        }
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
                }
            },
            error: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
                // Reset any previous errors
                $('#update-profile-form').parsley().reset();

                if (resp.responseJSON && resp.responseJSON.errors) {
                    var errors = resp.responseJSON.errors;

                     // Loop through the errors and add classes and error messages
                    $.each(errors, function (field, messages) {
                        // Find the input element for the field
                        var inputElement = $('#update-profile-form').find('[name="' + field + '"]');

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


    $('#change-password-form').on('submit', function (event) {
        event.preventDefault();
        var form = $('#change-password-form');
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
                    form[0].reset();
                }
            },
            error: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
                // Reset any previous errors
                $('#change-password-form').parsley().reset();

                if (resp.responseJSON && resp.responseJSON.errors) {
                    var errors = resp.responseJSON.errors;

                     // Loop through the errors and add classes and error messages
                    $.each(errors, function (field, messages) {
                        // Find the input element for the field
                        var inputElement = $('#change-password-form').find('[name="' + field + '"]');

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

    $('#create-crud-form').submit(function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('').closest('.form-group').removeClass('has-error');
        var url = $(this).attr('action');
        var data = new FormData($(this)[0]);
        // console.log(data);return false;
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
                notie.alert({
                    type: 'success',
                    text: '<i class="fa fa-check"></i> '+resp.msg,
                    time: 3
                });
                $('#create-crud-form')[0].reset();

            },
            error: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
                $.each(resp.responseJSON.errors, function (key, val) {
                    $('#create-crud-form').find('[name="' + key + '"]').closest('.form-group').find('.help-block').html(val[0]);
                    $('#create-crud-form').find('[name="' + key + '"]').closest('.form-group').addClass('has-error');
                });
            }
        })
    });

    $('#update-crud-form').submit(function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('').closest('.form-group').removeClass('has-error');
        var url = $(this).attr('action');
        var data = new FormData($(this)[0]);
        // console.log(data);return false;
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
                notie.alert({
                    type: 'success',
                    text: '<i class="fa fa-check"></i> '+resp.msg,
                    time: 3
                });
                // $('#update-crud-form')[0].reset();

            },
            error: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
                $.each(resp.responseJSON.errors, function (key, val) {
                    $('#update-crud-form').find('[name="' + key + '"]').closest('.form-group').find('.help-block').html(val[0]);
                    $('#update-crud-form').find('[name="' + key + '"]').closest('.form-group').addClass('has-error');
                });
            }
        })
    });

});
