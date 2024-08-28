/* global notie, full_path, grecaptcha, map, getLat, getLong */
function submit_form(form_id)
{
    $('#'+form_id).submit();
}

$(document).ready(function () {

    $('#phone_number').mask('(999) 999-9999');

$('#signup-form-submit').submit(function (event) {
    event.preventDefault();
    ajaxindicatorstart();
    let form = $(this);
    $('.help-block').html('').closest('.has-error').removeClass('has-error');
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
                 form[0].reset();
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
            console.log(resp);
            $.each(resp.responseJSON.errors, function (key, val) {
                console.log(val[0]);
                form.find('[name="' + key + '"]').closest('.ss-form-group').find('.help-block').html(val[0]);
                form.find('[name="' + key + '"]').closest('.ss-form-group').addClass('has-error');
            });
        }
    })
});


$('#login-form').submit(function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        let form = $(this);
        $('.help-block').html('').closest('.has-error').removeClass('has-error');
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
                // notie.alert({
                //     type: 'error',
                //     text: '<i class="fa fa-check"></i> Wrong email or password' ,
                //     time: 5
                // });
                console.log(resp);
                $.each(resp.responseJSON.errors, function (key, val) {
                    console.log(val[0]);
                    form.find('[name="' + key + '"]').closest('.ss-form-group').find('.help-block').html(val[0]);
                    form.find('[name="' + key + '"]').closest('.ss-form-group').addClass('has-error');
                });
            }
        })
});

$('#counter-offer-form').submit(function (event) {
    event.preventDefault();
    ajaxindicatorstart();
    let form = $(this);
    $('.help-block').html('').closest('.has-error').removeClass('has-error');
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
            // notie.alert({
            //     type: 'error',
            //     text: '<i class="fa fa-check"></i> Wrong email or password' ,
            //     time: 5
            // });
            console.log(resp);
            $.each(resp.responseJSON.errors, function (key, val) {
                console.log(val[0]);
                form.find('[name="' + key + '"]').closest('.ss-form-group').find('.help-block').html(val[0]);
                form.find('[name="' + key + '"]').closest('.ss-form-group').addClass('has-error');
            });
        }
    })
});

$('#otp-form').submit(function (event) {
    event.preventDefault();
    ajaxindicatorstart();
    let form = $(this);
    $('.help-block').html('').closest('.has-error').removeClass('has-error');
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
                text: '<i class="fa fa-check"></i> OOPS! something went wrong.' ,
                time: 5
            });
            console.log(resp);
            $.each(resp.responseJSON.errors, function (key, val) {
                console.log(val[0]);
                form.find('[name="' + key + '"]').closest('.ss-form-group').find('.help-block').html(val[0]);
                form.find('[name="' + key + '"]').closest('.ss-form-group').addClass('has-error');
            });
        }
    })
});


$('.modal-form').submit(function (event) {
    event.preventDefault();
    ajaxindicatorstart();
    let form = $(this);
    $('.help-block').html('').closest('.has-error').removeClass('has-error');
    let url = $(this).attr('action');
    let modal = $(this).attr('id').split('_form').join('');
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
                $('#'+modal).modal('hide');
                notie.alert({
                     type: 'success',
                     text: '<i class="fa fa-check"></i> ' + resp.msg,
                     time: 3
                 });
                 form[0].reset();
                    setTimeout(function(){
                        location.reload();
                    },2000);
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
                text: '<i class="fa fa-check"></i> OOPS! something went wrong.' ,
                time: 5
            });
            console.log(resp);
            $.each(resp.responseJSON.errors, function (key, val) {
                console.log(val[0]);
                form.find('[name="' + key + '"]').closest('.ss-form-group').find('.help-block').html(val[0]);
                form.find('[name="' + key + '"]').closest('.ss-form-group').addClass('has-error');
            });
        }
    })
});

$('#ref-modal-form').submit(function (event) {
    event.preventDefault();
    ajaxindicatorstart();
    let form = $(this);
    $('.help-block').html('').closest('.has-error').removeClass('has-error');
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
                $('#references-modal-form-btn').click();
                notie.alert({
                     type: 'success',
                     text: '<i class="fa fa-check"></i> ' + resp.msg,
                     time: 3
                 });
                 form[0].reset();
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
            // notie.alert({
            //     type: 'error',
            //     text: '<i class="fa fa-check"></i> Wrong email or password' ,
            //     time: 5
            // });
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
            state_id: $(obj).find('option:selected').data('id')
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
            kid: $(obj).find('option:selected').val()
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

function get_dropdown(obj)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        xhrFields: {
            withCredentials: true
        }
    });
    $.ajax({
        url: full_path+'get-dropdown',
        type: 'POST',
        dataType: 'json',
        // processData: false,
        // contentType: false,
        data: {
            filter: $(obj).data('filter')
        },
        success: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
            if (resp.success) {
                console.log('#'+$(obj).data('target')+'_modal_form');
                $('#'+$(obj).data('target')+'_modal_form').find('select').html(resp.content);
            }
        },
        error: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
        }
    });

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

function save_jobs(obj, reload_page=false)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: full_path+'worker/add-save-jobs',
        type: 'POST',
        dataType: 'json',
        // processData: false,
        // contentType: false,
        data: {
            jid: $(obj).data('id')
        },
        success: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
            if (resp.success) {
                notie.alert({
                    type: 'success',
                    text: '<i class="fa fa-check"></i> ' + resp.msg,
                    time: 3
                });
                $(obj).find('img').attr('src', resp.img);
                if (reload_page) {
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                }
            }
        },
        error: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
        }
    });
}


function apply_on_jobs(obj,worked_at_facility_before,reload_page = true)
{
    console.log($(obj).data('id'));
    
    
    $.confirm({
        title: 'Apply on the Job',
        content: 'Are you sure?',
        type: 'green',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-green',
                action: function () {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                            $.ajax({
                                url: full_path + 'worker/apply-on-job',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    jid: $(obj).data('id'),
                                    worked_at_facility_before: worked_at_facility_before,
                                },
                                success: function (resp) {
                                    console.log(resp);
                                    ajaxindicatorstop();
                                    if (resp.success) {
                                        notie.alert({
                                            type: 'success',
                                            text: '<i class="fa fa-check"></i> ' + resp.msg,
                                            time: 3
                                        });
                                        if (reload_page) {
                                            setTimeout(() => {
                                                location.reload();
                                            }, 3000);
                                        }else{
                                            $(obj).remove();
                                        }
                                    }
                                },
                                error: function (resp) {
                                    console.log(resp);
                                    ajaxindicatorstop();
                                }
                            });
                    
                   
                }
            },
            cancel: function () {}
        }
    })
}


function match_worker_with_jobs_update(data_to_send)
{
    
    console.log(data_to_send);
    
    $.confirm({
        title: 'Save Your Information',
        content: 'Are you sure?',
        type: 'green',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-green',
                action: function () {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: full_path + 'worker/match-worker-job',
                        type: 'POST',
                        dataType: 'json',
                        data: data_to_send,
                        success: function (resp) {
                            console.log(resp);
                            ajaxindicatorstop();
                            if (resp.success) {
                                notie.alert({
                                    type: 'success',
                                    text: '<i class="fa fa-check"></i> ' + resp.msg,
                                    time: 3
                                });
                            }
                        },
                        error: function (resp) {
                            console.log(resp);
                            ajaxindicatorstop();
                        }
                    })
                    
                   
                }
            },
            cancel: function () {}
        }
    })
}